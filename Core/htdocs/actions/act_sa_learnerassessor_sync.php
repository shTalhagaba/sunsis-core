<?php
class sa_learnerassessor_sync extends ActionController
{
	/**
	 * Compare Learner Assessor in Sunesis with Learner Course Assessor in Smart Assessor
	 * @param PDO $link
	 * @override
	 * @throws UnauthorizedException
	 */
	public function indexAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}

		//$filterSections = $this->_getParam("filter_sections", array('partialmatch', 'nomatchsun'));
        $filterSections = $this->_getParam("filter_sections", array('nomatchsun'));
		$filterSectionsOptions = array(
			array('exactmatch', 'Linked records'),
			array('partialmatch', 'No Match (Sun-SA)'),
			/*array('nomatchsun', 'No links (Sunesis)'),*/
			array('nomatchsa', 'No links (Smart Assessor)')
		);

		include('smartassessor/tpl_sa_learnerassessor_sync.php');
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
		$saRecords = $sa->getGetLearnerCourseDetail();
		$this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_sa_learnercourse', $saRecords);

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
			throw new Exception("An error occurred while updating learner assessor records. Operation aborted.", 1, $e);
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
    			throw new Exception("An error occurred while updating assessor in Training records. Operation aborted.", 1, $e);
    		}

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
    			throw new Exception("An error occurred while updating assessor in Training records. Operation aborted.", 1, $e);
    		}

        }

	}


    private function _renderPartialMatch(PDO $link)
	{
		$requiredFields = array('SAAssUserName','SAAssSunesisId');

		$sql = <<<SQL
SELECT
    student_qualifications.internaltitle AS internaltitle,
    student_qualifications.id AS QAN,
    student_qualifications.unitsUnderAssessment AS unitsUnderAssessment,
    student_frameworks.title as framework,
    tr.id AS tr_id,

	users.id AS userid,
	users.username as learnerusername,
	users.surname as learnerlastname,
	users.firstnames as learnerfirstnames,

	u2.username AS sunUserName,
	tmp_sa_assessors.UserName AS SAAssUserName,
    tmp_sa_assessors.SunesisId AS SAAssSunesisId,
	u2.surname,
	u2.firstnames,

	tmp_sa_learnercourse.*,

    tmp_sa_courses.QANCode,
    tmp_sa_courses.QualificationTitle
FROM
    student_qualifications
        INNER JOIN tmp_sa_learnercourse ON ( tmp_sa_learnercourse.QANCode = replace(student_qualifications.id,'/',''))
        INNER JOIN tr ON student_qualifications.tr_id = tr.id
        INNER JOIN users ON (users.username = tr.username AND users.smart_assessor_id = tmp_sa_learnercourse.LearnerSmartAssessorId)
        INNER JOIN users u2 ON (u2.id = tr.assessor)
        INNER JOIN tmp_sa_assessors ON (tmp_sa_assessors.SmartAssessorId = tmp_sa_learnercourse.AssessorSmartAssessorId)
        INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
        INNER JOIN tmp_sa_courses ON ( tmp_sa_courses.QANCode = replace(student_qualifications.id,'/',''))
WHERE
	-- Sunesis-SA migrated Not Migrated Assessors only
	tmp_sa_learnercourse.QANCode IS NOT NULL

    -- Sunesis-SA Not Migrated Learner Assessors only
	AND tmp_sa_learnercourse.AssessorSmartAssessorId IS NOT NULL

    -- Sunesis-SA migrated Not matched Assessors only
    AND u2.smart_assessor_id != tmp_sa_learnercourse.AssessorSmartAssessorId
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
		echo '<col width="80"/>';   // Progress
		echo '</colgroup>';
		echo '<colgroup>';
		echo '<col width="100"/>';  // ID
		echo '<col width="80"/>';  // QAN Code
		echo '<col width="100"/>';  // Qualification
		echo '<col width="80"/>';   // Progress
		echo '</colgroup>';
		echo '<col width="100"/>';  // Action
		echo '<caption>No match in assigned assessors in Sunesis and Smart Assessor</caption>';
		echo '<tr><th colspan="6">Sunesis</th><th colspan="4">Smart Assessor</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th rowspan="1" colspan="2">Training Record</th><th colspan="4">Qualification</th><th colspan="4">Qualification</th></tr>';
		echo '<tr><th>Learner</th><th>Framework</th><th>TR ID</th><th>QAN Code</th><th>Qualification</th><th>Assessor</th>'
			. '<th>Learner</th><th>QAN Code</th><th>Qualification</th><th>Assessor</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
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
  			echo '<td align="left"><font color="red">' . htmlspecialchars((string)$row['sunUserName']). '</font></td>';

              // Smart assessor fields
            echo '<td align="left">' . htmlspecialchars((string)$row['learnerlastname']).' '.htmlspecialchars((string)$row['learnerfirstnames']) . '</td>';
  			echo '<td align="left">' . htmlspecialchars((string)$row['QANCode']) . '</td>';
  			echo '<td align="left">' . htmlspecialchars((string)$row['QualificationTitle']) . '</td>';


              if($row['SAAssSunesisId']){
              echo '<td align="left">'.$row['SAAssUserName'].' <font color="green">(Linked)</font></td>';
              } else {
              echo '<td align="left">'.$row['SAAssUserName'].' <font color="red">(Not Linked)</font></td>';
              }


              // Action checkbox
  			if ($rowValid) {
  				echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['SAAssUserName']).":".htmlspecialchars((string)$row['tr_id'])); // Valid record for creation in Smart Assessor
  			} else {
  				echo '<td>&nbsp;</td>'; // Invalid (missing fields)
  			}

  			echo '</tr>';

		}
		echo '<tr><td colspan="10">&nbsp;</td><td align="center"><input type="button" id="BtnUpdateInSunesis" value="Update in Sunesis"/></td></tr>';
        echo '<tr><td colspan="11" align="left">
            <font color="red">Assessor</font> - Different assigned assessor in Sunesis<br/>
            <font color="green">Linked</font> - Assessor assigned in SA is linked with Sunesis<br/>
            <font color="red">Not Linked</font> - Assessor assigned in SA is not linked with Sunesis Assessor
            </td></tr>';
		echo '</table>';
	}


    private function _renderNoMatchSun(PDO $link)
	{
		$requiredFields = array('firstnames', 'surname');

	  /*	$sql = <<<SQL
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

    tmp_sa_assessors.LastName,
    tmp_sa_assessors.FirstName,
    tmp_sa_assessors.SmartAssessorId as OrgAssessorSmartAssessorId,

    tmp_sa_learnercourse.*
FROM
	student_qualifications
      INNER JOIN tr ON (tr.id = student_qualifications.tr_id)
      INNER JOIN users ON (tr.assessor = users.username)
      INNER JOIN tmp_sa_assessors ON (users.id = tmp_sa_assessors.SunesisId)
      INNER JOIN student_frameworks ON (student_frameworks.tr_id = tr.id)
    INNER JOIN tmp_sa_learnercourse ON (replace(student_qualifications.id,'/','') = tmp_sa_learnercourse.QANCode AND tmp_sa_learnercourse.AssessorSmartAssessorId = users.smart_assessor_id)
WHERE
	-- Sunesis-SA migrated progress only
	tmp_sa_learnercourse.AssessorSmartAssessorId IS NULL
ORDER BY
    tr.id
SQL;*/

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
    tr.assessor,

    tmp_sa_learnercourse.*,

    tmp_sa_courses.QANCode,
    tmp_sa_courses.QualificationTitle
FROM
	student_qualifications
        INNER JOIN tmp_sa_learnercourse ON ( tmp_sa_learnercourse.QANCode = replace(student_qualifications.id,'/',''))
        INNER JOIN tr ON student_qualifications.tr_id = tr.id
        INNER JOIN users ON (users.username = tr.username AND users.smart_assessor_id = tmp_sa_learnercourse.LearnerSmartAssessorId)
        INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
        INNER JOIN tmp_sa_courses ON ( tmp_sa_courses.QANCode = replace(student_qualifications.id,'/',''))
WHERE
	tmp_sa_learnercourse.AssessorSmartAssessorId IS NULL

    AND tr.assessor IS NOT NULL
ORDER BY
	users.surname, users.firstnames
SQL;

		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
	 //	$duplicated_assessors = $this->_getDuplicatedAssessors($link);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<colgroup>';
		echo '<col width="100"/>';  // Name
		echo '<col width="80"/>';   // Framework
		echo '</colgroup>';
		echo '<colgroup style="background-color:#FAFAFA">';
		echo '<col width="70"/>';   // ID
		echo '<col width="80"/>';  // QAN Code
		echo '<col width="100"/>';  // Qualification
		echo '<col width="80"/>';   // Progress
		echo '</colgroup>';
		echo '<colgroup>';
		echo '<col width="100"/>';  // ID
		echo '<col width="80"/>';  // QAN Code
		echo '<col width="100"/>';  // Qualification
		echo '<col width="80"/>';   // Progress
		echo '</colgroup>';
		echo '<col width="100"/>';  // Action
		echo '<caption>Sunesis Assessor no link with Learners in Smart Assessor (Push)</caption>';
		echo '<tr><th colspan="6">Sunesis</th><th colspan="4">Smart Assessor</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th rowspan="1" colspan="2">Training Record</th><th colspan="4">Qualification</th><th colspan="4">Qualification</th></tr>';
		echo '<tr><th>Learner</th><th>Framework</th><th>TR ID</th><th>QAN Code</th><th>Qualification</th><th>Assessor</th>'
			. '<th>Learner</th><th>QAN Code</th><th>Qualification</th><th>Assessor</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
		foreach ($rs as $row) {

        $assessorinsun = $row['assessor'];
            // Get more details on the matching records in SmartAssessor
		$sql = <<<HEREDOC
SELECT
    users.username AS assessor_username,
    users.surname AS assessor_surname,
    users.firstnames AS assessor_firstname,
    tmp_sa_assessors.SmartAssessorId AS AssessorSmartAssessorId
FROM
	users
    INNER JOIN tmp_sa_assessors ON ( tmp_sa_assessors.SmartAssessorId = users.smart_assessor_id )
WHERE
	users.id = '$assessorinsun'
HEREDOC;
			$options  = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);


            $rowValid = true;
			foreach ($requiredFields as $field) {
				$rowValid = $rowValid && !empty($row[$field]);
				if (!$rowValid) {
					break;
				}
			}

			if (!$rowValid || count($options) < 1) {
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
            if(count($options) > 0){
			echo '<td align="left">' . htmlspecialchars((string)$options[0]['assessor_surname']).' '.htmlspecialchars((string)$options[0]['assessor_firstname']) . '</td>';
            } else {
            echo '<td align="left">&nbsp;</td>';
            }

            // Smart assessor fields
            echo '<td align="left">' . htmlspecialchars((string)$row['learnerlastname']).' '.htmlspecialchars((string)$row['learnerfirstnames']) .  '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['QANCode']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['QualificationTitle']) . '</td>';

            echo '<td align="left">&nbsp;</td>';


            // Action checkbox
			if ($rowValid && count($options) == 1) {
				echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$options[0]['AssessorSmartAssessorId']).":".htmlspecialchars((string)$row['SmartAssessorId']).":".htmlspecialchars((string)$row['LearnerSmartAssessorId'])); // Valid record for creation in Smart Assessor
			} else {
				echo '<td>&nbsp;</td>'; // Invalid (missing fields)
			}

			echo '</tr>';
		}
		echo '<tr><td colspan="10">&nbsp;</td><td align="center"><input type="button" id="BtnCreate" value="Create"/></td></tr>';
		echo '</table>';
	}


	private function _renderNoMatchSa(PDO $link)
	{
        $requiredFields = array('SmartAssessorId');

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

    u2.username AS assessorusername,

    tmp_sa_assessors.LastName,
    tmp_sa_assessors.FirstName,

    tmp_sa_learnercourse.*
FROM
  tmp_sa_learnercourse
	INNER JOIN student_qualifications ON ( replace(student_qualifications.id,'/','') = tmp_sa_learnercourse.QANCode )
		INNER JOIN tr ON (tr.id = student_qualifications.tr_id)
		INNER JOIN users ON (users.username = tr.username AND users.smart_assessor_id = tmp_sa_learnercourse.LearnerSmartAssessorId)
        INNER JOIN users u2 ON (u2.smart_assessor_id = tmp_sa_learnercourse.AssessorSmartAssessorId)
		INNER JOIN tmp_sa_assessors ON (u2.id = tmp_sa_assessors.SunesisId)
		INNER JOIN student_frameworks ON (student_frameworks.tr_id = tr.id)
		INNER JOIN tmp_sa_courses ON ( tmp_sa_courses.QANCode = replace(student_qualifications.id,'/',''))
WHERE
	tr.assessor IS NULL
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
		echo '<caption>Smart Assessor learner link with assessor with no record in Sunesis (Pull)</caption>';
		echo '<tr><th colspan="6">Sunesis</th><th colspan="4">Smart Assessor</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th rowspan="1" colspan="2">Training Record</th><th colspan="4">Qualification</th><th colspan="4">Qualification</th></tr>';
		echo '<tr><th>Learner</th><th>Framework</th><th>TR ID</th><th>QAN Code</th><th>Qualification</th><th>Assessor</th>'
			. '<th>AssessorSAId</th><th>QAN Code</th><th>Qualification</th><th>Assessor</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
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
			echo '<td align="left">&nbsp;</td>';

			// Smart assessor fields
			echo '<td align="left" class="SmartAssessorId">' . htmlspecialchars((string)$row['AssessorSmartAssessorId']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['QANCode']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['QualificationTitle']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['LastName']).' '.htmlspecialchars((string)$row['FirstName']) . '</td>';

            // Action checkbox
			if ($rowValid) {
                echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['assessorusername']).":".htmlspecialchars((string)$row['tr_id'])); // Valid record for creation in Smart Assessor
            } else {
				echo '<td>&nbsp;</td>'; // Invalid (missing fields)
			}

			echo '</tr>';
		}
        echo '<tr><td colspan="10">&nbsp;</td><td align="center"><input type="button" id="BtnCreateInSunesis" value="Update in Sunesis"/></td></tr>';
		echo '</table>';
	}


	private function _renderExactMatch(PDO $link)
	{
        $sql = <<<SQL
SELECT
    student_qualifications.internaltitle AS internaltitle,
    student_qualifications.id AS QAN,
    student_qualifications.unitsUnderAssessment AS unitsUnderAssessment,
    student_frameworks.title as framework,
    tr.id AS tr_id,

	users.id AS userid,
	users.username as learnerusername,
	users.surname as learnersurname,
	users.firstnames as learnerfirstname,

	u2.username,
	u2.surname,
	u2.firstnames,

	tmp_sa_learnercourse.*
FROM
    student_qualifications
        INNER JOIN tmp_sa_learnercourse ON ( tmp_sa_learnercourse.QANCode = replace(student_qualifications.id,'/',''))
        INNER JOIN tr ON student_qualifications.tr_id = tr.id
        INNER JOIN users ON (users.username = tr.username AND users.smart_assessor_id = tmp_sa_learnercourse.LearnerSmartAssessorId)
        INNER JOIN users u2 ON (u2.smart_assessor_id = tmp_sa_learnercourse.AssessorSmartAssessorId)
        INNER JOIN tmp_sa_assessors ON (tmp_sa_assessors.SmartAssessorId = u2.smart_assessor_id)
        INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
        INNER JOIN tmp_sa_courses ON ( tmp_sa_courses.QANCode = replace(student_qualifications.id,'/',''))
WHERE
	-- Sunesis-SA migrated Not Migrated Assessors only
	tmp_sa_learnercourse.QANCode IS NOT NULL

    -- Sunesis-SA migrated Not Migrated Assessors only
	AND tmp_sa_learnercourse.AssessorSmartAssessorId IS NOT NULL

    -- Sunesis-SA migrated Not Migrated Assessors only
    AND tr.assessor IS NOT NULL
ORDER BY
	tr.id
SQL;

		/*$sql = <<<SQL
SELECT
    student_qualifications.internaltitle AS internaltitle,
    student_qualifications.id AS QAN,
    student_qualifications.unitsUnderAssessment AS unitsUnderAssessment,
    student_frameworks.title as framework,
    tr.id AS tr_id,

	users.id AS userid,
	users.username,
	users.surname,
	users.firstnames,

    (select CONCAT(surname,' ',firstnames) FROM users where users.smart_assessor_id = tmp_sa_learnercourse.LearnerSmartAssessorId) AS learner,

	tmp_sa_learnercourse.*
FROM
    student_qualifications
        INNER JOIN tmp_sa_learnercourse ON ( tmp_sa_learnercourse.QANCode = replace(student_qualifications.id,'/',''))
        INNER JOIN tr ON student_qualifications.tr_id = tr.id
        INNER JOIN users ON (users.username = tr.assessor AND users.smart_assessor_id = tmp_sa_learnercourse.AssessorSmartAssessorId)
        INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
WHERE
	-- Sunesis-SA migrated Not Migrated Assessors only
	tmp_sa_learnercourse.QANCode IS NOT NULL

    -- Sunesis-SA migrated Not Migrated Assessors only
	AND tmp_sa_learnercourse.AssessorSmartAssessorId IS NOT NULL
ORDER BY
	tr.id
SQL;*/

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
		echo '<caption>Linked Assessor with Learner records</caption>';
		echo '<tr><th colspan="6">Sunesis</th><th colspan="4">Smart Assessor</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th rowspan="1" colspan="2">Training Record</th><th colspan="4">Qualification</th><th colspan="4">Qualification</th></tr>';
		echo '<tr><th>Learner</th><th>Framework</th><th>TR ID</th><th>QAN Code</th><th>Qualification</th><th>Assessor</th>'
			. '<th>Learner : Course ID</th><th>QAN Code</th><th>Qualification</th><th>Assessor</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
		foreach ($rs as $row) {
			echo '<tr class="Data">';

			// Training Record
			echo '<td align="left">' . htmlspecialchars((string)$row['learnersurname']).' '.htmlspecialchars((string)$row['learnerfirstname']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['framework']) . '</td>';

			// Qualification
            echo '<td align="left">' . htmlspecialchars((string)$row['tr_id']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['QAN']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['internaltitle']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['surname']).' '.htmlspecialchars((string)$row['firstnames'])  . '</td>';
			//echo '<td align="left">&nbsp;</td>';

			// Smart assessor fields
			echo '<td align="left" class="SmartAssessorId">' . htmlspecialchars((string)$row['LearnerSmartAssessorId'])." : ".htmlspecialchars((string)$row['SmartAssessorId']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['QANCode']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['QualificationTitle']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['surname']).' '.htmlspecialchars((string)$row['firstnames'])  . '</td>';
            //echo '<td align="left">&nbsp;</td>';

			echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['LearnerSmartAssessorId']).":".htmlspecialchars((string)$row['SmartAssessorId']));

			echo '</tr>';
		}
		echo '<tr><td colspan="10">&nbsp;</td><td align="center"><!--<input type="button" id="BtnUnlink" value="Unlink" style="color:red"/>--></td></tr>';

		echo '</table>';
	}



	private function _createTempTables(PDO $link)
	{
		DAO::execute($link, "DROP TEMPORARY TABLE IF EXISTS tmp_sa_learnercourse");
		$sql = <<<SQL
CREATE TEMPORARY TABLE tmp_sa_learnercourse (
	`SmartAssessorId` CHAR(50) NOT NULL,
	`SunesisId` BIGINT,
	`LearnerSmartAssessorId` CHAR(50),
	`AssessorSmartAssessorId` CHAR(50),
	`QANCode` VARCHAR(100),
    `QualificationTitle` VARCHAR(200),
    `Progress_AssessedPC` VARCHAR(100),
    `Progress_MappedPC` VARCHAR(100),
    `TimeLinePerc` VARCHAR(100),
	KEY (`SunesisId`)
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
		$rs = DAO::getResultset($link, "SELECT * FROM tmp_sa_learnercourse", DAO::FETCH_ASSOC);
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