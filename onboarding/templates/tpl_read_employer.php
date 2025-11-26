<?php /* @var $vo Employer */ ?>
<?php /* @var $link PDO */ ?>

<!DOCTYPE html>

<head xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis: View Employer</title>
    <link rel="stylesheet" href="css/common.css" type="text/css" />
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote-bs3.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        @media screen and (min-width: 768px) {
            .modal-dialog {
                width: 700px;
                /* New width for default modal */
            }

            .modal-sm {
                width: 350px;
                /* New width for small modal */
            }
        }

        @media screen and (min-width: 992px) {
            .modal-lg {
                width: 950px;
                /* New width for large modal */
            }
        }

        .row.is-flex {
            display: flex;
            flex-wrap: wrap;
        }

        .row.is-flex>[class*='col-'] {
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
                <div class="Title" style="margin-left: 6px;">View Employer</div>
                <div class="ButtonBar">
                    <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                    <span class="btn btn-xs btn-default" onclick="window.location.href='do.php?_action=edit_employer&edit=1&id=<?php echo $vo->id; ?>';"><i class="fa fa-edit"></i> Edit</span>
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
            <div class="col-sm-3">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="box-title text-bold">
                            <?php echo $vo->legal_name; ?>
                        </h1> / <small><?php echo $vo->trading_name; ?></small>
                        <?php
                        echo '<span style="display: inline;"> ';
                        $trophy = $vo->company_rating;
                        if ($trophy == 'G')
                            echo '<i title="GOLD Employer" class="fa fa-trophy fa-2x" style="color: gold;"></i>';
                        elseif ($trophy == 'S')
                            echo '<i title="Silver Employer" class="fa fa-trophy fa-2x" style="color: silver;"></i>';
                        elseif ($trophy == 'B')
                            echo '<i title="Bronze Employer" class="fa fa-trophy fa-2x" style="color: #cd7f32;"></i>';
                        echo '</span>';
                        ?>
                        <div class="pull-right">
                            <span class="label <?php echo $vo->active == '1' ? 'label-success' : 'label-danger'; ?>"><?php echo $vo->active == '1' ? '<span class="fa fa-check"></span>' : '<span class="fa fa-close"></span>'; ?> Active</span>
                            <span class="label <?php echo $vo->levy_employer == '1' ? 'label-success' : 'label-danger'; ?>"><?php echo $vo->levy_employer == '1' ? '<span class="fa fa-check"></span>' : '<span class="fa fa-close"></span>'; ?> Levy Employer</span>
                            <?php 
                            if(in_array(DB_NAME, ["am_ela", "am_eet", "am_am"]))
                            {
                                if( isset($health_safety->id) )
                                {
                                    echo 
                                        ($health_safety->employer_sign != '' && $health_safety->provider_sign != '') ?
                                        '<span class="label label-success"><span class="fa fa-check"></span> Health and Safety</span>' :
                                            ($health_safety->employer_sign == '' && $health_safety->provider_sign == '' ? 
                                                '<span class="label label-danger"><span class="fa fa-close"></span> Health and Safety</span>' : 
                                                '<span class="label label-warning"><span class="fa fa-check"></span> Health and Safety</span>'
                                            );
                                }
                                else
                                {
                                    echo '<span class="label label-danger"><span class="fa fa-close"></span> Health and Safety</span>';
                                }
                            }
                            else
                            {
                                echo $vo->health_safety == '1' ? 
                                    '<span class="label label-success"><span class="fa fa-check"></span> Health and Safety</span>': 
                                    '<span class="label label-danger"><span class="fa fa-close"></span> Health and Safety</span>';
                            }
			    echo '&nbsp;';
                            if( isset($employer_agreement->id) )
                            {
                                echo 
                                    ($employer_agreement->employer_sign != '' && $employer_agreement->provider_sign != '') ?
                                    '<span class="label label-success"><span class="fa fa-check"></span> Employer Agreement</span>' :
                                        ($employer_agreement->employer_sign == '' && $employer_agreement->provider_sign == '' ? 
                                            '<span class="label label-danger"><span class="fa fa-close"></span> Employer Agreement</span>' : 
                                            '<span class="label label-warning"><span class="fa fa-check"></span> Employer Agreement</span>'
                                        );
                            }
                            else
                            {
                                echo '<span class="label label-danger"><span class="fa fa-close"></span> Employer Agreement</span>';
                            }
                            ?>
                            <!-- <span class="label <?php echo (isset($employer_agreement->status) && $employer_agreement->status >= EmployerAgreement::TYPE_SIGNED_BY_EMPLOYER) ? 'label-success' : 'label-danger'; ?>"><?php echo (isset($employer_agreement->status) && $employer_agreement->status >= EmployerAgreement::TYPE_SIGNED_BY_EMPLOYER) ? '<span class="fa fa-check"></span>' : '<span class="fa fa-remove"></span>'; ?> Employer Agreement</span>                             -->
                        </div>
                    </div>
                    <div class="box-body">
                        <dl class="dl-vertical">
                            <dt>EDRS:</dt>
                            <dd><span class="text-muted"><?php echo $vo->edrs; ?></span></dd>
                            <dt>Company Number:</dt>
                            <dd>
                                <span class="text-muted">
                                    <a href="https://beta.companieshouse.gov.uk/company/<?php echo $vo->company_number; ?>" target="_blank"><?php echo $vo->company_number; ?></a>
                                </span>
                            </dd>
                            <dt>VAT Number:</dt>
                            <dd><span class="text-muted"><?php echo $vo->vat_number; ?></span></dd>
                            <dt>Retailer Code:</dt>
                            <dd><span class="text-muted"><?php echo $vo->retailer_code; ?></span></dd>
                            <dt>Employer Code:</dt>
                            <dd><span class="text-muted"><?php echo $vo->employer_code; ?></span></dd>
                            <dt>Sector:</dt>
                            <dd><span class="text-muted"><?php echo htmlspecialchars($sector ?? ''); ?></span></dd>
                            <dt>Region:</dt>
                            <dd><span class="text-muted"><?php echo htmlspecialchars($vo->region ?? ''); ?></span></dd>
                            <dt>Size:</dt>
                            <dd><span class="text-muted"><?php echo htmlspecialchars($size ?? ''); ?></span></dd>
                            <dt>Total number of employees:</dt>
                            <dd><span class="text-muted"><?php echo htmlspecialchars($vo->site_employees ?? ''); ?></span></dd>
                            <dt>Employer Type:</dt>
                            <dd><span class="text-muted"><?php echo $vo->employer_type != '' ? LookupHelper::getListEmployerType($vo->employer_type) : ''; ?></span></dd>
                            <dt>Funding Type:</dt>
                            <dd><span class="text-muted"><?php echo $vo->funding_type != '' ? LookupHelper::getListFundingType($vo->funding_type) : ''; ?></span></dd>
                            <?php if ($vo->levy_employer == '1') { ?>
                                <dt>Levy Amount:</dt>
                                <dd><span class="text-muted"><?php echo $vo->levy; ?></span></dd>
                            <?php } ?>
                            <dt>URL:</dt>
                            <dd><span class="text-muted"><small><?php echo htmlspecialchars($vo->url ?? ''); ?></small></span></dd>
                            <dt>Created By:</dt>
                            <dd><span class="text-muted"><?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.username = '{$vo->creator}'"); ?></span></dd>
                        </dl>
                    </div>
                </div>

            </div>

            <div class="col-sm-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tabLocations" data-toggle="tab">Locations <label class="label label-info"><?php echo $locations_count; ?></label></a></li>
                        <!--</label></a></li>-->
                        <li><a href="#tabCRMNotes" data-toggle="tab">CRM Notes <label class="label label-info"><?php echo $crm_notes_count; ?></label></a></li>
                        <li><a href="#tabCRMContacts" data-toggle="tab">CRM Contact <label class="label label-info"><?php echo $crm_contacts_count; ?></label></a></li>
                        <li><a href="#tabFiles" data-toggle="tab"> File Repository <label class="label label-info"><?php echo $files_count; ?></label></a></li>
                        <li><a href="#tabAgreement" data-toggle="tab"> Agreement <label class="label label-info"><?php echo $agreements_count; ?></label></a></li>
                        <?php if(SOURCE_LOCAL || in_array(DB_NAME, ["am_ela", "am_demo", "am_eet", "am_am"])) { ?>
                        <li><a href="#tabHs" data-toggle="tab"> Health & Safety <label class="label label-info"><?php echo $hs_count; ?></label></a></li>
                        <?php } ?>
                        <li><a href="#tabObLearners" data-toggle="tab"> Onboarding Learners <label class="label label-info"><?php echo $ob_learners_count; ?></label></a></li>
			<li><a href="#tabEmails" data-toggle="tab"> Sent Emails <label class="label label-info"><?php echo $sent_emails_count; ?></label></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="tabLocations">
                            <p><span onclick="window.location.href='do.php?_action=edit_location&id=&organisations_id=<?php echo $vo->id; ?>'" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Add New Location</span></p>
                            <div class=""><?php $this->renderLocations($link, 'read_employer_v3'); ?></div>
                        </div>
                        <div class="tab-pane" id="tabCRMNotes">
                            <p><span onclick="window.location.href='do.php?_action=edit_org_crm_note&organisations_id=<?php echo $vo->id; ?>'" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Add New CRM Note</span></p>
                            <div class="table-responsive"><?php $this->renderCRMNotes($link, 'read_employer'); ?></div>
                        </div>
                        <div class="tab-pane" id="tabCRMContacts">
                            <p><span onclick="window.location.href='do.php?_action=edit_crm_contact&org_type=employer&org_id=<?php echo $vo->id; ?>'" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Add New CRM Contact</span></p>
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
                                                <input type="hidden" name="organisation_id" value="<?php echo $vo->id; ?>" />
                                                <table class="table table-responsive">
                                                    <tr>
                                                        <td colspan="2"><input class="compulsory" type="file" name="uploaded_organisation_file" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <span id="uploadFileButton" class="btn btn-xs btn-primary" onclick="uploadFile();"><i class="fa fa-upload"></i> Click to Upload</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class=""><?php echo $this->renderFileRepository($link, $vo); ?></div>
                        </div>
                        <div class="tab-pane" id="tabAgreement">
                            <p>
                                <span onclick="createNewAgreement();" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Create New Agreement</span>
                            </p>
                            <div class="table-responsive">
                                <p><?php echo $this->renderAgreements($link, $vo); ?></p>
                            </div>
                        </div>
                        <?php if(SOURCE_LOCAL || in_array(DB_NAME, ["am_ela", "am_demo", "am_eet", "am_am"])) { ?>
                        <div class="tab-pane" id="tabHs">
                            <p>
                                <span onclick="createNewHs();" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Create New Health & Safety Assessment</span>
                            </p>
                            <div class="table-responsive">
                                <p><?php echo $this->renderHs($link, $vo); ?></p>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="tab-pane" id="tabObLearners">
                            <p>
                                <span onclick="window.location.href='do.php?_action=add_edit_ob_learners&employer_id=<?php echo $vo->id; ?>'" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Create New Onboarding Learner</span>
                            </p>
                            <p><input id="txtSearchObLearners" type="text" placeholder="Search.."></p>
                            <div class="divObLearners"><?php $this->renderObLearners($link); ?></div>
                        </div>
			<div class="tab-pane" id="tabEmails">
                            <p></p>
                            <?php echo $this->showSentEmails($link, $vo); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div id="dialogDeleteFile" style="display:none" title="Delete file"></div>

        <div id="dialogDeleteRecord" style="display:none" title="Delete Record"></div>

        <div class="modal fade" id="emailModal" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h5 class="modal-title text-bold">Email Editor</h5>
                    </div>
                    <div class="modal-body">
                        <form autocomplete="0" class="form-horizontal" method="post" name="frmEmail" id="frmEmail" method="post" action="do.php">
                            <input type="hidden" name="_action" value="ajax_email_actions" />
                            <input type="hidden" name="subaction" value="sendEmail" />
                            <input type="hidden" name="frmEmailEntityType" value="organisations" />
                            <input type="hidden" name="frmEmailEntityId" value="<?php echo $vo->id; ?>" />
                            <input type="hidden" name="agreement_id" value="" />
                            <input type="hidden" name="hs_id" value="" />
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="control-group"><label class="control-label" for="frmEmailTo">To:</label>
                                        <input autocomplete="off" type="text" name="frmEmailTo" id="frmEmailTo" class="form-control compulsory" value="<?php echo $primary_contact_email; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="control-group"><label class="control-label" for="frmEmailSubject">Subject:</label>
                                        <input autocomplete="0" type="text" name="frmEmailSubject" id="frmEmailSubject" class="form-control compulsory" value="Employer Agreement<?php echo DB_NAME == "am_ela" ? " - ELA Training" : ""; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="frmEmailBody">Message:</label>
                                <textarea name="frmEmailBody" id="frmEmailBody" class="form-control"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left btn-md" onclick="$('#emailModal').modal('hide');">Cancel</button>
                        <button type="button" id="btnEmailModalSave" class="btn btn-primary btn-md"><i class="fa fa-send"></i> Send</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/adminlte/dist/js/app.min.js"></script>
    <script src="js/common.js" type="text/javascript"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.js"></script>
    <script src="/assets/adminlte/plugins/summernote/summernote.min.js"></script>

    <script>
        $(function() {
            $('#frmEmailBody').summernote({
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['fontsize', ['fontsize']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['link', 'picture', 'hr']]
                ],
                height: 250,
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
                        if (client) {
                            window.location.reload();
                        }
                    },
                    'Cancel': function() {
                        $(this).dialog('close');
                    }
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
                        var client = ajaxRequest('do.php?_action=delete_record_from_org&' + $.param(record));
                        if (client) {
                            $(this).dialog('close');
                            $('<div>' + client.responseText + '</div>').dialog({
                                title: 'Deletion result',
                                buttons: {
                                    'OK': function() {
                                        $(this).dialog("close");
                                        window.location.reload();
                                    }
                                }
                            });
                        } else {
                            alert(client);
                        }
                    },
                    'Cancel': function() {
                        $(this).dialog('close');
                    }
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

            $("button#btnEmailModalSave").click(function() {
                var frmEmail = document.forms['frmEmail'];
                var agreement_id = frmEmail.agreement_id.value;

                if (!validateForm(frmEmail)) {
                    return;
                }

                var client1 = ajaxPostForm(document.forms['frmEmail']);
                if (client1 && client1.responseText == 'success') {
                    var client2 = ajaxRequest('do.php?_action=ajax_helper&subaction=updateEmployerAgreementStatus&agreement_id=' + agreement_id + '&status=<?php echo EmployerAgreement::TYPE_SENT; ?>');
                    if (client2 && client2.status == 200) {
                        window.location.reload();
                    }
                }
            });

        });

        function deleteFile(path) {
            var $dialog = $('#dialogDeleteFile');

            $dialog.data('filepath', path);

            var filename = path.split('/').pop();
            $dialog.html("<p>Delete <b>" + filename + "</b>.</p><p>Deletion is permanent and irrecoverable.  Continue?</p>");

            $dialog.dialog("open");
        }

        function uploadFile() {
            var myForm = document.forms["frmUploadFile"];
            if (validateForm(myForm) == false) {
                return false;
            }
            myForm.submit();
        }

        function deleteRecord(record_type, record_username, record_id, record_name) {
            var record = {};
            record["record_type"] = record_type;
            record["record_username"] = record_username;
            record["record_id"] = record_id;

            var $dialog = $('#dialogDeleteRecord');

            $dialog.data('record', record);

            $dialog.html('<p><b>' + record_type.replace('_', ' ').toUpperCase() + '</b><br>' + record_name + ' (' + record_username + ')</p>' + '<p>Deletion is permanent and irrecoverable.  Continue?</p>');

            $dialog.dialog("open");
        }

        function load_and_prepare_agreement_email(agreement_id) {
            var frmEmail = document.forms["frmEmail"];
            frmEmail.agreement_id.value = agreement_id;
	    frmEmail.frmEmailSubject.value = "Employer Agreement<?php echo DB_NAME == "am_ela" ? " - ELA Training" : ""; ?>";

            function getEmployerAgreementTemplateCallback(client) {
                if (client.status == 200) {
                    $("#frmEmailBody").summernote("code", client.responseText);
                }
                $('#emailModal').modal('show');
            }

            var client = ajaxRequest('do.php?_action=ajax_email_actions&subaction=getEmployerAgreementTemplate' +
                '&agreement_id=' + agreement_id, null, null, getEmployerAgreementTemplateCallback);
        }

        function load_and_prepare_hs_email(hs_id) {
            var frmEmail = document.forms["frmEmail"];
            frmEmail.hs_id.value = hs_id;
	    frmEmail.frmEmailSubject.value = "Health and Safety Agreement<?php echo DB_NAME == "am_ela" ? " - ELA Training" : ""; ?>";

            function getEmployerHsTemplateCallback(client) {
                if (client.status == 200) {
                    $("#frmEmailBody").summernote("code", client.responseText);
                }
                $('#emailModal').modal('show');
            }

            var client = ajaxRequest('do.php?_action=ajax_email_actions&subaction=getEmployerHsTemplate' +
                '&hs_id=' + hs_id, null, null, getEmployerHsTemplateCallback);
        }

        function createNewAgreement() {
            window.location.href = 'do.php?_action=edit_employer_agreement&id=&employer_id=<?php echo $vo->id; ?>';
        }

        function createNewSchedule() {
            window.location.href = 'do.php?_action=edit_employer_schedule1&id=&employer_id=<?php echo $vo->id; ?>';
        }

        function deleteAgreement() {
            if (!confirm("This action is irreversible, are you sure you want to continue?"))
                return false;


        }

        function downloadAgreement(id) {
            window.location.href = "do.php?_action=generate_pdf&subaction=employerAgreement&id=" + id;
        }

	function download_hs_form_pdf(id) {
            window.location.href = "do.php?_action=generate_pdf&subaction=employerHsForm&id=" + id;
        }

        function createNewHs()
        {
            window.location.href = 'do.php?_action=edit_employer_hs&employer_id=<?php echo $vo->id; ?>';
        }

	function viewEmail(email_id) 
        {
            if (email_id == "") 
            {
                return;
            }
            
            var postData = "do.php?_action=ajax_email_actions&subaction=" + encodeURIComponent("getEmail") + "&email_id=" + encodeURIComponent(email_id);
            var req = ajaxRequest(postData);

            $("<div class='small'></div>").html(req.responseText).dialog({
                id: "dialogEmailView",
                title: "Email",
                resizable: false,
                modal: true,
                width: 750,
                height: 500,
                
                buttons: {
                    Close: function () {
                        $(this).dialog("close");
                    },
                },
            });
        }

    </script>
</body>

</html>