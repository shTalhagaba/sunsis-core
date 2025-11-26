<?php /* @var $tr TrainingRecord */ ?>

<div class="row">
    <div class="col-sm-12">
        <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
            <h4>Apprenticeship Care Leaver</h4>
        </div>
    </div>
</div>

<p><br></p>

<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="ehc_plan" class="col-sm-6 control-label fieldLabel_optional">Do you have an Education Health Care Plan?:</label>
            <div class="col-sm-6">
                <input type="checkbox" name="ehc_plan" value="1" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo $tr->ehc_plan == "1" ? 'checked="checked"' : '';?> />
            </div>
        </div>
        <div class="form-group divEhcPlanEvidence" style="display: none;">
            <label for="ehc_plan" class="col-sm-6 control-label fieldLabel_optional">Upload your Education Health Care Plan:</label>
            <div class="col-sm-6">
                <input type="file" class="form-control optional" name="ehc_evidence_file" id="ehc_evidence_file" value=""  />
            </div>
        </div>
        <div class="form-group">
            <label for="care_leaver" class="col-sm-6 control-label fieldLabel_optional">Are you a care leaver?:</label>
            <div class="col-sm-6">
                <input type="checkbox" name="care_leaver" value="1" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo $tr->care_leaver == "1" ? 'checked="checked"' : '';?> />
            </div>
        </div>
    </div>
</div>

<div class="row" <?php echo $tr->care_leaver != '1' ? 'style="display: none;"' : '' ;?> id="divCareLeaverInfo">
    <div class="col-sm-12">

        <div class="table-responsive">
            <table class="table table-bordered">
                <caption class="callout callout-info">
                    <i class="fa fa-info-circle"></i>
                    Please answer the following questions regarding Care Leaver if you are aged 16 to 24.
                    If you are aged 25 and above, you do not need to answer the following questions and you can skip to
                    the Next page.
                </caption>

                <tr>
                    <td colspan="2">
                        <table class="table-bordered table-condensed">
                            <tr><th colspan="2">For an apprentice to be eligible for the care leavers' bursary, they must
                                    have begun their apprenticeship on or after 1 August 2018, and must not have received
                                    the care leavers' bursary before. Please select one of the three options below:</th></tr>
                            <tr>
                                <td>
                                    an eligible child - a young person who is 16 or 17 and who has been looked after by a UK local authority or health and social care trust for at least a period of 13 weeks since the age of 14 and who is still looked after
                                </td>
                                <td>
                                    <input type="radio" value="1" name="child_type" <?php echo $care_leaver_details->child_type == 1 ? 'checked' : ''; ?> />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    a relevant child - a young person who is 16 or 17 who has left care within the UK after their 16th birthday and before leaving care was an eligible child
                                </td>
                                <td>
                                    <input type="radio" value="2" name="child_type" <?php echo $care_leaver_details->child_type == 2 ? 'checked' : ''; ?> />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    a former relevant child - a young person who is aged between 18 and 21 (up to their 25th birthday if they are in education or training) who, before turning 18, was either an eligible or a relevant child
                                </td>
                                <td>
                                    <input type="radio" value="3" name="child_type" <?php echo $care_leaver_details->child_type == 3 ? 'checked' : ''; ?> />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th>Can you please confirm that you been in the care of a UK local authority?</th>
                    <td><input type="checkbox" name="in_care_of_local_authority" id="in_care_of_local_authority" value="1" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo $care_leaver_details->in_care_of_local_authority == "1" ? 'checked="checked"' : '';?> /></td>
                </tr>
                <tr>
                    <th>As a care leaver, you are eligible to receive a &pound;1,000 bursary payment. Please confirm whether you would like to access this bursary?</th>
                    <td><input type="checkbox" name="eligible_for_bursary_payment" id="eligible_for_bursary_payment" value="1" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo $care_leaver_details->eligible_for_bursary_payment == "1" ? 'checked="checked"' : '';?> /></td>
                </tr>
                <tr>
                    <th>Do you give consent to inform your employer that you have been in the care of a UK local authority?<br>
                        (If yes, your declaration will be used to generate additional payments to both the main provider and your employer to support your transition into work).</th>
                    <td><input type="checkbox" name="give_consent_to_inform_employer" id="give_consent_to_inform_employer" value="1" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo $care_leaver_details->give_consent_to_inform_employer == "1" ? 'checked="checked"' : '';?> /></td>
                </tr>
                <tr>
                    <th>We will need evidence of one of the following: please select</th>
                    <td>
                        <?php
                        $ddlCareaLeaverEvidenceTypes = [
                            [1, 'Signed Email from a local authority appointed personal advisor confirming that you are a care leaver.'],
                            [2, 'Letter from a local authority appointed personal advisor confirming that you are a care leaver.'],
                        ];
                        echo HTML::selectChosen('in_care_evidence', $ddlCareaLeaverEvidenceTypes, $care_leaver_details->in_care_evidence, true);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Upload evidence for care leaver</th>
                    <td>
                        <input type="file" class="form-control optional" name="in_care_evidence_file" id="in_care_evidence_file" value=""  />
                    </td>
                </tr>
                <tr>
                    <th colspan="2">Care Leaver Bank Details</th>
                </tr>
                <tr>
                    <td colspan="2">
                        <p>Learners who are eligible for the &pound;1,000 Care Leaver Bursary will need to provide their bank details so that the bursary can be paid directly to them.</p>
                        <p>Learners who are not eligible for this bursary are not required to provide their bank details. </p>
                        <p>By providing these details, I confirm that <?php echo $provider->legal_name; ?> are authorised to pay the Care Leaver Bursary payment, when due, into the account as detailed below.</p>
                    </td>
                </tr>
                <tr>
                    <th>Name of Bank</th>
                    <td><input class="form-control" type="text" name="care_leaver_bank_name" value="<?php echo $care_leaver_details->care_leaver_bank_name; ?>" maxlength="100" /></td>
                </tr>
                <tr>
                    <th>Account Name</th>
                    <td><input class="form-control" type="text" name="care_leaver_account_name" value="<?php echo $care_leaver_details->care_leaver_account_name; ?>" maxlength="100" /></td>
                </tr>
                <tr>
                    <th>Sort Code</th>
                    <td><input class="form-control" type="text" name="care_leaver_sort_code" value="<?php echo $care_leaver_details->care_leaver_sort_code; ?>" maxlength="6" /></td>
                </tr>
                <tr>
                    <th>Account Number</th>
                    <td><input class="form-control" type="text" name="care_leaver_account_number" value="<?php echo $care_leaver_details->care_leaver_account_number; ?>" maxlength="8" /></td>
                </tr>


            </table>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered  text-blue">
                <tr>
                    <th>
                        <u>Declarations</u>: I confirm that
                    </th>
                </tr>
                <tr>
                    <td>
                        <input class="clsICheck" type="checkbox" name="care_leaver_declarations[]" value="1" <?php echo in_array(1, $selected_disclaimer) ? 'checked' : '';?> /><label>I understand that I am eligible for and would like to receive a bursary as a care leaver.</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input class="clsICheck" type="checkbox" name="care_leaver_declarations[]" value="2" <?php echo in_array(2, $selected_disclaimer) ? 'checked' : '';?> /><label>I understand that if I have been found to have accepted the payment incorrectly or if I am ineligible then the government will require it to be repaid.</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input class="clsICheck" type="checkbox" name="care_leaver_declarations[]" value="3" <?php echo in_array(3, $selected_disclaimer) ? 'checked' : '';?> /><label>I have not been paid a care leavers bursary before. This only includes the care leavers bursary paid by the Department for Education (DfE); other local incentives do not apply.</label>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script>
    $(function(){
       $("input[name=care_leaver]").on('change', function(){
           if(this.checked)
           {
               $('div#divCareLeaverInfo').show();
           }
           else
           {
               $('div#divCareLeaverInfo').hide();
           }
        });

       $("input[name=ehc_plan]").on('change', function(){
           if(this.checked)
           {
               $('div.divEhcPlanEvidence').show();
           }
           else
           {
               $('div.divEhcPlanEvidence').hide();
           }
        });
    });
</script>