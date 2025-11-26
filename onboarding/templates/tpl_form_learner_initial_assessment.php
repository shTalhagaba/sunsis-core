<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Skills Scan</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link href="/assets/adminlte/plugins/JQuerySteps/jquery.steps.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.css">
    <link rel="stylesheet" href="/css/flipclock.css">
    <link rel="stylesheet" href="/css/onboarding.css">

    <style type="text/css">
        textarea,
        input[type=text] {
            border: 1px solid #3366FF;
            border-radius: 5px;
            border-left: 5px solid #3366FF;
        }

        input[type=checkbox] {
            transform: scale(1.4);
        }

        .loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 1000;
            background: url('images/progress-animations/loading51.gif') 50% 50% no-repeat rgba(255, 255, 255, .8);
        }

        .disabledRow {
            pointer-events: none;
            opacity: 0.7;
        }
    </style>
    <style>
        body {
            /* min-height: 2000px; */
            padding-top: 100px;
        }

        hr {
            box-sizing: content-box;
            height: 0;
            overflow: visible;
        }

        @media screen and (max-width: 768px) {

            .vertical .steps,
            .vertical .content {
                float: none;
                width: 100%;
            }
        }

        .navbar {
            min-height: 80px;
        }

        .navbar-brand {
            padding: 0 15px;
            height: 80px;
            line-height: 80px;
        }

        .navbar-toggle {
            /* (80px - button height 34px) / 2 = 23px */
            margin-top: 23px;
            padding: 9px 10px !important;
        }

        @media (min-width: 768px) {
            .navbar-nav > li > a {
                /* (80px - line-height of 27px) / 2 = 26.5px */
                padding-top: 26.5px;
                padding-bottom: 26.5px;
                line-height: 27px;
            }
        }
    </style>

    <script type="text/javascript">
        var trid = '<?php echo $tr->id; ?>';
        var asid = '<?php echo $assessment->id ; ?>';
        var subject = '<?php echo $subject; ?>';
        var phpHeaderLogo1 = '<?php echo $header_image1; ?>';
        var phpHeaderLogo2 = '<?php echo $header_image1; ?>';
        var phpScrolLogic = '<?php echo $scroll_logic; ?>';
        var phpLearnerSignature = '<?php echo $tr->getSign($link); ?>';
    </script>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/JQuerySteps/jquery.steps.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>
    <script src="/js/flipclock.js" type="text/javascript"></script>
    <script src="/js/common.js" type="text/javascript"></script>
    <script src="/js/initial_assessment.js?n=<?php echo time(); ?>"></script>
    <style>
        .panel.question table,
        .panel.question table {
            margin: 10px 0;
        }

        .panel.question table td,
        .panel.question table th {
            padding: 3px 10px;
        }

        .panel.question table td > img {
            max-height: 40px !important;
            margin: 0 10px;
        }

        .panel.question .question-content table td,
        .panel.question .question-content table th {
            border: 1px solid gray;
        }

        .panel.question ul, .panel.question ol {
            margin: 10px 0;
            padding-left: 20px;
        }

        .panel.question img {
            max-height: 100px;
        }

        .radio-inline {
            margin-left: 0 !important;
            margin-right: 10px;
            margin-bottom: 0;
            padding: 0;
        }

        .radio-inline .iradio_minimal-blue {
            margin-right: 10px;
        }

        .form-group.error .error {
            color: #8a1f11;
        }

        .form-group.error span.error {
            color: #8a1f11;
            display: inline-block;
            margin-left: 0;
            margin-top: 5px;
        }

        .wizard .content {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fff;
            border-radius: 4px;
        }

        .clock .flip-clock-label {
            display: none;
        }

        .flip-clock-wrapper ul li a div div.inn {
            color: #fff;
        }

        .stage-level {
            display: none;
        }

        .stage-level.active {
            display: block;
        }

        .report table.bordered td {
            padding: 10px;
            border: 1px solid #dadada;
        }

        .result-breakdown .score {
            border-top: 1px solid gray;
            padding: 10px;
        }

        .result-breakdown .score p {
            margin: 0;
        }

        .result-breakdown .score:not(:first-child) {
            border-left: 1px solid gray;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header page-scroll">
            <a class="navbar-brand img-responsive" href="#">
                <img class="" src="<?php echo $header_image1; ?>" alt="" width="260" height="80">
            </a>
        </div>
        <div class="text-right" style="margin-top: 1px;">
            <?php echo $ob_learner->firstnames . ' ' . strtoupper($ob_learner->surname); ?>
        </div>
    </div>
</nav>

<div id="main_container" style="min-height: 400px;">
    <?php if ($assessment && $assessment->status == 'completed'): ?>
        <section class="container">
            <div class="text-center" style="font-size: 16px;">
                <p>Assessment is already completed</p>
            </div>
        </section>
        <section class="container">
            <?php
            $progressData = InitialAssessmentHelper::getProgress($assessment->id);
            ?>
            <div class="report text-center">
                <table style="text-align: center; width: 100%" border="0">
                    <tr>
                        <td>
                            <!--<span class="fa fa-check-circle" style="font-size: 80px; color: #0e90d2"></span>-->
                            <p class="lead text-bold"><?php echo ucfirst($subject); ?> Initial Assessment</p>
                            <p class="prg-stage"><?php echo $progressData['progress']['stage']; ?></p>
                            <p class="prg-statement"><?php echo $progressData['progress']['statement']; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px">
                            <table class="bordered" style="width: 100%; border-collapse: collapse">
                                <tr>
                                    <td style="border: 1px solid #dadada; padding: 10px">
                                        <p><b>Started At</b></p>
                                        <div class="start-date"><?php echo date('d/m/Y', strtotime($assessment->start_at)) ?></div>
                                        <div class="start-time"><?php echo date('H:i:s', strtotime($assessment->start_at)) ?></div>
                                    </td>
                                    <td style="border: 1px solid #dadada; padding: 10px">
                                        <p><b>Ended At</b></p>
                                        <div class="end-date"><?php echo date('d/m/Y', strtotime($assessment->end_at)) ?></div>
                                        <div class="end-time"><?php echo date('H:i:s', strtotime($assessment->end_at)) ?></div>
                                    </td>
                                    <td style="border: 1px solid #dadada; padding: 10px">
                                        <p><b>Time Spent</b></p>
                                        <div class="time-diff"><?php echo InitialAssessmentHelper::timeDiff($assessment->start_at, $assessment->end_at) ?></div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px">
                            <h4>Result Breakdown</h4>
                            <table class="bordered" style="width: 100%">
                                <tr class="result-breakdown">
                                    <?php foreach ($progressData['scores'] as $row) { ?>
                                        <td>
                                            <p><?php echo $row['topic']; ?></p>
                                            <p><?php echo $row['given']; ?>/<?php echo $row['total']; ?>
                                                (<?php echo $row['percent']; ?>%)</p>
                                        </td>
                                    <?php } ?>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </section>
    <?php else: ?>
        <section class="container" id="landingPage">
            <div style="background:transparent !important" class="jumbotron text-center">
                <h2>
                    Welcome, <?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?>
                </h2>
                <div class="callout callout-default text-left">
                    <p>You are required to complete this Initial Assessment questionnaire. </p>
                    <p>Please click "Start Assessment" button and complete your questionnaire. </p>
                    <?php if ($provider_email = SystemConfig::getEntityValue($link, 'provider_email')): ?>
                        <p>If you have any questions or require further support, please contact us at
                            <a class="text-green" href="mailto:<?php echo $provider_email ?>">
                                <?php echo $provider_email; ?>
                            </a>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="text-center">
                <button id="btnStartOnboarding" onclick="start()" style=" padding-left: 50px; padding-right: 50px;"
                        class="btn btn-lg btn-primary text-uppercase">
                    <strong>Start Assessment</strong>&nbsp; <i class="fa fa-play"></i>
                </button>
            </div>
        </section>

        <section id="contentForm" style="display: none;">
            <div class="nts-secondary-teaser-gradient" style="margin-bottom: 20px">
                <div class="container">
                    <h3>Initial Assessment for <?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></h3>
                </div>
            </div>
            <form class="form" name="assessmentForm" id="assessmentForm"
                  action="<?php echo $_SERVER['PHP_SELF']; ?>"
                  method="post" autocomplete="off">
                <input type="hidden" name="_action" value="save_form_learner_initial_assessment"/>
                <input type="hidden" name="key" value="<?php echo $key; ?>"/>
                <input type="hidden" name="is_completed_by_learner" value=""/>

                <div>
                    <div style="margin: 0 auto; width: fit-content">
                        <div class="clock" style="margin:2em;"></div>
                    </div>
                </div>

                <?php
                foreach ($stages as $stage => $lavels):
                    ?>
                    <h3>Stage <?php echo $stage; ?></h3>
                    <fieldset id="step<?php echo $stage; ?>" data-stage="<?php echo $stage; ?>">

                        <?php foreach ($lavels as $level => $questions):
                            $level = trim($level);
                            ?>
                            <div class="stage-level <?php echo $stage == 1 ? 'active' : '' ?>"
                                 id="level_<?php echo $stage; ?>_<?php echo $level; ?>"
                                 data-level="<?php echo $level; ?>"
                            >
                                <h2 style="margin-bottom: 20px">
                                    Stage <?php echo trim($stage . ' - ' . $level, '- '); ?>
                                </h2>
                                <?php
                                foreach ($questions as $question) {
                                    $question_id = $question['id'];
                                    $description = $question['description'];
                                    $content = InitialAssessmentHelper::replaceImagePath($question['question'], '/images/questions/');
                                    $topic = $question['topic'];
                                    $options = isset($question['options']) && is_string($question['options']) ?
                                            $question['options'] : '';
                                    $options = $options ? json_decode($options, true) : [];
                                    ?>

                                    <div class="panel panel-default question"
                                         data-question="<?php echo $question_id; ?>"
                                         data-optional="<?php echo empty($options) ? 0 : 1; ?>"
                                         id="question_<?php echo $question_id; ?>">
                                        <div class="panel-body">
                                            <div style="margin-bottom: 20px; display: flex; justify-content: space-between;">
                                                <span style="margin-right: 20px"><b>Topic:</b> <?php echo $topic; ?></span>
                                                <!--<span style="margin-right: 20px"><b>Leve:</b> <?php /*echo $question['level']; */ ?></span>-->
                                                <span><b>Marks:</b> <?php echo $question['mark']; ?></span>
                                            </div>
                                            <?php if ($description): ?>
                                                <div class="question-desc" style="margin-bottom: 20px">
                                                    <?php echo $description; ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="question-content">
                                                <?php echo $content; ?>
                                            </div>
                                            <div class="form-group">
                                                <?php if (empty($options)): ?>
                                                    <input class="form-control" required
                                                           name="question[<?php echo $stage; ?>][<?php echo $question_id; ?>]">
                                                    <span for="question[<?php echo $stage; ?>][<?php echo $question_id; ?>]"
                                                          class="error"></span>
                                                <?php else: ?>
                                                    <div class="radio-group">
                                                        <?php foreach ($options as $key => $option): ?>
                                                            <label class="radio-inline">
                                                                <input type="radio"
                                                                       class="radioICheck"
                                                                       required
                                                                       name="question[<?php echo $stage; ?>][<?php echo $question_id; ?>]"
                                                                       value="<?php echo $key; ?>"> <?php echo $option; ?>
                                                            </label>
                                                        <?php endforeach; ?>
                                                        <label class="radio-inline">
                                                            <input type="radio"
                                                                   class="radioICheck"
                                                                   required
                                                                   name="question[<?php echo $stage; ?>][<?php echo $question_id; ?>]"
                                                                   value="Not Sure"> Not Sure
                                                        </label>
                                                    </div>
                                                    <span for="question[<?php echo $stage; ?>][<?php echo $question_id; ?>]"
                                                          class="error"></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php endforeach; ?>
                    </fieldset>
                <?php endforeach; ?>
            </form>
        </section>

        <section class="container" id="finished" style="display: none;">
            <div class="jumbotron text-center" style="padding: 20px; margin-bottom: 10px">
                <h2>Thank you for completing the Initial Assessment</h2>
                <p>Your responses have been recorded successfully.</p>
            </div>
            <div class="report text-center">
                <table style="text-align: center; width: 100%" border="0">
                    <tr>
                        <td>
                            <span class="fa fa-check-circle" style="font-size: 80px; color: #0e90d2"></span>
                            <p class="lead text-bold"><?php echo ucfirst($subject); ?> Initial Assessment</p>
                            <p class="prg-stage"></p>
                            <p class="prg-statement"></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px">
                            <table class="bordered" style="width: 100%; border-collapse: collapse">
                                <tr>
                                    <td style="border: 1px solid #dadada; padding: 10px">
                                        <p><b>Started At</b></p>
                                        <div class="start-date">--</div>
                                        <div class="start-time">--</div>
                                    </td>
                                    <td style="border: 1px solid #dadada; padding: 10px">
                                        <p><b>Ended At</b></p>
                                        <div class="end-date">--</div>
                                        <div class="end-time">--</div>
                                    </td>
                                    <td style="border: 1px solid #dadada; padding: 10px">
                                        <p><b>Time Spent</b></p>
                                        <div class="time-diff">--</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px">
                            <h4>Result Breakdown</h4>
                            <table class="bordered" style="width: 100%">
                                <tr class="result-breakdown"></tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </section>
    <?php endif; ?>
</div>
<?php include_once(__DIR__ . '/layout/footer1.php') ?>
</body>
</html>