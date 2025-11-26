<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>
<?php /* @var $employer Organisation */ ?>
<?php /* @var $employer_location Location */ ?>
<?php /* @var $skills_analysis SkillsAnalysis */ ?>
<?php
$planned_reviews_start_date = $tr->practical_period_start_date;
$planned_reviews_end_date = $tr->practical_period_end_date;
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Sign Apprenticeship Agreement</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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
        input[type=checkbox] {
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

<div class="content-wrapper" >

    <section class="content-header text-center"><h1><strong>Apprenticeship Agreement</strong></h1></section>

    <section class="content">
        <div class="container-fluid container-table">
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

                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmEmployerSignOnboarding">
                        <input type="hidden" name="_action" value="save_employer_sign_onboarding">
                        <input type="hidden" name="tr_id" value="<?php echo $tr_id; ?>">
                        <input type="hidden" name="key" value="<?php echo $key; ?>">

                        <div class="row small">
                            <div class="col-sm-12">
                                <?php if(DB_NAME != "am_ela") {?>
                                <div class="well well-sm">
                                    <p>Further to the Apprenticeships (Form of Apprenticeship Agreement) Regulations which came into force on 6th April 2012, an Apprenticeship Agreement is required at the commencement of an Apprenticeship for all new apprentices who start on or after that date.</p>
                                    <p>The purpose of the Apprenticeship Agreement is to:</p>
                                    <ul style="margin-left: 25px;">
                                        <li>the skill, trade or occupation for which the apprentice is being trained;</li>
                                        <li>the apprenticeship standard or framework connected to the apprenticeship;</li>
                                        <li>the dates during which the apprenticeship is expected to take place; and</li>
                                        <li>the amount of off the job training that the apprentice is to receive.</li>
                                    </ul>
                                    <p></p>
                                    <p>The Apprenticeship Agreement is incorporated into and does not replace the written statement of particulars issued to the individual in accordance with the requirements of the Employment Rights Act 1996.</p>
                                    <p>The Apprenticeship is to be treated as being a contract of service not a contract of Apprenticeship.</p>
                                </div>
                                <?php } ?>
                                <?php if(DB_NAME == "am_ela") {?>
                                <div class="well well-sm">
                                    <ol>
                                        <li>
                                            <p class="text-bold">The apprenticeship agreement</p>
                                            <p>The apprenticeship agreement is a statutory requirement for the employment of an apprentice in connection with an approved apprenticeship standard. It forms part of the individual employment arrangements between the apprentice and the employer; it is a contract of service (i.e. a contract of employment) and not a contract of apprenticeship. If all the requirements of section 1 of the Employment Rights Act 1996 are complied with, the apprenticeship agreement can also serve as the written statement of particulars of employment. You are not required to use this template, but the requirements of the legislation as described below must be met when you form your apprenticeship agreement.</p>
                                        </li>
                                        <li>
                                            <p class="text-bold">Why an apprenticeship agreement is required</p>
                                            <p>The Apprenticeships, Skills, Children and Learning Act 2009 (ASCLA) introduced the requirement for an apprenticeship agreement to be in place when engaging an apprentice under a statutory apprenticeship. The requirements for an apprenticeship agreement can be found in section A1 of ASCLA and the Apprenticeships (Miscellaneous Provisions) Regulations 2017.</p>
                                        </li>
                                        <li>
                                            <p class="text-bold">When the apprenticeship agreement must be in place</p>
                                            <p>An apprenticeship agreement must be in place when an individual starts a statutory apprenticeship programme and should remain in place throughout the apprenticeship. The end date is when the end-point assessment is due to be completed.</p>
                                        </li>
                                        <li>
                                            <p class="text-bold">The 'practical period'</p>
                                            <p>The practical period is the period for which an apprentice is expected to work and receive training under an approved English apprenticeship agreement. The practical period does not include the end-point assessment. For the purpose of meeting the Education and Skills Funding Agency funding requirements, the practical period start date set out in the apprenticeship agreement must match the practical period start date in the training plan and the start date in the Individual Learner Record.</p>
                                        </li>
                                        <li>
                                            <p class="text-bold">In certain circumstances, an apprenticeship can be completed without an apprenticeship agreement being in place</p>
                                            <p>To commence a statutory apprenticeship (when an individual starts their apprenticeship programme) it is a legal requirement that an apprenticeship agreement be in place. The two circumstances in which an apprentice can complete a statutory apprenticeship without an apprenticeship agreement are where (i) they are holding office as an apprentice police constable, or as an apprentice minister of a religious organisation; or (ii) where they have been made redundant with less than six months of their apprenticeship's practical period left to run (see regulation 6 of the Apprenticeships (Miscellaneous Provisions) Regulations 2017).</p>
                                        </li>
                                        <li>
                                            <p class="text-bold">Who needs to sign the apprenticeship agreement?</p>
                                            <p>The employer and the apprentice need to sign the agreement - it is an agreement between these two parties only. Training providers sign a separate training plan which outlines the planned content and schedule for training, what is expected of and offered by the employer, provider and the apprentice, and how to resolve queries or complaints.</p>
                                        </li>
                                        <li>
                                            <p class="text-bold">What you need to do with the signed agreement</p>
                                            <p>You (the employer) must keep the agreement for the duration of the apprenticeship and give a copy to the apprentice and the training provider.</p>
                                        </li>
                                        <li>
                                            <p class="text-bold">Information needed in an apprenticeship agreement</p>
                                            <p>The apprenticeship agreement must comply with the requirements as provided in ASCLA. It must:</p>
                                            <ul>
                                                <li>provide for the apprentice to work for the employer for reward in an occupation for which a standard has been published by the Institute for Apprenticeships and Technical Education;</li>
                                                <li>provide for the apprentice to receive training in order to assist the apprentice to achieve the standard in the work done under the agreement;</li>
                                                <li>specify the apprenticeship's practical period; and</li>
                                                <li>specify the amount of off-the-job training the apprentice is to receive.</li>
                                            </ul>
                                        </li>
                                        <li>
                                            <p class="text-bold">Specifying the amount of off-the-job training</p>
                                            <p>This is a requirement of the Apprenticeships (Miscellaneous Provisions) Regulations 2017. Off-the-job training is a critical requirement of apprenticeships and, in order to meet the Education and Skills Funding Agency's funding rules, this must be at least 20% of the apprentice's normal working hours over the total duration of the apprenticeship (until gateway). Off-the-job training can only be received by an apprentice during their normal working hours. Maths and English, up to and including level 2, does not count towards the minimum 20% off-the-job training requirement. The amount of off-the-job training should be agreed with the main provider. The provider must account for relevant prior learning the apprentice has received and reduce the content and duration of off-the-job training as necessary to achieve occupational competence. All apprenticeships must be of minimum duration of 12 months and include at least 20% off-the-job training.</p>
                                        </li>
                                        <li>
                                            <p class="text-bold">Off-the-job training definition</p>
                                            <p>Off-the-job training is defined as training which is received by the apprentice, during the apprentice's normal working hours, for the purpose of achieving the standard connected to the apprenticeship. It is not on the job training received by the apprentice for the sole purpose of enabling the apprentice to perform the work to which the apprenticeship agreement relates. More information, including examples of off-the-job training, can be found on gov.uk</p>
                                        </li>
                                        <li>
                                            <p class="text-bold">The apprenticeship agreement does not mean a change to existing contracts or terms and conditions</p>
                                            <p>Any apprenticeship entered into before 15 January 2018 (the date the Apprenticeships (Miscellaneous Provisions) Regulations 2017 came into force) will not be affected by the additional requirements that must be set out in an apprenticeship agreement. Any apprenticeship entered into after 15 January 2018 in connection with an apprenticeship standard must satisfy the requirements of the 2017 Regulations.</p>
                                        </li>
                                    </ol>
                                </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <h4><strong>Apprenticeship Details</strong></h4>
                                <table class="table table-bordered table-condensed">
                                    <tr><th>Apprentice Name:</th><td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td></tr>
                                    <tr>
                                        <th>Employer Name:</th>
                                        <td>
                                            <?php echo in_array(DB_NAME, ["am_superdrug", "am_sd_demo"]) ? $employer->brandDescription($link) : $employer->legal_name; ?><br>
                                            <small>
                                                <?php 
                                                echo $employer_location->address_line_1 != '' ? $employer_location->address_line_1 . '<br>' : ''; 
                                                echo $employer_location->address_line_2 != '' ? $employer_location->address_line_2 . '<br>' : ''; 
                                                echo $employer_location->address_line_3 != '' ? $employer_location->address_line_3 . '<br>' : ''; 
                                                echo $employer_location->address_line_4 != '' ? $employer_location->address_line_4 . '<br>' : '';
                                                echo $employer_location->postcode != '' ? $employer_location->postcode : '';
                                                ?>
                                            </small>
                                        </td>
                                    </tr>
                                    <tr><th>Training Provider Name:</th><td><?php echo $tr->getProviderLegalName($link); ?></td></tr>
                                    <tr><th>Subcontractor Name:</th><td><?php echo !is_null($tr->subcontractor_id) ? $tr->getSubcontractorLegalName($link) : 'NA'; ?></td></tr>
                                    <tr><td colspan="2"></td></tr>
                                    <tr>
                                        <th>Standard Title:</th>
                                        <td><?php echo $framework->getStandardCodeDesc($link); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Level:</th>
                                        <td><?php echo DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';"); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Start Date of Practical Period:</th>
                                        <td><?php echo Date::toShort($tr->practical_period_start_date); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Planned End Date of Practical Period:</th>
                                        <td><?php echo Date::toShort($tr->practical_period_end_date); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Duration of Practical Period - months:</th>
                                        <td><?php echo $tr->duration_practical_period; ?></td>
                                    </tr>
                                    <tr><td colspan="2"></td></tr>
                                    <tr>
                                        <th>Start Date of Apprenticeship:</th>
                                        <td><?php echo Date::toShort($tr->apprenticeship_start_date); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Planned End date of Apprenticeship (incl EPA):</th>
                                        <td><?php echo Date::toShort($tr->apprenticeship_end_date_inc_epa); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Duration of Full Apprenticeship (incl EPA) - months:</th>
                                        <td><?php echo $tr->apprenticeship_duration_inc_epa; ?></td>
                                    </tr>
                                    <tr><td colspan="2"><hr></td></tr>
                                    <tr>
                                        <th><input class="clsICheck" type="checkbox" name="agree_app_agreement" value="1" /><label>CHECK THIS BOX TO AGREE</label></th>
                                        <td class="text-bold"><?php echo date('d/m/Y'); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <h4><strong>Prices following Skills Assessment</strong></h4>
                                <table class="table table-bordered table-condensed">
                                    <?php 
                                    $tnp1_after = json_decode($tr->tnp1);
                                    $tnp1_total = 0;
                                    foreach($tnp1_after AS $price_item)
                                    {
                                        echo '<tr>';
                                        echo '<th>' . $price_item->description . '</th>';
                                        echo '<td>&pound;' . $price_item->cost . '</td>';
                                        echo '</tr>';
                                        $tnp1_total += ($price_item->cost=="") ? 0 : $price_item->cost;
                                    }
                                    ?>
                                    <tr class="text-success">
                                        <th>Total Negotiated Price following Skills Analysis (TNP 1 + TNP 2)</th>
                                        <th>&pound;<?php echo ceil($tnp1_total + $skills_analysis->epa_price_fa); ?></th>
                                    </tr>
                                    <tr class="text-success">
                                        <th>End Point Assessment Price (TNP2)</th>
                                        <th>&pound;<?php echo $tr->epa_price; ?></th>
                                    </tr>
                                    <!-- <tr class="text-success">
                                        <th>Total Negotiated Price (TNP1 + TNP2)</th>
                                        <th>&pound;<?php echo ceil($tnp1_total+$tr->epa_price); ?></th>
                                    </tr> -->
                                    <tr>
                                        <th>Original/Recommended Duration - months</th>
                                        <td><?php echo $framework->getRecommendedDuration($link); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Duration following skills assessment - months</th>
                                        <td><?php echo $tr->duration_practical_period; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Contracted Hours Per Week</th>
                                        <td><?php echo $tr->contracted_hours_per_week; ?> hours</td>
                                    </tr>
                                    <tr>
                                        <th>Weeks to be worked per Year</th>
                                        <td><?php echo $tr->weeks_to_be_worked_per_year; ?> weeks</td>
                                    </tr>
                                    <tr>
                                        <th>Total Contracted Hours - Per Year</th>
                                        <td><?php echo $tr->total_contracted_hours_per_year; ?> hours</td>
                                    </tr>
                                    <?php if($tr->contracted_hours_per_week >= 30) {?>
                                        <tr>
                                            <th colspan="2" class="bg-green-gradient">Full Time Hours (30 or above)</th>
                                        </tr>
                                        <tr>
                                            <th>Length of Programme (Practical Period)</th>
                                            <td><?php echo $tr->duration_practical_period; ?> months</td>
                                        </tr>
                                        <tr>
                                            <th>Total Contracted Hours - Full Apprenticeship</th>
                                            <td><?php echo $tr->total_contracted_hours_full_apprenticeship; ?> hours</td>
                                        </tr>
                                        <tr>
                                            <th>Off-the-job Hours</th>
                                            <td><?php echo $tr->off_the_job_hours_based_on_duration; ?> hours</td>
                                        </tr>
                                    <?php } else {?>
                                        <tr><td colspan="2"></td></tr>
                                        <tr>
                                            <th colspan="2" class="bg-green-gradient">Part Time Hours (less than 30)</th>
                                        </tr>
                                        <tr>
                                            <th>Minimum Duration (part time)</th>
                                            <td><?php echo $tr->minimum_duration_part_time; ?> months</td>
                                        </tr>
                                        <tr>
                                            <th>Total Contracted Hours - Full Apprenticeship</th>
                                            <td><?php echo $tr->part_time_total_contracted_hours_full_apprenticeship; ?> hours</td>
                                        </tr>
                                        <tr class="bg-light-blue-gradient">
                                            <th>Minimum 20% OTJ Training</th>
                                            <td><?php echo $tr->part_time_otj_hours; ?> hours</td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-condensed">
                                        <tr class="bg-gray">
                                            <th>Training to be delivered</th>
                                            <th>Exempt</th>
                                            <th>Level</th>
                                            <th>Details</th>
                                            <th>Start Date</th>
                                            <th>Planned End Date</th>
                                            <th>Number of months</th>
                                        </tr>
                                        <?php
                                        $ob_quals_sql = <<<SQL
SELECT 
    ob_learner_quals.*,
    framework_qualifications.level,
    framework_qualifications.qualification_type,
    TIMESTAMPDIFF(MONTH, qual_start_date, qual_end_date) AS no_of_months,
    framework_qualifications.`main_aim`
FROM
    ob_learner_quals
    LEFT JOIN framework_qualifications ON REPLACE(ob_learner_quals.qual_id, '/', '') = REPLACE(framework_qualifications.id, '/', '') 
WHERE
    ob_learner_quals.tr_id = '{$tr->id}' AND 
    framework_qualifications.framework_id = '{$tr->framework_id}' ORDER BY framework_qualifications.sequence  
SQL;
                                        $ob_quals = DAO::getResultset($link, $ob_quals_sql, DAO::FETCH_ASSOC);
                                        foreach($ob_quals AS $qual)
                                        {
                                            echo '<tr>';
                                            echo '<td>' . $qual['qual_id'] .  ' ' . $qual['qual_title'] . '</td>';
                                            if($qual['qual_exempt'] == 0)
                                            {
                                                echo '<td>No</td>';    
                                            }
                                            elseif($qual['qual_exempt'] == 1)
                                            {
                                                echo '<td>Yes</td>';    
                                            }
                                            elseif($qual['qual_exempt'] == 2)
                                            {
                                                echo '<td>Pending</td>';    
                                            }
                                            else
                                            {
                                                echo '<td></td>';    
                                            }
                                            echo '<td>' . $qual['level'] . '</td>';
                                            echo '<td>' . DAO::getSingleValue($link, "SELECT description FROM lookup_qual_type WHERE id = '{$qual['qualification_type']}'") . '</td>';
                                            echo '<td>' . Date::toShort($qual['qual_start_date']) . '</td>';
                                            echo '<td>' . Date::toShort($qual['qual_end_date']) . '</td>';
                                            echo '<td>' . $qual['no_of_months'] . ' months</td>';
                                            if($qual['main_aim'] == 1)
                                            {
                                                $planned_reviews_start_date = $qual['qual_start_date'];
                                                $planned_reviews_end_date = $qual['qual_end_date'];
                                            }
                                            echo '</tr>';
                                        }
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="text-bold">Planned Reviews - (main provider, employer, apprentice must be present)</h4>
                                <p>The first review should take place at week <?php echo $framework->first_review; ?>, and all other reviews every <?php echo $framework->review_frequency; ?> weeks.</p>
                                <p>Reviews should discuss progress to date against the training plan and the immediate next steps required.</p>

                                <div class="table-responsive" style="font-size: medium;">
                                    <table class="table table-bordered">
                                        <caption class="text-bold">
                                            Planned Reviews<br>
                                            Start Date: <?php echo Date::toShort($tr->practical_period_start_date); ?><br>
                                            End Date: <?php echo Date::toShort($tr->practical_period_end_date); ?>
                                        </caption>
                                        <thead>
                                        <tr>
                                            <td>Review No.</td>
                                            <td>Review Date</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $first_review_days = $framework->first_review != '' ? intval($framework->first_review)*7 : 28;
                                        $subsequent_review_days = $framework->review_frequency != '' ? intval($framework->review_frequency)*7 : 96;
                                        $_review_dates = OnboardingHelper::getReviewsDates($planned_reviews_start_date, $planned_reviews_end_date, $first_review_days, $subsequent_review_days);
                                        foreach($_review_dates AS $_review_number => $_review_date)
                                        {
                                            echo "<tr><td>{$_review_number}</td><td>{$_review_date}</td></tr>";
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <h4><strong>Employer Roles & Reponsibilities</strong></h4>
                                <table class="table table-bordered">
                                    <caption class="bg-gray-light text-bold" style="padding: 5px;">The Employer (Manager of Apprentice) will:</caption>
                                    <?php
                                    $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_roles_responsibilities WHERE user_type = 'EMPLOYER' AND sub_id = 0 ORDER BY id", DAO::FETCH_ASSOC);
                                    $first_loop = true;
                                    $previous_id = '';
                                    foreach($result AS $row)
                                    {
                                        echo '<tr>';
                                        echo $previous_id != $row['id'] ? '<td>' . $row['id'] . '</td>' : '<td></td>';
                                        echo '<td>';
                                        echo DB_NAME == "am_eet" ? str_replace('ELA Training', $provider->legal_name, $row['description']) : $row['description'];
                                        $subs = DAO::getSingleColumn($link, "SELECT description FROM lookup_cs_roles_responsibilities WHERE sub_id = '{$row['id']}' AND user_type = 'EMPLOYER'");
                                        if(count($subs) > 0)
                                            echo '<ul>';
                                        foreach($subs AS $sub)
                                        {
					    $sub = DB_NAME == "am_eet" ? str_replace('ELA Training', $provider->legal_name, $sub) : $sub;
                                            echo '<li style="margin-left: 20px;">' . $sub . '</li>';
                                        }
                                        if(count($subs) > 0)
                                            echo '</ul>';
                                        echo '</td>';
                                        echo '</tr>';
                                        $first_loop = false;
                                        $previous_id = $row['id'];
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>

                        <?php 
                        if(DB_NAME == "am_ela") { ?>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
					<col width="70%" />
                                        <tr>
                                            <th colspan="2" class="bg-gray">Apprenticeship Wages & Employment</th>
                                        </tr>
                                        <tr>
                                            <td>Please confirm that the following agreements have been made for the apprenticeship to be eligible for apprenticeship funding.</td>
                                        </tr>
                                        <tr>
                                            <td>The apprentice is receiving a wage in line with the national minimum wage requirements</td>
                                            <td><?php echo isset($wages_and_employment->opt1) ? HTML::selectChosen('opt1', [['Yes', 'Yes'], ['No', 'No']], $wages_and_employment->opt1, true, true) : HTML::selectChosen('opt1', [['Yes', 'Yes'], ['No', 'No']], '', true, true); ?></td>
                                        </tr>
                                        <tr>
                                            <td>The apprentice rate was not used prior to a valid apprenticeship agreement being in place</td>
                                            <td><?php echo isset($wages_and_employment->opt2) ? HTML::selectChosen('opt2', [['Yes', 'Yes'], ['No', 'No']], $wages_and_employment->opt2, true, true) : HTML::selectChosen('opt2', [['Yes', 'Yes'], ['No', 'No']], '', true, true); ?></td>
                                        </tr>
					<?php if($tr->practical_period_start_date >= '2023-08-01' && (DB_NAME == "am_ela") && !in_array($tr->id, OnboardingHelper::UlnsToSkip($link)) ) { ?>
                                        <tr>
                                            <td>The apprentice is included in the PAYE Scheme declared in the Apprenticeship Service account (Y/N)</td>
                                            <td><?php echo isset($wages_and_employment->opt3) ? HTML::selectChosen('opt3', [['Yes', 'Yes'], ['No', 'No']], $wages_and_employment->opt3, true, true) : HTML::selectChosen('opt3', [['Yes', 'Yes'], ['No', 'No']], '', true, true); ?></td>
                                        </tr>
                                        <tr>
                                            <td>The apprentice will be provided with the time required to undertake all off the job training requirements (20% or 6 hrs/wk) 
                                                within their normal hours of work in addition to any English and Maths requirements that they might have (Y/N)</td>
                                            <td><?php echo isset($wages_and_employment->opt4) ? HTML::selectChosen('opt4', [['Yes', 'Yes'], ['No', 'No']], $wages_and_employment->opt4, true, true) : HTML::selectChosen('opt4', [['Yes', 'Yes'], ['No', 'No']], '', true, true); ?></td>
                                        </tr>
                                        <?php } else { ?>
                                        <tr>
                                            <td>The apprentice is receiving a wage in line with the national minimum wage requirements</td>
                                            <td><?php echo isset($wages_and_employment->opt3) ? HTML::selectChosen('opt3', [['Yes', 'Yes'], ['No', 'No']], $wages_and_employment->opt3, true, true) : HTML::selectChosen('opt3', [['Yes', 'Yes'], ['No', 'No']], '', true, true); ?></td>
                                        </tr>
                                        <tr>
                                            <td>The apprentice is receiving a wage in line with the national minimum wage requirements</td>
                                            <td><?php echo isset($wages_and_employment->opt4) ? HTML::selectChosen('opt4', [['Yes', 'Yes'], ['No', 'No']], $wages_and_employment->opt4, true, true) : HTML::selectChosen('opt4', [['Yes', 'Yes'], ['No', 'No']], '', true, true); ?></td>
                                        </tr>
					<?php } ?>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col style="width: 8%" />
                                    <caption class="bg-light-blue text-bold" style="padding: 5px;">Declarations:</caption>
                                    <?php
                                    if($tr->practical_period_start_date >= '2023-08-01' && (DB_NAME == "am_ela") && !in_array($tr->id, OnboardingHelper::UlnsToSkip($link)) )
                                    {
                                        $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_declarations WHERE user_type = 'EMPLOYER' AND year = '2023' and version = 1 ORDER BY id", DAO::FETCH_ASSOC);
                                    }
                                    else
                                    {
                                        $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_declarations WHERE user_type = 'EMPLOYER' AND year = '2022' and version = 1 ORDER BY id", DAO::FETCH_ASSOC);
                                    }
                                    $saved_emp_dec = $tr->emp_dec != '' ? explode(",", $tr->emp_dec) : [];
                                    foreach($result AS $row)
                                    {
                                        echo '<tr>';
                                        if(in_array($row['id'], $saved_emp_dec))
                                            echo '<td align="right"><input type="checkbox" name="emp_dec[]" checked value="' . $row['id'] . '" /></td>';
                                        else
                                            echo '<td align="right"><input type="checkbox" name="emp_dec[]" value="' . $row['id'] . '" /></td>';
                                        $declrationDescription = $row['description'];
                                        if(in_array(DB_NAME, ["am_superdrug", "am_sd_demo"]))
                                        {
                                            $declrationDescription = str_replace('SD_HOURS_PER_WEEK', $tr->contracted_hours_per_week, $declrationDescription);
                                            $declrationDescription = str_replace('SD_OTJ_HOURS', $tr->off_the_job_hours_based_on_duration, $declrationDescription);
                                        }
                                        if(in_array(DB_NAME, ["am_eet"]))
                                        {
                                            $declrationDescription = str_replace('ELA Training', $provider->legal_name, $declrationDescription);
                                        }
                                        echo '<td>' . $declrationDescription . '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 table-responsive">
                                <table style="margin-top: 5px;" class="table table-bordered table-condensed">
                                    <caption class="bg-gray-light text-bold" style="padding: 5px;">Signatures:</caption>
                                    <tr><th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th></tr>
                                    <tr>
                                        <td>Learner</td>
                                        <td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td>
                                        <td><img id="img_tp_sign" src="do.php?_action=generate_image&<?php echo $tr->learner_sign ?>" style="border: 2px solid;border-radius: 15px;" /></td>
                                        <td><?php echo Date::toShort($tr->learner_sign_date); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Employer</td>
                                        <td>
                                            <?php echo '<input type="text" class="form-control compulsory" name="emp_sign_name" id="emp_sign_name" value="" placeholder="Please enter your name" />'; ?>
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
                                            $ob_emp_sign_date = $tr->emp_sign_date == '' ? date('d/m/Y') : $tr->emp_sign_date;
                                            echo Date::toShort($ob_emp_sign_date);
                                            echo '<input type="hidden" name="emp_sign_date" value="' . $ob_emp_sign_date . '" />';
                                            //echo '<span class="content-max-width">' . HTML::datebox('emp_sign_date', $agreement_emp_sign_date) . '</span>'; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <span class="btn btn-block btn-success btn-lg" onclick="submitOnboarding();">
                                    <i class="fa fa-save"></i> Submit Information
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
        <img class="img-responsive" src="<?php echo $logo;?>" />
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

    var fonts = Array("Little_Days.ttf","ArtySignature.ttf","Signerica_Medium.ttf","Champignon_Alt_Swash.ttf","Bailey_MF.ttf","Carolina.ttf","DirtyDarren.ttf","Ruf_In_Den_Wind.ttf");
    var sizes = Array(30,40,15,30,30,30,25,30);

    $(function() {

        $("input[type=checkbox]:checked").each(function() {
            $(this).closest('tr').addClass('bg-green');
        });

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

        //$('input[class=radioICheck]').iCheck({radioClass: 'iradio_square-green', increaseArea: '20%'});

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
        $('#signature_text').val($('#emp_sign_name').val());
        $( "#panel_signature" ).data('panel', 'manager').dialog( "open");
        return;
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

    function submitOnboarding()
    {
        var frmEmployerSignOnboarding = document.forms['frmEmployerSignOnboarding'];
        if(!$("input[name=agree_app_agreement]").prop('checked'))
        {
            alert('Please agree to continue.');
            $("input[name=agree_app_agreement]").focus();
            return false;
        }

	<?php if(DB_NAME == "am_ela") { ?>
        if($("#opt1").val() == '' || $("#opt2").val() == '' || $("#opt3").val() == '' || $("#opt4").val() == '')
        {
            $("#opt1").focus();
            alert("Please confirm Apprenticeship Wages & Employment options");
            return false;
        }
        <?php } ?>

        var selected_dec = 0;
        $("input[name='emp_dec[]']").each( function () {
            if( this.checked )
            {
                selected_dec++;
            }
        });
        if(selected_dec < $("input[name='emp_dec[]']").length)
        {
            alert("Please tick the complete declaration list.");
            return ;
        }

        var emp_sign = frmEmployerSignOnboarding.elements["emp_sign"];
        if(emp_sign.value.trim() == '')
        {
            alert('Please provide your signature.');
            return;
        }

        // var client = ajaxPostForm(frmEmployerSignOnboarding);
        // if(client != null)
        // {
        //     var username = parseInt(client.responseText);
        //     window.location.href('do.php?_action=read_user&username=' + username);
        //
        //     // Exit the function without releasing the save lock
        //     return;
        // }
        //
        frmEmployerSignOnboarding.submit();
    }

</script>

</body>
</html>
