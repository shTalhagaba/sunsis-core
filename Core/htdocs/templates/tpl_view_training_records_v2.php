
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Training records</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="module_tracking/css/calendar_navigation.css">

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body class="table-responsive">
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">View Training Records</div>
            <div class="ButtonBar small" style="margin-left: 15px;">
                <form class="small" method="get" name="preferences" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="_action" value="view_training_records_v2" />
                    <?php
                    foreach($view->getViewSections() AS $section)
                    {
                        echo '<input type="hidden" name="' . get_class($view) . '_' . $section . '" value="' . $view->getPreference($section) . '" />';
                        $checked = $view->getPreference($section) == '1' ? 'checked="checked"' : '';
                        echo '<input type="checkbox" name="' . $section . '_ui" value="1" ' . $checked . ' onclick="this.form.elements[\'' . get_class($view).'_'.$section.'\'].value=(this.checked?\'1\':\'0\');this.form.submit();" />&nbsp;'.ucwords($section).' &nbsp; &nbsp;';
                    }
                    ?>
                </form>
            </div>
            <div class="ActionIconBar">
                <span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"></span>
                <span class="btn btn-sm btn-info fa fa-check-square" onclick="showHideBlock('div_columnsSelector');" title="Choose columns you want to see"></span>
                <span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="exportToExcel('view_ViewTrainingRecordsV2')" title="Export to .CSV file"></span>
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
        <div class="col-sm-12">

            <div id="div_filters" style="display:none" class="small">
                <form name="savedFilters" method="get" action="#" id="applySavedFilter">
                    <input type="hidden" name="_action" value="view_training_records_v2" />
                    <?php echo $view->getSavedFiltersHTML(); ?>
                </form>
                <form name="filters" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
                    <input type="hidden" name="_action" value="view_training_records_v2" />
                    <input type="hidden" id="filter_name" name="filter_name" value="" />
                    <input type="hidden" id="filter_id" name="filter_id" value="" />

                    <div id="filterBox" class="clearfix">
                        <fieldset>
                            <legend>Learner</legend>
                            <div class="field float"><label>Learner Reference (L03):</label><?php echo $view->getFilterHTML('filter_l03'); ?></div>
                            <div class="field float"><label>Surname:</label><?php echo $view->getFilterHTML('filter_surname'); ?></div>
                            <div class="field float"><label>First Name:</label><?php echo $view->getFilterHTML('filter_firstnames'); ?></div>
                            <div class="field float"><label title="Unique Learner Number" abbr="Unique Learner Number">ULN:</label><?php echo $view->getFilterHTML('filter_uln'); ?></div>
                            <div class="field float"><label>National Insurance:</label><?php echo $view->getFilterHTML('filter_ni'); ?></div>
                            <div class="field float"><label>DOB:</label><?php echo $view->getFilterHTML('filter_dob'); ?></div>
                            <div class="field float"><label>Gender:</label><?php echo $view->getFilterHTML('filter_gender'); ?></div>
                            <div class="field float"><label>Ethnicity:</label><?php echo $view->getFilterHTML('filter_ethnicity'); ?></div>
                            <div class="field float"><label>TR IDs:</label><?php echo $view->getFilterHTML('filter_tr_ids'); ?></div>
                            <?php if(DB_NAME == "am_baltic_demo") { ?>
                            <div class="field float"><label>Tag:</label><?php echo $view->getFilterHTML('filter_tag'); ?></div>
                            <?php } ?>
                        </fieldset>
                        <fieldset>
                            <legend>Training Record</legend>
                            <div class="field float"><label>Status:</label><?php echo $view->getFilterHTML('filter_record_status'); ?></div>
			    <div class="field float"><label>Gateway Learners:</label><?php echo $view->getFilterHTML('filter_gateway'); ?></div>	
                            <div class="field float"><label>Contract:</label><?php echo $view->getFilterHTML('filter_contract'); ?></div>
                            <div class="field float"><label>Contract Year:</label><?php echo $view->getFilterHTML('filter_contract_year'); ?></div>
                            <div class="field float"><label>Restart:</label><?php echo $view->getFilterHTML('filter_restart'); ?></div>
                            <div class="field float"><label>Progress:</label><?php echo $view->getFilterHTML('filter_progress'); ?></div>
                            <div class="field float"><label>Employer:</label><?php echo $view->getFilterHTML('filter_employer'); ?></div>
                            <div class="field float"><label>Employer Locations:</label><?php echo $view->getFilterHTML('filter_employer_locations'); ?></div>
                            <div class="field float"><label>Provider:</label><?php echo $view->getFilterHTML('filter_provider'); ?></div>
                            <div class="field float"><label>Provider Locations:</label><?php echo $view->getFilterHTML('filter_provider_locations'); ?></div>
                            <div class="field float"><label>Course:</label><?php echo $view->getFilterHTML('filter_course'); ?></div>
                            <div class="field float"><label>Apprenticeship Title:</label><?php echo $view->getFilterHTML('filter_apprenticeship_title'); ?></div>
                            <div class="field float"><label>Framework:</label><?php echo $view->getFilterHTML('filter_framework'); ?></div>
                            <div class="field float"><label>Framework Code:</label><?php echo $view->getFilterHTML('filter_framework_code'); ?></div>
                            <div class="field float"><label>Standard Code:</label><?php echo $view->getFilterHTML('filter_standard_code'); ?></div>
                            <div class="field float"><label>Group:</label><?php echo $view->getFilterHTML('filter_group'); ?></div>
                            <!-- <div class="field float"><label>Group Title contains:</label><?php echo $view->getFilterHTML('filter_group_title'); ?></div> -->
                            <div class="field float"><label>Flag:</label><?php echo $view->getFilterHTML('filter_flags'); ?></div>
                        </fieldset>
                        <fieldset>
                            <legend>Users</legend>
                            <div class="field float"><label>Assessor:</label><?php echo $view->getFilterHTML('filter_assessor'); ?></div>
                            <div class="field float"><label>Verifier:</label><?php echo $view->getFilterHTML('filter_verifier'); ?></div>
                            <div class="field float"><label>Account Relationship Manager:</label><?php echo $view->getFilterHTML('filter_arm'); ?></div>
                        </fieldset>
                        <fieldset>
                            <legend>Dates</legend>
                            <div class="field float"><label>Start Date between:</label><?php echo $view->getFilterHTML('from_start_date'); ?> &nbsp;and&nbsp; <?php echo $view->getFilterHTML('to_start_date'); ?></div>
                            <div class="field float"><label>Planned End Date between:</label><?php echo $view->getFilterHTML('from_target_date'); ?> &nbsp;and&nbsp; <?php echo $view->getFilterHTML('to_target_date'); ?></div>
                            <div class="field float"><label>End Date between:</label><?php echo $view->getFilterHTML('from_closure_date'); ?> &nbsp;and&nbsp; <?php echo $view->getFilterHTML('to_closure_date'); ?></div>
                            <div class="field float"><label>Marked Date between:</label><?php echo $view->getFilterHTML('from_marked_date'); ?> &nbsp;and&nbsp; <?php echo $view->getFilterHTML('to_marked_date'); ?></div>
                            <div class="field float"><label>Created Date between:</label><?php echo $view->getFilterHTML('from_created_date'); ?> &nbsp;and&nbsp; <?php echo $view->getFilterHTML('to_created_date'); ?></div>
                            <div class="field float"><label>Induction Date between:</label><?php echo $view->getFilterHTML('from_induction_date'); ?> &nbsp;and&nbsp; <?php echo $view->getFilterHTML('to_induction_date'); ?></div>
			    <div class="field float"><label>Gateway Forecast Date between:</label><?php echo $view->getFilterHTML('from_gateway_forecast'); ?> &nbsp;and&nbsp; <?php echo $view->getFilterHTML('to_gateway_forecast'); ?></div>
                        </fieldset>
                        <fieldset>
                            <legend>Options:</legend>
                            <div class="field float"><label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></div>
                            <div class="field float"><label>Sort By:</label> <?php echo $view->getFilterHTML(View::KEY_ORDER_BY); ?></div>
                        </fieldset>
                        <fieldset>
                            <input type="submit" value="Apply"/> &nbsp;
                            <!--							<input type="button" onclick="resetViewFilters(document.forms['filters']);" value="Reset" /> &nbsp;-->
                            <input type="button" onclick="resetFilters();" value="Reset" /> &nbsp;
                            <input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12"><?php echo $view->render($link, $view->getSelectedColumns($link)); ?></div>
    </div>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script type="text/javascript" src="/calendarPopup/CalendarPopup.js"></script>


<script type="text/javascript">

    function div_filter_crumbs_onclick(div)
    {
        showHideBlock(div);
        showHideBlock('div_filters');
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

    $('#form applyFilter').keydown(function(e) {
        if (e.keyCode == 13) {
            $('#form').submit();
        }
    });

    function resetFilters()
    {
        var form = document.forms["filters"];
        resetViewFilters(form);

        if ( $('#grid_filter_contract').length )
        {
            var grid = document.getElementById('grid_filter_contract');
            grid.resetGridToDefault();
        }
        if ( $('#grid_filter_record_status').length )
        {
            var grid = document.getElementById('grid_filter_record_status');
            grid.resetGridToIndex(1);
        }
    }

    function ViewTrainingRecords_filter_course_onchange(course)
    {

        // Lock this element
        course.disabled = true;

        var f = document.forms['filters'];
        var group = f.elements['ViewTrainingRecords_filter_group'];

        if(course.value == '')
        {
            // Clear group dropdown
            emptySelectElement(group);
            group.options[0] = new Option("","");
            group.selectedIndex = 0;
        }
        else
        {
            group.disabled = true;

            var url = 'do.php?_action=ajax_load_group_dropdown&course_id=' + course.value;
            ajaxPopulateSelect(group, url);
            group.disabled = false;
        }

        course.disabled = false;
    }

    function ViewTrainingRecords_filter_record_status_onchange(record_status)
    {

        // Lock this element
        record_status.disabled = true;

        var f = document.forms['filters'];
        var group = f.elements['ViewTrainingRecords_filter_group'];

        if(record_status.value == '')
        {
            // Clear group dropdown
            emptySelectElement(group);
            group.options[0] = new Option("","");
            group.selectedIndex = 0;
        }
        else
        {
            group.disabled = true;

            var url = 'do.php?_action=ajax_load_group_dropdown&record_status=' + record_status.value;
            ajaxPopulateSelect(group, url);
            group.disabled = false;
        }

        record_status.disabled = false;
    }

    function ViewTrainingRecords_filter_employer_onchange(employer)
    {

        // Lock this element
        employer.disabled = true;

        var f = document.forms['filters'];
        var locations = f.elements['ViewTrainingRecords_filter_locations'];

        if(employer.value == '')
        {
            // Clear group dropdown
            emptySelectElement(locations);
            locations.options[0] = new Option("","");
            locations.selectedIndex = 0;
        }
        else
        {
            locations.disabled = true;

            var url = 'do.php?_action=ajax_load_location_dropdown&org_id=' + employer.value;
            ajaxPopulateSelect(locations, url);
            locations.disabled = false;
        }

        employer.disabled = false;
    }

    <!-- Initialise calendar popup -->
    <?php if(preg_match('/MSIE [1-6]/', $_SERVER['HTTP_USER_AGENT']) ) { ?>
    var calPop = new CalendarPopup();
    calPop.showNavigationDropdowns();
    <?php } else { ?>
    var calPop = new CalendarPopup("calPop1");
    calPop.showNavigationDropdowns();
    document.write(getCalendarStyles());
    <?php } ?>

</script>

</body>
</html>