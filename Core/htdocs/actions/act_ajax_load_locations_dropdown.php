<?php
class ajax_load_locations_dropdown implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml');
		
		$course_id = array_key_exists('course_id', $_REQUEST)?$_REQUEST['course_id']:'';
		
		if($course_id == '')
		{
			throw new Exception("Missing querystring argument 'org_id'");
		}
		 
		$que = "select organisations_id from courses where id='$course_id'";
		$provider_id = trim(DAO::getSingleValue($link, $que));
		
		$sql = <<<HEREDOC
SELECT
	id, full_name, null
FROM
	locations
WHERE
	organisations_id=$provider_id
ORDER BY
	is_legal_address DESC, full_name ASC;
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