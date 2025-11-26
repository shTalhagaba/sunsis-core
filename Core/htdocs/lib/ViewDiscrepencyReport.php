<?php
class ViewDiscrepencyReport extends View
{

	public static function getInstance($contract_id, $submission)
	{
		$key = 'view_'.__CLASS__.$contract_id.$submission;
		
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT 
	ilr.*, contracts.*
FROM 
	ilr 
	LEFT JOIN contracts on contracts.id = ilr.contract_id
WHERE 
	contract_id = $contract_id AND submission = '$submission'
	order by L03
HEREDOC;
			$view = $_SESSION[$key] = new ViewDiscrepencyReport();
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
			$filters = array();
			$ilr_fields = $this->getFilterValue('filter_ilr_fields');
			if($ilr_fields!='')
			{
				$ilr_fields = explode(",", $ilr_fields);
				foreach($ilr_fields as $mn)
				{
					if(!strpos($mn,"="))
						$data .= "<th>" . $mn . "</th>";
					else
						$filters[] = explode("=",$mn);
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
					$ilr = Ilr2010::loadFromXML($row['ilr']);
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
					
					$subaims = -2;
					$ilrtemp = $row['ilr'];
					//$pageDom = new DomDocument();
					$ilrtemp = str_replace("&","&amp;",$ilrtemp);
					//$pageDom->loadXML($ilrtemp);
					$pageDom = XML::loadXmlDom($ilrtemp);
					$e = $pageDom->getElementsByTagName('A09');
					foreach($e as $node)
					{
						$subaims++;	
					}				
				
			
				for($sa=0;$sa<=$subaims;$sa++)
				{

					if(!empty($filters))
					{
						foreach($filters as $filter)
						{
							$f = $filter[0];
							$fv = $ilr->aims[$sa]->$f;
							if(trim($ilr->aims[$sa]->$f) == $filter[1])
							{
								echo '<tr>';
								if($ilr->programmeaim->A10=="70" || ($ilr->programmeaim->A15!="99" && $ilr->programmeaim->A15!="" && $ilr->programmeaim->A15!="0"))
									$styles='';
								else
									if($sa==0)
										$styles = 'style="border-top: thick solid"';
									else
										$styles = '';
										
								if($sa==0 && $row['contract_year']<2008)
										$styles = 'style="border-top: thick solid"';

		
								if($row['funding_body']==1)					
									echo '<td align="center"' . $styles . '"><a href="do.php?_action=edit_lr_ilr'. $contract_year . '&submission=' . $submission . '&contract_id=' . $contract_id . '&tr_id=' . $tr_id . '&L03=' . $l03 . '">' .  ($ilr->learnerinformation->L03) . '</a></td>';
								else
									echo '<td align="center"' . $styles . '"><a href="do.php?_action=edit_ilr'. $contract_year . '&submission=' . $submission . '&contract_id=' . $contract_id . '&tr_id=' . $tr_id . '&L03=' . $l03 . '">' .  ($ilr->learnerinformation->L03) . '</a></td>';
								
								//window.location.href=('do.php?_action=edit_ilr'+contract_year+'&submission=' + values[0] + '&contract_id=' + values[1] + '&tr_id=' + values[2] + '&L03=' + values[4]);
								//echo '<td> <a href="do.php?_action=read_contract&id=' . $row['cid'] . '">' . $row['title'] . '</a></td>';			
								echo '<td align="center"' . $styles . '"><a href="do.php?_action=read_training_record&id=' . $tr_id . '">' .  HTML::cell($ilr->learnerinformation->L09) . '</a></td>';
								
								//echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->learnerinformation->L09) . '</td>';
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
								$sql = "SELECT ((DATE_FORMAT('$start_date','%Y') - DATE_FORMAT('$dob','%Y')) - (DATE_FORMAT('$start_date','00-%m-%d') < DATE_FORMAT('$dob','00-%m-%d')));";
								$age = DAO::getSingleValue($link, $sql);
								
								$a09 = $ilr->aims[$sa]->A09;
								$qual_title = DAO::getSingleValue($link, "select internaltitle from qualifications where replace(id,'/','') = '$a09'");

								echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->aims[$sa]->A09) . ' ' . $qual_title . '</td>';
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
										if(!strpos($mn,"="))
										{
											if(substr($mn,0,1)=='L')
												echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->learnerinformation->$mn) . '</td>';
											else
												echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->aims[$sa]->$mn) . '</td>';
										}
									}
								}		
								echo '</tr>';
							}
						}
					}
					else
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
								
						// Check if this exist at training record level
						$a09 = $ilr->aims[$sa]->A09;
						$count = DAO::getSingleValue($link, "select count(*) from student_qualifications where tr_id = '$tr_id' and replace(id,'/','') = '$a09' and aptitude != 1");

						if($count==0)
						{
							if($row['funding_body']==1)
								echo '<td align="center"' . $styles . '"><a href="do.php?_action=edit_lr_ilr'. $contract_year . '&submission=' . $submission . '&contract_id=' . $contract_id . '&tr_id=' . $tr_id . '&L03=' . $l03 . '">' .  ($ilr->learnerinformation->L03) . '</a></td>';
							else
								echo '<td align="center"' . $styles . '"><a href="do.php?_action=edit_ilr'. $contract_year . '&submission=' . $submission . '&contract_id=' . $contract_id . '&tr_id=' . $tr_id . '&L03=' . $l03 . '">' .  ($ilr->learnerinformation->L03) . '</a></td>';
							
							//window.location.href=('do.php?_action=edit_ilr'+contract_year+'&submission=' + values[0] + '&contract_id=' + values[1] + '&tr_id=' + values[2] + '&L03=' + values[4]);
							//echo '<td> <a href="do.php?_action=read_contract&id=' . $row['cid'] . '">' . $row['title'] . '</a></td>';			
							echo '<td align="center"' . $styles . '"><a href="do.php?_action=read_training_record&id=' . $tr_id . '">' .  HTML::cell($ilr->learnerinformation->L09) . '</a></td>';
							
							//echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->learnerinformation->L09) . '</td>';
							echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->learnerinformation->L10) . '</td>';
							
							if($ilr->aims[$sa]->A15=="2" || $ilr->aims[$sa]->A15=="02")
								$programme_type = "Advanced Apprenticeship";
							elseif($ilr->aims[$sa]->A15=="3" || $ilr->aims[$sa]->A15=="03")
								$programme_type = "Apprenticeship";
							elseif($ilr->aims[$sa]->A15=="99")
								$programme_type = "Adult NVQ";
							else
								$programme_type = "Unknown";
							
							if($ilr->learnerinformation->L11!='00/00/0000')
							{
								$dob = Date::toMySQL($ilr->learnerinformation->L11);
								$start_date = Date::toMySQL($ilr->aims[$sa]->A27);
								$sql = "SELECT ((DATE_FORMAT('$start_date','%Y') - DATE_FORMAT('$dob','%Y')) - (DATE_FORMAT('$start_date','00-%m-%d') < DATE_FORMAT('$dob','00-%m-%d')));";
								$age = DAO::getSingleValue($link, $sql);
							}
							else
							{
								$age = '';
							}
									
							$qual_title = DAO::getSingleValue($link, "select internaltitle from qualifications where replace(id,'/','') = '$a09'");
							
							$how_many = DAO::getSingleValue($link, "select count(*) from student_qualifications where tr_id = '$tr_id' and replace(id,'/','') = '$a09' and aptitude = 0");
							if($how_many==0)
								echo '<td align="center"' . $styles . ' style="color: red"' . '">' . HTML::cell($ilr->aims[$sa]->A09) . ' ' . $qual_title . '</td>';
							else 
								echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->aims[$sa]->A09) . ' ' . $qual_title . '</td>';
							
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
									if(!strpos($mn,"="))
									{
										if(substr($mn,0,1)=='L')
											echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->learnerinformation->$mn) . '</td>';
										else
											echo '<td align="center"' . $styles . '">' . HTML::cell($ilr->aims[$sa]->$mn) . '</td>';
									}
								}
							}		
							echo '</tr>';
								
						}
						
					}
				}
			}
			echo '</tbody></table></div align="center">';
			//echo $this->getViewNavigator();
			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

			// Checking the other way round
			$sql = <<<HEREDOC
SELECT 
	tr.*, student_qualifications.id as qual_id, 
	student_qualifications.end_date as ed,
	student_qualifications.actual_end_date,
	student_qualifications.achievement_date
FROM 
	tr 
	inner JOIN student_qualifications on student_qualifications.tr_id = tr.id
WHERE 
	contract_id = $contract_id
	and aptitude != 1
	order by L03
HEREDOC;

		$st = $link->query($sql);
		if($st) 
		{
			echo '<h3> Qualifications exists at Training Record Level but not in ILR </h3>'; 
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
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
	</tr>
	</thead>
HEREDOC;
			
			echo '<tbody>';
			
			while($row = $st->fetch())
			{
				$qual_id = $row['qual_id'];
				$tr_id = $row['id'];
				$count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ilr WHERE tr_id = '$tr_id' AND contract_id = '$contract_id' AND LOCATE(REPLACE('$qual_id','/',''),ilr)>0");

				if($count==0)
				{
					echo '<tr><td>' . $row['l03'] . '</td>';
					echo '<td>' . $row['surname'];
					echo '<td>' . $row['firstnames'];
					echo '<td>' . $qual_id . '</td>';
					echo '<td>' . Date::toShort($row['start_date']) . '</td>';
					echo '<td>' . Date::toShort($row['ed']) . '</td>';
					echo '<td>' . Date::toShort($row['actual_end_date']) . '</td>';
					echo '<td>' . Date::toShort($row['achievement_date']) . '</td>';
				}
			}
			
			echo '</tbody></table></div>';
		}
		
	}
}
?>