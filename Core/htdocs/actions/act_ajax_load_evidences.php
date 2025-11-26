<?php
class ajax_load_evidences implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml');
		
		$tr_id = array_key_exists('tr_id', $_REQUEST)?$_REQUEST['tr_id']:'';
		$qualification_id = array_key_exists('qualification_id', $_REQUEST)?$_REQUEST['qualification_id']:'';
		
		if($tr_id == '')
		{
			throw new Exception("Missing querystring argument 'course_id'");
		}
		
		$sql = <<<HEREDOC
SELECT 
	CONCAT(title, '|', page_no), CONCAT(title, ' - ', DATE_FORMAT(date,'%d-%m-%Y') , ' - ' , assessor), null 
FROM 
	evidence_template 
where 
	tr_id='$tr_id' and qualification_id = '$qualification_id' ORDER BY id
HEREDOC;

		// Filter: only show group titles that a school's students are members of
/*		if($school_id != '')
		{
			$sql .= ' AND pot.school_id='.addslashes((string)$school_id);
		}
*/		
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