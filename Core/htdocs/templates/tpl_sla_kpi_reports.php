<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SLA / KPI reports - Dashboard</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" href="/css/reports_css.css" type="text/css"/>

<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<script language="javascript" src="/js/highcharts.js" type="text/javascript"></script>
<script src="/js/exporting.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
      $('#container_line').highcharts({
            chart: {
                type: 'line',
                //marginRight: 130,
                //marginBottom: 25
            },
            title: {
                text: 'Monthly Average Temperature',
                x: -20 //center
            },
            subtitle: {
                text: 'Source: WorldClimate.com',
                x: -20
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            yAxis: {
                title: {
                    text: 'Temperature (�C)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '�C'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
            },
            series: [{
                name: 'Tokyo',
                data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
            }, {
                name: 'New York',
                data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
            }, {
                name: 'Berlin',
                data: [-0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9, 14.3, 9.0, 3.9, 1.0]
            }, {
                name: 'London',
                data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
            }]
        });


        $('#container_pie').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'Browser market shares at a specific website, 2010'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage}%</b>',
            	percentageDecimals: 1
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Browser share',
                data: [
                    ['Firefox',   45.0],
                    ['IE',       26.8],
                    {
                        name: 'Chrome',
                        y: 12.8,
                        sliced: true,
                        selected: true
                    },
                    ['Safari',    8.5],
                    ['Opera',     6.2],
                    ['Others',   0.7]
                ]
            }]
        });


        var colors = Highcharts.getOptions().colors,
            categories = ['MSIE', 'Firefox', 'Chrome', 'Safari', 'Opera'],
            name = 'Browser brands',
            data = [{
                    y: 55.11,
                    color: colors[0],
                    drilldown: {
                        name: 'MSIE versions',
                        categories: ['MSIE 6.0', 'MSIE 7.0', 'MSIE 8.0', 'MSIE 9.0', 'MSIE 10.0'],
                        data: [10.85, 7.35, 33.06, 2.81, 15.2],
                        color: colors[0]
                    }
                }, {
                    y: 21.63,
                    color: colors[1],
                    drilldown: {
                        name: 'Firefox versions',
                        categories: ['Firefox 2.0', 'Firefox 3.0', 'Firefox 3.5', 'Firefox 3.6', 'Firefox 4.0'],
                        data: [0.20, 0.83, 1.58, 13.12, 5.43],
                        color: colors[1]
                    }
                }, {
                    y: 11.94,
                    color: colors[2],
                    drilldown: {
                        name: 'Chrome versions',
                        categories: ['Chrome 5.0', 'Chrome 6.0', 'Chrome 7.0', 'Chrome 8.0', 'Chrome 9.0',
                            'Chrome 10.0', 'Chrome 11.0', 'Chrome 12.0'],
                        data: [0.12, 0.19, 0.12, 0.36, 0.32, 9.91, 0.50, 0.22],
                        color: colors[2]
                    }
                }, {
                    y: 7.15,
                    color: colors[3],
                    drilldown: {
                        name: 'Safari versions',
                        categories: ['Safari 5.0', 'Safari 4.0', 'Safari Win 5.0', 'Safari 4.1', 'Safari/Maxthon',
                            'Safari 3.1', 'Safari 4.1'],
                        data: [4.55, 1.42, 0.23, 0.21, 0.20, 0.19, 0.14],
                        color: colors[3]
                    }
                }, {
                    y: 2.14,
                    color: colors[4],
                    drilldown: {
                        name: 'Opera versions',
                        categories: ['Opera 9.x', 'Opera 10.x', 'Opera 11.x'],
                        data: [ 0.12, 0.37, 1.65],
                        color: colors[4]
                    }
                }];

        function setChart(name, categories, data, color) {
			chart.xAxis[0].setCategories(categories, false);
			chart.series[0].remove(false);
			chart.addSeries({
				name: name,
				data: data,
				color: color || 'white'
			}, false);
			chart.redraw();
        }

        var chart = $('#container').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Browser market share, April, 2011'
            },
            subtitle: {
                text: 'Click the columns to view versions. Click again to view brands.'
            },
            xAxis: {
                categories: categories
            },
            yAxis: {
                title: {
                    text: 'Total percent market share'
                }
            },
            plotOptions: {
                column: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function() {
                                var drilldown = this.drilldown;
                                if (drilldown) { // drill down
                                    setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                                } else { // restore
                                    setChart(name, categories, data);
                                }
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        color: colors[0],
                        style: {
                            fontWeight: 'bold'
                        },
                        formatter: function() {
                            return this.y +'%';
                        }
                    }
                }
            },
            tooltip: {
                formatter: function() {
                    var point = this.point,
                        s = this.x +':<b>'+ this.y +'% market share</b><br/>';
                    if (point.drilldown) {
                        s += 'Click to view '+ point.category +' versions';
                    } else {
                        s += 'Click to return to browser brands';
                    }
                    return s;
                }
            },
            series: [{
                name: name,
                data: data,
                color: 'white'
            }],
            exporting: {
                enabled: true
            }
        })
        .highcharts(); // return chart
});

function redirectPage(value)
{
    window.location.href="do.php?_action="+value;
}
</script>

</head>

<body>
<div class="banner">
	<div class="Title">
    <!--<?php echo $_SESSION['user']->firstnames.' '.$_SESSION['user']->surname.' (<code>'.$_SESSION['user']->username.'</code>)'; ?> -->
    SLA / KPI Reports
    </div>

	<!--<div class="ActionIconBar"></div> -->
</div>

<div style="margin:0 0 0 7px;">
Select :
<select name="report_type" id="report_type" onchange="redirectPage(this.value)">
<option value="">Onward progression � L2, L3, L4</option>
<option value="sla_kpi_rep_achievers" <?php if($report_type == "sla_kpi_rep_achievers"){echo 'selected';}?>>Progress through qualification, achieved to date</option>
<option value="">Retention Rates over time</option>
<option value="">Overall Success Rate</option>
<option value="">Timely Success Rates</option>
<option value="sla_kpi_rep_last_visit" <?php if($report_type == "sla_kpi_rep_last_visit"){echo 'selected';}?>>Weeks since last visit for each learner</option>
<option value="sla_kpi_rep_new_starts" <?php if($report_type == "sla_kpi_rep_new_starts"){echo 'selected';}?>>Number of starts in period and over time</option>
<option value="">Number of completions in period and over time</option>
<option value="">Number of early leavers in period and over time</option>
<optgroup label="Satisfaction survey results">
  <option value="">Program Satisfaction</option>
  <option value="">FS Tutor Rating</option>
  <option value="">Assessor rating</option>
</optgroup>
<option value="">Number of Learners broken down by</option>
</select>
</div>

<br style="clear: both;"/><br style="clear: both;"/>

<!--<table style="left: 10px; width: 1233px; border: 1px solid #ABABAB; border-collapse: collapse; margin:0 0 0 7px; background-color: #EFEFEF;" cellpadding="6" border="0">-->
<table style="left: 10px; width: 1233px; margin:0 0 0 7px;" class="CSSTableGenerator">
<tr>
  <td><b>Learners per group</b></td>
  <td><b>Total OPP</b></td>
  <td><b>Funded Learners</b></td>
</tr>

<tr style="font-size: 20px;font-weight: bold;">
  <td>14</td>
  <td>&pound;61,888</td>
  <td>320</td>
</tr>

<tr>
  <td>Training avg : 12 (0.00%)</td>
  <td>% of total : 100%(320)</td>
  <td>% of total : 100% (&pound;61,888)</td>
</tr>

</table>


<br style="clear: both;"/><br style="clear: both;"/>

<div style="width: 1241px;">

<div style="width: 930px; float: left;">
<div style="position: absolute; left: 10px; width: 930px;" id="u211">
    <div style="left:10px; top:10px; width:911px; height:381px; overflow:visible; border: 1px solid #ABABAB; border-radius: 7px 7px 7px 7px;" id="container_line"></div>
<div style="clear: both;"></div>

<div style="float: left; width:1230px; margin: 40px 0 0; border: 1px solid #ABABAB; border-radius: 7px 7px 7px 7px;">
<div style="width:570px; height:381px;float:left;" id="container_pie"></div>
<div style="width:570px; height:381px;float:left; margin: 0 0 0 20px;" id="container"></div>
</div>

</div>
</div>

<div style="width: 300px; float: right; border: 1px solid #ABABAB; border-radius: 7px 7px 7px 7px; min-height: 381px; padding: 0 0 0 4px;">
<div style="width:150px; text-align: left ; font-family:Arial; text-align:left; word-wrap:break-word;" id="u271">
<div style="font-family:Arial; margin: 6px 0 20px;" id="u262">
    <span style=" font-family:'Arial'; color:#333333; font-size:19px;">Change Graph</span>
</div>

<div id="u271_rtf">
    <span style=" font-family:'Arial'; color:#666666; font-size:13px;">Graph Type</span>
</div>
<br>
<select style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none" id="u269">
<option value="Line">Line</option>
<option value="Line + Bar">Line + Bar</option>
<option value="Line + Bar + Pie" selected="">Line + Bar + Pie</option>
</select>
</div>
</div>

</div>

</body>
</html>