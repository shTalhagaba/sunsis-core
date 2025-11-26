<?php
class add_course_lesson implements IAction
{
	public function execute(PDO $link)
	{
		$vo = new LessonVO();
		$vo->populate($_REQUEST);
		
		//$vo->tutor = DAO::getSingleValue($link, "select tutor_username from course_qualifications_dates where concat(qualification_id,'::',internaltitle)='$vo->qualification'");
		 
		$days_till_next_lesson = array_key_exists('_frequency', $_REQUEST) ? $_REQUEST['_frequency'] : '7'; // Default to weekly
		$number_to_add = array_key_exists('_number_to_add', $_REQUEST) ? $_REQUEST['_number_to_add'] : '1'; // Default to 1 lesson

		
		// Create Data Access Object
		$dao = new LessonDAO($link);
		
//		DAO::transaction_start($link);
//		try
//		{
			// Add lessons to database
			$lesson_date = new Date($vo->date);
			for($i = 0; $i < $number_to_add; $i++)
			{
				$vo->date = $lesson_date->formatMySQL();
				$new_lesson_id = $dao->insert($vo);

				if( ($i + 1) < $number_to_add)
				{
					$lesson_date->addDays($days_till_next_lesson);
					// Exclude weekends
					$d = $lesson_date->formatMySQL();
					$dayname = DAO::getSingleValue($link, "select DAYNAME('$d');");
					if($dayname=='Saturday')
						$lesson_date->addDays(2);
					elseif($dayname=='Sunday')
						$lesson_date->addDays(1);
				}
			}

            // If ad-hoc lesson then go straight to register
            if(isset($_REQUEST['tr_id']))
            {
                http_redirect('do.php?_action=edit_register&lesson_id=' . $new_lesson_id);
            }

			// Need to update group record with the new number of lessons
			$grp_dao = new CourseGroupDAO($link);
			$grp_dao->updateAttendanceStatistics($vo->groups_id);
			
			// Need to update course record with the new number of lessons
			$grp_vo = $grp_dao->find($vo->groups_id);
			$course = Course::loadFromDatabase($link, $grp_vo->courses_id);
			$course->updateAttendanceStatistics($link);


			// Need to update POT records with the new number of lessons
			$pot_ids = <<<HEREDOC
SELECT
	group_members.tr_id
FROM
	group_members INNER JOIN lessons
	ON lessons.groups_id = group_members.groups_id
WHERE
	lessons.id = {$vo->id}
HEREDOC;
			$pot_dao = new PotDAO($link);
			$pot_dao->updateAttendanceStatistics($link, $pot_ids);
			
//		}
//		catch(Exception $e)
//		{
//			DAO::transaction_rollback($link, $e);
//			throw new WrappedException($e);
//		}

//		DAO::transaction_commit($link);
		
		
		// Return user to the lessons view, and prepopulate the 'add lesson' fields
		// to make it quicker to add the next lesson
		$lesson_date->addDays($days_till_next_lesson);

		$uri = 'do.php?_action=view_course_lessons'
			. '&course_id=' . urlencode($_REQUEST['course_id'])
			. '&group_id=' . urlencode($vo->groups_id)
			. '&date=' . urlencode($lesson_date->formatShort())
			. '&frequency=' . urlencode($days_till_next_lesson)
			. '&start_time=' . urlencode($vo->start_time)
			. '&end_time=' . urlencode($vo->end_time)
			. "&number_to_add=" . urlencode($number_to_add)
			. '&tutor=' . urlencode($vo->tutor)
			. '&location=' . urlencode($vo->location) 
			. "&_showPanel=1";
		http_redirect($uri);
	}
	
}
?>