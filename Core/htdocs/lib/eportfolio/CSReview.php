<?php
class CSReview extends Entity
{
	public function __construct($tr_id)
	{
		$this->tr_id = $tr_id;
		$this->review = XML::loadSimpleXML('<Assessment></Assessment>');
		$this->action_plan = XML::loadSimpleXML('<ActionPlan></ActionPlan>');
		$signatures_xml = <<<XML
<Signatures>
		<Review id="1">
		<Apprentice>
			<SignText></SignText>
			<SignDate></SignDate>
		</Apprentice>
		<Manager>
			<SignText></SignText>
			<SignDate></SignDate>
		</Manager>
		<Assessor>
			<SignText></SignText>
			<SignDate></SignDate>
		</Assessor>
	</Review>
		<Review id="2">
		<Apprentice>
			<SignText></SignText>
			<SignDate></SignDate>
		</Apprentice>
		<Manager>
			<SignText></SignText>
			<SignDate></SignDate>
		</Manager>
		<Assessor>
			<SignText></SignText>
			<SignDate></SignDate>
		</Assessor>
	</Review>
		<Review id="3">
		<Apprentice>
			<SignText></SignText>
			<SignDate></SignDate>
		</Apprentice>
		<Manager>
			<SignText></SignText>
			<SignDate></SignDate>
		</Manager>
		<Assessor>
			<SignText></SignText>
			<SignDate></SignDate>
		</Assessor>
	</Review>
</Signatures>
XML;

		$this->signatures = XML::loadSimpleXML($signatures_xml);
	}

	public static function loadFromDatabase(PDO $link, $id)
	{
		if($id == '')
		{
			return null;
		}

		$key = addslashes((string)$id);
		$query = <<<HEREDOC
SELECT
	*
FROM
	cs_reviews
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$review = null;
		if($st)
		{
			$review = null;
			$row = $st->fetch();
			if($row)
			{
				$review = new CSReview($row['tr_id']);
				$review->populate($row);
				$review->review = XML::loadSimpleXML($review->review);
				$review->action_plan = XML::loadSimpleXML($review->action_plan);
				$review->signatures = XML::loadSimpleXML($review->signatures);
			}
		}
		else
		{
			throw new Exception("Could not execute database query to find review record. " . '----' . $query . '----' . $link->errorCode());
		}

		return $review;
	}

	public static function loadFromDatabaseByTrainingId(PDO $link, $tr_id)
	{
		if (!$tr_id || !is_numeric($tr_id)) {
			throw new Exception("Missing or non-numeric id");
		}

		$id = DAO::getSingleValue($link, "SELECT id FROM cs_reviews WHERE tr_id=" . $link->quote($tr_id));
		if (!$id) {
			return null;
		}

		return self::loadFromDatabase($link, $id);
	}

	public function save(PDO $link)
	{
		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		@$dom->loadXML($this->review->saveXML());
		$dom->formatOutput = TRUE;
		$this->review = $dom->saveXml();
		$this->review = str_replace('<?xml version="1.0"?>', '', $this->review);

		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		@$dom->loadXML($this->action_plan->saveXML());
		$dom->formatOutput = TRUE;
		$this->action_plan = $dom->saveXml();
		$this->action_plan = str_replace('<?xml version="1.0"?>', '', $this->action_plan);

		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		@$dom->loadXML($this->signatures->saveXML());
		$dom->formatOutput = TRUE;
		$this->signatures = $dom->saveXml();
		$this->signatures = str_replace('<?xml version="1.0"?>', '', $this->signatures);

		return DAO::saveObjectToTable($link, 'cs_reviews', $this);
	}

	public function getHeaderLogo(PDO $link)
	{
		$employer_legal_name = DAO::getSingleValue($link, "SELECT legal_name FROM organisations INNER JOIN tr ON organisations.id = tr.employer_id WHERE tr.id = '{$this->tr_id}'");
		return strpos(strtolower($employer_legal_name), 'savers') !== false ? 'Savers.png' : 'superdrug.png';
	}

	public $id = NULL;
	public $tr_id = NULL;
	public $review = NULL;
	public $action_plan = NULL;
	public $signatures = NULL;
	public $old_review_data = NULL;
	
}
