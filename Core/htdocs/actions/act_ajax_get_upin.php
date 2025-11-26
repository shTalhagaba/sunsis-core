<?php
class ajax_get_upin implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml; charset=iso-8859-1');
		
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		
		$vo = Organisation::loadFromDatabase($link, $id);
		 
		if(!is_null($vo))
		{
			/* echo '<?xml version="1.0" encoding="iso-8859-1"?>'.$vo->upin; */
			echo $vo->upin;
		}
		else
		{
			/*echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?><error>No organisation found with id: $id</error>";*/
			echo "error";
			
		}
	}
}
?>