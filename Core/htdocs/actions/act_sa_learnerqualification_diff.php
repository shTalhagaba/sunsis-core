<?php

class sa_learnerqualification_diff extends ActionController {

    public function indexAction(PDO $link) {
        if (!$_SESSION['user']->isAdmin()) {
            throw new UnauthorizedException();
        }

        include('smartassessor/tpl_sa_learnerqualification_diff.php');
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
        $this->_createTempTables($link);

        $saRecords = $sa->getGetLearnerCourseDetail();
        DAO::multipleRowInsert($link, 'tmp_sa_learnercourse', $saRecords);

        $saRecords = $sa->getLearners();
        DAO::multipleRowInsert($link, 'tmp_sa_learners', $saRecords);

        $saRecords = $sa->getCourses();
        DAO::multipleRowInsert($link, 'tmp_sa_courses', $saRecords);


        echo '<div style="width:800px; margin-left: auto; margin-right: auto;">';
        echo '<h3>Differences between linked records</h3>';
        echo '</div>';

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
		  INNER JOIN users ON (tr.username = users.username)
		  INNER JOIN tmp_sa_learners ON (users.id = tmp_sa_learners.SunesisId)
          INNER JOIN student_frameworks ON (student_frameworks.tr_id = tr.id)
		LEFT JOIN tmp_sa_learnercourse ON (replace(student_qualifications.id,'/','') = tmp_sa_learnercourse.QANCode AND users.smart_assessor_id = tmp_sa_learnercourse.	LearnerSmartAssessorId )
WHERE
	-- Sunesis-SA migrated progress only
	tmp_sa_learnercourse.QANCode IS NULL
ORDER BY
	users.surname, users.firstnames
SQL;

        echo '<div>';

        // Sort through
        $st = DAO::query($link, $sql);
          echo <<<HTML
<table class="resultset CompareRecords" cellspacing="0" cellpadding="4">
HTML;

    echo '<thead>';
    echo '<th>&nbsp;</th><th>Learner</th><th>Framework ID</th><th>ID</th><th>QAN Code</th><th>Qualification</th><th>Progress</th>';

    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {

            $QANcode = $row['QAN'];
            // Get more details on the matching records in SmartAssessor
             $sql = <<<HEREDOC
SELECT
	QANCode,
   	QualificationTitle
FROM
	tmp_sa_courses
WHERE
	QANCode = replace('$QANcode','/','')
HEREDOC;

            $options = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);


            if (count($options) > 0) {




                echo '<tr>';
                echo '<td align="left" style="font-weight:bold">Sunesis</td>';
                // Training Record
                echo '<td align="left">' . htmlspecialchars((string)$row['surname']) . ' ' . htmlspecialchars((string)$row['firstnames']) . '</td>';
                echo '<td align="left">' . htmlspecialchars((string)$row['framework']) . '</td>';

                // Qualification
                echo '<td align="left">' . htmlspecialchars((string)$row['tr_id']) . '</td>';
                echo '<td align="left">' . htmlspecialchars((string)$row['QAN']) . '</td>';
                echo '<td align="left">' . htmlspecialchars((string)$row['internaltitle']) . '</td>';
                echo '<td align="left">' . htmlspecialchars((string)$row['unitsUnderAssessment']) . '%</td>';

                
                echo '</tr>';


            }
        }
         echo '</tbody></table>';
        echo '</div>';
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