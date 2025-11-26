<?php
class save_cs_review_dates implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		$tr = TrainingRecord::loadFromDatabase($link, $id);
		$tr->cs_review1 = isset($_REQUEST['cs_review1'])?$_REQUEST['cs_review1']:'';
		$tr->cs_review2 = isset($_REQUEST['cs_review2'])?$_REQUEST['cs_review2']:'';
		$tr->cs_review3 = isset($_REQUEST['cs_review3'])?$_REQUEST['cs_review3']:'';

		$tr->save($link);

		if(IS_AJAX)
		{
			header("Content-Type: text/plain");
			echo $tr->id;
		}
		else
		{
			http_redirect('do.php?_action=read_training_record&id=' . $tr->id);
		}
	}
}