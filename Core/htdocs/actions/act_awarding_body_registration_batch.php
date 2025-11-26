<?php
class awarding_body_registration_batch implements IAction
{
	public function execute(PDO $link)
	{

		$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : '';
		$group_id = isset($_GET['group_id']) ? $_GET['group_id'] : '';
		
		$course = Course::loadFromDatabase($link, $course_id);
		$centre_reference = $course->awarding_body_centre;
		$dt = date("Ymd:Hi");
		$filename = $centre_reference;
		
header("Content-Type: text/html");
header('Content-Disposition: attachment; filename="' . $filename . '.ERD"');

// Internet Explorer requires two extra headers when downloading files over HTTPS
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
{
	header('Pragma: public');
	header('Cache-Control: max-age=0');
}			

//		$cyear = DAO::getSingleValue($link, "SELECT contract_year FROM contracts ORDER BY contract_year DESC LIMIT 0,1;");
//		$submission = DAO::getSingleValue($link, "SELECT central.lookup_submission_dates.submission FROM central.lookup_submission_dates WHERE central.lookup_submission_dates.start_submission_date <= CURDATE() AND central.lookup_submission_dates.last_submission_date > CURDATE() ORDER BY last_submission_date LIMIT 0,1;");

		$enrolment_date = DAO::getSingleValue($link, "SELECT DATE_FORMAT(tr.start_date,'%Y%m%d') AS start_date FROM tr LEFT JOIN student_qualifications ON student_qualifications.tr_id = tr.id AND student_qualifications.awarding_body_reg IS NOT NULL AND student_qualifications.awarding_body_reg != '' LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id 	 WHERE (awarding_body_reg IS NULL OR awarding_body_reg = '') AND courses_tr.course_id = $course_id and tr.status_code = 1 ORDER BY start_date DESC LIMIT 0,1;");

		if($group_id=='')
			$st = $link->query("SELECT DATE_FORMAT(tr.target_date,'%Y%m%d') as target_date2, DATE_FORMAT(tr.dob,'%Y%m%d') as dob2, tr.id AS trid, tr.* FROM tr LEFT JOIN student_qualifications ON student_qualifications.tr_id = tr.id AND student_qualifications.awarding_body_reg IS NOT NULL AND student_qualifications.awarding_body_reg != '' LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id	WHERE (awarding_body_reg IS NULL OR awarding_body_reg = '') AND courses_tr.course_id = $course_id and tr.status_code = 1;");
		else
			$st = $link->query("SELECT DATE_FORMAT(tr.target_date,'%Y%m%d') as target_date2, DATE_FORMAT(tr.dob,'%Y%m%d') as dob2, tr.id AS trid, tr.* FROM tr LEFT JOIN student_qualifications ON student_qualifications.tr_id = tr.id AND student_qualifications.awarding_body_reg IS NOT NULL AND student_qualifications.awarding_body_reg != '' LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id LEFT JOIN group_members ON group_members.tr_id = tr.id	WHERE (awarding_body_reg IS NULL OR awarding_body_reg = '') AND courses_tr.course_id = $course_id and tr.status_code = 1 AND group_members.groups_id = '$group_id';");
		
		$count = 1;
		if($st) 
		{
			echo 'UNB+UNOA+2+' . $centre_reference . '+EDEX+' . $dt . '+0001++++++0';
			echo "\r\n";
			echo 'UNG+REGIST+STUDENTSERVICES++'.$dt.'+0001+BT+6:1+++';
			echo "\r\n";
			echo 'UNH+0001+REGIST+6:1++1:F';
			echo "\r\n";
			echo 'REG1+R+'. $centre_reference .'+' . $course->programme_number . '+' . $enrolment_date . '++++++';
			echo "\r\n";
			while($row = $st->fetch())
			{
				try
				{
					echo 'REG2+' . $row['trid'] . '+' . $row['firstnames'] . '+' . $row['surname'] . '+' . $row['gender'] . '+' . $row['dob2'] . '+' . $row['target_date2'] . '++++++';
					echo "\r\n";
					$count++;
				}
				catch(Exception $e)
				{
					throw new Exception($row['ilr']);
				}
			}
			echo 'UNT+' . $count . '+0001';
			echo "\r\n";
			echo 'UNE+1+0001';
			echo "\r\n";
			echo 'UNZ+1+0001';
		}

	}
}
?>