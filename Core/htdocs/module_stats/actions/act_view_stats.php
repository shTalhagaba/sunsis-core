<?php

class view_stats implements IAction {
	
	public function execute(PDO $link)	{	
		
		// allow only perspective logins, from perspective offices to view loadtime reports
		if ( !SOURCE_BLYTHE_VALLEY && !SOURCE_LOCAL ) {
			http_redirect($_SESSION['bc']->getCurrent());
		}
		
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_stats", "Todays Load Times");	
		
		$no_access_log = 0;
		
		// can we read the log files ?? 
		if ( SOURCE_LOCAL ) { 
			if ( file_exists('../logs/access.log') ) {
				$handle = fopen('../logs/access.log', 'r');	
			}
			else {
				$no_access_log = 1;
			}
		}
		else {
			if ( file_exists('/srv/www/'.DB_NAME.'/logs/access_log') ) {
				$handle = fopen('/srv/www/'.DB_NAME.'/logs/access_log', 'r');
			}
			elseif(file_exists('/srv/www/'.DB_NAME.'/logs/access.log')	) {
				$handle = fopen('/srv/www/'.DB_NAME.'/logs/access.log', 'r');
			}
			else {
				$no_access_log = 1;
			}
		}			
		
		if ( $no_access_log ) {
			http_redirect($_SESSION['bc']->getCurrent());
		}
		
		// The log format of the above data
    	$format = "/^(\S+) (\S+) \"(\S+)\" \[(\d+)\/(\S+)\/(\d+):(\d+:\d+:\d+) ([^\]]+)\] \"(\S+) (.*?) (\S+)\" (\S+) (\S+) \"(\S+)\" \"(.+)\" (\S+)$/";
    	    	
		$contents = '';
		while (!feof($handle)) {
  			$contents = fgets($handle);
  			$info = array();
  			preg_match($format, $contents, $info);
  			if ( sizeof($info) > 0 ) {
  				// ignore internal IP addresses
  				if( $info[1] != '80.195.116.88' || DB_NAME == 'am_demo' ) {
  					preg_match("/_action=(\S+)/", $info[10], $call_is);	
  					if (sizeof($call_is) > 0) {	  					
  						$call_variables = preg_split("/\&/", $call_is[1]);
  						if ( $call_variables[0] != 'view_stats' ) {
  							if ( array_key_exists($call_variables[0], $this->record_stats) ) {
  								$this->record_stats[$call_variables[0]]['total'] += $info[16];
  								$this->record_stats[$call_variables[0]]['count']++;
  								$this->record_stats[$call_variables[0]]['average'] = sprintf("%.0f", $this->record_stats[$call_variables[0]]['total']/$this->record_stats[$call_variables[0]]['count']);
  							}
  							else {
  								$this->record_stats[$call_variables[0]] = array('count' => 1, 'total' => $info[16], 'average' => $info[16]);
  							}
  						}
  					}
  				}
  			}
		}
		fclose($handle);		
		
		$this->store_live_stats($link);
		
		// get the historicals
		$sql_request_retrieval = 'SELECT request, SUM(totaltime) AS total, SUM(requestcount) AS requests, (SUM(totaltime)/SUM(requestcount)/1000000) AS average, COUNT(daterecorded) AS timescale FROM module_stats_loadtimes GROUP BY request ORDER BY request';

		if( $result = $link->query($sql_request_retrieval) ) {
			while( $row = $result->fetch() ) {
				if ( array_key_exists($row['request'], $this->historical_stats) ) {
					$this->historical_stats[$row['request']]['total'] += $row['total'];
					$this->historical_stats[$row['request']]['count'] += $row['requests'];
					$this->historical_stats[$row['request']]['average'] = sprintf("%.0f", $this->historical_stats[$row['request']]['total']/$this->historical_stats[$row['request']]['count']);
					$this->historical_stats[$row['request']]['timescale'] += $row['timescale'];
				}
				else {
					$this->historical_stats[$row['request']] = array('count' => $row['requests'], 'total' => $row['total'], 'average' => $row['average'], 'timescale' => $row['timescale']);	
				}	
			}
		}
		
		require_once('tpl_view_stats.php');
	}
	
	private function store_live_stats(PDO $link) {
		
		// Create the requested table if it does not exist
		$table_exists = DAO::getSingleValue($link, "SELECT table_name FROM information_schema.tables WHERE table_schema = '".DB_NAME."' AND table_name='module_stats_loadtimes'");
		if( !$table_exists ) {
			$st = $link->query($this->sql_tables['module_stats_loadtimes']);
		}
		
		// quick and dirty method for ensuring none duplication of results
		// remove previous stats for today
		$delete_sql = 'DELETE FROM module_stats_loadtimes WHERE daterecorded = CURDATE()';
		$st = $link->query($delete_sql);

		if ( sizeof($this->record_stats) > 0 ) {
			foreach($this->record_stats as $function => $values ) {
				$insert_sql = 'insert into module_stats_loadtimes (request, totaltime, requestcount, averageload, lastrequest, daterecorded) values ("'.$function.'", '.$values['total'].', '.$values['count'].', '.$values['average'].', now(), curdate() )';
				$st = $link->query($insert_sql);
			}	
		}
	}
	
	public function display_live_stats() {
		
	    if ( sizeof($this->record_stats) <= 0 ) {
        	return '';
        }
		
		$html_output = '';		
		
		$total_time = 0;
		$total_average_time = 0;
		$total_requests = 0;
		$total_exceeds = 0;
		ksort($this->record_stats);	
		foreach($this->record_stats as $function => $values ) {
			$row_style = '';
			$average_in_seconds = sprintf("%4.4f", ($values['average']/1000000));
			if ( $average_in_seconds >= 1 ) {
				$row_style = 'background-color: #FFBFBF; border-bottom: 1px solid #fff;';
				$total_exceeds++;
			}
			$html_output .= '<tr style="'.$row_style.'" ><td><a href="do.php?_action=view_stats&amp;request='.$function.'">'.$function.'</a></td><td>'.$average_in_seconds.'</td><td>'.$values['count'].'</td></tr>';	
			$total_time += $values['total'];
			$total_average_time += $values['average'];
			$total_requests += $values['count'];				
		}	
		
		$html_output = '<tfoot><tr><td><strong>'.$total_exceeds.'</strong> (Requests Exceeding Avg. 1 Sec Load)</td><td><strong>'.sprintf("%4.4f", ($total_time/$total_requests)/1000000).'</strong> (Avg Response Time)</td><td><strong>'.$total_requests.'</strong> (Total Requests)</td></tr></tfoot><tbody>'.$html_output.'</tbody>';
		return $html_output;	
	}
	
	
	public function display_historical_stats() {
	
		$html_output = '';
		$total_time = 0;
		$total_average_time = 0;
		$total_requests = 0;
		$requests_per_day = 0;
		$no_of_days = 0;
		$total_exceeds = 0;
		ksort($this->historical_stats);	
		foreach($this->historical_stats as $function => $values ) {
			$row_style = '';
			if ( $values['average'] >= 1 ) {
				$row_style = 'background-color: #FFBFBF; border-bottom: 1px solid #fff;';
				$total_exceeds++;
			}
			$html_output .= '<tr style="'.$row_style.'" ><td><a href="do.php?_action=view_stats&amp;request='.$function.'">'.$function.'</a></td><td>'.sprintf("%4.4f", $values['average']).'</td><td>'.$values['count'].'</td><td>'.$values['timescale'].'</td></tr>';	
			$total_time += $values['total'];
			$total_average_time += $values['average'];
			$total_requests += $values['count'];	

			if ($values['timescale'] > $no_of_days ) {
				$no_of_days = $values['timescale'];
			}
		}	
		
		if ( $no_of_days <= 0 ) {
			$no_of_days = 1;
		}
		
		$requests_per_day = $total_requests/$no_of_days;
		
		if ( $total_requests <= 0 ) {
			$total_requests = 1;
		}
		
		$html_output = '<tfoot><tr><td><strong>'.$total_exceeds.'</strong> (Requests Exceeding Avg. 1 Sec Load)</td><td><strong>'.sprintf("%4.4f", ($total_time/$total_requests)/1000000).'</strong> (Avg Response Time)</td><td><strong>'.$total_requests.'</strong> (Total Requests)</td><td><strong>'.$requests_per_day.'</strong> (Avg Daily Requests)</td></tr></tfoot><tbody>'.$html_output.'</tbody>';
		return $html_output;	
	}
	
 	public function display_request_stats(PDO $link, $request = 'All ' ) {
        // get the historicals

        $sql_request_retrieval = 'SELECT daterecorded, totaltime, requestcount, ((totaltime/requestcount)/1000000) AS average FROM module_stats_loadtimes GROUP BY daterecorded ORDER BY daterecorded';

        if (isset($_REQUEST['request'])) {
            $request = $_REQUEST['request'];
        	$sql_request_retrieval = 'SELECT daterecorded, totaltime, requestcount, ((totaltime/requestcount)/1000000) AS average FROM module_stats_loadtimes WHERE request = "'.$request.'" GROUP BY daterecorded ORDER BY daterecorded';
    	}
		
		$data = '';

		$data_categories = array();

		$date_records = '';
		$average_records = '';
		$daily_records = '';
		
		if( $result = $link->query($sql_request_retrieval) ) {
			$count = 1;
			while( $row = $result->fetch() ) {
				$data_categories[$row['daterecorded']] = array('total' => $row['totaltime'], 'requestcount' => $row['requestcount'], 'average' => $row['average']);
				$date_records .= "'Day ".$count."',";
				$average_records .= sprintf("%.4f", $row['average']).",";
				$count++;
			}
		}
		
		$data = <<<HEREDOC
		chart = new Highcharts.Chart({
				chart: {
					renderTo: 'container'
				},
				title: {
					text: '${request} Request Stats'
				},
				xAxis: {
					categories: [${date_records}]
				},
				yAxis: { 
					title:{text:'Avg. Load Time (seconds)'}
				},
				tooltip: {
					formatter: function() {
						var s = ''+	this.x  +': '+ this.y;
						return s;
					}
				},
				labels: {
				},
				credits: {
        			enabled: false
    			},
				series: [{
					type: 'spline',
					name: 'Average',
					data: [${average_records}]
				}]
			});
HEREDOC;

		return $data;
		
	}
	
 	public function display_request_counts(PDO $link, $request = 'All ' ) {
        // get the historicals

        $sql_request_retrieval = 'SELECT daterecorded, sum(requestcount) as requestcnt, SUM(requestcount)/((UNIX_TIMESTAMP(lastrequest)/3600) - (UNIX_TIMESTAMP(CONCAT(daterecorded, " 08:00:00"))/3600)) AS requests_per_hour FROM module_stats_loadtimes GROUP BY daterecorded ORDER BY daterecorded';

   		if (isset($_REQUEST['request'])) {
            $request = $_REQUEST['request'];
        	$sql_request_retrieval = 'SELECT daterecorded, sum(requestcount) as requestcnt, SUM(requestcount)/((UNIX_TIMESTAMP(lastrequest)/3600) - (UNIX_TIMESTAMP(CONCAT(daterecorded, " 08:00:00"))/3600)) AS requests_per_hour FROM module_stats_loadtimes WHERE request = "'.$request.'" GROUP BY daterecorded ORDER BY daterecorded';
    	}
		
		$data_categories = array();
		$daily_records = array();

		$date_records = '';
		$request_records = '';
		$hourly_records = '';
		$estimated_records = '';
		
		if( $result = $link->query($sql_request_retrieval) ) {
			$count = 1;
			while( $row = $result->fetch() ) {
				$data_categories[$row['daterecorded']] = array('requestcount' => $row['requestcnt']);
				$date_records .= "'Day ".$count."',";
				$request_records .= $row['requestcnt'].",";
				$hourly_records .= sprintf("%.0f", $row['requests_per_hour']).",";
				$estimated_records .= sprintf("%.0f", ($row['requests_per_hour']*12)).",";
				$count++;
			}
		}
		
		$data = <<<HEREDOC
		chart = new Highcharts.Chart({
				chart: {
					renderTo: 'hist-container'
				},
				title: {
					text: '${request}: Number of Requests'
				},
				xAxis: {
					categories: [${date_records}]
				},
				yAxis: { 
					title:{text:'Number of Requests'}
				},
				tooltip: {
					formatter: function() {
						var s = ''+	this.x  +': '+ this.y;
						return s;
					}
				},
				plotOptions: {
         			spline: {
            			dataLabels: {
               				enabled: true
            			},
            			enableMouseTracking: true
         			}
      			},
      			credits: {
        			enabled: false
    			},
				series: [{
					type: 'spline',
					name: 'Number Of Requests',
					data: [${request_records}],
				},				
				{
					type: 'spline',
					name: 'Average Hourly Requests',
					data: [${hourly_records}]
				},
				{
					type: 'spline',
					name: 'Estimated 12hr Day Requests',
					data: [${estimated_records}]
				}]
			});
HEREDOC;

		return $data;
		
	}
	
	public function show_action_today() {

		// the requested action to view
		$action_to_view = isset($_REQUEST['show_action'])?$_REQUEST['show_action']:'';
		
		$no_access_log = 0;
		
		// can we read the log files ?? 
		if ( SOURCE_LOCAL ) { 
			if ( file_exists('../logs/access.log') ) {
				$handle = fopen('../logs/access.log', 'r');	
			}
			else {
				$no_access_log = 1;
			}
		}
		else {
			if ( file_exists('/srv/www/'.DB_NAME.'/logs/access_log') ) {
				$handle = fopen('/srv/www/'.DB_NAME.'/logs/access_log', 'r');
			}
			elseif(file_exists('/srv/www/'.DB_NAME.'/logs/access.log')	) {
				$handle = fopen('/srv/www/'.DB_NAME.'/logs/access.log', 'r');
			}
			else {
				$no_access_log = 1;
			}
		}			

		// The log format of the above data
    	$format = "/^(\S+) (\S+) \"(\S+)\" \[(\d+)\/(\S+)\/(\d+):(\d+:\d+:\d+) ([^\]]+)\] \"(\S+) (.*?) (\S+)\" (\S+) (\S+) \"(\S+)\" \"(.+)\" (\S+)$/";
    	    	
		$contents = '';
		while (!feof($handle)) {
  			$contents = fgets($handle);
  			$info = array();
  			preg_match($format, $contents, $info);
  			if ( sizeof($info) > 0 ) {
  				// ignore internal IP addresses
  				if( $info[1] != '80.195.116.88' || DB_NAME == 'am_demo' ) {
  					preg_match("/_action=(\S+)/", $info[10], $call_is);	
  					if (sizeof($call_is) > 0) {	  					
  						$call_variables = preg_split("/\&/", $call_is[1]);
  						if ( $call_variables[0] == $action_to_view ) {
  							if ( array_key_exists($call_variables[0], $this->record_stats) ) {
  								$this->record_stats[$call_variables[0]]['total'] += $info[16];
  								$this->record_stats[$call_variables[0]]['count']++;
  								$this->record_stats[$call_variables[0]]['average'] = sprintf("%.0f", $this->record_stats[$call_variables[0]]['total']/$this->record_stats[$call_variables[0]]['count']);
  								$this->record_stats[$call_variables[0]]['loadtime'] .= sprintf("%.4f", $info[16]/1000000).",";
  							}
  							else {
  								$this->record_stats[$call_variables[0]] = array('count' => 1, 'total' => $info[16], 'average' => $info[16], 'loadtime' => sprintf("%.4f", $info[16]/1000000)."," );
  							}
  						}
  					}
  				}
  			}
		}
		fclose($handle);			
	}
	
	
	public $record_stats = array();
	
	public $historical_stats = array();
	
	private $sql_tables = array(
		'module_stats_loadtimes' => "CREATE TABLE `module_stats_loadtimes` ( `id` int(6) unsigned NOT NULL AUTO_INCREMENT, `request` varchar(200) COLLATE utf8_bin NOT NULL, `totaltime` binary(64) NOT NULL DEFAULT '0', `requestcount` int(10) NOT NULL DEFAULT '0', `averageload` float(14,4) NOT NULL DEFAULT '0.0000', `lastrequest` datetime DEFAULT NULL, `daterecorded` date DEFAULT NULL, PRIMARY KEY (`id`), UNIQUE KEY `load_by_date` (`request`,`daterecorded`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;"
	);
}
?>
