<?php
$ddlNationalities = [[27, "British"]];
$ddlNationalities = $ddlNationalities + DAO::getResultset($link,"SELECT code, description, NULL FROM lookup_country_list WHERE code != 27 ORDER BY description;");

$ddlCountries = [["GB", "United Kingdom"]];
$ddlCountries = $ddlCountries + DAO::getResultset($link, "SELECT `country_code`, country_name FROM central.lookup_countries WHERE `country_code` != 'GB' ORDER BY country_name");
?>

<section class="text-bold text-purple text-center"><h1>Initial Screening Questionnaire</h1></section>

<section class="content">
	<form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" autocomplete="off">
		<input type="hidden" name="_action" value="save_app_questionnaire">

		<input type="hidden" name="which" value="1">
<!--			<div class="container container-table">-->

		<div class="row">
			<div class="col-sm-12">
				<div class="callout callout-default">
					<h3 class="text-bold">Appraisal of existing knowledge, skills and behaviours</h3>
					<p>As a part of the screening and enrolment process you will be required to complete an on-line assessment of your current knowledge, skills, experience and prior qualifications.</p>
					<p>In recognising prior learning, the following should be considered against the knowledge, skills and behaviours set out in the framework or standard:</p>
					<ul>
						<li>Work experience;</li>
						<li>Prior education, training or associated qualification(s) relating to Lean, Manufacturing and/or Business Improvement Techniques;</li>
						<li>Any previous apprenticeship undertaken.</li>
					</ul>
					<table class="table table-bordered">
						<tr>
							<th class="text-blue">
								Have you previously untaken any training in Lean, Manufacturing and/or Business Improvement Techniques?
							</th>
							<td>
								<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />
							</td>
						</tr>
						<tr>
							<th class="text-blue" colspan="2">If YES, provide details of this training below:</th>
						</tr>
						<tr>
							<td colspan="2">
								<textarea name="txt" id="txt" style="width: 100%;" rows="5"></textarea>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="callout callout-default">
					<h3 class="text-bold">New Skills, Knowledge and Behaviours</h3>
					<table class="table table-bordered">
						<tr>
							<th class="text-blue">
								Are you currently undertaking any other Apprenticeship, other qualifications or study with a college, university or other training provider?
							</th>
							<td>
								<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />
							</td>
						</tr>
						<tr>
							<th class="text-blue">
								Is this Apprenticeship at the same level or at a lower level than the highest qualification you already hold? e.g. if you have achieved any degree, A-levels, 5 or more GCSE A*-C grades or any other qualification that is level 2 or above then this will be Yes.
							</th>
							<td>
								<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />
							</td>
						</tr>
						<tr>
							<th class="text-blue">
								The Apprenticeship is a genuine job with an accompanying skills development programme?
							</th>
							<td>
								<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />
							</td>
						</tr>
						<tr>
							<th class="text-blue">
								The knowledge and skills the Apprenticeship will provide is substantially different from any previous qualifications you already hold?
							</th>
							<td>
								<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />
							</td>
						</tr>
						<tr>
							<th class="text-blue" colspan="2">Explain what new skills and knowledge you hope to gain by undertaking this Apprenticeship and how this will benefit you and your employer.</th>
						</tr>
						<tr>
							<td colspan="2">
								<textarea name="txt" id="txt" style="width: 100%;" rows="5"></textarea>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="callout callout-default">
					<h3 class="text-bold">Funding Eligibility <i>(Nationality and Residency Status)</i></h3>
					<table class="table table-bordered text-blue">
						<col width="20%;"/><col width="30%;"/><col width="20%;"/><col width="30%;"/>
						<tr>
							<th>Nationality</th>
							<td><?php echo HTML::selectChosen('nationality', $ddlNationalities, '', false); ?></td>
							<th>Country of birth</th>
							<td><?php echo HTML::selectChosen('country_of_birth', $ddlCountries, '', false); ?></td>
						</tr>
					</table>
					<table class="table table-bordered">
						<tr>
							<th class="text-blue">
								1.Have you been resident in the UK/or other EEA country for the last 3 years?
							</th>
							<td>
								<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />
							</td>
						</tr>
						<tr>
							<th class="text-blue">
								2.Do you have the right to live and work in the United Kingdom without restrictions?
							</th>
							<td>
								<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />
							</td>
						</tr>
						<tr>
							<td colspan="2"><i class="fa fa-info-circle"></i> If NO, to any of the above question's complete additional questions below.
								If YES, continue to section 5 Employment Status.
							</td>
						</tr>
						<tr>
							<th class="text-blue">
								3.Are you a family member (husband, wife, civil partner, child, grandchild, dependent parent or grandparent) of an EEA citizen who has been ordinarily resident in the EEA for at least the previous 3 years?
							</th>
							<td>
								<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />
							</td>
						</tr>
						<tr>
							<th class="text-blue">
								4.I am a Non-EEA citizen who has permission from the UK Government to live in the UK (not for educational purposes) and have been ordinarily resident in the UK for a least the previous 3 years?
							</th>
							<td>
								<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />
							</td>
						</tr>
						<tr>
							<th class="text-blue">
								5.I hold the following immigration status from the UK Government, or I am husband, wife, civil partner or child of someone who does (tick applicable)
							</th>
							<td>
								<p><input type="checkbox" value="" /> &nbsp; Refugee Status </p>
								<p><input type="checkbox" value="" /> &nbsp; Discretionary leave to remain </p>
								<p><input type="checkbox" value="" /> &nbsp; Exceptional leave to enter or remain </p>
								<p><input type="checkbox" value="" /> &nbsp; Indefinite leave to enter or remain </p>
								<p><input type="checkbox" value="" /> &nbsp; Humanitarian protection </p>
								<p><input type="checkbox" value="" /> &nbsp; I have leave outside the rules </p>
							</td>
						</tr>
						<tr>
							<th class="text-blue">
								6.Are there any immigration restrictions on how long you can stay in the UK?
							</th>
							<td>
								<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />
							</td>
						</tr>
						<tr>
							<th class="text-blue">
								7.Are you in the United Kingdom on a Tier 4 (general) Student Visa?
							</th>
							<td>
								<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />
							</td>
						</tr>
						<tr>
							<th class="text-blue">
								8.Are you registered as an Asylum Seeker? If YES, (*Please select box for circumstance below)
							</th>
							<td>
								<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />
							</td>
						</tr>
						<tr>
							<th class="text-blue" style="width: 50%;">
								*Have you lived in the UK for six months or longer while your claim is being considered by the Home Office, and no decision on your claim has been made?
								<p><input type="checkbox" value="" /></p>
							</th>
							<th class="text-blue">
								*Are you in the care of the local authority and receiving local authority and receiving local authority support?
								<p><input type="checkbox" value="" /></p>
							</th>
						</tr>
						<tr>
							<th class="text-blue">
								*I have been refused asylum, but I have lodged an appeal and no decision has been made within 6 months of me lodging an appeal.
								<p><input type="checkbox" value="" /></p>
							</th>
							<th class="text-blue">
								*I have been refused asylum but have been granted support under section 4 of the Immigration and Asylum Act 1999.
								<p><input type="checkbox" value="" /></p>
							</th>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="callout callout-default">
					<h3 class="text-bold">Employment Status <i>(Confirm current employment status)</i></h3>
					<table class="table table-bordered">
						<tr>
							<th style="width: 50%;">1.Employer Name</th>
							<td></td>
						</tr>
						<tr>
							<th>2.Your Workplace Address</th>
							<td></td>
						</tr>
						<tr>
							<th>3.Job Title</th>
							<td></td>
						</tr>
					</table>
					<table class="table table-bordered">
						<col width="60%;"/>
						<tr>
							<th class="text-blue">
								4.Are you an employee of the company named above?
							</th>
							<td>
								<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />
							</td>
						</tr>
						<tr>
							<th class="text-blue">
								5.Do you have a Contract of Employment?
							</th>
							<td>
								<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />
							</td>
						</tr>
						<tr>
							<th class="text-blue">
								6. If NO, are you employed as a contractor or agency staff?
							</th>
							<td>
								<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />
							</td>
						</tr>
						<tr>
							<th class="text-blue">
								7.How many hours are you contracted to work per week? <i>(Excludes overtime and additional hours worked outside of your contracted hours)</i>
							</th>
							<td>
								<input type="text" onkeypress="return numbersonly(this);" maxlength="3" /> hours
							</td>
						</tr>
						<tr>
							<th class="text-blue">
								8.How many days a week are you contracted to work?
							</th>
							<td>
								<input type="text" onkeypress="return numbersonly(this);" maxlength="1" /> days
							</td>
						</tr>
						<tr>
							<th class="text-blue">
								9.What type of contract do you have?
							</th>
							<td>
								<p><input type="radio" value="" /> &nbsp; Permanent </p>
								<p><input type="radio" value="" /> &nbsp; Fixed Term </p>
								<p><input type="radio" value="" /> &nbsp; Zero Hours </p>
							</td>
						</tr>
						<tr>
							<th class="text-blue">
								10.If you answered (Fixed term) above, please provide the contract end/expiry date.
								If you answered (Zero hours) above, please provide average weekly hours total.

							</th>
							<td>
								TBD :TODO:
							</td>
						</tr>
						<tr>
							<th class="text-blue">
								11.Does the nature of your job role cause you to spend any of your contracted hours working outside of England? Are you planning to leave the country for any work commitments or extended leave over a month within the duration of the programme?
							</th>
							<td>
								<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />
							</td>
						</tr>
						<tr>
							<th class="text-blue">
								12.If YES, provide details, number of days, hours or % of hours spent?
							</th>
							<td>
								<input type="text" /> hours
							</td>
						</tr>
						<tr>
							<th class="text-blue">
								13.Are you being paid at least the minimum wage which is relevant for your age?
							</th>
							<td>
								<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>

	</form>
</section>


