<?php
class add_to_group implements IAction
{
    public function execute(PDO $link)
    {
        $group_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';

        //DAO::execute($link, "delete from group_members where tr_id = '$tr_id'");
        DAO::execute($link, "insert into group_members values($group_id,$tr_id,NULL)");

        http_redirect('do.php?_action=read_training_record&id='.$tr_id);
    }
}
?>
