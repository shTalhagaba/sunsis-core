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

	function delete_candidate(candidate_id)
	{
		if(confirm("Candidate record will be permanently deleted, this action cannot be undone. Are you sure you want to continue?"))
			window.location.replace('do.php?id=' + candidate_id + '&_action=delete_candidate');
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
			formname.screening_score.value = score;
			formname.submit();
		}

		function open_new_window(URL)
		{
			NewWindow = window.open(URL,"vacancy_screen","toolbar=no,menubar=0,status=0,copyhistory=0,location=no,scrollbars=yes,resizable=0,location=0,Width=920,Height=730") ;
			NewWindow.location.href = URL;
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
	
		</div>
		<div class="ActionIconBar">
			<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" /></button>
			<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" /></button>
			<button onclick="exportToExcel('view_ViewCandidates')" title="Export to .CSV file"><img src="/images/btn-excel.gif" /></button>
			<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" /></button>
			<button onclick="showHideBlock('div_addLesson');" title="Choose columns you want to see"><img src="/images/btn-columns.gif" /></button>
		</div>
	</div>

	<div id="infoblock">
		<?php $_SESSION['bc']->render($link); ?>
		<div id="feedback"><?php echo $feedback_message; ?></div>
	</div>
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
	<div id="maincontent">


		<div id="div_filters" >
			<form method="get" action="#" id="applySavedFilter">
				<input type="hidden" name="_action" value="view_candidates" />
				<input type="hidden" name="id" value="" />
				<?php echo $view->getSavedFiltersHTML(); ?>
			</form>
			<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
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
						<div class="field float">
							<label>Candidate Screening Status:</label> <?php echo $view->getFilterHTML('filter_screened'); ?>
						</div>
						<div class="field float">
							<label>Candidate Screening Score:</label> <?php echo $view->getFilterHTML('filter_screening'); ?>
						</div>
					<?php 
						if ( !isset($_SESSION['user']->department) ) { 
							echo '<div class="field float">';
							echo '	<label>In Vacancy Region:</label>';
							echo $view->getFilterHTML('filter_region');
							echo '</div>';		
						}
					?>
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
