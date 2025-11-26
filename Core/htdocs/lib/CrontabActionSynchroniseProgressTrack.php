<?php

class CrontabActionSynchroniseProgressTrack extends CrontabAction {

    public $Progress_AssessedPC;

    public function __construct() {
        $this->task = 'SynchroniseProgressTrack';
    }

    public function execute(PDO $link) {
        if (!SystemConfig::get('smartassessor.soap.enabled')) {
            return;
        }

        // Populate tmp_sa_progresstrack
        $sa = new SmartAssessor($this->read_only);
        $saRecords = $sa->getProgresstrack();

        $this->_createTempTables($link);
        DAO::multipleRowInsert($link, 'tmp_sa_progresstrack', $saRecords);

        DAO::execute($link, "drop table IF EXISTS sa_progress");
        DAO::execute($link, "create table sa_progress select * from tmp_sa_progresstrack");


        // Update Progress in existing Learner Qualification in Sunesis from Smart Assessor
        $this->_updateProgress($link, $sa);
    }

    private function _updateProgress(PDO $link, SmartAssessor $sa) {

        $sql = <<<SQL
SELECT
    student_qualifications.internaltitle AS internaltitle,
    student_qualifications.id AS QAN,
    student_qualifications.username as username,
    student_qualifications.unitsUnderAssessment AS unitsUnderAssessment,
    student_qualifications.trading_name as trading_name,
    student_qualifications.framework_id as framework_id,
    student_frameworks.title as framework,
	users.id AS userid,
	users.username,
	users.surname,
	users.firstnames,
        users.smart_assessor_id,
    tr.id AS tr_id,
	tmp_sa_progresstrack.*
FROM
	student_qualifications
        INNER JOIN tmp_sa_progresstrack ON tmp_sa_progresstrack.QANCode = replace(student_qualifications.id,'/','')
        INNER JOIN tr ON student_qualifications.tr_id = tr.id
        INNER JOIN users ON (users.username = tr.username AND users.smart_assessor_id = tmp_sa_progresstrack.LearnerSmartAssessorId)
        INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
WHERE
    -- Sunesis Progress is different than SA progress
   tmp_sa_progresstrack.Progress_AssessedPC!= student_qualifications.unitsUnderAssessment
ORDER BY
	users.surname, users.firstnames
SQL;


/*
SELECT
    student_qualifications.internaltitle AS internaltitle,
    student_qualifications.id AS QAN,
    student_qualifications.unitsUnderAssessment AS unitsUnderAssessment,
    student_frameworks.title as framework,
	users.id AS userid,
	users.username,
	users.surname,
	users.firstnames,
        users.smart_assessor_id,
    tr.id AS tr_id,
	tmp_sa_progresstrack.*
FROM
	student_qualifications
        INNER JOIN tmp_sa_progresstrack ON tmp_sa_progresstrack.QANCode = replace(student_qualifications.id,'/','')
        INNER JOIN tr ON student_qualifications.tr_id = tr.id
        INNER JOIN users ON (users.username = tr.username AND users.smart_assessor_id = tmp_sa_progresstrack.LearnerSmartAssessorId)
        INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
WHERE
	-- Sunesis-SA migrated progress only
	tmp_sa_progresstrack.SunesisId IS NULL
    -- Sunesis users with no existing linked record in Smart Assessor
	AND student_qualifications.smart_assessor_id IS NULL
    -- Sunesis Progress is different than SA progress
    AND tmp_sa_progresstrack.Progress_AssessedPC!= student_qualifications.unitsUnderAssessment
ORDER BY
	users.surname, users.firstnames

*/

        // Sort through
        $st = DAO::query($link, $sql);
        $saData = array();
        $sunData = array();
        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
            $pass_array = array();
            $pass_array['unitsUnderAssessment'] = $row['Progress_AssessedPC'];
            $pass_array['smart_assessor_id'] = $row['SmartAssessorId'];
            $pass_array['id'] = $row['QAN'];
            $pass_array['framework_id'] = $row['framework_id'];
            $pass_array['username'] = $row['username'];
            $pass_array['tr_id'] = $row['tr_id'];
            $pass_array['trading_name'] = $row['trading_name'];
            $pass_array['internaltitle'] = $row['internaltitle'];

            $this->log('Updating Qualification Progress in Sunesis: Progress-' . $pass_array['unitsUnderAssessment']
			    . ' (TR Id #' .  $pass_array['tr_id'] . ')', Zend_Log::INFO);

            $sa->updateProgressInSunesis($pass_array);
        }
    }

    private function _createTempTables(PDO $link) {
        DAO::execute($link, "DROP TEMPORARY TABLE IF EXISTS tmp_sa_progresstrack");
        $sql = <<<SQL
CREATE TEMPORARY TABLE tmp_sa_progresstrack (
	`SmartAssessorId` CHAR(50) NOT NULL,
	`SunesisId` BIGINT,
	`LearnerSmartAssessorId` CHAR(50),
	`AssessorSmartAssessorId` CHAR(50),
	`QANCode` VARCHAR(100),
    `QualificationTitle` VARCHAR(200),
    `Progress` VARCHAR(100),
    `Progress_AssessedPC` VARCHAR(100),
    `Progress_MappedPC` VARCHAR(100),
    `TimeLinePerc` VARCHAR(100),
	KEY (`SunesisId`)
);
SQL;
        DAO::execute($link, $sql);
    }

}