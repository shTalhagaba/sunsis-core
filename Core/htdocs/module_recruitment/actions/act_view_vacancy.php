<?php
define('METRES_IN_A_MILE', 1609.344);

class view_vacancy implements IAction
{
	public function execute(PDO $link)
	{	
		// $_SESSION['bc']->index=0;
		if ( isset($_REQUEST['display']) ) {
			$_SESSION['bc']->add($link, "do.php?_action=view_candidates", "View Available Candidates");	
		}else {
			$_SESSION['bc']->add($link, "do.php?_action=view_vacancy", "View Current Vacancy");
		}
		
		$view = ViewVacancies::getInstance($link);
		$view->refresh($link, $_REQUEST);
		
		$pc = isset($_REQUEST['pc'])?$_REQUEST['pc']:'';

		if ( isset($_REQUEST['id']) && $_REQUEST['id'] != '' ) {
			$vacancy = Vacancy::loadFromDatabase($link, $_REQUEST['id']);
			if ( !isset($vacancy) || !is_object($vacancy) ) {
				$url_location = $_SESSION['bc']->getCurrent();
				$url_location .= '&mesg=We have been unable to locate this vacancy';
				// throw new Exception($url_location);
				http_redirect($url_location);
			}
			$vacancy->radius = isset($_REQUEST['radius'])?$_REQUEST['radius']:'5';
			$vacancy->radius_metres = $vacancy->radius * METRES_IN_A_MILE;

			$surname = isset($_REQUEST['surname'])?$_REQUEST['surname']:'';

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
		// check for vacancy object here - rttg error
		// ---
		else {
			$url_location = $_SESSION['bc']->getCurrent();
			$url_location .= '&mesg=We have been unable to locate this vacancy';
			http_redirect($url_location);
		}

		if ( isset($_REQUEST['mailshot']) ) {
			$radius_metres = $vacancy->radius_metres;
			// this query includes the users link which isn't relevant,
			$sql = <<<SQL
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
SQL;

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
					require_once('tpl_view_vacancy.php');
				}
			}
		}
		else {
			require_once('tpl_view_vacancy.php');
		}
	}
}
?>
