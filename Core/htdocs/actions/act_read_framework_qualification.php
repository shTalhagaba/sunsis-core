<?php
class read_framework_qualification  implements IAction
{
	public function execute(PDO $link)
	{
		$qualification_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
		$framework_title = isset($_REQUEST['framework_title'])?$_REQUEST['framework_title']:'';
		$internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';

		if($qualification_id== '' || $framework_id=='' || $framework_title=='')
		{
			throw new Exception("Missing argument \$qualification_id");
		}

		$vo = FrameworkQualification::loadFromDatabase($link, $qualification_id, $framework_id, $internaltitle);
		
		if(is_null($vo))
		{
			$vo = new FrameworkQualification(); // Blank qualification
		}
		
		require_once('tpl_read_framework_qualification.php');
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
			
			return $is_employee && ($is_local_admin || $listed_in_course_acl);
		}
		elseif($_SESSION['org']->org_type_id == ORG_SCHOOL)
		{
			$num_pupils_on_course = "SELECT COUNT(*) FROM pot WHERE pot.courses_id={$c_vo->id} "
				. "AND pot.school_id={$_SESSION['org']->id};";
			$num_pupils_on_course = DAO::getSingleColumn($link, $num_pupils_on_course);
			
			return $num_pupils_on_course > 0;
		}
		else
		{
			return false;
		}
	}
}
?>