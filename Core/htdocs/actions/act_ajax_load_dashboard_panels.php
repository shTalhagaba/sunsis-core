<?php
class ajax_load_dashboard_panels implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml');


		$sql = "SELECT panel_name, panel_heading, null FROM dashboard_panels WHERE visible = 0 AND user = '" . $_SESSION['user']->username . "' ";

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