<?php /* @var $vo Employer */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Organisation</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>

	<script>
		$(function() {
			$( "#audit_log" ).dialog({
				autoOpen: false,
				show: {
					effect: "blind",
					duration: 1000
				},
				hide: {
					effect: "explode",
					duration: 1000
				},
				width:
					700,
				height:
					700
			});

			$( "#audit_log_opener" ).click(function() {
				$( "#audit_log" ).dialog( "open" );
			});

			$( "#audit_log_closer" ).click(function() {
				$( "#audit_log" ).dialog( "close" );
			});
		});
	</script>

	<script language="JavaScript">
		function deleteRecord()
		{
			if(window.confirm("Delete this employer?"))
			{
				window.location.replace('do.php?_action=delete_employer&id=<?php echo $vo->id; ?>');
			}
		}

		function populate()
		{
			var grid_level = document.getElementById('grid_level');
			grid_level.clear();
			var ty = "<?php echo $vo->organisation_type;?>";
			grid_level.setValues(ty.split(','));
		}

		function uploadFile() {
			var myForm = document.forms[1];
			myForm.submit();
		}


		//YAHOO.util.Event.onDOMReady(populate);


	</script>
</head>

<style type="text/css">
	.label
	{
		font-weight:bold;
	}

</style>

<body>
<div class="banner">
	<div class="Title"><?php echo $page_title ?></div>
	<div class="ButtonBar">
		<?php if($emp_group_id==null) { ?>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		<?php } else{ ?>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		<?php } if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==7 || $_SESSION['user']->type==8 || ($_SESSION['user']->type==User::TYPE_VERIFIER && DB_NAME=='am_mcq')) { ?>
		<button onclick="window.location.replace('do.php?id=<?php echo $vo->id; ?>&_action=edit_employer&edit=1');">Edit</button>
		<?php } ?>

		<?php if($_SESSION['user']->type!=4) {?>
		<!-- 		<button onclick="window.location.replace('do.php?id=<?php echo $vo->id; ?>&_action=edit_health_and_safety&back=read_employer');">Health & Safety</button> -->
		<?php } ?>

		<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==8) { ?>
		<?php if($_SESSION['user']->type!=12){?>
			<button onclick="deleteRecord();">Delete</button>
			<?php }} ?>
		<button id="audit_log_opener">Audit Log</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Name</h3>
<table border="0" cellspacing="4" cellpadding="4">
	<col width="150" />
	<tr><td class="fieldLabel">Legal name:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->legal_name); ?></td></tr>
	<tr><td class="fieldLabel">Trading name:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->trading_name); ?></td></tr>
	<tr><td class="fieldLabel">Abbreviation:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->short_name); ?></td></tr>
	<tr><td class="fieldLabel">Sector:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$sector); ?></td></tr>
	<tr><td class="fieldLabel">EDRS No:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->edrs); ?></td></tr>
	<tr><td class="fieldLabel">Retailer code:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->retailer_code); ?></td></tr>
	<tr><td class="fieldLabel">Size:</td><td class="fieldValue"><?php echo DAO::getSingleValue($link, "select description from lookup_employer_size where code = '$vo->code'"); ?></td></tr>
	<?php
	if ( SystemConfig::getEntityValue($link, 'module_recruitment') ) {
		echo '<tr><td class="fieldLabel">Sales Region:</td><td class="fieldValue">'.htmlspecialchars((string)$vo->region).'</td></tr>';
	}
	?>


	<tr><td class="fieldLabel">District:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->district); ?></td></tr>
	<tr>
		<td class="fieldLabel">Group Employer:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$group_employer); ?></td>
	</tr>

	<tr><td class="fieldLabel">Company Number:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->company_number); ?></td></tr>
	<tr><td class="fieldLabel">VAT Number:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->vat_number); ?></td></tr>
	<?php if(DB_NAME=='am_lewisham') { ?>
	<tr><td class="fieldLabel">Size:</td><td class="fieldValue"><?php echo DAO::getSingleValue($link, "select description from lookup_employer_size where code = '$vo->code'"); ?></td></tr>
	<?php } ?>

	<tr>
		<td class="fieldLabel">Account Manager:</td>
		<td class="fieldValue">
			<?php
				echo DAO::getSingleValue($link,"select CONCAT(firstnames,' ',surname) from users where username = '$vo->creator'");
			?>
		</td>
	</tr>
	<tr><td class="fieldLabel">Lead Referral:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->lead_referral); ?></td></tr>
	<tr><td class="fieldLabel">ONA:</td><td class="fieldValue"><?php if(is_null($vo->ono)) echo '';else echo $vo->ono? 'Yes':'No'; ?></td></tr>
	<tr><td class="fieldLabel">Number of on-site Employees:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->site_employees); ?></td></tr>
	<tr><td class="fieldLabel" valign="top">Training Provider:</td><td class="fieldValue"><?php echo (DAO::getSingleValue($link, "select legal_name, null from organisations where id = '{$vo->parent_org}'")); ?></td></tr>
	<?php if(DB_NAME == "am_superdrug")
	{ ?>
	<tr>
		<td class="fieldLabel">Salary Rate:</td>
		<?php
		$salary_rate_label = "";
		switch($vo->salary_rate)
		{
			case 1:
				$salary_rate_label = 'Grade 1';
				break;
			case 2:
				$salary_rate_label = 'Grade 2';
				break;
			case 3:
				$salary_rate_label = 'Grade 3';
				break;
			default:
				$salary_rate_label = "";
				break;
		}
		?>
		<td class="fieldValue"><?php echo $salary_rate_label; ?></td>
	</tr>
	<?php } ?>
</table>

<!--
	<tr>
		<td class="fieldLabel"><abbr title="UK Provider Reference Number">UKPRN</abbr>:</td>
		<td class="fieldValue"><?php //if($vo->ukprn != '') { ?><a href="" onclick="document.forms['display_UKRLP_record'].submit();return false;"
		title="Display provider's record in the UKRLP online database"><?php //echo htmlspecialchars((string)$vo->ukprn); ?></a>
		<img src="/images/external.png" /><?php //} ?></td>
	</tr>
	<tr><td class="fieldLabel">UPIN:</td><td class="fieldValue"><?php //echo htmlspecialchars((string)$vo->upin); ?></td></tr>
	 <tr>
		<td class="fieldLabel_compulsory" valign="top">Type:</td>
		<td class="fieldValue"><?php //echo HTML::checkboxGrid('level', $type_checkboxes, null, 3, false); ?></td>
	</tr>
-->
</table>

<!-- Hidden form for displaying a provider's UKRLP record -->
<form name="display_UKRLP_record" method="post" action="http://www.ukrlp.co.uk/ukrlp/ukrlp_provider.page_pls_searchProviders" target="_blank">
	<input type="hidden" name="pn_ukprn" value="<?php echo htmlspecialchars((string)$vo->ukprn); ?>" />
	<input type="hidden" name="x" value="" />
</form>

<h3>Notes</h3>

<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==7 || $_SESSION['user']->type==8 || $_SESSION['user']->type==3 || $_SESSION['user']->type==4 || ($_SESSION['user']->type==1 && $_SESSION['user']->org->organisation_type!=2) || (DB_NAME=='am_baltic' && $_SESSION['user']->type==12) || (DB_NAME=='am_pathway' && $_SESSION['user']->type==22)) { ?>
<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_crm_note&mode=new&organisations_id=<?php echo $vo->id; ?>&organisation_type=read_workplace'"> Add New Note</span>
	<?php } ?>
<?php $view2->render($link,'read_employer'); ?>

<h3>Locations</h3>
<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==7 || $_SESSION['user']->type==8 || (DB_NAME=='am_mcq' && $_SESSION['user']->type==4)) { ?>
<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_location&organisations_id=<?php echo $vo->id; ?>&back=<?php echo "employer"; ?>'"> Add new location </span>
	<?php } ?>
<?php $locations->render($link,'read_employer'); ?>

<h3>Learners</h3>
<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==7 || $_SESSION['user']->type==8 || (DB_NAME=='am_edudo' && $_SESSION['user']->type==1)) { ?>
<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Learner"; ?>&people_type=<?php echo 5; ?>'"> Add new learner </span>
	<?php } $vo3->render($link);  ?>

<h3>File Repository</h3>
<?php echo $html2;?>
<div>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=save_employer_repository" ENCTYPE="multipart/form-data">
		<input type="hidden" name="_action" value="save_employer_repository" />
		<input type="hidden" name="emp_id" value="<?php echo $emp_id;?>" />

		<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
			<col width="150" />
			<tr>
				<td class="fieldLabel_compulsory">File to upload:</td>
				<?php
				// re - 01/03/2012 - changed the form element name #22414
				//    - there are too many things called uploadFile around here
				//    - for clarity.  Also removed camelcase and replaced with
				//    - underscored word separation as above support request
				//    - was caused by camelcase issue.
				?>
				<td><input class="compulsory" type="file" name="uploaded_employer_file" />&nbsp;
					<span id="uploadFileButton" class="button" onclick="uploadFile()">&nbsp;Upload&nbsp;</span>
				</td>
			</tr>
		</table>
	</form>
</div>

<h3>System Users</h3>
<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==7 || $_SESSION['user']->type==8) { ?>
<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Admin"; ?>&people_type=<?php echo 1; ?> '"> Add administrator </span>
<!-- 
<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php //echo $vo->id; ?>&people=<?php //echo "Other Learner"; ?>&people_type=<?php //echo 2; ?> '"> Add FS Tutor </span>
-->
<!--<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php //echo $vo->id; ?>&people=<?php //echo "Other Learner"; ?>&people_type=<?php //echo 4; ?> '"> Add verifier </span>
-->
	<?php } $vo5->render($link);?>

<h3>Contacts</h3>
<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_crm_contact&org_type=employer&org_id=<?php echo $vo->id; ?>'"> Add New CRM Contact</span>
<?php
echo $viewCRMContacts->render($link);
?>

<!-- 
<h3>Training Records</h3>
<?php //$vo4->render($link); ?>
-->

<!-- 
<h3>Access Control</h3>
<p class="sectionDescription">Will include roles for administering the organisation,
reading all documents of the organisation and creating new users(?).  In security terms,
an organisation is a "domain" and there are varying levels of access to it.
Ordinary users have the least access of all, and may only see their own documents
and any documents to which they have been granted privileges by an organisation administrator.</p>
-->
<div id="audit_log" title="Employer Audit Log" style="height: 100px; overflow-y: scroll; overflow-x: scroll;" >
	<?php
	echo Note::renderNotes($link, 'employer', $vo->id);

	?>
</div>

</body>
</html>