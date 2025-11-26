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
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
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
                    <form name="frmReviewEmployer" id="frmReviewEmployer" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <input type="hidden" name="_action" value="save_lead_learner_employer_form" />
                        <input type="hidden" name="review_id" value="<?php echo $review->id; ?>" />
                        <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />
                        <input type="hidden" name="formName" value="frmReviewEmployer" />
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
                                                <?php echo $tr->l03; ?>
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
                                            <th class="bg-teal-active" width="25%;">Date of Activity: </th><td><?php echo Date::toShort($review->date_of_activity); ?></td>
                                            <th class="bg-teal-active" width="30%;">Total Learning Hours for session: </th><td><?php echo $review->total_learning_hours_for_this_session; ?></td>
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
                                                <span class="text-bold lead">Exceptions to the above and additional information</span>
                                                <br><?php echo $review->expectations; ?>
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
                            <div class="col-sm-12">
                                <div class="table-responsive callout callout-default">
                                    <table class="table table-bordered table-condensed">
                                        <tr>
                                            <th class="bg-gray-active">Learners reflection on learning to date</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php echo $review->learner_reflection_on_learning_to_date; ?>
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
                            <div class="col-sm-6">
                                <div class="list-group-item">
                                    <span class="text-bold">Coach</span><br>
                                    <?php echo $review->coach_sign_name; ?><br>
                                    <img id="img_coach_sign" src="do.php?_action=generate_image&<?php echo $review->coach_sign; ?>" style="border: 2px solid;border-radius: 15px;"/><br>
                                    <?php echo Date::toShort($review->coach_sign_date); ?>
                                </div>
                            </div>
                            <?php if($review->learner_sign != '') { ?>
                                <div class="col-sm-6">
                                    <div class="list-group-item">
                                        <span class="text-bold">Learner</span><br>
                                        <?php echo $tr->firstnames . ' ' . $tr->surname; ?><br>
                                        <img id="img_learner_sign" src="do.php?_action=generate_image&<?php echo $review->learner_sign; ?>" style="border: 2px solid;border-radius: 15px;"/><br>
                                        <?php echo Date::toShort($review->learner_sign_date); ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <p><br></p>
                    </form>
                </div>
            </div>
        </div>
    </section>


</div>



<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/common.js" type="text/javascript"></script>


<script language="JavaScript">

    var phpLearnerSignature = '<?php echo $review->learner_sign != '' ? $review->learner_sign : 'title=not yet signed&font=Signature_Regular.ttf&size=25';  ?>';
    var phpCoachSignature = '<?php echo $a_sign_img; ?>';

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

</script>

</html>
