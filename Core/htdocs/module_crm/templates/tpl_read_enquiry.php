<?php /* @var $enquiry Enquiry */ ?>

<!DOCTYPE html>

<head xmlns="http://www.w3.org/1999/html">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Enquiry Detail</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css" />
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/jquery.timepicker.css" />
	<link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote-bs3.css">
	<link href="/assets/adminlte/plugins/toastr/toastr.min.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		.disabled {
			pointer-events: none;
			opacity: 0.4;
		}

		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
	</style>

</head>

<body>
	<div class="row">
		<div class="col-lg-12">
			<div class="banner">
				<div class="Title" style="margin-left: 6px;">Enquiry Detail</div>
				<div class="ButtonBar">
					<span class="btn btn-xs btn-default"
						onclick="window.location.href='do.php?_action=view_enquiries';"><i
							class="fa fa-arrow-circle-o-left"></i> Back to Enquiries</span>
					<?php echo $back_to_org; ?>
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
						<div class="row">
							<div class="col-sm-5">
								<h1 class="box-title text-bold"><?php echo $enquiry->enquiry_title; ?></h1><br>
								<span class="text-bold">Owner: </span><span class="text-muted"
									id="lblOwnerName"><?php echo $enquiry->getOwnerName($link); ?></span>
								&nbsp;&nbsp;&nbsp;
								<?php if ($_SESSION['user']->isAdmin() || $enquiry->created_by == $_SESSION['user']->id) { ?>
									<button type="button"
										class="btn btn-xs btn-primary <?php echo $enquiry->isLocked() ? 'disabled' : ''; ?>"
										onclick="$('#changeOwnerModal').modal('show');"><i class="fa fa-edit"></i>
										Change</button>
								<?php } ?>
								<br><span class="text-bold">Created on: </span><span
									class="text-muted"><?php echo Date::to($enquiry->created, Date::DATETIME); ?></span>
								|
								<span class="text-bold">Last modified on: </span><span
									class="text-muted"><?php echo Date::to($enquiry->modified, Date::DATETIME); ?></span>
								<br>
							</div>
							<div class="col-sm-5">
								<div class="btn-group btn-group-justified" data-toggle="buttons">
									<label
										class="btn btn-success btn-app <?php echo $enquiry->isInProgress() ? 'active' : ''; ?> lockable"
										title="In Progress">
										<input type="radio" name="status" value="2" autocomplete="off"
											<?php echo $enquiry->status == '2' ? 'checked="checked"' : ''; ?>>
										<i class="fa fa-hourglass-end"></i>
									</label>
									<label
										class="btn btn-success btn-app <?php echo $enquiry->isSuccessful() ? 'active' : ''; ?> lockable"
										title="Successful (Selecting this status will switch on the button 'Convert to Lead' to progress to next stage i.e. Lead)">
										<input type="radio" name="status" value="3" autocomplete="off"
											<?php echo $enquiry->status == '3' ? 'checked="checked"' : ''; ?>>
										<i class="fa fa-thumbs-o-up"></i>
									</label>
									<label
										class="btn btn-success btn-app <?php echo $enquiry->isUnsuccessful() ? 'active' : ''; ?> lockable"
										title="Unsuccessful">
										<input type="radio" name="status" value="4" autocomplete="off"
											<?php echo $enquiry->status == '4' ? 'checked="checked"' : ''; ?>>
										<i class="fa fa-thumbs-o-down"></i>
									</label>
								</div>
							</div>
							<div class="col-sm-2">
								<?php echo $enquiry->isNew() ? '<div class="pull-right bg-green" style="padding: 5px; border-radius: 5px;">New</div>' : ''; ?>
							</div>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-4">
								<div class="callout callout-default">
									<?php
									echo '<span class="text-bold">Main Contact Person:</span><br>';
									echo $company_contact->contact_title . ' ' . $company_contact->contact_name . '<br>';
									echo $company_contact->contact_email != '' ? '<i class="fa fa-envelope"></i> <a class="text-blue" href="mailto: ' . $company_contact->contact_email . '">' . $company_contact->contact_email . '</a><br>' : '';
									echo '<i class="fa fa-phone"></i> ' . $company_contact->contact_telephone . '<br>';
									echo '<i class="fa fa-mobile"></i> ' . $company_contact->contact_mobile . '<br>';
									?>
								</div>
							</div>
							<div class="col-md-4">
								<dl class="dl-horizontal small">
									<dt>Type:</dt>
									<dd><span
											class="text-muted"><?php echo $enquiry->getListEnquiryType($enquiry->enquiry_type); ?></span>
									</dd>
									<dt>Product:</dt>
									<dd><span
											class="text-muted"><?php echo htmlspecialchars((string)$enquiry->getIndustryDescription($link)); ?></span>
									</dd>
									<dt>Source of enquiry:</dt>
									<dd><span
											class="text-muted"><?php echo htmlspecialchars((string)$enquiry->source); ?></span>
									</dd>
								</dl>
							</div>
							<div class="col-md-4">
								<dl class="dl-vertical small">
									<dt>Description:</dt>
									<dd style="max-height: 150px; overflow-y: scroll;"><span
											class="text-muted small"><?php echo nl2br((string) ($lead->description ?? '')); ?></span>
									</dd>
								</dl>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<?php if ($enquiry->isEditable()) { ?>
									<span class="btn btn-xs btn-primary"
										onclick="window.location.href='do.php?_action=edit_enquiry&id=<?php echo $enquiry->id; ?>&org_id=<?php echo $enquiry->company_id; ?>&org_type=<?php echo $enquiry->company_type; ?>';"><i
											class="fa fa-edit"></i> Edit</span> &nbsp;
								<?php } ?>
								<?php if ($enquiry->isSuccessful() && !$enquiry->isLocked()) { ?>
									<span id="btnConvertEnquiryToLead" class="btn btn-xs btn-primary"
										onclick="convertEnquiryToLead();"><i class="fa fa-tick"></i> Convert to Lead</span>
									&nbsp;
								<?php } ?>
								<?php if (!$enquiry->isLocked()) { ?>
									<form name="frmDeleteEnquiry"
										action="<?php echo $_SERVER['PHP_SELF']; ?>?_action=ajax_helper"
										style="display: inline;" method="post">
										<input type="hidden" name="enquiry_id_to_delete"
											value="<?php echo $enquiry->id; ?>">
										<input type="hidden" name="subaction" value="delete_enquiry">
										<span id="btnDeleteEnquiry" class="btn btn-xs btn-danger"><i
												class="fa fa-trash"></i> Delete</span> &nbsp;
									</form>
								<?php } ?>
								<?php if ($enquiry->isConverted()) { ?>
									<span class="btn btn-xs btn-success"
										onclick="window.location.href='do.php?_action=read_lead&id=<?php echo $enquiry->getLinkedLeadID($link); ?>'">
										<i class="fa fa-folder"></i> Linked Lead
									</span> &nbsp;
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">

			<div class="col-sm-8">
				<div class="nav-tabs-custom bg-gray-light">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab1" data-toggle="tab"> Activities <label
									class="label label-info"><?php echo $activities_count; ?></label></a></li>
						<li><a href="#tabContacts" data-toggle="tab"> Contacts <label
									class="label label-info"><?php echo $contacts_count; ?></label></a></li>
						<li><a href="#tab2" data-toggle="tab"> Comments <label
									class="label label-info"><?php echo $comments_count; ?></label></a></li>
						<li><a href="#tab3" data-toggle="tab"> Audit/Log</a></li>
					</ul>
					<div class="tab-content">
						<div class="active tab-pane callout" id="tab1">
							<div class="row">
								<div class="col-sm-12">
									<?php if ($enquiry->isEditable()) { ?>
										<span class="btn btn-xs btn-primary" onclick="$('#taskModal').modal('show');"><i
												class=""></i> Add Task</span> &nbsp;
										<span class="btn btn-xs btn-primary" onclick="$('#phoneModal').modal('show');"><i
												class=""></i> Add Phone Call</span> &nbsp;
										<span class="btn btn-xs btn-primary" onclick="$('#meetingModal').modal('show');"><i
												class=""></i> Add Meeting</span> &nbsp;
										<span class="btn btn-xs btn-primary" onclick="$('#emailModal').modal('show')"><i
												class=""></i> Compose Email</span> &nbsp;
									<?php } ?>
									<p><br></p>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="nav-tabs-custom">
										<ul class="nav nav-tabs">
											<li class="active" onclick="loadActivities('combined');">
												<a href="#tab11" data-toggle="tab">Combined View <label
														class="label label-info"><?php echo $activities_count; ?></label></a>
											</li>
											<li onclick="loadActivities('task');"><a href="#tab12"
													data-toggle="tab">Tasks <label
														class="label label-info"><?php echo $enquiry->activityCount($link, 'task'); ?></label></a>
											</li>
											<li onclick="loadActivities('phone');"><a href="#tab13"
													data-toggle="tab">Phone Calls <label
														class="label label-info"><?php echo $enquiry->activityCount($link, 'phone'); ?></label></a>
											</li>
											<li onclick="loadActivities('meeting');"><a href="#tab14"
													data-toggle="tab">Meetings <label
														class="label label-info"><?php echo $enquiry->activityCount($link, 'meeting'); ?></label></a>
											</li>
											<li onclick="loadActivities('email');"><a href="#tab15"
													data-toggle="tab">Emails <label
														class="label label-info"><?php echo $enquiry->activityCount($link, 'email'); ?></label></a>
											</li>
										</ul>
										<div class="tab-content">
											<div class="tab-pane active" id="tab11">
												<div class="table-responsive" id="divCombined"></div>
											</div>
											<div class="tab-pane" id="tab12">
												<div class="table-responsive" id="divTasks"></div>
											</div>
											<!-- /.tab-pane -->
											<div class="tab-pane" id="tab13">
												<div class="table-responsive" id="divPhones"></div>
											</div>
											<!-- /.tab-pane -->
											<div class="tab-pane" id="tab14">
												<div class="table-responsive" id="divMeetings"></div>
											</div>
											<div class="tab-pane" id="tab15">
												<div class="table-responsive" id="divEmails"></div>
											</div>
											<!-- /.tab-pane -->
										</div>
										<!-- /.tab-content -->
									</div>
									<!-- nav-tabs-custom -->
								</div>
							</div>
						</div>

						<div class="tab-pane" id="tabContacts">
							<span class="btn btn-xs btn-primary"
								onclick="window.location.href='do.php?_action=edit_crm_contacts&contact_id=&org_id=<?php echo $enquiry->company_id; ?>&org_type=<?php echo $enquiry->company_type; ?>'"><i
									class="fa fa-user"></i> Add Contact</span> &nbsp;
							<div class="table-responsive">
								<table class="table table-bordered table-condensed">
									<thead>
										<tr>
											<th>Title</th>
											<th>Name</th>
											<th>Decision Maker</th>
											<th>Telephone</th>
											<th>Mobile</th>
											<th>Email</th>
											<th>Job</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php
										if ($enquiry->company_type == "pool") {
											$records = DAO::getResultset($link, "SELECT * FROM pool_contact WHERE pool_id = '{$company->id}' ORDER BY contact_name", DAO::FETCH_ASSOC);
										} elseif ($enquiry->company_type == "employer") {
											$records = DAO::getResultset($link, "SELECT * FROM organisation_contact WHERE org_id = '{$company->id}' ORDER BY contact_name", DAO::FETCH_ASSOC);
										}
										foreach ($records as $row) {
											echo '<tr>';
											echo '<td>' . $row['contact_title'] . '</td>';
											echo '<td>' . $row['contact_name'] . '</td>';
											echo $row['decision_maker'] == '1' ? '<td class="bg-green">Yes</td>' : '<td>No</td>';
											echo '<td>' . $row['contact_telephone'] . '</td>';
											echo '<td>' . $row['contact_mobile'] . '</td>';
											echo '<td>' . $row['contact_email'] . '</td>';
											echo $row['job_role'] != '' ? '<td>' . $row['job_role'] . '</td>' : '<td>' . $row['job_title'] . '</td>';
											echo '<td>';
											echo '<span class="btn btn-xs btn-primary" onclick="window.location.href=\'do.php?_action=edit_crm_contacts&contact_id=' . $row['contact_id'] . '&org_id=' . $enquiry->company_id . '&org_type=' . $enquiry->company_type . '\'"><i class="fa fa-edit"></i></span> &nbsp; ';
											echo '</td>';
											echo '</tr>';
										}
										?>
									</tbody>
								</table>
							</div>
						</div>

						<div class="tab-pane" id="tab2">
							<div class="row">
								<div class="col-sm-12">
									<?php
									echo EntityComment::renderModalOpenButton();
									?>
									<p></p>
								</div>
								<div class="col-sm-12">
									<div class="table-responsive">
										<?php
										echo EntityComment::renderComments($link, 'enquiry', $enquiry->id);
										?>
									</div>
								</div>
							</div>
						</div>

						<div class="tab-pane" id="tab3" id="auditsTab">
							<div class="row">
								<div class="col-sm-12">
									<?php
									echo $enquiry->renderNotes($link);
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tabAddress" data-toggle="tab"> Company</a></li>
						<li><a href="#tabFiles" data-toggle="tab"> Files
								<?php echo '<label class="label label-info">' . $enquiry->filesCount($link) . '</label>'; ?></a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="active tab-pane" id="tabAddress">
							<?php include_once(__DIR__ . '/partials/tpl_crm_company_info.php'); ?>
						</div>
						<div class="tab-pane" id="tabFiles">
							<?php include_once(__DIR__ . '/partials/tpl_crm_entity_files.php'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>

	<div class="modal fade" id="changeOwnerModal" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<form autocomplete="off" class="form-horizontal" method="post" name="frmChangeOwner" id="frmChangeOwner"
					method="post" action="do.php?_action=ajax_helper">
					<input type="hidden" name="formName" value="frmChangeOwner" />
					<input type="hidden" name="subaction" value="update_owner" />
					<input type="hidden" name="entity_id" value="<?php echo $enquiry->id; ?>" />
					<input type="hidden" name="entity_type" value="<?php echo get_class($enquiry); ?>" />
					<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title text-bold">Change Owner</h5>
					</div>
					<div class="modal-body">
						<div class="callout callout-info small"><i class="fa fa-info-circle"></i> Use this functionality
							to change owner of this enquiry.</div>
						<div class="control-group small">
							<label class="control-label">Select New Owner Name:</label>
							<?php
							$owners = DAO::getResultset($link, "SELECT users.id, CONCAT(users.firstnames, ' ', surname), null FROM users WHERE users.type != 5 AND users.web_access = '1' AND users.id != '{$enquiry->created_by}' ORDER BY users.firstnames;");
							echo HTML::selectChosen('owner', $owners, '', true, true);
							?>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary btnUpdateOwner"><i class="fa fa-save"></i>
							Update</button>
						<button type="button" class="btn btn-default pull-left btn-md"
							onclick="$('#changeOwnerModal').modal('hide');">Cancel</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="timeSpentModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>

	<?php echo EntityComment::renderModalHTML($enquiry->id, 'enquiry'); ?>
	<?php echo CRMActivity::renderActivityModalHTML($enquiry->id, 'enquiry', 'task', $link); ?>
	<?php echo CRMActivity::renderActivityModalHTML($enquiry->id, 'enquiry', 'phone', $link); ?>
	<?php echo CRMActivity::renderActivityModalHTML($enquiry->id, 'enquiry', 'meeting', $link); ?>
	<?php echo CRMActivity::renderActivityModalHTML($enquiry->id, 'enquiry', 'email', $link); ?>

	<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
	<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
	<script src="/assets/adminlte/dist/js/app.min.js"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>
	<script src="/assets/js/jquery/jquery.timepicker.js"></script>
	<script src="/assets/adminlte/plugins/summernote/summernote.min.js"></script>

	<?php echo EntityComment::renderModalJS(); ?>
	<?php echo CRMActivity::renderActivityModalJS('task'); ?>
	<?php echo CRMActivity::renderActivityModalJS('phone'); ?>
	<?php echo CRMActivity::renderActivityModalJS('meeting'); ?>
	<?php echo CRMActivity::renderActivityModalJS('email'); ?>

	<script>
		var phpEnquiryId = '<?php echo $enquiry->id; ?>';

		$(function() {

			$('.datepicker').datepicker({
				format: 'dd/mm/yyyy',
				yearRange: 'c-50:c+50'
			});



			$(".timebox").timepicker({
				timeFormat: 'H:i',
				minTime: '08:00:00',
				maxTime: '18:00:00'
			});
			$('.timebox').attr('class', 'timebox optional form-control');
			$('#email_message').summernote({
				toolbar: [
					['style', ['bold', 'italic', 'underline', 'clear']],
					['fontsize', ['fontsize']],
					['para', ['ul', 'ol', 'paragraph']],
					['height', ['height']],
					['insert', ['link', 'picture', 'hr']]
				],
				height: 300,
				callbacks: {
					onImageUpload: function(files, editor, welEditable) {
						sendFile(files[0], editor, welEditable);
					}
				}
			});

			toastr.options = {
				"closeButton": true,
				"progressBar": true,
				"preventDuplicates": true,
				"positionClass": "toast-top-center",
				"onclick": null,
				"showDuration": "400",
				"hideDuration": "1000",
				"timeOut": "7000",
				"extendedTimeOut": "1000",
				"showEasing": "swing",
				"hideEasing": "linear",
				"showMethod": "fadeIn",
				"hideMethod": "fadeOut"
			};

			<?php echo $enquiry->isLocked() ? '$(".lockable").attr("disabled", "disabled")' : ''; ?>

		});

		function convertEnquiryToLead() {
			if (!confirm('Are you sure you want to convert this enquiry into lead?')) {
				return;
			}

			window.location.href = 'do.php?_action=ajax_helper&subaction=convert_enquiry_to_lead&enquiry_id=' + window
				.phpEnquiryId;
		}

		loadActivities('task');
		loadActivities('combined');

		function loadActivities(activity_type) {
			var div = '';
			if (activity_type == 'task')
				div = 'divTasks';
			if (activity_type == 'phone')
				div = 'divPhones';
			if (activity_type == 'meeting')
				div = 'divMeetings';
			if (activity_type == 'email')
				div = 'divEmails';
			if (activity_type == 'combined')
				div = 'divCombined';

			$.ajax({
				type: 'GET',
				url: 'do.php?_action=ajax_helper&subaction=loadActivities',
				data: {
					entity_id: '<?php echo $enquiry->id; ?>',
					entity_type: 'enquiry',
					activity_type: activity_type
				},
				beforeSend: function() {
					$('#' + div).html(
						'<span style="margin-left: 50%;"> <i class="fa fa-refresh fa-3x fa-spin"></i></span>'
					);
				},
				success: function(data) {
					$('#' + div).html(data);
				},
				error: function(xhr, textStatus, thrownError) {
					alert(thrownError, textStatus);
				}
			});
		}

		function editActivity(activity_id, activity_type) {
			$.ajax({
				type: 'GET',
				dataType: 'json',
				url: 'do.php?_action=ajax_helper&subaction=get_activity_detail',
				data: {
					activity_id: activity_id,
					activity_type: activity_type
				},
				success: function(data) {
					var form = document.forms[activity_type + 'Form'];
					$.each(data, function(key, value) {
						if (form.elements[key] != undefined)
							form.elements[key].value = value;
						if (form.elements[activity_type + '_' + key] != undefined)
							form.elements[activity_type + '_' + key].value = value;
					});
					$('#' + activity_type + 'Modal').modal('show');
				},
				error: function(data, textStatus, xhr) {
					console.log(data.responseText);
				}
			});
		}

		$('#btnDeleteEnquiry').on('click', function(e) {
			e.preventDefault();
			if (!confirm(
					'This action is irreversible. This record, its activities and audit logs will be removed. Are you sure you want to continue?'
				)) {
				return false;
			}

			var form = this.closest('form');
			$.ajax({
				url: form.action,
				type: form.method,
				data: $(form).serialize()
			}).done(function(response, textStatus) {
				alert(response);
				window.location.href = "do.php?_action=view_enquiries";
			}).fail(function(jqXHR, textStatus, errorThrown) {
				alert(textStatus + ': ' + errorThrown);
			});
		});

		$('.btnDeleteCRMEntityFile').on('click', function(e) {
			e.preventDefault();
			if (!confirm(
					'This action is irreversible. This record, its activities and audit logs will be removed. Are you sure you want to continue?'
				)) {
				return false;
			}

			var form = this.closest('form');
			$.ajax({
				url: form.action,
				type: form.method,
				data: $(form).serialize()
			}).done(function(response, textStatus) {
				alert(response);
				window.location.reload();
			}).fail(function(jqXHR, textStatus, errorThrown) {
				alert(textStatus + ': ' + errorThrown);
			});
		});

		function uploadFile() {
			var myForm = document.forms["frmFiles"];
			if (validateForm(myForm) == false) {
				return false;
			}
			myForm.submit();
		}

		var phpStatus = '<?php echo $enquiry->status; ?>';

		$('input[type="radio"][name="status"]').on('change', function(e) {
			<?php echo $enquiry->isLocked() ? 'return' : ''; ?>
			e.preventDefault();
			$.ajax({
				type: 'GET',
				url: 'do.php?_action=save_enquiry',
				data: {
					id: window.phpEnquiryId,
					status: this.value
				}
			}).done(function(response, textStatus) {
				toastr.success('Status updated.');
				window.location.reload();
			}).error(function(jqXHR, textStatus, errorThrown) {
				console.log(jqXHR);
				console.log(textStatus);
				console.log(errorThrown);
				$('input[type="radio"][name="status"][value="' + window.phpStatus + '"]').prop('checked',
					true);
				//alert(textStatus + ': ' + errorThrown);
			});
		});

		$('.btnUpdateOwner').on('click', function() {
			if (!confirm('Are you sure you want to change the owner of this enquiry?')) {
				return false;
			}
			var form = this.closest('form');
			if (validateForm(form) == false) {
				return false;
			}
			$.ajax({
				url: form.action,
				type: form.method,
				data: $(form).serialize()
			}).done(function(response, textStatus) {
				toastr.success(response);
				window.location.reload();
			}).fail(function(jqXHR, textStatus, errorThrown) {
				alert(textStatus + ': ' + errorThrown);
			});
		});

		function prepareTimeSpentModal(activity_id, tab_combined = 0) {
			$.ajax({
				url: 'do.php?_action=ajax_helper&subaction=prepareTimeSpentModal',
				type: 'get',
				data: {
					activity_id: activity_id,
					tab_combined: tab_combined
				}
			}).done(function(response, textStatus) {
				$('#timeSpentModal').html(response);
				$('#timeSpentModal').modal('show');
			}).fail(function(jqXHR, textStatus, errorThrown) {
				alert(textStatus + ': ' + errorThrown);
			});
		}

		<?php echo CRMActivity::renderCompletionToggleJs(); ?>
	</script>
</body>

</html>