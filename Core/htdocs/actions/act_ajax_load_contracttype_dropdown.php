<?php
class ajax_load_contracttype_dropdown implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml');
		 
		$funding_body = array_key_exists('funding_body', $_REQUEST)?$_REQUEST['funding_body']:'';
		
		if($funding_body == '')
		{
			throw new Exception("Missing querystring argument 'funding_body'");
		}
		
		$key = addslashes((string)$funding_body);
		
		$sql = <<<HEREDOC
SELECT
	id, contract_type, null
FROM
	lookup_contract_types
WHERE
	funding_body='$key'
ORDER BY
	id;
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