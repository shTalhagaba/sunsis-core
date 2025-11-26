<?php
class sa_learnerlinkiv_sync extends ActionController
{
	/**
	 * Create Learner IV in Sunesis from Smart Assessor
	 * @param PDO $link
	 * @override
	 * @throws UnauthorizedException
	 */
	public function indexAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}

        $filterSections = $this->_getParam("filter_sections", array('exactmatch', 'nomatchsa'));
		$filterSectionsOptions = array(
			array('exactmatch', 'Linked records'),
			array('nomatchsa', 'No links (Smart Assessor)')
		);

		include('smartassessor/tpl_sa_learnerlinkiv_sync.php');
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
		$saRecords = $sa->getLearnerBatchDetails();


		$this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_sa_learnerbatches', $saRecords);

        $saRecords = $sa->getLearners();
		DAO::multipleRowInsert($link, 'tmp_sa_learners', $saRecords);

        $saRecords = $sa->getCourses();
		DAO::multipleRowInsert($link, 'tmp_sa_courses', $saRecords);

        $saRecords = $sa->getAssessors();
		DAO::multipleRowInsert($link, 'tmp_sa_assessors', $saRecords);

		$filterSections = $this->_getParam("filter_sections", array());

		if (in_array('exactmatch', $filterSections)) {
			$this->_renderExactMatch($link);
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
		/*foreach($ids as $id) {
			if (!is_numeric($id)) {
				throw new Exception("Illegal non-numeric value for id: " . $id);
			}
		}*/

        foreach ($ids as $id) {
    	   $pair = explode(':', $id);

           $row['AssessorSmartAssessorId']=$pair[0];
           $row['SmartAssessorId']=$pair[1];
           $row['LearnerSmartAssessorId']=$pair[2];


         try
		{
			$sa = new SmartAssessor(false);
			$sa->updateLearnerAssessor($row);
		}
		catch(Exception $e)
		{
			throw new Exception("An error occurred while updating learner IV records. Operation aborted.", 1, $e);
		}

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
				DAO::execute($link, "UPDATE student_qualifications SET smart_assessor_id=NULL WHERE student_qualifications.tr_id={$pair[0]} AND replace(student_qualifications.id,'/','') = {$pair[2]}");
				$sa->updateProgress(array('SunesisId' => $pair[0], 'SmartAssessorId' => $pair[1]));
			}
			//DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			//DAO::transaction_rollback($link);
			throw new Exception("An error occurred while linking progress records. Operation aborted.", 1, $e);
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
				//DAO::execute($link, "UPDATE student_qualifications SET smart_assessor_id=NULL WHERE student_qualifications.tr_id={$pair[0]} AND replace(student_qualifications.id,'/','') = {$pair[2]}");
				$sa->deleteLearnerQualification(array('LearnerSmartAssessorId' => $pair[0], 'UnitId' => $pair[1]));
			}
			//DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			//DAO::transaction_rollback($link);
			throw new Exception("An error occurred while deleting learner qualification records. Operation aborted.", 1, $e);
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
		$saRecords = $sa->getLearnerBatchDetails();
		$this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_sa_learnerbatches', $saRecords);

        $saRecords = $sa->getLearners();
		DAO::multipleRowInsert($link, 'tmp_sa_learners', $saRecords);

        $saRecords = $sa->getCourses();
		DAO::multipleRowInsert($link, 'tmp_sa_courses', $saRecords);

        $saRecords = $sa->getAssessors();
		DAO::multipleRowInsert($link, 'tmp_sa_assessors', $saRecords);

		// Allow longer execution time
		set_time_limit(180); // 3 minutes

		$ids = (array) $this->_getParam("ids");
		if (count($ids) == 0) {
			throw new Exception("No records selected to create");
		}

		$SmartAssessor_ids = DAO::pdo_implode($ids);

$sql = <<<SQL
SELECT
    tr.id AS tr_id,
    u2.username AS iv_name_1,
    tmp_sa_learnerbatches.ActualDate AS actual_date_1,
    tmp_sa_learnerbatches.SampleType AS comment1,
    tmp_sa_learnerbatches.SampleFeedback AS comment2,
    tmp_sa_learnerbatches.BatchId AS smart_assessor_id,
    student_qualifications.auto_id AS auto_id
FROM
  tmp_sa_learnerbatches
	INNER JOIN student_qualifications ON ( replace(student_qualifications.id,'/','') = tmp_sa_learnerbatches.QANCode )
		INNER JOIN tr ON (tr.id = student_qualifications.tr_id)
		INNER JOIN users ON (users.username = tr.username AND users.smart_assessor_id = tmp_sa_learnerbatches.LearnerSmartAssessorId)
        INNER JOIN users u2 ON (u2.smart_assessor_id = tmp_sa_learnerbatches.IQASmartAssessorId)
		INNER JOIN tmp_sa_assessors ON (u2.id = tmp_sa_assessors.SunesisId)
		INNER JOIN student_frameworks ON (student_frameworks.tr_id = tr.id)
		INNER JOIN tmp_sa_courses ON ( tmp_sa_courses.QANCode = replace(student_qualifications.id,'/',''))
WHERE
    tmp_sa_learnerbatches.BatchId IN ($SmartAssessor_ids);
SQL;
		$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		try
		{
			$sa = new SmartAssessor(false);
			foreach ($rows as $row) {
				$sa->createBatchesInSunesis($row);
			}
		}
		catch(Exception $e)
		{
			throw new Exception("An error occurred while creating assessor records. Operation aborted.", 1, $e);
		}

	}


    /**
	 * @param PDO $link
	 * @throws UnauthorizedException
	 * @throws Exception
	 */
	public function updateRecordsInSunesisAction(PDO $link)
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
		/*foreach($ids as $id) {
			if (!is_numeric($id)) {
				throw new Exception("Illegal non-numeric value for id: " . $id);
			}
		}   */

        foreach ($ids as $id) {
    	   $pair = explode(':', $id);

           $row['assessor'] = $pair[0];
           $row['id'] = $pair[1];

            try
    		{
    			$sa = new SmartAssessor(false);
    			$sa->updateLearnerAssessorInSunesis($row);

    		}
    		catch(Exception $e)
    		{
    			throw new Exception("An error occurred while updating IV in Training records. Operation aborted.", 1, $e);
    		}

        }

	}


	private function _renderNoMatchSa(PDO $link)
	{
        $requiredFields = array('BatchId');

		$sql = <<<SQL
SELECT
    student_qualifications.internaltitle AS internaltitle,
    student_qualifications.id AS QAN,
    student_qualifications.unitsUnderAssessment AS unitsUnderAssessment,
	student_qualifications.tr_id,
    student_frameworks.title AS framework,

	users.username,
    users.surname,
    users.firstnames,
    users.id AS user_id,

    tr.firstnames as learnerfirstnames,
    tr.surname as learnerlastname,
    tr.id AS tr_id,

    u2.username AS ivusername,
    u2.smart_assessor_id AS ivsmart_assessor_id,.

    tmp_sa_assessors.LastName,
    tmp_sa_assessors.FirstName,

    tmp_sa_learnerbatches.*,
    tmp_sa_courses.QualificationTitle
FROM
  tmp_sa_learnerbatches
	INNER JOIN student_qualifications ON ( replace(student_qualifications.id,'/','') = tmp_sa_learnerbatches.QANCode )
		INNER JOIN tr ON (tr.id = student_qualifications.tr_id)
		INNER JOIN users ON (users.username = tr.username AND users.smart_assessor_id = tmp_sa_learnerbatches.LearnerSmartAssessorId)
        INNER JOIN users u2 ON (u2.smart_assessor_id = tmp_sa_learnerbatches.IQASmartAssessorId)
		INNER JOIN tmp_sa_assessors ON (u2.id = tmp_sa_assessors.SunesisId)
		INNER JOIN student_frameworks ON (student_frameworks.tr_id = tr.id)
		INNER JOIN tmp_sa_courses ON ( tmp_sa_courses.QANCode = replace(student_qualifications.id,'/',''))
    LEFT OUTER JOIN iv
		ON (iv.tr_id = tr.id  AND iv.smart_assessor_id = tmp_sa_learnerbatches.BatchId)
WHERE
	iv.smart_assessor_id IS NULL
ORDER BY
	tr.id
SQL;
		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);


		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<colgroup>';
		echo '<col width="100"/>';  // Name
		echo '<col width="80"/>';   // Framework
		echo '</colgroup>';
		echo '<colgroup style="background-color:#FAFAFA">';
		echo '<col width="70"/>';   // ID
		echo '<col width="80"/>';  // QAN Code
		echo '<col width="100"/>';  // Qualification
		echo '<col width="80"/>';   // Assessor
		echo '</colgroup>';
		echo '<colgroup>';
		echo '<col width="100"/>';  // ID
		echo '<col width="80"/>';  // QAN Code
		echo '<col width="100"/>';  // Qualification
		echo '<col width="80"/>';   // Assessor
		echo '</colgroup>';
		echo '<col width="100"/>';  // Action
		echo '<caption>Smart Assessor learner link with IV with no record in Sunesis (Pull)</caption>';
		echo '<tr><th colspan="6">Sunesis</th><th colspan="4">Smart Assessor</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th rowspan="1" colspan="2">Training Record</th><th colspan="4">Qualification</th><th colspan="4">Qualification</th></tr>';
		echo '<tr><th>Learner</th><th>Framework</th><th>TR ID</th><th>QAN Code</th><th>Qualification</th><th>IV</th>'
			. '<th>Batch Id</th><th>QAN Code</th><th>Qualification</th><th>IV</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
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

			// Training Record
			echo '<td align="left">' . htmlspecialchars((string)$row['learnerlastname']).' '.htmlspecialchars((string)$row['learnerfirstnames']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['framework']) . '</td>';

			// Qualification
            echo '<td align="left">' . htmlspecialchars((string)$row['tr_id']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['QAN']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['internaltitle']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['ivsmart_assessor_id']) . '</td>';

			// Smart assessor fields
			echo '<td align="left" class="SmartAssessorId">' . htmlspecialchars((string)$row['BatchId']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['QANCode']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['QualificationTitle']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['IQASmartAssessorId']) . '</td>';

            // Action checkbox
			if ($rowValid) {
                echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['BatchId'])); // Valid record for creation in Smart Assessor
            } else {
				echo '<td>&nbsp;</td>'; // Invalid (missing fields)
			}

			echo '</tr>';
		}
        echo '<tr><td colspan="10">&nbsp;</td><td align="center"><input type="button" id="BtnCreateInSunesis" value="Create in Sunesis"/></td></tr>';
		echo '</table>';
	}


	private function _renderExactMatch(PDO $link)
	{
        $sql = <<<SQL
SELECT
    student_qualifications.internaltitle AS internaltitle,
    student_qualifications.id AS QAN,
    student_qualifications.unitsUnderAssessment AS unitsUnderAssessment,
	student_qualifications.tr_id,
    student_frameworks.title AS framework,

	users.username,
    users.surname,
    users.firstnames,
    users.id AS user_id,

    tr.firstnames as learnerfirstnames,
    tr.surname as learnerlastname,
    tr.id AS tr_id,

    u2.username AS ivusername,
    u2.smart_assessor_id AS ivsmart_assessor_id,.

    tmp_sa_assessors.LastName,
    tmp_sa_assessors.FirstName,

    tmp_sa_learnerbatches.*,
    tmp_sa_courses.QualificationTitle
FROM
  tmp_sa_learnerbatches
	INNER JOIN student_qualifications ON ( replace(student_qualifications.id,'/','') = tmp_sa_learnerbatches.QANCode )
		INNER JOIN tr ON (tr.id = student_qualifications.tr_id)
		INNER JOIN users ON (users.username = tr.username AND users.smart_assessor_id = tmp_sa_learnerbatches.LearnerSmartAssessorId)
        INNER JOIN users u2 ON (u2.smart_assessor_id = tmp_sa_learnerbatches.IQASmartAssessorId)
		INNER JOIN tmp_sa_assessors ON (u2.id = tmp_sa_assessors.SunesisId)
		INNER JOIN student_frameworks ON (student_frameworks.tr_id = tr.id)
		INNER JOIN tmp_sa_courses ON ( tmp_sa_courses.QANCode = replace(student_qualifications.id,'/',''))
    INNER JOIN iv
		ON (iv.tr_id = tr.id  AND iv.smart_assessor_id = tmp_sa_learnerbatches.BatchId)
WHERE
    -- Sunesis-SA migrated Not Migrated batches only
	iv.smart_assessor_id IS NOT NULL
ORDER BY
	tr.id
SQL;


		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<col width="100"/>';  // Name
		echo '<col width="80"/>';   // Framework
		echo '<col width="70"/>';   // ID
		echo '<col width="80"/>';  // QAN Code
		echo '<col width="100"/>';  // Qualification
		echo '<col width="80"/>';   // Assessor
		echo '<col width="100"/>';  // ID
		echo '<col width="80"/>';  // QAN Code
		echo '<col width="100"/>';  // Qualification
		echo '<col width="80"/>';   // Assessor
		echo '<col width="100"/>';  // Action
		echo '<caption>Linked IV with Learner records</caption>';
		echo '<tr><th colspan="6">Sunesis</th><th colspan="4">Smart Assessor</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th rowspan="1" colspan="2">Training Record</th><th colspan="4">Qualification</th><th colspan="4">Qualification</th></tr>';
		echo '<tr><th>Learner</th><th>Framework</th><th>TR ID</th><th>QAN Code</th><th>Qualification</th><th>Assessor</th>'
			. '<th>Batch Id</th><th>QAN Code</th><th>Qualification</th><th>Assessor</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
		foreach ($rs as $row) {
			echo '<tr class="Data">';

			// Training Record
			echo '<td align="left">' . htmlspecialchars((string)$row['learnerlastname']).' '.htmlspecialchars((string)$row['learnerfirstnames']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['framework']) . '</td>';

			// Qualification
            echo '<td align="left">' . htmlspecialchars((string)$row['tr_id']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['QAN']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['internaltitle']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['ivsmart_assessor_id']) . '</td>';

			// Smart assessor fields
			echo '<td align="left" class="SmartAssessorId">' . htmlspecialchars((string)$row['BatchId']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['QANCode']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['QualificationTitle']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['IQASmartAssessorId']) . '</td>';

            echo '<td align="left">&nbsp;</td>';
			//echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['BatchId']));

			echo '</tr>';
		}
		echo '<tr><td colspan="10">&nbsp;</td><td align="center"><!--<input type="button" id="BtnUnlink" value="Unlink" style="color:red"/>--></td></tr>';

		echo '</table>';
	}



	private function _createTempTables(PDO $link)
	{
		DAO::execute($link, "DROP TEMPORARY TABLE IF EXISTS tmp_sa_learnerbatches");
		$sql = <<<SQL
CREATE TEMPORARY TABLE tmp_sa_learnerbatches (
	`IQASmartAssessorId` CHAR(50) NOT NULL,
	`CourseSmartAssessorId` CHAR(50),
	`LearnerSmartAssessorId` CHAR(50),
	`BatchId` CHAR(50),
	`SampleType` VARCHAR(100),
    `QANCode` VARCHAR(200),
    `PlannedDate` VARCHAR(100),
    `ActualDate` VARCHAR(100),
    `SampleFeedback` TEXT,
	KEY (`IQASmartAssessorId`)
);
SQL;
		DAO::execute($link, $sql);


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
	`TelNumber` VARCHAR(100),
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
	`TelNumber` VARCHAR(100),
	`Mobile` VARCHAR(30),
	PRIMARY KEY (`SmartAssessorId`),
	KEY (`SunesisId`)
);
SQL;
      DAO::execute($link, $sql);

        DAO::execute($link, "DROP TEMPORARY TABLE IF EXISTS tmp_sa_courses");
		$sql = <<<SQL
CREATE TEMPORARY TABLE tmp_sa_courses (
	`SmartAssessorId` CHAR(50) NOT NULL,
	`QANCode` VARCHAR(100),
    `QualificationTitle` VARCHAR(200),
	PRIMARY KEY (`SmartAssessorId`)
);
SQL;
		DAO::execute($link, $sql);


	}


	private function _renderTemporaryTable(PDO $link)
	{
		$rs = DAO::getResultset($link, "SELECT * FROM tmp_sa_learnerbatches", DAO::FETCH_ASSOC);
		HTML::renderResultset($rs);
	}


	/**
	 * Returns an array of user IDs in Sunesis that are duplicates.
	 * @param PDO $link
	 * @return array
	 */
	private function _getDuplicatedProgressTrack(PDO $link)
	{
		$sql = <<<SQL
SELECT
	GROUP_CONCAT(student_qualifications.id) AS `QAN_Number`
FROM
	student_qualifications
WHERE
	student_qualifications.unitsUnderAssessment > 0
GROUP BY
	student_qualifications.id
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