<?php
$selectedLlddcat = explode(',', $registration->llddcat);
?>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="learner_title" class="col-sm-4 control-label fieldLabel_compulsory">Title: *</label>
            <div class="col-sm-8">
                <?php echo HTML::selectChosen('learner_title', $titlesDdl, $registration->learner_title, true); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="firstnames" class="col-sm-4 control-label fieldLabel_compulsory">First Name(s): *</label>
            <div class="col-sm-8">
                <input type="text" class="form-control compulsory" name="firstnames" id="firstnames" value="<?php echo $registration->firstnames; ?>" maxlength="80" onblur="showNameHeading();" />
            </div>
        </div>
        <div class="form-group">
            <label for="surname" class="col-sm-4 control-label fieldLabel_compulsory">Surname: *</label>
            <div class="col-sm-8">
                <input type="text" class="form-control compulsory" name="surname" id="surname" value="<?php echo $registration->surname; ?>" maxlength="80" onblur="showNameHeading();" />
            </div>
        </div>
        <div class="form-group">
            <label for="input_dob" class="col-sm-4 control-label fieldLabel_compulsory">Date of Birth: *</label>
            <div class="col-sm-8">
                <input type="text" name="dob" id="input_dob" value="<?php echo Date::toShort($registration->dob); ?>" class="form-control datecontrol">
            </div>
        </div>
        <div class="form-group">
            <label for="home_postcode" class="col-sm-4 control-label fieldLabel_compulsory">Current Postcode: *</label>
            <div class="col-sm-8">
                <input type="text" class="form-control compulsory" name="home_postcode" id="home_postcode" value="<?php echo $registration->home_postcode; ?>" maxlength="10" />
            </div>
        </div>
        <div class="form-group">
            <label for="home_address_line_1" class="col-sm-4 control-label fieldLabel_compulsory">Current Address Line 1: *</label>
            <div class="col-sm-8">
                <input type="text" class="form-control compulsory" name="home_address_line_1" id="home_address_line_1" value="<?php echo $registration->home_address_line_1; ?>" maxlength="80" />
            </div>
        </div>
        <div class="form-group">
            <label for="home_address_line_2" class="col-sm-4 control-label fieldLabel_optional">Current Address Line 2:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control optional" name="home_address_line_2" id="home_address_line_2" value="<?php echo $registration->home_address_line_2; ?>" maxlength="80" />
            </div>
        </div>
        <div class="form-group">
            <label for="home_address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Current Address Line 3  (Town): *</label>
            <div class="col-sm-8">
                <input type="text" class="form-control compulsory" name="home_address_line_3" id="home_address_line_3" value="<?php echo $registration->home_address_line_3; ?>" maxlength="80" />
            </div>
        </div>
        <div class="form-group">
            <label for="home_address_line_4" class="col-sm-4 control-label fieldLabel_optional">Current Address Line 4  (County):</label>
            <div class="col-sm-8">
                <input type="text" class="form-control optional" name="home_address_line_4" id="home_address_line_4" value="<?php echo $registration->home_address_line_4; ?>" maxlength="80" />
            </div>
        </div>
        <div class="form-group">
            <label for="home_telephone" class="col-sm-4 control-label fieldLabel_optional">Telephone Number:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control optional" name="home_telephone" id="home_telephone" value="<?php echo $registration->home_telephone; ?>" maxlength="50" />
            </div>
        </div>
        <div class="form-group">
            <label for="home_mobile" class="col-sm-4 control-label fieldLabel_optional">Mobile Phone:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control optional" name="home_mobile" id="home_mobile" value="<?php echo $registration->home_mobile; ?>" maxlength="50" />
            </div>
        </div>
        <div class="form-group">
            <label for="home_email" class="col-sm-4 control-label fieldLabel_optional">Email Address: *</label>
            <div class="col-sm-8">
                <input type="email" class="form-control optional" name="home_email" id="home_email" value="<?php echo $registration->home_email; ?>" maxlength="100" />
            </div>
        </div>
        <div class="form-group">
            <label for="ni" class="col-sm-4 control-label fieldLabel_compulsory">National Insurance: *</label>
            <div class="col-sm-8">
                <input type="text" class="form-control compulsory" name="ni" id="ni" value="<?php echo $registration->ni; ?>" maxlength="9" />
            </div>
        </div>
        <div class="form-group">
            <label for="gender" class="col-sm-4 control-label fieldLabel_compulsory">Gender: *</label>
            <div class="col-sm-8">
                <?php echo HTML::selectChosen('gender', $gendersDdl, $registration->gender, true, true); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="ethnicity" class="col-sm-4 control-label fieldLabel_optional">Ethnicity: *</label>
            <div class="col-sm-8">
                <?php echo HTML::selectChosen('ethnicity', $ethnicityDdl, $registration->ethnicity, true); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="hhs" class="col-sm-4 control-label fieldLabel_compulsory">Household Situation: *</label>
            <div class="col-sm-8">
                <?php echo HTML::selectChosen('hhs', LookupHelper::getDDLHhs(), $registration->hhs, true, true); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="criminal_conviction" class="col-sm-4 control-label fieldLabel_compulsory">
                Do you have criminal conviction?<br><span class="text-info">(excluding motor offences)</span>: *
            </label>
            <div class="col-sm-8">
                <?php echo HTML::selectChosen('criminal_conviction', $YesNoList, $registration->criminal_conviction, true, true); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="currently_caring" class="col-sm-4 control-label fieldLabel_compulsory">
                Are you currently caring for children or other adults? *</span>
            </label>
            <div class="col-sm-8">
                <?php echo HTML::selectChosen('currently_caring', $YesNoList, $registration->currently_caring, true, true); ?>
            </div>
        </div>
        
    </div>
    
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px; margin-bottom: 10px;">
            <h4>Emergency Contacts</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="box box-primary" style="box-shadow: 0 5px 5px rgba(1, 1, 0, 0.1)">
            <div class="box-header with-border">
                <span class="box-title">Emergency Contact 1</span>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label for="em_con_title1" class="col-sm-4 control-label">Title:</label>
                    <div class="col-sm-8">
                        <?php
                        echo isset($registration->em_con_title1) ? 
                            HTML::selectChosen('em_con_title1', $titlesDdl, $registration->em_con_title1, true) : 
                            HTML::selectChosen('em_con_title1', $titlesDdl, '', true); 
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="em_con_name1" class="col-sm-4 control-label">Full Name:</label>
                    <div class="col-sm-8">
                        <?php
                        echo isset($registration->em_con_name1) ? 
                            '<input type="text" class="form-control" name="em_con_name1" id="em_con_name1" value="'.$registration->em_con_name1.'" maxlength="100" />' : 
                            '<input type="text" class="form-control" name="em_con_name1" id="em_con_name1" maxlength="100" />'; 
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="em_con_rel1" class="col-sm-4 control-label">Relationship to you:</label>
                    <div class="col-sm-8">
                        <?php
                        echo isset($registration->em_con_rel1) ? 
                            '<input type="text" class="form-control" name="em_con_rel1" id="em_con_rel1" value="'.$registration->em_con_rel1.'" maxlength="100" />' : 
                            '<input type="text" class="form-control" name="em_con_rel1" id="em_con_rel1" maxlength="100" />'; 
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="em_con_tel1" class="col-sm-4 control-label">Telephone:</label>
                    <div class="col-sm-8">
                        <?php
                        echo isset($registration->em_con_tel1) ? 
                            '<input type="text" class="form-control" name="em_con_tel1" id="em_con_tel1" value="'.$registration->em_con_tel1.'" maxlength="70" />' : 
                            '<input type="text" class="form-control" name="em_con_tel1" id="em_con_tel1" maxlength="70" />'; 
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="em_con_mob1" class="col-sm-4 control-label">Mobile Phone:</label>
                    <div class="col-sm-8">
                        <?php
                        echo isset($registration->em_con_mob1) ? 
                            '<input type="text" class="form-control" name="em_con_mob1" id="em_con_mob1" value="'.$registration->em_con_mob1.'" maxlength="70" />' : 
                            '<input type="text" class="form-control" name="em_con_mob1" id="em_con_mob1" maxlength="70" />'; 
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="em_con_email1" class="col-sm-4 control-label">Email:</label>
                    <div class="col-sm-8">
                        <?php
                        echo isset($registration->em_con_email1) ? 
                            '<input type="text" class="form-control" name="em_con_email1" id="em_con_email1" value="'.$registration->em_con_email1.'" maxlength="100" />' : 
                            '<input type="text" class="form-control" name="em_con_email1" id="em_con_email1" maxlength="100" />'; 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="box box-primary" style="box-shadow: 0 5px 5px rgba(1, 1, 0, 0.1)">
            <div class="box-header with-border">
                <span class="box-title">Emergency Contact 2</span>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label for="em_con_title2" class="col-sm-4 control-label">Title:</label>
                    <div class="col-sm-8">
                        <?php
                        echo isset($registration->em_con_title2) ? 
                            HTML::selectChosen('em_con_title2', $titlesDdl, $registration->em_con_title2, true) : 
                            HTML::selectChosen('em_con_title2', $titlesDdl, '', true); 
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="em_con_name2" class="col-sm-4 control-label">Full Name:</label>
                    <div class="col-sm-8">
                        <?php
                        echo isset($registration->em_con_name2) ? 
                            '<input type="text" class="form-control" name="em_con_name2" id="em_con_name2" value="'.$registration->em_con_name2.'" maxlength="100" />' : 
                            '<input type="text" class="form-control" name="em_con_name2" id="em_con_name2" maxlength="100" />'; 
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="em_con_rel2" class="col-sm-4 control-label">Relationship to you:</label>
                    <div class="col-sm-8">
                        <?php
                        echo isset($registration->em_con_rel2) ? 
                            '<input type="text" class="form-control" name="em_con_rel2" id="em_con_rel2" value="'.$registration->em_con_rel2.'" maxlength="100" />' : 
                            '<input type="text" class="form-control" name="em_con_rel2" id="em_con_rel2" maxlength="100" />'; 
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="em_con_tel2" class="col-sm-4 control-label">Telephone:</label>
                    <div class="col-sm-8">
                        <?php
                        echo isset($registration->em_con_tel2) ? 
                            '<input type="text" class="form-control" name="em_con_tel2" id="em_con_tel2" value="'.$registration->em_con_tel2.'" maxlength="70" />' : 
                            '<input type="text" class="form-control" name="em_con_tel2" id="em_con_tel2" maxlength="70" />'; 
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="em_con_mob2" class="col-sm-4 control-label">Mobile Phone:</label>
                    <div class="col-sm-8">
                        <?php
                        echo isset($registration->em_con_mob2) ? 
                            '<input type="text" class="form-control" name="em_con_mob2" id="em_con_mob2" value="'.$registration->em_con_mob2.'" maxlength="70" />' : 
                            '<input type="text" class="form-control" name="em_con_mob2" id="em_con_mob2" maxlength="70" />'; 
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="em_con_email2" class="col-sm-4 control-label">Email:</label>
                    <div class="col-sm-8">
                        <?php
                        echo isset($registration->em_con_email2) ? 
                            '<input type="text" class="form-control" name="em_con_email2" id="em_con_email2" value="'.$registration->em_con_email2.'" maxlength="100" />' : 
                            '<input type="text" class="form-control" name="em_con_email2" id="em_con_email2" maxlength="100" />'; 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>