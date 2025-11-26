<?php
class ajax_load_acm_dropdown implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml');


		$sql = <<<HEREDOC
SELECT
	id, description, null
FROM
	lookup_acm
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