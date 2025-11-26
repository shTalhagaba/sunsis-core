<?php
class save_review_form implements IAction
{
	public function execute(PDO $link)
	{
		$review_id = isset($_REQUEST['review_id']) ? $_REQUEST['review_id'] : '';
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

		if($review_id == '' || $tr_id == '')
			throw new Exception('Missing querystring argument(s)');

		if($_SESSION['user']->type == User::TYPE_LEARNER)
			$review_form = SDReviewFormHelper::getLearnerReviewFormVO($link);
		elseif($_SESSION['user']->type == User::TYPE_ASSESSOR)
			$review_form = SDReviewFormHelper::getAssessorReviewFormVO($link);
		elseif($_SESSION['user']->isAdmin())
			$review_form = SDReviewFormHelper::getFullReviewFormVO($link);
		else
			throw new Exception('You are not authorised to save changes to this form.');

		foreach($_POST AS $key => $value)
		{
			$review_form->$key = $value;
		}

		DAO::saveObjectToTable($link, 'reviews_forms', $review_form);

		if(
			$_SESSION['user']->type == User::TYPE_LEARNER &&
			!is_null($review_form->l_sign) &&
			!is_null($review_form->l_sign_date)
		)
		{
			$log = new stdClass();
			$log->id = null;
			$log->review_id = $review_form->review_id;
			$log->user_type = 'LEARNER';
			$log->user_id = $_SESSION['user']->id;
			$log->log = json_encode($review_form);
			DAO::saveObjectToTable($link, 'reviews_forms_log', $log);
		}
		if(
			$_SESSION['user']->type != User::TYPE_LEARNER &&
			!is_null($review_form->a_sign) &&
			!is_null($review_form->a_sign_date)
		)
		{
			$log = new stdClass();
			$log->id = null;
			$log->review_id = $review_form->review_id;
			$log->user_type = 'ASSESSOR';
			$log->user_id = $_SESSION['user']->id;
			$log->log = json_encode($review_form);
			DAO::saveObjectToTable($link, 'reviews_forms_log', $log);
		}

		http_redirect($_SESSION['bc']->getCurrent());
	}
}