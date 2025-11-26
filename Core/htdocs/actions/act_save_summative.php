<?php
class save_summative implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = $_POST['tr_id'];
		$vo = TrainingRecord::loadFromDatabase($link, $tr_id);
		$vo->iqa_lead = $_POST['iqa_person'];
		$vo->summative_date = Date::toMySQL($_POST['summative_date']);
		$vo->summative_date_actioned = Date::toMySQL($_POST['summative_date_actioned']);
		$vo->iqa_summary = $_POST['iqa_summary'];
		$vo->coach_comments = $_POST['coach_comments'];
		$vo->summative_status = $_POST['summative_status'];
		$vo->save($link);
		http_redirect('do.php?_action=read_training_record&id='.$_POST['tr_id']);
	}
}
?>