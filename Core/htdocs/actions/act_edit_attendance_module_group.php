<?php
class edit_attendance_module_group implements IAction
{
	public function execute(PDO $link)
	{
		// Retrieve values from user input
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		$module_id = isset($_GET['module_id']) ? $_GET['module_id'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=edit_attendance_module_group&id=" . $id . "&module_id=" . $module_id, "Add/Edit Module Group");

		// Specified IDs must be numeric
		if($id !== '' && !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring in order to edit this record");
		}
		if( $module_id !== '' && !is_numeric($module_id) )
		{
			throw new Exception("You must specify a numeric module id");
		}

		if($id == '')
		{
			// New record
			$g_vo = new AttendanceModuleGroupVO();
			$g_vo->module_id = $module_id;
		}
		else
		{
			$dao = new AttendanceModuleGroupDAO($link);
			$g_vo = $dao->find((integer)$id); /* @var $g_vo AttendanceModuleGroupVO */
		}

		$m_vo = AttendanceModule::loadFromDatabase($link, $g_vo->module_id);

		$tutor_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), '')
	),
	NULL
FROM
	users
WHERE
	employer_id={$m_vo->provider_id} and type=2
ORDER BY
	firstnames;
HEREDOC;


			$assessor_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(firstnames, ' ', surname),
	NULL
FROM
	users
INNER JOIN organisations on organisations.id = users.employer_id 
where (employer_id={$m_vo->provider_id} or organisations.organisation_type = 1) and type=3
order by firstnames
HEREDOC;

			$verifier_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(firstnames, ' ', surname),
	NULL
FROM
	users

INNER JOIN organisations on organisations.id = users.employer_id 
where employer_id={$m_vo->provider_id} and type=4
order by firstnames
HEREDOC;

		$tutor_select = DAO::getResultset($link, $tutor_sql);
		$assessor_select = DAO::getResultset($link, $assessor_sql);
		$verifier_select = DAO::getResultset($link, $verifier_sql);
		$wbcoordinator_select = DAO::getResultset($link, "SELECT users.id, CONCAT(firstnames, ' ', surname),null from users INNER JOIN organisations on organisations.id = users.employer_id where type='6'");

		if($_SESSION['user']->isAdmin())
			$training_providers = DAO::getResultset($link, "SELECT id, legal_name, null FROM organisations WHERE organisation_type like '%3%' order by legal_name");
		else
			$training_providers = DAO::getResultset($link, "SELECT id, legal_name, null FROM organisations WHERE id = " . $_SESSION['user']->employer_id . " order by legal_name");

		$view = $this->getMembersViewEdit($link, $id, $module_id);

		// Cancel button URL
		if($g_vo->id == 0)
		{
			$js_cancel = "window.location.replace('do.php?_action=view_attendance_module_group&module_id=" . $m_vo->id . "');";
		}
		else
		{
			$js_cancel = "window.location.replace('do.php?_action=read_attendance_module_group&id={$g_vo->id}');";
		}

		// Presentation
		include('tpl_edit_attendance_module_group.php');
	}


	private function getMembersViewEdit(PDO $link, $group_id, $module_id)
	{
		$attendance_module = AttendanceModule::loadFromDatabase($link, $module_id);
		$view = null;

		// Retrieve module members view
/*		if(!array_key_exists('view', $_SESSION)
			|| (array_key_exists('view', $_SESSION) && !($_SESSION['view'] instanceof VoltView))
			|| (array_key_exists('view', $_SESSION) && ($_SESSION['view']->getViewName() != 'attendance_module_groups_edit')) )*/
		{

			$order_by = " surname ASC, firstnames ASC";

			// Create new view object
			if(is_null($group_id) || $group_id == '')
			{
				$sql = <<<HEREDOC
SELECT
	tr.id AS tr_id, tr.surname, tr.firstnames, tr.gender, tr.id as pot_id, tr.status_code,
	organisations.legal_name AS short_name, student_frameworks.title as ftitle,
	users.enrollment_no,
	tr.gender,
	tr.start_date,
	tr.target_date,
	student_qualifications.start_date AS qual_start_date,
	(SELECT courses.title FROM courses WHERE courses.id IN (SELECT DISTINCT course_id FROM courses_tr WHERE courses_tr.tr_id = tr.id)) AS course
FROM
	tr
	INNER JOIN student_qualifications ON tr.id = student_qualifications.tr_id AND REPLACE(student_qualifications.id, '/', '') = '$attendance_module->qualification_id'
	LEFT JOIN organisations on organisations.id = tr.employer_id
	LEFT JOIN group_members ON (tr.employer_id=organisations.id AND group_members.tr_id = tr.id)
	LEFT JOIN attendance_module_groups ON attendance_module_groups.id = group_members.groups_id
	LEFT JOIN attendance_modules ON attendance_modules.id =  attendance_module_groups.module_id
	LEFT JOIN student_frameworks on student_frameworks.tr_id = tr.id
	LEFT JOIN users on tr.username = users.username
WHERE
	student_qualifications.actual_end_date IS NULL
GROUP BY
	tr.id
ORDER BY
	$order_by
HEREDOC;
			}
			else
			{
				$sql = <<<HEREDOC
SELECT
	tr.id AS tr_id, tr.surname, tr.firstnames, tr.gender, tr.id as pot_id, tr.status_code,
	organisations.legal_name AS short_name, student_frameworks.title as ftitle,
	users.enrollment_no,
	tr.gender,
	tr.start_date,
	tr.target_date,
	student_qualifications.start_date AS qual_start_date,
	attendance_module_groups.title,
	(SELECT COUNT(*) FROM group_members WHERE group_members.tr_id=tr.id AND group_members.groups_id='$group_id') AS is_member,
	(SELECT courses.title FROM courses WHERE courses.id IN (SELECT DISTINCT course_id FROM courses_tr WHERE courses_tr.tr_id = tr.id)) AS course
FROM
	tr
	INNER JOIN student_qualifications ON tr.id = student_qualifications.tr_id AND REPLACE(student_qualifications.id, '/', '') = '$attendance_module->qualification_id'
	LEFT JOIN organisations on organisations.id = tr.employer_id
	LEFT JOIN group_members ON (tr.employer_id=organisations.id AND group_members.tr_id = tr.id)
	LEFT JOIN attendance_module_groups ON attendance_module_groups.id = group_members.groups_id
	LEFT JOIN attendance_modules ON attendance_modules.id =  attendance_module_groups.module_id
	LEFT JOIN student_frameworks on student_frameworks.tr_id = tr.id
	LEFT JOIN users on tr.username = users.username
WHERE
	student_qualifications.actual_end_date IS NULL
GROUP BY
	tr.id
ORDER BY
	is_member DESC, $order_by
HEREDOC;

		}
		$_SESSION['view'] = $view = new VoltView('attendance_module_groups_edit', $sql); /* @var $view View */
	}
		/*else
		{
			$view = $_SESSION['view'];
		}*/

		return $view;
	}

}
?>