<?php /* @var $vo Organisation */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Prospect</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>

	<script language="JavaScript">

		function postcodesValidation(postcode)
		{
			var postcode_value = postcode;
			if( !postcode_value.match(/(^gir\s0aa$)|(^[a-pr-uwyz]((\d{1,2})|([a-hk-y]\d{1,2})|(\d[a-hjks-uw])|([a-hk-y]\d[abehmnprv-y]))\s\d[abd-hjlnp-uw-z]{2}$)/i ) )
			{
				alert("Incorrect format for Postcode");
				return false;
			}

			return true;
		}

		function prospectExists()
		{
			editMode = <?php echo $editMode; ?>

			if(editMode == 1)
			{

				save();
			}
			else
			{

				var username = document.forms[0].elements['company'];

				if(username.value == '')
				{
					return;
				}

				var client = ajaxRequest('do.php?_action=ajax_is_prospect_exists&identifier='+ encodeURIComponent(username.value));
				if(client != null)
				{
					if(client.responseText != "")
					{
						var response = confirm("Following matching records found with this record\n\n"+ client.responseText+ "\nDo you want to continue?");
						if(response)
							save();
					}
					else
						save();
				}
			}
		}

		function save()
		{
			var myForm = document.forms[0];
			if(validateForm(myForm) == false)
			{
				return false;
			}
			if(!postcodesValidation(myForm.elements['postcode'].value))
			{
				myForm.elements['postcode'].focus();
				return false;
			}


			var illegal_characters = /[*,\/]/;

			var company = myForm.elements['company'];
			if(illegal_characters.test(company.value))
			{
				alert("Company name may not contain '/', ',' or '*' characters");
				company.focus();
				return false;
			}

			myForm.submit();
		}

		function numbersonly(myfield, e, dec)
		{
			var key;
			var keychar;

			if (window.event)
				key = window.event.keyCode;
			else if (e)
				key = e.which;
			else
				return true;
			keychar = String.fromCharCode(key);

			// control keys
			if ((key==null) || (key==0) || (key==8) ||
				(key==9) || (key==13) || (key==27) )
				return true;

			// numbers
			else if ((("0123456789").indexOf(keychar) > -1))
				return true;

			// decimal point jump
			else if (dec && (keychar == "."))
			{
				myfield.form.elements[dec].focus();
				return false;
			}
			else
				return false;
		}

	</script>

</head>
<body>
<div class="banner">
	<div class="Title"><?php echo $page_title; ?></div>
	<div class="ButtonBar">
		<?php if(DB_NAME!='am_imi' || (DB_NAME=='am_imi' && $_SESSION['user']->isAdmin())) { ?>
		<button onclick="prospectExists();">Save</button>
		<?php } ?>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Company Details</h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="auto_id" value="<?php echo $vo->auto_id; ?>" />
	<input type="hidden" name="dpn" value="<?php echo $vo->dpn; ?>" />
	<input type="hidden" name="_action" value="save_prospect" />
	<input type="hidden" name="edit_mode" value="<?php echo $editMode;?>" />
	<table border="0" cellspacing="4" style="margin-left:10px">
		<col width="140" />
		<tr>
			<td class="fieldLabel_compulsory">Company:</td>
			<td><input class="compulsory" type="text" name="company" value="<?php echo htmlspecialchars((string)$vo->company); ?>" size="40" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Address Line 1:</td>
			<td><input class="compulsory" type="text" name="address1" value="<?php echo htmlspecialchars((string)$vo->address1); ?>" size="40" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Address Line 2:</td>
			<td><input class="optional" type="text" name="address2" value="<?php echo htmlspecialchars((string)$vo->address2); ?>" size="40" />
		</tr>
		<tr>
			<td class="fieldLabel_optional">Address Line 3:</td>
			<td><input class="optional" type="text" name="address3" value="<?php echo htmlspecialchars((string)$vo->address3); ?>" size="40" />
		</tr>
		<tr>
			<td class="fieldLabel_optional">Address Line 4:</td>
			<td><input class="optional" type="text" name="address4" value="<?php echo htmlspecialchars((string)$vo->address4); ?>" size="40" />
		</tr>
		<tr>
			<td class="fieldLabel_optional">Address Line 5:</td>
			<td><input class="optional" type="text" name="address5" value="<?php echo htmlspecialchars((string)$vo->address5); ?>" size="40" />
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Postcode:</td>
			<td><input class="compulsory" type="text" name="postcode" value="<?php echo htmlspecialchars((string)$vo->postcode); ?>" size="15" />
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Telephone:</td>
			<td><input class="compulsory" type="text" name="telephone" value="<?php echo htmlspecialchars((string)$vo->telephone); ?>" size="15" />
		</tr>
		<tr>
			<td class="fieldLabel_optional">Fax:</td>
			<td><input class="optional" type="text" name="fax" value="<?php echo htmlspecialchars((string)$vo->fax); ?>" size="40" />
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Primary Email Address:</td>
			<td><input class="compulsory" type="text" name="primary_email_address" value="<?php echo htmlspecialchars((string)$vo->primary_email_address); ?>" size="40" />
		</tr>
		<tr>
			<td class="fieldLabel_optional">URL:</td>
			<td><input class="optional" type="text" name="url" value="<?php echo htmlspecialchars((string)$vo->url); ?>" size="40" />
		</tr>
		<tr>
			<td class="fieldLabel_optional">Twitter Address:</td>
			<td><input class="optional" type="text" name="twitter_address" value="<?php echo htmlspecialchars((string)$vo->twitter_address); ?>" size="40" />
		</tr>
		<tr>
			<td class="fieldLabel_optional">Facebook Address:</td>
			<td><input class="optional" type="text" name="facebook_address" value="<?php echo htmlspecialchars((string)$vo->facebook_address); ?>" size="40" />
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Country:</td>
			<td><?php echo HTML::select('country', $country_list, $vo->country, true, true); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Region:</td>
			<?php echo '<td>'.HTML::select('region', $region_dropdown, $vo->region, true, true).'</td></tr>'; ?>
		</tr>
		<!--		<tr>
			<td class="fieldLabel_compulsory">Status:</td>
			<td><input class="compulsory" type="text" name="status" value="<?php /*echo htmlspecialchars((string)$vo->status); */?>" size="40" />
		</tr>
-->		<tr>
		<td class="fieldLabel_optional">Number of Employees:</td>
		<td><input class="optional" type="text" name="no_employees" onKeyPress="return numbersonly(this, event);" value="<?php echo htmlspecialchars((string)$vo->no_employees); ?>" size="40" />
	</tr>
		<tr>
			<td class="fieldLabel_compulsory">Source:</td>
			<?php echo '<td>'.HTML::select('source', $source_dropdown, $vo->source, true, true).'</td></tr>'; ?>
		</tr>
	</table>
	<h3>Company Contact Details</h3>
	<table border="0" cellspacing="4" style="margin-left:10px">
		<col width="140" />
		<tr>
			<td class="fieldLabel_optional">Title:</td>
			<td><input class="optional" type="text" name="title" value="<?php echo htmlspecialchars((string)$vo->title); ?>" size="5" />
		</tr>
		<tr>
			<td class="fieldLabel_optional">First Name:</td>
			<td><input class="optional" type="text" name="firstname" value="<?php echo htmlspecialchars((string)$vo->firstname); ?>" size="40" />
		</tr>
		<tr>
			<td class="fieldLabel_optional">Surname:</td>
			<td><input class="optional" type="text" name="surname" value="<?php echo htmlspecialchars((string)$vo->surname); ?>" size="40" />
		</tr>
		<tr>
			<td class="fieldLabel_optional">Job Title:</td>
			<td><input class="optional" type="text" name="job" value="<?php echo htmlspecialchars((string)$vo->job); ?>" size="40" />
		</tr>
		<tr>
			<td class="fieldLabel_optional">Email 1:</td>
			<td><input class="optional" type="text" name="email1" value="<?php echo htmlspecialchars((string)$vo->email1); ?>" size="40" />
		</tr>
		<tr>
			<td class="fieldLabel_optional">Email 2:</td>
			<td><input class="optional" type="text" name="email2" value="<?php echo htmlspecialchars((string)$vo->email2); ?>" size="40" />
		</tr>
	</table>
</form>
</body>
</html>