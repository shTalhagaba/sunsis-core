<?php 
$caseload_only = $_SESSION['user']->employer_id == 3278 ? true : false;

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Homepage</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/skins/_all-skins.min.css">
    <link href="/assets/adminlte/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/adminlte/plugins/fullcalendar/fullcalendar.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>

</head>

<body>

<div class="wrapper">
    <header class="main-header"></header>

    <div class="content-wrapper">
        <section class="content-header">
            <h1><span class="fa fa-dashboard"></span> Dashboard<span class="pull-right"><img class="img-rounded" src="images/logos/SUNlogo.png" height="35px;"/></span></h1>
        </section>

	<?php if(!$caseload_only) {?>
        <section class="content">
            <div class="row">
                <div class="col-sm-4">
                    <div class="box box-info box-solid">
                        <div class="box-header with-border">Quick Search</div>
                        <div class="box-body small" style="height: 400px;">
                            <div class="callout callout-default">
                                <form role="form" class="form-vertical" name="frmQuickSearchEmployer" id="frmQuickSearchEmployer"
                                      action="do.php?_action=home_page_duplex" method="post">
                                    <input type="hidden" name="subaction" value="quickSearchEmployer" >
                                    <div class="form-group">
                                        <label for="txtSearchEmployer">Employer Name:</label>
                                        <input type="text" class="form-control" name="txtSearchEmployer" placeholder="Enter employer name" required>
                                    </div>
                                    <!-- <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="txtSearchEmployerTelephone">Employer Telephone:</label>
                                                <input type="text" class="form-control" name="txtSearchEmployerTelephone" placeholder="Enter employer telephone">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="txtSearchEmployerContactTelephone">Employer Contact Telephone:</label>
                                                <input type="text" class="form-control" name="txtSearchEmployerContactTelephone" placeholder="Enter employer contact telephone">
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="form-group">
                                        <button name="quickSearchEmployer" type="submit" class="btn btn-xs btn-info pull-right"><i class="fa fa-search"></i> Search Employer</button>
                                    </div>
                                </form>
                            </div>

                            <div class="callout callout-default">
                                <form role="form" class="form-vertical" name="frmQuickSearchLearner" id="frmQuickSearchLearner"
                                      action="do.php?_action=home_page_duplex" method="post">
                                    <input type="hidden" name="subaction" value="quickSearchLearner" >
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="txtSearchLearnerFirstname">Learner First Name:</label>
                                                <input type="text" class="form-control" name="txtSearchLearnerFirstname" placeholder="Enter learner firstname" >
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="txtSearchLearnerSurname">Learner Surname:</label>
                                                <input type="text" class="form-control" name="txtSearchLearnerSurname" placeholder="Enter learner surname" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="txtSearchLearnerMobile">Learner Mobile Number:</label>
                                        <input type="text" class="form-control" name="txtSearchLearnerMobile" placeholder="Enter learner mobile number" >
                                    </div>
                                    <div class="form-group">
                                        <button name="quickSearchLearner" type="submit" class="btn btn-xs btn-info pull-right"><i class="fa fa-search"></i> Search Learner</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
		    <br>
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>
                                <?php
                                $view = ViewLearnersV2::getInstance($link);
                                $q = [
                                    'ViewLearnersV2_'.View::KEY_PAGE_SIZE => 0, // No limit
                                    'ViewLearnersV2_filter_ni' => 2,
                                    '_reset' => 1,
                                ];
                                $view->refresh($link, $q);
                                echo $view->getRowCount();
                                ?>
                            </h3>
                            <p>Learners without NI Number</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-warning"></i>
                        </div>
                        <a href="do.php?_action=view_learners&<?php echo http_build_query($q); ?>" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="box box-info box-solid">
                        <div class="box-header with-border">Stats</div>
                        <div class="box-body" style="height: 400px;">
                            <p>
                                <span class="btn btn-info btn-xs" onclick="window.location.href='do.php?_action=view_employers&_reset=1'">View All Employers</span>
                                <span class="btn btn-primary btn-xs" onclick="window.location.href='do.php?_action=edit_employer_v2'">Create New Employer</span>
                            </p>
                            <table class="table table-bordered table-condensed">
                                <caption class="text-bold">Employers</caption>
                                <tr class="bg-gray"><th>Status</th><th>Count</th></tr>
                                <?php
                                $stat_emp_rows = '';
                                $employers_stats_by_status = DAO::getResultset($link, "SELECT org_status, lookup_org_status.`description`, COUNT(*) AS cnt FROM organisations LEFT JOIN lookup_org_status ON organisations.`org_status` = lookup_org_status.`id` WHERE organisations.organisation_type = 2 GROUP BY org_status;", DAO::FETCH_ASSOC);
                                foreach($employers_stats_by_status AS $row_stat)
                                {
                                    $q = [
                                        'ViewEmployersV2_filter_org_status' => $row_stat['org_status'],
                                        'ViewEmployersV2_filter_active' => 1, // Show all
                                        'ViewEmployersV2_'.View::KEY_PAGE_SIZE => 0, // No limit
                                        '_reset' => 1,
                                    ];
                                    $stat_emp_rows .= HTML::viewrow_opening_tag('do.php?_action=view_employers&'.http_build_query($q));
                                    $stat_emp_rows .= '<td>' . $row_stat['description'] . '</td>';
                                    $stat_emp_rows .= '<td align="center">' . $row_stat['cnt'] . '</td>';
                                    $stat_emp_rows .= '</tr>';
                                }
                                echo count($employers_stats_by_status) == 0 ? '<tr><td colspan="2"><i>No records found.</i></td> </tr>' : $stat_emp_rows;
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="box box-info box-solid">
                        <div class="box-header with-border">Stats</div>
                        <div class="box-body">
                            <p>
                                <span class="btn btn-info btn-xs" onclick="window.location.href='do.php?_action=view_learners&_reset=1'">View All Learners</span>
                                <span class="btn btn-primary btn-xs" onclick="window.location.href='do.php?_action=edit_learner_duplex'">Create New Learner</span>
                            </p>
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tabNonEm" data-toggle="tab">West Midlands</a></li>
                                    <li><a href="#tabEm" data-toggle="tab">East Midlands</a></li>
				    <li><a href="#tabPsa" data-toggle="tab">PSA</a></li>	
                                </ul>
                                <div class="tab-content">
                                    <div class="active tab-pane" id="tabNonEm">
                                        <table class="table table-bordered table-condensed">
                                            <caption class="text-bold">Learners</caption>
                                            <tr class="bg-gray"><th>Status</th><th>Count</th></tr>
                                            <?php
                                            $stat_learner_rows = '';
                                            $view = ViewLearnersV2::getInstance($link);
                                            $q = [
                                                'ViewLearnersV2_'.View::KEY_PAGE_SIZE => 0, // No limit
                                                'ViewLearnersV2_filter_east_midland' => 1,
                                                '_reset' => 1,
                                            ];
                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 1]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>New</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 2]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Booked</td><td align="center">' . DAO::getSingleValue($link, "SELECT COUNT(DISTINCT training.id) FROM training INNER JOIN crm_training_schedule ON training.schedule_id = crm_training_schedule.id WHERE crm_training_schedule.`venue` != 'Ruddington' AND training.learner_id IN (SELECT id FROM users)") . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 6]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Booked Level 3</td><td align="center">' . DAO::getSingleValue($link, "SELECT COUNT(*) FROM training INNER JOIN crm_training_schedule ON training.schedule_id = crm_training_schedule.id WHERE crm_training_schedule.`venue` != 'Ruddington' AND crm_training_schedule.level = 'L3' AND training.learner_id IN (SELECT id FROM users)") . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 7]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Booked Level 4</td><td align="center">' . DAO::getSingleValue($link, "SELECT COUNT(*) FROM training INNER JOIN crm_training_schedule ON training.schedule_id = crm_training_schedule.id WHERE crm_training_schedule.`venue` != 'Ruddington' AND crm_training_schedule.level = 'L4' AND training.learner_id IN (SELECT id FROM users)") . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 2, 'ViewLearnersV2_filter_postcode_status' => 1]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Booked with <span class="text-success">valid</span> postcode</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 8, 'ViewLearnersV2_filter_postcode_status' => 2]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Booked with <span class="text-danger">invalid</span> postcode</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 3]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Level 3 <span class="text-success">Completed</span></td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 4]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Level 4 <span class="text-success">Completed</span></td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 5]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Level 3 <span class="text-success">Completed</span> and not Level 4</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 8, 'ViewLearnersV2_filter_imi_redeem_code' => 2]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Booked with <span class="text-danger">no</span> IMI code</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 8, 'ViewLearnersV2_filter_hs_form_status' => 1]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Booked with <span class="text-danger">incomplete</span> HS Form</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 9]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Level 1</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 10]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Level 2</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 11]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Needs rebooking for Level 3</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 12]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Needs rebooking for Level 4</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_outstanding_payment' => 2]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Outstanding Payment</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            echo $stat_learner_rows;
                                            ?>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="tabEm">
                                        <table class="table table-bordered table-condensed">
                                            <caption class="text-bold">Learners</caption>
                                            <tr class="bg-gray"><th>Status</th><th>Count</th></tr>
                                            <?php
                                            $stat_learner_rows = '';
                                            $view = ViewLearnersV2::getInstance($link);
                                            $q = [
                                                'ViewLearnersV2_'.View::KEY_PAGE_SIZE => 0, // No limit
                                                'ViewLearnersV2_filter_east_midland' => 2,
                                                '_reset' => 1,
                                            ];
                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 1]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>New</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 2]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Booked</td><td align="center">' . DAO::getSingleValue($link, "SELECT COUNT(*) FROM training INNER JOIN crm_training_schedule ON training.schedule_id = crm_training_schedule.id WHERE crm_training_schedule.`venue` = 'Ruddington' AND training.learner_id IN (SELECT id FROM users)") . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 6]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Booked Level 3</td><td align="center">' . DAO::getSingleValue($link, "SELECT COUNT(*) FROM training INNER JOIN crm_training_schedule ON training.schedule_id = crm_training_schedule.id WHERE crm_training_schedule.`venue` = 'Ruddington' AND crm_training_schedule.level = 'L3' AND training.learner_id IN (SELECT id FROM users)") . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 7]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Booked Level 4</td><td align="center">' . DAO::getSingleValue($link, "SELECT COUNT(*) FROM training INNER JOIN crm_training_schedule ON training.schedule_id = crm_training_schedule.id WHERE crm_training_schedule.`venue` = 'Ruddington' AND crm_training_schedule.level = 'L4' AND training.learner_id IN (SELECT id FROM users)") . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 2, 'ViewLearnersV2_filter_postcode_status' => 1]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Booked with <span class="text-success">valid</span> postcode</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 8, 'ViewLearnersV2_filter_postcode_status' => 2]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Booked with <span class="text-danger">invalid</span> postcode</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 3]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Level 3 <span class="text-success">Completed</span></td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 4]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Level 4 <span class="text-success">Completed</span></td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 5]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Level 3 <span class="text-success">Completed</span> and not Level 4</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 8, 'ViewLearnersV2_filter_imi_redeem_code' => 2]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Booked with <span class="text-danger">no</span> IMI code</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 8, 'ViewLearnersV2_filter_hs_form_status' => 1]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Booked with <span class="text-danger">incomplete</span> HS Form</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 9]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Level 1</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 10]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Level 2</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 11]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Needs rebooking for Level 3</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 12]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Needs rebooking for Level 4</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_outstanding_payment' => 2]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Outstanding Payment</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            echo $stat_learner_rows;
                                            ?>
                                        </table>
                                    </div>
				    <div class="tab-pane" id="tabPsa">
                                        <table class="table table-bordered table-condensed">
                                            <caption class="text-bold">Learners</caption>
                                            <tr class="bg-gray"><th>Status</th><th>Count</th></tr>
                                            <?php
                                            $stat_learner_rows = '';
                                            $view = ViewLearnersV2::getInstance($link);
                                            $q = [
                                                'ViewLearnersV2_'.View::KEY_PAGE_SIZE => 0, // No limit
                                                'ViewLearnersV2_filter_psa_learner' => 2,
                                                '_reset' => 1,
                                            ];
                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 1]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>New</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 2]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Booked</td><td align="center">' . DAO::getSingleValue($link, "SELECT COUNT(*) FROM training INNER JOIN crm_training_schedule ON training.schedule_id = crm_training_schedule.id WHERE crm_training_schedule.`venue` = 'Peterborough Skills Academy' AND training.learner_id IN (SELECT id FROM users)") . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 6]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Booked Level 3</td><td align="center">' . DAO::getSingleValue($link, "SELECT COUNT(*) FROM training INNER JOIN crm_training_schedule ON training.schedule_id = crm_training_schedule.id WHERE crm_training_schedule.`venue` = 'Peterborough Skills Academy' AND crm_training_schedule.level = 'L3' AND training.learner_id IN (SELECT id FROM users)") . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 7]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Booked Level 4</td><td align="center">' . DAO::getSingleValue($link, "SELECT COUNT(*) FROM training INNER JOIN crm_training_schedule ON training.schedule_id = crm_training_schedule.id WHERE crm_training_schedule.`venue` = 'Peterborough Skills Academy' AND crm_training_schedule.level = 'L4' AND training.learner_id IN (SELECT id FROM users)") . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 2, 'ViewLearnersV2_filter_postcode_status' => 1]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Booked with <span class="text-success">valid</span> postcode</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 8, 'ViewLearnersV2_filter_postcode_status' => 2]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Booked with <span class="text-danger">invalid</span> postcode</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 3]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Level 3 <span class="text-success">Completed</span></td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 4]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Level 4 <span class="text-success">Completed</span></td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 5]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Level 3 <span class="text-success">Completed</span> and not Level 4</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 8, 'ViewLearnersV2_filter_imi_redeem_code' => 2]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Booked with <span class="text-danger">no</span> IMI code</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 8, 'ViewLearnersV2_filter_hs_form_status' => 1]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Booked with <span class="text-danger">incomplete</span> HS Form</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 9]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Level 1</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 10]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Level 2</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 11]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Needs rebooking for Level 3</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 12]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Needs rebooking for Level 4</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            $qs = array_merge($q, ['ViewLearnersV2_filter_outstanding_payment' => 2]);
                                            $view->refresh($link, $qs);
                                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                                            $stat_learner_rows .= '<td>Outstanding Payment</td><td align="center">' . $view->getRowCount() . '</td>';
                                            $stat_learner_rows .= '</tr>';

                                            echo $stat_learner_rows;
                                            ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="box box-solid box-info">
                        <div class="box-header with-border">Training Dates in next 6 months</div>
                        <div class="box-body" style="max-height: 500px; overflow-y: scroll">
                            <p>
                                <span class="btn btn-info btn-xs" onclick="window.location.href='do.php?_action=view_crm_schedule_entries'">View All</span>
                                <span class="btn btn-primary btn-xs" onclick="window.location.href='do.php?_action=view_crm_scheduler'">Create New</span>
                            </p>
                            <div class="table-responsive">
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#tabL3Dates" data-toggle="tab">Level 3</a></li>
                                        <li><a href="#tabL4Dates" data-toggle="tab">Level 4</a></li>
                                        <li><a href="#tabL2Dates" data-toggle="tab">Level 2</a></li>
                                        <li><a href="#tabL1Dates" data-toggle="tab">Level 1</a></li>
                                        <li><a href="#tabML3Dates" data-toggle="tab">MAN Level 3</a></li>
                                        <li><a href="#tabFGDates" data-toggle="tab">F-Gas</a></li>
                                        <li><a href="#tabADASL1Dates" data-toggle="tab">ADAS L1</a></li>
                                        <li><a href="#tabADASL2Dates" data-toggle="tab">ADAS L2</a></li>
                                        <li><a href="#tabADASL3Dates" data-toggle="tab">ADAS L3</a></li>
                                        <li><a href="#tabLVDTDates" data-toggle="tab">LVDT</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="active tab-pane" id="tabL3Dates">
                                            <table class="table table-bordered table-condensed">
                                                <tr class="bg-gray">
                                                    <th>Level</th><th>Dates</th><th>Duration</th><th>Venue</th><th>Capacity</th><th>Remaining</th><th class="small">No IMI Code</th><th class="small">No NI</th>
                                                </tr>
                                                <?php
                                                $l3_sql = <<<SQL
SELECT
  crm_training_schedule.*,
  (SELECT COUNT(*) FROM training WHERE training.`schedule_id` = crm_training_schedule.`id`) AS assigned,
  (SELECT COUNT(*) FROM training INNER JOIN users ON training.`learner_id` = users.id WHERE training.`schedule_id` = crm_training_schedule.`id` 
            AND users.`type` = 5 AND users.`imi_redeem_code` IS NULL) AS no_imi,
  (SELECT COUNT(*) FROM training INNER JOIN users ON training.`learner_id` = users.id WHERE training.`schedule_id` = crm_training_schedule.`id` 
            AND users.`type` = 5 AND users.`ni` IS NULL) AS no_ni
FROM
  crm_training_schedule
WHERE LEVEL = 'L3'
  #AND training_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 24 WEEK)
  AND training_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
ORDER BY training_date
LIMIT 10;
SQL;
                                                $training_dates_records = DAO::getResultset($link, $l3_sql, DAO::FETCH_ASSOC);
                                                if(count($training_dates_records) == 0)
                                                {
                                                    echo '<tr><td colspan="6"><i>No level 3 training dates have been found.</i></td></tr>';
                                                }
                                                else
                                                {
                                                    foreach($training_dates_records AS $tr_row)
                                                    {
                                                        echo HTML::viewrow_opening_tag('do.php?_action=view_edit_crm_schedule&id='.$tr_row['id']);
                                                        echo '<td>' . $tr_row['level'] . '</td>';
                                                        echo '<td>';
                                                        echo Date::toShort($tr_row['training_date']) . ' - ' . Date::toShort($tr_row['training_end_date']);
                                                        echo $tr_row['start_time'] != '' ? '<br>[' . Date::to($tr_row['start_time'], Date::HM) . ' - ' . Date::to($tr_row['end_time'], Date::HM) . ']' : '';
                                                        echo '</td>';
                                                        echo '<td>' . $tr_row['duration'] . '</td>';
                                                        echo '<td>' . $tr_row['venue'] . '</td>';
                                                        echo '<td align="center">' . $tr_row['capacity'] . '</td>';
                                                        $v1 = intval($tr_row['capacity']);
                                                        $v2 = intval($tr_row['assigned']);
                                                        $v3 = $v1-$v2;
                                                        echo '<td align="center">' . $v3 . '</td>';
                                                        echo $tr_row['no_imi'] > 0 ? '<td align="center" class="bg-danger">' . $tr_row['no_imi'] . '</td>' : '<td align="center">' . $tr_row['no_imi'] . '</td>';
							echo $tr_row['no_ni'] > 0 ? '<td align="center" class="bg-danger">' . $tr_row['no_ni'] . '</td>' : '<td align="center">' . $tr_row['no_ni'] . '</td>';
                                                        echo '</tr>';
                                                    }
                                                }
                                                ?>
                                            </table>
                                        </div>
                                        <div class="tab-pane" id="tabL4Dates">
                                            <table class="table table-bordered table-condensed">
                                                <tr class="bg-gray">
                                                    <th>Level</th><th>Dates</th><th>Duration</th><th>Venue</th><th>Capacity</th><th>Remaining</th><th class="small">No IMI Code</th><th class="small">No NI</th>
                                                </tr>
                                                <?php
                                                $l4_sql = <<<SQL
SELECT
  crm_training_schedule.*,
  (SELECT COUNT(*) FROM training WHERE training.`schedule_id` = crm_training_schedule.`id`) AS assigned,
  (SELECT COUNT(*) FROM training INNER JOIN users ON training.`learner_id` = users.id WHERE training.`schedule_id` = crm_training_schedule.`id` 
            AND users.`type` = 5 AND users.`imi_redeem_code` IS NULL) AS no_imi,
  (SELECT COUNT(*) FROM training INNER JOIN users ON training.`learner_id` = users.id WHERE training.`schedule_id` = crm_training_schedule.`id` 
            AND users.`type` = 5 AND users.`ni` IS NULL) AS no_ni
FROM
  crm_training_schedule
WHERE LEVEL = 'L4'
  #AND training_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 24 WEEK)
  AND training_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
ORDER BY training_date
LIMIT 10;
SQL;
                                                $training_dates_records = DAO::getResultset($link, $l4_sql, DAO::FETCH_ASSOC);
                                                if(count($training_dates_records) == 0)
                                                {
                                                    echo '<tr><td colspan="6"><i>No level 4 training dates have been found.</i></td></tr>';
                                                }
                                                else
                                                {
                                                    foreach($training_dates_records AS $tr_row)
                                                    {
                                                        echo HTML::viewrow_opening_tag('do.php?_action=view_edit_crm_schedule&id='.$tr_row['id']);
                                                        echo '<td>' . $tr_row['level'] . '</td>';
                                                        echo '<td>';
                                                        echo Date::toShort($tr_row['training_date']) . ' - ' . Date::toShort($tr_row['training_end_date']);
                                                        echo $tr_row['start_time'] != '' ? '<br>[' . Date::to($tr_row['start_time'], Date::HM) . ' - ' . Date::to($tr_row['end_time'], Date::HM) . ']' : '';
                                                        echo '</td>';
                                                        echo '<td>' . $tr_row['duration'] . '</td>';
                                                        echo '<td>' . $tr_row['venue'] . '</td>';
                                                        echo '<td align="center">' . $tr_row['capacity'] . '</td>';
                                                        $v1 = intval($tr_row['capacity']);
                                                        $v2 = intval($tr_row['assigned']);
                                                        $v3 = $v1-$v2;
                                                        echo '<td align="center">' . $v3 . '</td>';
                                                        #echo '<td align="center">' . $tr_row['no_imi'] . '</td>';
                                                        echo $tr_row['no_imi'] > 0 ? '<td align="center" class="bg-danger">' . $tr_row['no_imi'] . '</td>' : '<td align="center">' . $tr_row['no_imi'] . '</td>';
                                                        echo $tr_row['no_ni'] > 0 ? '<td align="center" class="bg-danger">' . $tr_row['no_ni'] . '</td>' : '<td align="center">' . $tr_row['no_ni'] . '</td>';
                                                        echo '</tr>';
                                                    }
                                                }
                                                ?>
                                            </table>
                                        </div> <!-- tabL4Dates -->
                                        
                                        <div class="tab-pane" id="tabL2Dates">
                                            <table class="table table-bordered table-condensed">
                                                <tr class="bg-gray">
                                                    <th>Level</th><th>Dates</th><th>Duration</th><th>Venue</th><th>Capacity</th><th>Remaining</th><th class="small">No IMI Code</th><th class="small">No NI</th>
                                                </tr>
                                                <?php
                                                $l2_sql = <<<SQL
SELECT
  crm_training_schedule.*,
  (SELECT COUNT(*) FROM training WHERE training.`schedule_id` = crm_training_schedule.`id`) AS assigned,
  (SELECT COUNT(*) FROM training INNER JOIN users ON training.`learner_id` = users.id WHERE training.`schedule_id` = crm_training_schedule.`id` 
            AND users.`type` = 5 AND users.`imi_redeem_code` IS NULL) AS no_imi,
  (SELECT COUNT(*) FROM training INNER JOIN users ON training.`learner_id` = users.id WHERE training.`schedule_id` = crm_training_schedule.`id` 
            AND users.`type` = 5 AND users.`ni` IS NULL) AS no_ni
FROM
  crm_training_schedule
WHERE LEVEL = 'L2'
  #AND training_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 24 WEEK)
  AND training_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
ORDER BY training_date
LIMIT 10;
SQL;
                                                $training_dates_records = DAO::getResultset($link, $l2_sql, DAO::FETCH_ASSOC);
                                                if(count($training_dates_records) == 0)
                                                {
                                                    echo '<tr><td colspan="6"><i>No level 2 training dates have been found.</i></td></tr>';
                                                }
                                                else
                                                {
                                                    foreach($training_dates_records AS $tr_row)
                                                    {
                                                        echo HTML::viewrow_opening_tag('do.php?_action=view_edit_crm_schedule&id='.$tr_row['id']);
                                                        echo '<td>' . $tr_row['level'] . '</td>';
                                                        echo '<td>';
                                                        echo Date::toShort($tr_row['training_date']) . ' - ' . Date::toShort($tr_row['training_end_date']);
                                                        echo $tr_row['start_time'] != '' ? '<br>[' . Date::to($tr_row['start_time'], Date::HM) . ' - ' . Date::to($tr_row['end_time'], Date::HM) . ']' : '';
                                                        echo '</td>';
                                                        echo '<td>' . $tr_row['duration'] . '</td>';
                                                        echo '<td>' . $tr_row['venue'] . '</td>';
                                                        echo '<td align="center">' . $tr_row['capacity'] . '</td>';
                                                        $v1 = intval($tr_row['capacity']);
                                                        $v2 = intval($tr_row['assigned']);
                                                        $v3 = $v1-$v2;
                                                        echo '<td align="center">' . $v3 . '</td>';
                                                        #echo '<td align="center">' . $tr_row['no_imi'] . '</td>';
                                                        echo $tr_row['no_imi'] > 0 ? '<td align="center" class="bg-danger">' . $tr_row['no_imi'] . '</td>' : '<td align="center">' . $tr_row['no_imi'] . '</td>';
                                                        echo $tr_row['no_ni'] > 0 ? '<td align="center" class="bg-danger">' . $tr_row['no_ni'] . '</td>' : '<td align="center">' . $tr_row['no_ni'] . '</td>';
                                                        echo '</tr>';
                                                    }
                                                }
                                                ?>
                                            </table>
                                        </div> <!-- tabL2Dates -->

                                        <div class="tab-pane" id="tabL1Dates">
                                            <table class="table table-bordered table-condensed">
                                                <tr class="bg-gray">
                                                    <th>Level</th><th>Dates</th><th>Duration</th><th>Venue</th><th>Capacity</th><th>Remaining</th><th class="small">No IMI Code</th><th class="small">No NI</th>
                                                </tr>
                                                <?php
                                                $l1_sql = <<<SQL
SELECT
  crm_training_schedule.*,
  (SELECT COUNT(*) FROM training WHERE training.`schedule_id` = crm_training_schedule.`id`) AS assigned,
  (SELECT COUNT(*) FROM training INNER JOIN users ON training.`learner_id` = users.id WHERE training.`schedule_id` = crm_training_schedule.`id` 
            AND users.`type` = 5 AND users.`imi_redeem_code` IS NULL) AS no_imi,
  (SELECT COUNT(*) FROM training INNER JOIN users ON training.`learner_id` = users.id WHERE training.`schedule_id` = crm_training_schedule.`id` 
            AND users.`type` = 5 AND users.`ni` IS NULL) AS no_ni
FROM
  crm_training_schedule
WHERE LEVEL = 'L1'
  #AND training_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 24 WEEK)
  AND training_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
ORDER BY training_date
LIMIT 10;
SQL;
                                                $training_dates_records = DAO::getResultset($link, $l1_sql, DAO::FETCH_ASSOC);
                                                if(count($training_dates_records) == 0)
                                                {
                                                    echo '<tr><td colspan="6"><i>No level 1 training dates have been found.</i></td></tr>';
                                                }
                                                else
                                                {
                                                    foreach($training_dates_records AS $tr_row)
                                                    {
                                                        echo HTML::viewrow_opening_tag('do.php?_action=view_edit_crm_schedule&id='.$tr_row['id']);
                                                        echo '<td>' . $tr_row['level'] . '</td>';
                                                        echo '<td>';
                                                        echo Date::toShort($tr_row['training_date']) . ' - ' . Date::toShort($tr_row['training_end_date']);
                                                        echo $tr_row['start_time'] != '' ? '<br>[' . Date::to($tr_row['start_time'], Date::HM) . ' - ' . Date::to($tr_row['end_time'], Date::HM) . ']' : '';
                                                        echo '</td>';
                                                        echo '<td>' . $tr_row['duration'] . '</td>';
                                                        echo '<td>' . $tr_row['venue'] . '</td>';
                                                        echo '<td align="center">' . $tr_row['capacity'] . '</td>';
                                                        $v1 = intval($tr_row['capacity']);
                                                        $v2 = intval($tr_row['assigned']);
                                                        $v3 = $v1-$v2;
                                                        echo '<td align="center">' . $v3 . '</td>';
                                                        #echo '<td align="center">' . $tr_row['no_imi'] . '</td>';
                                                        echo $tr_row['no_imi'] > 0 ? '<td align="center" class="bg-danger">' . $tr_row['no_imi'] . '</td>' : '<td align="center">' . $tr_row['no_imi'] . '</td>';
                                                        echo $tr_row['no_ni'] > 0 ? '<td align="center" class="bg-danger">' . $tr_row['no_ni'] . '</td>' : '<td align="center">' . $tr_row['no_ni'] . '</td>';
                                                        echo '</tr>';
                                                    }
                                                }
                                                ?>
                                            </table>
                                        </div> <!-- tabL1Dates -->

                                        <div class="tab-pane" id="tabML3Dates">
                                            <table class="table table-bordered table-condensed">
                                                <tr class="bg-gray">
                                                    <th>Level</th><th>Dates</th><th>Duration</th><th>Venue</th><th>Capacity</th><th>Remaining</th><th class="small">No IMI Code</th><th class="small">No NI</th>
                                                </tr>
                                                <?php
                                                $lml3_sql = <<<SQL
SELECT
  crm_training_schedule.*,
  (SELECT COUNT(*) FROM training WHERE training.`schedule_id` = crm_training_schedule.`id`) AS assigned,
  (SELECT COUNT(*) FROM training INNER JOIN users ON training.`learner_id` = users.id WHERE training.`schedule_id` = crm_training_schedule.`id` 
            AND users.`type` = 5 AND users.`imi_redeem_code` IS NULL) AS no_imi,
  (SELECT COUNT(*) FROM training INNER JOIN users ON training.`learner_id` = users.id WHERE training.`schedule_id` = crm_training_schedule.`id` 
            AND users.`type` = 5 AND users.`ni` IS NULL) AS no_ni
FROM
  crm_training_schedule
WHERE LEVEL = 'ML3'
  #AND training_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 24 WEEK)
  AND training_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
ORDER BY training_date
LIMIT 10;
SQL;
                                                $training_dates_records = DAO::getResultset($link, $lml3_sql, DAO::FETCH_ASSOC);
                                                if(count($training_dates_records) == 0)
                                                {
                                                    echo '<tr><td colspan="6"><i>No MAN Level 3 training dates have been found.</i></td></tr>';
                                                }
                                                else
                                                {
                                                    foreach($training_dates_records AS $tr_row)
                                                    {
                                                        echo HTML::viewrow_opening_tag('do.php?_action=view_edit_crm_schedule&id='.$tr_row['id']);
                                                        echo '<td>' . $tr_row['level'] . '</td>';
                                                        echo '<td>';
                                                        echo Date::toShort($tr_row['training_date']) . ' - ' . Date::toShort($tr_row['training_end_date']);
                                                        echo $tr_row['start_time'] != '' ? '<br>[' . Date::to($tr_row['start_time'], Date::HM) . ' - ' . Date::to($tr_row['end_time'], Date::HM) . ']' : '';
                                                        echo '</td>';
                                                        echo '<td>' . $tr_row['duration'] . '</td>';
                                                        echo '<td>' . $tr_row['venue'] . '</td>';
                                                        echo '<td align="center">' . $tr_row['capacity'] . '</td>';
                                                        $v1 = intval($tr_row['capacity']);
                                                        $v2 = intval($tr_row['assigned']);
                                                        $v3 = $v1-$v2;
                                                        echo '<td align="center">' . $v3 . '</td>';
                                                        #echo '<td align="center">' . $tr_row['no_imi'] . '</td>';
                                                        echo $tr_row['no_imi'] > 0 ? '<td align="center" class="bg-danger">' . $tr_row['no_imi'] . '</td>' : '<td align="center">' . $tr_row['no_imi'] . '</td>';
                                                        echo $tr_row['no_ni'] > 0 ? '<td align="center" class="bg-danger">' . $tr_row['no_ni'] . '</td>' : '<td align="center">' . $tr_row['no_ni'] . '</td>';
                                                        echo '</tr>';
                                                    }
                                                }
                                                ?>
                                            </table>
                                        </div> <!-- tabML3Dates -->

                                        <div class="tab-pane" id="tabFGDates">
                                            <table class="table table-bordered table-condensed">
                                                <tr class="bg-gray">
                                                    <th>Level</th><th>Dates</th><th>Duration</th><th>Venue</th><th>Capacity</th><th>Remaining</th><th class="small">No IMI Code</th><th class="small">No NI</th>
                                                </tr>
                                                <?php
                                                $lfg_sql = <<<SQL
SELECT
  crm_training_schedule.*,
  (SELECT COUNT(*) FROM training WHERE training.`schedule_id` = crm_training_schedule.`id`) AS assigned,
  (SELECT COUNT(*) FROM training INNER JOIN users ON training.`learner_id` = users.id WHERE training.`schedule_id` = crm_training_schedule.`id` 
            AND users.`type` = 5 AND users.`imi_redeem_code` IS NULL) AS no_imi,
  (SELECT COUNT(*) FROM training INNER JOIN users ON training.`learner_id` = users.id WHERE training.`schedule_id` = crm_training_schedule.`id` 
            AND users.`type` = 5 AND users.`ni` IS NULL) AS no_ni
FROM
  crm_training_schedule
WHERE LEVEL = 'FG'
  #AND training_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 24 WEEK)
  AND training_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
ORDER BY training_date
LIMIT 10;
SQL;
                                                $training_dates_records = DAO::getResultset($link, $lfg_sql, DAO::FETCH_ASSOC);
                                                if(count($training_dates_records) == 0)
                                                {
                                                    echo '<tr><td colspan="6"><i>No FS-Gas training dates have been found.</i></td></tr>';
                                                }
                                                else
                                                {
                                                    foreach($training_dates_records AS $tr_row)
                                                    {
                                                        echo HTML::viewrow_opening_tag('do.php?_action=view_edit_crm_schedule&id='.$tr_row['id']);
                                                        echo '<td>' . $tr_row['level'] . '</td>';
                                                        echo '<td>';
                                                        echo Date::toShort($tr_row['training_date']) . ' - ' . Date::toShort($tr_row['training_end_date']);
                                                        echo $tr_row['start_time'] != '' ? '<br>[' . Date::to($tr_row['start_time'], Date::HM) . ' - ' . Date::to($tr_row['end_time'], Date::HM) . ']' : '';
                                                        echo '</td>';
                                                        echo '<td>' . $tr_row['duration'] . '</td>';
                                                        echo '<td>' . $tr_row['venue'] . '</td>';
                                                        echo '<td align="center">' . $tr_row['capacity'] . '</td>';
                                                        $v1 = intval($tr_row['capacity']);
                                                        $v2 = intval($tr_row['assigned']);
                                                        $v3 = $v1-$v2;
                                                        echo '<td align="center">' . $v3 . '</td>';
                                                        #echo '<td align="center">' . $tr_row['no_imi'] . '</td>';
                                                        echo $tr_row['no_imi'] > 0 ? '<td align="center" class="bg-danger">' . $tr_row['no_imi'] . '</td>' : '<td align="center">' . $tr_row['no_imi'] . '</td>';
                                                        echo $tr_row['no_ni'] > 0 ? '<td align="center" class="bg-danger">' . $tr_row['no_ni'] . '</td>' : '<td align="center">' . $tr_row['no_ni'] . '</td>';
                                                        echo '</tr>';
                                                    }
                                                }
                                                ?>
                                            </table>
                                        </div> <!-- tabFGDates -->

                                        <div class="tab-pane" id="tabADASL1Dates">
                                            <table class="table table-bordered table-condensed">
                                                <tr class="bg-gray">
                                                    <th>Level</th><th>Dates</th><th>Duration</th><th>Venue</th><th>Capacity</th><th>Remaining</th><th class="small">No IMI Code</th><th class="small">No NI</th>
                                                </tr>
                                                <?php
                                                $lfg_sql = <<<SQL
SELECT
  crm_training_schedule.*,
  (SELECT COUNT(*) FROM training WHERE training.`schedule_id` = crm_training_schedule.`id`) AS assigned,
  (SELECT COUNT(*) FROM training INNER JOIN users ON training.`learner_id` = users.id WHERE training.`schedule_id` = crm_training_schedule.`id` 
            AND users.`type` = 5 AND users.`imi_redeem_code` IS NULL) AS no_imi,
  (SELECT COUNT(*) FROM training INNER JOIN users ON training.`learner_id` = users.id WHERE training.`schedule_id` = crm_training_schedule.`id` 
            AND users.`type` = 5 AND users.`ni` IS NULL) AS no_ni
FROM
  crm_training_schedule
WHERE LEVEL = 'ADASL1'
  #AND training_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 24 WEEK)
  AND training_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
ORDER BY training_date
LIMIT 10;
SQL;
                                                $training_dates_records = DAO::getResultset($link, $lfg_sql, DAO::FETCH_ASSOC);
                                                if(count($training_dates_records) == 0)
                                                {
                                                    echo '<tr><td colspan="6"><i>No ADAS L1 training dates have been found.</i></td></tr>';
                                                }
                                                else
                                                {
                                                    foreach($training_dates_records AS $tr_row)
                                                    {
                                                        echo HTML::viewrow_opening_tag('do.php?_action=view_edit_crm_schedule&id='.$tr_row['id']);
                                                        echo '<td>' . $tr_row['level'] . '</td>';
                                                        echo '<td>';
                                                        echo Date::toShort($tr_row['training_date']) . ' - ' . Date::toShort($tr_row['training_end_date']);
                                                        echo $tr_row['start_time'] != '' ? '<br>[' . Date::to($tr_row['start_time'], Date::HM) . ' - ' . Date::to($tr_row['end_time'], Date::HM) . ']' : '';
                                                        echo '</td>';
                                                        echo '<td>' . $tr_row['duration'] . '</td>';
                                                        echo '<td>' . $tr_row['venue'] . '</td>';
                                                        echo '<td align="center">' . $tr_row['capacity'] . '</td>';
                                                        $v1 = intval($tr_row['capacity']);
                                                        $v2 = intval($tr_row['assigned']);
                                                        $v3 = $v1-$v2;
                                                        echo '<td align="center">' . $v3 . '</td>';
                                                        #echo '<td align="center">' . $tr_row['no_imi'] . '</td>';
                                                        echo $tr_row['no_imi'] > 0 ? '<td align="center" class="bg-danger">' . $tr_row['no_imi'] . '</td>' : '<td align="center">' . $tr_row['no_imi'] . '</td>';
                                                        echo $tr_row['no_ni'] > 0 ? '<td align="center" class="bg-danger">' . $tr_row['no_ni'] . '</td>' : '<td align="center">' . $tr_row['no_ni'] . '</td>';
                                                        echo '</tr>';
                                                    }
                                                }
                                                ?>
                                            </table>
                                        </div> <!-- tabADASL1Dates -->

                                        <div class="tab-pane" id="tabADASL2Dates">
                                            <table class="table table-bordered table-condensed">
                                                <tr class="bg-gray">
                                                    <th>Level</th><th>Dates</th><th>Duration</th><th>Venue</th><th>Capacity</th><th>Remaining</th><th class="small">No IMI Code</th><th class="small">No NI</th>
                                                </tr>
                                                <?php
                                                $lfg_sql = <<<SQL
SELECT
  crm_training_schedule.*,
  (SELECT COUNT(*) FROM training WHERE training.`schedule_id` = crm_training_schedule.`id`) AS assigned,
  (SELECT COUNT(*) FROM training INNER JOIN users ON training.`learner_id` = users.id WHERE training.`schedule_id` = crm_training_schedule.`id` 
            AND users.`type` = 5 AND users.`imi_redeem_code` IS NULL) AS no_imi,
  (SELECT COUNT(*) FROM training INNER JOIN users ON training.`learner_id` = users.id WHERE training.`schedule_id` = crm_training_schedule.`id` 
            AND users.`type` = 5 AND users.`ni` IS NULL) AS no_ni
FROM
  crm_training_schedule
WHERE LEVEL = 'ADASL2'
  #AND training_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 24 WEEK)
  AND training_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
ORDER BY training_date
LIMIT 10;
SQL;
                                                $training_dates_records = DAO::getResultset($link, $lfg_sql, DAO::FETCH_ASSOC);
                                                if(count($training_dates_records) == 0)
                                                {
                                                    echo '<tr><td colspan="6"><i>No ADAS L2 training dates have been found.</i></td></tr>';
                                                }
                                                else
                                                {
                                                    foreach($training_dates_records AS $tr_row)
                                                    {
                                                        echo HTML::viewrow_opening_tag('do.php?_action=view_edit_crm_schedule&id='.$tr_row['id']);
                                                        echo '<td>' . $tr_row['level'] . '</td>';
                                                        echo '<td>';
                                                        echo Date::toShort($tr_row['training_date']) . ' - ' . Date::toShort($tr_row['training_end_date']);
                                                        echo $tr_row['start_time'] != '' ? '<br>[' . Date::to($tr_row['start_time'], Date::HM) . ' - ' . Date::to($tr_row['end_time'], Date::HM) . ']' : '';
                                                        echo '</td>';
                                                        echo '<td>' . $tr_row['duration'] . '</td>';
                                                        echo '<td>' . $tr_row['venue'] . '</td>';
                                                        echo '<td align="center">' . $tr_row['capacity'] . '</td>';
                                                        $v1 = intval($tr_row['capacity']);
                                                        $v2 = intval($tr_row['assigned']);
                                                        $v3 = $v1-$v2;
                                                        echo '<td align="center">' . $v3 . '</td>';
                                                        #echo '<td align="center">' . $tr_row['no_imi'] . '</td>';
                                                        echo $tr_row['no_imi'] > 0 ? '<td align="center" class="bg-danger">' . $tr_row['no_imi'] . '</td>' : '<td align="center">' . $tr_row['no_imi'] . '</td>';
                                                        echo $tr_row['no_ni'] > 0 ? '<td align="center" class="bg-danger">' . $tr_row['no_ni'] . '</td>' : '<td align="center">' . $tr_row['no_ni'] . '</td>';
                                                        echo '</tr>';
                                                    }
                                                }
                                                ?>
                                            </table>
                                        </div> <!-- tabADASL2Dates -->

                                        <div class="tab-pane" id="tabADASL3Dates">
                                            <table class="table table-bordered table-condensed">
                                                <tr class="bg-gray">
                                                    <th>Level</th><th>Dates</th><th>Duration</th><th>Venue</th><th>Capacity</th><th>Remaining</th><th class="small">No IMI Code</th><th class="small">No NI</th>
                                                </tr>
                                                <?php
                                                $lfg_sql = <<<SQL
SELECT
  crm_training_schedule.*,
  (SELECT COUNT(*) FROM training WHERE training.`schedule_id` = crm_training_schedule.`id`) AS assigned,
  (SELECT COUNT(*) FROM training INNER JOIN users ON training.`learner_id` = users.id WHERE training.`schedule_id` = crm_training_schedule.`id` 
            AND users.`type` = 5 AND users.`imi_redeem_code` IS NULL) AS no_imi,
  (SELECT COUNT(*) FROM training INNER JOIN users ON training.`learner_id` = users.id WHERE training.`schedule_id` = crm_training_schedule.`id` 
            AND users.`type` = 5 AND users.`ni` IS NULL) AS no_ni
FROM
  crm_training_schedule
WHERE LEVEL = 'ADASL3'
  #AND training_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 24 WEEK)
  AND training_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
ORDER BY training_date
LIMIT 10;
SQL;
                                                $training_dates_records = DAO::getResultset($link, $lfg_sql, DAO::FETCH_ASSOC);
                                                if(count($training_dates_records) == 0)
                                                {
                                                    echo '<tr><td colspan="6"><i>No ADAS L3 training dates have been found.</i></td></tr>';
                                                }
                                                else
                                                {
                                                    foreach($training_dates_records AS $tr_row)
                                                    {
                                                        echo HTML::viewrow_opening_tag('do.php?_action=view_edit_crm_schedule&id='.$tr_row['id']);
                                                        echo '<td>' . $tr_row['level'] . '</td>';
                                                        echo '<td>';
                                                        echo Date::toShort($tr_row['training_date']) . ' - ' . Date::toShort($tr_row['training_end_date']);
                                                        echo $tr_row['start_time'] != '' ? '<br>[' . Date::to($tr_row['start_time'], Date::HM) . ' - ' . Date::to($tr_row['end_time'], Date::HM) . ']' : '';
                                                        echo '</td>';
                                                        echo '<td>' . $tr_row['duration'] . '</td>';
                                                        echo '<td>' . $tr_row['venue'] . '</td>';
                                                        echo '<td align="center">' . $tr_row['capacity'] . '</td>';
                                                        $v1 = intval($tr_row['capacity']);
                                                        $v2 = intval($tr_row['assigned']);
                                                        $v3 = $v1-$v2;
                                                        echo '<td align="center">' . $v3 . '</td>';
                                                        #echo '<td align="center">' . $tr_row['no_imi'] . '</td>';
                                                        echo $tr_row['no_imi'] > 0 ? '<td align="center" class="bg-danger">' . $tr_row['no_imi'] . '</td>' : '<td align="center">' . $tr_row['no_imi'] . '</td>';
                                                        echo $tr_row['no_ni'] > 0 ? '<td align="center" class="bg-danger">' . $tr_row['no_ni'] . '</td>' : '<td align="center">' . $tr_row['no_ni'] . '</td>';
                                                        echo '</tr>';
                                                    }
                                                }
                                                ?>
                                            </table>
                                        </div> <!-- tabADASL3Dates -->

                                        <div class="tab-pane" id="tabLVDTDates">
                                            <table class="table table-bordered table-condensed">
                                                <tr class="bg-gray">
                                                    <th>Level</th><th>Dates</th><th>Duration</th><th>Venue</th><th>Capacity</th><th>Remaining</th><th class="small">No IMI Code</th><th class="small">No NI</th>
                                                </tr>
                                                <?php
                                                $lfg_sql = <<<SQL
SELECT
  crm_training_schedule.*,
  (SELECT COUNT(*) FROM training WHERE training.`schedule_id` = crm_training_schedule.`id`) AS assigned,
  (SELECT COUNT(*) FROM training INNER JOIN users ON training.`learner_id` = users.id WHERE training.`schedule_id` = crm_training_schedule.`id` 
            AND users.`type` = 5 AND users.`imi_redeem_code` IS NULL) AS no_imi,
  (SELECT COUNT(*) FROM training INNER JOIN users ON training.`learner_id` = users.id WHERE training.`schedule_id` = crm_training_schedule.`id` 
            AND users.`type` = 5 AND users.`ni` IS NULL) AS no_ni
FROM
  crm_training_schedule
WHERE LEVEL = 'LVDT'
  #AND training_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 24 WEEK)
  AND training_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
ORDER BY training_date
LIMIT 10;
SQL;
                                                $training_dates_records = DAO::getResultset($link, $lfg_sql, DAO::FETCH_ASSOC);
                                                if(count($training_dates_records) == 0)
                                                {
                                                    echo '<tr><td colspan="6"><i>No LVDT training dates have been found.</i></td></tr>';
                                                }
                                                else
                                                {
                                                    foreach($training_dates_records AS $tr_row)
                                                    {
                                                        echo HTML::viewrow_opening_tag('do.php?_action=view_edit_crm_schedule&id='.$tr_row['id']);
                                                        echo '<td>' . $tr_row['level'] . '</td>';
                                                        echo '<td>';
                                                        echo Date::toShort($tr_row['training_date']) . ' - ' . Date::toShort($tr_row['training_end_date']);
                                                        echo $tr_row['start_time'] != '' ? '<br>[' . Date::to($tr_row['start_time'], Date::HM) . ' - ' . Date::to($tr_row['end_time'], Date::HM) . ']' : '';
                                                        echo '</td>';
                                                        echo '<td>' . $tr_row['duration'] . '</td>';
                                                        echo '<td>' . $tr_row['venue'] . '</td>';
                                                        echo '<td align="center">' . $tr_row['capacity'] . '</td>';
                                                        $v1 = intval($tr_row['capacity']);
                                                        $v2 = intval($tr_row['assigned']);
                                                        $v3 = $v1-$v2;
                                                        echo '<td align="center">' . $v3 . '</td>';
                                                        #echo '<td align="center">' . $tr_row['no_imi'] . '</td>';
                                                        echo $tr_row['no_imi'] > 0 ? '<td align="center" class="bg-danger">' . $tr_row['no_imi'] . '</td>' : '<td align="center">' . $tr_row['no_imi'] . '</td>';
                                                        echo $tr_row['no_ni'] > 0 ? '<td align="center" class="bg-danger">' . $tr_row['no_ni'] . '</td>' : '<td align="center">' . $tr_row['no_ni'] . '</td>';
                                                        echo '</tr>';
                                                    }
                                                }
                                                ?>
                                            </table>
                                        </div> <!-- tabLVDTDates -->

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
		            <div class="box box-solid box-info">
                        <div class="box-header with-border"><h3 class="box-title"> Postcode Checker</h3></div>
                        <div class="box-body">
                            <form role="form" class="form-vertical" name="frmPostcodeChecker" 
                                    action="do.php" method="post">
                                <div class="form-group">
                                    <label for="txtSearchEmployer">Postcode:</label>
                                    <input type="text" class="form-control" name="txtPostcode" placeholder="Enter postcode to check" maxlength="12" required style="text-transform: uppercase;">
                                    <span id="lblInvalidPostcode" class="text-red" style="display: none;">Invalid postcode according to the lookup</span>
                                    <span id="lblValidPostcode" class="text-green" style="display: none;">Valid postcode according to the lookup</span>
                                </div>
                                <div class="form-group">
                                    <span class="btn btn-xs btn-info pull-right" onclick="postcodeChecker();"><i class="fa fa-check"></i> Press to check</span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="box box-info box-solid">
                        <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-calendar"></i> Diary</h3>
                            <div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
                        </div>
                        <div class="box-body no-padding">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
	<?php } else {?>
            <section class="content">
                <div class="row">
                    <div class="col-sm-6">
                        <table class="table table-bordered table-condensed">
                            <caption class="text-bold">Learners</caption>
                            <tr class="bg-gray"><th>Status</th><th>Count</th></tr>
                            <?php
                            $stat_learner_rows = '';
                            $view = ViewLearnersV2::getInstance($link);
                            $q = [
                                'ViewLearnersV2_'.View::KEY_PAGE_SIZE => 0, // No limit
                                'ViewLearnersV2_filter_psa_learner' => 2,
                                '_reset' => 1,
                            ];
                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 1]);
                            $view->refresh($link, $qs);
                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                            $stat_learner_rows .= '<td>New</td><td align="center">' . $view->getRowCount() . '</td>';
                            $stat_learner_rows .= '</tr>';

                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 2]);
                            $view->refresh($link, $qs);
                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                            $stat_learner_rows .= '<td>Booked</td><td align="center">' . DAO::getSingleValue($link, "SELECT COUNT(*) FROM training INNER JOIN crm_training_schedule ON training.schedule_id = crm_training_schedule.id WHERE crm_training_schedule.`venue` = 'Peterborough Skills Academy' AND training.learner_id IN (SELECT id FROM users)") . '</td>';
                            $stat_learner_rows .= '</tr>';

                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 6]);
                            $view->refresh($link, $qs);
                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                            $stat_learner_rows .= '<td>Booked Level 3</td><td align="center">' . DAO::getSingleValue($link, "SELECT COUNT(*) FROM training INNER JOIN crm_training_schedule ON training.schedule_id = crm_training_schedule.id WHERE crm_training_schedule.`venue` = 'Peterborough Skills Academy' AND crm_training_schedule.level = 'L3' AND training.learner_id IN (SELECT id FROM users)") . '</td>';
                            $stat_learner_rows .= '</tr>';

                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 7]);
                            $view->refresh($link, $qs);
                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                            $stat_learner_rows .= '<td>Booked Level 4</td><td align="center">' . DAO::getSingleValue($link, "SELECT COUNT(*) FROM training INNER JOIN crm_training_schedule ON training.schedule_id = crm_training_schedule.id WHERE crm_training_schedule.`venue` = 'Peterborough Skills Academy' AND crm_training_schedule.level = 'L4' AND training.learner_id IN (SELECT id FROM users)") . '</td>';
                            $stat_learner_rows .= '</tr>';

                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 2, 'ViewLearnersV2_filter_postcode_status' => 1]);
                            $view->refresh($link, $qs);
                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                            $stat_learner_rows .= '<td>Booked with <span class="text-success">valid</span> postcode</td><td align="center">' . $view->getRowCount() . '</td>';
                            $stat_learner_rows .= '</tr>';

                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 8, 'ViewLearnersV2_filter_postcode_status' => 2]);
                            $view->refresh($link, $qs);
                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                            $stat_learner_rows .= '<td>Booked with <span class="text-danger">invalid</span> postcode</td><td align="center">' . $view->getRowCount() . '</td>';
                            $stat_learner_rows .= '</tr>';

                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 3]);
                            $view->refresh($link, $qs);
                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                            $stat_learner_rows .= '<td>Level 3 <span class="text-success">Completed</span></td><td align="center">' . $view->getRowCount() . '</td>';
                            $stat_learner_rows .= '</tr>';

                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 4]);
                            $view->refresh($link, $qs);
                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                            $stat_learner_rows .= '<td>Level 4 <span class="text-success">Completed</span></td><td align="center">' . $view->getRowCount() . '</td>';
                            $stat_learner_rows .= '</tr>';

                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 5]);
                            $view->refresh($link, $qs);
                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                            $stat_learner_rows .= '<td>Level 3 <span class="text-success">Completed</span> and not Level 4</td><td align="center">' . $view->getRowCount() . '</td>';
                            $stat_learner_rows .= '</tr>';

                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 8, 'ViewLearnersV2_filter_imi_redeem_code' => 2]);
                            $view->refresh($link, $qs);
                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                            $stat_learner_rows .= '<td>Booked with <span class="text-danger">no</span> IMI code</td><td align="center">' . $view->getRowCount() . '</td>';
                            $stat_learner_rows .= '</tr>';

                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 8, 'ViewLearnersV2_filter_hs_form_status' => 1]);
                            $view->refresh($link, $qs);
                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                            $stat_learner_rows .= '<td>Booked with <span class="text-danger">incomplete</span> HS Form</td><td align="center">' . $view->getRowCount() . '</td>';
                            $stat_learner_rows .= '</tr>';

                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 9]);
                            $view->refresh($link, $qs);
                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                            $stat_learner_rows .= '<td>Level 1</td><td align="center">' . $view->getRowCount() . '</td>';
                            $stat_learner_rows .= '</tr>';

                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 10]);
                            $view->refresh($link, $qs);
                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                            $stat_learner_rows .= '<td>Level 2</td><td align="center">' . $view->getRowCount() . '</td>';
                            $stat_learner_rows .= '</tr>';

                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 11]);
                            $view->refresh($link, $qs);
                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                            $stat_learner_rows .= '<td>Needs rebooking for Level 3</td><td align="center">' . $view->getRowCount() . '</td>';
                            $stat_learner_rows .= '</tr>';

                            $qs = array_merge($q, ['ViewLearnersV2_filter_learner_status' => 12]);
                            $view->refresh($link, $qs);
                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                            $stat_learner_rows .= '<td>Needs rebooking for Level 4</td><td align="center">' . $view->getRowCount() . '</td>';
                            $stat_learner_rows .= '</tr>';

                            $qs = array_merge($q, ['ViewLearnersV2_filter_outstanding_payment' => 2]);
                            $view->refresh($link, $qs);
                            $stat_learner_rows .= HTML::viewrow_opening_tag('do.php?_action=view_learners&'.http_build_query($qs));
                            $stat_learner_rows .= '<td>Outstanding Payment</td><td align="center">' . $view->getRowCount() . '</td>';
                            $stat_learner_rows .= '</tr>';

                            echo $stat_learner_rows;
                            ?>
                        </table>
                    </div>
                    <div class="col-sm-6">
                        <div class="box box-info box-solid">
                            <div class="box-header with-border">Quick Search</div>
                            <div class="box-body" style="height: 400px;">
                                <div class="callout callout-default">
                                    <form role="form" class="form-vertical" name="frmQuickSearchEmployer" id="frmQuickSearchEmployer"
                                        action="do.php?_action=home_page_duplex" method="post">
                                        <input type="hidden" name="subaction" value="quickSearchEmployer" >
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
                                    <form role="form" class="form-vertical" name="frmQuickSearchLearner" id="frmQuickSearchLearner"
                                        action="do.php?_action=home_page_duplex" method="post">
                                        <input type="hidden" name="subaction" value="quickSearchLearner" >
                                        <div class="form-group">
                                            <label for="txtSearchLearnerFirstname">Learner First Name:</label>
                                            <input type="text" class="form-control" name="txtSearchLearnerFirstname" placeholder="Enter learner firstname" >
                                        </div>
                                        <div class="form-group">
                                            <label for="txtSearchLearnerSurname">Learner Surname:</label>
                                            <input type="text" class="form-control" name="txtSearchLearnerSurname" placeholder="Enter learner surname" >
                                        </div>
                                        <div class="form-group">
                                            <button name="quickSearchLearner" type="submit" class="btn btn-xs btn-info pull-right"><i class="fa fa-search"></i> Search Learner</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="box-footer">
                                <span class="btn btn-primary btn-xs" onclick="window.location.href='do.php?_action=edit_employer_v2'">Create New Employer</span> &nbsp; 
                                <span class="btn btn-primary btn-xs" onclick="window.location.href='do.php?_action=edit_learner_duplex'">Create New Learner</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
	    <?php } ?>
    </div>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            Powered by Sunesis &nbsp;|&nbsp;&copy; <?php echo date('Y'); ?> Perspective Ltd
        </div>
        <strong>
            <?php echo date('D, d M Y'); ?>
    </footer>

    <div id="eventContent" title="Event Details" style="display:none;" class="small">
        Next Action Date: <span id="startTime"></span><br>
        Next Action: <span id="next_action_desc"></span><br>
        Detail: <span id="line"></span><br>
	<span id="btn_nav_crm_note"></span><br>
    </div>

</div>

<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>
<script src="/common.js"></script>
<script type="text/javascript" src="/assets/adminlte/plugins/fullcalendar/moment.js"></script>
<script type="text/javascript" src="/assets/adminlte/plugins/fullcalendar/fullcalendar.js"></script>

<script src="https://code.highcharts.com/7.0.0/highcharts.src.js"></script>
<script src="https://code.highcharts.com/7.0.0/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/7.0.0/modules/exporting.js"></script>
<script src="https://code.highcharts.com/7.0.0/modules/no-data-to-display.js"></script>
<script src="module_charts/assets/jsonfn.js"></script>

<script>

    $(function(){

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
            events: 'do.php?_action=ajax_calendar_manager&id=<?php echo $_SESSION['user']->id; ?>&type=crm_action',
            eventRender: function (event, element) {
                element.attr('href', 'javascript:void(0);');
                element.click(function() {
                    $("#startTime").html(moment(event.start).format('MMM Do h:mm A'));
                    $("#next_action_desc").html(event.by_whom);
                    $("#line").html(event.line);
		            $("#btn_nav_crm_note").html('<span class="btn btn-xs btn-info" onclick="window.location.href=\''+event.nav_to_crm_detail+'\'"><i class="fa fa-folder-open"></i> Detail</span>');
                    $("#eventContent").dialog({
                        modal: true,
                        title: event.title,
                        width:500,
                        draggable: false,
                        buttons:{
                            "Close":function () {
                                $(this).dialog("close");
                            }
                        }
                    });
                });
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


    });

    function postcodeChecker()
    {
        $('#lblInvalidPostcode').hide();
        $('#lblValidPostcode').hide();

        var frmPostcodeChecker = document.forms["frmPostcodeChecker"];
        var txtPostcode = frmPostcodeChecker.txtPostcode.value.trim();

        if(txtPostcode != '')
        {
            if(!validatePostcode(txtPostcode))
            {
                alert("Please enter a valid postcode");
                return false;
            }
            var url = 'do.php?_action=edit_learner_duplex&subaction=validatePostcodeInLookup'
                + "&home_postcode=" + encodeURIComponent(txtPostcode)
            var client = ajaxRequest(url);
            if(client.responseText == '0')
            {
                $('#lblInvalidPostcode').show();
                $('#lblValidPostcode').hide();
            }
            if(client.responseText == '1')
            {
                $('#lblInvalidPostcode').hide();
                $('#lblValidPostcode').show();
            }
        }
    }

</script>
</body>
</html>

<?php unset($_SESSION['view_ViewLearnersV2']); ?>