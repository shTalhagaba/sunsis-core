<?php 
  // date drop down populations
  $day = array(array('','dd'),array(1,1),array(2,2),array(3,3),array(4,4),array(5,5),array(6,6),array(7,7),array(8,8),array(9,9),array(10,10),array(11,11),array(12,12),array(13,13),array(14,14),array(15,15),array(16,16),array(17,17),array(18,18),array(19,19),array(20,20),array(21,21),array(22,22),array(23,23),array(24,24),array(25,25),array(26,26),array(27,27),array(28,28),array(29,29),array(30,30),array(31,31)); 
  $month = array(array('','mon'),array(1,'Jan'),array(2,'Feb'),array(3,'Mar'),array(4,'Apr'),array(5,'May'),array(6,'Jun'),array(7,'Jul'),array(8,'Aug'),array(9,'Sep'),array(10,'Oct'),array(11,'Nov'),array(12,'Dec'));
  $year = array(array('','yyyy'));
  for($a = 2010; $a>=1930; $a--) {
  	$year[] = array($a,$a);	
  } 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sunesis - Vacancies</title>
<link rel="stylesheet" href="/css/core.css" type="text/css"/>
<?php if(DB_NAME!='am_pathway' && DB_NAME!='am_pathway_demo')
{
  echo '<link rel="stylesheet" href="/css/open.css" type="text/css"/>';
}
else
{
    echo '<link rel="stylesheet" href="/css/open_pathway.css" type="text/css"/>';
}
?>
<link rel="stylesheet" href="/print.css" media="print" type="text/css"/>
<?php
	// #176 - allow for client specific styling
	$css_filename = SystemConfig::getEntityValue($link, 'styling');
	if ( $css_filename != '' ) {
		echo '<link rel="stylesheet" href="/css/client/'.$css_filename.'" type="text/css"/>';	
	} 
?>
</head>


<?php
    if(DB_NAME=='am_pathway' || DB_NAME=='am_pathway_demo')
        echo '<body id="registration" >';
    else
        echo '<body onload="body_onload()" id="registration" >';

    $filename = DAO::getSingleValue($link, "Select value from configuration where entity='logo'");
	$filename = ($filename=='')?'perspective.png':$filename;
?>
<div id="recruitment">
    <div id="customerlogo">
      <!-- img src="/images/logos/<?php //echo $filename; ?>" alt="Sunesis - <?php echo DB_NAME; ?> vacancies" / -->
    </div>
    <div id="divWarnings"></div>
    <div id="divMessages">
    	<ul id="status">
<?php if ( !isset($_REQUEST['distance']) ) { ?>	
	 		<li id="status_1" class="active">Search Vacancies</li>
	 		<li id="status_2">Vacancy Matches</li>
<?php } else { ?>
			<li id="status_1" >Search Vacancies</li>
	 		<li id="status_2" class="active">Vacancy Matches</li>
<?php } ?>
		</ul>    
    </div>					
    <div id="main">
<?php if ( isset($_REQUEST['distance']) ) { 
		$candidate_id = '';
		if ( ( isset($_REQUEST['firstname'])&& $_REQUEST['firstname'] != '' ) || ( isset($_REQUEST['surname']) && $_REQUEST['surname'] != '' ) && isset($_REQUEST['dob_day']) && isset($_REQUEST['dob_month']) && isset($_REQUEST['dob_year']) ) {
			$user_birthday = $_REQUEST['dob_year']."-".sprintf("%02d", $_REQUEST['dob_month'])."-".$_REQUEST['dob_day'];
			$candidate_id = DAO::getSingleValue($link, "select id from candidate where LOWER(firstnames) = LOWER('".htmlspecialchars((string)$_REQUEST['firstname'])."') and LOWER(surname) = LOWER('".htmlspecialchars((string)$_REQUEST['surname'])."') and dob = '".$user_birthday."'");			
			if ( $candidate_id != '' ) {
				echo '<p>Welcome back <strong>'.$_REQUEST['firstname'].' '.$_REQUEST['surname'].'</strong>, we have found your details, please register for the vacancies you would like to apply for</p>';			
			}
			else {
				echo '<p>Sorry <strong>'.$_REQUEST['firstname'].' '.$_REQUEST['surname'].'</strong>, we haven\'t found your details, you can still apply for a vacancy here or go back to search and enter your details again</p>'; 
			}
		}
?>	
		<form action="do.php">
			<input type="hidden" name="candidate_id" value="<?php echo $candidate_id; ?>" />
			<div id="registration_2" class="formentry" >
	 			<?php 
	 				echo $view->render_candidate($link); 
	 			?>
	 			<div class="navigation" >
					<button type="button" class="previous button" id="proceed_1" onclick="javascript:window.location.href='/do.php?_action=view_candidate_vacancies&amp;sector=<?php echo $_REQUEST['keyword']; ?>'"  >&laquo; Back to Search</button>
				</div>
	 		</div>
	 	</form>
<?php } else { ?>
      <form name="recruitmentForm" action="/do.php?_action=view_candidate_vacancies" method="post">
	  <input type="hidden" name="screen_width" />
	  <input type="hidden" name="screen_height" />
	  <input type="hidden" name="color_depth" />
	  <input type="hidden" name="flash" />
	  <input type="hidden" name="destination" value="<?php echo (isset($_REQUEST['destination'])?htmlspecialchars((string)$_REQUEST['destination']):''); ?>" />
      <div id="wizard">
		<div id="items">	
			<div id="registration_1" class="formentry" >
				<h1>Search for a vacancy here...</h1>
				<p>
					Please use the form below to find suitable vacancies. Leave all the fields blank to view all our current vacancies within your chosen sector. 
				</p>
				<table>
					<tr>
						<td width="240" >Sector: </td>
						<?php 
							if ( isset($_REQUEST['sector']) && !(int)($_REQUEST['sector']) ) {
							 	echo '<td>';
								//echo $_REQUEST['sector'];
								//echo '<input type="hidden" name="keyword" id="keyword" value="'.$_REQUEST['sector'].'"/>';
								echo HTML::select('keyword', $type_dropdown, $_REQUEST['sector'], false, true, true);
							 	echo '</td>';
							}
							else {
								echo '<td>';
								echo '<input type="hidden" name="keyword" id="keyword" value=""/>';
								echo HTML::select('keyword', $type_dropdown, '', false, true, true);
								echo '</td>';
							}
						?>
					</tr>
					<tr>
						<td width="240" class="">Your postcode:</td>
						<td><input type="text" name="pc" id="pc" size="8" maxlength="100"/></td>
					</tr>
					<tr>
						<td width="240" class="">Travel distance:</td>
						<td><input type="text" name="distance" id="distance" size="8" maxlength="100"/> miles</td>
					</tr>
					
				</table>
				<p>
					If you have registered with us previously, <a href="#" onclick="displayprevious(); return false;" >click here</a> so we can find your details and make it easier for you to register for our other vacancies.
				</p>
				<div id="previous_registration" style="display:none;" >
					<table>
						<tr>
							<td width="240" >First Name:</td>
							<td><input type="text" name="firstname" id="firstname" size="30" maxlength="100" /> </td>
						</tr>
						<tr>
							<td width="240" >Surname:</td>
							<td><input type="text" name="surname" id="surname" size="30" maxlength="100" /> </td>
						</tr>
						<tr>
							<td width="240" >Date of Birth:</td>
							<td>
							<?php
								echo HTML::select('dob_day', $day, '', false, true); 
								echo HTML::select('dob_month', $month, '', false, true); 
								echo HTML::select('dob_year', $year, '', false, true); 
							?>		
							</td>
						</tr>
					</table>
				</div>
				<div class="navigation" >
					<button type="submit" class="next right button" id="submit" onclick="" >Search &raquo;</button>
				</div>
			</div>
		  </div>
	  	</div>	
		</form>		
<?php } ?>	 		
    </div>
</div>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
function save()
{
	var myForm = document.forms['recruitmentForm'];
	if( !validateForm(myForm) ) {	
		return false;
	}
	myForm.submit();
}

function body_onload() {
	
	if(window.self != window.top) {
		window.top.location.href = window.location.href;
	}
}


/**
 * Requires the Adobe Flash Detection script
 */
function getFlashVersion() {
	versionStr = GetSwfVer();
	
	if (versionStr == -1 )
	{
		versionStr = '';
	} 
	else if (versionStr != 0) 
	{
		if(isIE && isWin && !isOpera) 
		{
			// Given "WIN 2,0,0,11"
			tokens = versionStr.split(" ");
			versionStr = tokens[1].replace(/,/g,'.');
		}
	}
	
	return versionStr;
}
</script>
<script type="text/javascript">
$(document).ready(function() {

});

function displaydetail(tr) {
	var detail_tr = 'detail_'+tr;
	var table_row = document.getElementById(detail_tr);

	var current_status = table_row.style.display;
	
	$("tr[id^=detail]").each(function() {
		$(this).css('display','none');
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
		}
	}	
}

function displayprevious() {

	var current_status = $("#previous_registration").css('display');
	if ( current_status == 'none' ) {
		$("#previous_registration").css('display', 'block');
	}
	else {
		$("#previous_registration").css('display', 'none');
	}
}

</script>
<noscript>
	<div class="divMessages">
		You don't have javascript enabled - the registration process requires that it is turned on.
	</div>	
</noscript>
</body>
</html>
