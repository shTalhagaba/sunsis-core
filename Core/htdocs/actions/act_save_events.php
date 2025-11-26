<?php 
class save_events implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$xml = isset($_REQUEST['xml'])?$_REQUEST['xml']:'';
		if(!$tr_id || !is_numeric($tr_id)){
			throw new Exception("Missing or non-numeric querystring argument, 'tr_id'");
		}
		if(!$xml){
			throw new Exception("Missing or empty querystring argument, 'xml'");
		}
		
		//$xmlevents = new SimpleXMLElement($xml);
		$xmlevents = XML::loadSimpleXML($xml);
		$data = array();
		foreach($xmlevents->event as $event)
		{	
			$row = array();
			$row['auto_id'] = null;
			$row['tr_id'] = (int)$tr_id;
			$event_id = (int)$event->event_id;;
			$row['event_id'] = $event_id;
			$row['audit'] = isset($event->audit)?$event->audit:"";
			$row['event_date'] = Date::toMySQL((string)$event->date);
			if(Date::isDate($event->date))
			{
				$event_date = DAO::getSingleValue($link, "select event_date from  student_events where tr_id = '$tr_id' and event_id = '$event_id' and audit is NOT null");
				if(!Date::isDate($event_date))
					$row['audit'] = $_SESSION['user']->id;
			}
			$row['owner'] = null;
			$row['comments'] = (string)$event->comments;
			if(isset($event->approved_date))
				$row['approved_date'] = Date::toMySQL((string)$event->approved_date);
			$data[] = $row;
		}
		
		DAO::transaction_start($link);
		try
		{
			DAO::execute($link, "DELETE FROM student_events WHERE tr_id=".$tr_id);
			DAO::multipleRowInsert($link, "student_events", $data);
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
		
	}
}
?>
