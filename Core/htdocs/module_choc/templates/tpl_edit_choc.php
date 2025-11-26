<?php /* @var $choc Choc */ ?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $choc->id == ''?'Create CHOC':'Edit CHOC'; ?></title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
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
			<div class="Title" style="margin-left: 6px;"><?php echo $choc->id == ''?'Create CHOC':'Edit CHOC'; ?></div>
			<div class="ButtonBar">
				<span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<span class="btn btn-xs btn-default" onclick="saveFrmChoc();"><i class="fa fa-save"></i> Save</span>
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
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
        <div class="callout callout-default">
            <span class="text-bold">Learner:</span> <?php echo $tr->firstnames . ' ' . $tr->surname; ?><br>
            <span class="text-bold">Learner's Contact:</span> <?php echo $tr->home_address_line_1 . ' ' . $tr->home_postcode; ?><br>
            <?php echo $tr->home_email; ?><br>
            <?php echo $tr->home_telephone; ?><br>            
            <span class="text-bold">Programme:</span> <?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$tr_id}'"); ?><br>
            <span class="text-bold">Start Date:</span> <?php echo Date::toShort($tr->start_date); ?><br>
            <span class="text-bold">Planned End Date:</span> <?php echo Date::toShort($tr->target_date); ?><br>
            <span class="text-bold">Training Status:</span> 
            <?php 
                if($tr->status_code == 1)
                {
                    echo "1 The learner is continuing or intending to continue the learning activities.";
                }
                elseif($tr->status_code == 2)
                {
                    echo "2 The learner has completed the learning activities.";
                }
                elseif($tr->status_code == 3)
                {
                    echo "3 The learner has withdrawn from the learning activities.";
                }
                elseif($tr->status_code == 4)
                {
                    echo "4 The learner has transferred to a new learning.";
                }
                elseif($tr->status_code == 5)
                {
                    echo "5 Changes in learning within the same programme.";
                }
                elseif($tr->status_code == 6)
                {
                    echo "6 Learner has temporarily withdrawn due to an agreed break in learning.";
                }
                else
                {
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
    </div>
    <div class="col-sm-2"></div>
</div>
<div class="row">
    <div class="col-sm-1"></div>
	<form class="form-horizontal" name="frmChoc" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<input type="hidden" name="_action" value="save_choc" />
		<input type="hidden" name="id" value="<?php echo $choc->id ?>" />
		<input type="hidden" name="tr_id" value="<?php echo $tr->id ?>" />
		<input type="hidden" name="choc_status" value="NEW" />
		<div class="col-sm-8">

			<div class="box box-primary">
                <div class="box-header with-border">
                    <span class="box-title"><h5 class="text-bold">Change of circumstances details</h5></span>
                </div>
				<div class="box-body">
                    <div class="form-group">
                        <label for="choc_type" class="col-sm-4 control-label fieldLabel_compulsory">Select Type:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('choc_type', $choc_types, $choc->choc_type, true, true); ?>
                        </div>
                    </div>
                    <div class="sectionCoe" style="display: none;">
                        <div class="form-group">
                            <label for="date_left" class="col-sm-4 control-label fieldLabel_compulsory">Date Left:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::datebox('date_left', ''); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_start_date" class="col-sm-4 control-label fieldLabel_compulsory">New Start Date:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::datebox('new_start_date', ''); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="learner_made_redundant" class="col-sm-4 control-label fieldLabel_compulsory">Was Learner made redundant:</label>
                            <div class="col-sm-8">
                                <input value="1" class="yes_no_toggle" type="checkbox" name="learner_made_redundant" data-toggle="toggle" checked="checked" data-toggle="toggle" 
                                    data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_employer" class="col-sm-4 control-label fieldLabel_compulsory">Select New Employer:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('new_employer', $employers_list, null, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_employer_location" class="col-sm-4 control-label fieldLabel_compulsory">Select New Employer Location:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('new_employer_location', [], '', false); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_employer_line_manager" class="col-sm-4 control-label fieldLabel_compulsory">Select New Employer Line Manager / Mentor:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('new_employer_line_manager', [], '', false); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_job_role" class="col-sm-4 control-label fieldLabel_compulsory">Enter New Job Role:</label>
                            <div class="col-sm-8">
                                <input type="text" name="new_job_role" id="new_job_role" class="form-control" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_hours_per_week" class="col-sm-4 control-label fieldLabel_compulsory">Enter New Contracted Hours per Week:</label>
                            <div class="col-sm-8">
                                <input type="text" name="new_hours_per_week" id="new_hours_per_week" class="form-control" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_working_weeks_per_year" class="col-sm-4 control-label fieldLabel_compulsory">Enter New Weeks to be worked per Year:</label>
                            <div class="col-sm-8">
                                <input type="text" name="new_working_weeks_per_year" id="new_working_weeks_per_year" class="form-control" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tnp3" class="col-sm-4 control-label fieldLabel_compulsory">Price - TNP 3:</label>
                            <div class="col-sm-8">
                                <input type="text" name="tnp3" id="tnp3" class="form-control" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tnp4" class="col-sm-4 control-label fieldLabel_compulsory">Price - TNP 4:</label>
                            <div class="col-sm-8">
                                <input type="text" name="tnp4" id="tnp4" class="form-control" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="change_act" class="col-sm-4 control-label fieldLabel_compulsory">Do you want to change Apprenticeship Contract Type:</label>
                            <div class="col-sm-8">
                                <input value="1" class="yes_no_toggle" type="checkbox" name="change_act" data-toggle="toggle" checked="checked" data-toggle="toggle" 
                                    data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_act" class="col-sm-4 control-label fieldLabel_compulsory">Select Apprenticeship Contract Type:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('new_act', [[1, "1 A levy or non-levy paying employer on the apprenticeship service and is funded through a contract for services with the employer"], [2, "2 An employer that is not on the apprenticeship service and is funded through a contract for services with the ESFA"]], null, true); ?>
                            </div>
                        </div>
                    </div>
                    <div class="sectionCol" style="display: none;">
                        <div class="form-group">
                            <label for="new_learner_firstnames" class="col-sm-4 control-label fieldLabel_compulsory">New Firstnames:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="new_learner_firstnames" id="new_learner_firstnames" maxlength="70"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_learner_surname" class="col-sm-4 control-label fieldLabel_compulsory">New Surname:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="new_learner_surname" id="new_learner_surname" maxlength="70"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_learner_address_line_1" class="col-sm-4 control-label fieldLabel_compulsory">New Address Line 1:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="new_learner_address_line_1" id="new_learner_address_line_1" maxlength="70"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_learner_address_line_2" class="col-sm-4 control-label fieldLabel_compulsory">New Address Line 2:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="new_learner_address_line_2" id="new_learner_address_line_2" maxlength="70"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_learner_address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">New Address Line 3:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="new_learner_address_line_3" id="new_learner_address_line_3" maxlength="70"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_learner_address_line_4" class="col-sm-4 control-label fieldLabel_compulsory">New Address Line 4:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="new_learner_address_line_4" id="new_learner_address_line_4" maxlength="70"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_learner_postcode" class="col-sm-4 control-label fieldLabel_compulsory">New Postcode:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="new_learner_postcode" id="new_learner_postcode" maxlength="10"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_learner_email" class="col-sm-4 control-label fieldLabel_compulsory">New Email:</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" name="new_learner_email" id="new_learner_email" maxlength="100"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_learner_telephone" class="col-sm-4 control-label fieldLabel_compulsory">New Telephone:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="new_learner_telephone" id="new_learner_telephone" maxlength="100"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_learner_mobile" class="col-sm-4 control-label fieldLabel_compulsory">New Mobile:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="new_learner_mobile" id="new_learner_mobile" maxlength="100"/>
                            </div>
                        </div>
                    </div>
                    <div class="sectionBil" style="display: none;">
                        <div class="form-group">
                            <label for="bil_last_date" class="col-sm-4 control-label fieldLabel_compulsory">Last date of learning:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::datebox('bil_last_date', ''); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bil_reason" class="col-sm-4 control-label fieldLabel_compulsory">Reason of break in learning:</label>
                            <div class="col-sm-8">
                                <textarea name="bil_reason" id="bil_reason" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bil_return_date" class="col-sm-4 control-label fieldLabel_compulsory">Date of expected return:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::datebox('bil_return_date', ''); ?>
                            </div>
                        </div>
                    </div>
                    <div class="sectionLldd" style="display: none;">
                        <div class="form-group">
                            <label for="LLDD" class="col-sm-12 fieldLabel_compulsory">Does learner consider to have a learning difficulty, health problem or disability?:</label>
                            <div class="col-sm-12">
                                <?php echo HTML::selectChosen('l14', $ddlLldd, '', true, true); ?>
                            </div>
                        </div>
                        <div class="form-group" id="divLLDDCat">
                            <div class="col-sm-12">
                                <label>Select categories:</label>
                                <table class="table table-bordered table-hover">
                                    <tr><th>Category</th><th>Primary
                                            <small>(only one)</small></th></tr>
                                    <?php
                                    foreach($ddlLlddCat AS $key => $value)
                                    {
                                        echo '<tr><td><input class="clsICheck" type="checkbox" name="lldd_cat[]" value="'.$key.'" /><label>'.$value.'</label></td>';
                                        echo '<td align="center"><p><input type="radio" name="primary_lldd" value="'.$key.'" ></p></td></tr>';
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="comments" class="col-sm-4 control-label fieldLabel_compulsory">Comments:</label>
                        <div class="col-sm-8">
                            <textarea name="comments" id="comments" class="form-control"></textarea>
                        </div>
                    </div>
				</div>

			</div>

		</div>
		

	</form>
</div>
<div class="row">
    <div class="col-sm-8">
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
</div>
<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script language="JavaScript">

    function showHideFields(value)
    {
        $("div.sectionCoe").hide();
        $("div.sectionCol").hide();
        $("div.sectionBil").hide();
        $("div.sectionLldd").hide();

        if(value == 'Change of Learner Details')
        {
            $("div.sectionCol").show();
        }
        if(value == 'Change of Employer')
        {
            $("div.sectionCoe").show();
        }
        if(value == 'Break in Learning')
        {
            $("div.sectionBil").show();
        }
        if(value == 'Change of LLDD')
        {
            $("div.sectionLldd").show();
        }
    }

	$(function() {

        <?php 
        if(isset($choc))
        {
            echo "showHideFields('{$choc->choc_type}');";
        }
        ?>

		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy'
		});

		$('.datebox').attr('class', 'datepicker');

        $("select[name=choc_type]").on("change", function(){
            if(this.value == '')
            {
                return;
            }
            showHideFields(this.value);
        });

        
        $('input[type=radio]').iCheck({
            radioClass: 'iradio_square-blue'
        });

        $('input[type=checkbox]').not(".yes_no_toggle").each(function(){

            var self = $(this);
            var label = self.next();
            var label_text = label.text();
            var checkboxClass;

            if (this.checked) {
                checkboxClass = 'icheckbox_line-green';
            } else  {
                checkboxClass = 'icheckbox_line-blue';
            }
            label.remove();
            self.iCheck({
                checkboxClass: checkboxClass,
                insert: '<div class="icheck_line-icon"></div>' + label_text
            });

        });

	});

    $(document).on('ifChanged', '[type=checkbox]', function() {
            var self = $(this);
            var label = self.parent();
            var label_text = label.text();
            var checkboxClass;
            if (this.checked) {
                checkboxClass = 'icheckbox_line-green';
            } else  {
                checkboxClass = 'icheckbox_line-blue';
            }
            self.iCheck({
                checkboxClass: checkboxClass,
                insert: '<div class="icheck_line-icon"></div>' + label_text
            });



        }).trigger('ifChanged');


	function saveFrmChoc()
	{
		var frmChoc = document.forms["frmChoc"];
		if(validateForm(frmChoc) == false)
		{
			return false;
		}
		frmChoc.submit();
	}

    function new_employer_onchange(employer, event) 
    {
        var f = employer.form;

        var employer_locations = document.getElementById('new_employer_location');
        var employer_line_managers = document.getElementById('new_employer_line_manager');

        if (employer.value != '') 
        {
            employer.disabled = true;

            employer_locations.disabled = true;
            ajaxPopulateSelect(employer_locations, 'do.php?_action=ajax_load_account_manager&subaction=load_employer_locations&employer_id=' + employer.value);
            employer_locations.disabled = false;

            employer_line_managers.disabled = true;
            ajaxPopulateSelect(employer_line_managers, 'do.php?_action=ajax_load_account_manager&subaction=load_organisation_contacts&employer_id=' + employer.value);
            employer_line_managers.disabled = false;

            employer.disabled = false;
        } 
        else 
        {
            emptySelectElement(employer_locations);
        }
    }

</script>

</body>
</html>