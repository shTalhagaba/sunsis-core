<?php
class delete_course implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		if($id == '' || !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring in order to delete this record");
		}
	
		$course = Course::loadFromDatabase($link, $id);
		
		 	
		//DAO::transaction_start($link);
		try
		{
			$numberOfTrainingRecordsForThisCourse = DAO::getSingleValue($link, "SELECT COUNT(*) FROM courses_tr WHERE course_id = " . $course->id);
			if( $numberOfTrainingRecordsForThisCourse > 0 )//although this condition cannot be true but just for double checking
				throw new Exception('Action Aborted: Course cannot be deleted, there are training records attached to this course.');
			$course->delete($link);
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