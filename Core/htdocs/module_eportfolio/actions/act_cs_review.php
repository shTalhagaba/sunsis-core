<?php
class cs_review implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$_section_id = isset($_REQUEST['new_section_id'])?$_REQUEST['new_section_id']:0;
		
		if($tr_id == '')
			throw new Exception('Missing querystring argument: tr_id');
		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);
		if(is_null($tr))
			throw new Exception('Training record not found');
		if($id == '')
		{
			// extra check to prevent creating another review for the learner - might happen if you open the screen in new tab
			$exists = DAO::getSingleValue($link, "SELECT id FROM cs_reviews WHERE tr_id = '{$tr_id}' ");
			if($exists != "")
				$cs_review = CSReview::loadFromDatabase($link, $exists);
			else
			{
				$cs_review = new CSReview($tr_id);
				$cs_review->save($link);
			}
		}
		else
		{
			$cs_review = CSReview::loadFromDatabase($link, $id);
		}


		if(!is_object($cs_review->review))
		{
			$cs_review->review = XML::loadSimpleXML($cs_review->review);
		}
		if(!is_object($cs_review->action_plan))
		{
			$cs_review->action_plan = XML::loadSimpleXML($cs_review->action_plan);
		}
		if(!is_object($cs_review->signatures))
		{
			$cs_review->signatures = XML::loadSimpleXML($cs_review->signatures);
		}
		$review_xml = $cs_review->review;
		$action_plan_xml = $cs_review->action_plan;

		$enable_review1 = true;
		$enable_review2 = false;
		$enable_review3 = false;
		if(isset($cs_review->signatures))
		{
			$review3_signatures = $cs_review->signatures->xpath('/Signatures/Review[@id="3"]');
			$result = $review3_signatures[0];
			if(
				$result->Apprentice->SignText->__toString() != '' &&
				$result->Manager->SignText->__toString() != '' &&
				$result->Assessor->SignText->__toString() != ''
			)
			{
				$enable_review1 = false;
				$enable_review2 = false;
				$enable_review3 = false;
			}
			else
			{
				$review2_signatures = $cs_review->signatures->xpath('//Signatures/Review[@id="2"]');
				$result = $review2_signatures[0];
				if(
					$result->Apprentice->SignText->__toString() != '' &&
					$result->Manager->SignText->__toString() != '' &&
					$result->Assessor->SignText->__toString() != ''
				)
				{
					$enable_review1 = false;
					$enable_review2 = false;
					$enable_review3 = true;
				}
				else
				{
					$review1_signatures = $cs_review->signatures->xpath('//Signatures/Review[@id="1"]');
					$result = $review1_signatures[0];
					if(
						isset($result->Apprentice->SignText) && $result->Apprentice->SignText->__toString() != '' &&
						isset($result->Manager->SignText) && $result->Manager->SignText->__toString() != '' &&
						isset($result->Manager->SignText) && $result->Assessor->SignText->__toString() != ''
					)
					{
						$enable_review1 = false;
						$enable_review2 = true;
						$enable_review3 = false;
					}
				}
			}
		}

		$current_review = $enable_review1 ? '1' : ($enable_review2 ? '2' : ($enable_review3 ? '3' : '3'));

		if($_section_id > 0)
			$_section_id = $_section_id - 1;
		else
			$_section_id = 0;

		$learner_signature = DAO::getSingleValue($link, "SELECT signature FROM users WHERE users.username = '{$tr->username}'");
		$assessor_signature = DAO::getSingleValue($link, "SELECT signature FROM users WHERE users.id = '{$tr->assessor}'");

		if($_SESSION['user']->type == User::TYPE_VERIFIER)
		{
			$enable_review1 = false;
			$enable_review2 = false;
			$enable_review3 = false;
		}

		include_once('tpl_cs_review.php');
	}

	public function getSectionSavedInformation($review_xml, $section_id)
	{
		$result = $review_xml->xpath('//Assessment/Section[@id="'.$section_id.'"]');
		if(count($result) > 0)
			return $result[0];
		else
		{
			$section_xml = <<<XML
<Section id="$section_id">
	<Review id="1" date="">
		<Questions></Questions>
		<Comments>
			<Apprentice></Apprentice>
			<Manager></Manager>
			<Assessor></Assessor>
		</Comments>
	</Review>
	<Review id="2" date="">
		<Questions></Questions>
		<Comments>
			<Apprentice></Apprentice>
			<Manager></Manager>
			<Assessor></Assessor>
		</Comments>
	</Review>
	<Review id="3" date="">
		<Questions></Questions>
		<Comments>
			<Apprentice></Apprentice>
			<Manager></Manager>
			<Assessor></Assessor>
		</Comments>
	</Review>
	<EPA>
		<Questions></Questions>
	</EPA>
</Section>
XML;
			return XML::loadSimpleXML($section_xml);
		}
	}
}