<?php
class employer_health_safety_form implements IAction
{
    public function execute(PDO $link)
    {
        $hs_id = isset($_REQUEST['hs_id']) ? $_REQUEST['hs_id'] : '';

        if($hs_id == '')
        {
            throw new Exception("Missing querystring arguments: hs_id, employer_id");
        }

        $hs = EmployerHealthAndSafety::loadFromDatabaseById($link, $hs_id);
        if(is_null($hs))
        {
            throw new Exception("Invalid hs_id");
        }
        
        $employer = Employer::loadFromDatabase($link, $hs->employer_id);
        if(is_null($employer))
        {
            throw new Exception("Invalid employer id");
        }

        $mainLocation = Location::loadFromDatabase($link, $hs->location_id);
        $hs_form = $hs->getHsForm($link);

        $_SESSION['bc']->add($link, "do.php?_action=employer_health_safety_form&hs_id={$hs->id}", "Employer Health and Safety Form");

        $employer_rep_contact = DAO::getObject($link, "SELECT * FROM organisation_contacts WHERE contact_id = '{$hs->employer_rep}'");
        $hs_contact = DAO::getObject($link, "SELECT * FROM organisation_contacts WHERE contact_id = '{$hs->hs_contact_person}'");

        $detail = json_decode($hs_form->detail);

        $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

	if(DB_NAME == "am_eet" && !is_null($employer->delivery_partner))
        {
            $provider = Organisation::loadFromDatabase($link, $employer->delivery_partner);
            $logo = !is_null($provider->provider_logo) ? $provider->provider_logo : $logo;
            $logoAlt = $provider->legal_name;
        }

        include_once('tpl_employer_health_safety_form.php');
    }
}