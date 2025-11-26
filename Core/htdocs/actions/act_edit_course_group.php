<?php
class edit_course_group implements IAction
{
	public function execute(PDO $link)
	{
		// Retrieve values from user input
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : '';
		
		$_SESSION['bc']->add($link, "do.php?_action=edit_course_group&id=" . $id . "&course_id=" . $course_id, "Add/Edit Group");
		
		// Specified IDs must be numeric
		if($id !== '' && !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring in order to edit this record");
		}
		if( $course_id !== '' && !is_numeric($course_id) )
		{
			throw new Exception("You must specify a numeric course id");
		}
		
/*		if($id == '' && $course_id == '')
		{
			throw new Exception("If no group id is specified, you must specify a course id");
		}*/

        $provider_id = $_SESSION['user']->employer_id;
		if($id == '')
		{
			// New record
			$g_vo = new CourseGroupVO();
            if(DB_NAME=='ams' || DB_NAME=='am_reed_demo' || DB_NAME=='am_reed')
    			$g_vo->courses_id = $provider_id;
            else
                $g_vo->courses_id = $course_id;
		}
		else
		{
			$dao = new CourseGroupDAO($link);
			$g_vo = $dao->find((integer)$id); /* @var $g_vo CourseGroupVO */
		}
		
		$c_vo = Course::loadFromDatabase($link, $g_vo->courses_id);


		$dao = new OrganisationDAO($link);
        if(DB_NAME=='ams' || DB_NAME=='am_reed_demo' || DB_NAME=='am_reed')
    		$o_vo = $dao->find($link, (integer) $g_vo->courses_id); /* @var $o_vo OrganisationVO */
        else
            $o_vo = $dao->find($link, (integer) $c_vo->organisations_id); /* @var $o_vo OrganisationVO */

        if(DB_NAME=='ams' || DB_NAME=='am_reed_demo' || DB_NAME=='am_reed')
        {
            if($_SESSION['user']->isAdmin())
            {
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
	type=2
ORDER BY
	firstnames;
HEREDOC;
            }
            else
            {
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
	employer_id=$provider_id and type=2
ORDER BY
	surname, firstnames;
HEREDOC;

            }
if(DB_NAME=="am_baltic" or DB_NAME=='am_baltic_demo')
{
    if($_SESSION['user']->isAdmin())
    {
        $assessor_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(firstnames, ' ', surname),
	NULL
FROM
	users
INNER JOIN organisations on organisations.id = users.employer_id
where web_access = 1 and type!=5
order by firstnames
HEREDOC;
    }
    else
    {
        $assessor_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(firstnames, ' ', surname),
	NULL
FROM
	users
INNER JOIN organisations on organisations.id = users.employer_id
where (employer_id=$provider_id or organisations.organisation_type = 1) and type!=5 and web_access = 1
order by firstnames
HEREDOC;
    }

}
else
{
    if($_SESSION['user']->isAdmin())
    {
        $assessor_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(firstnames, ' ', surname),
	NULL
FROM
	users
INNER JOIN organisations on organisations.id = users.employer_id
where type=3 and web_access = 1
order by firstnames
HEREDOC;
    }
    else
    {
        $assessor_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(firstnames, ' ', surname),
	NULL
FROM
	users
INNER JOIN organisations on organisations.id = users.employer_id
where (employer_id=$provider_id or organisations.organisation_type = 1) and type=3 and web_access = 1
order by firstnames
HEREDOC;
    }

}


            //	$que = "select CONCAT(firstnames, ' ', surname) from users INNER JOIN organisations on organisations.id = users.employer_id where username='$g_vo->assessor'";

            $verifier_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(firstnames, ' ', surname),
	NULL
FROM
	users

INNER JOIN organisations on organisations.id = users.employer_id
where employer_id=$provider_id and type=4
order by firstnames
HEREDOC;
        }
        else
        {
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
	employer_id={$c_vo->organisations_id} and type=2
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
where (employer_id={$c_vo->organisations_id} or organisations.organisation_type = 1) and type=3
order by firstnames
HEREDOC;
	
	
	//	$que = "select CONCAT(firstnames, ' ', surname) from users INNER JOIN organisations on organisations.id = users.employer_id where username='$g_vo->assessor'";
	
		$verifier_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(firstnames, ' ', surname),
	NULL
FROM
	users

INNER JOIN organisations on organisations.id = users.employer_id 
where employer_id={$c_vo->organisations_id} and type=4
order by firstnames
HEREDOC;
        }

		$tutor_select = DAO::getResultset($link, $tutor_sql);
		$assessor_select = DAO::getResultset($link, $assessor_sql);
		$verifier_select = DAO::getResultset($link, $verifier_sql);
		$wbcoordinator_select = DAO::getResultset($link, "SELECT users.id, CONCAT(firstnames, ' ', surname),null from users INNER JOIN organisations on organisations.id = users.employer_id where type='6'");

		if($_SESSION['user']->isAdmin())
			$training_providers = DAO::getResultset($link, "SELECT id, legal_name, null FROM organisations WHERE organisation_type like '%3%' order by legal_name");
		else
			$training_providers = DAO::getResultset($link, "SELECT id, legal_name, null FROM organisations WHERE id = " . $_SESSION['user']->employer_id . " order by legal_name");

        $statuses = array(
            array('1', 'Open', ''),
            array('2', 'Closed', ''),
            array('3', 'Cancelled', ''));

		$view = $this->getMembersViewEdit($id, $course_id);
		
		
		// Cancel button URL
		if($g_vo->id == 0)
		{
			$js_cancel = "window.location.replace('do.php?_action=view_course_groups&course_id=" . $c_vo->id . "');";
		}
		else
		{
			$js_cancel = "window.location.replace('do.php?_action=read_course_group&id={$g_vo->id}');";
		}
	
		
		// Presentation
		include('tpl_edit_course_group.php');
	}
	
	
	private function getMembersViewEdit($group_id,$course_id)
	{
		$view = null;
		
		// Retrieve course members view
		if(!array_key_exists('view', $_SESSION)
		|| (array_key_exists('view', $_SESSION) && !($_SESSION['view'] instanceof VoltView))
		|| (array_key_exists('view', $_SESSION) && ($_SESSION['view']->getViewName() != 'course_groups_edit')) )
		{

		//	$order_by = (DB_NAME=='am_rttg')?" is_member DESC, surname ASC, firstnames ASC, organisations.legal_name ASC":" is_member DESC, organisations.legal_name ASC, surname ASC, firstnames ASC";
			
			$order_by = " is_member DESC, surname ASC, firstnames ASC";
			
			// Create new view object
			$sql = <<<HEREDOC
SELECT
	tr.surname, tr.firstnames, tr.gender, tr.id as pot_id, tr.status_code,
	organisations.legal_name AS short_name, student_frameworks.title as ftitle,
	users.enrollment_no,
	tr.gender,
	groups.title,
	(SELECT COUNT(*) FROM group_members WHERE group_members.tr_id=tr.id AND group_members.groups_id='$group_id') AS is_member
FROM
	tr
	LEFT JOIN organisations on organisations.id = tr.employer_id
	LEFT JOIN group_members ON (tr.employer_id=organisations.id AND group_members.tr_id = tr.id)
	LEFT JOIN student_frameworks on student_frameworks.tr_id = tr.id
	LEFT JOIN users on tr.username = users.username
	LEFT JOIN groups on groups.id = group_members.groups_id
	LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
WHERE
	courses_tr.course_id=$course_id AND (tr.id NOT IN (SELECT tr_id FROM group_members) OR tr.id IN (SELECT tr_id FROM group_members WHERE groups_id = '$group_id'))
group by tr.id
order by
$order_by
HEREDOC;

	
			$_SESSION['view'] = $view = new VoltView('course_groups_edit', $sql); /* @var $view View */
		}
		else
		{
			$view = $_SESSION['view'];
		}
		
		return $view;
	}
	
}
?>