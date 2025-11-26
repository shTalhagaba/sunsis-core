<?php
class save_employer_sign_commercial_ob implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        if(trim($tr_id) != '' && trim($key) != '')
        {
            if(!OnboardingHelper::isValidEmployerSignCommUrl($link, $tr_id, $key))
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
        $ob_learner = $tr->getObLearnerRecord($link);
        if(is_null($ob_learner))
        {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }

        $employer = Employer::loadFromDatabase($link, $tr->employer_id);
        if(is_null($employer))
        {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }

        $tr = TrainingRecord::loadFromDatabase($link, $_POST['tr_id']);
        $tr->emp_dec = isset($_POST['emp_dec']) ? $_POST['emp_dec'] : '';
        $tr->emp_sign_name = isset($_POST['emp_sign_name']) ? $_POST['emp_sign_name'] : '';
        $tr->emp_sign = isset($_POST['emp_sign']) ? $_POST['emp_sign'] : '';
        $tr->emp_sign_date = isset($_POST['emp_sign_date']) ? $_POST['emp_sign_date'] : '';

        $tr->save($link);

        $tr->generateSignatureImages($link);

	    EmployerAgreement::generateCompletionPage($link);

        $_POST = null;
        unset($_POST);
    }
}
?>