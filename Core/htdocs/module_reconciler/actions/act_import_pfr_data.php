<?php

class import_pfr_data implements IAction
{
	/**
	 * @param PDO $link
	 */
	public function execute(PDO $link)
	{
		// $_SESSION['bc']->add($link, "do.php?_action=read_pfr", "PFR Reconciler");

		$view = ViewReconcilerSnapshot::getInstance();

		$fileName = '';

		// Check for use of preloaded file.
		if ( isset($_REQUEST['importfileid']) && is_numeric($_REQUEST['importfileid']) ) {
			$file_details = DAO::getResultset($link, "select filename, pfr_year, period from rpt_reconciler_header where import_id = '".$_REQUEST['importfileid']."'", DAO::FETCH_ASSOC);
			$fileName = $file_details[0]['filename'];
			$this->pfr_year = $file_details[0]['pfr_year'];
			$this->submission_period = $file_details[0]['period'];
			$original_import_id = $_REQUEST['importfileid'];

			$this->import_id = DAO::getSingleValue($link, 'SELECT MAX(import_id)+1 AS import_count FROM rpt_reconciler_pfr');


			// delete the data for the old import id from the pfr reconciler table
			$transfer_import_id = <<<HEREDOC
INSERT INTO rpt_reconciler_pfr (
	`import_id`, `ULN`, `name`, `A09`, `L03`, `provider_name`, `course_name`, `employer_name`,
	`qualification_title`,`learner_start_date`,`learner_target_end_date`,`learner_end_date`,
	`entry_end_date`,`outcome_indicator`,`P1_OPP`,`P1_bal`,`P1_ach`,`P1_total`,`P2_OPP`,`P2_bal`,
	`P2_ach`,`P2_total`,`P3_OPP`,`P3_bal`,`P3_ach`,`P3_total`,`P4_OPP`,`P4_bal`,`P4_ach`,`P4_total`,
	`P5_OPP`,`P5_bal`,`P5_ach`,`P5_total`,`P6_OPP`,`P6_bal`,`P6_ach`,`P6_total`,`P7_OPP`,`P7_bal`,
	`P7_ach`,`P7_total`,`P8_OPP`,`P8_bal`,`P8_ach`,`P8_total`,`P9_OPP`,`P9_bal`,`P9_ach`,`P9_total`,
	`P10_OPP`,`P10_bal`,`P10_ach`,`P10_total`,`P11_OPP`,`P11_bal`,`P11_ach`,`P11_total`,`P12_OPP`,
	`P12_bal`,`P12_ach`,`P12_total`,`grand_total`,`contract_id`, `record_issue`
)
SELECT
	$this->import_id, `ULN`, `name`, `A09`, `L03`, `provider_name`, `course_name`, `employer_name`,
	`qualification_title`,`learner_start_date`,`learner_target_end_date`,`learner_end_date`,
	`entry_end_date`,`outcome_indicator`,`P1_OPP`,`P1_bal`,`P1_ach`,`P1_total`,`P2_OPP`,`P2_bal`,
	`P2_ach`,`P2_total`,`P3_OPP`,`P3_bal`,`P3_ach`,`P3_total`,`P4_OPP`,`P4_bal`,`P4_ach`,`P4_total`,
	`P5_OPP`,`P5_bal`,`P5_ach`,`P5_total`,`P6_OPP`,`P6_bal`,`P6_ach`,`P6_total`,`P7_OPP`,`P7_bal`,
	`P7_ach`,`P7_total`,`P8_OPP`,`P8_bal`,`P8_ach`,`P8_total`,`P9_OPP`,`P9_bal`,`P9_ach`,`P9_total`,
	`P10_OPP`,`P10_bal`,`P10_ach`,`P10_total`,`P11_OPP`,`P11_bal`,`P11_ach`,`P11_total`,`P12_OPP`,
	`P12_bal`,`P12_ach`,`P12_total`,`grand_total`,`contract_id`, '<tr><th>Column</th><th>Sunesis Value</th><th>PFR Value</th></tr>'
FROM
	rpt_reconciler_pfr
WHERE
	import_id = $original_import_id;
HEREDOC;

			DAO::execute($link, $transfer_import_id);
			$this->reconciler = new Reconciler();

			$preloaded = $this->reconciler->setHeader($link, $fileName, $this->import_id, $this->pfr_year, $this->submission_period);

			include('tpl_import_pfr_data.php');
			exit;
		}
		elseif(!empty($_FILES["file"])) {

			if($_FILES['file']['error'] == UPLOAD_ERR_OK) {

				$fileName = basename($_FILES['file']['name']);

				// check if the file has been loaded up previously.
				$previous_file_upload = DAO::getSingleValue($link, "select distinct(import_id) from rpt_reconciler_header where filename = '".$fileName."'");
				// do some stuff here to negate the need to re-upload.

				if ( $previous_file_upload != '' ) {
					$file_details = DAO::getResultset($link, "select filename, pfr_year from rpt_reconciler_header where import_id = '".$previous_file_upload."'", DAO::FETCH_ASSOC);
					$fileName = $file_details[0]['filename'];
					$this->pfr_year = $file_details[0]['pfr_year'];
					http_redirect('do.php?_action=read_pfr&amp;mesg=This file has been uploaded previously <strong>'.$fileName.'</strong>. Please use the previously loaded version, or upload a new PFR file.');
					exit;
				}

				$fileExt = strtolower(substr($fileName, strrpos($fileName, '.') + 1));
				if($fileExt == 'xlsx' || $fileExt == 'csv') {
					// create the reconciler object
					$this->reconciler = new Reconciler();
				}
				else {
					http_redirect('do.php?_action=read_pfr&amp;mesg=Unsupported PFR file format. Please upload an XLSX or CSV file.');
					exit;
				}
			}
			else {
				http_redirect('do.php?_action=read_pfr&amp;mesg='.$this->_getFileUploadError($_FILES['file']['error']));
				exit;
			}
		}

		$fileExt = strtolower(substr($fileName, strrpos($fileName, '.') + 1));
		$contracts = isset($_REQUEST['contract']) ? implode(",", $_REQUEST['contract']) : "";

		// get the relevant contract data
		// as selected by the user
		// ---
		/*if ( !isset($_REQUEST['contract_auto_detect']) ) {
			if ( '' == $contracts ) {
				// user has deselected auto detect, but
				// hasn't selected a specific contract to
				// run against - odd, let them know
				http_redirect('do.php?_action=read_pfr&amp;mesg=Please select a contract to compare, or else leave <strong>(auto detect contracts)</strong> selected');
				exit;
			}
		}*/

		if ( $this->debug_pfr_process === 1 ) {
			$this->check_memory(__line__);
		}

		// Extract the PFR data and save to the pfr reporting table
		$this->reconciler->createPfrTable($link);
		$this->import_id = $this->reconciler->getImportID($link);
		if ( !isset($this->import_id) ) {
			$this->import_id = 1;
		}
		else {
			$this->import_id++;
		}

		// check for header information
		$this->reconciler->createHeaderTable($link);

		$preloaded = $this->reconciler->setHeader($link, $fileName, $this->import_id, '', '');

		// check the snapshot table exists ( set up if not )
		$this->reconciler->createReportTable($link);


		switch($fileExt) {
			case "xlsx":
				$contains_valid_worksheet = $this->_storePfrDataXlsx($link);
				if ( $contains_valid_worksheet !== 1 ) {
					http_redirect('do.php?_action=read_pfr&amp;mesg=Sorry, we couldn\'t find a worksheet with the name \'<strong>Occupancy Report</strong>\' in your PFR File');
					exit;
				}
				break;

			case "csv":
				$this->_storePfrDataCsv($link);
				break;

			default:
				break;
		}


		$this->clear_pfr_from_memory();
		// ----
		if ( $this->debug_pfr_process === 1 ) {
			$this->check_memory(__line__);
		}

		include('tpl_import_pfr_data.php');
	}

	/**
	 * @param PDO $link
	 * @return int
	 */
	private function _storePfrDataXlsx(PDO $link)
	{
		// flag indicating if worksheet is found
		$is_valid_worksheet = 0;

		// get the key values
		$header_keys = array_flip($this->reconciler->reconciler_datamap_array);

		// start building the data from the PFR
		// ---
		$getWorksheetName = array();

		$xlsx = new XLSX($_FILES['file']['tmp_name']);

		$getWorksheetName = $xlsx->getWorksheetName();

		// $present_learner_html .= '<div id="datacontent">';
		$sheetsCount = $xlsx->sheetsCount();
		for( $j=1; $j <= $sheetsCount; $j++ )
		{
			// ---
			// get the worksheet name, we are only interested in
			// the Occupancy Report sheet.
			$thisWorksheetName = $getWorksheetName[$j-1];
			if ( $thisWorksheetName != 'Occupancy Report' ) {
				// ---
				// carry on if not Occupancy Report
				continue;
			}
			else {
				// we've found a valid worksheet amongst many
				// ---
				$is_valid_worksheet = 1;
			}

			list($cols,) = $xlsx->dimension($j);
			// Number of learner records
			// Preamble capture, to avoid populating the data array with summary info
			// ---
			// Preamble content sample:
			// 	[1][0] = [2011-12 Employer Responsive Occupancy Report]
			// 	[1][1] = [Skills Funding Agency Region: South East][Date Produced: 05/01/2012]
			// 	[1][2] = [Contracting Organisation Code: SFSE][Last Provider Batch File Received: ILR-A-10007928-1112-0013-01_05012012_083006.XML]
			// 	[1][3] = [Provider Name: FAREHAM COLLEGE][Last Provider Batch Update: 05/01/2012]
			// 	[1][4] = [Provider Number: 108459][Last POL Update: ]
			// 	[1][5] = [UK Provider Reference Number: 10007928][Last TPS Update: ]
			// ---

			$preamble_complete = 0;

			$db_rows = array();
			foreach( $xlsx->rows($j) as $k => $r)
			{
				// check on the first value containing text
				// to validate incorrect data in spreadsheet??
				if ( !isset($r[0]) ) {
					http_redirect('do.php?_action=read_pfr&amp;mesg=Sorry, we couldn\'t recognise the data in your file. Are you sure it is a PFR file with no data changes?');
					exit;
				}

				// hold the data in the sunesis format
				$sunesis_data_sql_columns = '';
				$sunesis_data_sql_values = '';

				// Learner Reference Number marks the end of the header preamble
				// and the beginnings of the data.
				// ---
				if ( strcasecmp($r[0], "Learner Reference Number") == 0 ) {
					$preamble_complete = 1;
					// build the 'headers' for the learner array.
					// need to map to Sunesis model
					for( $column_position = 0; $column_position < $cols; $column_position++) {
						//Display data
						if ( isset($r[$column_position]) ) {
							$column_name = strtolower($r[$column_position]);
							$this->reconciler->occ_reconciler_headers[$column_position] = $column_name;
						}
					}
					// don't bother going any further with this row
					continue;
				}

				// If we are still in the preamble, we expect a two column
				// dataset as per the sample above.
				if ( !$preamble_complete ) {
					// use only the first part of the spreadsheet
					// to import year of contract
					// - check this is valid....??
					// ---
					switch ($k) {
						case 0:
							// Type of report - what do we handle
							if ( preg_match("/^(\d+){4}/", $r[0], $contract_year) ) {
								$this->pfr_year = $contract_year[0];
							}
							break;
						default:
					}

					// don't bother going any further with these rows
					continue;
				}

				// to be here, we are in the learner PFR data section of
				// the Occupancy Report spreadsheet.
				$row_totaliser = 0;

				$db_row = array();
				for( $column_position = 0; $column_position < $cols; $column_position++)
				{
					//Display data
					if ( isset($r[$column_position]) )
					{
						if(isset($this->reconciler->occ_reconciler_headers[$column_position]))
						{
							if ( !isset( $header_keys[$this->reconciler->occ_reconciler_headers[$column_position]] ) )
							{
								continue;
							}
							$sunesis_key = $header_keys[$this->reconciler->occ_reconciler_headers[$column_position]];

							// sort the financial format out...
							// only once the textual data is passed ( > 15 bit )
							if ( $sunesis_key != "" )
							{
								if ( is_numeric($r[$column_position]) && $column_position > 15 )
								{
									$numeric_value =  sprintf("%.2f", round($r[$column_position],2));
									$sunesis_data_sql_columns .= $sunesis_key.",";
									$sunesis_data_sql_values .= "'".$numeric_value."',";
									if ( $sunesis_key != 'outcome_indicator' ) {
										$row_totaliser += $r[$column_position];
									}
									$db_row[$sunesis_key] = $numeric_value;
								}
								else
								{

									if ( $sunesis_key == 'outcome_indicator' && !is_numeric($r[$column_position]) )
									{
										$sunesis_data_sql_columns .= $sunesis_key.",";
										$sunesis_data_sql_values .= "0,\n";
										$db_row[$sunesis_key] = 0;
									}
									else
									{
										$sunesis_data_sql_columns .= $sunesis_key.",";
										$sunesis_data_sql_values .= "'".addslashes((string)$r[$column_position])."',\n";
										$db_row[$sunesis_key] = $r[$column_position];
										if ($sunesis_key == 'qualification_title' )
										{
											$sunesis_data_sql_columns .= "A09,";
											$sunesis_data_sql_values .= "'".addslashes((string)$r[$column_position])."',\n";
											$db_row['A09'] = $r[$column_position];
										}
									}
								}
							}
						}
					}
				}

				$db_row['record_issue'] = '<tr><th>Column</th><th>Sunesis Value</th><th>PFR Value</th></tr>';

				if($row_totaliser > 0){
					$db_row['grand_total'] = $row_totaliser;
					$db_row['import_id'] = $this->import_id;
					$db_rows[] = $db_row;
					if(count($db_rows) > 50){
						DAO::multipleRowInsert($link, "rpt_reconciler_pfr", $db_rows);
						$db_rows = array();
					}
				}
			}

			DAO::multipleRowInsert($link, "rpt_reconciler_pfr", $db_rows);
		}

		// what have we got in memory still from this?
		unset($xlsx);
		$xlsx = NULL;

		return $is_valid_worksheet;
	}

	/**
	 * @param PDO $link
	 */
	private function _storePfrDataCsv(PDO $link)
	{
		$header_keys = array_flip($this->reconciler->reconciler_datamap_array);
		$columnTitles = array();
		$preamble_complete = false;
		$db_rows = array();

		$csv = new CsvFileReader($_FILES['file']['tmp_name']);
		$count = 0;

		$fish = array();

		foreach( $csv as $row)
		{
			// Column headings found - preamble over
			if ( strcasecmp($row[0], "Learner Reference Number") == 0 ) {
				$preamble_complete = true;
				// build the 'headers' for the learner array.
				// need to map to Sunesis model
				for( $column_position = 0, $cols = count($row); $column_position < $cols; $column_position++) {
					if ( !empty($row[$column_position]) ) {
						$this->reconciler->occ_reconciler_headers[$column_position] = strtolower($row[$column_position]);
					}
				}
				$columnTitles = $this->reconciler->occ_reconciler_headers;
				$numColumnTitles = count($columnTitles);
				continue; // move on to next row
			}

			$count++;

			// Skim through the preamble
			if(!$preamble_complete){
				// Preamble content sample:
				// 	[2011-12 Employer Responsive Occupancy Report]
				// 	[Skills Funding Agency Region: South East][Date Produced: 05/01/2012]
				// 	[Contracting Organisation Code: SFSE][Last Provider Batch File Received: ILR-A-10007928-1112-0013-01_05012012_083006.XML]
				// 	[Provider Name: FAREHAM COLLEGE][Last Provider Batch Update: 05/01/2012]
				// 	[Provider Number: 108459][Last POL Update: ]
				// 	[UK Provider Reference Number: 10007928][Last TPS Update: ]

				// Get the contract date from the first preamble line with a four-digit number
				if ( empty($this->pfr_year) && preg_match("/^(\d+){4}/", $row[0], $contract_year) ) {
					$this->pfr_year = $contract_year[0];
				}

				// Get the Batch Update date to get the Submission Period
				if ( empty($this->submission_period) ) {
					$batch_date_col = key(preg_grep("/^Last Provider Batch Update: (.*)$/", $row));
					if ( isset($batch_date_col) ) {
						preg_match("/^Last Provider Batch Update: (.*)$/", $row[$batch_date_col], $submission_date);
						$this->submission_period = DAO::getSingleValue($link, "SELECT submission FROM central.lookup_submission_dates WHERE start_submission_date <= STR_TO_DATE('".$submission_date[1]."', '%d/%m/%Y') AND last_submission_date >= STR_TO_DATE('".$submission_date[1]."', '%d/%m/%Y') AND contract_type = 2 AND contract_year = ".$this->pfr_year);
					}
				}

				continue;
			}
			// Data rows from hereon
			$row_totaliser = 0;
			$db_row = array();

			for( $column_position = 0, $colCount = count($row); $column_position < $colCount; $column_position++)
			{
				if($column_position > $numColumnTitles){
					break;
				}
				$columnTitle = $columnTitles[$column_position];
				$sunesisFieldName = isset($header_keys[$columnTitle]) ? $header_keys[$columnTitle] : null;
				if(empty($sunesisFieldName)){
					continue; // No map between column title and database table
				}

				// Get column value, and detect and remove currency formatting
				$value = $row[$column_position];
				if(preg_match('/^([\\-+]?)(?:[A-Z]{3,3} |[^0-9A-Z])([0-9][0-9\\.,]*)$/', $value, $matches)){
					$value = $matches[1] . $matches[2]; // Extract sign and value
				}

				// RE 27/09/2012
				// pad the values to the 12 character length
				if ( $sunesisFieldName == 'L03'  ) {
					$value = str_pad($value, 12, '0', STR_PAD_LEFT);
				}

				// sort the financial format out...
				// only once the textual data is passed ( > 15 bit )
				if ( $column_position > 15 ) {
					if(is_numeric($value)) {
						$numeric_value =  sprintf("%.2f", $value,2);
						if ($sunesisFieldName != 'outcome_indicator') {
							$row_totaliser += $numeric_value;
						}
						$db_row[$sunesisFieldName] = $numeric_value;
					}
					elseif(preg_match('/(.*)_date$/', $sunesisFieldName)) {
						// $input_date = preg_split('/\//', $value);
						// if ( sizeof($input_date) == 3 ) {
						// 	$db_row[$sunesisFieldName] = $value;
						// }
						$db_row[$sunesisFieldName] = $value;
					}
				}
				else {
					if ( $sunesisFieldName == 'outcome_indicator' && !is_numeric($value) ) {
						$db_row[$sunesisFieldName] = 0;
					}
					else {
						$db_row[$sunesisFieldName] = $value;
						if ($sunesisFieldName == 'qualification_title' )	{
							$db_row['A09'] = $value;
						}
					}
				}
			}

			if($row_totaliser > 0) {
				$db_row['grand_total'] = $row_totaliser;
				$db_row['import_id'] = $this->import_id;
				$db_rows[] = $db_row;
				if(count($db_rows) > 50) {
					DAO::multipleRowInsert($link, "rpt_reconciler_pfr", $db_rows);
					$fish[] = $db_rows;
					$db_rows = array();
				}
			}
		}
		// Write remaining rows to the temporary PFR table
		DAO::multipleRowInsert($link, "rpt_reconciler_pfr", $db_rows);
		$fish[] = $db_rows;

		// set the period in the header table
		DAO::execute($link, "update rpt_reconciler_header set period = '".$this->submission_period."', pfr_year = '".$this->pfr_year."' where import_id = ".$this->import_id);
	}


	private function clear_pfr_from_memory()
	{
		$this->reconciler->occ_reconciler_headers = NULL;
		unset($this->reconciler->occ_reconciler_headers);
	}


	/**
	 * @param PDO $link
	 * @return string
	 */
	private function get_comparable_contracts(PDO $link)
	{
		$contracts_list = array();

		// if the user has requested an automated contract lookup do it here
		$contract_query = 'SELECT DISTINCT(ilr.contract_id) as contractid FROM ilr, rpt_reconciler_pfr, contracts ';
		$contract_query .= 'WHERE ilr.A09 = rpt_reconciler_pfr.A09 ';
		$contract_query .= 'AND ilr.L03 = rpt_reconciler_pfr.L03 ';
		$contract_query .= 'AND ilr.contract_id = contracts.id ';
		$contract_query .= 'AND contracts.contract_year = "'.$this->pfr_year.'" ';

		$found_auto_contracts = 0;

		if( $result = $link->query($contract_query) ) {
			while( $row = $result->fetch() ) {
				$contracts_list[$row['contractid']] = $row['contractid'];
				$found_auto_contracts = 1;
			}
		}

		if ( $found_auto_contracts != 1 ) {
			http_redirect('do.php?_action=read_pfr&amp;mesg=Sorry, we couldn\'t find any relevant data in your PFR file. Are you sure it is a PFR file for your learners?');
			exit;
		}

		$these_contracts = implode(",", $contracts_list);

		return $these_contracts;

	}

	/**
	 * @param string $line
	 */
	private function check_memory($line = '') {
		$this->memory_result .= "line: ".$line.": ".round((memory_get_usage(true)/1024)/1024)." MB\n";
	}

	/**
	 * @param int $code
	 * @return string
	 */
	private function _getFileUploadError($code)
	{
		switch($code)
		{
			case UPLOAD_ERR_INI_SIZE:
				return "The PFR file exceeded the global maximum upload size of ".ini_get("upload_max_filesize");
				break;

			case UPLOAD_ERR_FORM_SIZE:
				return "The PFR file exceeded the maximum upload size for this page";
				break;

			case UPLOAD_ERR_PARTIAL:
				return "The PFR file was only partially uploaded";
				break;

			case UPLOAD_ERR_NO_FILE:
				return "No PFR file was uploaded";
				break;

			case UPLOAD_ERR_NO_TMP_DIR:
				return "Missing temporary folder";
				break;

			case UPLOAD_ERR_CANT_WRITE:
				return "Failed to write PFR file to disk";
				break;

			case UPLOAD_ERR_EXTENSION:
				return "PFR file upload stopped by extension";
				break;

			default:
				return "Unknown file-upload error code: ".$code;
				break;
		}
	}

	/** @var Reconciler */
	private $reconciler;

	private $debug_pfr_process = 0;

	private $memory_result = '';

	public $pfr_year = NULL;

	public $submission_period = NULL;

	// new ones:
	public $import_id = null;
}