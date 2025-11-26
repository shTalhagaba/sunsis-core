<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Khushnood
 * Date: 16/11/12
 * Time: 15:58
 * To change this template use File | Settings | File Templates.
 */

class demigrate_2014 implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';

		DAO::execute($link, "delete from ilr where tr_id = '$tr_id' and contract_id in (select id from contracts where contract_year = 2015)");
		DAO::execute($link, "update tr set contract_id = (select contract_id from ilr left join contracts on contracts.id = ilr.contract_id where ilr.tr_id = tr.id order by contract_year desc limit 0,1) WHERE tr.id = '$tr_id'");
		http_redirect("do.php?_action=read_training_record&id=$tr_id");
	}
}


?>