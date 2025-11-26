<?php /* @var $session OperationsSession */ ?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Send Email to Schedule Entries</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote-bs3.css">

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
            <div class="Title" style="margin-left: 6px;">Send Email to Schedule Entries</div>
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
        <div class="col-sm-12 well">
            <div align="center">
                <div class="row ">
                    <div class="col-sm-2">
                        <span class="text-bold">Level: </span>
                        <?php 
                        if($schedule->level == 'L1')
                            echo 'Level 1';
                        elseif($schedule->level == 'L2')
                            echo 'Level 2';
                        elseif($schedule->level == 'L3')
                            echo 'Level 3';
                        elseif($schedule->level == 'L4')
                            echo 'Level 4';
                        else
                            echo '';
                        ?>
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
        <div class="col-sm-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <span class="box-title">Learners selected for this training date</span>
                    <span class="pull-right label label-info"><?php echo count($number_of_added_learners); ?></span>
                </div>
                <div class="box-body">
                    <?php
                    if($schedule->learner_ids != '')
                    {
                        $added_learners = DAO::getResultset($link, "SELECT * FROM users WHERE users.id IN ({$schedule->learner_ids}) ORDER BY firstnames", DAO::FETCH_ASSOC);
                        echo '<table class="table table-bordered table-condensed>"';
                        echo '<tr><th></th><th>Employer</th><th>Firstnames</th><th>Surname</th><th>Home Address</th></tr>';
                        foreach($added_learners AS $added_learner)
                        {
                            echo '<tr>';
                            echo '<td><input type="checkbox" name="learners[]" value="' . $added_learner['id'] . '"></td>';
                            echo '<td>'.DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$added_learner['employer_id']}'").'</td>';
                            echo '<td>' . $added_learner['firstnames'] . '</td>';
                            echo '<td>' . $added_learner['surname'] . '</td>';
                            echo '<td class="small">'.$added_learner['home_address_line_1'] . ' ' . $added_learner['home_address_line_2'] . ' ' . $added_learner['home_address_line_3'] . ' ' . $added_learner['home_address_line_4'] . ' ' . $added_learner['home_postcode'].'</td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                    }
                    else
                    {
                        echo '<span class="text-info"><i class="fa fa-info-circle"></i> No learner has been selected for this training date.</span>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <span class="box-title">Email</span>
                </div>
                <div class="box-body">
                    <?php
                    $email_templates = DAO::getResultset($link, "SELECT template_type, template_type, null FROM email_templates WHERE template_type IN ('EMPLOYER_TNA', 'REMINDER_EMPLOYER_TNA', 'SUCCESSFUL_SCREENING', 'UNSUCCESSFUL_SCREENING');");
                    if(SystemConfig::getEntityValue($link, 'module_crm'))
                    {
                        if(DB_NAME == "am_duplex")
                            $email_templates = DAO::getResultset($link, "SELECT template_type, template_type, null FROM email_templates WHERE template_type NOT IN ('INITIAL_MARKETING_EMAIL', 'REMINDER_INITIAL_CONTACT', 'LEVEL3_THANKS_BOOKING', 'LEVEL4_THANKS_BOOKING');");
                        if(SOURCE_LOCAL)
                            $email_templates = DAO::getResultset($link, "SELECT template_type, template_type, null FROM email_templates;");
                    }
                    array_unshift($email_templates, array('','Email template:',''));
                    $ddlTemplates =  HTML::selectChosen('frmEmailTemplate', $email_templates, '', false);
                    $from_email = $_SESSION['user']->work_email == '' ? SystemConfig::getEntityValue($link, 'onboarding_email') : $_SESSION['user']->work_email;

                    $html = <<<HTML
<form name="frmEmail" id="frmEmail" action="do.php?_action=ajax_actions" method="post">
	<input type="hidden" name="subaction" value="sendEmail" />
	<input type="hidden" name="frmEmailEntityType" value="learner" />
	<div class="box box-primary">
		<div class="box-header with-border"><h2 class="box-title">Compose New Email</h2></div>
		<div class="box-body">
			<div class="form-group"><div class="row"> <div class="col-sm-8"> $ddlTemplates </div><div class="col-sm-4"> <span class="btn btn-sm btn-default" onclick="load_email_template_in_frmEmail();">Load template</span></div> </div></div>
			<div class="form-group">To: <input name="frmEmailTo" id="frmEmailTo" class="form-control compulsory" placeholder="To:" value=""></div>
			<div class="form-group">Subject: <input name="frmEmailSubject" id="frmEmailSubject" class="form-control compulsory" placeholder="Subject:"></div>
			<div class="form-group"><textarea name="frmEmailBody" id="frmEmailBody" class="form-control compulsory" style="height: 300px"></textarea></div>
		</div>
		<div class="box-footer">
			<div class="pull-right"><span class="btn btn-primary" onclick="sendEmail();"><i class="fa fa-envelope-o"></i> Send</span></div>
			<span class="btn btn-default" onclick="$('#btnCompose').show(); $('#mailBox').show(); $('#composeNewMessageBox').hide();"><i class="fa fa-times"></i> Discard</span>
		</div>
	</div>
</form>
HTML;

                    echo $html;

                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/summernote/summernote.min.js"></script>

<script language="JavaScript">

    $(function(){

        $('#frmEmailBody').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link', 'hr']]
            ],
            height: 300,
            callbacks: {
                onImageUpload: function(files, editor, welEditable) {
                    sendFile(files[0], editor, welEditable);
                }
            }
        });

    });

    function sendEmail()
    {
        var frmEmail = document.forms["frmEmail"];
        if(!validateForm(frmEmail))
        {
            return;
        }

        var client = ajaxPostForm(frmEmail);
        if(client)
        {
            if(client.responseText == 'success')
                alert('Email has been sent successfully.');
            else
                alert('Unknown Email Error: Email has not been sent.');
        }
        else
        {
            alert(client);
        }
        window.location.reload();
    }

    function load_email_template_in_frmEmail()
    {
        var frmEmail = document.forms["frmEmail"];
        var employer_id = '<?php echo $vo->id; ?>';
        var email_template_type = frmEmail.frmEmailTemplate.value;

        if(email_template_type == '')
        {
            alert('Please select template from templates list');
            frmEmail.frmEmailTemplate.focus();
            return false;
        }

        function loadAndPrepareEmailTemplateCallback(client)
        {
            if(client.status == 200)
                $("#frmEmailBody").summernote("code", client.responseText);
        }

        var client = ajaxRequest('do.php?_action=ajax_actions&subaction=loadAndPrepareEmailTemplate' +
            '&entity_type=pool&entity_id=' + employer_id +
            '&template_type=' + email_template_type, null, null, loadAndPrepareEmailTemplateCallback);
    }

    function frmEmailTemplate_onchange(template)
    {
        if(template.value == "EMPLOYER_TNA")
        {
            var client_name = '<?php echo $client_name = SystemConfig::getEntityValue($link, "client_name"); ?>';
            document.forms["frmEmail"].frmEmailSubject.value = client_name + " - Training Needs Analysis";
        }
    }

</script>

</body>
</html>