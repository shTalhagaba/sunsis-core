<?php
class delete_framework_qualification implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
			$qualification_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
			$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
			$internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
			
		if($qualification_id == '' || $framework_id=='')
		{
			throw new Exception("Missing argument \$qualification_id");
		}
		
		$vo = FrameworkQualification::loadFromDatabase($link, $qualification_id, $framework_id, $internaltitle);
 
		if(is_null($vo))
		{
			
			throw new Exception("Could not find framework qualification '$qualification_id' of framework '$framework_id'");
			
		}
		
		$vo->delete($link);
		
		// Presentation
		http_redirect("do.php?_action=view_framework_qualifications&id='$framework_id'");
	}
}
?>