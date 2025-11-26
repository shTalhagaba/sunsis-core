<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Learning Style Assessment Questionnaire</title>
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
        input[type=checkbox], input[type=radio] {
            transform: scale(1.4);
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

<body>


    <br>

    <div class="content-wrapper">

        <section class="content">
            <div class="container-fluid container-table">
                <div class="row vertical-center-row">
                    <div class="col-sm-12" style="background-color: white;">
                        <p><br></p>

                        <div class="row">
                            <div class="col-sm-4">
                                <?php if(!$tr->isNonApp($link)){ ?>
                                <img class="img-responsive" src="images/logos/app_logo.jpg" />
                                <?php } ?>
                            </div>
                            <div class="col-sm-4"></div>
                            <div class="col-sm-4"><img class="img-responsive" src="<?php echo $ob_header_image1; ?>" /></div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <p class="lead text-bold text-center">Learning Style Assessment Questionnaire</p>
                            </div>
                        </div>

                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmLearningStyle">
                            <input type="hidden" name="_action" value="save_learning_style_assessment">
                            <input type="hidden" name="id" value="<?php echo $tr->id; ?>">
                            <input type="hidden" name="key" value="<?php echo $key; ?>">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="bg-gray">Learner:</th>
                                                <td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="text-bold text-green">Select the answer that most represents how you generally behave.</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <table class="table table-bordered">
                                        <?php 
                                        
                                        $result = DAO::getResultset($link, "SELECT * FROM lookup_learning_style_assessment ORDER BY id", DAO::FETCH_ASSOC);
                                        foreach($result AS $row)
                                        {
                                            $question_id = 'question_' . $row['id'];
                                            $question_options = [
                                                ['a', $row['opt_a'], ''],
                                                ['b', $row['opt_b'], ''],
                                                ['c', $row['opt_c'], ''],
                                            ];
                                            echo '<tr>';
                                                echo '<td>';
                                                    echo '<div class="callout callout-default">';
                                                        echo '<p class="text-bold text-info">' . $row['question'] . '</p>';
                                                        echo '<table class="table" style="margin-left: 10px;">';
                                                        foreach($question_options AS $opt)
                                                        {
                                                            $checked = ( isset($form_data->$question_id) && $form_data->$question_id == $opt[0] ) ? 'checked' : '';
                                                            echo '<tr>';
                                                            echo '<td>' . strtoupper($opt[0]) . ')</td>';
                                                            echo '<td>';
                                                            echo '<input type="radio" name="'.$question_id.'" value="'.$opt[0].'" ' . $checked . ' /> &nbsp; ' . $opt[1];
                                                            echo '</td>';
                                                            echo '</tr>';
                                                        }
                                                        echo '</table>';
                                                    echo '</div>';    
                                                echo '</td>';
                                            echo '</tr>';
                                        }
                                        ?>
                                    </table>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <table class="table table-bordered">
                                        <caption class="text-info text-center captionOutcome"><i class="fa fa-info-circle"></i> Answer all questions to view the resutls here.</caption>
                                        <tr><th>A's</th><th>B's</th><th>C's</th></tr>
                                        <tr><td id="tdA"></td><td id="tdB"></td><td id="tdC"></td></tr>
                                        <tr><th id="tdOutcome" colspan="3"></th></tr>
                                    </table>
                                    <hr>
                                    <div class="text-info">
                                        <p>Some people find that their learning style may be a blend of two or three styles, in this case read about the styles that apply to you in the explanation below.</p>
                                        <p>When you have identified your learning style(s), read the learning styles explanations and consider how this might help you to identify learning and development that best meets your preference(s).</p>
                                        <p><br></p>
                                        <p>Now see the Visual, Auditory, and Kinesthetic (Tactile) Learning Styles Explanation.</p>
                                        <p><br></p>
                                        <p class="lead text-center text-bold">Learning Styles Explanation</p>
                                        <p>The VAK learning styles model suggests that most people can be divided into one of three preferred styles of learning. These three styles are as follows, (and there is no right or wrong learning style):</p>
                                        <ul style="margin-left: 10px;">
                                            <li>Someone with a <span class="text-bold">Visual</span> learning style has a preference for seen or observed things, including pictures, diagrams, demonstrations, displays, handouts, films, flip-chart, etc. These people will use phrases such as 'show me', 'let's have a look at that' and will be best able to perform a new task after reading the instructions or watching someone else do it first. These are the people who will work from lists and written directions and instructions.</li>
                                            <li>Someone with an <span class="text-bold">Auditory</span> learning style has a preference for the transfer of information through listening: to the spoken word, of self or others, of sounds and noises. These people will use phrases such as 'tell me', 'let's talk it over' and will be best able to perform a new task after listening to instructions from an expert. These are the people who are happy being given spoken instructions over the telephone, and can remember all the words to songs that they hear!</li>
                                            <li>Someone with a <span class="text-bold">Kinaesthetic</span> learning style has a preference for physical experience - touching, feeling, holding, doing, and practical hands-on experiences. These people will use phrases such as 'let me try', 'how do you feel?' and will be best able to perform a new task by going ahead and trying it out, learning as they go. These are the people who like to experiment, hands-on, and never look at the instructions first!</li>
                                        </ul>
                                        <p>People commonly have a main preferred learning style, but this will be part of a blend of all three. Some people have a very strong preference; other people have a more even mixture of two or less commonly, three styles.</p>
                                        <p>When you know your preferred learning style(s) you understand the type of learning that best suits you. This enables you to choose the types of learning that work best for you.</p>
                                        <p><span class="text-bold">There is no right or wrong learning style.</span> The point is that there are types of learning that are right for your own preferred learning style.</p>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-12"><hr></div>
                            </div>

                            <div class="row">
                                <div class="col-sm-3">
                                    <?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?>
                                </div>
                                <div class="col-sm-6 text-center">
                                    <span class="btn btn-info" onclick="getSignature();">
                                        <img id="img_learner_sign" src="do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=20" style="border: 2px solid;border-radius: 15px; width: 100%;" />
                                        <input type="hidden" name="learner_sign" id="learner_sign" value="" />
                                    </span>
                                </div>
                                <div class="col-sm-3">
                                    <?php echo date('d/m/Y'); ?>
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
            <table class="table" style="border-collapse: separate; border-spacing: 0.5em;">
                <tr>
                    <td><img width="230px" src="images/logos/<?php echo SystemConfig::getEntityValue($link, 'logo'); ?>" /></td>
                </tr>
            </table>
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
                    <td><input maxlength="23" type="text" id="signature_text" value="<?php echo $ob_learner->firstnames; ?>" onkeypress="return onlyAlphabets(event,this);"/>
                        &nbsp; <span class="btn btn-sm btn-primary" onclick="refreshSignature();">Generate</span>
                    </td>
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


    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        var phpLearnerSignature = '<?php echo $tr->getSign($link); ?>';

        $(function() {
            $( "#panel_signature" ).dialog({
                autoOpen: false,
                modal: true,
                draggable: false,
                width: "auto",
                height: 500,
                buttons: {
                    'Add': function() {
                        $("#img_learner_sign").attr('src',$('.sigboxselected').children('img')[0].src);
                        $("#learner_sign").val($('.sigboxselected').children('img')[0].src);
                        $(this).dialog('close');
                    },
                    'Cancel': function() {$(this).dialog('close');}
                }
            });
        });

        function getSignature(user) {
            if(window.phpLearnerSignature != '')
            {
                $('#img_learner_sign').attr('src', 'do.php?_action=generate_image&' + window.phpLearnerSignature);
                $('#learner_sign').val(window.phpLearnerSignature);
            }
            else
            {
                $( "#panel_signature" ).dialog( "open");
            }
            return;
        }

        function submitInformation() {
            if($("input[name^=question_]:checked").length < 16)
            {
                alert('Please answer all questions');
                return;
            }

            var frmLearningStyle = document.forms['frmLearningStyle'];

            var learner_sign = frmLearningStyle.elements["learner_sign"];

            if (learner_sign.value.trim() == '') {
                alert('Please provide your signature.');
                return;
            }

            frmLearningStyle.submit();
        }



        var fonts = Array("Little_Days.ttf","ArtySignature.ttf","Signerica_Medium.ttf","Champignon_Alt_Swash.ttf","Bailey_MF.ttf","Carolina.ttf","DirtyDarren.ttf","Ruf_In_Den_Wind.ttf");
        var sizes = Array(15,40,15,20,20,20,15,30);

        function refreshSignature()
        {
            for(var i = 1; i <= 8; i++)
                $("#img"+i).attr('src', 'images/loading.gif');

            for(var i = 0; i <= 7; i++)
                $("#img"+(i+1)).attr('src', 'do.php?_action=generate_image&title='+$("#signature_text").val()+'&font='+fonts[i]+'&size='+sizes[i]);
        }

        function loadDefaultSignatures()
        {
            for(var i = 1; i <= 8; i++)
                $("#img"+i).attr('src', 'images/loading.gif');

            for(var i = 0; i <= 7; i++)
                $("#img"+(i+1)).attr('src', 'do.php?_action=generate_image&title=Signature'+'&font='+fonts[i]+'&size='+sizes[i]);
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
            $('.sigboxselected').attr('class','sigbox');
            sig.className = "sigboxselected";
        }

        $(function(){
            $("input[type=radio]").on('change', function(){
                if($("input[name^=question_]:checked").length < 16)
                {
                    return false;
                }
                $(".captionOutcome").html("<i class='fa fa-info-circle'></i> Your Result");
                var a = 0;
                var b = 0;
                var c = 0;
                $("input[type=radio]").each(function(){
                    if(this.checked)
                    {
                        if(this.value == 'a')
                        {
                            a++;
                        }
                        else if(this.value == 'b')
                        {
                            b++;
                        }
                        else if(this.value == 'c')
                        {
                            c++;
                        }
                    }
                });

                $('#tdA').html(a);
                $('#tdB').html(b);
                $('#tdC').html(c);

                if(a > b && a > c)
                {
                    $('#tdOutcome').html('<p class="text-green">You have a <span class="text-bold">VISUAL</span> learning style</p>');
                }
                if(b > a && b > c)
                {
                    $('#tdOutcome').html('<p class="text-green">You have a <span class="text-bold">AUDITORY</span> learning style</p>');
                }
                if(c > a && c > b)
                {
                    $('#tdOutcome').html('<p class="text-green">You have a <span class="text-bold">KINAESTHETIC</span> learning style</p>');
                }
		if(a > c && a == b)
                {
                    $('#tdOutcome').html('<p class="text-green">You have a <span class="text-bold">VISUAL & AUDITORY</span> learning style</p>');
                }
                if(a > b && a == c)
                {
                    $('#tdOutcome').html('<p class="text-green">You have a <span class="text-bold">VISUAL & KINAESTHETIC</span> learning style</p>');
                }
                if(b > a && b == c)
                {
                    $('#tdOutcome').html('<p class="text-green">You have a <span class="text-bold">AUDITORY & KINAESTHETIC</span> learning style</p>');
                }

            });
        });
    </script>

</body>

</html>