<?php
class college_tools_stuff implements IAction
{
	public function execute(PDO $link)
	{
		require_once('tpl_college_tools_stuff.php');
	}
}