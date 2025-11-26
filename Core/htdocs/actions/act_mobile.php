<?php
class mobile implements IAction
{
    public function execute(PDO $link)
    {
	    $view = ViewVacancies::getInstance($link);

	    // get the postcode for a vacancy search
	    $pc = isset($_REQUEST['pc'])?$_REQUEST['pc']:'';
	    // get the distance from the postcode search is wanting to match
	    $distance = isset($_REQUEST['distance'])?$_REQUEST['distance']:'1000';

	    // re: 05/09/2011
	    // added in check for none entry of distance
	    if ( !is_numeric($distance) ) {
		    $distance = 1000;
	    }
	    // get the keyword for a vacnacy search
	    $keyword = isset($_REQUEST['keyword'])?$_REQUEST['keyword']:'';

	    // re: 05/09/2011
	    // check if its a returning customer
	    if ( ( isset($_REQUEST['firstname'])&& $_REQUEST['firstname'] != '' )
		    || ( isset($_REQUEST['surname']) && $_REQUEST['surname'] != '' )
			    && isset($_REQUEST['dob_day'])
			    && isset($_REQUEST['dob_month']) && isset($_REQUEST['dob_year']) ) {
		    $user_birthday = $_REQUEST['dob_year']."-".sprintf("%02d", $_REQUEST['dob_month'])."-".$_REQUEST['dob_day'];
		    $candidate_data = DAO::getResultset($link, "select id, postcode from candidate where LOWER(firstnames) = LOWER('".htmlspecialchars((string)$_REQUEST['firstname'])."') and LOWER(surname) = LOWER('".htmlspecialchars((string)$_REQUEST['surname'])."') and dob = '".$user_birthday."'", DAO::FETCH_ASSOC);

		    // set the postcode to allow distance from display
		    if ( isset($candidate_data[0]['postcode']) && $candidate_data[0]['postcode'] != '' ) {
			    $pc = $candidate_data[0]['postcode'];
		    }
	    }


	    if ( isset($_REQUEST['id']) ) {
		    $vacancy = Vacancy::loadFromDatabase($link, $_REQUEST['id']);
		    $vacancy->radius = isset($_REQUEST['radius'])?$_REQUEST['radius']:'5';
		    $vacancy->radius_metres = $vacancy->radius * METRES_IN_A_MILE;
	    }

	    // get the location details of the postcode
	    // what if we don't have a $pc value
	    if ( $pc ) {
		    $loc = new GeoLocation();
		    $loc->setPostcode($pc, $link);
		    $longitude = $loc->getLongitude();
		    $latitude = $loc->getLatitude();
		    $easting = $loc->getEasting();
		    $northing = $loc->getNorthing();
	    }

	    // update the sql used to retreive the vacancies
	    // relmes - check this for compliance - where else is ViewVacancies used ??
	    $sql = 'SELECT organisations.*, vacancies.*, ';
	    // having a postcode so work out how
	    // far the vacancies are from it
	    if ( $pc ) {
		    $sql .= 'SQRT(POWER(ABS('.$easting.' - vacancies.easting), 2) + POWER(ABS('.$northing.' - vacancies.northing), 2)) AS distance, ';
	    }
	    $sql .= 'lookup_vacancy_type.description as vac_desc, ';
	    $sql .= 'vacancies.id AS vac_id ';
	    $sql .= 'FROM vacancies, organisations, lookup_vacancy_type ';
	    $sql .= 'WHERE vacancies.employer_id = organisations.id and vacancies.type = lookup_vacancy_type.id ';
	    $sql .= 'and vacancies.active = 1 ';
	    // and now() >= vacancies.live_date and vacancies.expiry_date >= now() ';
	    // if we have a keyword, check the relevant bits of the vacancy
	    if ( $keyword ) {
		    $sql .= 'and lookup_vacancy_type.description = "'.$keyword.'"';
	    }
	    // if we have both postcode and distance, check
	    // it is within the chosen radius
	    if ( $pc && $distance ) {
		    $distance_radius = $distance * 1609.344;
		    $sql .= 'and vacancies.easting >= ('.$easting.' - '.$distance_radius.') AND vacancies.easting <= ('.$easting.' + '.$distance_radius.') ';
		    $sql .= 'and vacancies.northing >= ('.$northing.' - '.$distance_radius.') AND vacancies.northing <= ('.$northing.' + '.$distance_radius.') ';
		    $sql .= 'HAVING	distance <= '.$distance_radius;
		    $sql .= ' ORDER BY distance, vacancies.type';
	    }
	    else {
		    $sql .= ' ORDER BY vacancies.type';
	    }

	    $view->setSQL($sql);

	    $type_dropdown = "SELECT description, description, null FROM lookup_vacancy_type ORDER BY description asc;";
	    $type_dropdown = DAO::getResultset($link, $type_dropdown);

	    include_once('tpl_mobile.php');
    }
}