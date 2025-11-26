<?php
class edit_course implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';

		$hasLearners = DAO::getSingleValue($link, "select count(*) from courses_tr where course_id = '$id'");

		$_SESSION['bc']->add($link, "do.php?_action=edit_course&id=" . $id, "Add/ Edit Course");

		if($id !== '' && !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring in order to edit this record");
		}


		if($id == '')
		{
			// New record
			$c_vo = new Course();
			$duration = ' ';
		}
		else
		{
			$c_vo = Course::loadFromDatabase($link, $id);
			$duration = DAO::getSingleValue($link,  "select duration_in_months from frameworks where id='$c_vo->framework_id'");
			$course_framework = Framework::loadFromDatabase($link, $c_vo->framework_id);
		}


		if($_SESSION['user']->isAdmin() )
			$provider_select = DAO::getResultset($link, "SELECT id, legal_name FROM organisations where organisation_type like '%3%'  ORDER BY legal_name");
		elseif($_SESSION['user']->type==8)
			$provider_select = DAO::getResultset($link, "SELECT id, legal_name FROM organisations where organisations.id=" . $_SESSION['user']->employer_id . "  ORDER BY legal_name");

		$qualification_select = DAO::getResultset($link, "select id, concat(id, '-', title) from qualifications");

		if( $_SESSION['user']->isAdmin() ) {
			$framework_select = $id == '' ? DAO::getResultset($link, "SELECT DISTINCT frameworks.id, frameworks.title FROM frameworks WHERE active = '1' ORDER BY title") : DAO::getResultset($link, "select DISTINCT frameworks.id, frameworks.title from frameworks ORDER BY title");
		}
		elseif( $_SESSION['user']->type==8 ) {
			$framework_select = DAO::getResultset($link, "select DISTINCT frameworks.id, frameworks.title from frameworks where frameworks.parent_org=" . $_SESSION['user']->employer_id . " ORDER BY title");
		}

		// re: Updated to use lookup_programme_type table #21814
		// ---
		$programme_type = DAO::getResultSet($link, "SELECT code, description FROM lookup_programme_type order by description asc");


		//$qualification_select = DAO::getResultset($link, "SELECT id, CONCAT(title,' (',id,')'), CONCAT(qualification_type,' ',level) FROM qualifications ORDER BY qualification_type, level;");

		if($c_vo->organisations_id != '')
		{
			$director_sql = <<<HEREDOC
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
	employer_id={$c_vo->organisations_id};
HEREDOC;
			$director_select = DAO::getResultset($link, $director_sql);
		}
		else
		{
			$director_select = array(
				0=>array('','',null)
			);
		}

		$director_select = DAO::getResultSet($link, "SELECT username, concat(firstnames,' ',surname) FROM users WHERE users.type = 21");
		// Cancel button URL
		if($c_vo->id ==0)
		{
			$js_cancel = "window.location.replace('do.php?_action=view_courses');";
		}
		else
		{
			$js_cancel = "window.location.replace('do.php?_action=read_course&id={$c_vo->id}');";
		}

		$dropdown_frequency = "SELECT frequency, description, null FROM lookup_review_frequency";
		$frequency_dropdown = DAO::getResultset($link, $dropdown_frequency);

		// Presentation
		include('tpl_edit_course.php');
	}
}
?>