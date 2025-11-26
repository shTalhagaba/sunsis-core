<?php
class ajax_load_course_dropdown implements IAction
{
	public function execute(PDO $link)
	{

		header('Content-Type: text/xml');
		
		$provider_id = array_key_exists('provider_id', $_REQUEST)?$_REQUEST['provider_id']:'';
		$start_date = array_key_exists('start_date', $_REQUEST)?Date::toMySQL($_REQUEST['start_date']):'';
		$end_date = array_key_exists('end_date', $_REQUEST)?Date::toMySQL($_REQUEST['end_date']):'';

		if($provider_id == '')
		{
			if($_SESSION['org']->org_type_id == ORG_PROVIDER)
			{
				$provider_id = $_SESSION['org']->id;
			}
			else
			{
				throw new Exception("Missing querystring argument 'provider_id'");
			}
		}
		
		
		$sql = <<<HEREDOC
SELECT
	courses.id,
	SUBSTRING(CONCAT(DATE_FORMAT(course_start_date, '%d/%m/%Y'), '::' ,courses.title),1,90) AS label,
	null
FROM
	courses 
WHERE
	courses.organisations_id=$provider_id
HEREDOC;

	
	
		if($start_date != '')
		{
			$sql .= " AND courses.course_end_date >= '$start_date' ";
		}
		if($end_date != '')
		{
			$sql .= " AND courses.course_start_date <= '$end_date' ";
		}
		
		$sql .= " ORDER BY courses.course_start_date, courses.title";
		
		
		$result = $link->query($sql);
		if($result)
		{
			echo "<?xml version=\"1.0\" ?>\r\n";
			echo "<select>\r\n";
			
			// First entry is empty
			echo "<option value=\"\"></option>\r\n";
			
			while($row = $result->fetch())
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