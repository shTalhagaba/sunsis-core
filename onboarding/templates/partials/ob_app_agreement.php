<?php
$app_agreement_provider_url = in_array(DB_NAME, ["am_barnsley", "am_barnsley_demo"]) ? "www.barnsley.ac.uk" : "www.test.com";
?>
<div class="row">
    <div class="col-sm-12">

        <div class="text-center">
            <img src="/images/logos/app_logo.jpg" alt="Apprenticeship" />
            <img src="/images/logos/ESF.png" alt="Apprenticeship" />
            <img  width="230px" class="headerlogo" src="<?php echo $header_image1; ?>"/>
        </div>

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
        <span style="margin-top: 2px;" class="btn btn-info btn-sm" onclick="window.open('app_agreement_guidance.pdf', '_blank')"><i class="fa fa-info-circle"></i> Guidance Notes</span>
        <table class="table row-border">
            <tr><th>Apprentice name:</th><td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td></tr>
            <tr>
                <th>Relevant Apprenticeship framework and level:</th>
                <td><?php echo $framework->title; ?></td>
            </tr>
            <tr>
                <th>Relevant Apprenticeship framework and level:</th>
                <td><?php echo DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';"); ?></td>
            </tr>
            <tr>
                <th>Place of work (employer):</th>
                <td>
			<?php echo $employer->legal_name; ?><br>
                    <?php echo $employer_location->address_line_1 != '' ? $employer_location->address_line_1 . '<br>' : ''; ?>
                    <?php echo $employer_location->address_line_2 != '' ? $employer_location->address_line_2 . '<br>' : ''; ?>
                    <?php echo $employer_location->address_line_3 != '' ? $employer_location->address_line_3 . '<br>' : ''; ?>
                    <?php echo $employer_location->address_line_4 != '' ? $employer_location->address_line_4 . '<br>' : ''; ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="table table-bordered">
                        <tr>
                            <th>Start date of apprenticeship:</th><td><?php echo Date::toShort($tr->apprenticeship_start_date); ?></td>
                            <th>End date of apprenticeship (including EPA):</th><td><?php echo Date::toShort($tr->apprenticeship_end_date_inc_epa); ?></td>
                        </tr>
                        <tr>
                            <th>Start date of practical period:</th><td><?php echo Date::toShort($tr->practical_period_start_date); ?></td>
                            <th>Estimated end date of practical period:</th><td><?php echo Date::toShort($tr->practical_period_end_date); ?></td>
                        </tr>
                        <tr>
                            <th>Duration of practical period:</th><td><?php echo $tr->duration_practical_period; ?> months</td>
                            <th>Planned amount of off-the-job training (hours):</th>
                            <td>
                                <?php 
                                if($tr->contracted_hours_per_week >= 30){
                                    echo $tr->off_the_job_hours_based_on_duration;
                                }
                                else {
                                    echo $tr->part_time_otj_hours;
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr><th colspan="2"><br></th></tr>
        </table>

        
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table">
            <tr>
                <th><input class="clsICheck" type="checkbox" name="agree_app_agreement" value="" /><label>Click to agree</label></th>
                <td class="text-bold"><?php echo date('d/m/Y'); ?></td>
            </tr>
        </table>
    </div>
</div>