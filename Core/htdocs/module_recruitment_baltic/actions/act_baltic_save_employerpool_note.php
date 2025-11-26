<?php
class baltic_save_employerpool_note implements IAction {
	
	public function execute(PDO $link) {			
		$emp_pool = EmployerPoolNotes::loadFromDatabase($link, $_REQUEST['emp_id']);

		$emp_pool->note = isset($_REQUEST['emp_note'])?addslashes((string)$_REQUEST['emp_note']):'';
		$emp_pool->username = $_SESSION['user']->username; 
    	$emp_pool->next_action = join("-", array($_REQUEST['emp_year'], $_REQUEST['emp_month'], $_REQUEST['emp_day']));
    	$emp_pool->status = isset($_REQUEST['emp_region'])?$_REQUEST['emp_region']:'';

		if ( $emp_pool->save($link) ){
			http_redirect('do.php?_action=view_employers_pool');
		}
		else {
			throw new Exception('There has been a problem with this note!');
		}
	}
}
?>