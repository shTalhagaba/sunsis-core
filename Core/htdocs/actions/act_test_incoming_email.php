<?php

class test_incoming_email implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $std = new stdClass();
        $std->data = json_encode($_REQUEST);
        $std->created = date('Y-m-d H:i:s');
        DAO::saveObjectToTable($link, "temp", $std);
    }
}