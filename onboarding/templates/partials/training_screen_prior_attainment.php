<?php
$QualLevelsDDL = DAO::getResultset($link, "SELECT DISTINCT id, description, NULL FROM lookup_ob_qual_levels ORDER BY id;");
$PriorAttainDDL = DAO::getResultset($link, "SELECT DISTINCT code, CONCAT(description), NULL FROM central.lookup_prior_attainment WHERE code NOT IN ('101', '102') ORDER BY sorting;");
$ddlPreAssessment = DAO::getResultset($link, "SELECT id, description, null FROM lookup_pre_assessment;");
?>

<form class="form-horizontal" name="frmPriorAttainment" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />
    <input type="hidden" name="_action" value="save_prior_attainment" />
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h2 class="box-title">Details</h2>
                    <div class="pull-right box-tools">
                        <?php if (! $tr->isArchived()) { ?>
                            <span class="btn btn-sm btn-primary" onclick="save_prior_attainment();">
                                <i class="fa fa-save"></i> Save Prior Attainment
                            </span>
                        <?php } ?>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="high_level" class="control-label fieldLabel_compulsory">Highest Prior Attainment Level: </label>
                                <?php $ob_high = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND q_type = 'h'"); ?>
                                <?php echo HTML::selectChosen('high_level', $PriorAttainDDL, isset($ob_high->level) ? $ob_high->level : '', true, true, true); ?>
                            </div>
                            <div class="form-group">
                                <label for="prior_edu_checked" class="control-label fieldLabel_optional">PLR / Certificates Checked: </label> &nbsp;
                                <input style="transform: scale(1.4);" type="checkbox" name="prior_edu_checked" id="prior_edu_checked" value="1" <?php echo $tr->prior_edu_checked == 1 ? 'checked' : ''; ?> />
                            </div>
                        </div>
                        <div class="col-sm-1"></div>
                        <div class="col-sm-4">
                            <div class="form-group callout callout-default">
                                <label for="literacy" class="control-label fieldLabel_optional">Initial Assessment - Literacy: </label>
                                <?php echo HTML::selectChosen('literacy', $ddlPreAssessment, $tr->literacy, true); ?>
                                <?php if (in_array(DB_NAME, ["am_ela", "am_eet"])) { ?>
                                    <br><input class="form-control" type="text" name="literacy_other" id="literacy_other" value="<?php echo $tr->literacy_other; ?>" maxlength="50" placeholder="Other literacy grade" />
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group callout callout-default">
                                <label for="numeracy" class="control-label fieldLabel_optional">Initial Assessment - Numeracy: </label>
                                <?php echo HTML::selectChosen('numeracy', $ddlPreAssessment, $tr->numeracy, true); ?>
                                <?php if (in_array(DB_NAME, ["am_ela", "am_eet"])) { ?>
                                    <br><input class="form-control" type="text" name="numeracy_other" id="numeracy_other" value="<?php echo $tr->numeracy_other; ?>" maxlength="50" placeholder="Other numeracy grade" />
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group callout callout-default">
                                <label for="literacy" class="control-label fieldLabel_optional">Diagnostic Assessment - Literacy: </label>
                                <?php echo HTML::selectChosen('literacy_diagnostic', $ddlPreAssessment, $tr->literacy_diagnostic, true); ?>
                                <?php if (in_array(DB_NAME, ["am_ela", "am_eet"])) { ?>
                                    <br><input class="form-control" type="text" name="literacy_diagnostic_other" id="literacy_diagnostic_other" value="<?php echo $tr->literacy_diagnostic_other; ?>" maxlength="50" placeholder="Other literacy diagnostic grade" />
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group callout callout-default">
                                <label for="numeracy_diagnostic" class="control-label fieldLabel_optional">Diagnostic Assessment - Numeracy: </label>
                                <?php echo HTML::selectChosen('numeracy_diagnostic', $ddlPreAssessment, $tr->numeracy_diagnostic, true); ?>
                                <?php if (in_array(DB_NAME, ["am_ela", "am_eet"])) { ?>
                                    <br><input class="form-control" type="text" name="numeracy_diagnostic_other" id="numeracy_diagnostic_other" value="<?php echo $tr->numeracy_diagnostic_other; ?>" maxlength="50" placeholder="Other numeracy diagnostic grade" />
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-1"></div>
                        <div class="col-sm-4">
                            <div class="form-group callout callout-default">
                                <label for="ict" class="control-label fieldLabel_optional">Initial Assessment - ICT: </label>
                                <?php echo HTML::selectChosen('ict', $ddlPreAssessment, $tr->ict, true); ?>
                                <?php if (in_array(DB_NAME, ["am_ela", "am_eet"])) { ?>
                                    <br><input class="form-control" type="text" name="ict_other" id="ict_other" value="<?php echo $tr->ict_other; ?>" maxlength="50" placeholder="Other ICT grade" />
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group callout callout-default">
                                <label for="esol" class="control-label fieldLabel_optional">Initial Assessment - ESOL: </label>
                                <?php echo HTML::selectChosen('esol', $ddlPreAssessment, $tr->esol, true); ?>
                                <?php if (in_array(DB_NAME, ["am_ela", "am_eet"])) { ?>
                                    <br><input class="form-control" type="text" name="esol_other" id="esol_other" value="<?php echo $tr->esol_other; ?>" maxlength="50" placeholder="Other ESOL grade" />
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <?php if($ageAtStart >= 19 && $tr->practical_period_start_date >= '2025-04-01' && !$tr->isNonApp($link)) { ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered">
                                <col width="60%" />
                                <col width="40%" />
                                <thead>
                                    <tr>
                                        <th colspan="2">
                                            Functional skills declaration and waiver<br>
                                            Learner's decision regarding the functional skills programme<br>
                                            <span class="text-info">
                                                <i class="fa fa-info-circle"></i> 
                                                Apprentices aged 19 and over no longer have a mandatory requirement to study towards and achieve Functional Skills (english and maths) qualifications.
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>
                                            Do you and your employer agree that you will complete Functional Skills English training and take the required assessment?
                                        </th>
                                        <td>
                                            <?php echo HTML::selectChosen('fs_eng_opt_in', [['Yes', 'We have agreed that I will opt in'], ['No', 'We have agreed that I am opting out']], isset($tr->fs_eng_opt_in)?$tr->fs_eng_opt_in:'', true, true, true);?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Do you and your employer agree that you will complete Functional Skills Maths training and take the required assessment?
                                        </th>
                                        <td>
                                            <?php echo HTML::selectChosen('fs_maths_opt_in', [['Yes', 'We have agreed that I will opt in'], ['No', 'We have agreed that I am opting out']], isset($tr->fs_maths_opt_in)?$tr->fs_maths_opt_in:'', true, true, true);?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-responsive row-border cw-table-list">
                                <tr>
                                    <th style="width: 25%;">GCSE/A/AS Level</th>
                                    <th style="width: 25%;">Subject</th>
                                    <th style="width: 15%;">Predicted Grade</th>
                                    <th style="width: 15%;">Actual Grade</th>
                                    <th style="width: 20%;">Date Completed</th>
                                </tr>
                                <tbody>
                                    <tr>
                                        <?php $ob_eng = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND level = '101'"); ?>
                                        <td>GCSE <input type="hidden" name="gcse_english_level" value="101" /></td>
                                        <td>English Language<input type="hidden" name="gcse_english_subject" value="English" /></td>
                                        <td>
                                            <?php $qual_grades = DAO::getResultset($link, "SELECT id, description, NULL FROM lookup_gcse_grades WHERE description NOT IN ('Credit', 'Distinction*') ORDER BY id;", DAO::FETCH_NUM);
                                            echo HTML::selectChosen('gcse_english_grade_predicted', $qual_grades, isset($ob_eng->p_grade) ? $ob_eng->p_grade : '', true, false, true);
                                            ?>
                                        </td>
                                        <td><?php echo HTML::selectChosen('gcse_english_grade_actual', $qual_grades, isset($ob_eng->a_grade) ? $ob_eng->a_grade : '', true, false, true); ?></td>
                                        <td><input class="datecontrol  form-control" type="text" name="gcse_english_date_completed" id="input_gcse_english_date_completed" value="<?php echo isset($ob_eng->date_completed) ? Date::toShort($ob_eng->date_completed) : ''; ?>" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td>
                                    </tr>
                                    <tr>
                                        <?php $ob_maths = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND level = '102'"); ?>
                                        <td>GCSE <input type="hidden" name="gcse_maths_level" value="102" /></td>
                                        <td>Maths<input type="hidden" name="gcse_maths_subject" value="Maths" /></td>
                                        <td><?php echo HTML::selectChosen('gcse_maths_grade_predicted', $qual_grades, isset($ob_maths->p_grade) ? $ob_maths->p_grade : '', true, false, true); ?></td>
                                        <td><?php echo HTML::selectChosen('gcse_maths_grade_actual', $qual_grades, isset($ob_maths->a_grade) ? $ob_maths->a_grade : '', true, false, true); ?></td>
                                        <td><input class="datecontrol  form-control" type="text" name="gcse_maths_date_completed" id="input_gcse_maths_date_completed" value="<?php echo isset($ob_maths->date_completed) ? Date::toShort($ob_maths->date_completed) : ''; ?>" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td>
                                    </tr>
                                    <tr>
                                        <?php $ob_ict = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND level = '103'"); ?>
                                        <td>GCSE <input type="hidden" name="gcse_ict_level" value="103" /></td>
                                        <td>ICT<input type="hidden" name="gcse_ict_subject" value="ICT" /></td>
                                        <td><?php echo HTML::selectChosen('gcse_ict_grade_predicted', $qual_grades, isset($ob_ict->p_grade) ? $ob_ict->p_grade : '', true, false, true); ?></td>
                                        <td><?php echo HTML::selectChosen('gcse_ict_grade_actual', $qual_grades, isset($ob_ict->a_grade) ? $ob_ict->a_grade : '', true, false, true); ?></td>
                                        <td><input class="datecontrol  form-control" type="text" name="gcse_ict_date_completed" id="input_gcse_ict_date_completed" value="<?php echo isset($ob_ict->date_completed) ? Date::toShort($ob_ict->date_completed) : ''; ?>" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td>
                                    </tr>
                                    <?php
                                    if (!in_array(DB_NAME, ["am_ela", "am_eet"])) {
                                        $result = DAO::getResultset($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND q_type BETWEEN 1 AND 15", DAO::FETCH_ASSOC);
                                        $i = 0;
                                        foreach ($result as $row) {
                                            $i++;
                                            echo '<tr>';
                                            echo '<td>' . HTML::selectChosen('level' . $i, $QualLevelsDDL, $row['level'], true, false, true) . '</td>';
                                            echo '<td><input class="form-control " type="text" name="subject' . $i . '" id="subject' . $i . '" value="' . $row['subject'] . '" /></td>';
                                            echo '<td>' . HTML::selectChosen('predicted_grade' . $i, $qual_grades, $row['p_grade'], true, false, true) . '</td>';
                                            echo '<td>' . HTML::selectChosen('actual_grade' . $i, $qual_grades, $row['a_grade'], true, false, true) . '</td>';
                                            echo '<td><input class="datecontrol  form-control" type="text" name="date_completed' . $i . '" id="input_date_completed' . $i . '" value="' . Date::toShort($row['date_completed']) . '" size="10" maxlength="10" placeholder="dd/mm/yyyy" />';
                                            echo '<input class="form-control optional" type="hidden" name="q_type' . $i . '" id="q_type' . $i . '" value="' . $i . '" /></td>';
                                            echo '</tr>';
                                        }
                                        for ($i = $i + 1; $i <= 15; $i++) {
                                            echo '<tr>';
                                            echo '<td>' . HTML::selectChosen('level' . $i, $QualLevelsDDL, '', true, false, true) . '</td>';
                                            echo '<td><input class="form-control " type="text" name="subject' . $i . '" id="subject' . $i . '" /></td>';
                                            echo '<td>' . HTML::selectChosen('predicted_grade' . $i, $qual_grades, '', true, false, true) . '</td>';
                                            echo '<td>' . HTML::selectChosen('actual_grade' . $i, $qual_grades, '', true, false, true) . '</td>';
                                            echo '<td><input class="datecontrol  form-control" type="text" name="date_completed' . $i . '" id="input_date_completed' . $i . '" size="10" maxlength="10" placeholder="dd/mm/yyyy" /><input class="form-control optional" type="hidden" name="q_type' . $i . '" id="q_type' . $i . '" value="' . $i . '" /></td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        $result = DAO::getResultset($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND q_type BETWEEN 1 AND 15", DAO::FETCH_ASSOC);
                                        $i = 0;
                                        foreach ($result as $row) {
                                            $i++;
                                            echo '<tr>';
                                            echo '<td>' . HTML::selectChosen('level' . $i, $QualLevelsDDL, $row['level'], true, false, true) . '</td>';
                                            echo '<td><input class="form-control " type="text" name="subject' . $i . '" id="subject' . $i . '" value="' . $row['subject'] . '" /></td>';
                                            echo '<td>' . HTML::selectChosen('predicted_grade' . $i, $qual_grades, $row['p_grade'], true, false, true) . '</td>';
                                            echo '<td>' . HTML::selectChosen('actual_grade' . $i, $qual_grades, $row['a_grade'], true, false, true) . '</td>';
                                            echo '<td><input class="datecontrol  form-control" type="text" name="date_completed' . $i . '" id="input_date_completed' . $i . '" value="' . Date::toShort($row['date_completed']) . '" size="10" maxlength="10" placeholder="dd/mm/yyyy" />';
                                            echo '<input class="form-control optional" type="hidden" name="q_type' . $i . '" id="q_type' . $i . '" value="' . $i . '" /></td>';
                                            echo '</tr>';
                                        }
                                        for ($i = $i + 1; $i <= 2; $i++) {
                                            echo '<tr>';
                                            echo '<td>' . HTML::selectChosen('level' . $i, $QualLevelsDDL, '', true, false, true) . '</td>';
                                            echo '<td><input class="form-control " type="text" name="subject' . $i . '" id="subject' . $i . '" /></td>';
                                            echo '<td>' . HTML::selectChosen('predicted_grade' . $i, $qual_grades, '', true, false, true) . '</td>';
                                            echo '<td>' . HTML::selectChosen('actual_grade' . $i, $qual_grades, '', true, false, true) . '</td>';
                                            echo '<td><input class="datecontrol  form-control" type="text" name="date_completed' . $i . '" id="input_date_completed' . $i . '" size="10" maxlength="10" placeholder="dd/mm/yyyy" /><input class="form-control optional" type="hidden" name="q_type' . $i . '" id="q_type' . $i . '" value="' . $i . '" /></td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>