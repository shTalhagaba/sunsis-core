
<div class="row">
    <div class="col-sm-12">
        <p class="text-bold">On the day prior to this course, what is your employment status? *</p>
        
        <div>
            <?php
            $ipe = ''; $nipn = ''; $nipl = ''; $nk = '';
            if($registration->employment_status == '10') $ipe = 'checked = "checked"';
            if($registration->employment_status == '11') $nipn = 'checked = "checked"';
            if($registration->employment_status == '12') $nipl = 'checked = "checked"';
            if($registration->employment_status == '98') $nk = 'checked = "checked"';
            ?>
            
            <div class="radio">
                <label>
                    <input type="radio" name="employment_status" <?php echo $ipe; ?>value="10"> In paid employment
                </label>
            </div>
            <div class="radio">
                <label><input type="radio" name="employment_status" <?php echo $nipn; ?> value="11"> Not in paid employment, looking for work and available to start work</label>
            </div>
            <div class="radio">
                <label><input type="radio" name="employment_status" <?php echo $nipl; ?> value="12"> Not in paid employment, not looking for work and/or not available to start work</label>
            </div>
            <div class="radio">
                <label><input type="radio" name="employment_status" <?php echo $nk; ?> value="98"> Not known / don't want to provide</label>
            </div>
        </div>
        <p><br></p>
        <table id="tbl_emp_status_10" class="table row-border" style="display: none;">
            <?php
            $work_curr_emp_checked = '';
            if($registration->employment_status == '10' && $registration->work_curr_emp == '1') $work_curr_emp_checked = 'checked = "checked"';
            $SEI_checked = '';
            if($registration->employment_status == '10' && $registration->SEI == '1') $SEI_checked = 'checked = "checked"';
            $PEI_checked = '';
            if(($registration->employment_status == '11' || $registration->employment_status == '12') && $registration->PEI == '1') $PEI_checked = 'checked = "checked"';
            $SEM_checked = '';
            if($registration->employment_status == '10' && $registration->SEM == '1') $SEM_checked = 'checked = "checked"';
            ?>
            <tr>
                <th>Were you self-employed:</th>
                <td><?php echo HTML::selectChosen('SEI', $YesNoList, $registration->SEI, true); ?></td>
            </tr>
            <tr>
                <th>Employer Name:</th>
                <td>
                    <input class="form-control compulsory" type="text" name="emp_status_employer" id="emp_status_employer" 
                        value="<?php echo trim($registration->emp_status_employer) != '' ? $registration->emp_status_employer : (isset($learner->org->legal_name) ? $learner->org->legal_name : ''); ?>" maxlength="100" />
                </td>
            </tr>
            <tr>
                <th>Employer Phone Number:</th>
                <td><input class="form-control compulsory" type="text" name="emp_status_employer_tel" id="emp_status_employer_tel" value="<?php echo $registration->emp_status_employer_tel; ?>" maxlength="50" /></td>
            </tr>
            <tr>
                <th>Employer Contact Name:</th>
                <td><input class="form-control compulsory" type="text" name="employer_contact_name" id="employer_contact_name" value="<?php echo $registration->employer_contact_name; ?>" maxlength="70" /></td>
            </tr>
            <tr>
                <th>Employer Contact Email:</th>
                <td><input class="form-control compulsory" type="email" name="employer_contact_email" id="employer_contact_email" value="<?php echo $registration->employer_contact_email; ?>" maxlength="100" /></td>
            </tr>
            <tr>
                <th>Employer Postcode:</th>
                <td><input type="text" class="form-control compulsory" name="workplace_postcode" id="workplace_postcode" value="<?php echo $registration->workplace_postcode; ?>" maxlength="10" /></td>
            </tr>
            <tr>
                <th>Your current Job Title:</th>
                <td><input class="form-control compulsory" type="text" name="current_job_title" id="current_job_title" value="<?php echo $registration->current_job_title; ?>" maxlength="70" /></td>
            </tr>
            <tr>
                <th>Industry/Sector of your current occupation:</th>
                <td><input class="form-control compulsory" type="text" name="current_occupation" id="current_occupation" value="<?php echo $registration->current_occupation; ?>" maxlength="70" /></td>
            </tr>
            <tr>
                <th>How long were you employed:</th>
                <td><?php echo HTML::selectChosen('LOE', $LOE_dropdown, $registration->LOE, false); ?></td>
            </tr>
            <tr>
                <th>How many hours did you work each week:</th>
                <td><?php echo HTML::selectChosen('EII', $EII_dropdown, $registration->EII, false); ?></td>
            </tr>
            <tr>
                <th>Current Salary:<br><span class="text-info">(please specify if hourly rate, weekly, monthly or yearly)</span></th>
                <td><input class="form-control compulsory" type="text" name="current_salary" id="current_salary" value="<?php echo $registration->current_salary; ?>" maxlength="70" /></td>
            </tr>
            <tr>
                <th>Are you attending this bootcamp via your current employer:</th>
                <td><?php echo HTML::selectChosen('via_current_employer', $viaCurrentEmployerList, $registration->via_current_employer, true); ?></td>
            </tr>
        </table>
        <table id="tbl_emp_status_11_12" class="table row-border" style="display: none;">
            <tr>
                <th>How long were you un-employed before start of this course:</label>?</th>
                <td><?php echo HTML::selectChosen('LOU', $LOU_dropdown, $registration->LOU, false); ?></td>
            </tr>
            <tr>
                <th>Did you receive any of these benefits:</th>
                <td><?php echo HTML::selectChosen('BSI', $BSI_dropdown, $registration->BSI, false); ?></td>
            </tr>
            <tr>
                <th>Were you in Full Time Education or Training prior to start of this course:</label>?</th>
                <td><?php echo HTML::selectChosen('PEI', $YesNoList, $registration->PEI, true); ?></td>
            </tr>
        </table>
        <table class="table row-border">
            <tr>
                <th>Do you plan to work alongside the bootcamp</label>? *</th>
                <td><?php echo HTML::selectChosen('plan_to_work_alongside', $planToWorkAlongsideList, $registration->plan_to_work_alongside, true); ?></td>
            </tr>
        </table>


    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <hr>
    </div>
</div>



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