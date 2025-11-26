<?php
class save_choc implements IAction
{
	public function execute(PDO $link)
	{
        $choc = new Choc();
        $choc->populate($_POST);
        if($_POST['choc_type'] != 'Change of LLDD')
        {
            $_POST = array_map(function ($value) {
                return mb_convert_encoding($value, 'UTF-8');
            }, $_POST);
        }
        $choc->choc_details = json_encode($_POST);
        if(isset($_POST['comments']) && $_POST['comments'] != '')
        {
            $choc->comments = Choc::saveComments($link, $choc, $_POST['comments']);
        }

        $choc->save($link);

        if($choc->choc_status == "REFERRED TO LEARNER")
        {
            // create a notification for the learner
            $notification = new stdClass();
            $notification->user_id = $choc->created_by != '' ? $choc->created_by : DAO::getSingleValue($link, "SELECT users.id FROM users INNER JOIN tr ON users.username = tr.username WHERE tr.id = '{$choc->tr_id}'");
            $notification->detail = "Your request for <strong>{$choc->choc_type}</strong> has been referred back to you for further information.";
            $notification->type = "CHOC";
            $notification->link = "do.php?_action=read_training_record&id={$choc->tr_id}";
            DAO::saveObjectToTable($link, "user_notifications", $notification);
        }

        http_redirect('do.php?_action=read_training_record&id='.$_POST['tr_id'].'&tabChoc=1');
    }
}