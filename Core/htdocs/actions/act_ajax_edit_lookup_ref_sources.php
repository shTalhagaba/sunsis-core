<?php
class ajax_edit_lookup_ref_sources implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		$sql = "";
		if($id != '')
		{
			$status = DAO::getSingleValue($link, "SELECT active FROM lookup_referral_source WHERE id = " . $id);
			if($status == 0)
				DAO::execute($link, "UPDATE lookup_referral_source SET active = 1 WHERE id = " . $id);
			else
				DAO::execute($link, "UPDATE lookup_referral_source SET active = 0 WHERE id = " . $id);
		}
	}
}

