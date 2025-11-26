<?php
class sign_employer_schedule implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';// schedule id
        $employer_id = isset($_REQUEST['employer_id']) ? $_REQUEST['employer_id'] : ''; // employer id
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : ''; // tr id
        $key = isset($_REQUEST['key']) ? $_REQUEST['key'] : ''; // key

        if(trim($id) != '' && trim($employer_id) != '' && trim($tr_id) != '' && trim($key) != '')
        {
            if(!OnboardingHelper::isValidEmployerScheduleKey($link, $id, $employer_id, $tr_id, $key))
            {
                OnboardingHelper::generateErrorPage($link);
                exit;
            }
        }
        else
        {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }

        /*$is_signed_by_employer = DAO::getSingleValue($link, "SELECT COUNT(*) FROM employer_agreement_schedules WHERE id = '{$id}' AND emp_sign IS NOT NULL");
        if($is_signed_by_employer > 0)
        {
            OnboardingHelper::generateAlreadyCompletedPage($link, $tr_id);
            exit;
        }*/

        $schedule = EmployerSchedule1::loadFromDatabase($link, $id);
        if($schedule->emp_sign != '')
        {
            OnboardingHelper::generateAlreadyCompletedPage($link, $tr_id);
            exit;
        }

        $tr = TrainingRecord::loadFromDatabase($link, $schedule->tr_id);
        if(is_null($tr))
            throw new Exception("Invalid tr_id");

        $ob_learner = $tr->getObLearnerRecord($link);

        $employer = Employer::loadFromDatabase($link, $tr->employer_id);

        $location_id = DAO::getSingleValue($link, "SELECT id FROM locations WHERE locations.organisations_id = '{$employer->id}' AND is_legal_address = '1'");
        $employer_location = Location::loadFromDatabase($link, $tr->employer_location_id);

        $detail = $schedule->detail != '' ? json_decode($schedule->detail) : null;
        if($detail == '' || is_null($detail))
            pre("Form is not yet ready, please come back later.");

        $apprentice_job_title = (isset($detail->apprentice_job_title) && $detail->apprentice_job_title != '') ? $detail->apprentice_job_title : $tr->job_title;

        

        $framework = Framework::loadFromDatabase($link, $tr->framework_id);

        $_trainer_type = User::TYPE_ASSESSOR;
        $ddlTrainers = DAO::getResultset($link, "SELECT users.id, CONCAT(firstnames, ' ', surname), null FROM users WHERE users.employer_id = '{$tr->provider_id}' AND users.type = '{$_trainer_type}' AND users.web_access = '1' ORDER BY firstnames");

        $avg_employees = DAO::getSingleValue($link, "SELECT avg_no_of_employees FROM employer_agreements WHERE employer_id = '{$employer->id}' ORDER BY id DESC");
        $avg_employees = $employer->site_employees == '' ? 0 : $employer->site_employees;

        $mainLocation = $employer->getMainLocation($link);

        if($mainLocation->contact_email == '')
        {
            $primary_contact_email_sql = <<<SQL
SELECT
  organisation_contacts.`contact_email`
FROM
  organisation_contacts
WHERE organisation_contacts.`org_id` = '{$employer->id}'
  AND organisation_contacts.`job_role` = 99
  AND organisation_contacts.`contact_email` IS NOT NULL
ORDER BY organisation_contacts.`contact_id` DESC
LIMIT 1;
SQL;
            $primary_contact_email = DAO::getSingleValue($link, $primary_contact_email_sql);
        }
        else
        {
            $primary_contact_email = $mainLocation->contact_email;
        }

        $learner_age_sql = <<<SQL
SELECT 
    ((DATE_FORMAT('{$tr->practical_period_start_date}','%Y') - DATE_FORMAT('{$ob_learner->dob}','%Y')) - (DATE_FORMAT('{$tr->practical_period_start_date}','00-%m-%d') < DATE_FORMAT('{$ob_learner->dob}','00-%m-%d'))) AS age        
SQL;
        $learner_age = DAO::getSingleValue($link, $learner_age_sql);

        $logo = DAO::getSingleValue($link, "SELECT provider_logo FROM organisations WHERE id = '{$tr->provider_id}'");
        if($logo == '')
            $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

	if(in_array(DB_NAME, ["am_superdrug", "am_sd_demo"]))
        {
            $logo = $employer->logoPath();
        }

	if(in_array(DB_NAME, ["am_superdrug", "am_sd_demo"]))
        {
            if($employer->manufacturer == 1)
            {
                $logo = 'images/logos/Savers.png';
            }
            elseif($employer->manufacturer == 7)
            {
                $logo = 'images/logos/superdrug.png';
            }
        }

        //$tnp1_prices = is_null($tr->tnp1) ? [] : json_decode($tr->tnp1);
	    $tnp1_prices = DAO::getSingleValue($link, "SELECT tnp1 FROM ob_learner_skills_analysis WHERE tr_id = '{$tr->id}'");
        $tnp1_prices = is_null($tnp1_prices) ? [] : json_decode($tnp1_prices);
        $tnp1_costs = array_map(function ($ar) {return $ar->cost;}, $tnp1_prices);
        $tnp1_total = array_sum(array_map('floatval', $tnp1_costs));
        
        $tnp_total = ceil($tnp1_total + $tr->epa_price);
        $additional_prices = (is_null($tr->additional_prices) || $tr->additional_prices == 0) ? [] : json_decode($tr->additional_prices);

	if ($employer->funding_type == "L") // show first box only
        {
            $s9boxes = '1';
        } 
        else 
        {
            if (in_array($employer->code, [1, 2, 3, 6]) || $learner_age >= 19) // then show 2nd and 3rd box
            {
                $s9boxes = '23';
            } 
            else // small employer with less than 50 employees and learner is also < 19 years
            {
                $s9boxes = '4';
            }
        }

	if ($employer->funding_type == "L") // show first box only
        {
            $s91boxes = '1';
        } 
        else 
        {
            if (in_array($employer->code, [1, 2, 3, 6]) || $learner_age > 21) // then show 2nd and 3rd box
            {
                $s91boxes = '23';
            } 
            else // small employer with less than 50 employees and learner is also < 21 years
            {
                $s91boxes = '4';
            }
        }

	$document_term = in_array(DB_NAME, ["am_ela"]) ? "agreement" : "contract";

        include_once ('tpl_sign_employer_schedule.php');
    }
}
?>