<?php /* @var $lead Opportunity */ ?>
<?php /* @var $opportunity Opportunity */ ?>

<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>View Opportunity</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
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
			cursor:not-allowed;
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
			<div class="Title" style="margin-left: 6px;">View Opportunity</div>
			<div class="ButtonBar">
				<span class="btn btn-xs btn-default"
				      onclick="window.location.href='do.php?_action=view_opportunities'"><i
					class="fa fa-arrow-circle-o-left"></i> Back to Opportunities</span>
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
					<div class="col-sm-4">
						<h1 class="box-title text-bold"><?php echo $opportunity->opportunity_title; ?></h1><br>
						<span class="text-bold">Owner: </span><span class="text-muted" id="lblOwnerName"><?php echo $opportunity->getOwnerName($link); ?></span> &nbsp;&nbsp;&nbsp;
						<?php if($_SESSION['user']->isAdmin() || $opportunity->created_by == $_SESSION['user']->id) { ?>
						<button type="button" class="btn btn-xs btn-primary <?php echo $opportunity->isLocked() ? 'disabled' : ''; ?>" onclick="$('#changeOwnerModal').modal('show');"><i class="fa fa-edit"></i> Change</button>
						<br><span class="text-bold">Created on: </span><span class="text-muted"><?php echo Date::to($opportunity->created, Date::DATETIME); ?></span>  | 
						<span class="text-bold">Last modified on: </span><span class="text-muted"><?php echo Date::to($opportunity->modified, Date::DATETIME); ?></span> <br>
						<?php } ?>
					</div>
					<div class="col-sm-3">
						<div class="btn-group btn-group-justified" data-toggle="buttons">
							<label
								class="btn btn-success btn-app <?php echo $opportunity->hwc == 'H' ? 'active' : ''; ?>  <?php echo $opportunity->isLocked() ? 'disabled' : ''; ?>"
								title="HOT"><input type="radio" name="hwc" value="H" autocomplete="off" <?php echo $opportunity->hwc == 'H' ? 'checked="checked"' : ''; ?>>
								HOT</label>
							<label
								class="btn btn-info btn-app <?php echo $opportunity->hwc == 'W' ? 'active' : ''; ?> <?php echo $opportunity->isLocked() ? 'disabled' : ''; ?>"
								title="WARM"><input type="radio" name="hwc" value="W"
							                        autocomplete="off" <?php echo $opportunity->hwc == 'W' ? 'checked="checked"' : ''; ?>>WARM</label>
							<label
								class="btn btn-warning btn-app <?php echo $opportunity->hwc == 'C' ? 'active' : ''; ?> <?php echo $opportunity->isLocked() ? 'disabled' : ''; ?>"
								title="COLD"><input type="radio" name="hwc" value="C"
							                        autocomplete="off" <?php echo $opportunity->hwc == 'C' ? 'checked="checked"' : ''; ?>>COLD</label>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="btn-group btn-group-justified" data-toggle="buttons">
							<label
								class="btn btn-success btn-app <?php echo $opportunity->isQualified() ? 'active' : ''; ?> <?php echo $opportunity->isLocked() ? 'disabled' : ''; ?>"
								title="Successful (Selecting this status will switch on the button 'Convert Opportunity' to progress to next stage i.e. Create Employer)"><input type="radio" name="status" value="3"
							                       autocomplete="off" <?php echo $opportunity->isQualified() ? 'checked="checked"' : ''; ?>><i
								class="fa fa-thumbs-o-up"></i></label>
							<label
								class="btn btn-danger btn-app <?php echo $opportunity->isUnqualified() ? 'active' : ''; ?> <?php echo $opportunity->isLocked() ? 'disabled' : ''; ?>"
								title="Lost"><input type="radio" name="status" value="4"
							                        autocomplete="off" <?php echo $opportunity->isUnqualified() ? 'checked="checked"' : ''; ?>><i
								class="fa fa-thumbs-o-down"></i></label>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="pull-right bg-green" style="padding: 5px; border-radius: 5px;"><?php echo $opportunity->getListOpportunityStatus($opportunity->status); ?></div>
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
						echo $company_contact->contact_email != '' ? '<i class="fa fa-envelope"></i> <a class="text-blue" href="mailto: '.$company_contact->contact_email . '">' . $company_contact->contact_email . '</a><br>' : '';
						echo '<i class="fa fa-phone"></i> ' . $company_contact->contact_telephone . '<br>';
						echo '<i class="fa fa-mobile"></i> ' . $company_contact->contact_mobile . '<br>';
						?>
						</div>
					</div>

					<div class="col-md-4 well well-sm">
						<div class="info-box">
							<span class="info-box-icon bg-aqua"><i class="fa fa-gbp"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">Estimated Revenue</span>
								<span
									class="info-box-number"><?php echo is_null($opportunity->est_revenue) ? 0 : $opportunity->est_revenue; ?></span>
							</div>
						</div>
						<?php echo !is_null($opportunity->est_closed_date) ? '<span title="Estimated Close Date"><i class="fa fa-calendar"></i> ' . Date::toShort($opportunity->est_closed_date) . '</span>' : '<i class="fa fa-warning"></i> no estimated close date set'; ?>
						<?php echo !is_null($opportunity->est_closed_date) ? '<label class="label label-info small">' . Date::dateDiff(new Date($opportunity->est_closed_date), new Date(date('Y-m-d'))) . '</label>' : ''; ?>
						<?php echo !is_null($opportunity->estimated_learners) ? '<br>Estimated Learners: ' . $opportunity->estimated_learners : ''; ?>
					</div>
					<div class="col-md-4">
						<dl class="dl-horizontal small">
							<dt>Academic year:</dt>
							<dd><span class="text-muted"><?php echo htmlspecialchars((string)$opportunity->a_year); ?></span></dd>
							<dt>Repeat business:</dt>
							<dd><span class="text-muted"><?php echo $opportunity->repeat_business == 1 ? 'Yes' : 'No'; ?></span></dd>
						</dl>
						<div class="small">
							<dt>Description:</dt>
							<dd><span
								class="text-muted small"><?php echo htmlspecialchars((string)$opportunity->description); ?></span>
							</dd>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<span class="btn btn-xs btn-primary <?php echo $opportunity->isLocked() ? 'disabled' : ''; ?>"
						      onclick="window.location.href='do.php?_action=edit_opportunity&id=<?php echo $opportunity->id; ?>&org_id=<?php echo $opportunity->company_id; ?>&org_type=<?php echo $opportunity->company_type; ?>'"><i
							class="fa fa-edit"></i> Edit</span> &nbsp;
						<?php if ($opportunity->isQualified() && !$opportunity->converted) { ?>
						<span class="btn btn-xs btn-primary" onclick="convert_opportunity();"><i class="fa fa-tick"></i> Convert Opportunity</span>
						&nbsp;
						<?php } ?>
						<form name="frmDeleteOpportunity" action="<?php echo $_SERVER['PHP_SELF']; ?>?_action=ajax_helper" style="display: inline;" method="post">
							<input type="hidden" name="opportunity_id_to_delete" value="<?php echo $opportunity->id; ?>">
							<input type="hidden" name="subaction" value="delete_opportunity">
							<span id="btnDeleteOpportunity" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</span> &nbsp;
						</form>
						<?php if ($opportunity->isQualified() && $opportunity->converted) { ?>
						<span class="btn btn-xs btn-success"
						      onclick="window.location.href='do.php?_action=read_employer&id=<?php echo $opportunity->employer_id; ?>'"><i
							class="fa fa-folder"></i> Linked Employer</span> &nbsp;
						<?php } ?>
						<span class="btn btn-xs btn-success"
						      onclick="window.location.href='do.php?_action=read_lead&id=<?php echo $opportunity->lead_id; ?>'"><i
							class="fa fa-folder"></i> Linked Lead</span> &nbsp;
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">

	<div class="col-sm-8">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab1" data-toggle="tab"> Activities <label class="label label-info"><?php echo $activities_count; ?></label></a></li>
                <li><a href="#tabContacts" data-toggle="tab"> Contacts <label class="label label-info"><?php echo $contacts_count; ?></label></a></li>
				<li><a href="#tab2" data-toggle="tab"> Comments <label class="label label-info"><?php echo $comments_count; ?></label></a></li>
				<li onclick=""><a href="#tab3" data-toggle="tab"> Audit/Log</a></li>
			</ul>
			<div class="tab-content">
				<div class="active tab-pane" id="tab1">
					<div class="row">
						<div class="col-sm-12">
							<span class="btn btn-xs btn-primary <?php echo $opportunity->isLocked() ? 'disabled' : ''; ?>" onclick="$('#taskModal').modal('show');"><i class=""></i> Add Task</span> &nbsp;
							<span class="btn btn-xs btn-primary <?php echo $opportunity->isLocked() ? 'disabled' : ''; ?>" onclick="$('#phoneModal').modal('show');"><i class=""></i> Add Phone Call</span> &nbsp;
							<span class="btn btn-xs btn-primary <?php echo $opportunity->isLocked() ? 'disabled' : ''; ?>" onclick="$('#meetingModal').modal('show')"><i class=""></i> Add Meeting</span> &nbsp;
							<span class="btn btn-xs btn-primary <?php echo $opportunity->isLocked() ? 'disabled' : ''; ?>" onclick="$('#emailModal').modal('show')"><i class=""></i> Compose Email</span> &nbsp;							
							<p><br></p>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="nav-tabs-custom">
								<ul class="nav nav-tabs">
									<li class="active" onclick="loadActivities('combined');">
										<a href="#tab11" data-toggle="tab">Combined View <label class="label label-info"><?php echo $activities_count; ?></label></a>
									</li>
									<li onclick="loadActivities('task');"><a href="#tab12" data-toggle="tab">Tasks <label class="label label-info"><?php echo $opportunity->activityCount($link, 'task'); ?></label></a></li>
									<li onclick="loadActivities('phone');"><a href="#tab13" data-toggle="tab">Phone Calls <label class="label label-info"><?php echo $opportunity->activityCount($link, 'phone'); ?></label></a></li>
									<li onclick="loadActivities('meeting');"><a href="#tab14" data-toggle="tab">Meetings <label class="label label-info"><?php echo $opportunity->activityCount($link, 'meeting'); ?></label></a></li>
									<li onclick="loadActivities('email');"><a href="#tab15" data-toggle="tab">Emails <label class="label label-info"><?php echo $opportunity->activityCount($link, 'email'); ?></label></a></li>
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
									<!-- /.tab-pane -->
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
                    <span class="btn btn-xs btn-primary" onclick="window.location.href='do.php?_action=edit_crm_contacts&contact_id=&org_id=<?php echo $opportunity->company_id; ?>&org_type=<?php echo $opportunity->company_type; ?>'"><i class="fa fa-user"></i> Add Contact</span> &nbsp;
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed">
                            <thead>
                            <tr><th>Title</th><th>First Name</th><th>Surname</th><th>Email</th><th>Telephone</th><th>Mobile</th></tr>
                            </thead>
                            <tbody>
                            <?php
							if($opportunity->company_type == "pool")
							{
								$records = DAO::getResultset($link, "SELECT * FROM pool_contact WHERE pool_id = '{$company->id}' ORDER BY contact_name", DAO::FETCH_ASSOC);
							}
							elseif($opportunity->company_type == "employer")
							{
								$records = DAO::getResultset($link, "SELECT * FROM organisation_contact WHERE org_id = '{$company->id}' ORDER BY contact_name", DAO::FETCH_ASSOC);
							}
							foreach($records AS $row)
							{
								echo '<tr>';
								echo '<td>' . $row['contact_title'] . '</td>';
								echo '<td>' . $row['contact_name'] . '</td>';
								echo $row['decision_maker'] == '1' ? '<td class="bg-green">Yes</td>' : '<td>No</td>';
								echo '<td>' . $row['contact_telephone'] . '</td>';
								echo '<td>' . $row['contact_mobile'] . '</td>';
								echo '<td>' . $row['contact_email'] . '</td>';
								echo $row['job_role'] != '' ? '<td>' . $row['job_role'] . '</td>' : '<td>' . $row['job_title'] . '</td>';
								echo '<td>';
								echo '<span class="btn btn-xs btn-primary" onclick="window.location.href=\'do.php?_action=edit_crm_contacts&contact_id=' . $row['contact_id'] . '&org_id=' . $opportunity->company_id . '&org_type=' . $opportunity->company_type . '\'"><i class="fa fa-edit"></i></span> &nbsp; ';
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
								echo EntityComment::renderComments($link, 'opportunity', $opportunity->id);
								?>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="tab3">
					<div class="row">
						<div class="col-sm-12">
							<div class="tab-pane">
								<div class="row">
									<div class="col-sm-12" id="auditsTab">
										<?php
										echo $opportunity->renderNotes($link);
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tabAddress" data-toggle="tab"> Address</a></li>
				<li><a href="#tabFiles" data-toggle="tab"> Files <?php echo '<label class="label label-info">' . $opportunity->filesCount($link) . '</label>'; ?></a></li>
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

<div class="modal fade" id="agreementModal" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title text-bold">Agreement</h5>
			</div>
			<div class="modal-body">
				<form autocomplete="off" class="form-horizontal" method="post" name="frmAgreement" id="frmAgreement"
				      method="post" action="do.php?_action=save_opportunity">
					<input type="hidden" name="formName" value="frmAgreement"/>
					<input type="hidden" name="opportunity_id" value="<?php echo $opportunity->id; ?>"/>
					<input type="hidden" name="agreement_id" value=""/>

					<div class="control-group">
						<label class="control-label" for="agreement_date">Date:</label>
						<input type="text" class="form-control compulsory required datepicker" id="agreement_date"
						       name="agreement_date" value="" placeholder="dd/mm/yyyy">
					</div>

					<div class="control-group">
						<label class="control-label" for="comments">Comments:</label>
						<textarea class="form-control" name="comments" id="comments" rows="5"
						          style="width: 100%;"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left btn-md"
				        onclick="$('#agreementModal').modal('hide');">Cancel
				</button>
				<button type="button" id="btnAgreementModalSave" class="btn btn-primary btn-md"><i
					class="fa fa-save"></i> Save
				</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="changeOwnerModal" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<form autocomplete="off" class="form-horizontal" method="post" name="frmChangeOwner" id="frmChangeOwner" method="post" action="do.php?_action=ajax_helper">
				<input type="hidden" name="formName" value="frmChangeOwner" />
				<input type="hidden" name="subaction" value="update_owner" />
				<input type="hidden" name="entity_id" value="<?php echo $opportunity->id; ?>" />
				<input type="hidden" name="entity_type" value="<?php echo get_class($opportunity); ?>" />
				<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h5 class="modal-title text-bold">Change Owner</h5></div>
				<div class="modal-body">
					<div class="callout callout-info small"><i class="fa fa-info-circle"></i> Use this functionality to change owner of this opportunity.</div>
					<div class="control-group small">
						<label class="control-label">Select New Owner Name:</label>
						<?php
						$owners = DAO::getResultset($link, "SELECT users.id, CONCAT(users.firstnames, ' ', surname), null FROM users WHERE users.type != 5 AND users.web_access = '1' ORDER BY users.firstnames;");
						echo HTML::selectChosen('owner', $owners, '', true, true);
						?>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary btnUpdateOwner"><i class="fa fa-save"></i> Update</button>
					<button type="button" class="btn btn-default pull-left btn-md" onclick="$('#changeOwnerModal').modal('hide');">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="timeSpentModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<?php echo EntityComment::renderModalHTML($opportunity->id, 'opportunity'); ?>
<?php echo CRMActivity::renderActivityModalHTML($opportunity->id, 'opportunity', 'task', $link); ?>
<?php echo CRMActivity::renderActivityModalHTML($opportunity->id, 'opportunity', 'phone', $link); ?>
<?php echo CRMActivity::renderActivityModalHTML($opportunity->id, 'opportunity', 'meeting', $link); ?>
<?php echo CRMActivity::renderActivityModalHTML($opportunity->id, 'opportunity', 'email', $link); ?>


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

var phpOpportunityId = '<?php echo $opportunity->id; ?>';

$(function () {
	$('.datepicker').datepicker({
		format: 'dd/mm/yyyy',
		yearRange: 'c-50:c+50'
	});

	$(".timebox").timepicker({ timeFormat: 'H:i', minTime: '08:00:00', maxTime: '18:00:00' });
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
		"timeOut": "3000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut"
	};
});

$("button#btnTimeSpentModalSave").click(function(){
	if(validateForm(document.forms['frmActivityTime']) == false)
	{
		return;
	}
	$("#frmActivityTime").submit();
});

$('.btnDeleteCRMEntityFile').on('click', function(e){
	e.preventDefault();
	if(!confirm('This action is irreversible. This record, its activities and audit logs will be removed. Are you sure you want to continue?'))
	{
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
		}).fail(function(jqXHR, textStatus, errorThrown){
			alert(textStatus + ': ' + errorThrown);
		});
});

function uploadFile()
{
	var myForm = document.forms["frmFiles"];
	if(validateForm(myForm) == false)
	{
		return false;
	}
	myForm.submit();
}

loadActivities('task');
loadActivities('combined');

function loadActivities(activity_type)
{
	var div = '';
	if(activity_type == 'task')
		div = 'divTasks';
	if(activity_type == 'phone')
		div = 'divPhones';
	if(activity_type == 'meeting')
		div = 'divMeetings';
	if(activity_type == 'email')
		div = 'divEmails';
	if(activity_type == 'combined')
		div = 'divCombined';				

	$.ajax({
		type:'GET',
		url:'do.php?_action=ajax_helper&subaction=loadActivities',
		data:{entity_id: window.phpOpportunityId, entity_type: 'opportunity', activity_type: activity_type},
		beforeSend:function () {
			$('#'+div).html('<span style="margin-left: 50%;"> <i class="fa fa-refresh fa-3x fa-spin"></i></span>');
		},
		success:function (data) {
			$('#'+div).html(data);
		},
		error:function (xhr, textStatus, thrownError) {
			alert(thrownError, textStatus);
		}
	});
}

function editActivity(activity_id, activity_type)
{
	$.ajax({
		type:'GET',
		dataType:'json',
		url:'do.php?_action=ajax_helper&subaction=get_activity_detail',
		data:{activity_id:activity_id, activity_type:activity_type},
		success:function (data) {
			var form = document.forms[activity_type+'Form'];
			$.each(data, function (key, value) {
				if(form.elements[key] != undefined)
					form.elements[key].value = value;
				if(form.elements[activity_type+'_'+key] != undefined)
					form.elements[activity_type+'_'+key].value = value;
			});
			$('#'+activity_type+'Modal').modal('show');
		},
		error:function (data, textStatus, xhr) {
			console.log(data.responseText);
		}
	});
}

$('.btnUpdateOwner').on('click', function(){
	if(!confirm('Are you sure you want to change the owner of this opportunity?'))
	{
		return false;
	}
	var form = this.closest('form');
	if(validateForm(form) == false)
	{
		return false;
	}
	$.ajax({
		url: form.action,
		type: form.method,
		data: $(form).serialize()
	}).done(function(response, textStatus) {
			toastr.success(response);
			window.location.reload();
		}).fail(function(jqXHR, textStatus, errorThrown){
			alert(textStatus + ': ' + errorThrown);
		});
});

function prepareTimeSpentModal(activity_id, tab_combined = 0)
{
	$.ajax({
		url: 'do.php?_action=ajax_helper&subaction=prepareTimeSpentModal',
		type: 'get',
		data: {activity_id: activity_id, tab_combined: tab_combined}
	}).done(function(response, textStatus) {
			$('#timeSpentModal').html(response);
			$('#timeSpentModal').modal('show');
		}).fail(function(jqXHR, textStatus, errorThrown){
			alert(textStatus + ': ' + errorThrown);
		});
}

function update_audits_tab()
{
	$.ajax({
		type:'GET',
		url:'do.php?_action=ajax_helper&subaction=update_audits_tab',
		data:{entity_id: window.phpOpportunityId, entity_type: 'Opportunity'},
		beforeSend:function () {
			$('#auditsTab').html('<span style="margin-left: 50%;"> <i class="fa fa-refresh fa-3x fa-spin"></i></span>');
		},
		success:function (data) {
			$('#auditsTab').html(data);
		},
		error:function (xhr, textStatus, thrownError) {
			alert(thrownError, textStatus);
		}
	});
}

$('#btnDeleteOpportunity').on('click', function(e){
	e.preventDefault();
	if(!confirm('This action is irreversible. This record, its activities and audit logs will be removed. Are you sure you want to continue?'))
	{
		return false;
	}

	var form = this.closest('form');
	$.ajax({
		url: form.action,
		type: form.method,
		data: $(form).serialize()
	}).done(function(response, textStatus) {
			alert(response);
			window.location.href="do.php?_action=view_opportunities";
		}).fail(function(jqXHR, textStatus, errorThrown){
			alert(textStatus + ': ' + errorThrown);
		});
});

function convert_opportunity() {
	if (!confirm('Are you sure you want to convert this opportunity?'))
		return false;

	window.location.href = 'do.php?_action=ajax_helper&subaction=convert_opportunity&opportunity_id=<?php echo $opportunity->id; ?>';
}

$('input[type="radio"][name="hwc"]').on('change', function(e){
	<?php echo $opportunity->isLocked() ? 'return alert("Opportunity record is locked.");' : ''; ?>
	e.preventDefault();
	$.ajax({
		type:'GET',
		url:'do.php?_action=save_opportunity',
		data: {id: window.phpOpportunityId, hwc: this.value},
		success: function(data) {
			toastr.success('Opportunity status has been updated.');
			update_audits_tab();
		},
		error: function(data, textStatus, xhr){
			toastr.success(data.responseText);
		}
	});
});

$('input[type="radio"][name="status"]').on('change', function(e){
	<?php echo $opportunity->isLocked() ? 'return alert("Opportunity record is locked.");' : ''; ?>

    e.preventDefault();

    if(this.value == '3')
    {
        if(!confirm('This action sets this ready for conversion.\r\nPlease note this record will be locked.\r\nAre you sure you want to continue?'))
        {
            window.location.reload();
            return false;
        }
    }
    if(this.value == '4')
    {
        if(!confirm('This action sets this as Unqualified and will not be editable then.\r\nAre you sure you want to continue?'))
        {
            window.location.reload();
            return false;
        }
    }

    $.ajax({
		type:'GET',
		url:'do.php?_action=ajax_helper&subaction=update_opportunity_status',
		data: {opportunity_id: window.phpOpportunityId, status: this.value},
		success: function(data) {
			window.location.reload();
		},
		error: function(data, textStatus, xhr){
			toastr.success(data.responseText);
		}
	});
});

<?php echo CRMActivity::renderCompletionToggleJs();?>

</script>
</body>
</html>
