<?php
class ajax_update_course_qualification_dates implements IAction
{
	public function execute(PDO $link)
	{
		$qualification_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
		$course_id= isset($_REQUEST['course_id'])?$_REQUEST['course_id']:'';
		$provider_id= isset($_REQUEST['provider_id'])?$_REQUEST['provider_id']:'';
		$location_id= isset($_REQUEST['location_id'])?$_REQUEST['location_id']:'';
		$tutor= isset($_REQUEST['tutor'])?$_REQUEST['tutor']:'';
		 
		$start_date= isset($_REQUEST['start_date'])?$_REQUEST['start_date']:'';
		$end_date= isset($_REQUEST['end_date'])?$_REQUEST['end_date']:'';

		$sd = new Date($start_date);
		$sd = $sd->getYear() . '-' . $sd->getMonth() . '-' . $sd->getDays();
		
		$ed = new Date($end_date);
		$ed = $ed->getYear() . '-' . $ed->getMonth() . '-' . $ed->getDays();
		
		//throw new Exception("Qualification id: " . $qualification_id . " \nInternaltitle: " . $internaltitle . " \n Course id: " . $course_id . " \nProvider Id: " . $provider_id . " \nLocation id: " . $location_id . " \nTutor:" . $tutor . " \nStart Date: " . $sd . " \nEnd Date: " . $ed); 
		

		if($location_id == '')
			$location_id = 0;
			
		if($provider_id == '')
			$provider_id = 0;

        if($tutor == '')
            $tutor = 0;


// updating qualification 		
$query = <<<HEREDOC
update 
	course_qualifications_dates
set qualification_start_date = '$sd', qualification_end_date = '$ed', provider_id= $provider_id, location_id = $location_id, tutor_username = '$tutor'
	where qualification_id = '$qualification_id' and internaltitle='$internaltitle' and course_id='$course_id';
HEREDOC;
		DAO::execute($link, $query);
		
	}
}
?>