<?php
class ajax_load_destination_outcome_code_dropdown implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml');

		$outcome_type = array_key_exists('outcome_type', $_REQUEST)?$_REQUEST['outcome_type']:'';


		if($outcome_type == '')
		{
			throw new Exception("Missing querystring argument 'org_id'");
		}

		$sql = <<<HEREDOC
			SELECT
				DISTINCT code, CONCAT(type,' - ', code , ' - ', description), NULL
			FROM
				central.lookup_destination_outcome_code
			WHERE
				type = '$outcome_type'
			ORDER BY
				code;
HEREDOC;

//		throw new Exception($sql);

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