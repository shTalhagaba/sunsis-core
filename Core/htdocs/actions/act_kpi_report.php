<?php

class kpi_report implements IAction
{
	function execute(PDO $link)
	{
		
		
		// 1) Get our relevent classes
		require_once('./lib/KPI_classes.php');
		
		// 2) Establish the type of report we are viewing
		if(isset($_REQUEST['type']))
		{
			// get output format
			if(!isset($_REQUEST['output']))
			{
				$_REQUEST['output'] = 'HTML';
			}
			
			// get provider
			$query = "SELECT * FROM ilr LIMIT 1;";
			$st = $link->query($query);
			$row = $st->fetch();	
			$provider = $row['L01'];
			
			switch($_REQUEST['type'])
			{
				// ############################################################
				// EARLY LEAVERS REPORT
				// ############################################################
				case 'earlyleavers':
					
					$title = 'Train to Gain Qualification Success Rate Data';
					
					$reportRows = new EarlyLeaversReportCollection;
					$totalRows = $reportRows->populate($link, $provider, $_REQUEST['submission']);
					
					$qualifications = new QualificationsLookupCollection;
					$qualifications->populate($link, $reportRows->getUniqueLARS());
					
					//pre($reportRows);
					
					$dataSet = array();
					$leaversFilter = new ComparisonFilter('A34', 3);
					$t2gFilter = new ComparisonFilter('A18', array(22,23));
					$sixWeeksFilter = new DateDifferenceWeeksFilter('A27', 'A31', 6);
					$chainFilter1 = new ChainFilter(array($leaversFilter, $t2gFilter));
					$chainFilter2 = new ChainFilter(array($leaversFilter, $t2gFilter, $sixWeeksFilter));
					foreach($reportRows->getData() AS $LAR => $years)
					{
						ksort($years, SORT_NUMERIC);
						foreach($years AS $endYear => $lookup)
						{
							
							$info = $qualifications->get($LAR);
							$dataSet[] = array(
								(empty($info['mainarea']) ? 'n/a' : $info['mainarea'])
								,(empty($info['subarea']) ? 'n/a' : $info['subarea'])
								,$info['title']
								,$endYear
								,clean_lar($info['id'])
								,$reportRows->filter($t2gFilter)->size($LAR, $endYear)
								,$reportRows->filter($chainFilter1)->size($LAR, $endYear)
								,$reportRows->filter($chainFilter2)->size($LAR, $endYear)
							);
						}
					}										
					
					$columns = array(
						'Area of Learning (AOL)'
						,'Contributory of AOL'
						,'Qualification Name'
						,'Planned end year'
						,'LAR'
						,'Total Learners'
						,'Total Leavers'
						,'Left in the first 6 weeks'
					);
					$data = new DataMatrix($columns, $dataSet, false);
					$data->addTotalColumns(array('Total Learners', 'Total Leavers', 'Left in the first 6 weeks'));
					$dataHTML = $data->to($_REQUEST['output']);
					break;
				
				// ############################################################
				// GENDER REPORT
				// ############################################################					
				case 'gender':
					
					$title = 'Number of learners by area of learning, gender and programme';

					// 1) Build up a collection of learners and their qualifications
					$learnersObj = new LearnerQualificationCollection($link, $provider, $_REQUEST['submission']);
					$learners = $learnersObj->build();

					//pre($learners);
					// 2) Find out all information we can about each qualification learners do
					$qualifications = new QualificationsLookupCollection;
					$qualifications->populate($link, array_unique($learnersObj->getLars()));
					
					// 3) Rebuild this into a nice data structure whereby we have an array indexed by area, with all learners who participate in that area
					$reportRows = new AreaReportCollection;
					$reportRows->populate($link, $learners, $qualifications);
					$dataSet = array();

					foreach($reportRows->getData() AS $area => $node)
					{
						$dataSet[] = array(
							'Area of Learning (AOL)' => $area
							,'Male' => $reportRows->filter(new ComparisonFilter('L13', 'M'))->size($area)
							,'Female' => $reportRows->filter(new ComparisonFilter('L13', 'F'))->size($area)
							,'Total' => $reportRows->size($area)
						);
					}					

					pre($dataSet);

					$data = new DataMatrix(array_keys($dataSet[0]), $dataSet);
					$data->addTotalColumns(array('Male', 'Female', 'Total'));
					$dataHTML = $data->to($_REQUEST['output']);					
					
				break;
					
				// ############################################################
				// SKIN COLOUR REPORT
				// ############################################################						
				case 'skin':
					
					$title = 'Number of learners by area, nationality and programme';
					
					// 1) Build up a collection of learners and their qualifications
					$learnersObj = new LearnerQualificationCollection($link, $provider, $_REQUEST['submission']);
					$learners = $learnersObj->build();
					
					// 2) Find out all information we can about each qualification learners do
					$qualifications = new QualificationsLookupCollection;
					$qualifications->populate($link, array_unique($learnersObj->getLars()));
					
					// 3) Rebuild this into a nice data structure whereby we have an array indexed by area, with all learners who participate in that area
					$reportRows = new AreaReportCollection;
					$reportRows->populate($link, $learners, $qualifications);
					
					$dataSet = array();
					foreach($reportRows->getData() AS $area => $node)
					{
						$dataSet[] = array(
							'Area of Learning (AOL)' => $area
							,'White' => $reportRows->filter(new ComparisonFilter('L12', array(23,24,25,31,32,33,34)))->size($area)
							,'Non-White' => $reportRows->filter(new ComparisonFilter('L12', array(23,24,25,31,32,33,34), false))->size($area)
							,'Total' => $reportRows->size($area)
						);
					}					
					
					$data = new DataMatrix(array_keys($dataSet[0]), $dataSet);
					$data->addTotalColumns(array('White', 'Non-White', 'Total'));
					$dataHTML = $data->to($_REQUEST['output']);					
					
					break;

				// ############################################################
				// RACE REPORT
				// ############################################################						
				case 'race':
					
					$title = 'Number of learners by area of learning and ethnicity';

					// 1) Build up a collection of learners and their qualifications
					$learnersObj = new LearnerQualificationCollection($link, $provider, $_REQUEST['submission']);
					$learners = $learnersObj->build();

					// 2) Find out all information we can about each qualification learners do
					$qualifications = new QualificationsLookupCollection;
					$qualifications->populate($link, array_unique($learnersObj->getLars()));

					
					// 3) Rebuild this into a nice data structure whereby we have an array indexed by area, with all learners who participate in that area
					$reportRows = new AreaReportCollection;
					$reportRows->populate($link, $learners, $qualifications);
					
			
					$dataSet = array();
					foreach($reportRows->getData() AS $area => $node)
					{
						$dataSet[] = array(
							'Area of Learning (AOL)' => $area
							,'White - British' => $reportRows->filter(new ComparisonFilter('L12', array('23','31')))->size($area)
							,'White - Irish' => $reportRows->filter(new ComparisonFilter('L12', array('24','32')))->size($area)
							,'White - Gypsy or Irish Traveller' => $reportRows->filter(new ComparisonFilter('L12', array('33')))->size($area)
							,'White - Other White' => $reportRows->filter(new ComparisonFilter('L12', array('25','34')))->size($area)
							,'Mixed - White & Black Caribbean' => $reportRows->filter(new ComparisonFilter('L12', array('21','35')))->size($area)
							,'Mixed - White & Black African' => $reportRows->filter(new ComparisonFilter('L12', array('20','36')))->size($area)
							,'Mixed - White & Asian' => $reportRows->filter(new ComparisonFilter('L12', array('19','37')))->size($area)
							,'Mixed - Other Mixed' => $reportRows->filter(new ComparisonFilter('L12', array('22','38')))->size($area)
							,'Asian or Asian British - Indian' => $reportRows->filter(new ComparisonFilter('L12', array('12','39')))->size($area)
							,'Asian or Asian British - Pakistani' => $reportRows->filter(new ComparisonFilter('L12', array('13','40')))->size($area)
							,'Asian or Asian British - Bangladeshi' => $reportRows->filter(new ComparisonFilter('L12', array('11','41')))->size($area)
							,'Asian or Asian British - Chinese' => $reportRows->filter(new ComparisonFilter('L12', array('42')))->size($area)
							,'Asian or Asian British - Other Asian' => $reportRows->filter(new ComparisonFilter('L12', array('14','43')))->size($area)
							,'Black or Black British - Black African' => $reportRows->filter(new ComparisonFilter('L12', array('15','44')))->size($area)
							,'Black or Black British - Black Caribbean' => $reportRows->filter(new ComparisonFilter('L12', array('16','45')))->size($area)
							,'Black or Black British - Other Black' => $reportRows->filter(new ComparisonFilter('L12', array('17','46')))->size($area)
							,'Arab' => $reportRows->filter(new ComparisonFilter('L12', '47'))->size($area)
							,'Other Ethnic Group' => $reportRows->filter(new ComparisonFilter('L12', '98'))->size($area)
							,'Not Provided' => $reportRows->filter(new ComparisonFilter('L12', '99'))->size($area)
							,'Total' => $reportRows->size($area)
						);
					}					
					
					$data = new DataMatrix(array_keys($dataSet[0]), $dataSet);
					$data->addTotalColumns(array('White - British'
						,'White - Irish'
						,'White - Gypsy or Irish Traveller'
						,'White - Other White'
						,'Mixed - White & Black Caribbean'
						,'Mixed - White & Black African'
						,'Mixed - White & Asian'
						,'Mixed - Other Mixed'
						,'Asian or Asian British - Indian'
						,'Asian or Asian British - Pakistani'
						,'Asian or Asian British - Bangladeshi'
						,'Asian or Asian British - Chinese'
						,'Asian or Asian British - Other Asian'
						,'Black or Black British - Black African'
						,'Black or Black British - Black Caribbean'
						,'Black or Black British - Other Black'
						,'Arab'
						,'Other Ethnic Group'
						,'Not Provided'
						,'Total'));
					$dataHTML = $data->to($_REQUEST['output']);					
					
					break;					

				// ############################################################
				// DISABILITY/LEARNING DIFFICULTIES REPORT
				// ############################################################	
				case 'disability':

					$title = 'Number of learners with a disability or learning difficulty by area of learning and programme';
					
					// 1) Build up a collection of learners and their qualifications
					$learnersObj = new LearnerQualificationCollection($link, $provider, $_REQUEST['submission']);
					$learners = $learnersObj->build();
					
					// 2) Find out all information we can about each qualification learners do
					$qualifications = new QualificationsLookupCollection;
					$qualifications->populate($link, array_unique($learnersObj->getLars()));
					
					// 3) Rebuild this into a nice data structure whereby we have an array indexed by area, with all learners who participate in that area
					$reportRows = new AreaReportCollection;
					$reportRows->populate($link, $learners, $qualifications);
					
					$dataSet = array();
					foreach($reportRows->getData() AS $area => $node)
					{
						$dataSet[] = array(
							'Area of Learning (AOL)' => $area
							,'Disability' => $reportRows->filter(new ComparisonFilter('L15', '98', false))->size($area)
							,'Learning Difficulty' => $reportRows->filter(new ComparisonFilter('L16', '98', false))->size($area)
							,'Total' => $reportRows->size($area)
						);
					}					
					//
					
/*					$dataSet = array();

					if($_REQUEST['submission']!='')
						$where = " contracts.id = " . $_REQUEST['submission'];
					else
						$where = "";
					
					$sql = "delete from multi_bar_graph";
					$st = $link->query($sql);

		$sql = <<<HEREDOC
SELECT STRAIGHT_JOIN
	DISTINCT tr.id AS tr_id, 
	tr.gender,
	CONCAT(lisl12.Ethnicity_Code, ' ', lisl12.Ethnicity_Desc) as ethnicity, 
	CONCAT(lisl15.Disability_Code, ' ', lisl15.Disability_Desc) as disability, 
	IF(target_status is null or framework_percentage>=target_status, "On Track", "Behind") as progress,
	IF((DATEDIFF(tr.start_date, tr.dob)/365)>=16 AND (DATEDIFF(tr.start_date, tr.dob)/365)<19, "16-18", IF((DATEDIFF(tr.start_date, tr.dob)/365)>=19 AND (DATEDIFF(tr.start_date, tr.dob)/365)<=24, "19-24", IF((DATEDIFF(tr.start_date, tr.dob)/365)>24, "24+", "Unknown"))) as age,
	CONCAT(lisl16.Difficulty_Code, ' ', lisl16.Difficulty_Desc) as learning_difficulty, 
	courses.title as course_title,
	
	IF(concat(assessors.firstnames,' ',assessors.surname) IS NOT NULL, concat(assessors.firstnames,' ',assessors.surname), concat(assessorsng.firstnames,' ',assessorsng.surname)) as assessor,
	
	concat(verifiers.firstnames, ' ', verifiers.surname) as verifier,
	concat(gtutors.firstnames, ' ', gtutors.surname) as tutor,
	providers.legal_name as provider,
	actual_work_experience_subquery.actual_work_experience,
	DATE_FORMAT(workplace_visits.end_date,"%Y-%m-01") as visits,	
	concat(wbcoordinators.firstnames, ' ', wbcoordinators.surname) as wbcoordinator,
	IF(actual_work_experience_subquery.actual_work_experience>=0 and actual_work_experience_subquery.actual_work_experience<=10, "0-10", IF(actual_work_experience_subquery.actual_work_experience>=11 and actual_work_experience_subquery.actual_work_experience<=20, "11-20",IF(actual_work_experience_subquery.actual_work_experience>=21 and actual_work_experience_subquery.actual_work_experience<=30, "21-30",IF(actual_work_experience_subquery.actual_work_experience>=31 and actual_work_experience_subquery.actual_work_experience<=40, "31-40",IF(actual_work_experience_subquery.actual_work_experience>=41 and actual_work_experience_subquery.actual_work_experience<=50, "41-50",Null))))) as band0to10,
	qualifications_subquery.mainarea,		
	qualifications_subquery.internaltitle,	
	qualifications_subquery.level,	
	users.job_role,
	lookup_pot_status.description as record_status	
FROM
	tr 
	INNER JOIN organisations AS providers ON tr.provider_id = providers.id
	LEFT JOIN organisations as employers on tr.employer_id = employers.id
	INNER JOIN users ON users.username = tr.username
	LEFT JOIN group_members ON group_members.tr_id = tr.id
	LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT JOIN groups on group_members.groups_id = groups.id 
	LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
	LEFT JOIN courses on courses.id = courses_tr.course_id
	LEFT JOIN lis200809.ILR_L12_Ethnicity as lisl12 on lisl12.Ethnicity_Code = tr.ethnicity
	LEFT JOIN lis200809.ILR_L15_Disability as lisl15 on lisl15.Disability_Code = tr.disability
	LEFT JOIN lis200809.ILR_L16_Difficulty as lisl16 on lisl16.Difficulty_Code = tr.learning_difficulty
	LEFT JOIN users as assessors on assessors.username = groups.assessor
	LEFT JOIN users as assessorsng on assessorsng.username = tr.assessor
	LEFT JOIN users as verifiers on verifiers.username = groups.verifier
	LEFT JOIN users as gtutors on gtutors.username = groups.tutor
	LEFT JOIN users as wbcoordinators on wbcoordinators.username = groups.wbcoordinator
	LEFT JOIN workplace_visits on workplace_visits.tr_id = tr.id 
	LEFT JOIN ilr on ilr.tr_id = tr.id
	LEFT JOIN contracts on contracts.id = tr.contract_id
	LEFT JOIN course_qualifications_dates on course_qualifications_dates.course_id = courses.id 
	LEFT JOIN lookup_pot_status on lookup_pot_status.code = tr.status_code
	LEFT OUTER JOIN (
		SELECT 
			qualifications.mainarea, 
			qualifications.internaltitle,
			qualifications.level,
			tr_id
		FROM qualifications 
			LEFT JOIN framework_qualifications AS mainaim ON mainaim.id = qualifications.id AND mainaim.internaltitle = qualifications.internaltitle AND main_aim = 1 
			LEFT JOIN student_qualifications ON student_qualifications.id = mainaim.id AND student_qualifications.framework_id = mainaim.framework_id

	) AS `qualifications_subquery`
		ON `qualifications_subquery`.tr_id = tr.id
	LEFT OUTER JOIN (
		SELECT
			student_qualifications.tr_id,
			sum(unitsUnderAssessment/100*proportion) AS `framework_percentage`
		FROM
			student_qualifications
		where aptitude!=1
		GROUP BY
			student_qualifications.tr_id
	) AS `student qualifications subquery`
		ON `student qualifications subquery`.tr_id = tr.id

	LEFT OUTER JOIN (
		SELECT
			workplace_visits.tr_id,
			count(*) AS `actual_work_experience`
		FROM
			workplace_visits
		where end_date is not null
		GROUP BY
			workplace_visits.tr_id
	) AS `actual_work_experience_subquery`
		ON `actual_work_experience_subquery`.tr_id = tr.id
		
	LEFT OUTER JOIN (
		SELECT
			tr.id,
			student_milestones.tr_id,
			CASE PERIOD_DIFF(DATE_FORMAT(CURDATE(),'%Y%m'), DATE_FORMAT(tr.start_date,'%Y%m') )
				WHEN 1 THEN avg(student_milestones.month_1)
				WHEN 2 THEN avg(student_milestones.month_2)
				WHEN 3 THEN avg(student_milestones.month_3)
				WHEN 4 THEN avg(student_milestones.month_4)
				WHEN 5 THEN avg(student_milestones.month_5)
				WHEN 6 THEN avg(student_milestones.month_6)
				WHEN 7 THEN avg(student_milestones.month_7)
				WHEN 8 THEN avg(student_milestones.month_8)
				WHEN 9 THEN avg(student_milestones.month_9)
				WHEN 10 THEN avg(student_milestones.month_10)
				WHEN 11 THEN avg(student_milestones.month_11)
				WHEN 12 THEN avg(student_milestones.month_12)
				WHEN 13 THEN avg(student_milestones.month_13)
				WHEN 14 THEN avg(student_milestones.month_14)
				WHEN 15 THEN avg(student_milestones.month_15)
				WHEN 16 THEN avg(student_milestones.month_16)
				WHEN 17 THEN avg(student_milestones.month_17)
				WHEN 18 THEN avg(student_milestones.month_18)
				WHEN 19 THEN avg(student_milestones.month_19)
				WHEN 20 THEN avg(student_milestones.month_20)
				WHEN 21 THEN avg(student_milestones.month_21)
				WHEN 22 THEN avg(student_milestones.month_22)
				WHEN 23 THEN avg(student_milestones.month_23)
				WHEN 24 THEN avg(student_milestones.month_24)
				WHEN 25 THEN avg(student_milestones.month_25)
				WHEN 26 THEN avg(student_milestones.month_26)
				WHEN 27 THEN avg(student_milestones.month_27)
				WHEN 28 THEN avg(student_milestones.month_28)
				WHEN 29 THEN avg(student_milestones.month_29)
				WHEN 30 THEN avg(student_milestones.month_30)
				WHEN 31 THEN avg(student_milestones.month_31)
				WHEN 32 THEN avg(student_milestones.month_32)
				WHEN 33 THEN avg(student_milestones.month_33)
				WHEN 34 THEN avg(student_milestones.month_34)
				WHEN 35 THEN avg(student_milestones.month_35)
				WHEN 36 THEN avg(student_milestones.month_36)
				ELSE 0
			END	AS `target_status`
		FROM
			tr
			LEFT JOIN student_milestones
				ON student_milestones.tr_id = tr.id
			LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
			LEFT JOIN courses on courses.id = courses_tr.course_id
			LEFT JOIN student_qualifications ON student_qualifications.tr_id = tr.id
			WHERE chosen=1 and student_qualifications.aptitude != 1 AND student_milestones.qualification_id = student_qualifications.id
		GROUP BY
			tr.id ) AS `student milestones subquery`
	ON tr.id = `student milestones subquery`.tr_id
	WHERE ($where) group by tr.username  ORDER BY tr.surname ASC, tr.firstnames ASC, tr.start_date ASC
HEREDOC;

					DAO::execute($link, "insert into multi_bar_graph ".$sql);
					
					$sql = "SELECT mainarea, 
COUNT(*) AS total,
(SELECT COUNT(*) FROM multi_bar_graph AS m2 WHERE m2.mainarea = m1.mainarea AND disability!='98 No disability' ) AS dis  ,
(SELECT COUNT(*) FROM multi_bar_graph AS m3 WHERE m3.mainarea = m1.mainarea AND learning_difficulty!='98 No learning difficulty' ) AS ld 
FROM multi_bar_graph AS m1 GROUP BY mainarea;
					";	
					$st = $link->query($sql);
					if($st) 
					{
						while($row = $st->fetch())
						{
							$dataSet[] = array("Area of Learning (AOL)" => $row['mainarea'], "Disability" => $row['dis'], "Learning Difficulty" => $row['ld'], "Total" => $row['total']);	
						}
					}					
		*/			
					$data = new DataMatrix(array_keys($dataSet[0]), $dataSet);
					$data->addTotalColumns(array('Disability'
						,'Learning Difficulty'
						,'Total'));					
					$dataHTML = $data->to($_REQUEST['output']);						
					
					break;
					
				// ############################################################
				// DISCREPENCY
				// ############################################################						
				case 'discrepency':
					
					$html = $html2 = '';
					if(isset($_REQUEST['submission']))
					{
						// 1) Get all contracts
						$sql = "
							SELECT id, title FROM contracts ORDER BY title ASC
						;";
				
						$st = $link->query($sql);
				
						$contracts = array();
						if($st)
						{
							//var_dump($st->fetch());
							while($row = $st->fetch())
							{
								$html .= '<h3>' . $row['title'] . ' (ID: ' . $row['id'] . ')</h3>';
								$sql = "
									SELECT
										i.L03
										,i.ilr
										,i.tr_id
										,sq.total
									FROM
										ilr AS i
									INNER JOIN
										(SELECT COUNT(*) AS total, tr_id FROM student_qualifications where aptitude != 1 GROUP BY tr_id) AS sq ON i.tr_id = sq.tr_id
									WHERE
										i.submission = '" . addslashes((string)$_REQUEST['submission']) . "' AND i.contract_id = '" . intval($row['id']) . "'							
								";
								$st2 = $link->query($sql);
								if($st2)
								{
									// 2) For every ILR record, check number of aims match number of student_qualification rows
									$total = 0;
									while($row2 = $st2->fetch())
									{
										//$xml = new SimpleXMLElement($row2['ilr']);
										$xml = XML::loadSimpleXML($row2['ilr']);
										$xresult = $xml->xpath('//subaim|//main');
										if(sizeof($xresult) != $row2['total'])									
										{
											$total++;
											$html .= '<span style="color: #FF0000;">ERROR:</span>' . $row2['L03'] . ' (' . $xml->learner->L09 . ',' . $xml->learner->L10 . ') has ' . sizeof($xresult) . ' aim(s) in the xml file but ' . $row2['total'] . ' rows in the database <br />';
										}
										else
										{
											//echo $row2['L03'] . ' has ' . sizeof($xresult) . ' aim(s) in the xml file but ' . $row2['total'] . ' rows in the database <br />';
										}									
									}
									if($total == 0)
									{
										$html .= '<p>No discrepencies in the ILR xml files for this contract</p>';
									}
								}
							}	
						}		
					}		
					
					// breadcrumb
					$_SESSION['bc']->index=1;
					$_SESSION['bc']->add($link, 'do.php?_action=kpi_report&type=' . $_REQUEST['type'], 'Discrepency Report');		
					
					require_once('tpl_view_discrepency_report.php');
					die;
					break;
					
				// ############################################################
				// DISCREPENCY DATES
				// ############################################################						
				case 'discrepencydates':
					
					set_time_limit(0);
					$total = $bad = 0;
					$html = $html2 = '';
					if(isset($_REQUEST['submission']))
					{
						$sql = "
							SELECT
								i.L03
								,i.ilr
								,i.tr_id
							FROM
								ilr AS i
							WHERE
								i.submission = '" . addslashes((string)$_REQUEST['submission']) . "'							
						";

						$st2 = $link->query($sql);
						if($st2)
						{
							// 2) For every ILR record, check number of aims match number of student_qualification rows
							while($row2 = $st2->fetch())
							{
								//$xml = new SimpleXMLElement($row2['ilr']);
								$xml = XML::loadSimpleXML($row2['ilr']);
								$xresult = $xml->xpath('//subaim|//main');
								
								foreach($xresult AS $key => $xml)
								{
									$statement = $link->query("
										SELECT * FROM student_qualifications WHERE REPLACE(id,'/','') = $xml->A09
									");
									
									while($sq = $statement->fetch())
									{
										$total += 2;
										$startdate = date('d/m/Y', strtotime($sq['start_date']));
										$target_end_date = date('d/m/Y', strtotime($sq['end_date']));
										if($startdate != $xml->A27)
										{
											$bad++;
											pr('Student_qualification auto_id = ' . $sq['auto_id'] . ' with start date ' . $startdate . ' is out of sync with ILR which has a value of ' . $xml->A27);
										}
										else if($target_end_date != $xml->A28)
										{
											$bad++;
											pr('Student_qualification auto_id = ' . $sq['auto_id'] . ' with start date ' . $target_end_date . ' is out of sync with ILR which has a value of ' . $xml->A27);											
										}
									}
								}
							}
							pre('Total Aims checked: ' . $total . ' and bad = ' . $bad);
						}		
					}		
					
					// breadcrumb
					$_SESSION['bc']->index=1;
					$_SESSION['bc']->add($link, 'do.php?_action=kpi_report&type=' . $_REQUEST['type'], 'Discrepency Date Report');		
					
					require_once('tpl_view_discrepency_report_dates.php');
					die;
					break;					
					
			}
			
			$_SESSION['bc']->index=1;
			$_SESSION['bc']->add($link, 'do.php?_action=kpi_report&type=' . $_REQUEST['type'], $title);			
			
			$url = $this->get_url();
						
			require_once('tpl_view_kpi_report.php');
		}
	}
	
	private function get_url()
	{
		return str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1));	
	}
}

?>