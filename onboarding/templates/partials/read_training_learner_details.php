<?php /* @var $ob_learner OnboardingLearner */ ?>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-solid box-success">
            <div class="box-header"><span class="box-title with-header"><span
                        class="lead text-bold"><?php echo htmlspecialchars($ob_learner->firstnames) . ' ' . htmlspecialchars(strtoupper($ob_learner->surname ?? '')); ?></span></span>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table">
                        <tr><th>Gender</th><td><?php echo DAO::getSingleValue($link, "SELECT description FROM lookup_gender WHERE id = '{$ob_learner->gender}'"); ?></td></tr>
                        <tr><th>Date of Birth</th><td><?php echo Date::toShort($ob_learner->dob); ?><br><label class="label label-info"><?php echo Date::dateDiff(date("Y-m-d"), $ob_learner->dob); ?></label></td></tr>
			     <?php if(in_array(DB_NAME, ["am_eet"]) && $ob_learner->dob!= '' ) { ?>
                            <tr><th>Age on 31/08/2024</th><td><?php echo Date::dateDiff(date("2024-08-31"), $ob_learner->dob); ?></td></tr>
                        <?php } ?>
                        <tr><th>Home Address (line 1)</th><td><?php echo $ob_learner->home_address_line_1; ?></td></tr>
                        <tr><th>Home Address (line 2)</th><td><?php echo $ob_learner->home_address_line_2; ?></td></tr>
                        <tr><th>Home Address (line 3)</th><td><?php echo $ob_learner->home_address_line_3; ?></td></tr>
                        <tr><th>Home Address (line 4)</th><td><?php echo $ob_learner->home_address_line_4; ?></td></tr>
                        <tr><th>Borough</th><td><?php echo $ob_learner->borough; ?></td></tr>
                        <tr><th>Home Postcode</th><td><?php echo $ob_learner->home_postcode; ?></td></tr>
                        <tr><th>Personal Mobile</th><td><?php echo $ob_learner->home_mobile; ?></td></tr>
                        <tr><th>Personal Email</th><td><a href="mailto:<?php echo $ob_learner->home_email; ?>"><?php echo $ob_learner->home_email; ?></a></td></tr>
                        <tr><th>Personal Telephone</th><td><?php echo $ob_learner->home_telephone; ?></td></tr>
                        <tr><th>Work Email</th><td><a href="mailto:<?php echo $ob_learner->work_email; ?>"><?php echo $ob_learner->work_email; ?></a></td></tr>
                        <tr><th>Ethnicity</th><td><?php echo $ob_learner->ethnicity != '' ? LookupHelper::getEthnicitiesList($ob_learner->ethnicity) : ''; ?></td></tr>
                        <tr><th>ULN (Unique Learner Number)</th><td><?php echo $ob_learner->uln; ?></td></tr>
                        <tr><th>National Insurance</th><td><?php echo $ob_learner->ni; ?></td></tr>
                        <tr><th>BKSB Username</th><td><?php echo $ob_learner->bksb_username; ?></td></tr>
                        <?php if(DB_NAME == "am_ela"){ ?>
                        <tr><th>DAS Admin</th><td><?php echo $ob_learner->das_admin; ?></td></tr>
                        <tr><th>DAS Cohort No.</th><td><?php echo $ob_learner->das_cohort_no; ?></td></tr>
                        <?php } ?>
                    </table>
                    <hr>
                    <table class="table text-info">
                        <tr><th>Created at</th><td><?php echo Date::to($ob_learner->created, Date::DATETIME); ?></td></tr>
                        <tr><th>Created by</th><td><?php echo $ob_learner->getCreatorName($link); ?></td></tr>
                        <tr><th>Last updated at</th><td><?php echo Date::to($ob_learner->updated, Date::DATETIME); ?></td></tr>
			<tr><th>System Learner ID</th><td><?php echo $ob_learner->id; ?></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
