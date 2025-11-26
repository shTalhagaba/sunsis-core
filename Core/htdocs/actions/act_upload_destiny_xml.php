<?php
class upload_destiny_xml implements IAction
{
	public function execute(PDO $link)
	{
 
		$_SESSION['bc']->add($link, "do.php?_action=upload_abody_registration", "Update Awarding Body Registration");
		
		include('tpl_upload_destiny_xml.php');
	}
}
?>