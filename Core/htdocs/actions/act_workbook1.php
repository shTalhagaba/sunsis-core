<?php
class workbook1 implements IAction
{
	public function execute(PDO $link)
	{

		include_once('tpl_workbook1.php');
	}
}