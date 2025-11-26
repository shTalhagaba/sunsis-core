<?php
class attach_student_qualification implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$qualification_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
 
		
// importing qualification from framework		
$query = <<<HEREDOC
insert into
	student_qualifications
select id, lsc_learning_aim, awarding_body, title, description, assessment_method, structure, level, qualification_type, accreditation_start_date, operational_centre_start_date, accreditation_end_date, certification_end_date, dfes_approval_start_date, dfes_approval_end_date, '0', '$tr_id', evidences, units, '0','0','0','0','0','0', internaltitle, '0', '','','','','','', '', '' 
from qualifications
	where id = '$qualification_id';
HEREDOC;
		DAO::execute($link, $query);
		
		http_redirect('do.php?_action=read_training_record&id=' . $tr_id);
		
	}
}
?>