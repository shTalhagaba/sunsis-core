<?php

class form_learner_initial_assessment implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $key = isset($_REQUEST['key']) ? $_REQUEST['key'] : '';
        $subject = isset($_REQUEST['subject']) ? $_REQUEST['subject'] : '';

        if (trim($key) == '' || trim($subject) == '') {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }

        if (!$this->isValidSubject($link, $subject)) {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }

        $assessment = InitialAssessmentHelper::getAssessmentByKey($link, $key);

        $trainingId = isset($assessment->tr_id) ? $assessment->tr_id : '';
        if ($trainingId == '') {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }

        $tr = TrainingRecord::loadFromDatabase($link, $trainingId);
        if (is_null($tr)) {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }

        $ob_learner = $tr->getObLearnerRecord($link);
        $employer = Employer::loadFromDatabase($link, $tr->employer_id);
        $framework = Framework::loadFromDatabase($link, $tr->framework_id);

        $ob_header_image1 = SystemConfig::getEntityValue($link, 'ob_header_image1');
        $ob_header_image2 = SystemConfig::getEntityValue($link, 'ob_header_image2');

        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);

        $header_image1 = $provider->provider_logo == '' ? SystemConfig::getEntityValue($link,
            "ob_header_image1") : $provider->provider_logo;

        if (in_array(DB_NAME, ["am_superdrug", "am_sd_demo"])) {
            $header_image1 = $employer->logoPath();
        }

        $provider_location = $provider->getMainLocation($link);

        $stages = $this->getQuestionsBySubject($link, $subject);

        $scroll_logic = 1;

        include_once('tpl_form_learner_initial_assessment.php');
    }

    protected function isValidSubject($link, $subject)
    {
        $subjects = DAO::getSingleColumn($link,
            "SELECT distinct subject FROM ob_tr_questions where subject IS NOT NULL");

        return !empty($subjects) && in_array($subject, $subjects);
    }

    protected function getQuestionsBySubject(PDO $link, $subject)
    {
        $questions = DAO::getResultset($link, "SELECT * FROM ob_tr_questions WHERE subject= '{$subject}' ORDER BY stage , stage_level,level ",
            DAO::FETCH_ASSOC);

        return Helpers::array_group_by($questions, ['stage', 'stage_level']);
    }
}