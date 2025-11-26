<?php /* @var $vo AdditionalSupport */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $vo->id == ''?'Add Evidence Matrix Submission':'Edit Evidence Matrix Submission'; ?></title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/jquery.timepicker.css" />

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
        .disabled{
            pointer-events:none;
            opacity:0.4;
        }


table {
  text-align: left;
  position: relative;
}

th {
  position: sticky;
  top: 0;
}

.div1 {
    max-height: 600px;
    overflow: scroll;
}


</style>


</head>
<body>

<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;"><?php echo $vo->id == ''?'Add Evidence Matrix Submission':'Edit Evidence Matrix Submission'; ?></div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <?php if($enable_save or $_SESSION['user']->id==21074){?>
                <span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
                <?php } ?>
                <?php if($submission_id != ''){?>
                <span class="btn btn-sm btn-default" onclick="delete_record(<?php echo $submission_id; ?>,<?php echo $tr_id; ?>,<?php echo $vo->project_id; ?>);"><i class="fa fa-trash"></i> Delete</span>
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
<form class="form-horizontal" name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="_action" value="save_evidence_matrix_submission" />
<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
<input type="hidden" name="project_id" value="<?php echo $vo->project_id ?>" />
<input type="hidden" name="tr_id" value="<?php echo $tr_id ?>" />
<input type="hidden" name="course_id" value="<?php echo $course_id ?>" />
<?php if(DB_NAME=="am_baltic_demo" or DB_NAME=="am_baltic") { ?>
<div class="col-md-12">
<?php } else { ?>
<div class="col-md-6">
<?php } ?>
    <div class="box box-primary">
        <div class="box-body">

            <div class="form-group">
                <label for="contract_holder" class="col-sm-4 control-label fieldLabel_compulsory">Project:</label>
                <div class="col-sm-8">
                    <?php echo HTML::selectChosen('mode', $mode_ddl, $vo->mode, true, true); ?>
                </div>
            </div>
            <?php if(DB_NAME=='am_baltic_demo' or DB_NAME=='am_baltic') { ?>
            <div class="form-group">
                <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">IQA Lead:</label>
                <div class="col-sm-8">
                    <?php echo HTML::selectChosen('iqa', $iqa_dropdown, $vo->iqa, true, false); ?>
                </div>
            </div>
            <?php } ?>        
            <div class = "row">
            <div class="col-sm-4" align=right><span onclick="showHideBlock('matrix')" class="btn btn-primary btn-xs"><i class="fad fa-bars"></i> Evidence Matrix</span></div>
            </div>
            <br>
            <div style="display:block" id="matrix" class="table-responsive">
                <?php
                $competencies = DAO::getResultset($link, "SELECT
                                id, description,
                                (SELECT COUNT(*) FROM evidence_criteria INNER JOIN courses ON courses.id = evidence_criteria.course_id AND courses.id = $course_id WHERE competency = lookup_assessment_plan_log_mode.id AND courses.id = evidence_criteria.course_id) AS evidence_count
                                FROM lookup_assessment_plan_log_mode WHERE framework_id = (SELECT framework_id FROM courses WHERE id = $course_id);", DAO::FETCH_ASSOC);

                echo '<div class="div1"><table class="resultset" ><tr><th style="padding: 5px">Competency</th><th>Criteria</th><th style="padding: 5px">Include</th><th style="padding: 5px">Accept ';
                echo '<input onclick="iqa_accept(this)" name="iqa_accept[]" type = checkbox " >';
                echo '</th><th style="padding: 5px">Reject ';
                echo '<input onclick="iqa_reject(this)" name="iqa_reject[]" type = checkbox " >';
                echo '</th><th style="padding: 5px">Reject Reason</th><th style="padding: 5px">Coach Rejection Actioned</th><th style="padding: 5px">Fail Reason 1</th><th style="padding: 5px">Fail Reason 2</th><th style="padding: 5px">Fail Reason 3</th><th style="padding: 5px">Rejection Comments</th><th>Recommendation Comments</th><th>Recommendation Type</th><th>Coach Actioned Status</th></tr>';

                $index = 1;
                foreach($competencies AS $competency)
                {
                    if($index == 1)
                    {
                        $index = 0;
                        $bgcolor = "#E5F2FF";
                    }
                    else
                    {
                        $index = 1;
                        $bgcolor = "White";
                    }
                    $count = ($competency['evidence_count']==0)?1:$competency['evidence_count'];
                    echo '<tr bgcolor = "' . $bgcolor . '"><td align="center"  style = "vertical-align: middle;" rowspan=' . $count . '><b>' . $competency['description'] . '</b></td>';

                    //$exists = DAO::getSingleValue($link, "select count(*) from submissions_iqa where tr_id = '$tr_id' and submission_id = '$submission_id'");
                    if($submission_id == '')
                    {
                        $submission_id2 = DAO::getSingleValue($link, "SELECT MAX(id) FROM project_submissions WHERE project_id = '$project_id';");
                        $evidences = DAO::getResultset($link, "SELECT 
                        evidence_criteria.*
                        ,submissions_iqa.*
                        ,(SELECT COUNT(*) FROM evidence_criteria_dropdown WHERE evidence_criteria_dropdown.evidence_criteria_id = evidence_criteria.id) AS dropdown
                        FROM evidence_criteria 
                        LEFT JOIN submissions_iqa ON submissions_iqa.tr_id = '$tr_id' and submission_id = '$submission_id2' AND evidence_criteria.id = submissions_iqa.competency_id
                        WHERE course_id = '{$course_id}' and competency = '{$competency['id']}' order by sequence", DAO::FETCH_ASSOC);
                    }
                    else
                    {
                        $evidences = DAO::getResultset($link, "SELECT 
                        evidence_criteria.*
                        ,submissions_iqa.*
                        ,(SELECT COUNT(*) FROM evidence_criteria_dropdown WHERE evidence_criteria_dropdown.evidence_criteria_id = evidence_criteria.id) AS dropdown
                        FROM evidence_criteria 
                        LEFT JOIN submissions_iqa ON submissions_iqa.tr_id = '$tr_id' and submission_id = '$submission_id' AND evidence_criteria.id = submissions_iqa.competency_id
                        WHERE course_id = '{$course_id}' and competency = '{$competency['id']}' order by sequence", DAO::FETCH_ASSOC);
                    }    

                    foreach($evidences as $evidence)
                    {
                        echo '<td width="500px" style="padding: 5px">' . $evidence['criteria'] . '</td>';
                        $matrix = explode(",",$vo->matrix);
                        if(in_array($evidence['id'],$matrix))
                            echo '<td align=center><input name="matrix[]" checked type = checkbox value = " ' . $evidence['id'] . '" >';
                        else
                            echo '<td align=center><input name="matrix[]" type = checkbox value = " ' . $evidence['id'] . '" >';

                        if(DB_NAME=='am_balti')
                        {
                            if($evidence['dropdown']>0)
                            {
                                $options_ddl = DAO::getResultSet($link, "SELECT id, description, NULL FROM evidence_criteria_dropdown WHERE evidence_criteria_id = {$evidence['id']}");
                                echo '<td width="100px">';
                                echo HTML::selectChosen('evidence_options'.$evidence['id'], $options_ddl, $evidence['dropdown_id'], true);
                                echo '</td>';
                            }
                            else
                            {
                                echo '<td>&nbsp;</td>';
                            } 
                        }    

                        if(DB_NAME=='am_baltic_demo' or DB_NAME=='am_baltic')
                        {
                            if(EvidenceMatrixSubmission::allowedToEdit())
                            {
                                
                                $accept_checked = ($evidence['iqa_accept']==1)?" checked ":"";
                                $reject_checked = ($evidence['iqa_reject']==1)?" checked ":"";
                                echo '<td align=center><input name="iqa_accept[]" type = checkbox ' . $accept_checked . '  value = " ' . $evidence['id'] . '" ></td>';
                                echo '<td align=center><input name="iqa_reject[]" type = checkbox ' . $reject_checked . '  value = " ' . $evidence['id'] . '" ></td>';

                                echo '<td width="200px" style="padding: 5px">';
                                echo HTML::selectChosen('reject_reason'.$evidence['id'], $iqa_reasons2, $evidence['reject_reason'], true);
                                echo '</td>';
                                if($evidence['coach_recommendations']==1)
                                    echo '<td align=center><input name="coach_recommendations_' . $evidence['id'] . '" checked type = checkbox  value = " ' . $evidence['id'] . '" >';
                                else
                                    echo '<td align=center><input name="coach_recommendations_' . $evidence['id'] . '" type = checkbox value = " ' . $evidence['id'] . '" >';
                                //echo '<td width="200px" style="padding: 5px">';
                                //echo HTML::selectChosen('attempt'.$evidence['id'], $attempts, $evidence['first_sample'], true); 
                                //echo '</td>';
                                echo '<td width="200px" style="padding: 5px">';
                                echo HTML::selectChosen('fail_reason1_'.$evidence['id'], $fail_reasons, $evidence['fail_reason1'], true); 
                                echo '</td>';
                                echo '<td width="200px" style="padding: 5px">';
                                echo HTML::selectChosen('fail_reason2_'.$evidence['id'], $fail_reasons, $evidence['fail_reason2'], true); 
                                echo '</td>';
                                echo '<td width="200px" style="padding: 5px">';
                                echo HTML::selectChosen('fail_reason3_'.$evidence['id'], $fail_reasons, $evidence['fail_reason3'], true); 
                                echo '</td>';
                                echo '<td width="300px" style="padding: 5px">';
                                echo '<textarea name="rejection_comments_' . $evidence['id'] . '" id="rejection_comments_' . $evidence['id'] . '" rows="1" style="width: 100%;">' . $evidence['rejection_comments'] . '</textarea>';
                                echo '</td>';
                                echo '<td width="300px" style="padding: 5px">';
                                echo '<textarea name="recommendation_comments_' . $evidence['id'] . '" id="recommendation_comments_' . $evidence['id'] . '" rows="1" style="width: 100%;">' . $evidence['recommendation_comments'] . '</textarea>';
                                echo '</td>';
                                echo '<td width="200px" style="padding: 5px">';
                                echo HTML::selectChosen('recommendations_type_'.$evidence['id'], $recommendations_type, $evidence['recommendations_type'], true); 
                                echo '</td>';
                                echo '<td width="200px" style="padding: 5px">';
                                echo HTML::selectChosen('coach_actioned_status_'.$evidence['id'], $coach_actioned_status, $evidence['coach_actioned_status'], true); 
                                echo '</td>';
                            }
                            else
                            {

                                $accept_checked = ($evidence['iqa_accept']==1)?" checked ":"";
                                $reject_checked = ($evidence['iqa_reject']==1)?" checked ":"";
                                echo '<td align=center><input onclick="iqa_accept(this)" name="iqa_accept[]" type = checkbox ' . $accept_checked . '  disabled value = " ' . $evidence['id'] . '" ></td>';
                                echo '<td align=center><input onclick="iqa_reject(this)" name="iqa_reject[]" type = checkbox ' . $reject_checked . '  disabled value = " ' . $evidence['id'] . '" ></td>';

                                echo '<td width="200px" style="padding: 5px">';
                                echo HTML::selectChosen('reject_reason'.$evidence['id'], $iqa_reasons2, $evidence['reject_reason'], true, false, false);
                                echo '</td>';
                                if($evidence['coach_recommendations']==1)
                                    echo '<td align=center><input name="coach_recommendations_' . $evidence['id'] . '" checked type = checkbox value = " ' . $evidence['id'] . '" >';
                                else
                                    echo '<td align=center><input name="coach_recommendations_' . $evidence['id'] . '" type = checkbox value = " ' . $evidence['id'] . '" >';
                                //echo '<td width="200px" style="padding: 5px">';
                                //echo HTML::selectChosen('attempt'.$evidence['id'], $attempts, $evidence['first_sample'], true, false, false); 
                                //echo '</td>';
                                echo '<td width="200px" style="padding: 5px">';
                                echo HTML::selectChosen('fail_reason1_'.$evidence['id'], $fail_reasons, $evidence['fail_reason1'], true, false, false); 
                                echo '</td>';
                                echo '<td width="200px" style="padding: 5px">';
                                echo HTML::selectChosen('fail_reason2_'.$evidence['id'], $fail_reasons, $evidence['fail_reason2'], true, false, false); 
                                echo '</td>';
                                echo '<td width="200px" style="padding: 5px">';
                                echo HTML::selectChosen('fail_reason3_'.$evidence['id'], $fail_reasons, $evidence['fail_reason3'], true, false, false); 
                                echo '</td>';
                                echo '<td width="300px" style="padding: 5px">';
                                echo '<textarea name="rejection_comments_' . $evidence['id'] . '" id="rejection_comments_' . $evidence['id'] . '" rows="1" disabled style="width: 100%;">' . $evidence['rejection_comments'] . '</textarea>';
                                echo '</td>';
                                echo '<td width="300px" style="padding: 5px">';
                                echo '<textarea name="recommendation_comments_' . $evidence['id'] . '" id="recommendation_comments_' . $evidence['id'] . '" disabled rows="1" style="width: 100%;">' . $evidence['recommendation_comments'] . '</textarea>';
                                echo '</td>';
                                echo '<td width="200px" style="padding: 5px">';
                                echo HTML::selectChosen('recommendations_type_'.$evidence['id'], $recommendations_type, $evidence['recommendations_type'], true, false, false); 
                                echo '</td>';
                                echo '<td width="200px" style="padding: 5px">';
                                echo HTML::selectChosen('coach_actioned_status_'.$evidence['id'], $coach_actioned_status, $evidence['coach_actioned_status'], true, false, false); 
                                echo '</td>';
                            }
                        }
                        echo '</tr><tr bgcolor="' . $bgcolor . '">';
                    }
                    echo '</tr><tr bgcolor="' . $bgcolor . '">';
                }

                echo '</table></div>';
                ?>
            </div>

<div class="col-md-6">
    <div class="box box-primary">
        <div class="box-header with-border"><h2 class="box-title">Plan</h2>
           <div class="box-body">
                <div class="form-group">
                    <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">Assessor:</label>
                    <div class="col-sm-8">
                        <?php echo HTML::selectChosen('assessor', $assessor_ddl, $vo->assessor, true); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input_set_date" class="col-sm-4 control-label fieldLabel_optional">Set Date:</label>
                    <div class="col-sm-8">
                        <?php
                        echo HTML::datebox('set_date', $vo->set_date);
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input_due_date" class="col-sm-4 control-label fieldLabel_optional">Due Date:</label>
                    <div class="col-sm-8">
                        <?php echo HTML::datebox('due_date', $vo->due_date); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Submission Date:</label>
                    <div class="col-sm-8">
                        <?php
                        echo HTML::datebox('submission_date', $vo->submission_date);
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Marked Date:</label>
                    <div class="col-sm-8">
                        <?php
                        echo HTML::datebox('marked_date', $vo->marked_date);
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">IQA Sent Date:</label>
                    <div class="col-sm-8">
                        <?php
                        echo HTML::datebox('sent_iqa_date', $vo->sent_iqa_date);
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Assessor Signed Off:</label>
                    <div class="col-sm-8">
                        <?php
                        echo HTML::datebox('assessor_signed_off', $vo->assessor_signed_off);
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">IQA Rework Awaiting Marking:</label>
                    <div class="col-sm-8">
                        <?php
                        echo HTML::datebox('iqa_rework_awaiting_marking', $vo->iqa_rework_awaiting_marking);
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="iqa_recheck_date" class="col-sm-4 control-label fieldLabel_optional">IQA Recheck Date:</label>
                    <div class="col-sm-8">
                        <?php
                            echo HTML::datebox('iqa_recheck_date', $vo->iqa_recheck_date);
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Feedback Received Date:</label>
                    <div class="col-sm-8">
                        <?php
                        echo HTML::datebox('feedback_received_date', $vo->feedback_received_date);
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Completion Date:</label>
                    <div class="col-sm-8">
                        <?php
                        echo HTML::datebox('completion_date', $vo->completion_date);
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">System:</label>
                    <div class="col-sm-8">
                        <?php echo HTML::selectChosen('system', $system, $vo->system, true); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">Assessor Reject Reason:</label>
                    <div class="col-sm-8">
                        <?php echo HTML::selectChosen('assessor_reason', $assessor_reasons, $vo->assessor_reason, true); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-sm-4 control-label fieldLabel_optional">Comments:</label>
                    <div class="col-sm-8">
                        <textarea name="comments" id="comments" rows="10" style="width: 100%;"><?php echo $vo->comments; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if($vo->id == '') { ?>            
    <div class="box box-primary">
        <div class="box-header with-border"><h2 class="box-title">Other learners this plan to be created for</h2>
           <div class="box-body">
                <div class="form-group">
                    <div class="col-sm-8">
                        <?php 
                            $learners = DAO::getResultset($link, "select id, firstnames, surname from tr inner join courses_tr on courses_tr.tr_id = tr.id where tr.status_code = 1 and courses_tr.course_id = '$course_id' and tr.assessor = '$assessor_id' and tr.id != '$tr_id' order by firstnames, surname");
                            echo '<table class="resultset"><thead><tr><th style="padding: 5px">Include</th><th style="padding: 5px">Learner Name</th></tr></thead>';
                            foreach($learners as $learner)
                            {
                                echo '<tr><td align=center style="padding: 5px"><input name="other_learners[]" type = checkbox value = " ' . $learner[0] . '" ></td>';
                                echo '<td style="padding: 5px">' . $learner[1] . ' ' . $learner[2] . '</td></tr>';
                            }
                            echo '</table>';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<?php if(in_array($_SESSION['user']->username,Array("davmiller","aspence1","mthompson16","cthomas1","dmartindale","kpattisona","nellwood1","hcoatesa","kmalcolm16","bblackett1","lbaggot1","caddison1"))) { ?>
<div class="col-md-6">
    <div class="box box-primary">
        <div class="box-header with-border"><h2 class="box-title">IQA</h2>
            <div class="box-body">

                <div class="form-group">
                    <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">IQA Status:</label>
                    <div class="col-sm-8">
                        <?php echo HTML::selectChosen('iqa_status', $iqa_status_ddl, $vo->iqa_status, true); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Acc/ Rej Date:</label>
                    <div class="col-sm-8">
                        <?php echo HTML::datebox('acc_rej_date', $vo->acc_rej_date); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">IQA Reject Reason:</label>
                    <div class="col-sm-8">
                        <?php echo HTML::selectChosen('iqa_reason', $iqa_reasons, $vo->iqa_reason, true); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">First sample?:</label>
                    <div class="col-sm-8">
                        <?php echo HTML::selectChosen('attempt', $attempts, $vo->attempt, true); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-sm-4 control-label fieldLabel_optional">Feedback Summary:</label>
                    <div class="col-sm-8">
                        <textarea name="feedback_summary" id="feedback_summary" rows="10" style="width: 100%;"><?php echo $vo->feedback_summary; ?></textarea>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php } else { ?>
        <div class="col-md-6">
    <div class="box box-primary">
        <div class="box-header with-border"><h2 class="box-title">IQA</h2>
            <div class="box-body">

                <div class="form-group">
                    <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">IQA Status:</label>
                    <div class="col-sm-8">
                        <?php echo HTML::selectChosen('iqa_status', $iqa_status_ddl, $vo->iqa_status, true, false, false); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Acc/ Rej Date:</label>
                    <div class="col-sm-8">
                        <?php echo $vo->acc_rej_date; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">IQA Reject Reason:</label>
                    <div class="col-sm-8">
                        <?php echo HTML::selectChosen('iqa_reason', $iqa_reasons, $vo->iqa_reason, true, false, false); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">First sample?:</label>
                    <div class="col-sm-8">
                        <?php echo HTML::selectChosen('attempt', $attempts, $vo->attempt, true, false, false); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-sm-4 control-label fieldLabel_optional">Feedback Summary:</label>
                    <div class="col-sm-8">
                        <textarea readonly name="feedback_summary" id="feedback_summary" rows="10" style="width: 100%;"><?php echo $vo->feedback_summary; ?></textarea>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php } ?>
    <?php if(DB_NAME!="am_baltic_demo" or DB_NAME!="am_baltic") { ?>

    <?php if(in_array($_SESSION['user']->username,Array("davmiller","aspence1","mthompson16","cthomas1","dmartindale","kpattisona","nellwood1","hcoatesa","kmalcolm16","bblackett1","lbaggot1","caddison1"))) { ?>
    <div class="box box-primary">
        <div class="box-header with-border"><h2 class="box-title">Summative</h2>
            <div class="box-body">

                <div class="form-group">
                    <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">IQA Status:</label>
                    <div class="col-sm-8">
                        <?php echo HTML::selectChosen('iqa_person', $iqa_dropdown, $vo->iqa_person, true); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Date:</label>
                    <div class="col-sm-8">
                        <?php echo HTML::datebox('summative_date', $vo->summative_date); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">RAG:</label>
                    <div class="col-sm-8">
                        <?php echo HTML::selectChosen('rag', $rags, $vo->rag, true); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-sm-4 control-label fieldLabel_optional">IQA Feedback:</label>
                    <div class="col-sm-8">
                        <textarea name="iqa_feedback" id="iqa_feedback" rows="10" style="width: 100%;"><?php echo $vo->iqa_feedback; ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">Actioned:</label>
                    <div class="col-sm-8">
                        <?php echo HTML::selectChosen('actioned', $attempts, $vo->actioned, true); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Date Actioned:</label>
                    <div class="col-sm-8">
                        <?php echo HTML::datebox('summative_date_actioned', $vo->summative_date_actioned); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-sm-4 control-label fieldLabel_optional">LM Comments:</label>
                    <div class="col-sm-8">
                        <textarea name="lm_comments" id="lm_comments" rows="10" style="width: 100%;"><?php echo $vo->lm_comments; ?></textarea>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php } else { ?>

    <div class="box box-primary">
        <div class="box-header with-border"><h2 class="box-title">Summative</h2>
            <div class="box-body">

                <div class="form-group">
                    <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">IQA Status:</label>
                    <div class="col-sm-8">
                        <?php echo HTML::selectChosen('iqa_person', $iqa_dropdown, $vo->iqa_person, true, false, false); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Date:</label>
                    <div class="col-sm-8">
                        <?php echo $vo->summative_date; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">RAG:</label>
                    <div class="col-sm-8">
                        <?php echo HTML::selectChosen('rag', $rags, $vo->rag, true, false, false); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-sm-4 control-label fieldLabel_optional">IQA Feedback:</label>
                    <div class="col-sm-8">
                        <textarea disabled name="iqa_feedback" id="iqa_feedback" rows="10" style="width: 100%;"><?php echo $vo->iqa_feedback; ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">Actioned:</label>
                    <div class="col-sm-8">
                        <?php echo HTML::selectChosen('actioned', $attempts, $vo->actioned, true, false, false); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Date Actioned:</label>
                    <div class="col-sm-8">
                        <?php echo $vo->summative_date_actioned; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-sm-4 control-label fieldLabel_optional">LM Comments:</label>
                    <div class="col-sm-8">
                        <textarea disabled name="lm_comments" id="lm_comments" rows="10" style="width: 100%;"><?php echo $vo->lm_comments; ?></textarea>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php } ?>
<?php } ?>
</form>
</div>
<div id="dialogDeleteFile" style="display:none" title="Delete file"></div>
<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker.js"></script>

<script language="JavaScript">

window.setInterval(function()
{ 
    alert("Please click to save your work");
    var myForm = document.forms["form1"];
    if(validateForm(myForm) == false)
    {
        return false;
    }
    myForm.submit(); 
}, 1800000);

    $(function() {

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy'
        });

        $('#input_actual_date').attr('class', 'datepicker optional form-control');
        $('#input_due_date').attr('class', 'datepicker optional form-control');

        $(".timebox").timepicker({ timeFormat: 'H:i' });

        $('.timebox').bind('timeFormatError timeRangeError', function() {
            this.value = '';
            alert("Please choose a valid time");
            this.focus();
        });
    });

    function save()
    {
        var myForm = document.forms["form1"];
        if(validateForm(myForm) == false)
        {
            return false;
        }
        myForm.submit();
    }

    function delete_record(record_id, tr_id, plan_id)
    {
        if(!confirm('This action cannot be undone, are you sure you want to delete this record?'))
            return;
        var client = ajaxRequest('do.php?_action=edit_evidence_matrix_submission&ajax_request=true&submission_id='+ encodeURIComponent(record_id)+'&tr_id='+encodeURIComponent(tr_id)+'&project_id='+encodeURIComponent(plan_id));
        //alert(client.responseText);
        //window.history.back();
        window.location.href='do.php?_action=read_training_record&id='+tr_id;
    }

    function iqa_accept(accept)
    {
        /*if(accept.checked == true)
        {
            $("input[name='iqa_reject[]']").each( function () 
                {
                    if($(this).attr('value')==include_value)
                    {
                        $(this).prop('checked',false);
                    }    
                });
        }*/
        $("input[name='matrix[]']").each( function () 
        {
            if($(this).attr('checked'))
            {
                include_value = ($(this).attr('value'));

                $("input[name='iqa_accept[]']").each( function () 
                {
                    if($(this).attr('value')==include_value)
                    {
                        if(accept.checked == true)
                            $(this).prop('checked',true);
                        else
                            $(this).prop('checked',false);
                    }    
                });
            }    
        });
    }

    function iqa_reject(accept)
    {
        $("input[name='matrix[]']").each( function () 
        {
            if($(this).attr('checked'))
            {
                include_value = ($(this).attr('value'));

                $("input[name='iqa_reject[]']").each( function () 
                {
                    if($(this).attr('value')==include_value)
                    {
                        if(accept.checked == true)
                            $(this).prop('checked',true);
                        else
                            $(this).prop('checked',false);
                    }    
                });
            }    
        });
    }



    function confirmation(question) {
    var defer = $.Deferred();
    $('<div></div>')
            .html(question)
            .dialog({
                autoOpen: true,
                modal: true,
                title: 'Confirmation',
                buttons: {
                    "Yes": function () {
                        defer.resolve("true");//this text 'true' can be anything. But for this usage, it should be true or false.
                        $(this).dialog("close");
                    },
                    "No": function () {
                        defer.resolve("false");//this text 'false' can be anything. But for this usage, it should be true or false.
                        $(this).dialog("close");
                    }
                },
                close: function () {
                    //$(this).remove();
                    $(this).dialog('destroy').remove()
                }
            });
    return defer.promise();
};


</script>

</body>
</html>