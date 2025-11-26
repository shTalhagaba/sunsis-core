<?php /* @var $vo Organisation */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Create New Employer</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>

<style type="text/css">

#candidates #maincontent .column table td {
	border-bottom: 1px solid #e9e9e9;
	border-top: 1px solid #e9e9e9;
}

tr.stripe, td.stripe {
	background-color: #E0EAD0;
}

</style>

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
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<script language="javascript" src="/js/highcharts.js" type="text/javascript"></script>

<script language="JavaScript">
function save() {
	
	document.getElementById('organisation_type').value = <?php echo $vo->organisation_type; ?>;
	
	var myForm = document.forms[0];
	if( validateForm(myForm) == false ) {
		return false;
	}
	
	var illegal_characters = /[*,\/]/;
	
	var legal_name = myForm.elements['legal_name'];
	if( illegal_characters.test(legal_name.value) ) {
		alert("Full name may not contain '/', ',' or '*' characters");
		legal_name.focus();
		return false;
	}
	
	var trading_name = myForm.elements['trading_name'];
	if( illegal_characters.test(trading_name.value) )	{
		alert("Trading name may not contain '/', ',' or '*' characters");
		trading_name.focus();
		return false;
	}

	var postData = 'get_emp=1';
	if ( legal_name.value != '' ) {
		postData += '&legal_name='+escape(legal_name.value);
	}
	if ( trading_name.value != '' ) {
		postData += '&trading_name='+escape(trading_name.value);
	}
	if ( myForm.elements['edrs'].value != '' ) {
		postData += '&edrs='+escape(myForm.elements['edrs'].value);
	}
	if ( myForm.elements['contact_name'].value != '' ) {
		postData += '&contact_name='+escape(myForm.elements['contact_name'].value);
	}
	if ( myForm.elements['postcode'].value != '' ) {
		postData += '&postcode='+escape(myForm.elements['postcode'].value);
	}

	var duplicate_name = /^ext_emp_/;
	var duplicates_selected = 0;
	var duplicates_removed = 0;
	
	$("INPUT[type='checkbox']").each(function(){
		if ( duplicate_name.test($(this).prop('name')) ) { 
			if ( $(this).prop('checked') ) {
				$("div[id=feedback]").html('There are still potential matching organisations within Sunesis selected');
				$('#feedback').css('background-color', '<?php echo $feedback_color; ?>');
				$('#feedback').slideDown('2000');
				duplicates_selected = 1;
				duplicates_removed = 0;
			}
			else {
				duplicates_removed = 1;
			}
		}
	});

	if ( duplicates_selected == 1 ) {
		return false;
	} 
 
	if ( duplicates_removed == 0 ) {
		var request = ajaxRequest('do.php?_action=ajax_employer_validate',postData);
		var response = request.responseText;
	
		if ( response != '' ) {
			lines = response.split(/\r\n|\r|\n/);
			$("div[id=feedback]").html(lines[0]);
			$('#feedback').css('background-color', '<?php echo $feedback_color; ?>');
			$('#feedback').slideDown('2000');

			var employer_length = lines.length;

			var htmlOutput = '<table>';
			
			for(var count = 1; count < employer_length; count++ ) {
				htmlOutput += lines[count];
			}

			htmlOutput += '</table>';	
	
			$("div[id=matches]").html(htmlOutput);
			return false;
		}
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

</script>

</head>
<body id="candidates">
	<div class="banner">
		<div class="Title"><?php echo $page_title; ?></div>
		<div class="ButtonBar">
			<?php if($_SESSION['user']->type!=12) { ?>
			<button onclick="save();">Save</button>
			<?php } ?>
 			<button onclick="if(confirm('Are you sure?'))window.history.go(-1);"> Cancel</button> 
		</div>
		<div class="ActionIconBar">
			<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" /></button>
			<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" /></button>
		</div>
	</div>
	<div id="infoblock">
		<?php 
			$_SESSION['bc']->render($link); 
		?>
		<div id="feedback"><?php echo $feedback_message; ?></div>
	</div>
	<div id="maincontent">
		<p>When you '<strong>save</strong>' this employer, if there are any existing organisations that may match it, they will be displayed for you to check. Depending on your screen resolution this will either be to the right or below the set up form.</p>
		<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
			<input type="hidden" name="organisation_type" id="organisation_type" />
			<input type="hidden" name="_action" value="save_rm_employer" />
			<input type="hidden" name="active" value="1" />
			<input type="hidden" name="short_name" id="short_name" value="<?php echo htmlspecialchars((string)$vo->short_name); ?>" size="12" maxlength="12" onfocus="short_name_onfocus(this);"/>
			<div id="col1" class="column">
				<h3>General Employer Information</h3>
				<p>
					Please fill in the details below, those marked with an asterisk (*) are required fields.
				</p>
				<table border="0" cellspacing="0" >
					<tr class="stripe">
						<td class="fieldLabel_compulsory">Legal name:&nbsp;*&nbsp;</td>
						<td><input class="compulsory" type="text" name="legal_name" value="<?php echo htmlspecialchars((string)$vo->legal_name); ?>" size="40" onblur="short_name_onfocus(document.getElementById('short_name'));trading_name_onfocus(document.getElementById('trading_name'));" /></td>
					</tr>
					<tr class="stripe">
						<td class="fieldLabel_compulsory">Trading Name:&nbsp;*&nbsp;</td>
						<td><input class="compulsory" type="text" name="trading_name" id="trading_name" value="<?php echo htmlspecialchars((string)$vo->trading_name); ?>" size="40" />
					</tr>
					<tr class="stripe">
						<td class="fieldLabel_compulsory">Sales Region:&nbsp;*&nbsp;</td>
						<td><?php echo HTML::select('region', $region_dropdown, $vo->region, true, true); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">EDRS No:</td>
						<td><input class="optional" type="text" name="edrs" value="<?php echo htmlspecialchars((string)$vo->edrs); ?>" size="40" />&nbsp;<a href="http://edrs.lsc.gov.uk" target="_blank"><img src="/images/external.png"></a></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">Company Number:</td>
						<td><input class="optional" type="text" name="company_number" value="<?php echo htmlspecialchars((string)$vo->company_number); ?>" size="20" maxlength="20" /></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">VAT Number:</td>
						<td><input class="optional" type="text" name="vat_number" value="<?php echo htmlspecialchars((string)$vo->vat_number); ?>" size="20" maxlength="20" /></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">Sector:</td>
						<td><?php echo HTML::select('sector', $sector_dropdown, $vo->sector, true, false); ?></td>
					</tr>	
					<tr>
						<td class="fieldLabel_optional">Retailer Code:</td>
						<td><input class="optional" type="text" name="retailer_code" value="<?php echo htmlspecialchars((string)$vo->retailer_code); ?>" size="10" maxlength="10" /></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">Employer Code:</td>
						<td><input class="optional" type="text" name="employer_code" value="<?php echo htmlspecialchars((string)$vo->employer_code); ?>" size="10" maxlength="10" /></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">District:</td>
						<td><input class="optional" type="text" name="district" value="<?php echo htmlspecialchars((string)$vo->district); ?>" size="2" maxlength="2" /></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">Size:</td>
						<td><?php echo HTML::select('size', $size, $vo->size, true, false); ?></td>
					</tr>	
					<tr>
						<td class="fieldLabel_optional">Account Manager:</td>
						<td><?php echo HTML::select('creator', $account_manager_dropdown, $vo->creator, true, false); ?></td>
					</tr>	
					<tr>
						<td class="fieldLabel_optional">Web Address:</td>
						<td><input class="optional" type="text" name="dealer_group" value="<?php echo htmlspecialchars((string)$vo->dealer_group); ?>" size="40" /></td>
					</tr>
				</table>
			</div>
			<div id="col2" class="column">
				<h3> Primary Employer  Contact </h3>
				<p>Please enter the details of the key contact at this employer, you can add more contacts after setting up the organisation</p>
				<table border="0" cellspacing="0" style="width: 98%;">
					<tr class="stripe">
						<td class="fieldLabel_compulsory">Contact name:&nbsp;*&nbsp;</td>
						<td><input class="optional" type="text" name="contact_name" value="<?php echo htmlspecialchars((string)$l_vo->contact_name); ?>" size="40"/></td>
					</tr>
					<tr class="stripe">
						<td class="fieldLabel_compulsory">
							Telephone:&nbsp;*&nbsp;
						</td>
						<td>	
							<input class="compulsory" type="text" name="contact_telephone" value="<?php echo htmlspecialchars((string)$l_vo->contact_telephone); ?>" size="15"/>
						</td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">
							Mobile phone:
						</td>
						<td>
							<input class="optional" type="text" name="contact_mobile" value="<?php echo htmlspecialchars((string)$l_vo->contact_mobile); ?>" size="11"/>
						</td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">Job Title:</td>
						<td><input class="optional" type="text" name="contact_title" value="<?php // echo htmlspecialchars((string)$l_vo->contact_title); ?>" size="40"/></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">Department:</td>
						<td><input class="optional" type="text" name="contact_title" value="<?php // echo htmlspecialchars((string)$l_vo->contact_department); ?>" size="40"/></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">Email:</td>
						<td><input class="optional" type="text" name="contact_email" value="<?php echo htmlspecialchars((string)$l_vo->contact_email); ?>" size="40"/></td>
					</tr>
				</table>
				<h3> Primary Location Details </h3>
				<p>This information is required to set up the main location for the employer, you can add more locations after this one has been set up</p>
				<table border="0" cellspacing="0" style="width: 98%;" >
					<tr class="stripe">
						<td class="fieldLabel_compulsory">Location title:&nbsp;*&nbsp;</td>
						<td><input class="compulsory" type="text" name="full_name" value="<?php echo htmlspecialchars((string)$l_vo->full_name); ?>" size="40"/></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional" valign="top">House No. / Name and Street:</td>
						<td><input class="optional" type="text" name="address_line_1" value="<?php echo htmlspecialchars((string)$l_vo->address_line_1); ?>" size="40"/></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">Suburb / Village:</td>
						<td><input class="optional" type="text" name="address_line_2" value="<?php echo htmlspecialchars((string)$l_vo->address_line_2); ?>" size="40" /></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">Town / City:</td>
						<td><input class="optional" type="text" name="address_line_3" value="<?php echo htmlspecialchars((string)$l_vo->address_line_3); ?>" size="40"/></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">County:</td>
						<td><input class="optional" type="text" name="address_line_4" value="<?php echo htmlspecialchars((string)$l_vo->address_line_4); ?>" size="40"/></td>
					</tr>
					<tr class="stripe">
						<td class="fieldLabel_compulsory">Current Postcode:&nbsp;*&nbsp;</td>
						<td><input class="optional" type="text" name="postcode" value="<?php echo htmlspecialchars((string)$l_vo->postcode); ?>" /></td>
					</tr>
				</table>
				<br/>
				<br/>
			</div>
			<!--  div style="clear:both"></div -->
			<div id="col3" class="column" >
				<h3>Potential Existing Employers</h3>
				<div id="matches">
					<p>
					There are currently no potential existing employers
					</p>
				</div>
			</div>
		</form>
	</div>
	<script language="javascript" type="text/javascript">
	// if the feedback element has content show it
		$(document).ready(function() {
			if ( '&nbsp;' != $('#feedback').html() ) {
				$('#feedback').css('background-color', '<?php echo $feedback_color; ?>');
				$('#feedback').slideDown('2000'); //.delay('1500').slideUp('2000');
			}

			$('#feedback').click(function(){
				$('#feedback').slideUp('2000');
			});
		});		
	</script>
	<noscript>
		<?php include_once('templates/tpl_noscript.php'); ?>
	</noscript>
</body>
</html>