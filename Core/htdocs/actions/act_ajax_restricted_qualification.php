<?php
class ajax_restricted_qualification implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text; charset=utf-8');
		
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		
		$id2 = str_replace("/","",$id);
		$count = DAO::getSingleValue($link, "select count(*) from lad201011.learning_aim where LEARNING_AIM_REF='$id2' and AWARDING_BODY_CODE='EDEXCEL'");

		if(DB_NAME=='am_edexcel' && $count!=1)
			echo "0";
		else
			echo "1";
	}
}
?>