<?php
class edit_epa_org_assessor implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';
		if($subaction == 'save_epa_org_assessor')
		{
			$this->save_epa_org_assessor($link);
			exit;
		}
		if($subaction == 'deleteEPAOrgAssessor')
		{
			$this->deleteEPAOrgAssessor($link);
			exit;
		}


		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		$EPA_Org_ID = isset($_REQUEST['EPA_Org_ID']) ? $_REQUEST['EPA_Org_ID'] : '';

		if($EPA_Org_ID == '')
			throw new Exception("Missing querystring argument: EPA_Org_ID");

		$EPA_Org = DAO::getObject($link, "SELECT * FROM central.epa_organisations WHERE EPA_ORG_ID = '{$EPA_Org_ID}'");
		if(!isset($EPA_Org->EPA_ORG_ID))
			throw new Exception('Could not found EPA Organisation with ID: ' . $EPA_Org_ID);

		if($id == '')
		{
			$_SESSION['bc']->add($link, "do.php?_action=edit_epa_org_assessor&id=", "Add EPA Assessor");

			$epa_assessor = new stdClass();
			$records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM epa_org_assessors");
			foreach($records AS $key => $value)
				$epa_assessor->$value = null;

			$epa_assessor->EPA_Org_ID = $EPA_Org_ID;
		}
		else
		{
			$_SESSION['bc']->add($link, "do.php?_action=edit_epa_org_assessor&id=" . $id, "Edit EPA Assessor");

			$epa_assessor = DAO::getObject($link, "SELECT * FROM epa_org_assessors WHERE id = '{$id}'");
		}

		include_once('tpl_edit_epa_org_assessor.php');
	}

	private function save_epa_org_assessor(PDO $link)
	{
		$epa_assessor = new stdClass();
		foreach($_POST AS $key => $value)
			$epa_assessor->$key = $value;
		if($epa_assessor->id == '')
		{
			$epa_assessor->id = DAO::getSingleValue($link, "SELECT MAX(id) + 1 FROM epa_org_assessors");
		}
		DAO::saveObjectToTable($link, 'epa_org_assessors', $epa_assessor);

		http_redirect($_SESSION['bc']->getPrevious());
	}

	private function deleteEPAOrgAssessor(PDO $link)
	{
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		if($id == '')
			throw new Exception("Missing querystring argument: id");

		DAO::execute($link, "DELETE FROM epa_org_assessors WHERE epa_org_assessors.id = '{$id}'");

		http_redirect($_SESSION['bc']->getCurrent());
	}
}