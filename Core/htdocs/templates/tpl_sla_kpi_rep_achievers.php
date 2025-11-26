<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $page_title;?></title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" href="/css/reports_css.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<script language="javascript" src="/js/highcharts.js" type="text/javascript"></script>
<script language="javascript" src="/js/highcharts-more.js" type="text/javascript"></script>
<script src="/js/exporting.js" type="text/javascript"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script type="text/javaScript" src="/calendarPopup/CalendarPopup.js"></script>
<script language="JavaScript">
	var calPop = new CalendarPopup("calPop1");
	calPop.showNavigationDropdowns();
	document.write(getCalendarStyles());
</script>


<script type="text/javascript">
var data_exists="";//if graph data exists or not
var colors = Highcharts.getOptions().colors;

var report_type = "<?php echo $report_type; ?>";
var page_mode = "<?php echo page_mode; ?>";


var line_chart_div_id = "container_line";
var line_chart_title="";
var line_chart_subtitle="";
var line_chart_x_axis_title="";
var line_chart_x_axis_categories=[];
var line_chart_y_axis_title="";
var line_chart_series_title="";
var line_chart_series_data=[];
var line_chart_drilldown_details=[];
var line_chart_info_arr=[];
var line_chart_color = colors[0];



var bar_chart_div_id = "container";
var bar_chart_title="";
var bar_chart_subtitle="";
var bar_chart_x_axis_title="";
var bar_chart_x_axis_categories=[];
var bar_chart_y_axis_title="";
var bar_chart_series_title="";
var bar_chart_series_data=[];
var bar_chart_drilldown_details=[];
var bar_chart_info_arr=[];
var bar_chart_tooltip_format = "";
var bar_chart_drillup_tooltip_format = "";
var bar_chart_drilldown_tooltip_format = "";

function barChartTooltip(obj)
{
	var point = obj.point;

	if (point.drilldown)
	{
		bar_chart_tooltip_format = bar_chart_drillup_tooltip_format
	}
	else
	{
		bar_chart_tooltip_format = bar_chart_drilldown_tooltip_format;
	}
	bar_chart_tooltip_format = bar_chart_tooltip_format.replace("ttl_title",obj.key);
	bar_chart_tooltip_format = bar_chart_tooltip_format.replace("ttl_value",obj.y);

	return bar_chart_tooltip_format;
}


var pie_chart_div_id = "container_pie";
var pie_chart_title="";
var pie_chart_subtitle="";
var pie_chart_x_axis_title="";
var pie_chart_x_axis_categories=[];
var pie_chart_y_axis_title="";
var pie_chart_series_title="";
var pie_chart_series_data=[];
var pie_chart_drilldown_details=[];
var pie_chart_info_arr=[];



var speedo_chart_div_id = "container_speedo";
var speedo_chart_title="";
var speedo_chart_subtitle="";
var speedo_chart_series_data=[];

function load_graphs()
{
	$.ajax({
		type:"GET",
		data:"generate_report=generate_report&report_type="+report_type+"&"+$('#report_criteria_form').serialize(),
		url:"do.php?_action=ajax_sla_kpi_reports",
		dataType : 'json',
		beforeSend:function(data)
		{
			$('#sp_loading_graphs').show();
		},
		success:function(response)
		{
			if(response.result == "no_data")
			{
				alert('Sorry, no data found !');
				$('#sp_loading_graphs').hide();
				$('#container_line').hide();
				$('#div_two_graphs').hide();
				$('#upper_div').hide();
				data_exists="false";
			}
			else
			{
				data_exists="true";
				var graph_type = $('#graph_type').val();
				$('#container_line').show();
				show_hide_graph_types(graph_type);
				$('#upper_div').show();

				////////////////set data for line chart

				line_chart_info_arr['chart_div_id'] = line_chart_div_id;
				line_chart_info_arr['chart_title'] = line_chart_title = response.line_chart_details.title;
				line_chart_info_arr['chart_subtitle'] = line_chart_subtitle = response.line_chart_details.subtitle;
				line_chart_info_arr['chart_x_axis_title'] = line_chart_x_axis_title = response.line_chart_details.x_axis_title;
				line_chart_info_arr['chart_x_axis_categories'] = line_chart_x_axis_categories = response.line_chart_details.x_axis_categories;
				line_chart_info_arr['chart_y_axis_title'] = line_chart_y_axis_title = response.line_chart_details.y_axis_title;
				line_chart_info_arr['chart_series_title'] = line_chart_series_title = response.line_chart_details.series_title;
				line_chart_info_arr['chart_series_data'] = line_chart_series_data = response.line_chart_details.series_data;
				line_chart_info_arr['chart_drilldown_details'] = line_chart_drilldown_details = response.line_chart_details.drilldown_details;

				$('#error_div').hide();
				if(line_chart_x_axis_categories.length > 14)
				{
					$('#error_div').show();
					$('#sp_loading_graphs').hide();
				}

				$('#'+line_chart_div_id).highcharts().series[0].update({
					//color:colors[0],
					point:{
						events:{
							click:function(){
								var drilldown = this.drilldown;
								if (drilldown) { // drill down
									setMyChart($('#'+line_chart_div_id).highcharts(), drilldown.name, drilldown.categories, drilldown.data, line_chart_color, 'line_drilldown_x_axis_title', chartType="line");
								}
							}
						}
					}
				});

				$('#'+line_chart_div_id).highcharts().setTitle({ text: line_chart_title }, { text: line_chart_subtitle });

				$('#'+line_chart_div_id).highcharts().xAxis[0].setTitle({
					text: line_chart_x_axis_title
				});

				$('#'+line_chart_div_id).highcharts().xAxis[0].categories = line_chart_x_axis_categories;

				$('#'+line_chart_div_id).highcharts().yAxis[0].setTitle({
					text: line_chart_y_axis_title
				});

				$('#'+line_chart_div_id).highcharts().series[0].update({
					name: line_chart_series_title,
					color:line_chart_color
				});

				$('#'+line_chart_div_id).highcharts().series[0].setData(line_chart_series_data);

				if(typeof response.line_chart_details.drilldown_details !== 'undefined')//if drilldown details exists
				{
					for(var i=0; i<line_chart_series_data.length; i++)
					{
						var x_cat_name = line_chart_x_axis_categories[i];


						var drilldown_name = line_chart_drilldown_details[x_cat_name].drill_down.name;
						var drilldown_x_axis_title = line_chart_drilldown_details[x_cat_name].drill_down.x_axis_title;

						var drilldown_categories = line_chart_drilldown_details[x_cat_name].drill_down.categories;

						var drilldown_data = line_chart_drilldown_details[x_cat_name].drill_down.data;

						$('#'+line_chart_div_id).highcharts().series[0].data[i].update({

							events:{
								click: function() {

									var drilldown = this.drilldown;
									if (drilldown) { // drill down

										setMyChart($('#'+line_chart_div_id).highcharts(), drilldown.name, drilldown.categories, drilldown.data, line_chart_color, drilldown_x_axis_title, chartType="line");
									}
								}
							},
							drilldown: {
								name: drilldown_name,
								categories: drilldown_categories,
								data: drilldown_data//,
							}
						});

					}
				}

				////////////////set data for bar chart

				bar_chart_info_arr['chart_div_id'] = bar_chart_div_id;
				bar_chart_info_arr['chart_title'] = bar_chart_title = response.bar_chart_details.title;
				bar_chart_info_arr['chart_subtitle'] = bar_chart_subtitle = response.bar_chart_details.subtitle;
				bar_chart_info_arr['chart_x_axis_title'] = bar_chart_x_axis_title = response.bar_chart_details.x_axis_title;
				bar_chart_info_arr['chart_x_axis_categories'] = bar_chart_x_axis_categories = response.bar_chart_details.x_axis_categories;
				bar_chart_info_arr['chart_y_axis_title'] = bar_chart_y_axis_title = response.bar_chart_details.y_axis_title;
				bar_chart_info_arr['chart_series_title'] = bar_chart_series_title = response.bar_chart_details.series_title;
				bar_chart_info_arr['chart_series_data'] = bar_chart_series_data = response.bar_chart_details.series_data;
				bar_chart_info_arr['chart_drilldown_details'] = bar_chart_drilldown_details = response.bar_chart_details.drilldown_details;
				bar_chart_drillup_tooltip_format = response.bar_chart_details.bar_chart_drillup_tooltip_format;
				bar_chart_drilldown_tooltip_format = response.bar_chart_details.bar_chart_drilldown_tooltip_format;

				$('#'+bar_chart_div_id).highcharts().series[0].update({
					//color:"green",
					point:{
						events:{
							click:function(){

								var drilldown = this.drilldown;
								if (drilldown) { // drill down
									setMyChart($('#'+bar_chart_div_id).highcharts(), drilldown.name, drilldown.categories, drilldown.data, drilldown.color, 'bar_drilldown_x_axis_title',chartType="bar");
								}
							}
						}
					}
				});

				$('#'+bar_chart_div_id).highcharts().setTitle({ text: bar_chart_title }, { text: bar_chart_subtitle });

				$('#'+bar_chart_div_id).highcharts().xAxis[0].setTitle({
					text: bar_chart_x_axis_title
				});

				$('#'+bar_chart_div_id).highcharts().xAxis[0].categories = bar_chart_x_axis_categories;

				$('#'+bar_chart_div_id).highcharts().yAxis[0].setTitle({
					text:bar_chart_y_axis_title
				});

				$('#'+bar_chart_div_id).highcharts().series[0].update({
					name:bar_chart_series_title
				});


				$('#'+bar_chart_div_id).highcharts().series[0].setData(bar_chart_series_data);

				//if drilldown details exists
				if(typeof response.bar_chart_details.drilldown_details !== 'undefined')
				{

					for(var i=0; i<bar_chart_series_data.length; i++)
					{
						var x_cat_name = bar_chart_x_axis_categories[i];
						var drilldown_name = bar_chart_drilldown_details[x_cat_name].drill_down.name;
						var drilldown_x_axis_title = bar_chart_drilldown_details[x_cat_name].drill_down.x_axis_title;

						var drilldown_categories = bar_chart_drilldown_details[x_cat_name].drill_down.categories;

						var drilldown_data = bar_chart_drilldown_details[x_cat_name].drill_down.data;

						$('#'+bar_chart_div_id).highcharts().series[0].data[i].update({
							color:colors[i],

							events:{
								click: function() {

									var drilldown = this.drilldown;
									if (drilldown) { // drill down
										setMyChart($('#'+bar_chart_div_id).highcharts(), drilldown.name, drilldown.categories, drilldown.data, drilldown.color, drilldown_x_axis_title, chartType="bar");
									}
								}
							},
							drilldown: {
								name: drilldown_name,
								categories: drilldown_categories,
								data: drilldown_data,
								color: colors[i]
							}
						});
					}
				}

				////////////////set data for pie chart
				//console.log(response);
				pie_chart_info_arr['chart_div_id'] = pie_chart_div_id;
				pie_chart_info_arr['chart_title'] = pie_chart_title = response.pie_chart_details.title;
				pie_chart_info_arr['chart_subtitle'] = pie_chart_subtitle = response.pie_chart_details.subtitle;
				pie_chart_info_arr['chart_x_axis_title'] = pie_chart_x_axis_title = response.pie_chart_details.x_axis_title;
				pie_chart_info_arr['chart_x_axis_categories'] = pie_chart_x_axis_categories = response.pie_chart_details.x_axis_categories;
				pie_chart_info_arr['chart_y_axis_title'] = pie_chart_y_axis_title = response.pie_chart_details.y_axis_title;
				pie_chart_info_arr['chart_series_title'] = pie_chart_series_title = response.pie_chart_details.series_title;
				pie_chart_info_arr['chart_series_data'] = pie_chart_series_data = response.pie_chart_details.series_data;
				pie_chart_info_arr['chart_drilldown_details'] = pie_chart_drilldown_details = response.pie_chart_details.drilldown_details;


				$('#'+pie_chart_div_id).highcharts().series[0].update({
					point:{
						events:{
							click:function(){

								var drilldown = this.drilldown;
								if (drilldown) { // drill down
									setMyPieChart($('#'+pie_chart_div_id).highcharts(), drilldown.name, drilldown.data, drilldown.color);
								}
							}
						}
					}
				});

				$('#'+pie_chart_div_id).highcharts().setTitle({ text: pie_chart_title }, { text: pie_chart_subtitle });

				var piechartData=[];

				$.each(pie_chart_series_data, function(key,value) {
					var point = [];
					point.push(key);
					value = parseFloat(value);
					point.push(value);
					piechartData.push(point);
				});


				$('#'+pie_chart_div_id).highcharts().series[0].setData(piechartData);

				//if drilldown details exists
				if(typeof response.pie_chart_details.drilldown_details !== 'undefined')
				{
					var i=0;
					$.each(pie_chart_series_data, function(key,value) {

						var x_cat_name = key;
						var drilldown_name = pie_chart_drilldown_details[x_cat_name].drill_down.name;
						var drilldown_data = pie_chart_drilldown_details[x_cat_name].drill_down.data;

						$('#'+pie_chart_div_id).highcharts().series[0].data[i].update({
							name : x_cat_name,
							y: value,
							drilldown: {
								name: drilldown_name,
								data: drilldown_data//,
							},
							events:{
								click: function() {

									var drilldown = this.drilldown;
									if (drilldown) { // drill down
										setMyPieChart($('#'+pie_chart_div_id).highcharts(), drilldown.name, drilldown.data, drilldown.color=null);
									}
								}
							}
						});

						i++;
					});
				}

				////////////////set data for speedo chart
				speedo_chart_title = response.speedo_chart_details.title;
				speedo_chart_subtitle = response.speedo_chart_details.subtitle;
				speedo_chart_series_data = [response.speedo_chart_details.series_data];
				//alert('speedo_chart_series_data = '+speedo_chart_series_data);

				if(speedo_chart_series_data != '')
				{
					$('#'+speedo_chart_div_id).highcharts().setTitle({ text: speedo_chart_title }, { text: speedo_chart_subtitle });
					$('#'+speedo_chart_div_id).highcharts().yAxis[0].options.max = response.speedo_chart_details.max_value;

					$('#'+speedo_chart_div_id).highcharts().series[0].update({
						name:speedo_chart_title,
						data: speedo_chart_series_data
					});
				}
				else
				{
					$('#container_speedo').hide();
				}

				$('#div_data_table').html(response.data_table);

				$('#sp_loading_graphs').hide();
			}
		}
	});
}

function setMyPieChart(chart_obj, name, data, color)
{
	chart_obj.series[0].remove(false);

	var piechartData=[];

	$.each(data, function(key,value) {
		var point = [];
		point.push(key);
		value = parseFloat(value);
		point.push(value);
		piechartData.push(point);
	});


	chart_obj.addSeries({
		name: name,
		data: piechartData,
		color: color,
		showInLegend: false
	}, false);



	var i=0;
	$.each(data, function(key,value) {
		var x_cat_name = key;

		if(typeof pie_chart_drilldown_details[x_cat_name] !== 'undefined')
		{
			var drilldown_name = pie_chart_drilldown_details[x_cat_name].drill_down.name;

			var drilldown_data = pie_chart_drilldown_details[x_cat_name].drill_down.data;

			chart_obj.series[0].data[i].update({
				name : x_cat_name,
				y: value,
				drilldown: {
					name: drilldown_name,
					data: drilldown_data//,
				}
			});
		}
		i++;

	});


	//chart_obj.series[0].setData(piechartData);
	chart_obj.redraw();
	//alert('hey here');
	chart_obj.series[0].update({
		color:color,
		point:{
			events:{
				click:function(){

					var drilldown = this.drilldown;
					if (drilldown) { // drill down
						setMyPieChart(chart_obj, drilldown.name, drilldown.data, color);
					}
					else
					{
						setMyPieChart(chart_obj, pie_chart_series_title, pie_chart_series_data, color);
					}
				}
			}
		}
	});
}


function setMyChart(chart_obj, name, categories, data, color, x_axis_title, chartType)
{
	//alert('setMyChart12');
	chart_obj.xAxis[0].setTitle({
		text: x_axis_title
	});
	chart_obj.xAxis[0].setCategories(categories, false);
	chart_obj.series[0].remove(false);
	chart_obj.addSeries({
		name: name,
		data: data,
		color: color,
		showInLegend: false
	}, false);
	chart_obj.redraw();
	chart_obj.series[0].update({
		color:color,
		point:{
			events:{
				click:function(){

					var drilldown = this.drilldown;
					if (drilldown) { // drill down
						setMyChart(chart_obj, drilldown.name, drilldown.categories, drilldown.data, drilldown.color, x_axis_title, chartType);
					}
					else
					{
						if(chartType == "bar")
						{
							setMyChart(chart_obj, bar_chart_series_title, bar_chart_x_axis_categories, bar_chart_series_data, color, bar_chart_x_axis_title, chartType);
						}
						else if(chartType == "line")
						{
							setMyChart(chart_obj, line_chart_series_title, line_chart_x_axis_categories, line_chart_series_data, color, line_chart_x_axis_title, chartType);
						}

					}

				}
			}
		}
	});
}


$(document).ready(function() {
	load_graphs();

	var categories = ['Q1', 'Q2', 'Q3', 'Q4', 'Q5'],
		drill_categories = ['Employer', 'Training Provider', 'Contract', 'Assessor'],
		name = 'Achievers',
		data = [{
			y: 55.11,
			color: colors[0],
			drilldown: {
				name: 'MSIE versions',
				categories: drill_categories,
				data: [10.85, 7.35, 33.06, 2.81],
				color: colors[0]
			}
		}, {
			y: 21.63,
			color: colors[1],
			drilldown: {
				name: 'Firefox versions',
				categories: drill_categories,
				data: [0.20, 0.83, 1.58, 13.12],
				color: colors[1]
			}
		}, {
			y: 11.94,
			color: colors[2],
			drilldown: {
				name: 'Chrome versions',
				categories: drill_categories,
				data: [0.12, 0.19, 0.12, 0.36],
				color: colors[2]
			}
		}, {
			y: 7.15,
			color: colors[3],
			drilldown: {
				name: 'Safari versions',
				categories: drill_categories,
				data: [4.55, 1.42, 0.23, 0.21],
				color: colors[3]
			}
		}, {
			y: 2.14,
			color: colors[4],
			drilldown: {
				name: 'Opera versions',
				categories: drill_categories,
				data: [ 0.12, 0.37, 1.65, 5.8],
				color: colors[4]
			}
		}];


	var chart = $('#'+bar_chart_div_id).highcharts({
		chart: {
			type: 'column'
		},
		title: {
			text: 'Bar chart title'
		},
		subtitle: {
			text: 'Click the columns to view versions. Click again to view brands.'
		},
		xAxis: {
			categories: categories,
			title: {
				text: 'bar x-title'
			}
		},
		yAxis: {
			title: {
				text: 'bar y-title'
			}
		},
		plotOptions: {
			column: {
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					style: {
						fontWeight: 'bold'
					},
					formatter: function() {
						return this.y;
					}
				}
			}
		},
		tooltip: {
			formatter: function() {
				return barChartTooltip(this);
			}
		},
		series: [{
			name: name,
			data: data,
			showInLegend: false
		}],
		exporting: {
			enabled: false
		}
	})
		.highcharts(); // return chart

	var chart_drill_line = $('#'+line_chart_div_id).highcharts({
		chart: {
			type: 'line'
		},
		title: {
			text: 'Browser market share, April, 2011 (Line chart with drilldown)'
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
			series: {
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					style: {
						fontWeight: 'bold'
					}
				}
			}
		},
		tooltip: {
			formatter: function() {
				return barChartTooltip(this);
			}
		},
		series: [{
			name: name,
			data: data,
			color: line_chart_color,
			showInLegend: false
		}],
		exporting: {
			enabled: false
		}
	})
		.highcharts(); // return chart

	var data_drill_pie = [{
		name : 'MS IE',
		y: 55,
		color: colors[0],
		drilldown: {
			name: 'MS IE Versions',
			data: [['IE 6.0',10], ['IE 7.0',7], ['IE 8.0',33], ['IE 9.0',2]],
			color: colors[0]
		}}, {
		name : 'Firefox',
		y: 25,
		color: colors[1],
		drilldown: {
			name: 'Firefox versions',
			data: [['Firefox 12.2',12], ['Firefox 14.5',99], ['Firefox 18.6',36], ['Firefox 20.1',52]],
			color: colors[0]
		}}, {
		name : 'Safari',
		y: 45,
		color: colors[2],
		drilldown: {
			name: 'Safari versions',
			data: [['Safari 2.5',50], ['Safari 4.3',99]],
			color: colors[0]
		}
	}];

	var drill_pie_chart = new Highcharts.Chart({
		chart: {
			renderTo: pie_chart_div_id,
			type: 'pie'
		},
		title: {
			text: 'Browser market shares at a specific website, 2010 (Pie chart with drill down)'
		},
		tooltip: {
			formatter: function() {
				return barChartTooltip(this);
			}
		},
		plotOptions: {
			pie: {
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					formatter: function() {
						return pie_chart_label_format(this);
					}
				}
			}
		},

		series: [{
			name: name,
			data: data_drill_pie//,
		}],
		exporting: {
			enabled: false
		}
	});


	$('#'+speedo_chart_div_id).highcharts({

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
		},

		series: [{
			name: 'Achievers',
			data: [55],
			tooltip: {
			}
		}],
		exporting: {
			enabled: false
		}
	});


});

<?php
if($report_type == "sla_kpi_rep_retention" || $report_type == 'sla_kpi_rep_overall_success' || $report_type == 'sla_kpi_rep_timely_success')
{
	?>
var pie_chart_data_label_format = "<b>dl_point_name<b><br>dl_point_value %";
	<?php
}
else
{
	?>
var pie_chart_data_label_format = "<b>dl_point_name<b><br>dl_point_value learners";
	<?php
}
?>
function pie_chart_label_format(obj)
{
	var pie_chart_data_label_frmat = pie_chart_data_label_format;
	pie_chart_data_label_frmat = pie_chart_data_label_frmat.replace("dl_point_name",obj.point.name);
	pie_chart_data_label_frmat = pie_chart_data_label_frmat.replace("dl_point_value",obj.y);

	return pie_chart_data_label_frmat;
}

function redirectPage(value)
{
	window.location.href="do.php?_action=sla_kpi_rep_achievers&report_type="+value;
}

function redirectPageForReport()
{
	//window.location.href="do.php?_action="+report_type+"&page_mode=generate_report&"+$('#report_criteria_form').serialize();
	if(report_type == "sla_kpi_rep_achievers")
	{
		window.location.href="do.php?_action=sla_kpi_generate_report&page_mode=generate_report&"+$('#report_criteria_form').serialize()+"&report_type="+report_type;
	}
	else if(report_type == "sla_kpi_rep_last_visit")
	{
		window.location.href="do.php?_action=sla_kpi_generate_report_last_visit&page_mode=generate_report&"+$('#report_criteria_form').serialize()+"&report_type="+report_type;
	}
	else if(report_type == "sla_kpi_rep_new_starts")
	{
		window.location.href="do.php?_action=sla_kpi_generate_report_new_starts&page_mode=generate_report&"+$('#report_criteria_form').serialize()+"&report_type="+report_type;
	}
	else if(report_type == "sla_kpi_rep_completions")
	{
		window.location.href="do.php?_action=sla_kpi_generate_report_completions&page_mode=generate_report&"+$('#report_criteria_form').serialize()+"&report_type="+report_type;
	}
	else if(report_type == "sla_kpi_rep_early_leavers")
	{
		window.location.href="do.php?_action=sla_kpi_generate_report_early_leavers&page_mode=generate_report&"+$('#report_criteria_form').serialize()+"&report_type="+report_type;
	}
	else if(report_type == "sla_kpi_rep_learners")
	{
		window.location.href="do.php?_action=sla_kpi_generate_report_learners&page_mode=generate_report&"+$('#report_criteria_form').serialize()+"&report_type="+report_type;
	}
	else if(report_type == "sla_kpi_rep_retention")
	{
		//alert($('#report_criteria_form').serialize());
		window.location.href="do.php?_action=sla_kpi_generate_report_retention&page_mode=generate_report&"+$('#report_criteria_form').serialize()+"&report_type="+report_type;
	}
	else if(report_type == "sla_kpi_rep_overall_success")
	{
		//alert($('#report_criteria_form').serialize());
		window.location.href="do.php?_action=sla_kpi_generate_report_overall_success&report=overall_success&page_mode=generate_report&"+$('#report_criteria_form').serialize()+"&report_type="+report_type;
	}
	else if(report_type == "sla_kpi_rep_timely_success")
	{
		//alert($('#report_criteria_form').serialize());
		window.location.href="do.php?_action=sla_kpi_generate_report_overall_success&report=timely_success&page_mode=generate_report&"+$('#report_criteria_form').serialize()+"&report_type="+report_type;
	}
	else if(report_type == "sla_kpi_rep_progression")
	{
		//alert($('#report_criteria_form').serialize());
		window.location.href="do.php?_action=sla_kpi_generate_report_progression&page_mode=generate_report&"+$('#report_criteria_form').serialize()+"&report_type="+report_type;
	}
	else if(report_type == "sla_kpi_rep_progression_l2tol3")
	{
		//alert($('#report_criteria_form').serialize());
		window.location.href="do.php?_action=sla_kpi_generate_report_progression&page_mode=generate_report&show_only=l2tol3&"+$('#report_criteria_form').serialize()+"&report_type="+report_type;
	}
}

function show_hide_graph_types(graph_type)
{
	if(data_exists !="false")
	{
		var div_line_graph = document.getElementById("container_line");
		var div_pie_graph = document.getElementById("container_pie");
		var div_bar_graph = document.getElementById("container");

		if(graph_type == "line")
		{
			$('#div_two_graphs').fadeOut();/*$(div_pie_graph).fadeOut();$(div_bar_graph).fadeOut();*/
		}
		else if(graph_type == "line_bar")
		{
			$('#div_two_graphs').fadeIn();
			$(div_bar_graph).fadeIn();
			$(div_pie_graph).fadeOut();
		}
		else if(graph_type == "line_bar_pie")
		{
			$('#div_two_graphs').fadeIn();
			$(div_bar_graph).fadeIn();
			$(div_pie_graph).fadeIn();
		}
	}
}

function save_filters()
{
	if(report_type == "sla_kpi_rep_progression" && document.getElementById('contract_year').value == "")
	{
		alert('You cannot select all contract years, please select only a specific year.');
	}
	else
	{
		$.ajax({
			type:"GET",
			data:"save_filters=save_filters&report_type="+report_type+"&"+$('#report_criteria_form').serialize(),
			url:"do.php?_action=ajax_sla_kpi_reports",
			dataType : 'json',
			beforeSend:function(data)
			{
				//alert('before send');
				$('#sp_saved_filters').hide();
				$('#sp_saving_filters').show();
			},
			success:function(response)
			{
				$('#sp_saving_filters').fadeOut();
				$('#sp_saved_filters').fadeIn();
			}
		});
	}
}
</script>

<style type="text/css">
	.lbl
	{
		font-family:'Arial'; color:#666666; font-size:13px;
	}
	.div_flds
	{
		margin: 5px 0 0 0;
	}


</style>
</head>

<body>
<div class="banner">
	<div class="Title">
		<?php echo $page_title;?>
	</div>

</div>

<?php
if(isset($_REQUEST['hide_dropdown']) && $_REQUEST['hide_dropdown'] == "hide_dropdown")
{
	$dropdown_display = "none";
}
else
{
	$dropdown_display = "block";
}
?>
<div style="margin:0 0 0 7px; display: <?php echo $dropdown_display;?>;">
	Select :
	<select name="report_type" id="report_type" onchange="redirectPage(this.value)" >
		<!--<option value="sla_kpi_rep_progression_l2tol3" <?php if($report_type == "sla_kpi_rep_progression_l2tol3"){echo 'selected';}?>>Onward progression – L2 to L3</option>-->
		<option value="sla_kpi_rep_progression" <?php if($report_type == "sla_kpi_rep_progression"){echo 'selected';}?>>Onward progression – L2, L3, L4</option>
		<option value="sla_kpi_rep_achievers" <?php if($report_type == "sla_kpi_rep_achievers"){echo 'selected';}?>>Progress through qualification, achieved to date</option>
		<option value="sla_kpi_rep_retention" <?php if($report_type == "sla_kpi_rep_retention"){echo 'selected';}?>>Retention Rates over time</option>
		<option value="sla_kpi_rep_overall_success" <?php if($report_type == "sla_kpi_rep_overall_success"){echo 'selected';}?>>Overall Success Rates</option>
		<option value="sla_kpi_rep_timely_success" <?php if($report_type == "sla_kpi_rep_timely_success"){echo 'selected';}?>>Timely Success Rates</option>
		<option value="sla_kpi_rep_last_visit" <?php if($report_type == "sla_kpi_rep_last_visit"){echo 'selected';}?>>Weeks since last visit for each learner</option>
		<option value="sla_kpi_rep_new_starts" <?php if($report_type == "sla_kpi_rep_new_starts"){echo 'selected';}?>>Number of starts in period and over time</option>
		<option value="sla_kpi_rep_completions" <?php if($report_type == "sla_kpi_rep_completions"){echo 'selected';}?>>Number of completions in period and over time</option>
		<option value="sla_kpi_rep_early_leavers" <?php if($report_type == "sla_kpi_rep_early_leavers"){echo 'selected';}?>>Number of early leavers in period and over time</option>
		<option value="sla_kpi_rep_learners" <?php if($report_type == "sla_kpi_rep_learners"){echo 'selected';}?>>Number of Learners broken down by criterias</option>
	</select>
</div>

<br style="clear: both;"/><br style="clear: both;"/>


<form name="report_criteria_form" id="report_criteria_form">

<!-- Filter div -->
<div id="div_filters" style="width: 1218px; border: 1px solid #ABABAB; border-radius: 7px 7px 7px 7px; min-height: 260px; margin: 0 0 0 7px; padding: 0 0 0 10px;">


<div style="font-family:Arial; margin: 6px 0 20px;" id="u262">
	<span style=" font-family:'Arial'; color:#333333; font-size:19px;">Change Graph</span>
</div>


<table style="width: 1210px;">
<tr>
	<td>
		<div style="margin: 0 0 5px;"><label class="lbl">Graph Type</label></div>

		<select id="graph_type" name="graph_type" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none" onchange="show_hide_graph_types(this.value)">
			<option value="line" <?php if($filter_arr->graph_type=='line'){echo 'selected';} ?>>Line</option>
			<option value="line_bar" <?php if($filter_arr->graph_type=='line_bar'){echo 'selected';} ?>>Line + Bar</option>
			<option value="line_bar_pie" <?php if($filter_arr->graph_type=='line_bar_pie'){echo 'selected';} ?>>Line + Bar + Pie</option>
		</select>
	</td>


	<?php
	if($report_type == 'sla_kpi_rep_new_starts' || $report_type == 'sla_kpi_rep_completions' || $report_type == 'sla_kpi_rep_early_leavers')
	{
		?>
		<td>
			<div class="div_flds">
				<div style="margin: 0 0 5px;"><label class="lbl">Drilldown by</label></div>

				<select id="drill_down_by" name="drill_down_by" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">

					<option value="quarter" <?php if($filter_arr->drill_down_by=="quarter"){echo 'selected';}?>>Quarter</option>
					<option value="month" <?php if($filter_arr->drill_down_by=="month"){echo 'selected';}?>>Month</option>
					<option value="week" <?php if($filter_arr->drill_down_by=="week"){echo 'selected';}?>>Week</option>
					<option value="employer" <?php if($filter_arr->drill_down_by=="employer"){echo 'selected';}?>>Employer</option>
					<option value="training_provider" <?php if($filter_arr->drill_down_by=="training_provider"){echo 'selected';}?>>Training Provider</option>
					<option value="contract" <?php if($filter_arr->drill_down_by=="contract"){echo 'selected';}?>>Contract</option>
					<option value="assessor" <?php if($filter_arr->drill_down_by=="assessor"){echo 'selected';}?>>Assessor</option>

				</select>
			</div>
		</td>
		<?php
	}

	if($report_type == 'sla_kpi_rep_achievers')
	{
		?>
		<td>
			<div class="div_flds">
				<div style="margin: 0 0 5px;"><label class="lbl">Drilldown by</label></div>

				<select id="drill_down_by" name="drill_down_by" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">

					<option value="employer" <?php if($filter_arr->drill_down_by=="employer"){echo 'selected';}?>>Employer</option>
					<option value="training_provider" <?php if($filter_arr->drill_down_by=="training_provider"){echo 'selected';}?>>Training Provider</option>
					<option value="contract" <?php if($filter_arr->drill_down_by=="contract"){echo 'selected';}?>>Contract</option>
					<option value="assessor" <?php if($filter_arr->drill_down_by=="assessor"){echo 'selected';}?>>Assessor</option>

				</select>
			</div>
		</td>
		<?php
	}

	if($report_type == 'sla_kpi_rep_learners')
	{
		?>
		<td>
			<div class="div_flds">
				<div style="margin: 0 0 5px;"><label class="lbl">Drilldown by</label></div>

				<select id="drill_down_by" name="drill_down_by" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">

					<option value="quarter" <?php if($filter_arr->drill_down_by=="quarter"){echo 'selected';}?>>Quarter</option>
					<option value="month" <?php if($filter_arr->drill_down_by=="month"){echo 'selected';}?>>Month</option>
					<option value="week" <?php if($filter_arr->drill_down_by=="week"){echo 'selected';}?>>Week</option>
					<option value="employer" <?php if($filter_arr->drill_down_by=="employer"){echo 'selected';}?>>Employer</option>
					<option value="training_provider" <?php if($filter_arr->drill_down_by=="training_provider"){echo 'selected';}?>>Training Provider</option>
					<option value="contract" <?php if($filter_arr->drill_down_by=="contract"){echo 'selected';}?>>Contract</option>
					<option value="assessor" <?php if($filter_arr->drill_down_by=="assessor"){echo 'selected';}?>>Assessor</option>

					<option value="age_range" <?php if($filter_arr->drill_down_by=="age_range"){echo 'selected';}?>>Age Range</option>
					<option value="course" <?php if($filter_arr->drill_down_by=="course"){echo 'selected';}?>>Course</option>
					<option value="disability" <?php if($filter_arr->drill_down_by=="disability"){echo 'selected';}?>>Disability</option>
					<option value="ethnicity" <?php if($filter_arr->drill_down_by=="ethnicity"){echo 'selected';}?>>Ethnicity</option>
					<option value="gender" <?php if($filter_arr->drill_down_by=="gender"){echo 'selected';}?>>Gender</option>
					<option value="tutor" <?php if($filter_arr->drill_down_by=="tutor"){echo 'selected';}?>>Group tutor</option>
					<option value="learning_difficulty" <?php if($filter_arr->drill_down_by=="learning_difficulty"){echo 'selected';}?>>Learning difficulty</option>
					<option value="progress" <?php if($filter_arr->drill_down_by=="progress"){echo 'selected';}?>>Progress</option>
					<option value="mainarea" <?php if($filter_arr->drill_down_by=="mainarea"){echo 'selected';}?>>Qualification Subject Sector Area</option>
					<option value="subarea" <?php if($filter_arr->drill_down_by=="subarea"){echo 'selected';}?>>Qualification Subject Sector Subarea</option>
					<option value="record_status" <?php if($filter_arr->drill_down_by=="record_status"){echo 'selected';}?>>Record status</option>
					<option value="verifier" <?php if($filter_arr->drill_down_by=="verifier"){echo 'selected';}?>>Verifier</option>
					<option value="work_experience_coordinator" <?php if($filter_arr->drill_down_by=="work_experience_coordinator"){echo 'selected';}?>>Work Experience Coordinator</option>
					<option value="actual_work_experience" <?php if($filter_arr->drill_down_by=="actual_work_experience"){echo 'selected';}?>>Work Experience Days</option>
					<option value="work_experience_band_10" <?php if($filter_arr->drill_down_by=="work_experience_band_10"){echo 'selected';}?>>Work Experience Visits 10 Days Band</option>
				</select>
			</div>
		</td>
		<?php
	}

	if($report_type == 'sla_kpi_rep_retention')
	{
		?>
		<td>
			<div class="div_flds">
				<div style="margin: 0 0 5px;"><label class="lbl">Drilldown by</label></div>

				<select id="drill_down_by" name="drill_down_by" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">
					<option value="area_of_learning" <?php if($filter_arr->drill_down_by=="area_of_learning"){echo 'selected';}?>>Area of Learning</option>
					<option value="employer" <?php if($filter_arr->drill_down_by=="employer"){echo 'selected';}?>>Employer</option>
					<option value="training_provider" <?php if($filter_arr->drill_down_by=="training_provider"){echo 'selected';}?>>Training Provider</option>
					<option value="contract" <?php if($filter_arr->drill_down_by=="contract"){echo 'selected';}?>>Contract</option>
					<option value="assessor" <?php if($filter_arr->drill_down_by=="assessor"){echo 'selected';}?>>Assessor</option>
					<option value="course" <?php if($filter_arr->drill_down_by=="course"){echo 'selected';}?>>Course</option>
					<option value="gender" <?php if($filter_arr->drill_down_by=="gender"){echo 'selected';}?>>Gender</option>
					<option value="ethnicity" <?php if($filter_arr->drill_down_by=="ethnicity"){echo 'selected';}?>>Ethnicity</option>
					<option value="frameworks" <?php if($filter_arr->drill_down_by=="frameworks"){echo 'selected';}?>>Frameworks</option>
				</select>
			</div>
		</td>
		<?php
	}


	if($report_type == 'sla_kpi_rep_overall_success' || $report_type == 'sla_kpi_rep_timely_success')
	{
		?>
		<td>
			<div class="div_flds">
				<div style="margin: 0 0 5px;"><label class="lbl">Drilldown by</label></div>

				<select id="drill_down_by" name="drill_down_by" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">
					<option value="age_band" <?php if($filter_arr->drill_down_by=="age_band"){echo 'selected';}?>>Age band</option>
					<option value="employer" <?php if($filter_arr->drill_down_by=="employer"){echo 'selected';}?>>Employer</option>
					<option value="training_provider" <?php if($filter_arr->drill_down_by=="training_provider"){echo 'selected';}?>>Training Provider</option>
					<option value="contract" <?php if($filter_arr->drill_down_by=="contract"){echo 'selected';}?>>Contract</option>
					<option value="assessor" <?php if($filter_arr->drill_down_by=="assessor"){echo 'selected';}?>>Assessor</option>
					<option value="programme_type" <?php if($filter_arr->drill_down_by=="programme_type"){echo 'selected';}?>>Programme Type</option>
					<option value="ssa" <?php if($filter_arr->drill_down_by=="ssa"){echo 'selected';}?>>Sector Subject Area</option>
					<option value="ethnicity" <?php if($filter_arr->drill_down_by=="ethnicity"){echo 'selected';}?>>Ethnicity</option>
				</select>
			</div>
		</td>
		<?php
	}

	if($report_type == 'sla_kpi_rep_progression' || $report_type == "sla_kpi_rep_progression_l2tol3")
	{
		?>
		<td>
			<div class="div_flds">
				<div style="margin: 0 0 5px;"><label class="lbl">Drilldown by</label></div>

				<select id="drill_down_by" name="drill_down_by" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">
					<option value="gender" <?php if($filter_arr->drill_down_by=="gender"){echo 'selected';}?>>Gender</option>
					<option value="employer" <?php if($filter_arr->drill_down_by=="employer"){echo 'selected';}?>>Employer</option>
					<option value="training_provider" <?php if($filter_arr->drill_down_by=="training_provider"){echo 'selected';}?>>Training Provider</option>
					<option value="contract" <?php if($filter_arr->drill_down_by=="contract"){echo 'selected';}?>>Contract</option>
					<option value="assessor" <?php if($filter_arr->drill_down_by=="assessor"){echo 'selected';}?>>Assessor</option>
					<option value="course" <?php if($filter_arr->drill_down_by=="course"){echo 'selected';}?>>Course</option>
					<option value="ethnicity" <?php if($filter_arr->drill_down_by=="ethnicity"){echo 'selected';}?>>Ethnicity</option>
					<option value="frameworks" <?php if($filter_arr->drill_down_by=="frameworks"){echo 'selected';}?>>Frameworks</option>
					<option value="submission" <?php if($filter_arr->drill_down_by=="submission"){echo 'selected';}?>>Submission</option>
					<option value="contract_year" <?php if($filter_arr->drill_down_by=="contract_year"){echo 'selected';}?>>Contract year</option>
				</select>
			</div>
		</td>
		<?php
	}
	?>
</tr>

<tr>

	<?php
	if($report_type == 'sla_kpi_rep_last_visit' || $report_type == 'sla_kpi_rep_new_starts' || $report_type == 'sla_kpi_rep_completions' || $report_type == 'sla_kpi_rep_early_leavers' || $report_type == "sla_kpi_rep_achievers" || $report_type == 'sla_kpi_rep_learners' || $report_type == 'sla_kpi_rep_retention' || $report_type == 'sla_kpi_rep_overall_success' || $report_type == 'sla_kpi_rep_timely_success' || $report_type == 'sla_kpi_rep_progression' || $report_type == "sla_kpi_rep_progression_l2tol3")
	{
		?>
		<td>
			<div class="div_flds">
				<div style="margin: 0 0 5px;"><label class="lbl">Assessor</label></div>

				<select id="assessor" name="assessor" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">
					<option value="">All</option>
					<?php
					foreach($assessor_arr as $assessor_dtls)
					{
						if($report_type == 'sla_kpi_rep_overall_success' || $report_type == 'sla_kpi_rep_timely_success')
						{
							if($filter_arr->assessor == $assessor_dtls['assessor_name'])
							{
								$selected="selected";
							}
							else
							{
								$selected="";
							}
							echo '<option value="'.$assessor_dtls['assessor_name'].'" '.$selected.'>'.$assessor_dtls['assessor_name'].'</option>';
						}
						else
						{
							if($filter_arr->assessor == $assessor_dtls['id'])
							{
								$selected="selected";
							}
							else
							{
								$selected="";
							}
							echo '<option value="'.$assessor_dtls['id'].'" '.$selected.'>'.$assessor_dtls['assessor_name'].'</option>';
						}
					}
					?>
				</select>
			</div>
		</td>
		<?php
	}
	if($report_type == 'sla_kpi_rep_last_visit' || $report_type == 'sla_kpi_rep_new_starts' || $report_type == 'sla_kpi_rep_completions' || $report_type == 'sla_kpi_rep_early_leavers' || $report_type == "sla_kpi_rep_achievers" || $report_type == 'sla_kpi_rep_learners' || $report_type == 'sla_kpi_rep_retention' || $report_type == 'sla_kpi_rep_overall_success' || $report_type == 'sla_kpi_rep_timely_success' || $report_type == 'sla_kpi_rep_progression' || $report_type == "sla_kpi_rep_progression_l2tol3")
	{
		?>
		<td>
			<div class="div_flds">
				<div style="margin: 0 0 5px;"><label class="lbl">Contract</label></div>

				<select id="contract" name="contract" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">
					<option value="">All</option>
					<?php
					foreach($contract_arr as $contract_dtls)
					{
						if($filter_arr->contract == $contract_dtls['id'])
						{
							$selected="selected";
						}
						else
						{
							$selected="";
						}
						echo '<option value="'.$contract_dtls['id'].'" '.$selected.'>'.$contract_dtls['title'].'</option>';
					}
					?>
				</select>
			</div>
		</td>
		<?php
	}

	if($report_type == 'sla_kpi_rep_new_starts' || $report_type == 'sla_kpi_rep_completions' || $report_type == "sla_kpi_rep_achievers" || $report_type == 'sla_kpi_rep_early_leavers' || $report_type == 'sla_kpi_rep_last_visit' || $report_type == 'sla_kpi_rep_learners' || $report_type == 'sla_kpi_rep_retention' || $report_type == 'sla_kpi_rep_overall_success' || $report_type == 'sla_kpi_rep_timely_success' || $report_type == 'sla_kpi_rep_progression' || $report_type == "sla_kpi_rep_progression_l2tol3")
	{
		?>
		<td>
			<div class="div_flds">
				<div style="margin: 0 0 5px;"><label class="lbl">Employer</label></div>

				<select id="employer" name="employer" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">
					<option value="">All</option>
					<?php
					foreach($employer_arr as $employer_dtls)
					{
						if($filter_arr->employer == $employer_dtls['id'])
						{
							$selected="selected";
						}
						else
						{
							$selected="";
						}
						echo '<option value="'.$employer_dtls['id'].'" '.$selected.'>'.$employer_dtls['employer_name'].'</option>';
					}
					?>
				</select>
			</div>
		</td>
		<?php
	}

	if($report_type == 'sla_kpi_rep_new_starts' || $report_type == 'sla_kpi_rep_completions' || $report_type == 'sla_kpi_rep_early_leavers' || $report_type == "sla_kpi_rep_achievers" || $report_type == 'sla_kpi_rep_last_visit' || $report_type == 'sla_kpi_rep_learners' || $report_type == 'sla_kpi_rep_retention' || $report_type == 'sla_kpi_rep_overall_success' || $report_type == 'sla_kpi_rep_timely_success' || $report_type == 'sla_kpi_rep_progression' || $report_type == "sla_kpi_rep_progression_l2tol3")
	{
		?>
		<td>
			<div class="div_flds">
				<div style="margin: 0 0 5px;"><label class="lbl">Training Provider</label></div>

				<select id="training_provider" name="training_provider" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">
					<option value="">All</option>
					<?php
					foreach($training_provider_arr as $training_provider_dtls)
					{
						if($filter_arr->training_provider == $training_provider_dtls['id'])
						{
							$selected="selected";
						}
						else
						{
							$selected="";
						}
						echo '<option value="'.$training_provider_dtls['id'].'" '.$selected.'>'.$training_provider_dtls['training_provider_name'].'</option>';
					}
					?>
				</select>
			</div>
		</td>
		<?php
	}
	?>
</tr>

<?php
if($report_type == 'sla_kpi_rep_learners')
{
	?>
<tr>
	<td>
		<div class="div_flds">
			<div style="margin: 0 0 5px;"><label class="lbl">Progress</label></div>

			<select id="progress" name="progress" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">
				<option value="0" <?php if($filter_arr->progress=="0"){echo 'selected';}?>>Show All</option>
				<option value="1" <?php if($filter_arr->progress=="1"){echo 'selected';}?>>On track</option>
				<option value="2" <?php if($filter_arr->progress=="2"){echo 'selected';}?>>Behind</option>
			</select>
		</div>
	</td>


	<td>
		<div class="div_flds">
			<div style="margin: 0 0 5px;"><label class="lbl">Record status</label></div>

			<select id="record_status" name="record_status" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">
				<option value="">Show All</option>
				<option value="1" <?php if($filter_arr->record_status=="1"){echo 'selected';}?>>
					1. The learner is continuing or intending to continue
				</option>
				<option value="2" <?php if($filter_arr->record_status=="2"){echo 'selected';}?>>
					2. The learner has completed the learning activity
				</option>
				<option value="3" <?php if($filter_arr->record_status=="3"){echo 'selected';}?>>
					3. The learner has withdrawn from learning
				</option>
				<option value="4" <?php if($filter_arr->record_status=="4"){echo 'selected';}?>>
					4. The learner has transferred to a new learning provider
				</option>
				<option value="5" <?php if($filter_arr->record_status=="5"){echo 'selected';}?>>
					5. Changes in learning within the same programme
				</option>
				<option value="6" <?php if($filter_arr->record_status=="6"){echo 'selected';}?>>
					6. Learner has temporarily withdrawn
				</option>
			</select>
		</div>
	</td>

	<td>
		<div class="div_flds">
			<div style="margin: 0 0 5px;"><label class="lbl">Programme</label></div>

			<select id="programme" name="programme" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">
				<option value="">All</option>
				<?php
				foreach($programme_arr as $programme_dtls)
				{
					if($filter_arr->programme == $programme_dtls['code'])
					{
						$selected="selected";
					}
					else
					{
						$selected="";
					}
					echo '<option value="'.$programme_dtls['code'].'" '.$selected.'>'.$programme_dtls['description'].'</option>';
				}
				?>
			</select>
		</div>
	</td>

	<td>
		<div class="div_flds">
			<div style="margin: 0 0 5px;"><label class="lbl">Group</label></div>

			<select id="group" name="group" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">
				<option value="">All</option>
				<?php
				foreach($group_arr as $group_dtls)
				{
					if($filter_arr->group == $group_dtls['id'])
					{
						$selected="selected";
					}
					else
					{
						$selected="";
					}
					echo '<option value="'.$group_dtls['id'].'" '.$selected.'>'.$group_dtls['title'].'</option>';
				}
				?>
			</select>
		</div>
	</td>
</tr>

	<?php
}
if($report_type == 'sla_kpi_rep_learners' || $report_type == 'sla_kpi_rep_retention' || $report_type == 'sla_kpi_rep_progression' || $report_type == "sla_kpi_rep_progression_l2tol3")
{
	?>
<tr>
	<td>
		<div class="div_flds">
			<div style="margin: 0 0 5px;"><label class="lbl">Gender</label></div>

			<select id="gender" name="gender" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">
				<option value="">All</option>
				<?php
				foreach($gender_arr as $gender_dtls)
				{
					if($filter_arr->gender == $gender_dtls['gender'])
					{
						$selected="selected";
					}
					else
					{
						$selected="";
					}
					echo '<option value="'.$gender_dtls['gender'].'" '.$selected.'>'.$gender_dtls['gender'].'</option>';
				}
				?>
			</select>
		</div>
	</td>


	<td>
		<div class="div_flds">
			<div style="margin: 0 0 5px;"><label class="lbl">Course</label></div>

			<select id="course" name="course" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">
				<option value="">All</option>
				<?php
				foreach($course_arr as $course_dtls)
				{
					if($filter_arr->course == $course_dtls['id'])
					{
						$selected="selected";
					}
					else
					{
						$selected="";
					}
					echo '<option value="'.$course_dtls['id'].'" '.$selected.'>'.$course_dtls['title'].'</option>';
				}
				?>
			</select>
		</div>
	</td>

	<td>
		<div class="div_flds">
			<div style="margin: 0 0 5px;"><label class="lbl">Framework</label></div>

			<select id="framework" name="framework" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">
				<option value="">All</option>
				<?php
				foreach($framework_arr as $framework_dtls)
				{
					if($filter_arr->framework == $framework_dtls['id'])
					{
						$selected="selected";
					}
					else
					{
						$selected="";
					}
					echo '<option value="'.$framework_dtls['id'].'" '.$selected.'>'.$framework_dtls['title'].'</option>';
				}
				?>
			</select>
		</div>
	</td>
</tr>

	<?php
}

if($report_type == 'sla_kpi_rep_retention')
{
	?>
<tr>
	<td>
		<div class="div_flds">
			<div style="margin: 0 0 5px;"><label class="lbl">Valid</label></div>

			<select id="valid" name="valid" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">
				<option value="all" <?php if($filter_arr->valid=="all"){echo 'selected = "selected"';}?>>
					All
				</option>
				<option value="valid" <?php if($filter_arr->valid=="valid"){echo 'selected = "selected"';}?>>
					Valid
				</option>
				<option value="invalid" <?php if($filter_arr->valid=="invalid"){echo 'selected = "selected"';}?>>
					Invalid
				</option>
			</select>
		</div>
	</td>


	<td>
		<div class="div_flds">
			<div style="margin: 0 0 5px;"><label class="lbl">Active</label></div>

			<select id="active" name="active" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">
				<option value="all" <?php if($filter_arr->active=="all"){echo 'selected = "selected"';}?>>
					All
				</option>
				<option value="active" <?php if($filter_arr->active=="active"){echo 'selected = "selected"';}?>>
					Active
				</option>
				<option value="inactive" <?php if($filter_arr->active=="inactive"){echo 'selected = "selected"';}?>>
					Inactive
				</option>
			</select>
		</div>
	</td>

	<td>
		<div class="div_flds">
			<div style="margin: 0 0 5px;"><label class="lbl">Submission</label></div>

			<select id="submission" name="submission" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">

				<?php
				if($filter_arr->submission == "")
				{
					$filter_arr->submission = "W13";
				}

				foreach($submission_arr as $submission_dtls)
				{
					if($filter_arr->submission == $submission_dtls['description'])
					{
						$selected="selected";
					}
					else
					{
						$selected="";
					}
					echo '<option value="'.$submission_dtls['description'].'" '.$selected.'>'.$submission_dtls['description'].'</option>';
				}
				?>
			</select>
		</div>
	</td>

	<td>
		<div class="div_flds">
			<div style="margin: 0 0 5px;"><label class="lbl">Contract year</label></div>

			<select id="contract_year" name="contract_year" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">

				<?php
				foreach($contract_year_arr as $contract_year_dtls)
				{
					if($filter_arr->contract_year == $contract_year_dtls['contract_year'])
					{
						$selected="selected";
					}
					else
					{
						$selected="";
					}
					echo '<option value="'.$contract_year_dtls['contract_year'].'" '.$selected.'>'.$contract_year_dtls['contract_year'].'</option>';
				}
				?>
			</select>
		</div>
	</td>
</tr>

	<?php
}


if($report_type == 'sla_kpi_rep_overall_success' || $report_type == 'sla_kpi_rep_timely_success')
{
	if($report_type == 'sla_kpi_rep_overall_success')
	{
		$report = "overall_success";
	}
	else if($report_type == 'sla_kpi_rep_timely_success')
	{
		$report = "timely_success";
	}
	?>
<input type="hidden" name="report" value="<?php echo $report; ?>">
<tr>
	<td>
		<div class="div_flds">
			<div style="margin: 0 0 5px;"><label class="lbl">Age band</label></div>

			<select id="age_band" name="age_band" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">
				<option value="" <?php if($filter_arr->age_band==""){echo 'selected = "selected"';}?>>All</option>
				<option value="16-18" <?php if($filter_arr->age_band=="16-18"){echo 'selected = "selected"';}?>>16-18</option>
				<option value="19-24" <?php if($filter_arr->age_band=="19-24"){echo 'selected = "selected"';}?>>19-24</option>
				<option value="25+" <?php if($filter_arr->age_band=="25+"){echo 'selected = "selected"';}?>>25+</option>
				<option value="Unknown" <?php if($filter_arr->age_band=="Unknown"){echo 'selected = "selected"';}?>>Unknown</option>
			</select>
		</div>
	</td>

	<td>
		<div class="div_flds">
			<div style="margin: 0 0 5px;"><label class="lbl">Program type</label></div>

			<select id="programme_type" name="programme_type" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">
				<option value="" <?php if($filter_arr->programme_type==""){echo 'selected = "selected"';}?>>All</option>
				<option value="Apprenticeship" <?php if($filter_arr->programme_type=="Apprenticeship"){echo 'selected = "selected"';}?>>Apprenticeship</option>
				<option value="Workplace" <?php if($filter_arr->programme_type=="Workplace"){echo 'selected = "selected"';}?>>Workplace</option>
				<option value="Classroom" <?php if($filter_arr->programme_type=="Classroom"){echo 'selected = "selected"';}?>>Classroom</option>
				<option value="Unknown" <?php if($filter_arr->programme_type=="Unknown"){echo 'selected = "selected"';}?>>Unknown</option>
			</select>
		</div>
	</td>

	<td>
		<div class="div_flds">
			<div style="margin: 0 0 5px;"><label class="lbl">Sector Subject Area (SSA) :</label></div>

			<select id="ssa" name="ssa" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">
				<option value="">All</option>
				<?php
				foreach($ssa_tier2_arr as $ssa_tier2_dtls)
				{
					if($filter_arr->ssa == $ssa_tier2_dtls['code_and_title'])
					{
						$selected="selected";
					}
					else
					{
						$selected="";
					}
					echo '<option value="'.$ssa_tier2_dtls['code_and_title'].'" '.$selected.'>'.$ssa_tier2_dtls['code_and_title'].'</option>';
				}
				?>
			</select>
		</div>
	</td>

	<td>
		<div class="div_flds">
			<div style="margin: 0 0 5px;"><label class="lbl">Ethnicity :</label></div>

			<select id="ethnicity" name="ethnicity" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">
				<option value="">All</option>
				<?php
				foreach($ethnicity_arr as $ethnicity_dtls)
				{
					if($filter_arr->ethnicity == $ethnicity_dtls['description'])
					{
						$selected="selected";
					}
					else
					{
						$selected="";
					}
					echo '<option value="'.$ethnicity_dtls['description'].'" '.$selected.'>'.$ethnicity_dtls['description'].'</option>';
				}
				?>
			</select>
		</div>
	</td>
</tr>
	<?php
}

if($report_type == 'sla_kpi_rep_progression' || $report_type == "sla_kpi_rep_progression_l2tol3")
{
	?>
<tr>
	<td>
		<div class="div_flds">
			<div style="margin: 0 0 5px;"><label class="lbl">Ethnicity :</label></div>

			<select id="ethnicity" name="ethnicity" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">
				<option value="">All</option>
				<?php
				foreach($ethnicity_201112_arr as $ethnicity_dtls)
				{
					if($filter_arr->ethnicity == $ethnicity_dtls['ethnicity_id'])
					{
						$selected="selected";
					}
					else
					{
						$selected="";
					}
					echo '<option value="'.$ethnicity_dtls['ethnicity_id'].'" '.$selected.'>'.$ethnicity_dtls['description'].'</option>';
				}
				?>
			</select>
		</div>
	</td>

	<td>
		<div class="div_flds">
			<div style="margin: 0 0 5px;"><label class="lbl">Submission</label></div>

			<select id="submission" name="submission" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">

				<option value="">All</option>
				<?php
				/*if($filter_arr->submission == "")
								{
									$filter_arr->submission = "W13";
								}*/

				foreach($submission_arr as $submission_dtls)
				{
					if($filter_arr->submission == $submission_dtls['description'])
					{
						$selected="selected";
					}
					else
					{
						$selected="";
					}
					echo '<option value="'.$submission_dtls['description'].'" '.$selected.'>'.$submission_dtls['description'].'</option>';
				}
				?>
			</select>
		</div>
	</td>

	<td>
		<div class="div_flds">
			<div style="margin: 0 0 5px;"><label class="lbl">Contract year</label></div>

			<select id="contract_year" name="contract_year" style="width:250px; text-align: left ; font-family:'Arial'; font-size: 13px; color:#000000; font-style:normal; font-weight:normal; text-decoration:none">

				<option value="">All</option>
				<?php
				foreach($contract_year_arr as $contract_year_dtls)
				{
					if($filter_arr->contract_year == $contract_year_dtls['contract_year'])
					{
						$selected="selected";
					}
					else
					{
						$selected="";
					}
					echo '<option value="'.$contract_year_dtls['contract_year'].'" '.$selected.'>'.$contract_year_dtls['contract_year'].'</option>';
				}
				?>
			</select>
		</div>
	</td>
</tr>
	<?php
}



if($report_type == 'sla_kpi_rep_learners')
{
	?>
<tr><td colspan="8">&nbsp;</td></tr>

<tr>
	<td colspan="8">
		<table>
			<tr>
				<td><b>Start date</b></td>
				<td>
					<label class="lbl">From </label>

					<input id="from_date" name="from_date" class="DateBox" type="text" onfocus="if(window.from_date_onfocus){window.from_date_onfocus(this)}" onblur="if(!this.validate()){return false;};if(window.from_date_onblur){window.from_date_onblur(this)}" onchange="if(window.from_date_onchange){window.from_date_onchange(this)}" maxlength="10" size="10" value="<?php echo empty($_REQUEST['from_date'])? $filter_arr->from_date : $_REQUEST['from_date']?>" style="width:66px;">

					<a id="from_date_anchor" onclick="window.calPop.select(this.previousSibling.previousSibling, this.id, 'dd-MM-yyyy'); return false;" href="#">
						<img height="15" border="0" width="20" title="Show calendar" alt="Show calendar" style="vertical-align:text-bottom" src="/images/calendar-icon.gif">
					</a>

					&nbsp;&nbsp;
					<label class="lbl">To </label>

					<input id="to_date" name="to_date" class="DateBox" type="text" onfocus="if(window.to_date_onfocus){window.to_date_onfocus(this)}" onblur="if(!this.validate()){return false;};if(window.to_date_onblur){window.to_date_onblur(this)}" onchange="if(window.to_date_onchange){window.to_date_onchange(this)}" maxlength="10" size="10" value="<?php echo empty($_REQUEST['to_date'])? $filter_arr->to_date : $_REQUEST['to_date']?>" style="width:66px;">

					<a id="to_date_anchor" onclick="window.calPop.select(this.previousSibling.previousSibling, this.id, 'dd-MM-yyyy'); return false;" href="#">
						<img height="15" border="0" width="20" title="Show calendar" alt="Show calendar" style="vertical-align:text-bottom" src="/images/calendar-icon.gif">
					</a>
				</td>
			</tr>

			<tr>
				<td><b>Projected end date</b></td>
				<td>
					<label class="lbl">From </label>

					<input id="target_start_date" name="target_start_date" class="DateBox" type="text" onfocus="if(window.from_date_onfocus){window.from_date_onfocus(this)}" onblur="if(!this.validate()){return false;};if(window.from_date_onblur){window.from_date_onblur(this)}" onchange="if(window.from_date_onchange){window.from_date_onchange(this)}" maxlength="10" size="10" value="<?php echo empty($_REQUEST['target_start_date'])? $filter_arr->target_start_date : $_REQUEST['target_start_date']?>" style="width:66px;">

					<a id="from_date_anchor" onclick="window.calPop.select(this.previousSibling.previousSibling, this.id, 'dd-MM-yyyy'); return false;" href="#">
						<img height="15" border="0" width="20" title="Show calendar" alt="Show calendar" style="vertical-align:text-bottom" src="/images/calendar-icon.gif">
					</a>

					&nbsp;&nbsp;
					<label class="lbl">To </label>

					<input id="target_end_date" name="target_end_date" class="DateBox" type="text" onfocus="if(window.to_date_onfocus){window.to_date_onfocus(this)}" onblur="if(!this.validate()){return false;};if(window.to_date_onblur){window.to_date_onblur(this)}" onchange="if(window.to_date_onchange){window.to_date_onchange(this)}" maxlength="10" size="10" value="<?php echo empty($_REQUEST['target_end_date'])? $filter_arr->target_end_date : $_REQUEST['target_end_date']?>" style="width:66px;">

					<a id="to_date_anchor" onclick="window.calPop.select(this.previousSibling.previousSibling, this.id, 'dd-MM-yyyy'); return false;" href="#">
						<img height="15" border="0" width="20" title="Show calendar" alt="Show calendar" style="vertical-align:text-bottom" src="/images/calendar-icon.gif">
					</a>
				</td>
			</tr>

			<tr>
				<td><b>Closure Date</b></td>
				<td>
					<label class="lbl">From </label>

					<input id="closure_start_date" name="closure_start_date" class="DateBox" type="text" onfocus="if(window.from_date_onfocus){window.from_date_onfocus(this)}" onblur="if(!this.validate()){return false;};if(window.from_date_onblur){window.from_date_onblur(this)}" onchange="if(window.from_date_onchange){window.from_date_onchange(this)}" maxlength="10" size="10" value="<?php echo empty($_REQUEST['closure_start_date'])? $filter_arr->closure_start_date : $_REQUEST['closure_start_date']?>" style="width:66px;">

					<a id="from_date_anchor" onclick="window.calPop.select(this.previousSibling.previousSibling, this.id, 'dd-MM-yyyy'); return false;" href="#">
						<img height="15" border="0" width="20" title="Show calendar" alt="Show calendar" style="vertical-align:text-bottom" src="/images/calendar-icon.gif">
					</a>

					&nbsp;&nbsp;
					<label class="lbl">To </label>

					<input id="closure_end_date" name="closure_end_date" class="DateBox" type="text" onfocus="if(window.to_date_onfocus){window.to_date_onfocus(this)}" onblur="if(!this.validate()){return false;};if(window.to_date_onblur){window.to_date_onblur(this)}" onchange="if(window.to_date_onchange){window.to_date_onchange(this)}" maxlength="10" size="10" value="<?php echo empty($_REQUEST['closure_end_date'])? $filter_arr->closure_end_date : $_REQUEST['closure_end_date']?>" style="width:66px;">

					<a id="to_date_anchor" onclick="window.calPop.select(this.previousSibling.previousSibling, this.id, 'dd-MM-yyyy'); return false;" href="#">
						<img height="15" border="0" width="20" title="Show calendar" alt="Show calendar" style="vertical-align:text-bottom" src="/images/calendar-icon.gif">
					</a>
				</td>
			</tr>

		</table>
	</td>
</tr>
	<?php
}
?>
</table>
<?php
if($report_type == 'sla_kpi_rep_achievers')
{
	?>
<div class="div_flds">

	<label class="lbl">From </label>

	<input id="from_date" name="from_date" class="DateBox" type="text" onfocus="if(window.from_date_onfocus){window.from_date_onfocus(this)}" onblur="if(!this.validate()){return false;};if(window.from_date_onblur){window.from_date_onblur(this)}" onchange="if(window.from_date_onchange){window.from_date_onchange(this)}" maxlength="10" size="10" value="<?php echo empty($_REQUEST['from_date'])? $filter_arr->from_date : $_REQUEST['from_date']?>" style="width:66px;">

	<a id="from_date_anchor" onclick="window.calPop.select(this.previousSibling.previousSibling, this.id, 'dd-MM-yyyy'); return false;" href="#">
		<img height="15" border="0" width="20" title="Show calendar" alt="Show calendar" style="vertical-align:text-bottom" src="/images/calendar-icon.gif">
	</a>

	&nbsp;&nbsp;
	<label class="lbl">To </label>

	<input id="to_date" name="to_date" class="DateBox" type="text" onfocus="if(window.to_date_onfocus){window.to_date_onfocus(this)}" onblur="if(!this.validate()){return false;};if(window.to_date_onblur){window.to_date_onblur(this)}" onchange="if(window.to_date_onchange){window.to_date_onchange(this)}" maxlength="10" size="10" value="<?php echo empty($_REQUEST['to_date'])? $filter_arr->to_date : $_REQUEST['to_date']?>" style="width:66px;">

	<a id="to_date_anchor" onclick="window.calPop.select(this.previousSibling.previousSibling, this.id, 'dd-MM-yyyy'); return false;" href="#">
		<img height="15" border="0" width="20" title="Show calendar" alt="Show calendar" style="vertical-align:text-bottom" src="/images/calendar-icon.gif">
	</a>

</div>
	<?php
}

if($report_type == 'sla_kpi_rep_last_visit')
{
	?>
<div class="div_flds">
	<label class="lbl">Last review From Date </label>

	<input id="from_date" name="from_date" class="DateBox" type="text" onfocus="if(window.from_date_onfocus){window.from_date_onfocus(this)}" onblur="if(!this.validate()){return false;};if(window.from_date_onblur){window.from_date_onblur(this)}" onchange="if(window.from_date_onchange){window.from_date_onchange(this)}" maxlength="10" size="10" value="<?php echo empty($_REQUEST['from_date'])? $filter_arr->from_date : $_REQUEST['from_date']?>" style="width:66px;">

	<a id="from_date_anchor" onclick="window.calPop.select(this.previousSibling.previousSibling, this.id, 'dd-MM-yyyy'); return false;" href="#">
		<img height="15" border="0" width="20" title="Show calendar" alt="Show calendar" style="vertical-align:text-bottom" src="/images/calendar-icon.gif">
	</a>

	<br><br>

	<label class="lbl">Last review To Date </label>

	<input id="to_date" name="to_date" class="DateBox" type="text" onfocus="if(window.to_date_onfocus){window.to_date_onfocus(this)}" onblur="if(!this.validate()){return false;};if(window.to_date_onblur){window.to_date_onblur(this)}" onchange="if(window.to_date_onchange){window.to_date_onchange(this)}" maxlength="10" size="10" value="<?php echo empty($_REQUEST['to_date'])? $filter_arr->to_date : $_REQUEST['to_date']?>" style="width:66px;">

	<a id="to_date_anchor" onclick="window.calPop.select(this.previousSibling.previousSibling, this.id, 'dd-MM-yyyy'); return false;" href="#">
		<img height="15" border="0" width="20" title="Show calendar" alt="Show calendar" style="vertical-align:text-bottom" src="/images/calendar-icon.gif">
	</a>
</div>
	<?php
}

if($report_type == 'sla_kpi_rep_new_starts' || $report_type == 'sla_kpi_rep_completions' || $report_type == 'sla_kpi_rep_early_leavers')
{
	?>
<div class="div_flds">

	<label class="lbl">From </label>

	<input id="from_date" name="from_date" class="DateBox" type="text" onfocus="if(window.from_date_onfocus){window.from_date_onfocus(this)}" onblur="if(!this.validate()){return false;};if(window.from_date_onblur){window.from_date_onblur(this)}" onchange="if(window.from_date_onchange){window.from_date_onchange(this)}" maxlength="10" size="10" value="<?php echo empty($_REQUEST['from_date'])? $filter_arr->from_date : $_REQUEST['from_date']?>" style="width:66px;">

	<a id="from_date_anchor" onclick="window.calPop.select(this.previousSibling.previousSibling, this.id, 'dd-MM-yyyy'); return false;" href="#">
		<img height="15" border="0" width="20" title="Show calendar" alt="Show calendar" style="vertical-align:text-bottom" src="/images/calendar-icon.gif">
	</a>

	&nbsp;&nbsp;
	<label class="lbl">To </label>

	<input id="to_date" name="to_date" class="DateBox" type="text" onfocus="if(window.to_date_onfocus){window.to_date_onfocus(this)}" onblur="if(!this.validate()){return false;};if(window.to_date_onblur){window.to_date_onblur(this)}" onchange="if(window.to_date_onchange){window.to_date_onchange(this)}" maxlength="10" size="10" value="<?php echo empty($_REQUEST['to_date'])? $filter_arr->to_date : $_REQUEST['to_date']?>" style="width:66px;">

	<a id="to_date_anchor" onclick="window.calPop.select(this.previousSibling.previousSibling, this.id, 'dd-MM-yyyy'); return false;" href="#">
		<img height="15" border="0" width="20" title="Show calendar" alt="Show calendar" style="vertical-align:text-bottom" src="/images/calendar-icon.gif">
	</a>

</div>


	<?php
}
?>


<br>

<input type="button" id="go_button" name="go_button" value="Go" onclick="javascript:load_graphs()"/>
&nbsp;&nbsp;
<input type="button" name="btn_save_filters" id="btn_save_filters" value="Save Filters for Dashboard" onclick="javascript:save_filters()" />
&nbsp;&nbsp;
<a href="javascript:redirectPageForReport();">View report</a>

<br>

    <span id="sp_saving_filters" style="display: none;">
    &nbsp;&nbsp;
        <img src="/images/wait30.gif" />
        <b>Saving filter, please wait....</b>
    </span>

    <span id="sp_saved_filters" style="display: none; margin: 0 0 0 5px;">
        <br>
        <b>Filter has been saved !</b>
    </span>

    <span id="sp_loading_graphs">
    &nbsp;&nbsp;
        <img src="/images/wait30.gif" />
        <b>Updating graphs, please wait....</b>
    </span>

    <span style="width: 1210px; display: none; font-size: 14px;" id="error_div">
        <br>
        <font color="red">If you are not getting the proper search results, try to refine your search by changing your search criterias !</font>
        <br style="clear: both;"/><br style="clear: both;"/>
    </span>
</div>

<div style="clear: both"></div>

<br><br>

<div style="width: 1241px;" id="upper_div">
	<!-- Data table div -->
	<div id="div_data_table" style="left: 10px; width: 1233px; margin:0 0 0 7px; width: 912px; float: left;"></div>

	<!-- Speedo chart  -->
	<div id="container_speedo" style="width: 300px; height: 200px; float: left;"></div>
</div>

<br style="clear: both;"/><br style="clear: both;"/>

<div style="width: 1241px;">

	<div style="width: 930px; float: left;">
		<div style="position: absolute; left: 10px; width: 1230px;" id="u211">
			<!-- Line chart -->
			<div style="left:10px; top:10px; width:1230px; height:381px; overflow:visible; border: 1px solid #ABABAB; border-radius: 7px 7px 7px 7px;" id="container_line"></div>


			<div style="clear: both;"></div><br><br>


			<div id="div_two_graphs" style="float: left; width:1230px; margin: 40px 0 0; border: 1px solid #ABABAB; border-radius: 7px 7px 7px 7px; <?php if($page_mode == "generate_report" || $filter_arr->graph_type=='line'){echo 'display:none;';} ?>">

				<!-- Pie chart -->
				<div id="container_pie" style="width:1230px; height:381px;float:left; <?php if($filter_arr->graph_type=='line_bar'){echo 'display:none;';} ?>"></div>

				<!-- Bar chart -->
				<div id="container" style="width:1190px; height:381px;float:left; margin:20px; 0 0 0;clear: both; <?php if($filter_arr->graph_type=='line'){echo 'display:none;';} ?>"></div>
			</div>

		</div>
	</div>



</div>
</form>

<script type="text/javascript">
	//<![CDATA[
	var ele = document.getElementById("from_date");
	//ele.resetToDefault = function(){this.value = "24/06/2013"};
	ele.validate = function(){
		if(this.value && window.stringToDate && !window.stringToDate(this.value)){
			alert("Invalid calendar-date or invalid date-format. Please use the format dd-mm-yyyy.");
			this.value = "";
			this.focus();
			return false;
		}
		return true;
	}
	//]]>

	//<![CDATA[
	var ele = document.getElementById("to_date");
	//ele.resetToDefault = function(){this.value = "24/06/2013"};
	ele.validate = function(){
		if(this.value && window.stringToDate && !window.stringToDate(this.value)){
			alert("Invalid calendar-date or invalid date-format. Please use the format dd-mm-yyyy.");
			this.value = "";
			this.focus();
			return false;
		}
		return true;
	}
	//]]>

	<?php
	if($report_type == "sla_kpi_rep_learners")
	{
		?>
	//<![CDATA[
	var ele = document.getElementById("target_start_date");
	//ele.resetToDefault = function(){this.value = "24/06/2013"};
	ele.validate = function(){
		if(this.value && window.stringToDate && !window.stringToDate(this.value)){
			alert("Invalid calendar-date or invalid date-format. Please use the format dd-mm-yyyy.");
			this.value = "";
			this.focus();
			return false;
		}
		return true;
	}
	//]]>

	//<![CDATA[
	var ele = document.getElementById("target_end_date");
	//ele.resetToDefault = function(){this.value = "24/06/2013"};
	ele.validate = function(){
		if(this.value && window.stringToDate && !window.stringToDate(this.value)){
			alert("Invalid calendar-date or invalid date-format. Please use the format dd-mm-yyyy.");
			this.value = "";
			this.focus();
			return false;
		}
		return true;
	}
	//]]>

	//<![CDATA[
	var ele = document.getElementById("closure_start_date");
	//ele.resetToDefault = function(){this.value = "24/06/2013"};
	ele.validate = function(){
		if(this.value && window.stringToDate && !window.stringToDate(this.value)){
			alert("Invalid calendar-date or invalid date-format. Please use the format dd-mm-yyyy.");
			this.value = "";
			this.focus();
			return false;
		}
		return true;
	}
	//]]>

	//<![CDATA[
	var ele = document.getElementById("closure_end_date");
	//ele.resetToDefault = function(){this.value = "24/06/2013"};
	ele.validate = function(){
		if(this.value && window.stringToDate && !window.stringToDate(this.value)){
			alert("Invalid calendar-date or invalid date-format. Please use the format dd-mm-yyyy.");
			this.value = "";
			this.focus();
			return false;
		}
		return true;
	}
	//]]>

	//<![CDATA[
	var ele = document.getElementById("work_experience_start_date");
	//ele.resetToDefault = function(){this.value = "24/06/2013"};
	ele.validate = function(){
		if(this.value && window.stringToDate && !window.stringToDate(this.value)){
			alert("Invalid calendar-date or invalid date-format. Please use the format dd-mm-yyyy.");
			this.value = "";
			this.focus();
			return false;
		}
		return true;
	}
	//]]>

	//<![CDATA[
	var ele = document.getElementById("work_experience_end_date");
	//ele.resetToDefault = function(){this.value = "24/06/2013"};
	ele.validate = function(){
		if(this.value && window.stringToDate && !window.stringToDate(this.value)){
			alert("Invalid calendar-date or invalid date-format. Please use the format dd-mm-yyyy.");
			this.value = "";
			this.focus();
			return false;
		}
		return true;
	}
	//]]>
		<?php
	}
	?>
</script>
<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>
</body>
</html>