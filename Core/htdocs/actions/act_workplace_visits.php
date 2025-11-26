<?php
class workplace_visits implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		
		$vo = TrainingRecord::loadFromDatabase($link, $tr_id);
		
		// Dropdowns
		$sql = "SELECT organisations.id, legal_name FROM organisations WHERE organisation_type like '%7%' ORDER BY legal_name;";
		$workplaces = DAO::getResultset($link, $sql);
		
		// Getting evidences from table
			$sql = <<<HEREDOC
SELECT
	*
FROM
	workplace_visits
where 
	tr_id = $tr_id;
HEREDOC;
		
		$st = $link->query($sql);	
		if($st) 
		{
			$data = Array();	
			$index = 1;
			while($row = $st->fetch())
			{
				$data[$index]['workplace_id'] = $row['workplace_id'];
				$data[$index]['start_date'] = $row['start_date'];
				$data[$index]['end_date'] = $row['end_date'];
				$data[$index]['comments'] = $row['comments'];
				$index++;
			}
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
		
		
		
		include('tpl_workplace_visits.php');
	}
}
?>