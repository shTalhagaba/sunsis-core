<?php
class ajax_get_saved_filter implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		
		$q = SavedFilters::getSavedFilter($link, $id);
		
		header('Content-Type: text/xml; charset=iso-8859-1');
		if(!is_null($q))
		{
			echo '<?xml version="1.0" encoding="iso-8859-1"?>'.$q->toXML();
		}
		else
		{
			echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?><error>No filter found with id: $id</error>";
		}
	}
}
?>