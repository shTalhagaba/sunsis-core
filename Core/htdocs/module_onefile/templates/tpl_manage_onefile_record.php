<?php /* @var $vo Framework */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $vo->id == ''?'Create Framework':'Edit Framework'; ?></title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

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
            <div class="Title" style="margin-left: 6px;"><?php echo $vo->id == ''?'Create Framework/ Standard':'Edit Framework/ Standard'; ?></div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
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


<form autocomplete="off" class="form-horizontal" name="frmFramework" id="frmFramework" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="id" value="<?php echo $vo->id ?>" />
    <input type="hidden" name="_action" value="save_framework"/>

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border"><h2 class="box-title">Framework/ Standard Details</small></h2>
                    <div class="box-tools pull-right"></div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="form-group">
                                <label for="title" class="col-sm-4 control-label fieldLabel_compulsory">Title:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control compulsory" name="title" id="title" value="<?php echo $vo->title; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="duration_in_months" class="col-sm-4 control-label fieldLabel_compulsory">Provider Duration:</label>
                                <div class="col-sm-8">
                                    <input type="text" onkeypress="return numbersonly(this, event);" class="form-control compulsory" name="duration_in_months" id="duration_in_months" value="<?php echo $vo->duration_in_months; ?>" />&nbsp;<i class="text-info">(months)</i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="framework_type" class="col-sm-4 control-label fieldLabel_optional">Programme Type:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('framework_type', $A15_dropdown, $vo->framework_type, true, false); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="framework_code" class="col-sm-4 control-label fieldLabel_optional">Framework Code:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('framework_code', $A26_dropdown, $vo->framework_code, true, false); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="PwayCode" class="col-sm-4 control-label fieldLabel_optional">Pathway Code:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('PwayCode', $PwayCode_dropdown, $vo->PwayCode, true, false); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="StandardCode" class="col-sm-4 control-label fieldLabel_optional">LARS Standard Code:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('StandardCode', $StandardCode_dropdown, $vo->StandardCode, true, false); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="standard_ref_no" class="col-sm-4 control-label fieldLabel_optional">Standard Ref Number:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control optional" name="standard_ref_no" id="standard_ref_no" value="<?php echo $vo->standard_ref_no; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="active" class="col-sm-4 control-label fieldLabel_optional">Active: </label>
                                <div class="col-sm-8">
                                    <input class="yes_no_toggle" type="checkbox" name="active" id="active"
                                           data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success"
                                           data-offstyle="danger" <?php echo $vo->active == '1' ? 'checked="checked"' : ''; ?> />
                                </div>
                            </div>
                            <?php if(DB_NAME == 'am_baltic_demo' OR DB_NAME == 'am_baltic'){?>
                                <div class="form-group">
                                    <label for="track" class="col-sm-4 control-label fieldLabel_optional">Track framework contents?: </label>
                                    <div class="col-sm-8">
                                        <input class="yes_no_toggle" type="checkbox" name="track" id="track"
                                               data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success"
                                               data-offstyle="danger" <?php echo $vo->track == '1' ? 'checked="checked"' : ''; ?> />
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <label for="epa_org_id" class="col-sm-4 control-label fieldLabel_optional">EPA Organisation:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('epa_org_id', $epa_organisations, $vo->epa_org_id, true, false); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="epa_org_assessor_id_label" for="epa_org_assessor_id" class="col-sm-4 control-label fieldLabel_optional">EPA Assessor:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('epa_org_assessor_id', $epa_org_assessors, $vo->epa_org_assessor_id, false, false); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="otj_hours" class="col-sm-4 control-label fieldLabel_optional">Off the Job Hours:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control optional" name="otj_hours" id="otj_hours" value="<?php echo $vo->otj_hours; ?>" onkeypress="return numbersonly(this);" maxlength="4" />&nbsp;<i class="text-info">(hours)</i>
                                </div>
                            </div>
                            <?php if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"])) {?>
                                <div class="form-group">
                                    <label for="gateway_forecast" class="col-sm-4 control-label fieldLabel_optional">Gateway Forecast Months:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control optional" name="gateway_forecast" id="gateway_forecast"
                                               value="<?php echo $vo->gateway_forecast; ?>" onkeypress="return numbersonly(this);" maxlength="4" />&nbsp;<i class="text-info">(month)</i>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="epa_forecast" class="col-sm-4 control-label fieldLabel_optional">EPA Forecast Months:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control optional" name="epa_forecast" id="epa_forecast"
                                               value="<?php echo $vo->epa_forecast; ?>" onkeypress="return numbersonly(this);" maxlength="4" />&nbsp;<i class="text-info">(months)</i>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="comments" class="col-sm-2 control-label fieldLabel_optional">Comments:</label>
                                <div class="col-sm-10 small">
                                    <textarea style="width: 100%;" name="comments" title="comments" rows="7"><?php echo $vo->comments; ?></textarea>
                                </div>
                            </div>
                            <?php if( $vo->id != '' && SystemConfig::getEntityValue($link, 'onefile.integration')) { ?>
                                <div class="box box-solid box-primary">
                                    <div class="box-header with-border">
                                        <h2 class="box-title">Onefile</h2>
                                    </div>
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label for="full_name" class="col-sm-4 control-label fieldLabel_optional">Onefile Organisation:</label>
                                            <div class="col-sm-8">
                                                <?php 
                                                echo HTML::selectChosen('onefile_organisation_id', Onefile::getOnefileOrganisationsDdl($link), $vo->onefile_organisation_id);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="full_name" class="col-sm-4 control-label fieldLabel_optional">Onefile Framework Template:</label>
                                            <div class="col-sm-8">
                                                <?php 
                                                $onefile_fwk_tpls_list = [];
                                                $onefile_fwk_tpls_list_from_db = DAO::getSingleValue($link, "SELECT `value` FROM onefile WHERE `key` = 'fwk_tpls_{$vo->onefile_organisation_id}'");
                                    
                                                if($onefile_fwk_tpls_list_from_db != '')
                                                {
                                                    $onefile_fwk_tpls_list_from_db = json_decode($onefile_fwk_tpls_list_from_db);
                                                    foreach($onefile_fwk_tpls_list_from_db AS $onefile_fwk_tpl)
                                                    {
                                                        $onefile_fwk_tpls_list[] = [$onefile_fwk_tpl->ID, $onefile_fwk_tpl->Title];
                                                    }
                                                }
                                                echo HTML::selectChosen('onefile_fwk_tpl_id', $onefile_fwk_tpls_list, $vo->onefile_fwk_tpl_id, true);
                                                ?>                                
                                                <br>
                                                <span class="btn btn-sm btn-info" onclick="refresh_onefile_fwk_tpls();"><i class="fa fa-refresh"></i> Refresh</span></td>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>
<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script language="JavaScript">

    $(function() {

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            yearRange: 'c-50:c+50'
        });

        $('.datepicker').attr('class', 'datepicker form-control');

    });

    function save()
    {
        var myForm = document.forms['frmFramework'];
        if(validateForm(myForm) == false)
        {
            return false;
        }

        myForm.submit();
    }

    function epa_org_id_onchange(element)
    {
        var form = document.forms['frmFramework'];
        var epa_org_assessor_id = form.elements['epa_org_assessor_id'];

        ajaxPopulateSelect(epa_org_assessor_id, 'do.php?_action=ajax_load_account_manager&subaction=load_epa_org_assessors&EPA_Org_ID='+encodeURIComponent(element.value));

    }

    <?php if($vo->id != ''){ ?>
    function refresh_onefile_fwk_tpls()
    {
        var url = 'do.php?_action=ajax_onefile&subaction=getOnefileFrameworkTemplates'
        + "&organisation_id=" + encodeURIComponent($('#onefile_organisation_id').val());
        var client = ajaxRequest(url);
        if (client) 
        {
            console.log(client);
            var onefile_fwk_tpl_id_select = document.getElementById('onefile_fwk_tpl_id');
            onefile_fwk_tpl_id_select.disabled = true;
            ajaxPopulateSelect(onefile_fwk_tpl_id_select, 'do.php?_action=ajax_load_account_manager&subaction=load_onefile_fwk_tpls&organisation_id='+$('#onefile_organisation_id').val());
            onefile_fwk_tpl_id_select.disabled = false;
        }
    }    
    <?php } ?>


</script>

</body>
</html>