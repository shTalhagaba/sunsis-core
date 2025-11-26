<?php /* @var $vo Organisation*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Organisation</title>
	<link rel="stylesheet" href="/common.css?v=<?php echo time(); ?>" type="text/css" />
	<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>

	<!-- CSS for TabView -->

	<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/skins/sam/tabview.css">
	<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/border_tabs.css">

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css" />
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#dataMatrix").tablesorter();
		});
	</script>

	<!-- Dependency source files -->

	<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/container/container.js"></script>

	<!-- Page-specific script -->
	<script type="text/javascript" src="/yui/2.4.1/build/utilities/utilities.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/element/element-beta.js"></script>

	<script type="text/javascript" src="/yui/2.4.1/build/treeview/treeview.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/animation/animation.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/accordion_menu/accordion-menu-v2.js"></script>

	<script type="text/javascript" src="/yui/2.4.1/build/dragdrop/dragdrop.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/tabview/tabview.js"></script>

	<script type="text/javascript">
		YAHOO.namespace("am.scope");


		function treeInit() {


			myTabs = new YAHOO.widget.TabView("demo");
		}


		YAHOO.util.Event.onDOMReady(treeInit);
	</script>
</head>

<style type="text/css">
	.label {
		font-weight: bold;
	}
</style>

<body onload='$(".loading-gif").hide();' class="yui-skin-sam">
	<div class="banner">
		<div class="Title"><?php echo $page_title ?></div>
		<div class="ButtonBar">
			<button onclick="window.location.replace('do.php?id=<?php echo $vo->id; ?>&_action=edit_system_owner');">Edit</button>
		</div>
		<div class="ActionIconBar">
			<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
			<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		</div>
	</div>

	<?php $_SESSION['bc']->render($link); ?>
	<div class="loading-gif" id="progress"></div>
	<div id="demo" class="yui-navset">

		<div align="left" style="font-size: 50px;padding: 15px;height: 50px;text-align: left;text-shadow: -4px 4px 3px #999, 1px -1px 2px #000;margin-top: 0;margin-bottom: 0;color: #395596;">
			<?php echo htmlspecialchars((string)$vo->legal_name?:''); ?>
		</div>
		<ul class="yui-nav">
			<li class="selected"><a href="#tab1"><em>Details</em></a></li>
			<li class=""><a href="#tab2"><em>Locations</em></a></li>
			<li class=""><a href="#tab3"><em>CRM Notes</em></a></li>
			<li class=""><a href="#tab4"><em>System Users</em></a></li>
			<li class=""><a href="#tab5"><em>Add System Users</em></a></li>
		</ul>
		<div class="yui-content" style='background: white;border-radius: 12px;border-width:1px;border-style:solid;border-color:#00A4E4;'>
			<div id="tab1">
				<h3>Details</h3>
				<table border="0" cellspacing="4" cellpadding="4">
					<col width="150" />
					<tr>
						<td class="fieldLabel">Legal name:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->legal_name?:''); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Trading name:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->trading_name?:''); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Abbreviation:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->short_name?:''); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Company Number:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->company_number?:''); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">VAT Number:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->vat_number?:''); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel"><abbr title="UK Provider Reference Number">UKPRN</abbr>:</td>
						<td class="fieldValue">
							<?php if ($vo->ukprn != '') { ?><a href="" onclick="document.forms['display_UKRLP_record'].submit();return false;" title="Display provider's record in the UKRLP online database"><?php echo htmlspecialchars((string)$vo->ukprn?:''); ?></a>
								<img src="/images/external.png" /><?php } ?>
						</td>
					</tr>
					<tr>
						<td class="fieldLabel">UPIN:</td>
						<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->upin?:''); ?></td>
					</tr>
				</table>
				<!-- Hidden form for displaying a provider's UKRLP record -->
				<form name="display_UKRLP_record" method="post" action="http://www.ukrlp.co.uk/ukrlp/ukrlp_provider.page_pls_searchProviders" target="_blank">
					<input type="hidden" name="pn_ukprn" value="<?php echo htmlspecialchars((string)$vo->ukprn?:''); ?>" />
					<input type="hidden" name="x" value="" />
				</form>
			</div>
			<div id="tab2">
				<h3>Locations</h3>
				<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_location&organisations_id=<?php echo $vo->id; ?>&back=<?php echo "system_owner"; ?>&history=<?php echo $history; ?>&id=<?php echo ''; ?>'"> Add new location </span>
				<?php $locations->render($link, 'read_system_owner'); ?>
			</div>
			<div id="tab3">
				<h3>CRM Notes</h3>
				<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_crm_note&organisations_id=<?php echo $vo->id; ?>&organisation_type=read_workplace'"> Add New Note </span>
				<?php $view2->render($link, 'read_system_owner'); ?>
			</div>
			<div id="tab4">
				<h3>System Users</h3>
				<?php $vo5->render($link); ?>
			</div>
			<div id="tab5">
				<table class="resultset">
					<thead>
						<tr>
							<th>Action</th>
							<th>User Type</th>
							<th>Description</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td align="center">
								<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Viewer"; ?>&people_type=<?php echo 12; ?> '"> Add system viewer </span>
							</td>
							<td>System Viewer</td>
							<td>System access restricted to read-only.</td>
						</tr>
						<?php if (SystemConfig::getEntityValue($link, "HSAuditor")) { ?>
							<tr>
								<td align="center">
									<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Consultant"; ?>&people_type=<?php echo 11; ?> '"> Add Consultant </span>
								</td>
								<td>Consultant</td>
								<td>System Viewer can only view the system records.</td>
							</tr>
						<?php } ?>
						<tr>
							<td align="center">
								<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Contract"; ?>&people_type=<?php echo 10; ?> '"> Add contract manager </span>
							</td>
							<td>Contract Manager</td>
							<td>Access to view Training Records.</td>
						</tr>
						<tr>
							<td align="center">
								<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Admin"; ?>&people_type=<?php echo 1; ?> '"> Add administrator </span>
							</td>
							<td>Administrator</td>
							<td>Access to Learner/Training Records for learners belonging to their organisation.</td>
						</tr>
						<!--
					<tr>
						<td align="center">
							<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Supervisor"; ?>&people_type=<?php echo 9; ?> '"> Add supervisor </span>
						</td>
						<td>Supervisor</td>
						<td>Access to view the Training Records of one ore more Assessors. Cannot edit Training Records or ILRs.</td>
					</tr>
					<tr>
						<td align="center">
							<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Apprentice Coordinator"; ?>&people_type=<?php echo 20; ?> '"> Add Apprentice Coordinator </span>
						</td>
						<td>Apprentice Coordinator</td>
						<td>Access to and able to Edit Training Records (case-loads) and can perform Progress Tracking, Reviews, CRM, Compliance & Interim/Summative IV. Access to view ILRs but not Edit.</td>
					</tr>
					<tr>
						<td align="center">
							<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "FS Tutor"; ?>&people_type=<?php echo 2; ?> '"> Add FS Tutor </span>
						</td>
						<td>FS Tutor</td>
						<td>Access to one or more Training Records (case-loads) to perform Progress Tracking, CRM & Compliance.</td>
					</tr>
					<tr>
						<td align="center">
							<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Assessor"; ?>&people_type=<?php echo 3; ?> '"> Add assessor </span>
						</td>
						<td>Assessor</td>
						<td>Access to one or more Training Records (case-loads) to perform Progress Tracking, Reviews, CRM & Compliance but cannot edit the Training Record or ILR.</td>
					</tr>
					<tr>
						<td align="center">
							<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Verifier"; ?>&people_type=<?php echo 4; ?> '"> Add verifier </span>
						</td>
						<td>Verifier</td>
						<td>Access to one ore more Training Records (case-loads) to perform Progress Tracking, Reviews, CRM, Compliance & Interim/Summative IV.</td>
					</tr>
-->
						<?php if (DB_NAME == 'am_demo' || DB_NAME == "am_baltic") { ?>
							<tr>
								<td align="center">
									<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Business Resource Manager"; ?>&people_type=<?php echo 23; ?> '"> Add Business Resource Manager </span>
								</td>
								<td>Business Resource Manager</td>
								<td>e-Recruitment Module User Type.</td>
							</tr>
							<tr>
								<td align="center">
									<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Telesales"; ?>&people_type=<?php echo 24; ?> '"> Add Telesales Person </span>
								</td>
								<td>Telesales</td>
								<td>e-Recruitment Module User Type.</td>
							</tr>
						<?php } ?>
						<?php if (DB_NAME == "am_baltic") { ?>
							<tr>
								<td align="center">
									<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Reviewer"; ?>&people_type=<?php echo User::TYPE_REVIEWER; ?> '"> Add Reviewer </span>
								</td>
								<td>Reviewer</td>
								<td>Read-only access to all the training records with edit right on Learners Reviews.</td>
							</tr>
						<?php } ?>
						<?php if (SystemConfig::getEntityValue($link, "module_crm")) { ?>
							<tr>
								<td align="center">
									<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "CRM Front Desk User"; ?>&people_type=<?php echo User::TYPE_CRM_FRON_DESK_USER; ?> '"> Add CRM Front Desk User </span>
								</td>
								<td>CRM Front Desk User</td>
								<td>This user has access to CRM Software menu and all its submenus. This user can also access Employers under Organisations menu.</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</body>

</html>