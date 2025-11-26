<?php
class sign_employer_agreement implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $employer_id = isset($_REQUEST['employer_id']) ? $_REQUEST['employer_id'] : '';
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        if(trim($id) != '' && trim($employer_id) != '' && trim($key) != '')
        {
            if(!EmployerAgreement::validateKey($link, $id, $employer_id, $key))
            {
                EmployerAgreement::generateErrorPage($link);
                exit;
            }
        }
        else
        {
            EmployerAgreement::generateErrorPage($link);
            exit;
        }

        $employer = Employer::loadFromDatabase($link, $employer_id);
        if(is_null($employer))
        {
            EmployerAgreement::generateErrorPage($link);
            exit;
        }
        $location_id = DAO::getSingleValue($link, "SELECT id FROM locations WHERE locations.organisations_id = '{$employer->id}' AND is_legal_address = '1'");
        $location = Location::loadFromDatabase($link, $location_id);
        if(is_null($location))
        {
            EmployerAgreement::generateErrorPage($link);
            exit;
        }

        $agreement = EmployerAgreement::loadFromDatabase($link, $id);
        if(in_array($agreement->status, [EmployerAgreement::TYPE_SIGNED_BY_EMPLOYER, EmployerAgreement::TYPE_COMPLETED]))
        {
            EmployerAgreement::generateAlreadyCompleted($link, $agreement->id);
            exit;
        }

        $employer_rep = OrganisationContact::loadFromDatabase($link, $agreement->employer_rep);
        $tp_rep = User::loadFromDatabaseById($link, $agreement->tp_rep);

        $tp = Organisation::loadFromDatabase($link, $tp_rep->employer_id);
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

	    $agreement_locations = $agreement->locations != '' ? explode(',', $agreement->locations) : [];
        $agreement_locations = !isset($agreement_locations[0]) ? $location->id : $agreement_locations; 
        $agreement_first_location = Location::loadFromDatabase($link, $agreement_locations[0]);

        include_once('tpl_sign_employer_agreement.php');
    }

}