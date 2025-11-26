<?php
class copy_framework2 implements IAction
{
	public function execute(PDO $link)
	{
		
		$source_id = isset($_REQUEST['source_id'])?$_REQUEST['source_id']:'';
		$target_id = isset($_REQUEST['target_id'])?$_REQUEST['target_id']:'';

		if($source_id=='' || $target_id=='')
			throw new Exception("missing arguments");
		 
// Importing framework
$query = <<<HEREDOC
insert into
	framework_qualifications
select id, lsc_learning_aim, awarding_body, title, description, assessment_method, structure, level, qualification_type, 
accreditation_start_date, operational_centre_start_date, accreditation_end_date, certification_end_date,
dfes_approval_start_date, dfes_approval_end_date, $source_id, evidences, units, internaltitle, proportion, duration_in_months, units_required, mandatory_units
from framework_qualifications
	where framework_id = '$target_id';
HEREDOC;
		DAO::execute($link, $query);

		
// Importing milestones
$query = <<<HEREDOC
insert into
	milestones
select $source_id, qualification_id, internaltitle, unit_id, month_1, month_2, month_3, month_4, month_5, month_6, month_7,
month_8, month_9, month_10, month_11, month_12, month_13, month_14, month_15, month_16, month_17, month_18, month_19,
month_20, month_21, month_22, month_23, month_24, month_25, month_26, month_27, month_28, month_29, month_30, month_31,
month_32, month_33, month_34, month_35, month_36, 0
from milestones
	where framework_id = '$target_id';
HEREDOC;
		DAO::execute($link, $query);
		
		
		http_redirect('do.php?_action=view_framework_qualifications&id='. $source_id);
		
	}	
		
}
?>