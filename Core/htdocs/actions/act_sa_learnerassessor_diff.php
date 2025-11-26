<?php
class sa_learnerassessor_diff extends ActionController {

    public function indexAction(PDO $link) {
        if (!$_SESSION['user']->isAdmin()) {
            throw new UnauthorizedException();
        }

        include('smartassessor/tpl_sa_learnerassessor_diff.php');
    }

    public function renderContentAction(PDO $link) {
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


        $this->_renderMissingRecordsinSA($link);
        $this->_renderMissingRecordsinsunesis($link);

    }

    private function _renderMissingRecordsinSA(PDO $link)
	{
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
		$rs = DAO::query($link, $sql);


		echo '<div style="width:800px; margin-left: auto; margin-right: auto;">';
		echo '<h3>Broken Links: Records in Sunesis linked to no record in Smart Assessor</h3>';
		echo '<table class="resultset" cellspacing="0" cellpadding="4" width="100%">';
	   echo '<tr><th>Learner</th><th>Framework</th><th>TR ID</th><th>QAN Code</th><th>Qualification</th><th>Assessor</th>'
			. '<th>Learner</th><th>QAN Code</th><th>Qualification</th><th>Assessor</th></tr>';
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
	users.username = '$assessorinsun'
HEREDOC;
			$options  = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);


            $rowValid = true;
			foreach ($requiredFields as $field) {
				$rowValid = $rowValid && !empty($row[$field]);
				if (!$rowValid) {
					break;
				}
			}

			if ($rowValid && count($options) > 0) {

				echo '<tr class="Data">';

			// Training Record
			echo '<td align="left">' . htmlspecialchars((string)$row['learnerlastname']).' '.htmlspecialchars((string)$row['learnerfirstnames']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['framework']) . '</td>';

			// Qualification
            echo '<td align="left">' . htmlspecialchars((string)$row['tr_id']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['QAN']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['internaltitle']) . '</td>';
		    echo '<td align="left">' . htmlspecialchars((string)$options[0]['assessor_surname']).' '.htmlspecialchars((string)$options[0]['assessor_firstname']) . '</td>';

            // Smart assessor fields
            echo '<td align="left">' . htmlspecialchars((string)$row['learnerlastname']).' '.htmlspecialchars((string)$row['learnerfirstnames']) .  '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['QANCode']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['QualificationTitle']) . '</td>';

            echo '<td align="left">&nbsp;</td>';

			echo '</tr>';
            }
		}
		echo '</table>';
		echo '</div>';
	}

    private function _renderMissingRecordsinSunesis(PDO $link)
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

$rs = DAO::query($link, $sql);


		echo '<div style="width:800px; margin-left: auto; margin-right: auto;">';
		echo '<h3>Broken Links: Records in SmartAssessor linked to no record in Sunesis</h3>';
		echo '<table class="resultset" cellspacing="0" cellpadding="4" width="100%">';
	   echo '<tr><th>Learner</th><th>Framework</th><th>TR ID</th><th>QAN Code</th><th>Qualification</th><th>Assessor</th>'
			. '<th>AssessorSAId</th><th>QAN Code</th><th>Qualification</th><th>Assessor</th></tr>';
		foreach ($rs as $row) {

            $rowValid = true;
			foreach ($requiredFields as $field) {
				$rowValid = $rowValid && !empty($row[$field]);
				if (!$rowValid) {
					break;
				}
			}

			if ($rowValid) {

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

			echo '</tr>';
            }
		}
		echo '</table>';
		echo '</div>';

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
	`TelNumber` VARCHAR(30),
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




}