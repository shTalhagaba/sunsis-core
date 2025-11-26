<?php

use setasign\Fpdi\Fpdi;

class ttg_ilp implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$qualification_id = isset($_GET['qualification_id']) ? $_GET['qualification_id'] : '';
		$framework_id = isset($_GET['framework_id']) ? $_GET['framework_id'] : '';
		$id = isset($_GET['tr_id']) ? $_GET['tr_id'] : '';
		$internaltitle = isset($_GET['internaltitle']) ? $_GET['internaltitle'] : '';


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

		$que = "select courses.programme_type from courses LEFT JOIN courses_tr on courses_tr.course_id = courses.id where tr_id='$id'";
		$programme_type = trim((string) DAO::getSingleValue($link, $que));

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
		$que = "SELECT description from dropdown0708 where code='L12' and value = $pot_vo->ethnicity";
		$ethnicity = trim((string) DAO::getSingleValue($link, $que));

		$submissions = DAO::getResultset($link, "SELECT concat(submission,':',contract_id,'|',tr_id, '*', contracts.contract_year), concat(contracts.title, ' ', submission) FROM ilr INNER JOIN contracts on contracts.id = ilr.contract_id where tr_id='$id';");

		//$que = "SELECT legal_name from organisations where id = $pot_vo->provider_id";
		//$provider_legal_name = trim((string) DAO::getSingleValue($link, $que));
		$provider_legal_name = '';

		//$que = "SELECT full_name from locations where id = $pot_vo->provider_location_id";
		//$provider_location= trim((string) DAO::getSingleValue($link, $que));
		$provider_location = '';

		$que = "select sum(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)/(SELECT SUM(proportion) FROM student_qualifications AS sq WHERE sq.tr_id = student_qualifications.tr_id AND aptitude!=1 GROUP BY student_qualifications.tr_id)*proportion) from student_qualifications where tr_id='$id' and framework_id='$framework_id'";
		$framework_percentage = trim((string) DAO::getSingleValue($link, $que) ?: 0);

		$que = "select title from student_frameworks where tr_id='$id'";
		$framework_title = trim((string) DAO::getSingleValue($link, $que));

		$showPanel = array_key_exists('_showPanel', $_REQUEST) ? $_REQUEST['_showPanel'] : '0'; // Default to 1 lesson

		// Calculate target against every training record
		$tr_id = $id;
		$que = "select DATE_FORMAT(start_date,'%m') from tr where id='$tr_id'";
		$study_start_month = (int)trim((string) DAO::getSingleValue($link, $que) ?: '');
		$que = "select DATE_FORMAT(start_date,'%Y') from tr where id='$tr_id'";
		$study_start_year = (int)trim((string) DAO::getSingleValue($link, $que) ?: '');
		$current_year = (int)date("Y");
		$current_month = (int)date("m");
		$current_month_since_study_start_date = ($current_year - $study_start_year) * 12;

		if ($current_month > $study_start_month)
			$current_month_since_study_start_date += ($current_month - $study_start_month + 1);
		else
			$current_month_since_study_start_date += ($current_month - $study_start_month + 1);

		if ($framework_title == NULL || $framework_title == '')
			$current_month_since_study_start_date = NULL;

		$month = "month_" . ($current_month_since_study_start_date - 1);

		// Calculating target month and target
		if ($current_month_since_study_start_date > 1 && $current_month_since_study_start_date <= 36) {
			$que = "select avg($month) from student_milestones where framework_id = '$framework_id' and chosen=1 and tr_id='$id'";
			$target = trim((string) DAO::getSingleValue($link, $que));
			$target = round($target ?: 0, 2);
		} else
			$target = 0;

		$que = "select DATE_FORMAT(tr.target_date,'%d/%m/%Y') from tr where id='$id'";
		$end_date = trim((string) DAO::getSingleValue($link, $que));

		$view = ViewFrameworksTrainingRecord::getInstance($link, $id, $framework_id);
		$view->refresh($link, $_REQUEST);

		$view2 = ViewQualificationsTrainingRecord::getInstance($link, $id);
		$view2->refresh($link, $_REQUEST);

		$que = "select description from lookup_pot_status where code='$pot_vo->status_code'";
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

			$que = "select timestampdiff(MONTH, tr.start_date, CURDATE()) from tr where id = '$id'";
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
		// relmes - php 5.3 assigning the return value of new by reference change
		$pdf = new FPDI();

		//		if($programme_type==2)
		$pagecount = $pdf->setSourceFile('appilpv3.pdf');
		//		else
		//			$pagecount = $pdf->setSourceFile('ttgilpv3.pdf');

		$tpl = $pdf->ImportPage(1);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);
		$pdf->SetFont('Arial', '', 10);

		$pdf->Text(47, 52, strtoupper($pot_vo->firstnames . ' ' . $pot_vo->surname));
		$d = new Date($pot_vo->dob);
		$pdf->Text(242, 52, $d->formatMedium());
		$d = new Date($pot_vo->start_date);
		$pdf->Text(72, 64, $d->formatMedium());

		$d = new Date($pot_vo->target_date);
		$pdf->Text(164, 64, $d->formatMedium());

		/*		$nvqs 	= DAO::getSingleValue($link,"select count(*) from student_qualifications where tr_id=$pot_vo->id and qualification_type='NVQ'");

		if($nvqs>1)
			throw new Exception("There are more than one NVQs for this learner");
		elseif($nvqs<1)
			throw new Exception("There is no NVQ for this learner");
*/

		//	$qid = DAO::getSingleValue($link,"select id from student_qualifications where tr_id=$pot_vo->id and qualification_type='NVQ'");	
		//	$fid = DAO::getSingleValue($link,"select framework_id from student_qualifications where tr_id=$pot_vo->id and qualification_type='NVQ' and id='$qid'");	
		//	$internaltitle = DAO::getSingleValue($link,"select internaltitle from student_qualifications where tr_id=$pot_vo->id and qualification_type='NVQ' and id='$qid' and framework_id = $fid");

		$q = StudentQualification::loadFromDatabase($link, $qualification_id, $framework_id, $pot_vo->id, $internaltitle);

		$pdf->Text(60, 41, $q->title);
		$pdf->Text(236, 74, $q->awarding_body);

		$pdf->Text(72, 74, $q->awarding_body_reg);


		$abd = new Date($q->awarding_body_date);
		$pdf->Text(164, 74, $abd->formatMedium());


		$o = Organisation::loadFromDatabase($link, $pot_vo->employer_id);
		$pdf->Text(52, 94, $o->legal_name);

		$l = Location::loadFromDatabase($link, $pot_vo->employer_location_id);
		/*		$pdf->Text(52,111,$l->paon_start_number . $l->paon_start_suffix . $l->paon_end_number . $l->paon_end_suffix);
		$pdf->Text(52,116,$l->street_description);
		$pdf->Text(52,121,$l->locality . ', ' . $l->town);
		$pdf->Text(52,126,$l->county);*/
		$pdf->Text(52, 111, $l->address_line_1);
		$pdf->Text(52, 116, $l->address_line_2);
		$pdf->Text(52, 121, $l->address_line_3);
		$pdf->Text(52, 126, $l->address_line_4);

		$assessor_name = DAO::getSingleValue($link, "select concat(firstnames, ' ', surname) from users inner join groups on groups.assessor = users.id inner join group_members on group_members.groups_id = groups.id where group_members.tr_id=$pot_vo->id;");
		$verifier = DAO::getSingleValue($link, "select concat(firstnames, ' ', surname) from users inner join groups on groups.verifier = users.id inner join group_members on group_members.groups_id = groups.id where group_members.tr_id=$pot_vo->id;");

		if ($assessor_name == '')
			$assessor_name = DAO::getSingleValue($link, "SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE id = '{$pot_vo->assessor}'");
		if ($verifier == '')
			$verifier = DAO::getSingleValue($link, "SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE id = '{$pot_vo->verifier}'");

		$pdf->Text(160, 110, $assessor_name);
		$pdf->Text(230, 110, $verifier);

		$pdf->Text(52, 142, $l->telephone);
		$pdf->Text(155, 142, $l->contact_name);

		$u = User::loadFromDatabase($link, $pot_vo->username);

		$numeracy = DAO::getSingleValue($link, "SELECT description from lookup_pre_assessment where id='$u->numeracy';");
		$literacy = DAO::getSingleValue($link, "SELECT description from lookup_pre_assessment where id='$u->literacy';");

		$pdf->Text(70, 176, $numeracy);
		$pdf->Text(70, 181, $literacy);

		$tpl = $pdf->ImportPage(2);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$tpl = $pdf->ImportPage(3);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$tpl = $pdf->ImportPage(4);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$tpl = $pdf->ImportPage(5);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$tpl = $pdf->ImportPage(6);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$doc = XML::loadXMLDom($q->evidences);
		$units = $doc->getElementsByTagName("unit");

		$row = 110;
		$owner_references = array();
		foreach ($units as $unit) {
			$owner_references[] = $unit->getAttribute('owner_reference') . '-' . $unit->getAttribute('title');
		}

		if (sizeof($owner_references) >= 4) {
			for ($index = 0; $index < 4; $index++) {
				$this->textWrap($pdf, $owner_references[$index], 22, $row);
				$row += 20;
			}
		} else {
			for ($index = 0; $index < sizeof($owner_references); $index++) {
				$this->textWrap($pdf, $owner_references[$index], 22, $row);
				$row += 20;
			}
		}

		$tpl = $pdf->ImportPage(7);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$row = 100;
		if (sizeof($owner_references) > 8) {
			for ($index = 4; $index < 8; $index++) {
				$this->textWrap($pdf, $owner_references[$index], 22, $row);
				$row += 20;
			}
		} else {
			for ($index = 4; $index < sizeof($owner_references); $index++) {
				$this->textWrap($pdf, $owner_references[$index], 22, $row);
				$row += 20;
			}
		}

		if (sizeof($owner_references) > 8) {
			$row = 110;
			$tpl = $pdf->ImportPage(7);
			$s = $pdf->getTemplatesize($tpl);
			$pdf->AddPage('P', array($s['width'], $s['height']));
			$pdf->useTemplate($tpl);
			if (sizeof($owner_references) > 12) {
				for ($index = 8; $index < 12; $index++) {
					$this->textWrap($pdf, $owner_references[$index], 22, $row);
					$row += 20;
				}
			} else {
				for ($index = 8; $index < sizeof($owner_references); $index++) {
					$this->textWrap($pdf, $owner_references[$index], 22, $row);
					$row += 20;
				}
			}
		}

		$tpl = $pdf->ImportPage(8);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$tpl = $pdf->ImportPage(9);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$tpl = $pdf->ImportPage(10);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$tpl = $pdf->ImportPage(11);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);



		echo $pdf->Output();
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

	public function textWrap($pdf, $references, $length, $r)
	{
		$chunks = array();
		$arr = explode(" ", $references);
		$in = 0;
		$temp = '';
		foreach ($arr as $ar) {
			if (strlen($temp . ' ' . $ar) > $length) {
				$chunks[$in] = $temp;
				$temp = $ar;
				$in++;
			} else {
				$temp = $temp . ' ' . $ar;
			}
		}
		$chunks[$in] = $temp;
		foreach ($chunks as $chunk) {
			$pdf->Text(13, $r, trim($chunk));
			$r += 5;
		}
	}
}
