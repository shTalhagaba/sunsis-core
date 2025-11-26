<?php
class ecordia_tr_sync extends ActionController
{
	public function indexAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}

		$ecordia_enabled = SystemConfig::get("ecordia.soap.enabled");
		$ecordia_username = SystemConfig::get("ecordia.soap.username");
		$ecordia_api_key = SystemConfig::get("ecordia.soap.api_key");

		if (!$ecordia_enabled) {
			throw new Exception("Ecordia integartion is not switched on");
		}
		if (!$ecordia_username) {
			throw new Exception("Missing configuration parameter: ecordia.username");
		}
		if (!$ecordia_api_key) {
			throw new Exception("Missing configuration parameter: ecordia.api_key");
		}

		$filterSections = $this->_getParam("filter_sections", array('nomatchec'));
		$filterSectionsOptions = array(
			array('exactmatch', 'Linked training records'),
			array('nomatchec', 'Unlinked training records')
		);

		include('tpl_ecordia_tr_sync.php');

	}

	public function renderContentAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}
		if (!SystemConfig::get("ecordia.soap.enabled")) {
			echo '<p style="font-weight: bold">Ecordia integration is not enabled for this Sunesis site</b></p>';
			return;
		}

		$ec = new Ecordia();
		$ecRecords = $ec->getLearners();
		$this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_ec_learners', $ecRecords);

		if(DB_NAME=="am_ray_recruit" || DB_NAME=="am_mcq")
		{
			DAO::execute($link, "drop table if exists ec_learners");
			DAO::execute($link, "create table ec_learners select * from tmp_ec_learners");
		}

		$filterSections = $this->_getParam("filter_sections", array());

		if (in_array('exactmatch', $filterSections))
		{
			$this->_renderExactMatch($link);
		}
		if (in_array('nomatchec', $filterSections)) {
			$this->_renderNoMatchEc($link);
		}
	}

	private function _renderExactMatch(PDO $link)
	{
		set_time_limit(0);
		ini_set('memory_limit','1024M');
		$type = User::TYPE_LEARNER;

		$sql = <<<SQL
SELECT
  tr.id AS tr_id,
  tr.firstnames, tr.surname, tr.dob, tr.start_date, tr.target_date,tr.uln,
  (SELECT organisations.legal_name FROM organisations WHERE organisations.id = tr.employer_id) AS sunesis_employer_name,
  (SELECT locations.postcode FROM locations WHERE locations.id = tr.employer_location_id) AS sunesis_employer_postcode,
  tmp_ec_learners.*

FROM
	tr INNER JOIN organisations INNER JOIN locations
		ON tr.employer_id = organisations.id
		AND tr.employer_location_id = locations.id
	INNER JOIN tmp_ec_learners
		ON tr.uln = tmp_ec_learners.ULN AND tr.ecordia_id = tmp_ec_learners.CandidateId
ORDER BY
	organisations.legal_name, locations.full_name, locations.id
SQL;
		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<colgroup span="7">';
		echo '<col width="100"/>';  // Sunesis Employer Name
		echo '<col width="80"/>';   // Sunesis Employer Postcode
		echo '<col width="50"/>';   // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // DOB
		echo '<col width="80"/>';   // ULN
		echo '<col width="80"/>';   // StartDate
		echo '<col width="80"/>';   // PlannedEndDate
		echo '</colgroup>';
		echo '<colgroup span="5" style="background-color:#FAFAFA">';
		echo '<col width="100"/>';  // Ecordia Workplace Name
		echo '<col width="100"/>';  // Ecordia Workplace Postcode
		echo '<col width="100"/>';  // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // DOB
		echo '<col width="80"/>';   // ULN
		echo '<col width="80"/>';   // TargetStartDate
		echo '<col width="80"/>';   // TargetEndDate
		echo '</colgroup>';
		echo '<col width="100"/>';  // Action
		echo '<caption>Linked Training Records</caption>';
		echo '<tr><th colspan="9">Sunesis</th><th colspan="9">Ecordia</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th colspan="2">Employer</th><th colspan="7">Learner</th><th colspan="2">Workplace</th><th colspan="7">Learner</th></tr>';
		echo '<tr><th>Name</th><th>Postcode</th><th>ID</th><th>Surname</th><th>Firstnames</th><th>DOB</th><th>ULN</th><th>Start Date</th><th>Planned End Date</th>'
			. '<th>Name</th><th>Postcode</th><th>Candidate ID</th><th>Surname</th><th>Firstnames</th><th>DOB</th><th>ULN</th><th>Start Date</th><th>Planned End Date</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
		foreach ($rs as $row)
		{
			echo '<tr class="Data">';

			// Employer
			echo '<td align="left">' . htmlspecialchars((string)$row['sunesis_employer_name']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['sunesis_employer_postcode']) . '</td>';

			// Learner
			echo '<td align="right"><a href="/do.php?_action=read_training_record&id=' . $row['tr_id'] . '" target="_blank">' . htmlspecialchars((string)$row['tr_id']) . '</a></td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['surname']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['firstnames']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['dob']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['uln']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['start_date']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['target_date']) . '</td>';

			echo '<td align="left">' . htmlspecialchars((string)$row['WorkplaceName']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['WorkplacePostcode']) . '</td>';
			echo '<td align="left" class="SmartAssessorId">' . htmlspecialchars((string)$row['CandidateId']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['LastName']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['FirstName']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['DateOfBirth']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['ULN']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['TargetStartDate']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['TargetEndDate']) . '</td>';

			echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['tr_id'] . ':' . $row['CandidateId']));

			echo '</tr>';
		}
		echo '<tr><td colspan="18">&nbsp;</td><td align="center"><input type="button" id="BtnUnlink" value="Unlink" style="color:red"/></td></tr>';

		echo '</table>';
	}

	public function unlinkRecordsAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}
		if (!SystemConfig::get("ecordia.soap.enabled")) {
			throw new Exception("Ecordia integration is not enabled for this Sunesis site.");
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
			foreach ($ids as $id)
			{
				$pair = explode(':', $id);
				DAO::execute($link, "UPDATE  tr SET ecordia_id = NULL WHERE  tr.id={$pair[0]}");
			}
		}
		catch(Exception $e)
		{
			throw new Exception("An error occurred while unlinking learner training records. Operation aborted.", 1, $e);
		}

	}

	private function _renderNoMatchEc(PDO $link) // No links in Sunesis
	{
		$requiredFields = array('CandidateId', 'FirstName', 'LastName', 'ULN', 'DateOfBirth', 'WorkplaceName', 'WorkplacePostcode', 'TargetStartDate', 'TargetEndDate');

		$sql = <<<SQL
SELECT
  tr.id AS tr_id,
  tr.firstnames, tr.surname, tr.dob, tr.start_date, tr.target_date,tr.uln,
  (SELECT organisations.legal_name FROM organisations WHERE organisations.id = tr.employer_id) AS sunesis_employer_name,
  (SELECT locations.postcode FROM locations WHERE locations.id = tr.employer_location_id) AS sunesis_employer_postcode,
  tmp.*
FROM
  tmp_ec_learners tmp
  INNER JOIN tr
    ON tmp.ULN = tr.uln
    AND tmp.TargetStartDate = tr.start_date
    AND tmp.TargetEndDate = tr.target_date
WHERE

  (tr.uln IS NOT NULL AND tr.`uln` != '')
  AND
  (tr.ecordia_id IS NULL OR tr.`ecordia_id` = '')
ORDER BY tmp.LastName,
  tmp.FirstName ;


SQL;

		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<colgroup span="7">';
		echo '<col width="100"/>';  // Sunesis Employer Name
		echo '<col width="80"/>';   // Sunesis Employer Postcode
		echo '<col width="50"/>';   // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // DOB
		echo '<col width="80"/>';   // ULN
		echo '<col width="80"/>';   // StartDate
		echo '<col width="80"/>';   // PlannedEndDate
		echo '</colgroup>';
		echo '<colgroup span="5" style="background-color:#FAFAFA">';
		echo '<col width="100"/>';  // Ecordia Workplace Name
		echo '<col width="100"/>';  // Ecordia Workplace Postcode
		echo '<col width="100"/>';  // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // DOB
		echo '<col width="80"/>';   // ULN
		echo '<col width="80"/>';   // TargetStartDate
		echo '<col width="80"/>';   // TargetEndDate
		echo '</colgroup>';
		echo '<col width="100"/>';  // Action
		echo '<caption>Unlinked Training Records</caption>';
		echo '<tr><th colspan="9">Sunesis</th><th colspan="9">Ecordia</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th colspan="2">Employer</th><th colspan="7">Learner</th><th colspan="2">Workplace</th><th colspan="7">Learner</th></tr>';
		echo '<tr><th>Name</th><th>Postcode</th><th>ID</th><th>Surname</th><th>Firstnames</th><th>DOB</th><th>ULN</th><th>Start Date</th><th>Planned End Date</th>'
			. '<th>Name</th><th>Postcode</th><th>Candidate ID</th><th>Surname</th><th>Firstnames</th><th>DOB</th><th>ULN</th><th>Start Date</th><th>Planned End Date</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
		foreach ($rs as $row)
		{
			$rowValid = true;
			foreach ($requiredFields as $field)
			{
				$rowValid = $rowValid && !empty($row[$field]);
				if (!$rowValid)
				{
					break;
				}
			}

			if (!$rowValid)
				echo '<tr style="color:gray" class="Data">';
			else
				echo '<tr class="Data">';

			echo '<td align="left">' . htmlspecialchars((string)$row['sunesis_employer_name']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['sunesis_employer_postcode']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['tr_id']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['surname']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['firstnames']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['dob']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['uln']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['start_date']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['target_date']) . '</td>';

			echo '<td align="left">' . htmlspecialchars((string)$row['WorkplaceName']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['WorkplacePostcode']) . '</td>';
			echo '<td align="left" class="SmartAssessorId">' . htmlspecialchars((string)$row['CandidateId']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['LastName']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['FirstName']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['DateOfBirth']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['ULN']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['TargetStartDate']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['TargetEndDate']) . '</td>';


			// Action checkbox
			if ($rowValid)
			{
				echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['tr_id'] . ':' . $row['CandidateId'])); // Valid record for creation in Smart Assessor
			}
			else
			{
				echo '<td>&nbsp;</td>'; // Invalid (missing fields)
			}

			echo '</tr>';
		}
		echo '<tr><td colspan="18">&nbsp;</td><td align="center"><input type="button" id="BtnLink" value="Link"/></td></tr>';
		echo '</table>';
	}

	/**
	 * @param PDO $link
	 * @throws UnauthorizedException
	 * @throws Exception
	 */
	public function linkRecordsAction(PDO $link)
	{

		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}
		if (!SystemConfig::get("ecordia.soap.enabled")) {
			throw new Exception("Ecordia integration is not enabled for this Sunesis site.");
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
			foreach ($ids as $id)
			{
				$pair = explode(':', $id);
				DAO::execute($link, "UPDATE tr SET ecordia_id='{$pair[1]}' WHERE tr.id={$pair[0]}");
			}
		}
		catch(Exception $e)
		{
			throw new Exception("An error occurred while linking training records. Operation aborted.", 1, $e);
		}
	}

	private function _createTempTables(PDO $link)
	{
		DAO::execute($link, "DROP TEMPORARY TABLE IF EXISTS tmp_ec_learners");
		$sql = <<<SQL
CREATE TEMPORARY TABLE tmp_ec_learners (
	`CandidateId` CHAR(36) NOT NULL,
	`SunesisId` BIGINT,
	`Title` VARCHAR(10),
	`FirstName` VARCHAR(100),
	`MiddleName` VARCHAR(100),
	`LastName` VARCHAR(100),
	`Email` VARCHAR(100),
	`PhoneNumber` VARCHAR(50),
	`DateOfBirth` DATE,
	`ULN` VARCHAR(50),
	`LearnRefNumber` VARCHAR(12),
	`WorkplaceName` VARCHAR(250),
	`WorkplacePostcode` VARCHAR(10),
	`CourseCode` VARCHAR(100),
	`TargetStartDate` DATE,
	`TargetEndDate` DATE,
	`EcordiaUsername` VARCHAR(100),
	`PortfolioStatus` VARCHAR(50),
	PRIMARY KEY (`CandidateId`),
	KEY (`SunesisId`),
	KEY (`ULN`)
);
SQL;
		DAO::execute($link, $sql);
	}
}