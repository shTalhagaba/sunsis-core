<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Perspective - Sunesis</title>
<!-- link rel="stylesheet" href="/common.css" type="text/css" / -->
<link rel="stylesheet" href="/css/core.css" type="text/css"/>
<link rel="stylesheet" href="/css/open.css" type="text/css"/>
<link rel="stylesheet" href="/print.css" media="print" type="text/css"/>
<?php
	// #176 - allow for client specific styling
	$css_filename = SystemConfig::getEntityValue($link, 'styling');
	if ( $css_filename != '' ) {
		echo '<link rel="stylesheet" href="/css/client/'.$css_filename.'" type="text/css"/>';	
	} 
?>

</head>
<body onload="body_onload()" id="registration" >
<?php 
	$filename = DAO::getSingleValue($link, "Select value from configuration where entity='logo'");
	$filename = ($filename=='')?'perspective.png':$filename;
?>
  <div id="recruitment">
    <div id="customerlogo">
      <!--  img src="/images/logos/<?php echo $filename; ?>" alt="Sunesis - <?php echo DB_NAME; ?> candidate registration" / -->
    </div>
    <div id="divWarnings">
    </div>
    <div id="divMessages">
 
<?php 
    if( isset($_REQUEST['msg']) ) {
		echo $_REQUEST['msg'];
    }
?> 
    	<ul id="status">
	  		<li id="status_1" class="active">Employer Details</li>
	  		<li id="status_2" >Contact Details</li>
	  		<li id="status_3" >Location Details</li>
	  		<li id="status_4" >Confirmation</li>
		</ul>     		
    </div>				
    <div id="main">
    	<form name="recruitmentForm" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
			<input type="hidden" name="id" value="" />
			<input type="hidden" name="_action" value="save_candidate_employer" />
			<input type="hidden" name="organisations_id" value="" />
			<input type="hidden" name="screen_width" />
	    	<input type="hidden" name="screen_height" />
	    	<input type="hidden" name="color_depth" />
	    	<input type="hidden" name="flash" />
	    	<input type="hidden" name="destination" value="<?php echo (isset($_REQUEST['destination'])?htmlspecialchars((string)$_REQUEST['destination']):''); ?>" />
			<div id="wizard">
				<div id="items">
		  			<div id="registration_1" class="formentry" >
						<h1>Employer details</h1>
						<table>
						<tr>
							<td class="fieldLabel_compulsory">Employer name:<span style="color: red">&nbsp; * </span></td>
							<td><input class="compulsory" type="text" name="legal_name" value="" size="40" /></td>
						</tr>
						<tr>
							<td class="fieldLabel_optional">EDRS No:</td>
							<td>
								<input class="optional" type="text" name="edrs" value="" size="40" />
								<a href="http://edrs.lsc.gov.uk" target="_blank"><img src="/images/external.png"></a>
							</td>
						</tr>
						<tr>
							<td class="fieldLabel_optional">Company Number:</td>
							<td><input class="optional" type="text" name="company_number" value="" size="20" maxlength="20" /></td>
						</tr>
						<tr>
							<td class="fieldLabel_optional">VAT Number:</td>
							<td><input class="optional" type="text" name="vat_number" value="" size="20" maxlength="20" /></td>
						</tr>
						<tr>
							<td class="fieldLabel_compulsory">Sector:<span style="color: red">&nbsp; * </span></td>
							<td><?php echo HTML::select('sector', $sector_dropdown, '', true, true); ?></td>
						</tr>	
						<tr>
							<td class="fieldLabel_optional">Retailer Code:</td>
							<td><input class="optional" type="text" name="retailer_code" value="" size="10" maxlength="10" /></td>
						</tr>
						<tr>
							<td class="fieldLabel_optional">Employer Code:</td>
							<td>
								<input class="optional" type="text" name="employer_code" value="" size="10" maxlength="10" />
								<input type="hidden" name="district" value="" size="2" maxlength="2" />
							</td>
						</tr>
						</table>
						<div class="navigation" >
							&nbsp;<button type="button" class="next button" id="proceed_2" >Proceed &raquo;</button>
						</div>
					</div>
					<div id="registration_2" class="formentry" >
						<h1> Primary Contact </h1>
						<table>
						<tr>
							<td class="fieldLabel_compulsory">Contact name:<span style="color: red">&nbsp; * </span></td>
							<td><input class="compulsory" type="text" name="contact_name" value="" size="50"/></td>
						</tr>
						<tr>
							<td class="fieldLabel_compulsory">Mobile phone:<span style="color: red">&nbsp; * </span></td>
							<td><input class="compulsory" type="text" name="contact_mobile" value="" size="11"/></td>
						</tr>
						<tr>
							<td class="fieldLabel_compulsory">Telephone:<span style="color: red">&nbsp; * </span></td>
							<td><input class="compulsory" type="text" name="contact_telephone" value="" size="15"/></td>
						</tr>
						<tr>
							<td class="fieldLabel_compulsory">Email:<span style="color: red">&nbsp; * </span></td>
							<td><input class="compulsory" type="text" name="contact_email" value="" size="50"/></td>
						</tr>
						</table>
						<div class="navigation" >
							<button type="button" class="previous button" id="proceed_1" >&laquo; Back</button>
							<button type="button" class="next right button" id="proceed_3" >Proceed &raquo;</button>
						</div>
					</div>
					<div id="registration_3" class="formentry" >
						<h1> Location Details</h1>
						<table>
						<tr>
							<td class="fieldLabel_compulsory">Region:<span style="color: red">&nbsp; * </span></td>
							<td>
								<select name="region">
									<option value="" selected="selected">Please select..</option>
									<option value="North West">North West</option>
									<option value="North East">North East</option>
									<option value="Midlands">Midlands</option>
									<option value="West Midlands">West Midlands</option>
									<option value="East Midlands">East Midlands</option>
									<option value="London North">London North</option>
									<option value="London South">London South</option>	
								</select>
							</td>
						</tr>
						<tr>
							<td class="fieldLabel_compulsory">Location title:<span style="color: red">&nbsp; * </span></td>
							<td><input class="compulsory" type="text" name="full_name" value="" size="50"/></td>
						</tr>
						</table>
						<?php echo $bs7666->formatEdit(true); ?>
						<table>
						<tr>
							<td class="fieldLabel_optional">Telephone:</td>
							<td><input class="optional" type="text" name="telephone" value="" /></td>
						</tr>
						<tr>
							<td class="fieldLabel_optional">Fax:</td>
							<td><input class="optional" type="text" name="fax" value="" /></td>
						</tr>
						</table>
						<div class="navigation" >
							<button type="button" class="previous button" id="proceed_2" >&laquo; Back</button>
							<button type="button" class="next right button" id="proceed_4" >Proceed &raquo;</button>
						</div>
					</div>
					<div id="registration_4" class="formentry" >
						<h1>Privacy Policy</h1>
 						<p>
 							In order for us to use your information, please read the policy below, and click on 'register' if you are happy to send us your details.
 						</p>
 						<table>
						<tr>
							<td>				
								<?php include_once('templates/tpl_terms_and_conditions.php'); ?> 
							</td>
						</tr>
						</table>
    					<div class="navigation" >
    		   				<button type="button" class="previous button" id="proceed_3" >&laquo; Back</button>
							<button onclick="javascript:return save();" class="button" >Register &raquo; </button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script language="javascript" src="/js/sunesis-registration.js" type="text/javascript"></script>
	<noscript>
		<?php include_once('templates/tpl_noscript.php'); ?>
	</noscript>
</body>
</html>