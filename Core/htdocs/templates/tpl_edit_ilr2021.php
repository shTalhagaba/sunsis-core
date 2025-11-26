<!DOCTYPE html>
<html>
<head>
    <title>Sunesis- Individual Learner Record</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width = device-width, initial-scale = 1.0"/>
    <link href="/css/zozo.tabs.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/css/common_2015.css" type="text/css"/>
    <link rel="stylesheet" href="/css/ilr2015.css" type="text/css"/>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/jquery.easing.min.js"></script>
    <script src="/js/zozo.tabs.min.js"></script>
    <link rel="stylesheet" href="/css/form-validation/validationEngine.jquery.css" type="text/css"/>
    <script src="/js/form-validation/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
    <script src="/js/form-validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
    <link rel="stylesheet" type="text/css" href="/css/tooltipster.css" />
    <script type="text/javascript" src="js/jquery.tooltipster.min.js"></script>
    <script src="/common.js"></script>
    <link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.24.custom.css" type="text/css"/>
    <script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.24.custom.min.js"></script>
    <script>
        var phpSubmission = <?php echo "'" . $submission . "'";?>;
        var phpContractId = <?php echo $contract_id;?>;
        var phpTrId = <?php echo $tr_id;?>;
        var phpTemplate = <?php echo $template;?>;
        var phpDBName = <?php echo "'" . DB_NAME . "'"; ?>;
        var phpHref = <?php echo "'" . $_SESSION['bc']->getPrevious() . "';" ?>;
    </script>
    <script src="/js/ilr2021.js?n=<?php echo time(); ?>"></script>
</head>

<body onload='$("#progress").hide();'>
<div class="banner">
    <table border="0" cellspacing="0" cellpadding="0" height="100%" width="100%">
        <tr class="head">
            <td valign="bottom">
                <?php if ($template != 1) { ?>
                <?php echo $vo->GivenNames . ' ' . $vo->FamilyName; ?> 2021/22 ILR
                <?php } else { ?>
                ILR Template
                <?php }  ?>
            </td>
            <td valign="bottom" align="right" class="Timestamp"></td>
        </tr>
    </table>
</div>

<div class="button_bar">
    <table border="0" cellspacing="0" cellpadding="0" height="100%" width="100%">
        <tr>
            <td valign="top" align="left" class="left">
                <div class="button_wrap">
                    <?php
                    if ($template != 1) {
                        ?>
                        <div class="button_header ui-icon-circle-close" id="b3"
                             onclick="confirm_on_cancel();"><img src="../images/close_icon.gif" height="15" width="15" /> Cancel
                        </div>
                        <?php } ?>

                    <?php if ($_SESSION['user']->isAdmin() || ($_SESSION['user']->type == User::TYPE_MANAGER && DB_NAME != 'am_reed_demo' && DB_NAME != "am_reed")) { ?>
                    <div class="button_header" id="b1" onclick="return save();"><img src="../images/save.png" height="15" width="15" /> Save</div>
                    <?php
                }

                    if ($template != 1) {
                        ?>

                        <?php if (DB_NAME == "am_ray_recruit") { ?>
                            <div class="button_header" id="b2" onclick="return validation();"><img src="../images/validate.png" height="15" width="15" /> SFA Validation</div>
                            <div class="button_header" id="b6" onclick="showHideBlock('validation_questions');"><img src="../images/validate.png" height="15" width="15" /> Internal
                                Validation
                            </div>
                            <?php } else { ?>
                            <div class="button_header" id="b2" onclick="return validation();"><img src="../images/validate.png" height="15" width="15" /> Validate</div>
                            <?php } ?>
                        <!--					<div class="button_header" id="b3" onclick="window.location.href='<?php /*echo $_SESSION['bc']->getPrevious();*/?>';">Cancel</div>
-->
                        <div class="button_header" id="b4" onclick="PDF();"><img src="../images/pdf_icon.png" height="15" width="15" /> PDF</div>
                        <div class="button_header" id="b5" onclick="if(prompt('Password','')=='pscd2017')changeL03();"><img src='../images/edit.png' height='15' width='15' /> Change LRN</div>

                        <?php } ?>

                    <?php if ($_SESSION['user']->isAdmin() || (DB_NAME == "am_lead" && $_SESSION['user']->type == User::TYPE_MANAGER)) { ?>
                    <div class="button_header" onclick="if(prompt('Password','')=='pscd2017')changeDates();"><img src='../images/calendar-icon.gif' height='15' width='15' /> Change Dates</div>
                    <div class="button_header addTab" id="addaim"><img src="../images/plus.png" height="15" width="15" /> Add Aim</div>
                    <?php
                }

                    if ($is_active == 1)
                        echo "<div class='button_header'><table><tr><td valign='top'><img src='../images/active.png' height='12' width='12' /></td><td valign='top'><input type='checkbox' id='active' checked /></td><td valign='top'>Active</td></tr></table></div>";
                    else
                        echo "<div class='button_header'><table><tr><td valign='top'><img src='../images/active.png' height='12' width='12' /></td><td valign='top'><input type='checkbox' id='active' /></td><td valign='top'>Active</td></tr></table></div>";

                    ?>
                </div>
            </td>
            <td valign="top" align="right" class="right"><span class="button_start"></span>
                <img src="/images/file.png" onclick="window.open('https://www.gov.uk/government/uploads/system/uploads/attachment_data/file/417028/ILR_Specification_2015-16_Appendix_A_Mar2015_v1.pdf')" title="ILR 2015-16 Submission Timetable" height="25px" width="25px"/>
                <img src="/images/printer_button.gif" onclick="window.print()" title="Print-friendly view"/>
                <img src="/images/refresh_button.gif" onclick="window.location.reload(false);"
                     title="Refresh view (see the latest changes from other users)"/>
            </td>
        </tr>
    </table>
</div>



<?php $_SESSION['bc']->render($link); ?>
<div class="loading-gif" id="progress"><img src="../images/progress-animations/loading51.gif"></div>
<span id="validating_ilr" style="display: none;">
	<img src="/images/progress-animations/validating_ilr.gif" alt="validating ilr ..." />
</span>
<br>
<div id='report'
     style="border: 1px solid #B9B9B9; -moz-border-radius: 5px; background-color: #F3FAE5;	color:#3E3E3E; padding: 10px; margin: 0px 10px 20px 10px;display: None;border-radius: 15px;">
    <p class='heading'> Validation Report </p>
</div>

<?php if (DB_NAME == "am_ray_recruit") { ?>
<div id='validation_questions'
     style="border: 1px solid #B9B9B9; -moz-border-radius: 5px; background-color: #F3FAE5;	color:#3E3E3E; padding: 10px; margin: 0px 10px 20px 10px;display: None;border-radius: 15px;">
    <p class='heading'> Internal Validation</p>
    <?php
    if (isset($internal_validation_questions) && count($internal_validation_questions) > 0) {
        echo '<fieldset>';
        echo '<legend>Questions</legend>';
        echo '<table id="tbl_validation_questions" border="0" cellspacing="4" cellpadding="4">';
        echo '<col width="500" />';
        $q_index = 1;
        $yes_no_dropdown = array(array(0, '', ''), array(1, 'Yes', ''), array(2, 'No', ''), array(3, 'Not Known', ''));
        foreach ($internal_validation_questions AS $question) {
            echo "<tr><td><strong>" . $question['description'] . "</strong></td><td>" . HTML::select('q_' . $question['id'], $yes_no_dropdown, $question['q_reply']) . "</td></tr>";
            $q_index++;
        }
        echo '<tr><td colspan="2"><span class="button" onclick="internal_validation();">Save</span></td></tr>';
        echo '</table></fieldset>';
    }

    ?>
</div>
    <?php } ?>

<form name="ilr" id="ilr" class="formular" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<div id="tabbed-nav" class="z-tabs-loading" <?php echo $default_tab;?>>
<ul>
    <li><a>Learner</a></li>
    <li><a>Learning Delivery</a></li>
    <li><a>Employment Status</a></li>
    <li><a>Audit Trail</a></li>
</ul>
<div>
<div>
<div id="tabbed-nav2">
<ul>
    <li><a>Learner Information</a></li>
    <li><a>LLDD and Learner FAM</a></li>
    <li><a>Learner Provider Specified Monitoring</a></li>
    <li><a>Learner HE Information</a></li>
</ul>
<div>
<div>
    <fieldset>
        <legend>Basic Information</legend>
        <table border="0" cellspacing="4" cellpadding="4" style="float: left;">
            <tr>
                <?php
                $this->dynamic_field_display('LearnRefNumber', "<input class='compulsory validate[required]' disabled type='text' value='" . $vo->LearnRefNumber . "' style='' id='LearnRefNumber' name='LearnRefNumber' maxlength=12 size=12 onKeyPress='return validLearnerReference(this, event)'>");
                $this->dynamic_field_display('PrevLearnRefNumber', "<input class='optional'  type='text' value='" . $vo->PrevLearnRefNumber . "' style='' id='PrevLearnRefNumber' name='PrevLearnRefNumber' maxlength=12 size=12 onKeyPress='return validLearnerReference(this, event)'>");
                ?>
            </tr>

            <tr>
                <?php
                $this->dynamic_field_display('PrevUKPRN', "<input class='optional' type='text' value='" . $vo->PrevUKPRN . "' style='' id='PrevUKPRN' name='PrevUKPRN' maxlength=12 size=12 onKeyPress='return numbersonly(this, event)'>");
                $this->dynamic_field_display('ULN', "<input class='compulsory validate[required]' type='text' value='" . trim($vo->ULN) . "' style='' id='ULN' name='ULN' maxlength=12 size=12 onKeyPress='return numbersonly(this, event)'>");
                ?>
            </tr>

            <tr>
                <?php
                $this->dynamic_field_display('PMUKPRN', "<input class='optional' type='text' value='" . $vo->PMUKPRN . "' style='' id='PMUKPRN' name='PMUKPRN' maxlength=8 size=8 onKeyPress='return numbersonly(this, event)'>");
                $this->dynamic_field_display('CampId', "<input class='optional' type='text' value='" . $vo->CampId . "' style='' id='CampId' name='CampId' maxlength=8 size=8>");
                ?>
            </tr>

            <tr>
                <?php
                $this->dynamic_field_display('FamilyName', "<input class='compulsory validate[required, custom[onlyLetterSp] maxSize[20]]' type=text value='" . htmlspecialchars((string)$vo->FamilyName, ENT_QUOTES) . "' id='FamilyName' name='FamilyName' maxlength=20 size=30 onKeyPress='return validName(this, event)'>");
                $this->dynamic_field_display('GivenNames', "<input class='compulsory validate[required, custom[onlyLetterSp] maxSize[40]]' type='text' value='" . htmlspecialchars((string)$vo->GivenNames, ENT_QUOTES) . "' id='GivenNames' name='GivenNames' maxlength=40 size=40 onKeyPress='return validName(this, event)'>");
                ?>
            </tr>

            <tr>
                <?php
                if ($vo->DateOfBirth != '00000000' && $vo->DateOfBirth != '' && $vo->DateOfBirth != '00/00/0000') {
                    $this->dynamic_field_display('DateOfBirth', HTML::datebox('DateOfBirth', Date::toShort($vo->DateOfBirth)));
                } else {
                    $this->dynamic_field_display('DateOfBirth', HTML::datebox('DateOfBirth', ''));
                }
                if ($vo->Sex == 'M') {
                    $male = "checked";
                    $female = "";
                }
                elseif ($vo->Sex == 'F') {
                    $female = "checked";
                    $male = "";
                } else {
                    $female = "";
                    $male = "";
                }
                $this->dynamic_field_display('Ethnicity', HTML::select('Ethnicity', $Ethnicity_dropdown, $vo->Ethnicity, true, true));
                ?>
            </tr>

            <tr>
                <?php
                $this->dynamic_field_display('Sex', "<table><tr><td><input type='Radio' name='Sex' value='M' " . $male . " /></td><td>Male</td><td><input type='Radio' name='Sex' value='F' " . $female . " /></td><td>Female</td></tr></table>");
                if ($funding_type != "ASL")
                    $this->dynamic_field_display('NINumber', "<input class='compulsory validate[required]' type='text' value='" . $vo->NINumber . "' style='' id='NINumber' name='NINumber' maxlength=9 size=20>");
                ?>
            </tr>

            <tr>
                <?php $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine1');
                $add1 = (empty($xpath)) ? '' : (string)$xpath[0];
                $this->dynamic_field_display('AddLine1', "<input class='compulsory validate[required]' type='text' value='" . htmlspecialchars((string)$add1, ENT_QUOTES) . "' style='' id='AddLine1' name='AddLine1' maxlength=30 size=28 onKeyPress='return validAddress(this, event)'>");
//echo "<input class='compulsory validate[required]' type='text' value='" . $add1 . "' style='' id='AddLine1' name='AddLine1' maxlength=30 size=28 onKeyPress='return validAddress(this, event)'>";
                ?>

                <?php $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine2');
                $add2 = (empty($xpath)) ? '' : (string)$xpath[0];
                $this->dynamic_field_display('AddLine2', "<input class='optional' type='text' value='" . $add2 . "' style='' id='AddLine2' name='AddLine2' maxlength=30 size=35 onKeyPress='return validAddress(this, event)'>");
                ?>
            </tr>

            <tr>
                <?php $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine3');
                $add3 = (empty($xpath)) ? '' : (string)$xpath[0];
                $this->dynamic_field_display('AddLine3', "<input class='optional' type='text' value='" . $add3 . "' style='' id='AddLine3' name='AddLine3' maxlength=30 size=30 onKeyPress='return validAddress(this, event)'>");
                ?>

                <?php $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine4');
                $add4 = (empty($xpath)) ? '' : (string)$xpath[0];
                $this->dynamic_field_display('AddLine4', "<input class='optional' type='text' value='" . $add4 . "' style='' id='AddLine4' name='AddLine4' maxlength=30 size=35 onKeyPress='return validAddress(this, event)'>");
                ?>
            </tr>

            <?php
            $cp = '';
            $ppe = '';
            foreach($vo->LearnerContact AS $LearnerContact)
            {
                if($LearnerContact->LocType->__toString() == "2" && $LearnerContact->ContType->__toString() == "2")
                {
                    $cp = $LearnerContact->Postcode->__toString();
                }
                if($LearnerContact->LocType->__toString() == "2" && $LearnerContact->ContType->__toString() == "1")
                {
                    $ppe = $LearnerContact->Postcode->__toString();
                }
            }
            ?>
            <tr>
                <?php $xpath = $vo->xpath("/Learner/LearnerContact[ContType='2' and LocType='2']/PostCode");
                if($cp == '')
                    $cp = (empty($xpath)) ? '' : $xpath[0];
                $this->dynamic_field_display('CurrentPostcode', "<input class='compulsory validate[required]' type='text' value='" . $cp . "' style='' id='CurrentPostcode' name='CurrentPostcode' maxlength=8 size=8>");
                ?>

                <?php $xpath = $vo->xpath("/Learner/LearnerContact[ContType='1' and LocType='2']/PostCode");
                if($ppe == '')
                    $ppe = (empty($xpath)) ? '' : $xpath[0];
                $this->dynamic_field_display('PostcodePriorEnrolment', "<input class='compulsory validate[required]' type='text' value='" . $ppe . "' style='background-color: white' id='PostcodePriorEnrolment' name='PostcodePriorEnrolment' maxlength=8 size=8>");
                ?>
            </tr>

            <tr>
                <?php $xpath = $vo->xpath('/Learner/LearnerContact/TelNumber');
                $tel = (empty($xpath)) ? '' : $xpath[0];
                $this->dynamic_field_display('TelNumber', "<input class='optional' type='text' value='" . $tel . "' style='' id='TelNumber' name='TelNumber' maxlength=15 size=15 onKeyPress='return numbersonly(this, event)'>");
                ?>
                <?php $xpath = $vo->xpath("/Learner/LearnerContact[ContType='2' and LocType='4']/Email");
                $email = (empty($xpath)) ? '' : $xpath[0];
                $this->dynamic_field_display('Email', "<input class='optional' type='text' value='" . $email . "' style='' id='Email' name='Email' maxlength=100 size=30>");
                ?>
            </tr>

            <tr><td colspan="2">
                <fieldset>
                    <legend>Tick any of the following boxes if Learner does not wish to be contacted:</legend>
                    <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                            <td colspan="2" class="tooltip" title="To take into account learners' wishes about the use of their data. The data held in this field is used by the FE Choices.">
                                <?php
                                $RUI_dropdown = array(
                                    array('1', 'About courses or learning opportunities'),
                                    array('2', 'For surveys and research'),
                                    array('4', 'Learner has suffered severe illness during the programme or other circumstances'),
                                    array('5', 'Learner is not to be contacted - learner has died')
                                );
                                $selected_rui = DAO::getSingleValue($link, "SELECT extractvalue(ilr, '/Learner/ContactPreference[ContPrefType=\'RUI\']/ContPrefCode') FROM ilr WHERE tr_id = '{$tr_id}' AND submission = '{$submission}' AND contract_id = {$contract_id};");
                                $selected_rui = explode(" ", $selected_rui);
                                echo HTML::checkboxGrid('RUI', $RUI_dropdown, $selected_rui);
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" class="tooltip" title="To take into account learners' wishes about contact methods for surveys, research and learning opportunities.">
                                <?php
                                $PMC_dropdown = array(
                                    array('1', 'By post'),
                                    array('2', 'By phone'),
                                    array('3', 'By email')
                                );
                                $selected_pmc = DAO::getSingleValue($link, "SELECT extractvalue(ilr, '/Learner/ContactPreference[ContPrefType=\'PMC\']/ContPrefCode') FROM ilr WHERE tr_id = '{$tr_id}' AND submission = '{$submission}' AND contract_id = {$contract_id};");
                                $selected_pmc = explode(" ", $selected_pmc);
                                echo HTML::checkboxGrid('PMC', $PMC_dropdown, $selected_pmc);
                                ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td> </tr>
        </table>
    </fieldset>
</div>
<div>
<fieldset>
    <legend>LLDD & Health Problems and Learner FAM</legend>
    <table border="0" cellspacing="4" cellpadding="4">
        <col width="150"/>
        <col/>
        <tr>
            <?php
            $this->dynamic_field_display('LLDDHealthProb', HTML::select('LLDDHealthProb', $LLDDHealthProb_dropdown, $vo->LLDDHealthProb, true, true), 'colspan="2"');
            ?>
        </tr>
        <tr><td colspan="2">
            <fieldset class="innerFieldset">
                <legend>LLDD and health problem type and code</legend>
                <table>
                    <!--<tr>-->
                    <?php
                    $LLDDCat_dropdown = array(
                        array('1', '1 Emotional/behavioural difficulties'),
                        array('2', '2 Multiple disabilities'),
                        array('3', '3 Multiple learning difficulties'),
                        array('4', '4 Vision impairment'),
                        array('5', '5 Hearing impairment'),
                        array('6', '6 Disability affecting mobility'),
                        array('7', '7 Profound complex disabilities'),
                        array('8', '8 Social and emotional difficulties'),
                        array('9', '9 Mental health difficulty'),
                        array('10', '10 Moderate learning difficulty'),
                        array('11', '11 Severe learning difficulty'),
                        array('12', '12 Dyslexia'),
                        array('13', '13 Dyscalculia'),
                        array('14', '14 Autism spectrum disorder'),
                        array('15', '15 Asperger\'s syndrome'),
                        array('16', '16 Temporary disability after illness (for example post-viral) or accident'),
                        array('17', '17 Speech, Language and Communication Needs'),
                        array('93', '93 Other physical disability'),
                        array('94', '94 Other specific learning difficulty (e.g. Dyspraxia)'),
                        array('95', '95 Other medical condition (for example epilepsy, asthma, diabetes)'),
                        array('96', '96 Other learning difficulty'),
                        array('97', '97 Other disability'),
                        array('98', '98 Prefer not to say'),
                        array('99', '99 Not provided')
                    );

                    $index = 1;
                    foreach($vo->LLDDandHealthProblem as $LLDD)
                    {
                        $id = "LLDDCat" . $index;
                        if($LLDD->LLDDCat!='')
                        {
                            echo "<tr>";
                            $this->dynamic_field_display('LLDDCat', HTML::select($id, $LLDDCat_dropdown, $LLDD->LLDDCat, true, true));
                            if($index==1)
                            {
                                if ($LLDD->PrimaryLLDD == '1')
                                    echo '<td colspan="2"><table><tr><td><input class = "tooltip" title = "To identify the primary learning difficulty, disability or health problem for reporting purposes and to align with data collected in the school census" type="checkbox" checked name="PrimaryLLDD" id="PrimaryLLDD" /></td><td>The learner\'s primary learning difficulty, disability or health problem</td></tr></table></td>';
                                else
                                    echo '<td colspan="2"><table><tr><td><input class = "tooltip" title = "To identify the primary learning difficulty, disability or health problem for reporting purposes and to align with data collected in the school census" type="checkbox" name="PrimaryLLDD" id="PrimaryLLDD" /></td><td>The learner\'s primary learning difficulty, disability or health problem</td></tr></table></td>';
                            }
                            echo "</tr>";
                            $index++;
                        }
                    }
                    $id = "LLDDCat" . $index;
                    echo '<tr><td><span class="button" <a onclick="$(\'tr.new_lldd_stat\').toggle();" >Add a new LLDD record</a></td><td>&nbsp;</td></tr>';
                    echo '<tr style="display:none;" class="new_lldd_stat">';
                    $this->dynamic_field_display('LLDDCat', HTML::select($id, $LLDDCat_dropdown, "", true, true));
                    if($index == 1)
                        echo '<td colspan="2"><table><tr><td><input class = "tooltip" title = "To identify the primary learning difficulty, disability or health problem for reporting purposes and to align with data collected in the school census" type="checkbox" name="PrimaryLLDD" id="PrimaryLLDD" /></td><td>The learner\'s primary learning difficulty, disability or health problem</td></tr></table></td>';

                    echo '</tr>';

                    ?>
                    <!--</tr>-->
                </table>
            </fieldset>
        </td></tr>
        <tr><td colspan="2">
            <fieldset class="innerFieldset"><legend>Does the learner have any of the following (tick those that apply)</legend>
                <table border="0" cellspacing="4" cellpadding="4">
                    <tr>
                        <td colspan="2" class="tooltip" title="Education Health Care plan: To indicate if the learner has an Education Health Care Plan.<br>
																							High needs students: To indicate if a local authority has paid element 3 'top-up' funding for an EFA funded student whose agreed support costs are greater than 6,000.<br>
																							Disabled student allowance: To indicate if the learner has an Education Health Care (EHC) plan.">
                            <?php
                            $LLDDFAM_dropdown = array(
                                array('EHC', 'Education Health Care plan'),
                                array('DLA', 'Disabled student allowance'),
                                array('SEN', 'Special educational needs'),
                                array('HNS', 'High needs students')
                            );
                            $sql = <<<SQL
SELECT
extractvalue(ilr, '/Learner/LearnerFAM[LearnFAMType=\'EHC\']/LearnFAMCode') AS EHC,
extractvalue(ilr, '/Learner/LearnerFAM[LearnFAMType=\'DLA\']/LearnFAMCode') AS DLA,
extractvalue(ilr, '/Learner/LearnerFAM[LearnFAMType=\'SEN\']/LearnFAMCode') AS SEN,
extractvalue(ilr, '/Learner/LearnerFAM[LearnFAMType=\'HNS\']/LearnFAMCode') AS HNS
FROM ilr WHERE tr_id = '$tr_id' AND submission = '$submission' AND contract_id = $contract_id;
SQL;
                            $result_set = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
                            $selected_lldm_fam = array();
                            if(isset($result_set[0]['EHC']) && $result_set[0]['EHC'] == '1')
                                $selected_lldm_fam[] = 'EHC';
                            if(isset($result_set[0]['DLA']) && $result_set[0]['DLA'] == '1')
                                $selected_lldm_fam[] = 'DLA';
                            if(isset($result_set[0]['SEN']) && $result_set[0]['SEN'] == '1')
                                $selected_lldm_fam[] = 'SEN';
                            if(isset($result_set[0]['HNS']) && $result_set[0]['HNS'] == '1')
                                $selected_lldm_fam[] = 'HNS';
                            echo HTML::checkboxGrid('LLDDFAMS', $LLDDFAM_dropdown, $selected_lldm_fam);
                            ?>
                        </td>
                    </tr>
                </table>
                <table><tr><?php $this->dynamic_field_display('ALSCost', "<input class='optional' type='text' value='" . $vo->ALSCost . "' id='ALSCost' name='ALSCost' maxlength=40 size=40 onKeyPress='return numbersonly(this, event)'>"); ?></tr></table>
            </fieldset>
        </td></tr>

        <tr><td colspan="2">
            <fieldset class="innerFieldset tooltip" title="Eligibility for EFA disadvantage funding">
                <legend>Eligibility for EFA disadvantage funding</legend>
                <table border="0" cellspacing="4" cellpadding="4">
                    <tr>
                        <td>
                            <?php
                            $EDF_dropdown = array(array('1', '1 Learner has not achieved a maths GCSE (at grade A*-C) by the end of year 11'), array('2', '2 Learner has not achieved an English GCSE (at grade A*-C) by the end of year 11'));
                            $selected_edf = DAO::getSingleValue($link, "SELECT extractvalue(ilr, '/Learner/LearnerFAM[LearnFAMType=\'EDF\']/LearnFAMCode') FROM ilr WHERE tr_id = '{$tr_id}' AND submission = '{$submission}' AND contract_id = {$contract_id};");
                            $selected_edf = explode(" ", $selected_edf);
                            $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='EDF']/LearnFAMCode");
                            echo HTML::checkboxGrid('EDF', $EDF_dropdown, $selected_edf);
                            ?>
            </fieldset>
        </td></tr></table>
    <tr><td colspan="2">
        <fieldset class="innerFieldset tooltip" title="Condition of funding">
            <legend>Condition of funding</legend>
            <table border="0" cellspacing="4" cellpadding="4">
                <tr>
                    <td>
                        <?php
                        $MCF_dropdown = array(array('1', '1 Learner is exempt from GCSE maths condition of funding due to a learning difficulty'), array('2', '2 Learner is exempt from GCSE maths condition of funding as they hold an equivalent overseas qualification'),array('3', '3 Learner has met the GCSE maths condition of funding as they hold an approved equivalent UK qualification'),array('4', '4 Learner has met the GCSE maths condition of funding by undertaking or completing a valid maths GCSE or equivalent qualification at another institution'),array('5', '5 Learner holds a pass grade for functional skills level 2 in maths'),array('6', '6 Unassigned'));
                        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='MCF']/LearnFAMCode");
                        $LearnerFAMECF = '';
                        $LearnerFAMMCF = '';
                        foreach($vo->LearnerFAM AS $_lFAM)
                        {
                            if($_lFAM->LearnFAMType == 'ECF')
                                $LearnerFAMECF = $_lFAM->LearnFAMCode->__toString();
                            if($_lFAM->LearnFAMType == 'MCF')
                                $LearnerFAMMCF = $_lFAM->LearnFAMCode->__toString();
                        }
                        $mcf = (empty($xpath[0])) ? '' : (string)$xpath[0];
                        // $this->dynamic_field_display('MCF', HTML::select('MCF', $MCF_dropdown, $mcf, true, false));
                        $this->dynamic_field_display('MCF', HTML::select('MCF', $MCF_dropdown, $LearnerFAMMCF, true, false));
                        $ECF_dropdown = array(array('1', '1 Learner is exempt from GCSE English condition of funding due to a learning difficulty'), array('2', '2 Learner is exempt from GCSE English condition of funding as they hold an equivalent overseas qualification'),array('3', '3 Learner has met the GCSE English condition of funding as they hold an approved equivalent UK qualification'),array('4', '4 Learner has met the GCSE English condition of funding by undertaking or completing a valid English GCSE or equivalent qualification at another institution'),array('5', '5 Learner holds a pass grade for functional skills level 2 English'),array('6', '6 Unassigned'));
                        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='ECF']/LearnFAMCode");
                        $ecf = (empty($xpath[0])) ? '' : (string)$xpath[0];
                        // $this->dynamic_field_display('ECF', HTML::select('ECF', $ECF_dropdown, $ecf, true, false));
                        $this->dynamic_field_display('ECF', HTML::select('ECF', $ECF_dropdown, $LearnerFAMECF, true, false));
                        ?>
                </tr>
            </table>
        </fieldset>
    </td></tr>
    </table>
</fieldset>
<fieldset>
    <legend>Additional Information</legend>
    <table border="0" cellspacing="4" cellpadding="4">
        <col width="150"/>
        <col/>
        <?php
        echo '<table>';
            $index = 0;
            foreach ($vo->PriorAttain as $ldf)
            {
                $index++;
                $prior = "Prior_" . $index;
                $from = "PriorDate_" . $index;
                echo '<tr>';
                $this->dynamic_field_display('PriorAttain', HTML::select($prior, $PriorAttain_dropdown2, $ldf->PriorLevel, true, false));
                $this->dynamic_field_display('DateLevelApp', HTML::datebox($from, $ldf->DateLevelApp, false, false));
                echo '</tr>';
            }
            $index++;
            $prior = "Prior_" . $index;
            $from = "PriorDate_" . $index;
            echo '<tr>';
            $this->dynamic_field_display('PriorAttain', HTML::select($prior, $PriorAttain_dropdown2, '', true, false));
            $this->dynamic_field_display('DateLevelApp', HTML::datebox($from, '', false, false));
            echo '</tr></table>';
            ?>
        <table><tr>
            <?php
            //$this->dynamic_field_display('PriorAttain', HTML::select('PriorAttain', $PriorAttain_dropdown, $vo->PriorAttain, true, true));
            $this->dynamic_field_display('PlanLearnHours', "<input class='optional validate[required]' type='text' value='" . $vo->PlanLearnHours . "' id='PlanLearnHours' name='PlanLearnHours' maxlength=40 size=40 onKeyPress='return numbersonly(this, event)'>");
            $this->dynamic_field_display('PlanEEPHours', "<input class='optional validate[required]' type='text' value='" . $vo->PlanEEPHours . "' id='PlanEEPHours' name='PlanEEPHours' maxlength=40 size=40 onKeyPress='return numbersonly(this, event)'>");
            ?>
        </tr>
        <tr>
            <?php
            $xpath = $vo->xpath("/Learner/Accom");
            $accom = (empty($xpath[0])) ? '' : $xpath[0];
            if ($accom == '5')
                echo '<td colspan="2"><table><tr><td><input class = "tooltip" title = "To allocate residential funding for EFA learners" type="checkbox" checked name="Accom" id="Accom" /></td><td>Check box if the learner is living away from home in accommodation owned or managed by the provider.</td></tr></table></td>';
            else
                echo '<td colspan="2"><table><tr><td><input class = "tooltip" title = "To allocate residential funding for EFA learners" type="checkbox" name="Accom" id="Accom" /></td><td>Check box if the learner is living away from home in accommodation owned or managed by the provider.</td></tr></table></td>';
            ?>
        </tr>

        <tr><td colspan="2">
            <fieldset class="innerFieldset tooltip" title="To identify and report on learners that are in receipt of different types of learner support and to assist in the evaluation of its effectiveness.">
                <legend>Learner Support Reason (select up to 4 that apply)</legend>
                <table border="0" cellspacing="4" cellpadding="4">
                    <tr>
                        <td>
                            <?php
                            $selected_lsr = DAO::getSingleValue($link, "SELECT extractvalue(ilr, '/Learner/LearnerFAM[LearnFAMType=\'LSR\']/LearnFAMCode') FROM ilr WHERE tr_id = '{$tr_id}' AND submission = '{$submission}' AND contract_id = {$contract_id};");
                            $selected_lsr = explode(" ", $selected_lsr);

                            $LSR_dropdown = array(array('36', '36 Care to Learn'), array('55', '55 16-19 Bursary Fund - learner is a member of a vulnerable group'), array('56', '56 16-19 Bursary Fund - learner has been awarded a discretionary bursary'), array('57', '57 Residential support'), array('58', '58 19+ Hardship (SFA or Advanced Learner Loan funded learners only)'), array('59', '59 20+ Childcare (SFA or Advanced Learner Loan funded learners only)'), array('60', '60 19+ Residential Access Fund (SFA or Advanced Learner Loan funded learners only)'), array('61', '61 ESF funded learner receiving childcare support'),array('62', '62 Unassigned'),array('63', '63 Unassigned'),array('64', '64 Unassigned'),array('65', '65 Unassigned'));
                            $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");

                            echo HTML::checkboxGrid('LSR', $LSR_dropdown, $selected_lsr);
                            ?>
                        </td></tr></table>
            </fieldset>
        </tr>

        <tr><td colspan="2">
            <fieldset class="innerFieldset tooltip" title="To identify any additional monitoring characteristics required for the learner.">
                <legend>National learner monitoring</legend>
                <?php
                $NLM_dropdown = array(
                    array('17', '17 Learner migrated as part of provider merger'),
                    array('18', '18 Learner moved as a result of Minimum Contract Level'),
                    array('21', '21 Learner in receipt of 16-19 tuition fund'),
                    array('22', '22 Learner repeating up to one full final year of 16-19 funded provision'),
                    array('23', '23 Unassigned'),
                    array('24', '24 Unassigned'),
                    array('25', '25 Unassigned')
                );
                $selected_nlm = DAO::getSingleValue($link, "SELECT extractvalue(ilr, '/Learner/LearnerFAM[LearnFAMType=\'NLM\']/LearnFAMCode') FROM ilr WHERE tr_id = '{$tr_id}' AND submission = '{$submission}' AND contract_id = {$contract_id};");
                $selected_nlm = explode(" ", $selected_nlm);
                echo HTML::checkboxGrid('NLM', $NLM_dropdown, $selected_nlm);
                ?>
            </fieldset>
        </td></tr>

        <tr><td colspan="2">
            <fieldset class="innerFieldset tooltip" title="English and Maths Grades">
                <legend>English and Maths Grades</legend>
                <table border="0" cellspacing="4" cellpadding="4">
                    <tr>
                        <td>
                            <?php
                            $EngMathGrade_dropdown = array(array('A', 'A'),array('A*', 'A*'),array('A*A', 'A*A'),array('A*A*', 'A*A*'),array('AA', 'AA'),array('AB', 'AB'),array('B', 'B'),array('BB', 'BB'),array('BC', 'BC'),array('C', 'C'),array('CC', 'CC'),array('CD', 'CD'),array('D', 'D'),array('DD', 'DD'),array('DE', 'DE'),array('E', 'E'),array('EE', 'EE'),array('EF', 'EF'),array('F', 'F'),array('FF', 'FF'),array('FG', 'FG'),array('G', 'G'),array('GG', 'GG'),array('N', 'N'),array('U', 'U'),array('1', '1'),array('2', '2'),array('3', '3'),array('4', '4'),array('5', '5'),array('6', '6'),array('7', '7'),array('8', '8'),array('9', '9'),array('NONE', 'NONE'));
                            $this->dynamic_field_display('EngGrade', HTML::select('EngGrade', $EngMathGrade_dropdown, $vo->EngGrade, true, true));
                            $this->dynamic_field_display('MathGrade', HTML::select('MathGrade', $EngMathGrade_dropdown, $vo->MathGrade, true, true));
                            ?>
                        </td></tr></table>
            </fieldset>
        </tr>

        <tr>
            <td colspan="2">
                <fieldset class="innerFieldset tooltip" title="Learner eligibility for Pupil Premium Funding">
                    <legend>Free Meals and Pupil premium funding eligibility</legend>
                    <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                            <td colspan="2" class="tooltip">
                                <?php
                                $FME_dropdown = array(array('1', '1 14-15 year old learner is eligible for free meals'), array('2', '2 16-19 year old learner is eligible for and in receipt of free meals'));
                                $LearnerFAMFME = '';
                                foreach($vo->LearnerFAM AS $_lFAM)
                                {
                                    if($_lFAM->LearnFAMType == 'FME')
                                        $LearnerFAMFME = $_lFAM->LearnFAMCode->__toString();
                                }
                                $this->dynamic_field_display('FME', HTML::select('FME', $FME_dropdown, $LearnerFAMFME, true, false));
                                ?>
                            </td>
                        </tr>
                    </table>
                    <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                            <td colspan="2" class="tooltip">
                                <?php
                                $PPE_dropdown = array(
                                    array('1', '1 Learner is eligible for Service Child premium'),
                                    array('2', '2 Learner is eligible for Adopted from Care premium'),
                                    array('3', '3 Unassigned'),
                                    array('4', '4 Unassigned'),
                                    array('5', '5 Unassigned')
                                );
                                $selected_ppe = DAO::getSingleValue($link, "SELECT extractvalue(ilr, '/Learner/LearnerFAM[LearnFAMType=\'PPE\']/LearnFAMCode') FROM ilr WHERE tr_id = '{$tr_id}' AND submission = '{$submission}' AND contract_id = {$contract_id};");
                                $selected_ppe = explode(" ", $selected_ppe);
                                echo HTML::checkboxGrid('PPE', $PPE_dropdown, $selected_ppe);
                                ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
    </table>
</fieldset>
</div>
<div>
    <fieldset>
        <legend>&nbsp;Provider Specified Monitoring Information</legend>
        <table border="0" cellspacing="4" cellpadding="4">
            <col width="394"/>
            <col width="435"/>
            <tr>
                <?php
                $ProvSpecLearnMon1='';
                $ProvSpecLearnMon2='';
                foreach($vo->ProviderSpecLearnerMonitoring as $pslm)
                {
                    if($pslm->ProvSpecLearnMonOccur=='A')
                        $ProvSpecLearnMon1 = $pslm->ProvSpecLearnMon;
                    if($pslm->ProvSpecLearnMonOccur=='B')
                        $ProvSpecLearnMon2 = $pslm->ProvSpecLearnMon;
                }
                $this->dynamic_field_display('ProvSpecLearnMon', "<input class='optional' type='text' value='" . $ProvSpecLearnMon1 . "' style='' id='ProvSpecLearnMonA' name='ProvSpecLearnMonA' maxlength=20 size=30>");
                $this->dynamic_field_display('ProvSpecLearnMon', "<input class='optional' type='text' value='" . $ProvSpecLearnMon2 . "' style='' id='ProvSpecLearnMonB' name='ProvSpecLearnMonB' maxlength=20 size=30>");
                ?>
            </tr>

        </table>
    </fieldset>
</div>
<div>
    <?php
    /*
               * The Learning delivery HE entity must be returned for learning aims that meet the following criteria and the collection requirements for the field apply:
               a. Learning aims that are HEFCE funded, as indicated in the Learning Delivery Funding and
               Monitoring fields using code SOF1.
               b. Learning aims funded by the EFA that are level 4 or above on LARS
               c. Learning aims that are level 4 or above on LARS, are funded by Adult Skills Budget funding,
               code 35 in the Funding model field, and are not workplace learning (no Workplace learning
               indicator is returned in the Learning Delivery Funding and Monitoring fields).
               d. Learning aims that are level 4 or above on LARS and are not funded by the EFA or Skills
               Funding Agency, code 99 in the Funding model field.
               */
    $condition1 = false;
    $condition2 = false;
    $condition3 = false;
    $condition4 = false;
    $condition5 = false;
    foreach($vo->LearningDelivery as $delivery)
    {
        $hefce_sof='';
        $hefce_wpl='';
        foreach($delivery->LearningDeliveryFAM as $ldf)
        {
            if($ldf->LearnDelFAMType == 'SOF')
                $hefce_sof = $ldf->LearnDelFAMCode;
            if($ldf->LearnDelFAMType == 'WPL')
                $hefce_wpl = $ldf->LearnDelFAMCode;
        }
        $condition1 = $hefce_sof != 'undefined' && $hefce_sof == '1';
        $condition2 = $hefce_sof != 'undefined' && $hefce_sof == '107' && $delivery->ProgType >= 20;
        $condition3 = $hefce_wpl != 'undefined' && $hefce_wpl != '1' && $delivery->FundModel == 35 && $delivery->ProgType >= 20;
        $condition4 = $delivery->FundModel == 99 && $delivery->ProgType >= 20;
        $condition5 = DAO::getSingleValue($link, "SELECT EnglandFEHEStatus FROM lars201718.Core_LARS_LearningDelivery WHERE LearnAimRef = '$delivery->LearnAimRef';");
        if($condition1 || $condition2 || $condition3 || $condition4 || $condition5)
            break;
    }
    if($condition1 || $condition2 || $condition3 || $condition4 || $condition5)
    {
        $LearnerHE = $vo->LearnerHE[0];
        $UCASPERID = isset($LearnerHE->UCASPERID)?$LearnerHE->UCASPERID:'';
        $TTACCOM = isset($LearnerHE->TTACCOM)?$LearnerHE->TTACCOM:'';
        //Learner  HE
        echo '<fieldset>';
        echo '<legend>Learner HE</legend>';
        echo '<table border="0" cellspacing="4" cellpadding="4">';
        echo '<col width="150"/><col />';
        echo '<tr>';
        $this->dynamic_field_display('UCASPERID',"<input class='optional validate[required]' type='text' value='" . $UCASPERID . "' style='' id='UCASPERID' name='UCASPERID' maxlength=10 size=10 >");
        $this->dynamic_field_display('TTACCOM',HTML::select('TTACCOM', $TTACCOM_dropdown, $TTACCOM, true, false));
        echo '</tr>';
        echo '<tr>';
        $financial_support_type_1 = '';
        $financial_support_type_2 = '';
        $financial_support_type_3 = '';
        $financial_support_type_4 = '';
        if(isset($LearnerHE))
        {
            foreach($LearnerHE->LearnerHEFinancialSupport as $lhfs)
            {
                if($lhfs->FINTYPE=='1')
                    $financial_support_type_1 = $lhfs->FINAMOUNT;
                if($lhfs->FINTYPE=='2')
                    $financial_support_type_2 = $lhfs->FINAMOUNT;
                if($lhfs->FINTYPE=='3')
                    $financial_support_type_3 = $lhfs->FINAMOUNT;
                if($lhfs->FINTYPE=='4')
                    $financial_support_type_4 = $lhfs->FINAMOUNT;
            }
        }
        $this->dynamic_field_display('FINAMOUNT1',"<input class='optional validate[required] tooltip' type='text' value='" . $financial_support_type_1 . "' style='' id='FINAMOUNT1' name='FINAMOUNT1' maxlength=10 size=10 >");
        $this->dynamic_field_display('FINAMOUNT2',"<input class='optional validate[required] tooltip' type='text' value='" . $financial_support_type_2 . "' style='' id='FINAMOUNT2' name='FINAMOUNT2' maxlength=10 size=10 >");
        echo '</tr>';
        echo '</tr>';
        $this->dynamic_field_display('FINAMOUNT3',"<input class='optional validate[required] tooltip' type='text' value='" . $financial_support_type_3 . "' style='' id='FINAMOUNT3' name='FINAMOUNT3' maxlength=10 size=10 >");
        $this->dynamic_field_display('FINAMOUNT4',"<input class='optional validate[required] tooltip' type='text' value='" . $financial_support_type_4 . "' style='' id='FINAMOUNT4' name='FINAMOUNT4' maxlength=10 size=10 >");
        echo '</tr>';
        echo '</table>';
        echo '</fieldset>';
    }
    else
        echo 'Not Applicable.';
    ?>
</div>
</div>
</div> <!-- learner tab finished tabbed-nav2 -->
</div> <!-- the link div of learner tab (tabbed-nav) -->
<div>
<div id="tabbed-nav3"  class="z-content-pad">
<ul>
    <?php
    $tab = 1;
    foreach ($vo->LearningDelivery as $aim) {
        $tab++;
        $title = DAO::getSingleValue($link, "select internaltitle from qualifications where replace(id,'/','') = '$aim->LearnAimRef'");
        echo '<li  class="tooltip" title="' . $title . '"><a>' . $aim->LearnAimRef . '</a></li>';
    }
    ?>
</ul>
<div id="deliveriesTab">
<?php
$a = 0;
foreach ($vo->LearningDelivery as $delivery) {
    $a++;
    echo '<div id="tab' . $a . '" class="Unit">';
    echo '<span class="removeTab" style="	background-color: #ff9b2f; border-width: 2px; border-style: solid; border-color: #ffc281 #CC7C26 #CC7C26 #ffc281; font-size:8pt; color: white; margin: 0px 5px 0px 0px; padding: 1px 3px 1px 3px; cursor: pointer;">Remove Aim</span>';

    echo '<fieldset>';
    echo '<legend>Learning Information - (Aim Reference: ' . $delivery->LearnAimRef . ', Aim Type: ' . $delivery->AimType . ')</legend>';
    echo '<table class="ilr" border="0" cellspacing="4" cellpadding="4">';
    echo '<col width="150"/><col />';

    if(DB_NAME=='am_baltic' && $delivery->AimType!='1')
    {
        if ($delivery->Exclude == '1')
        {
            echo '<tr><td><table><tr><td><input type="checkbox" title="To exclude learing aim from ILR Batch file" checked name="Exclude" /></td><td>Exclude learing aim from ILR return</td></tr></table></td></tr>';
        }
        else
            echo '<tr><td><table><tr><td><input type="checkbox" title="To exclude learing aim from ILR Batch file" name="Exclude" /></td><td>Exclude learing aim from ILR return</td></tr></table></td></tr>';
    }

    echo '<tr>';
    $this->dynamic_field_display('AimType', HTML::select('AimType', $aimtype_dropdown, $delivery->AimType, true, true));
    $this->dynamic_field_display('LearnAimRef', "<input class='compulsory validate[required]' type='text' value='" . $delivery->LearnAimRef . "' style='' id='LearnAimRef' name='LearnAimRef' maxlength=8 size=8>");
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('LearnStartDate', HTML::datebox('LearnStartDate_' . $delivery->LearnAimRef, $delivery->LearnStartDate, true, true));
    $this->dynamic_field_display('LearnPlanEndDate', HTML::datebox('LearnPlanEndDate_' . $delivery->LearnAimRef, $delivery->LearnPlanEndDate, true, true));
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('OrigLearnStartDate', HTML::datebox('OrigLearnStartDate_' . $delivery->LearnAimRef, $delivery->OrigLearnStartDate, true, false));
    echo '</tr>';

    echo '<tr>';
    $FundModel_dropdown = array(
        array('10', '10 Community Learning'),
        array('25', '25 16-19 EFA'),
        array('35', '35 Adult Skills'),
        array('36', '36 Apprenticeships (from 1 May 2017)'),
        array('70', '70 ESF'),
        array('81', '81 Other SFA'),
        array('82', '82 Other EFA'),
        array('99', '99 Non-funded')
    );
    $this->dynamic_field_display('FundModel', HTML::select('FundModel', $FundModel_dropdown, $delivery->FundModel, true, true));

    $ProgType_dropdown = array(
        array('2', '2 Advanced Level Apprenticeship'),
        array('3', '3 Intermediate Level Apprenticeship'),
        array('20', '20 Higher Level Apprenticeship (Level 4)'),
        array('21', '21 Higher Level Apprenticeship (Level 5)'),
        array('22', '22 Higher Level Apprenticeship (Level 6)'),
        array('23', '23 Higher Level Apprenticeship (Level 7+)'),
        array('24', '24 Traineeship'),
        array('25', '25 Apprenticeship standard'),
        array('30', '30 T-level transition programme'),
        array('31', '31 T-level programme')
    );
    $this->dynamic_field_display('ProgType', HTML::select('ProgType', $ProgType_dropdown, $delivery->ProgType, true, false));
    echo '</tr>';

    echo '<tr>';
    if ($delivery->ProgType == '2')
        $this->dynamic_field_display('FworkCode', HTML::select('FworkCode', $FworkCode2_dropdown, $delivery->FworkCode, true, false));
    elseif ($delivery->ProgType == '3')
        $this->dynamic_field_display('FworkCode', HTML::select('FworkCode', $FworkCode3_dropdown, $delivery->FworkCode, true, false));
    elseif ($delivery->ProgType == '21')
        $this->dynamic_field_display('FworkCode', HTML::select('FworkCode', $FworkCode4_dropdown, $delivery->FworkCode, true, false));
    elseif ($delivery->ProgType == '20')
        $this->dynamic_field_display('FworkCode', HTML::select('FworkCode', $FworkCode5_dropdown, $delivery->FworkCode, true, false));
    else
        $this->dynamic_field_display('FworkCode', HTML::select('FworkCode', $FworkCode_dropdown, $delivery->FworkCode, true, false));
    if (!isset($delivery) || $delivery->FworkCode == '')
        $PwayCode_dropdown = DAO::getResultset($link, "SELECT DISTINCT PwayCode, LEFT(CONCAT(PwayCode, ' ', COALESCE(PathwayName,'')),50) ,NULL FROM lars201718.Core_LARS_Framework ORDER BY FworkCode, PwayCode;", DAO::FETCH_NUM);
    else
        $PwayCode_dropdown = DAO::getResultset($link, "SELECT DISTINCT PwayCode, LEFT(CONCAT(PwayCode, ' ', COALESCE(PathwayName,'')),50) ,NULL FROM lars201718.Core_LARS_Framework WHERE FworkCode = '$delivery->FworkCode' AND ProgType='$delivery->ProgType' ORDER BY FworkCode, PwayCode;", DAO::FETCH_NUM);
    $this->dynamic_field_display('PwayCode', HTML::select('PwayCode', $PwayCode_dropdown, $delivery->PwayCode, true, true));
    echo '</tr>';
    echo '<tr>';
    $this->dynamic_field_display('StdCode', HTML::select('StdCode', $StdCode_dropdown, $delivery->StdCode, true, false));
    echo '<td colspan="2">';
    echo '<table>';
    $res = '';
    $wpl = '';
    $fln = '';
    $adl = '';
    $sof = '';
    $ffi = '';
    $eef = '';
    $asl = '';
    $nsa = '';
    $pod = '';
    foreach($delivery->LearningDeliveryFAM as $ldf)
    {
        if($ldf->LearnDelFAMType == 'RES' and $ldf->LearnDelFAMCode!='undefined')
            $res = $ldf->LearnDelFAMCode;
        if($ldf->LearnDelFAMType == 'WPL' and $ldf->LearnDelFAMCode!='undefined')
            $wpl = $ldf->LearnDelFAMCode;
        if($ldf->LearnDelFAMType == 'FLN' and $ldf->LearnDelFAMCode!='undefined')
            $fln = $ldf->LearnDelFAMCode;
        if($ldf->LearnDelFAMType == 'ADL' and $ldf->LearnDelFAMCode!='undefined')
            $adl = $ldf->LearnDelFAMCode;
        if($ldf->LearnDelFAMType == 'SOF' and $ldf->LearnDelFAMCode!='undefined')
            $sof = $ldf->LearnDelFAMCode;
        if($ldf->LearnDelFAMType == 'FFI' and $ldf->LearnDelFAMCode!='undefined')
            $ffi = $ldf->LearnDelFAMCode;
        if($ldf->LearnDelFAMType == 'EEF' and $ldf->LearnDelFAMCode!='undefined')
            $eef = $ldf->LearnDelFAMCode;
        if($ldf->LearnDelFAMType == 'ASL' and $ldf->LearnDelFAMCode!='undefined')
            $asl = $ldf->LearnDelFAMCode;
        if($ldf->LearnDelFAMType == 'NSA' and $ldf->LearnDelFAMCode!='undefined')
            $nsa = $ldf->LearnDelFAMCode;
        if($ldf->LearnDelFAMType == 'POD' and $ldf->LearnDelFAMCode!='undefined')
            $pod = $ldf->LearnDelFAMCode;
    }
    if ($res == '1')
        echo '<tr><td><input class="tooltip" title="To identify whether the learner has restarted the learning aim." type="checkbox" checked name="RES" /></td><td>Is the aim a re-start?</td></tr>';
    else
        echo '<tr><td><input class="tooltip" title="To identify whether the learner has restarted the learning aim." type="checkbox" name="RES" /></td><td>Is the aim a re-start?</td></tr>';
    echo '</table></td></tr>';
    echo '<tr>';
    $this->dynamic_field_display('PartnerUKPRN', "<input class='compulsory validate[required]' type='text' value='" . $delivery->PartnerUKPRN . "' style='' id='PartnerUKPRN' name='PartnerUKPRN' maxlength=8 size=8>");
    $this->dynamic_field_display('DelLocPostCode', "<input class='compulsory validate[required]' type='text' value='" . $delivery->DelLocPostCode . "' style='' id='DelLocPostCode' name='DelLocPostCode' maxlength=8 size=8>");
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('PriorLearnFundAdj', "<input class='optional tooltip' type='text' value='" . $delivery->PriorLearnFundAdj . "' style='' id='PriorLearnFundAdj' name='PriorLearnFundAdj' maxlength=8 size=8 onKeyPress='return numbersonly99(this, event)'>");
    $this->dynamic_field_display('OtherFundAdj', "<input class='optional tooltip' type='text' value='" . $delivery->OtherFundAdj . "' style='' id='OtherFundAdj' name='OtherFundAdj' maxlength=8 size=8>");
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('AddHours', "<input class='optional tooltip' type='text' value='" . $delivery->AddHours . "' style='' id='AddHours' name='AddHours' maxlength=8 size=8 onKeyPress='return numbersonly(this, event)'>");
    $this->dynamic_field_display('ConRefNumber', "<input class='optional tooltip' type='text' value='" . $delivery->ConRefNumber . "' style='' id='ConRefNumber' name='ConRefNumber' maxlength=8 size=8>");
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('EPAOrgID', "<input class='optional tooltip' type='text' value='" . $delivery->EPAOrgID . "' style='' id='EPAOrgID' name='EPAOrgID' maxlength='7' >");
    $this->dynamic_field_display('LSDPostcode', "<input class='optional tooltip' type='text' value='" . $delivery->LSDPostcode . "' style='' id='LSDPostcode' name='LSDPostcode' maxlength='8' >");
    echo '</tr>';

    echo '<tr>';
    if ($delivery->AimType == '1' or $delivery->AimType == '99')
    {
        $this->dynamic_field_display('PHours', "<input class='optional tooltip' type='text' value='" . $delivery->PHours . "' style='' id='PHours' name='PHours' maxlength=7 size=7 >");
        $this->dynamic_field_display('OTJActHours', "<input class='optional tooltip' type='text' value='" . $delivery->OTJActHours . "' style='' id='OTJActHours' name='OTJActHours' maxlength=4 size=4 >");
    }
    echo '</tr>';

    echo '</table>';
    echo '</fieldset>';
    echo '<fieldset>';
    echo '<legend>Funding and Monitoring Information - (Aim Reference: ' . $delivery->LearnAimRef . ', Aim Type: ' . $delivery->AimType . ')</legend>';
    echo '<table border="0" cellspacing="4" cellpadding="4">';
    echo '<col width="150"/><col />';
    if ($delivery->AimType == '1' || $delivery->AimType == '4') // As told by Khush
    {
        echo '<tr><td colspan="2">';
        echo '<fieldset class="innerFieldset">';
        echo '<legend>Learning support funding</legend>';
        echo '<table>';
        $index = 0;
        foreach ($delivery->LearningDeliveryFAM as $ldf) {
            if ($ldf->LearnDelFAMType == 'LSF' && $ldf->LearnDelFAMCode != '' && $ldf->LearnDelFAMCode != 'undefined') {
                $index++;
                $lsf = "LSF" . $index;
                $from = "LSFFrom_" . $delivery->LearnAimRef . $index;
                $to = "LSFTo_" . $delivery->LearnAimRef . $index;
                echo '<tr>';
                $this->dynamic_field_display('LSFType', HTML::select($lsf, $LSF_dropdown, $ldf->LearnDelFAMCode, true, false));
                echo '</tr>';
                echo '<tr>';
                $this->dynamic_field_display('LearnDelFAMDateFrom', HTML::datebox($from, $ldf->LearnDelFAMDateFrom, false, false));
                $this->dynamic_field_display('LearnDelFAMDateTo', HTML::datebox($to, $ldf->LearnDelFAMDateTo, false, false));
                echo '</tr>';
            }
        }
        $index++;
        $lsf = "LSF" . $index;
        $from = "LSFFrom_" . $delivery->LearnAimRef . $index;
        $to = "LSFTo_" . $delivery->LearnAimRef . $index;
        echo '<tr>';
        $this->dynamic_field_display('LSFType', HTML::select($lsf, $LSF_dropdown, '', true, false));
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('LearnDelFAMDateFrom', HTML::datebox($from, '', false, false));
        $this->dynamic_field_display('LearnDelFAMDateTo', HTML::datebox($to, '', false, false));

        echo '</table></fieldset></td></tr>';
        echo '<tr><td colspan="2">';
        echo '<fieldset class="innerFieldset">';
        echo '<legend>24+ Advanced Learning Loans Bursary Fund</legend>';
        echo '<table>';
        $index = 0;
        $ALB_dropdown = array(array('1', '1 Advanced Learner Loan Bursary funding - rate 1'), array('2', '2 Advanced Learner Loan Bursary funding - rate 2'), array('3', '3 Advanced Learner Loan Bursary funding - rate 3'));
        foreach ($delivery->LearningDeliveryFAM as $ldf) {
            if ($ldf->LearnDelFAMType == 'ALB' && $ldf->LearnDelFAMCode != '' && $ldf->LearnDelFAMCode != 'undefined') {
                $index++;
                $alb = "ALB" . $index;
                $from = "ALBFrom_" . $delivery->LearnAimRef . $index;
                $to = "ALBTo_" . $delivery->LearnAimRef . $index;
                echo '<tr>';
                $this->dynamic_field_display('ALBType', HTML::select($alb, $ALB_dropdown, $ldf->LearnDelFAMCode, true, false));
                echo '</tr>';
                echo '<tr>';
                $this->dynamic_field_display('LearnDelFAMDateFrom', HTML::datebox($from, $ldf->LearnDelFAMDateFrom, false, false));
                $this->dynamic_field_display('LearnDelFAMDateTo', HTML::datebox($to, $ldf->LearnDelFAMDateTo, false, false));
                echo '</tr>';
            }
        }
        $index++;
        $alb = "ALB" . $index;
        $from = "ALBFrom_" . $delivery->LearnAimRef . $index;
        $to = "ALBTo_" . $delivery->LearnAimRef . $index;
        echo '<tr>';
        $this->dynamic_field_display('ALBType', HTML::select($alb, $ALB_dropdown, '', true, false));
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('LearnDelFAMDateFrom', HTML::datebox($from, '', false, false));
        $this->dynamic_field_display('LearnDelFAMDateTo', HTML::datebox($to, '', false, false));
        echo '</table></fieldset></td></tr>';
    }

    if ($delivery->AimType == '1' || $delivery->AimType == '3') // ACT
    {
        echo '<tr><td colspan="2">';
        echo '<fieldset class="innerFieldset">';
        echo '<legend>Apprenticeship Contract Type</legend>';
        echo '<table>';
        $index = 0;
        foreach ($delivery->LearningDeliveryFAM as $ldf) {
            if ($ldf->LearnDelFAMType == 'ACT' && $ldf->LearnDelFAMCode != '' && $ldf->LearnDelFAMCode != 'undefined') {
                $index++;
                $lsf = "ACT" . $index;
                $from = "ACTFrom_" . $delivery->LearnAimRef . $index;
                $to = "ACTTo_" . $delivery->LearnAimRef . $index;
                echo '<tr>';
                $this->dynamic_field_display('ACTType', HTML::select($lsf, $ACT_dropdown, $ldf->LearnDelFAMCode, true, false));
                echo '</tr>';
                echo '<tr>';
                $this->dynamic_field_display('LearnDelFAMDateFrom', HTML::datebox($from, $ldf->LearnDelFAMDateFrom, false, false));
                $this->dynamic_field_display('LearnDelFAMDateTo', HTML::datebox($to, $ldf->LearnDelFAMDateTo, false, false));
                echo '</tr>';
            }
        }
        $index++;
        $lsf = "ACT" . $index;
        $from = "ACTFrom_" . $delivery->LearnAimRef . $index;
        $to = "ACTTo_" . $delivery->LearnAimRef . $index;
        echo '<tr>';
        $this->dynamic_field_display('ACTType', HTML::select($lsf, $ACT_dropdown, '', true, false));
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('LearnDelFAMDateFrom', HTML::datebox($from, '', false, false));
        $this->dynamic_field_display('LearnDelFAMDateTo', HTML::datebox($to, '', false, false));
        echo '</table></fieldset></td></tr>';
    }


    echo '<tr><td colspan="2">';
    echo '<table>';
    echo '<tr>';
    if ($wpl == '1')
        echo '<td><input type="checkbox" class="tooltip" title="To identify whether the programme or learning aim is classified as workplace learning as defined in the Skills Funding Agency\'s funding rules." checked name="WPL" /></td><td>Is the aim workplace learning?</td>';
    else
        echo '<td><input type="checkbox" class="tooltip" title="To identify whether the programme or learning aim is classified as workplace learning as defined in the Skills Funding Agency\'s funding rules." name="WPL" /></td><td>Is the aim workplace learning?</td>';

    echo '<tr>';
    if ($fln == '1')
        echo '<td><input type="checkbox" class="tooltip" title="Policy monitoring and development" checked name="FLN" /></td><td>Family English, Maths or Language learning aim delivered through the Adult Skills Budget?</td>';
    else
        echo '<td><input type="checkbox" class="tooltip" title="Policy monitoring and development" name="FLN" /></td><td>Family English, Maths or Language learning aim delivered through the Adult Skills Budget?</td>';

    if ($delivery->AimType == '1' || $delivery->AimType == '4') {
        if ($adl == '1')
            echo '<tr><td><input type="checkbox" class="tooltip" title="To identify whether the learning aim is financed by a 24+ Advanced Learning Loan." checked name="ADL" /></td><td>Is the learner aim financed by 24+ Advanced learning loan?</td></tr>';
        else
            echo '<tr><td><input type="checkbox" class="tooltip" title="To identify whether the learning aim is financed by a 24+ Advanced Learning Loan." name="ADL" /></td><td>Is the learner aim financed by 24+ Advanced learning loan?</td></tr>';
    }
    echo '</tr>';
    echo '</table></td></tr>';
    echo '<tr>';
    $this->dynamic_field_display('SOF', HTML::select('SOF', $SOF_dropdown, $sof, true, true, true, 1, 'title = "' . $delivery->AimType . '" '));
    $this->dynamic_field_display('FFI', HTML::select('FFI', $FFI_dropdown, $ffi, true, true, true, 1, 'title = "' . $delivery->AimType . '" '));
    echo '</tr>';

    $EEF_dropdown = array(
        array('2', '2 Entitlement to 16-18 apprenticeship funding, where the learner is 19 or over'),
        array('3', '3 Entitlement to 19-23 apprenticeship funding, where the learner is 24 or over'),
        array('4', '4 Entitlement to extended funding for apprentices aged 19 to 24')
    );

    $this->dynamic_field_display('EEF', HTML::select('EEF', $EEF_dropdown, $eef, true, false));
    echo '</tr>';

    echo '<tr>';


    if ($delivery->AimType == '1' || $delivery->AimType == '4') {
        echo '<tr>';
        $this->dynamic_field_display('ASL', HTML::select('ASL', $ASL_dropdown, $asl, true, false));
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('NSA', HTML::select('NSA', $NSA_dropdown, $nsa, true, false));
        echo '</tr>';
    }

    if ($delivery->AimType == "1" || $delivery->AimType == "4" || $delivery->AimType == "5" || $delivery->AimType == "3") {

        $ldm = Array('','','','');
        $index = -1;
        foreach($delivery->LearningDeliveryFAM as $ldf)
        {
            if($ldf->LearnDelFAMType == 'LDM')
            {
                $index++;
                $ldm[$index] = $ldf->LearnDelFAMCode;
            }
        }
        $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='LDM']/LearnDelFAMCode");
        echo '<tr>';
        $this->dynamic_field_display('LDM', HTML::select(('LDM1'), $LDM_dropdown, $ldm[0], true, false));
        $this->dynamic_field_display('LDM', HTML::select(('LDM2'), $LDM_dropdown, $ldm[1], true, false));
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('LDM', HTML::select(('LDM3'), $LDM_dropdown, $ldm[2], true, false));
        $this->dynamic_field_display('LDM', HTML::select(('LDM4'), $LDM_dropdown, $ldm[3], true, false));
        echo '</tr>';
    }

    if (true) {

        $dam = Array('','','','');
        $index = -1;
        foreach($delivery->LearningDeliveryFAM as $ldf)
        {
            if($ldf->LearnDelFAMType == 'DAM')
            {
                $index++;
                $dam[$index] = $ldf->LearnDelFAMCode;
            }
        }
        $DAM_dropdown = array(
            array('001', '001'),
            array('002', '002'),
            array('003', '003'),
            array('004', '004'),
            array('005', '005'),
            array('006', '006'),
            array('007', '007'),
            array('008', '008'),
            array('009', '009'),
            array('010', '010'),
            array('011', '011'),
            array('012', '012'),
            array('013', '013'),
            array('014', '014'),
            array('015', '015'),
            array('016', '016'),
            array('017', '017'),
            array('018', '018'),
            array('019', '019'),
            array('020', '020'),
            array('023', '023'),
            array('040', '040'),
        );

        echo '<tr>';
        $this->dynamic_field_display('DAM', HTML::select(('DAM1'), $DAM_dropdown, $dam[0], true, false));
        $this->dynamic_field_display('DAM', HTML::select(('DAM2'), $DAM_dropdown, $dam[1], true, false));
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('DAM', HTML::select(('DAM3'), $DAM_dropdown, $dam[2], true, false));
        $this->dynamic_field_display('DAM', HTML::select(('DAM4'), $DAM_dropdown, $dam[3], true, false));
        echo '</tr>';
    }


    if ($delivery->AimType == "3" || $delivery->AimType == "4") {
        echo '<tr>';
        $POD_dropdown = array(array('1', '0%'), array('2', '1%-9%'), array('3', '10%-24%'), array('4', '25%-49%'), array('5', '50%-74%'), array('6', '75%-99%'), array('7', '100%'));
        $this->dynamic_field_display('POD', HTML::select('POD', $POD_dropdown, $pod, true, false));
        echo '</tr>';
    }

    echo '</tr>';

    echo '</table>';
    echo '</fieldset>';

    if($template != '1')
    {
        if ($delivery->AimType == "1" || $delivery->AimType == "4")
        {
            $delivery_aim_reference = $delivery->LearnAimRef;
            $sql = <<<SQL
SELECT
extractvalue(ilr, '/Learner/LearningDelivery[LearnAimRef=\'$delivery_aim_reference\']/LearningDeliveryFAM[LearnDelFAMType=\'HHS\']/LearnDelFAMCode') AS HHS
FROM ilr WHERE tr_id = '$tr_id' AND submission = '$submission' AND contract_id = $contract_id;
SQL;
            $result_set = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
            $hhs_string = $result_set[0]['HHS'];
            $selected_hhs_fam = explode(" ", $hhs_string);

            if(in_array('1', $selected_hhs_fam))
                $selected_hhs_fam[] = '1';
            if(in_array('2', $selected_hhs_fam))
                $selected_hhs_fam[] = '2';
            if(in_array('3', $selected_hhs_fam))
                $selected_hhs_fam[] = '3';

            echo '<tr><td colspan="2">';
            echo '	    <fieldset class="innerFieldset tooltip" title="Information about the household situation of the learner.">';
            echo '	      <legend><img style="cursor: pointer;" src="/images/info-icon.png" alt="Information" height="15" width="15" title="Click for information" onclick="HHSDetail();" />Household Situation (select up to 2 that apply) - (Aim Reference: ' . $delivery->LearnAimRef . ', Aim Type: ' . $delivery->AimType . ')</legend>';
            echo '	        <table border="0" cellspacing="4" cellpadding="4">';
            echo '	          <tr>';
            echo '	            <td>';
            $HHS_dropdown = array(
                array('1', 'HHS1 - No member of the household in which learner lives (including learner) is employed'),
                array('2', 'HHS2 - The household that learner lives in includes only one adult (aged 18 or over)'),
                array('3', 'HHS3 - There are one or more dependent children (aged 0-17 years or 18-24 years if full-time student or inactive) in the household'),
                array('99', 'HHS99 - None of these statements apply'),
                array('98', 'HHS98 - Learner wants to withhold this information')
            );
            echo HTML::checkboxGrid('HHS_'.$delivery_aim_reference, $HHS_dropdown, $selected_hhs_fam);
            echo '             </td></tr></table>';
            echo '      </fieldset>';
            echo '</tr>';
        }
    }

    echo '<fieldset>';
    echo '<legend>Provider Specified Delivery Monitoring Information - (Aim Reference: ' . $delivery->LearnAimRef . ', Aim Type: ' . $delivery->AimType . ')</legend>';
    echo '<table border="0" cellspacing="4" cellpadding="4">';
    echo '<col width="150"/><col />';
    echo '<tr>';
    $ProvSpecDelMonA = '';
    $ProvSpecDelMonB = '';
    $ProvSpecDelMonC = '';
    $ProvSpecDelMonD = '';
    foreach($delivery->ProviderSpecDeliveryMonitoring as $psdm)
    {
        if($psdm->ProvSpecDelMonOccur='A')
            $ProvSpecDelMonA = $psdm->ProvSpecDelMon;
        if($psdm->ProvSpecDelMonOccur='B')
            $ProvSpecDelMonB = $psdm->ProvSpecDelMon;
        if($psdm->ProvSpecDelMonOccur='C')
            $ProvSpecDelMonC = $psdm->ProvSpecDelMon;
        if($psdm->ProvSpecDelMonOccur='D')
            $ProvSpecDelMonD = $psdm->ProvSpecDelMon;
    }
    $this->dynamic_field_display('ProvSpecDelMon', "<input class='optional' type='text' value='" . $ProvSpecDelMonA . "' style='' id='ProvSpecDelMonA' name='ProvSpecDelMonA' maxlength=12 size=30>");
    $this->dynamic_field_display('ProvSpecDelMon', "<input class='optional' type='text' value='" . $ProvSpecDelMonB . "' style='' id='ProvSpecDelMonB' name='ProvSpecDelMonB' maxlength=12 size=30>");
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('ProvSpecDelMon', "<input class='optional' type='text' value='" . $ProvSpecDelMonC . "' style='' id='ProvSpecDelMonC' name='ProvSpecDelMonC' maxlength=12 size=30>");
    $this->dynamic_field_display('ProvSpecDelMon', "<input class='optional' type='text' value='" . $ProvSpecDelMonD . "' style='' id='ProvSpecDelMonD' name='ProvSpecDelMonD' maxlength=12 size=30>");
    echo '</tr>';
    echo '</table>';
    echo '</fieldset>';

    $xpath = $delivery->xpath("LearningDeliveryFAM[LearnDelFAMType='SOF']");
    $hefce_sof = (empty($xpath[0])) ? '' : $xpath[0]->LearnDelFAMCode->__toString();
    $xpath = $delivery->xpath("LearningDeliveryFAM[LearnDelFAMType='WPL']");
    $hefce_wpl = (empty($xpath[0])) ? '' : $xpath[0]->LearnDelFAMCode->__toString();
    $condition1 = $hefce_sof != 'undefined' && $hefce_sof == '1';
    $condition2 = $hefce_sof != 'undefined' && $hefce_sof == '107' && $delivery->ProgType >= 20;
    $condition3 = $hefce_wpl != 'undefined' && $hefce_wpl != '1' && $delivery->FundModel == 35 && $delivery->ProgType >= 20;
    $condition4 = $delivery->FundModel == 99 && $delivery->ProgType >= 20;
    $LearnAimRef = $delivery->LearnAimRef;
    $ShouldLDHE = DAO::getSingleValue($link, "SELECT EnglandFEHEStatus FROM lars201718.Core_LARS_LearningDelivery WHERE LearnAimRef = '$LearnAimRef';");
    if ($ShouldLDHE=="H") {
        //Learning Delivery HE
        echo '<fieldset>';
        echo '<legend>Learning Delivery HE - (Aim Reference: ' . $delivery->LearnAimRef . ', Aim Type: ' . $delivery->AimType . ')</legend>';
        echo '<table border="0" cellspacing="4" cellpadding="4">';
        echo '<col width="150"/><col />';
        echo '<tr>';
        $this->dynamic_field_display('NUMHUS', "<input class='optional validate[required]' type='text' value='" . $delivery->LearningDeliveryHE->NUMHUS . "' style='' id='NUMHUS' name='NUMHUS' maxlength=20 size=20 >");
        $this->dynamic_field_display('SSN', "<input class='optional validate[required]' type='text' value='" . $delivery->LearningDeliveryHE->SSN . "' style='' id='SSN' name='SSN' maxlength=13 size=13 >");
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('UCASAPPID', "<input class='optional validate[required]' type='text' value='" . $delivery->LearningDeliveryHE->UCASAPPID . "' style='' id='UCASAPPID' name='UCASAPPID' maxlength=9 size=9 >");
        $this->dynamic_field_display('SOC2000', HTML::select('SOC2000', $SOC2000_dropdown, $delivery->LearningDeliveryHE->SOC2000, true, false));
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('SEC', HTML::select('SEC', $SEC_dropdown, $delivery->LearningDeliveryHE->SEC, true, false));
        $this->dynamic_field_display('QUALENT3', HTML::select('QUALENT3', $QUALENT3_dropdown, $delivery->LearningDeliveryHE->QUALENT3, true, false));
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('ELQ', HTML::select('ELQ', $ELQ_dropdown, $delivery->LearningDeliveryHE->ELQ, true, false));
        $this->dynamic_field_display('DOMICILE', HTML::select('DOMICILE', $Domicile_dropdown, $delivery->LearningDeliveryHE->DOMICILE, true, false));
        echo '</tr>';
        echo '<tr><td colspan="2">';
        echo '<fieldset class="innerFieldset">';
        echo '<legend>Instance Information</legend>';
        echo '<table border="0" cellspacing="4" cellpadding="4">';
        echo '<col width="150"/><col />';
        echo '<tr>';
        $this->dynamic_field_display('TYPEYR', HTML::select('TYPEYR', $TypeYr_dropdown, $delivery->LearningDeliveryHE->TYPEYR, true, true));
        $this->dynamic_field_display('PCOLAB', "<input class='optional validate[required]' type='text' value='" . $delivery->LearningDeliveryHE->PCOLAB . "' style='' id='PCOLAB' name='PCOLAB' maxlength=6 size=6 >");
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('MODESTUD', HTML::select('MODESTUD', $ModeStud_dropdown, $delivery->LearningDeliveryHE->MODESTUD, true, true));
        $this->dynamic_field_display('PCFLDCS', "<input class='optional validate[required]' type='text' value='" . $delivery->LearningDeliveryHE->PCFLDCS . "' style='' id='PCFLDCS' name='PCFLDCS' maxlength=6 size=6 >");
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('FUNDLEV', HTML::select('FUNDLEV', $FundLev_dropdown, $delivery->LearningDeliveryHE->FUNDLEV, true, true));
        $this->dynamic_field_display('PCSLDCS', "<input class='optional validate[required]' type='text' value='" . $delivery->LearningDeliveryHE->PCSLDCS . "' style='' id='PCSLDCS' name='PCSLDCS' maxlength=6 size=6 >");
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('YEARSTU', "<input class='compulsory validate[required]' type='text' value='" . $delivery->LearningDeliveryHE->YEARSTU . "' style='' id='YEARSTU' name='YEARSTU' maxlength=2 size=2 >");
        $this->dynamic_field_display('PCTLDCS', "<input class='optional validate[required]' type='text' value='" . $delivery->LearningDeliveryHE->PCTLDCS . "' style='' id='PCTLDCS' name='PCTLDCS' maxlength=6 size=6 >");
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('FUNDCOMP', HTML::select('FUNDCOMP', $FundComp_dropdown, $delivery->LearningDeliveryHE->FUNDCOMP, true, true));
        $this->dynamic_field_display('STULOAD', "<input class='optional validate[required]' type='text' value='" . $delivery->LearningDeliveryHE->STULOAD . "' style='' id='STULOAD' name='STULOAD' maxlength=6 size=6 >");
        echo '</tr>';
        echo '</table>';
        echo '</td></tr>';
        echo '<tr><td colspan="2">';
        echo '<fieldset class="innerFieldset">';
        echo '<legend>Fee Information</legend>';
        echo '<table border="0" cellspacing="4" cellpadding="4">';
        echo '<col width="150"/><col />';
        echo '<tr>';
        $this->dynamic_field_display('MSTUFEE', HTML::select('MSTUFEE', $MSTuFee_dropdown, $delivery->LearningDeliveryHE->MSTUFEE, true, true));
        $this->dynamic_field_display('SPECFEE', HTML::select('SPECFEE', $SpecFee_dropdown, $delivery->LearningDeliveryHE->SPECFEE, true, true));
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('NETFEE', "<input class='optional validate[required]' type='text' value='" . $delivery->LearningDeliveryHE->NETFEE . "' style='' id='NETFEE' name='NETFEE' maxlength=6 size=6 >");
        $this->dynamic_field_display('GROSSFEE', "<input class='optional validate[required]' type='text' value='" . $delivery->LearningDeliveryHE->GROSSFEE . "' style='' id='GROSSFEE' name='GROSSFEE' maxlength=6 size=6 >");
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('HEPostCode', "<input class='optional validate[required]' type='text' value='" . $delivery->LearningDeliveryHE->HEPostCode . "' style='' id='HEPostCode' name='HEPostCode' maxlength=6 size=6 >");
        echo '</tr>';
        echo '</table>';
        echo '</td></tr>';
        echo '<tr><td colspan="2">';
        echo '<fieldset class="innerFieldset">';
        echo '<legend>HEM Indicators</legend>';
        $HEMFAM_dropdown = array(
            array('HEM1', '1 - Student is funded by HEFCE using the old funding regime (only for learning aims starting on or after 1 September 2012)'),
            array('HEM3', '3 - Student has received an award under the National Scholarship programme for this learning aim'),
            array('HEM5', '5 - Student\'s qualifications and grades prior to enrolment are included in the student number control exemption list according to HEFCE')
        );
        $delivery_aim_reference = $delivery->LearnAimRef;
        $sql = <<<SQL
SELECT
extractvalue(ilr, '/Learner/LearningDelivery[LearnAimRef=\'$delivery_aim_reference\']/LearningDeliveryFAM[LearnDelFAMType=\'HEM\']/LearnDelFAMCode') AS HEM
FROM ilr WHERE tr_id = '$tr_id' AND submission = '$submission' AND contract_id = $contract_id;
SQL;
        $result_set = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        $hem_string = $result_set[0]['HEM'];
        $selected_hem_fam = explode(" ", $hem_string);

        if(in_array('1', $selected_hem_fam))
            $selected_hem_fam[] = 'HEM1';
        if(in_array('3', $selected_hem_fam))
            $selected_hem_fam[] = 'HEM3';
        if(in_array('5', $selected_hem_fam))
            $selected_hem_fam[] = 'HEM5';

        echo HTML::checkboxGrid('HEM_'.$delivery_aim_reference, $HEMFAM_dropdown, $selected_hem_fam);
        echo '</fieldset>';
        echo '</td></tr>';
        echo '</table>';
        echo '</fieldset>';
    }

    if ($delivery->AimType == '1' && ($delivery->ProgType == '25' || $delivery->FundModel=='36')) {
        echo '<fieldset>';
        echo '<legend>Apprenticeship Financial Details - (Aim Reference: ' . $delivery->LearnAimRef . ', Aim Type: ' . $delivery->AimType . ')</legend>';
        echo '<table border="0" cellspacing="4" cellpadding="4">';
        echo '<col width="150"/><col />';

        if(DB_NAME=='am_baltic')
        {
            echo '<tr>';
            echo '<td class="fieldLabel_compulsory">Overall TNP for Waste Reporting:</td>';
            echo '<td><input class="optional" type="text" name="OverallTNP" value="' . $delivery->OverallTNP . '" maxlength="50"/></td>';
            echo '</tr>';
        }

        $trailblazerindex = 0;
        $TBFinType_dropdown = array(array('TNP', 'TNP total negotiated price'), array('PMR', 'PMR Payment record'));
        $TBFinCode_dropdown = array(array('1', '1'), array('2', '2'), array('3', '3'), array('4', '4'));
        foreach ($delivery->TrailblazerApprenticeshipFinancialRecord as $trailblazer) {
            if ($trailblazer->TBFinType != '') {
                $trailblazerindex++;
                echo '<tr>';
                $tbfintype = $trailblazer->TBFinType;
                $this->dynamic_field_display('TBFinType', HTML::select('TBFinType' . $trailblazerindex, $TBFinType_dropdown, $tbfintype, true, true));
                $tbfincode = $trailblazer->TBFinCode;
                $this->dynamic_field_display('TBFinCode', HTML::select('TBFinCode' . $trailblazerindex, $TBFinCode_dropdown, $tbfincode, true, true));
                echo '</tr>';
                echo '<tr>';
                $tbfindate = $trailblazer->TBFinDate;
                $this->dynamic_field_display('TBFinDate', HTML::datebox('TBFinDate' . $trailblazerindex, $tbfindate, true));
                $tbfinamount = $trailblazer->TBFinAmount;
                $this->dynamic_field_display('TBFinAmount', "<input class='compulsory validate[required]' type='text' value='" . $tbfinamount . "' style='' id='TBFinAmount$trailblazerindex' name='TBFinAmount$trailblazerindex' maxlength=30 size=30 onKeyPress='return numbersonly(this, event)'>");
                echo '</tr>';
            }
        }
        $trailblazerindex++;
        echo '<tr>';
        $this->dynamic_field_display('TBFinType', HTML::select('TBFinType' . $trailblazerindex, $TBFinType_dropdown, '', true, true));
        $this->dynamic_field_display('TBFinCode', HTML::select('TBFinCode' . $trailblazerindex, $TBFinCode_dropdown, '', true, true));
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('TBFinDate', HTML::datebox('TBFinDate' . $trailblazerindex, '', true));
        $this->dynamic_field_display('TBFinAmount', "<input class='compulsory validate[required]' type='text' value='" . '' . "' style='' id='TBFinAmount$trailblazerindex' name='TBFinAmount$trailblazerindex' maxlength=30 size=30 onKeyPress='return numbersonly(this, event)'> ");
        echo '</tr>';
        if($trailblazerindex==1)
        {
            $trailblazerindex++;
            echo '<tr>';
            $this->dynamic_field_display('TBFinType', HTML::select('TBFinType' . $trailblazerindex, $TBFinType_dropdown, '', true, true));
            $this->dynamic_field_display('TBFinCode', HTML::select('TBFinCode' . $trailblazerindex, $TBFinCode_dropdown, '', true, true));
            echo '</tr>';
            echo '<tr>';
            $this->dynamic_field_display('TBFinDate', HTML::datebox('TBFinDate' . $trailblazerindex, '', true));
            $this->dynamic_field_display('TBFinAmount', "<input class='compulsory validate[required]' type='text' value='" . '' . "' style='' id='TBFinAmount$trailblazerindex' name='TBFinAmount$trailblazerindex' maxlength=30 size=30 onKeyPress='return numbersonly(this, event)'>");
            echo '</tr>';
        }
        echo '</table>';
        echo '</fieldset>';
    }
    if ($delivery->LearnAimRef == 'Z0007834' || $delivery->LearnAimRef == 'Z0007835' || $delivery->LearnAimRef == 'Z0007836' || $delivery->LearnAimRef == 'Z0007837' || $delivery->LearnAimRef == 'Z0007838' || $delivery->LearnAimRef == 'ZWRKX001') {
        echo '<fieldset>';
        echo '<legend>Work Placement - (Aim Reference: ' . $delivery->LearnAimRef . ', Aim Type: ' . $delivery->AimType . ')</legend>';
        $ldwpindex = 0;
        echo '<table border="0" cellspacing="4" cellpadding="4">';
        echo '<col width="150"/><col />';
        foreach ($delivery->LearningDeliveryWorkPlacement as $ldwp) {
            if ($ldwp->WorkPlaceStartDate != '' && $ldwp->WorkPlaceStartDate != 'undefined') {
                $ldwpindex++;
                echo '<tr>';
                $workplace_mode = "WorkPlaceMode_" . $delivery->LearnAimRef . $ldwpindex;
                $workplace_emp_id = "WorkPlaceEmpId_" . $delivery->LearnAimRef . $ldwpindex;
                $workplace_hours = "WorkPlaceHours_" . $delivery->LearnAimRef . $ldwpindex;

                $from = "WorkPlaceStartDate_" . $delivery->LearnAimRef . $ldwpindex;
                $to = "WorkPlaceEndDate_" . $delivery->LearnAimRef . $ldwpindex;
                $this->dynamic_field_display('WorkPlaceStartDate', HTML::datebox($from, $ldwp->WorkPlaceStartDate));
                $this->dynamic_field_display('WorkPlaceEndDate', HTML::datebox($to, $ldwp->WorkPlaceEndDate));
                echo '</tr>';
                echo '<tr>';
                $WPMode_dropdown = array(array('1', '1 Internal (simulated) work placement'), array('2', '2 External work placement'));
                $this->dynamic_field_display('WorkPlaceMode', HTML::select($workplace_mode, $WPMode_dropdown, $ldwp->WorkPlaceMode, true, true));
                $this->dynamic_field_display('WorkPlaceEmpId', "<input class='compulsory validate[required]' type='text' value='" . $ldwp->WorkPlaceEmpId . "' style='' id='$workplace_emp_id' name='$workplace_emp_id' maxlength=30 size=30>");
                echo '</tr>';
                echo '<tr>';
                $this->dynamic_field_display('WorkPlaceHours', "<input class='compulsory validate[required]' type='text' value='" . $ldwp->WorkPlaceHours . "' style='' id='$workplace_hours' name='$workplace_hours' maxlength=30 size=30 onKeyPress='return numbersonly(this, event)'>");
                echo '</tr>';
            }
        }
        $ldwpindex++;
        $workplace_mode = "WorkPlaceMode_" . $delivery->LearnAimRef . $ldwpindex;
        $workplace_emp_id = "WorkPlaceEmpId_" . $delivery->LearnAimRef . $ldwpindex;
        $workplace_hours = "WorkPlaceHours_" . $delivery->LearnAimRef . $ldwpindex;
        $from = "WorkPlaceStartDate_" . $delivery->LearnAimRef . $ldwpindex;
        $to = "WorkPlaceEndDate_" . $delivery->LearnAimRef . $ldwpindex;
        echo '<tr>';
        $this->dynamic_field_display('WorkPlaceStartDate', HTML::datebox($from, ''));
        $this->dynamic_field_display('WorkPlaceEndDate', HTML::datebox($to, ''));
        echo '</tr>';
        echo '<tr>';
        $WPMode_dropdown = array(array('1', '1 Internal (simulated) work placement'), array('2', '2 External work placement'));
        $this->dynamic_field_display('WorkPlaceMode', HTML::select($workplace_mode, $WPMode_dropdown, '', true, true));
        $this->dynamic_field_display('WorkPlaceEmpId', "<input class='compulsory validate[required]' type='text' value='" . '' . "' style='' id='$workplace_emp_id' name='$workplace_emp_id' maxlength=30 size=30>");
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('WorkPlaceHours', "<input class='compulsory validate[required]' type='text' value='" . '' . "' style='' id='$workplace_hours' name='$workplace_hours' maxlength=30 size=30 onKeyPress='return numbersonly(this, event)'>");
        echo '</tr>';
        echo '</table></fieldset>';
    }
    echo '<fieldset>';
    echo '<legend>Learning End Information - (Aim Reference: ' . $delivery->LearnAimRef . ', Aim Type: ' . $delivery->AimType . ')</legend>';
    echo '<table border="0" cellspacing="4" cellpadding="4">';
    echo '<col width="150"/><col />';

    echo '<tr>';
    $this->dynamic_field_display('LearnActEndDate', HTML::datebox('LearnActEndDate_' . $delivery->LearnAimRef, $delivery->LearnActEndDate));
    $this->dynamic_field_display('AchDate', HTML::datebox('AchDate_' . $delivery->LearnAimRef, $delivery->AchDate));
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('CompStatus', HTML::select('CompStatus', $CompStatus_dropdown, $delivery->CompStatus, false, true));
    $this->dynamic_field_display('WithdrawReason', HTML::select('WithdrawReason', $WithdrawReason_dropdown, $delivery->WithdrawReason, true, true));
    echo '</tr>';

    echo '<tr>';
    $Outcome_dropdown = array(
        array('1', '1 Achieved'),
        array('2', '2 Partial achievement'),
        array('3', '3 No achievement'),
        array('8', '8 Learning activities are complete but the outcome is not yet known))')
    );
    $this->dynamic_field_display('Outcome', HTML::select('Outcome', $Outcome_dropdown, $delivery->Outcome, true, true));
    $this->dynamic_field_display('EmpOutcome', HTML::select('EmpOutcome', $EmpOutcome_dropdown, $delivery->EmpOutcome, true, true));
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('OutGrade', HTML::select('OutGrade', $OutGrade_dropdown, $delivery->OutGrade, true, true));
    echo '</tr>';

    echo '</table></fieldset>';
    echo '</div>';
}
?>
</div>
</div>
</div>
<div>
<fieldset>
<legend>Employment Information</legend>
<div id="employment_set" style="border: solid 0px #A3A3A3; width: 830px;">
<?php
$index = 0;
$SEI_dropdown = array(array('1', '1 Learner is self employed'));
//$EII_dropdown = array(array('1', '1 Learner is employed for 16 hours or more per week'), array('2', '2 Learner is employed for less than 16 hours per week'), array('3', '3 Learner is employed for 16-19 hours per week'), array('4', '4 Learner is employed for 20 hours or more per week'));
$EII_dropdown = array(
    //array('1', '1 Learner is employed for 16 hours or more per week'),
    array('2', '2 Learner is employed for less than 16 hours per week (Valid till 31/07/2018)'),
    array('3', '3 Learner is employed for 16-19 hours per week (Valid till 31/07/2018)'),
    array('4', '4 Learner is employed for 20 hours or more per week (Valid till 31/07/2018)'),
    array('5', '5 Learner is employed for 0 to 10 hours per week'),
    array('6', '6 Learner is employed for 11 to 20 hours per week'),
    array('7', '7 Learner is employed for 21 to 30 hours per week'),
    array('8', '8 Learner is employed for 31+ hours per week')
);
$LOU_dropdown = array(array('1', '1 Learner has been unemployed for less than 6 months'), array('2', '2 Learner has been unemployed for 6-11 months'), array('3', '3 Learner has been unemployed for 12-23 months'), array('4', '4 Learner has been unemployed for 24-35 months'), array('5', '5 Learner has been unemployed for over 36 months'));
$LOE_dropdown = array(array('1', '1 Learner has been employed for up to 3 months'), array('2', '2 Learner has been employed for 4-6 months'), array('3', '3 Learner has been employed for 7-12 months'), array('4', '4 Learner has been employed for more than 12 months'));
$BSI_dropdown = array(array('1', '1 Learner is in receipt of JSA'), array('2', '2 Learner is in receipt of ESA WRAG'), array('3', '3 Learner is in receipt of another state benefit'), array('4', '4 Learner is in receipt of Universal Credit'), array('5', '5 Learner is in receipt of Employment and Support Allowance (all categories)'), array('6', '6 Learner is in receipt of other state benefits'), array('7', '7 Unassigned'), array('8', '8 Unassigned'), array('9', '9 Unassigned'), array('10', '10 Unassigned'));
$PEI_dropdown = array(array('1', '1 Learner was in full time education or training prior to enrolment'));
$RON_dropdown = array(array('1', '1 Learner is aged 14-15 and is at risk of becoming NEET'));
$SEM_dropdown = array(array('1', '1 Small employer'));
$OET_dropdown = array(array('1', '1 Learner has been made redundant'));
// Prior enrolment
foreach ($vo->LearnerEmploymentStatus as $empstatus) {
    $tr_start_date = new Date($training_record->start_date);
    if($tr_start_date->after(Date::toShort($empstatus->DateEmpStatApp)))
    {
        $index++;
        echo '<div id="employ-status-' . $index . '">';
        echo '<fieldset class="innerFieldset"><legend>Prior to enrolment Learning Employment Status</legend>';
        echo '<table border="0" cellspacing="4" cellpadding="4" >';
        echo '<col width="394"/><col width="435" />';
        $empstat_desc = DAO::getSingleValue($link, "SELECT EmpStaCode_Desc from lis201415.ilr_empstatcode where EmpStatCode = '" . $empstatus->EmpStat . "'");
        echo '<tr><td style="background-color: #F3FAE5; border:1px solid #648827; padding:1px; ">' . Date::toShort($empstatus->DateEmpStatApp) . ' - ' . $empstat_desc . '</td><td><span class="button"><a onclick="$(\'tr.emp_stat_' . $index . '\').toggle();" >update</a></span></td></tr>';
        $id = "EmpStat" . $index;

        echo '<tr style="display:none" class="emp_stat_' . $index . '">';
        $this->dynamic_field_display('EmpStat', HTML::select($id, $EmpStat_dropdown, $empstatus->EmpStat, true, true));
        $id = "DateEmpStatApp" . $index;
        $this->dynamic_field_display('DateEmpStatApp', HTML::datebox($id, Date::toShort($empstatus->DateEmpStatApp), true, true));
        echo '</tr>';

        echo '<tr style="display:none;" class="emp_stat_' . $index . '">';
        $this->dynamic_field_display('EmpId', "<input class='compulsory validate[required]' type='text' value='" . $empstatus->EmpId . "' style='' id='EmpId$index' name='EmpId$index' maxlength=30 size=30>");
        //$this->dynamic_field_display('AgreeId', "<input class='compulsory validate[required]' type='text' value='" . $empstatus->AgreeId . "' style='' id='AgreeId$index' name='AgreeId$index' maxlength=6 size=30>");
        echo '</tr>';
        echo '<tr style="display:none;" class="emp_stat_' . $index . '">';
        echo '<td colspan="2">';
        echo '<fieldset class="innerFieldset" style="border: margin-top: 15px;padding: 10px;border: 1px solid #B5B8C8;border-radius: 15px;"><legend>Employment status monitoring types and codes</legend>';
        echo '<table>';

        echo '<tr><td colspan="2">';
        echo '<table>';
        $xpath = $empstatus->xpath("EmploymentStatusMonitoring[ESMType='SEI']");
        $sei = (empty($xpath[0]))?'':$xpath[0]->ESMCode->__toString();
        $id = "SEI" . $index;
        if ($sei == '1')
            echo '<tr><td><input type="checkbox" class="tooltip" title="Indicates whether the learner is self employed." checked name="' . $id .'" id="' . $id .'" /></td><td>Is the learner self employed?</td></tr>';
        else
            echo '<tr><td><input type="checkbox" class="tooltip" title="Indicates whether the learner is self employed." name="' . $id .'" id="' . $id .'" /></td><td>Is the learner self employed?</td></tr>';
        $xpath = $empstatus->xpath("EmploymentStatusMonitoring[ESMType='PEI']");
        $pei = (empty($xpath[0]))?'':$xpath[0]->ESMCode->__toString();
        $id = "PEI" . $index;
        if ($pei == '1')
            echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies whether the learner was in full time education or training prior to enrolment. To be used in conjunction with the employment status data to identify learners who were NEET (Not in education, employment or training)before starting learning." checked name="' . $id .'" id="' . $id .'" /></td><td>Was the learner in full time education or training prior to enrolment?</td></tr>';
        else
            echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies whether the learner was in full time education or training prior to enrolment. To be used in conjunction with the employment status data to identify learners who were NEET (Not in education, employment or training)before starting learning." name="' . $id .'" id="' . $id .'" /></td><td>Was the learner in full time education or training prior to enrolment?</td></tr>';
        $xpath = $empstatus->xpath("EmploymentStatusMonitoring[ESMType='RON']");
        $ron = (empty($xpath[0]))?'':$xpath[0]->ESMCode->__toString();
        $id = "RON" . $index;
        //if ($ron == '1')
        //    echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies learners who are aged 14-15 and at risk of becoming NEET (Not in education, employment or training) for ESF funding and eligibility purposes." checked name="' . $id .'" id="' . $id .'" /></td><td>Is the learner aged 14-15 and at risk of becoming NEET?</td></tr>';
        //else
        //    echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies learners who are aged 14-15 and at risk of becoming NEET (Not in education, employment or training) for ESF funding and eligibility purposes." name="' . $id .'" id="' . $id .'" /></td><td>Is the learner aged 14-15 and at risk of becoming NEET?</td></tr>';
        $xpath = $empstatus->xpath("EmploymentStatusMonitoring[ESMType='SEM']");
        $sem = (empty($xpath[0]))?'':$xpath[0]->ESMCode->__toString();
        $id = "SEM" . $index;
        if ($sem == '1')
            echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies whether the employer recorded in the Employer identifier is a small employer as defined in the funding rules for Trailblazer apprenticeships." checked name="' . $id .'" id="' . $id .'" /></td><td>For apprenticeships only is this a small employer?</td></tr>';
        else
            echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies whether the employer recorded in the Employer identifier is a small employer as defined in the funding rules for Trailblazer apprenticeships." name="' . $id .'" id="' . $id .'" /></td><td>For apprenticeships only is this a small employer?</td></tr>';

        echo '</table></td></tr>';

        echo '<tr>';

        echo '<tr>';
        $xpath = $empstatus->xpath("EmploymentStatusMonitoring[ESMType='LOE']");
        $loe = (empty($xpath[0]))?'':$xpath[0]->ESMCode->__toString();
        $id = "LOE" . $index;
        $this->dynamic_field_display('LOE', HTML::select($id, $LOE_dropdown, $loe, true, true));

        $xpath = $empstatus->xpath("EmploymentStatusMonitoring[ESMType='EII']");
        $eii = (empty($xpath[0]))?'':$xpath[0]->ESMCode->__toString();
        $id = "EII" . $index;
        $this->dynamic_field_display('EII', HTML::select($id, $EII_dropdown, $eii, true, true));
        echo '</tr>';

        echo '<tr style="display:none;" class="emp_stat_' . $index . '">';
        $xpath = $empstatus->xpath("EmploymentStatusMonitoring[ESMType='LOU']");
        $lou = (empty($xpath[0]))?'':$xpath[0]->ESMCode->__toString();
        $id = "LOU" . $index;
        $this->dynamic_field_display('LOU', HTML::select($id, $LOU_dropdown, $lou, true, true));
        $xpath = $empstatus->xpath("EmploymentStatusMonitoring[ESMType='BSI']");
        $bsi = (empty($xpath[0]))?'':$xpath[0]->ESMCode->__toString();
        $id = "BSI" . $index;
        $this->dynamic_field_display('BSI', HTML::select($id, $BSI_dropdown, $bsi, true, true));
        echo '</tr>';

        echo '<tr style="display:none;" class="emp_stat_' . $index . '">';
        $xpath = $empstatus->xpath("EmploymentStatusMonitoring[ESMType='OET']");
        $oet = (empty($xpath[0]))?'':$xpath[0]->ESMCode->__toString();
        $id = "OET" . $index;
        $this->dynamic_field_display('OET', HTML::select($id, $OET_dropdown, $oet, true, true));
        echo '</tr>';

        echo '</table>';
        echo '</fieldset>';
        echo '</td></tr>';
        echo '</table>';
        if ($index == 1) {
            echo '</fieldset>';
        } elseif ($index == 2) {
            echo '</fieldset>';
        }
        echo '</div>';
    }
}
// Post enrolment
foreach ($vo->LearnerEmploymentStatus as $empstatus) {
    if(!$tr_start_date->after(Date::toShort($empstatus->DateEmpStatApp)))
    {
        $index++;
        echo '<div id="employ-status-' . $index . '">';
        echo '<fieldset class="innerFieldset"><legend>Employment Status since enrolment</legend>';
        echo '<table border="0" cellspacing="4" cellpadding="4" >';
        echo '<col width="394"/><col width="435" />';
        $empstat_desc = DAO::getSingleValue($link, "SELECT EmpStaCode_Desc from lis201415.ilr_empstatcode where EmpStatCode = '" . $empstatus->EmpStat . "'");
        echo '<tr><td style="background-color: #F3FAE5; border:1px solid #648827; padding:1px; ">' . Date::toShort($empstatus->DateEmpStatApp) . ' - ' . $empstat_desc . '</td><td><span class="button"><a onclick="$(\'tr.emp_stat_' . $index . '\').toggle();" >update</a></span></td></tr>';
        $id = "EmpStat" . $index;

        echo '<tr style="display:none" class="emp_stat_' . $index . '">';
        $this->dynamic_field_display('EmpStat', HTML::select($id, $EmpStat_dropdown, $empstatus->EmpStat, true, true));
        $id = "DateEmpStatApp" . $index;
        $this->dynamic_field_display('DateEmpStatApp', HTML::datebox($id, Date::toShort($empstatus->DateEmpStatApp), true, true));
        echo '</tr>';

        echo '<tr style="display:none;" class="emp_stat_' . $index . '">';
        $this->dynamic_field_display('EmpId', "<input class='compulsory validate[required]' type='text' value='" . $empstatus->EmpId . "' style='' id='EmpId$index' name='EmpId$index' maxlength=30 size=30>");
        //$this->dynamic_field_display('AgreeId', "<input class='compulsory validate[required]' type='text' value='" . $empstatus->AgreeId . "' style='' id='AgreeId$index' name='AgreeId$index' maxlength=6 size=30>");
        echo '</tr>';
        echo '<tr style="display:none;" class="emp_stat_' . $index . '">';
        echo '<td colspan="2">';
        echo '<fieldset class="innerFieldset" style="border: margin-top: 15px;padding: 10px;border: 1px solid #B5B8C8;border-radius: 15px;"><legend>Employment status monitoring types and codes</legend>';
        echo '<table>';

        echo '<tr><td colspan="2">';
        echo '<table>';
        $xpath = $empstatus->xpath("EmploymentStatusMonitoring[ESMType='SEI']");
        $sei = (empty($xpath[0]))?'':$xpath[0]->ESMCode->__toString();
        $id = "SEI" . $index;
        if ($sei == '1')
            echo '<tr><td><input type="checkbox" class="tooltip" title="Indicates whether the learner is self employed." checked name="' . $id .'" id="' . $id .'" /></td><td>Is the learner self employed?</td></tr>';
        else
            echo '<tr><td><input type="checkbox" class="tooltip" title="Indicates whether the learner is self employed." name="' . $id .'" id="' . $id .'" /></td><td>Is the learner self employed?</td></tr>';
        $xpath = $empstatus->xpath("EmploymentStatusMonitoring[ESMType='PEI']");
        $pei = (empty($xpath[0]))?'':$xpath[0]->ESMCode->__toString();
        $id = "PEI" . $index;
        if ($pei == '1')
            echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies whether the learner was in full time education or training prior to enrolment. To be used in conjunction with the employment status data to identify learners who were NEET (Not in education, employment or training)before starting learning." checked name="' . $id .'" id="' . $id .'" /></td><td>Was the learner in full time education or training prior to enrolment?</td></tr>';
        else
            echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies whether the learner was in full time education or training prior to enrolment. To be used in conjunction with the employment status data to identify learners who were NEET (Not in education, employment or training)before starting learning." name="' . $id .'" id="' . $id .'" /></td><td>Was the learner in full time education or training prior to enrolment?</td></tr>';
        $xpath = $empstatus->xpath("EmploymentStatusMonitoring[ESMType='RON']");
        $ron = (empty($xpath[0]))?'':$xpath[0]->ESMCode->__toString();
        $id = "RON" . $index;
        //if ($ron == '1')
        //    echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies learners who are aged 14-15 and at risk of becoming NEET (Not in education, employment or training) for ESF funding and eligibility purposes." checked name="' . $id .'" id="' . $id .'" /></td><td>Is the learner aged 14-15 and at risk of becoming NEET?</td></tr>';
        //else
        //    echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies learners who are aged 14-15 and at risk of becoming NEET (Not in education, employment or training) for ESF funding and eligibility purposes." name="' . $id .'" id="' . $id .'" /></td><td>Is the learner aged 14-15 and at risk of becoming NEET?</td></tr>';
        $xpath = $empstatus->xpath("EmploymentStatusMonitoring[ESMType='SEM']");
        $sem = (empty($xpath[0]))?'':$xpath[0]->ESMCode->__toString();
        $id = "SEM" . $index;
        if ($sem == '1')
            echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies whether the employer recorded in the Employer identifier is a small employer as defined in the funding rules for Trailblazer apprenticeships." checked name="' . $id .'" id="' . $id .'" /></td><td>For trailblazer apprenticeships only is this a small employer?</td></tr>';
        else
            echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies whether the employer recorded in the Employer identifier is a small employer as defined in the funding rules for Trailblazer apprenticeships." name="' . $id .'" id="' . $id .'" /></td><td>For trailblazer apprenticeships only is this a small employer?</td></tr>';

        echo '</table></td></tr>';

        echo '<tr>';

        echo '<tr>';
        $xpath = $empstatus->xpath("EmploymentStatusMonitoring[ESMType='LOE']");
        $loe = (empty($xpath[0]))?'':$xpath[0]->ESMCode->__toString();
        $id = "LOE" . $index;
        $this->dynamic_field_display('LOE', HTML::select($id, $LOE_dropdown, $loe, true, true));

        $xpath = $empstatus->xpath("EmploymentStatusMonitoring[ESMType='EII']");
        $eii = (empty($xpath[0]))?'':$xpath[0]->ESMCode->__toString();
        $id = "EII" . $index;
        $this->dynamic_field_display('EII', HTML::select($id, $EII_dropdown, $eii, true, true));
        echo '</tr>';

        echo '<tr style="display:none;" class="emp_stat_' . $index . '">';
        $xpath = $empstatus->xpath("EmploymentStatusMonitoring[ESMType='LOU']");
        $lou = (empty($xpath[0]))?'':$xpath[0]->ESMCode->__toString();
        $id = "LOU" . $index;
        $this->dynamic_field_display('LOU', HTML::select($id, $LOU_dropdown, $lou, true, true));
        $xpath = $empstatus->xpath("EmploymentStatusMonitoring[ESMType='BSI']");
        $bsi = (empty($xpath[0]))?'':$xpath[0]->ESMCode->__toString();
        $id = "BSI" . $index;
        $this->dynamic_field_display('BSI', HTML::select($id, $BSI_dropdown, $bsi, true, true));
        echo '</tr>';

        echo '<tr style="display:none;" class="emp_stat_' . $index . '">';
        $xpath = $empstatus->xpath("EmploymentStatusMonitoring[ESMType='OET']");
        $oet = (empty($xpath[0]))?'':$xpath[0]->ESMCode->__toString();
        $id = "OET" . $index;
        $this->dynamic_field_display('OET', HTML::select($id, $OET_dropdown, $oet, true, true));
        echo '</tr>';

        echo '</table>';
        echo '</fieldset>';
        echo '</td></tr>';
        echo '</table>';
        if ($index == 1) {
            echo '</fieldset>';
        } elseif ($index == 2) {
            echo '</fieldset>';
        }
        echo '</div>';
    }
}


if ($index == -1) {

    echo '<h4>LLDD & Health Problems and Learner Funding and Monitoring</h4>';
    echo '<table border="0" cellspacing="4" cellpadding="4">';
    echo '<col width="150"/><col />';

    $index++;

    echo '<tr>';
    $id = "EmpStat" . $index;
    $this->dynamic_field_display("EmpStat", HTML::select($id, $EmpStat_dropdown, '', true, true));

    $id = "DateEmpStatApp" . $index;
    $this->dynamic_field_display("DateEmpStatApp", HTML::datebox($id, '', true, true));
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('EmpId', "<input class='compulsory validate[required]' type='text' value='' style='' id='EmpId$index' name='EmpId$index' maxlength=30 size=30>");
    $id = "LOE" . $index;
    $this->dynamic_field_display('LOE', HTML::select($id, $LOE_dropdown, '', true, true));
    //     $this->dynamic_field_display('WorkLocPostCode',"<input class='compulsory validate[required]' type='text' value='' style='background-color: white' id='WorkLocPostCode$index' name='WorkLocPostCode$index' maxlength=8 size=80>");
    echo '</tr>';

    echo '<tr>';
    echo '<td colspan="2">';
    echo '<table>';
    $id = "SEI" . $index;
    echo '<tr><td><input type="checkbox" class="tooltip" title="Indicates whether the learner is self employed." name="' . $id .'" id="' . $id .'" /></td><td>Is the learner self employed?</td></tr>';
    $id = "PEI" . $index;
    echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies whether the learner was in full time education or training prior to enrolment. To be used in conjunction with the employment status data to identify learners who were NEET (Not in education, employment or training)before starting learning." name="' . $id .'" id="' . $id .'" /></td><td>Was the learner in full time education or training prior to enrolment?</td></tr>';
    $id = "RON" . $index;
    //echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies learners who are aged 14-15 and at risk of becoming NEET (Not in education, employment or training) for ESF funding and eligibility purposes." name="' . $id .'" id="' . $id .'" /></td><td>Is the learner aged 14-15 and at risk of becoming NEET?</td></tr>';
    $id = "SEM" . $index;
    echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies whether the employer recorded in the Employer identifier is a small employer as defined in the funding rules for Trailblazer apprenticeships." name="' . $id .'" id="' . $id .'" /></td><td>For trailblazer apprenticeships only is this a small employer?</td></tr>';

    echo '</td></tr></table>';

    echo '<tr>';
    $id = "EII" . $index;
    $this->dynamic_field_display('EII', HTML::select($id, $EII_dropdown, '', true, true));
    echo '</tr>';

    echo '<tr>';
    $id = "LOU" . $index;
    $this->dynamic_field_display('LOU', HTML::select($id, $LOU_dropdown, '', true, true));

    $id = "BSI" . $index;
    $this->dynamic_field_display('BSI', HTML::select($id, $BSI_dropdown, '', true, true));
    echo '</tr>';

} else {
    $index++;
    echo '<table border="0" cellspacing="4" cellpadding="4" >';
    echo '<col width="394"/><col width="435" />';
    echo '<tr><td><span class="button"<a onclick="$(\'tr.new_emp_stat\').toggle();" >Add a new employment status</a></td><td>&nbsp;</td></tr>';

    echo '<tr style="display:none;" class="new_emp_stat">';


    $id = "EmpStat" . $index;
    $this->dynamic_field_display("EmpStat", HTML::select($id, $EmpStat_dropdown, '', true, true));

    $id = "DateEmpStatApp" . $index;
    $this->dynamic_field_display("DateEmpStatApp", HTML::datebox($id, '', true, true));
    echo '</tr>';

    echo '<table border="0" cellspacing="4" cellpadding="4">';
    echo '<col width="150"/><col />';

    echo '<tr style="display:none;" class="new_emp_stat">';
    $this->dynamic_field_display('EmpId', "<input class='compulsory validate[required]' type='text' value='' style='' id='EmpId$index' name='EmpId$index' maxlength=30 size=30>");
    //$this->dynamic_field_display('AgreeId', "<input class='compulsory validate[required]' type='text' value='' style='' id='AgreeId$index' name='AgreeId$index' maxlength=30 size=30>");
    echo '</tr>';

    echo '<tr style="display:none;" class="new_emp_stat">';
    echo '<td colspan="2">';
    echo '<table>';
    $id = "SEI" . $index;
    echo '<tr><td><input type="checkbox" class="tooltip" title="Indicates whether the learner is self employed." name="' . $id .'" id="' . $id .'" /></td><td>Is the learner self employed?</td></tr>';
    $id = "PEI" . $index;
    echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies whether the learner was in full time education or training prior to enrolment. To be used in conjunction with the employment status data to identify learners who were NEET (Not in education, employment or training)before starting learning." name="' . $id .'" id="' . $id .'" /></td><td>Was the learner in full time education or training prior to enrolment?</td></tr>';
    $id = "RON" . $index;
    //echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies learners who are aged 14-15 and at risk of becoming NEET (Not in education, employment or training) for ESF funding and eligibility purposes." name="' . $id .'" id="' . $id .'" /></td><td>Is the learner aged 14-15 and at risk of becoming NEET?</td></tr>';
    $id = "SEM" . $index;
    echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies whether the employer recorded in the Employer identifier is a small employer as defined in the funding rules for Trailblazer apprenticeships." name="' . $id .'" id="' . $id .'" /></td><td>For trailblazer apprenticeships only is this a small employer?</td></tr>';
    echo '</td></tr></table>';

    echo '<tr style="display:none;" class="new_emp_stat">';
    $id = "LOE" . $index;
    $this->dynamic_field_display('LOE', HTML::select($id, $LOE_dropdown, '', true, true));
    $id = "EII" . $index;
    $this->dynamic_field_display('EII', HTML::select($id, $EII_dropdown, '', true, true));
    echo '</tr>';

    echo '<tr style="display:none;" class="new_emp_stat">';
    $id = "LOU" . $index;
    $this->dynamic_field_display('LOU', HTML::select($id, $LOU_dropdown, '', true, true));

    $id = "BSI" . $index;
    $this->dynamic_field_display('BSI', HTML::select($id, $BSI_dropdown, '', true, true));
    echo '</tr>';

    echo '<tr style="display:none;" class="new_emp_stat">';
    $id = "OET" . $index;
    $this->dynamic_field_display('OET', HTML::select($id, $OET_dropdown, '', true, true));

    echo '</tr>';
    echo '</table>';

}
?>
</table>
</div>
</fieldset>
</div>
<div>
    <fieldset>
        <legend>Audit Trail</legend>
        <?php
        $sql = "SELECT * FROM ilr_audit Where tr_id = '$tr_id' and contrat_id = '$contract_id' Order by date DESC";
        $count = DAO::getSingleValue($link, "select count(*) from ilr_audit where tr_id = '$tr_id' and contrat_id = '$contract_id'");
        if ($count > 0) {
            $st = $link->query($sql);
            if ($st) {
                echo '<img src="/images/info-icon.png" alt="Break In Learning Learner" style="float: left; padding-right: 5px" height="20" width="20" />Audit entry is created as soon as ILR is saved. Please click on each audit entry to get the detailed information.';
                $c = 0;
                echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
                echo '<thead><tr><th>&nbsp;</th><th>System Username</th><th>Name</th><th>Date and Time (most recent first)</th><th>Status</th></tr></thead>';
                echo '<tbody>';
                $ids = array();
                while ($row = $st->fetch()) {
                    echo HTML::viewrow_opening_tag('do.php?_action=view_ilr_log_entry_details&amp;entry_id=' . $row['id']);
                    echo '<td align="center"><img height="80%" width = "80%" src="/images/event.jpg" /></td>';
                    echo '<td align="left">' . HTML::cell($row['username']) . "</td>";
                    $name_of_user = DAO::getSingleValue($link, "SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE username = '" . $row['username'] . "'");
                    echo '<td align="left">' . HTML::cell($name_of_user) . '</td>';
                    echo '<td align="left">' . HTML::cell(Date::to($row['date'], Date::DATETIME)) . "</td>";
                    $resultText = Ilr::getAuditDetails($link,$row['id']);
                    if($resultText=="")
                        echo '<td align="left">No change</td>';
                    else
                        echo '<td align="left">Changes were made</td>';

                    echo '</tr>';
                }
                echo '</table>';
            }
        } else {
            echo 'No Audit History Found.';
        }
        ?>
    </fieldset>
</div>
</div> <!-- the list of tabs to ul -->
</div> <!-- main tab finished (tabbed-nav) -->
</form>
<div id = "delivery_template"  class = "Unit" style="display: none;">
    <?php
    echo '<fieldset>';
    echo '<legend>Learning Information</legend>';
    echo '<table class="ilr" border="0" cellspacing="4" cellpadding="4">';
    echo '<col width="150"/><col />';
    echo '<tr>';
    $this->dynamic_field_display('AimType', HTML::select('AimType', $aimtype_dropdown, '', true, true));
    $this->dynamic_field_display('LearnAimRef', "<input class='compulsory validate[required]' type='text' value='' style='' id='LearnAimRef' name='LearnAimRef' maxlength=8 size=8>");
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('LearnStartDate', HTML::datebox('LearnStartDate', '', true, false));
    $this->dynamic_field_display('LearnPlanEndDate', HTML::datebox('LearnPlanEndDate', '', true, false));
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('OrigLearnStartDate', HTML::datebox('OrigLearnStartDate', '', true, false));
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('FundModel', HTML::select('FundModel', $FundModel_dropdown, '', true, true));
    $this->dynamic_field_display('ProgType', HTML::select('ProgType', $ProgType_dropdown, '', true, true));
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('FworkCode', HTML::select('FworkCode', $FworkCode_dropdown, '', true, true));
    $PwayCode_dropdown = DAO::getResultset($link, "SELECT DISTINCT PwayCode, CONCAT(PwayCode, ' ', COALESCE(PathwayName,'')) ,NULL FROM lars201718.Core_LARS_Framework ORDER BY FworkCode;", DAO::FETCH_NUM);
    $this->dynamic_field_display('PwayCode', HTML::select('PwayCode', $PwayCode_dropdown, '', true, false));
    echo '</tr>';
    echo '<tr><td colspan="2">';
    echo '<table>';
    echo '<tr><td><input class="tooltip" title="To identify whether the learner has restarted the learning aim." type="checkbox" name="RES" /></td><td>Is the aim a re-start?</td></tr>';
    echo '</table></td></tr>';
    echo '<tr>';
    $this->dynamic_field_display('PartnerUKPRN', "<input class='compulsory validate[required]' type='text' value='' style='' id='PartnerUKPRN' name='PartnerUKPRN' maxlength=8 size=8>");
    $this->dynamic_field_display('DelLocPostCode', "<input class='compulsory validate[required]' type='text' value='' style='' id='DelLocPostCode' name='DelLocPostCode' maxlength=8 size=8>");
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('PriorLearnFundAdj', "<input class='optional tooltip' type='text' value='' style='' id='PriorLearnFundAdj' name='PriorLearnFundAdj' maxlength=8 size=8 onKeyPress='return numbersonly99(this, event)'>");
    $this->dynamic_field_display('OtherFundAdj', "<input class='optional tooltip' type='text' value='' style='' id='OtherFundAdj' name='OtherFundAdj' maxlength=8 size=8>");
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('AddHours', "<input class='optional tooltip' type='text' value='' style='' id='AddHours' name='AddHours' maxlength=8 size=8 onKeyPress='return numbersonly(this, event)'>");
    $this->dynamic_field_display('ConRefNumber', "<input class='optional tooltip' type='text' value='' style='' id='ConRefNumber' name='ConRefNumber' maxlength=8 size=8>");
    echo '</tr>';

    echo '</table>';
    echo '</fieldset>';
    echo '<fieldset>';
    echo '<legend>Funding and Monitoring Information</legend>';
    echo '<table border="0" cellspacing="4" cellpadding="4">';
    echo '<col width="150"/><col />';
    echo '<tr><td colspan="2">';
    echo '<table>';
    echo '<tr><td><input type="checkbox" class="tooltip" title="To identify whether the programme or learning aim is classified as workplace learning as defined in the Skills Funding Agency\'s funding rules." name="WPL" /></td><td>Is the aim workplace learning?</td></tr>';
    echo '<tr><td><input type="checkbox" class="tooltip" title="Policy monitoring and development" name="FLN" /></td><td>Family English, Maths or Language learning aim delivered through the Adult Skills Budget?</td></tr>';
    echo '</tr>';
    echo '</table></td></tr>';
    echo '<tr>';
    $this->dynamic_field_display('SOF', HTML::select('SOF', $SOF_dropdown,'', true, true, true));
    $this->dynamic_field_display('FFI', HTML::select('FFI', $FFI_dropdown, '', true, true, true));
    echo '</tr>';

    $this->dynamic_field_display('EEF', HTML::select('EEF', $EEF_dropdown, '', true, false));
    echo '</tr>';

    echo '</table>';
    echo '</fieldset>';
    echo '<fieldset>';
    echo '<legend>Provider Specified Delivery Monitoring Information</legend>';
    echo '<table border="0" cellspacing="4" cellpadding="4">';
    echo '<col width="150"/><col />';
    echo '<tr>';
    $this->dynamic_field_display('ProvSpecDelMon', "<input class='optional' type='text' value='' style='' id='ProvSpecDelMonA' name='ProvSpecDelMonA' maxlength=12 size=30>");

    $this->dynamic_field_display('ProvSpecDelMon', "<input class='optional' type='text' value='' style='' id='ProvSpecDelMonB' name='ProvSpecDelMonB' maxlength=12 size=30>");
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('ProvSpecDelMon', "<input class='optional' type='text' value='' style='' id='ProvSpecDelMonC' name='ProvSpecDelMonC' maxlength=12 size=30>");

    $this->dynamic_field_display('ProvSpecDelMon', "<input class='optional' type='text' value='' style='' id='ProvSpecDelMonD' name='ProvSpecDelMonD' maxlength=12 size=30>");
    echo '</tr>';
    echo '</table>';
    echo '</fieldset>';

    echo '</fieldset>';
    echo '<fieldset>';
    echo '<legend>Learning End Information</legend>';
    echo '<table border="0" cellspacing="4" cellpadding="4">';
    echo '<col width="150"/><col />';

    echo '<tr>';
    $this->dynamic_field_display('LearnActEndDate', HTML::datebox('LearnActEndDate', ''));
    $this->dynamic_field_display('AchDate', HTML::datebox('AchDate', ''));
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('CompStatus', HTML::select('CompStatus', $CompStatus_dropdown, '', false, true));
    $this->dynamic_field_display('WithdrawReason', HTML::select('WithdrawReason', $WithdrawReason_dropdown, '', true, true));
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('Outcome', HTML::select('Outcome', $Outcome_dropdown, '', true, true));
    $this->dynamic_field_display('EmpOutcome', HTML::select('EmpOutcome', $EmpOutcome_dropdown, '', true, true));
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('OutGrade', HTML::select('OutGrade', $OutGrade_dropdown, '', true, true));
    echo '</tr>';


    echo '</table></fieldset>';
    ?>
</div>
<script>
    jQuery(document).ready(function ($) {
        /* jQuery activation and setting options for the tabs*/
        var tabbedNav = $("#tabbed-nav3").zozoTabs({
                    position: "top-left",
                    theme: "green",
                    rounded: true,
                    shadows: true,
                    defaultTab: "tab1",
                    autoContentHeight: true,
                    animation:{
                        easing:"easeInOutCirc",
                        effects:"slideV"
                    },
                    size:"medium"
                }),
                getItem = function () {
                    return $("#tabIndex").val();
                },
                select = function (e) {
                    tabbedNav.data("zozoTabs").select(getItem());
                },
                add = function (e) {
                    var newAim = document.getElementById('delivery_template').cloneNode(true);
                    tabbedNav.data("zozoTabs").add("New Aim", document.getElementById("deliveriesTab").appendChild(newAim));
                    a=0;
                    $('.Unit').each(function() {a++; $(this).attr('id',('tab'+a))});
                    tabbedNav.data("zozoTabs").last();
                },
                remove = function (e) {
                    p = confirm("Do you want to remove this aim?");
                    if(p)
                    {
                        tabbedNav.data("zozoTabs").remove($(".z-content-pad > ul > li.z-active").index()+1);
                        a=0;
                        $('.Unit').each(function() {a++; $(this).attr('id',('tab'+a))});
                    }
                };

        $(".selectTab").click(select);
        $(".addTab").click(add);
        $(".removeTab").click(remove);
    });

</script>
<form  name="pdf" id="pdf" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <input type="hidden" name="_action" value="export_ilr_to_pdf" />
    <input type="hidden" name="xml" value="" />
    <input type="hidden" name="contract_id" value="<?php echo $contract_id;?>" />
    <input type="hidden" name="tr_id" value="<?php echo $tr_id;?>" />
</form>
</body>
</html>
