<?php /* @var $vo Subcontractor */ ?>
<?php /* @var $link PDO */ ?>

<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis: View Subcontractor</title>
    <link rel="stylesheet" href="css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

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
            <div class="Title" style="margin-left: 6px;">View Subcontractor</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <span class="btn btn-sm btn-default" onclick="window.location.href='do.php?_action=edit_subcontractor&edit=1&id=<?php echo $vo->id; ?>';"><i class="fa fa-edit"></i> Edit</span>
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
                    <div class="pull-right">
                        <span class="label <?php echo $vo->active == '1'?'label-success':'label-danger'; ?>"><?php echo $vo->active=='1'?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> Active</span>
                        <span class="label <?php echo $vo->health_safety == '1'?'label-success':'label-danger'; ?>"><?php echo $vo->health_safety=='1'?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> Health and Safety</span>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <dl class="dl-horizontal">
                                <dt>UKPRN:</dt><dd><span class="text-muted"><?php echo $vo->ukprn; ?></span></dd>
                                <dt>Company Number:</dt>
                                <dd>
									<span class="text-muted">
										<a href="https://beta.companieshouse.gov.uk/company/<?php echo $vo->company_number; ?>" target="_blank"><?php echo $vo->company_number; ?></a>
									</span>
                                </dd>
                                <dt>VAT Number:</dt><dd><span class="text-muted"><?php echo $vo->vat_number; ?></span></dd>
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
                    <li class="active"><a href="#tabLocations" data-toggle="tab">Locations <label class="label label-info"><?php echo $locations_count; ?></label></a></li>
                    <li><a href="#tabUsers" data-toggle="tab">System Users <label class="label label-info"><?php echo $users_count; ?></label></a></li>
                    <li><a href="#tabCRMNotes" data-toggle="tab">CRM Notes <label class="label label-info"><?php echo $crm_notes_count; ?></label></a></li>
                    <li><a href="#tabCRMContacts" data-toggle="tab">CRM Contact <label class="label label-info"><?php echo $crm_contacts_count; ?></label></a></li>
                    <li><a href="#tabFiles" data-toggle="tab"> File Repository <label class="label label-info"><?php echo $files_count; ?></label></a></li>
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="tabLocations">
                        <p><span onclick="window.location.href='do.php?_action=edit_location&id=&organisations_id=<?php echo $vo->id; ?>'" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Add New Location</span></p>
                        <div class=""><?php $this->renderLocations($link,'read_Subcontractor'); ?></div>
                    </div>
                    <div class="tab-pane" id="tabUsers">
                        <p><span onclick="window.location.href='do.php?_action=add_system_user&org_id=<?php echo $vo->id; ?>'" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Add New User</span></p>
                        <div class=""><?php $this->renderSystemUsers($link); ?></div>
                    </div>
                    <div class="tab-pane" id="tabCRMNotes">
                        <p><span onclick="window.location.href='do.php?_action=edit_org_crm_note&organisations_id=<?php echo $vo->id; ?>'" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Add New CRM Note</span></p>
                        <div class="table-responsive"><?php $this->renderCRMNotes($link,'read_Subcontractor'); ?></div>
                    </div>
                    <div class="tab-pane" id="tabCRMContacts">
                        <p><span onclick="window.location.href='do.php?_action=edit_crm_contact&org_type=subcontractor&org_id=<?php echo $vo->id; ?>'" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Add New CRM Contact</span></p>
                        <div class=""><?php $this->renderCRMContacts($link); ?></div>
                    </div>
                    <div class="tab-pane" id="tabFiles">
                        <p><br></p>

                        <div class="row">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-4">
                                <div class="box">
                                    <div class="box-body">
                                        <form name="frmUploadFile" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=save_employer_repository" ENCTYPE="multipart/form-data">
                                            <input type="hidden" name="_action" value="save_organisation_repository" />
                                            <input type="hidden" name="organisation_id" value="<?php echo $vo->id;?>" />
                                            <input class="compulsory" type="file" name="uploaded_organisation_file" />
                                            <span id="uploadFileButton" class="btn btn-sm btn-primary pull-right" onclick="uploadFile();"><i class="fa fa-upload"></i></span>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=""><?php echo $this->renderFileRepository($link, $vo); ?></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div id="dialogDeleteFile" style="display:none" title="Delete file"></div>

    <div id="dialogDeleteRecord" style="display:none" title="Delete Record"></div>

</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.js"></script>
<script src="/assets/adminlte/plugins/summernote/summernote.min.js"></script>

<script>
    $(function() {
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

        $('#dialogDeleteRecord').dialog({
            modal: true,
            width: 450,
            closeOnEscape: true,
            autoOpen: false,
            resizable: false,
            draggable: false,
            buttons: {
                'Delete': function() {
                    var record = $(this).data('record');
                    var client = ajaxRequest('do.php?_action=delete_record_from_org&'+$.param(record));
                    if(client)
                    {
                        $(this).dialog('close');
                        $('<div>'+client.responseText+'</div>').dialog({
                            title: 'Deletion result',
                            buttons: {
                                'OK': function() {
                                    $( this ).dialog( "close" );
                                    window.location.reload();
                                }
                            }
                        });
                    }
                    else
                    {
                        alert(client);
                    }
                },
                'Cancel': function() {$(this).dialog('close');}
            }
        });

        $("#txtSearchLearners").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".divLearners .col-sm-3").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
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

    function uploadFile()
    {
        var myForm = document.forms["frmUploadFile"];
        if(validateForm(myForm) == false)
        {
            return false;
        }
        myForm.submit();
    }

    function deleteRecord(record_type, record_username, record_id, record_name)
    {
        var record = {};
        record["record_type"] = record_type;
        record["record_username"] = record_username;
        record["record_id"] = record_id;

        var $dialog = $('#dialogDeleteRecord');

        $dialog.data('record', record);

        $dialog.html('<p><b>'+record_type.replace('_', ' ').toUpperCase()+'</b><br>'+record_name+' ('+record_username+')</p>' + '<p>Deletion is permanent and irrecoverable.  Continue?</p>');

        $dialog.dialog("open");
    }

</script>
</body>
</html>
