<?php

/**
 *
 * Outputs the Training Record in Word format
 * Raytheon Requirement
 * 187 {0000000206} - relmes
 * @author Perspective
 *
 */

class print_ilr_word implements IAction
{
	// 187 {0000000206} - relmes - initial functionality to be tidied
	public function execute(PDO $link)
	{
		// temporary username used to test output
		$username = $_SESSION['user']->username;

		$target_path = DATA_ROOT."/uploads/".DB_NAME;

		// check all files and folders exist
		if( !( file_exists(DATA_ROOT."/uploads/".DB_NAME) ) ) {
			mkdir(DATA_ROOT."/uploads/".DB_NAME);
		}
		if( !(file_exists(DATA_ROOT."/uploads/".DB_NAME."/training_reports")) ) {
			mkdir($target_path."/training_reports");
		}
		if( !(file_exists(DATA_ROOT."/uploads/".DB_NAME."/training_reports/training_report_docx")) ) {
			mkdir($target_path."/training_reports/training_report_docx");
		}
		// ---

		$target_path = DATA_ROOT."/uploads/".DB_NAME;

		// Decompress word file in user directory		
		$zip = new ZipArchive();
		// open archive 
		if ($zip->open(DATA_ROOT.'/uploads/'.DB_NAME.'/training_reports/training_report_template.docx') !== TRUE) {
			throw new Exception("Could not open archive ".'uploads/'.DB_NAME.'/training_reports/training_report_template.docx');
		}
		// extract contents to destination directory
		$zip->extractTo(DATA_ROOT.'/uploads/'.DB_NAME.'/training_reports/training_report_docx');

		$zip->close();
		// ----

		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		$contract = isset($_GET['contract']) ? $_GET['contract'] : '';

		if( $id == '' || !is_numeric($id) ) {
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

		$days_between_course_start_date_and_end_date = (($coursestamp/60)/60)/24;
		$days_between_course_start_date_and_today = (($currentstamp/60)/60)/24;

		//$months_in_course = round($days_between_course_start_date_and_end_date / 30,0);

		$que = "select duration_in_months from frameworks where id=$framework_id";
		$months_in_course = trim((string) DAO::getSingleValue($link, $que));

		$months_passed_float = (round($days_between_course_start_date_and_today / 30,2));

		$months_passed = floor($days_between_course_start_date_and_today / 30);

		$months_passed = ($months_passed<0)?0:$months_passed;

		if( $days_between_course_start_date_and_end_date>0 ) {
			$percentcoursepassed = $days_between_course_start_date_and_today / $days_between_course_start_date_and_end_date * 100;
		}

		$que = "select sum(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)/(SELECT SUM(proportion) FROM student_qualifications AS sq WHERE sq.tr_id = student_qualifications.tr_id AND aptitude!=1 GROUP BY student_qualifications.tr_id)*proportion) from student_qualifications where tr_id='$id' and framework_id='$framework_id'";
		$achieved = trim((string) DAO::getSingleValue($link, $que));

		$pot_vo = TrainingRecord::loadFromDatabase($link, $id); /* @var $pot_vo TrainingRecord */

		$provider = Organisation::loadFromDatabase($link, $pot_vo->provider_id);

		$employer = Organisation::loadFromDatabase($link, $pot_vo->employer_id);

		$isSafeToDelete = $pot_vo->isSafeToDelete($link);

		$acl = ACL::loadFromDatabase($link, 'trainingrecord', $id); /* @var $acl ACL */

		$que = "SELECT description from dropdown0708 where code='L12' and value = $pot_vo->ethnicity";
		$ethnicity = trim((string) DAO::getSingleValue($link, $que));

		$submissions = DAO::getResultset($link, "SELECT concat(submission,':',contract_id,'|',tr_id, '*', contracts.contract_year), concat(contracts.title, ' ', submission) FROM ilr INNER JOIN contracts on contracts.id = ilr.contract_id where tr_id='$id';");

		$provider_legal_name = '';
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

		if($current_month > $study_start_month)
			$current_month_since_study_start_date += ($current_month - $study_start_month + 1);
		else
			$current_month_since_study_start_date += ($current_month - $study_start_month + 1);

		if($framework_title==NULL || $framework_title=='')
			$current_month_since_study_start_date = NULL;

		$month = "month_" . ($current_month_since_study_start_date-1);

		// Calculating target month and target
		if($current_month_since_study_start_date>1 && $current_month_since_study_start_date<=36)
		{
			$que = "select avg($month) from student_milestones where framework_id = '$framework_id' and chosen=1 and tr_id='$id'";
			$target = trim((string) DAO::getSingleValue($link, $que));
			$target = round($target ?: 0,2);
		}
		else
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

		// student record information ??
		$stu_vo = $pot_vo;

		if( $workplace ) {

			$que = "select count(*) from workplace_visits where tr_id='$id' and start_date is not null order by tr_id";
			$planned_work_experience = trim((string) DAO::getSingleValue($link, $que));

			$work_experience_milestones = array(0,0,2,3,5,7,8,10,13,17,20,23,27,30,32,33,35,37,38,40,42,43,45,47,48,50);

			$que = "select PERIOD_DIFF(DATE_FORMAT(NOW(),'%Y%m'),DATE_FORMAT(tr.start_date,'%Y%m'))+1 from tr where id = '$id'";
			$work_experience_month = DAO::getSingleValue($link, $que);

			if( $work_experience_month<0 ) {
				$work_experience_month = 0;
			}
			elseif( $work_experience_month>24 ) {
				$work_experience_month=24;
			}

			$target_work_experience = $work_experience_milestones[$work_experience_month];

			$que = "select count(*) from workplace_visits where tr_id='$id' and end_date is not null order by tr_id";
			$workplace_visits = trim((string) DAO::getSingleValue($link, $que));
			$workplace_visits = ($workplace_visits==null)?0:$workplace_visits;

			$dealersView = ViewTrainingRecordDealers::getInstance($tr_id);
		}

		// set up some default word values
		$paragraph_separator = '<w:p w:rsidR="00293E94" w:rsidRPr="00267E42" w:rsidRDefault="00293E94" w:rsidP="00293E94"><w:pPr><w:rPr><w:b/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:b/></w:rPr><w:t></w:t></w:r><w:proofErr w:type="spellEnd"/></w:p>';


		// read file 	
		$document = file_get_contents(DATA_ROOT.'/uploads/'.DB_NAME.'/training_reports/training_report_docx/word/document.xml');

		// Populate values
		$fp = fopen(DATA_ROOT.'/uploads/'.DB_NAME.'/training_reports/training_report_docx/word/document.xml', "w") or die("Couldn't create new file");
		$tr = TrainingRecord::loadFromDatabase($link,$id);

		// #120 {0000000011} relmes - changed calling method
		$new_training_record = new TrainingRecord();
		$data = $new_training_record->loadData($link,$id);
		$xml = XML::loadSimpleXML($data);

		$document = str_replace('FrameworkTitle', htmlspecialchars((string)$xml->FrameworkTitle), $document);
		$document = str_replace('CourseTitle', htmlspecialchars((string)$xml->CourseTitle), $document);
		$document = str_replace('DateToday', date('d/m/Y'), $document);

		$data2 = array('STUDENT' => htmlspecialchars((string)$xml->FirstNames).' '.htmlspecialchars((string)$xml->Surname), 'SCHOOL' => htmlspecialchars((string)$xml->EmployerName));

		if( DB_NAME=='am_landrover' || DB_NAME=='am_raytheon' ) {
			$document = str_replace('LearnerTitle', "Learner", $document);
			$document = str_replace('TitleSchool', "Employer", $document);
		}
		else {
			$document = str_replace('LearnerTitle', "Learner", $document);
			$document = str_replace('TitleSchool', "Employer", $document);
		}

		$document = str_replace('RegisteredLessons', (string)$tr->registered_lessons, $document);
		$document = str_replace('Attendances', (string)$tr->attendances, $document);
		$document = str_replace('Lates', (string)$tr->lates, $document);

		if( SystemConfig::getEntityValue($link, "workplace") ) {
			$document = str_replace('WorkExperience', (string)$target_work_experience, $document);
			$document = str_replace('Plannd', (string)$planned_work_experience, $document);
			$document = str_replace('Visits', (string)$workplace_visits, $document);
		}

		if( DB_NAME=='am_raytheon' ) {
			// break this down
			$review_coverage = '<w:tbl><w:tblPr><w:tblStyle w:val="TableGrid"/><w:tblW w:w="0" w:type="auto"/><w:tblLook w:val="04A0"/></w:tblPr><w:tblGrid><w:gridCol w:w="4621"/><w:gridCol w:w="4621"/></w:tblGrid><w:tr w:rsidR="00425E99" w:rsidTr="007B58FD"><w:trPr><w:trHeight w:val="1217"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="4621" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"><w:r>';
			$review_coverage .= '<w:rPr>';
			$review_coverage .= '<w:sz w:val="16" />';
			$review_coverage .= '<w:szCs w:val="16" />';
			$review_coverage .= '</w:rPr>';
			$review_coverage .= '<w:t xml:space="preserve">Subjects covered at this training visit </w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="4621" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"/></w:tc></w:tr><w:tr w:rsidR="00425E99" w:rsidTr="007B58FD"><w:trPr><w:trHeight w:val="1217"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="4621" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"><w:r>';
			$review_coverage .= '<w:rPr>';
			$review_coverage .= '<w:sz w:val="16" />';
			$review_coverage .= '<w:szCs w:val="16" />';
			$review_coverage .= '</w:rPr>';
			$review_coverage .= '<w:t xml:space="preserve">Silver Award  training progress </w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="4621" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"/></w:tc></w:tr><w:tr w:rsidR="00425E99" w:rsidTr="007B58FD"><w:trPr><w:trHeight w:val="1217"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="4621" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"><w:r><w:lastRenderedPageBreak/>';
			$review_coverage .= '<w:rPr>';
			$review_coverage .= '<w:sz w:val="16" />';
			$review_coverage .= '<w:szCs w:val="16" />';
			$review_coverage .= '</w:rPr>';
			$review_coverage .= '<w:t xml:space="preserve">ADC comments regarding practical training conduct </w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="4621" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"/></w:tc></w:tr><w:tr w:rsidR="00425E99" w:rsidTr="007B58FD"><w:trPr><w:trHeight w:val="1217"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="4621" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"><w:r>';
			$review_coverage .= '<w:rPr>';
			$review_coverage .= '<w:sz w:val="16" />';
			$review_coverage .= '<w:szCs w:val="16" />';
			$review_coverage .= '</w:rPr>';
			$review_coverage .= '<w:t xml:space="preserve">ADC comments regarding theory training conduct </w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="4621" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"/></w:tc></w:tr><w:tr w:rsidR="00425E99" w:rsidTr="007B58FD"><w:trPr><w:trHeight w:val="1217"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="4621" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"><w:r>';
			$review_coverage .= '<w:rPr>';
			$review_coverage .= '<w:sz w:val="16" />';
			$review_coverage .= '<w:szCs w:val="16" />';
			$review_coverage .= '</w:rPr>';
			$review_coverage .= '<w:t xml:space="preserve">Learner comments regarding  training (all areas) </w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="4621" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"/></w:tc></w:tr></w:tbl>';
			$document = str_replace('ReviewStatus', $review_coverage, $document);
		}
		else {
			$document = str_replace('ReviewStatus', '', $document);
		}

		// standard table header - used in both completed and outstanding unit display.
		$document_table_header = '<w:tbl>';
		$document_table_header .= '<w:tblPr>';
		$document_table_header .= '<w:tblStyle w:val="TableGrid"/>';
		$document_table_header .= '<w:tblW w:w="0" w:type="auto"/>';
		$document_table_header .= '<w:tblInd w:w="108" w:type="dxa"/>';
		$document_table_header .= '<w:tblLayout w:type="fixed"/>';
		$document_table_header .= '<w:tblLook w:val="04A0"/>';
		$document_table_header .= '</w:tblPr>';
		$document_table_header .= '<w:tblGrid>';
		$document_table_header .= '<w:gridCol w:w="1799"/>';
		$document_table_header .= '<w:gridCol w:w="5714"/>';
		$document_table_header .= '<w:gridCol w:w="1621"/></w:tblGrid><w:tr w:rsidR="00425E99" w:rsidTr="007B58FD"><w:tc><w:tcPr>';
		$document_table_header .= '<w:tcW w:w="1799" w:type="dxa"/><w:tcBorders><w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
		$document_table_header .= '</w:tcBorders></w:tcPr>';
		$document_table_header .= '<w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"><w:r><w:t>#</w:t></w:r></w:p></w:tc>';
		$document_table_header .= '<w:tc><w:tcPr><w:tcW w:w="5714" w:type="dxa"/><w:tcBorders><w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
		$document_table_header .= '</w:tcBorders></w:tcPr><w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"><w:r><w:t>UNIT</w:t></w:r></w:p></w:tc><w:tc><w:tcPr>';
		$document_table_header .= '<w:tcW w:w="1621" w:type="dxa"/><w:tcBorders><w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
		$document_table_header .= '</w:tcBorders></w:tcPr><w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"><w:r><w:t>% Completed</w:t></w:r></w:p></w:tc></w:tr>';

		$document = str_replace('Student', htmlspecialchars((string)$data2['STUDENT']), $document);
		//$document = str_replace('School', $data2['SCHOOL'], $document);
		$document = str_replace('School', '<![CDATA['.htmlspecialchars((string)$data2['SCHOOL']).']]>', $document);

		$qualification_output = '';
		foreach( $xml->children() as $child ) {
			if ( $child->getName() == 'Qualifications' ) {
				$count=1;
				foreach( $child as $qual ) {
					$qualification_output .= '<w:p w:rsidR="00293E94" w:rsidRPr="00267E42" w:rsidRDefault="00293E94" w:rsidP="00293E94"><w:pPr><w:rPr><w:b/></w:rPr></w:pPr><w:r><w:rPr><w:b/></w:rPr><w:t>(</w:t></w:r><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:b/></w:rPr><w:t>'.strval($count).'</w:t></w:r><w:proofErr w:type="spellEnd"/><w:r w:rsidRPr="00267E42"><w:rPr><w:b/></w:rPr><w:t xml:space="preserve">) </w:t></w:r><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:b/></w:rPr><w:t>'.htmlspecialchars((string)$qual->QualificationTitle).'</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p>';
					$qualification_output .= $paragraph_separator;
					$count++;
					$count2=1;
					$completed_units = '<w:p w:rsidR="00293E94" w:rsidRPr="00267E42" w:rsidRDefault="00293E94" w:rsidP="00293E94"><w:pPr><w:rPr><w:b/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:b/></w:rPr><w:t>Completed Units</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p>';

					foreach( $qual->CompletedUnits as $units ) {
						if ( !$units->children() ) {
							$completed_units .= '<w:p w:rsidR="00293E94" w:rsidRPr="00267E42" w:rsidRDefault="00293E94" w:rsidP="00293E94"><w:proofErr w:type="spellStart"/><w:r><w:t>No Units Completed</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p>';
							$completed_units .= $paragraph_separator;
							break;
						}
						$count2=0;
						$completed_units .= $document_table_header;
						foreach($units as $unit) {
							$count2++;
							$color_scheme = '<w:shd w:val="clear" w:color="auto" w:fill="D9D9D9" w:themeFill="background1" w:themeFillShade="D9" />';
							if ( $count2 % 2) {
								$color_scheme = '';
							}
							$completed_units .=  '<w:tr w:rsidR="00425E99" w:rsidTr="007B58FD">';
							$completed_units .= '<w:tc><w:tcPr>';
							$completed_units .= '<w:tcW w:w="1799" w:type="dxa"/>';
							$completed_units .= $color_scheme;
							$completed_units .= '<w:tcBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
							$completed_units .= '<w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
							$completed_units .= '<w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr>';
							$completed_units .= '<w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"><w:proofErr w:type="spellStart"/><w:r>';
							$completed_units .= '<w:rPr>';
							$completed_units .= '<w:sz w:val="16" />';
							$completed_units .= '<w:szCs w:val="16" />';
							$completed_units .= '</w:rPr>';
							$completed_units .= '<w:t>'.strval($count2).'</w:t>';
							$completed_units .= '</w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="5714" w:type="dxa"/>';
							$completed_units .= $color_scheme;
							$completed_units .= '<w:tcBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
							$completed_units .= '<w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
							$completed_units .= '<w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr>';
							$completed_units .= '<w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"><w:proofErr w:type="spellStart"/><w:r>';
							$completed_units .= '<w:rPr>';
							$completed_units .= '<w:sz w:val="16" />';
							$completed_units .= '<w:szCs w:val="16" />';
							$completed_units .= '</w:rPr>';
							$completed_units .= '<w:t>'.htmlspecialchars((string)$unit).'</w:t>';
							$completed_units .= '</w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1621" w:type="dxa"/>';
							$completed_units .= $color_scheme;
							$completed_units .= '<w:tcBorders>';
							$completed_units .= '<w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
							$completed_units .= '<w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
							$completed_units .= '</w:tcBorders></w:tcPr><w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"><w:proofErr w:type="spellStart"/><w:r>';
							$completed_units .= '<w:rPr>';
							$completed_units .= '<w:sz w:val="16" />';
							$completed_units .= '<w:szCs w:val="16" />';
							$completed_units .= '</w:rPr>';
							$completed_units .= '<w:t>'.round($unit->attributes()->percentage,2).'</w:t>';
							$completed_units .= '</w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc></w:tr>';
						}
						$completed_units .= '</w:tbl>';
					}
					$qualification_output .= $completed_units;

					$outstanding_units = '<w:p w:rsidR="00293E94" w:rsidRPr="00267E42" w:rsidRDefault="00293E94" w:rsidP="00293E94"><w:pPr><w:rPr><w:b/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:b/></w:rPr><w:t>Outstanding Units</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p>';

					foreach( $qual->ToBeCompletedUnits as $units ) {
						if ( !$units->children() ) {
							$completed_units .= '<w:p w:rsidR="00293E94" w:rsidRPr="00267E42" w:rsidRDefault="00293E94" w:rsidP="00293E94"><w:proofErr w:type="spellStart"/><w:r><w:t>No Outstanding Units</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p>';
							$completed_units .= $paragraph_separator;
							break;
						}
						$count2=0;
						$outstanding_units .= $document_table_header;
						foreach( $units as $unit ) {
							$count2++;
							$color_scheme = '<w:shd w:val="clear" w:color="auto" w:fill="D9D9D9" w:themeFill="background1" w:themeFillShade="D9" />';
							if ( $count2 % 2) {
								$color_scheme = '';
							}
							$outstanding_units .=  '<w:tr w:rsidR="00425E99" w:rsidTr="007B58FD">';
							$outstanding_units .= '<w:tc><w:tcPr><w:tcW w:w="1799" w:type="dxa"/>';
							$outstanding_units .= $color_scheme;
							$outstanding_units .= '<w:tcBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
							$outstanding_units .= '<w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
							$outstanding_units .= '<w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr>';
							$outstanding_units .= '<w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"><w:proofErr w:type="spellStart"/><w:r>';
							$outstanding_units .= '<w:rPr>';
							$outstanding_units .= '<w:sz w:val="16" />';
							$outstanding_units .= '<w:szCs w:val="16" />';
							$outstanding_units .= '</w:rPr>';
							$outstanding_units .= '<w:t>'.strval($count2).'</w:t>';
							$outstanding_units .= '</w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="5714" w:type="dxa"/>';
							$outstanding_units .= $color_scheme;
							$outstanding_units .= '<w:tcBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
							$outstanding_units .= '<w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
							$outstanding_units .= '<w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr>';
							$outstanding_units .= '<w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"><w:proofErr w:type="spellStart"/><w:r>';
							$outstanding_units .= '<w:rPr>';
							$outstanding_units .= '<w:sz w:val="16" />';
							$outstanding_units .= '<w:szCs w:val="16" />';
							$outstanding_units .= '</w:rPr>';
							$outstanding_units .= '<w:t>'.htmlspecialchars((string)$unit).'</w:t>';
							$outstanding_units .= '</w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1621" w:type="dxa"/>';
							$outstanding_units .= $color_scheme;
							$outstanding_units .= '<w:tcBorders>';
							$outstanding_units .= '<w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
							$outstanding_units .= '<w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
							$outstanding_units .= '</w:tcBorders></w:tcPr><w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"><w:proofErr w:type="spellStart"/><w:r>';
							$outstanding_units .= '<w:rPr>';
							$outstanding_units .= '<w:sz w:val="16" />';
							$outstanding_units .= '<w:szCs w:val="16" />';
							$outstanding_units .= '</w:rPr>';
							$outstanding_units .= '<w:t>'.( is_numeric($unit->attributes()->percentage) ? round($unit->attributes()->percentage ?: 0,2) : $unit->attributes()->percentage).'</w:t>';
							$outstanding_units .= '</w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc></w:tr>';
						}
						$outstanding_units .= '</w:tbl>';
					}

					$qualification_output .= $outstanding_units;

					if( DB_NAME == 'am_raytheon' ) {
						$smart_measurables = $paragraph_separator;
						$smart_measurables .= '<w:tbl><w:tblPr><w:tblStyle w:val="TableGrid"/><w:tblW w:w="0" w:type="auto"/><w:tblInd w:w="108" w:type="dxa"/><w:tblLook w:val="04A0"/></w:tblPr><w:tblGrid><w:gridCol w:w="4536"/><w:gridCol w:w="1560"/><w:gridCol w:w="1559"/><w:gridCol w:w="1479"/></w:tblGrid><w:tr w:rsidR="00425E99" w:rsidTr="007B58FD"><w:tc><w:tcPr><w:tcW w:w="4536" w:type="dxa"/></w:tcPr><w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"><w:r>';
						$smart_measurables .= '<w:rPr>';
						$smart_measurables .= '<w:sz w:val="16" />';
						$smart_measurables .= '<w:szCs w:val="16" />';
						$smart_measurables .= '</w:rPr>';
						$smart_measurables .= '<w:t xml:space="preserve">SMART Action </w:t></w:r></w:p><w:p w:rsidR="00425E99" w:rsidRPr="00EF6B4F" w:rsidRDefault="00425E99" w:rsidP="007B58FD"><w:pPr><w:rPr><w:sz w:val="16"/><w:szCs w:val="16"/></w:rPr></w:pPr><w:r w:rsidRPr="00EF6B4F"><w:rPr><w:sz w:val="16"/><w:szCs w:val="16"/></w:rPr><w:t>(specific, measurable, achievable, realistic, time-bound)</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1560" w:type="dxa"/></w:tcPr><w:p w:rsidR="00425E99" w:rsidRPr="00EF6B4F" w:rsidRDefault="00425E99" w:rsidP="007B58FD"><w:r w:rsidRPr="00EF6B4F">';
						$smart_measurables .= '<w:rPr>';
						$smart_measurables .= '<w:sz w:val="16" />';
						$smart_measurables .= '<w:szCs w:val="16" />';
						$smart_measurables .= '</w:rPr>';
						$smart_measurables .= '<w:t>Person Responsible</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1559" w:type="dxa"/></w:tcPr><w:p w:rsidR="00425E99" w:rsidRPr="00EF6B4F" w:rsidRDefault="00425E99" w:rsidP="007B58FD"><w:r w:rsidRPr="00EF6B4F">';
						$smart_measurables .= '<w:rPr>';
						$smart_measurables .= '<w:sz w:val="16" />';
						$smart_measurables .= '<w:szCs w:val="16" />';
						$smart_measurables .= '</w:rPr>';
						$smart_measurables .= '<w:t>Date to Complete</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1479" w:type="dxa"/></w:tcPr><w:p w:rsidR="00425E99" w:rsidRPr="00EF6B4F" w:rsidRDefault="00425E99" w:rsidP="007B58FD"><w:r w:rsidRPr="00EF6B4F">';
						$smart_measurables .= '<w:rPr>';
						$smart_measurables .= '<w:sz w:val="16" />';
						$smart_measurables .= '<w:szCs w:val="16" />';
						$smart_measurables .= '</w:rPr>';
						$smart_measurables .= '<w:t>Evidence of Completion</w:t></w:r></w:p></w:tc></w:tr><w:tr w:rsidR="00425E99" w:rsidTr="007B58FD"><w:tc><w:tcPr><w:tcW w:w="4536" w:type="dxa"/></w:tcPr><w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"/></w:tc><w:tc><w:tcPr><w:tcW w:w="1560" w:type="dxa"/></w:tcPr><w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"/></w:tc><w:tc><w:tcPr><w:tcW w:w="1559" w:type="dxa"/></w:tcPr><w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"/></w:tc><w:tc><w:tcPr><w:tcW w:w="1479" w:type="dxa"/></w:tcPr><w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"/><w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"/><w:p w:rsidR="00425E99" w:rsidRDefault="00425E99" w:rsidP="007B58FD"/></w:tc></w:tr></w:tbl>';
						$qualification_output .= $smart_measurables;
						$qualification_output .= $paragraph_separator;
					}
				}
			}
		}

		$document = str_replace('QUALIFICATIONS', $qualification_output, $document);

		$document = fwrite($fp, $document);
		fclose($fp);


		// create object
		$zip = new ZipArchive();

		// check all files and folders exist
		if( !( file_exists($target_path."/".$username) ) ) {
			mkdir($target_path."/".$username);
		}


		// open archive 
		// docx filetype on earlier versions of windows may require:
		// http://www.microsoft.com/downloads/en/details.aspx?FamilyId=941B3470-3AE9-4AEE-8F43-C6BB74CD1466&displaylang=en
		//
		// relmes - updated to use learner surname and date of generation to uniquely identify the report
		if ( $zip->open($target_path."/".$username."/".date('dmY')."_".$xml->Surname."_training_report.docx", ZIPARCHIVE::CREATE ) !== TRUE) {
			throw new Exception("Could not open archive");
		}

		// Get the contents of the word document template directory 
		// - this ensures all the relevant bits are included
		// - replaces the previsou hardcoded method.
		//TODO create a library function for word manipulations		
		$this->docxDirectory(DATA_ROOT.'/uploads/'.DB_NAME.'/training_reports/training_report_docx');

		// add files
		foreach ($this->fileList as $f) {
			// create the path to the file as stored in the archive
			$zip_pathname = str_replace(DATA_ROOT.'/uploads/'.DB_NAME.'/training_reports/training_report_docx/', "", $f);
			// check if the file actually exists
			$full_file_path = realpath($f);
			if ( $full_file_path != '' ) {
				// add it to the archive
				$zip->addFile($f, $zip_pathname) or die ("ERROR: Could not add file: $f");
			}
		}

		// close and save archive
		$zip->close();
		ob_clean();
		// relmes - updated to use learner surname and date of generation to uniquely identify the report
		http_redirect("do.php?_action=downloader&path=".$username."&f=".date('dmY')."_".$xml->Surname."_training_report.docx");
	}

	// function to obtain the contents of a docx archive
	// - this differs depending on the content on the word document
	// - so needs to be dynamic
	private function docxDirectory($path = '') {
		if ( '' != $path ) {
			$handle = @opendir($path);
			while (false !== ($file = readdir($handle))) {
				if ($file == '.' || $file == '..') continue;

				if ( is_dir("$path/$file")) {
					$this->docxDirectory("$path/$file");
				} else {
					$this->fileList[] = $path.'/'.$file;
				}
			}
			closedir($handle);
		}
	}
	// array to hold the content of the word extraction folders
	private $fileList = array();

}
