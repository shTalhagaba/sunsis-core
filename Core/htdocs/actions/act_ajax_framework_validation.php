<?php
class ajax_framework_validation implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml; charset=utf-8');
		$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';

		$qualifications = DAO::getSingleColumn($link, "select id from framework_qualifications where framework_id = $framework_id");
		$framework_code = DAO::getSingleValue($link, "select framework_code from frameworks where id = $framework_id");
		$framework_type = DAO::getSingleValue($link, "select framework_type from frameworks where id = $framework_id");

		$value = '';
		
		foreach($qualifications as $qualification)
		{
			
			$qualification = str_replace("/","",$qualification);

			$query = "select count(*) from lars201516.Core_LARS_FrameworkAims where LearnAimRef='$qualification' and ProgType='$framework_type' and FworkCode='$framework_code'";
            $mr1 = DAO::getSingleValue($link, $query);
			
			$query = "select count(*) from lad201213.framework_cmn_components where FRAMEWORK_TYPE_CODE='$framework_type' and FRAMEWORK_CODE='$framework_code' and COMMON_COMPONENT_CODE in (select COMMON_COMPONENT_CODE from lad201213.learning_aim where LEARNING_AIM_REF='$qualification')";
			$mr2 = DAO::getSingleValue($link, $query);

			$mr2 = 1;

			if($framework_code!='000' && $mr1==0 && $mr2==0 && $framework_code!='')
			{
				$value .= "If framework code is entered, it must match the framework for that learning aim in the LAD, for ER funded provision for qualification $qualification \n";
			}
		}		

		if($value!='')
		{
			echo $value; 
		}
		else
		{
			echo "Framework is Valid";
		}
	}
}
?>