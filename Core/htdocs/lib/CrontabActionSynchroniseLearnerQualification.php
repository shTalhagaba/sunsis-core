<?php
class CrontabActionSynchroniseLearnerQualification extends CrontabAction
{
	public $Course;

	public function __construct()
	{
		$this->task = 'SynchroniseLearnerQualification';
	}

	public function execute(PDO $link)
	{
		if (!SystemConfig::get('smartassessor.soap.enabled')) {
			return;
		}

		// Populate
		$sa = new SmartAssessor($this->read_only);
        $this->_createTempTables($link);

        $saRecords = $sa->getGetLearnerCourseDetail();
        DAO::multipleRowInsert($link, 'tmp_sa_learnercourse', $saRecords);

        DAO::execute($link, "drop table sa_qualification");
        DAO::execute($link, "create table sa_qualification select * from tmp_sa_learnercourse");

        $saRecords = $sa->getLearners();
        DAO::multipleRowInsert($link, 'tmp_sa_learners', $saRecords);

        $saRecords = $sa->getCourses();
        DAO::multipleRowInsert($link, 'tmp_sa_courses', $saRecords);

		// Link Course with Learner in Smart Assessor
		$this->_createSaCourse($link, $sa);
	}


	private function _createSaCourse(PDO $link, SmartAssessor $sa)
	{

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
          INNER JOIN contracts on contracts.id = tr.id and  contracts.`sync_learners_smart_assessor` = 1
		  INNER JOIN users ON (tr.username = users.username)
		  INNER JOIN tmp_sa_learners ON (users.id = tmp_sa_learners.SunesisId)
          INNER JOIN student_frameworks ON (student_frameworks.tr_id = tr.id)
          INNER JOIN tmp_sa_courses ON replace(student_qualifications.id,'/','') = tmp_sa_courses.QANCode
		LEFT JOIN tmp_sa_learnercourse ON (replace(student_qualifications.id,'/','') = tmp_sa_learnercourse.QANCode AND users.smart_assessor_id = tmp_sa_learnercourse.LearnerSmartAssessorId )
WHERE
	-- Sunesis-SA migrated progress only
	tmp_sa_learnercourse.QANCode IS NULL and users.smart_assessor_id is not null;
SQL;

		// Sort through
		$st = DAO::query($link, $sql);
		$saData = array();
		$sunData = array();
		while ($row = $st->fetch(PDO::FETCH_ASSOC)) {

                    $QANcode = $row['QANCode'];
            // Get more details on the matching records in SmartAssessor
			$sql = <<<HEREDOC
SELECT
    SmartAssessorId,
	QANCode,
   	QualificationTitle
FROM
	tmp_sa_courses
WHERE
	QANCode = '$QANcode'
HEREDOC;
		     $options  = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

              if (count($options) > 1) {
                   $pass_array=array();

                   $pass_array['SmartAssessorId']=$row['SmartAssessorId'];
                   $pass_array['LearnerSmartAssessorId']=$row['LearnerSmartAssessorId'];
                   $pass_array['QANCode']=$row['QANCode'];
                   $pass_array['QualificationTitle']=$row['QualificationTitle'];
                   $pass_array['StatusofCourse']='AI';
                   $pass_array['CourseStartDate']=$row['CourseStartDate'];
                   $pass_array['CourseEndDate']=$row['CourseEndDate'];

                   $this->log('Link Learner Courses in Smart Assessor: ' . $pass_array['QANCode'].', Learner:'.$pass_array['LearnerSmartAssessorId']
			        . ' (SmartAssessorId #' . $pass_array['SmartAssessorId'] . ')', Zend_Log::INFO);

                   $sa->createLearnerQualification($pass_array);
              }
		}

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