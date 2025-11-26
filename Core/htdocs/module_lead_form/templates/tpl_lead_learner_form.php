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

<br>

<div class="content-wrapper" >

    <section class="content-header text-center"><h1><strong>Learner Engagement Action Plan</strong></h1></section>

    <section class="content" style="background-color: #AAFFEE">
        <div class="container container-table">
            <div class="row">
                <div class="col-sm-12" style="background-color: white; font-size: large">
                    <p><br></p>

                    <div class="row">
                        <div class="col-sm-5"></div>
                        <div class="col-sm-2">
                            <img class="img img-responsive" src="<?php echo SystemConfig::getEntityValue($link, 'ob_header_image1'); ?>" alt="">
                        </div>
                        <div class="col-sm-5"></div>
                    </div>

                    <form name="frmReviewLearner" id="frmReviewLearner" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <input type="hidden" name="_action" value="save_lead_learner_employer_form" />
                        <input type="hidden" name="review_id" value="<?php echo $review->id; ?>" />
                        <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />
                        <input type="hidden" name="formName" value="frmReviewLearner" />
                        <input type="hidden" name="key" value="<?php echo $key; ?>" />

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="callout callout-default table-responsive">
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
                                            <th class="bg-teal-gradient">Start Date: </th><td><?php echo Date::toShort($tr->start_date); ?></td>
                                            <th class="bg-teal-gradient">End Date: </th><td><?php echo Date::toShort($tr->target_date); ?></td>
                                            <th class="bg-teal-gradient">FS Registrations: </th><td><span class="text-bold">English: </span><?php echo $fs_registrations['eng']; ?><br><span class="text-bold">Maths: </span><?php echo $fs_registrations['math']; ?></td>
                                        </tr>
                                    </table>
                                    <table class="table table-bordered table-condensed">
                                        <tr>
                                            <th class="bg-teal-active" width="20%;">Date of Activity: </th><td><?php echo Date::toShort($review->date_of_activity); ?></td>
                                            <th class="bg-teal-active" width="35%;">Total Learning Hours for session: </th><td><?php echo $review->total_learning_hours_for_this_session; ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive callout callout-default">
                                    <table class="table table-condensed">
                                        <tr>
                                            <th>
                                                <span class="text-bold lead">Record of work completed</span>
                                                <br>
                                                <?php echo str_replace(",", "<br> ", $review->record_of_work_completed); ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="text-bold lead">Exceptions to the above and additional information</span><br>
                                                <?php echo $review->expectations; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive callout callout-default">
                                    <table class="text-center table table-bordered table-condensed">
                                        <caption class="lead text-bold">
                                            Learning aims completed in this session
                                        </caption>
                                        <?php
                                        $student_qualifications_result = DAO::getResultset($link, "SELECT id, internaltitle, auto_id FROM student_qualifications WHERE tr_id = '{$tr->id}' AND aptitude = '0'", DAO::FETCH_ASSOC);
                                        echo '<tr class="bg-gray-active">';
                                        foreach($student_qualifications_result AS $student_qualification_row)
                                        {
                                            echo '<th class="small">' . $student_qualification_row['internaltitle'] . '</th>';
                                        }
                                        echo '</tr>';
                                        echo '<tr>';
                                        foreach($student_qualifications_result AS $student_qualification_row)
                                        {
                                            $learning_aims_completed_in_this_session = explode(",", $review->learning_aims_completed_in_this_session);
                                            echo in_array($student_qualification_row['auto_id'], $learning_aims_completed_in_this_session) ?
                                                '<td>Yes</td>' :
                                                '<td>No</td>';
                                        }
                                        echo '</tr>';
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive callout callout-default">
                                    <table class="text-center table table-bordered table-condensed">
                                        <caption class="lead text-bold">
                                            Cultural Development
                                        </caption>
                                        <tr class="bg-gray-active">
                                            <th>E & D</th>
                                            <th>Safeguarding</th>
                                            <th>Prevent</th>
                                            <th>British Values</th>
                                            <th>Hot Topic No.</th>
                                        </tr>
                                        <tr>
                                            <td><?php echo $review->end == 1 ? 'Yes' : 'No'; ?></td>
                                            <td><?php echo $review->safeguarding == 1 ? 'Yes' : 'No'; ?></td>
                                            <td><?php echo $review->prevent == 1 ? 'Yes' : 'No'; ?></td>
                                            <td><?php echo $review->british_values == 1 ? 'Yes' : 'No'; ?></td>
                                            <td><?php echo $review->hot_topic_no; ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive callout callout-default">
                                    <table class="table table-bordered table-condensed">
                                        <col width="60%;">
                                        <tr>
                                            <th class="bg-gray-active text-right">Has the learner progressed on Skills Forward since last session</th>
                                            <td>
                                                <?php echo $review->has_the_learner_progressed_to_sf == 1 ? 'Yes' : 'No'; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-8">
                                <div class="table-responsive callout callout-default">
                                    <table class="table table-bordered table-condensed">
                                        <tr>
                                            <th class="bg-gray-active">Learners reflection on learning to date</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <textarea name="learner_reflection_on_learning_to_date" id="learner_reflection_on_learning_to_date" style="width: 100%;" rows="10"><?php echo $review->learner_reflection_on_learning_to_date; ?></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <span class="text-bold text-info"><br><i class="fa fa-info-circle"></i> Guidance to complete the reflection box:</span>
                                <ol class="small">
                                    <li>From today's session explain what you have learned?</li>
                                    <li>Explain how you will use the new information from today at work and in a broader context i.e. outside of your role or in other areas of your life?</li>
                                    <li>What news skills will you be confident in using now?</li>
                                    <li>Describe 2 things what was interesting about this session and explain why.</li>
                                    <li>What can you do now that you couldn't do before?</li>
                                    <li>Has your confidence improved and if so in what areas and how will it help at work in your job role?</li>
                                </ol>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive callout callout-default">
                                    <table class="table table-bordered table-condensed">
                                        <caption class="lead text-bold">
                                            Overall Progress
                                        </caption>
                                        <tr class="bg-gray-active">
                                            <th>Knowledge</th>
                                            <th>Skills</th>
                                            <th>Behaviour</th>
                                            <th>OTJ Monthly Target</th>
                                            <th>OTJ to Date</th>
                                            <th>Total OTJ Req</th>
                                            <th>Risk Rating</th>
                                        </tr>
                                        <tr>
                                            <td><?php echo $review->Knowledge; ?></td>
                                            <td><?php echo $review->Skills; ?></td>
                                            <td><?php echo $review->Behaviours; ?></td>
                                            <td><?php echo $review->otj_monthly_target; ?></td>
                                            <td><?php echo $review->otj_to_date; ?></td>
                                            <td><?php echo $review->total_otj_req; ?></td>
                                            <td>
                                                <?php
                                                $risk_rating_list = ['R' => 'Red', 'A' => 'Amber', 'G' => 'Green'];
                                                echo isset($risk_rating_list[$review->risk_rating]) ? $risk_rating_list[$review->risk_rating] : $review->risk_rating;
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive callout callout-default">
                                    <table class="table table-bordered table-condensed">
                                        <caption class="lead text-bold">
                                            SMART Targets
                                        </caption>
                                        <tr>
                                            <th>1.</th><td>Complete development of maths and english on the Skills Forward Platform (<a class="text-green" href="https://www.myskillsforward.co.uk/institution/lead/" target="_blank">https://www.myskillsforward.co.uk/institution/lead/</a>)</td>
                                        </tr>
                                        <tr>
                                            <th>2.</th><td>Complete OTJ Diary and submit to coach <a class="text-green" href="mailto:<?php echo $coach->work_email; ?>"><?php echo $coach->work_email; ?></a></td>
                                        </tr>
                                        <tr>
                                            <th>3.</th><td><?php echo isset($review->t3) ? $review->t3 : ''; ?></td>
                                        </tr>
                                        <tr>
                                            <th>4.</th><td><?php echo isset($review->t4) ? $review->t4 : ''; ?></td>
                                        </tr>
                                        <tr>
                                            <th>5.</th><td><?php echo isset($review->t5) ? $review->t5 : ''; ?></td>
                                        </tr>
                                        <tr>
                                            <th>6.</th><td><?php echo isset($review->t6) ? $review->t6 : ''; ?></td>
                                        </tr>
                                        <tr>
                                            <th>7.</th><td><?php echo isset($review->t7) ? $review->t7 : ''; ?></td>
                                        </tr>
                                        <tr>
                                            <th>8.</th><td><?php echo isset($review->t8) ? $review->t8 : ''; ?></td>
                                        </tr>
                                    </table>
                                    <table class="table table-bordered table-condensed">
                                        <caption class="lead text-bold">
                                            Targets/actions that support your individual learning goals (Career and ambition goals)
                                        </caption>
                                        <tr>
                                            <th>1.</th><td><?php echo $review->goal1; ?></td>
                                        </tr>
                                        <tr>
                                            <th>2.</th><td><?php echo $review->goal2; ?></td>
                                        </tr>
                                        <tr>
                                            <th>3.</th><td><?php echo $review->goal3; ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive callout callout-default">
                                    <table class="table table-bordered">
                                        <caption class="lead text-bold">
                                            Confirm and Sign
                                        </caption>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="confirmation" id="confirmation" value="1" />
                                            </td>
                                            <td colspan="3">
                                                I can confirm that the information and dates detailed in this document are a true reflection of activities and discussions that took place.
                                            </td>
                                        </tr>
                                        <tr class="text-bold">
                                            <td>&nbsp;</td>
                                            <td id="tdLearnerName"><?php echo $tr->firstnames . ' ' . $tr->surname; ?></td>
                                            <td>
                                                <span class="btn btn-info" onclick="getSignature('learner');">
                                                    <img id="img_learner_sign" src="do.php?_action=generate_image&<?php echo $review->learner_sign != ''?$review->learner_sign:'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
                                                    <input type="hidden" name="learner_sign" id="learner_sign" value="<?php echo $review->learner_sign; ?>" />
                                                </span>
                                            </td>
                                            <td><?php echo $review->learner_sign_date != '' ? Date::toShort($review->learner_sign_date) : date('d/m/Y'); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="list-group-item">
                                    <span class="text-bold">Coach</span><br>
                                    <?php echo $review->coach_sign_name; ?><br>
                                    <img id="img_coach_sign" src="do.php?_action=generate_image&<?php echo $review->coach_sign; ?>" style="border: 2px solid;border-radius: 15px;"/><br>
                                    <?php echo Date::toShort($review->coach_sign_date); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <p></p>
                                <span class="btn btn-block btn-success btn-lg" onclick="save();">
                                    <i class="fa fa-save"></i> Click Here To Submit Information
                                </span>
                                <p></p>
                            </div>
                        </div>

                        <p><br></p>
                    </form>
                </div>
            </div>
        </div>
    </section>


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



<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/common.js" type="text/javascript"></script>


<script language="JavaScript">

    var phpLearnerSignature = '<?php echo $learner_sign_img; ?>';
    var phpCoachSignature = '<?php echo $a_sign_img; ?>';
    var phpManagerSignature = '<?php echo $review->emp_sign; ?>';


    $(function(){

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            yearRange: 'c-50:c+50'
        });

        $('.datepicker').attr('class');


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
        var frmReviewLearner = document.forms["frmReviewLearner"];

        if(frmReviewLearner.learner_reflection_on_learning_to_date.value == '')
        {
            alert("Please complete the box 'Learners reflection on learning to date'");
            frmReviewLearner.learner_reflection_on_learning_to_date.focus();
            return;
        }

        if(!frmReviewLearner.confirmation.checked)
        {
            alert("Please select the confirmation box.'");
            frmReviewLearner.confirmation.focus();
            return;
        }

        if(frmReviewLearner.learner_sign.value == '')
        {
            alert("Please sign the form before saving.");
            return;
        }

        frmReviewLearner.submit();
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

    // function getSignature()
    // {
    //     if(window.phpCoachSignature == '')
    //     {
    //         $('#signature_text').val('');
    //         $( "#panel_signature" ).data('panel', 'coach').dialog( "open");
    //         return;
    //     }
    //     $('#img_coach_sign').attr('src', 'do.php?_action=generate_image&'+window.phpCoachSignature);
    //     $('#coach_sign').val(window.phpCoachSignature);
    //     return;
    // }

    function getSignature(user)
    {
        loadDefaultSignatures();
        if(user == 'learner')
        {

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
</script>

</html>
