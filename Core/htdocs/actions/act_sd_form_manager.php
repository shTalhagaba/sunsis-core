<?php
class sd_form_manager implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		$review_id = isset($_REQUEST['review_id']) ? $_REQUEST['review_id'] : '';
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
		$access_key = isset($_REQUEST['key']) ? $_REQUEST['key'] : '';

		//pre(md5("SunesisSuperdrugTrainingId=3696ReviewId=95684"));
		$key_to_verify = md5("SunesisSuperdrugTrainingId=".$tr_id."ReviewId=".$review_id);
		if($access_key != $key_to_verify)
		{
			pre("Invalid URL");
		}

		if($review_id == '' || $tr_id == '')
			throw new Exception('Missing querystring argument(s)');

		$review = DAO::getObject($link, "SELECT * FROM assessor_review WHERE id = '{$_REQUEST['review_id']}'");
		if(!isset($review->id))
			throw new Exception('Invalid review_id');

		$review_form = DAO::getObject($link, "SELECT * FROM reviews_forms WHERE review_id = '{$review->id}'");
		if(!isset($review_form->review_id))
			throw new Exception('Review form is not yet ready for you to sign. Either assessor or learner still has not yet completed the information.');

		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);
		if(is_null($tr))
			throw new Exception('Invalid tr_id');

		$is_learner = false;
		$is_assessor = false;
		$is_manager = true;

		$employer = Organisation::loadFromDatabase($link, $tr->employer_id);

		$manager = DAO::getObject($link, "SELECT * FROM organisation_contact WHERE contact_id = '{$tr->crm_contact_id}'");
		if(!isset($manager->contact_id))
		{
//			$manager = new stdClass();
//			$records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM organisation_contact");
//			foreach($records AS $key => $value)
//				$manager->$value = null;
			throw new UnauthorizedException();
		}

		$superdrug = true;
		$savers = true;

		if ((preg_match('/\Savers\b/', $employer->legal_name)))
		{
			$superdrug = false;
		}
		else
		{
			$savers = false;
		}

		$qualification = DAO::getSingleValue($link, "SELECT COUNT(*) FROM student_qualifications WHERE tr_id = '{$tr->id}' AND REPLACE(id, '/', '') = '" . Workbook::RETAIL_QAN . "'");
		$qualification = $qualification > 0 ? Workbook::RETAIL_QAN : Workbook::CS_QAN;

		$qualification = DAO::getObject($link, "SELECT * FROM student_qualifications WHERE tr_id = '{$tr->id}' AND REPLACE(id, '/', '') = '{$qualification}'");

		$disable_save = false;
		if( // form is fully completed and signed
			!is_null($review_form->a_sign) &&
			!is_null($review_form->l_sign) &&
			!is_null($review_form->m_sign)
		)
			$disable_save = true;

		if(!is_null($review_form->m_sign))
			$disable_save = true;

		include_once('tpl_sd_form.php');
	}

	public function getManagerChecklist()
	{
		return
			array(
				'm_punc' => 'Punctual',
				'm_well_pres' => 'Well presented',
				'm_respectful' => 'Respectful to others',
				'm_team_player' => 'A team player',
				'm_show_pd' => 'Showing personal development',
				'm_demo_skills' => 'Demonstrating new skills',
				'm_show_conf' => 'Showing an increase in confidence',
				'm_attendance' => 'Good attendance record',
				'm_creative' => 'Creativity and imagination'
			);
	}
}