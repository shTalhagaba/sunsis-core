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
	<title>Companies</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/common.js" type="text/javascript"></script>

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.css">-->
	<link rel="stylesheet" href="mobile/jquery.mobile-1.4.2.min.css">
	<script src="mobile/jquery-1.10.2.min.js"></script>
	<script src="mobile/jquery.mobile-1.4.2.min.js"></script>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script language="JavaScript">

		function displayprevious() {

			var current_status = $("#previous_registration").css('display');
			if ( current_status == 'none' ) {
				$("#previous_registration").css('display', 'block');
			}
			else {
				$("#previous_registration").css('display', 'none');
			}
		}

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
				}
			}
			else {
				if ( current_status != 'table-row' ) {
					table_row.style.display = 'table-row';
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

</head>

<body>

<div data-role="page" id="pageone">
	<div data-role="header">
		<h1>Baltic Apprenticeship Opportunities</h1>
	</div>

	<div data-role="main" class="ui-content">
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
			if(DB_NAME=="am_demo" || DB_NAME=="am_baltic_demo" || DB_NAME=="am_baltic")
				echo $view->render_candidate_for_mobiles($link);
			else
				echo $view->render_candidate($link);
			?>
			<div class="navigation" >
				<button type="button" class="previous button" id="proceed_1" onclick="javascript:window.location.href='/do.php?_action=mobile&amp;sector=<?php echo $_REQUEST['keyword']; ?>'"  >&laquo; Back to Search</button>
			</div>
		</div>
	</form>
	<?php } else { ?>
		<form name="recruitmentForm" action="/do.php?_action=mobile" method="post">
			<input type="hidden" name="destination" value="<?php echo (isset($_REQUEST['destination'])?htmlspecialchars((string)$_REQUEST['destination']):''); ?>" />
		<p>
			Please use the form below to find suitable vacancies. Leave all the fields blank to view all our current vacancies within your chosen sector.
		</p>
		<table>
			<tr>
				<td width="240" >Sector: </td>
				<?php
				if ( isset($_REQUEST['sector']) && !(int)($_REQUEST['sector']) ) {
					echo '<td>';
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
			<tr>
				<td><button type="submit" class="next right button" id="submit" onclick="" >Search &raquo;</button></td>
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
		<!--<a href="#pagetwo" data-transition="slide">Slide to Page Two</a>-->
		</form>
		<?php } ?>
	</div>

	<div data-role="footer">
		<h1>Perspective Ltd.</h1>
	</div>
</div>

<div data-role="page" id="pagetwo">
	<div data-role="header">
		<h1>Search for a vacancy here...</h1>
	</div>

	<div data-role="main" class="ui-content">
		<p>Click on the link to go back. <b>Note</b>: fade is default.</p>
		<a href="#pageone">Go to Page One</a>
	</div>

	<div data-role="footer">
		<h1>Footer Text</h1>
	</div>
</div>


</body>
</html>