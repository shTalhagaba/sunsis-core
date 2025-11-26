<?php

class view_release_notes implements IAction
{
    public function execute(PDO $link)
    {
        include_once('tpl_view_release_notes.php');
    }
}