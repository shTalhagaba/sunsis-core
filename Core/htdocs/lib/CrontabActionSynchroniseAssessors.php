<?php

class CrontabActionSynchroniseAssessors extends CrontabAction {

    public $FirstName;
    public $LastName;
    public $UserName;
    public $Password;
    public $Region;
    public $Email;
    public $Telephone;
    public $Mobile;

    public function __construct() {
        $this->task = 'SynchroniseAssessors';
    }

    public function execute(PDO $link) {
        if (!SystemConfig::get('smartassessor.soap.enabled')) {
            return;
        }

        // Populate tmp_sa_assessors
        $sa = new SmartAssessor($this->read_only);
        $saRecords = $sa->getAssessors();
        $this->_createTempTables($link);
        DAO::multipleRowInsert($link, 'tmp_sa_assessors', $saRecords);

        // Unlink any Sunesis assessor records that no longer have a corresponding record in SmartAssessor
        $this->_unlinkSunesisRecords($link, $sa);

        // Unlink any SmartAssessor assessor records that no longer have a corresponding record in Sunesis
        $this->_unlinkSmartAssessorRecords($link, $sa);

        // Create new records in Smart Assessor
        $this->_createAssessorsInSmartAssessor($link, $sa);

        // Create new records in Sunesis
        $this->_createAssessorsInSunesis($link, $sa);

        // Update existing linked records in both Sunesis and Smart Assessor
        $this->_updateAssessors($link, $sa);
    }

    /**
     * Unlink any Sunesis assessor records that no longer have a corresponding record in SmartAssessor
     * @param PDO $link
     * @param SmartAssessor $sa
     */
    private function _unlinkSunesisRecords(PDO $link, SmartAssessor $sa) {
        $type = User::TYPE_ASSESSOR;
        $type_verifier = User::TYPE_VERIFIER;
        $sql = <<<SQL
SELECT
	users.id,
	users.firstnames,
	users.surname
FROM
	users LEFT OUTER JOIN tmp_sa_assessors
		ON users.smart_assessor_id = tmp_sa_assessors.SmartAssessorId
WHERE
	users.smart_assessor_id IS NOT NULL
	AND tmp_sa_assessors.SunesisId IS NULL
	AND (users.type = {$type} OR users.type = {$type_verifier})
SQL;
        $rs = DAO::query($link, $sql);
        foreach ($rs as $row) {
            $this->log("Sunesis assessor {#" . $row['id'] . ', ' . $row['firstnames'] . ' ' . $row['surname'] . '} no longer has a corresponding record in SmartAssessor.', Zend_Log::INFO);
        }

        $sql = <<<SQL
UPDATE
	users LEFT OUTER JOIN tmp_sa_assessors
		ON users.smart_assessor_id = tmp_sa_assessors.SmartAssessorId
SET
	users.smart_assessor_id = NULL
WHERE
	users.smart_assessor_id IS NOT NULL
	AND tmp_sa_assessors.SunesisId IS NULL
SQL;
        if (!$this->read_only) {
            DAO::execute($link, $sql);
        }
    }

    /**
     * Unlink any Smart Assessor assessor records that no longer have a corresponding record in Sunesis
     * @param PDO $link
     * @param SmartAssessor $sa
     */
    private function _unlinkSmartAssessorRecords(PDO $link, SmartAssessor $sa) {
        $sql = <<<SQL
SELECT
	tmp_sa_assessors.FirstName,
	tmp_sa_assessors.LastName,
	tmp_sa_assessors.SmartAssessorId
FROM
	tmp_sa_assessors LEFT OUTER JOIN users
		ON tmp_sa_assessors.SunesisId = users.id
WHERE
	users.id IS NULL
	AND tmp_sa_assessors.SunesisId IS NOT NULL
SQL;
        $rs = DAO::query($link, $sql);

        foreach ($rs as $row) {
            $data = array(
                'SmartAssessorId' => $row['SmartAssessorId'],
                'SunesisId' => ''
            );
            $this->log("Smart Assessor assessor {" . $row['FirstName'] . ' ' . $row['LastName'] . ', '
                    . $row['SmartAssessorId'] . '} no longer has a corresponding record in Sunesis.', Zend_Log::INFO);
            $sa->updateAssessor($data);
        }
    }

    private function _createAssessorsInSmartAssessor(PDO $link, SmartAssessor $sa) {
        $type = User::TYPE_ASSESSOR;
        $type_verifier = User::TYPE_VERIFIER;
        $duplicatedAssesors = $this->_getDuplicatedAssessors($link);

        $sql = <<<SQL
SELECT
	users.id AS `SunesisId`,
	users.firstnames AS `FirstName`,
	users.surname AS `LastName`,
	users.username AS `UserName`,
	users.password AS `Password`,
    users.home_address_line_3 AS `Region`,
	users.home_email AS `Email`,
	users.home_telephone AS `Telephone`,
    users.home_mobile AS `Mobile`,
    CASE users.type WHEN 3 THEN 3072 WHEN 4 THEN 512 ELSE NULL END AS `UserType`
FROM
	users INNER JOIN organisations INNER JOIN locations
		ON users.employer_id = organisations.id
		AND users.employer_location_id = locations.id
	LEFT OUTER JOIN tmp_sa_assessors AS tmp
		-- Partial match on name and username
		ON (tmp.FirstName = users.firstnames AND tmp.LastName = users.surname AND tmp.UserName = users.username)
WHERE
	-- Sunesis assessors only
	(users.`type` = {$type} OR users.`type` = {$type_verifier})
	-- Sunesis assessors without an existing link to a Smart Assessor assessor
	AND users.smart_assessor_id IS NULL
	-- Smart Assessor assessors without an existing link to a Sunesis assessor
	AND tmp.SunesisId IS NULL
	-- LEFT JOIN: Return only Sunesis assessors where no match could be made to a Smart Assessor assessor
	AND tmp.SmartAssessorId IS NULL
	-- Mandatory Sunesis fields
	AND users.firstnames IS NOT NULL
	AND users.surname IS NOT NULL
	AND users.username IS NOT NULL
ORDER BY
	users.surname, users.firstnames
SQL;

        $st = DAO::query($link, $sql);
        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
            if (in_array($row['SunesisId'], $duplicatedAssesors)) {
                continue;
            }
            $this->log('Creating SmartAssessor assessor: ' . $row['FirstName']
                    . ' ' . $row['LastName'] . ' (Sunesis #' . $row['SunesisId'] . ')', Zend_Log::INFO);
            $sa->createAssessor($row);
        }
    }

    private function _createAssessorsInSunesis(PDO $link, SmartAssessor $sa) {
        $type = User::TYPE_ASSESSOR;
        $type_verifier = User::TYPE_VERIFIER;

        $sql = <<<SQL
SELECT
	sa.SmartAssessorId AS smart_assessor_id,
    sa.FirstName AS firstnames,
	sa.LastName AS surname,
    sa.UserName AS username,
    sa.Password AS password,
	sa.Email AS home_email,
	sa.Telephone AS home_telephone,
	sa.Mobile AS home_mobile,
	sa.Region AS home_address_line_3,
    CASE sa.UserType WHEN 'Assessor' THEN 3 WHEN 'Internal Quality Assurer' THEN 4 ELSE NULL END AS `type`
FROM
	tmp_sa_assessors AS sa
	LEFT OUTER JOIN users
		-- Partial match on name and Username
		ON (sa.FirstName = users.firstnames AND sa.LastName = users.surname AND sa.UserName = users.username)
WHERE
	-- Smart Assessor records with no existing linked record in Sunesis
	sa.SunesisId IS NULL
	-- Sunesis users with no existing linked record in Smart Assessor
	AND users.smart_assessor_id IS NULL
	-- Only those rows where no exact/partial match was made
	AND users.id IS NULL
	-- Mandatory Sunesis fields
	AND sa.FirstName IS NOT NULL
	AND sa.LastName IS NOT NULL
	AND sa.UserName IS NOT NULL
ORDER BY
	users.surname, users.firstnames
SQL;

        $st = DAO::query($link, $sql);
        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
            $this->log('Creating Sunesis assessor: ' . $row['firstnames']
                    . ' ' . $row['surname'] . ' (SmartAssessorId #' . $row['smart_assessor_id'] . ')', Zend_Log::INFO);
            $sa->createAssessorinSunesis($row);
        }
    }

    private function _updateAssessors(PDO $link, SmartAssessor $sa) {
        $type = User::TYPE_ASSESSOR;
        $type_verifier = User::TYPE_VERIFIER;

        $duplicatedAssesors = $this->_getDuplicatedAssessors($link);

        $sql = <<<SQL
SELECT
	users.id AS `users_id`,
	users.firstnames,
	users.surname,
	users.gender,
	users.home_email,
	users.home_telephone,
	users.home_mobile,
	users.home_address_line_3,

	tmp_sa_assessors.SmartAssessorId,
	tmp_sa_assessors.SunesisId,
	tmp_sa_assessors.FirstName,
	tmp_sa_assessors.LastName,
	tmp_sa_assessors.Email,
	tmp_sa_assessors.Telephone,
	tmp_sa_assessors.Mobile,
	tmp_sa_assessors.Region
FROM
	users INNER JOIN organisations INNER JOIN locations
		ON users.employer_id = organisations.id
		AND users.employer_location_id = locations.id
	-- Imported Smart Assessor assessors
	INNER JOIN tmp_sa_assessors
		ON users.id = tmp_sa_assessors.SunesisId
WHERE
	-- assessors only
	(users.type =  {$type} OR users.type =  {$type_verifier})
	-- Linked records with differing field values
	AND (
		TRIM(users.surname) != TRIM(tmp_sa_assessors.LastName)
		OR TRIM(users.firstnames) != TRIM(tmp_sa_assessors.FirstName)
		OR TRIM(users.home_email) != TRIM(tmp_sa_assessors.Email)
		OR TRIM(users.home_telephone) != TRIM(tmp_sa_assessors.Telephone)
		OR TRIM(users.home_mobile) != TRIM(tmp_sa_assessors.Mobile)
		OR TRIM(users.home_address_line_3) != TRIM(tmp_sa_assessors.Region)
		)
ORDER BY
	users.surname, users.firstnames
SQL;

        // Sort through
        $st = DAO::query($link, $sql);
        $saData = array();
        $sunData = array();
        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
            if (in_array($row['users_id'], $duplicatedAssesors)) {
                continue;
            }

            $saDatum = array();
            $sunDatum = array();
            $this->_compareSystems($row, 'LastName', 'surname', $saDatum, $sunDatum);
            $this->_compareSystems($row, 'FirstName', 'firstnames', $saDatum, $sunDatum);
            $this->_compareSystems($row, 'UserName', 'username', $saDatum, $sunDatum);
            $this->_compareSystems($row, 'Email', 'home_email', $saDatum, $sunDatum);
            $this->_compareSystems($row, 'Telephone', 'home_telephone', $saDatum, $sunDatum);
            $this->_compareSystems($row, 'Mobile', 'home_mobile', $saDatum, $sunDatum);
            $this->_compareSystems($row, 'Region', 'home_address_line_3', $saDatum, $sunDatum);


            if ($saDatum) {
                /* if(isset($saDatum['Sex'])) {
                  switch ($saDatum['Sex']) {
                  case 'M':
                  $saDatum['Sex'] = 'Male';
                  break;
                  case 'F':
                  $saDatum['Sex'] = 'Female';
                  break;
                  default:
                  unset($saDatum['Sex']);
                  break;
                  }
                  } */
                $saDatum['SmartAssessorId'] = $row['SmartAssessorId'];
                $saDatum['_row'] = $row;
                $saData[] = $saDatum;
            }

            if ($sunDatum) {
                $sunDatum['id'] = $row['users_id'];
                $sunDatum['_row'] = $row;
                $sunData[] = $sunDatum;
            }
        }

        // Update Sunesis users
        foreach ($sunData as $data) {
            $firstnames = $data['_row']['firstnames'];
            $surname = $data['_row']['surname'];
            $id = $data['_row']['users_id'];
            unset($data['_row']);   // Remove this before dumping $data below
            $this->log("Updating Sunesis assessor " . $firstnames . " " . $surname . " (Sunesis #" . $id . "): "
                    . $this->_serialiseArray($data), Zend_Log::INFO);
            if (!$this->read_only) {
                DAO::saveObjectToTable($link, 'users', $data);
            }
        }

        // Update Smart Assessor users
        foreach ($saData as $data) {
            $givenNames = $data['_row']['GivenNames'];
            $familyName = $data['_row']['FamilyName'];
            $saId = $data['_row']['SmartAssessorId'];
            $sunId = $data['_row']['users_id'];
            unset($data['_row']);   // Remove this before dumping $data below
            $this->log("Updating SmartAssessor assessor " . $givenNames . " " . $familyName . " (Sunesis #" . $sunId . "): "
                    . $this->_serialiseArray($data), Zend_Log::INFO);
            $sa->updateAssessor($data);
        }
    }

    /**
     * Helper method to _updateAssessors()
     * @param array $row
     * @param string $saKey Smart Assessor field name
     * @param string $sunKey Sunesis fieldname
     * @param array $saArray
     * @param array $sunArray
     */
    private function _compareSystems(array $row, $saKey, $sunKey, array &$saArray, array &$sunArray) {
        if (isset($this->$saKey) && $this->$saKey && (trim($row[$sunKey]) != trim($row[$saKey]))) {
            if ($this->$saKey == 'Sunesis') {
                $saArray[$saKey] = trim($row[$sunKey]);
            } else {
                $sunArray[$sunKey] = trim($row[$saKey]);
            }
        }
    }

    private function _createTempTables(PDO $link) {
        DAO::execute($link, "DROP TEMPORARY TABLE IF EXISTS tmp_sa_assessors");
        $sql = <<<SQL
CREATE TEMPORARY TABLE tmp_sa_assessors (
	`SmartAssessorId` CHAR(50) NOT NULL,
	`SunesisId` BIGINT,
	`FirstName` VARCHAR(100),
	`LastName` VARCHAR(100),
	`UserName` VARCHAR(100),
    `Password` VARCHAR(100),
	`Region` VARCHAR(30),
	`Email` VARCHAR(100),
	`Telephone` VARCHAR(30),
	`Mobile` VARCHAR(30),
    `UserType` VARCHAR(30),
	PRIMARY KEY (`SmartAssessorId`),
	KEY (`SunesisId`)
);
SQL;
        DAO::execute($link, $sql);
    }

    /**
     * Returns an array of user IDs in Sunesis that are duplicates.
     * @param PDO $link
     * @return array
     */
    private function _getDuplicatedAssessors(PDO $link) {
        $sql = <<<SQL
SELECT
	GROUP_CONCAT(users.id) AS `user_id`
FROM
	users
WHERE
	users.type = 3
GROUP BY
	users.employer_id, users.username
HAVING
	COUNT(*) > 1
SQL;
        $raw = DAO::getSingleColumn($link, $sql);

        $ids = array();
        foreach ($raw as $r) {
            $ids = array_merge($ids, explode(',', $r));
        }

        return $ids;
    }

    /**
     * Helper method
     * @param array $array
     * @return string
     */
    private function _serialiseArray(array $array) {
        $str = '';
        foreach ($array as $field => $key) {
            $str .= $field . '=\'' . $key . '\', ';
        }
        return trim($str, ' ,');
    }

}