<div class="col-sm-12">

	<p>As well as other information requested from you such as Knowledge and Skills, we also take any prior learning into consideration, with regard to:</p>
	<ul style="margin-left: 5%; margin-bottom: 5%;">
		<li>Work experience;</li>
		<li>Prior education, training or associated qualification(s) relating to Lean, Manufacturing and/or Business
			Improvement Techniques;
		</li>
		<li>Any previous apprenticeship undertaken.</li>
	</ul>
	<table class="table table-bordered">
        <col width="80%;"/>
        <tr>
			<th class="text-blue">
				With this in mind, can you tell us if you have you previously undertaken any training in Lean, Manufacturing and/or Business Improvement Techniques?
			</th>
			<td>
                <?php echo HTML::selectChosen('previous_training', OnboardingHelper::getYesNoDdlYN(), $ob_learner->previous_training, true, true); ?>
			</td>
		</tr>
		<tr>
			<th class="text-blue" colspan="2">If yes, provide details of this training below:</th>
		</tr>
		<tr>
			<td colspan="2">
				<textarea
                        <?php echo (isset($ob_learner->previous_training) && $ob_learner->previous_training == 'N') ? 'disabled' : ''; ?>
                        name="previous_training_details" id="previous_training_details" style="width: 100%;" rows="5"
				          maxlength="800"><?php echo $ob_learner->previous_training_details; ?></textarea>
			</td>
		</tr>
	</table>
</div>

<script>
    $(function(){
        $("select[name=previous_training]").on('change', function(){
            if(this.value == 'Y')
            {
                $("textarea[name=previous_training_details]").prop("disabled", false);
            }
            else
            {
                $("textarea[name=previous_training_details]").prop("disabled", true);
                $("textarea[name=previous_training_details]").val("");
            }
        });
    });
</script>