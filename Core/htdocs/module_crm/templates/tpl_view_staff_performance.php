<?php /* @var $view VoltView */ ?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis</title>
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
		<input type="hidden" name="_action" value="view_staff_performance" />
		<input type="hidden" name="subaction" value="" />
		<input type="hidden" name="startMonth" value="<?php echo $startMonth; ?>" />
		<input type="hidden" name="endMonth" value="<?php echo $endMonth; ?>" />
		<section class="content">
			<div class="row">

				<div class="col-sm-12">
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
					</div>
					<div class="row">
						<div class="col-sm-12">
							<table class="table table-bordered table-hover">
								<tr class="bg-gray">
									<th>User</th><th>Enquiries</th><th>Leads</th><th>Opportunities</th><th>Activities</th>
								</tr>
								<?php
								foreach($staff as $id)
								{
									echo '<tr>';
									echo '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$id}' ") . '</td>';
									echo '<td>';
									echo 'In Progress: <strong>' . DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_enquiries WHERE status IN (1, 2) AND created_by = '{$id}' AND crm_enquiries.created BETWEEN '$dates->start_date' AND '$dates->end_date'") . '</strong><br>';
									echo 'Successful: <strong>' . DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_enquiries WHERE status IN (3) AND created_by = '{$id}' AND crm_enquiries.created BETWEEN '$dates->start_date' AND '$dates->end_date'") . '</strong><br>';
									echo 'Unsuccessful: <strong>' . DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_enquiries WHERE status IN (4) AND created_by = '{$id}' AND crm_enquiries.created BETWEEN '$dates->start_date' AND '$dates->end_date'") . '</strong><br>';
									echo '</td>';
									echo '<td>';
									echo 'In Progress: <strong>' . DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_leads WHERE status IN (1, 2) AND created_by = '{$id}' AND crm_leads.created BETWEEN '$dates->start_date' AND '$dates->end_date'") . '</strong><br>';
									echo 'Successful: <strong>' . DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_leads WHERE status IN (3) AND created_by = '{$id}' AND crm_leads.created BETWEEN '$dates->start_date' AND '$dates->end_date'") . '</strong><br>';
									echo 'Unsuccessful: <strong>' . DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_leads WHERE status IN (4) AND created_by = '{$id}' AND crm_leads.created BETWEEN '$dates->start_date' AND '$dates->end_date'") . '</strong><br>';
									echo '</td>';
									echo '<td>';
									echo 'In Progress: <strong>' . DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_opportunities WHERE status IN (1, 2) AND created_by = '{$id}' AND crm_opportunities.created BETWEEN '$dates->start_date' AND '$dates->end_date'") . '</strong><br>';
									echo 'Successful: <strong>' . DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_opportunities WHERE status IN (3) AND created_by = '{$id}' AND crm_opportunities.created BETWEEN '$dates->start_date' AND '$dates->end_date'") . '</strong><br>';
									echo 'Unsuccessful: <strong>' . DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_opportunities WHERE status IN (4) AND created_by = '{$id}' AND crm_opportunities.created BETWEEN '$dates->start_date' AND '$dates->end_date'") . '</strong><br>';
									echo '</td>';
									echo '<td>';
									$total_activities = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_activities WHERE created_by = '{$id}'");
									$total_hours = DAO::getSingleValue($link, "SELECT SUM(hours) FROM crm_activities WHERE created_by = '{$id}' AND created_at BETWEEN '$dates->start_date' AND '$dates->end_date';");
									$total_minutes = DAO::getSingleValue($link, "SELECT SUM(minutes) FROM crm_activities WHERE created_by = '{$id}' AND created_at BETWEEN '$dates->start_date' AND '$dates->end_date';");
									echo 'Total Activities: <strong>' . $total_activities . '</strong><br>';
									$total_minutes += $total_hours*60;
									echo 'Total Time Spent: ' . $this->convertToHoursMins($total_minutes, '<strong>%02d </strong>hours <strong>%02d </strong>minutes');
//									echo 'Total Time Spent: <strong>' . $total_hours . '</strong> hours and <strong>' . $total_minutes . '</strong> minutes<br>';

									echo '</td>';
									echo '</tr>';

								}
								?>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-sm12">
							<table class="table table-bordered table-hover">
								<tr class="bg-gray"><th>User</th><th>Tasks</th><th>Calls</th><th>Meetings</th><th>Emails</th><th>Enquiry Activities</th></tr>
								<?php
								foreach($staff as $id)
								{
									echo '<tr>';
									echo '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$id}' ") . '</td>';
									echo '<td>';
									$tasks = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_activities WHERE activity_type = 'task' AND created_by = '{$id}' AND created_at BETWEEN '$dates->start_date' AND '$dates->end_date'");
									$total_hours = DAO::getSingleValue($link, "SELECT SUM(hours) FROM crm_activities WHERE activity_type = 'task' AND created_by = '{$id}' AND created_at BETWEEN '$dates->start_date' AND '$dates->end_date';");
									$total_minutes = DAO::getSingleValue($link, "SELECT SUM(minutes) FROM crm_activities WHERE activity_type = 'task' AND created_by = '{$id}' AND created_at BETWEEN '$dates->start_date' AND '$dates->end_date';");
									echo $tasks . '<br>';
									$total_minutes += $total_hours*60;
									echo 'Total Time Spent: ' . $this->convertToHoursMins($total_minutes, '<strong>%02d </strong>hours <strong>%02d </strong>minutes');
									echo '</td>';
									echo '<td>';
									$calls = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_activities WHERE activity_type = 'phone' AND created_by = '{$id}' AND created_at BETWEEN '$dates->start_date' AND '$dates->end_date'");
									$total_hours = DAO::getSingleValue($link, "SELECT SUM(hours) FROM crm_activities WHERE activity_type = 'phone' AND created_by = '{$id}' AND created_at BETWEEN '$dates->start_date' AND '$dates->end_date';");
									$total_minutes = DAO::getSingleValue($link, "SELECT SUM(minutes) FROM crm_activities WHERE activity_type = 'phone' AND created_by = '{$id}' AND created_at BETWEEN '$dates->start_date' AND '$dates->end_date';");
									echo $calls . '<br>';
									$total_minutes += $total_hours*60;
									echo 'Total Time Spent: ' . $this->convertToHoursMins($total_minutes, '<strong>%02d </strong>hours <strong>%02d </strong>minutes');
									echo '</td>';
									echo '<td>';
									$meetings = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_activities WHERE activity_type = 'meeting' AND created_by = '{$id}' AND created_at BETWEEN '$dates->start_date' AND '$dates->end_date'");
									$total_hours = DAO::getSingleValue($link, "SELECT SUM(hours) FROM crm_activities WHERE activity_type = 'meeting' AND created_by = '{$id}' AND created_at BETWEEN '$dates->start_date' AND '$dates->end_date';");
									$total_minutes = DAO::getSingleValue($link, "SELECT SUM(minutes) FROM crm_activities WHERE activity_type = 'meeting' AND created_by = '{$id}' AND created_at BETWEEN '$dates->start_date' AND '$dates->end_date';");
									echo $meetings . '<br>';
									$total_minutes += $total_hours*60;
									echo 'Total Time Spent: ' . $this->convertToHoursMins($total_minutes, '<strong>%02d </strong>hours <strong>%02d </strong>minutes');
									echo '</td>';
									echo '<td>';
									$emails = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_activities WHERE activity_type = 'email' AND created_by = '{$id}' AND created_at BETWEEN '$dates->start_date' AND '$dates->end_date'");
									$total_hours = DAO::getSingleValue($link, "SELECT SUM(hours) FROM crm_activities WHERE activity_type = 'email' AND created_by = '{$id}' AND created_at BETWEEN '$dates->start_date' AND '$dates->end_date';");
									$total_minutes = DAO::getSingleValue($link, "SELECT SUM(minutes) FROM crm_activities WHERE activity_type = 'email' AND created_by = '{$id}' AND created_at BETWEEN '$dates->start_date' AND '$dates->end_date';");
									echo $emails . '<br>';
									$total_minutes += $total_hours*60;
									echo 'Total Time Spent: ' . $this->convertToHoursMins($total_minutes, '<strong>%02d </strong>hours <strong>%02d </strong>minutes');
									echo '</td>';
									echo '<td>';
									echo DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_activities WHERE created_by = '{$id}' AND crm_activities.created_at BETWEEN '$dates->start_date' AND '$dates->end_date'");
									echo '</td>';
									echo '</tr>';
								}
								?>
							</table>
						</div>
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

		$("#topFilters").zInput();
		$( "#filterMonthRange" ).jqButtonRangeSlider({
			sliderOptions: [{
				name: "January",
				value: 1
			},{
				name: "February",
				value: 2
			},{
				name: "March",
				value: 3
			},{
				name: "April",
				value: 4
			},{
				name: "May",
				value: 5
			},{
				name: "June",
				value: 6
			},{
				name: "July",
				value: 7
			},{
				name: "August",
				value: 8
			},{
				name: "September",
				value: 9
			},{
				name: "October",
				value: 10
			},{
				name: "November",
				value: 11
			},{
				name: "December",
				value: 12
			}]
		}).on('afterChange', function(e, values, ui, slider) {
				updateView();
			});
		$( "#filterMonthRange" ).jqButtonRangeSlider( "setRange", {lb: <?php echo $startMonth; ?>, ub: <?php echo $endMonth; ?>});


		$('input[name=filterYear]').on('change', function(){
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