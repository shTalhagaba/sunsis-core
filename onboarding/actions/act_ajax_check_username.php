<?php
class ajax_check_username implements IAction
{
    public function execute(PDO $link)
    {
        header('Content-Type: text/plain');

        $username = array_key_exists('username', $_REQUEST) ? $_REQUEST['username']:null;

        if($username == '')
        {
            echo "1";
        }
        else
        {
            $user_id = DAO::getSingleValue($link, "SELECT id FROM users WHERE username='".addslashes($username)."'");
            echo is_null($user_id) ? "1":"0";
        }
    }
}
?>