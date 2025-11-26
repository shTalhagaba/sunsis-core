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
        	$location_ids = $agreement->locations != '' ? explode(',', $agreement->locations) : [];
        	$location = isset($location_ids[0]) ? Location::loadFromDatabase($link, $location_ids[0]) : $employer->getMainLocation($link); // there must be at least one location

		$_SESSION['bc']->add($link, "do.php?_action=view_employer_agreement&id={$agreement->id}", "View Employer Agreement");

		$employer_rep = OrganisationContact::loadFromDatabase($link, $agreement->employer_rep);
		$tp_rep = User::loadFromDatabaseById($link, $agreement->tp_rep);

		if(DB_NAME == "am_crackerjack")
		{ 
			$tp_id = 581; 
		}
		else
		{
			$tp_id = $tp_rep->employer_id;
		}
		$tp = TrainingProvider::loadFromDatabase($link, $tp_id);
		$tp_location = $tp->getMainLocation($link);

		if(DB_NAME == "am_eet" && $employer->delivery_partner != "")
		{
			$tp = TrainingProvider::loadFromDatabase($link, $employer->delivery_partner);
			$tp_location = $tp->getMainLocation($link);
		}

        $logo = DAO::getSingleValue($link, "SELECT provider_logo FROM organisations WHERE id = '{$tp->id}'");
        if($logo == '')
            $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

		if(DB_NAME == "am_eet" && !is_null($employer->delivery_partner))
        {
            $provider = Organisation::loadFromDatabase($link, $employer->delivery_partner);
            $logo = !is_null($provider->provider_logo) ? $provider->provider_logo : $logo;
            $logoAlt = $provider->legal_name;
        }


        include_once('tpl_view_employer_agreement.php');
	}
}