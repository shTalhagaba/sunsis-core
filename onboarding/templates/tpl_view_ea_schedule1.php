<?php /* @var $employer Employer */ ?>
<?php /* @var $employer_location Location */ ?>
<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>
<?php /* @var $framework Framework */ ?>
<?php /* @var $skills_analysis SkillsAnalysis */ ?>
<?php /* @var $link PDO */ ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Employer Agreement - Schedule 1</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        html,
        body {
            height: 100%;
            font-size: medium;
        }
        textarea, input[type=text] {
            border:1px solid #3366FF;
            border-radius: 5px;
            border-left: 5px solid #3366FF;
        }
        input[type=checkbox], input[type=radio] {
            transform: scale(1.4);
        }
        .sigbox {
            border-radius: 15px;
            border: 1px solid #EEE;
            cursor: pointer;
        }
        .sigboxselected {
            border-radius: 25px;
            border: 2px solid #EEE;
            cursor: pointer;
            background-color: #d3d3d3;
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
            <div class="Title" style="margin-left: 6px;">View Employer Apprenticeship Agreement Schedule 1</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
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

<div class="content-wrapper bg-gray-light" >

    <section class="content-header text-center"><h1><strong>Employer Apprenticeship Agreement Schedule 1</strong></h1></section>

    <section class="content">
        <div class="container container-table">
            <div class="row vertical-center-row">
                <div class="col-md-10 col-md-offset-1" style="background-color: white;">
                    <p><br></p>

                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <img class="img-responsive" src="<?php echo $logo;?>" />
                        </div>
                        <div class="col-sm-4"></div>
                    </div>

                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmSchedule">
                        <input type="hidden" name="_action" value="save_sign_ea_schedule1">
                        <input type="hidden" name="id" value="<?php echo isset($schedule->id) ? $schedule->id : ''; ?>">
                        <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>">
                        <input type="hidden" name="employer_id" value="<?php echo $employer->id; ?>">
                        <input type="hidden" name="key" value="">

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col width="15%">
                                    <col width="20%">
                                    <col width="20%">
                                    <col width="20%">
                                    <col width="20%">
                                    <tr><th colspan="6" class="bg-gray">Section 1 - Employer and Apprentice Details</th></tr>
                                    <tr>
                                        <th rowspan="4">1.1</th>
                                    </tr>
                                    <tr>
                                        <th>Name of Employer</th>
                                        <td colspan="3"><?php echo $employer->legal_name; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Contact Name</th>
                                        <td><?php echo $mainLocation->contact_name; ?></td>
                                        <th>Contact Tel No.</th>
                                        <td><?php echo $mainLocation->contact_telephone; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Contact Email</th>
                                        <td colspan="3"><?php echo $mainLocation->contact_email; ?></td>
                                    </tr>
                                    <tr>
                                        <th rowspan="4">1.2</th>
                                    </tr>
                                    <tr>
                                        <th>Name of Apprentice</th>
                                        <td colspan="3"><?php echo $ob_learner->firstnames  . ' ' . $ob_learner->surname; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Date of Birth</th>
                                        <td><?php echo Date::toShort($ob_learner->dob); ?></td>
                                        <th>Age at start of apprenticeship</th>
                                        <td><?php echo Date::dateDiff(date("Y-m-d"), $ob_learner->dob); ?></td>
                                    </tr>
                                    <tr>
                                        <th>ULN</th>
                                        <td><?php echo $ob_learner->uln; ?></td>
                                        <th>Cohort</th>
                                        <td><?php echo $framework->title; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col width="15%">
                                    <col width="30%">
                                    <col width="70%">
                                    <tr><th colspan="3" class="bg-gray">Section 2 - Apprenticeship Programme</th></tr>
                                    <tr>
                                        <th>2.1</th>
                                        <th>Apprentice Job Title</th>
                                        <td>
                                            <?php echo $apprentice_job_title; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>2.2</th>
                                        <th>Standard</th>
                                        <td><?php echo $framework->title; ?></td>
                                    </tr>
                                    <tr>
                                        <th>2.3</th>
                                        <th>Level of Apprenticeship</th>
                                        <td><?php echo DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';"); ?></td>
                                    </tr>
                                    <tr>
                                        <th>2.4</th>
                                        <th>Title of Apprenticeship</th>
                                        <td><?php echo $framework->getStandardCodeDesc($link); ?></td>
                                    </tr>
                                    <tr>
                                        <th>2.5</th>
                                        <th>Location of Training</th>
                                        <td>
                                            <?php
                                            echo $employer_location->address_line_1 != '' ? $employer_location->address_line_1 . '<br>' : '';
                                            echo $employer_location->address_line_2 != '' ? $employer_location->address_line_2 . '<br>' : '';
                                            echo $employer_location->address_line_3 != '' ? $employer_location->address_line_3 . '<br>' : '';
                                            echo $employer_location->address_line_4 != '' ? $employer_location->address_line_4 . '<br>' : '';
                                            echo $employer_location->postcode != '' ? $employer_location->postcode . '<br>' : '';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>2.6</th>
                                        <th>Proposed Start Date</th>
                                        <td>
                                            <?php echo isset($detail->proposed_start_date) ?
                                                HTML::datebox('proposed_start_date', $detail->proposed_start_date, true) :
                                                HTML::datebox('proposed_start_date', '', true); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>2.7</th>
                                        <th>Proposed End Date<br><small>(for practical training)</th>
                                        <td>
                                            <?php echo isset($detail->proposed_end_date) ?
                                                HTML::datebox('proposed_end_date', $detail->proposed_end_date, true) :
                                                HTML::datebox('proposed_end_date', '', true); ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col width="15%">
                                    <col width="30%">
                                    <col width="70%">
                                    <tr><th colspan="3" class="bg-gray">Section 3 - Training Provider Actions</th></tr>
                                    <tr>
                                        <th>3.1</th>
                                        <th>Training to be delivered by the<br>Training Provider</th>
                                        <td>
                                            <textarea name="training_by_provider" id="training_by_provider" rows="5" style="width: 100%;"><?php echo isset($detail->training_by_provider) ? $detail->training_by_provider : $framework->training_by_provider; ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>3.2</th>
                                        <th>Trainer</th>
                                        <td>
                                            <?php
                                            $trainers_ids = explode(",", $tr->trainers);
                                            foreach($trainers_ids AS $_t_id)
                                                echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$_t_id}'") . '<br>';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>3.3</th>
                                        <th>Training Provider Equipment</th>
                                        <td>
                                            <textarea name="provider_equipment" id="provider_equipment" rows="5" style="width: 100%;"><?php echo isset($detail->provider_equipment) ? $detail->provider_equipment : $framework->provider_equipment; ?></textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col width="15%">
                                    <col width="30%">
                                    <col width="70%">
                                    <tr><th colspan="3" class="bg-gray">Section 4 - Employer Actions</th></tr>
                                    <tr>
                                        <th>4.1</th>
                                        <th>Training to be delivered by the<br>Employer</th>
                                        <td>
                                            <textarea name="training_by_employer" id="training_by_employer" rows="5" style="width: 100%;"><?php echo isset($detail->training_by_employer) ? $detail->training_by_employer : $framework->training_by_employer; ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>4.2</th>
                                        <th>Employer Equipment</th>
                                        <td>
                                            <textarea name="employer_equipment" id="employer_equipment" rows="5" style="width: 100%;"><?php echo isset($detail->employer_equipment) ? $detail->employer_equipment : $framework->employer_equipment; ?></textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col width="15%">
                                    <col width="30%">
                                    <col width="70%">
                                    <tr><th colspan="3" class="bg-gray">Section 5 - End-Point Assessment (EPA) Organisation - Standards Only</th></tr>
                                    <tr>
                                        <th>5.1</th>
                                        <th>Name of EPA Organisation</th>
                                        <td>
                                            <?php echo $tr->getEpaOrgName($link); ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col width="15%">
                                    <col width="30%">
                                    <col width="70%">
                                    <tr><th colspan="3" class="bg-gray">Section 6 - Subcontracting</th></tr>
                                    <tr>
                                        <th>6.1</th>
                                        <th>Name of Subcontractor</th>
                                        <td><?php echo $tr->getSubcontractorLegalName($link) ?></td>
                                    </tr>
                                    <tr>
                                        <th>6.2</th>
                                        <th>Training to be delivered by<br>Subcontractor</th>
                                        <td>
                                            <textarea name="training_by_subcontractor" id="training_by_subcontractor" rows="5" style="width: 100%;"><?php echo isset($detail->training_by_subcontractor) ? $detail->training_by_subcontractor : $framework->training_by_subcontractor; ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>6.3</th>
                                        <th>UKPRN</th>
                                        <td>
                                            <?php echo DAO::getSingleValue($link, "SELECT ukprn FROM organisations WHERE id = '{$tr->subcontractor_id}'"); ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col width="15%">
                                    <col width="30%">
                                    <col width="70%">
                                    <tr><th colspan="3" class="bg-gray">Section 7 - Functional Skills required for this Apprenticeship (not the individual)</th></tr>
                                    <tr>
                                        <th>7.1</th>
                                        <th>Maths</th>
                                        <td>
                                            <?php
                                            $_e = DAO::getSingleValue($link, "SELECT COUNT(*) FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}' AND qualification_type = 'FS' AND LOWER(title) LIKE '%english%';");
                                            echo $_e > 0 ? 'Yes' : 'No';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>7.2</th>
                                        <th>English</th>
                                        <td>
                                            <?php
                                            $_m = DAO::getSingleValue($link, "SELECT COUNT(*) FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}' AND qualification_type = 'FS' AND LOWER(title) LIKE '%math%';");
                                            echo $_m > 0 ? 'Yes' : 'No';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>7.3</th>
                                        <th>ICT</th>
                                        <td>
                                            <?php
                                            $_ict = DAO::getSingleValue($link, "SELECT COUNT(*) FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}' AND qualification_type = 'FS' AND LOWER(title) LIKE '%ict%';");
                                            echo $_ict > 0 ? 'Yes' : 'No';
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col width="15%">
                                    <col width="70%">
                                    <col width="30%">
                                    <tr><th colspan="3" class="bg-gray">Section 8 - Non-Funded Items</th></tr>
                                    <tr><th colspan="2">Detail of items not eligible for funding</th><th>Cost (&pound;)</th></tr>
                                    <tr>
                                        <th>8.1</th>
                                        <td><input class="form-control" type="text" name="items_not_eligible_for_funding1" value="<?php echo isset($detail->items_not_eligible_for_funding1) ? $detail->items_not_eligible_for_funding1 : $framework->items_not_eligible_for_funding1; ?>" /></td>
                                        <td><input type="text" name="cost_of_items_not_eligible_for_funding1" onkeypress="return numbersonly();" value="<?php echo isset($detail->cost_of_items_not_eligible_for_funding1) ? $detail->cost_of_items_not_eligible_for_funding1 : $framework->cost_of_items_not_eligible_for_funding1; ?>" /></td>
                                    </tr>
                                    <tr>
                                        <th>8.2</th>
                                        <td><input class="form-control" type="text" name="items_not_eligible_for_funding2" value="<?php echo isset($detail->items_not_eligible_for_funding2) ? $detail->items_not_eligible_for_funding2 : $framework->items_not_eligible_for_funding2; ?>" /></td>
                                        <td><input type="text" name="cost_of_items_not_eligible_for_funding2" onkeypress="return numbersonly();" value="<?php echo isset($detail->cost_of_items_not_eligible_for_funding2) ? $detail->cost_of_items_not_eligible_for_funding2 : $framework->cost_of_items_not_eligible_for_funding2; ?>" /></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col width="15%">
                                    <col width="70%">
                                    <col width="30%">
                                    <tr><th colspan="3" class="bg-gray">Section 9 - Proposed Cost of Training Per Apprentice</th></tr>
                                    <tr><th colspan="2">The agreed charges (excluding VAT) for the training of each apprentice under this agreement is as follows:</th><th>Price per Agreement (&pound;)</th></tr>
                                    <tr>
                                        <th>9.1</th>
                                        <th>
                                            Training Costs (all costs associated with the delivery of training, e.g. teaching, learning, assessment, reviews & OTJ etc.)
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
                                        <th>Registration, Examination & Certification cost associated with mandatory qualifications</th>
                                        <td><input type="text" name="reg_and_cert" class="form-control" onkeypress="return numbersonly();" value="<?php echo isset($detail->reg_and_cert) ? $detail->reg_and_cert : ''; ?>" /></td>
                                    </tr>
                                    <tr>
                                        <th>9.4</th>
                                        <th>Total College Training Costs - TNP1 (9.1 + 9.2 + 9.3)</th>
                                        <td class="bg-gray-light"><input readonly type="text" name="total_col_train_cost" class="form-control" value="<?php echo isset($detail->total_col_train_cost) ? $detail->total_col_train_cost : ''; ?>" onkeypress="return numbersonly();" /></td>
                                    </tr>
                                    <tr>
                                        <th>9.5</th>
                                        <th>End Point Assessment Costs - TNP2 (standards only)</th>
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
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
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

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <tr><th class="bg-gray">Section 11 - Additional Details (details supporting the negotiated costs / reduced rates)</th></tr>
                                    <tr>
                                        <td>
                                            <p>The negotiated price will be confirmed with the Employer after the Skills Analysis has taken place, together with the first visit from the trainer.</p>
                                            <p>Please enter additional details in the box below:</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><textarea name="section11_additional_details" id="section11_additional_details" style="width: 100%;" rows="10"><?php echo isset($detail->section11_additional_details) ? $detail->section11_additional_details : ''; ?></textarea></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <tr><th class="bg-gray">Section 12 - Additional Payments</th></tr>
                                    <tr>
                                        <td>
                                            <p class="text-bold">16-18 Employer Incentive / 19-24 Education Health Care Plan</p>
                                            <p>
                                                The training provider and employer will receive a payment towards the additional cost associated with training
                                                if, at the start of the apprenticeship, the apprentice is:
                                            </p>
                                            <ul style="margin-left: 5px;">
                                                <li class="text-bold">
                                                    Aged between 16 and 18 years old (or 15 years of age if the apprentice's 16th birthday
                                                    is between the last Friday of June and 31 August).
                                                </li>
                                                <li class="text-bold">
                                                    Aged between 19 and 24 years old and has either an Education, Health and Care (EHC) plan
                                                    provided by their local authority or has been in the care of thier local authority.
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                <input class="clsICheck" type="checkbox" disabled value="1"
                                                    <?php echo (isset($detail->section12) && in_array(1, $detail->section12)) ? 'checked' : ''; ?>
                                                />
                                                <label>
                                                    I (the Employer) confirm I am eligible for the &pound;1,000 16-18 Employer Incentive
                                                    for the Apprentice detailed within this schedule.
                                                </label>
                                            </p>
                                            <p>
                                                <input class="clsICheck" type="checkbox" disabled value="2"
                                                    <?php echo (isset($detail->section12) && in_array(2, $detail->section12)) ? 'checked' : ''; ?>
                                                />
                                                <label>
                                                    I (the Employer) confirm I am eligible for the &pound;1,000 19-24 Education Health Care plan or care leaver
                                                    employer incentive for the Apprentice detailed within this schedule.
                                                    (Relevant evidence will be required at the beginning of the apprenticeship)
                                                </label>
                                            </p>
                                            <p>
                                                <input class="clsICheck" type="checkbox" disabled value="3"
                                                    <?php echo (isset($detail->section12) && in_array(3, $detail->section12)) ? 'checked' : ''; ?>
                                                />
                                                <label>
                                                    Not Applicable
                                                </label>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <tr><th class="bg-gray">Section 13 - Payment Schedule</th></tr>
                                    <tr>
                                        <td>
                                            <p class="text-bold">Levy Paying Employers</p>
                                            <ul style="margin-left: 5px;">
                                                <li class="text-bold">
                                                    80% of the total price will be taken from your Apprenticeship Service
                                                    account on a monthly basis, over the duration of the apprentice's programme.
                                                </li>
                                                <li class="text-bold">
                                                    20% of the total cost will be retained for achievement and/or End Point
                                                    Assessment costs and will be taken from your Apprenticeship Service Account.
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="text-bold">Co-Investor Employers</p>
                                            <ul style="margin-left: 5px;">
                                                <li class="text-bold">
                                                    Where your 5% Employer Contribution is &pound;250 or less, you will be
                                                    invoiced in full at the start of the apprenticeship programme.
                                                </li>
                                                <li class="text-bold">
                                                    Where your 5% Employer Contribution is over &pound;250, you will be invoiced in full,
                                                    and payments will be obtained on 4 equal instalments at months 1, 4, 7 and 9.
                                                </li>
                                                <li class="text-bold">
                                                    Invoices are to be paid within 30 days from the date of invoice.
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <tr><th class="bg-gray">Section 14 - Mandatory Policies</th></tr>
                                    <tr>
                                        <td>
                                            <p>Training Provider policies available to learner:</p>
                                            <ul style="margin-left: 5px;">
                                                <li>Safeguarding</li>
                                                <li>Health & Safety</li>
                                                <li>Equality & Diversity</li>
                                                <li>GDPR</li>
                                                <li>Complaints</li>
                                            </ul>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <tr><th class="bg-gray">Section 15 - Employer Declarations (Please tick Option 1 OR Option 2 and all 3 declarations further below.)</th></tr>
                                    <tr>
                                        <td>
                                            <input class="" type="radio" disabled value="1"
                                                <?php echo (isset($detail->section15radio) && $detail->section15radio == 1) ? 'checked' : ''; ?>
                                            /> <span class="text-bold" style="margin-left: 5px;">Option 1 - </span>
                                            I confirm that apprentice(s) named in this Schedule 1 has/have been issued with a contract of
                                            employment and is/will be employed for at least 30 hours per week. The minimum
                                            duration of each apprenticeship is based on the apprentice working at least 30 hours a week.
                                            <p class="text-bold">OR</p>
                                            <input class="" type="radio" disabled value="2"
                                                <?php echo (isset($detail->section15radio) && $detail->section15radio == 2) ? 'checked' : ''; ?>
                                            /> <span class="text-bold" style="margin-left: 5px;">Option 2 - </span>
                                            I confirm that apprentice(s) named in this Schedule 1 has/have been issued with a contract of
                                            employment and is/will be employed for at least 16 hours per week. I am aware that
                                            the duration of the apprenticeship will be extended accordingly to take account of this.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="" disabled type="checkbox" value="1" <?php echo (isset($detail->section15) && in_array(1, $detail->section15)) ? 'checked' : ''; ?> /> &nbsp; &nbsp;
                                            Off-the-job training has been discussed and I am aware of the requirements for this.
                                            <?php if(!$tr->postJuly25Start()) { ?>
                                            20% off-the-job training is the equivalent of 1 day per week based on a 5 day working week.
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="" disabled type="checkbox" value="2" <?php echo (isset($detail->section15) && in_array(2, $detail->section15)) ? 'checked' : ''; ?> /> &nbsp; &nbsp;
                                            The cost of this Apprenticeship has been discussed with us in detail,
                                            we fully understand the negotiated price for training and associated costs (TNP1) and we have negotiated the EPA price (TNP2).
                                            I understand that this is an indicative price at this point and is subject to change after the Skills Analysis has taken place.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="" type="checkbox" disabled value="3" <?php echo (isset($detail->section15) && in_array(3, $detail->section15)) ? 'checked' : ''; ?> /> &nbsp; &nbsp;
                                            I confirm that all apprentices listed in this schedule will spend at least
                                            50% of their working hours in England over the duration of the apprenticeship.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="" type="checkbox" disabled value="4" <?php echo (isset($detail->section15) && in_array(4, $detail->section15)) ? 'checked' : ''; ?> /> &nbsp; &nbsp;
                                            I confirm as part of our recruitment process we have check the named apprentice(s) right
                                            to work in the UK and have checked and hold copies of the relevant documentation which will be made
                                            available to the main provider when requested.
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type == User::TYPE_ADMIN) {?>
                        <div class="row">
                            <div class="col-sm-12 table-responsive">
                                <table style="margin-top: 5px;" class="table table-bordered">
                                    <tr><th colspan="4" class="bg-gray">Signatures</th></tr>
                                    <tr><th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th></tr>
                                    <tr>
                                        <td>Employer</td>
                                        <td>
                                            <?php
                                            echo (isset($schedule->emp_sign_name) && $schedule->$schedule != '') ? $schedule->emp_sign_name : '';
                                            ?>
                                        </td>
                                        <td>
                                        <span class="btn btn-info">
                                            <img id="img_emp_sign" src="do.php?_action=generate_image&<?php echo isset($detail->emp_sign) ? $detail->emp_sign : 'title=Not yet signed&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
                                        </span>
                                        </td>
                                        <td>
                                            <?php
                                            echo (isset($schedule->emp_sign_date) && $schedule->$schedule != '') ? Date::toShort($schedule->emp_sign_date) : '';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Training Provider</td>
                                        <td>
                                            <?php echo '<input type="text" class="form-control compulsory" name="tp_sign_name" id="tp_sign_name" value="' . $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname . '" />'; ?>
                                        </td>
                                        <td>
                                        <span class="btn btn-info" onclick="getSignature('tp');">
                                            <img id="img_tp_sign" src="do.php?_action=generate_image&<?php echo isset($detail->tp_sign) ? $detail->tp_sign : 'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
                                            <input type="hidden" name="tp_sign" id="tp_sign" value="<?php echo isset($detail->tp_sign) ? $detail->tp_sign : ''; ?>" />
                                        </span>
                                        </td>
                                        <td>
                                            <?php echo '<span class="content-max-width">' . HTML::datebox('tp_sign_date', date('d/m/Y')) . '</span>'; ?>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>
                        <?php } else {?>
                        <div class="row">
                            <div class="col-sm-12">
                                <hr>
                                <p class="text-bold">
                                    <input name="set_for_sign" type="checkbox" value="1" <?php echo (isset($schedule->set_for_sign) && $schedule->set_for_sign == '1') ? 'checked' : ''; ?> /> &nbsp; &nbsp;
                                    Please tick this box to confirm that this form is completed and ready for Program Manager signature.
                                    <br>
                                </p>
                            </div>
                        </div>
                        <?php } ?>

                        <div class="row">
                            <div class="col-sm-12">
                                <span class="btn btn-block btn-success btn-lg" onclick="submitSchedule();">
                                    <i class="fa fa-save"></i> Save Schedule 1 Form
                                </span>
                                <p></p>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </section>

</div>

<div id="panel_signature" title="Signature Panel">
    <div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter the name/initials, press 'Generate' and select the signature font you like and press "Create". </div>
    <div class="table-responsive">
        <table class="table row-border">
            <tr>
                <td class="small">Enter the name/initials</td>
                <td><input type="text" id="signature_text" onkeypress="return onlyAlphabets(event,this);" /> &nbsp; <span class="btn btn-xs btn-primary" onclick="refreshSignature();">Generate</span> </td>
            </tr>
            <tr>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img1" src=""  /></td>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img2" src=""  /></td>
            </tr>
            <tr>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img3" src=""  /></td>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img4" src=""  /></td>
            </tr>
            <tr>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img5" src=""  /></td>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img6" src=""  /></td>
            </tr>
            <tr>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img7" src=""  /></td>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img8" src=""  /></td>
            </tr>
        </table>
    </div>
</div>

<footer class="main-footer">
    <div class="pull-left">
        <table class="table" style="border-collapse: separate; border-spacing: 0.5em;">
            <tr>
                <td><img width="230px" src="<?php echo $logo; ?>" /></td>
            </tr>
        </table>
    </div>
    <div class="pull-right">
        <img src="images/logos/SUNlogo.png" />
    </div>
</footer>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="js/common.js"></script>

<script type="text/javascript">

    var phpManagerSignature = '';
    var phpAvgEmployeesOfEmployer = '<?php echo $avg_employees; ?>';


    var phpProviderSignature = '<?php echo (isset($detail->tp_sign) && $detail->tp_sign != '') ? $detail->tp_sign : $_SESSION['user']->signature; ?>';

    var fonts = Array("Little_Days.ttf","ArtySignature.ttf","Signerica_Medium.ttf","Champignon_Alt_Swash.ttf","Bailey_MF.ttf","Carolina.ttf","DirtyDarren.ttf","Ruf_In_Den_Wind.ttf");
    var sizes = Array(30,40,15,30,30,30,25,30);

    $(function() {


        $('.clsICheck').each(function(){
            var self = $(this),
                label = self.next(),
                label_text = label.text();

            label.remove();
            self.iCheck({
                checkboxClass: 'icheckbox_line-blue',
                insert: '<div class="icheck_line-icon"></div>' + label_text
            });
        });

        $( "#panel_signature" ).dialog({
            autoOpen: false,
            modal: true,
            draggable: false,
            width: "auto",
            height: 500,
            buttons: {
                'Create': function() {
                    var panel = $(this).data('panel');
                    if($('#signature_text').val() == '')
                    {
                        alert('Please input name/initials to generate signature.');
                        $('#signature_text').focus();
                        return;
                    }
                    if($('.sigboxselected').children('img')[0] === undefined)
                    {
                        alert('Please select your font');
                        return;
                    }
                    var sign_field = '';
                    if(panel == 'manager')
                    {
                        sign_field = 'emp_sign';
                    }
                    if(panel == 'tp')
                    {
                        sign_field = 'tp_sign';
                    }
                    $("#img_"+sign_field).attr('src', $('.sigboxselected').children('img')[0].src);
                    var _link = $('.sigboxselected').children('img')[0].src;
                    _link = _link.split('&');
                    $("#"+sign_field).val(_link[1]+'&'+_link[2]+'&'+_link[3]);
                    if($('#'+sign_field).val() == '')
                    {
                        alert('Please create your signature');
                        return;
                    }

                    $(this).dialog('close');
                },
                'Cancel': function() {$(this).dialog('close');}
            }
        });

    });

    function getSignature(user)
    {
        if(user == 'manager')
        {
            $('#signature_text').val($('#emp_sign_name').val());
            $( "#panel_signature" ).data('panel', user).dialog( "open");
            return;
        }
        else
        {
            if(window.phpProviderSignature == '')
            {
                $('#signature_text').val($('#tp_sign_name').val());
                $('#signature_text').val('');
                $( "#panel_signature" ).data('panel', 'provider').dialog( "open");
                return;
            }
            $('#img_tp_sign').attr('src', 'do.php?_action=generate_image&'+window.phpProviderSignature);
            $('#tp_sign').val(window.phpProviderSignature);
        }

    }

    function onlyAlphabets(e, t)
    {
        try {
            if (window.event) {
                var charCode = window.event.keyCode;
            }
            else if (e) {
                var charCode = e.which;
            }
            else { return true; }
            if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 32 || charCode == 39 || charCode == 45 || charCode == 8 || charCode == 46)
                return true;
            else
                return false;
        }
        catch (err) {
            alert(err.Description);
        }
    }

    function SignatureSelected(sig)
    {
        $(".sigboxselected").attr("class", "sigbox");
        sig.className = "sigboxselected";
    }

    function refreshSignature()
    {
        for(var i = 1; i <= 8; i++)
            $("#img"+i).attr('src', 'images/loading.gif');

        for(var i = 0; i <= 7; i++)
            $("#img"+(i+1)).attr('src', 'do.php?_action=generate_image&title='+$("#signature_text").val()+'&font='+fonts[i]+'&size='+sizes[i]);
    }

    function submitSchedule()
    {
        var frmSchedule = document.forms['frmSchedule'];
        if(!validateForm(frmSchedule))
        {
            return;
        }

        var proposed_start_date = stringToDate(frmSchedule.proposed_start_date.value);
        var proposed_end_date = stringToDate(frmSchedule.proposed_end_date.value);

        if(proposed_end_date <= proposed_start_date)
        {
            alert("Proposed end date must be after the proposed start date.");
            frmSchedule.proposed_end_date.focus();
            return;
        }

        var dob = stringToDate('<?php echo $ob_learner->dob; ?>');
        var y16 = new Date(dob.getFullYear() + 16, dob.getMonth(), dob.getDay());
        if(y16 >= proposed_start_date)
        {
            alert("Learner is under 16 years: based on DOB and proposed start date.");
            frmSchedule.proposed_start_date.focus();
            return;
        }

        var duration = monthDiff(proposed_start_date, proposed_end_date);
        if(duration < 12)
        {
            if(!confirm("The duration is less than 12 months, are you sure you want to continue with these proposed dates? "))
                return;
        }


        // if($('#tp_sign').val() == '')
        // {
        //     alert("Please provide your signature.");
        //     return;
        // }

        frmSchedule.submit();
    }

    function monthDiff(d1, d2)
    {
        var months;
        months = (d2.getFullYear() - d1.getFullYear()) * 12;
        months -= d1.getMonth();
        months += d2.getMonth();
        return months <= 0 ? 0 : months;
    }

    $(function(){

        $("input[name=training_cost], input[name=training_material], input[name=reg_and_cert], input[name=epa_cost]").on('change', function(){
            updateStats();
        });

        var frmSchedule = document.forms["frmSchedule"];
        var v91 = frmSchedule.training_cost.value == '' ? 0 : parseFloat(frmSchedule.training_cost.value);
        var v92 = frmSchedule.training_material.value == '' ? 0 : parseFloat(frmSchedule.training_material.value);
        var v93 = frmSchedule.reg_and_cert.value == '' ? 0 : parseFloat(frmSchedule.reg_and_cert.value);

        var v94 = v91+v92+v93;
        frmSchedule.total_col_train_cost.value = v94;

        var v95 = frmSchedule.epa_cost.value == '' ? 0 : parseFloat(frmSchedule.epa_cost.value);

        var v96 = v94+v95;
        frmSchedule.total_negotiated_price.value = v96;
        frmSchedule.cost_paid_to_barnsley1.value = v96;
        frmSchedule.cost_paid_to_barnsley4.value = v96;
        frmSchedule.cost_paid_to_barnsley2.value = (v96*0.05).toFixed(0);
        frmSchedule.cost_paid_to_barnsley3.value = (v96*0.95).toFixed(0);

    });

    function updateStats()
    {
        var frmSchedule = document.forms["frmSchedule"];
        var v91 = frmSchedule.training_cost.value == '' ? 0 : parseFloat(frmSchedule.training_cost.value);
        var v92 = frmSchedule.training_material.value == '' ? 0 : parseFloat(frmSchedule.training_material.value);
        var v93 = frmSchedule.reg_and_cert.value == '' ? 0 : parseFloat(frmSchedule.reg_and_cert.value);

        var v94 = v91+v92+v93;
        frmSchedule.total_col_train_cost.value = v94;

        var v95 = frmSchedule.epa_cost.value == '' ? 0 : parseFloat(frmSchedule.epa_cost.value);

        var v96 = v94+v95;
        frmSchedule.total_negotiated_price.value = v96;
        frmSchedule.cost_paid_to_barnsley1.value = v96;
        frmSchedule.cost_paid_to_barnsley4.value = v96;
        frmSchedule.cost_paid_to_barnsley2.value = (v96*0.05).toFixed(0);
        frmSchedule.cost_paid_to_barnsley3.value = (v96*0.95).toFixed(0);

    }

</script>

</body>
</html>
