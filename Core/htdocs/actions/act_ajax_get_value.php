<?php
class ajax_get_value implements IAction
{
    public function execute(PDO $link)
    {
        header('Content-Type: text/xml; charset=iso-8859-1');

        $query = isset($_REQUEST['query'])?$_REQUEST['query']:'';
        $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
        $additional = isset($_REQUEST['additional'])?$_REQUEST['additional']:'';
        $query = $query . "'" . $id . "' " . $additional;
        $value = DAO::getSingleValue($link, $query);
        echo $value;
    }
}
?>