<?php /* @var $tr TrainingRecord */ ?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Learner's Learner Engagement Action Plan</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="/assets/adminlte/plugins/datatables/jquery.dataTables.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        body {
            overflow: scroll;
        }
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>
</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">View Learner's Learner Engagement Action Plans</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>

            </div>
            <div class="ActionIconBar">
                <span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="window.location.href='do.php?_action=view_epa_orgs&subaction=export_csv'" title="Export to .CSV file"></span>
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
            <div class="table-responsive">
                <br>
                <table class="table table-bordered table-condensed">
                    <tr>
                        <th>Learner Name:</th><td id="tdLearnerName"><?php echo $tr->firstnames . ' ' . $tr->surname; ?></td>
                        <span style="display: none;" id="lblLearnerEmail"><?php echo $tr->home_email != '' ? $tr->home_email : $tr->work_email; ?></span>
                        <th>Learner Ref.:</th><td><?php echo $tr->l03; ?></td>
                        <th>Course:</th><td><?php echo DAO::getSingleValue($link, "SELECT courses.title FROM courses INNER JOIN courses_tr ON courses.id = courses_tr.course_id WHERE courses_tr.tr_id = '{$tr->id}'"); ?></td>
                        <th>Dates:</th><td><?php echo Date::toShort($tr->start_date) . ' - ' . Date::toShort($tr->target_date); ?></td>
                        <th>Employer:</th><td><a href="do.php?_action=read_employer_v3&id=<?php echo $tr->employer_id; ?>"><?php echo DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$tr->employer_id}'"); ?></a></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <span class="text-bold lead">Learner's Contacts</span>
            <table class="table table-bordered table-condensed">
                <tr>
                    <th>Date of Activity</th>
                    <th>Record of work Completed</th>
                    <th>Learner Sign</th>
                    <th>Coach Sign</th>
                    <th>Employer Sign</th>
                    <th>Action</th>
                </tr>
                <?php
                $records = DAO::getResultset($link, "SELECT id, date_of_activity, record_of_work_completed, learner_sign, coach_sign, emp_sign FROM review_forms WHERE tr_id = '{$tr->id}' ORDER BY date_of_activity", DAO::FETCH_ASSOC);
                if(count($records) == 0)
                    echo '<tr><td colspan="5"><i>No records found.</i></td> </tr>';
                else
                {
                    foreach($records AS $row)
                    {
                        //echo HTML::viewrow_opening_tag("do.php?_action=lead_form&review_id={$row['id']}&tr_id={$tr->id}");
			echo '<tr onclick="openReviewForm(\''. $row['id'] . '\', \'' . $tr->id . '\');" 
                        onmouseover="if(window.viewrow_onmouseover){window.viewrow_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)};" 
                        onmouseout="if(window.viewrow_onmouseout){window.viewrow_onmouseout(this, arguments.length > 0 ? arguments[0] : window.event)};" 
                        style="cursor: pointer;">';
                        echo '<td class="dateOfActivity">' . Date::toShort($row['date_of_activity']) . '</td>';
                        echo '<td>' . $row['record_of_work_completed'] . '</td>';
                        echo $row['learner_sign'] != '' ? '<td align="center"><i class="fa fa-check fa-lg"></i></td>' : '<td></td>';
                        echo $row['coach_sign'] != '' ? '<td align="center"><i class="fa fa-check fa-lg"></i></td>' : '<td></td>';
                        echo $row['emp_sign'] != '' ? '<td align="center"><i class="fa fa-check fa-lg"></i></td>' : '<td></td>';
                        echo '<td class="colActions">';
                        if($row['coach_sign'] == '')
                        {
                            $btn_learner_email_class = "disabled";
                        }
                        else
                        {
                            $btn_learner_email_class = $row['learner_sign'] != '' ? "disabled" : "onclick=\"sendEmailToLearner('{$row['id']}', '{$row['date_of_activity']}');\"";
                        }
                        echo '<p><span ' . $btn_learner_email_class . ' class="btn btn-primary btn-xs" ><i class="fa fa-envelope"></i> Send email to LEARNER</span></p>';
                        if($row['coach_sign'] == '')
                        {
                            $btn_employer_email_class = "disabled";
                        }
                        else
                        {
                            $btn_employer_email_class = $row['emp_sign'] != '' ? "disabled" : "onclick=\"sendEmailToEmployer('{$row['id']}', '{$row['date_of_activity']}');\"";
                        }
                        echo '<p><span ' . $btn_employer_email_class . ' class="btn btn-primary btn-xs"><i class="fa fa-envelope"></i> Send email to EMPLOYER</span></p>';
                        echo '</td>';
                        echo '</tr>';
                    }
                }
                ?>
            </table>
        </div>
    </div>
</div>

<div style="display:none;" id="modalFormEmployerEmail" title="Send email to employer">
    <form name="formEmployerEmail" action="do.php?_action=ajax_actions">
        <input type="hidden" name="subaction" value="sendLeadFormEmailToEmployer">
        <input type="hidden" name="review_id" value="">
        <p><strong>Learner:</strong> <?php echo $tr->firstnames . ' ' . $tr->surname; ?></p>
        <p><strong>Email:</strong> <?php echo $tr->work_email != '' ? $tr->work_email : $tr->home_email; ?></p>
        <p><strong>Date of activity:</strong> <span class="lblFormDoa"></span></p>
        <p><strong>Select employer contact:</strong> <?php echo HTML::select('formEmployerContact', $employer_contacts_ddl, '', false); ?></p>
        <p><strong>Sure to send email to the employer?</strong></p>
        <p></p>
    </form>
</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<script language="JavaScript">
    $(function() {

        $('.colActions').click(function(e){
            e.stopPropagation();
        });

    });

    function sendEmailToLearner(review_id, date_of_activity)
    {
        if($("#lblLearnerEmail").text().trim() == '')
        {
            alert("Please edit the learner's training record and provide email address.");
            return;
        }

        var doa = stringToDate(date_of_activity);

        var html = "<p><strong>Learner:</strong> " + $("#tdLearnerName").text() + "</p>";
        html += "<p><strong>Email:</strong> " + $("#lblLearnerEmail").text() + "</p>";
        html += "<p><strong>Date of activity:</strong> " + formatDate(doa) + "</p>";
        html += "<p><strong>Sure to send email to the learner?</strong></p>";

        $("<div></div>").html(html).dialog({
            title: 'Send email to learner',
            resizable: false,
            modal: true,
            width: 400,
            height: 270,
            buttons: {
                "Yes": function() {
                    var client = ajaxRequest("do.php?_action=ajax_actions&subaction=sendLeadFormEmailToLearner&review_id="+encodeURIComponent(review_id));
                    if(client)
                    {
                        $("<div></div>").html(client.responseText).dialog( {buttons: {"OK": function(){$(this).dialog("close")}}} );
                        $( this ).dialog( "close" );
                    }
                },
                "No": function() {
                    $( this ).dialog( "close" );
                }
            }
        }).css("font-size", "smaller");
    }

    function sendEmailToEmployer(review_id, date_of_activity)
    {
        var formEmployerEmail = document.forms["formEmployerEmail"];
        var doa = stringToDate(date_of_activity);
        $(".lblFormDoa").text(formatDate(doa));
        formEmployerEmail.review_id.value = review_id;

        $('#modalFormEmployerEmail').dialog({
            title: 'Send email to employer',
            resizable: false,
            modal: true,
            width: 400,
            height: 300,
            buttons: {
                "Yes": function() {
                    var client = ajaxPostForm(document.forms["formEmployerEmail"]);
                    if(client)
                    {
                        $("<div></div>").html(client.responseText).dialog( {buttons: {"OK": function(){$(this).dialog("close")}}} );
                        $( this ).dialog( "close" );
                    }
                },
                "No": function() {
                    $( this ).dialog( "close" );
                }
            }
        }).css("font-size", "smaller");
    }

    function openReviewForm(form_id, tr_id)
    {
        var coach = '<?php echo $tr->coach; ?>';
        if(coach == '')
        {
            alert("Coach field is not set. Please edit the learner's training record and select the Coach.");
            return;
        }

        window.location.href = "do.php?_action=lead_form&review_id="+form_id+"&tr_id="+tr_id;
    }

</script>

</body>
</html>