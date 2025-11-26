<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Skills Analysis</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .modal-dialog {
            width: 75%;
            margin: 0 auto;
        }

        html,
        body {
            height: 100%;
            font-size: medium;
        }
        textarea, input[type=text] {
            border:1px solid #3366FF;
            border-radius: 5px;
            border-left: 5px solid #3366FF;
        }
        .sigbox {
            border-radius: 15px;
            border: 1px solid #EEE;
            cursor: pointer;
        }
        .sigboxselected {
            border-radius: 25px;
            border: 2px solid #EEE;
            cursor: pointer;
            background-color: #d3d3d3;
        }
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>
</head>

<body class="table-responsive">
    <br>

    <div class="content-wrapper">
    
        <section class="content">
            <div class="container-fluid">
                <div class="row vertical-center-row">
                    <div class="col-sm-12" style="background-color: white;">
                        <p><br></p>

                        <div class="row">
                            <div class="col-sm-4"><img class="img-responsive" src="images/logos/app_logo.jpg" /></div>
                            <div class="col-sm-4"></div>
                            <div class="col-sm-4"><img class="img-responsive" src="<?php echo $providerLogo; ?>" /></div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <p class="lead text-bold text-center">Skills Analysis</p>
                            </div>
                        </div>

                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmSkillsAnalysis">
                            <input type="hidden" name="_action" value="save_skills_scan_employer_view">
                            <input type="hidden" name="id" value="<?php echo $sa->id; ?>">
                            <input type="hidden" name="key" value="<?php echo $key; ?>">

                            <div class="row">
                                <div class="col-sm-12">
                                    <h4><strong>Apprenticeship Details</strong></h4>
                                    <table class="table table-bordered table-condensed">
                                        <tr><th>Apprentice Name:</th><td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td></tr>
                                        <tr><th>Employer Name:</th><td><?php echo $employer->legal_name; ?></td></tr>
                                        <tr><th>Training Provider Name:</th><td><?php echo $tr->getProviderLegalName($link); ?></td></tr>
                                        <tr>
                                            <th>Standard Title & Level:</th>
                                            <td>
                                                <?php 
                                                echo $framework->getStandardCodeDesc($link) . '<br>'; 
                                                echo DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';");
                                                ?>
                                            </td>
                                        </tr>
                                        <tr><th>Start Date of Practical Period:</th><td><?php echo Date::toShort($tr->practical_period_start_date); ?></td></tr>
                                        <tr><th>Planned End Date of Practical Period:</th><td><?php echo Date::toShort($tr->practical_period_end_date); ?></td></tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="box box-info box-solid">
                                        <div class="box-header">
                                            <span class="box-title">Skills Analysis Result</span>
                                        </div>
                                        <div class="box-body">
                                            <div class="table-responsive">
                                                
                                                <span id="myBtn" class="btn btn-xs btn-info"><i class="fa fa-folder-open"></i> Click to view scores</span>

                                                <table class="table table-bordered" style="font-size: medium;">
                                                    <tr>
                                                        <td>
                                                            <span class="text-info">Percentage of assessment: </span><?php echo $sa->percentage_fa; ?>%<br>
                                                            <span class="text-info">Price reduction percentage following assessment: </span><?php echo $sa->price_reduction_percentage; ?>%<br>
                                                            <span class="text-info">Duration following assessment: </span><?php echo $sa->duration_fa; ?> months<br>
                                                            <span class="text-info">OTJ hours: </span><?php echo $tr->contracted_hours_per_week >= 30 ? $tr->off_the_job_hours_based_on_duration : $tr->part_time_otj_hours; ?> hours<br>
                                                            <span class="text-info">Training Provider Comments: </span><?php echo nl2br($sa->rationale_by_provider); ?><br>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered small">
                                                                    <!-- <caption class="text-bold">TNP 1</caption> -->
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
                                                                    if (is_array($tnp1_fa)) {
                                                                        foreach ($tnp1 as $price_item) {
                                                                            echo '<tr>';
                                                                            echo '<td>' . $price_item->description . '</td>';
                                                                            echo '<td>' . $price_item->cost . '</td>';
                                                                            $tnp1_ba_total += floatval($price_item->cost);
                                                                            foreach ($tnp1_fa as $price_item_fa) {
                                                                                if ($price_item_fa->description === $price_item->description) {
                                                                                    echo '<td>' . $price_item_fa->cost . '</td>';
                                                                                    $tnp1_fa_total += floatval($price_item_fa->cost);
                                                                                }
                                                                            }
                                                                            echo '</tr>';
                                                                        }
                                                                        $tnp1_fa_total = ceil($tnp1_fa_total);
                                                                        echo '<tr><th align="right">Total Price</th><td>' . ceil($tnp1_ba_total) . '</td><td>' . $tnp1_fa_total . '</td></tr>';
                                                                    }
                                                                    ?>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <!-- <tr>
                                                        <td>
                                                            <span class="text-info">TNP 1: </span>&pound;<?php echo $tnp1_fa_total; ?><br>
                                                            <span class="text-info">TNP 2: </span>&pound;<?php echo $tr->epa_price; ?><br>
                                                            <span class="text-info">Total TNP: </span>&pound;<?php echo $tnp1_fa_total + $tr->epa_price; ?><br>
                                                        </td>
                                                    </tr> -->
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <div class="form-group">
                                        <label for="employer_comments">Enter your comments</label>
                                        <textarea class="form-control compulsory" name="employer_comments" id="employer_comments" maxlength="1800"><?php echo $sa->employer_comments; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="employer_sign_name">Enter your name <span class="text-info small"><i class="fa fa-info-circle"></i> Please enter your name and not the company or learner name</span></label>
                                        <input type="text" class="form-control compulsory" name="employer_sign_name" id="employer_sign_name" value="" placeholder="Please enter your name" maxlength="70" />
                                    </div>
                                    <div class="form-group">
                                        <span class="btn btn-info" onclick="getSignature('manager');">
                                            <img id="img_employer_sign" src="do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" />
                                            <input type="hidden" name="employer_sign" id="employer_sign" value="" />
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <p><br></p>
                                    <span class="btn btn-block btn-success btn-lg" onclick="submitInformation();">
                                        <i class="fa fa-save"></i> Submit Information
                                    </span>
                                    <p><br></p>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <footer class="main-footer">
        <div class="pull-left">
            <img width="230px" src="<?php echo $providerLogo; ?>" />
        </div>
        <div class="pull-right">
            <img src="images/logos/SUNlogo.png" />
        </div>
    </footer>

    <div id="panel_signature" title="Signature Panel">
        <div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter your name, then select the
            signature font you like and press "Add".
        </div>
        <div>
            <table class="table row-border">
                <tr>
                    <td>Enter your name</td>
                    <td><input type="text" id="signature_text" onkeypress="return onlyAlphabets(event,this);" /> &nbsp; <span class="btn btn-xs btn-primary" onclick="refreshSignature();">Generate</span> </td>
                </tr>
                <tr>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img1" src=""/></td>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img2" src=""/></td>
                </tr>
                <tr>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img3" src=""/></td>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img4" src=""/></td>
                </tr>
                <tr>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img5" src=""/></td>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img6" src=""/></td>
                </tr>
                <tr>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img7" src=""/></td>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img8" src=""/></td>
                </tr>
            </table>
        </div>
    </div>


    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding:5px 5px;">
                    <p class="text-center text-bold lead">
                        Skills Analysis Details of <?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?>
                    </p>
                </div>
                <div class="modal-body" style="padding:10px 10px; max-height: 650px; overflow-y: scroll;">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <?php if (is_array($sa->ksb)) {
                                        echo "<thead>Total Questions: " . count($sa->ksb) . "</thead>";
                                    } ?>
                                    <tr class="bg-success">
                                        <th>#</th>
                                        <th>Question</th>
                                        <th>Learner Comments</th>
                                        <th>Learner Score (1 to 5)</th>
                                    </tr>
                                    <?php
                                    $dh_total = 0;
                                    $dh_scored = 0;
                                    $question_counter = 0;
                                    $scores_list = $sa->getRplPercentages();
                                    foreach ($sa->ksb as $entry) 
                                    {
                                        $row_score = 0;
                                        $scores_list_key = "score_{$entry['score']}";
                                        echo '<tr>';
                                        echo '<td>' . ++$question_counter . '/' . count($sa->ksb) . '</td>';
                                        echo '<td class="small">' . html_entity_decode($entry['evidence_title']) . '</td>';
                                        echo '<td class="small">' . $entry['comments'] . '</td>';
                                        echo '<td>' . $entry['score'] . '</td>';
                                        $p = !isset($scores_list[$scores_list_key]) ? 0 : round(100 - ($scores_list[$scores_list_key] * 100), 3);

                                        if (intval($entry['score']) > 0) {
                                            $row_score = round(floatval($entry['del_hours']) * $scores_list[$scores_list_key], 2);
                                        } else {
                                            $row_score = 0;
                                        }

                                        echo '</tr>';
                                        $dh_total += floatval($entry['del_hours']);
                                        $dh_scored += $row_score;
                                    }
                                    $dh_scored = ceil($dh_scored);
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"> Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

   

<script type="text/javascript">

    var fonts = Array("Little_Days.ttf","ArtySignature.ttf","Signerica_Medium.ttf","Champignon_Alt_Swash.ttf","Bailey_MF.ttf","Carolina.ttf","DirtyDarren.ttf","Ruf_In_Den_Wind.ttf");
    var sizes = Array(30,40,15,30,30,30,25,30);

    $(function() {

        $("#myBtn").click(function(){
			$("#myModal").modal();
		});

        $("input[type=checkbox]:checked").each(function() {
            $(this).closest('tr').addClass('bg-green');
        });

        $('.clsICheck').each(function(){
            var self = $(this),
                label = self.next(),
                label_text = label.text();

            label.remove();
            self.iCheck({
                checkboxClass: 'icheckbox_line-orange',
                insert: '<div class="icheck_line-icon"></div>' + label_text
            });
        });

        //$('input[class=radioICheck]').iCheck({radioClass: 'iradio_square-green', increaseArea: '20%'});

        $( "#panel_signature" ).dialog({
            autoOpen: false,
            modal: true,
            draggable: false,
            width: "auto",
            height: 500,
            buttons: {
                'Create': function() {
                    var panel = $(this).data('panel');
                    if($('#signature_text').val() == '')
                    {
                        alert('Please input name/initials to generate signature.');
                        $('#signature_text').focus();
                        return;
                    }
                    if($('.sigboxselected').children('img')[0] === undefined)
                    {
                        alert('Please select your font');
                        return;
                    }
                    var sign_field = '';
                    if(panel == 'manager')
                    {
                        sign_field = 'employer_sign';
                    }
                    $("#img_"+sign_field).attr('src', $('.sigboxselected').children('img')[0].src);
                    var _link = $('.sigboxselected').children('img')[0].src;
                    _link = _link.split('&');
                    $("#"+sign_field).val(_link[1]+'&'+_link[2]+'&'+_link[3]);
                    if($('#'+sign_field).val() == '')
                    {
                        alert('Please create your signature');
                        return;
                    }

                    $(this).dialog('close');
                },
                'Cancel': function() {$(this).dialog('close');}
            }
        });

    });

    function getSignature(user)
    {
        $('#signature_text').val($('#employer_sign_name').val());
        $( "#panel_signature" ).data('panel', 'manager').dialog( "open");
        return;
    }

    function onlyAlphabets(e, t)
    {
        try {
            if (window.event) {
                var charCode = window.event.keyCode;
            }
            else if (e) {
                var charCode = e.which;
            }
            else { return true; }
            if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 32 || charCode == 39 || charCode == 45 || charCode == 8 || charCode == 46)
                return true;
            else
                return false;
        }
        catch (err) {
            alert(err.Description);
        }
    }

    function SignatureSelected(sig)
    {
        $(".sigboxselected").attr("class", "sigbox");
        sig.className = "sigboxselected";
    }

    function refreshSignature()
    {
        for(var i = 1; i <= 8; i++)
            $("#img"+i).attr('src', 'images/loading.gif');

        for(var i = 0; i <= 7; i++)
            $("#img"+(i+1)).attr('src', 'do.php?_action=generate_image&title='+$("#signature_text").val()+'&font='+fonts[i]+'&size='+sizes[i]);
    }

    function submitInformation() 
    {
        var frmSkillsAnalysis = document.forms['frmSkillsAnalysis'];

        var employer_sign = frmSkillsAnalysis.elements["employer_sign"];

        if (employer_sign.value.trim() == '') {
            alert('Please provide your signature.');
            return;
        }

        frmSkillsAnalysis.submit();
    }

    

</script>


</body>

</html>