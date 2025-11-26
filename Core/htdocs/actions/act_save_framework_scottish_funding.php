<?php
class save_framework_scottish_funding implements IAction
{
	public function execute(PDO $link)
	{
		$framework_id = isset($_REQUEST['fwrk_id'])?$_REQUEST['fwrk_id']:'';
		$sql = "";
		foreach($_REQUEST AS $key=>$value)
		{
			if($key != 'fwrk_id' && $key != '_action')
				$sql .= "INSERT INTO fwrk_scottish_funding (fwrk_id, description, amount) VALUES ('{$framework_id}', '{$key}', '{$value}'); " . PHP_EOL;
		}
		DAO::execute($link, "DELETE FROM fwrk_scottish_funding WHERE fwrk_id = " . $framework_id);
		DAO::execute($link, $sql);
		$_SESSION['bc']->index = $_SESSION['bc']->index-1;
		http_redirect('do.php?_action=view_framework_qualifications&id='.$framework_id);
	}
}
?>