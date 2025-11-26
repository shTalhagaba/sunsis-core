<?php
class Profile extends Entity
{
	public static function loadFromDatabase(PDO $link, $id, $type)
	{
		if($id == '')
		{
			return null;
		}
		
		$org = new Profile();

		if($type=="profile")
		{
			$org->w01 = DAO::getSingleValue($link, "select profile from lookup_profile_values where submission = 'W01' and contract_id = '$id'");
			$org->w02 = DAO::getSingleValue($link, "select profile from lookup_profile_values where submission = 'W02' and contract_id = '$id'");
			$org->w03 = DAO::getSingleValue($link, "select profile from lookup_profile_values where submission = 'W03' and contract_id = '$id'");
			$org->w04 = DAO::getSingleValue($link, "select profile from lookup_profile_values where submission = 'W04' and contract_id = '$id'");
			$org->w05 = DAO::getSingleValue($link, "select profile from lookup_profile_values where submission = 'W05' and contract_id = '$id'");
			$org->w06 = DAO::getSingleValue($link, "select profile from lookup_profile_values where submission = 'W06' and contract_id = '$id'");
			$org->w07 = DAO::getSingleValue($link, "select profile from lookup_profile_values where submission = 'W07' and contract_id = '$id'");
			$org->w08 = DAO::getSingleValue($link, "select profile from lookup_profile_values where submission = 'W08' and contract_id = '$id'");
			$org->w09 = DAO::getSingleValue($link, "select profile from lookup_profile_values where submission = 'W09' and contract_id = '$id'");
			$org->w10 = DAO::getSingleValue($link, "select profile from lookup_profile_values where submission = 'W10' and contract_id = '$id'");
			$org->w11 = DAO::getSingleValue($link, "select profile from lookup_profile_values where submission = 'W11' and contract_id = '$id'");
			$org->w12 = DAO::getSingleValue($link, "select profile from lookup_profile_values where submission = 'W12' and contract_id = '$id'");
		}
		else
		{
			$org->w01 = DAO::getSingleValue($link, "select profile from lookup_pfr_values where submission = 'W01' and contract_id = '$id'");
			$org->w02 = DAO::getSingleValue($link, "select profile from lookup_pfr_values where submission = 'W02' and contract_id = '$id'");
			$org->w03 = DAO::getSingleValue($link, "select profile from lookup_pfr_values where submission = 'W03' and contract_id = '$id'");
			$org->w04 = DAO::getSingleValue($link, "select profile from lookup_pfr_values where submission = 'W04' and contract_id = '$id'");
			$org->w05 = DAO::getSingleValue($link, "select profile from lookup_pfr_values where submission = 'W05' and contract_id = '$id'");
			$org->w06 = DAO::getSingleValue($link, "select profile from lookup_pfr_values where submission = 'W06' and contract_id = '$id'");
			$org->w07 = DAO::getSingleValue($link, "select profile from lookup_pfr_values where submission = 'W07' and contract_id = '$id'");
			$org->w08 = DAO::getSingleValue($link, "select profile from lookup_pfr_values where submission = 'W08' and contract_id = '$id'");
			$org->w09 = DAO::getSingleValue($link, "select profile from lookup_pfr_values where submission = 'W09' and contract_id = '$id'");
			$org->w10 = DAO::getSingleValue($link, "select profile from lookup_pfr_values where submission = 'W10' and contract_id = '$id'");
			$org->w11 = DAO::getSingleValue($link, "select profile from lookup_pfr_values where submission = 'W11' and contract_id = '$id'");
			$org->w12 = DAO::getSingleValue($link, "select profile from lookup_pfr_values where submission = 'W12' and contract_id = '$id'");
		}
		
		
		return $org;	
	}
	
	public function save(PDO $link)
	{
		return DAO::saveObjectToTable($link, 'contracts', $this);
	}
	
	public function delete(PDO $link)
	{
		// Placeholder
	}
	
	
	public function isSafeToDelete(PDO $link)
	{
		return false;
	}
	
	
	public $id = null;
	public $w01 = NULL;
	public $w02 = NULL;
	public $w03 = NULL;
	public $w04 = NULL;
	public $w05 = NULL;
	public $w06 = NULL;
	public $w07 = NULL;
	public $w08 = NULL;
	public $w09 = NULL;
	public $w10 = NULL;
	public $w11 = NULL;
	public $w12 = NULL;
						
}
?>