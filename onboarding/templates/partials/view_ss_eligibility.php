<?php
/* @var $ob_learner OnboardingLearner */
/* @var $tr TrainingRecord */
/* @var $skills_analysis SkillsAnalysis */

?>
<div class="row" style="font-size: medium">
    <div class="col-sm-12">
        <p>Following the Skills Analysis Assessment, I confirm that:</p>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <?php if($skills_analysis->provider_sign != "") { ?>
                <tr>
                    <td align="right">
                        <input type="radio" checked name="is_eligible_after_ss" value="<?php echo $skills_analysis->is_eligible_after_ss; ?>" style="display: none;" />
                        <?php echo $skills_analysis->is_eligible_after_ss == "Y" ? "<i class='fa fa-check text-green fa-2x'></i>" : ""; ?>
                    </td>
                    <th class="text-green">Learner is eligible</th>
                </tr>
                <tr>
                    <td align="right">
                        <?php echo $skills_analysis->is_eligible_after_ss == "N" ? "<i class='fa fa-check text-red fa-2x'></i>" : ""; ?>
                    </td>
                    <th class="text-red">
                        Learner is NOT eligible
                        <?php if($skills_analysis->is_eligible_after_ss == "N") {?>
                        <table class="table table-bordered">
                            <tr><th>Reason for ineligibility</th></tr>
                            <tr><td><?php echo $skills_analysis->ineligibility_reason; ?></td></tr>
                        </table>
                        <?php } ?>
                    </th>
                </tr>
            <?php } else { $_max_duration_fa = $skills_analysis->max_duration_fa; ?>
            <tr>
                <td align="right"><input type="radio" name="is_eligible_after_ss" value="Y"
                    <?php echo $skills_analysis->is_eligible_after_ss == "Y" ? "checked" : ""; ?>
                    <?php echo $_max_duration_fa >= 12 ? "checked" : ""; ?>
                        <?php //echo $_max_duration_fa < 12 ? "style='pointer-events:none;'" : ""; ?>
                    ></td>
                <th class="text-green">Learner is eligible</th>
            </tr>
            <tr>
                <td align="right"><input type="radio" name="is_eligible_after_ss" value="N"
                    <?php echo $skills_analysis->is_eligible_after_ss == "N" ? "checked" : ""; ?>
                        <?php echo $_max_duration_fa < 12 ? "checked" : ""; ?>

                    ></td>
                <th class="text-red">
                    Learner is NOT eligible
                    <table class="table table-bordered">
                        <tr>
                            <th>Provide reason for ineligibility</th>
                        </tr>
                        <tr>
                            <td>
                                <textarea name="ineligibility_reason" id="ineligibility_reason" style="width: 100%;" rows="5" maxlength="500"></textarea>
                            </td>
                        </tr>
                    </table>

                </th>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>
