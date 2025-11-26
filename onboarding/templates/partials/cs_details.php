<?php
/* @var $ob_learner OnboardingLearner */
/* @var $tr TrainingRecord */
/* @var $skills_analysis SkillsAnalysis */
/* @var $employer_location Location */

?>

<div class="row">
    <div class="col-sm-12">
        <p class="lead text-bold">Section 1 - Details</p>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive" style="font-size: medium;">
            <table class="table table-bordered">
                <col width="50%">
                <tr><th class="bg-light-blue">Standard Title:</th><td><?php echo $framework->title; ?></td></tr>
                <tr><th>Level:</th><td><?php echo DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';"); ?></td></tr>
                <tr><th>Price (top of funding band):</th><td>&pound;<?php echo $skills_analysis->funding_band_maximum; ?></td></tr>
                <tr><th>Recommended Duration - months:</th><td><?php echo $skills_analysis->recommended_duration; ?></td></tr>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive" style="font-size: medium;">
            <table class="table table-bordered">
                <col width="50%">
                <caption style="padding: 5px;" class="text-bold bg-light-blue">Results of the Skills Analysis (taking into account all prior learning)</caption>
                <tr><th>Duration:</th><td><?php echo $skills_analysis->max_duration_fa; ?> months</td></tr>
                <tr><th>Price:</th><td>&pound;<?php echo $skills_analysis->total_nego_price_fa; ?></td></tr>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive" style="font-size: medium;">
            <table class="table table-bordered">
                <col width="50%">
                <caption style="padding: 5px;" class="text-bold bg-light-blue">Individualised Apprenticeship Details</caption>
                <tr><th>Start Date Practical Period:</th><td><?php echo Date::toShort($tr->practical_period_start_date); ?></td></tr>
                <tr><th>Planned End Date Practical Period:</th><td><?php echo Date::toShort($tr->practical_period_end_date); ?></td></tr>
                <tr><th>Duration of Practical Period:</th><td><?php echo $tr->duration_practical_period; ?> months</td></tr>
                <tr><th>Start Date Apprenticeship:</th><td><?php echo Date::toShort($tr->apprenticeship_start_date); ?></td></tr>
                <tr><th>Planned End Date of Apprenticeship (incl. EPA):</th><td><?php echo Date::toShort($tr->apprenticeship_end_date_inc_epa); ?></td></tr>
                <tr><th>Duration of Full Apprenticeship (incl. EPA):</th><td><?php echo $tr->apprenticeship_duration_inc_epa; ?> months</td></tr>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive" style="font-size: medium;">
            <table class="table table-bordered">
                <col width="50%">
                <caption style="padding: 5px;" class="text-bold bg-light-blue">Apprentice Contact Details</caption>
                <tr><th>Name:</th><td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td></tr>
                <tr><th>Job Title:</th><td><?php echo $tr->job_title; ?></td></tr>
                <tr><th>Contracted Hours per Week:</th><td><?php echo $tr->contracted_hours_per_week; ?></td></tr>
                <tr><th>Email:</th><td><?php echo $tr->home_email; ?></td></tr>
                <tr><th>Telephone/Mobile:</th><td><?php echo $tr->home_telephone . ' ' . $tr->home_mobile; ?></td></tr>
                <tr><th>Date of Birth:</th><td><?php echo Date::toShort($ob_learner->dob); ?></td></tr>
                <tr>
                    <th>Address:</th>
                    <td>
                        <?php echo $tr->home_address_line_1 != '' ? $tr->home_address_line_1 . '<br>' : ''; ?>
                        <?php echo $tr->home_address_line_2 != '' ? $tr->home_address_line_2 . '<br>' : ''; ?>
                        <?php echo $tr->home_address_line_3 != '' ? $tr->home_address_line_3 . '<br>' : ''; ?>
                        <?php echo $tr->home_address_line_4 != '' ? $tr->home_address_line_4 . '<br>' : ''; ?>
                    </td>
                </tr>
                <tr><th>Postcode:</th><td><?php echo $tr->home_postcode; ?></td></tr>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive" style="font-size: medium;">
            <table class="table table-bordered">
                <col width="50%">
                <caption style="padding: 5px;" class="text-bold bg-light-blue">Employer Details</caption>
                <tr><th>Name:</th><td><?php echo $employer->legal_name; ?></td></tr>
                <tr><th>Employer Mentor:</th><td></td></tr>
                <tr>
                    <th>Address:</th>
                    <td>
                        <?php echo $employer_location->address_line_1 != '' ? $employer_location->address_line_1 . '<br>' : ''; ?>
                        <?php echo $employer_location->address_line_2 != '' ? $employer_location->address_line_2 . '<br>' : ''; ?>
                        <?php echo $employer_location->address_line_3 != '' ? $employer_location->address_line_3 . '<br>' : ''; ?>
                        <?php echo $employer_location->address_line_4 != '' ? $employer_location->address_line_4 . '<br>' : ''; ?>
                    </td>
                </tr>
                <tr><th>Postcode:</th><td><?php echo $employer_location->postcode; ?></td></tr>
                <tr><th>Email:</th><td></td></tr>
                <tr><th>Telephone/Mobile:</th><td></td></tr>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive" style="font-size: medium;">
            <table class="table table-bordered">
                <col width="50%">
                <caption style="padding: 5px;" class="text-bold bg-light-blue">Main Training Provider Details</caption>
                <tr><th>Name:</th><td><?php echo $provider->legal_name; ?></td></tr>
                <tr><th>UKPRN:</th><td><?php echo $provider->ukprn; ?></td></tr>
                <tr>
                    <th>Delivery Location Address:</th>
                    <td>
                        <?php echo $provider_location->address_line_1 != '' ? $provider_location->address_line_1 . '<br>' : ''; ?>
                        <?php echo $provider_location->address_line_2 != '' ? $provider_location->address_line_2 . '<br>' : ''; ?>
                        <?php echo $provider_location->address_line_3 != '' ? $provider_location->address_line_3 . '<br>' : ''; ?>
                        <?php echo $provider_location->address_line_4 != '' ? $provider_location->address_line_4 . '<br>' : ''; ?>
                        <?php echo $provider_location->postcode != '' ? $provider_location->postcode : ''; ?>
                    </td>
                </tr>
                <?php
                $trainers = $tr->trainers != '' ? explode(",", $tr->trainers) : [];
                $i = 1;
                foreach($trainers AS $trainer_id)
                {
                    if($trainer_id == '') continue;
                    $trainer = User::loadFromDatabaseById($link, $trainer_id);
                    echo '<tr>';
                    echo '<th>Trainer ' . $i . '</th>';
                    echo '<td>';
                    echo $trainer->firstnames . ' ' . $trainer->surname;
                    echo $trainer->work_email != '' ? '<br><i class="fa fa-envelope"></i> ' . $trainer->work_email : '';
                    echo $trainer->work_telephone != '' ? '<br><i class="fa fa-phone"></i> ' . $trainer->work_telephone : '';
                    echo $trainer->work_mobile != '' ? '<br><i class="fa fa-mobile"></i> ' . $trainer->work_mobile : '';
                    echo '</td>';
                    echo '</tr>';
                    $i++;
                }
                ?>
            </table>
        </div>
    </div>
</div>

<?php if(!is_null($subcontractor)) { ?>
<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive" style="font-size: medium;">
            <table class="table table-bordered">
                <col width="50%">
                <caption style="padding: 5px;" class="text-bold bg-light-blue">Delivery Subcontractor</caption>
                <tr><th>Name:</th><td><?php echo $subcontractor->legal_name; ?></td></tr>
                <tr><th>UKPRN:</th><td><?php echo $subcontractor->ukprn; ?></td></tr>
                <tr>
                    <th>Delivery Location Address:</th>
                    <td>
                        <?php echo $subcontractor_location->address_line_1 != '' ? $subcontractor_location->address_line_1 . '<br>' : ''; ?>
                        <?php echo $subcontractor_location->address_line_2 != '' ? $subcontractor_location->address_line_2 . '<br>' : ''; ?>
                        <?php echo $subcontractor_location->address_line_3 != '' ? $subcontractor_location->address_line_3 . '<br>' : ''; ?>
                        <?php echo $subcontractor_location->address_line_4 != '' ? $subcontractor_location->address_line_4 . '<br>' : ''; ?>
                        <?php echo $subcontractor_location->postcode != '' ? $subcontractor_location->postcode : ''; ?>
                    </td>
                </tr>
                <tr><th>Trainer:</th><td></td></tr>
                <tr><th>Email:</th><td></td></tr>
                <tr><th>Telephone/Email:</th><td></td></tr>
            </table>
        </div>
    </div>
</div>
<?php } ?>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive" style="font-size: medium;">
            <table class="table table-bordered">
                <col width="50%">
                <caption style="padding: 5px;" class="text-bold bg-light-blue">End Point Assessment Organisation (EPAO)</caption>
                <tr><th>EPAO Name:</th><td><?php echo $tr->getEpaOrgName($link); ?></td></tr>
                <tr><th>Planned EPA Date:</th><td><?php echo Date::toShort($tr->planned_epa_date); ?></td></tr>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive" style="font-size: medium;">
            <table class="table table-bordered">
                <col width="50%">
                <caption style="padding: 5px;" class="text-bold bg-light-blue">Off-the-job Training - 20% minimum</caption>
                <tr><th>Contracted hours per week:</th><td><?php echo $skills_analysis->contracted_hours_per_week; ?> hours</td></tr>
                <tr><th>Weeks to be worked per year:</th><td><?php echo $skills_analysis->weeks_to_be_worked_per_year; ?> weeks</td></tr>
                <tr><th>Total contracted hours per year:</th><td><?php echo $skills_analysis->total_contracted_hours_per_year; ?> hours</td></tr>
                <tr><th>Length of programme:</th><td><?php echo $skills_analysis->length_of_programme_practical_period; ?> months</td></tr>
                <tr><th>Total contracted hours - full apprenticeship:</th><td><?php echo $skills_analysis->total_contracted_hours_full_apprenticeship; ?> hours</td></tr>
                <tr><th>Minimum 20% OTJ training:</th><td><?php echo $skills_analysis->minimum_percentage_otj_training; ?> hours</td></tr>
                <tr><td colspan="2"></td></tr>
                <tr>
                    <th colspan="2" class="bg-gray-light">If the learner works less than 30 hours per week, please use this space to detail how the minimum duration has been extended. (12 x 30/average weekly hours = new minimum duration in months)</th>
                </tr>
                <tr>
                    <td colspan="2">
                        <textarea name="how_minimum_duration_extended" id="how_minimum_duration_extended" style="width: 100%" rows="5"></textarea>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

