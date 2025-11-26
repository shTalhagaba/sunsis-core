<?php
class ajax_save_qualification_cerno implements IAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
        $cerno = isset($_REQUEST['cerno'])?$_REQUEST['cerno']:'';


        DAO::execute($link, "update student_qualifications set certificate_no = '$cerno' where tr_id = '$tr_id' and replace(id,'/','') = '$qualification_id'");

    }
}
?>
