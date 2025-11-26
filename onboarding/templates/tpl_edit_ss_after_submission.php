<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>
<?php /* @var $skills_analysis SkillsAnalysis */ ?>
<?php /* @var $link PDO */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Skills Scan</title>
    <link rel="stylesheet" href="css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link href="/assets/adminlte/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">

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
            <div class="Title" style="margin-left: 6px;">Edit Skills Scan</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <?php if(in_array($_SESSION['user']->username, ["admin", "angelak20", "sjenkins", "srodway1", "dburgin1"])) { ?>
                    <span id="btnSave" class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
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

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-4">
            <div class="box box-solid box-info">
                <div class="box-header with-border">
                    <h2 class="box-title">Learner Details</h2>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr><th>Name:</th><td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td></tr>
                        <tr><th>Date of Birth:</th><td><?php echo Date::toShort($ob_learner->dob); ?></td></tr>
                        <tr><th>Postcode:</th><td><?php echo $ob_learner->home_postcode; ?></td></tr>
                        <tr><th>Email:</th><td><?php echo $ob_learner->home_email; ?></td></tr>
                        <tr>
                            <th>Employer:</th>
                            <td>
                                <?php echo $employer->legal_name; ?>

                                <?php
                                echo $employer_location->address_line_1 != '' ? $employer_location->address_line_1 . '<br>' : '';
                                echo $employer_location->address_line_2 != '' ? $employer_location->address_line_2 . '<br>' : '';
                                echo $employer_location->address_line_3 != '' ? $employer_location->address_line_3 . '<br>' : '';
                                echo $employer_location->address_line_4 != '' ? $employer_location->address_line_4 . '<br>' : '';
                                echo $employer_location->postcode != '' ? $employer_location->postcode . '<br>' : '';
                                ?>
                                </small>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="box box-solid box-info">
                <div class="box-header with-border">
                    <h2 class="box-title">Apprenticeship Details</h2>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr><th>Standard:</th><td><?php echo $framework->title; ?></td></tr>
                        <tr><th>Level:</th><td><?php echo DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';"); ?></td></tr>
                        <tr><th>Funding Band Max.:</th><td>&pound;<?php echo $skills_analysis->funding_band_maximum; ?></td></tr>
                        <tr><th>Recommended Duration:</th><td><?php echo $skills_analysis->recommended_duration; ?> months</td></tr>
                        <tr><th>Contracted Hours per Week:</th><td><?php echo $tr->contracted_hours_per_week; ?></td></tr>
                        <tr><th>EPA Organisation:</th><td><?php echo DAO::getSingleValue($link, "SELECT EP_Assessment_Organisations FROM central.`epa_organisations` WHERE EPA_ORG_ID = '{$tr->epa_organisation}';"); ?></td></tr>
                        <tr><th>EPA Price:</th><td>&pound;<?php echo $tr->epa_price; ?></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <form class="form-horizontal" name="frmEditSkillsScan" id="frmEditSkillsScan" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />
        <input type="hidden" name="_action" value="save_ss_after_submission" />

        <div class="row">
            <div class="col-sm-12">
                <div class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title">Schedule 1 Prices</h2>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <col width="15%">
                            <col width="70%">
                            <col width="30%">
                            <tr><th colspan="3" class="bg-gray">Section 9 - Proposed Cost of Training Per Apprentice</th></tr>
                            <tr><th colspan="2">The agreed charges (excluding VAT) for the training of each apprentice under this agreement is as follows:</th><th>Price per Agreement (&pound;)</th></tr>
                            <tr>
                                <th>9.1</th>
                                <th>
                                    Training Costs (all costs associated with the delivery of training, e.g. teaching, learning, assessment, reviews etc.)
                                    <br>
                                    <span class="text-info"><i class="fa fa-info-circle"></i> <i>the maximum funding band for this standard is &pound; <?php echo $framework->getFundingBandMax($link); ?></i></span>
                                </th>
                                <td>
                                    <input type="text" name="training_cost" class="form-control compulsory" onkeypress="return numbersonly();" value="<?php echo isset($detail->training_cost) ? $detail->training_cost : ''; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th>9.2</th>
                                <th>Training Materials</th>
                                <td><input type="text" name="training_material" class="form-control" onkeypress="return numbersonly();" value="<?php echo isset($detail->training_material) ? $detail->training_material : ''; ?>" /></td>
                            </tr>
                            <tr>
                                <th>9.3</th>
                                <th>Registration & Certification</th>
                                <td><input type="text" name="reg_and_cert" class="form-control" onkeypress="return numbersonly();" value="<?php echo isset($detail->reg_and_cert) ? $detail->reg_and_cert : ''; ?>" /></td>
                            </tr>
                            <tr>
                                <th>9.4</th>
                                <th>Total College Training Costs (9.1 + 9.2 + 9.3)</th>
                                <td class="bg-gray-light"><input readonly type="text" name="total_col_train_cost" class="form-control" value="<?php echo isset($detail->total_col_train_cost) ? $detail->total_col_train_cost : ''; ?>" onkeypress="return numbersonly();" /></td>
                            </tr>
                            <tr>
                                <th>9.5</th>
                                <th>End Point Assessment Costs (standards only)</th>
                                <td><input type="text" name="epa_cost" value="<?php echo round($tr->epa_price, 0); ?>" class="form-control" onkeypress="return numbersonly();" /></td>
                            </tr>
                            <tr>
                                <th>9.6</th>
                                <th>Total Negotiated Price (9.4 + 9.5)</th>
                                <td class="bg-gray-light"><input readonly type="text" name="total_negotiated_price" value="" class="form-control" onkeypress="return numbersonly();" /></td>
                            </tr>
                            <tr>
                                <th>9.7</th>
                                <th>Subcontractor Training Costs (if applicable) </th>
                                <td><input type="text" name="subcontractor_training_cost" class="form-control" onkeypress="return numbersonly();" value="<?php echo isset($detail->subcontractor_training_cost) ? $detail->subcontractor_training_cost : ''; ?>" /></td>
                            </tr>
                            <tr>
                                <th>9.8</th>
                                <th>Subcontractor Management / Monitoring Fee (if applicable)</th>
                                <td><input type="text" name="subcontractor_management_cost" class="form-control" onkeypress="return numbersonly();" value="<?php echo isset($detail->subcontractor_management_cost) ? $detail->subcontractor_management_cost : ''; ?>" /></td>
                            </tr>
                            <tr>
                                <th>9.9</th>
                                <th>Additional costs to be funded by the Employer (not eligible for Department for Education (DfE) funding)</th>
                                <td><input type="text" name="additional_costs_by_employer" class="form-control" onkeypress="return numbersonly();" value="<?php echo isset($detail->additional_costs_by_employer) ? $detail->additional_costs_by_employer : ''; ?>" /></td>
                            </tr>
                            <tr>
                                <th>9.10</th>
                                <th>Additional costs to be funded by the Training Provider (not eligible for Department for Education (DfE) funding)</th>
                                <td><input type="text" name="additional_costs_by_tp" class="form-control" onkeypress="return numbersonly();" value="<?php echo isset($detail->additional_costs_by_tp) ? $detail->additional_costs_by_tp : ''; ?>" /></td>
                            </tr>
                        </table>

                        <table class="table table-bordered">
                            <col width="25%">
                            <col width="25%">
                            <col width="25%">
                            <col width="25%">
                            <tr><th colspan="4" class="bg-gray">Section 10 - Total Cost of Training Paid to the Training Provider</th></tr>
                            <tr class="text-center">
                                <th>Levy Paying Employers</th>
                                <th>Co-Funded Employers</th>
                                <th>Government Contribution</th>
                                <th>Government Contribution - SME</th>
                            </tr>
                            <tr class="text-center">
                                <td>Maximum Employer Contribution via Levy - 100%</td>
                                <td>0% or 5% Employer Contribution</td>
                                <td>95%</td>
                                <td>100%</td>
                            </tr>
                            <tr class="text-center">
                                <?php
                                if($employer->funding_type == "L") // show first box only
                                {
                                    echo isset($detail->cost_paid_to_barnsley1) ?
                                        '<td class="bg-gray-light"><input readonly type="text" class="form-control" name="cost_paid_to_barnsley1" value="' . $detail->cost_paid_to_barnsley1 . '" onkeypress="return numbersonly();"></td>' :
                                        '<td class="bg-gray-light"><input readonly type="text" class="form-control" name="cost_paid_to_barnsley1" value="" onkeypress="return numbersonly();"></td>';
                                    echo '<td><input type="hidden" class="form-control" name="cost_paid_to_barnsley2" ></td>';
                                    echo '<td><input type="hidden" class="form-control" name="cost_paid_to_barnsley3" ></td>';
                                    echo '<td><input type="hidden" class="form-control" name="cost_paid_to_barnsley4" ></td>';
                                }
                                else
                                {
                                    if(in_array($employer->code, [3, 4]) || $learner_age >= 19) // then show 2nd and 3rd box
                                    {
                                        echo '<td><input type="hidden" class="form-control" name="cost_paid_to_barnsley1" ></td>';
                                        echo isset($detail->cost_paid_to_barnsley2) ?
                                            '<td class="bg-gray-light"><input readonly type="text" class="form-control" name="cost_paid_to_barnsley2" value="' . $detail->cost_paid_to_barnsley2 . '" onkeypress="return numbersonly();"></td>' :
                                            '<td class="bg-gray-light"><input readonly type="text" class="form-control" name="cost_paid_to_barnsley2" value="" onkeypress="return numbersonly();"></td>';
                                        echo isset($detail->cost_paid_to_barnsley3) ?
                                            '<td class="bg-gray-light"><input readonly type="text" class="form-control" name="cost_paid_to_barnsley3" value="' . $detail->cost_paid_to_barnsley3 . '" onkeypress="return numbersonly();"></td>' :
                                            '<td class="bg-gray-light"><input readonly type="text" class="form-control" name="cost_paid_to_barnsley3" value="" onkeypress="return numbersonly();"></td>';
                                        echo '<td><input type="hidden" class="form-control" name="cost_paid_to_barnsley4" ></td>';

                                    }
                                    else // small employer with less than 50 employees and learner is also < 19 years
                                    {
                                        echo '<td><input type="hidden" class="form-control" name="cost_paid_to_barnsley1" ></td>';
                                        echo '<td><input type="hidden" class="form-control" name="cost_paid_to_barnsley2" ></td>';
                                        echo '<td><input type="hidden" class="form-control" name="cost_paid_to_barnsley3" ></td>';
                                        echo isset($detail->cost_paid_to_barnsley4) ?
                                            '<td class="bg-gray-light"><input readonly type="text" class="form-control" name="cost_paid_to_barnsley4" value="' . $detail->cost_paid_to_barnsley4 . '" onkeypress="return numbersonly();"></td>' :
                                            '<td class="bg-gray-light"><input readonly type="text" class="form-control" name="cost_paid_to_barnsley4" value="" onkeypress="return numbersonly();"></td>';
                                    }
                                }
                                ?>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title">Skills Scan</h2>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="new_recommended_duration" class="col-sm-3 control-label fieldLabel_compulsory">Recommended Duration:</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control compulsory" name="new_recommended_duration" id="new_recommended_duration" value="<?php echo $skills_analysis->recommended_duration; ?>" onkeypress="return numbersonly();" />
                            </div>
                        </div>

                        <table class="table table-bordered">
                            <tr>
                                <th>Knowledge, Skills & Behaviours</th>
                                <th>Topic</th>
                                <th>What is required?</th>
                                <th style="width: 25%;">Score 1-5 <small>(please select from drop down list)</small></th>
                                <th style="width: 20%;">Comments/Details</th>
                                <th class="small">Delivery Plan Hours (100%)</th>
                                <th class="small">Delivery Plan Hours (following assessment)</th>
                            </tr>
                            <?php
                            $ksb_result = DAO::getResultset($link, "SELECT * FROM ob_learner_ksb WHERE tr_id = '{$tr->id}' ORDER BY id", DAO::FETCH_ASSOC);
                            $delivery_plan_total_ba = $total_planned_hours;
                            $delivery_plan_total_fa = 0;

                            $ksb_ids = [];
                            foreach($ksb_result AS $row)
                            {
                                $ksb_ids[] = $row['id'];
                                $delivery_plan_hours = 0;
                                $del_hours = $row['del_hours'] != '' ? floatval($row['del_hours']) : 0;
                                echo '<tr class="ss_row">';
                                echo '<td class="ss_row_unit_group">' . $row['unit_group'] .'</td>';
                                echo '<td class="small ss_row_unit_title">' . $row['unit_title'] .'</td>';
                                echo '<td class="ss_row_evidence_title">' . HTML::cell($row['evidence_title']) .'</td>';
                                echo '<td class="ss_row_score">' . HTML::selectChosen('score_' . $row['id'], $scores, $row['score'], false) .'</td>';
                                echo '<td class="ss_row_comments"><textarea name="comments_' . $row['id'] . '" id="comments_' . $row['id'] . '" style="width: 100%;">' . $row['comments'] . '</textarea></td>';
                                echo '<td class="ss_row_del_hours"><input class="delHours" type="text" name="del_hours_' . $row['id'] . '" id="del_hours_' . $row['id'] . '" value="'.$row['del_hours'].'" onkeypress="return numbersonlywithpoint(this);" onblur="updateRowFromDelHours(this);"></td>';
                                echo '<td class="ss_row_hours_fa">';
                                if($row['score'] == 5)
                                    $delivery_plan_hours = ceil($del_hours * 0.25);
                                elseif($row['score'] == 4)
                                    $delivery_plan_hours = ceil($del_hours * 0.5);
                                elseif($row['score'] == 3)
                                    $delivery_plan_hours = ceil($del_hours * 0.75);
                                elseif($row['score'] == 2)
                                    $delivery_plan_hours = ceil($del_hours * 0.9);
                                elseif($row['score'] == 1)
                                    $delivery_plan_hours = $del_hours;
                                echo $delivery_plan_hours;
                                echo '</td>';
                                echo '</tr>';
                                $delivery_plan_total_fa += $delivery_plan_hours;
                            }
                            echo '<tr><th></th><th></th><th></th><th></th><th></th>';
                            echo '<th class="bg-light-blue" id="colDeliveryPlanTotalBa">' . $delivery_plan_total_ba . '</th>';
                            echo '<input type="hidden" name="txtDeliveryPlanTotalBa" value="'.$delivery_plan_total_ba.'" /> ';
                            echo '<th class="bg-light-blue" id="colDeliveryPlanTotalFa">' . ceil($delivery_plan_total_fa) . '</th></tr>';
                            echo '<input type="hidden" name="txtDeliveryPlanTotalFa" value="0" /> ';
                            echo '<tr>';
                            echo '<th colspan="6" class="text-right bg-green-gradient"> Percentage following assessment</th>';
                            echo '<th class="text-center bg-green-gradient" id="colDeliveryPlanPercentageFa">' . round(($delivery_plan_total_fa/$delivery_plan_total_ba)*100, 0) . '%</th>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<th colspan="6" class="text-right bg-green-gradient"> Duration following assessment (months)</th>';
                            //                            echo '<th class="text-center bg-green-gradient" id="maani">' . $skills_analysis->max_duration_fa . ' months</th>';
                            echo '<th class="text-center">';
                            $_max_duration_fa = intval($skills_analysis->max_duration_fa);
                            $_recommended_duration = intval($skills_analysis->recommended_duration);
                            $_months = [];
                            for($i = 1; $i <= 50; $i++)
                            {
                                $_months[] = [$i, $i];
                            }
                            echo HTML::select('overwrite_max_duration_fa', $_months, $skills_analysis->max_duration_fa, false);
                            echo '</th>';
                            echo '</tr>';

                            ?>
                            <input type="hidden" name="ksb_ids" value="<?php echo implode(',', $ksb_ids); ?>">
                        </table>

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
<script src="js/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>

<script language="JavaScript">

    var phpObLearnerId = '<?php echo $tr->id; ?>';

    var phpTotalPlannedHours = parseInt('<?php echo $total_planned_hours; ?>');

    var recommendedDuration = <?php echo $skills_analysis->recommended_duration; ?>;

    $(function(){

        $("select[name^=score_]").on('change', function(){
            updateRowFromScore(this);
        });

    });

    function updateRowFromDelHours(textbox)
    {
        var delHours = textbox.value;
        if(delHours.trim() === '')
            delHours = 0;
        delHours = parseFloat(delHours);
        var row_id = textbox.id.replace('del_hours_', '');
        var score = $("#score_"+row_id).val();
        var dph = applyPercentageBasedOnScore(score, delHours);

        $(textbox).closest('tr').find('.ss_row_hours_fa').html(Math.ceil(dph));

        updateTotalDeliveryPlanHoursBa();
        updateTotalDeliveryPlanHoursFa();
        updateDeliveryPlanPercentageFa();
    }

    function updateRowFromScore(select)
    {
        var delHours = $(select).closest('tr').find('.delHours').val();
        if(delHours.trim() === '')
            delHours = 0;
        delHours = parseFloat(delHours);
        var score = parseFloat(select.value);
        var dph = applyPercentageBasedOnScore(score, delHours);

        $(select).closest('tr').find('.ss_row_hours_fa').html(Math.ceil(dph));

        updateTotalDeliveryPlanHoursBa();
        updateTotalDeliveryPlanHoursFa();
        updateDeliveryPlanPercentageFa();
    }

    function updateTotalDeliveryPlanHoursBa()
    {
        var sum = 0;

        // sum delivery plan hours Ba of all questions
        $("input[name^=del_hours_]").each(function() {
            var value = $(this).val();
            if(!isNaN(value)) {
                sum = parseFloat(sum) + parseFloat(value);
            }
        });

        // update the total
        $('th#colDeliveryPlanTotalBa').html(sum.toFixed(0));
    }

    function updateTotalDeliveryPlanHoursFa()
    {
        var sum = 0;

        // sum delivery plan hours Fa of all questions
        $(".ss_row_hours_fa").each(function() {
            var value = $(this).html();
            if(!isNaN(value) && value.length != 0) {
                sum = parseFloat(sum) + parseFloat(value);
            }
        });

        // update the total
        $('th#colDeliveryPlanTotalFa').html(sum.toFixed(0));
    }

    function updateDeliveryPlanPercentageFa()
    {
        var ba = parseInt($('th#colDeliveryPlanTotalBa').html());
        var fa = parseInt($('th#colDeliveryPlanTotalFa').html());

        var p = (fa/ba)*100;
        p = parseFloat(p).toFixed(0);
        $('th#colDeliveryPlanPercentageFa').html(p + '%');
        var duration = recommendedDuration*(p/100);
        $('#overwrite_max_duration_fa').val(duration.toFixed(0));
        console.log(duration);
    }

    function applyPercentageBasedOnScore(score, delHours)
    {
        delHours = parseFloat(delHours);
        score = parseFloat(score);

        var dph = 0;
        if(score == 5)
            dph = delHours * 0.25;
        else if(score == 4)
            dph = delHours * 0.5;
        else if(score == 3)
            dph = delHours * 0.75;
        else if(score == 2)
            dph = delHours * 0.9;
        else if(score == 1)
            dph = delHours;

        return dph;
    }

    function save()
    {
        if (window.saveLock)
        {
            return;
        }

        window.saveLock = true;
        $('#btnSave').attr('disabled', true);
        $('#btnSave').html('<i class="fa fa-refresh fa-spin"></i> Saving ...');

        var myForm = document.forms["frmEditSkillsScan"];
        if(!validateForm(myForm))
        {
            window.saveLock = false;
            $('#btnSave').attr('disabled', false);
            $('#btnSave').html('<i class="fa fa-save"></i> Save');
            return;
        }

        if(parseInt(myForm.overwrite_max_duration_fa.value) < 12 )
        {
            if(!confirm("The duration is less than 12 months. Click OK to proceed or click Cancel to change details."))
            {
                window.saveLock = false;
                $('#btnSave').attr('disabled', false);
                $('#btnSave').html('<i class="fa fa-save"></i> Save');
                return;
            }
        }

        myForm.submit();
    }

    $(function(){

        $("input[name=training_cost], input[name=training_material], input[name=reg_and_cert], input[name=epa_cost]").on('change', function(){
            updateStats();
        });

        var frmEditSkillsScan = document.forms["frmEditSkillsScan"];
        var v91 = frmEditSkillsScan.training_cost.value == '' ? 0 : parseFloat(frmEditSkillsScan.training_cost.value);
        var v92 = frmEditSkillsScan.training_material.value == '' ? 0 : parseFloat(frmEditSkillsScan.training_material.value);
        var v93 = frmEditSkillsScan.reg_and_cert.value == '' ? 0 : parseFloat(frmEditSkillsScan.reg_and_cert.value);

        var v94 = v91+v92+v93;
        frmEditSkillsScan.total_col_train_cost.value = v94;

        var v95 = frmEditSkillsScan.epa_cost.value == '' ? 0 : parseFloat(frmEditSkillsScan.epa_cost.value);

        var v96 = v94+v95;
        frmEditSkillsScan.total_negotiated_price.value = v96;
        frmEditSkillsScan.cost_paid_to_barnsley1.value = v96;
        frmEditSkillsScan.cost_paid_to_barnsley4.value = v96;
        frmEditSkillsScan.cost_paid_to_barnsley2.value = (v96*0.05).toFixed(0);
        frmEditSkillsScan.cost_paid_to_barnsley3.value = (v96*0.95).toFixed(0);

    });

    function updateStats()
    {
        var frmEditSkillsScan = document.forms["frmEditSkillsScan"];
        var v91 = frmEditSkillsScan.training_cost.value == '' ? 0 : parseFloat(frmEditSkillsScan.training_cost.value);
        var v92 = frmEditSkillsScan.training_material.value == '' ? 0 : parseFloat(frmEditSkillsScan.training_material.value);
        var v93 = frmEditSkillsScan.reg_and_cert.value == '' ? 0 : parseFloat(frmEditSkillsScan.reg_and_cert.value);

        var v94 = v91+v92+v93;
        frmEditSkillsScan.total_col_train_cost.value = v94;

        var v95 = frmEditSkillsScan.epa_cost.value == '' ? 0 : parseFloat(frmEditSkillsScan.epa_cost.value);

        var v96 = v94+v95;
        frmEditSkillsScan.total_negotiated_price.value = v96;
        frmEditSkillsScan.cost_paid_to_barnsley1.value = v96;
        frmEditSkillsScan.cost_paid_to_barnsley4.value = v96;
        frmEditSkillsScan.cost_paid_to_barnsley2.value = (v96*0.05).toFixed(0);
        frmEditSkillsScan.cost_paid_to_barnsley3.value = (v96*0.95).toFixed(0);

    }

</script>

</body>
</html>