<?php

class EmailTemplate
{
    public function prepare(PDO $link, $template_type, TrainingRecord $tr, EmployerSchedule1 $schedule = null)
    {
        $template = '';
        if ($template_type == '') {
            return $template;
        }

        $template = DAO::getSingleValue($link, "SELECT template FROM email_templates WHERE template_type = '{$template_type}'");
        if ($template == '') {
            return $template;
        }

        // hook for EET site.
        if (DB_NAME == "am_eet") {
            $template = $this->customContactDetailsAndLogo($link, $template, $tr);
        }


        $class = new ReflectionClass(__CLASS__);
        $methods = $class->getMethods();
        foreach ($methods as $method) {
            $method_name = $method->getName();
            if (substr($method_name, 0, 7) != 'replace') {
                continue;
            }

            if ($method_name == 'replaceInitialAssessmentUrlsInformation') {
                $template = $this->$method_name($link, $template, $tr, $template_type);
            } else {
                $template = $this->$method_name($link, $template, $tr, $schedule);
            }
        }

        return $template;
    }

    private function customContactDetailsAndLogo(PDO $link, $template, TrainingRecord $tr)
    {
        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);
        $providerLocation = Location::loadFromDatabase($link, $tr->provider_location_id);
        if (is_null($providerLocation)) {
            return;
        }

        $template = str_replace('$$CLIENT_EMAIL$$', $providerLocation->contact_email, $template);
        $template = str_replace('$$CLIENT_TELEPHONE$$', $providerLocation->telephone, $template);
        $template = str_replace('$$PROVIDER_LEGALNAME$$', $provider->legal_name, $template);

        $providerLogoUrl = SystemConfig::getEntityValue($link, 'email_logo');
        if (!is_null($provider->provider_logo)) {
            $parsedUrl = parse_url($providerLogoUrl);
            $providerLogoUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . '/' . $provider->provider_logo;
        }

        $template = str_replace('$$LOGO$$', '<img title="' . $provider->legal_name . '" src="' . $providerLogoUrl . '" alt="' . $provider->legal_name . '" />', $template);

        return $template;
    }

    private function replaceCommonKeyWords(PDO $link, $template, TrainingRecord $tr)
    {
        $template = str_replace('$$CLIENT_EMAIL$$', SystemConfig::getEntityValue($link, 'client_email'), $template);
        $template = str_replace('$$CLIENT_TELEPHONE$$', SystemConfig::getEntityValue($link, 'client_telephone'), $template);

        if (DB_NAME == "am_superdrug") {
            $org = Organisation::loadFromDatabase($link, $tr->employer_id);
            if ($org->isSavers()) {
                $template = str_replace('$$LOGO$$', '<img title="Logo" src="https://sd-onboarding.sunesis.uk.net/images/logos/Savers.png" alt="Logo" />', $template);
            } else {
                $template = str_replace('$$LOGO$$', '<img title="Logo" src="' . SystemConfig::getEntityValue($link, 'email_logo') . '" alt="Logo" />', $template);
            }
        } else {
            $template = str_replace('$$LOGO$$', '<img title="Logo" src="' . SystemConfig::getEntityValue($link, 'email_logo') . '" alt="Logo" />', $template);
        }

        return $template;
    }

    private function replaceLearnerInformation(PDO $link, $template, TrainingRecord $tr)
    {
        $ob_learner = $tr->getObLearnerRecord($link);

        $template = str_replace('$$LEARNER_FIRSTNAME$$', $ob_learner->firstnames, $template);
        $template = str_replace('$$OB_LEARNER_NAME$$', $ob_learner->firstnames . ' ' . $ob_learner->surname, $template);
        $template = str_replace('$$LEARNER_FULL_NAME$$', $ob_learner->firstnames . ' ' . $ob_learner->surname, $template);

        return $template;
    }

    private function replaceApprenticeshipInformation(PDO $link, $template, TrainingRecord $tr)
    {
        $framework = Framework::loadFromDatabase($link, $tr->framework_id);
        $programme_level = DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';");

        $template = str_replace('$$APPRENTICESHIP_PROGRAMME_TITLE$$', $framework->title, $template);
        $template = str_replace('$$APPRENTICESHIP_PROGRAMME_LEVEL$$', $programme_level, $template);

        return $template;
    }

    private function replaceInitialAssessmentUrlsInformation(PDO $link, $template, TrainingRecord $tr, $template_type)
    {
        if (in_array($template_type, ['INITIAL_ASSESSMENT_MATH', 'INITIAL_ASSESSMENT_ENGLISH'])) {

            $subject = strtolower(substr($template_type, strrpos($template_type, '_') + 1));

            $url = InitialAssessmentHelper::generateUrl($link, $tr->id, $subject);

            $template = str_replace('$$' . $template_type . '_URL$$', '<a href="' . $url . '">Initial Assessment Form for ' . ucfirst($subject) . '</a> ', $template);
            $template = str_replace('$$' . $template_type . '_URL_AS_IT_IS$$', $url, $template);
        }
        return $template;
    }

    private function replaceUrlsInformation(PDO $link, $template, TrainingRecord $tr, EmployerSchedule1 $schedule = null)
    {
        $sa_id = DAO::getSingleValue($link, "SELECT id FROM ob_learner_skills_analysis WHERE tr_id = '{$tr->id}' ORDER BY id DESC LIMIT 1");

        $template = str_replace('$$SKILLS_SCAN_URL$$', '<a href="' . OnboardingHelper::generateSkillsScanUrl($sa_id) . '">Skills Scan Form</a> ', $template);
        $template = str_replace('$$SKILLS_SCAN_URL_AS_IT_IS$$', OnboardingHelper::generateSkillsScanUrl($sa_id), $template);
        $template = str_replace('$$ONBOARDING_URL$$', '<a href="' . OnboardingHelper::generateOnboardingUrl($tr->id) . '">Onboarding URL</a> ', $template);
        $template = str_replace('$$ONBOARDING_URL_AS_IT_IS$$', OnboardingHelper::generateOnboardingUrl($tr->id), $template);

        $template = str_replace('$$ENROLMENT_URL$$', '<a href="' . OnboardingHelper::generateOnboardingUrl($tr->id) . '">Enrolment URL</a> ', $template);
        $template = str_replace('$$ENROLMENT_URL_AS_IT_IS$$', OnboardingHelper::generateOnboardingUrl($tr->id), $template);

        // $schedule = $tr->getEmployerAgreementSchedule1($link);

        if (!is_null($schedule)) {
            $template = str_replace('$$EMPLOYER_SCHEDULE_URL$$', '<a href="' . OnboardingHelper::generateEmployerScheduleUrl($schedule->id, $schedule->employer_id, $schedule->tr_id) . '">Initial Contract</a> ', $template);
            $template = str_replace('$$EMPLOYER_SCHEDULE_URL_AS_IT_IS$$', OnboardingHelper::generateEmployerScheduleUrl($schedule->id, $schedule->employer_id, $schedule->tr_id), $template);
        }

        $template = str_replace('$$NON_APP_ENROLMENT_URL$$', '<a href="' . OnboardingHelper::generateNonAppEnrolmentUrl($tr->id) . '">Enrolment Form </a> ', $template);
        $template = str_replace('$$NON_APP_ENROLMENT_URL_AS_IT_IS$$', OnboardingHelper::generateNonAppEnrolmentUrl($tr->id), $template);

        $template = str_replace('$$ONBOARDING_EMPLOYER_URL$$', '<a href="' . OnboardingHelper::generateEmployerAppAgreementUrl($tr->id) . '">Onboarding Employer URL </a> ', $template);
        $template = str_replace('$$ONBOARDING_EMPLOYER_URL_AS_IT_IS$$', OnboardingHelper::generateEmployerAppAgreementUrl($tr->id), $template);

        $template = str_replace('$$COMM_ONBOARDING_EMPLOYER_URL$$', '<a href="' . OnboardingHelper::generateEmployerSignCommUrl($tr->id) . '">Onboarding Employer URL </a> ', $template);
        $template = str_replace('$$COMM_ONBOARDING_EMPLOYER_URL_AS_IT_IS$$', OnboardingHelper::generateEmployerSignCommUrl($tr->id), $template);

        $template = str_replace('$$FIRST_DAY_OF_LEARNING_URL$$', '<a href="' . OnboardingHelper::generateFreeWritingAssessmentUrl($tr->id) . '">Click to Access Writing Assessment</a> ', $template);
        $template = str_replace('$$FIRST_DAY_OF_LEARNING_URL_AS_IT_IS$$', OnboardingHelper::generateFreeWritingAssessmentUrl($tr->id), $template);

        $template = str_replace('$$PRE_IAG_FORM_URL$$', '<a href="' . OnboardingHelper::generatePreIagFormUrl($tr->id) . '">Click to Access Pre IAG Form</a> ', $template);
        $template = str_replace('$$PRE_IAG_FORM_URL_AS_IT_IS$$', OnboardingHelper::generatePreIagFormUrl($tr->id), $template);

        $template = str_replace('$$BESPOKE_TRAINING_PLAN_URL$$', '<a href="' . OnboardingHelper::generateBespokeTrainingPlanFormUrl($tr->id) . '">Click to Access</a> ', $template);
        $template = str_replace('$$BESPOKE_TRAINING_PLAN_URL_AS_IT_IS$$', OnboardingHelper::generateBespokeTrainingPlanFormUrl($tr->id), $template);

        $template = str_replace('$$WELLBEING_ASSESSMENT_URL$$', '<a href="' . OnboardingHelper::generateWellbeingAssessmentFormUrl($tr->id) . '">Click to Access</a> ', $template);
        $template = str_replace('$$WELLBEING_ASSESSMENT_URL_AS_IT_IS$$', OnboardingHelper::generateWellbeingAssessmentFormUrl($tr->id), $template);

        $template = str_replace('$$LEARN_STYLE_ASSESSMENT_URL$$', '<a href="' . OnboardingHelper::generateLearnStyleAssessmentUrl($tr->id) . '">Click to Access Learning Styles Assessment Form</a> ', $template);
        $template = str_replace('$$LEARN_STYLE_ASSESSMENT_URL_AS_IT_IS$$', OnboardingHelper::generateLearnStyleAssessmentUrl($tr->id), $template);

        $template = str_replace('$$FDIL_SESSION_LEARNER_URL$$', '<a href="' . OnboardingHelper::generateLearnerFdilUrl($tr->id) . '">Click to Access</a> ', $template);
        $template = str_replace('$$FDIL_SESSION_LEARNER_URL$$', OnboardingHelper::generateLearnerFdilUrl($tr->id), $template);

        $template = str_replace('$$FDIL_SESSION_TUTOR_URL$$', '<a href="' . OnboardingHelper::generateTutorFdilUrl($tr->id) . '">Click to Access</a> ', $template);
        $template = str_replace('$$FDIL_SESSION_TUTOR_URL$$', OnboardingHelper::generateTutorFdilUrl($tr->id), $template);

        $template = str_replace('$$OTJ_LEARNER_URL$$', '<a href="' . OnboardingHelper::generateOtjPlannerLearnerViewUrl($tr->id) . '">Click to Access</a> ', $template);
        $template = str_replace('$$OTJ_LEARNER_URL_AS_IT_IS$$', OnboardingHelper::generateOtjPlannerLearnerViewUrl($tr->id), $template);

        $template = str_replace('$$DP_LEARNER_URL$$', '<a href="' . OnboardingHelper::generateDpLearnerViewUrl($tr->id) . '">Click to Access</a> ', $template);
        $template = str_replace('$$DP_LEARNER_URL_AS_IT_IS$$', OnboardingHelper::generateDpLearnerViewUrl($tr->id), $template);

        $template = str_replace('$$OTJ_EMPLOYER_URL$$', '<a href="' . OnboardingHelper::generateOtjPlannerEmployerViewUrl($tr->id) . '">Click to Access</a> ', $template);
        $template = str_replace('$$OTJ_EMPLOYER_URL_AS_IT_IS$$', OnboardingHelper::generateOtjPlannerEmployerViewUrl($tr->id), $template);

        $template = str_replace('$$DP_EMPLOYER_URL$$', '<a href="' . OnboardingHelper::generateDpEmployerViewUrl($tr->id) . '">Click to Access</a> ', $template);
        $template = str_replace('$$DP_EMPLOYER_URL_AS_IT_IS$$', OnboardingHelper::generateDpEmployerViewUrl($tr->id), $template);

        $template = str_replace('$$SKILLS_SCAN_EMPLOYER_URL$$', '<a href="' . OnboardingHelper::generateSkillsScanEmployerUrl($sa_id) . '">Click to Access</a> ', $template);
        $template = str_replace('$$SKILLS_SCAN_EMPLOYER_URL_AS_IT_IS$$', OnboardingHelper::generateSkillsScanEmployerUrl($sa_id), $template);

        return $template;
    }

    private function replaceTrainerInformation(PDO $link, $template, TrainingRecord $tr)
    {
        $trainers = $tr->trainers != '' ? explode(",", $tr->trainers) : [];
        $trainer_id = isset($trainers[0]) ? $trainers[0] : '';
        if ($trainer_id == '') {
            return $template;
        }

        $trainer_record = DAO::getObject($link, "SELECT firstnames, surname, work_email FROM users WHERE users.id = '{$trainer_id}'");
        if (!isset($trainer_record->firstnames)) {
            return $template;
        }

        $template = str_replace('$$TRAINER_FIRSTNAME$$', $trainer_record->firstnames, $template);
        $template = str_replace('$$TRAINER_FULLNAME$$', $trainer_record->firstnames . ' ' . $trainer_record->surname, $template);

        return $template;
    }

    private function replaceEmployerInformation(PDO $link, $template, TrainingRecord $tr)
    {
        $employer = Employer::loadFromDatabase($link, $tr->employer_id);

        $location = $employer->getMainLocation($link);

        $primary_contact_name = $location->contact_name;
        if ($primary_contact_name == '') {
            $primary_contact_email_sql = <<<SQL
SELECT
  organisation_contacts.`contact_email`, organisation_contacts.`contact_name`
FROM
  organisation_contacts
WHERE organisation_contacts.`org_id` = '{$employer->id}'
  AND organisation_contacts.`job_role` = 99
  AND organisation_contacts.`contact_email` IS NOT NULL
ORDER BY organisation_contacts.`contact_id` DESC
LIMIT 1;
SQL;
            $primary_contact = DAO::getObject($link, $primary_contact_email_sql);
            if (isset($primary_contact->contact_name)) {
                $primary_contact_name = $primary_contact->contact_name;
            }
        }

        $template = str_replace('$$EMPLOYER_NAME$$', $employer->legal_name, $template);
        $template = str_replace('$$EMPLOYER_CONTACT_FIRST_NAME$$', $primary_contact_name, $template);

        return $template;
    }

    private function replaceProviderInformation(PDO $link, $template, TrainingRecord $tr)
    {
        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);

        $template = str_replace('$$PROVIDER_NAME$$', $provider->legal_name, $template);

        return $template;
    }

    private function replaceBksbInformation(PDO $link, $template, TrainingRecord $tr)
    {
        return $template;
        $ob_learner = $tr->getObLearnerRecord($link);

        if ($ob_learner->bksb_login == '') {
            return $template;
        }

        $bksb_login = json_decode($ob_learner->bksb_login);
        if (!isset($bksb_login->Link) || $bksb_login->Link == '') {
            return $template;
        }

        $expiry = new Date($bksb_login->Expiry);

        $template = str_replace('$$BKSB_AUTO_LOGIN_URL$$', $bksb_login->Link, $template);
        $template = str_replace('$$BKSB_LOGIN_URL_EXPIRY_DATE$$', $expiry->formatShort(), $template);
        $template = str_replace('$$BKSB_LOGIN_URL_EXPIRY_TIME$$', $expiry->format('H:i:s'), $template);
        $template = str_replace('$$BKSB_USERNAME$$', $ob_learner->bksb_username, $template);

        return $template;
    }

    private function replaceSuperdrugSaversInfo(PDO $link, $template, TrainingRecord $tr)
    {
        if (DB_NAME != "am_superdrug") {
            return $template;
        }

        $org = Organisation::loadFromDatabase($link, $tr->employer_id);
        $isSavers = $org->isSavers();

        if ($org->isSavers()) {
            $template = str_replace('Superdrug', 'Savers', $template);
        }

        return $template;
    }
}