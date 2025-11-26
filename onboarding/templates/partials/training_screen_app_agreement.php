<?php
$app_agreement_provider_url = in_array(DB_NAME, ["am_barnsley", "am_barnsley_demo"]) ? "www.barnsley.ac.uk" : "www.test.com";
$app_agreement_header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");
$app_agreement_employer_location = Location::loadFromDatabase($link, $tr->employer_location_id);
?>
<div class="row">
    <div class="col-sm-12">
        <p class="pull-right">
            
        </p>
    </div>
    <div class="col-sm-12">
        <div class="well">
            <p>Further to the Apprenticeships (Form of Apprenticeship Agreement) Regulations which came into force on 6th April 2012, an Apprenticeship Agreement is required at the commencement of an Apprenticeship for all new apprentices who start on or after that date.</p>
            <p>The purpose of the Apprenticeship Agreement is to:</p>
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
        <table class="table row-border">
            <tr>
                <th>Apprentice name:</th>
                <td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td>
            </tr>
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
                    <?php echo $app_agreement_employer_location->address_line_1 != '' ? $app_agreement_employer_location->address_line_1 . '<br>' : ''; ?>
                    <?php echo $app_agreement_employer_location->address_line_2 != '' ? $app_agreement_employer_location->address_line_2 . '<br>' : ''; ?>
                    <?php echo $app_agreement_employer_location->address_line_3 != '' ? $app_agreement_employer_location->address_line_3 . '<br>' : ''; ?>
                    <?php echo $app_agreement_employer_location->address_line_4 != '' ? $app_agreement_employer_location->address_line_4 . '<br>' : ''; ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="table table-bordered">
                        <tr>
                            <th>Start date of apprenticeship:</th>
                            <td><?php echo Date::toShort($tr->apprenticeship_start_date); ?></td>
                            <th>End date of apprenticeship (including EPA):</th>
                            <td><?php echo Date::toShort($tr->apprenticeship_end_date_inc_epa); ?></td>
                        </tr>
                        <tr>
                            <th>Start date of practical period:</th>
                            <td><?php echo Date::toShort($tr->practical_period_start_date); ?></td>
                            <th>Estimated end date of practical period:</th>
                            <td><?php echo Date::toShort($tr->practical_period_end_date); ?></td>
                        </tr>
                        <tr>
                            <th>Duration of practical period:</th>
                            <td><?php echo $tr->duration_practical_period; ?> months</td>
                            <th>Planned amount of off-the-job training (hours):</th>
                            <td>
                                <?php
                                if($tr->otj_overwritten != '')
                                {
                                    echo $tr->otj_overwritten;
                                } 
                                else
                                {
                                    echo $tr->contracted_hours_per_week >= 30 ? $tr->off_the_job_hours_based_on_duration : $tr->part_time_otj_hours;
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <th colspan="2"><br></th>
            </tr>
        </table>

    </div>
    <div class="col-sm-12">
        <div class="box box-primary box-solid">
            <div class="box-header">
                <span class="box-title">Signatures</span>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered" style="font-size: medium;">
                        <tr>
                            <th>Learner</th>
                            <td>
                                <img id="img_learner_sign" src="do.php?_action=generate_image&<?php echo $tr->learner_sign != '' ? $tr->learner_sign : 'title=Not yet signed&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" /><br>
                                <?php echo Date::toShort($tr->learner_sign_date); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Employer</th>
                            <td>
                                <img id="img_emp_sign" src="do.php?_action=generate_image&<?php echo $tr->emp_sign != '' ? $tr->emp_sign : 'title=Not yet signed&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" /><br>
                                <?php echo Date::toShort($tr->emp_sign_date); ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>