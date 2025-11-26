<?php
class CrontabActionSynchroniseLearners extends CrontabAction
{
	public $FamilyName;
	public $GivenNames;
	public $ULN;
	public $DateOfBirth;
	public $Sex;
	public $NINumber;
	public $Domicile;
	public $Email;
	public $TelNumber;
	public $Mobile;
	public $LlddDisability;
	public $LlddLearningDifficulty;
	public $HomeAddressLine1;
	public $HomeAddressLocality;
	public $HomeAddressTown;
	public $HomeAddressCounty;
	public $HomeAddressPostCode;

	public function __construct()
	{
		$this->task = 'SynchroniseLearners';
	}

	public function execute(PDO $link)
	{
		if (!SystemConfig::get('smartassessor.soap.enabled')) {
			return;
		}

		// Populate tmp_sa_learners
		$sa = new SmartAssessor($this->read_only);
		$saRecords = $sa->getLearners();

		$this->_createTempTables($link);

        //DAO::execute($link, "truncate sa_learners");
        //DAO::multipleRowInsert($link, 'sa_learners', $saRecords);
		DAO::multipleRowInsert($link, 'tmp_sa_learners', $saRecords);

		// Unlink any Sunesis learner records that no longer have a corresponding record in SmartAssessor
		$this->_unlinkSunesisRecords($link, $sa);

		// Unlink any SmartAssessor learner records that no longer have a corresponding record in Sunesis
		$this->_unlinkSmartAssessorRecords($link, $sa);

		// Create new records in Smart Assessor
		$this->_createLearnersInSmartAssessor($link, $sa);

        // Create new records in Sunesis
		//$this->_createLearnersInSunesis($link, $sa);

		// Update existing linked records in both Sunesis and Smart Assessor
		$this->_updateLearners($link, $sa);
	}

	/**
	 * Unlink any Sunesis learner records that no longer have a corresponding record in SmartAssessor
	 * @param PDO $link
	 * @param SmartAssessor $sa
	 */
	private function _unlinkSunesisRecords(PDO $link, SmartAssessor $sa)
	{
        $type = User::TYPE_LEARNER;
		$sql = <<<SQL
SELECT
	users.id,
	users.firstnames,
	users.surname,
	organisations.legal_name
FROM
	users INNER JOIN organisations
		ON users.employer_id = organisations.id
	LEFT OUTER JOIN tmp_sa_learners
		ON users.smart_assessor_id = tmp_sa_learners.SmartAssessorId
WHERE
	users.smart_assessor_id IS NOT NULL
	AND tmp_sa_learners.SunesisId IS NULL
	AND users.type = {$type}
SQL;
		$rs = DAO::query($link, $sql);
		foreach ($rs as $row) {
			$this->log("Sunesis learner {#" . $row['id'] . ', ' . $row['firstnames'] . ' ' . $row['surname'] . ', '
				. $row['legal_name'] . '} no longer has a corresponding record in SmartAssessor.', Zend_Log::INFO);
		}

		$sql = <<<SQL
UPDATE
	users LEFT OUTER JOIN tmp_sa_learners
		ON users.smart_assessor_id = tmp_sa_learners.SmartAssessorId
SET
	users.smart_assessor_id = NULL
WHERE
	users.smart_assessor_id IS NOT NULL
	AND tmp_sa_learners.SunesisId IS NULL
SQL;
		if (!$this->read_only) {
			DAO::execute($link, $sql);
		}
	}

	/**
	 * Unlink any Smart Assessor learner records that no longer have a corresponding record in Sunesis
	 * @param PDO $link
	 * @param SmartAssessor $sa
	 */
	private function _unlinkSmartAssessorRecords(PDO $link, SmartAssessor $sa)
	{
		$sql = <<<SQL
SELECT
	tmp_sa_learners.GivenNames,
	tmp_sa_learners.FamilyName,
	tmp_sa_learners.SmartAssessorId
FROM
	tmp_sa_learners LEFT OUTER JOIN users
		ON tmp_sa_learners.SunesisId = users.id
WHERE
	users.id IS NULL
	AND tmp_sa_learners.SunesisId IS NOT NULL
SQL;
		$rs = DAO::query($link, $sql);

		foreach($rs as $row) {
			$data = array(
				'SmartAssessorId' => $row['SmartAssessorId'],
				'SunesisId' => ''
			);
			$this->log("Smart Assessor learner {" . $row['GivenNames'] . ' ' . $row['FamilyName'] . ', '
				. $row['SmartAssessorId'] . '} no longer has a corresponding record in Sunesis.', Zend_Log::INFO);
			$sa->updateLearner($data);
		}
	}

	private function _createLearnersInSmartAssessor(PDO $link, SmartAssessor $sa)
	{
		$type = User::TYPE_LEARNER;
		$duplicatedLearners = $this->_getDuplicatedLearners($link);

		$sql = <<<SQL
SELECT
	users.id AS `SunesisId`,
	users.employer_location_id AS `EmployerSunesisId`,
	locations.smart_assessor_id AS `EmployerSmartAssessorId`,
	users.firstnames AS `GivenNames`,
	users.surname AS `FamilyName`,
	users.l45 AS `ULN`,
	users.ni AS `NINumber`,
	users.dob AS `DateOfBirth`,
	CASE users.gender WHEN 'M' THEN 'Male' WHEN 'F' THEN 'Female' ELSE NULL END AS `Sex`,
	users.l24 AS `Domicile`,
	users.home_email AS `Email`,
	users.home_telephone AS `TelNumber`,
	users.home_mobile AS `Mobile`,
	users.l15 AS `LlddDisability`,
	users.l16 AS `LlddLearningDifficulty`,
	users.home_address_line_1 AS `HomeAddressLine1`,
	users.home_address_line_2 AS `HomeAddressLocality`,
	users.home_address_line_3 AS `HomeAddressTown`,
	users.home_address_line_4 AS `HomeAddressCounty`,
	users.home_postcode AS `HomeAddressPostCode`
FROM
	users INNER JOIN locations
		ON users.employer_location_id = locations.id
	INNER JOIN tmp_contract_learners
		ON users.id = tmp_contract_learners.id
	LEFT OUTER JOIN tmp_sa_learners AS tmp
		-- Match on ULN and employer
		ON (users.l45 = tmp.uln AND locations.smart_assessor_id = tmp.EmployerSmartAssessorId)
		-- Partial match on name and employer, if the ULN field of either record is NULL
		OR (tmp.GivenNames = users.firstnames AND tmp.FamilyName = users.surname
			AND locations.smart_assessor_id = tmp.EmployerSmartAssessorId
			AND (users.l45 IS NULL || tmp.ULN IS NULL))
WHERE
	-- Sunesis learners only
	users.`type` = {$type}
	-- Sunesis learners without an existing link to a Smart Assessor learner
	AND users.smart_assessor_id IS NULL
	-- Smart Assessor learners without an existing link to a Sunesis learner
	AND tmp.SunesisId IS NULL
	-- LEFT JOIN: Return only Sunesis learners where no match could be made to a Smart Assessor learner
	AND tmp.SmartAssessorId IS NULL
	-- Mandatory Sunesis fields
	AND users.firstnames IS NOT NULL
	AND users.surname IS NOT NULL
	AND users.l45 IS NOT NULL
	AND users.dob IS NOT NULL
	AND users.employer_location_id IS NOT NULL
	AND locations.smart_assessor_id IS NOT NULL
ORDER BY
	users.employer_id, users.employer_location_id, users.surname, users.firstnames
SQL;

		$st = DAO::query($link, $sql);
		while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
			if (in_array($row['SunesisId'], $duplicatedLearners)) {
				continue;
			}
			$this->log('Creating SmartAssessor learner: ' . $row['GivenNames']
				. ' ' . $row['FamilyName'] . ' (Sunesis #' . $row['SunesisId'] . ')', Zend_Log::INFO);
			$sa->createLearner($row);
		}
	}

    private function _createLearnersInSunesis(PDO $link, SmartAssessor $sa)
	{
		$type = User::TYPE_LEARNER;

		$sql = <<<SQL
SELECT
	locations.id AS employer_location_id,
	organisations.id AS employer_id,
	sa.GivenNames AS `firstnames`,
	sa.FamilyName AS `surname`,
	sa.ULN AS l45,
	sa.NINumber AS ni,
	sa.DateOfBirth AS dob,
    CASE sa.Sex WHEN 'Male' THEN 'M' WHEN 'Female' THEN 'F' ELSE 'U' END AS gender,
	sa.Domicile AS l24,
	sa.Email AS home_email,
	sa.TelNumber AS home_telephone,
	sa.Mobile AS home_mobile,
	sa.LlddDisability AS l15,
	sa.LlddLearningDifficulty AS l16,
	sa.HomeAddressLine1 AS home_address_line_1,
	sa.HomeAddressLocality AS home_address_line_2,
	sa.HomeAddressTown AS home_address_line_3,
	sa.HomeAddressCounty AS home_address_line_4,
	sa.HomeAddressPostCode AS home_postcode,
    sa.SmartAssessorId AS smart_assessor_id
FROM
	tmp_sa_learners AS sa INNER JOIN locations INNER JOIN organisations
		ON sa.EmployerSmartAssessorId = locations.smart_assessor_id
		AND locations.organisations_id = organisations.id
	LEFT OUTER JOIN users
		-- Partial match on ULN and employer
		ON (sa.ULN = users.l45	AND users.employer_location_id = locations.id)
		-- Partial match on name and employer, if the ULN field of either record is NULL
		OR (sa.GivenNames = users.firstnames AND sa.FamilyName = users.surname
			AND users.employer_location_id = locations.id
			AND (users.l45 IS NULL || sa.ULN IS NULL))
WHERE
	-- Smart Assessor records with no existing linked record in Sunesis
	sa.SunesisId IS NULL
	-- Sunesis users with no existing linked record in Smart Assessor
	AND users.smart_assessor_id IS NULL
	-- Only those rows where no exact/partial match was made
	AND users.id IS NULL
	-- Mandatory Sunesis fields
	AND sa.GivenNames IS NOT NULL
	AND sa.FamilyName IS NOT NULL
	AND sa.ULN IS NOT NULL
	AND sa.DateOfBirth IS NOT NULL
	AND locations.smart_assessor_id IS NOT NULL
ORDER BY
	organisations.legal_name, sa.FamilyName, sa.GivenNames
SQL;

		$st = DAO::query($link, $sql);
		while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
			$this->log('Creating Sunesis learner: ' . $row['firstnames']
				. ' ' . $row['surname'] . ' (Smart Assessor Id #' . $row['smart_assessor_id'] . ')', Zend_Log::INFO);
			$sa->createLearnerinSunesis($row);
		}
	}


	private function _updateLearners(PDO $link, SmartAssessor $sa)
	{
		$duplicatedLearners = $this->_getDuplicatedLearners($link);

		$sql = <<<SQL
SELECT
	users.id AS `users_id`,
	users.employer_location_id,
	locations.smart_assessor_id,
	users.firstnames,
	users.surname,
	users.l45,
	users.ni,
	users.dob,
	users.gender,
	users.l24,
	users.home_email,
	users.home_telephone,
	users.home_mobile,
	users.l15,
	users.l16,
	users.home_address_line_1,
	users.home_address_line_2,
	users.home_address_line_3,
	users.home_address_line_4,
	users.home_postcode,

	tmp_sa_learners.SmartAssessorId,
	tmp_sa_learners.SunesisId,
	tmp_sa_learners.GivenNames,
	tmp_sa_learners.FamilyName,
	tmp_sa_learners.ULN,
	tmp_sa_learners.NINumber,
	tmp_sa_learners.DateOfBirth,
	tmp_sa_learners.Sex,
	tmp_sa_learners.Domicile,
	tmp_sa_learners.Email,
	tmp_sa_learners.TelNumber,
	tmp_sa_learners.Mobile,
	tmp_sa_learners.LlddDisability,
	tmp_sa_learners.LlddLearningDifficulty,
	tmp_sa_learners.HomeAddressLine1,
	tmp_sa_learners.HomeAddressLocality,
	tmp_sa_learners.HomeAddressTown,
	tmp_sa_learners.HomeAddressCounty,
	tmp_sa_learners.HomeAddressPostCode
FROM
	users INNER JOIN organisations INNER JOIN locations
		ON users.employer_id = organisations.id
		AND users.employer_location_id = locations.id
	-- Learners on synchronised contracts only
	/*INNER JOIN tmp_contract_learners
		ON users.id = tmp_contract_learners.id*/
	-- Imported Smart Assessor learners
	INNER JOIN tmp_sa_learners
		ON users.id = tmp_sa_learners.SunesisId
WHERE
	-- Learners only
	users.type = 5
	-- Linked records with differing field values
	AND (
		TRIM(users.surname) != TRIM(tmp_sa_learners.FamilyName)
		OR TRIM(users.firstnames) != TRIM(tmp_sa_learners.GivenNames)
		OR TRIM(users.l45) != TRIM(tmp_sa_learners.ULN)
		OR TRIM(users.ni) != TRIM(tmp_sa_learners.NINumber)
		OR TRIM(users.dob) != TRIM(tmp_sa_learners.DateOfBirth)
		OR TRIM(users.gender) != TRIM(tmp_sa_learners.Sex)
		OR TRIM(users.l24) != TRIM(tmp_sa_learners.Domicile)
		OR TRIM(users.home_email) != TRIM(tmp_sa_learners.Email)
		OR TRIM(users.home_telephone) != TRIM(tmp_sa_learners.TelNumber)
		OR TRIM(users.home_mobile) != TRIM(tmp_sa_learners.Mobile)
		OR TRIM(users.l15) != TRIM(tmp_sa_learners.LlddDisability)
		OR TRIM(users.l16) != TRIM(tmp_sa_learners.LlddLearningDifficulty)
		OR TRIM(users.home_address_line_1) != TRIM(tmp_sa_learners.HomeAddressLine1)
		OR TRIM(users.home_address_line_2) != TRIM(tmp_sa_learners.HomeAddressLocality)
		OR TRIM(users.home_address_line_3) != TRIM(tmp_sa_learners.HomeAddressTown)
		OR TRIM(users.home_address_line_4) != TRIM(tmp_sa_learners.HomeAddressCounty)
		OR TRIM(users.home_postcode) != TRIM(tmp_sa_learners.HomeAddressPostCode) )
ORDER BY
	users.employer_id, users.employer_location_id, users.surname, users.firstnames
SQL;

		// Sort through
		$st = DAO::query($link, $sql);
		$saData = array();
		$sunData = array();
		while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
			//if (in_array($row['users_id'], $duplicatedLearners)) {
			//	continue;
			//}
            //pre($row);

//            if($row['users_id']==10144)
 //               pre($row);
			$saDatum = array();
			$sunDatum = array();
			$this->_compareSystems($row, 'FamilyName', 'surname', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'GivenNames', 'firstnames', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'ULN', 'l45', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'NINumber', 'ni', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'DateOfBirth', 'dob', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'Sex', 'gender', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'ULN', 'l45', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'Email', 'home_email', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'TelNumber', 'home_telephone', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'Mobile', 'home_mobile', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'LlddDisability', 'l15', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'LlddLearningDifficulty', 'l16', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'HomeAddressLine1', 'home_address_line_1', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'HomeAddressLocality', 'home_address_line_2', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'HomeAddressTown', 'home_address_line_3', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'HomeAddressCounty', 'home_address_line_4', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'HomeAddressPostCode', 'home_postcode', $saDatum, $sunDatum);

			if ($saDatum) {
				if(isset($saDatum['Sex'])) {
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
				}
				$saDatum['SmartAssessorId'] = $row['SmartAssessorId'];
                $saDatum['SunesisId'] = $row['users_id'];
				$saDatum['_row'] = $row;
				$saData[] = $saDatum;
			}

			if ($sunDatum) {
				$sunDatum['id'] = $row['users_id'];
                $sunDatum['smart_assessor_id'] = $row['SmartAssessorId'];
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
			$this->log("Updating Sunesis learner " . $firstnames . " " . $surname . " (Sunesis #" . $id . "): "
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
			$this->log("Updating SmartAssessor learner " . $givenNames . " " . $familyName . " (Sunesis #" . $sunId . "): "
				. $this->_serialiseArray($data), Zend_Log::INFO);
			$sa->updateLearner($data);
		}

	}

	/**
	 * Helper method to _updateLearners()
	 * @param array $row
	 * @param string $saKey Smart Assessor field name
	 * @param string $sunKey Sunesis fieldname
	 * @param array $saArray
	 * @param array $sunArray
	 */
	private function _compareSystems(array $row, $saKey, $sunKey, array &$saArray, array &$sunArray)
	{
		if (isset($this->$saKey) && $this->$saKey && (trim($row[$sunKey]) != trim($row[$saKey]))) {
			if ($this->$saKey == 'Sunesis') {
				$saArray[$saKey] = trim($row[$sunKey]);
			} else {
				$sunArray[$sunKey] = trim($row[$saKey]);
			}
		}
	}

	private function _createTempTables(PDO $link)
	{
		DAO::execute($link, "DROP TEMPORARY TABLE IF EXISTS tmp_sa_learners");
		$sql = <<<SQL
CREATE TEMPORARY TABLE tmp_sa_learners (
	`SmartAssessorId` CHAR(36) NOT NULL,
	`SunesisId` BIGINT,
	`EmployerSunesisId` BIGINT,
	`EmployerSmartAssessorId` BIGINT,
	`FamilyName` VARCHAR(100),
	`GivenNames` VARCHAR(100),
	`ULN` VARCHAR(10),
	`NINumber` VARCHAR(10),
	`Domicile` CHAR(2),
	`DateOfBirth` DATE,
	`Sex` VARCHAR(10),
	`Email` VARCHAR(100),
	`TelNumber` VARCHAR(30),
	`Mobile` VARCHAR(30),
	`LlddLearningDifficulty` INT,
	`LlddDisability` INT,
	`HomeAddressLine1` VARCHAR(100),
	`HomeAddressLocality` VARCHAR(100),
	`HomeAddressTown` VARCHAR(100),
	`HomeAddressCounty` VARCHAR(100),
	`HomeAddressPostCode` VARCHAR(12),
	PRIMARY KEY (`SmartAssessorId`),
	KEY (`SunesisId`),
	KEY (`EmployerSunesisId`),
	KEY (`ULN`),
	KEY (`EmployerSunesisId`)
);
SQL;
		DAO::execute($link, $sql);

		// Sunesis users on a synchronised contract
		DAO::execute($link, "DROP TEMPORARY TABLE IF EXISTS tmp_contract_learners");
		$sql = <<<SQL
CREATE TEMPORARY TABLE tmp_contract_learners (
	`id` BIGINT NOT NULL,
	PRIMARY KEY (`id`)
)
SELECT DISTINCT
	users.id
FROM
	users INNER JOIN tr INNER JOIN contracts
		ON users.username = tr.username
		AND tr.`contract_id` = contracts.id
WHERE
	users.type = 5
	AND contracts.`sync_learners_smart_assessor` = 1
ORDER BY
	users.id;
SQL;
		DAO::execute($link, $sql);
	}


	/**
	 * Returns an array of user IDs in Sunesis that are duplicates.
	 * @param PDO $link
	 * @return array
	 */
	private function _getDuplicatedLearners(PDO $link)
	{
		$sql = <<<SQL
SELECT
	GROUP_CONCAT(users.id) AS `user_id`
FROM
	users
WHERE
	users.type = 5
	AND users.uln IS NOT NULL
GROUP BY
	users.employer_id, users.uln
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
	private function _serialiseArray(array $array)
	{
		$str = '';
		foreach ($array as $field=>$key) {
			$str .= $field . '=\'' . $key . '\', ';
		}
		return trim($str, ' ,');
	}
}