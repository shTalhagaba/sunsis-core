<?php
class ajax_save_new_acm implements IAction
{
	public function execute(PDO $link)
	{
		$acm = isset($_REQUEST['acm'])?$_REQUEST['acm']:'';

		if (!empty($acm)) {
			$existingRecord = DAO::getSingleValue($link, "SELECT id FROM lookup_acm WHERE description = '" . addslashes((string)$acm) . "' LIMIT 1");
			if (!$existingRecord) {
				$values = array('description'=>$acm);
				DAO::saveObjectToTable($link, "lookup_acm", $values);
			}
		}

	}
}
?>