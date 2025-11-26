<?php
class save_profile implements IAction
{
	
	public function execute(PDO $link)
	{

		$contract_id = $_POST['id'];
		$type = $_POST['type'];
		

		$w01 = ($_POST['W01']=='')?0:$_POST['W01'];
		$w02 = ($_POST['W02']=='')?0:$_POST['W02'];
		$w03 = ($_POST['W03']=='')?0:$_POST['W03'];
		$w04 = ($_POST['W04']=='')?0:$_POST['W04'];
		$w05 = ($_POST['W05']=='')?0:$_POST['W05'];
		$w06 = ($_POST['W06']=='')?0:$_POST['W06'];
		$w07 = ($_POST['W07']=='')?0:$_POST['W07'];
		$w08 = ($_POST['W08']=='')?0:$_POST['W08'];
		$w09 = ($_POST['W09']=='')?0:$_POST['W09'];
		$w10 = ($_POST['W10']=='')?0:$_POST['W10'];
		$w11 = ($_POST['W11']=='')?0:$_POST['W11'];
		$w12 = ($_POST['W12']=='')?0:$_POST['W12'];
		
		$values = "($contract_id,'W01',$w01)";
		$values .= ",($contract_id,'W02',$w02)";
		$values .= ",($contract_id,'W03',$w03)";
		$values .= ",($contract_id,'W04',$w04)";
		$values .= ",($contract_id,'W05',$w05)";
		$values .= ",($contract_id,'W06',$w06)";
		$values .= ",($contract_id,'W07',$w07)";
		$values .= ",($contract_id,'W08',$w08)";
		$values .= ",($contract_id,'W09',$w09)";
		$values .= ",($contract_id,'W10',$w10)";
		$values .= ",($contract_id,'W11',$w11)";
		$values .= ",($contract_id,'W12',$w12)";
										
		if($type=="profile")
		{
			DAO::execute($link, "delete from lookup_profile_values where contract_id = '$contract_id'");
			DAO::execute($link, "insert into lookup_profile_values (contract_id, submission, profile) values" . $values);
		}
		else
		{
			DAO::execute($link, "delete from lookup_pfr_values where contract_id = '$contract_id'");
			DAO::execute($link, "insert into lookup_pfr_values (contract_id, submission, profile) values" . $values);
		}
		
		http_redirect($_SESSION['bc']->getPrevious());
		
	}
}
?>