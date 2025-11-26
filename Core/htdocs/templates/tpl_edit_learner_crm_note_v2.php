<?php /* @var $vo LearnerCrmNote */ ?>
<?php /* @var $tr TrainingRecord */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $vo->id == ''?'Create CRM Note':'Edit CRM Note'; ?></title>
    <link rel="stylesheet" href="module_tracking/css/common.css?n=<?php echo time(); ?>" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/jquery.timepicker.css" />
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <style>
        .disabled{
            pointer-events:none;
            opacity:0.4;
        }
	input[type=checkbox] {
			transform: scale(1.4);
		}
    </style>
</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;"><?php echo $vo->id == ''?'Create CRM Note':'Edit CRM Note'; ?></div>
            <div class="ButtonBar">
                <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <span class="btn btn-xs btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
                <?php if($vo->id != "" && $_SESSION['user']->isAdmin()) { ?>
                    <span class="btn btn-xs btn-danger" onclick="deleteNote();"><i class="fa fa-remove"></i> Delete</span>
                <?php } ?>
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
    <div class="col-sm-6">
        <div class="callout">
            <label class="col-sm-4 control-label fieldLabel_optional">Learner Name:</label>
            <div class="col-sm-8 text-bold"><?php echo $tr->firstnames . ' ' . $tr->surname;?></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="box box-primary callout">
            <form method="post" autocomplete="off" class="form-horizontal" name="frmLearnerCRMNote" id="frmLearnerCRMNote" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $vo->id; ?>" />
                <input type="hidden" name="tr_id" value="<?php echo $tr_id; ?>" />
                <input type="hidden" name="_action" value="save_learner_crm_note" />
                <div class="box-body with-border">
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label fieldLabel_compulsory">Person Contacted:</label>
                        <div class="col-sm-8 callout">
                            <div class="form-group">
                                <label for="name_of_person" class="col-sm-4 control-label fieldLabel_compulsory">Name:</label>
                                <div class="col-sm-8">
                                    <input class="form-control compulsory" type="text" name="name_of_person" id="name_of_person" value="<?php echo $vo->name_of_person == '' ? $tr->firstnames . ' ' . $tr->surname:$vo->name_of_person; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="position" class="col-sm-4 control-label fieldLabel_compulsory">Position:</label>
                                <div class="col-sm-8">
                                    <input class="form-control compulsory" type="text" name="position" id="position" value="<?php echo $vo->position == '' ? 'Learner':$vo->position; ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type_of_contact" class="col-sm-4 control-label fieldLabel_compulsory">Type of Contact:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('type_of_contact', $contact_type, $vo->type_of_contact, true, true); ?>
			    <?php if(!in_array(DB_NAME, ["am_baltic", "am_baltic_demo"])){ ?>
                            <span class="btn btn-xs btn-info" id="btnNewContactType" title="Add new contact type" onclick="$('#btnNewContactType').hide();$('#divNewContactType').show();">&nbsp;+&nbsp;</span>
			    <?php } ?>
                        </div>
                    </div>
                    <div class="form-group" id="divNewContactType" style="display: none;">
                        <label for="txtNewContactType" class="col-sm-4 control-label fieldLabel_optional">Enter New Contact Type:</label>
                        <div class="col-sm-8">
                            <div class="callout">
                                <input class="form-control optional" type="text" id="txtNewContactType" value="" size="50" maxlength="50" />
                                <p class="small"> 50 charactere max.</p>
                                <span class="btn btn-xs btn-primary" onclick="saveNewContactType();">&nbsp;Save Contact Type&nbsp;</span>
                                <span class="btn btn-xs btn-info" onclick="$('#btnNewContactType').show();$('#divNewContactType').hide();">&nbsp;Cancel&nbsp;</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject" class="col-sm-4 control-label fieldLabel_compulsory">Subject:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('subject', $subject, $vo->subject, true, true); ?>
			    <?php if(!in_array(DB_NAME, ["am_baltic", "am_baltic_demo"])){ ?>
                            <span class="btn btn-xs btn-info" id="btnNewSubject" title="Add new subject" onclick="$('#btnNewSubject').hide();$('#divNewSubject').show();">&nbsp;+&nbsp;</span>
			    <?php } ?>
                        </div>
                    </div>
                    <div class="form-group" id="divNewSubject" style="display: none;">
                        <label for="txNewtSubject" class="col-sm-4 control-label fieldLabel_optional">Enter New Subject:</label>
                        <div class="col-sm-8">
                            <div class="callout">
                                <input class="form-control optional" type="text" id="txtNewSubject" value="" size="50" maxlength="50" />
                                <p class="small"> 50 charactere max.</p>
                                <span class="btn btn-xs btn-primary" onclick="saveNewSubject();">&nbsp;Save Subject&nbsp;</span>
                                <span class="btn btn-xs btn-info" onclick="$('#btnNewSubject').show();$('#divNewSubject').hide();">&nbsp;Cancel&nbsp;</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date" class="col-sm-4 control-label fieldLabel_compulsory">Contact Date:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::datebox('date', $vo->date, true); ?>
                        </div>
                    </div>
                    <?php if(in_array(DB_NAME, ["am_baltic_demo"])){ ?>
                        <div class="form-group">
                            <label for="contact_time" class="col-sm-4 control-label fieldLabel_optional">Contact Time:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::timebox('contact_time', $vo->contact_time, false); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label fieldLabel_compulsory">By Whom:</label>
                        <div class="col-sm-8 callout">
                            <div class="form-group">
                                <label for="by_whom" class="col-sm-4 control-label fieldLabel_compulsory">Name:</label>
                                <div class="col-sm-8">
                                    <input class="form-control compulsory" type="text" name="by_whom" id="by_whom" value="<?php echo $vo->by_whom == '' ? $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname:$vo->by_whom; ?>" />
                                </div>
                            </div>
                            <?php if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"])) { ?>
                                <input type="hidden" name="whom_position" id="whom_position" value="<?php echo $vo->whom_position == '' ? DAO::getSingleValue($link, "SELECT description FROM lookup_user_types WHERE id = '{$_SESSION['user']->type}'"):$vo->whom_position; ?>" />
                            <?php }else { ?>
                                <div class="form-group">
                                    <label for="position" class="col-sm-4 control-label fieldLabel_compulsory">Position:</label>
                                    <div class="col-sm-8">
                                        <input class="form-control compulsory" type="text" name="whom_position" id="whom_position" value="<?php echo $vo->whom_position == '' ? DAO::getSingleValue($link, "SELECT description FROM lookup_user_types WHERE id = '{$_SESSION['user']->type}'"):$vo->whom_position; ?>" />
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label fieldLabel_compulsory">Next Action:</label>
                        <div class="col-sm-8 callout">
                            <div class="form-group">
                                <label for="next_action_date" class="col-sm-4 control-label fieldLabel_optional">Date:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::datebox('next_action_date', $vo->next_action_date, false); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="time" class="col-sm-4 control-label fieldLabel_optional">Time:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::timebox('next_action_time', $vo->next_action_time, false); ?>
                                </div>
                            </div>
			    <?php if(in_array(DB_NAME, ["am_sd_demo", "am_superdrug"])){?>
                            <div class="form-group">
                                <label for="notify_assessor" class="col-sm-4 control-label fieldLabel_optional">Notify Assessor<br>(<?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ' , surname) FROM users WHERE users.id = '{$tr->assessor}'"); ?>):</label>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="notify_assessor" id="notify_assessor" value="<?php echo $vo->notify_assessor; ?>" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo $vo->notify_assessor == '1'?'checked="checked"':''; ?> />
                                </div>
                            </div>
			    <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="agreed_action" class="col-sm-4 control-label fieldLabel_optional"><?php echo in_array(DB_NAME, ["am_baltic", "am_baltic_demo"]) ? "Comments" : "Agreed Action"; ?>:</label>
                        <div class="col-sm-8">
                            <textarea class="optional" name="agreed_action" id="agreed_action" rows="10" style="width: 100%;"><?php echo htmlspecialchars((string)$vo->agreed_action); ?></textarea>
                        </div>
                    </div>
                    <?php if(in_array(DB_NAME, ["am_baltic_demo", "am_baltic"])){?>
                    <div class="form-group">
                        <label for="rating" class="col-sm-4 control-label fieldLabel_optional">Rating:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('rating', InductionHelper::getCrmNoteRating(), $vo->rating, true, false); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="concerns" class="col-sm-4 control-label fieldLabel_optional">Concerns Raised By:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('concerns', InductionHelper::getCrmNoteConcerns(), $vo->concerns, true, false); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reason" class="col-sm-4 control-label fieldLabel_optional">Reason:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('reason', InductionHelper::getDdlLearnerCrmReason(), $vo->reason, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="for_caseload" class="col-sm-4 control-label fieldLabel_optional">For Caseload Tab</label>
                        <div class="col-sm-8">
                            <?php echo HTML::checkbox('for_caseload', 1, $vo->for_caseload == '1' ? true : false); ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </form>
        </div>

    </div>


</div>
<br>

<div id="dialogNewContactType">
    <p class="text-bold">Enter New Contact Type:</p>
    <p><input type="text" size="50" name="txtNewContactType" id="txtNewContactType" /></p>
    <p class="small">50 characters max.</p>
</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script language="JavaScript">

    $(function() {

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            yearRange: 'c-50:c+50'
        });

        $('#input_date').attr('class', 'datepicker compulsory form-control');
        $('#input_next_action_date').attr('class', 'datepicker form-control');
        $('#input_next_action_time').attr('class', 'timebox optional form-control');

        $(".timebox").timepicker({ timeFormat: 'H:i' });

        $('.timebox').bind('timeFormatError timeRangeError', function() {
            this.value = '';
            alert("Please choose a valid time");
            this.focus();
        });

        $('#dialogNewContactType').dialog({
            title: "Contact Type",
            modal: true,
            width: 'auto',
            maxWidth: 550,
            height: 'auto',
            maxHeight: 500,
            closeOnEscape: true,
            autoOpen: false,
            resizable: false,
            draggable: false,
            buttons: {
                'Cancel': function() {$(this).dialog('close');},
                'OK': function() {
                    var new_contact_type = $('#txtNewContactType').val().trim();
                    if(new_contact_type == '')
                    {
                        alert('Please provide the suitable contact type');
                        return;
                    }
                    $.ajax({
                        type:'GET',
                        url:'do.php?_action=edit_learner_crm_note&subaction=new_contact_type&value='+new_contact_type,
                        data: $('#frm_wb_developing_self').serialize(),
                        success: function(data, textStatus, xhr) {
                            console.log(data);
                        }
                    });
                }
            }
        });
    });

    function saveNewContactType()
    {
        if($('#txtNewContactType').val().trim() == '')
        {
            alert('Please enter the new contact type');
            return;
        }

        $('#divNewContactType').hide();
        $('#btnNewContactType').show();

        var client = ajaxRequest('do.php?_action=edit_learner_crm_note&subaction=new_contact_type&value='+encodeURIComponent($('#txtNewContactType').val().trim()));

        $('#txtNewContactType').val();
        var form = document.forms['frmLearnerCRMNote'];
        var contact_type = form.elements['type_of_contact'];
        ajaxPopulateSelect(contact_type, 'do.php?_action=edit_learner_crm_note&subaction=load_contact_type');
    }

    function saveNewSubject()
    {
        if($('#txtNewSubject').val().trim() == '')
        {
            alert('Please enter the new subject');
            return;
        }

        $('#divNewSubject').hide();
        $('#btnNewSubject').show();

        var client = ajaxRequest('do.php?_action=edit_learner_crm_note&subaction=new_subject&value='+encodeURIComponent($('#txtNewSubject').val().trim()));

        $('#txtNewSubject').val();
        var form = document.forms['frmLearnerCRMNote'];
        var subject = form.elements['subject'];
        ajaxPopulateSelect(subject, 'do.php?_action=ajax_load_crm_subject_dropdown');
    }

    function save()
    {
        var myForm = document.forms['frmLearnerCRMNote'];
        if(validateForm(myForm) == false)
        {
            return false;
        }

        myForm.submit();
    }

    function deleteNote()
    {
        if(window.confirm("Do you really want to delete this Note?"))
        {
            window.location.replace('do.php?_action=delete_learner_crm_note&id=<?php echo $vo->id; ?>');
        }
    }
</script>

</body>
</html>