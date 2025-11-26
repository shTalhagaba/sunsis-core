<?php /* @var $inductee Inductee */ ?>
<?php /* @var $induction Induction */ ?>
<?php /* @var $inductionProgramme InductionProgramme */ ?>

<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>View Induction Learner</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/chosen/bootstrap-chosen.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
	<link href="/assets/adminlte/plugins/toastr/toastr.min.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		.notification-counter {
			position: relative;
			top: -10px;
			left: 8px;
			background-color: rgba(212, 19, 13, 1);
			color: #fff;
			border-radius: 3px;
			padding: 1px 3px;
			font: 10px Verdana;
		}
		.ui-icon.white { background-image: url(/assets/adminlte/plugins/jQueryUI/images/ui-icons_ffffff_256x240.png); }
		.ui-icon.red { background-image: url(/assets/adminlte/plugins/jQueryUI/images/ui-icons_222222_256x240.png); }
		.ui-icon.blue { background-image: url(/assets/adminlte/plugins/jQueryUI/images/ui-icons_ef8c08_256x240.png); }
		.ui-dialog-titlebar-close {
			visibility: hidden;
		}
		.ui-icon {
			font-size: 1.2em;
		}
		.loading-image{background-image: url('images/progress-animations/loading51.gif');background-color: rgba(255,255,255,0.5);background-position: center center;background-repeat: no-repeat; filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#50FFFFFF,endColorstr=#50FFFFFF);width: 100%; height: 100%; position: fixed; top: 0; left: 0; z-index: 9999;}
	</style>

</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;"><?php echo $inductee->id == ''?'Create Induction':'View Induction'; ?></div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<?php if(is_null($inductee->sunesis_username) && (SOURCE_BLYTHE_VALLEY || $_SESSION['user']->induction_access != 'R')){?>
				<span class="btn btn-sm btn-default" onclick="window.location.href='do.php?_action=edit_inductee&id=<?php echo $inductee->id; ?>';"><i class="fa fa-edit"></i> Edit</span>
				<?php } ?>
				<?php if(is_null($inductee->sunesis_username) && ($_SESSION['user']->username == 'jcoates')){?>
				<span class="btn btn-sm btn-danger" onclick="deleteInduction();"><i class="fa fa-trash"></i> Delete</span>
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
<div class="col-md-3">
	<div class="well">
		<h2><?php echo htmlspecialchars((string)$inductee->firstnames) . ' ' . htmlspecialchars(strtoupper($inductee->surname)); ?></h2>
		<ul class="list-unstyled">
			<?php echo trim($inductee->home_email) != ''?'<li><span class="fa fa-envelope"></span><a href="mailto:' . htmlspecialchars((string)$inductee->home_email). '"> '.htmlspecialchars((string)$inductee->home_email).'</a> <span class="label label-info">Personal</span></li>':''; ?>
			<?php echo trim($inductee->work_email) != ''?'<li><span class="fa fa-envelope"></span><a href="mailto:' . htmlspecialchars((string)$inductee->work_email). '"> '.htmlspecialchars((string)$inductee->work_email).'</a> <span class="label label-info">Work</span></li>':''; ?>
			<?php echo trim($inductee->home_telephone) != ''?'<li><span class="fa fa-phone"></span> '.htmlspecialchars((string)$inductee->home_telephone).'</li>':''; ?>
			<?php echo trim($inductee->home_mobile) != ''?'<li><span class="fa fa-mobile-phone"></span> '.htmlspecialchars((string)$inductee->home_mobile).'</li>':''; ?>
		</ul>
		<hr>
		<table class="table">
			<col width="100" /><col />
			<tr><th>Gender:</th><td><?php echo htmlspecialchars((string)$listGender[$inductee->gender]); ?></td></tr>
			<tr>
				<th>Date of Birth:</th>
				<td>
					<?php
					echo htmlspecialchars(Date::toMedium($inductee->dob));
					if ($inductee->dob) {
						echo '<span style="margin-left:30px;color:gray"></span><br><label class="label label-info">' . Date::dateDiff(date("Y-m-d"),$inductee->dob) . '</label>';
					}
					?>
				</td>
			</tr>
			<tr><th>NI Number:</th><td><?php echo htmlspecialchars((string)$inductee->ni); ?></td></tr>
		</table>
	</div>
	<div class="box box-primary">
		<div class="box-body">
			<strong><i class="fa fa-map-marker margin-r-5"></i> Employer Contact Details</strong>
			<address class="well">
				<?php
				if(!is_null($employer))
				{
					echo $employer->legal_name.'<br>';
					echo trim($employer_location->address_line_1) != ''?htmlspecialchars((string)$employer_location->address_line_1).'<br>':'';
					echo trim($employer_location->address_line_2) != ''?htmlspecialchars((string)$employer_location->address_line_2).'<br>':'';
					echo trim($employer_location->address_line_3) != ''?htmlspecialchars((string)$employer_location->address_line_3).'<br>':'';
					echo trim($employer_location->address_line_4) != ''?htmlspecialchars((string)$employer_location->address_line_4).'<br>':'';
					echo trim($employer_location->postcode) != ''?htmlspecialchars((string)$employer_location->postcode).'<br>':'';
					echo trim($employer_location->telephone) != ''?'<span class="fa fa-phone"></span> '.htmlspecialchars((string)$employer_location->telephone).'<br>':'';
				}
				else
				{
					echo '<i class="fa fa-warning"></i> This record has been imported from Salesforce, and still needs Sunesis employer';
				}
				?>
			</address>
		</div>
	</div>
	<div class="box box-primary">
		<div class="box-header with-border"><h2 class="box-title">Record Creation</h2>
			<div class="box-tools pull-right">
			</div>
		</div>

		<div class="box-body">
			<div class="well well-small">
				<table class="table">
					<tr><th colspan="2">Learner</th></tr>
					<tr><th style="width:30%">DateTime:</th><td><?php echo Date::to($inductee->created, Date::DATETIME); ?></td></tr>
					<tr><th style="width:30%">By:</th><td><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$inductee->created_by}'")); ?></td></tr>
					<tr><th colspan="2">Induction</th></tr>
					<tr><th style="width:30%">DateTime:</th><td><?php echo Date::to($induction->created, Date::DATETIME); ?></td></tr>
					<tr><th style="width:30%">By:</th><td><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$induction->created_by}'")); ?></td></tr>
				</table>
			</div>

		</div>
	</div>
</div>

<div class="col-md-9" >
<div class="row">
	<div class="col-sm-12">
		<?php if(isset($induction->created) && !is_null($induction->created))
	{
		echo 'Induction record created on: <label>' . Date::to($induction->created, Date::DATETIME) . '</label>';
	}
		?>
	</div>
	<div class="col-md-12">
		<div class="table-responsive">
			<table class="table table-bordered">
				<td id="status_TBA" align="center">To Be Arranged</td>
				<td id="status_S" align="center">Scheduled</td>
				<td id="status_H" align="center">Holding Induction</td>
				<td id="status_C" class="" align="center">Completed <?php if($induction->comp_issue == 'Y'){ echo '<i class="fa fa-warning" title="Completion Issue Reason:'.$induction->comp_issue_notes.'"></i>'; }?> </td>
				<td id="status_L" align="center">Leaver</td>
				<td id="status_W" align="center">Withdrawn</td>
			</table>
		</div>
	</div>
</div>
<div class="row">
<div class="col-md-12">
<div class="nav-tabs-custom">
<ul class="nav nav-tabs bg-gray">
	<li class="active"><a href="#tab1" data-toggle="tab"><u>Induction</u></a></li>
	<li><a href="#tab2" data-toggle="tab"><u>Programme</u></a></li>
	<li><a href="#tab3" data-toggle="tab"><u>Additional Details</u></a></li>
	<li><a href="#tab4" data-toggle="tab"><u>Audit</u></a></li>
</ul>
<div class="tab-content">
<div class="active tab-pane" id="tab1">
	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table callout">
					<thead><tr><th>MIAP</th><th>Headset Issued</th><th>Moredle Account</th><th>Sunesis Account</th><th>Levy Payer</th></tr></thead>
					<tbody>
					<tr>
						<td><?php echo isset($listMIAP[$induction->miap])?$listMIAP[$induction->miap]:''; ?></td>
						<td><?php echo isset($listHeadset[$induction->headset_issued])?$listHeadset[$induction->headset_issued]:$induction->headset_issued; ?></td>
						<td><?php echo isset($listYesNoNA[$induction->moredle_account])?$listYesNoNA[$induction->moredle_account]:$induction->moredle_account; ?></td>
						<td>
							<?php
							if(is_null($inductee->sunesis_username) || $inductee->sunesis_username == '')
							{
								echo 'No';
								if($induction->induction_status == 'C')
								{
									if($_SESSION['user']->induction_access != 'R')
									{
										echo '<br><span class="btn btn-sm btn-primary" onclick="showHideBlock(\'tblSunesisAccountCreation\');"><i class="fa fa-plus"></i> Create Sunesis Account</span>';
										echo '<br><table id="tblSunesisAccountCreation" class="table" style="background-color: #e0ffff; display: none;">';
										echo '<tr><th colspan="2">Sunesis Account Creation </th></tr>';
										echo '<tr><td colspan="2"><i class="fa fa-info-circle"></i> &nbsp; <i class="text-muted">Use this panel to create a sunesis account. Please note that this action will also enrol the learner onto the programme.</i></td></tr>';
										echo '<tr><th>Select Contract</th><td>' . HTML::selectChosen('training_contract_id', DAO::getResultset($link, "SELECT contracts.id, contracts.title, NULL FROM contracts WHERE LOWER(title) IN ('2023-24 holding contract', '2024-25 holding contract') ORDER BY id DESC;")) . '</td></tr>';
										echo '<tr><th>Select Provider location</th><td>' . HTML::selectChosen('training_provider_location_id', InductionHelper::getProviderLocationsDDL($link, $inductionProgramme->programme_id), '', false) . '</td></tr>';
										echo '<tr><td class="2" align="left"><span class="btn btn-sm btn-primary" onclick="checkExistingRecordsBeforeCreation();"><i class="fa fa-graduation-cap"></i> Create Sunesis Account and Enrol</span></tr>';
										echo '</table> ';
									}
								}
							}
							else
							{
								echo 'Yes' . '<br><span class="btn btn-sm btn-info" onclick="window.location.href=\'do.php?_action=read_user&username='.$inductee->sunesis_username.'\';"><i class="fa fa-folder-open"></i> Open Sunesis Account</span>';
							}
							?>
						</td>
						<td><?php echo isset($listYesNo[$induction->levy_payer])?$listYesNo[$induction->levy_payer]:$induction->levy_payer; ?></td>
					</tr>
					</tbody>
				</table>
				<hr>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-body">
					<div class="table-responsive">
						<table class="table callout">
							<col width="200" /><col />
							<col width="200" /><col />
							<tr>
								<th>Induction Date:</th><td><?php echo Date::toShort($induction->induction_date); ?><br><label class="label label-info"><?php echo Date::dateDiff($induction->induction_date, $inductee->dob); ?></label></td>
								<th>Planned End Date:</th><td><?php echo Date::toShort($induction->planned_end_date); ?></td>
							</tr>
							<tr>
								<th>Join Time:</th><td><?php echo isset($listJoinTime[$induction->join_time])?$listJoinTime[$induction->join_time]:$induction->join_time; ?></td>
								<th>Age Group at Induction:</th>
								<td>
									<?php
									$age_group = Date::dateDiffInfo($induction->induction_date,$inductee->dob);
									if(isset($age_group['year']))
									{
										if($age_group['year'] >= 16 && $age_group['year'] <= 18)
											echo '16-18';
										elseif($age_group['year'] >= 19 && $age_group['year'] <= 24)
											echo '19-24';
										elseif($age_group['year'] > 24)
											echo '24+';
										else
											echo $age_group['year'] . ' years';
									}
									else
									{
										echo '-';
									}
									?>
								</td>
							</tr>
							<tr>
								<th>Numeracy Level:</th><td><?php echo isset($listIAG[$induction->iag_numeracy])?$listIAG[$induction->iag_numeracy]:$induction->iag_numeracy; ?></td>
								<th>Literacy Level:</th><td><?php echo isset($listIAG[$induction->iag_literacy])?$listIAG[$induction->iag_literacy]:$induction->iag_literacy; ?></td>
							</tr>
							<tr>
								<th>ICT Level:</th><td><?php echo isset($listICT[$induction->iag_ict])?$listICT[$induction->iag_ict]:$induction->iag_ict; ?></td>
								<th>Induction Assessor:</th><td><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$induction->induction_assessor}'")); ?></td>
							</tr>
							<tr>
								<th>Assigned Assessor:</th><td><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$induction->assigned_assessor}'")); ?></td>
								<th>EEM:</th><td><?php echo htmlspecialchars((string)$induction->brm); ?></td>
							</tr>
							<tr>
								<th>Business Consultant:</th><td><?php echo htmlspecialchars((string)$induction->lead_gen); ?></td>
								<th>Recruiter:</th><td><?php echo htmlspecialchars((string)$induction->resourcer); ?></td>
							</tr>
							<tr>
								<th>SLA Received:</th><td><?php echo isset($listSLAReceived[$induction->sla_received])?$listSLAReceived[$induction->sla_received]:$induction->sla_received; ?></td>
<!--								<th>WFD Assessment:</th><td>--><?php //echo isset($listEWFDAssessment[$induction->wfd_assessment])?$listEWFDAssessment[$induction->wfd_assessment]:$induction->wfd_assessment; ?><!--</td>-->
							</tr>
							<tr>
								<th>Quality Category:</th><td><?php echo isset($listDiplomaWSDelivery[$induction->dip_ws_delivery])?$listDiplomaWSDelivery[$induction->dip_ws_delivery]:$induction->dip_ws_delivery; ?></td>
								<th>Re-instated:</th><td><?php echo isset($listYesNo[$induction->reinstated])?$listYesNo[$induction->reinstated]:$induction->reinstated; ?></td>
							</tr>
							<tr>
								<th>Delivery Location:</th><td><?php echo isset($listDeliveryLocation[$inductee->location_area])?$listDeliveryLocation[$inductee->location_area]:$inductee->location_area; ?></td>
								<td colspan="2">
									<table class="table">
										<caption><label>Commitment Statement: </label> <?php echo isset($listCommitmentStatement[$induction->commit_statement])?$listCommitmentStatement[$induction->commit_statement]:$induction->commit_statement; ?></caption>
										<tr>
											<td>
																		<span class="label <?php echo strpos($induction->commit_signed, 'E') !== false?'label-success':'label-danger'; ?>">
																		<?php echo strpos($induction->commit_signed, 'E') !== false?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> Employer Signed</span>
											</td>
											<td>
																		<span class="label <?php echo strpos($induction->commit_signed, 'L') !== false?'label-success':'label-danger'; ?>">
																		<?php echo strpos($induction->commit_signed, 'L') !== false?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> Learner Signed</span>
											</td>
											<td>
																		<span class="label <?php echo strpos($induction->commit_signed, 'P') !== false?'label-success':'label-danger'; ?>">
																		<?php echo strpos($induction->commit_signed, 'P') !== false?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> Parent Signed</span>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<th>Next of Kin:</th><td><?php echo $inductee->next_of_kin; ?></td>
								<th>Next of Kin (contact):</th><td><?php echo $inductee->next_of_kin_tel . ' &nbsp; ' . $inductee->next_of_kin_email; ?></td>
							</tr>
							<tr>
								<th>Learner ID:</th><td><?php echo isset($listLearnerID[$inductee->learner_id])?$listLearnerID[$inductee->learner_id]:''; ?></td>
								<th></th><td></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border"><h2 class="box-title">Grey Section</h2></div>
				<div class="box-body">
					<div class="table-responsive">
						<label>Date moved from holding section: </label> <?php echo Date::toShort($induction->date_moved_from_grey_section); ?>
						<table class="table callout">
							<tr><td><?php echo $induction->renderComments($link, 'grey_section_comments'); ?></td></tr>
						</table>
					</div>
				</div>
			</div>
			<div class="box box-primary">
				<div class="box-header with-border"><h2 class="box-title">Coordinator Comments</h2></div>
				<div class="box-body">
					<div class="table-responsive">
						<table class="table callout">
							<tr><td><?php echo $induction->renderComments($link, 'coordinator_notes'); ?></td></tr>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border"><h2 class="box-title">Induction Comments</h2></div>
				<div class="box-body">
					<?php echo $induction->renderComments($link, 'induction_notes'); ?>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border"><h2 class="box-title">Employer Contacts Notes</h2></div>
				<div class="box-body">
					<div class="table-responsive">
						<table class="table callout">
							<tr><td><?php echo $inductee->renderComments($link, 'emp_crm_contacts_notes'); ?></td></tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="tab-pane" id="tab2">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-body">
					<div class="table-responsive">
						<table class="table callout">
							<col width="150" /><col />
							<tr><th style="width:30%">Programme:</th><td><?php echo isset($listProgrammes[$inductionProgramme->programme_id])?$listProgrammes[$inductionProgramme->programme_id]:''; ?> </td></tr>
							<tr><th style="width:30%">Eligibility Test Status:</th><td><?php echo isset($listEligibilityTestStatus[$inductionProgramme->eligibility_test_status])?$listEligibilityTestStatus[$inductionProgramme->eligibility_test_status]:''; ?></td></tr>
							<tr>
								<td colspan="2">
									<table class="table no-border">
										<tr>
											<td><span class="label <?php echo strpos($inductionProgramme->eligibility_test_type, 'S') !== false?'label-success':'label-danger'; ?>"><?php echo strpos($inductionProgramme->eligibility_test_type, 'S') !== false? '<i class="fa fa-check"></i>':'<i class="fa fa-close"></i>'; ?> Standard eligibility test</span></td>
											<td><span class="label <?php echo strpos($inductionProgramme->eligibility_test_type, 'W') !== false?'label-success':'label-danger'; ?>"><?php echo strpos($inductionProgramme->eligibility_test_type, 'W') !== false? '<i class="fa fa-check"></i>':'<i class="fa fa-close"></i>'; ?> WS 1 rework</span></td>
											<td><span class="label <?php echo strpos($inductionProgramme->eligibility_test_type, 'N') !== false?'label-success':'label-danger'; ?>"><?php echo strpos($inductionProgramme->eligibility_test_type, 'N') !== false? '<i class="fa fa-check"></i>':'<i class="fa fa-close"></i>'; ?> No test required</span></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border"><h2 class="box-title">Comments</h2></div>
				<div class="box-body">
					<div class="table-responsive">
						<table class="table callout">
							<tr><td><?php echo $inductionProgramme->renderComments($link); ?></td></tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="tab-pane" id="tab3">
	<div class="row">
		<div class="col-md-12">
			<!--<table class="table callout">
											<tr><th>User</th><th>DateTime</th><th>Detail</th></tr>
											<?php
			/*											$records = DAO::getResultset($link, "SELECT * FROM notes WHERE notes.parent_id = '{$induction->id}'", DAO::FETCH_ASSOC);
									foreach($records AS $r)
									{
										echo '<tr>';
										echo '<td>' . $r['firstnames'] . ' ' . $r['surname'] . ' (' . $r['username'] . ')</td>';
										echo '<td>' . Date::to($r['created'], Date::DATETIME) . '</td>';
										echo '<td>' . $r['note'] . '</td>';
										echo '</tr>';
									}
									*/?>
										</table>-->
			<table class="table table-responsive row-border">
				<thead><tr><th>Contact Title</th><th>Contact Name</th><th>Contact Dept.</th><th>Contact Tel.</th><th>Contact Mobile</th><th>Contact Email</th><th>Detail</th><th>Action</th></tr></thead>
				<tbody>
				<?php
				$emp_contacts = DAO::getResultset($link, "SELECT * FROM organisation_contact WHERE org_id = '{$inductee->employer_id}'", DAO::FETCH_ASSOC);
				if(count($emp_contacts) == 0)
					echo '<tr><td colspan="8">No CRM contact is found for learner\'s employer</td> </tr>';
				else
				{
					$emp_crm_contacts = explode(',', $inductee->emp_crm_contacts);
					foreach($emp_contacts AS $contact)
					{
						$checked = in_array($contact['contact_id'], $emp_crm_contacts)?'checked':'';
						echo '<tr>';
						echo '<td>' . $contact['contact_title'] . '</td>';
						echo '<td>' . $contact['contact_name'] . '</td>';
						echo '<td>' . $contact['contact_department'] . '</td>';
						echo '<td>' . $contact['contact_telephone'] . '</td>';
						echo '<td>' . $contact['contact_mobile'] . '</td>';
						echo '<td>' . $contact['contact_email'] . '</td>';
						echo '<td>' . $contact['contact_type'] . '</td>';
						echo '<td><input class="chkSelectedCRMContacts" type="checkbox" name="selectedCRMContacts[]" value="' . $contact['contact_id'] . '" ' . $checked . ' /></td>';
						echo '</tr>';
					}
					if(is_null($inductee->sunesis_username))
						echo '<tr><td colspan="8"><span class="btn btn-md btn-primary pull-right" onclick="saveCRMContactsForInductee();"><i class="fa fa-save"></i> Save</span></td></tr>';
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="tab-pane" id="tab4">
	<div class="row">
		<div class="col-sm-12">
			<span class="lead">Audit</span>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="callout callout-info">
				<i class="fa fa-info-circle"></i> Please note that currently only the following fields changes are audited.
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<ul>
				<li>Induction Status</li>
				<li>SLA Received</li>
				<li>Assigned Assessor</li>
			</ul>
		</div>
	</div>

	<?php
	$notes = DAO::getResultset($link, "SELECT * FROM notes WHERE notes.parent_table = 'induction' AND notes.parent_id = '{$induction->id}' ORDER BY id DESC", DAO::FETCH_ASSOC);
	if(count($notes) == 0)
		echo '<i>No audit history found for the above mentioned fields of this record.</i>';
	else
	{
		echo '<div class="table-responsive">';
		echo '<table class="table table-bordered">';
		echo '<tr><th>User</th><th>Changes</th></tr>';
		foreach($notes AS $note)
		{
			echo '<tr>';
			echo '<td>';
			echo '<code>' . $note['username'] . '</code> - ';
			echo $note['firstnames'] . ' ' . $note['surname'] . '<br>';
			echo Date::to($note['modified'], DATE::DATETIME);
			echo '</td>';
			echo '<td>';
			echo nl2br($note['note']);
			echo '</td>';
			echo '</tr>';
		}
		echo '</table> ';
		echo '</div>';
	}
	?>

</div>

</div>
</div>
</div>
</div>
</div>

</div>

<div class="loading-image" style="display: none;"></div>
<div id="dialogConvertToLearner"></div>

<form name="frmDeleteInduction" id="frmDeleteInduction" method="POST" action="do.php?_action=ajax_tracking">
	<input type="hidden" name="subaction" value="delete_induction" />
	<input type="hidden" name="inductee_id" value="<?php echo $inductee->id; ?>" />
</form>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>

<script>
	var phpInductionStatus = 'status_<?php echo $induction->induction_status; ?>';
	$("#"+phpInductionStatus).prop("class", "callout callout-info");
	$("#"+phpInductionStatus).html($("#"+phpInductionStatus).html()+'<span title="Number of days in this status" class="notification-counter"><?php echo $days_in_current_induction_status; ?></span>');

	$(function(){
		toastr.options = {
			"closeButton": true,
			"progressBar": true,
			"preventDuplicates": true,
			"positionClass": "toast-bottom-right",
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

		$("#dialogConvertToLearner").dialog({
			autoOpen: false,
			modal: true,
			width: 'auto',
			title: "Matching records",
			buttons: {
				Close: function () {
					$(this).dialog('close');
				}
			}
		}).css("background", "#FFF");

	});

	function checkExistingRecordsBeforeCreation()
	{
		if($('#training_provider_location_id').val() == '')
		{
			alert('Please select the provider location');
			return;
		}
		$.ajax({
			type:'GET',
			url:'do.php?_action=ajax_tracking&subaction=checkExistingRecordsBeforeCreation',
			data: { firstnames: "<?php echo addslashes((string)$inductee->firstnames); ?>", surname: "<?php echo addslashes((string)$inductee->surname); ?>", dob: "<?php echo $inductee->dob; ?>" },
			dataType: 'json',
			async: true,
			beforeSend: function(){
				$(".loading-image").show();
			},
			success: function(result) {
				if(result != "no_matching_records")
				{
					var html = '<span class="btn btn-sm btn-primary pull-left" onclick="createLinkedSunesisAccount();">Enrol Only</span>';
					html += '<span class="btn btn-sm btn-primary pull-right" onclick="createNewSunesisAccount();">Create New & Enrol</span><br>';
					html += '<form name="frmInducteeConversion" id="frmInducteeConversion" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">';
					html += '<input type="hidden" name="_action" value="ajax_tracking" />';
					html += '<input type="hidden" name="subaction" value="createLinkedSunesisAccount" />';
					html += '<input type="hidden" name="inductee_id" value="<?php echo $inductee->id; ?>" />';
					html += '<input type="hidden" name="training_provider_location_id" value="'+$('#training_provider_location_id').val()+'" />';
					html += '<input type="hidden" name="training_contract_id" value="'+$('#training_contract_id').val()+'" />';
					html += '<br>';
					html += '<table class="table table-bordered small">';
					html += '<tr><th></th><th>Username</th><th>First Name(s)</th><th>Surname</th><th>Details</th></tr>';
					$.each(result, function(k, matching_record){
						html += '<tr>';
						html += '<td><input type="radio" name="selectedLearnerForConversion" value="'+matching_record["id"]+'" </td>';
						html += '<td>'+matching_record["username"]+'</td>';
						html += '<td>'+matching_record["firstnames"]+'</td>';
						html += '<td>'+matching_record["surname"]+'</td>';
						html += '<td class="small">';
						html += '<span class="text-bold">Gender: </span>'+matching_record["gender"]+'<br>';
						html += '<span class="text-bold">DOB: </span>'+matching_record["date_of_birth"]+'<br>';
						html += '<span class="text-bold">NI: </span>'+matching_record["ni"]+'<br>';
						html += '<span class="text-bold">Address: </span>'+matching_record["home_address_line_1"]+', '+matching_record["home_postcode"];
						html += '</td>';
						html += '</tr>';
					});
					html += '</table>';
					html += '</form>';
					$("#dialogConvertToLearner").dialog('open').html(html).css({height:"350px", overflow:"auto"});
				}
				else
				{
					var client = ajaxRequest('do.php?_action=ajax_tracking&subaction=createNewSunesisAccount&inductee_id=<?php echo $inductee->id; ?>&training_provider_location_id='+$('#training_provider_location_id').val()+'&training_contract_id='+$('#training_contract_id').val(), null, null, createSunesisAccountCallback);
				}
				$(".loading-image").hide();
			},
			error: function(error){
				console.log(error);
				$(".loading-image").hide();
			}
		});
	}

	function createLinkedSunesisAccount()
	{
		if($('input[name=selectedLearnerForConversion]:checked', '#frmInducteeConversion').val() === undefined)
		{
			alert('Please select learner from the list');
			return;
		}

		$(".loading-image").show();
		var myForm = document.forms["frmInducteeConversion"];
		var client = ajaxPostForm(myForm, createSunesisAccountCallback);
	}
	function createNewSunesisAccount()
	{
		if(!confirm('This action will create new learner record and training record, are you sure you want to continue?'))
			return;

		if(validateContract() == 'Unsuccessful')
		{
			alert("The induction date is not valid for this contract. ");
			return false;
		}

		$(".loading-image").show();
		var client = ajaxRequest('do.php?_action=ajax_tracking&subaction=createNewSunesisAccount&inductee_id=<?php echo $inductee->id; ?>&training_provider_location_id='+$('#training_provider_location_id').val()+'&training_contract_id='+$('#training_contract_id').val(), null, null, createSunesisAccountCallback);
	}

	function createSunesisAccountCallback(response)
	{
		$(".loading-image").hide();
		if(response.status == 200)
		{
			window.location.reload();
		}
		else
		{
			alert(response.responseText);
		}
	}

	$(function(){
		$('.chkSelectedCRMContacts').iCheck({
			checkboxClass: 'icheckbox_flat-red',
			radioClass: 'iradio_flat-red'
		});
	});

	function saveCRMContactsForInductee()
	{
		var stringCRMContacts = [];
		$("input[name='selectedCRMContacts[]']").each( function () {
			if(this.checked)
				stringCRMContacts.push(this.value);
		});

		stringCRMContacts = stringCRMContacts.join(',');

		var client = ajaxRequest('do.php?_action=ajax_tracking&subaction=saveCRMContactsForInductee&inductee_id=<?php echo $inductee->id; ?>&contact_ids=' + stringCRMContacts);

		toastr.success('Saved successfully');
	}

	function validateContract()
	{
		var contractId = $('#training_contract_id').val();
		var startDate = '<?php echo isset($induction->induction_date) ? Date::toShort($induction->induction_date) : ''; ?>';
		var targetDate = '<?php echo isset($induction->planned_end_date) ? Date::toShort($induction->planned_end_date) : ''; ?>';
		if(startDate == '')
			return 'Unsuccessful';

		var postData = 'contract_id=' + contractId
			+ '&startDate=' + startDate
			+ '&targetDate=' + targetDate;

		var request = ajaxRequest('do.php?_action=verify_contract', postData);

		return request.responseText;
	}

	function deleteInduction()
	{
		if(!confirm("This action is irreversible, are you sure you want to continue?"))
		{
			return;
		}

		var myForm = document.forms["frmDeleteInduction"];
		myForm.submit();
	}

</script>
</body>
</html>
