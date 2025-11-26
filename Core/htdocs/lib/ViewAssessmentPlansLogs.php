<?php
class ViewAssessmentPlanLogs extends View
{

	public static function getInstance($link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			$sql = <<<HEREDOC
select
tr.l03 as learnrefnumber
,tr.contract_id
,assessment_plan_log.tr_id
, tr.uln as uln
,CONCAT(tr.firstnames, ' ', tr.surname) AS learner_name
,assessment_plan_log.due_date
,assessment_plan_log.actual_date
,assessment_plan_log.mode
,assessment_plan_log.traffic as status
,assessment_plan_log.paperwork
,assessment_plan_log.comments
From
assessment_plan_log
inner join tr on tr.id = assessment_plan_log.tr_id
left join group_members on group_members.tr_id = assessment_plan_log.tr_id
left join groups on groups.id = group_members.groups_id
HEREDOC;
			$view = $_SESSION[$key] = new ViewAssessmentPlanLogs();
			$view->setSQL($sql);

			$parent_org = $_SESSION['user']->employer_id;
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, '1. The learner is continuing or intending to continue', null, 'WHERE tr.status_code=1'),
				2=>array(2, '2. The learner has completed the learning activity', null, 'WHERE tr.status_code=2'),
				3=>array(3, '3. The learner has withdrawn from learning', null, 'WHERE tr.status_code=3'),
				4=>array(4, '4. The learner has transferred to a new learning provider', null, 'WHERE tr.status_code = 4'),
				5=>array(5, '5. Changes in learning within the same programme', null, 'WHERE tr.status_code = 5'),
				6=>array(6, '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
				7=>array(7, '7. Delete from ILR', null, 'WHERE tr.status_code = 7'));
			$f = new DropDownViewFilter('filter_record_status', $options, 1, false);
			$f->setDescriptionFormat("Show: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, '1. Green', null, 'WHERE traffic=1'),
				2=>array(2, '2. Yellow', null, 'WHERE traffic=2'),
				3=>array(3, '3. Red', null, 'WHERE traffic=3'));
			$f = new DropDownViewFilter('filter_review_status', $options, 0, false);
			$f->setDescriptionFormat("Show: %s");
			$view->addFilter($f);


			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, '1. In progress', null, 'WHERE paperwork=1'),
				2=>array(2, '2. Awaiting marking', null, 'WHERE paperwork=2'),
				3=>array(3, '3. Complete', null, 'WHERE paperwork=3'),
				4=>array(4, '4. Rework required', null, 'WHERE paperwork=4'),
				5=>array(5, '5. IQA', null, 'WHERE paperwork=5'),
				6=>array(6, '6. Overdue', null, 'WHERE paperwork=6'));
			$f = new DropDownViewFilter('filter_paperwork', $options, 0, false);
			$f->setDescriptionFormat("Show: %s");
			$view->addFilter($f);

			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(200,200,null,null),
				4=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);


			$options = array(
				0=>array(1, 'Learner, Due Date ASC', null, 'ORDER BY learner_name, due_date ASC'),
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
			$format = "WHERE true";
			$f = new DateViewFilter('start_date', $format, '');
			$f->setDescriptionFormat("From: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE true";
			$f = new DateViewFilter('end_date', $format, '');
			$f->setDescriptionFormat("To: %s");
			$view->addFilter($f);


			// Last Review Date filters
			$dateInfo = getdate();
			$weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
			$timestamp = time()  - ((60*60*24) * $weekday);

			// Surname Filter
			$f = new TextboxViewFilter('surname', "WHERE tr.surname LIKE '%s%%'", null);
			$f->setDescriptionFormat("Surname: %s");
			$view->addFilter($f);

			// Rewind by a further 1 week
			$timestamp = $timestamp - ((60*60*24) * 7);

			// Start Date Filter
			$format = "WHERE assessment_plan_log.actual_date >= '%s'";
			$f = new DateViewFilter('last_start_date', $format, '');
			$f->setDescriptionFormat("From: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE assessment_plan_log.actual_date <= '%s'";
			$f = new DateViewFilter('last_end_date', $format, '');
			$f->setDescriptionFormat("To: %s");
			$view->addFilter($f);

			$options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',char(39),id,char(39),' or tr.assessor=' , char(39),id, char(39)) FROM users where type=3";
			$f = new DropDownViewFilter('filter_assessor', $options, null, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);

			$options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE assessment_plan_log.assessor=',id) FROM users where type=3 ORDER BY username";
			$f = new DropDownViewFilter('filter_person_reviewed', $options, null, true);
			$f->setDescriptionFormat("Person Reviewed: %s");
			$view->addFilter($f);

			// ULN Filter
			$f = new TextboxViewFilter('filter_uln', "WHERE tr.uln LIKE '%s%%'", null);
			$f->setDescriptionFormat("Learner ULN: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
			$f->setDescriptionFormat("LearnRefNumber: %s");
			$view->addFilter($f);


		}

		return $_SESSION[$key];
	}


	public function render(PDO $link)
	{
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo <<<HEREDOC
	<thead>
	<tr>
		<th>LearnRefNumnber</th>
		<th>ULN</th>
		<th>Learner Name</th>
		<th>Due Date</th>
		<th>Actual Date</th>
		<th>Mode</th>
		<th>Status</th>
		<th>Paperwork</th>
		<th>Comments</th>
	</tr>
	</thead>
HEREDOC;

			echo '<tbody>';
			while($row = $st->fetch())
			{
				//echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&amp;id=' . $row['tr_id'] . '&amp;contract=' . $row['contract_id']);
				echo '<tr style="font-size:8pt">';
				echo '<td align="left">' . HTML::cell($row['learnrefnumber']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['uln']) . '</td>';
				echo '<td align="left"><a href=do.php?_action=read_training_record&amp;id='. $row['tr_id'] . '&amp;contract=' . $row['contract_id']. ' ><span style="color: black"> ' . HTML::cell($row['learner_name']) . '</span></a></td>';
				echo '<td align="left">' . HTML::cell(Date::toShort($row['due_date'])) . '</td>';
				echo '<td align="left">' . HTML::cell(Date::toShort($row['actual_date'])) . '</td>';

				$mode_ddl = array(
					array('1', 'Analysis'),
					array('2', 'Business Operation'),
					array('3', 'Communication'),
					array('4', 'Customer Service'),
					array('5', 'Data'),
					array('6', 'Digital Analytics'),
					array('7', 'Digital Tools'),
					array('8', 'Implementation'),
					array('9', 'Industry Developments & Practices'),
					array('10', 'Problem Solving'),
					array('11', 'Research'),
					array('12', 'Specialist Areas'),
					array('13', 'Technologies'),
					array('14', 'H&S'),
					array('15', 'Remote Infrastructure'),
					array('16', 'Workflow Management'),
					array('17', 'IT Security'),
					array('18', 'WEEE'),
					array('19', 'Performance'),
					array('20', 'Business'),
					array('21', 'Development Lifecycle'),
					array('22', 'Logic'),
					array('23', 'Quality'),
					array('24', 'Security'),
					array('25', 'Test'),
					array('26', 'User Interface'),
					array('27', 'Assess & Qualify Sales Leads'),
					array('28', 'Context & CPD'),
					array('29', 'Customer Experience'),
					array('30', 'Data Security'),
					array('31', 'Database & Campaign Management'),
					array('32', 'Sales Process'),
					array('33', 'Data manipulating & Linking'),
					array('34', 'Performance Queries'),
					array('35', 'Data Quality'),
					array('36', 'Presenting Data'),
					array('37', 'Investigation Techniques'),
					array('38', 'Data Modelling'),
					array('39', 'Stakeholder Analysis & Management'),
					array('40', 'Diagnostic Tools & Techniques'),
					array('41', 'Integrating Network Software'),
					array('42', 'Monitor Test & Adjust Networks'),
					array('43', 'Service Level Agreements'),
					array('44', 'Business Environment'),
					array('45', 'Operational Requirements'),
					array('46', 'Advise and Support Others'),
					array('47', 'Developing & Collecting Data'),
					array('48', 'Presenting Test Results'),
					array('49', 'Test Cases'),
					array('50', 'Legislation'),
					array('51', 'Technical'),
					array('52', 'Data Analysis Security & Policies'),
					array('53', 'Statistical Analysis'),
					array('54', 'Applications'),
					array('55', 'Data Architecture'),
					array('56', 'Business Process Modelling'),
					array('57', 'Gap Analysis'),
					array('58', 'Business Impact Assessment'),
					array('59', 'Documenting'),
					array('60', 'Interpret Written Requirements and Tech Specs'),
					array('61', 'Network Installation'),
					array('62', 'Troubleshooting & Repair'),
					array('63', 'Deployment'),
					array('64', 'Testing'),
					array('65', 'Conduct Software Testing'),
					array('66', 'Implementing Software Testing'),
					array('67', 'Results vs Expectations'),
					array('68', 'Test Outcomes'),
					array('69', 'Project Management'),
					array('70', 'Data Migration'),
					array('71', 'Collect & Compile Data'),
					array('72', 'Analytical Techniques'),
					array('73', 'Reporting Data'),
					array('74', 'Business Analysis'),
					array('75', 'Requirements Engineering & Management'),
					array('76', 'Acceptance Testing'),
					array('77', 'Design Networks from a Specification'),
					array('78', 'Effective Business Operation'),
					array('79', 'Logging & Responding to Calls'),
					array('80', 'Network Performance'),
					array('81', 'Upgrading Network Systems'),
					array('82', 'Design'),
					array('83', 'User Interface'),
					array('84', 'Design Test Strategies'),
					array('85', 'Legislation & Standards'),
					array('86', 'Software Requirements'),
					array('87', 'Service Level Agreements '),
                    array('88', 'Test Plans'),
                    array('89', 'Test Outcomes'),
					array('90', 'Employer Reference')
				);

				if(isset($mode_ddl[$row['mode']-1][1]))
					echo '<td align="left">' . HTML::cell($mode_ddl[$row['mode']-1][1]) . '</td>';
				else
					echo '<td align="left">' . HTML::cell('') . '</td>';

				if($row['status']=='1')
					echo '<td width="100px" align="center"> <img src="/images/trafficlight-green.jpg" border="0" alt="" /></td>';
				else
					if($row['status']=='2')
						echo '<td width="100px" align="center"> <img src="/images/trafficlight-yellow.jpg" border="0" alt="" /></td>';
					else
						if($row['status']=='3')
							echo '<td width="100px" align="center"> <img src="/images/trafficlight-red.jpg" border="0" alt="" /></td>';
						else
							echo '<td align="center"> No status </td>';

				$paperwork_ddl = array(
					array('1', 'In progress'),
					array('2', 'Awaiting marking'),
					array('3', 'Complete'),
					array('4', 'Rework required'),
					array('5', 'IQA'),
					array('6', 'Overdue')
				);
				echo '<td align="left">' . HTML::cell($paperwork_ddl[$row['paperwork']-1][1]) . '</td>';
				echo '<td align="center" style="font-size: 11px;">' . HTML::cell($row['comments']) . '</td>';

				echo '</tr>';
			}
			echo '</tbody></table></div>';
			echo $this->getViewNavigator();

		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

	}
}
?>