<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Learner Writing Assessment</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/css/common.css" type="text/css" />
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

        textarea,
        input[type=text] {
            border: 1px solid #3366FF;
            border-radius: 5px;
            border-left: 5px solid #3366FF;
        }

        input[type=checkbox] {
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
    <div class="row">
        <div class="col-sm-12">
            <div class="banner">
                <div class="Title" style="margin-left: 6px;">View Learner Writing Assessment</div>
                <div class="ButtonBar">
                    <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
                </div>
                <div class="ActionIconBar">

                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?php $_SESSION['bc']->render($link); ?>
        </div>
    </div>
    

    <br>

    <div class="content-wrapper">

        <section class="content">
            <div class="container-fluid container-table">
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
                                <p class="lead text-bold text-center">Learner Writing Assessment</p>
                            </div>
                        </div>

                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmWritingAssessment">
                            <input type="hidden" name="_action" value="view_learner_writing_assessment">
                            <input type="hidden" name="subaction" value="save_sign_assessment">
                            <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>">
                            <input type="hidden" name="total_marks" value="<?php echo $assessment->total_marks; ?>">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="bg-gray">Employer:</th>
                                                <td><?php echo DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE organisations.id = '{$tr->employer_id}'"); ?></td>
                                            </tr>
                                            <tr>
                                                <th class="bg-gray">Learner:</th>
                                                <td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td>
                                            </tr>
                                            <tr>
                                                <th class="bg-gray">Programme:</th>
                                                <td><?php echo $framework->title; ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <?php echo str_replace('500 words', $framework->writing_assessment_chars . ' words', $framework->writing_assessment_text); ?>

                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th><span class="lead text-green text-bold">Details</span></th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <textarea class="inputLimiter" name="learner_comments" id="learner_comments" style="width: 100%;" rows="15"><?php echo $assessment->learner_comments; ?></textarea>
                                                <br>words count: <span id="lblWordsCount"><?php echo str_word_count($assessment->learner_comments) + 5; ?></span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <caption class="lead text-info text-bold">Marking</caption>
                                        <tr class="bg-gray">
                                            <th><p class="text-center">Content and Layout</p></th><th>Marks Awarded</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <td>
                                                            Has included all of the information requested (where they work, and their job role; what inspires them most about working with children in this sector; what activities they most enjoy doing with children; what inspires them to work in their current setting; how their apprenticeship will impact their career prospects; how their apprenticeship will impact their personal development; a typical working day.)
                                                        </td>
                                                        <td style="width: 15%;">4</td>
                                                    </tr>
                                                    <tr><td>Has included at least five of the points listed. </td><td>3</td></tr>
                                                    <tr><td>Has included at least three of the points listed. </td><td>2</td></tr>
                                                    <tr><td>Has included less than three of the points listed. </td><td>1</td></tr>
                                                </table>
                                            </td>
                                            <td style="width: 15%;">
                                                <?php echo isset($marking->s1) ? HTML::selectChosen('s1', OnboardingHelper::generateMarksAwardedDdl(1, 4), $marking->s1) : HTML::selectChosen('s1', OnboardingHelper::generateMarksAwardedDdl(1, 4)); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <td>
                                                        Text contains both simple and complex sentences, with a wide range of connectives used (and, but, so, however, therefore, although, whereas, because etc.).
                                                        </td>
                                                        <td style="width: 15%;">2</td>
                                                    </tr>
                                                    <tr><td>Text contains both simple and complex sentences, with a limited range of connectives used (and, but, so)</td><td>1</td></tr>
                                                </table>
                                            </td>
                                            <td style="width: 15%;">
                                                <?php echo isset($marking->s2) ? HTML::selectChosen('s2', OnboardingHelper::generateMarksAwardedDdl(1, 2), $marking->s2) : HTML::selectChosen('s2', OnboardingHelper::generateMarksAwardedDdl(1, 2)); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table class="table table-bordered">
                                                    <tr><td>Writing is accurately laid out into paragraphs. </td><td style="width: 15%;">2</td></tr>
                                                    <tr><td>There has been an attempt at paragraphing, although not always successfully. </td><td>1</td></tr>
                                                    <tr><td>No paragraphs. </td><td>0</td></tr>
                                                </table>
                                            </td>
                                            <td style="width: 15%;">
                                                <?php echo isset($marking->s3) ? HTML::selectChosen('s3', OnboardingHelper::generateMarksAwardedDdl(0, 2), $marking->s3) : HTML::selectChosen('s3', OnboardingHelper::generateMarksAwardedDdl(0, 2)); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table class="table table-bordered">
                                                    <tr><td>The text is logical and well-organised.  Information, ideas and opinions are consistently communicated clearly and effectively.</td><td style="width: 15%;">2</td></tr>
                                                    <tr><td>There has been some attempt to organise the text.  Information, ideas and opinions are not always communicated clearly or effectively.</td><td>1</td></tr>
                                                    <tr><td>The text is poorly structured.  Information and ideas are unclear.</td><td>0</td></tr>
                                                </table>
                                            </td>
                                            <td style="width: 15%;">
                                                <?php echo isset($marking->s4) ? HTML::selectChosen('s4', OnboardingHelper::generateMarksAwardedDdl(0, 2), $marking->s4) : HTML::selectChosen('s4', OnboardingHelper::generateMarksAwardedDdl(0, 2)); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table class="table table-bordered">
                                                    <?php 
                                                    $writing_assessment_chars = $framework->writing_assessment_chars;
                                                    for($i = 4; $i >= 1; $i--)
                                                    {
                                                        echo '<tr><td>The text is approximately ' . $writing_assessment_chars . ' words.</td><td style="width: 15%;">' . $i . '</td></tr>';
                                                        $writing_assessment_chars -= 50;
                                                    }
                                                    ?>
                                                </table>
                                            </td>
                                            <td style="width: 15%;">
                                                <?php echo isset($marking->s5) ? HTML::selectChosen('s5', OnboardingHelper::generateMarksAwardedDdl(1, 4), $marking->s5) : HTML::selectChosen('s5', OnboardingHelper::generateMarksAwardedDdl(1, 4)); ?>
                                            </td>
                                        </tr>
                                        <tr class="bg-gray">
                                            <th><p class="text-center">Spelling</p></th><th>Marks Awarded</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table class="table table-bordered">
                                                    <tr><td>Spelling is consistently accurate, including ambitious and /or irregular words where used.</td><td style="width: 15%;">3</td></tr>
                                                    <tr><td>Spelling is accurate most of the time, with some accurate spelling of more complex or irregular words. </td><td>2</td></tr>
                                                    <tr><td>Limited accuracy: some accurate spelling of simple or regular words.</td><td>1</td></tr>
                                                    <tr><td>Spelling errors significantly impair meaning, or insufficient evidence to judge ability.</td><td>0</td></tr>
                                                </table>
                                            </td>
                                            <td style="width: 15%;">
                                                <?php echo isset($marking->s6) ? HTML::selectChosen('s6', OnboardingHelper::generateMarksAwardedDdl(0, 3), $marking->s6) : HTML::selectChosen('s6', OnboardingHelper::generateMarksAwardedDdl(0, 3)); ?>
                                            </td>
                                        </tr>
                                        <tr class="bg-gray">
                                            <th><p class="text-center">Punctuation</p></th><th>Marks Awarded</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table class="table table-bordered">
                                                    <tr><td>A range of punctuation (e.g. colons, commas, inverted commas, apostrophes, quotation marks) is used consistently accurately to mark the structure of sentences and give clarity and emphasis.</td><td style="width: 15%;">3</td></tr>
                                                    <tr><td>Some accuracy / range in punctuation: some sentences are correctly demarcated, with some use of other punctuation, e.g. commas to mark phrases or clauses or within lists.</td><td>2</td></tr>
                                                    <tr><td>Limited accuracy / range in punctuation.</td><td>1</td></tr>
                                                    <tr><td>Punctuation errors significantly impair meaning, or insufficient evidence to judge ability.</td><td>0</td></tr>
                                                </table>
                                            </td>
                                            <td style="width: 15%;">
                                                <?php echo isset($marking->s7) ? HTML::selectChosen('s7', OnboardingHelper::generateMarksAwardedDdl(0, 3), $marking->s7) : HTML::selectChosen('s7', OnboardingHelper::generateMarksAwardedDdl(0, 3)); ?>
                                            </td>
                                        </tr>
                                        <tr class="bg-gray">
                                            <th><p class="text-center">Grammar</p></th><th>Marks Awarded</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table class="table table-bordered">
                                                    <tr><td>Grammar is consistently accurate: e.g. tenses and verb forms such as modals (would have been...) are controlled; definite and indefinite articles are used accurately when needed.</td><td style="width: 15%;">3</td></tr>
                                                    <tr><td>Some accuracy in grammar: e.g. some sentences are grammatically sound; there is some variation in verb forms; tense choice is appropriate some of the time; definite and indefinite articles are often incorrectly used or omitted when needed.</td><td>2</td></tr>
                                                    <tr><td>Limited accuracy in grammar: e.g. errors in verb forms and tenses are frequent and tense choice is often incorrect; definite and indefinite articles are frequently inaccurate or omitted when needed.</td><td>1</td></tr>
                                                    <tr><td>Grammar errors significantly impair meaning, or insufficient evidence to judge ability.</td><td>0</td></tr>
                                                </table>
                                            </td>
                                            <td style="width: 15%;">
                                                <?php echo isset($marking->s8) ? HTML::selectChosen('s8', OnboardingHelper::generateMarksAwardedDdl(0, 3), $marking->s8) : HTML::selectChosen('s8', OnboardingHelper::generateMarksAwardedDdl(0, 3)); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-gray">
                                                <p class="text-right">Total Marks</p>
                                            </th>
                                            <th class="lblTotal text-bold lead text-center"></th>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <hr>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-3">
                                    Learner: <?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?>
                                </div>
                                <div class="col-sm-6 text-center">
                                    <?php if($assessment->learner_sign == '') {?> 
                                        <img src="do.php?_action=generate_image&title=Not yet signed&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" />
                                    <?php } else {?> 
                                        <img src="do.php?_action=generate_image&<?php echo $assessment->learner_sign ?>" style="border: 2px solid;border-radius: 15px;" />
                                    <?php } ?>
                                </div>
                                <div class="col-sm-3">
                                    <?php echo Date::toShort($assessment->learner_sign_date); ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    Provider/Assessor: <?php echo $assessment->provider_sign_name != '' ? $assessment->provider_sign_name : $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?>
                                    <input type="hidden" name="provider_sign_name" value="<?php echo $assessment->provider_sign_name != '' ? $assessment->provider_sign_name : $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?>" />
                                </div>
                                <div class="col-sm-6 text-center">
                                    <?php if($assessment->provider_sign == '') {?> 
                                        <span class="btn btn-info" onclick="getSignature('provider');">
                                            <img id="img_provider_sign" src="do.php?_action=generate_image&<?php echo $assessment->provider_sign != '' ? $assessment->provider_sign : 'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
                                            <input type="hidden" name="provider_sign" id="provider_sign" value="" />
                                        </span>
                                    <?php } else {?> 
                                        <img src="do.php?_action=generate_image&<?php echo $assessment->provider_sign ?>" style="border: 2px solid;border-radius: 15px;" />
                                        <input type="hidden" name="provider_sign" id="provider_sign" value="<?php echo $assessment->provider_sign; ?>" />
                                    <?php } ?>
                                </div>
                                <div class="col-sm-3">
                                    <?php echo $assessment->provider_sign_date != '' ? Date::toShort($assessment->provider_sign_date) : date('d/m/Y'); ?>
                                    <input type="hidden" name="provider_sign_date" value="<?php echo $assessment->provider_sign_date != '' ? $assessment->provider_sign_date : date('d/m/Y'); ?>" />
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



    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        var phpProviderSignature = '<?php echo $_SESSION['user']->signature; ?>';

        $(function() {
            refreshTotal();
            //$('.inputLimiter').inputlimiter();


            $('textarea[name="learner_comments"]').on('change keyup paste', function() {
                var words = this.value.trim().replace(/\s+/gi, ' ').split(' ').length;
                $('#lblWordsCount').html(words);
            });

            $("select[name^=s]").on('change', function(){
                refreshTotal();
            });

        });

        function refreshTotal()
        {
            var total = 0;
            $("select[name^=s]").each(function(i, ele){
                total += parseInt(ele.value);
            });

            $("th.lblTotal").html(total);
        }

        function getSignature(user)
        {
            if(window.phpProviderSignature == '')
            {
                $( "#panel_signature" ).data('panel', 'provider').dialog( "open");
                return;
            }
            $('#img_provider_sign').attr('src', 'do.php?_action=generate_image&'+window.phpProviderSignature);
            $('#provider_sign').val(window.phpProviderSignature);
        }

        function submitInformation() {
            var frmWritingAssessment = document.forms['frmWritingAssessment'];

            var provider_sign = frmWritingAssessment.elements["provider_sign"];

            if (frmWritingAssessment.learner_comments.value == '') {
                alert('Please provide the answer.');
                frmWritingAssessment.learner_comments.focus();
                return;
            }

            var words = frmWritingAssessment.learner_comments.value.trim().replace(/\s+/gi, ' ').split(' ').length;
            // if (words < 500) {
            //     alert('500 words is the minimum requirement.');
            //     frmWritingAssessment.learner_comments.focus();
            //     return;
            // }

            if (provider_sign.value.trim() == '') {
                alert('Please provide your signature.');
                return;
            }

            frmWritingAssessment.total_marks.value = $("th.lblTotal").html();


            frmWritingAssessment.submit();
        }
    </script>

</body>

</html>