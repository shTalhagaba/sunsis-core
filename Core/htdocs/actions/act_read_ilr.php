<?php
class read_ilr implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		if($id == '')
		{
			throw new Exception("Missing argument \$id");
		}
		
		
		$vo= Ilr0708::loadFromDatabase($link, $id);
		
		
		if(is_null($vo))
		{
			throw new Exception ("Could not load ILR from database");
		}
		
		
	//kh 	if(!is_null($vo->units) && ($vo->units instanceof QualificationUnits) )
	//kh	{
	//kh		$structureHTML = $vo->units->toHTML();
	//kh	}
	//kh	else
	//kh	{
	//kh		$structureHTML = '';
	//kh	}
		

		
		require_once('tpl_read_ilr.php');
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
			$num_pupils_on_course = "SELECT COUNT(*) FROM tr WHERE tr.courses_id={$c_vo->id} "
				. "AND tr.school_id={$_SESSION['org']->id};";
			$num_pupils_on_course = DAO::getSingleColumn($link, $num_pupils_on_course);
			
			return $num_pupils_on_course > 0;
		}
		else
		{
			return false;
		}
	}
	
public function make_stream($vo)
{
	throw new Exception($vo->aims[0]->A09);
}
	
	
	
}
?>