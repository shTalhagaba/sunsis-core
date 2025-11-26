<?php
class view_student_qualifications implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_student_qualifications", "View Student Qualifications");
		
	    	DAO::execute($link, "UPDATE student_qualifications SET aptitude = 1 WHERE LOCATE(REPLACE(id,'/',''), (SELECT ilr FROM ilr INNER JOIN contracts ON contracts.id = ilr.`contract_id` WHERE tr_id = student_qualifications.`tr_id` ORDER BY contracts.`contract_year` DESC, submission DESC LIMIT 0,1)) = 0");
        	$view = ViewStudentQualifications::getInstance($link);
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_student_qualifications.php');
	}
}

