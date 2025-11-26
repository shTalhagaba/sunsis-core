<?php

class ajax_build_sunesis_pfr_data implements IAction
{
	/**
	 * @param PDO $link
	 */
	public function execute(PDO $link) {
		// populate the contracts in Sunesis
		// for use in the selection of contracts to reconcile
		// ---

		$contracts = isset($_REQUEST['contract']) ? implode(",", $_REQUEST['contract']) : '';

		$this->build_contracts($link);
		$this->reconciler = new Reconciler();

		$this->import_id = $this->reconciler->getImportID($link);

		if ( '' == $contracts && isset($_REQUEST['contract_auto_detect']) ) {

			$contracts = $this->reconciler->get_comparable_contracts($link, $_REQUEST['pfr_year']);

			if ( '' != $contracts ) {
				// gets the funding data from sunesis
				// here is the data bit
				$this->reconciler->build_sunesis_data($link, $this->import_id, $contracts);
				$this->refine_contracts($contracts);
			}
		}


		if ( $this->debug_pfr_process === 1 ) {
			$this->check_memory(__line__);
		}

		// save the sunesis data
		// ----
		$this->reconciler->createSunesisTable($link);				// build the temporary table for Sunesis data.
		$this->store_sunesis_data($link);
		$this->clear_sunesis_from_memory();
		if ( $this->debug_pfr_process === 1 ) {
			$this->check_memory(__line__);
		}
		echo 'OK';
		exit;
	}

	/**
	 * Get all the contracts available on the system
	 * to compare pfr data against
	 * @param PDO $link
	 */
	public function build_contracts(PDO $link)
	{
		$contract_predictor_object = GetContractsPredictor::getInstance();
		$st = $link->query($contract_predictor_object->getSQL());
		if( $st ) {
			while( $row = $st->fetch() ) {
				$this->contract_list[$row['id']] = $row['title'];
			}
		}
	}

	/*
		 * reduce the amount of contracs we are working with
		 * based on the user filter or the auto selected
		 */
	public function refine_contracts($selected_contracts = '')
	{
		$only_use_contracts = explode(",", $selected_contracts);
		$temp_array = array();
		foreach ( $only_use_contracts as $contract_incre_no => $contract_id ) {
			if ( isset($this->contract_list[$contract_id]) ) {
				$temp_array[$contract_id] = $this->contract_list[$contract_id];
			}
		}
		unset($this->contract_list);
		$this->contract_list = $temp_array;
		unset($temp_array);
	}

	/**
	 * @param PDO $link
	 * @return int
	 */

	/**
	 * @param PDO $link
	 */
	private function store_sunesis_data(PDO $link)
	{
		$sunesis_learners = $this->reconciler->reconciler_predictions->get_learnerdata();

		$header_count = 0;

		// why do we need a duplicate check?
		$duplicated_learners = array();

		foreach ( $sunesis_learners as $id => $sunesis_learner_data ) {

			$sunesis_data_sql_columns = '';
			$sunesis_data_sql_values = '';
			// need to get just the qualification code
			$qan_code_content = explode(' ', $sunesis_learner_data['qualification_title']);
			$qan_code = str_replace("/", "", $qan_code_content[0]);


			$learner_detail = $qan_code."_".$sunesis_learner_data['L03'];
			// set & check duplicates
			if ( isset($duplicated_learners[$learner_detail]) ) {
				$duplicated_learners[$learner_detail]++;
				continue;
			}
			else {
				$duplicated_learners[$learner_detail] = 1;
			}


			$this_uln = '999999999';
			// get the ULN from Sunesis for this learner
			// ---
			$this_uln = DAO::getSingleValue($link, 'SELECT DISTINCT(extractvalue(ilr, "/Learner/ULN")) AS ULN FROM ilr WHERE L03 = "'.$sunesis_learner_data['L03'].'" AND extractvalue(ilr, "/Learner/ULN") != ""');

			foreach ( $sunesis_learner_data as $sunesis_column => $sunesis_data ) {
				if(isset($this->reconciler->reconciler_datamap_array[$sunesis_column])) {
					// sort the financial format out...
					// only once the textual data is passed ( > 15 bit )
					if ( $sunesis_data != "" ) {
						if ( is_numeric($sunesis_data) ) {
							$numeric_value =  sprintf("%.2f", round($sunesis_data));
							$sunesis_data_sql_columns .= $sunesis_column.",";
							$sunesis_data_sql_values .= "'".$sunesis_data."',";
						}
						else {
							if ( $sunesis_column == 'outcome_indicator' && !is_numeric($sunesis_data) ) {
								$sunesis_data_sql_columns .= $sunesis_column.",";
								$sunesis_data_sql_values .= "0,\n";
							}
							else {
								$sunesis_data_sql_columns .= $sunesis_column.",";
								$sunesis_data_sql_values .= "'".addslashes((string)$sunesis_data)."',\n";
							}
						}
					}
				}
			}

			$insert_sql = "insert into rpt_reconciler_sunesis (import_id, ULN, ".$sunesis_data_sql_columns." A09, record_issue) values (".$this->import_id.", '".$this_uln."', ".$sunesis_data_sql_values." '".$qan_code."', '<tr><th>Column</th><th>Sunesis</th><th>PFR</th></tr>')";


			DAO::execute($link, $insert_sql );
		}

		$this->sunesis_count = DAO::getSingleValue($link, "select count(*) from rpt_reconciler_sunesis;");
	}

	private function clear_sunesis_from_memory() {
		$this->reconciler->reconciler_predictions = NULL;
		unset($this->reconciler->reconciler_predictions);
	}


	private function check_memory($line = '') {
		$this->memory_result .= "line: ".$line.": ".round((memory_get_usage(true)/1024)/1024)." MB\n";
	}

	/** @var Reconciler */
	private $reconciler;

	private $debug_pfr_process = 0;

	private $memory_result = '';

	public $pfr_year = NULL;

	public $reconciler_data = array();

	// contracts on Sunesis for this client
	public $contract_list = array();

	public $sunesis_count = 0;
	public $sunesis_discrep_count = 0;

	public $import_id = null;
}