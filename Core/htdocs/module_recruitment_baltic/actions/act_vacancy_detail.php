<?php
class vacancy_detail implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		if(DB_NAME=="am_baltic")
		{
			header('Location: http://jobs.baltictraining.com/baltictraining/Search/Results/All/1');
			exit;
		}
		$id=isset($_REQUEST['id'])?$_REQUEST['id']:'';
		if($id=='')
			throw new Exception('Vacancy ID missing.');
		$vacancy = Vacancy::loadFromDatabase($link, $id);
		if(is_null($vacancy) || $vacancy == '')
			pre('Sorry, this vacancy is no longer active.');
		$shift_pattern = $vacancy->shift_pattern;
		if ( $shift_pattern == '' )
		{
			// join all the shift data together to enable switch to single textbox
			$hours_per_week = (int)$vacancy->hours_mon+(int)$vacancy->hours_tues+(int)$vacancy->hours_wed+(int)$vacancy->hours_thurs+(int)$vacancy->hours_fri+(int)$vacancy->hours_sat+(int)$vacancy->hours_sun;
			if ( is_int($hours_per_week) && $hours_per_week > 0 )
			{
				$shift_pattern = "General hours per week: ".$hours_per_week;
			}
			$shift_pattern .= isset($vacancy->shifts_mon)?"\nMonday: ".$vacancy->shifts_mon:'';
			$shift_pattern .= isset($vacancy->shifts_tues)?"\nTuesday: ".$vacancy->shifts_tues:'';
			$shift_pattern .= isset($vacancy->shifts_wed)?"\nWednesday: ".$vacancy->shifts_wed:'';
			$shift_pattern .= isset($vacancy->shifts_thurs)?"\nThursday: ".$vacancy->shifts_thurs:'';
			$shift_pattern .= isset($vacancy->shifts_fri)?"\nFriday: ".$vacancy->shifts_fri:'';
			$shift_pattern .= isset($vacancy->shifts_sat)?"\nSaturday: ".$vacancy->shifts_sat:'';
			$shift_pattern .= isset($vacancy->shifts_sun)?"\nSunday: ".$vacancy->shifts_sun:'';
		}
		include_once('tpl_vacancy_detail.php');
	}
}