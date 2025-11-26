<?php
class save_student_qualification implements IAction
{
	public function execute(PDO $link)
	{
		$qan = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$qan_before_editing = isset($_REQUEST['qan_before_editing'])?$_REQUEST['qan_before_editing']:'';
		$xml = isset($_REQUEST['xml'])?$_REQUEST['xml']:'';
		$fid = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$xml2 = isset($_REQUEST['xml2'])?$_REQUEST['xml2']:'';
		$units = isset($_REQUEST['units'])?$_REQUEST['units']:'';
		$unitsNotStarted = isset($_REQUEST['unitsNotStarted'])?$_REQUEST['unitsNotStarted']:'';
		$unitsBehind = isset($_REQUEST['unitsBehind'])?$_REQUEST['unitsBehind']:'';
		$unitsOnTrack = isset($_REQUEST['unitsOnTrack'])?$_REQUEST['unitsOnTrack']:'';
		$unitsUnderAssessment = isset($_REQUEST['unitsUnderAssessment'])?$_REQUEST['unitsUnderAssessment']:'';
		$unitsCompleted = isset($_REQUEST['unitsCompleted'])?$_REQUEST['unitsCompleted']:'';
		$milestones = isset($_REQUEST['milestones'])?$_REQUEST['milestones']:'';
		$internaltitle= isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
		$audit= isset($_REQUEST['audit'])?$_REQUEST['audit']:'';
		$auto_id= isset($_REQUEST['auto_id'])?$_REQUEST['auto_id']:'';
		$actual_end_date= isset($_REQUEST['actual_end_date'])?$_REQUEST['actual_end_date']:'';
		$achievement_date= isset($_REQUEST['achievement_date'])?$_REQUEST['achievement_date']:'';
		$qualification_start_date= isset($_REQUEST['qualification_start_date'])?$_REQUEST['qualification_start_date']:'';
		$qualification_end_date= isset($_REQUEST['qualification_end_date'])?$_REQUEST['qualification_end_date']:'';
		$proportion= isset($_REQUEST['proportion'])?$_REQUEST['proportion']:'';
		$awarding_body_reg = isset($_REQUEST['awarding_body_reg'])?$_REQUEST['awarding_body_reg']:'';
		$awarding_body_date = isset($_REQUEST['awarding_body_date'])?$_REQUEST['awarding_body_date']:'';
		$awarding_body_batch = isset($_REQUEST['awarding_body_batch'])?$_REQUEST['awarding_body_batch']:'';
		$qualification_proportion = isset($_REQUEST['qualification_proportion'])?$_REQUEST['qualification_proportion']:0;
		$exempt = isset($_REQUEST['exempt'])?$_REQUEST['exempt']:'';
		$pending = isset($_REQUEST['pending'])?$_REQUEST['pending']:'';//if($tr_id == "5082") throw new Exception (json_encode($_REQUEST));
		$marker = isset($_REQUEST['marker'])?$_REQUEST['marker']:'';
		$fs_opt_in = isset($_REQUEST['fs_opt_in'])?$_REQUEST['fs_opt_in']:'';

		if($actual_end_date == 'dd/mm/yyyy')
			$actual_end_date = '';

			
		if($achievement_date == 'dd/mm/yyyy')
			$achievement_date = '';
			
		if($awarding_body_date == 'dd/mm/yyyy')
			$awarding_body_date = '';
			
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
		
		if($xml == '' || $xml=='')
		{
			throw new Exception("Missing or empty argument 'xml'");
		}
		
		// If the QAN has changed, delete the old qualification before
		// writing the new one, otherwise we end up with more than one qualification
		// registered against a course. Allowing for the future possibility of delivering
		// more than one qualification per course is deliberate, but this is unwanted
		// at the moment.
		if( ($qan != $qan_before_editing) && ($qan_before_editing != '') )
		{
			$old_qualification = StudentQualification::loadFromDatabase($link, $qan_before_editing, $fid, $tr_id, $internaltitle); /* @var $old_qualification Qualification */
			$old_qualification->delete($link);
		}

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
				    
					$qid = str_replace("/","",$qan);
					
					if($item->A09==$qid)
					{
						$item->A27 = $qualification_start_date;	
						$item->A28 = $qualification_end_date;
						$item->A31 = $actual_end_date;
						$item->A40 = $achievement_date;
					}
				}
				foreach ($ilrxml->subaim as $item) 
				{
				    
					$qid = str_replace("/","",$qan);
					
					if($item->A09==$qid)
					{
						$item->A27 = $qualification_start_date;	
						$item->A28 = $qualification_end_date;
						$item->A31 = $actual_end_date;
						$item->A40 = $achievement_date;
					}
				}
				
				$blob = substr($ilrxml->asXML(),22);
				DAO::execute($link, "update ilr set ilr = '$blob' where submission='$submission' and tr_id = $tr_id and contract_id = $contract_id and l03='$l03'");
			}
		}
	*/
		
		// POST data submitted by modern browsers will be in UTF-8
		$xml = '<?xml version="1.0" encoding="utf-8"?>' . $xml;
		$qualification = StudentQualification::loadFromXML($xml); 
		$qualification->framework_id = $fid; // Khushnood
		$qualification->internaltitle = $internaltitle;
		$qualification->tr_id = $tr_id; // Khushnood
		$qualification->evidences = $xml2;
		$qualification->actual_end_date = $actual_end_date;
		$qualification->achievement_date = $achievement_date;
		$qualification->start_date = $qualification_start_date;
		$qualification->end_date = $qualification_end_date;
		$qualification->aptitude = $exempt;
		$qualification->pending = $pending;				
		$qualification->marker = $marker;				
		$qualification->fs_opt_in = $fs_opt_in;				
		//$qualification->save($link, $fid, $tr_id);

		$actual_end_date = ($actual_end_date=='')?'null':$actual_end_date = "'" . Date::toMySQL($actual_end_date) . "'";
		$achievement_date = ($achievement_date=='')?'null':$achievement_date = "'" . Date::toMySQL($achievement_date) . "'";
		$start_date = ($qualification_start_date=='')?'null':$qualification_start_date= "'" . Date::toMySQL($qualification_start_date) . "'";
		$end_date = ($qualification_end_date=='')?'null':$qualification_end_date= "'" . Date::toMySQL($qualification_end_date) . "'";
		$awarding_body_date = ($awarding_body_date=='')?'null':$awarding_body_date= "'" . Date::toMySQL($awarding_body_date) . "'";
		
		$sq = StudentQualification::loadFromDatabase($link, $qan, $fid, $tr_id, $internaltitle);
		$sq->evidences = $xml2;
		$sq->units = $units;
		$sq->unitsNotStarted = $unitsNotStarted;
		$sq->unitsBehind = $unitsBehind;
		$sq->unitsOnTrack = $unitsOnTrack;
		
		if($unitsUnderAssessment> $sq->unitsUnderAssessment)
			$sq->unitsUnderAssessment = $unitsUnderAssessment;
		
		$sq->unitsCompleted = $unitsCompleted;
$qualification_proportion = $qualification_proportion == '' ? 0 : $qualification_proportion;
		$sq->units_required = $proportion;
		$sq->proportion = $qualification_proportion;
		$sq->pending = ( $pending == 'true' ) ? 1 : 0;
		$sq->marker = ( $marker == 'true' ) ? 1 : 0;
		$sq->fs_opt_in = $fs_opt_in;				

		DAO::saveObjectToTable($link, 'student_qualifications', $sq);
		
// Updating qualification

// if current percentage is higher than the calculated percentage then do not override
$internaltitle = addslashes((string)$internaltitle);
$p = DAO::getSingleValue($link, "select unitsUnderAssessment from student_qualifications where id='$qan_before_editing' and framework_id = $fid and tr_id = $tr_id and internaltitle = '$internaltitle'");		
if((int)$p>(int)$unitsUnderAssessment)
{
		$sql2 = <<<HEREDOC
update
	student_qualifications
set 
	actual_end_date = $actual_end_date, achievement_date = $achievement_date,
	start_date = $qualification_start_date, end_date = $qualification_end_date, 
	awarding_body_date = $awarding_body_date, awarding_body_reg = '$awarding_body_reg', awarding_body_batch = '$awarding_body_batch',
	unitsNotStarted = $unitsNotStarted, unitsBehind = $unitsBehind, unitsOnTrack = $unitsOnTrack,
	unitsCompleted = $unitsCompleted,
	proportion = $qualification_proportion,
	unitsPercentage = 100,
	aptitude = $exempt
	
where 
		id='$qan_before_editing' and framework_id = $fid and tr_id = $tr_id and internaltitle = '$internaltitle';
HEREDOC;
	DAO::execute($link, $sql2);
	
}
else
{
		$sql2 = <<<HEREDOC
update
	student_qualifications
set 
	actual_end_date = $actual_end_date, achievement_date = $achievement_date,
	start_date = $qualification_start_date, end_date = $qualification_end_date, 
	awarding_body_date = $awarding_body_date, awarding_body_reg = '$awarding_body_reg', awarding_body_batch = '$awarding_body_batch',
	unitsNotStarted = $unitsNotStarted, unitsBehind = $unitsBehind, unitsOnTrack = $unitsOnTrack,
	unitsUnderAssessment = $unitsUnderAssessment, unitsCompleted = $unitsCompleted,
	proportion = $qualification_proportion,
	unitsPercentage = 100,
	aptitude = $exempt
	
where 
		id='$qan_before_editing' and framework_id = $fid and tr_id = $tr_id and internaltitle = '$internaltitle';
HEREDOC;
	DAO::execute($link, $sql2);
	
}

		

				
		$values = '';
		//$xmlmilestones = new SimpleXMLElement($milestones);
		$xmlmilestones = XML::loadSimpleXML($milestones);
		foreach($xmlmilestones->unit as $unit)
		{	
			$values .= '(' . $fid . ',' . '"' . $qualification->id . '"' . ',' . '"' . $qualification->internaltitle . '"' . ',' . '"' . $unit['value'] . '"';

			$index = 1;
			foreach($unit->month as $month)
			{
				if($index<=36)
					$values .= ',' . $month;
				$index++;				
			}

			$values .= ',1,' . $tr_id;
			
			$values .= ',' . $unit['chosen'];
			
			$values .= '),';
		}
		
		$values = substr($values, 0, -1);  

		if($values!='')
		{
			$inter_title = addslashes((string)$qualification->internaltitle);
// Delete existing milestones
		$sql2 = <<<HEREDOC
delete from
	student_milestones
where framework_id = '$fid' and qualification_id = '$qualification->id' and internaltitle = '$inter_title' and tr_id = '$tr_id'
HEREDOC;
		DAO::execute($link, $sql2);


				
// Add new milestones				
		$sql2 = <<<HEREDOC
insert into 
	student_milestones (framework_id, qualification_id, internaltitle, unit_id, month_1, month_2, month_3, month_4, month_5, month_6, month_7, month_8, month_9, month_10, month_11, month_12, month_13, month_14, month_15, month_16, month_17, month_18, month_19, month_20, month_21, month_22, month_23, month_24, month_25, month_26, month_27, month_28, month_29, month_30, month_31, month_32, month_33, month_34, month_35, month_36, id, tr_id, chosen)
values
	$values;	
HEREDOC;
		DAO::execute($link, $sql2);
		}




		TrainingRecord::updateProgressStatistics($link, $tr_id);

	http_redirect($_SESSION['bc']->getPrevious());
				
		
	}
	
	
	private function checkPermissions(PDO $link, Course $c_vo)
	{
		if($_SESSION['role'] == 'admin')
		{
			return true;
		}
		elseif($_SESSION['org']->org_type_id == ORG_PROVIDER)
		{
			$acl = CourseACL::loadFromDatabase($link, $c_vo->id);
			$is_employee = $_SESSION['org']->id == $c_vo->organisations_id;
			$is_local_admin = in_array('ladmin', $_SESSION['privileges']);
			$listed_in_course_acl = in_array($_SESSION['username'], $acl->usernames);
			
			return $is_employee && $is_local_admin;
		}
		elseif($_SESSION['org']->org_type_id == ORG_SCHOOL)
		{
			return false;
		}
		else
		{
			return false;
		}
	}
}
?>