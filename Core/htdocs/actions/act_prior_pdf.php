<?php

use setasign\Fpdi\Fpdi;

class prior_pdf implements IAction
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
		$review_date = trim(DAO::getSingleValue($link, $que));

		$que = "select comments from assessor_review where tr_id='$id' and meeting_date = (select max(meeting_date) from assessor_review where tr_id='$id') order by tr_id;";
		$last_review_status = trim(DAO::getSingleValue($link, $que));

		$que = "select value from configuration where entity='workplace'";
		$workplace = DAO::getSingleValue($link, $que);

		$que = "select id from student_frameworks where tr_id='$id'";
		$framework_id = trim(DAO::getSingleValue($link, $que));

		$que = "select start_date from tr where id='$id'";
		$course_start_date = trim(DAO::getSingleValue($link, $que));

		$que = "select target_date from tr where id='$id'";
		$course_end_date = trim(DAO::getSingleValue($link, $que));

		$que = "select DATEDIFF(target_date,start_date) from tr where id='$id'";
		$no_of_days_in_course = trim(DAO::getSingleValue($link, $que));

		$que = "select DATEDIFF(NOW(), start_date) from tr where id='$id'";
		$days_passed_since_course_started = trim(DAO::getSingleValue($link, $que));

		$que = "select courses.title from courses LEFT JOIN courses_tr on courses_tr.course_id = courses.id where tr_id='$id'";
		$course_title = trim(DAO::getSingleValue($link, $que));

		$fsd = new Date($course_start_date);
		$fed = new Date($course_end_date);

		$coursestamp = $fed->getDate() - $fsd->getDate();
		$currentstamp = time() - $fsd->getDate();

		$days_between_course_start_date_and_end_date = (($coursestamp / 60) / 60) / 24;
		$days_between_course_start_date_and_today = (($currentstamp / 60) / 60) / 24;

		//$months_in_course = round($days_between_course_start_date_and_end_date / 30,0);

		$que = "select duration_in_months from frameworks where id=$framework_id";
		$months_in_course = trim(DAO::getSingleValue($link, $que));

		$months_passed_float = (round($days_between_course_start_date_and_today / 30, 2));

		$months_passed = floor($days_between_course_start_date_and_today / 30);

		$months_passed = ($months_passed < 0) ? 0 : $months_passed;

		if ($days_between_course_start_date_and_end_date > 0)
			$percentcoursepassed = $days_between_course_start_date_and_today / $days_between_course_start_date_and_end_date * 100;

		$que = "select sum(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)/(SELECT SUM(proportion) FROM student_qualifications AS sq WHERE sq.tr_id = student_qualifications.tr_id AND aptitude!=1 GROUP BY student_qualifications.tr_id)*proportion) from student_qualifications where tr_id='$id' and framework_id='$framework_id'";

		$value = DAO::getSingleValue($link, $que);
		$achieved = $value === null ? null : trim($value);

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
		$que = "SELECT description FROM dropdown0708 WHERE code='L12' AND value = '" . addslashes($pot_vo->ethnicity) . "'";
		$value = DAO::getSingleValue($link, $que);
		$ethnicity = $value === null ? null : trim($value);

		$submissions = DAO::getResultset($link, "SELECT concat(submission,':',contract_id,'|',tr_id, '*', contracts.contract_year), concat(contracts.title, ' ', submission) FROM ilr INNER JOIN contracts on contracts.id = ilr.contract_id where tr_id='$id';");

		//$que = "SELECT legal_name from organisations where id = $pot_vo->provider_id";
		//$provider_legal_name = trim(DAO::getSingleValue($link, $que));
		$provider_legal_name = '';

		//$que = "SELECT full_name from locations where id = $pot_vo->provider_location_id";
		//$provider_location= trim(DAO::getSingleValue($link, $que));
		$provider_location = '';

		$que = "select sum(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)/(SELECT SUM(proportion) FROM student_qualifications AS sq WHERE sq.tr_id = student_qualifications.tr_id AND aptitude!=1 GROUP BY student_qualifications.tr_id)*proportion) from student_qualifications where tr_id='$id' and framework_id='$framework_id'";

		$value = DAO::getSingleValue($link, $que);
		$framework_percentage = $value === null ? null : trim($value);

		$que = "select title from student_frameworks where tr_id='$id'";
		$framework_title = trim(DAO::getSingleValue($link, $que));

		$showPanel = array_key_exists('_showPanel', $_REQUEST) ? $_REQUEST['_showPanel'] : '0'; // Default to 1 lesson

		// Calculate target against every training record
		$tr_id = $id;
		$que = "select DATE_FORMAT(start_date,'%m') from tr where id='$tr_id'";
		$study_start_month = (int)trim(DAO::getSingleValue($link, $que));
		$que = "select DATE_FORMAT(start_date,'%Y') from tr where id='$tr_id'";
		$study_start_year = (int)trim(DAO::getSingleValue($link, $que));
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

			$value = DAO::getSingleValue($link, $que);

			if ($value === null) {
				$target = null;
			} else {
				$target = round((float)$value, 2); // cast to float before rounding
			}
		} else
			$target = 0;

		$que = "select DATE_FORMAT(tr.target_date,'%d/%m/%Y') from tr where id='$id'";
		$end_date = trim(DAO::getSingleValue($link, $que));

		$view = ViewFrameworksTrainingRecord::getInstance($link, $id, $framework_id);
		$view->refresh($link, $_REQUEST);

		$view2 = ViewQualificationsTrainingRecord::getInstance($link, $id);
		$view2->refresh($link, $_REQUEST);

		$que = "select description from lookup_pot_status where code='$pot_vo->status_code'";
		$record_status = trim(DAO::getSingleValue($link, $que));


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
			$planned_work_experience = trim(DAO::getSingleValue($link, $que));

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
			$workplace_visits = trim(DAO::getSingleValue($link, $que));
			$workplace_visits = ($workplace_visits == null) ? 0 : $workplace_visits;

			$dealersView = ViewTrainingRecordDealers::getInstance($tr_id);
		}

		// Presentation
		// relmes - php 5.3 assigning the return value of new by reference change
		$pdf = new FPDI();

		$pagecount = $pdf->setSourceFile('ttgilrv6.pdf');

		$tpl = $pdf->ImportPage(2);
		$s = $pdf->getTemplatesize($tpl);

		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);
		$pdf->SetFont('Arial', '', 10); // built-in, no AddFont needed

		$pdf->Text(17, 37, $this->spaceout($provider->upin));
		$pdf->Text(62, 37, $this->spaceout($provider->ukprn));
		$pdf->Text(116, 37, $this->spaceout($pot_vo->l03));

		$pdf->Text(130, 44, $this->spaceout($pot_vo->uln));

		$pdf->Text(
			101,
			61,
			$this->spaceout(str_pad((string) $pot_vo->prior_attainment_level, 2, '0', STR_PAD_LEFT))
		);


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
			$buffer = $buffer . $char . '     ';
		}

		return $buffer;
	}
}
