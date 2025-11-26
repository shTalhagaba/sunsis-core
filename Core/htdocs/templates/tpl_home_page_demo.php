<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Homepage</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/skins/_all-skins.min.css">
    <link href="/assets/adminlte/plugins/pace/pace.css" rel="stylesheet">
    <link href="/assets/adminlte/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="wrapper">
    <header class="main-header"></header>

    <div class="content-wrapper">
        
	<?php include_once(__DIR__ . '/layout/tpl_main_header_users.php'); ?>

        <section class="content">
            <div class="row">
                <div class="col-lg-6">
                    <div class="box box-primary">
                        <div class="box-header with-border"><h3 class="box-title"><span class="glyphicon glyphicon-stats"></span> Learners (2025 - 2026)</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <?php if(in_array(DB_NAME, ["am_lead"])){ ?>
                                <div class="col-lg-6 col-xs-6">
                                    <div class="small-box bg-aqua">
                                        <div class="inner">
                                            <h3><?php echo DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id WHERE contract_year = '2025' AND tr.status_code = '1' AND tr.outcome != '8' {$_where}");?></h3>
                                            <p>Learners in training</p>
                                        </div>
                                        <div class="icon"><i class="fa fa-hourglass-half"></i></div>
                                        <a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=1&ViewTrainingRecords_filter_contract_year=2025&ViewTrainingRecords_filter_gateway=2" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>    
                                <div class="col-lg-6 col-xs-6">
                                    <div class="small-box bg-aqua">
                                        <div class="inner">
                                            <h3><?php echo DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id WHERE contract_year = '2025' AND tr.status_code = '1' AND tr.outcome = '8' {$_where}");?></h3>
                                            <p>Gateway Learners</p>
                                        </div>
                                        <div class="icon"><i class="fa fa-hourglass-half"></i></div>
                                        <a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=1&ViewTrainingRecords_filter_contract_year=2025&ViewTrainingRecords_filter_gateway=1" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                                <?php } else {?>
                                <div class="col-lg-6 col-xs-6">
                                    <div class="small-box bg-aqua">
                                        <div class="inner">
                                            <h3><?php echo DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id WHERE contract_year = '2025' AND tr.status_code = '1' {$_where}");?></h3>
                                            <p>Learners in training</p>
                                        </div>
                                        <div class="icon"><i class="fa fa-hourglass-half"></i></div>
                                        <a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=1&ViewTrainingRecords_filter_contract_year=2025" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                                <?php } ?>

                                <div class="col-lg-6 col-xs-6">
                                    <div class="small-box bg-red">
                                        <div class="inner">
                                            <h3><?php echo DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr INNER JOIN contracts ON tr.`contract_id` = contracts.id WHERE contract_year = 2025 AND tr.`status_code` = 1 AND target_date < CURDATE() {$_where};");?></h3>
                                            <p>Learners past planned end date</p>
                                        </div>
                                        <div class="icon"><i class="fa fa-calendar-plus-o"></i></div>
                                        <?php $cdate = date('d/m/Y');
                                        $href = '"do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_target_end_date='.$cdate.'&ViewTrainingRecords_filter_contract_year=2025"';
                                        ?>
                                        <a href=<?php echo $href; ?> class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xs-6">
                                    <div class="small-box bg-yellow">
                                        <div class="inner">
                                            <h3><?php echo DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id WHERE contract_year = '2025' AND tr.status_code = '6' {$_where} AND tr.l03 NOT IN (SELECT tr2.l03 FROM tr AS tr2 WHERE tr2.`start_date` > tr.`start_date` AND tr2.status_code != '6')");?></h3>
                                            <p>Learners temporarily withdrawn (<span class="small">not yet returned</span>)</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-pause"></i>
                                        </div>
                                        <a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=7&ViewTrainingRecords_filter_contract_year=2025" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xs-6">
                                    <div class="small-box bg-red">
                                        <div class="inner">
                                            <h3><?php echo DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id WHERE contract_year = '2025' AND tr.status_code = '3' AND tr.outcome = '3' {$_where}");?></h3>
                                            <p>Learners withdrawn</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-chain-broken"></i>
                                        </div>
                                        <a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=3&ViewTrainingRecords_filter_contract_year=2025" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="box box-primary">
                        <div class="box-header with-border"><h3 class="box-title"><span class="glyphicon glyphicon-stats"></span> Completion/Progression (2025 - 2026)</h3>
                            <div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" onclick="swapCompletionTiles();"><i class="fa fa-arrows-h"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body" id="boxCompletionProgression">

                            <div class="overlay">
                                <i class="fa fa-refresh fa-spin"></i> Loading...
                            </div>

                        </div>
			<div class="box-body" id="boxCompletionProgression2" style="display: none;">

                            <table class="table table-bordered" style="font-size: medium;">
                                <caption class="text-bold text-info">Completions by Outcome Type</caption>
				<col width="70%;">
                                <tr>
                                    <th>Fully Achieved</th>
                                    <td class="lead text-bold">
                                        <a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=2&ViewTrainingRecords_filter_contract_year=2025&ViewTrainingRecords_filter_outcome=1">
                                            <?php echo DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id WHERE contract_year = '2025' AND tr.status_code = '2' AND tr.outcome = '1'");?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Partial Achievement</th>
                                    <td class="lead text-bold">
                                        <a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=2&ViewTrainingRecords_filter_contract_year=2025&ViewTrainingRecords_filter_outcome=2">
                                            <?php echo DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id WHERE contract_year = '2025' AND tr.status_code = '2' AND tr.outcome = '2'");?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Learning activities complete and Outcome Unknown</th>
                                    <td class="lead text-bold">
                                        <a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=2&ViewTrainingRecords_filter_contract_year=2025&ViewTrainingRecords_filter_outcome=3">
                                            <?php echo DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id WHERE contract_year = '2025' AND tr.status_code = '2' AND tr.outcome = '8'");?>
                                        </a>
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="box box-primary ">
                        <div class="box-body table-responsive">
                            <span class="text-info text-bold"><i class="fa fa-info-circle"></i> New starts over the previous 6 months</span>
                            <table class="table table-bordered text-center">
                                <?php
                                $start_stats_previous_6_months = HomePageV2::getStartsGraphs($link);
                                echo '<tr>';
                                foreach($start_stats_previous_6_months AS $month => $detail)
                                {
                                    echo '<td>' . $month . '</td>';
                                }
                                echo '<td>Total</td>';
                                echo '</tr>';
                                echo '<tr>';
                                $total = 0;
                                
                                foreach($start_stats_previous_6_months AS $month => $detail)
                                {
                                    echo '<td class="text-blue text-bold" style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $detail->tr_ids) . '\');">' . count($detail->tr_ids) . '</td>';
                                    $total += count($detail->tr_ids);
                                }
                                echo '<td>' . $total . '</td>';
                                echo '</tr>';
                                ?>
                            </table>
                            <p><hr></p>
                            <?php $withdrawals_in_current_submission_year = HomePageV2::getWithdrawalsGraph($link); ?>
                            <div id="withdrawals_in_current_submission_year" style="height: 250px;"></div>
			    <?php if(DB_NAME != "am_demo"){?>
			    <p class="small text-info"> A learner withdrawn back to within 42 days/6 weeks (qualifying period) of their start date receives no ESFA funding and is excluded from the calculation of achievement rates.</p>
			    <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="box box-primary ">
                        <div class="box-body table-responsive">
                            <table class="table table-bordered">
                                <?php
                                $on_programme_stats = HomePageV2::getOnProgrammeStats($link);
                                echo '<tr><th style="width: 50%;">On-programme learners</th><th>Of which: Overstayers</th><th>% of overstayers</th></tr>';
                                echo '<tr>';
                                echo count($on_programme_stats['on_programme']) < 500 ? 
                                    '<td class="text-blue text-bold" style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $on_programme_stats['on_programme']) . '\');">' . count($on_programme_stats['on_programme']) . '</td>' :
                                    '<td class="text-blue text-bold">' . count($on_programme_stats['on_programme']) . '</td>';
                                echo '<td class="text-blue text-bold" style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $on_programme_stats['overstayer']) . '\');">' . count($on_programme_stats['overstayer']) . '</td>';
                                echo count($on_programme_stats['on_programme']) > 0 ?
                                    '<td>' . round((count($on_programme_stats['overstayer']) / count($on_programme_stats['on_programme']))*100, 2) . '%</td>' :
                                    '<td>' . round((count($on_programme_stats['overstayer']) / 1)*100, 2) . '%</td>';
                                echo '</tr>';
                                ?>
                            </table>
                            <p><br></p>
					        <div id="on_programme_by_duration_left_graph" style="height: 250px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="box box-primary small">
                        <div class="box-body">
                            <div class="table-responsive">
                                <?php $completions_due_by_expected_month = HomePageV2::getUpcomingCompletionsGraph($link); ?>
                                <div id="completions_due_by_expected_month" style="height: 350px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="box box-primary small">
                        <div class="box-body">
                            <div class="table-responsive">
                                <?php $overstayers_by_expected_month = HomePageV2::getOverstayersByExpectedMonthGraph($link); ?>
                                <div id="overstayers_by_expected_month" style="height: 350px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="box box-primary" style="max-height: 350px;">
                        <div class="box-header with-border">
                            <h3 class="box-title"><span class="fa fa-pie-chart"></span> Learners by Progress (2025 - 2026)</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="chart-responsive">
                                        <canvas style="display: block;" id="pieChartLearnerProgress" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer no-padding">
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_progress=1">On Track<span class="pull-right text-red"><?php echo $percentage_on_track;?>%</span></a></li>
                                <li><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_progress=2">Behind<span class="pull-right text-red"><?php echo $percentage_behind;?>%</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><span class="fa fa-calendar"></span> ILR Submissions (Funding Year: 2025-26)</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive" style="max-height: 300px; overflow-y: scroll;">
				<?php if(false){ ?>
                                <table class="table table-bordered">
                                    <caption class="text-info text-bold text-center">Funding Year 2020/21</caption>
                                    <tr class="small"><th>Period</th><th>Last Submission Date</th><th>Total ILRs</th><th>Valid ILRs</th><th>Invalid ILRs</th></tr>
                                    <?php
                                    $submissions_details = DAO::getResultset($link, "SELECT * FROM central.lookup_submission_dates WHERE contract_year IN (2020)", DAO::FETCH_ASSOC);
                                    $current_submission = DAO::getSingleValue($link, "SELECT right(submission,2) FROM central.`lookup_submission_dates` WHERE contract_year = 2025  and CURDATE() BETWEEN start_submission_date AND last_submission_date;");
                                    $current_submission = 'W' . $current_submission;
                                    foreach($submissions_details AS $submission_record)
                                    {
                                        if($submission_record['submission'] < $current_submission)
                                            continue;
                                        if($submission_record['submission'] == $current_submission)
                                        {
                                            $today = new Date(date('Y-m-d'));
                                            $last_submission_date = new Date($submission_record['last_submission_date']);
                                            $days_left = Date::dateDiffInfo($today, $last_submission_date);
                                            echo '<tr class="bg-warning"><td>' . $submission_record['submission'] . '</td><td>' . Date::toShort($submission_record['last_submission_date']) . ' (' . $days_left['days'] . ' days left)</td><td>' . $total_ilrs . '</td><td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_funding=1">' . $valid_ilrs . '</a></td><td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_funding=2">' . $invalid_ilrs . '</a></td></tr>';
                                        }
                                        else
                                            echo '<tr><td>' . $submission_record['submission'] . '</td><td>' . Date::toShort($submission_record['last_submission_date']) . '</td><td>-</td><td>-</td><td>-</td></tr>';
                                    }
                                    ?>
                                </table>
				<?php } ?>
                                <table class="table table-bordered">
                                    <caption class="text-info text-bold text-center">Funding Year 2025/26</caption>
                                    <tr class="small"><th>Period</th><th>Last Submission Date</th><th>Total ILRs</th><th>Valid ILRs</th><th>Invalid ILRs</th></tr>
                                    <?php
                                    $submissions_details = DAO::getResultset($link, "SELECT * FROM central.lookup_submission_dates WHERE contract_year IN (2025)", DAO::FETCH_ASSOC);
                                    $current_submission = DAO::getSingleValue($link, "SELECT right(submission,2) FROM central.`lookup_submission_dates` WHERE contract_year = 2025  and CURDATE() BETWEEN start_submission_date AND last_submission_date;");
                                    $current_submission = 'W' . $current_submission;
                                    foreach($submissions_details AS $submission_record)
                                    {
                                        if($submission_record['submission'] < $current_submission)
                                            continue;
                                        if($submission_record['submission'] == $current_submission)
                                        {
                                            $today = new Date(date('Y-m-d'));
                                            $last_submission_date = new Date($submission_record['last_submission_date']);
                                            $days_left = Date::dateDiffInfo($today, $last_submission_date);
                                            echo '<tr class="bg-warning"><td>' . $submission_record['submission'] . '</td><td>' . Date::toShort($submission_record['last_submission_date']) . ' (' . $days_left['days'] . ' days left)</td><td>' . $total_ilrs . '</td><td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_funding=1">' . $valid_ilrs . '</a></td><td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_funding=2">' . $invalid_ilrs . '</a></td></tr>';
                                        }
                                        else
                                            echo '<tr><td>' . $submission_record['submission'] . '</td><td>' . Date::toShort($submission_record['last_submission_date']) . '</td><td>-</td><td>-</td><td>-</td></tr>';
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><span class="fa fa-info-circle"></span> How to Guides</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body" style="max-height: 300px; overflow-y: scroll;">
                            <p class="text-info">Please use the guides below to help with your use of Sunesis. All our 'How to' guides are in PDF format.</p>
                            <ul class="list-group list-group-unbordered">
                                <?php
                                $how_to_dir = DATA_ROOT."/uploads/am_demo/howto";
                                $files = Repository::readDirectory($how_to_dir);
                                if(count($files) == 0){
                                    echo '<i>No files uploaded</i>';
                                }
                                foreach($files as $f)
                                {
                                    if($f->isDir() || $f->getName() == "On Boarding Software User Guide_Jan2021_V2.pdf"){
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
                                    echo '<li class="list-group-item"><a href="do.php?_action=downloader&path=/am_demo/howto/'. "&f=" . $f->getName() . '"><i class="fa '.$image.'"></i> ' . htmlspecialchars((string)$f->getName()) . '</a><br><span class="direct-chat-timestamp "><i class="fa fa-clock-o"></i> <small>' . date("d/m/Y H:i:s", $f->getModifiedTime()) .'</small></span></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>


                </div>

            </div>

            <div class="row">
                <div class="col-md-8">

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><span class="glyphicon glyphicon-stats"></span> Learners by Assessors (2025 - 2026)</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="chart-responsive">
                                        <canvas id="canvas" ></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <?php
                if(DB_NAME == "am_demo") {
                    echo HomePage::renderOtjProgressPanel($link);
                }
                ?>

            </div>

        </section>

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
<script src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>
<script src="/assets/adminlte/plugins/chartjs/Chart.min.js"></script>

<script src="https://code.highcharts.com/7.0.0/highcharts.src.js"></script>
<script src="https://code.highcharts.com/7.0.0/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/7.0.0/modules/exporting.js"></script>
<script src="https://code.highcharts.com/7.0.0/modules/no-data-to-display.js"></script>
<script src="module_charts/assets/jsonfn.js"></script>

<script>

    var pieChartCanvas = $("#pieChartLearnerProgress").get(0).getContext("2d");
    var pieChartLearnerProgress = new Chart(pieChartCanvas);
    var PieData = [{value: <?php echo $learners_on_track; ?>,color: "lightgreen",label: "On Track"},{value: <?php echo $learners_behind; ?>,color: "red",label: "Behind"}];
    var pieOptions = {
        percentageInnerCutout: 0, // This is 0 for Pie charts
        animationSteps: 100,
        animationEasing: "easeOutBounce",
        animateRotate: true,
        animateScale: false,
        responsive: true,
        maintainAspectRatio: false,
        tooltipEvents: [],
        showTooltips: true,
        onAnimationComplete: function() {
            this.showTooltip(this.segments, true);
        },
        tooltipTemplate: "<%= label %> - <%= value %>"
    };
    pieChartLearnerProgress.Doughnut(PieData, pieOptions);

    $(function(){
        
	<?php if($days_remaining_for_password_change <= 5) { ?>
            var change_password_message = "<h5 class=\"text-bold text-danger\"><i class=\"fa fa-warning\"></i> Time to update your password</h5>";
            change_password_message += "<p>You updated your password <?php echo $days_password_changed; ?> days ago. For security reasons, please change your password.</p>";
            change_password_message += "<p><a href=\"do.php?_action=change_password\" class=\"btn btn-sm btn-primary\">Change Password</a></p>";
            change_password_message += "<p>Please note that after <?php echo $days_remaining_for_password_change == 1 ? 'tomorrow' : $days_remaining_for_password_change . ' days'; ?> you won't be able to do anything unless you change your password.</p>";
            $("<div></div>").html(change_password_message).dialog({
                id: "dlg_change_password",
                title: "Change Your Password",
                resizable: false,
                modal: true,
                width: 400,
                height: 312,

                buttons: {
                    'Close': function() {
                        $(this).dialog('close');
                    }
                }
            });
        <?php } ?>

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


        var lineChartData = {
            labels : <?php echo json_encode($assessors);?>,
            datasets : [
                {
                    label: "Continuing",
                    fillColor: randomColorGenerator(),
                    strokeColor: randomColorGenerator(),
                    //highlightFill: randomColorGenerator(),
                    //highlightStroke: randomColorGenerator(),
                    data : <?php echo json_encode(array_values($assessorsValuesContinuing));?>
                },
                {
                    label: "Completed",
                    fillColor: randomColorGenerator(),
                    strokeColor: randomColorGenerator(),
                    //highlightFill: randomColorGenerator(),
                    //highlightStroke: randomColorGenerator(),
                    data : <?php echo json_encode(array_values($assessorsValuesCompleted));?>
                },
                {
                    label: "Early Leavers",
                    fillColor: randomColorGenerator(),
                    strokeColor: randomColorGenerator(),
                    //highlightFill: randomColorGenerator(),
                    //highlightStroke: randomColorGenerator(),
                    data : <?php echo json_encode(array_values($assessorsValuesEarlyLeavers));?>
                }
            ]
        }

        var ctx = document.getElementById("canvas").getContext("2d");
        window.myLine = new Chart(ctx).Bar(lineChartData, {
            responsive: true
        });

        

        // load completion/progression panel

        $.ajax({
            type:'GET',
            url:'do.php?_action=ajax_home_page&subaction=load_completion_progression',
            success: function(response) {
                $('#boxCompletionProgression').html(response);
            },
            error: function(data, textStatus, xhr){
                console.log(data.responseText);
            }
        });

	new Highcharts.chart('pieBootcampOutcome', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'Bootcamp by Outcome Type'
    },
    tooltip: {
        valueSuffix: '%'
    },
    plotOptions: {
        "pie": {
         "allowPointSelect": true,
         "cursor": "pointer",
         "dataLabels": {
            "enabled": true,
            "format": "<b>{point.name}<\/b>: ({point.percentage:.0f} %)"
         },
         "showInLegend": true
      }
    },
    series: [
        {
            name: 'Percentage',
            colorByPoint: true,
            data: [
                {
                    name: 'No Positive Outcome Reported',
                    y: 45
                },
                {
                    name: 'New Employment',
                    y: 23
                },
                {
                    name: 'Self Employment',
                    y: 7
                },
                {
                    name: 'New or Increased Responsibilities at Work',
                    y: 18
                },
                {
                    name: 'New Role with Current Employer',
                    y: 10
                }
            ]
        }
    ]
});

    });

    var randomColorGenerator = function () {
        return '#' + (Math.random().toString(16) + '0000000').slice(2, 8);
    };

    <?php
    if(DB_NAME == "am_demo") {
        echo HomePage::renderOtjProgressPanelJs($link);
    } ?>

    function showDetail(ids)
	{
		if(ids == '')
			return;

		var frmFilters = document.forms["frmFilters"];
		frmFilters.filter_tr_ids.value = ids;

		frmFilters.submit();
	}

    function initializeCharts()
    {
        var chart = new Highcharts.chart('on_programme_by_duration_left_graph', <?php echo $on_programme_stats['on_programme_by_duration_left_graph']; ?>);
        var chart = new Highcharts.chart('withdrawals_in_current_submission_year', <?php echo $withdrawals_in_current_submission_year; ?>);
        var chart = new Highcharts.chart('completions_due_by_expected_month', <?php echo $completions_due_by_expected_month; ?>);
        var chart = new Highcharts.chart('overstayers_by_expected_month', <?php echo $overstayers_by_expected_month; ?>);
    }

    $(window).load(initializeCharts);

	function swapCompletionTiles()
    {
        if($("#boxCompletionProgression").is(':visible'))
        {
            $("#boxCompletionProgression").hide();
            $("#boxCompletionProgression2").show();
        }
        else
        {
            $("#boxCompletionProgression").show();
            $("#boxCompletionProgression2").hide();
        }
    }

</script>
</body>
</html>
