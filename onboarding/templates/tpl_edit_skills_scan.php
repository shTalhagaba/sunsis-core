<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>
<?php /* @var $skills_analysis SkillsAnalysis */ ?>
<?php /* @var $link PDO */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Skills Scan</title>
    <link rel="stylesheet" href="css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link href="/assets/adminlte/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

                                            
    <style>
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>
</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Edit Skills Scan</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <?php if(is_null($skills_analysis->provider_sign)) { ?>
                    <span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
                <?php } ?>

                <?php if($_SESSION['user']->username == 'admin' && true) { ?>
                    <span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
                <?php } ?>
            </div>
            <div class="ActionIconBar">

            </div>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>
<br>

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-4">
            <div class="box box-solid box-info">
                <div class="box-header with-border">
                    <h2 class="box-title">Learner Details</h2>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr><th>Name:</th><td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td></tr>
                        <tr><th>Date of Birth:</th><td><?php echo Date::toShort($ob_learner->dob); ?></td></tr>
                        <tr><th>Postcode:</th><td><?php echo $ob_learner->home_postcode; ?></td></tr>
                        <tr><th>Email:</th><td><?php echo $ob_learner->home_email; ?></td></tr>
                        <tr>
                            <th>Employer:</th>
                            <td>
                                <?php echo $employer->legal_name; ?>

                                <?php
                                echo $employer_location->address_line_1 != '' ? $employer_location->address_line_1 . '<br>' : '';
                                echo $employer_location->address_line_2 != '' ? $employer_location->address_line_2 . '<br>' : '';
                                echo $employer_location->address_line_3 != '' ? $employer_location->address_line_3 . '<br>' : '';
                                echo $employer_location->address_line_4 != '' ? $employer_location->address_line_4 . '<br>' : '';
                                echo $employer_location->postcode != '' ? $employer_location->postcode . '<br>' : '';
                                ?>
                                </small>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="box box-solid box-info">
                <div class="box-header with-border">
                    <h2 class="box-title">Apprenticeship Details</h2>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr><th>Standard:</th><td><?php echo $framework->title; ?></td></tr>
                        <tr><th>Level:</th><td><?php echo DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';"); ?></td></tr>
                        <tr><th>Funding Band Max.:</th><td>&pound;<?php echo $skills_analysis->funding_band_maximum; ?></td></tr>
                        <tr><th>Recommended Duration:</th><td><?php echo $skills_analysis->recommended_duration; ?> months</td></tr>
                        <tr><th>Contracted Hours per Week:</th><td><?php echo $tr->contracted_hours_per_week; ?></td></tr>
                        <tr><th>EPA Organisation:</th><td><?php echo DAO::getSingleValue($link, "SELECT EP_Assessment_Organisations FROM central.`epa_organisations` WHERE EPA_ORG_ID = '{$tr->epa_organisation}';"); ?></td></tr>
                        <tr><th>EPA Price:</th><td>&pound;<?php echo $tr->epa_price; ?></td></tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="box box-solid box-info">
                <div class="box-header with-border">
                    <h2 class="box-title">Previous employment history</h2>
                </div>
                <div class="box-body table-responsive">
                    <small><?php include_once(__DIR__ . '/partials/view_ss_employment_history.php'); ?></small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-5">
            <ul style="margin-left: 15px;">
                <li><span class="text-bold">1</span> - I have <span class="text-bold">no knowledge or skills</span> in this topic area.</li>
                <li><span class="text-bold">2</span> - I have <span class="text-bold">minimal knowledge and skills</span> in this topic area.</li>
                <li><span class="text-bold">3</span> - I have <span class="text-bold">some of the knowledge and skills</span> to carry out my role, but not yet to full competence and with confidence.</li>
                <li><span class="text-bold">4</span> - I have <span class="text-bold">the majority of the knowledge and skills</span> required to carry out my role, but not yet fully competent and confident.</li>
                <li><span class="text-bold">5</span> - I am <span class="text-bold">fully competent</span> in this area and can provide evidence to support.</li>
            </ul>
        </div>
    </div>

    <form class="form-horizontal" name="frmEditSkillsScan" id="frmEditSkillsScan" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />
        <input type="hidden" name="_action" value="update_skills_scan" />

        <div class="row">
            <div class="col-sm-12">
                <div class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title">Skill</h2>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Knowledge, Skills & Behaviours</th>
                                <th>Topic</th>
                                <th>What is required?</th>
                                <th style="width: 25%;">Score 1-5 <small>(please select from drop down list)</small></th>
                                <th style="width: 20%;">Comments/Details</th>
                                <th class="small">Delivery Plan Hours (100%)</th>
                                <th class="small">Delivery Plan Hours (following assessment)</th>
                            </tr>
                            <?php
                            $ksb_result = DAO::getResultset($link, "SELECT * FROM ob_learner_ksb WHERE tr_id = '{$tr->id}' ORDER BY id", DAO::FETCH_ASSOC);
                            $delivery_plan_total_ba = $total_planned_hours;
                            $delivery_plan_total_fa = 0;

                            foreach($ksb_result AS $row)
                            {
                                $delivery_plan_hours = 0;
                                $del_hours = $row['del_hours'] != '' ? floatval($row['del_hours']) : 20;
                                echo '<tr>';
                                echo '<td>' . $row['unit_group'] .'</td>';
                                echo '<td class="small">' . $row['unit_title'] .'</td>';
                                echo '<td>' . HTML::cell($row['evidence_title']) .'</td>';
                                echo '<td>' . HTML::selectChosen('score_' . $row['id'], $scores, $row['score'], false) .'</td>';
                                echo '<td><textarea name="comments_' . $row['id'] . '" id="comments_' . $row['id'] . '" style="width: 100%;">' . $row['comments'] . '</textarea></td>';
                                echo '<td class="delHours">' . $del_hours . '</td>';
                                echo '<td class="colHoursFollowAssess">';
                                if($row['score'] == 5)
                                    $delivery_plan_hours = ceil($del_hours * 0.25);
                                elseif($row['score'] == 4)
                                    $delivery_plan_hours = ceil($del_hours * 0.5);
                                elseif($row['score'] == 3)
                                    $delivery_plan_hours = ceil($del_hours * 0.75);
                                elseif($row['score'] == 2)
                                    $delivery_plan_hours = ceil($del_hours * 0.9);
                                elseif($row['score'] == 1)
                                    $delivery_plan_hours = $del_hours;
                                echo $delivery_plan_hours;
                                echo '</td>';
                                echo '</tr>';
                                $delivery_plan_total_fa += $delivery_plan_hours;
                            }
                            echo '<tr><th></th><th></th><th></th><th></th><th></th>';
                            echo '<th class="bg-light-blue" id="colDeliveryPlanTotalBa">' . $delivery_plan_total_ba . '</th>';
                            echo '<input type="hidden" name="txtDeliveryPlanTotalBa" value="'.$delivery_plan_total_ba.'" /> ';
                            echo '<th class="bg-light-blue" id="colDeliveryPlanTotalFa">' . ceil($delivery_plan_total_fa) . '</th></tr>';
                            echo '<input type="hidden" name="txtDeliveryPlanTotalFa" value="0" /> ';
                            echo '<tr>';
                            echo '<th colspan="6" class="text-right bg-green-gradient"> Percentage following assessment</th>';
                            echo '<th class="text-center bg-green-gradient" id="colDeliveryPlanPercentageFa">' . round(($delivery_plan_total_fa/$delivery_plan_total_ba)*100, 0) . '%</th>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<th colspan="6" class="text-right bg-green-gradient"> Duration following assessment (months)</th>';
                            //                            echo '<th class="text-center bg-green-gradient" id="maani">' . $skills_analysis->max_duration_fa . ' months</th>';
                            echo '<th class="text-center">';
                            $_max_duration_fa = intval($skills_analysis->max_duration_fa);
                            $_recommended_duration = intval($skills_analysis->recommended_duration);
                            $_months = [];
                            for($i = 1; $i <= $_recommended_duration; $i++)
                            {
                                $_months[] = [$i, $i];
                            }
                            echo HTML::select('overwrite_max_duration_fa', $_months, $skills_analysis->max_duration_fa, false);
                            echo '</th>';
                            echo '</tr>';

                            ?>
                        </table>

                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>

<script language="JavaScript">

    function save()
    {
        var myForm = document.forms["frmEditSkillsScan"];
        if(!validateForm(myForm))
        {
            return;
        }

        if(parseInt(myForm.overwrite_max_duration_fa.value) < 12 )
        {
            if(!confirm("The duration is less than 12 months. Click OK to proceed or click Cancel to change details."))
            {
                return;
            }
        }

        myForm.submit();
    }

    var phpObLearnerId = '<?php echo $tr->id; ?>';

    var phpTotalPlannedHours = parseInt('<?php echo $total_planned_hours; ?>');

    $(function(){

        $("select[name^=score_]").on('change', function(){
            var delHours = $(this).closest('tr').find('.delHours').html();
            delHours = parseInt(delHours);
            var score = parseInt(this.value);
            var dph = 0;
            if(score == 5)
                dph = delHours * 0.2;
            else if(score == 4)
                dph = delHours * 0.4;
            else if(score == 3)
                dph = delHours * 0.6;
            else if(score == 2)
                dph = delHours * 0.8;
            else if(score == 1)
                dph = delHours;

            $(this).closest('tr').find('.colHoursFollowAssess').html(dph.toFixed(1));

            updateDeliveryPlanTotalFa();


        });

    });

    function updateDeliveryPlanTotalFa()
    {
        var sum = 0;

        $(".colHoursFollowAssess").each(function() {
            var value = $(this).html();
            if(!isNaN(value) && value.length != 0) {
                sum = parseFloat(sum) + parseFloat(value);
            }
        });

        $('th#colDeliveryPlanTotalFa').html(sum.toFixed(0));

        updateDeliveryPlanPercentageFa(sum);
    }

    var recommendedDuration = <?php echo $skills_analysis->recommended_duration; ?>;

    function updateDeliveryPlanPercentageFa(v)
    {
        var ba = parseInt($('th#colDeliveryPlanTotalBa').html());
        var aa = parseInt(v);

        var p = (aa/ba)*100;
        p = parseFloat(p).toFixed(0);
        $('th#colDeliveryPlanPercentageFa').html(p + '%');
        var duration = recommendedDuration*(p/100);
        // $('th#maani').html(duration.toFixed(0) + ' months' );
        $('#overwrite_max_duration_fa').val(duration.toFixed(0));
        console.log(duration);
    }


</script>

</body>
</html>