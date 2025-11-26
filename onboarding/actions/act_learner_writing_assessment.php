<?php
class learner_writing_assessment implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : ''; // $id is the training record id
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        if(trim($id) != '' && trim($key) != '')
        {
            if(!OnboardingHelper::isValidFreeWritingAssessmentUrl($link, $id, $key))
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

        $tr = TrainingRecord::loadFromDatabase($link, $id);
        if(is_null($tr))
        {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }

        $assessment = DAO::getObject($link, "SELECT * FROM ob_learner_writing_assessment WHERE tr_id = '{$tr->id}'");
        if(!isset($assessment->tr_id))
        {
            $assessment = new stdClass();
            $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM ob_learner_writing_assessment");
            foreach($records AS $_key => $value)
                $assessment->$value = null;
            $assessment->tr_id = $tr->id;

        }

        if($assessment->learner_sign != '')
        {
            OnboardingHelper::generateAlreadyCompletedPage($link, $tr->id);
            exit;
        }
        $ob_learner = $tr->getObLearnerRecord($link);

        $ob_header_image1 = SystemConfig::getEntityValue($link, 'ob_header_image1');
        $ob_header_image2 = SystemConfig::getEntityValue($link, 'ob_header_image2');

        $scroll_logic = 1;

        $header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");

        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);

        $framework = Framework::loadFromDatabase($link, $tr->framework_id);

        if($tr->trainers != '')
            $trainer = User::loadFromDatabaseById($link, $tr->trainers);
        else
            $trainer = new User();

	$required_words = $framework->writing_assessment_chars != '' ? $framework->writing_assessment_chars : 300;

        include_once('tpl_learner_writing_assessment.php');
    }
}