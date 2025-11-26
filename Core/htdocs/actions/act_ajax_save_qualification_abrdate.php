<?php
class ajax_save_qualification_abrdate implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$abrdate = isset($_REQUEST['abrdate'])?$_REQUEST['abrdate']:'';

        if($abrdate!="")
    		$abrdate = "'" . Date::toMySQL($abrdate) . "'";
        else
            $abrdate = "NULL";

		DAO::execute($link, "update student_qualifications set awarding_body_date = $abrdate where tr_id = '$tr_id' and replace(id,'/','') = '$qualification_id'");

	}
}
?>
