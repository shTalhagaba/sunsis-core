<?php 
class save_assessment_plan implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$xml = isset($_REQUEST['xml'])?$_REQUEST['xml']:'';
		$frequency = isset($_REQUEST['frequency'])?$_REQUEST['frequency']:'';
		$weeks= isset($_REQUEST['weeks'])?$_REQUEST['weeks']:'';

		//$xmlreviews = new SimpleXMLElement($xml);
		$xmlreviews = XML::loadSimpleXML($xml);
		$values = '';
		foreach($xmlreviews->review as $review)
		{	
			$ac = str_replace("'","\'",$review->assessorcomments);
			$paperwork = ($review->paperwork=='true')?1:0;
			$d = new Date($review->date);
			$d = $d->getYear() . '-' . $d->getMonth() . '-' . $d->getDays();
			$values .= '(' . '0' . ',' . $tr_id . ',' . "'" . $d . "'" . ',' . "'" . $review->assessor . "'"  . ',' . "'" . $review->traffic . "'," . $paperwork . ",'" . $ac . "'),";
		}
		
		$values = substr($values, 0, -1);  

		DAO::transaction_start($link);
		try
		{
			if($values!='')
			{
				// Delete existing reviews
				$sql2 = <<<HEREDOC
delete from
	assessment_plan
where tr_id = '$tr_id'
HEREDOC;
				DAO::execute($link, $sql2);
	
				// Store reviews		
				$sql2 = <<<HEREDOC
insert into 
	assessment_plan (id, tr_id, meeting_date, assessor, comments, paperwork_received, assessor_comments)
values
	$values;	
HEREDOC;
				DAO::execute($link, $sql2);
			}

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}


// Frequency

	
//	if(isset($_SESSION['bc']->urls[$_SESSION['bc']->index-1]))
//		http_redirect($_SESSION['bc']->getPrevious());
	
	}
}
?>
