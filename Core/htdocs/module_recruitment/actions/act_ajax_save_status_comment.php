<?php
class ajax_save_status_comment implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/plain;');
		$id = isset($_REQUEST['comment'])?$_REQUEST['comment']:'';
		$candid = isset($_REQUEST['candid'])?$_REQUEST['candid']:'';
		$vacid = isset($_REQUEST['vacid'])?$_REQUEST['vacid']:'';
		if(  '' == $candid ) {
		    
		}
		else {

			$has_applications = DAO::getSingleValue($link, 'select count(*) from candidate_applications where candidate_id = '.$candid);
			
			if ( $has_applications ) {
				$status_update_sql = 'UPDATE candidate_applications set application_comments = "'.addslashes((string)$id).'" '; 
				if ( isset($_REQUEST['date']) ) {
					$status_update_sql .= ', next_action_date = "'.$_REQUEST['date'].'" ';	
				}
				$status_update_sql .= ' where candidate_id = '.$candid;
				// re release dec 20th 2011 - new features item 1
				// - simple solution as defined by M Cannon at RTTG
				// - update all candidate applications status based on this 
				// - single update.
				// if ( $vacid != 'undefined' ) {
				//	$status_update_sql .= 'and vacancy_id = '.$vacid;
				// }	
			}
			else {
				$status_update_sql = 'INSERT INTO candidate_applications (candidate_id, vacancy_id, application_comments, next_action_date) values ('.$candid.', 0, "'.addslashes((string)$id).'", "'.$_REQUEST['date'].'" )';
			}
			$st = $link->query($status_update_sql);	
			if ($st==false) {
				echo 'Status update failed! '.$status_update_sql;
			}
			else {
				echo 'Status: '.$id;
			}
		}
	}
}
?>
