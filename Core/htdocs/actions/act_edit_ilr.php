<?php
class edit_ilr implements IAction
{
	public function execute(PDO $link)
	{
		$submission = isset($_REQUEST['submission'])?$_REQUEST['submission']:'';
		$contract_id = isset($_REQUEST['contract_id'])?$_REQUEST['contract_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		
		if($submission == '' || $contract_id=='' || $tr_id=='')
		{
		//	throw new Exception("Missing argument ");
		}
		
		$vo = Ilr0708::loadFromDatabase($link, $submission, $contract_id, $tr_id);
		
		if($submission!='W01')
		{
			$previous_submission = (int)substr($submission,1,2);
			$previous_submission--;
			if($previous_submission<=9)
				$previous_submission = "W0" . $previous_submission;
			else
				$previous_submission = "W" . $previous_submission;
		}
		else
		{
			$previous_submission=$submission;
		}

		
		$previous_vo = Ilr0708::loadFromDatabase($link, $previous_submission, $contract_id, $tr_id);
		
		if($vo==null)
		{
			throw new Exception("Could not load from database");
		}
		
		// Drop down list arrays
		$type_dropdown = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_qual_type ORDER BY id;";
		$type_dropdown = DAO::getResultset($link, $type_dropdown);
		
		$L25_dropdown = "SELECT value, description,null from dropdown0708 where code='L25' order by value;";
		$L25_dropdown = DAO::getResultset($link,$L25_dropdown);

		$L44_dropdown = "SELECT value, description,null from dropdown0708 where code='L44' order by value;";
		$L44_dropdown = DAO::getResultset($link,$L44_dropdown);
		
		$L46_dropdown = "SELECT value, description,null from dropdown0708 where code='L46' order by value;";
		$L46_dropdown = DAO::getResultset($link,$L46_dropdown);
		
		$A56_dropdown = "SELECT value, description,null from dropdown0708 where code='L46' order by value;";
		$A56_dropdown = DAO::getResultset($link,$A56_dropdown);
		
		$A26_dropdown = "SELECT value, description,null from dropdown0708 where code='A26' order by value;";
		$A26_dropdown = DAO::getResultset($link,$A26_dropdown);
		
		$L12_dropdown = "SELECT value, description,null from dropdown0708 where code='L12' order by value;";
		$L12_dropdown = DAO::getResultset($link,$L12_dropdown);
		
		$L24_dropdown = "SELECT value, description,null from dropdown0708 where code='L24' order by value;";
		$L24_dropdown = DAO::getResultset($link,$L24_dropdown);
		
		$L14_dropdown = "SELECT value, description,null from dropdown0708 where code='L14' order by value;";
		$L14_dropdown = DAO::getResultset($link,$L14_dropdown);
		
		$L15_dropdown = "SELECT value, description,null from dropdown0708 where code='L15' order by value;";
		$L15_dropdown = DAO::getResultset($link,$L15_dropdown);
		
		$L16_dropdown = "SELECT value, description,null from dropdown0708 where code='L16' order by value;";
		$L16_dropdown = DAO::getResultset($link,$L16_dropdown);
		
		$L35_dropdown = "SELECT value, description,null from dropdown0708 where code='L35' order by value;";
		$L35_dropdown = DAO::getResultset($link,$L35_dropdown);
		
		$L36_dropdown = "SELECT value, description,null from dropdown0708 where code='L36' order by value;";
		$L36_dropdown = DAO::getResultset($link,$L36_dropdown);
		
		$L37_dropdown = "SELECT value, description,null from dropdown0708 where code='L37' order by value;";
		$L37_dropdown = DAO::getResultset($link,$L37_dropdown);
		
		$L47_dropdown = "SELECT value, description,null from dropdown0708 where code='L47' order by value;";
		$L47_dropdown = DAO::getResultset($link,$L47_dropdown);
		
		$L28_dropdown = "SELECT value, description,null from dropdown0708 where code='L28' order by value;";
		$L28_dropdown = DAO::getResultset($link,$L28_dropdown);
		
		$L39_dropdown = "SELECT value, description,null from dropdown0708 where code='L39' order by value;";
		$L39_dropdown = DAO::getResultset($link,$L39_dropdown);
		
		$A02_dropdown = "SELECT value, description,null from dropdown0708 where code='A02' order by value;";
		$A02_dropdown = DAO::getResultset($link,$A02_dropdown);
		
		$A10_dropdown = "SELECT value, description,null from dropdown0708 where code='A10' order by value;";
		$A10_dropdown = DAO::getResultset($link,$A10_dropdown);
		
		$A15_dropdown = "SELECT value, description,null from dropdown0708 where code='A15' order by value;";
		$A15_dropdown = DAO::getResultset($link,$A15_dropdown);
		
		$A16_dropdown = "SELECT value, description,null from dropdown0708 where code='A16' order by value;";
		$A16_dropdown = DAO::getResultset($link,$A16_dropdown);
		
		$A18_dropdown = "SELECT value, description,null from dropdown0708 where code='A18' order by value;";
		$A18_dropdown = DAO::getResultset($link,$A18_dropdown);
		
		$A46_dropdown = "SELECT value, description,null from dropdown0708 where code='A46' order by value;";
		$A46_dropdown = DAO::getResultset($link,$A46_dropdown);
		
		$A24_dropdown = "SELECT value, description,null from dropdown0708 where code='A24' order by value;";
		$A24_dropdown = DAO::getResultset($link,$A24_dropdown);
		
		$A53_dropdown = "SELECT value, description,null from dropdown0708 where code='A53' order by value;";
		$A53_dropdown = DAO::getResultset($link,$A53_dropdown);
		
		$A06_dropdown = "SELECT value, description,null from dropdown0708 where code='A06' order by value;";
		$A06_dropdown = DAO::getResultset($link,$A06_dropdown);
		
		$A34_dropdown = "SELECT value, description,null from dropdown0708 where code='A34' order by value;";
		$A34_dropdown = DAO::getResultset($link,$A34_dropdown);
		
		$A35_dropdown = "SELECT value, description,null from dropdown0708 where code='A35' order by value;";
		$A35_dropdown = DAO::getResultset($link,$A35_dropdown);
		
		$A50_dropdown = "SELECT value, description,null from dropdown0708 where code='A50' order by value;";
		$A50_dropdown = DAO::getResultset($link,$A50_dropdown);
		
		$L01_dropdown = "SELECT value, description,null from dropdown0708 where code='L01' order by value;";
		$L01_dropdown = DAO::getResultset($link,$L01_dropdown);
		
		$E01_dropdown = "SELECT value, description,null from dropdown0708 where code='L01' order by value;";
		$E01_dropdown = DAO::getResultset($link,$E01_dropdown);
		
		$A01_dropdown = "SELECT value, description,null from dropdown0708 where code='L01' order by value;";
		$A01_dropdown = DAO::getResultset($link,$A01_dropdown);
		
		$A54_dropdown = "SELECT value, description,null from dropdown0708 where code='A54' order by value;";
		$A54_dropdown = DAO::getResultset($link,$A54_dropdown);
		
		$L40_dropdown = "SELECT value, description,null from dropdown0708 where code='L40' order by value;";
		$L40_dropdown = DAO::getResultset($link,$L40_dropdown);
		
		$E11_dropdown = "SELECT value, description,null from dropdown0708 where code='E11' order by value;";
		$E11_dropdown = DAO::getResultset($link,$E11_dropdown);
		
		$E12_dropdown = "SELECT value, description,null from dropdown0708 where code='E12' order by value;";
		$E12_dropdown = DAO::getResultset($link,$E12_dropdown);
		
		$E13_dropdown = "SELECT value, description,null from dropdown0708 where code='E13' order by value;";
		$E13_dropdown = DAO::getResultset($link,$E13_dropdown);
		
		$E14_dropdown = "SELECT value, description,null from dropdown0708 where code='E14' order by value;";
		$E14_dropdown = DAO::getResultset($link,$E14_dropdown);
		
		$E15_dropdown = "SELECT value, description,null from dropdown0708 where code='E15' order by value;";
		$E15_dropdown = DAO::getResultset($link,$E15_dropdown);
		
		$E16_dropdown = "SELECT value, description,null from dropdown0708 where code='E16' order by value;";
		$E16_dropdown = DAO::getResultset($link,$E16_dropdown);
		
		$E18_dropdown = "SELECT value, description,null from dropdown0708 where code='E18' order by value;";
		$E18_dropdown = DAO::getResultset($link,$E18_dropdown);
		
		$E19_dropdown = "SELECT value, description,null from dropdown0708 where code='E19' order by value;";
		$E19_dropdown = DAO::getResultset($link,$E19_dropdown);
		
		$E20_dropdown = "SELECT value, description,null from dropdown0708 where code='E20' order by value;";
		$E20_dropdown = DAO::getResultset($link,$E20_dropdown);
		
		$E21_dropdown = "SELECT value, description,null from dropdown0708 where code='E21' order by value;";
		$E21_dropdown = DAO::getResultset($link,$E21_dropdown);
		
		$level_checkboxes = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_qual_level ORDER BY id;";
		$level_checkboxes = DAO::getResultset($link, $level_checkboxes);
		
		require_once('tpl_edit_ilr.php');
	}
	
	
	private function checkPermissions(mysqli $link, Course $c_vo)
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