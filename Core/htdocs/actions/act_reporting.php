<?php
class reporting implements IAction
{
	public function execute(PDO $link)
	{
		$sd = "01/" . date('m/Y');
		$ed = date('d/m/Y',strtotime('-1 second',strtotime('+1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00'))));

		include_once('tpl_reporting.php');
	}
}