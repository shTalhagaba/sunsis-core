<?php
class save_bc_registration implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $registration = new Registration();
        $key = isset($_POST['key'])?$_POST['key']:'';
        if(trim($key) != '')
        {
            $registrationId = BootcampHelper::isValidBootcampRegistrationUrl($link, $key);
            if($registrationId != '')
            {
                $registration = Registration::loadFromDatabase($link, $registrationId);
                if($registration->is_finished == 'Y')
                {
                    http_redirect('do.php?_action=bc_form_already_completed');
                }
            }
        }

        
        $registration->populate($_POST);

        // pre($registration);

        DAO::transaction_start($link);
        try
        {
            $registration->home_postcode = strtoupper($registration->home_postcode);
            $registration->workplace_postcode = strtoupper($registration->workplace_postcode);
            $registration->ni = strtoupper($registration->ni);

            if($registration->LLDD != 'Y')
            {
                $registration->llddcat = '';
                $registration->primary_lldd = '';
            }

            if($registration->is_finished == 'Y' && $registration->learner_sign != '')
            {
                $registration->learner_sign_date = date('Y-m-d');
            }
            
            $registration->save($link);    
            
            DAO::transaction_commit($link);
        }
        catch(Exception $ex)
        {
            DAO::transaction_rollback($link);
            throw new Exception($ex->getMessage());
        }

        http_redirect('do.php?_action=bc_form_completed');
    }
}