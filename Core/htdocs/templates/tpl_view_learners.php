<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Learners</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/js/json.js" type="text/javascript"></script>
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

</head>

<body>
<div class="banner">
	<div class="Title"><?php echo $a; ?></div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER && $_SESSION['user']->type != User::TYPE_ORGANISATION_VIEWER && $_SESSION['user']->type != 20) { ?>
		<button onclick="window.location.href='do.php?_action=<?php echo DB_NAME == "am_ela" ? "add_learner" : "edit_user"; ?>&people=<?php echo "Learner"; ?>&people_type=<?php echo 5; ?>';">Add New</button>
		<?php } ?>
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="exportToExcel('view_ViewLearners')" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="showHideBlock('div_addLesson');" title="Choose columns you want to see"><img src="/images/btn-columns.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<?php echo $view->getFilterCrumbs() ?>

<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<div id="div_addLesson" align="center" style="margin-top:10px;margin-bottom:20px;<?php echo 'display:none'; ?>">
		<table border="0" style="margin:10px;background-color:silver; border:1px solid black;padding:3px;">
			<tr>
				<td><?php echo HTML::checkBoxGrid('columns', $view->getColumns($link), $view->getSelectedColumnsNumbers($link), 9); ?></td>
				<td>
					<div style="margin:20px 0px 20px 10px">
						<span class="button" onclick="changeColumns();"> Go </span>
					</div>
				</td>
			</tr>
		</table>
	</div>
</form>


<div id="div_filters" style="display:none">
	<form method="get" action="#" id="applySavedFilter">
		<input type="hidden" name="_action" value="view_learners" />
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<?php echo $view->getSavedFiltersHTML(); ?>
	</form>


	<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
		<input type="hidden" name="_action" value="view_learners" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />
		<input type="hidden" name="id" value="<?php echo $id; ?>" />

		<div id="filterBox" class="clearfix">
			<fieldset>
				<legend>General</legend>
				<?php if(DB_NAME == "am_duplex"){?>
							<div class="field float"><label>Training Date:</label><?php echo $view->getFilterHTML('filter_crm_training_date'); ?></div>
                            <?php } ?>
				<div class="field float">
					<label>Learners:</label><?php echo $view->getFilterHTML('filter_learners'); ?>
				</div>
				<div class="field float">
					<label>Employer:</label><?php echo $view->getFilterHTML('organisation'); ?>
				</div>
				<div class="field float">
					<label>Location:</label><?php echo $view->getFilterHTML('location'); ?>
				</div>
				<div class="field float">
					<label>Ethnicity:</label><?php echo $view->getFilterHTML('ethnicity'); ?>
				</div>
				<div class="field float">
					<label>Manufacturer:</label><?php echo $view->getFilterHTML('filter_manufacturer'); ?>
				</div>
				<div class="field float">
					<label>Contract:</label><?php echo $view->getFilterHTML('filter_contract'); ?>
				</div>
				<div class="field float">
					<label>Programme Type:</label><?php echo $view->getFilterHTML('filter_programme_type'); ?>
				</div>
				<div class="field float">
					<label>Training provider:</label><?php echo $view->getFilterHTML('filter_provider'); ?>
				</div>
			</fieldset>
			<fieldset>
				<legend>Learner</legend>
				<div class="field float">
					<label>Surname:</label><?php echo $view->getFilterHTML('filter_surname2'); ?>
				</div>
				<div class="field float">
					<label>First Name:</label><?php echo $view->getFilterHTML('filter_firstname'); ?>
				</div>
				<div class="field float">
					<label>Gender:</label><?php echo $view->getFilterHTML('filter_gender'); ?>
				</div>
				<div class="field float">
					<label>National Insurance:</label><?php echo $view->getFilterHTML('filter_nationalinsurance'); ?>
				</div>
				<div class="field float">
					<label>Date of Birth:</label><?php echo $view->getFilterHTML('filter_dob'); ?>
				</div>
				<div class="field float">
					<label>L03:</label><?php echo $view->getFilterHTML('filter_l03'); ?>
				</div>
				<div class="field float">
					<label>ULN:</label><?php echo $view->getFilterHTML('filter_uln'); ?>
				</div>
				<?php if(DB_NAME == "am_reed" || DB_NAME == "am_reed_demo") {?>
				<div class="field float">
					<label>Job Goal 1:</label><?php echo $view->getFilterHTML('filter_job_goal_1'); ?>
				</div>
				<div class="field float">
					<label>Job Goal 2:</label><?php echo $view->getFilterHTML('filter_job_goal_2'); ?>
				</div>
				<div class="field float">
					<label>Job Goal 3:</label><?php echo $view->getFilterHTML('filter_job_goal_3'); ?>
				</div>
				<?php } ?>
				<?php if(SystemConfig::getEntityValue($link, "module_onboarding")) { ?>
				<div class="field float">
					<label>Onboarding Form Status:</label><?php echo $view->getFilterHTML('filter_ob_status'); ?>
				</div>
				<?php } ?>
			</fieldset>
			<fieldset>
				<legend>
					<div class="field">
						<?php if(DB_NAME=="am_baltic"){?><label>Induction Date Between</label><?php }else{?><label>Initial Appointment Date Between</label><?php } ?>
						<?php echo $view->getFilterHTML('filter_from_initial_appointment_date'); ?>&nbsp;and <?php echo $view->getFilterHTML('filter_to_initial_appointment_date'); ?>
					</div>
					
				</legend>
			</fieldset>

			<fieldset>
				<legend>Health & Safety:</legend>
				<div class="field float">
					<label>Timeliness:</label> <?php echo $view->getFilterHTML('by_health_safety_timeliness'); ?>
				</div>
				<div class="field float">
					<label>Compliance:</label> <?php echo $view->getFilterHTML('by_health_safety_compliance'); ?>
				</div>
				<div class="field float">
					<label>Paperwork:</label> <?php echo $view->getFilterHTML('by_paperwork'); ?>
				</div>
			</fieldset>
			<?php if(DB_NAME=="am_lead" || DB_NAME=="ams") { ?>
			<fieldset>
				<legend>User Defined Fields:</legend>
				<div class="field float">
					<label>Learner Defined Field 1:</label> <?php echo $view->getFilterHTML('filter_ld1'); ?>
				</div>
				<div class="field float">
					<label>Learner Defined Field 2:</label> <?php echo $view->getFilterHTML('filter_ld2'); ?>
				</div>
			</fieldset>
			<?php } ?>
			<fieldset>
				<legend>Options:</legend>

				<div class="field float">
					<label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?>
				</div>
				<div class="field float">
					<label>Sort By:</label> <?php echo $view->getFilterHTML('order_by'); ?>
				</div>
			</fieldset>

			<fieldset>
				<input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[2]);" value="Reset" />&nbsp;<input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
			</fieldset>
		</div>
	</form>
</div>

<div align="center" style="margin-top:50px;">

	<?php
	echo $view->render($link, $view->getSelectedColumns($link)); ?>
</div>
<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>
