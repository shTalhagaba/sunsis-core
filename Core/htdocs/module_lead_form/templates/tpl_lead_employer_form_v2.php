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
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css" />
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

    <div class="content-wrapper">

        <section class="content-header text-center">
            <h1><strong>Learner Engagement Action Plan</strong></h1>
        </section>

        <section class="content" style="background-color: #AAFFEE">
            <div class="container container-table">
                <div class="row">
                    <div class="col-sm-12" style="background-color: white; font-size: large">
                        <p><br></p>

                        
                        <form name="frmReviewEmployer" id="frmReviewEmployer" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <input type="hidden" name="_action" value="save_lead_learner_employer_form" />
                            <input type="hidden" name="review_id" value="<?php echo $review->id; ?>" />
                            <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />
                            <input type="hidden" name="formName" value="frmReviewEmployer" />
                            <input type="hidden" name="key" value="<?php echo $key; ?>" />

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
                                    <img class="img img-responsive" src="images/logos/lead_form_header.png" alt="LEAD Form Header">
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
                                                    <span class="text-bold lead"><?php echo $framework_title; ?></span>
                                                </td>
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
                                                <td style="width: 25%;"><?php echo Date::toShort($review->date_of_activity); ?></td>
                                                <th class="bg-teal-active" style="width: 25%;">Total Learning Hours for session:</th>
                                                <td style="width: 25%;"><?php echo $review->total_learning_hours_for_this_session; ?></td>
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
                                                <p><?php echo nl2br($review->expectations); ?></p>
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
                                                <p><?php echo nl2br($review->cultural_development); ?></p>
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
                                            <th>1.</th>
                                            <td><?php echo nl2br($review->goal1); ?></td>
                                        </tr>
                                        <tr>
                                            <th>2.</th>
                                            <td><?php echo nl2br($review->goal2); ?></td>
                                        </tr>
                                        <tr>
                                            <th>3.</th>
                                            <td><?php echo nl2br($review->goal3); ?></td>
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
                                                        <td><?php echo Date::toShort($review->date_of_next_meeting); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Time: </th>
                                                        <td><?php echo $review->time_of_next_meeting; ?></td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td>
                                                <?php echo $minutes_planned; ?>
                                            </td>
                                            <td>
                                                <?php echo $review->otj_remaining_minutes != '' ? $review->otj_remaining_minutes : $minutes_remaining; ?>
                                            </td>
                                            <td>
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th>Target</th>
                                                        <td><?php echo $review->target_otj_this_month; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Actual</th>
                                                        <td><?php echo $review->actual_otj_this_month; ?></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="list-group-item">
                                        <span class="text-bold">Coach</span><br>
                                        <?php echo $review->coach_sign_name; ?><br>
                                        <img id="img_coach_sign" src="do.php?_action=generate_image&<?php echo $review->coach_sign; ?>" style="border: 2px solid;border-radius: 15px;" /><br>
                                        <?php echo Date::toShort($review->coach_sign_date); ?>
                                    </div>
                                </div>
                                <?php if ($review->learner_sign != '') { ?>
                                    <div class="col-sm-6">
                                        <div class="list-group-item">
                                            <span class="text-bold">Learner</span><br>
                                            <?php echo $tr->firstnames . ' ' . $tr->surname; ?><br>
                                            <img id="img_learner_sign" src="do.php?_action=generate_image&<?php echo $review->learner_sign; ?>" style="border: 2px solid;border-radius: 15px;" /><br>
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

        $(function() {

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                yearRange: 'c-50:c+50'
            });

            $('.datepicker').attr('class');


        });

        function checkLength(e, t, l) {
            if (t.value.length >= l) {
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
                            t.value = t.value.substr(0, l - 1);
                        }
                    }
                }).css("background", "#FFF");
            }
        }
    </script>

</html>