<?php 
    /* @var $tr TrainingRecord */ 
    if(in_array(DB_NAME, ["am_ela"]))
    {
        $extra_info = DAO::getObject($link, "SELECT * FROM ob_learner_extra_details WHERE tr_id = '{$tr->id}'");
        if(!isset($extra_info->tr_id))
        {
            $extra_info = new stdClass();
            $ob_learner_extra_details_fields = DAO::getSingleColumn($link, "SHOW COLUMNS FROM ob_learner_extra_details");
            foreach($ob_learner_extra_details_fields AS $extra_info_key => $extra_info_value)
                $extra_info->$extra_info_value = null;

        }
    }
?>
<div class="row">
    <div class="col-sm-12">
        <p class="">Please tell us more about what you did prior to starting this Programme on the <label><?php echo Date::toLong($tr->practical_period_start_date); ?></label>.</p>
        <div class="form-group">
            <label for="EmploymentStatus" class="col-sm-4 control-label fieldLabel_optional">Were you</label>
            <div class="col-sm-8">
                <?php
                $ipe = ''; $nipn = ''; $nipl = ''; $nk = '';
                if($tr->EmploymentStatus == '10') $ipe = 'checked = "checked"';
                if($tr->EmploymentStatus == '11') $nipn = 'checked = "checked"';
                if($tr->EmploymentStatus == '12') $nipl = 'checked = "checked"';
                if($tr->EmploymentStatus == '98') $nk = 'checked = "checked"';
                ?>
                <p><input type="radio" name="EmploymentStatus" <?php echo $ipe; ?>value="10"> In paid employment</p>
                <p><input type="radio" name="EmploymentStatus" <?php echo $nipn; ?> value="11"> Not in paid employment, looking for work and available to start work</p>
                <p><input type="radio" name="EmploymentStatus" <?php echo $nipl; ?> value="12"> Not in paid employment, not looking for work and/or not available to start work</p>
                <p><input type="radio" name="EmploymentStatus" <?php echo $nk; ?> value="98"> Not known / don't want to provide</p>
            </div>
        </div>
        <table id="tbl_emp_status_10" class="table row-border" style="display: none;">
            <?php
            $work_curr_emp_checked = '';
            if($tr->EmploymentStatus == '10' && $tr->work_curr_emp == '1') $work_curr_emp_checked = 'checked = "checked"';
            $SEI_checked = '';
            if($tr->EmploymentStatus == '10' && $tr->SEI == '1') $SEI_checked = 'checked = "checked"';
            $PEI_checked = '';
            if(($tr->EmploymentStatus == '11' || $tr->EmploymentStatus == '12') && $tr->PEI == '1') $PEI_checked = 'checked = "checked"';
            $SEM_checked = '';
            if($tr->EmploymentStatus == '10' && $tr->SEM == '1') $SEM_checked = 'checked = "checked"';
            ?>
            <tr>
                <th>Were you employed with your current employer<br>prior to you starting this Programme?</th>
                <td><input type="checkbox" name="work_curr_emp" id="work_curr_emp" value="1" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo $work_curr_emp_checked; ?> /></td>
            </tr>
            <tr>
                <th>If not, were you self-employed?</th>
                <td><input type="checkbox" name="SEI" id="SEI" data-toggle="toggle" value="1" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo $SEI_checked; ?> /></td>
            </tr>
            <tr>
                <th>Tell us your Employer Name?</th>
                <td><input class="form-control compulsory" type="text" name="empStatusEmployer" id="empStatusEmployer" value="<?php echo $tr->empStatusEmployer; ?>" maxlength="100" /></td>
            </tr>
            <tr>
                <th>Sector of your Employer?</th>
                <td><?php echo HTML::selectChosen('curr_emp_sector', DAO::getResultset($link, "SELECT id, description FROM lookup_sector_types ORDER BY description"), $tr->curr_emp_sector == '' ? $employer->sector : $tr->curr_emp_sector, true); ?></td>
            </tr>
            <tr>
                <th>How long were you employed?</th>
                <td><?php echo HTML::selectChosen('LOE', $LOE_dropdown, $tr->LOE, false); ?></td>
            </tr>
            <tr>
                <th>How many hours did you work each week?</th>
                <td><?php echo HTML::selectChosen('EII', $EII_dropdown, $tr->EII, false); ?></td>
            </tr>
            <?php if(in_array($framework->fund_model, [Framework::FUNDING_STREAM_BOOTCAMP, Framework::FUNDING_STREAM_ASF])) { ?>
            <tr>
                <?php if($framework->fund_model == Framework::FUNDING_STREAM_BOOTCAMP) { ?>
                    <td>Are your earnings below the London Living Wage of &pound;13.85 per hour, or gross salary of less than &pound;27,007.50?</td>
                <?php } else { ?>
                    <td>Are your earnings below the London Living Wage of &pound;13.85 per hour, or gross salary of less than &pound;27,007.50?</td>
                <?php } ?>
                <td><?php echo HTML::selectChosen('earnings_below_llw', [['Yes', 'Yes'], ['No', 'No']], $tr->earnings_below_llw, true); ?></td>
            </tr>
            <?php } ?>
            <?php if($framework->fund_model == Framework::FUNDING_STREAM_BOOTCAMP) { ?>
                <tr>
                    <td>Are you attending this bootcamp via your current employer?</td>
                    <td><?php echo HTML::selectChosen('bootcamp_via_current_emp', [['Yes', 'Yes'], ['No', 'No']], $tr->bootcamp_via_current_emp, true); ?></td>
                </tr>
                <tr>
                    <td>Do you plan to work alongside the bootcamp?</td>
                    <td><?php echo HTML::selectChosen('bootcamp_with_work', [['Yes - Full Time Employment', 'Yes - Full Time Employment'], ['Yes - Part Time Employment', 'Yes - Part Time Employment'], ['Yes - Self Employer', 'Yes - Self Employed'], ['No', 'No']], $tr->bootcamp_with_work, true); ?></td>
                </tr>
            <?php } ?>
        </table>
        <table id="tbl_emp_status_11_12" class="table row-border" style="display: none;">
            <tr>
                <th>How long were you un-employed before <label class="text-blue"><?php echo Date::toLong($tr->apprenticeship_start_date); ?></label>?</th>
                <td><?php echo HTML::selectChosen('LOU', $LOU_dropdown, $tr->LOU, false); ?></td>
            </tr>
            <tr>
                <th>Did you receive any of these benefits?</th>
                <td><?php echo HTML::selectChosen('BSI', $BSI_dropdown, $tr->BSI, false); ?></td>
            </tr>
            <tr>
                <th>If another state benefit, provide details.</th>
                <td><input type="text" class="form-control" name="BSI_other_details" id="BSI_other_details" value="<?php echo $tr->BSI_other_details; ?>" maxlength="50" /></td>
            </tr>
            <tr>
                <th>Were you in Full Time Education or Training prior to <label class="text-blue"><?php echo Date::toLong($tr->practical_period_start_date); ?></label>?</th>
                <td><input type="checkbox" name="PEI" id="PEI" data-toggle="toggle" value="1" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo $PEI_checked; ?> /></td>
            </tr>
        </table>

    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <hr>
    </div>
</div>

<?php if(DB_NAME == "am_ela") { ?>
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-bordered">
                <tr>
                    <td>Your Current Employment Contract</td>
                    <td><input type="text" class="form-control" name="employment_contract" id="employment_contract" value="<?php echo $extra_info->employment_contract; ?>" maxlength="250" /></td>
                </tr>
                <tr>
                    <td>Your Current Employment Start Date</td>
                    <td>
                        <input type="text" name="employment_start_date" id="input_employment_start_date" value="<?php echo Date::toShort($extra_info->employment_start_date); ?>" class="form-control datecontrol">
                    </td>
                </tr>                            
            </table>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12">
            <hr>
        </div>
    </div>
<?php } ?>

<?php if(DB_NAME != "am_superdrug") { ?>
<div class="row" id="row_working_pattern" style="display: none;">
    <div class="col-sm-12">
        <p class="text-bold">Please tell us about your working pattern in current employment.</p>

        <table class="table table-bordered">
            <thead class="bg-gray-light text-bold">
                <tr>
                    <th>Day</th><th>Start</th><th>End</th><th></th>
                </tr>
            </thead>
            <?php
            $saved_shifts = (array)DAO::getObject($link, "SELECT * FROM ob_learner_shift_pattern WHERE tr_id = '{$tr->id}'");
            $week_days = [
                'Mon' => 'Monday',
                'Tue' => 'Tuesday',
                'Wed' => 'Wednesday',
                'Thu' => 'Thursday',
                'Fri' => 'Friday',
                'Sat' => 'Saturday',
                'Sun' => 'Sunday',
            ];

            foreach($week_days AS $key => $value)
            {
                $default_start = isset($saved_shifts["{$key}_start"]) ? $saved_shifts["{$key}_start"] : (!in_array($key, ["Sat", "Sun"]) ? '09:00' : '');
                $default_end = isset($saved_shifts["{$key}_end"]) ? $saved_shifts["{$key}_end"] : (!in_array($key, ["Sat", "Sun"]) ? '17:00' : '');
                
                echo '<tr id="'.$key.'">';
                echo '<td id="start'.$key.'">' . $value . '</td>';
                echo '<td>';
                echo HTML::timebox($key . '_start', $default_start, false);
                echo '</td>';
                echo '<td id="end'.$key.'">';
                echo HTML::timebox($key . '_end', $default_end, false);
                echo '</td>';
                echo '<td id="shift_time_'.$key.'"></td>';
                echo '<input class="shift_time_seconds" type="hidden" name="'.$key.'_shift_time_seconds" value="0" />';
                echo '</tr>';
            }
            echo '<tr><td colspan="3" align="right">Total hours per week</td><td id="shift_time_total"></td></tr>';
            echo isset($saved_shifts['shift_pattern_comments']) ? 
                '<tr><td colspan="2" align="right">Any comments regarding your shift pattern:</td><td colspan="2"><textarea name="shift_pattern_comments" id="shift_pattern_comments" rows="3" style="width: 100%;">'.$saved_shifts['shift_pattern_comments'].'</textarea></td></tr>' :
                '<tr><td colspan="2" align="right">Any comments regarding your shift pattern:</td><td colspan="2"><textarea name="shift_pattern_comments" id="shift_pattern_comments" rows="3" style="width: 100%;"></textarea></td></tr>';
            ?>
        </table>

    </div>
</div>
<?php } ?>

<script>
    calculate_shift_time();
    calculate_hours_per_week();
    $(".timebox").on('change', function(){
        calculate_shift_time();
        calculate_hours_per_week();
    });

    function calculate_shift_time()
    {
        var days = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
        $(days).each(function(index, week_day){
            var shift_start_time = $("#input_"+week_day+"_start").val();
            var shift_end_time = $("#input_"+week_day+"_end").val();
            var shift_diff = Math.abs( new Date("1970-1-1 "+shift_end_time) - new Date("1970-1-1 "+shift_start_time) ) ;
            var shift_seconds = Math.floor(shift_diff/1000);
            var shift_minutes = Math.floor(shift_seconds/60); 
            shift_seconds = shift_seconds % 60;
            var shift_hours = Math.floor(shift_minutes/60);
            shift_minutes = shift_minutes % 60;
            $("#shift_time_"+week_day).html(shift_hours + ' hour(s) and ' + shift_minutes + ' minute(s)');

            var temp = week_day+"_shift_time_seconds";
            $("input[name="+temp+"]").val(Math.floor(shift_diff/1000));
        });
    }

    function calculate_hours_per_week()
    {
        var total_seconds = 0;
        $(".shift_time_seconds").each(function(){
            total_seconds += parseInt(this.value);
        });

        var total_minutes = Math.floor(total_seconds/60); 
        var total_hours = Math.floor(total_minutes/60);
        total_minutes = total_minutes % 60;

        $("#shift_time_total").html(total_hours + ' hour(s) and ' + total_minutes + ' minute(s)');
    }
</script>