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
			border-right:1px solid #7F9D89;
		}
		
		dd, input, select, textarea {
			padding: 2px 0;
			width: 300px;
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
			height: 450px;
			width: 315px;
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
		$banner['page_title'] = 'Support Request Form';
		$banner['low_system_buttons'] = '';
		include_once('layout/tpl_banner.php'); 
		
		$_SESSION['bc']->add($link, "do.php?_action=support_form&header=1", "Support Request");
		$_SESSION['bc']->render($link);
	}
?>
		<div id="wrapper">
			<div id="content">
<?php 
if( $sent ) {
?>
	<h2>Thank you for your query</h2>
	<p>Your support request has been sent to Perspective Support</p>
	<?php if($case_id) { ?>
		<p>Your case number is: <strong><?php echo $case_id; ?></strong><br />
		(This number should be used in all future communications regarding this query)</p> 
	<?php } ?>
	<p>You have been sent a copy of the Support Request details to the email address you provided.</p>
	<p>A member of the Support Team will contact you at the earliest opportunity</p>
	<p>Support Hours are 9am until 5pm Monday to Friday</p>
	<br/>
	<p>You can check on your support requests by visting the <a href="do.php?_action=support_requests&amp;header=1">Your Support Requests</a> section on Sunesis.</p>
<?php 
}
else {
?>
	<h2>Send us a query</h2>
	<form name="support_form" id="support_form" action="do.php?_action=support_form&amp;subaction=send" method="post" onsubmit="return save();" enctype="multipart/form-data">
		<fieldset>
			<dl>
				<dt><label for="type">Request Type</label><span style="color: red; padding-left: 5px;">*</span></dt>
				<dd>
			<?php 
				// output the type of the support request
				echo HTML::select("type",$type,$this->getField("type"),false,true); 
			?>
				</dd>
			</dl>
			<dl>
				<dt><label for="priority">Priority</label><span style="color: red; padding-left: 5px;">*</span></dt>
				<dd>
			<?php 
				echo HTML::select("priority",$priority,$this->getField("priority")); 
			?>
				</dd>
			</dl>
			<dl>
				<dt><label for="details">Details</label><span style="color: red; padding-left: 5px;">*</span></dt>
				<dd><textarea name="details" id="details" class="compulsory" cols="50" rows="15"><?php echo htmlspecialchars((string)$this->getField("details")); ?></textarea>
				<p>
					Please include details of the action you were attempting to complete.
					<br/>
					<br/>
					e.g. the name of the learner, course or qualification, or the name of the report or export.
				</p>
				</dd>
			</dl>
		</fieldset>
		<fieldset>
			<dl>
				<dt>Your Name</dt>
				<dd class="datadisplay" ><?php echo $_SESSION['user']->firstnames; ?> <?php echo $_SESSION['user']->surname; ?></dd>
			</dl>
			<dl>
				<dt>Organisation</dt>
				<dd class="datadisplay" ><?php echo $_SESSION['user']->org->legal_name; ?></dd>
			</dl>
			<dl>
				<dt><label for="email">Email</label><span style="color: red; padding-left: 5px;">*</span></dt>
				<?php // removed defaulting as user data is poor ?>
				<dd><input type="text" class="compulsory" id="email" name="email" value="<?php echo htmlspecialchars((string)$_SESSION['user']->work_email); ?>" /></dd>
			</dl>
			<dl>
				<dt><label for="telephone">Phone Number</label></dt>
				<dd><input type="text" class="compulsory" name="telephone" id="telephone" value="<?php echo htmlspecialchars((string)$_SESSION['user']->work_telephone); ?>" /></dd>
			</dl>
			<dl>
				<dt><label for="fax">Fax Number</label></dt>
				<dd><input type="text" class="optional" name="fax" id="fax" value="<?php echo htmlspecialchars((string)$_SESSION['user']->work_fax); ?>" /></dd>
			</dl>
		</fieldset>
		<fieldset>
			<dl>
				<dt>
					<label for="ufile">File attachment</label>
				</dt>
				<dd>
				<input type="file" id="ufile" name="ufile"></input>
				</dd>
			</dl>
			<span class="smalltext">
				If there is a screenshot or document you can send us to help us understand your query, please attach it here.<br>
				If you want to upload multiple files, zip them to make single zip file.
			</span>
			<p>
				Thank you for taking the time to complete this form, our support hours are 9am until 5pm Monday to Friday.
			</p>
			<p>
				We aim to review all queries we receive via this form within 24 hours.
			</p>
			<button style="float:right;" type="submit" >Send &raquo;</button>
		</fieldset>
	</form>
<?php } ?>
	<div class="clearfix"></div>
	</div>
		<div id="help">
			<h2>Support Resources</h2>
			<p>
				Please use the guides below to help with your use of Sunesis.  All our 'How to' guides are in PDF format. 
			</p> 
			<?php echo $help_guide_html;?>
			<p>
				In order to view them you will need to have Adobe Reader installed.
			</p>
			<p>
				<a href="http://www.adobe.com/products/acrobat/readstep2.html" target="_blank"><img src="/images/get_adobe_reader.png" style="border:0;" alt="get adobe reader" /></a>
			</p>
		</div>
	</div>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script type="text/javascript">
		function save() {

			var myForm = document.support_form;

			// re - disabled to allow user friendly feedback	
			// General validation
			// if( validateForm(myForm) == false ) {
			//	return false;
			// }
				
			if ( myForm.type.value == "" ) {
                                alert("More information please! - Request Type\n\nWe need to know the type of your request so we can get the relevant people looking at it for you.");
                                myForm.type.focus();
                                return false;
                        }

			if ( myForm.priority.value == "" ) {
				alert("More information please! - Priority\n\nWe need to know how critical your request is so we can make sure we give your request the attention it needs");
                                myForm.priority.focus();
                                return false;
			}

			if( myForm.details.value == "" ) {
				alert("More information please!\n\nPlease let us have some details about your request");
				myForm.details.focus();
				return false;
			}

			if( myForm.email.value == "" ) {
                                alert("More information please! - Your Email Address\n\nSo we can let you know your reference number and keep you uptodate on progress");
                                myForm.details.focus();
                                return false;
                        }	

			return true;
		}
	</script>
</body>
</html>
