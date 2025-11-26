<?php
class ajax_save_qualification_abreg implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$abreg = isset($_REQUEST['abreg'])?$_REQUEST['abreg']:'';


		DAO::execute($link, "update student_qualifications set awarding_body_reg = '$abreg' where tr_id = '$tr_id' and replace(id,'/','') = '$qualification_id'");

	}
}
?>
