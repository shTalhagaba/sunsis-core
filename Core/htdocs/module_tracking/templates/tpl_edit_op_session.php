<?php /* @var $session OperationsSession */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $session->id == ''?'Create Event':'Edit Event'; ?></title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/chosen/bootstrap-chosen.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/jquery.timepicker.css" />

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

        th.ui-datepicker-week-end,
        td.ui-datepicker-week-end {
            pointer-events:none;
            opacity:0.4;
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
            <div class="Title" style="margin-left: 6px;"><?php echo $session->id == ''?'Create Event':'Edit Event'; ?></div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
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
    <div class="col-md-10">

        <div class="box box-primary callout">
            <form class="form-horizontal" name="frmSession" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="id" value="<?php echo $session->id; ?>" />
                <input type="hidden" name="_action" value="save_op_session" />
                <div class="box-header with-border"><h2 class="box-title">Event <small>enter event information</small></h2>
                    <div class="box-tools pull-right"></div>
                </div>
                <div class="box-body">
                    <!--				<div class="form-group">
					<label for="title" class="col-sm-4 control-label fieldLabel_compulsory">Title:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control compulsory" name="title" id="title" value="<?php /*echo $session->title; */?>" maxlength="149" />
					</div>
				</div>
-->
                    <div class="form-group">
                        <label for="unit_ref" class="col-sm-4 control-label fieldLabel_compulsory">Unit:</label>
                        <div class="col-sm-8">
                            <?php
                            echo $session->id == '' ?
                                HTML::checkboxGrid('unit_ref', InductionHelper::getTrackingUnitsForDDL($link), [], 3, true) :
                                HTML::checkboxGrid('unit_ref', InductionHelper::getTrackingUnitsForDDL($link), explode(',', $session->unit_ref), 3, true);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tracker_id" class="col-sm-4 control-label fieldLabel_compulsory">Programme:</label>
                        <div class="col-sm-8" style="border-style: ridge">
                            <?php //echo HTML::checkboxGrid('tracker_id', DAO::getResultset($link, "SELECT id, title, null FROM op_trackers ORDER BY title"), explode(',', $session->tracker_id), true, true); ?>
			    <div class="checkbox" style="width: 5%;padding-left:15px; padding-right:5px">
                                <label>
                                    <input type="checkbox" name="chkAllTrackers" value="ALL" disabled="disabled" onclick="selectAll(this);"> All
                                </label>
                            </div>
			    <?php
                            echo $session->id == '' ?
                                HTML::checkboxGrid('tracker_id', DAO::getResultset($link, "SELECT id, title, null FROM op_trackers WHERE title NOT LIKE 'z%' ORDER BY title"), explode(',', $session->tracker_id), true, true) :
                                HTML::checkboxGrid('tracker_id', DAO::getResultset($link, "SELECT id, title, null FROM op_trackers ORDER BY title"), explode(',', $session->tracker_id), true, true);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="event_type" class="col-sm-4 control-label fieldLabel_compulsory">Event Type:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('event_type', InductionHelper::getDDLEventTypes(), $session->event_type, true, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="personnel" class="col-sm-4 control-label fieldLabel_compulsory">Trainer:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('personnel', InductionHelper::getDDLOpTrainers($link), $session->personnel, true, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_start_date" class="col-sm-4 control-label fieldLabel_compulsory">Start Date:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::datebox('start_date', $session->start_date, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="start_time" class="col-sm-4 control-label fieldLabel_compulsory">Start Time:</label>
                        <div class="col-sm-8">
                            <input class="timebox form-control" type="text" id="start_time" name="start_time" value="<?php echo !is_null($session->start_time)?$session->start_time:'09:00'; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_end_date" class="col-sm-4 control-label fieldLabel_compulsory">End Date:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::datebox('end_date', $session->end_date, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="end_time" class="col-sm-4 control-label fieldLabel_compulsory">End Time:</label>
                        <div class="col-sm-8">
                            <input class="timebox form-control" type="text" id="end_time" name="end_time" value="<?php echo !is_null($session->end_time)?$session->end_time:'17:00'; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="max_learners" class="col-sm-4 control-label fieldLabel_compulsory">Max. Learners:</label>
                        <div class="col-sm-8">
                            <!--<input type="text" class="form-control compulsory" name="max_learners" id="max_learners" value="<?php /*echo $session->max_learners; */?>" maxlength="2" />-->
                            <?php echo HTML::selectChosen('max_learners', $maxLearnersDDL, $session->max_learners, false, true); ?>
                        </div>
                    </div>
		    <div class="form-group">
                        <label for="best_case" class="col-sm-4 control-label fieldLabel_compulsory">Best Case:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('best_case', $maxLearnersDDL, $session->best_case, false, true); ?>
                        </div>
                    </div>	
                    <div class="form-group">
                        <label for="location" class="col-sm-4 control-label fieldLabel_optional">Location:</label>
                        <div class="col-sm-8">
                            <textarea name="location" id="location" style="width: 100%;"><?php echo $session->location; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="test_location" class="col-sm-4 control-label fieldLabel_optional">Test Location:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('test_location', InductionHelper::getDDLTestLocation(), $session->test_location, true, false); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="comments" class="col-sm-4 control-label fieldLabel_optional">Comments:</label>
                        <div class="col-sm-8">
                            <textarea name="comments" id="comments" style="width: 100%;"><?php echo $session->comments; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <?php
                    $disallowed_access = array('lepearson', 'sahutchinson');
                    if($_SESSION['user']->op_access == 'W')
                    {
                        ?>
                        <button type="button" class="btn btn-primary pull-right" onclick="saveFrmSession(); "><i class="fa fa-save"></i> <?php echo $session->id == ''?'Create Event':'Save Event';?></button>
                        <?php
                    }
                    ?>
                </div>
            </form>
        </div>

    </div>

</div>

<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker.js"></script>

<script language="JavaScript">

    $(function() {

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy'
        });

        $(".timebox").timepicker({ timeFormat: 'H:i' });

        $('.timebox').bind('timeFormatError timeRangeError', function() {
            this.value = '';
            alert("Please choose a valid time");
            this.focus();
        });

        $('#input_start_date').attr('class', 'datepicker compulsory form-control');
        $('#input_end_date').attr('class', 'datepicker compulsory form-control');
        $('#start_time').attr('class', 'timebox compulsory form-control');
        $('#end_time').attr('class', 'timebox compulsory form-control');


        <?php if($session->id != '') { ?>
        disableTrackerIDs();
        <?php } ?>

    });

    function disableTrackerIDs()
    {
        var grid_tracker_id = document.getElementById('grid_tracker_id');
        var grid_tracker_id = grid_tracker_id.getElementsByTagName('INPUT');

        var data = '<?php echo $session->tracker_id; ?>';
        data = data.split(',');

        for(var j = 0; j < grid_tracker_id.length; j++)
        {
            if($.inArray(grid_tracker_id[j].value, data) === -1)
            {
                $(grid_tracker_id[j]).attr('disabled', true);
                grid_tracker_id[j].checked = false;
		$(grid_tracker_id[j]).closest('tr').removeClass('bg-warning');
            }
	    else
            {
                $(grid_tracker_id[j]).closest('tr').addClass('bg-warning');
            }
        }
    }

    function selectAll(e)
    {
        var grid_tracker_id = document.getElementById('grid_tracker_id');
        var grid_tracker_id = grid_tracker_id.getElementsByTagName('INPUT');

        if (e.checked) 
        {
            for(var j = 0; j < grid_tracker_id.length; j++)
            {
		if(!$(grid_tracker_id[j]).attr('disabled'))
                grid_tracker_id[j].checked = true;
            }
        }
        else
        {
            for(var j = 0; j < grid_tracker_id.length; j++)
            {
                    grid_tracker_id[j].checked = false;
            }
        }
    }

    $("input[name='unit_ref[]']").on('change', function(){

        var grid_unit_ref = document.getElementById('grid_unit_ref');
        var grid_unit_ref_units = grid_unit_ref.getElementsByTagName('INPUT');

        var values = new Array();
        for(var i = 0; i < grid_unit_ref_units.length; i++)
        {
            if(grid_unit_ref_units[i].checked)
            {
                values[values.length] = encodeURIComponent(grid_unit_ref_units[i].value);
            }
        }

        var grid_tracker_id = document.getElementById('grid_tracker_id');
        var grid_tracker_id = grid_tracker_id.getElementsByTagName('INPUT');

        for(var j = 0; j < grid_tracker_id.length; j++)
        {
            $(grid_tracker_id[j]).attr('disabled', true);
            grid_tracker_id[j].checked = false;
	    $(grid_tracker_id[j]).closest('tr').removeClass('bg-warning');
            $("input[type=checkbox][name=chkAllTrackers]").attr('checked', false);
        }

        if(values.length == 0)
            return;

        $.ajax({
            type:'POST',
            url:'do.php?_action=ajax_tracking&subaction=getApplicableTrackers&key='+JSON.stringify(values),
            success: function(data, textStatus, xhr) {
                data = $.parseJSON(data);

                var grid_tracker_id = document.getElementById('grid_tracker_id');
                var grid_tracker_id = grid_tracker_id.getElementsByTagName('INPUT');

                for(var i= 0; i < data.length; i++)
                {
                    for(var j = 0; j < grid_tracker_id.length; j++)
                    {
                        if(grid_tracker_id[j].value == data[i])
                        {
                            $(grid_tracker_id[j]).attr('disabled', false);
                            grid_tracker_id[j].checked = false;	
		
				$(grid_tracker_id[j]).closest('tr').addClass('bg-warning');

				$("input[type=checkbox][name=chkAllTrackers]").attr('disabled', false);
                        }
                    }
                    console.log(data[i]);
                }
            },
            error: function(data, textStatus, xhr){
                console.log(data);
            }
        });


    });

    /*
    $('#unit_ref').on('change', function(){

        var grid_tracker_id = document.getElementById('grid_tracker_id');
        var grid_tracker_id = grid_tracker_id.getElementsByTagName('INPUT');

        for(var j = 0; j < grid_tracker_id.length; j++)
        {
            $(grid_tracker_id[j]).attr('disabled', false);
            grid_tracker_id[j].checked = false;
        }

        if(this.value == '')
            return;

        $.ajax({
            type:'POST',
            url:'do.php?_action=ajax_tracking&subaction=getNotApplicableTrackers&key='+encodeURIComponent(this.value),
            beforeSend: function(){
                //$("#loading").dialog('open').html("<p><i class=\"fa fa-refresh fa-spin\"></i> Busy ...</p>");
            },
            success: function(data, textStatus, xhr) {
                data = $.parseJSON(data);

                var grid_tracker_id = document.getElementById('grid_tracker_id');
                var grid_tracker_id = grid_tracker_id.getElementsByTagName('INPUT');

                for(var i= 0; i < data.length; i++)
                {
                    for(var j = 0; j < grid_tracker_id.length; j++)
                    {
                        if(grid_tracker_id[j].value == data[i])
                        {
                            $(grid_tracker_id[j]).attr('disabled', true);
                            grid_tracker_id[j].checked = false;
                        }
                    }
                }
                console.log(data);
            },
            error: function(data, textStatus, xhr){
            }
        });

    });

    */
    function saveFrmSession()
    {
        var grid_tracker_id = document.getElementById('grid_tracker_id');
        var grid_tracker_id = grid_tracker_id.getElementsByTagName('INPUT');

        var anySelected = false;
        for(var j = 0; j < grid_tracker_id.length; j++)
        {
            if(grid_tracker_id[j].checked)
            {
                anySelected = true;
                break;
            }
        }

        if(!anySelected)
        {
            return alert('Please select at least one Programme.');
        }

        var frmSession = document.forms["frmSession"];
        if(validateForm(frmSession) == false)
        {
            return false;
        }
	var input_start_date = stringToDate($('#input_start_date').val());
        var input_end_date = stringToDate($('#input_end_date').val());
        input_start_date.setHours(0,0,0,0);
        input_end_date.setHours(0,0,0,0);
        if(input_end_date < input_start_date)
        {
            alert('Start date ' + formatDateGB(input_start_date) + ' cannot be before end date ' + formatDateGB(input_end_date));
            return;
        }

        frmSession.submit();
    }

    var disabled_trainers = [<?php echo DAO::getSingleValue($link, "SELECT GROUP_CONCAT(user_id) FROM lookup_op_trainers WHERE enabled = 'N'")?>];
    $("#personnel option").each(function(){
        if($.inArray(parseInt(this.value), disabled_trainers) != -1)
            $(this).attr('disabled', 'disabled');
    });

</script>

</body>
</html>