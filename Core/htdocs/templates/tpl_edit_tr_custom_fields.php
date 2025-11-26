<?php /* @var $pot_vo TrainingRecord */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Custom Training Fields</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <style>
        .disabled{
            pointer-events:none;
            opacity:0.4;
        }
    </style>
</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Edit Custom Training Record Fields</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
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

    <div class="col-sm-6">
        <div class="box box-primary">
            <div class="box-header">
                <h5 class="text-bold">Update Progression Fields</h5>
            </div>
            <form method="post" class="form-horizontal" name="frmTrProgressionFields" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="_action" value="save_training_record_customized" />
                <input type="hidden" name="id" value="<?php echo $pot_vo->id; ?>" />
                <input type="hidden" name="form_name" value="frmTrProgressionFields" />
                <div class="box-body with-border">
                    <div class="form-group">
                        <label for="progression_status" class="col-sm-4 control-label fieldLabel_optional">Progression Status:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('progression_status', InductionHelper::getDDLProgressionStatus(), $pot_vo->progression_status, true, false); ?>
                        </div>
                    </div>
		    <div class="form-group">
                        <label for="app_title" class="col-sm-4 control-label fieldLabel_optional">Progression Programme:</label>
                        <div class="col-sm-8">
                            <?php
                            $apprenticeship_titles = DAO::getResultSet($link, "SELECT distinct apprenticeship_title, apprenticeship_title FROM courses");
                            echo HTML::selectChosen('app_title', $apprenticeship_titles, $pot_vo->app_title, true, false);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="notified_arm" class="col-sm-4 control-label fieldLabel_optional">Notified ARM:</label>
                        <div class="col-sm-8">
                            <?php
                            echo HTML::selectChosen('notified_arm', InductionHelper::getDDLYesNo(), $pot_vo->notified_arm, true, false);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reason_not_progressing" class="col-sm-4 control-label fieldLabel_optional">Reason for not progressing:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('reason_not_progressing', InductionHelper::getDDLReasonForNotProgressing(), $pot_vo->reason_not_progressing, true, false); ?>
                        </div>
                    </div>
		    <div class="form-group">
                        <label for="progression_comments" class="col-sm-4 control-label fieldLabel_optional">Progression Comments:</label>
                        <div class="col-sm-8">
                            <textarea name="progression_comments" id="progression_comments" style="width: 100%;" rows="3"><?php echo $pot_vo->progression_comments; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="progression_last_date" class="col-sm-4 control-label fieldLabel_optional">Last Update:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::datebox('progression_last_date', $pot_vo->progression_last_date); ?>
                        </div>
                    </div>
		    <div class="form-group">
                        <label for="progression_rating" class="col-sm-4 control-label fieldLabel_optional">Progression Rating:</label>
                        <div class="col-sm-8">
                            <?php
                            echo HTML::selectChosen('progression_rating', InductionHelper::getDdlProgressionRating(), $pot_vo->progression_rating, true, false);
                            ?>
                        </div>
                    </div>
		    <span class="box-title text-bold">ARM Progression</span>
                    <div class="form-group">
                        <label for="arm_prog_status" class="col-sm-4 control-label fieldLabel_optional">ARM Progression Status:</label>
                        <div class="col-sm-8">
                            <?php
                            echo HTML::selectChosen('arm_prog_status', InductionHelper::getDdlArmProgressionStatus(), $pot_vo->arm_prog_status, true, false);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="arm_reason_not_prog" class="col-sm-4 control-label fieldLabel_optional">ARM Reason for Non Progression:</label>
                        <div class="col-sm-8">
                            <?php
                            echo HTML::selectChosen('arm_reason_not_prog', InductionHelper::getDdlArmReasonForNonProgression(), $pot_vo->arm_reason_not_prog, true, false);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="arm_closed_date" class="col-sm-4 control-label fieldLabel_optional">ARM Closed Date:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::datebox('arm_closed_date', $pot_vo->arm_closed_date, false); ?>
                        </div>
                    </div>
		    <div class="form-group">
                        <label for="arm_revisit_progression" class="col-sm-4 control-label fieldLabel_optional">ARM Date to Revisit Progression:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::datebox('arm_revisit_progression', $pot_vo->arm_revisit_progression, false); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="arm_prog_rating" class="col-sm-4 control-label fieldLabel_optional">ARM Progression Rating:</label>
                        <div class="col-sm-8">
                            <?php
                            echo HTML::selectChosen('arm_prog_rating', InductionHelper::getDdlArmProgressionRating(), $pot_vo->arm_prog_rating, true, false);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="arm_comments" class="col-sm-4 control-label fieldLabel_optional">ARM Comments:</label>
                        <div class="col-sm-8">
                            <textarea name="arm_comments" id="arm_comments" style="width: 100%;" rows="5"><?php echo nl2br((string) $pot_vo->arm_comments);?></textarea>
                        </div>
                    </div>
		    <span class="box-title text-bold">Management Progression</span>
                    <div class="form-group">
                        <label for="actual_progression" class="col-sm-4 control-label fieldLabel_optional">Actual Progression:</label>
                        <div class="col-sm-8">
                            <?php
                            echo HTML::selectChosen('actual_progression', InductionHelper::getDDLYesNo(), $pot_vo->actual_progression, true, false);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="planned_induction_date" class="col-sm-4 control-label fieldLabel_optional">Planned Induction Date:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::datebox('planned_induction_date', $pot_vo->planned_induction_date, false); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="actual_induction_date" class="col-sm-4 control-label fieldLabel_optional">Actual Induction Date:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::datebox('actual_induction_date', $pot_vo->actual_induction_date, false); ?>
                        </div>
                    </div>	
		    <span class="box-title text-bold">Employer Mentor</span>
                    <div class="form-group">
                        <label for="employer_mentor" class="col-sm-4 control-label fieldLabel_optional">Employer Mentor:</label>
                        <div class="col-sm-8">
                            <?php
                            echo HTML::selectChosen('employer_mentor', InductionHelper::getDdlEmployerMentor(), $pot_vo->employer_mentor, true, false);
                            ?>
                        </div>
                    </div>		
            <span class="box-title text-bold">Learner</span>
                    <div class="form-group">
                        <label for="employer_mentor" class="col-sm-4 control-label fieldLabel_optional">Trusted Contact Name:</label>
                        <div class="col-sm-8">
                            <input type="text" name="trusted_contact_name" id="trusted_contact_name" value="<?php echo $pot_vo->trusted_contact_name; ?>" maxlength="100" />
                        </div>
                    </div>		
                    <div class="form-group">
                        <label for="employer_mentor" class="col-sm-4 control-label fieldLabel_optional">Trusted Contact Mobile:</label>
                        <div class="col-sm-8">
                            <input type="text" name="trusted_contact_mobile" id="trusted_contact_mobile" value="<?php echo $pot_vo->trusted_contact_mobile; ?>" maxlength="15" />
                        </div>
                    </div>		
                    <div class="form-group">
                        <label for="employer_mentor" class="col-sm-4 control-label fieldLabel_optional">Trusted Contact Relationship:</label>
                        <div class="col-sm-8">
                            <input type="text" name="trusted_contact_rel" id="trusted_contact_rel" value="<?php echo $pot_vo->trusted_contact_rel; ?>" maxlength="100" />
                        </div>
                    </div>		
                    <div class="form-group">
                        <label for="employer_mentor" class="col-sm-4 control-label fieldLabel_optional">Details Checked Date:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::datebox('details_checked_date', $pot_vo->details_checked_date); ?>
                        </div>
                    </div>		
                </div>
            </form>
        </div>

    </div>

    <div class="col-sm-6">
        <div class="table-responsive">
            <table class="table-bordered table-condensed">
                <tr>
                    <th>Learner Name:</th><td><?php echo $pot_vo->firstnames . ' ' . $pot_vo->surname;?></td>
                </tr>
                <tr>
                    <th>Learner Reference:</th><td><?php echo $pot_vo->l03;?></td>
                </tr>
                <tr>
                    <th>Employer:</th><td><?php echo DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$pot_vo->employer_id}'");?></td>
                </tr>
            </table>
        </div>
    </div>

</div>
<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>

<script language="JavaScript">

    function save()
    {
        var myForm = document.forms['frmTrProgressionFields'];
        if(validateForm(myForm) == false)
        {
            return false;
        }

        myForm.submit();
    }

</script>

</body>
</html>