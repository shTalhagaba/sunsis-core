<?php
class attach_course_to_training_record implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id']:'';
		$course_id = isset($_REQUEST['course_id']) ? $_REQUEST['course_id']:'';
		$group_id = isset($_REQUEST['group_id']) ? $_REQUEST['group_id']:'';
		
		$framework_id = '0';
		$qualification_id = '0';
		
		if($tr_id=='' || $course_id=='')	
		{
			throw new Exception("Could not enrol on a course! insufficient information given");	
		}
		 
		$que = "select main_qualification_id from courses where id='$course_id'";
		$qualification_id = DAO::getSingleValue($link, $que);
		
		if($qualification_id=='')
		{
			$qualification_id  = '0';
			$que = "select framework_id from courses where id='$course_id'";
			$framework_id = DAO::getSingleValue($link, $que);
			$fid = $framework_id;
		}
		
// enroling on a course
$query = <<<HEREDOC
insert into
	courses_tr (course_id, tr_id, qualification_id, framework_id)
values($course_id, $tr_id, '$qualification_id', $framework_id);
HEREDOC;
		DAO::execute($link, $query);

		
// 	attaching to a group
$query = <<<HEREDOC
insert into
	group_members (groups_id, tr_id, member)
values($group_id, $tr_id, '');
HEREDOC;
		DAO::execute($link, $query);

// Check if this course has a framework attached to it and get framework id
$que = "select framework_id from courses where id='$course_id'";
$fid = DAO::getSingleValue($link, $que);

$que = "select id from student_frameworks where tr_id='$tr_id'";
$tr_framework_id = DAO::getSingleValue($link, $que);

if($fid!='')
{
	if($tr_framework_id=='')
	{
		
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
select id, lsc_learning_aim, awarding_body, title, description, assessment_method, structure, level, qualification_type, accreditation_start_date, operational_centre_start_date, accreditation_end_date, certification_end_date, dfes_approval_start_date, dfes_approval_end_date, '$fid', '$tr_id', evidences, units,'0','0','0','0','0','0', internaltitle, proportion, '', '', '', '', '', '','', start_date, end_date, '', '' 
from framework_qualifications
	where framework_id = '$fid';
HEREDOC;
		DAO::execute($link, $query);
		
// Importing milestones
$query = <<<HEREDOC
insert into
	student_milestones
select *, '$tr_id', '1'
from milestones
	where framework_id = '$fid';
HEREDOC;
		DAO::execute($link, $query);
		
	}
}
		
		http_redirect('do.php?_action=read_training_record&id=' . $tr_id);		

	}
}
?>