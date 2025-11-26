<?php
class baltic_view_candidate_employer implements IUnauthenticatedAction
{
	public function execute(PDO $link) {

		$sector_dropdown = DAO::getResultset($link,"SELECT id, description,null from lookup_sector_types order by description;");
		$bs7666 = new Address();
		
		require_once('tpl_baltic_view_candidate_employer.php');
	}
}
?>