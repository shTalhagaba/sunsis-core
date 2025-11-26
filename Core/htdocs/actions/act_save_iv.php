<?php 
class save_iv implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$xml = isset($_REQUEST['xml'])?$_REQUEST['xml']:'';

		//$xmlevents = new SimpleXMLElement($xml);
		$xmlevents = XML::loadSimpleXML($xml);
		$values = '';
		foreach($xmlevents->event as $event)
		{	
			//$ec = str_replace("'","\'",$event->comments);

			$auto_id = $event->event_id;
			$iv_name_1 = $event->iv_name_1;
			$iv_name_2 = $event->iv_name_2;
			$unit_1 = $event->unit1;
			$unit_2 = $event->unit2;
			$unit_3 = $event->unit3;
			$unit_4 = $event->unit4;
			$unit_5 = $event->unit5;
			$unit_6 = $event->unit6;
			$unit_7 = $event->unit7;
			$unit_8 = $event->unit8;
			$unit_9 = $event->unit9;
			$unit_10 = $event->unit10;
			
			if($event->actual_date_1=='' || $event->actual_date_1=='dd/mm/yyyy')
				$actual_date_1 = "NULL";
			else
				$actual_date_1 = "'" . Date::toMySQL($event->actual_date_1) . "'";

			if($event->actual_date_2=='' || $event->actual_date_2=='dd/mm/yyyy')
				$actual_date_2 = "NULL";
			else
				$actual_date_2 = "'" . Date::toMySQL($event->actual_date_2) . "'";

			$comment1 = $event->comment1;
			$comment2 = $event->comment2;

			if($event->action_date=='' || $event->action_date=='dd/mm/yyyy')
				$action_date = "NULL";
			else
				$action_date = "'" . Date::toMySQL($event->action_date) . "'";
			
			
			
			$values .= "('" . $auto_id . "','" . $tr_id . "','" . $iv_name_1 . "','" . $unit_1 . "','" . $unit_2 . "','" . $unit_3 . "'," . $actual_date_1 . ",'" . $iv_name_2 . "','" . $unit_4 . "','" . $unit_5 . "','" . $unit_6 . "','" . $unit_7 . "','" . $unit_8 . "','" . $unit_9 . "','" . $unit_10 . "'," . $actual_date_2 . ",'" . $comment1 . "','" . $comment2 . "'," . $action_date .  ",''),";
		}
		
		$values = substr($values, 0, -1);  

		$link->beginTransaction();
		try
		{
		
		
if($values!='')
{
	// Delete existing reviews
	$sql2 = <<<HEREDOC
delete from
	iv
where tr_id = '$tr_id'
HEREDOC;
	DAO::execute($link, $sql2);

				
// Store reviews		
		$sql2 = <<<HEREDOC
insert into iv(auto_id, tr_id, iv_name_1, unit_1, unit_2, unit_3, actual_date_1, iv_name_2, unit_4, unit_5, unit_6, unit_7, unit_8, unit_9, unit_10, actual_date_2, comment1, comment2, action_date,smart_assessor_id)
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


	
		http_redirect($_SESSION['bc']->getCurrent());
	
	}
}
?>
