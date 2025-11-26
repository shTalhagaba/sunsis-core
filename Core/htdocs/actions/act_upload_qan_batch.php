<?php
class upload_qan_batch implements IAction
{
	public function execute(PDO $link)
	{

		$_SESSION['bc']->add($link, "do.php?_action=upload_qan_batch", "Upload Batch Qualifications");
		
		include('tpl_upload_qan_batch.php');
	}
}
?>