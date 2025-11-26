<?php /* @var $ob_learner User */ ?>
<?php
$gender_list = InductionHelper::getListGender();
?>

<div class="row">
    <div class="col-sm-6">
        <?php
        echo '<span style="font-size: larger">' . $ob_learner->learner_title . ' ' . $ob_learner->firstnames . ' ' . $ob_learner->surname . '</span><br>';
        echo $ob_learner->home_address_line_1 != '' ? $ob_learner->home_address_line_1 . '' : '' ;
        echo $ob_learner->home_address_line_2 != '' ? ', ' . $ob_learner->home_address_line_2 . '' : '' ;
        echo $ob_learner->home_address_line_3 != '' ? ' ' . $ob_learner->home_address_line_3 . '' : '' ;
        echo $ob_learner->home_address_line_4 != '' ? ' ' . $ob_learner->home_address_line_4 : '' ;
        echo $ob_learner->home_postcode != '' ? '<br>' . $ob_learner->home_postcode . '<br>' : '' ;
//        echo $ob_learner->home_telephone != '' ? '<i class="fa fa-phone"></i> ' . $ob_learner->home_telephone . '<br>' : '' ;
//        echo $ob_learner->home_mobile != '' ? '<i class="fa fa-mobile"></i> ' . $ob_learner->home_mobile . '<br>' : '' ;
//        echo $ob_learner->home_email != '' ? '<i class="fa fa-envelope"></i> ' . $ob_learner->home_email . '<br>' : '' ;
        ?>
    </div>
    <div class="col-sm-6">
        <span class="text-bold">Date of Birth: </span><?php echo Date::toShort($ob_learner->dob); ?><br>
        <span class="text-bold">Gender: </span><?php echo isset($gender_list[$ob_learner->gender]) ? $gender_list[$ob_learner->gender] : $ob_learner->gender; ?><br>
        <span class="text-bold">Ethnicity: </span><?php echo DAO::getSingleValue($link, "SELECT Ethnicity_Desc FROM lis201213.ilr_ethnicity WHERE Ethnicity = '{$ob_learner->ethnicity}'"); ?><br>
        <span class="text-bold">National Insurance: </span><?php echo $ob_learner->ni; ?><br>
    </div>
</div>
<hr>

<div class="row">
    <div class="row-sm-12">
        <div class="callout">
            <label style="font-size: larger">Your Contact</label>
            <div class="form-group">
                <label for="home_telephone" class="col-sm-4 control-label fieldLabel_optional">Telephone:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control optional" name="home_telephone" id="home_telephone" value="<?php echo $ob_learner->home_telephone; ?>" maxlength="50" />
                </div>
            </div>
            <div class="form-group">
                <label for="home_mobile" class="col-sm-4 control-label fieldLabel_optional">Mobile:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control optional" name="home_mobile" id="home_mobile" value="<?php echo $ob_learner->home_mobile; ?>" maxlength="50" />
                </div>
            </div>
            <div class="form-group">
                <label for="home_email" class="col-sm-4 control-label fieldLabel_optional">Email:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control optional" name="home_email" id="home_email" value="<?php echo $ob_learner->home_email; ?>" maxlength="100" />
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="row-sm-12">
        <div class="callout">
            <label style="font-size: larger">Emergency Contact</label>
            <div class="form-group">
                <label for="em_con_title" class="col-sm-4 control-label fieldLabel_optional">Title:</label>
                <div class="col-sm-8">
                    <?php echo HTML::selectChosen('em_con_title', $titlesDDl, $ob_learner->em_con_title, true); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="em_con_name" class="col-sm-4 control-label fieldLabel_optional">Name:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control optional" name="em_con_name" id="em_con_name" value="<?php echo $ob_learner->em_con_name; ?>" maxlength="100" />
                </div>
            </div>
            <div class="form-group">
                <label for="em_con_rel" class="col-sm-4 control-label fieldLabel_optional">Relationship:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control optional" name="em_con_rel" id="em_con_rel" value="<?php echo $ob_learner->em_con_rel; ?>" maxlength="100" />
                </div>
            </div>
            <div class="form-group">
                <label for="em_con_tel" class="col-sm-4 control-label fieldLabel_optional">Telephone:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control optional" name="em_con_tel" id="em_con_tel" value="<?php echo $ob_learner->em_con_tel; ?>" maxlength="100" />
                </div>
            </div>
            <div class="form-group">
                <label for="em_con_mob" class="col-sm-4 control-label fieldLabel_optional">Mobile:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control optional" name="em_con_mob" id="em_con_mob" value="<?php echo $ob_learner->em_con_mob; ?>" maxlength="100" />
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="row-sm-12">
        <div class="callout">
            <label style="font-size: larger">LLDD</label>
            <div class="form-group small">
                <label for="LLDD" class="col-sm-6 control-label fieldLabel_compulsory">Do you consider yourself to have a learning difficulty, health problem or disability?:</label>
                <div class="col-sm-6">
                    <?php echo HTML::selectChosen('LLDD', $LLDD, $ob_learner->LLDD, true, true); ?>
                </div>
            </div>
            <div class="form-group" id="divLLDDCat" style="display: none;">
                <div class="col-sm-12" style="max-height: 300px; overflow-y: scroll;">
                    <label>Select categories and also select the primary category:</label>
                    <table class="table table-bordered table-condensed table-striped">
                        <tr><th>Category</th><th>Primary Category</th></tr>
                        <?php
                        foreach($LLDDCat AS $key => $value)
                        {
                            $checked = in_array($key, $selected_llddcat)?'checked="checked"':'';
                            $checked_pri = $key == $ob_learner->primary_lldd?'checked="checked"':'';
                            echo '<tr><td><input type="checkbox" name="llddcat[]" '.$checked.' value="'.$key.'" /> <label> &nbsp; '.$value.'</label></td><td><p><input type="radio" name="primary_lldd" value="'.$key.'" '.$checked_pri.'></td></tr>';
                        }
                        ?>
                    </table>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-condensed">
                    <tr>
                        <td>Do you need additional learning support?:</td>
                        <td>
                            <?php echo HTML::selectChosen('need_als', OnboardingHelper::getYesNoDdlYN(), $ob_learner->need_als, true, true); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Are you a care leaver?:</td>
                        <td>
                            <?php echo HTML::selectChosen('care_leaver', OnboardingHelper::getYesNoDdlYN(), $ob_learner->care_leaver, true, true); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Do you have an EHC Plan?:</td>
                        <td>
                            <?php echo HTML::selectChosen('EHC_Plan', OnboardingHelper::getYesNoDdlYN(), $ob_learner->EHC_Plan, true, true); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Upload evidence for care leaver or EHC plan:</td>
                        <td>
                            <input type="file" class="form-control optional" name="care_or_ehc" id="care_or_ehc" value=""  />
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="row-sm-12">
        <div class="callout">
            <label style="font-size: larger">Household Information - indicate if any of the following apply to your household</label>
            <div class="table-responsive">
                <table class="table table-bordered text-center bg-gray-active">
                    <col width="50%;"><col width="50%;">
                    <tbody>
                    <tr>
                        <td>
                            No member of the household in which learner lives (including learner) is employed
                            <p><input type="checkbox" name="HHS[]" value="1" <?php echo in_array('1', $selected_hhs) ? 'checked' : ''; ?> /></p>
                        </td>
                        <td>
                            The household that learner lives in includes only one adult (aged 18 or over)
                            <p><input type="checkbox" name="HHS[]" value="2" <?php echo in_array('2', $selected_hhs) ? 'checked' : ''; ?> /></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            There are one or more dependent children (aged 0-17 years or 18-24 years if full-time student or inactive) in the household
                            <p><input type="checkbox" name="HHS[]" value="3" <?php echo in_array('3', $selected_hhs) ? 'checked' : ''; ?> /></p>
                        </td>
                        <td>
                            None of these statements apply
                            <p><input type="checkbox" name="HHS[]" value="99" <?php echo in_array('99', $selected_hhs) ? 'checked' : ''; ?> /></p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            You want to withhold this information
                            <p><input type="checkbox" name="HHS[]" value="98" <?php echo in_array('98', $selected_hhs) ? 'checked' : ''; ?> /></p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!--<div class="row">
    <div class="row-sm-12">
        <div class="callout">
            <label style="font-size: larger">Are you receiving any of the following type of benefit?</label>
            <div class="table-responsive">
                <table class="table table-bordered text-center bg-gray-active">
                    <col width="50%;"><col width="50%;">
                    <tbody>
                    <tr>
                        <td>
                            Job Seekers Allowance (JSA)
                            <p><input type="checkbox" name="BSI[]" value="1" <?php /*echo in_array('1', $selected_bsi) ? 'checked' : ''; */?> /></p>
                        </td>
                        <td>
                            Universal Credit
                            <p><input type="checkbox" name="BSI[]" value="4" <?php /*echo in_array('2', $selected_bsi) ? 'checked' : ''; */?> /></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Employment & Support Allowance
                            <p><input type="checkbox" name="BSI[]" value="2" <?php /*echo in_array('3', $selected_bsi) ? 'checked' : ''; */?> /></p>
                        </td>
                        <td>
                            Other State benefit not listed
                            <p><input type="checkbox" name="BSI[]" value="3" <?php /*echo in_array('99', $selected_bsi) ? 'checked' : ''; */?> /></p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
-->