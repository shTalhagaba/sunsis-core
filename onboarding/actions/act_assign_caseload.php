<?php
class assign_caseload implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        if($subaction == 'save_caseload')
        {
            $this->save_caseload($link);
            exit;
        }

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=assign_caseload", "Assign Caseload");

        $view = ViewObLearners::getInstance($link);
        if(!$_SESSION['user']->isAdmin())
        {
            $view->refresh($link, [
                '_reset' => 1,
                'ViewObLearners_filter_provider' => $_SESSION['user']->employer_id,
                'ViewObLearners_filter_have_trainer' => 1
            ]);
        }
        else
        {
            $view->refresh($link, [
                '_reset' => 1,
                'ViewObLearners_filter_have_trainer' => 1
            ]);
        }

        require_once('tpl_assign_caseload.php');
    }

    public function save_caseload(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        if($tr_id == '')
            return;
        $trainer = isset($_REQUEST['trainer']) ? $_REQUEST['trainer'] : '';
        $trainer = $trainer == 'null' ? '' : $trainer;

        $save = (object)[
            'id' => $tr_id,
            'trainers' => $trainer
        ];

        DAO::saveObjectToTable($link, 'tr', $save);

        $trainer_email = DAO::getSingleValue($link, "SELECT work_email FROM users WHERE users.id = '{$trainer}'");
        if($trainer_email != '')
        {
            $template = DAO::getSingleValue($link, "SELECT template FROM email_templates WHERE template_type = 'EMAIL_TO_TRAINER_FOLLOWING_CASELOADING'");
            if($template != '')
            {
                $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
                $email_template = new EmailTemplate();
                $ready_template = $email_template->prepare($link, 'EMAIL_TO_TRAINER_FOLLOWING_CASELOADING', $tr);

                Emailer::notification_email($trainer_email,
                    'no-reply@perspective-uk.com',
                    '',
                    'Caseloading',
                    '',
                    $ready_template
                );
            }

        }

        echo 'success';
    }
}
?>