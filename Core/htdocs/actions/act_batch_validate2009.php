<?php
class batch_validate2009 implements IAction
{
	public function execute(PDO $link)
	{
		
		// Check arguments
		$contract_id = isset($_REQUEST['contract_id'])?$_REQUEST['contract_id']:'';
		$submission = isset($_REQUEST['submission'])?$_REQUEST['submission']:'';
		

		$sql = "select * from ilr where submission='$submission' and contract_id = $contract_id";
		$st = $link->query($sql);
		//$st=$link->query("call view_training_providers();");
		if($st) 
		{
			while($row = $st->fetch())
			{
			
				// Run server validation routines
				$ilr = Ilr2009::loadFromXML($row['ilr']);
				
				$validator = new ValidateILR2009();
				$report = $validator->validate($link, $ilr);

				
				$sub = $submission;
				$tr_id = $row['tr_id'];
				$qan = $row['L03'];
				
				
				if($report != '')
				{
					//pre($qan . $report);
					$sql2 = "update ilr set is_valid = 0 where submission='$sub' and tr_id=$tr_id and contract_id=$contract_id";
					DAO::execute($link, $sql2);
				}
				else
				{
					$sql2 = "update ilr set is_valid = 1, is_approved = 1 where submission='$sub' and tr_id=$tr_id and contract_id=$contract_id";
					DAO::execute($link, $sql2);
				}
			}
		}
		
		http_redirect('do.php?_action=view_ilrs&id=' . $submission.$contract_id);		
		
	}
}
?>