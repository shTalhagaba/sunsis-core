<?php
class save_course implements IAction
{
	public function execute(PDO $link)
	{
		// Populate Value Object from user's <form> submission
		$vo = new Course();
		$vo->populate($_POST);
		
		// Delete all the previous qualifications dates for this course from course_qualifications_dates 
		// if this course was updated. No need to do that if this a new course

		// #22927 - active was always setting to one? re 02/07/2012
		$vo->active = 0;
		if ( isset($_POST['active']) ) {
			$vo->active = 1;
		}
		if ( isset($_POST['induction']) )
			$vo->induction = 'Y';
		else
			$vo->induction = 'N';
		if ( isset($_POST['l4']) )
			$vo->l4 = 'Y';
		else
			$vo->l4 = 'N';
		
		try
		{
			DAO::transaction_start($link);
			if($vo->id!='')
			{
				$query  = "delete from course_qualifications_dates where course_id='$vo->id'";
				DAO::execute($link, $query);
			}
	
			$vo->save($link);
				
			// Add default qualification dates for this course to course_qualification_dates
			if(!empty($vo->framework_id) && is_numeric($vo->framework_id)) {
				$query  = "insert into course_qualifications_dates (select framework_qualifications.id, framework_qualifications.framework_id, internaltitle, $vo->id, courses.course_start_date, DATE_ADD(courses.course_start_date, INTERVAL framework_qualifications.duration_in_months MONTH), '0', '0', '0' from framework_qualifications left join courses on courses.id = $vo->id where framework_qualifications.framework_id = $vo->framework_id);";
				DAO::execute($link, $query);
			}

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

	
		http_redirect($_SESSION['bc']->getPrevious());
	}
}
?>