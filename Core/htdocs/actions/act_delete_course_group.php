<?php
class delete_course_group implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		if($id == '' || !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring in order to delete this record");
		}

	
		// Retrieve the course group record (to retrieve the course ID)
		$dao = new CourseGroupDAO($link);
		$vo = $dao->find((integer)$id); /* @var $vo CourseGroupVO */
		 
		
		//DAO::transaction_start($link);
		try
		{
			// Delete the course group
			$dao->delete($link, $id);
		}
		catch(Exception $e)
		{
			//DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);			
		}
		//DAO::transaction_commit($link);
		
	
		http_redirect($_SESSION['bc']->getPrevious());
	}
	

}
?>