<!DOCTYPE html>
<html>

<head>
    <title>Learner Registration Form</title>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        #stepList {
            display: block;
        }

        #stepButtons {
            display: block;
        }

        @media (max-width: 768px) {
            #stepList {
                display: none;
            }

            #stepButtons {
                display: block;
            }

            .navbar-brand img {
                max-width: 100px;
                max-height: 50px;
            }
        }

        #home_postcode,
        #ni,
        #workplace_postcode {
            text-transform: uppercase
        }

        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }

        .disabled {
            pointer-events: none;
            opacity: 0.4;
        }

        input[type=checkbox] {
            transform: scale(1.4);
        }

        input[type=radio] {
            transform: scale(1.4);
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand pull-left" href="#">
                <img src="images/logos/duplex.png" alt="Logo">
            </a>
            <a class="navbar-brand pull-right" href="#">
                <img id="rightLogo" src="images/logos/Skills_For_Life_Logo_Black_RGB.png" alt="Skills for Life Logo" height="100" width="200">
            </a>
        </div>
    </nav>

    <div class="container-fluid">
        <h1 class="text-center">WAVE 4 - Green Technologies</h1>
        <h2 class="text-center">Skills Bootcamp Enrolment Form</h2>
        <h3 class="text-center">Electric/Hybrid Vehicle Qualification</h3>
        <form id="multi-step-form">
            <div class="row">
                <div class="col-md-3" id="stepList">
                    <ul class="list-group">
                        <li class="list-group-item step-item active" data-step="1">Personal Information</li>
                        <li class="list-group-item step-item" data-step="2">Employment</li>
                        <li class="list-group-item step-item" data-step="3">LLDD</li>
                        <li class="list-group-item step-item" data-step="4">Marketing Info.</li>
                        <li class="list-group-item step-item" data-step="5">Declaration & Commitment</li>
                        <li class="list-group-item step-item" data-step="6">Signatures</li>
                    </ul>
                </div>
                <div class="col-md-9">
                    <div class="step active" id="step-1">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr class="bg-gray">
                                            <th colspan="2">
                                                <h4 class="text-bold text-center">Applicant Information</h4>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="text-right"><label for="title">Title:</label></th>
                                            <td>
                                                <?php echo HTML::selectChosen('title', $titlesDdl, null, true, true); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right"><label for="surname">Surname/Family Name:</label></th>
                                            <td>
                                                <input type="text" class="form-control" name="surname" id="surname" maxlength="70" required="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right"><label for="firstnames">First Name(s) in full:</label></th>
                                            <td>
                                                <input type="text" class="form-control" name="firstnames" id="firstnames" maxlength="70" required="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right"><label for="preferred_name">Preferred Name:</label></th>
                                            <td>
                                                <input type="text" class="form-control" name="preferred_name" id="preferred_name" maxlength="70">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right">Address:</th>
                                            <td>
                                                <div class="form-group">
                                                    <label for="home_address_line_1" class="control-label">Building &amp; Street Name:</label>
                                                    <input type="text" class="form-control" name="home_address_line_1" id="home_address_line_1" maxlength="70">
                                                </div>
                                                <div class="form-group">
                                                    <label for="home_address_line_2" class="control-label">Suburb/Village:</label>
                                                    <input type="text" class="form-control" name="home_address_line_2" id="home_address_line_2" maxlength="70">
                                                </div>
                                                <div class="form-group">
                                                    <label for="home_address_line_3" class="control-label">Town/City:</label>
                                                    <input type="text" class="form-control" name="home_address_line_3" id="home_address_line_3" maxlength="70">
                                                </div>
                                                <div class="form-group">
                                                    <label for="home_address_line_4" class="control-label">County:</label>
                                                    <input type="text" class="form-control" name="home_address_line_4" id="home_address_line_4" maxlength="70">
                                                </div>
                                                <div class="form-group">
                                                    <label for="home_postcode" class="control-label">Postcode:</label>
                                                    <input type="text" class="form-control" name="home_postcode" id="home_postcode" maxlength="10">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right"><label for="input_dob">Date of Birth<br>(dd/mm/yyyy e.g: 25/01/1975):</label></th>
                                            <td>
                                                <input class="datepicker form-control" type="text" id="input_dob" name="dob" maxlength="10" placeholder="dd/mm/yyyy">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right">Gender: </th>
                                            <td>
                                                <?php echo HTML::selectChosen('title', $gendersDdl, null, true, true); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right"><label for="home_mobile">Mobile No:</label></th>
                                            <td>
                                                <input class="form-control" type="text" id="home_mobile" name="home_mobile" maxlength="20">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right"><label for="home_email">Email:</label></th>
                                            <td>
                                                <input class="form-control" type="email" id="home_email" name="home_email" maxlength="80">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right"><label for="ni">National Insurance:</label></th>
                                            <td>
                                                <input class="form-control" type="text" id="ni" name="ni" maxlength="9" onkeypress="return event.charCode != 32">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right">Ethnicity: </th>
                                            <td>
                                                <?php echo HTML::selectChosen('ethnicity', $ethnicityDdl, null, true, true); ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>Do you have a criminal conviction (excluding minor motoring offences)? </th>
                                            <td>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="criminal_conviction" value="Yes"> Yes
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="criminal_conviction" value="No"> No
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Are you currently caring for children or other adults? </th>
                                            <td>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="caring" value="Yes"> Yes
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="caring" value="No"> No
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr class="bg-gray">
                                            <th colspan="2">
                                                <h4 class="text-bold text-center">Emergency Contacts</h4>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group">
                                                    <label for="home_address_line_1" class="control-label">Emergency Contact Name:</label>
                                                    <input type="text" class="form-control" name="home_address_line_1" id="home_address_line_1" maxlength="70">
                                                </div>
                                                <div class="form-group">
                                                    <label for="home_address_line_2" class="control-label">Relationship:</label>
                                                    <input type="text" class="form-control" name="home_address_line_2" id="home_address_line_2" maxlength="70">
                                                </div>
                                                <div class="form-group">
                                                    <label for="home_address_line_3" class="control-label">Mobile No:</label>
                                                    <input type="text" class="form-control" name="home_address_line_3" id="home_address_line_3" maxlength="70">
                                                </div>
                                                <div class="form-group">
                                                    <label for="home_address_line_4" class="control-label">Home Telephone No:</label>
                                                    <input type="text" class="form-control" name="home_address_line_4" id="home_address_line_4" maxlength="70">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <label for="home_address_line_1" class="control-label">Emergency Contact Name:</label>
                                                    <input type="text" class="form-control" name="home_address_line_1" id="home_address_line_1" maxlength="70">
                                                </div>
                                                <div class="form-group">
                                                    <label for="home_address_line_2" class="control-label">Relationship:</label>
                                                    <input type="text" class="form-control" name="home_address_line_2" id="home_address_line_2" maxlength="70">
                                                </div>
                                                <div class="form-group">
                                                    <label for="home_address_line_3" class="control-label">Mobile No:</label>
                                                    <input type="text" class="form-control" name="home_address_line_3" id="home_address_line_3" maxlength="70">
                                                </div>
                                                <div class="form-group">
                                                    <label for="home_address_line_4" class="control-label">Home Telephone No:</label>
                                                    <input type="text" class="form-control" name="home_address_line_4" id="home_address_line_4" maxlength="70">
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr class="bg-gray">
                                            <th>
                                                <h4 class="text-bold text-center">Prior Attainment / Highest Previous Qualifications</h4>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group">
                                                    <label for="home_address_line_1" class="control-label">Prior Attainment Level:</label>
                                                    <?php echo HTML::selectChosen('prior_attain', $priorAttainDdl, null, true, true); ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group">
                                                    <label for="home_address_line_1" class="control-label">
                                                        If you have completed a level 6 qualification or higher, please select which subject this was in:
                                                    </label>
                                                    <?php echo HTML::selectChosen('prior_attain', $subjectsDdl, null, true, true); ?>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="step" id="step-2">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    
                                    <tbody>
                                        <tr class="bg-gray">
                                            <th colspan="2">
                                                <h4 class="text-bold text-center">Employment Information</h4>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="text-right">
                                                <label for="employment_status">Employment Status:</label><br>
                                                <span class="text-info">
                                                    On the day prior to this course, what is your employment status?
                                                </span>
                                            </th>
                                            <td>
                                                <?php
                                                foreach ($employerStatusDdl as $key => $value) {
                                                    echo '<div class="radio">';
                                                    echo '<label>';
                                                    echo '<input type="radio" name="employment_status" value="' . $key . '"> ' . $value;
                                                    echo '</label>';
                                                    echo '</div>';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr class="trEmployerFields">
                                            <th class="text-right">
                                                <label for="employer_name">Name of employer:</label><br>
                                            </th>
                                            <td>
                                                <input class="form-control" type="text" name="employer_name" id="employer_name" maxlength="150" />
                                            </td>
                                        </tr>
                                        <tr class="trEmployerFields">
                                            <th class="text-right">
                                                <label for="employer_phone">Employer phone number:</label><br>
                                            </th>
                                            <td>
                                                <input class="form-control" type="text" name="employer_phone" id="employer_phone" maxlength="25" />
                                            </td>
                                        </tr>
                                        <tr class="trEmployerFields">
                                            <th class="text-right">
                                                <label for="employer_contact_name">Employer contact name:</label><br>
                                            </th>
                                            <td>
                                                <input class="form-control" type="text" name="employer_contact_name" id="employer_contact_name" maxlength="70" />
                                            </td>
                                        </tr>
                                        <tr class="trEmployerFields">
                                            <th class="text-right">
                                                <label for="workplace_postcode">Workplace postcode:</label><br>
                                            </th>
                                            <td>
                                                <input type="text" class="form-control" name="workplace_postcode" id="workplace_postcode" maxlength="10">
                                            </td>
                                        </tr>
                                        <tr class="trEmployerFields">
                                            <th class="text-right">
                                                <label for="current_job_title">Current job title:</label><br>
                                            </th>
                                            <td>
                                                <input type="text" class="form-control" name="current_job_title" id="current_job_title" maxlength="70">
                                            </td>
                                        </tr>
                                        <tr class="trEmployerFields">
                                            <th class="text-right">
                                                <label for="industry">Industry / sector of current occupation:</label><br>
                                            </th>
                                            <td>
                                                <input type="text" class="form-control" name="industry" id="industry" maxlength="70">
                                            </td>
                                        </tr>
                                        <tr class="trEmployerFields">
                                            <th class="text-right">
                                                <label for="contracted_hours_per_week">Hours worked per week:</label><br>
                                            </th>
                                            <td>
                                                <input type="text" class="form-control" name="contracted_hours_per_week" id="contracted_hours_per_week" onkeypress="return numbersonlywithpoint();" maxlength="4">
                                            </td>
                                        </tr>
                                        <tr class="trEmployerFields">
                                            <th class="text-right">
                                                <label for="current_salary">
                                                    Current salary:<br>
                                                    <span class="text-info">(please specify if hourly rate, weekly, monthly or yearly)</span>
                                                </label>
                                            </th>
                                            <td>
                                                <input type="text" class="form-control" name="current_salary" id="current_salary" maxlength="70">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right">Do you currently receive any of the following?</th>
                                            <td>
                                                <?php
                                                foreach ($benefitsDdl as $key => $value) {
                                                    echo '<div class="radio">';
                                                    echo '<label>';
                                                    echo '<input type="radio" name="benefit" value="' . $key . '"> ' . $value;
                                                    echo '</label>';
                                                    echo '</div>';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr class="trEmployerFields">
                                            <th class="text-right">
                                                <label for="sent_thru_current_emp">Are you attending this bootcamp via your current employer:</label><br>
                                            </th>
                                            <td>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="sent_thru_current_emp" value="1"> 
                                                        Yes - My employer supports me attending this course and will be co-funding this training with a funding contribution
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="sent_thru_current_emp" value="2"> 
                                                        No - I am looking for a new job with a different employer
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="sent_thru_current_emp" value="3"> 
                                                        N/A - Self Employed
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="sent_thru_current_emp" value="4"> 
                                                        N/A - not in paid employment
                                                    </label>
                                                </div>
                                                
                                            </td>
                                        </tr>
                                        <tr class="trEmployerFields">
                                            <th class="text-right">
                                                <label for="plan_to_work_outside">Do you plan to work alongside the bootcamp?</label><br>
                                            </th>
                                            <td>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="plan_to_work_outside" value="1"> 
                                                        Yes - (Full-time employment)
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="plan_to_work_outside" value="2"> 
                                                        Yes - (Part-time employed)
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="plan_to_work_outside" value="3"> 
                                                        Yes - (Self-employed)
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="plan_to_work_outside" value="4"> 
                                                        No
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="step" id="step-3">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col style="width: 50%;">
                                    <tbody>
                                        <tr class="bg-gray">
                                            <th colspan="2">
                                                <h4 class="text-bold text-center">Disability, Learning Difficulty and or Long Term Health Condition</h4>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="text-right">
                                                <label for="lldd">Do you consider that you have a learning difficulty, disability or long-term health condition?</label><br>
                                            </th>
                                            <td>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="lldd" value="1"> 
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="lldd" value="2"> 
                                                        No
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="lldd" value="9"> 
                                                        Prefer not to say
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right">Select your disability, learning difficult categories</th>
                                            <td>
                                                <?php
                                                foreach ($LLDDCats as $key => $value) {
                                                    echo '<div class="checkbox">';
                                                    echo '<label>';
                                                    echo '<input type="checkbox" name="lldd_cat[]" value="' . $key . '"> &nbsp; ' . $value;
                                                    echo '</label>';
                                                    echo '</div>';
                                                }
                                                ?>
                                            </td>                                            
                                        </tr>
                                        <tr>
                                            <th class="text-right">
                                                As you have ticked more than one of the above, please select which disability, learning difficulty and/or health condition impacts most on your learning
                                            </th>
                                            <td>
                                                <?php echo HTML::selectChosen('primary_lldd', $LLDDCat_dropdown, null, true); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right">
                                                Would you like to benefit from a confidential interview
                                            </th>
                                            <td>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="discuss_als" value="1"> 
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="discuss_als" value="2"> 
                                                        No
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="step" id="step-4">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col style="width: 50%;">
                                    <tbody>
                                        <tr class="bg-gray">
                                            <th colspan="2">
                                                <h4 class="text-bold text-center">Contact and Marketing Information</h4>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="text-right">
                                                <label for="hear_us">How did you hear about us?</label><br>
                                            </th>
                                            <td>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="hear_us[]" value="1"> 
                                                        Current Employer
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="hear_us[]" value="2"> 
                                                        Job Center / Work Coach / DWP
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="hear_us[]" value="3"> 
                                                        Social Media
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="hear_us[]" value="4"> 
                                                        Friends / Family
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="hear_us[]" value="5"> 
                                                        FE college / training provider
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="hear_us[]" value="6"> 
                                                        THE National Careers Service
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="hear_us[]" value="7"> 
                                                        Gov.uk website
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="hear_us[]" value="8"> 
                                                        Other (e.g. search engine, local media press)
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="step" id="step-5">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col style="width: 50%;">
                                    <tbody>
                                        <tr class="bg-gray">
                                            <th colspan="2">
                                                <h4 class="text-bold text-center">Learner Declaration and Commitment</h4>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <p>
                                                    This privacy notice is issued by the Education and Skills Funding Agency (ESFA) on behalf of the Secretary of State 
                                                    for the Department of Education (DfE) to inform learners about the Individualised Learner Record (ILR) and how their 
                                                    personal information is used in the ILR. Your personal information is used by the DfE to exercise our functions under 
                                                    article 6(1)(e) of the UK GDPR and to meet our statutory responsibilities, including under the Apprenticeships, Skills, 
                                                    Children and Learning Act 2009. Our lawful basis for using your special category personal data is covered under Substantial 
                                                    Public Interest based in law (Article 9(2)(g)) of GDPR legislation. This processing is under Section 54 of the Further and 
                                                    Higher Education Act (1992).
                                                </p>
                                                <p>
                                                    The ILR collects data about learners and learning undertaken. Publicly funded colleges, training organisations, local authorities, 
                                                    and employers (FE providers) must collect and return the data to the ESFA each year under the terms of a funding agreement, contract 
                                                    or grant agreement. It helps ensure that public money distributed through the ESFA is being spent in line with government targets. 
                                                    It is also used for education, training, employment, and well-being purposes, including research.
                                                </p>
                                                <p>
                                                    We retain your ILR learner data for 20 years for operational purposes (e.g. to fund your learning and to publish official statistics). 
                                                    Your personal data is then retained in our research databases until you are aged 80 years so that it can be used for long-term research 
                                                    purposes. For more information about the ILR and the data collected, please see the ILR specification at 
                                                    <a target="_blank" href="https://www.gov.uk/government/collections/individualised-learner-record-ilr">https://www.gov.uk/government/collections/individualised-learner-record-ilr</a>
                                                </p>
                                                <p>
                                                    ILR data is shared with third parties where it complies with DfE data sharing procedures and where the law allows it. 
                                                    The DfE and the English European Social Fund (ESF) Managing Authority (or agents acting on their behalf) may contact 
                                                    learners to carry out research and evaluation to inform the effectiveness of training.
                                                </p>
                                                <p>
                                                    For more information about how your personal data is used and your individual rights, please see the DfE Personal Information Charter 
                                                    (<a target="_blank" href="https://www.gov.uk/government/organisations/department-for-education/about/personal-information-charter">https://www.gov.uk/government/organisations/department-for-education/about/personal-information-charter</a>) 
                                                    and the DfE Privacy Notice (<a target="_blank" href="https://www.gov.uk/government/publications/privacy-notice-for-key-stage-5-and-adult-education">https://www.gov.uk/government/publications/privacy-notice-for-key-stage-5-and-adult-education</a>)
                                                </p>
                                                <p>
                                                    If you would like to get in touch with us or request a copy of the personal information DfE holds about you, you can contact the DfE in the following ways:
                                                </p>
                                                <ul style="margin-left: 25px;">
                                                    <li>Using our online contact form <a href="https://form.education.gov.uk/service/Contact_the_Department_for_Education" target="_blank">https://form.education.gov.uk/service/Contact_the_Department_for_Education</a></li>
                                                    <li>By telephoning the DfE Helpline on 0370 000 2288</li>
                                                    <li>Or in writing to: Data Protection Officer, Department for Education (B2.28), 7 & 8 Wellington Place, Wellington Street, Leeds, LS1 4AW</li>
                                                </ul>
                                                <p>
                                                    If you are unhappy with how we have used your personal data, you can complain to the Information Commissioner's Office (ICO) at:
                                                    Wycliffe House, Water Lane, Wilmslow, Cheshire, SK9 5AF. You can also call their helpline on 0303 123 1113 or visit <a href="https://www.ico.org.uk" target="_blank">https://www.ico.org.uk</a>
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right">
                                                <label for="survey">I agree to take part in qualitative surveys or interviews that have been organised by the Department for Education</label>
                                            </th>
                                            <td>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="survey" value="Y"> 
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="survey" value="N"> 
                                                        No
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right">
                                                <label for="consent">I have read the Duplex Privacy Notice and consent to being contacted by the Authority for purposes set out in the Privacy Notice</label>
                                            </th>
                                            <td>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="survey" value="Y"> 
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="survey" value="N"> 
                                                        No
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right">
                                                <label for="consent">I agree to visual images being used for marketing purposes.</label>
                                            </th>
                                            <td>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="survey" value="Y"> 
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="survey" value="N"> 
                                                        No
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">
                                                <label for="consent">Your information may also be shared with other third parties for the above purposes, but only where the law
                                                     allowes it and the sharing is in compliance with data protection legislation. You can agree to be contacted for other purposes
                                                     by ticking any of the following boxes:</label>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="survey" value="Y"> 
                                                        About courses or learning opportunities
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="survey" value="N"> 
                                                        For research and evaluation purposes
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="survey" value="N"> 
                                                        By post
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="survey" value="N"> 
                                                        By phone
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="survey" value="N"> 
                                                        By email
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="step" id="step-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col style="width: 50%;">
                                    <tbody>
                                        <tr class="bg-gray">
                                            <th colspan="2">
                                                <h4 class="text-bold text-center">Signatures</h4>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="text-right">
                                                <label for="survey">I confirm that I have not attended another Skills Bootcamp during the current financial year (From 1st April 2023 onwards)</label>
                                            </th>
                                            <td>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="survey" value="Y"> 
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="survey" value="N"> 
                                                        No
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <span class="btn btn-info" onclick="getSignature();">
                                    <img id="img_learner_sign"
                                        src="do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=20"
                                        style="border: 2px solid;border-radius: 15px; width: 100%;"/>
                                    <input type="hidden" name="learner_sign" id="learner_sign" value=""/>
                                </span>
                            </div>
                            <div class="col-sm-4">
                                <h2 class="content-max-width"><?php echo date('d/m/Y'); ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="stepButtons" class="text-right">
                <button class="btn btn-primary prev" type="button">Previous</button>
                <button class="btn btn-primary next" type="button">Next</button>
            </div>
        </form>

        <footer class="footer text-center">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">

                    </div>
                    <div class="col-md-4 col-sm-12">
                        Nottingham - 0115 678 3800<br>
                        Wolverhampton - 01902 947740<br>
                        Peterborough - 01733 911910<br>
                        info@ev-training.org
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <img src="images/logos/SUNlogo.png">
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <div id="panel_signature" title="Signature Panel">
    <div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter your name/initials, then select the
        signature font you like and press "Add".
    </div>
    <div>
        <table class="table row-border">
            <tr>
                <td>Enter your name/initials</td>
                <td><input maxlength="23" type="text" id="signature_text" onkeypress="return onlyAlphabets(event,this);"/>
                    &nbsp; <span class="btn btn-sm btn-primary" onclick="refreshSignature();">Generate</span>
                </td>
            </tr>
            <tr>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img1" src=""/></td>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img2" src=""/></td>
            </tr>
            <tr>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img3" src=""/></td>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img4" src=""/></td>
            </tr>
            <tr>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img5" src=""/></td>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img6" src=""/></td>
            </tr>
            <tr>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img7" src=""/></td>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img8" src=""/></td>
            </tr>
        </table>
    </div>
</div>

    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script> -->

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>

    <script>
        $(document).ready(function() {
            var currentStep = 1;
            var totalSteps = $(".step").length;

            function updateStepDisplay() {
                $(".step").removeClass("active");
                $("#step-" + currentStep).addClass("active");
                $(".step-item").removeClass("active");
                $(".step-item[data-step='" + currentStep + "']").addClass("active");
            }

            $(".step-item").click(function() {
                currentStep = $(this).data("step");
                updateStepDisplay();
            });

            $(".next").click(function() {
                if (currentStep < totalSteps) {
                    currentStep++;
                    updateStepDisplay();
                }
            });

            $(".prev").click(function() {
                if (currentStep > 1) {
                    currentStep--;
                    updateStepDisplay();
                }
            });

            $('.datepicker').datepicker({
                dateFormat: 'dd/mm/yy',
                yearRange: 'c-50:c+50',
                changeMonth: false,
                changeYear: true,
                constrainInput: true,
                buttonImage: "/images/calendar-icon.gif",
                buttonImageOnly: true,
                buttonText: "Show calendar",
                showOn: "both",
                showAnim: "fadeIn"
            });

        });

        function numbersonlywithpoint(myfield, e, dec) {
            var key;
            var keychar;

            if (window.event)
                key = window.event.keyCode;
            else if (e)
                key = e.which;
            else
                return true;
            keychar = String.fromCharCode(key);

            // control keys
            if ((key == null) || (key == 0) || (key == 8) ||
                (key == 9) || (key == 13) || (key == 27))
                return true;

            // numbers
            else if ((("0123456789.").indexOf(keychar) > -1))
                return true;

            // decimal point jump
            else if (dec && (keychar == ".")) {
                myfield.form.elements[dec].focus();
                myfield.form.elements[dec].select();
                return false;
            } else
                return false;
        }
    </script>

<script>

var fonts = Array("Little_Days.ttf","ArtySignature.ttf","Signerica_Medium.ttf","Champignon_Alt_Swash.ttf","Bailey_MF.ttf","Carolina.ttf","DirtyDarren.ttf","Ruf_In_Den_Wind.ttf");
var sizes = Array(15,40,15,20,20,20,15,30);

function refreshSignature()
{
    for(var i = 1; i <= 8; i++)
        $("#img"+i).attr('src', 'images/loading.gif');

    for(var i = 0; i <= 7; i++)
        $("#img"+(i+1)).attr('src', 'do.php?_action=generate_image&title='+$("#signature_text").val()+'&font='+fonts[i]+'&size='+sizes[i]);
}

function loadDefaultSignatures()
{
    for(var i = 1; i <= 8; i++)
        $("#img"+i).attr('src', 'images/loading.gif');

    for(var i = 0; i <= 7; i++)
        $("#img"+(i+1)).attr('src', 'do.php?_action=generate_image&title=Signature'+'&font='+fonts[i]+'&size='+sizes[i]);
}

function onlyAlphabets(e, t)
{
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 32 || charCode == 39 || charCode == 45 || charCode == 8 || charCode == 46)
            return true;
        else
            return false;
    }
    catch (err) {
        alert(err.Description);
    }
}

function getSignature()
{
    $( "#panel_signature" ).dialog( "open");
}

function SignatureSelected(sig)
{
    $('.sigboxselected').attr('class','sigbox');
    sig.className = "sigboxselected";
}
$(function() {
    $( "#panel_signature" ).dialog({
        autoOpen: false,
        modal: true,
        draggable: false,
        width: "auto",
        height: 500,
        buttons: {
            'Add': function() {
                $("#img_learner_sign").attr('src',$('.sigboxselected').children('img')[0].src);
                $("#learner_sign").val($('.sigboxselected').children('img')[0].src);
                $(this).dialog('close');
            },
            'Cancel': function() {$(this).dialog('close');}
        }
    });
});
</script>
</body>

</html>