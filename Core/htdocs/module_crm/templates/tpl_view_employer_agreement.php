<?php /* @var $employer Organisation */ ?>
<?php /* @var $location Location */ ?>
<?php /* @var $tp_location Location */ ?>
<?php /* @var $agreement EmployerAgreement */ ?>
<?php /* @var $employer_rep OrganisationContact */ ?>
<?php /* @var $tp Organisation */ ?>
<?php /* @var $tp_rep User */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>View Employer Agreement</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="css/common_ob.css" type="text/css"/>
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
	</style>
</head>

<body>

<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">View Employer Agreement</div>
			<div class="ButtonBar">
				<span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
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

<br>

<div class="content-wrapper bg-gray-light" >

	<section class="content-header text-center"><h1><strong>Employer Agreement Particulars</strong></h1></section>

	<section class="content">
		<div class="container container-table">
			<div class="row vertical-center-row">
				<div class="col-md-10 col-md-offset-1" style="background-color: white;">
					<p><br></p>

					<div class="row">
                        <div class="col-sm-4"></div>
						<div class="col-sm-4 text-center">
                            <div style="margin: 0 auto; display: inline-block">
                                <div class="text-right">
                                    <?php
                                    if($agreement->status == EmployerAgreement::TYPE_CREATED)
                                        echo '<label class="label label-info">CREATED</label>';
                                    if($agreement->status == EmployerAgreement::TYPE_SENT)
                                        echo '<label class="label label-warning">SENT TO EMPLOYER</label>';
                                    if($agreement->status == EmployerAgreement::TYPE_SIGNED_BY_EMPLOYER)
                                        echo '<label class="label label-success">SIGNED BY EMPLOYER</label>';
                                    if($agreement->status == EmployerAgreement::TYPE_COMPLETED)
                                        echo '<label class="label bg-green-gradient">COMPLETED</label>';
                                    ?>
                                </div>
                                <img class="img-responsive" src="<?php echo $logo; ?>" />
                            </div>
						</div>
                        <div class="col-sm-4"></div>
					</div>

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
                                <tr><th>Company Number:</th><td class="text-blue"><?php echo $employer->company_number; ?></td></tr>
                                <tr>
                                    <th>Employer Registered Address:</th>
                                    <td class="text-blue">
                                        <?php echo $location->address_line_1 != '' ? $location->address_line_1 . '<br>' : ''; ?>
                                        <?php echo $location->address_line_2 != '' ? $location->address_line_2 . '<br>' : ''; ?>
                                        <?php echo $location->address_line_3 != '' ? $location->address_line_3 . '<br>' : ''; ?>
                                        <?php echo $location->address_line_4 != '' ? $location->address_line_4 . '<br>' : ''; ?>
                                        <?php echo $location->postcode != '' ? $location->postcode . '<br>' : ''; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Type of Employer:</th>
                                    <td>
                                        <table class="table table-bordered">
                                            <tr>
                                                <td>Levy (DAS Account) <?php echo $agreement->funding_type == 'L' ? '<i class="fa fa-check-circle fa-lg text-green"></i>' : '' ?></td>
                                                <td>Co-Investment <?php echo $agreement->funding_type == 'CO' ? '<i class="fa fa-check-circle fa-lg text-green"></i>' : '' ?></td>
                                            </tr>
                                            <tr>
                                                <td>New Employer <?php echo $agreement->employer_type == 'NE' ? '<i class="fa fa-check-circle fa-lg text-green"></i>' : '' ?></td>
                                                <td>Existing Employer <?php echo $agreement->employer_type == 'EE' ? '<i class="fa fa-check-circle fa-lg text-green"></i>' : '' ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Employer Representative:</th>
                                    <td>
                                        <?php if ($employer_rep): ?>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>Name: <?php echo '<span class="text-blue">' . $employer_rep->contact_name . '</span>'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Position:  <?php echo '<span class="text-blue">' . $employer_rep->job_title . '</span>'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Email:  <?php echo '<span class="text-blue">' . $employer_rep->contact_email . '</span>'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Telephone:  <?php echo '<span class="text-blue">' . $employer_rep->contact_telephone . '</span>'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Postal Address:<br>
                                                        <span class="text-blue">
                                                            <?php
                                                            echo $location->address_line_1 != '' ? $location->address_line_1 . '<br>' : '';
                                                            echo $location->address_line_2 != '' ? $location->address_line_2 . '<br>' : '';
                                                            echo $location->address_line_3 != '' ? $location->address_line_3 . '<br>' : '';
                                                            echo $location->address_line_4 != '' ? $location->address_line_4 . '<br>' : '';
                                                            echo $location->postcode != '' ? $location->postcode : '';
                                                            ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Finance Contact:</th>
                                    <td>
                                        <table class="table table-bordered">
                                            <tr>
                                                <td>Name:  <?php echo '<span class="text-blue">' . $agreement->finance_contact_name . '</span>'; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Telephone:   <?php echo '<span class="text-blue">' . $agreement->finance_contact_telephone . '</span>'; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Email:   <?php echo '<span class="text-blue">' . $agreement->finance_contact_email . '</span>'; ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Levy Contact:</th>
                                    <td>
                                        <table class="table table-bordered">
                                            <tr>
                                                <td>Name:   <?php echo '<span class="text-blue">' . $agreement->levy_contact_name . '</span>'; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Telephone:    <?php echo '<span class="text-blue">' . $agreement->levy_contact_telephone . '</span>'; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Email:    <?php echo '<span class="text-blue">' . $agreement->levy_contact_email . '</span>'; ?></td>
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
                                <tr><th>Registered Address:</th><td class="text-blue"><?php echo $tp->trading_name; ?></td></tr>
                                <tr><th>Registered Company Number:</th><td class="text-blue"><?php echo $tp->company_number; ?></td></tr>
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
                                    <td>
                                        <span class="text-blue">
                                            <?php
                                            echo $tp_location->address_line_1 != '' ? $tp_location->address_line_1 . '<br>' : '';
                                            echo $tp_location->address_line_2 != '' ? $tp_location->address_line_2 . '<br>' : '';
                                            echo $tp_location->address_line_3 != '' ? $tp_location->address_line_3 . '<br>' : '';
                                            echo $tp_location->address_line_4 != '' ? $tp_location->address_line_4 . '<br>' : '';
                                            echo $tp_location->postcode != '' ? $tp_location->postcode . '<br>' : '';
                                            ?>
                                        </span>
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
                                    <td class="text-blue"><?php echo Date::toShort($agreement->expiry_date); ?></td>
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
                                    <td  class="text-blue">
                                        <?php echo $agreement->avg_no_of_employees; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <p>
                                            <?php echo $agreement->avg_no_of_employees <= 49 ? '<i class="fa fa-check-circle fa-lg text-green"></i>' : '' ?> &nbsp;
                                            49 or fewer - may be eligible for small employer waiver (subject to availability
                                            - see Small Employer Incentive section of Schedule 1)
                                        </p>
                                        <p>
                                            <?php echo $agreement->avg_no_of_employees >= 50 ? '<i class="fa fa-check-circle fa-lg text-green"></i>' : '' ?> &nbsp;
                                            50 or more - ineligible for small employer waiver
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

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
                                            By providing these details, I confirm that Training Provider are authorised
                                            to pay the apprenticeship incentive payment, when due, into the account as
                                            detialed below.
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Name of Bank</td>
                                    <td class="text-blue">
                                        <?php echo $agreement->bank_name; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Account Name</td>
                                    <td class="text-blue">
                                        <?php echo $agreement->account_name; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sort Code</td>
                                    <td class="text-blue">
                                        <?php echo $agreement->sort_code; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Account Number</td>
                                    <td class="text-blue">
                                        <?php echo $agreement->account_number; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <p style="margin-top: 5px;">
                                <a style="margin-top: 2px;" class="btn btn-info btn-sm text-white" href="do.php?_action=downloader&f=policies/Employer-Agreement-Terms-and-Conditions.pdf"><i class="fa fa-info-circle"></i> Agreement Terms</a>
                            </p>
                            <p style="margin-top: 5px;">
                                This agreement is entered into on the date set out above and is made up of these
                                Agreement Particulars, the Agreement Terms and the Schedules stated above.
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 table-responsive">
                            <table style="margin-top: 5px;" class="table table-bordered">
                                <tr><th colspan="4" class="bg-gray">Signatures</th></tr>
                                <tr><th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th></tr>
                                <tr>
                                    <td>Employer</td>
                                    <td>
                                        <?php echo $agreement->employer_sign_name; ?>
                                    </td>
                                    <td>
                                        <img id="img_m_sign" src="do.php?_action=generate_image&<?php echo $agreement->employer_sign; ?>" style="border: 2px solid;border-radius: 15px;" />
                                    </td>
                                    <td>
                                        <?php echo Date::toShort($agreement->employer_sign_date); ?>
                                    </td>
                                </tr>
                                <?php if($agreement->status == EmployerAgreement::TYPE_COMPLETED){?>
                                    <tr>
                                        <td>Provider</td>
                                        <td>
                                            <?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$agreement->provider_sign_id}'"); ?>
                                        </td>
                                        <td>
                                            <img src="do.php?_action=generate_image&<?php echo $agreement->provider_sign; ?>" style="border: 2px solid;border-radius: 15px;" />
                                        </td>
                                        <td>
                                            <?php echo Date::toShort($agreement->provider_sign_date); ?>
                                        </td>
                                    </tr>
                                <?php } ?>

                            </table>
                        </div>
                    </div>

                    <?php if($agreement->status == EmployerAgreement::TYPE_SIGNED_BY_EMPLOYER){?>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmAgreement">
                        <input type="hidden" name="_action" value="save_provider_sign_employer_agreement">
                        <input type="hidden" name="id" value="<?php echo $agreement->id; ?>">
                        <input type="hidden" name="employer_id" value="<?php echo $agreement->employer_id; ?>">
                        <div class="row">
                            <div class="col-sm-12 table-responsive">
                                <table style="margin-top: 5px;" class="table table-bordered">
                                    <tr><th colspan="4" class="bg-gray">Signatures</th></tr>
                                    <tr><th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th></tr>
                                    <tr>
                                        <td>Provider</td>
                                        <td>
                                            <?php echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?>
                                        </td>
                                        <td>
                                            <span class="btn btn-info" onclick="getSignature('provider');">
                                                <img id="img_provider_sign" src="do.php?_action=generate_image&<?php echo $agreement->provider_sign != '' ? $agreement->provider_sign : 'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
                                                <input type="hidden" name="provider_sign" id="provider_sign" value="<?php echo $agreement->provider_sign; ?>" />
                                            </span>
                                        </td>
                                        <td>
                                            <?php echo $agreement->provider_sign_date != '' ? Date::toShort($agreement->provider_sign_date) : date('d/m/Y'); ?>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-sm-12">
                            <p><br></p>
                            <span class="btn btn-block btn-success btn-lg" onclick="completeAgreement();">
                                <i class="fa fa-save"></i> Save Agreement
                            </span>
                            <p><br></p>
                            <p><br></p>
                        </div>
                    </div>
                    <?php } ?>

                </div>
			</div>
		</div>
	</section>

    <div id="panel_signature" title="Signature Panel">
        <div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter the name/initials, press 'Generate' and select the signature font you like and press "Create". </div>
        <div class="table-responsive">
            <table class="table row-border">
                <tr>
                    <td class="small">Enter the name/initials</td>
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

</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript">

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
                checkboxClass: 'icheckbox_line-blue',
                insert: '<div class="icheck_line-icon"></div>' + label_text
            });
        });

        // $('input[class=radioICheck]').iCheck({radioClass: 'iradio_square-green', increaseArea: '20%'});
	});

    var phpProviderSignature = '<?php echo is_null($agreement->provider_sign) ? $_SESSION['user']->signature : $agreement->provider_sign; ?>';
    var fonts = Array("Little_Days.ttf","ArtySignature.ttf","Signerica_Medium.ttf","Champignon_Alt_Swash.ttf","Bailey_MF.ttf","Carolina.ttf","DirtyDarren.ttf","Ruf_In_Den_Wind.ttf");
    var sizes = Array(30,40,15,30,30,30,25,30);

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

    function SignatureSelected(sig)
    {
        $(".sigboxselected").attr("class", "sigbox");
        sig.className = "sigboxselected";
    }

    $(function(){
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
                    var sign_field = 'provider_sign';
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
    });

    function getSignature(user)
    {
        if(window.phpProviderSignature == '')
        {
            $('#signature_text').val('');
            $( "#panel_signature" ).data('panel', 'provider').dialog( "open");
            return;
        }
        $('#img_provider_sign').attr('src', 'do.php?_action=generate_image&'+window.phpProviderSignature);
        $('#provider_sign').val(window.phpProviderSignature);

        return;
    }

    function completeAgreement()
    {
        var frmAgreement = document.forms['frmAgreement'];
        var provider_sign = frmAgreement.elements["provider_sign"];
        if(provider_sign.value.trim() == '')
        {
            alert('Please provide your signature.');
            return;
        }

        frmAgreement.submit();
    }
</script>

</body>
</html>
