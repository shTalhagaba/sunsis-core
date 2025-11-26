<?php

class edit_attendance_module_lessons implements IAction
{
	public function execute(PDO $link)
	{
		$vo = new LessonVO();

		// Populate using the "treat empty strings as NULL" option.
		// The ValueObject->toNameValuePairs() method ignores NULL
		// values, and so only positively valued fields will be written
		// to the database
		$vo->populate($_REQUEST, true);

		// Clear the date value - it is not applicable in this context
		$vo->date = NULL;

		if(!array_key_exists('lessons', $_REQUEST))
		{
			throw new Exception("No lessons have been selected for amendment");
		}

		try
		{
			$dao = new LessonDAO($link);
			$ids = $_REQUEST['lessons'];
			for($i = 0; $i < count($ids); $i++)
			{
				$vo->id = $ids[$i];
				$dao->update($vo);
			}
		}
		catch(Exception $e)
		{
			if($e->getCode())
			{
				throw new Exception("One of the lessons you have amended clashes with an existing lesson. A group of learners cannot take two lessons at once.");
			}
			else
			{
				throw new WrappedException($e);
			}
		}



		// Return user to the lessons view, and prepopulate the 'add lesson' fields
		// to make it quicker to add the next lesson
		$uri = 'do.php?_action=view_attendance_module_lessons'
			. '&module_id=' . urlencode($_REQUEST['module_id'])
			. '&group_id=' . urlencode($vo->groups_id)
			. '&start_time=' . urlencode($vo->start_time)
			. '&end_time=' . urlencode($vo->end_time)
			. '&tutor=' . urlencode($vo->tutor)
			. '&location=' . urlencode($vo->location)
			. "&_showPanel=1";
		http_redirect($uri);
	}

}
?>