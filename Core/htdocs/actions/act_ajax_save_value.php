<?php
class ajax_save_value implements IAction
{
    public function execute(PDO $link)
    {
        $query = isset($_REQUEST['query'])?$_REQUEST['query']:'';
        DAO::execute($link, $query);
    }
}
?>
