<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Khushnood
 * Date: 16/04/12
 * Time: 16:10
 * To change this template use File | Settings | File Templates.
 */

class scramble_data implements IAction
{
	public function execute(PDO $link)
	{
		// Scramble the firstnames and  surname from users table
		$sql = "SELECT * FROM users";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				$username = $row['username'];
				while(1)
				{
					if($row['gender']=='M')
						$firstname = DAO::getSingleValue($link, "SELECT name FROM central.uk_firstnames WHERE gender = 1 ORDER BY RAND() LIMIT 1");
					else
						$firstname = DAO::getSingleValue($link, "SELECT name FROM central.uk_firstnames WHERE gender = 2 ORDER BY RAND() LIMIT 1");
					$surname = DAO::getSingleValue($link, "SELECT name FROM central.uk_surnames where name not like '%\'%' ORDER BY RAND() LIMIT 1");

					$found = DAO::getSingleValue($link, "select count(*) from users where firstnames = '$firstname' and surname = '$surname'");
					if(!$found)
						break;
				}

				$link->query("update users set firstnames = '$firstname', surname = '$surname' where username = '$username'");

			}
		}

		// Update tr table with the scrambled firstnames and surnames
		$link->query("update tr left join users on users.username = tr.username set tr.firstnames = users.firstnames, tr.surname = users.surname");
		$link->query("UPDATE lesson_notes LEFT JOIN users ON users.username = lesson_notes.username SET lesson_notes.firstnames = users.firstnames, lesson_notes.surname = users.surname;");
		$link->query("UPDATE logins LEFT JOIN users ON users.username = logins.username SET logins.firstnames = users.firstnames, logins.surname = users.surname;");
		$link->query("UPDATE notes LEFT JOIN users ON users.username = notes.username SET notes.firstnames = users.firstnames, notes.surname = users.surname;");
		$link->query("UPDATE register_entry_notes LEFT JOIN users ON users.username = register_entry_notes.username SET register_entry_notes.firstnames = users.firstnames, register_entry_notes.surname = users.surname;");
		$link->query("UPDATE tr LEFT JOIN users ON users.username = tr.username SET tr.firstnames = users.firstnames, tr.surname = users.surname;");
		$link->query("UPDATE tr_notes LEFT JOIN tr ON tr_notes.pot_id = tr.id SET tr_notes.firstnames = tr.firstnames, tr_notes.surname = tr.surname;");
		$link->query("UPDATE ilr LEFT JOIN tr ON ilr.l03 = tr.l03 SET ilr = REPLACE(ilr,SUBSTR(ilr,LOCATE('<L10>',ilr)+5,(LOCATE('</L10>',ilr)-LOCATE('<L10>',ilr)-5)), tr.firstnames) , ilr = REPLACE(ilr,SUBSTR(ilr,LOCATE('<L09>',ilr)+5,(LOCATE('</L09>',ilr)-LOCATE('<L09>',ilr)-5)), tr.surname) WHERE tr.surname IS NOT NULL;");


		$link->query("UPDATE acl LEFT JOIN users on users.username = acl.ident set acl.ident = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE assessment_plan LEFT JOIN users on users.username = assessment_plan.assessor set assessment_plan.assessor = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE assessor_review LEFT JOIN users on users.username = assessor_review.assessor set assessor_review.assessor = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE course_qualifications_dates LEFT JOIN users on users.id = course_qualifications_dates.tutor_username set course_qualifications_dates.tutor_username = id where users.username is not null;");
		$link->query("UPDATE courses LEFT JOIN users on users.username = courses.username set courses.username = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE frameworks LEFT JOIN users on users.username = frameworks.client set frameworks.client = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE groups LEFT JOIN users on users.username = groups.tutor set groups.tutor = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE groups LEFT JOIN users on users.username = groups.old_tutor set groups.old_tutor = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE groups LEFT JOIN users on users.username = groups.assessor set groups.assessor = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE groups LEFT JOIN users on users.username = groups.verifier set groups.verifier = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE groups LEFT JOIN users on users.username = groups.old_assessor set groups.old_assessor = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE groups LEFT JOIN users on users.username = groups.old_verifier set groups.old_verifier = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE groups LEFT JOIN users on users.username = groups.wbcoordinator set groups.wbcoordinator = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE groups LEFT JOIN users on users.username = groups.old_wbcoordinator set groups.old_wbcoordinator = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE ilr_audit LEFT JOIN users on users.username = ilr_audit.username set ilr_audit.username = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE iv LEFT JOIN users on users.username = iv.iv_name_1 set iv.iv_name_1 = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE iv LEFT JOIN users on users.username = iv.iv_name_2 set iv.iv_name_2 = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE lesson_notes LEFT JOIN users on users.username = lesson_notes.username set lesson_notes.username = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE lessons LEFT JOIN users on users.username = lessons.tutor set lessons.tutor = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE logins LEFT JOIN users on users.username = logins.username set logins.username = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE logins_unsuccessful LEFT JOIN users on users.username = logins_unsuccessful.username set logins_unsuccessful.username = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE notes LEFT JOIN users on users.username = notes.username set notes.username = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE register_entry_notes LEFT JOIN users on users.username = register_entry_notes.username set register_entry_notes.username = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE student_qualifications LEFT JOIN users on users.username = student_qualifications.username set student_qualifications.username = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE tr LEFT JOIN users on users.username = tr.username set tr.username = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE tr LEFT JOIN users on users.id = tr.assessor set tr.assessor = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE tr LEFT JOIN users on users.username = tr.tutor set tr.tutor = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE tr LEFT JOIN users on users.username = tr.verifier set tr.verifier = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE tr_notes LEFT JOIN users on users.username = tr_notes.username set tr_notes.username = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE user_saved_filters LEFT JOIN users on users.username = user_saved_filters.username set user_saved_filters.username = CONCAT(users.firstnames,'.',users.surname) where users.username is not null;");
		$link->query("UPDATE users LEFT JOIN users AS u2 ON users.username = u2.username SET u2.supervisor = CONCAT(users.firstnames,'.',users.surname) WHERE users.username IS NOT NULL;");
		$link->query("UPDATE view_columns LEFT JOIN users ON users.username = view_columns.user SET view_columns.user = CONCAT(users.firstnames,'.',users.surname) WHERE users.username IS NOT NULL;");


		$link->query("UPDATE users SET username = CONCAT(users.firstnames,'.',users.surname)");

		$sql = "SELECT * FROM users";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				$username = $row['username'];
				$ni = DAO::getSingleValue($link, "SELECT extractvalue(ilr,'/ilr/learner/L26') FROM ilr WHERE extractvalue(ilr,'/ilr/learner/L26')!='' ORDER BY RAND() LIMIT 1;");
				$link->query("update users set ni = '$ni' where username = '$username'");
			}
		}
		$link->query("UPDATE tr LEFT JOIN users on users.username = tr.username set tr.ni = users.ni where users.username is not null;");
		$link->query("UPDATE ilr LEFT JOIN tr ON ilr.l03 = tr.l03 SET ilr = REPLACE(ilr,SUBSTR(ilr,LOCATE('<L26>',ilr)+5,(LOCATE('</L26>',ilr)-LOCATE('<L26>',ilr)-5)), tr.firstnames) , ilr = REPLACE(ilr,SUBSTR(ilr,LOCATE('<L26>',ilr)+5,(LOCATE('</L26>',ilr)-LOCATE('<L26>',ilr)-5)), tr.ni) WHERE tr.surname IS NOT NULL;");

		$link->query("UPDATE tr set tr.home_email = CONCAT(tr.firstnames,'.',tr.surname,'@email.co.uk') where tr.username is not null;");
		$link->query("UPDATE tr set tr.work_email = CONCAT(tr.firstnames,'.',tr.surname,'@email.co.uk') where tr.username is not null;");
		$link->query("UPDATE locations set locations.contact_email = ''");
		$link->query("UPDATE tr set provider_email = ''");


		//$link->query("UPDATE locations set saon_start_number = '1', paon_start_number = '1', street_description = 'The Street', line1 = '1 The Street'");
		DAO::execute($link, "UPDATE locations SET address_line_1 = '1 High Street', address_line_2 = 'Oxford', address_line_3 = 'Oxfordshire', address_line_4 = NULL");
		//$link->query("UPDATE tr set home_saon_start_number = '1', home_paon_start_number = '1', home_street_description = 'The Street', work_saon_start_number = '1', work_paon_start_number = '1', work_street_description = 'The Street', provider_saon_start_number = '1', provider_paon_start_number = '1', provider_street_description = 'The Street'");
		DAO::execute($link, "UPDATE tr SET home_address_line_1 = '1 High Street', home_address_line_2 = 'Oxford', home_address_line_3 = 'Oxfordshire', home_address_line_4 = NULL");
		DAO::execute($link, "UPDATE tr SET work_address_line_1 = '1 High Street', work_address_line_2 = 'Oxford', work_address_line_3 = 'Oxfordshire', work_address_line_4 = NULL");
		DAO::execute($link, "UPDATE tr SET provider_address_line_1 = '1 High Street', provider_address_line_2 = 'Oxford', provider_address_line_3 = 'Oxfordshire', provider_address_line_4 = NULL");
		//$link->query("UPDATE users set home_saon_start_number = '1', home_paon_start_number = '1', home_street_description = 'The Street', work_saon_start_number = '1', work_paon_start_number = '1', work_street_description = 'The Street'");
		DAO::execute($link, "UPDATE users SET home_address_line_1 = '1 High Street', home_address_line_2 = 'Oxford', home_address_line_3 = 'Oxfordshire', home_address_line_4 = NULL");
		DAO::execute($link, "UPDATE users SET work_address_line_1 = '1 High Street', work_address_line_2 = 'Oxford', work_address_line_3 = 'Oxfordshire', work_address_line_4 = NULL");

		$sql = "SELECT * FROM organisations";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				$username = $row['legal_name'];
				while(1)
				{
					$surname = DAO::getSingleValue($link, "SELECT name FROM central.uk_surnames where name not like '%\'%' ORDER BY RAND() LIMIT 1");

					$found = DAO::getSingleValue($link, "select count(*) from organisations where legal_name = CONCAT('$surname',' LIMITED')");
					if(!$found)
						break;
				}

				$link->query("update organisations set legal_name = CONCAT('$surname',' LIMITED') where legal_name = '$username'");
			}
		}


		pre("Done");
	}
}
?>