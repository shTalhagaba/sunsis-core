
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Training Scheduler</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="module_tracking/css/calendar_navigation.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/jquery.timepicker.css" />

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>
</head>
<body class="table-responsive">
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Training Scheduler</div>
            <div class="ButtonBar">
		        <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
            </div>
            <div class="ActionIconBar">
                <span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"></span>
                <span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="window.location.href='do.php?_action=view_crm_scheduler&subaction=export_csv'" title="Export to .CSV file"></span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4">
            <form class="form-horizontal" id="frmAddTrainingSchedule" action="do.php?_action=ajax_helper" method="post">
                <input type="hidden" name="subaction" value="add_training_schedule">

                <div class="box box-primary box-solid" style="margin-top: 15px;">
                    <div class="box-header">
                        <span class="box-title">Use this panel to create CRM scheduler Entry</span>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="level" class="col-sm-4 control-label fieldLabel_compulsory">Level:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('level', AppHelper::duplexTrainingLevelsDdl(), ''); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="training_date" class="col-sm-4 control-label fieldLabel_compulsory">Training Date:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::datebox('training_date', '', true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="duration" class="col-sm-4 control-label fieldLabel_compulsory">Duration (days):</label>
                            <div class="col-sm-8">
                                <?php
                                $duration_ddl = [];
                                for($i = 1; $i <= 30; $i++)
                                {
                                    $duration_ddl[] = [$i, $i];
                                }
                                echo HTML::selectChosen('duration', $duration_ddl, '');
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="training_end_date" class="col-sm-4 control-label fieldLabel_compulsory">Training End Date:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::datebox('training_end_date', '', true); ?>
                            </div>
                        </div>
			            <div class="form-group">
							<label for="start_time" class="col-sm-4 control-label fieldLabel_optional">Start Time:</label>
							<div class="col-sm-8">
								<?php echo HTML::timebox('start_time', '', false); ?>
							</div>
						</div>
                        <div class="form-group">
							<label for="end_time" class="col-sm-4 control-label fieldLabel_optional">End Time:</label>
							<div class="col-sm-8">
								<?php echo HTML::timebox('end_time', '', false); ?>
							</div>
						</div>
                        <div class="form-group">
                            <label for="level" class="col-sm-4 control-label fieldLabel_compulsory">Capacity:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::textbox('capacity', '', 'onkeypress="return numbersonly();" maxlength="2" id="capacity" class="form-control"'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trainer" class="col-sm-4 control-label fieldLabel_compulsory">Trainer:</label>
                            <div class="col-sm-8">
                                <?php
                                $trainers_ddl = DAO::getResultset($link, "SELECT id, CONCAT(firstnames, ' ', surname), job_role FROM users WHERE users.type = 2 ORDER BY job_role, firstnames");
                                echo HTML::selectChosen('trainer', $trainers_ddl, '');
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="venue" class="col-sm-4 control-label fieldLabel_compulsory">Venue:</label>
                            <div class="col-sm-8">
                                <?php /*echo HTML::textbox('venue', '', 'maxlength="150" id="venue" class="form-control"'); */?>
                                <!-- <input class="form-control" type="search" list="listVenue" name="venue" id="venue" value="" maxlength="150" /> -->
                                <?php echo HTML::selectChosen('venue', DAO::getResultset($link, "SELECT venue, venue, NULL FROM lookup_crm_training_schedule_venue ORDER BY venue"), '', true); ?>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success btn-sm btn-block"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-8">
            <div class="row">
                <div class="col-sm-12">
                    <?php echo $view->getFilterCrumbs(); ?>
                </div>
                <div class="col-sm-12">
                    <div id="div_filters" style="display:none" class="small">
                        <form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter" name="applyFilter">
                            <input type="hidden" name="_action" value="view_crm_scheduler" />

                            <div id="filterBox" class="clearfix">
                                <fieldset>
                                    <div class="field float"><label>Venue:</label><?php echo $view->getFilterHTML('filter_venue'); ?></div>
                                    <div class="field float"><label>Training date between</label><?php echo $view->getFilterHTML('filter_from_training_date'); ?>&nbsp;and <?php echo $view->getFilterHTML('filter_to_training_date'); ?></div>
                                    <div class="field float"><label>Order by:</label> <?php echo $view->getFilterHTML(VoltView::KEY_ORDER_BY); ?></div>
                                    <div class="field float"><label>Records per page:</label> <?php echo $view->getFilterHTML(VoltView::KEY_PAGE_SIZE); ?></div>
                                </fieldset>
                                <fieldset>
                                    <input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms['applyFilter']);" value="Reset" />&nbsp;
                                </fieldset>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="cols-sm-12">
                    <div class="">
                        <?php echo $this->renderView($link, $view); ?>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<datalist id="listVenue">
    <?php
    $_venues  = DAO::getSingleColumn($link, "SELECT DISTINCT venue FROM crm_training_schedule");
    foreach($_venues AS $_ven)
    {
        echo "<option value=\"{$_ven}\">";
    }
    ?>
</datalist>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>


<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>
<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker.js"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script type="text/javascript" src="/calendarPopup/CalendarPopup.js"></script>

<!-- Initialise calendar popup -->
<script type="text/javascript">

    function div_filter_crumbs_onclick(div)
    {
        showHideBlock(div);
        showHideBlock('div_filters');
    }

    <?php if(preg_match('/MSIE [1-6]/', $_SERVER['HTTP_USER_AGENT']) ) { ?>
    var calPop = new CalendarPopup();
    calPop.showNavigationDropdowns();
    <?php } else { ?>
    var calPop = new CalendarPopup("calPop1");
    calPop.showNavigationDropdowns();
    document.write(getCalendarStyles());
    <?php } ?>
</script>

<script>
    $(function(){
        $('#start_time').attr('class', 'timebox optional form-control');
        $('#end_time').attr('class', 'timebox optional form-control');

		$(".timebox").timepicker({ 
            timeFormat: 'H:i',
            minTime: '08:00:00'
        });

		$('.timebox').bind('timeFormatError timeRangeError', function() {
			this.value = '';
			alert("Please choose a valid time");
			this.focus();
		});
    });

    $('#frmAddTrainingSchedule').validate({
        rules: {
            training_date: {
                required: true
            },
            level: {
                required: true
            },
            capacity: {
                required: true,
                min: 1,
                max: 99
            }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    window.location.reload();
                }
            });
        }
    });

    function saveEntry(entry_id)
    {
        var level = $('#level'+entry_id).val();
        var training_date = $('#input_date'+entry_id).val();
        var duration = $('#duration'+entry_id).val();
        var training_end_date = $('#input_training_end_date'+entry_id).val();
	    var start_time = $('#input_start_time'+entry_id).val();
        var end_time = $('#input_end_time'+entry_id).val();
        var capacity = $('#capacity'+entry_id).val();
        var trainer = $('#trainer'+entry_id).val();
        var venue = $('#venue'+entry_id).val();

        var qs = '&id='+encodeURIComponent(entry_id) +
            '&level='+encodeURIComponent(level) +
            '&training_date='+encodeURIComponent(training_date) +
            '&duration='+encodeURIComponent(duration) +
            '&training_end_date='+encodeURIComponent(training_end_date) +
	    '&start_time='+encodeURIComponent(start_time) +
            '&end_time='+encodeURIComponent(end_time) +	
            '&capacity='+encodeURIComponent(capacity) +
            '&trainer='+encodeURIComponent(trainer) +
            '&venue='+encodeURIComponent(venue)
        ;
        var client = ajaxRequest('do.php?_action=ajax_helper&subaction=update_training_schedule'+qs);
        if(client)
        {
            window.location.reload();
        }
    }

    function deleteEntry(entry_id)
    {
        if(!confirm("Are you sure you want to remove this entry?"))
        {
            return;
        }

        var client = ajaxRequest('do.php?_action=ajax_helper&subaction=delete_training_schedule&id='+entry_id);
        if(client)
        {
            if(client.responseText.startsWith("Error"))
            {
                alert(client.responseText);
                return false;
            }

            window.location.reload();
        }
    }

    function duration_onchange(duration)
    {
        // console.log(duration.value);
        // var training_date = stringToDate($('#input_training_date').val());
        // var training_end_date = new Date();
        // training_end_date.setDate(training_date.getDay() + duration.value);
        // console.log(formatDateGB(training_end_date));
    }
</script>
</body>
</html>