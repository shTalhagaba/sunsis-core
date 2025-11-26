<?php /* @var $view RecViewVacancies */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Vacancies</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="/css/tooltipster.css" />
	<script type="text/javascript" src="js/jquery.tooltipster.min.js"></script>
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

		function checkFilters(form_name)
		{
			var myForm = document.forms[form_name];
			var RecViewVacancies_filter_postcodes = myForm.elements["RecViewVacancies_filter_postcodes"];
			var RecViewVacancies_filter_distance = myForm.elements["RecViewVacancies_filter_distance"];
			if(RecViewVacancies_filter_postcodes.value.trim() != '')
			{
				if(RecViewVacancies_filter_distance.value.trim() == '')
				{
					alert('Please enter postcode distance match range in miles');
					RecViewVacancies_filter_distance.focus();
					return false;
				}
				var client = ajaxRequest('do.php?_action=ajax_validate_postcode&postcode='+RecViewVacancies_filter_postcodes.value.trim());
				if(client.responseText != 'valid')
				{
					alert('Please enter a valid postcode');
					RecViewVacancies_filter_postcodes.focus();
					return false;
				}
			}
			myForm.submit();
		}

		$(function(){
			$('#RecViewVacancies_filter_distance').keypress(function(e){
				return numbersonly(this, e);
			});
		});

		function numbersonly(myfield, e, dec)
		{
			var key;
			var keychar;

			if (window.event)
				key = window.event.keyCode;
			else if (e)
				key = e.which;
			else
				return true;
			keychar = String.fromCharCode(key);

			// control keys
			if ((key==null) || (key==0) || (key==8) ||
				(key==9) || (key==13) || (key==27) )
				return true;

			// numbers
			else if ((("0123456789").indexOf(keychar) > -1))
				return true;

			// decimal point jump
			else if (dec && (keychar == "."))
			{
				myfield.form.elements[dec].focus();
				return false;
			}
			else
				return false;
		}
	</script>

	<script>
		$(document).ready(function() {
			$('.tooltip').tooltipster({
				contentAsHTML: true,
				animation: 'fade',
				delay: 200
			});
		});
	</script>
</head>

<body>
<div class="banner">
	<div class="Title">Vacancies</div>
	<div class="ButtonBar">
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.href='do.php?_action=rec_view_vacancies&subaction=exportToCSV'" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<?php echo $view->getFilterCrumbs() ?>

<div id="div_filters" style="display:none">

	<form method="get" action="#" id="applySavedFilter">
		<input type="hidden" name="_action" value="rec_view_vacancies" />
		<?php echo $view->getSavedFiltersHTML(); ?>
	</form>

	<form name="filters" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
		<input type="hidden" name="page" value="1" />
		<input type="hidden" name="_action" value="rec_view_vacancies" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />

		<div id="filterBox" class="clearfix">
			<fieldset>
				<legend>Vacancy Location Search:</legend>
				<div class="field float">
					<label>Postcode distance match:</label>
					<?php echo $view->getFilterHTML('filter_postcodes'); ?>
				</div>
				<div class="field float">
					<label>Vacancy distance match range:</label>
					<?php echo $view->getFilterHTML('filter_distance'); ?>
					(miles)
				</div>
			</fieldset>
			<fieldset>
				<legend>General:</legend>
				<div class="field float">
					<label>Reference:</label><?php echo $view->getFilterHTML('filter_vacancy_reference'); ?>
				</div>
				<div class="field float">
					<label>Title:</label><?php echo $view->getFilterHTML('filter_vacancy_title'); ?>
				</div>
				<div class="field float">
					<label>Status:</label><?php echo $view->getFilterHTML('filter_status'); ?>
				</div>
				<?php if($_SESSION['user']->type != User::TYPE_STORE_MANAGER) { ?>
				<div class="field float">
					<label>Archive:</label><?php echo $view->getFilterHTML('filter_archive'); ?>
				</div>
				<?php } ?>
				<div class="field float">
					<label>Employer Name:</label><?php echo $view->getFilterHTML('filter_employername'); ?>
				</div>
				<div class="field float">
					<label>Primary Sector:</label><?php echo $view->getFilterHTML('filter_sector'); ?>
				</div>
				<div class="field newrow"></div>
				<div class="field float">
					<label>Closing Date Between</label><?php echo $view->getFilterHTML('filter_from_closing_date'); ?>
					&nbsp;and <?php echo $view->getFilterHTML('filter_to_closing_date'); ?>
				</div>
			</fieldset>
			<fieldset>
				<input type="button" onclick="checkFilters('filters');" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms['filters']);" value="Reset" />
			</fieldset>
		</div>

	</form>
</div>

<div align="center" style="margin-top:50px;">
	<?php
		$view->render($link);
	?>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

<?php if(!$_SESSION['user']->isAdmin()){
	$logo = 'SUNlogo.jpg';
	$c_name = 'Perspective';
	if(DB_NAME == "am_superdrug")
	{
		$logo = 'superdrug.bmp';
		$c_name = 'Superdrug';
	}
	?>
<div id="footer">
	<span style="float: left; text-align: left;" ><?php echo date('D, d M Y H:i:s T'); ?></span>
	<span style="float: right; text-align: right;">Powered by Sunesis &nbsp;|&nbsp;&copy; <?php echo date('Y'); ?> Perspective Ltd</span>
	<span style="float: right"><img src="/images/logos/<?php echo $logo; ?>" alt="<?php echo $c_name; ?>" style="box-shadow:2px 3px 6px #ccc; border-radius: 6px;" />
</div>
<?php } ?>

</body>
</html>