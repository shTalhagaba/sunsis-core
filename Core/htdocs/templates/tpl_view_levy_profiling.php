<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Levy Calculator</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>

	<script language="JavaScript">
		$(function() {
			$('.date-picker').datepicker( {
				minDate: new Date(2017, 04, 01),
				setDate: new Date(2017, 04, 01),
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true,
				dateFormat: 'MM yy',
				onClose: function(dateText, inst) {
					$(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
				}
			});

			$("input[name='chkStdFwk']").click(function() {
				if($(this).val() == 'std')
				{
					$('#trStandard').show();
					$('#trFwk').hide();
					$('#framework').val('');
					$('#messageBoxFramework').hide();
				}
				else
				{
					$('#trStandard').hide();
					$('#trFwk').show();
					$('#standard').val('');
					$('#messageBoxStandard').hide();
				}
			});
		});
		function numbersonly(myfield, e, dec)
		{
			var key;
			var keychar;

			if (window.event)
				key = window.event.keyCode;
			else if (e)
				key = e.which;
			else
				return true;
			keychar = String.fromCharCode(key);

			// control keys
			if ((key==null) || (key==0) || (key==8) ||
				(key==9) || (key==13) || (key==27) )
				return true;

			// numbers
			else if ((("0123456789").indexOf(keychar) > -1))
				return true;

			// decimal point jump
			else if (dec && (keychar == "."))
			{
				myfield.form.elements[dec].focus();
				return false;
			}
			else
				return false;
		}

		function employerCalculate()
		{
			$('#lblAnnualLevyAmount').html('');
			$('#lblMonthlyLevyAmount').html('');

			if($('#employer_paybill').val() == '')
			{
				alert('Please provide the employer payroll');
				return false;
			}

			var annualLevyAmount = 0;
			var monthlyLevyAmount = 0;
			var percentage_of_england_employees = '1';
			if($('#percentage_of_england_employees').val() != '')
				percentage_of_england_employees = parseFloat($('#percentage_of_england_employees').val())/100;

			var payroll = parseFloat($('#employer_paybill').val());
			if(payroll > 3000000)
			{
				annualLevyAmount = payroll * 0.005;

				annualLevyAmount = annualLevyAmount - 15000;

				monthlyLevyAmount = Math.floor(annualLevyAmount/12);

				monthlyLevyAmount = monthlyLevyAmount * percentage_of_england_employees;


				monthlyLevyAmount += monthlyLevyAmount * 0.1;

				monthlyLevyAmount = Math.ceil(monthlyLevyAmount);

				annualLevyAmount = monthlyLevyAmount*12;
			}
			$('#lblAnnualLevyAmount').html('&pound;'+annualLevyAmount);
			$('#lblMonthlyLevyAmount').html(monthlyLevyAmount);

			$('#lblAnnualLevyAmount').show();
			$('#lblAnnualLevyAmount').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
			$('#lblMonthlyLevyAmount').show();
		}

		function standard_onchange(standard)
		{
			$('#messageBoxStandard').hide();
			$('#messageBoxFramework').hide();
			if(standard.value == '')
				return;
			var url = 'do.php?_action=view_levy_profiling&subaction=getStandardDetails'
				+ "&code=" + encodeURIComponent(standard.value);

			var client = ajaxRequest(url);
			var html = null;
			if (client)
			{
				var records = jQuery.parseJSON(client.responseText);
				jQuery.each(records, function(name, value)
				{
					$('#Standard'+name).html('');
					if(name == 'UrlLink')
						$('#Standard'+name).html('<a href="'+value+'" target="_blank">'+value+'</a>');
					else
						$('#Standard'+name).val(value);
				});
				$('#messageBoxStandard').show();
			}
            else
                alert("Error");

		}

		function framework_onchange(framework)
		{
			$('#messageBoxFramework').hide();
			$('#messageBoxStandard').hide();
			if(framework.value == '')
				return;
			var url = 'do.php?_action=view_levy_profiling&subaction=getFrameworkDetails'
				+ "&code=" + encodeURIComponent(framework.value);

			var client = ajaxRequest(url);
			var html = null;
			if (client)
			{
				var records = jQuery.parseJSON(client.responseText);
				jQuery.each(records, function(name, value)
				{
					$('#Framework'+name).html('');
					$('#Framework'+name).val(value);
				});
				$('#messageBoxFramework').show();
			}

		}

		function submitForm()
		{
			var myForm = document.forms["frmProfiling"];
			if(validateForm(myForm) == false)
			{
				return false;
			}

			var client = ajaxPostForm(myForm, fullReportCallback);
		}

		function fullReportCallback(response)
		{
			if(response.status == 200)
			{
				employerCalculate();
				$('#div_report').html(response.responseText);
			}
			else
			{
				alert(response.responseText);
			}
		}
	</script>

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

		#framework optgroup { font-size:10px; }
	</style>
</head>
<body>
<div class="banner">
	<div class="Title">Levy Calculator</div>
	<div class="ButtonBar">
		<!--		<button onclick="save();">Save</button>
  -->		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<br>
<form name="frmProfiling" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="_action" value="view_levy_profiling" />
	<input type="hidden" name="subaction" value="produceFullReport" />
	<table border="0">
		<tr>
			<td valign="top">
				<fieldset>
					<legend>Employer</legend>
					<table border="0" cellspacing="5" cellpadding="5">
						<tr>
							<td class="fieldLabel">Payroll: </td>
							<td class="fieldValue">&pound;<input type="text" name="employer_paybill" onKeyPress="return numbersonly(this, event);" id="employer_paybill" size="10" placeholder="0" value="5000000" /></td>
						</tr>
						<tr>
							<td >Total Number of Employees: </td>
							<td class="fieldValue" > &nbsp;&nbsp;&nbsp;<input type="text" name="total_number_of_employees" onKeyPress="return numbersonly(this, event);" id="total_number_of_employees" placeholder="" size="10" value="" /></td>
						</tr>
						<tr>
							<td class="fieldLabel">Percentage of Employees in <br>England: </td>
							<td class="fieldValue" >% <input type="text" name="percentage_of_england_employees" onKeyPress="return numbersonly(this, event);" id="percentage_of_england_employees" placeholder="" size="10" value="100" /></td>
						</tr>
						<tr>
							<td colspan="2" align="right">Annual Levy Amount:  <label id="lblAnnualLevyAmount" style="font-size: 150%; display: none;"> </label> </td>
	  					</table>
				</fieldset>
			</td>
			<td valign="top">
				<fieldset>
					<legend>Apprenticeship</legend>
					<table border="0" cellspacing="5" cellpadding="5">
						<tr>
							<td class="fieldLabel">Standard: <input type="radio" name="chkStdFwk" value="std" checked="checked" /></td>
							<td class="fieldLabel">Framework: <input type="radio" name="chkStdFwk" value="fwk" /></td>
						</tr>
						<tr id="trStandard">
							<td class="fieldLabel">Apprenticeship Standard: </td>
							<td colspan="3" class="fieldValue"><?php echo HTML::select('standard', $standardDDL, '', true, true, true, 1, ' style="max-width:300px;" '); ?></td>
						</tr>
						<tr id="trFwk" style="display: none;">
							<td class="fieldLabel">Apprenticeship Framework: </td>
							<td colspan="3" class="fieldValue"><?php echo HTML::select('framework', $frameworksDDL, '', true, true, true, 1, ' style="max-width:300px;" '); ?></td>
						</tr>
						<tr>
							<td class="fieldLabel">Negotiated Price: </td>
							<td class="fieldValue">&pound;<input class="compulsory" type="text" name="negotiated_price" onKeyPress="return numbersonly(this, event);" id="negotiated_price" size="10" placeholder="0" value="" /></td>
							<td class="fieldLabel">Expected Duration: </td>
							<td class="fieldValue">Months <input class="compulsory" type="text" name="expected_duration" onKeyPress="return numbersonly(this, event);" id="expected_duration" size="10" placeholder="0" value="" /></td>
						</tr>
						<tr>
							<td class="fieldLabel">16-18 Learners: </td>
							<td class="fieldValue"> &nbsp;<input class="compulsory" type="text" name="learners_1618" onKeyPress="return numbersonly(this, event);" id="learners1618" size="10" placeholder="" value="" /></td>
							<td >19+ Learners: </td>
							<td class="fieldValue"> &nbsp;<input class="optional" type="text" name="learners_19" onKeyPress="return numbersonly(this, event);" id="learners_19" size="10" placeholder="" value="" /></td>
						</tr>
						<tr>
							<td class="fieldLabel">Start Date: </td>
							<td class="fieldValue"><input name="startDate" id="startDate" class="compulsory date-picker" /></td>
							<td ></td>
							<td></td>
						</tr>
					</table>
				</fieldset>
			</td>
			<td valign="top">
				<fieldset id="messageBoxStandard" style="display: none;">
					<legend>Standard Details</legend>
					<table cellpadding="6">
						<tr><td class="fieldLabel">Effective from:</td><td class="fieldValue"><input id="StandardEffectiveFrom" name="StandardEffectiveFrom" readonly/></td></tr>
						<tr><td class="fieldLabel">Upper limit:</td><td class="fieldValue">&pound; <input id="StandardMaxEmployerLevyCap" name="StandardMaxEmployerLevyCap" value="" readonly/></td></tr>
						<tr><td class="fieldLabel">BandNumber:</td><td class="fieldValue"><input id="StandardBandNumber" name="StandardBandNumber" readonly/></td></tr>
						<tr><td class="fieldLabel">Further Information:</td><td class="fieldValue"><label id="StandardUrlLink"></label></td></tr>
					</table>
				</fieldset>
			</td>
			<td valign="top">
				<fieldset id="messageBoxFramework" style="display: none;">
					<legend>Framework Details</legend>
					<table cellpadding="6">
						<tr><td class="fieldLabel">Effective from:</td><td class="fieldValue"><input id="FrameworkEffectiveFrom" name="FrameworkEffectiveFrom"/></td></tr>
						<tr><td class="fieldLabel">Upper limit:</td><td class="fieldValue">&pound; <input id="FrameworkMaxEmployerLevyCap" name="FrameworkMaxEmployerLevyCap" value=""/></td></tr>
						<tr><td class="fieldLabel">BandNumber:</td><td class="fieldValue"><input id="FrameworkBandNumber" name="FrameworkBandNumber"/></td></tr>
					</table>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td colspan="3" align="center"><span onclick="submitForm();" class="button"> &nbsp;&nbsp;&nbsp;Calculate&nbsp;&nbsp;&nbsp; </span> </td>
		</tr>
	</table>
</form>

<fieldset id="audit_learners">
	<legend>Report</legend>
	<div id="div_report" align="center">Enter your criteria and click Generate</div>
</fieldset>
</body>
</html>