<?php
class save_tr_env_impct implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
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
        $ob_learner = $tr->getObLearnerRecord($link);

        $tr->personality_test = isset($_POST['personality_test']) ? substr($_POST['personality_test'], 0, 1499) : '';
        $tr->personality_test_saved_at = date('Y-m-d H:i:s');
        $tr->save($link);

        OnboardingHelper::generateCompletionPage($link, $tr->id);
    }

}