<?php
class mock_page2 implements IAction
{

	public function execute(PDO $link)
	{
		include('tpl_mock_page2.php');
	}
}