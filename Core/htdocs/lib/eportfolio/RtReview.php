<?php

class RtReview extends Entity
{
	public function __construct($tr_id)
	{
		$this->tr_id = $tr_id;
		$this->review = self::getBlankXML();
	}

	public static function getReviewAreas()
	{
		return 	array(
			'customer'
			,'communication'
			,'technical'
			,'performance'
			,'team'
			,'product_and_service'
			,'business'
			,'brand_reputation'
			,'marketing'
			,'stock'
			,'sales_and_promotion'
			,'marchandising'
			,'legal_and_governance'
			,'diversity'
			,'financial'
			,'environment'
		);
	}

	/**
	 * @return xml
	 * @param null
	 */
	public static function getBlankXML()
	{
		$areas = '';
		foreach(self::getReviewAreas() AS $area)
		{
			$areas .= <<<XML
<Area name="$area">
	<KeyLearningGoals></KeyLearningGoals>
	<WhatHaveYouAchieved></WhatHaveYouAchieved>
	<NewGoals></NewGoals>
	<SupportingEvidence></SupportingEvidence>
	<Status></Status>
	<Date></Date>
</Area>
XML;
		}
		$xml = <<<XML
<Review>
	$areas
</Review>
XML;
		return XML::loadSimpleXML($xml);
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
	retailer_reviews
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
				$review = new RtReview($row['tr_id']);
				$review->populate($row);
				$review->review = XML::loadSimpleXML($review->review);
			}
		}
		else
		{
			throw new Exception("Could not execute database query to find review record. " . '----' . $query . '----' . $link->errorCode());
		}

		return $review;
	}

	public function save(PDO $link)
	{
		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		@$dom->loadXML($this->review->saveXML());
		$dom->formatOutput = TRUE;
		$this->review = $dom->saveXml();
		$this->review = str_replace('<?xml version="1.0"?>', '', $this->review);

		$this->modified = "";

		$this->learner_signature = $this->learner_signature != '' ? str_replace('do.php?_action=generate_image&', '', $this->learner_signature) : null;
		$this->assessor_signature = $this->assessor_signature != '' ? str_replace('do.php?_action=generate_image&', '', $this->assessor_signature) : null;

		return DAO::saveObjectToTable($link, 'retailer_reviews', $this);
	}

	public $id = NULL;
	public $tr_id = NULL;
	public $review = NULL;
	public $status = NULL;
	public $date = NULL;
	public $learner_signature = NULL;
	public $l_sign_date = NULL;
	public $assessor_signature = NULL;
	public $a_sign_date = NULL;
	public $modified = NULL;
	public $comments1 = NULL;
	public $comments1_date = NULL;
	public $comments2 = NULL;
	public $comments2_date = NULL;

	const STATUS_BEHIND = 'B';
	const STATUS_ONTRACK = 'O';
	const STATUS_AHEAD = 'A';
	const STATUS_COMPLETED = 'C';

}
