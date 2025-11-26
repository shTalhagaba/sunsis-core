<?php /* @var $vo User */  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Training Records</title>
    <link rel="stylesheet" href="/common.css" type="text/css"/>
    <!--[if IE]>
    <link rel="stylesheet" href="/common-ie.css" type="text/css"/>
    <![endif]-->
    <script src="/js/jquery.min.js" type="text/javascript"></script>
    <script src="/common.js" type="text/javascript"></script>

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


    <script type="text/javascript">

        $('#form applyFilter').keydown(function(e) {
            if (e.keyCode == 13) {
                $('#form').submit();
            }
        });

        function validateFilters()
        {

            /*	var f = document.forms[0];

       var e = f.elements['cohort'];

       if(e.value != '')
       {
           var num = parseInt(e.value);
           if(isNaN(num))
           {
               alert("Cohort field accepts numeric values only");
               e.focus();
               return false;
           }
       }
   */
            return true;

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


        function div_filter_crumbs_onclick(div)
        {
            showHideBlock(div);
            showHideBlock('div_filters');
        }


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
    </script>
    <script type="text/javascript">
        var GB_ROOT_DIR = "/assets/js/greybox/";
    </script>
    <script type="text/javascript" src="/assets/js/greybox/AJS.js"></script>
    <script type="text/javascript" src="/assets/js/greybox/AJS_fx.js"></script>
    <script type="text/javascript" src="/assets/js/greybox/gb_scripts.js"></script>
    <link href="/assets/js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />



</head>

<body>
<div class="banner">
    <div class="Title">Training Records</div>
    <div class="ButtonBar">
        <form method="get" name="preferences" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="_action" value="view_training_records" />
            <input type="hidden" name="<?php echo get_class($view).'_'; ?>showAttendanceStats" value="<?php echo $view->getPreference('showAttendanceStats')?>" />
            <!--<input type="hidden" name="<?php /*echo get_class($view).'_'; */?>showProgressStats" value="<?php /*echo $view->getPreference('showProgressStats')*/?>" />-->
            <input type="checkbox" name="showAttendanceStats_ui" value="1" <?php echo $view->getPreference('showAttendanceStats')=='1'?'checked="checked"':''; ?> onclick="this.form.elements['<?php echo get_class($view).'_'; ?>showAttendanceStats'].value=(this.checked?'1':'0');this.form.submit()"/>Attendance Statistics
            &nbsp;&nbsp;
            <!--<input type="checkbox" name="showProgressStats_ui" value="1" <?php /*//echo $view->getPreference('showProgressStats')=='1'?'checked="checked"':''; */?> onclick="this.form.elements['<?php /*echo get_class($view).'_'; */?>showProgressStats'].value=(this.checked?'1':'0');this.form.submit()"/>Progress Statistics-->
        </form>
    </div>
    <div class="ActionIconBar">
        <button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
        <button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
        <!--		<button onclick="exportToExcel('view_ViewTrainingRecords')" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button> -->

        <button onclick="exportToExcel('view_ViewTrainingRecords')" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>


        <button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
        <button onclick="showHideBlock('div_addLesson');" title="Choose columns you want to see"><img src="/images/btn-columns.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
        <?php if(DB_NAME=="am_reed_demo" || DB_NAME=="am_reed"){ ?>
        <button onclick="window.location.href='do.php?_action=view_training_records&type=cert_template'" title="ACE Export"><img src="/images/export-3.png" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
        <?php } ?>
    </div>
</div>

<?php $_SESSION['bc']->render($link); ?><?php echo $view->getFilterCrumbs() ?>

<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div id="div_addLesson" align="center" style="margin-top:10px;margin-bottom:20px;<?php echo 'display:none'; ?>">
        <table border="0" style="margin:10px;background-color:silver; border:1px solid black;padding:3px;">
            <tr>
                <td><?php echo HTML::checkBoxGrid('columns', $view->getColumns($link), $view->getSelectedColumnsNumbers($link), 10); ?></td>
                <td>
                    <div style="margin:20px 0px 20px 10px">
                        <span class="button" onclick="changeColumns();"> Go </span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</form>


<div id="div_filters" style="display: none">

<form method="get" action="#" id="applySavedFilter">
    <input type="hidden" name="_action" value="view_training_records" />
    <?php echo $view->getSavedFiltersHTML(); ?>
</form>


<form method="get" name="filters" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="applyFilter">
<input type="hidden" name="page" value="1" />
<input type="hidden" name="_action" value="view_training_records" />
<input type="hidden" id="filter_name" name="filter_name" value="" />
<input type="hidden" id="filter_id" name="filter_id" value="" />

<div id="filterBox" class="clearfix">

<!--  1) Status -->
<fieldset>
    <legend>Status</legend>
    <div class="field float">
        <label>Record Status:</label> <?php echo $view->getFilterHTML('filter_record_status'); ?>
    </div>
    <div class="field float">
        <label>Gateway Learners:</label><?php echo $view->getFilterHTML('filter_gateway'); ?>
    </div>
    <div class="field float">
        <label>Restart:</label><?php echo $view->getFilterHTML('filter_restart'); ?>
    </div>
    <div class="field float">
        <label>Reason for leaving:</label> <?php echo $view->getFilterHTML('filter_reasons_for_leaving'); ?>
    </div>
    <div class="field float">
        <label>Reason for leaving not in:</label> <?php echo $view->getFilterHTML('filter_reasons_for_leaving_not_in'); ?>
    </div>
    <div class="field float">
        <label>Assessment Status:</label><?php echo $view->getFilterHTML('filter_assessment_status'); ?>
    </div>
    <div class="field float">
        <label>Progress:</label><?php echo $view->getFilterHTML('filter_progress'); ?>
    </div>
    <?php if(in_array(DB_NAME, ["am_demo"])){ ?>
    <div class="field float">
        <label>OTJ Progress:</label><?php echo $view->getFilterHTML('filter_otj_progress'); ?>
    </div>
    <?php } ?>
    <?php if(DB_NAME=="am_baltic_demo" || DB_NAME=="am_baltic") {?>
    <div class="field float">
        <label>Email Stage:</label><?php echo $view->getFilterHTML('filter_emails'); ?>
    </div>
    <div class="field float">
        <label>Assessment Plan Email Stage:</label><?php echo $view->getFilterHTML('filter_emails_assessment'); ?>
    </div>
    <?php }?>
    <div class="field float">
        <label>Outcome Type:</label><?php echo $view->getFilterHTML('filter_outcome'); ?>
    </div>
    <div class="field float">
        <label>Tag:</label><?php echo $view->getFilterHTML('filter_tag'); ?>
    </div>
    <?php if(DB_NAME == "am_ela") { ?>
        <div class="field float">
            <label>Sales Lead:</label><?php echo $view->getFilterHTML('filter_sales_lead'); ?>
        </div>
        <div class="field float">
            <label>Under consideration for BIL/ Withdrawal:</label><?php echo $view->getFilterHTML('filter_bil_withdrawal'); ?>
        </div>
        <div class="field float">
            <label>EPA Status:</label><?php echo $view->getFilterHTML('filter_epa'); ?>
        </div>
        <div class="field float">
            <label>Team Leader:</label><?php echo $view->getFilterHTML('filter_team_leader'); ?>
        </div>
     <?php } ?>
    <div class="field float">
        <label>Start Status:</label><?php echo $view->getFilterHTML('filter_restart_status'); ?>
    </div>
    <div class="field float">
        <label>BIL Status:</label><?php echo $view->getFilterHTML('filter_bil_status'); ?>
    </div>
    <div class="field float">
        <label>Tags Status:</label><?php echo $view->getFilterHTML('filter_with_tags'); ?>
    </div>
    <div class="field float">
        <label>ILR Status:</label><?php echo $view->getFilterHTML('filter_is_active_ilr'); ?>
    </div>
</fieldset>
<fieldset>
<div class="field float">
        <label>TR IDs:</label><?php echo $view->getFilterHTML('filter_tr_ids'); ?>
    </div>
</fieldset>

<!--  2) General -->
<fieldset>
    <legend>General</legend>
    <div class="field float">
        <label>Learner surname:</label><?php echo $view->getFilterHTML('surname'); ?>
    </div>
    <div class="field float">
        <label>Gender:</label><?php echo $view->getFilterHTML('filter_gender'); ?>
    </div>
    <div class="field float">
        <label>First Name:</label><?php echo $view->getFilterHTML('filter_firstname'); ?>
    </div>
    <div class="field float">
        <label>National Insurance:</label><?php echo $view->getFilterHTML('filter_nationalinsurance'); ?>
    </div>
    <div class="field float">
        <label>Last Modified:</label><?php echo $view->getFilterHTML('filter_modified'); ?>
    </div>
    <div class="field float">
        <label>Employer Lead Referral:</label><?php echo $view->getFilterHTML('filter_lead_referral'); ?>
    </div>
    <div class="field newrow">
        <label>Date of Birth:</label><?php echo $view->getFilterHTML('filter_dob'); ?>
    </div>
    <div class="field float">
        <label>Ethnicity:</label><?php echo $view->getFilterHTML('filter_ethnicity'); ?>
    </div>
    <div class="field float">
        <label>Learner Reference:</label><?php echo $view->getFilterHTML('l03'); ?>
    </div>
    <div class="field float">
        <label>ULN:</label><?php echo $view->getFilterHTML('filter_uln'); ?>
    </div>
    <div class="field float">
        <label>Job Role:</label><?php echo $view->getFilterHTML('filter_job_role'); ?>
    </div>
    <div class="field float">
        <label>Apprentice Type:</label><?php echo $view->getFilterHTML('filter_apprentice'); ?>
    </div>
    <?php if(SOURCE_LOCAL || DB_NAME == "am_ligauk") {?>
    <div class="field float">
        <label>At Risk:</label><?php echo $view->getFilterHTML('filter_at_risk'); ?>
    </div>
    <?php } ?>
    <?php if(DB_NAME=="am_pathway" || DB_NAME=="ams") {?>
    <div class="field newrow">
        <label>ACM:</label><?php echo $view->getFilterHTML('filter_acm'); ?>
    </div>
    <div class="field float">
        <label>IV Line Manager:</label><?php echo $view->getFilterHTML('filter_iv_line_manager'); ?>
    </div>
    <div class="field float">
        <label>Notification Status:</label><?php echo $view->getFilterHTML('filter_notification_status'); ?>
    </div>
    <?php } ?>
</fieldset>

<!-- 3) Qualification -->
<fieldset>
    <legend>Qualification</legend>
    <!-- 			<div class="field float">
					<label>Qualification:</label><?php //echo $view->getFilterHTML('filter_student_qualifications'); ?>
				</div> -->
    <div class="field float">
        <label>Training provider:</label><?php echo $view->getFilterHTML('filter_provider'); ?>
    </div>
    <div class="field float">
        <label>Not training provider:</label><?php echo $view->getFilterHTML('filter_not_provider'); ?>
    </div>
    <div class="field float">
        <label>Provider Location:</label><?php echo $view->getFilterHTML('filter_provider_locations'); ?>
    </div>
    <div class="field newrow">
        <label>Brand/Manufacturer:</label><?php echo $view->getFilterHTML('filter_manufacturer'); ?>
    </div>
    <div class="field newrow">
        <label>Employer/School:</label><?php echo $view->getFilterHTML('filter_employer'); ?>
    </div>
    <div class="field float">
        <label>Employer Sector:</label><?php echo $view->getFilterHTML('filter_sector'); ?>
    </div>
    <div class="field float">
        <label>Employer Location:</label><?php echo $view->getFilterHTML('filter_locations'); ?>
    </div>
    <div class="field float">
        <label>Not employer/school:</label><?php echo $view->getFilterHTML('filter_not_employer'); ?>
    </div>
    <div class="field newrow">
        <label>Course:</label><?php echo $view->getFilterHTML('filter_course'); ?>
    </div>
    <div class="field newrow">
        <label>Group:</label><?php echo $view->getFilterHTML('filter_group'); ?>
    </div>
    <div class="field newrow">
        <label>Group Text:</label><?php echo $view->getFilterHTML('group'); ?>
    </div>
    <div class="field float">
        <label>Assessor:</label><?php echo $view->getFilterHTML('filter_assessor'); ?>
    </div>
    <div class="field float">
        <label>Apprentice Coordinator:</label><?php echo $view->getFilterHTML('filter_acoordinator'); ?>
    </div>
    <div class="field float">
        <label>With Assessor:</label><?php echo $view->getFilterHTML('filter_with_assessor'); ?>
    </div>
    <div class="field float">
        <label>IQA:</label><?php echo $view->getFilterHTML('filter_verifier'); ?>
    </div>
    <div class="field float">
        <label>Group FS Tutor:</label><?php echo $view->getFilterHTML('filter_tutor'); ?>
    </div>
    <div class="field float">
        <label>FS Tutor:</label><?php echo $view->getFilterHTML('filter_ng_tutor'); ?>
    </div>
    <div class="field float">
        <label>Framework Code:</label><?php echo $view->getFilterHTML('filter_framework_code'); ?>
    </div>
    <div class="field float">
        <label>Framework Type:</label><?php echo $view->getFilterHTML('filter_framework_type'); ?>
    </div>
    <div class="field float">
        <label>Framework:</label><?php echo $view->getFilterHTML('filter_framework'); ?>
    </div>
    <div class="field float">
        <label>Programme Type:</label><?php echo $view->getFilterHTML('filter_programme_type'); ?>
    </div>
</fieldset>
<fieldset>
    <legend>Dates</legend>
    <div class="field">
        <label>Learners who started between</label><?php echo $view->getFilterHTML('start_date'); ?>
        &nbsp;and <?php echo $view->getFilterHTML('end_date'); ?>
    </div>
    <div class="field">
        <label>Learners who are planned to finish between </label><?php echo $view->getFilterHTML('target_start_date'); ?>
        &nbsp;and <?php echo $view->getFilterHTML('target_end_date'); ?>
    </div>
    <div class="field">
        <label>Learners who closed between </label><?php echo $view->getFilterHTML('closure_start_date'); ?>
        &nbsp;and <?php echo $view->getFilterHTML('closure_end_date'); ?>
    </div>
    <div class="field">
        <label>Created between </label><?php echo $view->getFilterHTML('created_start_date'); ?>
        &nbsp;and <?php echo $view->getFilterHTML('created_end_date'); ?>
    </div>
    <div class="field">
        <label>Modified between </label><?php echo $view->getFilterHTML('modified_start_date'); ?>
        &nbsp;and <?php echo $view->getFilterHTML('modified_end_date'); ?>
    </div>
    <?php if(DB_NAME=='ams' || DB_NAME=='am_lead' || DB_NAME=='am_lmpqswift') { ?>
    <div class="field">
        <label>Learners who marked close between </label><?php echo $view->getFilterHTML('marked_start_date'); ?>
        &nbsp;and <?php echo $view->getFilterHTML('marked_end_date'); ?>
    </div>
    <?php } ?>

</fieldset>

<!-- 4) Work Experience -->
<?php
if(SystemConfig::getEntityValue($link, "workplace"))
{

    ?>
<fieldset>
    <legend>Work Experience</legend>
    <div class="field float">
        <label>Work Experience:</label><?php echo $view->getFilterHTML('filter_work_experience'); ?>
    </div>
    <div class="field float">
        <label>Min. work experience:</label><?php echo $view->getFilterHTML('minwork'); ?>
    </div>
    <div class="field float">
        <label>Max. work experience:</label><?php echo $view->getFilterHTML('maxwork'); ?>
    </div>
    <div class="field float">
        <label>Work-based coordinators:</label><?php echo $view->getFilterHTML('filter_wbcoordinator'); ?>
    </div>
    <div class="field float">
        <label>Work Experience Inclusion:</label><?php echo $view->getFilterHTML('filter_work_experience_with'); ?>
    </div>
</fieldset>
    <?php } ?>

<!-- 5) Funding -->
<?php
if(SystemConfig::getEntityValue($link, "funding"))
{

    ?>
<fieldset>
    <legend>Funding</legend>
    <div class="field float">
        <label>Funding:</label><?php echo $view->getFilterHTML('filter_funding'); ?>
    </div>
    <div class="field float">
        <label>Contract</label><?php echo $view->getFilterHTML('filter_contract'); ?>
    </div>
    <div class="field float">
        <label>Contract Year</label><?php echo $view->getFilterHTML('filter_contract_year'); ?>
    </div>
    <div class="field float">
        <label>Contract Holder</label><?php echo $view->getFilterHTML('filter_contract_holder'); ?>
    </div>
    <!--				<div class="field float">
					<label>Deletion Flag:</label><?//php echo $view->getFilterHTML('filter_deletion_flag'); ?>
				</div>
-->
    <div class="field float">
        <label>Contract Location:</label><?php echo $view->getFilterHTML('filter_contract_location'); ?>
    </div>
    <div class="field float">
        <label>Project Code:</label><?php echo $view->getFilterHTML('filter_project_code'); ?>
    </div>
</fieldset>
    <?php } ?>

<?php if(DB_NAME=="am_lead" || DB_NAME=="ams" || DB_NAME=="am_lmpqswift") { ?>
<fieldset>
    <legend>User Defined Fields:</legend>
    <div class="field float">
        <label>Learner Defined Field 1:</label> <?php echo $view->getFilterHTML('filter_ld1'); ?>
    </div>
    <div class="field float">
        <label>Learner Defined Field 2:</label> <?php echo $view->getFilterHTML('filter_ld2'); ?>
    </div>
    <div class="field float">
        <label>Training Record Defined Field 1:</label> <?php echo $view->getFilterHTML('filter_td1'); ?>
    </div>
    <div class="field float">
        <label>Training Record Defined Field 2:</label> <?php echo $view->getFilterHTML('filter_td2'); ?>
    </div>
</fieldset>
    <?php } ?>

<!-- 6) Misc. -->
<fieldset >
    <legend>Miscellaneous</legend>
    <div class="field float">
        <label>Records per page:</label><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?>
    </div>
    <div class="field float">
        <label>Sort by:</label><?php echo $view->getFilterHTML('order_by'); ?>
    </div>
</fieldset>

<!-- Submit form controls -->
<div class="field newrow">
    <input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetFilters();" value="Reset" />&nbsp;<input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
</div>

</div>
</form>
</div>

<div align="center" style="margin-top:50px;">

    <?php
    echo $view->render($link, $view->getSelectedColumns($link));
    ?>
</div>

<pre><?php //echo $view->getSQLStatement()->__toString(); ?></pre>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>
</body>
</html>