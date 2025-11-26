<?php
class sign_fdil_learner implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : ''; // $id is the training record id
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        if(trim($id) != '' && trim($key) != '')
        {
            if(!OnboardingHelper::isValidPersonalityTestUrl($link, $id, $key))
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
        if($tr->personality_test != '')
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

        include_once('tpl_sign_fdil_learner.php');
    }
}