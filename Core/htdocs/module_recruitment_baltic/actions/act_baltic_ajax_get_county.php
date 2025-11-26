<?php
class baltic_ajax_get_county implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/plain;');
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		if( '' == $id ) {
		    throw new Exception(1);	
		}
		$county = DAO::getSingleValue($link, "select county from central.lookup_boroughs where id = '$id'");
        	echo $county;
	}
}
?>
