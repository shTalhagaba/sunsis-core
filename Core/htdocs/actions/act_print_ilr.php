<?php

class print_ilr implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		$contract = isset($_GET['contract']) ? $_GET['contract'] : '';

		if ($id == '' || !is_numeric($id)) {
			throw new Exception("You must specify a numeric id in the querystring");
		}

		$que = "select max(meeting_date) from assessor_review where tr_id='$id' order by tr_id";
		$review_date = trim((string) DAO::getSingleValue($link, $que));

		$que = "select comments from assessor_review where tr_id='$id' and meeting_date = (select max(meeting_date) from assessor_review where tr_id='$id') order by tr_id;";
		$last_review_status = trim((string) DAO::getSingleValue($link, $que));

		$que = "select value from configuration where entity='workplace'";
		$workplace = DAO::getSingleValue($link, $que);

		$que = "select id from student_frameworks where tr_id='$id'";
		$framework_id = trim((string) DAO::getSingleValue($link, $que));

		$que = "select start_date from tr where id='$id'";
		$course_start_date = trim((string) DAO::getSingleValue($link, $que));

		$que = "select target_date from tr where id='$id'";
		$course_end_date = trim((string) DAO::getSingleValue($link, $que));

		$que = "select DATEDIFF(target_date,start_date) from tr where id='$id'";
		$no_of_days_in_course = trim((string) DAO::getSingleValue($link, $que));

		$que = "select DATEDIFF(NOW(), start_date) from tr where id='$id'";
		$days_passed_since_course_started = trim((string) DAO::getSingleValue($link, $que));

		$que = "select courses.title from courses LEFT JOIN courses_tr on courses_tr.course_id = courses.id where tr_id='$id'";
		$course_title = trim((string) DAO::getSingleValue($link, $que));

		$fsd = new Date($course_start_date);
		$fed = new Date($course_end_date);

		$coursestamp = $fed->getDate() - $fsd->getDate();
		$currentstamp = time() - $fsd->getDate();

		$days_between_course_start_date_and_end_date = (($coursestamp / 60) / 60) / 24;
		$days_between_course_start_date_and_today = (($currentstamp / 60) / 60) / 24;

		//$months_in_course = round($days_between_course_start_date_and_end_date / 30,0);

		$que = "select duration_in_months from frameworks where id=$framework_id";
		$months_in_course = trim((string) DAO::getSingleValue($link, $que));

		$months_passed_float = (round($days_between_course_start_date_and_today / 30, 2));

		$months_passed = floor($days_between_course_start_date_and_today / 30);

		$months_passed = ($months_passed < 0) ? 0 : $months_passed;

		if ($days_between_course_start_date_and_end_date > 0)
			$percentcoursepassed = $days_between_course_start_date_and_today / $days_between_course_start_date_and_end_date * 100;

		$que = "select sum(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)/(SELECT SUM(proportion) FROM student_qualifications AS sq WHERE sq.tr_id = student_qualifications.tr_id AND aptitude!=1 GROUP BY student_qualifications.tr_id)*proportion) from student_qualifications where tr_id='$id' and framework_id='$framework_id'";
		$achieved = trim((string) DAO::getSingleValue($link, $que));

		$pot_vo = TrainingRecord::loadFromDatabase($link, $id); /* @var $pot_vo TrainingRecord */

		$provider = Organisation::loadFromDatabase($link, $pot_vo->provider_id);

		$employer = Organisation::loadFromDatabase($link, $pot_vo->employer_id);

		$isSafeToDelete = $pot_vo->isSafeToDelete($link);

		$acl = ACL::loadFromDatabase($link, 'trainingrecord', $id); /* @var $acl ACL */

		// Check authorisation
		/*		if(!($acl->isAuthorised($_SESSION['user'], 'read') || $acl->isAuthorised($_SESSION['user'], 'write') || (($_SESSION['user']->employer_id==$pot_vo->employer_id || $pot_vo->provider_id==$_SESSION['user']->employer_id) && $_SESSION['user']->isOrgAdmin())))
		{
			throw new UnauthorizedException();
		}
*/
		$que = "SELECT description from dropdown0708 where code='L12' and value = '{$pot_vo->ethnicity}'";
		$ethnicity = trim((string) DAO::getSingleValue($link, $que));

		$submissions = DAO::getResultset($link, "SELECT concat(submission,':',contract_id,'|',tr_id, '*', contracts.contract_year), concat(contracts.title, ' ', submission) FROM ilr INNER JOIN contracts on contracts.id = ilr.contract_id where tr_id='$id';");

		//$que = "SELECT legal_name from organisations where id = $pot_vo->provider_id";
		//$provider_legal_name = trim((string) DAO::getSingleValue($link, $que));
		$provider_legal_name = '';

		//$que = "SELECT full_name from locations where id = $pot_vo->provider_location_id";
		//$provider_location= trim((string) DAO::getSingleValue($link, $que));
		$provider_location = '';

		$que = "select sum(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)/(SELECT SUM(proportion) FROM student_qualifications AS sq WHERE sq.tr_id = student_qualifications.tr_id AND aptitude!=1 GROUP BY student_qualifications.tr_id)*proportion) from student_qualifications where tr_id='$id' and framework_id='$framework_id'";
		$framework_percentage = trim((string) DAO::getSingleValue($link, $que));

		$que = "select title from student_frameworks where tr_id='$id'";
		$framework_title = trim((string) DAO::getSingleValue($link, $que));

		$showPanel = array_key_exists('_showPanel', $_REQUEST) ? $_REQUEST['_showPanel'] : '0'; // Default to 1 lesson

		// Calculate target against every training record
		$tr_id = $id;
		$que = "select DATE_FORMAT(start_date,'%m') from tr where id='$tr_id'";
		$study_start_month = (int)trim((string) DAO::getSingleValue($link, $que));
		$que = "select DATE_FORMAT(start_date,'%Y') from tr where id='$tr_id'";
		$study_start_year = (int)trim((string) DAO::getSingleValue($link, $que));
		$current_year = (int)date("Y");
		$current_month = (int)date("m");
		$current_month_since_study_start_date = ($current_year - $study_start_year) * 12;

		if ($current_month > $study_start_month)
			$current_month_since_study_start_date += ($current_month - $study_start_month + 1);
		else
			$current_month_since_study_start_date += ($current_month - $study_start_month + 1);

		if ($framework_title == NULL || $framework_title == '') {
			$current_month_since_study_start_date = NULL;
			$month = NULL;
		} else {
			$monthIndex = $current_month_since_study_start_date - 1;
			$month = "month_" . $monthIndex;
		}

		$month = "month_" . ($current_month_since_study_start_date - 1);

		// Calculating target month and target
		if ($current_month_since_study_start_date > 1 && $current_month_since_study_start_date <= 36) {
			$que = "select avg($month) from student_milestones where framework_id = '$framework_id' and chosen=1 and tr_id='$id'";
			$target = trim((string) DAO::getSingleValue($link, $que));
			$target = round((float) $target, 2);
		} else
			$target = 0;

		$que = "select DATE_FORMAT(tr.target_date,'%d/%m/%Y') from tr where id='$id'";
		$end_date = trim((string) DAO::getSingleValue($link, $que));

		$view = ViewFrameworksTrainingRecord::getInstance($link, $id, $framework_id);
		$view->refresh($link, $_REQUEST);

		$view2 = ViewQualificationsTrainingRecord::getInstance($link, $id);
		$view2->refresh($link, $_REQUEST);

		$que = "select description from lookup_pot_status where code='{$pot_vo->status_code}'";
		$record_status = trim((string) DAO::getSingleValue($link, $que));


		// Create Address presentation helper
		$home_bs7666 = new Address();
		$home_bs7666->set($pot_vo, 'home_');

		$work_bs7666 = new Address();
		$work_bs7666->set($pot_vo, 'work_');

		$provider_bs7666 = new Address();
		$provider_bs7666->set($pot_vo, 'provider_');

		$page_record = 'Training Record';

		//$dao = new StudentDAO($link);
		//$stu_vo = $dao->find( (integer) $id); /* @var $stu_vo StudentVO */

		$stu_vo = $pot_vo;

		if ($workplace) {

			$que = "select count(*) from workplace_visits where tr_id='$id' and start_date is not null order by tr_id";
			$planned_work_experience = trim((string) DAO::getSingleValue($link, $que));

			$work_experience_milestones = array(0, 0, 2, 3, 5, 7, 8, 10, 13, 17, 20, 23, 27, 30, 32, 33, 35, 37, 38, 40, 42, 43, 45, 47, 48, 50);

			$que = "select PERIOD_DIFF(DATE_FORMAT(NOW(),'%Y%m'),DATE_FORMAT(tr.start_date,'%Y%m'))+1 from tr where id = '$id'";
			$work_experience_month = DAO::getSingleValue($link, $que);

			if ($work_experience_month < 0)
				$work_experience_month = 0;
			elseif ($work_experience_month > 24)
				$work_experience_month = 24;

			$target_work_experience = $work_experience_milestones[$work_experience_month];


			//$target_work_experience = $days_passed_since_course_started / $no_of_days_in_course * 50;

			$que = "select count(*) from workplace_visits where tr_id='$id' and end_date is not null order by tr_id";
			$workplace_visits = trim((string) DAO::getSingleValue($link, $que));
			$workplace_visits = ($workplace_visits == null) ? 0 : $workplace_visits;

			$dealersView = ViewTrainingRecordDealers::getInstance($tr_id);
		}

		// Presentation

		$filename = DAO::getSingleValue($link, "Select value from configuration where entity='logo'");
		$filename = ($filename == '') ? 'perspective.png' : $filename;
		$logo1 = "1_" . $filename;

		if (DB_NAME == 'am_raytheon')
			$logo1 = 'raytheon.jpg';
		// relmes - php 5.3 assigning the return value of new by reference change
		$pdf = new Cezpdf($paper = 'A4', $orientation = 'portrait');
		$pdf->ezSetCmMargins(1, 2, 1, 1);
		$pdf->selectFont("./lib/Helvetica.afm");
		//$pdf->selectFont( "./lib/Courier.afm" );
		//$pdf->line(20,822,578,822);
		//$pdf->ezImage( getcwd().'/images/logos/'.$logo1);

		//	$pdf->ezStartPageNumbers(100,20,8,'','',1);
		$tr = TrainingRecord::loadFromDatabase($link, $id);
		//	$pdf->ezText(  $row['surname'] . $tr->dob  . getcwd().'/images/logos/'.$filename.'----' . DB_NAME . '/'.$_SESSION['user']->username);


		if (DB_NAME == 'am_raytheon')
			$pdf->addJpegFromFile(getcwd() . '/images/logos/' . $logo1, 60, 750, 500);
		else
			$pdf->addJpegFromFile(getcwd() . '/images/logos/' . $logo1, 176, 731, 260);


		//$pdf->addJpegFromFile(getcwd().'/images/logos/'.$logo1,176,177,44);

		// #120 {0000000011} relmes - changed calling method
		$new_training_record = new TrainingRecord();
		$data = $new_training_record->loadData($link, $id);
		$xml = XML::loadSimpleXML($data);
		$pdf->ezSetY(731);
		$pdf->ezText("<b>" . $xml->FrameworkTitle . "</b>", 14, array('spacing' => '1', 'justification' => 'centre'));
		$pdf->ezText('<b>Programme:</b> ' . $xml->CourseTitle . "\n", 12, array('spacing' => '1.5', 'justification' => 'centre'));
		$pdf->ezText('Progress Report as from: ' . date('d/m/Y'), 12, array('justification' => 'centre'));
		$pdf->ezSetDy(-10);

		$data2 = array();

		$data2[] = array('STUDENT' => $xml->FirstNames . ' ' . $xml->Surname, 'SCHOOL' => $xml->EmployerName);

		if (DB_NAME == 'am_landrover' || DB_NAME == 'am_raythoen')
			$pdf->ezTable($data2, array('STUDENT' => '<b>Student</b>', 'SCHOOL' => '<b>School</b>'), '', array('width' => '525'));
		else
			$pdf->ezTable($data2, array('STUDENT' => '<b>Learner</b>', 'SCHOOL' => '<b>Employer</b>'), '', array('width' => '525'));

		$pdf->ezSetDy(-10);
		$pdf->ezText("<b>QUALIFICATIONS</b>", 14);
		$pdf->ezSetDy(-10);
		foreach ($xml->children() as $child) {
			if ($child->getName() == 'Qualifications') {
				$count = 1;
				foreach ($child as $qual) {
					$pdf->saveState();
					$pdf->ezText('<c:uline><b>' . (strval($count)) . ') ' . $qual->QualificationTitle . '</b></c:uline>', 12);
					$pdf->restoreState();
					$pdf->ezSetDy(-10);
					$pdf->ezText("<b>Completed Units</b>", 12);
					$pdf->ezSetDy(-5);

					$count += 1;
					$count2 = 1;
					foreach ($qual->CompletedUnits as $units) {

						$count2 = 0;
						$data1 = array();
						foreach ($units as $unit) {
							$count2 += 1;

							$data1[] = array('#' => (string)$count2, 'UNIT' => $unit, 'Cmplt' => $unit->attributes()->percentage);
							//$pdf->ezText((strval($count2)) . ') ' . $unit . ' '.$unit->attributes()->percentage,10 );
						}

						if ($units->children())
							$pdf->ezTable($data1, array('#' => '#', 'UNIT' => 'UNIT', 'Cmplt' => '% Completed'), '', array('fontSize' => '6', 'width' => '525'));
						else
							$pdf->ezText("No units completed");
					}

					$pdf->ezSetDy(-10);


					$pdf->ezText("<b>Units Outstanding</b>", 12);
					$pdf->ezSetDy(-5);



					foreach ($qual->ToBeCompletedUnits as $units) {
						$count2 = 0;

						$data3 = array();
						foreach ($units as $unit) {
							$count2 += 1;

							$data3[] = array('#' => (string)$count2, 'UNIT' => $unit, 'Cmplt' => round((float) $unit->attributes()->percentage, 2));



							//$pdf->ezText((strval($count2)) . ') ' . $unit . ' '.$unit->attributes()->percentage,10 );


						}
						if ($units->children())
							$pdf->ezTable($data3, array('#' => '#', 'UNIT' => 'UNIT', 'Cmplt' => '% Completed'), '', array('fontSize' => '6', 'width' => '525'));
						else
							$pdf->ezText("All units completed");
					}
					$pdf->ezSetDy(-10);

					if (DB_NAME == 'am_raytheon') {
						$pdf->ezSetDy(-10);
						$pdf->ezText("<b>SMART Actions Required</b>", 10);
						$pdf->ezSetDy(-5);
						$data4 = array();
						$data4[] = array('a' => '', 'b' => '', 'c' => '', 'd' => '');
						$data4[] = array('a' => '', 'b' => '', 'c' => '', 'd' => '');
						$data4[] = array('a' => '', 'b' => '', 'c' => '', 'd' => '');
						$data4[] = array('a' => '', 'b' => '', 'c' => '', 'd' => '');

						$pdf->ezTable($data4, array('a' => 'SMART Action (specific, measurable, achievable, realistic, time-bound)', 'b' => 'Person Responsible', 'c' => 'Date to Complete', 'd' => 'Evidence of Completion'), '', array('fontSize' => '6', 'height' => '400', 'width' => '525', 'shaded' => 0));

						$pdf->ezSetDy(-10);
					}

					if (DB_NAME != 'am_raytheon')
						$pdf->ezNewPage();
				}
			}
		}

		$pdf->ezSetDy(-10);
		$pdf->ezText("<b>Attendance</b>", 12);
		$pdf->ezSetDy(-5);
		$data4 = array();
		$data4[] = array('a' => (string)$tr->registered_lessons, 'b' => (string)$tr->attendances, 'c' => (string)$tr->lates);
		$pdf->ezTable($data4, array('a' => 'Total Lessons', 'b' => 'Lessons Attended', 'c' => 'Lessons Late'), '', array('fontSize' => '6', 'width' => '525'));

		if (SystemConfig::getEntityValue($link, "workplace")) {
			$pdf->ezSetDy(-10);
			$pdf->ezText("<b>Work Experience</b>", 12);
			$pdf->ezSetDy(-5);
			$data4 = array();
			$data4[] = array('a' => (string)$target_work_experience, 'b' => (string)$planned_work_experience, 'c' => (string)$workplace_visits);
			$pdf->ezTable($data4, array('a' => 'Target', 'b' => 'Planned', 'c' => 'Completed'), '', array('fontSize' => '6', 'width' => '525'));
		}


		if (DB_NAME == 'am_raytheon') {

			$pdf->ezSetDy(-10);
			$data4 = array();
			$data4[] = array('a' => "\n Subjects covered at \n this training visit \n ", 'b' => " \n \n                                                                                 ", 'c' => '\n\n\n\n          ', 'd' => '                 ');
			$data4[] = array('a' => "\n Silver Award \n training progress \n ", 'b' => " \n \n                                                                                 ", 'c' => '', 'd' => '');
			$data4[] = array('a' => "\n ADC comments regarding \n practical training conduct \n ", 'b' => " \n \n                                                                                 ", 'c' => '', 'd' => '');
			$data4[] = array('a' => "\n ADC comments regarding \n theory training conduct \n ", 'b' => " \n \n                                                                                 ", 'c' => '', 'd' => '');
			$data4[] = array('a' => "\n Learner comments regarding \n training (all areas) \n ", 'b' => " \n \n                                                                                 ", 'c' => '', 'd' => '');
			$pdf->ezTable($data4, array('a' => ' ', 'b' => ' '), '', array('fontSize' => '6', 'width' => '525', 'shaded' => 0, 'showLines' => 2));

			$pdf->ezSetDy(-10);
			$pdf->ezSetDy(-5);
			$data4 = array();
			$data4[] = array('a' => "\n Manager", 'b' => " \n \n                                                                                 ", 'c' => '', 'd' => '');
			$data4[] = array('a' => "\n ADC", 'b' => " \n \n                                                                                 ", 'c' => '', 'd' => '');
			$data4[] = array('a' => "\n Learner", 'b' => " \n \n                                                                                 ", 'c' => '', 'd' => '');
			$pdf->ezTable($data4, array('a' => 'Title', 'b' => 'Comments', 'c' => 'Date', 'd' => 'Signature'), '', array('fontSize' => '6', 'width' => '525', 'shaded' => 0, 'showLines' => 2));
		}


		/*			$x=200;
			$y=100;
			$text = "This is cm svks dfk sdkf sd fksj dfkjs dkfj sdfkjs dtest text";
			while($text = $pdf->addTextWrap($x,$y,100,8,$text,'left'))
			{
				$y-=10;				
			}
			
*/

		//$pdf->ezNewPage();
		// $pdf->ezStopPageNumbers();

		//$pdf->ezText( "End of Documents");


		if (!(file_exists(DATA_ROOT . "/uploads/" . DB_NAME)))
			mkdir(DATA_ROOT . "/uploads/" . DB_NAME);


		$target_path = DATA_ROOT . "/uploads/" . DB_NAME . "/";

		if (!(file_exists(DATA_ROOT . "/uploads/" . DB_NAME . "/" . $_SESSION['user']->username . '_reports')))
			mkdir($target_path . "/" . $_SESSION['user']->username . '_reports');

		$pdfcode = $pdf->ezOutput();
		//This path is hardcoded for now ( /data/srv/www/am_common ) 

		$fp = fopen(DATA_ROOT . '/uploads/' . DB_NAME . '/' . $_SESSION['user']->username . '_reports' . '/term_report1.pdf', 'wb');
		fwrite($fp, $pdfcode);
		fclose($fp);
		$filename = DATA_ROOT . '/uploads/' . DB_NAME . '/' . $_SESSION['user']->username . '_reports' . '/term_report1.pdf';
		$len = filesize($filename);
		header("content-type: application/pdf");
		//	header("content-length: $len");
		header("content-disposition: inline; filename=termreport.pdf");

		$fp = fopen($filename, "r");
		fpassthru($fp);



		//$pdf->ezStream();



	}



	public function spaceout($strvalue)
	{

		$buffer = "";


		if ($strvalue == '')
			return $buffer;

		$j = mb_strlen($strvalue);
		for ($k = 0; $k < $j; $k++) {
			$char = mb_substr($strvalue, $k, 1);
			// do stuff with $char
			$buffer = $buffer . $char . '   ';
		}

		return $buffer;
	}
}
