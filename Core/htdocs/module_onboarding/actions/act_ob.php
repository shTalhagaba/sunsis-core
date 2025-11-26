<?php
class ob implements IAction
{
	public function execute(PDO $link)
	{
		$subview = isset($_GET['subview']) ? $_GET['subview'] : 'overview';

		$tr_id = DAO::getSingleValue($link, "SELECT id FROM tr ORDER BY id DESC LIMIT 1");
		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);


		include_once('tpl_ob.php');
	}
}