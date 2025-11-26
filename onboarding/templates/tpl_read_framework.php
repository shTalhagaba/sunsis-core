<?php /* @var $framework Framework */ ?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $isFramework ? 'Framework' : 'Standard/ Programme'; ?></title>
	<link rel="stylesheet" href="/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
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
			<div class="Title" style="margin-left: 6px;"><?php echo $isFramework ? 'Framework' : 'Standard/ Programme'; ?></div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
				<?php if( $_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [1]) ) { ?>
				<span class="btn btn-sm btn-default" onclick="window.location.replace('do.php?_action=edit_framework&framework_id=<?php echo $framework->id; ?>');"><i class="fa fa-edit"></i> Edit</span>
				<?php } ?>
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
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-7">
				<div class="box box-solid box-primary">
					<div class="box-header with-border">
						<h2 class="box-title"><?php echo $framework->title; ?></h2>
					</div>
					<div class="box-body">
						<div class="table-responsive">
							<table class="table table-bordered">
								<tr>
									<th>Status</th>
									<td><?php echo $framework->active == "1" ? '<label class="label label-success">Active</label>' : '<label class="label label-info">Inactive</label>'; ?></td>
								</tr>
								<tr>
									<th>Duration</th>
									<td><?php echo $framework->duration_in_months; ?> months</td>
								</tr>
								<tr>
									<th>EPA Duration</th>
									<td><?php echo $framework->epa_duration; ?> months</td>
								</tr>
								<tr>
									<th>Programme Type</th>
									<td><?php echo DAO::getSingleValue($link, "SELECT CONCAT(ProgType, ' ' , ProgTypeDesc) FROM lars201718.CoreReference_LARS_ProgType_Lookup WHERE ProgType = '{$framework->framework_type}'"); ?></td>
								</tr>
								<?php if($isFramework) { ?>
								<tr>
									<th>Framework Code</th>
									<td><?php echo DAO::getSingleValue($link, "SELECT CONCAT(FworkCode, ' ', IssuingAuthorityTitle) FROM lars201718.`Core_LARS_Framework` WHERE FworkCode = '{$framework->framework_code}'"); ?></td>
								</tr>
								<tr>
									<th>Pathway Code</th>
									<td><?php echo DAO::getSingleValue($link, "SELECT CONCAT(PwayCode, ' ' , PathwayName) FROM lars201718.`Core_LARS_Framework` WHERE PwayCode = '{$framework->PwayCode}' AND FworkCode = '{$framework->framework_code}' AND ProgType = '{$framework->framework_type}'"); ?></td>
								</tr>
                                <tr>
                                    <th>LARS Duration</th>
                                    <td><?php echo DAO::getSingleValue($link, "SELECT Round(Duration) FROM lars201718.Core_LARS_ApprenticeshipFunding WHERE ApprenticeshipCode = '{$framework->framework_code}' and ApprenticeshipType='FWK' ORDER BY EffectiveFrom DESC LIMIT 0,1"); ?></td>
                                </tr>
								<?php } else { ?>
								<tr>
									<th>Standard Code</th>
									<td><?php echo DAO::getSingleValue($link, "SELECT CONCAT(StandardCode, ' ' , StandardName) FROM lars201718.Core_LARS_Standard WHERE StandardCode = '{$framework->StandardCode}'"); ?></td>
								</tr>
								<tr>
									<th>Programme Code</th>
									<td><?php echo $framework->programme_code;  ?></td>
								</tr>
								<tr>
									<th>Programme Parent</th>
									<td><?php echo $framework->programme_parent;  ?></td>
								</tr>
                                <tr>
                                    <th>LARS Duration</th>
                                    <td><?php echo DAO::getSingleValue($link, "SELECT Round(Duration) FROM lars201718.Core_LARS_ApprenticeshipFunding WHERE ApprenticeshipCode = '{$framework->StandardCode}' AND ApprenticeshipType='STD' ORDER BY EffectiveFrom DESC LIMIT 0,1"); ?></td>
                                </tr>
                                <tr>
                                    <th>Maximum Funding Cap</th>
                                    <td><?php echo DAO::getSingleValue($link, "SELECT Round(MaxEmployerLevyCap) FROM lars201718.Core_LARS_ApprenticeshipFunding WHERE ApprenticeshipCode = '{$framework->StandardCode}' AND ApprenticeshipType='STD' ORDER BY EffectiveFrom DESC LIMIT 0,1"); ?></td>
                                </tr>
                                <tr>
                                    <th>Standard Document</th>
                                    <td><a href="<?php echo DAO::getSingleValue($link, "SELECT UrlLink FROM lars201718.Core_LARS_Standard WHERE StandardCode = '{$framework->StandardCode}'"); ?>" target="_blank"><?php echo DAO::getSingleValue($link, "SELECT UrlLink FROM lars201718.Core_LARS_Standard WHERE StandardCode = '{$framework->StandardCode}'"); ?></a></td>
                                </tr>
								<?php } ?>
								<tr>
									<th>EPA Organisation</th>
									<td><?php echo DAO::getSingleValue($link, "SELECT CONCAT(EPA_ORG_ID, ' - ', EP_Assessment_Organisations) FROM central.`epa_organisations` WHERE EPA_ORG_ID = '{$framework->epa_org_id}'"); ?></td>
								</tr>
								<tr>
									<th>EPA Assessor</th>
									<td>
										<?php
										$sql = <<<SQL
SELECT CONCAT(
    COALESCE(title, ' '),
    `firstnames`, ' ',
    `surname`,
    ' (',
    COALESCE(`address1`, ''), ' ',
    COALESCE(`address4`, ' '), ' ',
    `postcode`, ') ',
    COALESCE(`email`, ''), ' '
  ) FROM epa_org_assessors WHERE id = '$framework->epa_org_assessor_id';
SQL;
										echo DAO::getSingleValue($link, $sql); ?>
									</td>
								</tr>
								<tr>
									<th>Off the Job Hours</th>
									<td><?php echo $framework->otj_hours; ?></td>
								</tr>
								<tr>
									<th>Prices</th>
									<td>
										TNP1: &pound;<?php echo $tnp1_total; ?> | 
										TNP2: &pound;<?php echo $framework->epa_price; ?> | 
										Total: &pound;<?php echo $tnp_total; ?> | 
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-5">
				<div class="table-responsive">
					<table class="table table-bordered">
						<tr>
							<th>Aim Reference</th>
							<th>16-18 Apps</th>
							<th>19-23 Apps</th>
							<th>24+ Apps</th>
							<th>ER Other</th>
						</tr>
						<?php
						$total = 0;
						foreach($frame as $f => $value1)
						{
							echo '<tr><td>' . $f . '</td>';
							foreach($frame[$f] as $g => $value2)
							{
								echo '<td>&pound; ' . sprintf("%.2f",$value2) . '</td>';
							}
							echo '</td>';
						}
						?>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h2 class="box-title">Qualifications (<?php echo $view->getRowCount(); ?>)</h2>
			</div>
			<div class="box-body">
				<?php if(!in_array($_SESSION['user']->type, [5, 12, 13])) { ?>
				<span class="btn btn-xs btn-primary" onclick="window.location.replace('do.php?_action=get_qualification&framework_id=<?php echo rawurlencode($framework->id); ?>');">
					<i class="fa fa-edit"></i>&nbsp;
					<i class="fa fa-graduation-cap"></i>&nbsp;
					Edit Qualifications
				</span>
				<?php if($isFramework){?>
					<span class="btn btn-xs btn-info" onclick="validateFramework();">
					<i class="fa fa-check-circle"></i>&nbsp;
					Validate
				</span>
					<?php } ?>
				<?php } ?>
				<?php if(in_array(DB_NAME, ["am_lead", "am_lead_demo"]) && $_SESSION['user']->isAdmin()) { ?>
				<span class="btn btn-xs btn-primary" onclick="window.location.replace('do.php?_action=edit_fwk_compliance_checklist&framework_id=<?php echo rawurlencode($framework->id); ?>');">
					<i class="fa fa-edit"></i>&nbsp;
					<i class="fa fa-list"></i>&nbsp; Edit Compliance Checklist
				</span>
				<?php } ?>
				<p></p>
				<div class="table-responsive">
					<?php echo $view->renderWithTitle($link, $framework->title); ?>
				</div>
			</div>
		</div>
	</div>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/highcharts.src.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/no-data-to-display.js"></script>

<script language="JavaScript">

	$(function() {

	});

	function validateFramework()
	{
		var postData = 'framework_id=' + <?php echo rawurlencode($framework->id); ?>

		var client = ajaxRequest('do.php?_action=ajax_framework_validation', postData);
		if(client != null)
		{
			var xml = client.responseText;
			alert(xml);
		}
	}

</script>

</body>
</html>