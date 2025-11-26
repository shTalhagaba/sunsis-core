
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $client_name; ?> - Learner Registration Form</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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
        #home_postcode, #ni {
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

<body class="bg-light">



<div class="container" >
    <div class="row">
        <div class="col-md-4 col-sm-12 text-center">
            <img src="images/logos/Skills_For_Life_Logo_Black_RGB.png" alt="Skills for Life Logo" height="129" width="300">
        </div>
        <div class="col-md-4 col-sm-12">
            <img class="img-responsive center-block" src="<?php echo $header_image1; ?>" alt="Logo" height="129" width="300">
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="text-center">            
                <h1>WAVE 4- Green Technologies</h1>
                <h2>Skills Bootcamp Enrolment Form</h2>
                <h3>Electric/Hybrid Vehicle Qualification</h3>
            </div>
        </div>
    </div>    

    <div class="row">
        <div class="col-sm-12">
            <table class="table table-bordered">
                <tr class="bg-gray"><th colspan="2"><h3 class="text-bold text-center">Applicant Information</h3></th></tr>
                <tr>
                    <th class="text-right"><label for="title">Title:</label></th>
                    <td>
                        <?php echo HTML::selectChosen('title', $titlesDdl, null, true, true); ?>
                    </td>
                </tr>
                <tr>
                    <th class="text-right"><label for="surname">Surname/Family Name:</label></th>
                    <td>
                        <input type="text" class="form-control" name="surname" id="surname" maxlength="70" required>
                    </td>
                </tr>
                <tr>
                    <th class="text-right"><label for="firstnames">First Name(s) in full:</label></th>
                    <td>
                        <input type="text" class="form-control" name="firstnames" id="firstnames" maxlength="70" required>
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
                            <label for="home_address_line_1" class="control-label">Building & Street Name:</label>
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
                        <?php 
                        $gendersDdl = [
                            'M' => 'Male',
                            'F' => 'Female',
                            'O' => 'Other',
                            'P' => 'Prefer not to say',
                        ];
                        foreach($gendersDdl AS $key => $value)
                        {
                            echo '<div class="radio">';
                            echo '<label>';
                            echo '<input type="radio" name="gender" value="' . $key . '"> ' . $value;
                            echo '</label>';
                            echo '</div>';
                        }
                        ?>
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
                        <?php 
                        foreach($ethnicityDdl AS $ethnicity)
                        {
                            $key = $ethnicity['Ethnicity'];
                            $value = $ethnicity['Ethnicity_Desc'];
                            if($key == '99')
                            {
                                $value = 'Not Known / Prefer not to say';
                            }
                            echo '<div class="radio">';
                            echo '<label>';
                            echo '<input type="radio" name="ethnicity" value="' . $key . '"> ' . $value;
                            echo '</label>';
                            echo '</div>';
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <table class="table table-bordered">
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
            </table>
            <table class="table table-bordered">
                <tr>
                    <td>
                        <span class="btn btn-success btn-block">Click to Submit Information</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

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
                    <img src="images/logos/SUNlogo.png"/>
                </div>
            </div>
        </div>
    </footer>

</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>

<script type="text/javascript">

    var phpLearnerSignature = '';

    $(function() {

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

    function submitForm()
    {
        var frmHs = document.forms["frmHs"];
        if(frmHs.learner_sign.value == '')
        {
            alert('Please provide your signature.');
            return;
        }

        frmHs.submit();
    }

</script>

</html>
