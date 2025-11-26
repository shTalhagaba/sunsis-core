<?php /* @var $vo User */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <title>Report - Double Dimension Graph</title>
    <link rel="stylesheet" href="/common.css" type="text/css"/>
    <script src="/js/jquery.min.js" type="text/javascript"></script>
    <script src="/common.js" type="text/javascript"></script>

    <script src="/js/table-export.js" type="text/javascript"></script>

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

        function validateFilters() {

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


        function div_filter_crumbs_onclick(div) {
            showHideBlock(div);
            showHideBlock('div_filters');
        }


        function resetFilters() {
            resetViewFilters(document.forms[0]);
            refreshQualificationList();
        }

        function filter_qualification_type_onchange(qualType) {
            refreshQualificationList();
        }

        function filter_qualification_level_onchange(qualLevel) {
            refreshQualificationList();
        }

        function refreshQualificationList() {

            var f = document.forms['filters'];
            var globe = document.getElementById('globe1');

            f.reset();

            var qualLevel = f.elements['filter_qualification_level'];
            var qualType = f.elements['filter_qualification_type'];
//	var qual = f.elements['filter_qualification_title'];

            // Disable controls
//	qual.disabled = true;

            // Populate course dropdown with a list of courses for the provider
//	globe.style.display = 'inline';
//	var url = 'do.php?_action=ajax_load_qualification_dropdown&qual_level=' + qualLevel.value + '&qual_type=' + qualType.value;
//	ajaxPopulateSelect(qual, url);

            // reactivate controls
//	qual.disabled = false;
//	globe.style.display = 'none';


            return false;
        }

        function graph() {
            <?php if($anyresults > 0) : ?>
            document.getElementById("stackedimage").src = "do.php?_action=generate_stacked_graph&data=" + <?php echo "'" . rawurlencode($xml) . "'";?> +"&titles=" + <?php echo "'" . rawurlencode($titles) . "'"; ?> +"&first=" + <?php echo "'" . rawurlencode($first) . "'"; ?> +"&second=" + <?php echo "'" . rawurlencode($second) . "'"; ?>;
            document.getElementById("multiimage").src = "do.php?_action=generate_multi_bar_graph&data=" + <?php echo "'" . rawurlencode($xml) . "'";?> +"&titles=" + <?php echo "'" . rawurlencode($titles) . "'"; ?> +"&first=" + <?php echo "'" . rawurlencode($first) . "'"; ?> +"&second=" + <?php echo "'" . rawurlencode($second) . "'"; ?>;
            document.getElementById("googleimage").src = "http://chart.apis.google.com/chart?cht=bvg&chd=t:30,30,20,10,10|60,60,60,50,20&chs=350x175&chco=339966,ff9933&chbh=30,0&chl=website|blog|newsletter|email|networking";
            <?php else : ?>
            alert("No results were found!");
            <?php endif; ?>
        }


    </script>

</head>

<body onload="graph();">
<div class="banner">
    <div class="Title">Double Dimension Report</div>
    <div class="ButtonBar">

    </div>
    <div class="ActionIconBar">
        <button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom"/></button>
        <button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom"/></button>
        <!-- <button onclick="window.location.href='do.php?_action=export_current_view_to_excel&key=primaryView'" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>-->
        <button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom"/></button>
    </div>
</div>

<?php echo $view->getFilterCrumbs() ?>


<div id="div_filters" style="display:none">

    <form method="get" action="#" id="applySavedFilter">
        <?php echo $view->getSavedFiltersHTML(); ?>
    </form>

    <form method="get" name='filters' action="<?php echo $_SERVER['PHP_SELF']; ?>" id="applyFilter">
        <input type="hidden" name="page" value="1"/>
        <input type="hidden" name="_action" value="view_double_graph"/>
        <input type="hidden" id="filter_name" name="filter_name" value=""/>
        <input type="hidden" id="filter_id" name="filter_id" value=""/>

        <div id="filterBox" class="clearfix">
            <fieldset>
                <legend>Status</legend>
                <div class="field float">
                    <label>Record Status:</label><?php echo $view->getFilterHTML('filter_record_status'); ?>
                </div>
                <div class="field float">
                    <label>Progress:</label><?php echo $view->getFilterHTML('filter_progress'); ?>
                </div>
                <div class="field float">
                    <label>Project/Area Code:</label><?php echo $view->getFilterHTML('filter_area_code'); ?>
                </div>
            </fieldset>
            <fieldset>
                <legend>General</legend>
                <div class="field float">
                    <label>Learner surname:</label><?php echo $view->getFilterHTML('surname'); ?>
                </div>
                <div class="field float">
                    <label>Gender:</label><?php echo $view->getFilterHTML('filter_gender'); ?>
                </div>
                <div class="field float">
                    <label>Modified:</label><?php echo $view->getFilterHTML('filter_modified'); ?>
                </div>
                <div class="field float">
                    <label>Employer:</label><?php echo $view->getFilterHTML('filter_employer'); ?>
                </div>
                <div class="field float">
                    <label>Brand/Manufacturer:</label><?php echo $view->getFilterHTML('filter_manufacturer'); ?>
                </div>
                <div class="field float">
                    <label>Programme:</label><?php echo $view->getFilterHTML('filter_programme'); ?>
                </div>
                <div class="field float">
                    <label>Assessor:</label><?php echo $view->getFilterHTML('filter_assessor'); ?>
                </div>
                <div class="field float">
                    <label>Apprentice Coordinator:</label><?php echo $view->getFilterHTML('filter_acoordinator'); ?>
                </div>
            </fieldset>
            <fieldset>
                <legend>Qualification</legend>
                <div class="field float">
                    <label>Training provider:</label><?php echo $view->getFilterHTML('filter_provider'); ?>
                </div>
                <div class="field float">
                    <label>Course:</label><?php echo $view->getFilterHTML('filter_course'); ?>
                </div>
                <div class="field float">
                    <label>Framework:</label><?php echo $view->getFilterHTML('filter_framework'); ?>
                </div>
                <div class="field float">
                    <label>Group:</label><?php echo $view->getFilterHTML('filter_group'); ?>
                </div>
            </fieldset>
            <fieldset>
                <legend>Contract</legend>
                <div class="field float">
                    <label>Type:</label><?php echo $view->getFilterHTML('filter_contract_type'); ?>
                </div>
                <div class="field float">
                    <label>Name:</label><?php echo $view->getFilterHTML('filter_contract'); ?>
                </div>
                <div class="field float">
                    <label>Year:</label><?php echo $view->getFilterHTML('filter_contract_year'); ?>
                </div>
            </fieldset>
            <fieldset>
                <legend>Dates</legend>
                <div class="field">
                    <label>Start Date:</label><?php echo $view->getFilterHTML('start_date'); ?> to <?php echo $view->getFilterHTML('end_date'); ?>
                </div>
                <div class="field">
                    <label>Projected end date:</label><?php echo $view->getFilterHTML('target_start_date'); ?> to <?php echo $view->getFilterHTML('target_end_date'); ?>
                </div>
                <div class="field">
                    <label>Closure Date:</label><?php echo $view->getFilterHTML('closure_start_date'); ?> to <?php echo $view->getFilterHTML('closure_end_date'); ?>
                </div>
                <div class="field">
                    <label>Work experience period:</label><?php echo $view->getFilterHTML('work_experience_start_date'); ?> to <?php echo $view->getFilterHTML('work_experience_end_date'); ?>
                </div>
            </fieldset>
            <fieldset>
                <legend>Options</legend>
                <div class="field">
                    <label>Sort By:</label><?php echo $view->getFilterHTML('order_by'); ?>
                </div>
            </fieldset>
            <fieldset>
                <input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset"/> <input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
            </fieldset>
        </div>

        <?php
        /*if(SystemConfig::getEntityValue($link, "workplace"))
        {
            echo '<tr>';
            echo '<td>Min Work Exp: </td>';
            echo '<td>' . $view->getFilterHTML('minwork') . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<td>Max Work Exp: </td>';
            echo '<td>' . $view->getFilterHTML('maxwork') . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<td>Work Experience Coordinator: </td>';
            echo '<td>' . $view->getFilterHTML('filter_wbcoordinator') . '</td>';
            echo '</tr>';

        }*/ ?>

</div>

<div style="font-size:11pt; margin-top: 2em">

    <table>
        <tr>
            <td style="font-size: 15px">X Axis</td>
            <td> <?php echo HTML::select('second', $second_dropdown, $second, false, true); ?></td>

            <td style="font-size: 15px">Y Axis</td>
            <td> <?php echo HTML::select('first', $first_dropdown, $first, false, true); ?></td>

            <td><input type="submit" value="Go"/>&nbsp;<input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/></td>

            <td>
                <input type="checkbox" name="showAttendanceStats_ui" value="1" checked="checked" onclick="showHideBlock('stacked')"/> <span style="font-size: 15px"> Stacked </span>
                &nbsp;&nbsp;
                <input type="checkbox" name="showProgressStats_ui" value="1" onclick="showHideBlock('multi')"/><span style="font-size: 15px"> Separate </span>
                &nbsp;&nbsp;
                <input type="checkbox" checked name="showProgressStats" value="1" onclick="showHideBlock('datatable')"/><span style="font-size: 15px"> Data Table </span>
            </td>


        </tr>
    </table>

    </form>
</div>


<table>
    <tr>
        <td>
            <div style="margin-top: 3em" id="stacked">
                <img id="stackedimage"></img>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div id="multi" style="display: none">
                <img id="multiimage"></img>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div id="google" style="display: none">
                <img id="googleimage"></img>
            </div>
        </td>
    </tr>
</table>

<div id="datatable" align="center" style="margin-top:50px; margin-left: 83px;">
    <?php
    echo '<div align="left"><table class="resultset" id="double-report" border="0" cellspacing="0" cellpadding="6">';
    echo '<thead><tr><th>&nbsp;</th><th>' . $table_titles . '</th><th>Total</th></tr></thead>';
    echo '<tbody>';

    // display data table
    $grand_total = 0;
    foreach ($first_labels as $first_label) {
        echo '<tr><td style="background-color: #EEEEEE; font-weight: bold">' . $first_label . '</td>';
        $rowtotal = 0;
        foreach ($second_labels as $second_label) {
            $first_label = str_replace("'", "\'", $first_label ?: '');
            $first_label = str_replace("\\\'", "\'", $first_label ?: '');
            $second_label = str_replace("'", "\'", $second_label ?: '');
            $sql = "select count(*) from multi_bar_graph where $first = '$first_label' and $second = '$second_label' group by concat($first, $second)";
            $value = DAO::getSingleValue($link, $sql);
            $value = ($value == "") ? 0 : $value;
            echo '<td align=center>' . $value . '</td>';
            $rowtotal += $value;
        }
        $grand_total += $rowtotal;
        echo '<td style="font-weight: bold" align=center>' . $rowtotal . '</td></tr>';
    }

    echo '<td style="background-color: #EEEEEE; font-weight: bold">' . HTML::cell("Total") . "</td>";

    foreach ($second_labels as $second_label) {
        $second_label = str_replace("'", "\'", $second_label ?: '');
        $sql = "select count(*) from multi_bar_graph where $second = '$second_label' group by $second";
        $value = DAO::getSingleValue($link, $sql);
        echo '<td style="font-weight: bold" align="center">' . $value . "</td>";
    }

    echo '<td align="center" style="font-weight: bold">' . $grand_total . "</td>";
    echo '</tbody></table>';

    echo '</div>';

    ?>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>