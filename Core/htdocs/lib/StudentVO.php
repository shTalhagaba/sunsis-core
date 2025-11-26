<?php
class StudentVO extends ValueObject
{
	public $id = NULL;
	public $school_id = NULL;
	public $ni = NULL;
	public $uln = NULL;
	public $upi = NULL;
	public $upn = NULL;
	public $your_id = NULL;
	public $firstnames = NULL;
	public $surname = NULL;
	public $dob = NULL;
	public $ks4 = NULL;
	public $ks5 = NULL;
	public $gender = NULL;
	public $ethnicity = NULL;

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
	
	public $email = NULL;
	public $daytime_tel = NULL;
	public $evening_tel = NULL;
	public $mobile_tel = NULL;

	public $guardian_name = NULL;
	
	// ATTENDANCE STATISTICS
	public $scheduled_lessons = null;
	public $registered_lessons = null;
	public $attendances = null;
	public $lates = null;
	public $authorised_absences = null;
	public $unexplained_absences = null;
	public $unauthorised_absences = null;
	public $dismissals_uniform = null;
	public $dismissals_discipline = null;
}
?>