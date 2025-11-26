<?php /* @var $vo Department */ ?>
<?php /* @var $location Location */ ?>
<?php /* @var $link PDO */ ?>

<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis: View Department</title>
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
            <div class="Title" style="margin-left: 6px;">View Department</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <span class="btn btn-sm btn-default" onclick="window.location.href='do.php?_action=edit_department&edit=1&id=<?php echo $vo->id; ?>';"><i class="fa fa-edit"></i> Edit</span>
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
                        <?php echo $vo->company_number . ' - ' . $vo->legal_name; ?>
                    </h1>
                    <div class="pull-right">
                        <span class="label <?php echo $vo->active == '1'?'label-success':'label-danger'; ?>"><?php echo $vo->active=='1'?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> Active</span>
                        <span class="label <?php echo $vo->health_safety == '1'?'label-success':'label-danger'; ?>"><?php echo $vo->health_safety=='1'?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> Health and Safety</span>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            echo '<span class="text-bold text-blue">Address: </span><br>';
                            echo $location->address_line_1 != '' ? $location->address_line_1 . '<br>' : '';
                            echo $location->address_line_2 != '' ? $location->address_line_2 . '<br>' : '';
                            echo $location->address_line_3 != '' ? $location->address_line_3 . '<br>' : '';
                            echo $location->address_line_4 != '' ? $location->address_line_4 . '<br>' : '';
                            echo $location->postcode != '' ? '<i class="fa fa-map-marker"></i> ' . $location->postcode . '<br>' : '';
                            echo $location->telephone != '' ? '<i class="fa fa-phone"></i> ' . $location->telephone . '<br>' : '';
                            echo $location->fax != '' ? '<i class="fa fa-fax"></i> ' . $location->fax . '<br>' : '';
                            ?>
                        </div>
                        <div class="col-md-6">
                            <span class="text-bold text-blue">Delivery Partner: </span><br>
                            <?php echo DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$vo->delivery_partner}'"); ?>
                            <br>
                            <?php
                            echo '<span class="text-bold text-blue">Head of Department: </span><br>';
                            echo $location->contact_name != '' ? $location->contact_name . '<br>' : '';
                            echo $location->contact_email != '' ? '<i class="fa fa-envelope"></i> ' . $location->contact_email . '<br>' : '';
                            echo $location->contact_telephone != '' ? '<i class="fa fa-phone"></i> ' . $location->contact_telephone . '<br>' : '';
                            echo $location->contact_mobile != '' ? '<i class="fa fa-mobile"></i> ' . $location->contact_mobile . '<br>' : '';
                            ?>
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
                    <li class="active"><a href="#tabSubDepartments" data-toggle="tab">Sub Departments <label class="label label-info"><?php echo $subdepts_count; ?></label></a></li>
                    <li><a href="#tabCRMContacts" data-toggle="tab">CRM Contact <label class="label label-info"><?php echo $crm_contacts_count; ?></label></a></li>
                    <li><a href="#tabFiles" data-toggle="tab"> File Repository <label class="label label-info"><?php echo $files_count; ?></label></a></li>
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="tabSubDepartments">
                        <p><span onclick="window.location.href='do.php?_action=edit_sub_department&id=&organisations_id=<?php echo $vo->id; ?>'" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Add New Sub Department</span></p>
                        <div class=""><?php $this->renderSubDepartments($link,'read_department'); ?></div>
                    </div>
                    <div class="tab-pane" id="tabCRMContacts">
                        <p><span onclick="window.location.href='do.php?_action=edit_crm_contact&org_type=department&org_id=<?php echo $vo->id; ?>'" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Add New CRM Contact</span></p>
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
        $("#txtSearchObLearners").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#tblObLearners tr").filter(function() {
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
