<?php
class CrontabActionSynchroniseReviews extends CrontabAction
{
	public $StartTime;
	public $Comments;
	public $Status;
	

	public function __construct()
	{
		$this->task = 'SynchroniseReviews';
	}

	public function execute(PDO $link)
	{
		if (!SystemConfig::get('smartassessor.soap.enabled')) {
			return;
		}

		// Populate tmp_sa_reviews
		$sa = new SmartAssessor($this->read_only);
		$saRecords = $sa->getReviews();
		$this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_sa_reviews', $saRecords);

		// Unlink any Sunesis review records that no longer have a corresponding record in SmartAssessor
		//$this->_unlinkSunesisRecords($link, $sa);

		// Unlink any SmartAssessor review records that no longer have a corresponding record in Sunesis
		//$this->_unlinkSmartAssessorRecords($link, $sa);

		// Create new records in Sunesis
		$this->_createReviewsInSunesis($link, $sa);

		// Update existing linked records in both Sunesis and Smart Assessor
		//$this->_updateReviews($link, $sa);
	}

	/**
	 * Unlink any Sunesis review records that no longer have a corresponding record in SmartAssessor
	 * @param PDO $link
	 * @param SmartAssessor $sa
	 */
	private function _unlinkSunesisRecords(PDO $link, SmartAssessor $sa)
	{
		$sql = <<<SQL
SELECT
	assessor_review.id,
	assessor_review.assessor
FROM
	assessor_review LEFT OUTER JOIN tmp_sa_reviews
		ON assessor_review.smart_assessor_id = tmp_sa_reviews.SmartAssessorId
WHERE
	assessor_review.smart_assessor_id IS NOT NULL
	AND tmp_sa_reviews.SunesisId IS NULL
SQL;
		$rs = DAO::query($link, $sql);
		foreach ($rs as $row) {
			$this->log("Sunesis review {#" . $row['id'] . ', ' . $row['assessor']. '} no longer has a corresponding record in SmartAssessor.', Zend_Log::INFO);
		}

		$sql = <<<SQL
UPDATE
	assessor_review LEFT OUTER JOIN tmp_sa_reviews
		ON assessor_review.smart_assessor_id = tmp_sa_reviews.SmartAssessorId
SET
	assessor_review.smart_assessor_id = NULL
WHERE
	assessor_review.smart_assessor_id IS NOT NULL
	AND tmp_sa_reviews.SunesisId IS NULL
SQL;
		if (!$this->read_only) {
			DAO::execute($link, $sql);
		}
	}

	/**
	 * Unlink any Smart Assessor review records that no longer have a corresponding record in Sunesis
	 * @param PDO $link
	 * @param SmartAssessor $sa
	 */
	private function _unlinkSmartAssessorRecords(PDO $link, SmartAssessor $sa)
	{
		$sql = <<<SQL
SELECT
	tmp_sa_reviews.SmartAssessorId
FROM
	tmp_sa_reviews LEFT OUTER JOIN assessor_review
		ON tmp_sa_reviews.SunesisId = assessor_review.id
WHERE
	assessor_review.id IS NULL
	AND tmp_sa_reviews.SunesisId IS NOT NULL
SQL;
		$rs = DAO::query($link, $sql);

		foreach($rs as $row) {
			$data = array(
				'SmartAssessorId' => $row['SmartAssessorId'],
				'SunesisId' => ''
			);
			$this->log("Smart Assessor review {". $row['SmartAssessorId'] . '} no longer has a corresponding record in Sunesis.', Zend_Log::INFO);
			$sa->updateAssessor($data);
		}
	}

	private function _createReviewsInSunesis(PDO $link, SmartAssessor $sa)
	{

		//$duplicatedReviews = $this->_getDuplicatedReviews($link);
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
	LEFT OUTER JOIN assessor_review
		-- Partial match on name and Username
		ON assessor_review.smart_assessor_id = sa.SmartAssessorId
WHERE
    -- Smart Assessor records with no existing linked record in Sunesis
	sa.SunesisId IS NULL
	-- Sunesis users with no existing linked record in Smart Assessor
	AND assessor_review.smart_assessor_id IS NULL
	-- Only those rows where no exact/partial match was made
	AND assessor_review.id IS NULL
	-- Mandatory Sunesis fields
	AND users.firstnames IS NOT NULL
	AND users.surname IS NOT NULL
	AND users.username IS NOT NULL
ORDER BY
	users.surname, users.firstnames
SQL;

		$st = DAO::query($link, $sql);
		while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
		  if($row['assessor'] != ''){
			//if (in_array($row['smart_assessor_id'], $duplicatedReviews)) {
			//	continue;
			//}
			$this->log('Creating Sunesis review: ' . $row['assessor']
			    . ' (SmartAssessorId #' . $row['smart_assessor_id'] . ')', Zend_Log::INFO);
			$sa->createReviewinSunesis($row);
          }
		}
	}


	private function _updateReviews(PDO $link, SmartAssessor $sa)
	{
		$duplicatedReviews = $this->_getDuplicatedReviews($link);

		$sql = <<<SQL
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
	   assessor_review.meeting_date != TRIM(tmp_sa_reviews.StartTime)
		OR TRIM(assessor_review.assessor_comments) != TRIM(tmp_sa_reviews.Comments)
ORDER BY
	users.surname, users.firstnames
SQL;

		// Sort through
		$st = DAO::query($link, $sql);
		$saData = array();
		$sunData = array();
		while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
			if (in_array($row['smart_assessor_id'], $duplicatedReviews)) {
				continue;
			}

			$saDatum = array();
			$sunDatum = array();
			$this->_compareSystems($row, 'StartTime', 'meeting_date', $saDatum, $sunDatum);
            $this->_compareSystems($row, 'Comments', 'assessor_comments', $saDatum, $sunDatum);


			if ($saDatum) {
				$saDatum['SmartAssessorId'] = $row['SmartAssessorId'];
				$saDatum['_row'] = $row;
				$saData[] = $saDatum;
			}

			if ($sunDatum) {
				$sunDatum['id'] = $row['review_id'];
				$sunDatum['_row'] = $row;
				$sunData[] = $sunDatum;
			}
		}

		// Update Sunesis reviews
		foreach ($sunData as $data) {
			$id = $data['_row']['review_id'];
			unset($data['_row']);   // Remove this before dumping $data below
			$this->log("Updating Sunesis review  (Sunesis #" . $id . "): "
				. $this->_serialiseArray($data), Zend_Log::INFO);
			if (!$this->read_only) {
				DAO::saveObjectToTable($link, 'assessor_review', $data);
			}
		}

		// Update Smart Assessor reviews
		foreach ($saData as $data) {
			$saId = $data['_row']['SmartAssessorId'];
			$sunId = $data['_row']['review_id'];
			unset($data['_row']);   // Remove this before dumping $data below
			$this->log("Updating SmartAssessor review (Sunesis #" . $sunId . "): "
				. $this->_serialiseArray($data), Zend_Log::INFO);
			$sa->updateReview($data);
		}

	}

	/**
	 * Helper method to _updateReview()
	 * @param array $row
	 * @param string $saKey Smart Assessor field name
	 * @param string $sunKey Sunesis fieldname
	 * @param array $saArray
	 * @param array $sunArray
	 */
	private function _compareSystems(array $row, $saKey, $sunKey, array &$saArray, array &$sunArray)
	{
		if (isset($this->$saKey) && $this->$saKey && (trim($row[$sunKey]) != trim($row[$saKey]))) {
			if ($this->$saKey == 'Sunesis') {
				$saArray[$saKey] = trim($row[$sunKey]);
			} else {
				$sunArray[$sunKey] = trim($row[$saKey]);
			}
		}
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
	`TypeofReview` VARCHAR(200),
	PRIMARY KEY (`SmartAssessorId`),
	KEY (`SunesisId`)
);
SQL;
		DAO::execute($link, $sql);


	}


	/**
	 * Returns an array of review IDs in Sunesis that are duplicates.
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

	/**
	 * Helper method
	 * @param array $array
	 * @return string
	 */
	private function _serialiseArray(array $array)
	{
		$str = '';
		foreach ($array as $field=>$key) {
			$str .= $field . '=\'' . $key . '\', ';
		}
		return trim($str, ' ,');
	}
}