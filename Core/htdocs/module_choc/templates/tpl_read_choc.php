<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CHOC Entry</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css" />
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
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
                <div class="Title" style="margin-left: 6px;">View CHOC Entry</div>
                <div class="ButtonBar">
                    <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
                    <?php if(in_array($choc->choc_status, ["IN PROGRESS", "NEW"])){?>
                    <span class="btn btn-xs btn-default" onclick="saveFrmChoc();"><i class="fa fa-save"></i> Save</span>
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
        <div class="col-sm-6">
            <div class="callout">
                <span class="lead text-bold text-info">Learner Details</span><br>
                <span class="text-bold">Learner:</span> <?php echo $tr->firstnames . ' ' . $tr->surname; ?><br>
                <span class="text-bold">Learner's Contact:</span> <?php echo $tr->home_address_line_1 . ' ' . $tr->home_postcode; ?><br>
                <?php echo $tr->home_email; ?><br>
                <?php echo $tr->home_telephone; ?><br>            
                <span class="text-bold">Programme:</span> <?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$tr_id}'"); ?><br>
                <span class="text-bold">Start Date:</span> <?php echo Date::toShort($tr->start_date); ?><br>
                <span class="text-bold">Planned End Date:</span> <?php echo Date::toShort($tr->target_date); ?><br>
                <span class="text-bold">Training Status:</span>
                <?php
                if ($tr->status_code == 1) {
                    echo "1 The learner is continuing or intending to continue the learning activities.";
                } elseif ($tr->status_code == 2) {
                    echo "2 The learner has completed the learning activities.";
                } elseif ($tr->status_code == 3) {
                    echo "3 The learner has withdrawn from the learning activities.";
                } elseif ($tr->status_code == 4) {
                    echo "4 The learner has transferred to a new learning.";
                } elseif ($tr->status_code == 5) {
                    echo "5 Changes in learning within the same programme.";
                } elseif ($tr->status_code == 6) {
                    echo "6 Learner has temporarily withdrawn due to an agreed break in learning.";
                } else {
                    echo htmlspecialchars((string)$record_status);
                }
                ?>
                <br>
                <span class="text-bold">Employer:</span> 
                <?php 
                if($choc->id == '')
                {
                    echo $tr->legal_name; 
                }
                else
                {
                    echo DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE organisations.id = '{$choc->old_employer}'");
                }
                ?><br>
                <span class="text-bold">Employer Address:</span> 
                <?php
                if($choc->id == '')
                {
                    $_location = Location::loadFromDatabase($link, $tr->employer_location_id);
                } 
                else
                {
                    $_location = Location::loadFromDatabase($link, $choc->old_employer_location);
                }
                echo $_location->address_line_1 . ', ' .  $_location->postcode . '<br>';
                echo $_location->telephone;
                ?><br>
                <span class="text-bold">Assessor:</span> <?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$tr->assessor}'"); ?><br>
                <span class="text-bold">Price - TNP 1:</span> 
                <?php 
                echo DAO::getSingleValue($link, "SELECT EXTRACTVALUE(ilr, 'Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/TrailblazerApprenticeshipFinancialRecord[TBFinType=\'TNP\' and TBFinCode=\'1\']/TBFinAmount') FROM ilr WHERE ilr.tr_id = '{$tr->id}' ORDER BY contract_id DESC, submission DESC LIMIT 1"); 
                ?><br>
                <span class="text-bold">Price - TNP 2:</span> 
                <?php 
                echo DAO::getSingleValue($link, "SELECT EXTRACTVALUE(ilr, 'Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/TrailblazerApprenticeshipFinancialRecord[TBFinType=\'TNP\' and TBFinCode=\'2\']/TBFinAmount') FROM ilr WHERE ilr.tr_id = '{$tr->id}' ORDER BY contract_id DESC, submission DESC LIMIT 1"); 
                ?><br>
            </div>
            <div class="callout">
                <span class="lead text-bold text-info">CHOC Details</span><br>
                <span class="text-bold">Created By:</span> <?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$choc->created_by}'"); ?><br>
                <span class="text-bold">Created At:</span> <?php echo Date::to($choc->created_at, Date::DATETIME); ?><br>
                <span class="text-bold">Status:</span> <?php echo $choc->choc_status; ?><br>
                <span class="text-bold">Type:</span> <?php echo $choc->choc_type; ?><br>
                <?php if ($choc->choc_type == "Change of Employer") {
                    $new_employer = isset($choc_details->new_employer) ? Organisation::loadFromDatabase($link, $choc_details->new_employer) : null;
                    $new_employer_location = isset($choc_details->new_employer_location) ? Location::loadFromDatabase($link, $choc_details->new_employer_location) : null;
                ?>
                    <span class="text-bold">Date left:</span> <?php echo isset($choc_details->date_left) ? $choc_details->date_left : ''; ?><br>
                    <span class="text-bold">New start date:</span> <?php echo isset($choc_details->new_start_date) ? $choc_details->new_start_date : ''; ?><br>
                    <span class="text-bold">Was learner made redundant:</span> <?php echo isset($choc_details->learner_made_redundant) ? ($choc_details->learner_made_redundant == 1 ? 'Yes' : 'No') : ''; ?><br>
                    <span class="text-bold">New Employer:</span> <?php echo !is_null($new_employer) ? $new_employer->legal_name : ''; ?><br>
                    <span class="text-bold">New Employer Location:</span>
                    <?php
                    if (!is_null($new_employer_location)) {
                        echo $new_employer_location->full_name . '<br>';
                        echo $new_employer_location->address_line_1 . '<br>';
                        echo $new_employer_location->address_line_2 . '<br>';
                        echo $new_employer_location->address_line_3 . '<br>';
                        echo $new_employer_location->address_line_4 . '<br>';
                        echo $new_employer_location->postcode . '<br>';
                        echo $new_employer_location->telephone . '<br>';
                    }
                    ?>
                    <span class="text-bold">Learner New Job Role:</span> <?php echo isset($choc_details->new_job_role) ? $choc_details->new_job_role : ''; ?><br>
                    <span class="text-bold">Learner New Hours per Week:</span> <?php echo isset($choc_details->new_hours_per_week) ? $choc_details->new_hours_per_week : ''; ?><br>
                    <span class="text-bold">Learner New Weeks per Year:</span> <?php echo isset($choc_details->new_working_weeks_per_year) ? $choc_details->new_working_weeks_per_year : ''; ?><br>
                    <span class="text-bold">Price - TNP 3:</span> <?php echo isset($choc_details->tnp3) ? $choc_details->tnp3 : ''; ?><br>
                    <span class="text-bold">Price - TNP 4:</span> <?php echo isset($choc_details->tnp4) ? $choc_details->tnp4 : ''; ?><br>
                    <span class="text-bold">Change <span title="Apprenticeship Contract Type">ACT</span>:</span> <?php echo isset($choc_details->change_act) ? ($choc_details->change_act == 1 ? 'Yes' : 'No') : ''; ?><br>
                    <span class="text-bold">ACT:</span> 
                    <?php 
                    if(isset($choc_details->new_act))
                    {
                        if($choc_details->new_act == 1)
                        {
                            echo "1 A levy or non-levy paying employer on the apprenticeship service and is funded through a contract for services with the employer";
                        }
                        if($choc_details->new_act == 2)
                        {
                            echo "2 An employer that is not on the apprenticeship service and is funded through a contract for services with the ESFA";
                        }
                    }
                    ?><br>
                <?php
                }
                elseif ($choc->choc_type == "Break in Learning") {
                ?>
                    <span class="text-bold">Last date of learning:</span> <?php echo isset($choc_details->bil_last_date) ? $choc_details->bil_last_date : ''; ?><br>
                    <span class="text-bold">Reason of break in learning:</span> <?php echo isset($choc_details->bil_reason) ? $choc_details->bil_reason : ''; ?><br>
                    <span class="text-bold">Date of expected return:</span> <?php echo isset($choc_details->bil_return_date) ? $choc_details->bil_return_date : ''; ?><br>
                <?php } 
                elseif ($choc->choc_type == "Change of Learner Details") {
                ?>
                    <span class="text-bold">New Firstnames:</span> <?php echo isset($choc_details->new_learner_firstnames) ? $choc_details->new_learner_firstnames : ''; ?><br>
                    <span class="text-bold">New Surname:</span> <?php echo isset($choc_details->new_learner_surname) ? $choc_details->new_learner_surname : ''; ?><br>
                    <span class="text-bold">New address line 1:</span> <?php echo isset($choc_details->new_learner_address_line_1) ? $choc_details->new_learner_address_line_1 : ''; ?><br>
                    <span class="text-bold">New address line 2:</span> <?php echo isset($choc_details->new_learner_address_line_2) ? $choc_details->new_learner_address_line_2 : ''; ?><br>
                    <span class="text-bold">New address line 3:</span> <?php echo isset($choc_details->new_learner_address_line_3) ? $choc_details->new_learner_address_line_3 : ''; ?><br>
                    <span class="text-bold">New address line 4:</span> <?php echo isset($choc_details->new_learner_address_line_4) ? $choc_details->new_learner_address_line_4 : ''; ?><br>
                    <span class="text-bold">New postcode:</span> <?php echo isset($choc_details->new_learner_postcode) ? $choc_details->new_learner_postcode : ''; ?><br>
                    <span class="text-bold">New email:</span> <?php echo isset($choc_details->new_learner_email) ? $choc_details->new_learner_email : ''; ?><br>
                    <span class="text-bold">New telephone:</span> <?php echo isset($choc_details->new_learner_telephone) ? $choc_details->new_learner_telephone : ''; ?><br>
                    <span class="text-bold">New mobile:</span> <?php echo isset($choc_details->new_learner_mobile) ? $choc_details->new_learner_mobile : ''; ?><br>
                <?php }
                elseif ($choc->choc_type == "Change of LLDD") { ?>
                    <span class="text-bold">Does learner consider to have a learning difficulty, health problem or disability?:</span> <?php echo isset($choc_details->l14) ? ($choc_details->l14 == 1 ? 'Yes' : 'No') : ''; ?><br>
                    <span class="text-bold">Categories:</span> <br>
                    <?php
                        if(isset($choc_details->lldd_cat))
                        {
                            foreach($choc_details->lldd_cat AS $_cat)
                            {
                                echo '<p>';
                                echo isset($ddlLlddCat[$_cat]) ? $ddlLlddCat[$_cat] : '';
                                if(isset($choc_details->primary_lldd) && $choc_details->primary_lldd == $_cat)
                                {
                                    echo ' &nbsp; - Primary Category';
                                }
                                echo '</p>';
                            }
                        }
                    ?>
                <?php } ?>

            </div>
            <hr>
            <div style="margin-left: 15px;">
                <span class="lead text-bold text-info">Comments</span>
                <?php 
                if($choc->comments != '')
                {
                    echo '<ul class="timeline">';
                    $comments = XML::loadSimpleXML($choc->comments);
                    foreach($comments AS $comment)
                    {
                        // $date = 
                        echo '<li class="time-label"><span class="bg-green">' . Date::toMedium($comment->DateTime->__toString()) . '</span></li>';

                        echo '<li>';
                        echo '<i class="fa fa-comments bg-blue"></i>';
                        echo '<div class="timeline-item">';
                        echo '<span class="time"><i class="fa fa-clock-o"></i> ' . Date::to($comment->DateTime->__toString(), 'H:i') . '</span>';
                        echo '<h5 class="timeline-header"><a href="#">' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$comment->CreatedBy->__toString()}'") . '</a></h5>';
                        echo '<div class="timeline-body">';
                        echo nl2br($comment->Note->__toString());
                        echo '</div>';
                        echo '</div>';
                        echo '</li>';
                    }
                    echo '</ul>';
                }
                ?>

            </div>
        </div>
        <div class="col-sm-6">
            <?php if(in_array($choc->choc_status, ["IN PROGRESS", "NEW"])){?>
            <form class="form-horizontal" name="frmChoc" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="_action" value="update_choc" />
                <input type="hidden" name="id" value="<?php echo $choc->id ?>" />
                <input type="hidden" name="tr_id" value="<?php echo $tr->id ?>" />
                <div class="col-sm-12">

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <span class="box-title">
                                <h5 class="text-bold">Update details</h5>
                            </span>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="choc_status" class="col-sm-4 control-label fieldLabel_compulsory">Select Status:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('choc_status', $choc_status_ddl, $choc->choc_status, true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="assigned_to" class="col-sm-4 control-label fieldLabel_compulsory">Assigned To:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('assigned_to', DAO::getResultset($link, "SELECT id, CONCAT(firstnames, ' ', surname) FROM users WHERE users.type = 1 ORDER BY firstnames"), $choc->assigned_to, true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="comments" class="col-sm-4 control-label fieldLabel_compulsory">Comments:</label>
                                <div class="col-sm-8">
                                    <textarea name="comments" id="comments" class="form-control" rows="5"></textarea>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>


            </form>
            <?php } ?>
            <?php if($choc->choc_status == "REFERRED"){?>
                <div class="alert alert-danger">
                    <span class="lead">Status: REFERRED</span>
                    <br><i class="fa fa-info-circle"></i> This change request has not been accepted.
                </div>
            <?php } ?>
            <?php if($choc->choc_status == "COMPLETED"){?>
                <div class="alert alert-info">
                    <span class="lead">Status: COMPLETED</span>
                    <br><i class="fa fa-check-circle"></i> This change request has been completed. System has been updated accordingly.
                </div>
            <?php } ?>
            <?php if($choc->choc_status == "ACCEPTED"){?>
            <form class="form-horizontal" name="frmChoc" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="_action" value="update_choc" />
                <input type="hidden" name="id" value="<?php echo $choc->id ?>" />
                <input type="hidden" name="tr_id" value="<?php echo $tr->id ?>" />
                <input type="hidden" name="completion" value="1" />
                <div class="col-sm-12">

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <span class="box-title">
                                <h5 class="text-bold">Update details</h5>
                            </span>
                        </div>
                        <div class="box-body">
                            <div class="alert alert-success">
                                <span class="lead">Status: Accepted</span>
                                <br><i class="fa fa-info-circle"></i> This change request has been accepted. Press the following "Update System" button to update the system.
                            </div>
                            <span class="btn btn-success btn-md" onclick="update_system_for_choc('<?php echo $choc->id; ?>');"><i class="fa fa-save"></i> Update System</span>
                        </div>

                    </div>

                </div>


            </form>
            <?php } ?>
        </div>
    </div>


    <br>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/common.js" type="text/javascript"></script>

    <script language="JavaScript">

    function saveFrmChoc()
	{
		var frmChoc = document.forms["frmChoc"];
		if(validateForm(frmChoc) == false)
		{
			return false;
		}
		frmChoc.submit();
	}

    function update_system_for_choc(choc_id)
    {
        if(!confirm("Are you sure you want to update the system regarding this change of request?"))
        {
            return false;
        }
        var frmChoc = document.forms["frmChoc"];
        var client = ajaxPostForm(frmChoc);
        if(client != null)
        {
            alert("Details are changed successfully");
            window.location.reload();
        }
    }

    </script>

</body>

</html>