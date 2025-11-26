<?php /* @var $vacancy RecVacancy*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<title>Vacancy</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>
	<script language="javascript" src="/js/highcharts2.js" type="text/javascript"></script>

	<script type="text/javascript">
		var phpVacancyID = '<?php echo $vacancy->id; ?>';
	</script>

	<script type="text/javascript" src="/js/rec_view_vacancy.js?n=<?php echo time(); ?>"></script>

	<style type="text/css">
		fieldset {
			border: 3px solid #B5B8C8;
			border-radius: 15px;
		}
		legend {
			font-size: 12px;
			color: #15428B;
			font-weight: 900;
		}
		.selectedMenuButton {
			border: 2px solid #0000ff;
		}
	</style>
</head>

<body onload='$(".loading-gif").hide();' class="yui-skin-sam">
<div class="banner">
	<div class="Title"><?php echo $vacancy->vacancy_title; ?></div>
	<div class="ButtonBar">
		<button onclick="window.location.replace('do.php?_action=rec_view_vacancies');">Close</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<div style = 'left : 50%;top : 50%;position : fixed;z-index : 101;width : 32px;height : 32px;margin-left : -16px;margin-top : -16px;'>
	<img src="/images/progress-animations/loading51.gif" alt="Loading" class="loading-gif" />
</div>

<p><?php include "rec_include_vacancy_navigator.php"; ?></p>

<?php if($top_message != ''){ echo '<table style="width: 100%; margin:10px;background-color:red; border:1px solid black;padding:3px; border-radius: 15px;"><tr valign="top"><td bgcolor="red" colspan="2" align="center" style="font-size: 200%;">' . $top_message . '</td></tr></table>'; }?>

<table>
	<tr valign="top">
		<td valign="top">
			<fieldset>
				<legend>Details</legend>
				<table cellpadding="6" cellspacing="1">
					<col width="100" />
					<col width="300" />
					<tr>
						<td class="fieldLabel">Vacancy Reference:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string)$vacancy->vacancy_reference); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel" valign="top">Number of Positions:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string)$vacancy->no_of_positions); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Vacancy Location:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string)$vacancy_location); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Framework:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT title FROM frameworks WHERE id = '{$vacancy->app_framework}';")); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Wage (�):</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string)$vacancy->wage); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Wage Type:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string)$vacancy->wage_type); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Wage Text:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string)$vacancy->wage_text); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Working Week:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string)$vacancy->working_week); ?></td>
					</tr>
					<tr>
						<td valign="top" class="fieldLabel">Short Description:</td>
						<td class="fieldValue"><?php echo ($vacancy->short_description); ?></td>
					</tr>
					<tr>
						<td valign="top" class="fieldLabel">Employer/Store:</td>
						<td class="fieldValue"><a href="do.php?_action=rec_read_employer&id=<?php echo $vacancy->employer_id; ?>"><?php echo ($vacancy->getEmployerName($link)); ?></a></td>
					</tr>
					<tr>
						<td valign="top" class="fieldLabel">Employer/Store Location:</td>
						<td class="fieldValue"><?php echo ($vacancy->getLocation($link)); ?></td>
					</tr>
					<?php if($_SESSION['user']->isAdmin()){?>
					<tr>
						<td colspan="2">
							<div class="chart-panel-body " id="graphApplicationsByStatus"></div>
						</td>
					</tr>
					<?php } ?>
				</table>
			</fieldset>
		</td>
		<td valign="top">
			<fieldset>
				<legend>Search Candidates</legend>
				<form id="frmSearchCandidates">
					<table cellspacing="1">
						<tr>
							<td class="fieldLabel">FirstName:</td>
							<td><input type="text" name="frmSearchCandidatesFirstName" id="frmSearchCandidatesFirstName" value="" size="30" /></td>
						</tr>
						<tr>
							<td class="fieldLabel">Surname:</td>
							<td><input type="text" name="frmSearchCandidatesSurname" id="frmSearchCandidatesSurname" value="" size="30" /></td>
						</tr>
						<tr>
							<td class="fieldLabel">Age:</td>
							<td><input type="text" name="frmSearchCandidatesAge" id="frmSearchCandidatesAge" value="" size="4" maxlength="4" onkeypress="return numbersonly(this, event);" /></td>
						</tr>
						<tr>
							<td class="fieldLabel">Available candidates within:</td>
							<td><input name="frmSearchCandidatesRadius" id="frmSearchCandidatesRadius" value="<?php echo $vacancy->radius; ?>" type="text" size="4" maxlength="4"onKeyPress="return numbersonly(this, event);" onblur="enterRadiusToReplaceBlank(this);"  /> miles<br></td>
						</tr>
						<tr>
							<td></td>
							<td><span class="button" onclick="btnFrmSearchCandidates();"> &nbsp; Search &raquo; &nbsp;</span></td>
						</tr>
					</table>
				</form>
			</fieldset>
			<fieldset>
				<legend>Matching Candidates</legend>
				<div id="tblSearchCandidatesResults">Fill your search criteria and click Search</div>
			</fieldset>
		</td>
	</tr>
</table>
<script type="text/javascript">
	var chart1;
	$.ajax({
		url:'do.php?_action=rec_view_vacancy&subaction=graphApplicationsByStatus&id=<?php echo $vacancy->id; ?>',
		type:"GET",
		async:true,
		beforeSend:function (data) {
			$("#graphApplicationsByStatus").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
		},
		success:function (response) {
			drawGraphApplicationsByStatus(JSON.parse(response));
		}
	});
	function drawGraphApplicationsByStatus(data){
		var options = {
			chart: {
				renderTo: 'graphApplicationsByStatus',
				type: 'column',
				options3d: {
					enabled: true,
					alpha: 15,
					beta: 8,
					depth: 50,
					viewDistance: 25
				},
				height: 350
			},
			title: {
				text: 'Applications By Status',
				x: -20 //center
			},
			subtitle: {
				text: '',
				x: -20
			},
			xAxis: {
				categories: []
			},
			yAxis: {
				title: {
					text: 'Applications'
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}]
			},
			tooltip: {
				formatter: function() {
					return '<b>'+ this.series.name +'</b><br/>'+
						this.x +': '+ this.y;
				}
			},
			plotOptions: {
				column: {
					dataLabels: {
						enabled: true
					}
				}
			},

			series: [{
				type: 'column',
				name: 'Applications By Status'
			}]
		}
		options.xAxis.categories = data[0]['data'];
		options.series[0] = data[1];
		if (chart1!==undefined) chart1.destroy();
		chart1 = new Highcharts.Chart(options);
	}
	$(function(){
		$('#btnMatching').attr("class","selectedMenuButton");
	});
	function enterRadiusToReplaceBlank(ele)
	{
		ele.value=ele.value==''?'5':ele.value;
	}
</script>
</body>
</html>