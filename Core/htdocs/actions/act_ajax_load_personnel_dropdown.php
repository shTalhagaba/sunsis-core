<?php
class ajax_load_personnel_dropdown implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml');
		
		$org_id = array_key_exists('org_id', $_REQUEST)?$_REQUEST['org_id']:'';
		
		if($org_id == '')
		{
			throw new Exception("Missing querystring argument, org_id.");
		}
		 
		
		$sql = <<<HEREDOC
SELECT
	username,
	firstnames,
	surname,
	department,
	job_role
FROM
	users
WHERE
	employer_id=$org_id and type=2;
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
				echo '<option value="' . htmlspecialchars((string)$row['username']) . '">'
					. htmlspecialchars((string)$row['firstnames'] . ' ' . $row['surname']);
				if($row['department'] != '' || $row['job_role'] != '')
				{
					echo ' (';
					if($row['department'] != '')
					{
						echo htmlspecialchars((string)$row['department']);
						
						if($row['job_role'] != '')
						{
							echo ', ' . htmlspecialchars((string)$row['job_role']);
						}
					}
					else
					{
						echo htmlspecialchars((string)$row['job_role']);
					}
					echo ')';
				}
				
				echo "</option>\r\n";
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