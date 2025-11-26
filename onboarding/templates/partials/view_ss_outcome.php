<?php
/* @var $ob_learner OnboardingLearner */
/* @var $tr TrainingRecord */
/* @var $skills_analysis SkillsAnalysis */

$percentage_following_assessment = floatval($ss_stats->percentage_following_assessment)/100;

$view_ss_outcome_sched = $tr->getEmployerAgreementSchedule1($link);
$view_ss_outcome_sched_detail = json_decode($view_ss_outcome_sched->detail);

$learner_age_sql = <<<SQL
SELECT 
    ((DATE_FORMAT('{$tr->practical_period_start_date}','%Y') - DATE_FORMAT('{$ob_learner->dob}','%Y')) - (DATE_FORMAT('{$tr->practical_period_start_date}','00-%m-%d') < DATE_FORMAT('{$ob_learner->dob}','00-%m-%d'))) AS age        
SQL;
$learner_age = DAO::getSingleValue($link, $learner_age_sql);

?>
<div class="row" style="font-size: medium">
    <div class="col-sm-12">
        <span class="lead text-bold">Section B - Self Assessment Outcome</span>
    </div>
    <div class="col-sm-12">
        <div class="callout callout-default">
            A rationale must be clearly described and evidenced in this section for any decisions made by the department where prior learning and skills have been identified by the apprentice. All prior learning that results.
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <tr>
                <th>Apprentice Name</th>
                <td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td>
            </tr>
            <tr>
                <th>Apprenticeship Title</th>
                <td><?php echo $framework->getStandardCodeDesc($link); ?></td>
            </tr>
            <tr>
                <th>Level</th>
                <td><?php echo DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';"); ?></td>
            </tr>
            <tr class="bg-gray-light"><th colspan="2">Employer Schedule 1 figures</th></tr>
            <tr>
                <th>Training Cost</th>
                <td>&pound;<?php echo $view_ss_outcome_sched_detail->training_cost; ?></td>
            </tr>
            <tr>
                <th>Training Materials</th>
                <td><?php echo $view_ss_outcome_sched_detail->training_material != '' ? '&pound;'.$view_ss_outcome_sched_detail->training_material : ''; ?></td>
            </tr>
            <tr>
                <th>Registration & Certification</th>
                <td><?php echo $view_ss_outcome_sched_detail->reg_and_cert != '' ? '&pound;'.$view_ss_outcome_sched_detail->reg_and_cert : ''; ?></td>
            </tr>
            <tr>
                <th>Total Training Price (9.4)</th>
                <td>&pound;<?php echo $view_ss_outcome_sched_detail->total_col_train_cost; ?></td>
            </tr>
            <tr>
                <th>End Point Assessment</th>
                <td>&pound;<?php echo $view_ss_outcome_sched_detail->epa_cost; ?></td>
            </tr>
            <tr>
                <th>Total Negotiated Price (9.6)</th>
                <td>
                    &pound;<?php echo $view_ss_outcome_sched_detail->total_negotiated_price; ?>
                    <?php
                    if($employer->funding_type == "L") // show first box only
                    {
                        echo '<br><span class="text-bold">Maximum Employer Contribution via Levy - 100%: </span>&pound;'.$view_ss_outcome_sched_detail->cost_paid_to_barnsley1;
                    }
                    else
                    {
                        if(in_array($employer->code, [3, 4]) || $learner_age >= 19) // then show 2nd and 3rd box
                        {
                            echo '<br><span class="text-bold">Employer Contribution - 5%: </span>&pound;'.$view_ss_outcome_sched_detail->cost_paid_to_barnsley2;
                            echo '<br><span class="text-bold">Government Contribution - 95%: </span>&pound;'.$view_ss_outcome_sched_detail->cost_paid_to_barnsley3;
                        }
                        else // small employer with less than 50 employees and learner is also < 19 years
                        {
                            echo '<br><span class="text-bold">Government Contribution - SME: </span>&pound;'.$view_ss_outcome_sched_detail->cost_paid_to_barnsley4;
                        }
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th>Original Recommended Duration</th>
                <td>
                    <?php
//                    $view_ss_outcome_duration = Date::dateDiffInfo($view_ss_outcome_sched_detail->proposed_start_date, $view_ss_outcome_sched_detail->proposed_end_date);
//                    if(isset($view_ss_outcome_duration['year']) && isset($view_ss_outcome_duration['month']))
//                        $view_ss_outcome_duration = ($view_ss_outcome_duration['year']*12)+$view_ss_outcome_duration['month'];
                    $view_ss_outcome_duration = $skills_analysis->recommended_duration;
                    echo $view_ss_outcome_duration;
                    ?> months
                </td>
            </tr>
            <tr class="bg-gray-light"><th colspan="2">Following Skills Analysis figures</th></tr>
            <tr>
                <th>Percentage Reduction to be applied</th>
                <td><?php echo $skills_analysis->percentage_fa; ?>%</td>
            </tr>
            <tr>
                <th>Total Training Price</th>
                <td>&pound;<?php echo $skills_analysis->total_training_price; ?></td>
            </tr>
            <tr>
                <th>End Point Assessment Price</th>
                <td>&pound;<?php echo $tr->epa_price; ?></td>
            </tr>
            <tr>
                <th>Total Negotiated Price</th>
                <td>
                    &pound;<?php echo $skills_analysis->total_nego_price_fa; ?>
                    <?php
                    if($employer->funding_type == "L") // show first box only
                    {
                        echo '<br><span class="text-bold">Maximum Employer Contribution via Levy - 100%: </span>&pound;'.$skills_analysis->total_nego_price_fa;
                    }
                    else
                    {
                        if(in_array($employer->code, [3, 4]) || $learner_age >= 19) // then show 2nd and 3rd box
                        {
                            $_emp_cont = ceil($skills_analysis->total_nego_price_fa*0.05);
                            $_govt_cont = ceil($skills_analysis->total_nego_price_fa*0.95);
                            echo '<br><span class="text-bold">Employer Contribution - 5%: </span>&pound;'.$_emp_cont;
                            echo '<br><span class="text-bold">Government Contribution - 95%: </span>&pound;'.$_govt_cont;
                        }
                        else // small employer with less than 50 employees and learner is also < 19 years
                        {
                            echo '<br><span class="text-bold">Government Contribution - SME: </span>&pound;'.$skills_analysis->total_nego_price_fa;
                        }
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th>Minimum Duration <?php echo $tr->contracted_hours_per_week < 30 ? 'Part Time' : ''; ?> (months)</th>
                <td>
                    <?php
                    if ($tr->contracted_hours_per_week >= 30) {
                        echo $skills_analysis->max_duration_fa; // it is actually minimum duration following assessment
                    }
                    else {
                        echo $skills_analysis->minimum_duration_part_time;
                    }
                    ?> months
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <tr>
                <th>Please Provide your Rationale (Duration and Negotiated Price):</th>
            </tr>
            <tr>
                <td>Confirmation that you have accounted for any prior learning, taking into account all elements of the Skills Analysis, and that where it affects the learning or the funding of any of the apprenticeship that you have detailed the adjustments to the content, duration and price accordingly.</td>
            </tr>
            <tr>
                <td>
                    <?php
                    echo $skills_analysis->signed_by_provider == 0 ?
                        '<textarea class="compulsory" name="rationale_by_provider" id="rationale_by_provider" rows="10" style="width: 100%;">' . $skills_analysis->rationale_by_provider . '</textarea>' :
                        '<textarea id="rationale_by_provider" disabled rows="10" style="width: 100%;">' . $skills_analysis->rationale_by_provider . '</textarea>'
                    ;
                    ?>

                </td>
            </tr>
        </table>
    </div>
</div>

<script>
    $(function(){
       $('#max_duration_fa').on('change', function(){
           if(parseInt(this.value) >= 12)
           {
               $("p[id=duration_warning]").hide();
           }
           else
           {
               $("p[id=duration_warning]").show();
           }
       });
    });
</script>