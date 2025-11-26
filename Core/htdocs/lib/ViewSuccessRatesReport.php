<?php
class ViewSuccessRatesReport extends View
{

	public static function getInstance($expected, $actual, $programme_type, $table)
	{
		$key = 'view_'.__CLASS__.$expected.$actual.$programme_type.$table;
		
		if($expected == '')
			$expectedwhere = " success_rates.expected IS NULL";
		else
			$expectedwhere = " success_rates.expected = '$expected'";
			
		if($actual == '')
			$actualwhere = " and success_rates.actual IS NULL";
		else
			$actualwhere = " and success_rates.actual = '$actual'";
			
		if($table=='cohort')
			$where = '';
		elseif($table=='overall')
			$where = " and success_rates.p_prog_status=1";
		elseif($table=='timely')
			$where = " and success_rates.p_prog_status=1 and DATEDIFF(success_rates.actual_end_date, success_rates.planned_end_date)<=90";
			
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT 
	* 
FROM 
	ilr 
	LEFT JOIN contracts on contracts.id = ilr.contract_id
	LEFT JOIN success_rates on success_rates.tr_id = ilr.tr_id and success_rates.l03 = ilr.L03 and success_rates.contract_id = ilr.contract_id and success_rates.submission = ilr.submission
where
	$expectedwhere $actualwhere and success_rates.programme_type = '$programme_type' $where	
order by ilr.L03
HEREDOC;
			$view = $_SESSION[$key] = new ViewSuccessRatesReport();
			$view->setSQL($sql);
			
			// Dealer Name Filter 	
			$f = new TextboxViewFilter('filter_ilr_fields', "where true", null, "size=100");
			$f->setDescriptionFormat("ILR Fields: %s");
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
			$ilr_fields = $this->getFilterValue('filter_ilr_fields');
			if($ilr_fields!='')
			{
				$ilr_fields = explode(",", $ilr_fields);
				foreach($ilr_fields as $mn)
				{
					$data .= "<th>" . $mn . "</th>";
				}
			}
			
			echo <<<HEREDOC
	<thead>
	<tr>
		<th>L03</th>
		<th>L09</th>
		<th>L10</th>
		<th>A09</th>
		<th>A27</th>
		<th>A28</th>
		<th>A31</th>
		<th>A40</th>
		<th>Programme Type</th>
		<th>Age</th>
		<th>A14</th>
		$data
	</tr>
	</thead>
HEREDOC;


			echo '<tbody>';
			$n = 0;
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
				
				// Programme Aim Starts
				if($ilr->programmeaim->A10=="70" || ($ilr->programmeaim->A15!="99" && $ilr->programmeaim->A15!="" && $row['contract_year']>=2008))
				{
					echo '<tr>';
					$styles = 'style="border-top: thick solid"';
				//	$styles = '';
				//	echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->learnerinformation->L03) . '</td>';
					echo '<td align="center"' . $styles . '"><a href="do.php?_action=edit_ilr'. $contract_year . '&submission=' . $submission . '&contract_id=' . $contract_id . '&tr_id=' . $tr_id . '&L03=' . $l03 . '">' .  ($ilr->learnerinformation->L03) . '</a></td>';
					echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->learnerinformation->L09) . '</td>';
					echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->learnerinformation->L10) . '</td>';
					
					if($ilr->programmeaim->A15=="2" || $ilr->programmeaim->A15=="02")
						$programme_type = "Advanced Apprenticeship";
					elseif($ilr->programmeaim->A15=="3" || $ilr->programmeaim->A15=="03")
						$programme_type = "Apprenticeship";
					elseif($ilr->programmeaim->A15=="99")
						$programme_type = "Adult NVQ";
					else
						$programme_type = "Unknown";
					
					$dob = Date::toMySQL($ilr->learnerinformation->L11);
					$start_date = Date::toMySQL($ilr->programmeaim->A27);
					$sql = "select truncate(DATEDIFF('$start_date', '$dob')/365,0)";
					$age = DAO::getSingleValue($link, $sql);
					
					if($ilr->programmeaim->A10=='70')
						echo '<td align="center"' . $styles . '">' . HTML::cell("ZESF0001") . '</td>';
					else
						echo '<td align="center"' . $styles . '">' . HTML::cell("ZPROG001") . '</td>';
					
					echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->programmeaim->A27) . '</td>';
					echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->programmeaim->A28) . '</td>';
					echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->programmeaim->A31) . '</td>';
					echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->programmeaim->A40) . '</td>';
					echo '<td align="center"' . $styles . '">' . HTML::cell($programme_type) . '</td>';
					echo '<td align="center"' . $styles . '">' . HTML::cell($age."+") . '</td>';
					echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->programmeaim->A14) . '</td>';

					$ilr_fields = str_replace(" ", "", $ilr_fields);
					if($ilr_fields!='')
					{
						foreach($ilr_fields as $mn)
						{
							if(substr($mn,0,1)=='L')
								echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->learnerinformation->$mn) . '</td>';
							else
								echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->programmeaim->$mn) . '</td>';
						}
					}		
					echo '</tr>';
					
				// Programme aim finishes				
				}			
					
				
				for($sa=0;$sa<=(int)$ilr->learnerinformation->subaims;$sa++)
				{
					
					echo '<tr>';
					
					if($ilr->programmeaim->A10=="70" || ($ilr->programmeaim->A15!="99" && $ilr->programmeaim->A15!=""))
						$styles='';
					else
						if($sa==0)
							$styles = 'style="border-top: thick solid"';
						else
							$styles = '';

							
					if($sa==0 && $row['contract_year']<2008)
							$styles = 'style="border-top: thick solid"';
					

					echo '<td align="center"' . $styles . '"><a href="do.php?_action=edit_ilr'. $contract_year . '&submission=' . $submission . '&contract_id=' . $contract_id . '&tr_id=' . $tr_id . '&L03=' . $l03 . '">' .  ($ilr->learnerinformation->L03) . '</a></td>';

					//window.location.href=('do.php?_action=edit_ilr'+contract_year+'&submission=' + values[0] + '&contract_id=' + values[1] + '&tr_id=' + values[2] + '&L03=' + values[4]);
					//echo '<td> <a href="do.php?_action=read_contract&id=' . $row['cid'] . '">' . $row['title'] . '</a></td>';			
					
					echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->learnerinformation->L09) . '</td>';
					echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->learnerinformation->L10) . '</td>';
					
					if($ilr->aims[$sa]->A15=="2" || $ilr->aims[$sa]->A15=="02")
						$programme_type = "Advanced Apprenticeship";
					elseif($ilr->aims[$sa]->A15=="3" || $ilr->aims[$sa]->A15=="03")
						$programme_type = "Apprenticeship";
					elseif($ilr->aims[$sa]->A15=="99")
						$programme_type = "Adult NVQ";
					else
						$programme_type = "Unknown";
					
					$dob = Date::toMySQL($ilr->learnerinformation->L11);
					$start_date = Date::toMySQL($ilr->aims[$sa]->A27);
					$sql = "select truncate(DATEDIFF('$start_date', '$dob')/365,0)";
					$age = DAO::getSingleValue($link, $sql);
					
					echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->aims[$sa]->A09) . '</td>';
					echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->aims[$sa]->A27) . '</td>';
					echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->aims[$sa]->A28) . '</td>';
					echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->aims[$sa]->A31) . '</td>';
					echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->aims[$sa]->A40) . '</td>';
					echo '<td align="center"' . $styles . '">' . HTML::cell($programme_type) . '</td>';
					echo '<td align="center"' . $styles . '">' . HTML::cell($age."+") . '</td>';
					echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->aims[$sa]->A14) . '</td>';

					$ilr_fields = str_replace(" ", "", $ilr_fields);
					if($ilr_fields!='')
					{
						foreach($ilr_fields as $mn)
						{
							if(substr($mn,0,1)=='L')
								echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->learnerinformation->$mn) . '</td>';
							else
								echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->aims[$sa]->$mn) . '</td>';
						}
					}		
					echo '</tr>';
				}
			}
			echo '</tbody></table></div align="center">';
			//echo $this->getViewNavigator();
			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
}
?>