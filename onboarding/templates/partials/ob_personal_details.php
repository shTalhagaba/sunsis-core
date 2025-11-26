<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="learner_title" class="col-sm-4 control-label fieldLabel_compulsory">Title:</label>
            <div class="col-sm-8">
                <?php echo HTML::selectChosen('learner_title', $titlesDDl, $ob_learner->learner_title, true); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="firstnames" class="col-sm-4 control-label fieldLabel_compulsory">First Name(s):</label>
            <div class="col-sm-8">
                <input type="text" class="form-control compulsory" name="firstnames" id="firstnames" value="<?php echo $ob_learner->firstnames; ?>" maxlength="80" />
            </div>
        </div>
        <div class="form-group">
            <label for="surname" class="col-sm-4 control-label fieldLabel_compulsory">Surname:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control compulsory" name="surname" id="surname" value="<?php echo $ob_learner->surname; ?>" maxlength="80" />
            </div>
        </div>
        <div class="form-group">
            <label for="input_dob" class="col-sm-4 control-label fieldLabel_compulsory">Date of Birth:</label>
            <div class="col-sm-8">
                <input type="text" name="dob" id="input_dob" value="<?php echo Date::toShort($ob_learner->dob); ?>" class="form-control datecontrol">
            </div>
        </div>
        <div class="form-group">
            <label for="home_postcode" class="col-sm-4 control-label fieldLabel_compulsory">Current Postcode:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control compulsory" name="home_postcode" id="home_postcode" value="<?php echo $ob_learner->home_postcode; ?>" maxlength="10" />
            </div>
        </div>
        <div class="form-group">
            <label for="home_address_line_1" class="col-sm-4 control-label fieldLabel_compulsory">Current Address Line 1:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control compulsory" name="home_address_line_1" id="home_address_line_1" value="<?php echo $ob_learner->home_address_line_1; ?>" maxlength="80" />
            </div>
        </div>
        <div class="form-group">
            <label for="home_address_line_2" class="col-sm-4 control-label fieldLabel_optional">Current Address Line 2:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control optional" name="home_address_line_2" id="home_address_line_2" value="<?php echo $ob_learner->home_address_line_2; ?>" maxlength="80" />
            </div>
        </div>
        <div class="form-group">
            <label for="home_address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Current Address Line 3 (Town):</label>
            <div class="col-sm-8">
                <input type="text" class="form-control compulsory" name="home_address_line_3" id="home_address_line_3" value="<?php echo $ob_learner->home_address_line_3; ?>" maxlength="80" />
            </div>
        </div>
        <div class="form-group">
            <label for="home_address_line_4" class="col-sm-4 control-label fieldLabel_optional">Current Address Line 4 (County):</label>
            <div class="col-sm-8">
                <input type="text" class="form-control optional" name="home_address_line_4" id="home_address_line_4" value="<?php echo $ob_learner->home_address_line_4; ?>" maxlength="80" />
            </div>
        </div>
        <div class="form-group">
            <label for="borough" class="col-sm-4 control-label fieldLabel_optional">Borough (if you live in London):</label>
            <div class="col-sm-8">
                <input type="text" class="form-control optional" name="borough" id="borough" value="<?php echo $ob_learner->borough; ?>" maxlength="50" />
            </div>
        </div>
        <div class="form-group">
            <label for="home_telephone" class="col-sm-4 control-label fieldLabel_optional">Telephone Number:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control optional" name="home_telephone" id="home_telephone" value="<?php echo $ob_learner->home_telephone; ?>" maxlength="50" />
            </div>
        </div>
        <div class="form-group">
            <label for="home_mobile" class="col-sm-4 control-label fieldLabel_optional">Mobile Phone:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control optional" name="home_mobile" id="home_mobile" value="<?php echo $ob_learner->home_mobile; ?>" maxlength="50" />
            </div>
        </div>
        <div class="form-group">
            <label for="home_email" class="col-sm-4 control-label fieldLabel_optional">Email Address:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control optional" name="home_email" id="home_email" value="<?php echo $ob_learner->home_email; ?>" maxlength="100" />
            </div>
        </div>
        <div class="form-group">
            <label for="ni" class="col-sm-4 control-label fieldLabel_compulsory">National Insurance:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control compulsory" name="ni" id="ni" value="<?php echo $ob_learner->ni; ?>" maxlength="9" />
            </div>
        </div>
        <div class="form-group">
            <label for="ethnicity" class="col-sm-4 control-label fieldLabel_optional">Ethnicity:</label>
            <div class="col-sm-8">
                <?php echo HTML::selectChosen('ethnicity', $ethnicityDDL, $ob_learner->ethnicity, true); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="gender" class="col-sm-4 control-label fieldLabel_compulsory">Gender:</label>
            <div class="col-sm-8">
                <?php echo HTML::selectChosen('gender', LookupHelper::getDDLGender(), $ob_learner->gender, true, true); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="hhs" class="col-sm-4 control-label fieldLabel_compulsory">Household Situation:</label>
            <div class="col-sm-8">
                <?php echo HTML::selectChosen('hhs', LookupHelper::getDDLHhs(), $tr->hhs, true, true); ?>
            </div>
        </div>
        
    </div>
    
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px; margin-bottom: 10px;">
            <h4>Health</h4>
        </div>
        <div class="form-group">
            <label for="LLDD" class="col-sm-4 control-label fieldLabel_compulsory">Do you consider yourself to have a learning difficulty, health problem or disability?:</label>
            <div class="col-sm-8">
                <?php echo HTML::selectChosen('LLDD', $LLDD, $tr->LLDD, true, true); ?>
            </div>
        </div>
        <div class="form-group" id="divLLDDCat" style="display: none;">
            <div class="col-sm-4  control-label">
                <label>Select categories:</label>
            </div>    
            <div class="col-sm-8">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>Click to select the Category</th>
                        <th>Select which one is the Primary Category</th>
                    </tr>
                    <?php
                    foreach ($LLDDCat as $key => $value) {
                        $checked = in_array($key, $selected_llddcat) ? 'checked="checked"' : '';
                        $checked_pri = $key == $tr->primary_lldd ? 'checked="checked"' : '';
                        echo '<tr><td align="center" valign="center"><input class="clsICheck" type="checkbox" name="llddcat[]" ' . $checked . ' value="' . $key . '" /><label>' . $value . '</label></td><td><p><input type="radio" name="primary_lldd" value="' . $key . '" ' . $checked_pri . '></td></tr>';
                    }
                    ?>
                </table>
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

<?php 
$emergency_contacts_result = DAO::getResultset($link, "SELECT * FROM ob_learner_emergency_contacts WHERE tr_id = '{$tr->id}'", DAO::FETCH_ASSOC);
?>
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
                        echo isset($emergency_contacts_result[0]['em_con_title']) ? 
                            HTML::selectChosen('em_con_title1', $titlesDDl, $emergency_contacts_result[0]['em_con_title'], true) : 
                            HTML::selectChosen('em_con_title1', $titlesDDl, '', true); 
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="em_con_name1" class="col-sm-4 control-label">Full Name:</label>
                    <div class="col-sm-8">
                        <?php
                        echo isset($emergency_contacts_result[0]['em_con_name']) ? 
                            '<input type="text" class="form-control" name="em_con_name1" id="em_con_name1" value="'.$emergency_contacts_result[0]['em_con_name'].'" maxlength="100" />' : 
                            '<input type="text" class="form-control" name="em_con_name1" id="em_con_name1" maxlength="100" />'; 
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="em_con_rel1" class="col-sm-4 control-label">Relationship to you:</label>
                    <div class="col-sm-8">
                        <?php
                        echo isset($emergency_contacts_result[0]['em_con_rel']) ? 
                            '<input type="text" class="form-control" name="em_con_rel1" id="em_con_rel1" value="'.$emergency_contacts_result[0]['em_con_rel'].'" maxlength="100" />' : 
                            '<input type="text" class="form-control" name="em_con_rel1" id="em_con_rel1" maxlength="100" />'; 
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="em_con_tel1" class="col-sm-4 control-label">Telephone:</label>
                    <div class="col-sm-8">
                        <?php
                        echo isset($emergency_contacts_result[0]['em_con_tel']) ? 
                            '<input type="text" class="form-control" name="em_con_tel1" id="em_con_tel1" value="'.$emergency_contacts_result[0]['em_con_tel'].'" maxlength="70" />' : 
                            '<input type="text" class="form-control" name="em_con_tel1" id="em_con_tel1" maxlength="70" />'; 
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="em_con_mob1" class="col-sm-4 control-label">Mobile Phone:</label>
                    <div class="col-sm-8">
                        <?php
                        echo isset($emergency_contacts_result[0]['em_con_mob']) ? 
                            '<input type="text" class="form-control" name="em_con_mob1" id="em_con_mob1" value="'.$emergency_contacts_result[0]['em_con_mob'].'" maxlength="70" />' : 
                            '<input type="text" class="form-control" name="em_con_mob1" id="em_con_mob1" maxlength="70" />'; 
                        ?>
                    </div>
                </div>
		<div class="form-group">
                    <label for="em_con_email1" class="col-sm-4 control-label">Email:</label>
                    <div class="col-sm-8">
                        <?php
                        echo isset($emergency_contacts_result[0]['em_con_email']) ? 
                            '<input type="text" class="form-control" name="em_con_email1" id="em_con_email1" value="'.$emergency_contacts_result[0]['em_con_email'].'" maxlength="100" />' : 
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
                        echo isset($emergency_contacts_result[1]['em_con_title']) ? 
                            HTML::selectChosen('em_con_title2', $titlesDDl, $emergency_contacts_result[1]['em_con_title'], true) : 
                            HTML::selectChosen('em_con_title2', $titlesDDl, '', true); 
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="em_con_name2" class="col-sm-4 control-label">Full Name:</label>
                    <div class="col-sm-8">
                        <?php
                        echo isset($emergency_contacts_result[1]['em_con_name']) ? 
                            '<input type="text" class="form-control" name="em_con_name2" id="em_con_name2" value="'.$emergency_contacts_result[1]['em_con_name'].'" maxlength="100" />' : 
                            '<input type="text" class="form-control" name="em_con_name2" id="em_con_name2" maxlength="100" />'; 
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="em_con_rel2" class="col-sm-4 control-label">Relationship to you:</label>
                    <div class="col-sm-8">
                        <?php
                        echo isset($emergency_contacts_result[1]['em_con_rel']) ? 
                            '<input type="text" class="form-control" name="em_con_rel2" id="em_con_rel2" value="'.$emergency_contacts_result[1]['em_con_rel'].'" maxlength="100" />' : 
                            '<input type="text" class="form-control" name="em_con_rel2" id="em_con_rel2" maxlength="100" />'; 
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="em_con_tel2" class="col-sm-4 control-label">Telephone:</label>
                    <div class="col-sm-8">
                        <?php
                        echo isset($emergency_contacts_result[1]['em_con_tel']) ? 
                            '<input type="text" class="form-control" name="em_con_tel2" id="em_con_tel2" value="'.$emergency_contacts_result[1]['em_con_tel'].'" maxlength="70" />' : 
                            '<input type="text" class="form-control" name="em_con_tel2" id="em_con_tel2" maxlength="70" />'; 
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="em_con_mob2" class="col-sm-4 control-label">Mobile Phone:</label>
                    <div class="col-sm-8">
                        <?php
                        echo isset($emergency_contacts_result[1]['em_con_mob']) ? 
                            '<input type="text" class="form-control" name="em_con_mob2" id="em_con_mob2" value="'.$emergency_contacts_result[1]['em_con_mob'].'" maxlength="70" />' : 
                            '<input type="text" class="form-control" name="em_con_mob2" id="em_con_mob2" maxlength="70" />'; 
                        ?>
                    </div>
                </div>
		<div class="form-group">
                    <label for="em_con_email2" class="col-sm-4 control-label">Email:</label>
                    <div class="col-sm-8">
                        <?php
                        echo isset($emergency_contacts_result[0]['em_con_email']) ? 
                            '<input type="text" class="form-control" name="em_con_email2" id="em_con_email2" value="'.$emergency_contacts_result[0]['em_con_email'].'" maxlength="100" />' : 
                            '<input type="text" class="form-control" name="em_con_email2" id="em_con_email2" maxlength="100" />'; 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>