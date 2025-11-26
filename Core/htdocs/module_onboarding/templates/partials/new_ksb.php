<div class="col-sm-12">
	<table class="table table-bordered">
        <col width="80%;" />
		<tr>
			<th class="text-blue">
				Are you currently undertaking any other Apprenticeship, other qualifications or study with a college,
				university or other training provider?
			</th>
			<td>
                <?php echo HTML::selectChosen('currently_undertaking_training', OnboardingHelper::getYesNoDdlYN(), $ob_learner->currently_undertaking_training, true, true); ?>
			</td>
		</tr>
		<tr>
			<td class="text-blue">
				<span class="text-bold">If Yes, is this Apprenticeship at the same level or at a lower level than the highest qualification you already hold?</span>
				<br>
				e.g. if you have achieved any degree, A-levels, 5 or more GCSE A*-C grades or any other qualification
				that is level 2 or above then this will be Yes.
			</td>
			<td>
                <?php echo HTML::selectChosen('same_or_lower', OnboardingHelper::getYesNoDdlYN(), $ob_learner->same_or_lower, true, true); ?>
            </td>
		</tr>
		<tr>
			<th class="text-blue">
				Is your apprenticeship a genuine job which includes a skills development programme and is the Knowledge
				and Skills you hope to gain substantially different from any other previous qualification you already hold?
			</th>
			<td>
                <?php echo HTML::selectChosen('genuine_job', OnboardingHelper::getYesNoDdlYN(), $ob_learner->genuine_job, true, true); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<span class="text-blue text-bold">Explain what new skills and knowledge you hope to gain by undertaking this Apprenticeship and how this will benefit you and your employer.</span>
				<textarea class="compulsory" name="new_training_details" id="new_training_details" style="width: 100%;"
				          maxlength="800"
				          rows="5"><?php echo $ob_learner->new_training_details; ?></textarea>
			</td>
		</tr>
	</table>
</div>
