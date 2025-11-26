<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>
<?php /* @var $employer Organisation */ ?>
<?php /* @var $location Location */ ?>
<?php /* @var $skills_analysis SkillsAnalysis */ ?>

<!DOCTYPE html>

<head xmlns="http://www.w3.org/1999/html">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Onboarding Enrolment</title>

    <link rel="stylesheet" href="css/common.css" type="text/css" />
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote-bs3.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/jquery.timepicker.css" />
    <link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style type="text/css">
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }

        table#tblOtjPlanner input[type="text"] {
            font-weight: bold;
            font-size: x-large;
        }
    </style>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>

</head>

<body>
    <div class="row">
        <div class="col-sm-12">
            <div class="banner">
                <div class="Title" style="margin-left: 6px;">View Onboarding Enrolment
                    [<?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?>]
                </div>
                <div class="ButtonBar">
                    <span class="btn btn-xs btn-default"
                        onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i
                            class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                    <span class="btn btn-xs btn-default"
                        onclick="window.location.href='do.php?_action=edit_training&id=<?php echo $tr->id; ?>';"><i
                            class="fa fa-edit"></i> Edit</span>
                    <?php if ($tr->learner_sign != '' && $tr->emp_sign != '' && $tr->status_code != TrainingRecord::STATUS_CONVERTED) { ?>
                        <span class="btn btn-xs btn-default"
                            onclick="window.location.href='do.php?_action=provider_sign_onboarding&tr_id=<?php echo $tr->id; ?>';"><i
                                class="fa fa-signature"></i> Sign Onboarding</span>
                    <?php } ?>
                    <?php if ($tr->isNonApp($link) && $tr->status_code == TrainingRecord::STATUS_COMPLETED) { ?>
                        <span class="btn btn-xs btn-default"
                            onclick="window.location.href='do.php?_action=provider_sign_onboarding&tr_id=<?php echo $tr->id; ?>';"><i
                                class="fa fa-signature"></i> Sign Onboarding</span>
                    <?php } ?>
                    <?php if ($tr->status_code == TrainingRecord::STATUS_COMPLETED) { ?>
                        <span class="btn btn-xs btn-default"
                            onclick="window.location.href='do.php?_action=create_ilr&tr_id=<?php echo $tr->id; ?>';"><i
                                class="fa fa-file"></i> Create ILR</span>
                    <?php } ?>
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

        <?php if ($tr->status_code == TrainingRecord::STATUS_CONVERTED && $tr->sunesis_tr_id != 0 && $tr->sunesis_tr_id != '') { ?>
            <div class="row">
                <div class="col-sm-9 col-sm-offset-2">
                    <div class="callout callout-success">
                        <i class="fa fa-info-circle"></i> <span class="lead text-bold">This record has been converted and
                            enrolled into main Sunesis System.</span>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-sm-4">
                <?php include_once(__DIR__ . '/partials/read_training_learner_details.php'); ?>
            </div>
            <div class="col-sm-8">
                <?php if (!$tr->isNonApp($link)) { ?>
                    <p>
                        <?php echo $initial_contract_label; ?>
                        <?php echo $pre_iag_label; ?>
                        <?php echo (!in_array(DB_NAME, ["am_superdrug"]) && $framework->fund_model != Framework::FUNDING_STREAM_99) ? $writing_assessment_label : ''; ?>
                        <?php echo $learn_styles_label; ?>
                        <?php echo $sa_label; ?>
                        <?php echo $ob_label; ?>
                        <?php echo $app_ag_label; ?>
                        <?php echo $otj_label; ?>
                    </p>
                <?php } else { ?>
                    <p>
                        <?php echo $enrolment_label; ?>
                        <?php echo $pre_iag_label; ?>
                        <?php echo $bespoke_tp_label; ?>
                        <?php echo $learn_styles_label; ?>
                        <?php echo $wellbeing_label; ?>
                        <?php echo (!in_array(DB_NAME, ["am_superdrug"]) && $framework->fund_model != Framework::FUNDING_STREAM_99) ? $writing_assessment_label : ''; ?>
                    </p>
                <?php } ?>
                <div class="nav-tabs-custom bg-gray-light">
                    <ul class="nav nav-tabs">
                        <li class="<?php echo $tab == 'tab_tr_details' ? 'active' : ''; ?>"><a href="#tab_tr_details"
                                data-toggle="tab" class="tabHyperlink">Details</a></li>
                        <?php if (!in_array($framework->fund_model, [Framework::FUNDING_STREAM_ASF, Framework::FUNDING_STREAM_BOOTCAMP, Framework::FUNDING_STREAM_99])) { ?>
                            <li class="<?php echo $tab == 'tab_sched' ? 'active' : ''; ?>"><a href="#tab_sched"
                                    data-toggle="tab" class="tabHyperlink">Initial Contract</a></li>
                        <?php } ?>
                        <?php if (in_array($framework->fund_model, [Framework::FUNDING_STREAM_ASF, Framework::FUNDING_STREAM_BOOTCAMP])) { ?>
                            <li class="<?php echo $tab == 'tab_bespoke_training_plan' ? 'active' : ''; ?>"><a
                                    href="#tab_bespoke_training_plan" data-toggle="tab" class="tabHyperlink">Bespoke
                                    Training Plan</a></li>
                            <li class="<?php echo $tab == 'tab_ilp' ? 'active' : ''; ?>"><a href="#tab_ilp"
                                    data-toggle="tab" class="tabHyperlink">Individual Action Plan</a></li>
                        <?php } ?>
                        <?php
                        // Databases allowed for IAG Form
                        $iagDatabases = ["am_crackerjack", "am_ela", "am_demo", "am_am"];

                        // Databases allowed for Learning Style Quiz
                        $quizDatabases = ["am_ela", "am_demo"];
                        ?>

                        <?php if (in_array(DB_NAME, $iagDatabases)) { ?>
                            <li class="<?php echo $tab == 'tab_pre_iag' ? 'active' : ''; ?>">
                                <a href="#tab_pre_iag" data-toggle="tab" class="tabHyperlink">Pre IAG Form</a>
                            </li>
                        <?php } ?>

                        <?php if (in_array(DB_NAME, $quizDatabases)) { ?>
                            <li class="<?php echo $tab == 'tab_learn_style' ? 'active' : ''; ?>">
                                <a href="#tab_learn_style" data-toggle="tab" class="tabHyperlink">Learning Style Quiz</a>
                            </li>
                        <?php } ?>

                        <?php if (in_array(DB_NAME, ["am_eet", "am_puzzled"])) { ?>
                            <?php if ($framework->fund_model == Framework::FUNDING_STREAM_APP) { ?>
                                <li class="<?php echo $tab == 'tab_pre_iag' ? 'active' : ''; ?>"><a href="#tab_pre_iag"
                                        data-toggle="tab" class="tabHyperlink">Pre IAG Form</a></li>
                            <?php } ?>
                            <li class="<?php echo $tab == 'tab_learn_style' ? 'active' : ''; ?>"><a href="#tab_learn_style"
                                    data-toggle="tab" class="tabHyperlink">Learning Style Quiz</a></li>
                            <li class="<?php echo $tab == 'tab_wellbeing' ? 'active' : ''; ?>"><a href="#tab_wellbeing"
                                    data-toggle="tab" class="tabHyperlink">Wellbeing Assessment</a></li>
                        <?php } ?>
                        <?php if (SystemConfig::getEntityValue($link, "bksb")) { ?>
                            <li class="<?php echo $tab == 'tab_bksb' ? 'active' : ''; ?>"><a href="#tab_bksb"
                                    data-toggle="tab" class="tabHyperlink"> BKSB</a></li>
                        <?php } ?>
                        <?php if (!in_array(DB_NAME, ["am_superdrug"]) && !in_array($framework->fund_model, [Framework::FUNDING_STREAM_ASF, Framework::FUNDING_STREAM_BOOTCAMP])) { ?>
                            <li class="<?php echo $tab == 'tab_als' ? 'active' : ''; ?>"><a href="#tab_als"
                                    data-toggle="tab" class="tabHyperlink">ALS</a></li>
                        <?php } ?>
                        <?php if (DB_NAME == "am_demo") { ?>
                            <li class="<?php echo $tab == 'tab_initial_assessment' ? 'active' : ''; ?>"><a
                                    href="#tab_initial_assessment" data-toggle="tab" class="tabHyperlink">Initial
                                    Assessments</a></li>
                        <?php } ?>
                        <?php if (!in_array(DB_NAME, ["am_superdrug"]) && $framework->fund_model != Framework::FUNDING_STREAM_99) { ?>
                            <li class="<?php echo $tab == 'tab_writing_asmt' ? 'active' : ''; ?>"><a
                                    href="#tab_writing_asmt" data-toggle="tab" class="tabHyperlink">Writing Assessment</a>
                            </li>
                        <?php } ?>
                        <?php if (!in_array($framework->fund_model, [Framework::FUNDING_STREAM_ASF, Framework::FUNDING_STREAM_BOOTCAMP, Framework::FUNDING_STREAM_99])) { ?>
                            <li class="<?php echo $tab == 'tab_ks' ? 'active' : ''; ?>"><a href="#tab_ks" data-toggle="tab"
                                    class="tabHyperlink">Skills Analysis</a></li>
                        <?php } ?>
                        <li class="<?php echo $tab == 'tab_prior_attainment' ? 'active' : ''; ?>"><a
                                href="#tab_prior_attainment" data-toggle="tab" class="tabHyperlink">Prior Attainment</a>
                        </li>
                        <?php if (!in_array(DB_NAME, ["am_ela", "am_eet"])) { ?>
                            <li class="<?php echo $tab == 'tab_prior_employments' ? 'active' : ''; ?>"><a
                                    href="#tab_prior_employments" data-toggle="tab" class="tabHyperlink">Employments</a>
                            </li>
                        <?php } ?>
                        <li class="<?php echo $tab == 'tab_eligibility' ? 'active' : ''; ?>"><a href="#tab_eligibility"
                                data-toggle="tab" class="tabHyperlink">Eligibility</a></li>
                        <li class="<?php echo $tab == 'tab_emergency_contacts' ? 'active' : ''; ?>"><a
                                href="#tab_emergency_contacts" data-toggle="tab" class="tabHyperlink">Contacts</a></li>
                        <?php if (!in_array($framework->fund_model, [Framework::FUNDING_STREAM_ASF, Framework::FUNDING_STREAM_BOOTCAMP, Framework::FUNDING_STREAM_99])) { ?>
                            <li class="<?php echo $tab == 'tab_app_agreement' ? 'active' : ''; ?>"><a
                                    href="#tab_app_agreement" data-toggle="tab" class="tabHyperlink">Apprenticeship
                                    Agreement</a></li>
                        <?php } ?>
                        <?php if (in_array($framework->fund_model, [Framework::FUNDING_STREAM_ASF, Framework::FUNDING_STREAM_BOOTCAMP, Framework::FUNDING_STREAM_99])) { ?>
                            <li class="<?php echo $tab == 'tab_non_app_agreement' ? 'active' : ''; ?>"><a
                                    href="#tab_non_app_agreement" data-toggle="tab" class="tabHyperlink">Learning
                                    Agreement</a></li>
                        <?php } ?>
                        <li class="<?php echo $tab == 'tab_emails' ? 'active' : ''; ?>"><a href="#tab_emails"
                                data-toggle="tab" class="tabHyperlink">Emails</a></li>
                        <li class="<?php echo $tab == 'tab_files' ? 'active' : ''; ?>"><a href="#tab_files"
                                data-toggle="tab" class="tabHyperlink">Files</a></li>
                        <li class="<?php echo $tab == 'tab_plr' ? 'active' : ''; ?>"><a href="#tab_plr"
                                data-toggle="tab" class="tabHyperlink">PLR Data</a></li>
                        <?php if (in_array(DB_NAME, ["am_ela"]) && !is_null($framework->fdil_page_content)) { ?>
                            <li class="<?php echo $tab == 'tab_fdil' ? 'active' : ''; ?>"><a href="#tab_fdil"
                                    data-toggle="tab" class="tabHyperlink">First Day in Learning</a></li>
                        <?php } ?>
                        <?php if (!$tr->isNonApp($link) && $tr->practical_period_start_date >= '2023-08-01' && (DB_NAME == "am_ela") && !in_array($tr->id, OnboardingHelper::UlnsToSkip($link))) { ?>
                            <li class="<?php echo $tab == 'tab_otj_planner' ? 'active' : ''; ?>"><a href="#tab_otj_planner"
                                    data-toggle="tab" class="tabHyperlink">OTJ Planner</a></li>
                        <?php } ?>
                        <?php if (DB_NAME == "am_ela" && in_array($tr->id, Helpers::trIdsForOtjPlanner()) && !$tr->isNonApp($link)) { ?>
                            <li class="<?php echo $tab == 'tab_otj_planner' ? 'active' : ''; ?>"><a href="#tab_otj_planner"
                                    data-toggle="tab" class="tabHyperlink">OTJ Planner</a></li>
                        <?php } ?>
                        <li class="<?php echo $tab == 'tab_delivery_plan' ? 'active' : ''; ?>"><a
                                href="#tab_delivery_plan" data-toggle="tab" class="tabHyperlink">Delivery Plan</a></li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane <?php echo $tab == 'tab_tr_details' ? 'active' : ''; ?>"
                            id="tab_tr_details">
                            <span class="lead text-info">Details</span>
                            <p><br></p>
                            <?php include_once(__DIR__ . '/partials/training_screen_training_details.php'); ?>
                        </div>
                        <?php if (!in_array($framework->fund_model, [Framework::FUNDING_STREAM_ASF, Framework::FUNDING_STREAM_BOOTCAMP])) { ?>
                            <div class="tab-pane <?php echo $tab == 'tab_sched' ? 'active' : ''; ?>" id="tab_sched">
                                <span class="lead">Initial Contract</span>
                                <p><span class="btn btn-xs btn-primary"
                                        onclick="window.location.href='do.php?_action=edit_initial_contract&id=&tr_id=<?php echo $tr->id; ?>'"><i
                                            class="fa fa-plus"></i> Create Initial Contract</span></p>
                                <p><br></p>

                                <?php include_once(__DIR__ . '/partials/training_screen_initial_contract.php'); ?>
                            </div>
                        <?php } ?>
                        <?php if (in_array($framework->fund_model, [Framework::FUNDING_STREAM_ASF, Framework::FUNDING_STREAM_BOOTCAMP])) { ?>
                            <div class="tab-pane <?php echo $tab == 'tab_bespoke_training_plan' ? 'active' : ''; ?>"
                                id="tab_bespoke_training_plan">
                                <span class="lead">Bespoke Training Plan</span>

                                <?php
                                $bespoke_training_plan_form_results = DAO::getResultset($link, "SELECT * FROM ob_learner_bespoke_training_plan WHERE tr_id = '{$tr->id}'", DAO::FETCH_ASSOC);
                                echo '<div class="table-responsive">';
                                echo '<table class="table table-bordered">';
                                echo '<tr><th>Signed By Learner</th><th>Signed By Provider</th><th></th></tr>';
                                foreach ($bespoke_training_plan_form_results as $bespoke_training_plan_form_row) {
                                    echo '<tr>';
                                    echo $bespoke_training_plan_form_row['learner_sign'] != '' ? '<td class="text-green"><i class="fa fa-check fa-lg" style="color: green"></i> Yes on ' . Date::toShort($bespoke_training_plan_form_row['learner_sign_date']) . '</td>' : '<td class="text-red">No</td>';
                                    echo $bespoke_training_plan_form_row['provider_sign'] != '' ? '<td class="text-green"><i class="fa fa-check fa-lg" style="color: green"></i> Yes on ' . Date::toShort($bespoke_training_plan_form_row['provider_sign_date']) . '</td>' : '<td class="text-red">No</td>';
                                    echo '<td>';
                                    echo '<p><span class="btn btn-info btn-xs" onclick="window.location.href=\'do.php?_action=view_bespoke_training_plan&tr_id=' . $bespoke_training_plan_form_row['tr_id'] . '\'"><i class="fa fa-folder-open"></i> Open</span></p>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                                echo '</table>';
                                echo '</div>';
                                ?>
                            </div>
                        <?php } ?>
                        <?php if (in_array($framework->fund_model, [Framework::FUNDING_STREAM_ASF, Framework::FUNDING_STREAM_BOOTCAMP])) { ?>
                            <div class="tab-pane <?php echo $tab == 'tab_ilp' ? 'active' : ''; ?>" id="tab_ilp">
                                <span class="lead">Individual Action Plan</span>

                                <p><br></p>
                                <?php include_once(__DIR__ . '/partials/training_screen_ilp.php'); ?>
                            </div>
                        <?php } ?>

                        <?php if (in_array(DB_NAME, ["am_ela", "am_demo", "am_puzzled", "am_am", "am_crackerjack"]) || (in_array(DB_NAME, ["am_eet"]) && $framework->fund_model == Framework::FUNDING_STREAM_APP)) { ?>
                            <div class="tab-pane <?php echo $tab == 'tab_pre_iag' ? 'active' : ''; ?>" id="tab_pre_iag">
                                <span class="lead">Pre IAG Form</span>
                                <p><br></p>
                                <?php
                                $iag_form_results = DAO::getResultset($link, "SELECT * FROM ob_learner_pre_iag_form WHERE tr_id = '{$tr->id}'", DAO::FETCH_ASSOC);
                                echo '<div class="table-responsive">';
                                echo '<table class="table table-bordered">';
                                echo '<tr><th>Signed By Learner</th><th>Signed By Provider</th><th></th></tr>';
                                foreach ($iag_form_results as $iag_form_row) {
                                    echo '<tr>';
                                    echo $iag_form_row['learner_sign'] != '' ? '<td class="text-green"><i class="fa fa-check fa-lg" style="color: green"></i> Yes on ' . Date::toShort($iag_form_row['learner_sign_date']) . '</td>' : '<td class="text-red">No</td>';
                                    echo $iag_form_row['provider_sign'] != '' ? '<td class="text-green"><i class="fa fa-check fa-lg" style="color: green"></i> Yes on ' . Date::toShort($iag_form_row['provider_sign_date']) . '</td>' : '<td class="text-red">No</td>';
                                    echo '<td>';
                                    echo '<p><span class="btn btn-info btn-xs" onclick="window.location.href=\'do.php?_action=view_pre_iag_form_learner&tr_id=' . $iag_form_row['tr_id'] . '\'"><i class="fa fa-folder-open"></i> Open</span></p>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                                echo '</table>';
                                echo '</div>';
                                ?>
                            </div>
                            <div class="tab-pane <?php echo $tab == 'tab_learn_style' ? 'active' : ''; ?>"
                                id="tab_learn_style">
                                <span class="lead">Learning Style Self-Assessment</span>
                                <p><br></p>
                                <?php include_once(__DIR__ . '/partials/training_screen_learning_style.php'); ?>

                            </div>
                        <?php } ?>
                        <?php if (in_array(DB_NAME, ["am_eet", "am_puzzled"])) { ?>
                            <div class="tab-pane <?php echo $tab == 'tab_learn_style' ? 'active' : ''; ?>"
                                id="tab_learn_style">
                                <span class="lead">Learning Style Self-Assessment</span>
                                <p><br></p>
                                <?php include_once(__DIR__ . '/partials/training_screen_learning_style.php'); ?>

                            </div>
                            <div class="tab-pane <?php echo $tab == 'tab_wellbeing' ? 'active' : ''; ?>" id="tab_wellbeing">
                                <span class="lead">Wellbeing Assessment</span>
                                <p><br></p>
                                <?php
                                $wellbeing_assessment_results = DAO::getResultset($link, "SELECT * FROM ob_learner_wellbeing_assessment WHERE tr_id = '{$tr->id}'", DAO::FETCH_ASSOC);
                                echo '<div class="table-responsive">';
                                echo '<table class="table table-bordered">';
                                echo '<tr><th>Signed By Learner</th><th>Signed By Provider</th><th></th></tr>';
                                foreach ($wellbeing_assessment_results as $wellbeing_assessment_form_row) {
                                    echo '<tr>';
                                    echo $wellbeing_assessment_form_row['learner_sign'] != '' ? '<td class="text-green"><i class="fa fa-check fa-lg" style="color: green"></i> Yes on ' . Date::toShort($wellbeing_assessment_form_row['learner_sign_date']) . '</td>' : '<td class="text-red">No</td>';
                                    echo $wellbeing_assessment_form_row['provider_sign'] != '' ? '<td class="text-green"><i class="fa fa-check fa-lg" style="color: green"></i> Yes on ' . Date::toShort($wellbeing_assessment_form_row['provider_sign_date']) . '</td>' : '<td class="text-red">No</td>';
                                    echo '<td>';
                                    echo '<p><span class="btn btn-info btn-xs" onclick="window.location.href=\'do.php?_action=view_wellbeing_assessment_form&tr_id=' . $wellbeing_assessment_form_row['tr_id'] . '\'"><i class="fa fa-folder-open"></i> Open</span></p>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                                echo '</table>';
                                echo '</div>';
                                ?>

                            </div>
                        <?php } ?>
                        <?php if (SystemConfig::getEntityValue($link, "bksb")) { ?>
                            <div class="tab-pane <?php echo $tab == 'tab_bksb' ? 'active' : ''; ?>" id="tab_bksb">
                                <?php include_once(__DIR__ . '/partials/training_screen_bksb.php'); ?>
                            </div>
                        <?php } ?>
                        <?php if (!in_array(DB_NAME, ["am_superdrug"]) && !in_array($framework->fund_model, [Framework::FUNDING_STREAM_ASF, Framework::FUNDING_STREAM_BOOTCAMP])) { ?>
                            <div class="tab-pane <?php echo $tab == 'tab_als' ? 'active' : ''; ?>" id="tab_als">
                                <span class="lead">Learning Support / Additional Details</span>
                                <p><br></p>
                                <?php
                                if (!in_array(DB_NAME, ['am_crackerjack'])) {
                                    echo '<p><span class="btn btn-xs btn-primary" onclick="window.location.href=\'do.php?_action=edit_ob_learner_als&tr_id=' . $tr->id . '\'">Edit ALS</span></p>';
                                    include_once(__DIR__ . '/partials/training_screen_als.php');
                                } else {
                                    echo $this->renderAlsAndAdditionalDetails($link, $tr);
                                }
                                ?>
                            </div>
                        <?php } ?>

                        <?php if (DB_NAME == "am_demo") { ?>
                            <div class="tab-pane <?php echo $tab == 'tab_initial_assessment' ? 'active' : ''; ?>"
                                id="tab_initial_assessment">
                                <span class="lead" ly>Initial Assessment</span>
                                <p><br></p>
                                <?php
                                $inital_assessment_results = DAO::getResultset($link, "SELECT * FROM ob_tr_assessments WHERE tr_id = '{$tr->id}' ORDER BY id DESC ", DAO::FETCH_ASSOC);
                                echo '<div class="table-responsive">';
                                echo '<table class="table table-bordered">';
                                echo '<tr><th>Subject</th><th>Start At</th><th>End At</th><th>Status</th><th>Action</th></tr>';
                                foreach ($inital_assessment_results as $as_row) {
                                    echo '<tr>';
                                    echo '<td>' . ucfirst($as_row['subject']) . '</td>';
                                    echo '<td>' . $as_row['start_at'] . '</td>';
                                    echo '<td>' . $as_row['end_at'] . '</td>';
                                    echo '<td>' . ($as_row['status'] == 'started' ? 'In Progress' : ucfirst(str_replace('_', ' ', $as_row['status']))) . '</td>';
                                    echo '<td>';
                                    if ($as_row['status'] == 'completed') {
                                        echo '<div class="btn btn-info btn-xs margin-r-5 " onclick="re_take_assessment(\'' . $as_row['id'] . '\',\'' . $as_row['subject'] . '\')"><i class="fa fa-envelope"></i> Re-Take</div>';
                                        echo '<div class="btn btn-info btn-xs" onclick="window.location.href=\'do.php?_action=view_learner_initial_assessment&id=' . $as_row['id'] . '\'"><i class="fa fa-eye"></i> View Report</div>';
                                    } else {
                                        echo '<div class="btn btn-info btn-xs margin-r-5 " onclick="re_take_assessment(\'' . $as_row['id'] . '\',\'' . $as_row['subject'] . '\',\'resend\')"><i class="fa fa-envelope"></i> Resend Email</div>';
                                    }
                                    echo '</td>';
                                    echo '</tr>';
                                }
                                echo '</table>';
                                echo '</div>';
                                ?>
                            </div>
                        <?php } ?>

                        <?php if (!in_array(DB_NAME, ["am_superdrug"]) && $framework->fund_model != Framework::FUNDING_STREAM_99) { ?>
                            <div class="tab-pane <?php echo $tab == 'tab_writing_asmt' ? 'active' : ''; ?>"
                                id="tab_writing_asmt">
                                <span class="lead" ly>Free Writing Assessment</span>
                                <p><br></p>
                                <?php
                                $assessment_results = DAO::getResultset($link, "SELECT * FROM ob_learner_writing_assessment WHERE tr_id = '{$tr->id}'", DAO::FETCH_ASSOC);
                                echo '<div class="table-responsive">';
                                echo '<table class="table table-bordered">';
                                echo '<tr><th>Signed By Learner</th><th>Signed By Provider</th><th></th></tr>';
                                foreach ($assessment_results as $assessment_row) {
                                    echo '<tr>';
                                    echo $assessment_row['learner_sign'] != '' ? '<td class="text-green"><i class="fa fa-check fa-lg" style="color: green"></i> Yes on ' . Date::toShort($assessment_row['learner_sign_date']) . '</td>' : '<td class="text-red">No</td>';
                                    echo $assessment_row['provider_sign'] != '' ? '<td class="text-green"><i class="fa fa-check fa-lg" style="color: green"></i> Yes on ' . Date::toShort($assessment_row['provider_sign_date']) . '</td>' : '<td class="text-red">No</td>';
                                    echo '<td>';
                                    echo '<p><span class="btn btn-info btn-xs" onclick="window.location.href=\'do.php?_action=view_learner_writing_assessment&tr_id=' . $assessment_row['tr_id'] . '\'"><i class="fa fa-folder-open"></i> Open</span></p>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                                echo '</table>';
                                echo '</div>';
                                ?>
                            </div>
                        <?php } ?>
                        <?php if (!in_array($framework->fund_model, [Framework::FUNDING_STREAM_ASF, Framework::FUNDING_STREAM_BOOTCAMP, Framework::FUNDING_STREAM_99])) { ?>
                            <div class="tab-pane <?php echo $tab == 'tab_ks' ? 'active' : ''; ?>" id="tab_ks">
                                <span class="lead">Skills Analysis</span>
                                <p><br></p>
                                <div class="callout callout-info">
                                    <i class="fa fa-info-circle"></i> Skills Analysis is signed by the Learner and the
                                    Training Provider. <br>
                                    <i class="fa fa-info-circle"></i> Please send an email by selecting the template
                                    "SKILLS_ANALYSIS_URL" from the templates list in order to send an email to the leanrer.
                                    <br>
                                    <i class="fa fa-info-circle"></i> The content of this email template contains a URL
                                    which learner can use to access and complete Skills Analysis. <br>
                                </div>
                                <?php
                                $sa_result = DAO::getResultset($link, "SELECT * FROM ob_learner_skills_analysis WHERE tr_id = '{$tr->id}'", DAO::FETCH_ASSOC);
                                echo '<div class="table-responsive">';
                                echo '<table class="table table-bordered">';
                                echo '<tr><th>Is Completed</th><th>% following assessment</th><th>Duration following assessment (months)</th><th>Signatures</th><th></th></tr>';
                                foreach ($sa_result as $sa_row) {
                                    echo '<tr>';
                                    echo $sa_row['is_finished'] == 'Y' ? '<td class="text-green"><i class="fa fa-check fa-lg" style="color: green"></i> Yes</td>' : '<td class="text-red">No</td>';
                                    echo '<td>' . $sa_row['percentage_fa'] . '</td>';
                                    echo '<td>' . $sa_row['duration_fa'] . '</td>';
                                    echo '<td>';
                                    echo $sa_row['learner_sign'] != '' ? '<span class="text-green">Learner Sign: Yes on ' . Date::toShort($sa_row['learner_sign_date']) . '</span><br>' : '<span class="text-red">Learner Sign: No</span><br>';
                                    if (DB_NAME == "am_ela") {
                                        echo $sa_row['employer_sign'] != '' ? '<span class="text-green">Employer Sign: Yes on ' . Date::toShort($sa_row['employer_sign_date']) . '</span><br>' : '<span class="text-red">Employer Sign: No</span><br>';
                                    }
                                    echo $sa_row['provider_sign'] != '' ? '<span class="text-green">Provider Sign: Yes on ' . Date::toShort($sa_row['provider_sign_date']) . '</span><br>' : '<span class="text-red">Provider Sign: No</span><br>';
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<p><span class="btn btn-info btn-xs" onclick="window.location.href=\'do.php?_action=view_skills_analysis&id=' . $sa_row['id'] . '\'"><i class="fa fa-folder-open"></i> Open Skills Analysis</span></p>';
                                    if ($sa_row['learner_sign'] != '' && $sa_row['provider_sign'] == '') {
                                        echo '<span class="small"><i class="fa fa-flag text-yellow"></i> Skills scan is signed by the learner. Please sign it and mark it as completed.</span>';
                                    }
                                    echo '<p>';
                                    if ($sa_row['is_finished'] == 'Y') {
                                        echo '<hr>';
                                        echo '<span class="btn btn-primary btn-xs" onclick="update_tr_from_sa(\'' . $sa_row['id'] . '\');"><i class="fa fa-refresh"></i> Update Price and Duration </span><br>';
                                        //echo $sa_row['update_from_counter'] > 0 ? '<span class="small"><i class="fa fa-warning text-info"></i> Price and duration have already been updated ' . $sa_row['update_from_counter'] . ' time(s).</span>' : '';
                                        echo $sa_row['update_from_counter_duration'] > 0 ? '<span class="small"><i class="fa fa-warning text-info"></i> Duration has already been updated ' . $sa_row['update_from_counter_duration'] . ' time(s).</span><br>' : '';
                                        echo $sa_row['update_from_counter_price'] > 0 ? '<span class="small"><i class="fa fa-warning text-info"></i> Price has already been updated ' . $sa_row['update_from_counter_price'] . ' time(s).</span>' : '';
                                        //echo ($sa_row['update_from_counter'] == 0 && $sa_row['is_finished'] == 'Y') ? '<span class="small"><i class="fa fa-flag text-red"></i> Skills scan is completed and signed, please click \'Update Price and Duration\' button to update the price and duration based on Skills analysis.</span>' : '';
                                        echo '<hr>';
                                        echo '<span class="btn btn-primary btn-xs" onclick="update_tr_duration_from_sa(\'' . $sa_row['id'] . '\');"><i class="fa fa-refresh"></i> Update Duration only <i class="fa fa-calendar"></i></span><br>';
                                        echo $sa_row['update_from_counter_duration'] > 0 ? '<span class="small"><i class="fa fa-warning text-info"></i> Duration has already been updated ' . $sa_row['update_from_counter_duration'] . ' time(s).</span>' : '';
                                        echo ($sa_row['update_from_counter_duration'] == 0 && $sa_row['is_finished'] == 'Y') ? '<span class="small"><i class="fa fa-flag text-red"></i> Skills scan is completed and signed, please click \'Update Duration\' button to update the duration based on Skills analysis.</span>' : '';
                                        echo '<hr>';
                                        echo '<span class="btn btn-primary btn-xs" onclick="update_tr_price_from_sa(\'' . $sa_row['id'] . '\');"><i class="fa fa-refresh"></i> Update Price only <i class="fa fa-gbp"></i> </span><br>';
                                        echo $sa_row['update_from_counter_price'] > 0 ? '<span class="small"><i class="fa fa-warning text-info"></i> Price has already been updated ' . $sa_row['update_from_counter_price'] . ' time(s).</span>' : '';
                                        echo ($sa_row['update_from_counter_price'] == 0 && $sa_row['is_finished'] == 'Y') ? '<span class="small"><i class="fa fa-flag text-red"></i> Skills scan is completed and signed, please click \'Update Price\' button to update the price based on Skills analysis.</span>' : '';
                                    }

                                    echo '</p>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                                echo '</table>';
                                echo '</div>';
                                ?>
                            </div>
                        <?php } ?>
                        <div class="tab-pane <?php echo $tab == 'tab_prior_attainment' ? 'active' : ''; ?>"
                            id="tab_prior_attainment">
                            <span class="lead">Prior Attainment</span>
                            <p><br></p>
                            <?php include_once(__DIR__ . '/partials/training_screen_prior_attainment.php'); ?>
                        </div>
                        <?php if (!in_array(DB_NAME, ["am_ela", "am_eet"])) { ?>
                            <div class="tab-pane <?php echo $tab == 'tab_prior_employments' ? 'active' : ''; ?>"
                                id="tab_prior_employments">
                                <span class="lead">Employments</span>
                                <p><br></p>
                                <?php include_once(__DIR__ . '/partials/training_screen_employment_history.php'); ?>
                            </div>
                        <?php } ?>
                        <div class="tab-pane <?php echo $tab == 'tab_eligibility' ? 'active' : ''; ?>"
                            id="tab_eligibility">
                            <span class="lead">Eligibility</span>
                            <p><br></p>
                            <?php
                            if ($framework->fund_model == Framework::FUNDING_STREAM_99 && ($framework->fund_model_extra == Framework::FUNDING_STREAM_LEARNER_LOAN || $framework->fund_model_extra == Framework::FUNDING_STREAM_COMMERCIAL)) {
                                include_once(__DIR__ . '/partials/training_screen_eligibility_learner_loan.php');
                            } else {
                                include_once(__DIR__ . '/partials/training_screen_eligibility.php');
                            }
                            ?>
                        </div>
                        <div class="tab-pane <?php echo $tab == 'tab_emergency_contacts' ? 'active' : ''; ?>"
                            id="tab_emergency_contacts">
                            <span class="lead">Contacts</span>
                            <p><br></p>
                            <?php include_once(__DIR__ . '/partials/training_screen_learner_contact.php'); ?>
                        </div>
                        <?php if (!in_array($framework->fund_model, [Framework::FUNDING_STREAM_ASF, Framework::FUNDING_STREAM_BOOTCAMP, Framework::FUNDING_STREAM_99])) { ?>
                            <div class="tab-pane <?php echo $tab == 'tab_app_agreement' ? 'active' : ''; ?>"
                                id="tab_app_agreement">
                                <span class="lead">Apprenticeship Agreement</span>
                                <p><br></p>
                                <div class="callout callout-info">
                                    <i class="fa fa-info-circle"></i> Apprenticeship agreement is signed by the Learner and
                                    the Employer. <br>
                                    <i class="fa fa-info-circle"></i> Please send an email by selecting the template
                                    "ONBOARDING_URL" from the templates list in order to send an email to the leanrer. <br>
                                    <i class="fa fa-info-circle"></i> The content of this email template contains a URL
                                    which learner can use to access and complete Onboarding Questionnaire and sign the
                                    Apprenticeship Agreement. <br>
                                    <i class="fa fa-info-circle"></i> After learner completes the Onboarding Questionnaire,
                                    please send an email to employer by choosing template "APP_AGREEMENT_EAMIL_TO_EMPLOYER".
                                    <br>
                                </div>
                                <?php include_once(__DIR__ . '/partials/training_screen_app_agreement.php'); ?>
                            </div>
                        <?php } elseif (in_array($framework->fund_model, [Framework::FUNDING_STREAM_ASF, Framework::FUNDING_STREAM_BOOTCAMP])) { ?>
                            <div class="tab-pane <?php echo $tab == 'tab_non_app_agreement' ? 'active' : ''; ?>"
                                id="tab_non_app_agreement">
                                <span class="lead">Learning Agreement & Induction Checklist</span>
                                <p><br></p>

                                <?php include_once(__DIR__ . '/partials/training_screen_non_app_agreement.php'); ?>
                            </div>
                        <?php } elseif ($framework->fund_model == Framework::FUNDING_STREAM_99 && ($framework->fund_model_extra == Framework::FUNDING_STREAM_LEARNER_LOAN || $framework->fund_model_extra == Framework::FUNDING_STREAM_COMMERCIAL)) { ?>
                            <div class="tab-pane <?php echo $tab == 'tab_non_app_agreement' ? 'active' : ''; ?>"
                                id="tab_non_app_agreement">
                                <span class="lead">Learning Agreement</span>
                                <p><br></p>

                                <?php include_once(__DIR__ . '/partials/training_screen_non_app_agreement_learner_loan.php'); ?>
                            </div>
                        <?php } ?>

                        <div class="tab-pane <?php echo $tab == 'tab_emails' ? 'active' : ''; ?>" id="tab_emails">
                            <span class="lead">Emails</span>
                            <p><br></p>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="box box-info collapsed-box">
                                        <div class="box-header">
                                            <span class="box-title">Email Templates Help</span>
                                            <div class="box-tools pull-right">
                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                        class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <div class="callout callout-info">
                                                SKILLS_SCAN_URL: Use this template to send Skills Analysis email to the
                                                Learner.<br>
                                                ONBOARDING_URL: Use this template to send Onboarding Questionnaire email
                                                to the Learner. This will also contain an Apprenticeship Agreement for
                                                the learner.<br>
                                                SKILLS_SCAN_PASSED: [optional] Send this email to the learner if learner
                                                is eligible after Skills Analysis.<br>
                                                SKILLS_SCAN_FAILED: [optional] Send this email to the learner if learner
                                                is not eligible after Skills Analysis.<br>
                                                APP_AGREEMENT_EAMIL_TO_EMPLOYER: Send this email to the employer to get
                                                their signature on Apprenticeship Agreement. Please note that system
                                                auto sends this email when learner completes Onboarding
                                                Questionnaire.<br>
                                                LEARNER_FIRST_DAY_IN_LEARNING: Send this email to the learner to record
                                                their first day of learnering information.<br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-4 col-sm-offset-4">
                                    <span id="btnCompose" class="btn btn-primary btn-block margin-bottom"
                                        onclick="$(this).hide(); $('#mailBox').hide(); $('#composeNewMessageBox').show();">Compose
                                        New Email</span>
                                </div>
                                <div class="col-sm-12" id="composeNewMessageBox" style="display: none;">
                                    <?php echo $this->renderComposeNewMessageBox($link, $tr); ?>
                                </div>
                            </div>
                            <hr>
                            <?php echo $this->showSentEmails($link, $tr); ?>
                        </div>
                        <div class="tab-pane <?php echo $tab == 'tab_files' ? 'active' : ''; ?>" id="tab_files">
                            <span class="lead">Files</span>
                            <p><br></p>
                            <div class="row">
                                <div class="col-sm-6">
                                    <?php
                                    $dirs = ["schedule1", "writing_assessment", "skills_analysis", "onboarding"];
                                    if (DB_NAME == "am_ela") {
                                        $dirs = ["schedule1", "iag", "learn_style_assessment", "writing_assessment", "skills_analysis", "onboarding"];
                                    }
                                    foreach ($dirs as $section) {
                                        echo $section != 'schedule1' ? '<span class="lead">' . ucwords(str_replace("_", " ", $section)) . ':</span>' : '<span class="lead">Initial Contract</span>';
                                        $s_files = Repository::readDirectory($tr->getDirectoryPath() . $section);
                                        $trs1 = '';
                                        foreach ($s_files as $s_file) {
                                            if (in_array($s_file->getName(), ["tutor_sign_image.png", "emp_sign_image.png", "learner_sign_image.png", "tp_sign_image.png", "provider_sign_image.png", "employer_sign_image.png"])) {
                                                continue;
                                            }

                                            $trs1 .= '<tr>';
                                            $trs1 .= '<td>' . $s_file->getName() . '</td><td>' . date('d/m/Y H:i:s', $s_file->getModifiedTime()) . '</td>';
                                            $trs1 .= '<td>';
                                            $trs1 .= '<a class="btn btn-xs btn-info" href="' . $s_file->getDownloadURL() . '"><i class="fa fa-download"></i></a>';
                                            if ($_SESSION['user']->username == 'aperspective') {
                                                $trs1 .= '<br><br><a class="btn btn-xs btn-danger" href="' . $s_file->getDeletionURL() . '"><i class="fa fa-trash"></i></a>';
                                            }
                                            $trs1 .= '</td>';
                                            $trs1 .= '</tr>';
                                        }
                                        echo '<table class="table table-bordered table-condensed"><tr><th>Name</th><th>Creation Timestamp</th><th>Actions</th></tr>';
                                        echo $trs1 == '' ? '<tr><td colspan="3"><i>No files in this section</i></td></tr>' : $trs1;
                                        echo '</table>';
                                        echo '<hr>';
                                    }
                                    ?>
                                </div>
                                <div class="col-sm-6">
                                    <div class="box">
                                        <div class="box-body">
                                            <form name="frmUploadFile" id="frmUploadFile" method="post"
                                                action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=upload_learner_files"
                                                ENCTYPE="multipart/form-data">
                                                <input type="hidden" name="_action" value="upload_learner_files" />
                                                <input type="hidden" name="ob_learner_id"
                                                    value="<?php echo $tr->ob_learner_id; ?>" />
                                                <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />
                                                <table class="table table-responsive">
                                                    <tr>
                                                        <td colspan="2">
                                                            <input class="compulsory" type="file"
                                                                name="input_uploaded_learner_file"
                                                                id="input_uploaded_learner_file"
                                                                accept=".jpg, .pdf, .doc, .docx, .xls, .xlsx, .csv, .txt, .xml, .zip, .rar, .7z" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <!-- <span id="uploadFileButton" class="btn btn-xs btn-primary" onclick="uploadFile();"><i class="fa fa-upload"></i> Click to Upload</span> -->
                                                            <button type="submit" id="uploadFileButton"
                                                                class="btn btn-xs btn-primary"><i
                                                                    class="fa fa-upload"></i> Click to Upload</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                    <br>
                                    <?php echo $this->renderFileRepository($tr); ?>
                                </div>
                            </div>
                            <p><br></p>
                            <table class="table table-bordered">
                                <?php if (in_array($framework->fund_model, [Framework::FUNDING_STREAM_ASF, Framework::FUNDING_STREAM_BOOTCAMP])) { ?>
                                    <tr>
                                        <td><span class="btn btn-xs btn-success"
                                                onclick="generateDocumentPdf('EF');">Generate Enrolment Form</span></td>
                                        <td>Generate new pdf document for enrolment form.</td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td><span class="btn btn-xs btn-success" onclick="generateOtjSheet();">Generate OTJT
                                            Sheet</span></td>
                                    <td>OTJT template sheet to record off the job activities for this learner.</td>
                                </tr>
                                <tr>
                                    <td><span class="btn btn-xs btn-success"
                                            onclick="generateDocumentPdf('S1');">Generate Initial Contract</span></td>
                                    <td>Generate a new intial contract pdf document.</td>
                                </tr>
                                <?php if (DB_NAME == "am_ela") { ?>
                                    <tr>
                                        <td><span class="btn btn-xs btn-success"
                                                onclick="generateDocumentPdf('IAG');">Generate IAG</span></td>
                                        <td>Generate new pdf document for IAG.</td>
                                    </tr>
                                    <tr>
                                        <td><span class="btn btn-xs btn-success"
                                                onclick="generateDocumentPdf('LSA');">Generate Learning Style
                                                Assessment</span></td>
                                        <td>Generate new pdf document for learner's writing assessment.</td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td><span class="btn btn-xs btn-success"
                                            onclick="generateDocumentPdf('SS');">Generate Skills Analysis</span></td>
                                    <td>Generate new pdf document for skills analysis.</td>
                                </tr>
                                <tr>
                                    <td><span class="btn btn-xs btn-success"
                                            onclick="generateDocumentPdf('AA');">Generate Apprenticeship
                                            Agreement</span></td>
                                    <td>Generate new pdf document for apprenticeship agreement.</td>
                                </tr>
                                <tr>
                                    <td><span class="btn btn-xs btn-success"
                                            onclick="generateDocumentPdf('CS');">Generate Training Plan</span></td>
                                    <td>Generate new pdf document for training plan.</td>
                                </tr>
                                <tr>
                                    <td><span class="btn btn-xs btn-success"
                                            onclick="generateDocumentPdf('LWA');">Generate Learner Writing
                                            Assessment</span></td>
                                    <td>Generate new pdf document for learning writing assessment.</td>
                                </tr>
                                <?php if (DB_NAME == "am_ela" && $tr->emp_sign != '') { ?>
                                    <tr>
                                        <td><span class="btn btn-xs btn-success"
                                                onclick="generateEvidenceOfEmploymentPdf('EE');">Generate Evidence of
                                                Employment</span></td>
                                        <td>Generate new pdf document for evidence of employment.</td>
                                    </tr>
                                    <?php if ($employer_agreement_full_signed) { ?>
                                        <tr>
                                            <td><span class="btn btn-xs btn-success"
                                                    onclick="generateEmpAgrWithTr('<?php echo $tr->id; ?>');">Generate Employer
                                                    Agreement</span></td>
                                            <td>Generate employer agreement with learner's information.</td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>

                            </table>
                        </div>
                        <div class="tab-pane <?php echo $tab == 'tab_plr' ? 'active' : ''; ?>" id="tab_plr">
                            <span class="lead">PLR Data from LRS</span>
                            <p><br></p>
                            <?php include_once(__DIR__ . '/partials/training_screen_learner_plr.php'); ?>
                        </div>
                        <?php if (in_array(DB_NAME, ["am_ela"]) && !is_null($framework->fdil_page_content)) { ?>
                            <div class="tab-pane <?php echo $tab == 'tab_fdil' ? 'active' : ''; ?>" id="tab_fdil">
                                <span class="lead">First Day in Learning</span>
                                <?php include_once(__DIR__ . '/partials/training_screen_learner_fdil.php'); ?>
                            </div>
                        <?php } ?>
                        <?php if ($tr->practical_period_start_date >= '2023-08-01' && (DB_NAME == "am_ela") && !in_array($tr->id, OnboardingHelper::UlnsToSkip($link))) { ?>
                            <div class="tab-pane <?php echo $tab == 'tab_otj_planner' ? 'active' : ''; ?>"
                                id="tab_otj_planner">
                                <span class="lead">OTJ Planner</span>
                                <?php include_once(__DIR__ . '/partials/training_screen_learner_otj_planner.php'); ?>
                            </div>
                        <?php } ?>
                        <?php if (DB_NAME == "am_ela" && in_array($tr->id, Helpers::trIdsForOtjPlanner())) { ?>
                            <div class="tab-pane <?php echo $tab == 'tab_otj_planner' ? 'active' : ''; ?>"
                                id="tab_otj_planner">
                                <span class="lead">OTJ Planner</span>
                                <?php include_once(__DIR__ . '/partials/training_screen_learner_otj_planner.php'); ?>
                            </div>
                        <?php } ?>
                        <div class="tab-pane <?php echo $tab == 'tab_delivery_plan' ? 'active' : ''; ?>"
                            id="tab_delivery_plan">
                            <span class="lead">Delivery Plan</span>
                            <?php include_once(__DIR__ . '/partials/training_screen_delivery_plan.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="emailModal" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h5 class="modal-title text-bold">Email Editor</h5>
                    </div>
                    <div class="modal-body">
                        <form autocomplete="0" class="form-horizontal" method="post" name="frmEmailInitialContract"
                            id="frmEmailInitialContract" method="post" action="do.php">
                            <input type="hidden" name="_action" value="ajax_email_actions" />
                            <input type="hidden" name="subaction" value="sendEmail" />
                            <input type="hidden" name="frmEmailEntityType" value="tr" />
                            <input type="hidden" name="frmEmailEntityId" value="<?php echo $tr->id; ?>" />
                            <input type="hidden" name="initial_contract_id" value="" />
                            <input type="hidden" name="frmEmailFrom" value="no-reply@perspective-uk.com" />
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="control-group"><label class="control-label" for="frmEmailTo">To:</label>
                                        <input autocomplete="off" type="text" name="frmEmailTo" id="frmEmailTo"
                                            class="form-control compulsory"
                                            value="<?php echo $primary_contact_email; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="control-group"><label class="control-label"
                                            for="frmEmailSubject">Subject:</label>
                                        <input type="text" name="frmEmailSubject" id="frmEmailSubject"
                                            class="form-control compulsory" value="Initial Contract" autocomplete="0">
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="frmEmailBody">Message:</label>
                                <textarea name="frmEmailBody" id="frmEmailInitialContractEmailBody"
                                    class="form-control"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left btn-md"
                            onclick="$('#emailModal').modal('hide');">Cancel</button>
                        <button type="button" id="btnEmailModalSave" class="btn btn-primary btn-md"><i
                                class="fa fa-send"></i> Send</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalOverwriteOtj" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form autocomplete="0" class="form-horizontal" method="post" name="frmOverwriteOtj"
                        id="frmOverwriteOtj" method="post" action="do.php">
                        <input type="hidden" name="_action" value="ajax_helper" />
                        <input type="hidden" name="subaction" value="overwriteOtj" />
                        <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title text-bold">Overwrite OTJ Hours</h5>
                        </div>
                        <div class="modal-body">
                            <p class="text-info"><i class="fa fa-info-circle"></i> Use this panel to overwrite OTJ hours
                                for this learner.</p>
                            <p class="text-info"><i class="fa fa-info-circle"></i> Please note that if you overwrite OTJ
                                hours then system will stop auto calculating OTJ for this learner.</p>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="control-group"><label class="control-label" for="otj_overwritten">Enter
                                            OTJ hours:</label>
                                        <input required type="number" name="otj_overwritten" id="otj_overwritten"
                                            class="form-control compulsory" value="<?php echo $tr->otj_overwritten; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left btn-md"
                                onclick="$('#modalOverwriteOtj').modal('hide');">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-md"><i class="fa fa-save"></i>
                                Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="reTakeEmailModal" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h5 class="modal-title text-bold">Email Editor</h5>
                    </div>
                    <div class="modal-body">
                        <form autocomplete="0" class="form-horizontal" method="post" name="reTakeEmail" id="reTakeEmail"
                            method="post" action="do.php">
                            <input type="hidden" name="_action" value="ajax_email_actions" />
                            <input type="hidden" name="subaction" value="sendEmail" />
                            <input type="hidden" name="frmEmailEntityType" value="ob_learners" />
                            <input type="hidden" name="frmEmailEntityId" value="<?php echo $tr->id; ?>" />
                            <input type="hidden" name="as_id" value="" />
                            <input type="hidden" name="subject" value="" />
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="control-group"><label class="control-label" for="frmEmailTo">To:</label>
                                        <input autocomplete="off" type="text" name="frmEmailTo" id="frmEmailTo"
                                            class="form-control compulsory"
                                            value="<?php echo $ob_learner->home_email; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="control-group"><label class="control-label"
                                            for="frmEmailSubject">Subject:</label>
                                        <input autocomplete="0" type="text" name="frmEmailSubject" id="frmEmailSubject"
                                            class="form-control compulsory"
                                            value="Employer Agreement<?php echo DB_NAME == "am_ela" ? " - ELA Training" : ""; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="frmEmailBody">Message:</label>
                                <textarea name="frmEmailBody" id="frmReTakeEmailBody" class="form-control"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left btn-md"
                            onclick="$('#reTakeEmailModal').modal('hide');">Cancel</button>
                        <button type="button" id="btnReTakeModalSave" class="btn btn-primary btn-md"><i
                                class="fa fa-send"></i> Send</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="dialogDeleteFile" style="display:none" title="Delete file"></div>

        <script>
            var phpTrainingId = '<?php echo $tr->id; ?>';
            var clientName =
                '<?php echo DAO::getSingleValue($link, "SELECT value FROM configuration WHERE entity = 'client_name'"); ?>';
            var phpLearnerPersonalEmail = '<?php echo $ob_learner->home_email; ?>';
            var phpBksbUsername = '<?php echo $ob_learner->bksb_username; ?>';
            var phpObLearnerId = '<?php echo $ob_learner->id; ?>';
            var phpEmployerContactEmail = '<?php echo DAO::getSingleValue($link, "SELECT
                organisation_contacts.`contact_email` FROM organisation_contacts
                WHERE organisation_contacts.`org_id` = '{$tr->employer_id}'
                AND organisation_contacts.`job_role` IN (28, 99)
                AND organisation_contacts.`contact_email` IS NOT NULL
                ORDER BY organisation_contacts.`contact_id` DESC
                LIMIT 1;"); ?>';

            var lrs_request = {
                'FindType': 'FUL',
                'FamilyName': <?php echo json_encode($ob_learner->surname); ?>,
                'GivenName': <?php echo json_encode($ob_learner->firstnames); ?>,
                'DateOfBirth': <?php echo json_encode($ob_learner->dob); ?>,
                'Gender': <?php echo json_encode($ob_learner->gender); ?>,
                'LastKnownPostCode': <?php echo json_encode($ob_learner->home_postcode); ?>,
                'EmailAddress': <?php echo json_encode($ob_learner->home_email); ?>,
                'ULN': <?php echo json_encode($ob_learner->uln); ?>,
                'tr_id': <?php echo json_encode($tr->id); ?>,
            };

            function createIlr() {
                const tpSign = '<?php echo $tr->tp_sign; ?>';
                if (tpSign == '') {
                    alert('Please sign the Onboarding Enrolment before creating ILR.');
                    return false;
                }

                window.location.href = 'do.php?_action=create_ilr&tr_id=<?php echo $tr->id; ?>';
            }
        </script>

        <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
        <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
        <script src="/assets/adminlte/dist/js/app.min.js"></script>
        <script src="js/common.js" type="text/javascript"></script>
        <script src="/assets/adminlte/plugins/summernote/summernote.min.js"></script>
        <script src="/assets/js/jquery/jquery.timepicker.js"></script>
        <script src="js/read_training.js?n=<?php echo time(); ?>" type="text/javascript"></script>
        <script src="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>

        <script>
            function re_take_assessment(as_id, subject, task) {
                var form = document.forms["reTakeEmail"];
                form.as_id.value = as_id;
                form.subject.value = subject;
                form.frmEmailSubject.value = "ReTake initial assessment for " + subject;
                if (task == 'resend') {
                    form.frmEmailSubject.value = "Initial assessment for " + subject;
                }


                function getEmployerHsTemplateCallback(client) {
                    if (client.status == 200) {
                        $("#frmReTakeEmailBody").summernote("code", client.responseText);
                    }
                    $('#reTakeEmailModal').modal('show');
                }

                var client = ajaxRequest('do.php?_action=ajax_email_actions&subaction=getTrainerAsTemplate' +
                    '&as_id=' + as_id, null, null, getEmployerHsTemplateCallback);
            }

            $("button#btnReTakeModalSave").click(function() {
                var form = document.forms["reTakeEmail"];
                if (!validateForm(form)) {
                    return;
                }
                var client1 = ajaxPostForm(form);
                if (client1 && client1.responseText == 'success') {
                    window.location.reload();
                }
            });
        </script>

</body>

</html>