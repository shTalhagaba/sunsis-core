
<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>EPA Organisation</title>
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
		.disabled {
			pointer-events: none;
			opacity: 0.4;
		}
	</style>

</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">EPA Organisation</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default"
				      onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i
					class="fa fa-arrow-circle-o-left"></i> Cancel</span>
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

<div class="row">
	<div class="col-sm-4">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h5 class="text-bold lead"><?php echo $EPA_Org->EPA_ORG_ID . ' / ' . $EPA_Org->EP_Assessment_Organisations; ?></h5>
				<label class="label label-info"><?php echo $EPA_Org->Organisation_type; ?></label>
			</div>
			<div class="box-body">
				<span class="text-bold">Contact:</span><br>
				<i class="fa fa-user"></i> <?php echo $EPA_Org->Contact_Name; ?><br>
				<?php echo $EPA_Org->Contact_address1; ?><br>
				<?php echo $EPA_Org->Contact_address2; ?><br>
				<?php echo $EPA_Org->Contact_address3; ?><br>
				<?php echo $EPA_Org->Contact_address4; ?><br>
				<i class="fa fa-map-marker"></i> <a target="_blank" href="https://www.google.co.uk/maps?f=q&hl=en&q=<?php echo urlencode($EPA_Org->Postcode); ?>"> <?php echo $EPA_Org->Postcode; ?></a><br>
				<i class="fa fa-phone"></i> <?php echo $EPA_Org->Contact_number; ?><br>
				<i class="fa fa-envelope"></i> <a href="mailto:<?php echo $EPA_Org->Contact_email; ?>"><?php echo $EPA_Org->Contact_email; ?></a><br>
				<i class="fa fa-external-link"></i> <a target="_blank" href="<?php echo $EPA_Org->Link_to_website; ?>"><?php echo $EPA_Org->Link_to_website; ?></a><br>
				<hr>
				<span class="text-bold">Delivery Areas:</span><br>
				<?php echo $EPA_Org->Delivery_Area_1 != '' ? $EPA_Org->Delivery_Area_1 . '<br>' : ''; ?>
				<?php echo $EPA_Org->Delivery_Area_2 != '' ? $EPA_Org->Delivery_Area_2 . '<br>' : ''; ?>
				<?php echo $EPA_Org->Delivery_Area_3 != '' ? $EPA_Org->Delivery_Area_2 . '<br>' : ''; ?>
				<?php echo $EPA_Org->Delivery_Area_4 != '' ? $EPA_Org->Delivery_Area_4 . '<br>' : ''; ?>
			</div>
		</div>
	</div>
	<div class="col-sm-8">
		<div class="box box-default">
			<div class="box-body">

				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab1" data-toggle="tab"> Standards</a></li>
						<li><a href="#tab2" data-toggle="tab"> EPA Assessors</a></li>
					</ul>
					<div class="tab-content">
						<div class="active tab-pane" id="tab1">
							<div class="table-responsive">
								<table class="table table-bordered table-hover">
									<thead>
									<tr>
										<th>Standard Code</th>
										<th>Standard Name</th>
										<th>Level</th>
										<th>Dates</th>
										<th>SSA</th>
										<th>Created On</th>
										<th>Modified On</th>
									</tr>
									</thead>
									<tbody>
									<?php
									foreach($EPA_Org_Standards AS $Standard)
									{

										echo "<tr>";
										echo "<td>{$Standard['StandardCode']}</td>";
										$link = $Standard['UrlLink'] != '' ? '<a target="_blank" href="'.$Standard['UrlLink'].'"><i title="Link to specs" class="fa fa-external-link"></i> </a>' : '';
										echo "<td>{$Standard['StandardName']} &nbsp; $link</td>";
										echo "<td>{$Standard['NotionalEndLevel']}</td>";
										echo "<td>";
										echo "Effective&nbsp;From:&nbsp;" . str_replace(" ", "&nbsp;", (string) Date::toShort($Standard['EffectiveFrom'])) . "<br>";
										echo "Effective&nbsp;To:&nbsp;" . str_replace(" ", "&nbsp;", (string) Date::toShort($Standard['EffectiveTo'])) . "<br>";
										echo "</td>";
										echo "<td>SSA1: {$Standard['SSA1']}<br>SSA2: {$Standard['SSA2']}</td>";
										echo "<td>" . Date::toShort($Standard['Created_On']) . "</td>";
										echo "<td>" . Date::toShort($Standard['Modified_On']) . "</td>";
										echo "</tr>";
									}
									?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane" id="tab2">
							<span class="btn btn-primary btn-xs" onclick="window.location.href='do.php?_action=edit_epa_org_assessor&id=&EPA_Org_ID=<?php echo $EPA_Org->EPA_ORG_ID;?>'">Add New EPA Assessor</span>
							<div class="table-responsive">
								<table class="table table-bordered table-hover">
									<thead>
									<tr>
										<th>Name</th>
										<th>Address</th>
										<th>Email</th>
										<th>Telephone</th>
										<th></th>
									</tr>
									</thead>
									<tbody>
										<?php
										if(count($EPA_Org_Assessors) == 0)
											echo '<tr><td colspan="5"><i class="text-muted">No records found.</i></td> </tr>';
										foreach($EPA_Org_Assessors AS $Assessor)
										{
											echo '<tr>';
											echo '<td>' . $Assessor['title'] . ' ' . $Assessor['firstnames'] . ' ' . $Assessor['surname'] . '</td>';
											echo '<td>';
											echo $Assessor['address1'] != '' ? $Assessor['address1'] . '<br>' : '';
											echo $Assessor['address2'] != '' ? $Assessor['address2'] . '<br>' : '';
											echo $Assessor['address3'] != '' ? $Assessor['address3'] . '<br>' : '';
											echo $Assessor['address4'] != '' ? $Assessor['address4'] . '<br>' : '';
											echo $Assessor['postcode'] != '' ? $Assessor['postcode'] . '<br>' : '';
											echo '</td>';
											echo '<td><a href="mailto:' . $Assessor['email'] . '">' . $Assessor['email'] . '</a></td>';
											echo '<td>' . $Assessor['telephone'] . '</td>';
											echo '<td>';
											echo '<span class="btn btn-info btn-xs" title="Edit this record" onclick="window.location.href=\'do.php?_action=edit_epa_org_assessor&id='.$Assessor['id'].'&EPA_Org_ID='.$EPA_Org->EPA_ORG_ID .'\'"><i class="fa fa-edit"></i> </span>&nbsp;&nbsp;';
											echo '<span class="btn btn-danger btn-xs" title="Delete this record" onclick="deleteEPAOrgAssessor(\''.$Assessor['id'].'\');"><i class="fa fa-trash"></i> </span>';
											echo '</td>';
											echo '</tr>';
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>


			</div>
		</div>
	</div>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>

<script>
	function deleteEPAOrgAssessor(id)
	{
		if(!confirm('Are you sure you want to delete this EPA Assessor record?'))
			return;

		window.location.href="do.php?_action=edit_epa_org_assessor&subaction=deleteEPAOrgAssessor&id="+id;
	}
</script>
</body>
</html>
