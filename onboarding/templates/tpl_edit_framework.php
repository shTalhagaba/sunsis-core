<?php /* @var $vo Framework */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $vo->id == ''?'Create Standard/ Programme':'Edit Standard/ Programme'; ?></title>
    <link rel="stylesheet" href="/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <style>
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
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
            <div class="Title" style="margin-left: 6px;"><?php echo $vo->id == ''?'Create Standard/ Programme':'Edit Standard/ Programme'; ?></div>
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

<div class="content-wrapper">
    <form autocomplete="off" class="form-horizontal" name="frmFramework" id="frmFramework" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $vo->id ?>" />
        <input type="hidden" name="_action" value="save_framework"/>

        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <span class="lead text-bold">Details</span><br>
                                <div class="form-group">
                                    <label for="title" class="col-sm-4 control-label fieldLabel_compulsory">Title:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control compulsory" name="title" id="title" value="<?php echo $vo->title; ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="duration_in_months" class="col-sm-4 control-label fieldLabel_compulsory">Duration:</label>
                                    <div class="col-sm-8">
                                        <input type="text" onkeypress="return numbersonly(this, event);" class="form-control compulsory" name="duration_in_months" id="duration_in_months" value="<?php echo $vo->duration_in_months; ?>" />&nbsp;
                                        <i class="text-info">(months) - (excluding EPA Duration)</i>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="epa_duration" class="col-sm-4 control-label fieldLabel_compulsory">EPA Duration:</label>
                                    <div class="col-sm-8">
                                        <input type="text" onkeypress="return numbersonly(this, event);" class="form-control compulsory" name="epa_duration" id="epa_duration" value="<?php echo $vo->epa_duration; ?>" />&nbsp;<i class="text-info">(months)</i>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fund_model" class="col-sm-4 control-label fieldLabel_optional">Funing Model:</label>
                                    <div class="col-sm-8">
                                        <?php echo HTML::selectChosen('fund_model', $FundModel_dropdown, $vo->fund_model, true, false); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fund_model_extra" class="col-sm-4 control-label fieldLabel_optional">Funing Model Specific:</label>
                                    <div class="col-sm-8">
                                        <?php echo HTML::selectChosen('fund_model_extra', Helpers::fundModelExtraOptions(), $vo->fund_model_extra, false, false); ?>
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
                                        <!-- <input type="text" class="form-control optional" name="standard_ref_no" id="standard_ref_no" value="<?php //echo $vo->standard_ref_no; ?>"> -->
                                        <?php echo HTML::selectChosen('standard_ref_no', $StandardRefNo_dropdown, $vo->standard_ref_no, true, false); ?>
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
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control optional" name="otj_hours" id="otj_hours" value="<?php echo $vo->otj_hours; ?>" onkeypress="return numbersonly(this);" maxlength="4" />&nbsp;<i class="text-info">(hours)</i>
                                    </div>
                                    <div class="col-sm-3">
                                        <span class="btn btn-xs btn-info" id="fetchOtjFromLookup">Fetch from Lookup</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="first_review" class="col-sm-4 control-label fieldLabel_optional">First Review:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control optional" name="first_review" id="first_review" value="<?php echo $vo->review_frequency; ?>" onkeypress="return numbersonly(this);" maxlength="3" />&nbsp;<i class="text-info">(weeks)</i>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="review_frequency" class="col-sm-4 control-label fieldLabel_optional">Review Frequency:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control optional" name="review_frequency" id="review_frequency" value="<?php echo $vo->review_frequency; ?>" onkeypress="return numbersonly(this);" maxlength="3" />&nbsp;<i class="text-info">(weeks)</i>
                                    </div>
                                </div>

                                <div class="callout callout-default">
                                    <span class="lead text-bold">Prices (TNP1)</span><br>
                                    <span class="btn btn-primary btn-xs" onclick="addTnpRow();">Add New TNP1 Item</span>
                                    <div class="table-responsive">
                                        <table id="tbl_tnp1" class="table table-bordered">
                                            <input type="hidden" name="total_tnp" value="<?php echo is_null($vo->tnp1) ? 0 : count(json_decode($vo->tnp1)); ?>">
                                            <thead><tr><th style="width: 70%;">Description</th><th>Cost &pound;</th><th>Include in Skills Scan reduction</th></tr></thead>
                                            <tbody>
                                                <?php
                                                $prices = is_null($vo->tnp1) ? [] : json_decode($vo->tnp1);
                                                if(count($prices) == 0)
                                                {
                                                    echo '<tr id="price1">';
                                                    echo '<td><input class="form-control" type="text" name="price_description_1" /></td>';
                                                    echo '<td><input class="form-control" type="text" name="price_cost_1" maxlength="5" /></td>';
                                                    echo '<td><input type="checkbox" value="1" name="price_include_1" /></td>';
                                                }
                                                else
                                                {
                                                    $i = 1;
                                                    
                                                    foreach($prices AS $price)
                                                    {
                                                        echo '<tr id="price'.$i.'">';
                                                        echo '<td><input class="form-control" type="text" name="price_description_'.$i.'" value="'.$price->description.'" /></td>';
                                                        echo '<td><input class="form-control" type="text" name="price_cost_'.$i.'" value="'.$price->cost.'" maxlength="5" /></td>';
                                                        echo $price->reduce == 1 ? 
                                                            '<td><input type="checkbox" value="1" name="price_include_'.$i.'" checked /></td>':
                                                            '<td><input type="checkbox" value="1" name="price_include_'.$i.'" /></td>';
                                                        echo '</tr>';
                                                        $i++;
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <span class="lead text-bold">TNP2</span><br>
                                    <div class="form-group">
                                        <label id="epa_price_label" for="epa_price" class="col-sm-4 control-label fieldLabel_optional">EPA Price:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control optional" name="epa_price" id="epa_price" value="<?php echo $vo->epa_price; ?>" onkeypress="return numbersonly(this);" maxlength="7" />&nbsp;<i class="text-info">(&pound;)</i>
                                        </div>
                                    </div>
                                    <span class="lead text-bold">Additional Prices</span><br>
                                    <span class="btn btn-primary btn-xs" onclick="addAdditionalPriceRow();">Add New Price Item</span>
                                    <div class="table-responsive">
                                        <table id="tbl_additional_prices" class="table table-bordered">
                                            <input type="hidden" name="total_additional_prices" value="<?php echo is_null($vo->additional_prices) ? 0 : count(json_decode($vo->additional_prices)); ?>">
                                            <thead><tr><th style="width: 70%;">Description</th><th>Cost &pound;</th></tr></thead>
                                            <tbody>
                                                <?php
                                                $additional_prices = is_null($vo->additional_prices) ? [] : json_decode($vo->additional_prices);
                                                if(count($additional_prices) == 0)
                                                {
                                                    echo '<tr id="additional_prices1">';
                                                    echo '<td><input class="form-control" type="text" name="additional_prices_description_1" /></td>';
                                                    echo '<td><input class="form-control" type="text" name="additional_prices_cost_1" maxlength="5" /></td>';
                                                }
                                                else
                                                {
                                                    $i = 1;
                                                    foreach($additional_prices AS $additional_price)
                                                    {
                                                        echo '<tr id="additional_prices'.$i.'">';
                                                        echo '<td><input class="form-control" type="text" name="additional_prices_description_'.$i.'" value="'.$additional_price->description.'" /></td>';
                                                        echo '<td><input class="form-control" type="text" name="additional_prices_cost_'.$i.'" value="'.$additional_price->cost.'" maxlength="5" /></td>';
                                                        $i++;
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="callout callout-default">
                                    <span class="lead text-bold">Scores and Percentages for Skills Analysis</span><br>
                                    <p class="text-info"><i class="fa fa-info-circle"></i> Please enter the reduction percentage for each score to be used for Skills Analysis</p>

                                    <?php 
                                    $rpl_percentages = json_decode($vo->rpl_percentages);
                                    for($i = 1; $i <= 5; $i++)
                                    {
                                        $_score = "score_{$i}";
                                        $_percentage = isset($rpl_percentages->$_score) ? $rpl_percentages->$_score : 0;
                                        echo '<div class="form-group">';
                                        echo '<label for="'.$_score.'" class="col-sm-4 control-label fieldLabel_optional">Score ' . $i . ':</label>';
                                        echo '<div class="col-sm-8">';
                                        echo '<input type="text" name="score_'.$i.'" id="score_'.$i.'" value="'.$_percentage.'" />';
                                        echo '</div>';
                                        echo '</div>';
                                    }
                                    ?>

                                </div>
                                
                                <div class="callout callout-default">
                                    <span class="lead text-bold">Additional Information</span><br>
				                    <div class="form-group">
                                        <label for="writing_assessment_chars" class="col-sm-4 control-label fieldLabel_optional">Writing Assessment Minimum Words Required:</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="writing_assessment_chars" id="writing_assessment_chars" value="<?php echo $vo->writing_assessment_chars; ?>" onkeypress="return numbersonly();" maxlength="4" />
                                        </div>
                                    </div>	
                                    <div class="form-group">
                                        <label for="training_by_provider" class="col-sm-4 control-label fieldLabel_optional">Training to be delivered by the Training Provider:</label>
                                        <div class="col-sm-8 small">
                                            <textarea style="width: 100%;" name="training_by_provider" id="training_by_provider" rows="7" maxlength="800"><?php echo $vo->training_by_provider; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="provider_equipment" class="col-sm-4 control-label fieldLabel_optional">Training Provider Equipment:</label>
                                        <div class="col-sm-8 small">
                                            <textarea style="width: 100%;" name="provider_equipment" id="provider_equipment" rows="7" maxlength="800"><?php echo $vo->provider_equipment; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="training_by_employer" class="col-sm-4 control-label fieldLabel_optional">Training to be delivered by the Employer:</label>
                                        <div class="col-sm-8 small">
                                            <textarea style="width: 100%;" name="training_by_employer" id="training_by_employer" rows="7" maxlength="800"><?php echo $vo->training_by_employer; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="employer_equipment" class="col-sm-4 control-label fieldLabel_optional">Employer Equipment:</label>
                                        <div class="col-sm-8 small">
                                            <textarea style="width: 100%;" name="employer_equipment" id="employer_equipment" rows="7" maxlength="800"><?php echo $vo->employer_equipment; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="training_by_subcontractor" class="col-sm-4 control-label fieldLabel_optional">Training to be delivered by the Subcontractor:</label>
                                        <div class="col-sm-8 small">
                                            <textarea style="width: 100%;" name="training_by_subcontractor" id="training_by_subcontractor" rows="7" maxlength="800"><?php echo $vo->training_by_subcontractor; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>

<script language="JavaScript">

    $(function() {

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            yearRange: 'c-50:c+50'
        });

        $('.datepicker').attr('class', 'datepicker form-control');

        var fundModel = "<?php echo $vo->fund_model; ?>";
        if(fundModel == '99')
        {
            showHideFundModelExtra(true);
        }
        else
        {
            showHideFundModelExtra(false);
        }

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

    function addTnpRow()
    {
        var last_row_id = $('#tbl_tnp1 tr:last').attr('id').replace('price', '');
        
        if(last_row_id > 9)
        {
            return alert("Only 10 rows can be created.");
        }
        last_row_id++;
        var tr = '<tr id="price'+last_row_id+'">';
        tr += '<td><input type="text" class="form-control" name="price_description_'+last_row_id+'"></td>';
        tr += '<td><input type="text" class="form-control" name="price_cost_'+last_row_id+'" maxlength="5"></td>';
        tr += '<td><input type="checkbox" value="1" name="price_include_'+last_row_id+'"></td>';
        tr += '</tr>';
        $('#tbl_tnp1 > tbody:last-child').append(tr);
        var rowCount = $('#tbl_tnp1 >tbody >tr').length;
        $("input[type=hidden][name=total_tnp]").val(rowCount);        
    }
    
    function addAdditionalPriceRow()
    {
        var last_row_id = $('#tbl_additional_prices tr:last').attr('id').replace('additional_prices', '');
        
        if(last_row_id > 9)
        {
            return alert("Only 10 rows can be created.");
        }
        last_row_id++;
        var tr = '<tr id="additional_prices'+last_row_id+'">';
        tr += '<td><input type="text" class="form-control" name="additional_prices_description_'+last_row_id+'"></td>';
        tr += '<td><input type="text" class="form-control" name="additional_prices_cost_'+last_row_id+'" maxlength="5"></td>';
        tr += '</tr>';
        $('#tbl_additional_prices > tbody:last-child').append(tr);
        var rowCount = $('#tbl_additional_prices >tbody >tr').length;
        $("input[type=hidden][name=total_additional_prices]").val(rowCount);        
    }

    $("span#fetchOtjFromLookup").click(function() {
        var form = document.forms['frmFramework'];
        var standard_ref_no = form.elements['standard_ref_no'].value;
        if(standard_ref_no == '')
        {
            return alert("Please select a Standard Ref Number first.");
        }
        
        $.ajax({
            url: 'do.php?_action=ajax_helper&subaction=fetch_otj_hours&standard_ref_no=' + encodeURIComponent(standard_ref_no),
            type: 'GET',
            success: function(data) {
                if(data.success)
                {
                    form.elements['otj_hours'].value = data.otj_hours;
                }
                else
                {
                    alert("Error fetching OTJ hours: " + data.message);
                }
            },
            error: function() {
                alert("An error occurred while fetching OTJ hours.");
            }
        });
    });

    function showHideFundModelExtra(show)
    {
        if(show)
        {
            $("#fund_model_extra").closest('.form-group').show();
        }
        else
        {
            $("#fund_model_extra").closest('.form-group').hide();
        }
    }

    $("#fund_model").change(function() {
        var selectedValue = $(this).val();
        if(selectedValue == '99') {
            showHideFundModelExtra(true);
        } else {
            showHideFundModelExtra(false);
        }
    });
</script>

</body>
</html>