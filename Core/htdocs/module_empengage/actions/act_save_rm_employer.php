<?php
class save_rm_employer implements IAction
{
	public function execute(PDO $link)
	{

		$org = new Employer();
		$org->populate($_POST);
		
		if($org->dealer_participating[0]=='')	
			$org->dealer_participating = 0;
		else
			$org->dealer_participating = $org->dealer_participating[0];
		
	//	$org->creator = $_SESSION['user']->username;

		$org->parent_org = $_SESSION['user']->employer_id;
		
		// EDRS Validation
		$A44 = $org->edrs;
		if($A44!='')
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

		
		if ( !$org->save($link) ) {
			http_redirect("do.php?_action=edit_rm_location&organisations_id=".$org->id."&mesg=There has been a problem saving this organisation");	
		}
		
		$loc = new Location();
		$loc->populate($_POST);
		$loc->is_legal_address = 1;
		$loc->organisations_id = $org->id;
		
		if ( $loc->postcode != '' ) {
			$geo = new GeoLocation();
			// RE - added passing of PDO to allow for location storage
			$geo->setPostcode($loc->postcode, $link);
			$loc->longitude = $geo->getLongitude();
			$loc->latitude = $geo->getLatitude();
			$loc->easting = $geo->getEasting();
			$loc->northing = $geo->getNorthing();
		}
		if ( $loc->save($link) ) {
			http_redirect("do.php?_action=read_employer&id=".$org->id."&mesg=successfully saved");
		}		
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