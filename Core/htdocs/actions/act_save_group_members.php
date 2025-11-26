<?php
class save_group_members implements IAction
{
    public function execute(PDO $link)
    {

        $groups = isset($_REQUEST['groups'])?$_REQUEST['groups']:'';
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $tobedeleted = isset($_REQUEST['tobedeleted'])?$_REQUEST['tobedeleted']:'';
        $updated = Date("d/m/Y h:i:s A");
        $alreadySavedTime = array();
        $tobedeleted = explode(",",$tobedeleted);
        foreach($tobedeleted as $del)
        {
            $group_id = $del;
            if($group_id!='')
            {
                //if record (tr_id, group_id) is already there then get the already saved updated time before deleting
                $isThere = DAO::getSingleValue($link, "SELECT COUNT(*) FROM group_members WHERE tr_id = $tr_id AND groups_id = $group_id");
                if($isThere != 0)
                    $alreadySavedTime[$group_id] = DAO::getSingleValue($link, "SELECT updated FROM group_members WHERE tr_id = $tr_id AND groups_id = $group_id");
                DAO::execute($link, "delete from group_members where tr_id = $tr_id and groups_id = $group_id");
            }
        }

        $groups = explode(",",$groups);
        foreach($groups as $group)
        {
            $group_id = $group;
            if($group_id!='')
            {//if group id is in array $alreadySavedTime array then this means that group was previously there so get its previous time
                if(isset($alreadySavedTime[$group_id]))
                    DAO::execute($link, "insert into group_members values($group_id,$tr_id,0,'$alreadySavedTime[$group_id]');");
                else//this means this is the new record so save the latest time
                    DAO::execute($link, "insert into group_members values($group_id,$tr_id,0,'$updated');");

                // Check if capacity is reached
                $no_of_members = DAO::getSingleValue($link, "select count(*) from group_members where groups_id = '$group_id'");
                $capacity = DAO::getSingleValue($link, "select capacity from groups where id = $group_id");
                if($no_of_members>=$capacity)
                    DAO::execute($link, "update groups set status = 2, system = 1 where system is null and id = '$group_id'");
                // End
            }
        }


        http_redirect('do.php?_action=read_training_record&id=' . $tr_id);
    }
}
?>