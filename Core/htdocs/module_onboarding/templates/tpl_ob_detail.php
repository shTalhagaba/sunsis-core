<?php /* @var $tr TrainingRecord */ ?>
<?php /* @var $user User */ ?>
<?php /* @var $contract Contract */ ?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Onboarding Detail</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
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
            <div class="Title" style="margin-left: 6px;">Onboarding Detail of [<?php echo $tr->firstnames . ' ' . $tr->surname; ?>]</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
            </div>
            <div class="ActionIconBar">

            </div>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>
<br>

<div class="row">
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-body">
                <!--				<span class="btn btn-md btn-primary" onclick="viewOnboardingEmail();"><i class="fa fa-eye"></i> View Onboarding Email</span>-->
                <!--				<span class="btn btn-md btn-primary" onclick="ViewEmailToEmployer();"><i class="fa fa-eye"></i> View Email to Employer</span>-->
                <!--                <span class="btn btn-sm btn-primary" onclick="synchroniseILR();"><i class="fa fa-refresh"></i> Synchronise ILR</span>-->
                <!--				<hr>-->
                <!--				<span class="btn btn-md btn-primary" onclick="sendOnboardingEmail();"><i class="fa fa-envelope"></i> Send Onboarding Email</span>-->
                <!--				<span class="btn btn-md btn-primary" onclick="sendEmailToEmployer();"><i class="fa fa-envelope"></i> Send Email to Employer</span>-->
                <!--                <hr>-->
                <span class="btn btn-sm btn-info" onclick="generateILP();"><i class="fa fa-download"></i> Download ILP</span>
                <span class="btn btn-sm btn-info" onclick="generateAppAgreement();"><i class="fa fa-download"></i> Download App. Agreement</span>
                <span class="btn btn-sm btn-info" onclick="generateGDPR();"><i class="fa fa-download"></i> Download Privacy Notice & GDPR</span>
                <span class="btn btn-sm btn-primary" onclick="synchroniseILR();"><i class="fa fa-refresh"></i> Synchronise ILR</span>

            </div>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border"><h2 class="box-title">Additional Details</h2></div>
            <div class="box-body">
                <p><span class="text-bold">Are you a care leaver? &nbsp; </span><?php echo $ob_learner->care_leaver == "1" ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>';?>
                <p><span class="text-bold">Do you have an EHC Plan? &nbsp; </span><?php echo $ob_learner->EHC_Plan == "1" ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>';?>
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab1" data-toggle="tab"> Emergency Contact</a></li>
                        <li><a href="#tab3" data-toggle="tab"> Prior Attainment</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="tab1">
                            <dl class="dl-horizontal">
                                <dt>Name:</dt><dd><span class="text-muted"><?php echo $ob_learner->em_con_name; ?></span></dd>
                                <dt>Relationship:</dt><dd><span class="text-muted"><?php echo $ob_learner->em_con_rel; ?></span></dd>
                                <dt>Telephone:</dt><dd><span class="text-muted"><?php echo $ob_learner->em_con_tel; ?></span></dd>
                                <dt>Mobile:</dt><dd><span class="text-muted"><?php echo $ob_learner->em_con_mob; ?></span></dd>
                            </dl>
                        </div>
                        <div class="tab-pane" id="tab3">
                            <label>Prior Attainment Level: </label>
                            <span class="label label-success">
								<?php
                                $sql = "SELECT description FROM central.lookup_prior_attainment WHERE code IN (SELECT level FROM ob_learners_pa WHERE ob_learner_id = '{$ob_learner->id}' AND q_type = 'h')";
                                echo DAO::getSingleValue($link,$sql);
                                ?>
							</span>
                            <table class="table row-border">
                                <thead><tr><th>Level</th><th>Subject</th><th>Predicted Grade</th><th>Actual Grade</th><th>Date Completed</th></tr></thead>
                                <?php
                                $sql = <<<SQL
SELECT
  ob_learners_pa.`ob_learner_id`,
  ob_learners_pa.`level`,
  ob_learners_pa.`subject`,
  (SELECT lookup_gcse_grades.`description` FROM lookup_gcse_grades WHERE lookup_gcse_grades.`id` = ob_learners_pa.`p_grade`) AS p_grade,
  (SELECT lookup_gcse_grades.`description` FROM lookup_gcse_grades WHERE lookup_gcse_grades.`id` = ob_learners_pa.`a_grade`) AS a_grade,
  ob_learners_pa.`date_completed`,
  ob_learners_pa.`school`,
  ob_learners_pa.`q_type`
FROM
  ob_learners_pa
WHERE ob_learner_id = '$ob_learner->id'
  AND q_type = 'g'
;
SQL;
                                $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
                                foreach($records AS $row)
                                {
                                    echo '<tr>';
                                    echo '<td>GCSE</td><td>' . $row['subject'] . '</td><td>' . $row['p_grade'] . '</td><td>' . $row['a_grade'] . '</td><td>' . Date::toShort($row['date_completed']) . '</td>';
                                    echo '</tr>';
                                }
                                $sql = <<<SQL
SELECT
  ob_learners_pa.`ob_learner_id`,
  ob_learners_pa.`level`,
  ob_learners_pa.`subject`,
  (SELECT lookup_gcse_grades.`description` FROM lookup_gcse_grades WHERE lookup_gcse_grades.`id` = ob_learners_pa.`p_grade`) AS p_grade,
  (SELECT lookup_gcse_grades.`description` FROM lookup_gcse_grades WHERE lookup_gcse_grades.`id` = ob_learners_pa.`a_grade`) AS a_grade,
  ob_learners_pa.`date_completed`,
  ob_learners_pa.`school`,
  ob_learners_pa.`q_type`
FROM
  ob_learners_pa
WHERE ob_learner_id = '$ob_learner->id'
  AND q_type NOT IN ('g', 'h')
;
SQL;
                                $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
                                foreach($records AS $row)
                                {
                                    $level = DAO::getSingleValue($link, "SELECT description FROM lookup_ob_qual_levels WHERE id = '{$row['level']}'");
                                    echo '<tr>';
                                    echo '<td>' . $level . '</td><td>' . $row['subject'] . '</td><td>' . $row['p_grade'] . '</td><td>' . $row['a_grade'] . '</td><td>' . Date::toShort($row['date_completed']) . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border"><h2 class="box-title">Log</h2></div>
            <div class="box-body">
                <?php $this->renderHistory($link, $ob_learner->id); ?>
            </div>
        </div>
    </div>
</div>

<div id="loading"></div>

<div id="dialogPreview" title="Email content" style="font-size: smaller;"></div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>

<script language="JavaScript">

    $(function(){
        $("#loading").dialog({
            autoOpen: false,
            width: 'auto',
            height: 'auto',
            modal: true,
            closeOnEscape: false,
            resizable: false,
            draggable: false,
            buttons: {}
        });

        $('#dialogPreview').dialog({
            modal: true,
            width: 700,
            height: 700,
            closeOnEscape: true,
            autoOpen: false,
            resizable: true,
            draggable: true,
            buttons: {
                'Close': function() {$(this).dialog('close');}
            }
        });

    });

    function viewOnboardingEmail()
    {
        $('#dialogPreview').html('');
        $.ajax({
            url: 'do.php?_action=ob_detail&subaction=view_learner_email&tr_id=<?php echo $tr->id; ?>&ob_learner_id=<?php echo $ob_learner->id; ?>',
            method: 'post',
            success: function(data) {
                $('#dialogPreview').html(data);
                $('#dialogPreview').dialog('open');
            },
            error: function(data){
                $('#dialogPreview').html(data);
            }
        });
    }

    function ViewEmailToEmployer()
    {
        $('#dialogPreview').html('');
        $.ajax({
            url: 'do.php?_action=ob_detail&subaction=view_employer_email&tr_id=<?php echo $tr->id; ?>&ob_learner_id=<?php echo $ob_learner->id; ?>',
            method: 'post',
            success: function(data) {
                $('#dialogPreview').html(data);
                $('#dialogPreview').dialog('open');
            },
            error: function(data){
                $('#dialogPreview').html(data);
            }
        });
    }

    function sendOnboardingEmail()
    {
        var is_finished = '<?php echo $ob_learner->is_finished; ?>';
        if(is_finished == 'Y')
        {
            alert('Learner has already completed the form.');
            return;
        }
        if(!confirm('Are you sure, you want to continue?'))
            return;
        $.ajax({
            url: 'do.php?_action=ob_detail&subaction=email&tr_id=<?php echo $tr->id; ?>&ob_learner_id=<?php echo $ob_learner->id; ?>',
            method: 'post',
            beforeSend: function(){
                $("#loading").dialog({ title: "Please wait ..." });
                $("#loading").dialog('open').html("<p><i class=\"fa fa-refresh fa-spin\"></i> Sending email ...</p>");
            },
            success: function(data) {
                $("#loading").dialog({ title: "Completed" });
                $('#loading').html(data);
            },
            error: function(data){
                $('#loading').html(data);
            }
        });
    }

    function sendEmailToEmployer()
    {
        var is_finished = '<?php echo $ob_learner->is_finished; ?>';
        if(is_finished == 'N')
        {
            alert('Learner has not yet completed the form.');
            return;
        }
        var employer_signature = '<?php echo $ob_learner->employer_signature; ?>';
        if(employer_signature != '')
        {
            alert('Employer has already signed the form.');
            return;
        }
        if(!confirm('Are you sure, you want to continue?'))
            return;
        $.ajax({
            url: 'do.php?_action=ob_detail&subaction=email_to_employer&tr_id=<?php echo $tr->id; ?>&ob_learner_id=<?php echo $ob_learner->id; ?>',
            method: 'post',
            beforeSend: function(){
                $("#loading").dialog({ title: "Please wait ..." });
                $("#loading").dialog('open').html("<p><i class=\"fa fa-refresh fa-spin\"></i> Sending email ...</p>");
            },
            success: function(data) {
                $("#loading").dialog({ title: "Completed" });
                $('#loading').html(data);
            },
            error: function(data){
                $('#loading').html(data);
            }
        });
    }

    function generateILP()
    {
        var is_finished = '<?php echo $ob_learner->is_finished; ?>';
        if(is_finished == 'N')
        {
            alert('Learner has not yet completed the form.');
            return;
        }
        window.location.href="do.php?_action=ob_detail&subaction=generate_ilp&tr_id=<?php echo $tr->id; ?>&ob_learner_id=<?php echo $ob_learner->id; ?>";
    }

    function generateAppAgreement()
    {
        var is_finished = '<?php echo $ob_learner->is_finished; ?>';
        if(is_finished == 'N')
        {
            alert('Learner has not yet completed the form.');
            return;
        }
        window.location.href="do.php?_action=ob_detail&subaction=generate_app_agreement&tr_id=<?php echo $tr->id; ?>&ob_learner_id=<?php echo $ob_learner->id; ?>";
    }

    function generateGDPR()
    {
        var is_finished = '<?php echo $ob_learner->is_finished; ?>';
        if(is_finished == 'N')
        {
            alert('Learner has not yet completed the form.');
            return;
        }
        window.location.href="do.php?_action=ob_detail&subaction=generate_gdpr_statement&tr_id=<?php echo $tr->id; ?>&ob_learner_id=<?php echo $ob_learner->id; ?>";
    }

    function generateEligibilityChecklist()
    {
        var is_finished = '<?php echo $ob_learner->is_finished; ?>';
        if(is_finished == 'N')
        {
            alert('Learner has not yet completed the form.');
            return;
        }
        window.location.href="do.php?_action=ob_detail&subaction=generate_eligibility_checklist&tr_id=<?php echo $tr->id; ?>&ob_learner_id=<?php echo $ob_learner->id; ?>";
    }

    function synchroniseILR()
    {
        var is_finished = '<?php echo $ob_learner->is_finished; ?>';
        if(is_finished == 'N')
        {
            alert('Learner has not yet completed the form.');
            return;
        }

        var is_learner_signed = '<?php echo $ob_learner->learner_signature; ?>';
        if(is_learner_signed == '')
        {
            alert('Learner has not yet signed the form.');
            return;
        }

        var is_employer_signed = '<?php echo $ob_learner->employer_signature; ?>';
        if(is_employer_signed == '')
        {
            alert('Learner\' employer has not yet signed the form.');
            return;
        }

        var ob_alert = '<?php echo $tr->ob_alert; ?>';
        if(ob_alert == '0')
        {
            if(!confirm('Learner ILR has already been synchronised, are you sure you want to continue?'))
                return;
        }

        $.ajax({
            type:'GET',
            url:'do.php?_action=ob_detail&subaction=synchronise_ilr&tr_id=<?php echo $tr->id; ?>',
            async: true,
            success: function(data) {
                alert('The learner\'s ILR has been successfully synchronised.');
                window.location.reload();
            },
            error: function(data, textStatus, xhr){
                console.log(data.responseText);
            }
        });

        //window.location.href="do.php?_action=ob_detail&subaction=synchronise_ilr&tr_id=<?php echo $tr->id; ?>";
    }

</script>

</body>
</html>
