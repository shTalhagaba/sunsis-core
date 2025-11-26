<?php
class save_screening_temporary implements IAction {
	
	public function execute(PDO $link) {			
		$sql_candidate_duplicates = "SELECT userinfoid, candidateid, MAX(userdataid) as dataid FROM candidate_metadata GROUP BY userinfoid, candidateid HAVING COUNT(*) > 1";
		$st = $link->query($sql_candidate_duplicates);		
        if( $st ) {
        	while( $meta_row = $st->fetch() ) {
				$sql_delete_duplicates = "DELETE FROM candidate_metadata WHERE userinfoid = ".$meta_row['userinfoid']." AND candidateid = ".$meta_row['candidateid']." AND userdataid != ".$meta_row['dataid'];
				$del_st = $link->query($sql_delete_duplicates);
			}	
        }
        
		$sql_insert_application_data = <<<HEREDOC
SELECT 
	candidate_metadata.userdataid,
	candidate_metadata.userinfoid,
	candidate_id,
	candidate_metadata.stringvalue,
	candidate_metadata.intvalue,
	candidate_metadata.datevalue,
	candidate_metadata.floatvalue,
	candidate_applications.vacancy_id
FROM 
	candidate_applications, 
	candidate_metadata 
WHERE 
	candidate_applications.candidate_id = candidate_metadata.candidateid			
HEREDOC;
		$ins_st = $link->query($sql_insert_application_data);
		if ( $ins_st ) {
			while( $ins_row = $ins_st->fetch() ) {
				$intval = $ins_row['intvalue'] != ''? $ins_row['intvalue'] : "NULL";
				$dateval = $ins_row['datevalue'] != ''? $ins_row['datevalue'] : "NULL";
				$floatval = $ins_row['floatvalue'] != ''? $ins_row['floatvalue'] : "NULL";
				$sql_insert_data = 'INSERT INTO candidate_metadata (userinfoid, candidateid, stringvalue, intvalue, datevalue, floatvalue, vacancy_id) values ('.$ins_row['userinfoid'].','.$ins_row['candidate_id'].',"'.$ins_row['stringvalue'].'",'.$intval.','.$dateval.', '.$floatval.', '.$ins_row['vacancy_id'].' );';		
				$setup_st = $link->query($sql_insert_data);
			}
		}
	}
}
?>