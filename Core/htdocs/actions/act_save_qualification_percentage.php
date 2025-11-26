<?php 
class save_qualification_percentage implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$percentage = isset($_REQUEST['percentage'])?$_REQUEST['percentage']:'';

		if($percentage=='')
				$percentage = 0;

		DAO::execute($link, "update student_qualifications set unitsUnderAssessment = '$percentage' where tr_id = '$tr_id' and replace(id,'/','') = '$qualification_id'");

		TrainingRecord::updateProgressStatistics($link, $tr_id);
		
	}
}
?>
