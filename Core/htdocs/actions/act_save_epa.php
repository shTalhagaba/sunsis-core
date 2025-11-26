<?php
class save_epa implements IAction
{
	public function execute(PDO $link)
	{
		$tr_epa = new stdClass();
		$records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM tr_epa");
		foreach($records AS $key => $value)
			$tr_epa->$value = null;
		foreach($tr_epa AS $key => $value)
		{
			$tr_epa->$key = $_REQUEST[$key];
		}

		DAO::saveObjectToTable($link, 'tr_epa', $tr_epa);

		http_redirect('do.php?_action=read_training_record&id=' . $tr_epa->tr_id);
	}

}
?>