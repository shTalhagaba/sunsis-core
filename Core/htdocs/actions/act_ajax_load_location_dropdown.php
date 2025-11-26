<?php
class ajax_load_location_dropdown implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml');
		
		$org_id = array_key_exists('org_id', $_REQUEST)?$_REQUEST['org_id']:'';
        $org_name = array_key_exists('org_name', $_REQUEST)?$_REQUEST['org_name']:'';

		if($org_id == '' && $org_name == '')
		{
			throw new Exception("Missing querystring argument 'org_id'");
		}
		
if($org_id!='')
{
		$sql = <<<HEREDOC
SELECT
	id, full_name, null
FROM
	locations
WHERE
	organisations_id=$org_id
ORDER BY
	is_legal_address DESC, full_name ASC;
HEREDOC;
}
else
{
    $sql = <<<HEREDOC
SELECT
	locations.id, CONCAT(locations.full_name, ' (', locations.postcode, ')') AS full_name, NULL
FROM
	locations
INNER JOIN organisations on organisations.id = locations.organisations_id and organisations.organisation_type = 3
WHERE
	organisations.legal_name='$org_name'
ORDER BY
	is_legal_address DESC, full_name ASC;
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