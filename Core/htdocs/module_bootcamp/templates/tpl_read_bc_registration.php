<?php

/** @var $registration Registration */ ?>

<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Applicant</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css" />
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

    <!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>

<body>
    <div class="row">
        <div class="col-lg-12">
            <div class="banner">
                <div class="Title" style="margin-left: 6px;">View Applicant</div>
                <div class="ButtonBar">
                    <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
                    <!-- <?php if (isset($learner->id) && !$registration->is_synced) {  ?>
                        <button id="updateLearnerBtn" type="button" class="btn btn-xs btn-default">
                            Update Learner Record
                        </button>
                    <?php }  ?> -->
                    <?php if (isset($learner->id) && $registration->is_compliant == 1) {  ?>
                        <button type="button" class="btn btn-xs btn-default" onclick="window.location.href='do.php?_action=read_learner&username=<?php echo $learner->username; ?>&id=<?php echo $learner->id; ?>';">
                            Open Learner Record
                        </button>
                    <?php }  ?>
                    <?php if (is_null($learner) && $registration->is_finished == 'Y' && $registration->is_compliant == 1) {  ?>
                        <button id="createLearnerBtn" type="button" class="btn btn-xs btn-default">
                            <i class="fa fa-plus"></i><i class="fa fa-user"></i> Create Learner Record
                        </button>
                    <?php }  ?>
                </div>
                <div class="ActionIconBar">
                    <?php if( true || $registration->getStatus() == Registration::STATUS_COMPLIANCE_COMPLETE ){ ?>
                    <span class="btn btn-xs btn-default" onclick="window.location.href='do.php?_action=read_bc_registration&id=<?php echo $registration->id; ?>&subaction=export_pdf';"><i class="fa fa-file-pdf-o"></i> Export</span>
                    <?php } ?>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php $_SESSION['bc']->render($link); ?>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-sm-12">
            <div class="text-center">
                <span class="label label-<?php echo Registration::getStatusLabelColor($registration->getStatus()); ?>" style="font-size: small;">
                    <?php echo $registration->getStatus(); ?>
                </span>
                <?php 
                if($registration->getStatus() == Registration::STATUS_COMPLIANCE_COMPLETE)
                {
                    echo ' &nbsp; &nbsp; &nbsp;<i class="fa  fa-long-arrow-right fa-lg"></i> &nbsp; &nbsp; &nbsp;';
                    
                    echo '<span class="label label-primary" style="font-size: small;">Create Learner</span>';
                }
                if($registration->getStatus() == Registration::STATUS_LEARNER_CREATED)
                {
                    echo ' &nbsp; &nbsp; &nbsp;<i class="fa  fa-long-arrow-right fa-lg"></i> &nbsp; &nbsp; &nbsp;';
                    
                    $isEnrolled = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr INNER JOIN users ON tr.username = users.username WHERE users.id = '{$registration->entity_id}'");
                    if(!$isEnrolled)
                    {
                        echo $registration->entity_id%2 === 0 ? 
                            '<span class="label label-primary" style="font-size: small;">Awaiting Enrolment</span>' : 
                            '<span class="label label-primary" style="font-size: small;">Awaiting Initial Assessment</span>';
                    }
                    else
                    {
                        echo '<span class="label label-success" style="font-size: small;">Learner Enrolled</span>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-8">
            <div class="panel-body fieldValue" style="margin-bottom: 5px;">
                <table class="table row-border">
                    <caption class="text-bold text-center">Personal Details</caption>
                    <tr>
                        <th>Title:</th>
                        <td><?php echo $registration->learner_title; ?></td>
                    </tr>
                    <tr>
                        <th>First Name(s):</th>
                        <td><?php echo $registration->firstnames; ?></td>
                    </tr>
                    <tr>
                        <th>Surname:</th>
                        <td><?php echo $registration->surname; ?></td>
                    </tr>
                    <tr>
                        <th>Date of Birth:</th>
                        <td>
                            <?php 
                            echo Date::toShort($registration->dob); 
                            if ($registration->dob) 
                            {
                                echo $registration->learner_sign_date != '' ? 
                                    '<span style="margin-left:30px;color:gray"></span><br><label class="label label-info">' . Date::dateDiff($registration->learner_sign_date,$registration->dob) . '</label>' : 
                                    '<span style="margin-left:30px;color:gray"></span><br><label class="label label-info">' . Date::dateDiff(date('Y-m-d'),$registration->dob) . '</label>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Current Postcode:</th>
                        <td><?php echo $registration->home_postcode; ?></td>
                    </tr>
                    <tr>
                        <th>Current Address Line 1:</th>
                        <td><?php echo $registration->home_address_line_1; ?></td>
                    </tr>
                    <tr>
                        <th>Current Address Line 2:</th>
                        <td><?php echo $registration->home_address_line_2; ?></td>
                    </tr>
                    <tr>
                        <th>Current Address Line 3 (Town):</th>
                        <td><?php echo $registration->home_address_line_3; ?></td>
                    </tr>
                    <tr>
                        <th>Current Address Line 4 (County):</th>
                        <td><?php echo $registration->home_address_line_4; ?></td>
                    </tr>
                    <tr>
                        <th>Telephone Number:</th>
                        <td><?php echo $registration->home_telephone; ?></td>
                    </tr>
                    <tr>
                        <th>Mobile Phone:</th>
                        <td><?php echo $registration->home_mobile; ?></td>
                    </tr>
                    <tr>
                        <th>National Insurnace:</th>
                        <td><?php echo $registration->ni; ?></td>
                    </tr>
                    <tr>
                        <th>Gender:</th>
                        <td><?php echo $registration->getGenderDescription(); ?></td>
                    </tr>
                    <tr>
                        <th>Ethnicity:</th>
                        <td><?php echo $registration->gerEthnicityDescription($link); ?></td>
                    </tr>
                </table>
            </div>

            <div class="panel-body fieldValue" style="margin-bottom: 5px;">
                <table class="table row-border">
                    <caption class="text-bold text-center">Household Situation</caption>
                    <?php
                    foreach (LookupHelper::getDDLHhs() as $entry) {
                        echo '<tr>';
                        echo '<td>' . $entry[1] . '</td>';
                        echo $entry[0] == $registration->hhs ? '<td><i class="fa fa-check fa-lg"></i></td>' : '<td></td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>

            <div class="panel-body fieldValue" style="margin-bottom: 5px;">
                <table class="table row-border">
                    <tr>
                        <th>Is learner currently caring for children or other adults?</th>
                        <td><?php echo $registration->currently_caring == 1 ? 'Yes' : 'No'; ?></td>
                    </tr>
                </table>
            </div>

            <div class="panel-body fieldValue" style="margin-bottom: 5px;">
                <table class="table row-border">
                    <caption class="text-bold text-center">Emergency Contacts</caption>
                    <tr>
                        <td>
                            <?php
                            echo $registration->em_con_rel1 . '<br>';
                            echo $registration->em_con_title1 . ' ' . $registration->em_con_name1 . '<br>';
                            echo $registration->em_con_tel1 . '<br>';
                            echo $registration->em_con_mob1 . '<br>';
                            echo $registration->em_con_email1;
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $registration->em_con_rel2 . '<br>';
                            echo $registration->em_con_title2 . ' ' . $registration->em_con_name2 . '<br>';
                            echo $registration->em_con_tel2 . '<br>';
                            echo $registration->em_con_mob2 . '<br>';
                            echo $registration->em_con_email2;
                            ?>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="panel-body fieldValue" style="margin-bottom: 5px;">
                <table class="table row-border">
                    <caption class="text-bold text-center">LLDD Information</caption>
                    <tr>
                        <th>Does learner consider him/herself to have a learning difficulty, health problem or disability?</th>
                        <td><?php echo $registration->getLlddDescription(); ?></td>
                    </tr>
                    <tr>
                        <th>LLDD Categories</th>
                        <td><?php echo $registration->getLlddCatDescription('<br>'); ?></td>
                    </tr>
                    <tr>
                        <th>Primary LLDD Category</th>
                        <td><?php echo $registration->getPrimaryLlddDescription(); ?></td>
                    </tr>
                    <tr>
                        <th>Learner would like to benefit from a confidential interview?</th>
                        <td><?php echo $registration->confidential_interview == '1' ? 'Yes' : 'No'; ?></td>
                    </tr>
                </table>
            </div>

            <div class="panel-body fieldValue" style="margin-bottom: 5px;">
                <table class="table row-border">
                    <caption class="text-bold text-center">Prior Attainment</caption>
                    <tr>
                        <th>Learner considers his/her Prior Attainment Level to be</th>
                        <td><?php echo $registration->getPriorAttainmentDescription(); ?></td>
                    </tr>
                    <tr>
                        <th>Subject for level 6 qualification or higher if completed</th>
                        <td><?php echo $registration->getLevel6SubjectDescription(); ?></td>
                    </tr>

                </table>
            </div>

            <div class="panel-body fieldValue" style="margin-bottom: 5px;">
                <table class="table row-border">
                    <caption class="text-bold text-center">Employment Status</caption>
                    <tr>
                        <td colspan="2" class="text-center"><?php echo $registration->getEmploymentStatusDescription(); ?></td>
                    </tr>
                    <?php if ($registration->employment_status == '10') { ?>
                        <tr>
                            <th>Learner is self employed</th>
                            <td><?php echo $registration->SEI == '1' ? 'Yes' : 'No'; ?></td>
                        </tr>
                        <tr>
                            <th>Employer Name</th>
                            <td><?php echo $registration->emp_status_employer; ?></td>
                        </tr>
                        <tr>
                            <th>Employer Phone Number</th>
                            <td><?php echo $registration->emp_status_employer_tel; ?></td>
                        </tr>
                        <tr>
                            <th>Employer Contact Name</th>
                            <td><?php echo $registration->employer_contact_name; ?></td>
                        </tr>
                        <tr>
                            <th>Employer Contact Email</th>
                            <td><?php echo $registration->employer_contact_email; ?></td>
                        </tr>
                        <tr>
                            <th>Employer/Workplace Postcode</th>
                            <td><?php echo $registration->workplace_postcode; ?></td>
                        </tr>
                        <tr>
                            <th>Current Job Title</th>
                            <td><?php echo $registration->current_job_title; ?></td>
                        </tr>
                        <tr>
                            <th>Industry/Sector of your current occupation</th>
                            <td><?php echo $registration->current_occupation; ?></td>
                        </tr>
                        <tr>
                            <th>How long learner was employed</th>
                            <td><?php echo $registration->getLoeDescription(); ?></td>
                        </tr>
                        <tr>
                            <th>Hours per week</th>
                            <td><?php echo $registration->getEiiDescription(); ?></td>
                        </tr>
                        <tr>
                            <th>Current salary</th>
                            <td><?php echo $registration->current_salary; ?></td>
                        </tr>
                        <tr>
                            <th>Is learner attending this bootcamp via current employer</th>
                            <td><?php echo $registration->viaCurrentEmployerDescription(); ?></td>
                        </tr>
                        <tr>
                            <th>Does learner plan to work alongside the bootcamp?</th>
                            <td><?php echo $registration->planToWorkAlongsideDescription(); ?></td>
                        </tr>
                    <?php } elseif ($registration->employment_status == '11' || $registration->employment_status == '12') { ?>
                        <tr>
                            <th>How long learner was un-employed before start of this course:</th>
                            <td><?php echo $registration->getLouDescription(); ?></td>
                        </tr>
                        <tr>
                            <th>Did learner receive any of the benefits</th>
                            <td><?php echo $registration->getBsiCatDescription('<br>'); ?></td>
                        </tr>
                        <tr>
                            <th>Was learner in Full Time Education or Training prior to start of this course?</th>
                            <td><?php echo $registration->PEI == '1' ? 'Yes' : 'No'; ?></td>
                        </tr>
                    <?php } ?>


                </table>
            </div>

            <div class="panel-body fieldValue" style="margin-bottom: 5px;">
                <table class="table row-border">
                    <caption class="text-bold text-center">Learner agrees to be contacted for other purposes as follows:</caption>
                    <tr>
                        <td>About courses or learning opportunities</td>
                        <td><?php echo in_array(1, $selectedRuis) ? '<i class="fa fa-check fa-lg"></i>' : '<i class="fa fa-times fa-lg"></i>'; ?></td>
                    </tr>
                    <tr>
                        <td>For research and evaluation purposes</td>
                        <td><?php echo in_array(2, $selectedRuis) ? '<i class="fa fa-check fa-lg"></i>' : '<i class="fa fa-times fa-lg"></i>'; ?></td>
                    </tr>
                    <tr>
                        <td>By post</td>
                        <td><?php echo in_array(1, $selectedPmcs) ? '<i class="fa fa-check fa-lg"></i>' : '<i class="fa fa-times fa-lg"></i>'; ?></td>
                    </tr>
                    <tr>
                        <td>By phone</td>
                        <td><?php echo in_array(2, $selectedPmcs) ? '<i class="fa fa-check fa-lg"></i>' : '<i class="fa fa-times fa-lg"></i>'; ?></td>
                    </tr>
                    <tr>
                        <td>By email</td>
                        <td><?php echo in_array(3, $selectedPmcs) ? '<i class="fa fa-check fa-lg"></i>' : '<i class="fa fa-times fa-lg"></i>'; ?></td>
                    </tr>
                </table>
            </div>

            <div class="panel-body fieldValue" style="margin-bottom: 5px;">
                <table class="table row-border">
                    <caption class="text-bold text-center">Contact and Marketing Information</caption>
                    <tr>
                        <th>How did learner hear about us?</th>
                        <td><?php echo $registration->getHearUsDescription('<br>'); ?></td>
                    </tr>
                </table>
            </div>

            <div class="panel-body fieldValue">
                <table class="table row-border">
                    <tr>
                        <th>Sign</th>
                        <td><?php echo $registration->learner_sign != '' ? '<img src="' . $registration->learner_sign . '" alt="Sign">' : ''; ?></td>
                    </tr>
                    <tr>
                        <th>Sign Date</th>
                        <td><?php echo Date::toShort($registration->learner_sign_date); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-sm-4">
            <?php if($registration->getStatus() == Registration::STATUS_COMPLIANCE_AWAITING){ ?>
            <div class="panel-body fieldValue">
                <?php if(!$registration->getCompliantStatus($link)){ ?>
                <form name="frmCompliance" class="form-horizontal" action="do.php?_action=bc_ajax_helper" method="POST" onsubmit="return validateForm(this);">
                    <input type="hidden" name="subaction" value="submitComplianceInfo">
                    <input type="hidden" name="registration_id" value="<?php echo $registration->id; ?>">
                    <div class="box box-primary">
                        <div class="box-header">
                            <span class="box-title with-border">Compliance Check</span>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="uk_residence_check">Has learner lived in the UK for 3 years or more?</label>
                                <?php echo HTML::selectChosen('uk_residence_check', [['Yes', 'Yes'], ['No', 'No']], '', true, true); ?>
                            </div>
                            <div class="form-group">
                                <label for="age_check">Learner is over 19 nears old?</label>
                                <?php echo HTML::selectChosen('age_check', [['Yes', 'Yes'], ['No', 'No']], '', true, true); ?>
                            </div>
                            <div class="form-group">
                                <label for="compliance_status">Approve?</label>
                                <?php echo HTML::selectChosen('compliance_status', [['1', 'Yes'], ['2', 'No']], '', true, true); ?>
                            </div>
                            <div class="form-group">
                                <label for="age_check">Comments:</label>
                                <textarea name="comments" id="comments" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-primary btn-sm compulsory" type="submit">Save Information</button>
                        </div>
                    </div>
                </form>
                <?php }  ?>
            </div>
            <?php } elseif (isset($compliance)) { ?>
                <div class="box box-primary">
                    <div class="box-header">
                        <span class="box-title with-border">Compliance Check</span>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="compliance_status">Approve?</label><br>
                            <?php echo isset($compliance->compliance_status) ? ($compliance->compliance_status == 1 ? 'Yes' : 'No') : ''; ?>
                        </div>
                        <div class="form-group">
                            <label for="age_check">Comments:</label><br>
                            <?php echo isset($compliance->comments) ? $compliance->comments : ''; ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>


    </div>

    <div class="text-center" id="updateLearnerDialog"  style="display: none;">
        <h4 class="lead text-bold">Confirmation</h4>
        <p class="text-info">Are you sure you want to update learner record from this registration?</p>
    </div>

    <div class="text-center" id="createLearnerDialog"  style="display: none;">
        <h4 class="lead text-bold">Confirmation</h4>
        <p class="text-info">Are you sure you want to create learner record from this registration?</p>
    </div>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/common.js" type="text/javascript"></script>



    <script language="JavaScript">
        const registrationId = <?php echo json_encode($registration->id); ?>;

        $(document).ready(function() {

            $('#updateLearnerBtn').on('click', function() {
                $('#updateLearnerDialog').dialog({
                    title: 'Confirm Update',
                    resizable: false,
                    height: "auto",
                    width: 400,
                    modal: true,
                    buttons: {
                        "Yes": function() {
                            $(this).html('<i class="fa fa-refresh fa-spin fa-2x"></i> Updating ....').dialog('option', 'buttons', {});
                            $.ajax({
                                url: 'do.php?_action=bc_ajax_helper&subaction=updateLearnerFromBcRegistraion',
                                type: 'POST',
                                data: {registrationId: registrationId},
                                success: function(response) {
                                    $('#updateLearnerDialog').html(response);
                                    setTimeout(function(){ 
                                        window.location.reload();
                                    }, 2000);                                    
                                },
                                error: function(xhr, status, error) {
                                    $('#updateLearnerDialog').html('Error: ' + error);
                                }
                            });
                        },
                        "No": function() {
                            $(this).dialog('close');
                        }
                    }
                });
            });
            
            $('#createLearnerBtn').on('click', function() {
                $('#createLearnerDialog').dialog({
                    title: 'Confirm Create',
                    resizable: false,
                    height: "auto",
                    width: 400,
                    modal: true,
                    buttons: {
                        "Yes": function() {
                            $(this).html('<i class="fa fa-refresh fa-spin fa-2x"></i> Creating ....').dialog('option', 'buttons', {});
                            $.ajax({
                                url: 'do.php?_action=bc_ajax_helper&subaction=createLearnerFromBcRegistraion',
                                type: 'POST',
                                data: {registrationId: registrationId},
                                success: function(response) {
                                    $('#createLearnerDialog').html(response);
                                    setTimeout(function(){ 
                                        window.location.reload();
                                    }, 2000);
                                },
                                error: function(xhr, status, error) {
                                    console.log();
                                    $('#createLearnerDialog').html('Error: ' + error);
                                }
                            });
                        },
                        "No": function() {
                            $(this).dialog('close');
                        }
                    }
                });
            });


        });


    </script>

</body>

</html>