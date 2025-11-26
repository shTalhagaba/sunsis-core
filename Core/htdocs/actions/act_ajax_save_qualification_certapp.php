<?php
class ajax_save_qualification_certapp implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$certapp = isset($_REQUEST['certapp'])?$_REQUEST['certapp']:'';

		$certapp = Date::toMySQL($certapp);

		DAO::execute($link, "update student_qualifications set certificate_applied = '$certapp' where tr_id = '$tr_id' and replace(id,'/','') = '$qualification_id'");

	}
}
?>
