<?php /* @var $session OperationsSession */ ?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Add/Remove Training Schedule Entries</title>
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

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Add/Remove Training Schedule Entries</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <span class="btn btn-sm btn-default" onclick="window.location.href='do.php?_action=edit_crm_schedule_attendance&id=<?php echo $schedule->id; ?>';"><i class="fa fa-calendar"></i> Attendance</span>
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
        <div class="col-sm-12 well">
            <div align="center">
                <div class="row ">
                    <div class="col-sm-2">
                        <span class="text-bold">Level: </span>
                        <?php echo $this->scheduleLevelDesc($schedule->level); ?>
                    </div>
                    <div class="col-sm-2"><span class="text-bold">Training Date: </span><?php echo Date::toShort($schedule->training_date); ?></div>
                    <div class="col-sm-2"><span class="text-bold">Duration: </span><?php echo $schedule->duration; ?> day(s)</div>
                    <div class="col-sm-2"><span class="text-bold">Training End Date: </span><?php echo Date::toShort($schedule->training_end_date); ?></div>
                    <div class="col-sm-2"><span class="text-bold">Capacity: </span><?php echo $schedule->capacity; ?></div>
                    <div class="col-sm-2"><span class="text-bold">Trainer: </span><?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$schedule->trainer}'"); ?></div>
                    <div class="col-sm-2"><span class="text-bold">Venue: </span><?php echo $schedule->venue; ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <span class="box-title">Learners selected for this training date</span>
                    <span class="pull-right label label-info"><?php echo count($schedule->learner_ids); ?></span>
                </div>
                <div class="box-body">
                    <?php
                    if(count($schedule->learner_ids) > 0)
                    {
                        $added_learners = DAO::getResultset($link, "SELECT users.*, training.status AS training_status FROM users INNER JOIN training ON users.`id` = training.`learner_id` WHERE training.`schedule_id` = '{$schedule->id}' ORDER BY users.firstnames;", DAO::FETCH_ASSOC);
                        echo '<table class="table table-bordered table-condensed>"';
                        echo '<tr><th></th><th>Employer</th><th>Firstnames</th><th>Surname</th><th>Home Address</th><th>IMI Redeem Code</th><th>NI</th></tr>';
                        foreach($added_learners AS $added_learner)
                        {
                            echo $added_learner['training_status'] == 2 ? '<tr class="bg-success">' : '<tr>';
                            echo '<td>';
                            echo '<span class="btn btn-info btn-xs" onclick="window.location.href=\'do.php?_action=read_learner&username='.$added_learner['username'].'&id='.$added_learner['id'].'\';"><i class="fa fa-folder-open"></i></span> &nbsp; ';
                            echo '<span class="btn btn-danger btn-xs" onclick="removeLearner('.$added_learner['id'].');"><i class="fa fa-trash"></i></span>';
                            echo '</td>';
                            echo '<td>'.DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$added_learner['employer_id']}'").'</td>';
                            echo '<td>' . $added_learner['firstnames'] . '</td>';
                            echo '<td>' . $added_learner['surname'] . '</td>';
                            echo '<td class="small">'.$added_learner['home_address_line_1'] . ' ' . $added_learner['home_address_line_2'] . ' ' . $added_learner['home_address_line_3'] . ' ' . $added_learner['home_address_line_4'] . ' ' . $added_learner['home_postcode'].'</td>';
                            echo $added_learner['imi_redeem_code'] != '' ? '<td class="text-success">' . $added_learner['imi_redeem_code'] . '</td>' : '<td class="bg-red"></td>';
			    echo $added_learner['ni'] != '' ? '<td class="text-success">' . $added_learner['ni'] . '</td>' : '<td class="bg-red"></td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                        echo '<br>';
                        echo '<span class="btn btn-primary btn-sm" onclick="openEmailModal();">';
                        echo '<i class="fa fa-envelope"></i> Send emails to these learners';
                        echo '</span>';
                    }
                    else
                    {
                        echo '<span class="text-info"><i class="fa fa-info-circle"></i> No learner has been selected for this training date.</span>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <span class="box-title">Add Learners</span>
                </div>
                <div class="box-body">
                    <div class="well well-sm">
                        <form class="form-horizontal" name="frmSelectEmployer" id="frmSelectEmployer" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <input type="hidden" name="_action" value="ajax_helper" />
                            <input type="hidden" name="subaction" value="fetch_learners_list" />
                            <span class="text-info">
                                <i class="fa fa-info-circle"></i> Select employer and click "Show Learners" to select learners for this training date
                            </span><br>
                            <div class="form-group">
                                <label for="employer_id" class="col-sm-4 control-label fieldLabel_compulsory">Select Employer:</label>
                                <div class="col-sm-8">
                                    <?php
                                    $employers_sql = new SQLStatement("SELECT id, legal_name, null FROM organisations WHERE organisation_type = 2 ORDER BY legal_name");
                                    if($_SESSION['user']->employer_id == 3278)
                                    {
                                        $employers_sql->setClause("WHERE organisations.`creator` IN (SELECT users.`username` FROM users WHERE users.`employer_id` = 3278) OR organisations.id IN (SELECT users.`employer_id` FROM users INNER JOIN training ON users.`id` = training.`learner_id` INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` WHERE users.`type` = 5 AND crm_training_schedule.`venue` = 'Peterborough Skills Academy')");
                                    }
                                    $employers = DAO::getResultset($link, $employers_sql->__toString());
                                    echo HTML::selectChosen('employer_id', $employers, '', true);
                                    ?>
                                </div>
                            </div>
                            <span class="btn btn-sm btn-info" onclick="showLearners();"><i class="fa fa-search"></i> Show Learners</span>
                        </form>
                    </div>
                </div>
                <div class="box-footer">
                    <p class="pull-right"><span id="btnAddLearners" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Click to add selected learners</span></p>
                    <div id="learnersSelection"></div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalLearnersSelection" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title text-bold">Send email to learners</h5>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" name="frmEmail" id="frmEmail" method="post" action="do.php?_action=send_email_to_learners_in_schedule">
                    <input type="hidden" name="schedule_id" value="<?php echo $schedule->id; ?>" />
                    <div class="control-group">
                        <label class="control-label" for ="task_comments">Select Learners:</label>
                        <?php
                        $selected_learners_list = [];
                        foreach($schedule->learner_ids AS $_learner_id)
                        {
                            $selected_learners_list[] = [$_learner_id, DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$_learner_id}'")];
                        }
                        echo HTML::checkboxGrid('learnersSelectionForEmail', $selected_learners_list, $schedule->learner_ids, 4);
                        ?>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for ="template_id">Template:</label> &nbsp
                        <span class="btn btn-xs btn-info" id="btnPreviewTemplate" title="Preview the selected template"><i class="fa fa-eye"></i></span>
                        <?php
			$templates_ddl = [];
			if($schedule->level == "L2")
                        {
                            $templates_ddl = DAO::getResultset($link, "SELECT id, template_type, null FROM email_templates WHERE template_type IN ('LEVEL2_JOIN_INST_RUDDINGTON', 'HS_REQUEST', 'LEVEL2_REMINDER_1_WEEK_TO_GO_RUDDINGTON', 'LEVEL2_LOOKING_FORWARD_1_DAY_TO_GO_RUDDINGTON', 'HS_REMINDER') ORDER BY sorting");
                        }
                        if($schedule->level == "L3")
                        {
                            // $templates_ddl = DAO::getResultset($link, "SELECT id, template_type, null FROM email_templates WHERE template_type IN ('LEVEL3_JOIN_INST', 'HS_REQUEST', 'LEVEL3_REMINDER_1_WEEK_TO_GO', 'LEVEL3_LOOKING_FORWARD_1_DAY_TO_GO', 'HS_REMINDER', 'LEVEL3_EL_REMINDER_VOCANTO', 'LEVEL3_WMP_JOIN_INST', 'LEVEL3_THANKS_BOOKING', 'TRAINING_VENUE_CHANGE', 'LEVEL3_JOIN_INST_AFTERNOON') ORDER BY sorting");
                            $templates_ddl = DAO::getResultset($link, "SELECT id, template_type, null FROM email_templates WHERE template_type IN ('HS_REQUEST', 'HS_REMINDER', 'Master_Level_3_WOLVES_JI', 'Master_Level_3_Nottingham_JI', 'Master_Level_3_Lincoln_JI') ORDER BY sorting");
                        }
                        if($schedule->level == "L4")
                        {
                            // $templates_ddl = DAO::getResultset($link, "SELECT id, template_type, null FROM email_templates WHERE template_type IN ('LEVEL4_JOIN_INST', 'HS_REQUEST', 'LEVEL4_REMINDER_1_WEEK_TO_GO', 'LEVEL4_LOOKING_FORWARD_1_DAY_TO_GO', 'HS_REMINDER', 'LEVEL3_EL_REMINDER_VOCANTO', 'LEVEL4_WMP_JOIN_INST', 'LEVEL4_THANKS_BOOKING', 'TRAINING_VENUE_CHANGE', 'LEVEL4_JOIN_INST_AFTERNOON') ORDER BY sorting");
                            $templates_ddl = DAO::getResultset($link, "SELECT id, template_type, null FROM email_templates WHERE template_type IN ('HS_REQUEST', 'HS_REMINDER', 'Master_Level_4_WOLVES_JI', 'Master_Level_4_Nottingham_JI', 'Master_Level_4_Lincoln_JI') ORDER BY sorting");
                        }
                        echo HTML::selectChosen('template_id', $templates_ddl, '', true);
                        ?>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for ="subject">Subject:</label> &nbsp;
                        <input type="text" name="subject" id="subject" class="form-control" value="" />
                    </div>
                    <p><br><br></p>
                </form>
		<div class="table-responsive">
                    <div id="email_perview"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left btn-md" onclick="$('#modalLearnersSelection').modal('hide');">Cancel</button>
                <button type="button" id="btnModalLearnersSelectionSave" class="btn btn-primary btn-md"><i class="fa fa-envelope"></i> Send</button>
            </div>
        </div>
    </div>
</div>



<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>

<script language="JavaScript">

    function removeLearner(learner_id)
    {
        if(!confirm('Are you sure you want to remove?'))
            return false;

        $.ajax({
            type:'GET',
            url:'do.php?_action=ajax_helper&subaction=remove_learner_from_training&schedule_id='+encodeURIComponent(<?php echo $schedule->id; ?>)+'&learner_id='+learner_id,
            success: function(response) {
                window.location.reload();
            },
            error: function(data, textStatus, xhr){
                console.log(data.responseText);
            }
        });
    }

    function showLearners()
    {
        var employer_id = $('#employer_id').val();
        $.ajax({
            type:'GET',
            url:'do.php?_action=ajax_helper&subaction=fetch_employer_learners&schedule_id='+encodeURIComponent(<?php echo $schedule->id; ?>)+'&employer_id='+encodeURIComponent(employer_id),
            success: function(response) {
                $('#btnAddLearners').show();
                $('#learnersSelection').html(response);
            },
            error: function(data, textStatus, xhr){
                console.log(data.responseText);
            }
        });
    }

    $(function(){

        $('#btnAddLearners').on('click', function(){
            var checked = []
            $("input[name='learners[]']:checked").each(function ()
            {
                checked.push(parseInt($(this).val()));
            });

            $.ajax({
                type:'POST',
                url:'do.php?_action=ajax_helper&subaction=add_learners_into_date&schedule_id='+encodeURIComponent(<?php echo $schedule->id; ?>)+'&learners='+encodeURIComponent(checked),
                success: function() {
                    window.location.reload();
                },
                error: function(data, textStatus, xhr){
                    console.log(data.responseText);
                }
            });
        });

        $("#btnModalLearnersSelectionSave").on('click', function(){
            var form = document.forms["frmEmail"];

            var grid_learnersSelectionForEmail = document.getElementById('grid_learnersSelectionForEmail');
            var grid_learnersSelectionForEmail_inputs = grid_learnersSelectionForEmail.getElementsByTagName('INPUT');
            var anySelected = false;
            for(var i = 0; i < grid_learnersSelectionForEmail_inputs.length; i++)
            {
                if(grid_learnersSelectionForEmail_inputs[i].checked)
                    anySelected = true;
            }
            if(!anySelected)
            {
                alert('Please select the learners to send the email.');
                return;
            }

            function sentEmailsCallback(client)
            {
                if(client && client.status == 200)
                {
                    alert(client.responseText);
                }

                $('#modalLearnersSelection').modal('hide');
            }

            var client = ajaxPostForm(form, sentEmailsCallback);
        });

	$("#btnPreviewTemplate").on("click", function(){
            if($("#template_id").val() == '')
            {
                $("#email_perview").html('');
                return false;
            }

            var url = 'do.php?_action=ajax_helper&subaction=preview_email_template&template_id='+encodeURIComponent($("#template_id").val());
            var req = ajaxRequest(url);

            $("#email_perview").html(req.responseText.replace('<img src="https://duplex.sunesis.uk.net/images/logos/city_of_wolverhampton_college.png" /><hr>', ''));

        });

    });

    function openEmailModal()
    {
	$("#email_perview").html('');
        $('#modalLearnersSelection').modal('show');

    }


</script>

</body>
</html>