<?php
class copy_contract implements IAction
{
	public function execute(PDO $link)
	{
		
		$source_id = isset($_REQUEST['source'])?$_REQUEST['source']:'';
		$target_id = isset($_REQUEST['target'])?$_REQUEST['target']:'';
 
		
		if($source_id=='' || $target_id=='')
			throw new Exception("missing arguments");
		
// Importing framework
$query = <<<HEREDOC
insert into
	tr
select $target_id, id, username, programme, cohort, start_date, target_date, closure_date, status_code, school_id, firstnames, surname,                                                  
gender, ethnicity, dob, uln, upi, upn, ni, home_address_line_1, home_address_line_2, home_address_line_3, home_address_line_4, home_postcode,
home_email, home_telephone, home_mobile, employer_id, employer_location_id, work_address_line_1,
work_address_line_2, work_address_line_3, work_address_line_4,
work_postcode, work_email, work_telephone, work_mobile, provider_id, provider_location_id, provider_address_line_1,
provider_address_line_2, provider_address_line_3, provider_address_line_4, provider_postcode, provider_email, provider_telephone,
scheduled_lessons, registered_lessons, attendances, lates,
authorised_absences, unexplained_absences, unauthorised_absences, dismissals_uniform, dismissals_discipline, units_total,                                                
units_not_started, units_behind, units_on_track, units_under_assessment, units_completed, modified, learning_difficulties,                                      
disability, learning_difficulty, current_postcode, country_of_domicile, prior_attainment_level                                     
from 
	tr
where
	contract_id='$source_id'
HEREDOC;
		DAO::execute($link, $query);

		http_redirect('do.php?_action=read_contract&id='. $target_id);
		
	}	
		
}
?>