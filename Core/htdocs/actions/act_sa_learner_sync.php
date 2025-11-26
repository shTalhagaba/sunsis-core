<?php
class sa_learner_sync extends ActionController
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

		//$filterSections = $this->_getParam("filter_sections", array('partialmatch', 'nomatchsun_continue'));
		//array('partialmatch', 'Potential links'),
		$filterSections = $this->_getParam("filter_sections", array('nomatchsun_continue'));
		$filterSectionsOptions = array(
			array('exactmatch', 'Linked records'),
			array('nomatchsun', 'No links (Sunesis)'),
			array('nomatchsa', 'No links (Smart Assessor)'),
			array('nomatchsun_continue', 'No links (Sunesis) only continue learners')
		);

		include('smartassessor/tpl_sa_learner_sync.php');
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
			echo '<p style="font-weight: bold">SmartAssessor integration is not enabled for this Sunesis site</b></p>';
			return;
		}

		$sa = new SmartAssessor();
		$saRecords = $sa->getLearners();
		$this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_sa_learners', $saRecords);

		if(DB_NAME=="am_ligauk")
		{
			DAO::execute($link, "drop table sa_learners");
			DAO::execute($link, "create table sa_learners select * from tmp_sa_learners");
		}

//        $ULN = DAO::getSingleValue($link, "select ULN from tmp_sa_learners where length(ULN)>10");
 //      if($ULN!='')
    //       pre($ULN);


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
		if (in_array('nomatchsun_continue', $filterSections)) {
			$this->_renderNoMatchSun($link, 'YES');
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
		$user_ids = DAO::pdo_implode($ids);

		$type = User::TYPE_LEARNER;
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
WHERE
	users.smart_assessor_id IS NULL
 	AND users.firstnames IS NOT NULL
 	AND users.surname IS NOT NULL
 	AND users.dob IS NOT NULL
 	AND users.l45 IS NOT NULL
 	AND users.type = {$type}
	AND users.id IN ($user_ids);
SQL;
		$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		try
		{
			$sa = new SmartAssessor(false);
			foreach ($rows as $row) {
				$sa->createLearner($row);
			}
		}
		catch(Exception $e)
		{
			throw new Exception("An error occurred while creating learner records. Operation aborted.", 1, $e);
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
			if(!preg_match('/^\d+:\w+/', $id)) {
				throw new Exception("Illegal value for id pair: " . $id);
			}
		}

		try
		{
			//DAO::transaction_start($link);
			$sa = new SmartAssessor();
			foreach ($ids as $id) {
				$pair = explode(':', $id);
				DAO::execute($link, "UPDATE users SET smart_assessor_id='{$pair[1]}' WHERE users.id={$pair[0]}");
				$sa->updateLearner(array('SunesisId' => $pair[0], 'SmartAssessorId' => $pair[1]));
			}
			//DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			//DAO::transaction_rollback($link);
			throw new Exception("An error occurred while linking learner records. Operation aborted.", 1, $e);
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
			throw new Exception("No records selected to unlink");
		}
		foreach ($ids as $id) {
			if(!preg_match('/^\d+:\w+/', $id)) {
				throw new Exception("Illegal value for id pair: " . $id);
			}
		}

		try
		{
			//DAO::transaction_start($link);
			$sa = new SmartAssessor();
			foreach ($ids as $id) {
				$pair = explode(':', $id);
				DAO::execute($link, "UPDATE users SET smart_assessor_id=NULL WHERE users.id={$pair[0]}");
				$sa->updateLearner(array('SunesisId' => '', 'SmartAssessorId' => $pair[1]));
			}
			//DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			//DAO::transaction_rollback($link);
			throw new Exception("An error occurred while unlinking learner records. Operation aborted.", 1, $e);
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
		$saRecords = $sa->getLearners();
		$this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_sa_learners', $saRecords);

		// Allow longer execution time
		set_time_limit(180); // 3 minutes

		$ids = (array) $this->_getParam("ids");
		if (count($ids) == 0) {
			throw new Exception("No records selected to create");
		}
		/*foreach($ids as $id) {
			if (!is_numeric($id)) {
				throw new Exception("Illegal non-numeric value for id: " . $id);
			}
		}   */
		$SmartAssessor_ids = DAO::pdo_implode($ids);

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
WHERE
    sa.SmartAssessorId IN ($SmartAssessor_ids);
SQL;
		$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		try
		{
			$sa = new SmartAssessor(false);
			foreach ($rows as $row) {
				$sa->createLearnerinSunesis($row);
			}
		}
		catch(Exception $e)
		{
			throw new Exception("An error occurred while creating learner records. Operation aborted.", 1, $e);
		}
	}


	private function _renderPartialMatch(PDO $link)
	{
		$type = User::TYPE_LEARNER;
		$requiredFields = array('firstnames', 'surname', 'uln', 'dob',
			'employer_location_id', 'locations_smart_assessor_id');

		$sql = <<<SQL
SELECT
	organisations.id AS `organisations_id`,
	organisations.legal_name AS `organisations_legal_name`,
	organisations.edrs AS `organisations_edrs`,
	locations.id AS `locations_id`,
	locations.full_name AS `locations_full_name`,
	locations.postcode AS `locations_postcode`,
	locations.smart_assessor_id AS `locations_smart_assessor_id`,
	users.id,
	users.username,
	users.employer_location_id,
	users.smart_assessor_id,
	users.surname,
	users.firstnames,
	users.l45 AS `uln`,
	users.dob,
	GROUP_CONCAT(tmp_sa_learners.SmartAssessorId) AS `smart_assessor_ids`
FROM
	users INNER JOIN organisations INNER JOIN locations
		ON users.employer_id = organisations.id
		AND users.employer_location_id = locations.id
	INNER JOIN tmp_contract_learners
		ON users.id = tmp_contract_learners.id
	INNER JOIN tmp_sa_learners
		-- Partial match on ULN and employer
		ON (users.l45 = tmp_sa_learners.ULN AND locations.smart_assessor_id = tmp_sa_learners.EmployerSmartAssessorId)
		-- Partial match on name and employer where the ULN in either record is NULL
		OR (users.firstnames = tmp_sa_learners.GivenNames AND users.surname = tmp_sa_learners.FamilyName
			AND locations.smart_assessor_id = tmp_sa_learners.EmployerSmartAssessorId
			AND (tmp_sa_learners.ULN IS NULL OR users.l45 IS NULL))
WHERE
	-- Sunesis learner records only
	users.`type` = {$type}
	-- Sunesis location records linked to Smart Assessor employer
	AND locations.smart_assessor_id IS NOT NULL
	-- Smart Assessor learner records not linked to a Sunesis learner
	AND (tmp_sa_learners.SunesisId IS NULL
	-- Sunesis learner records not linked to a Smart Assessor learner
	OR users.smart_assessor_id IS NULL)
GROUP BY
	users.id
ORDER BY
	organisations.legal_name, users.surname, users.firstnames
SQL;
		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$duplicatedLearners = $this->_getDuplicatedLearners($link);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<colgroup span="7">';
		echo '<col width="100"/>';  // Name
		echo '<col width="80"/>';   // Postcode
		echo '<col width="50"/>';   // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // DOB
		echo '<col width="80"/>';   // ULN
		echo '</colgroup>';
		echo '<colgroup span="5">';
		echo '<col width="100"/>';  // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // DOB
		echo '<col width="80"/>';   // ULN
		echo '</colgroup>';
		echo '<col width="100"/>';  // Action
		echo '<caption>Potential links between existing records</caption>';
		echo '<tr><th colspan="7">Sunesis</th><th colspan="5">Smart Assessor</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th colspan="2">Employer</th><th colspan="5">Learner</th><th colspan="5">Learner</th></tr>';
		echo '<tr><th>Name</th><th>Postcode</th><th>ID</th><th>Surname</th><th>Firstnames</th><th>DOB</th><th>ULN</th>'
			. '<th>ID</th><th>Surname</th><th>Firstnames</th><th>DOB</th><th>ULN</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
		foreach ($rs as $row) {
			$rowValid = true;
			foreach ($requiredFields as $field) {
				$rowValid = $rowValid && !empty($row[$field]);
				if (!$rowValid) {
					break;
				}
			}
			$rowValid = $rowValid && !in_array($row['id'], $duplicatedLearners); // Sunesis learner not duplicated
			$rowValid = $rowValid && (strpos($row['smart_assessor_ids'], ',') === false); // Not more than one match in Smart Assessor

			$smartAssessorIds = explode(',', $row['smart_assessor_ids']); // Unpack
			$rowSpan = count($smartAssessorIds);
			$smartAssessorIds = DAO::quote($smartAssessorIds); // Repack with quotes

			if (!$rowValid) {
				echo '<tr style="color:gray" class="Data">';
			} else {
				echo '<tr class="Data">';
			}

			// Employer
			echo '<td align="left" rowspan="', $rowSpan, '">', htmlspecialchars((string)$row['organisations_legal_name']) , '</td>';
			echo '<td align="left" rowspan="', $rowSpan, '">', htmlspecialchars((string)$row['locations_postcode']) , '</td>';

			// Learner
//			echo '<td align="right"><a href="/do.php?_action=read_user&id=' . $row['id'] . '" target="_blank">' . htmlspecialchars((string)$row['id']) . '</a></td>';
			echo '<td align="right"><a href="/do.php?_action=read_user&username=' . $row['username'] . '" target="_blank">' . htmlspecialchars((string)$row['username']) . '</a></td>';
			echo '<td align="left" rowspan="', $rowSpan, '">', htmlspecialchars((string)$row['surname']), '</td>';
			echo '<td align="left" rowspan="', $rowSpan, '">', htmlspecialchars((string)$row['firstnames']), '</td>';
			echo '<td align="left" rowspan="', $rowSpan, '">', htmlspecialchars((string)$row['dob']), '</td>';
			echo '<td align="left" rowspan="', $rowSpan, '">', htmlspecialchars((string)$row['uln']), '</td>';

			// Get more details on the matching records in SmartAssessor
			$sql = <<<HEREDOC
SELECT
	SmartAssessorId,
	GivenNames,
	FamilyName,
	DateOfBirth,
	ULN,
	HomeAddressPostcode
FROM
	tmp_sa_learners
WHERE
	SmartAssessorId IN ($smartAssessorIds)
HEREDOC;
			$options  = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

			echo '<td align="left" class="SmartAssessorId">', htmlspecialchars((string)$options[0]['SmartAssessorId']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$options[0]['FamilyName']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$options[0]['GivenNames']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$options[0]['DateOfBirth']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$options[0]['ULN']), '</td>';

			if (count($options) == 1) {
				echo sprintf('<td align="center"><input class="SelectRow" type="checkbox" value="%s"/></td>', htmlspecialchars((string)$row['id'] . ':' . $options[0]['SmartAssessorId']));
				echo '</tr>';
			} else {
				echo '<td rowspan="', $rowSpan, '">&nbsp;</td>'; // No checkbox
				echo '</tr>';

				// Remaining matches
				for ($i = 1; $i < count($options); $i++) {
					echo '<tr style="color:gray" class="Data">';
					echo '<td align="left" class="SmartAssessorId">', htmlspecialchars((string)$options[$i]['SmartAssessorId']), '</td>';
					echo '<td align="left">', htmlspecialchars((string)$options[$i]['FamilyName']), '</td>';
					echo '<td align="left">', htmlspecialchars((string)$options[$i]['GivenNames']), '</td>';
					echo '<td align="left">', htmlspecialchars((string)$options[$i]['DateOfBirth']), '</td>';
					echo '<td align="left">', htmlspecialchars((string)$options[$i]['ULN']), '</td>';
					echo '</tr>';
				}
			}
		}
		echo '<tr><td colspan="12">&nbsp;</td><td align="center"><input type="button" id="BtnLink" value="Link"/></td></tr>';
		echo '</table>';
	}


	private function _renderNoMatchSun(PDO $link, $continuing_learners_only = '')
	{
		$join_tr = "";
		if($continuing_learners_only == 'YES')
			$join_tr = " INNER JOIN tr ON users.username = tr.username AND tr.status_code = 1 ";
		$type = User::TYPE_LEARNER;
		$requiredFields = array('firstnames', 'surname', 'uln', 'dob', 'employer_location_id', 'locations_smart_assessor_id');

		$sql = <<<SQL
SELECT
	organisations.id AS `organisations_id`,
	organisations.legal_name AS `organisations_legal_name`,
	organisations.edrs AS `organisations_edrs`,
	locations.id AS `locations_id`,
	locations.full_name AS `locations_full_name`,
	locations.postcode AS `locations_postcode`,
	locations.smart_assessor_id AS `locations_smart_assessor_id`,
	users.id,
	users.username,
	users.employer_location_id,
	users.smart_assessor_id,
	users.surname,
	users.firstnames,
	users.l45 AS `uln`,
	users.dob
FROM
	users INNER JOIN organisations INNER JOIN locations
		ON users.employer_id = organisations.id
		AND users.employer_location_id = locations.id
	$join_tr
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
	-- Sunesis locations linked to a Smart Assessor employer
	AND locations.smart_assessor_id IS NOT NULL
	-- Return only those rows without a matching row in Smart Assessor
	AND tmp.SmartAssessorId IS NULL
ORDER BY
	organisations.legal_name, users.surname, users.firstnames
SQL;

		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$duplicated_learners = $this->_getDuplicatedLearners($link);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<colgroup span="7">';
		echo '<col width="100"/>';  // Name
		echo '<col width="80"/>';   // Postcode
		echo '<col width="50"/>';   // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // DOB
		echo '<col width="80"/>';   // ULN
		echo '</colgroup>';
		echo '<colgroup span="5" style="background-color:#FAFAFA">';
		echo '<col width="100"/>';  // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // DOB
		echo '<col width="80"/>';   // ULN
		echo '</colgroup>';
		echo '<col width="100"/>';  // Action
		echo '<caption>Sunesis learners with no record in Smart Assessor (Push)</caption>';
		echo '<tr><th colspan="7">Sunesis</th><th colspan="5">Smart Assessor</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th colspan="2">Employer</th><th colspan="5">Learner</th><th colspan="5">Learner</th></tr>';
		echo '<tr><th>Name</th><th>Postcode</th><th>ID</th><th>Surname</th><th>Firstnames</th><th>DOB</th><th>ULN</th>'
			. '<th>ID</th><th>Surname</th><th>Firstnames</th><th>DOB</th><th>ULN</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
		foreach ($rs as $row) {
			$rowValid = true;
			foreach ($requiredFields as $field) {
				$rowValid = $rowValid && !empty($row[$field]);
				if (!$rowValid) {
					break;
				}
			}
			if (in_array($row['id'], $duplicated_learners)) {
				$rowValid = false;
			}

			if (!$rowValid) {
				echo '<tr style="color:gray" class="Data">';
			} else {
				echo '<tr class="Data">';
			}

			// Employer
			echo '<td align="left">' . htmlspecialchars((string)$row['organisations_legal_name']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['locations_postcode']) . '</td>';

			// Learner
//			echo '<td align="right"><a href="/do.php?_action=read_user&id=' . $row['id'] . '" target="_blank">' . htmlspecialchars((string)$row['id']) . '</a></td>';
			echo '<td align="right"><a href="/do.php?_action=read_user&username=' . $row['username'] . '" target="_blank">' . htmlspecialchars((string)$row['username']) . '</a></td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['surname']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['firstnames']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['dob']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['uln']) . '</td>';

			// Smart assessor fields
			echo '<td colspan="5">&nbsp;</td>';

			// Action checkbox
			if ($rowValid) {
				echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['id'])); // Valid record for creation in Smart Assessor
			} else {
				echo '<td>&nbsp;</td>'; // Invalid (missing fields)
			}

			echo '</tr>';
		}
		echo '<tr><td colspan="12">&nbsp;</td><td align="center"><input type="button" id="BtnCreate" value="Create"/></td></tr>';
		echo '</table>';
	}


	private function _renderNoMatchSa(PDO $link)
	{
		$type = User::TYPE_LEARNER;
        $requiredFields = array('GivenNames', 'FamilyName', 'DateOfBirth', 'ULN', 'SmartAssessorId');

		$sql = <<<SQL
SELECT
	organisations.legal_name AS `organisations_legal_name`,
	locations.postcode AS `locations_postcode`,
	sa.*
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
	/*INNER JOIN tmp_contract_learners
		ON users.id = tmp_contract_learners.id*/
WHERE
	-- Smart Assessor records with no existing linked record in Sunesis
	sa.SunesisId IS NULL
	-- Sunesis users with no existing linked record in Smart Assessor
	AND users.smart_assessor_id IS NULL
	-- Only those rows where no exact/partial match was made
	AND users.id IS NULL
ORDER BY
	organisations.legal_name, sa.FamilyName, sa.GivenNames
SQL;
		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<colgroup>';
		echo '<col width="100"/>';  // Name
		echo '<col width="80"/>';   // Postcode
		echo '</colgroup>';
		echo '<colgroup style="background-color:#FAFAFA">';
		echo '<col width="50"/>';   // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // DOB
		echo '<col width="80"/>';   // ULN
		echo '</colgroup>';
		echo '<colgroup>';
		echo '<col width="100"/>';  // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // DOB
		echo '<col width="80"/>';   // ULN
		echo '</colgroup>';
		echo '<col width="100"/>';  // Action
		echo '<caption>Smart Assessor learners with no record in Sunesis (Pull)</caption>';
		echo '<tr><th colspan="7">Sunesis</th><th colspan="5">Smart Assessor</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th colspan="2">Employer</th><th colspan="5">Learner</th><th colspan="5">Learner</th></tr>';
		echo '<tr><th>Name</th><th>Postcode</th><th>ID</th><th>Surname</th><th>Firstnames</th><th>DOB</th><th>ULN</th>'
			. '<th>ID</th><th>Surname</th><th>Firstnames</th><th>DOB</th><th>ULN</th><th><input type="checkbox" class="SelectAll"/></tr>';
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
			echo '<td align="right">' . htmlspecialchars((string)$row['organisations_legal_name']) . '</td>';
			echo '<td align="right">' . htmlspecialchars((string)$row['locations_postcode']) . '</td>';
			echo '<td colspan="5">&nbsp;</td>';
			echo '<td align="left" class="SmartAssessorId">' . htmlspecialchars((string)$row['SmartAssessorId']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['FamilyName']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['GivenNames']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['DateOfBirth']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['ULN']) . '</td>';
			//echo '<td style="background-color:#FAFAFA">&nbsp;</td>';

            // Action checkbox
			if ($rowValid) {
                echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['SmartAssessorId'])); // Valid record for creation in Smart Assessor
            } else {
				echo '<td>&nbsp;</td>'; // Invalid (missing fields)
			}

			echo '</tr>';
		}
        echo '<tr><td colspan="12">&nbsp;</td><td align="center"><input type="button" id="BtnCreateinSunesis" value="Create in Sunesis"/></td></tr>';
		echo '</table>';
	}


	private function _renderExactMatch(PDO $link)
	{

        set_time_limit(0);
        ini_set('memory_limit','1024M');
		$type = User::TYPE_LEARNER;

		$sql = <<<SQL
SELECT
	organisations.id AS `organisations_id`,
	organisations.legal_name AS `organisations_legal_name`,
	organisations.edrs AS `organisations_edrs`,
	locations.id AS `locations_id`,
	locations.full_name AS `locations_full_name`,
	locations.postcode AS `locations_postcode`,
	locations.smart_assessor_id AS `locations_smart_assessor_id`,
	users.id,
	users.username,
	users.employer_location_id,
	users.smart_assessor_id,
	users.surname,
	users.firstnames,
	users.l45 AS `uln`,
	users.dob,
	tmp_sa_learners.*
FROM
	users INNER JOIN organisations INNER JOIN locations
		ON users.employer_id = organisations.id
		AND users.employer_location_id = locations.id
	/*INNER JOIN tmp_contract_learners
		ON users.id = tmp_contract_learners.id */
	INNER JOIN tmp_sa_learners
		ON users.id = tmp_sa_learners.SunesisId and users.smart_assessor_id = tmp_sa_learners.SmartAssessorId
WHERE
	-- Sunesis learners only
	users.type = $type
ORDER BY
	organisations.legal_name, locations.full_name, locations.id
SQL;
		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<col width="100"/>';  // Name
		echo '<col width="80"/>';   // Postcode
		echo '<col width="50"/>';   // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // DOB
		echo '<col width="80"/>';   // ULN
		echo '<col width="100"/>';  // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // DOB
		echo '<col width="80"/>';   // ULN
		echo '<col width="100"/>';  // Action
		echo '<caption>Linked learner records</caption>';
		echo '<tr><th colspan="7">Sunesis</th><th colspan="5">Smart Assessor</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th rowspan="1" colspan="2">Employer</th><th colspan="5">Learner</th><th colspan="5">Learner</th></tr>';
		echo '<tr><th>Name</th><th>Postcode</th><th>ID</th><th>Surname</th><th>Firstnames</th><th>DOB</th><th>ULN</th>'
			. '<th>ID</th><th>Surname</th><th>Firstnames</th><th>DOB</th><th>ULN</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
  	foreach ($rs as $row) {
			echo '<tr class="Data">';

			// Employer
			echo '<td align="left">' . htmlspecialchars((string)$row['organisations_legal_name']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['locations_postcode']) . '</td>';

			// Learner
//			echo '<td align="right"><a href="/do.php?_action=read_user&id=' . $row['id'] . '" target="_blank">' . htmlspecialchars((string)$row['id']) . '</a></td>';
			echo '<td align="right"><a href="/do.php?_action=read_user&username=' . $row['username'] . '" target="_blank">' . htmlspecialchars((string)$row['username']) . '</a></td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['surname']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['firstnames']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['dob']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['uln']) . '</td>';

			// Smart assessor fields
			echo '<td align="left" class="SmartAssessorId">' . htmlspecialchars((string)$row['SmartAssessorId']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['FamilyName']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['GivenNames']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['DateOfBirth']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['ULN']) . '</td>';

			echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['id'] . ':' . $row['SmartAssessorId']));

			echo '</tr>';
		}
		//echo '<tr><td colspan="12">&nbsp;</td><td align="center"><input type="button" id="BtnUpdate" value="Update" disabled="disabled"/></td></tr>';
		echo '<tr><td colspan="12">&nbsp;</td><td align="center"><input type="button" id="BtnUnlink" value="Unlink" style="color:red"/></td></tr>';

		echo '</table>';
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
	`ULN` VARCHAR(50),
	`NINumber` VARCHAR(10),
	`Domicile` CHAR(2),
	`DateOfBirth` DATE,
	`Sex` VARCHAR(10),
	`Email` VARCHAR(100),
	`TelNumber` VARCHAR(50),
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

		// All Sunesis learners on a synchronised contract
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
	AND contracts.`sync_learners_smart_assessor` = 1;
SQL;
		DAO::execute($link, $sql);
	}


	private function _renderTemporaryTable(PDO $link)
	{
		$rs = DAO::getResultset($link, "SELECT * FROM tmp_sa_learners", DAO::FETCH_ASSOC);
		HTML::renderResultset($rs);
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
}