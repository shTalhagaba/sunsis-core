<?php
class ajax_get_qualification_xml implements IAction
{
	public function execute(PDO $link)
	{
		$qualification_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
		$clients = isset($_REQUEST['clients'])?$_REQUEST['clients']:'';


		header('Content-Type: text/xml; charset=iso-8859-1');
		
		
		$q = Qualification::loadFromDatabase($link, $qualification_id, $internaltitle, $clients);
		if($q=='')
		{
			$q = Qualification::loadFromCache($link, $qualification_id);
		} 
		
		if(!is_null($q))
		{
			echo '<?xml version="1.0" encoding="utf-8"?>'.$q->toXML();
		}
		else
		{
			/* echo '<?xml version="1.0" encoding="iso-8859-1"?><error>No qualification found with id: {$qan}</error>'; */
			echo '<?xml version="1.0" encoding="iso-8859-1"?><error>' . $qualification_id . '</error>';
			
		} 
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