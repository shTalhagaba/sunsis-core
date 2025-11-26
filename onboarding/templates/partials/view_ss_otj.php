<?php
/* @var $ob_learner OnboardingLearner */
/* @var $tr TrainingRecord */
/* @var $skills_analysis SkillsAnalysis */

$percentage_following_assessment = floatval($ss_stats->percentage_following_assessment)/100;
?>
<div class="row" style="font-size: medium">
    <div class="col-sm-12">
        <p>Please use the relevant section based on the contracted hours per week.</p>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
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
            <tr><td colspan="2"></td></tr>
            <tr>
                <th colspan="2" class="bg-green-gradient">Full Time Hours (30 or above)</th>
            </tr>
            <tr>
                <th>Length of Programme (Practical Period)</th>
                <td><?php echo $tr->length_of_programme_practical_period; ?> months</td>
            </tr>
            <tr>
                <th>Total Contracted Hours - Full Apprenticeship</th>
                <td><?php echo $tr->total_contracted_hours_full_apprenticeship; ?> hours</td>
            </tr>
            <tr class="bg-light-blue-gradient">
                <th>Minimum 20% OTJ Training</th>
                <td><?php echo $tr->minimum_percentage_otj_training; ?> hours</td>
            </tr>
            <?php } else {?>
            <tr><td colspan="2"></td></tr>
            <tr>
                <th colspan="2" class="bg-green-gradient">Part Time Hours (less than 30)</th>
            </tr>
            <tr>
                <th>Minimum Duration (part time)</th>
                <td><?php echo $skills_analysis->minimum_duration_part_time; ?> months</td>
            </tr>
            <tr>
                <th>Total Contracted Hours - Full Apprenticeship</th>
                <td><?php echo $skills_analysis->part_time_total_contracted_hours_full_apprenticeship; ?> hours</td>
            </tr>
            <tr class="bg-light-blue-gradient">
                <th>Minimum 20% OTJ Training</th>
                <td><?php echo $skills_analysis->part_time_otj_hours; ?> hours</td>
            </tr>
            <?php } ?>
            <tr><td colspan="2"></td></tr>
            <tr><td colspan="2"></td></tr>
            <tr class="bg-green-gradient">
                <th><?php echo (DB_NAME == "am_onboarding") ? 'Planned Delivery Hours following Skills Analysis' : 'Planned Delivery Hours (OTJ) following Skills Analysis'; ?></th>
                <td><?php echo $skills_analysis->delivery_plan_hours_fa; ?></td>
            </tr>
        </table>
    </div>
</div>
