<div class="row">
    <div class="col-sm-12">
        <p>Please read and agree to the roles and responsibilities listed below.</p>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <caption class="bg-gray-light text-bold" style="padding: 5px;">Roles & Responsibilities:</caption>
            <?php
            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_roles_responsibilities WHERE user_type = 'LEARNER' ORDER BY id", DAO::FETCH_ASSOC);
            foreach($result AS $row)
            {
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . $row['description'] . '</td>';
                echo '</tr>';
            }
            ?>
            <tr>
                <td colspan="2">
                    <table class="table">
                        <tr>
                            <th><input class="clsICheck" type="checkbox" name="roles_resp_desc" value="" /><label>Click to agree to your roles & repsonsibiities</label></th>
                            <td class="text-bold"><?php echo date('d/m/Y'); ?></td>
                        </tr>
                    </table>
                </td> 
            </tr>
        </table>
    </div>
</div>

<?php if(in_array(DB_NAME, ["am_superdrug", "am_sd_demo"])) { ?>
<div class="row">
    <div class="col-sm-12">
        <p>
            <?php echo $employer->brandDescription($link); ?> commits to paying you for your 39 hour week, at the rate agreed with your store, on a 4 weekly basis and will support
            your achievement of this programme by ensuring you receive at least 1 hour per week study time during your working
            hours, off the shopfloor as well as all relevant training on and off the job. (The minimum requirement is for you to have at
            least 288 hours of 'off the job' training during the course of your programme, this will include your study time).
        </p>        
    </div>
</div>
<?php } ?>

<div class="row">
    <div class="col-sm-12">
        <div class="callout">
            <span class="lead text-bold">Working Together</span>
            <p>
                <i>The Employer and the Apprentice will work together with the Training Provider's
                    representatives to ensure that the Apprentice has the best chance to achieve.
                    In so doing, each parties' roles and responsibilities should be read carefully
                    in this Training Plan with further recourse to the appropriate,
                    Funding Rules in force at the time.</i>
            </p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <?php if(DB_NAME == "am_crackerjack") {?>
        <div class="callout">
            <span class="lead text-bold">Queries and Complaints Process</span>
            <p>
                A formal complaint should be put in writing to the Operations Manager; <a class="text-blue" href="mailto:donna.johal@crackerjacktraining.com">donna.johal@crackerjacktraining.com</a> you will receive a response to your complaint within a further 10 working days. 
                If you are not satisfied with the outcome of the stage one consideration of your complaint you may request a review of the decision within 10 working days of receiving the outcome. 
                You must submit a written explanation to the Managing Director; <a class="text-blue" href="fiona.baker@crackerjacktraining.com">fiona.baker@crackerjacktraining.com</a>, of why you are dissatisfied with the outcome of stage one. 
                If following this process the complaint has not been addressed, you can raise this issue directly with the Department for Education (DfE) through; DfE at <a class="text-blue" href="complaints.esfa@education.gov.uk">complaints.esfa@education.gov.uk</a>.
            </p>
        </div>
	<?php } elseif(in_array(DB_NAME, ["am_superdrug", "am_sd_demo"])) { ?>	
            <p>
                If at any time you are not happy with your Apprenticeship programme and wish to make a complaint, in the 1st instance
                speak with your Manager and/or your Assessor. If you need to escalate this further you can make a formal complaint in
                writing to the Internal Verifier; their contact details are in your learning plan or alternatively contact the Superdrug
                Apprenticeship helpline on <i class="fa fa-phone"></i> 01977 809564.<br>
                Or on our email address: <i class="fa fa-envelope"></i> <a class="text-white" href="mailto:apprenticeships@uk.aswatson.com">apprenticeships@uk.aswatson.com</a>
            </p> 
        <?php } ?>

        <div class="callout callout-info">
            <p class="text-bold"><i class="fa fa-info-circle"></i> Apprenticeship Helpline</p>
            All parties can make use of the Apprenticeship Helpline if they have any queries, concerns or complaints:<br>
            <i class="fa fa-envelope"></i> Email: <a class="text-white" href="mailto:helpdesk@manage-apprenticeships.service.gov.uk">helpdesk@manage-apprenticeships.service.gov.uk</a><br>
            <i class="fa fa-phone"></i> Telephone: 08000 150 600<br>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col style="width: 8%" />
            <caption class="bg-gray-light text-bold" style="padding: 5px;">Declarations:</caption>
            <?php
            if($tr->practical_period_start_date >= '2023-08-01' && (DB_NAME == "am_ela") && !in_array($tr->id, OnboardingHelper::UlnsToSkip($link)) )
                $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_declarations WHERE user_type = 'LEARNER' AND year = '2023' AND version = '1' ORDER BY id", DAO::FETCH_ASSOC);
            else
                $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_declarations WHERE user_type = 'LEARNER' AND year = '2022' ORDER BY id", DAO::FETCH_ASSOC);
            $saved_learner_dec = $tr->learner_dec != '' ? explode(",", $tr->learner_dec) : [];
            foreach($result AS $row)
            {
                echo '<tr>';
                if(in_array($row['id'], $saved_learner_dec))
                    echo '<td align="right"><input type="checkbox" name="learner_dec[]" checked value="' . $row['id'] . '" /></td>';
                else
                    echo '<td align="right"><input type="checkbox" name="learner_dec[]" value="' . $row['id'] . '" /></td>';
                echo '<td>' . $row['description'] . '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
</div>
