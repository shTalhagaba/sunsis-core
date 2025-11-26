<?php
class read_uploads implements IAction
{
    public function execute(PDO $link)
    {
        $time = (int)isset($_REQUEST['time'])?$_REQUEST['time']:'';

        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=read_uploads&time=" . $time, "View Upload");

        $view = ViewReadUploads::getInstance($link, $_REQUEST);
        $view->refresh($link, $_REQUEST);

        require_once('tpl_read_uploads.php');
    }
}
?>