<?php
class populate_learnergroups_from_csv_file implements IAction
{
	public function execute(PDO $link)
	{		
		// create assessor, tutor and verifier with the name "UnDefined"
		// do this manually
		// traverse this sheet to read the learner details
		// lookup course id learner is currently attached to
		// create Group object (if group was already created avoid creating a new group for every single learner)
		// set tutor assessor and verifier as undefined
		// set title as group id
		// set course id that you have
		// save group

		// insert a row into group_members table that will associate this learner with the group 

		$handle = fopen("learner_group.csv","r");
		$st = fgets($handle);
		$user = new User();
		
		while(!feof($handle)) {
			$st = fgets($handle);
			// Create Learners
			$arr = explode(",",$st);
			
			if ( sizeof($arr) >= 54 ) {	
			
				$enrollment_no = trim($arr[2]);
				$course_id = trim($arr[49]);
				$group_id = trim($arr[50]);
							
				$user_sql = 'SELECT tr.id FROM tr, users WHERE tr.username = '.$enrollment_no.' and tr.username = users.username';
				$sun_tr_id = DAO::getSingleValue($link, $user_sql);
				if ( $sun_tr_id != '' ) {
					$course_id_sql = 'SELECT * FROM courses_tr WHERE tr_id = '.$sun_tr_id;
					$sun_course_id = DAO::getSingleValue($link, $course_id_sql);
				}				
				if ( $sun_course_id != "" && $group_id ) {
					$group_check_sql = 'select * from groups where title = "'.$group_id.'"';
					$group_check = DAO::getSingleValue($link, $group_check_sql);
					if ( $group_check == '' ) {
						$group_insert_sql = 'insert into groups ( title, courses_id, assessor, tutor, verifier ) values ("'.$group_id.'", "'.$sun_course_id.'", "undefined_assessor", "undefined_tutor", "undefined_verifier")';
						DAO::getSingleValue($link, $group_insert_sql);	
					}	
				}
				
				$group_check_id = DAO::getSingleValue($link, $group_check_sql);
				if ( ( DAO::getSingleValue($link, 'select count(*) from group_members where groups_id = "'.$group_check_id.'" and tr_id = "'.$sun_tr_id.'" ') < 1 ) && ( is_int($sun_tr_id) ) ) {
					if ( DAO::getSingleValue($link, 'select count(*) from group_members where tr_id = "'.$sun_tr_id.'" ') < 1 ) {
						$group_members_sql = 'insert into group_members ( groups_id, tr_id ) values ( "'.$group_check_id.'", "'.$sun_tr_id.'" )';
						DAO::getSingleValue($link, $group_members_sql);
					}
				}
			}
							
		}			
		fclose($handle); 
	}
}
?>