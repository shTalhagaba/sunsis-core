<?php
class ajax_save_qualification_certrec implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$certrec = isset($_REQUEST['certrec'])?$_REQUEST['certrec']:'';

		$certrec = Date::toMySQL($certrec);

		DAO::execute($link, "update student_qualifications set certificate_received = '$certrec' where tr_id = '$tr_id' and replace(id,'/','') = '$qualification_id'");

	}
}
?>
