<?php
class save_user implements IAction
{
    public function execute(PDO $link)
    {
        $vo = new User();
        $vo->populate($_POST);

        // Ignore an empty password field
        if($vo->password == '')
        {
            $vo->password = NULL;
        }

        $this->validate($link, $vo);

        try
        {
            DAO::transaction_start($link);

            $vo->save($link);

            if(SystemConfig::getEntityValue($link, "send_password_reset_email") && $_POST['id'] != '' && $_POST['password'] != '')
            {
                $previous_pwd = DAO::getResultset($link, "SELECT pwd_sha1 FROM users WHERE users.id = '{$_POST['id']}'");
                $new_pwd = sha1($_POST['password']);
                if($previous_pwd != $new_pwd)
                {
                    $_p = $_POST['password'];
                    $mail_html = <<<HTML
<p>Your Sunesis password has been reset by System Administrator. Following is your new password:</p>
<br>
{$_p}
<br>
<p>Please change this password when you login to the system.</p>
HTML;

                    Emailer::html_mail($vo->work_email, "apprenticeships@perspective-uk.com", "Sunesis password reset", "", $mail_html);
                }
            }

            DAO::transaction_commit($link);
        }
        catch (Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        if(IS_AJAX)
        {
            echo $vo->id;
        }
        else
        {
            http_redirect('do.php?_action=read_user&id=' . $vo->id);
        }
    }

    private function validate(PDO $link, User $vo)
    {
        if($vo->id == '')
        {
            // Check password
            if($vo->password == '')
            {
                throw new Exception("New users must have a password. Creation of user aborted.");
            }

            // Check user does not exist
            $num_users = "SELECT COUNT(*) FROM users WHERE username='".addslashes(strtolower($vo->username))."';";
            $num_users = DAO::getSingleValue($link, $num_users);
            if($num_users > 0)
            {
                throw new Exception("Username '{$vo->username}' is already in use.  Please choose another username.");
            }
        }
    }
}