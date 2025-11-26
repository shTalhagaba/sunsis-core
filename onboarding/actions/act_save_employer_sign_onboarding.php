<?php
class save_employer_sign_onboarding implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        if(trim($tr_id) != '' && trim($key) != '')
        {
            if(!OnboardingHelper::isValidEmployerAppAgreementUrl($link, $tr_id, $key))
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
        $tr->emp_dec = $_POST['emp_dec'];
        $tr->emp_sign_name = isset($_POST['emp_sign_name']) ? $_POST['emp_sign_name'] : '';
        $tr->emp_sign = isset($_POST['emp_sign']) ? $_POST['emp_sign'] : '';
        $tr->emp_sign_date = isset($_POST['emp_sign_date']) ? $_POST['emp_sign_date'] : '';

        $tr->save($link);

        $tr->generateSignatureImages($link);

	// wages and employment
        $ob_learner_wae = DAO::getObject($link, "SELECT * FROM ob_learner_wae WHERE tr_id = '$tr->id'");
        if(!isset($ob_learner_wae->tr_id))
        {
            $ob_learner_wae = new stdClass();
        }
        $ob_learner_wae->tr_id = $tr->id;
        $ob_learner_wae->opt1 = isset($_POST['opt1']) ? $_POST['opt1'] : '';
        $ob_learner_wae->opt2 = isset($_POST['opt2']) ? $_POST['opt2'] : '';
        $ob_learner_wae->opt3 = isset($_POST['opt3']) ? $_POST['opt3'] : '';
        $ob_learner_wae->opt4 = isset($_POST['opt4']) ? $_POST['opt4'] : '';
        DAO::saveObjectToTable($link, "ob_learner_wae", $ob_learner_wae);

        //$tr->generateEmployerAppAgreementPdf($link);

        EmployerAgreement::generateCompletionPage($link);

        $_POST = null;
        unset($_POST);
    }
}
?>