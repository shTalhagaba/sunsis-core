<?php /* @var $ob_learner User */ ?>
<?php
$employer = Employer::loadFromDatabase($link, $ob_learner->employer_id);
$employer_location = Location::loadFromDatabase($link, $ob_learner->employer_location_id);

?>

<div class="row">
    <div class="col-sm-4">
        <span class="text-bold">Employer & Workplace Address: </span><br><?php echo $employer->legal_name; ?><br>
        <?php
        echo $employer_location->full_name != '' ? $employer_location->full_name . '<br>' : '';
        echo $employer_location->address_line_1 != '' ? $employer_location->address_line_1 . '' : '';
        echo $employer_location->address_line_2 != '' ? ', ' . $employer_location->address_line_2 . '' : '';
        echo $employer_location->address_line_3 != '' ? ' ' . $employer_location->address_line_3 . '' : '';
        echo $employer_location->address_line_4 != '' ? ' ' . $employer_location->address_line_4 . '<br>' : '';
        echo $employer_location->postcode != '' ? $employer_location->postcode . '<br>' : '';
        ?>
    </div>
    <div class="col-sm-8">
        <?php
        echo $ob_learner->job_title != '' ? '<span class="text-bold">Job Title: </span>' . $ob_learner->job_title . '<br>' : '';
        ?>
        <?php
        echo $ob_learner->emp_q4 == 'Y' ? ' You are an employee of this company<br>' : ' You are not an employee of this company<br>';
        if($ob_learner->emp_q5 == 'Y')
        {
            if($ob_learner->emp_q9 == 'P')
            {
                echo '<span class="text-bold">Contract Type: </span>Permanent<br>';
                echo '<span class="text-bold">Contracted hours per week: </span>' . $ob_learner->emp_q7 . ' hours<br>';
                echo '<span class="text-bold">Contracted days per week: </span>' . $ob_learner->emp_q8 . ' days<br>';
            }
            if($ob_learner->emp_q9 == 'FT')
            {
                echo '<span class="text-bold">Contract Type: </span>Fixed Term<br>';
                echo '<span class="text-bold">Contract end/expiry date: </span>' . Date::toShort($ob_learner->contract_end_date) . ' hours<br>';
            }
            if($ob_learner->emp_q9 == 'ZH')
            {
                echo '<span class="text-bold">Contract Type: </span>Zero Hours<br>';
                echo '<span class="text-bold">Average weekly hours: </span>' . $ob_learner->avg_weekly_hours . ' hours<br>';
            }
        }
        else
        {
            echo $ob_learner->emp_q6 == 'Y' ? '<br>You a Contractor or Agency Staff' : '';
        }
        if($ob_learner->emp_q11 == 'Y')
        {
            echo '<br>Your job role cause you to spend any of your contracted hours working outside of England or planning to work outside of England during your apprenticeship';
        }
        if($ob_learner->emp_q11 == 'Y')
        {
            echo $ob_learner->emp_q14 == 'Y' ?
                '<br>You will spend more than 50% of your normal working time during your apprenticeship working outside of England' :
                '<br>You will not spend more than 50% of your normal working time during your apprenticeship working outside of England';
        }
        echo $ob_learner->emp_q13 == 'Y' ?
            '<br>You are being paid at least the minimum wage which is relevant for your age' :
            '<br>You are not being paid at least the minimum wage which is relevant for your age';
        ?>
    </div>
</div>

<div class="col-sm-12">
    <table class="table table-bordered">
        <tr>
            <th colspan="2" class="bg-gray-light">Employment Profile</th>
        </tr>
        <tr>
            <th>
                What is your overall length of employment with this employer?
            </th>
            <td>
                <input type="text" name="overall_employment_years" value="<?php echo $ob_learner->overall_employment_years; ?>" onkeypress="return numbersonly(this);" maxlength="2" /> years
                <input type="text" name="overall_employment_months" value="<?php echo $ob_learner->overall_employment_months; ?>" onkeypress="return numbersonly(this);" maxlength="2" /> months
            </td>
        </tr>
        <tr>
            <th>
                Is this a new job or an existing job role with your employer?
            </th>
            <td>
                <?php echo HTML::selectChosen('new_or_existing', [["N", "New"], ["E", "Existing"]], $ob_learner->new_or_existing, true, true); ?>
            </td>
        </tr>
        <tr>
            <th>
                How long have you been in your <b>current</b> job role?
            </th>
            <td>
                <input class="" type="text" name="current_job_years" value="<?php echo $ob_learner->current_job_years; ?>" onkeypress="return numbersonly(this);" maxlength="2" /> years
                <input class="" type="text" name="current_job_months" value="<?php echo $ob_learner->current_job_months; ?>" onkeypress="return numbersonly(this);" maxlength="2" /> months
            </td>
        </tr>
        <tr>
            <th style="width: 60%;">
                Give a brief summary of your current duties and responsibilities.
            </th>
            <td>
                <textarea maxlength="499" name="summary_current_job" id="summary_current_job" rows="4" style="width: 100%;"><?php echo $ob_learner->summary_current_job; ?></textarea>
            </td>
        </tr>
        <tr>
            <th style="width: 60%;">
                If you are working less than 30 hours per week then your program duration will need to be extended.
                Explain why you work less than 30 hours per week and how you will meet the required training sessions
            </th>
            <td>
                <textarea maxlength="499" name="why_less_hours" id="why_less_hours" rows="4" style="width: 100%;"><?php echo $ob_learner->why_less_hours; ?></textarea>
            </td>
        </tr>
    </table>
    <p><br></p>
</div>



<script type="text/javascript">
	$(function(){

		$("input[type=radio][name=emp_q9]").on('ifChanged', function(){
			if( $(this).is(":checked") ){
				$('#row_emp_q7').addClass('disabledRow');
				$('#row_emp_q8').addClass('disabledRow');
				$('#row_contract_end_date').addClass('disabledRow');
				$('#row_avg_weekly_hours').addClass('disabledRow');
				$("input[name=emp_q7]").val('');
				$("input[name=emp_q8]").val('');
				$("input[name=contract_end_date]").val('');
				$("input[name=avg_weekly_hours]").val('');
				if(this.value == 'P')
				{
					$('#row_emp_q7').removeClass('disabledRow');
					$('#row_emp_q8').removeClass('disabledRow');
				}
				if(this.value == 'FT')
				{
					$('#row_contract_end_date').removeClass('disabledRow');
				}
				if(this.value == 'ZH')
				{
					$('#row_avg_weekly_hours').removeClass('disabledRow');
				}
			}
		});

	});

	function showMinimumWageInfo()
	{
		$("<div></div>").html('<img width="440px" height="350px" src="/images/s300_2019_rates_pic_WEB.jpg" alt="">').dialog({
			id: "dlg_lrs_result",
			title: "National Living and National Minimum Wage Figures ",
			resizable: false,
			modal: true,
			width: 500,
			height: 500,

			buttons: {
				'Close': function() {$(this).dialog('close');}
			}
		});
	}
</script>