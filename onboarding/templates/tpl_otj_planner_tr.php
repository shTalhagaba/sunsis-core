
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>OTJ Planner</title>
    <link rel="stylesheet" href="css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style type="text/css">
        input[type="text"] {font-weight:bold; font-size: x-large;}
    </style>

</head>
<body class="table-responsive">
<div class="row">
    <div class="col-sm-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">OTJ Planner for <?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></div>
            <div class="ButtonBar">
                <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
                <span class="btn btn-xs btn-default" onclick="save();"><i class="fa fa-save"></i> Save Information </span>
            </div>
            <div class="ActionIconBar">
                <span class="btn btn-sm btn-info fa fa-refresh" onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"></span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-6">
            <h5 class="lead text-bold"><?php echo $framework->title; ?></h5>
            <span class="text-bold">Learner Name: </span><span class="text-info text-bold"><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></span><br>
            <span class="text-bold">OTJ hours to be completed: </span>
            <span class="text-info text-bold">
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
            </span><br>
            <span class="text-bold">OTJ hours delivery planner: </span><span class="text-info text-bold" id="otj_delivery_planner"></span>
        </div>
        <div class="col-sm-6">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <form name="frmProgOtj" method="POST" action="do.php?_action=otj_planner_tr">
                <input type="hidden" name="subaction" value="save" />
                <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />
                <div class="">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr class="bg-info">
                                <th></th>
                                <th>KSB</th>
                                <?php 
                                foreach(OnboardingHelper::generateOtjColumnsHeader($link, $tr->framework_id) AS $_c)
                                {
                                    echo '<th>' . $_c . '</th>';
                                }
                                ?>
                                <th>Behaviours</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $otj_tr_template_sections = DAO::getResultset($link, "SELECT * FROM otj_tr_template_sections WHERE tr_id = '{$tr->id}' ORDER BY section_id", DAO::FETCH_ASSOC);

                        foreach($otj_tr_template_sections AS $otj_tr_template_section)
                        {
                            $subsections_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM otj_tr_template_subsections WHERE section_id = '{$otj_tr_template_section['section_id']}'");
                            if($subsections_count > 1)
                            {
                                $row_span = (int)$subsections_count+1;
                                echo '<tr>';

                                echo '<td class="bg-info" rowspan="' . $row_span . '">' . $otj_tr_template_section['section_desc'] . '</td>';
        
                                echo '</tr>';
                            }

                            $otj_tr_template_subsections = DAO::getResultset($link, "SELECT * FROM otj_tr_template_subsections WHERE section_id = '{$otj_tr_template_section['section_id']}' ORDER BY subsection_id", DAO::FETCH_ASSOC);
                            foreach($otj_tr_template_subsections AS $otj_tr_template_subsection)
                            {
                                echo '<tr>';

                                echo $subsections_count > 1 ? '' : '<td class="bg-info">' . $otj_tr_template_section['section_desc'] . '</td>';
                                
                                echo '<td>' . $otj_tr_template_subsection['subsection_desc'] . '</td>';
    
                                $otj_tr_template_activities = DAO::getResultset($link, "SELECT * FROM otj_tr_template_activities WHERE subsection_id = '{$otj_tr_template_subsection['subsection_id']}' ORDER BY activity_id", DAO::FETCH_ASSOC);
                                foreach($otj_tr_template_activities AS $otj_prog_template_activity)
                                {
                                    echo '<td>';
                                    echo '<textarea name="activity_'.$otj_prog_template_activity['activity_id'].'" maxlength="500" rows="7">'.$otj_prog_template_activity['activity_desc'].'</textarea>';
                                    echo '</td>';
                                }
                                echo '</tr>';
                            }

                            echo '<tr class="bg-warning">';
                            echo '<td></td>';
                            echo '<td></td>';
                            for($col = 2; $col <= OnboardingHelper::colsOfStandard($link, $tr->framework_id)+1; $col++)
                            {
				                if($col < 16)
                                	echo '<td><input onkeypress="return numbersonlywithpoint();" maxlength="5" name="txt_section_' . $otj_tr_template_section['section_id'] . '_col_' . $col . '" class="form-control" type="text" value="'.$otj_tr_template_section['col_'.$col.'_otj'].'" /></td>';
				                else    
                                    echo '<td><input onkeypress="return numbersonlywithpoint();" maxlength="5" name="txt_section_' . $otj_tr_template_section['section_id'] . '_col_' . $col . '" class="form-control" type="text" value="'.$otj_tr_template_section['col_'.$col.'_otj'].'" /></td>';
                            }
                            echo '</tr>';
                        }
                        if(isset($otj_tr_template_section))
                        {
                            echo '<tr class="bg-black">';
                            echo '<td>Commulative OTJ</td>';
                            echo '<td></td>';
                            for($col = 2; $col <= OnboardingHelper::colsOfStandard($link, $tr->framework_id)+1; $col++)
                            {
                                echo '<td><input readonly="true" name="total_section_' . $otj_tr_template_section['section_id'] . '_col_' . $col . '" class="form-control" type="text" value="0" /></td>';
                            }
                            echo '</tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>

                <table style="margin-top: 5px;" class="table table-bordered table-condensed">
                    <caption class="bg-gray-light text-bold" style="padding: 5px;">Signatures:</caption>
                    <tr>
                        <th>Learner</th>
                        <th>Employer</th>
                        <th>Provider</th>
                    </tr>
                    <tr>
                        <td>
                            <?php if( isset($otj_signatures->learner_sign) && $otj_signatures->learner_sign != '' ) {?> 
                                <img src="do.php?_action=generate_image&<?php echo $otj_signatures->learner_sign ?>" style="border: 2px solid;border-radius: 15px;" /><br>
                                <?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?><br>
                                <?php echo Date::toShort($otj_signatures->learner_sign_date); ?>
                            <?php } else {?> 
                                <img src="do.php?_action=generate_image&title=Not yet signed&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" />
                            <?php } ?>
                        </td>
                        <td>
                            <?php if( isset($otj_signatures->employer_sign) && $otj_signatures->employer_sign != '' ) {?> 
                                <img src="do.php?_action=generate_image&<?php echo $otj_signatures->employer_sign ?>" style="border: 2px solid;border-radius: 15px;" /><br>
                                <?php echo $otj_signatures->employer_sign_name; ?><br>
                                <?php echo Date::toShort($otj_signatures->employer_sign_date); ?>
                            <?php } else {?> 
                                <img src="do.php?_action=generate_image&title=Not yet signed&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" />
                            <?php } ?>
                        </td>
                        <td>
                            <?php if( isset($otj_signatures->provider_sign) && $otj_signatures->provider_sign != '' ) {?> 
                                <img src="do.php?_action=generate_image&<?php echo $otj_signatures->provider_sign ?>" style="border: 2px solid;border-radius: 15px;" /><br>
                                <?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$otj_signatures->provider_sign_id}'"); ?><br>
                                <?php echo Date::toShort($otj_signatures->provider_sign_date); ?>
                            <?php } else {?> 
                                <span class="btn btn-info" onclick="getSignature('manager');">
                                    <img id="img_provider_sign" src="do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" />
                                    <input type="hidden" name="provider_sign" id="provider_sign" value="" />
                                </span>
                            <?php } ?>
                        </td>
                    </tr>
                    
                </table>
                <?php 
		if( 
			isset($otj_signatures->learner_sign) && $otj_signatures->learner_sign != '' &&
			isset($otj_signatures->employer_sign) && $otj_signatures->employer_sign != '' &&
			isset($otj_signatures->provider_sign) && $otj_signatures->provider_sign != ''
		) 
		{
		?>
                <button class="btn btn-success btn-block btn-sm disabled" type="button" disabled><i class="fa fa-save"></i> Save Information</button>
		<?php } else {?>
		<button class="btn btn-success btn-block btn-sm" type="button" onclick="save();"><i class="fa fa-save"></i> Save Information</button>
		<?php } ?>
                <p></p>
            </form>
        </div>
    </div>

</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript">
refreshGridTotals();

$('input[name^="txt_section_"]').on('change', function(){
    refreshGridTotals();
});

function refreshGridTotals()
{
    total = 0;
    $('input[name^="total_section_"]').each(function(index, elem){
        var name_parts = elem.name.split('_');
        var col = name_parts[4];

        sum = 0;
        $("input[name$='_col_"+col+"']").not('input[name^="total_section_"]').each(function(){
            sum += Number( $(this).val() );
        });

        total += sum;
        $(this).val( sum );
    }); 

    $("span#otj_delivery_planner").html(total);
}

function save()
{
    document.forms["frmProgOtj"].submit();
}

var phpProviderSignature = '<?php echo (isset($otj_signautres->provider_sign) && $otj_signautres->provider_sign != '') ? $otj_signautres->provider_sign : $_SESSION['user']->signature; ?>';

function getSignature(user)
{
    if(window.phpProviderSignature == '')
    {
        $('#signature_text').val($('#provider_sign_name').val());
        $('#signature_text').val('');
        $( "#panel_signature" ).data('panel', 'provider').dialog( "open");
        return;
    }
    $('#img_provider_sign').attr('src', 'do.php?_action=generate_image&'+window.phpProviderSignature);
    $('#provider_sign').val(window.phpProviderSignature);
}
</script>

</body>
</html>