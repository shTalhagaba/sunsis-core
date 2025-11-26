<?php 
class save_event_template implements IAction
{
	public function execute(PDO $link)
	{
		$provider_id = isset($_REQUEST['provider_id'])?$_REQUEST['provider_id']:'';
		$xml = isset($_REQUEST['xml'])?$_REQUEST['xml']:'';

		//$xmlevents = new SimpleXMLElement($xml);
		$xmlevents = XML::loadSimpleXML($xml);
		$values = '';
		foreach($xmlevents->event as $event)
		{	
			$ei = $event->event_id;
			if($ei==''){
				$ei='null';
			}	
			$title = $event->title;
				
			$values .= '(' . $ei . ',"' . $title . '"),';
		}
		
		$values = substr($values, 0, -1);  

		$link->beginTransaction();
		try
		{
		
		
if($values!='')
{
// Delete existing reviews
		$sql2 = <<<HEREDOC
truncate 
events_template;
HEREDOC;
		DAO::execute($link, $sql2);
				
// Store reviews		
		$sql2 = <<<HEREDOC
insert into 
	events_template
values
	$values;	
HEREDOC;
		DAO::execute($link, $sql2);

}

			$link->commit();
			}
			catch(Exception $e)
			{
				$link->rollback();
				throw new WrappedException($e);
			}


	
		http_redirect($_SESSION['bc']->getPrevious());
	
	}
}
?>
