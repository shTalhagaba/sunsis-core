<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Vacancies Home</title>
<link rel="stylesheet" href="common.css" type="text/css"/>
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
</head>

<body id="candidates">
	<div class="banner">
		<div class="Title">Vacancies Home</div>
		<div class="ButtonBar">
	
		</div>
		<div class="ActionIconBar">
			<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
			<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
			<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		</div>
	</div>
	
	

	<div id="infoblock">
		<?php 
			$_SESSION['bc']->render($link); 
			// $dates_presented = $this->display_candidate_actions($link);
		?>
		<div id="feedback"><?php echo $feedback_message; ?></div>
	</div>
	
	
	
	
	<div id="maincontent" style="" >
		<div id="col1" class="column">
		</div>
		
		<div id="col2" class="column">
			<h3>Candidate Information</h3>
			<p></p>
			<?php echo VacanciesHome::homepageDashboard($link); ?>
			<h3>Find a Candidate</h3>
			<p>Search for candidates, using all or part of their first name or surname</p>
			<div>			
				<form action="do.php" name="find_candidate">
				<input type="hidden" name="_action" value="view_candidates" />
				<input type="hidden" name="_reset" value="1" />
				<label for="ViewCandidates_filter_firstnames">Firstname:</label><br/>
				<input id="ViewCandidates_filter_firstnames" type="text" value="" name="ViewCandidates_filter_firstnames">
				<br/>
				<label for="ViewCandidates_filter_surname">Surname:</label><br/>
				<input id="ViewCandidates_filter_surname" type="text" value="" name="ViewCandidates_filter_surname">
				<input type="submit" name="search_candidates" value="go &raquo;" />
				</form>
			</div>
			<h3>Find a Candidate</h3>
			<p>Search for candidate within 10 miles of the postcode:</p>
			<div>
				<form action="do.php" name="find_candidate">
					<input type="hidden" name="_action" value="view_candidates" />
					<input type="hidden" name="_reset" value="1" />
					<label for="ViewCandidates_filter_postcodes">Postcode:</label>&nbsp;
					<input id="ViewCandidates_filter_postcodes" type="text" value="" name="ViewCandidates_filter_postcodes">
					<input id="ViewCandidates_filter_distance" type="hidden" value="10" name="ViewCandidates_filter_distance">
					<input type="submit" name="search_candidates" value="go &raquo;" />
				</form>			
			</div>
			<h3>Find Employers</h3>
			<div>	
				<p>Find employers with current vacancies</p>
				<form action="do.php" name="find_vacancies" >
					<input type="hidden" name="_action" value="view_vacancies" />
					<input type="hidden" name="_reset" value="1" />
					<input id="ViewVacancies_filter_employername" type="text" value="" name="ViewVacancies_filter_employername">
					<input type="submit" name="search_candidates" value="go &raquo;" />
				</form>
			</div>
		</div>
		<div id="col3" class="column">
			<h3>Screening Statistics</h3>
			<p>Number of individual vacancy application screenings.  A candidate can be screened and scored separately for each vacancy they apply for.</p>
			<div id="stat-container"></div>
		</div>
	</div>
	<div class="clearfix"></div>
	<?php 
		// include the footer options
		// include_once('layout/tpl_footer.php'); 
	?>
	
	
	
	
	<!-- Popup calendar -->
	<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>
	
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script language="javascript" src="/js/highcharts.js" type="text/javascript"></script>
	
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
	
	
	<script language="javascript" type="text/javascript">
	// if the feedback element has content show it
		$(document).ready(function() {
			if ( '&nbsp;' != $('#feedback').html() ) {
				$('#feedback').css('background-color', '<?php echo $feedback_color; ?>');
				$('#feedback').slideDown('2000'); //.delay('1500').slideUp('2000');
			}

			display_actions(-1);

			$('#feedback').click(function(){
				$('#feedback').slideUp('2000');
			});

			$('.actionlist').live("click", function(){
				display_actions($(this).attr('href').match(/cand_start_date=(.*)/)[1]);	
				return false;
			});	
		});

		function display_actions(cand_start) {
			var request = ajaxRequest('do.php?_action=ajax_vacancies_crm','cand_start_date='+cand_start);
			$("div[id=col1]").html(request.responseText);			
		}

		<?php echo $this->display_screening_stats($link); 		?>
				
	</script>
	<noscript>
		<?php include_once('templates/tpl_noscript.php'); ?>
	</noscript>
</body>
</html>
