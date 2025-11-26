<?php
class sa_review_diff extends ActionController
{

	public function indexAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}

		include('smartassessor/tpl_sa_review_diff.php');
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
		$saRecords = $sa->getReviews();
	    $this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_sa_reviews', $saRecords);

		$duplicatedReviews = $this->_getDuplicatedReviews($link);

        $this->_renderNoMatchInSunesis($link);
        //$this->_renderBrokenLinksSunesis($link);
		//$this->_renderBrokenLinksSmartAssessor($link);


		/*echo '<div style="width:800px; margin-left: auto; margin-right: auto;">';
		echo '<h3>Differences between linked records</h3>';
		echo '</div>';

		$sql = <<<SQL
SELECT
    assessor_review.id AS `id`,
    assessor_review.qualification AS `qualification`,
	assessor_review.meeting_date AS `meeting_date`,
    assessor_review.assessor_comments AS `assessor_comments`,
    assessor_review.comments AS `comments`,
    assessor_review.assessor AS `assessor`,

    tmp_sa_reviews.SmartAssessorId,
	tmp_sa_reviews.SunesisId,
	tmp_sa_reviews.QANCode,
	tmp_sa_reviews.StartTime,
	tmp_sa_reviews.Comments,
	tmp_sa_reviews.Status,

    tr.id AS tr_id
FROM
    assessor_review
        INNER JOIN tr ON assessor_review.tr_id = tr.id
        INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
        INNER JOIN users ON users.username = tr.username
	INNER JOIN tmp_sa_reviews
        ON assessor_review.id = tmp_sa_reviews.SunesisId
WHERE
    -- Sunesis-SA migrated reviews only
	tmp_sa_reviews.SunesisId IS NOT NULL
	-- Linked records with differing field values
	AND (
		TRIM(replace(assessor_review.qualification,'/','')) != TRIM(tmp_sa_reviews.QANCode)
		OR TRIM(assessor_review.meeting_date) != TRIM(tmp_sa_reviews.StartTime)
		OR TRIM(assessor_review.assessor_comments) != TRIM(tmp_sa_reviews.Comments)
		OR TRIM(assessor_review.comments) != TRIM(tmp_sa_reviews.Status)
		)
ORDER BY
	users.surname, users.firstnames
SQL;

		echo '<div>';

		// Sort through
		$st = DAO::query($link, $sql);
		while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
			$diffs = array();
			$this->_compareFields($row, 'QANCode', 'qualification', $diffs);
			$this->_compareFields($row, 'StartTime', 'meeting_date', $diffs);
			$this->_compareFields($row, 'Comments', 'assessor_comments', $diffs);
			$this->_compareFields($row, 'Status', 'comments', $diffs);

			echo <<<HTML
<table class="resultset CompareRecords" cellspacing="0" cellpadding="4">
HTML;
			echo '<thead>';
			echo '<th>&nbsp;</th><th>ID</th><th>TR ID</th><th>TR ID</th>';
			if (in_array('QANCode', $diffs)) {
				echo '<th>QANCode</th>';
			}
			if (in_array('StartTime', $diffs)) {
				echo '<th>StartTime</th>';
			}
			if (in_array('Comments', $diffs)) {
				echo '<th>Comments</th>';
			}
			if (in_array('Status', $diffs)) {
				echo '<th>Status</th>';
			}


			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';

			echo '<tr>';
			echo '<td align="left" style="font-weight:bold">Sunesis</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['id']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['tr_id']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['tr_id']), '</td>';
			echo $this->_cell($row, 'qualification', $diffs);
			echo $this->_cell($row, 'meeting_date', $diffs);
			echo $this->_cell($row, 'assessor_comments', $diffs);
			echo $this->_cell($row, 'comments', $diffs);
			echo '</tr>';


			echo '<tr>';
			echo '<td  align="left" style="font-weight:bold">SmartAssessor</td>';
			echo '<td align="left" class="SmartAssessorId">', htmlspecialchars((string)$row['SmartAssessorId']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['tr_id']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['tr_id']), '</td>';
			echo $this->_cell($row, 'QANCode', $diffs);
			echo $this->_cell($row, 'StartTime', $diffs);
			echo $this->_cell($row, 'Comments', $diffs);
			echo $this->_cell($row, 'Status', $diffs);
			echo '</tr>';

			echo '</tbody></table>';
		}

		echo '</div>'; */

	}

    private function _renderNoMatchInSunesis(PDO $link)
	{
		$sql = <<<SQL
SELECT
	sa.*,
    users.username as learner,
    users.firstnames,
    users.surname,
    users.username,
    (SELECT username from users WHERE users.smart_assessor_id = sa.AssessorSmartAssessorId) AS assessor,
    tr.id AS tr_id,
    student_frameworks.title AS framework
FROM
	tmp_sa_reviews AS sa
        INNER JOIN users ON sa.LearnerSmartAssessorId  = users.smart_assessor_id
        INNER JOIN tr ON tr.username = users.username
        INNER JOIN student_qualifications ON (sa.QANCode = replace(student_qualifications.id,'/','')  AND student_qualifications.tr_id = tr.id)
        INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT OUTER JOIN assessor_review
		ON assessor_review.smart_assessor_id = sa.SmartAssessorId
WHERE
	-- Smart Assessor records with no existing linked record in Sunesis
	sa.SunesisId IS NULL
	-- Sunesis users with no existing linked record in Smart Assessor
	AND assessor_review.smart_assessor_id IS NULL
	-- Only those rows where no exact/partial match was made
	AND assessor_review.id IS NULL
ORDER BY
	users.firstnames, users.surname
SQL;
		$rs = DAO::query($link, $sql);

		echo '<div style="width:800px; margin-left: auto; margin-right: auto;">';
		echo '<h3>Smart Assessor reviews with no record in Sunesis</h3>';
		echo '<table class="resultset" cellspacing="0" cellpadding="4" width="100%">';
		echo '<tr><th>SmartAssessor ID</th><th>GivenNames</th><th>FamilyName</th><th>Assessor</th><th>Review Date</th><th>TR Id</th></tr>';
		foreach ($rs as $row) {
			echo '<tr>';
			echo '<td>', htmlspecialchars((string)$row['SmartAssessorId']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['firstnames']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['surname']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['assessor']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['StartTime']), '</td>';
            echo '<td>', htmlspecialchars((string)$row['tr_id']), '</td>';
			echo '</tr>';
		}
		echo '</table>';
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
	sa.StartTime,
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
			echo '<td>', htmlspecialchars((string)$row['StartTime']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['Comments']), '</td>';
			echo '</tr>';
		}
		echo '</table>';
		echo '</div>';
	}


	private function _createTempTables(PDO $link)
	{
		DAO::execute($link, "DROP TEMPORARY TABLE IF EXISTS tmp_sa_reviews");
		$sql = <<<SQL
CREATE TEMPORARY TABLE tmp_sa_reviews (
	`SmartAssessorId` CHAR(50) NOT NULL,
	`SunesisId` BIGINT,
	`LearnerSmartAssessorId` CHAR(50),
	`AssessorSmartAssessorId` CHAR(50),
	`QANCode` VARCHAR(100),
    `StartTime` VARCHAR(100),
    `Duration` VARCHAR(100),
    `PlanningNotes` TEXT,
    `Feedback` TEXT,
    `Status` VARCHAR(100),
	`Comments` VARCHAR(200),
	PRIMARY KEY (`SmartAssessorId`),
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
	private function _getDuplicatedReviews(PDO $link)
	{
		$sql = <<<SQL
SELECT
	GROUP_CONCAT(assessor_review.id) AS `review_id`
FROM
	assessor_review
WHERE
	assessor_review.assessor != ''
GROUP BY
	assessor_review.id
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