<?php /* @var $sa SkillsAnalysis */ ?>
<?php /* @var $tr TrainingRecord */ ?>
<?php /* @var $ob_learner OnboardingLeanrer */ ?>

<?php 
$score_instances = [
    0 => 0,
    1 => 0,
    2 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
];

$delivery_plan_total_hours_ba = $total_planned_hours;
$delivery_plan_total_hours_fa = 0;
foreach ($sa->ksb as $row) 
{
    $del_hours = $row['del_hours'] != '' ? floatval($row['del_hours']) : 0;
    $row['score'] = $row['score'] == '' ? 0 : $row['score'];	
    $score_instances[$row['score']] += $del_hours;
}
$delivery_plan_total_hours_fa = $score_instances[5] + $score_instances[4] + $score_instances[3];
$delivery_plan_total_hours_ba = array_sum($score_instances);

?>
<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Skills Analysis</title>
    <link rel="stylesheet" href="/css/common.css" type="text/css" />
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

    <!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body>
    <div class="row">
        <div class="col-lg-12">
            <div class="banner">
                <div class="Title" style="margin-left: 6px;">Edit Skills Analysis</div>
                <div class="ButtonBar">
                    <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
                    <?php if ( ($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [1])) && $sa->signed_by_provider != "1" ) { ?>
                        <span class="btn btn-xs btn-default" onclick="save();"><i class="fa fa-save"></i> Save Skills Analysis</span>
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

    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <td>
                                    <span class="text-bold">Learner Details:</span><br>
                                    <span class="text-info">First Name(s): </span><?php echo $ob_learner->firstnames; ?><br>
                                    <span class="text-info">Surname: </span><?php echo $ob_learner->surname; ?><br>
                                    <span class="text-info">Personal Email: </span><?php echo $ob_learner->home_email; ?><br>
                                    <span class="text-info">Employer: </span><?php echo DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$tr->employer_id}'"); ?><br>
                                </td>
                                <td>
                                    <span class="text-bold">Apprenticeship Details:</span><br>
                                    <span class="text-info">Standard/ Programme: </span><?php echo $framework->title; ?><br>
                                    <span class="text-info">Apprenticeship Title: </span><?php echo $framework->getStandardCodeDesc($link); ?><br>
                                    <span class="text-info">Level: </span><?php echo DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}'"); ?><br>
                                    <span class="text-info">Funding Band Max.: </span>&pound;<?php echo $framework->getFundingBandMax($link); ?><br>
                                    <span class="text-info">Recommended Duration: </span><?php echo $framework->getRecommendedDuration($link); ?> months<br>
                                </td>
                                <td>
                                    <span class="text-bold">Training Details:</span><br>
                                    <?php if($otj_based_on_6_hrs_pr_week){ ?> 
                                        <span class="text-success"><i class="fa fa-info-circle"></i> Full time learner</span><br>
                                        <span class="text-info">Practical Period Dates: </span><?php echo Date::toShort($tr->practical_period_start_date) . ' - ' . Date::toShort($tr->practical_period_end_date); ?><br>
                                        <span class="text-info">Practical Period Duration: </span><?php echo $tr->duration_practical_period; ?> months<br>
                                        <span class="text-info">Contracted Hours per Week: </span><?php echo $tr->contracted_hours_per_week; ?> hours<br>
                                        <span class="text-info">Weeks to be worked per Year: </span><?php echo $tr->weeks_to_be_worked_per_year; ?> weeks<br>
                                    <?php } else {?>
                                        <span class="text-info"><i class="fa fa-info-circle"></i> Part time learner</span><br>
                                        <span class="text-info">Contracted Hours per Week: </span><?php echo $tr->contracted_hours_per_week; ?> hours<br>
                                        <span class="text-info">Weeks to be worked per Year: </span><?php echo $tr->weeks_to_be_worked_per_year; ?> weeks<br>
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <form class="form-horizontal" name="frmSkillsAnalysis" method="post" action="do.php">
                <input type="hidden" name="_action" value="save_skills_analysis_dl" />
                <input type="hidden" name="id" value="<?php echo $sa->id; ?>" />
                <input type="hidden" name="off_the_job_hours_based_on_duration" value="" />
                <input type="hidden" name="txtDeliveryPlanTotalBa" value="<?php echo $delivery_plan_total_hours_ba; ?>" />
                <input type="hidden" name="txtDeliveryPlanTotalFa" value="<?php echo $delivery_plan_total_hours_fa; ?>" />

                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <?php if (is_array($sa->ksb)) {
                                echo "<thead>Total Questions: " . count($sa->ksb) . "</thead>";
                            } ?>
                            <table class="table table-bordered">
                                <tr class="bg-success">
                                    <th>#</th>
                                    <th>Knowledge, Skills & Behaviours</th>
                                    <th>What is required?</th>
                                    <th>Score 1-5 <small>(please select from drop down list)</small></th>
                                    <th style="width: 25%;">Comments/Details</th>
                                </tr>
                                <?php
                                $question_counter = 0;
                                foreach ($sa->ksb as $row) 
                                {
                                    $delivery_plan_hours = 0;
                                    $del_hours = $row['del_hours'] != '' ? floatval($row['del_hours']) : 0;
				    $row['score'] = $row['score'] == '' ? 0 : $row['score'];	
                                    $score_instances[$row['score']] += $del_hours;
                                    
                                    echo '<tr>';
                                    echo '<td>' . ++$question_counter . '/' . count($sa->ksb) . '</td>';
                                    echo '<td>' . $row['unit_title'] . '</td>';
                                    echo '<td>' . HTML::cell($row['evidence_title']) . '</td>';
                                    echo '<td>' . HTML::selectChosen('score_' . $row['id'], $scores, $row['score'], true) . '</td>';
                                    echo '<td><textarea name="comments_' . $row['id'] . '" id="comments_' . $row['id'] . '" style="width: 100%;">' . $row['comments'] . '</textarea></td>';
                                    echo '<td class="col_question_delivery_plan_hours" style="display: none;">' . $del_hours . '</td>';
                                    echo '</tr>';
                                }

                                // echo '<tr>';
                                // echo '<th></th><th></th><th></th>';
                                // echo '<th class="bg-light-blue" id="colDeliveryPlanTotalBa">' . $delivery_plan_total_hours_ba . '</th>';
                                // echo '<th class="bg-light-blue" id="colDeliveryPlanTotalFa" >' . $delivery_plan_total_hours_fa . '</th>';
                                // echo '</tr>';
                                
                                echo '<tr>';
                                echo '<th colspan="4" class="text-right"> Percentage following assessment (%)</th>';
                                echo '<th class="text-center"><input readonly maxlength="6" type="text" name="percentage_fa" id="colDeliveryPlanPercentageFa" value="' . $sa->percentage_fa . '" /></th>';
                                echo '</tr>';

                                echo '<tr>';
                                echo '<th colspan="4" class="text-right"> Duration following assessment (months)</th>';
                                echo '<th class="text-center">';
                                $_recommended_duration = intval($recommended_duration);
                                $_months = [];
                                for ($i = 1; $i <= $max_duration_list_option; $i++) {
                                    $_months[] = [$i, $i];
                                }
                                echo HTML::select('overwrite_duration_fa', $_months, $duration_list_selected_option, false);
                                echo '</th>';
                                echo '</tr>';

                                if($otj_based_on_6_hrs_pr_week)
                                {
                                    echo '<tr>';
                                    echo '<th colspan="4" class="text-right bg-green-gradient"> Off-the-job hours for <span class="spanDurationInMonths"></span> months<br><small><i class="fa fa-info-circle"></i> based on 6 hours per week</small></th>';
                                    echo '<td class="tdOtj text-center text-bold"></td>';
                                    echo '</tr>';
                                }

                                    echo '<tr>';
                                    echo '<th colspan="4" class="text-right bg-green-gradient"> Select Reduction percentage for TNP1 (price)<br><small><i class="fa fa-info-circle"></i> TNP1 is reduced by at least 50% of the Percentage Reduction following assessment - you may increase the % result if required.</small></th>';
                                    echo '<td class="text-center">';
                                    echo HTML::select('price_reduction_percentage', [], null);
                                    echo '%';
                                    echo $sa->price_reduction_percentage != '' ? '<br>previously selected: ' . $sa->price_reduction_percentage . '%' : '';
                                    echo '</td>';
                                    echo '</tr>';
                                

                                ?>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <table class="table table-bordered" id="tblResult">
                            <tr>
                                <th>Total OTJ hours for Apprenticeship</th>
                                <td id="colDeliveryPlanTotalBa"><?php echo $delivery_plan_total_hours_ba; ?></td>
                                <th>Total OTJ hours to be RPL'd</th>
                                <td id="colDeliveryPlanTotalFa"><?php echo $delivery_plan_total_hours_fa; ?></td>
                                <th>OTJ RPL % Total OTJ</th>
                                <td id="colPercentageRpld"><?php echo 100-$sa->percentage_fa; ?>%</td>
                            </tr>
                        </table>
                    </div>
		    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Price</th>
                                    <th>Before Assessment</th>
                                    <th>Following Assessment</th>
                                </tr>
                                <?php
                                $tnp1 = json_decode($sa->tnp1);
                                $tnp1_fa = json_decode($sa->tnp1_fa);
                                $tnp1_ba_total = 0;
                                $tnp1_fa_total = 0;
                                if (is_array($tnp1_fa)) 
                                {
                                    foreach ($tnp1 as $price_item) 
                                    {
                                        echo $price_item->reduce == 1 ? '<tr class="bg-info trPrice">' : '<tr class="trUnchangedPrice">';
                                        echo '<td>' . $price_item->description . '</td>';
                                        echo '<td class="tdBeforePrice">' . $price_item->cost . '</td>';
                                        $tnp1_ba_total += intval($price_item->cost);
                                        foreach ($tnp1_fa as $price_item_fa) 
                                        {
                                            if ($price_item_fa->description === $price_item->description) 
                                            {
                                                echo '<td class="tdAfterPrice">' . $price_item_fa->cost . '</td>';
                                                $tnp1_fa_total += intval($price_item_fa->cost);
                                            }
                                        }
                                        echo '</tr>';
                                    }
                                    echo '<tr><th align="right">Total</th><td>' . $tnp1_ba_total . '</td><td class="tdAfterPriceTotal">' . $tnp1_fa_total . '</td></tr>';
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="box box-<?php echo $sa->provider_sign == "" ? "warning" : "success"; ?> box-solid">
                            <div class="box-header">
                                <div class="box-title">Sign the Skills Analysis</div>
                            </div>
                            <div class="box-body">
				<?php if(DB_NAME == "am_ela"){?>
                                <div class="form-group">
                                    <label for="lock_for_learner" class="col-sm-5 control-label fieldLabel_compulsory">Lock for Learner:</label>
                                    <div class="col-sm-7">
                                        <?php echo HTML::select('lock_for_learner', [[0, 'No'], [1, 'Yes']], $sa->lock_for_learner, false); ?>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label for="is_eligible_after_ss" class="col-sm-5 control-label fieldLabel_compulsory">Is learner eligible after skills analysis:</label>
                                    <div class="col-sm-7">
                                        <?php echo HTML::select('is_eligible_after_ss', [['N', 'No'], ['Y', 'Yes']], $sa->is_eligible_after_ss, true); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="rationale_by_provider" class="col-sm-5 control-label fieldLabel_compulsory">
                                        Comments:<br>
                                        <span class="text-info small">
                                            e.g. that you have accounted for any prior learning, taking into account all elements of the Skills Analysis, and that where it affects the learning or the funding of any of the apprenticeship that you have detailed the adjustments to the content, duration and price accordingly.
                                        </span>
                                    </label>
                                    <div class="col-sm-7">
                                        <textarea class="form-control" name="rationale_by_provider" id="rationale_by_provider" rows="5"><?php echo htmlspecialchars($sa->rationale_by_provider ?? ''); ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="is_finished" class="col-sm-5 control-label fieldLabel_compulsory">Mark as completed:</label>
                                    <div class="col-sm-7">
                                        <?php echo HTML::select('is_finished', [['N', 'No'], ['Y', 'Yes']], $sa->is_finished, true); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12 table-responsive">
                                        <table style="margin-top: 5px;" class="table table-bordered">
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>Name</th>
                                                <th>Signature</th>
                                                <th>Date</th>
                                            </tr>
                                            <tr>
                                                <th><?php echo in_array(DB_NAME, ["am_ela"]) ? 'Assessor/Tutor' : 'Training Provider'; ?></th>
                                                <td>
                                                    <?php
                                                    if ($sa->provider_sign != '') {
                                                        echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.signature = '{$sa->provider_sign}'");
                                                    } else {
                                                        echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname;
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <span class="btn btn-info" onclick="getSignature('provider');">
                                                        <img id="img_provider_sign" src="do.php?_action=generate_image&<?php echo $sa->provider_sign != '' ? $sa->provider_sign : 'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
                                                        <input type="hidden" name="provider_sign" id="provider_sign" value="<?php echo $sa->provider_sign; ?>" />
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php echo $sa->provider_sign_date != '' ? Date::toShort($sa->provider_sign_date) : date('d/m/Y'); ?>
                                                </td>
                                            </tr>

                                        </table>
                                    </div>
                                </div>
				
                                <span class="btn btn-block btn-primary" style="margin-bottom: 15px;" onclick="save();"><i class="fa fa-save"></i> Save Skills Analysis</span>
				
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>




    </div>


    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/common.js" type="text/javascript"></script>

    <script language="JavaScript">
        var phpProviderSignature = '<?php echo is_null($sa->provider_sign) ? $_SESSION['user']->signature : $sa->provider_sign; ?>';

        $(function() {

            $("select[name^=score_]").on('change', function() {

		if($(this).closest('td').hasClass('bg-red'))
                {
                    $(this).closest('td').removeClass('bg-red');
                }

                refreshScoresAndCalcs();
                refreshOtj();
                refreshPriceReducationDdl();

            });

            $("select[name^=overwrite_duration_fa]").on('change', function() {

                refreshOtj();
                refreshPriceReducationDdl();

            });

            <?php if($otj_based_on_6_hrs_pr_week){ ?> 
                refreshOtj(); 
            <?php }?>

            refreshPriceReducationDdl();
            
        });

        function refreshScoresAndCalcs() 
        {
            var score_instances = {
                0: 0,
                1: 0,
                2: 0,
                3: 0,
                4: 0,
                5: 0
            }

            // start with checking scores of all questions and count instances and sum delivery hours for each score option
            $("select[name^=score_]").each(function(index) {
                var col_question_delivery_plan_hours = $(this).closest('tr').find('.col_question_delivery_plan_hours').html();
                col_question_delivery_plan_hours = parseInt(col_question_delivery_plan_hours);
                var score = parseInt(this.value);
                score_instances[score] += col_question_delivery_plan_hours;
            });

            // update calculations
            var delivery_plan_total_hours_ba = score_instances[0] + score_instances[1] + score_instances[2] + score_instances[3] + score_instances[4] + score_instances[5];
            var delivery_plan_total_hours_fa = score_instances[3] + score_instances[4] + score_instances[5];
            $("td#colDeliveryPlanTotalBa").html(delivery_plan_total_hours_ba);
            $("td#colDeliveryPlanTotalFa").html(delivery_plan_total_hours_fa);
            $("input[name=txtDeliveryPlanTotalFa]").val(delivery_plan_total_hours_fa);

            var percentage = Math.round( (delivery_plan_total_hours_fa / delivery_plan_total_hours_ba) * 100 );
            $("td#colPercentageRpld").html(percentage + '%');

            var p = 100 - percentage;
            $('#colDeliveryPlanPercentageFa').val( p );

            var recommendedDuration = <?php echo intval($recommended_duration); ?>;
            var duration = recommendedDuration * (p / 100);
            <?php if($otj_based_on_6_hrs_pr_week){ ?>
            $('#overwrite_duration_fa').val(duration.toFixed(0));
            <?php } ?>
        }
        
        function refreshOtj()
        {
            <?php if(!$otj_based_on_6_hrs_pr_week){ ?> 
                return; 
            <?php } ?>
            var durationInMonths = $("select[name=overwrite_duration_fa]").val();
            var weeks_to_be_worked_per_year = parseFloat(<?php echo json_encode($tr->weeks_to_be_worked_per_year); ?>);
            var otj = Math.round( (weeks_to_be_worked_per_year/12)*durationInMonths*6);
            $("input[name=off_the_job_hours_based_on_duration]").val(otj);
            $("td.tdOtj").html( otj );
            $("span.spanDurationInMonths").html(durationInMonths);
        }

        function refreshPriceReducationDdl()
        {
            var price_reduction_percentage = document.getElementById("price_reduction_percentage");
            var percentage = $("#colDeliveryPlanPercentageFa").val();
            ajaxPopulateSelect(price_reduction_percentage, 'do.php?_action=ajax_load_account_manager&subaction=load_price_reduction_ddl&percentage=' + encodeURIComponent(percentage));
        }

        function save() 
        {
            var frmSkillsAnalysis = document.forms["frmSkillsAnalysis"];

	    var allAnswered = true;
            $("select[name^=score_]").each(function(){
                if(this.value == '')
                {
                    $(this).closest('td').addClass('bg-red');
                    alert('Please answer all questions');
		    this.focus();
                    return allAnswered = false;
                }
            });	

            var overwrite_duration_fa = frmSkillsAnalysis.overwrite_duration_fa.value;
            if(overwrite_duration_fa < 12)
            {
                if( !confirm("Duration is less than 12 months. Are you sure you want to continue?") )
                {
                    return false;
                }
            }
            <?php if($otj_based_on_6_hrs_pr_week){ ?> 
                if(frmSkillsAnalysis.price_reduction_percentage.value == '')
                {
                    alert('Please select the price reduction percentage.');
                    frmSkillsAnalysis.price_reduction_percentage.focus();
                    return;
                }
            <?php } ?>
	
	    if($("#is_finished").val() == 'Y' && provider_sign.value.trim() == '')
            {
                alert('Please provide your signature.');
                return;
            }


            frmSkillsAnalysis.submit();
        }

        function getSignature(user) 
        {
            if (window.phpProviderSignature == '') 
            {
                $('#signature_text').val('');
                $("#panel_signature").data('panel', 'provider').dialog("open");
                return;
            }
            $('#img_provider_sign').attr('src', 'do.php?_action=generate_image&' + window.phpProviderSignature);
            $('#provider_sign').val(window.phpProviderSignature);

            return;
        }

	function price_reduction_percentage_onchange(ele)
        {
            var selected_price_percentage = ele.value;
            var afterPriceTotal = 0;

            $("tr.trPrice").each(function(index, element){
                var beforePrice = $(element).find("td.tdBeforePrice").text();
                if(beforePrice != '')
                {
                    beforePrice = parseFloat(beforePrice);
                    var afterPrice = Math.ceil( beforePrice * ((100-selected_price_percentage) / 100) );
                    $(element).find("td.tdAfterPrice").text(afterPrice);
                    afterPriceTotal += afterPrice;
                }
            });

	    $("tr.trUnchangedPrice").each(function(index, element){
                var beforePrice = $(element).find("td.tdBeforePrice").text();
                if(beforePrice != '')
                {
                    afterPriceTotal += parseFloat(beforePrice);
                }
            });

            $("td.tdAfterPriceTotal").text(afterPriceTotal);
        }
    </script>

</body>

</html>