<?php
class save_matrix implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
		$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
		$evidences = isset($_REQUEST['evidences'])?$_REQUEST['evidences']:'';
		$percentage= isset($_REQUEST['percentage'])?$_REQUEST['percentage']:'';
		$units = isset($_REQUEST['units'])?$_REQUEST['units']:'';
		$unitscompleted = isset($_REQUEST['unitscompleted'])?$_REQUEST['unitscompleted']:'';
		$unitsnotstarted = isset($_REQUEST['unitsnotstarted'])?$_REQUEST['unitsnotstarted']:'';
		$unitsbehind = isset($_REQUEST['unitsbehind'])?$_REQUEST['unitsbehind']:'';
		$unitsontrack = isset($_REQUEST['unitsontrack'])?$_REQUEST['unitsontrack']:'';
		$unitsunderassessment = isset($_REQUEST['unitsunderassessment'])?$_REQUEST['unitsunderassessment']:''; // Not being used at the moment
		$audit = isset($_REQUEST['audit'])?$_REQUEST['audit']:'';
		$auto_id = isset($_REQUEST['auto_id'])?$_REQUEST['auto_id']:'';
//		$actual_end_date= isset($_REQUEST['actual_end_date'])?$_REQUEST['actual_end_date']:'nodate';
//		$achievement_date= isset($_REQUEST['achievement_date'])?$_REQUEST['achievement_date']:'nodate';
		

	
		// Synchronise dates into ILR
/*		$st = $link->query("SELECT * FROM ilr INNER JOIN contracts on contracts.id = ilr.contract_id where tr_id='$tr_id' AND submission = (SELECT submission FROM lookup_submission_dates WHERE last_submission_date>=CURDATE() ORDER BY last_submission_date LIMIT 1);");
		if($st) 
		{
			while($row = $st->fetch())
			{
				$submission = $row['submission'];
				$tr_id = $row['tr_id'];
				$contract_id = $row['contract_id'];
				$l03 = $row['L03'];
				$ilr = $row['ilr'];
				
				$ilrxml = new SimpleXMLElement($ilr);
				foreach ($ilrxml->main as $item) 
				{
				    
					$qid = str_replace("/","",$qualification_id);
					
					if($item->A09==$qid)
					{
						$item->A27 = $qualification_start_date;	
						$item->A28 = $qualification_end_date;
					}
				}
				foreach ($ilrxml->subaim as $item) 
				{
				    
					$qid = str_replace("/","",$qualification_id);
					
					if($item->A09==$qid)
					{
						$item->A31 = $actual_end_date;
						$item->A40 = $achievement_date;
					}
				}
				
				$blob = substr($ilrxml->asXML(),22);
				$link->query("update ilr set ilr = '$blob' where submission='$submission' and tr_id = $tr_id and contract_id = $contract_id and l03='$l03'");
			}
		}
		
*/		
		
/*		if($actual_end_date=='dd/mm/yyyy' || $actual_end_date=='nodate')
			$actual_end_date = 'NULL';
		else
			$actual_end_date = "'" . Date::toMySQL($actual_end_date) . "'"; 
		
		if($achievement_date=='dd/mm/yyyy' || $achievement_date=='nodate')
			$achievement_date = 'NULL';
		else
			$achievement_date = "'" . Date::toMySQL($achievement_date) . "'";
*/		
		$auditarray = array();

		if($audit!='')
		{
			if(strpos($audit,'|')==false)
			{
				$auditarray[]=$audit;	
			}
			else
			{
				$sub='';
				for($a=0;$a<=strlen($audit);$a++)
				{
					$ch = substr($audit,$a,1);
					if($ch!='|')
					{
						$sub .= $ch;
					}
					else
					{
						$auditarray[] = $sub;
						$sub='';
					}
				}
					$auditarray[] = $sub;
			}
		}

		// Saving Audit Log
		foreach($auditarray as $change)
		{
			$note = new Note();
			$note->subject = "Document changed";
			$note->note = $change;
			$note->is_audit_note = true;
			$note->parent_table = 'student_qualification';
			$note->parent_id = $auto_id;
			$note->firstnames = $_SESSION['user']->firstnames;
			$note->surname = $_SESSION['user']->surname;
			$note->fqn = $_SESSION['user']->getFullyQualifiedName();
			$note->save($link);
		}
		
		
		
		$d1 = $evidences;
		$d2 = '';
		for($i=0;$i<=strlen($d1);$i++)
		{
			if(substr($d1,$i,1)!="\n")
			{
				if(ord(substr($d1,$i,1))==39)
					$d2 .= chr(34);
				else
					$d2 .= substr($d1,$i,1);
			}
		}
		
		$inter_title = addslashes((string)$internaltitle);
		$sql = "update student_qualifications set evidences = '$d2', unitsUnderAssessment = '$percentage', units = '$units' , unitsCompleted = '$unitscompleted', unitsNotStarted = '$unitsnotstarted', unitsBehind = '$unitsbehind', unitsOnTrack = '$unitsontrack' where id = '$qualification_id' and framework_id='$framework_id' and tr_id='$tr_id' and internaltitle='$inter_title'";
		DAO::execute($link, $sql);
        DAO::execute($link, "UPDATE student_qualifications SET units = (LENGTH(evidences) - LENGTH(REPLACE(evidences, 'chosen=\"true\"', ''))) / LENGTH('chosen=\"true\"') where id = '$qualification_id' and framework_id='$framework_id' and tr_id='$tr_id' and internaltitle='$inter_title';");



		TrainingRecord::updateProgressStatistics($link, $tr_id);
			
			
	}
}
?>