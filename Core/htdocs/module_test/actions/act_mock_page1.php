<?php
class mock_page1 implements IAction
{

	public function execute(PDO $link)
	{
		include('tpl_mock_page1.php');
	}
}