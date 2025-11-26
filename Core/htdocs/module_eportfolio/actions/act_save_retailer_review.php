<?php
class save_retailer_review implements IAction
{
	public function execute(PDO $link)
	{
		//pre($_POST);

		$vo = new RtReview($_POST['tr_id']);
		$vo->populate($_REQUEST);

		$review = XML::loadSimpleXML('<Review></Review>');

		$area_names = RtReview::getReviewAreas();
		foreach($area_names AS $a)
		{
			$area = $review->addChild('Area');
			$area->addAttribute('name', htmlspecialchars((string)$a));
			$area->addChild('KeyLearningGoals', htmlspecialchars((string)$_REQUEST[$a.'KeyLearningGoals']));
			$area->addChild('WhatHaveYouAchieved', htmlspecialchars((string)$_REQUEST[$a.'WhatHaveYouAchieved']));
			$area->addChild('NewGoals', htmlspecialchars((string)$_REQUEST[$a.'NewGoals']));
			$area->addChild('SupportingEvidence', htmlspecialchars((string)$_REQUEST[$a.'SupportingEvidence']));
			if(isset($_REQUEST[$a.'Status'][0]))
				$area->addChild('Status', htmlspecialchars((string)$_REQUEST[$a.'Status'][0]));
			else
				$area->addChild('Status', '');
			$area->addChild('Date', $_REQUEST[$a.'Date']);
		}

		$vo->review = $review;

		DAO::transaction_start($link);
		try
		{
			// if it is an existing review then do the signatures dates
			if($vo->id != '' && $_SESSION['user']->type == User::TYPE_LEARNER)
			{
				$l_sign_date = DAO::getSingleValue($link, "SELECT l_sign_date FROM retailer_reviews WHERE id = '{$vo->id}'");
				if(is_null($l_sign_date) && $_REQUEST['learner_signature'] != '')
					$vo->l_sign_date = date('Y-m-d');
			}
			if($vo->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR)
			{
				$a_sign_date = DAO::getSingleValue($link, "SELECT a_sign_date FROM retailer_reviews WHERE id = '{$vo->id}'");
				if(is_null($a_sign_date) && $_REQUEST['assessor_signature'] != '')
					$vo->a_sign_date = date('Y-m-d');
			}

			$vo->save($link);

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		if(IS_AJAX)
		{
			header("Content-Type: text/plain");
			echo 'success';
		}
		else
		{
			http_redirect($_SESSION['bc']->getPrevious());
		}

	}
}