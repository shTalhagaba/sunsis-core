<?php /* @var $session OperationsSession */ ?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Send Emails to Orgnaisations</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link href="/assets/adminlte/plugins/duallistbox/bootstrap-duallistbox.min.css" rel="stylesheet">

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
            <div class="Title" style="margin-left: 6px;">Send Emails to Orgnaisations</div>
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
            <div class="callout callout-info">
                <p><i class="fa fa-info-circle"></i> Use this functionality to send bulk emails to the organisations.</p>
                <p><i class="fa fa-info-circle"></i> You can select organisations from Pool (not previously dealt with) and Employers (previously successful opportunities).</p>
            </div>
        </div>
    </div>

    <?php if(isset($_SESSION['tpl_send_bulk_emails_message'])){ ?>
    <div class="row">
        <div class="col-sm-4 col-sm-offset-4" style="margin-top: 10px; margin-bottom: 15px;">
            <span class="alert alert-success"><?php echo $_SESSION['tpl_send_bulk_emails_message']; ?></span>
        </div>
    </div>
    <?php }?>

    <p><br></p>

    <div class="row">
        <div class="col-sm-12">
            <form role="form" class="form-horizontal" action="do.php" name="frmSendEmail">
                <input type="hidden" name="_action" value="send_bulk_emails">
                <input type="hidden" name="subaction" value="start_sending">
                <div class="form-group">
                    <label for="email_template_id" class="col-sm-4 control-label fieldLabel_compulsory">Select Template:</label>
                    <div class="col-sm-6">
                        <?php echo HTML::selectChosen('email_template_id', DAO::getResultset($link, "SELECT id, template_type FROM email_templates WHERE template_type IN ('INITIAL_MARKETING_EMAIL', 'REMINDER_INITIAL_CONTACT')"), '', true, true); ?>
                    </div>
                    <div class="col-sm-2">
                        <span class="btn btn-xs btn-info" onclick="preview_template();" title="Click to view the template"><i class="fa fa-eye"></i></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="organisations" class="col-sm-12 fieldLabel_compulsory">Select Organisations:</label>
                    <div class="col-sm-12">
                        <select class="form-control dual_select" name="organisations[]" id="organisations" multiple>
                            <?php
                            $sql = <<<HEREDOC
SELECT
	DISTINCT CONCAT('p', pool.id) AS org_id, CONCAT(legal_name, ' [Pool]') AS legal_name, 'Pool' AS category
FROM
	pool
	INNER JOIN pool_locations ON pool.id = pool_locations.pool_id

UNION ALL
SELECT
	DISTINCT CONCAT('e', employers.id) AS org_id, CONCAT(legal_name, ' [Employer]') AS legal_name, 'Employer' AS category
FROM
	organisations AS employers
	INNER JOIN locations ON employers.id = locations.`organisations_id`
WHERE employers.organisation_type = 2
ORDER BY
	category, legal_name
    ;
HEREDOC;
                            $organisations_list = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
                            foreach($organisations_list AS $organisation)
                            {
                                echo '<option value="' . $organisation['org_id'] . '">' . $organisation['legal_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <button name="send" type="button" onclick="sendEmails();" class="btn btn-primary btn-block">
                    <i class="fa fa-send"></i>
                    Send Email to Selected Organisations
                </button>

            </form>
        </div>
    </div>
</div>



<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/duallistbox/jquery.bootstrap-duallistbox.js"></script>

<script language="JavaScript">

    $(function(){
        $('.dual_select').bootstrapDualListbox({
            selectorMinimalHeight:500,
            preserveSelectionOnMove:'true'
        });
    });
    function preview_template()
    {
        var template_id = $("#email_template_id").val();
        if(template_id == '')
        {
            alert("Select the template form the drop down list.");
            return;
        }

        var postData = 'do.php?_action=ajax_helper'
            + '&subaction=preview_email_template'
            + '&template_id=' + encodeURIComponent(template_id)
        ;

        var req = ajaxRequest(postData);
        $("<div></div>").html(req.responseText).dialog({
            id: "dlg_lrs_result",
            title: "Preview Template",
            resizable: false,
            modal: true,
            width: 750,
            height: 500,

            buttons: {
                'Close': function() {$(this).dialog('close');}
            }
        });
    }

    function sendEmails()
    {
        var template_id = $("#email_template_id").val();
        if(template_id == '')
        {
            alert("Select the template form the drop down list.");
            return;
        }

        if($('#organisations').val() === null)
        {
            alert('You have not selected any organisation.');
            return;
        }

        $("form[name=frmSendEmail]").submit();

    }

</script>

</body>
</html>

<?php unset($_SESSION['tpl_send_bulk_emails_message']); ?>