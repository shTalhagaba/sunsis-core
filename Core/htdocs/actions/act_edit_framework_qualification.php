<?php
class edit_framework_qualification implements IAction
{
	public function execute(PDO $link)
	{
		$qualification_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
		$internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';

		
		$_SESSION['bc']->add($link, "do.php?_action=edit_framework_qualification&id=" . $qualification_id . '&framework_id=' . $framework_id . '&internaltitle=' . $internaltitle , "Set Milestones");
		
		
		$que = "select title from frameworks where id='$framework_id'";
		$framework_title = trim(DAO::getSingleValue($link, $que));
		
/*		$que = "select start_date from frameworks where id='$framework_id'";
		$framework_start_date = trim(DAO::getSingleValue($link, $que));
		
		$que = "select end_date from frameworks where id='$framework_id'";
		$framework_end_date = trim(DAO::getSingleValue($link, $que));
*/		
		
		if($qualification_id != '')
		{
			$vo = FrameworkQualification::loadFromDatabase($link, $qualification_id, $framework_id, $internaltitle);
			//$framework_start_date = $vo->start_date;
			//$framework_end_date = $vo->end_date;
		}
		else
		{
			$vo = new FrameworkQualification(); // Blank qualification
		}
		
		// Drop down list arrays
		$type_dropdown = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_qual_type ORDER BY id;";
		$type_dropdown = DAO::getResultset($link, $type_dropdown);
		$evidence_type_dropdown = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_assess_type ORDER BY id;";
		$evidence_type_dropdown = DAO::getResultset($link, $evidence_type_dropdown);
		$assess_dropdown = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_assess_type ORDER BY id;";
		$assess_dropdown = DAO::getResultset($link, $assess_dropdown);
		$level_checkboxes = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_qual_level ORDER BY id;";
		$level_checkboxes = DAO::getResultset($link, $level_checkboxes);
		
		$assessment_method_dropdown = "SELECT id, type, null FROM lookup_evidence_type ORDER BY id;";
		$assessment_method_dropdown = DAO::getResultset($link, $assessment_method_dropdown);
		
		$evidence_type_dropdown = "SELECT id, content, null FROM lookup_evidence_content ORDER BY id;";
		$evidence_type_dropdown = DAO::getResultset($link, $evidence_type_dropdown);

		$category_dropdown = "SELECT id, category, null FROM lookup_evidence_categories ORDER BY id;";
		$category_dropdown = DAO::getResultset($link, $category_dropdown);

		$qualification_dropdown = "SELECT concat(id,'*',internaltitle), LEFT(internaltitle,80), null FROM qualifications ORDER BY internaltitle;";
		$qualification_dropdown = DAO::getResultset($link, $qualification_dropdown);

		$importUnits = array();

		$status = array(
		array('1', 'Achieved', ''),
		array('0', 'Outstanding', ''));
		
		
		require_once('tpl_edit_framework_qualification.php');
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