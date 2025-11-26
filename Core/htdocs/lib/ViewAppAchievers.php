<?php
class ViewAppAchievers extends View
{

	public static function getInstance($link)
	{
		$key = 'view_'.__CLASS__;
		
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT 
	* 
FROM 
	ilr 
	LEFT JOIN contracts on contracts.id = ilr.contract_id
	order by L03
HEREDOC;
			$view = $_SESSION[$key] = new ViewAppAchievers();
			$view->setSQL($sql);
			
			// Dealer Name Filter 	
//			$f = new TextboxViewFilter('filter_ilr_fields', "where true", null, "size=100");
//			$f->setDescriptionFormat("ILR Fields: %s");
//			$view->addFilter($f);
			
			$options = "SELECT id, title, null, CONCAT('WHERE ilr.contract_id=',id) FROM contracts";
			$f = new DropDownViewFilter('filter_contract', $options, null, true);
			$f->setDescriptionFormat("Contract: %s");
			$view->addFilter($f);
			
			$cyear = DAO::getSingleValue($link, "SELECT contract_year FROM contracts WHERE CURDATE()>= start_date AND CURDATE()<= end_date LIMIT 1,1;");
			
			$options = "SELECT DISTINCT contract_year, contract_year, null, CONCAT('WHERE contracts.contract_year=',contract_year) FROM contracts order by contract_year desc";
			$f = new DropDownViewFilter('filter_contract_year', $options, $cyear, true);
			$f->setDescriptionFormat("Contract Year: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'Achievers', null, 'WHERE true'),
				2=>array(2, 'Timely Achievers', null, 'WHERE true'),
				3=>array(3, 'Actual', null, 'WHERE true'),
				4=>array(4, 'Expected', null, 'WHERE true'));
			$f = new DropDownViewFilter('filter_report', $options, 0, false);
			$f->setDescriptionFormat("Report: %s");
			$view->addFilter($f);
			
			/*
			 * re: Updated to use lookup_programme_type table #21814
			 */
			$options = "SELECT code, description, null, 'WHERE TRUE' from lookup_programme_type order by description asc";
			$f = new DropDownViewFilter('filter_programme_type', $options, null, true);
			$f->setDescriptionFormat("Programme Type: %s");
			$view->addFilter($f);

			$submission = DAO::getSingleValue($link, "SELECT central.lookup_submission_dates.submission FROM central.lookup_submission_dates WHERE central.lookup_submission_dates.start_submission_date < CURDATE() AND central.lookup_submission_dates.last_submission_date > CURDATE();");
			$options = "SELECT DISTINCT submission, submission, null, CONCAT('WHERE ilr.submission=',char(39),submission,char(39)) FROM ilr order by submission";
			$f = new DropDownViewFilter('filter_submission', $options, $submission, true);
			$f->setDescriptionFormat("Submission: %s");
			$view->addFilter($f);
			
			
/*
			// Add view filters
			$options = array(
			0=>array(20,20,null,null),
			1=>array(50,50,null,null),
			2=>array(100,100,null,null),
			3=>array(200,200,null,null),
			4=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 0, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			$options = array(
			0=>array(1, 'Assessor', null, 'ORDER BY assessor, group_code, employer, learner_name, last_review_date'),
			1=>array(2, 'L03', null, 'ORDER BY l03'),
			2=>array(3, 'Leaner', null, 'ORDER BY learner_name'));

			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);
			
			// Date filters	
			$dateInfo = getdate();
			$weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
			$timestamp = time()  - ((60*60*24) * $weekday);
			
			// Rewind by a further 1 week
			$timestamp = $timestamp - ((60*60*24) * 7);
					
			// Start Date Filter
			$format = "WHERE DATE_ADD(IF(assessment_date_subquery.assessment_date IS NOT NULL,assessment_date_subquery.assessment_date, tr.start_date), INTERVAL contracts.frequency WEEK) >= '%s'";
			$f = new DateViewFilter('start_date', $format, '');
			$f->setDescriptionFormat("From: %s");
			$view->addFilter($f);
	
			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));
			
			$format = "WHERE DATE_ADD(IF(assessment_date_subquery.assessment_date IS NOT NULL,assessment_date_subquery.assessment_date, tr.start_date), INTERVAL contracts.frequency WEEK) <= '%s'";
			$f = new DateViewFilter('end_date', $format, '');
			$f->setDescriptionFormat("To: %s");
			$view->addFilter($f);	
			
			$options = "SELECT username, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',char(39),username,char(39),' or tr.assessor=' , char(39),username, char(39)) FROM users where type=3";
			$f = new DropDownViewFilter('filter_assessor', $options, null, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);
			
			$options = array(
				0=>array(0, 'All reviews', null,null),
				1=>array(1, 'Future reviews', null, 'WHERE DATE_ADD(IF(assessment_date_subquery.assessment_date IS NOT NULL,assessment_date_subquery.assessment_date, tr.start_date), INTERVAL contracts.frequency WEEK) > CURRENT_DATE'),
				2=>array(2, 'Missed reviews', null, 'WHERE DATE_ADD(IF(assessment_date_subquery.assessment_date IS NOT NULL,assessment_date_subquery.assessment_date, tr.start_date), INTERVAL contracts.frequency WEEK) < CURRENT_DATE'));
				
			$f = new DropDownViewFilter('filter_assessor_status', $options, null, false);
			$f->setDescriptionFormat("Reviews: %s");
			$view->addFilter($f);
*/			
			
		}

		return $_SESSION[$key];
	}
	
	
	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st) 
		{
			//echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			$data = '';
			$report = $this->getFilterValue('filter_report');
			$programme_type = $this->getFilterValue('filter_programme_type');
			$contract_year = $this->getFilterValue('filter_contract_year');
			
			$contract_start_date = DAO::getSingleValue($link, "select start_date from contracts where contract_year = $contract_year limit 1,1");
			$contract_end_date = DAO::getSingleValue($link, "select end_date from contracts where contract_year = $contract_year limit 1,1");
			$contract_start_date = new Date($contract_start_date);
			$contract_end_date = new Date($contract_end_date);
			$achieved = false;
			$leaver = false;
			
			echo <<<HEREDOC
	<thead>
	<tr>
		<th>#</th>
		<th>Reference</th>
		<th>Name</th>
		<th>Main Aim</th>
		<th>Start Date</th>
		<th>Projected End Date</th>
		<th>Actual End Date</th>
	</tr>
	</thead>
HEREDOC;


			echo '<tbody>';
			$n = 0;
			$serial = 0;
			$l03 = '';
			$total_leavers = 0;
			$total_timely_leavers = 0;
			$total_achievers = 0;
			$total_timely_achievers = 0;
			
			while($row = $st->fetch())
			{
				try
				{
					$ilr = Ilr2009::loadFromXML($row['ilr']);
				}
				catch(Exception $e)
				{
					throw new Exception($row['ilr']);
				}

				$contract_year = $row['contract_year'];
				$tr_id = $row['tr_id'];
				$submission = $row['submission'];
				$l03 = $row['L03'];
				$contract_id = $row['contract_id'];
			
				// Is it achieved?
				$a35 = 1;
				$app = false;
				if($ilr->programmeaim->A10=="70" || ($ilr->programmeaim->A15!="99" && $ilr->programmeaim->A15!=""))
				{
					$a35 = $ilr->programmeaim->A35;
					$a28 = $ilr->programmeaim->A28;
					$a31 = $ilr->programmeaim->A31;
					
					if($ilr->programmeaim->A15!="99" && $ilr->programmeaim->A15!="")
						$app = true;
					else
						$app = false;
				}
				else
					for($sa=0;$sa<=(int)$ilr->learnerinformation->subaims;$sa++)
					{
						$a35 = ($ilr->aims[$sa]->A35!='1')?$ilr->aims[$sa]->A35:$a35;		
						$a28 = $ilr->aims[$sa]->A28;
						$a31 = $ilr->aims[$sa]->A31;
					}									

				if($a35=='1')
				{
					$a31d = new Date($a31);
					if($a31d->getDate()>=$contract_start_date->getDate() && $a31d->getDate()<=$contract_end_date->getDate())
						$achieved = true;		
				}
				else
					$achieved = false;

				if($a31!='' && $a31!='00000000' & $a31!='dd/mm/yyyy')
				{	
					$a31d = new Date($a31);
					if($a31d->getDate()>=$contract_start_date->getDate() && $a31d->getDate()<=$contract_end_date->getDate())
					{
						$leaver = true;
					}	
				}
				else
				{
					$leaver = false;
				}

				
				$a28d = new Date($a28);
				if($a28d->getDate()>= $contract_start_date->getDate() && $a28d->getDate()<=$contract_end_date->getDate())
					$early_leaver = true;
				else
					$early_leaver = false;					
				
			//	if($l03 = '025201188451')
			//		pre($ilr);	
			//	$l03 = $ilr->learnerinformation->L03;
					
				if($achieved)
				{
					$a28 = Date::toMySQL($a28);
					$a31 = Date::toMySQL($a31);					
					$days = DAO::getSingleValue($link, "SELECT '$a31' <= DATE_ADD('$a28', INTERVAL 90 DAY)");	
					$leaver = true;
				}
				else
				{
					$days = 0;					
				}
				
				
				
		
				// Decide display or not	
				if($report==0) // Show All
					$display1 = true;
				elseif($report == 1 && $achieved) // Achieved
					$display1 = true;
				elseif($report == 2 && $days == 1) // Timely Achiever
					$display1 = true;
				elseif($report == 3 && $leaver) // Leaver
					$display1 = true;
				elseif($report == 4 && $early_leaver) // Early Leaver
					$display1 = true;
				else
					$display1 = false;

				if($programme_type == 0)
					$display2 = true;
				elseif($programme_type == 1 && $app)
					$display2 = true;
				elseif($programme_type == 2 && !$app)
					$display2 = true;
				else
					$display2 = false;

				//Variable setting 
				if($achieved)
					$total_achievers++;
				if($days == 1)
					$total_timely_achievers++;
				if($leaver)
					$total_leavers++;
				if($early_leaver)
					$total_timely_leavers++;

					
					
    			// Return difference 
				if($display1 && $display2)
				{		
					$serial++;
					// Learner Information	
					echo '<tr>';
					echo '<td align="center">' . $serial . '</td>';
					echo '<td align="center"><a href="do.php?_action=edit_ilr'. $contract_year . '&submission=' . $submission . '&contract_id=' . $contract_id . '&tr_id=' . $tr_id . '&L03=' . $l03 . '">' .  ($ilr->learnerinformation->L03) . '</a></td>';
					echo '<td align="center">' . HTML::cell($ilr->learnerinformation->L10 . " " . $ilr->learnerinformation->L09) . '</td>';
					echo '<td align="center">' . HTML::cell($ilr->aims[0]->A09) . '</td>';
					
					// check if its apprenticeship or ESF
					if($ilr->programmeaim->A10=="70" || ($ilr->programmeaim->A15!="99" && $ilr->programmeaim->A15!=""))
					{
						echo '<td align="center">' . HTML::cell($ilr->programmeaim->A27) . '</td>';
						echo '<td align="center">' . HTML::cell($ilr->programmeaim->A28) . '</td>';
						echo '<td align="center">' . HTML::cell($ilr->programmeaim->A31) . '</td>';
					}						
					else // This is a TtG so take main aim dates
					{
						echo '<td align="center">' . HTML::cell($ilr->aims[0]->A27) . '</td>';
						echo '<td align="center">' . HTML::cell($ilr->aims[0]->A28) . '</td>';
						echo '<td align="center">' . HTML::cell($ilr->aims[0]->A31) . '</td>';
					}
				}
			}
			echo '</tbody></table></div align="center">';
			//echo $this->getViewNavigator();
			
			$this->total_achievers = $total_achievers;
			$this->total_timely_achievers = $total_timely_achievers;
			$this->total_leavers = $total_leavers;
			$this->total_timely_leavers = $total_timely_leavers;
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
	public function dateDiff($dformat, $endDate, $beginDate)
	{
		$date_parts1=explode($dformat, $beginDate);
		$date_parts2=explode($dformat, $endDate);
		$start_date=gregoriantojd($date_parts1[0], $date_parts1[1], $date_parts1[2]);
		$end_date=gregoriantojd($date_parts2[0], $date_parts2[1], $date_parts2[2]);
		return $end_date - $start_date;
	}	

	public $total_achievers = 0;
	public $total_timely_achievers = 0;
	public $total_leavers = 0;
	public $total_timely_leavers = 0;
}
?>