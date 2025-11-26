<?php
class PersonnelVO extends ValueObject
{
	public $id = NULL;
	public $organisations_id = NULL;
	public $firstnames = NULL;
	public $surname = NULL;
	public $department = NULL;
	public $job_role = NULL;
	
/*	public $paon_start_number = NULL;
	public $paon_start_suffix = NULL;
	public $paon_end_number = NULL;
	public $paon_end_suffix = NULL;
	public $paon_description = NULL;
	
	public $saon_start_number = NULL;
	public $saon_start_suffix = NULL;
	public $saon_end_number = NULL;
	public $saon_end_suffix = NULL;
	public $saon_description = NULL;
	
	public $street_description = NULL;
	public $locality = NULL;
	public $town = NULL;
	public $county = NULL;*/
	public $address_line_1 = NULL;
	public $address_line_2 = NULL;
	public $address_line_3 = NULL;
	public $address_line_4 = NULL;
	public $postcode = NULL;	
	
	public $telephone = NULL;
	public $mobile = NULL;
	public $fax = NULL;
	public $email = NULL;
}
?>