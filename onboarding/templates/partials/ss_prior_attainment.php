<div class="row">
    <div class="col-sm-12">
        <div class="well well-sm">
            <p>
                <span class="text-bold">
                    Please detail all qualifications fully or partly achieved, including any apprenticeships you may have completed or part completed, even if it is not related to the apprenticeship you are applying for.
                </span>
                (Include all qualifications that may be related to this apprenticeship, including Maths, English, ICT, Digital, Health & Safety, Manual Handling, etc.)
            </p>
        </div>
        <div style="max-height: 600px; overflow-y: scroll;">
            <table class="table table-responsive row-border cw-table-list">
                <tr><th style="width: 25%;">GCSE/A/AS Level</th><th style="width: 25%;">Subject</th><th style="width: 15%;">Predicted Grade</th><th style="width: 15%;">Actual Grade</th><th style="width: 20%;">Date Completed</th></tr>
                <tbody>
                <tr>
                    <?php $ob_eng = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND level = '101'");?>
                    <td>GCSE <input type="hidden" name="gcse_english_level" value="101" /></td>
                    <td>English Language<input type="hidden" name="gcse_english_subject" value="English" /></td>
                    <td>
                        <?php $qual_grades = DAO::getResultset($link,"SELECT id, description, NULL FROM lookup_gcse_grades WHERE description NOT IN ('Credit', 'Distinction*') ORDER BY id;", DAO::FETCH_NUM);
                        echo HTML::selectChosen('gcse_english_grade_predicted', $qual_grades, isset($ob_eng->p_grade)?$ob_eng->p_grade:'', true, true, true);
                        ?>
                    </td>
                    <td><?php echo HTML::selectChosen('gcse_english_grade_actual', $qual_grades, isset($ob_eng->a_grade)?$ob_eng->a_grade:'', true, true, true); ?></td>
                    <td><input class="datecontrol  form-control" type="text" name="gcse_english_date_completed" id="input_gcse_english_date_completed" value="<?php echo isset($ob_eng->date_completed)?Date::toShort($ob_eng->date_completed):''; ?>" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td>
                </tr>
                <tr>
                    <?php $ob_maths = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND level = '102'");?>
                    <td>GCSE <input type="hidden" name="gcse_maths_level" value="102" /></td>
                    <td>Maths<input type="hidden" name="gcse_maths_subject" value="Maths" /></td>
                    <td><?php echo HTML::selectChosen('gcse_maths_grade_predicted', $qual_grades, isset($ob_maths->p_grade)?$ob_maths->p_grade:'', true, true, true); ?></td>
                    <td><?php echo HTML::selectChosen('gcse_maths_grade_actual', $qual_grades, isset($ob_maths->a_grade)?$ob_maths->a_grade:'', true, true, true); ?></td>
                    <td><input class="datecontrol  form-control" type="text" name="gcse_maths_date_completed" id="input_gcse_maths_date_completed" value="<?php echo isset($ob_maths->date_completed)?Date::toShort($ob_maths->date_completed):''; ?>" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td>
                </tr>
                <tr>
                    <?php $ob_ict = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND level = '103'");?>
                    <td>GCSE <input type="hidden" name="gcse_ict_level" value="103" /></td>
                    <td>ICT<input type="hidden" name="gcse_ict_subject" value="ICT" /></td>
                    <td><?php echo HTML::selectChosen('gcse_ict_grade_predicted', $qual_grades, isset($ob_ict->p_grade)?$ob_ict->p_grade:'', true, false, true); ?></td>
                    <td><?php echo HTML::selectChosen('gcse_ict_grade_actual', $qual_grades, isset($ob_ict->a_grade)?$ob_ict->a_grade:'', true, false, true); ?></td>
                    <td><input class="datecontrol  form-control" type="text" name="gcse_ict_date_completed" id="input_gcse_ict_date_completed" value="<?php echo isset($ob_ict->date_completed)?Date::toShort($ob_ict->date_completed):''; ?>" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td>
                </tr>
                <?php
                if(DB_NAME != "am_ela")
                {
                    for($i = 1; $i <= 15; $i++)
                    {
                        $ob_q = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND q_type = '{$i}'");
                        echo '<tr>';
                        echo '<td>' . HTML::selectChosen('level'.$i, $QualLevelsDDL, isset($ob_q->level)?$ob_q->level:'', true, false, true) . '</td>';
                        if(isset($ob_q->subject))
                            echo '<td><input class="form-control " type="text" name="subject'.$i.'" id="subject'.$i.'" value="' . $ob_q->subject . '" /></td>';
                        else
                            echo '<td><input class="form-control " type="text" name="subject'.$i.'" id="subject'.$i.'" value="" /></td>';
                        echo '<td>' . HTML::selectChosen('predicted_grade'.$i, $qual_grades, isset($ob_q->p_grade)?$ob_q->p_grade:'', true, false, true) . '</td>';
                        echo '<td>' . HTML::selectChosen('actual_grade'.$i, $qual_grades, isset($ob_q->a_grade)?$ob_q->a_grade:'', true, false, true) . '</td>';
                        if(isset($ob_q->date_completed))
                            echo '<td><input class="datecontrol  form-control" type="text" name="date_completed'.$i.'" id="input_date_completed'.$i.'" value="'.Date::toShort($ob_q->date_completed).'" size="10" maxlength="10" placeholder="dd/mm/yyyy" /><input class="form-control optional" type="hidden" name="q_type'.$i.'" id="q_type'.$i.'" value="'.$i.'" /></td>';
                        else
                            echo '<td><input class="datecontrol  form-control" type="text" name="date_completed'.$i.'" id="input_date_completed'.$i.'" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy" /><input class="form-control optional" type="hidden" name="q_type'.$i.'" id="q_type'.$i.'" value="'.$i.'" /></td>';
                        echo '</tr>';
                    }
                }
                ?>
            </table>
        </div>

        <table class="table table-responsive row-border cw-table-list">
            <tr style="background-color: #e0ffff;">
                <td colspan="5">
                    <label>Prior Attainment Level</label>
                    <p>
                        <i class="text-muted">Please use the <span style="margin-top: 2px;" class="btn btn-info btn-sm" onclick="window.open('PriorAttainmentGuidance2018_19.pdf', '_blank')"><i class="fa fa-info-circle"></i> Guidance Notes</span>
                            to let us know the overall level of prior attainment of your qualifications achieved to date.<br>For example,</i>
                    </p>
                    <ul style="margin-left: 25px;">
                        <li><i class="text-muted">if you have 4 GCSE's with Grades A - C, this would fall into Level 1</i></li>
                        <li><i class="text-muted">if you have 5 GCSE's with Grades A - C, this would fall into Level 2</i></li>
                    </ul>
                </td>
            </tr>
            <tr style="background-color: #e0ffff;">
                <?php $ob_high = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND q_type = 'h'"); ?>
                <th colspan="1" align="right">
                    I consider my Prior Attainment Level to be *
                </th>
                <td colspan="4" align="left"><?php echo HTML::selectChosen('high_level', $PriorAttainDDL, isset($ob_high->level)?$ob_high->level:'', true, false, true);?></td>
            </tr>
        </table>
		<hr>
		<?php if($ageAtStart >= 19 && !$tr->isNonApp($link)) { ?>
        <div class="table-responsive">
            <table class="table row-border cw-table-list">
                <col width="50%" />
                <col width="50%" />
                <tr>
                    <th colspan="2" align="center">
                        Functional skills declaration and waiver<br>
                        Please indicate your decision regarding the functional skills programme.
                    </th>
                </tr>
                <tr>
                    <td colspan="2">
                        <span class="text-info">
                            <i class="fa fa-info-circle"></i>  
                            Apprentices aged 19 and over no longer have a mandatory requirement to study towards and achieve
                             Functional Skills (english and maths) qualifications.
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>
                        Do you and your employer agree that you will complete Functional Skills English training and take the required assessment?
                    </th>
                    <td>
                        <?php echo HTML::selectChosen('fs_eng_opt_in', [['Yes', 'We have agreed that I will opt in'], ['No', 'We have agreed that I am opting out']], isset($tr->fs_eng_opt_in)?$tr->fs_eng_opt_in:'', true, true, true);?>
                    </td>
                </tr>
                <tr style="display: none;" id="tr_eng_opt_out_reason">
                    <th>
                        Please provide reason for your functional skills ENGLISH decision.
                    </th>
                    <td>
                        <textarea class="form-control" name="fs_eng_opt_out_reason" id="fs_eng_opt_out_reason" rows="3" maxlength="255"><?php echo $tr->fs_eng_opt_out_reason; ?></textarea>
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
                <tr style="display: none;" id="tr_maths_opt_out_reason">
                    <th>
                        Please provide reason for your functional skills MATHS decision.
                    </th>
                    <td>
                        <textarea class="form-control" name="fs_maths_opt_out_reason" id="fs_maths_opt_out_reason" rows="3" maxlength="255"><?php echo $tr->fs_maths_opt_out_reason; ?></textarea>
                    </td>
                </tr>
            </table>
        </div>
        <?php } ?>
		
    </div>
</div>