<?php
class ajax_load_lessons implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml');
		
		$course_id = array_key_exists('course_id', $_REQUEST)?$_REQUEST['course_id']:'';
		$group_id = array_key_exists('group_id', $_REQUEST)?$_REQUEST['group_id']:'0';
		
		if($course_id == '')
		{
			throw new Exception("Missing groups or course");
		}

		 
		$sql = <<<HEREDOC
SELECT DISTINCT
	lessons.id AS id,
	DATE_FORMAT(lessons.date, '%a') as day,
	DATE_FORMAT(lessons.date, '%D %b %Y') as date,
	lessons.start_time,
	lessons.end_time,
	lessons.num_entries,
	groups.title,
	CONCAT(users.firstnames, ' ', users.surname) as tutor_name,
	locations.full_name AS location_name
FROM
	lessons LEFT OUTER JOIN groups
	ON (lessons.groups_id=groups.id)
	LEFT OUTER JOIN users
	ON (users.username=lessons.tutor)
	LEFT OUTER JOIN locations
	ON (locations.id=lessons.location)
WHERE
	lessons.groups_id='$group_id'
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{

			$lessons = "<table class='resultset' border='0' cellpadding='6' cellspacing='0'>";
			$lessons .= "<tr>";
			$lessons .= "<th colspan='2'>Date</th>";
			$lessons .= "<th colspan='2'>Period</th>";
			$lessons .=	"<th>Site</th>";
			$lessons .= "<th>Tutor</th>";
			$lessons .= "</tr>";
			
			while($row = $st->fetch())
			{
				$lessons .= "<tr>";
				$lessons .= "<td>" . $row['day'] . "</td>";
				$lessons .= "<td>" . $row['date'] . "</td>";
				$lessons .= "<td>" . $row['start_time'] . "</td>";
				$lessons .= "<td>" . $row['end_time'] . "</td>";
				$lessons .= "<td>" . $row['location_name'] . "</td>";
				$lessons .= "<td>" . $row['tutor_name'] . "</td>";
				$lessons .= "</tr>";					
			}
			$lessons .= "</table>";
			
			
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
		
		
		echo $lessons;
	}
}
?>