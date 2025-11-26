<?php
class upload_batch implements IAction
{
	public function execute(PDO $link)
	{

		$_SESSION['bc']->add($link, "do.php?_action=upload_batch", "Update Batch");

		include('tpl_upload_batch.php');
	}
}
?>