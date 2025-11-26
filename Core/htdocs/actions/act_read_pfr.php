<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

set_time_limit(0);
ini_set('memory_limit', '2048M');
class read_pfr implements IAction
{
	private $monthsArray;
	private $sunesisTotal = 0;
	private $pfrTotal = 0;
	private $spreadsheet;
	private $similarLearningAims = 0;
	private $differentLearningAims = 0;
	private $extraRecordsInPFR = 0;
	private $extraRecordsInSunesis = 0;
	private $totalLearningAimsInPFR = 0;
	private $totalLearningAimsInSunesis = 0;
	private $pfrLearningAimsPoundValue = 0;
	private $sunesisLearningAimsPoundValue = 0;

	private $color_scheme = array(
		'good' 	=> '#AA4643',
		'ok'	=> '#4572A7',
		'bad'	=> '#89A54E'
	);

	public function execute(PDO $link)
	{
		/**
		 * PhpSpreadsheet
		 *
		 * Copyright (c) 2017 - present PhpSpreadsheet contributors
		 *
		 * Licensed under the MIT License.
		 * For full license details see:
		 * https://opensource.org/licenses/MIT
		 *
		 * @package    PhpSpreadsheet
		 * @copyright  Copyright (c) 2017 - present PhpSpreadsheet contributors
		 * @license    https://opensource.org/licenses/MIT  MIT License
		 * @version    ##VERSION##, ##DATE##
		 */
		gc_enable();
		//echo memory_get_usage() . "<br>";

		require_once('./lib/OLERead.inc');
		require_once('./lib/reader.php');
		require_once('./lib/funding/FundingCore.php');
		require_once('./lib/funding/PeriodLookup.php');
		require_once('./lib/funding/LearnerFunding.php');
		require_once('./lib/funding/FundingPeriod.php');
		require_once('./lib/funding/FundingPrediction.php');
		require_once('./lib/funding/FundingPredictionPeriod.php');
		require_once('./lib/funding/years/FundingCalculator_2013.php');
		require_once('./lib/funding/years/FundingCalculator.php');

		/** Error reporting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Europe/London');

		define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');


		$_SESSION['bc']->add($link, "do.php?_action=read_pfr", "PFR Reconciler");

		$report1 = "";
		$report2 = "";
		$report4 = "";
		$report5 = "";
		$report6 = "";

		if (isset($_POST['contract']) and isset($_POST['submissions'])) {

			if (
				$_FILES['file']['error'] == UPLOAD_ERR_OK               //checks for errors
				&& is_uploaded_file($_FILES['file']['tmp_name'])
			) { //checks that file is uploaded
				$file_handle = fopen($_FILES['file']['tmp_name'], "r");

				if (substr(strrchr($_FILES['file']['name'], '.'), 1) == 'xls') {
					//$csvFile = sys_get_temp_dir() . "\csvFile.tmp";
					$csvFile = DATA_ROOT . '/uploads/' . DB_NAME . '/' . time() . '_toBeDeleted.csv';
					file_put_contents($csvFile, $this->convertToCSV($_FILES['file']['tmp_name']));
					//$this->convertToCSV($_FILES['file']['tmp_name']);
					gc_collect_cycles();
					$file_handle = fopen($csvFile, "r");
				}
			}

			$contract = $_REQUEST['contract'];

			if (intval($_REQUEST['submissions']) < 10)
				$submission = '0' . $_REQUEST['submissions'];
			else
				$submission = $_REQUEST['submissions'];

			$submission = 'W' . $submission;

			$this->prepareMonthsArray();

			//$file_handle = fopen(DATA_ROOT . '/uploads/' . DB_NAME . '/pfrReconciler/' . $path_parts['filename'] . '.csv', "r");


			$this->createTempTable($link, $this->monthsArray);

			$objFundingPredictionPeriod = new FundingPredictionPeriod($link, $contract, 25, "", "", "", $submission, "", "", "", 0);

			//$data = new FundingPredictionPeriod($link, "16", 13, "", "", "", 'W7');
			$data = array();
			try {
				$data = $objFundingPredictionPeriod->get_learnerdata(); //pre($data);
			} catch (Exception $e) {
				echo $e->getMessage();
			}

			gc_collect_cycles();
			$dataTable1InsertQuery = $this->generateInsertSQLQueryForTable1($data);
			$link->query($dataTable1InsertQuery);

			gc_collect_cycles();

			$dataTable2InsertQuery = $this->generateInsertSQLQueryForTable2($file_handle, $this->monthsArray);
			$link->query($dataTable2InsertQuery);

			fclose($file_handle);

			if (substr(strrchr($_FILES['file']['name'], '.'), 1) == 'xls') {
				unlink($csvFile);
			}

			gc_collect_cycles();

			/// Khushnood
			DAO::execute($link, "DROP TABLE IF EXISTS pfr");
			DAO::execute($link, "create table pfr select * from dataTable2");
			//// Khushnood

			if (isset($_REQUEST['export_only'])) {
				$this->spreadsheet = new Spreadsheet();
				// Set document properties
				$this->spreadsheet->getProperties()->setCreator($_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname)
					->setLastModifiedBy($_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname)
					->setTitle("Office 2007 XLSX Test Document")
					->setSubject("Office 2007 XLSX Test Document")
					->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
					->setKeywords("office 2007 openxml php")
					->setCategory("Test result file");

				// output different records
				$sql = "SELECT t1.*, t2.* FROM dataTable1 t1 INNER JOIN dataTable2 t2 ON t1.learning_ref_number_t1 = t2.learning_ref_number_t2
				AND t1.learning_aim_ref_t1 = t2.learning_aim_ref_t2
				AND t1.learner_start_date_t1 = t2.learner_start_date_t2
				AND ( FALSE ";
				foreach ($this->monthsArray as $month) {
					$sql .= " OR FLOOR(t1.{$month}_prog_earned_cash_t1) != FLOOR(t2.{$month}_prog_earned_cash_t2) ";
					$sql .= " OR FLOOR(t1.{$month}_aim_completion_earned_cash_t1) != FLOOR(t2.{$month}_aim_completion_earned_cash_t2) ";
					$sql .= " OR FLOOR(t1.{$month}_bal_earned_cash_t1) != FLOOR(t2.{$month}_bal_earned_cash_t2) ";
					$sql .= " OR FLOOR(ROUND(t1.{$month}_total_t1, 1)) != FLOOR(ROUND(t2.{$month}_total_t2, 1)) ";
				}
				$sql .= ");";
				$st = $link->query($sql);
				$this->exportRecords($link, $st, 0);
				$this->spreadsheet->getActiveSheet()->setTitle('Different Records');
				// create new worksheet and output similar records
				$this->spreadsheet->createSheet(NULL, 1);
				$sql = " SELECT t1.*, t2.* FROM dataTable1 t1 INNER JOIN dataTable2 t2 ON t1.learning_ref_number_t1 = t2.learning_ref_number_t2 AND t1.learning_aim_ref_t1 = t2.learning_aim_ref_t2
				AND t1.learner_start_date_t1 = t2.learner_start_date_t2 AND ( TRUE ";
				foreach ($this->monthsArray as $month) {
					$sql .= " AND FLOOR(t1.{$month}_prog_earned_cash_t1) = FLOOR(t2.{$month}_prog_earned_cash_t2) ";
					$sql .= " AND FLOOR(t1.{$month}_aim_completion_earned_cash_t1) = FLOOR(t2.{$month}_aim_completion_earned_cash_t2) ";
					$sql .= " AND FLOOR(t1.{$month}_bal_earned_cash_t1) = FLOOR(t2.{$month}_bal_earned_cash_t2) ";
					$sql .= " AND FLOOR(ROUND(t1.{$month}_total_t1, 1)) = FLOOR(ROUND(t2.{$month}_total_t2, 1)) ";
				}
				$sql .= ");";
				$st = $link->query($sql);
				$this->exportRecords($link, $st, 1);
				$this->spreadsheet->getActiveSheet()->setTitle('Similar Records');
				//create new worksheet for no funding in sunesis
				$this->spreadsheet->createSheet(NULL, 2);
				$sql = "SELECT t2.* FROM dataTable2 t2
				WHERE t2.learning_ref_number_t2 NOT IN (SELECT t1.learning_ref_number_t1 FROM dataTable1 t1 GROUP BY t1.learning_ref_number_t1)
				AND t2.learning_ref_number_t2 != 'Unique learner number'
				AND t2.learning_ref_number_t2 != ''
				AND t2.learning_aim_ref_t2 != 'ZPROG001' ";
				$st = $link->query($sql);
				$this->exportRecords($link, $st, 2);
				$this->spreadsheet->getActiveSheet()->setTitle('No Funding in Sunesis');
				//create new worksheet for no funding in PFR
				$this->spreadsheet->createSheet(NULL, 3);
				$sql = "SELECT t1.* FROM dataTable1 t1
				WHERE t1.learning_ref_number_t1 NOT IN (SELECT t2.learning_ref_number_t2 FROM dataTable2 t2 GROUP BY t2.learning_ref_number_t2)
				AND t1.learning_ref_number_t1 != 'Unique learner number'
				AND t1.learning_ref_number_t1 != ''
				AND t1.learning_aim_ref_t1 != 'ZPROG001' ";
				$st = $link->query($sql);
				$this->exportRecords($link, $st, 3);
				$this->spreadsheet->getActiveSheet()->setTitle('No Funding in PFR');
				//
				$this->spreadsheet->setActiveSheetIndex(0);
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
				exit;
			} else {
				$report2 = $this->renderDifferentRecords($link);
				$report1 = $this->renderSimilarRecords($link);
				$report4 = $this->renderExtraRecordsInPFR($link);
				$report5 = $this->renderExtraRecordsInSunesis($link);
				//$report6 = $this->renderRecordsWithNoLearningAimsButWithFunding($link);

				$sql = " SELECT DISTINCT t1.`learning_aim_ref_t1` FROM dataTable1 t1 INNER JOIN dataTable2 t2 ON t1.learning_ref_number_t1 = t2.learning_ref_number_t2 AND t1.learning_aim_ref_t1 = t2.learning_aim_ref_t2
				AND t1.learner_start_date_t1 = t2.learner_start_date_t2 AND ( TRUE ";
				foreach ($this->monthsArray as $month) {
					$sql .= " AND FLOOR(t1.{$month}_prog_earned_cash_t1) = FLOOR(t2.{$month}_prog_earned_cash_t2) ";
					$sql .= " AND FLOOR(t1.{$month}_aim_completion_earned_cash_t1) = FLOOR(t2.{$month}_aim_completion_earned_cash_t2) ";
					$sql .= " AND FLOOR(t1.{$month}_bal_earned_cash_t1) = FLOOR(t2.{$month}_bal_earned_cash_t2) ";
					$sql .= " AND FLOOR(ROUND(t1.{$month}_total_t1, 1)) = FLOOR(ROUND(t2.{$month}_total_t2, 1)) ";
					$sql .= " AND FLOOR(t1.{$month}_learning_support_earned_cash_t1) = FLOOR(t2.{$month}_learning_support_earned_cash_t2) ";
					$sql .= " AND FLOOR(t1.{$month}_eng_math_on_prog_earned_cash_t1) = FLOOR(t2.{$month}_eng_math_on_prog_earned_cash_t2) ";
					$sql .= " AND FLOOR(t1.{$month}_eng_math_bal_earned_cash_t1) = FLOOR(t2.{$month}_eng_math_bal_earned_cash_t2) ";
					$sql .= " AND FLOOR(t1.{$month}_disadvantage_earned_cash_t1) = FLOOR(t2.{$month}_disadvantage_earned_cash_t2) ";
					$sql .= " AND FLOOR(t1.{$month}_1618_additional_emp_t1) = FLOOR(t2.{$month}_1618_additional_emp_t2) ";
					$sql .= " AND FLOOR(t1.{$month}_1618_additional_prov_t1) = FLOOR(t2.{$month}_1618_additional_prov_t2) ";
					$sql .= " AND FLOOR(t1.{$month}_1618_fw_uplift_opp_t1) = FLOOR(t2.{$month}_1618_fw_uplift_opp_t2) ";
					$sql .= " AND FLOOR(t1.{$month}_1618_fw_uplift_bal_t1) = FLOOR(t2.{$month}_1618_fw_uplift_bal_t2) ";
					$sql .= " AND FLOOR(t1.{$month}_1618_fw_uplift_comp_t1) = FLOOR(t2.{$month}_1618_fw_uplift_comp_t2) ";
				}
				$sql .= "); ";
				//$st = $link->query($sql);
				//$this->similarLearningAims = $st->rowCount();

				$sql = "SELECT DISTINCT t1.`learning_aim_ref_t1` FROM dataTable1 t1 INNER JOIN dataTable2 t2 ON t1.learning_ref_number_t1 = t2.learning_ref_number_t2
				AND t1.learning_aim_ref_t1 = t2.learning_aim_ref_t2
				AND t1.learner_start_date_t1 = t2.learner_start_date_t2
				AND ( FALSE ";
				foreach ($this->monthsArray as $month) {
					$sql .= " OR FLOOR(t1.{$month}_prog_earned_cash_t1) != FLOOR(t2.{$month}_prog_earned_cash_t2) ";
					$sql .= " OR FLOOR(t1.{$month}_aim_completion_earned_cash_t1) != FLOOR(t2.{$month}_aim_completion_earned_cash_t2) ";
					$sql .= " OR FLOOR(t1.{$month}_bal_earned_cash_t1) != FLOOR(t2.{$month}_bal_earned_cash_t2) ";
					$sql .= " OR FLOOR(ROUND(t1.{$month}_total_t1, 1)) != FLOOR(ROUND(t2.{$month}_total_t2, 1)) ";
					$sql .= " OR FLOOR(t1.{$month}_learning_support_earned_cash_t1) != FLOOR(t2.{$month}_learning_support_earned_cash_t2) ";
					$sql .= " AND FLOOR(t1.{$month}_eng_math_on_prog_earned_cash_t1) != FLOOR(t2.{$month}_eng_math_on_prog_earned_cash_t2) ";
					$sql .= " AND FLOOR(t1.{$month}_eng_math_bal_earned_cash_t1) != FLOOR(t2.{$month}_eng_math_bal_earned_cash_t2) ";
					$sql .= " AND FLOOR(t1.{$month}_disadvantage_earned_cash_t1) != FLOOR(t2.{$month}_disadvantage_earned_cash_t2) ";
					$sql .= " AND FLOOR(t1.{$month}_1618_additional_emp_t1) != FLOOR(t2.{$month}_1618_additional_emp_t2) ";
					$sql .= " AND FLOOR(t1.{$month}_1618_additional_prov_t1) != FLOOR(t2.{$month}_1618_additional_prov_t2) ";
					$sql .= " AND FLOOR(t1.{$month}_1618_fw_uplift_opp_t1) = FLOOR(t2.{$month}_1618_fw_uplift_opp_t2) ";
					$sql .= " AND FLOOR(t1.{$month}_1618_fw_uplift_bal_t1) = FLOOR(t2.{$month}_1618_fw_uplift_bal_t2) ";
					$sql .= " AND FLOOR(t1.{$month}_1618_fw_uplift_comp_t1) = FLOOR(t2.{$month}_1618_fw_uplift_comp_t2) ";
				}
				$sql .= "); ";
				//$st = $link->query($sql);
				//$this->differentLearningAims = $st->rowCount();

				$sql = "SELECT DISTINCT t2.learning_aim_ref_t2 FROM dataTable2 t2
				WHERE t2.learning_ref_number_t2 NOT IN (SELECT t1.learning_ref_number_t1 FROM dataTable1 t1 GROUP BY t1.learning_ref_number_t1)
				AND t2.learning_ref_number_t2 != 'Unique learner number'
				AND t2.learning_ref_number_t2 != ''
				AND t2.learning_aim_ref_t2 != 'ZPROG001' ";
				$st = $link->query($sql);
				$this->extraRecordsInPFR = $st->rowCount();

				$sql = "SELECT DISTINCT t1.learning_aim_ref_t1 FROM dataTable1 t1
				WHERE t1.learning_ref_number_t1 NOT IN (SELECT t2.learning_ref_number_t2 FROM dataTable2 t2 WHERE t2.`learning_aim_ref_t2` != '' GROUP BY t2.learning_ref_number_t2)
				AND t1.learning_ref_number_t1 != 'Unique learner number'
				AND t1.learning_ref_number_t1 != ''
				AND t1.learning_aim_ref_t1 != 'ZPROG001' ";
				//$st = $link->query($sql);
				//$this->extraRecordsInSunesis = $st->rowCount();

				$sql = "SELECT DISTINCT t1.`learning_aim_ref_t1` FROM dataTable1 t1 ";
				$st = $link->query($sql);
				//$this->totalLearningAimsInSunesis = $st->rowCount();

				$sql = "SELECT DISTINCT t2.`learning_aim_ref_t2` FROM dataTable2 t2 WHERE t2.`learning_aim_ref_t2` != 'ZPROG001' AND t2.`learning_aim_ref_t2` != '' AND t2.`learning_aim_ref_t2` != 'Learning aim reference' ";
				$st = $link->query($sql);
				//$this->totalLearningAimsInPFR = $st->rowCount();

				$sql = "SELECT DISTINCT t1.`learning_aim_ref_t1` FROM dataTable1 t1 WHERE t1.grand_total_t1 != '0.00' ";
				//$st = $link->query($sql);
				//$this->sunesisLearningAimsPoundValue = $st->rowCount();

				$sql = "SELECT DISTINCT t2.`learning_aim_ref_t2` FROM dataTable2 t2 WHERE t2.grand_total_t2 != '0.00' ";
				//$st = $link->query($sql);
				//$this->pfrLearningAimsPoundValue = $st->rowCount();

				$data = array();
				$labels = array();

				$data[0] = $this->sunesisTotal;
				$data[1] = $this->pfrTotal;

				$labels[0] = "Sunesis Total Amount";
				$labels[1] = "PFR Total Amount";
			}
		}
		require_once('tpl_read_pfr.php');
	} //end function getInstance()

	private function convertToCSV($inputFile)
	{
		$excel = new Spreadsheet_Excel_Reader();
		$output = "";
		$excel->read($inputFile);
		$x = 1;
		while ($x <= $excel->sheets[0]['numRows']) {
			//echo "\t<tr>\n";

			$y = 1;
			while ($y <= $excel->sheets[0]['numCols']) {
				$cell = isset($excel->sheets[0]['cells'][$x][$y]) ? $excel->sheets[0]['cells'][$x][$y] : '';
				//echo "\t\t<td>$cell</td>\n";
				$output .= $cell . ",";
				$y++;
			}
			//echo "\t</tr>\n";
			$output .= "\n";
			$x++;
		}
		//echo $output;
		return $output;
	}

	private function prepareMonthsArray()
	{
		$this->monthsArray = array();

		//months array to pick up the columns from input CSV file
		$this->monthsArray[] = "August";
		$this->monthsArray[] = "September";
		$this->monthsArray[] = "October";
		$this->monthsArray[] = "November";
		$this->monthsArray[] = "December";
		$this->monthsArray[] = "January";
		$this->monthsArray[] = "February";
		$this->monthsArray[] = "March";
		$this->monthsArray[] = "April";
		$this->monthsArray[] = "May";
		$this->monthsArray[] = "June";
		$this->monthsArray[] = "July";
	} // end function prepareMonthsArray()


	private function generateInsertSQLQueryForTable1($data)
	{
		$dataTable1InsertQuery = "INSERT INTO dataTable1 VALUES ";
		for ($i = 0; $i < count($data); $i++) {
			$grandTotal = "";

			$dataTable1InsertQuery .= "(";
			$dataTable1InsertQuery .= "'" . $data[$i]['uln'] . "', ";
			$dataTable1InsertQuery .= "'" . trim($data[$i]['qualification_title']) . "', ";
			$date_start_date = new Date($data[$i]['learner_start_date']);
			$dataTable1InsertQuery .= "'" . $date_start_date->formatMySQL() . "', ";

			$ii = 1;
			foreach ($this->monthsArray as $month) {
				$data[$i]['P' . $ii . '_total'] = floatval($data[$i]['P' . $ii . '_total']) - (floatval($data[$i]['P' . $ii . '_ALS'])
					+ floatval($data[$i]['P' . $ii . '_EM_OPP'])
					+ floatval($data[$i]['P' . $ii . '_EM_Bal'])
					+ floatval($data[$i]['P' . $ii . '_FM36_Disadv'])
					+ floatval($data[$i]['P' . $ii . '_1618_Pro_Inc'])
					+ floatval($data[$i]['P' . $ii . '_1618_Emp_Inc'])
				);
				$dataTable1InsertQuery .= floatval($data[$i]['P' . $ii . '_OPP']) . ", ";
				$dataTable1InsertQuery .= floatval($data[$i]['P' . $ii . '_ach']) . ", ";
				$dataTable1InsertQuery .= floatval($data[$i]['P' . $ii . '_bal']) . ", ";

				$dataTable1InsertQuery .= floatval($data[$i]['P' . $ii . '_total']) . " ,";
				$grandTotal = $grandTotal + floatval($data[$i]['P' . $ii . '_total']);

				$dataTable1InsertQuery .= floatval($data[$i]['P' . $ii . '_ALS']) . ", ";
				$dataTable1InsertQuery .= floatval($data[$i]['P' . $ii . '_EM_OPP']) . ", ";
				$dataTable1InsertQuery .= floatval($data[$i]['P' . $ii . '_EM_Bal']) . ", ";
				$dataTable1InsertQuery .= floatval($data[$i]['P' . $ii . '_FM36_Disadv']) . ", ";
				$dataTable1InsertQuery .= floatval($data[$i]['P' . $ii . '_1618_Pro_Inc']) . ", ";
				$dataTable1InsertQuery .= floatval($data[$i]['P' . $ii . '_1618_Emp_Inc']) . ", ";
				$dataTable1InsertQuery .= floatval($data[$i]['P' . $ii . '_1618_FW_Uplift_OPP']) . ", ";
				$dataTable1InsertQuery .= floatval($data[$i]['P' . $ii . '_1618_FW_Uplift_Bal']) . ", ";
				$dataTable1InsertQuery .= floatval($data[$i]['P' . $ii . '_1618_FW_Uplift_Comp']) . ", ";
				$ii++;
			}
			$dataTable1InsertQuery .= $grandTotal . "";
			if ($i == count($data) - 1)
				$dataTable1InsertQuery .= ")";
			else
				$dataTable1InsertQuery .= "),";
		}
		//$dataTable1InsertQuery .= ";";
		$dataTable1InsertQuery .= ";";
		return $dataTable1InsertQuery;
	} // end function generateInsertSQLQueryForTable1()

	private function generateInsertSQLQueryForTable2($file_handle, $monthsArray)
	{
		$indexArray = array(); // this array stores the indexes for each filed to be read from the input file
		$firstLine = true; // to take care of first line to read the headers
		$found = false; // boolean variable which is set to true when the headers are found inside the file

		$dataTable2InsertQuery = "INSERT INTO dataTable2 VALUES ";

		while (!feof($file_handle)) {
			$grandTotal = "";

			//start reading the file line by line
			$line_of_text = fgetcsv($file_handle);

			if (!$found and is_array($line_of_text) and (!in_array('Learner reference number', $line_of_text)) and (!in_array('LearnRefNumber', $line_of_text)) and (!in_array('L03', $line_of_text))) { // continue skipping until found the headers
				//var_dump("skipping");
				continue;
			}
			$found = true;

			if ($firstLine) // get the indexes for each field
			{
				for ($z = 0; $z < count($line_of_text); $z++) {
					if ($line_of_text[$z] == 'Unique learner number' || strtolower($line_of_text[$z]) == 'uln')
						$indexArray['unique_learner_number'] = $z;
					elseif ($line_of_text[$z] == 'Provider specified learner monitoring (A)')
						$indexArray['provider_specified_learner_monitoring_a'] = $z;
					elseif ($line_of_text[$z] == 'Provider specified learner monitoring (B)')
						$indexArray['provider_specified_learner_monitoring_b'] = $z;
					elseif ($line_of_text[$z] == 'Learning aim reference')
						$indexArray['learning_aim_ref'] = $z;
					elseif ($line_of_text[$z] == 'Learning start date')
						$indexArray['learner_start_date'] = $z;
					foreach ($monthsArray as $month) {
						if (strtolower($line_of_text[$z]) == strtolower($month . ' On Programme Earnings'))
							$indexArray[$month . '_prog_earned_cash'] = $z;
						elseif (strtolower($line_of_text[$z]) == strtolower($month . ' Aim Completion Earnings') || strtolower($line_of_text[$z]) == strtolower($month . ' Aim Achievement Earnings'))
							$indexArray[$month . '_aim_completion_earned_cash'] = $z;
						elseif (strtolower($line_of_text[$z]) == strtolower($month . ' Balancing Payment Earnings'))
							$indexArray[$month . '_bal_earned_cash'] = $z;
						elseif (strtolower($line_of_text[$z]) == strtolower($month . ' Learning Support Earnings'))
							$indexArray[$month . '_learning_support_earned_cash'] = $z;
						elseif (strtolower($line_of_text[$z]) == strtolower($month . ' English and maths on programme Earnings'))
							$indexArray[$month . '_eng_math_on_prog_earned_cash'] = $z;
						elseif (strtolower($line_of_text[$z]) == strtolower($month . ' English and maths balancing payment Earnings'))
							$indexArray[$month . '_eng_math_bal_earned_cash'] = $z;
						elseif (strtolower($line_of_text[$z]) == strtolower($month . ' disadvantage Earnings'))
							$indexArray[$month . '_disadvantage_earned_cash'] = $z;
						elseif (strtolower($line_of_text[$z]) == strtolower($month . ' 16-18 additional payments for employers'))
							$indexArray[$month . '_1618_additional_emp'] = $z;
						elseif (strtolower($line_of_text[$z]) == strtolower($month . ' 16-18 additional payments for providers'))
							$indexArray[$month . '_1618_additional_prov'] = $z;
						elseif (strtolower($line_of_text[$z]) == strtolower($month . ' 16-18 framework uplift on programme payment'))
							$indexArray[$month . '_1618_fw_uplift_opp'] = $z;
						elseif (strtolower($line_of_text[$z]) == strtolower($month . ' 16-18 framework uplift balancing payment'))
							$indexArray[$month . '_1618_fw_uplift_bal'] = $z;
						elseif (strtolower($line_of_text[$z]) == strtolower($month . ' 16-18 framework uplift completion payment'))
							$indexArray[$month . '_1618_fw_uplift_comp'] = $z;
					}
				}
				//pr(count(array_keys($indexArray)));
				// Error Checking to verify that all the required fields are there in the input file
				if (count(array_keys($indexArray)) != 149) {
					$missingFields = array();
					if (!array_key_exists('unique_learner_number', $indexArray))
						$missingFields[] = 'Unique Learner Number';
					if (!array_key_exists('learning_aim_ref', $indexArray))
						$missingFields[] = 'Learning Aim Reference';
					if (!array_key_exists('learner_start_date', $indexArray))
						$missingFields[] = 'Learning Start Date';
					foreach ($monthsArray as $month) {
						if (!array_key_exists($month . '_prog_earned_cash', $indexArray))
							$missingFields[] = $month . ' On Programme Earnings';
						if (!array_key_exists($month . '_aim_completion_earned_cash', $indexArray))
							$missingFields[] = $month . ' Aim Completion Earnings';
						if (!array_key_exists($month . '_bal_earned_cash', $indexArray))
							$missingFields[] = $month . ' Balancing Payment Earnings';
						if (!array_key_exists($month . '_learning_support_earned_cash', $indexArray))
							$missingFields[] = $month . ' Learning Support Earnings';
						if (!array_key_exists($month . '_eng_math_on_prog_earned_cash', $indexArray))
							$missingFields[] = $month . ' English and maths on programme Earnings';
						if (!array_key_exists($month . '_eng_math_bal_earned_cash', $indexArray))
							$missingFields[] = $month . ' English and maths balancing payment Earnings';
						if (!array_key_exists($month . '_disadvantage_earned_cash', $indexArray))
							$missingFields[] = $month . ' disadvantage Earnings';
						if (!array_key_exists($month . '_1618_additional_emp', $indexArray))
							$missingFields[] = $month . ' 16-18 additional payments for employers';
						if (!array_key_exists($month . '_1618_additional_prov', $indexArray))
							$missingFields[] = $month . ' 16-18 additional payments for providers';
						if (!array_key_exists($month . '_1618_fw_uplift_opp', $indexArray))
							$missingFields[] = $month . ' 16-18 fw uplift opp';
						if (!array_key_exists($month . '_1618_fw_uplift_bal', $indexArray))
							$missingFields[] = $month . ' 16-18 fw uplift bal';
						if (!array_key_exists($month . '_1618_fw_uplift_comp', $indexArray))
							$missingFields[] = $month . ' 16-18 fw uplift comp';
					}

					echo "<br>Error: The input file misses following required fields<br>";
					foreach ($missingFields as $missingField)
						echo "'{$missingField}'<br>";
					exit(0);
				}
				$firstLine = false;
			}

			try {
				$dataTable2InsertQuery .= "(";
				//start filling the output array with data from the input file
				$dataTable2InsertQuery .= "'" . $line_of_text[$indexArray['unique_learner_number']] . "', ";
				$dataTable2InsertQuery .= "'" . $line_of_text[$indexArray['provider_specified_learner_monitoring_a']] . "', ";
				$dataTable2InsertQuery .= "'" . $line_of_text[$indexArray['provider_specified_learner_monitoring_b']] . "', ";
				$dataTable2InsertQuery .= "'" . $line_of_text[$indexArray['learning_aim_ref']] . "', ";
				if ($line_of_text[$indexArray['learner_start_date']] == 'Learning start date')
					$dataTable2InsertQuery .= "'', ";
				else {
					$matches = array();
					preg_match('/(\d{2})-(\d{2})-(\d{2}) \d{1}:\d{2}/', $line_of_text[$indexArray['learner_start_date']], $matches); // check if format is 02-05-14 0:00
					if (count($matches) > 0) {
						$date_start_date = DateTime::createFromFormat('d-m-y h:i', $line_of_text[$indexArray['learner_start_date']]);
						$dataTable2InsertQuery .= "'" . $date_start_date->format('Y-m-d') . "', ";
					} else {
						preg_match('/(\d{2})-(\d{2})-(\d{2})/', $line_of_text[$indexArray['learner_start_date']], $matches); // check if format is 02-05-14
						if (count($matches) > 0) {
							$date_start_date = DateTime::createFromFormat('d-m-y', $line_of_text[$indexArray['learner_start_date']]);
							$dataTable2InsertQuery .= "'" . $date_start_date->format('Y-m-d') . "', ";
						} else {
							preg_match('/(\d{2})-(\D*)-(\d{2})/', $line_of_text[$indexArray['learner_start_date']], $matches); // check if format is 02-May-14 00:00:00 AM
							if (count($matches) > 0) {
								$date_start_date = DateTime::createFromFormat('d-M-y h:i:s A', $line_of_text[$indexArray['learner_start_date']]);
								$dataTable2InsertQuery .= "'" . $date_start_date->format('Y-m-d') . "', ";
							} else {
								preg_match('/(\d{2})\/(\d{2})\/(\d{4}) \d{2}:\d{2}/', $line_of_text[$indexArray['learner_start_date']], $matches); // check if format is 02/05/2014 00:00
								if (count($matches) > 0) {
									$date_start_date = DateTime::createFromFormat('d/m/Y H:i', $line_of_text[$indexArray['learner_start_date']]);
									$dataTable2InsertQuery .= "'" . $date_start_date->format('Y-m-d') . "', ";
								} else {
									preg_match('/(\d{2})\/(\d{2})\/(\d{4})/', $line_of_text[$indexArray['learner_start_date']], $matches); // check if format is 02/05/2014
									if (count($matches) > 0) {
										$date_start_date = DateTime::createFromFormat('d/m/Y', $line_of_text[$indexArray['learner_start_date']]);
										$dataTable2InsertQuery .= "'" . $date_start_date->format('Y-m-d') . "', ";
									} else {
										preg_match('/(\d{2})\/(\d{2})\/(\d{2})/', $line_of_text[$indexArray['learner_start_date']], $matches); // check if format is 02/05/14
										if (count($matches) > 0) {
											$date_start_date = DateTime::createFromFormat('d/m/y', $line_of_text[$indexArray['learner_start_date']]);
											$dataTable2InsertQuery .= "'" . $date_start_date->format('Y-m-d') . "', ";
										} else
											$dataTable2InsertQuery .= "'', ";
									}
								}
							}
						}
					}
				}

				foreach ($monthsArray as $month) {
					$dataTable2InsertQuery .= floatval(preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_prog_earned_cash']])) . ", ";
					$dataTable2InsertQuery .= floatval(preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_aim_completion_earned_cash']])) . ", ";
					$dataTable2InsertQuery .= floatval(preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_bal_earned_cash']])) . ", ";

					$value1 = preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_prog_earned_cash']]);
					$value2 = preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_aim_completion_earned_cash']]);
					$value3 = preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_bal_earned_cash']]);
					$value4 = preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_learning_support_earned_cash']]);
					$value5 = preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_eng_math_on_prog_earned_cash']]);
					$value6 = preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_eng_math_bal_earned_cash']]);
					$value7 = preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_disadvantage_earned_cash']]);
					$value8 = preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_1618_additional_emp']]);
					$value9 = preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_1618_additional_prov']]);
					$value10 = preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_1618_fw_uplift_opp']]);
					$value11 = preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_1618_fw_uplift_bal']]);
					$value12 = preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_1618_fw_uplift_comp']]);

					$dataTable2InsertQuery .= floatval($value1) + floatval($value2) + floatval($value3) . ", ";

					$grandTotal = $grandTotal + floatval($value1) + floatval($value2) + floatval($value3);
					$grandTotal = $grandTotal + floatval($value4) + floatval($value5) + floatval($value6);
					$grandTotal = $grandTotal + floatval($value7) + floatval($value8) + floatval($value9);
					$grandTotal = $grandTotal + floatval($value10) + floatval($value11) + floatval($value12);

					$dataTable2InsertQuery .= floatval(preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_learning_support_earned_cash']])) . ", ";
					$dataTable2InsertQuery .= floatval(preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_eng_math_on_prog_earned_cash']])) . ", ";
					$dataTable2InsertQuery .= floatval(preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_eng_math_bal_earned_cash']])) . ", ";
					$dataTable2InsertQuery .= floatval(preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_disadvantage_earned_cash']])) . ", ";
					$dataTable2InsertQuery .= floatval(preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_1618_additional_emp']])) . ", ";
					$dataTable2InsertQuery .= floatval(preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_1618_additional_prov']])) . ", ";
					$dataTable2InsertQuery .= floatval(preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_1618_fw_uplift_opp']])) . ", ";
					$dataTable2InsertQuery .= floatval(preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_1618_fw_uplift_bal']])) . ", ";
					$dataTable2InsertQuery .= floatval(preg_replace("/[^0-9a-zA-Z. ]/", "", $line_of_text[$indexArray[$month . '_1618_fw_uplift_comp']])) . ", ";
				}
			} catch (Exception $e) {
				echo "Error: " . $e->getMessage();
				exit(0);
			}
			$dataTable2InsertQuery .= $grandTotal . "),";
			//$dataTable2InsertQuery .= "),";
		}
		$dataTable2InsertQuery = substr($dataTable2InsertQuery, 0, -1);
		$dataTable2InsertQuery .= ";"; //pre($dataTable2InsertQuery);

		return $dataTable2InsertQuery;
	} // end function generateInsertSQLQueryForTable2()

	public function exportRecords(PDO $link, $st, $index)
	{
		if ($st) {
			$this->spreadsheet->setActiveSheetIndex($index)
				->setCellValue('A1', '')
				->setCellValue('B1', 'ULN')
				->setCellValue('C1', 'Learning Aim');
			$col = "E";
			foreach ($this->monthsArray as $month) {
				$this->spreadsheet->setActiveSheetIndex($index)
					->setCellValue(++$col . '1', $month . ' On Program Earned Cash')
					->setCellValue(++$col . '1', $month . ' Aim Completion Earned Cash')
					->setCellValue(++$col . '1', $month . ' Balancing Payment Earned Cash')
					->setCellValue(++$col . '1', $month . ' Total')
					->setCellValue(++$col . '1', $month . ' Learning Support Earned Cash')
					->setCellValue(++$col . '1', $month . ' English and Maths On Programme Earned Cash')
					->setCellValue(++$col . '1', $month . ' English and Maths Balancing Payment Earned Cash')
					->setCellValue(++$col . '1', $month . ' Disadvantage Earned Cash')
					->setCellValue(++$col . '1', $month . ' 16-18 Additional Payments for Employers')
					->setCellValue(++$col . '1', $month . ' 16-18 Additional Payments for Providers')
					->setCellValue(++$col . '1', $month . ' 16-18 Framework Uplift On Programme Payment')
					->setCellValue(++$col . '1', $month . ' 16-18 Framework Uplift Balancing Payment')
					->setCellValue(++$col . '1', $month . ' 16-18 Framework Uplift Completion Payment');
			}
			$this->spreadsheet->setActiveSheetIndex($index)->setCellValue(++$col . '1', 'Grand Total');
			$r = 1;
			while ($row = $st->fetch()) {
				if ($index == 0 || $index == 1 || $index == 3) {
					$col = 'A';
					$r++;
					$this->spreadsheet->setActiveSheetIndex($index)->setCellValue($col . $r, 'Sunesis');
					$this->spreadsheet->setActiveSheetIndex($index)
						->setCellValue(++$col . $r, $row['learning_ref_number_t1'])
						->setCellValue(++$col . $r, $row['learning_aim_ref_t1']);

					foreach ($this->monthsArray as $month) {
						$this->spreadsheet->setActiveSheetIndex($index)
							->setCellValue(++$col . $r, $row[$month . '_prog_earned_cash_t1'])
							->setCellValue(++$col . $r, $row[$month . '_aim_completion_earned_cash_t1'])
							->setCellValue(++$col . $r, $row[$month . '_bal_earned_cash_t1'])
							->setCellValue(++$col . $r, $row[$month . '_total_t1'])
							->setCellValue(++$col . $r, $row[$month . '_learning_support_earned_cash_t1'])
							->setCellValue(++$col . $r, $row[$month . '_eng_math_on_prog_earned_cash_t1'])
							->setCellValue(++$col . $r, $row[$month . '_eng_math_bal_earned_cash_t1'])
							->setCellValue(++$col . $r, $row[$month . '_disadvantage_earned_cash_t1'])
							->setCellValue(++$col . $r, $row[$month . '_1618_additional_emp_t1'])
							->setCellValue(++$col . $r, $row[$month . '_1618_additional_prov_t1'])
							->setCellValue(++$col . $r, $row[$month . '_1618_fw_uplift_opp_t1'])
							->setCellValue(++$col . $r, $row[$month . '_1618_fw_uplift_bal_t1'])
							->setCellValue(++$col . $r, $row[$month . '_1618_fw_uplift_comp_t1']);

						$this->sunesisTotal += floatval($row[$month . '_prog_earned_cash_t1'])
							+ floatval($row[$month . '_aim_completion_earned_cash_t1'])
							+ floatval($row[$month . '_bal_earned_cash_t1'])
							+ floatval($row[$month . '_learning_support_earned_cash_t1'])
							+ floatval($row[$month . '_eng_math_on_prog_earned_cash_t1'])
							+ floatval($row[$month . '_eng_math_bal_earned_cash_t1'])
							+ floatval($row[$month . '_disadvantage_earned_cash_t1'])
							+ floatval($row[$month . '_1618_additional_emp_t1'])
							+ floatval($row[$month . '_1618_additional_prov_t1'])
							+ floatval($row[$month . '_1618_fw_uplift_opp_t1'])
							+ floatval($row[$month . '_1618_fw_uplift_bal_t1'])
							+ floatval($row[$month . '_1618_fw_uplift_comp_t1']);
					} //end foreach
					$this->spreadsheet->setActiveSheetIndex($index)->setCellValue(++$col . $r, $row['grand_total_t1']);
				}
				if ($index == 0 || $index == 1 || $index == 2) {
					$col = "A";
					$r++;
					$this->spreadsheet->setActiveSheetIndex($index)->setCellValue($col . $r, 'PFR');
					$this->spreadsheet->setActiveSheetIndex($index)
						->setCellValue(++$col . $r, $row['learning_ref_number_t2'])
						->setCellValue(++$col . $r, $row['learning_aim_ref_t2']);
					foreach ($this->monthsArray as $month) {
						$this->spreadsheet->setActiveSheetIndex($index)
							->setCellValue(++$col . $r, $row[$month . '_prog_earned_cash_t2'])
							->setCellValue(++$col . $r, $row[$month . '_aim_completion_earned_cash_t2'])
							->setCellValue(++$col . $r, $row[$month . '_bal_earned_cash_t2'])
							->setCellValue(++$col . $r, $row[$month . '_total_t2'])
							->setCellValue(++$col . $r, $row[$month . '_learning_support_earned_cash_t2'])
							->setCellValue(++$col . $r, $row[$month . '_eng_math_on_prog_earned_cash_t2'])
							->setCellValue(++$col . $r, $row[$month . '_eng_math_on_prog_earned_cash_t2'])
							->setCellValue(++$col . $r, $row[$month . '_disadvantage_earned_cash_t2'])
							->setCellValue(++$col . $r, $row[$month . '_1618_additional_emp_t2'])
							->setCellValue(++$col . $r, $row[$month . '_1618_additional_prov_t2'])
							->setCellValue(++$col . $r, $row[$month . '_1618_fw_uplift_opp_t2'])
							->setCellValue(++$col . $r, $row[$month . '_1618_fw_uplift_bal_t2'])
							->setCellValue(++$col . $r, $row[$month . '_1618_fw_uplift_comp_t2']);

						$this->pfrTotal += floatval($row[$month . '_prog_earned_cash_t2'])
							+ floatval($row[$month . '_aim_completion_earned_cash_t2'])
							+ floatval($row[$month . '_bal_earned_cash_t2'])
							+ floatval($row[$month . '_learning_support_earned_cash_t2'])
							+ floatval($row[$month . '_eng_math_on_prog_earned_cash_t2'])
							+ floatval($row[$month . '_eng_math_bal_earned_cash_t2'])
							+ floatval($row[$month . '_disadvantage_earned_cash_t2'])
							+ floatval($row[$month . '_1618_additional_emp_t2'])
							+ floatval($row[$month . '_1618_additional_prov_t2'])
							+ floatval($row[$month . '_1618_fw_uplift_opp_t2'])
							+ floatval($row[$month . '_1618_fw_uplift_bal_t2'])
							+ floatval($row[$month . '_1618_fw_uplift_comp_t2']);
					} //end foreach
					$this->spreadsheet->setActiveSheetIndex($index)->setCellValue(++$col . $r, $row['grand_total_t2']);
				}
			}
		} else {
			pre($link->errorInfo());
		}
	}

	public function renderDifferentRecords(PDO $link)
	{

		$sql = "SELECT t1.*, t2.* FROM dataTable1 t1 INNER JOIN dataTable2 t2 ON t1.learning_ref_number_t1 = t2.learning_ref_number_t2
				AND t1.learning_aim_ref_t1 = t2.learning_aim_ref_t2
				AND t1.learner_start_date_t1 = t2.learner_start_date_t2
				AND ( FALSE ";
		foreach ($this->monthsArray as $month) {
			$sql .= " OR FLOOR(t1.{$month}_prog_earned_cash_t1) != FLOOR(t2.{$month}_prog_earned_cash_t2) ";
			$sql .= " OR FLOOR(t1.{$month}_aim_completion_earned_cash_t1) != FLOOR(t2.{$month}_aim_completion_earned_cash_t2) ";
			$sql .= " OR FLOOR(t1.{$month}_bal_earned_cash_t1) != FLOOR(t2.{$month}_bal_earned_cash_t2) ";
			$sql .= " OR FLOOR(ROUND(t1.{$month}_total_t1, 1)) != FLOOR(ROUND(t2.{$month}_total_t2, 1)) ";
			$sql .= " OR FLOOR(t1.{$month}_learning_support_earned_cash_t1) != FLOOR(t2.{$month}_learning_support_earned_cash_t2) ";
			$sql .= " OR FLOOR(t1.{$month}_eng_math_on_prog_earned_cash_t1) != FLOOR(t2.{$month}_eng_math_on_prog_earned_cash_t2) ";
			$sql .= " OR FLOOR(t1.{$month}_eng_math_bal_earned_cash_t1) != FLOOR(t2.{$month}_eng_math_bal_earned_cash_t2) ";
			$sql .= " OR FLOOR(t1.{$month}_disadvantage_earned_cash_t1) != FLOOR(t2.{$month}_disadvantage_earned_cash_t2) ";
			$sql .= " OR FLOOR(t1.{$month}_1618_additional_emp_t1) != FLOOR(t2.{$month}_1618_additional_emp_t2) ";
			$sql .= " OR FLOOR(t1.{$month}_1618_additional_prov_t1) != FLOOR(t2.{$month}_1618_additional_prov_t2) ";
			$sql .= " OR FLOOR(t1.{$month}_1618_fw_uplift_opp_t1) != FLOOR(t2.{$month}_1618_fw_uplift_opp_t2) ";
			$sql .= " OR FLOOR(t1.{$month}_1618_fw_uplift_bal_t1) != FLOOR(t2.{$month}_1618_fw_uplift_bal_t2) ";
			$sql .= " OR FLOOR(t1.{$month}_1618_fw_uplift_comp_t1) != FLOOR(t2.{$month}_1618_fw_uplift_comp_t2) ";
		}

		$sql .= ");";
		$st = $link->query($sql);
		$report = "";
		if ($st) {
			$report = '<div><table id="dataMatrix" class="resultset" border="1" cellspacing="0" cellpadding="6">';
			$report .= '<thead><tr><th>&nbsp;</th><th>ULN</th><th>Learning Aim</th>';
			foreach ($this->monthsArray as $month) {
				$report .= '<th>' . $month . ' On Program Earned Cash</th>';
				$report .= '<th>' . $month . ' Aim Completion Earned Cash</th>';
				$report .= '<th>' . $month . ' Balancing Payment Earned Cash</th>';
				$report .= '<th>' . $month . ' Total</th>';
				$report .= '<th>' . $month . ' Learning Support Earned Cash</th>';
				$report .= '<th>' . $month . ' Eng & Math On Prog Earned Cash</th>';
				$report .= '<th>' . $month . ' Eng & Math Balancing Payment Earned Cash</th>';
				$report .= '<th>' . $month . ' Disadvantage Earned Cash</th>';
				$report .= '<th>' . $month . ' 16-18 Additional Payment for Employers</th>';
				$report .= '<th>' . $month . ' 16-18 Additional Payment for Providers</th>';
				$report .= '<th>' . $month . ' 16-18 framework uplift on programme payment</th>';
				$report .= '<th>' . $month . ' 16-18 framework uplift balancing payment</th>';
				$report .= '<th>' . $month . ' 16-18 framework uplift completion payment</th>';
			}
			$report .= '<th>Grand Total</th></tr></thead>';
			$report .= '<tbody>';
			$x = 0;
			while ($row = $st->fetch()) {
				$x++;

				$color = ($x % 2 == 0) ? '#E6E6E6' : '#FFFFFF';

				$report .= '<tr bgcolor="' . $color . '"><td align="left">Sunesis</td>';
				$report .= '<td align="left"  >' . HTML::cell($row['learning_ref_number_t1']) . '</td>';
				$report .= '<td align="left">' . HTML::cell($row['learning_aim_ref_t1']) . '</td>';
				foreach ($this->monthsArray as $month) {
					if (floor($row[$month . '_prog_earned_cash_t1']) != floor($row[$month . '_prog_earned_cash_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_prog_earned_cash_t1']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_prog_earned_cash_t1']) . '</td>';
					if (floor($row[$month . '_aim_completion_earned_cash_t1']) != floor($row[$month . '_aim_completion_earned_cash_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_aim_completion_earned_cash_t1']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_aim_completion_earned_cash_t1']) . '</td>';
					if (floor($row[$month . '_bal_earned_cash_t1']) != floor($row[$month . '_bal_earned_cash_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_bal_earned_cash_t1']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_bal_earned_cash_t1']) . '</td>';
					if (floor($row[$month . '_total_t1']) != floor($row[$month . '_total_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_total_t1']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_total_t1']) . '</td>';

					if (floor($row[$month . '_learning_support_earned_cash_t1']) != floor($row[$month . '_learning_support_earned_cash_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_learning_support_earned_cash_t1']) . '</td>';
					else
						$report .= '<td align="left">&#163;' . HTML::cell($row[$month . '_learning_support_earned_cash_t1']) . '</td>';

					if (floor($row[$month . '_eng_math_on_prog_earned_cash_t1']) != floor($row[$month . '_eng_math_on_prog_earned_cash_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_eng_math_on_prog_earned_cash_t1']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_eng_math_on_prog_earned_cash_t1']) . '</td>';

					if (floor($row[$month . '_eng_math_bal_earned_cash_t1']) != floor($row[$month . '_eng_math_bal_earned_cash_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_eng_math_bal_earned_cash_t1']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_eng_math_bal_earned_cash_t1']) . '</td>';

					if (floor($row[$month . '_disadvantage_earned_cash_t1']) != floor($row[$month . '_disadvantage_earned_cash_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_disadvantage_earned_cash_t1']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_disadvantage_earned_cash_t1']) . '</td>';

					if (floor($row[$month . '_1618_additional_emp_t1']) != floor($row[$month . '_1618_additional_emp_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_1618_additional_emp_t1']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_additional_emp_t1']) . '</td>';

					if (floor($row[$month . '_1618_additional_prov_t1']) != floor($row[$month . '_1618_additional_prov_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_1618_additional_prov_t1']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_additional_prov_t1']) . '</td>';
					if (floor($row[$month . '_1618_fw_uplift_opp_t1']) != floor($row[$month . '_1618_fw_uplift_opp_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_opp_t1']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_opp_t1']) . '</td>';
					if (floor($row[$month . '_1618_fw_uplift_bal_t1']) != floor($row[$month . '_1618_fw_uplift_bal_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_bal_t1']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_bal_t1']) . '</td>';
					if (floor($row[$month . '_1618_fw_uplift_comp_t1']) != floor($row[$month . '_1618_fw_uplift_comp_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_comp_t1']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_comp_t1']) . '</td>';

					$this->sunesisTotal += floatval($row[$month . '_prog_earned_cash_t1'])
						+ floatval($row[$month . '_aim_completion_earned_cash_t1'])
						+ floatval($row[$month . '_bal_earned_cash_t1'])
						+ floatval($row[$month . '_learning_support_earned_cash_t1'])
						+ floatval($row[$month . '_eng_math_on_prog_earned_cash_t1'])
						+ floatval($row[$month . '_eng_math_bal_earned_cash_t1'])
						+ floatval($row[$month . '_disadvantage_earned_cash_t1'])
						+ floatval($row[$month . '_1618_additional_emp_t1'])
						+ floatval($row[$month . '_1618_additional_prov_t1'])
						+ floatval($row[$month . '_1618_fw_uplift_opp_t1'])
						+ floatval($row[$month . '_1618_fw_uplift_bal_t1'])
						+ floatval($row[$month . '_1618_fw_uplift_comp_t1']);
					//pre($row);
				} //end foreach
				if (floor($row['grand_total_t1']) != floor($row['grand_total_t2']))
					$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row['grand_total_t1']) . '</td>';
				else
					$report .= '<td align="left" >&#163;' . HTML::cell($row['grand_total_t1']) . '</td>';

				$report .= '</tr>';
				$report .= '<tr bgcolor="' . $color . '"><td align="left">PFR</td>';
				$report .= '<td align="left"  >' . HTML::cell($row['learning_ref_number_t2']) . '</td>';
				$report .= '<td align="left">' . HTML::cell($row['learning_aim_ref_t2']) . '</td>';
				foreach ($this->monthsArray as $month) {
					if (floor($row[$month . '_prog_earned_cash_t1']) != floor($row[$month . '_prog_earned_cash_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_prog_earned_cash_t2']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_prog_earned_cash_t2']) . '</td>';
					if (floor($row[$month . '_aim_completion_earned_cash_t1']) != floor($row[$month . '_aim_completion_earned_cash_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_aim_completion_earned_cash_t2']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_aim_completion_earned_cash_t2']) . '</td>';
					if (floor($row[$month . '_bal_earned_cash_t1']) != floor($row[$month . '_bal_earned_cash_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_bal_earned_cash_t2']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_bal_earned_cash_t2']) . '</td>';
					if (floor($row[$month . '_total_t1']) != floor($row[$month . '_total_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_total_t2']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_total_t2']) . '</td>';

					if (floor($row[$month . '_learning_support_earned_cash_t1']) != floor($row[$month . '_learning_support_earned_cash_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_learning_support_earned_cash_t2']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_learning_support_earned_cash_t2']) . '</td>';

					if (floor($row[$month . '_eng_math_on_prog_earned_cash_t1']) != floor($row[$month . '_eng_math_on_prog_earned_cash_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_eng_math_on_prog_earned_cash_t2']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_eng_math_on_prog_earned_cash_t2']) . '</td>';

					if (floor($row[$month . '_eng_math_bal_earned_cash_t1']) != floor($row[$month . '_eng_math_bal_earned_cash_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_eng_math_bal_earned_cash_t2']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_eng_math_bal_earned_cash_t2']) . '</td>';

					if (floor($row[$month . '_disadvantage_earned_cash_t1']) != floor($row[$month . '_disadvantage_earned_cash_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_disadvantage_earned_cash_t2']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_disadvantage_earned_cash_t2']) . '</td>';

					if (floor($row[$month . '_1618_additional_emp_t1']) != floor($row[$month . '_1618_additional_emp_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_1618_additional_emp_t2']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_additional_emp_t2']) . '</td>';

					if (floor($row[$month . '_1618_additional_prov_t1']) != floor($row[$month . '_1618_additional_prov_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_1618_additional_prov_t2']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_additional_prov_t2']) . '</td>';

					if (floor($row[$month . '_1618_fw_uplift_opp_t1']) != floor($row[$month . '_1618_fw_uplift_opp_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_opp_t2']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_opp_t2']) . '</td>';

					if (floor($row[$month . '_1618_fw_uplift_bal_t1']) != floor($row[$month . '_1618_fw_uplift_bal_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_bal_t2']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_bal_t2']) . '</td>';

					if (floor($row[$month . '_1618_fw_uplift_comp_t1']) != floor($row[$month . '_1618_fw_uplift_comp_t2']))
						$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_comp_t2']) . '</td>';
					else
						$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_comp_t2']) . '</td>';

					$this->pfrTotal += floatval($row[$month . '_prog_earned_cash_t2'])
						+ floatval($row[$month . '_aim_completion_earned_cash_t2'])
						+ floatval($row[$month . '_bal_earned_cash_t2'])
						+ floatval($row[$month . '_learning_support_earned_cash_t2'])
						+ floatval($row[$month . '_eng_math_on_prog_earned_cash_t2'])
						+ floatval($row[$month . '_eng_math_bal_earned_cash_t2'])
						+ floatval($row[$month . '_disadvantage_earned_cash_t2'])
						+ floatval($row[$month . '_1618_additional_emp_t2'])
						+ floatval($row[$month . '_1618_additional_prov_t2'])
						+ floatval($row[$month . '_1618_fw_uplift_opp_t2'])
						+ floatval($row[$month . '_1618_fw_uplift_bal_t2'])
						+ floatval($row[$month . '_1618_fw_uplift_comp_t2']);
				} //end foreach
				if (floor($row['grand_total_t1']) != floor($row['grand_total_t2']))
					$report .= '<td align="left" bgcolor="#80FF00" >&#163;' . HTML::cell($row['grand_total_t2']) . '</td>';
				else
					$report .= '<td align="left" >&#163;' . HTML::cell($row['grand_total_t2']) . '</td>';
				$report .= '</tr>';
			} //end while
			$report .= '</tbody></table></div>';
		} else {
			pre($link->errorInfo());
		}
		return $report;
	} // end function renderDifferentRecords()


	private function renderExtraRecordsInPFR(PDO $link)
	{
		$report = "";
		$sql = "SELECT t2.* FROM dataTable2 t2
				WHERE CONCAT(t2.learning_ref_number_t2,t2.learning_aim_ref_t2) NOT IN (SELECT CONCAT(t1.learning_ref_number_t1,t1.learning_aim_ref_t1) FROM dataTable1 t1 GROUP BY t1.learning_ref_number_t1, t1.learning_aim_ref_t1)
				AND t2.learning_ref_number_t2 != 'Unique learner number'
				AND t2.learning_ref_number_t2 != ''
				#AND t2.learning_aim_ref_t2 != 'ZPROG001'
				AND (  FALSE
				";

		foreach ($this->monthsArray as $month) {
			$sql .= " OR FLOOR(t2.{$month}_prog_earned_cash_t2) != 0 ";
			$sql .= " OR FLOOR(t2.{$month}_aim_completion_earned_cash_t2) != 0 ";
			$sql .= " OR FLOOR(t2.{$month}_bal_earned_cash_t2) != 0 ";
			$sql .= " OR FLOOR(t2.{$month}_learning_support_earned_cash_t2) != 0 ";
			$sql .= " OR FLOOR(t2.{$month}_eng_math_on_prog_earned_cash_t2) != 0 ";
			$sql .= " OR FLOOR(t2.{$month}_eng_math_bal_earned_cash_t2) != 0 ";
			$sql .= " OR FLOOR(t2.{$month}_disadvantage_earned_cash_t2) != 0 ";
			$sql .= " OR FLOOR(t2.{$month}_1618_additional_emp_t2) != 0 ";
			$sql .= " OR FLOOR(t2.{$month}_1618_additional_prov_t2) != 0 ";
			$sql .= " OR FLOOR(t2.{$month}_1618_fw_uplift_opp_t2) != 0 ";
			$sql .= " OR FLOOR(t2.{$month}_1618_fw_uplift_bal_t2) != 0 ";
			$sql .= " OR FLOOR(t2.{$month}_1618_fw_uplift_comp_t2) != 0 ";
		}
		$sql .= ");";

		$st = $link->query($sql);
		if ($st) {
			$report = '<div><table id="dataMatrix" class="resultset" border="1" cellspacing="0" cellpadding="6">';
			$report .= '<thead><tr><th>&nbsp;</th><th>ULN</th><th>Learning Aim</th>';
			foreach ($this->monthsArray as $month) {
				$report .= '<th>' . $month . ' On Program Earned Cash</th>';
				$report .= '<th>' . $month . ' Aim Completion Earned Cash</th>';
				$report .= '<th>' . $month . ' Balancing Payment Earned Cash</th>';
				$report .= '<th>' . $month . ' Total</th>';
				$report .= '<th>' . $month . ' Learning Support Earned Cash</th>';
				$report .= '<th>' . $month . ' Eng & Math On Prog Earned Cash</th>';
				$report .= '<th>' . $month . ' Eng & Math Balancing Payment Earned Cash</th>';
				$report .= '<th>' . $month . ' Disadvantage Earned Cash</th>';
				$report .= '<th>' . $month . ' 16-18 Additional Payment for Employers</th>';
				$report .= '<th>' . $month . ' 16-18 Additional Payment for Providers</th>';
				$report .= '<th>' . $month . ' 16-18 framework uplift on programme payment</th>';
				$report .= '<th>' . $month . ' 16-18 framework uplift balancing payment</th>';
				$report .= '<th>' . $month . ' 16-18 framework uplift completion payment</th>';
			}
			$report .= '</tr></thead>';
			$report .= '<tbody>';
			while ($row = $st->fetch()) {
				$report .= '</tr>';
				$report .= '<tr><td align="left">PFR</td>';
				$report .= '<td align="left"  >' . HTML::cell($row['learning_ref_number_t2']) . '</td>';
				$report .= '<td align="left">' . HTML::cell($row['learning_aim_ref_t2']) . '</td>';
				foreach ($this->monthsArray as $month) {
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_prog_earned_cash_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_aim_completion_earned_cash_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_bal_earned_cash_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_total_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_learning_support_earned_cash_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_eng_math_on_prog_earned_cash_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_eng_math_bal_earned_cash_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_disadvantage_earned_cash_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_additional_emp_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_additional_prov_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_opp_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_bal_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_comp_t2']) . '</td>';

					/*$this->pfrTotal += floatval($row[$month . '_prog_earned_cash_t2'])
						+ floatval($row[$month . '_aim_completion_earned_cash_t2'])
						+ floatval($row[$month . '_bal_earned_cash_t2'])
						+ floatval($row[$month . '_learning_support_earned_cash_t2'])
						+ floatval($row[$month . '_eng_math_on_prog_earned_cash_t2'])
						+ floatval($row[$month . '_eng_math_bal_earned_cash_t2'])
						+ floatval($row[$month . '_disadvantage_earned_cash_t2'])
						+ floatval($row[$month . '_1618_additional_emp_t2'])
						+ floatval($row[$month . '_1618_additional_prov_t2'])
						+ floatval($row[$month . '_1618_fw_uplift_opp_t2'])
						+ floatval($row[$month . '_1618_fw_uplift_bal_t2'])
						+ floatval($row[$month . '_1618_fw_uplift_comp_t2']);*/
				} //end foreach
				$report .= '</tr>';
			}
			$report .= '</tbody></table></div>';
		} else {
			pre($link->errorInfo());
		}
		return $report;
	}

	private function renderRecordsWithNoLearningAimsButWithFunding(PDO $link)
	{
		$sql = "SELECT t2.* FROM dataTable2 t2
				WHERE t2.learning_aim_ref_t2 = ''
				AND ( FALSE ";
		//(t1.area_uplift_t1 != t2.area_uplift_t2 OR t1.disadvantage_uplift_t1 != t2.disadvantage_uplift_t2 ";
		foreach ($this->monthsArray as $month) {
			$sql .= " OR FLOOR(t2.{$month}_prog_earned_cash_t2) != '' ";
			$sql .= " OR FLOOR(t2.{$month}_aim_completion_earned_cash_t2) != '' ";
			$sql .= " OR FLOOR(t2.{$month}_bal_earned_cash_t2) != '' ";
			$sql .= " OR FLOOR(ROUND(t2.{$month}_total_t2, 1)) != '' ";
		}

		$sql .= ");"; //pre($sql);
		$st = $link->query($sql);
		if ($st) {
			$report = '<div><table id="dataMatrix" class="resultset" border="1" cellspacing="0" cellpadding="6">';
			$report .= '<thead><tr><th>&nbsp;</th><th>ULN</th><th>Learning Aim</th>';
			foreach ($this->monthsArray as $month) {
				$report .= '<th>' . $month . ' On Program Earned Cash</th>';
				$report .= '<th>' . $month . ' Aim Completion Earned Cash</th>';
				$report .= '<th>' . $month . ' Balancing Payment Earned Cash</th>';
				$report .= '<th>' . $month . ' Total</th>';
				$report .= '<th>' . $month . ' Learning Support Earned Cash</th>';
				$report .= '<th>' . $month . ' Eng & Math On Prog Earned Cash</th>';
				$report .= '<th>' . $month . ' Eng & Math Balancing Payment Earned Cash</th>';
				$report .= '<th>' . $month . ' Disadvantage Earned Cash</th>';
				$report .= '<th>' . $month . ' 16-18 Additional Payment for Employers</th>';
				$report .= '<th>' . $month . ' 16-18 Additional Payment for Providers</th>';
				$report .= '<th>' . $month . ' 16-18 framework uplift on programme payment</th>';
				$report .= '<th>' . $month . ' 16-18 framework uplift balancing payment</th>';
				$report .= '<th>' . $month . ' 16-18 framework uplift completion payment</th>';
			}
			$report .= '</tr></thead>';
			$report .= '<tbody>';
			while ($row = $st->fetch()) {
				$report .= '</tr>';
				$report .= '<tr><td align="left">PFR</td>';
				$report .= '<td align="left"  >' . HTML::cell($row['learning_ref_number_t2']) . '</td>';
				$report .= '<td align="left">' . HTML::cell($row['learning_aim_ref_t2']) . '</td>';
				foreach ($this->monthsArray as $month) {
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_prog_earned_cash_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_aim_completion_earned_cash_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_bal_earned_cash_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_total_t2']) . '</td>';
					$this->pfrTotal = $this->pfrTotal + floatval($row[$month . '_total_t2']);
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_learning_support_earned_cash_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_eng_math_on_prog_earned_cash_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_eng_math_bal_earned_cash_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_disadvantage_earned_cash_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_additional_emp_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_additional_prov_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_opp_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_bal_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_comp_t2']) . '</td>';
				} //end foreach
				$report .= '</tr>';
			}
			$report .= '</tbody></table></div>';
		} else {
			pre($link->errorInfo());
		}
		return $report;
	}

	private function renderExtraRecordsInSunesis(PDO $link)
	{
		$report = "";
		$sql = "SELECT t1.* FROM dataTable1 t1
				WHERE t1.learning_ref_number_t1 NOT IN (SELECT t2.learning_ref_number_t2 FROM dataTable2 t2 WHERE t2.`learning_aim_ref_t2` != '' GROUP BY t2.learning_ref_number_t2)
				AND t1.learning_ref_number_t1 != 'Unique learner number'
				AND t1.learning_ref_number_t1 != ''
				AND t1.learning_aim_ref_t1 != 'ZPROG001' ";
		$st = $link->query($sql);
		if ($st) {
			$report = '<div><table id="dataMatrix" class="resultset" border="1" cellspacing="0" cellpadding="6">';
			$report .= '<thead><tr><th>&nbsp;</th><th>ULN</th><th>Learning Aim</th>';
			foreach ($this->monthsArray as $month) {
				$report .= '<th>' . $month . ' On Program Earned Cash</th>';
				$report .= '<th>' . $month . ' Aim Completion Earned Cash</th>';
				$report .= '<th>' . $month . ' Balancing Payment Earned Cash</th>';
				$report .= '<th>' . $month . ' Total</th>';
				$report .= '<th>' . $month . ' Learning Support Earned Cash</th>';
				$report .= '<th>' . $month . ' Eng & Math On Prog Earned Cash</th>';
				$report .= '<th>' . $month . ' Eng & Math Balancing Payment Earned Cash</th>';
				$report .= '<th>' . $month . ' Disadvantage Earned Cash</th>';
				$report .= '<th>' . $month . ' 16-18 Additional Payment for Employers</th>';
				$report .= '<th>' . $month . ' 16-18 Additional Payment for Providers</th>';
				$report .= '<th>' . $month . ' 16-18 framework uplift on programme payment</th>';
				$report .= '<th>' . $month . ' 16-18 framework uplift balancing payment</th>';
				$report .= '<th>' . $month . ' 16-18 framework uplift completion payment</th>';
			}
			$report .= '</tr></thead>';
			$report .= '<tbody>';
			while ($row = $st->fetch()) {
				$report .= '</tr>';
				$report .= '<tr><td align="left">Sunesis</td>';
				$report .= '<td align="left"  >' . HTML::cell($row['learning_ref_number_t1']) . '</td>';
				$report .= '<td align="left">' . HTML::cell($row['learning_aim_ref_t1']) . '</td>';
				foreach ($this->monthsArray as $month) {
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_prog_earned_cash_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_aim_completion_earned_cash_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_bal_earned_cash_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_total_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_learning_support_earned_cash_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_eng_math_on_prog_earned_cash_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_eng_math_bal_earned_cash_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_disadvantage_earned_cash_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_additional_emp_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_additional_prov_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_opp_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_bal_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_comp_t1']) . '</td>';

					/*$this->sunesisTotal += floatval($row[$month . '_prog_earned_cash_t1'])
						+ floatval($row[$month . '_aim_completion_earned_cash_t1'])
						+ floatval($row[$month . '_bal_earned_cash_t1'])
						+ floatval($row[$month . '_learning_support_earned_cash_t1'])
						+ floatval($row[$month . '_eng_math_on_prog_earned_cash_t1'])
						+ floatval($row[$month . '_eng_math_bal_earned_cash_t1'])
						+ floatval($row[$month . '_disadvantage_earned_cash_t1'])
						+ floatval($row[$month . '_1618_additional_emp_t1'])
						+ floatval($row[$month . '_1618_additional_prov_t1'])
						+ floatval($row[$month . '_1618_fw_uplift_opp_t1'])
						+ floatval($row[$month . '_1618_fw_uplift_bal_t1'])
						+ floatval($row[$month . '_1618_fw_uplift_comp_t1']);*/
				} //end foreach
				$report .= '</tr>';
			}
			$report .= '</tbody></table></div>';
		} else {
			pre($link->errorInfo());
		}
		return $report;
	}

	public function renderSimilarRecords(PDO $link)
	{

		$sql = " SELECT t1.*, t2.* FROM dataTable1 t1 INNER JOIN dataTable2 t2 ON t1.learning_ref_number_t1 = t2.learning_ref_number_t2 AND t1.learning_aim_ref_t1 = t2.learning_aim_ref_t2
				AND t1.learner_start_date_t1 = t2.learner_start_date_t2 AND ( TRUE ";
		foreach ($this->monthsArray as $month) {
			$sql .= " AND FLOOR(t1.{$month}_prog_earned_cash_t1) = FLOOR(t2.{$month}_prog_earned_cash_t2) ";
			$sql .= " AND FLOOR(t1.{$month}_aim_completion_earned_cash_t1) = FLOOR(t2.{$month}_aim_completion_earned_cash_t2) ";
			$sql .= " AND FLOOR(t1.{$month}_bal_earned_cash_t1) = FLOOR(t2.{$month}_bal_earned_cash_t2) ";
			$sql .= " AND FLOOR(ROUND(t1.{$month}_total_t1, 1)) = FLOOR(ROUND(t2.{$month}_total_t2, 1)) ";
			$sql .= " AND FLOOR(t1.{$month}_learning_support_earned_cash_t1) = FLOOR(t2.{$month}_learning_support_earned_cash_t2) ";
			$sql .= " AND FLOOR(t1.{$month}_eng_math_on_prog_earned_cash_t1) = FLOOR(t2.{$month}_eng_math_on_prog_earned_cash_t2) ";
			$sql .= " AND FLOOR(t1.{$month}_eng_math_bal_earned_cash_t1) = FLOOR(t2.{$month}_eng_math_bal_earned_cash_t2) ";
			$sql .= " AND FLOOR(t1.{$month}_disadvantage_earned_cash_t1) = FLOOR(t2.{$month}_disadvantage_earned_cash_t2) ";
			$sql .= " AND FLOOR(t1.{$month}_1618_additional_emp_t1) = FLOOR(t2.{$month}_1618_additional_emp_t2) ";
			$sql .= " AND FLOOR(t1.{$month}_1618_additional_prov_t1) = FLOOR(t2.{$month}_1618_additional_prov_t2) ";
			$sql .= " AND FLOOR(t1.{$month}_1618_fw_uplift_opp_t1) = FLOOR(t2.{$month}_1618_fw_uplift_opp_t2) ";
			$sql .= " AND FLOOR(t1.{$month}_1618_fw_uplift_bal_t1) = FLOOR(t2.{$month}_1618_fw_uplift_bal_t2) ";
			$sql .= " AND FLOOR(t1.{$month}_1618_fw_uplift_comp_t1) = FLOOR(t2.{$month}_1618_fw_uplift_comp_t2) ";
		}
		$sql .= ");";

		$st = $link->query($sql);
		$this->similarRecords = $st->rowCount();
		$report = "";
		if ($st) {
			$report = '<div><table id="dataMatrix" class="resultset" border="1" cellspacing="0" cellpadding="6">';
			$report .= '<thead><tr><th>&nbsp;</th><th>ULN</th><th>Learning Aim</th>';
			foreach ($this->monthsArray as $month) {
				$report .= '<th>' . $month . ' On Program Earned Cash</th>';
				$report .= '<th>' . $month . ' Aim Completion Earned Cash</th>';
				$report .= '<th>' . $month . ' Balancing Payment Earned Cash</th>';
				$report .= '<th>' . $month . ' Total</th>';
				$report .= '<th>' . $month . ' Learning Support Earned Cash</th>';
				$report .= '<th>' . $month . ' Eng & Math On Prog Earned Cash</th>';
				$report .= '<th>' . $month . ' Eng & Math Balancing Payment Earned Cash</th>';
				$report .= '<th>' . $month . ' Disadvantage Earned Cash</th>';
				$report .= '<th>' . $month . ' 16-18 Additional Payment for Employers</th>';
				$report .= '<th>' . $month . ' 16-18 Additional Payment for Providers</th>';
				$report .= '<th>' . $month . ' 16-18 framework uplift on programme payment</th>';
				$report .= '<th>' . $month . ' 16-18 framework uplift balancing payment</th>';
				$report .= '<th>' . $month . ' 16-18 framework uplift completion payment</th>';
			}
			$report .= '<th>Grand Total</th></tr></thead>';

			$report .= '<tbody>';
			$x = 0;
			while ($row = $st->fetch()) {
				$x++;

				$color = ($x % 2 == 0) ? '#E6E6E6' : '#FFFFFF';

				$report .= '<tr bgcolor="' . $color . '"><td align="left">Sunesis</td>';
				$report .= '<td align="left" >' . HTML::cell($row['learning_ref_number_t1']) . '</td>';
				$report .= '<td align="left">' . HTML::cell($row['learning_aim_ref_t1']) . '</td>';
				foreach ($this->monthsArray as $month) {
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_prog_earned_cash_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_aim_completion_earned_cash_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_bal_earned_cash_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_total_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_learning_support_earned_cash_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_eng_math_on_prog_earned_cash_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_eng_math_bal_earned_cash_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_disadvantage_earned_cash_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_additional_emp_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_additional_prov_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_opp_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_bal_t1']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_comp_t1']) . '</td>';

					$this->sunesisTotal += floatval($row[$month . '_prog_earned_cash_t1'])
						+ floatval($row[$month . '_aim_completion_earned_cash_t1'])
						+ floatval($row[$month . '_bal_earned_cash_t1'])
						+ floatval($row[$month . '_learning_support_earned_cash_t1'])
						+ floatval($row[$month . '_eng_math_on_prog_earned_cash_t1'])
						+ floatval($row[$month . '_eng_math_bal_earned_cash_t1'])
						+ floatval($row[$month . '_disadvantage_earned_cash_t1'])
						+ floatval($row[$month . '_1618_additional_emp_t1'])
						+ floatval($row[$month . '_1618_additional_prov_t1'])
						+ floatval($row[$month . '_1618_fw_uplift_opp_t1'])
						+ floatval($row[$month . '_1618_fw_uplift_bal_t1'])
						+ floatval($row[$month . '_1618_fw_uplift_comp_t1']);
				} //end foreach

				$report .= '<td align="left" >' . HTML::cell($row['grand_total_t1']) . '</td>';

				$report .= '</tr>';
				$report .= '<tr bgcolor="' . $color . '"><td align="left">PFR</td>';
				$report .= '<td align="left"  >' . HTML::cell($row['learning_ref_number_t2']) . '</td>';
				$report .= '<td align="left">' . HTML::cell($row['learning_aim_ref_t2']) . '</td>';
				foreach ($this->monthsArray as $month) {
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_prog_earned_cash_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_aim_completion_earned_cash_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_bal_earned_cash_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_total_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_learning_support_earned_cash_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_eng_math_on_prog_earned_cash_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_eng_math_bal_earned_cash_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_disadvantage_earned_cash_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_additional_emp_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_additional_prov_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_opp_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_bal_t2']) . '</td>';
					$report .= '<td align="left" >&#163;' . HTML::cell($row[$month . '_1618_fw_uplift_comp_t2']) . '</td>';

					$this->pfrTotal += floatval($row[$month . '_prog_earned_cash_t2'])
						+ floatval($row[$month . '_aim_completion_earned_cash_t2'])
						+ floatval($row[$month . '_bal_earned_cash_t2'])
						+ floatval($row[$month . '_learning_support_earned_cash_t2'])
						+ floatval($row[$month . '_eng_math_on_prog_earned_cash_t2'])
						+ floatval($row[$month . '_eng_math_bal_earned_cash_t2'])
						+ floatval($row[$month . '_disadvantage_earned_cash_t2'])
						+ floatval($row[$month . '_1618_additional_emp_t2'])
						+ floatval($row[$month . '_1618_additional_prov_t2'])
						+ floatval($row[$month . '_1618_fw_uplift_opp_t2'])
						+ floatval($row[$month . '_1618_fw_uplift_bal_t2'])
						+ floatval($row[$month . '_1618_fw_uplift_comp_t2']);
				} //end foreach

				$report .= '<td align="left" >' . HTML::cell($row['grand_total_t2']) . '</td>';

				$report .= '</tr>';
			} //end while



			$report .= '</tbody></table></div>';
		} else {
			pre($link->errorInfo());
		}
		return $report;
	} // end function renderSimilarRecords()

	private function createTempTable(PDO $link, $monthsArray)
	{
		$sql = <<<HEREDOC
CREATE TEMPORARY TABLE `dataTable1` (
  `learning_ref_number_t1` varchar(12) DEFAULT NULL,
  `learning_aim_ref_t1` varchar(12) DEFAULT NULL,
  `learner_start_date_t1` varchar(20),
HEREDOC;
		foreach ($monthsArray as $month) {
			$sql .= "`{$month}_prog_earned_cash_t1` FLOAT(8,2), ";
			$sql .= "`{$month}_aim_completion_earned_cash_t1` FLOAT(8,2) , ";
			$sql .= "`{$month}_bal_earned_cash_t1` FLOAT(8,2) , ";
			$sql .= "`{$month}_total_t1` FLOAT(8,2) , ";
			$sql .= "`{$month}_learning_support_earned_cash_t1` FLOAT(8,2) , ";
			$sql .= "`{$month}_eng_math_on_prog_earned_cash_t1` FLOAT(8,2) , ";
			$sql .= "`{$month}_eng_math_bal_earned_cash_t1` FLOAT(8,2) , ";
			$sql .= "`{$month}_disadvantage_earned_cash_t1` FLOAT(8,2) , ";
			$sql .= "`{$month}_1618_additional_emp_t1` FLOAT(8,2) , ";
			$sql .= "`{$month}_1618_additional_prov_t1` FLOAT(8,2) , ";
			$sql .= "`{$month}_1618_fw_uplift_opp_t1` FLOAT(8,2) , ";
			$sql .= "`{$month}_1618_fw_uplift_bal_t1` FLOAT(8,2) , ";
			$sql .= "`{$month}_1618_fw_uplift_comp_t1` FLOAT(8,2) , ";
		}
		$sql .= <<<HEREDOC
	`grand_total_t1` FLOAT(8,2),
	KEY `i_L03_and_SD` (`learning_ref_number_t1`, `learning_aim_ref_t1`, `learner_start_date_t1`)
) ENGINE 'MEMORY'
HEREDOC;

		$link->query($sql);
		$sql = <<<HEREDOC
CREATE TEMPORARY TABLE `dataTable2` (
  `learning_ref_number_t2` varchar(25) DEFAULT NULL,
  `provider_specified_learner_monitoring_a` varchar(100) DEFAULT NULL,
  `provider_specified_learner_monitoring_b` varchar(100) DEFAULT NULL,
  `learning_aim_ref_t2` varchar(25) DEFAULT NULL,

  `learner_start_date_t2` varchar(20),
HEREDOC;
		foreach ($monthsArray as $month) {
			$sql .= "`{$month}_prog_earned_cash_t2` FLOAT(8,2) , ";
			$sql .= "`{$month}_aim_completion_earned_cash_t2` FLOAT(8,2) , ";
			$sql .= "`{$month}_bal_earned_cash_t2` FLOAT(8,2) , ";
			$sql .= "`{$month}_total_t2` FLOAT(8,2) , ";
			$sql .= "`{$month}_learning_support_earned_cash_t2` FLOAT(8,2) , ";
			$sql .= "`{$month}_eng_math_on_prog_earned_cash_t2` FLOAT(8,2) , ";
			$sql .= "`{$month}_eng_math_bal_earned_cash_t2` FLOAT(8,2) , ";
			$sql .= "`{$month}_disadvantage_earned_cash_t2` FLOAT(8,2) , ";
			$sql .= "`{$month}_1618_additional_emp_t2` FLOAT(8,2) , ";
			$sql .= "`{$month}_1618_additional_prov_t2` FLOAT(8,2) , ";
			$sql .= "`{$month}_1618_fw_uplift_opp_t2` FLOAT(8,2) , ";
			$sql .= "`{$month}_1618_fw_uplift_bal_t2` FLOAT(8,2) , ";
			$sql .= "`{$month}_1618_fw_uplift_comp_t2` FLOAT(8,2) , ";
		}
		$sql .= <<<HEREDOC
		`grand_total_t2` FLOAT(8,2),
		KEY `i_L03_and_SD` (`learning_ref_number_t2`, `learning_aim_ref_t2`, `learner_start_date_t2`)
) ENGINE 'MEMORY'
HEREDOC;

		$link->query($sql);
	} // end function createTempTable()

	private function _formatCash($value)
	{
		return '&pound;' . number_format(sprintf("%.2f", $value), 2, ".", ",");
	}
}// end class