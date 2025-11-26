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
    alert("The employer has been created in Sunesis");
    window.location.href='do.php?_action=active_campaign&emp_id='+id;
}
</script>
</head>
<body class="table-responsive">
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Active Campaign</div>
            <div class="ButtonBar">

            </div>
            <div class="ActionIconBar">
                <span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="window.location.href='do.php?_action=manager_intervention_report&subaction=export_csv'" title="Export to .CSV file"></span>
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
            <?php
            echo '<table id="tblLogs" class="table table-bordered">';
            echo <<<HEREDOC
	<thead class="bg-gray">
	<tr>
		<th>Action</th>
		<th>AC ID</th>
		<th>Employer</th>
		<th>EDRS</th>
		<th>Address Line 1</th>
		<th>Address Line 2</th>
		<th>Address Line 3</th>
		<th>Address Line 4</th>
		<th>Postcode</th>
		<th>Contact Name</th>
		<th>Contact Mobile</th>
		<th>Contact Email</th>
	</tr>
	</thead>
HEREDOC;

            echo '<tbody>';
            $st = $link->query("SELECT * FROM ac_employers ORDER BY Id DESC");
            if($st)
            {
                while($row = $st->fetch())
                {
                    if($row['Sunesis']>0)
                    {
                        $class = 'style="background-color: lightgreen"';
                        echo '<tr ' . $class . '><td>&nbsp;</td><td>' . $row['Id'] . '</td><td>' . $row['Name'] . '</td><td>' . $row['EDRS'] . '</td><td>' . $row['Add1'] . '</td><td>' . $row['Add2'] . '</td><td>' . $row['Add3'] . '</td><td>' . $row['Add4'] . '</td><td>' . $row['Postcode'] . '</td><td>' . $row['PC_Name'] . '</td><td>' . $row['PC_Mobile'] . '</td><td>' . $row['PC_Email'] . '</td></tr>';
                    }
                    else
                    {
                        $class = "";
                        echo '<tr ' . $class . '><td><button onClick="createInSunesis('.$row['Id'].')">Create in Suneis</button></td><td>' . $row['Id'] . '</td><td>' . $row['Name'] . '</td><td>' . $row['EDRS'] . '</td><td>' . $row['Add1'] . '</td><td>' . $row['Add2'] . '</td><td>' . $row['Add3'] . '</td><td>' . $row['Add4'] . '</td><td>' . $row['Postcode'] . '</td><td>' . $row['PC_Name'] . '</td><td>' . $row['PC_Mobile'] . '</td><td>' . $row['PC_Email'] . '</td></tr>';
                    }
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