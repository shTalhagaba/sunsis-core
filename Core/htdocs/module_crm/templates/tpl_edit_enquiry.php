<?php /* @var $enquiry Enquiry */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $enquiry->id == ''?'Create Enquiry':'Edit Enquiry'; ?></title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link href="/assets/adminlte/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <style>
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
        .disabled{
            pointer-events:none;
            opacity:0.4;
        }
    </style>
</head>
<body>
<div class="row">
    <div class="col-sm-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;"><?php echo $enquiry->id == ''?'Create Enquiry':'Edit Enquiry'; ?></div>
            <div class="ButtonBar">
                <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <span class="btn btn-xs btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
            </div>
            <div class="ActionIconBar">

            </div>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>
<br>

<div class="row">
    <div class="col-sm-11 col-sm-offset-1">
        <h5 class="lead text-bold"><?php echo $enquiry->id == '' ? 'New Enquiry' : 'Edit Enquiry'; ?></h5>
    </div>
</div>

<form autocomplete="off" class="form-horizontal" name="frmEnquiry" id="frmEnquiry" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="id" value="<?php echo $enquiry->id; ?>" />
    <input type="hidden" name="_action" value="save_enquiry" />
    <input type="hidden" name="company_id" value="<?php echo $enquiry->company_id; ?>" />
    <input type="hidden" name="company_type" value="<?php echo $enquiry->company_type; ?>" />

    <div class="row">
        <div class="col-sm-8">
            <div class="box box-primary box-solid">
                <div class="box-header"><h4 class="text-bold">Enquiry</h4> </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="company_location_id" class="col-sm-4 control-label fieldLabel_compulsory">Location:</label>
                        <div class="col-sm-8"><?php echo HTML::selectChosen('company_location_id', $company_locations, $enquiry->company_location_id, false, true); ?></div>
                    </div>
                    <div class="form-group">
                        <label for="main_contact_id" class="col-sm-4 control-label fieldLabel_compulsory">Contact Person:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('main_contact_id', $company_contacts, $enquiry->main_contact_id, true, true); ?>
                            <span class="btn btn-info btn-xs" id="btnAddNewContact">Add New Contact</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status" class="col-sm-4 control-label">Enquiry Status:</label>
                        <div class="col-sm-8"><?php echo HTML::selectChosen('status', $enquiryStatus, $enquiry->status, false, true); ?></div>
                    </div>
                    <div class="form-group">
                        <label for="enquiry_type" class="col-sm-4 control-label">Enquiry Type:</label>
                        <div class="col-sm-8"><?php echo HTML::selectChosen('enquiry_type', Enquiry::getDDLEnquiryType(), $enquiry->enquiry_type, true, false); ?></div>
                    </div>
                    <div class="form-group">
                        <label for="enquiry_title" class="col-sm-4 control-label">Enquiry Title:</label>
                        <div class="col-sm-8">
                            <input class="form-control compulsory" type="text" name="enquiry_title" id="enquiry_title" maxlength="150" value="<?php echo $enquiry->enquiry_title; ?>">
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label for="source" class="col-sm-4 control-label fieldLabel_optional">Source of enquiry:</label>
                        <div class="col-sm-8"><input class="form-control" type="text" name="source" id="source" maxlength="100" value="<?php //echo $enquiry->source; ?>"></div>
                    </div> -->
                    <div class="form-group">
                        <label for="industry" class="col-sm-4 control-label fieldLabel_optional">Product:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('industry', Enquiry::getDDLIndustries($link), explode(',', (string)$enquiry->industry), false, true, true, 10); ?>
                            <br>
                            <span class="btn btn-info btn-xs" id="btnAddNewProduct">Add New Product</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-12 fieldLabel_optional">Description:</label>
                        <div class="col-sm-12"><textarea name="description" id="description" style="width: 100%;" rows="10"><?php echo $enquiry->description; ?></textarea></div>
                    </div>
                </div>
                <div class="box-footer">
                    <span class="btn btn-sm btn-block btn-primary" onclick="save();">
                        <i class="fa fa-save"></i> Save
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="callout callout-default">
                <h5 class="lead text-bold text-success">Company: <?php echo $organisation->legal_name; ?></h5>
                <span class="text-bold">System ID: </span><?php echo $organisation->id; ?><br>
                <span class="text-bold">Number of employees: </span><?php echo $organisation->site_employees; ?><br>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <p class="pull-left" style="color: #000000; font-size: 0.9em; font-style: italic;">
                <?php echo $enquiry->id != '' ? 'Created on ' . Date::toShort($enquiry->created) . ' at ' . Date::to($enquiry->created, 'H:i') : ''; ?>
            </p>
            <p class="pull-right" style="color: #000000; font-size: 0.9em; font-style: italic;">
                <?php echo $enquiry->id != '' ? 'Last modified on ' . Date::toShort($enquiry->modified) . ' at ' . Date::to($enquiry->modified, 'H:i') : ''; ?>
            </p>
        </div>
    </div>
</form>
<br>
<div class="modal fade" id="modalAddProduct" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form class="form-horizontal" method="post" name="frmAddProduct" id="frmAddProduct" method="post"
              action="do.php?_action=ajax_helper">
            <input type="hidden" name="formName" value="frmAddProduct" />
            <input type="hidden" name="subaction" value="save_crm_product" />
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title text-bold">Add new Product in the list</h5>
                </div>
                <div class="modal-body">
                    <div class="control-group">
                        <label class="control-label" for ="product_name">Product Name:</label>
                        <?php echo HTML::textbox('product_name', '', 'class="form-control"'); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left btn-md" onclick="$('#modalAddProduct').modal('hide');">Cancel</button>
                    <button type="submit" id="btnModalAddProductSave" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="modalAddContact" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form class="form-horizontal" method="post" name="frmAddContact" id="frmAddContact" method="post"
              action="do.php?_action=save_crm_contacts">
            <input type="hidden" name="_action" value="save_crm_contacts" />
            <input type="hidden" name="org_id" value="<?php echo $org_id; ?>" />
            <input type="hidden" name="org_type" value="<?php echo $org_type; ?>" />
            <input type="hidden" name="formName" value="frmAddContact" />
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title text-bold">Add new Contact in the list</h5>
                </div>
                <div class="modal-body">
                    <div class="control-group">
                        <label class="control-label" for ="contact_title">Contact Title:</label>
                        <?php echo HTML::textbox('contact_title', '', 'class="form-control" maxlength="10"'); ?>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for ="contact_name">Contact Name:</label>
                        <?php echo HTML::textbox('contact_name', '', 'class="form-control" maxlength="150"'); ?>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for ="contact_email">Contact Email:</label>
                        <?php echo HTML::textbox('contact_email', '', 'class="form-control" maxlength="150"'); ?>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for ="contact_telephone">Contact Telephone:</label>
                        <?php echo HTML::textbox('contact_telephone', '', 'class="form-control" maxlength="40"'); ?>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for ="contact_mobile">Contact Mobile:</label>
                        <?php echo HTML::textbox('contact_mobile', '', 'class="form-control" maxlength="40"'); ?>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for ="job_title">Job Title:</label>
                        <?php echo HTML::textbox('job_title', '', 'class="form-control" maxlength="150"'); ?>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for ="decision_maker">Decision Maker:</label>
                        <?php echo HTML::selectChosen('decision_maker', [[0, 'No'], [1, 'Yes']]); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left btn-md" onclick="$('#modalAddContact').modal('hide');">Cancel</button>
                    <button type="submit" id="btnModalAddContactSave" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>
<script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>

<script language="JavaScript">

    $(function() {

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            yearRange: 'c-50:c+50'
        });

        $('.datepicker').attr('class', 'datepicker form-control');

        $('#industry').chosen({width: "100%"});
    });

    function save()
    {
        var myForm = document.forms['frmEnquiry'];
        if(!validateForm(myForm))
        {
            return false;
        }

        myForm.submit();
    }

    function pull_address(type, id)
    {
        if(type != '' && id != '')
        {
            $.ajax({
                type:'GET',
                dataType: 'json',
                url:'do.php?_action=ajax_helper&subaction=get_location_details&type='+type,
                data: {location_id: id} ,
                success:function(location){
                    $('#pool_location_id').attr('disabled', false);
                    $('#p_addr').val(location.address_line_1 + ' ' + location.address_line_2);
                    $('#p_addr_city').val(location.address_line_3);
                    $('#p_addr_region').val(location.address_line_4);
                    $('#p_addr_postcode').val(location.postcode);
                },
                error:function(msg){
                    $("#"+type+"_location_id").attr('disabled', false);
                    alert('Error: Please contact Sunesis Support with the screenshot.\r\n'+msg);
                    console.log(msg);
                }
            });
        }
    }

    $("#btnAddNewProduct").on('click', function(e){
        e.preventDefault();

        document.forms["frmAddProduct"].reset();
        $('#modalAddProduct').modal('show');
    });
    
    $("#btnAddNewContact").on('click', function(e){
        e.preventDefault();

        document.forms["frmAddContact"].reset();
        $('#modalAddContact').modal('show');
    });

    $('#frmAddProduct').validate({
        rules: {
            product_name: {
                required: true,
                minlength: 5
            }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    $('#modalAddProduct').modal('hide');
                    ajaxPopulateSelect(document.forms["frmEnquiry"].elements["industry"], 'do.php?_action=ajax_helper&subaction=load_products');
                    $("#industry").trigger("chosen:updated");
                }
            });
        }
    });

    $('#frmAddContact').validate({
        rules: {
            contact_title: {
                maxlength: 10
            },
            contact_name: {
                required: true,
                minlength: 5,
                maxlength: 150
            },
            contact_email: {
                maxlength: 150
            },
            contact_telephone: {
                maxlength: 40
            },
            contact_mobile: {
                maxlength: 40
            }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    $('#modalAddContact').modal('hide');
                    ajaxPopulateSelect(document.forms["frmEnquiry"].elements["main_contact_id"], 'do.php?_action=ajax_helper&subaction=load_contacts&org_id=<?php echo $enquiry->company_id; ?>&org_type=<?php echo $enquiry->company_type; ?>');
                    $("#industry").trigger("chosen:updated");
                }
            });
        }
    });

</script>

</body>
</html>