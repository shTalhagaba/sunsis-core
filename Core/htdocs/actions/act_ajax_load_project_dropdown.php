<?php
class ajax_load_project_dropdown implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml');
		
		$course_id = array_key_exists('course_id', $_REQUEST)?$_REQUEST['course_id']:'';
        $record_status = array_key_exists('record_status', $_REQUEST)?$_REQUEST['record_status']:'';


        if($course_id!='')
        {
            $sql = <<<HEREDOC
SELECT DISTINCT
    evidence_project.id, project, null
FROM
	evidence_project
where
	course_id=$course_id
HEREDOC;

        }
        else
        {
            $sql = <<<HEREDOC
            SELECT DISTINCT
            evidence_project.id, project,null
        FROM
            evidence_project
HEREDOC;

        }

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