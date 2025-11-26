<?php
/**
 * 
 * convert candidate request is made 
 * 
 * via ajax to allow on page feedback.
 * @author relmes
 *
 */

define('METRES_IN_A_MILE', 1609.344);

class baltic_convert_candidates implements IAction
{
	public function execute(PDO $link) {	
		if ( isset($_REQUEST['id']) ) {
			$vacancy = Vacancy::loadFromDatabase($link, $_REQUEST['id']);
			$vacancy->radius = isset($_REQUEST['radius'])?$_REQUEST['radius']:'5';
			$vacancy->radius_metres = $vacancy->radius * METRES_IN_A_MILE;
			
			$pc = $vacancy->postcode;
			
			$loc = new GeoLocation();
			$loc->setPostcode($pc, $link);
			$longitude = $loc->getLongitude();
			$latitude = $loc->getLatitude();
			$easting = $loc->getEasting();
			$northing = $loc->getNorthing();
		}
		
		if ( $_REQUEST['cid'] != '' ) {
			$vo = Candidate::loadFromDatabase($link, $_REQUEST['cid']);
			if ( !$vo ) {
				$vacancy->feedback['message'] = 'Failed to locate this candidate in the system';
			}
			$vacancy->feedback['message'] = $vo->convertToLearner($link, $vacancy->id );
			// update the available places
            if($vacancy->feedback['message']!="We already have a user matching this candidate!" && strpos($vacancy->feedback['message'], 'New user ') !== false)
	    		$vacancy->update($link);
		}
		else {
			$vacancy->feedback['message'] = 'Failed to convert this candidate to a learner';
		}
		$vacancy->feedback['background-color'] = '#DCE5CD';
		$vacancy->feedback['location'] = '#tab-2';		
		
		$view = ViewVacancies::getInstance($link);
		$view->refresh($link, $_REQUEST);		
		// Presentation
		include('tpl_baltic_view_vacancy.php');
	}
}
?>