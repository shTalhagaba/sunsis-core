<?php
class paste_subtree implements IAction
{
	public function execute(PDO $link)
	{
		header("Content-Type: text/xml");
		echo $_SESSION['user']->clipboard;
	}
}
?>