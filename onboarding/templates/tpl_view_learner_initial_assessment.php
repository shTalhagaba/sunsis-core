<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Learner Initial Assessment</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <style>
        html,
        body {
            height: 100%;
            font-size: medium;
        }

        textarea,
        input[type=text] {
            border: 1px solid #3366FF;
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

        .question img {
            max-height: 100px;
        }

        .question table td > img {
            max-height: 40px !important;
            margin: 0 5px;
        }

        .result-breakdown .score {
            padding: 10px;
        }

        .result-breakdown .score p {
            margin: 0;
        }

        .result-breakdown .score {
            border-top: 1px solid gray;
        }

    </style>
</head>
<body>
<div class="row">
    <div class="col-sm-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Learner Initial Assessment</div>
            <div class="ButtonBar">
                <?php if (strpos($_SESSION['bc']->getPrevious(), 'view_learner_initial_assessment')): ?>
                    <span class="btn btn-xs btn-default"
                          onclick="window.location.href='do.php?_action=read_training&id=<?php echo $assessment->tr_id; ?>';">
                        <i class="fa fa-arrow-circle-o-left"></i> Close</span>
                <?php else: ?>
                    <span class="btn btn-xs btn-default"
                          onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';">
                        <i class="fa fa-arrow-circle-o-left"></i> Close</span>
                <?php endif; ?>
            </div>
            <div class="ActionIconBar">

            </div>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>


<br>

<div class="content-wrapper">

    <section class="content">
        <div class="container-fluid container-table">
            <div class="row vertical-center-row">
                <div class="col-sm-12" style="background: #ffffff;">
                    <p><br></p>
                    <?php
                    $progressData = InitialAssessmentHelper::getProgress($assessment->id);
                    ?>
                    <div class="text-right" style="margin-bottom: 15px">
                        <button type="button" class="btn btn-info" onclick="downloadPdf()"><i class="fa fa-file-pdf-o"></i> Download</button>
                    </div>
                    <div id="report" style="width: 100%; padding: 0; margin: 0">
                        <div class="panel panel-default" style="width: 100%">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6 text-center" style="padding: 20px">
                                        <span class="fa fa-check-circle" style="font-size: 80px; color: #0e90d2"></span>
                                        <p class="lead text-bold"><?php echo ucwords($assessment->subject) ?> Initial
                                            Assessment</p>
                                        <p><?php echo $progressData['progress']['stage'] ?></p>
                                        <?php if (isset($progressData['progress']['statement'])): ?>
                                            <p><?php echo $progressData['progress']['statement'] ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6 text-center">
                                        <div style="padding: 10px">
                                            <p><b>Student Name</b></p>
                                            <?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?>
                                        </div>
                                        <div class="row" style="margin: 0">
                                            <div class="col-md-4"
                                                 style="padding: 10px; border-top: 1px solid gray; border-right: 1px solid gray">
                                                <p><b>Started At</b></p>
                                                <?php echo date('d/m/Y', strtotime($assessment->start_at)) ?> <br>
                                                <?php echo date('H:i:s', strtotime($assessment->start_at)) ?>
                                            </div>
                                            <div class="col-md-4"
                                                 style="padding: 10px; border-top: 1px solid gray; border-right: 1px solid gray">
                                                <p><b>Ended At</b></p>
                                                <?php echo date('d/m/Y', strtotime($assessment->end_at)) ?> <br>
                                                <?php echo date('H:i:s', strtotime($assessment->end_at)) ?>
                                            </div>
                                            <div class="col-md-4" style="padding: 10px; border-top: 1px solid gray">
                                                <p><b>Time Spent</b></p>
                                                <?php echo InitialAssessmentHelper::timeDiff($assessment->start_at, $assessment->end_at) ?>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($progressData['scores'])): ?>
                            <div class="panel panel-default" style="max-width: 100%">
                                <div class="panel-body">
                                    <p style="font-weight: 500">Result Breakdown</p>
                                    <div class="result-breakdown row" style="margin: 0">
                                        <?php
                                        $index = 0;
                                        foreach ($progressData['scores'] as $row): ?>
                                            <div class="score col-md-4"
                                                 style="border-top: 1px solid gray; border-left: <?php echo $index ? '1px solid gray' : 'none'; ?>"
                                            >
                                                <p><?php echo $row['topic']; ?></p>
                                                <p><?php echo $row['given']; ?>/<?php echo $row['total']; ?>
                                                    (<?php echo $row['percent']; ?>%)</p>
                                            </div>
                                            <?php $index++; endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="panel panel-default ">
                        <div class="panel-heading" style="cursor: pointer"
                             data-toggle="collapse" href="#collapseExample" aria-expanded="false"
                             aria-controls="collapseExample">
                            Marks Detail <i class="fa fa-caret-down pull-right"></i>
                        </div>
                        <div class="collapse" id="collapseExample">
                            <div class="panel-body">
                                <table class="table table-bordered">
                                    <tr class="bg-gray">
                                        <th><p class="text-center">Topic</p></th>
                                        <th><p class="text-center">Level</p></th>
                                        <th><p class="text-center">Question</p></th>
                                        <th><p class="text-center">Answer</p></th>
                                        <th><p class="text-center">Mark</p></th>
                                        <th><p class="text-center">User Answer</p></th>
                                        <th>Marks Awarded</th>
                                    </tr>
                                    <?php $total_marks = 0; ?>
                                    <?php foreach (Helpers::array_group_by($questions, ['stage', 'stage_level']) as $stage => $levels): ?>
                                        <?php foreach ($levels as $stage_level => $rows):
                                            $row_marks = array_sum(array_map(function ($item) {
                                                return $item['correct'] ? $item['mark'] : 0;
                                            }, $rows));
                                            $total_marks += $row_marks;
                                            ?>
                                            <tr class="bg-gray-light">
                                                <td style="text-align: center;font-weight: bold;background-colorx: #d2d6de"
                                                    colspan="7">Stage <?php echo $stage; ?>
                                                    <?php if ($stage_level): ?>
                                                        - <?php echo $stage_level; ?>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php foreach ($rows as $row) { ?>
                                            <?php $options = $row['options'] ? json_decode($row['options'], true) : []; ?>
                                            <tr>
                                                <td>
                                                    <?php echo $row['topic']; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $row['level']; ?>
                                                </td>
                                                <td class="question">
                                                    <?php if ($row['description']): ?>
                                                        <div style="margin-bottom: 15px"><?php echo $row['description']; ?></div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <?php echo InitialAssessmentHelper::replaceImagePath($row['question'], '/images/questions/') ?>
                                                    </div>
                                                    <?php if (!empty($options)): ?>
                                                        <div style="margin-top: 15px">
                                                            <?php foreach ($options as $key => $option): ?>
                                                                <b><?php echo $key; ?>) </b>
                                                                <?php echo $option; ?><br>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['answer']; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $row['mark']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['givin_answer']; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $row['correct'] ? $row['mark'] : 0; ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                            <tr class="">
                                                <td colspan="6" class="text-right">
                                                    Total marks stage <?php echo $stage; ?>
                                                    <?php if ($stage_level): ?>
                                                        - <?php echo $stage_level; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center"><?php echo $row_marks; ?> </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                    <tr class="bg-gray text-bold">
                                        <td colspan="6" class="text-right">Total Marks</td>
                                        <td class="text-center"><?php echo $total_marks; ?> </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($previousAssessments)) { ?>
                        <hr style="margin: 40px -15px; border: none; border-top: 2px dashed gray;"/>
                        <div class="panel panel-default ">
                            <div class="panel-heading" style="cursor: pointer"
                                 data-toggle="collapse" href="#collapseAttempts" aria-expanded="false"
                                 aria-controls="collapseAttempts">
                                Re-Attempts (<?php echo count($previousAssessments); ?>) <i class="fa fa-caret-down pull-right"></i>
                            </div>
                            <div class="collapse" id="collapseAttempts">
                                <div class="panel-body" style="padding: 0">
                                    <table class="table table-condensed table-bordered">
                                        <tr class="bg-gray">
                                            <th class="text-center">Created</th>
                                            <th class="text-center">Started</th>
                                            <th class="text-center">Completed</th>
                                            <th class="text-center">Stage</th>
                                            <th class="text-center">Status</th>
                                            <th></th>
                                        </tr>
                                        <?php foreach ($previousAssessments as $row):
                                            $progress = InitialAssessmentHelper::getProgress($row['id']);
                                            ?>
                                            <tr>
                                                <td class="text-center">
                                                    <?php
                                                    if ($row['created_at']) {
                                                        echo date('d/m/Y H:i:s', strtotime($row['created_at']));
                                                    } else {
                                                        echo '--';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    if ($row['start_at']) {
                                                        echo date('d/m/Y H:i:s', strtotime($row['start_at']));
                                                    } else {
                                                        echo '--';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    if ($row['end_at']) {
                                                        echo date('d/m/Y H:i:s', strtotime($row['end_at']));
                                                    } else {
                                                        echo '--';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php echo isset($progress['progress']['stage']) ? $progress['progress']['stage'] : '--' ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $row['status'] == 'started' ? 'In-Progress' : ucfirst($row['status']) ?>
                                                </td>
                                                <td>
                                                    <?php if ($row['status'] !== 'pending'): ?>
                                                        <a href="do.php?_action=view_learner_initial_assessment&id=<?php echo $row['id']; ?>"> View Report</a>
                                                    <?php endif; ?>
                                                    <?php if ($row['status'] !== 'completed'): ?>
                                                        <!--<a class="btn btn-link btn-sm" href="<?php /*InitialAssessmentHelper::generateReTakeUrl($link, $row['tr_id'], $row['subject'], (object)$row) */ ?>"> Re-Attempt</a>-->
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>

                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</div>

<footer class="main-footer">
    <div class="pull-left">
        <table class="table" style="border-collapse: separate; border-spacing: 0.5em;">
            <tr>
                <td><img width="230px" src="images/logos/<?php echo SystemConfig::getEntityValue($link, 'logo'); ?>"/>
                </td>
            </tr>
        </table>
    </div>
    <div class="pull-right">
        <img src="images/logos/SUNlogo.png"/>
    </div>
</footer>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript">
    var phpProviderSignature = '<?php echo $_SESSION['user']->signature; ?>';

    function downloadPdf() {
        var element = document.getElementById('report');
        var opt = {
            margin: 0.25,
            filename: 'report.pdf',
            image: {type: 'jpeg', quality: 1},
            html2canvas: {
                x: 110,
                y: 0,
                scrollX: 0,
                scrollY: 0,
                scale: 4,
                windowWidth: 1024,
                windowHeight: 1600,
                width: 800,
                height: 1000,
            },
            jsPDF: {
                unit: 'in',
                format: 'letter',
                orientation: 'portrait'
            }
        };

        html2pdf().from(element).set(opt).save();
    }
</script>
</body>
</html>