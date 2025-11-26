<?php /* @var $ob_learner User */ ?>
<?php
$employer = Employer::loadFromDatabase($link, $ob_learner->employer_id);
$location = Location::loadFromDatabase($link, $ob_learner->employer_location_id);

?>
<div class="col-sm-12">
	<table class="table table-bordered">
		<tr>
			<th style="width: 40%;">1. Employer Name</th>
			<td><?php echo $employer->legal_name;  ?></td>
		</tr>
		<tr>
			<th>2. Your Workplace Address</th>
			<td>
				<?php
				echo $location->full_name != '' ? $location->full_name . '<br>' : '';
				echo $location->address_line_1 != '' ? $location->address_line_1 . '<br>' : '';
				echo $location->address_line_2 != '' ? $location->address_line_2 . '<br>' : '';
				echo $location->address_line_3 != '' ? $location->address_line_3 . '<br>' : '';
				echo $location->address_line_4 != '' ? $location->address_line_4 . '<br>' : '';
				echo $location->postcode != '' ? $location->postcode . '<br>' : '';
				?>
			</td>
		</tr>
		<tr>
			<th class="text-blue">3. Job Title</th>
			<td><input type="text" class="form-control" name="job_title" value="<?php echo $ob_learner->job_title; ?>" /></td>
		</tr>
	</table>
	<table class="table table-bordered">
		<col width="60%;"/>
		<tr>
			<th class="text-blue">
				4. Are you an employee of the company named above?
			</th>
			<td>
                <?php echo HTML::selectChosen('emp_q4', OnboardingHelper::getYesNoDdlYN(), $ob_learner->emp_q4, true, true); ?>
			</td>
		</tr>
		<tr id="row_Q5">
			<th class="text-blue">
				5. Do you have a Contract of Employment with the above Employer for the above job?
			</th>
			<td>
                <?php echo HTML::selectChosen('emp_q5', OnboardingHelper::getYesNoDdlYN(), $ob_learner->emp_q5, true, true); ?>
			</td>
		</tr>
        <tr>
            <th class="text-blue">
                6. If you answered No to Q5, are you a Contractor or Agency Staff?
            </th>
            <td>
                <?php echo HTML::selectChosen('emp_q6', OnboardingHelper::getYesNoDdlYN(), $ob_learner->emp_q6, true, true); ?>
            </td>
        </tr>
		<tr>
			<th class="text-blue">
				7. What type of contract do you have?
			</th>
			<td>
                <?php echo HTML::selectChosen('emp_q9', [["P", "Permanent"], ["FT", "Fixed Term"], ["ZH", "Zero Hours"]], $ob_learner->emp_q9, true, true); ?>
			</td>
		</tr>
		<tr id="row_emp_q7" class="disabledRow">
			<th class="text-blue">
				8. How many hours are you contracted to work per week?<br><i>(Excludes overtime and additional hours worked outside of your contracted hours)</i>
			</th>
			<td>
				<input class="form-control" type="text" name="emp_q7" id="emp_q7" value="<?php echo $ob_learner->emp_q7; ?>" maxlength="4" onkeypress="return numbersonlywithpoint(this);" /> hours
			</td>
		</tr>
		<tr id="row_emp_q8" class="disabledRow">
			<th class="text-blue">
				9. How many days a week are you contracted to work?
			</th>
			<td>
                <?php
                $_daysDdl = [];
                for($i = 1; $i <= 7; $i++)
                    $_daysDdl[] = [$i, $i];
                echo HTML::selectChosen('emp_q8', $_daysDdl, $ob_learner->emp_q8, true);
                ?>
			</td>
		</tr>
		<tr id="row_contract_end_date" class="disabledRow">
			<th class="text-blue">
				10. Please provide the contract end/expiry date.
			</th>
			<td>
				<input type="text" class="form-control datecontrol" name="contract_end_date" value="<?php echo Date::toShort($ob_learner->contract_end_date); ?>">
			</td>
		</tr>
		<tr id="row_avg_weekly_hours" class="disabledRow">
			<th class="text-blue">
				11. Please provide average weekly hours total.
			</th>
			<td>
				<input type="text" class="form-control" name="avg_weekly_hours" value="<?php echo $ob_learner->avg_weekly_hours; ?>" maxlength="4" size="5" onkeypress="return numbersonlywithpoint(this);" /> hours
			</td>
		</tr>
		<tr>
			<th class="text-blue">
				12. Does the nature of your job role cause you to spend any of your contracted hours working outside of England or are you planning to work outside of England during your apprenticeship?
			</th>
			<td>
                <?php echo HTML::selectChosen('emp_q11', OnboardingHelper::getYesNoDdlYN(), $ob_learner->emp_q11, true, true); ?>
			</td>
		</tr>
		<tr id="row_Q12" class="disabledRow">
			<th class="text-blue">
				13. If you have answered Yes to Q12, will you spend more than 50% of your normal working time during your apprenticeship working outside of England?
			</th>
			<td>
                <?php echo HTML::selectChosen('emp_q14', OnboardingHelper::getYesNoDdlYN(), $ob_learner->emp_q14, true, true); ?>
			</td>
		</tr>
		<tr>
			<th class="text-blue">
				14. Are you being paid at least the minimum wage which is relevant for your age?
				<span class="btn btn-xs btn-info" onclick="showMinimumWageInfo();"><i class="fa fa-info-circle"></i></span>
			</th>
			<td>
                <?php echo HTML::selectChosen('emp_q13', OnboardingHelper::getYesNoDdlYN(), $ob_learner->emp_q13, true, true); ?>
			</td>
		</tr>
	</table>

</div>



<script type="text/javascript">
	$(function(){

		$("select[name=emp_q9]").on('change', function(){
            $('#row_emp_q7').addClass('disabledRow');
            $('#row_emp_q8').addClass('disabledRow');
            $('#row_contract_end_date').addClass('disabledRow');
            $('#row_avg_weekly_hours').addClass('disabledRow');
            $("input[name=emp_q7]").val('');
            $("select[name=emp_q8]").val('');
            $("input[name=contract_end_date]").val('');
            $("input[name=avg_weekly_hours]").val('');
            $("input[name=emp_q7]").attr('readonly', true);
            $("select[name=emp_q8]").attr('readonly', true);
            $("input[name=row_contract_end_date]").attr('readonly', true);
            $("input[name=row_avg_weekly_hours]").attr('readonly', true);
            if(this.value == 'P')
            {
                $('#row_emp_q7').removeClass('disabledRow');
                $('#row_emp_q8').removeClass('disabledRow');
                $("input[name=emp_q7]").attr('readonly', false);
                $("select[name=emp_q8]").attr('readonly', false);
            }
            if(this.value == 'FT')
            {
                $('#row_contract_end_date').removeClass('disabledRow');
                $("input[name=row_contract_end_date]").attr('readonly', false);
            }
            if(this.value == 'ZH')
            {
                $('#row_avg_weekly_hours').removeClass('disabledRow');
                $("input[name=row_avg_weekly_hours]").attr('readonly', false);
            }
		});

        $("select[name=emp_q4]").on('change', function(){
            $('#row_Q5').addClass('disabledRow');
            if(this.value == 'Y')
            {
                $('#row_Q5').removeClass('disabledRow');
            }
        });

        $("select[name=emp_q11]").on('change', function(){
            $('#row_Q12').addClass('disabledRow');
            if(this.value == 'Y')
            {
                $('#row_Q12').removeClass('disabledRow');
            }
        });

        $('input#emp_q7').blur(function(){
            var num = parseFloat($(this).val());
            var cleanNum = num.toFixed(1);
            $(this).val(cleanNum);

            if(cleanNum > 70)
            {
                alert('Please enter a valid value for your contracted hours per week');
                $('input#emp_q7').focus();
            }
        });

        enableDisableEmploymentRows();
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

    function enableDisableEmploymentRows()
    {
        var typeOfContract = '<?php echo $ob_learner->emp_q9; ?>';
        if(typeOfContract == 'P')
        {
            $('#row_emp_q7').removeClass('disabledRow');
            $('#row_emp_q8').removeClass('disabledRow');
            $("input[name=emp_q7]").attr('readonly', false);
            $("select[name=emp_q8]").attr('readonly', false);
        }
        else if(typeOfContract == 'FT')
        {
            $('#row_contract_end_date').removeClass('disabledRow');
            $("input[name=row_contract_end_date]").attr('readonly', false);
        }
        else if(typeOfContract == 'ZH')
        {
            $('#row_avg_weekly_hours').removeClass('disabledRow');
            $("input[name=row_avg_weekly_hours]").attr('readonly', false);
        }

        var emp_11 = '<?php echo $ob_learner->emp_q11; ?>';
        if(emp_11 == 'Y')
        {
            $('#row_Q12').removeClass('disabledRow');
        }

        var emp_q4 = '<?php echo $ob_learner->emp_q4; ?>';
        if(emp_q4 == 'Y')
        {
            $('#row_Q5').removeClass('disabledRow');
        }
    }
    function numbersonlywithpoint(myfield, e, dec)
    {
        var key;
        var keychar;

        if (window.event)
            key = window.event.keyCode;
        else if (e)
            key = e.which;
        else
            return true;
        keychar = String.fromCharCode(key);

        // control keys
        if ((key==null) || (key==0) || (key==8) ||
            (key==9) || (key==13) || (key==27) )
            return true;

        // numbers
        else if ((("0123456789.").indexOf(keychar) > -1))
            return true;

        // decimal point jump
        else if (dec && (keychar == "."))
        {
            myfield.form.elements[dec].focus();
            myfield.form.elements[dec].select();
            return false;
        }
        else
            return false;
    }

</script>