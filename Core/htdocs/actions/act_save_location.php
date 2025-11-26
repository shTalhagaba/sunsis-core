<?php
class save_location implements IAction
{
	public function execute(PDO $link)
	{
		$loc = new Location();
		$loc->populate($_POST);
		$loc->save($link);
		
		$assessor = isset($_POST['assessor'])?$_POST['assessor']:'';
		$course = isset($_POST['course'])?$_POST['course']:'';
		
		if($assessor!='' && $course!='')
		{
			DAO::execute($link, "update tr INNER JOIN courses_tr ON courses_tr.tr_id = tr.id set assessor = '$assessor' where employer_location_id = '$loc->id' and courses_tr.course_id = '$course'");
		}	
		
		$id = $_POST['organisations_id'];
		$org = Organisation::loadFromDatabase($link, $id);

		//$_SESSION['bc']->index = $_SESSION['bc']->index - 2;
		http_redirect($_SESSION['bc']->getPrevious(1));

		// issue with missing breadcrumb items
        // ----
        // - re need to investigate this to provide a more
        //   robust methodology for doing this
        // pushing the index count to the last entry in breadcrumbs	
        //end($_SESSION['bc']->urls);
        //$_SESSION['bc']->index = key($_SESSION['bc']->urls);
        
        // where is the -2 going to? back to the organisation, as -1
        // should be back to the edit_location screen?
        // http_redirect($_SESSION['bc']->urls[$_SESSION['bc']->index-2]);
	}
}
?>