<?php
class read_attendance_module_group implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=read_attendance_module_group&id=" . $id, "View Attendance Module Group");

		if( ($id == '' || !is_numeric($id)) )
		{
			throw new Exception("You must specify a numeric id to view a group");
		}

		// Create Value Objects
		$dao = new AttendanceModuleGroupDAO($link);
		$g_vo = $dao->find((integer) $id); /* @var $g_vo AttendanceModuleGroupVO */
		$isSafeToDelete = $dao->isSafeToDelete($id);

		$m_vo = AttendanceModule::loadFromDatabase($link, $g_vo->module_id);

		$dao = new OrganisationDAO($link);
		$o_vo = $dao->find($link, (integer) $m_vo->provider_id); /* @var $o_vo OrganisationVO */

		$que = "select CONCAT(firstnames, ' ', surname) from users INNER JOIN organisations on organisations.id = users.employer_id where users.id='$g_vo->tutor'";
		$tutor = DAO::getSingleValue($link, $que);

		$que = "select CONCAT(firstnames, ' ', surname) from users INNER JOIN organisations on organisations.id = users.employer_id where users.id='$g_vo->old_tutor'";
		$old_tutor = DAO::getSingleValue($link, $que);

		$que = "select CONCAT(firstnames, ' ', surname) from users INNER JOIN organisations on organisations.id = users.employer_id where users.id='$g_vo->assessor'";
		$assessor = DAO::getSingleValue($link, $que);

		$que = "select CONCAT(firstnames, ' ', surname) from users INNER JOIN organisations on organisations.id = users.employer_id where users.id='$g_vo->verifier'";
		$verifier= DAO::getSingleValue($link, $que);

		$que = "select CONCAT(firstnames, ' ', surname) from users INNER JOIN organisations on organisations.id = users.employer_id where users.id='$g_vo->wbcoordinator'";
		$wbcoordinator= DAO::getSingleValue($link, $que);

		$que = "select legal_name from organisations where id='$m_vo->provider_id'";
		$training_provider= DAO::getSingleValue($link, $que);

		$view = View::getViewFromSession('primaryView', 'attendance_module_groups_read'); /* @var $view View */
		if(is_null($view))
		{
			$_SESSION['view'] = $view = $this->buildView($link, $id); /* @var $view View */
		}

		$view->refresh($link, $_REQUEST);

		// Presentation
		include('tpl_read_attendance_module_group.php');
	}

	private function buildView(PDO $link, $group_id)
	{
		// Create new view object
		$sql = <<<HEREDOC
SELECT tr.id AS tr_id,
	tr.surname, tr.firstnames, tr.gender, tr.id AS tr_id, tr.status_code,
	(SELECT organisations.`legal_name` FROM organisations WHERE organisations.id = tr.`provider_id`) AS legal_name,
	tr.gender,
	COUNT(DISTINCT lessons.id) AS scheduled_lessons,
	COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) AS registered_lessons,
	COUNT(IF(entry=1,1,NULL)) AS 'attendances',
	COUNT(IF(entry=2,1,NULL)) AS 'lates',
	COUNT(IF(entry=2,1,NULL)) AS 'very_lates',
	COUNT(IF(entry=3,1,NULL)) AS 'authorised_absences',
	COUNT(IF(entry=4,1,NULL)) AS 'unexplained_absences',
	COUNT(IF(entry=5,1,NULL)) AS 'unauthorised_absences',
	COUNT(IF(entry=6,1,NULL)) AS 'dismissals_uniform',
	COUNT(IF(entry=7,1,NULL)) AS 'dismissals_discipline',
	COUNT(IF(entry=8,1,NULL)) AS 'not_applicables'
FROM
	group_members INNER JOIN lessons INNER JOIN tr INNER JOIN attendance_module_groups
	ON group_members.groups_id = lessons.groups_id
	AND tr.id = group_members.tr_id
	AND group_members.groups_id = attendance_module_groups.id

	LEFT JOIN register_entries ON lessons.id = register_entries.`lessons_id` AND tr.id = register_entries.`pot_id`
WHERE attendance_module_groups.id = {$group_id}
GROUP BY tr.id
ORDER BY surname ASC, firstnames ASC
HEREDOC;

		$view = new View('attendance_module_groups_read', $sql);
		$view->setSql($sql);
		return $view;
	}
}
?>