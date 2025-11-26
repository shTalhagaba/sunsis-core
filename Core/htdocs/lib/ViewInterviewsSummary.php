<?php
class ViewInterviewsSummary extends View
{
	const ReedInPartnerShipSunesisId = 769;

	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		//if(!isset($_SESSION[$key]))
		{
			$reed_in_partnership_id = ViewInterviewsSummary::ReedInPartnerShipSunesisId;
			$today_date = date('Y-m-d');
			$sql = <<<SQL

SELECT
	period AS event_reed_period, lookup_interview_types.description AS event_name,
	(SUM(IF(interviews.`interview_status` = (SELECT id FROM lookup_interview_status WHERE description = 'Booked'), 1, 0))) AS `booked`,
	(SUM(IF(interviews.`interview_status` = (SELECT id FROM lookup_interview_status WHERE description = 'Attended'), 1, 0))) AS `attended`,
	(SUM(IF(interviews.`interview_status` = (SELECT id FROM lookup_interview_status WHERE description = 'Attended Late'), 1, 0))) AS `attended_late`,
	(SUM(IF(interviews.`interview_status` = (SELECT id FROM lookup_interview_status WHERE description = 'Authorised Absence'), 1, 0))) AS `authorised_absence`,
	(SUM(IF(interviews.`interview_status` = (SELECT id FROM lookup_interview_status WHERE description = 'Cancelled'), 1, 0))) AS `cancelled`,
	(SUM(IF(interviews.`interview_status` = (SELECT id FROM lookup_interview_status WHERE description = 'Failed to Attend'), 1, 0))) AS `failed_to_attend`,
	(SUM(IF(interviews.`interview_status` = (SELECT id FROM lookup_interview_status WHERE description = 'Rescheduled'), 1, 0))) AS `rescheduled`
FROM interviews LEFT JOIN tr ON interviews.`tr_id` = tr.`id`
LEFT JOIN lookup_reed_periods ON date_mysql = interviews.interview_date
LEFT JOIN lookup_interview_status ON interviews.`interview_status` = lookup_interview_status.id
LEFT JOIN lookup_interview_types ON interviews.`interview_type` = lookup_interview_types.id
GROUP BY period, lookup_interview_types.id;

SQL;

			// Create new view object

			$view = $_SESSION[$key] = new ViewInterviewsSummary();
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

			$options = "SELECT id, legal_name, null, CONCAT('WHERE tr.provider_id=',char(39),id,char(39)) FROM organisations WHERE organisation_type = 3 ORDER BY legal_name";
			$f = new DropDownViewFilter('filter_provider', $options, null, true);
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

			$options = "SELECT id, legal_name, null, CONCAT('WHERE tr.employer_id=',char(39),id,char(39)) FROM organisations WHERE organisation_type = 2 ORDER BY legal_name";
			$f = new DropDownViewFilter('filter_employer', $options, null, true);
			$f->setDescriptionFormat("Provider: %s");
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
				0=>array(0, 'Show All', null, null),
				1=>array(1, 'Reed', null, 'WHERE tr.provider_id=' . $reed_in_partnership_id),
				2=>array(2, 'Supply Chain', null, 'WHERE tr.provider_id!=' . $reed_in_partnership_id)
			);
			$f = new DropDownViewFilter('filter_type', $options, 0, false);
			$f->setDescriptionFormat("Type: %s");
			$view->addFilter($f);

			$options = "SELECT id, description, null, CONCAT('WHERE interviews.interview_paperwork=',char(39),id,char(39)) FROM lookup_interview_paperwork ORDER BY description";
			$f = new DropDownViewFilter('filter_interview_paperwork', $options, null, true);
			$f->setDescriptionFormat("Interview Paperwork: %s");
			$view->addFilter($f);

		}

		return $_SESSION[$key];
	}

	public function render(PDO $link, $columns)
	{
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead>';
			echo '<tr>';
			foreach($columns as $column)
			{
				if(DB_NAME != "am_reed_demo" && $column == 'event_reed_period')
					$column = 'event_period';
				echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}
			echo '<th>Grand Total</th>';
			echo '</tr></thead>';
			echo '<tbody>';
			$event_total = 0;
			$event_period = "";
			$booked_total = 0;
			$attended_total = 0;
			$attended_late_total = 0;
			$auth_absence_total = 0;
			$cancelled_total = 0;
			$failed_total = 0;
			$rescheduled_total = 0;
			$first_row = true;
			$event_period = "";
			$set = 1;
			while($row = $st->fetch())
			{
				$row_total = 0;
				if($event_period != $row['event_reed_period'] && !$first_row)
				{
					echo '<tr><td><span title="click to show/hide detail" class="button" id="set_' . $set . '" onclick="showComments(this);">+/-</span> &nbsp;&nbsp;&nbsp;' . $event_period . '</td><td></td><td align="center"><strong>' . $booked_total . '</strong></td><td align="center"><strong>' . $attended_total . '</strong></td><td align="center"><strong>' . $attended_late_total . '</strong></td><td align="center"><strong>' . $auth_absence_total . '</strong></td><td align="center"><strong>' . $cancelled_total . '</strong></td><td align="center"><strong>' . $failed_total . '</strong></td><td align="center"><strong>' . $rescheduled_total . '</strong></td><td align="center"><strong>' . $event_total . '</strong></td></tr>';
					$event_total = 0;
					$event_period = "";
					$booked_total = 0;
					$attended_total = 0;
					$attended_late_total = 0;
					$auth_absence_total = 0;
					$cancelled_total = 0;
					$failed_total = 0;
					$rescheduled_total = 0;
					$set++;
				}
				echo '<tr group="' . $set . '" style="display: none;">';
				foreach($columns as $column)
				{
					echo '<td align="center">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
					if($column != "event_reed_period" && $column != "event_name")
						$row_total += ((isset($row[$column]))?(($row[$column]=='')?0:$row[$column]):0);
				}

				$booked_total += $row['booked'];
				$attended_total += $row['attended'];
				$attended_late_total += $row['attended_late'];
				$auth_absence_total += $row['authorised_absence'];
				$cancelled_total += $row['cancelled'];
				$failed_total += $row['failed_to_attend'];
				$rescheduled_total += $row['rescheduled'];

				echo '<td align="center">' . $row_total . "</td>";
				echo '</tr>';

				$event_total += $row_total;
				$event_period = $row['event_reed_period'];

				$first_row = false;
			}

			echo '<tr><td><span title="click to show/hide detail" class="button" id="set_' . $set . '" onclick="showComments(this);">+/-</span> &nbsp;&nbsp;&nbsp;' . $event_period . '</td><td></td><td align="center"><strong>' . $booked_total . '</strong></td><td align="center"><strong>' . $attended_total . '</strong></td><td align="center"><strong>' . $attended_late_total . '</strong></td><td align="center"><strong>' . $auth_absence_total . '</strong></td><td align="center"><strong>' . $cancelled_total . '</strong></td><td align="center"><strong>' . $failed_total . '</strong></td><td align="center"><strong>' . $rescheduled_total . '</strong></td><td align="center"><strong>' . $event_total . '</strong></td></tr>';
			echo '</tbody></table></div>';
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

	}



}
?>