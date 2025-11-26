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
    document.forms[0].submit();
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
<div class="col-sm-8">
	<div class="box box-primary">
		<form autocomplete="off" class="form-horizontal" name="frmOrgCRMContact" id="frmOrgCRMContact" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input type="hidden" name="_action" value="active_campaign_learner" />
			<div class="box-body with-border">
				<div class="form-group">
					<label for="contact_title" class="col-sm-4 control-label fieldLabel_optional">Search:</label>
					<div class="col-sm-4">
						<input class="form-control" type="text" name="search" id="search" value=""  maxlength="30" />
					</div>
					<div class="col-sm-4">
                    <input type="button" onclick="getResults()" value="Get Results" />
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

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
		<th>Action</th>
		<th>AC ID</th>
		<th>Firstnames</th>
		<th>Surname</th>
        <th>Employer</th>
		<th>Date of Birth</th>
		<th>National Insurance</th>
		<th>ULN</th>
		<th>Gender</th>
		<th>Home Address Line 1</th>
		<th>Home Address Line 2</th>
		<th>Home Address Line 3</th>
		<th>Home Address Line 4</th>
		<th>Home Postcode</th>
		<th>Phone</th>
		<th>Email</th>
		<th>Most Recent OTJ Progress Review</th>
		<th>Most Recent OTJ Attendance</th>
		<th>Total Recorded OTJ</th>
		<th>Development Coach</th>
		<th>Latest Attendance</th>
		<th>Latest Attendance Type</th>
		<th>Most Recent Review Date</th>
		<th>Most Recent OTJ My Prtofolio</th>
	</tr>
	</thead>
HEREDOC;

            echo '<tbody>';
            $st = $link->query("SELECT ac_learners.*, organisations.legal_name, (select count(*) from users where ac_learners.ni = users.ni or ac_learners.uln = users.uln) as Sunesis FROM ac_learners INNER JOIN organisations ON organisations.id = ac_learners.employer_id ORDER BY ac_learners.id DESC");
            if($st)
            {
                while($row = $st->fetch())
                {
                    if($row['Sunesis']>0)
                    {
                        $class = 'style="background-color: lightgreen"';
                        echo '<tr ' . $class . '><td><button onClick="updateSunesis('.$row['id'].')">Update Suneis</button></td><td>' . $row['id'] . '</td><td>' . $row['firstnames'] . '</td><td>' . $row['surname'] . '</td><td>' . $row['legal_name'] . '</td><td>' . $row['dob'] . '</td><td>' . $row['ni'] . '</td><td>' . $row['uln'] . '</td><td>' . $row['gender'] . '</td><td>' . $row['home_address_line_1'] . '</td><td>' . $row['home_address_line_2'] . '</td><td>' . $row['home_address_line_3'] . '</td><td>' . $row['home_address_line_4'] . '</td><td>' . $row['home_postcode'] . '</td><td>' . $row['home_telephone'] . '</td><td>' . $row['home_email'] . '</td><td>' . $row['most_recent_otj_progress_review'] . '</td><td>' . $row['most_recent_otj_attendance'] . '</td><td>' . $row['total_recorded_otj'] . '</td><td>' . $row['development_coach'] . '</td><td>' . $row['latest_attendance'] . '</td><td>' . $row['latest_attendance_type'] . '</td><td>' . $row['most_recent_review_date'] . '</td><td>' . $row['most_recent_otj_my_portfolio'] . '</td></tr>';
                    }
                    else
                    {
                        $class = "";
                        echo '<tr ' . $class . '><td><button onClick="createInSunesis('.$row['id'].')">Create in Suneis</button></td><td>' . $row['id'] . '</td><td>' . $row['firstnames'] . '</td><td>' . $row['surname'] . '</td><td>' . $row['legal_name'] . '</td><td>' . $row['dob'] . '</td><td>' . $row['ni'] . '</td><td>' . $row['uln'] . '</td><td>' . $row['gender'] . '</td><td>' . $row['home_address_line_1'] . '</td><td>' . $row['home_address_line_2'] . '</td><td>' . $row['home_address_line_3'] . '</td><td>' . $row['home_address_line_4'] . '</td><td>' . $row['home_postcode'] . '</td><td>' . $row['home_telephone'] . '</td><td>' . $row['home_email'] . '</td><td>' . $row['most_recent_otj_progress_review'] . '</td><td>' . $row['most_recent_otj_attendance'] . '</td><td>' . $row['total_recorded_otj'] . '</td><td>' . $row['development_coach'] . '</td><td>' . $row['latest_attendance'] . '</td><td>' . $row['latest_attendance_type'] . '</td><td>' . $row['most_recent_review_date'] . '</td><td>' . $row['most_recent_otj_my_portfolio'] . '</td></tr>'; 
                        //echo '<tr ' . $class . '><td></td><td>' . $row['Id'] . '</td><td>' . $row['Name'] . '</td><td>' . $row['EDRS'] . '</td><td>' . $row['Add1'] . '</td><td>' . $row['Add2'] . '</td><td>' . $row['Add3'] . '</td><td>' . $row['Add4'] . '</td><td>' . $row['Postcode'] . '</td><td>' . $row['PC_Name'] . '</td><td>' . $row['PC_Mobile'] . '</td><td>' . $row['PC_Email'] . '</td></tr>';
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