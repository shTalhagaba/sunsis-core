<?php
class ajax_save_reasons_for_leaving implements IAction
{
	public function execute(PDO $link)
	{
		$reason = isset($_REQUEST['reason'])?$_REQUEST['reason']:'';

		if (!empty($reason)) {
			$existingRecord = DAO::getSingleValue($link, "SELECT id FROM lookup_reasons_for_leaving WHERE description = '" . addslashes((string)$reason) . "' LIMIT 1");
			if (!$existingRecord) {
				$values = array('description'=>$reason);
				DAO::saveObjectToTable($link, "lookup_reasons_for_leaving", $values);
			}
		}

	}
}
?>