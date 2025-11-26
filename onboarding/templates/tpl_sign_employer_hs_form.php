<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Employer Health & Safety Form</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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
        input[type=checkbox], input[type=radio] {
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

<div class="content-wrapper" >

    <section class="content-header text-center"><h1><strong>Work Placement Health and Safety Checklist</strong></h1></section>

    <section class="content">
        <div class="container-fluid container-table">
            <div class="row vertical-center-row">
                <div class="col-sm-12" style="background-color: white;">
                    <p><br></p>

                    <div class="row">
                        <div class="col-sm-4"><img class="img-responsive" src="images/logos/app_logo.jpg" /></div>
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <img class="img-responsive" src="<?php echo $logo; ?>" />
                        </div>
                    </div>

                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmEmployerHsForm">
                        <input type="hidden" name="_action" value="save_sign_employer_hs_form">
                        <input type="hidden" name="hs_id" value="<?php echo $hs_form->hs_id; ?>">
                        <input type="hidden" name="employer_id" value="<?php echo $employer_id; ?>">
                        <input type="hidden" name="key" value="<?php echo $key; ?>">

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col width="20%">
                                    <col width="20%">
                                    <col width="60%">
                                    <tr>
                                        <th>Company Name</th>
                                        <td><?php echo $employer->legal_name; ?></td>
                                        <td>
                                            <table class="table row-border">
                                                <tr>
                                                    <th>Contact Person</th>
                                                    <td>
                                                        <input class="form-control" type="text" name="employer_rep_contact_name" id="employer_rep_contact_name" value="<?php echo isset($employer_rep_contact->contact_name) ? $employer_rep_contact->contact_name : ''; ?>">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Telephone Number</th>
                                                    <td>
                                                        <input class="form-control" type="text" name="employer_rep_contact_tel" id="employer_rep_contact_tel" value="<?php echo isset($employer_rep_contact->contact_telephone) ? $employer_rep_contact->contact_telephone : ''; ?>">
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Total number of employees</th>
                                        <td>
                                            <?php echo $employer->site_employees; ?>
                                            <input class="form-control" type="text" name="site_employees" id="site_employees" value="<?php echo isset($employer->site_employees) ? $employer->site_employees : ''; ?>" onkeypress="return numbersonly(this);" maxlength="4">
                                        </td>
                                        <td>
                                            <table class="table row-border">
                                                <tr>
                                                    <th>Health & Safety contact name and email</th>
                                                    <td>
                                                        <input class="form-control" type="text" name="hs_contact_name" id="hs_contact_name" value="<?php echo isset($hs_contact->contact_name) ? $hs_contact->contact_name : ''; ?>"> <br> 
                                                        <input class="form-control" type="text" name="hs_contact_email" id="hs_contact_email" value="<?php echo isset($hs_contact->contact_email) ? $hs_contact->contact_email : ''; ?>">
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Address & Postcode</th>
                                        <td>
                                            <?php 
                                            echo $mainLocation->address_line_1 != '' ? $mainLocation->address_line_1 . '<br>' : ''; 
                                            echo $mainLocation->address_line_2 != '' ? $mainLocation->address_line_2 . '<br>' : ''; 
                                            echo $mainLocation->address_line_3 != '' ? $mainLocation->address_line_3 . '<br>' : ''; 
                                            echo $mainLocation->address_line_4 != '' ? $mainLocation->address_line_4 . '<br>' : ''; 
                                            echo $mainLocation->postcode != '' ? $mainLocation->postcode . '<br>' : ''; 
                                            ?>
                                        </td>
                                        <td>
                                            <table class="table row-border">
                                                <tr>
                                                    <th>Telephone</th>
                                                    <td>
                                                        <input class="form-control" type="text" name="location_telephone" id="location_telephone" value="<?php echo $mainLocation->telephone; ?>">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Fax</th>
                                                    <td>
                                                        <input class="form-control" type="text" name="location_fax" id="location_fax" value="<?php echo $mainLocation->fax; ?>">
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Website Address</th>
                                        <td colspan="2">
                                            <input class="form-control" type="text" name="employer_url" id="employer_url" value="<?php echo $employer->url; ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>ERN</th>
                                        <td colspan="2">
                                            <input class="form-control" type="text" name="employer_edrs" id="employer_edrs" value="<?php echo $employer->edrs; ?>">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="box box-success box-solid small">
                                    <div class="box-header">
                                        <span class="box-title text-center">Nature of Business</span>
                                    </div>
                                    <div class="box-body">
                                        <textarea name="nature_of_business" id="nature_of_business" rows="5" style="width: 100%;"><?php echo isset($detail->nature_of_business) ? nl2br($detail->nature_of_business) : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="box box-success box-solid small">
                                    <div class="box-header">
                                        <span class="box-title text-center">Health and Safety Standards</span>
                                    </div>
                                    <div class="box-body">
                                        <?php 
                                        for($i = 1; $i <= 11; $i++)
                                        {
                                            echo '<table class="table table-bordered">';
                                            echo '<thead class="bg-info"><tr>';
                                            echo '<th>' . $i . '</th>';
                                            echo '<th style="width: 50%;">' . DAO::getSingleValue($link, "SELECT section_title FROM lookup_employer_health_safety_questions WHERE serial_n = '{$i}' LIMIT 1") . '</th>';
                                            echo '<th align="center">Yes</th>';
                                            echo '<th align="center">No</th>';
                                            echo '<th>Evidence and Comments</th>';
                                            echo '</tr></thead><tbody>';
                                            $records = DAO::getResultset($link, "SELECT * FROM lookup_employer_health_safety_questions WHERE serial_n = '{$i}'", DAO::FETCH_ASSOC);
                                            foreach($records AS $row)
                                            {
                                                $q = "q{$row['id']}";
                                                $ec = "evidence_and_comments{$row['id']}";
                                                echo '<tr>';
                                                echo '<th>' . $row['serial_c'] . '</th>';
                                                echo '<td>' . $row['question'] . '</td>';
                                                echo (isset($detail->$q) && $detail->$q == 'Yes') ?
                                                        '<td align="center"><input type="radio" name="' . $q . '" value="Yes" checked /></td>' :
                                                        '<td align="center"><input type="radio" name="' . $q . '" value="Yes" /></td>';
                                                echo (isset($detail->$q) && $detail->$q == 'No') ?
                                                        '<td align="center"><input type="radio" name="' . $q . '" value="No" checked /></td>' :
                                                        '<td align="center"><input type="radio" name="' . $q . '" value="No" /></td>';
                                                if($row['serial_n'] == '10' && $row['serial_c'] == 'A')
                                                {
                                                    echo '<td>';
                                                    echo '<span>Insurer\'s name: </span> &nbsp; <input class="form-control" type="text" name="el_insurer" id="el_insurer" value="' . $hs->el_insurer . '" maxlength="180" placeholder="Enter Insurer name" /><br>';
                                                    echo '<span>Policy number: </span> &nbsp; <input class="form-control" type="text" name="el_insurance" id="el_insurance" value="' . $hs->el_insurance . '" maxlength="25" placeholder="Enter Policy number" /><br>';
                                                    echo '<span>Expiry date: </span> <br>' . HTML::datebox('el_date', $hs->el_date);
                                                    echo '</td>';
                                                }
                                                else
                                                {
                                                    echo isset($detail->$ec) ?
                                                        '<td><textarea name="' . $ec . '" rows="3" style="width: 100%;">' . nl2br($detail->$ec) . '</textarea></td>' :
                                                        '<td><textarea name="' . $ec . '" rows="3" style="width: 100%;"></textarea></td>';
                                                }
                                                echo '</tr>';
                                            }
                                            echo '</tbody></table>';
                                        }
                                        
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 table-responsive">
                                <table style="margin-top: 5px;" class="table table-bordered">
                                    <tr><th colspan="3" class="bg-gray">Signatures</th></tr>
                                    <tr><th>Your Name</th><th>Signature</th><th>Date</th></tr>
                                    <tr>
                                        <td>
                                            <?php echo '<input type="text" class="form-control compulsory" name="employer_sign_name" id="employer_sign_name" value="' . $hs->employer_sign_name . '" placeholder="Please enter your name" />'; ?>
					    <span class="small text-info"><i class="fa fa-info-circle"></i> Please enter your name and not the company name</span>
                                        </td>
                                        <td>
                                            <span class="btn btn-info" onclick="getSignature('tp');">
                                                <img id="img_employer_sign" src="do.php?_action=generate_image&<?php echo isset($detail->employer_sign) ? $detail->employer_sign : 'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
                                                <input type="hidden" name="employer_sign" id="employer_sign" value="<?php echo isset($detail->employer_sign) ? $detail->employer_sign : ''; ?>" />
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            $employer_sign_date = $hs->employer_sign_date == '' ? date('d/m/Y') : $hs->employer_sign_date;
                                            echo Date::toShort($employer_sign_date);
                                            echo '<input type="hidden" name="employer_sign_date" value="' . $employer_sign_date . '" />';
                                            ?>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <span class="btn btn-block btn-primary btn-lg" onclick="submitOnboarding();">
                                    <i class="fa fa-save"></i> Click Here to Submit Information
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
        <img width="230px"  class="img-responsive" src="<?php echo $logo; ?>" />
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

    });

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

    function submitOnboarding()
    {
        var frmEmployerHsForm = document.forms['frmEmployerHsForm'];

	if($("#employer_sign_name").val().trim() == '')
        {
            alert("Please enter your name in signature box.");
            $("#employer_sign_name").focus();
            return;
        }

        var employer_sign = frmEmployerHsForm.elements["employer_sign"];
        if(employer_sign.value.trim() == '')
        {
            alert('Please provide your signature.');
            return;
        }

        frmEmployerHsForm.submit();
    }

</script>

</body>
</html>
