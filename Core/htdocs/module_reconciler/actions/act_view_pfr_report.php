<?php
/**
 * User: Richard Elmes
 * Date: 25/05/12
 * Time: 10:53
 */

class view_pfr_report implements IAction
{
	/**
	 * @param PDO $link
	 */
	public function execute(PDO $link) {

		$_SESSION['bc']->add($link, "do.php?_action=view_pfr_report", "PFR Report");

		if ( isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) ) {
			$report_sql = 'select * from rpt_reconciler_snapshot where import_id = '.$_REQUEST['id'];
			$this->report_data = DAO::getResultset($link, $report_sql, DAO::FETCH_ASSOC);
			$this->import_id = $_REQUEST['id'];
		}

		if ( sizeof($this->report_data) <= 0 ) {
			// we haven't got a valid pfr report??

		}

		// ---
		// id = 8
		// active = tab1
		// if ( !isset($_REQUEST['active']) ) {
		//	$_REQUEST['active'] = 'tab1';
		// }
		// ---

		// do some 'fixing' on the view filters to enable cross tab filtering
		// ViewReconcilerDiscrepancies_filter_contracts
		if ( isset($_REQUEST['ViewReconcilerDiscrepancies_filter_contracts']) ) {
			$_REQUEST['ViewReconcilerMissing_filter_contracts'] = $_REQUEST['ViewReconcilerDiscrepancies_filter_contracts'];
		}

		// if ( isset($_REQUEST['ViewReconcilerDiscrepancies___page_size']) ) {
			// $_REQUEST['ViewReconcilerMissing___page_size'] = $_REQUEST['ViewReconcilerDiscrepancies___page_size'];
		// }

		$this->reconciler_discrepant = ViewReconcilerDiscrepancies::getInstance($this->import_id);
		$this->reconciler_discrepant->refresh($link, $_REQUEST);

		$this->sunesis_missing = ViewReconcilerMissing::getInstance($this->import_id, 'pfr');
		$this->sunesis_missing->refresh($link, $_REQUEST);

		$this->pfr_missing = ViewReconcilerMissing::getInstance($this->import_id, 'sunesis');
		$this->pfr_missing->refresh($link, $_REQUEST);

		include('tpl_view_pfr_report.php');
	}

	public function present_pfr_summary( PDO $link ) {

		$filtered_report = 0;

		$pfr_sql = "select sum(grand_total) from rpt_reconciler_pfr where import_id = ".$this->import_id;
		$sunesis_sql = "select sum(grand_total) from rpt_reconciler_sunesis where import_id = ".$this->import_id;

		if ( is_numeric($this->reconciler_discrepant->getFilterValue('filter_contracts')) ) {
				$filtered_report = $this->reconciler_discrepant->getFilterValue('filter_contracts');
				$pfr_sql .= " and contract_id = ".$filtered_report;
				$sunesis_sql .= " and contract_id = ".$filtered_report;

				// get the contract details to display
				$this->reconciler_header_info = DAO::getSingleValue($link, "select title from contracts where id = ".$filtered_report);
		}
		else {
			$this->sunesis_missing->resetFilters();
			$this->pfr_missing->resetFilters();
			$this->reconciler_discrepant->resetFilters();
			// get the total number of contracts in the PFR report.
			$this->reconciler_header_info .= "&nbsp;(".DAO::getSingleValue($link, "select count(distinct(contract_id)) from rpt_reconciler_sunesis where import_id =".$this->import_id)." contracts)";
		}

		// add the period and
		$pfr_grand_total = DAO::getSingleValue($link, $pfr_sql);
		$sun_grand_total = DAO::getSingleValue($link, $sunesis_sql);

		// show filtered summary data
		if ( is_numeric($filtered_report) && $filtered_report !== 0 ) {
			$this->report_data[0]['pfr_count'] = DAO::getSingleValue($link, "select count(*) from rpt_reconciler_pfr where contract_id = ".$filtered_report." and import_id = ".$this->import_id);
			$this->report_data[0]['number_of_learners'] = DAO::getSingleValue($link, "select count(*) from rpt_reconciler_pfr where contract_id = ".$filtered_report." and grand_total > 0 and import_id = ".$this->import_id);
			$this->report_data[0]['number_sunesis_learners'] = DAO::getSingleValue($link, "select count(*) from rpt_reconciler_sunesis where contract_id = ".$filtered_report." and import_id = ".$this->import_id);
			$this->report_data[0]['present_discrepancy_count'] = $this->reconciler_discrepant->getRowCount();
			$this->report_data[0]['number_missing_value_lines'] = $this->sunesis_missing->getRowCount();

			$this->report_data[0]['sunesis_discrep_count'] = $this->report_data[0]['present_discrepancy_count'];
			$this->report_data[0]['number_pfr_missing_lines'] = $this->pfr_missing->getRowCount();

			echo '<ul class="yui-nav">';
			echo '<li><a href="#tab1" class="selected" ><em>Summary</em></a></li>';
			echo '<li><a href="#tab2"><em>Discrepancies ('.$this->reconciler_discrepant->getRowCount().' Learning Aims)</em></a></li>';
			echo '<li><a href="#tab3"><em>No funding in Sunesis ('.$this->sunesis_missing->getRowCount().' Learning Aims)</em></a></li>';
			echo '<li><a href="#tab4"><em>No funding on the PFR ('.$this->pfr_missing->getRowCount().' Learning Aims)</em></a></li>';
			echo '</ul>';
		}
		else {
			echo '<ul class="yui-nav">';
			echo '<li><a href="#tab1" class="selected" ><em>Summary</em></a></li>';
			echo '<li><a href="#tab2"><em>Discrepancies ('.$this->report_data[0]['present_discrepancy_count'].' Learning Aims)</em></a></li>';
			echo '<li><a href="#tab3"><em>No funding in Sunesis ('.$this->report_data[0]['number_missing_value_lines'].' Learning Aims)</em></a></li>';
			echo '<li><a href="#tab4"><em>No funding on the PFR ('.$this->report_data[0]['number_pfr_missing_lines'].' Learning Aims)</em></a></li>';
			echo '</ul>';
		}

		echo '<div class="yui-content" style="background-color: #fff;">';
		echo '	<div id="tab1">';
		echo '		<div id="graphs" style="float:left;">';
		echo '			<div id="cash_count">&nbsp;</div>';
		echo '			<div id="line_count">&nbsp;</div>';
		echo ' 		</div>';
		echo '		<div id="summary" style="width: 500px; float: left;">';
		echo '		<table>';
		echo '			<tr><td colspan="2" style="font-size: 1.4em; color: #666;" >'.$this->reconciler_header_info.'</td></tr>';
		echo '			<tr><td colspan="2" style="font-weight: bold; font-style: italic;" >Learning Aim Summary</td></tr>';

		echo '			<tr><td>Total Learning Aims in the PFR file: </td><td style="text-align:right;">'.$this->report_data[0]['pfr_count'].'</td></tr>';
		echo '			<tr><td>&pound; Value Learning Aims in the PFR file: </td><td style="text-align:right;">'.$this->report_data[0]['number_of_learners'].'</td></tr>';
		echo '			<tr><td>&pound; Value Learning Aims in Sunesis: </td><td style="text-align:right;">'.$this->report_data[0]['number_sunesis_learners'].'</td></tr>';
		echo '			<tr><td>Learning Aims with no discrepancies</td><td style="text-align:right;">'.($this->report_data[0]['pfr_count']-($this->report_data[0]['present_discrepancy_count']+$this->report_data[0]['number_missing_value_lines'])).'</td></tr>';
		echo '			<tr><td>Learning Aims with discrepancies</td><td style="text-align:right;">'.$this->report_data[0]['present_discrepancy_count'].'</td></tr>';
		echo '			<tr><td>Learning Aims with no funding in Sunesis</td><td style="text-align:right;">'.$this->report_data[0]['number_missing_value_lines'].'</td></tr>';

		echo '			<tr><td>Learning Aims with no funding on the PFR</td><td style="text-align:right;">'.$this->report_data[0]['number_pfr_missing_lines'].'</td></tr>';

		echo '			<tr><td colspan="2" style="font-weight: bold; font-style: italic;" >Financial Summary</td></tr>';
		echo '			<tr><td>Value in the PFR: </td><td style="text-align:right;" >'.$this->_formatCash(round($pfr_grand_total,2)).'</td></tr>';
		echo '			<tr><td>Value in Sunesis: </td><td style="text-align:right;" >'.$this->_formatCash($sun_grand_total).'</td></tr>';

		$discrepancy =  $sun_grand_total-$pfr_grand_total;
		$discrepancy_style = '';
		$graph_style = '';
		if ( $discrepancy < 0 ) {
			$discrepancy_style = 'font-style:italic; color: '.$this->color_scheme['good'].'; font-weight: bold;';
			$graph_style = "color: '".$this->color_scheme['good']."'";
		}
		else {
			$discrepancy_style = 'font-style:italic; color: '.$this->color_scheme['bad'].'; font-weight: bold;';
			$graph_style = "color: '".$this->color_scheme['bad']."'";
		}
		echo '			<tr><td style="'.$discrepancy_style.'" >Financial Difference: </td>';
		echo '			<td style="'.$discrepancy_style.'text-align:right;" >'.$this->_formatCash(round($discrepancy,2)).'</td></tr>';

		$this->report_data[0]['sunesis_accuracy'] = "{ type: 'bar', name: 'Financial Discrepancy', data: [".round($discrepancy,2)."], ".$graph_style." }";
		echo '		</table>';
		echo ' 		</div>';
		echo '<div class="clearfix">&nbsp;</div>';
		echo '</div>';
		// ---
		if ( $this->report_data[0]['present_discrepancy_count'] > 0 ) {
			// ----
			// OUTPUT THE RECORDS MARKED AS DISCREPANT HERE.......[rpt_reconciler_sunesis = 1, rpt_reconciler_pfr = 1].....
			// ----
			echo "<div id='tab2'>";
			echo '<p>Learning Aims with differences in the PFR file and the data we have in Sunesis</p>';
			$this->reconciler_discrepant->render($link);
			echo "</div>";
		}
		else {
			echo "<div id='tab2'>";
			echo '<p>There are no Learning Aims with differences in the PFR file and the data we have in Sunesis</p>';
			echo "</div>";
		}

		// if ( $this->report_data[0]['number_missing_value_lines'] > 0 ) {
			echo "<div id='tab3'>";
			echo '<p>Learning Aims with no funding in Sunesis (with funding on PFR)</p>';
			// ----
			// OUTPUT THE RECORDS MISSING FROM SUNESIS HERE [rpt_reconciler_pfr status = 2] .......
			// ----
			$this->sunesis_missing->render($link);

			echo "</div>";

		// ---
		if ( $this->report_data[0]['number_pfr_missing_lines'] > 0 ) {
			echo "<div id='tab4'>";
			echo '<p>Learning Aims with no funding in PFR that have funding on Sunesis</p>';
			// OUTPUT THE RECORDS MISSING FROM PFR HERE [ rpt_reconciler_sunesis status = 2]......
			// ----
			$this->pfr_missing->render($link);
			echo "</div>";
		}
		else {
			echo '<p>There are no Learning Aims with without funding in PFR that have funding on Sunesis</p>';
		}
		echo '</div>';

		return;
	}

	public function present_pfr_tabs($link)
	{

		$this->reconciler_discrepant->refresh($link, $_REQUEST);
		$this->sunesis_missing->refresh($link, $_REQUEST);
		$this->pfr_missing->refresh($link, $_REQUEST);



		return;
	}

	public function display_pfrcount_graph($link)
	{
		// split this into separate functions.
		// ---
		$pfr_sql = "select sum(grand_total) from rpt_reconciler_pfr where import_id = ".$this->import_id;
		$sunesis_sql = "select sum(grand_total) from rpt_reconciler_sunesis where import_id = ".$this->import_id;

		if ( is_numeric($this->reconciler_discrepant->getFilterValue('filter_contracts')) ) {
			$filtered_report = $this->reconciler_discrepant->getFilterValue('filter_contracts');
			$pfr_sql .= " and contract_id = ".$filtered_report;
			$sunesis_sql .= " and contract_id = ".$filtered_report;
		}

		$pfr_grand_total = DAO::getSingleValue($link, $pfr_sql);
		$sun_grand_total = DAO::getSingleValue($link, $sunesis_sql);

		$discrepancy =  $sun_grand_total-$pfr_grand_total;
		$discrepancy_style = '';
		$graph_style = '';
		if ( $discrepancy < 0 ) {
			$discrepancy_style = 'font-style:italic; color: '.$this->color_scheme['good'].'; font-weight: bold;';
			$graph_style = "color: '".$this->color_scheme['good']."'";
		}
		else {
			$discrepancy_style = 'font-style:italic; color: '.$this->color_scheme['bad'].'; font-weight: bold;';
			$graph_style = "color: '".$this->color_scheme['bad']."'";
		}

		$this->report_data[0]['sunesis_accuracy'] = "{ type: 'bar', name: 'Financial Discrepancy', data: [".round($discrepancy,2)."], ".$graph_style." }";

		$match_count = ($this->report_data[0]['pfr_count']-($this->report_data[0]['present_discrepancy_count']+$this->report_data[0]['number_missing_value_lines']));

		$data = '';
		$max_axis = $this->report_data[0]['pfr_missing_count']+$this->report_data[0]['sunesis_discrep_count']+$this->report_data[0]['sunesis_count'];
		$data = <<<HEREDOC
		chart = new Highcharts.Chart({
				chart: {
					renderTo: 'line_count',
					defaultSeriesType: 'bar',
					width: 300,
					height: 200
				},
				credits: {
        			enabled: false
    			},
				title: {
					text: 'PFR/Sunesis issues',
        			align: 'center'
				},
				xAxis: {
					categories: ['Learning Aims to reconcile'],
					labels:{ enabled:null }
				},
				yAxis: {
					title:null,
        			endOnTick: false
				},
				tooltip: {
					formatter: function() {
						var s = ''+	this.series.name  +': '+ this.y;
						return s;
					}
				},
				plotOptions: {
         			series: {
            			stacking: false
         			}
      			},

				labels: {

				},
				legend: {
					enabled: false
				},
				groupPadding: 1,
				series: [
					{name: 'Discrepancies ({$this->report_data[0]['present_discrepancy_count']})', data: [{$this->report_data[0]['present_discrepancy_count']}], color: '{$this->color_scheme['good']}'},
					{name: 'Matches ({$match_count})', data: [{$match_count}], color: '{$this->color_scheme['bad']}'},
					{name: 'No funding in Sunesis ({$this->report_data[0]['number_missing_value_lines']} Learning Aims)', data: [{$this->report_data[0]['number_missing_value_lines']}], color: '{$this->color_scheme['ok']}'},
					{name: 'No funding on the PFR ({$this->report_data[0]['number_pfr_missing_lines']} Learning Aims)', data: [{$this->report_data[0]['number_pfr_missing_lines']}], color: '{$this->color_scheme['ok']}'}
				]
			});


			chart = new Highcharts.Chart({
				chart: {
					renderTo: 'cash_count',
					defaultSeriesType: 'bar',
					width: 300,
					height: 200,
					inverted: false
				},
				credits: {
        			enabled: false
    			},
				title: {
					text: 'Financial Discrepancy',
        			align: 'center'
				},
				xAxis: {
					categories: ['Financial Reconciler'],
					labels:{ enabled:false }
				},
				yAxis: {
					title:null
				},
				tooltip: {
					formatter: function() {
						var s = "\u00A3 "+this.y;
						return s;
					}
				},
				plotOptions: {
      			},
				labels: {
					enabled: false
				},
				legend: {
					enabled: false
				},
				series: [{$this->report_data[0]['sunesis_accuracy']}]
			});
HEREDOC;
		return $data;
	}

	private function _formatCash($value) {
		return '&pound;'.number_format(sprintf("%.2f", $value),2,".",",");
	}


	public $import_id = NULL;
	public $report_data = array();
	public $reconciler_discrepant = NULL;
	public $sunesis_missing = NULL;
	public $pfr_missing = NULL;
	public $reconciler_header_info = 'All Contracts';

	public $color_scheme = array(
		'good' 	=> '#AA4643',
		'ok'	=> '#4572A7',
		'bad'	=> '#89A54E'
	);
}