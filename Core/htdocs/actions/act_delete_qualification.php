<?php
class delete_qualification implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
			$qualification_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
			$internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
						
		if($qualification_id == '')
		{
			throw new Exception("Missing argument \$qualification_id");
		}
		
		$vo = Qualification::loadFromDatabase($link, $qualification_id, $internaltitle);

		if(is_null($vo))
		{
			
			throw new Exception("Could not find qualification '$qualification_id'");
			
		}
		
		$vo->delete($link);
		 
		// Presentation
		http_redirect($_SESSION['bc']->getPrevious());
	}
}
?>