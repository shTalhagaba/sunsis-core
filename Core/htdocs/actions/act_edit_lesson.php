<?php
class edit_lesson implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';

		$dao = new LessonDAO($link);
		$l_vo = $dao->find($id); /* @var $l_vo LessonVO */
		$is_safe_to_delete = $dao->isSafeToDelete($id);
		
		$dao = new CourseGroupDAO($link);
		$g_vo = $dao->find($l_vo->groups_id); /* @var $g_vo CourseGroupVO */
		
		$c_vo = Course::loadFromDatabase($link, $g_vo->courses_id);

        if(DB_NAME!='am_reed_demo' && DB_NAME!='am_reed')
        {
            $dao = new OrganisationDAO($link);
            $p_vo = $dao->find($link, $c_vo->organisations_id); /* @var $p_vo OrganisationVO */
            $groups = DAO::getResultset($link, 'SELECT id, title, null FROM groups WHERE courses_id=' . $c_vo->id);
            $personnel_sql = <<<HEREDOC
SELECT
	username,
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
	employer_id={$c_vo->organisations_id}
ORDER BY
	surname, firstnames, department;
HEREDOC;
            $personnel = DAO::getResultset($link, $personnel_sql);
            $locations = DAO::getResultset($link, "SELECT id, full_name, null FROM locations WHERE organisations_id=" . $c_vo->organisations_id . " ORDER BY is_legal_address DESC;");
        }
        else
        {
            $groups = DAO::getResultset($link, 'SELECT id, title, null FROM groups WHERE courses_id=' . $_SESSION['user']->employer_id);
            $personnel_sql = <<<HEREDOC
SELECT
	username,
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
	employer_id={$_SESSION['user']->employer_id}
ORDER BY
	surname, firstnames, department;
HEREDOC;
            $personnel = DAO::getResultset($link, $personnel_sql);
            $locations = DAO::getResultset($link, "SELECT id, full_name, null FROM locations WHERE organisations_id=" . $g_vo->courses_id . " ORDER BY is_legal_address DESC;");

        }
		
		// Drop down list arrays
		include "templates/tpl_edit_lesson.php";
	}

}
?>