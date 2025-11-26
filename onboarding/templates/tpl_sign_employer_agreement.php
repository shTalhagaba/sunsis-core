<?php /* @var $employer Organisation */ ?>
<?php /* @var $location Location */ ?>
<?php /* @var $agreement EmployerAgreement */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Employer Agreement</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!--    <link rel="stylesheet" href="css/common.css" type="text/css"/>-->
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        html,
        body {
            height: 100%;
            font-size: medium;
        }
        textarea, input[type=text] {
            border:1px solid #3366FF;
            border-radius: 5px;
            border-left: 5px solid #3366FF;
        }
        input[type=checkbox] {
            transform: scale(1.4);
        }
        .sigbox {
            border-radius: 15px;
            border: 1px solid #EEE;
            cursor: pointer;
        }
        .sigboxselected {
            border-radius: 25px;
            border: 2px solid #EEE;
            cursor: pointer;
            background-color: #d3d3d3;
        }
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>
</head>

<body>


<br>

<div class="content-wrapper bg-gray-light" >

    <section class="content-header text-center"><h1><strong>Employer Agreement Particulars</strong></h1></section>

    <section class="content">
        <div class="container container-table">
            <div class="row vertical-center-row ">
                <div class="col-md-10 col-md-offset-1" style="background-color: white;">
                    <p><br></p>

                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <img class="img-responsive" src="<?php echo $logo; ?>" />
                        </div>
                        <div class="col-sm-4"></div>
                    </div>

                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmAgreement">
                        <input type="hidden" name="_action" value="save_sign_employer_agreement">
                        <input type="hidden" name="id" value="<?php echo $agreement->id; ?>">
                        <input type="hidden" name="employer_id" value="<?php echo $agreement->employer_id; ?>">
                        <input type="hidden" name="key" value="<?php echo $key; ?>">
                        <input type="hidden" name="pdf" value="0">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <col width="30%">
                                        <tr><th class="bg-gray">AGREEMENT NUMBER (EDRS):</th><td class="text-blue"><?php echo $agreement->agreement_number; ?></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col width="30%">
                                    <tr><th colspan="2" class="bg-gray">EMPLOYER DETAILS</th></tr>
                                    <tr><th>Company Name:</th><td class="text-blue"><?php echo $employer->legal_name; ?></td></tr>
                                    <tr><th>Trading As:</th><td class="text-blue"><?php echo $employer->trading_name; ?></td></tr>
                                    <tr>
                                        <th>Company Number:</th>
                                        <td class="text-blue">
                                            <input class="form-control" type="text" name="company_number"
                                                   id="company_number" value="<?php echo $agreement->company_number == '' ? $employer->company_number : $agreement->company_number;?>"
                                                   maxlength="10" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Employer Registered Address:</th>
                                        <td class="text-blue">
                                            <?php echo $agreement_first_location->address_line_1 != '' ? $agreement_first_location->address_line_1 . '<br>' : ''; ?>
                                            <?php echo $agreement_first_location->address_line_2 != '' ? $agreement_first_location->address_line_2 . '<br>' : ''; ?>
                                            <?php echo $agreement_first_location->address_line_3 != '' ? $agreement_first_location->address_line_3 . '<br>' : ''; ?>
                                            <?php echo $agreement_first_location->address_line_4 != '' ? $agreement_first_location->address_line_4 . '<br>' : ''; ?>
                                            <?php echo $agreement_first_location->postcode != '' ? $agreement_first_location->postcode . '<br>' : ''; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Type of Employer:</th>
                                        <td>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>Levy (DAS Account) <?php echo $agreement->funding_type == 'L' ? '<i class="fa fa-check-circle fa-lg text-green"></i>' : '' ?></td>
                                                    <td><?php echo in_array(DB_NAME, ["am_ela"]) ? 'Non-Levy' : 'Co-Investment'; ?> <?php echo $agreement->funding_type == 'CO' ? '<i class="fa fa-check-circle fa-lg text-green"></i>' : '' ?></td>
                                                    <td>Levy Gifted <?php echo $agreement->funding_type == 'LG' ? '<i class="fa fa-check-circle fa-lg text-green"></i>' : '' ?></td>
                                                </tr>
                                                <tr>
                                                    <td>New Employer <?php echo $agreement->employer_type == 'NE' ? '<i class="fa fa-check-circle fa-lg text-green"></i>' : '' ?></td>
                                                    <td>Existing Employer <?php echo $agreement->employer_type == 'EE' ? '<i class="fa fa-check-circle fa-lg text-green"></i>' : '' ?></td>
						    <td></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Employer Representative:</th>
                                        <td>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>Name: <?php echo '<span class="text-blue">' . $employer_rep->contact_name . '</span>'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Position: &nbsp; <?php echo '<input class="form-control" type="text" name="job_title" id="job_title" value="' . $employer_rep->job_title . '" maxlength="50" />'; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Email:  <?php echo '<span class="text-blue">' . $employer_rep->contact_email . '</span>'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Telephone: &nbsp; <?php echo '<input class="form-control" type="text" name="contact_telephone" id="contact_telephone" value="' . $employer_rep->contact_telephone . '" maxlength="50" />'; ?>
                                                    </td>
                                                </tr>
                                                
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Finance Contact:</th>
                                        <td>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>Name: <?php echo '<span class="text-blue"><input class="form-control" type="text" name="finance_contact_name" value="' . $agreement->finance_contact_name . '" /></span>'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Telephone: <?php echo '<span class="text-blue"><input class="form-control" type="text" name="finance_contact_telephone" value="' . $agreement->finance_contact_telephone . '" /></span>'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Email: <?php echo '<span class="text-blue"><input class="form-control" type="text" name="finance_contact_email" value="' . $agreement->finance_contact_email . '" /></span>'; ?></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Apprenticeship Service Contact:<br><span class="small text-info"><i class="fa fa-info-circle"></i> Levy / DAS</span></th>
                                        <td>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>Name:
                                                        <?php
                                                        $levy_class = $agreement->funding_type == 'L' ? 'compulsory' : '';
                                                        echo '<span class="text-blue"><input class="form-control ' . $levy_class. ' " type="text" name="levy_contact_name" value="' . $agreement->levy_contact_name . '" /></span>';
                                                        ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Telephone: <?php echo '<span class="text-blue"><input class="form-control" type="text" name="levy_contact_telephone" value="' . $agreement->levy_contact_telephone . '" /></span>'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Email: <?php echo '<span class="text-blue"><input class="form-control" type="text" name="levy_contact_email" value="' . $agreement->levy_contact_email . '" /></span>'; ?></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col width="30%">
                                    <tr><th colspan="2" class="bg-gray">TRAINING PROVIDER DETAILS</th></tr>
                                    <tr><th>Name:</th><td class="text-blue"><?php echo $tp->legal_name; ?></td></tr>
                                    <tr>
                                        <th>Registered Address:</th>
                                        <td class="text-blue">
                                            <?php echo $tp->trading_name . '<br>'; ?>
                                            <?php echo $tp_location->address_line_1 != '' ? $tp_location->address_line_1 . '<br>' : ''; ?>
                                            <?php echo $tp_location->address_line_2 != '' ? $tp_location->address_line_2 . '<br>' : ''; ?>
                                            <?php echo $tp_location->address_line_3 != '' ? $tp_location->address_line_3 . '<br>' : ''; ?>
                                            <?php echo $tp_location->address_line_4 != '' ? $tp_location->address_line_4 . '<br>' : ''; ?>
                                            <?php echo $tp_location->postcode != '' ? $tp_location->postcode . '<br>' : ''; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Registered Company Number:</th>
                                        <td class="text-blue">
                                            <?php echo $tp->company_number; ?>
                                        </td>
                                    </tr>
                                    <tr><th>UKPRN:</th><td class="text-blue"><?php echo $tp->ukprn; ?></td></tr>
                                    <tr><th>VAT Number:</th><td class="text-blue"><?php echo $tp->vat_number; ?></td></tr>
                                    <tr>
                                        <th>Training Provider Representative:</th>
                                        <td>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>Name: <?php echo '<span class="text-blue">' . $tp_rep->firstnames . ' ' . $tp_rep->surname . '</span>'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Position:  <?php echo '<span class="text-blue">' . $tp_rep->job_role . '</span>'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Email:  <?php echo '<span class="text-blue">' . $tp_rep->work_email . '</span>'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Telephone:  <?php echo '<span class="text-blue">' . $tp_rep->work_telephone . '</span>'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Mobile:  <?php echo '<span class="text-blue">' . $tp_rep->work_mobile . '</span>'; ?></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Postal Address:</th>
                                        <td class="text-blue">
                                            <?php echo $tp_location->address_line_1 != '' ? $tp_location->address_line_1 . '<br>' : ''; ?>
                                            <?php echo $tp_location->address_line_2 != '' ? $tp_location->address_line_2 . '<br>' : ''; ?>
                                            <?php echo $tp_location->address_line_3 != '' ? $tp_location->address_line_3 . '<br>' : ''; ?>
                                            <?php echo $tp_location->address_line_4 != '' ? $tp_location->address_line_4 . '<br>' : ''; ?>
                                            <?php echo $tp_location->postcode != '' ? $tp_location->postcode . '<br>' : ''; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col width="30%">
                                    <tr>
                                        <th>Agreement Expiry Date</th>
                                        <td>
                                            <?php echo Date::toShort($agreement->expiry_date); //HTML::datebox('expiry_date', $agreement->expiry_date, true); ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col width="80%">
                                    <tr><th colspan="2" class="bg-gray">SMALL EMPLOYER WAIVER</th></tr>
                                    <tr>
                                        <th>
                                            In the 365 days before the apprentice was recruited, how many employees
                                            (on average) did you employ?
                                        </th>
                                        <td>
                                            <input type="text" name="avg_no_of_employees" id="avg_no_of_employees" class="form-control compulsory" maxlength="5" size="5" onchange="setAvgEmployeesCheck();" onkeypress="return numbersonly();" value="<?php echo $agreement->avg_no_of_employees; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <p>
                                                <input type="radio" class="radioICheck" name="employer_incentive" value="1" disabled /> &nbsp;
                                                49 or fewer - may be eligible for small employer waiver (subject to availability
                                                - see Small Employer Incentive section of Schedule 1)
                                            </p>
                                            <p>
                                                <input type="radio" class="radioICheck" name="employer_incentive" value="2" disabled /> &nbsp;
                                                50 or more - ineligible for small employer waiver
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

			<?php if(SystemConfig::getEntityValue($link, "emp_aggr_bank_section")) {?>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <tr><th colspan="2" class="bg-gray">Employer Bank Details</th></tr>
                                    <tr>
                                        <td colspan="2">
                                            <p>
                                                Employers who are eligible for the &pound;1,000 16-18 Employer Incentive
                                                or 19-24 or Care Leaver's Incentive as detailed on the Schedule 1 document
                                                will need to provide their company bank details.
                                            </p>
                                            <p>
                                                Employers who are not eligible for this incentive are not required to provide
                                                their bank details.
                                            </p>
                                            <p>
                                                By providing these details, I confirm that the Training Provider are authorised
                                                to pay the apprenticeship incentive payment, when due, into the account as
                                                detailed below.
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Name of Bank</td>
                                        <td>
                                            <input type="text" class="form-control" name="bank_name" value="<?php echo $agreement->bank_name; ?>" maxlength="149" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Account Name</td>
                                        <td>
                                            <input type="text" class="form-control" name="account_name" value="<?php echo $agreement->account_name; ?>" maxlength="149" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Sort Code</td>
                                        <td>
                                            <input type="text" class="form-control" name="sort_code" value="<?php echo $agreement->sort_code; ?>" maxlength="8" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Account Number</td>
                                        <td>
                                            <input type="text" class="form-control" name="account_number" value="<?php echo $agreement->account_number; ?>" maxlength="10" />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
			<?php } ?>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="well well-sm">
				    <?php if( in_array(DB_NAME, ["am_ela"]) && $agreement->id > 494 ) { ?> 
                                    <p style="margin-top: 5px;">
                                        <a style="margin-top: 2px;" class="btn btn-info btn-sm" href="do.php?_action=downloader&f=policies/Employer-Agreement-Terms-and-Conditions 2324.pdf"><i class="fa fa-info-circle"></i> Agreement Terms</a>
                                    </p>
                                    <?php } else {?>
                                    <p style="margin-top: 5px;">
                                        <a style="margin-top: 2px;" class="btn btn-info btn-sm" href="do.php?_action=downloader&f=policies/Employer-Agreement-Terms-and-Conditions.pdf"><i class="fa fa-info-circle"></i> Agreement Terms</a>
                                    </p>
				    <?php } ?>
                                    <p style="margin-top: 5px;">
                                        This agreement is entered into on the date set out above and is made up of these
                                        Agreement Particulars, the Agreement Terms and the Schedules stated above.
                                    </p>
                                    <p>
                                        <input class="clsICheck" type="checkbox" name="read_terms" value="1" />
                                        <label>
                                            Click here to confirm that you have read and accept Agreement Terms
                                        </label>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 table-responsive">
                                <table style="margin-top: 5px;" class="table table-bordered">
                                    <tr><th colspan="4" class="bg-gray">Signatures</th></tr>
                                    <tr><th>Your Name</th><th>Signature</th><th>Date</th></tr>
                                    <tr>
                                        <td>
                                            <?php echo '<input type="text" class="form-control compulsory" name="employer_sign_name" id="employer_sign_name" value="' . $agreement->employer_sign_name . '" placeholder="Please enter your name" />'; ?>
					                        <span class="small text-info"><i class="fa fa-info-circle"></i> Please enter your name and not the company name</span>
                                        </td>
                                        <td>
                                        <span class="btn btn-info" onclick="getSignature('manager');">
                                            <img id="img_employer_sign" src="do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" />
                                            <input type="hidden" name="employer_sign" id="employer_sign" value="" />
                                        </span>
                                        </td>
                                        <td>
                                            <?php
                                            $agreement_employer_sign_date = $agreement->employer_sign_date == '' ? date('d/m/Y') : $agreement->employer_sign_date;
                                            echo Date::toShort($agreement_employer_sign_date);
                                            echo '<input type="hidden" name="employer_sign_date" value="' . $agreement_employer_sign_date . '" />';
                                            //echo '<span class="content-max-width">' . HTML::datebox('employer_sign_date', $agreement_employer_sign_date) . '</span>'; 
                                            ?>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <span class="btn btn-block btn-success btn-lg" onclick="submitAgreement();">
                                    <i class="fa fa-save"></i> Submit Information
                                </span>
                                <p></p>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>


</div>

<div id="panel_signature" title="Signature Panel">
    <div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter your name, press 'Generate' and select the signature font you like and press "Create". </div>
    <div class="table-responsive">
        <table class="table row-border">
            <tr>
                <td class="small">Enter your name</td>
                <td><input type="text" id="signature_text" onkeypress="return onlyAlphabets(event,this);" /> &nbsp; <span class="btn btn-xs btn-primary" onclick="refreshSignature();">Generate</span> </td>
            </tr>
            <tr>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img1" src=""  /></td>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img2" src=""  /></td>
            </tr>
            <tr>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img3" src=""  /></td>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img4" src=""  /></td>
            </tr>
            <tr>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img5" src=""  /></td>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img6" src=""  /></td>
            </tr>
            <tr>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img7" src=""  /></td>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img8" src=""  /></td>
            </tr>
        </table>
    </div>
</div>


<footer class="main-footer">
    <div class="pull-left">
        <img width="230px" src="<?php echo $logo; ?>" />
    </div>
    <div class="pull-right">
        <img src="images/logos/SUNlogo.png" />
    </div>
</footer>



<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="js/common_nto.js"></script>

<script type="text/javascript">

    var phpManagerSignature = '<?php echo $agreement->employer_sign; ?>';

    var fonts = Array("Little_Days.ttf","ArtySignature.ttf","Signerica_Medium.ttf","Champignon_Alt_Swash.ttf","Bailey_MF.ttf","Carolina.ttf","DirtyDarren.ttf","Ruf_In_Den_Wind.ttf");
    var sizes = Array(30,40,15,30,30,30,25,30);

    $(function() {

        $("input[type=checkbox]:checked").each(function() {
            $(this).closest('tr').addClass('bg-green');
        });

        $('.clsICheck').each(function(){
            var self = $(this),
                label = self.next(),
                label_text = label.text();

            label.remove();
            self.iCheck({
                checkboxClass: 'icheckbox_line-orange',
                insert: '<div class="icheck_line-icon"></div>' + label_text
            });
        });

        //$('input[class=radioICheck]').iCheck({radioClass: 'iradio_square-green', increaseArea: '20%'});

        $( "#panel_signature" ).dialog({
            autoOpen: false,
            modal: true,
            draggable: false,
            width: "auto",
            height: 500,
            buttons: {
                'Create': function() {
                    var panel = $(this).data('panel');
                    if($('#signature_text').val() == '')
                    {
                        alert('Please input name/initials to generate signature.');
                        $('#signature_text').focus();
                        return;
                    }
                    if($('.sigboxselected').children('img')[0] === undefined)
                    {
                        alert('Please select your font');
                        return;
                    }
                    var sign_field = '';
                    if(panel == 'manager')
                    {
                        sign_field = 'employer_sign';
                    }
                    $("#img_"+sign_field).attr('src', $('.sigboxselected').children('img')[0].src);
                    var _link = $('.sigboxselected').children('img')[0].src;
                    _link = _link.split('&');
                    $("#"+sign_field).val(_link[1]+'&'+_link[2]+'&'+_link[3]);
                    if($('#'+sign_field).val() == '')
                    {
                        alert('Please create your signature');
                        return;
                    }

                    $(this).dialog('close');
                },
                'Cancel': function() {$(this).dialog('close');}
            }
        });

        $("input[name^=minimis_]").not("input[name^=minimis_total]").on('change', function(){
            updateStats();
        });

    });

    function updateStats()
    {
        var frmAgreement = document.forms["frmAgreement"];
        var v1 = frmAgreement.minimis_201718.value == '' ? 0 : parseFloat(frmAgreement.minimis_201718.value);
        var v2 = frmAgreement.minimis_201819.value == '' ? 0 : parseFloat(frmAgreement.minimis_201819.value);
        var v3 = frmAgreement.minimis_201920.value == '' ? 0 : parseFloat(frmAgreement.minimis_201920.value);

        var v4 = v1+v2+v3;
        frmAgreement.minimis_total.value = v4;
    }

    function setAvgEmployeesCheck()
    {
        if($('#avg_no_of_employees').val() <= 49)
            $("input[name=employer_incentive][value=1]").prop('checked', true);
        if($('#avg_no_of_employees').val() >= 50)
            $("input[name=employer_incentive][value=2]").prop('checked', true);
    }

    function getSignature(user)
    {
        $('#signature_text').val($('#employer_sign_name').val());
        $( "#panel_signature" ).data('panel', 'manager').dialog( "open");
        return;
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

    function SignatureSelected(sig)
    {
        $(".sigboxselected").attr("class", "sigbox");
        sig.className = "sigboxselected";
    }

    function refreshSignature()
    {
        for(var i = 1; i <= 8; i++)
            $("#img"+i).attr('src', 'images/loading.gif');

        for(var i = 0; i <= 7; i++)
            $("#img"+(i+1)).attr('src', 'do.php?_action=generate_image&title='+$("#signature_text").val()+'&font='+fonts[i]+'&size='+sizes[i]);
    }

    function submitAgreement()
    {
        var frmAgreement = document.forms['frmAgreement'];
        if(!validateForm(frmAgreement))
        {
            return;
        }

        if(!$("input[name=read_terms]").prop('checked'))
        {
            alert('Please tick the box to confirm you have read Agreement Terms');
            $("input[name=read_terms]").focus();
            return;
        }

        var employer_sign = frmAgreement.elements["employer_sign"];
        if(employer_sign.value.trim() == '')
        {
            alert('Please provide your signature.');
            return;
        }

        frmAgreement.submit();
    }

</script>

</body>
</html>
