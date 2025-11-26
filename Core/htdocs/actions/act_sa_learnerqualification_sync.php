<?php

class sa_learnerqualification_sync extends ActionController {

    /**
     * Compare Learner Qualification in Sunesis with Learner Course in Smart Assessor
     * @param PDO $link
     * @override
     * @throws UnauthorizedException
     */
    public function indexAction(PDO $link) {
        if (!$_SESSION['user']->isAdmin()) {
            throw new UnauthorizedException();
        }

        $filterSections = $this->_getParam("filter_sections", array('exactmatch', 'nomatchsun'));
        $filterSectionsOptions = array(
            array('exactmatch', 'Linked records'),
            //array('partialmatch', 'Potential links'),
            array('nomatchsun', 'No links (Sunesis)'),
            //array('nomatchsa', 'No links (Smart Assessor)')
        );

        include('smartassessor/tpl_sa_learnerqualification_sync.php');
    }

    /**
     * @param PDO $link
     * @return mixed
     * @throws UnauthorizedException
     */
    public function renderContentAction(PDO $link) {
        if (!$_SESSION['user']->isAdmin()) {
            throw new UnauthorizedException();
        }
        if (!SystemConfig::get("smartassessor.soap.enabled")) {
            echo '<p style="font-weight: bold">SmartAssessor integration is not enabled for this Sunesis site</b></p>';
            return;
        }

        $sa = new SmartAssessor();
        $this->_createTempTables($link);

        $saRecords = $sa->getGetLearnerCourseDetail();
        DAO::multipleRowInsert($link, 'tmp_sa_learnercourse', $saRecords);
	    if(DB_NAME=="am_ligauk")
	    {
		    DAO::execute($link, "drop table if exists sa_learnercourse");
		    DAO::execute($link, "create table sa_learnercourse select * from tmp_sa_learnercourse");
	    }
        $saRecords = $sa->getLearners();
        DAO::multipleRowInsert($link, 'tmp_sa_learners', $saRecords);
		if(DB_NAME=="am_ligauk")
	    {
		    DAO::execute($link, "drop table if exists sa_learners");
		    DAO::execute($link, "create table sa_learners select * from tmp_sa_learners");
	    }
        $saRecords = $sa->getCourses();
        DAO::multipleRowInsert($link, 'tmp_sa_courses', $saRecords);
		if(DB_NAME=="am_ligauk")
	    {
		    DAO::execute($link, "drop table if exists sa_courses");
		    DAO::execute($link, "create table sa_courses select * from tmp_sa_courses");
	    }
        $filterSections = $this->_getParam("filter_sections", array());

        if (in_array('exactmatch', $filterSections)) {
            $this->_renderExactMatch($link);
        }
        /* if (in_array('partialmatch', $filterSections)) {
          $this->_renderPartialMatch($link);
          } */
        if (in_array('nomatchsun', $filterSections)) {
            $this->_renderNoMatchSun($link);
        }
        /* if (in_array('nomatchsa', $filterSections)) {
          $this->_renderNoMatchSa($link);
          } */

        //$this->_renderTemporaryTable($link);
    }

    /**
     * @param PDO $link
     * @throws UnauthorizedException
     * @throws Exception
     */
    public function createRecordsAction(PDO $link) {
        if (!$_SESSION['user']->isAdmin()) {
            throw new UnauthorizedException();
        }
        if (!SystemConfig::get("smartassessor.soap.enabled")) {
            throw new Exception("SmartAssessor integration is not enabled for this Sunesis site.");
        }

        $sa = new SmartAssessor();
        $this->_createTempTables($link);

        $saRecords = $sa->getGetLearnerCourseDetail();
        DAO::multipleRowInsert($link, 'tmp_sa_learnercourse', $saRecords);

        $saRecords = $sa->getLearners();
        DAO::multipleRowInsert($link, 'tmp_sa_learners', $saRecords);

        $saRecords = $sa->getCourses();
        DAO::multipleRowInsert($link, 'tmp_sa_courses', $saRecords);


        // Allow longer execution time
        set_time_limit(180); // 3 minutes

        $ids = (array) $this->_getParam("ids");
        if (count($ids) == 0) {
            throw new Exception("No records selected to create");
        }
        /* foreach($ids as $id) {
          if (!is_numeric($id)) {
          throw new Exception("Illegal non-numeric value for id: " . $id);
          }
          } */



        foreach ($ids as $id) {
            $pair = explode(':', $id);
            $p = $pair[1];

           $sql = <<<SQL
SELECT
    users.smart_assessor_id AS `LearnerSmartAssessorId`,
    replace(student_qualifications.id,'/','') AS `QANCode`,
    student_qualifications.internaltitle AS `QualificationTitle`,
    student_qualifications.start_date AS `CourseStartDate`,
    student_qualifications.actual_end_date AS `CourseEndDate`,
    'AI' AS `StatusofCourse`,
    tmp_sa_courses.SmartAssessorId AS SmartAssessorId
FROM
	student_qualifications
		  INNER JOIN tr ON (tr.id = student_qualifications.tr_id)
		  INNER JOIN contracts on contracts.id = tr.contract_id and contracts.`sync_learners_smart_assessor` = 1
		  INNER JOIN users ON (tr.username = users.username)
		  INNER JOIN tmp_sa_learners ON (users.id = tmp_sa_learners.SunesisId)
          INNER JOIN student_frameworks ON (student_frameworks.tr_id = tr.id)
          INNER JOIN tmp_sa_courses ON replace(student_qualifications.id,'/','') = tmp_sa_courses.QANCode
		LEFT JOIN tmp_sa_learnercourse ON (replace(student_qualifications.id,'/','') = tmp_sa_learnercourse.QANCode AND users.smart_assessor_id = tmp_sa_learnercourse.	LearnerSmartAssessorId )
WHERE
	-- Sunesis-SA migrated progress only
	tmp_sa_learnercourse.QANCode IS NULL
    -- Sunesis learner
    AND users.id = {$pair[0]}
    -- Sunesis Training Record and Qualification
    AND replace(student_qualifications.id,'/','') = '$p' AND student_qualifications.tr_id = {$pair[2]}
SQL;
            $row = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);


            try {
                $sa = new SmartAssessor(false);
                $sa->createLearnerQualification($row[0]);
            } catch (Exception $e) {
                throw new Exception("An error occurred while creating learner qualification records. Operation aborted.", 1, $e);
            }
        }
    }

    public function linkRecordsAction(PDO $link) {
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
            if (!preg_match('/^\d+:\w+/', $id)) {
                throw new Exception("Illegal value for id pair: " . $id);
            }
        }

        try {
            //DAO::transaction_start($link);
            $sa = new SmartAssessor();
            foreach ($ids as $id) {
                $pair = explode(':', $id);
                DAO::execute($link, "UPDATE student_qualifications SET smart_assessor_id=NULL WHERE student_qualifications.tr_id={$pair[0]} AND replace(student_qualifications.id,'/','') = {$pair[2]}");
                $sa->updateProgress(array('SunesisId' => $pair[0], 'SmartAssessorId' => $pair[1]));
            }
            //DAO::transaction_commit($link);
        } catch (Exception $e) {
            //DAO::transaction_rollback($link);
            throw new Exception("An error occurred while linking progress records. Operation aborted.", 1, $e);
        }
    }

    public function unlinkRecordsAction(PDO $link) {
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
        /*foreach ($ids as $id) {
            if (!preg_match('/^\d+:\w+/', $id)) {
                throw new Exception("Illegal value for id pair: " . $id);
            }
        } */

        try {
            //DAO::transaction_start($link);
            $sa = new SmartAssessor();
            foreach ($ids as $id) {
                $pair = explode(':', $id);
                //DAO::execute($link, "UPDATE student_qualifications SET smart_assessor_id=NULL WHERE student_qualifications.tr_id={$pair[0]} AND replace(student_qualifications.id,'/','') = {$pair[2]}");
                $sa->deleteLearnerQualification(array('LearnerSmartAssessorId' => $pair[0], 'SmartAssessorId' => $pair[1]));
            }
            //DAO::transaction_commit($link);
        } catch (Exception $e) {
            //DAO::transaction_rollback($link);
            throw new Exception("An error occurred while deleting learner qualification records. Operation aborted.", 1, $e);
        }
    }

    private function _renderNoMatchSun(PDO $link) {
        $requiredFields = array('firstnames', 'surname');

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

    tmp_sa_learners.FamilyName,
    tmp_sa_learners.GivenNames,
    tmp_sa_learnercourse.*
FROM
	student_qualifications
		  INNER JOIN tr ON (tr.id = student_qualifications.tr_id)
		  INNER JOIN contracts on (contracts.id = tr.contract_id and contracts.`sync_learners_smart_assessor` = 1)
		  INNER JOIN users ON (users.username = tr.username)
		  INNER JOIN tmp_sa_learners ON (users.id = tmp_sa_learners.SunesisId)
          INNER JOIN student_frameworks ON (student_frameworks.tr_id = tr.id)
		LEFT JOIN tmp_sa_learnercourse ON (replace(student_qualifications.id,'/','') = tmp_sa_learnercourse.QANCode AND users.smart_assessor_id = tmp_sa_learnercourse.	LearnerSmartAssessorId )
WHERE
	-- Sunesis-SA migrated progress only
	tmp_sa_learnercourse.QANCode IS NULL
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
        echo '<caption>Sunesis Qualifications no link with Learners in Smart Assessor (Push)</caption>';
        echo '<tr><th colspan="6">Sunesis</th><th colspan="4">Smart Assessor</th><th width="100" rowspan=2">Action</th></tr>';
        echo '<tr><th rowspan="1" colspan="2">Training Record</th><th colspan="4">Qualification</th><th colspan="4">Qualification</th></tr>';
        echo '<tr><th>Learner</th><th>Framework</th><th>ID</th><th>QAN Code</th><th>Qualification</th><th>Progress</th>'
        . '<th>Learner</th><th>QAN Code</th><th>Qualification</th><th>Progress</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
        foreach ($rs as $row) {

            $QANcode = $row['QAN'];
            // Get more details on the matching records in SmartAssessor
            $sql = <<<HEREDOC
SELECT
    SmartAssessorId,
	QANCode,
   	QualificationTitle
FROM
	tmp_sa_courses
WHERE
	QANCode = replace('$QANcode','/','')
HEREDOC;
            $options = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

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
            echo '<td align="left">' . htmlspecialchars((string)$row['surname']) . ' ' . htmlspecialchars((string)$row['firstnames']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['framework']) . '</td>';

            // Qualification
            echo '<td align="left">' . htmlspecialchars((string)$row['tr_id']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['QAN']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['internaltitle']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['unitsUnderAssessment']) . '%</td>';

            // Smart assessor fields

            echo '<td align="left">' . htmlspecialchars((string)$row['FamilyName']) . ' ' . htmlspecialchars((string)$row['GivenNames']) . '</td>';
            if (count($options) == 1) {
                echo '<td align="left">' . htmlspecialchars((string)$options[0]['QANCode']) . '</td>';
                echo '<td align="left">' . htmlspecialchars((string)$options[0]['QualificationTitle']) . '</td>';
            } else {
                echo '<td align="left">&nbsp;</td>';
                echo '<td align="left">&nbsp;</td>';
            }
            echo '<td align="left">' . htmlspecialchars((string)$row['Progress_AssessedPC']) . '</td>';


            // Action checkbox
            if ($rowValid && count($options) == 1) {
                echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['user_id']) . ":" . htmlspecialchars((string)$options[0]['QANCode']) . ":" . htmlspecialchars((string)$row['tr_id'])); // Valid record for creation in Smart Assessor
            } else {
                echo '<td>&nbsp;</td>'; // Invalid (missing fields)
            }

            echo '</tr>';
        }
        echo '<tr><td colspan="10">&nbsp;</td><td align="center"><input type="button" id="BtnCreate" value="Create"/></td></tr>';
        echo '</table>';
    }

    private function _renderNoMatchSa(PDO $link) {
        $requiredFields = array('SmartAssessorId');

        $sql = <<<SQL
SELECT
    student_qualifications.internaltitle AS internaltitle,
    student_qualifications.id AS QAN,
    student_qualifications.unitsUnderAssessment AS unitsUnderAssessment,
    student_frameworks.title as framework,
	users.id AS userid,
	users.username,
	users.surname,
	users.firstnames,
    tr.id AS tr_id,
	tmp_sa_learnercourse.*
FROM
	student_qualifications
        INNER JOIN tmp_sa_learnercourse ON tmp_sa_learnercourse.QANCode = replace(student_qualifications.id,'/','')
        INNER JOIN tr ON student_qualifications.tr_id = tr.id
        INNER JOIN contracts on contracts.id = tr.contract_id and contracts.`sync_learners_smart_assessor` = 1
        INNER JOIN users ON (users.username = tr.username AND users.smart_assessor_id = tmp_sa_learnercourse.LearnerSmartAssessorId)
        INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
WHERE
	-- Sunesis-SA migrated progress only
	tmp_sa_learnercourse.SunesisId IS NULL
    -- Sunesis users with no existing linked record in Smart Assessor
	AND student_qualifications.smart_assessor_id IS NULL
ORDER BY
	users.surname, users.firstnames
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
        echo '<caption>Smart Assessor qualification progress with no record in Sunesis (Pull)</caption>';
        echo '<tr><th colspan="6">Sunesis</th><th colspan="4">Smart Assessor</th><th width="100" rowspan=2">Action</th></tr>';
        echo '<tr><th rowspan="1" colspan="2">Training Record</th><th colspan="4">Qualification</th><th colspan="4">Qualification</th></tr>';
        echo '<tr><th>Learner</th><th>Framework</th><th>ID</th><th>QAN Code</th><th>Qualification</th><th>Progress</th>'
        . '<th>ID</th><th>QAN Code</th><th>Qualification</th><th>Progress</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
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
            echo '<td align="left">' . htmlspecialchars((string)$row['surname']) . ' ' . htmlspecialchars((string)$row['firstnames']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['framework']) . '</td>';

            // Qualification
            echo '<td align="left">' . htmlspecialchars((string)$row['tr_id']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['QAN']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['internaltitle']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['unitsUnderAssessment']) . '%</td>';
            //echo '<td align="left">&nbsp;</td>';
            // Smart assessor fields
            echo '<td align="left" class="SmartAssessorId">' . htmlspecialchars((string)$row['SmartAssessorId']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['QANCode']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['QualificationTitle']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['Progress_AssessedPC']) . '%</td>';
            //echo '<td align="left">&nbsp;</td>';
            // Action checkbox
            if ($rowValid) {
                echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['SmartAssessorId'])); // Valid record for creation in Smart Assessor
            } else {
                echo '<td>&nbsp;</td>'; // Invalid (missing fields)
            }

            echo '</tr>';
        }
        echo '<tr><td colspan="10">&nbsp;</td><td align="center"><input type="button" id="BtnCreateInSunesis" value="Update in Sunesis"/></td></tr>';
        echo '</table>';
    }

    private function _renderExactMatch(PDO $link) {
        $sql = <<<SQL
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

	tmp_sa_learnercourse.*
FROM
	student_qualifications
        INNER JOIN tmp_sa_learnercourse ON ( tmp_sa_learnercourse.QANCode = replace(student_qualifications.id,'/',''))
        INNER JOIN tr ON student_qualifications.tr_id = tr.id
		INNER JOIN contracts on contracts.id = tr.contract_id and contracts.`sync_learners_smart_assessor` = 1
        INNER JOIN users ON (users.username = tr.username AND users.smart_assessor_id = tmp_sa_learnercourse.LearnerSmartAssessorId)
        INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
WHERE
	-- Sunesis-SA migrated Progress only
	tmp_sa_learnercourse.QANCode IS NOT NULL
ORDER BY
	users.surname, users.firstnames
SQL;

        $rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

        echo '<table class="resultset" cellspacing="0" cellpadding="4">';
        echo '<col width="100"/>';  // Name
        echo '<col width="80"/>';   // Framework
        echo '<col width="70"/>';   // ID
        echo '<col width="80"/>';  // QAN Code
        echo '<col width="100"/>';  // Qualification
        echo '<col width="80"/>';   // Progress
        echo '<col width="100"/>';  // ID
        echo '<col width="80"/>';  // QAN Code
        echo '<col width="100"/>';  // Qualification
        echo '<col width="80"/>';   // Progress
        echo '<col width="100"/>';  // Action
        echo '<caption>Linked Qualifications with Learner records</caption>';
        echo '<tr><th colspan="6">Sunesis</th><th colspan="4">Smart Assessor</th><th width="100" rowspan=2">Action</th></tr>';
        echo '<tr><th rowspan="1" colspan="2">Training Record</th><th colspan="4">Qualification</th><th colspan="4">Qualification</th></tr>';
        echo '<tr><th>Learner</th><th>Framework</th><th>ID</th><th>QAN Code</th><th>Qualification</th><th>Progress</th>'
        . '<th>Learner : Course ID</th><th>QAN Code</th><th>Qualification</th><th>Progress</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
        foreach ($rs as $row) {
            echo '<tr class="Data">';

            // Training Record
            echo '<td align="left">' . htmlspecialchars((string)$row['surname']) . ' ' . htmlspecialchars((string)$row['firstnames']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['framework']) . '</td>';

            // Qualification
            echo '<td align="left">' . htmlspecialchars((string)$row['tr_id']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['QAN']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['internaltitle']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['unitsUnderAssessment']) . '%</td>';
            //echo '<td align="left">&nbsp;</td>';
            // Smart assessor fields
            echo '<td align="left" class="SmartAssessorId">' . htmlspecialchars((string)$row['LearnerSmartAssessorId']) . " : " . htmlspecialchars((string)$row['SmartAssessorId']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['QANCode']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['QualificationTitle']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['Progress_AssessedPC']) . '%</td>';
            //echo '<td align="left">&nbsp;</td>';

            echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['LearnerSmartAssessorId']) . ":" . htmlspecialchars((string)$row['SmartAssessorId']));

            echo '</tr>';
        }
        echo '<tr><td colspan="10">&nbsp;</td><td align="center"><input type="button" id="BtnUnlink" value="Delete" style="color:red"/></td></tr>';

        echo '</table>';
    }

    private function _createTempTables(PDO $link) {
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



        DAO::execute($link, "DROP TEMPORARY TABLE IF EXISTS tmp_sa_courses");
        $sql = <<<SQL
CREATE TEMPORARY TABLE tmp_sa_courses (
	`SmartAssessorId` CHAR(36) NOT NULL,
	`QANCode` VARCHAR(100),
    `QualificationTitle` VARCHAR(200),
	PRIMARY KEY (`SmartAssessorId`)
);
SQL;
        DAO::execute($link, $sql);
    }

    private function _renderTemporaryTable(PDO $link) {
        $rs = DAO::getResultset($link, "SELECT * FROM tmp_sa_learnercourse", DAO::FETCH_ASSOC);
        HTML::renderResultset($rs);
    }

    /**
     * Returns an array of user IDs in Sunesis that are duplicates.
     * @param PDO $link
     * @return array
     */
    private function _getDuplicatedProgressTrack(PDO $link) {
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