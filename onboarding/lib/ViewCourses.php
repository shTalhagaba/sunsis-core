<?php
class ViewCourses extends View
{
	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			$sql = new SQLStatement("
SELECT
	courses.id AS c_id, courses.title AS course_title,
	DATE_FORMAT(courses.course_start_date, '%d/%m/%Y') AS start_date,
	DATE_FORMAT(courses.course_end_date, '%d/%m/%Y') AS end_date,
	providers.short_name AS provider, providers.id AS p_id,
	frameworks.title AS framework,
	(SELECT ProgTypeDesc FROM lars201718.CoreReference_LARS_ProgType_Lookup WHERE ProgType=frameworks.`framework_type`) AS `type`,
	IF(
		frameworks.`framework_type` = 25, 
		(SELECT LEFT(CONCAT(StandardCode, ' ' , StandardName),40) FROM lars201718.Core_LARS_Standard WHERE lars201718.Core_LARS_Standard.`StandardCode` = frameworks.`StandardCode` LIMIT 1), 
		(SELECT CONCAT(FworkCode, ' ', IssuingAuthorityTitle) FROM lars201718.`Core_LARS_Framework` WHERE FworkCode = frameworks.framework_code LIMIT 1)
	) AS `code`,
	(SELECT CONCAT(PwayCode, ' ' , PathwayName) FROM lars201718.`Core_LARS_Framework` WHERE PwayCode = frameworks.`PwayCode` AND FworkCode = frameworks.`framework_code` AND ProgType = frameworks.`framework_type`) AS pathway_code,
	courses.framework_id,

	courses.scheduled_lessons,
	courses.registered_lessons,
	courses.attendances,
	courses.lates,
	courses.very_lates,
	courses.authorised_absences,
	courses.unexplained_absences,
	courses.unauthorised_absences,
	courses.dismissals_uniform,
	courses.dismissals_discipline,
	(courses.attendances+
	courses.lates+
	courses.very_lates+
	courses.authorised_absences+
	courses.unexplained_absences+
	courses.unauthorised_absences+
	courses.dismissals_uniform+
	courses.dismissals_discipline) AS `total`,

	COUNT(DISTINCT tr.id) AS total,
	COUNT(IF(tr.status_code = 1, 1, NULL)) AS active,
	COUNT(IF(tr.status_code = 2, 1, NULL)) AS successful,
	COUNT(IF(tr.status_code = 3, 1, NULL)) AS unsuccessful,
	COUNT(IF(tr.status_code > 3, 1, NULL)) AS withdrawn

FROM
	courses
	LEFT JOIN organisations AS providers ON courses.organisations_id=providers.id
	LEFT JOIN courses_tr ON courses_tr.course_id = courses.id
	LEFT JOIN tr ON tr.id = courses_tr.tr_id
	LEFT JOIN frameworks ON frameworks.id = courses.framework_id

;
			");

			$sql->setClause("GROUP BY courses.id");

			if($_SESSION['user']->type == User::TYPE_TUTOR)
			{
				$sql->setClause("WHERE courses.id IN (SELECT groups.`courses_id` FROM groups WHERE groups.tutor = '{$_SESSION['user']->id}' OR groups.old_tutor = '{$_SESSION['user']->id}')");
			}
			if($_SESSION['user']->type == User::TYPE_ASSESSOR)
			{
				$sql->setClause("WHERE courses.id IN (SELECT groups.`assessor` FROM groups WHERE groups.assessor = '{$_SESSION['user']->id}' OR tr.assessor = '{$_SESSION['user']->id}')");
			}
			if($_SESSION['user']->type == User::TYPE_VERIFIER)
			{
				$sql->setClause("WHERE courses.id IN (SELECT groups.`verifier` FROM groups WHERE groups.verifier = '{$_SESSION['user']->id}' OR tr.verifier = '{$_SESSION['user']->id}')");
			}
			if($_SESSION['user']->type == User::TYPE_SUPERVISOR)
			{
				$assessors = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(\"\'\",id,\"\'\") FROM users WHERE supervisor = '{$_SESSION['user']->username}';");
				if ($assessors == '' || $assessors === null)
				{
					$sql->setClause("WHERE (1=2) ");
				}
				else
				{
					$sql->setClause("WHERE courses.id IN (SELECT groups.`assessor` FROM groups WHERE groups.assessor IN ({$assessors}) OR tr.assessor IN ({$assessors}))");
				}
			}
			if($_SESSION['user']->type == User::TYPE_APPRENTICE_COORDINATOR)
			{
				$sql->setClause("WHERE tr.programme = '{$_SESSION['user']->id}'");
			}
			if($_SESSION['user']->type == User::TYPE_COURSE_DIRECTOR)
			{
				$sql->setClause("WHERE FIND_IN_SET('{$_SESSION['']->username}', courses.director) ");
			}

			$view = $_SESSION[$key] = new ViewCourses();
			$view->setSQL($sql->__toString());

			$f = new TextboxViewFilter('filter_course_title', "WHERE courses.title LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Course Title: %s");
			$view->addFilter($f);

			if ($_SESSION['user']->type == User::TYPE_MANAGER)
				$options = "SELECT id, legal_name, null, CONCAT(\"WHERE providers.id=\",id) FROM organisations WHERE id = '{$_SESSION['user']->employer_id}'";
			else
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE providers.id=",id) FROM organisations WHERE organisation_type LIKE "%3%" ORDER BY legal_name;';
			$f = new DropDownViewFilter('filter_provider', $options, null, true);
			$f->setDescriptionFormat("Provider: %s");
			$view->addFilter($f);

			$options = "SELECT code, description, null, CONCAT('WHERE courses.programme_type=',code) FROM lookup_programme_type ORDER BY description";
			$f = new DropDownViewFilter('filter_programme_type', $options, null, true);
			$f->setDescriptionFormat("Programme Type: %s");
			$view->addFilter($f);

			$options = array(
				0 => array(1, 'All Courses', null, null),
				1 => array(2, 'Active Courses', null, 'WHERE  courses.active=1'),
				2 => array(3, 'Inactive Courses', null, 'WHERE courses.active<>1'));
			$f = new DropDownViewFilter('by_active', $options, 2, false);
			$f->setDescriptionFormat("Active: %s");
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
				0 => array(1, 'Course Title', null, 'ORDER BY course_title ASC'),
				1 => array(2, 'Start Date (desc), Course Title (asc)', null, 'ORDER BY courses.course_start_date DESC, course_title ASC'),
				2 => array(3, 'Provider Name', null, 'ORDER BY provider'),
				3 => array(4, 'Start Date (asc)', null, 'ORDER BY start_date ASC'),
				4 => array(5, 'Start Date (desc)', null, 'ORDER BY start_date DESC'));
			$f = new DropDownViewFilter(View::KEY_ORDER_BY, $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

			$view->setPreference('showAttendanceStats', '1');
			$view->setPreference('showStudentNumbers', '1');
		}

		return $_SESSION[$key];
	}

	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$st = DAO::query($link, $this->getSQL());
		if ($st) {
			echo $this->getViewNavigator();
			echo '<div align="center" class="table-responsive"><table class="table resultset">';
			echo '<thead><tr><th class="topRow">&nbsp;</th>';
			echo '<th class="topRow" colspan="7">Course Information</th>';
			if ($this->getPreference('showStudentNumbers') == '1')
			{
				echo '<th class="topRow" colspan="5">Training Records</th>';
			}
			if ($this->getPreference('showAttendanceStats') == '1')
			{
				echo '<th class="topRow" colspan="8">Attendance Statistics</th>';
			}
			echo '</tr><tr>';
			echo <<<HEREDOC
				<th class="bottomRow">&nbsp;</th>
				<th class="bottomRow">Provider</th>
				<th class="bottomRow">Course Title</th>
				<th class="bottomRow">Framework/Standard</th>
				<th class="bottomRow">Type</th>
				<th class="bottomRow">Code</th>
				<th class="bottomRow">Pathway Code</th>
				<th class="bottomRow">Dates</th>
HEREDOC;
			if($this->getPreference('showStudentNumbers') == '1')
			{
				echo <<<HEREDOC
				<th class="bottomRow small">Total</th>
				<th class="bottomRow small">Active</th>
				<th class="bottomRow small">Successful</th>
				<th class="bottomRow small">Unsuccessful</th>
				<th class="bottomRow small">Withdrawn</th>
HEREDOC;
			}



			echo '</tr></thead>';

			echo '<tbody>';

			while ($row = $st->fetch())
			{

				if(SOURCE_LOCAL || DB_NAME == "am_lead_demo" || DB_NAME == "am_lead")
					echo HTML::viewrow_opening_tag('do.php?_action=read_course_v2&id=' . $row['c_id']);
				else
					echo HTML::viewrow_opening_tag('do.php?_action=read_course&id=' . $row['c_id']);
				echo '<td><img src="/images/slate-apple.png" border="0" title="#'.$row['c_id'].'" /></td>';
				echo '<td align="left"><a href="do.php?_action=read_trainingprovider&id='.$row['p_id'].'">' . str_replace(' ', '&nbsp;', HTML::cell($row['provider'])) . '</a></td>';
				echo '<td class="small">' . HTML::cell($row['course_title']) . '</td>';
				echo '<td class="small"><a href="do.php?_action=view_framework_qualifications&id='.$row['framework_id'].'">' . HTML::cell($row['framework']) . '</a></td>';
				echo '<td class="small">' . HTML::cell($row['type']) . '</td>';
				echo '<td class="small">' . HTML::cell($row['code']) . '</td>';
				echo '<td class="small">' . HTML::cell($row['pathway_code']) . '</td>';
				echo '<td>' . HTML::cell($row['start_date'])
					. '<br/><span class="AttendancePercentage" style="color:gray">' . HTML::cell($row['end_date']) . '</span></td>';

				if($this->getPreference('showStudentNumbers'))
				{
					if(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo")
						$link_tr = "do.php?_action=view_training_records_v2&_reset=1&ViewTrainingRecordsV2_filter_course={$row['c_id']}&ViewTrainingRecordsV2_filter_record_status%5B%5D=";
					else
						$link_tr = "do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_course={$row['c_id']}&ViewTrainingRecords_filter_record_status%5B%5D=";
					echo '<td align="center"'.($row['total'] == 0?' style="color:silver" ':'').'><a href="'.$link_tr.'SHOW_ALL">'.$row['total'].'</a></td>';
					echo '<td align="center"'.($row['active'] == 0?' style="color:silver" ':'').'><a href="'.$link_tr.'1">'.$row['active'].'</td>';
					echo '<td align="center"'.($row['successful'] == 0?' style="color:silver" ':'').'><a href="'.$link_tr.'2">'.$row['successful'].'</td>';
					echo '<td class="text-red" align="center"'.($row['unsuccessful'] == 0?' style="color:silver" ':'').'><a href="'.$link_tr.'3">'.$row['unsuccessful'].'</td>';
					echo '<td align="center"'.($row['withdrawn'] == 0?' style="color:silver" ':'').'><a href="'.$link_tr.'6">'.$row['withdrawn'].'</td>';
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