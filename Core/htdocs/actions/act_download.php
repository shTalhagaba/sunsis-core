<?php
class download implements IAction
{
	public function execute(PDO $link)
	{
		$contract_id = isset($_REQUEST['contract_id'])?$_REQUEST['contract_id']:'';
		$submission = isset($_REQUEST['submission'])?$_REQUEST['submission']:'';
		$L01 = isset($_REQUEST['L01'])?$_REQUEST['L01']:'';
		
		$sql = "Select count(*) from ilr where submission = '$submission' and contract_id = '$contract_id' and is_active=1";
		$no_of_active = DAO::getSingleValue($link, $sql);
		
		$sql = "Select count(*) from ilr where submission = '$submission' and contract_id = '$contract_id' and is_approved=1 and is_active=1";
		$no_of_active_approved = DAO::getSingleValue($link, $sql);

		if($no_of_active==0)
			throw new Exception("There is no active ILR to download");
		else
//			if($no_of_active_approved<$no_of_active)
//			{
//				throw new Exception("All active ILRs must be approved before you download the batch file");
//			}
//			else
			{
				$saveasname = Ilr2008::getFilename($link,$contract_id,$submission,$L01);
				require_once('tpl_download.php');			
			}



	}
}		
?>