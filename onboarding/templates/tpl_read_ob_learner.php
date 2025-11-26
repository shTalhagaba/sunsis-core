<?php /* @var $vo OnboardingLearner */ ?>
<?php /* @var $employer Organisation */ ?>
<?php /* @var $location Location */ ?>

<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>View Onboarding Learner</title>

	<link rel="stylesheet" href="css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<!-- <link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote-bs3.css"> -->


	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style type="text/css">
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
	</style>

	<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>

</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">View Onboarding Learner
				[<?php echo $vo->firstnames . ' ' . $vo->surname; ?>]
			</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default"
				      onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i
					class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <?php if($skills_analysis->signed_by_learner == 0) { ?>
                <span class="btn btn-sm btn-default" onclick="window.location.href='do.php?_action=edit_ob_learner&id=<?php echo $vo->id; ?>';"><i class="fa fa-edit"></i> Edit</span>
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

<div class="content-wrapper">

<div class="row">
	<div class="col-sm-4">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-primary">
					<div class="box-header"><span class="box-title with-header"><span
						class="lead text-bold"><?php echo htmlspecialchars($vo->firstnames) . ' ' . htmlspecialchars(strtoupper($vo->surname)); ?></span></span>
					</div>
					<div class="table-responsive">
						<table class="table">
							<tr><th>Gender</th><td><?php echo $gender_description; ?></td></tr>
							<tr><th>Date of Birth</th><td><?php echo Date::toShort($vo->dob); ?><br><label class="label label-info"><?php echo Date::dateDiff(date("Y-m-d"), $vo->dob); ?></label></td></tr>
							<?php if(in_array(DB_NAME, ["am_eet"]) && $ob_learner->dob!= '' ) { ?>
								<tr><th>Age on 31/08/2024</th><td><?php echo Date::dateDiff(date("2024-08-31"), $ob_learner->dob); ?></td></tr>
							<?php } ?>
							<tr><th>Postcode</th><td><?php echo $vo->home_postcode; ?></td></tr>
							<tr><th>Email</th><td><a href="mailto:<?php echo $vo->home_email; ?>"><?php echo $vo->home_email; ?></a></td></tr>
							<tr><th>Framework</th><td><?php echo isset($framework) ? $framework->title : ''; ?></td></tr>
							<tr><th>Employer</th><td><a href="do.php?_action=read_employer&id=<?php echo $employer->id; ?>"><?php echo $employer->legal_name; ?></a></td></tr>
							<tr>
								<th>Employer Address</th>
								<td class="small">
									<?php echo $location->address_line_1 != '' ? $location->address_line_1 . '<br>' : ''; ?>
									<?php echo $location->address_line_2 != '' ? $location->address_line_2 . '<br>' : ''; ?>
									<?php echo $location->address_line_3 != '' ? $location->address_line_3 . '<br>' : ''; ?>
									<?php echo $location->address_line_4 != '' ? $location->address_line_4 . '<br>' : ''; ?>
									<?php echo $location->postcode != '' ? $location->postcode . '<br>' : ''; ?>
								</td>
							</tr>
							<tr><th>Provider</th><td><?php echo $provider->legal_name; ?></td></tr>
							<tr>
								<th>Provider Address</th>
								<td class="small">
									<?php echo $provider_location->address_line_1 != '' ? $provider_location->address_line_1 . '<br>' : ''; ?>
									<?php echo $provider_location->address_line_2 != '' ? $provider_location->address_line_2 . '<br>' : ''; ?>
									<?php echo $provider_location->address_line_3 != '' ? $provider_location->address_line_3 . '<br>' : ''; ?>
									<?php echo $provider_location->address_line_4 != '' ? $provider_location->address_line_4 . '<br>' : ''; ?>
									<?php echo $provider_location->postcode != '' ? $provider_location->postcode . '<br>' : ''; ?>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="box box-primary">
					<div class="box-header"><span class="box-title with-header"><?php echo $vo->user_id == '' ? 'Set Eligibility' : 'Learner is converted'; ?></span></div>
					<div class="box-body">
						<?php if($vo->user_id == ''){ ?>
						<span class="text-info">
							<i class="fa fa-info-circle"></i> Use this panel to select learner eligibility for onboarding.
						</span>
						<p><br></p>
						<table class="table table-bordered">
							<form method="post" name="frmEligibility" action="<?php echo $_SERVER['PHP_SELF']; ?>">
								<input type="hidden" name="_action" value="ajax_helper" />
								<input type="hidden" name="subaction" value="saveObLearnerEligibility" />
								<input type="hidden" name="ob_id" value="<?php echo $vo->id; ?>" />
								<tr>
									<th style="width: 50%;">Learner Eligibility</th>
									<td style="width: 50%;"><?php echo HTML::selectChosen('ob_eligibility', [['Y', 'Yes Eligible'], ['N', 'Not Eligible']], $vo->is_eligible, true, true); ?></td>
								</tr>
								<tr>
									<td colspan="2">
										<span class="btn btn-success btn-md btn-block" onclick="saveEligibility();">Save Eligibility</span>
									</td>
								</tr>
							</form>
						</table>
						<?php } ?>
						<?php if($vo->user_id != '') { ?>
						<p><br></p>
						<span class="text-info">
							<i class="fa fa-info-circle"></i> This learner has been converted into Sunesis Learner.
						</span>
						<p><br></p>
						<span class="btn btn-info btn-sm" onclick="window.location.replace('do.php?_action=read_learner&id=<?php echo $vo->user_id; ?>'); ">Navigate to Learner Screen</span>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-8">
        <p>
            <span class="label <?php echo $skills_analysis->signed_by_learner == 1 ?'label-success':'label-danger'; ?>"><?php echo $skills_analysis->signed_by_learner == 1 ?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> Skills Analysis</span>
            <span class="label <?php echo $vo->learner_sign != '' ?'label-success':'label-danger'; ?>"><?php echo $vo->learner_sign != '' ?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> Onboarding Questionnaire</span>
            <span class="label <?php echo (isset($schedule->emp_sign) && $schedule->emp_sign != '') ?'label-success':'label-danger'; ?>"><?php echo (isset($schedule->emp_sign) && $schedule->emp_sign != '') ?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> Agreement Schedule 1</span>
        </p>
		<div class="nav-tabs-custom bg-gray-light">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab_ks" data-toggle="tab">Knowledge & Skills</a></li>
                <li><a href="#tab_als" data-toggle="tab">ALS</a></li>
                <li><a href="#tab_enrolment" data-toggle="tab">Enrolment</a></li>
                <li><a href="#tab_emails" data-toggle="tab">Emails</a></li>
                <li><a href="#tab_onboarding" data-toggle="tab">Additional Details</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab_ks">
					<div class="row">
					<div class="col-sm-12">
						<span class="lead">Knowledge & Skills Assessment</span>
                        <p><br></p>
                        <?php if(isset($skills_analysis->signed_by_provider) && $skills_analysis->signed_by_provider == 0) {?>
                        <span class="btn btn-primary btn-md"
                              onclick="window.location.href='do.php?_action=edit_skills_scan&ob_learner_id=<?php echo $vo->id; ?>'">
                                View and Edit Skills Assessment
                        </span>
                        <?php } ?>
                        <p><br></p>
                        <span class="btn btn-primary btn-md" onclick="window.location.href='do.php?_action=provider_view_sign_skills_scan&id=<?php echo $vo->id; ?>'">
                                View and Sign Skills Assessment
                        </span>
                        <p><br></p>
                        <?php if (in_array($vo->status, [OnboardingLearner::TYPE_CREATED, OnboardingLearner::TYPE_SS_EMAILS_ENT])) { ?>
                        <div class="row">
                            <div class="col-sm-4">
                                <span class="text-info text-bold pull-right">Skills Analysis form URL </span>
                            </div>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input id="ks_url" readonly type="text" class="form-control" value="<?php echo OnboardingHelper::generateSkillsScanUrl($vo->id); ?>">
                                    <span class="input-group-addon" title="Click to copy the URL" onclick="copyUrl('ks_url', 'copyKsUrlTooltip');"> <i class="fa fa-copy"></i></span>
                                </div>
                                <span id="copyKsUrlTooltip"></span>
                            </div>
                        </div>
                        <?php } else {
                            $this->renderKsbStatsOverview($link, $vo);
                            echo '<p><br></p>';
                            $this->renderKsbStatsDetail($link, $vo);
                            echo '<p><br></p>';
                        } ?>
                        <p></p>
					</div>
				</div>
                </div>
                <div class="tab-pane" id="tab_als">
                    <span class="lead">Learning Support / Additional Details</span><p><br></p>
                    <?php
                    echo $this->renderAlsAndAdditionalDetails($link, $vo);
                    ?>
                </div>
				<div class="tab-pane" id="tab_enrolment">
                    <p><br></p>
		    <?php if(!$vo->isNonApp($link)) { ?>
                    <span class="btn btn-primary btn-md" onclick="window.location.href='do.php?_action=enrol_ob_learner&ob_learner_id=<?php echo $vo->id; ?>'">
                           Enrol Learner
                    </span>
		    <?php } ?>
                    <div class="box  box-info">
                        <div class="box-header">
                            <div class="box-title">Training Records</div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr><th>Standard</th><th>Status</th><th>Dates</th><th></th></tr>
                                        <?php
                                        $sql = <<<SQL
SELECT
	frameworks.title AS framework_title, tr.*
FROM
	ob_learners INNER JOIN tr ON tr.ob_learner_id = ob_learners.id
	LEFT JOIN frameworks ON ob_learners.framework_id = frameworks.id
WHERE
	ob_learners.id = '{$vo->id}'
ORDER BY
	tr.id DESC
;
SQL;
                                        $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
                                        if(count($result) == 0)
                                        {
                                            echo '<tr><td colspan="4"><i>Currently, this learner has no training records.</i></td> </tr>';
                                        }
                                        else
                                        {
                                            foreach($result AS $row)
                                            {
                                                echo '<tr>';
                                                echo '<td>' . $row['framework_title'] . '</td>';
                                                if($row['status_code'] == '1')
                                                    echo '<td><label class="label label-primary">Continuing</label></td>';
                                                elseif($row['status_code'] == '2')
                                                    echo '<td><label class="label label-success">Completed</label></td>';
                                                elseif($row['status_code'] == '3')
                                                    echo '<td><label class="label label-danger">Withdrawn</label></td>';
                                                elseif($row['status_code'] == '6')
                                                    echo '<td><label class="label label-warning">Temp. Withdrawn</label></td>';
                                                else
                                                    echo '<td><label class="label label-info">' . $row['status_code'] . '</label></td>';
                                                echo '<td>';
                                                echo 'Start Date: ' . Date::toShort($row['apprenticeship_start_date']) . '<br>';
                                                echo 'Planned End Date: ' . Date::toShort($row['apprenticeship_end_date_inc_epa']) . '<br>';
                                                echo 'Duration: ' . $row['apprenticeship_duration_inc_epa'];
                                                echo '</td>';
//                                                echo '<td><span class="btn btn-sm btn-block btn-info" onclick="window.location.href=\'do.php?_action=read_training_record&id='.$row['id'].'\'"><i class="fa fa-folder-open"></i> View</span> </td>';
                                                echo '<td><span class="btn btn-sm btn-block btn-info" onclick="alert(\'Under development, will be released soon.\');"><i class="fa fa-folder-open"></i> View</span> </td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="tab-pane" id="tab_emails">
                    <span class="lead">Emails</span>

                    <p><br></p>

                    <div class="row">
                        <div class="col-sm-4 col-sm-offset-4">
                            <span id="btnCompose" class="btn btn-primary btn-block margin-bottom" onclick="$(this).hide(); $('#mailBox').hide(); $('#composeNewMessageBox').show();">Compose New Email</span>
                        </div>
                        <div class="col-sm-12" id="composeNewMessageBox" style="display: none;">
                            <?php echo $this->renderComposeNewMessageBox($link, $vo); ?>
                        </div>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered small">
                            <?php $result = DAO::getResultset($link, "SELECT * FROM emails WHERE emails.entity_type = 'ob_learners' AND emails.entity_id = '{$vo->id}' ORDER BY created DESC", DAO::FETCH_ASSOC); ?>
                            <caption class="lead text-bold text-center">Sent Emails (<?php echo count($result); ?>)</caption>
                            <tr><th>DateTime</th><th>By</th><th>To Address</th><th>From Address</th><th>Subject</th><th>Email</th></tr>
                            <?php
                            foreach($result AS $row)
                            {
                                echo '<tr>';
                                echo '<td>' . Date::to($row['created'], Date::DATETIME) . '</td>';
                                echo '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = '{$row['by_whom']}'") . '</td>';
                                echo '<td>' . $row['email_to'] . '</td>';
                                echo '<td>' . $row['email_from'] . '</td>';
                                echo '<td>' . $row['email_subject'] . '</td>';
                                echo '<td><span class="btn btn-xs btn-info" onclick="viewEmail(\''.$row['id'].'\');"><i class="fa fa-eye"></i> View Email</span> </td>';
                                echo '</tr>';
                            }
                            ?>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="tab_onboarding">
                    <span class="lead">Additional Details</span><p><br></p>
                    <p><br></p>
                    <?php if(!is_null($tr)) { ?>
                    <div class="input-group">
                        <input id="ob_url" readonly type="text" class="form-control" value="<?php echo OnboardingHelper::generateOnboardingUrl($vo->id); ?>">
                        <span class="input-group-addon" title="Click to copy the URL" onclick="copyUrl('ob_url', 'copyObUrlTooltip');"> <i class="fa fa-copy"></i></span>
                    </div>
                    <?php } ?>
                    <span id="copyObUrlTooltip"></span>
                    <p><br></p>
                    <span class="btn btn-md btn-primary"
                          onclick="window.location.href='do.php?_action=view_ea_schedule1&ob_learner_id=<?php echo $vo->id; ?>&employer_id=<?php echo $vo->employer_id; ?>'">Employer Schedule 1</span>
                    <p><br></p>

                    <span class="btn btn-primary btn-md"
                          onclick="window.location.href='do.php?_action=provider_commitment_statement&ob_learner_id=<?php echo $vo->id; ?>'">
                                View & Sign Training Plan
                    </span>
                    <p><br></p>
                    <span class="btn btn-primary btn-md"
                          onclick="window.location.href='do.php?_action=view_apprenticeship_agreement&ob_learner_id=<?php echo $vo->id; ?>'">
                                View Apprenticeship Agreement
                    </span>
                    <?php

                    ?>
                </div>
			</div>
		</div>
	</div>
</div>

<form method="post" role="form" class="form-horizontal" name="frmLearner" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<input type="hidden" name="_action" value="ajax_onboarding" />
	<input type="hidden" name="subaction" value="createSunesisLearner" />
	<input type="hidden" name="ob_id" value="<?php echo $vo->id; ?>" />
</form>



<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/summernote/summernote.min.js"></script>

<script>

$(function () {
	$('.datepicker').datepicker({
		format:'dd/mm/yyyy',
		yearRange:'c-50:c+50'
	});

	$('.datepicker').attr('class', 'datepicker');

	$('#frmEmailBody').summernote({
		toolbar:[
			['style', ['bold', 'italic', 'underline', 'clear']],
			['fontsize', ['fontsize']],
			['para', ['ul', 'ol', 'paragraph']],
			['height', ['height']],
			['insert', ['link', 'picture', 'hr']]
		],
		height:300,
		callbacks:{
			onImageUpload:function (files, editor, welEditable) {
				sendFile(files[0], editor, welEditable);
			}
		}
	});

});

function copyUrl(url, copy_ele) {
	var copyText = document.getElementById(url);
	copyText.select();
	copyText.setSelectionRange(0, 99999);
	document.execCommand('copy');
	var tooltip = document.getElementById(copy_ele);
	tooltip.innerHTML = "Copied: " + copyText.value;
	$("#" + copy_ele).show().delay(1000).hide(0);
}

function sendEmail()
{
	var frmEmail = document.forms["frmEmail"];
	if(!validateForm(frmEmail))
	{
		return;
	}

	var client = ajaxPostForm(frmEmail);
	if(client)
	{
		if(client.responseText == 'success')
			alert('Email has been sent successfully.');
		else
			alert('Unknown Email Error: Email has not been sent.');
	}
	else
	{
		alert(client);
	}
	window.location.reload();
}

function load_email_template_in_frmEmail()
{
	var frmEmail = document.forms["frmEmail"];
	var ob_learner_id = '<?php echo $vo->id; ?>';
	var email_template_type = frmEmail.frmEmailTemplate.value;

	if(email_template_type == '')
	{
		alert('Please select template from templates list');
		frmEmail.frmEmailTemplate.focus();
		return false;
	}

	function loadAndPrepareLearnerEmailTemplateCallback(client)
	{
		if(client.status == 200)
			$("#frmEmailBody").summernote("code", client.responseText);
	}

	var client = ajaxRequest('do.php?_action=ajax_email_actions&subaction=loadAndPrepareLearnerEmailTemplate' +
		'&entity_type=ob_learners&entity_id=' + ob_learner_id +
		'&template_type=' + email_template_type, null, null, loadAndPrepareLearnerEmailTemplateCallback);
}

function viewEmail(email_id)
{
	if(email_id == '')
		return;

	var postData = 'do.php?_action=ajax_onboarding'
			+ '&subaction=' + encodeURIComponent("getEmail")
			+ '&email_id=' + encodeURIComponent(email_id)
		;

	var req = ajaxRequest(postData);
	$("<div class='small'></div>").html(req.responseText).dialog({
		id: "dialogEmailView",
		title: "Email",
		resizable: false,
		modal: true,
		width: 750,
		height: 500,

		buttons: {
			'Close': function() {$(this).dialog('close');}
		}
	});
}

function saveEligibility()
{
	var frmEligibility = document.forms["frmEligibility"];
	if(!validateForm(frmEligibility))
	{
		return false;
	}

	var client = ajaxPostForm(frmEligibility);
	if(client)
    {
        alert('Eligibility saved.');
        window.location.reload();
    }
}

function viewKsbLogInfo()
{
    var ob_learner_id = '<?php echo $vo->id; ?>';

    var postData = 'do.php?_action=ajax_helper'
        + '&subaction=getOblearnerKsbLog'
        + '&ob_learner_id=' + encodeURIComponent(ob_learner_id)
    ;

    var req = ajaxRequest(postData);
    $("<div></div>").html(req.responseText).dialog({
        id: "dlg_info",
        title: "Log",
        resizable: false,
        modal: true,
        width: 'auto',
        height: 550,

        buttons: {
            'Close': function() {$(this).dialog('close');}
        }
    });

}
</script>
</body>
</html>
