<?php
class edit_employer_v2 implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		$_SESSION['bc']->add($link, "do.php?_action=edit_employer_v2&id=" . $id, "Add/Edit Employer");

		if($id == '')
		{
			$employer = new Employer();
			$employer->active = 1;
			$employer->organisation_type = Organisation::TYPE_EMPLOYER;
			$mainLocation = new Location();
			$mainLocation->is_legal_address = 1;
		}
		else
		{
			$employer = Employer::loadFromDatabase($link, $id);
			$mainLocation = $employer->getMainLocation($link);
		}


		$ddlSectors = DAO::getResultset($link, "SELECT id, description, NULL FROM lookup_sector_types ORDER BY description;");
		$ddlGroupEmployers = DAO::getResultset($link,"SELECT id, title, NULL FROM brands ORDER BY title;");
		$ddlRegions = DAO::getResultset($link, "SELECT description, description, NULL FROM lookup_vacancy_regions ORDER BY description;");
		$ddlCodes = DAO::getResultset($link, "SELECT code, description, NULL FROM lookup_employer_size ORDER BY code;");
		$ddlDeliveryPartners = DAO::getResultset($link, "SELECT id, legal_name, NULL FROM organisations WHERE organisation_type = '" . Organisation::TYPE_TRAINING_PROVIDER . "' ORDER BY legal_name");
		if($_SESSION['user']->isAdmin())
			$ddlAccountManagers = DAO::getResultset($link, "SELECT username, Concat(firstnames, ' ', surname) ,null FROM users WHERE type = 7 OR username = '{$employer->creator}' ORDER BY firstnames;");
		else
			$ddlAccountManagers = DAO::getResultset($link, "SELECT username, Concat(firstnames, ' ', surname) ,null FROM users WHERE username = '{$_SESSION['user']->username}' OR username = '{$employer->creator}' ORDER BY firstnames;");


		$salary_rate_options = [
			0 => [0, '', null, null],
			1 => [1, 'Grade 1'],
			2 => [2, 'Grade 2'],
			3 => [3, 'Grade 3']];

		require_once('tpl_edit_employer_v2.php');
	}
}