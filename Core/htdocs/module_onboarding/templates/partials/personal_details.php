<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="learner_title" class="col-sm-4 control-label fieldLabel_compulsory">Title:</label>
            <div class="col-sm-8">
                <?php echo HTML::selectChosen('learner_title', $titlesDDl, $ob_learner->learner_title, true, true); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="firstnames" class="col-sm-4 control-label fieldLabel_compulsory">First Name(s)*:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control compulsory" name="firstnames" id="firstnames" value="<?php echo $ob_learner->firstnames; ?>" maxlength="100" />
            </div>
        </div>
        <div class="form-group">
            <label for="surname" class="col-sm-4 control-label fieldLabel_compulsory">Surname:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control compulsory" name="surname" id="surname" value="<?php echo $ob_learner->surname; ?>" maxlength="100" />
            </div>
        </div>
        <div class="form-group">
            <label for="input_dob" class="col-sm-4 control-label fieldLabel_compulsory">Date of Birth:</label>
            <div class="col-sm-8"><input class="datecontrol compulsory form-control" type="text" id="input_dob" name="dob" value="<?php echo Date::toShort($ob_learner->dob); ?>" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></div>
        </div>
        <div class="form-group">
            <label for="ethnicity" class="col-sm-4 control-label fieldLabel_compulsory">Ethnicity:</label>
            <div class="col-sm-8">
                <?php echo HTML::selectChosen('ethnicity', $ethnicityDDL, $ob_learner->ethnicity, true); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="gender" class="col-sm-4 control-label fieldLabel_compulsory">Gender:</label>
            <div class="col-sm-8">
                <?php echo HTML::selectChosen('gender', InductionHelper::getDDLGender(), $ob_learner->gender, true, true); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="ni" class="col-sm-4 control-label fieldLabel_compulsory">National Insurance:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control compulsory" name="ni" id="ni" value="<?php echo $ob_learner->ni; ?>" maxlength="9" />
            </div>
        </div>

    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="home_address_line_1" class="col-sm-4 control-label fieldLabel_compulsory">Address Line 1:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control compulsory" name="home_address_line_1" id="home_address_line_1" value="<?php echo $ob_learner->home_address_line_1; ?>" maxlength="100" />
            </div>
        </div>
        <div class="form-group">
            <label for="home_address_line_2" class="col-sm-4 control-label fieldLabel_optional">Address Line 2:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control optional" name="home_address_line_2" id="home_address_line_2" value="<?php echo $ob_learner->home_address_line_2; ?>" maxlength="100" />
            </div>
        </div>
        <div class="form-group">
            <label for="home_address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">City:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control compulsory" name="home_address_line_3" id="home_address_line_3" value="<?php echo $ob_learner->home_address_line_3; ?>" maxlength="100" />
            </div>
        </div>
        <div class="form-group">
            <label for="home_address_line_4" class="col-sm-4 control-label fieldLabel_optional">County:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control optional" name="home_address_line_4" id="home_address_line_4" value="<?php echo $ob_learner->home_address_line_4; ?>" maxlength="100" />
            </div>
        </div>
        <div class="form-group">
            <label for="home_postcode" class="col-sm-4 control-label fieldLabel_compulsory">Postcode:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control compulsory" name="home_postcode" id="home_postcode" value="<?php echo $ob_learner->home_postcode; ?>" maxlength="100" />
            </div>
        </div>
        <div class="form-group">
            <label for="home_telephone" class="col-sm-4 control-label fieldLabel_optional">Telephone:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control optional" name="home_telephone" id="home_telephone" value="<?php echo $ob_learner->home_telephone; ?>" maxlength="100" />
            </div>
        </div>
        <div class="form-group">
            <label for="home_mobile" class="col-sm-4 control-label fieldLabel_optional">Mobile Phone:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control optional" name="home_mobile" id="home_mobile" value="<?php echo $ob_learner->home_mobile; ?>" maxlength="100" />
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

<div class="row">
    <div class="col-sm-12">
        <div class="callout">
            <label>Emergency Contact</label>
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
