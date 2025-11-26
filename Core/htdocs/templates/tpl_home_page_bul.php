<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Homepage</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/skins/_all-skins.min.css">
    <link href="/assets/adminlte/plugins/pace/pace.css" rel="stylesheet">
    <link href="/assets/adminlte/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/adminlte/plugins/fullcalendar/fullcalendar.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="wrapper">
    <header class="main-header"></header>

    <div class="content-wrapper">
        <section class="content-header">
            <h1><span class="fa fa-dashboard"></span> Dashboard<span class="pull-right"><img class="img-rounded"
                                                                                             src="images/logos/SUNlogo.png"
                                                                                             height="35px;"/></span>
            </h1>
        </section>

        <section class="content">
            
            <div class="row">

                <div class="col-sm-6">
                    <div class="box box-primary ">
                        <div class="box-header with-border"><h3 class="box-title"><span class="glyphicon glyphicon-stats"></span> On-Programme Learners</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body bg-gray-active">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed">
                                    <?php
                                    echo '<tr>';
                                    echo '<th style="width: 50%;">On-programme learners</th>';
                                    echo '<td class="text-blue text-bold" style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $on_programme_stats['on_programme']) . '\');">' . count($on_programme_stats['on_programme']) . '</td>';
                                    echo '</tr>';
                                    echo '<tr>';
                                    echo '<th>Of which: Overstayers</th>';
                                    echo '<td class="text-blue text-bold" style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $on_programme_stats['overstayer']) . '\');">' . count($on_programme_stats['overstayer']) . '</td>';
                                    echo '</tr>';
                                    echo '<tr>';
                                    echo count($on_programme_stats['on_programme']) > 0 ?
                                        '<th>% of overstayers</th><td>' . round((count($on_programme_stats['overstayer']) / count($on_programme_stats['on_programme']))*100, 2) . '%</td>' :
                                        '<th>% of overstayers</th><td>' . round((count($on_programme_stats['overstayer']) / 1)*100, 2) . '%</td>';
                                    echo '</tr>';
                                    ?>
                                </table>
                                <p><br></p>
                                <div id="on_programme_by_duration_left_graph" style="height: 250px;"></div>
                                <p><br></p>
                                <div id="overstayers_by_expected_month" style="height: 350px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="box box-success box-solid">
                        <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-calendar"></i> Diary</h3> </div>
                        <div class="box-body no-padding">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>

                <div class="row">

                <div class="col-lg-6">
                    <div class="box box-primary">
                        <div class="box-header with-border"><h3 class="box-title"><span
                                        class="glyphicon glyphicon-stats"></span> Progression (<?php echo $current_submission_year_disp; ?>)
                            </h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body bg-gray-light">
                            <div class="row">

                                <div class="col-lg-12 col-xs-12">
                                    <div class="chart-panel-body " style="width: 300px; height: 200px; float: left" id="SolidLearnerProgressionL2L3"></div>
                                    <div class="chart-panel-body " style="width: 300px; height: 200px; float: left" id="SolidLearnerProgressionL3L4"></div>
                                    <div class="chart-panel-body " style="width: 300px; height: 200px; float: left" id="SolidLearnerProgressionTP"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">

                    <div class="box box-primary">
                        <div class="box-header with-border"><h3 class="box-title"><span class="glyphicon glyphicon-stats"></span> Completions Due</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body bg-gray-active">
                            <div class="table-responsive">
                                <div id="completions_due_by_expected_month" style="height: 350px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-6">

                <div class="box box-primary">
                    <div class="box-header with-border"><h3 class="box-title"><span class="glyphicon glyphicon-stats"></span> Withdrawals</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body bg-gray-active">
                        <div class="table-responsive">
                            <div id="withdrawals_in_current_submission_year" style="height: 250px;"></div>
                            <p class=""> A learner withdrawn back to within 42 days/6 weeks (qualifying period) of their start date receives no ESFA funding and is excluded from the calculation of achievement rates.</p>
                        </div>
                    </div>
                </div>

                    <div class="box box-primary">
                        <div class="box-header with-border"><h3 class="box-title"><span class="glyphicon glyphicon-stats"></span> New Starts</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <span class="text-info"><i class="fa fa-info-circle"></i> New starts over the previous 6 months</span>
                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed text-center">
                                    <?php
                                    echo '<tr>';
                                    foreach($start_stats_previous_3_months AS $month => $detail)
                                    {
                                        echo '<td>' . $month . '</td>';
                                    }
                                    echo '<td>Total</td>';
                                    echo '</tr>';
                                    echo '<tr>';
                                    $total = 0;

                                    foreach($start_stats_previous_3_months AS $month => $detail)
                                    {
                                        echo '<td class="text-blue text-bold" style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $detail->tr_ids) . '\');">' . count($detail->tr_ids) . '</td>';
                                        $total += count($detail->tr_ids);
                                    }
                                    echo '<td>' . $total . '</td>';
                                    echo '</tr>';
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-6">
                    <div class="box box-primary">
                        <div class="box-header with-border"><h3 class="box-title"><span
                                class="glyphicon glyphicon-stats"></span> Learners (<?php echo $current_submission_year_disp; ?>)</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body bg-gray-light">
                            <div class="row">

                                <div class="col-lg-6 col-xs-6">
                                    <div class="small-box bg-yellow">
                                        <div class="inner">
                                            <h3><?php echo $learners_temp_withdrawn['row_count']; ?></h3>
                                            <p>Learners temporarily withdrawn</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-pause"></i>
                                        </div>
                                        <a href="<?php echo $learners_temp_withdrawn['url']; ?>"
                                           class="small-box-footer">Click to see <i
                                                class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xs-6">
                                    <div class="small-box bg-green-gradient">
                                        <div class="inner">
                                            <h3><?php echo $learners_completed['row_count']; ?></h3>
                                            <p>Learners completed</p>
                                        </div>
                                        <div class="icon"><i class="fa fa-hourglass-half"></i></div>
                                        <a href="<?php echo $learners_completed['url']; ?>"
                                           class="small-box-footer">Click to see <i
                                                class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="row">

                <div class="col-sm-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><span class="fa fa-pie-chart"></span> Learners by Progress (<?php echo $current_submission_year_disp; ?>)</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body table-responsive bg-gray-active">
                            <div id="pieChartLearnerProgress"
                                 style="min-width: 500px; height: 280px; margin: 30 auto"></div>
                        </div>
                        <div class="box-footer no-padding">
                            <ul class="nav nav-pills nav-stacked">
                                <li>
                                    <a href="<?php echo $learners_by_progress['On Track']['url']; ?>">On
                                        Track<span
                                                class="pull-right text-red"><?php echo $learners_by_progress['On Track']['row_count']; ?></span></a>
                                </li>
                                <li>
                                    <a href="<?php echo $learners_by_progress['Behind']['url']; ?>">Behind<span
                                                class="pull-right text-red"><?php echo $learners_by_progress['Behind']['row_count']; ?></span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><span class="fa fa-pie-chart"></span> Learners by OTJ Progress (<?php echo $current_submission_year_disp; ?>)</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body table-responsive bg-gray-active">
                            <div id="pieChartLearnerOtjProgress"
                                 style="min-width: 500px; height: 280px; margin: 30 auto"></div>
                        </div>
                        <div class="box-footer no-padding">
                            <ul class="nav nav-pills nav-stacked">
                                <li>
                                    <a href="<?php echo $learners_by_otj_progress['On Track']['url']; ?>">On
                                        Track<span
                                                class="pull-right text-red"><?php echo $learners_by_otj_progress['On Track']['row_count']; ?>%</span></a>
                                </li>
                                <li>
                                    <a href="<?php echo $learners_by_otj_progress['Behind']['url']; ?>">Behind<span
                                                class="pull-right text-red"><?php echo $learners_by_otj_progress['Behind']['row_count']; ?>%</span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            <?php if($_SESSION['user']->isAdmin()){ ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title"><span class="fa fa-pie-chart"></span> Gateway Information</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                class="fa fa-minus"></i></button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                                class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="row">

                                    <div class="col-sm-5">
                                        <div class="table-responsive bg-gray-active">
                                            <div id="GatewayLearnersBarChart"
                                                 style="min-width: 500px; height: 350px; margin: 30 auto"></div>
                                        </div>
                                    </div>

                                    <div class="col-sm-7">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="col-sm-12">
                                                    <div class="small-box bg-orange-active">
                                                        <div class="inner">
                                                            <h3><?php echo $gateway_stats['gateway1']; ?></h3>
                                                            <p>Gateway 1 Learners</p>
                                                        </div>
                                                        <div class="icon">Gateway 1</div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="small-box bg-yellow-gradient">
                                                        <div class="inner">
                                                            <h3><?php echo $gateway_stats['gateway2']; ?></h3>
                                                            <p>Gateway 2 Learners</p>
                                                        </div>
                                                        <div class="icon">Gateway 2</div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="small-box bg-red-gradient">
                                                        <div class="inner">
                                                            <h3><?php echo $gateway_stats['gateway3']; ?></h3>
                                                            <p>Gateway 3 Learners</p>
                                                        </div>
                                                        <div class="icon">Gateway 3</div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="box box-primary collapsed-box" style="max-height: 450px;">
                            <div class="box-header with-border">
                                <h3 class="box-title"><span class="fa fa-battery-three-quarters"></span> File Repository
                                </h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                class="fa fa-plus"></i></button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                                class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="box-body table-responsive">
                                <p class="text-info">The Sunesis <a href="do.php?_action=file_repository">File
                                        Repository</a> provides a secure conduit for the movement of sensitive data files
                                    between users and Perspective.</p>
                                <div id="pieChartFileUsage"
                                     style="min-width: 500px; height: 280px; margin: 30 auto"></div>
                            </div>
                            <div class="box-footer no-padding">
                                <ul class="nav nav-pills nav-stacked">
                                    <li><a href="#">Used Space<span
                                                    class="pull-right"><?php echo $file_repo_graph['Used']['value']; ?> mb</span></a></li>
                                    <li><a href="#">Remaining Space<span class="pull-right"><?php echo $file_repo_graph['Remaining']['value']; ?> mb</a></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="box box-primary collapsed-box">
                            <div class="box-header with-border">
                                <h3 class="box-title"><span class="glyphicon glyphicon-stats"></span> Repository Space Usage
                                </h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                class="fa fa-plus"></i></button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                                class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div id="panelFileRepoUsage" style="min-width: 500px; height: 450px; margin: 30 auto"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">

                        <div class="box box-primary collapsed-box">
                            <div class="box-header with-border">
                                <h3 class="box-title"><span class="glyphicon glyphicon-stats"></span> Learners by Assessors
                                    (<?php echo $current_submission_year_disp; ?>)</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                class="fa fa-plus"></i></button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                                class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div id="learners_by_assessors"></div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-3">
                        <div class="box box-primary collapsed-box">
                            <div class="box-header with-border">
                                <h3 class="box-title"><span class="fa fa-calendar"></span> ILR Submissions (
                                    <?php echo $current_submission_year_disp; ?>)</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                class="fa fa-plus"></i></button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                                class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive" style="max-height: 300px; overflow-y: scroll;">
                                    <table class="table table-bordered table-condensed small">
                                        <tr>
                                            <th>Period</th>
                                            <th>Last Submission Date</th>
                                            <th>Total ILRs</th>
                                            <th>Valid ILRs</th>
                                            <th>Invalid ILRs</th>
                                        </tr>
                                        <?php
                                        $submissions_details = DAO::getResultset($link, "SELECT * FROM central.lookup_submission_dates WHERE contract_year IN ({$current_submission_year})", DAO::FETCH_ASSOC);
                                        $current_submission = 'W' . $current_submission;
                                        foreach ($submissions_details as $submission_record) {
                                            if ($submission_record['submission'] < $current_submission)
                                                continue;
                                            if ($submission_record['submission'] == $current_submission) {
                                                $today = new Date(date('Y-m-d'));
                                                $last_submission_date = new Date($submission_record['last_submission_date']);
                                                $days_left = Date::dateDiffInfo($today, $last_submission_date);
                                                echo '<tr bgcolor="orange"><td bgcolor="orange">' . $submission_record['submission'] . '</td><td bgcolor="orange">' . Date::toShort($submission_record['last_submission_date']) . ' (' . $days_left['days'] . ' days left)</td><td>' . $total_ilrs . '</td><td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_funding=1">' . $valid_ilrs . '</a></td><td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_funding=2">' . $invalid_ilrs . '</a></td></tr>';
                                            } else
                                                echo '<tr><td>' . $submission_record['submission'] . '</td><td>' . Date::toShort($submission_record['last_submission_date']) . '</td><td>-</td><td>-</td><td>-</td></tr>';
                                        }
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="box box-primary collapsed-box">
                            <div class="box-header with-border">
                                <h3 class="box-title"><span class="fa fa-info-circle"></span> How to Guides</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                class="fa fa-plus"></i></button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                                class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="box-body" style="max-height: 300px; overflow-y: scroll;">
                                <p class="text-info">Please use the guides below to help with your use of Sunesis. All our
                                    'How to' guides are in PDF format.</p>
                                <ul class="list-group list-group-unbordered">
                                    <?php
                                    $how_to_dir = DATA_ROOT . "/uploads/am_demo/howto";
                                    $files = Repository::readDirectory($how_to_dir);
                                    if (count($files) == 0) {
                                        echo '<i>No files uploaded</i>';
                                    }
                                    foreach ($files as $f) {
                                        if ($f->isDir()) {
                                            continue;
                                        }
                                        $ext = new SplFileInfo($f->getName());
                                        $ext = $ext->getExtension();
                                        $image = 'fa-file';
                                        if ($ext == 'doc' || $ext == 'docx')
                                            $image = 'fa-file-word-o';
                                        elseif ($ext == 'pdf')
                                            $image = 'fa-file-pdf-o';
                                        elseif ($ext == 'txt')
                                            $image = 'fa-file-text-o';
                                        echo '<li class="list-group-item"><a href="do.php?_action=downloader&path=/am_demo/howto/' . "&f=" . $f->getName() . '"><i class="fa ' . $image . '"></i> ' . htmlspecialchars((string)$f->getName()) . '</a><br><span class="direct-chat-timestamp "><i class="fa fa-clock-o"></i> <small>' . date("d/m/Y H:i:s", $f->getModifiedTime()) . '</small></span></li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>

                    </div>

                </div>
            <?php } ?>

        </section>

    </div>

    <div id="reviewContent" title="Event Details" style="display:none;">
        <table class="table row-border">
            <tr><th>Date:</th><td><span id="r_start"></span></td></tr>
            <tr><th>Action:</th><td><span id="r_action"></span></td></tr>
        </table>
    </div>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            Powered by Sunesis &nbsp;|&nbsp;&copy; <?php echo date('Y'); ?> Perspective Ltd
        </div>
        <strong>
            <?php echo date('D, d M Y'); ?>
    </footer>

    <form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="frmFilters" name="frmFilters">
        <input type="hidden" name="_action" value="view_home_page_dash_learners" />
        <input type="hidden" name="_reset" value="1" />
        <input type="hidden" name="filter_tr_ids" value="" />
    </form>

</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/assets/adminlte/plugins/pace/pace.js"></script>
<script src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>
<script type="text/javascript" src="/assets/adminlte/plugins/fullcalendar/moment.js"></script>
<script type="text/javascript" src="/assets/adminlte/plugins/fullcalendar/fullcalendar.js"></script>

<script src="https://code.highcharts.com/highcharts.src.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
<script src="https://code.highcharts.com/modules/no-data-to-display.js"></script>
<script src="module_charts/assets/jsonfn.js"></script>

<script>


    $(function () {
        $(document).ajaxStart(function () {
            Pace.restart();
        });

        $('#calendar').fullCalendar({
            header    : {
                left  : 'prev,next today',
                center: 'title',
                right : 'month,agendaWeek,agendaDay'
            },
            buttonText: {
                today: 'today',
                month: 'month',
                week : 'week',
                day  : 'day'
            },
            weekends: false,
            events: 'do.php?_action=ajax_calendar_manager&user_id=<?php echo $_SESSION['user']->id; ?>',
            eventRender: function (event, element) {
                if(event.type != 'review')
                {
                    element.attr('href', 'javascript:void(0);');
                    element.click(function() {
                        $("#r_start").html(moment(event.start).format('DD/MM/YYYY'));
                        //$("#r_for").html(event.for);
                        $("#r_action").html(event.next_action_desc);
                        //$("#r_link").attr('href', event.url);
                        $("#reviewContent").dialog({
                            modal: true,
                            title: event.title,
                            width:350,
                            draggable: false,
                            buttons:{
                                "Close":function () {
                                    $(this).dialog("close");
                                }
                            }
                        });
                    });
                }
                else
                {
                    element.attr('href', 'javascript:void(0);');
                    element.click(function() {
                        $("#r_start").html(moment(event.start).format('DD/MM/YYYY'));
                        //$("#r_for").html(event.for);
                        $("#r_action").html(event.next_action_desc);
                        //$("#r_link").attr('href', event.url);
                        $("#reviewContent").dialog({
                            modal: true,
                            title: event.title,
                            width:350,
                            draggable: false,
                            buttons:{
                                "Close":function () {
                                    $(this).dialog("close");
                                }
                            }
                        });
                    });
                }
            },
            editable  : false,
            droppable : false, // this allows things to be dropped onto the calendar !!!
            views: {
                basic: {
                    // options apply to basicWeek and basicDay views
                },
                agenda: {
                    // options apply to agendaWeek and agendaDay views
                },
                week: {
                    columnFormat: 'ddd D/M'
                },
                day: {
                    // options apply to basicDay and agendaDay views
                }
            }
        });

        <?php if($toastr_message != ''){?>
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

        var chart = new Highcharts.chart('pieChartLearnerProgress', JSONfn.parse(JSON.stringify(<?php echo $learners_by_progress['graph']; ?>)));
        var chart = new Highcharts.chart('pieChartLearnerOtjProgress', JSONfn.parse(JSON.stringify(<?php echo $learners_by_otj_progress['graph']; ?>)));

        <?php if($_SESSION['user']->isAdmin()){ ?>
        var chart = new Highcharts.chart('GatewayLearnersBarChart', JSONfn.parse(JSON.stringify(<?php echo HomePageV2::GatewayLearnersBarChart($link); ?>)));
        var chart = new Highcharts.chart('pieChartFileUsage', JSONfn.parse(JSON.stringify(<?php echo $file_repo_graph['graph']; ?>)));
        var chart = new Highcharts.chart('panelFileRepoUsage', JSONfn.parse(JSON.stringify(<?php echo $panelFileRepoUsage; ?>)));
        var chart = new Highcharts.chart('learners_by_assessors', JSONfn.parse(JSON.stringify(<?php echo $learners_by_assessors; ?>)));
        var chart = new Highcharts.chart('SolidLearnerProgressionL2L3', <?php echo HomePageV2::renderGauage($link, 'L2 to L3'); ?>);
        var chart = new Highcharts.chart('SolidLearnerProgressionL3L4', <?php echo HomePageV2::renderGauage($link, 'L3 to L4'); ?>);
        var chart = new Highcharts.chart('SolidLearnerProgressionTP', <?php echo HomePageV2::renderGauage($link, 'Traineeship to App.'); ?>);
        <?php } ?>

        var chart = new Highcharts.chart('on_programme_by_duration_left_graph', <?php echo $on_programme_stats['on_programme_by_duration_left_graph']; ?>);
        var chart = new Highcharts.chart('overstayers_by_expected_month', <?php echo $overstayers_by_expected_month; ?>);
        var chart = new Highcharts.chart('withdrawals_in_current_submission_year', <?php echo $withdrawals_in_current_submission_year; ?>);
        var chart = new Highcharts.chart('completions_due_by_expected_month', <?php echo $completions_due_by_expected_month; ?>);


    });

    function showDetail(ids)
    {
        if(ids == '')
            return;

        var frmFilters = document.forms["frmFilters"];
        frmFilters.filter_tr_ids.value = ids;

        frmFilters.submit();
    }


</script>
</body>
</html>
