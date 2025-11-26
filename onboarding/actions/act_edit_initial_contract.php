<?php
class edit_initial_contract implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        if($id == '' && $tr_id == '')
            throw new Exception("Missing querystring argument: tr_id");

        if($id == '')
        {
            $schedule = new EmployerSchedule1();
            $schedule->tr_id = $tr_id;
        }
        else
        {
            $schedule = EmployerSchedule1::loadFromDatabase($link, $id);
            if($schedule->tr_id != $tr_id)
                throw new Exception("Invalid tr_id for this record.");
        }
        
        $tr = TrainingRecord::loadFromDatabase($link, $schedule->tr_id);
        if(is_null($tr))
            throw new Exception("Invalid tr_id");

        $employer = Employer::loadFromDatabase($link, $tr->employer_id);    
        $employer_location = Location::loadFromDatabase($link, $tr->employer_location_id);
	    $mainLocation = $employer->getMainLocation($link);
        $ob_learner = $tr->getObLearnerRecord($link);

        $_SESSION['bc']->add($link, "do.php?_action=edit_initial_contract&id={$schedule->id}tr_id={$schedule->tr_id}", "Add/Edit Initial Contract");

        $detail = $schedule->detail != '' ? json_decode($schedule->detail) : null;

        $apprentice_job_title = (isset($detail->apprentice_job_title) && $detail->apprentice_job_title != '') ? $detail->apprentice_job_title : $tr->job_title;

        $framework = Framework::loadFromDatabase($link, $tr->framework_id);
        $training_cost = DAO::getSingleValue($link, "SELECT Round(MaxEmployerLevyCap) FROM lars201718.Core_LARS_ApprenticeshipFunding WHERE ApprenticeshipCode = '{$framework->StandardCode}' and ApprenticeshipType='STD' ORDER BY EffectiveFrom LIMIT 0,1");

        $_trainer_type = User::TYPE_ASSESSOR;
        $ddlTrainers = DAO::getResultset($link, "SELECT users.id, CONCAT(firstnames, ' ', surname), null FROM users WHERE users.employer_id = '{$tr->provider_id}' AND users.type = '{$_trainer_type}' AND users.web_access = '1' ORDER BY firstnames");

        if($employer_location->contact_email == '')
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
            $primary_contact_email = $employer_location->contact_email;
        }

        $logo = DAO::getSingleValue($link, "SELECT provider_logo FROM organisations WHERE id = '{$tr->provider_id}'");
        if($logo == '')
            $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

	$practicalPeriodStartDate = $tr->practical_period_start_date;
        $learner_age_sql = <<<SQL
SELECT 
    ((DATE_FORMAT('{$practicalPeriodStartDate}','%Y') - DATE_FORMAT('{$ob_learner->dob}','%Y')) - (DATE_FORMAT('{$practicalPeriodStartDate}','00-%m-%d') < DATE_FORMAT('{$ob_learner->dob}','00-%m-%d'))) AS age        
SQL;
        $learner_age = DAO::getSingleValue($link, $learner_age_sql);

        //$tnp1_prices = is_null($tr->tnp1) ? [] : json_decode($tr->tnp1);
	    $tnp1_prices = DAO::getSingleValue($link, "SELECT tnp1 FROM ob_learner_skills_analysis WHERE tr_id = '{$tr->id}'");
        $tnp1_prices = is_null($tnp1_prices) ? [] : json_decode($tnp1_prices);
        $tnp1_costs = array_map(function ($ar) {return $ar->cost;}, $tnp1_prices);
        $tnp1_total = array_sum(array_map('floatval', $tnp1_costs));
        $tnp_total = ceil($tnp1_total + $tr->epa_price);
        $additional_prices = is_null($tr->additional_prices) ? [] : json_decode($tr->additional_prices);
	
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

        include_once('tpl_edit_initial_contract.php');
    }
}