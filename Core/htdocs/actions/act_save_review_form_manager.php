<?php
class save_review_form_manager implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		$review_id = isset($_REQUEST['review_id']) ? $_REQUEST['review_id'] : '';
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
		$key = isset($_REQUEST['key']) ? $_REQUEST['key'] : '';

		$key_to_verify = md5("SunesisSuperdrugTrainingId=".$tr_id."ReviewId=".$review_id);
		if($key != $key_to_verify)
		{
			pr($key);
			pr($key_to_verify);
			pre($_SERVER);
		}

		if($review_id == '' || $tr_id == '')
			throw new Exception('Missing querystring argument(s)');

		$review_form_present = DAO::getSingleValue($link, "SELECT COUNT(*) FROM reviews_forms WHERE review_id = '{$review_id}'");
		if($review_form_present == 0)
		{
			throw new Exception('Invalid review');
		}

		$review_form = SDReviewFormHelper::getManagerReviewFormVO($link);

		foreach($review_form AS $key => $value)
		{
			$review_form->$key = isset($_POST[$key]) ? $_POST[$key] : $review_form->$key;
		}


		DAO::saveObjectToTable($link, 'reviews_forms', $review_form);

		if(
			!is_null($review_form->m_sign) &&
			!is_null($review_form->m_sign_date)
		)
		{
			$log = new stdClass();
			$log->id = null;
			$log->review_id = $review_form->review_id;
			$log->user_type = 'MANAGER';
			$log->user_id = null;
			$log->log = json_encode($review_form);
			DAO::saveObjectToTable($link, 'reviews_forms_log', $log);
		}

		pre('Information Saved');
	}
}