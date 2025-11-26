<?php /* @var $link PDO */ ?>

<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Pool Organisation</title>
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
        .row.is-flex {
            display: flex;
            flex-wrap: wrap;
        }
        .row.is-flex > [class*='col-'] {
            display: flex;
            flex-direction: column;
        }
        .tooltip {
            position: relative;
            display: inline-block;
        }


    </style>

</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">View Pool Organisation</div>
            <div class="ButtonBar">
                <span class="btn btn-xs btn-default" onclick="window.location.href='do.php?_action=view_orgs'"><i class="fa fa-arrow-circle-o-left"></i> Back to Companies</span>
                <span class="btn btn-xs btn-default" onclick="window.location.href='do.php?_action=edit_pool_organisation&edit=1&id=<?php echo $vo->id; ?>';"><i class="fa fa-edit"></i> Edit</span>
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

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h1 class="box-title text-bold">
                        <?php echo $vo->legal_name; ?>
                    </h1> / <small><?php echo $vo->trading_name; ?></small>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <dl class="dl-horizontal">
                                <dt>Trading Name:</dt><dd><span class="text-muted"><?php echo $vo->trading_name; ?></span></dd>
                                <dt>System ID:</dt><dd><span class="text-muted"><?php echo $vo->id; ?></span></dd>
                                <dt>Client ID:</dt><dd><span class="text-muted"><?php echo $vo->client_id; ?></span></dd>
                                <dt>Company Number:</dt><dd><span class="text-muted"><?php echo $vo->company_number; ?></span></dd>
                                <dt>Number of employees:</dt><dd><span class="text-muted"><?php echo $vo->site_employees; ?></span></dd>
                                <dt>Credit Rating:</dt><dd><span class="text-muted"><?php echo $vo->credit_rating; ?></span></dd>
                                <dt>Credit Limit:</dt><dd><span class="text-muted"><?php echo $vo->credit_limit; ?></span></dd>
                            </dl>
                        </div>
                        <div class="col-md-4">
                            <dl class="dl-horizontal">
                                <dt>Incorporation Date:</dt><dd><span class="text-muted"><?php echo Date::toShort($vo->incorporation_date); ?></span></dd>
                                <dt>Annual Turnover:</dt><dd><span class="text-muted"><?php echo $vo->annual_turnover; ?></span></dd>
                                <dt>Net Worth:</dt><dd><span class="text-muted"><?php echo $vo->net_worth; ?></span></dd>
                            </dl>
                        </div>
                        <div class="col-md-4">
                            <dl class="dl-horizontal">
                                <dt>Website:</dt><dd><span class="text-muted"><?php echo $vo->website; ?></span></dd>
                                <dt><i class="fa fa-linkedin-square"></i></dt><dd><?php echo $vo->linked_in_page != '' ? '<a href="' . $vo->linked_in_page . '" target="_blank">' . $vo->linked_in_page . '</a>' : ''; ?></dd>
                                <dt><i class="fa fa-twitter-square"></i></dt><dd><?php echo $vo->twitter_handle != '' ? '<a href="https://twitter.com/' . $vo->twitter_handle . '" target="_blank">' . $vo->twitter_handle . '</a>' : ''; ?></dd>
                                <dt><i class="fa fa-facebook-square"></i></dt><dd><?php echo $vo->facebook_page != '' ? '<a href="' . $vo->facebook_page . '" target="_blank">' . $vo->facebook_page . '</a>' : ''; ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-sm-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tabEnquiries" data-toggle="tab">Enquiries <label class="label label-info"><?php echo $enquiries_count; ?></label></a></li>
                    <li><a href="#tabLeads" data-toggle="tab">Leads <label class="label label-info"><?php echo $leads_count; ?></label></a></li>
                    <li><a href="#tabOpportunities" data-toggle="tab">Opportunities <label class="label label-info"><?php echo $opportunities_count; ?></label></a></li>
                    <li><a href="#tabLocations" data-toggle="tab">Locations <label class="label label-info"><?php echo $locations_count; ?></label></a></li>
                    <li><a href="#tabPoolContacts" data-toggle="tab"> Contacts <label class="label label-info"><?php echo $contacts_count; ?></label></a></li>
                    <li><a href="#tabFiles" data-toggle="tab"> File Repository <label class="label label-info"><?php echo $files_count; ?></label></a></li>
		            <li><a href="#tabExtraInfo" data-toggle="tab"> Additional Information</a></li>
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="tabEnquiries">
                        <p>
                            <span onclick="window.location.href='do.php?_action=edit_enquiry&org_id=<?php echo $vo->id; ?>&org_type=pool'" class="btn btn-primary btn-xs">
                                <i class="fa fa-plus"></i> Add New Enquiry
                            </span>
                        </p>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php $this->renderEnquiries($link, $vo->id); ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tabLeads">
                        <p>
                            <span onclick="window.location.href='do.php?_action=edit_lead&org_id=<?php echo $vo->id; ?>&org_type=pool'" class="btn btn-primary btn-xs">
                                <i class="fa fa-plus"></i> Add New Lead
                            </span>
                        </p>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php $this->renderLeads($link, $vo->id); ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tabOpportunities">

                        <div class="row">
                            <div class="col-sm-12">
                                <?php $this->renderOpportunities($link, $vo->id); ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tabLocations">
                        <p>
                            <span onclick="window.location.href='do.php?_action=edit_pool_location&pool_id=<?php echo $vo->id; ?>&back=pool'" class="btn btn-primary btn-xs">
                                <i class="fa fa-plus"></i> Add New Location
                            </span>
                        </p>
                        <div class=""><?php $this->renderLocations($link,'read_employer_v3'); ?></div>
                    </div>
                    <?php if(SystemConfig::getEntityValue($link, 'module_crm')) {?>
                        <div class="tab-pane" id="tabPoolContacts">
                            <p>
                                <span onclick="window.location.href='do.php?_action=edit_pool_contact&pool_id=<?php echo $vo->id; ?>&contact_id=&org_type=pool'" class="btn btn-primary btn-xs">
                                    <i class="fa fa-plus"></i> Add New Contact
                                </span>
                            </p>
                            <div class=""><?php $this->renderCrmContacts($link); ?></div>
                        </div>
                    <?php } ?>
                    <div class="tab-pane" id="tabFiles">
                        <p><br></p>

                        <div class="row">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-4">
                                <div class="box">
                                    <div class="box-body">
                                        <form name="frmUploadFile" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=save_pool_repository" ENCTYPE="multipart/form-data">
                                            <input type="hidden" name="_action" value="save_pool_repository" />
                                            <input type="hidden" name="pool_id" value="<?php echo $vo->id;?>" />
                                            <input class="compulsory" type="file" name="uploaded_pool_file" />
                                            <span id="uploadFileButton" class="btn btn-sm btn-primary pull-right" onclick="uploadFile();"><i class="fa fa-upload"></i></span>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=""><?php echo $this->renderFileRepository($link, $vo); ?></div>
                    </div>        
		            <?php if(SystemConfig::getEntityValue($link, 'module_crm')) {?>
                        <div class="tab-pane" id="tabExtraInfo">
                            <div class="col-sm-12">
                                <span class="lead text-bold">Additional Information</span>
                            </div>
                            <div class="row">
                                <?php
                                $records = DAO::getResultset($link, "SELECT * FROM pool_extra_info WHERE id = '{$vo->id}'", DAO::FETCH_ASSOC);
                                foreach($records AS $row)
                                {
                                    $detail = json_decode($row['detail']);
                                    foreach($detail AS $key => $value)
                                    {
                                        echo '<br><span class="text-bold text-md">' . $key . ': </span>';
                                        echo '<span>' . nl2br($value) . '</span>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

    </div>

</div>

<div id="dialogDeleteFile" style="display:none" title="Delete file"></div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.js"></script>
<script src="/assets/adminlte/plugins/summernote/summernote.min.js"></script>

<script>
    $(function() {
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

        $('#dialogDeleteFile').dialog({
            modal: true,
            width: 450,
            closeOnEscape: true,
            autoOpen: false,
            resizable: false,
            draggable: false,
            buttons: {
                'Delete': function() {
                    $(this).dialog('close');
                    var client = ajaxRequest('do.php?_action=delete_file&f=' + encodeURIComponent($(this).data('filepath')));
                    if(client){
                        window.location.reload();
                    }
                },
                'Cancel': function() {$(this).dialog('close');}
            }
        });

    });

    function deleteFile(path)
    {
        var $dialog = $('#dialogDeleteFile');

        $dialog.data('filepath', path);

        var filename = path.split('/').pop();
        $dialog.html("<p>Delete <b>" + filename + "</b>.</p><p>Deletion is permanent and irrecoverable.  Continue?</p>");

        $dialog.dialog("open");
    }

    function sendFile(file, editor, welEditable)
    {
        data = new FormData();
        data.append("file", file);
        $.ajax({
            data: data,
            type: "POST",
            url: "do.php?_action=ajax_actions&subaction=uploadImageToEmailEditor",
            cache: false,
            contentType: false,
            processData: false,
            success: function(url) {
                //editor.insertImage(welEditable, url);
                $('#compose-textarea').summernote('editor.insertImage', url);
            }
        });
    }

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

    function viewEmail(tbl_emails_id)
    {
        var postData = 'do.php?_action=ajax_helper'
            + '&subaction=view_sent_email'
            + '&id=' + encodeURIComponent(tbl_emails_id)
        ;

        var req = ajaxRequest(postData);
        $("<div></div>").html(req.responseText).dialog({
            id: "dlg_lrs_result",
            title: "View Sent Email",
            resizable: false,
            modal: true,
            width: 750,
            height: 500,

            buttons: {
                'Close': function() {$(this).dialog('close');}
            }
        });

    }

    function uploadFile()
    {
        var myForm = document.forms["frmUploadFile"];
        if(validateForm(myForm) == false)
        {
            return false;
        }
        myForm.submit();
    }

</script>
</body>
</html>
