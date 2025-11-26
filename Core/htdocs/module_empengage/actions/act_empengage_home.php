<?php
define('METRES_IN_A_MILE', 1609.344);

class empengage_home implements IAction
{
	public function execute(PDO $link)
	{
		// resets the breadcrumb trail.
		$_SESSION['bc']->index=0;	
		$_SESSION['bc']->add($link, "do.php?_action=empengage_home", "Prospect Engagement Home");

		$diary_filter_region = isset($_REQUEST['diary_filter_region'])?$_REQUEST['diary_filter_region']:'';

		require_once('tpl_empengage_home.php');
	}
	
	
	public function display_screening_stats(PDO $link ) {
		
		$average_records = '';
		$categories = "";
		$organisation_status = array();

		// employers
		// ---
		$org_status_sql = "SELECT org_status, org_status_comment, organisations_status.org_type, COUNT(organisations_status.org_type) as stat_type FROM organisations_status, organisations where organisations.id = organisations_status.org_id and organisations_status.org_type = 1 ";
		if ( isset($_SESSION['user']->department) ) {
               $org_status_sql .= 'AND organisations.region = "'.$_SESSION['user']->department.'" ';
       	}
		$org_status_sql .= " GROUP BY organisations_status.org_type, org_status ORDER BY org_status, organisations_status.org_type";

		$st = $link->query($org_status_sql);
		if( $st ) {
			while ( $row = $st->fetch() ) {
				if ( !array_key_exists($row['org_status'], $organisation_status) ) {
					$organisation_status[$row['org_status']] = array('description' => $row['org_status_comment'], 'emp' => 0, 'pool' => 0);
				}
				$organisation_status[$row['org_status']]['emp']+=$row['stat_type'];
			}
		}

		// pool ones
		// ---
		$org_status_sql = "SELECT org_status, org_status_comment, organisations_status.org_type, COUNT(organisations_status.org_type) as stat_type FROM organisations_status where organisations_status.org_type = 2 ";
		$org_status_sql .= " GROUP BY organisations_status.org_type, org_status ORDER BY org_status, organisations_status.org_type";

		$st = $link->query($org_status_sql);
		if( $st ) {
			while ( $row = $st->fetch() ) {
				if ( !array_key_exists($row['org_status'], $organisation_status) ) {
					$organisation_status[$row['org_status']] = array('description' => $row['org_status_comment'], 'emp' => 0, 'pool' => 0);
				}
				$organisation_status[$row['org_status']]['pool']+=$row['stat_type'];
			}
		}

		$employer_records = "{name: 'Employers', data: [";
		$pool_records = "{name: 'Pool', data: [";

		foreach ( $organisation_status as $status_id => $status_data ) {
			$categories .= "'".$status_data['description']."',";
			$employer_records .= $status_data['emp'].",";
			$pool_records .= $status_data['pool'].",";
		}

		$employer_records .= "]},";
		$pool_records .= "]}";
			
		$average_records .= $employer_records.$pool_records;

		
		$data = <<<HEREDOC
		chart = new Highcharts.Chart({
				chart: {
					renderTo: 'stat-container',
					defaultSeriesType: 'column',
					height: 480,
					width: 320,
					inverted: false
				},
				title: {
					text: null
				},
				xAxis: {
					categories: [${categories}],
					labels: {
						style: {
							fontWeight: 'normal',
						},
						rotation: -75,
						align: 'right'
					}
				},
				yAxis: { 
					allowDecimals:false,
					title:{text:''}
				},
				tooltip: {
					formatter: function() {
						var s = ''+	this.series.name  +': '+ this.y;
						return s;
					}
				},
				credits: {
        			enabled: false
    			},
				plotOptions: {
					bar: {
						dataLabels: {
							enabled: true
						}
					}
				},
				labels: {
				},
				series: [${average_records}]
			});
HEREDOC;
		return $data;		
	}
	
	public $all_candidates = 0;
	public $new_candidates = 0;
	public $screened_candidates = 0;
	public $approved_candidates = 0;
	public $unassigned_candidates = 0;
}
?>
