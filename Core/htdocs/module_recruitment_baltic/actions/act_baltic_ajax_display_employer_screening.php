<?php
class baltic_ajax_display_employer_screening implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/html;');
		$empid = isset($_REQUEST['empid'])?$_REQUEST['empid']:'';
		if( '' == $empid ) {
		    
		}
		else {	
			// load the candidate history
			$employerpool = EmployerPoolNotes::loadFromDatabase($link, $empid);					
			echo $employerpool->render($link);
		}
	}
}
?>
