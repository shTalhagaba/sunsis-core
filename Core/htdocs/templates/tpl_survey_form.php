<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Sunesis Support</title>
		<link rel="stylesheet" href="common.css" type="text/css" />
		<style type="text/css">

		body {
			background-color: transparent;
			/* text-align: center; */
		}

		#wrapper {
			background-color: #fff;
			margin: 0 auto;
  			padding: 20px;
  			text-align: left;
		}

		#content {
			float: left;
			margin: 0,auto;
			width: auto;
			padding: 0 10px;
		   /*	border-right:1px solid #7F9D89;  */
		}

		dd, input, select, textarea {
			padding: 7px 0;
			/*width: 210px;*/
		}

		.datadisplay {
			font-size: 1.2em;
		}

		.clearfix {
			clear: both;
			height: 0;
			overflow: hidden;
		}

		#help {
			float: left;
			width: 240px;
			padding-left: 10px;
		}

		legend {
			/* background-color:#77A22F; */
			/* border:1px solid #7F9D89; */
			color: #000;
			font-size: 1.4em;
			padding: 0;
			margin: 0px 0px 10px -3px;
  			text-align:right;
			text-transform: capitalize;
		}

		.smalltext {
			padding: 0 0 0 3px;
			margin: 0;
			font-size: 0.8em;
			color: #9f9f9f;
		}

		fieldset {
			/* background-color: #f9f9f9; */
			border: none;
			float: left;
			/*height: 250px; */
			/*width: 215px;*/
			margin: 3px;
			padding: 3px;
		}

		button {
			background-color: #77A22F;
			border: 1px solid #000;
			color: #fff;
		}

		button:hover {
			background-color: #000;
			border: 1px solid #77A22F;
			cursor: pointer;
		}

		div.banner, #breadcrumbs {
			text-align: left;
		}

		h2 {
			padding: 0;
			color: #77A22F;
			margin: 0 0 5px 0;
			font-weight: normal;
			font-size: 1.5em;
			/* text-decoration: underline; */
		}

  		.icon-pdf {
  			padding-left: 20px;
  			background: transparent url(/images/icons.png) 0 -248px no-repeat;
  			width: 50px;
  			height: 50px;
  			line-height: 1.2em;
  		}

  		.icon-new-pdf {
  			padding-left: 20px;
  			background: transparent url(/images/icons.png) 0 -286px no-repeat;
  			width: 50px;
  			height: 50px;
  			line-height: 1.2em;
  		}

		</style>
	</head>
	<body>

<?php
	if ( isset($_REQUEST['header']) ) {
		$banner = array();
		$banner['page_title'] = 'Survey Form';
		$banner['low_system_buttons'] = '';
		include_once('layout/tpl_banner.php');


	}
?>
		<div id="wrapper">
			<div id="content">
<?php
if( $sent ) {
?>
	<h2>Thank you for providing Survey information.</h2>
	<p>Your Survey information has been submitted to Perspective.</p>
	<?php if($survey_id) { ?>
		<p>Your Survey number is: <strong><?php echo $survey_id; ?></strong><br />
		(This number should be used in all future communications regarding this Survey)</p>
	<?php } ?>
<?php
}
else {
?>
	<h2>Learner Survey Form</h2>
	<form name="survey_form" id="survey_form" action="do.php?_action=survey_form&amp;subaction=send<?php echo $addparam; ?>" method="post" onsubmit="return save();" enctype="multipart/form-data">

		<fieldset>
			<dl>
				<dt>Your Name</dt>
				<dd class="datadisplay" ><?php echo $vo->firstnames; ?> <?php echo $vo->surname; ?></dd>
			</dl>
			<dl>
				<dt>Organisation</dt>
				<dd class="datadisplay" ><?php echo $vo->org->legal_name; ?></dd>
			</dl>
            <dl>
				<dt><label for="details">Details</label><span style="color: red; padding-left: 5px;">*</span></dt>
				<dd><textarea name="details" id="details" class="compulsory" cols="50" rows="4"></textarea>
				<p>
					Please include details in the Sunesis Survey form.
				</p>
				</dd>
			</dl>
            <dl>
				<dt></dt>
				<dd><button style="float:right;" type="submit" >Send &raquo;</button></dd>
			</dl>


		</fieldset>

	</form>
<?php } ?>

	</div>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script type="text/javascript">
		function save() {

			var myForm = document.survey_form;

			if( myForm.details.value == "" ) {
				alert("More information please!\n\nPlease let us have some details about your request");
				myForm.details.focus();
				return false;
			}

			return true;
		}
	</script>
    </div>
</body>
</html>
