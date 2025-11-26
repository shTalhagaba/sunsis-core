<?php 
class save_workplace_visits implements IAction
{
	public function execute(PDO $link)
	{
	
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$xml= isset($_REQUEST['xml'])?$_REQUEST['xml']:'';
		$current_month = isset($_REQUEST['current_month'])?$_REQUEST['current_month']:'';
		$current_year = isset($_REQUEST['current_year'])?$_REQUEST['current_year']:'';
		

		//$xmlreviews = new SimpleXMLElement($xml);
		$xmlreviews = XML::loadSimpleXML($xml);
		$values = '';
		foreach($xmlreviews->evidence as $review)
		{	
			$wo = ($review->workplace_id=='')?0:$review->workplace_id;
			//$sd = ($review->start_date=='')?0:$review->start_date;
			
			if($review->start_date!='dd/mm/yyyy')
			{
				$d = new Date($review->start_date);
				$sd = "'" . $d->getYear() . '-' . $d->getMonth() . '-' . $d->getDays() . "'";
			}
			else
				$sd = 'NULL';
			
			//$ed = ($review->end_date=='')?0:$review->end_date;

			if($review->end_date!='dd/mm/yyyy')
			{
				$d = new Date($review->end_date);
				$ed = "'" . $d->getYear() . '-' . $d->getMonth() . '-' . $d->getDays() . "'"; 
			}
			else
				$ed = 'NULL';
			
				
			$co = ($review->comments=='')?'':$review->comments;
			$co = addslashes((string)$co);
			$values .= '(' . $wo . ',' . $sd . ','  . $ed . ',' . "'" . $co . "'" . ',' . $tr_id . '),';
		}
		
		$values = substr($values, 0, -1);  

		
		
// Delete existing reviews
		$sql2 = <<<HEREDOC
delete from
	workplace_visits
where tr_id = $tr_id and MONTH(start_date)=$current_month and YEAR(start_date)=$current_year
HEREDOC;
		DAO::execute($link, $sql2);

if($values!='')
{
				
// Store reviews		
		$sql2 = <<<HEREDOC
insert into 
	workplace_visits (workplace_id, start_date, end_date, comments, tr_id)
values
	$values;	
HEREDOC;
	DAO::execute($link, $sql2);
}				
	
http_redirect($_SESSION['bc']->getPrevious());
	
}
}
?>
