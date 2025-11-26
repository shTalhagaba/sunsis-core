<?php /* @var $crm_note CrmNote */ ?>
<?php /* @var $org Organisation */ ?>
<?php /* @var $main_location Location */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $crm_note->id == ''?'Create CRM Note':'Edit CRM Note'; ?></title>
    <link rel="stylesheet" href="css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/jquery.timepicker.css" />

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <style>
        .disabled{
            pointer-events:none;
            opacity:0.4;
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
            <div class="Title" style="margin-left: 6px;"><?php echo $crm_note->id == ''?'Create CRM Note':'Edit CRM Note'; ?></div>
            <div class="ButtonBar">
                <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <?php
                echo '<span class="btn btn-xs btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>';
                ?>
                <?php if($crm_note->id != "" && $_SESSION['user']->isAdmin()) { ?>
                    <span class="btn btn-xs btn-default" onclick="deleteNote();"><i class="fa fa-remove"></i> Delete</span>
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
    <div class="col-sm-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h2 class="box-title"><?php echo $crm_note->id == ''?'Add':'Edit'; ?> Details</h2>
            </div>
            <form autocomplete="off" class="form-horizontal" name="frmOrgCrmNote" id="frmOrgCrmNote" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="id" value="<?php echo $crm_note->id; ?>" />
                <input type="hidden" name="organisation_id" value="<?php echo $crm_note->organisation_id; ?>" />
                <input type="hidden" name="_action" value="save_org_crm_note" />
                <div class="box-body with-border">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label fieldLabel_compulsory">Contact Details:</label>
                        <div class="col-sm-9 callout">
                            <div class="form-group">
                                <label for="org_contact_id" class="col-sm-4 control-label fieldLabel_compulsory">Person Contacted:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('org_contact_id', $ddlOrganisationContacts, $crm_note->org_contact_id, true, true); ?>
                                    <span class="btn btn-xs btn-info" id="btnNewOrganisationContact" title="Add new organisation contact" onclick="$('#btnNewOrganisationContact').hide();$('#divNewOrganisationContact').show();">&nbsp;+&nbsp;</span>
                                </div>
                            </div>
                            <div class="form-group" id="divNewOrganisationContact" style="display: none;">
                                <label for="" class="col-sm-4 control-label fieldLabel_optional">Enter New Organisation Contact:</label>
                                <div class="col-sm-8">
                                    <div class="callout">
                                        <div class="form-group">
                                            <label for="contact_title" class="col-sm-4 control-label fieldLabel_compulsory">Title:</label>
                                            <div class="col-sm-8">
                                                <input class="form-control optional" type="text" name="contact_title" id="contact_title" value="" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="contact_name" class="col-sm-4 control-label fieldLabel_compulsory">Full Name:</label>
                                            <div class="col-sm-8">
                                                <input class="form-control optional" type="text" name="contact_name" id="contact_name" value="" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="job_title" class="col-sm-4 control-label fieldLabel_compulsory">Job Title / Position:</label>
                                            <div class="col-sm-8">
                                                <input class="form-control optional" type="text" name="job_title" id="job_title" value="" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="contact_department" class="col-sm-4 control-label fieldLabel_compulsory">Department:</label>
                                            <div class="col-sm-8">
                                                <input class="form-control optional" type="text" name="contact_department" id="contact_department" value="" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="contact_telephone" class="col-sm-4 control-label fieldLabel_compulsory">Telephone:</label>
                                            <div class="col-sm-8">
                                                <input class="form-control optional" type="text" name="contact_telephone" id="contact_telephone" value="" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="contact_mobile" class="col-sm-4 control-label fieldLabel_optional">Mobile:</label>
                                            <div class="col-sm-8">
                                                <input class="form-control optional" type="text" name="contact_mobile" id="contact_mobile" value="" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="contact_email" class="col-sm-4 control-label fieldLabel_optional">Email:</label>
                                            <div class="col-sm-8">
                                                <input class="form-control optional" type="text" name="contact_email" id="contact_email" value="" />
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <span class="btn btn-xs btn-primary" onclick="saveNewOrganisationContact();">&nbsp;Save Organisation Contact&nbsp;</span>
                                            <span class="btn btn-xs btn-info" onclick="$('#btnNewOrganisationContact').show();$('#divNewOrganisationContact').hide();">&nbsp;Cancel&nbsp;</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="type_of_contact" class="col-sm-4 control-label fieldLabel_compulsory">Type of Contact:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('type_of_contact', $ddlContactTypes, $crm_note->type_of_contact, true, true); ?>
                                    <span class="btn btn-xs btn-info" id="btnNewContactType" title="Add new contact type" onclick="$('#btnNewContactType').hide();$('#divNewContactType').show();">&nbsp;+&nbsp;</span>
                                </div>
                            </div>
                            <div class="form-group" id="divNewContactType" style="display: none;">
                                <label for="txtNewContactType" class="col-sm-4 control-label fieldLabel_optional">Enter New Contact Type:</label>
                                <div class="col-sm-8">
                                    <div class="callout">
                                        <input class="form-control optional" type="text" id="txtNewContactType" value="" size="50" maxlength="50" />
                                        <p class="small"> 50 characters max.</p>
                                        <span class="btn btn-xs btn-primary" onclick="saveNewContactType();">&nbsp;Save Contact Type&nbsp;</span>
                                        <span class="btn btn-xs btn-info" onclick="$('#btnNewContactType').show();$('#divNewContactType').hide();">&nbsp;Cancel&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="subject" class="col-sm-4 control-label fieldLabel_compulsory">Subject:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('subject', $ddlSubjects, $crm_note->subject, true, true); ?>
                                    <span class="btn btn-xs btn-info" id="btnNewSubject" title="Add new subject" onclick="$('#btnNewSubject').hide();$('#divNewSubject').show();">&nbsp;+&nbsp;</span>
                                </div>
                            </div>
                            <div class="form-group" id="divNewSubject" style="display: none;">
                                <label for="txNewtSubject" class="col-sm-4 control-label fieldLabel_optional">Enter New Subject:</label>
                                <div class="col-sm-8">
                                    <div class="callout">
                                        <input class="form-control optional" type="text" id="txtNewSubject" value="" size="50" maxlength="50" />
                                        <p class="small"> 50 characters max.</p>
                                        <span class="btn btn-xs btn-primary" onclick="saveNewSubject();">&nbsp;Save Subject&nbsp;</span>
                                        <span class="btn btn-xs btn-info" onclick="$('#btnNewSubject').show();$('#divNewSubject').hide();">&nbsp;Cancel&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="date" class="col-sm-4 control-label fieldLabel_compulsory">Contact Date:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::datebox('contact_date', $crm_note->contact_date, true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="contact_time" class="col-sm-4 control-label fieldLabel_optional">Contact Time:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::timebox('contact_time', $crm_note->contact_time, false); ?> (HH:MM)
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="contact_duration" class="col-sm-4 control-label fieldLabel_optional">Contact Duration:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::timebox('contact_duration', $crm_note->contact_duration, false); ?> (HH:MM)
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label fieldLabel_compulsory">By Whom:</label>
                        <div class="col-sm-9 callout">
                            <div class="form-group">
                                <label for="by_whom" class="col-sm-4 control-label fieldLabel_compulsory">Name:</label>
                                <div class="col-sm-8">
                                    <input class="form-control compulsory" type="text" name="by_whom" id="by_whom" value="<?php echo $crm_note->by_whom == '' ? $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname:$crm_note->by_whom; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="by_whom_position" class="col-sm-4 control-label fieldLabel_compulsory">Position:</label>
                                <div class="col-sm-8">
                                    <input class="form-control compulsory" type="text" name="by_whom_position" id="by_whom_position" value="<?php echo $crm_note->by_whom_position == '' ? $_SESSION['user']->job_role:$crm_note->by_whom_position; ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label fieldLabel_compulsory">Next Action:</label>
                        <div class="col-sm-9 callout">
                            <div class="form-group">
                                <label for="next_action_id" class="col-sm-4 control-label fieldLabel_compulsory">Next Action:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('next_action_id', $ddlNextActions, $crm_note->next_action_id, true, true); ?>
                                    <span class="btn btn-xs btn-info" id="btnNewNextAction" title="Add new next action" onclick="$('#btnNewNextAction').hide();$('#divNewNextAction').show();">&nbsp;+&nbsp;</span>
                                </div>
                            </div>
                            <div class="form-group" id="divNewNextAction" style="display: none;">
                                <label for="txtNewNextAction" class="col-sm-4 control-label fieldLabel_optional">Enter New Next Action:</label>
                                <div class="col-sm-8">
                                    <div class="callout">
                                        <input class="form-control optional" type="text" id="txtNewNextAction" value="" size="50" maxlength="50" />
                                        <p class="small"> 50 characters max.</p>
                                        <span class="btn btn-xs btn-primary" onclick="saveNewNextAction();">&nbsp;Save Next Action&nbsp;</span>
                                        <span class="btn btn-xs btn-info" onclick="$('#btnNewNextAction').show();$('#divNewNextAction').hide();">&nbsp;Cancel&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="next_action_date" class="col-sm-4 control-label fieldLabel_compulsory">Date:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::datebox('next_action_date', $crm_note->next_action_date, true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="next_action_time" class="col-sm-4 control-label fieldLabel_optional">Time:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::timebox('next_action_time', $crm_note->next_action_time, false); ?> (HH:MM)
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="actioned" class="col-sm-4 control-label fieldLabel_optional">Actioned:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('actioned', OrganisationCRMNote::getDDLActioned(), $crm_note->actioned, false); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="agreed_action" class="col-sm-3 control-label fieldLabel_optional">Agreed Action / Comments:</label>
                        <div class="col-sm-9">
                            <textarea class="optional" name="agreed_action" id="agreed_action" rows="10" style="width: 100%;"><?php echo htmlspecialchars($crm_note->agreed_action ?? ''); ?></textarea>
                        </div>
                    </div>
                    <?php
                    if($crm_note->id == '')
                        echo '<input type="hidden" name="audit_info" value="'.$_SESSION['user']->firstnames.' '.$_SESSION['user']->surname.' ('.$_SESSION['user']->username.') at '.date('H:i:s D d M Y').'" />';
                    ?>
                </div>
                <div class="box-footer">
                    <span class="btn btn-sm btn-primary btn-block" onclick="save();"><i class="fa fa-save"></i> Save Information</span>
                </div>
            </form>
        </div>

    </div>

    <div class="col-sm-4">
        <div class="callout callout-default">
            <?php
            echo $organisation->legal_name;
            echo '<span class="small">';
            echo $location->address_line_1 . '<br>';
            echo !is_null($location->address_line_2) ? $location->address_line_2 . '<br>' : '';
            echo !is_null($location->address_line_3) ? $location->address_line_3 . '<br>' : '';
            echo !is_null($location->address_line_4) ? $location->address_line_4 . '<br>' : '';
            echo $location->postcode . '<br>';
            echo !is_null($location->telephone) ? $location->telephone . '<br>' : '';
            echo '</span> ';
            ?>
        </div>
    </div>
</div>
<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker.js"></script>

<script language="JavaScript">

    $(function() {

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            yearRange: 'c-50:c+50'
        });

        $('#input_date').attr('class', 'datepicker compulsory form-control');
        $('#input_next_action_date').attr('class', 'datepicker compulsory form-control');
        $('#timeset').attr('class', 'timebox optional form-control');

        $(".timebox").timepicker({ timeFormat: 'H:i', minTime: '08:00:00', maxTime: '18:00:00' });

        $('.timebox').bind('timeFormatError timeRangeError', function() {
            this.value = '';
            alert("Please choose a valid time");
            this.focus();
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

        var client = ajaxRequest('do.php?_action=edit_org_crm_note&subaction=new_contact_type&value='+encodeURIComponent($('#txtNewContactType').val().trim()));

        $('#txtNewContactType').val();
        var form = document.forms['frmOrgCrmNote'];
        var contact_type = form.elements['type_of_contact'];
        ajaxPopulateSelect(contact_type, 'do.php?_action=edit_org_crm_note&subaction=load_contact_type');
    }

    function saveNewOrganisationContact()
    {
        if($('#contact_title').val().trim() == '' || $('#contact_name').val().trim() == '' || $('#contact_department').val().trim() == '' || $('#contact_telephone').val().trim() == '')
        {
            alert('Please provide all compulsory information for new organisation contact');
            return;
        }


        $('#divNewOrganisationContact').hide();
        $('#btnNewOrganisationContact').show();

        var parameters = 'contact_title='+encodeURIComponent($('#contact_title').val().trim()) +
            '&contact_name='+encodeURIComponent($('#contact_name').val().trim()) +
            '&job_title='+encodeURIComponent($('#job_title').val().trim()) +
            '&contact_department='+encodeURIComponent($('#contact_department').val().trim()) +
            '&contact_telephone='+encodeURIComponent($('#contact_telephone').val().trim()) +
            '&contact_mobile='+encodeURIComponent($('#contact_mobile').val().trim()) +
            '&contact_email='+encodeURIComponent($('#contact_email').val().trim()) +
            '&organisation_id='+encodeURIComponent('<?php echo $organisation->id; ?>');

        var client = ajaxRequest('do.php?_action=edit_org_crm_note&subaction=new_organisation_contact&'+parameters);

        $('#contact_title').val('');
        $('#contact_name').val('');
        $('#contact_department').val('');
        $('#contact_telephone').val('');
        $('#contact_mobile').val('');
        $('#contact_email').val('');
        var form = document.forms['frmOrgCrmNote'];
        var org_contact_id = form.elements['org_contact_id'];
        ajaxPopulateSelect(org_contact_id, 'do.php?_action=edit_org_crm_note&subaction=load_organisation_contact&organisation_id='+encodeURIComponent('<?php echo $organisation->id; ?>'));
    }

    function saveNewNextAction()
    {
        if($('#txtNewNextAction').val().trim() == '')
        {
            alert('Please enter new next action description');
            return;
        }

        $('#divNewNextAction').hide();
        $('#btnNewNextAction').show();

        var client = ajaxRequest('do.php?_action=edit_org_crm_note&subaction=new_next_action&value='+encodeURIComponent($('#txtNewNextAction').val().trim()));

        $('#txtNewNextAction').val();
        var form = document.forms['frmOrgCrmNote'];
        var next_action = form.elements['next_action_id'];
        ajaxPopulateSelect(next_action, 'do.php?_action=edit_org_crm_note&subaction=load_next_action');
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

        var client = ajaxRequest('do.php?_action=edit_org_crm_note&subaction=new_subject&employer=1&value='+encodeURIComponent($('#txtNewSubject').val().trim()));

        $('#txtNewSubject').val();
        var form = document.forms['frmOrgCrmNote'];
        var subject = form.elements['subject'];
        ajaxPopulateSelect(subject, 'do.php?_action=ajax_load_crm_subject_dropdown&employer=1');
    }

    function save()
    {
        var myForm = document.forms['frmOrgCrmNote'];
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
            window.location.replace('do.php?_action=delete_org_crm_note&id=<?php echo $crm_note->id; ?>');
        }
    }

    
</script>

</body>
</html>