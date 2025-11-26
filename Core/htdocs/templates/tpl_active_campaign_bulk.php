<?php /* @var $view ViewAssessmentPlanLogs */ ?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Active Campaign Integration</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link href="/assets/adminlte/plugins/datatables/jquery.dataTables.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        body {

        }
    </style>

<script>
function createInSunesis(id)
{
    alert("The learner has been created in Sunesis");
    window.location.href='do.php?_action=active_campaign_learner&emp_id='+id;
}

function updateSunesis(id)
{
    alert("The learner has been updated in Sunesis");
    window.location.href='do.php?_action=active_campaign_learner&update=1&&emp_id='+id;
}


function getResults()
{
    document.getElementById("validating_ilr").style.display = "block";
    window.location.href='do.php?_action=active_campaign_bulk&refresh=1';
}

</script>
</head>
<body class="table-responsive">
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Active Campaign Bulk Update</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="getResults()"><i class="fa fa-arrow-circle-o-left"></i> Update</span>
            </div>
            <div class="ActionIconBar">
                <span class="btn btn-sm btn-info fa fa-refresh" onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"></span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>

<div class="container-fluid">

<div class="row">
    <div class="col-sm-12">
        <span id="validating_ilr" style="display: none;">
            <img src="/images/progress-animations/loading51.gif" alt="validating ilr ..." />
        </span>
    </div>
</div>

    <div class="row">
        <div class="col-sm-12">
            <?php
            echo '<table id="tblLogs" class="table table-bordered">';
            echo <<<HEREDOC
	<thead class="bg-gray">
	<tr>
		<th>AC ID</th>
		<th>Firstnames</th>
		<th>Surname</th>
		<th>Most Recent OTJ Progress Review</th>
		<th>Most Recent OTJ Attendance</th>
		<th>Total Recorded OTJ</th>
		<th>Development Coach</th>
		<th>Latest Attendance</th>
		<th>Latest Attendance Type</th>
		<th>Most Recent Review Date</th>
		<th>Most Recent OTJ My Prtofolio</th>
		<th>Gateway Date</th>
		<th>Status</th>
	</tr>
	</thead>
HEREDOC;

            echo '<tbody>';
            $st = $link->query("SELECT * FROM tr
            LEFT JOIN ac_bulk_update ON ac_bulk_update.id = tr.active_campaign_id
            WHERE tr.status_code = 1 AND ac_bulk_update.created = (SELECT MAX(created) FROM ac_bulk_update);
            ");
            if($st)
            {
                while($row = $st->fetch())
                {
                    $class = "";
                    echo '<tr ' . $class . '><td>' . $row['active_campaign_id'] . '</td><td>' . $row['firstnames'] . '</td><td>' . $row['surname'] . '</td><td>' . $row['most_recent_otj_progress_review'] . '</td><td>' . $row['most_recent_otj_attendance'] . '</td><td>' . $row['total_recorded_otj'] . '</td><td>' . $row['development_coach'] . '</td><td>' . $row['latest_attendance'] . '</td><td>' . $row['latest_attendance_type'] . '</td><td>' . $row['most_recent_review_date'] . '</td><td>' . $row['most_recent_otj_my_portfolio'] . '</td><td>' . $row['gateway_date'] . '</td><td>Updated on ' . $row['created'] . '</td></tr>';
                }
            }
            $st = $link->query("SELECT * FROM tr WHERE tr.status_code = 1 and active_campaign_id is null");
            if($st)
            {
                while($row = $st->fetch())
                {
                    $class = "";
                    echo '<tr ' . $class . '><td>' . $row['active_campaign_id'] . '</td><td>' . $row['firstnames'] . '</td><td>' . $row['surname'] . '</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>Not linked</td></tr>';
                }
            }
            echo '</tbody></table>';
            ?>
        </div>
    </div>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>

<script type="text/javascript">
    $(function () {

    $('#tblLogs').DataTable({
    "paging": false,
    "lengthChange": false,
    "searching": true,
    "ordering": false,
    "info": false,
    "autoWidth": true
    });


    });
</script>
</body>
</html>