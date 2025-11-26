<?php
class save_sign_employer_hs_form implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $id = isset($_POST['hs_id']) ? $_POST['hs_id'] : '';
        $employer_id = isset($_POST['employer_id']) ? $_POST['employer_id'] : '';
        $key = isset($_POST['key'])?$_POST['key']:'';
        if(trim($id) != '' && trim($employer_id) != '' && trim($key) != '')
        {
            if(!EmployerHealthAndSafetyForm::validateKey($link, $id, $employer_id, $key))
            {
                EmployerAgreement::generateErrorPage($link);
                exit;
            }
        }
        else
        {
            EmployerAgreement::generateErrorPage($link);
            exit;
        }

        $hs = EmployerHealthAndSafety::loadFromDatabaseById($link, $id);
        if(is_null($hs))
        {
            EmployerHealthAndSafetyForm::generateErrorPage($link);
            exit;
        }

        $employer = Employer::loadFromDatabase($link, $employer_id);
        if(is_null($employer))
        {
            EmployerHealthAndSafetyForm::generateErrorPage($link);
            exit;
        }

        DAO::transaction_start($link);
        try
        {
            $hs_form = $hs->getHsForm($link);
            $_POST = Helpers::utf8_sanitize_recursive($_POST);
            $hs_form->detail = json_encode($_POST);
            $hs_form->nature_of_business = isset($_POST['nature_of_business']) ? substr($_POST['nature_of_business'], 0, 799) : '';
            $hs->status = EmployerHealthAndSafety::TYPE_SIGNED_BY_EMPLOYER;
            $hs->employer_sign_name = isset($_REQUEST['employer_sign_name']) ? $_REQUEST['employer_sign_name'] : '';
            $hs->employer_sign = isset($_REQUEST['employer_sign']) ? $_REQUEST['employer_sign'] : '';
            $hs->employer_sign_date = date('Y-m-d');

	    $hs->el_insurer = isset($_POST['el_insurer']) ? $_POST['el_insurer'] : $hs->el_insurer;
            $hs->el_insurance = isset($_POST['el_insurance']) ? $_POST['el_insurance'] : $hs->el_insurance;
            $hs->el_date = isset($_POST['el_date']) ? $_POST['el_date'] : $hs->el_date;

            $hs->save($link);


            $hs_form->save($link);

            // save employer signatures
            $employer_signatures_log = (object)[
                'entity_id' => $hs->id,
                'entity_type' => 'health_safety',
                'user_sign' => $_POST['employer_sign'],
                'user_sign_date' => $_POST['employer_sign_date'],
                'user_sign_name' => $_POST['employer_sign_name'],
                'user_type' => 'EMPLOYER',
            ];

            DAO::saveObjectToTable($link, "documents_signatures", $employer_signatures_log);


            DAO::transaction_commit($link);
        }
        catch (Exception $ex)
        {
            DAO::transaction_rollback($link);
            throw new Exception($ex->getMessage());
        }
       


        EmployerHealthAndSafetyForm::generateCompletionPage($link, $hs->id);
    }
}
?>