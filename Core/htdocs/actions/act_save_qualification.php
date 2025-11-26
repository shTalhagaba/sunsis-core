<?php
class save_qualification implements IAction
{
	public function execute(PDO $link)
	{
		$qan = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$qan_before_editing = isset($_REQUEST['qan_before_editing'])?$_REQUEST['qan_before_editing']:'';
		$xml = isset($_REQUEST['xml'])?$_REQUEST['xml']:'';
		$blob = isset($_REQUEST['blob'])?$_REQUEST['blob']:'';
		$units = isset($_REQUEST['units'])?$_REQUEST['units']:'';
		$proportion = isset($_REQUEST['proportion'])?$_REQUEST['proportion']:'';
        	$lsc_learning_aim = isset($_REQUEST['lsc_learning_aim'])?$_REQUEST['lsc_learning_aim']:'';
		$unitswithevidence = isset($_REQUEST['unitswithevidence'])?$_REQUEST['unitswithevidence']:'';
		$elementswithoutevidence = isset($_REQUEST['elementswithoutevidences'])?$_REQUEST['elementswithoutevidences']:'';
		$units_required = isset($_REQUEST['unitsrequired'])?$_REQUEST['unitsrequired']:'';
		$internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
		$mandatory_units = isset($_REQUEST['mandatoryunits'])?$_REQUEST['mandatoryunits']:'';
		$status = isset($_REQUEST['status'])?$_REQUEST['status']:'';
		$active = isset($_REQUEST['isactive'])?$_REQUEST['isactive']:'';

		if($xml == '')
		{
			throw new Exception("Missing or empty argument 'xml'");
		}

		// If the QAN has changed, delete the old qualification before
		// writing the new one, otherwise we end up with more than one qualification
		// registered against a course. Allowing for the future possibility of delivering
		// more than one qualification per course is deliberate, but this is unwanted
		// at the moment.
		if( ($qan != $qan_before_editing) && ($qan_before_editing != '') )
		{
			$old_qualification = Qualification::loadFromDatabase($link, $qan_before_editing, $internaltitle); /* @var $old_qualification Qualification */
			$old_qualification->delete($link);
		}

		$blob = str_replace(' op_title="null" ', ' ', $blob);
		// POST data submitted by modern browsers will be in UTF-8
		$xml = '<?xml version="1.0" encoding="ISO-8859-1"?>' . $xml;
		$qualification = Qualification::loadFromXML($xml); /* @var $qualification Qualification */
		$qualification->evidences = mb_convert_encoding($blob,'UTF-8');
		$qualification->units = $units;
		$qualification->total_proportion = $proportion;
		$qualification->unitswithevidence = $unitswithevidence;
		$qualification->elements_without_evidence = $elementswithoutevidence;
		$qualification->units_required = $units_required;
		$qualification->mandatory_units = $mandatory_units;
        $qualification->lsc_learning_aim = $lsc_learning_aim;

		if(DB_NAME=='am_edexcel')
		{
			$qualification->clients = $_SESSION['user']->username;
		}
		else
		{
			$qualification->clients = DB_NAME;
		}		

		$qualification->qual_status = $status;
		$qualification->active = $active;		
		//	$qualification->status = $status;
//		$qualification->internaltitle = str_replace("\'","",$qualification->internaltitle);		
//		$qualification->title = str_replace("\'","",$qualification->title);		


		if(DB_NAME=="am_edexcel")
			$qualification->active = 1;

        	$qualification->ebs_ui_code = isset($_REQUEST['ebs_ui_code']) ? $_REQUEST['ebs_ui_code'] : '';

		$qualification->tqt = isset($_REQUEST['tqt']) ? $_REQUEST['tqt'] : '';
		$qualification->save($link);

		if(DB_NAME=="am_lead" && $_SESSION['user']->type == User::TYPE_MANAGER)
		{
			$provider_id = $_SESSION['user']->employer_id;
			$qualification_id = str_replace('/', '', $qualification->id);
			$internal_title = addslashes((string)$qualification->internaltitle);
			$isNotThere = DAO::getSingleValue($link, "SELECT COUNT(*) FROM provider_qualifications WHERE org_id = '$provider_id' AND qualification_id = '$qualification_id' AND internaltitle = '$internal_title'");
			if($isNotThere == 0)
				DAO::execute($link, "INSERT INTO provider_qualifications (org_id, qualification_id, internaltitle) VALUES ('$provider_id', '$qualification_id', '$internal_title')");
		}
		
		if(IS_AJAX)
		{
			// Return anything
			header('Content-Type: text/xml; charset=ISO-8859-1');
//			echo $course_qualification->toXML();
		}
		else
		{
			http_redirect($_SESSION['bc']->getPrevious());
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