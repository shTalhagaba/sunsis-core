<?php
class upload_miap implements IAction
{
	public function execute(PDO $link)
	{

		$_SESSION['bc']->add($link, "do.php?_action=upload_miap", "Update ULNs");
		
		include('tpl_upload_miap.php');
	}
}
?>