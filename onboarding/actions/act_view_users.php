<?php
class view_users implements IAction
{
    public function execute(PDO $link)
    {
        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_users", "View Users");

        $view = ViewUsers::getInstance($link);
        $view->refresh($link, $_REQUEST);

        $people = isset($_REQUEST['ViewUsers_filter_user_type']) ?
            DAO::getSingleValue($link, "SELECT description FROM lookup_user_types WHERE id = '{$_REQUEST['ViewUsers_filter_user_type']}'") : 'Users';

        require_once('tpl_view_users.php');
    }
}
?>