<?php
class sa_assessor_sync extends ActionController
{
	/**
	 * Compare assessors in Smart Assessor with assessors in Sunesis
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

		include('smartassessor/tpl_sa_assessor_sync.php');
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
		$saRecords = $sa->getAssessors();
		$this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_sa_assessors', $saRecords);

		if(DB_NAME=="am_ligauk" OR DB_NAME=="am_pathway")
		{
			DAO::execute($link, "drop table sa_assessors");
			DAO::execute($link, "create table sa_assessors select * from tmp_sa_assessors");
		}


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
		$user_ids = DAO::pdo_implode($ids);

		$type = User::TYPE_ASSESSOR;
		$type_verifier = User::TYPE_VERIFIER;

		$sql = <<<SQL
SELECT
	users.id AS `SunesisId`,
	users.firstnames AS `FirstName`,
	users.surname AS `LastName`,
    users.username AS `UserName`,
    users.password AS `Password`,
	users.home_email AS `Email`,
	users.home_telephone AS `Telephone`,
	users.home_mobile AS `Mobile`,
	users.home_address_line_3 AS `Region`,
    CASE users.type WHEN 3 THEN 3072 WHEN 4 THEN 512 ELSE NULL END AS `UserType`
FROM
	users INNER JOIN locations
		ON users.employer_location_id = locations.id
WHERE
	users.smart_assessor_id IS NULL
 	AND users.firstnames IS NOT NULL
 	AND users.surname IS NOT NULL
 	AND (users.type = {$type} OR users.type = {$type_verifier})
	AND users.id IN ($user_ids);
SQL;
		$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		try
		{
			$sa = new SmartAssessor(false);
			foreach ($rows as $row) {
				$sa->createAssessor($row);
			}
		}
		catch(Exception $e)
		{
			throw new Exception("An error occurred while creating assessor records. Operation aborted.", 1, $e);
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
				$sa->updateAssessor(array('SunesisId' => $pair[0], 'SmartAssessorId' => $pair[1], 'UserType' => $pair[2]));
			}
			//DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			//DAO::transaction_rollback($link);
			throw new Exception("An error occurred while linking assessor records. Operation aborted.", 1, $e);
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
				$sa->updateAssessor(array('SunesisId' => '', 'SmartAssessorId' => $pair[1], 'UserType' => $pair[2]));
			}
			//DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			//DAO::transaction_rollback($link);
			throw new Exception("An error occurred while unlinking assessor records. Operation aborted.", 1, $e);
		}

	}


	/**
	 * @param PDO $link
	 * @throws UnauthorizedException
	 * @throws Exception
	 */
	public function createRecordsInSunesisAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}
		if (!SystemConfig::get("smartassessor.soap.enabled")) {
			throw new Exception("SmartAssessor integration is not enabled for this Sunesis site.");
		}

		// Create temporary table and Insert records from Smart Assessor
		$sa = new SmartAssessor();
		$saRecords = $sa->getAssessors();
		$this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_sa_assessors', $saRecords);

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
    sa.SmartAssessorId AS smart_assessor_id,
    sa.FirstName AS firstnames,
	sa.LastName AS surname,
    sa.UserName AS username,
    sa.Password AS password,
	sa.Email AS home_email,
	sa.Telephone AS home_telephone,
	sa.Mobile AS home_mobile,
	sa.Region AS home_address_line_3,
    CASE sa.UserType WHEN 3072 THEN 3 WHEN 512 THEN 4 ELSE NULL END AS `type`
FROM
	tmp_sa_assessors AS sa
WHERE
    sa.SmartAssessorId IN ($SmartAssessor_ids);
SQL;
		$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		try
		{
			$sa = new SmartAssessor(false);
			foreach ($rows as $row) {
				$sa->createAssessorInSunesis($row);
			}
		}
		catch(Exception $e)
		{
			throw new Exception("An error occurred while creating assessor records. Operation aborted.", 1, $e);
		}
	}


	private function _renderPartialMatch(PDO $link)
	{
		$type = User::TYPE_ASSESSOR;
		$type_verifier = User::TYPE_VERIFIER;
		$requiredFields = array('firstnames', 'surname');

		$sql = <<<SQL
SELECT
	organisations.id AS `organisations_id`,
	organisations.legal_name AS `organisations_legal_name`,
	organisations.edrs AS `organisations_edrs`,
	locations.id AS `locations_id`,
	locations.full_name AS `locations_full_name`,
	locations.postcode AS `locations_postcode`,
	users.id,
	users.employer_location_id,
	users.smart_assessor_id,
	users.surname,
	users.firstnames,
    users.username,
	users.dob,
    users.type,
	GROUP_CONCAT(tmp_sa_assessors.SmartAssessorId) AS `smart_assessor_ids`
FROM
	users INNER JOIN organisations INNER JOIN locations
		ON users.employer_id = organisations.id
		AND users.employer_location_id = locations.id
	INNER JOIN tmp_sa_assessors
		-- Partial match on name and UserName
		ON (users.firstnames = tmp_sa_assessors.FirstName AND users.surname = tmp_sa_assessors.LastName)
WHERE
	-- Sunesis assessor records only
	(users.`type` = {$type} OR users.`type` = {$type_verifier})
	-- Smart Assessor assessor records not linked to a Sunesis assessor
	AND tmp_sa_assessors.SunesisId IS NULL
	-- Sunesis assessor records not linked to a Smart Assessor assessor
	AND users.smart_assessor_id IS NULL
GROUP BY
	users.id
ORDER BY
	users.surname, users.firstnames
SQL;
		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$duplicatedAssessors = $this->_getDuplicatedAssessors($link);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<colgroup span="7">';
		echo '<col width="100"/>';  // Name
		echo '<col width="80"/>';   // Postcode
		echo '<col width="50"/>';   // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // Username
		echo '</colgroup>';
		echo '<colgroup span="5">';
		echo '<col width="100"/>';  // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // Username
		echo '</colgroup>';
		echo '<col width="100"/>';  // Action
		echo '<caption>Potential links between existing records</caption>';
		echo '<tr><th colspan="6">Sunesis</th><th colspan="4">Smart Assessor</th><th rowspan=2">User Type</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th colspan="2">Training Provider</th><th colspan="4">Assessor</th><th colspan="4">Assessor</th></tr>';
		echo '<tr><th>Name</th><th>Postcode</th><th>ID</th><th>Surname</th><th>Firstnames</th><th>Username</th>'
			. '<th>ID</th><th>Surname</th><th>Firstnames</th><th>Username</th><th></th><th><input type="checkbox" class="SelectAll"/></th></tr>';
		foreach ($rs as $row) {
			$rowValid = true;
			foreach ($requiredFields as $field) {
				$rowValid = $rowValid && !empty($row[$field]);
				if (!$rowValid) {
					break;
				}
			}
			$rowValid = $rowValid && !in_array($row['id'], $duplicatedAssessors); // Sunesis assessor not duplicated
			$rowValid = $rowValid && (strpos($row['smart_assessor_ids'], ',') === false); // Not more than one match in Smart Assessor

			$smartAssessorIds = explode(',', $row['smart_assessor_ids']); // Unpack
			$rowSpan = count($smartAssessorIds);
			$smartAssessorIds = DAO::quote($smartAssessorIds); // Repack with quotes

			if (!$rowValid) {
				echo '<tr style="color:gray" class="Data">';
			} else {
				echo '<tr class="Data">';
			}

			// Training Provider
			echo '<td align="left" rowspan="', $rowSpan, '">', htmlspecialchars((string)$row['organisations_legal_name']) , '</td>';
			echo '<td align="left" rowspan="', $rowSpan, '">', htmlspecialchars((string)$row['locations_postcode']) , '</td>';

			// Assessor
			echo '<td align="right"><a href="/do.php?_action=read_user&id=' . $row['id'] . '" target="_blank">' . htmlspecialchars((string)$row['id']) . '</a></td>';
			echo '<td align="left" rowspan="', $rowSpan, '">', htmlspecialchars((string)$row['surname']), '</td>';
			echo '<td align="left" rowspan="', $rowSpan, '">', htmlspecialchars((string)$row['firstnames']), '</td>';
			echo '<td align="left" rowspan="', $rowSpan, '">', htmlspecialchars((string)$row['username']), '</td>';

			// Get more details on the matching records in SmartAssessor
			$sql = <<<HEREDOC
SELECT
	SmartAssessorId,
	FirstName,
	LastName,
	UserName,
	UserType
FROM
	tmp_sa_assessors
WHERE
	SmartAssessorId IN ($smartAssessorIds)
HEREDOC;
			$options  = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

			echo '<td align="left" class="SmartAssessorId">', htmlspecialchars((string)$options[0]['SmartAssessorId']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$options[0]['LastName']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$options[0]['FirstName']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$options[0]['UserName']), '</td>';

			if($row['type'] == $type_verifier){
				echo '<td>IV</td>';
			} else {
				echo '<td>Assessor</td>';
			}

			if (count($options) == 1) {
				echo sprintf('<td align="center"><input class="SelectRow" type="checkbox" value="%s"/></td>', htmlspecialchars((string)$row['id'] . ':' . $options[0]['SmartAssessorId'] . ':' . $options[0]['UserType']));
				echo '</tr>';
			} else {
				echo '<td rowspan="', $rowSpan, '">&nbsp;</td>'; // No checkbox
				echo '</tr>';

				// Remaining matches
				for ($i = 1; $i < count($options); $i++) {
					echo '<tr style="color:gray" class="Data">';
					echo '<td align="left" class="SmartAssessorId">', htmlspecialchars((string)$options[$i]['SmartAssessorId']), '</td>';
					echo '<td align="left">', htmlspecialchars((string)$options[$i]['FirstName']), '</td>';
					echo '<td align="left">', htmlspecialchars((string)$options[$i]['LastName']), '</td>';
					echo '<td align="left">', htmlspecialchars((string)$options[$i]['UserName']), '</td>';
					echo '</tr>';
				}
			}
		}
		echo '<tr><td colspan="11">&nbsp;</td><td align="center"><input type="button" id="BtnLink" value="Link"/></td></tr>';
		echo '</table>';
	}


	private function _renderNoMatchSun(PDO $link)
	{
		$type = User::TYPE_ASSESSOR;
		$type_verifier = User::TYPE_VERIFIER;
		$requiredFields = array('firstnames', 'surname','username');

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
	users.employer_location_id,
	users.smart_assessor_id,
	users.surname,
	users.firstnames,
	users.username AS username,
	users.dob,
    users.type
FROM
	users INNER JOIN organisations INNER JOIN locations
		ON users.employer_id = organisations.id
		AND users.employer_location_id = locations.id
	LEFT OUTER JOIN tmp_sa_assessors AS tmp
		-- Partial match on name and username
		ON (tmp.FirstName = users.firstnames AND tmp.LastName = users.surname)
WHERE
	-- Sunesis assessors only
	(users.`type` = {$type} OR users.`type` = {$type_verifier})
	-- Sunesis assessors without an existing link to a Smart Assessor assessor
	AND users.smart_assessor_id IS NULL
	-- Smart Assessor assessor without an existing link to a Sunesis assessor
	AND tmp.SunesisId IS NULL
	-- Return only those rows without a matching row in Smart Assessor
	AND tmp.SmartAssessorId IS NULL
ORDER BY
	users.surname, users.firstnames
SQL;

		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$duplicated_assessors = $this->_getDuplicatedAssessors($link);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<colgroup span="7">';
		echo '<col width="100"/>';  // Name
		echo '<col width="80"/>';   // Postcode
		echo '<col width="50"/>';   // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // USername
		echo '</colgroup>';
		echo '<colgroup span="5" style="background-color:#FAFAFA">';
		echo '<col width="100"/>';  // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // Username
		echo '</colgroup>';
		echo '<col width="100"/>';  // Action
		echo '<caption>Sunesis assessors with no record in Smart Assessor (Push)</caption>';
		echo '<tr><th colspan="6">Sunesis</th><th colspan="4">Smart Assessor</th><th rowspan=2">User Type</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th colspan="2">Training Provider</th><th colspan="4">Assessor</th><th colspan="4">Assessor</th></tr>';
		echo '<tr><th>Name</th><th>Postcode</th><th>ID</th><th>Surname</th><th>Firstnames</th><th>Username</th>'
			. '<th>ID</th><th>Surname</th><th>Firstnames</th><th>Username</th><th></th><th><input type="checkbox" class="SelectAll"/></th></tr>';
		foreach ($rs as $row) {
			$rowValid = true;
			foreach ($requiredFields as $field) {
				$rowValid = $rowValid && !empty($row[$field]);
				if (!$rowValid) {
					break;
				}
			}
			if (in_array($row['id'], $duplicated_assessors)) {
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

			// Assessor
			echo '<td align="right"><a href="/do.php?_action=read_user&id=' . $row['id'] . '" target="_blank">' . htmlspecialchars((string)$row['id']) . '</a></td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['surname']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['firstnames']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['username']) . '</td>';

			// Smart assessor fields
			echo '<td colspan="4">&nbsp;</td>';

			if($row['type'] == $type_verifier){
				echo '<td>IV</td>';
			} else {
				echo '<td>Assessor</td>';
			}

			// Action checkbox
			if ($rowValid) {
				echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['id'])); // Valid record for creation in Smart Assessor
			} else {
				echo '<td>&nbsp;</td>'; // Invalid (missing fields)
			}

			echo '</tr>';
		}
		echo '<tr><td colspan="11">&nbsp;</td><td align="center"><input type="button" id="BtnCreate" value="Create"/></td></tr>';
		echo '</table>';
	}


	private function _renderNoMatchSa(PDO $link)
	{
		$type = User::TYPE_ASSESSOR;
		$type_verifier = User::TYPE_VERIFIER;
		$requiredFields = array('FirstName', 'LastName', 'UserName', 'SmartAssessorId');

		$sql = <<<SQL
SELECT
	sa.*,
    CASE sa.UserType WHEN 512 THEN 'IV' WHEN 3072 THEN 'Assessor' ELSE NULL END AS `UserType`
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
ORDER BY
	sa.FirstName, sa.LastName
SQL;
		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);


		//Get Training Provider of Assessor
		$query_getTP = <<<SQL
SELECT
    organisations.id as employer_id,
    organisations.legal_name,
    locations.id as employer_location_id,
    locations.postcode
FROM
    organisations
    INNER JOIN locations
        ON locations.organisations_id=organisations.id
WHERE
    organisation_type = 3
    AND ukprn in (select ukprn from organisations where organisation_type = 1)
ORDER BY organisations.id LIMIT 1
SQL;
		$rs_TP = DAO::getResultset($link, $query_getTP, DAO::FETCH_ASSOC);


		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<colgroup>';
		echo '<col width="100"/>';  // Name
		echo '<col width="80"/>';   // Postcode
		echo '</colgroup>';
		echo '<colgroup style="background-color:#FAFAFA">';
		echo '<col width="50"/>';   // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // Username
		echo '</colgroup>';
		echo '<colgroup>';
		echo '<col width="100"/>';  // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // Username
		echo '</colgroup>';
		echo '<col width="100"/>';  // Action
		echo '<caption>Smart Assessor assessors with no record in Sunesis (Pull)</caption>';
		echo '<tr><th colspan="6">Sunesis</th><th colspan="4">Smart Assessor</th><th rowspan=2">User Type</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th colspan="2">Training Provider</th><th colspan="4">Assessor</th><th colspan="4">Assessor</th></tr>';
		echo '<tr><th>Name</th><th>Postcode</th><th>ID</th><th>Surname</th><th>Firstnames</th><th>Username</th>'
			. '<th>ID</th><th>Surname</th><th>Firstnames</th><th>Username</th><th></th><th><input type="checkbox" class="SelectAll"/></tr>';
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

			echo '<td>'.$rs_TP[0]['legal_name'].'</td>';
			echo '<td>'.$rs_TP[0]['postcode'].'</td>';
			echo '<td colspan="4">&nbsp;</td>';
			echo '<td align="left" class="SmartAssessorId">' . htmlspecialchars((string)$row['SmartAssessorId']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['LastName']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['FirstName']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['UserName']) . '</td>';

			echo '<td align="left">' . htmlspecialchars((string)$row['UserType']) . '</td>';

			if($row['UserType'] == 'IV')
				$row['UserType'] = 512;
			elseif($row['UserType'] == 'Assessor')
				$row['UserType'] = 3072;


			// Action checkbox
			if ($rowValid) {
				echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['SmartAssessorId'])); // Valid record for creation in Smart Assessor
			} else {
				echo '<td>&nbsp;</td>'; // Invalid (missing fields)
			}

			echo '</tr>';
		}
		echo '<tr><td colspan="11">&nbsp;</td><td align="center"><input type="button" id="BtnCreateInSunesis" value="Create in Sunesis"/></td></tr>';
		echo '</table>';
	}


	private function _renderExactMatch(PDO $link)
	{
		$type = User::TYPE_ASSESSOR;
		$type_verifier = User::TYPE_VERIFIER;

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
	users.employer_location_id,
	users.smart_assessor_id,
	users.surname,
	users.firstnames,
    users.username,
	users.dob,
    users.type,
	tmp_sa_assessors.*
FROM
	users INNER JOIN organisations INNER JOIN locations
		ON users.employer_id = organisations.id
		AND users.employer_location_id = locations.id
	INNER JOIN tmp_sa_assessors
		ON users.id = tmp_sa_assessors.SunesisId
WHERE
	-- Sunesis assessors only
	(users.type = $type OR users.type = $type_verifier)
ORDER BY
	users.surname, users.firstnames
SQL;
		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<col width="100"/>';  // Name
		echo '<col width="80"/>';   // Postcode
		echo '<col width="50"/>';   // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // Username
		echo '<col width="100"/>';  // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // Username
		echo '<col width="100"/>';  // Action
		echo '<caption>Linked assessor records</caption>';
		echo '<tr><th colspan="6">Sunesis</th><th colspan="4">Smart Assessor</th><th rowspan=2">User Type</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th rowspan="1" colspan="2">Training Provider</th><th colspan="4">Assessor</th><th colspan="4">Assessor</th></tr>';
		echo '<tr><th>Name</th><th>Postcode</th><th>ID</th><th>Surname</th><th>Firstnames</th><th>Username</th>'
			. '<th>ID</th><th>Surname</th><th>Firstnames</th><th>Username</th><th></th><th><input type="checkbox" class="SelectAll"/></th></tr>';
		foreach ($rs as $row) {
			echo '<tr class="Data">';

			// Training Provider
			echo '<td align="left">' . htmlspecialchars((string)$row['organisations_legal_name']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['locations_postcode']) . '</td>';

			// Assessor
			echo '<td align="right"><a href="/do.php?_action=read_user&id=' . $row['id'] . '" target="_blank">' . htmlspecialchars((string)$row['id']) . '</a></td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['surname']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['firstnames']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['username']) . '</td>';

			// Smart assessor fields
			echo '<td align="left" class="SmartAssessorId">' . htmlspecialchars((string)$row['SmartAssessorId']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['LastName']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['FirstName']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['UserName']) . '</td>';

			if($row['type'] == $type_verifier){
				echo '<td>IV</td>';
			} else {
				echo '<td>Assessor</td>';
			}

			echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['id'] . ':' . $row['SmartAssessorId'] . ':' . $row['UserType']));

			echo '</tr>';
		}
		echo '<tr><td colspan="11">&nbsp;</td><td align="center"><input type="button" id="BtnUnlink" value="Unlink" style="color:red"/></td></tr>';

		echo '</table>';
	}



	private function _createTempTables(PDO $link)
	{
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


	private function _renderTemporaryTable(PDO $link)
	{
		$rs = DAO::getResultset($link, "SELECT * FROM tmp_sa_assessors", DAO::FETCH_ASSOC);
		HTML::renderResultset($rs);
	}


	/**
	 * Returns an array of user IDs in Sunesis that are duplicates.
	 * @param PDO $link
	 * @return array
	 */
	private function _getDuplicatedAssessors(PDO $link)
	{
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
}