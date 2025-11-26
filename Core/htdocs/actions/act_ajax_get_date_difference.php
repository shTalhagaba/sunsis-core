<?php
class ajax_get_date_difference implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/plain; charset=iso-8859-1');
		$d1 = isset($_REQUEST['d1'])?$_REQUEST['d1']:'';
		$d2 = isset($_REQUEST['d2'])?$_REQUEST['d2']:'';
		$diff = Date::dateDiff($d2, $d1);
		$diff = str_replace(",","",$diff);	
		echo $diff;
	}
}
?>