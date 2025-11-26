<?php
class baltic_ajax_get_vacancy_details implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		$vacancy_id = isset($_REQUEST['vacancy_id'])?$_REQUEST['vacancy_id']: '';
		if($vacancy_id == '')
		{
			echo "No vacancy selected";
		}
		else
		{
			$vacancy_details = DAO::getResultset($link, "SELECT job_title, `type`, apprenticeship_type, salary, hrs_per_week, description, skills_req, training_provided, required_quals, person_spec, future_prospects FROM vacancies WHERE id = '" . $vacancy_id . "'");

			for($i=0; $i<=10; $i++)
			{
				$vacancy_details[0][$i] = str_replace(',',';',$vacancy_details[0][$i]);
				$vacancy_details[0][$i] = str_replace('�',PHP_EOL,$vacancy_details[0][$i]);
				$vacancy_details[0][$i] = mb_convert_encoding($vacancy_details[0][$i],'UTF-8');
			}
			echo json_encode($vacancy_details);
		}
	}
}