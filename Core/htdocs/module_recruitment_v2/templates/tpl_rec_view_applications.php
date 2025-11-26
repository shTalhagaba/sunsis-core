<?php /* @var $view VoltView */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Vacancy Applications</title>
<link rel="stylesheet" href="/common.css?n=<?php echo time(); ?>" type="text/css"/>
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<script language="JavaScript" src="/geometry.js"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script language="JavaScript" src="/calendarPopup/CalendarPopup.js"></script>

<!-- Initialise calendar popup -->
<script language="JavaScript">
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
</script>

<!--[if IE]>
<link rel="stylesheet" href="/common-ie.css" type="text/css"/>
<![endif]-->
<script type="text/javascript">
	var GB_ROOT_DIR = "/assets/js/greybox/";
</script>
<script type="text/javascript" src="/assets/js/greybox/AJS.js"></script>
<script type="text/javascript" src="/assets/js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/assets/js/greybox/gb_scripts.js"></script>
<link href="/assets/js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />

	<style type="text/css">
		td.greenl
		{
			background-image:url('/images/trafficlight-green.jpg');
			background-color:white;
			background-repeat: no-repeat;
			background-position: center;
			opacity: 0.2;
			filter: alpha(opacity=20);
		}

		td.redl
		{
			background-image:url('/images/trafficlight-red.jpg');
			background-color:white;
			background-repeat: no-repeat;
			background-position: center;
			opacity: 0.2;
			filter: alpha(opacity=20);
		}

		td.yellowl
		{
			background-image:url('/images/trafficlight-yellow.jpg');
			background-color:white;
			background-repeat: no-repeat;
			background-position: center;
			opacity: 0.2;
			filter: alpha(opacity=20);
		}

		td.greend
		{
			background-image:url('/images/trafficlight-green.jpg');
			background-color:white;
			background-repeat: no-repeat;
			background-position: center;
			opacity: 1;
			filter: alpha(opacity=100);
		}

		td.redd
		{
			background-image:url('/images/trafficlight-red.jpg');
			background-color:white;
			background-repeat: no-repeat;
			background-position: center;
			opacity: 1;
			filter: alpha(opacity=100);
		}

		td.yellowd
		{
			background-image:url('/images/trafficlight-yellow.jpg');
			background-color:white;
			background-repeat: no-repeat;
			background-position: center;
			opacity: 1;
			filter: alpha(opacity=100);
		}

	</style>

<script type="text/javascript">
	function entry_onclick(radio)
	{
		var td = radio.parentNode;
		var tr = td.parentNode;

		var inputs = tr.getElementsByTagName("td");

		for(var i = 0; i < 3; i++)
		{
			if(inputs[i].tagName == 'TD')
			{
				if(inputs[i].className=='redd')
					inputs[i].className='redl';

				if(inputs[i].className=='greend')
					inputs[i].className='greenl';

				if(inputs[i].className=='yellowd')
					inputs[i].className='yellowl';
			}
		}

		if(td.className=='redl')
			td.className='redd';

		if(td.className=='greenl')
			td.className='greend';

		if(td.className=='yellowl')
			td.className='yellowd';
	}

	function screenApplication(application_id, candidate_id, vacancy_id)
	{
		if(!$('[name="screen_'+application_id+'"]:checked').val())
			return;
		var postData = '_action=rec_save_application'
			+ '&id=' + application_id
			+ '&candidate_id=' + candidate_id
			+ '&vacancy_id=' + vacancy_id
			+ '&application_comments=' + 'Edited from summary screen'
			+ '&application_screening=' + $('[name="screen_'+application_id+'"]:checked').val()
			+ '&application_status=1'
		;
		var request = ajaxRequest('do.php?_action=rec_save_application', postData);
		if(request && request.status == 200)
		{
			alert("Record Saved.");
			window.location.reload();
		}
		else
		{
			alert(request.responseText);
		}
//		console.log(postData);
	}

	function processApplication(application_id, candidate_id, vacancy_id)
	{
		var interview_outcome = "";
		var application_status = $('#ddl_'+application_id).val();
		if(application_status == '')
			return;
		if(application_status == 3 || application_status == 4)
		{
			if(application_status == 3)
				interview_outcome = 1;
			else if(application_status == 4)
				interview_outcome = 0;
			application_status = 2;
		}
		var postData = '_action=rec_save_application'
			+ '&id=' + application_id
			+ '&candidate_id=' + candidate_id
			+ '&vacancy_id=' + vacancy_id
			+ '&application_comments=' + 'Edited from summary screen'
			+ '&application_status=' + application_status
			+ '&interview_outcome=' + interview_outcome
		;
		var request = ajaxRequest('do.php?_action=rec_save_application', postData);
		if(request && request.status == 200)
		{
			alert("Record Saved.");
			window.location.reload();
		}
		else
		{
			alert(request.responseText);
		}
//		console.log(postData);
	}

	function convertApplication(application_id, candidate_id, vacancy_id)
	{
		var interview_outcome = "";
		var application_status = $('#ddl_'+application_id).val();
		if(application_status == 5)
		{
			application_status = 3;
		}
		var postData = '_action=rec_save_application'
				+ '&id=' + application_id
				+ '&candidate_id=' + candidate_id
				+ '&vacancy_id=' + vacancy_id
				+ '&application_comments=' + 'Edited from summary screen'
				+ '&application_status=' + application_status
			;
		var request = ajaxRequest('do.php?_action=rec_save_application', postData);
		if(request && request.status == 200)
		{
			alert("Record Saved.");
			window.location.reload();
		}
		else
		{
			alert(request.responseText);
		}
	}
</script>
</head>

<body>
<div class="banner">
	<div class="Title">Applications</div>
	<div class="ButtonBar">
		<!-- <button onclick="window.location.href='do.php?_action=edit_course';">New</button> -->
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<?php echo $view->getFilterCrumbs() ?>

<div id="div_filters" style="display:none">

	<form method="get" action="#" id="applySavedFilter">
		<input type="hidden" name="_action" value="rec_view_applications" />
		<?php echo $view->getSavedFiltersHTML(); ?>
	</form>

	<form name="filters" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="applyFilter">
		<input type="hidden" name="_action" value="rec_view_applications" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />

		<div id="filterBox" class="clearfix">
			<fieldset>
				<legend>Vacancy</legend>
				<div class="field float">
					<label>Status:</label><?php echo $view->getFilterHTML('filter_status'); ?>
				</div>
				<div class="field float">
					<label>Code:</label><?php echo $view->getFilterHTML('filter_code'); ?>
				</div>
				<div class="field float">
					<label>Title:</label><?php echo $view->getFilterHTML('filter_job_title'); ?>
				</div>
				<div class="field float">
					<label>Primary Sector:</label><?php echo $view->getFilterHTML('filter_primary_sector'); ?>
				</div>
				<div class="field newrow">
					<label>Live Date Between</label><?php echo $view->getFilterHTML('filter_from_live_date'); ?>
					&nbsp;and <?php echo $view->getFilterHTML('filter_to_live_date'); ?>
				</div>
				<div class="field newrow">
					<label>Expiry Date Between</label><?php echo $view->getFilterHTML('filter_from_expiry_date'); ?>
					&nbsp;and <?php echo $view->getFilterHTML('filter_to_expiry_date'); ?>
				</div>
			</fieldset>
			<fieldset>
				<legend>Candidate</legend>
				<div class="field float">
					<label>Firstname(s):</label><?php echo $view->getFilterHTML('filter_firstnames'); ?>
				</div>
				<div class="field float">
					<label>Surname:</label><?php echo $view->getFilterHTML('filter_surname'); ?>
				</div>
			</fieldset>
			<fieldset>
				<legend>Application</legend>
				<div class="field float">
					<label>Screening Status:</label><?php echo $view->getFilterHTML('filter_screened'); ?>
				</div>
				<div class="field float">
					<label>Screening Score:</label><?php echo $view->getFilterHTML('filter_screening'); ?>
				</div>
				<div class="field float">
					<label>Status:</label><?php echo $view->getFilterHTML('filter_application_status'); ?>
				</div>
			</fieldset>
			<fieldset>
				<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms['filters']);" value="Reset" /> <input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
			</fieldset>
		</div>
	</form>
</div>

<p><br></p>
<?php
$this->renderView($link, $view);
?>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>