<?php
class wb_knowing_your_customers implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';

		if($tr_id == '')
			throw new Exception('Missing querystring argument: tr_id');
		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);
		if(is_null($tr))
			throw new Exception('Training record not found');

		if($id == '')
		{
			// extra check to prevent creating another workbook for the learner - might happen if you open the screen in new tab
			$exists = DAO::getSingleValue($link, "SELECT id FROM workbooks WHERE tr_id = '{$tr_id}' AND wb_title = 'WBKnowingYourCustomers'");
			if($exists != "")
				$wb = WBKnowingYourCustomers::loadFromDatabase($link, $exists);
			else
			{
				$wb = new WBKnowingYourCustomers($tr_id);
				$wb->tr_id = $tr->id;
				$wb->save($link);
				if(strpos(strtolower($tr->legal_name), 'savers') !== false)
					$wb->savers_or_sp = 'savers';
				else
					$wb->savers_or_sp = 'superdrug';
			}
		}
		else
		{
			$wb = Workbook::loadFromDatabase($link, $id);
		}

		if(isset($wb->wb_content->Answers))
		{
			$answers = $wb->wb_content->Answers;
			$feedback = $wb->wb_content->Feedback;
		}
		else
		{
			$wb->wb_content = XML::loadSimpleXML($wb->wb_content);
			$answers = $wb->wb_content->Answers;
			$feedback = $wb->wb_content->Feedback;
		}
		//pre($wb->wb_content->Feedback);

		$disable_answers = '';
		if($_SESSION['user']->type != User::TYPE_LEARNER)
			$disable_answers = 'disabled="disabled"';
		elseif(!$wb->enableForUser())
			$disable_answers = 'disabled="disabled"';

		$answer_status = array(
			array('NA', 'Not Accepted'),
			array('A', 'Accepted')
		);

		// for learners the bookmark css class means whatever the learner has chosen
		// for others bookmark css class means the pages with questions and answers
		if($_SESSION['user']->type == User::TYPE_LEARNER)
			$wb_bookmarks = DAO::getSingleColumn($link, "SELECT page FROM wb_bookmarks WHERE wb_id = '{$wb->id}'");
		else
			$wb_bookmarks = array();
		$wb_bookmarks = implode(',', $wb_bookmarks);

		$learner_signature = DAO::getSingleValue($link, "SELECT users.signature FROM users WHERE users.username = '{$tr->username}'");
		$assessor_signature = DAO::getSingleValue($link, "SELECT users.signature FROM users WHERE users.id = '{$tr->assessor}'");

        $wb->learner_signature = !is_null($wb->learner_sign_date) ? $learner_signature : '';

		include_once('tpl_wb_knowing_your_customers.php');
	}
}