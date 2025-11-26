<?php
class assessor_review implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		
		$_SESSION['bc']->add($link, "do.php?_action=assessor_review&tr_id=" . $tr_id, "Assessor Review");
		
		$training_record = TrainingRecord::loadFromDatabase($link,$tr_id);

		//$start_date = new Date($training_record->start_date);		
		$end_date = new Date($training_record->target_date);

		$que = "select frequency from contracts where id=$training_record->contract_id";
		$weeks = trim(DAO::getSingleValue($link, $que));
 
		$que = "select start_date from tr where id=$tr_id";
		$first_review_date = trim(DAO::getSingleValue($link, $que));
		
		$last_review_date = new Date(DAO::getSingleValue($link, "select meeting_date from assessor_review where tr_id = '$tr_id' order by meeting_date DESC LIMIT 1"));
		$loop_date = ($end_date->getDate()>$last_review_date->getDate())?$end_date:$last_review_date;
	//	pre($loop_date);
		
		
		$start_date = new Date($first_review_date);
		
		if($weeks=='')
			$weeks = 12;
		else
			$weeks = (int)$weeks;
		
	//	$start_date->subtractDays($weeks*7);
			
		$meetings = array();
		$d = '';
		
		while($start_date->getDate() <= $loop_date->getDate())
		{
			if($weeks==1)
				$start_date->addMonths($weeks);
			else
				$start_date->addDays($weeks*7);
			
			if($start_date->getDate()<=$loop_date->getDate())
			{	
				if($start_date->getDays()<=9)
					$d = "0".$start_date->getDays();
				else
					$d = $start_date->getDays();
				
				$d .= '/';	
					
				if($start_date->getMonth()<=9)
					$d .= "0".$start_date->getMonth();
				else
					$d .= $start_date->getMonth();

				$d .= '/';	
					
				$d .= $start_date->getYear();
				
				$meetings[] = $d;
			}	
		}
		
/*		
			$start_date->addDays($weeks*7);
			if($start_date->getDays()<=9)
				$d = "0".$start_date->getDays();
			else
				$d = $start_date->getDays();
			
			$d .= '/';	
				
			if($start_date->getMonth()<=9)
				$d .= "0".$start_date->getMonth();
			else
				$d .= $start_date->getMonth();

			$d .= '/';	
				
			$d .= $start_date->getYear();
			
			$meetings[] = $d;
*/		

		//$dropdown_assessor = "SELECT users.username, CONCAT(users.firstnames, ' ' , users.surname), null FROM users INNER JOIN tr ON tr.employer_id = users.employer_id where tr.id = '$tr_id' and users.type=3;";

		if($_SESSION['user']->type==8)
		{
			$dropdown_assessor = "SELECT users.id, CONCAT(users.firstnames, ' ' , users.surname), null FROM users where (users.type=3 or users.type=6) and users.employer_id = {$_SESSION['user']->employer_id};";
			$assessor_select = DAO::getResultset($link, $dropdown_assessor);
		}
		else
		{
			$dropdown_assessor = "SELECT users.id, CONCAT(users.firstnames, ' ' , users.surname), null FROM users where users.type=3 or users.type=6;";
			$assessor_select = DAO::getResultset($link, $dropdown_assessor);
		}
			
			
		$dropdown_frequency = "SELECT frequency, description, null FROM lookup_review_frequency";
		$frequency_dropdown = DAO::getResultset($link, $dropdown_frequency);
		
		
		// Getting reviews from table
			$sql = <<<HEREDOC
SELECT
	tr_id, meeting_date, assessor, comments, paperwork_received, assessor_comments
FROM
	assessor_review
where 
	tr_id = '$tr_id'
order by 
	meeting_date;
HEREDOC;
		
		$st = $link->query($sql);	
		if($st) 
		{
			$meeting_date = array();
			$assessor = array();
			$comments = array();
			$assessor_comments = array();
			$paperwork_received = array();

			$master_date = array();
			$mdindex = 0;
			$d = new Date($training_record->start_date);

			if($weeks==1)
				$d->addMonths($weeks);
			else
				$d->addDays($weeks*7);
			
			$master_date[$mdindex++] = $d->getDays() . "/" . $d->getMonth() . "/" . $d->getYear();
			
			$c=0;
			while($row = $st->fetch())
			{
				$c++;

				$meeting_date[$c] 	= 	$row['meeting_date'];
				$assessor[$c]		=	$row['assessor'];
				$comments[$c]		=	$row['comments'];
				$paperwork_received[$c]		=	$row['paperwork_received'];
				$assessor_comments[$c]	=	$row['assessor_comments'];
				
				$master_date[$mdindex] = $meeting_date[$c];

				// Calculation of next due
				$d1 = new Date($master_date[$mdindex-0]);
				$d2 = new Date($master_date[$mdindex-1]);
				if($d1->getDate()<$d2->getDate())
				{
					if($weeks==1)
						$d1->addMonths($weeks);
					else
						$d1->addDays($weeks*7);

					if($d1->getDays()<=9)
						$d = "0".$d1->getDays();
					else
						$d = $d1->getDays();
					
					if($d1->getMonth()<=9)
						$m = "0".$d1->getMonth();
					else
						$m = $d1->getMonth();
						
					$master_date[$mdindex++] = $d . "/" . $m . "/" . $d1->getYear();
				}
				else
				{
					if($weeks==1)
						$d2->addMonths($weeks);
					else
						$d2->addDays($weeks*7);
					
					if($d2->getDays()<=9)
						$d = "0".$d2->getDays();
					else
						$d = $d2->getDays();
					
					if($d2->getMonth()<=9)
						$m = "0".$d2->getMonth();
					else
						$m = $d2->getMonth();
						
					$master_date[$mdindex++] = $d . "/" . $m . "/" . $d2->getYear();
				}

				//
/*				if($c>1)
					$start_date = new Date($row['meeting_date']);
				$start_date->addDays($weeks*7);
				if($start_date->getDays()<=9)
					$d = "0".$start_date->getDays();
				else
					$d = $start_date->getDays();
				
				$d .= '/';	
					
				if($start_date->getMonth()<=9)
					$d .= "0".$start_date->getMonth();
				else
					$d .= $start_date->getMonth();
	
				$d .= '/';	
					
				$d .= $start_date->getYear();
				
				$meetings[] = $d;
*/
				
			}
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}

	
//		pre($meeting_date);
		
		$start_date = new Date($master_date[$mdindex-1]);
		while($start_date->getDate() <= $loop_date->getDate())
		{
			if($weeks==1)
				$start_date->addMonths($weeks);
			else
				$start_date->addDays($weeks*7);
			
			if($start_date->getDate()<=$loop_date->getDate())
			{	
				if($start_date->getDays()<=9)
					$d = "0".$start_date->getDays();
				else
					$d = $start_date->getDays();
				
				$d .= '/';	
					
				if($start_date->getMonth()<=9)
					$d .= "0".$start_date->getMonth();
				else
					$d .= $start_date->getMonth();

				$d .= '/';	
					
				$d .= $start_date->getYear();
				
				$master_date[$mdindex++] = $d;
			}	
		}

		
		if($start_date->getDays()<=9)
			$d = "0".$start_date->getDays();
		else
			$d = $start_date->getDays();
		
		$d .= '/';	
			
		if($start_date->getMonth()<=9)
			$d .= "0".$start_date->getMonth();
		else
			$d .= $start_date->getMonth();

		$d .= '/';	
			
		$d .= $start_date->getYear();
		
		$master_date[$mdindex++] = $d;
		
		
	/*	$acl = ACL::loadFromDatabase($link, 'assessor_review', $tr_id);
		if(!($acl->isAuthorised($_SESSION['user'],'read') || $acl->isAuthorised($_SESSION['user'],'write')))
		{
			throw new UnauthorizedException();
		}
	*/	
		
		include('tpl_assessor_review.php');
	}
}
?>