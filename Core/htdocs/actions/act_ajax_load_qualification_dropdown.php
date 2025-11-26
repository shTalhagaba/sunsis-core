<?php
class ajax_load_qualification_dropdown implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml');
		
		$qual_level = array_key_exists('qual_level', $_REQUEST)?$_REQUEST['qual_level']:'';
		$qual_type = array_key_exists('qual_type', $_REQUEST)?$_REQUEST['qual_type']:'';
		 
		// Build query
		$sql = "SELECT	DISTINCT qualifications.id, qualifications.title FROM qualifications INNER JOIN courses ON qualifications.id = courses.main_qualification_id ";
		if( ($qual_level != '') || ($qual_type != '') )
		{
			$sql .= " WHERE ";
			
			if($qual_level != '')
			{
				$sql .= " FIND_IN_SET('$qual_level', qualifications.level) ";
				
				if($qual_type != '')
				{
					$sql .= " AND qualifications.qualification_type = '$qual_type' ";
				}
			}
			else
			{
				$sql .= " qualifications.qualification_type = '$qual_type' ";
			}
		}
		$sql .= " ORDER BY qualifications.title;";
		
		$st = $link->query($sql);
		if($st)
		{
			echo "<?xml version=\"1.0\" ?>\r\n";
			echo "<select>\r\n";
			
			// First entry is empty
			echo "<option value=\"\"></option>\r\n";
			
			while($row = $st->fetch())
			{
				echo '<option value="' . htmlspecialchars((string)$row['id']) . '">';
				echo htmlspecialchars((string)$row['title']);
				echo '</option>\r\n';
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