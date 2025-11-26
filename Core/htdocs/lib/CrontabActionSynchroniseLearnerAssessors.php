<?php

class CrontabActionSynchroniseLearnerAssessors extends CrontabAction {

    public $Assesor;

    public function __construct() {
        $this->task = 'SynchroniseLearnerAssessors';
    }

    public function execute(PDO $link) {
        if (!SystemConfig::get('smartassessor.soap.enabled')) {
            return;
        }

        // Populate tmp_sa_reviews
        $sa = new SmartAssessor($this->read_only);
		$saRecords = $sa->getGetLearnerCourseDetail();
		$this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_sa_learnercourse', $saRecords);

        $saRecords = $sa->getLearners();
		DAO::multipleRowInsert($link, 'tmp_sa_learners', $saRecords);

        $saRecords = $sa->getCourses();
		DAO::multipleRowInsert($link, 'tmp_sa_courses', $saRecords);

        $saRecords = $sa->getAssessors();
		DAO::multipleRowInsert($link, 'tmp_sa_assessors', $saRecords);

        // Update Learner Assessor in Smart Assessor
        $this->_updateSaLearnerAssessors($link, $sa);

        // Update Learner Assessor in Sunesis
        $this->_updateSunLearnerAssessors($link, $sa);
    }

    private function _updateSunLearnerAssessors(PDO $link, SmartAssessor $sa) {

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

    u2.username AS assessorusername,

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

        // Sort through
        $st = DAO::query($link, $sql);
        $saData = array();
        $sunData = array();
        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {

            $pass_array = array();
            $pass_array['id'] = $row['tr_id'];
            $pass_array['assessor'] = $row['assessorusername'];

            $this->log('Updating Learner Assessor in Sunesis: assessor-' . $pass_array['assessor']
			    . ' (TR Id #' .  $pass_array['id'] . ')', Zend_Log::INFO);

            $sa->updateLearnerAssessorInSunesis($pass_array);
        }
    }

    private function _updateSaLearnerAssessors(PDO $link, SmartAssessor $sa) {


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

        // Sort through
        $st = DAO::query($link, $sql);
        $saData = array();
        $sunData = array();
        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {

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
            $options = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

            if (count($options) > 1) {
                $pass_array = array();

                 $pass_array['AssessorSmartAssessorId']=$options[0]['AssessorSmartAssessorId'];
                 $pass_array['SmartAssessorId']=$row['SmartAssessorId'];
                 $pass_array['LearnerSmartAssessorId']=$row['LearnerSmartAssessorId'];

                 $this->log('Updating Learner Assessor in Smart Assessor: LearnerSmartAssessorId-' . $pass_array['LearnerSmartAssessorId']
			    . ' (SmartAssessorId #' .  $pass_array['SmartAssessorId'] . ')', Zend_Log::INFO);

                $sa->updateLearnerAssessor($pass_array);
            }
        }
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
	`ULN` VARCHAR(10),
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