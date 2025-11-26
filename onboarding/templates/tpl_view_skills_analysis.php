<?php /* @var $sa SkillsAnalysis */ ?>
<?php /* @var $tr TrainingRecord */ ?>
<?php /* @var $ob_learner OnboardingLeanrer */ ?>

<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Skills Analysis</title>
    <link rel="stylesheet" href="/css/common.css" type="text/css" />
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

    <!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

    <style>
    #btn-scroll-up {
        display: none;
        position: fixed;
        bottom: 20px;
        right: 30px;
        z-index: 99;
        cursor: pointer;
    }
    </style>
</head>

<body>
    <div class="row">
        <div class="col-lg-12">
            <div class="banner">
                <div class="Title" style="margin-left: 6px;">View Skills Analysis</div>
                <div class="ButtonBar">
                    <span class="btn btn-xs btn-default"
                        onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i
                            class="fa fa-arrow-circle-o-left"></i> Close</span>
                    <?php if ($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [1])) { ?>
                    <span class="btn btn-xs btn-default"
                        onclick="window.location.replace('do.php?_action=edit_skills_analysis&id=<?php echo $sa->id; ?>');"><i
                            class="fa fa-edit"></i> Edit Skills Analysis</span>
                    <!-- <span class="btn btn-xs btn-default" onclick="downloadSkillsAssessment('<?php //echo $sa->id; 
                                                                                                        ?>');"><i class="fa fa-file-pdf-o"></i> Download Skills Analysis</span> -->
                    <?php } ?>
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

    <div class="row">

    </div>

    <div class="row">
        <div class="col-sm-10">
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive" id="sectionTrainingDetails">
                        <table class="table table-bordered">
                            <tr>
                                <td>
                                    <span class="text-bold">Learner Details:</span><br>
                                    <span class="text-info">First Name(s):
                                    </span><?php echo $ob_learner->firstnames; ?><br>
                                    <span class="text-info">Surname: </span><?php echo $ob_learner->surname; ?><br>
                                    <span class="text-info">Personal Email:
                                    </span><?php echo $ob_learner->home_email; ?><br>
                                    <span class="text-info">Employer:
                                    </span><?php echo DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$tr->employer_id}'"); ?><br>
                                </td>
                                <td>
                                    <span class="text-bold">Apprenticeship Details:</span><br>
                                    <span class="text-info">Standard/ Programme:
                                    </span><?php echo $framework->title; ?><br>
                                    <span class="text-info">Apprenticeship Title:
                                    </span><?php echo $framework->getStandardCodeDesc($link); ?><br>
                                    <span class="text-info">Level:
                                    </span><?php echo DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}'"); ?><br>
                                    <span class="text-info">Funding Band Max.:
                                    </span>&pound;<?php echo $framework->getFundingBandMax($link); ?><br>
                                    <span class="text-info">Recommended Duration:
                                    </span><?php echo $framework->getRecommendedDuration($link); ?> months<br>
                                </td>
                                <td>
                                    <span class="text-bold">Training Details:</span><br>
                                    <?php if ($tr->contracted_hours_per_week >= 30) { ?>
                                    <span class="text-success"><i class="fa fa-info-circle"></i> Full time
                                        learner</span><br>
                                    <span class="text-info">Practical Period Dates:
                                    </span><?php echo Date::toShort($tr->practical_period_start_date) . ' - ' . Date::toShort($tr->practical_period_end_date); ?><br>
                                    <span class="text-info">Practical Period Duration:
                                    </span><?php echo $tr->duration_practical_period; ?> months<br>
                                    <span class="text-info">Contracted Hours per Week:
                                    </span><?php echo $tr->contracted_hours_per_week; ?> hours<br>
                                    <span class="text-info">Weeks to be worked per Year:
                                    </span><?php echo $tr->weeks_to_be_worked_per_year; ?> weeks<br>
                                    <?php } else { ?>
                                    <span class="text-info"><i class="fa fa-info-circle"></i> Part time
                                        learner</span><br>
                                    <span class="text-info">Contracted Hours per Week:
                                    </span><?php echo $tr->contracted_hours_per_week; ?> hours<br>
                                    <span class="text-info">Weeks to be worked per Year:
                                    </span><?php echo $tr->weeks_to_be_worked_per_year; ?> weeks<br>
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-sm-12">
                    <p class="text-center">
                        <?php
                        if ($sa->is_finished == 'Y') {
                            echo $sa->provider_sign != '' ? '<label for="" class="label label-success">Completed and Signed by Provider</label>' : '<label for="" class="label label-warning">Awaiting Provider Signature</label>';
                        } else {
                            echo $sa->learner_sign != '' ? '<label for="" class="label label-info">Completed and Signed by Learner</label>' : '<label for="" class="label label-warning">Awaiting Learner</label>';
                        }
                        ?>
                    </p>
                </div>

                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="sectionScoresTable">
                            <?php if (is_array($sa->ksb)) {
                                echo "<thead>Total Questions: " . count($sa->ksb) . "</thead>";
                            } ?>
                            <tr class="bg-success">
                                <th>#</th>
                                <th>Unit</th>
                                <th>Evidence</th>
                                <th>Learner Comments</th>
                                <!-- <th>Del. Hours (before)</th> -->
                                <th>Learner Score</th>
                                <!-- <th>Reduction applied</th> -->
                                <!-- <th title="Delivery Hours">Del. Hours (after)</th> -->
                            </tr>
                            <?php
                            $dh_total = 0;
                            $dh_scored = 0;
                            $question_counter = 0;
                            // $scores_list = SkillsAnalysis::getScoreAndPercentageList();
                            $scores_list = $sa->getRplPercentages();
                            if (!empty($sa->ksb) && is_array($sa->ksb)) {
                                foreach ($sa->ksb as $entry) {
                                    $row_score = 0;
                                    $scores_list_key = "score_{$entry['score']}";
                                    echo '<tr>';
                                    echo '<td>' . ++$question_counter . '/' . count($sa->ksb) . '</td>';
                                    echo '<td>' . $entry['unit_title'] . '</td>';
                                    echo '<td>' . html_entity_decode($entry['evidence_title']) . '</td>';
                                    echo '<td class="small">' . $entry['comments'] . '</td>';
                                    // echo '<td>' . $entry['del_hours'] . '</td>';
                                    echo '<td>' . $entry['score'] . '</td>';
                                    $p = !isset($scores_list[$scores_list_key]) ? 0 : round(100 - ($scores_list[$scores_list_key] * 100), 3);

                                    //echo $entry['score'] != 1 ? '<td>' . $p . '%</td>' : '<td>0%</td>';
                                    if (intval($entry['score']) > 0) {
                                        $row_score = round(floatval($entry['del_hours']) * $scores_list[$scores_list_key], 2);
                                    } else {
                                        $row_score = 0;
                                    }
                                    //echo '<td>' . $row_score . '</td>';

                                    echo '</tr>';
                                    $dh_total += floatval($entry['del_hours']);
                                    $dh_scored += $row_score;
                                }
                            }
                            $dh_scored = ceil($dh_scored);
                            ?>
                        </table>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="box box-info box-solid" id="sectionSkillsAnalysisResult">
                        <div class="box-header">
                            <span class="box-title">Skills Analysis Result</span>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" style="font-size: medium;">
                                    <tr>
                                        <td>
                                            <!-- <span class="text-info">Delivery hours total: </span><?php echo $dh_total; ?><br> -->
                                            <!-- <span class="text-info">Delivery hours following assessment: </span><?php echo $dh_scored; ?><br> -->
                                            <span class="text-info">Percentage of assessment:
                                            </span><?php echo $sa->percentage_fa; ?>%<br>
                                            <span class="text-info">Price reduction percentage following assessment:
                                            </span><?php echo $sa->price_reduction_percentage; ?>%<br>
                                            <span class="text-info">Duration following assessment:
                                            </span><?php echo $sa->duration_fa; ?> months<br>
                                            <!-- <span class="text-info">OTJ hours: </span><?php //echo $tr->contracted_hours_per_week >= 30 ? $sa->off_the_job_hours_based_on_duration : $sa->part_time_otj_hours; ?> hours<br> -->
                                            <span class="text-info">Rationale/Comments:
                                            </span><?php echo nl2br($sa->rationale_by_provider ?? ''); ?><br>
                                            <?php 
                                            if($sa->employer_comments != '') 
                                            {
                                                echo '<span class="text-info">Employer Comments: </span>' . nl2br($sa->employer_comments ?? '') . '<br>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <!-- <caption class="text-bold">TNP 1</caption> -->
                                                    <tr>
                                                        <th>Price</th>
                                                        <th>Before Assessment</th>
                                                        <th>Following Assessment</th>
                                                    </tr>
                                                    <?php
                                                    $tnp1 = json_decode($sa->tnp1);
                                                    $tnp1_fa = json_decode($sa->tnp1_fa);
                                                    $tnp1_ba_total = 0;
                                                    $tnp1_fa_total = 0;
                                                    if (is_array($tnp1_fa)) {
                                                        foreach ($tnp1 as $price_item) {
                                                            echo '<tr>';
                                                            echo '<td>' . $price_item->description . '</td>';
                                                            echo '<td>' . $price_item->cost . '</td>';
                                                            $tnp1_ba_total += floatval($price_item->cost);
                                                            foreach ($tnp1_fa as $price_item_fa) {
                                                                if ($price_item_fa->description === $price_item->description) {
                                                                    echo '<td>' . $price_item_fa->cost . '</td>';
                                                                    $tnp1_fa_total += floatval($price_item_fa->cost);
                                                                }
                                                            }
                                                            echo '</tr>';
                                                        }
                                                        // if($tr->practical_period_start_date >= '2025-07-31') {
                                                        //     echo '<tr class="bg-info trPrice">';
                                                        //     echo '<td>Assessment Price Element</td>';
                                                        //     echo '<td>' . $sa->epa_price . '</td>';
                                                        //     echo '<td>' . $sa->epa_price_fa . '</td>';
                                                        //     echo '</tr>';
                                                        //     $tnp1_ba_total += floatval($sa->epa_price);
                                                        //     $tnp1_fa_total += floatval($sa->epa_price_fa);
                                                        // }
                                                        $tnp1_fa_total = ceil($tnp1_fa_total);
                                                        echo '<tr><th align="right">Total</th><td>' . ceil($tnp1_ba_total) . '</td><td>' . $tnp1_fa_total . '</td></tr>';
                                                    }
                                                    ?>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-sm-12">
                    <?php
                    $sig_panel_class = "info";
                    if ($sa->learner_sign != "" && $sa->provider_sign == "")
                        $sig_panel_class = "warning";
                    if ($sa->learner_sign != "" && $sa->provider_sign != "")
                        $sig_panel_class = "success";
                    ?>
                    <div class="box box-<?php echo $sig_panel_class; ?> box-solid" id="sectionSignatures">
                        <div class="box-header">
                            <span class="box-title">Signatures</span>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" style="font-size: medium;">
                                    <tr>
                                        <th>Learner</th>
                                        <td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td>
                                        <td>
                                            <img id="img_learner_sign"
                                                src="do.php?_action=generate_image&<?php echo $sa->learner_sign != '' ? $sa->learner_sign : 'title=Not yet signed&font=Signature_Regular.ttf&size=25'; ?>"
                                                style="border: 2px solid;border-radius: 15px;" /><br>
                                            <?php echo Date::toShort($sa->learner_sign_date); ?>
                                        </td>
                                    </tr>
                                    <?php if ($sa->employer_sign != '') { ?>
                                    <tr>
                                        <th>Employer</th>
                                        <td><?php echo $sa->employer_sign_name; ?></td>
                                        <td>
                                            <img id="img_employer_sign"
                                                src="do.php?_action=generate_image&<?php echo $sa->employer_sign; ?>"
                                                style="border: 2px solid;border-radius: 15px;" /><br>
                                            <?php echo Date::toShort($sa->employer_sign_date); ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <th><?php echo in_array(DB_NAME, ["am_ela"]) ? 'Assessor/Tutor' : 'Training Provider'; ?>
                                        </th>
                                        <td><?php echo $sa->provider_sign != '' ? DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.signature = '{$sa->provider_sign}'") : ''; ?>
                                        </td>
                                        <td>
                                            <img id="img_provider_sign"
                                                src="do.php?_action=generate_image&<?php echo $sa->provider_sign != '' ? $sa->provider_sign : 'title=Not yet signed&font=Signature_Regular.ttf&size=25'; ?>"
                                                style="border: 2px solid;border-radius: 15px;" /><br>
                                            <?php echo Date::toShort($sa->provider_sign_date); ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-sm-12">
                    <div class="box box-info box-solid" id="sectionChangeHistory">
                        <div class="box-header">
                            <span class="box-title">Change History</span>
                        </div>
                        <div class="box-body">
                            <?php
                            $change_history_result = DAO::getResultset($link, "SELECT * FROM ob_learner_ksb_log WHERE skills_analysis_id = '{$sa->id}' ORDER BY created DESC ", DAO::FETCH_ASSOC);
                            if (count($change_history_result) > 0) {
                                echo '<div class="nav-tabs-custom">';
                                echo '<ul class="nav nav-tabs">';
                                $i = 1;
                                foreach ($change_history_result as $change_row) {
                                    $_active = $i == 1 ? 'active' : '';
                                    echo '<li class="' . $_active . '"><a href="#tab_log_' . $i . '" data-toggle="tab">' . Date::to($change_row['created'], Date::DATETIME) . '</a></li>';
                                    $i++;
                                }
                                echo '</ul>';
                                echo '<div class="tab-content">';
                                $i = 1;
                                foreach ($change_history_result as $change_row) {
                                    $_active = $i == 1 ? 'active' : '';
                                    echo '<div class="tab-pane ' . $_active . '" id="tab_log_' . $i . '">';
                                    $updated_details = json_decode($change_row['updated_detail']);
                                    echo $change_row['updated_by'] == 0 ?
                                        '<span class="text-bold">Saved By: </span> LEARNER' :
                                        '<span class="text-bold">Changed By: </span> ' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$change_row['updated_by']}'");
                                    foreach ($updated_details as $updated_detail) {
                                        echo '<div class="box-body">';
                                        echo '<span class="text-bold">Unit Group: </span> ' . $updated_detail->unit_group . '<br>';
                                        echo '<span class="text-bold">Unit Title: </span> ' . $updated_detail->unit_title . '<br>';
                                        echo '<span class="text-bold">Evidence Title: </span> ' . $updated_detail->evidence_title . '<br>';
                                        if ($change_row['updated_by'] == 0) {
                                            echo '<span class="text-bold">Score set by Learner: </span> ' . substr($updated_detail->score, -1) . '<br>';
                                        } else {
                                            echo '<span class="text-bold">Score: </span> ' . $updated_detail->score . '<br>';
                                            echo '<span class="text-bold">Comments: </span> ' . $updated_detail->comments;
                                        }
                                        echo '</div>';
                                    }
                                    echo '</div>';
                                    $i++;
                                }
                                echo '</div>'; // tab-content
                                echo '</div>'; // nav-tabs-custom
                            } else {
                                echo 'No records found.';
                            }

                            ?>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <div class="col-sm-2">
            <div class="RightMenu">
                <div class="RightMenuTitle">Sections</div>
                <div class="RightMenuItem">- <a href=""
                        onclick="document.getElementById('sectionTrainingDetails').scrollIntoView(true);return false">Training
                        Details</a></div>
                <div class="RightMenuItem">- <a href=""
                        onclick="document.getElementById('sectionScoresTable').scrollIntoView(true);return false">Scores
                        Table</a></div>
                <div class="RightMenuItem">- <a href=""
                        onclick="document.getElementById('sectionSkillsAnalysisResult').scrollIntoView(true);return false">Skills
                        Analysis Result</a></div>
                <div class="RightMenuItem">- <a href=""
                        onclick="document.getElementById('sectionSignatures').scrollIntoView(true);return false">Signatures</a>
                </div>
                <div class="RightMenuItem">- <a href=""
                        onclick="document.getElementById('sectionChangeHistory').scrollIntoView(true);return false">Change
                        History</a></div>
                <div class="RightMenuTitle">Actions</div>
                <?php if ($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [1])) { ?>
                <div class="RightMenuItem">
                    - <a href="do.php?_action=edit_skills_analysis&id=<?php echo $sa->id; ?>">Edit Skills Analysis</a>
                </div>
                <?php } ?>
                <div class="RightMenuItem">
                    - <a href="<?php echo $_SESSION['bc']->getPrevious(); ?>">Close</a>
                </div>
            </div>
        </div>
    </div>


    <span onclick="fnScrollToTop()" title="Go to top" id="btn-scroll-up" class="btn btn-success btn-sm btn-scroll-up">
        <i class="fa fa-arrow-up"></i>
    </span>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/common.js" type="text/javascript"></script>

    <script language="JavaScript">
    $(function() {

    });

    function downloadSkillsAssessment(skills_analysis_id) {
        window.location.href = "do.php?_action=generate_pdf&subaction=skillsAssessment&skills_analysis_id=" +
            skills_analysis_id + "&tr_id=<?php echo $sa->tr_id; ?>";
    }
    </script>

    <script>
    let btnScrollUp = document.getElementById("btn-scroll-up");
    window.onscroll = function() {
        fnShowScrollToTop()
    };

    function fnShowScrollToTop() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            btnScrollUp.style.display = "block";
        } else {
            btnScrollUp.style.display = "none";
        }
    }

    function fnScrollToTop() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
    </script>

</body>

</html>