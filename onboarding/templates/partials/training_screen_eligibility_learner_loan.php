<?php 
$ob_directory = Repository::getRoot() . DIRECTORY_SEPARATOR . 'OnboardingModule' . DIRECTORY_SEPARATOR . 'learners' . DIRECTORY_SEPARATOR . $tr->ob_learner_id . DIRECTORY_SEPARATOR . $tr->id . DIRECTORY_SEPARATOR .'onboarding';
$ob_learner_extra_details = DAO::getObject($link, "SELECT * FROM ob_learner_extra_details WHERE tr_id = {$tr->id}");
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
                                <col style="width: 60%;" />
                                <tbody>
                                <tr>
                                    <td>Learner Immigration Status</td>
                                    <td><?php echo HTML::selectChosen('immigration_status', OnboardingHelper::immigrationStatusDdl(), isset($ob_learner_extra_details->immigration_status) ? $ob_learner_extra_details->immigration_status : '', true); ?></td>
                                </tr>
                                <tr>
                                    <td>Learner has a valid UK Passport?</td>
                                    <td><?php echo HTML::selectChosen('have_uk_pp', OnboardingHelper::getYesNoDDL(), isset($ob_learner_extra_details->have_uk_pp) ? $ob_learner_extra_details->have_uk_pp : '', true); ?></td>
                                </tr>
                                <tr>
                                    <td>Learner has a UK Birth Certificate?</td>
                                    <td><?php echo HTML::selectChosen('have_uk_bc', OnboardingHelper::getYesNoDDL(), isset($ob_learner_extra_details->have_uk_bc) ? $ob_learner_extra_details->have_uk_bc : '', true); ?></td>
                                </tr>
                                <tr>
                                    <td>Country of birth:</td>
                                    <td><?php echo HTML::selectChosen('country_of_birth', $countries, $tr->country_of_birth, true); ?></td>
                                </tr>
                                <tr>
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
                                </tr>
                                <tr>
                                    <td>Copy of your passport, birth certificate or driving license :</td>
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
                                <tr>
                                    <td>Learner is a legal resident of the UK and able to take paid employment within the EU?</td>
                                    <td><?php echo HTML::selectChosen('legal_uk_resident', OnboardingHelper::getYesNoDDL(), isset($ob_learner_extra_details->legal_uk_resident) ? $ob_learner_extra_details->legal_uk_resident : '', true); ?></td>
                                </tr>
                                <tr>
                                    <td>Learner has lived within the UK/EU for the last 3 Years?</td>
                                    <td><?php echo HTML::selectChosen('lived_in_eu', OnboardingHelper::getYesNoDDL(), isset($ob_learner_extra_details->lived_in_eu) ? $ob_learner_extra_details->lived_in_eu : '', true); ?></td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
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
                            <table class="table row-border">
                                <tbody>
                                <tr style="background-color: #d3d3d3;"><th colspan="4">ICT</th></tr>
                                <tr>
                                    <th>ICT Skills</th>
                                    <td>
                                        <span class="text-bold">Internet/Email</span><br>
                                        <?php echo HTML::selectChosen('internet_use', OnboardingHelper::ictSkillsDdl(), isset($ob_learner_extra_details->internet_use) ? $ob_learner_extra_details->internet_use : null, true); ?>
                                    </td>
                                    <td>
                                        <span class="text-bold">MS Office (Word/Excel)</span><br>
                                        <?php echo HTML::selectChosen('ms_office', OnboardingHelper::ictSkillsDdl(), isset($ob_learner_extra_details->ms_office) ? $ob_learner_extra_details->ms_office : null, true); ?>
                                    </td>
                                    <td>
                                        <span class="text-bold">ePortfolio (or similar web-based platforms)</span><br>
                                        <?php echo HTML::selectChosen('eportfolio', OnboardingHelper::ictSkillsDdl(), isset($ob_learner_extra_details->eportfolio) ? $ob_learner_extra_details->eportfolio : null, true); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Access to Devices</th>
                                    <td>
                                        <span class="text-bold">Smartphone</span><br>
                                        <?php echo HTML::selectChosen('smartphone', OnboardingHelper::getYesNoDDL(), isset($ob_learner_extra_details->smartphone) ? $ob_learner_extra_details->smartphone : null, true); ?>
                                    </td>
                                    <td>
                                        <span class="text-bold">Tablet</span><br>
                                        <?php echo HTML::selectChosen('tablet', OnboardingHelper::getYesNoDDL(), isset($ob_learner_extra_details->tablet) ? $ob_learner_extra_details->tablet : null, true); ?>
                                    </td>
                                    <td>
                                        <span class="text-bold">Laptop/PC</span><br>
                                        <?php echo HTML::selectChosen('laptop', OnboardingHelper::getYesNoDDL(), isset($ob_learner_extra_details->laptop) ? $ob_learner_extra_details->laptop : null, true); ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>