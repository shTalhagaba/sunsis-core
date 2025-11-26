<?php
class delete_student_qualification implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
		
		
/*		if($qualification_id == '' || $framework_id=='' || $tr_id=='')
		{
			throw new Exception("Missing argument \$qualification_id \$framework_id \$tr_id");
		}
*/		
		$vo = StudentQualification::loadFromDatabase($link, $qualification_id,$framework_id,$tr_id, $internaltitle);

		if(is_null($vo))
		{
			
			throw new Exception("Could not find student qualification '$id' of framework '$fid' for learner '$tr_id'");
			
		}
		
		$vo->delete($link);
		
		// Presentation
		http_redirect("do.php?_action=read_training_record&id=$tr_id");
	}
}
?>