<?php
class lewisham_step6 implements IAction
{
	public function execute(PDO $link)
	{

		// Non-Apps first
		$sql = "SELECT DISTINCT A03,A22,A09 FROM aim WHERE A22!='00000000' AND A15=99";
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				$l03 = $row['A03'];
				$ukprn = $row['A22'];
				$A09 = $row['A09'];

				$org_id = DAO::getSingleValue($link, "select id from organisations where ukprn = '$ukprn' and organisation_type = 3");
				$loc_id = DAO::getSingleValue($link, "select id from locations where organisations_id = '$org_id'");
				$tr_id = DAO::getSingleValue($link, "SELECT tr.id FROM tr LEFT JOIN student_qualifications ON tr.id = student_qualifications.`tr_id` LEFT JOIN framework_qualifications on framework_qualifications.id = student_qualifications.id left join frameworks on frameworks.id = framework_qualifications.framework_id and frameworks.framework_type = 99 WHERE tr.l03 = '$l03' AND REPLACE(student_qualifications.id,'/','') = '$A09';");
				$course_id = DAO::getSingleValue($link, "SELECT * FROM courses LEFT JOIN frameworks on frameworks.id = courses.framework_id and frameworks.framework_type = 99 LEFT JOIN framework_qualifications ON framework_qualifications.`framework_id` = frameworks.id WHERE REPLACE(framework_qualifications.id,'/','') = '$A09' AND organisations_id = '$org_id';");

				//pre("SELECT tr.id FROM tr LEFT JOIN student_qualifications ON tr.id = student_qualifications.`tr_id` LEFT JOIN framework_qualifications on framework_qualifications.id = student_qualifications.id left join frameworks on frameworks.id = framework_qualifications.framework_id and frameworks.framework_type = 99 WHERE tr.l03 = '$l03' AND REPLACE(student_qualifications.id,'/','') = '$A09';");

				if($org_id == '' || $loc_id == '' || $tr_id =='' || $course_id == '')
					pre("Data Missing org = " . $org_id . " loc = " . $loc_id);

				$link->query("update courses_tr set course_id = '$course_id' where tr_id = '$tr_id'");
				$link->query("update tr set provider_id = '$org_id', provider_location_id = '$loc_id' where id = '$tr_id'");

			}
		}

		// Apps Now
		$sql = "SELECT DISTINCT A03,A15,A26,A22 FROM aim WHERE A22!='00000000' AND A15!=99 and A15!=0";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				$l03 = $row['A03'];
				$ukprn = $row['A22'];
				$A15 = $row['A15'];
				$A26 = $row['A26'];


				$org_id = DAO::getSingleValue($link, "select id from organisations where ukprn = '$ukprn' and organisation_type = 3");
				$loc_id = DAO::getSingleValue($link, "select id from locations where organisations_id = '$org_id'");
				$tr_id = DAO::getSingleValue($link, "SELECT tr.id FROM tr LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id LEFT JOIN courses ON courses.id = courses_tr.`course_id` LEFT JOIN frameworks ON frameworks.id = courses.framework_id WHERE tr.l03 = '$l03' AND frameworks.framework_type = '$A15' AND frameworks.framework_code = '$A26';");
				$course_id = DAO::getSingleValue($link, "SELECT * FROM courses LEFT JOIN frameworks ON frameworks.id = courses.framework_id WHERE courses.organisations_id = '$org_id' AND frameworks.`framework_code` = '$A26' and frameworks.`framework_type` = '$A15';");

				//pre("SELECT tr.id FROM tr LEFT JOIN student_qualifications ON tr.id = student_qualifications.`tr_id` LEFT JOIN framework_qualifications on framework_qualifications.id = student_qualifications.id left join frameworks on frameworks.id = framework_qualifications.framework_id and frameworks.framework_type = 99 WHERE tr.l03 = '$l03' AND REPLACE(student_qualifications.id,'/','') = '$A09';");

				if($org_id == '' || $loc_id == '' || $tr_id =='' || $course_id == '')
					pre("Data Missing org = " . $org_id . " loc = " . $loc_id);

				$link->query("update courses_tr set course_id = '$course_id' where tr_id = '$tr_id'");
				$link->query("update tr set provider_id = '$org_id', provider_location_id = '$loc_id' where id = '$tr_id'");

			}
		}


		// Relink ILRs with TRs

/*		$sql = "SELECT * FROM tr GROUP BY l03 HAVING COUNT(l03)=1;";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				$tr_id = $row['id'];
				$l03 = $row['l03'];
				$link->query("update ilr set tr_id = '$tr_id' where l03 = '$l03'");
			}
		}
*/

		pre("complete");
	}
}
?>