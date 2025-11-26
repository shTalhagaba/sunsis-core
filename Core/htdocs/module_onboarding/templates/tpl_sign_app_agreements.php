<?php /* @var $learner TrainingRecord */ ?>
<?php /* @var $employer_main_site Location */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Onboarding</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="/assets/adminlte/plugins/JQuerySteps/jquery.steps.css?n=<?php echo time(); ?>" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/module_onboarding/css/onboarding.css">

</head>


<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
	<div class="container">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#">
				<img height="50px" class="headerlogo" src="images/logos/SUNlogo.png" />
			</a>
		</div>
	</div>
</nav>

<div class="container">
	<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
		<h4>Apprenticeship Agreement</h4>
	</div>

	<div class="text-center"><img src="/images/logos/app_logo.jpg" alt="Apprenticeship" /></div>

	<div class="well">
		<p>Further to the Apprenticeships (Form of Apprenticeship Agreement) Regulations which came into force on 6th April 2012, an Apprenticeship Agreement is required at the commencement of an Apprenticeship for all new apprentices who start on or after that date.</p>
		<p>The purpose of the Apprenticeship Agreement is to:-</p>
		<ul style="margin-left: 25px;">
			<li>identify the skill, trade or occupation for which the apprentice is being trained; and</li>
			<li>confirm the qualifying Apprenticeship framework that the apprentice is following.</li>
		</ul>
		<p></p>
		<p>The Apprenticeship Agreement is incorporated into and does not replace the written statement of particulars issued to the individual in accordance with the requirements of the Employment Rights Act 1996.</p>
		<p>The Apprenticeship is to be treated as being a contract of service not a contract of Apprenticeship.</p>
	</div>

	<h4><strong>Apprenticeship Particulars</strong></h4>
	<table class="table row-border">
		<?php $f_t = DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$learner->id}'");?>
		<tr><th>Apprentice name:</th><td><?php echo $learner->firstnames . ' ' . $learner->surname; ?></td></tr>
		<tr>
			<th>Skill, trade or occupation for which the apprentice is being trained:</th>
			<td><?php echo $ob_learner->skills_trade_occ; ?></td>
		</tr>
		<tr><th>Relevant Apprenticeship framework and level:</th><td><?php echo $f_t; ?></td></tr>
		<tr><th>Start date:</th><td><?php echo Date::toShort($learner->start_date); ?></td></tr>
		<tr><th>Estimated completion of learning date:</th><td><?php echo Date::toShort($learner->target_date); ?></td></tr>
		<tr><th>Location:</th><td><?php echo $employer_main_site->address_line_1 . ' ' . $employer_main_site->address_line_2 . ' ' . $employer_main_site->address_line_3 . ' ' . $employer_main_site->address_line_4; ?></td></tr>
		<tr><th>Learner Signature:</th><td class="text-bold"><img src="do.php?_action=generate_image&<?php echo $ob_learner->learner_signature; ?>&size=25" style="border: 2px solid;border-radius: 15px;" /></td></tr>
		<tr><th colspan="2"><br></th></tr>
	</table>


	<form class="form-horizontal" name="frmSignAppAgreement" id="frmSignAppAgreement" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"  autocomplete="off">
		<input type="hidden" name="_action" value="save_sign_app_agreements" />
		<input type="hidden" name="ob_learner_id" value="<?php echo $ob_learner->id; ?>" />
		<input type="hidden" name="tr_id" value="<?php echo $learner->id; ?>" />
		<input type="hidden" name="contact_id" value="<?php echo $contact_id; ?>" />
		<input type="hidden" name="key" value="<?php echo $key; ?>" />

		<div class="row">
			<div class="col-sm-8">
			<span class="btn btn-info" onclick="getSignature();">
				<?php if(is_null($ob_learner->employer_signature)) {?>
				<img id="img_employer_signature" src="do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" />
				<?php } else {?>
				<img id="img_employer_signature" src="do.php?_action=generate_image&<?php echo $ob_learner->employer_signature; ?>&size=25" style="border: 2px solid;border-radius: 15px;" />
				<?php } ?>
				<input type="hidden" name="employer_signature" id="employer_signature" value="<?php echo !is_null($ob_learner->employer_signature)?'do.php?_action=generate_image&'.$ob_learner->employer_signature:''; ?>" />
			</span>
			<?php
				//if(!is_null($ob_learner->employer_signature))
				//	echo "<p><i class=\"text-muted\">click on signature box if you want to change</i> </p>";
			?>
			</div>
			<div class="col-sm-4">
				<h2 class="content-max-width"><?php echo date('d/m/Y'); ?></h2>
			</div>
		</div>

		<hr>

		<div class="col-sm-12">
			<span onclick="submitForm();" class="btn btn-primary btn-block"><b><i class="fa fa-save"></i> SAVE </b></span>
		</div>

		<hr><br>

		<!--<div id="panel_signature" title="Signature Panel">
			<div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter your name/initials, then select the signature font you like and press "Add". </div>
			<div>
				<table class="table row-border">
					<tr><td>Enter your name/initials</td><td><input type="text" id="signature_text" onkeyup="refreshSignature();" onkeypress="return onlyAlphabets(event,this);" /></td></tr>
					<tr>
						<td onclick="SignatureSelected(this);" class="sigbox"><img id="img1" src=""  /></td>
						<td onclick="SignatureSelected(this);" class="sigbox"><img id="img2" src=""  /></td>
					</tr>
					<tr>
						<td onclick="SignatureSelected(this);" class="sigbox"><img id="img3" src=""  /></td>
						<td onclick="SignatureSelected(this);" class="sigbox"><img id="img4" src=""  /></td>
					</tr>
					<tr>
						<td onclick="SignatureSelected(this);" class="sigbox"><img id="img5" src=""  /></td>
						<td onclick="SignatureSelected(this);" class="sigbox"><img id="img6" src=""  /></td>
					</tr>
					<tr>
						<td onclick="SignatureSelected(this);" class="sigbox"><img id="img7" src=""  /></td>
						<td onclick="SignatureSelected(this);" class="sigbox"><img id="img8" src=""  /></td>
					</tr>
				</table>
			</div>
		</div>-->
	</form>
</div>
<footer class="main-footer">
	<div class="pull-left">
		<table class="table" style="border-collapse: separate; border-spacing: 0.5em;">
			<tr>
				<td><img width="230px" src="images/logos/siemens/ESFA.png" /></td>
				<td><img src="images/logos/siemens/ESF.png" /></td>
				<td><img src="images/logos/siemens/ofsted.jpg" /></td>
				<td><img src="images/logos/siemens/top70.png" width="200px" height="99px" /></td>
				<td><img src="images/logos/siemens/top100.jpg" width="100px" height="165px" /></td>
			</tr>
		</table>
	</div>
	<div class="pull-right">
		<img src="images/logos/SUNlogo.png" />
	</div>
</footer>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/JQuerySteps/jquery.steps.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/common.js?n=<?php echo time(); ?>" type="text/javascript"></script>

<script>
	var fonts = Array("Little_Days.ttf","ArtySignature.ttf","Signerica_Medium.ttf","Champignon_Alt_Swash.ttf","Bailey_MF.ttf","Carolina.ttf","DirtyDarren.ttf","Ruf_In_Den_Wind.ttf");
	var sizes = Array(30,40,15,30,30,30,25,30);

	function refreshSignature()
	{
		for(var i = 1; i <= 8; i++)
			$("#img"+i).attr('src', 'images/loading.gif');

		for(var i = 0; i <= 7; i++)
			$("#img"+(i+1)).attr('src', 'do.php?_action=generate_image&title='+$("#signature_text").val()+'&font='+fonts[i]+'&size='+sizes[i]);
	}

	function loadDefaultSignatures()
	{
		for(var i = 1; i <= 8; i++)
			$("#img"+i).attr('src', 'images/loading.gif');

		for(var i = 0; i <= 7; i++)
			$("#img"+(i+1)).attr('src', 'do.php?_action=generate_image&title=Signature'+'&font='+fonts[i]+'&size='+sizes[i]);
	}

	function onlyAlphabets(e, t)
	{
		try {
			if (window.event) {
				var charCode = window.event.keyCode;
			}
			else if (e) {
				var charCode = e.which;
			}
			else { return true; }
			if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 32 || charCode == 39 || charCode == 45 || charCode == 8 || charCode == 46)
				return true;
			else
				return false;
		}
		catch (err) {
			alert(err.Description);
		}
	}

	function getSignature()
	{
		$( "#panel_signature" ).dialog( "open");
	}

	function SignatureSelected(sig)
	{
		$('.sigboxselected').attr('class','sigbox');
		sig.className = "sigboxselected";
	}

	$(function() {
		$( "#panel_signature" ).dialog({
			autoOpen: false,
			modal: true,
			draggable: false,
			width: "auto",
			height: 500,
			buttons: {
				'Add': function() {
					$("#img_employer_signature").attr('src',$('.sigboxselected').children('img')[0].src);
					$("#employer_signature").val($('.sigboxselected').children('img')[0].src);
					$(this).dialog('close');
				},
				'Cancel': function() {$(this).dialog('close');}
			}
		});

		loadDefaultSignatures();
	});

	function submitForm()
	{
		if($('#employer_signature').val() == '')
		{
			alert('Please sign the agreement before saving the form.');
			return;
		}
		var myForm = document.forms["frmSignAppAgreement"];

		myForm.submit();
	}
</script>

</body>
</html>
