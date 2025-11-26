<?php
class view_employer_agreement implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

		if($id == '')
			throw new Exception("Missing querystring argument: id");

		$agreement = EmployerAgreement::loadFromDatabase($link, $id);
		if(is_null($agreement))
			throw new Exception("Invalid id");

		$employer = Employer::loadFromDatabase($link, $agreement->employer_id);
        $location = $employer->getMainLocation($link);

		$_SESSION['bc']->add($link, "do.php?_action=view_employer_agreement&id={$agreement->id}", "View Employer Agreement");

		$employer_rep = OrganisationContact::loadFromDatabase($link, $agreement->employer_rep);
		$tp_rep = User::loadFromDatabaseById($link, $agreement->tp_rep);

		$tp = TrainingProvider::loadFromDatabase($link, $tp_rep->employer_id);
		$tp_location = $tp->getMainLocation($link);

        $logo = DAO::getSingleValue($link, "SELECT provider_logo FROM organisations WHERE id = '{$tp->id}'");
        if($logo == '')
            $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');


        include_once('tpl_view_employer_agreement.php');
	}
}