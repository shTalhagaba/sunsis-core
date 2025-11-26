<?php
class ViewComplianceReport extends View
{

	public static function getInstance($link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$where = '';
			if($_SESSION['user']->type==20)
			{
				$id = $_SESSION['user']->id;
				$where = " where tr.programme = '$id'";
			}
			elseif($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER)
			{
				$emp = $_SESSION['user']->employer_id;
				$where = " where (tr.provider_id= '$emp' or tr.employer_id='$emp')" ;
			}
			$approved_date = "";
			if(DB_NAME=="am_platinum")
				$approved_date = " ,student_events.`approved_date` ";
			$sql = <<<HEREDOC
SELECT DISTINCT
  tr.id AS tr_id,
  CONCAT(tr.surname, ', ', tr.firstnames) AS learner_name,
  lookup_programme_type.description AS programme_type,
  student_frameworks.`title` AS programme,
  tr.`dob`,
  tr.`l03`,
  tr.`start_date`,
  tr.`target_date`,
  tr.`closure_date`,
  employers.legal_name AS employer,
  CONCAT(acs.firstnames, ' ', acs.surname) AS apprentice_coordinator,
  CONCAT(assessors.firstnames, assessors.surname) AS assessor,
  CONCAT(tutors.firstnames, tutors.surname) AS tutor,
  providers.`legal_name` AS training_provider,
  events_template.`title` AS compliance_event,
  student_events.`event_date` AS compliance_date,
  student_events.`comments` AS compliance_comments,
  concat(changer.firstnames,' ',changer.surname) as audit
  $approved_date
FROM
  courses_tr
  LEFT JOIN tr ON tr.id = courses_tr.tr_id
  LEFT JOIN courses ON courses.id = courses_tr.course_id
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN events_template ON events_template.provider_id = tr.provider_id
    AND (events_template.course_id = courses_tr.course_id OR events_template.course_id = 0 OR events_template.course_id IS NULL)
    AND (IF(courses.programme_type = 5,2 = events_template.programme_type,IF(courses.programme_type > 2,1 = events_template.programme_type,courses.programme_type = events_template.programme_type)))
  LEFT JOIN student_events ON student_events.event_id = events_template.id AND student_events.tr_id = tr.id
  LEFT JOIN lookup_programme_type ON lookup_programme_type.code = courses.programme_type
  LEFT JOIN organisations AS employers ON employers.id = tr.`employer_id`
  LEFT JOIN users AS acs ON acs.id = tr.programme
  LEFT JOIN users AS assessors ON assessors.id = tr.assessor
  LEFT JOIN users AS tutors ON tutors.id = tr.tutor
  LEFT JOIN users AS changer on changer.id = student_events.audit
  LEFT JOIN organisations AS providers ON providers.id = tr.`provider_id`
  LEFT JOIN group_members ON tr.id = group_members.`tr_id`
  LEFT JOIN groups ON group_members.`groups_id` = groups.`id`
  LEFT JOIN users AS group_assessors ON groups.`assessor` = group_assessors.id
$where

HEREDOC;

			$view = $_SESSION[$key] = new ViewComplianceReport();
			$view->setSQL($sql);


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
				0=>array(1, 'Assessor', null, 'ORDER BY assessor'),
				1=>array(2, 'L03', null, 'ORDER BY l03'),
				2=>array(3, 'Leaner', null, 'ORDER BY learner_name, tr.id, events_template.id'));

			$f = new DropDownViewFilter('order_by', $options, 3, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

			$options = "SELECT username, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE tr.assessor=',char(39),id,char(39),' or tr.assessor=' , char(39),id, char(39)) FROM users where type=" . User::TYPE_ASSESSOR . " ORDER BY users.firstnames";
			$f = new DropDownViewFilter('filter_assessor', $options, null, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);

			$options = "SELECT DISTINCT groups.`assessor`, CONCAT(users.`firstnames`, ' ' , users.`surname`), null, CONCAT('WHERE groups.assessor=', CHAR(39), groups.`assessor`, CHAR(39)) FROM groups INNER JOIN users ON groups.`assessor` = users.id ORDER BY users.`firstnames`;";
			$f = new DropDownViewFilter('filter_group_assessor', $options, null, true);
			$f->setDescriptionFormat("Group Assessor: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('surname', "WHERE tr.surname LIKE '%s%%'", null);
			$f->setDescriptionFormat("Surname: %s");
			$view->addFilter($f);

			/*
						 * re: Updated to use lookup_programme_type table #21814
						 */
			$options = "SELECT code, description, null, CONCAT('WHERE courses.programme_type=',code) FROM lookup_programme_type order by description asc ";
			$f = new DropDownViewFilter('filter_programme_type', $options, null, true);
			$f->setDescriptionFormat("Programme Type: %s");
			$view->addFilter($f);

			// Start Date Filter
			$format = "WHERE tr.start_date >= '%s'";
			$f = new DateViewFilter('start_date_start', $format, '');
			$f->setDescriptionFormat("From start date: %s");
			$view->addFilter($f);

			$format = "WHERE tr.start_date <= '%s'";
			$f = new DateViewFilter('start_date_end', $format, '');
			$f->setDescriptionFormat("To start date: %s");
			$view->addFilter($f);

			// Planned end date Filter
			$format = "WHERE tr.target_date >= '%s'";
			$f = new DateViewFilter('end_date_start', $format, '');
			$f->setDescriptionFormat("From end date: %s");
			$view->addFilter($f);

			$format = "WHERE tr.target_date <= '%s'";
			$f = new DateViewFilter('end_date_end', $format, '');
			$f->setDescriptionFormat("To end date: %s");
			$view->addFilter($f);

			// Actual end date Filter
			$format = "WHERE tr.closure_date >= '%s'";
			$f = new DateViewFilter('actual_end_date_start', $format, '');
			$f->setDescriptionFormat("From actual end date: %s");
			$view->addFilter($f);

			$format = "WHERE tr.closure_date <= '%s'";
			$f = new DateViewFilter('actual_end_date_end', $format, '');
			$f->setDescriptionFormat("To actual end date: %s");
			$view->addFilter($f);

			// Compliance Date Filter
			$format = "WHERE student_events.event_date >= '%s'";
			$f = new DateViewFilter('event_date_start', $format, '');
			$f->setDescriptionFormat("From event date: %s");
			$view->addFilter($f);

			$format = "WHERE student_events.event_date <= '%s'";
			$f = new DateViewFilter('event_date_end', $format, '');
			$f->setDescriptionFormat("To event date: %s");
			$view->addFilter($f);


			// Add view filters
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

			$options = "SELECT distinct title, title, null, CONCAT('WHERE events_template.title=',char(39),title,char(39)) FROM events_template";
			$f = new DropDownViewFilter('filter_events', $options, null, true);
			$f->setDescriptionFormat("Compliance Event: %s");
			$view->addFilter($f);

			$options = "SELECT distinct id, title, null, CONCAT('WHERE tr.contract_id=',char(39),id,char(39)) FROM contracts";
			$f = new DropDownViewFilter('filter_contract', $options, null, true);
			$f->setDescriptionFormat("Contract: %s");
			$view->addFilter($f);

//			$options = "SELECT DISTINCT id, title, null, CONCAT('WHERE events_template.course_id=',id) FROM courses order by courses.title";
			$options = "SELECT DISTINCT id, title, null, CONCAT('WHERE events_template.course_id=',id) FROM courses order by courses.title";
			$f = new DropDownViewFilter('filter_course', $options, null, true);
			$f->setDescriptionFormat("Course: %s");
			$view->addFilter($f);

			// Compliance Status 
			if(DB_NAME=='am_gigroup')
				$choice = 0;
			else
				$choice = 2;
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'Compliant', null, 'WHERE student_events.event_id is not null'),
				2=>array(2, 'Missed', null, ' WHERE student_events.event_id is null'));
			$f = new DropDownViewFilter('filter_compliance_status', $options, $choice, false);
			$f->setDescriptionFormat("Compliance Status: %s");
			$view->addFilter($f);

			// Provider Filter
			if($_SESSION['user']->type == User::TYPE_MANAGER)
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE providers.id=",id) FROM organisations WHERE organisation_type LIKE "%3%" AND organisations.id = ' . $_SESSION['user']->employer_id . ' ORDER BY legal_name';
			else
				$options = "SELECT id, legal_name, null, CONCAT('WHERE providers.id=',id) FROM organisations WHERE organisation_type like '%3%' order by legal_name";
			$f = new DropDownViewFilter('filter_provider', $options, null, true);
			$f->setDescriptionFormat("Training Provider: %s");
			$view->addFilter($f);

			// Employer Filter
			if($_SESSION['user']->type == User::TYPE_MANAGER)
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE employers.id=",id) FROM organisations WHERE organisation_type LIKE "%2%" AND organisations.parent_org = ' . $_SESSION['user']->employer_id . ' ORDER BY legal_name';
			else
				$options = "SELECT id, legal_name, null, CONCAT('WHERE  employers.id=',id) FROM organisations WHERE organisation_type like '%2%' order by legal_name";
			$f = new DropDownViewFilter('filter_employer', $options, null, true);
			$f->setDescriptionFormat("Employer: %s");
			$view->addFilter($f);

			// ULN Filter
			$f = new TextboxViewFilter('filter_uln', "WHERE tr.uln LIKE '%s%%'", null);
			$f->setDescriptionFormat("Learner ULN: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
			$f->setDescriptionFormat("L03: %s");
			$view->addFilter($f);


		}

		return $_SESSION[$key];
	}


	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st)
		{
			//pre($this->getSQL());
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			$approved_date_header = "";
			if(DB_NAME=="am_platinum")
				$approved_date_header = '<th>Approved Date</th>';
			echo <<<HEREDOC
	<thead>
		<tr>
			<th>Learner Name</th>
			<th>Programme Type</th>
			<th>Programme</th>
			<th>Date of Birth </th>
			<th>L03</th>
			<th>Start date</th>
			<th>Target date</th>
			<th>Actual end date</th>
			<th>Employer</th>
			<th>Apprentice Coordinator</th>
			<th>Assessor</th>
			<th>Tutor</th>
			<th>Training Provider</th>
			<th>Compliance Event</th>
			<th>Compliance Date</th>
			$approved_date_header
			<th>Compliance Comments</th>
			<th>Audit</th>
		</tr>
	</thead>
HEREDOC;

			echo '<tbody>';
			while($row = $st->fetch())
			{

//				$d = strtotime($row['review_date']);
//				$c = strtotime(date("Y-m-d"));
//				$color='blue';
//				if ( $d < $c ) { $color='red' ; }
				//if ( $d > $c ) { $color='blue' ; }

//				if ( preg_match("/LSC/i",$row['contract']) ) { $contract = "LSC"; }
//				if ( preg_match("/Scottish/i",$row['contract'] ) ) { $contract = "Scottish"; }

//				echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&amp;id=' . $row['tr_id'] . '&amp;contract=' . $row['contract_id']);
				echo '<tr style="font-size:8pt">';
				echo '<td align="left"><a href=do.php?_action=read_training_record&amp;id='. $row['tr_id'] . ' ><span style="color: black"> ' . HTML::cell($row['learner_name']) . '</span></a></td>';

				echo '<td align="left">' . HTML::cell($row['programme_type']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['programme']) . '</td>';
				echo '<td align="left">' . HTML::cell(Date::toMedium($row['dob'])) . '</td>';
				echo '<td align="left">' . HTML::cell($row['l03']) . '</td>';
				echo '<td align="left">' . HTML::cell(Date::toMedium($row['start_date'])) . '</td>';

				$cd = new Date(date('Y-m-d'));
				$pd = new Date($row['target_date']);

				if($cd->getDate()>$pd->getDate())
					echo '<td align="left"><span style="color:red">' . HTML::cell(Date::toMedium($row['target_date'])) . '</span></td>';
				else
					echo '<td align="left">' . HTML::cell(Date::toMedium($row['target_date'])) . '</td>';
				echo '<td align="left">' . HTML::cell(Date::toMedium($row['closure_date'])) . '</td>';
				echo '<td align="left">' . HTML::cell($row['employer']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['apprentice_coordinator']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['assessor']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['tutor']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['training_provider']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['compliance_event']) . '</td>';
				echo '<td align="left">' . HTML::cell(Date::toMedium($row['compliance_date'])) . '</td>';
				if(DB_NAME=="am_platinum")
					echo '<td align="left">' . HTML::cell(Date::toMedium($row['approved_date'])) . '</td>';
				echo '<td align="left" style="width: 100px;">' . HTML::cell($row['compliance_comments']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['audit']) . '</td>';

				echo '</tr>';
			}
			echo '</tbody></table></div align="center">';
			echo $this->getViewNavigator();

		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

	}
}
?>