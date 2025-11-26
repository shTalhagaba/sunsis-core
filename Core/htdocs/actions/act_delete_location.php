<?php
class delete_location implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
			$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		if($id == '')
		{
			throw new Exception("Missing argument $id");
		}
		
		$vo = Location::loadFromDatabase($link, $id);

		if(is_null($vo))
		{
			
			throw new Exception("Could not find location '$id'");
			
		}
		
		$vo->delete($link);
		 
		// Presentation
		http_redirect($_SESSION['bc']->getPrevious());
	}
}
?>