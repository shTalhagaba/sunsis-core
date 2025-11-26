<?php
class ajax_get_quarters implements IAction
{
	public function execute(PDO $link)
	{

		header('Content-Type: text/xml; charset=iso-8859-1');
		 
		$start_date = isset($_REQUEST['start_date'])?$_REQUEST['start_date']:'';
		$end_date   = isset($_REQUEST['end_date'])?$_REQUEST['end_date']:'';
		
		$start_date = Date::setDate($start_date);

		throw new Exception(" " . $start_date->getMonth());
		
		if(!is_null($start_date))
		{
			echo '<?xml version="1.0" encoding="iso-8859-1"?>'.$q->toXML();
		}
		else
		{
			/* echo '<?xml version="1.0" encoding="iso-8859-1"?><error>No qualification found with id: {$qan}</error>'; */
			echo '<?xml version="1.0" encoding="iso-8859-1"?><error></error>';
			
		} 
	}
}
?>