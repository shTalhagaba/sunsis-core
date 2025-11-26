<?php /* @var $framework Framework */ ?>
<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>

<div class="row" style="font-size: medium">

    <div class="col-sm-12">
        <table class="table table-bordered">
            <col width="40%" />
            <col width="60%" />
            <tr>
                <th class="text-bold">Apprentice Name</th>
                <td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td>
            </tr>
            <tr>
                <th class="text-bold">Employer Name</th>
                <td>
                    <?php echo in_array(DB_NAME, ["am_superdrug", "am_sd_demo"]) ? $employer->brandDescription($link) : $employer->legal_name; ?><br>
                    <small>
                    <?php 
                    echo $mainLocation->address_line_1 != '' ? $mainLocation->address_line_1 . '<br>' : ''; 
                    echo $mainLocation->address_line_2 != '' ? $mainLocation->address_line_2 . '<br>' : ''; 
                    echo $mainLocation->address_line_3 != '' ? $mainLocation->address_line_3 . '<br>' : ''; 
                    echo $mainLocation->address_line_4 != '' ? $mainLocation->address_line_4 . '<br>' : '';
                    echo $mainLocation->postcode != '' ? $mainLocation->postcode : '';
                    ?>
                    </small>
                </td>
            </tr>
            <tr>
                <th class="text-bold">Level</th>
                <td><?php echo DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';"); ?></td>
            </tr>
            <tr>
                <th class="text-bold">Contracted Hours per week</th>
                <td><?php echo $tr->contracted_hours_per_week; ?></td>
            </tr>
        </table>
    </div>

    <div class="col-sm-12">
        <table class="table table-bordered">
            <col width="40%" />
            <col width="60%" />
            <tr>
                <th class="text-bold">Main Training Provider</th>
                <td><?php echo $tr->getProviderLegalName($link); ?></td>
            </tr>
            <?php 
            if($tr->subcontractor_id != '')
            {
                echo '<tr>';
                echo '<th class="text-bold">Subcontractor</th>';
                echo '<td>' . $tr->getSubcontractorLegalName($link) . '</td>';
                echo '</tr>';
            }
            ?>
            <tr>
                <th class="text-bold">Apprenticeship Link</th>
                <td><?php echo $framework->getApprenticeshipLink($link); ?></td>
            </tr>
        </table>
    </div>

    <div class="col-sm-12">
        <table class="table table-bordered">
            <col width="40%" />
            <col width="60%" />
            <tr>
                <th class="text-bold text-green">End Point Assessment Organisation</th>
                <td><?php echo $tr->getEpaOrgName($link); ?></td>
            </tr>
        </table>
    </div>

</div>