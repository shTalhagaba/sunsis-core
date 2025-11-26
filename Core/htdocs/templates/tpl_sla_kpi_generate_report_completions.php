<?php /* @var $vo User */  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $page_title; ?></title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" href="/css/reports_css.css" type="text/css"/>

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

<script language="JavaScript">

function div_filter_crumbs_onclick(div)
{
	showHideBlock(div);
	showHideBlock('div_filters');
}


function save_filters()
{
//alert('function save_filters');
    $.ajax({
        type:"GET",
        data:"save_filters=save_filters&"+$('#report_criteria_form').serialize(),
        url:"do.php?_action=ajax_sla_kpi_reports",
        dataType : 'json',
        beforeSend:function(data)
        {
            //alert('before send');
            $('#sp_saving_filters').show();
        },
        success:function(response)
        {
            $('#sp_saving_filters').fadeOut();
        }
   });
}

</script>

<style title="text/css">
.resultset tr th{
    text-align: left;
}
</style>
</head>

<body>
<div class="banner">
	<div class="Title"><?php echo $page_title; ?></div>
	<div class="ButtonBar">

	</div>
	<div class="ActionIconBar">
        <input type="button" value="< Back to Dashboard" onclick="window.location.href='?_action=sla_kpi_rep_achievers&report_type=sla_kpi_rep_completions'" style="float:left;margin: 0 0 10px;">

		<button onclick="showHideBlock('filterBox');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<!--<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>-->
		<!--<button onclick="exportToExcel('view_ViewEVReport')" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="showHideBlock('div_addLesson');" title="Choose columns you want to see"><img src="/images/btn-columns.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>-->
	</div>
</div>





<?php
if($page_mode != 'normal')
{
?>
<script type="text/javascript">
window.onload = function abc(){
//alert('onload');
$('#applyFilter').submit();
}
</script>
<?php
}
?>


<div align="center" style="margin-top:50px;">


<div id="div_filters" align="center" style="width:1230px;">

	<!--<form method="get" action="#" id="applySavedFilter">
	<input type="hidden" name="_action" value="sla_kpi_generate_report" />
	<?php echo $view->getSavedFiltersHTML(); ?>
	</form>-->


	<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
		<input type="hidden" name="_action" value="<?php echo $report_type;?>" />
		<!--<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />-->

		<div id="filterBox" class="clearfix" style="text-align: left; display: none;">

            Assessor : <?php echo $view->getFilterHTML('filter_assessor'); ?>
				&nbsp;
            Contract : <?php echo $view->getFilterHTML('filter_contract'); ?>
            <br><br>
            Employer : <?php echo $view->getFilterHTML('filter_employer'); ?>
				&nbsp;
            Training provider : <?php echo $view->getFilterHTML('filter_training_provider'); ?>

            <br><br>
			Actual completion (closure) Date &nbsp;
				From :
                    <?php echo $view->getFilterHTML('start_date'); ?>
				&nbsp;
                To : <?php echo $view->getFilterHTML('end_date'); ?>

            <br><br>

            <input type="submit" value="Apply"/>&nbsp;
            <!--<input type="button" onclick="resetViewFilters(document.forms[1]);" value="Reset" />-->
            <input type="button" value="Reset" onclick="clearForm(this.form);"/>&nbsp;
            <!--<input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>-->
        </div>

        <div style="text-align: left; margin: 15px 0 0 0;">
   			Records per page: <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?>
            &nbsp;
		    <!--Sort: <?php echo $view->getFilterHTML("order_by"); ?>
            &nbsp;-->
			Drilldown by :  <?php echo $view->getFilterHTML("filter_drilldown"); ?>

            <input type="submit" value="Go">
            <br><br />

        </div>
	</form>
    </div>
</div>

<?php
echo $view->render($link, $view->getSelectedColumns($link));
?>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>
