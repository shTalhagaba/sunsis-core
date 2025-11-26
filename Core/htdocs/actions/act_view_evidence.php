<?php
class view_evidence implements IAction
{
	public function execute(PDO $link)
	{
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
		$target= isset($_REQUEST['target'])?$_REQUEST['target']:'';
		$achieved= isset($_REQUEST['achieved'])?$_REQUEST['achieved']:'';
		$group_id= isset($_REQUEST['group_id'])?$_REQUEST['group_id']:'';
		$evidence_title= isset($_REQUEST['evidence_title'])?$_REQUEST['evidence_title']:'';
		
		$evidence_view = ViewEvidences::getInstance($tr_id,$qualification_id,$framework_id);
		$evidence_view->refresh($link, $_REQUEST);
		
		$que = "select surname from tr where id='$tr_id'";
		$learner = trim(DAO::getSingleValue($link, $que));
		
		// Drop down list arrays
		$type_dropdown = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_qual_type ORDER BY id;";
		$type_dropdown = DAO::getResultset($link, $type_dropdown);
		$assess_dropdown = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_assess_type ORDER BY id;";
		$assess_dropdown = DAO::getResultset($link, $assess_dropdown);
		$level_checkboxes = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_qual_level ORDER BY id;";
		$level_checkboxes = DAO::getResultset($link, $level_checkboxes);

		$evidence_dropdown = "SELECT id, CONCAT(title, ' - ', type , ' - ', content , ' - ' , date , ' - ' , assessor), null FROM evidence_template ORDER BY id;";
		$evidence_dropdown = DAO::getResultset($link, $evidence_dropdown);
		
		require_once('tpl_view_evidence.php');
	}
	
	
	private function checkPermissions(PDO $link, Course $c_vo)
	{
		if($_SESSION['role'] == 'admin')
		{
			return true;
		}
		elseif($_SESSION['org']->org_type_id == ORG_PROVIDER)
		{
			$acl = CourseACL::loadFromDatabase($link, $c_vo->id);
			$is_employee = $_SESSION['org']->id == $c_vo->organisations_id;
			$is_local_admin = in_array('ladmin', $_SESSION['privileges']);
			$listed_in_course_acl = in_array($_SESSION['username'], $acl->usernames);
			
			return $is_employee && $is_local_admin;
		}
		elseif($_SESSION['org']->org_type_id == ORG_SCHOOL)
		{
			return false;
		}
		else
		{
			return false;
		}
	}
}
?>