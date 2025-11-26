<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Khushnood
 * Date: 17/04/12
 * Time: 13:27
 * To change this template use File | Settings | File Templates.
 */
class ajax_load_units_dropdown implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml');

		$id = array_key_exists('id', $_REQUEST)?$_REQUEST['id']:'';
		$internaltitle = array_key_exists('internaltitle', $_REQUEST)?$_REQUEST['internaltitle']:'';

		if($id == '')
		{
			throw new Exception("Missing querystring argument 'org_id'");
		}


		$internaltitle = addslashes((string)$internaltitle);

		$sql = "SELECT * from qualifications where id = '$id' and internaltitle = '$internaltitle' limit 0,1";
		$st = $link->query($sql);
		if($st)
		{
			echo "<select>";

			// First entry is empty
			echo "<option value=''></option>";

			while($row = $st->fetch())
			{
				$pageDom = new DomDocument();
				@$pageDom->loadXML($row['evidences']);
				$units = $pageDom->getElementsByTagName('unit');
				foreach($units as $unit)
				{
					echo '<option value="' . htmlspecialchars((string)$unit->getAttribute('title')) . '">' . htmlspecialchars(substr($unit->getAttribute('title'),0,80) . ' [Reference: ' . $unit->getAttribute('reference') . ']') . "</option>\r\n";
				}
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