<?php
class save_employer implements IAction
{
	public function execute(PDO $link)
	{

		$org = new Employer();

		$org->populate($_POST);

		if($_POST['id'] != '')
		{
			$existing_record = Employer::loadFromDatabase($link, $_POST['id']);
			$log_string = $existing_record->buildAuditLogString($link, $org);
			if($log_string!='')
			{
				$note = new Note();
				$note->subject = "Employer Record Edited";
				$note->note = $log_string;
			}
		}
		else
		{
			$note = new Note();
			$note->subject = "New employer added";
		}
		
		// fix the inactive / active flag setting issue
		// ---
		if ( !isset($_POST['gold_employer']) ) {
			$org->gold_employer = 0;
		}
		if ( !isset($_POST['active']) ) {
			$org->active = 0;
		}
		if ( !isset($_POST['not_linked']) ) {
			$org->not_linked = 0;
		}
		if ( !isset($_POST['epp']) ) {
			$org->epp = 0;
		}
		if ( !isset($_POST['ept']) ) {
			$org->ept = 0;
		}

/*
		if( isset($org->dealer_participating) && is_array($org->dealer_participating) && $org->dealer_participating[0]=='' ) {
			$org->dealer_participating = 0;
		}
		else {
			$org->dealer_participating = $org->dealer_participating[0];
		}
*/
		$org->dealer_participating = 0;
		

        if($org->creator=='')
    		$org->creator = $_SESSION['user']->username;
        if($org->parent_org=='')
    	    $org->parent_org = $_SESSION['user']->employer_id;
		
		// EDRS Validation
		$A44 = $org->edrs;
		if($A44!='' && trim($A44) != '100137032')
		{
			$flag1 = true;
			for($a=0;$a<=8; $a++)
				if(!($this->isDigit(substr($A44,$a,1))))
					$flag1 = false;
			
			$flag2 = true;
			if(strlen($A44)>9)
				for($a=9;$a<=29; $a++)
					if((substr($A44,$a,1)!=' ') && (substr($A44,$a,1)!=''))
						$flag2 = false;
						
			if($flag1 && $flag2)
			{
				$res = 11-((9*(int)substr($A44,0,1)+8*(int)substr($A44,1,1)+7*(int)substr($A44,2,1)+6*(int)substr($A44,3,1)+5*(int)substr($A44,4,1)+4*(int)substr($A44,5,1) + 3*(int)substr($A44,6,1) + 2*(int)substr($A44,7,1)) % 11);
				if($res==11)
					$AD03='0';
				else
					if($res==10)
						$AD03='X';
					else
						$AD03=$res;
			}
			else
				$AD03 = 'T';
		
			if($AD03=='T')
			{
				throw new Exception("Invalid EDRS Number");
			}
		}		
		DAO::transaction_start($link);
		try
		{
			$org->save($link);
			if(isset($note) && !is_null($note))
			{
				$note->is_audit_note = true;
				$note->parent_table = 'Employer';
				$note->parent_id = $org->id;
				$note->save($link);
			}

			if(DB_NAME=="am_siemens" || DB_NAME=="am_siemens_demo")
			{
				if(isset($_REQUEST['business_codes']))
				{
					DAO::execute($link, "DELETE FROM employer_business_codes WHERE employer_business_codes.employer_id = " . $org->id);
					$insert_query = "";
					foreach($_REQUEST['business_codes'] AS $business_code)
					{
						$insert_query = "INSERT INTO employer_business_codes (employer_id, brands_id) VALUES (" . $org->id .", " . $business_code . ")";
						DAO::execute($link, $insert_query);
					}
				}
			}

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		$locations = DAO::getSingleValue($link, "select count(*) from locations where organisations_id='$org->id'");
		if($locations>0)
			http_redirect($_SESSION['bc']->getPrevious());
		else
			http_redirect("do.php?_action=edit_location&organisations_id=" . $org->id . "&back=employer");
		
	}

	
	public static function isDigit($ch)
	{
		if(ord($ch)>=48 && ord($ch)<=57)
			return true;
		else
			return false;
	}
	
}


?>