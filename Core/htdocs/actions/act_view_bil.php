<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class view_bil implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_bil", 'Break in Learning');

		set_time_limit(0);
		ini_set('memory_limit', '2048M');

		// Loop through all the contracts starting with the most recent
		$current_contract_year = DAO::getSingleValue($link, "SELECT contract_year FROM contracts ORDER BY contract_year DESC LIMIT 0,1;");
		$this->createTempTable($link);
		$data = array();
		for ($year = $current_contract_year; $year >= ($current_contract_year - 4); $year--) {
			$sql = "SELECT * FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE ilr.is_active = 1 and contracts.funding_body = 2 and submission = (SELECT MAX(submission) FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE contract_year = $year) and contract_year = '$year' and tr_id not in (SELECT tr_id FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE contract_year > $year);";
			$st = $link->query($sql);
			if ($st) {
				while ($row = $st->fetch()) {
					if ($row['contract_year'] < 2012) {
						if (is_null($row['ilr']) || $row['ilr'] == '')
							continue;
						$ilr = Ilr2011::loadFromXML($row['ilr']);
						$tr_id = $row['tr_id'];
						$framework_title = DAO::getSingleValue($link, "SELECT frameworks.title FROM frameworks INNER JOIN courses ON courses.`framework_id` = frameworks.`id` INNER JOIN courses_tr ON courses_tr.`course_id` = courses.`id` WHERE courses_tr.tr_id = '$tr_id'");
						$assessor = DAO::getSingleValue($link, "SELECT
	CASE
	WHEN (tr.`assessor` IS NULL OR tr.assessor = 0) AND (groups.`assessor` IS NULL OR groups.`assessor` = 0)
	THEN ''
	WHEN (tr.`assessor` IS NOT NULL AND tr.assessor != 0) AND (groups.`assessor` IS NULL OR groups.`assessor` = 0)
	THEN (SELECT CONCAT(users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = tr.`assessor`)
	WHEN (tr.`assessor` IS NULL OR tr.`assessor` = 0) AND (groups.`assessor` IS NOT NULL AND groups.`assessor` != 0)
	THEN (SELECT CONCAT(users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = groups.`assessor`)
	WHEN (tr.`assessor` IS NOT NULL AND tr.`assessor` != 0) AND (groups.`assessor` IS NOT NULL AND groups.`assessor` != 0) AND tr.`assessor` = groups.`assessor`
	THEN (SELECT CONCAT(users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = groups.`assessor`)
	WHEN (tr.`assessor` IS NOT NULL AND tr.`assessor` != 0) AND (groups.`assessor` IS NOT NULL AND groups.`assessor` != 0) AND tr.`assessor` != groups.`assessor`
	THEN (
			SELECT CONCAT
					(
							(SELECT CONCAT('GA: ', users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = tr.`assessor`)
						, '; ',
							(SELECT CONCAT('TA: ', users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = groups.`assessor`)
					)
		 )
	END AS assessor
FROM
	tr LEFT JOIN group_members ON tr.id = group_members.`tr_id` LEFT JOIN groups ON group_members.`groups_id` = groups.`id`
WHERE
	tr.id = $tr_id
;");
						$tutor = DAO::getSingleValue($link, "SELECT
	CASE
	WHEN (tr.`tutor` IS NULL OR tr.tutor = 0) AND (groups.`tutor` IS NULL OR groups.`tutor` = 0)
	THEN ''
	WHEN (tr.`tutor` IS NOT NULL AND tr.tutor != 0) AND (groups.`tutor` IS NULL OR groups.`tutor` = 0)
	THEN (SELECT CONCAT(users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = tr.`tutor`)
	WHEN (tr.`tutor` IS NULL OR tr.`tutor` = 0) AND (groups.`tutor` IS NOT NULL AND groups.`tutor` != 0)
	THEN (SELECT CONCAT(users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = groups.`tutor`)
	WHEN (tr.`tutor` IS NOT NULL AND tr.`tutor` != 0) AND (groups.`tutor` IS NOT NULL AND groups.`tutor` != 0) AND tr.`tutor` = groups.`tutor`
	THEN (SELECT CONCAT(users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = groups.`tutor`)
	WHEN (tr.`tutor` IS NOT NULL AND tr.`tutor` != 0) AND (groups.`tutor` IS NOT NULL AND groups.`tutor` != 0) AND tr.`tutor` != groups.`tutor`
	THEN (
			SELECT CONCAT
					(
							(SELECT CONCAT('GT: ', users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = tr.`tutor`)
						, '; ',
							(SELECT CONCAT('TT: ', users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = groups.`tutor`)
					)
		 )
	END AS tutor
FROM
	tr LEFT JOIN group_members ON tr.id = group_members.`tr_id` LEFT JOIN groups ON group_members.`groups_id` = groups.`id`
WHERE
	tr.id = $tr_id
;");

						$l03 = $row['L03'];

						if ($ilr->learnerinformation->L08 != "Y") {
							if (($ilr->programmeaim->A15 != "99" && $ilr->programmeaim->A15 != "" && $ilr->programmeaim->A15 != "0")) {
								$start_date = Date::toMySQL($ilr->programmeaim->A27);
								$end_date = Date::toMySQL($ilr->programmeaim->A28);

								if ($ilr->programmeaim->A31 != '00000000' && $ilr->programmeaim->A31 != '00/00/0000' && $ilr->programmeaim->A31 != '')
									$actual_date = Date::toMySQL($ilr->programmeaim->A31);
								else
									$actual_date = "0000-00-00";

								// Calculation for p_prog_status for apprenticeship only
								if ($ilr->programmeaim->A15 == '2' || $ilr->programmeaim->A15 == '3' || $ilr->programmeaim->A15 == '10') {
									if ($ilr->programmeaim->A34 == 6) {
										$d = array();
										$d['l03'] = $l03;
										$d['uln'] = $ilr->learnerinformation->L45;
										$d['surname'] = $ilr->learnerinformation->L09;
										$d['firstname'] = $ilr->learnerinformation->L10;
										$d['framework_title'] = $framework_title;
										$d['assessor'] = $assessor;
										$d['tutor'] = $tutor;
										$d['contract'] = DAO::getSingleValue($link, "SELECT title FROM contracts WHERE id = '" . $row['contract_id'] . "'");
										$d['start_date'] = $start_date;
										$d['planned_end_date'] = $end_date;
										$d['actual_end_date'] = $actual_date;
										$d['comp_status'] = $ilr->programmeaim->A34;
										$d['outcome'] = $ilr->programmeaim->A35;
										$d['restart'] = 0;
										$d['fund_remain'] = 0;
										$d['tr_id'] = $tr_id;
										$data[] = $d;
									}
									if ($ilr->programmeaim->A16 == 11) {
										$d = array();
										$d['l03'] = $l03;
										$d['uln'] = $ilr->learnerinformation->L45;
										$d['surname'] = $ilr->learnerinformation->L09;
										$d['firstname'] = $ilr->learnerinformation->L10;
										$d['framework_title'] = $framework_title;
										$d['assessor'] = $assessor;
										$d['tutor'] = $tutor;
										$d['contract'] = DAO::getSingleValue($link, "SELECT title FROM contracts WHERE id = '" . $row['contract_id'] . "'");
										$d['start_date'] = $start_date;
										$d['planned_end_date'] = $end_date;
										$d['actual_end_date'] = $actual_date;
										$d['comp_status'] = $ilr->programmeaim->A34;
										$d['outcome'] = $ilr->programmeaim->A35;
										$d['restart'] = 1;
										$d['fund_remain'] = $ilr->programmeaim->A51a;
										$d['tr_id'] = $tr_id;
										$data[] = $d;
									}
								}
							}
						}
					} else {
						$ilr = Ilr2012::loadFromXML($row['ilr']);
						$tr_id = $row['tr_id'];
						$framework_title = DAO::getSingleValue($link, "SELECT frameworks.title FROM frameworks INNER JOIN courses ON courses.`framework_id` = frameworks.`id` INNER JOIN courses_tr ON courses_tr.`course_id` = courses.`id` WHERE courses_tr.tr_id = '$tr_id'");
						$assessor = DAO::getSingleValue($link, "SELECT
	CASE
	WHEN (tr.`assessor` IS NULL OR tr.assessor = 0) AND (groups.`assessor` IS NULL OR groups.`assessor` = 0)
	THEN ''
	WHEN (tr.`assessor` IS NOT NULL AND tr.assessor != 0) AND (groups.`assessor` IS NULL OR groups.`assessor` = 0)
	THEN (SELECT CONCAT(users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = tr.`assessor`)
	WHEN (tr.`assessor` IS NULL OR tr.`assessor` = 0) AND (groups.`assessor` IS NOT NULL AND groups.`assessor` != 0)
	THEN (SELECT CONCAT(users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = groups.`assessor`)
	WHEN (tr.`assessor` IS NOT NULL AND tr.`assessor` != 0) AND (groups.`assessor` IS NOT NULL AND groups.`assessor` != 0) AND tr.`assessor` = groups.`assessor`
	THEN (SELECT CONCAT(users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = groups.`assessor`)
	WHEN (tr.`assessor` IS NOT NULL AND tr.`assessor` != 0) AND (groups.`assessor` IS NOT NULL AND groups.`assessor` != 0) AND tr.`assessor` != groups.`assessor`
	THEN (
			SELECT CONCAT
					(
							(SELECT CONCAT('GA: ', users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = tr.`assessor`)
						, '; ',
							(SELECT CONCAT('TA: ', users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = groups.`assessor`)
					)
		 )
	END AS assessor
FROM
	tr LEFT JOIN group_members ON tr.id = group_members.`tr_id` LEFT JOIN groups ON group_members.`groups_id` = groups.`id`
WHERE
	tr.id = $tr_id
;");
						$tutor = DAO::getSingleValue($link, "SELECT
	CASE
	WHEN (tr.`tutor` IS NULL OR tr.tutor = 0) AND (groups.`tutor` IS NULL OR groups.`tutor` = 0)
	THEN ''
	WHEN (tr.`tutor` IS NOT NULL AND tr.tutor != 0) AND (groups.`tutor` IS NULL OR groups.`tutor` = 0)
	THEN (SELECT CONCAT(users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = tr.`tutor`)
	WHEN (tr.`tutor` IS NULL OR tr.`tutor` = 0) AND (groups.`tutor` IS NOT NULL AND groups.`tutor` != 0)
	THEN (SELECT CONCAT(users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = groups.`tutor`)
	WHEN (tr.`tutor` IS NOT NULL AND tr.`tutor` != 0) AND (groups.`tutor` IS NOT NULL AND groups.`tutor` != 0) AND tr.`tutor` = groups.`tutor`
	THEN (SELECT CONCAT(users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = groups.`tutor`)
	WHEN (tr.`tutor` IS NOT NULL AND tr.`tutor` != 0) AND (groups.`tutor` IS NOT NULL AND groups.`tutor` != 0) AND tr.`tutor` != groups.`tutor`
	THEN (
			SELECT CONCAT
					(
							(SELECT CONCAT('GT: ', users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = tr.`tutor`)
						, '; ',
							(SELECT CONCAT('TT: ', users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = groups.`tutor`)
					)
		 )
	END AS tutor
FROM
	tr LEFT JOIN group_members ON tr.id = group_members.`tr_id` LEFT JOIN groups ON group_members.`groups_id` = groups.`id`
WHERE
	tr.id = $tr_id
;");
						$l03 = $row['L03'];

						foreach ($ilr->LearningDelivery as $delivery) {
							if ($delivery->AimType == 1 && $delivery->ProgType != '99' && ("" . $delivery->ProgType) != '') {
								$start_date = Date::toMySQL("" . $delivery->LearnStartDate);
								$end_date = Date::toMySQL("" . $delivery->LearnPlanEndDate);
								$LearnActEndDate = "" . $delivery->LearnActEndDate;
								$OrigLearnStartDate = "" . $delivery->OrigLearnStartDate;
								if ($LearnActEndDate != '00000000' && $LearnActEndDate != '00/00/0000' && $LearnActEndDate != '')
									$actual_date = Date::toMySQL($LearnActEndDate);
								else
									$actual_date = "0000-00-00";
								if ($OrigLearnStartDate != '00000000' && $OrigLearnStartDate != '00/00/0000' && $OrigLearnStartDate != '')
									$original_actual_date = Date::toMySQL($OrigLearnStartDate);
								else
									$original_actual_date = "0000-00-00";

								if ($delivery->CompStatus == '6') {
									$d = array();
									$d['l03'] = $l03;
									$d['uln'] = $ilr->ULN;
									$d['surname'] = $ilr->FamilyName;
									$d['firstname'] = $ilr->GivenNames;
									$d['framework_title'] = $framework_title;
									$d['assessor'] = $assessor;
									$d['tutor'] = $tutor;
									$d['contract'] = DAO::getSingleValue($link, "SELECT title FROM contracts WHERE id = '" . $row['contract_id'] . "'");
									$d['start_date'] = $start_date;
									$d['planned_end_date'] = $end_date;
									$d['actual_end_date'] = $actual_date;
									$d['comp_status'] = $delivery->CompStatus;
									$d['outcome'] = $delivery->Outcome;;
									$d['restart'] = 0;
									$d['fund_remain'] = NULL;
									$d['tr_id'] = $tr_id;
									$data[] = $d;
								}
								foreach ($delivery->LearningDeliveryFAM as $ldfam) {
									if ($ldfam->LearnDelFAMType == 'RES' && $ldfam->LearnDelFAMCode == '1') {
										$d = array();
										$d['l03'] = $l03;
										$d['uln'] = $ilr->ULN;
										$d['surname'] = $ilr->FamilyName;
										$d['firstname'] = $ilr->GivenNames;
										$d['framework_title'] = $framework_title;
										$d['assessor'] = $assessor;
										$d['tutor'] = $tutor;
										$d['contract'] = DAO::getSingleValue($link, "SELECT title FROM contracts WHERE id = '" . $row['contract_id'] . "'");
										$d['start_date'] = $start_date;
										$d['planned_end_date'] = $end_date;
										$d['actual_end_date'] = $actual_date;
										$d['comp_status'] = $delivery->CompStatus;
										$d['outcome'] = $delivery->Outcome;;
										$d['restart'] = 1;
										$d['original_start_date'] = $original_actual_date;
										$d['fund_remain'] = $delivery->PriorLearnFundAdj;
										$d['tr_id'] = $tr_id;
										$data[] = $d;
									}
								}
							}
						}
					}
				}
			}
		}


		DAO::multipleRowInsert($link, "bil_report", $data);

		DAO::execute($link, "drop table IF EXISTS bil_report2");
		DAO::execute($link, "create table bil_report2 select * from bil_report");
		DAO::execute($link, "UPDATE bil_report SET `status` = 'B' WHERE l03 IN (SELECT l03 FROM  bil_report2 WHERE restart = 0 AND l03 IN (SELECT l03 FROM bil_report2 WHERE restart = 1)) AND `status` IS NULL;");
		DAO::execute($link, "UPDATE bil_report SET `status` = 'L' WHERE restart = 0 AND STATUS IS NULL;");
		DAO::execute($link, "UPDATE bil_report SET `status` = 'R' WHERE restart = 1 AND STATUS IS NULL;");
		DAO::execute($link, "drop table IF EXISTS bil_report2");
		DAO::execute($link, "create table bil_report2 select * from bil_report");

		$view = ViewBreakInLearningReport::getInstance();
		$view->refresh($link, $_REQUEST);

		$export = isset($_REQUEST['export']) ? $_REQUEST['export'] : '';
		if ($export == 'export')
			$this->exportRecordsToExcel($link, $view);
		require_once("tpl_view_bil.php");
	}

	private function exportRecordsToExcel(PDO $link, ViewBreakInLearningReport $view)
	{
		define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

		$view = View::getViewFromSession('view_ViewBreakInLearningReport');
		$columnsToShow = $view->getSelectedColumns($link);

		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');

		$st = $link->query($statement);
		if ($st) {

			$objSpreadsheet = new Spreadsheet();
			// Set document properties
			$objSpreadsheet->getProperties()->setCreator($_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname)
				->setLastModifiedBy($_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname)
				->setTitle("Office 2007 XLSX Test Document")
				->setSubject("Office 2007 XLSX Test Document")
				->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
				->setKeywords("office 2007 openxml php")
				->setCategory("Test result file");

			/*$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Hello')
				->setCellValue('B2', 'world!')
				->setCellValue('C1', 'Hello')
				->setCellValue('D2', 'world!');*/

			$objSpreadsheet->setActiveSheetIndex(0)
				->setCellValue('A1', 'Learner Information')
				->mergeCells("A1:G1")
				->setCellValue('H1', 'Break In Learning')
				->mergeCells("H1:M1")
				->setCellValue('N1', '.')
				->setCellValue('O1', 'Restart')
				->mergeCells("O1:W1")
				->setCellValue('A2', 'L03')
				->setCellValue('B2', 'ULN')
				->setCellValue('C2', 'Surname')
				->setCellValue('D2', 'Forenames')
				->setCellValue('E2', 'Framework Title')
				->setCellValue('F2', 'Assessor')
				->setCellValue('G2', 'Tutor')
				->setCellValue('H2', 'Contract')
				->setCellValue('I2', 'Start Date')
				->setCellValue('J2', 'Planned End Date')
				->setCellValue('K2', 'Actual End Date')
				->setCellValue('L2', 'Completion Status')
				->setCellValue('M2', 'Outcome')
				->setCellValue('N2', '.')
				->setCellValue('O2', 'Restart')
				->setCellValue('P2', 'Contract')
				->setCellValue('Q2', 'Original Start Date')
				->setCellValue('R2', 'Start Date')
				->setCellValue('S2', 'Planned End Date')
				->setCellValue('T2', 'Actual End Date')
				->setCellValue('U2', 'Proportion of Funding Remaining')
				->setCellValue('V2', 'Completion Status')
				->setCellValue('W2', 'Outcome')

			;

			$rowNumber = 3;
			while ($row = $st->fetch()) {
				$colTitle = 'A';
				if ($row['status'] == 'B' && $row['restart'] == 0)
					$arrayForBothHandSides[$row['l03']]['left'] = $row;
				elseif ($row['status'] == 'B' && $row['restart'] == 1)
					$arrayForBothHandSides[$row['l03']]['right'] = $row;
				if ($row['status'] == 'L') {
					$objSpreadsheet->setActiveSheetIndex(0)
						->setCellValue($colTitle . $rowNumber, $row['l03'])
						->setCellValue(++$colTitle . $rowNumber, $row['uln'])
						->setCellValue(++$colTitle . $rowNumber, $row['surname'])
						->setCellValue(++$colTitle . $rowNumber, $row['firstname'])
						->setCellValue(++$colTitle . $rowNumber, $row['framework_title'])
						->setCellValue(++$colTitle . $rowNumber, $row['assessor'])
						->setCellValue(++$colTitle . $rowNumber, $row['tutor'])
						->setCellValue(++$colTitle . $rowNumber, $row['contract'])
						->setCellValue(++$colTitle . $rowNumber, Date::toShort($row['start_date']))
						->setCellValue(++$colTitle . $rowNumber, Date::toShort($row['planned_end_date']))
						->setCellValue(++$colTitle . $rowNumber, Date::toShort($row['actual_end_date']))
						->setCellValue(++$colTitle . $rowNumber, $row['comp_status'])
						->setCellValue(++$colTitle . $rowNumber, $row['outcome'])
						->setCellValue(++$colTitle . $rowNumber, '.')
						->setCellValue(++$colTitle . $rowNumber, '')
						->setCellValue(++$colTitle . $rowNumber, '')
						->setCellValue(++$colTitle . $rowNumber, '')
						->setCellValue(++$colTitle . $rowNumber, '')
						->setCellValue(++$colTitle . $rowNumber, '')
						->setCellValue(++$colTitle . $rowNumber, '')
						->setCellValue(++$colTitle . $rowNumber, '')
						->setCellValue(++$colTitle . $rowNumber, '')
						->setCellValue(++$colTitle . $rowNumber, '')
					;
					$rowNumber++;
				} elseif ($row['status'] == 'R') {
					$restartLabel = $row['restart'] == 1 ? 'Yes' : 'No';
					$objSpreadsheet->setActiveSheetIndex(0)
						->setCellValue($colTitle . $rowNumber, $row['l03'])
						->setCellValue(++$colTitle . $rowNumber, $row['uln'])
						->setCellValue(++$colTitle . $rowNumber, $row['surname'])
						->setCellValue(++$colTitle . $rowNumber, $row['firstname'])
						->setCellValue(++$colTitle . $rowNumber, $row['framework_title'])
						->setCellValue(++$colTitle . $rowNumber, $row['assessor'])
						->setCellValue(++$colTitle . $rowNumber, $row['tutor'])
						->setCellValue(++$colTitle . $rowNumber, '')
						->setCellValue(++$colTitle . $rowNumber, '')
						->setCellValue(++$colTitle . $rowNumber, '')
						->setCellValue(++$colTitle . $rowNumber, '')
						->setCellValue(++$colTitle . $rowNumber, '')
						->setCellValue(++$colTitle . $rowNumber, '')
						->setCellValue(++$colTitle . $rowNumber, '.')
						->setCellValue(++$colTitle . $rowNumber, $restartLabel)
						->setCellValue(++$colTitle . $rowNumber, $row['contract'])
						->setCellValue(++$colTitle . $rowNumber, Date::toShort($row['original_start_date']))
						->setCellValue(++$colTitle . $rowNumber, Date::toShort($row['start_date']))
						->setCellValue(++$colTitle . $rowNumber, Date::toShort($row['planned_end_date']))
						->setCellValue(++$colTitle . $rowNumber, Date::toShort($row['actual_end_date']))
						->setCellValue(++$colTitle . $rowNumber, $row['fund_remain'])
						->setCellValue(++$colTitle . $rowNumber, $row['comp_status'])
						->setCellValue(++$colTitle . $rowNumber, $row['outcome'])
					;
					$rowNumber++;
				}
			}
			if (isset($arrayForBothHandSides)) {
				foreach ($arrayForBothHandSides as $row) {
					$colTitle = 'A';
					$left_side = $row['left'];
					$right_side = $row['right'];
					$restartLabel = $right_side['restart'] == 1 ? 'Yes' : 'No';
					$objSpreadsheet->setActiveSheetIndex(0)
						->setCellValue($colTitle . $rowNumber, $left_side['l03'])
						->setCellValue(++$colTitle . $rowNumber, $left_side['uln'])
						->setCellValue(++$colTitle . $rowNumber, $left_side['surname'])
						->setCellValue(++$colTitle . $rowNumber, $left_side['firstname'])
						->setCellValue(++$colTitle . $rowNumber, $left_side['framework_title'])
						->setCellValue(++$colTitle . $rowNumber, $left_side['assessor'])
						->setCellValue(++$colTitle . $rowNumber, $left_side['tutor'])
						->setCellValue(++$colTitle . $rowNumber, $left_side['contract'])
						->setCellValue(++$colTitle . $rowNumber, Date::toShort($left_side['start_date']))
						->setCellValue(++$colTitle . $rowNumber, Date::toShort($left_side['planned_end_date']))
						->setCellValue(++$colTitle . $rowNumber, Date::toShort($left_side['actual_end_date']))
						->setCellValue(++$colTitle . $rowNumber, $left_side['comp_status'])
						->setCellValue(++$colTitle . $rowNumber, $left_side['outcome'])
						->setCellValue(++$colTitle . $rowNumber, '.')
						->setCellValue(++$colTitle . $rowNumber, $restartLabel)
						->setCellValue(++$colTitle . $rowNumber, $right_side['contract'])
						->setCellValue(++$colTitle . $rowNumber, Date::toShort($right_side['original_start_date']))
						->setCellValue(++$colTitle . $rowNumber, Date::toShort($right_side['start_date']))
						->setCellValue(++$colTitle . $rowNumber, Date::toShort($right_side['planned_end_date']))
						->setCellValue(++$colTitle . $rowNumber, Date::toShort($right_side['actual_end_date']))
						->setCellValue(++$colTitle . $rowNumber, $right_side['fund_remain'])
						->setCellValue(++$colTitle . $rowNumber, $right_side['comp_status'])
						->setCellValue(++$colTitle . $rowNumber, $right_side['outcome'])
					;
					$rowNumber++;
				}
			}
			$objSpreadsheet->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			$objSpreadsheet->getActiveSheet()->getStyle('H1:M1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			$objSpreadsheet->getActiveSheet()->getStyle('O1:W1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			$objSpreadsheet->getActiveSheet()->getStyle('N1:N' . $rowNumber)->applyFromArray(
				array(
					'fill' => array(
						'type' => Fill::FILL_SOLID,
						'color' => array('rgb' => '000000')
					)
				)
			);
			$objSpreadsheet->getActiveSheet()->setTitle('BIL Report');
			foreach (range('A', $objSpreadsheet->getActiveSheet()->getHighestDataColumn()) as $col) {
				$objSpreadsheet->getActiveSheet()
					->getColumnDimension($col)
					->setAutoSize(true);
			}
			$objSpreadsheet->getActiveSheet()->getStyle("A1:W1")->getFont()->setBold(true);
			$objSpreadsheet->getActiveSheet()->getStyle("A2:W2")->getFont()->setBold(true);
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objSpreadsheet->setActiveSheetIndex(0);
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

	public function createTempTable(PDO $link)
	{
		$sql = <<<HEREDOC
CREATE TEMPORARY TABLE `bil_report` (
  `l03` varchar(12) DEFAULT NULL,
  `uln` varchar(10) DEFAULT NULL,
  `surname` varchar(200) DEFAULT NULL,
  `firstname` varchar(200) DEFAULT NULL,
  `framework_title` varchar(500) DEFAULT NULL,
  `assessor` varchar(250) DEFAULT NULL,
  `tutor` varchar(250) DEFAULT NULL,
  `contract` varchar(250) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `planned_end_date` date DEFAULT NULL,
  `actual_end_date` date DEFAULT NULL,
  `comp_status` int(11) DEFAULT NULL,
  `outcome` int(11) DEFAULT NULL,
  `restart` int(11) DEFAULT NULL,
  `original_start_date` date DEFAULT NULL,
  `fund_remain` int(11) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `tr_id` int(11) DEFAULT NULL,
  INDEX(tr_id), INDEX(l03), index(assessor), index(tutor)
) ENGINE 'MEMORY'
HEREDOC;
		DAO::execute($link, $sql);
	}
}
