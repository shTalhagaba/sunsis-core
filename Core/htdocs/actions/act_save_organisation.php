<?php
class save_organisation implements IAction
{
	public function execute(PDO $link)
	{

		$organisation_type = isset($_POST['org_type']) ? $_POST['org_type'] : '';

		$org = new Organisation();
		$org->populate($_POST);
		
		$org->save($link);
		
		http_redirect('do.php?_action=view_organisations&organisation_type=' . $organisation_type);
	}
}
?>