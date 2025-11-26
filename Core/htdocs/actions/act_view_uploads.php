<?php
class view_uploads implements IAction
{
    public function execute(PDO $link)
    {
		//if(DB_NAME != "am_donc_demo")
		  //  pre('We\'ve temporarily switched off this functionality for maintenance. ');

        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_uploads", "View Web Service Uploads");

        $view = ViewUploads::getInstance();
        $view->refresh($link, $_REQUEST);

        require_once('tpl_view_uploads.php');
    }
}
?>