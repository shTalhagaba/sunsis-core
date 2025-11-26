<?php
class edit_qualification implements IAction
{
	public function execute(PDO $link)
	{
		if(DB_NAME=="am_aet")
		{
			pre("Please contact Sunesis Support to download new or edit existing qualifications.");
		}
		$qualification_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
				
		if($internaltitle!='cached')
			$_SESSION['bc']->add($link, "do.php?_action=edit_qualification&id=" . $qualification_id . "&internaltitle=" . $internaltitle, "Add/ Edit Qualification");

		if($internaltitle=='cached')
		{
			$vo = Qualification::loadFromCache($link, $qualification_id);
			if(!$vo){
				throw new Exception("No qualification found with id: ".$qualification_id);
			}
		}
		else
		{
			if($qualification_id != '')
			{
				$vo = Qualification::loadFromDatabase($link, $qualification_id, $internaltitle);
				if(!$vo){
					throw new Exception("No qualification found with id: ".$qualification_id);
				}
			}
			else
			{
				$vo = new Qualification(); // Blank qualification
			}
		}

		// Drop down list arrays
		$type_dropdown = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_qual_type ORDER BY id;";
		$type_dropdown = DAO::getResultset($link, $type_dropdown);
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
		
		
		$status = array(
		array('1', 'Achieved', ''),
		array('0', 'Outstanding', ''));
		
		$qual_status = array(
		array('1', 'Full Qualification', ''),
		array('0', 'Unit Level', ''));
		
		
		
		require_once('tpl_edit_qualification.php');
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