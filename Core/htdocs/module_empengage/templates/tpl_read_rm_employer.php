<?php /* @var $vo Organisation */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>View Created Employer</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
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
	
	var short_name = myForm.elements['short_name'];
	if( illegal_characters.test(short_name.value) )	{
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

</script>

</head>
<body id="candidates">
<div class="banner">
	<table cellspacing="5" cellpadding="0" width="100%" height="100%">
		<tr>
			<td valign="top"><?php echo $page_title; ?></td>
		</tr>
		<tr>
			<td valign="bottom">

				<?php if(DB_NAME!='am_imi' || (DB_NAME=='am_imi' && $_SESSION['user']->isAdmin())) { ?>			
				<button onclick="save();">Save</button>
				<?php } ?>
				<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
			</td>
		</tr>
	</table>
</div>

	<div class="banner">
		<div class="Title">View Created Employer</div>
		<div class="ButtonBar"></div>
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
		<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
			<input type="hidden" name="organisation_type" id="organisation_type" />
			<input type="hidden" name="_action" value="save_rm_employer" />
			<input type="hidden" name="short_name" id="short_name" value="<?php echo htmlspecialchars((string)$vo->short_name); ?>" size="12" maxlength="12" onfocus="short_name_onfocus(this);"/>
			<div id="col1" class="column">
				<h3>Name</h3>
				<table border="0" cellspacing="0" >
					<tr>
						<td class="fieldLabel_compulsory">Legal name:&nbsp;*&nbsp;</td>
						<td><?php echo htmlspecialchars((string)$vo->legal_name); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">EDRS No:</td>
						<td><?php echo htmlspecialchars((string)$vo->edrs); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">Trading Name:&nbsp;*&nbsp;</td>
						<td><?php echo htmlspecialchars((string)$vo->trading_name); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">Company Number:</td>
						<td><?php echo htmlspecialchars((string)$vo->company_number); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">VAT Number:</td>
						<td><?php echo htmlspecialchars((string)$vo->vat_number); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">Sector:</td>
						<td><?php echo $vo->sector; ?></td>
					</tr>	
					<tr>
						<td class="fieldLabel_optional">Retailer Code:</td>
						<td><?php echo htmlspecialchars((string)$vo->retailer_code); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">Employer Code:</td>
						<td><?php echo htmlspecialchars((string)$vo->employer_code); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">Sales Region:&nbsp;*&nbsp;</td>
						<td><?php echo $vo->region; ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">District:</td>
						<td><?php echo htmlspecialchars((string)$vo->district); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">Size:</td>
						<td><?php echo $vo->size; ?></td>
					</tr>	
					<tr>
						<td class="fieldLabel_optional">Active?:</td>
						<td class="optional"><?php echo $vo->active; ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">Health and Safety?:</td>
						<td class="optional"><?php echo $vo->health_safety; ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">Account Manager:</td>
						<td><?php echo $vo->creator; ?></td>
					</tr>	
				</table>
			</div>
			<div id="col2" class="column">
				<h3> Primary Contact </h3>
				<table border="0" cellspacing="0">
					<tr>
						<td class="fieldLabel_compulsory">Contact name:</td>
						<td><?php echo htmlspecialchars((string)$l_vo->contact_name); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">Contact job title:</td>
						<td><?php // echo htmlspecialchars((string)$l_vo->contact_title); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">Contact department:</td>
						<td><?php // echo htmlspecialchars((string)$l_vo->contact_department); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">Contact name:</td>
						<td><?php echo htmlspecialchars((string)$l_vo->contact_name); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">Mobile phone:</td>
						<td><?php echo htmlspecialchars((string)$l_vo->contact_mobile); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">Telephone:</td>
						<td><?php echo htmlspecialchars((string)$l_vo->contact_telephone); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">Email:</td>
						<td><?php echo htmlspecialchars((string)$l_vo->contact_email); ?></td>
					</tr>
				</table>
				<h3> Primary Location Details </h3>
				<table border="0" cellspacing="4">
					<tr>
						<td class="fieldLabel_compulsory">Location title:&nbsp;*&nbsp;</td>
						<td><?php echo htmlspecialchars((string)$l_vo->full_name); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional" valign="top">House No. / Name and Street:</td>
						<td><?php echo htmlspecialchars((string)$l_vo->address_line_1); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">Suburb / Village:</td>
						<td><?php echo htmlspecialchars((string)$l_vo->address_line_2); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">Town / City:</td>
						<td><?php echo htmlspecialchars((string)$l_vo->address_line_3); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">County:</td>
						<td><?php echo htmlspecialchars((string)$l_vo->address_line_4); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">Current Postcode:</td>
						<td><?php echo htmlspecialchars((string)$l_vo->postcode); ?></td>
					</tr>
				</table>
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

			display_actions(-1);

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