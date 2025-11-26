<?php
class view_attendance_module_students implements IAction
{
	public function execute(PDO $link)
	{

		$id = isset($_GET['id']) ? $_GET['id'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=view_attendance_module_students&id=" . $id, "Module Learners");

		if($id == '' || !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring");
		}

		// Create Value Objects
		$m_vo = AttendanceModule::loadFromDatabase($link, $id);

		$m_students_vo = ViewAttendanceModuleStudents::getInstance($link, $id);
		$m_students_vo->refresh($link, $_REQUEST);

		require_once('tpl_view_attendance_module_students.php');
	}


	/*private function buildView(PDO $link, $attendance_module_id)
	{
		// Create view object

		if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12)
		{
			$where = '';
		}
		elseif($_SESSION['user']->isOrgAdmin())
		{
			$emp = $_SESSION['user']->employer_id;
			$where = ' and tr.provider_id= '. $emp;
		}
		elseif($_SESSION['user']->type==2)
		{
			$id = $_SESSION['user']->id;
			$where = ' and (attendance_module_groups.tutor = '. '"' . $id . '"' . '")';
		}
		elseif($_SESSION['user']->type==3)
		{
			$id = $_SESSION['user']->id;
			$where = ' and (attendance_module_groups.assessor = '. '"' . $id . '" or tr.assessor="' . $id . '")';
		}
		elseif($_SESSION['user']->type==4)
		{
			$id = $_SESSION['user']->id;
			$where = ' and attendance_module_groups.verifier = '. '"' . $id . '"';
		}

		$sql = <<<HEREDOC
select
	tr.surname, tr.firstnames, tr.gender, organisations.short_name AS school_name,
	tr.id as pot_id, tr.status_code, tr.username,

	sum(student_qualifications.units) as units,
	(sum(student_qualifications.units) - sum(student_qualifications.unitsBehind) - sum(student_qualifications.unitsOnTrack) - sum(student_qualifications.unitsCompleted)) as unitsNotStarted,
	sum(student_qualifications.unitsBehind) as unitsBehind,
	sum(student_qualifications.unitsOnTrack) as unitsOnTrack,
	sum(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)) as unitsUnderAssessment,
	sum(student_qualifications.unitsCompleted) as unitsCompleted,

	contracts.title,
	contracts.id as cid,

	tr.`scheduled_lessons`,
	tr.`registered_lessons`,
	tr.`attendances`,
	tr.`lates`,
	tr.`authorised_absences`,
	tr.`unexplained_absences`,
	tr.`unauthorised_absences`,
	tr.`dismissals_uniform`,
	tr.`dismissals_discipline`,
	(tr.attendances+
	tr.lates+
	tr.authorised_absences+
	tr.unexplained_absences+
	tr.unauthorised_absences+
	tr.dismissals_uniform+
	tr.dismissals_discipline) as `total`
FROM
	tr
	LEFT JOIN group_members ON group_members.tr_id = tr.id
	LEFT JOIN attendance_modules ON attendance_module_groups ON attendance_module_groups.groups_id = attendance_modules.id
	LEFT JOIN organisations	ON (tr.employer_id=organisations.id)
	LEFT JOIN users ON users.username = tr.username
	LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT JOIN student_qualifications ON tr.id = student_qualifications.tr_id
	LEFT JOIN contracts on contracts.id = tr.contract_id
WHERE
	attendance_modules.id = $attendance_module_id
$where
Group By
	student_qualifications.tr_id
HEREDOC;

		$view = new VoltView('view_attendance_module_students', $sql);

		$options = "SELECT id, legal_name, null, CONCAT('WHERE organisations.id=', id) FROM organisations;";
		$f = new VoltDropDownViewFilter('filter_organisation', $options, null, true);
		$f->setDescriptionFormat("Organisation: %s");
		$view->addFilter($f);

		// Add paging filter
		$options = array(
			0=>array(30,30,null,null),
			1=>array(50,50,null,null),
			2=>array(70,70,null,null),
			3=>array(100,100,null,null),
			4=>array(0, 'No limit', null, null) );
		$f = new VoltDropDownViewFilter(VoltView::KEY_PAGE_SIZE, $options, 30, false);
		$f->setDescriptionFormat("Records per page: %s");
		$view->addFilter($f);

		// Add ordering filter
		$options = array(0=>array(1, 'Learner (asc)', null, 'ORDER BY tr.surname ASC, tr.firstnames ASC'),
			1=>array(2, 'Learner (desc)', null, 'ORDER BY tr.surname DESC, tr.firstnames DESC'),
			2=>array(3, 'Organisation (asc), Learner (asc)', null, 'ORDER BY organisations.short_name ASC, tr.surname ASC, tr.firstnames ASC'));
		$f = new VoltDropDownViewFilter(VoltView::KEY_ORDER_BY, $options, 1, false);
		$f->setDescriptionFormat("Sort by: %s");
		$view->addFilter($f);

		// Add preferences
		$view->setPreference('showAttendanceStats', '1');
		$view->setPreference('showProgressStats', '0');
		$view->setPreference('course_id', $attendance_module_id);

		return $view;
	}*/
}
?>