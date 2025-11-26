<?php
class validate_ilr implements IAction
{
	public function execute(PDO $link)
	{
		
		// Check arguments
		$qan = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$qan_before_editing = isset($_REQUEST['qan_before_editing'])?$_REQUEST['qan_before_editing']:'';
		$xml = isset($_REQUEST['xml'])?$_REQUEST['xml']:'';
		$submission_date = isset($_REQUEST['submission_date'])?$_REQUEST['submission_date']:'';
		$L01 = isset($_REQUEST['L01'])?$_REQUEST['L01']:'';
		$A09 = isset($_REQUEST['A09'])?$_REQUEST['A09']:'';
		$sub = isset($_REQUEST['sub'])?$_REQUEST['sub']:'';
		
		
		
		// Run server validation routines

		$ilr = Ilr0708::loadFromXML($xml);
		$validator = new ValidateILR0708();
		$report = $validator->validate($link, $ilr);
		
		/*
		if($report!="")
		{
			throw new Exception($report);
		}
		*/

		
			$xml_escaped = addslashes((string)$xml);
			$qan_escaped = addslashes((string)$qan);
			$submission_escaped = addslashes((string)$submission_date);
			$L01_escaped = addslashes((string)$L01);
			$A09_escaped = addslashes((string)$A09);
			$sub_escaped = addslashes((string)$sub);
		
		if($report != '')
		{

			$sql = "update ilr set is_valid = 0 where concat(submission,tr_id)='$sub_escaped'";
			DAO::execute($link, $sql);
			header('Content-Type: text/xml');
			echo $report;
		}
		else
		{
			// Save to database
			
			$sql = "update ilr set is_valid = 1 where concat(submission,tr_id)='$sub_escaped'";
			DAO::execute($link, $sql);

			header("Content-Type: text/xml");
			echo '<?xml version="1.0"?><report><success l01="' . htmlspecialchars((string)$ilr->learnerinformation->L01) . '" '
				. ' l03="' . htmlspecialchars((string)$ilr->learnerinformation->L03) . '" '
				. ' a09="' . htmlspecialchars((string)$ilr->aims[0]->A09) . '" /></report>';
			
		}
		
		
		
		
		
		//http_redirect('do.php?_action=read_ilr&id=' . $qan);
		
		/*
//		if($xml == '')
//		{
//			throw new Exception("Missing or empty argument 'xml'");
//		}
		
		
		// If the QAN has changed, delete the old qualification before
		// writing the new one, otherwise we end up with more than one qualification
		// registered against a course. Allowing for the future possibility of delivering
		// more than one qualification per course is deliberate, but this is unwanted
		// at the moment.
//		if( ($qan != $qan_before_editing) && ($qan_before_editing != '') )
//		{
//			$old_qualification = Qualification::loadFromDatabase($link, $qan_before_editing);
//			$old_qualification->delete($link);
//		}
		
		
		// POST data submitted by modern browsers will be in UTF-8
		$xml = '<?xml version="1.0" encoding="utf-8"?>' . $xml;
//		$qualification = Qualification::loadFromXML($xml);
//		$qualification->save($link);
		
		
		// Presentation
//		if(IS_AJAX)
//		{
			// Return anything
//			header('Content-Type: text/xml; charset=ISO-8859-1');
//			echo $course_qualification->toXML();
//		}
//		else
//		{
			
//		}
		*/
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