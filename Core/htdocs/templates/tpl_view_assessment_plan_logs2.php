<?php /* @var $view ViewAssessmentPlanLogs */ ?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Assessment Plan Logs Report 2</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        body {

        }
    </style>
</head>
<body class="table-responsive">
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Assessment Plan Logs Report 2</div>
            <div class="ButtonBar">

            </div>
            <div class="ActionIconBar">
                <span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"></span>
	            <span class="btn btn-sm btn-info fa fa-check-square" onclick="showHideBlock('div_columnsSelector');" title="Choose columns you want to see"></span>
                <span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="window.location.href='do.php?_action=view_assessment_plan_logs2&subaction=export_csv'" title="Export to .CSV file"></span>
                <span class="btn btn-sm btn-info fa fa-refresh" onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"></span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <?php echo $view->getFilterCrumbs(); ?>
    </div>
</div>

<div class="row">
	<div class="col-lg-12">
		<form name="frm_columnsSelector" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<div id="div_columnsSelector" class="small" align="center" style="margin-top:10px;margin-bottom:20px;<?php echo 'display:none'; ?>">
				<table class="table row-border bg-gray">
					<caption class="text-bold text-info">Choose columns you want to see</caption>
					<tr>
						<td>
							<?php
							$columns = $view->getColumns($link);
							foreach($columns AS &$column)
							{
								$column[1] = ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column[1])));
							}
							echo HTML::checkBoxGrid('columns', $columns, $view->getSelectedColumnsNumbers($link), 10);
							?>
						</td>
					</tr>
					<tr>
						<td><span class="btn btn-block btn-primary" onclick="changeColumns();"> Click to view your selected columns </span></td>
					</tr>
				</table>
			</div>
		</form>
	</div>
</div>

<div class="container-fluid">

    <div class="row">
        <div id="div_filters" style="display:none">

            <form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
                <input type="hidden" name="_action" value="view_assessment_plan_logs2" />
                <input type="hidden" id="filter_name" name="filter_name" value="" />
                <input type="hidden" id="filter_id" name="filter_id" value="" />


                <div id="filterBox" class="clearfix small">
                    <fieldset>
                        <legend>General</legend>
                        <div class="field float"><label>Record Status:</label><?php echo $view->getFilterHTML('filter_record_status'); ?></div>
                        <div class="field float"><label>Assessor:</label><?php echo $view->getFilterHTML('filter_assessor'); ?></div>
                        <div class="field float"><label>Person Reviewed:</label><?php echo $view->getFilterHTML('filter_person_reviewed'); ?></div>
                        <div class="field float"><label>Learner ULN:</label><?php echo $view->getFilterHTML('filter_uln'); ?></div>
                        <div class="field float"><label>Learner Reference:</label><?php echo $view->getFilterHTML('filter_l03'); ?></div>
                        <div class="field float"><label>Learner Firstnames:</label><?php echo $view->getFilterHTML('firstnames'); ?></div>
                        <div class="field float"><label>Learner Surname:</label><?php echo $view->getFilterHTML('surname'); ?></div>
                        <div class="field float"><label>Paperwork:</label><?php echo $view->getFilterHTML('filter_paperwork'); ?></div>
                        <div class="field float"><label>Employer:</label><?php echo $view->getFilterHTML('filter_employer'); ?></div>
                        <div class="field float"><label>IDs:</label><?php echo $view->getFilterHTML('filter_trs'); ?></div>
                        <div class="field float"><label>Manager:</label><?php echo $view->getFilterHTML('filter_manager'); ?></div>
                        <div class="field float"><label>Routway:</label><?php echo $view->getFilterHTML('filter_routways'); ?></div>
                    </fieldset>
                    <fieldset>
                        <legend>Dates:</legend>
                        <!--<div class="field newrow"></div>
                        <div class="field float"><label>Gateway forecast between</label><?php //echo $view->getFilterHTML('gateway_forecast_from'); ?>&nbsp;and <?php //echo $view->getFilterHTML('gateway_forecast_to'); ?></div>-->
                        <div class="field newrow"></div>
                        <div class="field float"><label>Start date between</label><?php echo $view->getFilterHTML('start_date_from'); ?>&nbsp;and <?php echo $view->getFilterHTML('start_date_to'); ?></div>
                        <div class="field newrow"></div>
                        <div class="field float"><label>Actual date between</label><?php echo $view->getFilterHTML('last_start_date'); ?>&nbsp;and <?php echo $view->getFilterHTML('last_end_date'); ?></div>
                        <div class="field newrow"></div>
                        <div class="field float"><label>Marked date between</label><?php echo $view->getFilterHTML('filter_from_marked_date'); ?>&nbsp;and <?php echo $view->getFilterHTML('filter_to_marked_date'); ?></div>
                        <div class="field newrow"></div>
                        <div class="field float"><label>Signed off date between</label><?php echo $view->getFilterHTML('filter_from_signed_off_date'); ?>&nbsp;and <?php echo $view->getFilterHTML('filter_to_signed_off_date'); ?></div>
                        <div class="field newrow"></div>
                        <div class="field float"><label>Due date between</label><?php echo $view->getFilterHTML('due_start_date'); ?>&nbsp;and <?php echo $view->getFilterHTML('due_end_date'); ?></div>
                        <div class="field newrow"></div>
                        <div class="field float"><label>Assessor signed off between</label><?php echo $view->getFilterHTML('filter_from_assessor_signed_off'); ?>&nbsp;and <?php echo $view->getFilterHTML('filter_to_assessor_signed_off'); ?></div>
                    </fieldset>
                    <fieldset>
                        <legend>Options:</legend>
                        <div class="field float"><label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></div>
                        <div class="field float"><label>Sort By:</label> <?php echo $view->getFilterHTML('order_by'); ?></div>
                    </fieldset>
                    <fieldset>
                        <input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" />
                    </fieldset>
                </div>
            </form>
        </div>

    </div>
    <div class="row">
        <?php $view->render($link, $view->getSelectedColumns($link)); ?>
    </div>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script type="text/javascript" src="/calendarPopup/CalendarPopup.js"></script>

<!-- Initialise calendar popup -->
<script type="text/javascript">
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
    function div_filter_crumbs_onclick(div)
    {
        showHideBlock(div);
        showHideBlock('div_filters');
    }
    function showApProgressLookup(tr_id)
    {
        $.ajax({
            type:'GET',
            async: false,
            url:'do.php?_action=ajax_tracking&subaction=showApProgressLookup&tr_id='+encodeURIComponent(tr_id),
            success: function(response) {
                $('<div>'+response+'</div>')
                        .dialog({
                            title: 'Lookup',
                            resizable: true,
                            height:'auto',
                            width:'auto',
                            modal: true,
                            buttons: {
                                OK: function() {
                                    $(this).dialog('close');
                                }
                            }
                        }).css("background", "#FFF");
            },
            error: function(data, textStatus, xhr){
                console.log(data.responseText);
            }
        });


    }

    function changeColumns()
    {
	    var viewName = "<?php echo $view->getViewName()?>";
	    var $checkboxes = $('input[type="checkbox"][name^="columns"]:not(:checked)'); // find unchecked boxes
	    var columns = new Array();
	    for(var i = 0; i < $checkboxes.length; i++)
	    {
		    var obj = {
			    view:viewName,
			    colum:$checkboxes[i].parentNode.title,
			    visible:0
		    };
		    columns.push(obj);
	    }
	    var json = JSON.stringify(columns);
	    var post = "json=" + encodeURIComponent(json) + "&view=" + encodeURIComponent(viewName);
	    var client = ajaxRequest("do.php?_action=ajax_save_columns", post);
	    if(client){
		    window.location.reload();
	    }
    }
</script>
</body>
</html>