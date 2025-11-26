<?php
$ddlNationalities = [[27, "British"]];
$ddlNationalities = $ddlNationalities + DAO::getResultset($link,"SELECT code, description, NULL FROM lookup_country_list WHERE code != 27 ORDER BY description;");

$ddlCountries = [["GB", "United Kingdom"]];
$ddlCountries = $ddlCountries + DAO::getResultset($link, "SELECT `country_code`, country_name FROM central.lookup_countries WHERE `country_code` != 'GB' ORDER BY country_name");

$ob_learner->funding_q5 = explode(',', $ob_learner->funding_q5);
$ob_learner->funding_q9 = explode(',', $ob_learner->funding_q9);
?>

<div class="col-sm-12">
	<table class="table table-bordered text-blue">
		<col width="20%;"/><col width="30%;"/><col width="20%;"/><col width="30%;"/>
		<tr>
			<th>Nationality</th>
			<td><?php echo HTML::selectChosen('nationality', $ddlNationalities, $ob_learner->nationality, false, true); ?></td>
			<th>Country of birth</th>
			<td><?php echo HTML::selectChosen('country_of_birth', $ddlCountries, $ob_learner->country_of_birth, false, true); ?></td>
		</tr>
		<tr>
			<th colspan="2">Please provide a copy of your Passport, Driving License or Birth Certificate (we will delete the document/s once viewed)</th>
			<td  colspan="2"><input type="file" class="form-control optional" name="nationality_id_file" id="nationality_id_file"  /></td>
		</tr>
	</table>
	<table class="table table-bordered">
		<col width="70%;" />
		<tr>
			<th class="text-blue">
				1. Were you 16 or over on the last Friday in June?
			</th>
			<td>
                <?php echo HTML::selectChosen('funding_q10', OnboardingHelper::getYesNoDdlYN(), $ob_learner->funding_q10, true, true); ?>
			</td>
		</tr>
		<tr>
			<th class="text-blue">
				2. Have you been resident in the UK/or other EEA country for the last 3 years?
			</th>
			<td>
                <?php echo HTML::selectChosen('funding_q1', OnboardingHelper::getYesNoDdlYN(), $ob_learner->funding_q1, true, true); ?>
			</td>
		</tr>
		<tr>
			<th class="text-blue">
				3. Do you have the right to live and work in the United Kingdom without restrictions?
			</th>
			<td>
                <?php echo HTML::selectChosen('funding_q2', OnboardingHelper::getYesNoDdlYN(), $ob_learner->funding_q2, true, true); ?>
			</td>
		</tr>
    </table>

    <table class="table table-bordered tbl_funding_additional_questions" style="display: none;">
        <col width="70%;" />
		<tr>
			<td colspan="2"><i class="fa fa-info-circle"></i> If you answered NO to Questions 2 or 3, please complete additional questions below.
				If YES, continue to section 6 Employment Status.
			</td>
		</tr>
		<tr>
			<th class="text-blue">
				4. Are you a family member (husband, wife, civil partner, child, grandchild, dependent parent or grandparent) of an EEA citizen who has been ordinarily resident in the EEA for at least the previous 3 years?
			</th>
			<td>
                <?php echo HTML::selectChosen('funding_q3', OnboardingHelper::getYesNoDdlYN(), $ob_learner->funding_q3, true, false); ?>
			</td>
		</tr>
		<tr>
			<th class="text-blue">
				5. I am a Non-EEA citizen who has permission from the UK Government to live in the UK (not for educational purposes) and have been ordinarily resident in the UK for a least the previous 3 years?
			</th>
			<td>
                <?php echo HTML::selectChosen('funding_q4', OnboardingHelper::getYesNoDdlYN(), $ob_learner->funding_q4, true, false); ?>
			</td>
		</tr>
		<tr>
			<th class="text-blue">
				6. I hold the following immigration status from the UK Government, or I am husband, wife, civil partner or child of someone who does (tick applicable)
			</th>
			<td>
				<p><input class="clsICheck" name="funding_q5[]" type="checkbox" value="RS" <?php echo in_array('RS', $ob_learner->funding_q5) ? 'checked' : ''; ?> /> <label>Refugee Status</label></p>
				<p><input class="clsICheck" name="funding_q5[]" type="checkbox" value="DLTR" <?php echo in_array('DLTR', $ob_learner->funding_q5) ? 'checked' : ''; ?> /> <label>Discretionary leave to remain</label></p>
				<p><input class="clsICheck" name="funding_q5[]" type="checkbox" value="ELTEOR" <?php echo in_array('ELTEOR', $ob_learner->funding_q5) ? 'checked' : ''; ?> /> <label>Exceptional leave to enter or remain</label></p>
				<p><input class="clsICheck" name="funding_q5[]" type="checkbox" value="ILTEOR" <?php echo in_array('ILTEOR', $ob_learner->funding_q5) ? 'checked' : ''; ?> /> <label>Indefinite leave to enter or remain</label></p>
				<p><input class="clsICheck" name="funding_q5[]" type="checkbox" value="HP" <?php echo in_array('HP', $ob_learner->funding_q5) ? 'checked' : ''; ?> /> <label>Humanitarian protection</label></p>
				<p><input class="clsICheck" name="funding_q5[]" type="checkbox" value="IHLOTR" <?php echo in_array('IHLOTR', $ob_learner->funding_q5) ? 'checked' : ''; ?> /> <label>I have leave outside the rules</label></p>
			</td>
		</tr>
		<tr>
			<th class="text-blue">
				7. Are there any immigration restrictions on how long you can stay in the UK?
			</th>
			<td>
                <?php echo HTML::selectChosen('funding_q6', OnboardingHelper::getYesNoDdlYN(), $ob_learner->funding_q6, true, false); ?>
			</td>
		</tr>
		<tr>
			<th class="text-blue">
				8. Are you in the United Kingdom on a Tier 4 (general) Student Visa?
			</th>
			<td>
                <?php echo HTML::selectChosen('funding_q7', OnboardingHelper::getYesNoDdlYN(), $ob_learner->funding_q7, true, false); ?>
			</td>
		</tr>
		<tr>
			<th class="text-blue">
				9. Are you registered as an Asylum Seeker? If YES, (*Please select box for circumstance below)
			</th>
			<td>
                <?php echo HTML::selectChosen('funding_q8', OnboardingHelper::getYesNoDdlYN(), $ob_learner->funding_q8, true, false); ?>
			</td>
		</tr>
	</table>
	<table class="table table-bordered text-center tbl_funding_additional_questions" style="display: none;">
		<col width="50%;"><col width="50%;">
		<tbody>
		<tr>
			<th class="text-blue">
				*Have you lived in the UK for six months or longer while your claim is being considered by the Home Office, and no decision on your claim has been made?
				<p><input type="checkbox" name="funding_q9[]" value="1" <?php echo in_array('1', $ob_learner->funding_q9) ? 'checked' : ''; ?> /></p>
			</th>
			<th class="text-blue">
				*Are you in the care of the local authority and receiving local authority and receiving local authority support?
				<p><input type="checkbox" name="funding_q9[]" value="2" <?php echo in_array('2', $ob_learner->funding_q9) ? 'checked' : ''; ?> /></p>
			</th>
		</tr>
		<tr>
			<th class="text-blue">
				*I have been refused asylum, but I have lodged an appeal and no decision has been made within 6 months of me lodging an appeal.
				<p><input type="checkbox" name="funding_q9[]" value="3" <?php echo in_array('3', $ob_learner->funding_q9) ? 'checked' : ''; ?> /></p>
			</th>
			<th class="text-blue">
				*I have been refused asylum but have been granted support under section 4 of the Immigration and Asylum Act 1999.
				<p><input type="checkbox" name="funding_q9[]" value="4" <?php echo in_array('4', $ob_learner->funding_q9) ? 'checked' : ''; ?> /></p>
			</th>
		</tr>
		</tbody>
	</table>
	<table class="table table-bordered text-blue tbl_funding_additional_questions" style="display: none;">
		<col width="60%;"/><col width="40%;"/>
		<tr>
			<th>Have you been granted Indefinite Leave to Enter/Remain? If yes please provide a copy of it.</th>
			<td><input type="file" class="form-control optional" name="ilr_file" id="ilr_file"  /></td>
		</tr>
		<tr>
			<th>Have you been previously granted a visa to study in the UK? If yes please provide a copy of it.</th>
			<td><input type="file" class="form-control optional" name="student_visa_file" id="student_visa_file"  /></td>
		</tr>
	</table>

</div>

<script>
    $(function(){

        $("select[name=funding_q1], select[name=funding_q2]").on('change', function(){
            showAdditionalRows();
        });

        <?php
        if($ob_learner->funding_q1 == 'Y' || $ob_learner->funding_q2 == 'Y')
        {
            echo "showAdditionalRows();";
        }
        ?>

    });

    function showAdditionalRows()
    {
        var q1 = $("select[name=funding_q1]").val();
        var q2 = $("select[name=funding_q2]").val();

        if(q1 == "N" || q2 == "N" || q1 == "" || q2 == "")
            $(".tbl_funding_additional_questions").show();
        if(q1 == "Y" && q2 == "Y")
            $(".tbl_funding_additional_questions").hide();
    }

</script>