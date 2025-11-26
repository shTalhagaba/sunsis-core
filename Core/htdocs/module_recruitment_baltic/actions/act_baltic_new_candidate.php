<?php
class baltic_new_candidate implements IAction
{
	public function execute(PDO $link)
	{

		if ( !isset($_REQUEST['vacancy_id']) ) {
			// resets the breadcrumb trail.
			$_SESSION['bc']->index=0;
			$_SESSION['bc']->add($link, "do.php?_action=new_candidate", "New Candidate");
		}

		$candidate = new Candidate();
		$candidate->candidate_notes = new CandidateNotes();
		$candidate->dob = date('Y-m-d');
		
		//metadata capture types
  		// instantiate the user 
		$registrant = new User();
		// get the client specific data required for capture
		$registrant->getUserMetaData($link);
		
		$candidate->metadata = $registrant->user_metadata;
		
		require_once('tpl_baltic_new_candidate.php');
/*
		$sql= "SELECT auto_id, postcode FROM central.emp_pool";

		$result = DAO::getResultset($link, $sql);

		$loc = NULL;
		$longitude = NULL;
		$latitude = NULL;
		$easting = NULL;
		$northing = NULL;

		foreach($result AS $r)
		{
			$loc = new GeoLocation();
			$loc->setPostcode($r[1], $link);
			$longitude = $loc->getLongitude();
			$latitude = $loc->getLatitude();
			$easting = $loc->getEasting();
			$northing = $loc->getNorthing();

			if($easting != '' AND $northing != '')
				DAO::execute($link, "UPDATE central.emp_pool SET easting = " . $easting . " , northing = " . $northing . " WHERE auto_id = " . $r[0]);
		}
*/
	}
}
?>
