<?php
class view_registered_employers implements IAction
{
	public function execute(PDO $link)
	{
		if ( isset($_REQUEST['convert']) ) {
			$registered_employer = CandidateEmployer::loadFromDatabase($link, $_REQUEST['convert']);
			$registered_employer->convertToOrganisation($link);
		}
		// resets the breadcrumb trail.
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_registered_employers", "View Candidate Employers");

		$view = ViewCandidateEmployers::getInstance($link);		
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_registered_employers.php');
	}
}
?>