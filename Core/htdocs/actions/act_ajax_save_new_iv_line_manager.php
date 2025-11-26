<?php
class ajax_save_new_iv_line_manager implements IAction
{
	public function execute(PDO $link)
	{
		$iv_line_manager = isset($_REQUEST['iv_line_manager'])?$_REQUEST['iv_line_manager']:'';

		if (!empty($iv_line_manager)) {
			$existingRecord = DAO::getSingleValue($link, "SELECT id FROM lookup_iv_line_manager WHERE description = '" . addslashes((string)$iv_line_manager) . "' LIMIT 1");
			if (!$existingRecord) {
				$values = array('description'=>$iv_line_manager);
				DAO::saveObjectToTable($link, "lookup_iv_line_manager", $values);
			}
		}

	}
}
?>