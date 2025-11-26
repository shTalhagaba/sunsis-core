<?php 
class save_provider_events implements IAction
{
	public function execute(PDO $link)
	{
		$provider_id = isset($_REQUEST['provider_id'])?$_REQUEST['provider_id']:'';
		$xml = isset($_REQUEST['xml'])?$_REQUEST['xml']:'';

		$xmlevents = XML::loadSimpleXML($xml);
		foreach($xmlevents->event as $event)
		{	
			$ei = $event->event_id;
			$course_id = $event->course_id != '' ? $event->course_id : 0;
			$title = addslashes((string)$event->title);
			$ptype = addslashes((string)$event->type);
			if($ei=='')
			{
//				DAO::execute($link, "insert into events_template (id, title, provider_id, programme_type) values(NULL, '$title', $provider_id, $ptype);");
				DAO::execute($link, "insert into events_template (id, title, provider_id, programme_type, course_id) values(NULL, '$title', $provider_id, $ptype, $course_id);");
			}
			else 
			{
				DAO::execute($link, "update events_template set title='$title', programme_type = '$ptype', course_id = '$course_id' where id = '$ei'");
			}
		}
		
		echo "Done";
	}
}
?>
