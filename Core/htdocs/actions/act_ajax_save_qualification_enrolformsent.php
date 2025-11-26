<?php
class ajax_save_qualification_enrolformsent implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$enrolformsent = isset($_REQUEST['enrolformsent'])?$_REQUEST['enrolformsent']:'';

		$enrolformsent = Date::toMySQL($enrolformsent);

		DAO::execute($link, "update student_qualifications set enrolment_form_sent = '$enrolformsent' where tr_id = '$tr_id' and replace(id,'/','') = '$qualification_id'");

	}
}
?>
