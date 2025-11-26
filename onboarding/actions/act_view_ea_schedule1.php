<?php
class view_ea_schedule1 implements IAction
{
    public function execute(PDO $link)
    {
        $employer_id = isset($_REQUEST['employer_id']) ? $_REQUEST['employer_id'] : '';
        if($employer_id == '')
            throw new Exception("Missing querystring argument: employer_id");

        $employer = Employer::loadFromDatabase($link, $employer_id);
        if(is_null($employer))
            throw new Exception("Invalid employer_id");

        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        if($tr_id == '')
            throw new Exception("Missing querystring argument: tr_id");

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
            throw new Exception("Invalid tr_id");

        $ob_learner = $tr->getObLearnerRecord($link);

        $location_id = DAO::getSingleValue($link, "SELECT id FROM locations WHERE locations.organisations_id = '{$employer->id}' AND is_legal_address = '1'");
        $employer_location = Location::loadFromDatabase($link, $ob_learner->employer_location_id);

        $_SESSION['bc']->add($link, "do.php?_action=view_employer_agreement&tr_id={$tr->id}&employer_id={$employer->id}", "View Employer Agreement");

        $schedule = $employer->getAgreementSchedule($link, $tr_id);
        $detail = $schedule->detail != '' ? json_decode($schedule->detail) : null;

        $apprentice_job_title = (isset($detail->apprentice_job_title) && $detail->apprentice_job_title != '') ? $detail->apprentice_job_title : $tr->job_title;

        $skills_analysis = $tr->getSkillsAnalysis($link);

        $framework = Framework::loadFromDatabase($link, $tr->framework_id);
        $training_cost = DAO::getSingleValue($link, "SELECT Round(MaxEmployerLevyCap) FROM lars201718.Core_LARS_ApprenticeshipFunding WHERE ApprenticeshipCode = '{$framework->StandardCode}' and ApprenticeshipType='STD' ORDER BY EffectiveFrom LIMIT 0,1");

        $_trainer_type = User::TYPE_ASSESSOR;
        $ddlTrainers = DAO::getResultset($link, "SELECT users.id, CONCAT(firstnames, ' ', surname), null FROM users WHERE users.employer_id = '{$tr->provider_id}' AND users.type = '{$_trainer_type}' AND users.web_access = '1' ORDER BY firstnames");

        $avg_employees = DAO::getSingleValue($link, "SELECT avg_no_of_employees FROM employer_agreements WHERE employer_id = '{$employer->id}' ORDER BY id DESC");
        $avg_employees = $avg_employees == '' ? 0 : $avg_employees;
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

        $logo = DAO::getSingleValue($link, "SELECT provider_logo FROM organisations WHERE id = '{$tr->provider_id}'");
        if($logo == '')
            $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

        $learner_age_sql = <<<SQL
SELECT 
    ((DATE_FORMAT('{$tr->practical_period_start_date}','%Y') - DATE_FORMAT('{$ob_learner->dob}','%Y')) - (DATE_FORMAT('{$tr->practical_period_start_date}','00-%m-%d') < DATE_FORMAT('{$ob_learner->dob}','00-%m-%d'))) AS age        
SQL;
        $learner_age = DAO::getSingleValue($link, $learner_age_sql);

        include_once('tpl_view_ea_schedule1.php');
    }
}