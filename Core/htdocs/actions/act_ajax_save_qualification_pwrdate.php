<?php
class ajax_save_qualification_pwrdate implements IAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
        $pwrdate = isset($_REQUEST['pwrdate'])?$_REQUEST['pwrdate']:'';

        if($pwrdate!="")
            $pwrdate = "'" . Date::toMySQL($pwrdate) . "'";
        else
            $pwrdate = "NULL";

        DAO::execute($link, "update student_qualifications set paperwork_received_date = $pwrdate where tr_id = '$tr_id' and replace(id,'/','') = '$qualification_id'");

    }
}
?>
