<?php 
$ob_directory = Repository::getRoot() . DIRECTORY_SEPARATOR . 'OnboardingModule' . DIRECTORY_SEPARATOR . 'learners' . DIRECTORY_SEPARATOR . $tr->ob_learner_id . DIRECTORY_SEPARATOR . $tr->id . DIRECTORY_SEPARATOR .'onboarding';
?>
<form class="form-horizontal" name="frmLearnerEligibility" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />
    <input type="hidden" name="_action" value="save_learner_eligibility" />
    <div class="row">
        <div class="col-sm-12">

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h2 class="box-title">Details</h2>
                    <div class="pull-right box-tools">
                        <span class="btn btn-sm btn-primary" onclick="save_learner_eligibility();">
                            <i class="fa fa-save"></i> Save Eligibility information
                        </span>
                    </div>
                </div>
                <div class="box-body">

                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table row-border">
                                <col style="width: 60%;">
                                <tbody>
                                <tr>
                                    <td>Are you a care leaver?</td>
                                    <td><?php echo HTML::selectChosen('care_leaver', OnboardingHelper::getYesNoDDL(), $tr->care_leaver, true); ?></td>
                                </tr>
                                <tr>
                                    <td>If you are a care leaver upload your Evidence:</td>
                                    <td>
                                        <input type="file" class="form-control optional" name="care_leaver_evidence_file" id="care_leaver_evidence_file" value=""  />
                                        <?php 
                                        if($tr->care_leaver_evidence_file != '' && is_file($ob_directory . DIRECTORY_SEPARATOR . $tr->care_leaver_evidence_file) )
                                        {
                                            $_file_care_leaver = new RepositoryFile($ob_directory . DIRECTORY_SEPARATOR . $tr->care_leaver_evidence_file);
                                            echo '<a href="' . $_file_care_leaver->getDownloadURL() . '">' . $_file_care_leaver->getName() . '</a>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Do you have an Education Health Care Plan?</td>
                                    <td><?php echo HTML::selectChosen('ehc_plan', OnboardingHelper::getYesNoDDL(), $tr->ehc_plan, true); ?></td>
                                </tr>
                                <tr>
                                    <td>If you have an Education Health Care Plan, upload your evidence</td>
                                    <td>
                                        <input type="file" class="form-control optional" name="ehc_evidence_file" id="ehc_evidence_file" value=""  />
                                        <?php 
                                        if($tr->ehc_evidence_file != '' && is_file($ob_directory . DIRECTORY_SEPARATOR . $tr->ehc_evidence_file) )
                                        {
                                            $_file_ehc_evidence_file = new RepositoryFile($ob_directory . DIRECTORY_SEPARATOR . $tr->ehc_evidence_file);
                                            echo '<a href="' . $_file_ehc_evidence_file->getDownloadURL() . '">' . $_file_ehc_evidence_file->getName() . '</a>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php if(DB_NAME == "am_superdrug") { ?>
                                    <tr>
                                        <td>When learner was in secondary school, was learner eligible for free school meals?</td>
                                        <td><?php echo HTML::selectChosen('free_school_meals', OnboardingHelper::getYesNoDDL(), $tr->free_school_meals, true); ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table row-border">
                                <col style="width: 60%;">
                                <tbody>
                                <tr>
                                    <td>Are you currently enrolled at any other college, or training provider?</td>
                                    <td><input type="checkbox" name="EligibilityList[]" value="2" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo in_array(2, $saved_eligibility_list)?'checked="checked"':''; ?> /></td>
                                    <!-- <td><?php //echo HTML::selectChosen('EligibilityList[]', [[2, 'Yes']], (in_array(2, $saved_eligibility_list) ? '2' : ''), true); ?></td> -->
                                </tr>
                                <tr>
                                    <td>If yes, please give details:</td>
                                    <td><input type="text" name="currently_enrolled_in_other" class="form-control" disabled value="<?php echo $tr->currently_enrolled_in_other; ?>" maxlength="250" /></td>
                                </tr>
                                </tbody>
                            </table>
                            <table class="table row-border">
                                <col style="width: 60%;">
                                <tbody>
                                <tr>
                                    <td>Do you currently have access to a student loan?</td>
                                    <td><?php echo HTML::selectChosen('had_student_loan', OnboardingHelper::getYesNoDDL(), $tr->had_student_loan, true); ?></td>
                                </tr>
                                <tr>
                                    <td>If Yes can you confirm this has been terminated and you are no longer receiving funding from the Student Loans Company?</td>
                                    <td><?php echo HTML::selectChosen('student_loan_terminated', OnboardingHelper::getYesNoDDL(), $tr->student_loan_terminated, true); ?></td>
                                </tr>
                                <tr>
                                    <td>Have you been asked to contribute to the cost of your programme?</td>
                                    <td><?php echo HTML::selectChosen('asked_to_contribute', OnboardingHelper::getYesNoDDL(), $tr->asked_to_contribute, true); ?></td>
                                </tr>
                                </tbody>
                            </table>
                            <table class="table row-border">
                                <tbody>
                                <tr>
                                    <td>Country of birth:</td>
                                    <td><?php echo HTML::selectChosen('country_of_birth', $countries, $tr->country_of_birth, true); ?></td>
                                    <td>Country of permanent residence:</td>
                                    <td><?php echo HTML::selectChosen('country_of_perm_residence', $countries, $tr->country_of_perm_residence, true); ?></td>
                                </tr>
                                <tr>
                                    <td>Nationality:</td>
                                    <td>
                                        <?php
                                        if($tr->nationality != '')
                                            echo HTML::selectChosen('nationality', $nationalities, $tr->nationality, true);
                                        else
                                            echo HTML::selectChosen('nationality', $nationalities, '', true);
                                        ?>
                                    </td>
                                    <td>Please provide a copy of your passport, birth certificate or driving license :</td>
                                    <td>
                                        <input type="file" class="form-control optional" name="evidence_pp_file" id="evidence_pp_file" value=""  />
                                        <?php 
                                        if($tr->evidence_pp_file != '' && is_file($ob_directory . DIRECTORY_SEPARATOR . $tr->evidence_pp_file) )
                                        {
                                            $_file_evidence_pp_file = new RepositoryFile($ob_directory . DIRECTORY_SEPARATOR . $tr->evidence_pp_file);
                                            echo '<a href="' . $_file_evidence_pp_file->getDownloadURL() . '">' . $_file_evidence_pp_file->getName() . '</a>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <table class="table row-border">
                                <tbody>
                                <tr>
                                    <td>Evidence Type</td>
                                    <td>
                                        <?php echo HTML::selectChosen('id_evidence_type', DAO::getResultset($link, "SELECT id, description FROM lookup_id_evidence_types"), $tr->id_evidence_type, true); ?>
                                    </td>
                                    <td>Evidence Reference</td>
                                    <td>
                                        <input class="form-control" type="text" name="evidence_reference" id="evidence_reference" value="<?php echo $tr->evidence_reference; ?>" maxlength="25">
                                    </td>
                                    <td>Expiration date of the document</td>
                                    <td>
                                        <?php echo HTML::datebox('evidence_expiry_date', $tr->evidence_expiry_date); ?>
                                    </td>
                                </tr>
                                </tbody>
                            </tables>

                            <table class="table row-border">
                                <tbody>
				<tr>
                                    <td>Have you lived within the UK for the last 3 Years?</td>
                                    <!-- <td><?php //echo HTML::selectChosen('EligibilityList[]', [[1, 'Yes']], (in_array(1, $saved_eligibility_list) ? '1' : ''), true); ?></td> -->
                                    <td><input type="checkbox" name="EligibilityList[]" value="1" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo in_array(1, $saved_eligibility_list)?'checked="checked"':''; ?> /></td>
                                </tr>
				<tr>
                                    <td>Do you have the right to live in the UK for at least 12 months after your programme has finished?</td>
                                    <!-- <td><?php //echo HTML::selectChosen('EligibilityList[]', [[10, 'Yes']], (in_array(10, $saved_eligibility_list) ? '10' : ''), true); ?></td> -->
                                    <td><input type="checkbox" name="EligibilityList[]" value="10" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo in_array(10, $saved_eligibility_list)?'checked="checked"':''; ?> /></td>
                                </tr>
                                <tr>
                                    <td>Do you have a valid National Insurance Number?</td>
                                    <td><input type="checkbox" name="EligibilityList[]" value="3" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo in_array(3, $saved_eligibility_list)?'checked="checked"':''; ?> /></td>
                                </tr>
				<?php if($framework->fund_model == Framework::FUNDING_STREAM_APP) { ?>
                                <tr>
                                    <td>Are you attending School or College for any other Further or Higher Education training apart from this apprenticeship?</td>
                                    <td><input type="checkbox" name="EligibilityList[]" value="4" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo in_array(4, $saved_eligibility_list)?'checked="checked"':''; ?> /></td>
                                </tr>
				<?php } ?>
                                </tbody>
                            </table>
                            <table class="table">
                                <tbody>
                                <tr style="background-color: #d3d3d3;"><th colspan="2">Applicants not born in the United Kingdom, please answer the following questions</th></tr>
                                <tr>
                                    <td>Are you a non-EU citizen currently resident in the UK?</td>
                                    <td><input type="checkbox" name="EligibilityList[]" value="5" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo in_array(5, $saved_eligibility_list)?'checked="checked"':''; ?> /></td>
                                </tr>
                                <tr><td colspan="2">If you have answered yes, please provide the following information in order to assist us in making an assessment of eligibility.</td></tr>
                                <tr><td>Date of first entry to the UK:</td><td><input class="datecontrol form-control" type="text" id="date_of_first_uk_entry" name="date_of_first_uk_entry" value="<?php echo Date::toShort($tr->date_of_first_uk_entry); ?>" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td></tr>
                                <tr><td>Date of most recent entry to the UK (excluding holidays):</td><td><input class="datecontrol form-control" type="text" id="date_of_most_recent_uk_entry" name="date_of_most_recent_uk_entry" value="<?php echo Date::toShort($tr->date_of_most_recent_uk_entry); ?>" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td></tr>
                                <tr>
                                    <td>Have you been granted indefinite Leave to Enter/Remain in the UK? If yes, please provide a copy of your ILR status as evidence.</td>
                                    <td>
                                        <input type="file" class="form-control optional" name="evidence_ilr_file" id="evidence_ilr_file" value=""  />
                                        <?php 
                                        if($tr->evidence_ilr_file != '' && is_file($ob_directory . DIRECTORY_SEPARATOR . $tr->evidence_ilr_file) )
                                        {
                                            $_file_evidence_ilr_file = new RepositoryFile($ob_directory . DIRECTORY_SEPARATOR . $tr->evidence_ilr_file);
                                            echo '<a href="' . $_file_evidence_ilr_file->getDownloadURL() . '">' . $_file_evidence_ilr_file->getName() . '</a>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <table class="table">
                                <tbody>
                                <tr>
                                    <td>Do you need a visa to study in the UK?</td>
                                    <td><input type="checkbox" name="EligibilityList[]" value="6" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo in_array(6, $saved_eligibility_list)?'checked="checked"':''; ?> /></td>
                                </tr>
                                <tr><td>If you have answered yes, please provide your passport number:</td><td><input class="form-control" type="text" name="passport_number" id="passport_number" value="<?php echo $tr->passport_number; ?>" maxlength="10" /></td></tr>
                                <tr><td>If no, what is your current immigration status:</td><td><input class="form-control" type="text" name="immigration_category" id="immigration_category" value="<?php echo $tr->immigration_category; ?>" maxlength="70" /></td></tr>
                                <tr>
                                    <td>Have you previously been granted a visa to study in the UK? If yes, please upload a copy of any such visas.</td>
                                    <td>
                                        <input type="file" class="form-control optional" name="evidence_previous_uk_study_visa_file" id="evidence_previous_uk_study_visa_file" value=""  />
                                        <?php 
                                        if($tr->evidence_previous_uk_study_visa_file != '' && is_file($ob_directory . DIRECTORY_SEPARATOR . $tr->evidence_previous_uk_study_visa_file) )
                                        {
                                            $_file_evidence_previous_uk_study_visa_file = new RepositoryFile($ob_directory . DIRECTORY_SEPARATOR . $tr->evidence_previous_uk_study_visa_file);
                                            echo '<a href="' . $_file_evidence_previous_uk_study_visa_file->getDownloadURL() . '">' . $_file_evidence_previous_uk_study_visa_file->getName() . '</a>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>In the following box, please provide other residency notes and details if required.</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <textarea name="other_residency_details" id="other_residency_details" rows="5" class="form-control" placeholder="Other residency notes and details"><?php echo $tr->other_residency_details; ?></textarea>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

			    <?php if(in_array(DB_NAME, ["am_ela", "am_eet"])) {?>
                            <table class="table">
                                <tbody>
                                <tr style="background-color: #d3d3d3;"><th colspan="3">Rehabilitation of Offenders</th></tr>
                                <tr>
                                    <td>
                                        Any criminal convictions except those for minor motoring offences or those spent in accordance with the Rehabilitation of Offenders Act 1974?
                                    </td>
                                    <td>
                                        <input type="radio" name="crime_conviction" value="Yes" <?php echo $tr->crime_conviction == 'Yes' ? 'checked="checked"' : ''; ?> /> &nbsp; Yes
                                    </td>
                                    <td>
                                        <input type="radio" name="crime_conviction" value="No" <?php echo $tr->crime_conviction == 'No' ? 'checked="checked"' : ''; ?> /> &nbsp; No
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <?php } ?>


                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</form>