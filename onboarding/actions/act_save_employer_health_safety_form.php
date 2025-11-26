<?php
class save_employer_health_safety_form implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_POST['hs_id']) ? $_POST['hs_id'] : '';
        $employer_id = isset($_POST['employer_id']) ? $_POST['employer_id'] : '';

        $hs = EmployerHealthAndSafety::loadFromDatabaseById($link, $id);
        $employer = Employer::loadFromDatabase($link, $employer_id);

        DAO::transaction_start($link);
        try
        {
            $hs_form = $hs->getHsForm($link);
            $_POST = Helpers::utf8_sanitize_recursive($_POST);
            $hs_form->detail = json_encode($_POST);

	    $hs->el_insurer = isset($_POST['el_insurer']) ? $_POST['el_insurer'] : $hs->el_insurer;
            $hs->el_insurance = isset($_POST['el_insurance']) ? $_POST['el_insurance'] : $hs->el_insurance;
            $hs->el_date = isset($_POST['el_date']) ? $_POST['el_date'] : $hs->el_date;

            if(isset($_REQUEST['provider_sign']) && $_REQUEST['provider_sign'] != '')
            {
                $provider_signatures_log = (object)[
                    'entity_id' => $hs->id,
                    'entity_type' => 'health_safety',
                    'user_sign' => $_POST['provider_sign'],
                    'user_sign_date' => date('Y-m-d'),
                    'user_sign_name' => $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname,
                    'user_type' => 'PROVIDER',
                ];

                DAO::saveObjectToTable($link, "documents_signatures", $provider_signatures_log);

                $hs->provider_sign = $_POST['provider_sign'];
                $hs->provider_sign_name = $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname;
                $hs->provider_sign_date = date('Y-m-d');

                $hs->status = EmployerHealthAndSafety::TYPE_SIGNED_BY_PROVIDER;
            }
            // if(isset($_REQUEST['verifier_sign']) && $_REQUEST['verifier_sign'] != '')
            // {
            //     $verifier_signatures_log = (object)[
            //         'entity_id' => $hs->id,
            //         'entity_type' => 'health_safety',
            //         'user_sign' => $_POST['verifier_sign'],
            //         'user_sign_date' => date('Y-m-d'),
            //         'user_sign_name' => $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname,
            //         'user_type' => 'VERIFIER',
            //     ];

            //     DAO::saveObjectToTable($link, "documents_signatures", $verifier_signatures_log);

            //     $hs->verifier_sign = $_POST['verifier_sign'];
            //     $hs->verifier_sign_name = $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname;
            //     $hs->verifier_sign_date = date('Y-m-d');

            //     $hs->status = EmployerHealthAndSafety::TYPE_SIGNED_BY_VERIFIER;
            // }

            $hs->risk_category = isset($_POST['risk_category']) ? $_POST['risk_category'] : '';
            $hs->recommendation = isset($_POST['recommendation']) ? $_POST['recommendation'] : '';
            $hs->assessment_type_other = isset($_POST['assessment_type_other']) ? $_POST['assessment_type_other'] : '';
            $hs->assessment_type = isset($_POST['assessment_type']) ? $_POST['assessment_type'] : '';

            $hs->save($link);

            $hs_form->save($link);


            DAO::transaction_commit($link);
        }
        catch (Exception $ex)
        {
            DAO::transaction_rollback($link);
            throw new Exception($ex->getMessage());
        }
       


        http_redirect($_SESSION['bc']->getPrevious());
    }
}
?>