<?php
class read_system_user implements IAction
{
    public function execute(PDO $link)
    {
        $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

        if(!$username && !$id)
        {
            throw new Exception("Missing or empty querystring argument 'username' or 'id'");
        }

        if ($id != '')
        {
            $vo = User::loadFromDatabaseById($link, $id);
            if (is_null($vo))
            {
                throw new Exception("No user with id '$id'");
            }
        }
        else
        {
            $vo = User::loadFromDatabase($link, $username);
            if (is_null($vo))
            {
                throw new Exception("No user with username '$username'");
            }
        }

        $_SESSION['bc']->add($link, "do.php?_action=read_user&id={$vo->id}&username={$vo->username}", "View System User");

        $home_address = new Address($vo, 'home_');
        $work_address = new Address($vo, 'work_');

        $photopath = $vo->getPhotoPath();
        if($photopath)
        {
            $photopath = "do.php?_action=display_image&username=".rawurlencode($vo->username);
        }
        else
        {
            $photopath = "/images/no_photo.png";
        }

        $last_login = DAO::getObject($link, "SELECT * FROM logins WHERE logins.username = '{$vo->username}' ORDER BY id DESC LIMIT 1");

        include('tpl_read_system_user.php');
    }

}
?>