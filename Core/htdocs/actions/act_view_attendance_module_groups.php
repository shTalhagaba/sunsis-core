<?php
class view_attendance_module_groups implements IAction
{
	public function execute(PDO $link)
	{
		$module_id = isset($_REQUEST['module_id']) ? $_REQUEST['module_id'] : '';

		if($module_id == '')
		{
			throw new Exception("Missing querystring argument: module_id");
		}

		$_SESSION['bc']->add($link, "do.php?_action=view_attendance_module_groups&module_id=" . $module_id, "View Module Groups");

		$view = $this->buildView($link, $module_id);

		$view->refresh($_REQUEST, $link);

		// Create Value Objects
		$m_vo = AttendanceModule::loadFromDatabase($link, $module_id);

		require_once('tpl_view_attendance_module_groups.php');
	}

	private function buildView(PDO $link, $module_id)
	{

		if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12 || $_SESSION['user']->type==15)
		{
			$where = '';
		}
		elseif($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==13 || $_SESSION['user']->type==14)
		{
			$emp = $_SESSION['user']->employer_id;
			$where = '';
		}
		elseif($_SESSION['user']->type==2)
		{
			$id = $_SESSION['user']->id;
			$where = ' and (attendance_module_groups.tutor = "' . $id . '") ';
		}
		elseif($_SESSION['user']->type==3)
		{
			$id = $_SESSION['user']->id;
			$where = ' and attendance_module_groups.assessor = '. '"' . $id . '"';
		}
		elseif($_SESSION['user']->type==4)
		{
			$id = $_SESSION['user']->id;
			$where = ' and attendance_module_groups.verifier = '. '"' . $id . '"';
		}
		elseif($_SESSION['user']->type==20)
		{
			$username = $_SESSION['user']->username;
			$where = ' and attendance_module_groups.assessor in (select assessor from tr where programme = '. '"' . $username . '")';
		}

		// Create new view object
		$sql = <<<HEREDOC
SELECT DISTINCT
	attendance_module_groups.id, attendance_module_groups.title, attendance_module_groups.tutor, users.firstnames as tutor_firstnames,
	users.surname as tutor_surname,
	assessors.firstnames as afirstnames,
	assessors.surname as asurname,
	attendance_module_groups.`scheduled_lessons`,
	attendance_module_groups.`registered_lessons`,
	attendance_module_groups.`attendances`,
	attendance_module_groups.`lates`,
	attendance_module_groups.`very_lates`,
	attendance_module_groups.`authorised_absences`,
	attendance_module_groups.`unexplained_absences`,
	attendance_module_groups.`unauthorised_absences`,
	attendance_module_groups.`dismissals_uniform`,
	attendance_module_groups.`dismissals_discipline`,
	(attendance_module_groups.attendances+
	attendance_module_groups.lates+
	attendance_module_groups.very_lates+
	attendance_module_groups.authorised_absences+
	attendance_module_groups.unexplained_absences+
	attendance_module_groups.unauthorised_absences+
	attendance_module_groups.dismissals_uniform+
	attendance_module_groups.dismissals_discipline) as `total`,
	(SELECT COUNT(*) FROM group_members WHERE group_members.groups_id = attendance_module_groups.id) AS student_count,
	(SELECT COUNT(*) FROM group_members INNER JOIN tr ON group_members.`tr_id` = tr.id WHERE group_members.groups_id = attendance_module_groups.id AND tr.status_code = 1) AS active,
	(SELECT COUNT(*) FROM group_members INNER JOIN tr ON group_members.`tr_id` = tr.id WHERE group_members.groups_id = attendance_module_groups.id AND tr.status_code = 2) AS successful,
	(SELECT COUNT(*) FROM group_members INNER JOIN tr ON group_members.`tr_id` = tr.id WHERE group_members.groups_id = attendance_module_groups.id AND tr.status_code = 3) AS unsuccessful,
	(SELECT COUNT(*) FROM group_members INNER JOIN tr ON group_members.`tr_id` = tr.id WHERE group_members.groups_id = attendance_module_groups.id AND tr.status_code > 3) AS withdrawn
FROM
	attendance_module_groups
	LEFT OUTER JOIN users ON attendance_module_groups.tutor=users.id
	LEFT JOIN attendance_modules on attendance_modules.id = attendance_module_groups.module_id
	LEFT JOIN users as assessors on assessors.id = attendance_module_groups.assessor
WHERE
	attendance_module_groups.module_id = $module_id
$where
HEREDOC;

		$view = new VoltView('view_attendance_module_groups', $sql); /* @var $view View */

		// Add view filters
		$options = array(
			0=>array(10,10,null,null),
			1=>array(20,20,null,null),
			2=>array(50,50,null,null),
			3=>array(0, 'No limit', null, null) );
		$f = new VoltDropDownViewFilter(VoltView::KEY_PAGE_SIZE, $options, 0, false);
		$f->setDescriptionFormat("Records per page: %s");
		$view->addFilter($f);

		$options = array(0=>array(1, 'Group title (asc)', null, 'ORDER BY attendance_module_groups.title ASC'),
			1=>array(2, 'Group title (desc)', null, 'ORDER BY attendance_module_groups.title DESC'));
		$f = new VoltDropDownViewFilter(VoltView::KEY_ORDER_BY, $options, 1, false);
		$f->setDescriptionFormat("Sort by: %s");
		$view->addFilter($f);


		return $view;
	}
}
?>