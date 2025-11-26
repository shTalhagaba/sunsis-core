<?php
class save_new_ilr implements IAction
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
		$approve = isset($_REQUEST['approve'])?$_REQUEST['approve']:'';
		$active = isset($_REQUEST['active'])?$_REQUEST['active']:'';
		$sub = isset($_REQUEST['sub'])?$_REQUEST['sub']:'';
		$contract = substr($sub,3);
		$sub = substr($sub,0,3);

		$sql = "Select contract_type from contracts where id ='$contract'";
		$contract_type = DAO::getResultset($link, $sql);
		$contract_type = $contract_type[0][0];					
		
		
		if($approve == 'true')
			$approved=1;
		else
			$approved=0;

		if($active == 'true')
			$activated=1;
		else
			$activated=0;
			
		$ilr = Ilr0708::loadFromXML($xml);
		$xml_escaped = addslashes((string)$xml);
		$L03_escaped = addslashes((string)$qan);
		$L01_escaped = addslashes((string)$L01);
		$A09_escaped = addslashes((string)$A09);
		$is_approved_escaped = addslashes((string)$approved);
		$is_activated_escaped = addslashes((string)$activated);
		$sub_escaped = addslashes((string)$sub);
		$contract = addslashes((string)$contract);
		$contract_type = addslashes((string)$contract_type);
		
		$check=$sub_escaped.$contract;
		$sql = "Select max(tr_id) from ilr where concat(submission,contract_id)='$check.'";
		$m = DAO::getResultset($link, $sql);
		$m = (int)$m[0][0];
		$m++;
		
		$sql = "insert into ilr (L01, L03, A09, ilr, submission, contract_type, tr_id, is_complete, is_valid, is_approved, is_active, contract_id) values ('$L01_escaped','$L03_escaped','$A09_escaped','$xml_escaped','$sub_escaped','$contract_type','$m','1','0','$is_approved_escaped','$is_activated_escaped','$contract')";
		DAO::execute($link, $sql);

		header("Content-Type: text/xml");
		echo '<?xml version="1.0"?><report><success l01="' . htmlspecialchars((string)$ilr->learnerinformation->L01) . '" '
			. ' l03="' . htmlspecialchars((string)$ilr->learnerinformation->L03) . '" '
			. ' a09="' . htmlspecialchars((string)$ilr->aims[0]->A09) . '" /></report>';
			
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