<?php /* @var $sa SkillsAnalysis */ ?>
<?php /* @var $tr TrainingRecord */ ?>
<?php /* @var $ob_learner OnboardingLeanrer */ ?>

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

    <style>
        #btn-scroll-up {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 30px;
            z-index: 99;
            cursor: pointer;
        }

    </style>
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
                                    <span class="text-info">Practical Period Dates: </span><?php echo Date::toShort($tr->practical_period_start_date) . ' - ' . Date::toShort($tr->practical_period_end_date); ?><br>
                                    <span class="text-info">Practical Period Duration: </span><?php echo $tr->duration_practical_period; ?> months<br>
				                    <span class="text-info">Contracted Hours per Week: </span><?php echo $tr->contracted_hours_per_week; ?> hours<br>
                                    <span class="text-info">Weeks to be worked per Year: </span><?php echo $tr->weeks_to_be_worked_per_year; ?> weeks<br>	

                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <form class="form-horizontal" name="frmSkillsAnalysis" method="post" action="do.php?_action=save_skills_analysis_ela">
                <input type="hidden" name="_action" value="save_skills_analysis_ela" />
                <input type="hidden" name="id" value="<?php echo $sa->id; ?>" />
		        <input type="hidden" name="off_the_job_hours_based_on_duration" value="" />

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
                                    <th style="width: 25%;">Score 1-5 <small>(please select from drop down list)</small></th>
                                    <th style="width: 20%;">Comments/Details</th>
                                    <th class="small">Delivery Hours (100%)</th>
                                    <th class="small">Delivery Hours (following assessment)</th>
                                </tr>
                                <?php

                                $delivery_plan_total_ba = $total_planned_hours;
                                $delivery_plan_total_fa = 0;
                                $question_counter = 0;
                                foreach ($sa->ksb as $row) 
                                {
                                    $delivery_plan_hours = 0;
                                    $del_hours = $row['del_hours'] != '' ? floatval($row['del_hours']) : 0;

                                    echo '<tr>';
                                    echo '<td>' . ++$question_counter . '/' . count($sa->ksb) . '</td>';
                                    echo '<td>' . $row['unit_title'] . '</td>';
                                    echo '<td>' . HTML::cell($row['evidence_title']) . '</td>';
                                    echo '<td>' . HTML::selectChosen('score_' . $row['id'], $scores, $row['score'], true) . '</td>';
                                    echo '<td><textarea name="comments_' . $row['id'] . '" id="comments_' . $row['id'] . '" style="width: 100%;">' . $row['comments'] . '</textarea></td>';
                                    echo '<td class="delHours">' . $del_hours . '</td>';
                                    echo '<td class="colHoursFollowAssess">';
                                    if ($row['score'] == 5)
                                        $delivery_plan_hours = round($del_hours * $score_percentages["score_5"], 2);
                                    elseif ($row['score'] == 4)
                                        $delivery_plan_hours = round($del_hours * $score_percentages["score_4"], 2);
                                    elseif ($row['score'] == 3)
                                        $delivery_plan_hours = round($del_hours * $score_percentages["score_3"], 2);
                                    elseif ($row['score'] == 2)
                                        $delivery_plan_hours = round($del_hours * $score_percentages["score_2"], 2);
                                    elseif ($row['score'] == 1)
                                        $delivery_plan_hours = $del_hours * $score_percentages["score_1"];

                                    echo $delivery_plan_hours;
                                    echo '</td>';
                                    echo '</tr>';
                                    $delivery_plan_total_fa += $delivery_plan_hours;
                                }

                                echo '<tr><th></th><th></th><th></th><th></th><th></th>';
                                echo '<th class="bg-light-blue" id="colDeliveryPlanTotalBa">' . $delivery_plan_total_ba . '</th>';
                                echo '<input type="hidden" name="txtDeliveryPlanTotalBa" value="' . $delivery_plan_total_ba . '" /> ';
                                echo '<th class="bg-light-blue" id="colDeliveryPlanTotalFa">' . floor($delivery_plan_total_fa) . '</th></tr>';
                                echo '<input type="hidden" name="txtDeliveryPlanTotalFa" value="0" /> ';
                                
                                echo '<tr>';
                                echo '<th colspan="6" class="text-right"> Percentage following assessment (%)</th>';
                                if ($sa->percentage_fa == '')
                                    echo '<th class="text-center"><input readonly maxlength="6" type="text" name="percentage_fa" id="colDeliveryPlanPercentageFa" value="' . round(($delivery_plan_total_fa / $delivery_plan_total_ba) * 100, 0) . '" /></th>';
                                else
                                    echo '<th class="text-center"><input readonly maxlength="6" type="text" name="percentage_fa" id="colDeliveryPlanPercentageFa" value="' . $sa->percentage_fa . '" /></th>';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<th colspan="6" class="text-right"> Duration following assessment (months)</th>';
                                echo '<th class="text-center">';
                                $_recommended_duration = intval($recommended_duration);
                                $_months = [];
								//$max_duration_list_option = $tr->id == 1564 ? $max_duration_list_option+1 : $max_duration_list_option;
                                for ($i = 1; $i <= $max_duration_list_option; $i++) 
                                {
                                    $_months[] = [$i, $i];
                                }
                                echo $sa->duration_fa == '' ? 
                                    HTML::select('overwrite_duration_fa', $_months, $duration_list_selected_option, false) : 
                                    HTML::select('overwrite_duration_fa', $_months, $sa->duration_fa, false);
                                echo '</th>';
                                echo '</tr>';

				                echo '<tr>';
                                echo '<th colspan="6" class="text-right bg-green-gradient">';
                                if($tr->postJuly25Start())
                                {
                                    echo 'Off-the-job hours for <span class="spanDurationInMonths"></span> months';
                                }
                                else
                                {
                                    echo $tr->contracted_hours_per_week < 30 ? 
                                        'Off-the-job hours for <span class="spanDurationInMonths"></span> months<br><small><i class="fa fa-info-circle"></i> based on 20% of Total Contracted Hours</small>' : 
                                        'Off-the-job hours for <span class="spanDurationInMonths"></span> months<br><small><i class="fa fa-info-circle"></i> based on 6 hours per week</small>';
                                }
                                echo '</th>';
                                echo '<td class="tdOtj text-center text-bold"></td>';
                                echo '</tr>';

                                echo '<tr>';
                                echo '<th colspan="6" class="text-right bg-green-gradient"> Select Reduction percentage for TNP1 (price)<br><small><i class="fa fa-info-circle"></i> TNP1 is reduced by at least 50% of the Percentage Reduction following assessment - you may increase the % result if required.</small></th>';
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
                        <table class="table table-bordered" id="tblResult" style="display: none;">
                            <tr>
                                <th style="width: 20%;">Total OTJ hours for Apprenticeship</th>
                                <td style="width: 10%;" id="colDeliveryPlanTotalBa"><?php echo $delivery_plan_total_ba; ?></td>
                                <th style="width: 20%;">Total OTJ hours to be RPL'd</th>
                                <td style="width: 10%;" id="colDeliveryPlanTotalFa1"><?php echo $delivery_plan_total_ba - $delivery_plan_total_fa ?></td>
                                <th style="width: 20%;" style="width: 20%;">OTJ RPL % Total OTJ</th>
                                <td style="width: 10%;" id="colPercentageRpld"><?php echo 100-$sa->percentage_fa; ?>%</td>
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
                                $tnp1_ba_total = floatval(0);
                                $tnp1_fa_total = floatval(0);
                                if (is_array($tnp1_fa)) 
                                {
                                    foreach ($tnp1 as $price_item) 
                                    {
                                        echo $price_item->reduce == 1 ? '<tr class="bg-info trPrice">' : '<tr class="trUnchangedPrice">';
                                        echo '<td>' . $price_item->description . '</td>';
                                        echo '<td class="tdBeforePrice">' . $price_item->cost . '</td>';
                                        $tnp1_ba_total += floatval($price_item->cost);
                                        foreach ($tnp1_fa as $price_item_fa) 
                                        {
                                            if ($price_item_fa->description === $price_item->description) 
                                            {
                                                echo '<td class="tdAfterPrice">' . $price_item_fa->cost . '</td>';
                                                $tnp1_fa_total += floatval($price_item_fa->cost);
                                            }
                                        }
                                        echo '</tr>';
                                    }
									// if($tr->practical_period_start_date >= '2025-07-31') {
                                    //     echo '<tr class="bg-info trPrice">';
                                    //     echo '<td>Assessment Price Element</td>';
                                    //     echo '<td class="tdBeforePrice">' . $sa->epa_price . '</td>';
                                    //     echo '<td class="tdAfterPrice">' . $sa->epa_price_fa . '</td>';
                                    //     echo '</tr>';
                                    //     $tnp1_ba_total += floatval($sa->epa_price);
                                    //     $tnp1_fa_total += floatval($sa->epa_price_fa);
                                    // }
                                    $tnp1_fa_total = ceil($tnp1_fa_total);
                                    echo '<tr><th align="right">Total</th><td>' . ceil($tnp1_ba_total) . '</td><td class="tdAfterPriceTotal">' . $tnp1_fa_total . '</td></tr>';
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
                                <div class="form-group">
                                    <label for="lock_for_learner" class="col-sm-5 control-label fieldLabel_compulsory">Lock for Learner:</label>
                                    <div class="col-sm-7">
                                        <?php echo HTML::select('lock_for_learner', [[0, 'No'], [1, 'Yes']], $sa->lock_for_learner, false); ?>
                                    </div>
                                </div>
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
					<br>
                                        <span class="text-info small">
                                            <i class="fa fa-info-circle"></i> Please only set it to "Yes" when there are no more changes required for Skills Scan.
                                        </span>
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
                                                <th>Training Provider</th>
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

    <span onclick="fnScrollToTop()" title="Go to top" id="btn-scroll-up" class="btn btn-success btn-sm btn-scroll-up">
        <i class="fa fa-arrow-up"></i>
    </span>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/common.js" type="text/javascript"></script>

    <script language="JavaScript">

        var phpProviderSignature = '<?php echo is_null($sa->provider_sign) ? $_SESSION['user']->signature : $sa->provider_sign; ?>';
        var phpRecommendedDuration = <?php echo intval($recommended_duration); ?>;

        $(function() {
            $("select[name^=score_]").on('change', function() {
                var delHours = $(this).closest('tr').find('.delHours').html();
                delHours = parseFloat(delHours);// parseInt(delHours);
                var score = parseInt(this.value);

                var dph = 0;
                if (score == 5)
                    dph = delHours * <?php echo $score_percentages["score_5"]; ?>;
                else if (score == 4)
                    dph = delHours * <?php echo $score_percentages["score_4"]; ?>;
                else if (score == 3)
                    dph = delHours * <?php echo $score_percentages["score_3"]; ?>;
                else if (score == 2)
                    dph = delHours * <?php echo $score_percentages["score_2"]; ?>;
                else if (score == 1)
                    dph = delHours * <?php echo $score_percentages["score_1"]; ?>;

                $(this).closest('tr').find('.colHoursFollowAssess').html(dph.toFixed(2));
                
                updateDeliveryPlanTotalFa();
            });
            
            refreshOtj(); 
        });

        function updateDeliveryPlanTotalFa() 
        {
            var sum = 0;

            $(".colHoursFollowAssess").each(function() {
                var value = $(this).html();
                if (!isNaN(value) && value.length != 0) 
                {
                    sum = parseFloat(sum) + parseFloat(value);
                }
            });
			sum = Math.floor(sum);

            $('th#colDeliveryPlanTotalFa').html(sum.toFixed(2));
            $('td#colDeliveryPlanTotalFa1').html(sum.toFixed(2));
            
            updateDeliveryPlanPercentageFa(sum);
        }

        function updateDeliveryPlanPercentageFa(DeliveryPlanTotalFa) 
        {
            var ba = parseFloat($('th#colDeliveryPlanTotalBa').html());
            var aa = Math.floor(DeliveryPlanTotalFa);

            var p = (aa / ba) * 100;

            $('#colDeliveryPlanPercentageFa').val( parseFloat(p).toFixed(2) );
            $('#colPercentageRpld').html( parseFloat(100-p).toFixed(2) );

            $('input[name="txtDeliveryPlanTotalFa"]').val(aa);

            var duration = phpRecommendedDuration * ( p / 100 );

            $('#overwrite_duration_fa').val( duration.toFixed(0) );

	        refreshOtj();
        }

        function refreshOtj()
        {
            var durationInMonths = $("select[name=overwrite_duration_fa]").val();

            var otj = 0;

            $.ajax({ 
                type: 'GET', 
                url: 'do.php?_action=ajax_helper&subaction=calculateOtj', 
                data: { tr_id: '<?php echo $tr->id; ?>', duration_in_months: durationInMonths, percentage_fa: $('#colDeliveryPlanPercentageFa').val() },  
                dataType: 'json',
                async: false,
                success: function (data) { 
                    otj = parseFloat(data.off_the_job_hours);
                },
                error: function (error) { 
                    console.log(error);
                    //alert(error);
                }
            });
            
            $("input[name=off_the_job_hours_based_on_duration]").val(otj);

            $("td.tdOtj").html( otj );

            $("span.spanDurationInMonths").html(durationInMonths);

            var price_reduction_percentage = document.getElementById("price_reduction_percentage");

            var percentage = $("#colDeliveryPlanPercentageFa").val();
            
            ajaxPopulateSelect(price_reduction_percentage, 'do.php?_action=ajax_load_account_manager&subaction=load_price_reduction_ddl&percentage=' + encodeURIComponent(percentage));
        }

        function overwrite_duration_fa_onchange(ele)
        {
            refreshOtj();
        }
        
        function save() 
        {
            var frmSkillsAnalysis = document.forms["frmSkillsAnalysis"];

	        if(frmSkillsAnalysis.price_reduction_percentage.value == '')
            {
                alert('Please select the price reduction percentage.');
                frmSkillsAnalysis.price_reduction_percentage.focus();
                return;
            }

            frmSkillsAnalysis.submit();
        }

        function price_reduction_percentage_onchange(ele)
        {
			var tnp2 = <?php echo $sa->epa_price ? $sa->epa_price : 0; ?>;
            var selected_price_percentage = ele.value == '' ? 0 : ele.value;
			var tnp1 = 0;
			$("tr.trPrice").each(function(index, element){
                var beforePrice = $(element).find("td.tdBeforePrice").text();
				tnp1 += parseFloat(beforePrice);
            });
			
            selected_price_percentage = parseFloat(selected_price_percentage);

            var afterPriceTotal = parseFloat(0);
			
			var total_reduction_amount = ((tnp1 + tnp2)*selected_price_percentage)/100;

			console.log('tnp1: ', tnp1);
			console.log('tnp2: ', tnp2);
			console.log('selected_price_percentage: ', selected_price_percentage);
			console.log('total_reduction_amount: ', total_reduction_amount);


            $("tr.trPrice").each(function(index, element){
                var beforePrice = $(element).find("td.tdBeforePrice").text();

                if(beforePrice != '')
                {
                    //beforePrice = parseFloat(beforePrice);

                    //var afterPrice = parseFloat( (beforePrice + tnp2) -( ( beforePrice + tnp2) * selected_price_percentage / 100 ) - tnp2 );

					var afterPrice = beforePrice - total_reduction_amount * (beforePrice/tnp1);
                    $(element).find("td.tdAfterPrice").text(parseFloat(afterPrice).toFixed(2));

                    afterPriceTotal = afterPriceTotal + afterPrice;
                }
            });

            $("tr.trUnchangedPrice").each(function(index, element){
                var beforePrice = $(element).find("td.tdBeforePrice").text();

                if(beforePrice != '')
                {
                    afterPriceTotal += parseFloat(beforePrice);
                }
            });

            afterPriceTotal = Math.ceil(afterPriceTotal);

            $("td.tdAfterPriceTotal").text(afterPriceTotal);
        }

        function getSignature(user) 
        {
            if (window.phpProviderSignature == '') {
                $('#signature_text').val('');
                $("#panel_signature").data('panel', 'provider').dialog("open");
                return;
            }
            $('#img_provider_sign').attr('src', 'do.php?_action=generate_image&' + window.phpProviderSignature);
            $('#provider_sign').val(window.phpProviderSignature);

            return;
        }

    </script>

    <script>
        let btnScrollUp = document.getElementById("btn-scroll-up");
        window.onscroll = function() {fnShowScrollToTop()};

        function fnShowScrollToTop() 
        {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) 
            {
                btnScrollUp.style.display = "block";
            } 
            else 
            {
                btnScrollUp.style.display = "none";
            }
        }

        function fnScrollToTop() 
        {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>
</body>

</html>