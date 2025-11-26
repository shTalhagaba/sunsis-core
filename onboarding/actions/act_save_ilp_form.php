<?php
class save_ilp_form implements IAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_POST['tr_id']) ? $_POST['tr_id'] : '';
        if($tr_id == '') 
        {
            throw new Exception('Training ID is required');
        }

        $_POST = Helpers::utf8_sanitize_recursive($_POST);

        $ilp_form = (object) [
            'tr_id' => $tr_id,
            'form_data' => json_encode($_POST),
        ];
        DAO::saveObjectToTable($link, "ob_learner_ilp_form", $ilp_form);

        http_redirect('do.php?_action=read_training&id=' . $tr_id);
    }
}