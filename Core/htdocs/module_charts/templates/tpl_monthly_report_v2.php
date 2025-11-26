<?php /* @var $view VoltView */ ?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis - Demographics</title>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
	<link rel="stylesheet" href="module_charts/assets/styles.css">
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->


</head>
<body class="hold-transition skin-blue sidebar-mini">

<div class="content-wrapper" style="background-color: white;">
	<section class="content-header">

	</section>
	<form name="frmFilters" id="frmFilters" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
		<input type="hidden" name="_action" value="monthly_report_v2" />
		<input type="hidden" name="subaction" value="" />
		<input type="hidden" name="startMonth" value="<?php echo $startMonth; ?>" />
		<input type="hidden" name="endMonth" value="<?php echo $endMonth; ?>" />
		<section class="content">
			<div class="row">
				<div class="col-sm-3">
					<div class="">
						<div class="row">
							<div class="col-sm-12">
								<div class="btn-group btn-group-justified" data-toggle="buttons">
									<label class="btn btn-success btn-app <?php echo $filterGender == 'S' ? 'active' : ''; ?>" title="Show All"><input type="radio" name="filterGender" value="S" autocomplete="off" <?php echo $filterGender == 'S' ? 'checked="checked"' : ''; ?>> All</label>
									<label class="btn btn-success btn-app <?php echo $filterGender == 'M' ? 'active' : ''; ?>" title="Male"><input type="radio" name="filterGender" value="M" autocomplete="off" <?php echo $filterGender == 'M' ? 'checked="checked"' : ''; ?>><i class="fa fa-male"></i></label>
									<label class="btn btn-success btn-app <?php echo $filterGender == 'F' ? 'active' : ''; ?>" title="Female"><input type="radio" name="filterGender" value="F" autocomplete="off" <?php echo $filterGender == 'F' ? 'checked="checked"' : ''; ?>><i class="fa fa-female"></i></label>
								</div>
								<hr>
							</div>
							<div class="col-sm-12">
								<div class="btn-group btn-group-justified" data-toggle="buttons">
									<label class="btn btn-success btn-app <?php echo $filterAgeBand == 'S' ? 'active' : ''; ?>" title="Show All"><input type="radio" name="filterAgeBand" value="S" autocomplete="off" <?php echo $filterAgeBand == 'S' ? 'checked="checked"' : ''; ?>>  All</label>
									<label class="btn btn-success btn-app <?php echo $filterAgeBand == '1618' ? 'active' : ''; ?>"><input type="radio" name="filterAgeBand" value="1618" autocomplete="off" <?php echo $filterAgeBand == '1618' ? 'checked="checked"' : ''; ?>> 16-18</label>
									<label class="btn btn-success btn-app <?php echo $filterAgeBand == '1923' ? 'active' : ''; ?>"><input type="radio" name="filterAgeBand" value="1923" autocomplete="off" <?php echo $filterAgeBand == '1923' ? 'checked="checked"' : ''; ?>> 19-23</label>
									<label class="btn btn-success btn-app <?php echo $filterAgeBand == '24' ? 'active' : ''; ?>"><input type="radio" name="filterAgeBand" value="24" autocomplete="off" <?php echo $filterAgeBand == '24' ? 'checked="checked"' : ''; ?>> 24+</label>
								</div>
								<hr>
							</div>
							<div class="col-sm-12">
								<div class="btn-group btn-group-justified" data-toggle="buttons">
									<label class="btn btn-success btn-app <?php echo $filterLLDD == 'S' ? 'active' : ''; ?>" title="Show All"><input type="radio" name="filterLLDD" value="S" autocomplete="off" <?php echo $filterLLDD == 'S' ? 'checked="checked"' : ''; ?>>  All</label>
									<label class="btn btn-success btn-app <?php echo $filterLLDD == '1' ? 'active' : ''; ?>"><input type="radio" name="filterLLDD" value="1" autocomplete="off" <?php echo $filterLLDD == '1' ? 'checked="checked"' : ''; ?>>With<br>LLDD</label>
									<label class="btn btn-success btn-app <?php echo $filterLLDD == '2' ? 'active' : ''; ?>"><input type="radio" name="filterLLDD" value="2" autocomplete="off" <?php echo $filterLLDD == '2' ? 'checked="checked"' : ''; ?>>Without<br>LLDD</label>
									<label class="btn btn-success btn-app <?php echo $filterLLDD == '9' ? 'active' : ''; ?>"><input type="radio" name="filterLLDD" value="9" autocomplete="off" <?php echo $filterLLDD == '9' ? 'checked="checked"' : ''; ?>>No Info.</label>
								</div>
								<hr>
							</div>
							<div class="col-sm-12">
								<p><input class="clsICheck clsProgType" id="allProgType" type="checkbox" name="filterFundingProvision[]" value="0" <?php echo in_array(0, $filterFundingProvision) ? 'checked="checked"' : ''; ?> /><label>All</label></p>
								<p><input class="clsICheck clsProgType" type="checkbox" name="filterFundingProvision[]" value="1" <?php echo in_array('1', $filterFundingProvision) ? 'checked="checked"' : ''; ?> /><label>16-18 Apprenticeship</label></p>
								<p><input class="clsICheck clsProgType" type="checkbox" name="filterFundingProvision[]" value="2" <?php echo in_array('2', $filterFundingProvision) ? 'checked="checked"' : ''; ?> /><label>19-23 Apprenticeship</label></p>
								<p><input class="clsICheck clsProgType" type="checkbox" name="filterFundingProvision[]" value="3" <?php echo in_array('3', $filterFundingProvision) ? 'checked="checked"' : ''; ?> /><label>16-18 Levy Apprenticeship</label></p>
								<p><input class="clsICheck clsProgType" type="checkbox" name="filterFundingProvision[]" value="4" <?php echo in_array('4', $filterFundingProvision) ? 'checked="checked"' : ''; ?> /><label>19+ Levy Apprenticeship</label></p>
								<p><input class="clsICheck clsProgType" type="checkbox" name="filterFundingProvision[]" value="5" <?php echo in_array('5', $filterFundingProvision) ? 'checked="checked"' : ''; ?> /><label>All Ages - Levy Apprenticeship</label></p>
								<p><input class="clsICheck clsProgType" type="checkbox" name="filterFundingProvision[]" value="6" <?php echo in_array('6', $filterFundingProvision) ? 'checked="checked"' : ''; ?> /><label>Study Programme</label></p>
								<p><input class="clsICheck clsProgType" type="checkbox" name="filterFundingProvision[]" value="7" <?php echo in_array('7', $filterFundingProvision) ? 'checked="checked"' : ''; ?> /><label>Traineeship</label></p>
								<p><input class="clsICheck clsProgType" type="checkbox" name="filterFundingProvision[]" value="8" <?php echo in_array('8', $filterFundingProvision) ? 'checked="checked"' : ''; ?> /><label>Learner Loans</label></p>
								<p><input class="clsICheck clsProgType" type="checkbox" name="filterFundingProvision[]" value="9" <?php echo in_array('9', $filterFundingProvision) ? 'checked="checked"' : ''; ?> /><label>Other</label></p>
								<hr>
							</div>
							<div class="col-sm-12">
								<?php
								$ethnicities = [
									31 => 'British',
									32 => 'Irish',
									33 => 'Gypsy or Irish Traveller',
									34 => 'Any other White background',
									35 => 'White and Black Caribbean',
									36 => 'White and Black African',
									37 => 'White and Asian',
									38 => 'Any other Mixed',
									39 => 'Indian',
									40 => 'Pakistani',
									41 => 'Bangladeshi',
									42 => 'Chinese',
									43 => 'Any other Asian',
									44 => 'African',
									45 => 'Caribbean',
									46 => 'Any other Black',
									47 => 'Arab',
									98 => 'Any other ethnic group',
									99 => 'Not known/not provided'
								];
								echo in_array(0, $filterEthnicity) ? '<p><input class="clsICheck clsEthnicity" id="allEthnicity" type="checkbox" name="filterEthnicity[]" value="0" checked="checked" /><label>All</label></p>' : '<p><input class="clsICheck clsEthnicity" id="allEthnicity" type="checkbox" name="filterEthnicity[]" value="0" /><label>All</label></p>';
								foreach($ethnicities AS $key => $value)
								{
									$checked = in_array($key, $filterEthnicity) ? 'checked="checked"' : '';
									echo '<p><input class="clsICheck clsEthnicity" type="checkbox" name="filterEthnicity[]" value="'.$key.'" '. $checked .' /><label>'.$key . ' ' . $value.'</label></p>';
								}
								?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-9">
					<div class="row">
						<div class="col-sm-12">
							<div id="topFilters" class="well well-sm text-center" style="padding:2px;">
								<?php
								$start_year = (int)date('Y');
								$max_year = $start_year-5;
								for($_year = $start_year; $_year >= $max_year; $_year--)
								{
									if($_year == $filterYear)
										echo '<input type="radio" name="filterYear" title="'.$_year.'" value="'.$_year.'" checked="checked" />';
									else
										echo '<input type="radio" name="filterYear" title="'.$_year.'" value="'.$_year.'" />';
								}
								?>
								<div id="filterMonthRange"></div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="btn-group btn-group-justified" data-toggle="buttons">
								<?php
								$filterReportTypes = array('starts', 'restarts', 'continuing', 'overdue', 'ended', 'withdrawn', 'break_in_learning', 'achievers');
								foreach($filterReportTypes AS $_type)
								{
									$active = $filterReportType == $_type ? 'active' : '';
									$checked = $filterReportType == $_type ? 'checked="checked"' : '';
									$badge = $_type == $filterReportType ? '<span class="badge bg-aqua">'.$view->getRowCount().'</span>' : '';
									echo '<label class="btn btn-success btn-app '.$active.'"><input type="radio" name="filterReportType" value="'.$_type.'" autocomplete="off" '.$checked.'>'.ucwords(str_replace("_"," ",$_type)).$badge.'</label>';
								}
								?>
							</div>
							<p><br></p>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 small">
							<span class="btn btn-xs btn-primary pull-right" onclick="refreshMasterTable();"><i class="fa fa-refresh"></i> </span>
							<span class="btn btn-xs btn-info pull-left" onclick="exportToCSV();"><i class="fa fa-download"></i> Download CSV</span>
						</div>
						<div class="col-sm-6">
							<div class="box box-primary">
								<div class="box-body"><div id="panelLearnersByEthnicity" style="min-width: 300px; height: 450px; margin: 30 auto"></div></div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="box box-primary">
								<div class="box-body"><div id="panelLearnersByAgeBand" style="min-width: 300px; height: 450px; margin: 30 auto"></div></div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="box box-primary">
								<div class="box-body"><div id="panelLearnersByGender" style="min-width: 300px; height: 450px; margin: 30 auto"></div></div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="box box-primary">
								<div class="box-body"><div id="panelLearnersByFundingProvision" style="min-width: 300px; height: 450px; margin: 30 auto"></div></div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="box box-primary">
								<div class="box-body"><div id="panelLearnersByAssessors" style="min-width: 300px; height: 500px; margin: 30 auto"></div></div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="box box-primary">
								<div class="box-body"><div id="panelLearnersByPrimaryLLDD" style="min-width: 300px; height: 500px; margin: 30 auto"></div></div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="box box-primary">
								<div class="box-body"><div id="panelLearnersByRegion" style="min-width: 300px; height: 500px; margin: 30 auto"></div></div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="box box-primary">
								<div class="box-body"><div id="panelLearnersByProgType" style="min-width: 300px; height: 500px; margin: 30 auto"></div></div>
							</div>
						</div>
						<div class="col-sm-12">
                            <div class="box box-primary">
                                <div class="box-body"><div id="panelLearnersByLevel" style="min-width: 300px; height: 500px; margin: 30 auto"></div></div>
                            </div>
                        </div>
						<div class="col-sm-12">
							<div class="box box-primary">
								<div class="box-body"><div id="panelLearnersByRegionAndSSA1" style="min-width: 300px; height: 500px; margin: 30 auto"></div></div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="box box-info table-responsive" id="divLearnersStats">
								<div class="box-header with-border">
									<h1 class="box-title"><span class="fa fa-calendar"></span> </h1>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" id="btnLearnersStats"><i class="fa fa-print"></i></button>
									</div>
								</div>
								<div class="box-body"><?php echo $panelLearnersByStartMonth; ?></div>
							</div>
						</div>
						<!--<div class="col-sm-6">
							<div class="box box-primary">
								<div class="box-body"><div id="panelLearnersBySSA1" style="min-width: 300px; height: 550px; margin: 30 auto"></div></div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="box box-primary">
								<div class="box-body"><div id="panelLearnersBySSA2" style="min-width: 300px; height: 550px; margin: 30 auto"></div></div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="box box-primary">
								<div class="box-body"><div id="panelLearnersByFwk" style="min-width: 300px; height: 550px; margin: 30 auto"></div></div>
							</div>
						</div>-->
					</div>
				</div>
			</div>
		</section>
	</form>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/slider/jq-button-range-slider.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="https://code.highcharts.com/7.0.0/highcharts.src.js"></script>
<script src="https://code.highcharts.com/7.0.0/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/7.0.0/modules/exporting.js"></script>
<script src="https://code.highcharts.com/7.0.0/modules/no-data-to-display.js"></script>
<script src="module_charts/assets/script.js"></script>
<script src="module_charts/assets/jsonfn.js"></script>
<script src="/assets/adminlte/plugins/html2canvas/html2canvas.js"></script>
<script src="/assets/adminlte/plugins/FileSaver/FileSaver.js"></script>

<script>

</script>
<script>
	function updateURL(filterName, filterValue)
	{
		var queryParameters = {}, queryString = location.search.substring(1),
			re = /([^&=]+)=([^&]*)/g, m;
		while (m = re.exec(queryString)) {
			queryParameters[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
		}
		queryParameters[filterName] = filterValue;
		location.search = $.param(queryParameters); // Causes page to reload
	}
	$(function(){

		$("#btnLearnersStats").click(function() {
			html2canvas($("#divLearnersStats"), {
				onrendered: function(canvas) {
					canvas.toBlob(function(blob) {
						saveAs(blob, "LearnersStats.png");
					});
				}
			});
		});

		$("#topFilters").zInput();
		$( "#filterMonthRange" ).jqButtonRangeSlider({
			sliderOptions: [{
				name: "August",
				value: 1
			},{
				name: "September",
				value: 2
			},{
				name: "October",
				value: 3
			},{
				name: "November",
				value: 4
			},{
				name: "December",
				value: 5
			},{
				name: "January",
				value: 6
			},{
				name: "February",
				value: 7
			},{
				name: "March",
				value: 8
			},{
				name: "April",
				value: 9
			},{
				name: "May",
				value: 10
			},{
				name: "June",
				value: 11
			},{
				name: "July",
				value: 12
			}]
		}).on('afterChange', function(e, values, ui, slider) {
				updateView();
			});
		$( "#filterMonthRange" ).jqButtonRangeSlider( "setRange", {lb: <?php echo $startMonth; ?>, ub: <?php echo $endMonth; ?>});

		var chart = new Highcharts.chart('panelLearnersByEthnicity', JSONfn.parse('<?php echo $panelLearnersByEthnicity; ?>'));
		var chart = new Highcharts.chart('panelLearnersByGender', JSONfn.parse('<?php echo $panelLearnersByGender; ?>'));
		var chart = new Highcharts.chart('panelLearnersByAgeBand', JSONfn.parse('<?php echo $panelLearnersByAgeBand; ?>'));
		var chart = new Highcharts.chart('panelLearnersByFundingProvision', JSONfn.parse('<?php echo $panelLearnersByFundingProvision; ?>'));
		var chart = new Highcharts.chart('panelLearnersByAssessors', <?php echo $panelLearnersByAssessors; ?>);
		var chart = new Highcharts.chart('panelLearnersByPrimaryLLDD', <?php echo $panelLearnersByPrimaryLLDD; ?>);
		var chart = new Highcharts.chart('panelLearnersByRegion', <?php echo $panelLearnersByRegion; ?>);
		var chart = new Highcharts.chart('panelLearnersByProgType', <?php echo $panelLearnersByProgType; ?>);
		var chart = new Highcharts.chart('panelLearnersByRegionAndSSA1', <?php echo $panelLearnersByRegionAndSSA1; ?>);
		var chart = new Highcharts.chart('panelLearnersByLevel', <?php echo $panelLearnersByLevel; ?>);
<!--		var chart = new Highcharts.chart('panelLearnersByStartMonth', --><?php //echo $panelLearnersByStartMonth; ?><!--);-->
		<!--		var chart = new Highcharts.chart('panelLearnersBySSA1', --><?php //echo $panelLearnersBySSA1; ?><!--);-->
		<!--		var chart = new Highcharts.chart('panelLearnersBySSA2', --><?php //echo $panelLearnersBySSA2; ?><!--);-->
		<!--		var chart = new Highcharts.chart('panelLearnersByFwk', --><?php //echo $panelLearnersByFwk; ?><!--);-->




		$('input[name=filterGender], input[name=filterAgeBand], input[name=filterLLDD], input[name=filterYear], input[name=filterReportType]').on('change', function(){
			updateView();
		});
	});

	function updateView()
	{
		var sliderRange = $( "#filterMonthRange").jqButtonRangeSlider("getSliderRangeValue");
		var myForm = document.forms["frmFilters"];
		myForm.elements["startMonth"].value = sliderRange.lb.value;
		myForm.elements["endMonth"].value = sliderRange.ub.value;
		myForm.elements["subaction"].value = '';

		myForm.submit();
	}

	function exportToCSV()
	{
		var sliderRange = $( "#filterMonthRange").jqButtonRangeSlider("getSliderRangeValue");
		var myForm = document.forms["frmFilters"];
		myForm.elements["startMonth"].value = sliderRange.lb.value;
		myForm.elements["endMonth"].value = sliderRange.ub.value;
		myForm.elements["subaction"].value = 'export_to_csv';

		myForm.submit();
	}

	function refreshMasterTable()
	{
		if(!confirm('This action will take sometime, are you sure you want to continue?'))
			return;

		$.ajax({
			type:'GET',
			url:'do.php?_action=refresh_charts_master_table',
			dataType: 'json',
			beforeSend: function(){
				$(".content").html("<h5 class='text-bold' style='margin-top: 25%; margin-left: 45%; font-size: xx-large;'><i class=\"fa fa-refresh fa-spin\"></i> refreshing ...</h5>");
			},
			success: function(response) {
				window.location.reload();
			},
			error: function(data, textStatus, xhr){
				console.log(data.responseText);
			}
		});
	}

</script>

<script>
	$(function(){
		$('input.clsICheck').each(function(){
			var self = $(this);
			var label = self.next();
			var label_text = label.text();
			var checkboxClass;

			if (this.checked) {
				checkboxClass = 'icheckbox_line-green';
			} else  {
				checkboxClass = 'icheckbox_line-aero';
			}
			label.remove();
			self.iCheck({
				checkboxClass: checkboxClass,
				insert: '<div class="icheck_line-icon"></div>' + label_text
			});
		});

		$('.btnExport').on('click', function(){

		});
	});
	$(document).on('ifChanged', 'input.clsICheck', function() {
		var self = $(this);
		var label = self.parent();
		var label_text = label.text();
		var checkboxClass;
		if (this.checked) {
			checkboxClass = 'icheckbox_line-green';
		} else  {
			checkboxClass = 'icheckbox_line-aero';
		}
		self.iCheck({
			checkboxClass: checkboxClass,
			insert: '<div class="icheck_line-icon"></div>' + label_text
		});

		//console.log(this.name, this.value, this.checked);
		if(this.name == "filterFundingProvision[]" && this.value == 0 && this.checked)
		{
			$('.clsProgType').each(function () {
				if($(this).val() != 0)
					$(this).iCheck('uncheck');
			});
		}
		if(this.name == "filterFundingProvision[]" && this.value != 0 && this.checked)
		{
			$('.clsProgType').each(function () {
				if($(this).val() == 0)
					$(this).iCheck('uncheck');
			});
		}

		var anyProgTypeChecked = false;
		$('.clsProgType').each(function () {
			if(this.checked)
				anyProgTypeChecked = true;
		});
		if(!anyProgTypeChecked)
			$('#allProgType').iCheck('check');

		if(this.name == "filterEthnicity[]" && this.value == 0 && this.checked)
		{
			$('.clsEthnicity').each(function () {
				if($(this).val() != 0)
					$(this).iCheck('uncheck');
			});
		}
		if(this.name == "filterEthnicity[]" && this.value != 0 && this.checked)
		{
			$('.clsEthnicity').each(function () {
				if($(this).val() == 0)
					$(this).iCheck('uncheck');
			});
		}

		var anyEthnicityChecked = false;
		$('.clsEthnicity').each(function () {
			if(this.checked)
				anyEthnicityChecked = true;
		});
		if(!anyEthnicityChecked)
			$('#allEthnicity').iCheck('check');

		updateView();

	}).trigger('ifChanged');



</script>
</body>
</html>

<?php unset($view); ?>