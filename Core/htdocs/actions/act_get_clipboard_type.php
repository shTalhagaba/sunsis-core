<?php
class get_clipboard_type implements IAction
{
	public function execute(PDO $link)
	{
		header("Content-Type: text/xml");
		echo $_SESSION['user']->clipboardType;
	}
}
?>