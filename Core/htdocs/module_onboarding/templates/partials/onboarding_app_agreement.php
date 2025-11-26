
<div class="row">
    <div class="col-sm-12">
        <div class="text-center"><img src="/images/logos/app_logo.jpg" alt="Apprenticeship" /></div>

        <div class="well">
            <p>Further to the Apprenticeships (Form of Apprenticeship Agreement) Regulations which came into force on 6th April 2012, an Apprenticeship Agreement is required at the commencement of an Apprenticeship for all new apprentices who start on or after that date.</p>
            <p>The purpose of the Apprenticeship Agreement is to:-</p>
            <ul style="margin-left: 25px;">
                <li>the skill, trade or occupation for which the apprentice is being trained;</li>
                <li>the apprenticeship standard or framework connected to the apprenticeship;</li>
                <li>the dates during which the apprenticeship is expected to take place; and</li>
                <li>the amount of off the job training that the apprentice is to receive.</li>
            </ul>
            <p></p>
            <p>The Apprenticeship Agreement is incorporated into and does not replace the written statement of particulars issued to the individual in accordance with the requirements of the Employment Rights Act 1996.</p>
            <p>The Apprenticeship is to be treated as being a contract of service not a contract of Apprenticeship.</p>
        </div>

        <h4><strong>Apprenticeship Particulars</strong></h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <?php $f_t = DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$tr->id}'");?>
                <?php $f_id = DAO::getSingleValue($link, "SELECT id FROM student_frameworks WHERE tr_id = '{$tr->id}'");?>
                <?php $is_standard = DAO::getSingleValue($link, "SELECT StandardCode FROM frameworks WHERE id = '{$f_id}'");?>
                <tr><th>Apprentice name:</th><td><?php echo $tr->firstnames . ' ' . $tr->surname; ?></td></tr>
                <tr>
                    <th>Skill, trade or occupation for which the apprentice is being trained:</th>
                    <td><textarea name="skills_trade_occ" id="skills_trade_occ" rows="5" cols="50"><?php echo $ob_learner->job_title; ?></textarea></td>
                </tr>
                <tr><th>Relevant Apprenticeship framework and level:</th><td><?php echo $f_t; ?></td></tr>
                <tr>
                    <th>Place of work (employer):</th>
                    <td>
                        <?php
                        echo $employer->legal_name . '<br>';
                        echo $employer_location->address_line_1 != '' ? $employer_location->address_line_1 . '' : '';
                        echo $employer_location->address_line_2 != '' ? ', ' . $employer_location->address_line_2 . '' : '';
                        echo $employer_location->address_line_3 != '' ? ' ' . $employer_location->address_line_3 . '' : '';
                        echo $employer_location->address_line_4 != '' ? ' ' . $employer_location->address_line_4 . '<br>' : '';
                        echo $employer_location->postcode != '' ? $employer_location->postcode . '<br>' : '';
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table class="table table-bordered">
                            <tr>
                                <th>Start date of apprenticeship:</th><td><?php echo Date::toShort($tr->start_date); ?></td>
                                <th>End date of apprenticeship (including EPA):</th><td><?php echo Date::toShort($tr->end_date_inc_epa); ?></td>
                            </tr>
                            <?php
                            if($is_standard != '' || true)
                            {
                                ?>
                                <tr>
                                    <th>Start date of practical period:</th><td><?php echo Date::toShort($ob_learner->practical_end_date); ?></td>
                                    <th>Estimated end date of practical period:</th><td><?php echo Date::toShort($ob_learner->practical_end_date); ?></td>
                                </tr>
                                <tr>
                                    <th>Duration of practical period:</th>
                                    <td>
                                        <?php
                                        $_diff = DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(MONTH, '{$ob_learner->practical_start_date}', '{$ob_learner->practical_end_date}');");
                                        if(is_null($_diff))
                                            echo '';
                                        else
                                            echo $_diff . ' month(s)';
                                        ?>
                                    </td>
                                    <th>Planned amount of off-the-job training (hours):</th><td><?php echo ceil($otj_hours); ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </td>
                </tr>
                <tr><th><input class="clsICheck" type="checkbox" name="aa_consent" value="" /><label>Click to agree</label></th><td class="text-bold"><?php echo date('d/m/Y'); ?></td></tr>
            </table>
        </div>

    </div>
</div>



<!--	<span style="margin-top: 2px;" class="btn btn-info btn-sm" onclick="window.open('app_agreement_guidance.pdf', '_blank')"><i class="fa fa-info-circle"></i> Guidance Notes</span>-->
