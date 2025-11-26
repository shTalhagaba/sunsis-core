
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Retention Dashboard</title>
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
            <div class="Title" style="margin-left: 6px;">Retention Dashboard</div>
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
    <input type="hidden" name="_action" value="retention_pack" />
    <input type="hidden" name="recalculate" value="0" />
    <div class="row well well-sm">
        <div class="col-sm-12">
                <span class="text-bold">Apprenticeship Title:</span><br>
                <?php echo HTML::checkboxGrid('apprenticeship_title', $programmes_ddl, $apprenticeship_title, 3, true); ?>
        </div>
    </div>
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
        <div class="col-sm-3">Last calculated date/time <?php echo DAO::getSingleValue($link, "select value from configuration where entity = 'Retention'");?></div>
        <div class="col-sm-2"><button onclick="calculate()" class="btn btn-xs btn-info"><i class="fa fa-refresh"></i> Recalculate</button></div>
    </div>
</form>

<hr>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-success box-solid">
            <div class="box-header with-border"><h1 class="box-title">Overall Cohort to date </h1></div>
            <div class="box-body">
                <div class="table-responsive">
                    <?php
                    echo '<table class="table table-bordered text-center">';
                    echo '<thead class="bg-gray"><tr><th>Cohort</th><th>Early Leavers</th><th>Achieved</th><th class="text-red">EPA Fail</th><th>On Programme</th><th>BIL</th><th class="bg-gray">Retention</th><th class="bg-gray">Achievement</th></tr></thead>';
                    echo '<tbody>';
                    echo '<tr>';

                    $cohort = RetentionPack::getCohort($link, $start_date, $end_date, "", $apprenticeship_title);
                    echo '<td class="text-blue" style="cursor: pointer;" onclick="expor(\''.$cohort[0][1].'\');">';
                    echo $cohort[0][0];
                    echo '</td>';

                    $early_leaver = RetentionPack::getEarlyLeaver($link, $start_date, $end_date, "", 0, $apprenticeship_title);
                    echo '<td class="text-blue" style="cursor: pointer;" onclick="expor(\''.$early_leaver[0][1].'\');">';
                    echo $early_leaver[0][0];
                    echo '</td>';

                    $achiever = RetentionPack::getAchievers($link, $start_date, $end_date, "", $apprenticeship_title);
                    echo '<td class="text-blue" style="cursor: pointer;" onclick="expor(\''.$achiever[0][1].'\');">';
                    echo $achiever[0][0];
                    echo '</td>';

                    $epa = RetentionPack::getEPAFail($link, $start_date, $end_date, "", $apprenticeship_title);
                    echo '<td class="text-blue" style="cursor: pointer;" onclick="expor(\''.$epa[0][1].'\');">';
                    echo $epa[0][0];
                    echo '</td>';

                    $on_programme = RetentionPack::getOnProgramme($link, $start_date, $end_date, "", $apprenticeship_title);
                    echo '<td class="text-blue" style="cursor: pointer;" onclick="expor(\''.$on_programme[0][1].'\');">';
                    echo $on_programme[0][0];
                    echo '</td>';

                    $bil = RetentionPack::getBIL($link, $start_date, $end_date, "", $apprenticeship_title);
                    echo '<td class="text-blue" style="cursor: pointer;" onclick="expor(\''.$bil[0][1].'\');">';
                    echo $bil[0][0];
                    echo '</td>';

                    echo '<td class="text-black">';
                    if($cohort[0][0]>0)
                        echo round(100-($early_leaver[0][0]/$cohort[0][0]*100),2)."%";
                    else
                        echo round(100-($early_leaver[0][0]/1*100),2)."%";
                    echo '</td>';

                    echo '<td class="text-black">';
                    if($cohort[0][0]>0)
                        echo round($achiever[0][0]/$cohort[0][0]*100,2)."%";
                    else
                        echo round($achiever[0][0]/1*100,2)."%";
                    echo '</td>';

                    echo '</tr>';
                    echo '</tbody>';
                    echo '</table> ';

                    echo '<table class="table table-bordered text-center">';
                    echo '<thead class="bg-gray"><tr><th>Leaver Profile</th><th>0-4 Weeks</th><th>5-8 Weeks</th><th>9-12 Weeks</th><th>13-16 Weeks</th><th>17-20 Weeks</th><th>21-24 Weeks</th><th>25-28 Weeks</th><th>29-32 Weeks</th><th>33-36 Weeks</th><th>37-40 Weeks</th><th>41-44 Weeks</th><th>45-48 Weeks</th><th>49-52 Weeks</th><th>53+ Weeks</th></tr></thead>';
                    echo '<tbody>';

                    echo '<tr>';
                    echo '<td class="bg-gray"><b>Leavers</b></td>';
                    for($a = 0; $a<=13; $a++)
                    {
                        $leaver = RetentionPack::getEarlyLeaverByWeek($link, $start_date, $end_date, "", $a, $apprenticeship_title);
                        echo '<td class="text-blue" style="cursor: pointer;" onclick="expor(\''.$leaver[0][1].'\');">';
                        echo $leaver[0][0];
                        echo '</td>';
                    }
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td class="bg-gray"><b>% By Period</b></td>';
                    for($a = 0; $a<=13; $a++)
                    {
                        $leaver = RetentionPack::getEarlyLeaverByWeek($link, $start_date, $end_date, "", $a, $apprenticeship_title);
                        echo '<td>';
                        if($early_leaver[0][0]>0)
                            echo round($leaver[0][0]/$early_leaver[0][0]*100,2);
                        else
                            echo round($leaver[0][0]/1*100,2);
                        echo '%</td>';
                    }
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td class="bg-gray"><b>% of Cohort</b></td>';
                    for($a = 0; $a<=13; $a++)
                    {
                        $leaver = RetentionPack::getEarlyLeaverByWeek($link, $start_date, $end_date, "", $a, $apprenticeship_title);
                        echo '<td>';
                        if($cohort[0][0]>0)
                            echo round($leaver[0][0]/$cohort[0][0]*100,2);
                        else
                            echo round($leaver[0][0]/1*100,2);
                        echo '%</td>';
                    }
                    echo '</tr>';

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

<?php
$st = $link->query("SELECT DISTINCT ByStandard FROM retention_data ORDER BY NLevel, StandardCode, framework_code;");
while($row = $st->fetch())
{
    $cohort = RetentionPack::getCohort($link, $start_date, $end_date, $row['ByStandard'], $apprenticeship_title);
    if($cohort[0][0]<1)
        continue;
?>
    <div class="container-fluid">
    <div class="row">
    <div class="col-sm-12">
        <div class="box box-success box-solid">
            <div class="box-header with-border"><h1 class="box-title"><?php echo $row['ByStandard']; ?></h1></div>
            <div class="box-body">
                <div class="table-responsive">
                    <?php
                    echo '<table class="table table-bordered text-center">';
                    echo '<thead class="bg-gray"><tr><th>Cohort</th><th>Early Leavers</th><th>Achieved</th><th class="text-red">EPA Fail</th><th>On Programme</th><th>BIL</th><th class="bg-gray">Retention</th><th class="bg-gray">Achievement</th></tr></thead>';
                    echo '<tbody>';
                    echo '<tr>';

                    $cohort = RetentionPack::getCohort($link, $start_date, $end_date, $row['ByStandard'], $apprenticeship_title);
                    echo '<td class="text-blue" style="cursor: pointer;" onclick="expor(\''.$cohort[0][1].'\');">';
                    echo $cohort[0][0];
                    echo '</td>';

                    $early_leaver = RetentionPack::getEarlyLeaver($link, $start_date, $end_date, $row['ByStandard'], 0, $apprenticeship_title);
                    echo '<td class="text-blue" style="cursor: pointer;" onclick="expor(\''.$early_leaver[0][1].'\');">';
                    echo $early_leaver[0][0];
                    echo '</td>';

                    $achiever = RetentionPack::getAchievers($link, $start_date, $end_date, $row['ByStandard'], $apprenticeship_title);
                    echo '<td class="text-blue" style="cursor: pointer;" onclick="expor(\''.$achiever[0][1].'\');">';
                    echo $achiever[0][0];
                    echo '</td>';

                    $epa = RetentionPack::getEPAFail($link, $start_date, $end_date, $row['ByStandard'], $apprenticeship_title);
                    echo '<td class="text-blue" style="cursor: pointer;" onclick="expor(\''.$epa[0][1].'\');">';
                    echo $epa[0][0];
                    echo '</td>';

                    $on_programme = RetentionPack::getOnProgramme($link, $start_date, $end_date, $row['ByStandard'], $apprenticeship_title);
                    echo '<td class="text-blue" style="cursor: pointer;" onclick="expor(\''.$on_programme[0][1].'\');">';
                    echo $on_programme[0][0];
                    echo '</td>';

                    $bil = RetentionPack::getBIL($link, $start_date, $end_date, $row['ByStandard'], $apprenticeship_title);
                    echo '<td class="text-blue" style="cursor: pointer;" onclick="expor(\''.$bil[0][1].'\');">';
                    echo $bil[0][0];
                    echo '</td>';

                    echo '<td class="text-black">';
                    echo round(100-($early_leaver[0][0]/$cohort[0][0]*100),2)."%";
                    echo '</td>';

                    echo '<td class="text-black">';
                    echo round($achiever[0][0]/$cohort[0][0]*100,2)."%";
                    echo '</td>';

                    echo '</tr>';
                    echo '</tbody>';
                    echo '</table> ';

                    echo '<table class="table table-bordered text-center">';
                    echo '<thead class="bg-gray"><tr><th>Leaver Profile</th><th>0-4 Weeks</th><th>5-8 Weeks</th><th>9-12 Weeks</th><th>13-16 Weeks</th><th>17-20 Weeks</th><th>21-24 Weeks</th><th>25-28 Weeks</th><th>29-32 Weeks</th><th>33-36 Weeks</th><th>37-40 Weeks</th><th>41-44 Weeks</th><th>45-48 Weeks</th><th>49-52 Weeks</th><th>53+ Weeks</th></tr></thead>';
                    echo '<tbody>';

                    echo '<tr>';
                    echo '<td class="bg-gray"><b>Leavers</b></td>';
                    for($a = 0; $a<=13; $a++)
                    {
                        $leaver = RetentionPack::getEarlyLeaverByWeek($link, $start_date, $end_date, $row['ByStandard'], $a, $apprenticeship_title);
                        echo '<td class="text-blue" style="cursor: pointer;" onclick="expor(\''.$leaver[0][1].'\');">';
                        echo $leaver[0][0];
                        echo '</td>';
                    }
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td class="bg-gray"><b>% By Period</b></td>';
                    for($a = 0; $a<=13; $a++)
                    {
                        $leaver = RetentionPack::getEarlyLeaverByWeek($link, $start_date, $end_date, $row['ByStandard'], $a, $apprenticeship_title);
                        echo '<td>';
                        if($early_leaver[0][0]==0)
                            echo '0';
                        else
                            echo round($leaver[0][0]/$early_leaver[0][0]*100,2);
                        echo '%</td>';
                    }
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td class="bg-gray"><b>% of Cohort</b></td>';
                    for($a = 0; $a<=13; $a++)
                    {
                        $leaver = RetentionPack::getEarlyLeaverByWeek($link, $start_date, $end_date, $row['ByStandard'], $a, $apprenticeship_title);
                        echo '<td>';
                        echo round($leaver[0][0]/$cohort[0][0]*100,2);
                        echo '%</td>';
                    }
                    echo '</tr>';

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
<?php
}
?>

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