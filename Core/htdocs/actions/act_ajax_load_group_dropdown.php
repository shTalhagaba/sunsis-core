<?php
class ajax_load_group_dropdown implements IAction
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
	groups.id, title,(SELECT COUNT(*) FROM group_members WHERE group_members.groups_id = groups.id) AS student_count,null
FROM
	groups
	LEFT JOIN group_members
		on group_members.groups_id = groups.id
	LEFT JOIN tr
		ON (groups.id = group_members.groups_id AND group_members.tr_id = tr.id)
WHERE
	groups.courses_id=$course_id
ORDER BY groups.title
HEREDOC;

        }
        else
        {
            if($record_status=='')
                $where = " where tr.status_code != '' ";
			elseif($record_status=='0')
                $where = "  ";
            else
                $where = " where tr.status_code = '$record_status' ";

            $sql = <<<HEREDOC
SELECT DISTINCT
	groups.id, title,(SELECT COUNT(*) FROM group_members WHERE group_members.groups_id = groups.id) AS student_count,null
FROM
	groups
	LEFT JOIN group_members
		on group_members.groups_id = groups.id
	LEFT JOIN tr
		ON (groups.id = group_members.groups_id AND group_members.tr_id = tr.id)
$where
ORDER BY groups.title
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
				echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . ' (' . htmlspecialchars((string)$row[2]) . ' learners)' . "</option>\r\n";
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