<?php

class sa_progresstracking_sync extends ActionController {

    /**
     * Compare Progress in Smart Assessor with Progress Track in Sunesis
     * @param PDO $link
     * @override
     * @throws UnauthorizedException
     */
    public function indexAction(PDO $link) {
        if (!$_SESSION['user']->isAdmin()) {
            throw new UnauthorizedException();
        }

        //$filterSections = $this->_getParam("filter_sections", array('partialmatch', 'nomatchsun'));
        $filterSections = $this->_getParam("filter_sections", array('exactmatch', 'nomatchsa'));
        $filterSectionsOptions = array(
            array('exactmatch', 'Linked records'),
            //array('partialmatch', 'Potential links'),
            //array('nomatchsun', 'No links (Sunesis)'),
            array('nomatchsa', 'No links (Smart Assessor)')
        );

        include('smartassessor/tpl_sa_progresstracking_sync.php');
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
        $saRecords = $sa->getProgresstrack();

        $this->_createTempTables($link);
        DAO::multipleRowInsert($link, 'tmp_sa_progresstrack', $saRecords);
        $filterSections = $this->_getParam("filter_sections", array());

        if (in_array('exactmatch', $filterSections)) {
            $this->_renderExactMatch($link);
        }
        /* if (in_array('partialmatch', $filterSections)) {
          $this->_renderPartialMatch($link);
          } */
        /* if (in_array('nomatchsun', $filterSections)) {
          $this->_renderNoMatchSun($link);
          } */
        if (in_array('nomatchsa', $filterSections)) {
            $this->_renderNoMatchSa($link);
        }

        //$this->_renderTemporaryTable($link);
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
                $sa->updateProgress(array('SunesisId' => '', 'SmartAssessorId' => $pair[1]));
            }
            //DAO::transaction_commit($link);
        } catch (Exception $e) {
            //DAO::transaction_rollback($link);
            throw new Exception("An error occurred while unlinking progress records. Operation aborted.", 1, $e);
        }
    }

    /**
     * @param PDO $link
     * @throws UnauthorizedException
     * @throws Exception
     */
    public function createRecordsInSunesisAction(PDO $link) {
        if (!$_SESSION['user']->isAdmin()) {
            throw new UnauthorizedException();
        }
        if (!SystemConfig::get("smartassessor.soap.enabled")) {
            throw new Exception("SmartAssessor integration is not enabled for this Sunesis site.");
        }

        // Create temporary table and Insert records from Smart Assessor
        $sa = new SmartAssessor();
        $saRecords = $sa->getProgresstrack();
        $this->_createTempTables($link);
        DAO::multipleRowInsert($link, 'tmp_sa_progresstrack', $saRecords);

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
        $SmartAssessor_ids = DAO::pdo_implode($ids);

        $sql = <<<SQL
SELECT
	student_qualifications.id AS id,
    student_qualifications.framework_id AS framework_id,
    student_qualifications.tr_id AS tr_id,
    student_qualifications.internaltitle AS internaltitle,

    tmp_sa_progresstrack.Progress_AssessedPC AS unitsUnderAssessment,
    tmp_sa_progresstrack.SmartAssessorId AS smart_assessor_id
FROM
	student_qualifications
        INNER JOIN tmp_sa_progresstrack ON tmp_sa_progresstrack.QANCode = replace(student_qualifications.id,'/','')
        INNER JOIN tr ON student_qualifications.tr_id = tr.id
        INNER JOIN users ON (users.username = tr.username AND users.smart_assessor_id = tmp_sa_progresstrack.LearnerSmartAssessorId)
        INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
WHERE
    tmp_sa_progresstrack.SmartAssessorId IN ($SmartAssessor_ids);
SQL;
        $rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

        try {
            $sa = new SmartAssessor(false);
            foreach ($rows as $row) {
                $sa->updateProgressInSunesis($row);
            }
        } catch (Exception $e) {
            throw new Exception("An error occurred while updating progress records. Operation aborted.", 1, $e);
        }
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
	tmp_sa_progresstrack.*
FROM
	student_qualifications
        INNER JOIN tmp_sa_progresstrack ON tmp_sa_progresstrack.QANCode = replace(student_qualifications.id,'/','')
        INNER JOIN tr ON student_qualifications.tr_id = tr.id
        INNER JOIN users ON (users.username = tr.username AND users.smart_assessor_id = tmp_sa_progresstrack.LearnerSmartAssessorId)
        INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
WHERE
    -- Sunesis-SA no matched Progress only
	tmp_sa_progresstrack.Progress_AssessedPC != student_qualifications.unitsUnderAssessment
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
ORDER BY
	users.surname, users.firstnames


*/

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

            // Smart assessor fields
            echo '<td align="left" class="SmartAssessorId">' . htmlspecialchars((string)$row['SmartAssessorId']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['QANCode']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['QualificationTitle']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['Progress_AssessedPC']) . '%</td>';

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
	tmp_sa_progresstrack.*
FROM
	student_qualifications
        INNER JOIN tmp_sa_progresstrack ON ( student_qualifications.smart_assessor_id = tmp_sa_progresstrack.SmartAssessorId AND tmp_sa_progresstrack.QANCode = replace(student_qualifications.id,'/',''))
        INNER JOIN tr ON student_qualifications.tr_id = tr.id
        INNER JOIN users ON (users.username = tr.username AND users.smart_assessor_id = tmp_sa_progresstrack.LearnerSmartAssessorId)
        INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
WHERE
	-- Sunesis-SA exact matched Progress only
	tmp_sa_progresstrack.Progress_AssessedPC = student_qualifications.unitsUnderAssessment
ORDER BY
	users.surname, users.firstnames
SQL;


/*

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
	tmp_sa_progresstrack.*
FROM
	student_qualifications
        INNER JOIN tmp_sa_progresstrack ON ( student_qualifications.smart_assessor_id = tmp_sa_progresstrack.SmartAssessorId AND tmp_sa_progresstrack.QANCode = replace(student_qualifications.id,'/',''))
        INNER JOIN tr ON student_qualifications.tr_id = tr.id
        INNER JOIN users ON (users.username = tr.username AND users.smart_assessor_id = tmp_sa_progresstrack.LearnerSmartAssessorId)
        INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
WHERE
	-- Sunesis-SA migrated Progress only
	tmp_sa_progresstrack.SunesisId IS NOT NULL
ORDER BY
	users.surname, users.firstnames


*/

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
        echo '<caption>Linked qualification progress records</caption>';
        echo '<tr><th colspan="6">Sunesis</th><th colspan="4">Smart Assessor</th><th width="100" rowspan=2">Action</th></tr>';
        echo '<tr><th rowspan="1" colspan="2">Training Record</th><th colspan="4">Qualification</th><th colspan="4">Qualification</th></tr>';
        echo '<tr><th>Learner</th><th>Framework</th><th>ID</th><th>QAN Code</th><th>Qualification</th><th>Progress</th>'
        . '<th>ID</th><th>QAN Code</th><th>Qualification</th><th>Progress</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
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

            // Smart assessor fields
            echo '<td align="left" class="SmartAssessorId">' . htmlspecialchars((string)$row['SmartAssessorId']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['QANCode']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['QualificationTitle']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['Progress_AssessedPC']) . '%</td>';

            echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['tr_id'] . ':' . $row['SmartAssessorId'] . ':' . $row['QANCode']));

            echo '</tr>';
        }
        //echo '<tr><td colspan="10">&nbsp;</td><td align="center"><input type="button" id="BtnUnlink" value="Unlink" style="color:red"/></td></tr>';
        echo '<tr><td colspan="10">&nbsp;</td><td align="center">&nbsp;</td></tr>';

        echo '</table>';
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
    `Progress` VARCHAR(200),
    `QualificationTitle` VARCHAR(200),
    `Progress_AssessedPC` VARCHAR(100),
    `Progress_MappedPC` VARCHAR(100),
    `TimeLinePerc` VARCHAR(100),
	KEY (`SunesisId`)
);
SQL;
        DAO::execute($link, $sql);
    }

    private function _renderTemporaryTable(PDO $link) {
        $rs = DAO::getResultset($link, "SELECT * FROM tmp_sa_progresstrack", DAO::FETCH_ASSOC);
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
	student_qualifications.id,student_qualifications.tr_id
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