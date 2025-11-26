<?php
define('METRES_IN_A_MILE', 1609.344);

class baltic_view_vacancies implements IAction
{
	public function execute(PDO $link)
	{
		$view = ViewVacancies::getInstance($link);
		$view->refresh($link, $_REQUEST);

		$export = isset($_REQUEST['export'])?$_REQUEST['export']:'';

		if($export=='csv')
		{
			$this->render_report($link, $view);
			exit;
		}

		if (strpos($_SESSION['bc']->getCurrent(),'baltic_view_forecast_vacancies_summary') == false)
		{
			$_SESSION['bc']->index=0;
		}

		if ( isset($_REQUEST['display']) ) {
			$_SESSION['bc']->add($link, "do.php?_action=view_candidates", "View Available Candidates");	
		} 
		else {
			$_SESSION['bc']->add($link, "do.php?_action=view_vacancies", "View Current Vacancies");
		}

		$pc = isset($_REQUEST['pc'])?$_REQUEST['pc']:'';

		if ( isset($_REQUEST['id']) && $_REQUEST['id'] != '' ) {
			$vacancy = Vacancy::loadFromDatabase($link, $_REQUEST['id']);
			if ( !is_object($vacancy) ) {	
				$url_location = $_SESSION['bc']->getCurrent();
				$url_location .= '&mesg=We have been unable to locate this vacancy';
				// throw new Exception($url_location);
				http_redirect($url_location);
			}
			$vacancy->radius = isset($_REQUEST['radius'])?$_REQUEST['radius']:'5';
			$vacancy->radius_metres = $vacancy->radius * METRES_IN_A_MILE;
			
			$_SESSION['bc']->add($link, "do.php?_action=view_vacancy&id=$vacancy->id&pc=".rawurlencode($pc), "View Vacancy Candidates");
			
			if ( $pc != '' ) {
				$loc = new GeoLocation();
				$loc->setPostcode($pc, $link);
				$longitude = $loc->getLongitude();
				$latitude = $loc->getLatitude();
				$easting = $loc->getEasting();
				$northing = $loc->getNorthing();
			}
		}
		
		if ( isset($_REQUEST['mailshot']) ) {
			$radius_metres = $vacancy->radius_metres;
			// this query includes the users link which isn't relevant, 
			$sql = <<<HEREDOC
				SELECT
					candidate.email,
					candidate.firstnames,
					candidate.surname,
					SQRT(POWER(ABS($vacancy->easting - candidate.easting), 2) + POWER(ABS($vacancy->northing - candidate.northing), 2)) AS distance
				FROM
					candidate
				WHERE
	    			candidate.username is NULL AND
					candidate.easting >= ($vacancy->easting - $radius_metres) AND candidate.easting <= ($vacancy->easting + $radius_metres) AND
					candidate.northing >= ($vacancy->northing - $radius_metres) AND candidate.northing <= ($vacancy->northing + $radius_metres) AND
					candidate.id NOT IN ( SELECT candidate_applications.candidate_id FROM candidate_applications WHERE candidate_applications.vacancy_id = $vacancy->id and candidate_applications.application_status != 2 )	
				HAVING
					distance <= $radius_metres
				ORDER BY
					distance,
        			candidate.surname;
HEREDOC;

			$sta = $link->query($sql);
			$mail_shot = '';
			if( $sta ) {
				while( $row = $sta->fetch() ) {
					if ( '' != $row['email'] ) {
						$mail_shot .= $row['email'].','.$row['firstnames'].','.$row['surname']."\r\n";
					}
				}
				
				if ( $mail_shot != '' ) {
					$mail_shot = "Email,Firstnames,Surname\r\n".$mail_shot;
					header("Content-Type: application/vnd.ms-excel");
					header('Content-Disposition: attachment; filename="mailshot.csv"');
			
					// Internet Explorer requires two extra headers when downloading files over HTTPS
					if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
					{
						header('Pragma: public');
						header('Cache-Control: max-age=0');
					}
					echo $mail_shot;	
				}
				else {
					require_once('tpl_baltic_view_vacancies.php');
				}
			}
		}
		else {
			require_once('tpl_baltic_view_vacancies.php');
		}
	}

	public function render_report(PDO $link, ViewVacancies $view)
	{
		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');
		$st = $link->query($statement->__toString());
		if($st)
		{
			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename="' . $view->getViewName() . '.csv"');
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
			{
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}
			echo 'Employer,Sector,Job Title,Total Positions,Current Applications,New Applications,Number of Candidates Applied'."\r\n";
			$row_count = 1;
			while( $row = $st->fetch() )
			{
				$row_count++;
				echo str_replace(',','', $row['legal_name']) . ',';
				echo str_replace(',','', $row['vac_desc']) . ',';
				echo str_replace(',','', $row['job_title']) . ',';
				echo $row['no_of_vacancies'] . ',';

				// application status is 1 meaning that number of candidates have been approved for the vacancy
				$current_sql = <<<HEREDOC
SELECT
	count(*)
FROM
	candidate, candidate_applications
WHERE
	candidate.id = candidate_applications.candidate_id
AND
	candidate_applications.vacancy_id = {$row['vac_id']}
AND
	candidate_applications.application_status = 1;

HEREDOC;
				echo DAO::getSingleValue($link, $current_sql);
				echo ',';
				// application status is
				$current_sql = <<<HEREDOC
SELECT
	count(*)
FROM
	candidate, candidate_applications
WHERE
	candidate.id = candidate_applications.candidate_id
AND
	candidate.enrolled = 1
AND
	candidate_applications.vacancy_id = {$row['vac_id']}
AND
	candidate_applications.application_status is null;
HEREDOC;
				echo DAO::getSingleValue($link, $current_sql);
				echo ',';
				$current_sql = <<<HEREDOC
SELECT
	count(*)
FROM
	candidate, candidate_applications
WHERE
	candidate.id = candidate_applications.candidate_id
AND
	candidate_applications.vacancy_id = {$row['vac_id']}
AND candidate_applications.application_status != 2
;

HEREDOC;
				echo DAO::getSingleValue($link, $current_sql);
				echo "\r\n";
			}
			echo "\r\n";


		}
		else {
			throw new DatabaseException($link, $view->getSQL());
		}
	}
}
?>
