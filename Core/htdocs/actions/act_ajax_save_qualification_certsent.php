<?php
class ajax_save_qualification_certsent implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$certsent = isset($_REQUEST['certsent'])?$_REQUEST['certsent']:'';

		$certsent = Date::toMySQL($certsent);
        if($certsent!='')
		    DAO::execute($link, "update student_qualifications set certificate_sent = '$certsent' where tr_id = '$tr_id' and replace(id,'/','') = '$qualification_id'");

	}
}
?>
