<?php
class start_import_process implements IAction
{
	private $where_clause = NULL;
	private $tt = NULL;

	public function execute(PDO $link)
	{
/*		switch(DB_NAME)
		{
			case 'am_doncaster':
				if($_SESSION['user']->username != 'lsmith' || !SOURCE_BLYTHE_VALLEY)
					throw new Exception('Data Import Error: You are not authorised to perform this action. Please contact your System Administrator or raise a support request.');
		}*/
		set_time_LIMIT(0);
		DAO::execute($link, "SET SESSION group_concat_max_len = 1000000;");

		$time = (int)isset($_REQUEST['time'])?$_REQUEST['time']:'';
		$entity = isset($_REQUEST['entity'])?$_REQUEST['entity']:'';

		$this->tt = $time;

		$this->where_clause = " DATE_FORMAT(upload_time,'%d %M %Y %H:%i:%s') = '$time' AND status!='Record Length Error'";


		if($entity != '')
		{
			switch($entity)
			{
				case 'special':
					DAO::execute($link, "UPDATE data_imports SET validated = 0 WHERE username = '{$_SESSION['user']->username}' AND DATE_FORMAT(file_datetime,'%d %M %Y %H:%i:%s') = '{$this->tt}'");
					echo $this->specialValidate($link);
					exit;
				case 'validation':
					$this->validate($link);
					exit;
				case 'employers':
					$outputHTML = "";
					// add employer records
					$employers = $this->addEmployers($link);
					if(count($employers) > 0)
					{
						$outputHTML .= '<h3>Employers Created</h3>';
						$outputHTML .= '<table class="resultset" cellspacing="0" cellpadding="4">';
						$outputHTML .= '<caption><strong>Following new employers are created in Sunesis.</strong></caption>';
						$outputHTML .= '<tr><th>EDRS Number</th><th>Legal Name</th><th>Postcode</th></tr>';
						foreach($employers AS $e)
						{
							$outputHTML .= $e;
						}
						$outputHTML .= '</table>';
					}

					// update employer records
					$employers = $this->updateEmployers($link);
					if(count($employers) > 0)
					{
						$outputHTML .= '<h3>Employers Updated</h3>';
						$outputHTML .= '<table class="resultset" cellspacing="0" cellpadding="4">';
						$outputHTML .= '<caption><strong>Following employers are updated in Sunesis.</strong></caption>';
						$outputHTML .= '<tr><th>EDRS Number</th><th>Legal Name</th><th>Postcode</th></tr>';
						foreach($employers AS $e)
						{
							$outputHTML .= $e;
						}
						$outputHTML .= '</table>';
					}
					echo $outputHTML;
					DAO::execute($link, "UPDATE data_imports SET imp_employers = 1 WHERE username = '{$_SESSION['user']->username}' AND DATE_FORMAT(file_datetime,'%d %M %Y %H:%i:%s') = '{$this->tt}'");
					exit;
				case 'frameworks':
					$outputHTML = "";
					$frameworks = $this->addFrameworks($link);
					if(count($frameworks) > 0)
					{
						$outputHTML .= '<h3>Frameworks Created</h3>';
						$outputHTML .= '<table class="resultset" cellspacing="0" cellpadding="4">';
						$outputHTML .= '<caption><strong>Following new frameworks are created in Sunesis.</strong></caption>';
						$outputHTML .= '<tr><th>Code</th><th>Type</th><th>Title</th></tr>';
						foreach($frameworks AS $f)
						{
							$outputHTML .= $f;
						}
						$outputHTML .= '</table>';
					}
					else
					{
						$outputHTML .= "No new frameworks created";
					}
					echo $outputHTML;
					DAO::execute($link, "UPDATE data_imports SET imp_frameworks = 1 WHERE username = '{$_SESSION['user']->username}' AND DATE_FORMAT(file_datetime,'%d %M %Y %H:%i:%s') = '{$this->tt}'");
					exit;
				case 'courses':
					$outputHTML = "";
					$courses = $this->addCourses($link);
					if(count($courses) > 0)
					{
						$outputHTML .= '<h3>Courses Created</h3>';
						$outputHTML .= '<table class="resultset" cellspacing="0" cellpadding="4">';
						$outputHTML .= '<caption><strong>Following new courses are created in Sunesis.</strong></caption>';
						$outputHTML .= '<tr><th>Title</th><th>Course Start Date</th><th>Course End Date</th></tr>';
						foreach($courses AS $c)
						{
							$outputHTML .= $c;
						}
						$outputHTML .= '</table>';
					}
					else
					{
						$outputHTML .= "No new courses created";
					}
					echo $outputHTML;
					DAO::execute($link, "UPDATE data_imports SET imp_courses = 1 WHERE username = '{$_SESSION['user']->username}' AND DATE_FORMAT(file_datetime,'%d %M %Y %H:%i:%s') = '{$this->tt}'");
					exit;
				case 'framework_quals':
					$outputHTML = "";
					$framework_quals = $this->addUpdateFrameworkQuals($link);
					if($framework_quals)
					{
						$outputHTML .= 'Frameworks Qualifications are added AND updated successfully';
					}
					else
					{
						$outputHTML .= "Query Execution Failed";
					}
					echo $outputHTML;
					DAO::execute($link, "UPDATE data_imports SET imp_framework_quals = 1 WHERE username = '{$_SESSION['user']->username}' AND DATE_FORMAT(file_datetime,'%d %M %Y %H:%i:%s') = '{$this->tt}'");
					exit;
				case 'course_quals':
					$outputHTML = "";
					$course_quals = $this->addUpdateCourseQuals($link);
					if($course_quals)
					{
						$outputHTML .= 'Courses Qualifications are added AND updated successfully';
					}
					else
					{
						$outputHTML .= "Query Execution Failed";
					}
					echo $outputHTML;
					DAO::execute($link, "UPDATE data_imports SET imp_course_quals = 1 WHERE username = '{$_SESSION['user']->username}' AND DATE_FORMAT(file_datetime,'%d %M %Y %H:%i:%s') = '{$this->tt}'");
					exit;
				case 'training_records':
					$outputHTML = "";
					$training_records = $this->addTrainingRecords($link);
					if(count($training_records) > 0)
					{
						$outputHTML .= '<h3>Training Records Created</h3>';
						$outputHTML .= '<table class="resultset" cellspacing="0" cellpadding="4">';
						$outputHTML .= '<caption><strong>Following new training records are created in Sunesis.</strong></caption>';
						$outputHTML .= '<tr><th>First name </th><th>Surname</th><th>Learning Reference Number</th></tr>';
						foreach($training_records AS $tr)
						{
							$outputHTML .= $tr;
						}
						$outputHTML .= '</table>';
					}
					else
					{
						$outputHTML .= "No new training records are created";
					}
					echo $outputHTML;
					DAO::execute($link, "UPDATE data_imports SET imp_training_records = 1 WHERE username = '{$_SESSION['user']->username}' AND DATE_FORMAT(file_datetime,'%d %M %Y %H:%i:%s') = '{$this->tt}'");
					exit;
				case 'learners':
					$outputHTML = "";
					$learners = $this->addLearners($link);
					if(count($learners) > 0)
					{
						$outputHTML .= '<h3>Learners Created</h3>';
						$outputHTML .= '<table class="resultset" cellspacing="0" cellpadding="4">';
						$outputHTML .= '<caption><strong>Following new learners are created in Sunesis.</strong></caption>';
						$outputHTML .= '<tr><th>First name </th><th>Surname</th><th>Username</th></tr>';
						foreach($learners AS $l)
						{
							$outputHTML .= $l;
						}
						$outputHTML .= '</table>';
					}
					else
					{
						$outputHTML .= "No new learners are created";
					}
					echo $outputHTML;
					DAO::execute($link, "UPDATE data_imports SET imp_learners = 1 WHERE username = '{$_SESSION['user']->username}' AND DATE_FORMAT(file_datetime,'%d %M %Y %H:%i:%s') = '{$this->tt}'");
					exit;
				case 'student_quals':
					$outputHTML = "";
					$student_quals = $this->importStudentQuals($link);
//					$student_quals = true;
					if($student_quals)
					{
						$outputHTML .= 'Student Qualifications are added AND updated successfully';
					}
					else
					{
						$outputHTML .= "Query Execution Failed";
					}
					echo $outputHTML;
					DAO::execute($link, "UPDATE data_imports SET imp_student_quals = 1 WHERE username = '{$_SESSION['user']->username}' AND DATE_FORMAT(file_datetime,'%d %M %Y %H:%i:%s') = '{$this->tt}'");
					exit;
				case 'ilrs':
					$outputHTML = "";
					$ilrs_updated = $this->updateILRs($link);
					if($ilrs_updated)
					{
						$outputHTML .= 'ILRS are created AND updated successfully';
					}
					else
					{
						$outputHTML .= "Query Execution Failed";
					}
					echo $outputHTML;
					DAO::execute($link, "UPDATE data_imports SET imp_ilrs = 1 WHERE username = '{$_SESSION['user']->username}' AND DATE_FORMAT(file_datetime,'%d %M %Y %H:%i:%s') = '{$this->tt}'");
					exit;
			}
		}
		else
		{
			$rs = DAO::getResultset($link, "SELECT * FROM data_imports WHERE username = '{$_SESSION['user']->username}' AND DATE_FORMAT(file_datetime,'%d %M %Y %H:%i:%s') = '{$time}'", DAO::FETCH_ASSOC);
			$rs = $rs[0];
			$validate_button = 'false';
			$imp_employers = 'true';
			$imp_frameworks = 'true';
			$imp_courses = 'true';
			$imp_framework_quals= 'true';
			$imp_course_quals = 'true';
			$imp_learners = 'true';
			$imp_training_records = 'true';
			$imp_student_quals = 'true';
			$imp_ilrs = 'true';
			if($rs['validated'] == 1)
			{
				$validate_button = 'true';//disable
				$imp_employers = 'false'; //enable
			}
			if($rs['imp_employers'] == 1)
			{
				$imp_employers = 'true';
				$imp_frameworks = 'false';
			}
			if($rs['imp_frameworks'] == 1)
			{
				$imp_frameworks = 'true';
				$imp_courses = 'false';
			}
			if($rs['imp_courses'] == 1)
			{
				$imp_courses = 'true';
				$imp_framework_quals = 'false';
			}
			if($rs['imp_framework_quals'] == 1)
			{
				$imp_framework_quals = 'true';
				$imp_course_quals = 'false';
			}
			if($rs['imp_course_quals'] == 1)
			{
				$imp_course_quals = 'true';
				$imp_learners = 'false';
			}
			if($rs['imp_learners'] == 1)
			{
				$imp_learners = 'true';
				$imp_training_records = 'false';
			}
			if($rs['imp_training_records'] == 1)
			{
				$imp_training_records = 'true';
				$imp_student_quals = 'false';
			}
			if($rs['imp_student_quals'] == 1)
			{
				$imp_student_quals = 'true';
				$imp_ilrs = 'false';
			}
			if($rs['imp_ilrs'] == 1)
			{
				$imp_ilrs = 'true';
			}


		}

		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=start_import_process&time=" . $time, "View Upload");

		require_once('tpl_start_import_process.php');
	}

	private function checkQualifications(PDO $link)
	{
		$qualificationCheck = DAO::getSingleValue($link, "SELECT COUNT(*) FROM exg WHERE learnaimref!='ZPROG001' AND learnaimref NOT IN (SELECT REPLACE(id,'/','') FROM qualifications) and $this->where_clause;");
		if($qualificationCheck >= 1)
		{
			echo '<table  class="resultset" cellspacing="0" cellpadding="4"><caption>Qualifications not found in Sunesis</caption><thead><th>Qualification Number in CSV</th></thead>';
			echo '<tbody>';
			$st = DAO::query($link, "SELECT distinct learnaimref FROM exg WHERE learnaimref!='ZPROG001' AND learnaimref NOT IN (SELECT REPLACE(id,'/','') FROM qualifications) and $this->where_clause;");
			if($st)
			{
				$size = count($st->rowCount());
				while($row = $st->fetch(PDO::FETCH_ASSOC))
				{
					echo '<tr><td>' . $row['learnaimref'] . '</td></tr>';
				}
			}
			else
			{
				throw new Exception('Query failed in checkQualifications.');
			}
			echo '</tbody></table><br>';
		}
	}

	private function checkEmployersWithMissingEDRS(PDO $link)
	{
		$edrsCheck = DAO::getSingleValue($link, "SELECT COUNT(*) FROM exg WHERE edrs = '' and $this->where_clause;");

		if($edrsCheck >= 1)
		{
			echo '<table  class="resultset" cellspacing="0" cellpadding="4"><caption>Employers with missing EDRS in CSV</caption><thead><th>Employer Name</th></thead>';
			$st = DAO::query($link, "SELECT distinct employer FROM exg WHERE edrs = '' and $this->where_clause;");
			if($st)
			{
				while($row = $st->fetch(PDO::FETCH_ASSOC))
				{
					$i = ' Employer: ' . $row['employer'] . ' ';
					echo '<tr><td>' . $row['employer'] . '</td></tr>';
				}
			}
			else
			{
				throw new Exception('Query failed in checkEmployersWithMissingEDRS.');
			}
			echo '</tbody></table><br>';
		}
	}

	private function checkExponentialL03(PDO $link)
	{
		$exponentialL03Check = DAO::getSingleValue($link, "SELECT COUNT(*) FROM exg WHERE learnrefnumber LIKE '%E+%' and $this->where_clause;");
		if($exponentialL03Check >= 1)
		{
			echo '<table  class="resultset" cellspacing="0" cellpadding="4"><caption>Invalid L03s found in CSV</caption><thead><th>L03</th><th>First Name</th><th>Surname</th></thead>';
			echo '<tbody>';
			$st = DAO::query($link, "SELECT * FROM exg WHERE learnrefnumber LIKE '%E+%' and $this->where_clause  group by firstnames, surname, dob;");
			if($st)
			{
				while($row = $st->fetch(PDO::FETCH_ASSOC))
				{
					echo '<tr><td>' . $row['learnrefnumber'] . '</td><td>' . $row['firstnames'] . '</td><td>' . $row['surname'] . '</td></tr>';
				}
			}
			else
			{
				throw new Exception('Query failed in checkExponentialL03.');
			}
			echo '</tbody></table><br>';
		}
	}

	private function checkAssessors(PDO $link)
	{
		$assessorCheck = DAO::getSingleValue($link, "SELECT COUNT(*) FROM exg WHERE exg.assessor !='' AND exg.assessor NOT IN (SELECT CONCAT(users.firstnames,' ', users.surname) FROM users) and $this->where_clause;");
		if($assessorCheck >= 1)
		{
			echo '<table  class="resultset" cellspacing="0" cellpadding="4"><caption>Assessors not found in Sunesis</caption><thead><th>Assessor Name in CSV</th></thead>';
			echo '<tbody>';
			$st = DAO::query($link, "SELECT distinct exg.assessor FROM exg WHERE exg.assessor !='' AND exg.assessor NOT IN (SELECT CONCAT(users.firstnames,' ', users.surname) FROM users) and $this->where_clause;");
			if($st)
			{
				while($row = $st->fetch(PDO::FETCH_ASSOC))
				{
					echo '<tr><td>' . $row['assessor'] . '</td></tr>';
				}
			}
			else
			{
				throw new Exception('Query failed in checkAssessors.');
			}
			echo '</tbody></table><br>';
		}
	}

	private function validate(PDO $link)
	{
		$this->checkExponentialL03($link);
		$this->checkQualifications($link);
		$this->checkEmployersWithMissingEDRS($link);
		$this->checkAssessors($link);
	}

	private function specialValidate(PDO $link)
	{
		$exponentialL03Check = DAO::getSingleValue($link, "SELECT COUNT(*) FROM exg WHERE learnrefnumber LIKE '%E+%' and $this->where_clause;");
		if($exponentialL03Check >= 1)
			return $exponentialL03Check;
		$qualificationCheck = DAO::getSingleValue($link, "SELECT COUNT(*) FROM exg WHERE learnaimref!='ZPROG001' AND learnaimref NOT IN (SELECT REPLACE(id,'/','') FROM qualifications) and $this->where_clause;");
		if($qualificationCheck >= 1)
			return $qualificationCheck;
		$edrsCheck = DAO::getSingleValue($link, "SELECT COUNT(*) FROM exg WHERE edrs = '' and $this->where_clause;");
		if($edrsCheck >= 1)
			return $edrsCheck;
		$assessorCheck = DAO::getSingleValue($link, "SELECT COUNT(*) FROM exg WHERE exg.assessor !='' AND exg.assessor NOT IN (SELECT CONCAT(users.firstnames,' ', users.surname) FROM users) and $this->where_clause;");
		if($assessorCheck >= 1)
			return $assessorCheck;
		DAO::execute($link, "UPDATE data_imports SET validated = 1 WHERE username = '{$_SESSION['user']->username}' AND DATE_FORMAT(file_datetime,'%d %M %Y %H:%i:%s') = '{$this->tt}'");
	}

	private function addEmployers(PDO $link)
	{
		$employers_created = array();
		$newemployers = DAO::getSingleValue($link, "SELECT count(DISTINCT edrs) FROM exg WHERE edrs IS NOT NULL AND edrs!='' AND edrs NOT IN (SELECT edrs FROM organisations WHERE edrs IS NOT NULL) AND $this->where_clause;");
		if($newemployers > 0)
		{
			$st = DAO::query($link, "SELECT DISTINCT edrs FROM exg WHERE edrs IS NOT NULL AND edrs!='' AND edrs NOT IN (SELECT edrs FROM organisations WHERE edrs IS NOT NULL) AND $this->where_clause;");
			if($st)
			{
				try
				{
					while($row = $st->fetch(PDO::FETCH_ASSOC))
					{
						$e = new Employer();
						$edrs = $row['edrs'];
						$e->edrs = $edrs;
						$e->legal_name = DAO::getSingleValue($link, "SELECT employer FROM exg WHERE edrs = '{$edrs}' AND $this->where_clause LIMIT 0,1");
						$e->trading_name = DAO::getSingleValue($link, "SELECT employer FROM exg WHERE edrs = '{$edrs}' AND $this->where_clause LIMIT 0,1");
						$e->active = 1;
						$e->save($link);
						$found = $e->id;

						$loc = new Location();
						$loc->short_name = "Main Site";
						$loc->full_name = "Main Site";
						$loc->organisations_id = $found;
						$loc->address_line_1 = DAO::getSingleValue($link, "SELECT empadd1 FROM exg WHERE edrs = '{$edrs}' AND $this->where_clause LIMIT 0,1");
						$loc->address_line_2 = DAO::getSingleValue($link, "SELECT empadd2 FROM exg WHERE edrs = '{$edrs}' AND $this->where_clause LIMIT 0,1");
						$loc->address_line_3 = DAO::getSingleValue($link, "SELECT empadd3 FROM exg WHERE edrs = '{$edrs}' AND $this->where_clause LIMIT 0,1");
						$loc->address_line_4 = DAO::getSingleValue($link, "SELECT empadd4 FROM exg WHERE edrs = '{$edrs}' AND $this->where_clause LIMIT 0,1");
						$loc->postcode = DAO::getSingleValue($link, "SELECT emppostcode FROM exg WHERE edrs = '{$edrs}' AND $this->where_clause LIMIT 0,1");
						$loc->is_legal_address = 1;
						$loc->telephone = DAO::getSingleValue($link, "SELECT LEFT(emptel, 20) FROM exg WHERE edrs = '{$edrs}' AND $this->where_clause LIMIT 0,1");
						$loc->contact_name = DAO::getSingleValue($link, "SELECT contactname FROM exg WHERE edrs = '{$edrs}' AND $this->where_clause LIMIT 0,1");
						$loc->save($link);
						$loc_id = $loc->id;
						$lastvisit = DAO::getSingleValue($link, "SELECT lastvisit FROM exg WHERE edrs = '{$edrs}' AND $this->where_clause ORDER BY lastvisit DESC LIMIT 0,1");
						if($lastvisit!='')
							$lastvisit = "'" . Date::toMySQL($lastvisit) . "'";
						else
							$lastvisit = "NULL";
						$hsexpiry = DAO::getSingleValue($link, "SELECT hsexpiry FROM exg WHERE edrs = '{$edrs}' AND $this->where_clause ORDER BY hsexpiry DESC LIMIT 0,1");
						if($hsexpiry!='')
							$hsexpiry = "'" . Date::toMySQL($hsexpiry) . "'";
						else
							$hsexpiry = "NULL";
						DAO::execute($link, "INSERT INTO health_safety values ( {$loc_id},{$lastvisit},{$hsexpiry},'','',1,1,1,NULL,'')");
						$employers_created[] = '<tr class="Data"><td>' . $e->edrs . '</td><td>' . $e->legal_name . '</td><td>' . $loc->postcode . '</td></tr>';
					}
				}
				catch(Exception $e)
				{
					// write to errors file
				}
			}
			return $employers_created;
		}
	}

	private function updateEmployers(PDO $link)
	{
		$employers_updated = array();
		$st = DAO::query($link, "SELECT DISTINCT edrs,employer,empadd1,empadd2,empadd3,empadd4,emppostcode,emptel,contactname,hsexpiry,lastvisit FROM exg WHERE edrs IS NOT NULL AND edrs!='' AND edrs IN (SELECT edrs FROM organisations WHERE edrs IS NOT NULL) AND $this->where_clause;");
		if($st)
		{
			try
			{
				while($row = $st->fetch(PDO::FETCH_ASSOC))
				{
					$edrs = $row['edrs'];
					$id = DAO::getSingleValue($link, "SELECT id FROM organisations WHERE edrs = '{$edrs}' LIMIT 0,1");
					$e = Employer::loadFromDatabase($link, $id);
					$e->legal_name = $row['employer'];
					$e->trading_name = $row['employer'];
					$e->active = 1;
					$e->save($link);
					$found = $e->id;

					$loc_id = DAO::getSingleValue($link, "SELECT id FROM locations WHERE organisations_id = '{$found}' LIMIT 0,1");
					if($loc_id)
					{
						$loc = Location::loadFromDatabase($link,$loc_id);
						$loc->short_name = "Main Site";
						$loc->full_name = "Main Site";
						$loc->address_line_1 = $row['empadd1'];
						$loc->address_line_2 = $row['empadd2'];
						$loc->address_line_3 = $row['empadd3'];
						$loc->address_line_4 = $row['empadd4'];
						$loc->postcode = $row['emppostcode'];
						$loc->is_legal_address = 1;
						$loc->telephone = substr($row['emptel'],0,20);
						$loc->contact_name = $row['contactname'];
						$loc->save($link);
					}
					else
					{
						$loc = new Location();
						$loc->short_name = "Main Site";
						$loc->full_name = "Main Site";
						$loc->organisations_id = $found;
						$loc->address_line_1 = DAO::getSingleValue($link, "SELECT empadd1 FROM exg WHERE edrs = '{$edrs}' AND $this->where_clause LIMIT 0,1");
						$loc->address_line_2 = DAO::getSingleValue($link, "SELECT empadd2 FROM exg WHERE edrs = '{$edrs}' AND $this->where_clause LIMIT 0,1");
						$loc->address_line_3 = DAO::getSingleValue($link, "SELECT empadd3 FROM exg WHERE edrs = '{$edrs}' AND $this->where_clause LIMIT 0,1");
						$loc->address_line_4 = DAO::getSingleValue($link, "SELECT empadd4 FROM exg WHERE edrs = '{$edrs}' AND $this->where_clause LIMIT 0,1");
						$loc->postcode = DAO::getSingleValue($link, "SELECT emppostcode FROM exg WHERE edrs = '{$edrs}' AND $this->where_clause LIMIT 0,1");
						$loc->is_legal_address = 1;
						$loc->telephone = DAO::getSingleValue($link, "SELECT LEFT(emptel, 20) FROM exg WHERE edrs = '{$edrs}' AND $this->where_clause LIMIT 0,1");
						$loc->contact_name = DAO::getSingleValue($link, "SELECT contactname FROM exg WHERE edrs = '{$edrs}' AND $this->where_clause LIMIT 0,1");
						$loc->save($link);
						$loc_id = $loc->id;
						$lastvisit = DAO::getSingleValue($link, "SELECT lastvisit FROM exg WHERE edrs = '{$edrs}' AND $this->where_clause ORDER BY lastvisit DESC LIMIT 0,1");
						if($lastvisit!='')
							$lastvisit = "'" . Date::toMySQL($lastvisit) . "'";
						else
							$lastvisit = "NULL";
						$hsexpiry = DAO::getSingleValue($link, "SELECT hsexpiry FROM exg WHERE edrs = '{$edrs}' AND $this->where_clause ORDER BY hsexpiry DESC LIMIT 0,1");
						if($hsexpiry!='')
							$hsexpiry = "'" . Date::toMySQL($hsexpiry) . "'";
						else
							$hsexpiry = "NULL";
						DAO::execute($link, "INSERT INTO health_safety values({$loc_id},{$lastvisit},{$hsexpiry},'','',1,1,1,NULL,'')");
					}
					$employers_updated[] = '<tr class="Data"><td>' . $e->edrs . '</td><td>' . $e->legal_name . '</td><td>' . $loc->postcode . '</td></tr>';
				}
			}
			catch(Exception $e)
			{
				// write to errors file
			}
		}
		return $employers_updated;
	}

	private function addFrameworks(PDO $link)
	{
		$frameworks_added = array();
		$p_org = DAO::getSingleValue($link, "SELECT id FROM organisations WHERE organisation_type = " . Organisation::TYPE_TRAINING_PROVIDER . " LIMIT 0,1");
		if(DB_NAME=="am_doncaster" || DB_NAME == "am_donc_demo")
			$sql = "SELECT DISTINCT CONCAT(fworkcode,' - ',lad.FRAMEWORK_DESC) AS Framework_Title, NULL, fworkcode, NULL, 18, 1750,1, NULL, actual_prog_route FROM exg INNER JOIN lad201314.`frameworks` AS lad ON lad.`FRAMEWORK_CODE` = exg.`fworkcode` AND lad.`FRAMEWORK_TYPE_CODE` = exg.`actual_prog_route` AND lad.`FRAMEWORK_PATHWAY_CODE` = exg.`app_pathway` WHERE CONCAT(fworkcode,'*',actual_prog_route) NOT IN (SELECT CONCAT(framework_code,'*',framework_type) FROM frameworks WHERE framework_code IS NOT NULL);";
		elseif(DB_NAME=="am_siemens")
			$sql = "SELECT DISTINCT CONCAT(fworkcode,' - ',lad.FRAMEWORK_DESC) AS Framework_Title, NULL, fworkcode, NULL, 18, 1658,1, NULL, actual_prog_route FROM exg INNER JOIN lad201314.`frameworks` AS lad ON lad.`FRAMEWORK_CODE` = exg.`fworkcode` AND lad.`FRAMEWORK_TYPE_CODE` = exg.`actual_prog_route` AND lad.`FRAMEWORK_PATHWAY_CODE` = exg.`app_pathway` WHERE CONCAT(fworkcode,'*',actual_prog_route) NOT IN (SELECT CONCAT(framework_code,'*',framework_type) FROM frameworks WHERE framework_code IS NOT NULL);";
		else
			$sql = "SELECT DISTINCT CONCAT(fworkcode,' - ',lad.FRAMEWORK_DESC) AS Framework_Title, NULL, fworkcode, NULL, 18, {$p_org},1, NULL, actual_prog_route FROM exg INNER JOIN lad201314.`frameworks` AS lad ON lad.`FRAMEWORK_CODE` = exg.`fworkcode` AND lad.`FRAMEWORK_TYPE_CODE` = exg.`actual_prog_route` AND lad.`FRAMEWORK_PATHWAY_CODE` = exg.`app_pathway` WHERE CONCAT(fworkcode,'*',actual_prog_route) NOT IN (SELECT CONCAT(framework_code,'*',framework_type) FROM frameworks WHERE framework_code IS NOT NULL);";
		$frameworks_to_be_added = DAO::getResultset($link, $sql, PDO::FETCH_ASSOC);
		foreach($frameworks_to_be_added AS $framework)
		{
			try
			{
				$f = new Framework();
				$f->title = $framework['Framework_Title'];
				$f->framework_code = $framework['fworkcode'];
				$f->comments = NULL;
				$f->duration_in_months = 18;
				if(DB_NAME=="am_doncaster" || DB_NAME == "am_donc_demo")
					$f->parent_org = 1750;
				elseif(DB_NAME=="am_siemens")
					$f->parent_org = 1658;
				else
					$f->parent_org = $p_org;
				$f->active = 1;
				$f->clients = NULL;
				$f->framework_type = $framework['actual_prog_route'];
				$f->save($link);
				$frameworks_added[] = '<tr class="Data"><td>' . $f->framework_code . '</td><td>' . $f->framework_type . '</td><td>' . $f->title . '</td></tr>';
			}
			catch(Exception $e)
			{
				// write to errors file
			}
		}
		return $frameworks_added;
	}

	private function addCourses(PDO $link)
	{
		$courses_added = array();
		$st = DAO::query($link, "SELECT * FROM frameworks;");
		if($st)
		{
			try
			{
				while($row = $st->fetch(PDO::FETCH_ASSOC))
				{
					$fid = $row['id'];
					$course_id = DAO::getSingleValue($link, "SELECT id FROM courses WHERE framework_id = {$fid} ORDER BY id DESC LIMIT 0,1");
					if($course_id=='')
					{
						$f = Framework::loadFromDatabase($link, $fid);
						$course = new Course($link);
						$course->id = NULL;
						$course->organisations_id = DAO::getSingleValue($link, "SELECT id FROM organisations WHERE organisation_type = " . Organisation::TYPE_TRAINING_PROVIDER . " LIMIT 0,1");
						$course->title = $f->title;
						$course->framework_id = $f->id;
						$course->programme_type = 2;
						$course->active = 1;
						$course->course_start_date = '2013-01-01';
						$course->course_end_date = '2020-12-31';
						$course->save($link);
						$courses_added[] = '<tr class="Data"><td>' . $course->title . '</td><td>' . $course->course_start_date . '</td><td>' . $course->course_end_date . '</td></tr>';
					}
				}
			}
			catch(Exception $e)
			{
				// write to errors file
			}
		}
		return $courses_added;
	}

	private function addUpdateFrameworkQuals(PDO $link)
	{
		$st = DAO::query($link, "SELECT DISTINCT fworkcode, actual_prog_route FROM exg WHERE fworkcode != '' AND actual_prog_route != '' AND $this->where_clause;");
		if($st)
		{
			try
			{
				while($row = $st->fetch(PDO::FETCH_ASSOC))
				{
					$fworkcode = $row['fworkcode'];
					$progtype = $row['actual_prog_route'];
					DAO::execute($link, "INSERT IGNORE INTO framework_qualifications SELECT DISTINCT qualifications.id, lsc_learning_aim, awarding_body, qualifications.title, description, assessment_method, structure, `level`, qualification_type,
		qualifications.`operational_start_date`, `dfes_approval_start_date`, certification_end_date , certification_end_date, dfes_approval_start_date, dfes_approval_end_date,
		frameworks.id, evidences, units, internaltitle, 10, frameworks.`duration_in_months`, units_required, mandatory_units, 0 FROM qualifications
		INNER JOIN exg ON exg.`learnaimref` = REPLACE(qualifications.id,'/','')
		INNER JOIN frameworks ON exg.`fworkcode` = frameworks.`framework_code` AND exg.`actual_prog_route` = frameworks.`framework_type`
		WHERE fworkcode='{$fworkcode}' AND actual_prog_route = '{$progtype}' AND learnaimref!='ZPROG001';");
				}
			}
			catch(Exception $e)
			{
				// write to errors file
			}
			return true;
		}
		else
			return false;
	}

	private function addUpdateCourseQuals(PDO $link)
	{
		$st = DAO::query($link, "SELECT * FROM frameworks;");
		if($st)
		{
			try
			{
				while($row = $st->fetch(PDO::FETCH_ASSOC))
				{
					$fid = $row['id'];
					$course_ids = DAO::getResultset($link, "SELECT id FROM courses WHERE framework_id = {$fid} ORDER BY id DESC LIMIT 0,1", DAO::FETCH_ASSOC);
					foreach($course_ids AS $course_id)
					{
						DAO::execute($link, "INSERT IGNORE INTO course_qualifications_dates
		(SELECT framework_qualifications.id, framework_qualifications.framework_id, internaltitle, '{$course_id}', courses.course_start_date, DATE_ADD(courses.course_start_date,
		INTERVAL framework_qualifications.duration_in_months MONTH), '0', '0', '0'
		FROM framework_qualifications
		LEFT JOIN courses ON courses.framework_id = framework_qualifications.`framework_id`
		WHERE framework_qualifications.framework_id = '{$fid}');");
					}
				}
			}
			catch(Exception $e)
			{
				// write to errors file
			}
			return true;
		}
		return false;
	}

	private function addTrainingRecords(PDO $link)
	{
		$training_records_created = array();
		$p_org = DAO::getSingleValue($link, "SELECT id FROM organisations WHERE organisation_type = " . Organisation::TYPE_TRAINING_PROVIDER . " LIMIT 0,1");
		// Check Training Records
		$st = DAO::query($link, "SELECT * FROM exg WHERE learnaimref = 'ZPROG001' AND $this->where_clause;");
		if($st)
		{
			try
			{
				while($row = $st->fetch(PDO::FETCH_ASSOC))
				{
					$learnstartdate = $row['learnstartdate'];
					$plannedenddate = $row['plannedenddate'];
					$learnrefnumber = $row['learnrefnumber'];
					$exists = DAO::getSingleValue($link, "SELECT count(*) FROM tr WHERE l03 = '{$learnrefnumber}' AND start_date='{$learnstartdate}' AND target_date='{$plannedenddate}' ");
					if(!$exists)
					{
						$learnrefnumber = $row['learnrefnumber'];
						$contract_id = DAO::getSingleValue($link, "SELECT id FROM contracts WHERE contract_year = 2015");
						$fworkcode = $row['fworkcode'];
						$programmetype = $row['actual_prog_route'];
						if(DB_NAME=="am_doncaster" || DB_NAME == "am_donc_demo")
							$provider_id = 1750;
						elseif(DB_NAME=="am_siemens")
							$provider_id = 1658;
						else
							$provider_id = $p_org;
						$course_id = DAO::getSingleValue($link, "SELECT courses.id FROM courses INNER JOIN frameworks ON frameworks.id = courses.`framework_id` WHERE frameworks.`framework_code` = '{$fworkcode}' AND frameworks.`framework_type` = '{$programmetype}';");
						$user = User::loadFromDatabase($link, $learnrefnumber);
						if($learnrefnumber!='' && $contract_id!='' && $course_id!='')
						{
							$training_records_created[] = $this->enrolLearner($link,$row,$user,$contract_id,$provider_id,$course_id,$this->where_clause);
						}
					}
				}
			}
			catch(Exception $e)
			{
				// write to errors file
			}
		}
		return $training_records_created;
	}

	public function enrolLearner($link,$row,$user,$contract_id,$provider_id,$course_id, $where_clause='')
	{
		$training_records_created = "";
		// Enrol Learner now
		$tr = new TrainingRecord();
		$tr->populate($user, true);
		$tr->contract_id = $contract_id;
		if(DB_NAME=='am_doncaster' || DB_NAME=='am_donc_demo' || DB_NAME=='ams' || DB_NAME=='am_siemens')
		{
			$sd = Date::toMySQL($row['learnstartdate']);
			$ed = Date::toMySQL($row['plannedenddate']);
		}
		else
		{
			$sd = Date::toMySQL($row['start_date']);
			$ed = Date::toMySQL($row['planned_end_date']);
		}
		$tr->start_date = $sd;
		$tr->target_date = $ed;
		$tr->status_code = 1;
		$tr->provider_id = $provider_id;
		$location_id = DAO::getSingleValue($link, "SELECT id FROM locations WHERE organisations_id='{$provider_id}'");
		if($location_id!='')
			$provider = Location::loadFromDatabase($link, $location_id);
		else
			$provider = new Location();
		$tr->provider_location_id = $location_id;
		$tr->provider_address_line_1 = $provider->address_line_1;
		$tr->provider_address_line_2 = $provider->address_line_2;
		$tr->provider_address_line_3 = $provider->address_line_3;
		$tr->provider_address_line_4 = $provider->address_line_4;
		$tr->provider_postcode = $provider->postcode;
		$tr->provider_telephone = $provider->telephone;
		$tr->ethnicity = $user->ethnicity;
		$tr->work_experience = 0;
		$tr->upi = $row['curriculum'];
		$tr->l36 = 0;
		$tr->id = NULL;
		$username = $user->username;
		$l03 = DAO::getSingleValue($link, "SELECT l03 FROM tr WHERE username = '{$username}' LIMIT 0,1");
		if($l03!='')
			$tr->l03 = $l03;
		else
			$tr->l03 = substr($user->username,0,12);
		$assessor = DAO::getSingleValue($link, "SELECT id FROM users WHERE CONCAT(users.firstnames, ' ', users.surname) = '" . $row['assessor'] . "'" );
		if($assessor != '')
			$tr->assessor = $assessor;
		$tr->save($link);
		$training_records_created = '<tr class="Data"><td>' . $tr->firstnames . '</td><td>' . $tr->surname . '</td><td>' . $tr->l03 . '</td></tr>';
		$tr_id = $tr->id;
		$framework_id = DAO::getSingleValue($link, "SELECT framework_id FROM courses WHERE id = '{$course_id}'");
		DAO::execute($link, "INSERT INTO courses_tr values('{$course_id}','{$tr_id}','','{$framework_id}')");
		$ok = DAO::getSingleValue($link, "SELECT COUNT(*) FROM student_frameworks WHERE tr_id = '{$tr_id}' AND id = '{$framework_id}'");
		if(empty($ok) || $ok =='')
			DAO::execute($link, "INSERT INTO student_frameworks SELECT title, id, '{$tr_id}', framework_code, comments, duration_in_months FROM frameworks WHERE id = '{$framework_id}';");
		$learnstartdate = $row['learnstartdate'];
		$plannedenddate = $row['plannedenddate'];
		$learnrefnumber = $row['learnrefnumber'];
		$query = <<<HEREDOC
INSERT into
	student_qualifications
select
id,
'$framework_id',
'$tr_id',
framework_qualifications.internaltitle,
lsc_learning_aim,
awarding_body,
title,
description,
assessment_method,
structure,
level,
qualification_type,
accreditation_start_date,
operational_centre_start_date,
accreditation_end_date,
certification_end_date,
dfes_approval_start_date,
dfes_approval_end_date,
evidences,
units,
'0',
'0',
'0',
'0',
'0',
units_required,
proportion,
0,
0,
0,
0,
0,
0,
0,
'$sd',
'$ed',
NULL,
NULL,
units_required,
NULL,
NULL,
NULL,
NULL,
NULL,
'100',
NULL,
'',
'',
''
FROM framework_qualifications
LEFT JOIN course_qualifications_dates ON course_qualifications_dates.qualification_id = framework_qualifications.id and
course_qualifications_dates.framework_id = framework_qualifications.framework_id and
course_qualifications_dates.internaltitle = framework_qualifications.internaltitle
	WHERE framework_qualifications.framework_id = '$framework_id' AND course_qualifications_dates.course_id='$course_id'
	AND replace(framework_qualifications.id,'/','') in (SELECT learnaimref FROM exg WHERE learnrefnumber = '$learnrefnumber' AND learnstartdate >= '$learnstartdate' AND plannedenddate <= '$plannedenddate' AND $this->where_clause);
HEREDOC;
		DAO::execute($link, $query);

		if(DB_NAME=='am_doncaster' || DB_NAME=='am_donc_demo' || DB_NAME=='ams' || DB_NAME=='am_siemens')
		{
			$learnstartdate = $row['learnstartdate'];
			$plannedenddate = $row['plannedenddate'];
			$learnrefnumber = $row['learnrefnumber'];
			$st3 = DAO::query($link, "SELECT * FROM exg WHERE learnrefnumber = '{$learnrefnumber}' AND learnstartdate >= '{$learnstartdate}' AND plannedenddate <= '{$plannedenddate}' AND $this->where_clause;");
			if($st3)
			{
				$index = 0;
				while($row3 = $st3->fetch(PDO::FETCH_ASSOC))
				{
					$index++;
					if($index==1)
					{
						$xml = '<Learner>';
						$xml .= "<LearnRefNumber>" . $row3['learnrefnumber'] . "</LearnRefNumber>";
						if($row3['uln']!='')
							$xml .= "<ULN>" . $row3['uln'] . "</ULN>";
						else
							$xml .= "<ULN>9999999999</ULN>";
						$xml .= "<FamilyName>" . addslashes((string)$row3['surname']) .	"</FamilyName>";
						$xml .= "<GivenNames>" . addslashes((string)$row3['firstnames']) . "</GivenNames>";
						$xml .= "<DateOfBirth>" . $row3['dob'] . "</DateOfBirth>";
						$xml .= "<Ethnicity>" . $row3['ethnicity'] .	"</Ethnicity>";
						$xml .= "<Sex>" . $row3['gender'] . "</Sex>";
						$xml .= "<LLDDHealthProb>" . $row3['healthproblems'] .	"</LLDDHealthProb>";
						$xml .= "<NINumber>" . $row3['ninumber'] . "</NINumber>";
						$xml .= "<Domicile>XF</Domicile>";
						$xml .= "<PriorAttain>" . $row3['priorattain'] .	"</PriorAttain>";
						$xml .= "<Dest>" . $row3['destination'] . "</Dest>";
						//$xml .= "<PlanLearnHours>" . $row['glh'] . "</PlanLearnHours>";
						$xml .= "<LearnerContact><LocType>2</LocType><ContType>1</ContType><PostCode>" . $row3['postcode'] . "</PostCode></LearnerContact>";
						$xml .= "<LearnerContact><LocType>1</LocType><ContType>2</ContType><PostAdd>";
						$xml .= "<AddLine1>" . addslashes((string)$row3['add1']) . "</AddLine1>";
						$xml .= "<AddLine2>" . addslashes((string)$row3['add2']) . "</AddLine2>";
						$xml .= "<AddLine3>" . addslashes((string)$row3['add3']) . "</AddLine3>";
						$xml .= "<AddLine4>" . addslashes((string)$row3['add4']) . "</AddLine4>";
						$xml .= "</PostAdd></LearnerContact>";
						$xml .= "<LearnerContact><LocType>2</LocType><ContType>2</ContType><PostCode>" . $row3['postcode'] . "</PostCode></LearnerContact>";
						$xml .= "<LearnerContact><LocType>3</LocType><ContType>2</ContType><TelNumber>" . $row3['telephone'] . "</TelNumber></LearnerContact>";
						//$xml .= "<LearnerContact><LocType>4</LocType><ContType>2</ContType><Email>" . addslashes((string)$row['email']) . "</Email></LearnerContact>";
						$xml .= "<LLDDandHealthProblem><LLDDType>DS</LLDDType><LLDDCode>" . $row3['disability'] . "</LLDDCode></LLDDandHealthProblem>";
						$xml .= "<LLDDandHealthProblem><LLDDType>LD</LLDDType><LLDDCode>" . $row3['learningdifficulty'] . "</LLDDCode></LLDDandHealthProblem>";
						//$xml .= "<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $row['lsr'] . "</LearnFAMCode></LearnerFAM>";
						$xml .= "<ProviderSpecLearnerMonitoring><ProvSpecLearnMonOccur>A</ProvSpecLearnMonOccur><ProvSpecLearnMon>" . $row3['curriculum'] . "</ProvSpecLearnMon></ProviderSpecLearnerMonitoring>";
						$xml .= "<ProviderSpecLearnerMonitoring><ProvSpecLearnMonOccur>B</ProvSpecLearnMonOccur><ProvSpecLearnMon>" . $row3['curriculum'] . "</ProvSpecLearnMon></ProviderSpecLearnerMonitoring>";
						//$xml .= "<LearnerEmploymentStatus><EmpStat>" . $row['employment_status'] . "</EmpStat><DateEmpStatApp>" . $sta->formatMySQL() . "</DateEmpStatApp><WorkLocPostCode></WorkLocPostCode><EmpId></EmpId><EmploymentStatusMonitoring>" . "<ESMType>EII</ESMType><ESMCode>" . $row['eii'] . "</ESMCode>" . "</EmploymentStatusMonitoring><EmploymentStatusMonitoring><ESMType>LOU</ESMType><ESMCode>" . $row['lou'] . "</ESMCode>" . "</EmploymentStatusMonitoring><EmploymentStatusMonitoring><ESMType>SEI</ESMType><ESMCode>" . $row['sei'] . "</ESMCode></EmploymentStatusMonitoring><EmploymentStatusMonitoring>" . "<ESMType>BSI</ESMType><ESMCode>" . $row['bsi'] . "</ESMCode>" .  "</EmploymentStatusMonitoring>" . "</LearnerEmploymentStatus>";
					}
					if($row3['learnaimref']=='ZPROG001')
						$xml .= "<LearningDelivery><AimType>1</AimType>";
					else
						$xml .= "<LearningDelivery><AimType>3</AimType>";
					$xml .= "<LearnAimRef>" . $row3['learnaimref'] . "</LearnAimRef>";
					$xml .= "<LearnStartDate>" . $row3['learnstartdate'] . "</LearnStartDate>";
					$xml .= "<LearnPlanEndDate>" . $row3['plannedenddate'] . "</LearnPlanEndDate>";
					$xml .= "<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>105</LearnDelFAMCode></LearningDeliveryFAM>";
					$xml .= "<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode></LearnDelFAMCode></LearningDeliveryFAM>";
					$xml .= "<LearningDeliveryFAM><LearnDelFAMType>FFI</LearnDelFAMType><LearnDelFAMCode></LearnDelFAMCode></LearningDeliveryFAM>";
					$xml .= "<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode></LearnDelFAMCode></LearningDeliveryFAM>";
					$xml .= "<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode></LearnDelFAMCode></LearningDeliveryFAM>";
					$xml .= "<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode></LearnDelFAMCode></LearningDeliveryFAM>";
					$xml .= "<ContOrg></ContOrg>";
					$xml .= "<FundModel>35</FundModel>";
					$xml .= "<ProgType>" . $row3['actual_prog_route']  . "</ProgType>";
					$xml .= "<DelLocPostCode></DelLocPostCode>";
					$xml .= "<MainDelMeth></MainDelMeth>";
					$xml .= "<LearnActEndDate>" . $row3['actualenddate'] . "</LearnActEndDate>";
					$xml .= "<CompStatus>" . $row3['compstatus'] . "</CompStatus>";
					$xml .= "<PropFundRemain>100</PropFundRemain>";
					$xml .= "<Outcome>" . $row3['outcome'] . "</Outcome>";
					$xml .= "<WithdrawReason></WithdrawReason>";
					$xml .= "<AchDate>" . $row3['achievementdate'] . "</AchDate>";
					$xml .= "<ActProgRoute></ActProgRoute>";
					$xml .= "</LearningDelivery>";
				}
				$xml .= "</Learner>";
			}
		}
		$co = Contract::loadFromDatabase($link, $contract_id);
		$submission = DAO::getSingleValue($link, "SELECT submission FROM central.lookup_submission_dates WHERE last_submission_date>=CURDATE() AND contract_year = '" . $co->contract_year . "' AND contract_type = '" . $co->funding_body . "' ORDER BY last_submission_date LIMIT 1;");
		if($submission=="")
			$submission = "W13";
		$q = "INSERT INTO ilr (L01,L03, A09, ilr,submission,contract_type,tr_id,is_complete,is_valid,is_approved,is_active,contract_id) values('','{$l03}','0','{$xml}','{$submission}','ER','{$tr_id}','0','0','1','1','{$contract_id}');";
		DAO::execute($link, "INSERT INTO data_import_ilr_queries (username, sql_query) VALUES ('" . $_SESSION['user']->username . "', '" . addslashes((string)$q) . "')");
		return $training_records_created;
	}

	private function  importStudentQuals(PDO $link)
	{
		// Check Training Records
		$st = DAO::query($link, "SELECT DISTINCT learnrefnumber FROM exg WHERE learnaimref = 'ZPROG001' AND $this->where_clause;");
		if($st)
		{
			try
			{
				while($row = $st->fetch(PDO::FETCH_ASSOC))
				{

					$learnrefnumber = $row['learnrefnumber'];



					// Update ILRs
					$ilr1 = DAO::getSingleValue($link,"SELECT CONCAT('<LearnRefNumber>',learnrefnumber,'</LearnRefNumber><ULN>',uln,'</ULN><FamilyName>',surname,'</FamilyName><GivenNames>',CONCAT(firstnames,' ',middlename),
	'</GivenNames><DateOfBirth>',dob,'</DateOfBirth><Ethnicity>',COALESCE(ethnicity,''),'</Ethnicity><LLDDHealthProb>',healthproblems,'</LLDDHealthProb><NINumber>',ninumber,
	'</NINumber><PriorAttain>',priorattain,'</PriorAttain><Dest>',COALESCE(destination,''),'</Dest>') FROM exg
	WHERE learnrefnumber = '{$learnrefnumber}' AND $this->where_clause LIMIT 0,1;
	");

					$ilr2 = DAO::getSingleValue($link, "SELECT GROUP_CONCAT('<LearningDelivery><LearnAimRef>',learnaimref,'</LearnAimRef><AimType>',IF(learnaimref='ZPROG001',1,3),'</AimType><LearnStartDate>',learnstartdate,
	'</LearnStartDate><LearnPlanEndDate>',plannedenddate,'</LearnPlanEndDate><FundModel>35</FundModel><ProgType>',COALESCE(actual_prog_route,''),'</ProgType><FworkCode>',fworkcode,
	'</FworkCode><PwayCode>',app_pathway,'</PwayCode><CompStatus>',COALESCE(compstatus,''),'</CompStatus><LearnActEndDate>',COALESCE(actualenddate,''),'</LearnActEndDate><Outcome>',
	COALESCE(outcome,''),'</Outcome><AchDate>',COALESCE(achievementdate,''),'</AchDate></LearningDelivery>') FROM exg
	WHERE learnrefnumber = '{$learnrefnumber}' AND $this->where_clause;");

					$mainilr = "<Learner>".$ilr1.$ilr2."</Learner>";
					$mainilr = str_replace("'","\'",$mainilr);
//					$q = "UPDATE ilr INNER JOIN tr ON tr.id = ilr.tr_id SET ilr.l03 = tr.l03;";
//					DAO::execute($link, "INSERT INTO data_import_ilr_queries (username, sql_query) VALUES ('" . $_SESSION['user']->username . "', '" . addslashes((string)$q) . "')");
					$q = "UPDATE ilr SET ilr = '$mainilr' WHERE L03='{$learnrefnumber}' AND contract_id in (SELECT id FROM contracts WHERE contract_year = 2014);";
					DAO::execute($link, "INSERT INTO data_import_ilr_queries (username, sql_query) VALUES ('" . $_SESSION['user']->username . "', '" . addslashes((string)$q) . "')");

					DAO::execute($link,"UPDATE student_qualifications
	INNER JOIN tr ON tr.id = student_qualifications.`tr_id`
	INNER JOIN exg ON tr.l03 = exg.`learnrefnumber` AND REPLACE(student_qualifications.id,'/','') = exg.`learnaimref` AND student_qualifications.`start_date` = exg.`learnstartdate`
	SET student_qualifications.actual_end_date = exg.`actualenddate`, student_qualifications.`achievement_date` = exg.`achievementdate`
	WHERE learnrefnumber = '{$learnrefnumber}' AND $this->where_clause;");
					$needed = DAO::getSingleValue($link, "SELECT count(*) FROM exg WHERE learnrefnumber = '{$learnrefnumber}' AND learnaimref!= 'ZPROG001' AND learnaimref NOT IN (SELECT REPLACE(id,'/','') FROM student_qualifications WHERE tr_id IN (SELECT id FROM tr WHERE l03 = '{$learnrefnumber}')) AND $this->where_clause");

					if($needed)
					{
						DAO::execute($link, "INSERT INTO student_qualifications
	SELECT DISTINCT
	framework_qualifications.id,
	framework_qualifications.framework_id,
	tr.id,
	framework_qualifications.internaltitle,
	framework_qualifications.lsc_learning_aim,
	framework_qualifications.awarding_body,
	framework_qualifications.title,
	framework_qualifications.description,
	framework_qualifications.assessment_method,
	framework_qualifications.structure,
	framework_qualifications.LEVEL,
	framework_qualifications.qualification_type,
	framework_qualifications.accreditation_start_date,
	framework_qualifications.operational_centre_start_date,
	framework_qualifications.accreditation_end_date,
	framework_qualifications.certification_end_date,
	framework_qualifications.dfes_approval_start_date,
	framework_qualifications.dfes_approval_end_date,
	framework_qualifications.evidences,
	framework_qualifications.units,'0','0','0','0','0','0',
	framework_qualifications.proportion, '0', NULL, NULL, NULL, '', '',NULL,
	exg.`learnstartdate`,
	exg.`plannedenddate`, NULL, NULL, NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL
	FROM exg
	INNER JOIN tr ON tr.l03 = exg.`learnrefnumber` AND exg.`learnstartdate` >= tr.`start_date` AND exg.`plannedenddate` <= tr.`target_date`
	INNER JOIN courses_tr ON courses_tr.`tr_id` = tr.`id`
	INNER JOIN courses ON courses.id = courses_tr.`course_id`
	INNER JOIN framework_qualifications ON framework_qualifications.`framework_id` = courses.`framework_id` AND REPLACE(framework_qualifications.id,'/','') = exg.`learnaimref`
	LEFT JOIN student_qualifications ON student_qualifications.`tr_id` = tr.id AND REPLACE(student_qualifications.id,'/','') = exg.`learnaimref`
	WHERE exg.`learnrefnumber` = '{$learnrefnumber}' AND student_qualifications.id IS NULL AND $this->where_clause");
					}
				}
			}
			catch(Exception $e)
			{
				// write to errors file
			}
		}

		// Update tr status AND actual end date AND project code
		$updatequery = "UPDATE tr
	INNER JOIN courses_tr ON courses_tr.`tr_id` = tr.`id`
	INNER JOIN courses ON courses.id = courses_tr.`course_id`
	INNER JOIN frameworks ON frameworks.id = courses.`framework_id`
	INNER JOIN exg ON exg.learnrefnumber=tr.l03 AND tr.`start_date` = exg.`learnstartdate` AND tr.`target_date` = exg.`plannedenddate` AND frameworks.`framework_code` = exg.`fworkcode` AND frameworks.`framework_type` = exg.`actual_prog_route`
	SET tr.`closure_date` = exg.`actualenddate`, tr.`status_code` = exg.`compstatus`, tr.upi = exg.curriculum
	WHERE learnaimref = 'ZPROG001' AND $this->where_clause";
		try
		{
			DAO::execute($link, $updatequery);
		}
		catch(Exception $e)
		{
			// write to errors file
		}

		//if(DB_NAME=="am_donc_demo") // initially released ON demo AND now available for all
		{
			// update assessors in tr
			$update_assessors = "UPDATE tr
				INNER JOIN courses_tr ON courses_tr.`tr_id` = tr.`id`
				INNER JOIN courses ON courses.id = courses_tr.`course_id`
				INNER JOIN frameworks ON frameworks.id = courses.`framework_id`
				INNER JOIN exg ON tr.l03 = exg.learnrefnumber AND tr.start_date = exg.learnstartdate AND tr.target_date = exg.plannedenddate AND frameworks.framework_code = exg.fworkcode AND frameworks.framework_type = exg.actual_prog_route
				INNER JOIN users ON exg.assessor = CONCAT(users.firstnames, ' ', users.surname)
				SET tr.assessor = users.id
				WHERE $this->where_clause ";

			try
			{
				DAO::execute($link, $update_assessors);
			}
			catch(Exception $e)
			{
				// write to errors file
			}
		}
		return true;
	}

	private function addLearners(PDO $link)
	{
		$learners_added = array();
		$st = DAO::query($link, "SELECT DISTINCT learnrefnumber FROM exg WHERE learnrefnumber NOT IN (SELECT username FROM users) AND $this->where_clause;");
		if($st)
		{
			try
			{
				while($row = $st->fetch(PDO::FETCH_ASSOC))
				{
					$learnrefnumber = $row['learnrefnumber'];
					$edrs = DAO::getSingleValue($link, "SELECT edrs FROM exg WHERE learnrefnumber = '{$learnrefnumber}' AND $this->where_clause LIMIT 0,1");
					$emp_id = DAO::getSingleValue($link, "SELECT id FROM organisations WHERE edrs = '{$edrs}' LIMIT 0,1");
					$emp_loc_id = DAO::getSingleValue($link, "SELECT id FROM locations WHERE organisations_id = '{$emp_id}' LIMIT 0,1");
					if($emp_id=='' || $emp_loc_id=='')
						throw new Exception("Employer id is missing. Details (learner reference number = " . $learnrefnumber . ", EDRS = " . $edrs . ", employer id = " . $emp_id . " , employer location id = " . $emp_loc_id . ")");
					$sql = "SELECT DISTINCT NULL, learnrefnumber, uln, CONCAT(firstnames,' ',middlename) AS First_Name, surname, '{$emp_id}' AS Emp_ID, '{$emp_loc_id}' AS Emp_Loc_ID, 'password', 1, 1, dob, ninumber, gender, ethnicity, add1, add2, add3, add4,postcode, telephone, mobile, CURDATE() AS Created_Date, 5, nationality, uln FROM exg WHERE learnrefnumber = '{$learnrefnumber}' AND learnrefnumber NOT IN (SELECT username FROM users) AND $this->where_clause  GROUP BY learnrefnumber;";
					$learners = DAO::getResultset($link, $sql, PDO::FETCH_ASSOC);
					foreach($learners AS $learner)
					{
						$l = new User();
						$l->username = $learner['learnrefnumber'];
						$l->uln = $learner['uln'];
						$l->firstnames = $learner['First_Name'];
						$l->surname = $learner['surname'];
						$l->employer_id = $learner['Emp_ID'];
						$l->employer_location_id = $learner['Emp_Loc_ID'];
						$l->password = 'password';
						$l->record_status = 1;
						$l->web_access = 1;
						$l->dob = $learner['dob'];
						$l->ni = $learner['ninumber'];
						$l->gender = $learner['gender'];
						$l->ethnicity = $learner['ethnicity'];
						$l->home_address_line_1 = $learner['add1'];
						$l->home_address_line_2 = $learner['add2'];
						$l->home_address_line_3 = $learner['add3'];
						$l->home_address_line_4 = $learner['add4'];
						$l->home_postcode = $learner['postcode'];
						$l->home_telephone = $learner['telephone'];
						$l->home_mobile = $learner['mobile'];
						$l->created = $learner['Created_Date'];
						$l->type = User::TYPE_LEARNER;
						$l->nationality = $learner['nationality'];
						$l->l45 = $learner['uln'];
						$l->save($link);
						$learners_added[] = '<tr class="Data"><td>' . $l->firstnames . '</td><td>' . $l->surname . '</td><td>' . $l->username . '</td></tr>';
					}
				}
			}
			catch(Exception $e)
			{
				// write to errors file
			}
		}
		return $learners_added;
	}

	private function  updateILRs(PDO $link)
	{
		try
		{
			DAO::execute($link, "DELETE FROM data_import_ilr_queries WHERE sql_query = 'UPDATE ilr INNER JOIN tr ON tr.id = ilr.tr_id SET ilr.l03 = tr.l03;'");
			$is_there_ilr_sql_query = DAO::getSingleValue($link, "SELECT COUNT(*) FROM data_import_ilr_queries WHERE username = '" . $_SESSION['user']->username . "'");
			if($is_there_ilr_sql_query > 0)
			{
				$sql_queries = DAO::getSingleColumn($link, "SELECT sql_query FROM data_import_ilr_queries WHERE username = '" . $_SESSION['user']->username . "'");
				foreach($sql_queries AS $sql)
					DAO::execute($link, $sql);
				DAO::execute($link, 'UPDATE ilr INNER JOIN tr ON tr.id = ilr.tr_id SET ilr.l03 = tr.l03;');
			}
			else
				return true;
		}
		catch(Exception $e)
		{
			// write to errors file
		}
		try
		{
			DAO::execute($link, "DELETE FROM data_import_ilr_queries WHERE username = '" . $_SESSION['user']->username . "'");
		}
		catch(Exception $e)
		{
			// write to errors file
		}
		return true;
	}

	private $ilr_sql_queries = NULL;
}
