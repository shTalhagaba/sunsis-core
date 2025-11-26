<?php
class sa_review_sync extends ActionController
{
	/**
	 * Compare reviews in Smart Assessor with reviews in Sunesis
	 * @param PDO $link
	 * @override
	 * @throws UnauthorizedException
	 */
	public function indexAction(PDO $link)
	{
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

		include('smartassessor/tpl_sa_review_sync.php');
	}

	/**
	 * @param PDO $link
	 * @return mixed
	 * @throws UnauthorizedException
	 */
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

        DAO::execute($link, 'truncate sa_reviews');
        DAO::multipleRowInsert($link, 'sa_reviews', $saRecords);
		DAO::multipleRowInsert($link, 'tmp_sa_reviews', $saRecords);

		$filterSections = $this->_getParam("filter_sections", array());

		if (in_array('exactmatch', $filterSections)) {
			$this->_renderExactMatch($link);
		}
		if (in_array('partialmatch', $filterSections)) {
			$this->_renderPartialMatch($link);
		}
		if (in_array('nomatchsun', $filterSections)) {
			$this->_renderNoMatchSun($link);
		}
		if (in_array('nomatchsa', $filterSections)) {
			$this->_renderNoMatchSa($link);
		}

		//$this->_renderTemporaryTable($link);
	}


	public function linkRecordsAction(PDO $link)
	{
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
			if(!preg_match('/^\d+:\w+/', $id)) {
				throw new Exception("Illegal value for id pair: " . $id);
			}
		}

		try
		{
			//DAO::transaction_start($link);
			$sa = new SmartAssessor();
			foreach ($ids as $id) {
				$pair = explode(':', $id);
				DAO::execute($link, "UPDATE users SET smart_assessor_id='{$pair[1]}' WHERE users.id={$pair[0]}");
				$sa->updateAssessor(array('SunesisId' => $pair[0], 'SmartAssessorId' => $pair[1]));
			}
			//DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			//DAO::transaction_rollback($link);
			throw new Exception("An error occurred while linking assessor records. Operation aborted.", 1, $e);
		}
	}


	public function unlinkRecordsAction(PDO $link)
	{
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
			if(!preg_match('/^\d+:\w+/', $id)) {
				throw new Exception("Illegal value for id pair: " . $id);
			}
		}

		try
		{
			//DAO::transaction_start($link);
			$sa = new SmartAssessor();
			foreach ($ids as $id) {
				$pair = explode(':', $id);
				DAO::execute($link, "UPDATE assessor_review SET smart_assessor_id=NULL WHERE assessor_review.id={$pair[0]}");
				$sa->updateReview(array('SunesisId' => '', 'SmartAssessorId' => $pair[1]));
			}
			//DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			//DAO::transaction_rollback($link);
			throw new Exception("An error occurred while unlinking review records. Operation aborted.", 1, $e);
		}

	}


    /**
	 * @param PDO $link
	 * @throws UnauthorizedException
	 * @throws Exception
	 */
	public function createRecordsInSunesisAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}
		if (!SystemConfig::get("smartassessor.soap.enabled")) {
			throw new Exception("SmartAssessor integration is not enabled for this Sunesis site.");
		}

        // Create temporary table and Insert records from Smart Assessor
        $sa = new SmartAssessor();
		$saRecords = $sa->getReviews();
		$this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_sa_reviews', $saRecords);

		// Allow longer execution time
		set_time_limit(180); // 3 minutes

		$ids = (array) $this->_getParam("ids");
		if (count($ids) == 0) {
			throw new Exception("No records selected to create");
		}
		/*foreach($ids as $id) {
			if (!is_numeric($id)) {
				throw new Exception("Illegal non-numeric value for id: " . $id);
			}
		}   */
		$SmartAssessor_ids = DAO::pdo_implode($ids);

$sql = <<<SQL
SELECT
    sa.SmartAssessorId AS smart_assessor_id,
	sa.StartTime AS meeting_date,
    sa.Comments AS assessor_comments,
    if(sa.Status='attended','green','red') AS comments,
    (SELECT username from users WHERE users.smart_assessor_id = sa.AssessorSmartAssessorId) AS assessor,
    tr.id AS tr_id,
    student_qualifications.id AS qualification
FROM
	tmp_sa_reviews AS sa
        INNER JOIN users ON sa.LearnerSmartAssessorId  = users.smart_assessor_id
        INNER JOIN tr ON tr.username = users.username
        INNER JOIN student_qualifications ON (sa.QANCode = replace(student_qualifications.id,'/','')  AND student_qualifications.tr_id = tr.id)
        INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
WHERE
	sa.SmartAssessorId IN ($SmartAssessor_ids)
SQL;
		$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		try
		{
			$sa = new SmartAssessor(false);
			foreach ($rows as $row) {
				$sa->createReviewInSunesis($row);
			}
		}
		catch(Exception $e)
		{
			throw new Exception("An error occurred while creating review records. Operation aborted.", 1, $e);
		}
	}


	private function _renderPartialMatch(PDO $link)
	{
		$type = User::TYPE_ASSESSOR;
		$requiredFields = array('firstnames', 'surname');

		$sql = <<<SQL
SELECT
	organisations.id AS `organisations_id`,
	organisations.legal_name AS `organisations_legal_name`,
	organisations.edrs AS `organisations_edrs`,
	locations.id AS `locations_id`,
	locations.full_name AS `locations_full_name`,
	locations.postcode AS `locations_postcode`,
	users.id,
	users.employer_location_id,
	users.smart_assessor_id,
	users.surname,
	users.firstnames,
    users.username,
	users.dob,
	GROUP_CONCAT(tmp_sa_assessors.SmartAssessorId) AS `smart_assessor_ids`
FROM
	users INNER JOIN organisations INNER JOIN locations
		ON users.employer_id = organisations.id
		AND users.employer_location_id = locations.id
	INNER JOIN tmp_sa_assessors
		-- Partial match on name and Login
		ON (users.firstnames = tmp_sa_assessors.FirstName AND users.surname = tmp_sa_assessors.LastName AND users.username = tmp_sa_assessors.Login)
WHERE
	-- Sunesis assessor records only
	users.`type` = {$type}
	-- Smart Assessor assessor records not linked to a Sunesis assessor
	AND tmp_sa_assessors.SunesisId IS NULL
	-- Sunesis assessor records not linked to a Smart Assessor assessor
	AND users.smart_assessor_id IS NULL
GROUP BY
	users.id
ORDER BY
	users.surname, users.firstnames
SQL;
		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$duplicatedReviews = $this->_getDuplicatedReviews($link);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<colgroup span="7">';
		echo '<col width="100"/>';  // Name
		echo '<col width="80"/>';   // Postcode
		echo '<col width="50"/>';   // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // DOB
		echo '<col width="80"/>';   // Username
		echo '</colgroup>';
		echo '<colgroup span="5">';
		echo '<col width="100"/>';  // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // DOB
		echo '<col width="80"/>';   // Username
		echo '</colgroup>';
		echo '<col width="100"/>';  // Action
		echo '<caption>Potential links between existing records</caption>';
		echo '<tr><th colspan="7">Sunesis</th><th colspan="5">Smart Assessor</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th colspan="2">Training Provider</th><th colspan="5">Assessor</th><th colspan="5">Assessor</th></tr>';
		echo '<tr><th>Name</th><th>Postcode</th><th>ID</th><th>Surname</th><th>Firstnames</th><th>DOB</th><th>Username</th>'
			. '<th>ID</th><th>Surname</th><th>Firstnames</th><th>DOB</th><th>Username</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
		foreach ($rs as $row) {
			$rowValid = true;
			foreach ($requiredFields as $field) {
				$rowValid = $rowValid && !empty($row[$field]);
				if (!$rowValid) {
					break;
				}
			}
			$rowValid = $rowValid && !in_array($row['id'], $duplicatedReviews); // Sunesis assessor not duplicated
			$rowValid = $rowValid && (strpos($row['smart_assessor_ids'], ',') === false); // Not more than one match in Smart Assessor

			$smartAssessorIds = explode(',', $row['smart_assessor_ids']); // Unpack
			$rowSpan = count($smartAssessorIds);
			$smartAssessorIds = DAO::quote($smartAssessorIds); // Repack with quotes

			if (!$rowValid) {
				echo '<tr style="color:gray" class="Data">';
			} else {
				echo '<tr class="Data">';
			}

			// Training Provider
			echo '<td align="left" rowspan="', $rowSpan, '">', htmlspecialchars((string)$row['organisations_legal_name']) , '</td>';
			echo '<td align="left" rowspan="', $rowSpan, '">', htmlspecialchars((string)$row['locations_postcode']) , '</td>';

			// Assessor
			echo '<td align="right"><a href="/do.php?_action=read_user&id=' . $row['id'] . '" target="_blank">' . htmlspecialchars((string)$row['id']) . '</a></td>';
			echo '<td align="left" rowspan="', $rowSpan, '">', htmlspecialchars((string)$row['surname']), '</td>';
			echo '<td align="left" rowspan="', $rowSpan, '">', htmlspecialchars((string)$row['firstnames']), '</td>';
			echo '<td align="left" rowspan="', $rowSpan, '">&nbsp;</td>';
			echo '<td align="left" rowspan="', $rowSpan, '">', htmlspecialchars((string)$row['username']), '</td>';

			// Get more details on the matching records in SmartAssessor
			$sql = <<<HEREDOC
SELECT
	SmartAssessorId,
	FirstName,
	LastName,
	Login
FROM
	tmp_sa_assessors
WHERE
	SmartAssessorId IN ($smartAssessorIds)
HEREDOC;
			$options  = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

			echo '<td align="left" class="SmartAssessorId">', htmlspecialchars((string)$options[0]['SmartAssessorId']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$options[0]['LastName']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$options[0]['FirstName']), '</td>';
			echo '<td align="left">&nbsp;</td>';
        	echo '<td align="left">', htmlspecialchars((string)$options[0]['Login']), '</td>';

			if (count($options) == 1) {
				echo sprintf('<td align="center"><input class="SelectRow" type="checkbox" value="%s"/></td>', htmlspecialchars((string)$row['id'] . ':' . $options[0]['SmartAssessorId']));
				echo '</tr>';
			} else {
				echo '<td rowspan="', $rowSpan, '">&nbsp;</td>'; // No checkbox
				echo '</tr>';

				// Remaining matches
				for ($i = 1; $i < count($options); $i++) {
					echo '<tr style="color:gray" class="Data">';
					echo '<td align="left" class="SmartAssessorId">', htmlspecialchars((string)$options[$i]['SmartAssessorId']), '</td>';
					echo '<td align="left">', htmlspecialchars((string)$options[$i]['FirstName']), '</td>';
					echo '<td align="left">', htmlspecialchars((string)$options[$i]['LastName']), '</td>';
					echo '<td align="left">&nsp;</td>';
					echo '<td align="left">', htmlspecialchars((string)$options[$i]['Login']), '</td>';
					echo '</tr>';
				}
			}
		}
		echo '<tr><td colspan="12">&nbsp;</td><td align="center"><input type="button" id="BtnLink" value="Link"/></td></tr>';
		echo '</table>';
	}


	private function _renderNoMatchSun(PDO $link)
	{
		$requiredFields = array('firstnames', 'surname');

		$sql = <<<SQL
SELECT
    users.username as learner,
    users.firstnames,
    users.surname,
    assessor_review.assessor,
    assessor_review.meeting_date,
    assessor_review.id,
    tr.id AS tr_id,
    student_frameworks.title AS framework,
    tmp.*
FROM
	assessor_review
        INNER JOIN tr ON assessor_review.tr_id = tr.id
        INNER JOIN users ON tr.username  = users.username
        INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT OUTER JOIN tmp_sa_reviews AS tmp
		ON tmp.SmartAssessorId = assessor_review.smart_assessor_id
WHERE
	-- Sunesis assessors without an existing link to a Smart Assessor assessor
	assessor_review.smart_assessor_id IS NULL
    AND assessor_review.assessor IS NOT NULL AND assessor_review.assessor !=''
	-- Smart Assessor assessor without an existing link to a Sunesis assessor
	AND tmp.SunesisId IS NULL
	-- Return only those rows without a matching row in Smart Assessor
	AND tmp.SmartAssessorId IS NULL
ORDER BY
	users.surname, users.firstnames
SQL;

		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$duplicated_assessors = $this->_getDuplicatedReviews($link);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<colgroup span="7">';
		echo '<col width="100"/>';  // Name
		echo '<col width="80"/>';   // Framework
		echo '<col width="50"/>';   // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // Assessor
		echo '<col width="80"/>';   // Review date
		echo '</colgroup>';
		echo '<colgroup span="5" style="background-color:#FAFAFA">';
		echo '<col width="100"/>';  // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // Assessor
		echo '<col width="80"/>';   // Review date
		echo '</colgroup>';
		echo '<col width="100"/>';  // Action
		echo '<caption>Sunesis reviews with no record in Smart Assessor (Push)</caption>';
		echo '<tr><th colspan="7">Sunesis</th><th colspan="5">Smart Assessor</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th colspan="2">Training Record</th><th colspan="5">Review</th><th colspan="5">Review</th></tr>';
		echo '<tr><th>Name</th><th>Framework</th><th>ID</th><th>Surname</th><th>Firstnames</th><th>Assessor</th><th>Review Date</th>'
			. '<th>ID</th><th>Surname</th><th>Firstnames</th><th>Assessor</th><th>Review Date</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
		foreach ($rs as $row) {
			$rowValid = true;
			foreach ($requiredFields as $field) {
				$rowValid = $rowValid && !empty($row[$field]);
				if (!$rowValid) {
					break;
				}
			}
			if (in_array($row['id'], $duplicated_assessors)) {
				$rowValid = false;
			}

			if (!$rowValid) {
				echo '<tr style="color:gray" class="Data">';
			} else {
				echo '<tr class="Data">';
			}

			// Employer
			echo '<td align="left">' . htmlspecialchars((string)$row['surname']).' '.htmlspecialchars((string)$row['firstnames']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['framework']) . '</td>';

			// Assessor
			echo '<td align="right">' . htmlspecialchars((string)$row['id']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['surname']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['firstnames']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['assessor']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['meeting_date']) . '</td>';

			// Smart assessor fields
			echo '<td colspan="5">&nbsp;</td>';

			// Action checkbox
			if ($rowValid) {
				echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['id'])); // Valid record for creation in Smart Assessor
			} else {
				echo '<td>&nbsp;</td>'; // Invalid (missing fields)
			}

			echo '</tr>';
		}
		echo '<tr><td colspan="12">&nbsp;</td><td align="center"><input type="button" id="BtnCreate" value="Create"/></td></tr>';
		echo '</table>';
	}


	private function _renderNoMatchSa(PDO $link)
	{
        $requiredFields = array('SmartAssessorId','assessor');

		$sql = <<<SQL
SELECT
	sa.*,
    users.username as learner,
    users.firstnames,
    users.surname,
    users.username,
    (SELECT username from users WHERE users.smart_assessor_id = sa.AssessorSmartAssessorId) AS assessor,
    tr.id,
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
		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<colgroup>';
		echo '<col width="100"/>';  // Name
		echo '<col width="80"/>';   // Framework
		echo '</colgroup>';
		echo '<colgroup style="background-color:#FAFAFA">';
		echo '<col width="50"/>';   // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // Assessor
		echo '<col width="80"/>';   // Review Date
		echo '</colgroup>';
		echo '<colgroup>';
		echo '<col width="100"/>';  // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // Assessor
		echo '<col width="80"/>';   // Review Date
		echo '</colgroup>';
		echo '<col width="100"/>';  // Action
		echo '<caption>Smart Assessor reviews with no record in Sunesis (Pull)</caption>';
		echo '<tr><th colspan="7">Sunesis</th><th colspan="5">Smart Assessor</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th colspan="2">Training Record</th><th colspan="5">Review</th><th colspan="5">Review</th></tr>';
		echo '<tr><th>Name</th><th>Framework</th><th>ID</th><th>Surname</th><th>Firstnames</th><th>Asssessor</th><th>Review Date</th>'
			. '<th>ID</th><th>Surname</th><th>Firstnames</th><th>Assessor</th><th>Review Date</th><th><input type="checkbox" class="SelectAll"/></tr>';
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

			//echo '<tr class="Data">';
			echo '<td align="left">' . htmlspecialchars((string)$row['surname']).' '.htmlspecialchars((string)$row['firstnames']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['framework']) . '</td>';
			echo '<td colspan="5">&nbsp;</td>';
			echo '<td align="left" class="SmartAssessorId">' . htmlspecialchars((string)$row['SmartAssessorId']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['surname']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['firstnames']) . '</td>';
            if($row['assessor'] == ''){
            echo '<td align="left"><font color="red"><a href="/do.php?_action=sa_assessor_sync" target="_blank">' . htmlspecialchars((string)$row['AssessorSmartAssessorId']) . '</a><br> (Not migrated)</font></td>';
            } else {
			echo '<td align="left">' . htmlspecialchars((string)$row['assessor']) . '</td>';
            }
			echo '<td align="left">' . htmlspecialchars((string)$row['StartTime']) . '</td>';
			//echo '<td style="background-color:#FAFAFA">&nbsp;</td>';

            // Action checkbox
			if ($rowValid) {
                echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['SmartAssessorId'])); // Valid record for creation in Smart Assessor
            } else {
				echo '<td>&nbsp;</td>'; // Invalid (missing fields)
			}

			echo '</tr>';
		}
        echo '<tr><td colspan="12">&nbsp;</td><td align="center"><input type="button" id="BtnCreateInSunesis" value="Create in Sunesis"/></td></tr>';
		echo '</table>';
	}


	private function _renderExactMatch(PDO $link)
	{

$sql = <<<SQL
SELECT
    assessor_review.id AS `id`,
	assessor_review.meeting_date AS `meeting_date`,
	assessor_review.assessor AS `assessor`,
	assessor_review.comments AS `comments`,
    assessor_review.assessor_comments AS `assessor_comments`,
    assessor_review.qualification AS `qualification`,
    assessor_review.smart_assessor_id AS `smart_assessor_id`,
    student_frameworks.title AS framework,
	users.id AS userid,
	users.username,
	users.surname,
	users.firstnames
FROM
	assessor_review
        INNER JOIN tr ON assessor_review.tr_id = tr.id
        INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
        INNER JOIN users ON users.username = tr.username
WHERE
    (assessor_review.smart_assessor_id != '' AND  assessor_review.smart_assessor_id IS NOT NULL)
ORDER BY
	users.surname, users.firstnames
SQL;

	   /*	$sql = <<<SQL
SELECT
    assessor_review.id AS `id`,
	assessor_review.meeting_date AS `meeting_date`,
	assessor_review.assessor AS `assessor`,
	assessor_review.comments AS `comments`,
    assessor_review.assessor_comments AS `assessor_comments`,
    assessor_review.qualification AS `qualification`,
    student_frameworks.title AS framework,
	users.id AS userid,
	users.username,
	users.surname,
	users.firstnames,
    tmp_sa_reviews.*
FROM
	assessor_review
        INNER JOIN tr ON assessor_review.tr_id = tr.id
        INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
        INNER JOIN users ON users.username = tr.username
    INNER JOIN tmp_sa_reviews ON assessor_review.id = tmp_sa_reviews.SunesisId
WHERE
	-- Sunesis-SA migrated reviews only
	tmp_sa_reviews.SunesisId IS NOT NULL
ORDER BY
	users.surname, users.firstnames
SQL;   */

/****
SELECT
    assessor_review.id AS `id`,
	assessor_review.meeting_date AS `meeting_date`,
	assessor_review.assessor AS `assessor`,
	assessor_review.comments AS `comments`,
    assessor_review.assessor_comments AS `assessor_comments`,
    assessor_review.qualification AS `qualification`,
    student_frameworks.title AS framework,
	users.id AS userid,
	users.username,
	users.surname,
	users.firstnames,
	tmp_sa_reviews.*
FROM
	assessor_review
        INNER JOIN tr ON assessor_review.tr_id = tr.id
        INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	INNER JOIN tmp_sa_reviews ON assessor_review.id = tmp_sa_reviews.SunesisId
        INNER JOIN users ON (users.username = tr.username AND users.smart_assessor_id = tmp_sa_reviews.LearnerSmartAssessorId )
        INNER JOIN student_qualifications ON (student_qualifications.tr_id = tr.id AND replace(student_qualifications.id,'/','') = tmp_sa_reviews.QANCode)
WHERE
	-- Sunesis-SA migrated reviews only
	tmp_sa_reviews.SunesisId IS NOT NULL
ORDER BY
	users.surname, users.firstnames

*/
		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<col width="100"/>';  // Name
		echo '<col width="80"/>';   // Framework
		echo '<col width="50"/>';   // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // Assessor
		echo '<col width="80"/>';   // Review Date
		echo '<col width="100"/>';  // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // Assessor
		echo '<col width="80"/>';   // Review Date
		echo '<col width="100"/>';  // Action
		echo '<caption>Linked review records</caption>';
		echo '<tr><th colspan="7">Sunesis</th><th colspan="5">Smart Assessor</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th rowspan="1" colspan="2">Training Record</th><th colspan="5">Review</th><th colspan="5">Session</th></tr>';
		echo '<tr><th>Name</th><th>Framework</th><th>ID</th><th>Surname</th><th>Firstnames</th><th>Assessor</th><th>Review Date</th>'
			. '<th>ID</th><th>Surname</th><th>Firstnames</th><th>Assessor</th><th>Review Date</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
		foreach ($rs as $row) {
			echo '<tr class="Data">';

			// Training Record
			echo '<td align="left">' . htmlspecialchars((string)$row['surname']).' '.htmlspecialchars((string)$row['firstnames']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['framework']) . '</td>';

			// Review
			echo '<td align="right">'. htmlspecialchars((string)$row['id']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['surname']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['firstnames']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['assessor']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['meeting_date']) . '</td>';

			// Smart assessor fields
			echo '<td align="left" class="SmartAssessorId">' . htmlspecialchars((string)$row['smart_assessor_id']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['surname']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['firstnames']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['assessor']) . '</td>';
            echo '<td align="left">' . htmlspecialchars((string)$row['meeting_date']) . '</td>';

			echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['id'] . ':' . $row['smart_assessor_id']));

			echo '</tr>';
		}
		echo '<tr><td colspan="12">&nbsp;</td><td align="center"><input type="button" id="BtnUnlink" value="Unlink" style="color:red"/></td></tr>';

		echo '</table>';
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


	private function _renderTemporaryTable(PDO $link)
	{
		$rs = DAO::getResultset($link, "SELECT * FROM tmp_sa_reviews", DAO::FETCH_ASSOC);
		HTML::renderResultset($rs);
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
}