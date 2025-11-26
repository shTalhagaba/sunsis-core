<?php /* @var $vo Employer */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sunesis | Organisation</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>

	<script language="JavaScript">
		function saveNewAccountManager()
		{
			if(document.getElementById('account_manager_desc').value.trim() == '')
			{
				alert('Please enter the account manager name');
				document.getElementById('AccountManagerDiv').style.display='None';
				return;
			}
			document.getElementById('AccountManagerDiv').style.display='None';
			postData = 'account_manager_desc=' + document.getElementById('account_manager_desc').value;

			var client = ajaxRequest('do.php?_action=ajax_save_account_manager', postData);

			document.getElementById('creator').value = '';
			var form = document.forms[0];
			var creator = form.elements['creator'];
			ajaxPopulateSelect(creator, 'do.php?_action=ajax_load_account_manager');
		}

		function saveNewGroupEmployer()
		{
			if($('#group_employer_desc').val().trim() == '')
			{
				alert('Please enter the group employer description');
				return;
			}
			$('#GroupEmployerDiv').hide();
			var postData = 'group_employer_desc=' + $('#group_employer_desc').val().trim();
			var client = ajaxRequest('do.php?_action=edit_employer&subaction=save_group_employer', postData);
			document.getElementById('group_employer').value = '';
			var form = document.forms[0];
			var group_employer = form.elements['group_employer'];
			ajaxPopulateSelect(group_employer, 'do.php?_action=ajax_load_account_manager&subaction=load_group_employers');
			$('#group_employer_desc').val('');
		}

		function employerExists()
		{

			editMode = <?php echo $editMode; ?>

//			alert("I am here");
//			return false;

			if(editMode == 1)
			{

				save();
			}
			else
			{
				var username = document.forms[0].elements['legal_name'];

				if(username.value == '')
				{
					return;
				}

				var client = ajaxRequest('do.php?_action=ajax_is_employer_exists&identifier='+ encodeURIComponent(username.value));
				if(client != null)
				{
					if(client.responseText != "")
					{
//					            alert("Following matching records found with this record\n"+ client.responseText+ "\nDo you want to continue?");
						var response = confirm("Following matchnig records found with this record\n\n"+ client.responseText+ "\nDo you want to continue?");
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

			/*	var levelGrid = document.getElementById('grid_level');
	   var levelValues = levelGrid.getValues();
	   if(levelValues.length == 0)
	   {
		   alert("Please select the type of this organisation");
		   return false;
	   }
	   else if((htmlspecialchars(forceASCII(levelGrid.getValues().join(','))).indexOf("2"))==-1)
	   {
		   alert("Please select the correct organisation type ");
		   return false;
	   }
	   else
	   {
		   document.getElementById('organisation_type').value=htmlspecialchars(forceASCII(levelGrid.getValues().join(',')));
	   }
   */

			document.getElementById('organisation_type').value = <?php echo $vo->organisation_type; ?>;

			var myForm = document.forms[0];
			if(validateForm(myForm) == false)
			{
				return false;
			}

			var illegal_characters = /[*,\/]/;

			var legal_name = myForm.elements['legal_name'];
			if(illegal_characters.test(legal_name.value))
			{
				alert("Full name may not contain '/', ',' or '*' characters");
				legal_name.focus();
				return false;
			}

		/*
			var trading_name = myForm.elements['trading_name'];
			if(illegal_characters.test(trading_name.value))
			{
				alert("Trading name may not contain '/', ',' or '*' characters");
				trading_name.focus();
				return false;
			}
		*/

			var short_name = myForm.elements['short_name'];
			if(illegal_characters.test(short_name.value))
			{
				alert("Abbreviation may not contain '/', ',' or '*' characters");
				short_name.focus();
				return false;
			}

			myForm.submit();
		}

		function trading_name_onfocus(trading_name)
		{
			if(trading_name.value == '')
			{
				trading_name.value = trading_name.form.elements['legal_name'].value;
			}
		}

		function short_name_onfocus(short_name)
		{
			if(short_name.value == '')
			{
				short_name.value = short_name.form.elements['legal_name'].value.substring(0, 13);
			}
		}

		function populate()
		{
			var grid_level = document.getElementById('grid_level');
			grid_level.clear();
			var ty = "<?php echo $vo->organisation_type;?>";
			grid_level.setValues(ty.split(','));
		}

		function saveBrand()
		{
			document.getElementById('brandDiv').style.display='None';
			postData = 'brand=' + encodeURIComponent(document.getElementById('brand').value);
			var client = ajaxRequest('do.php?_action=ajax_save_brand', postData);

			document.getElementById('brand').value = '';
			var form = document.forms[0];
			var manufacturer = form.elements['manufacturer'];
			ajaxPopulateSelect(manufacturer, 'do.php?_action=ajax_load_brand_dropdown');
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

			// To check if it goes beyond 100
			if(parseInt(myfield.value+keychar)<0 || parseInt(myfield.value+keychar)>9000)
				return false;

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

		//YAHOO.util.Event.onDOMReady(populate);
	</script>

</head>
<body>
<div class="banner">
	<div class="Title"><?php echo $page_title; ?></div>
	<div class="ButtonBar">
		<?php if(DB_NAME!='am_imi' || (DB_NAME=='am_imi' && $_SESSION['user']->isAdmin())) { ?>
		<!--		<button onclick="save();">Save</button>-->
		<button onclick="employerExists();">Save</button>
		<?php } ?>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Name</h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
	<input type="hidden" name="organisation_type" id="organisation_type" />
	<input type="hidden" name="_action" value="save_employer" />
	<table border="0" cellspacing="4" style="margin-left:10px">
		<col width="160" />
		<tr>
			<td class="fieldLabel_compulsory">Legal name:</td>
			<td><input class="compulsory" type="text" name="legal_name" value="<?php echo htmlspecialchars((string)$vo->legal_name); ?>" size="40" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">EDRS No:</td>
			<td><input class="optional" type="text" name="edrs" value="<?php echo htmlspecialchars((string)$vo->edrs); ?>" size="40" /></td>
			<td><a href="http://edrs.lsc.gov.uk" target="_blank"><img src="/images/external.png"></a></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Trading Name:</td>
			<td><input class="compulsory" type="text" name="trading_name" value="<?php echo htmlspecialchars((string)$vo->trading_name); ?>" size="40" onfocus="trading_name_onfocus(this);" />
		</tr>
		<tr>
			<td class="fieldLabel_compulsory"><abbr title="A space saver for use in views -- the shorter the better">Abbreviation</abbr>:</td>
			<td><input class="optional" type="text" name="short_name" value="<?php echo htmlspecialchars((string)$vo->short_name); ?>" size="12" maxlength="12" onfocus="short_name_onfocus(this);"/>
				<span style="color:gray;font-style:italic">12 letters or fewer</span></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional"><?php echo SystemConfig::getEntityValue($link, 'module_recruitment_v2')?'EDS URN':'Company Number'; ?>:</td>
			<td><input class="optional" type="text" name="company_number" value="<?php echo htmlspecialchars((string)$vo->company_number); ?>" size="20" maxlength="20" /><?php echo SystemConfig::getEntityValue($link, 'module_recruitment_v2')?'<span style="color:gray;font-style:italic"> required for uploading vacancies to NAS</span>':''; ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">VAT Number:</td>
			<td><input class="optional" type="text" name="vat_number" value="<?php echo htmlspecialchars((string)$vo->vat_number); ?>" size="20" maxlength="20" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Sector:</td>
			<td><?php echo HTML::select('sector', $sector_dropdown, $vo->sector, true, false); ?></td>
		</tr>
		<?php if(DB_NAME!='am_siemens' && DB_NAME!='am_siemens_demo') {?>
		<tr>
			<td class="fieldLabel_optional">Group Employer:</td>
			<td><?php echo HTML::select('manufacturer', $brands, $vo->manufacturer, true, false); ?></td>
			<td><span class="button" onclick="document.getElementById('brandDiv').style.display='block'"> New </span></td>
		</tr>
		<tr id="brandDiv" style="Display: None;">
			<td> Enter Group Employer </td>
			<td><input class="optional" type="text" id="brand" value="" size="40" maxlength="40" /></td>
			<td><span class="button" onclick="saveBrand();"> Save </span></td>
		</tr>
		<?php } ?>
		<tr>
			<td class="fieldLabel_optional">Retailer Code:</td>
			<td><input class="optional" type="text" name="retailer_code" value="<?php echo htmlspecialchars((string)$vo->retailer_code); ?>" size="10" maxlength="10" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Employer Code:</td>
			<td><input class="optional" type="text" name="employer_code" value="<?php echo htmlspecialchars((string)$vo->employer_code); ?>" size="10" maxlength="10" /></td>
		</tr>

		<?php
		if ( SystemConfig::getEntityValue($link, 'module_recruitment') || SystemConfig::getEntityValue($link, 'module_recruitment_baltic') ) {
			echo '<tr><td class="fieldLabel_compulsory">Sales Region:</td>';
			$mandatory_region = in_array(DB_NAME, ["am_baltic"]) ? true : false;
			echo '<td>'.HTML::select('region', $region_dropdown, $vo->region, true, $mandatory_region).'</td></tr>';
		}
		?>

		<tr>
			<td class="fieldLabel_optional">Region:</td>

			<?php
            if(DB_NAME=='am_siemens')
            {
                $districts_dropdown = array(array("SE","SE"),array("SW","SW"),array("NE","NE"),array("EofE","EofE"),array("NW","NW"),array("EM","EM"),array("WM","WM"),array("Lond","Lond"),array("Y&H","Y&H"));
                echo '<td>'.HTML::select('district', $districts_dropdown, $vo->district, true, false).'</td>';
            }
            else
            {
                echo '<td><input class="optional" type="text" name="district" value="' .htmlspecialchars((string)$vo->district). '" size="4" maxlength="4" /></td>';
            }
            ?>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Size:</td>
			<td><?php echo HTML::select('code', $code, $vo->code, true, false); ?></td>
		</tr>
		<?php if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"])) {?>
        <tr>
            <td class="fieldLabel_optional">Gold Star Employer:</td>
            <td class="optional"><?php echo HTML::checkbox('gold_employer', 1, $vo->gold_employer, true, false); ?></td>
        </tr>
        <?php } ?>
		<tr>
			<td class="fieldLabel_optional">Active?:</td>
			<td class="optional"><?php echo HTML::checkbox('active', 1, $vo->active, true, false); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Health and Safety?:</td>
			<td class="optional"><?php echo HTML::checkbox('health_safety', 1, $vo->health_safety, true, false); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">ONA:</td>
			<td class="optional"><?php echo HTML::checkbox('ono', 1, $vo->ono, true, false); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Number of on-site employees:</td>
			<td><input class="optional" type="text" name="site_employees" value="<?php echo htmlspecialchars((string)$vo->site_employees); ?>" size="4" maxlength="4" onkeypress= "return numbersonly(this, event);" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Levy Employer:</td>
			<td class="optional"><?php echo HTML::checkbox('levy_employer', 1, $vo->levy_employer, true, false); ?></td>
		</tr>
        	<?php if(DB_NAME == "am_baltic_demo" || DB_NAME == "am_baltic") {?>
		<tr>
                	<td class="fieldLabel_optional">Expert Provider Owner:</td>
                	<td class="optional"><?php echo HTML::checkbox('epp', 1, $vo->epp, true, false); ?></td>
        	</tr>
            	<tr>
                	<td class="fieldLabel_optional">Expert Provider Transactor:</td>
                	<td class="optional"><?php echo HTML::checkbox('ept', 1, $vo->ept, true, false); ?></td>
            	</tr>
        	<tr>
            <td class="fieldLabel_optional">Due Diligence:</td>
            <td class="optional"><?php echo HTML::checkbox('due_diligence', 1, $vo->due_diligence, true, false); ?></td>
        </tr>
		<tr>
			<td class="fieldLabel_optional">Source:</td>
			<td class="optional"><?php echo HTML::select('source', DAO::getResultset($link, "SELECT id, description FROM lookup_prospect_source ORDER BY description"), $vo->source, true); ?></td>
		</tr>
		<?php } ?>
		<?php if(DB_NAME == "am_peraesf") {?>
		<tr>
			<td class="fieldLabel_optional">C2 Applicable?:</td>
			<td class="optional"><?php echo HTML::checkbox('c2_applicable', 1, $vo->c2_applicable, true, false); ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td class="fieldLabel_optional">Account Manager:</td>
			<td><?php echo HTML::select('creator', $account_manager_dropdown, $vo->creator, true, false); ?></td>
			<?php if(DB_NAME == "am_siemens" || DB_NAME == "am_siemens_demo") { ?>
			<td><span class="button" onclick="document.getElementById('AccountManagerDiv').style.display='block'"> New </span></td>
			<?php } ?>
		</tr>
		<?php if(DB_NAME == "am_siemens" || DB_NAME == "am_siemens_demo") { ?>
		<tr id="AccountManagerDiv" style="Display: None;">
			<td> Enter new account manager</td>
			<td><input class="optional" type="text" id="account_manager_desc" value="" size="40" maxlength="40" /></td>
			<td><span class="button" onclick="saveNewAccountManager();">Save</span></td>
		</tr>
		<?php } ?>
		<tr>
			<td class="fieldLabel_optional">Lead Referral:</td>
			<td><input class="optional" type="text" name="lead_referral" value="<?php echo htmlspecialchars((string)$vo->lead_referral); ?>" size="40" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Training Provider:</td>
			<td><?php echo HTML::select('parent_org', $delivery_partner, $vo->parent_org, true, false); ?></td>
		</tr>
        <tr>
            <td class="fieldLabel_optional">Monthly Levy Amount:</td>
            <td><input class="optional" type="text" name="levy" value="<?php echo htmlspecialchars((string)$vo->levy); ?>" size="10" maxlength="10" onkeypress= "return numbersonly(this, event);" /></td>
        </tr>
		<?php if(DB_NAME == "am_baltic_demo" || DB_NAME == "am_baltic") {?>
<tr>
	<td class="fieldLabel_optional">No longer working with this employer:</td>
	<td class="optional"><?php echo HTML::checkbox('not_linked', 1, $vo->not_linked, true, false); ?></td>
</tr>
<tr>
	<td class="fieldLabel_optional">Comments:</td>
	<td class="optional"><textarea rows="5" cols="50" name="not_linked_comments"><?php echo $vo->not_linked_comments; ?></textarea> </td>
</tr>
	<?php } ?>
		<?php if(DB_NAME == "am_superdrug")
	{ ?>
		<tr>
			<td class="fieldLabel_optional">Salary Rate:</td>
			<?php
			$salary_rate_options = array(
				0=>array(0, '', null, null),
				1=>array(1, 'Grade 1'),
				2=>array(2, 'Grade 2'),
				3=>array(3, 'Grade 3'));
			?>
			<td><?php echo HTML::select('salary_rate', $salary_rate_options, $vo->salary_rate, false, false); ?></td>
		</tr>
		<?php } ?>

		<!--
	<tr>
		<td class="fieldLabel_optional">Provider Number (UPIN):</td>
		<td><?php //echo HTML::select('upin', $L01_dropdown, $vo->upin, true, false); ?></td>
	</tr>	
	<tr>
		<td class="fieldLabel_optional">UK Provider Reference Number:</td>
		<td><?php //echo HTML::select('ukprn', $L46_dropdown, $vo->ukprn, true, false); ?></td>
	</tr>	

	
	 <tr>
		<td class="fieldLabel_optional"><abbr title="UK Provider Reference Number">UKPRN</abbr>:</td>
		<td><input class="optional" type="text" name="ukprn" value="<?php //echo htmlspecialchars((string)$vo->ukprn); ?>" size="8" maxlength="8" />
		<a href="http://www.ukrlp.co.uk" target="_blank" style="font-size:80%">UK Register of Learning Providers</a>
		<img src="/images/external.png" /></td>
	</tr>

	 <tr>
		<td class="fieldLabel_compulsory" valign="top">Type:</td>
		<td class="fieldValue"><?php // echo HTML::checkboxGrid('level', $type_checkboxes, null, 3, true); ?></td>
	</tr>
-->
		<?php if(DB_NAME=="am_siemens" || DB_NAME=="am_siemens_demo") {?>
		<tr>
			<td class="fieldLabel_optional">Cost Centre:</td>
			<td><input class="optional" type="text" name="cost_centre" value="<?php echo htmlspecialchars((string)$vo->cost_centre); ?>" size="6" maxlength="6" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">ARE Code:</td>
			<td><input class="optional" type="text" name="are_code" value="<?php echo htmlspecialchars((string)$vo->are_code); ?>" size="4" maxlength="4" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Group Employer:</td>
			<td>
				<?php echo HTML::select('group_employer', DAO::getResultset($link, "SELECT id, description, null FROM lookup_group_employers ORDER BY description"), $vo->group_employer, true); ?>
				<span class="button" onclick="$('#GroupEmployerDiv').show();"> New </span>
			</td>
		</tr>
		<tr id="GroupEmployerDiv" style="display: none;">
			<td class="fieldLabel_optional"> Enter new group employer</td>
			<td>
				<input class="optional" type="text" id="group_employer_desc" value="" size="50" maxlength="50" />
				<span class="button" onclick="saveNewGroupEmployer();">Save</span> &nbsp;
				<span class="button" onclick="$('#group_employer_desc').val('');$('#GroupEmployerDiv').hide();">Cancel</span>
			</td>
		</tr>
		<tr>
			<td class="fieldLabel_optional" valign="top">Business Codes:</td>
			<td>
				<div style="height: 260px; overflow-y: scroll; overflow-x: scroll;" ><?php echo HTML::checkboxGrid('business_codes', $business_codes_list, $selected_business_codes_list, 2); ?></div>
			</td>
		</tr>

		<?php } ?>
	</table>
</form>
</body>
</html>