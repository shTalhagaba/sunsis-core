<?php
class copy_framework implements IAction
{
	public function execute(PDO $link)
	{
		$fid = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
 
		
// Importing framework
$query = <<<HEREDOC
insert into
	student_frameworks
select title, id, '$tr_id', start_date, end_date, sector, comments
from frameworks
	where id = '$fid';
HEREDOC;
		DAO::execute($link, $query);

// importing qualification from framework		
$query = <<<HEREDOC
insert into
	student_qualifications
select id, lsc_learning_aim, awarding_body, title, description, assessment_method, structure, level, qualification_type, accreditation_start_date, operational_centre_start_date, accreditation_end_date, certification_end_date, dfes_approval_start_date, dfes_approval_end_date, '$fid', '$tr_id', evidences, units,'0','0','0','0','0','0', internaltitle, proportion, '', '', '', '', '', '','', start_date, end_date , '', ''
from framework_qualifications
	where framework_id = '$fid';
HEREDOC;
		DAO::execute($link, $query);
		
// Importing milestones
$query = <<<HEREDOC
insert into
	student_milestones
select *, '$tr_id'
from milestones
	where framework_id = '$fid';
HEREDOC;
		DAO::execute($link, $query);

		http_redirect('do.php?_action=read_training_record&id=' . $tr_id);		
		
	}
}
?>