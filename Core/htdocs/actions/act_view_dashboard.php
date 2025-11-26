<?php
class view_dashboard implements IAction
{
	private $panel1_graph = NULL;
	private $panel2_graph = NULL;

    public function execute(PDO $link)
    {
	    $p = isset($_REQUEST['p'])?$_REQUEST['p']:'';
	    if($p == 'panel1')
	    {
		    $this->generatePanel1($link);
		    echo $this->panel1_graph;
		    exit;
	    }
	    if($p == 'panel2')
	    {
		    $this->generatePanel2($link);
		    echo $this->panel2_graph;
		    exit;
	    }
	    if($p == 'panel3')
	    {
		    echo $this->generateGraph3();
		    exit;
	    }
	    if($p == 'panel4')
	    {
		    echo $this->generateGraph4($link);
		    exit;
	    }
	    if($p == 'panel5')
	    {
		    echo $this->generateGraph5();
		    exit;
	    }
	    if($p == 'panel6')
	    {
		    echo $this->generateGraph6($link);
		    exit;
	    }
	    if($p == 'panel7')
	    {
		    echo $this->generateGraph7($link);
		    exit;
	    }
	    if($p == 'panel8')
	    {
		    echo $this->generateGraph8();
		    exit;
	    }
	    $statustotal = '';
	    $trainingrecords = '';
	    $ilrstatus = '';

	    $panelsToShow = DAO::getSingleColumn($link, "SELECT DISTINCT panel_name FROM dashboard_panels WHERE visible = 1");
	    $allPanels = DAO::getResultset($link, "SELECT panel_name, panel_heading FROM dashboard_panels GROUP BY panel_name;", DAO::FETCH_ASSOC);

	    foreach($panelsToShow AS $panel)
	    {
		    if($panel == 'panel1')
			    $this->generatePanel1($link);
		    elseif($panel == 'panel2')
			    $this->generatePanel2($link);
	    }

	    include_once('tpl_view_dashboard.php');
    }

	private function generatePanel2(PDO $link)
	{
		$ilrstatus = '';
		$trainingrecords = '';
		// Build ILR status Graph with HTML table
		$ilr_count=array('invalid' => 0, 'valid' => 0);
		//$sql="SELECT DISTINCT IF(tr.ilr_status=1,'valid','invalid') AS is_valid, COUNT(tr.ilr_status) AS training_record FROM tr LEFT JOIN ilr ON ilr.contract_id = tr.contract_id AND ilr.tr_id = tr.id AND ilr.submission = (SELECT MAX(submission) FROM ilr WHERE tr_id = tr.id AND contract_id = tr.contract_id) WHERE tr.`ilr_status` IS NOT NULL AND tr.status_code = 1 GROUP BY is_valid;";
		$sql="SELECT DISTINCT IF(tr.ilr_status=1,'valid','invalid') AS is_valid_ilr, COUNT(tr.ilr_status) AS training_record FROM tr LEFT JOIN ilr ON ilr.contract_id = tr.contract_id AND ilr.tr_id = tr.id AND ilr.submission = (SELECT MAX(submission) FROM ilr WHERE tr_id = tr.id AND contract_id = tr.contract_id) WHERE tr.`ilr_status` IS NOT NULL AND tr.status_code = 1 GROUP BY is_valid_ilr;";
		$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC, "homepage valid ilrs", 600);
		foreach($rows as $row)
		{
			$ilr_count[$row['is_valid_ilr']] = $row['training_record'];

			if ($row['training_record'] != 0)
			{

				$ilrstatus .= "'".addslashes((string)$row['is_valid_ilr'])."',";

				$trainingrecords .= "{ name:'".$row['is_valid_ilr']."',";
				$trainingrecords .= "y:".$row['training_record']."},";
			}
		}

		$this->panel2_graph = <<<JS

											/*******ILR Column Chart******************/
var chart;
$(function() {


/*******Build ILR bar chart******************/

chart = new Highcharts.Chart({
chart:
{
	renderTo: 'panel2',

	borderColor: '#727375',
	borderWidth: 0,
	backgroundColor: null,
	defaultSeriesType: 'column',
	inverted: false,
	Style:
	{
		fontFamily: 'arial',
 		fontSize:'11px'
	}
},
title:
{
	text: null,
	floating: false,
	align: 'center',
	style:
	{
		fontFamily: 'arial',
		fontSize: '12px',
		color: 'black',
		fontWeight: 'bold'
	}
},
credits:
{
	enabled: false
},
xAxis:
{
	labels:
	{
		enabled: true,
		style:
		{
			fontFamily: 'arial',
			fontSize: '12px',
			color: 'gray',
			fontWeight: 'bold'
		}
	},
	categories:[$ilrstatus]
},
yAxis:
{
	min: 0,
	title:
	{
		align: 'low',
		text: null,
       	style:
       	{
			fontFamily: 'arial',
			fontSize: '12px',
			color: 'gray'
       	}
	}
},
legend:
{
	layout: 'vertical'
},
colors:
	['#437C17', '#727375'],
tooltip:
{
	formatter: function() { return ''+ this.y +' Training Records';}
},
plotOptions:
{

		pointPadding: 0.2,
		borderWidth: 0,
        dataLabels:
        {
        	enabled: true,
        	color: '#FFFFFF'
            //align: 'right',
            // x: -10
        }
},
series:
[{
	cursor: 'pointer',
	point:
	{
		events:
		{
			click: function()
			{
				//alert (this.name)
				switch (this.name)
				{
					//Invalid filter
					case 'invalid':
					window.location.href='do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_funding=2';
					break;

					//Valid filter
					case 'valid':
					window.location.href='do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_funding=1';
					break;

					default:
					break;
				}
			}
		}
	},
	name: 'Training Records',
	data: [$trainingrecords]
}],
exporting:
{
	enabled: true,
	width: 500,
	filename: 'ILR Status',
	buttons:
	{
		printButton:
		{
			enabled: false
		}
	}
},
navigation:
{
	buttonOptions:
 	{
		verticalAlign: 'top',
    	y: 0
	}
}

});

});
JS;

	}

	private function generatePanel1(PDO $link) // generate learner status
	{
		$statustotal = '';

		// Build Learner Status Pie Chart
		$learner_status = $this->getLearnerStatus($link);
		foreach($learner_status as $key=>$value)
		{
			$statustotal .= "{ name:'".$key."',";
			$statustotal .= "y:".$value;

			if ($key == "On Track")
			{
				$statustotal .= ",sliced:true,";
				$statustotal .= "selected:true";
			}

			$statustotal .= "},";
		}

		$this->panel1_graph = <<<JS
$(function() {
chart = new Highcharts.Chart({
chart:
{
	renderTo: 'panel1',
	width: '250',
	height: '250',
    borderColor: '#727375',
    borderWidth: 0,
    backgroundColor: null,
    defaultSeriesType: 'pie'
},
title:
{
	text: null,
	floating: false,
	align: 'center',
	style:
	{
		fontFamily: 'arial',
		fontSize: '12px',
		color: 'black',
		fontWeight: 'bold'
	}
},
legend:
{
	layout: 'vertical'
},
credits:
{
	enabled: false
},
colors:
[
	'#151B8D',
	'#437C17',
	'#eeeeee'
],
tooltip:
{
	formatter: function()
	{
		//return + this.y+ ' Learners';

		return + this.y+ ' Learners (' + Math.round(this.percentage*10)/10 +' %)';
	}
},
plotOptions:
{
	pie:
	{
		/*size:200*/
	}
},
series:
[{
	cursor: 'pointer',
	showInLegend: true,
	dataLabels:
	{
		enabled: true,
        distance: -50,
		color: 'white',
		fontWeight: 'bold',
		formatter: function()
		{
			return this.y;
		},
        borderRadius: 5,
        //padding: 3,
        //shadow: true,
        //backgroundColor: 'rgba(252, 255, 197, 0.7)',
        borderWidth: 1,
        borderColor: 'red'
	},
	point:
	{
		events:
		{
			click: function()
			{
				//alert (this.name)
				switch (this.name)
				{
					//Behind filter
					case 'Behind':
					window.location.href='do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_progress=2';
					break;

					//On track filter
					case 'On Track':
					window.location.href='do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_progress=1';
					break;

					default:
					break;
				}
			}
		}
	},
	type: 'pie',
	name: 'Status Total',
	data:  [$statustotal]
}],
exporting:
{
	enabled: true,
	width: 300,
	filename: 'Learner Status',
	buttons:
	{
		printButton:
		{
			enabled: false
		}
	}
},
navigation:
{
	buttonOptions:
	{
		verticalAlign: 'top',
		y: 0
	},
	menuItemStyle:
    {
 		width:150,
		borderLeft: '20px solid #E0E0E0'
	}
}

});

});


JS;

	}

	private function getLearnerStatus(PDO $link)
	{
		$cache_key = $_SERVER['SERVER_NAME'].' '.$_SESSION['user']->username.' homepage learner status graph';
		$status = Cache::get($cache_key);
		if($status){
			return $status;
		}

		$view = HomePage::getInstance($link);
		$view->refresh($link, $_REQUEST);
		$sql = $view->getSQLStatement()->__toString();

		$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$status = array("Behind"=>0, "On Track"=>0);
		foreach($rows as $row)
		{
			if($row['status_code'] !=2 and $row['status_code'] !=3)
			{
				if(floatval($row['target']) >= 0 || floatval($row['percentage_completed']) >= 0)
				{
					if(floatval($row['percentage_completed']) < floatval($row['target']))
						$status['Behind']++;
					else
						$status['On Track']++;
				}
			}
		}
		Cache::set($cache_key, $status, 600);
		Cache::set($cache_key.' timestamp', Date::toTimestamp("now"), 600);
		return $status;
	}

	private function generateGraph3()
	{
		return <<<JS
		$(function () {
    $('#panel3').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Success Rates'
        },
        subtitle: {
            text: 'Source: Sunesis'
        },
        xAxis: {
            categories: [
                '2011-12',
                '2012-13',
                '2013-14',
                '2014-15'
            ]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Percentage'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Overall',
            data: [77, 72, 76, 81]

        }, {
            name: 'Timely',
            data: [55, 61, 63, 67]

        }]
    });
});
JS;

	}

	private function generateGraph4(PDO $link)
	{
		$total_learners = DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE type = 5");
		return <<<JS
		$('#panel4').highcharts({

        chart: {
            type: 'gauge',
            plotBackgroundColor: null,
            plotBackgroundImage: null,
            plotBorderWidth: 0,
            plotShadow: false
        },

        title: {
            text: 'Total Learners'
        },

        pane: {
            startAngle: -150,
            endAngle: 150,
            background: [{
                backgroundColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, '#FFF'],
                        [1, '#333']
                    ]
                },
                borderWidth: 0,
                outerRadius: '109%'
            }, {
                backgroundColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, '#333'],
                        [1, '#FFF']
                    ]
                },
                borderWidth: 1,
                outerRadius: '107%'
            }, {
                // default background
            }, {
                backgroundColor: '#DDD',
                borderWidth: 0,
                outerRadius: '105%',
                innerRadius: '103%'
            }]
        },

        // the value axis
        yAxis: {
            min: 0,
            max: 20000,

            minorTickInterval: 'auto',
            minorTickWidth: 1,
            minorTickLength: 10,
            minorTickPosition: 'inside',
            minorTickColor: '#666',

            tickPixelInterval: 30,
            tickWidth: 2,
            tickPosition: 'inside',
            tickLength: 10,
            tickColor: '#666',
            labels: {
                step: 2,
                rotation: 'auto'
            },
            title: {
                text: ''
            },
            plotBands: [{
                from: 0,
                to: 120,
                color: '#55BF3B' // green
            }, {
                from: 120,
                to: 160,
                color: '#DDDF0D' // yellow
            }, {
                from: 160,
                to: 200,
                color: '#DF5353' // red
            }]
        },

        series: [{
            name: 'Speed',
            data: [$total_learners],
            tooltip: {
                valueSuffix: ' km/h'
            }
        }]

    });
JS;

	}

	private function generateGraph5()
	{
		return <<<JS
		$('#panel5').highcharts({

		chart: {
			type: 'gauge',
			plotBackgroundColor: null,
			plotBackgroundImage: null,
			plotBorderWidth: 0,
			plotShadow: false
		},

		title: {
			text: 'Achievers'
		},

		pane: {
			startAngle: -150,
			endAngle: 150,
			background: [{
				backgroundColor: {
					linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
					stops: [
						[0, '#FFF'],
						[1, '#333']
					]
				},
				borderWidth: 0,
				outerRadius: '109%'
			}, {
				backgroundColor: {
					linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
					stops: [
						[0, '#333'],
						[1, '#FFF']
					]
				},
				borderWidth: 1,
				outerRadius: '107%'
			}, {
				// default background
			}, {
				backgroundColor: '#DDD',
				borderWidth: 0,
				outerRadius: '105%',
				innerRadius: '103%'
			}]
		},

		// the value axis
		yAxis: {
			min: 0,
			max: 100,

			minorTickInterval: 'auto',
			minorTickWidth: 1,
			minorTickLength: 10,
			minorTickPosition: 'inside',
			minorTickColor: '#666',

			tickPixelInterval: 30,
			tickWidth: 2,
			tickPosition: 'inside',
			tickLength: 10,
			tickColor: '#666',
			labels: {
				step: 2,
				rotation: 'auto'
			}//,
			/*title: {
							text: 'km/h'
						},
						plotBands: [{
							from: 0,
							to: 120,
							color: '#55BF3B' // green
						}, {
							from: 120,
							to: 160,
							color: '#DDDF0D' // yellow
						}, {
							from: 160,
							to: 200,
							color: '#DF5353' // red
						}]*/
		},

		series: [{
			name: 'Achievers',
			data: [55],
			tooltip: {
				// valueSuffix: ' km/h'
			}
		}],
		exporting: {
			enabled: false
		}
	});



JS;

	}

	private function generateGraph6(PDO $link)
	{
		$learners = DAO::getSingleValue($link, "SELECT CONCAT(CEIL(((SUM(IF(tr.`target_date` > tr.`closure_date`, 1, 0)))*100)/(SELECT COUNT(*) FROM tr WHERE tr.`closure_date` IS NOT NULL)), '%')FROM tr;");
		return <<<JS
		//document.getElementById('panel6').innerHTML = '34%';
		var para = document.createElement('p');
		para.style.cssText = 'font-size: 100px;padding: 15px;height: 50px;text-align: center;text-shadow: -4px 4px 3px #999, 1px -1px 2px #000;margin-top: 0;margin-bottom: 0;color: #395596;';
		var node = document.createTextNode('$learners');
		para.appendChild(node);
		document.getElementById("panel6").appendChild(para);
		var para2 = document.createElement('p');
		para2.style.cssText = 'bottom : 0;height : 40px;margin-top : 50px;text-align: center;';
		var node2 = document.createTextNode('past their expected end date');
		para2.appendChild(node2);
		document.getElementById("panel6").appendChild(para2);
JS;

	}

	private function generateGraph7(PDO $link)
	{
		$continuing = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr WHERE status_code = 1");
		$completed = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr WHERE status_code = 2");
		$withdrawn = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr WHERE status_code = 3");
		return <<<JS
		$(function () {
    $('#panel7').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotShadow: true
        },
        title: {
            text: 'Learners'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true/*,
                    format: '{point.name}: {point.percentage:.1f} %'*/
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Learners',
            data: [
                ['Continuing',   $continuing],
                ['Withdrawn',       $withdrawn],
                {
                    name: 'Completed',
                    y: $completed,
                    sliced: true,
                    selected: true
                }
            ]
        }]
    });
});


JS;

	}

	private function generateGraph8()
	{
		$style = 'style="font-size: 30px;text-shadow: -4px 4px 3px #999, 1px -1px 2px #000;color: #395596;"';
		return <<<JS

	    var table = '<table style="height : 40px;margin-top : 50px;">' +
	     '<tr>' +
	      '<td align="center">' +
	       '<img src="/images/smile-face.png" width="80" />' +
	      '</td>' +
	      '<td align="center">' +
	       '<img src="/images/flat-face.png" width="80" />' +
	      '</td>' +
	      '<td align="center">' +
	       '<img src="/images/sad-face.png" width="80" />' +
	      '</td>' +
	     '</tr>' +
	     '<tr>' +
	      '<td align="center" $style>50%</td>' +
	      '<td align="center" $style>35%</td>' +
	      '<td align="center" $style>15%</td>' +
	     '</tr>' +
	    '</table>';

	    document.getElementById('panel8').innerHTML = table;

JS;

	}

}