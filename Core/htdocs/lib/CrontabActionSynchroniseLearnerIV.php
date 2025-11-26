<?php

class CrontabActionSynchroniseLearnerIV extends CrontabAction {


    public function __construct() {
        $this->task = 'SynchroniseLearnerIV';
    }

    public function execute(PDO $link) {
        if (!SystemConfig::get('smartassessor.soap.enabled')) {
            return;
        }

        // Populate tmp_sa_learnerbatches
        $sa = new SmartAssessor($this->read_only);
		$saRecords = $sa->getLearnerBatchDetails();
		$this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_sa_learnerbatches', $saRecords);

        $saRecords = $sa->getLearners();
		DAO::multipleRowInsert($link, 'tmp_sa_learners', $saRecords);

        $saRecords = $sa->getCourses();
		DAO::multipleRowInsert($link, 'tmp_sa_courses', $saRecords);

        $saRecords = $sa->getAssessors();
		DAO::multipleRowInsert($link, 'tmp_sa_assessors', $saRecords);

        // Create Learner batches in Sunesis
        $this->_createLearnerBatchesInSunesis($link, $sa);
    }

    private function _createLearnerBatchesInSunesis(PDO $link, SmartAssessor $sa)
	{

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
    LEFT OUTER JOIN iv
		ON (iv.tr_id = tr.id  AND iv.smart_assessor_id = tmp_sa_learnerbatches.BatchId)
WHERE
	iv.smart_assessor_id IS NULL
SQL;

		$st = DAO::query($link, $sql);
		while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
			$this->log('Creating Learner IV: ' . $row['iv_name_1']
			    . ' (SmartAssessorId #' . $row['smart_assessor_id'] . ')', Zend_Log::INFO);
			$sa->createBatchesInSunesis($row);
		}
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