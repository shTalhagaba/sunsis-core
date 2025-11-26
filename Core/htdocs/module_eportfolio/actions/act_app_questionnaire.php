<?php
class app_questionnaire implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = DAO::getSingleValue($link, "SELECT id FROM tr ORDER BY id DESC LIMIT 1");
		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);

		include_once('tpl_app_questionnaire.php');
	}
}