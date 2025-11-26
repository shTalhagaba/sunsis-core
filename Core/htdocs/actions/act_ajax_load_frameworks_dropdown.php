<?php
class ajax_load_frameworks_dropdown implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml');
		
		$framework_type = array_key_exists('framework_type', $_REQUEST)?$_REQUEST['framework_type']:'';
		
		if($framework_type == '')
		{
			throw new Exception("Missing querystring argument 'org_id'");
		}
		
		$sql = <<<HEREDOC
SELECT
	FworkCode, CONCAT(FworkCode,' - ',PathwayName), NULL
FROM
	lars201516.Core_LARS_Framework
WHERE
	ProgType='$framework_type' AND PathwayName != '';
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