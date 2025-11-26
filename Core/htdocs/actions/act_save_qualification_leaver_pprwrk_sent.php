<?php
class save_qualification_leaver_pprwrk_sent implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$leaverpprwrksent = isset($_REQUEST['leaverpprwrksent'])?$_REQUEST['leaverpprwrksent']:'';

		$leaverpprwrksent = Date::toMySQL($leaverpprwrksent);

		DAO::execute($link, "update student_qualifications set leaver_pprwrk_sent = '$leaverpprwrksent' where tr_id = '$tr_id' and replace(id,'/','') = '$qualification_id'");

	}
}
?>
