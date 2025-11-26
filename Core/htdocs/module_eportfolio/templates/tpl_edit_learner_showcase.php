<?php /* @var $tr TrainingRecord */ ?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Learner Showcase</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote-bs3.css">
	<link href="/assets/adminlte/plugins/toastr/toastr.min.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->


	<style>
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
		.note-group-select-from-files {
			display: none;
		}
	</style>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Showcase | <?php echo $tr->firstnames . ' ' . $tr->surname; ?></div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
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
		<div class="box box-primary">
			<div class="box-body">
				<div class="row">
					<div class="col-sm-8">
						<div class="box box-solid box-success">
							<div class="box-header">
								<span class="box-title"><b>Details</b></span>
							</div>
							<div class="box-body">
								<form autocomplete="off" class="form-horizontal" name="frmShowcase" id="frmShowcase " action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
									<input type="hidden" name="tr_id" value="<?php echo $tr->id ?>" />
									<input type="hidden" name="_action" value="edit_learner_showcase"/>
									<input type="hidden" name="subaction" value="save"/>

									<textarea name="sc_content" id="sc_content" style="width: 100%;">
										<?php echo $showcase->sc_content; ?>
									</textarea>
								</form>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<form name="frmUploadFile" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" ENCTYPE="multipart/form-data">
							<input type="hidden" name="_action" value="edit_learner_showcase" />
							<input type="hidden" name="tr_id" value="<?php echo $tr->id;?>" />
							<input type="hidden" name="subaction" value="upload_file" />
							<div class="box box-primary">
								<div class="box-header with-border"><h1 class="box-title"><span class="fa fa-files-o"></span> File Repository</h1></div>
								<div class="box-body" style="max-height: 250px; overflow-y: scroll;">
									<ul class="list-group list-group-unbordered">
										<?php
										$repository = Repository::getRoot().'/'.$tr->username.'/showcases';
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
											$image = 'fa-file';
											if($ext == 'doc' || $ext == 'docx')
												$image = 'fa-file-word-o';
											elseif($ext == 'pdf')
												$image = 'fa-file-pdf-o';
											elseif($ext == 'txt')
												$image = 'fa-file-text-o';
											echo '<li class="list-group-item"><a href="do.php?_action=downloader&path=/'.DB_NAME.'/'.$tr->username.'/showcases/'. "&f=" . $f->getName() . '"><i class="fa '.$image.'"></i> ' . htmlspecialchars((string)$f->getName()) . '</a><br><span class="direct-chat-timestamp "><i class="fa fa-clock-o"></i> <small>' . date("d/m/Y H:i:s", $f->getModifiedTime()) .'</small></span></li>';
										}
										?>
									</ul>
								</div>
								<div class="box-footer">
									<input class="compulsory" type="file" name="input_file_field" />
									<span id="uploadFileButton" class="btn btn-sm btn-primary pull-right" onclick="uploadFile();"><i class="fa fa-upload"></i></span>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/summernote/summernote.min.js"></script>
<script src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>

<script language="JavaScript">

	$(function() {
		$('#sc_content').summernote({
			toolbar: [
				['style', ['bold', 'italic', 'underline']],
				['fontsize', ['fontsize']],
				['para', ['ul', 'ol', 'paragraph']],
				['height', ['height']],
				['insert', ['link', 'hr']]
			],
			height: 650
		});


		toastr.options = {
			"closeButton": true,
			"progressBar": true,
			"preventDuplicates": true,
			"positionClass": "toast-top-center",
			"onclick": null,
			"showDuration": "400",
			"hideDuration": "1000",
			"timeOut": "1000",
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "fadeIn",
			"hideMethod": "fadeOut"
		};

		var toastr_message = '<?php echo $toastr_message; ?>';
		if(toastr_message != '')
		{
			toastr.success(toastr_message);
		}

	});

	function save()
	{
		var frmShowcase = document.forms["frmShowcase"];

		frmShowcase.submit();
	}

	function uploadFile()
	{
		var frmUploadFile = document.forms["frmUploadFile"];

		frmUploadFile.submit();
	}

</script>

</body>
</html>