<?php /* @var $vo User */ ?>
<?php
$valid_postcode = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lookup_wmca_postcode WHERE postcode = '{$vo->home_postcode}'");

$hs_form = DAO::getObject($link, "SELECT * FROM crm_learner_hs_form WHERE learner_id = '{$id}'");
if (!isset($hs_form->learner_id)) 
{
    $hs_form = new stdClass();
    $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM crm_learner_hs_form");
    foreach ($records as $key => $value)
        $hs_form->$value = null;
    $hs_form->learner_id = $vo->id;
}

$registration = new Registration();
$registrationId = DAO::getSingleValue($link, "SELECT id FROM registrations WHERE entity_id = '{$vo->id}' AND entity_type = 'User'");
if ($registrationId != '') {
    $registration = Registration::loadFromDatabase($link, $registrationId);
}
$selectedRuis = explode(',', $registration->RUI);
$selectedPmcs = explode(',', $registration->PMC);
$selectedHearUs = explode(',', $registration->hear_us);
?>
<!DOCTYPE html>

<head xmlns="http://www.w3.org/1999/html">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Learner</title>

    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css" />
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote-bs3.css">


    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style type="text/css">
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="col-lg-12">
            <div class="banner">
                <div class="Title" style="margin-left: 6px;">View Learner [<?php echo $vo->firstnames . ' ' . $vo->surname; ?>]</div>
                <div class="ButtonBar">
                    <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                    <span class="btn btn-sm btn-default" onclick="window.location.href='do.php?_action=edit_learner_duplex&id=<?php echo $vo->id; ?>&organisations_id=<?php echo $vo->employer_id; ?>';"><i class="fa fa-edit"></i> Edit</span>
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
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="<?php echo $photopath; ?>" alt="User profile picture">
                        <span class="profile-username"><?php echo htmlspecialchars((string)$vo->firstnames) . ' ' . htmlspecialchars(strtoupper($vo->surname)); ?></span>
                        <p class="text-muted"><?php echo htmlspecialchars((string)$vo->job_role); ?></p>
                        <div class="col-sm-12 invoice-col">
                            <a class="text-bold" href="do.php?_action=read_employer_v3&id=<?php echo isset($vo->org->id) ? $vo->org->id : ''; ?>">
                                <?php echo isset($vo->org->legal_name) ? $vo->org->legal_name : ''; ?>
                            </a>
                            <br>
                            <?php echo $vo->loc->address_line_3 != '' ? $vo->loc->address_line_3 . '<br>' : ''; ?>
                            <?php echo $vo->loc->address_line_4 != '' ? $vo->loc->address_line_4 . '<br>' : ''; ?>
                        </div>
                    </div>
                </div>
                <div class="box box-info box-solid">
                    <div class="box-header with-border">
                        <span class="box-title">Personal Details</span>
                    </div>
                    <div class="box-body no-padding">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th style="width:30%">Gender:</th>
                                    <td><?php echo htmlspecialchars((string)$gender_description); ?></td>
                                </tr>
                                <tr>
                                    <th style="width:30%">Date of Birth:</th>
                                    <td>
                                        <?php
                                        echo htmlspecialchars(Date::toMedium($vo->dob));
                                        if ($vo->dob) {
                                            echo ' &nbsp; <label class="label label-info" id="lblAgeToday">' . Date::dateDiff(date("Y-m-d"), $vo->dob) . '</label>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width:30%" class="small">IMI Redeem Code:</th>
                                    <td><?php echo htmlspecialchars((string)$vo->imi_redeem_code); ?></td>
                                </tr>
                                <tr>
                                    <th style="width:30%" class="small">IMI Candidate Number:</th>
                                    <td><?php echo htmlspecialchars((string)$vo->imi_candidate_number); ?></td>
                                </tr>
                                <tr>
                                    <th style="width:30%">NI Number:</th>
                                    <td><?php echo htmlspecialchars((string)$vo->ni); ?></td>
                                </tr>
                                <tr>
                                    <th style="width:30%">Employment Status:</th>
                                    <td><?php echo htmlspecialchars((string)$vo->duplex_emp_status); ?></td>
                                </tr>
                                <tr>
                                    <th style="width:30%">Funding Available:</th>
                                    <td><?php echo htmlspecialchars((string)$vo->duplex_funding_available); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="box  box-info box-solid">
                    <div class="box-header with-border"><span class="box-title">Contact Information</span></div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <strong><i class="fa fa-map-marker margin-r-5"></i> Work Contact Details</strong>
                        <address>
                            <?php
                            echo trim($work_address->address_line_1) != '' ? htmlspecialchars((string)$work_address->address_line_1) . '<br>' : '';
                            echo trim($work_address->address_line_2) != '' ? htmlspecialchars((string)$work_address->address_line_2) . '<br>' : '';
                            echo trim($work_address->address_line_3) != '' ? htmlspecialchars((string)$work_address->address_line_3) . '<br>' : '';
                            echo trim($work_address->address_line_4) != '' ? htmlspecialchars((string)$work_address->address_line_4) . '<br>' : '';
                            echo trim($work_address->postcode) != '' ? htmlspecialchars((string)$work_address->postcode) . '<br>' : '';
                            echo trim($vo->work_telephone) != '' ? '<span class="fa fa-phone"></span> ' . htmlspecialchars((string)$vo->work_telephone) . '<br>' : '';
                            echo trim($vo->work_mobile) != '' ? '<span class="fa fa-mobile-phone"></span> ' . htmlspecialchars((string)$vo->work_mobile) . '<br>' : '';
                            echo trim($vo->work_email) != '' ? '<span class="fa  fa-envelope"></span> <a href="mailto:' . $vo->work_email . '">' . htmlspecialchars((string)$vo->work_email) . '</a>' : '';
                            ?>
                        </address>

                        <hr>

                        <strong><i class="fa fa-map-marker margin-r-5"></i> Home Contact Details</strong>

                        <address>
                            <?php
                            echo trim($home_address->address_line_1) != '' ? htmlspecialchars((string)$home_address->address_line_1) . '<br>' : '';
                            echo trim($home_address->address_line_2) != '' ? htmlspecialchars((string)$home_address->address_line_2) . '<br>' : '';
                            echo trim($home_address->address_line_3) != '' ? htmlspecialchars((string)$home_address->address_line_3) . '<br>' : '';
                            echo trim($home_address->address_line_4) != '' ? htmlspecialchars((string)$home_address->address_line_4) . '<br>' : '';
                            if (trim($home_address->postcode) != '') {
                                echo $valid_postcode != '' ?
                                    '<span class="text-success">' . $home_address->postcode . '</span><br>' :
                                    '<span class="text-danger">' . $home_address->postcode . '</span><br>';
                            } else {
                                echo '<br>';
                            }
                            echo trim($vo->home_telephone) != '' ? '<span class="fa fa-phone"></span> ' . htmlspecialchars((string)$vo->home_telephone) . '<br>' : '';
                            echo trim($vo->home_mobile) != '' ? '<span class="fa fa-mobile-phone"></span> ' . htmlspecialchars((string)$vo->home_mobile) . '<br>' : '';
                            echo trim($vo->home_email) != '' ? '<span class="fa  fa-envelope"></span> <a href="mailto:' . $vo->home_email . '">' . htmlspecialchars((string)$vo->home_email) . '</a>' : '';

                            ?>
                        </address>

                        <hr>

                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="pull-right">
                    <?php
                    if ($vo->home_postcode != '') {
                        echo $valid_postcode > 0 ? '&nbsp;<label class="label label-success" style="font-size: medium">Valid Postcode</label>&nbsp;' : '&nbsp;<label class="label label-danger" style="font-size: medium">Invalid Postcode</label>&nbsp;';
                    }
                    echo $vo->crb == 1 ? '&nbsp;<label class="label label-danger" style="font-size: medium">Archived</label>&nbsp;' : '&nbsp;&nbsp;';
                    echo $vo->ni == '' ? '&nbsp;<label class="label label-danger" style="font-size: medium">No NI Number</label>&nbsp;' : '&nbsp;&nbsp;';
                    ?>
                </div>
                <div class="nav-tabs-custom bg-gray-light">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_details" data-toggle="tab">Details</a></li>
                        <li><a href="#tab_emails" data-toggle="tab">Emails</a></li>
                        <li><a href="#tab_registration" data-toggle="tab">Registration Info.</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="tab_details">

                            <div class="row">

                                <div class="col-sm-12">
                                    <div class="box  box-info box-solid">
                                        <div class="box-header with-border">
                                            <span class="box-title">Training Dates</span>
                                        </div>
                                        <div class="box-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-condensed">
                                                    <tr class="bg-gray">
                                                        <th></th>
                                                        <th>Level</th>
                                                        <th>Start Date</th>
                                                        <th>End Date</th>
                                                        <th>Duration</th>
                                                        <th>Venue</th>
                                                        <th>Trainer</th>
                                                        <th>Booked Date</th>
                                                        <th>Progress</th>
                                                    </tr>
                                                    <?php
                                                    $_sql = <<<SQL
SELECT 
	crm_training_schedule.*, training.`id` AS training_id, training.`status` AS training_status, training.booked_date,
	(SELECT id FROM learner_feedbacks WHERE learner_feedbacks.`learner_id` = training.`learner_id` AND learner_feedbacks.`training_id` = training.`id`) AS feedback_id,
    training.learner_id, IF(training.`vocanto_progress` IS NULL, 0, training.`vocanto_progress`) AS vocanto_progress
FROM 
	crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id`
WHERE 
	training.`learner_id` = '{$vo->id}'
ORDER BY crm_training_schedule.`training_date`
SQL;
                                                    $training_dates_records = DAO::getResultset($link, $_sql, DAO::FETCH_ASSOC);
                                                    if (count($training_dates_records) == 0) {
                                                        echo '<tr><td colspan="6"><i>No training dates have been assigned for this learner.</i></td></tr>';
                                                    } else {
                                                        foreach ($training_dates_records as $tr_row) {
                                                            $class = $tr_row['training_status'] == 2 ? 'bg-green' : '';
                                                            //echo HTML::viewrow_opening_tag('do.php?_action=view_edit_crm_schedule&id='.$tr_row['id']);
                                                            echo '<tr>';
                                                            echo '<td class="' . $class . '">';
                                                            echo '<span title="Navigate to the schedule screen" class="btn btn-xs btn-info" onclick="window.location.href=\'do.php?_action=view_edit_crm_schedule&id=' . $tr_row['id'] . '\';"><i class="fa fa-folder"></i></span>&nbsp;';
                                                            echo '<span title="Update training status" class="btn btn-xs btn-primary" onclick="updateStatus(' . $tr_row['training_id'] . ', ' . $tr_row['training_status'] . ', ' . $tr_row['vocanto_progress'] . ');"><i class="fa fa-check"></i></span>&nbsp;';
                                                            if ($tr_row['training_status'] == 2) {
                                                                echo $tr_row['feedback_id'] == '' ?
                                                                    '<span title="Send feedback email to learner" class="btn btn-xs btn-info" onclick="sendLearnerFeedbackEmail(\'' . $tr_row['training_id'] . '\');"><i class="fa fa-envelope"></i></span>&nbsp;' :
                                                                    '<span title="View learner feeedback" class="btn btn-xs btn-info" onclick="window.location.href=\'do.php?_action=read_feedback&id=' . $tr_row['feedback_id'] . '\'"><i class="fa fa-comments"></i></span>&nbsp;';
                                                            }
                                                            echo '</td>';
                                                            echo '<td class="' . $class . '">' . $tr_row['level'] . '</td>';
                                                            echo '<td class="' . $class . '">' . Date::to($tr_row['training_date'], Date::LONG) . '</td>';
                                                            echo '<td class="' . $class . '">' . Date::to($tr_row['training_end_date'], Date::LONG) . '</td>';
                                                            echo '<td class="' . $class . '">' . $tr_row['duration'] . '</td>';
                                                            echo '<td class="' . $class . '">' . $tr_row['venue'] . '</td>';
                                                            echo '<td class="' . $class . '">' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$tr_row['trainer']}'") . '</td>';
                                                            echo '<td class="' . $class . '">' . Date::toShort($tr_row['booked_date']) . '</td>';
                                                            echo '<td>';
                                                            echo AttendanceHelper::renderWeeklyAttendanceStatusIcons($link, $tr_row['learner_id'], $tr_row['id']);
                                                            echo AttendanceHelper::duplexTrainingProgress($link, $tr_row['training_id']);
                                                            echo '</td>';
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

                            <?php if (trim($vo->x509_serial) != '') { ?>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="box box-primary">
                                            <div class="box-header with-border">
                                                <span class="box-title">Notes </span>
                                            </div>
                                            <div class="box-body">
                                                <?php echo nl2br((string) $vo->x509_serial); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php
                            $this->generateSignatureImageFromHsForm($link, $hs_form);
                            ?>
                            <div class="row">
                                <div class="col-sm-12">
                                    
                                    <div class="box box-primary">
                                        <div class="box-header with-border">
                                            <span class="box-title">
                                                Health & Safety Form <br><br>
                                                <span class="label 
                                                <?php echo $hs_form->learner_sign != '' ? 'label-success' : 'label-danger'; ?>">
                                                    <?php echo ($hs_form->learner_sign != '') ? '<span class="fa fa-check"></span>' : '<span class="fa fa-close"></span>'; ?> Signed by learner</span>
                                            </span>
                                            <div class="box-tools">
                                                <span class="btn btn-sm btn-primary" onclick="window.location.href='do.php?_action=edit_duplex_hs_form&id=<?php echo $hs_form->learner_id; ?>'">
                                                    <i class="fa fa-edit"></i> Edit HS Form
                                                </span>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <table class="table-boredered table-condensed" style="width: 100%;">
                                                <tr class="bg-gray-light">
                                                    <th colspan="4">SECTION 1: Delegate Details</th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span class="text-bold">Name: </span><br> <?php echo $vo->firstnames . ' ' . $vo->surname; ?>
                                                    </td>
                                                    <td>
                                                        <span class="text-bold">DOB: </span> <br><?php echo Date::toShort($vo->dob); ?>
                                                    </td>
                                                    <td>
                                                        <span class="text-bold">Job Role: </span><br>
                                                        <?php echo $vo->job_role; ?>
                                                    </td>
                                                    <td>
                                                        <span class="text-bold">Company Name: </span><br>
                                                        <a href="do.php?_action=read_employer_v3&id=<?php echo isset($vo->org->id) ? $vo->org->id : ''; ?>">
                                                            <?php echo isset($vo->org->legal_name) ? $vo->org->legal_name : ''; ?>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span class="text-bold">Home Postcode: </span><br>
                                                        <?php echo $vo->home_postcode; ?>
                                                    </td>
                                                    <td>
                                                        <span class="text-bold">Email: </span><br>
                                                        <?php echo $vo->home_email; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                            <br>
                                            <table class="table table-bordered table-condensed">
                                                <tr class="bg-gray-light">
                                                    <th colspan="3">SECTION 2: Experience (to be completed by the delegate)</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="3">
                                                        In order to attend the Electric vehicle training please complete the required fields below:
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td style="width: 60%;">
                                                        I have extensive experience working with mechanical, electrical and an awareness of hazardous voltage components and systems.
                                                    </td>
                                                    <td>
                                                        <?php echo $hs_form->s2c1 == '1' ? 'Yes' : ($hs_form->s2c1 == '0' ? 'No' : ''); ?>
                                                    </td>
                                                    <td style="width: 30%;">
                                                        <?php echo $hs_form->s2d1; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 60%;">
                                                        I have qualifications and experience in the motor trade.
                                                    </td>
                                                    <td>
                                                        <?php echo $hs_form->s2c2 == 1 ? 'Yes' : ($hs_form->s2c2 == '0' ? 'No' : ''); ?>
                                                    </td>
                                                    <td style="width: 30%;">
                                                        <?php echo $hs_form->s2d2; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 60%;">
                                                        I have a thorough knowledge of Health and Safety best practice.
                                                    </td>
                                                    <td>
                                                        <?php echo $hs_form->s2c3 == 1 ? 'Yes' : ($hs_form->s2c3 == '0' ? 'No' : ''); ?>
                                                    </td>
                                                    <td style="width: 30%;">
                                                        <?php echo $hs_form->s2d3; ?>
                                                    </td>
                                                </tr>

                                            </table>
                                            <br>
                                            <table class="table table-bordered table-condensed">
                                                <tr class="bg-gray-light">
                                                    <th colspan="3">SECTION 3: Medical History</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="2">
                                                        <p>Please read and complete the following details in order to attend this course;</p>
                                                        <p>Any pre-existing medical conditions which might prevent involvement</p>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td style="width: 80%;">Do you have, or require the use of, a pacemaker?</td>
                                                    <td><?php echo $hs_form->s3c1 == 1 ? '<span class="text-red">Yes</span>' : ($hs_form->s3c1 == '0' ? '<span class="text-green">No</span>' : ''); ?></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 80%;">Do you have, or require the use of, an ICD (implantable cardioverter defibrillator)?</td>
                                                    <td><?php echo $hs_form->s3c4 == 1 ? '<span class="text-red">Yes</span>' : ($hs_form->s3c4 == '0' ? '<span class="text-green">No</span>' : ''); ?></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 80%;">Do you have, or require the use of, an insulin pump?</td>
                                                    <td><?php echo $hs_form->s3c5 == 1 ? '<span class="text-red">Yes</span>' : ($hs_form->s3c5 == '0' ? '<span class="text-green">No</span>' : ''); ?></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 80%;">
                                                        Do you have a medical condition and/or have had a surgical procedures that would prevent you
                                                        from working on or near systems or components containing hazardous voltage and magnetic emissions?
                                                    </td>
                                                    <td>
                                                        <?php echo $hs_form->s3c2 == 1 ? '<span class="text-red">Yes</span>' : ($hs_form->s3c2 == '0' ? '<span class="text-green">No</span>' : ''); ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 80%;">
                                                        Do you have any learning difficulties that we need to be aware of?
                                                    </td>
                                                    <td>
                                                        <?php echo $hs_form->s3c6 == 1 ? '<span class="text-red">Yes</span>' : ($hs_form->s3c6 == '0' ? '<span class="text-green">No</span>' : ''); ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        If yes, please provide details about your learning difficulty:<br>
                                                        <?php echo nl2br((string) $hs_form->s3c6_detail); ?>
                                                    </td>
                                                </tr>

                                            </table>
                                            <br>
                                            <table class="table table-bordered table-condensed">
                                                <tr class="bg-gray-light">
                                                    <th colspan="3">SECTION 3a: Eyesight</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="2">
                                                        <p>Please read and complete the following details in order to attend this course;</p>
                                                        <p>Any pre-existing medical conditions which might prevent involvement</p>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td style="width: 80%;">
                                                        I can clearly distinguish the colour "orange".
                                                    </td>
                                                    <td>
                                                        <?php echo $hs_form->s3c3 == 1 ? '<span class="text-green">Yes</span>' : ($hs_form->s3c3 == '0' ? '<span class="text-red">No</span>' : ''); ?>
                                                    </td>
                                                </tr>

                                            </table>
                                            <br>
                                            <table class="table table-bordered table-condensed">
                                                <tr class="bg-gray-light">
                                                    <th colspan="3">SECTION 4: Acknowledgement (to be completed by the delegate)</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="2">
                                                        <p>Please read carefully the statements below and tick the box <u>only</u> if you agree fully agree with the statement</p>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td style="width: 80%;">
                                                        The information that I have given is accurate to the best of my knowledge at the time of signing this document.
                                                    </td>
                                                    <td>
                                                        <?php echo $hs_form->s4c1 == 1 ? 'Yes' : ($hs_form->s4c1 == '0' ? 'No' : ''); ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 80%;">
                                                        I agree that if any of the information should change, I will inform my service manager, as soon as reasonably possible.
                                                    </td>
                                                    <td>
                                                        <?php echo $hs_form->s4c2 == 1 ? 'Yes' : ($hs_form->s4c2 == '0' ? 'No' : ''); ?>
                                                    </td>
                                                </tr>

                                            </table>
                                            <table class="table bordered table-condensed">
                                                <tr>
                                                    <td>
                                                        <img src="do.php?_action=generate_image&<?php echo $hs_form->learner_sign != '' ? $hs_form->learner_sign : 'title=Not Signed&font=Signature_Regular.ttf&size=25'; ?>" />
                                                        <br>
                                                        <?php echo Date::to($hs_form->signed_at, Date::DATETIME); ?>
                                                    </td>
                                                </tr>
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
                            <?php echo $this->showSentEmails($link, $vo); ?>
                        </div>
                        <div class="tab-pane" id="tab_registration">
                            <span class="lead">Registration Info</span>
                            <br>
                            <?php 
                            if($registration->is_finished == 'Y')
                            {
                                echo '<span class="label label-success"><i class="fa fa-check"></i> Completed by Learner</span> &nbsp; '; 
                            }
                            else
                            {
                                echo '<span class="label label-warning"><i class="fa fa-times"></i> Not Completed by Learner</span> &nbsp; '; 
                            }
                            if($registration->is_synced == 'Y')
                            {
                                echo '<span class="label label-success"><i class="fa fa-check"></i> Synchonised</span>'; 
                            }
                            else
                            {
                                echo '<span class="label label-warning"><i class="fa fa-times"></i> Not Synchronised</span>'; 
                            }
                            ?>
                            <p><br></p>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="panel-body fieldValue" style="margin-bottom: 5px;">
                                        <table class="table row-border">
                                            <caption class="text-bold text-center">Personal Details</caption>
                                            <tr>
                                                <th>Title:</th>
                                                <td><?php echo $registration->learner_title; ?></td>
                                            </tr>
                                            <tr>
                                                <th>First Name(s):</th>
                                                <td><?php echo $registration->firstnames; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Surname:</th>
                                                <td><?php echo $registration->surname; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Date of Birth:</th>
                                                <td><?php echo Date::toShort($registration->dob); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Current Postcode:</th>
                                                <td><?php echo $registration->home_postcode; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Current Address Line 1:</th>
                                                <td><?php echo $registration->home_address_line_1; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Current Address Line 2:</th>
                                                <td><?php echo $registration->home_address_line_2; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Current Address Line 3 (Town):</th>
                                                <td><?php echo $registration->home_address_line_3; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Current Address Line 4 (County):</th>
                                                <td><?php echo $registration->home_address_line_4; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Telephone Number:</th>
                                                <td><?php echo $registration->home_telephone; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Mobile Phone:</th>
                                                <td><?php echo $registration->home_mobile; ?></td>
                                            </tr>
                                            <tr>
                                                <th>National Insurnace:</th>
                                                <td><?php echo $registration->ni; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Gender:</th>
                                                <td><?php echo $registration->getGenderDescription(); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Ethnicity:</th>
                                                <td><?php echo $registration->gerEthnicityDescription($link); ?></td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="panel-body fieldValue">
                                        <table class="table row-border">
                                            <tr>
                                                <th>Sign</th>
                                                <td><?php echo $registration->learner_sign != '' ? '<img src="' . $registration->learner_sign . '" alt="Sign">' : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Sign Date</th>
                                                <td><?php echo Date::toShort($registration->learner_sign_date); ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="panel-body fieldValue" style="margin-bottom: 5px;">
                                        <table class="table row-border">
                                            <caption class="text-bold text-center">Household Situation</caption>
                                            <?php
                                            foreach (LookupHelper::getDDLHhs() as $entry) {
                                                echo '<tr>';
                                                echo '<td>' . $entry[1] . '</td>';
                                                echo $entry[0] == $registration->hhs ? '<td><i class="fa fa-check fa-lg"></i></td>' : '<td></td>';
                                                echo '</tr>';
                                            }
                                            ?>
                                        </table>
                                    </div>

                                    <div class="panel-body fieldValue" style="margin-bottom: 5px;">
                                        <table class="table row-border">
                                            <tr>
                                                <th>Is learner currently caring for children or other adults?</th>
                                                <td>
                                                    <?php echo $registration->is_finished == 'Y' ? ($registration->currently_caring == 1 ? 'Yes' : 'No') : ''; ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="panel-body fieldValue" style="margin-bottom: 5px;">
                                        <table class="table row-border">
                                            <caption class="text-bold text-center">Emergency Contacts</caption>
                                            <tr>
                                                <td>
                                                    <?php
                                                    echo $registration->em_con_rel1 . '<br>';
                                                    echo $registration->em_con_title1 . ' ' . $registration->em_con_name1 . '<br>';
                                                    echo $registration->em_con_tel1 . '<br>';
                                                    echo $registration->em_con_mob1 . '<br>';
                                                    echo $registration->em_con_email1;
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo $registration->em_con_rel2 . '<br>';
                                                    echo $registration->em_con_title2 . ' ' . $registration->em_con_name2 . '<br>';
                                                    echo $registration->em_con_tel2 . '<br>';
                                                    echo $registration->em_con_mob2 . '<br>';
                                                    echo $registration->em_con_email2;
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="panel-body fieldValue" style="margin-bottom: 5px;">
                                        <table class="table row-border">
                                            <caption class="text-bold text-center">LLDD Information</caption>
                                            <tr>
                                                <th>Does learner consider him/herself to have a learning difficulty, health problem or disability?</th>
                                                <td><?php echo $registration->getLlddDescription(); ?></td>
                                            </tr>
                                            <tr>
                                                <th>LLDD Categories</th>
                                                <td><?php echo $registration->getLlddCatDescription('<br>'); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Primary LLDD Category</th>
                                                <td><?php echo $registration->getPrimaryLlddDescription(); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Learner would like to benefit from a confidential interview?</th>
                                                <td>
                                                    <?php echo $registration->is_finished == 'Y' ? ($registration->confidential_interview == 1 ? 'Yes' : 'No') : ''; ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="panel-body fieldValue" style="margin-bottom: 5px;">
                                        <table class="table row-border">
                                            <caption class="text-bold text-center">Prior Attainment</caption>
                                            <tr>
                                                <th>Learner considers his/her Prior Attainment Level to be</th>
                                                <td><?php echo $registration->getPriorAttainmentDescription(); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Subject for level 6 qualification or higher if completed</th>
                                                <td><?php echo $registration->getLevel6SubjectDescription(); ?></td>
                                            </tr>

                                        </table>
                                    </div>

                                    <div class="panel-body fieldValue" style="margin-bottom: 5px;">
                                        <table class="table row-border">
                                            <caption class="text-bold text-center">Employment Status</caption>
                                            <tr>
                                                <td colspan="2" class="text-center"><?php echo $registration->getEmploymentStatusDescription(); ?></td>
                                            </tr>
                                            <?php if ($registration->employment_status == '10') { ?>
                                                <tr>
                                                    <th>Learner is self employed</th>
                                                    <td><?php echo $registration->SEI == '1' ? 'Yes' : 'No'; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Employer Name</th>
                                                    <td><?php echo $registration->emp_status_employer; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Employer Phone Number</th>
                                                    <td><?php echo $registration->emp_status_employer_tel; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Employer Contact Name</th>
                                                    <td><?php echo $registration->employer_contact_name; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Employer Contact Email</th>
                                                    <td><?php echo $registration->employer_contact_email; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Employer/Workplace Postcode</th>
                                                    <td><?php echo $registration->workplace_postcode; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Current Job Title</th>
                                                    <td><?php echo $registration->current_job_title; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Industry/Sector of your current occupation</th>
                                                    <td><?php echo $registration->current_occupation; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>How long learner was employed</th>
                                                    <td><?php echo $registration->getLoeDescription(); ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Hours per week</th>
                                                    <td><?php echo $registration->getEiiDescription(); ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Current salary</th>
                                                    <td><?php echo $registration->current_salary; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Is learner attending this bootcamp via current employer</th>
                                                    <td><?php echo $registration->viaCurrentEmployerDescription(); ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Does learner plan to work alongside the bootcamp?</th>
                                                    <td><?php echo $registration->planToWorkAlongsideDescription(); ?></td>
                                                </tr>
                                            <?php } elseif ($registration->employment_status == '11' || $registration->employment_status == '12') { ?>
                                                <tr>
                                                    <th>How long learner was un-employed before start of this course:</th>
                                                    <td><?php echo $registration->getLouDescription(); ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Did learner receive any of the benefits</th>
                                                    <td><?php echo $registration->getBsiCatDescription('<br>'); ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Was learner in Full Time Education or Training prior to start of this course?</th>
                                                    <td><?php echo $registration->PEI == '1' ? 'Yes' : 'No'; ?></td>
                                                </tr>
                                            <?php } ?>


                                        </table>
                                    </div>

                                    <div class="panel-body fieldValue" style="margin-bottom: 5px;">
                                        <table class="table row-border">
                                            <caption class="text-bold text-center">Learner agrees to be contacted for other purposes as follows:</caption>
                                            <tr>
                                                <td>About courses or learning opportunities</td>
                                                <td><?php echo $registration->is_finished == 'Y' ? (in_array(1, $selectedRuis) ? '<i class="fa fa-check fa-lg"></i>' : '<i class="fa fa-times fa-lg"></i>') : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <td>For research and evaluation purposes</td>
                                                <td><?php echo $registration->is_finished == 'Y' ? (in_array(2, $selectedRuis) ? '<i class="fa fa-check fa-lg"></i>' : '<i class="fa fa-times fa-lg"></i>') : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <td>By post</td>
                                                <td><?php echo $registration->is_finished == 'Y' ? (in_array(1, $selectedPmcs) ? '<i class="fa fa-check fa-lg"></i>' : '<i class="fa fa-times fa-lg"></i>') : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <td>By phone</td>
                                                <td><?php echo $registration->is_finished == 'Y' ? (in_array(2, $selectedPmcs) ? '<i class="fa fa-check fa-lg"></i>' : '<i class="fa fa-times fa-lg"></i>') : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <td>By email</td>
                                                <td><?php echo $registration->is_finished == 'Y' ? (in_array(3, $selectedPmcs) ? '<i class="fa fa-check fa-lg"></i>' : '<i class="fa fa-times fa-lg"></i>') : ''; ?></td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="panel-body fieldValue" style="margin-bottom: 5px;">
                                        <table class="table row-border">
                                            <caption class="text-bold text-center">Contact and Marketing Information</caption>
                                            <tr>
                                                <th>How did learner hear about us?</th>
                                                <td><?php echo $registration->getHearUsDescription('<br>'); ?></td>
                                            </tr>
                                        </table>
                                    </div>

                                </div>


                            </div>

                        </div>
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="modal fade" id="trainingUpdateModal" role="dialog" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title text-bold">Update status of this training for <?php echo $vo->firstnames . ' ' . $vo->surname; ?></h5>
                        </div>
                        <div class="modal-body">
                            <form autocomplete="off" class="form-horizontal" name="frmTrainingUpdate" id="frmTrainingUpdate" method="post" action="do.php?_action=ajax_helper">
                                <input type="hidden" name="subaction" value="update_duplex_training_status" />
                                <input type="hidden" name="training_id" value="" />
                                <div class="control-group">
                                    <label class="control-label" for="training_status">Select Status:</label>
                                    <?php echo HTML::selectChosen('training_status', [[1, 'Continuing'], [2, 'Completed']], '', false, true); ?>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="vocanto_progress">Vocanto Progress Percentage:</label>
                                    <input class="form-control optional" type="text" name="vocanto_progress" id="vocanto_progress" value="" maxlength="3" 
                                        onkeypress="return numbersonly();" />
                                    <span class="text-info small"><i class="fa fa-info-circle"></i> Enter percentage of Vocanto progress</span>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left btn-md" onclick="$('#trainingUpdateModal').modal('hide');">Cancel</button>
                            <button type="button" id="btnTrainingUpdateModalSave" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Save</button>
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
        <script src="/assets/adminlte/plugins/summernote/summernote.min.js"></script>


        <script>
            $(function() {
                $('.datepicker').datepicker({
                    format: 'dd/mm/yyyy',
                    yearRange: 'c-50:c+50'
                });

                $('.datepicker').attr('class', 'datepicker form-control');

                $('#frmEmailBody').summernote({
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

                $('#btnTrainingUpdateModalSave').on('click', function() {
                    var myForm = document.forms['frmTrainingUpdate'];
                    var client = ajaxPostForm(myForm);
                    if (client) {
                        window.location.reload();
                    }
                });
            });

            function course_id_onchange(course, event) {
                var providers_locations = document.getElementById('provider_location_id');

                if (course.value != '') {
                    course.disabled = true;

                    providers_locations.disabled = true;
                    ajaxPopulateSelect(providers_locations, 'do.php?_action=ajax_load_account_manager&subaction=load_provider_locations&course_id=' + course.value);
                    providers_locations.disabled = false;

                    course.disabled = false;
                } else {
                    emptySelectElement(providers_locations);
                }
            }

            function provider_location_id_onchange(location, event) {
                var assessors = document.getElementById('assessor');
                var tutors = document.getElementById('tutor');

                if (location.value != '') {
                    location.disabled = true;

                    assessors.disabled = true;
                    ajaxPopulateSelect(assessors, 'do.php?_action=ajax_load_account_manager&subaction=load_assessors&location_id=' + location.value);
                    assessors.disabled = false;

                    tutors.disabled = true;
                    ajaxPopulateSelect(tutors, 'do.php?_action=ajax_load_account_manager&subaction=load_tutors&location_id=' + location.value);
                    tutors.disabled = false;

                    location.disabled = false;
                } else {
                    emptySelectElement(assessors);
                    emptySelectElement(tutors);
                }
            }

            $('.btnEnrolLearner').on('click', function() {
                var form = document.forms['frmEnrolLearner'];

                if (form.elements["course_id"].value == '') {
                    return alert('Please select course');
                }
                if (form.elements["provider_location_id"].value == '') {
                    return alert('Please select location');
                }
                if (form.elements["contract_id"].value == '') {
                    return alert('Please select contract');
                }
                if (form.elements["start_date"].value == '') {
                    return alert('Please select start date');
                }
                if (form.elements["end_date"].value == '') {
                    return alert('Please select end date');
                }

                form.submit();
            });

            function load_email_template_in_frmEmail() {
                var frmEmail = document.forms["frmEmail"];
                var learner_id = '<?php echo $vo->id; ?>';
                var email_template_type = frmEmail.frmEmailTemplate.value;

                if (email_template_type == '') {
                    alert('Please select template from templates list');
                    frmEmail.frmEmailTemplate.focus();
                    return false;
                }

                function loadAndPrepareLearnerEmailTemplateCallback(client) {
                    if (client && client.status == 200) {
                        var result = $.parseJSON(client.responseText);
                        if (result.status == 'error') {
                            alert(result.message);
                            return;
                        }

                        $("#frmEmailBody").summernote('code', result.email_content);
                    }
                }

                var client = ajaxRequest('do.php?_action=ajax_email_actions&subaction=loadAndPrepareLearnerEmailTemplate' +
                    '&entity_type=learner&entity_id=' + learner_id +
                    '&template_type=' + email_template_type, null, null, loadAndPrepareLearnerEmailTemplateCallback);
            }

            function sendEmail() {
                var frmEmail = document.forms["frmEmail"];
                if (!validateForm(frmEmail)) {
                    return;
                }

                //frmEmail.frmEmailTemplate.value = "";

                var client = ajaxPostForm(frmEmail);
                if (client) {
                    if (client.responseText == 'success')
                        alert('Email has been sent successfully.');
                    else
                        alert('Unknown Email Error: Email has not been sent.');
                } else {
                    alert(client);
                }
                window.location.reload();
            }

            function viewEmail(tbl_emails_id) {
                var postData = 'do.php?_action=ajax_helper' +
                    '&subaction=view_sent_email' +
                    '&id=' + encodeURIComponent(tbl_emails_id);

                var req = ajaxRequest(postData);
                $("<div></div>").html(req.responseText).dialog({
                    id: "dlg_lrs_result",
                    title: "View Sent Email",
                    resizable: false,
                    modal: true,
                    width: 750,
                    height: 500,

                    buttons: {
                        'Close': function() {
                            $(this).dialog('close');
                        }
                    }
                });

            }

            function updateStatus(training_id, training_status, vocanto_progress) {
                var myForm = document.forms['frmTrainingUpdate'];
                myForm.elements.training_id.value = training_id;
                myForm.elements.training_status.value = training_status;
                myForm.elements.vocanto_progress.value = vocanto_progress;
                $('#trainingUpdateModal').modal('show');
            }

            function frmEmailTemplate_onchange(frmEmailTemplate) {
                var template = frmEmailTemplate.value;

                var frmEmail = document.forms["frmEmail"];

                if (frmEmailTemplate.value == "LEVEL3_CRM_EMPLOYER_CONTACT_RUDDINGTON") {
                    frmEmail.frmEmailTo.value = "<?php echo isset($organisation_contact->contact_email) ? $organisation_contact->contact_email : ''; ?>";
                } else if (frmEmailTemplate.value == "LEVEL4_CRM_EMPLOYER_CONTACT_RUDDINGTON") {
                    frmEmail.frmEmailTo.value = "<?php echo isset($organisation_contact->contact_email) ? $organisation_contact->contact_email : ''; ?>";
                } else {
                    frmEmail.frmEmailTo.value = "<?php echo $vo->home_email; ?>";
                }
            }

            function sendLearnerFeedbackEmail(training_id) {
                if (!confirm('Are you sure you want to send a feedback email to the learner?')) {
                    return;
                }

                var client = ajaxRequest('do.php?_action=ajax_email_actions&subaction=sendFeedbackEmail&training_id=' + training_id);
                if (client) {
                    alert(client.responseText);
                } else {
                    alert('Something went wrong, email is not sent.');
                }
            }
        </script>
</body>

</html>