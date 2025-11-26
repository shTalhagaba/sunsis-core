<?php
class CrontabActionSynchroniseEmployers extends CrontabAction
{
	public $Name;
	public $EdsId;
	public $AddressLine1;
	public $AddressLine2;
	public $AddressTown;
	public $AddressCounty;
	public $AddressPostCode;
	public $Telephone;
	public $KeyContactName;
	public $KeyContactEmail;

	public function __construct()
	{
		$this->task = 'SynchroniseEmployers';
	}

	public function execute(PDO $link)
	{
		if (!SystemConfig::get('smartassessor.soap.enabled')) {
			return;
		}

		// Populate tmp_sa_learners
		$sa = new SmartAssessor($this->read_only);
		$saRecords = $sa->getEmployers();
		$this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_sa_employers', $saRecords);

		// Unlink any Sunesis learner records that no longer have a corresponding record in SmartAssessor
		$this->_unlinkSunesisRecords($link, $sa);

		// Unlink any SmartAssessor learner records that no longer have a corresponding record in Sunesis
		$this->_unlinkSmartAssessorRecords($link, $sa);

		// Create new records in Smart Assessor
		//$this->_createEmployersInSmartAssessor($link, $sa);

        // Create new records in Sunesis
        // $this->_createEmployersInSunesis($link, $sa);

		// Update existing linked records in both Sunesis and Smart Assessor
		$this->_updateEmployers($link, $sa);
	}

	/**
	 * Unlink any Sunesis learner records that no longer have a corresponding record in SmartAssessor
	 * @param PDO $link
	 * @param SmartAssessor $sa
	 */
	private function _unlinkSunesisRecords(PDO $link, SmartAssessor $sa)
	{
		$sql = <<<SQL
SELECT
	locations.id,
	organisations.legal_name
FROM
	locations INNER JOIN organisations
		ON locations.organisations_id = organisations.id
	LEFT OUTER JOIN tmp_sa_employers
		ON locations.smart_assessor_id = tmp_sa_employers.SmartAssessorId
WHERE
	locations.smart_assessor_id IS NOT NULL
	AND tmp_sa_employers.SunesisId IS NULL
SQL;
		$rs = DAO::query($link, $sql);
		foreach ($rs as $row) {
			$this->log("Sunesis employer {" . $row['legal_name'] . ', #' . $row['id']
				. '} no longer has a corresponding record in SmartAssessor.', Zend_Log::INFO);
		}

		$sql = <<<SQL
UPDATE
	locations LEFT OUTER JOIN tmp_sa_employers
		ON locations.smart_assessor_id = tmp_sa_employers.SmartAssessorId
SET
	locations.smart_assessor_id = NULL
WHERE
	locations.smart_assessor_id IS NOT NULL
	AND tmp_sa_employers.SunesisId IS NULL
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
	tmp_sa_employers.Name,
	tmp_sa_employers.SmartAssessorId
FROM
	tmp_sa_employers LEFT OUTER JOIN locations
		ON tmp_sa_employers.SunesisId = locations.id
WHERE
	locations.id IS NULL
	AND tmp_sa_employers.SunesisId IS NOT NULL
SQL;
		$rs = DAO::query($link, $sql);

		foreach($rs as $row) {
			$data = array(
				'SmartAssessorId' => $row['SmartAssessorId'],
				'SunesisId' => ''
			);
			$this->log("SmartAssessor employer {" . $row['Name'] . ', #'
				. $row['SmartAssessorId'] . '} no longer has a corresponding record in Sunesis.', Zend_Log::INFO);
			$sa->updateEmployer($data);
		}
	}


	private function _createEmployersInSmartAssessor(PDO $link, SmartAssessor $sa)
	{
		$type = Organisation::TYPE_EMPLOYER;
		$duplicatedLocations = $this->_getDuplicatedLocations($link);

		$rowCount = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tmp_sa_employers");
		$this->log($rowCount, Zend_Log::INFO);

		$sql = <<<SQL
SELECT
	organisations.legal_name AS `Name`,
	organisations.edrs AS `EdsId`,
	locations.id AS `SunesisId`,
	locations.address_line_1 AS `AddressLine1`,
	locations.address_line_2 AS `AddressLine2`,
	locations.address_line_3 AS `AddressTown`,
	locations.address_line_4 AS `AddressCounty`,
	locations.postcode AS `AddressPostCode`,
	locations.telephone AS `Telephone`,
	locations.contact_name AS `KeyContactName`,
	locations.contact_email As `KeyContactEmail`
FROM
	locations INNER JOIN organisations
		ON locations.organisations_id = organisations.id
	LEFT OUTER JOIN tmp_sa_employers
		ON locations.id = tmp_sa_employers.SunesisId
		OR locations.postcode = tmp_sa_employers.AddressPostCode
WHERE
	-- Sunesis employer locations only
	organisations.`organisation_type` = {$type}
	-- Sunesis locations without an existing link to a Smart Assessor employer
	AND locations.smart_assessor_id IS NULL
	-- LEFT JOIN: Return only Sunesis learners where no match could be made to a Smart Assessor learner
	AND tmp_sa_employers.SmartAssessorId IS NULL
	-- Mandatory Sunesis fields
	AND organisations.legal_name IS NOT NULL
	AND organisations.edrs IS NOT NULL
	AND locations.postcode IS NOT NULL
SQL;

		$st = DAO::query($link, $sql);
		$this->log($st->rowCount(), Zend_Log::INFO);
		while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
			if (in_array($row['SunesisId'], $duplicatedLocations)) {
				continue;
			}
			$this->log('Creating SmartAssessor employer {' . $row['Name'] . ', ' . $row['AddressPostCode']
				. '} (Sunesis #' . $row['SunesisId'] . ')', Zend_Log::INFO);
			$sa->createEmployer($row);
		}
	}


    private function _createEmployersInSunesis(PDO $link, SmartAssessor $sa)
	{
		$type = Organisation::TYPE_EMPLOYER;

		$rowCount = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tmp_sa_employers");
		$this->log($rowCount, Zend_Log::INFO);

		$sql = <<<SQL
SELECT
	tmp_sa_employers.*
FROM
	tmp_sa_employers LEFT OUTER JOIN
		(locations INNER JOIN organisations	ON locations.organisations_id = organisations.id AND organisations.organisation_type = $type)
		ON locations.id = tmp_sa_employers.SunesisId
		OR locations.postcode = tmp_sa_employers.AddressPostCode
WHERE
	-- Only Smart Assessor employers where no match could be made to a Sunesis employers
	 tmp_sa_employers.SunesisId IS NULL
    -- LEFT JOIN: Return Sunesis locations without an existing link to a Smart Assessor employer
	AND locations.id IS NULL
	-- Mandatory Sunesis fields
	AND tmp_sa_employers.Name IS NOT NULL
	AND tmp_sa_employers.EdsId IS NOT NULL
	AND tmp_sa_employers.AddressPostCode IS NOT NULL
SQL;

		$st = DAO::query($link, $sql);
		$this->log($st->rowCount(), Zend_Log::INFO);
		while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
			$this->log('Creating Sunesis employer {' . $row['Name'] . ', ' . $row['AddressPostCode']
				. '} (Smart Assessor #' . $row['SmartAssessorId'] . ')', Zend_Log::INFO);
			$sa->createEmployerinSunesis($row);
		}
	}


	private function _updateEmployers(PDO $link, SmartAssessor $sa)
	{
		$sql = <<<SQL
SELECT
	organisations.legal_name,
	organisations.edrs,
	locations.id AS `locations_id`,
	locations.address_line_1,
	locations.address_line_2,
	locations.address_line_3,
	locations.address_line_4,
	locations.postcode,
	locations.telephone,
	locations.contact_name,
	locations.contact_email,
	tmp_sa_employers.*
FROM
	locations INNER JOIN organisations
		ON locations.organisations_id = organisations.id
	-- Imported Smart Assessor employers
	INNER JOIN tmp_sa_employers
		ON locations.id = tmp_sa_employers.SunesisId
WHERE
	-- Linked records with differing field values
	TRIM(organisations.legal_name) != TRIM(tmp_sa_employers.Name)
	OR TRIM(organisations.edrs) != TRIM(tmp_sa_employers.EdsId)
	OR TRIM(locations.address_line_1) != TRIM(tmp_sa_employers.AddressLine1)
	OR TRIM(locations.address_line_2) != TRIM(tmp_sa_employers.AddressLine2)
	OR TRIM(locations.address_line_3) != TRIM(tmp_sa_employers.AddressTown)
	OR TRIM(locations.address_line_4) != TRIM(tmp_sa_employers.AddressCounty)
	OR TRIM(locations.postcode) != TRIM(tmp_sa_employers.AddressPostCode)
	OR TRIM(locations.telephone) != TRIM(tmp_sa_employers.Telephone)
	OR TRIM(locations.contact_name) != TRIM(tmp_sa_employers.KeyContactName)
	OR TRIM(locations.contact_email) != TRIM(tmp_sa_employers.KeyContactEmail);
SQL;

		// Sort through
		$st = DAO::query($link, $sql);
		$saData = array();
		$sunData = array();
		while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
			$saDatum = array();
			$sunDatum = array();
			$this->_compareSystems($row, 'Name', 'legal_name', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'EdsId', 'edrs', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'Telephone', 'telephone', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'KeyContactName', 'contact_name', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'KeyContactEmail', 'contact_email', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'AddressLine1', 'address_line_1', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'AddressLine2', 'address_line_2', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'AddressTown', 'address_line_3', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'AddressCounty', 'address_line_4', $saDatum, $sunDatum);
			$this->_compareSystems($row, 'AddressPostCode', 'postcode', $saDatum, $sunDatum);

			if ($saDatum) {
				$saDatum['SmartAssessorId'] = $row['SmartAssessorId'];
				$saDatum['_row'] = $row;
				$saData[] = $saDatum;
			}

			if ($sunDatum) {
				$sunDatum['id'] = $row['locations_id'];
				$sunDatum['_row'] = $row;
				$sunData[] = $sunDatum;
			}
		}



		// Update Sunesis locations
		foreach ($sunData as $data) {
			$name = $data['_row']['legal_name'];
			$postcode = $data['_row']['postcode'];
			$sunId = $data['_row']['locations_id'];
			unset($data['_row']);   // Remove this before dumping $data below

			$this->log("Updating Sunesis location {" . $name . " " . $postcode . ", #" . $sunId . "}: "
				. $this->_serialiseArray($data), Zend_Log::INFO);
			if (!$this->read_only) {
				DAO::saveObjectToTable($link, 'locations', $data);  // Update locations

                //Get EmployerId
                $employerid = DAO::getSingleValue($link, "SELECT organisations_id FROM locations WHERE id=".$sunId);
                $data['id']=$employerid;
                DAO::saveObjectToTable($link, 'organisations', $data);  // Update organisations
			}
		}

		// Update Smart Assessor employers
		foreach ($saData as $data) {
			$name = $data['_row']['Name'];
			$postcode = $data['_row']['AddressPostCode'];
			$saId = $data['_row']['SmartAssessorId'];
			unset($data['_row']);   // Remove this before dumping $data below
			$this->log("Updating SmartAssessor employer {" . $name . " " . $postcode . ", #" . $saId . "}: "
				. $this->_serialiseArray($data), Zend_Log::INFO);
			$sa->updateEmployer($data);
		}

	}


	/**
	 * Helper method to _updateEmployers()
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
		DAO::execute($link, "DROP TEMPORARY TABLE IF EXISTS tmp_sa_employers");
		$sql = <<<SQL
CREATE TEMPORARY TABLE tmp_sa_employers (
	`SmartAssessorId` INTEGER NOT NULL,
	`SunesisId` BIGINT,
	`Name` VARCHAR(100),
	`EdsId` VARCHAR(15),
	`AddressLine1` VARCHAR(100),
	`AddressLine2` VARCHAR(100),
	`AddressTown` VARCHAR(100),
	`AddressCounty` VARCHAR(100),
	`AddressPostCode` VARCHAR(12),
	`Telephone` VARCHAR(20),
	`KeyContactName` VARCHAR(100),
	`KeyContactEmail` VARCHAR(100),
	PRIMARY KEY (`SmartAssessorId`),
	KEY (`SunesisId`),
	KEY (`AddressPostCode`)
);
SQL;
		DAO::execute($link, $sql);

	}

	private function _getDuplicatedLocations(PDO $link)
	{
		$sql = <<<SQL
SELECT
	GROUP_CONCAT(locations.id) AS `location_id`
FROM
	locations
WHERE
	locations.postcode IS NOT NULL
GROUP BY
	locations.`organisations_id`, locations.`postcode`
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