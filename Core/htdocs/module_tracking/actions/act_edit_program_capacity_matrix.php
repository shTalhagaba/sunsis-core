<?php
class edit_program_capacity_matrix implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=edit_program_capacity_matrix", "Programmes Capacity Matrix");

		$titles = DAO::getLookupTable($link, "SELECT id, description FROM lookup_apprenticeship_titles WHERE show_in_capacity_matrix = '1' ORDER BY order_in_capacity_matrix;");

		$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';
		if($subaction == 'save')
		{
			$this->saveInformation($link);
			$_SESSION['edit_program_capacity_matrix_saved'] = 'The information has been saved successfully.'; 
		}

		include_once('tpl_edit_program_capacity_matrix.php');
	}

	private function saveInformation(PDO $link)
	{
		foreach($_POST AS $key => $value)
		{
			if(substr($key, 0, 6) == 'month_')
			{
				$parts = explode("_", $key);
				$month_name = $parts[1];
				$app_title_id = $parts[2];

				$entry = new stdClass();
				$entry->month_name = $month_name;
				$entry->ap_title_id = $app_title_id;
				$entry->capacity = $value;

				DAO::saveObjectToTable($link, "program_capacity_matrix", $entry);
			}
		}
	}
}