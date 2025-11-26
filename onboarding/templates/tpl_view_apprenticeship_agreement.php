<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Apprenticeship Agreement</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">

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
        input[type=checkbox] {
            transform: scale(1.4);
        }
    </style>

</head>


<body>

<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">View Apprenticeship Agreement</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <!--                <span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>-->
            </div>
            <div class="ActionIconBar">

            </div>
        </div>

    </div>
</div>

<div class="content-wrapper" >

    <section class="content-header text-center"><h1><strong>Apprenticeship Agreement</strong></h1></section>

    <section class="content">
        <div class="container container-table">
            <div class="row vertical-center-row">

                <div class="col-md-10 col-md-offset-1" style="background-color: white;">
                    <p><br></p>
                    <div class="row">
                        <div class="col-sm-8">
                            <p>
                                An apprenticeship agreement must be in place at the start of the apprenticeship. The purpose of the apprenticeship agreement is to identify:
                            </p>
                            <ul>
                                <li>the skill, trade or occupation for which the apprentice is being trained;</li>
                                <li>the apprenticeship standard or framework connected to the apprenticeship;</li>
                                <li>the dates during which the apprenticeship is expected to take place; and</li>
                                <li>the amount of off the job training that the apprentice is to receive.</li>
                            </ul>
                        </div>
                        <div class="col-sm-4">
                            <p class="text-center">
                                <img class="img-responsive" src="images/logos/<?php echo SystemConfig::getEntityValue($link, 'logo');?>" />
                            </p>
                        </div>
                        <div class="col-sm-12">
                            <p>
                                The apprenticeship agreement is a statutory requirement for the employment of an apprentice in connection with an approved apprenticeship standard.
                                It forms part of the individual employment arrangements between the apprentice and the employer;
                                it is a contract of service (i.e. a contract of employment) and not a contract of apprenticeship.
                            </p>
                            <p>For further information, please see the explanatory notes and references before completing.</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive" style="font-size: medium;">
                                <table class="table table-bordered">
                                    <col width="50%">
                                    <caption style="padding: 5px;" class="text-bold bg-light-blue">Apprenticeship Details</caption>
                                    <tr><th>Apprentice Name:</th><td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td></tr>
                                    <tr><th>Employer Company Name:</th><td><?php echo $employer->legal_name; ?></td></tr>
                                    <tr><th>Training Provider Name:</th><td><?php echo $provider->legal_name; ?></td></tr>
                                    <tr><th>Subncontractor Name:</th><td><?php echo !is_null($subcontractor) ? $subcontractor->legal_name : ''; ?></td></tr>
                                    <tr><td colspan="2"></td></tr>
                                    <tr><th>Standard Title:</th><td><?php echo $framework->getStandardCodeDesc($link); ?></td></tr>
                                    <tr><th>Level:</th><td><?php echo DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';"); ?></td></tr>
                                    <tr><th>Start Date of Practical Period:</th><td><?php echo Date::toShort($tr->practical_period_start_date); ?></td></tr>
                                    <tr><th>Planned End Date of Practical Period:</th><td><?php echo Date::toShort($tr->practical_period_end_date); ?></td></tr>
                                    <tr><th>Duration of Practical Period - months:</th><td><?php echo $tr->duration_practical_period; ?></td></tr>
                                    <tr><td colspan="2"></td></tr>
                                    <tr><th>Start Date of Apprenticeship:</th><td><?php echo Date::toShort($tr->apprenticeship_start_date); ?></td></tr>
                                    <tr><th>Planned End Date of Apprenticeship (incl EPA):</th><td><?php echo Date::toShort($tr->apprenticeship_end_date_inc_epa); ?></td></tr>
                                    <tr><th>Duration of Apprenticeship (incl EPA):</th><td><?php echo $tr->apprenticeship_duration_inc_epa; ?></td></tr>
                                    <tr><td colspan="2"></td></tr>
                                    <tr><th>Planned Off-the-Job Hours:</th><td><?php echo $tr->getPlannedOtjHours($link); ?></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <caption style="padding: 5px;" class="text-bold bg-light-blue">Signatures</caption>
                                        <tr>
                                            <th></th><th>Name</th><th>Signature</th><th>Date</th>
                                        </tr>
                                        <tr>
                                            <td>Learner</td>
                                            <td>
                                                <?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?>
                                            </td>
                                            <td>
                                                <img width="25px" height="50px" src="do.php?_action=generate_image&<?php echo (isset($ob_learner->learner_sign) && $ob_learner->learner_sign != '') ? $ob_learner->learner_sign : 'title=not signed&size=20' ;?>" style="border: 2px solid;border-radius: 15px; width: 100%;"/>
                                            </td>
                                            <td>
                                                <?php echo (isset($ob_learner->learner_sign_date) && $ob_learner->learner_sign_date != '') ? Date::toShort($ob_learner->learner_sign_date) : date('d/m/Y') ;?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Employer</td>
                                            <td>
                                                <?php echo $employer->legal_name; ?>
                                            </td>
                                            <td>
                                                <img width="25px" height="50px" src="do.php?_action=generate_image&title=not signed&size=20" style="border: 2px solid;border-radius: 15px; width: 100%;"/>
                                            </td>
                                            <td>

                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section>


</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript">

    $(function() {

        $("input[type=checkbox]:checked").each(function() {
            $(this).closest('tr').addClass('bg-green');
        });

        $('.clsICheck').each(function(){
            var self = $(this),
                label = self.next(),
                label_text = label.text();

            label.remove();
            self.iCheck({
                checkboxClass: 'icheckbox_line-blue',
                insert: '<div class="icheck_line-icon"></div>' + label_text
            });
        });

        // $('input[class=radioICheck]').iCheck({radioClass: 'iradio_square-green', increaseArea: '20%'});
    });

</script>

</body>
</html>
