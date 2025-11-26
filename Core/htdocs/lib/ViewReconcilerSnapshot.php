<?php
class ViewReconcilerSnapshot extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT
		rpt_reconciler_header.filename,
		rpt_reconciler_header.import_date,
		rpt_reconciler_header.pfr_year,
		rpt_reconciler_header.period,
		rpt_reconciler_snapshot.*
FROM
		rpt_reconciler_header,
		rpt_reconciler_snapshot
WHERE
		rpt_reconciler_header.import_id = rpt_reconciler_snapshot.import_id
ORDER BY
		rpt_reconciler_snapshot.timelog DESC;
HEREDOC;

			$view = $_SESSION[$key] = new ViewReconcilerSnapshot();
			$view->setSQL($sql);
		}
		return $_SESSION[$key];
	}

	public function render(PDO $link) {

		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		$previous_reports = '';
		if($st)	{
			
			$missing_totaliser = 0;

			echo '<div id="historical" class="">';
			$previous_reports .= '<h2>Previous Reconciler Reports</h2>';
			$previous_reports .= $this->getViewNavigator();

			$previous_reports .= "<table class='resultset' cellpadding='6' ><thead><tr>";
			$previous_reports .= "<th>Comparison Date</th>";
			$previous_reports .= "<th>Comparison Time</th>";
			$previous_reports .= "<th>Using PFR File</th>";
			$previous_reports .= "<th>Funding Period</th>";
			$previous_reports .= "<th style='text-align:right;'>Sunesis Value</th>";
			$previous_reports .= "<th style='text-align:right;'>PFR Value</th>";
			$previous_reports .= "<th style='text-align:right;'>Sunesis Accuracy</th>";
			$previous_reports .=  "</tr></thead>";
			$previous_reports .= "<tbody>";

			// remove any zero lined records
			$number_of_reports = 0;
			
			while( $row = $st->fetch() ) {
				$previous_reports .= HTML::viewrow_opening_tag('do.php?_action=view_pfr_report&id='.$row['import_id'].'&active=tab1');
				$previous_reports .= '<td>'.date("l, jS F Y", strtotime($row['timelog'])).'</td>'; // .$row['import_date'].'</td>';
				$previous_reports .= '<td>'.date("g:ia", strtotime($row['timelog'])).'</td>';
				$previous_reports .= '<td>'.$row['filename'].'<br/>'.date("l, jS F Y", strtotime($row['import_date'])).'</td>';
				$previous_reports .= '<td>'.$row['period'].' / '.$row['pfr_year'].'</td>';
				$previous_reports .= '<td style="text-align:right;">'.$this->_formatCash($row['sunesis_cash_count']).'</td>';
				$previous_reports .= '<td style="text-align:right;">'.$this->_formatCash($row['pfr_cash_count']).'</td>';

				$accuracy_percentage = '0.00';
				if ( $row['sunesis_cash_count'] > 0 ) {
					$accuracy_percentage = sprintf("%.2f", (100/$row['pfr_cash_count'])*$row['sunesis_cash_count'])-100;
					if ( $accuracy_percentage > 0 ) {
						$accuracy_percentage = 'Over Predicted by <br/><strong>'.$accuracy_percentage.'</strong>';
						$previous_reports .= '<td style="color: '.$this->color_scheme['good'].'" >'.$accuracy_percentage.'%</td>';
					}
					else {
						$accuracy_percentage = 'Under Predicted by <br/><strong>'.$accuracy_percentage.'</strong>';
						$previous_reports .= '<td style="color: '.$this->color_scheme['bad'].'" >'.$accuracy_percentage.'%</td>';
					}
				}
				else {
					$previous_reports .= '<td style="" >'.$accuracy_percentage.'%</td>';
				}

				$previous_reports .= "</tr>";
				$number_of_reports++;
			}

			$previous_reports .= "</tbody>";
			$previous_reports .= "</table>";

			$previous_reports .= $this->getViewNavigator();

			
			if ( $number_of_reports > 0 ) {
				echo $previous_reports;
			}
			else {
				echo "<h2>You haven't run a reconciler report before</h2>";
				echo "<p>Click on the 'Import New PFR' option above to begin</p>";
				echo "<p>Please bear in mind the process takes a little time, but once uploaded, you can compare the PFR file against Sunesis data any time you like</p>";
			}
			echo "</div>";
		}
	}

	public function render_graph(PDO $link) {

		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if( $st ) {

			$graph_categories = '';
			$graph_series['pfr_lines'] = '';
			$graph_series['sunesis_lines'] = '';
			$graph_series['discrepant_lines'] = '';
			$graph_series['matching_lines'] = '';

			$pie_mtch = '';
			$pie_miss = '';
			$pie_disc = '';
			$pie_count = 0;

			// remove any zero lined records
			while( $row = $st->fetch() ) {
				$comparable_lines = ($row['present_discrepancy_count']+$row['number_matched_lines']);
				$graph_categories .= "'".date("l, jS F Y", strtotime($row['import_date']))."',";
				$graph_series['pfr_lines'] .= "{name: ".$row['import_id'].",  y: ".($row['number_of_learners']-$comparable_lines).", expTitle: 'No funding in Sunesis (".($row['number_of_learners']-$comparable_lines)." Learning Aims)<br/>found in ".$row['filename']." on ".date("l, jS F Y", strtotime($row['import_date']))."' },";
				$graph_series['sunesis_lines'] .= "{name: ".$row['import_id'].",  y: ".($row['number_sunesis_learners']-$comparable_lines).", expTitle: 'No funding on the PFR (".($row['number_sunesis_learners']-$comparable_lines)." Learning Aims)<br/>found for ".$row['filename']." on ".date("l, jS F Y", strtotime($row['import_date']))."' },";

				$graph_series['discrepant_lines'] .= "{name: ".$row['import_id'].",  y: ".$row['present_discrepancy_count'].", expTitle: '".$row['present_discrepancy_count']." Discrepancies<br/>found in ".$row['filename']."<br/> on ".date("l, jS F Y", strtotime($row['import_date']))."' },";
				$graph_series['matching_lines'] .= "{name: ".$row['import_id'].",  y: ".$row['number_matched_lines'].", expTitle: '".$row['number_matched_lines']." Matched Learning Aims<br/>found in ".$row['filename']."<br/> on ".date("l, jS F Y", strtotime($row['import_date']))."' },";

				$pie_mtch += $row['number_matched_lines'];
				$pie_disc += $row['present_discrepancy_count'];
				$pie_count++;

				// only do the latest five reports in
				// graph form.
				if ( $pie_count == 5 ) {
					break;
				}
			}

			//$graph_series['pfr_lines'] = substr_replace( $graph_series['pfr_lines'], '', -1);
			//$graph_series['sunesis_lines'] = substr_replace($graph_series['sunesis_lines'], '', -1);
			//$graph_series['discrepant_lines'] = substr_replace($graph_series['discrepant_lines'], '', -1);
			//$graph_series['matching_lines'] = substr_replace($graph_series['matching_lines'], '', -1);
			//$graph_categories = substr_replace($graph_categories, '', -1);

			if ( $pie_count == 0 ) {
				echo '';
				return;
			}

			$graph_pie = "{\n";
			$graph_pie .= "	name: 'Matched',\n";
			$graph_pie .= "	y: ".($pie_mtch/$pie_count).",\n";
			$graph_pie .= "	color: '".$this->color_scheme['good']."'\n";
			$graph_pie .= "},\n";
			$graph_pie .= "{\n";
			$graph_pie .= "	name: 'Discrepant',\n";
			$graph_pie .= "	y: ".($pie_disc/$pie_count).",\n";
			$graph_pie .= "	color: '".$this->color_scheme['bad']."'\n";
			$graph_pie .= "},\n";

			$data_output = <<<HEREDOC
<script language="javascript" type="text/javascript">
var chart;
$(document).ready(function() {
	chart = new Highcharts.Chart({
		chart: {
			renderTo: 'reconciler-highlevel'
		},
		title: {
			text: 'Reconciler Reporting',
			align: 'right',
			backgroundColor: '#FFFFFF'
		},
		yAxis: {
			title: '',
			maxPadding: 0.4

		},
		xAxis: {
			categories: [$graph_categories],
			labels: {
                    formatter: function() {
                        return 'PFR File\u00A0\u00A0\u00A0\u00A0\u00A0\u00A0\u00A0\u00A0\u00A0\u00A0Sunesis<br/>' + this.value;

                    }
                }
		},
		tooltip: {
			enabled: false
		},
		legend: {
			enabled: false
		},
		credits: {
			enabled: false
		},
		/*labels: {
			items: [{
				html: 'Comparable Aim Match Accuracy',
				style: {
					left: '175px',
					top: '18px'
				}
			}
			]
		},*/
		plotOptions: {
			column: {
				stacking: 'normal'
			}
		},
		series: [
			// reconciler stacks
			{
				type: 'column',
				name: 'No funding in Sunesis',
				data: [{$graph_series['pfr_lines']}],
				stack: 'reconciler',
				color: '{$this->color_scheme['ok']}',
				point : {
					events: {
						click: function() { window.location.href='do.php?_action=view_pfr_report&id='+this.name+'&active=tab3' },
						mouseOver: function() {
							chart.setTitle({text: this.expTitle});
						}
					}
				},
				events: {
					mouseOut: function() {
							chart.setTitle({text: 'Reconciler Reporting'});
						}
				}
			},
			{
				type: 'column',
				name: 'Matched (PFR Learning Aims)',
				data: [{$graph_series['matching_lines']}],
				stack: 'reconciler',
				color: '{$this->color_scheme['good']}',
				point : {
					events: {
						click: function() { window.location.href='do.php?_action=view_pfr_report&id='+this.name+'&active=tab1' },
						mouseOver: function() {
							chart.setTitle({text: this.expTitle});
						}
					}
				},
				events: {
					mouseOut: function() {
							chart.setTitle({text: 'Reconciler Reporting'});
						}
				}
			},
			{
				type: 'column',
				name: 'Discrepancies (PFR Learning Aims)',
				data: [{$graph_series['discrepant_lines']}],
				stack: 'reconciler',
				color: '{$this->color_scheme['bad']}',
				point : {
					events: {
						click: function() { window.location.href='do.php?_action=view_pfr_report&id='+this.name+'&active=tab2' },
						mouseOver: function() {
							chart.setTitle({text: this.expTitle});
						}
					}
				},
				events: {
					mouseOut: function() {
							chart.setTitle({text: 'Reconciler Reporting'});
						}
				}
			},
			// sunesis stacks
			{
				type: 'column',
				name: 'No funding on the PFR',
				data: [{$graph_series['sunesis_lines']}],
				stack: 'sunesis',
				color: '{$this->color_scheme['ok']}',
				point : {
					events: {
						click: function() { window.location.href='do.php?_action=view_pfr_report&id='+this.name+'&active=tab4' },
						mouseOver: function() {
							chart.setTitle({text: this.expTitle});
						}}
				},
				events: {
					mouseOut: function() {
							chart.setTitle({text: 'Reconciler Reporting'});
						}
				}
			},
			{
				type: 'column',
				name: 'Matched (Sunesis Learning Aims)',
				data: [{$graph_series['matching_lines']}],
				stack: 'sunesis',
				color: '{$this->color_scheme['good']}',
				point : {
					events: {
						click: function() { window.location.href='do.php?_action=view_pfr_report&id='+this.name+'&active=tab1' },
						mouseOver: function() {
							chart.setTitle({text: this.expTitle});
						}
					}
				},
				events: {
					mouseOut: function() {
							chart.setTitle({text: 'Reconciler Reporting'});
						}
				}
			},
			{
				type: 'column',
				name: 'Discrepancies (Sunesis Learning Aims)',
				data: [{$graph_series['discrepant_lines']}],
				stack: 'sunesis',
				color: '{$this->color_scheme['bad']}',
				point : {
					events: {
						click: function() { window.location.href='do.php?_action=view_pfr_report&id='+this.name+'&active=tab2' },
						mouseOver: function() {
							chart.setTitle({text: this.expTitle});
						}
					}
				},
				events: {
					mouseOut: function() {
							chart.setTitle({text: 'Reconciler Reporting'});
						}
				}
			}
			,
			/*
			{
				type: 'pie',
				name: 'Financial Accuracy',
				data: [
					$graph_pie
				],
				center: [100, 20],
				size: 100,
				showInLegend: false,
				dataLabels: {
					enabled: false
				}
			}*/
			]
	});
});
</script>
HEREDOC;
			echo $data_output;
		}

	}

	private function _formatCash($value) {
		return '&pound;'.number_format(sprintf("%.2f", $value),2,".",",");
	}

	public $color_scheme = array(
		'good' 	=> '#89A54E',
		'ok'	=> '#4572A7',
		'bad'	=> '#AA4643'
	);
}