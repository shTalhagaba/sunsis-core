<?php /* @var $view View */ ?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Contracts Selection</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
		.disabled{
			pointer-events:none;
			opacity:0.4;
		}
		input[type=checkbox] {
			transform: scale(1.4);
		}
	</style>
</head>
<body onload="setContracts();">

<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Download Batch File</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
<!--				<span class="btn btn-sm btn-default" onclick="downloadBatchFile();"><i class="fa fa-download"></i> Download</span>-->
			</div>
			<div class="ActionIconBar">

			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<?php $_SESSION['bc']->render($link); ?>
	</div>
</div>

<br>

<div class="container-fluid">

	<form name="frmBatchFileFilters" class="form-horizontal" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		<input type="hidden" name="_action" value="generate_batch_file" />
		<input type="hidden" name="xml" value="" />
		<input type="hidden" name="beta" value="" />
		<input type="hidden" name="submission" value="" />

		<div class="row">
			
			<div class="col-sm-offset-2 col-sm-8 ">
				<div class="well well-sm">
					<div class="form-group">
						<label for="submissions" class="col-sm-4 control-label fieldLabel_compulsory">Select submission period:</label>
						<div class="col-sm-4">
							<?php echo HTML::selectChosen('submissions', $submissions, $submission, true, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="contract_year" class="col-sm-4 control-label fieldLabel_compulsory">Select year to view related contracts:</label>
						<div class="col-sm-4">
							<?php echo HTML::selectChosen('contract_year', $contract_years, $contract_years, false, true); ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-offset-2 col-sm-8 ">
				<span id="btnDownload" class="btn btn-md btn-primary btn-block" onclick="downloadBatchFile();"><i class="fa fa-download"></i> Click to Download</span><br>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-offset-2 col-sm-8 ">
				<div class="table-responsive" id="data">
					<?php echo $view->render($link); ?>
				</div>
			</div>
		</div>
	</form>

</div>

<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>


<script language="JavaScript">

	$(function() {

		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy'
		});

		

	});
	
	function setContracts()
	{
		var y = <?php echo $contract_years[0][0]; ?>;
		div = document.getElementById("data");
		elements = div.getElementsByTagName('tr');
		for(var i = 0; i < elements.length; i++)
		{
			if(elements[i].title == y)
				elements[i].style.display = "table-row";
			else if(elements[i].title!='')
				elements[i].style.display = "none";
		}

		var obj = 
		{
		value: y,
		}
		contract_year_onchange(obj);
	}

	function downloadBatchFile()
	{
		var myForm = document.forms["frmBatchFileFilters"];
		var buttons = myForm.elements['evidenceradio'];
		var evidence_id = '';
		var selected = 0;

		var xml = "<contracts>";
		for(var i = 0; i < buttons.length; i++)
		{
			if(buttons[i].checked)
			{
				selected = 1;
				evidence_id =  buttons[i].value;
				xml += '<contract>' + evidence_id + '</contract>';
			}
		}
		xml += '</contracts>';

		if(selected == 0)
		{
			alert("Please select a contract");
			return false;
		}

		if(document.getElementById('submissions').value == '')
		{
			alert("Please select a submission");
			return false;
		}

		var submission = myForm.submissions.options[myForm.submissions.selectedIndex].text;

		myForm.xml.value = xml;
		myForm.submission.value = submission;
		myForm.beta.value = "n";


		$('#btnDownload').addClass('disabled');
		$('#btnDownload').html('<i class="fa fa-spin fa-spinner fa-1x"></i> Generating... Refresh page when download completes');

		myForm.submit();
	}

	function evidenceradio_onclick(element)
	{
		var row = element.parentNode.parentNode;

		if(element.checked == true)
		{
			row.style.backgroundColor = 'orange';
		}
		else
		{
			row.style.backgroundColor = '';
		}
	}

	function contract_year_onchange(y)
	{
		div = document.getElementById("data");
		elements = div.getElementsByTagName('input');
		for(var i = 0; i < elements.length; i++)
			if(elements[i].type == "checkbox")
				elements[i].checked = false;
		elements = div.getElementsByTagName('tr');
		for(var i = 0; i < elements.length; i++)
		{
			if(elements[i].title == y.value)
				elements[i].style.display = "table-row";
			else if(elements[i].title!='')
				elements[i].style.display = "none";
		}

		query = "SELECT RIGHT(submission,2) FROM central.lookup_submission_dates WHERE contract_year = " + y.value + " AND CURDATE() BETWEEN census_start_date AND last_submission_date;";
		var request = ajaxRequest("do.php?_action=ajax_get_value&id=&query=" + htmlspecialchars(query));
		if(request.responseText!="")
			document.forms[0].submissions.selectedIndex = request.responseText;
		else
			document.forms[0].submissions.selectedIndex = "13";


	}

	function checkAll(t)
	{
		var y = document.getElementById('contract_year').value;
		div = document.getElementById("data");
		elements = div.getElementsByTagName('input');
		for(var i = 0; i < elements.length; i++)
		{
			if(elements[i].type == "checkbox" && elements[i].title == y)
			{
				if(t.checked)
				{
					elements[i].checked = true;
					elements[i].parentNode.parentNode.style.backgroundColor = 'orange';
				}
				else
				{
					elements[i].checked = false;
					elements[i].parentNode.parentNode.style.backgroundColor = '';
				}
			}
		}
	}

</script>

</body>
</html>