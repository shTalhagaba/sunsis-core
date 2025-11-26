<?php /* @var $vo OnboardingLearner */ ?>
<?php /* @var $employer Organisation */ ?>
<?php /* @var $location Location */ ?>

<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Onboarding Learners</title>

    <link rel="stylesheet" href="css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
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
				<span class="btn btn-xs btn-default"
                      onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i
                        class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                    <span class="btn btn-xs btn-default" onclick="window.location.href='do.php?_action=add_edit_ob_learners&id=<?php echo $vo->id; ?>';"><i class="fa fa-edit"></i> Edit</span>
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
                    <div class="box box-solid box-success">
                        <div class="box-header"><span class="box-title with-header"><span
                                    class="lead text-bold"><?php echo htmlspecialchars($vo->firstnames) . ' ' . htmlspecialchars(strtoupper($vo->surname)); ?></span></span>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table">
					<tr><th>Employer</th><td><?php echo DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$vo->employer_id}'"); ?></td></tr>
                                    <tr><th>Gender</th><td><?php echo DAO::getSingleValue($link, "SELECT description FROM lookup_gender WHERE id = '{$vo->gender}'"); ?></td></tr>
                                    <tr><th>Date of Birth</th><td><?php echo Date::toShort($vo->dob); ?><br><label class="label label-info"><?php echo Date::dateDiff(date("Y-m-d"), $vo->dob); ?></label></td></tr>
				    <?php if(in_array(DB_NAME, ["am_eet"]) && $vo->dob!= '' ) { ?>
								        <tr><th>Age on 31/08/2024</th><td><?php echo Date::dateDiff(date("2024-08-31"), $vo->dob); ?></td></tr>
							        <?php } ?>
                                    <tr><th>Home Address (line 1)</th><td><?php echo $vo->home_address_line_1; ?></td></tr>
                                    <tr><th>Home Address (line 2)</th><td><?php echo $vo->home_address_line_2; ?></td></tr>
                                    <tr><th>Home Address (line 3)</th><td><?php echo $vo->home_address_line_3; ?></td></tr>
                                    <tr><th>Home Address (line 4)</th><td><?php echo $vo->home_address_line_4; ?></td></tr>
                                    <tr><th>Home Postcode</th><td><?php echo $vo->home_postcode; ?></td></tr>
                                    <tr><th>Personal Mobile</th><td><?php echo $vo->home_mobile; ?></td></tr>
                                    <tr><th>Personal Email</th><td><a href="mailto:<?php echo $vo->home_email; ?>"><?php echo $vo->home_email; ?></a></td></tr>
                                    <tr><th>Personal Telephone</th><td><?php echo $vo->home_telephone; ?></td></tr>
                                    <tr><th>Work Email</th><td><a href="mailto:<?php echo $vo->work_email; ?>"><?php echo $vo->work_email; ?></a></td></tr>
                                    <tr><th>Ethnicity</th><td><?php echo $vo->ethnicity == '' ? '' : LookupHelper::getEthnicitiesList($vo->ethnicity); ?></td></tr>
                                    <tr><th>ULN (Unique Learner Number)</th><td><?php echo $vo->uln; ?></td></tr>
                                    <tr><th>National Insurance</th><td><?php echo $vo->ni; ?></td></tr>
                                </table>
                                <hr>
                                <table class="table text-info">
                                    <tr><th>Created at</th><td><?php echo Date::to($vo->created, Date::DATETIME); ?></td></tr>
                                    <tr><th>Created by</th><td><?php echo $vo->getCreatorName($link); ?></td></tr>
                                    <tr><th>Last updated at</th><td><?php echo Date::to($vo->updated, Date::DATETIME); ?></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-sm-8">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_enrolment" data-toggle="tab" class="text-bold"><i class="fa fa-graduation-cap"></i> Enrolments</a></li>
                    <?php if(SystemConfig::getEntityValue($link, "bksb")) {?>
                    <li class=""><a href="#tab_bksb" data-toggle="tab" class="text-bold"> BKSB</a></li>
                    <?php } ?>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_enrolment">
                        <h5 class="lead">Enrolments</h5>
                        <div class="row">
                            <div class="col-sm-12">
				                <?php if(false && !in_array($vo->funding_stream, [Framework::FUNDING_STREAM_ASF, Framework::FUNDING_STREAM_BOOTCAMP])) { ?>
                                <span class="btn btn-primary btn-xs" onclick="window.location.href='do.php?_action=enrol_ob_learner&id=<?php echo $vo->id; ?>'"><i class="fa fa-graduation-cap"></i> Enrol Learner</span>
				                <?php } ?>
                                <?php if(true && !in_array($vo->funding_stream, [Framework::FUNDING_STREAM_ASF, Framework::FUNDING_STREAM_BOOTCAMP, Framework::FUNDING_STREAM_COMMERCIAL, Framework::FUNDING_STREAM_LEARNER_LOAN])) { ?>
                                <div class="btn-group btn-group-xs">
                                    <button type="button" class="btn btn-primary"><i class="fa fa-graduation-cap"></i> Enrol Learner</button>
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="do.php?_action=enrol_ob_learner&id=<?php echo $vo->id; ?>&start_after_july24=1">Start in and after August 2025</a></li>
                                        <li><a href="do.php?_action=enrol_ob_learner&id=<?php echo $vo->id; ?>">Start before August 2025</a></li>
                                    </ul>
                                </div>
                                <?php } ?>
                                <p><br></p>
                            </div>
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <tr class="bg-success">
                                        <th>System ID</th><th>Status</th><th>Programme</th><th>Dates</th><th></th>
                                    </tr>
                                    <?php
                                    $tr_ids = DAO::getSingleColumn($link, "SELECT ob_tr.id FROM ob_tr WHERE ob_tr.ob_learner_id = '{$vo->id}' ORDER BY ob_tr.practical_period_start_date");
                                    if(count($tr_ids) == 0)
                                    {
                                        echo '<tr class="text-info"><td colspan="5"><i class="fa fa-info-circle"></i> Learner is not yet enrolled to any programme.</td></tr>';
                                    }
                                    else
                                    {
                                        foreach($tr_ids AS $tr_id)
                                        {
                                            $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
                                            echo '<tr>';
					    echo '<td>' . $tr->id . '</td>';
                                            echo '<td>';
                                            switch($tr->status_code)
                                            {
                                                case TrainingRecord::STATUS_IN_PROGRESS:
                                                    echo 'In Progress';
                                                    break;
                                                case TrainingRecord::STATUS_COMPLETED:
                                                    echo 'Completed';
                                                    break;                                                    
                                                case TrainingRecord::STATUS_ARCHIVED:
                                                    echo 'Archived';
                                                    break;
                                                case TrainingRecord::STATUS_CONVERTED:
                                                    echo 'Converted to Sunesis Learner';
                                                    break;
                                                default:
                                                    break;
                                            }
                                            echo '</td>';
                                            echo '<td>' . DAO::getSingleValue($link, "SELECT title FROM frameworks WHERE id = '{$tr->framework_id}'") . '</td>';
                                            echo '<td>';
                                            echo '<span class="text-info">Practical Period Start Date:</span> ' . Date::toShort($tr->practical_period_start_date) . '<br>';
                                            echo '<span class="text-info">Practical Period End Date:</span> ' . Date::toShort($tr->practical_period_end_date) . '<br>';
					    echo '<span class="text-info">Contracted Hours per Week:</span> ' . $tr->contracted_hours_per_week . '<br>';
                                            echo '</td>';
                                            echo '<td><span class="btn btn-xs btn-info" onclick="window.location.href=\'do.php?_action=read_training&id='.$tr->id.'\'"><i class="fa fa-folder-open"></i> Open</span></td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php if(SystemConfig::getEntityValue($link, "bksb")) {?>
                    <div class="tab-pane" id="tab_bksb">
                        <h5 class="lead">BKSB</h5>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php
                                if($vo->bksb_username == '')
                                {
                                    echo '<span class="btn btn-xs btn-info" onclick="createLearnerInBksb();"><i class="fa fa-user-plus"></i> Create learner in BKSB</span>';
                                }
                                ?>
                                <p></p>
                                <table class="table table-bordered table-condensed">
                                    <tr>
                                        <th>BKSB Username:</th><td><?php echo $vo->bksb_username; ?></td>
                                        <th>BKSB ID:</th><td><?php echo $vo->bksb_userid; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php if($vo->bksb_username != ''){
                                    echo '<span class="btn btn-xs btn-info" onclick="downloadIaFromBksb();"><i class="fa fa-refresh"></i> Refresh IA from BKSB</span>';
                                } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-condensed">
                                        <caption class="text-bold bg-gray">Initial Assessment</caption>
                                        <tr><th>Session ID</th><th>Course Component Name</th><th>Ability Measurement</th><th>Measuring Assessment Name</th><th>Measured At</th></tr>
                                        <?php
                                        $result = DAO::getResultset($link, "SELECT SessionId, AssessmentEOAData FROM bksb_assessment_sessions WHERE ob_learner_id = '{$vo->id}' AND AssessmentType = '1'", DAO::FETCH_ASSOC);
                                        $html1 = '';
                                        foreach($result AS $row)
                                        {
                                            if($row['AssessmentEOAData'] != '')
                                            {
                                                $AssessmentEOAData = json_decode($row['AssessmentEOAData']);
                                                if(isset($AssessmentEOAData->AssessmentEOAData))
                                                {
                                                    $AssessmentEOAData = $AssessmentEOAData->AssessmentEOAData;
                                                    foreach($AssessmentEOAData AS $entry)
                                                    {
                                                        $html1 .= '<tr>';
                                                        $html1 .= '<td class="small">' . $row['SessionId'] . '</td>';
                                                        $html1 .= isset($entry->CourseComponentName) ? '<td>' . $entry->CourseComponentName . '</td>' : '<td></td>';
                                                        $html1 .= isset($entry->AbilityMeasurement) ? '<td align="center" class="text-bold">' . $entry->AbilityMeasurement . '</td>' : '<td></td>';
                                                        $html1 .= isset($entry->MeasuringAssessmentName) ? '<td>' . $entry->MeasuringAssessmentName . '</td>' : '<td></td>';
                                                        if(isset($entry->MeasuredAt))
                                                        {
                                                            $d = new Date($entry->MeasuredAt);
                                                            $html1 .= '<td>' . $d->format(Date::DATETIME) . '</td>';
                                                        }
                                                        else
                                                        {
                                                            $html1 .= '<td></td>';
                                                        }
                                                        $html1 .= '</tr>';
                                                    }
                                                }
                                            }
                                        }

                                        echo $html1 == '' ? '<tr><td colspan="5"><i>No records to show</i></td></tr>' : $html1;
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-condensed">
                                        <caption class="text-bold bg-gray">Diagnostic Assessment</caption>
                                        <tr><th>Session ID</th><th>Course Component Name</th><th>Ability Measurement</th><th>Measuring Assessment Name</th><th>Measured At</th></tr>
                                        <?php
                                        $result = DAO::getResultset($link, "SELECT SessionId, AssessmentEOAData FROM bksb_assessment_sessions WHERE ob_learner_id = '{$vo->id}' AND AssessmentType = '2'", DAO::FETCH_ASSOC);
                                        $html1 = '';
                                        foreach($result AS $row)
                                        {
                                            if($row['AssessmentEOAData'] != '')
                                            {
                                                $AssessmentEOAData = json_decode($row['AssessmentEOAData']);
                                                if(isset($AssessmentEOAData->AssessmentEOAData))
                                                {
                                                    $AssessmentEOAData = $AssessmentEOAData->AssessmentEOAData;
                                                    foreach($AssessmentEOAData AS $entry)
                                                    {
                                                        $html1 .= '<tr>';
                                                        $html1 .= '<td class="small">' . $row['SessionId'] . '</td>';
                                                        $html1 .= isset($entry->CourseComponentName) ? '<td>' . $entry->CourseComponentName . '</td>' : '<td></td>';
                                                        $html1 .= isset($entry->AbilityMeasurement) ? '<td align="center" class="text-bold">' . $entry->AbilityMeasurement . '</td>' : '<td></td>';
                                                        $html1 .= isset($entry->MeasuringAssessmentName) ? '<td>' . $entry->MeasuringAssessmentName . '</td>' : '<td></td>';
                                                        if(isset($entry->MeasuredAt))
                                                        {
                                                            $d = new Date($entry->MeasuredAt);
                                                            $html1 .= '<td>' . $d->format(Date::DATETIME) . '</td>';
                                                        }
                                                        else
                                                        {
                                                            $html1 .= '<td></td>';
                                                        }
                                                        $html1 .= '</tr>';
                                                    }
                                                }
                                            }
                                        }

                                        echo $html1 == '' ? '<tr><td colspan="5"><i>No records to show</i></td></tr>' : $html1;
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/adminlte/dist/js/app.min.js"></script>
    <script src="js/common.js" type="text/javascript"></script>
    <script src="/assets/adminlte/plugins/summernote/summernote.min.js"></script>

    <script>

        $(function () {

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

        function uploadFile()
        {
            var myForm = document.forms["frmUploadFile"];
            if(validateForm(myForm) == false)
            {
                return false;
            }
            myForm.submit();
        }

        function createLearnerInBksb()
        {
            var bksb_username = '<?php echo $vo->bksb_username; ?>';
            if(bksb_username == '')
            {
                alert("BKSB username is unabailable. Please edit the record and provide BKSB username.");
                return;
            }

            var qs = '&username='+encodeURIComponent(bksb_username) +
                '&ob_learner_id=<?php echo $vo->id; ?>'
            ;

            var client = ajaxRequest('do.php?_action=ajax_bksb&subaction=createLearnerInBksb'+qs);

            if(client)
            {
                alert(client.responseText);
                window.location.reload();
            }
        }

    </script>
</body>
</html>
