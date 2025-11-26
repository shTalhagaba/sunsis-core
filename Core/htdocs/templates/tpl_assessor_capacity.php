
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Assessor Capacity</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>

        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>

</head>

<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Assessor Capacity</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
            </div>
            <div class="ActionIconBar"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>
<p></p>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-info" id="divPanels">
                <div class="box-header with-border">
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <form name="frmEPADates" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" name="_action" value="assessor_capacity" />
                        <input type="hidden" name="recalculate" value="0" />
                        <div class="row well well-sm">
                            <div class="col-sm-1">
                                From
                            </div>
                            <div class="col-sm-2">
                                <input name="fromDate" id="fromDate" class="date-picker" value="<?php echo $fromDate; ?>" />
                            </div>
                            <div class="col-sm-1">
                                To
                            </div>
                            <div class="col-sm-2">
                                <input name="toDate" id="toDate" class="date-picker" value="<?php echo $toDate; ?>" />
                            </div>
                            <div class="col-sm-2"><button onclick="Refresh()" class="btn btn-xs btn-info"><i class="fa fa-refresh"></i> Apply</button></div>
                        </div>
                    </form>

                    <hr>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="box box-success box-solid">
                                <div class="box-header with-border"><h1 class="box-title">Assessor Capacity Report</h1></div>
                                <div class="box-body">
                                    <div class="table-responsive">
                                        <?php
                                        echo '<table class="table table-bordered text-center">';
                                        echo '<thead class="bg-gray"><tr><th>Assessor</th><th>Max Capacity</th><th>Actual Caseload</th><th>Capacity</th><th>On Programme</th><th>Planned Achievers</th><th>Gateway Ready</th><th>Starts</th><th>PEED</th><th>BIL</th></tr></thead>';
                                        echo '<tbody>';

                                        $st = $link->query("SELECT
                                                            CONCAT(firstnames, ' ', surname) AS AssessorName
                                                            ,capacity AS MaxCapacity
                                                            ,(SELECT COUNT(*) FROM tr LEFT JOIN tr_operations ON tr_operations.tr_id = tr.id WHERE status_code = 1 and learner_status != 'PC' and learner_status != 'GR' AND assessor = users.id and start_date <= '$end_date') AS ActualCaseload
                                                            ,(SELECT COUNT(*) FROM tr WHERE status_code = 1 AND assessor = users.id AND start_date <= '$end_date') AS OnProgramme
                                                            ,(SELECT COUNT(*) FROM tr WHERE status_code = 1 AND assessor = users.id AND (SELECT task_actual_date FROM op_epa WHERE op_epa.`tr_id` = tr.id AND op_epa.`task` = '5' AND op_epa.`task_applicable` = 'Y' ORDER BY id DESC LIMIT 1) = NULL AND (SELECT op_epa.task_actual_date FROM op_epa WHERE op_epa.`tr_id` = tr.id AND op_epa.task = '12' ORDER BY id DESC LIMIT 1) BETWEEN '$start_date' AND '$end_date') AS PlannedAchievers
                                                            ,(SELECT COUNT(*) FROM tr LEFT JOIN tr_operations ON tr_operations.tr_id = tr.id WHERE status_code = 1 and learner_status = 'GR' AND assessor = users.id and start_date <= '$end_date') AS GatewayReady
                                                            ,(SELECT COUNT(*) FROM tr WHERE status_code = 1 AND assessor = users.id AND start_date BETWEEN '$start_date' AND '$end_date') AS `Starts`
                                                            ,(SELECT COUNT(*) FROM tr LEFT JOIN tr_operations ON tr_operations.tr_id = tr.id WHERE status_code = 1 and (learner_status = 'PA' OR learner_status = 'PC') AND assessor = users.id) AS PEED
                                                            ,(SELECT COUNT(*) FROM tr LEFT JOIN tr_operations ON tr_operations.tr_id = tr.id WHERE status_code = 6 AND assessor = users.id AND extractvalue(tr_operations.bil_details, '/Notes/Note[last()]/Type') = 'F' ) AS BIL
                                                            FROM users WHERE TYPE = 3 AND web_access = 1 ORDER BY firstnames;");
                                        while($row = $st->fetch())
                                        {
                                            echo '<tr>';
                                            echo '<td>' . $row['AssessorName'] . '</td>';
                                            echo '<td>' . $row['MaxCapacity'] . '</td>';
                                            echo '<td>' . $row['ActualCaseload'] . '</td>';
                                            $capacity = $row['MaxCapacity'] - $row['ActualCaseload'];
                                            echo '<td>' . $capacity . '</td>';
                                            echo '<td>' . $row['OnProgramme'] . '</td>';
                                            echo '<td>' . $row['PlannedAchievers'] . '</td>';
                                            echo '<td>' . $row['GatewayReady'] . '</td>';
                                            echo '<td>' . $row['Starts'] . '</td>';
                                            echo '<td>' . $row['PEED'] . '</td>';
                                            echo '<td>' . $row['BIL'] . '</td>';
                                            echo '</tr>';
                                        }
                                        echo '</tr>';
                                        echo '</tbody>';
                                        echo '</table> ';

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
</div>


<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="frmFilters" name="frmFilters">
    <input type="hidden" name="_action" value="view_epa_dash_learners" />
    <input type="hidden" name="_reset" value="1" />
    <input type="hidden" name="filter_ids" value="" />
</form>

<form name="frmDashPDF" action="do.php?_action=operations_dashboard" method="post">
    <input type="hidden" name="subaction" value="generate_dash_pdf" />
    <input type="hidden" name="html" value="" />
</form>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/plugins/html2canvas/html2canvas.js"></script>
<script src="/assets/adminlte/plugins/FileSaver/FileSaver.js"></script>

<script language="JavaScript">

    function showDetail(ids)
    {
        var frmFilters = document.forms["frmFilters"];
        frmFilters.filter_ids.value = ids;

        frmFilters.submit();
    }

    $(function(){
        $('#manager').on('change', function(){
            showWaitingOverlays();
            var qs = '&fromDate=' + encodeURIComponent($("#fromDate").val()) +
                    '&toDate=' + encodeURIComponent($("#toDate").val());
        });

        $('.date-picker').datepicker( {

            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'dd/mm/yy',

            onSelect: function(dateText) {
                showWaitingOverlays();
                var qs = '&fromDate=' + encodeURIComponent($("#fromDate").val()) +
                        '&toDate=' + encodeURIComponent($("#toDate").val());
            }
        });

    });

    function expor(detail)
    {
        window.location.href='do.php?_action=export_success_rates&trs='+detail;
    }


    function Refresh()
    {
        form = document.forms[0];
        form.submit();
    }

    function calculate()
    {
        form = document.forms[0];
        form.recalculate.value=1;
        form.submit();
    }

</script>

</body>
</html>
<?php
$programmes = null;
$grand_total = null;
$gradesTotals = null;
$assessors = null;
$grand_total_a = null;
$gradesTotals_a = null;
$supervisors = null;
$grand_total_p = null;
$gradesTotals_p = null;

?>