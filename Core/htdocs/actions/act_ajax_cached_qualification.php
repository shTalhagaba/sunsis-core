<?php
class ajax_cached_qualification implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text; charset=utf-8');
		
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		
		$id2 = str_replace("/","",$id);
		
		$count = DAO::getSingleValue($link, "select count(*) from central.qualifications where replace(id,'/','')='$id2'");

		echo $count;
	}
}
?>