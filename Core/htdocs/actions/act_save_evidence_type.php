<?php
class save_evidence_type implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$type = isset($_REQUEST['type'])?$_REQUEST['type']:'';
		
		if($id == '' || $type=='')
		{
			throw new Exception("Missing or empty argument ");
		}
		
		
$query = <<<HEREDOC
insert into
	lookup_evidence_type (id, type)
VALUES('$id','$type');
HEREDOC;
		DAO::execute($link, $query);
		
		
		
		
		// Presentation
//		if(IS_AJAX)
//		{
			// Return anything
//			header('Content-Type: text/xml; charset=ISO-8859-1');
//			echo $course_qualification->toXML();
//			echo header;
//		}
//		else
//		{
			//http_redirect('do.php?_action=read_qualification&id=' . $qan);
//		}		
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