<?php
class delete_provider_events implements IAction
{
	public function execute(PDO $link)
	{
		$provider_id = isset($_REQUEST['provider_id'])?$_REQUEST['provider_id']:'';
		$xml = isset($_REQUEST['xml'])?$_REQUEST['xml']:'';

		$xmlevents = XML::loadSimpleXML($xml);
		$deleteQuery = "";
		foreach($xmlevents->event as $event)
		{
			$ei = $event->event_id;
			if(!$this->isMarked($link, $ei))
				$deleteQuery .= "delete from events_template  where id = '$ei'; ";
		}

		DAO::transaction_start($link);
		try
		{
			DAO::execute($link, $deleteQuery);
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		echo "Done";
	}

	private function isMarked($link, $event_id) // this function is not required though as no already marked event id is passed to this class
	{
		$sql = "SELECT * FROM student_events WHERE event_id = " . $event_id;
		$st = $link->query($sql);
		if($st->rowCount() > 0)
			return true;
		else
			return false;
	}
}
?>


