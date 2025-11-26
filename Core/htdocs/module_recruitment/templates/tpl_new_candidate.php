<?php ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Register New Candidate</title>
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
	<div class="Title">Register New Candidate</div>
	<div class="ButtonBar">
		<button onclick="if(confirm('Are you sure?')){history.go(-1);}">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php 
	// establish row colors
	$row_colors = array(
		'high' => '#E0EAD0',
		'med' => '#FFE6D7',
		'low' => '#FFBFBF');

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
	}
	
	$vacancy_id = isset($_REQUEST['vacancy_id']) ? $_REQUEST['vacancy_id'] : NULL;

?>
<div id="infoblock">
	<?php $_SESSION['bc']->render($link); ?>
	<div id="feedback"><?php echo $feedback_message; ?></div>
</div>

<div id="maincontent">
	<h3>Register and screen a new candidate
	<?php 
	if ( isset($vacancy_id) ) {
		$candidate_vacancy = Vacancy::loadFromDatabase($link, $vacancy_id);
			// sugar case: 21735 [21729] - vacancy id not populating correctly.
			echo ' for the vacancy: <a onclick="open_new_window(\'/do.php?_action=read_vacancy&id='.$vacancy_id.'\');" href="#" >'.$candidate_vacancy->job_title.'</a> at '.$candidate_vacancy->trading_name;
	}
	?>
	</h3>
	<div id="new_registration" >
		<?php 
			echo $candidate->render_candidate_form($link, 1, $vacancy_id);
		?>
	</div>
	<script type="text/javascript">
	//<![CDATA[
	/*
    * relmes: extra validation for candidate registration requirements
    */
	var ele = document.getElementById("national_insurance");
	ele.validate = function() {
		/*
		* only concerned with ni if its been filled in.
		*/
		if ( this.value != "" ) {
			if( !this.value.match( /^[A-Za-z]{2}[0-9]{2}[0-9]{2}[0-9]{2}[A-Za-z]{1}$/ ) ) {
				alert("Incorrect format of National Insurance Number 'LL######L'");
				this.focus();
		    	return false;
			}
		}
		return true;
	}

	var cv_upload = document.getElementById("uploadedfile");
	cv_upload.validate = function() {
		/*
		* only concerned with CV if its being uploaded.
		*/
		if ( this.value != "" ) {
			extArray = new Array(".doc", ".pdf", ".docx");
			ext = this.value.slice(this.value.indexOf(".")).toLowerCase();
		    for (var i = 0; i < extArray.length; i++) {
		    	if (extArray[i] == ext) { 
			    	return true; 
			    }
		    }

		    alert("We only accept .doc or .pdf files for your CV");
			this.focus();
		    return false;
		}
	}
	
    var email_valid = document.getElementById("email");
    email_valid.validate = function() {
    	/*
		* only concerned with email if its been filled in.
		*/
        if ( this.value != "" ) {
    		if( !this.value.match( /^[-+.\w]{1,64}@[-.\w]{1,64}\.[-.\w]{2,6}$/ ) ) {
				alert("Incorrect format for Email Address");
				this.focus();
		    	return false;
    		}
		}
		return true;
    }

    var postcode_valid = document.getElementById("postcode");
    postcode_valid.validate = function() {
            	if( !this.value.match(/(^gir\s0aa$)|(^[a-pr-uwyz]((\d{1,2})|([a-hk-y]\d{1,2})|(\d[a-hjks-uw])|([a-hk-y]\d[abehmnprv-y]))\s\d[abd-hjlnp-uw-z]{2}$)/i ) ) {
			alert("Incorrect format for Postcode");
			this.focus();
		    return false;
		}
		return true;
    }
	//]]>
	</script>
</div>
	<?php 
		// include the footer options
		include_once('layout/tpl_footer.php'); 
	?>
	
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script language="javascript" src="/js/sunesis-registration.js" type="text/javascript"></script>
	<script type="text/javascript">

	$(document).ready( function() {
		$("#new_registration > tbody > tr").each( function() {
			$(this).css('display', 'block');
		});
	});

	function open_new_window(URL) {
		NewWindow = window.open(URL,"vacancy_screen","toolbar=no,menubar=0,status=0,copyhistory=0,location=no,scrollbars=yes,resizable=0,location=0,Width=920,Height=730") ;
		NewWindow.location.href = URL;
	}

	function setscreening(score, formid) {			
		formname = document.getElementById('screen_'+formid);
		formname.screening_score.value = score;
		var myForm = document.forms['screen_'+formid];
		
		if( !validateForm(myForm) ) {	
			return false;
		}
		myForm.submit();
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
