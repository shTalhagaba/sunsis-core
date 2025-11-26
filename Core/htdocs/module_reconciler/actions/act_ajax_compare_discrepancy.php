<?php

class ajax_compare_discrepancy implements IAction
{
	/**
	 * @param PDO $link
	 */
	public function execute(PDO $link) {

		$this->reconciler = new Reconciler();
		$this->import_id = $this->reconciler->getImportID($link);

		// set the contract information up
		$this->reconciler->set_reconciler_contracts($link, 'pfr', $this->import_id);
		$this->reconciler->set_reconciler_contracts($link, 'sunesis', $this->import_id);

		// get the differences and build the missing section
		$pfr_to_sunesis_diffs = $this->collate_missing_pfr_lines($link, $this->get_period_diffs($link));
		$this->collate_missing_sunesis_lines($link);

		$this->present_discrepancy_count = 0;

		// totalisers for discrepancies
		$this->discrepancy_totaliser = 0;
		$this->pfr_missing_totaliser = 0;

		// all items in PFR not in Sunesis.
		// $this->build_sunesis_missing($link);

		// row styles for the zebra effect
		// ---
		$row_style 		= '';

		//
		$row_L03 		= '';
		$row_A09		= '';
		$row_reference	= '';

		// this is where the comparisons we are doing
		// this is the key PFR lookup in Sunesis.
		// ----

		if ( $this->debug_pfr_process === 1 ) {
			$this->check_memory(__line__);
		}

		// count of number of discrpeant lines
		$db_rows = 0;
		$sunesis_status = '';
		$pfr_status = '';

		foreach ( $pfr_to_sunesis_diffs as $id => $financial_data_row )	{
			// $this->pfr_cash_count += $recon_learner_data['TOTAL'];

			// set a line discrepancy flag
			// ---
			// - needs to be able to disregard the ignore list
			// - differences
			// ---
			$line_discrepancy_flag = 0;

			if ( $this->debug_pfr_process === 1 ) {
				$this->check_memory(__line__);
			}

			// #TODO - resolve array in array return here....
			if ( !isset($financial_data_row['sunesis_id']) || $financial_data_row['sunesis_id'] == "" || !isset($financial_data_row['pfr_id']) || $financial_data_row['pfr_id'] == "" ) {
				continue;
			}
			$this_sunesis_data_row = DAO::getResultSet($link, "select *,(P1_OPP+P2_OPP+P3_OPP+P4_OPP+P5_OPP+P6_OPP+P7_OPP+P8_OPP+P9_OPP+P10_OPP+P11_OPP+P12_OPP) AS on_payment, (P1_bal+P2_bal+P3_bal+P4_bal+P5_bal+P6_bal+P7_bal+P8_bal+P9_bal+P10_bal+P11_bal+P12_bal) AS balancing, (P1_ach+P2_ach+P3_ach+P4_ach+P5_ach+P6_ach+P7_ach+P8_ach+P9_ach+P10_ach+P11_ach+P12_ach) AS achieved from rpt_reconciler_sunesis where id = ".$financial_data_row['sunesis_id'], DAO::FETCH_ASSOC);
			$this_pfr_data_row = DAO::getResultSet($link, "select *,(P1_OPP+P2_OPP+P3_OPP+P4_OPP+P5_OPP+P6_OPP+P7_OPP+P8_OPP+P9_OPP+P10_OPP+P11_OPP+P12_OPP) AS on_payment,	(P1_bal+P2_bal+P3_bal+P4_bal+P5_bal+P6_bal+P7_bal+P8_bal+P9_bal+P10_bal+P11_bal+P12_bal) AS balancing, (P1_ach+P2_ach+P3_ach+P4_ach+P5_ach+P6_ach+P7_ach+P8_ach+P9_ach+P10_ach+P11_ach+P12_ach) AS achieved from rpt_reconciler_pfr where id = ".$financial_data_row['pfr_id'], DAO::FETCH_ASSOC);

			// we only care about _ach values if outcome indicator is set to 1
			if ( $this_pfr_data_row[0]['outcome_indicator'] != 1 ) {
				unset($this_sunesis_data_row[0]['P1_ach']);
				unset($this_sunesis_data_row[0]['P2_ach']);
				unset($this_sunesis_data_row[0]['P3_ach']);
				unset($this_sunesis_data_row[0]['P4_ach']);
				unset($this_sunesis_data_row[0]['P5_ach']);
				unset($this_sunesis_data_row[0]['P6_ach']);
				unset($this_sunesis_data_row[0]['P7_ach']);
				unset($this_sunesis_data_row[0]['P8_ach']);
				unset($this_sunesis_data_row[0]['P9_ach']);
				unset($this_sunesis_data_row[0]['P10_ach']);
				unset($this_sunesis_data_row[0]['P11_ach']);
				unset($this_sunesis_data_row[0]['P12_ach']);
				$this_sunesis_data_row[0]['grand_total'] -= $this_sunesis_data_row[0]['achieved'];
				unset($this_sunesis_data_row[0]['achieved']);
				unset($this_pfr_data_row[0]['P1_ach']);
				unset($this_pfr_data_row[0]['P2_ach']);
				unset($this_pfr_data_row[0]['P3_ach']);
				unset($this_pfr_data_row[0]['P4_ach']);
				unset($this_pfr_data_row[0]['P5_ach']);
				unset($this_pfr_data_row[0]['P6_ach']);
				unset($this_pfr_data_row[0]['P7_ach']);
				unset($this_pfr_data_row[0]['P8_ach']);
				unset($this_pfr_data_row[0]['P9_ach']);
				unset($this_pfr_data_row[0]['P10_ach']);
				unset($this_pfr_data_row[0]['P11_ach']);
				unset($this_pfr_data_row[0]['P12_ach']);
				$this_pfr_data_row[0]['grand_total'] -= $this_pfr_data_row[0]['achieved'];
				unset($this_pfr_data_row[0]['achieved']);
			}

			$this_line_diff = array_diff_assoc($this_sunesis_data_row[0], $this_pfr_data_row[0]);

			if ( sizeof($this_line_diff ) > 0 ) {
				$reference = $this_sunesis_data_row[0]['name'];
				$sunesis = $this_sunesis_data_row[0];
				$pfr = $this_pfr_data_row[0];

				// tidy this ignore list out some way
				$ignore_list = array(
					'id'					=> 1,
					'name' 					=> 1,
					'L03' 					=> 1,
					'ULN' 					=> 1,
					'provider_name'			=> 1,
					'course_name' 			=> 1,
					'employer_name' 		=> 1,
					'qualification_title'	=> 1,
					'excel location'		=> 1,
					'TOTAL'					=> 1,
					'emploer_name'			=> 1,
					'grand_total'			=> 1,
					'P1_total'				=> 1,
					'P2_total'				=> 1,
					'P3_total'				=> 1,
					'P4_total'				=> 1,
					'P5_total'				=> 1,
					'P6_total'				=> 1,
					'P7_total'				=> 1,
					'P8_total'				=> 1,
					'P9_total'				=> 1,
					'P10_total'				=> 1,
					'P11_total'				=> 1,
					'P12_total'				=> 1,
					'outcome_indicator'		=> 1,
					'achieved'				=> 1,
					'on_payment'			=> 1,
					// 'entry_end_date'		=> 1,
					// 'learner_end_date'	=> 1,
					'contract_id'			=> 1,
					'record_issue'			=> 1
				);

				// variable for storing the issues
				// associated with the learning aim record
				// ---
				$record_issue = '';

				foreach ( $this_line_diff as $column_title => $column_value ) {
					// move on from the lines we want to ignore
					if ( isset($ignore_list[$column_title]) &&  $ignore_list[$column_title] == 1 ) {
						continue;
					}

					// doing some html buld here for actual issue detail
					// $record_issue = '<tr><td>'.$this->reconciler->reconciler_datamap_array[$column_title].'</td><td style="text-align:right;">';
					// if(preg_match('/(.*)_date$/', $column_title)) {
					// 	$record_issue .= $sunesis[$column_title];
					// 	$record_issue .= '</td><td style="text-align:right;">';
					// 	$record_issue .= $pfr[$column_title];
					// }
					// else {
					// 	$record_issue .= Reconciler::formatCash($sunesis[$column_title]);
					// 	$record_issue .= '</td><td style="text-align:right;">';
					// 	$record_issue .= Reconciler::formatCash($pfr[$column_title]);
					// }

					// $record_issue .= '</td></tr>';

					$sunesis_status .= $financial_data_row['sunesis_id'].',';
					$pfr_status .= $financial_data_row['pfr_id'].',';
					$db_rows++;

					// insert and set status to flag to user
					// THIS IS A WAY TOO SLOW WAY OF DOING THIS
					// DAO::execute($link, "update rpt_reconciler_sunesis set record_status = 1, record_issue = IF(record_issue is not null, CONCAT(record_issue, '".$record_issue."'), '".$record_issue."') where id = ".$financial_data_row['sunesis_id']);
					// DAO::execute($link, "update rpt_reconciler_pfr set record_status = 1, record_issue = IF(record_issue is not null, CONCAT(record_issue, '".$record_issue."'),'".$record_issue."') where id = ".$financial_data_row['pfr_id']);

					if( count($db_rows) > 500 ) {

						DAO::execute($link, "update rpt_reconciler_sunesis set record_status = 1 where id in (".rtrim($sunesis_status,",").")");
						DAO::execute($link, "update rpt_reconciler_pfr set record_status = 1 where id in (".rtrim($pfr_status,",").")");
						$db_rows = 0;
						$sunesis_status = '';
						$pfr_status = '';
					}
				}
			}
		}

		if( count($db_rows) > 0 && $sunesis_status != '' && $pfr_status != '' ) {
			DAO::execute($link, "update rpt_reconciler_sunesis set record_status = 1 where id in (".rtrim($sunesis_status,",").")");
			DAO::execute($link, "update rpt_reconciler_pfr set record_status = 1 where id in (".rtrim($pfr_status,",").")");
		}

		$this->present_discrepancy_count = DAO::getSingleValue($link, "select count(*) from rpt_reconciler_sunesis where import_id = ".$this->import_id." and record_status = 1");

		$this->number_of_learners = DAO::getSingleValue($link, "select count(*) from rpt_reconciler_pfr where import_id = ".$this->import_id);
		$this->number_sunesis_learners = DAO::getSingleValue($link, "select count(*) from rpt_reconciler_sunesis where import_id = ".$this->import_id);
		$this->number_matched_lines = DAO::getSingleValue($link, "select count(*) from rpt_reconciler_sunesis where record_status = 0 and import_id = ".$this->import_id);
		$this->number_missing_value_lines = DAO::getSingleValue($link, "select count(*) from rpt_reconciler_pfr where record_status = 2 and import_id = ".$this->import_id);

		// why are the these lines in here - re-assigning things?? - re-format the snapshot table.....
		// ---
		$this->pfr_missing_count = $this->number_missing_value_lines;
		$this->pfr_count = $this->number_of_learners;
		$this->sunesis_discrep_count = $this->present_discrepancy_count;

		$this->pfr_cash_count = DAO::getSingleValue($link, "select sum(grand_total) from rpt_reconciler_pfr where import_id = ".$this->import_id);
		$this->sunesis_cash_count = DAO::getSingleValue($link, "select sum(grand_total) from rpt_reconciler_sunesis where import_id = ".$this->import_id);

		$discrepancy = $this->sunesis_cash_count-$this->pfr_cash_count;
		$discrepancy_style = $this->color_scheme['bad'];

		if ( $discrepancy < 0 ) {
			$discrepancy_style = $this->color_scheme['good'];
		}
		$this->sunesis_accuracy = "{ type: 'bar', name: 'Financial Discrepancy', data: [$discrepancy], color: '".$discrepancy_style."' }";

		$this->sunesis_accuracy = addslashes((string)$this->sunesis_accuracy);

		$snapshot_sql = <<<HEREDOC
			insert into rpt_reconciler_snapshot (
				number_of_learners,
				number_sunesis_learners,
  				sunesis_count,
  				sunesis_discrep_count,
  				pfr_count,
  				pfr_missing_count,
 				pfr_missing_totaliser,
  				discrepancy_totaliser,
  				missing_totaliser,
  				number_matched_lines,
  				present_discrepancy_count,
  				present_discrepancy_html,
  				number_missing_value_lines,
  				missing_value_lines_html,
  				number_pfr_missing_lines,
  				missing_pfr_lines_html,
  				pfr_cash_count,
  				sunesis_cash_count,
  				sunesis_cash_percentage,
  				sunesis_accuracy,
  				import_id
			)
			values
			(
  				'{$this->number_of_learners}',
 				'{$this->number_sunesis_learners}',
  				'{$this->sunesis_count}',
  				'{$this->sunesis_discrep_count}',
  				'{$this->pfr_count}',
  				'{$this->pfr_missing_count}',
  				'{$this->pfr_missing_totaliser}',
				'{$this->discrepancy_totaliser}',
  				'{$this->missing_totaliser}',
  				'{$this->number_matched_lines}',
  				'{$this->present_discrepancy_count}',
 				'',
  				'{$this->number_missing_value_lines}',
  				'',
  				'{$this->number_pfr_missing_lines}',
 				'',
  				'{$this->pfr_cash_count}',
  				'{$this->sunesis_cash_count}',
  				'{$this->sunesis_cash_percentage}',
  				'{$this->sunesis_accuracy}',
  				'{$this->import_id}'
			)
HEREDOC;

		DAO::execute($link, $snapshot_sql);
		// RE - 16th October 2012 - why is this pulling in the id instead of import_id ??
		// echo DAO::getSingleValue($link, 'SELECT MAX(id) from rpt_reconciler_snapshot where import_id = '.$this->import_id);
		echo $this->import_id;
		exit;
	}

	private function get_period_diffs(PDO $link) {
		// only interested in the records that appear in both the PFR and Sunesis tables.

		$financial_sql = <<<HEREDOC
SELECT 	srt.id as sunesis_id, srt.name, srt.A09 as sunesis_a09, srt.L03 as sunesis_l03,
	prt.id as pfr_id, prt.A09 as pfr_a09, prt.L03 as pfr_l03,
	prt.contract_id as pfr_contract_id, srt.contract_id as contract_id,
	SUM(srt.P1_OPP-prt.P1_OPP) AS P1_OPP,
	SUM(srt.P1_bal-prt.P1_bal) AS P1_bal,
	SUM(srt.P1_ach-prt.P1_ach) AS P1_ach,
	SUM(srt.P2_OPP-prt.P2_OPP) AS P2_OPP,
	SUM(srt.P2_bal-prt.P2_bal) AS P2_bal,
	SUM(srt.P2_ach-prt.P2_ach) AS P2_ach,
	SUM(srt.P3_OPP-prt.P3_OPP) AS P3_OPP,
	SUM(srt.P3_bal-prt.P3_bal) AS P3_bal,
	SUM(srt.P3_ach-prt.P3_ach) AS P3_ach,
	SUM(srt.P4_OPP-prt.P4_OPP) AS P4_OPP,
	SUM(srt.P4_bal-prt.P4_bal) AS P4_bal,
	SUM(srt.P4_ach-prt.P4_ach) AS P4_ach,
	SUM(srt.P5_OPP-prt.P5_OPP) AS P5_OPP,
	SUM(srt.P5_bal-prt.P5_bal) AS P5_bal,
	SUM(srt.P5_ach-prt.P5_ach) AS P5_ach,
	SUM(srt.P6_OPP-prt.P6_OPP) AS P6_OPP,
	SUM(srt.P6_bal-prt.P6_bal) AS P6_bal,
	SUM(srt.P6_ach-prt.P6_ach) AS P6_ach,
	SUM(srt.P7_OPP-prt.P7_OPP) AS P7_OPP,
	SUM(srt.P7_bal-prt.P7_bal) AS P7_bal,
	SUM(srt.P7_ach-prt.P7_ach) AS P7_ach,
	SUM(srt.P8_OPP-prt.P8_OPP) AS P8_OPP,
	SUM(srt.P8_bal-prt.P8_bal) AS P8_bal,
	SUM(srt.P8_ach-prt.P8_ach) AS P8_ach,
	SUM(srt.P9_OPP-prt.P9_OPP) AS P9_OPP,
	SUM(srt.P9_bal-prt.P9_bal) AS P9_bal,
	SUM(srt.P9_ach-prt.P9_ach) AS P9_ach,
	SUM(srt.P10_OPP-prt.P10_OPP) AS P10_OPP,
	SUM(srt.P10_bal-prt.P10_bal) AS P10_bal,
	SUM(srt.P10_ach-prt.P10_ach) AS P10_ach,
	SUM(srt.P11_OPP-prt.P11_OPP) AS P11_OPP,
	SUM(srt.P11_bal-prt.P11_bal) AS P11_bal,
	SUM(srt.P11_ach-prt.P11_ach) AS P11_ach,
	SUM(srt.P12_OPP-prt.P12_OPP) AS P12_OPP,
	SUM(srt.P12_bal-prt.P12_bal) AS P12_bal,
	SUM(srt.P12_ach-prt.P12_ach) AS P12_ach,
	SUM(srt.grand_total-prt.grand_total) AS grand_total,
	srt.grand_total as sunesis_grand_total,
	prt.grand_total as pfr_grand_total
FROM
	rpt_reconciler_sunesis AS srt
	LEFT JOIN rpt_reconciler_pfr AS prt
	ON (prt.A09 = srt.A09
	# AND prt.L03 = srt.L03
	AND prt.ULN = srt.ULN
	AND prt.import_id = srt.import_id)
WHERE
	srt.import_id = {$this->import_id}
GROUP BY
	srt.A09, srt.L03;
HEREDOC;

		return DAO::getResultSet($link, $financial_sql, DAO::FETCH_ASSOC);
	}

	/**
	 * @param PDO $link
	 * @param array $pfr_to_sunesis_diffs
	 * @return mixed
	 */
	private function collate_missing_pfr_lines(PDO $link, $pfr_to_sunesis_diffs) {

		// set the data flag, so we can report on
		// historical data instead of re-querying
		$flag_missing_sql = <<<HEREDOC
UPDATE
	rpt_reconciler_pfr AS prt
	RIGHT JOIN rpt_reconciler_sunesis AS srt
	ON (
		prt.A09 = srt.A09
		# AND prt.L03 = srt.L03
		AND prt.ULN = srt.ULN
		AND prt.import_id = srt.import_id
	)
SET
	srt.record_status = 2
WHERE
    prt.id is NULL;
HEREDOC;

		DAO::execute($link, $flag_missing_sql);

		// set the count of the missing lines in the PFR
		$this->number_pfr_missing_lines = DAO::getSingleValue($link, "select count(*) from rpt_reconciler_sunesis where import_id = ".$this->import_id." and record_status = 2");


		return $pfr_to_sunesis_diffs;
	}

	/**
	 * @param PDO $link
	 * @return array
	 */
	private function collate_missing_sunesis_lines(PDO $link) {
		// set the data flag, so we can report on
		// historical data instead of re-querying
		$flag_missing_sql = <<<HEREDOC
UPDATE
	rpt_reconciler_sunesis AS srt
	RIGHT JOIN rpt_reconciler_pfr AS prt
	ON (
		prt.A09 = srt.A09
		# AND prt.L03 = srt.L03
		AND prt.ULN = srt.ULN
		AND prt.import_id = srt.import_id
	)
SET
	prt.record_status = 2
WHERE
    srt.id is NULL
AND
	prt.import_id = {$this->import_id}
HEREDOC;

		DAO::execute($link, $flag_missing_sql);

		// set the number of lines missing from the pfr
		// $this->number_missing_value_lines = DAO::getSingleValue($link, "SELECT * from rpt_reconciler_pfr where record_status = 2 and import_id = ".$this->import_id);
		return;
	}

	private function summarise_missing_sunesis_lines(PDO $link) {
		$financial_sql = 'select count(*) as missing_lines, sum(grand_total) as missing_values from rpt_reconciler_pfr where record_status = 2 and import_id = '.$this->import_id;
		return DAO::getResultSet($link, $financial_sql, DAO::FETCH_ASSOC);
	}

	private function clear_sunesis_from_memory() {
		$this->reconciler->reconciler_predictions = NULL;
		unset($this->reconciler->reconciler_predictions);
	}


	// NOT SURE WE NEED ALL THESE DECLARATIONS?
	public $color_scheme = array(
		'good' 	=> '#AA4643',
		'ok'	=> '#4572A7',
		'bad'	=> '#89A54E'
	);

	/**
	 * @param string $line
	 */
	private function check_memory($line = '') {
		$this->memory_result .= "line: ".$line.": ".round((memory_get_usage(true)/1024)/1024)." MB\n";
	}

	private function _formatCash($value) {
		return '&pound;'.number_format(sprintf("%.2f", $value),2,".",",");
	}

	/** @var Reconciler */
	private $reconciler;

	private $debug_pfr_process = 0;

	private $memory_result = '';

	public $pfr_year = NULL;

	public $reconciler_data = array();

	// contracts on Sunesis for this client
	public $contract_list = array();

	// Number of PFR learner records
	public $number_of_learners = 0;
	// Number of Sunesis learner records
	public $number_sunesis_learners = 0;

	public $sunesis_count = 0;
	public $sunesis_discrep_count = 0;
	public $pfr_count = 0;
	public $pfr_missing_count = 0;

	// sectional totalisers
	public $pfr_missing_totaliser = 0;
	public $discrepancy_totaliser = 0;
	public $missing_totaliser = 0;

	// number of lines found in sunesis & pfr
	public $number_matched_lines = 0;
	public $present_discrepancy_count = 0;
	public $present_discrepancy_html = "";
	// number of lines found in PFR but not in Sunesis with associated
	public $number_missing_value_lines = 0;
	public $missing_value_lines_html = "";
	// number of lines found in Sunesis but not found in PFR with associated
	public $number_pfr_missing_lines = 0;
	public $missing_pfr_lines_html = "";

	// total cash count
	public $pfr_cash_count = 0;
	public $sunesis_cash_count = 0;
	public $sunesis_cash_percentage = 100;
	public $sunesis_accuracy = "{ type: 'bar', name: 'Financial Discrepancy', data: ['0']}";


	public $import_id = null;
}