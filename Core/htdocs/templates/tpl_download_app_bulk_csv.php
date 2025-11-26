
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis - Provider Bulk Upload</title>
	<link rel="stylesheet" href="module_tracking/css/common.css?n=<?php echo time(); ?>" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		#home_postcode{text-transform:uppercase}
	</style>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">CS Download - Apprenticeship Service</div>
			<div class="ButtonBar"></div>
			<div class="ActionIconBar"></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<?php $_SESSION['bc']->render($link); ?>
	</div>
</div>

<p></p>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-2">
			<div class="box-body" style="max-height: 500px; overflow-y: scroll;">
				<table class="table table-striped small">
					<thead><tr><th>&nbsp;</th><th>Contract Title</th></tr></thead>
					<tbody>
					<?php
					$contracts = DAO::getResultset($link, "SELECT contracts.id, contracts.title FROM contracts WHERE contract_year IN (2023, 2024) ORDER BY contract_year DESC, contracts.title ", DAO::FETCH_ASSOC);
					foreach($contracts AS $c)
					{
						echo '<tr>';
						echo '<td><input class="chkContractChoice" type="checkbox" name="contracts[]" value="' . $c['id'] . '" /></td>';
						echo '<td>' . $c['title'] . '</td>';
						echo '</tr>';
					}
					?>
					</tbody>
				</table>
			</div>
			<div class="box-footer">
				<button type="button" class="btn btn-primary pull-right" onclick="viewLearners(); "><i class="fa fa-search"></i> View</button>
			</div>
		</div>

		<div class="col-sm-10 table-responsive">
			<span class="btn btn-primary pull-right" onclick="exportLearners();"><i class="fa fa-download"></i> </span>
			<form name="frmLearners" id="frmLearners" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<input type="hidden" name="_action" value="download_app_bulk_csv" />
				<input type="hidden" name="subaction" value="" />
				<input type="hidden" name="contracts" value="" />
				<table id="tblResult" class="table row-border">
					<thead>
                    <tr>
                        <th></th><th><i class="fa fa-info-circle" title="This column will not be included in the export"></i> L03</th>
                        <th>CohortRef</th><th>AgreementID</th><th>ULN</th><th>FamilyName</th><th>GivenNames</th><th>DateOfBirth</th><th>EmailAddress</th>
						<th>StdCode</th><th>StartDate</th><th>EndDate</th><th>TotalPrice</th><th>EPAOrgID</th><th>ProviderRef</th>
						<?php if(date('Y-m-d') > '2022-07-31' && date('Y-m-d') < '2023-11-30'){ ?>
							<th>RecognisePriorLearning</th><th>DurationReducedBy</th><th>PriceReducedBy</th>
						<?php } ?>
						<?php if(date('Y-m-d') > '2023-11-29'){ ?>
							<th>RecognisePriorLearning</th><th>TrainingTotalhours</th><th>TrainingHoursReduction</th><th>IsDurationReducedByRPL</th>
							<th>DurationReducedBy</th><th>PriceReducedBy</th>
						<?php } ?>
                    </tr>
                    </thead>
					<tbody>
					<tr><td colspan="13"><i>Select contract(s) and click 'View' to bring the learners</i></td></tr>
					</tbody>
				</table>
			</form>
		</div>
	</div>

</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>

<script language="JavaScript">

	$(function(){
		$('.chkContractChoice').iCheck({
			checkboxClass: 'icheckbox_flat-red',
			radioClass: 'iradio_flat-red'
		});
	});

	function viewLearners()
	{
		var selectedContracts = [];
		$("input[name='contracts[]']").each( function () {
			if(this.checked)
				selectedContracts.push(this.value);
		});
		if(selectedContracts.length == 0)
		{
			alert('Please select the contract');
			return false;
		}

		$('#tblResult > tbody').html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i> <img id="loading" src="images/loading.gif" alt="Loading" /></div>');

		var client = ajaxRequest('do.php?_action=download_app_bulk_csv&subaction=viewLearners&contracts='+selectedContracts.join(','), null, null, viewLearnersCallback);
	}

	function viewLearnersCallback(client)
	{
		//console.log(client.responseText);
		$('#tblResult > tbody').html(client.responseText);
		$('.chkLearnerChoice').iCheck({
			checkboxClass: 'icheckbox_flat-red',
			radioClass: 'iradio_flat-red'
		});
	}

	function exportLearners()
	{
		var selectedContracts = [];
		$("input[name='contracts[]']").each( function () {
			if(this.checked)
				selectedContracts.push(this.value);
		});
		if(selectedContracts.length == 0)
		{
			alert('Please select the contract');
			return false;
		}
		var selectedLearners = [];
		$("input[name='learners[]']").each( function () {
			if(this.checked)
				selectedLearners.push(this.value);
		});
		var frmLearners = document.forms["frmLearners"];
		frmLearners.elements["contracts"].value = selectedContracts.join(',');
		frmLearners.elements["subaction"].value = 'exportLearners';
		frmLearners.submit();
		//window.location.href='do.php?_action=download_app_bulk_csv&subaction=exportLearners&contracts='+selectedContracts.join(',')+'&selected_learners='+selectedLearners.join(',');
	}

</script>

</body>
</html>