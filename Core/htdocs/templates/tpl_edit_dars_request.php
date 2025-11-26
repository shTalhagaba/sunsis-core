<?php /* @var $request DARSRequest */ ?>
<?php /* @var $requester User */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Raise Support Request</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>

	<script language="JavaScript">
		function save()
		{
			var myForm = document.forms["support_form"];
			if(validateForm(myForm) == false)
			{
				return false;
			}

			myForm.submit();
		}
	</script>
	<style type="text/css">

		body {
			background-color: transparent;
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
		}

		dd, select, textarea {
			padding: 2px 0;
			width: 300px;
		}

		.datadisplay {
			font-size: 1.2em;
		}

		legend {
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
			height: 250px;
			width: 315px;
			margin: 3px;
			padding: 3px;
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
	</style>
</head>
<body>
<div class="banner">
	<div class="Title">Raise Support Request</div>
	<div class="ButtonBar">
	<?php if( $saved != '1' ) {?>
	<button onclick="save();">Send</button>
	<button onclick="window.location.replace('<?php echo $_SESSION['bc']->getPrevious(); ?>');">Cancel</button>
	<?php } ?>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<div id="wrapper">
	<div id="content">
		<?php if( $saved == '1' ) {?>
			<h2>Thank you for your query</h2>
			<p>Your support request has been sent to Reed In Partnership</p>
			<?php if($case_number) { ?>
				<p>Your case number is: <strong><?php echo $case_number; ?></strong><br />
					(This number should be used in all future communications regarding this query)</p>
			<?php } ?>
			<p>A member of the Reed in Partnership Team will contact you at the earliest opportunity</p>
			<br/>
			<p>You can check on your support requests by visiting the <a href="do.php?_action=view_dars_requests_staff">Your Support Requests</a> section on Sunesis.</p>
		<?php } else { ?>
		<form name="support_form" id="support_form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
			<input type="hidden" name="id" value="<?php echo $request->id; ?>" />
			<input type="hidden" name="_action" value="edit_dars_request" />
			<input type="hidden" name="subaction" value="save" />
			<input type="hidden" name="requester" value="<?php echo $request->requester; ?>" />
			<fieldset>
				<dl>
					<dt><label for="type">Request Type</label><span style="color: red; padding-left: 5px;">*</span></dt>
					<dd><?php echo HTML::select("type",$request->getRequestTypes(),$request->type,false,true); ?></dd>
				</dl>
				<dl>
					<dt><label for="priority">Priority</label><span style="color: red; padding-left: 5px;">*</span></dt>
					<dd><?php echo HTML::select("priority",$request->getRequestPriorityList(),$request->priority); ?></dd>
				</dl>
				<dl>
					<dt><label for="details">Details</label><span style="color: red; padding-left: 5px;">*</span></dt>
					<dd><textarea name="details" id="details" class="compulsory" cols="50" rows="15"><?php echo htmlspecialchars((string)$request->details); ?></textarea>
						<p>Please include details of the action you were attempting to complete.</p>
					</dd>
				</dl>
			</fieldset>
			<fieldset>
				<dl>
					<dt>Your Name</dt>
					<dd class="datadisplay" ><?php echo $requester->firstnames; ?> <?php echo $requester->surname; ?></dd>
				</dl>
				<dl>
					<dt>Organisation</dt>
					<dd class="datadisplay" ><?php echo $requester->org->legal_name; ?></dd>
				</dl>
				<dl>
					<dt><label for="email">Email</label><span style="color: red; padding-left: 5px;">*</span></dt>
					<dd><input type="text" class="compulsory" id="email" name="email" value="<?php echo htmlspecialchars((string)$requester->work_email); ?>" style="width: 300px;" /></dd>
				</dl>
				<dl>
					<dt><label for="telephone">Phone Number</label></dt>
					<dd><input type="text" class="compulsory" name="telephone" id="telephone" value="<?php echo htmlspecialchars((string)$requester->work_telephone); ?>" style="width: 300px;" /></dd>
				</dl>
				<dl>
					<dt><label for="fax">Fax Number</label></dt>
					<dd><input type="text" class="optional" name="fax" id="fax" value="<?php echo htmlspecialchars((string)$requester->work_fax); ?>" style="width: 300px;" /></dd>
				</dl>
			</fieldset>
			<fieldset>
				<dl>
					<dt><label for="ufile">File attachment</label></dt>
					<dd><input type="file" id="ufile" name="ufile" /></dd>
				</dl>
				<span class="smalltext">If there is a screenshot or document you can send us to help us understand your query, please attach it here</span>
			</fieldset>
			<fieldset>
				<dl>
					<dt><label for="participants">Select Participants if necessary</label></dt>
					<dd>
						<div style="overflow: scroll; max-height: 500px;">
							<?php echo HTML::checkboxGrid('participants', $participants, $selected_participants); ?>
						</div>
					</dd>
				</dl>
			</fieldset>
		</form>
		<?php } ?>
	</div>
</div>
</body>
</html>