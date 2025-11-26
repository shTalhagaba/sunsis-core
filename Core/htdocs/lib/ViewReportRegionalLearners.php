<?php
class ViewReportRegionalLearners extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;
		if(!isset($_SESSION[$key]))
		{
			// Create new view object

			$sql = <<<HEREDOC
SELECT 
	COUNT(users.username) as usercount, 
	organisations.retailer_code,
	organisations.district 
FROM 
	organisations, users, tr
WHERE 
	organisations.organisation_type = 2 
AND 
	organisations.district IS NOT NULL
AND
	organisations.id = users.employer_id
AND 
	users.type = 5
AND 
	tr.username = users.username
AND
	tr.status_code = 1
GROUP BY 
	organisations.retailer_code,
	organisations.district;
HEREDOC;
			$view = $_SESSION[$key] = new ViewReportRegionalLearners();
			$view->setSQL($sql);
		}
		return $_SESSION[$key];
	}
	
	public function build_page(PDO $link)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());

		if( $st ) {
			while( $row = $st->fetch() ) {
				if ( !array_key_exists($row['retailer_code'], $this->region_tabs) ) {
					$this->region_tabs[$row['retailer_code']] = array();
				}
				
				$this->region_tabs[$row['retailer_code']][$row['district']] = $row['usercount'];
			}
		}
		else {
			throw new DatabaseException($link, $this->getSQL());
		}	
		
	}
	
	public function render_headings() {
		$tabcount = 1;
		foreach ( $this->region_tabs as $retailer => $district ) {
			echo '<li><a href="#tab-'.$tabcount.'">Region: '.$retailer.'</a></li>';
			$tabcount++;
		}
	}
		
	public function render() {
		$tabcount = 1;
		foreach ( $this->region_tabs as $retailer => $district ) {
			echo '<div id="tab-'.$tabcount.'">';
			echo '<div id="'.$tabcount.'-container" style="width: 80%; height: 400px; margin: 0 auto"></div>';		
			echo '</div>';
			$tabcount++;
		}		
	}
	
	public function render_js() {
		$tabcount = 1;
		foreach ( $this->region_tabs as $retailer => $district ) {
			// re: does this keep the correct sorting?
			$categories = implode(",", array_keys($district));
			$rep_data = implode(",", array_values($district));
			$data = <<<HEREDOC
			chart = new Highcharts.Chart({
				chart: {
					renderTo: '${tabcount}-container'
				},
				title: {
					text: 'Region ${retailer}: Number of Learners per Area'
				},
				xAxis: {
					title:{text:'Area code'},
					categories: [${categories}]
				},
				yAxis: { 
					allowDecimals: false,
					title:{text:'No. of Learners'}
				},
				tooltip: {
					formatter: function() {
						var s = 'Area '+	this.x  +': '+ this.y + ' Learners';
						return s;
					}
				},
				labels: { },
				plotOptions: {
					bar: {
						dataLabels: {
							enabled: true
						}
					}
				},
				legend: {
         			enabled: false
      			},
      			credits: {
        			enabled: false
    			},
				series: [{ 
					type: 'bar',
					color: '#9EB574',
					name: 'Number Of Learners',
					data: [${rep_data}]
				}]
			});
HEREDOC;
			echo $data;
			$tabcount++;
		}
	}
		
	public $region_tabs = array(); 
}
?>