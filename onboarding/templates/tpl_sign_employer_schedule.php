<?php /* @var $employer Employer */ ?>
<?php /* @var $employer_location Location */ ?>
<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>
<?php /* @var $framework Framework */ ?>
<?php /* @var $skills_analysis SkillsAnalysis */ ?>
<?php /* @var $link PDO */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Initial <?php echo ucwords($document_term); ?></title>
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


<br>

<div class="content-wrapper bg-gray-light" >

    <section class="content-header text-center"><h1><strong>Initial <?php echo ucwords($document_term); ?></strong></h1></section>

    <section class="content">
        <div class="container-fluid container-table">
            <div class="row vertical-center-row">
                <div class="col-sm-12" style="background-color: white;">
                    <p><br></p>

                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <img class="img-responsive" src="<?php echo $logo;?>" />
                        </div>
                        <div class="col-sm-4"></div>
                    </div>

                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmSchedule">
                        <input type="hidden" name="_action" value="save_sign_employer_schedule">
                        <input type="hidden" name="id" value="<?php echo isset($schedule->id) ? $schedule->id : ''; ?>">
                        <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>">
                        <input type="hidden" name="employer_id" value="<?php echo $employer->id; ?>" />
                        <input type="hidden" name="key" value="<?php echo $key; ?>" />

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
                                        <td colspan="3">
                                            <?php echo in_array(DB_NAME, ["am_superdrug", "am_sd_demo"]) ? $employer->brandDescription($link) : $employer->legal_name; ?><br>
                                            <small>
                                            <?php 
                                            echo $mainLocation->address_line_1 != '' ? $mainLocation->address_line_1 . '<br>' : ''; 
                                            echo $mainLocation->address_line_2 != '' ? $mainLocation->address_line_2 . '<br>' : ''; 
                                            echo $mainLocation->address_line_3 != '' ? $mainLocation->address_line_3 . '<br>' : ''; 
                                            echo $mainLocation->address_line_4 != '' ? $mainLocation->address_line_4 . '<br>' : '';
                                            echo $mainLocation->postcode != '' ? $mainLocation->postcode : '';
                                            ?>
                                            </small>
                                        </td>
                                    </tr>
                                    <tr>
                                    <th>Contact Name</th>
                                        <td>
                                            <?php 
                                            echo (isset($detail->contact_name) && $detail->contact_name != '') ? 
                                            '<input type="text" class="compulsory" value="' . $detail->contact_name . '" name="contact_name" id="contact_name" maxlength="70" />' :
                                            '<input type="text" class="compulsory" value="' . $employer_location->contact_name . '" name="contact_name" id="contact_name" maxlength="70" />'; 
                                            ?>
                                        </td>
                                        <th>Contact Tel No.</th>
                                        <td>
                                            <?php 
                                            echo (isset($detail->contact_telephone) && $detail->contact_telephone != '') ? 
                                            '<input type="text" class="compulsory" value="' . $detail->contact_telephone . '" name="contact_telephone" id="contact_telephone" maxlength="15"  />' :
                                            '<input type="text" class="compulsory" value="' . $employer_location->contact_telephone . '" name="contact_telephone" id="contact_telephone" maxlength="15" />'; 
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Contact Email</th>
                                        <td colspan="3">
                                            <?php 
                                            echo (isset($detail->contact_email) && $detail->contact_email != '') ? 
                                            '<input type="text" class="compulsory" value="' . $detail->contact_email . '" name="contact_email" id="contact_email" size="50"  maxlength="150" />' :
                                            '<input type="text" class="compulsory" value="' . $employer_location->contact_email . '" name="contact_email" id="contact_email" size="50" maxlength="150" />'; 
                                            ?>
                                        </td>
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
                                        <td>
                                            <?php echo $framework->title; ?>
                                            <?php 
                                            $max_funding_band = $framework->getFundingBandMax($link);
                                            if($max_funding_band != '') 
                                            {
                                                echo '<br><span class="text-bold">Maximum Funding Band: </span>&pound;' . $max_funding_band;
                                            }
                                            ?>
                                        </td>
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
                                        <th>Practical Period Start Date</th>
                                        <td>
                                            <?php 
                                            echo (isset($detail->practical_period_start_date) && $detail->practical_period_start_date != '') ? 
                                                HTML::datebox('practical_period_start_date', $detail->practical_period_start_date, true) :
                                                HTML::datebox('practical_period_start_date', $tr->practical_period_start_date, true); 
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>2.7</th>
                                        <th>Practical Period End Date</th>
                                        <td>
                                            <?php 
                                            echo (isset($detail->practical_period_end_date) && $detail->practical_period_end_date != '') ? 
                                                HTML::datebox('practical_period_end_date', $detail->practical_period_end_date, true) :
                                                HTML::datebox('practical_period_end_date', $tr->practical_period_end_date, true); 
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>2.10</th>
                                        <th>Planned EPA Date</th>
                                        <td>
                                            <?php 
                                            echo (isset($detail->planned_epa_date) && $detail->planned_epa_date != '') ? 
                                                HTML::datebox('planned_epa_date', $detail->planned_epa_date, true) :
                                                HTML::datebox('planned_epa_date', $tr->planned_epa_date, true); 
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>2.11</th>
                                        <th>Contracted Hours Per Week</th>
                                        <td>
                                            <?php 
                                            echo (isset($detail->contracted_hours_per_week) && $detail->contracted_hours_per_week != '') ? 
                                            '<input type="text" class="compulsory" value="' . $detail->contracted_hours_per_week . '" name="contracted_hours_per_week" id="contracted_hours_per_week" onkeypress="return numbersonlywithpoint();" maxlength="4" />' :
                                            '<input type="text" class="compulsory" value="' . $tr->contracted_hours_per_week . '" name="contracted_hours_per_week" id="contracted_hours_per_week" onkeypress="return numbersonlywithpoint();" maxlength="4" />'; 
                                            ?> (hours/week)
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>2.12</th>
                                        <th>Weeks to be worked per year</th>
                                        <td>
                                            <?php 
                                            echo (isset($detail->weeks_to_be_worked_per_year) && $detail->weeks_to_be_worked_per_year != '') ? 
                                            '<input type="text" class="compulsory" value="' . $detail->weeks_to_be_worked_per_year . '" name="weeks_to_be_worked_per_year" id="weeks_to_be_worked_per_year" onkeypress="return numbersonlywithpoint();" maxlength="4" />' :
                                            '<input type="text" class="compulsory" value="' . $tr->weeks_to_be_worked_per_year . '" name="weeks_to_be_worked_per_year" id="weeks_to_be_worked_per_year" onkeypress="return numbersonlywithpoint();" maxlength="4" />'; 
                                            ?> (weeks/year)
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
                                    <tr><th colspan="3" class="bg-gray">Section 3 - Training Provider</th></tr>
                                    <tr>
                                        <th>3.1</th>
                                        <th>Training Provider</th>
                                        <td>
                                            <?php 
                                            $provider = Organisation::loadFromDatabase($link, $tr->provider_id);
                                            $provider_location = Location::loadFromDatabase($link, $tr->provider_location_id);
                                            echo $provider->legal_name . '<br>';
                                            echo $provider_location->address_line_1 != '' ? $provider_location->address_line_1 . '<br>' : '';
                                            echo $provider_location->address_line_2 != '' ? $provider_location->address_line_2 . '<br>' : '';
                                            echo $provider_location->address_line_3 != '' ? $provider_location->address_line_3 . '<br>' : '';
                                            echo $provider_location->address_line_4 != '' ? $provider_location->address_line_4 . '<br>' : '';
                                            echo $provider_location->postcode . '<br>';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>3.1</th>
                                        <th>Contract Manager</th>
                                        <td>
                                            <?php echo isset($detail->tp_contract_manager) ? DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$detail->tp_contract_manager}'") : ''; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>3.2</th>
                                        <th>Operations Manager</th>
                                        <td>
                                            <?php echo isset($detail->tp_op_manager) ? DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$detail->tp_op_manager}'") : ''; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>3.3</th>
                                        <th>Trainer</th>
                                        <td>
                                            <?php
                                            $trainers_ids = $tr->trainers != '' ? explode(",", $tr->trainers) : [];
                                            foreach($trainers_ids AS $_t_id)
                                                echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$_t_id}'") . '<br>';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>3.4</th>
                                        <th>Training to be delivered by the<br>Training Provider</th>
                                        <td>
                                            <?php echo $detail->training_by_provider; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>3.5</th>
                                        <th>Training Provider Equipment</th>
                                        <td>
                                            <?php echo $detail->provider_equipment; ?>
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
                                            <?php echo $detail->training_by_employer; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>4.2</th>
                                        <th>Employer Equipment</th>
                                        <td>
                                            <?php echo $detail->employer_equipment; ?>
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
                                            <?php echo $detail->training_by_subcontractor; ?>
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
                                    <?php
                                    $_e = DAO::getSingleValue($link, "SELECT COUNT(*) FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}' AND qualification_type = 'FS' AND LOWER(title) LIKE '%english%';");
                                    $_m = DAO::getSingleValue($link, "SELECT COUNT(*) FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}' AND qualification_type = 'FS' AND LOWER(title) LIKE '%math%';");
                                    $_ict = DAO::getSingleValue($link, "SELECT COUNT(*) FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}' AND qualification_type = 'FS' AND LOWER(title) LIKE '%ict%';");
                                    $_e = $_e > 0 ? 'Yes' : 'No';
                                    $_m = $_m > 0 ? 'Yes' : 'No';
                                    $_ict = $_ict > 0 ? 'Yes' : 'No';
                                    ?>
                                    <tr><th colspan="3" class="bg-gray">Section 7 - Functional Skills required for this Apprenticeship (not the individual)</th></tr>
                                    <tr>
                                        <th>7.1</th>
                                        <th>Maths</th>
                                        <td>
                                            <?php
                                            if($tr->postJuly25Start($link)) 
                                            {
                                                echo '<strong>As the employer, I agree that my apprentice will:</strong><br>';
                                                echo HTML::selectChosen(
                                                    'fs_maths_opt_in', 
                                                    [['Yes', 'opt-in to complete Functional Skills Maths training and take the required assessment'], ['No', 'opt-out to complete Functional Skills Maths training and take the required assessment']], 
                                                    isset($tr->fs_maths_opt_in)?$tr->fs_maths_opt_in:'', true, false, true
                                                );
                                            }
                                            else
                                            {
                                                echo $_m;
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>7.2</th>
                                        <th>English</th>
                                        <td>
                                            <?php
                                            if($tr->postJuly25Start($link)) 
                                            {
                                                echo '<strong>As the employer, I agree that my apprentice will:</strong><br>';
                                                echo HTML::selectChosen(
                                                    'fs_eng_opt_in', 
                                                    [['Yes', 'opt-in to complete Functional Skills English training and take the required assessment'], ['No', 'opt-out to complete Functional Skills English training and take the required assessment']], 
                                                    isset($tr->fs_eng_opt_in)?$tr->fs_eng_opt_in:'', true, false, true
                                                );
                                            }
                                            else
                                            {
                                                echo $_e;
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>7.3</th>
                                        <th>ICT</th>
                                        <td>
                                            <?php echo $_ict; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <tr><th class="bg-gray">Section 8 - Proposed Cost of Training Per Apprentice (subject to RPL)</th></tr>
                                    <tr>
                                        <th>
                                            The agreed charges (excluding VAT) for the training of each apprentice under this agreement is as follows:<br>
                                            <span class="text-info"><i class="fa fa-info-circle"></i> <i>the maximum funding band for this standard is &pound; <?php echo $framework->getFundingBandMax($link); ?></i></span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <col width="70%">
                                                    <caption class="lead text-bold">TNP 1</caption>
                                                    <?php
                                                    foreach($tnp1_prices AS $tnp1)
                                                    {
                                                        echo '<tr>';
                                                        echo '<th>'.$tnp1->description.'</th>';
                                                        echo '<td>&pound;'.$tnp1->cost.'</td>';
                                                        echo '</tr>';
                                                    }
                                                    ?>
                                                    <tr><th>TNP 1 Total</th><td>&pound;<?php echo $tnp1_total; ?></td></tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <col width="70%">
                                                    <caption class="lead text-bold">TNP 2</caption>
                                                    <tr><th>EPA Cost</th><td>&pound;<?php echo $tr->epa_price; ?></td></tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <col width="70%">
                                                    <caption class="lead text-bold">TNP</caption>
                                                    <tr><th>Total Proposed Cost (TNP 1 + TNP 2)</th><td>&pound;<span class="lblTnp"><?php echo $tnp_total; ?></span></td></tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <col width="70%">
                                                    <caption class="lead text-bold">Additional Prices</caption>
                                                    <?php
                                                    foreach($additional_prices AS $additional_price)
                                                    {
                                                        echo '<tr>';
                                                        echo '<th>'.$additional_price->description.'</th>';
                                                        echo '<td>&pound;'.$additional_price->cost.'</td>';
                                                        echo '</tr>';
                                                    }
                                                    ?>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

			<?php if($tr->practical_period_start_date < '2024-04-01') { ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col width="20%">
                                    <col width="20%">
                                    <col width="20%">
                                    <col width="20%">
                                    <col width="20%">
                                    <tr>
                                        <th colspan="5" class="bg-gray">Section 9 - Total Cost of Training Paid to the Training Provider (subject to RPL)</th>
                                    </tr>
                                    <?php if(in_array(DB_NAME, ["am_crackerjack"])){?>
                                    <tr>
                                        <th>West Midlands Combined Authority</th>
                                        <td>
                                            <input type="checkbox" name="wm_auth" value="1" disabled <?php echo (isset($detail->wm_auth) && $detail->wm_auth == '1') ? 'checked' : ''; ?> /> 
                                        </td>
                                        <th></th>
                                        <td></td>
                                    </tr>
                                    <?php } ?>
                                    <tr class="text-center">
                                        <th class="text-center">Levy Paying Employers</th>
                                        <th class="text-center">Co-Funded Employers</th>
                                        <th class="text-center">Government Contribution</th>
                                        <th class="text-center">Government Contribution - SME</th>
                                        <th class="text-center">Levy Gifted</th>
                                    </tr>
                                    <tr class="text-center">
                                        <td>Maximum Employer Contribution via Levy - 100%</td>
                                        <td>0% or 5% Employer Contribution</td>
                                        <td>95%</td>
                                        <td>100%</td>
                                        <td>100%</td>
                                    </tr>
				    <tr class="text-center">
                                    <?php
                                    $five_percent_tnp = ceil(($tnp_total*5)/100);
                                    $ninety_five_percent_tnp = ceil(($tnp_total*95)/100);
                                    
                                    if ($employer->funding_type == "LG" && $tr->type_of_funding == "Levy Gifted") 
                                    {
                                        echo '<td><span id="s9td1" style="display: none;">&pound;>&pound;' . $tnp_total . '</span></td>';
                                        echo '<td><span id="s9td2" style="display: none;">&pound;' . $five_percent_tnp . '</span></td>';
                                        echo '<td><span id="s9td3" style="display: none;">&pound;' . $ninety_five_percent_tnp . '</span></td>';
                                        echo '<td><span id="s9td4" style="display: none;">&pound;' . $tnp_total . '</span></td>';
                                        echo '<td><span id="s9td5">&pound;' . $tnp_total . '</span></td>';
                                    } 
                                    elseif ($employer->funding_type == "L") // show first box only
                                    {
                                        echo '<td><span id="s9td1">&pound;' . $tnp_total . '</span></td>';
                                        echo '<td><span id="s9td2" style="display: none;">&pound;' . $five_percent_tnp . '</span></td>';
                                        echo '<td><span id="s9td3" style="display: none;">&pound;' . $ninety_five_percent_tnp . '</span></td>';
                                        echo '<td><span id="s9td4" style="display: none;">&pound;' . $tnp_total . '</span></td>';
                                        echo '<td><span id="s9td5" style="display: none;">&pound;' . $tnp_total . '</span></td>';
                                    } 
                                    else 
                                    {
                                        if (in_array($employer->code, [1, 2, 3, 6]) || $learner_age >= 19) // then show 2nd and 3rd box
                                        {
                                            echo '<td><span id="s9td1" style="display: none;">&pound;' . $tnp_total . '</span></td>';
                                            echo '<td><span id="s9td2">&pound;' . $five_percent_tnp . '</span></td>';
                                            echo '<td><span id="s9td3">&pound;' . $ninety_five_percent_tnp . '</span></td>';
                                            echo '<td><span id="s9td4" style="display: none;">&pound;' . $tnp_total . '</span></td>';
                                        	echo '<td><span id="s9td5" style="display: none;">&pound;' . $tnp_total . '</span></td>';
                                        } 
                                        else // small employer with less than 50 employees and learner is also < 19 years
                                        {
                                            echo '<td><span id="s9td1" style="display: none;">&pound;' . $tnp_total . '</span></td>';
                                            echo '<td><span id="s9td2" style="display: none;">&pound;' . $five_percent_tnp . '</span></td>';
                                            echo '<td><span id="s9td3" style="display: none;">&pound;' . $ninety_five_percent_tnp . '</span></td>';
                                            echo '<td><span id="s9td4">&pound;' . $tnp_total . '</span></td>';
                                        	echo '<td><span id="s9td5" style="display: none;">&pound;' . $tnp_total . '</span></td>';
                                        }
                                    }
                                    ?>
				    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <i class="fa fa-info-circle"></i> 
                                            The Department for Education (DfE) will fund 95% of the Apprenticeship programme, with the Employer contributing the other 5%. 
                                            This Co-Investment is not applicable for small employers with less than 50 employees if they take on a 16-18 year old or a 19-23 year old with an EHC plan. 
                                            Delivery of Maths and English will be paid directly to Training Provider via the Department for Education (DfE).
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
			<?php } else { ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col width="20%">
                                    <col width="20%">
                                    <col width="20%">
                                    <col width="20%">
                                    <col width="20%">
                                    <tr>
                                        <th colspan="5" class="bg-gray">Section 9 - Total Cost of Training Paid to the Training Provider (subject to RPL)</th>
                                    </tr>
                                    <?php if(in_array(DB_NAME, ["am_crackerjack"])){?>
                                    <tr>
                                        <th>West Midlands Combined Authority</th>
                                        <td>
                                            <input type="checkbox" name="wm_auth" value="1" disabled <?php echo (isset($detail->wm_auth) && $detail->wm_auth == '1') ? 'checked' : ''; ?> /> 
                                        </td>
                                        <th></th>
                                        <td></td>
                                    </tr>
                                    <?php } ?>
                                    <tr class="text-center">
                                        <th class="text-center">Levy Paying Employers</th>
                                        <th class="text-center"><?php echo in_array(DB_NAME, ["am_ela"]) ? 'Non-Levy' : 'Co-Investment'; ?> Employers</th>
                                        <th class="text-center">Government Contribution</th>
                                        <th class="text-center">Government Contribution - SME</th>
                                        <th class="text-center">Levy Gifted</th>
                                    </tr>
                                    <tr class="text-center">
                                        <td>Maximum Employer Contribution via Levy - 100%</td>
                                        <td>0% or 5% Employer Contribution</td>
                                        <td>95%</td>
                                        <td>100%</td>
                                        <td>100%</td>
                                    </tr>
                                    <tr class="text-center">
                                        <?php
                                        $five_percent_tnp = ceil(($tnp_total*5)/100);
                                        $ninety_five_percent_tnp = ceil(($tnp_total*95)/100);
                                        
                                        if ($employer->funding_type == "LG" && $tr->type_of_funding == "Levy Gifted") 
                                        {
                                            echo '<td><span id="s91td1" style="display: none;">&pound;>&pound;' . $tnp_total . '</span></td>';
                                            echo '<td><span id="s91td2" style="display: none;">&pound;' . $five_percent_tnp . '</span></td>';
                                            echo '<td><span id="s91td3" style="display: none;">&pound;' . $ninety_five_percent_tnp . '</span></td>';
                                            echo '<td><span id="s91td4" style="display: none;">&pound;' . $tnp_total . '</span></td>';
                                            echo '<td><span id="s91td5">&pound;' . $tnp_total . '</span></td>';
                                        } 
                                        elseif ($employer->funding_type == "L") // show first box only
                                        {
                                            echo '<td><span id="s91td1">&pound;' . $tnp_total . '</span></td>';
                                            echo '<td><span id="s91td2" style="display: none;">&pound;' . $five_percent_tnp . '</span></td>';
                                            echo '<td><span id="s91td3" style="display: none;">&pound;' . $ninety_five_percent_tnp . '</span></td>';
                                            echo '<td><span id="s91td4" style="display: none;">&pound;' . $tnp_total . '</span></td>';
                                            echo '<td><span id="s91td5" style="display: none;">&pound;' . $tnp_total . '</span></td>';
                                        } 
                                        else 
                                        {
                                            if (in_array($employer->code, [1, 2, 3, 6]) || $learner_age > 21) // then show 2nd and 3rd box
                                            {
                                                echo '<td><span id="s91td1" style="display: none;">&pound;' . $tnp_total . '</span></td>';
                                                echo '<td><span id="s91td2">&pound;' . $five_percent_tnp . '</span></td>';
                                                echo '<td><span id="s91td3">&pound;' . $ninety_five_percent_tnp . '</span></td>';
                                                echo '<td><span id="s91td4" style="display: none;">&pound;' . $tnp_total . '</span></td>';
                                                echo '<td><span id="s91td5" style="display: none;">&pound;' . $tnp_total . '</span></td>';
                                            } 
                                            else // small employer with less than 50 employees and learner is also < 19 years
                                            {
                                                echo '<td><span id="s91td1" style="display: none;">&pound;' . $tnp_total . '</span></td>';
                                                echo '<td><span id="s91td2" style="display: none;">&pound;' . $five_percent_tnp . '</span></td>';
                                                echo '<td><span id="s91td3" style="display: none;">&pound;' . $ninety_five_percent_tnp . '</span></td>';
                                                echo '<td><span id="s91td4">&pound;' . $tnp_total . '</span></td>';
                                                echo '<td><span id="s91td5" style="display: none;">&pound;' . $tnp_total . '</span></td>';
                                            }
                                        }
                                        ?>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <?php } ?>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <tr><th class="bg-gray">Section 10 - Additional Details (details supporting the negotiated costs / reduced rates)</th></tr>
                                    <tr>
                                        <td>The negotiated price will be confirmed with the Employer after the Skills Analysis has taken place.</td>
                                    </tr>
                                    <tr>
                                        <td><?php echo isset($detail->section11_additional_details) ? $detail->section11_additional_details : ''; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <tr><th class="bg-gray">Section 11 - Additional Payments</th></tr>
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
                                                <input class="clsICheck" name="section12[]" type="checkbox" value="1" <?php echo $learner_age <= 18 ? '' : 'disabled'; ?> />
                                                <label>
                                                    I (the Employer) confirm I am eligible for the &pound;1,000 16-18 Employer Incentive
                                                    for the Apprentice detailed within this schedule.
                                                </label>
                                            </p>
                                            <p>
                                                <input class="clsICheck" name="section12[]" type="checkbox" value="2" <?php echo ($learner_age > 18 && $learner_age <= 24) ? '' : 'disabled'; ?> />
                                                <label>
                                                    I (the Employer) confirm I am eligible for the &pound;1,000 19-24 Education Health Care plan or care leaver
                                                    employer incentive for the Apprentice detailed within this schedule.
                                                    (Relevant evidence will be required at the beginning of the apprenticeship)
                                                </label>
                                            </p>
                                            <?php if($learner_age > 24) {
                                                echo '<input type="checkbox" name="section12[]" value="3" checked style="display: none;" />';
                                                echo '<p><label class="text-info">Not Applicable</label></p>';
                                            }?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <tr><th class="bg-gray">Section 12 - Payment Schedule</th></tr>
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
                                                    Where your 5% Employer Contribution is required, you will be automatically invoiced monthly, or you have the option to settle in full at the start of your apprenticeship programme.
                                                </li>
                                                
                                                <li class="text-bold">
                                                    Invoices are to be paid within 30 days from the date of invoice.
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                </table>
                                <?php if(DB_NAME == "am_crackerjack") {?>
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 50%;">Please select a payment option below:</th>
                                        <td>
                                            <input type="radio" name="payment_structure" value="upfront_payment" <?php echo (isset($detail->payment_structure) && $detail->payment_structure == 'upfront_payment') ? 'checked' : '' ?> /> &nbsp; Upfront Payment <br>
                                            <input type="radio" name="payment_structure" value="monthly_standing_order" <?php echo (isset($detail->payment_structure) && $detail->payment_structure == 'monthly_standing_order') ? 'checked' : '' ?> /> &nbsp; Monthly Standing Order <br>
                                        </td>
                                    </tr>
                                </table>
                                <?php } ?>
                                
                            </div>
                        </div>

                        <?php if(SOURCE_LOCAL || DB_NAME == "am_crackerjack") {?>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <tr><th class="bg-gray">Section 13 - Mandatory Policies</th></tr>
                                    <tr>
                                        <td>
                                            <p>Click to view our policies:</p>
                                            <ul style="margin-left: 5px;">
                                                <li><a href="do.php?_action=downloader&amp;path=policies&amp;f=Health-and-Safety-Policy.pdf"><i class="fa fa-file-pdf-o"></i> Health & Safety</a></li>
                                                <li><a href="do.php?_action=downloader&amp;path=policies&amp;f=Learner-Mental-Health-Policy.pdf"><i class="fa fa-file-pdf-o"></i> Learner Mental Health Policy</a></li>
                                                <li><a href="do.php?_action=downloader&amp;path=policies&amp;f=Prevent-Policy.pdf"><i class="fa fa-file-pdf-o"></i> Prevent Policy</a></li>
                                                <li><a href="do.php?_action=downloader&amp;path=policies&amp;f=Safeguarding-Policy.pdf"><i class="fa fa-file-pdf-o"></i> Safeguarding</a></li>
                                                <li><a href="do.php?_action=downloader&amp;path=policies&amp;f=Change-Procedure.pdf"><i class="fa fa-file-pdf-o"></i> Change Procedure</a></li>
                                                <li><a href="do.php?_action=downloader&amp;path=policies&amp;f=Dispute-Resolution-Procedure.pdf"><i class="fa fa-file-pdf-o"></i> Dispute Resolution Procedure</a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <?php } ?>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table-bordered table-condensed">
                                        <tr><th class="bg-gray" colspan="2">Section <?php echo DB_NAME == "am_crackerjack" ? "14" : "13"; ?> - Employer Declarations (Please tick Option 1 OR Option 2 and all 3 declarations further below.)</th></tr>
                                        <tr>
                                            <td>
                                                <table class="table table-bordered table-condensed">
                                                    <tr>
                                                        <td>
                                                            <input class="" type="radio"  value="1" name="section15radio"
                                                                <?php echo (isset($detail->section15radio) && $detail->section15radio == 1) ? 'checked' : ''; ?>
                                                            /> <span class="text-bold" style="margin-left: 5px;">Option&nbsp;1 </span>
                                                        </td>
                                                        <td>
                                                            I confirm that apprentice(s) named in this <?php echo $document_term; ?> has/have been issued with a contract of
                                                            employment and is/will be employed for at least 30 hours per week. The minimum
                                                            duration of each apprenticeship is based on the apprentice working at least 30 hours a week.
                                                        </td>
                                                    </tr>
                                                    <tr><td colspan="2" align="center"><p class="text-bold">OR</p></td></tr>
                                                    <tr>
                                                        <td>
                                                            <input class="" type="radio"  value="2" name="section15radio"
                                                                <?php echo (isset($detail->section15radio) && $detail->section15radio == 2) ? 'checked' : ''; ?>
                                                            /> <span class="text-bold" style="margin-left: 5px;">Option&nbsp;2 </span>
                                                        </td>
                                                        <td>
                                                            I confirm that apprentice(s) named in this <?php echo $document_term; ?> has/have been issued with a contract of
                                                            employment and is/will be employed for at least 16 hours per week. I am aware that
                                                            the duration of the apprenticeship will be extended accordingly to take account of this.
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table class="table table-bordered table-condensed">
                                                    <tr>
                                                        <th colspan="2">Click the 4 items below to confirm they have been discussed and agreed.</th>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">
                                                            <input name="section15[]" type="checkbox" value="1" <?php echo (isset($detail->section15) && in_array(1, $detail->section15)) ? 'checked' : ''; ?> /> &nbsp; &nbsp;
                                                        </td>
                                                        <td>
                                                            Off-the-job training has been discussed and I am aware of the requirements for this.
                                                            <?php if(!$tr->postJuly25Start()) { ?>
                                                            20% off-the-job training is the equivalent of 1 day per week based on a 5 day working week.
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">
                                                            <input name="section15[]"  type="checkbox" value="2" <?php echo (isset($detail->section15) && in_array(2, $detail->section15)) ? 'checked' : ''; ?> /> &nbsp; &nbsp;
                                                        </td>
                                                        <td>
                                                            The cost of this Apprenticeship has been discussed with us in detail,
                                                            we fully understand the negotiated price for training and associated costs (TNP1) and we have negotiated the EPA price (TNP2).
                                                            I understand that this is an indicative price at this point and is subject to change after the Skills Analysis has taken place.
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">
                                                            <input name="section15[]" type="checkbox"  value="3" <?php echo (isset($detail->section15) && in_array(3, $detail->section15)) ? 'checked' : ''; ?> /> &nbsp; &nbsp;
                                                        </td>
                                                        <td>
                                                            I confirm that all apprentices listed in this schedule will spend at least
                                                            50% of their working hours in England over the duration of the apprenticeship.
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">
                                                            <input name="section15[]" type="checkbox"  value="4" <?php echo (isset($detail->section15) && in_array(4, $detail->section15)) ? 'checked' : ''; ?> /> &nbsp; &nbsp;
                                                        </td>
                                                        <td>
                                                            I confirm as part of our recruitment process we have check the named apprentice(s) right
                                                            to work in the UK and have checked and hold copies of the relevant documentation which will be made
                                                            available to the main provider when requested.
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 table-responsive">
                            <i class="fa fa-info-circle"></i> All costs shown in the <?php echo $document_term; ?> are current however, this is subject to change.
                                <table style="margin-top: 5px;" class="table table-bordered">
                                    <tr><th colspan="4" class="bg-gray">Signatures</th></tr>
                                    <tr><th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th></tr>
                                    <tr>
                                        <td>Employer</td>
                                        <td>
                                            <?php echo '<input type="text" class="form-control compulsory" name="emp_sign_name" id="emp_sign_name" value="' . $schedule->emp_sign_name . '" placeholder="Please enter your name" />'; ?>
					    <span class="small text-info"><i class="fa fa-info-circle"></i> Please enter your name and not the company name</span>
                                        </td>
                                        <td>
                                            <span class="btn btn-info" onclick="getSignature('manager');">
                                                <img id="img_emp_sign" src="do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" />
                                                <input type="hidden" name="emp_sign" id="emp_sign" value="" />
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            $schedule_emp_sign_date = $schedule->emp_sign_date == '' ? date('d/m/Y') : $schedule->emp_sign_date;
                                            echo Date::toShort($schedule_emp_sign_date);
                                            echo '<input type="hidden" name="emp_sign_date" value="' . $schedule_emp_sign_date . '" />';
                                            //echo '<span class="content-max-width">' . HTML::datebox('emp_sign_date', date('d/m/Y')) . '</span>';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Training Provider</td>
                                        <td>
                                            <?php echo $schedule->tp_sign_name; ?>
                                        </td>
                                        <td>
                                            <img src="do.php?_action=generate_image&<?php echo isset($schedule->tp_sign) ? $schedule->tp_sign : 'title=Not yet signed&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />

                                        </td>
                                        <td>
                                            <?php echo Date::toShort($schedule->tp_sign_date); ?>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <span class="btn btn-block btn-success btn-lg" onclick="submitSchedule();">
                                    <i class="fa fa-save"></i> Submit Form
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
    <div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter your name, press 'Generate' and select the signature font you like and press "Create". </div>
    <div class="table-responsive">
        <table class="table row-border">
            <tr>
                <td class="small">Enter your name</td>
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
<script src="js/common_nto.js"></script>

<script type="text/javascript">

    var phpManagerSignature = '';

    var phpProviderSignature = '<?php echo (isset($detail->tp_sign) && $detail->tp_sign != '') ? $detail->tp_sign : ( isset($_SESSION['user']) ? $_SESSION['user']->signature : ''); ?>';

    var fonts = Array("Little_Days.ttf","ArtySignature.ttf","Signerica_Medium.ttf","Champignon_Alt_Swash.ttf","Bailey_MF.ttf","Carolina.ttf","DirtyDarren.ttf","Ruf_In_Den_Wind.ttf");
    var sizes = Array(30,40,15,30,30,30,25,30);

    $(function() {


        $('.clsICheck').each(function(){
            var self = $(this),
                label = self.next(),
                label_text = label.text();

            label.remove();
            self.iCheck({
                checkboxClass: 'icheckbox_line-orange',
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

        $("input[name=wm_auth]").on('change', function(){
            updateS9();
		updateS91();
        });

	<?php if(DB_NAME == "am_crackerjack"){?>
        updateS9();
        updateS91();
        <?php } ?>

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

        var selected_dec = 0;
        $("input[name='section15[]']").each( function () {
            if( this.checked )
            {
                selected_dec++;
            }
        });
        if(selected_dec < 4 || !$("input[name='section15radio']").is(':checked'))
        {
            alert("Section 15 - Employer Declarations.\nPlease tick Option 1 OR Option 2 and all 4 declarations further below.");
            return ;
        }

        if(frmSchedule.emp_sign.value == "")
        {
            alert("Please provide your signature.");
            return;
        }

        frmSchedule.submit();
    }

    function updateS9()
    {
        var wm_auth = $("input[name=wm_auth]").is(":checked");
        $('#s9td1').hide();
        $('#s9td2').hide();
        $('#s9td3').hide();
        $('#s9td4').hide();

        if(wm_auth)
        {
            $('#s9td1').show();
        }
        else
        {
            if(window.s9boxes == '1')
            {
                $('#s9td1').show();
            }
            else if(window.s9boxes == '23')
            {
                $('#s9td2').show();
                $('#s9td3').show();
            }
            else if(window.s9boxes == '4')
            {
                $('#s9td4').show();
            }
        }
    }

    function updateS91()
    {
        var wm_auth = $("input[name=wm_auth]").is(":checked");
        $('#s91td1').hide();
        $('#s91td2').hide();
        $('#s91td3').hide();
        $('#s91td4').hide();

        if(wm_auth)
        {
            $('#s91td1').show();
        }
        else
        {
            if(window.s91boxes == '1')
            {
                $('#s91td1').show();
            }
            else if(window.s91boxes == '23')
            {
                $('#s91td2').show();
                $('#s91td3').show();
            }
            else if(window.s91boxes == '4')
            {
                $('#s91td4').show();
            }
        }
    }

    var s9boxes = '<?php echo $s9boxes; ?>';
    var s91boxes = '<?php echo $s91boxes; ?>';
</script>

</body>
</html>
