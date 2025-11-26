
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis - Aims Difference in ILR and TR</title>
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
			<div class="Title" style="margin-left: 6px;">Aims Difference in ILR and TR</div>
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
		<div class="col-md-12">
			<div class="callout callout-info">
				<i class="fa fa-info-circle"></i> This report lists all the learners having different aims in Training Record and ILR.
				This report will select the current submission's (<b>W<?php echo $current_submission; ?></b>) ILRs.
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-3">
			<div class="box-body" style="max-height: 500px; overflow-y: scroll;">
				<table class="table table-striped">
					<thead><tr><th>&nbsp;</th><th>Contract Title</th></tr></thead>
					<tbody>
					<?php
					$contracts = DAO::getResultset($link, "SELECT contracts.id, contracts.title FROM contracts WHERE contract_year = (select contract_year from contracts order by contract_year desc limit 0,1) ORDER BY contracts.title ", DAO::FETCH_ASSOC);
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
				<button type="button" class="btn btn-primary pull-right" onclick="viewLearners(); "> View</button>
			</div>
		</div>

		<div class="col-sm-9 table-responsive">
			<span class="btn btn-primary pull-right" onclick="exportAimsDifference();"><i class="fa fa-download"></i> </span>
			<table id="tblResult" class="table row-border">
				<thead><tr><th>L03</th><th>ULN</th><th>FamilyName</th><th>GivenName</th><th>Diff aims in ILR</th><th>Diff aims in TR</th></tr></thead>
				<tbody>
				<tr><td colspan="14"><i>Select contract(s) and click 'View' to find the learners with different aims in ILR and Training Record</i></td></tr>
				</tbody>
			</table>
		</div>
	</div>

</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js?n=<?php echo time(); ?>" type="text/javascript"></script>
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

		var client = ajaxRequest('do.php?_action=view_aims_difference&subaction=viewLearners&contracts='+selectedContracts.join(','), null, null, viewLearnersCallback);
	}

	function viewLearnersCallback(client)
	{
		$('#tblResult > tbody').html(client.responseText);
	}

	function exportAimsDifference()
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
		window.location.href='do.php?_action=view_aims_difference&subaction=exportAimsDifference&contracts='+selectedContracts.join(',');
	}

</script>

</body>
</html>