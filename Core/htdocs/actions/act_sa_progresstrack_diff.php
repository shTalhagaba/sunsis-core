<?php
class sa_progresstrack_diff extends ActionController
{

	public function indexAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}

		include('smartassessor/tpl_sa_progresstrack_diff.php');
	}


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
        $saRecords = $sa->getProgresstrack();
        $this->_createTempTables($link);
        DAO::multipleRowInsert($link, 'tmp_sa_progresstrack', $saRecords);

		echo '<div style="width:800px; margin-left: auto; margin-right: auto;">';
		echo '<h3>Differences between course progress records</h3>';
		echo '</div>';

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

		echo '<div>';

		// Sort through
		$st = DAO::query($link, $sql);
                echo <<<HTML
<table class="resultset CompareRecords" cellspacing="0" cellpadding="4">
HTML;
		while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
			$diffs = array();
			$this->_compareFields($row, 'unitsUnderAssessment', 'Progress_AssessedPC', $diffs);



			echo '<thead>';
			echo '<th>&nbsp;</th><th>Internaltitle</th><th>QAN </th>';
			if (in_array('unitsUnderAssessment', $diffs)) {
				echo '<th>Progress</th>';
			}

			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';

			echo '<tr>';
			echo '<td align="left" style="font-weight:bold">Sunesis</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['internaltitle']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['QAN']), '</td>';
			echo $this->_cell($row, 'unitsUnderAssessment', $diffs);
			echo '</tr>';


			echo '<tr>';
			echo '<td  align="left" style="font-weight:bold">SmartAssessor</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['QualificationTitle']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['QANCode']), '</td>';
			echo $this->_cell($row, 'Progress_AssessedPC', $diffs);
			echo '</tr>';


		}
                                echo '</tbody></table>';
		echo '</div>';

	}

	private function _renderBrokenLinksSunesis(PDO $link)
	{
		$sql = <<<SQL
SELECT
    users.username as learner,
    users.firstnames,
    users.surname,

    assessor_review.id,
    assessor_review.smart_assessor_id,
    assessor_review.assessor,
    assessor_review.meeting_date,

    tr.id AS tr_id
FROM
	assessor_review
        INNER JOIN tr ON assessor_review.tr_id = tr.id
        INNER JOIN users ON tr.username  = users.username
        INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT OUTER JOIN tmp_sa_reviews AS tmp
		ON tmp.SmartAssessorId = assessor_review.smart_assessor_id
WHERE
	assessor_review.smart_assessor_id IS NOT NULL
	AND tmp.SunesisId IS NULL
SQL;
		$rs = DAO::query($link, $sql);

		echo '<div style="width:800px; margin-left: auto; margin-right: auto;">';
		echo '<h3>Broken Links: Records in Sunesis linked to a missing record in Smart Assessor</h3>';
		echo '<table class="resultset" cellspacing="0" cellpadding="4" width="100%">';
		echo '<tr><th>Sunesis ID</th><th>SmartAssessor ID</th><th>GivenNames</th><th>FamilyName</th><th>Assessor</th><th>Review Date</th></tr>';
		foreach ($rs as $row) {
			echo '<tr>';
			echo '<td>', htmlspecialchars((string)$row['id']), '</td>';
			echo '<td class="SmartAssessorId BrokenLinkId">', htmlspecialchars((string)$row['smart_assessor_id']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['firstnames']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['surname']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['assessor']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['meeting_date']), '</td>';
			echo '</tr>';
		}
		echo '</table>';
		echo '</div>';
	}


	private function _renderBrokenLinksSmartAssessor(PDO $link)
	{
		$sql = <<<SQL
SELECT
	users.username AS learner,

	sa.SmartAssessorId,
	sa.SunesisId,
    sa.LearnerSmartAssessorId,
    sa.AssessorSmartAssessorId,
	sa.DateTime,
	sa.Comments
FROM
	tmp_sa_reviews AS sa
        INNER JOIN users ON sa.LearnerSmartAssessorId  = users.smart_assessor_id
        INNER JOIN tr ON tr.username = users.username
        INNER JOIN student_qualifications ON (sa.QANCode = replace(student_qualifications.id,'/','')  AND student_qualifications.tr_id = tr.id)
        INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT OUTER JOIN assessor_review
		ON assessor_review.smart_assessor_id = sa.SmartAssessorId
WHERE
	assessor_review.id IS NULL
	AND sa.SunesisId IS NOT NULL
SQL;
		$rs = DAO::query($link, $sql);

		echo '<div style="width:800px; margin-left: auto; margin-right: auto;">';
		echo '<h3>Broken Links: Records in Smart Assessor linked to a missing record in Sunesis</h3>';
		echo '<table class="resultset" cellspacing="0" cellpadding="4" width="100%">';
		echo '<tr><th>Sunesis ID</th><th>SmartAssessor ID</th><th>Learner</th><th>Assessor</th><th>Review Date</th><th>Comments</th></tr>';
		foreach ($rs as $row) {
			echo '<tr>';
			echo '<td class="BrokenLinkId" align="left" >', htmlspecialchars((string)$row['SunesisId']), '</td>';
			echo '<td class="SmartAssessorId">', htmlspecialchars((string)$row['SmartAssessorId']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['LearnerSmartAssessorId']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['AssessorSmartAssessorId']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['DateTime']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['Comments']), '</td>';
			echo '</tr>';
		}
		echo '</table>';
		echo '</div>';
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


	private function _compareFields(array $row, $field1, $field2, array &$diffs)
	{
		if (trim($row[$field1]) != trim($row[$field2])) {
			$diffs[] = $field1;
			$diffs[] = $field2;
		}
	}


	private function _cell(array $row, $field, $diffs)
	{
		if (in_array($field, $diffs)) {
			return '<td align="left">' . htmlspecialchars((string)$row[$field]) . '</td>';
		}
	}
}