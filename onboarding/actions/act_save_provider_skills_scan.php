<?php
class save_provider_skills_scan implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_POST['id']) ? $_POST['id'] : '';

        $tr = TrainingRecord::loadFromDatabase($link, $id);

        $skills_analysis = $tr->getSkillsAnalysis($link);
        if($skills_analysis->signed_by_provider == 1)
            http_redirect($_SESSION['bc']->getPrevious());

        $provider_sign = $qs = $_POST['provider_sign'];
        if(filter_var($provider_sign, FILTER_VALIDATE_URL))
        {
            $qs = parse_url($_POST['provider_sign']);
            $qs = str_replace('_action=generate_image&', '', $qs['query']);
        }

        $skills_analysis->signed_by_provider = 1;
        $skills_analysis->provider_sign = $qs;
        $skills_analysis->provider_sign_date = date('Y-m-d');
        $skills_analysis->provider_user_id = $_SESSION['user']->id;
        $skills_analysis->rationale_by_provider = isset($_POST['rationale_by_provider']) ? $_POST['rationale_by_provider'] : null;
        $skills_analysis->is_eligible_after_ss = isset($_POST['is_eligible_after_ss']) ? $_POST['is_eligible_after_ss'] : null;
        $skills_analysis->ineligibility_reason = isset($_POST['ineligibility_reason']) ? $_POST['ineligibility_reason'] : null;

        // has duration been changed by the user because it was less than 12 months?
        if(isset($_POST['max_duration_fa']))
        {
            $skills_analysis->max_duration_fa = $_POST['max_duration_fa'];
            $skills_analysis->length_of_programme_practical_period = $skills_analysis->max_duration_fa;
            $skills_analysis->total_contracted_hours_full_apprenticeship = (floatval($skills_analysis->total_contracted_hours_per_year)/12)*floatval($skills_analysis->length_of_programme_practical_period);
            $skills_analysis->total_contracted_hours_full_apprenticeship = ceil($skills_analysis->total_contracted_hours_full_apprenticeship);
            $skills_analysis->minimum_percentage_otj_training = $skills_analysis->total_contracted_hours_full_apprenticeship*0.2;
            $skills_analysis->minimum_percentage_otj_training = ceil($skills_analysis->minimum_percentage_otj_training);
            $minimum_duration_part_time = floatval($skills_analysis->length_of_programme_practical_period*30)/floatval($tr->contracted_hours_per_week);
            $skills_analysis->minimum_duration_part_time = ceil($minimum_duration_part_time);
            $part_time_total_contracted_hours_full_apprenticeship = floatval($skills_analysis->total_contracted_hours_per_year/12)*floatval($skills_analysis->minimum_duration_part_time);
            $skills_analysis->part_time_total_contracted_hours_full_apprenticeship = ceil($part_time_total_contracted_hours_full_apprenticeship);
            $skills_analysis->part_time_otj_hours = floatval($skills_analysis->part_time_total_contracted_hours_full_apprenticeship)*0.2;
            $skills_analysis->part_time_otj_hours = ceil($skills_analysis->part_time_otj_hours);
        }

        DAO::saveObjectToTable($link, 'ob_learner_skills_analysis', $skills_analysis);

        $tr->length_of_programme_practical_period = $skills_analysis->length_of_programme_practical_period;
        $tr->total_contracted_hours_full_apprenticeship = $skills_analysis->total_contracted_hours_full_apprenticeship;
        $tr->minimum_percentage_otj_training = $skills_analysis->minimum_percentage_otj_training;
        $tr->status_code = TrainingRecord::STATUS_SS_SIGNED_BY_PROVIDER;
        $tr->save($link);

        //check if provider user has signature otherwise save it
        if($_SESSION['user']->signature == '')
        {
            $_SESSION['user']->signature = $qs;
            $_SESSION['user']->save($link);
        }

        //duration is less than 12 but still is marked as eligible so send an email.
        if($skills_analysis->max_duration_fa < 12 && $skills_analysis->is_eligible_after_ss == 'Y')
        {
            if(DB_NAME == "am_barnsley")
            {
                $framework_title = DAO::getSingleValue($link, "SELECT frameworks.title FROM frameworks WHERE frameworks.id = '{$tr->framework_id}'");
                $email_to = "appteam@barnsley.ac.uk";
                $email_from = "no-reply@perspective-uk.com";
                $subject = "Alert: Learner Eligibility";
                $message = <<<HTML
<p><strong>Alert</strong></p>

<p>{$tr->firstnames} {$tr->surname} on {$framework_title} has been approved as Eligible but the duration of the programme is less than 12 months.</p>
HTML;
                Emailer::html_mail($email_to, $email_from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
            }
        }

        if(!SOURCE_LOCAL)
        {
            $this->sendEmailToLearnerAboutOutcome($link, $tr, $skills_analysis->is_eligible_after_ss);
        }

        http_redirect('do.php?_action=read_training&id='.$tr->id);

    }

    private function sendEmailToLearnerAboutOutcome(PDO $link, TrainingRecord $tr, $is_eligible_after_ss)
    {
        if($is_eligible_after_ss == 'Y')
            $email_content = DAO::getSingleValue($link, "SELECT template FROM email_templates WHERE template_type = 'SKILLS_SCAN_PASSED' ");
        elseif($is_eligible_after_ss == 'N')
            $email_content = DAO::getSingleValue($link, "SELECT template FROM email_templates WHERE template_type = 'SKILLS_SCAN_FAILED' ");
        else
            return;

        if($email_content == '')
            return;

        $email_template = new EmailTemplate();
        if($is_eligible_after_ss == 'Y')
            $ready_template = $email_template->prepare($link, 'SKILLS_SCAN_PASSED', $tr);
        elseif($is_eligible_after_ss == 'N')
            $ready_template = $email_template->prepare($link, 'SKILLS_SCAN_FAILED', $tr);

        $ob_learner = $tr->getObLearnerRecord($link);

        Emailer::notification_email($ob_learner->home_email,
            'no-reply@perspective-uk.com',
            '',
            'Skills Scan Outcome',
            '',
            $ready_template
        );

    }

}