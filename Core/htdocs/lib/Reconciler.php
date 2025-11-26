<?php
class Reconciler extends Entity {
	
	/***
	 * KNOWN ISSUES:
	 * 
	 * achievement should not be compared:
	 * 		- for current and future periods
	 * 			e.g. PFR FOR PERIOD 7: (P1_ach, P2_ach...etc ) acheivement matching only upto P6_ach on sunesis
	 * 		- for any learner who hasn't achieved
	 * 
	 * 			[17:31:01] Khushnood: If a learner is also an achiever then reconcile the 
	 * 			achievement payment otherwise if a learner is not an achiever 
	 * 			then do not reconcile achievement payment
	 * 
	 * 		- if 'outcome indicator' in PFR is set to 1 compare the achievement
	 * 
	 */

	function __construct() {
		
	}
	
	public function build_sunesis_data(PDO $link, $import_id, $contracts) {
		
		require_once('./lib/funding/FundingCore.php');
  		require_once('./lib/funding/PeriodLookup.php');
  		require_once('./lib/funding/LearnerFunding.php');
  		require_once('./lib/funding/FundingPeriod.php');
  		require_once('./lib/funding/FundingPrediction.php');
 		require_once('./lib/funding/FundingPredictionPeriod.php');

		// get the period from the import id
		$submission = DAO::getSingleValue($link, "SELECT period FROM rpt_reconciler_header where import_id = ".$import_id);

		// set to the period for the PFR file if possible
		if ( $submission == "" ) {
			// need to find out the current period as a default
			$submission = DAO::getSingleValue($link, "SELECT submission FROM central.lookup_submission_dates WHERE start_submission_date <= NOW() AND last_submission_date >= NOW() AND contract_type = 2");
		}
 		$this->reconciler_predictions = new FundingPredictionPeriod($link, $contracts, 13, "", "", "", $submission);
	}

	/**
	 * @param PDO $link
	 * @return string
	 */
	public function get_comparable_contracts(PDO $link, $pfr_year = null)
	{
		// set the default pfr year, if not set
		if ( !isset($pfr_year) ) {
			$pfr_year = $this->_getPfrYear();
		}
		$contracts_list = array();

		// if the user has requested an automated contract lookup do it here
		// $contract_query = 'SELECT DISTINCT(ilr.contract_id) as contractid FROM ilr, rpt_reconciler_pfr, contracts ';
		// $contract_query .= "WHERE extractvalue(ilr,'/ilr/main/A09|/ilr/subaim/A09') LIKE CONCAT('%', rpt_reconciler_pfr.A09)";
		// $contract_query .= 'AND ilr.L03 = rpt_reconciler_pfr.L03 ';
		// $contract_query .= 'AND ilr.contract_id = contracts.id ';
		// $contract_query .= 'AND contracts.contract_year = "'.$pfr_year.'" ';

		$contract_query = 'SELECT ilr.contract_id AS contractid FROM contracts, ilr ';
		$contract_query .= 'WHERE contract_year = "'.$pfr_year.'" ';
		$contract_query .= 'AND contracts.id = ilr.contract_id ';
		$contract_query .= 'GROUP BY ilr.contract_id HAVING COUNT(ilr.contract_id) > 0';
		$found_auto_contracts = 0;

		if( $result = $link->query($contract_query) ) {
			while( $row = $result->fetch() ) {
				$contracts_list[$row['contractid']] = $row['contractid'];
				$found_auto_contracts = 1;
			}
		}

		if ( $found_auto_contracts != 1 ) {
			$this->number_of_learners = 0;
			http_redirect('do.php?_action=read_pfr&amp;mesg=Sorry, we couldn\'t find any relevant data in your PFR file. Are you sure it is a PFR file for your learners?');
			exit;
		}

		$these_contracts = implode(",", $contracts_list);

		return $these_contracts;

	}

	public function set_reconciler_contracts(PDO $link, $table_name = '', $import_id = null) {

		if ( $table_name == '' || $import_id == null || ( $table_name != 'pfr' && $table_name != 'sunesis') ) {
			return;
		}

		$pfr_year = DAO::getSingleValue($link, "select pfr_year from rpt_reconciler_header where import_id = ".$import_id);

		if ( $pfr_year == '' ) {
			$pfr_year = $this->_getPfrYear();
		}

		// set all the main aims up
		$update_pfr_contract_sql = 'UPDATE rpt_reconciler_'.$table_name.', ilr, contracts ';
		$update_pfr_contract_sql .= 'SET rpt_reconciler_'.$table_name.'.contract_id = ilr.contract_id ';
		if ( $pfr_year <= 2011 ) {
			$update_pfr_contract_sql .= "WHERE extractvalue(ilr,'/ilr/main/A09|/ilr/subaim/A09') LIKE CONCAT('%',rpt_reconciler_".$table_name.".A09,'%') ";
		}
		else {
			$update_pfr_contract_sql .= "WHERE extractvalue(ilr,'/Learner/LearningDelivery/LearnAimRef') LIKE CONCAT('%',rpt_reconciler_".$table_name.".A09,'%') ";
		}
		// $update_pfr_contract_sql .= 'AND rpt_reconciler_'.$table_name.'.L03 = ilr.L03 ';
		$update_pfr_contract_sql .= 'AND rpt_reconciler_'.$table_name.'.ULN = extractvalue(ilr,"/Learner/ULN") ';
		$update_pfr_contract_sql .= 'AND ilr.contract_id = contracts.id ';
		$update_pfr_contract_sql .= 'AND contracts.contract_year = "'.$pfr_year.'" ';
		$update_pfr_contract_sql .= 'AND rpt_reconciler_'.$table_name.'.import_id = "'.$import_id.'"';

		DAO::execute($link, $update_pfr_contract_sql);

		// do the subisiduary ones
		$update_pfr_contract_sql = 'UPDATE rpt_reconciler_'.$table_name.' AS a, rpt_reconciler_'.$table_name.' AS b ';
		// $update_pfr_contract_sql .= 'SET a.contract_id = b.contract_id WHERE a.L03 = b.L03 AND a.contract_id is NULL AND b.contract_id IS NOT NULL ';
		$update_pfr_contract_sql .= 'SET a.contract_id = b.contract_id WHERE a.ULN = b.ULN AND a.contract_id is NULL AND b.contract_id IS NOT NULL ';
		$update_pfr_contract_sql .= 'AND a.import_id = "'.$import_id.'" AND a.import_id = b.import_id';

		DAO::execute($link, $update_pfr_contract_sql);
	}
	
		// Create the table for holding the pfr data
	// ---
	public function createSunesisTable(PDO $link) {
		$this->createTable($link, "rpt_reconciler_sunesis");
	}

	// Create the table for holding the pfr data
	// ---
	public function createPfrTable(PDO $link) {
		$this->createTable($link, "rpt_reconciler_pfr");
	}
	
	//
	// ---
	public function createTable(PDO $link, $table_name = '' ) 	{
		if ( $table_name == '' ) {
			return;
		}
		// check if the table already exists
		$sql_table_data = "select count(*) from information_schema.tables WHERE TABLE_SCHEMA = '".DB_NAME."'  and TABLE_NAME in ('".$table_name."')";
		$table_exists = DAO::getSingleValue($link, $sql_table_data);

		if ( $table_exists == 0 ) {
			$sql = <<<HEREDOC
			CREATE TABLE {$table_name} (
				id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
				 `import_id` int(10) NOT NULL,
				`name` varchar(200) default '',
				`ULN` varchar(12) default '',
				`A09` varchar(12) default '',
				`L03` varchar(12) default '',
				`provider_name` varchar(200) default '',
				`course_name` varchar(200) default '',
				`employer_name` varchar(200) default '',
				`qualification_title` varchar(200) default '',
				`learner_start_date` varchar(200) default '',
				`learner_target_end_date` varchar(200) default '',
				`learner_end_date` varchar(200) default '',
				`entry_end_date` varchar(200) default '',
				`outcome_indicator` int(1) default 0,
				`P1_OPP` FLOAT(9,2) UNSIGNED default 0.00, 
				`P1_bal` FLOAT(9,2) UNSIGNED default 0.00, 
				`P1_ach` FLOAT(9,2) UNSIGNED default 0.00, 
				`P1_total` FLOAT(9,2) UNSIGNED default 0.00, 
				`P2_OPP` FLOAT(9,2) UNSIGNED default 0.00, 
				`P2_bal` FLOAT(9,2) UNSIGNED default 0.00, 
				`P2_ach` FLOAT(9,2) UNSIGNED default 0.00, 
				`P2_total` FLOAT(9,2) UNSIGNED default 0.00,
				`P3_OPP` FLOAT(9,2) UNSIGNED default 0.00, 
				`P3_bal` FLOAT(9,2) UNSIGNED default 0.00, 
				`P3_ach` FLOAT(9,2) UNSIGNED default 0.00, 
				`P3_total` FLOAT(9,2) UNSIGNED default 0.00, 
				`P4_OPP` FLOAT(9,2) UNSIGNED default 0.00, 
				`P4_bal` FLOAT(9,2) UNSIGNED default 0.00, 
				`P4_ach` FLOAT(9,2) UNSIGNED default 0.00, 
				`P4_total` FLOAT(9,2) UNSIGNED default 0.00, 
				`P5_OPP` FLOAT(9,2) UNSIGNED default 0.00, 
				`P5_bal` FLOAT(9,2) UNSIGNED default 0.00, 
				`P5_ach` FLOAT(9,2) UNSIGNED default 0.00, 
				`P5_total` FLOAT(9,2) UNSIGNED default 0.00, 
				`P6_OPP` FLOAT(9,2) UNSIGNED default 0.00, 
				`P6_bal` FLOAT(9,2) UNSIGNED default 0.00, 
				`P6_ach` FLOAT(9,2) UNSIGNED default 0.00, 
				`P6_total` FLOAT(9,2) UNSIGNED default 0.00, 
				`P7_OPP` FLOAT(9,2) UNSIGNED default 0.00,
				`P7_bal` FLOAT(9,2) UNSIGNED default 0.00,
				`P7_ach` FLOAT(9,2) UNSIGNED default 0.00,
				`P7_total` FLOAT(9,2) UNSIGNED default 0.00,
				`P8_OPP` FLOAT(9,2) UNSIGNED default 0.00, 
				`P8_bal` FLOAT(9,2) UNSIGNED default 0.00,
				`P8_ach` FLOAT(9,2) UNSIGNED default 0.00,
				`P8_total` FLOAT(9,2) UNSIGNED default 0.00,
				`P9_OPP` FLOAT(9,2) UNSIGNED default 0.00,
				`P9_bal` FLOAT(9,2) UNSIGNED default 0.00, 
				`P9_ach` FLOAT(9,2) UNSIGNED default 0.00,
				`P9_total` FLOAT(9,2) UNSIGNED default 0.00,
				`P10_OPP` FLOAT(9,2) UNSIGNED default 0.00,
				`P10_bal` FLOAT(9,2) UNSIGNED default 0.00,
				`P10_ach` FLOAT(9,2) UNSIGNED default 0.00,
				`P10_total` FLOAT(9,2) UNSIGNED default 0.00,
				`P11_OPP` FLOAT(9,2) UNSIGNED default 0.00, 
				`P11_bal` FLOAT(9,2) UNSIGNED default 0.00,
				`P11_ach` FLOAT(9,2) UNSIGNED default 0.00, 
				`P11_total` FLOAT(9,2) UNSIGNED default 0.00,
				`P12_OPP` FLOAT(9,2) UNSIGNED default 0.00, 
				`P12_bal` FLOAT(9,2) UNSIGNED default 0.00,
				`P12_ach` FLOAT(9,2) UNSIGNED default 0.00,
				`P12_total` FLOAT(9,2) UNSIGNED default 0.00, 
				`grand_total` FLOAT(9,2) UNSIGNED default 0.00,
				`contract_id` int(11) default NULL,
				`record_status` INT(1) NOT NULL DEFAULT '0',
				`record_issue` text default null,
				PRIMARY KEY(id),
				UNIQUE KEY `import_aim` (`import_id`,`A09`,`ULN`),
				KEY `contracts` (`import_id`,`ULN`),
				KEY `learning_aim` (`A09`,`ULN`)
			) ENGINE=InnoDB
HEREDOC;
			DAO::execute($link, $sql);
		}
	}

	public function createHeaderTable(PDO $link) {
				// check if the table already exists
		$sql_table_data = "select count(*) from information_schema.tables WHERE TABLE_SCHEMA = '".DB_NAME."'  and TABLE_NAME = 'rpt_reconciler_header' ";
		$table_exists = DAO::getSingleValue($link, $sql_table_data);

		if ( $table_exists == 0 ) {
			$sql = <<<HEREDOC
			CREATE TABLE rpt_reconciler_header (
				id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
				`import_id` int(10) NOT NULL,
				`filename` varchar(200) default null,
				`pfr_year` int(4) DEFAULT '0000',
				`period` varchar(4) default null,
				`import_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY(id),
				UNIQUE KEY `import_aim` (`import_id`,`filename`)
			) ENGINE=InnoDB
HEREDOC;
			DAO::execute($link, $sql);
		}
	}

	public function createReportTable(PDO $link) {
		// check if the table already exists
		$sql_table_data = "select count(*) from information_schema.tables WHERE TABLE_SCHEMA = '".DB_NAME."'  and TABLE_NAME = 'rpt_reconciler_snapshot' ";
		$table_exists = DAO::getSingleValue($link, $sql_table_data);

		if ( $table_exists == 0 ) {
			$sql = <<<HEREDOC
			CREATE TABLE rpt_reconciler_snapshot (
  				`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
 				`number_of_learners` INT(8) DEFAULT 0,
  				`number_sunesis_learners` INT(8) DEFAULT 0,
  				`sunesis_count` INT(8) DEFAULT 0,
  				`sunesis_discrep_count` INT(8) DEFAULT 0,
  				`pfr_count` INT(8) DEFAULT 0,
  				`pfr_missing_count` INT(8) DEFAULT 0,
  				`pfr_missing_totaliser` FLOAT(10,2) DEFAULT '0.00',
  				`discrepancy_totaliser` FLOAT(10,2) DEFAULT '0.00',
  				`missing_totaliser` FLOAT(10,2) DEFAULT '0.00',
  				`number_matched_lines` INT(8) DEFAULT 0,
  				`present_discrepancy_count` INT(8) DEFAULT 0,
  				`present_discrepancy_html` LONGTEXT,
  				`number_missing_value_lines` INT(8) DEFAULT 0,
  				`missing_value_lines_html` LONGTEXT,
  				`number_pfr_missing_lines` INT(8) DEFAULT 0,
  				`missing_pfr_lines_html` LONGTEXT,
  				`pfr_cash_count` FLOAT(10,2) DEFAULT '0.00',
  				`sunesis_cash_count` FLOAT(10,2) DEFAULT '0.00',
  				`sunesis_cash_percentage`  INT(3) DEFAULT 0,
  				`sunesis_accuracy` VARCHAR(200) DEFAULT "{ type: 'bar', name: 'Financial Discrepancy', data: [0.00], color: '#89A54E' }",
  				`import_id` INT(10) NOT NULL,
  				`timelog` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  				PRIMARY KEY(id),
  				UNIQUE KEY `report_snapshot` (`import_id`,`timelog`)
  			) ENGINE=INNODB;
HEREDOC;
			DAO::execute($link, $sql);
		}

	}

	public function setHeader(PDO $link, $filename = '', $import_id = '', $pfr_year = '', $submission_period = '' ) {
		if ( $filename != '' && $import_id != '' && is_numeric($import_id) ) {

			// if we have no pfr year, we assume
			// we use the current academic one.
			if ( $pfr_year == '' ) {
				$pfr_year = $this->_getPfrYear();
			}

			// ---
			// For Reference:
			// if submission period is blank, it is a new import, so will populate once we've built the data??
			// ----

			$insert_reconciler_sql = 'insert into rpt_reconciler_header (import_id, filename, pfr_year, period) values ("'.$import_id.'","'.addslashes((string)$filename).'", "'.$pfr_year.'", "'.$submission_period.'")';
			DAO::execute($link, $insert_reconciler_sql);
		}
		return;
	}

	/*
	 * check if the record is in a valid contract for this reconcile
	 * TODO: make this more efficient gets called too much....
	 */
	public function check_record_contract( PDO $link, $LO3 = '', $AO9 = '', $pfr_year = '' ) {

		$checked_contract = array('name' => '', 'is_in_selected' => 0);

		// if we have no pfr year, we assume
		// we use the current academic one.
		if ( $pfr_year == '' ) {
			$pfr_year = $this->_getPfrYear();
		}

		if ( $LO3 != '' && $AO9 != '' ) {
			$contract_query = 'SELECT tr.contract_id FROM tr LEFT JOIN student_qualifications ON (student_qualifications.tr_id = tr.id), contracts WHERE tr.L03 = "'.$LO3;
			$contract_query .= '" AND REPLACE(student_qualifications.id,"/", "") = "'.$AO9;
			$contract_query .= '" and tr.contract_id = contracts.id and contracts.contract_year = "'.$pfr_year.'" ';

			$learner_contract_id = DAO::getSingleValue($link, $contract_query);
			if ( isset($this->contract_list[$learner_contract_id]) ) {
				$checked_contract['name'] = $this->contract_list[$learner_contract_id];
				$checked_contract['is_in_selected'] = 1;
				return $checked_contract;
			}
		}

		if ( $learner_contract_id != '' ) {
			$get_missing_contract_query = 'SELECT title FROM contracts where id = '.$learner_contract_id;
			$missing_contract_title = DAO::getSingleValue($link, $get_missing_contract_query);
			$checked_contract['name'] = $missing_contract_title;
		}
		return $checked_contract;
	}

	public function getImportID($link) {
		return DAO::getSingleValue($link, 'SELECT MAX(import_id) AS import_count FROM rpt_reconciler_pfr');
	}

	private function _getPfrYear() {
		$pfr_year = date('Y');
		if ( date('n') <= 7 ) {
			$pfr_year--;
		}
		return $pfr_year;
	}
				
	// contains the 'coversion map' of the sunesis format (key) reconciler data headers (values)
	// --
	public $reconciler_datamap_array = array(
						'name' => 'name'
						,'L03' => 'learner reference number'
						,'ULN' => 'unique learner number'
						,'provider_name' => 'provider_name'
						,'course_name' => 'learning aim title'
						,'employer_name' => 'employer identifier'
						,'qualification_title' =P1_OPP> 'learning aim reference'
						,'learner_start_date' => 'learning start date'
						,'learner_target_end_date' => 'learning planned end date'
						,'learner_end_date' => 'learning actual end date'
						,'entry_end_date' => 'achievement date'
						,'outcome_indicator' => 'outcome indicator'
						,'P1_OPP' => 'august on programme earned cash'
						,'P1_bal' => 'august balancing payment earned cash'
						,'P1_ach' => 'august achievement earned cash'
						,'P1_total' => 'P1_total'
						,'P2_OPP' => 'september on programme earned cash'
						,'P2_bal' => 'september balancing payment earned cash'
						,'P2_ach' => 'september achievement earned cash'
						,'P2_total' => 'P2_total'
						,'P3_OPP' => 'october on programme earned cash'
						,'P3_bal' => 'october balancing payment earned cash'
						,'P3_ach' => 'october achievement earned cash'
						,'P3_total' => 'P3_total'
						,'P4_OPP' => 'november on programme earned cash'
						,'P4_bal' => 'november balancing payment earned cash'
						,'P4_ach' => 'november achievement earned cash'
						,'P4_total' => 'P4_total'
						,'P5_OPP' => 'december on programme earned cash'
						,'P5_bal' => 'december balancing payment earned cash'
						,'P5_ach' => 'december achievement earned cash'
						,'P5_total' => 'P5_total'
						,'P6_OPP' => 'january on programme earned cash'
						,'P6_bal' => 'january balancing payment earned cash'
						,'P6_ach' => 'january achievement earned cash'
						,'P6_total' => 'P6_total'
						,'P7_OPP' => 'february on programme earned cash'
						,'P7_bal' => 'february balancing payment earned cash'
						,'P7_ach' => 'february achievement earned cash'
						,'P7_total' => 'P7_total'
						,'P8_OPP' => 'march on programme earned cash'
						,'P8_bal' => 'march balancing payment earned cash'
						,'P8_ach' => 'march achievement earned cash'
						,'P8_total' => 'P8_total'
						,'P9_OPP' => 'april on programme earned cash'
						,'P9_bal' => 'april balancing payment earned cash'
						,'P9_ach' => 'april achievement earned cash'
						,'P9_total' => 'P9_total'
						,'P10_OPP' => 'may on programme earned cash'
						,'P10_bal' => 'may balancing payment earned cash'
						,'P10_ach' => 'may achievement earned cash'
						,'P10_total' => 'P10_total'
						,'P11_OPP' => 'june on programme earned cash'
						,'P11_bal' => 'june balancing payment earned cash'
						,'P11_ach' => 'june achievement earned cash'
						,'P11_total' => 'P11_total'
						,'P12_OPP' => 'july on programme earned cash'
						,'P12_bal' => 'july balancing payment earned cash'
						,'P12_ach' => 'july achievement earned cash'
						,'P12_total' => 'P12_total'
						,'grand_total' => 'grand_total'
						,'on_payment' => 'on_payment'
						,'achieved' => 'achieved'
						,'balancing' => 'balancing'
		);

		public static function formatCash($value) {
			return '&pound;'.number_format(sprintf("%.2f", $value),2,".",",");
		}
		
		public $occ_reconciler_headers = array();
		public $occ_reconciler_data = array();
		public $occ_sunesis_data = array();
		public $reconciler_predictions;
}
?>