<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Smart Assessor: Scheduled Tasks Log</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.17.custom.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script src="/jquery-ui/js/jquery-ui-1.8.17.custom.min.js" type="text/javascript"></script>

	<script type="text/javascript">
		/**
		 * jQuery datepicker default settings
		 */
		if($.datepicker)
		{
			$.datepicker.setDefaults({
				dateFormat: 'dd/mm/yy',
				yearRange: 'c-14:c+14',
				changeMonth: false,
				changeYear: true,
				constrainInput: true,
				buttonImage: "/images/calendar-icon.gif",
				buttonImageOnly: true,
				buttonText: "Show calendar",
				showOn: "both",
				showAnim: "fadeIn"
			});
		}

		/**
		 * jQuery Datepicker initialisation
		 */
		$(function(){
			if($.datepicker)
			{
				$('input.datepicker').datepicker().change(datepicker_change).blur(datepicker_blur);

				// Add validation code (for when the field is not blank)
				$('input.datepicker').each(function(){
						if(!this.validate){
							this.validate = function(){
								if(this.value != "" && window.stringToDate(this.value) == null){
									alert("Invalid date " + this.value + ". Format: dd/mm/yyyy");
									$(this).focus();
									return false;
								}
								return true;
							};
						}
					}
				);
			}
		});

		/**
		 * jQuery event handler
		 */
		function datepicker_change(e)
		{
			// Call old CLM event handlers
			if(window[this.name+"_onchange"])
			{
				window[this.name+"_onchange"](this);
			}
		}

		/**
		 * jQuery event handler
		 */
		function datepicker_blur(e)
		{
			if(this.value != "" && (window.stringToDate(this.value) == null) ){
				alert("Invalid date format or invalid calendar date. Format: dd/mm/yyyy");
				this.value = "";
				return;
			}

			// Call old CLM event handlers
			if(window[this.name+"_onblur"])
			{
				window[this.name+"_onblur"](this);
			}
		}

		function dateCmp(d1, d2)
		{
			d1 = stringToDate(d1);
			d2 = stringToDate(d2);

			if(d1 == null || d2 == null){
				return null;
			}

			if(d1 == d2)
			{
				return 0;
			}
			else
			{
				return d1 > d2 ? 1:-1;
			}
		}

		$(function(){
			$('select[name="scheduler_status"]').change(function(e){
				var client = ajaxRequest('do.php?_action=sa_crontab&subaction=updateCrontabSettings&enabled=' + $(this).val());
				if (client) {
					alert("Scheduler status updated");
				}
			});

			$('div#div_filter_crumbs').click(function(e){
				//$(this).toggle();
				$('div#div_filters').toggle();
			});
		});


		function viewTasks()
		{
			window.location.href="do.php?_action=sa_crontab";
		}

	</script>

	<style type="text/css">
		tr.DEBUG {
			color: gray;
		}

		tr.INFO {
			color: black;
		}

		tr.WARN {
			background-color: orange;
		}

		tr.ERR, tr.CRIT, tr.ALERT, tr.EMERG {
			color: white;
			background-color: red;
		}

	</style>

</head>

<body>
<div class="banner">
	<div class="Title">Smart Assessor: Scheduled Tasks Log</div>
	<div class="ButtonBar">
		<button onclick="viewTasks()">View schedule</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php echo $view->getFilterCrumbs() ?>
<div id="div_filters"  style="display:none; margin-top: 10px">
	<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		<input type="hidden" name="page" value="1" />
		<input type="hidden" name="_action" value="sa_crontab_log" />
		<table>
			<col width="150"/>
			<tr>
				<td class="fieldLabel">Period:</td>
				<td><?php echo $view->getFilterHTML('filter_date'); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel">Priority:</td>
				<td><?php echo $view->getFilterHTML('filter_priority'); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel">Records per page: </td>
				<td><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></td>
			</tr>
		</table>
		<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" />
	</form>
</div>

<div align="center" style="margin-top:50px;">
	<?php echo $view->getViewNavigator(); ?>
	<?php echo $this->_renderView($link, $view); ?>
	<?php echo $view->getViewNavigator(); ?>
</div>

</body>

</html>