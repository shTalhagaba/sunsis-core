<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Candidates</title>

	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
	<script type="text/javascript" src="/calendarPopup/CalendarPopup.js"></script>
	<style type="text/css">
			/******* Background Style ******************/
		div.block
		{
			text-align: left;
			border-width: 1px;
			border-style: solid;
			border-color: #E3E3E3 #BFBFBF #BFBFBF #E3E3E3;
			padding: 8px!important;
			margin-bottom: 1.5em;
			word-wrap: break-word;
			width: 95%!important;
			/* To enable gradients in IE < 9 */
			zoom: 1;
			-moz-border-radius: 7px;
			-webkit-border-radius: 7px;
			border-radius: 7px;
			-moz-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			-webkit-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			/* http://www.colorzilla.com/gradient-editor/ */
			background: rgb(255,255,255); /* Old browsers */
			/* IE9 SVG, needs conditional override of 'filter' to 'none' */
			background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmZmZmZiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmNmY2ZjYiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
			background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(246,246,246,1) 100%); /* FF3.6+ */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,1)), color-stop(100%,rgba(246,246,246,1))); /* Chrome,Safari4+ */
			background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* Opera 11.10+ */
			background: -ms-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* IE10+ */
			background: linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* W3C */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f6f6f6',GradientType=0 ); /* IE6-8 */
		}
	</style>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
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
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />

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
	
	
	<script language="javascript" type="text/javascript">

	function validateForm(form, excludeFields)
	{
		form = document.forms['screen_'+form];
		var e = form.elements;

		// If exclude fields is not specified, create an empty array
		if(excludeFields == null)
		{
			excludeFields = new Array();
		}
		excludeFields.sort();

		for(var i = 0; i < e.length; i++)
		{
			// Check if the field is to be excluded
			if(isInSortedArray(e[i].name, excludeFields))
				continue; // skip this field

			// Trim text fields before continuing
			// (useful anyway and stops a 'space' registering as valid content)
			if(e[i].tagName == 'TEXTAREA' || (e[i].tagName == 'INPUT' && (e[i].type == 'text' || e[i].type == 'password')) ){
				e[i].value = jQuery.trim(e[i].value);
			}

			// Multi-node fields
			if(e[i].tagName == 'INPUT' && (e[i].type == 'checkbox' || e[i].type == 'radio'))
			{
				if(e[i].className.indexOf('compulsory') > -1)
				{
					var nodeList = e[e[i].name];
					var checked = false;
					var disabled = false;
					if(nodeList.length)
					{
						// Many nodes
						for(var j = 0; j < nodeList.length; j++)
						{
							disabled = (disabled || nodeList[j].disabled);
							checked = (checked || nodeList[j].checked);
						}
					}
					else
					{
						// Single element
						disabled = e[i].disabled;
						checked = e[i].checked;
					}

					if(checked == false && disabled == false)
					{
						alert("Please fill in all compulsory fields");
						e[i].focus();
						return false;
					}

				}

				// Don't check elements of this name again
				excludeFields.push(e[i].name);
				excludeFields.sort();
			}

			// All other fields
			var $ele = $(e[i]);
			if( (e[i].tagName == 'SELECT' || e[i].tagName == 'TEXTAREA' || (e[i].tagName == 'INPUT' && (e[i].type == 'text' || e[i].type == 'password' || e[i].type == 'file')))
				&& $ele.hasClass('compulsory')
				&& e[i].disabled != true
				&& $ele.is(":visible")
				&& (e[i].value == '' || ($ele.hasClass('DateBox') && $ele.val() == 'dd/mm/yyyy')))
			{
				alert("Please fill in all compulsory fields");
				e[i].focus();
				return false;
			}


			if(e[i].validate && e[i].validate() == false)
			{
				return false;
			}
		}

		return true;
	}

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

	/* relmes: function for the dynamic addition of qualifications
	 *   expects a table id to which the row is to be appended.
	 */
	function newqual(tableId) {
		if ( "" == tableId ) {
			return false;
		}
		var $tr = $("#"+tableId+' tr:last').clone(true);
		// clear any values already in the last row
		$tr.find('input').val('');
		$tr.find('select').each(function () {
			$(this).find('option:first').attr('selected','selected');
		});
		// insert into the table.
		$tr.insertAfter($("#"+tableId+' tr:last'));
		return true;
	}

		function displaydetail(tr) {
			var detail_tr = 'detail_'+tr;
			var user_tr = 'user_'+tr;
			var candid = tr.split("_");
			var table_row = document.getElementById(detail_tr);
			var user_row = document.getElementById(user_tr);

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
					var request = ajaxRequest('do.php?_action=ajax_display_candidate_screening','candid='+candid[1]+'&tabid='+candid[0]);
					if ( request.responseText.match('/^Successfully/') ) {
						alert('There has been a problem finding candidate screening');
					}
					else {
						$("tr[id="+detail_tr+"] td:first").html(request.responseText);
					}
				}
			}
			else {
				if ( current_status != 'table-row' ) {
		    		table_row.style.display = 'table-row';
		    		//user_row.style.backgroundColor = '#DCE5CD';
		    		var request = ajaxRequest('do.php?_action=ajax_display_candidate_screening','candid='+candid[1]+'&tabid='+candid[0]);
					if ( request.responseText.match('/^Successfully/') ) {
						alert('There has been a problem finding candidate screening');
					}
					else {
						$("tr[id="+detail_tr+"] td:first").html(request.responseText);
					}
				}
			}			
		}

		function setscreening(score, formid) {
			formname = document.getElementById('screen_'+formid);

			if(validateForm(formid,null))
			{
				formname.screening_score.value = score;

				formname.submit();
			}
		}

		function open_new_window(URL)
		{
			NewWindow = window.open(URL,"vacancy_screen","toolbar=no,menubar=0,status=0,copyhistory=0,location=no,scrollbars=yes,resizable=0,location=0,Width=920,Height=730") ;
			NewWindow.location.href = URL;
		}

	function sendBatchEmails()
	{
		var numberOfCandidateRecords = "";
		numberOfCandidateRecords = <?php echo $numberOfCandidateRecords ?>;
		if(numberOfCandidateRecords > 0)
			window.location.href='do.php?_action=send_candidate_batch_email';
		else
			alert("Filter some records ");
	}

<?php 
		// if we have candidate notes, then output the functionality to allow
		// saving of the note.
		echo CandidateNotes::render_js(); 
?>
		
	</script>

<!-- Temproary overrides for #candidate definitions in common.css -->	
<style type="text/css">
span.filterCrumb{
	float: none !important;
	padding: 1px !important;
	font-size: 8pt !important;
}
</style>	

</head>

<body id="candidates">
	<div class="banner">
		<div class="Title">Candidates</div>
		<div class="ButtonBar">
			<?php if($_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER){ ?><button onclick="sendBatchEmails();"> Send Batch Email </button><?php } ?>
		</div>
		<div class="ActionIconBar">
			<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" /></button>
			<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" /></button>
			<button onclick="exportToExcel('view_ViewCandidates')" title="Export to .CSV file"><img src="/images/btn-excel.gif" /></button>
			<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" /></button>
			<button onclick="showHideBlock('div_addLesson');" title="Choose columns you want to see"><img src="/images/btn-columns.gif" /></button>
		</div>
	</div>
	<?php $_SESSION['bc']->render($link); ?>
	<?php echo $view->getFilterCrumbs() ?>
	<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<div id="div_addLesson" align="center" style="margin-top:10px;margin-bottom:20px;<?php echo 'display:none'; ?>">
			<table border="0" style="margin:10px;background-color:silver; border:1px solid black;padding:3px;">
				<tr>
					<td><?php echo HTML::checkBoxGrid('columns', $view->getColumns($link), $view->getSelectedColumnsNumbers($link), 20); ?></td>
					<td>
						<div style="margin:20px 0px 20px 10px">
							<span class="button" onclick="changeColumns();"> Go </span>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</form>
	<div id="infoblock">
		<div id="feedback"><?php echo $feedback_message; ?></div>
	</div>


	<div id="div_filters" >
		<form method="get" action="#" id="applySavedFilter">
			<input type="hidden" name="_action" value="view_candidates" />
			<input type="hidden" name="id" value="" />
			<?php echo $view->getSavedFiltersHTML(); ?>
		</form>
		<form method="get" name="filters" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
			<input type="hidden" name="page" value="1" />
			<input type="hidden" name="_action" value="view_candidates" />
			<input type="hidden" id="filter_name" name="filter_name" value="" />
			<input type="hidden" id="filter_id" name="filter_id" value="" />
			<input type="hidden" name="id" value="" />

			<div id="filterBox" class="clearfix">
				<fieldset>
					<legend>Candidate Name Search:</legend>
					<div class="field float">
						<label>First name contains:</label> <?php echo $view->getFilterHTML('filter_firstnames'); ?>
					</div>
					<div class="field float">
						<label>Surname contains:</label> <?php echo $view->getFilterHTML('filter_surname'); ?>
					</div>
				</fieldset>
				<fieldset>
					<legend>Candidate Location Search:</legend>
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
					<legend>Filters:</legend>
					<div class="field float">
						<label>Gender:</label> <?php echo $view->getFilterHTML('filter_gender'); ?>
					</div>
					<div class="field float">
						<label>Age:</label> <?php echo $view->getFilterHTML('filter_age'); ?>
					</div>
					<div class="field float">
						<label>Candidate Status:</label> <?php echo $view->getFilterHTML('filter_appliedfor'); ?>
					</div>
					<div class="field float">
						<label>Candidate Application Status:</label> <?php echo $view->getFilterHTML('filter_applicant_status'); ?>
					</div>
					<!--<div class="field float">
						<label>Candidate Screening Status:</label> <?php /*echo $view->getFilterHTML('filter_screened'); */?>
					</div>-->
					<div class="field float">
						<label>Applications Screening Score:</label> <?php echo $view->getFilterHTML('filter_screening'); ?>
					</div>
					<div class="field float">
						<label>Candidate Age:</label> <?php echo $view->getFilterHTML('filter_age_custom'); ?>
					</div>

					<?php
					if ( !isset($_SESSION['user']->department) ) {
						echo '<div class="field float">';
						echo '	<label>In Vacancy Region:</label>';
						echo $view->getFilterHTML('filter_region');
						echo '</div>';
					}
					?>
					<?php if(DB_NAME=="am_baltic_demo" || DB_NAME=="am_demo" || DB_NAME=="am_baltic") { ?>
					<div class="field float">
						<label>Candidates With Interest In:</label> <?php echo $view->getFilterHTML('filter_cand_interests'); ?>
					</div>
					<div class="field float">
						<label>Candidate Source:</label> <?php echo $view->getFilterHTML('filter_cand_source'); ?>
					</div>
					<div class="field float">
						<label>Candidate Jobatar Completed:</label> <?php echo $view->getFilterHTML('filter_jobatar'); ?>
					</div>
					<div class="field newrow">
						<label>Candidate Record Created By:</label><?php echo $view->getFilterHTML('filter_applied_directly'); ?>
					</div>
					<div class="field newrow">
						<label>Candidate Ethnicity:</label><?php echo $view->getFilterHTML('filter_ethnicity'); ?>
					</div>
					<?php } ?>
				</fieldset>
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
					<legend>Dates</legend>
					<div class="field">
						<label>Candidates who registered between</label>
						<?php echo $view->getFilterHTML('start_date'); ?>
						&nbsp;and
						<?php echo $view->getFilterHTML('end_date'); ?>
					</div>
				</fieldset>
				<fieldset>
					<input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[2]);" value="Reset" />&nbsp;<input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
				</fieldset>
			</div>
		</form>
	</div>
	<div id="maincontent">

		<div align="center" style="margin-top:50px;">
		<?php 
  			echo $view->render($link, $view->getSelectedColumns($link));
  		?>
  		</div>
	</div>
	<?php 
		// include the footer options
		include_once('layout/tpl_footer.php'); 
	?>
	
	
	<!-- Popup calendar -->
	<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>
	<noscript>
		<?php include_once('templates/tpl_noscript.php'); ?>
	</noscript>
</body>
</html>
