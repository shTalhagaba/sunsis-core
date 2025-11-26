<?php
class view_candidates implements IAction
{
	public function execute(PDO $link)
	{
	
		// resets the breadcrumb trail.
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_candidates", "View Candidates");

		$view = ViewCandidates::getInstance($link);
		// re - this is questionable I think, but a high importance
		//      rttg client request
		// -------------
		// added in check so only happens on a blank ( hopefully initial ) request
		//throw new Exception(pre($_REQUEST));
		if ( isset($_REQUEST['filter_id']) ) {
			//throw new Exception('pre done');	
		}
		
		// $view->resetFilters();
		
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_candidates.php');
	}
}
?>