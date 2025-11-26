<!DOCTYPE html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $page_title; ?></title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css" />
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

		.disabled {
			pointer-events: none;
			opacity: 0.4;
		}
		input[type=checkbox] {
			transform: scale(1.4);
		}
	</style>
</head>

<body>

	<div class="row">
		<div class="col-lg-12">
			<div class="banner">
				<div class="Title" style="margin-left: 6px;"><?php echo $page_title; ?></div>
				<div class="ButtonBar">
					<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
					<?php if ($enable_save) { ?>
						<span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
					<?php } ?>
					<?php if ($enable_save && !is_null($vo->id) && $vo->id != '') { ?>
						<span class="btn btn-sm btn-danger" onclick="delete_record(<?php echo $vo->id; ?>);"><i class="fa fa-trash"></i> Delete</span>
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
			<div class="col-sm-6">
				<div class="callout">
					<label class="col-sm-4 control-label fieldLabel_optional">Learner Name:</label>
					<div class="col-sm-8 text-bold"><?php echo $pot_vo->firstnames . ' ' . $pot_vo->surname; ?></div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<form class="form-horizontal" name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input type="hidden" name="_action" value="save_tr_manager_comment" />
			<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
			<input type="hidden" name="tr_id" value="<?php echo $vo->tr_id ?>" />
			<div class="col-md-6">

				<div class="box box-primary">

					<div class="box-body">
						<div class="form-group">
							<label for="rag" class="col-sm-4 control-label fieldLabel_optional">RAG:</label>
							<div class="col-sm-8">
								<?php
								$rag_ddl = [
									['R', 'Red'],
									['A', 'Amber'],
									['G', 'Green']
								];
								echo HTML::selectChosen('rag', $rag_ddl, $vo->rag, true, false);
								?>
							</div>
						</div>
						<div class="form-group">
							<label for="date" class="col-sm-4 control-label fieldLabel_optional">To be processed deadline:</label>
							<div class="col-sm-8">
								<?php echo HTML::datebox('to_be_processed_deadline', $vo->to_be_processed_deadline, false); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="comment_type" class="col-sm-4 control-label fieldLabel_compulsory">Comment Type:</label>
							<div class="col-sm-8">
								<?php
								$comment_types = array(array("ER", "Employer reference comment"), array("LP", "Learner progress comment"), array("FS", "Functional Skills"));
								echo HTML::selectChosen('comment_type', $comment_types, $vo->comment_type, true, true);
								?>
							</div>
						</div>
						<div class="form-group">
							<label for="comment" class="col-sm-4 control-label fieldLabel_compulsory">Comments:</label>
							<div class="col-sm-8">
								<textarea class="compulsory" name="comment" id="comment" rows="10" style="width: 100%;"><?php echo $vo->comment; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="for_caseload" class="col-sm-4 control-label fieldLabel_optional">For Caseload Tab</label>
							<div class="col-sm-8">
                                				<?php echo HTML::checkbox('for_caseload', 1, $vo->for_caseload == '1' ? true : false); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="date" class="col-sm-4 control-label fieldLabel_optional">Functional skills to be processed:</label>
							<div class="col-sm-8">
								<?php echo HTML::datebox('fs_to_be_processed', $vo->fs_to_be_processed, false); ?>
							</div>
						</div>
					</div>

				</div>

			</div>

			<?php if ($other_records != '') { ?>
				<div class="col-md-6">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h2 class="box-title">Other Records</h2>
							<div class="box-body">
								<?php echo $other_records; ?>
							</div>
						</div>
					</div>
				<?php } ?>

		</form>
	</div>
	<div id="dialogDeleteFile" style="display:none" title="Delete file"></div>
	<br>

	<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
	<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
	<script src="/assets/adminlte/dist/js/app.min.js"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker.js"></script>

	<script language="JavaScript">
		$(function() {


		});

		function save() {
			var myForm = document.forms["form1"];
			if (validateForm(myForm) == false) {
				return false;
			}
			myForm.submit();
		}

		function delete_record(record_id) {
			if (!confirm('This action cannot be undone, are you sure you want to delete this record?'))
				return;
			var client = ajaxRequest('do.php?_action=edit_tr_manager_comment&ajax_request=true&id=' + encodeURIComponent(record_id));
			alert(client.responseText);
			window.history.back();
		}
	</script>

</body>

</html>