<?php
class sign_employer_hs_form implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $employer_id = isset($_REQUEST['employer_id']) ? $_REQUEST['employer_id'] : '';
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        if(trim($id) != '' && trim($employer_id) != '' && trim($key) != '')
        {
            if(!EmployerHealthAndSafetyForm::validateKey($link, $id, $employer_id, $key))
            {
                EmployerHealthAndSafetyForm::generateErrorPage($link);
                exit;
            }
        }
        else
        {
            EmployerHealthAndSafetyForm::generateErrorPage($link);
            exit;
        }

        $hs = EmployerHealthAndSafety::loadFromDatabaseById($link, $id);
        if(is_null($hs))
        {
            EmployerHealthAndSafetyForm::generateErrorPage($link);
            exit;
        }

        $employer = Employer::loadFromDatabase($link, $employer_id);
        if(is_null($employer))
        {
            EmployerHealthAndSafetyForm::generateErrorPage($link);
            exit;
        }

        //if(in_array($hs->status, [EmployerHealthAndSafetyForm::TYPE_SIGNED_BY_EMPLOYER, EmployerHealthAndSafetyForm::TYPE_COMPLETED]))
        //{
        //    EmployerHealthAndSafetyForm::generateAlreadyCompleted($link, $hs->id);
        //    exit;
        //}

        $mainLocation = Location::loadFromDatabase($link, $hs->location_id);
        $hs_form = $hs->getHsForm($link);

        $employer_rep_contact = DAO::getObject($link, "SELECT * FROM organisation_contacts WHERE contact_id = '{$hs->employer_rep}'");
        $hs_contact = DAO::getObject($link, "SELECT * FROM organisation_contacts WHERE contact_id = '{$hs->hs_contact_person}'");

	$detail = json_decode($hs_form->detail);

	if( isset($detail->employer_sign) && $detail->employer_sign != '' )
        {
            EmployerHealthAndSafetyForm::generateAlreadyCompleted($link, $hs->id);
            exit;
        }

        $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

	if(DB_NAME == "am_eet" && !is_null($employer->delivery_partner))
        {
            $provider = Organisation::loadFromDatabase($link, $employer->delivery_partner);
            $logo = !is_null($provider->provider_logo) ? $provider->provider_logo : $logo;
            $logoAlt = $provider->legal_name;
        }

        include_once('tpl_sign_employer_hs_form.php');
    }

}