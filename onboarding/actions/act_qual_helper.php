<?php
class qual_helper extends ActionController
{
    public function indexAction(PDO $link)
    {
        // TODO: Implement indexAction() method.
    }

    public function copySubTreeAction(PDO $link)
    {
        $clipboardType = isset($_REQUEST['clipboardType']) ? $_REQUEST['clipboardType'] : '';
        $clipboard = isset($_REQUEST['clipboard']) ? $_REQUEST['clipboard'] : '';
        $clipboardNode = isset($_REQUEST['clipboardNode']) ? $_REQUEST['clipboardNode'] : '';

        $_SESSION['user']->clipboardType 	= 	$clipboardType;
        $_SESSION['user']->clipboard 		=	$clipboard;
        $_SESSION['user']->clipboardNode	=	$clipboardNode;
    }

    public function pasteSubTreeAction(PDO $link)
    {
        header("Content-Type: text/xml");
        echo $_SESSION['user']->clipboard;
    }

    public function getClipboardTypeAction(PDO $link)
    {
        //pre($_SESSION['user']);
        header("Content-Type: text/xml");
        echo $_SESSION['user']->clipboardType;
    }
}
