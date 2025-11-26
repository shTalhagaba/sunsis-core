<?php
class ajax_load_courses_dropdown implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml');
		
		$framework_id = array_key_exists('framework_id', $_REQUEST)?$_REQUEST['framework_id']:'';
		  
		if($framework_id == '')
		{
			throw new Exception("Missing querystring argument 'course_id'");
		}
		
		$sql = <<<HEREDOC
SELECT 
	courses.id, title
FROM
	courses
WHERE 
	framework_id='$framework_id'
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			echo "<?xml version=\"1.0\" ?>\r\n";
			echo "<select>\r\n";
			
			// First entry is empty
			echo "<option value=\"\"></option>\r\n";
			
			while($row = $st->fetch())
			{
				echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>\r\n";
			}
			
			echo '</select>';
			
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}
}
?>