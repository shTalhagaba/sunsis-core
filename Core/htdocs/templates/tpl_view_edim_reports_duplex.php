<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | CRM Dashboard</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/fullcalendar/fullcalendar.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>

    <style>
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <header class="main-header"></header>

        <div class="content-wrapper">
            <section class="content-header">
                <h1><span class="fa fa-dashboard"></span> Dashboard<span class="pull-right"><img class="img-rounded" src="images/logos/SUNlogo.png" height="35px;" /></span></h1>
            </section>

            <section class="content">
                <div class="row" style="display: none;">
                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="box box-primary">
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>?_action=view_edim_reports_suplex" role="form">
                                <input type="hidden" subaction="updateGenderPanel" />

                                <div class="box-header with-border">
                                    <span class="box-title">Learners By Gender</span>
                                </div>
                                <div class="box-header with-border">
                                    From: <?php echo HTML::datebox('sdGender', null); ?> &nbsp;
                                    To: <?php echo HTML::datebox('edGender', null); ?> &nbsp; | &nbsp;
                                    Level 2 <input type="checkbox" name="levelGender[]" value="L2">
                                    Level 3 <input type="checkbox" name="levelGender[]" value="L3">
                                    Level 4 <input type="checkbox" name="levelGender[]" value="L4">
                                    &nbsp;
                                    <span class="btn btn-info btn-xs">Refresh</span>
                                </div>
                                <div class="box-body" id="crmActivitiesPanel">

                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2">

                        <div class="callout callout-default">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Description</th>
                                    <th style="width: 10%;">Count</th>
                                </tr>
                                <tr>
                                    <th>Female</th>
                                    <td class="lead text-center">
                                        <?php
                                        echo '<span style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $females) . '\');">' . count($females) . '</span>';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Male</th>
                                    <td class="lead text-center">
                                        <?php
                                        echo '<span style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $males) . '\');">' . count($males) . '</span>';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Other</th>
                                    <td class="lead text-center">
                                        <?php
                                        echo '<span style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $others) . '\');">' . count($others) . '</span>';
                                        ?>
                                    </td>
                                </tr>
                            </table>
                            <div id="gendersPieChart" style="height: 450px;"></div>
                        </div>

                        <div class="callout callout-default">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Description</th>
                                    <th style="width: 10%;">Count</th>
                                </tr>
                                <tr>
                                    <th>Level 1 Completed</th>
                                    <td class="lead text-center">
                                        <?php
                                        
                                        echo '<span style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $l1comp) . '\');">' . count($l1comp) . '</span>';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Level 2 Completed</th>
                                    <td class="lead text-center">
                                        <?php
                                        
                                        echo '<span style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $l2comp) . '\');">' . count($l2comp) . '</span>';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Level 3 Completed</th>
                                    <td class="lead text-center">
                                        <?php
                                        
                                        echo '<span style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $l3comp) . '\');">' . count($l3comp) . '</span>';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Level 4 Completed</th>
                                    <td class="lead text-center">
                                        <?php
                                        
                                        echo '<span style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $l4comp) . '\');">' . count($l4comp) . '</span>';
                                        ?>
                                    </td>
                                </tr>
                            </table>
                            <div id="completionsBarChart" style="height: 450px;"></div>

                        </div>

                        <div class="callout callout-default">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Description</th>
                                    <th style="width: 10%;">Count</th>
                                </tr>
                                <tr>
                                    <th>Progressed - Level 1 to Level 2</th>
                                    <td class="lead text-center">
                                        <?php
                                        $p12 = DAO::getSingleColumn(
                                            $link,
                                            "SELECT DISTINCT training.`learner_id` FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` 
                                        WHERE crm_training_schedule.`level` = 'L2'
                                        AND training.`learner_id` IN 
                                        (
                                        SELECT training.`learner_id` FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` 
                                        WHERE crm_training_schedule.`level` = 'L1'
                                        AND training.`status` = 2
                                        );"
                                        );
                                        echo '<span style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $p12) . '\');">' . count($p12) . '</span>';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Progressed - Level 2 to Level 3</th>
                                    <td class="lead text-center">
                                        <?php
                                        $p23 = DAO::getSingleColumn(
                                            $link,
                                            "SELECT DISTINCT training.`learner_id` FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` 
                                        WHERE crm_training_schedule.`level` = 'L3'
                                        AND training.`learner_id` IN 
                                        (
                                        SELECT training.`learner_id` FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` 
                                        WHERE crm_training_schedule.`level` = 'L2'
                                        AND training.`status` = 2
                                        );"
                                        );
                                        echo '<span style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $p23) . '\');">' . count($p23) . '</span>';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Progressed - Level 3 to Level 4</th>
                                    <td class="lead text-center">
                                        <?php
                                        $p34 = DAO::getSingleColumn(
                                            $link,
                                            "SELECT DISTINCT training.`learner_id` FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` 
                                        WHERE crm_training_schedule.`level` = 'L4'
                                        AND training.`learner_id` IN 
                                        (
                                        SELECT training.`learner_id` FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` 
                                        WHERE crm_training_schedule.`level` = 'L3'
                                        AND training.`status` = 2
                                        );"
                                        );
                                        echo '<span style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $p34) . '\');">' . count($p34) . '</span>';
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="callout callout-default">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Description</th>
                                    <th style="width: 10%;">Count</th>
                                </tr>
                                <tr>
                                    <th>Number of learners who have, or require the use of, a pacemaker</th>
                                    <td class="lead text-center">
                                        <?php
                                        $s3c1 =  DAO::getSingleColumn(
                                            $link,
                                            "SELECT DISTINCT learner_id FROM crm_learner_hs_form WHERE s3c1 = 1 AND learner_id IN (SELECT id FROM users WHERE users.`type` = 5);"
                                        );
                                        echo '<span style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $s3c1) . '\');">' . count($s3c1) . '</span>';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nnumber of learners who have, or require the use of, an ICD (implantable cardioverter defibrillator)</th>
                                    <td class="lead text-center">
                                        <?php
                                        $s3c4 =  DAO::getSingleColumn(
                                            $link,
                                            "SELECT DISTINCT learner_id FROM crm_learner_hs_form WHERE s3c4 = 1 AND learner_id IN (SELECT id FROM users WHERE users.`type` = 5);"
                                        );
                                        echo '<span style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $s3c4) . '\');">' . count($s3c4) . '</span>';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Number of learners who have, or require the use of, an insulin pump</th>
                                    <td class="lead text-center">
                                        <?php
                                        $s3c5 =  DAO::getSingleColumn(
                                            $link,
                                            "SELECT DISTINCT learner_id FROM crm_learner_hs_form WHERE s3c5 = 1 AND learner_id IN (SELECT id FROM users WHERE users.`type` = 5);"
                                        );
                                        echo '<span style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $s3c5) . '\');">' . count($s3c5) . '</span>';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Number of learners who have a medical condition and/or have had a surgical procedures that would prevent them
                                        from working on or near systems or components containing hazardous voltage and magnetic emissions</th>
                                    <td class="lead text-center">
                                        <?php
                                        $s3c2 =  DAO::getSingleColumn(
                                            $link,
                                            "SELECT DISTINCT learner_id FROM crm_learner_hs_form WHERE s3c2 = 1 AND learner_id IN (SELECT id FROM users WHERE users.`type` = 5);"
                                        );
                                        echo '<span style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $s3c2) . '\');">' . count($s3c2) . '</span>';
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Number of learners who have any learning difficulties that they informed</th>
                                    <td class="lead text-center">
                                        <?php
                                        $s3c6 =  DAO::getSingleColumn(
                                            $link,
                                            "SELECT DISTINCT learner_id FROM crm_learner_hs_form WHERE s3c6 = 1 AND learner_id IN (SELECT id FROM users WHERE users.`type` = 5);"
                                        );
                                        echo '<span style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $s3c6) . '\');">' . count($s3c6) . '</span>';
                                        ?>
                                    </td>
                                </tr>

                            </table>

                        </div>

                    </div>
                </div>
            </section>

        </div>

        <form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="frmFilters" name="frmFilters">
            <input type="hidden" name="_action" value="view_edim_learners" />
            <input type="hidden" name="_reset" value="1" />
            <input type="hidden" name="filter_ids" value="" />
        </form>

    </div>

    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/adminlte/dist/js/app.min.js"></script>
    <script src="/common.js"></script>

    <script src="https://code.highcharts.com/7.0.0/highcharts.src.js"></script>
    <script src="https://code.highcharts.com/7.0.0/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/7.0.0/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/7.0.0/modules/no-data-to-display.js"></script>
    <script src="module_charts/assets/jsonfn.js"></script>

    <script>
        function showDetail(ids) {
            if (ids == '') {
                return;
            }
            var frmFilters = document.forms["frmFilters"];
            frmFilters.filter_ids.value = ids;

            frmFilters.submit();
        }

        $(function() {
            
            var chart = new Highcharts.chart('gendersPieChart', JSONfn.parse(JSON.stringify(<?php echo $gendersPieChart; ?>) ) );
            var chart = new Highcharts.chart('completionsBarChart', JSONfn.parse(JSON.stringify(<?php echo $completionsBarChart; ?>) ) );

        });
    </script>
</body>

</html>