<?php
class ViewInterviewsReport extends View
{
	const ReedInPartnerShipSunesisId = 769;

	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			$reed_in_partnership_id = ViewInterviewsReport::ReedInPartnerShipSunesisId;
			$today_date = date('Y-m-d');
			$sql = <<<SQL

			SELECT DISTINCT
	interviews.id AS interview_id,
	interviews.interview_date,
	interviews.interview_start_time AS start_time,
	interviews.interview_end_time AS end_time,
	(SELECT description FROM lookup_interview_types WHERE id = interviews.interview_type) AS interview_type,
	(SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE id = interviews.interviewer) AS interviewer,
	(SELECT description FROM lookup_interview_status WHERE id = interviews.interview_status) AS interview_status,
	interviews.interview_rgb_status,
	(SELECT description FROM lookup_interview_paperwork WHERE id = interviews.interview_paperwork) AS interview_paperwork,
	#(SELECT title FROM modules WHERE id = interviews.interview_module) AS interview_module,
	interviews.interview_comments AS interview_comments_complete,
	IF(LENGTH(interviews.interview_comments) > 20, CONCAT(LEFT(interviews.interview_comments, 20), ' ...'), interviews.`interview_comments`) AS interview_comments,
	tr.l03 AS learner_reference_number,
	CONCAT(tr.firstnames, ' ', tr.surname) AS learner_name,
	tr.ethnicity AS ethnic_origin,
	tr.gender,
	IF(tr.`ethnicity` IN (31,32,33,34,99), 'No', 'Yes') AS bame,
	'' AS health_problem,
	'' AS learning_difficulty,
	'' AS disability,
	'' AS prior_attainment,
	(IF(tr.provider_id = $reed_in_partnership_id, 'Reed', 'Supply Chain')) AS type,
	(SELECT legal_name FROM organisations WHERE id = tr.provider_id) AS provider,
	(IF(tr.provider_id = $reed_in_partnership_id, 'Prime', 'Sub')) AS reed_status,
	(IF(interviews.interview_date > CURRENT_DATE, 'Forward', 'Past')) AS forward_or_past,
	(CONCAT(RIGHT(YEAR(interview_date),2), '-', LPAD(MONTH(interview_date), 2, '0'))) AS event_calendar_month,
	(SELECT period FROM lookup_reed_periods WHERE date_mysql = interviews.interview_date) AS reed_period,
	(SELECT week FROM lookup_reed_periods WHERE date_mysql = interviews.interview_date) AS event_reed_week,
	interviews.tr_id,
	interviews.created
FROM
	interviews
LEFT JOIN
	tr ON interviews.tr_id = tr.id
LEFT JOIN
	group_members ON tr.id = group_members.tr_id
LEFT JOIN groups
	ON groups.id = group_members.groups_id
SQL;

			// Create new view object

			$view = $_SESSION[$key] = new ViewInterviewsReport();
			$view->setSQL($sql);

			$parent_org = $_SESSION['user']->employer_id;

			//L03 filter
			$f = new TextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
			$f->setDescriptionFormat("Learner Ref: %s");
			$view->addFilter($f);

			// Firstname Filter
			$f = new TextboxViewFilter('filter_firstnames', "WHERE tr.firstnames LIKE '%s%%'", null);
			$f->setDescriptionFormat("First Name: %s");
			$view->addFilter($f);

			// SurnameFilter
			$f = new TextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%s%%'", null);
			$f->setDescriptionFormat("Surname: %s");
			$view->addFilter($f);

			//Gender filter
			$options = array(
				0=>array(0, 'Show All', null, null),
				1=>array(1, 'Male', null, 'WHERE tr.gender="M"'),
				2=>array(2, 'Female', null, 'WHERE tr.gender="F"'),
				3=>array(3, 'Withheld', null, 'WHERE tr.gender="W"'),
				4=>array(4, 'Unknown', null, 'WHERE tr.gender="U"')
			);
			$f = new DropDownViewFilter('filter_gender', $options, 0, false);
			$f->setDescriptionFormat("Gender: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show All', null, null),
				1=>array(1, 'Prime', null, 'WHERE tr.provider_id=' . $reed_in_partnership_id),
				2=>array(2, 'Sub', null, 'WHERE tr.provider_id!=' . $reed_in_partnership_id)
			);
			$f = new DropDownViewFilter('filter_reed_status', $options, 0, false);
			$f->setDescriptionFormat("Reed Status: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show All', null, null),
				1=>array(1, 'Reed', null, 'WHERE tr.provider_id=' . $reed_in_partnership_id),
				2=>array(2, 'Supply Chain', null, 'WHERE tr.provider_id!=' . $reed_in_partnership_id)
			);
			$f = new DropDownViewFilter('filter_type', $options, 0, false);
			$f->setDescriptionFormat("Type: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show All', null, null),
				1=>array(1, 'Forward', null, 'WHERE interviews.interview_date > CURRENT_DATE() '),
				2=>array(2, 'Past', null, 'WHERE interviews.interview_date <= CURRENT_DATE() ')
			);
			$f = new DropDownViewFilter('filter_forward_past', $options, 0, false);
			$f->setDescriptionFormat("Forward / Past: %s");
			$view->addFilter($f);

			// Assessor Filter
			if($_SESSION['user']->type==User::TYPE_MANAGER)
				$options = "SELECT id, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE groups.assessor=',char(39),id,char(39),' or interviews.interviewer=' , char(39),id, char(39)) FROM users where type=3 order by firstnames,surname";
			else
				$options = "SELECT id, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE groups.assessor=',char(39),id,char(39),' or interviews.interviewer=' , char(39),id, char(39)) FROM users where type=3 order by firstnames,surname";
			$f = new DropDownViewFilter('filter_assessor', $options, null, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);

			$options = "SELECT id, description, null, CONCAT('WHERE interviews.interview_status=',char(39),id,char(39)) FROM lookup_interview_status ORDER BY description";
			$f = new DropDownViewFilter('filter_interview_status', $options, null, true);
			$f->setDescriptionFormat("Interview Status: %s");
			$view->addFilter($f);

			$options = "SELECT date_mysql, CONCAT(period,' (week = ',WEEK,', day in week = ',day_in_week,')'), year, CONCAT('WHERE interviews.interview_date=',char(39),date_mysql,char(39)) FROM lookup_reed_periods ORDER BY period_id";
			$f = new DropDownViewFilter('filter_reed_period', $options, null, true);
			$f->setDescriptionFormat("Reed Period: %s");
			$view->addFilter($f);

			$options = "SELECT id, description, null, CONCAT('WHERE interviews.interview_type=',char(39),id,char(39)) FROM lookup_interview_types ORDER BY description";
			$f = new DropDownViewFilter('filter_interview_type', $options, null, true);
			$f->setDescriptionFormat("Interview Type: %s");
			$view->addFilter($f);

			$options = "SELECT id, description, null, CONCAT('WHERE interviews.interview_paperwork=',char(39),id,char(39)) FROM lookup_interview_paperwork ORDER BY description";
			$f = new DropDownViewFilter('filter_interview_paperwork', $options, null, true);
			$f->setDescriptionFormat("Interview Paperwork: %s");
			$view->addFilter($f);

			$options = "SELECT id, legal_name, null, CONCAT('WHERE tr.provider_id=',char(39),id,char(39)) FROM organisations WHERE organisation_type = 3 ORDER BY legal_name";
			$f = new DropDownViewFilter('filter_provider', $options, null, true);
			$f->setDescriptionFormat("Provider: %s");
			$view->addFilter($f);

			$options = "SELECT id, legal_name, null, CONCAT('WHERE tr.employer_id=',char(39),id,char(39)) FROM organisations WHERE organisation_type = 2 ORDER BY legal_name";
			$f = new DropDownViewFilter('filter_employer', $options, null, true);
			$f->setDescriptionFormat("Provider: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show All', null, null),
				1=>array(1, 'Green', null, 'WHERE interviews.interview_rgb_status = "green"'),
				2=>array(2, 'Yellow', null, 'WHERE interviews.interview_rgb_status = "yellow"'),
				3=>array(3, 'Red', null, 'WHERE interviews.interview_rgb_status = "red"'));
			$f = new DropDownViewFilter('filter_interview_rgb_status', $options, 0, false);
			$f->setDescriptionFormat("Interview GYR Status: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show All', null, null),
				1=>array(1, 'Sessions Today', null, ' WHERE interview_date = CURRENT_DATE() AND interviews.interview_status = 1 '),
				2=>array(2, 'Booked and Overdue (less than 13 weeks)', null, ' WHERE interview_date < CURRENT_DATE() AND interview_date >= CURRENT_DATE() - INTERVAL 13 WEEK AND interviews.interview_status = 1 '),
				3=>array(3, 'Booked and Overdue (more than 13 weeks)', null, ' WHERE interview_date <= CURRENT_DATE() - INTERVAL 13 WEEK AND interviews.interview_status = 1 '));
			$f = new DropDownViewFilter('filter_sessions', $options, 0, false);
			$f->setDescriptionFormat("Interview Sessions: %s");
			$view->addFilter($f);

			// Interview date filter
			$format = "WHERE interviews.interview_date >= '%s'";
			$f = new DateViewFilter('filter_from_interview_date', $format, '');
			$f->setDescriptionFormat("From interview date: %s");
			$view->addFilter($f);

			$format = "WHERE interviews.interview_date <= '%s'";
			$f = new DateViewFilter('filter_to_interview_date', $format, '');
			$f->setDescriptionFormat("To interview date: %s");
			$view->addFilter($f);

			$options = "SELECT id, title, contract_year, CONCAT('WHERE tr.contract_id =',char(39),id,char(39)) FROM contracts ORDER BY contract_year DESC , title";
			$f = new DropDownViewFilter('filter_contract', $options, null, true);
			$f->setDescriptionFormat("Contract: %s");
			$view->addFilter($f);

			$options = "SELECT Ethnicity AS id, LEFT(CONCAT(Ethnicity, ' ', Ethnicity_Desc),60) AS description, null, CONCAT('WHERE tr.ethnicity=',Ethnicity) FROM lis201314.ilr_ethnicity";
			$f = new DropDownViewFilter('filter_ethnicity', $options, null, true);
			$f->setDescriptionFormat("Ethnicity: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show All', null, null),
				1=>array(1, 'No', null, 'WHERE tr.ethnicity IN (31,32,33,34,99) '),
				2=>array(2, 'Yes', null, 'WHERE tr.ethnicity NOT IN (31,32,33,34,99) '));
			$f = new DropDownViewFilter('filter_bame', $options, 0, false);
			$f->setDescriptionFormat("BAME: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(200,200,null,null),
				4=>array(300,300,null,null),
				5=>array(400,400,null,null),
				6=>array(500,500,null,null),
				7=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'Learner', null, 'ORDER BY tr.firstnames ASC'),
				1=>array(2, 'Interview Date', null, 'ORDER BY interviews.interview_date ASC, interviews.interview_start_time ASC'),
				2=>array(3, 'Interviewer/Assessor', null, 'ORDER BY interviewer'),
				3=>array(4, 'Creation Date', null, 'ORDER BY interviews.created'));

			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);
		}

		return $_SESSION[$key];
	}

	public function render(PDO $link, $columns)
	{
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead>';
			echo '<tr><th>&nbsp;</th><th class="topRow" colspan="8">Interview Information</th><th class="topRow" colspan="18">Additional Fields</th></tr>';
			echo '<tr><th>&nbsp;</th>';
			foreach($columns as $column)
			{
				echo '<th class="bottomRow">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}
			echo '</tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				$tr_id = $row['tr_id'];
				$health_problem =  '"' . "/Learner/LLDDHealthProb|ilr/learner/L14" . '"';
				$disability =  '"' . "/Learner/LLDDandHealthProblem[LLDDType=\'DS\']/LLDDCode|/ilr/learner/L15" . '"';
				$learning_difficulty = '"' . "/Learner/LLDDandHealthProblem[LLDDType='LD']/LLDDCode|/ilr/learner/L16" . '"';
				$prior_attain = '"' . "/Learner/PriorAttain" . '"';
				$res = DAO::getResultset($link, "SELECT extractvalue(ilr, $health_problem),extractvalue(ilr,$disability),extractvalue(ilr,$learning_difficulty),extractvalue(ilr,$prior_attain) FROM ilr WHERE ilr.tr_id = $tr_id  ORDER BY ilr.`contract_id` DESC, submission DESC LIMIT 0,1");
				$row['health_problem'] = @$res[0][0];
				$row['disability'] = @$res[0][1];
				$row['learning_difficulty'] = @$res[0][2];
				$row['prior_attainment'] = @$res[0][3];

				echo HTML::viewrow_opening_tag('/do.php?_action=read_training_record&interview_tab=1&id=' . $row['tr_id']);
				echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/interview-icon.png\" border=\"0\" alt=\"\" /></td>";
				foreach($columns as $column)
				{
					if($column == 'interview_rgb_status')
					{
						switch($row['interview_rgb_status'])
						{
							case 'green':
								echo '<td align="center" class="greend" width="32"></td>';
								break;
							case 'yellow':
								echo '<td align="center" class="yellowd" width="32"></td>';
								break;
							case 'red':
								echo '<td align="center" class="redd" width="32"></td>';
								break;
							default:
								echo '<td align="center"></td>';
								break;
						}
					}
					elseif($column == 'interview_date')
						echo '<td align="center">' . HTML::cell(Date::toShort($row['interview_date'])) . '</td>';
					elseif($column == 'interview_comments')
						echo '<td align="center" title="' . HTML::cell($row['interview_comments_complete']) . '">' . HTML::cell($row['interview_comments']) . '</td>';
					else
						echo '<td align="center">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
				}
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