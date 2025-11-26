<?php
class sa_employer_sync extends ActionController
{
	/**
	 * Compare employers in Smart Assessor with employers in Sunesis
	 * @param PDO $link
	 * @override
	 * @throws UnauthorizedException
	 */
	public function indexAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}

		$filterSections = $this->_getParam("filter_sections", array('partialmatch', 'nomatchsun'));
		$filterSectionsOptions = array(
			array('exactmatch', 'Linked records'),
			array('partialmatch', 'Potential links'),
			array('nomatchsun', 'No links (Sunesis)'),
			array('nomatchsa', 'No links (Smart Assessor)')
		);

		include('smartassessor/tpl_sa_employer_sync.php');
	}

	/**
	 * @param PDO $link
	 * @return mixed
	 * @throws UnauthorizedException
	 */
	public function renderContentAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}
		if (!SystemConfig::get("smartassessor.soap.enabled")) {
			echo '<p style="font-weight: bold">SmartAssessor integration is not enabled for this Sunesis site</p>';
			return;
		}

		$sa = new SmartAssessor();
		$saEmployers = $sa->getEmployers();
		$this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_sa_employers', $saEmployers);

        DAO::execute($link, "drop table if exists sa_employers");
        DAO::execute($link, "create table sa_employers select * from tmp_sa_employers");

		$filterSections = $this->_getParam("filter_sections", array());

		if (in_array('exactmatch', $filterSections)) {
			$this->_renderExactMatch($link);
		}
		if (in_array('partialmatch', $filterSections)) {
			$this->_renderPartialMatch($link);
		}
		if (in_array('nomatchsun', $filterSections)) {
			$this->_renderNoMatchSun($link);
		}
		if (in_array('nomatchsa', $filterSections)) {
			$this->_renderNoMatchSa($link);
		}

		//$this->_renderTemporaryTable($link);
	}

	/**
	 * @param PDO $link
	 * @throws UnauthorizedException
	 * @throws Exception
	 */
	public function createRecordsAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}
		if (!SystemConfig::get("smartassessor.soap.enabled")) {
			throw new Exception("SmartAssessor integration is not enabled for this Sunesis site.");
		}

		// Allow longer execution time
		set_time_limit(180); // 3 minutes

		$ids = (array) $this->_getParam("ids");
		if (count($ids) == 0) {
			throw new Exception("No records selected to create");
		}
		foreach($ids as $id) {
			if (!is_numeric($id)) {
				throw new Exception("Illegal non-numeric value for id: " . $id);
			}
		}
		$location_ids = DAO::pdo_implode($ids);

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
WHERE
	organisations.legal_name IS NOT NULL
	AND locations.id IS NOT NULL
	AND locations.postcode IS NOT NULL
	AND locations.id IN ($location_ids);
SQL;
		$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		try
		{
			//DAO::transaction_start($link);
			$sa = new SmartAssessor(false);
			foreach ($rows as $row) {
				//$addr = new Address($row);
				//list($row['AddressLine1'], $row['AddressLine2'], $row['AddressTown'], $row['AddressCounty']) = $addr->to4Lines();
				$sa->createEmployer($row);
			}
			//DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			//DAO::transaction_rollback($link);
			throw new Exception("An error occurred while creating employer records. Operation aborted.", 1, $e);
		}

	}


	public function linkRecordsAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}
		if (!SystemConfig::get("smartassessor.soap.enabled")) {
			throw new Exception("SmartAssessor integration is not enabled for this Sunesis site.");
		}

		// Allow longer execution time
		set_time_limit(180); // 3 minutes

		$ids = (array) $this->_getParam("ids");
		if (count($ids) == 0) {
			throw new Exception("No records selected to link");
		}
		foreach ($ids as $id) {
			if(!preg_match('/^\d+-\d+/', $id)) {
				throw new Exception("Illegal value for id pair: " . $id);
			}
		}

		try
		{
			//DAO::transaction_start($link);
			$sa = new SmartAssessor();
			foreach ($ids as $id) {
				$pair = explode('-', $id);
				$employer = array(
					'SunesisId' => $pair[0],
					'SmartAssessorId' => $pair[1]
				);

				// Update SmartAssessor record
				$sa->updateEmployer($employer);

				// Update Sunesis record
				DAO::execute($link, "UPDATE locations SET smart_assessor_id={$employer['SmartAssessorId']} WHERE locations.id={$employer['SunesisId']}");
			}
			//DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			//DAO::transaction_rollback($link);
			throw new Exception("An error occurred while linking employer records. Operation aborted.", 1, $e);
		}


	}


	public function unlinkRecordsAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}
		if (!SystemConfig::get("smartassessor.soap.enabled")) {
			throw new Exception("SmartAssessor integration is not enabled for this Sunesis site.");
		}

		// Allow longer execution time
		set_time_limit(180); // 3 minutes

		$ids = (array) $this->_getParam("ids");
		if (count($ids) == 0) {
			throw new Exception("No records selected to link");
		}
		foreach ($ids as $id) {
			if(!preg_match('/^\d+-\d+/', $id)) {
				throw new Exception("Illegal value for id pair: " . $id);
			}
		}

		try
		{
			//DAO::transaction_start($link);
			$sa = new SmartAssessor();
			foreach ($ids as $id) {
				$pair = explode('-', $id);
				DAO::execute($link, "UPDATE locations SET smart_assessor_id=NULL WHERE locations.id={$pair[0]}");
				$sa->updateEmployer(array('SunesisId' => '', 'SmartAssessorId' => $pair[1]));
			}
			//DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			//DAO::transaction_rollback($link);
			throw new Exception("An error occurred while unlinking employer records. Operation aborted.", 1, $e);
		}

	}


    /**
	 * @param PDO $link
	 * @throws UnauthorizedException
	 * @throws Exception
	 */
	public function createRecordsinSunesisAction(PDO $link)
	{

		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}
		if (!SystemConfig::get("smartassessor.soap.enabled")) {
			throw new Exception("SmartAssessor integration is not enabled for this Sunesis site.");
		}

        // Create temporary table and Insert records from Smart Assessor
        $sa = new SmartAssessor();
		$saEmployers = $sa->getEmployers();
		$this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_sa_employers', $saEmployers);

		// Allow longer execution time
		set_time_limit(180); // 3 minutes

		$ids = (array) $this->_getParam("ids");
		if (count($ids) == 0) {
			throw new Exception("No records selected to create");
		}
		foreach($ids as $id) {
			if (!is_numeric($id)) {
				throw new Exception("Illegal non-numeric value for id: " . $id);
			}
		}
		$SmartAssessor_ids = DAO::pdo_implode($ids);

		$sql = <<<SQL
SELECT
	*
FROM
	tmp_sa_employers
WHERE
    tmp_sa_employers.SmartAssessorId IN ($SmartAssessor_ids);
SQL;
		$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		try
		{
			//DAO::transaction_start($link);
			$sa = new SmartAssessor(false);
			foreach ($rows as $row) {
				//$addr = new Address($row);
				//list($row['AddressLine1'], $row['AddressLine2'], $row['AddressTown'], $row['AddressCounty']) = $addr->to4Lines();
				$sa->createEmployerinSunesis($row);
			}
			//DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			//DAO::transaction_rollback($link);
			throw new Exception("An error occurred while creating employer records. Operation aborted.", 1, $e);
		}

	}


	private function _renderPartialMatch(PDO $link)
	{
		$type = Organisation::TYPE_EMPLOYER;

		$sql = <<<SQL
SELECT
	organisations.id AS `org_id`,
	organisations.legal_name,
	organisations.edrs,
	locations.*,
	GROUP_CONCAT(tmp_sa_employers.SmartAssessorId) AS `smart_assessor_ids`
FROM
	locations INNER JOIN organisations
		ON locations.organisations_id = organisations.id
	INNER JOIN tmp_sa_employers
		-- Identical postcodes
		ON locations.postcode = tmp_sa_employers.AddressPostCode
		-- EDS id matches or is NULL in either record
		AND (tmp_sa_employers.EdsId IS NULL OR organisations.edrs IS NULL OR organisations.edrs = tmp_sa_employers.EdsId)
		-- Employer names match (unfortunately it is common for EDS id and postcode to be the same for subsidiaries and parent companies)
		AND (organisations.legal_name = tmp_sa_employers.Name)
WHERE
	-- Sunesis employers only
	organisations.organisation_type = $type
	-- Unlinked Smart Assessor records only
	AND tmp_sa_employers.SunesisId IS NULL
	-- Unlinked Sunesis locations only
	AND locations.smart_assessor_id IS NULL
GROUP BY
	locations.id
ORDER BY
	organisations.legal_name, locations.full_name, locations.id
SQL;
		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<colgroup>';
		echo '<col width="40"/>';  // Id
		echo '<col width="150"/>';   // Name
		echo '<col width="60"/>';   // EDS ID
		echo '<col width="40"/>';  // Location ID
		echo '<col width="100"/>';  // Name
		echo '<col width="60"/>';   // Postcode
		echo '</colgroup>';
		echo '<colgroup>';
		echo '<col width="310"/>';
		echo '</colgroup>';
		echo '<col width="80"/>';   // Action
		echo '<caption>Sunesis locations with possible matching employers in Smart Assessor</caption>';
		echo '<tr><th colspan="6">Sunesis</th><th>Smart Assessor</th><th rowspan="2" width="100">Action</th></tr>';
		echo '<tr><th colspan="3">Employer</th><th colspan="3">Location</th><th rowspan="2">Employer</th></tr>';
		echo '<tr><th>ID</th><th>Name</th><th>EDS ID</th></th><th>ID</th></th><th>Name</th><th>Postcode</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
		foreach ($rs as $row) {
			echo '<tr class="Data">';

			echo '<td align="right"><a href="/do.php?_action=read_employer&id='.$row['org_id'].'" target="_blank">' . htmlspecialchars((string)$row['org_id']) . '</a></td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['legal_name']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['edrs']) . '</td>';
			echo '<td align="right">' . htmlspecialchars((string)$row['id']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['full_name']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['postcode']) . '</td>';

			$options = DAO::getResultset($link, "SELECT SmartAssessorId, `Name`, EdsId, AddressPostCode FROM tmp_sa_employers WHERE SmartAssessorId IN ({$row['smart_assessor_ids']})", DAO::FETCH_ASSOC);
			$select = '<select style="font-family:monospace; font-size: 9pt">';
			if(count($options) == 1) {
				$opt = $options[0];
				$select .= sprintf('<option value="%s">', htmlspecialchars((string)$opt['SmartAssessorId']));
				$select .= str_replace(' ', '&nbsp;', sprintf('%-20s | %-10s | %-10s',
					htmlspecialchars((string)$opt['Name']),
					htmlspecialchars((string)$opt['EdsId']),
					htmlspecialchars((string)$opt['AddressPostCode'])
				));
				$select .= '</option>';
			} else {
				$select .= '<option selected="selected"></option>'; // Insert a blank option
				foreach ($options as $opt) {
					$select .= sprintf('<option value="%s">', htmlspecialchars((string)$opt['SmartAssessorId']));
					$select .= str_replace(' ', '&nbsp;', sprintf('%-20s | %-10s | %-10s',
						htmlspecialchars((string)$opt['Name']),
						htmlspecialchars((string)$opt['EdsId']),
						htmlspecialchars((string)$opt['AddressPostCode'])
					));
					$select .= '</option>';
				}
			}
			$select .= '</select>';

			echo '<td align="left">', $select, '</td>';
			echo sprintf('<td align="center"><input class="SelectRow" type="checkbox" value="%s"/></td>', htmlspecialchars((string)$row['id']));

			echo '</tr>';
		}
		echo '<tr><td colspan="7">&nbsp;</td><td align="center"><input type="button" id="BtnLink" value="Link"/></td></tr>';
		echo '</table>';
	}


	private function _renderNoMatchSun(PDO $link)
	{
		$type = Organisation::TYPE_EMPLOYER;

		$sql = <<<SQL
SELECT
	organisations.id AS `org_id`,
	organisations.legal_name,
	organisations.edrs,
	locations.*
FROM
	locations INNER JOIN organisations
		ON locations.organisations_id = organisations.id
	LEFT OUTER JOIN tmp_sa_employers
		ON locations.id = tmp_sa_employers.SunesisId
		OR (tmp_sa_employers.SunesisId IS NULL
			AND locations.postcode = tmp_sa_employers.AddressPostCode
			-- EDS id matches or is NULL in either record
			AND (tmp_sa_employers.EdsId IS NULL OR organisations.edrs IS NULL OR organisations.edrs = tmp_sa_employers.EdsId)
			-- Employer names match (unfortunately it is common for EDS id and postcode to be the same for subsidiaries and parent companies)
			AND (organisations.legal_name = tmp_sa_employers.Name))
WHERE
	organisations.organisation_type = $type
	-- LEFT JOIN exclusion clause (rows in locations without a match)
	AND tmp_sa_employers.SmartAssessorId IS NULL
	AND locations.smart_assessor_id IS NULL
	and organisations.active = 1
ORDER BY
	organisations.legal_name, locations.full_name, locations.postcode
SQL;
		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		$duplicated_locations = $this->_getDuplicatedLocations($link);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<colgroup>';
		echo '<col width="40"/>';  // Id
		echo '<col width="150"/>';   // Name
		echo '<col width="60"/>';   // EDS ID
		echo '<col width="40"/>';  // Location ID
		echo '<col width="100"/>';  // Name
		echo '<col width="60"/>';   // Postcode
		echo '</colgroup>';
		echo '<colgroup style="background-color:#FAFAFA">';
		echo '<col width="40"/>';   // Smart Assessor ID
		echo '<col width="150"/>';  // Name
		echo '<col width="60"/>';  // EDS ID
		echo '<col width="60"/>';  // Postcode
		echo '</colgroup>';
		echo '<col width="80"/>';   // Action
		echo '<caption>Sunesis locations with no matching employers in Smart Assessor (Push)</caption>';
		echo '<tr><th colspan="6">Sunesis</th><th colspan="4">Smart Assessor</th><th rowspan="2" width="100">Action</th></tr>';
		echo '<tr><th colspan="3">Employer</th><th colspan="3">Location</th><th colspan="4">Employer</th></tr>';
		echo '<tr><th>ID</th><th>Name</th><th>EDS ID</th></th><th>ID</th></th><th>Name</th><th>Postcode</th><th>ID</th></th><th>Name</th><th>EDS ID</th><th>Postcode</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
		foreach ($rs as $row) {
			if (!$row['postcode'] || !$row['edrs']) {
				echo '<tr class="Data" style="color:gray">';
			} else {
				echo '<tr class="Data">';
			}


			echo '<td align="right"><a href="/do.php?_action=read_employer&id='.$row['org_id'].'" target="_blank">' . htmlspecialchars((string)$row['org_id']) . '<a/></td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['legal_name']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['edrs']) . '</td>';
			echo '<td align="right">' . htmlspecialchars((string)$row['id']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['full_name']) . '</td>';
			if (in_array($row['id'], $duplicated_locations)) {
				echo '<td align="left" style="font-weight:bold">' . htmlspecialchars((string)$row['postcode']) . '</td>';
			} else {
				echo '<td align="left">' . htmlspecialchars((string)$row['postcode']) . '</td>';
			}
			
			echo '<td colspan="4">&nbsp;</td>';
			if ( ($row['legal_name'] && $row['edrs'] && $row['postcode']) && !in_array($row['id'], $duplicated_locations) ) {
				echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['id'])); // Valid record for creation in Smart Assessor
			} else {
				echo '<td>&nbsp;</td>'; // invalid (missing fields)
			}

			echo '</tr>';
		}
		echo '<tr><td colspan="10">&nbsp;</td><td align="center"><input type="button" id="BtnCreate" value="Create"/></td></tr>';
		echo '</table>';
	}


	private function _renderNoMatchSa(PDO $link)
	{
		$type = Organisation::TYPE_EMPLOYER;
        $requiredFields = array('SmartAssessorId','Name', 'EdsId', 'AddressPostCode');

		$sql = <<<SQL
SELECT
	tmp_sa_employers.*
FROM
	tmp_sa_employers LEFT OUTER JOIN
		(locations INNER JOIN organisations	ON locations.organisations_id = organisations.id AND organisations.organisation_type = $type)
		ON locations.id = tmp_sa_employers.SunesisId
		OR locations.postcode = tmp_sa_employers.AddressPostCode
WHERE
	locations.id IS NULL
ORDER BY
	tmp_sa_employers.Name
SQL;
		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<colgroup style="background-color:#FAFAFA">';
		echo '<col width="40"/>';  // Id
		echo '<col width="150"/>';   // Name
		echo '<col width="60"/>';   // EDS ID
		echo '<col width="40"/>';  // Location ID
		echo '<col width="100"/>';  // Name
		echo '<col width="60"/>';   // Postcode
		echo '</colgroup>';
		echo '<colgroup>';
		echo '<col width="40"/>';   // Smart Assessor ID
		echo '<col width="150"/>';  // Name
		echo '<col width="60"/>';  // EDS ID
		echo '<col width="60"/>';  // Postcode
		echo '</colgroup>';
		echo '<col width="80"/>';   // Action
		echo '<caption>Smart Assessor employers with no matching locations in Sunesis (Pull)</caption>';
		echo '<tr><th colspan="6">Sunesis</th><th colspan="4">Smart Assessor</th><th rowspan="2" width="100">Action</th></tr>';
		echo '<tr><th colspan="3">Employer</th><th colspan="3">Location</th><th colspan="4">Employer</th></tr>';
		echo '<tr><th>ID</th><th>Name</th><th>EDS ID</th></th><th>ID</th></th><th>Name</th><th>Postcode</th><th>ID</th></th><th>Name</th><th>EDS ID</th><th>Postcode</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
		foreach ($rs as $row) {
            $rowValid = true;
			foreach ($requiredFields as $field) {
				$rowValid = $rowValid && !empty($row[$field]);
				if (!$rowValid) {
					break;
				}
			}

			if (!$rowValid) {
				echo '<tr style="color:gray" class="Data">';
			} else {
				echo '<tr class="Data">';
			}


			//echo '<tr class="Data">';
			echo '<td align="right" colspan="6" >&nbsp;</td>';
			echo '<td align="right">' . htmlspecialchars((string)$row['SmartAssessorId']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['Name']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['EdsId']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['AddressPostCode']) . '</td>';
			//echo '<td style="background-color:#FAFAFA">&nbsp;</td>';
            // Action checkbox
			if ($rowValid) {
            echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['SmartAssessorId'])); // Valid record for creation in Smart Assessor
            } else {
			echo '<td>&nbsp;</td>'; // Invalid (missing fields)
			}

			echo '</tr>';
		}
        echo '<tr><td colspan="10">&nbsp;</td><td align="center"><input type="button" id="BtnCreateinSunesis" value="Create in Sunesis"/></td></tr>';
		echo '</table>';
	}


	private function _renderExactMatch(PDO $link)
	{
		$type = Organisation::TYPE_EMPLOYER;

		$sql = <<<SQL
SELECT
	organisations.id AS `org_id`,
	organisations.legal_name,
	organisations.edrs,
	locations.id,
	locations.full_name,
	locations.postcode,
	tmp_sa_employers.*
FROM
	locations INNER JOIN organisations
		ON locations.organisations_id = organisations.id
	INNER JOIN tmp_sa_employers
		ON locations.id = tmp_sa_employers.SunesisId
WHERE
	organisations.organisation_type = $type
ORDER BY
	organisations.legal_name, locations.full_name, locations.id
SQL;
                
               
                
		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<col width="40"/>';  // Id
		echo '<col width="150"/>';   // Name
		echo '<col width="60"/>';   // EDS ID
		echo '<col width="40"/>';  // Location ID
		echo '<col width="100"/>';  // Name
		echo '<col width="60"/>';   // Postcode
		echo '<col width="40"/>';   // Smart Assessor ID
		echo '<col width="150"/>';  // Name
		echo '<col width="60"/>';  // EDS ID
		echo '<col width="60"/>';  // Postcode
		echo '<col width="80"/>';   // Action
		echo '<caption>Sunesis locations linked to employers in Smart Assessor</caption>';
		echo '<tr><th colspan="6">Sunesis</th><th colspan="4">Smart Assessor</th><th rowspan="2" width="100">Action</th></tr>';
		echo '<tr><th colspan="3">Employer</th><th colspan="3">Location</th><th colspan="4">Employer</th></tr>';
		echo '<tr><th>ID</th><th>Name</th><th>EDS ID</th></th><th>ID</th></th><th>Name</th><th>Postcode</th><th>ID</th></th><th>Name</th><th>EDS ID</th><th>Postcode</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
		foreach ($rs as $row) {
			echo '<tr class="Data">';

			echo '<td align="right"><a href="/do.php?_action=read_employer&id='.$row['org_id'].'" target="_blank">' . htmlspecialchars((string)$row['org_id']) . '</a></td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['legal_name']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['edrs']) . '</td>';
			echo '<td align="right">' . htmlspecialchars((string)$row['id']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['full_name']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['postcode']) . '</td>';
			echo '<td align="right">' . htmlspecialchars((string)$row['SmartAssessorId']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['Name']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['EdsId']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['AddressPostCode']) . '</td>';

			echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['id'] . '-' . $row['SmartAssessorId']));

			echo '</tr>';
		}
		//echo '<tr><td colspan="10">&nbsp;</td><td align="center"><input type="button" id="BtnUpdate" value="Update" disabled="disabled"/></td></tr>';
		echo '<tr><td colspan="10">&nbsp;</td><td align="center"><input type="button" id="BtnUnlink" value="Unlink" style="color:red"/></td></tr>';

		echo '</table>';
	}


	/**
	 * @param PDO $link
	 * @return array
	 */
/*	private function _getSunesisEmployers(PDO $link)
	{
		$type = Organisation::TYPE_EMPLOYER;

		$sql = <<<SQL
SELECT
	organisations.id AS `org_id`,
	organisations.legal_name,
	organisations.edrs,
	locations.*
FROM
	locations INNER JOIN organisations
		ON locations.organisations_id = organisations.id
WHERE
	organisations.organisation_type = $type
ORDER BY
	organisations.legal_name
SQL;
		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		return $rs;
	}*/


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
	`AddressPostCode` VARCHAR(20),
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


	private function _renderTemporaryTable(PDO $link)
	{
		$rs = DAO::getResultset($link, "SELECT * FROM tmp_sa_employers", DAO::FETCH_ASSOC);
		HTML::renderResultset($rs);
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
}