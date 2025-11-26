<?php
class edit_learnergroup implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$learnergroup_id = isset($_REQUEST['learnergroup_id']) ? $_REQUEST['learnergroup_id']:'';

		if($learnergroup_id == '')
		{
			// New record
			$vo = new LearnerGroup();
		}
		else
		{
			$vo = LearnerGroup::loadFromDatabase($link, $learnergroup_id);
		}

		$sector_dropdown = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_sector_types ORDER BY id;";
		$sector_dropdown = DAO::getResultset($link, $sector_dropdown);
		
		// Presentation
		include('tpl_edit_learnergroup.php');
	}
}
?>