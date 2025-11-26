<?php
class save_sign_employer_agreement implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $employer_id = isset($_POST['employer_id']) ? $_POST['employer_id'] : '';
        $key = isset($_POST['key'])?$_POST['key']:'';
        if(trim($id) != '' && trim($employer_id) != '' && trim($key) != '')
        {
            if(!EmployerAgreement::validateKey($link, $id, $employer_id, $key))
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

        $employer = Employer::loadFromDatabase($link, $employer_id);
        if(is_null($employer))
        {
            EmployerAgreement::generateErrorPage($link);
            exit;
        }
        
        $agreement = new EmployerAgreement();
        $agreement->populate($_POST);
        $agreement->status = EmployerAgreement::TYPE_SIGNED_BY_EMPLOYER;

        $minmis = [];
        foreach($_POST AS $key => $value)
        {
            if(substr($key, 0, 8) == 'minimis_')
            {
                $minmis[$key] = $value;
            }
        }
        //$agreement->de_minimis_state_aid = json_encode((object)$minmis);

        DAO::transaction_start($link);
        try
        {
            $agreement->save($link);

            // save employer signatures
            $employer_signatures_log = (object)[
                'entity_id' => $agreement->id,
                'entity_type' => 'employer_agreements',
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

//        EmployerAgreement::generateCompletionPage($link, $agreement->id);

        $_POST = null;
        unset($_POST);

        http_redirect('do.php?_action=employer_agreement_completed&k='.md5('sunesis_employer_agreement_form_completed_for_'.$agreement->id));

    }

}
?>