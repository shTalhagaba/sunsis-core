<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Employers Pool</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<!--[if IE]>
	<link rel="stylesheet" href="/common-ie.css" type="text/css"/>
	<![endif]-->
	<link href="/assets/js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />

	<?php
	$selected_theme = SystemConfig::getEntityValue($link, 'module_theme');
	if ( $selected_theme ) {
		echo '<link rel="stylesheet" href="/css/'.$selected_theme.'/common.css" type="text/css"/>';
	}

	// establish all the messaging values
	// for use in feedback
	$feedback_message = '&#160;';
	$feedback_color = '#F6B035';

	if ( isset($_REQUEST['mesg']) && $_REQUEST['mesg'] != '' ) {
		$feedback_message = $_REQUEST['mesg'];
	}
	?>
	<script type="text/javascript">
		function sendBatchEmails()
		{
			var numberOfProspectRecords = "";
			numberOfProspectRecords = <?php echo $numberOfProspectRecords ?>;
			if(numberOfProspectRecords > 0)
			{
				if(numberOfProspectRecords > 10)
					alert('Batch emails can only be sent to maximum 10 Prospects, please filter out some records.');
				else
					window.location.href='do.php?_action=send_prospect_batch_email';
			}
			else
				alert("Filter some records ");
		}
	</script>
</head>

<body>
<div class="banner">
	<div class="Title">Prospects</div>
	<div class="ButtonBar">
		<?php if((DB_NAME == 'am_baltic' || DB_NAME == 'ams') && $_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER){ ?>
		<button onclick="window.location.href='do.php?_action=edit_prospect&edit_mode=2';">New</button>
		<button onclick="sendBatchEmails();"> Send Batch Email </button>
		<?php } ?>
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<?php if(isset($postcode) && $postcode != ''){?>
			<button onclick="window.location.href='do.php?_action=view_employers_pool&export=export&viewName=<?php echo $view->getViewName(); ?>&postcode=<?php echo $postcode; ?>&distance=<?php echo $distance; ?>';" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<?php }else{ ?>
			<button onclick="exportToExcel('view_ViewEmployersPool');" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<?php } ?>
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

<div id="div_filters" style="display:none">

	<form method="get" action="#" id="applySavedFilter">
		<input type="hidden" name="_action" value="view_employers_pool" />
		<?php echo $view->getSavedFiltersHTML(); ?>
	</form>

	<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
		<input type="hidden" name="_action" value="view_employers_pool" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />

		<div id="filterBox" class="clearfix">
			<fieldset>
				<legend>Prospect Location Search:</legend>
				<div class="field float">
					<label>Postcode distance match:</label>
					<?php echo $view->getFilterHTML('filter_postcodes'); ?>
				</div>
				<div class="field float">
					<label>Postcode distance match range:</label>
					<?php echo $view->getFilterHTML('filter_distance'); ?>
					(miles)
				</div>
			</fieldset>
			<fieldset>
				<legend>Options</legend>
				<div class="field float">
					<label>Records per page:</label><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?>
				</div>
				<div class="field float">
					<label>Company:</label><?php echo $view->getFilterHTML('filter_company'); ?>
				</div>
				<div class="field float">
					<label>Locality:</label><?php echo $view->getFilterHTML('filter_locality'); ?>
				</div>
				<div class="field float">
					<label>Town:</label><?php echo $view->getFilterHTML('filter_town'); ?>
				</div>
				<div class="field float">
					<label>County:</label><?php echo $view->getFilterHTML('filter_county'); ?>
				</div>
				<div class="field float">
					<label>Region:</label><?php echo $view->getFilterHTML('filter_region'); ?>
				</div>
				<div class="field float">
					<label>Status:</label><?php echo $view->getFilterHTML('filter_status'); ?>
				</div>
				<div class="field float">
					<label>Source:</label><?php echo $view->getFilterHTML('filter_source'); ?>
				</div>
			</fieldset>
			<fieldset>
				<legend>Company CRM Contacts</legend>
				<div class="field float">
					<label>Company Contact Name:</label><?php echo $view->getFilterHTML('filter_by_contact_name'); ?>
				</div>
				<div class="field float">
					<label>Company Contact Telephone:</label><?php echo $view->getFilterHTML('filter_by_contact_tel'); ?>
				</div>
				<div class="field float">
					<label>Company Contact Mobile:</label><?php echo $view->getFilterHTML('filter_by_contact_mob'); ?>
				</div>
				<div class="field float">
					<label>Company Contact Email:</label><?php echo $view->getFilterHTML('filter_by_contact_email'); ?>
				</div>
			</fieldset>
			<fieldset>
				<input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[2]);" value="Reset" /> <input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
			</fieldset>
		</div>
	</form>
</div>

<!-- <span class="button" onclick="window.location.replace('do.php?_action=get_employer&emp_group_id=<?php //echo rawurlencode($id); ?>');"> Import Employer</span> -->
<!-- <span class="button" onclick="window.location.replace('do.php?_action=get_employer_dettach&emp_group_id=<?php //echo rawurlencode($id); ?>');"> Dettach Employer</span> -->
<div align="left" style="margin-top:10px;">

	<?php
	echo $view->render($link, $view->getSelectedColumns($link)); ?>
</div>

<script type="text/javascript" src="/assets/js/greybox/AJS.js"></script>
<script type="text/javascript" src="/assets/js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/assets/js/greybox/gb_scripts.js"></script>
<script language="javascript" src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<script language="javascript" type="text/javascript">
	// if the feedback element has content show it
	$(document).ready(function() {
		if ( '&nbsp;' != $('#feedback').html() ) {
			$('#feedback').css('background-color', '<?php echo $feedback_color; ?>');
			$('#feedback').slideDown('2000'); //.delay('1500').slideUp('2000');
		}

		$('#feedback').click(function(){
			$('#feedback').slideUp('2000');
		});
	});

	function div_filter_crumbs_onclick(div) {
		showHideBlock(div);
		showHideBlock('div_filters');
	}

	/*
			function changeColumns() {
				var myForm = document.forms[0];

				data = 'view=' + <?php echo "'" . $view->getViewName() . "'"; ?>;
			var request = ajaxRequest('do.php?_action=ajax_delete_columns',data);
	
			for(a = 0; a<myForm.length; a++) {	
				data = 'view=' + <?php echo "'" . $view->getViewName() . "'"; ?>
				+ '&colum=' + myForm[a].parentNode.title
				+ '&visible=' + ((myForm[a].checked==true)?1:0);
		
				if( myForm[a].checked==false ) {	
					var request = ajaxRequest('do.php?_action=ajax_save_columns',data);
				}
			}	

			var myForm = document.forms[1];
			myForm.submit();
		}
		*/

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

	function displaydetail(tr) {
		var detail_tr = 'detail_'+tr;
		var user_tr = 'user_'+tr;
		var empid = tr.split("_");
		var table_row = document.getElementById(detail_tr);
		var current_status = table_row.style.display;

		$("tr[id^=detail]").each(function() {
			$(this).css('display','none');
		});
		$("tr[id^=user]").each(function() {
			//$(this).css('background-color','#fff');
		});
		// IE sillyness - check for table-row conformance IE7 +
		if( $.browser.msie && $.browser.version < 7 ) {
			if ( current_status != 'block' ) {
				table_row.style.display = 'block';
				//user_row.style.backgroundColor = '#DCE5CD';
			}
		}
		else {
			if ( current_status != 'table-row' ) {
				table_row.style.display = 'table-row';
				var request = ajaxRequest('do.php?_action=ajax_display_employer_screening','empid='+empid[1]+'&tabid='+empid[0]);
				if ( request.responseText.match('/^Successfully/') ) {
					alert('There has been a problem finding employer pool contact information');
				}
				else {
					$("tr[id="+detail_tr+"] td:first").html(request.responseText);
				}
			}
		}
	}

	function div_filter_crumbs_onclick(div)	{
		showHideBlock(div);
		showHideBlock('div_filters');
	}

	function convertEmployer(dpn) {

		var data = 'dpn=' + dpn;
		var dname = "ext_emp_"+dpn;

		if ( $("input[name='"+dname+"']").length > 0 && $("input[name='"+dname+"']:checked").length == 0 ) {
			var continue_convert = confirm("There are organisations that may match this employer.  Do you still want to set it up as a new employer?");
			if ( continue_convert ) {
				var selectedValue = $("input[name='"+dname+"']:checked").val();
				if ( selectedValue ) {
					data += '&emp='+selectedValue;
				}
				var request = ajaxRequest('do.php?_action=ajax_convert_employer',data);
				window.location.replace(<?php echo "'" . $_SESSION['bc']->getCurrent() . "'"; ?>);
			}
		}
		else {
			var selectedValue = $("input[name='"+dname+"']:checked").val();
			if ( selectedValue ) {
				data += '&emp='+selectedValue;
			}
			var request = ajaxRequest('do.php?_action=ajax_convert_employer',data);
			window.location.replace(<?php echo "'" . $_SESSION['bc']->getCurrent() . "'"; ?>);
		}
	}
</script>
<noscript>
	<?php include_once('templates/tpl_noscript.php'); ?>
</noscript>

</body>
</html>
