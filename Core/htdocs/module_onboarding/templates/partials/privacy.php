<?php
$selected_rui = explode(',', $ob_learner->RUI);
$selected_pmc = explode(',', $ob_learner->PMC);
$selected_disclaimer = explode(',', $ob_learner->disclaimer);
?>
<div style="text-align: justify;
  text-justify: inter-word;">
	<p>
		Lean Education and Development Limited is committed to protecting your privacy and personal data.
		The data captured on this form will be used to create a learner record and will be passed on to agencies of the Department for Education.
		For more information on what data we collect, how we use it, and your rights under our data protection law
		please visit <a href="leadlimited.co.uk/privacy-policy/" target="_blank">leadlimited.co.uk/privacy-policy/ </a>
	</p>

	<h4 class="text-bold"><u>How We Use Your Personal Information</u></h4>

	<p>
		This privacy notice is issued by the Education and Skills Funding Agency (ESFA), on behalf of the Secretary of
		State for the Department of Education (DfE). It is to inform learners how their personal information will be
		used by the DfE, the ESFA (an executive agency of the DfE) and any successor bodies to these organisations.
		For the purposes of relevant data protection legislation, the DfE is the data controller
		for personal data processed by the ESFA. Your personal information is used by the DfE to exercise its functions
		and to meet its statutory responsibilities, including under the Apprenticeships, Skills, Children and
		Learning Act 2009 and to create and maintain a unique learner number (ULN) and a personal learning record (PLR).
		Your information will be securely destroyed after it is no longer required for these purposes.
		Your information may be used for education, training, employment and well-being related purposes, including for research.
		The DfE and the English European Social Fund (ESF) Managing Authority (or agents acting on their behalf) may contact you in order for
		them to carry out research and evaluation to inform the effectiveness of training.
	</p>

	<p>Your information may also be shared with other third parties for the above purposes, but only where the law allows it and the
		sharing is in compliance with data protection legislation.
	</p>

	<p>
		Further information about use of and access to your personal data, details of organisations with whom we
		regularly share data, information about how long we retain your data, and how to change your consent to being contacted,
		please visit: <a href="https://www.gov.uk/government/publications/esfa-privacy-notice" target="_blank">https://www.gov.uk/government/publications/esfa-privacy-notice</a>
	</p>

	<p>
		The information you supply is used by the Learning Records Service (LRS). The LRS issues Unique Learner Numbers (ULN)
		and creates Personal Learning records across England, Wales and Northern Ireland, and is operated by the Education and Skills Funding Agency,
		an executive agency of the Department for Education (DfE).For more information about how your information is
		processed, and to access your Personal Learning Record,
		please refer to: <a href="https://www.gov.uk/government/publications/lrs-privacy-notices" target="_blank">https://www.gov.uk/government/publications/lrs-privacy-notices</a>
	</p>

	<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
		<img height="50px" style="background-color: #ffffff;" class="pull-right" src="<?php echo $header_image1; ?>" />
		<h4>GDPR</h4>How we use your personal data
	</div>
	<div class="well">
		<p>As you are aware <?php echo $company_name; ?> is your training provider. We want to be transparent with you about how we collect, process and store your data</p>
		<h4><strong>What information do we need?</strong></h4>
		<ul style="margin-left: 15px;">
			<li>Your contact details and personal characteristics</li>
			<li>Medical information we need to know to keep you sake</li>
			<li>Academic progress and attendance records</li>
			<li>Support needs and other pastoral information</li>
			<li>What you do next once you've finished your apprenticeship</li>
		</ul>
		<h4><strong>We will use your personal data in a number of ways, such as:</strong></h4>
		<ul style="margin-left: 15px;">
			<li>Support and monitor your learning, progress and achievement</li>
			<li>Provide you with advice, guidance and pastoral support</li>
			<li>Analyse our performance</li>
			<li>Meet our legal obligations</li>
		</ul>
		<h4><strong>Where do we keep your data?</strong></h4>
		<p>The information we collect about you is used by our staff in the UK. All of our data is stored in the UK, and our electronic data is stored on servers in the UK.</p>
		<h4><strong>How long do we keep your data?</strong></h4>
		<p>We are required to keep all documents, information, data, reports, accounts, records or written or verbal explanations relating to your apprenticeship for a minimum of 6 years after the end of you apprenticeship.</p>
		<h4><strong>Who will we share your information with?</strong></h4>
		<p>We may share information about you with certain other organizations, or get information about you from them. These other organisation�s include government departments, local authorities and examination boards.</p>
		<p>We are required by law to provide certain information about you to the Education and Skills funding agency. We may also haveto provide information to the European Social Fund (ESF).</p>
		<p>We will not give your information about you to anyone without your consent unless the law or policies allow us to do so.</p>
		<h4><strong>Contacting you</strong></h4>
		<p>We will contact you about your attendance, learning, progress and assessment in respect of the course you are studying.</p>

		<div class="table-responsive">
			<table class="table table-bordered text-blue">
				<col width="70%"><col width="30%">
				<tr>
					<th colspan="2">
						<span class="lead text-bold">You can <u>agree</u> to be contacted for other purposes by ticking any of the following boxes:</span>
					</th>
				</tr>
				<tr>
					<td>
						<input class="clsICheck" type="checkbox" name="RUI[]" value="1" <?php echo in_array(1, $selected_rui) ? 'checked' : '';?> /><label>About courses or learning opportunities.</label>
						<br>
						<input class="clsICheck" type="checkbox" name="RUI[]" value="2" <?php echo in_array(2, $selected_rui) ? 'checked' : '';?> /><label>For surveys and research.</label>
					</td>
					<td>
						<input class="clsICheck" type="checkbox" name="PMC[]" value="1" <?php echo in_array(1, $selected_pmc) ? 'checked' : '';?> /><label>By post</label>
						<br>
						<input class="clsICheck" type="checkbox" name="PMC[]" value="2" <?php echo in_array(2, $selected_pmc) ? 'checked' : '';?> /><label>By phone</label>
						<br>
						<input class="clsICheck" type="checkbox" name="PMC[]" value="3" <?php echo in_array(3, $selected_pmc) ? 'checked' : '';?> /><label>By email</label>
					</td>
				</tr>
			</table>
		</div>

	</div>



	<div class="table-responsive">
		<table class="table table-bordered  text-blue" >
			<tr>
				<th>
					<u class="lead text-bold">Consent - Please click to accept the following statements in order to continue</u>:
				</th>
			</tr>
			<tr>
				<td>
					<input class="clsICheck" type="checkbox" name="disclaimer[]" value="1" <?php echo in_array(1, $selected_disclaimer) ? 'checked' : '';?> /><label>I give consent to use my image on social media and for marketing purposes.</label>
				</td>
			</tr>
			<tr>
				<td>
					<input class="clsICheck" type="checkbox" name="disclaimer[]" value="3" <?php echo in_array(1, $selected_disclaimer) ? 'checked' : '';?> /><label>I give consent for my coach to take photo and film recordings.</label>
				</td>
			</tr>
			<tr class="bg-gray">
				<td>
					<input class="clsICheck" type="checkbox" name="disclaimer[]" value="2" /><label>I give my consent to my coach to take voice recordings to use as evidence as part of my course content (this is a requirement for Functional Skills English - Speaking, Listening and Communication).</label>
				</td>
			</tr>
			<tr class="bg-gray">
				<td>
					<input class="clsICheck" type="checkbox" name="disclaimer[]" value="4" /><label>I agree to adhere to the rules and regulations of the Data Protection Act 1998 and the Freedom of Information Act 2000, ensuring high standards in the returning and communication of personal information and giving  a general right of access to all recorded information held by public authorities, including educational establishments.</label>
				</td>
			</tr>
			<tr class="bg-gray">
				<td>
					<input class="clsICheck" type="checkbox" name="disclaimer[]" value="5" /><label>I agree to promote and adhere to Equal Opportunity and Diversity policies on race, gender, age, disability, religion or belief and sexual orientation within the Apprenticeship Programme.</label>
				</td>
			</tr>
			<tr class="bg-gray">
				<td>
					<input class="clsICheck" type="checkbox" name="disclaimer[]" value="6" /><label>I have read and understood GDPR statement regarding my personal data.</label>
				</td>
			</tr>
		</table>
	</div>

</div>