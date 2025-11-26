<?php
class ajax_get_framework_duration implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml; charset=iso-8859-1');
	 	
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		
		$vo = Framework::loadFromDatabase($link, $id);
		
		if(!is_null($vo))
		{
			echo $vo->duration_in_months; 
		}
		else
		{
			echo "error";
		}
	}
}
?>