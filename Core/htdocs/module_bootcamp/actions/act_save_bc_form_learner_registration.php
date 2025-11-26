<?php
class save_bc_form_learner_registration implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        include('/lib/bootcamp/BootcampHelper.php');
        
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        if(trim($id) != '' && trim($key) != '')
        {
            if(!BootcampHelper::isValidBootcampRegistrationUrl($id, $key))
            {
                http_redirect("do.php?_action=bc_error_page");
                exit;
            }
        }
        else
        {
            http_redirect("do.php?_action=bc_error_page");
            exit;
        }

        $learner = User::loadFromDatabaseById($link, $id);
        if(is_null($learner))
        {
            http_redirect("do.php?_action=bc_error_page");
            exit;
        }

        $extraInfo = UserExtraInfo::loadFromDatabase($link, $learner->id);
        if($extraInfo->is_finished == 'Y')
        {
            http_redirect("do.php?_action=bc_form_already_completed");
            exit;
        }

        $learner->populate($_POST);
        $extraInfo->populate(array_merge(['user_id' => $learner->id], $_POST));

        

        DAO::transaction_start($link);
        try
        {
            if($_POST['is_finished'] == 'N')
            {
                echo json_encode([
                    'learner' => $learner,
                    'extra' => $extraInfo,
                ]);
            }
            elseif($_POST['is_finished'] == 'Y')
            {
                $extraInfo->learner_sign_date = date('Y-m-d');
            }
            else
            {
                http_redirect("do.php?_action=bc_error_page");
                exit;
            }
        }
        catch(Exception $ex)
        {
            throw new Exception($ex->getMessage());
        }

        // pr($learner);
        // pre($extraInfo);

        http_redirect('do.php?_action=bc_form_completed');
    }
}