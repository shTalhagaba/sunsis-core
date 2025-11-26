<?php /* @var $vo Employer */ ?>

<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>View Hotel</title>
	<link rel="stylesheet" href="module_tracking/css/common.css?n=<?php echo time(); ?>" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="/assets/adminlte/plugins/datatables/jquery.dataTables.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
	</style>

</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">View Hotel</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<span class="btn btn-sm btn-default" onclick="window.location.href='do.php?_action=edit_hotel&edit=1&id=<?php echo $vo->id; ?>';"><i class="fa fa-edit"></i> Edit</span>
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
		<div class="col-lg-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h1 class="box-title text-bold">
						<?php echo $vo->legal_name; ?>
					</h1> / <small><?php echo $vo->trading_name; ?></small>
					<?php
					echo '<span style="display: inline;"> ';
					$trophy = $vo->company_rating;
					if($trophy == 'G')
						echo '<i title="GOLD Hotel" class="fa fa-trophy fa-2x" style="color: gold;"></i>';
					elseif($trophy == 'S')
						echo '<i title="Silver Hotel" class="fa fa-trophy fa-2x" style="color: silver;"></i>';
					elseif($trophy == 'B')
						echo '<i title="Bronze Hotel" class="fa fa-trophy fa-2x" style="color: #cd7f32;"></i>';
					echo '</span>';
					?>
					<div class="pull-right">
						<span class="label <?php echo $vo->active == '1'?'label-success':'label-danger'; ?>"><?php echo $vo->active=='1'?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> Active</span>
						<span class="label <?php echo $vo->levy_employer == '1'?'label-success':'label-danger'; ?>"><?php echo $vo->levy_employer=='1'?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> Levy Hotel</span>
						<span class="label <?php echo $vo->health_safety == '1'?'label-success':'label-danger'; ?>"><?php echo $vo->health_safety=='1'?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> Health and Safety</span>
						<span class="label <?php echo $vo->ono == '1'?'label-success':'label-danger'; ?>"><?php echo $vo->ono=='1'?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> ONA</span>
						<span class="label <?php echo $vo->due_diligence == '1'?'label-success':'label-danger'; ?>"><?php echo $vo->due_diligence=='1'?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> Due Diligence</span>

					</div>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-2">
							<dl class="dl-horizontal">
								<dt>EDRS:</dt><dd><span class="text-muted"><?php echo $vo->edrs; ?></span></dd>
								<dt>Company Number:</dt>
								<dd><span class="text-muted"><a href="https://beta.companieshouse.gov.uk/company/<?php echo $vo->company_number; ?>" target="_blank"><?php echo $vo->company_number; ?></a></span></dd>
								<dt>VAT Number:</dt><dd><span class="text-muted"><?php echo $vo->vat_number; ?></span></dd>
								<dt>Retailer Code:</dt><dd><span class="text-muted"><?php echo $vo->retailer_code; ?></span></dd>
								<dt>Hotel Code:</dt><dd><span class="text-muted"><?php echo $vo->employer_code; ?></span></dd>
							</dl>
						</div>
						<div class="col-md-5">
							<dl class="dl-horizontal">
								<dt>Sector:</dt><dd><span class="text-muted"><?php echo htmlspecialchars((string)$sector); ?></span></dd>
								<dt>Group:</dt><dd><span class="text-muted"><?php echo htmlspecialchars((string)$group_employer); ?></span></dd>
								<dt>Region:</dt><dd><span class="text-muted"><?php echo htmlspecialchars((string)$vo->region); ?></span></dd>
								<dt>Size:</dt><dd><span class="text-muted"><?php echo htmlspecialchars((string)$size); ?></span></dd>
								<dt>On-site Employees:</dt><dd><span class="text-muted"><?php echo htmlspecialchars((string)$vo->site_employees); ?></span></dd>
							</dl>
						</div>
						<div class="col-md-5">
							<dl class="dl-horizontal">
								<?php if($vo->levy_employer == '1') {?>
								<dt>Levy Amount:</dt><dd><span class="text-muted"><?php echo $vo->levy; ?></span></dd>
								<?php } ?>
								<dt>URL:</dt><dd><span class="text-muted"><small><?php echo htmlspecialchars((string)$vo->url); ?></small></span></dd>
								<dt>Account Manager:</dt><dd><span class="text-muted"><small><?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE username = '{$vo->creator}'"); ?></small></span></dd>
							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">

		<div class="col-sm-9 well well-sm">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab1" data-toggle="tab">Locations <label class="label label-info"><?php echo $locations_count; ?></label></a></li>
					<li><a href="#tab3" data-toggle="tab">System Users <label class="label label-info"><?php echo $users_count; ?></label></a></li>
					<li><a href="#tab4" data-toggle="tab">CRM Notes <label class="label label-info"><?php echo $org_crm_notes_count; ?></label></a></li>
					<li><a href="#tab5" data-toggle="tab">CRM Contact <label class="label label-info"><?php echo $crm_contacts_count; ?></label></a></li>
				</ul>
				<div class="tab-content">
					<div class="active tab-pane" id="tab1">
						<p><span onclick="window.location.href='do.php?_action=edit_location&organisations_id=<?php echo $vo->id; ?>&back=hotel'" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Add New Location</span></p>
						<div class="table-responsive"><?php $this->renderLocations($link,'read_hotel'); ?></div>
					</div>
					<div class="tab-pane" id="tab3">
						<p><span onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=Admin&people_type=1'" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Add New Administrator</span></p>
						<div class="table-responsive"><?php $this->renderSystemUsers($link); ?></div>
					</div>
					<div class="tab-pane" id="tab4">
						<p><span onclick="window.location.href='do.php?_action=edit_crm_note&mode=new&organisations_id=<?php echo $vo->id; ?>&organisation_type=read_workplace'" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Add New CRM Note</span></p>
						<div class="table-responsive"><?php $this->renderOrganisationCRMNotes($link,'read_hotel'); ?></div>
                        <?php /*<div class="table-responsive"><?php $this->renderCRMNotes($link,'read_hotel'); ?></div> */ ?>
					</div>
					<div class="tab-pane" id="tab5">
						<p><span onclick="window.location.href='do.php?_action=edit_crm_contact&org_type=hotel&org_id=<?php echo $vo->id; ?>'" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Add New CRM Contact</span></p>
						<div class="table-responsive"><?php $this->renderCRMContacts($link); ?></div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-3">
			<form name="frmUploadFile" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=save_employer_repository" ENCTYPE="multipart/form-data">
				<input type="hidden" name="_action" value="save_employer_repository" />
				<input type="hidden" name="emp_id" value="<?php echo $vo->id;?>" />
				<div class="box box-primary">
					<div class="box-header with-border"><h1 class="box-title"><span class="fa fa-files-o"></span> File Repository</h1></div>
					<div class="box-body" style="max-height: 250px; overflow-y: scroll;">
						<ul class="list-group list-group-unbordered">
							<?php
							$repository = Repository::getRoot().'/employers/'.$vo->id;
							$files = Repository::readDirectory($repository);
							if(count($files) == 0){
								echo '<i>No files uploaded</i>';
							}
							foreach($files as $f)
							{
								if($f->isDir()){
									continue;
								}
								$ext = new SplFileInfo($f->getName());
								$ext = $ext->getExtension();
                                $href2 = "do.php?_action=delete_file&path=employers/" . $vo->id . '/' . "&f=" . rawurlencode($f->getName()) . "&redirect=" . rawurlencode('do.php?_action=read_hotel&id='.$vo->id);

                                $image = 'fa-file';
								if($ext == 'doc' || $ext == 'docx')
									$image = 'fa-file-word-o';
								elseif($ext == 'pdf')
									$image = 'fa-file-pdf-o';
								elseif($ext == 'txt')
									$image = 'fa-file-text-o';
								echo '<li class="list-group-item">' .
                                    '<a href="do.php?_action=downloader&path=/'.DB_NAME.'/employers/'.$vo->id. "&f=" . $f->getName() . '"><i class="fa '.$image.'"></i> ' . htmlspecialchars((string)$f->getName()) . '</a>' .
                                    '<br><span class="direct-chat-timestamp "><i class="fa fa-clock-o"></i> <small>' . date("d/m/Y H:i:s", $f->getModifiedTime()) .'</small></span>' .
                                    '<a href='.$href2.' style="margin-left: 10px;color:red"><i class="fa fa-trash"></i></a>'.
                                    '</li>';
							}
							?>
						</ul>
					</div>
					<div class="box-footer">
						<input class="compulsory" type="file" name="uploaded_employer_file" />
						<span id="uploadFileButton" class="btn btn-sm btn-primary pull-right" onclick="uploadFile();"><i class="fa fa-upload"></i></span>
					</div>
				</div>
			</form>
		</div>
	</div>

</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js?n=<?php echo time(); ?>" type="text/javascript"></script>
<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.js"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>

<script>
	$(function() {

		$('#tblLearners').DataTable({
			"paging": false,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": true
		});

	});

	function uploadFile()
	{
		var myForm = document.forms["frmUploadFile"];
		if(validateForm(myForm) == false)
		{
			return false;
		}
		myForm.submit();
	}

	function deleteOrganisationCRMContact(contact_id)
	{
		if(!confirm('This action cannot be undone, are you sure to continue?'))
			return;

		var client = ajaxRequest('do.php?_action=baltic_read_employer&subaction=deleteOrganisationCRMContact&contact_id='+encodeURIComponent(contact_id));

		alert(client.responseText);

		window.location.reload();
	}
</script>
</body>
</html>
