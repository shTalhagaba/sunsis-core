<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Homepage</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <link href="/assets/adminlte/plugins/pace/pace.css" rel="stylesheet">
    <link href="/assets/adminlte/plugins/toastr/toastr.min.css" rel="stylesheet">


    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>

    <style>
        .sigbox {
            border-radius: 15px;
            border: 1px solid #EEE;
            cursor: pointer;
        }

        .sigboxselected {
            border-radius: 25px;
            border: 2px solid #EEE;
            cursor: pointer;
            background-color: #d3d3d3;
        }

        .ui-dialog-titlebar-close {
            visibility: hidden;
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <header class="main-header"></header>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    <img class="img-rounded" src="<?php echo SystemConfig::getEntityValue($link, 'ob_header_image1'); ?>" height="35px;" />
                    <span class="pull-right"><img class="img-rounded" src="images/logos/SUNlogo.png" height="35px;" /></span>
                </h1>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-sm-8">
                        <?php if (in_array(DB_NAME, ['am_eet', 'am_puzzled', 'am_demo', "am_ela"])) { ?>
                            <div class="box box-info box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">AEB</h3>
                                </div>
                                <div class="box-body">
                                    <p>
                                        <span class="btn btn-info btn-xs" onclick="window.location.href='do.php?_action=view_ob_learners&_reset=1'">View All Learners</span>
                                        <span class="btn btn-info btn-xs" onclick="window.location.href='do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_funding_model=<?php echo Framework::FUNDING_STREAM_ASF; ?>'">View All Enrolments</span>
                                        <span class="btn btn-primary btn-xs" onclick="window.location.href='do.php?_action=add_edit_ob_learners&funding_stream=<?php echo Framework::FUNDING_STREAM_ASF; ?>'">Create New Learner</span>
                                    </p>
                                    <table class="table table-bordered table-condensed">
                                        <tr>
                                            <td>Awaiting Learner Enrolment Form</td>
                                            <td align="center">
                                                <span class="text-info" style="cursor:pointer;" onclick='showDetail(<?php echo json_encode($aebStats['awaitingLearnerEnrolmentForm']); ?>);'>
                                                    <?php echo count($aebStats['awaitingLearnerEnrolmentForm']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Bespoke Trainig Plan Not Signed by Learner</td>
                                            <td align="center">
                                                <span class="text-info" style="cursor:pointer;" onclick='showDetail(<?php echo json_encode($aebStats['bespokeTpUnsignedByLearner']); ?>);'>
                                                    <?php echo count($aebStats['bespokeTpUnsignedByLearner']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Bespoke Trainig Plan Not Signed by Provider</td>
                                            <td align="center">
                                                <span class="text-info" style="cursor:pointer;" onclick='showDetail(<?php echo json_encode($aebStats['bespokeTpUnsignedByProvider']); ?>);'>
                                                    <?php echo count($aebStats['bespokeTpUnsignedByProvider']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Enrolment Form Not Signed by Provider</td>
                                            <td align="center">
                                                <span class="text-info" style="cursor:pointer;" onclick='showDetail(<?php echo json_encode($aebStats['readyToSignEmployerForm']); ?>);'>
                                                    <?php echo count($aebStats['readyToSignEmployerForm']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="box box-info box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Bootcamp</h3>
                                </div>
                                <div class="box-body">
                                    <p>
                                        <span class="btn btn-info btn-xs" onclick="window.location.href='do.php?_action=view_ob_learners&_reset=1'">View All Learners</span>
                                        <span class="btn btn-info btn-xs" onclick="window.location.href='do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_funding_model=<?php echo Framework::FUNDING_STREAM_BOOTCAMP; ?>'">View All Enrolments</span>
                                        <span class="btn btn-primary btn-xs" onclick="window.location.href='do.php?_action=add_edit_ob_learners&funding_stream=<?php echo Framework::FUNDING_STREAM_BOOTCAMP; ?>'">Create New Learner</span>
                                    </p>
                                    <table class="table table-bordered table-condensed">
                                        <tr>
                                            <td>Awaiting Learner Enrolment Form</td>
                                            <td align="center">
                                                <span class="text-info" style="cursor:pointer;" onclick='showDetail(<?php echo json_encode($bcStats['awaitingLearnerEnrolmentForm']); ?>);'>
                                                    <?php echo count($bcStats['awaitingLearnerEnrolmentForm']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Bespoke Trainig Plan Not Signed by Learner</td>
                                            <td align="center">
                                                <span class="text-info" style="cursor:pointer;" onclick='showDetail(<?php echo json_encode($bcStats['bespokeTpUnsignedByLearner']); ?>);'>
                                                    <?php echo count($bcStats['bespokeTpUnsignedByLearner']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Bespoke Trainig Plan Not Signed by Provider</td>
                                            <td align="center">
                                                <span class="text-info" style="cursor:pointer;" onclick='showDetail(<?php echo json_encode($bcStats['bespokeTpUnsignedByProvider']); ?>);'>
                                                    <?php echo count($bcStats['bespokeTpUnsignedByProvider']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Enrolment Form Not Signed by Provider</td>
                                            <td align="center">
                                                <span class="text-info" style="cursor:pointer;" onclick='showDetail(<?php echo json_encode($bcStats['readyToSignEmployerForm']); ?>);'>
                                                    <?php echo count($bcStats['readyToSignEmployerForm']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if(SystemConfig::getEntityValue($link, 'FUNDING_STREAM_LEARNER_LOAN')) { ?>
                        <div class="box box-info box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title">Learner Loan</h3>
                            </div>
                            <div class="box-body">
                                <p>
                                    <span class="btn btn-info btn-xs" onclick="window.location.href='do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_funding_model=<?php echo Framework::FUNDING_STREAM_99; ?>&ViewTrainingRecords_filter_fund_model_extra=<?php echo Framework::FUNDING_STREAM_LEARNER_LOAN; ?>'">View All Enrolments</span>
                                    <span class="btn btn-primary btn-xs" onclick="window.location.href='do.php?_action=add_edit_ob_learners&funding_stream=<?php echo Framework::FUNDING_STREAM_LEARNER_LOAN; ?>'">Create New Learner</span>
                                </p>
                                <table class="table table-bordered table-condensed">
                                    <tr>
                                        <td>Awaiting Learner Enrolment Form</td>
                                        <td align="center">
                                            <span class="text-info" style="cursor:pointer;" onclick='showDetail(<?php echo json_encode($llStats['awaitingLearnerEnrolmentForm']); ?>);'>
                                                <?php echo count($llStats['awaitingLearnerEnrolmentForm']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Enrolment Form Not Signed by Provider</td>
                                        <td align="center">
                                            <span class="text-info" style="cursor:pointer;" onclick='showDetail(<?php echo json_encode($llStats['readyToSignEmployerForm']); ?>);'>
                                                <?php echo count($llStats['readyToSignEmployerForm']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="box box-info box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title">Commercial</h3>
                            </div>
                            <div class="box-body">
                                <p>
                                    <span class="btn btn-info btn-xs" onclick="window.location.href='do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_funding_model=<?php echo Framework::FUNDING_STREAM_99; ?>&ViewTrainingRecords_filter_fund_model_extra=<?php echo Framework::FUNDING_STREAM_COMMERCIAL; ?>'">View All Enrolments</span>
                                    <span class="btn btn-primary btn-xs" onclick="window.location.href='do.php?_action=add_edit_ob_learners&funding_stream=<?php echo Framework::FUNDING_STREAM_COMMERCIAL; ?>'">Create New Learner</span>
                                </p>
                                <table class="table table-bordered table-condensed">
                                    <tr>
                                        <td>Awaiting Learner Enrolment Form</td>
                                        <td align="center">
                                            <span class="text-info" style="cursor:pointer;" onclick='showDetail(<?php echo json_encode($comStats['awaitingLearnerEnrolmentForm']); ?>);'>
                                                <?php echo count($comStats['awaitingLearnerEnrolmentForm']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Enrolment Form Not Signed by Provider</td>
                                        <td align="center">
                                            <span class="text-info" style="cursor:pointer;" onclick='showDetail(<?php echo json_encode($comStats['readyToSignEmployerForm']); ?>);'>
                                                <?php echo count($comStats['readyToSignEmployerForm']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="box box-info box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title">Apprentices Stats - Apprenticeship</h3>
                            </div>
                            <div class="box-body" style="height: 400px;">
                                <p>
                                    <span class="btn btn-info btn-xs" onclick="window.location.href='do.php?_action=view_ob_learners&_reset=1'">View All Apprentices</span>
                                    <span class="btn btn-info btn-xs" onclick="window.location.href='do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_funding_model=<?php echo Framework::FUNDING_STREAM_APP; ?>'">View All Enrolments</span>
                                    <span class="btn btn-primary btn-xs" onclick="window.location.href='do.php?_action=add_edit_ob_learners&funding_stream=<?php echo Framework::FUNDING_STREAM_APP; ?>'">Create New Apprentice</span>
                                </p>
                                <table class="table table-bordered table-condensed">
                                    <caption class="text-bold">Apprentices</caption>
                                    <?php
                                    $stat_learner_rows = '';
                                    $viewTrainingRecords = ViewTrainingRecords::getInstance($link);
                                    $q = [
                                        'ViewTrainingRecords_' . View::KEY_PAGE_SIZE => 0, // No limit
                                        '_reset' => 1,
                                        'ViewTrainingRecords_filter_funding_model' => Framework::FUNDING_STREAM_APP,
                                    ];
                                    $qs = array_merge($q, ['ViewTrainingRecords_filter_stats' => 1]);
                                    $viewTrainingRecords->refresh($link, $qs);
                                    $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_training_records&' . http_build_query($qs));
                                    $stat_learner_rows .= '<td>Skills analysis not signed by learner</td><td align="center">' . $viewTrainingRecords->getRowCount() . '</td>';
                                    $stat_learner_rows .= '</tr>';

                                    $qs = array_merge($q, ['ViewTrainingRecords_filter_stats' => 2]);
                                    $viewTrainingRecords->refresh($link, $qs);
                                    $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_training_records&' . http_build_query($qs));
                                    $stat_learner_rows .= '<td>Skills analysis signed by learner and not by provider</td><td align="center">' . $viewTrainingRecords->getRowCount() . '</td>';
                                    $stat_learner_rows .= '</tr>';

                                    $qs = array_merge($q, ['ViewTrainingRecords_filter_stats' => 3]);
                                    $viewTrainingRecords->refresh($link, $qs);
                                    $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_training_records&' . http_build_query($qs));
                                    $stat_learner_rows .= '<td>Onboarding not signed by learner</td><td align="center">' . $viewTrainingRecords->getRowCount() . '</td>';
                                    $stat_learner_rows .= '</tr>';

                                    $qs = array_merge($q, ['ViewTrainingRecords_filter_stats' => 4]);
                                    $viewTrainingRecords->refresh($link, $qs);
                                    $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_training_records&' . http_build_query($qs));
                                    $stat_learner_rows .= '<td>Onboarding signed by learner and not by employer</td><td align="center">' . $viewTrainingRecords->getRowCount() . '</td>';
                                    $stat_learner_rows .= '</tr>';

                                    $qs = array_merge($q, ['ViewTrainingRecords_filter_stats' => 5]);
                                    $viewTrainingRecords->refresh($link, $qs);
                                    $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_training_records&' . http_build_query($qs));
                                    $stat_learner_rows .= '<td>Onboarding signed by learner and employer and not by provider</td><td align="center">' . $viewTrainingRecords->getRowCount() . '</td>';
                                    $stat_learner_rows .= '</tr>';

                                    $qs = array_merge($q, ['ViewTrainingRecords_filter_stats' => 6, 'ViewTrainingRecords_filter_status' => 4]);
                                    $viewTrainingRecords->refresh($link, $qs);
                                    $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_training_records&' . http_build_query($qs));
                                    $stat_learner_rows .= '<td>Onboarding learners converted and moved to Main Sunesis</td><td align="center">' . $viewTrainingRecords->getRowCount() . '</td>';
                                    $stat_learner_rows .= '</tr>';

                                    echo $stat_learner_rows;
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-font"></i> Your Signature</h3>
                                <div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
                            </div>
                            <div class="box-body">
                                <span class="btn btn-info" onclick="getSignature();">
                                    <img id="img_user_signature" src="do.php?_action=generate_image&<?php echo $_SESSION['user']->signature == '' ? 'title=Create your signature&font=Signature_Regular.ttf&size=20' : $_SESSION['user']->signature; ?>" style="border: 1px solid;border-radius: 15px;" />
                                    <input type="hidden" name="user_signature" id="user_signature" value="<?php echo $_SESSION['user']->signature; ?>" />
                                </span>
                            </div>
                        </div>
                        <div class="box box-info box-solid">
                            <div class="box-header with-border">Quick Search</div>
                            <div class="box-body" style="height: 400px;">
                                <div class="callout callout-default">
                                    <form role="form" class="form-vertical" name="frmQuickSearchEmployer" id="frmQuickSearchEmployer" action="do.php?_action=home_page" method="post">
                                        <input type="hidden" name="subaction" value="quickSearchEmployer">
                                        <div class="form-group">
                                            <label for="txtSearchEmployer">Employer Name:</label>
                                            <input type="text" class="form-control" name="txtSearchEmployer" placeholder="Enter employer name" required>
                                        </div>
                                        <div class="form-group">
                                            <button name="quickSearchEmployer" type="submit" class="btn btn-xs btn-info pull-right"><i class="fa fa-search"></i> Search Employer</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="callout callout-default">
                                    <form role="form" class="form-vertical" name="frmQuickSearchLearner" id="frmQuickSearchLearner" action="do.php?_action=home_page" method="post">
                                        <input type="hidden" name="subaction" value="quickSearchLearner">
                                        <div class="form-group">
                                            <label for="txtSearchLearnerFirstname">Learner First Name:</label>
                                            <input type="text" class="form-control" name="txtSearchLearnerFirstname" placeholder="Enter learner firstname">
                                        </div>
                                        <div class="form-group">
                                            <label for="txtSearchLearnerSurname">Learner Surname:</label>
                                            <input type="text" class="form-control" name="txtSearchLearnerSurname" placeholder="Enter learner surname">
                                        </div>
                                        <div class="form-group">
                                            <button name="quickSearchLearner" type="submit" class="btn btn-xs btn-info pull-right"><i class="fa fa-search"></i> Search Learner</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($_SESSION['user']->isAdmin()) { ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="box box-primary ">
                                <div class="box-body table-responsive">
                                    <span class="text-info"><i class="fa fa-info-circle"></i> Number of learners based on practical period start date</span>
                                    <table class="table table-bordered">
                                        <?php
                                        $frameworks_titles = DAO::getSingleColumn($link, "SELECT frameworks.title FROM frameworks WHERE frameworks.id IN (SELECT DISTINCT ob_tr.framework_id FROM ob_tr) ORDER BY frameworks.title");
                                        $start_stats_previous_6_months = HomePage::getStartsGraphs($link);
                                        echo '<tr>';
                                        echo '<td></td>';
                                        $months = [];
                                        $colTotal = [];

                                        foreach ($start_stats_previous_6_months as $month => $detail) {
                                            echo '<td>' . $month . '</td>';
                                            $months[] = $month;
                                            $colTotal[$month] = 0;
                                        }
                                        echo '<td>Total</td>';
                                        echo '</tr>';
                                        echo '<tr>';

                                        foreach ($frameworks_titles as $standard_name) {
                                            $row_total = 0;
                                            echo '<tr>';
                                            echo '<td>' . $standard_name . '</td>';
                                            foreach ($months as $month) {
                                                echo '<td class="text-center">' . count($start_stats_previous_6_months[$month][$standard_name]) . '</td>';
                                                $row_total += count($start_stats_previous_6_months[$month][$standard_name]);
                                                $colTotal[$month] += count($start_stats_previous_6_months[$month][$standard_name]);
                                            }
                                            echo '<td class="text-center text-bold">' . $row_total . '</td>';
                                            echo '</tr>';
                                        }

                                        echo '<tr>';
                                        echo '<td>Total</td>';
                                        foreach ($colTotal as $cTotal) {
                                            echo '<td class="text-center text-bold">' . $cTotal . '</td>';
                                        }
                                        echo '<td class="text-center text-bold">' . array_sum($colTotal) . '</td>';
                                        echo '</tr>';
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </section>
        </div>

        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                Powered by Sunesis &nbsp;|&nbsp;&copy; <?php echo date('Y'); ?> Perspective Ltd
            </div>
            <strong>
                <?php echo date('D, d M Y'); ?>
        </footer>

        <div id="panel_signature" title="Signature Panel">
            <div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter your name, then select the signature font you like and press "Create". </div>
            <div>
                <table class="table row-border">
                    <tr>
                        <td>Enter your name</td>
                        <td><input type="text" id="signature_text" onkeyup="refreshSignature();" onkeypress="return onlyAlphabets(event,this);" /></td>
                    </tr>
                    <tr>
                        <td onclick="SignatureSelected(this);" class="sigbox"><img id="img1" src="" /></td>
                        <td onclick="SignatureSelected(this);" class="sigbox"><img id="img2" src="" /></td>
                    </tr>
                    <tr>
                        <td onclick="SignatureSelected(this);" class="sigbox"><img id="img3" src="" /></td>
                        <td onclick="SignatureSelected(this);" class="sigbox"><img id="img4" src="" /></td>
                    </tr>
                    <tr>
                        <td onclick="SignatureSelected(this);" class="sigbox"><img id="img5" src="" /></td>
                        <td onclick="SignatureSelected(this);" class="sigbox"><img id="img6" src="" /></td>
                    </tr>
                    <tr>
                        <td onclick="SignatureSelected(this);" class="sigbox"><img id="img7" src="" /></td>
                        <td onclick="SignatureSelected(this);" class="sigbox"><img id="img8" src="" /></td>
                    </tr>
                </table>
            </div>
        </div>

        <form method="get" action="/do.php" id="frmShowDetail" name="frmShowDetail">
            <input type="hidden" name="_action" value="view_training_records" />
            <input type="hidden" name="_reset" value="1" />
            <input type="hidden" name="ViewTrainingRecords_filter_status" value="" />
            <input type="hidden" name="ViewTrainingRecords_filter_system_id" value="" />
        </form>

    </div>

    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/common.js" type="text/javascript"></script>
    <script src="/assets/adminlte/dist/js/app.min.js"></script>
    <script src="/assets/adminlte/plugins/pace/pace.js"></script>
    <script src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>

    <!-- <script src="https://code.highcharts.com/highcharts.src.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/no-data-to-display.js"></script> -->


    <script>
        function showDetail(ids) {
            var frmFilters = document.forms["frmShowDetail"];
            frmFilters.ViewTrainingRecords_filter_system_id.value = ids;

            frmFilters.submit();
        }

        var phpUserSignature = '<?php echo $_SESSION['user']->signature; ?>';
        $(function() {
            $(document).ajaxStart(function() {
                Pace.restart();
            });

            <?php if ($toastr_message != '') { ?>
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
                toastr.success('<?php echo $toastr_message; ?>');
            <?php } ?>

            if (window.phpUserSignature == '') {
                $("<div></div>").html('<p><strong>Create your signature</strong></p><p>Your signature is required, please use "Your Signature" panel to create your signature.<br></p>').dialog({
                    title: " Important information ",
                    resizable: false,
                    modal: true,
                    width: 'auto',
                    maxWidth: 550,
                    height: 'auto',
                    maxHeight: 500,
                    closeOnEscape: false,
                    buttons: {
                        'OK': function() {
                            $(this).dialog('close');
                        }
                    }
                }).css("background", "#FFF");
            }

            $("#panel_signature").dialog({
                autoOpen: false,
                modal: true,
                draggable: false,
                width: "auto",
                height: 500,
                buttons: {
                    'Create': function() {
                        if ($('#signature_text').val() == '') {
                            alert('Please input your signature');
                            $('#signature_text').focus();
                            return;
                        }
                        if ($('.sigboxselected').children('img')[0] === undefined) {
                            alert('Please select your font');
                            return;
                        }
                        $("#img_user_signature").attr('src', $('.sigboxselected').children('img')[0].src);
                        var _link = $('.sigboxselected').children('img')[0].src;
                        _link = _link.split('&');
                        $("#user_signature").val(_link[1] + '&' + _link[2] + '&' + _link[3]);
                        if ($('#user_signature').val() == '') {
                            alert('Please create your signature');
                            return;
                        }
                        if (!confirm('You can only save your signature once, are you sure you want to continue?')) {
                            $("#img_user_signature").attr('src', 'do.php?_action=generate_image&title=Create your signature&font=Signature_Regular.ttf&size=20');
                            $(this).dialog('close');
                            return;
                        }
                        saveSignature();
                        $(this).dialog('close');
                    },
                    'Cancel': function() {
                        $(this).dialog('close');
                    }
                }


            });

            if (window.phpUserSignature == '') {
                loadDefaultSignatures();
            }

        });

        var fonts = Array("Little_Days.ttf", "ArtySignature.ttf", "Signerica_Medium.ttf", "Champignon_Alt_Swash.ttf", "Bailey_MF.ttf", "Carolina.ttf", "DirtyDarren.ttf", "Ruf_In_Den_Wind.ttf");
        var sizes = Array(30, 40, 15, 30, 30, 30, 25, 30);

        function refreshSignature() {
            for (var i = 1; i <= 8; i++)
                $("#img" + i).attr('src', 'images/loading.gif');

            for (var i = 0; i <= 7; i++)
                $("#img" + (i + 1)).attr('src', 'do.php?_action=generate_image&title=' + $("#signature_text").val() + '&font=' + fonts[i] + '&size=' + sizes[i]);
        }

        function loadDefaultSignatures() {
            for (var i = 1; i <= 8; i++)
                $("#img" + i).attr('src', 'images/loading.gif');

            for (var i = 0; i <= 7; i++)
                $("#img" + (i + 1)).attr('src', 'do.php?_action=generate_image&title=Signature' + '&font=' + fonts[i] + '&size=' + sizes[i]);
        }

        function onlyAlphabets(e, t) {
            try {
                if (window.event) {
                    var charCode = window.event.keyCode;
                } else if (e) {
                    var charCode = e.which;
                } else {
                    return true;
                }
                if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 32 || charCode == 39 || charCode == 45 || charCode == 8 || charCode == 46)
                    return true;
                else
                    return false;
            } catch (err) {
                alert(err.Description);
            }
        }

        function getSignature() {
            <?php if ($_SESSION['user']->signature != '') { ?>
                return;
            <?php } ?>
            $("#panel_signature").dialog("open");
        }

        function SignatureSelected(sig) {
            $(".sigboxselected").attr("class", "sigbox");
            sig.className = "sigboxselected";
        }

        function saveSignature() {
            $.ajax({
                type: 'POST',
                url: 'do.php?_action=save_user_signature&from_page=home_page&id=<?php echo $_SESSION['user']->id; ?>&user_signature=' + encodeURIComponent($('#user_signature').val()),
                success: function(data, textStatus, xhr) {
                    window.location.reload();
                },
                error: function(data, textStatus, xhr) {
                    console.log(data.responseText);
                }
            });
        }
    </script>

</body>

</html>
<?php
$viewTrainingRecords->refresh($link, ['_reset' => 1]);
?>