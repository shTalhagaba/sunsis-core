
<div class="row">
    <div class="col-sm-12">
        <div class="well well-sm"><p>Please list your educational prior attainment and include your maths, english, ICT, or any other engineering related qualifications.</p></div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive" style="max-height: 700px; overflow-y: scroll;">
            <table class="table row-border cw-table-list">
                <tr><th style="width: 25%;">GCSE/A/AS Level</th><th style="width: 25%;">Subject</th><th style="width: 15%;">Predicted Grade</th><th style="width: 15%;">Actual Grade</th><th style="width: 20%;">Date Completed</th></tr>
                <tbody>
                <tr><?php $ob_eng = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE ob_learner_id = '{$ob_learner->id}' AND level = '101'");?>
                    <td>GCSE <input type="hidden" name="gcse_english_level" value="101" /></td>
                    <td>English Language<input type="hidden" name="gcse_english_subject" value="English" /></td>
                    <td>
                        <?php $qual_grades = DAO::getResultset($link,"SELECT id, description, NULL FROM lookup_gcse_grades WHERE description NOT IN ('Credit', 'Distinction*') ORDER BY id;", DAO::FETCH_NUM);
                        echo HTML::selectChosen('gcse_english_grade_predicted', $qual_grades, isset($ob_eng->p_grade)?$ob_eng->p_grade:'', true, true, true);
                        ?>
                    </td>
                    <td><?php echo HTML::selectChosen('gcse_english_grade_actual', $qual_grades, isset($ob_eng->a_grade)?$ob_eng->a_grade:'', true, true, true); ?></td>
                    <td><input class="datecontrol compulsory form-control" type="text" name="gcse_english_date_completed" id="input_gcse_english_date_completed" value="<?php echo isset($ob_eng->date_completed)?Date::toShort($ob_eng->date_completed):''; ?>" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td>
                </tr>
                <tr>
                    <?php
                    $ob_maths = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE ob_learner_id = '{$ob_learner->id}' AND level = '102'");
                    ?>
                    <td>GCSE <input type="hidden" name="gcse_maths_level" value="102" /></td>
                    <td>Maths<input type="hidden" name="gcse_maths_subject" value="Maths" /></td>
                    <td><?php echo HTML::selectChosen('gcse_maths_grade_predicted', $qual_grades, isset($ob_maths->p_grade)?$ob_maths->p_grade:'', true, true, true); ?></td>
                    <td><?php echo HTML::selectChosen('gcse_maths_grade_actual', $qual_grades, isset($ob_maths->a_grade)?$ob_maths->a_grade:'', true, true, true); ?></td>
                    <td><input class="datecontrol compulsory form-control" type="text" name="gcse_maths_date_completed" id="input_gcse_maths_date_completed" value="<?php echo isset($ob_maths->date_completed)?Date::toShort($ob_maths->date_completed):''; ?>" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td>
                </tr>
                <?php
                for($i = 1; $i <= 7; $i++)
                {
                    $ob_q = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE ob_learner_id = '{$ob_learner->id}' AND q_type = '{$i}'");
                    echo '<tr>';
                    echo '<td>' . HTML::selectChosen('level'.$i, $QualLevelsDDL, isset($ob_q->level)?$ob_q->level:'', true, false, true) . '</td>';
                    if(isset($ob_q->subject))
                        echo '<td><input class="form-control compulsory" type="text" name="subject'.$i.'" id="subject'.$i.'" value="' . $ob_q->subject . '" /></td>';
                    else
                        echo '<td><input class="form-control compulsory" type="text" name="subject'.$i.'" id="subject'.$i.'" value="" /></td>';
                    echo '<td>' . HTML::selectChosen('predicted_grade'.$i, $qual_grades, isset($ob_q->p_grade)?$ob_q->p_grade:'', true, false, true) . '</td>';
                    echo '<td>' . HTML::selectChosen('actual_grade'.$i, $qual_grades, isset($ob_q->a_grade)?$ob_q->a_grade:'', true, false, true) . '</td>';
                    if(isset($ob_q->date_completed))
                        echo '<td><input class="datecontrol compulsory form-control" type="text" name="date_completed'.$i.'" id="input_date_completed'.$i.'" value="'.Date::toShort($ob_q->date_completed).'" size="10" maxlength="10" placeholder="dd/mm/yyyy" /><input class="form-control optional" type="hidden" name="q_type'.$i.'" id="q_type'.$i.'" value="'.$i.'" /></td>';
                    else
                        echo '<td><input class="datecontrol compulsory form-control" type="text" name="date_completed'.$i.'" id="input_date_completed'.$i.'" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy" /><input class="form-control optional" type="hidden" name="q_type'.$i.'" id="q_type'.$i.'" value="'.$i.'" /></td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="callout">
            <label>Prior Attainment Level</label>
            <p>
                <i class="text-muted">Please use the <span style="margin-top: 2px;" class="btn btn-info btn-xs" onclick="window.open('PriorAttainmentGuidance2018_19.pdf', '_blank')"><i class="fa fa-info-circle"></i> Guidance Notes</span>
                    to let us know the overall level of prior attainment of your qualifications achieved to date.<br>For example,</i>
            </p>
            <ul style="margin-left: 25px;">
                <li><i class="text-muted">if you have 4 GCSE's with Grades A - C, this would fall into Level 1</i></li>
                <li><i class="text-muted">if you have 5 GCSE's with Grades A - C, this would fall into Level 2</i></li>
            </ul>
            <p class="text-bold">I consider my Prior Attainment Level to be:
                <?php
                $ob_high = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE ob_learner_id = '{$ob_learner->id}' AND q_type = 'h'");
                echo isset($ob_high->level) ?
                    HTML::selectChosen('high_level', $PriorAttainDDL, $ob_high->level, true, false, true) :
                    HTML::selectChosen('high_level', $PriorAttainDDL, '', true, false, true);

                ?>
            </p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="callout">
            <label>Certificates</label>
            <p><input type="file" class="form-control optional" name="file1" id="file1" value=""  /></p>
            <p><input type="file" class="form-control optional" name="file2" id="file2" value=""  /></p>
            <p><input type="file" class="form-control optional" name="file3" id="file3" value=""  /></p>
            <p><input type="file" class="form-control optional" name="file4" id="file4" value=""  /></p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <p><br></p>
        <p><input class="clsICheck" type="checkbox" name="pa_agree" /><label>Please click to indicate you understand that, if relevant, I need to progress onto Level 2 Functional Skills exams once I have passed Level 1.</label></p>
        <p><br></p>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="callout callout-info">
            <p><span class="fa fa-info-circle"></span> You should bring copies / or originals of your certificates for induction meeting</p>
            <p><span class="fa fa-info-circle"></span> If you did not provide copy certificates during the screening process to show you are exempt from Functional Skills Maths or English, you will be enrolled onto Functional Skills, however, if you can supply the certificates today or within one month, we will exempt you from these qualifications</p>
            <p><span class="fa fa-info-circle"></span> You have the option to request replacement certificates from the Awarding Organisation at your own cost.</p>
        </div>
    </div>
</div>
