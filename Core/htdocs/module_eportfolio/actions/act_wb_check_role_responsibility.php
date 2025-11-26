<?php
class wb_check_role_responsibility implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';

		if($tr_id == '')
			throw new Exception('Missing querystring argument: tr_id');

		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);

		$wb_role_responsibility = DAO::getObject($link, "SELECT * FROM wb_role_responsibility WHERE tr_id = '{$tr_id}'");
		if(is_null($wb_role_responsibility))
		{
			$fields = DAO::getSingleColumn($link, "SELECT column_name FROM information_schema.columns WHERE table_name='wb_role_responsibility';");
			$wb_role_responsibility = new stdClass();
			foreach($fields AS $f)
				$wb_role_responsibility->$f = null;

			$wb_role_responsibility->tr_id = $tr_id;

		}

		if(!is_null($wb_role_responsibility->smart_obj) && $wb_role_responsibility->smart_obj != '')
		{
			$wb_role_responsibility->smart_obj = json_decode($wb_role_responsibility->smart_obj);
		}
		else
		{
			$wb_role_responsibility->smart_obj = new stdClass();
			$wb_role_responsibility->smart_obj->work_obj_q1 = null;
			$wb_role_responsibility->smart_obj->work_obj_q1_comments = null;
			$wb_role_responsibility->smart_obj->work_obj_q2 = null;
			$wb_role_responsibility->smart_obj->work_obj_q2_comments = null;
			$wb_role_responsibility->smart_obj->work_obj_q3 = null;
			$wb_role_responsibility->smart_obj->work_obj_q3_comments = null;
			$wb_role_responsibility->smart_obj->your_smart_obj = null;
		}

		$wb_role_responsibility->rsrch = json_decode($wb_role_responsibility->rsrch);

		$answer_status = array(
			array('NA', 'Not Accepted'),
			array('A', 'Accepted')
		);

		include_once('tpl_wb_check_role_responsibility.php');
	}
}