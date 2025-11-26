<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Dashboard</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<style type="text/css">

		div.main { width: 50%; height: 50%; float: left; }
		div.block
		{
			text-align: left;
			border-width: 1px;
			border-style: solid;
			border-color: #E3E3E3 #BFBFBF #BFBFBF #E3E3E3;
			padding: 8px!important;
			margin-bottom: 1.5em;
			word-wrap: break-word;
			width: 45%!important;
			/* To enable gradients in IE < 9 */
			zoom: 1;
			-moz-border-radius: 7px;
			-webkit-border-radius: 7px;
			border-radius: 7px;
			-moz-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			-webkit-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			/* http://www.colorzilla.com/gradient-editor/ */
			background: rgb(255,255,255); /* Old browsers */
			/* IE9 SVG, needs conditional override of 'filter' to 'none' */
			background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmZmZmZiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmNmY2ZjYiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
			background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(246,246,246,1) 100%); /* FF3.6+ */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,1)), color-stop(100%,rgba(246,246,246,1))); /* Chrome,Safari4+ */
			background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* Opera 11.10+ */
			background: -ms-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* IE10+ */
			background: linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* W3C */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f6f6f6',GradientType=0 ); /* IE6-8 */
		}
	</style>
	<script language="javascript" src="/js/highcharts2.js" type="text/javascript"></script>
	<script language="javascript" src="/js/highcharts-more.js" type="text/javascript"></script>
	<script src="/js/modules/exporting.js" type="text/javascript"></script>
	<script type="text/javascript">
	$(function () {
		$('#div1').highcharts({
			chart: {
				type: 'column'
			},
			title: {
				text: 'Volumes'
			},
			subtitle: {
				text: 'Source: Sunesis'
			},
			xAxis: {
				categories: [
					'Jan',
					'Feb',
					'Mar',
					'Apr',
					'May',
					'Jun',
					'Jul',
					'Aug',
					'Sep',
					'Oct',
					'Nov',
					'Dec'
				],
				crosshair: true
			},
			yAxis: {
				min: 0,
				title: {
					text: 'Learners'
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
				name: 'Actual',
                data: [<?php echo DAO::getSingleValue($link, "SELECT
CONCAT(SUM(CASE WHEN start_date >= '2014-01-01' AND start_date <= '2014-01-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN start_date >= '2014-02-01' AND start_date <= '2014-02-28' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN start_date >= '2014-03-01' AND start_date <= '2014-03-30' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN start_date >= '2014-04-01' AND start_date <= '2014-04-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN start_date >= '2014-05-01' AND start_date <= '2014-05-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN start_date >= '2014-06-01' AND start_date <= '2014-06-30' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN start_date >= '2014-07-01' AND start_date <= '2014-07-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN start_date >= '2014-08-01' AND start_date <= '2014-08-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN start_date >= '2014-09-01' AND start_date <= '2014-09-30' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN start_date >= '2014-10-01' AND start_date <= '2014-10-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN start_date >= '2014-11-01' AND start_date <= '2014-11-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN start_date >= '2014-12-01' AND start_date <= '2014-12-31' THEN 1 ELSE 0 END))
FROM tr
INNER JOIN courses_tr ON courses_tr.`tr_id` = tr.id
INNER JOIN courses ON courses.id = courses_tr.`course_id`
INNER JOIN frameworks ON frameworks.id = courses.framework_id;
") ?>]

			}, {
				name: 'Target',
				data: [<?php echo DAO::getSingleValue($link, "SELECT
CONCAT(SUM(`jan`), ',', SUM(`feb`), ',',SUM(`mar`), ',',SUM(`apr`), ',',SUM(`may`), ',',SUM(`jun`), ',',SUM(`jul`), ',',SUM(`aug`), ',',SUM(`sep`), ',',SUM(`oct`), ',',SUM(`nov`), ',',SUM(`dec`))
FROM forecast_learners") ?>]

			}]
		});
	});



    $(function () {
        $('#div2').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Level 2 Achievers'
            },
            subtitle: {
                text: 'Source: Sunesis'
            },
            xAxis: {
                categories: [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec'
                ],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Learners'
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
                name: 'Actual',
                data: [<?php echo DAO::getSingleValue($link, "SELECT
CONCAT(SUM(CASE WHEN closure_date >= '2014-01-01' AND closure_date <= '2014-01-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN closure_date >= '2014-02-01' AND closure_date <= '2014-02-28' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN closure_date >= '2014-03-01' AND closure_date <= '2014-03-30' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN closure_date >= '2014-04-01' AND closure_date <= '2014-04-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN closure_date >= '2014-05-01' AND closure_date <= '2014-05-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN closure_date >= '2014-06-01' AND closure_date <= '2014-06-30' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN closure_date >= '2014-07-01' AND closure_date <= '2014-07-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN closure_date >= '2014-08-01' AND closure_date <= '2014-08-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN closure_date >= '2014-09-01' AND closure_date <= '2014-09-30' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN closure_date >= '2014-10-01' AND closure_date <= '2014-10-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN closure_date >= '2014-11-01' AND closure_date <= '2014-11-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN closure_date >= '2014-12-01' AND closure_date <= '2014-12-31' THEN 1 ELSE 0 END))
FROM tr
INNER JOIN courses_tr ON courses_tr.`tr_id` = tr.id
INNER JOIN courses ON courses.id = courses_tr.`course_id`
INNER JOIN frameworks ON frameworks.id = courses.framework_id
WHERE status_code = 2 AND frameworks.`framework_type` = 3;") ?>]

            }, {
                name: 'Target',
                data: [<?php echo DAO::getSingleValue($link, "SELECT
CONCAT(SUM(CASE WHEN target_date >= '2014-01-01' AND target_date <= '2014-01-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN target_date >= '2014-02-01' AND target_date <= '2014-02-28' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN target_date >= '2014-03-01' AND target_date <= '2014-03-30' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN target_date >= '2014-04-01' AND target_date <= '2014-04-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN target_date >= '2014-05-01' AND target_date <= '2014-05-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN target_date >= '2014-06-01' AND target_date <= '2014-06-30' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN target_date >= '2014-07-01' AND target_date <= '2014-07-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN target_date >= '2014-08-01' AND target_date <= '2014-08-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN target_date >= '2014-09-01' AND target_date <= '2014-09-30' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN target_date >= '2014-10-01' AND target_date <= '2014-10-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN target_date >= '2014-11-01' AND target_date <= '2014-11-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN target_date >= '2014-12-01' AND target_date <= '2014-12-31' THEN 1 ELSE 0 END))
FROM tr
INNER JOIN courses_tr ON courses_tr.`tr_id` = tr.id
INNER JOIN courses ON courses.id = courses_tr.`course_id`
INNER JOIN frameworks ON frameworks.id = courses.framework_id
WHERE status_code = 2 AND frameworks.`framework_type` = 3;
") ?>]

            }]
        });
    });


    $(function () {
        $('#div3').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Level 3 Achievers'
            },
            subtitle: {
                text: 'Source: Sunesis'
            },
            xAxis: {
                categories: [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec'
                ],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Learners'
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
                name: 'Actual',
                data: [<?php echo DAO::getSingleValue($link, "SELECT
CONCAT(SUM(CASE WHEN closure_date >= '2014-01-01' AND closure_date <= '2014-01-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN closure_date >= '2014-02-01' AND closure_date <= '2014-02-28' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN closure_date >= '2014-03-01' AND closure_date <= '2014-03-30' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN closure_date >= '2014-04-01' AND closure_date <= '2014-04-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN closure_date >= '2014-05-01' AND closure_date <= '2014-05-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN closure_date >= '2014-06-01' AND closure_date <= '2014-06-30' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN closure_date >= '2014-07-01' AND closure_date <= '2014-07-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN closure_date >= '2014-08-01' AND closure_date <= '2014-08-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN closure_date >= '2014-09-01' AND closure_date <= '2014-09-30' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN closure_date >= '2014-10-01' AND closure_date <= '2014-10-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN closure_date >= '2014-11-01' AND closure_date <= '2014-11-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN closure_date >= '2014-12-01' AND closure_date <= '2014-12-31' THEN 1 ELSE 0 END))
FROM tr
INNER JOIN courses_tr ON courses_tr.`tr_id` = tr.id
INNER JOIN courses ON courses.id = courses_tr.`course_id`
INNER JOIN frameworks ON frameworks.id = courses.framework_id
WHERE status_code = 2 AND frameworks.`framework_type` = 2;") ?>]

            }, {
                name: 'Target',
                data: [<?php echo DAO::getSingleValue($link, "SELECT
CONCAT(SUM(CASE WHEN target_date >= '2014-01-01' AND target_date <= '2014-01-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN target_date >= '2014-02-01' AND target_date <= '2014-02-28' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN target_date >= '2014-03-01' AND target_date <= '2014-03-30' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN target_date >= '2014-04-01' AND target_date <= '2014-04-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN target_date >= '2014-05-01' AND target_date <= '2014-05-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN target_date >= '2014-06-01' AND target_date <= '2014-06-30' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN target_date >= '2014-07-01' AND target_date <= '2014-07-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN target_date >= '2014-08-01' AND target_date <= '2014-08-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN target_date >= '2014-09-01' AND target_date <= '2014-09-30' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN target_date >= '2014-10-01' AND target_date <= '2014-10-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN target_date >= '2014-11-01' AND target_date <= '2014-11-31' THEN 1 ELSE 0 END)
,','
,SUM(CASE WHEN target_date >= '2014-12-01' AND target_date <= '2014-12-31' THEN 1 ELSE 0 END))
FROM tr
INNER JOIN courses_tr ON courses_tr.`tr_id` = tr.id
INNER JOIN courses ON courses.id = courses_tr.`course_id`
INNER JOIN frameworks ON frameworks.id = courses.framework_id
WHERE status_code = 2 AND frameworks.`framework_type` = 2;
") ?>]

            }]
        });
    });


		$(function () {
			$('#div5').highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: 'Sunesis usage by browser'
				},
				tooltip: {
					pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: true,
							format: '<b>{point.name}</b>: {point.percentage:.1f} %',
							style: {
								color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
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
		});


	</script>


</head>

<body>
<div class="banner">
	<div class="Title">Dashboard</div>
	<div class="ButtonBar">
	</div>
	<div class="ActionIconBar">
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>




<div id="container" align="center" style="margin-top:50px;">
	<div id="div1" class="block" style="width: 50%; float: left; margin: 10px;">
		1
	</div>
	<div id="div2" class="block" style="width: 50%; float: left; margin: 10px;">
		2
	</div>
	<div id="div3" class="block" style="width: 50%; float: left; margin: 10px;">
		3
	</div>
	<div id="div5" class="block" style="width: 50%; float: left; margin: 10px;">
		5
	</div>
	<div id="div6" class="block" style="width: 50%; float: left; margin: 10px;">
		6
	</div>
</div>



</body>
</html>