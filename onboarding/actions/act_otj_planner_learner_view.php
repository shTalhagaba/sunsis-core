<?php
class otj_planner_learner_view implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : ''; 
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        if(trim($tr_id) != '' && trim($key) != '')
        {
            if(!OnboardingHelper::isValidOtjPlannerLearnerViewUrl($link, $tr_id, $key))
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

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
        {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }

        $otj_planner_signatures = DAO::getObject($link, "SELECT * FROM otj_planner_signatures WHERE tr_id = '{$tr->id}'");
        if(isset($otj_planner_signatures->learner_sign) && $otj_planner_signatures->learner_sign != '')
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



        include_once('tpl_otj_planner_learner_view.php');
    }
}