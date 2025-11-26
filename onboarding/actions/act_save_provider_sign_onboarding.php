<?php
class save_provider_sign_onboarding implements IAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        $tr->tp_dec = $_POST['tp_dec'];
        $tr->tp_sign_name = isset($_POST['tp_sign_name']) ? $_POST['tp_sign_name'] : '';
        $tr->tp_sign = isset($_POST['tp_sign']) ? $_POST['tp_sign'] : '';
        $tr->tp_sign_date = isset($_POST['tp_sign_date']) ? $_POST['tp_sign_date'] : '';

        $tr->save($link);

        http_redirect($_SESSION['bc']->getPrevious());
    }
}
?>