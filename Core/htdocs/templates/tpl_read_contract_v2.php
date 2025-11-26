<?php /* @var $vo Contract */ ?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Contract</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;"><?php echo htmlspecialchars((string)$vo->title); ?></div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
				<?php if($acl->isAuthorised($_SESSION['user'], 'write') || ($_SESSION['user']->type == User::TYPE_MANAGER && DB_NAME=="am_lead")) { ?>
				<span class="btn btn-sm btn-default" onclick="window.location.replace('do.php?id=<?php echo $vo->id; ?>&_action=edit_contract');"><i class="fa fa-edit"></i> Edit</span>
				<?php } ?>
<!--				<span class="btn btn-sm btn-default" onclick="window.location.replace('do.php?contract_id=--><?php //echo $id; ?><!--&_action=edit_ilr--><?php //echo $contract_year?><!--&template=1');"><i class="fa fa-file"></i> ILR Template</span>
				<span class="btn btn-sm btn-default" onclick="if(prompt('Password','')=='thereisnopassword')window.location.replace('do.php?contract_id=<?php echo $vo->id; ?>&_action=import_contracts');"><i class="fa fa-caret-square-o-right"></i> Migrate</span>-->
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

<div class="row">
	<div class="col-sm-7">
		<div class="col-sm-12">
			<div class="callout">
				<h4><?php echo htmlspecialchars((string)$vo->title); ?> &nbsp;  &nbsp; <i class="text-muted"><?php echo Date::toShort($vo->start_date) . ' - ' . Date::toShort($vo->end_date); ?></i> </h4>
				<dl class="dl-horizontal">
					<dt>Contract Holder: </dt><dd><?php echo htmlspecialchars((string)$contract_holder); ?></dd>
					<dt>Location: </dt><dd><?php echo htmlspecialchars((string)$contract_location); ?></dd>
					<dt>UKPRN: </dt><dd><?php echo htmlspecialchars((string)$vo->ukprn); ?></dd>
					<dt>UPIN: </dt><dd><?php echo htmlspecialchars((string)$vo->upin); ?></dd>
				</dl>
				<?php echo $vo->funded == "1" ? '<label class="label label-success">Funded</label>' : '<label class="label label-info">Non Funded</label>'; ?>
				<?php echo $vo->funding_type == "1" ? '<label class="label label-success">Included in QARs</label>' : '<label class="label label-info">Not Included in QARs</label>'; ?>
				<?php echo $vo->active == "1" ? '<label class="label label-success">Active</label>' : '<label class="label label-info">Inactive</label>'; ?>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-header with-border"><h5 class="box-title"> Status of ILRs</h5></div>
				<div class="box-body">
					<div class="table-responsive">
						<table class="table table-bordered">
							<tr class="bg-gray"><th>&nbsp;</th><th> Submission </th> <th> Last Submisison Date </th> <th> Valid / Invalid </th><th> Approved / Unapproved </th> <th> Active / Suspended </th>
								<th><a href="do.php?id=<?php echo $vo->id; ?>&_action=edit_profile&data=profile">Profile</a></th>
								<th><a href="do.php?id=<?php echo $vo->id; ?>&_action=edit_profile&data=pfr">PFR</a></th>
								<th> Status  </th></tr>
							<?php

							if($vo->funding_body=='2')
							{
								echo '<tr>';
								$vo3->render($link,'W01');
								echo '</tr><tr>';
								$vo3->render($link,'W02');
								echo '</tr><tr>';
								$vo3->render($link,'W03');
								echo '</tr><tr>';
								$vo3->render($link,'W04');
								echo '</tr><tr>';
								$vo3->render($link,'W05');
								echo '</tr><tr>';
								$vo3->render($link,'W06');
								echo '</tr><tr>';
								$vo3->render($link,'W07');
								echo '</tr><tr>';
								$vo3->render($link,'W08');
								echo '</tr><tr>';
								$vo3->render($link,'W09');
								echo '</tr><tr>';
								$vo3->render($link,'W10');
								echo '</tr><tr>';
								$vo3->render($link,'W11');
								echo '</tr><tr>';
								$vo3->render($link,'W12');
								echo '</tr><tr>';
								$vo3->render($link,'W13');
								echo '</tr>';
							}
							elseif($vo->funding_body=='1')
							{
								echo '<tr>';
								$vo3->render($link,'W01');
								echo '</tr><tr>';
								$vo3->render($link,'W02');
								echo '</tr><tr>';
								$vo3->render($link,'W03');
								echo '</tr><tr>';
								$vo3->render($link,'W04');
								echo '</tr><tr>';
								$vo3->render($link,'W05');
								echo '</tr>';
							}
							?>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-body"><div id="panelLearnersByLevel" style="min-width: 300px; height: 550px; margin: 30 auto"></div></div>
			</div>
			<div class="box box-primary">
				<div class="box-body"><div id="panelLearnersByAssessors" style="min-width: 300px; height: 600px; margin: 30 auto"></div></div>
			</div>
			<div class="box box-primary">
				<div class="box-body"><div id="panelLearnersByOutcomeCode" style="min-width: 300px; height: 500px; margin: 30 auto"></div></div>
			</div>
		</div>
	</div>
	<div class="col-sm-5">
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-body"><div id="panelLearnersByEthnicity" style="min-width: 300px; height: 400px; margin: 30 auto"></div></div>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-body"><div id="panelLearnersByAgeBand" style="min-width: 300px; height: 400px; margin: 30 auto"></div></div>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-body"><div id="panelLearnersByGender" style="min-width: 300px; height: 400px; margin: 30 auto"></div></div>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-body"><div id="panelLearnersBySubmission" style="min-width: 300px; height: 500px; margin: 30 auto"></div></div>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-body"><div id="panelLearnersByOutcomeType" style="min-width: 300px; height: 400px; margin: 30 auto"></div></div>
			</div>
		</div>
	</div>
</div>

<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/7.0.0/highcharts.src.js"></script>
<script src="https://code.highcharts.com/7.0.0/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/7.0.0/modules/exporting.js"></script>
<script src="https://code.highcharts.com/7.0.0/modules/no-data-to-display.js"></script>

<script language="JavaScript">

	$(function() {
		var chart = new Highcharts.chart('panelLearnersByEthnicity', <?php echo $panelLearnersByEthnicity; ?>);
		var chart = new Highcharts.chart('panelLearnersByAgeBand', <?php echo $panelLearnersByAgeBand; ?>);
		var chart = new Highcharts.chart('panelLearnersByGender', <?php echo $panelLearnersByGender; ?>);
		var chart = new Highcharts.chart('panelLearnersByAssessors', <?php echo $panelLearnersByAssessors; ?>);
		var chart = new Highcharts.chart('panelLearnersByLevel', <?php echo $panelLearnersByLevel; ?>);
		var chart = new Highcharts.chart('panelLearnersBySubmission', <?php echo $panelLearnersBySubmission; ?>);
		var chart = new Highcharts.chart('panelLearnersByOutcomeType', <?php echo $panelLearnersByOutcomeType; ?>);
		var chart = new Highcharts.chart('panelLearnersByOutcomeCode', <?php echo $panelLearnersByOutcomeCode; ?>);
	});

	function importContract(event)
	{
		var myForm = document.forms[0];
		var buttons = myForm.elements['contracts'];

		id = buttons[buttons.selectedIndex].value;

		if(id == '')
		{
			alert("Please select a Contract");
			return false;
		}
		else
		{

			var postData = 'contract_to_import_id=' + id
				+ '&current_contract_id=' + <?php echo $vo->id; ?>;

			var client = ajaxRequest('do.php?_action=import_contract', postData);
			if(client != null)
			{
				var xml = client.responseXML;
				var report = client.responseXML.documentElement;
				var tags = report.getElementsByTagName('success');
				if(tags.length > 0)
				{
					alert("ILR Form saved!");
					window.history.go(-1);
				}
			}
		}
	}


</script>

</body>
</html>