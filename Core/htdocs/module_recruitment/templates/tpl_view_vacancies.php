<?php /* @var $view View */ ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Vacancies</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>

<?php 
	$selected_theme = SystemConfig::getEntityValue($link, 'module_theme');
	if ( $selected_theme ) {
		echo '<link rel="stylesheet" href="/css/'.$selected_theme.'/common.css" type="text/css"/>';	
	}	
?>

<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
</head>
<body id="candidates" >
<div class="banner">
	<div class="Title">Vacancies</div>
	<div class="ButtonBar">

	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php 
	// establish all the messaging values
	// for use in feedback 
	$feedback_message = '&#160;';
	$feedback_color = '#DCE5CD';
	$current_tab = '#tab-2';
	
	if ( isset($vacancy) ) {
		if ( $vacancy->feedback['message'] != NULL ) { 
			$feedback_message = $vacancy->feedback['message'];
		}		
		if ( $vacancy->feedback['background-color'] != NULL ) { 
			$feedback_color = $vacancy->feedback['background-color'];
		}	
		if ( $vacancy->feedback['location'] != NULL ) { 
			$current_tab = $vacancy->feedback['location'];
		}
		
		if ( isset($_REQUEST['search_apps']) ) {
			$current_tab = '#tab-1';	
		}
	}
	
	
?>
<div id="infoblock">
	<?php $_SESSION['bc']->render($link); ?>
	<div id="feedback"><?php echo $feedback_message; ?></div>
</div>

<div id="maincontent">
	<?php 
		if ( isset($vacancy) && $vacancy->feedback['message'] != NULL ) {
			$vacancy->feedback['message'] = NULL; 
		}
		// display the actual vacancy listings
		if ( !isset($vacancy) ) { 
	?>
	<div id="div_filters" style="display:none">
		<form method="get" action="#" id="applySavedFilter">
			<input type="hidden" name="_action" value="view_vacancies" />
			<input type="hidden" name="id" value="" />
			<?php echo $view->getSavedFiltersHTML(); ?>
		</form>

		<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
			<input type="hidden" name="_action" value="view_vacancies" />
			<input type="hidden" id="filter_name" name="filter_name" value="" />
			<input type="hidden" id="filter_id" name="filter_id" value="" />
			<input type="hidden" name="id" value="" />	
			<div id="filterBox" class="clearfix">
				<fieldset>
					<legend>Filters:</legend>
					<div class="field float">
						<label>Vacancy Status:</label> <?php echo $view->getFilterHTML('filter_isactive'); ?>
					</div>
					<div class="field float">
					<label>Employer name contains:</label> <?php echo $view->getFilterHTML('filter_employername'); ?>
					</div>
					<div class="field float">
					<label>Vacancy Sector:</label> <?php echo $view->getFilterHTML('filter_sectortype'); ?>
					</div>
				</fieldset>		
				<fieldset>
					<input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[2]);" value="Reset" />&nbsp;<input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
				</fieldset>
			</div>
		</form>
	</div>
	<div id="vacancies">
  		<h3>
  		<?php 
  			$status = 'All ';
  			$status_code = $view->getFilterValue('filter_isactive');
  			if ( $status_code == 2 ) {
  				$status = 'Active ';
  			}
  			elseif( $status_code == 3 ) {
  				$status = 'Inactive ';
  			}
  			echo $status;
		?>
		 Vacancies
  		</h3>
  		<?php echo $view->render($link); ?>
  	</div>
  	<?php 
		}
		 
		include_once('templates/layout/tpl_footer.php'); 
	?>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$('#tabs > div').hide();
	// ---------------------------
	// default load position
	$('#tabs <?php echo $current_tab; ?>').show();
	$('#tabs ul li a[href=<?php echo $current_tab; ?>]').parent().addClass('active');
	
	$('#tabs ul li a').click(function(){
		$('#tabs ul li').removeClass('active');
		$(this).parent().addClass('active');
		var currentTab = $(this).attr('href');
		$('#tabs > div').hide();
		$(currentTab).show();
		return false;
	});
	// --------------------------
	
	<?php 
		if ( isset($_REQUEST['display']) ) {
			echo '$("[id^=detail_]").filter("[id$='.$_REQUEST['display'].']").each( function() {';
			echo "\n  candidate_location = $(this).prop('id').split('_');\n";
			echo "	$('#tabs > div').hide();\n";
			echo "  $('#tabs ul li').removeClass('active');\n";
			echo "  $('#tabs #tab-'+candidate_location[1]).show();\n";
			echo "  $('#tabs ul li a[href=#tab-'+candidate_location[1]+']').parent().addClass('active');\n";
			echo "  displaydetail(candidate_location[1]+'_'+candidate_location[2]);\n";
			echo '});';
		}	
	?>

	// if the feedback element has content show it
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
    		<?php if ( isset($vacancy) ) { ?>
    		var request = ajaxRequest('do.php?_action=ajax_display_candidate_screening','candid='+candid[1]+'&tabid='+candid[0]+'&vacid='+<?php echo $vacancy->id; ?>);
			if ( request.responseText.match('/^Successfully/') ) {
				alert('There has been a problem finding candidate screening');
			}
			else {
				$("tr[id="+detail_tr+"] td:first").html(request.responseText);
			}
			<?php } ?>
		}
	}
}

function displayform(tr) {
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
		}
	}
}

function open_new_window(URL) {
	NewWindow = window.open(URL,"vacancy_screen","toolbar=no,menubar=0,status=0,copyhistory=0,location=no,scrollbars=yes,resizable=0,location=0,Width=920,Height=730") ;
	NewWindow.location.href = URL;
}

function setscreening(score, formid) {			
	formname = document.getElementById('screen_'+formid);
	formname.screening_score.value = score;
	formname.submit();
}


<?php 
	// if we have candidate notes, then output the functionality to allow
	// saving of the note.
	echo CandidateNotes::render_js(); 
?>

</script>
<noscript>
	<?php include_once('templates/tpl_noscript.php'); ?>
</noscript>
</body>
</html>
