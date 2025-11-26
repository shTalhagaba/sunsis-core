<?php
class delete_framework implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
			$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';

		if($framework_id == '')
		{
			throw new Exception("Missing argument \$id");
		}
		
		$vo = Framework::loadFromDatabase($link, $framework_id);

		if(is_null($vo))
		{
			
			throw new Exception("Could not find framework '$id'");
			
		}
		 
		$vo->delete($link);
		
		// Presentation
		http_redirect('do.php?_action=view_frameworks');
	}
}
?>