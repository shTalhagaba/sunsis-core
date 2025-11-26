<?php /* @var $tr TrainingRecord */ ?>
<?php /* @var $qualification StudentQualification */ ?>
<?php /* @var $employer Organisation */ ?>
<?php /* @var $employer_location Location */ ?>
<?php /* @var $framework Framework */ ?>
<?php /* @var $review LeapReviewForm */ ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Learner Engagement Action Plan</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="/assets/adminlte/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/jquery.timepicker.css" />

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        html,
        body {
            height: 100%
        }
        textarea {
            border:1px solid #3366FF;
            border-radius: 5px;
            border-left: 5px solid #3366FF;
        }
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
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
        #btnGoTop {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 30px;
            z-index: 99;
            font-size: 18px;
            border: none;
            outline: none;
            color: white;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
        }
        .fieldValue{
            box-shadow: 2px 2px 5px gray;
        }
        input[type=checkbox] {
            transform: scale(1.4);
        }
    </style>

</head>

<body>

<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Learner Engagement Action Plan</div>
            <div class="ButtonBar">
                <?php if(isset($_SESSION['user'])) { ?>
                    <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
                    <?php if(!$disable_save) {?> <span class="btn btn-sm btn-success" onclick="save();"><i class="fa fa-save"></i> Save Form</span> <?php } ?>
                <?php } ?>
                <?php
                ?>
            </div>
            <div class="ActionIconBar">
                <?php if(true){ ?>
                    <span class="btn btn-sm btn-default" onclick="window.location.href='do.php?_action=lead_form&subaction=export_pdf&review_id=<?php echo $review->id; ?>&tr_id=<?php echo $tr->id; ?>';"><i class="fa fa-file-pdf-o"></i> </span>
                <?php } ?>
            </div>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <?php
        if(isset($_SESSION['bc']))
            $_SESSION['bc']->render($link);
        ?>
    </div>
</div>

<br>

<div class="container-fluid" style="font-size: medium">
    <form name="frmReview" id="frmReview" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="_action" value="save_lead_form" />
        <input type="hidden" name="review_id" value="<?php echo $review->id; ?>" />
        <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />
        <input type="hidden" name="coach_id" value="<?php echo $coach_id; ?>" />

        <div class="row">
            <div class="col-sm-3">
                <h4 class="text-bold">
                    Lean Education and Development Limited
                </h4>
                Unit 3 Hillcrest Business Parkbr<br>
                Cinder Bank,<br>
                Dudley<br>
                DY2 9AP
            </div>
            <div class="col-sm-9">
                <img class="img img-responsive" src="images/logos/lead_form_header.PNG" alt="LEAD Form Header" >
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <tr class="bg-teal-gradient">
                            <th>Learner</th>
                            <th>Company</th>
                            <th width="25%">Qualification & Level</th>
                            <th>Coach</th>
                        </tr>
                        <tr>
                            <td>
                                <span class="text-bold lead"><?php echo $tr->firstnames . ' ' . $tr->surname; ?></span> <br>
                                <?php echo $tr->l03; ?><br>
                                <?php echo $tr->work_email; ?>
                            </td>
                            <td>
                                <span class="text-bold lead"><?php echo $employer->legal_name; ?></span> <br>
                                <span class="small">
                                    <?php
                                    echo $employer_location->address_line_1 != '' ? $employer_location->address_line_1 . '<br>' : '';
                                    echo $employer_location->address_line_2 != '' ? $employer_location->address_line_2 . '<br>' : '';
                                    echo $employer_location->address_line_3 != '' ? $employer_location->address_line_3 . '<br>' : '';
                                    echo $employer_location->address_line_4 != '' ? $employer_location->address_line_4 . '<br>' : '';
                                    echo $employer_location->postcode != '' ? $employer_location->postcode . '<br>' : '';
                                    echo $employer_location->telephone != '' ? $employer_location->telephone . '<br>' : '';
                                    ?>
                                </span>
                            </td>
                            <td>
                                <span class="text-bold lead"><?php echo $framework_title; ?></span></td>
                            <td>
                                <span class="text-bold lead"><?php echo $coach->firstnames . ' ' . $coach->surname; ?></span><br>
                                <?php echo $coach->work_email; ?>
                            </td>
                        </tr>
                    </table>
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <th class="bg-teal-gradient" style="width: 25%;">Start Date: </th>
                            <td style="width: 25%;"><?php echo Date::toShort($tr->start_date); ?></td>
                            <th class="bg-teal-gradient" style="width: 25%;">Projected End Date: </th>
                            <td style="width: 25%;"><?php echo Date::toShort($tr->target_date); ?></td>

                        </tr>
                    </table>
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <th class="bg-teal-active" style="width: 25%;">Date of Activity:</th>
                            <td style="width: 25%;"><?php echo HTML::datebox('date_of_activity', $review->date_of_activity, true); ?></td>
                            <th class="bg-teal-active" style="width: 25%;">Total Learning Hours for session:</th>
                            <td style="width: 25%;">
                                <input type="text" size="4" maxlength="4" name="total_learning_hours_for_this_session" id="total_learning_hours_for_this_session"
                                       value="<?php echo $review->total_learning_hours_for_this_session; ?>" onkeypress="return numbersonlywithpoint();">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <strong>Record of today's activity and learning that has taken place</strong>
                            <br>
                            <?php echo str_replace(",", ", ", $review->record_of_work_completed); ?>
                            <br><br>
                            <span class="text-bold" style="color: #777;">Exceptions to the above and additional information</span>
                            <textarea name="expectations" id="expectations" style="width: 100%;" rows="5"><?php echo $review->expectations; ?></textarea>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <strong>Cultural development:</strong><br>(Safeguarding, British values, Prevent and E&D occurring today or since our last meeting)
                            <br><br>
                            <textarea name="cultural_development" id="cultural_development" style="width: 100%;" rows="5"><?php echo $review->cultural_development; ?></textarea>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered table-condensed">
                    <tr>
                        <th colspan="2">
                            Targets incl. FS to be completed for the next meeting:
                        </th>
                    </tr>
                    <tr>
                        <th>1.</th><td><textarea name="goal1" id="goal1" style="width: 100%" rows="2"><?php echo $review->goal1; ?></textarea></td>
                    </tr>
                    <tr>
                        <th>2.</th><td><textarea name="goal2" id="goal2" style="width: 100%" rows="2"><?php echo $review->goal2; ?></textarea></td>
                    </tr>
                    <tr>
                        <th>3.</th><td><textarea name="goal3" id="goal3" style="width: 100%" rows="2"><?php echo $review->goal3; ?></textarea></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered">
                    <tr>
                        <th>Date and time of next meeting</th>
                        <th>20% OTJ total minimum hours required</th>
                        <th>Total hours remaining</th>
                        <th>Target against actual hours at this stage/month</th>
                    </tr>
                    <tr>
                        <td>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Date: </th>
                                    <td><?php echo HTML::datebox('date_of_next_meeting', $review->date_of_next_meeting); ?></td>
                                </tr>
                                <tr>
                                    <th>Time: </th>
                                    <td><?php echo HTML::timebox('time_of_next_meeting', $review->time_of_next_meeting); ?></td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <?php echo $this->convertToHoursMins($minutes_planned, '%02d hours %02d minutes'); ?>
                        </td>
                        <td>
                            <?php
                            if($review->otj_remaining_minutes != '')
                            {
                                echo $this->convertToHoursMins($review->otj_remaining_minutes, '%02d hours %02d minutes');
                            } 
                            else
                            {
                                
                                echo $this->convertToHoursMins($minutes_remaining, '%02d hours %02d minutes');
                            }
                            ?>
                            <input type="hidden" name="otj_remaining_minutes" value="<?php echo $review->otj_remaining_minutes != '' ? $review->otj_remaining_minutes : $minutes_remaining; ?>" />
                        </td>
                        <td>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Target</th>
                                    <td><input type="text" name="target_otj_this_month" value="<?php echo $review->target_otj_this_month; ?>" onkeypress="return numbersonly();" maxlength="4" /></td>
                                </tr>
                                <tr>
                                    <th>Actual</th>
                                    <td><input type="text" name="actual_otj_this_month" value="<?php echo $review->actual_otj_this_month; ?>" onkeypress="return numbersonly();" maxlength="4" /></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive callout callout-default">
                    <table class="table table-bordered table-condensed">
                        <caption class="lead text-bold">
                            Signatures
                        </caption>
                        <col style="width: 30%;" />
                        <col style="width: 30%;" />
                        <col style="width: 30%;" />
                        <tr class="bg-gray-active">
                            <th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th>
                        </tr>
                        <tr>
                            <td>Coach</td>
                            <td id="tdCoachName">
                                <?php echo $review->coach_sign != '' ? $review->coach_sign_name : $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?>
                            </td>
                            <td>
                                <span class="btn btn-info" onclick="getSignature('coach');">
                                    <img id="img_coach_sign" src="do.php?_action=generate_image&<?php echo $review->coach_sign != '' ? $review->coach_sign : 'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;"/>
								    <input type="hidden" name="coach_sign" id="coach_sign" value="<?php echo $review->coach_sign; ?>"/>
                                </span>
                            </td>
                            <td id="tdCoachSigndate">
                                <?php //echo $review->coach_sign_date != '' ? Date::toShort($review->coach_sign_date) : date('d/m/Y'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Learner</td>
                            <td id="tdLearnerName"><?php echo $tr->firstnames . ' ' . $tr->surname; ?></td>
                            <td>
                                <span class="btn btn-info" onclick="getSignature('learner');">
                                    <img id="img_learner_sign" src="do.php?_action=generate_image&<?php echo $review->learner_sign != ''?$review->learner_sign:'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
                                    <input type="hidden" name="learner_sign" id="learner_sign" value="<?php echo $review->learner_sign; ?>" />
                                </span>
                            </td>
                            <td id="tdLearnerSigndate">
                                <?php //echo $review->learner_sign_date != '' ? Date::toShort($review->learner_sign_date) : date('d/m/Y'); ?>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>

    </form>


</div>

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

<?php if(!$disable_save) {?>
    <button class="btn btn-md btn-primary" type="button" onclick="save()" id="btnGoTop" title="Save the form"> <i class="fa fa-save"></i> Save &nbsp;</button>
<?php } else { ?>
    <button class="btn btn-md btn-primary" type="button" disabled id="btnGoTop" title="Save disabled, form is signed by all coach, learner and employer."> <i class="fa fa-save"></i> Save &nbsp;</button>
<?php } ?>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker.js"></script>
<script src="/common.js" type="text/javascript"></script>


<script language="JavaScript">

    var phpLearnerSignature = '<?php echo $learner_sign_img; ?>';
    var phpCoachSignature = '<?php echo $a_sign_img; ?>';
    var phpManagerSignature = '<?php echo $review->emp_sign; ?>';


    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("btnGoTop").style.display = "block";
        } else {
            document.getElementById("btnGoTop").style.display = "none";
        }
    }

    $(function(){

        $(".timebox").timepicker({ timeFormat: 'H:i' });

        $('.timebox').bind('timeFormatError timeRangeError', function() {
            this.value = '';
            alert("Please choose a valid time");
            this.focus();
        });

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            yearRange: 'c-50:c+50'
        });

        $('.datepicker').attr('class');

        $('#tdCoachSigndate').html('<?php echo Date::toShort($review->date_of_activity); ?>');
        $('#tdLearnerSigndate').html('<?php echo Date::toShort($review->date_of_activity); ?>');

        $('#input_date_of_activity').on('change', function(){
            $('#tdCoachSigndate').html(this.value);
            $('#tdLearnerSigndate').html(this.value);
        });
    });

    function checkLength(e, t, l)
    {
        if(t.value.length>=l)
        {
            $("<div class='small'></div>").html('You have reached to the maximum length of this field').dialog({
                title: " Maximum number of characters ",
                resizable: false,
                modal: true,
                width: 500,
                maxWidth: 500,
                height: 'auto',
                maxHeight: 500,
                closeOnEscape: false,
                buttons: {
                    'OK': function() {
                        $(this).dialog('close');
                        t.value = t.value.substr(0,l-1);
                    }
                }
            }).css("background", "#FFF");
        }
    }

    function save()
    {
        var frmReview = document.forms["frmReview"];

        frmReview.submit();
    }



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
                    var sign_field = '';
                    if(panel == 'learner')
                    {
                        sign_field = 'learner_sign';
                    }
                    if(panel == 'coach')
                    {
                        sign_field = 'coach_sign';
                    }
                    if(panel == 'manager')
                    {
                        sign_field = 'emp_sign';
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
        loadDefaultSignatures();
        if(user == 'learner')
        {
            <?php if($_SESSION['user']->type != User::TYPE_LEARNER) { ?>
            // alert('You cannot sign on learner\'s behalf.');
            // return;
            <?php } ?>

            if(window.phpLearnerSignature == '')
            {
                $('#signature_text').val('');
                $('#signature_text').val($('#tdLearnerName').html().trim());
                $( "#panel_signature").data('panel', 'learner').dialog( "open");
                return;
            }
            $('#img_learner_sign').attr('src', 'do.php?_action=generate_image&'+window.phpLearnerSignature);
            $('#learner_sign').val(window.phpLearnerSignature);
            return;
        }
        if(user == 'coach')
        {
            if(window.phpCoachSignature == '')
            {
                $('#signature_text').val('');
                $('#signature_text').val($('#tdCoachName').html().trim());
                $( "#panel_signature" ).data('panel', 'coach').dialog( "open");
                return;
            }
            $('#img_coach_sign').attr('src', 'do.php?_action=generate_image&'+window.phpCoachSignature);
            $('#coach_sign').val(window.phpCoachSignature);
            return;
        }
        if(user == 'manager')
        {
            if(window.phpManagerSignature == '')
            {
                $('#signature_text').val('');
                $('#signature_text').val($('#emp_sign_name').val().trim());
                $( "#panel_signature" ).data('panel', 'manager').dialog( "open");
                return;
            }
            $('#img_emp_sign').attr('src', 'do.php?_action=generate_image&'+window.phpManagerSignature);
            $('#emp_sign').val(window.phpManagerSignature);
            return;
        }
    }

    function numbersonlywithpoint(myfield, e, dec)
    {
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
        if ((key==null) || (key==0) || (key==8) ||
            (key==9) || (key==13) || (key==27) )
            return true;

        // numbers
        else if ((("0123456789.").indexOf(keychar) > -1))
            return true;

        // decimal point jump
        else if (dec && (keychar == "."))
        {
            myfield.form.elements[dec].focus();
            myfield.form.elements[dec].select();
            return false;
        }
        else
            return false;
    }


    $(function(){
        $('input#total_learning_hours_for_this_session, input#eng_comp_percentage, input#math_comp_percentage' +
            ', input#otj_monthly_target, input#otj_to_date, input#total_otj_req ').blur(function(){
            if($(this).val() != '')
            {
                var num = parseFloat($(this).val());
                var cleanNum = num.toFixed(1);
                $(this).val(cleanNum);
            }
        });

    });
</script>

</html>
