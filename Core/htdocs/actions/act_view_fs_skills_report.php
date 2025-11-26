<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class view_fs_skills_report implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_fs_skills_report", "View Functional Skills Report");


		$view = ViewFSSkillsReport::getInstance($link);
		$view->refresh($link, $_REQUEST);

		$export = isset($_REQUEST['export']) ? $_REQUEST['export'] : '';
		if ($export == 'export')
			$this->exportRecordsToExcel($link, $view);
		require_once('tpl_view_fs_skills_report.php');
	}

	private function exportRecordsToExcel(PDO $link, ViewFSSkillsReport $view)
	{
		define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

		$view = View::getViewFromSession('view_ViewFSSkillsReport');
		$columnsToShow = $view->getSelectedColumns($link);

		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');

		$exam_types_ddl = array(
			'' => '',
			'1' => 'Actual Exam',
			'2' => 'Mock Exam'
		);

		$st = $link->query($statement);
		if ($st) {
			$objSpreadsheet = new Spreadsheet();
			// Set document properties
			$objSpreadsheet->getProperties()->setCreator($_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname)
				->setLastModifiedBy($_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname . ' from Sunesis')
				->setTitle("Office 2007 XLSX Test Document")
				->setSubject("Office 2007 XLSX Test Document")
				->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
				->setKeywords("office 2007 openxml php")
				->setCategory("Test result file");

			$objSpreadsheet->setActiveSheetIndex(0)
				->setCellValue('A1', 'Training')
				->mergeCells('A1:O1')
				->setCellValue('P1', 'Initial Assessment')
				->mergeCells('P1:R1')
				->setCellValue('S1', 'FS English (Reading)')
				->mergeCells('S1:Y1')
				->setCellValue('Z1', 'FS English (Writing)')
				->mergeCells('Z1:AF1')
				->setCellValue('AG1', 'FS English (SLC)')
				->mergeCells('AG1:AM1')
				->setCellValue('AN1', 'FS Maths')
				->mergeCells('AN1:AT1')
				->setCellValue('AU1', 'FS ICT')
				->mergeCells('AU1:BA1')
				->setCellValue('A2', 'L03')
				->setCellValue('B2', 'Surname')
				->setCellValue('C2', 'Forenames')
				->setCellValue('D2', 'Course')
				->setCellValue('E2', 'Tutor')
				->setCellValue('F2', 'Assessor')
				->setCellValue('G2', 'Main Aim Level')
				->setCellValue('H2', 'Main Aim Title')
				->setCellValue('I2', 'Age At Start Of Training')
				->setCellValue('J2', 'Provider')
				->setCellValue('K2', 'Employer')
				->setCellValue('L2', 'Location')
				->setCellValue('M2', 'Employer Postcode')
				->setCellValue('N2', 'Learner Start Date')
				->setCellValue('O2', 'Nine Months End Date')
				->setCellValue('P2', 'IA Maths Level')
				->setCellValue('Q2', 'IA English Level')
				->setCellValue('R2', 'IA ICT Level')
				->setCellValue('S2', 'FS Qual Title')
				->setCellValue('T2', 'Exam Status')
				->setCellValue('U2', 'Exam Type')
				->setCellValue('V2', 'Attempt No.')
				->setCellValue('W2', 'Booked Date')
				->setCellValue('X2', 'Exam Date')
				->setCellValue('Y2', 'Result')
				->setCellValue('Z2', 'FS Qual Title')
				->setCellValue('AA2', 'Exam Status')
				->setCellValue('AB2', 'Exam Type')
				->setCellValue('AC2', 'Attempt No.')
				->setCellValue('AD2', 'Booked Date')
				->setCellValue('AE2', 'Exam Date')
				->setCellValue('AF2', 'Result')
				->setCellValue('AG2', 'FS Qual Title')
				->setCellValue('AH2', 'Exam Status')
				->setCellValue('AI2', 'Exam Type')
				->setCellValue('AJ2', 'Attempt No.')
				->setCellValue('AK2', 'Booked Date')
				->setCellValue('AL2', 'Exam Date')
				->setCellValue('AM2', 'Result')
				->setCellValue('AN2', 'FS Qual Title')
				->setCellValue('AO2', 'Exam Status')
				->setCellValue('AP2', 'Exam Type')
				->setCellValue('AQ2', 'Attempt No.')
				->setCellValue('AR2', 'Booked Date')
				->setCellValue('AS2', 'Exam Date')
				->setCellValue('AT2', 'Result')
				->setCellValue('AU2', 'FS Qual Title')
				->setCellValue('AV2', 'Exam Status')
				->setCellValue('AW2', 'Exam Type')
				->setCellValue('AX2', 'Attempt No.')
				->setCellValue('AY2', 'Booked Date')
				->setCellValue('AZ2', 'Exam Date')
				->setCellValue('BA2', 'Result')

			;

			$rowNumber = 3;
			while ($row = $st->fetch()) {
				$training_record_id = $row['training_record_id'];
				$ilr = $row['ilr'];
				$colTitle = 'A';
				foreach ($columnsToShow as $column) {
					if ($column != 'fs_data') {
						$objSpreadsheet->setActiveSheetIndex(0)
							->setCellValue($colTitle . $rowNumber, $row[$column]);
					} else {
						$colTitle = 'R';
						$sql = <<<ENGLISHREADING
SELECT exam_results.* FROM exam_results INNER JOIN student_qualifications ON
(REPLACE(exam_results.qualification_id, '/', '') = REPLACE(student_qualifications.id, '/', '') AND exam_results.tr_id = student_qualifications.tr_id)
WHERE exam_results.tr_id = '$training_record_id' AND LOWER(internaltitle) LIKE '%english%' AND LOWER(unit_title) LIKE '%reading%' ORDER BY exam_results.id DESC LIMIT 0, 1;
ENGLISHREADING;

						$english_fs_reading_details = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
						if (count($english_fs_reading_details) == 0) {
							$english_reading_fs = "NA";
							$english_reading_fs_id = DAO::getSingleValue($link, "SELECT REPLACE(id, '/', '') AS qid FROM student_qualifications WHERE tr_id = " . $training_record_id . " AND qualification_type = 'FS' AND LOCATE('english', LOWER(internaltitle)) > 0 ;");
							if ($english_reading_fs_id != "") {
								$english_reading_fs = "Exempted";
								if (strpos($ilr, $english_reading_fs_id) != false)
									$english_reading_fs = "Required";
							}
							$objSpreadsheet->setActiveSheetIndex(0)
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, $english_reading_fs)
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, '')
							;
						} else {
							$objSpreadsheet->setActiveSheetIndex(0)
								->setCellValue(++$colTitle . $rowNumber, $english_fs_reading_details[0]['qualification_title'])
								->setCellValue(++$colTitle . $rowNumber, DAO::getSingleValue($link, "SELECT description FROM lookup_exam_status WHERE id = '" . $english_fs_reading_details[0]['exam_status'] . "'"))
								->setCellValue(++$colTitle . $rowNumber, $exam_types_ddl[$english_fs_reading_details[0]['exam_type']])
								->setCellValue(++$colTitle . $rowNumber, $english_fs_reading_details[0]['attempt_no'])
								->setCellValue(++$colTitle . $rowNumber, Date::to($english_fs_reading_details[0]['exam_booked_date'], Date::SHORT))
								->setCellValue(++$colTitle . $rowNumber, Date::to($english_fs_reading_details[0]['exam_date'], Date::SHORT))
								->setCellValue(++$colTitle . $rowNumber, $english_fs_reading_details[0]['exam_result'])
							;
						}
						$sql = <<<ENGLISHWRITING
SELECT exam_results.* FROM exam_results INNER JOIN student_qualifications ON
(REPLACE(exam_results.qualification_id, '/', '') = REPLACE(student_qualifications.id, '/', '') AND exam_results.tr_id = student_qualifications.tr_id)
WHERE exam_results.tr_id = '$training_record_id' AND LOWER(internaltitle) LIKE '%english%' AND LOWER(unit_title) LIKE '%writing%' ORDER BY exam_results.id DESC LIMIT 0, 1;
ENGLISHWRITING;

						$english_fs_writing_details = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
						if (count($english_fs_writing_details) == 0) {
							$english_writing_fs = "NA";
							$english_writing_fs_id = DAO::getSingleValue($link, "SELECT REPLACE(id, '/', '') AS qid FROM student_qualifications WHERE tr_id = " . $training_record_id . " AND qualification_type = 'FS' AND LOCATE('english', LOWER(internaltitle)) > 0 ;");
							if ($english_writing_fs_id != "") {
								$english_writing_fs = "Exempted";
								if (strpos($ilr, $english_writing_fs_id) != false)
									$english_writing_fs = "Required";
							}
							$objSpreadsheet->setActiveSheetIndex(0)
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, $english_writing_fs)
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, '')
							;
						} else {
							$objSpreadsheet->setActiveSheetIndex(0)
								->setCellValue(++$colTitle . $rowNumber, $english_fs_writing_details[0]['qualification_title'])
								->setCellValue(++$colTitle . $rowNumber, DAO::getSingleValue($link, "SELECT description FROM lookup_exam_status WHERE id = '" . $english_fs_writing_details[0]['exam_status'] . "'"))
								->setCellValue(++$colTitle . $rowNumber, $exam_types_ddl[$english_fs_writing_details[0]['exam_type']])
								->setCellValue(++$colTitle . $rowNumber, $english_fs_writing_details[0]['attempt_no'])
								->setCellValue(++$colTitle . $rowNumber, Date::to($english_fs_writing_details[0]['exam_booked_date'], Date::SHORT))
								->setCellValue(++$colTitle . $rowNumber, Date::to($english_fs_writing_details[0]['exam_date'], Date::SHORT))
								->setCellValue(++$colTitle . $rowNumber, $english_fs_writing_details[0]['exam_result'])
							;
						}

						$sql = <<<ENGLISHSLC
SELECT exam_results.* FROM exam_results INNER JOIN student_qualifications ON
(REPLACE(exam_results.qualification_id, '/', '') = REPLACE(student_qualifications.id, '/', '') AND exam_results.tr_id = student_qualifications.tr_id)
WHERE exam_results.tr_id = '$training_record_id' AND LOWER(internaltitle) LIKE '%english%' AND (LOWER(unit_title) LIKE '%speak%' OR LOWER(unit_title) LIKE '%listen%') ORDER BY exam_results.id DESC LIMIT 0, 1;
ENGLISHSLC;

						$english_fs_slc_details = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
						if (count($english_fs_slc_details) == 0) {
							$english_slc_fs = "NA";
							$english_slc_fs_id = DAO::getSingleValue($link, "SELECT REPLACE(id, '/', '') AS qid FROM student_qualifications WHERE tr_id = " . $training_record_id . " AND qualification_type = 'FS' AND LOCATE('english', LOWER(internaltitle)) > 0 ;");
							if ($english_slc_fs_id != "") {
								$english_slc_fs = "Exempted";
								if (strpos($ilr, $english_slc_fs_id) != false)
									$english_slc_fs = "Required";
							}
							$objSpreadsheet->setActiveSheetIndex(0)
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, $english_slc_fs)
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, '')
							;
						} else {
							$objSpreadsheet->setActiveSheetIndex(0)
								->setCellValue(++$colTitle . $rowNumber, $english_fs_slc_details[0]['qualification_title'])
								->setCellValue(++$colTitle . $rowNumber, DAO::getSingleValue($link, "SELECT description FROM lookup_exam_status WHERE id = '" . $english_fs_slc_details[0]['exam_status'] . "'"))
								->setCellValue(++$colTitle . $rowNumber, $exam_types_ddl[$english_fs_slc_details[0]['exam_type']])
								->setCellValue(++$colTitle . $rowNumber, $english_fs_slc_details[0]['attempt_no'])
								->setCellValue(++$colTitle . $rowNumber, Date::to($english_fs_slc_details[0]['exam_booked_date'], Date::SHORT))
								->setCellValue(++$colTitle . $rowNumber, Date::to($english_fs_slc_details[0]['exam_date'], Date::SHORT))
								->setCellValue(++$colTitle . $rowNumber, $english_fs_slc_details[0]['exam_result'])
							;
						}

						$sql = <<<MATHS
SELECT exam_results.* FROM exam_results INNER JOIN student_qualifications ON
(REPLACE(exam_results.qualification_id, '/', '') = REPLACE(student_qualifications.id, '/', '') AND exam_results.tr_id = student_qualifications.tr_id)
WHERE exam_results.tr_id = '$training_record_id' AND (LOWER(internaltitle) LIKE '%maths%' OR LOWER(internaltitle) LIKE '%mathematics%')  ORDER BY exam_results.id DESC LIMIT 0, 1;
MATHS;

						$maths_fs_details = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
						if (count($maths_fs_details) == 0) {
							$maths_fs = "NA";
							$maths_fs_id = DAO::getSingleValue($link, "SELECT REPLACE(id, '/', '') AS qid FROM student_qualifications WHERE tr_id = " . $training_record_id . " AND qualification_type = 'FS' AND (LOCATE('maths', LOWER(internaltitle)) > 0 OR LOCATE('mathematics', LOWER(internaltitle)) > 0) ;");
							if ($maths_fs_id != "") {
								$maths_fs = "Exempted";
								if (strpos($ilr, $maths_fs_id) != false)
									$maths_fs = "Required";
							}
							$objSpreadsheet->setActiveSheetIndex(0)
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, $maths_fs)
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, '')
							;
						} else {
							$objSpreadsheet->setActiveSheetIndex(0)
								->setCellValue(++$colTitle . $rowNumber, $maths_fs_details[0]['qualification_title'])
								->setCellValue(++$colTitle . $rowNumber, DAO::getSingleValue($link, "SELECT description FROM lookup_exam_status WHERE id = '" . $maths_fs_details[0]['exam_status'] . "'"))
								->setCellValue(++$colTitle . $rowNumber, $exam_types_ddl[$maths_fs_details[0]['exam_type']])
								->setCellValue(++$colTitle . $rowNumber, $maths_fs_details[0]['attempt_no'])
								->setCellValue(++$colTitle . $rowNumber, Date::to($maths_fs_details[0]['exam_booked_date'], Date::SHORT))
								->setCellValue(++$colTitle . $rowNumber, Date::to($maths_fs_details[0]['exam_date'], Date::SHORT))
								->setCellValue(++$colTitle . $rowNumber, $maths_fs_details[0]['exam_result'])
							;
						}

						$sql = <<<ICT
SELECT exam_results.* FROM exam_results INNER JOIN student_qualifications ON
(REPLACE(exam_results.qualification_id, '/', '') = REPLACE(student_qualifications.id, '/', '') AND exam_results.tr_id = student_qualifications.tr_id)
WHERE exam_results.tr_id = '$training_record_id' AND LOWER(internaltitle) LIKE '%ict%'  ORDER BY exam_results.id DESC LIMIT 0, 1;
ICT;

						$ict_fs_details = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
						if (count($ict_fs_details) == 0) {
							$ict_fs = "NA";
							$ict_fs_id = DAO::getSingleValue($link, "SELECT REPLACE(id, '/', '') AS qid FROM student_qualifications WHERE tr_id = " . $training_record_id . " AND qualification_type = 'FS' AND LOCATE('ict', LOWER(internaltitle)) > 0;");
							if ($ict_fs_id != "") {
								$ict_fs = "Exempted";
								if (strpos($ilr, $ict_fs_id) != false)
									$ict_fs = "Required";
							}
							$objSpreadsheet->setActiveSheetIndex(0)
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, $ict_fs)
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, '')
								->setCellValue(++$colTitle . $rowNumber, '')
							;
						} else {
							$objSpreadsheet->setActiveSheetIndex(0)
								->setCellValue(++$colTitle . $rowNumber, $ict_fs_details[0]['qualification_title'])
								->setCellValue(++$colTitle . $rowNumber, DAO::getSingleValue($link, "SELECT description FROM lookup_exam_status WHERE id = '" . $ict_fs_details[0]['exam_status'] . "'"))
								->setCellValue(++$colTitle . $rowNumber, $exam_types_ddl[$ict_fs_details[0]['exam_type']])
								->setCellValue(++$colTitle . $rowNumber, $ict_fs_details[0]['attempt_no'])
								->setCellValue(++$colTitle . $rowNumber, Date::to($ict_fs_details[0]['exam_booked_date'], Date::SHORT))
								->setCellValue(++$colTitle . $rowNumber, Date::to($ict_fs_details[0]['exam_date'], Date::SHORT))
								->setCellValue(++$colTitle . $rowNumber, $ict_fs_details[0]['exam_result'])
							;
						}
					}
					$colTitle++;
				}
				$rowNumber++;
			}

			$objSpreadsheet->getActiveSheet()->getStyle("A1:BA1")->getFont()->setBold(true);
			$objSpreadsheet->getActiveSheet()->getStyle("A2:BA2")->getFont()->setBold(true);

			$objSpreadsheet->getActiveSheet()->getStyle('A1:O1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			$objSpreadsheet->getActiveSheet()->getStyle('P1:R1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			$objSpreadsheet->getActiveSheet()->getStyle('S1:Y1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			$objSpreadsheet->getActiveSheet()->getStyle('Z1:AF1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			$objSpreadsheet->getActiveSheet()->getStyle('AG1:AM1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			$objSpreadsheet->getActiveSheet()->getStyle('AN1:AT1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			$objSpreadsheet->getActiveSheet()->getStyle('AU1:BA1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

			$objSpreadsheet->getActiveSheet()->setTitle('FS Status Report');

			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objSpreadsheet->setActiveSheetIndex(0);
			// Redirect output to a client�s web browser (Excel5)
			if (ob_get_length()) {
				ob_end_clean();
			}

			// Send headers
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="CRMActivities.xlsx"');
			header('Cache-Control: max-age=0');
			header('Pragma: public');

			$objWriter = new Xlsx($objSpreadsheet);
			$objWriter->save('php://output');
		}

		exit;
	}
}
