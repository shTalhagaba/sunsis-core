<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>
<?php
$planned_reviews_start_date = $tr->practical_period_start_date;
$planned_reviews_end_date = $tr->practical_period_end_date;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Onboarding</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/css/common.css">
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link href="/assets/adminlte/plugins/JQuerySteps/jquery.steps.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">

    <style type="text/css">
        .loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 1000;
            background: url('images/progress-animations/loading51.gif') 50% 50% no-repeat rgba(255, 255, 255, .8);
        }

        .disabledRow {
            pointer-events: none;
            opacity: 0.7;
        }

    </style>

    <script type="text/javascript">
        var phpHeaderLogo1 = '<?php echo $ob_header_image1; ?>';
        var phpHeaderLogo2 = '<?php echo $ob_header_image2; ?>';
        var phpScrolLogic = '<?php echo $scroll_logic; ?>';
        var phpAiY = '<?php echo DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(YEAR, dob, CURDATE()) FROM ob_learners WHERE id = '{$ob_learner->id}'"); ?>';
    </script>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/JQuerySteps/jquery.steps.js"></script>
    <script src="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

</head>


<body>

<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">View/Sign Apprenticeship Enrolment / Training Plan</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <!--                <span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>-->
            </div>
            <div class="ActionIconBar">

            </div>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>
<div class="container-fluid">
    <div class="nts-secondary-teaser-gradient">
        <div class="container"><h3>Enrolment Form / Training Plan</h3></div>
    </div>
    <br>

    <!--    <span class="btn btn-primary" onclick="$('#frmOnboarding').submit();">Save</span>-->

    <div class="container-fluid" style="font-size: medium">

        <div id="loading" title="Please wait"></div>

        <form class="form-horizontal" name="frmOnboarding" id="frmOnboarding"
              action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off"
              enctype="multipart/form-data">
            <input type="hidden" name="_action" value="save_onboarding"/>
            <input type="hidden" name="id" value="<?php echo $tr->id; ?>"/>

            <h3>Privacy Notice & GDPR</h3>
            <step id="step1">
                <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                    <h4>Privacy Notice & GDPR</h4>
                </div>
                <br>

                <div style="text-align: justify;
  text-justify: inter-word;">
                    <h4 class="text-bold"><u>How We Use Your Personal Information</u></h4>
                    <p>
                        This privacy notice is issued by the Department for Education (DfE), on behalf of the Secretary of
                        State for the Department of Education (DfE). It is to inform learners how their personal information will be
                        used by the DfE and any successor bodies to these organisations.
                        For the purposes of relevant data protection legislation, the DfE is the data controller
                        for personal data processed by the Department for Education (DfE). Your personal information is used by the DfE to exercise its functions
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
                        <img height="50px" style="background-color: #ffffff;" class="pull-right" src="<?php echo $provider->provider_logo; ?>" />
                        <h4>GDPR</h4>How we use your personal data
                    </div>
                    <div class="well">
                        <p>As you are aware <?php echo $provider->legal_name; ?> is your training provider. We want to be transparent with you about how we collect, process and store your data</p>
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
                                        You can <u>agree</u> to be contacted for other purposes by ticking any of the following boxes:
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo in_array(1, $selected_rui) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>';?>
                                        <label>About courses or learning opportunities.</label>
                                        <br>
                                        <?php echo in_array(2, $selected_rui) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>';?>
                                        <label>For surveys and research.</label>
                                    </td>
                                    <td>
                                        <?php echo in_array(1, $selected_pmc) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>';?>
                                        <label>By post</label>
                                        <br>
                                        <?php echo in_array(2, $selected_pmc) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>';?><label>By phone</label>
                                        <br>
                                        <?php echo in_array(3, $selected_pmc) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>';?><label>By email</label>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>



                    <div class="table-responsive">
                        <table class="table table-bordered  text-blue">
                            <tr>
                                <th>
                                    <u>Consent</u>:
                                </th>
                            </tr>
                            <tr class="bg-gray">
                                <td>
                                    <?php echo in_array(1, $selected_disclaimer) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>';?><label>I agree to adhere to the rules and regulations of the Data Protection Act 1998 and the Freedom of Information Act 2000, ensuring high standards in the returning and communication of personal information and giving  a general right of access to all recorded information held by public authorities, including educational establishments.</label>
                                </td>
                            </tr>
                            <tr class="bg-gray">
                                <td>
                                    <?php echo in_array(2, $selected_disclaimer) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>';?><label>I agree to promote and adhere to Equal Opportunity and Diversity policies on race, gender, age, disability, religion or belief and sexual orientation within the Apprenticeship Programme.</label>
                                </td>
                            </tr>
                            <tr class="bg-gray">
                                <td>
                                    <?php echo in_array(3, $selected_disclaimer) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>';?><label>I have read and understood GDPR statement regarding my personal data.</label>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>

            </step>

            <h3>Personal Details</h3>
            <step id="step2">
                <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                    <h4>Personal Details</h4>
                </div>
                <br>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="learner_title" class="col-sm-4 control-label ">Title:</label>
                        <div class="col-sm-8 fieldValue">
                            <?php echo isset($titlesDDl[$ob_learner->learner_title]) ? $titlesDDl[$ob_learner->learner_title] : $ob_learner->learner_title; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="firstnames" class="col-sm-4 control-label ">First Name(s):</label>
                        <div class="col-sm-8 fieldValue">
                            <?php echo $ob_learner->firstnames; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="surname" class="col-sm-4 control-label ">Surname:</label>
                        <div class="col-sm-8 fieldValue">
                            <?php echo $ob_learner->surname; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_dob" class="col-sm-4 control-label ">Date of Birth:</label>
                        <div class="col-sm-8 fieldValue">
                            <?php echo Date::toShort($ob_learner->dob); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ethnicity" class="col-sm-4 control-label fieldLabel_optional">Ethnicity:</label>
                        <div class="col-sm-8 fieldValue">
                            <?php echo isset($ethnicityDDL[$ob_learner->ethnicity]) ? $ethnicityDDL[$ob_learner->ethnicity] : $ob_learner->ethnicity; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="gender" class="col-sm-4 control-label ">Gender:</label>
                        <div class="col-sm-8 fieldValue">
                            <?php
                            $genders = LookupHelper::getListGender();
                            echo isset($genders[$ob_learner->gender]) ? $genders[$ob_learner->gender] : $ob_learner->gender;
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ni" class="col-sm-4 control-label ">National Insurance:</label>
                        <div class="col-sm-8 fieldValue">
                            <?php echo $ob_learner->ni; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hhs" class="col-sm-4 control-label ">Household Situation:</label>
                        <div class="col-sm-8 fieldValue">
                            <?php
                            $hhs_list = LookupHelper::getListHhs();
                            $selected_hhs = explode(",", $tr->hhs);
                            foreach($selected_hhs AS $_v)
                                echo isset($hhs_list[$_v]) ? $hhs_list[$_v] . '<br>' : $_v . '<br>';
                            ?>
                        </div>
                    </div>
                    <div class="form-group small">
                        <label for="LLDD" class="col-sm-4 control-label ">Do you consider yourself to have a learning difficulty, health problem or disability?:</label>
                        <div class="col-sm-8 fieldValue">
                            <?php echo isset($LLDD[$tr->LLDD]) ? $LLDD[$tr->LLDD] : $tr->LLDD; ?>
                        </div>
                    </div>
                    <?php if($tr->LLDD == "Y") { ?>
                        <div class="form-group" id="divLLDDCat" >
                            <div class="col-sm-12" style="max-height: 300px; overflow-y: scroll;">
                                <label>categories:</label>
                                <table class="text-center table table-condensed table-bordered">
                                    <tr><th>Category</th><th>Primary</th></tr>
                                    <?php
                                    foreach($selected_llddcat AS $_v)
                                    {
                                        echo '<tr>';
                                        echo isset($LLDDCat[$_v]) ? '<td>' . $LLDDCat[$_v] . '</td>' : '<td>' . $_v . '</td>';
                                        echo $_v == $tr->primary_lldd ? '<td>Yes</td>' : '<td></td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="home_address_line_1" class="col-sm-4 control-label ">Address Line 1:</label>
                        <div class="col-sm-8 fieldValue">
                            <?php echo $tr->home_address_line_1; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="home_address_line_2" class="col-sm-4 control-label fieldLabel_optional">Address Line 2:</label>
                        <div class="col-sm-8 fieldValue">
                            <?php echo $tr->home_address_line_2; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="home_address_line_3" class="col-sm-4 control-label ">City:</label>
                        <div class="col-sm-8 fieldValue">
                            <?php echo $tr->home_address_line_3; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="home_address_line_4" class="col-sm-4 control-label fieldLabel_optional">County:</label>
                        <div class="col-sm-8 fieldValue">
                            <?php echo $tr->home_address_line_4; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="home_postcode" class="col-sm-4 control-label ">Postcode:</label>
                        <div class="col-sm-8 fieldValue">
                            <?php echo $tr->home_postcode == '' ? $ob_learner->home_postcode : $tr->home_postcode; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="home_telephone" class="col-sm-4 control-label fieldLabel_optional">Telephone:</label>
                        <div class="col-sm-8 fieldValue">
                            <?php echo $tr->home_telephone; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="home_mobile" class="col-sm-4 control-label fieldLabel_optional">Mobile Phone:</label>
                        <div class="col-sm-8 fieldValue">
                            <?php echo $tr->home_mobile; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="home_email" class="col-sm-4 control-label fieldLabel_optional">Email:</label>
                        <div class="col-sm-8 fieldValue">
                            <?php echo $tr->home_email; ?>
                        </div>
                    </div>
                </div>

            </step>

            <h3>Emergency Contacts</h3>
            <step id="step3">
                <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                    <h4>Emergency Contacts</h4>
                </div>
                <br>

                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered">
                            <caption>
                                Enter details of your emergency contacts
                            </caption>
                            <tr>
                                <th>Title</th>
                                <th>Full Name</th>
                                <th>Relationship</th>
                                <th>Telephone</th>
                                <th>Mobile</th>
                            </tr>
                            <?php
                            $emergency_contacts_result = DAO::getResultset($link, "SELECT * FROM ob_learner_emergency_contacts WHERE tr_id = '{$tr->id}'", DAO::FETCH_ASSOC);
                            foreach($emergency_contacts_result AS $row_emergency_contact_row)
                            {
                                echo '<tr>';
                                echo '<td>'.$row_emergency_contact_row['em_con_title'].'</td>';
                                echo '<td>'.$row_emergency_contact_row['em_con_name'].'</td>';
                                echo '<td>'.$row_emergency_contact_row['em_con_rel'].'</td>';
                                echo '<td>'.$row_emergency_contact_row['em_con_tel'].'</td>';
                                echo '<td>'.$row_emergency_contact_row['em_con_mob'].'</td>';
                                echo '</tr>';
                            }
                            ?>
                        </table>
                    </div>
                </div>

            </step>

            <h3>ALS</h3>
            <step id="step4">
                <?php
                $als_records = DAO::getResultset($link, "SELECT * FROM ob_learner_als WHERE tr_id = '{$tr->id}' ORDER BY id", DAO::FETCH_ASSOC);
                ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div>
                            <table class="table table-responsive row-border cw-table-list">
                                <tr>
                                    <th style="width: 15%;">Date Discussed</th>
                                    <th style="width: 15%;">Support Required</th>
                                    <th style="width: 20%;">Details</th>
                                    <th style="width: 20%;">Date Claimed From</th>
                                    <th style="width: 30%;">Additional Info.</th>
                                </tr>
                                <tbody>
                                <?php
                                if(count($als_records) == 0)
                                    echo '<tr><td colspan="5"><i>No records.</i></td></tr>';
                                foreach($als_records AS $als_row)
                                {
                                    $als_row = (object)$als_row;
                                    echo '<tr>';
                                    echo '<td>' . Date::toShort($als_row->date_discussed) . '</td>';
                                    echo $als_row->support_required == 'Y' ? '<td>Yes</td>' : '<td>No</td>';
                                    echo '<td>' . HTML::cell($als_row->details) . '</td>';
                                    echo '<td>' . Date::toShort($als_row->date_claimed_from) . '</td>';
                                    echo '<td>' . HTML::cell($als_row->additional_info) . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </step>

            <h3>Prior Attainment</h3>
            <step id="step5">
                <div class="row">
                    <div class="col-sm-12">
                        <div>
                            <table class="table table-responsive row-border cw-table-list">
                                <tr><th style="width: 25%;">GCSE/A/AS Level</th><th style="width: 25%;">Subject</th><th style="width: 15%;">Predicted Grade</th><th style="width: 15%;">Actual Grade</th><th style="width: 20%;">Date Completed</th></tr>
                                <tbody>
                                <?php
                                $ob_high = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND q_type = 'h'");
                                if(isset($ob_high->level) && isset($PriorAttainDDL[$ob_high->level]))
                                    echo '<tr><td colspan="5"><span class="text-bold">Prior Attainment Level: </span>' . $PriorAttainDDL[$ob_high->level] . '</td></tr>';
                                ?>
                                <tr>
                                    <?php $ob_eng = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND level = '101'"); ?>
                                    <td>GCSE </td>
                                    <td>English Language</td>
                                    <td>
                                        <?php $qual_grades = DAO::getLookupTable($link,"SELECT id, description FROM lookup_gcse_grades WHERE description NOT IN ('Credit', 'Distinction*') ORDER BY id;");
                                        echo isset($qual_grades[$ob_eng->p_grade]) ? $qual_grades[$ob_eng->p_grade] : $ob_eng->p_grade;
                                        ?>
                                    </td>
                                    <td><?php echo isset($ob_eng->a_grade)?$ob_eng->a_grade:''; ?></td>
                                    <td><?php echo isset($ob_eng->date_completed)?Date::toShort($ob_eng->date_completed):''; ?></td>
                                </tr>
                                <tr>
                                    <?php $ob_maths = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND level = '102'");?>
                                    <td>GCSE </td>
                                    <td>Maths</td>
                                    <td><?php echo isset($qual_grades[$ob_maths->p_grade]) ? $qual_grades[$ob_maths->p_grade] : $ob_maths->p_grade; ?></td>
                                    <td><?php echo isset($ob_maths->a_grade)?$ob_maths->a_grade:''; ?></td>
                                    <td><?php echo isset($ob_maths->date_completed)?Date::toShort($ob_maths->date_completed):''; ?></td>
                                </tr>
                                <tr>
                                    <?php $ob_ict = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND level = '103'");?>
                                    <td>GCSE </td>
                                    <td>Maths</td>
                                    <td><?php echo (isset($ob_ict->p_grade) && isset($qual_grades[$ob_ict->p_grade])) ? $qual_grades[$ob_ict->p_grade] : ''; ?></td>
                                    <td><?php echo isset($ob_ict->a_grade)?$ob_ict->a_grade:''; ?></td>
                                    <td><?php echo isset($ob_ict->date_completed)?Date::toShort($ob_ict->date_completed):''; ?></td>
                                </tr>
                                <?php
                                for($i = 1; $i <= 15; $i++)
                                {
                                    $ob_q = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND q_type = '{$i}'");
                                    echo '<tr>';
                                    echo (isset($ob_q->level) && isset($QualLevelsDDL[$ob_q->level])) ? '<td>' . $QualLevelsDDL[$ob_q->level] . '</td>' : '<td></td>';
                                    if(isset($ob_q->subject))
                                        echo '<td>' . $ob_q->subject . '</td>';
                                    else
                                        echo '<td></td>';
                                    echo isset($ob_q->p_grade) ? '<td>' . isset($ob_q->p_grade) . '</td>' : '<td></td>';
                                    echo isset($ob_q->a_grade) ? '<td>' . isset($ob_q->a_grade) . '</td>' : '<td></td>';
                                    if(isset($ob_q->date_completed))
                                        echo '<td>'.Date::toShort($ob_q->date_completed).'</td>';
                                    else
                                        echo '<td></td>';
                                    echo '</tr>';
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </step>

            <h3>Eligibility</h3>
            <step id="step5">
                <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                    <h4>Eligibility</h4>
                </div>
                <br>

                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered table-condensed">
                            <col style="width: 60%;">
                            <tbody>
                            <tr>
                                <td>Have you lived within the UK/EEA or EU for the last 3 Years?</td>
                                <td><?php echo in_array(1, $saved_eligibility_list)?'Yes':'No'; ?></td>
                            </tr>
                            <tr>
                                <td>Are you currently enrolled at any other college, or training provider?</td>
                                <td><?php echo in_array(2, $saved_eligibility_list)?'Yes':'No'; ?></td>
                            </tr>
                            <tr>
                                <td>If yes, please give details:</td>
                                <td><?php echo $tr->currently_enrolled_in_other; ?></td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered table-condensed">
                            <col style="width: 60%;">
                            <tbody>
                            <tr>
                                <td>Have you previously had access to a student loan?</td>
                                <td><?php echo $tr->had_student_loan == 1 ? 'Yes' : ($tr->had_student_loan == 2 ? 'No' : ''); ?></td>
                            </tr>
                            <tr>
                                <td>If Yes can you confirm this has been terminated?</td>
                                <td><?php echo $tr->student_loan_terminated == 1 ? 'Yes' : ($tr->student_loan_terminated == 2 ? 'No' : ''); ?></td>
                            </tr>
                            <tr>
                                <td>Have you been asked to contribute to the cost of your apprenticeship?</td>
                                <td><?php echo $tr->asked_to_contribute == 1 ? 'Yes' : ($tr->asked_to_contribute == 2 ? 'No' : ''); ?></td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered table-condensed">
                            <tbody>
                            <tr>
                                <td>Country of birth:</td>
                                <td><?php echo isset($countries[$tr->country_of_birth]) ? $countries[$tr->country_of_birth] : $tr->country_of_birth; ?></td>
                                <td>Country of permanent residence:</td>
                                <td><?php echo isset($countries[$tr->country_of_perm_residence]) ? $countries[$tr->country_of_perm_residence] : $tr->country_of_perm_residence; ?></td>
                            </tr>
                            <tr>
                                <td>Nationality:</td>
                                <td><?php echo isset($countries[$tr->nationality]) ? $countries[$tr->nationality] : $tr->nationality; ?></td>
                                <td>Please provide a copy of your passport or birth certificate:</td>
                                <td>
                                    <?php
                                    if(is_file($learner_directory . $tr->evidence_pp_file))
                                    {
                                        $evidence_pp_file = new RepositoryFile($learner_directory . $tr->evidence_pp_file);
                                        echo '<a href="' . $evidence_pp_file->getDownloadURL() . '">' . $tr->evidence_pp_file . '</a>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <table class="table row-border">
                            <tbody>
                            <tr>
                                <td>Do you have a valid National Insurance Number?</td>
                                <td><?php echo in_array(3, $saved_eligibility_list)?'Yes':'No'; ?></td>
                            </tr>
                            <tr>
                                <td>Are you attending School or College for any other Further or Higher Education training apart from this apprenticeship?</td>
                                <td><?php echo in_array(4, $saved_eligibility_list)?'Yes':'No'; ?></td>
                            </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered table-condensed">
                            <tbody>
                            <tr style="background-color: #d3d3d3;"><th colspan="2">Applicants not born in the United Kingdom, please answer the following questions</th></tr>
                            <tr>
                                <td>Are you a non-EU citizen currently resident in the UK?</td>
                                <td><?php echo in_array(5, $saved_eligibility_list)?'Yes':'No'; ?></td>
                            </tr>
                            <tr><td colspan="2">If you have checked the box, please provide the following information in order to assist us in making an assessment of your fee status.</td></tr>
                            <tr><td>Date of first entry to the UK:</td><td><?php echo Date::toShort($tr->date_of_first_uk_entry); ?></td></tr>
                            <tr><td>Date of most recent entry to the UK (excluding holidays):</td><td><?php echo Date::toShort($tr->date_of_most_recent_uk_entry); ?></td></tr>
                            <tr>
                                <td>Have you been granted indefinite Leave to Enter/Remain in the UK? If yes, please provide a copy of your ILR status as evidence.</td>
                                <td>
                                    <?php
                                    if(is_file($learner_directory . $tr->evidence_ilr_file))
                                    {
                                        $evidence_ilr_file = new RepositoryFile($learner_directory . $tr->evidence_ilr_file);
                                        echo '<a href="' . $evidence_ilr_file->getDownloadURL() . '">' . $tr->evidence_ilr_file . '</a>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered table-condensed">
                            <tbody>
                            <tr>
                                <td>Do you need a visa to study in the UK?</td>
                                <td><?php echo in_array(6, $saved_eligibility_list)?'Yes':'No'; ?></td>
                            </tr>
                            <tr><td>If you have checked the box, please provide your passport number:</td><td><?php echo $tr->passport_number; ?></td></tr>
                            <tr><td>If no, under what immigration category will you enter the UK:</td><td><?php echo $tr->immigration_category; ?></td></tr>
                            <tr>
                                <td>Have you previously been granted a visa to study in the UK? If yes, please upload a copy of any such visas.</td>
                                <td>
                                    <?php
                                    if(is_file($learner_directory . $tr->evidence_previous_uk_study_visa_file))
                                    {
                                        $evidence_previous_uk_study_visa_file = new RepositoryFile($learner_directory . $tr->evidence_previous_uk_study_visa_file);
                                        echo '<a href="' . $evidence_previous_uk_study_visa_file->getDownloadURL() . '">' . $tr->evidence_previous_uk_study_visa_file . '</a>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>


                    </div>
                </div>

            </step>

            <h3>Employment Status</h3>
            <step id="step6">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                            <h4>Employment Status Questionnaire</h4>
                        </div><br>
                        <p class="">Please tell us more about what you did prior to starting your Apprenticeship Programme on the <label><?php echo Date::toLong($tr->apprenticeship_start_date); ?></label>.</p>
                        <div class="form-group">
                            <label for="EmploymentStatus" class="col-sm-4 control-label fieldLabel_optional">Were you</label>
                            <div class="col-sm-8 fieldValue">
                                <?php
                                $ipe = ''; $nipn = ''; $nipl = ''; $nk = '';
                                if($tr->EmploymentStatus == '10') $ipe = '<i class="fa fa-check"></i> ';
                                if($tr->EmploymentStatus == '11') $nipn = '<i class="fa fa-check"></i> ';
                                if($tr->EmploymentStatus == '12') $nipl = '<i class="fa fa-check"></i> ';
                                if($tr->EmploymentStatus == '98') $nk = '<i class="fa fa-check"></i> ';
                                ?>
                                <p><?php echo $ipe; ?> In paid employment</p>
                                <p><?php echo $nipn; ?> Not in paid employment, looking for work and available to start work</p>
                                <p><?php echo $nipl; ?> Not in paid employment, not looking for work and/or not available to start work</p>
                                <p><?php echo $nk; ?> Not known / don't want to provide</p>
                            </div>
                        </div>
                        <table id="tbl_emp_status_10" class="table table-bordered table-condensed" <?php echo $tr->EmploymentStatus != "10" ? 'style="display: none;'  : ''; ?>">
                        <?php
                        $work_curr_emp_checked = '';
                        if($tr->EmploymentStatus == '10' && $tr->work_curr_emp == '1') $work_curr_emp_checked = 'Yes';
                        $SEI_checked = '';
                        if($tr->EmploymentStatus == '10' && $tr->SEI == '1') $SEI_checked = 'Yes';
                        $PEI_checked = '';
                        if(($tr->EmploymentStatus == '11' || $tr->EmploymentStatus == '12') && $tr->PEI == '1') $PEI_checked = 'Yes';
                        $SEM_checked = '';
                        if($tr->EmploymentStatus == '10' && $tr->SEM == '1') $SEM_checked = 'Yes';
                        ?>
                        <tr>
                            <th>Were you employed with your current employer<br>prior to you starting your Apprenticeship Programme?</th>
                            <td><?php echo $work_curr_emp_checked; ?></td>
                        </tr>
                        <tr>
                            <th>If not, were you self-employed?</th>
                            <td><?php echo $SEI_checked; ?></td>
                        </tr>
                        <tr>
                            <th>Tell us your Employer Name?</th>
                            <td><?php echo $tr->empStatusEmployer; ?></td>
                        </tr>
                        <!--<tr>
                                <th>Was the company a Small Employer with less than 50 employees?</th>
                                <td><?php /*echo $SEM_checked; */?></td>
                            </tr>-->
                        <tr>
                            <th>How long were you employed?</th>
                            <td><?php echo isset($LOE_dropdown[$tr->LOE]) ? $LOE_dropdown[$tr->LOE] : $tr->LOE; ?></td>
                        </tr>
                        <tr>
                            <th>How many hours did you work each week?</th>
                            <td><?php echo isset($EII_dropdown[$tr->EII]) ? $EII_dropdown[$tr->EII] : $tr->EII; ?></td>
                        </tr>
                        </table>
                        <table id="tbl_emp_status_11_12" class="table table-bordered table-condensed"  <?php echo !in_array($tr->EmploymentStatus, [11, 12]) ? 'style="display: none;'  : ''; ?>">
                        <tr>
                            <th>How long were you un-employed before <label class="text-blue"><?php echo Date::toLong($tr->apprenticeship_start_date); ?></label>?</th>
                            <td><?php echo isset($LOU_dropdown[$tr->LOU]) ? $LOU_dropdown[$tr->LOU] : $tr->LOU; ?></td>
                        </tr>
                        <tr>
                            <th>Did you receive any of these benefits?</th>
                            <td><?php echo isset($BSI_dropdown[$tr->BSI]) ? $BSI_dropdown[$tr->BSI] : $tr->BSI; ?></td>
                        </tr>
                        <tr>
                            <th>Were you in Full Time Education or Training prior to <label class="text-blue"><?php echo Date::toLong($tr->apprenticeship_start_date); ?></label>?</th>
                            <td><?php echo $PEI_checked; ?></td>
                        </tr>
                        </table>

                    </div>
                </div>

            </step>

            <h3>Care Leaver</h3>
            <step id="step7">

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
                                <?php echo $tr->ehc_plan == "1" ? 'Yes' : 'No';?>
                            </div>
                        </div>
                        <div class="form-group divEhcPlanEvidence" <?php echo $tr->ehc_plan == "1" ? '' : 'style="display: none;"';?> >
                            <label for="ehc_plan" class="col-sm-6 control-label fieldLabel_optional">Upload your Education Health Care Plan:</label>
                            <div class="col-sm-6">
                                <?php
                                if(is_file($learner_directory . $tr->ehc_evidence_file))
                                {
                                    $ehc_evidence_file = new RepositoryFile($learner_directory . $tr->ehc_evidence_file);
                                    echo '<a href="' . $ehc_evidence_file->getDownloadURL() . '">' . $tr->ehc_evidence_file . '</a>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="care_leaver" class="col-sm-6 control-label fieldLabel_optional">Are you a care leaver?:</label>
                            <div class="col-sm-6">
                                <?php echo $tr->care_leaver == "1" ? 'Yes' : 'No';?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" <?php echo $tr->care_leaver != '1' ? 'style="display: none;"' : '' ;?> id="divCareLeaverInfo">
                    <div class="col-sm-12">

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Can you please confirm that you been in the care of a UK local authority?</th>
                                    <td><?php echo $care_leaver_details->in_care_of_local_authority == "1" ? 'Yes' : 'No';?></td>
                                </tr>
                                <tr>
                                    <th>As a care leaver, you are eligible to receive a �1,000 bursary payment. Please confirm whether you would like to access this bursary?</th>
                                    <td><?php echo $care_leaver_details->eligible_for_bursary_payment == "1" ? 'Yes' : 'No';?></td>
                                </tr>
                                <tr>
                                    <th>Do you give consent to inform your employer that you have been in the care of a UK local authority?<br>
                                        (If yes, your declaration will be used to generate additional payments to both the main provider and your employer to support your transition into work).</th>
                                    <td><?php echo $care_leaver_details->give_consent_to_inform_employer == "1" ? 'Yes' : '';?></td>
                                </tr>
                                <tr>
                                    <th>We will need evidence of one of the following: please select</th>
                                    <td>
                                        <?php
                                        $ddlCareaLeaverEvidenceTypes = [
                                            1 => 'Signed Email from a local authority appointed personal advisor confirming that you are a care leaver.',
                                            2 => 'Letter from a local authority appointed personal advisor confirming that you are a care leaver.',
                                        ];
                                        echo isset($ddlCareaLeaverEvidenceTypes[$care_leaver_details->in_care_evidence]) ? $ddlCareaLeaverEvidenceTypes[$care_leaver_details->in_care_evidence] : $care_leaver_details->in_care_evidence;
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Upload evidence for care leaver</th>
                                    <td>
                                        <?php
                                        if(is_file($learner_directory . $tr->in_care_evidence_file))
                                        {
                                            $in_care_evidence_file = new RepositoryFile($learner_directory . $tr->in_care_evidence_file);
                                            echo '<a href="' . $in_care_evidence_file->getDownloadURL() . '">' . $tr->in_care_evidence_file . '</a>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2">Care Leaver Bank Details</th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <p>Learners who are eligible for the �1,000 Care Leaver Bursary will need to provide their bank details so that the bursary can be paid directly to them.</p>
                                        <p>Learners who are not eligible for this bursary are not required to provide their bank details. </p>
                                        <p>By providing these details, I confirm that <?php echo $provider->legal_name; ?> are authorised to pay the Care Leaver Bursary payment, when due, into the account as detailed below.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Name of Bank</th>
                                    <td><?php echo $care_leaver_details->care_leaver_bank_name; ?></td>
                                </tr>
                                <tr>
                                    <th>Account Name</th>
                                    <td><?php echo $care_leaver_details->care_leaver_account_name; ?></td>
                                </tr>
                                <tr>
                                    <th>Sort Code</th>
                                    <td><?php echo $care_leaver_details->care_leaver_sort_code; ?></td>
                                </tr>
                                <tr>
                                    <th>Account Number</th>
                                    <td><?php echo $care_leaver_details->care_leaver_account_number; ?></td>
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
                                        <?php echo in_array(1, $selected_disclaimer) ? '<i class="fa fa-check"></i> ' : '';?> <label>I understand that I am eligible for and would like to receive a bursary as a care leaver.</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo in_array(2, $selected_disclaimer) ? '<i class="fa fa-check"></i> ' : '';?><label>I understand that if I have been found to have accepted the payment incorrectly or if I am ineligible then the government will require it to be repaid.</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo in_array(3, $selected_disclaimer) ? '<i class="fa fa-check"></i> ' : '';?><label>I have not been paid a care leavers bursary before. This only includes the care leavers bursary paid by the Department for Education (DfE); other local incentives do not apply.</label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>


            </step>

            <h3>Criminal Convictions</h3>
            <step id="step8">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                            <h4>Details of Criminal Convictions</h4>
                        </div><br>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="have_criminal_conviction" class="col-sm-4 control-label ">Do you have criminal conviction:</label>
                            <div class="col-sm-8 fieldValue">
                                <?php
                                $yes_no_list = LookupHelper::getListYesNo();
                                echo isset($yes_no_list[$criminal_conviction_details->have_criminal_conviction]) ? $yes_no_list[$criminal_conviction_details->have_criminal_conviction] : $criminal_conviction_details->have_criminal_conviction;
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="motoring_offence" class="col-sm-4 control-label ">Is it a motoring offence:</label>
                            <div class="col-sm-8 fieldValue">
                                <?php
                                $yes_no_list = LookupHelper::getListYesNo();
                                echo isset($yes_no_list[$criminal_conviction_details->is_it_motoring_conviction]) ? $yes_no_list[$criminal_conviction_details->is_it_motoring_conviction] : $criminal_conviction_details->is_it_motoring_conviction;
                                ?>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="callout callout-default text-info">
                            <p>Please Note: You are not required to include details of criminal conviction/s which are spent in accordance
                                with the Rehabilitation of Offenders Act 1974.  The National Association for the Care & Resettlement of
                                Offenders (NACRO), the Youth Offending Service, the Probation Service and the Citizen's Advice Bureau
                                are able to give advice on whether convictions are spent.  If you are applying to study on a course where
                                an Enhanced DBS Check is required, please state convictions which are �Spent� and �Unspent�, including Warnings,
                                Reprimands, Cautions or Referral Orders.</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-responsive table-bordered table-condensed cw-table-list ">
                            <tr class="bg-gray">
                                <th style="width: 15%;">Date of conviction</th>
                                <th style="width: 15%;">Nature of offence</th>
                                <th style="width: 20%;">Sentence (include both length & type of sentence, e.g. YRO, caution, custodial)</th>
                            </tr>
                            <?php
                            $details_of_criminal_conviction = json_decode($criminal_conviction_details->details);
                            for($i = 1; $i <= 8; $i++)
                            {
                                $co_date = '';$co_nature = '';$co_sentence = '';
                                if(isset($details_of_criminal_conviction[$i-1]))
                                {
                                    $_co_entry = (array)$details_of_criminal_conviction[$i-1];
                                    $co_date = $_co_entry["co_date_of_conviction{$i}"];
                                    $co_nature = $_co_entry["co_nature_of_offence{$i}"];
                                    $co_sentence = $_co_entry["co_sentence{$i}"];
                                }
                                echo '<tr>';
                                echo '<td>'.$co_date.'</td>';
                                echo '<td>'.$co_nature.'</td>';
                                echo '<td>'.$co_sentence.'</td>';
                                echo '</tr>';
                            }
                            ?>
                        </table>
                    </div>
                </div>

                <p><br></p>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="working_with_agencies" class="col-sm-4 control-label ">Are you working with any other agencies</label>
                            <div class="col-sm-8 fieldValue">
                                <?php
                                $yes_no_list = LookupHelper::getListYesNo();
                                echo isset($yes_no_list[$criminal_conviction_details->working_with_agencies]) ? $yes_no_list[$criminal_conviction_details->working_with_agencies] : $criminal_conviction_details->working_with_agencies;
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="motoring_offence" class="col-sm-4 control-label ">Please include the name and contact details of the agencies/workers that you are working with:</label>
                            <div class="col-sm-8 fieldValue">
                                <?php echo $criminal_conviction_details->details_of_agencies; ?>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="callout callout-default text-success">
                            <p>The College recognises that the information on this form constitutes sensitive personal data and by
                                signing below you explicitly consent for the College collecting, holding, and otherwise processing this data,
                                which may include liaising with any other agencies you are working with. The College will process this data
                                only for legitimate reasons and will do so in a way that does not unjustifiably prejudice your own interests. </p>
                        </div>
                    </div>
                </div>


            </step>

            <h3>Apprenticeship Delivery Details</h3>
            <step id="step9">

                <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                    <h4>Apprenticeship Delivery Details</h4>
                </div>

                <p><br></p>
                <div class="col-sm-12">
                    <table class="table table-bordered">
                        <col width="40%" />
                        <col width="60%" />
                        <tr>
                            <th class="text-bold">Apprentice Name</th>
                            <td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td>
                        </tr>
                        <tr>
                            <th class="text-bold">Employer Name</th>
                            <td><?php echo $employer->legal_name; ?></td>
                        </tr>
                        <tr>
                            <th class="text-bold">Apprenticeship Title</th>
                            <td><?php echo $framework->getStandardCodeDesc($link); ?></td>
                        </tr>
                        <tr>
                            <th class="text-bold">Level</th>
                            <td><?php echo DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';"); ?></td>
                        </tr>
                        <tr>
                            <th class="text-bold">Contracted Hours per week</th>
                            <td><?php echo $skills_analysis->contracted_hours_per_week; ?></td>
                        </tr>
                        <?php if($tr->contracted_hours_per_week >= 30) {?>
                        <tr>
                            <th class="text-bold">Total Contracted Hours (full apprenticeship)</th>
                            <td><?php echo $skills_analysis->total_contracted_hours_full_apprenticeship; ?></td>
                        </tr>
                        <tr>
                            <th class="text-bold">Minimum 20% OTJ Requirement</th>
                            <td><?php echo $skills_analysis->minimum_percentage_otj_training; ?> hours</td>
                        </tr>
                        <?php } else { ?>
                            <tr>
                                <th class="text-bold">Total Contracted Hours (full apprenticeship)</th>
                                <td><?php echo $skills_analysis->part_time_total_contracted_hours_full_apprenticeship; ?></td>
                            </tr>
                            <tr>
                                <th class="text-bold">Minimum 20% OTJ Requirement</th>
                                <td><?php echo $skills_analysis->part_time_otj_hours; ?> hours</td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <th class="text-bold">Total training price</th>
                            <td>&pound;<?php echo $skills_analysis->total_training_price; ?></td>
                        </tr>
                        <tr>
                            <th class="text-bold">Total EPA price</th>
                            <td>&pound;<?php echo $skills_analysis->epa_price; ?></td>
                        </tr>
                        <tr>
                            <th class="text-bold">Total negotiated price</th>
                            <td>&pound;<?php echo $skills_analysis->total_nego_price_fa; ?></td>
                        </tr>
                    </table>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive" style="font-size: medium;">
                            <table class="table table-bordered">
                                <tr class="bg-light-blue">
                                    <th>Training to be delivered</th>
                                    <th>Exempt</th>
                                    <th>Level</th>
                                    <th>Details</th>
                                    <th>Start Date</th>
                                    <th>Planned End Date</th>
                                    <th>Number of months</th>
                                    <th>Delivery location</th>
                                    <th>Mode of attendance</th>
                                    <th>Day of week</th>
                                    <th>Delivery hours</th>
                                </tr>
                                <?php
                                $ob_quals_sql = <<<SQL
SELECT 
    ob_learner_quals.*,
    framework_qualifications.level,
    framework_qualifications.qualification_type,
    TIMESTAMPDIFF(MONTH, qual_start_date, qual_end_date) AS no_of_months,
    framework_qualifications.`main_aim`
FROM
    ob_learner_quals
    LEFT JOIN framework_qualifications ON REPLACE(ob_learner_quals.qual_id, '/', '') = REPLACE(framework_qualifications.id, '/', '') 
WHERE
    ob_learner_quals.tr_id = '{$tr->id}' AND 
    framework_qualifications.framework_id = '{$tr->framework_id}'   
SQL;
                                $ob_quals = DAO::getResultset($link, $ob_quals_sql, DAO::FETCH_ASSOC);
                                foreach($ob_quals AS $qual)
                                {
                                    echo '<tr>';
                                    echo '<td>' . $qual['qual_id'] .  ' ' . $qual['qual_title'] . '</td>';
                                    if($qual['qual_exempt'] == 0)
                                    {
                                        echo '<td>No</td>';    
                                    }
                                    elseif($qual['qual_exempt'] == 1)
                                    {
                                        echo '<td>Yes</td>';    
                                    }
                                    elseif($qual['qual_exempt'] == 2)
                                    {
                                        echo '<td>Pending</td>';    
                                    }
                                    else
                                    {
                                        echo '<td></td>';    
                                    }
                                    echo '<td>' . $qual['level'] . '</td>';
                                    echo '<td>' . DAO::getSingleValue($link, "SELECT description FROM lookup_qual_type WHERE id = '{$qual['qualification_type']}'") . '</td>';
                                    echo '<td>' . Date::toShort($qual['qual_start_date']) . '</td>';
                                    echo '<td>' . Date::toShort($qual['qual_end_date']) . '</td>';
                                    echo '<td>' . $qual['no_of_months'] . ' months</td>';
                                    echo '<td>' . LookupHelper::getListDeliveryLocation($qual['qual_dl']) . '</td>';
                                    echo '<td>' . LookupHelper::getListModeOfAttendance($qual['qual_ma']) . '</td>';
                                    echo '<td>' . $qual['qual_dow'] . '</td>';
                                    echo '<td>' . $qual['qual_dh'] . '</td>';
                                    if($qual['main_aim'] == 1)
                                    {
                                        $planned_reviews_start_date = $qual['qual_start_date'];
                                        $planned_reviews_end_date = $qual['qual_end_date'];
                                    }
                                    echo '</tr>';
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="text-bold">Planned Reviews - (main provider, employer, apprentice must be present)</h4>
                        <p>The first review should take place at week 4, and all other reviews every 8 weeks and should be signed off by all parties on OneFile.</p>
                        <p>Reviews should discuss progress to date against the training plan and the immediate next steps required.</p>

                        <div class="table-responsive" style="font-size: medium;">
                            <table class="table table-bordered">
                                <caption class="text-bold">Planned Reviews</caption>
                                <thead>
                                <tr>
                                    <td>Review No.</td>
                                    <td>Review Date</td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $_review_dates = OnboardingHelper::getReviewsDates($planned_reviews_start_date, $planned_reviews_end_date);
                                foreach($_review_dates AS $_review_number => $_review_date)
                                {
                                    echo "<tr><td>{$_review_number}</td><td>{$_review_date}</td></tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </step>

            <h3>Apprenticeship Agreement</h3>
            <step id="step10">

                <div class="row">
                    <div class="col-sm-12">

                        <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                            <h4>Apprenticeship Agreement</h4>
                        </div>

                        <div class="text-center">
                            <img src="/images/logos/app_logo.jpg" alt="Apprenticeship" />
                            <img src="/images/logos/ESF.png" alt="Apprenticeship" />
                            <img class="headerlogo" src="<?php echo $ob_header_image1; ?>"/>
                        </div>

                        <div class="well">
                            <p>Further to the Apprenticeships (Form of Apprenticeship Agreement) Regulations which came into force on 6th April 2012, an Apprenticeship Agreement is required at the commencement of an Apprenticeship for all new apprentices who start on or after that date.</p>
                            <p>The purpose of the Apprenticeship Agreement is to:-</p>
                            <ul style="margin-left: 25px;">
                                <li>the skill, trade or occupation for which the apprentice is being trained;</li>
                                <li>the apprenticeship standard or framework connected to the apprenticeship;</li>
                                <li>the dates during which the apprenticeship is expected to take place; and</li>
                                <li>the amount of off the job training that the apprentice is to receive.</li>
                            </ul>
                            <p></p>
                            <p>The Apprenticeship Agreement is incorporated into and does not replace the written statement of particulars issued to the individual in accordance with the requirements of the Employment Rights Act 1996.</p>
                            <p>The Apprenticeship is to be treated as being a contract of service not a contract of Apprenticeship.</p>
                        </div>

                        <h4><strong>Apprenticeship Particulars</strong></h4>
                        <table class="table row-border">
                            <tr><th>Apprentice name:</th><td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td></tr>
                            <tr>
                                <th>Relevant Apprenticeship framework and level:</th>
                                <td><?php echo $framework->title; ?></td>
                            </tr>
                            <tr>
                                <th>Relevant Apprenticeship framework and level:</th>
                                <td><?php echo DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';"); ?></td>
                            </tr>
                            <tr>
                                <th>Place of work (employer):</th>
                                <td>
					<?php echo $employer->legal_name; ?><br>
                                    <?php echo $employer_location->address_line_1 != '' ? $employer_location->address_line_1 . '<br>' : ''; ?>
                                    <?php echo $employer_location->address_line_2 != '' ? $employer_location->address_line_2 . '<br>' : ''; ?>
                                    <?php echo $employer_location->address_line_3 != '' ? $employer_location->address_line_3 . '<br>' : ''; ?>
                                    <?php echo $employer_location->address_line_4 != '' ? $employer_location->address_line_4 . '<br>' : ''; ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Start date of apprenticeship:</th><td><?php echo Date::toShort($tr->apprenticeship_start_date); ?></td>
                                            <th>End date of apprenticeship (including EPA):</th><td><?php echo Date::toShort($tr->apprenticeship_end_date_inc_epa); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Start date of practical period:</th><td><?php echo Date::toShort($tr->practical_period_start_date); ?></td>
                                            <th>Estimated end date of practical period:</th><td><?php echo Date::toShort($tr->practical_period_end_date); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Duration of practical period:</th><td><?php echo $tr->duration_practical_period; ?> months</td>
                                            <th>Planned amount of off-the-job training (hours):</th><td><?php echo $skills_analysis->delivery_plan_hours_fa; ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr><th colspan="2"><br></th></tr>
                        </table>

                        <div class="well">
                            <p><?php echo $provider->legal_name; ?> will collect personal data such as name, address, personal characteristics and course details
                                in order to create a learner record. The College will share your data with government and other third parties
                                linked to and approved by the College. For full details please read the College Privacy Notice at <?php echo $app_agreement_provider_url; ?>
                                In order to complete your enrolment form, and enrol to College you need to agree to the following:</p>
                            <ol style="margin-left: 15px;">
                                <li>I agree with the apprenticeship outlined above and have discussed the details with a trainer. I also confirm that I have discussed my prior qualifications with my training provider and employer. This apprenticeship includes the need to study functional skills maths and English where required.</li>
                                <li>I understand that progression to a higher level apprenticeship is based on successful completion of this apprenticeship.</li>
                                <li>I agree to abide by the rules and regulations of <?php echo $provider->legal_name; ?>.</li>
                                <li>I understand that I may be required to undertake any examination or assessment as part of my apprenticeship, which I agree to complete in accordance with the requirements of the awarding body.</li>
                                <li>I agree that I will be enrolled to Functional Skills/GCSEs as appropriate for my needs. I must attend, I understand failure to attend English and/or maths will result in me being asked to leave the College.</li>
                                <li>I understand that I must abide by the standards of behaviour agreed at the start of my study programme.</li>
                                <li>I must abide by the rules of the Student Support Fund scheme as outlined in the Induction handbook.</li>
                                <li>I agree that I will wear my College identification badge whilst on College premises and failure to do so may result in a disciplinary action.</li>
                                <li>I agree to inform the College immediately of any change in my circumstances.</li>
                                <li>I confirm I am aware of the College's fees and charging policy which can be found on the <?php echo $provider->legal_name; ?> website, <?php echo $app_agreement_provider_url; ?></li>
                                <li>I understand that failure to disclose an unspent criminal conviction may result in the withdrawal of the offer of a place at <?php echo $provider->legal_name; ?>.</li>
                                <li>I agree to declare both unspent and spent criminal convictions if my programme of study requires me to have an enhanced DBS check.</li>
                                <li>I agree to inform the College if I am under police investigation, charged with a crime or receive any criminal convictions during my time at College.</li>
                                <li>I agree to abide by the rules and regulations regarding the use of the internet, mobile phones and other media devices.</li>
                                <li>I understand that I will receive text messages informing me of important College information.</li>
                                <?php if(in_array(DB_NAME, ["am_barnsley", "am_barnsley_demo"])) { ?>
                                    <li>I agree that the College can share my personal data with the Education and Skills Funding Agency (EFSA), Department for Education (DfE), Barnsley Metropolitan Borough Council (BMBC), Office for Students, Student Loans Company and external companies approved by the College.</li>
                                <?php } ?>
                                <li>I agree that the College can collect and use my personal data to assist in my learner journey.</li>
                                <li>I agree the College may contact me after I leave College for destination surveys, result information and alumni information.</li>
                                <li>I agree to my information being shared with my named Next of Kin (parents/guardian/carer)</li>
                                <li>I agree to my information being shared with my sponsor (employer) while they sponsor me at College.</li>
                            </ol>
                            <p>This is a Department for Education (DfE) funded programme and this learning activity may be directly or indirectly part-financed by the European Social Fund � helping develop employment by promoting employability, business spirit and equal opportunities and investing in human resources.</p>
                        </div>
                    </div>
                </div>

            </step>

            <h3>Roles, Resp. & Dec.</h3>
            <step id="step11">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                            <h4>Roles, Responsibilities & Declarations</h4>
                        </div><br>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <p>Please read and agree to the roles and responsibilities listed below.</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered">
                            <caption class="bg-gray-light text-bold" style="padding: 5px;">Learner Roles & Responsibilities:</caption>
                            <?php
                            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_roles_responsibilities WHERE user_type = 'LEARNER' ORDER BY id", DAO::FETCH_ASSOC);
                            foreach($result AS $row)
                            {
                                echo '<tr>';
                                echo '<td>' . $row['id'] . '</td>';
                                echo '<td>' . $row['description'] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered">
                                <caption class="bg-gray-light text-bold" style="padding: 5px;">The Employer (Manager of Apprentice) agrees to:</caption>
                                <?php
                                $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_roles_responsibilities WHERE user_type = 'EMPLOYER' AND sub_id = 0 ORDER BY id", DAO::FETCH_ASSOC);
                                $first_loop = true;
                                $previous_id = '';
                                foreach($result AS $row)
                                {
                                    echo '<tr>';
                                    echo $previous_id != $row['id'] ? '<td>' . $row['id'] . '</td>' : '<td></td>';
                                    echo '<td>';
                                    echo $row['description'];
                                    $subs = DAO::getSingleColumn($link, "SELECT description FROM lookup_cs_roles_responsibilities WHERE sub_id = '{$row['id']}' AND user_type = 'EMPLOYER'");
                                    if(count($subs) > 0)
                                        echo '<ul>';
                                    foreach($subs AS $sub)
                                    {
                                        echo '<li style="margin-left: 20px;">' . $sub . '</li>';
                                    }
                                    if(count($subs) > 0)
                                        echo '</ul>';
                                    echo '</td>';
                                    echo '</tr>';
                                    $first_loop = false;
                                    $previous_id = $row['id'];
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered">
                                <caption class="bg-gray-light text-bold" style="padding: 5px;">The Main Provider agrees to:</caption>
                                <?php
                                $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_roles_responsibilities WHERE user_type = 'PROVIDER' AND sub_id = 0 ORDER BY id", DAO::FETCH_ASSOC);
                                $first_loop = true;
                                $previous_id = '';
                                foreach($result AS $row)
                                {
                                    echo '<tr>';
                                    echo $previous_id != $row['id'] ? '<td>' . $row['id'] . '</td>' : '<td></td>';
                                    echo '<td>';
                                    echo $row['description'];
                                    $subs = DAO::getSingleColumn($link, "SELECT description FROM lookup_cs_roles_responsibilities WHERE sub_id = '{$row['id']}' AND user_type = 'PROVIDER'");
                                    if(count($subs) > 0)
                                        echo '<ul>';
                                    foreach($subs AS $sub)
                                    {
                                        echo '<li style="margin-left: 20px;">' . $sub . '</li>';
                                    }
                                    if(count($subs) > 0)
                                        echo '</ul>';
                                    echo '</td>';
                                    echo '</tr>';
                                    $first_loop = false;
                                    $previous_id = $row['id'];
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="callout">
                            <span class="lead text-bold">Working Together</span>
                            <p>
                                <i>The Employer and the Apprentice will work together with the Training Provider's
                                    representatives to ensure that the Apprentice has the best chance to achieve.
                                    In so doing, each parties' roles and responsibilities should be read carefully
                                    in this Training Plan with further recourse to the appropriate,
                                    Funding Rules in force at the time.</i>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="callout">
                            <span class="lead text-bold">Queries and Complaints Process</span>
                            <p>
                                <?php echo $provider->legal_name; ?> strive to provide the best quality learning and services that meet
                                or exceed the expectations of our learners and users. The College/Training Provider promotes
                                a culture that is responsive to feedback, whether complimentary or critical.
                                Comments about our services are actively encouraged and acknowledged as a valuable
                                source of information that we can evaluate and use to improve the quality of provision to
                                learners, other users and partners/stakeholders.
                                Learners and users can bring their concerns to the attention of the college either informally or formally.
                            </p>

                            <div class="callout">
                                <span class="lead text-bold">Informal Complaints</span>
                                <ul style="margin-left: 5px;">
                                    <li>
                                        In the first instance, complainants are strongly encouraged to resolve the matter informally with appropriate members of staff.
                                    </li>
                                    <li>
                                        If a complaint is not resolved at this stage, the complainant should be advised to progress their complaint through the College/Training Provider formal complaints procedure.
                                    </li>
                                </ul>
                            </div>

                            <div class="callout">
                                <span class="lead text-bold">Formal Complaints</span>
                                <ul style="margin-left: 5px;">
                                    <li>
                                        Complainants can make a formal complaint either verbally or in writing. All formal complaints should be passed to the Director of Quality and Performance.
                                    </li>
                                    <li>
                                        All complaints will be formally acknowledged in writing upon receipt.
                                    </li>
                                    <li>In the first instance, the Director of Quality and Performance will contact the complainant to discuss their concerns and any requirements.  Where appropriate a meeting will be arranged to discuss their concerns and requirements in more detail.</li>
                                    <li>All formal complaints will be resolved within 10 working days of the receipt of the formal complaint or if this is not possible, the complainant will be advised on the progress made to address their concerns.</li>
                                    <li>Upon completion of the investigation into the complaint the Director of Quality and Performance will notify the complainant in writing of the outcome.</li>
                                    <li>If at this point the complainant feels their complaint has not been addressed to their satisfaction, they can refer the complaint to the Vice Principal Quality for further consideration.</li>
                                    <li>If after due consideration by the Vice Principal Quality the complainant feels their complaint has not been addressed to their satisfaction they can refer the complaint to the Principal.</li>
                                    <li>If after due consideration by the Principal or a Senior Post Holder the complainant feels their complaint has not been addressed to their satisfaction, they can refer the complaint to the Department for Education (DfE) through the apprenticeship helpline detailed below:</li>
                                </ul>
                            </div>
                        </div>

                        <p class="text-bold">Apprenticeship Helpline</p>
                        <p>All parties can make use of the Apprenticeship Helpline if they have any queries, concerns or complaints:</p>
                        <p>Email: <a class="text-green" href="mailto:helpdesk@manage-apprenticeships.service.gov.uk">helpdesk@manage-apprenticeships.service.gov.uk</a></p>
                        <p>Telephone: 08000 150 600</p>

                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered">
                            <col style="width: 8%" />
                            <caption class="bg-light-blue text-bold" style="padding: 5px;">Learner Declarations:</caption>
                            <?php
                            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_declarations WHERE user_type = 'LEARNER' ORDER BY id", DAO::FETCH_ASSOC);
                            $saved_learner_dec = explode(",", $tr->learner_dec);
                            foreach($result AS $row)
                            {
                                echo '<tr>';
                                if(in_array($row['id'], $saved_learner_dec))
                                    echo '<td align="right"><i class="fa fa-check"></i> </td>';
                                else
                                    echo '<td align="right"></td>';
                                echo '<td>' . $row['description'] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered">
                            <col style="width: 8%" />
                            <caption class="bg-light-blue text-bold" style="padding: 5px;">Employer Declarations:</caption>
                            <?php
                            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_declarations WHERE user_type = 'EMPLOYER' ORDER BY id", DAO::FETCH_ASSOC);
                            $saved_employer_dec = explode(",", $tr->emp_dec);
                            foreach($result AS $row)
                            {
                                echo '<tr>';
                                if(in_array($row['id'], $saved_employer_dec))
                                    echo '<td align="right"><i class="fa fa-check"></i> </td>';
                                else
                                    echo '<td align="right"></td>';
                                echo '<td>' . $row['description'] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered">
                            <col style="width: 8%" />
                            <caption class="bg-light-blue text-bold" style="padding: 5px;">Provider Declarations:</caption>
                            <?php
                            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_declarations WHERE user_type = 'PROVIDER' ORDER BY id", DAO::FETCH_ASSOC);
                            $saved_tp_dec = explode(",", $tr->tp_dec);
                            foreach($result AS $row)
                            {
                                echo '<tr>';
                                if(in_array($row['id'], $saved_tp_dec))
                                    echo '<td align="right"><i class="fa fa-check"></i> </td>';
                                else
                                    echo '<td align="right"></td>';
                                echo '<td>' . $row['description'] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </table>
                    </div>
                </div>


            </step>

            <h3>Signature</h3>
            <step id="step12">

                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered">
                            <col style="width: 30%;" />
                            <col style="width: 30%;" />
                            <col style="width: 30%;" />
                            <tr>
                                <th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th>
                            </tr>
                            <tr>
                                <td>Apprentice</td>
                                <td>
                                    <?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?>
                                </td>
                                <td>
                                    <img width="25px" height="50px" src="do.php?_action=generate_image&<?php echo $tr->learner_sign; ?>&size=20" style="border: 2px solid;border-radius: 15px; width: 100%;"/>
                                </td>
                                <td><?php echo Date::toShort($tr->learner_sign_date); ?></td>
                            </tr>
                            <tr>
                                <td>Employer</td>
                                <td><?php echo $tr->emp_sign_name; ?></td>
                                <td>
                                    <img width="25px" height="50px" src="do.php?_action=generate_image&<?php echo $tr->emp_sign == '' ? 'title=not yet signed' : $tr->emp_sign; ?>&size=20" style="border: 2px solid;border-radius: 15px; width: 100%;"/>
                                </td>
                                <td><?php echo Date::toShort($tr->emp_sign_date); ?></td>
                            </tr>
                            <tr>
                                <td>Provider</td>
                                <td><?php echo $tr->tp_sign_name; ?></td>
                                <td>
                                    <img width="25px" height="50px" src="do.php?_action=generate_image&<?php echo $tr->tp_sign == '' ? 'title=not yet signed' : $tr->tp_sign; ?>&size=20" style="border: 2px solid;border-radius: 15px; width: 100%;"/>
                                </td>
                                <td><?php echo Date::toShort($tr->tp_sign_date); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>


            </step>


        </form>
    </div>

</div>

<div class="loader" style="display: none;"></div>


<script>
    var frmOnboarding = $("#frmOnboarding").show();
    frmOnboarding.steps({
        headerTag:"h3",
        bodyTag:"step",
        transitionEffect:"slideLeft",
        stepsOrientation:"vertical",
        enableAllSteps: true,
        // startIndex: 5,
        onStepChanging:function (event, currentIndex, newIndex) {
            // Always allow previous action even if the current form is not valid!
            return true;
        },
        onStepChanged:function (event, currentIndex, priorIndex) {
            $('.loader').hide();
            //window.scrollTo(0, 0);
            return true;
        },
        onFinishing:function (event, currentIndex) {
            return true;
        },
        onFinished:function (event, currentIndex) {

        }
    });

</script>

</body>
</html>
