<?php
class ajax_process_batch implements IAction
{
	private $where_clause = NULL;

	public function execute(PDO $link)
	{
		set_time_limit(0);
        DAO::execute($link, "SET SESSION group_concat_max_len = 1000000;");
		$time = array_key_exists('time', $_REQUEST)?$_REQUEST['time']:'';
		if($time == '')
			throw new Exception("Missing querystring argument 'time'");
		$this->where_clause = " DATE_FORMAT(upload_time,'%d %M %Y %H:%i:%s') = '$time' and status!='Record Length Error'";
		if(is_null($this->where_clause))
			throw new Exception("Missing querystring argument 'time' for where clause");
		if(DB_NAME=='am_doncaster' || DB_NAME=='am_donc_demo' || DB_NAME=='ams' || DB_NAME=='am_siemens')
		{
			$outputHTML = '';
			DAO::transaction_start($link);
			try
			{
				// verify there are no exponential l03s
				$exponentialL03s = $this->checkExponentialL03($link);
				if(count($exponentialL03s) > 0)
				{
					$outputHTML .= '<h3>Learner with exponential reference numbers in CSV file</h3>';
					$outputHTML .= '<table class="resultset" cellspacing="0" cellpadding="4" width="25%">';
					$outputHTML .= '<caption><strong>Please correct the learner reference numbers for these learners.</strong></caption>';
					$outputHTML .= '<tr><th>L03</th><th>Learner Name</th><th>Date of Birth</th></tr>';
					foreach($exponentialL03s AS $e)
					{
						$outputHTML .= $e ;
					}

					$outputHTML .= '</table>';
					include_once('tpl_import_learner_result.php');
					exit;
				}

				// verify all the qualifications are in the database
				$qualifications_to_be_downloaded = $this->checkQualifications($link);
				if(count($qualifications_to_be_downloaded) > 0)
				{
					$outputHTML .= '<h3>Qualifications Not Found</h3>';
					$outputHTML .= '<table class="resultset" cellspacing="0" cellpadding="4" width="25%">';
					$outputHTML .= '<caption><strong>Following qualifications are not found, please download the qualifications.</strong></caption>';
					$outputHTML .= '<tr><th>Qualification Number</th></tr>';
					foreach($qualifications_to_be_downloaded AS $q)
					{
						$outputHTML .= '<tr class="Data"><td>' . $q . '</td></tr>';
					}
					$outputHTML .= '</table>';
					include_once('tpl_import_learner_result.php');
					exit;
				}

				// verify all the edrs are present in CSV file
				$employers_with_missing_edrs = $this->checkEmployersWithMissingEDRS($link);
				if(count($employers_with_missing_edrs) > 0)
				{
					$outputHTML .= '<h3>Employers with Missing EDRS numbers in CSV file</h3>';
					$outputHTML .= '<table class="resultset" cellspacing="0" cellpadding="4">';
					$outputHTML .= '<caption><strong>Please add the EDRS numbers for following employers.</strong></caption>';
					$outputHTML .= '<tr><th>Employer Name</th></tr>';
					foreach($employers_with_missing_edrs AS $e)
					{
						$outputHTML .= '<tr class="Data"><td>' . $e . '</td></tr>';
					}

					$outputHTML .= '</table>';
					include_once('tpl_import_learner_result.php');
					exit;
				}

				// verify all the assessors are in the database
				$missing_assessors = $this->checkAssessors($link);
				if(count($missing_assessors) > 0)
				{
					$outputHTML .= '<h3>Assessors Not Found</h3>';
					$outputHTML .= '<table class="resultset" cellspacing="0" cellpadding="4" width="25%">';
					$outputHTML .= '<caption><strong>Following assessors are not found, please create these as assessors in Sunesis with the same first and surname.</strong></caption>';
					$outputHTML .= '<tr><th>Assessor </th></tr>';
					foreach($missing_assessors AS $a)
					{
						$outputHTML .= '<tr class="Data"><td>' . $a . '</td></tr>';
					}
					$outputHTML .= '</table>';
					include_once('tpl_import_learner_result.php');
					exit;
				}

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

				// check/add/update frameworks and related records
				$this->addFrameworks($link);
				$outputHTML .= '<h3>Frameworks</h3>';
				$outputHTML .= 'Frameworks are added/updated successfully';

				// add learners
				$learners = $this->addLearners($link);
				if(count($learners) > 0)
				{
					$outputHTML .= '<h3>Learners</h3>';
					$outputHTML .= '<table class="resultset" cellspacing="0" cellpadding="4">';
					$outputHTML .= '<caption><strong>Following new learners are created in Sunesis.</strong></caption>';
					$outputHTML .= '<tr><th>Learner Reference Number</th><th>Learner Name</th></tr>';
					foreach($learners AS $l)
					{
						$outputHTML .= $l;
					}
					$outputHTML .= '</table>';
				}

				// check/add/updated training records
				$this->addUpdateTrainingRecords($link);
				$outputHTML .= '<h3>Training Records</h3>';
				$outputHTML .= 'Training records are added/updated successfully';
				DAO::transaction_commit($link);
			}
			catch(Exception $e)
			{
				DAO::transaction_rollback($link);
				throw new WrappedException($e);
			}

			include_once('tpl_import_learner_result.php');
		}
	}

	/**
	 * @param PDO $link
	 * @return array
	 * @throws Exception
	 */
	private function checkAssessors(PDO $link)
	{
		$missing_assessors = array();
		$assessorCheck = DAO::getSingleValue($link, "SELECT COUNT(*) FROM exg WHERE exg.assessor !='' AND exg.assessor NOT IN (SELECT CONCAT(users.firstnames,' ', users.surname) FROM users) and $this->where_clause;");
		if($assessorCheck >= 1)
		{

			$st = DAO::query($link, "SELECT distinct exg.assessor FROM exg WHERE exg.assessor !='' AND exg.assessor NOT IN (SELECT CONCAT(users.firstnames,' ', users.surname) FROM users) and $this->where_clause;");
			if($st)
			{
				while($row = $st->fetch(PDO::FETCH_ASSOC))
				{

					$missing_assessors[] = $row['assessor'];

				}
			}
			else
			{
				throw new Exception('Query failed in checkAssessors.');
			}

			return $missing_assessors;
		}
	}

	/**
	 * @param PDO $link
	 * @return array
	 * @throws Exception
	 */
	private function checkEmployersWithMissingEDRS(PDO $link)
	{
		$employers_with_missing_edrs = array();
		$edrsCheck = DAO::getSingleValue($link, "SELECT COUNT(*) FROM exg WHERE edrs = '' and $this->where_clause;");

		if($edrsCheck >= 1)
		{

			$st = DAO::query($link, "SELECT distinct employer FROM exg WHERE edrs = '' and $this->where_clause;");
			if($st)
			{
				while($row = $st->fetch(PDO::FETCH_ASSOC))
				{
					$employers_with_missing_edrs[] = $row['employer'];
				}
			}
			else
			{
				throw new Exception('Query failed in checkEmployersWithMissingEDRS.');
			}

			return $employers_with_missing_edrs;
		}
	}

	/**
	 * @param PDO $link
	 * @return array
	 * @throws Exception
	 */
	private function checkQualifications(PDO $link)
	{
		$qualifications_to_be_downloaded = array();
		$qualificationCheck = DAO::getSingleValue($link, "SELECT COUNT(*) FROM exg WHERE learnaimref!='ZPROG001' AND learnaimref NOT IN (SELECT REPLACE(id,'/','') FROM qualifications) and $this->where_clause;");
		if($qualificationCheck >= 1)
		{

			$st = DAO::query($link, "SELECT distinct learnaimref FROM exg WHERE learnaimref!='ZPROG001' AND learnaimref NOT IN (SELECT REPLACE(id,'/','') FROM qualifications) and $this->where_clause;");
			if($st)
			{
				$size = count($st->rowCount());
				while($row = $st->fetch(PDO::FETCH_ASSOC))
				{

					$qualifications_to_be_downloaded[] = $row['learnaimref'];

				}
			}
			else
			{
				throw new Exception('Query failed in checkQualifications.');
			}

			return $qualifications_to_be_downloaded;
		}
	}

	/**
	 * @param PDO $link
	 * @return array
	 * @throws Exception
	 */
	private function checkExponentialL03(PDO $link)
	{
		$exponential_l03 = array();
		$exponentialL03Check = DAO::getSingleValue($link, "SELECT COUNT(*) FROM exg WHERE learnrefnumber LIKE '%E+%' and $this->where_clause;");
		if($exponentialL03Check >= 1)
		{

			$st = DAO::query($link, "SELECT * FROM exg WHERE learnrefnumber LIKE '%E+%' and $this->where_clause  group by firstnames, surname, dob;");
			if($st)
			{
				while($row = $st->fetch(PDO::FETCH_ASSOC))
				{
					$exponential_l03[] = '<tr class="Data"><td>' . $row['learnrefnumber'] . '</td><td>' . $row['firstnames'] . ' ' . $row['middlename'] . ' ' . $row['surname'] .  '</td><td>' . $row['dob'] . '</td></tr>';
				}
			}
			else
			{
				throw new Exception('Query failed in checkExponentialL03.');
			}

			return $exponential_l03;
		}
	}

	/**
	 * @param PDO $link
	 * @return array
	 * @throws Exception
	 */
	private function addEmployers(PDO $link)
	{
		$employers_created = array();
		$newemployers = DAO::getSingleValue($link, "SELECT count(DISTINCT edrs) FROM exg WHERE edrs IS NOT NULL AND edrs!='' AND edrs NOT IN (SELECT edrs FROM organisations WHERE edrs IS NOT NULL) and $this->where_clause;");
		if($newemployers > 0)
		{

			$st = DAO::query($link, "SELECT DISTINCT edrs FROM exg WHERE edrs IS NOT NULL AND edrs!='' AND edrs NOT IN (SELECT edrs FROM organisations WHERE edrs IS NOT NULL) and $this->where_clause;");
			if($st)
			{

				while($row = $st->fetch(PDO::FETCH_ASSOC))
				{

					$e = new Employer();
					$edrs = $row['edrs'];
					$e->edrs = $edrs;
					$e->legal_name = DAO::getSingleValue($link, "select employer from exg where edrs = '$edrs' and $this->where_clause limit 0,1");
					$e->trading_name = DAO::getSingleValue($link, "select employer from exg where edrs = '$edrs' and $this->where_clause limit 0,1");
					$e->active = 1;
					$e->save($link);
					$found = $e->id;

					$loc = new Location();
					$loc->short_name = "Main Site";
					$loc->full_name = "Main Site";
					$loc->organisations_id = $found;
					$loc->address_line_1 = DAO::getSingleValue($link, "select empadd1 from exg where edrs = '$edrs' and $this->where_clause limit 0,1");
					$loc->address_line_2 = DAO::getSingleValue($link, "select empadd2 from exg where edrs = '$edrs' and $this->where_clause limit 0,1");
					$loc->address_line_3 = DAO::getSingleValue($link, "select empadd3 from exg where edrs = '$edrs' and $this->where_clause limit 0,1");
					$loc->address_line_4 = DAO::getSingleValue($link, "select empadd4 from exg where edrs = '$edrs' and $this->where_clause limit 0,1");
					$loc->postcode = DAO::getSingleValue($link, "select emppostcode from exg where edrs = '$edrs' and $this->where_clause limit 0,1");
					$loc->is_legal_address = 1;
					$loc->telephone = DAO::getSingleValue($link, "select emptel from exg where edrs = '$edrs' and $this->where_clause limit 0,1");
					$loc->contact_name = DAO::getSingleValue($link, "select contactname from exg where edrs = '$edrs' and $this->where_clause limit 0,1");
					$loc->save($link);
					$loc_id = $loc->id;
					$lastvisit = DAO::getSingleValue($link, "select lastvisit from exg where edrs = '$edrs' and $this->where_clause order by lastvisit desc limit 0,1");
					if($lastvisit!='')
						$lastvisit = "'" . Date::toMySQL($lastvisit) . "'";
					else
						$lastvisit = "NULL";
					$hsexpiry = DAO::getSingleValue($link, "select hsexpiry from exg where edrs = '$edrs' and $this->where_clause order by hsexpiry desc limit 0,1");
					if($hsexpiry!='')
						$hsexpiry = "'" . Date::toMySQL($hsexpiry) . "'";
					else
						$hsexpiry = "NULL";
					DAO::execute($link, "insert into health_safety values($loc_id,$lastvisit,$hsexpiry,'','',1,1,1,NULL,'')");
					$employers_created[] = '<tr class="Data"><td>' . $e->edrs . '</td><td>' . $e->legal_name . '</td><td>' . $loc->postcode . '</td></tr>';

				}
			}
			else
			{
				throw new Exception("Query failed in addEmployers.");
			}
			return $employers_created;
		}
	}

	/**
	 * @param PDO $link
	 * @return array
	 * @throws Exception
	 */
	private function updateEmployers(PDO $link)
	{
		$employers_updated = array();
		$st = DAO::query($link, "SELECT DISTINCT edrs,employer,empadd1,empadd2,empadd3,empadd4,emppostcode,emptel,contactname,hsexpiry,lastvisit FROM exg WHERE edrs IS NOT NULL AND edrs!='' AND edrs IN (SELECT edrs FROM organisations WHERE edrs IS NOT NULL) and $this->where_clause;");
		if($st)
		{

			while($row = $st->fetch(PDO::FETCH_ASSOC))
			{

				$edrs = $row['edrs'];
				$id = DAO::getSingleValue($link, "select id from organisations where edrs = '$edrs' limit 0,1");
				$e = Employer::loadFromDatabase($link, $id);
				$e->legal_name = $row['employer'];
				$e->trading_name = $row['employer'];
				$e->active = 1;
				$e->save($link);
				$found = $e->id;

				$loc_id = DAO::getSingleValue($link, "select id from locations where organisations_id = '$found' limit 0,1");
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
					$loc->address_line_1 = DAO::getSingleValue($link, "select empadd1 from exg where edrs = '$edrs' and $this->where_clause limit 0,1");
					$loc->address_line_2 = DAO::getSingleValue($link, "select empadd2 from exg where edrs = '$edrs' and $this->where_clause limit 0,1");
					$loc->address_line_3 = DAO::getSingleValue($link, "select empadd3 from exg where edrs = '$edrs' and $this->where_clause limit 0,1");
					$loc->address_line_4 = DAO::getSingleValue($link, "select empadd4 from exg where edrs = '$edrs' and $this->where_clause limit 0,1");
					$loc->postcode = DAO::getSingleValue($link, "select emppostcode from exg where edrs = '$edrs' and $this->where_clause limit 0,1");
					$loc->is_legal_address = 1;
					$loc->telephone = DAO::getSingleValue($link, "select emptel from exg where edrs = '$edrs' and $this->where_clause limit 0,1");
					$loc->contact_name = DAO::getSingleValue($link, "select contactname from exg where edrs = '$edrs' and $this->where_clause limit 0,1");
					$loc->save($link);
					$loc_id = $loc->id;
					$lastvisit = DAO::getSingleValue($link, "select lastvisit from exg where edrs = '$edrs' and $this->where_clause order by lastvisit desc limit 0,1");
					if($lastvisit!='')
						$lastvisit = "'" . Date::toMySQL($lastvisit) . "'";
					else
						$lastvisit = "NULL";
					$hsexpiry = DAO::getSingleValue($link, "select hsexpiry from exg where edrs = '$edrs' and $this->where_clause order by hsexpiry desc limit 0,1");
					if($hsexpiry!='')
						$hsexpiry = "'" . Date::toMySQL($hsexpiry) . "'";
					else
						$hsexpiry = "NULL";
					DAO::execute($link, "insert into health_safety values($loc_id,$lastvisit,$hsexpiry,'','',1,1,1,NULL,'')");
				}
				$employers_updated[] = '<tr class="Data"><td>' . $e->edrs . '</td><td>' . $e->legal_name . '</td><td>' . $loc->postcode . '</td></tr>';
			}
		}
		return $employers_updated;
	}

	/**
	 * @param PDO $link
	 * @return array
	 * @throws Exception
	 */
	private function addFrameworks(PDO $link)
	{
		$p_org = DAO::getSingleValue($link, "SELECT id FROM organisations WHERE organisation_type = 3 LIMIT 0,1");
		if(DB_NAME=="am_doncaster" || DB_NAME == "am_donc_demo")
			//$sql = "SELECT DISTINCT CONCAT(fworkcode,' - ',lad.FRAMEWORK_DESC) AS Framework_Title, NULL, fworkcode, NULL, 18, 1750,1, NULL, actual_prog_route FROM exg INNER JOIN lad201314.`frameworks` AS lad ON lad.`FRAMEWORK_CODE` = exg.`fworkcode` AND lad.`FRAMEWORK_TYPE_CODE` = exg.`actual_prog_route` AND lad.`FRAMEWORK_PATHWAY_CODE` = exg.`app_pathway` WHERE CONCAT(fworkcode,'*',actual_prog_route) NOT IN (SELECT CONCAT(framework_code,'*',framework_type) FROM frameworks WHERE framework_code IS NOT NULL);";
			$sql = "SELECT DISTINCT CONCAT(lars_frameworks.FworkCode, ' - ', lars_frameworks.IssuingAuthorityTitle) AS Framework_Title, NULL, exg.fworkcode, NULL, 18, 1750, 1, NULL, actual_prog_route FROM exg INNER JOIN lars201415.`Core_LARS_Framework` AS lars_frameworks ON lars_frameworks.FworkCode = exg.`fworkcode` AND lars_frameworks.ProgType = exg.`actual_prog_route` AND lars_frameworks.PwayCode = exg.`app_pathway` WHERE CONCAT(exg.fworkcode,'*',actual_prog_route) NOT IN (SELECT CONCAT(framework_code,'*',framework_type) FROM frameworks WHERE framework_code IS NOT NULL); ";
		elseif(DB_NAME=="am_siemens")
			$sql = "SELECT DISTINCT CONCAT(fworkcode,' - ',lad.FRAMEWORK_DESC) AS Framework_Title, NULL, fworkcode, NULL, 18, 1658,1, NULL, actual_prog_route FROM exg INNER JOIN lad201314.`frameworks` AS lad ON lad.`FRAMEWORK_CODE` = exg.`fworkcode` AND lad.`FRAMEWORK_TYPE_CODE` = exg.`actual_prog_route` AND lad.`FRAMEWORK_PATHWAY_CODE` = exg.`app_pathway` WHERE CONCAT(fworkcode,'*',actual_prog_route) NOT IN (SELECT CONCAT(framework_code,'*',framework_type) FROM frameworks WHERE framework_code IS NOT NULL);";
		else
			$sql = "SELECT DISTINCT CONCAT(fworkcode,' - ',lad.FRAMEWORK_DESC) AS Framework_Title, NULL, fworkcode, NULL, 18, $p_org,1, NULL, actual_prog_route FROM exg INNER JOIN lad201314.`frameworks` AS lad ON lad.`FRAMEWORK_CODE` = exg.`fworkcode` AND lad.`FRAMEWORK_TYPE_CODE` = exg.`actual_prog_route` AND lad.`FRAMEWORK_PATHWAY_CODE` = exg.`app_pathway` WHERE CONCAT(fworkcode,'*',actual_prog_route) NOT IN (SELECT CONCAT(framework_code,'*',framework_type) FROM frameworks WHERE framework_code IS NOT NULL);";
		$frameworks_to_be_added = DAO::getResultset($link, $sql, PDO::FETCH_ASSOC);
		foreach($frameworks_to_be_added AS $framework)
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
		}

		$st = DAO::query($link, "SELECT DISTINCT fworkcode, actual_prog_route FROM exg where $this->where_clause;");
		if($st)
		{
			while($row = $st->fetch(PDO::FETCH_ASSOC))
			{

				$fworkcode = $row['fworkcode'];
				$progtype = $row['actual_prog_route'];
				DAO::execute($link, "insert IGNORE into framework_qualifications SELECT DISTINCT qualifications.id, lsc_learning_aim, awarding_body, qualifications.title, description, assessment_method, structure, `level`, qualification_type,
	qualifications.`operational_start_date`, `dfes_approval_start_date`, certification_end_date , certification_end_date, dfes_approval_start_date, dfes_approval_end_date,
	frameworks.id, evidences, units, internaltitle, 10, frameworks.`duration_in_months`, units_required, mandatory_units, 0 FROM qualifications
	INNER JOIN exg ON exg.`learnaimref` = REPLACE(qualifications.id,'/','')
	INNER JOIN frameworks ON exg.`fworkcode` = frameworks.`framework_code` AND exg.`actual_prog_route` = frameworks.`framework_type`
	WHERE fworkcode='$fworkcode' AND actual_prog_route = '$progtype' AND learnaimref!='ZPROG001';");

			}
		}
		$st = DAO::query($link, "SELECT * from frameworks;");
		if($st)
		{

			while($row = $st->fetch(PDO::FETCH_ASSOC))
			{

				$fid = $row['id'];
				$course_id = DAO::getSingleValue($link, "select id from courses where framework_id = $fid order by id desc limit 0,1");
				if($course_id=='')
				{
					$f = Framework::loadFromDatabase($link, $fid);
					// and create course too
					$course = new Course($link);
					$course->id = NULL;
					$course->organisations_id = DAO::getSingleValue($link, "select id from organisations where organisation_type = 3 limit 0,1");
					$course->title = $f->title;
					$course->framework_id = $f->id;
					$course->programme_type = 2;
					$course->active = 1;
					$course->course_start_date = '2013-01-01';
					$course->course_end_date = '2020-12-31';
					$course->save($link);
					$course_id = $course->id;
					DAO::execute($link, "INSERT IGNORE INTO course_qualifications_dates
	(SELECT framework_qualifications.id, framework_qualifications.framework_id, internaltitle, '$course_id', courses.course_start_date, DATE_ADD(courses.course_start_date,
	INTERVAL framework_qualifications.duration_in_months MONTH), '0', '0', '0'
	FROM framework_qualifications
	LEFT JOIN courses ON courses.framework_id = framework_qualifications.`framework_id`
	WHERE framework_qualifications.framework_id = '$fid');");
				}

			}
		}
		return true;
	}


	private function addLearners(PDO $link)
	{
		$learners_added = array();
		$st = DAO::query($link, "SELECT distinct learnrefnumber FROM exg WHERE learnrefnumber NOT IN (SELECT username FROM users) and $this->where_clause;");
		if($st)
		{

			while($row = $st->fetch(PDO::FETCH_ASSOC))
			{

				$learnrefnumber = $row['learnrefnumber'];
				$edrs = DAO::getSingleValue($link, "select edrs from exg where learnrefnumber = '$learnrefnumber' AND $this->where_clause limit 0,1");
				$emp_id = DAO::getSingleValue($link, "select id from organisations where edrs = '$edrs' limit 0,1");
				$emp_loc_id = DAO::getSingleValue($link, "select id from locations where organisations_id = '$emp_id' limit 0,1");
				if($emp_id=='' || $emp_loc_id=='')
					throw new Exception("Employer id is missing. Details (learner reference number = " . $learnrefnumber . ", EDRS = " . $edrs . ", employer id = " . $emp_id . " , employer location id = " . $emp_loc_id . ")");
				$sql = "SELECT DISTINCT NULL, learnrefnumber, uln, CONCAT(firstnames,' ',middlename) AS First_Name, surname, '$emp_id' AS Emp_ID, '$emp_loc_id' AS Emp_Loc_ID, 'password', 1, 1, dob, ninumber, gender, ethnicity, add1, add2, add3, add4,postcode, telephone, mobile, CURDATE() AS Created_Date, 5, nationality, uln FROM exg WHERE learnrefnumber = '$learnrefnumber' AND learnrefnumber NOT IN (SELECT username FROM users) and $this->where_clause  GROUP BY learnrefnumber;";
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
					$learners_added[] = '<tr class="Data"><td>' . $l->username . '</td><td>' . $l->firstnames . ' ' . $l->surname . '</td></tr>';
				}
			}
		}
		return $learners_added;
	}

	private function addUpdateTrainingRecords(PDO $link)
	{
		$p_org = DAO::getSingleValue($link, "SELECT id FROM organisations WHERE organisation_type = 3 LIMIT 0,1");
		// Check Training Records
		$st = DAO::query($link, "SELECT * FROM exg WHERE learnaimref = 'ZPROG001' AND $this->where_clause;");
		if($st)
		{

			$rec = Array();
			while($row = $st->fetch(PDO::FETCH_ASSOC))
			{

				$learnstartdate = $row['learnstartdate'];
				$plannedenddate = $row['plannedenddate'];
				$learnrefnumber = $row['learnrefnumber'];
				$exists = DAO::getSingleValue($link, "SELECT count(*) FROM tr WHERE l03 = '$learnrefnumber' AND start_date='$learnstartdate' AND target_date='$plannedenddate' ");
				if(!$exists)
				{
					$learnrefnumber = $row['learnrefnumber'];
					$contract_id = DAO::getSingleValue($link, "select id from contracts where contract_year = 2014");
					$fworkcode = $row['fworkcode'];
					$programmetype = $row['actual_prog_route'];
					if(DB_NAME=="am_doncaster" || DB_NAME == "am_donc_demo")
						$provider_id = 1750;
					elseif(DB_NAME=="am_siemens")
						$provider_id = 1658;
					else
						$provider_id = $p_org;
					$course_id = DAO::getSingleValue($link, "SELECT courses.id FROM courses INNER JOIN frameworks ON frameworks.id = courses.`framework_id` WHERE frameworks.`framework_code` = '$fworkcode' AND frameworks.`framework_type` = '$programmetype';");
					$user = User::loadFromDatabase($link, $learnrefnumber);
					if($learnrefnumber!='' && $contract_id!='' && $course_id!='')
					{
						$this->enrolLearner($link,$row,$user,$contract_id,$provider_id,$course_id,$this->where_clause);
					}
				}
				else
				{

                    // Update ILRs
                    $ilr1 = DAO::getSingleValue($link,"SELECT CONCAT('<LearnRefNumber>',learnrefnumber,'</LearnRefNumber><ULN>',uln,'</ULN><FamilyName>',surname,'</FamilyName><GivenNames>',CONCAT(firstnames,' ',middlename),
'</GivenNames><DateOfBirth>',dob,'</DateOfBirth><Ethnicity>',COALESCE(ethnicity,''),'</Ethnicity><LLDDHealthProb>',healthproblems,'</LLDDHealthProb><NINumber>',ninumber,
'</NINumber><PriorAttain>',priorattain,'</PriorAttain><Dest>',COALESCE(destination,''),'</Dest>') FROM exg
WHERE learnrefnumber = '$learnrefnumber' AND $this->where_clause limit 0,1;
");

$ilr2 = DAO::getSingleValue($link, "SELECT GROUP_CONCAT('<LearningDelivery><LearnAimRef>',learnaimref,'</LearnAimRef><AimType>',IF(learnaimref='ZPROG001',1,3),'</AimType><LearnStartDate>',learnstartdate,
'</LearnStartDate><LearnPlanEndDate>',plannedenddate,'</LearnPlanEndDate><FundModel>35</FundModel><ProgType>',COALESCE(actual_prog_route,''),'</ProgType><FworkCode>',fworkcode,
'</FworkCode><PwayCode>',app_pathway,'</PwayCode><CompStatus>',COALESCE(compstatus,''),'</CompStatus><LearnActEndDate>',COALESCE(actualenddate,''),'</LearnActEndDate><Outcome>',
COALESCE(outcome,''),'</Outcome><AchDate>',COALESCE(achievementdate,''),'</AchDate></LearningDelivery>') FROM exg
WHERE learnrefnumber = '$learnrefnumber' AND $this->where_clause;");

                    $mainilr = "<Learner>".$ilr1.$ilr2."</Learner>";
                    $mainilr = str_replace("'","\'",$mainilr);
                    DAO::execute($link,"update ilr inner join tr on tr.id = ilr.tr_id set ilr.l03 = tr.l03");
                    DAO::execute($link,"update ilr set ilr = '$mainilr' where L03='$learnrefnumber' and contract_id in (select id from contracts where contract_year = 2014)");

                    DAO::execute($link,"UPDATE student_qualifications
INNER JOIN tr ON tr.id = student_qualifications.`tr_id`
INNER JOIN exg ON tr.l03 = exg.`learnrefnumber` AND REPLACE(student_qualifications.id,'/','') = exg.`learnaimref` AND student_qualifications.`start_date` = exg.`learnstartdate`
SET student_qualifications.actual_end_date = exg.`actualenddate`, student_qualifications.`achievement_date` = exg.`achievementdate`
WHERE learnrefnumber = '$learnrefnumber' AND $this->where_clause;");


					$needed = DAO::getSingleValue($link, "SELECT * FROM exg WHERE learnrefnumber = '$learnrefnumber' AND learnaimref!= 'ZPROG001' and learnaimref NOT IN (SELECT REPLACE(id,'/','') FROM student_qualifications WHERE tr_id IN (SELECT id FROM tr WHERE l03 = '$learnrefnumber')) AND $this->where_clause");
					//
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
WHERE exg.`learnrefnumber` = '$learnrefnumber' AND student_qualifications.id IS NULL AND $this->where_clause");
					}
				}
			}
		}

		// Update tr status and actual end date and project code
		$updatequery = "UPDATE tr
	INNER JOIN courses_tr ON courses_tr.`tr_id` = tr.`id`
	INNER JOIN courses ON courses.id = courses_tr.`course_id`
	INNER JOIN frameworks ON frameworks.id = courses.`framework_id`
	INNER JOIN exg ON exg.learnrefnumber=tr.l03 AND tr.`start_date` = exg.`learnstartdate` AND tr.`target_date` = exg.`plannedenddate` AND frameworks.`framework_code` = exg.`fworkcode` and frameworks.`framework_type` = exg.`actual_prog_route`
	SET tr.`closure_date` = exg.`actualenddate`, tr.`status_code` = exg.`compstatus`, tr.upi = exg.curriculum
	WHERE learnaimref = 'ZPROG001' AND $this->where_clause";
		DAO::execute($link, $updatequery);

		//if(DB_NAME=="am_donc_demo") // initially released on demo and now available for all
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
				throw new Exception('Assessors update query failed. ' . $e);
			}
		}
	}

	public function createLearner($link, $row, $employer_id)
	{
		$user = new User();
		$l03 = (int)DAO::getSingleValue($link, "select max(l03) from tr where l03 + 0 <> 0 AND LENGTH(RTRIM(l03))=12");
		$l03 += 1;
		$username = str_pad($l03,12,'0',STR_PAD_LEFT);
		$user->username = $username;
		$user->password = "password";
		$user->record_status = 1;
		$user->web_access = 1;
		$user->type = 5;
		$user->surname = $row['surname'];
		$user->firstnames = $row['firstnames'];
		$user->employer_id = $employer_id;
		$user->employer_location_id = DAO::getSingleValue($link, "select id from locations where organisations_id = '$employer_id'");
		$user->job_role = $row['job_role'];
		$user->gender = $row['gender'];
		$user->ethnicity = $row['ethnicity'];
		$user->dob = $row['dob'];
		$user->enrollment_no = $row['enrolment'];
		$user->ni = $row['ni'];
		$user->uln = $row['uln'];
		$user->l45 = $row['uln'];
		$user->bennett_test = $row['diagnostic'];
		$user->numeracy = $row['numeracy'];
		$user->literacy = $row['literacy'];
		$user->esol = $row['esol'];
		$user->l39 = $row['destination'];
		$user->home_address_line_1 = $row['add1'];
		$user->home_address_line_2 = $row['add2'];
		$user->home_address_line_3 = $row['add3'];
		$user->home_address_line_4 = $row['add4'];
		$user->home_postcode = $row['postcode'];
		$user->home_telephone = $row['telephone'];
		$user->home_mobile = $row['mobile'];
		$user->home_fax = $row['fax'];
		$user->home_email = $row['email'];
		$user->save($link, true);
		return $user;
	}

	public function createLearnerNoExg($link, $row, $employer_id)
	{
		$user = new User();
//		$l03 = (int)DAO::getSingleValue($link, "select max(l03) from tr where l03 + 0 <> 0 AND LENGTH(RTRIM(l03))=12");
//		$l03 += 1;
//		$username = str_pad($l03,12,'0',STR_PAD_LEFT);
		$username = $row['ni'];
		$user->username = $username;
		$user->password = "password";
		$user->record_status = 1;
		$user->web_access = 1;
		$user->type = 5;
		$user->surname = $row['surname'];
		$user->firstnames = $row['firstnames'];
		$user->employer_id = $employer_id;
		$user->job_role = $row['job_role'];
		$user->gender = $row['gender'];
		$user->ethnicity = $row['ethnicity'];
		$user->dob = $row['dob'];
		$user->enrollment_no = $row['enrolment'];
		$user->ni = $row['ni'];
		$user->uln = $row['uln'];
		$user->l45 = $row['uln'];
		$user->bennett_test = $row['diagnostic'];
		$user->numeracy = $row['numeracy'];
		$user->literacy = $row['literacy'];
		$user->esol = $row['esol'];
		$user->l39 = 95;
		$user->home_address_line_1 = $row['add1'];
		$user->home_address_line_2 = $row['add2'];
		$user->home_address_line_3 = $row['add3'];
		$user->home_address_line_4 = $row['add4'];
		$user->home_postcode = $row['postcode'];
		$user->home_telephone = $row['telephone'];
		$user->home_mobile = $row['mobile'];
		$user->home_fax = $row['fax'];
		$user->home_email = $row['email'];
		$user->save($link, true);
		return $user;
	}


	public function enrolLearner($link,$row,$user,$contract_id,$provider_id,$course_id, $where_clause='')
	{
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
		$location_id = DAO::getSingleValue($link, "select id from locations where organisations_id='$provider_id'");
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
		$l03 = DAO::getSingleValue($link, "select l03 from tr where username = '$username' limit 0,1");
		if($l03!='')
			$tr->l03 = $l03;
		else
			$tr->l03 = substr($user->username,0,12);
		$assessor = DAO::getSingleValue($link, "SELECT id FROM users WHERE CONCAT(users.firstnames, ' ', users.surname) = '" . $row['assessor'] . "'" );
		if($assessor != '')
			$tr->assessor = $assessor;
		$tr->save($link);
		$tr_id = $tr->id;
		$framework_id = DAO::getSingleValue($link, "select framework_id from courses where id = '$course_id'");
		DAO::execute($link, "insert into courses_tr values('$course_id','$tr_id','','$framework_id')");
		DAO::execute($link, "insert into student_frameworks select title, id, '$tr_id', framework_code, comments, duration_in_months from frameworks where id = '$framework_id';");
		$learnstartdate = $row['learnstartdate'];
		$plannedenddate = $row['plannedenddate'];
		$learnrefnumber = $row['learnrefnumber'];
		$query = <<<HEREDOC
insert into
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
from framework_qualifications
LEFT JOIN course_qualifications_dates on course_qualifications_dates.qualification_id = framework_qualifications.id and
course_qualifications_dates.framework_id = framework_qualifications.framework_id and
course_qualifications_dates.internaltitle = framework_qualifications.internaltitle
	where framework_qualifications.framework_id = '$framework_id' and course_qualifications_dates.course_id='$course_id'
	and replace(framework_qualifications.id,'/','') in (select learnaimref from exg where learnrefnumber = '$learnrefnumber' and learnstartdate >= '$learnstartdate' and plannedenddate <= '$plannedenddate' and $this->where_clause);
HEREDOC;
		DAO::execute($link, $query);

		if(DB_NAME=='am_doncaster' || DB_NAME=='am_donc_demo' || DB_NAME=='ams' || DB_NAME=='am_siemens')
		{
			$learnstartdate = $row['learnstartdate'];
			$plannedenddate = $row['plannedenddate'];
			$learnrefnumber = $row['learnrefnumber'];
			$st3 = DAO::query($link, "SELECT * FROM exg WHERE learnrefnumber = '$learnrefnumber' AND learnstartdate>='$learnstartdate' AND plannedenddate<='$plannedenddate' AND $this->where_clause;");
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
						$sta = new Date($sd);
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
		$submission = DAO::getSingleValue($link, "select submission from central.lookup_submission_dates where last_submission_date>=CURDATE() and contract_year = '$co->contract_year' and contract_type = '$co->funding_body' order by last_submission_date LIMIT 1;");
		if($submission=="")
			$submission = "W13";
		$q = "insert into ilr (L01,L03, A09, ilr,submission,contract_type,tr_id,is_complete,is_valid,is_approved,is_active,contract_id) values('','$l03','0','$xml','$submission','ER','$tr_id','0','0','1','1','$contract_id');";
		DAO::execute($link, $q);
		return true;
	}
}
?>