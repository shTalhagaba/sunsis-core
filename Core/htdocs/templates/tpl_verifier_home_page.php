
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis</title>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.css">
	<link rel="stylesheet" href="assets/adminlte/dist/css/skins/_all-skins.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/fullcalendar/fullcalendar.min.css">
	<link href="/assets/adminlte/plugins/datatables/jquery.dataTables.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		.sigbox {
			border-radius: 15px;
			border: 1px solid #EEE;
			cursor: pointer;
		}
		.sigboxselected {
			border-radius: 25px;
			border: 2px solid #EEE;
			cursor: pointer;
			background-color: #d3d3d3;
		}
		.ui-dialog-titlebar-close {
			visibility: hidden;
		}
	</style>

	<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>

</head>
<body class="hold-transition skin-green sidebar-mini">


<?php include_once(__DIR__ . '/layout/tpl_main_header.php'); ?>

<div class="content">

<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-12">

		<div class="box box-primary table-responsive collapsed-box">
			<div class="box-header with-border">
				<h3 class="box-title">Your Details</h3>
				<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button></div>
			</div>
			<div class="box-body box-profile">
				<img class="profile-user-img img-responsive img-circle" src="<?php echo $photopath; ?>" alt="Verifier profile picture">
				<h3 class="profile-username text-center"><?php echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?></h3>
				<p class="text-muted text-center"><?php echo DAO::getSingleValue($link, "SELECT description FROM lookup_user_types WHERE id = '{$_SESSION['user']->type}'"); ?></p>
				<i class="fa fa-envelope"></i> <?php echo $_SESSION['user']->work_email; ?>
				<ul class="list-group list-group-unbordered">
					<li class="list-group-item">
						<strong><i class="fa fa-map-marker margin-r-5"></i> Work Address</strong>
						<p class="text-muted">
							<?php
							echo !is_null($_SESSION['user']->work_address_line_1)?$_SESSION['user']->work_address_line_1 . '<br>':'';
							echo !is_null($_SESSION['user']->work_address_line_2)?$_SESSION['user']->work_address_line_2 . '<br>':'';
							echo !is_null($_SESSION['user']->work_address_line_3)?$_SESSION['user']->work_address_line_3 . '<br>':'';
							echo !is_null($_SESSION['user']->work_address_line_4)?$_SESSION['user']->work_address_line_4 . '<br>':'';
							echo !is_null($_SESSION['user']->work_postcode)?$_SESSION['user']->work_postcode . ' &nbsp; <a href="http://maps.google.co.uk/maps?f=q&hl=en&q=' . urlencode($_SESSION['user']->work_postcode) . '" target="_blank"><i class="fa fa-external-link"></i></a>':'';
							?>
						</p>
						<p>
							<?php echo isset($_SESSION['user']->work_telephone)?'<i class="fa fa-phone"></i> ' . $_SESSION['user']->work_telephone . '<br>':''; ?>
							<?php echo isset($_SESSION['user']->work_mobile)?'<i class="fa fa-mobile"></i> ' . $_SESSION['user']->work_mobile . '<br>':''; ?>
						</p>
					</li>
					<li class="list-group-item">
						<b>Employer: </b> <a class="pull-right text-bold"><?php echo $_SESSION['user']->org->legal_name; ?></a>
					</li>
				</ul>
			</div>
		</div>

	</div>
	<div class="col-lg-6 col-md-6 col-sm-12">
		<div class="box box-primary collapsed-box">
			<div class="box-header with-border">
				<h3 class="box-title"><span class="glyphicon glyphicon-stats"></span> Learners Stats (<?php echo $current_year . ' - ' . $next_year; ?>)</h3>
				<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button></div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-lg-6 col-xs-6">
						<div class="small-box bg-aqua">
							<div class="inner">
								<h3><?php echo DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id WHERE contract_year = '{$current_year}' AND tr.status_code = '1' AND tr.verifier = '{$_SESSION['user']->id}'");?></h3>
								<p>Learners in training</p>
							</div>
							<div class="icon"><i class="fa fa-hourglass-half"></i></div>
							<a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_verifier=<?php echo $_SESSION['user']->id; ?>&ViewTrainingRecords_filter_record_status[]=1&ViewTrainingRecords_filter_contract_year={}" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>

					<div class="col-lg-6 col-xs-6">
						<div class="small-box bg-red">
							<div class="inner">
								<h3><?php echo DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr INNER JOIN contracts ON tr.`contract_id` = contracts.id WHERE contract_year = '{$current_year}' AND tr.`status_code` = 1 AND target_date < CURDATE()  AND tr.verifier = '{$_SESSION['user']->id}';");?></h3>
								<p>Learners past planned end date</p>
							</div>
							<div class="icon"><i class="fa fa-calendar-plus-o"></i></div>
							<a href="#" class="small-box-footer">&nbsp;</a>
						</div>
					</div>

					<div class="col-lg-6 col-xs-6">
						<div class="small-box bg-yellow">
							<div class="inner">
								<h3><?php echo DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id WHERE contract_year = '{$current_year}' AND tr.verifier = '{$_SESSION['user']->id}' AND tr.status_code = '6' AND tr.l03 NOT IN (SELECT tr2.l03 FROM tr AS tr2 WHERE tr2.`start_date` > tr.`start_date` AND tr2.status_code != '6')");?></h3>
								<p>Learners temporarily withdrawn</p>
							</div>
							<div class="icon">
								<i class="fa fa-pause"></i>
							</div>
							<a href="#" class="small-box-footer">&nbsp;</a>
						</div>
					</div>

					<div class="col-lg-6 col-xs-6">
						<div class="small-box bg-red">
							<div class="inner">
								<h3><?php echo DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id WHERE contract_year = '{$current_year}' AND tr.verifier = '{$_SESSION['user']->id}' AND tr.status_code = '3'");?></h3>
								<p>Learners withdrawn</p>
							</div>
							<div class="icon">
								<i class="fa fa-chain-broken"></i>
							</div>
							<a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_verifier=<?php echo $_SESSION['user']->id; ?>&ViewTrainingRecords_filter_record_status[]=3&ViewTrainingRecords_filter_contract_year=<?php echo $current_year; ?>" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>

				</div>
			</div>
		</div>

	</div>
</div>
<div class="row">

	<div class="col-lg-8 col-md-8 col-sm-12">
		<div class="box box-success box-solid">
			<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-calendar"></i> Learners</h3> </div>
			<div class="box-body no-padding table-responsive">
				<table id="tblLearners" class="table row-border small">
					<thead><tr><th></th><th>Learner</th><th>L03</th><th>Last Login</th><th>Progress</th><th></th><th></th><th></th></tr></thead>
					<tbody>
					<?php
					$verifier_id = $_SESSION['user']->id;
					$result = DAO::getResultset($link, "SELECT
  tr.id,
  tr.username,
  tr.`firstnames`,
  tr.`surname`,
  tr.l03,
  student_qualifications.id AS qan
FROM
  tr INNER JOIN student_qualifications ON tr.id = student_qualifications.`tr_id`
WHERE tr.`verifier` = '$verifier_id'
  AND tr.`status_code` = 1 AND student_qualifications.id IN ('Z0001875', '60313432');

", DAO::FETCH_ASSOC);

					if(count($result) == 0)
						echo '<tr><td colspan="8"><i>No record found</i></td></tr>';
					else
					{
						foreach($result AS $row)
						{
							echo '<tr><td><a href="do.php?_action=read_training_record&id=' . $row['id'] . '"><i class="fa fa-folder-open"></i></a></td>';
							echo '<td>' . $row['firstnames'] . ' ' . strtoupper($row['surname']) . '</td><td>' . $row['l03'] . '</td>';
							$last_login = DAO::getSingleValue($link, "SELECT `date` FROM logins WHERE username = '{$row['username']}' ORDER BY `date` DESC LIMIT 1");
							echo $last_login == ''?'<td></td>':'<td>' . Date::to($last_login, Date::DATETIME) . '</td>';
							$t = TrainingRecord::getFrameworkTarget($link, $row['id']);
							$t = sprintf("%.1f", $t);
							$a = TrainingRecord::getPercentageAchieved($link, $row['id']);
							$a = sprintf("%.1f", $a);

							echo <<<HTML
<td>
	<table class="table table-bordered well-white">
		<tr>
			<td style="width: 15%;">Target</td>
			<td style="width: 70%;">
				<div class="progress progress-sm progress-striped active">
					<div class="progress-bar progress-bar-primary" style="width: $t%"></div>
				</div>
			</td>
			<td style="width: 15%;"><span class="badge bg-light-blue">$t%</span></td>
		</tr>
		<tr>
				<td style="width: 15%;">Achieved</td>
				<td style="width: 70%;">
					<div class="progress progress-sm progress-striped active">
						<div class="progress-bar progress-bar-success" style="width: $a%"></div>
					</div>
				</td>
				<td style="width: 15%;">
					<span class="badge bg-green">$a%</span><br>
				</td>
			</tr>
	</table>
</td>
HTML;
							$workbook_result = DAO::getResultset($link, "SELECT * FROM workbooks WHERE tr_id = '{$row['id']}'", DAO::FETCH_ASSOC);
							echo <<<WORKBOOKS
<td>
	<div class="btn-group" title="Learner Workbooks">
		<button type="button" class="btn btn-default">W</button>
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
		<span class="caret"></span>
		<span class="sr-only">Toggle Dropdown</span>
		</button>
		<ul class="dropdown-menu" role="menu">
WORKBOOKS;

							if(count($workbook_result) == 0)
								echo '<li><i>No record found</i></li>';
							else
							{
								foreach($workbook_result AS $workbook_row)
								{
									if($workbook_row['wb_title'] == 'WBCommunication')
										echo '<li><a href="#" onclick="return window.phpVerifierSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'do.php?_action=wb_communication&id='.$workbook_row['id'].'&tr_id='.$workbook_row['tr_id'].'\'"><i class="' . Workbook::getWBStatusIcon($workbook_row['wb_status']) . '"></i>Communication</a></li>';
									if($workbook_row['wb_title'] == 'WBCustomerExperience')
										echo '<li><a href="#" onclick="return window.phpVerifierSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'do.php?_action=wb_customer_experience&id='.$workbook_row['id'].'&tr_id='.$workbook_row['tr_id'].'\'"><i class="' . Workbook::getWBStatusIcon($workbook_row['wb_status']) . '"></i>Customer Experience</a></li>';
									if($workbook_row['wb_title'] == 'WBDevelopingSelf')
										echo '<li><a href="#" onclick="return window.phpVerifierSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'do.php?_action=wb_developing_self&id='.$workbook_row['id'].'&tr_id='.$workbook_row['tr_id'].'\'"><i class="' . Workbook::getWBStatusIcon($workbook_row['wb_status']) . '"></i>Developing Self</a></li>';
									if($workbook_row['wb_title'] == 'WBKnowingYourCustomers')
										echo '<li><a href="#" onclick="return window.phpVerifierSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'do.php?_action=wb_knowing_your_customers&id='.$workbook_row['id'].'&tr_id='.$workbook_row['tr_id'].'\'"><i class="' . Workbook::getWBStatusIcon($workbook_row['wb_status']) . '"></i>Knowing Your Customers</a></li>';
									if($workbook_row['wb_title'] == 'WBMeetingRegulationsAndLegislation')
										echo '<li><a href="#" onclick="return window.phpVerifierSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'do.php?_action=wb_meeting_regulations_and_legislation&id='.$workbook_row['id'].'&tr_id='.$workbook_row['tr_id'].'\'"><i class="' . Workbook::getWBStatusIcon($workbook_row['wb_status']) . '"></i>Regulations & Legislation</a></li>';
									if($workbook_row['wb_title'] == 'WBProductAndService')
										echo '<li><a href="#" onclick="return window.phpVerifierSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'do.php?_action=wb_product_and_service&id='.$workbook_row['id'].'&tr_id='.$workbook_row['tr_id'].'\'"><i class="' . Workbook::getWBStatusIcon($workbook_row['wb_status']) . '"></i>Product and Service</a></li>';
									if($workbook_row['wb_title'] == 'WBRoleResponsibility')
										echo '<li><a href="#" onclick="return window.phpVerifierSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'do.php?_action=wb_role_responsibility&id='.$workbook_row['id'].'&tr_id='.$workbook_row['tr_id'].'\'"><i class="' . Workbook::getWBStatusIcon($workbook_row['wb_status']) . '"></i>Role, Responsibility & Personal Organisation</a></li>';
									if($workbook_row['wb_title'] == 'WBSystemsAndResources')
										echo '<li><a href="#" onclick="return window.phpVerifierSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'do.php?_action=wb_systems_and_resources&id='.$workbook_row['id'].'&tr_id='.$workbook_row['tr_id'].'\'"><i class="' . Workbook::getWBStatusIcon($workbook_row['wb_status']) . '"></i>Systems and Resources</a></li>';
									if($workbook_row['wb_title'] == 'WBTeamWorking')
										echo '<li><a href="#" onclick="return window.phpVerifierSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'do.php?_action=wb_team_working&id='.$workbook_row['id'].'&tr_id='.$workbook_row['tr_id'].'\'"><i class="' . Workbook::getWBStatusIcon($workbook_row['wb_status']) . '"></i>Team Working</a></li>';
									if($workbook_row['wb_title'] == 'WBUnderstandingTheOrganisation')
										echo '<li><a href="#" onclick="return window.phpVerifierSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'do.php?_action=wb_understanding_the_organisation&id='.$workbook_row['id'].'&tr_id='.$workbook_row['tr_id'].'\'"><i class="' . Workbook::getWBStatusIcon($workbook_row['wb_status']) . '"></i>Understanding the Organisation</a></li>';
									if($workbook_row['wb_title'] == 'WBEnvironment')
										echo '<li><a href="#" onclick="return window.phpVerifierSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'do.php?_action=wb_environment&id='.$workbook_row['id'].'&tr_id='.$workbook_row['tr_id'].'\'"><i class="' . Workbook::getWBStatusIcon($workbook_row['wb_status']) . '"></i>Environment</a></li>';
									if($workbook_row['wb_title'] == 'WBFinancial')
										echo '<li><a href="#" onclick="return window.phpVerifierSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'do.php?_action=wb_financial&id='.$workbook_row['id'].'&tr_id='.$workbook_row['tr_id'].'\'"><i class="' . Workbook::getWBStatusIcon($workbook_row['wb_status']) . '"></i>Financial</a></li>';
									if($workbook_row['wb_title'] == 'WBHSAndSecurity')
										echo '<li><a href="#" onclick="return window.phpVerifierSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'do.php?_action=wb_hs_and_security&id='.$workbook_row['id'].'&tr_id='.$workbook_row['tr_id'].'\'"><i class="' . Workbook::getWBStatusIcon($workbook_row['wb_status']) . '"></i>H&S and Security</a></li>';
									if($workbook_row['wb_title'] == 'WBPersonalTeamPerformance')
										echo '<li><a href="#" onclick="return window.phpVerifierSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'do.php?_action=wb_personal_team_performance&id='.$workbook_row['id'].'&tr_id='.$workbook_row['tr_id'].'\'"><i class="' . Workbook::getWBStatusIcon($workbook_row['wb_status']) . '"></i>Personal and Team Performance</a></li>';
									if($workbook_row['wb_title'] == 'WBTechnical')
										echo '<li><a href="#" onclick="return window.phpVerifierSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'do.php?_action=wb_technical&id='.$workbook_row['id'].'&tr_id='.$workbook_row['tr_id'].'\'"><i class="' . Workbook::getWBStatusIcon($workbook_row['wb_status']) . '"></i>Technical</a></li>';
									if($workbook_row['wb_title'] == 'WBStock')
										echo '<li><a href="#" onclick="return window.phpVerifierSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'do.php?_action=wb_stock&id='.$workbook_row['id'].'&tr_id='.$workbook_row['tr_id'].'\'"><i class="' . Workbook::getWBStatusIcon($workbook_row['wb_status']) . '"></i>Stock</a></li>';
									if($workbook_row['wb_title'] == 'WBBusinessAndBrandReputation')
										echo '<li><a href="#" onclick="return window.phpVerifierSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'do.php?_action=wb_business_and_brand_reputation&id='.$workbook_row['id'].'&tr_id='.$workbook_row['tr_id'].'\'"><i class="' . Workbook::getWBStatusIcon($workbook_row['wb_status']) . '"></i>Business & Brand Reputation</a></li>';
									if($workbook_row['wb_title'] == 'WBMarketing')
										echo '<li><a href="#" onclick="return window.phpVerifierSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'do.php?_action=wb_marketing&id='.$workbook_row['id'].'&tr_id='.$workbook_row['tr_id'].'\'"><i class="' . Workbook::getWBStatusIcon($workbook_row['wb_status']) . '"></i>Marketing</a></li>';
									if($workbook_row['wb_title'] == 'WBSalesPromotionMarchandising')
										echo '<li><a href="#" onclick="return window.phpVerifierSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'do.php?_action=wb_sales_promotion_marchandising&id='.$workbook_row['id'].'&tr_id='.$workbook_row['tr_id'].'\'"><i class="' . Workbook::getWBStatusIcon($workbook_row['wb_status']) . '"></i>Sales and Promotion & Marchandising</a></li>';
									if($workbook_row['wb_title'] == 'WBCustomer')
										echo '<li><a href="#" onclick="return window.phpVerifierSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'do.php?_action=wb_customer&id='.$workbook_row['id'].'&tr_id='.$workbook_row['tr_id'].'\'"><i class="' . Workbook::getWBStatusIcon($workbook_row['wb_status']) . '"></i>Customer</a></li>';
									if($workbook_row['wb_title'] == 'WBRetailProductAndService')
										echo '<li><a href="#" onclick="return window.phpVerifierSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'do.php?_action=wb_retail_product_and_service&id='.$workbook_row['id'].'&tr_id='.$workbook_row['tr_id'].'\'"><i class="' . Workbook::getWBStatusIcon($workbook_row['wb_status']) . '"></i>Product & Service</a></li>';
									if($workbook_row['wb_title'] == 'WBLegalAndGovernance')
										echo '<li><a href="#" onclick="return window.phpVerifierSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'do.php?_action=wb_legal_and_governance&id='.$workbook_row['id'].'&tr_id='.$workbook_row['tr_id'].'\'"><i class="' . Workbook::getWBStatusIcon($workbook_row['wb_status']) . '"></i>Legal and Governance</a></li>';
								}
								echo '<li><hr><a href="#" onclick="showWorkbooksKeys();"><i class="fa fa-info-circle"></i> Icons Information</a></li>';
							}

							echo <<<WORKBOOKS
		</ul>
	</div>
</td>
WORKBOOKS;

							if($row['qan'] == Workbook::CS_QAN)
							{
								echo '<td><span class="btn btn-default btn-sm" title="Customer Service Practitioner Self Assessment / Review" onclick="window.location.href=\'do.php?_action=cs_review&id=&tr_id='.$row['id'].'\'">Review</span> </td>';
								echo '<td><span class="btn btn-default btn-sm" title="Customer Service Observation & Performance checklist" onclick="window.location.href=\'do.php?_action=cs_observation&id=&tr_id='.$row['id'].'\'">Observation</span> </td>';
								if(SOURCE_LOCAL || DB_NAME == "am_sd_demo")
									echo '<td><span class="btn btn-default btn-sm" onclick="window.location.href=\'do.php?_action=tr_videos&tr_id='.$row['id'].'\'">Videos</span> </td>';
							}
							if($row['qan'] == Workbook::RETAIL_QAN)
							{
								echo '<td><span class="btn btn-default btn-sm" title="Retailer observation performance checklist" onclick="window.location.href=\'do.php?_action=rt_observation&id=&tr_id='.$row['id'].'\'">Observation</span> </td>';
								echo '<td></td>';
								if(SOURCE_LOCAL || DB_NAME == "am_sd_demo")
									echo '<td><span class="btn btn-default btn-sm" onclick="window.location.href=\'do.php?_action=tr_videos&tr_id='.$row['id'].'\'">Videos</span> </td>';
							}
							echo '</tr>';
						}
					}
					?>
					</tbody>
				</table>
				<p><br><br></p><br><br>
			</div>
		</div>
	</div>

	<div class="col-lg-4 col-md-4 col-sm-12">

		<div class="box box-primary">
			<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-font"></i> Your Signature</h3>
				<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
			</div>
			<div class="box-body">
				<span class="btn btn-info" onclick="getSignature();">
					<img id="img_user_signature" src="do.php?_action=generate_image&<?php echo $user_signature == '' ? 'title=Create your signature&font=Signature_Regular.ttf&size=20' : $user_signature; ?>" style="border: 1px solid;border-radius: 15px;" />
					<input type="hidden" name="user_signature" id="user_signature" value="<?php echo $user_signature; ?>" />
				</span>
			</div>
		</div>

	</div>
</div>


<div id="panel_signature" title="Signature Panel">
	<div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter your name/initials, then select the signature font you like and press "Create". </div>
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
</div>

<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js?n=<?php echo time(); ?>" type="text/javascript"></script>
<script type="text/javascript" src="/assets/adminlte/plugins/fullcalendar/moment.js"></script>
<script type="text/javascript" src="/assets/adminlte/plugins/fullcalendar/fullcalendar.js"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/adminlte/plugins/sparkline/jquery.sparkline.min.js"></script>

<script>
var phpVerifierSignature = '<?php echo $user_signature; ?>';
$(function(){

	if(window.phpVerifierSignature == '')
	{
		$("<div></div>").html('<p><strong>Create your signature</strong></p><p>Your signature is required, please use "Your Signature" panel to create your signature.<br></p>').dialog({
			title: " Important information ",
			resizable: false,
			modal: true,
			width: 'auto',
			maxWidth: 550,
			height: 'auto',
			maxHeight: 500,
			closeOnEscape: false,
			buttons: {
				'OK': function() {
					$(this).dialog('close');
				}
			}
		}).css("background", "#FFF");
	}

	$('#tblLearners').DataTable({
		"paging": true,
		"lengthChange": true,
		"searching": true,
		"ordering": true,
		"info": false,
		"autoWidth": true
	});

	$( "#panel_signature" ).dialog({
		autoOpen: false,
		modal: true,
		draggable: false,
		width: "auto",
		height: 500,
		buttons: {
			'Create': function() {
				if($('#signature_text').val() == '')
				{
					alert('Please input your signature');
					$('#signature_text').focus();
					return;
				}
				if($('.sigboxselected').children('img')[0] === undefined)
				{
					alert('Please select your font');
					return;
				}
				$("#img_user_signature").attr('src', $('.sigboxselected').children('img')[0].src);
				var _link = $('.sigboxselected').children('img')[0].src;
				_link = _link.split('&');
				$("#user_signature").val(_link[1]+'&'+_link[2]+'&'+_link[3]);
				if($('#user_signature').val() == '')
				{
					alert('Please create your signature');
					return;
				}
				if(!confirm('You can only save your signature once, are you sure you want to continue?'))
				{
					$("#img_user_signature").attr('src', 'do.php?_action=generate_image&title=Create your signature&font=Signature_Regular.ttf&size=20');
					$(this).dialog('close');
					return;
				}
				saveSignature();
				$(this).dialog('close');
			},
			'Cancel': function() {$(this).dialog('close');}
		}
	});

	loadDefaultSignatures();

});

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
<?php if($user_signature != '') { ?> return; <?php } ?>

	$( "#panel_signature" ).dialog( "open");
}

function SignatureSelected(sig)
{
	$(".sigboxselected").attr("class", "sigbox");
	sig.className = "sigboxselected";
}

function saveSignature()
{
	$.ajax({
		type:'POST',
		url:'do.php?_action=save_user_signature&from_page=assessor_home_page&id=<?php echo $_SESSION['user']->id; ?>&user_signature='+encodeURIComponent($('#user_signature').val()),
		success: function(data, textStatus, xhr) {
			window.location.reload();
		},
		error: function(data, textStatus, xhr){
			console.log(data.responseText);
		}
	});
}

function showWorkbooksKeys()
{
	var html = '<table class="table row-border">';
	html += '<tr><td><i class="fa fa-flag-o"></i></td><td>Not Started by Learner</td></tr>';
	html += '<tr><td><i class="fa fa-refresh"></i></td><td>In Progress by Learner</td></tr>';
	html += '<tr><td><i class="fa fa-send-o"></i></td><td>Submitted by Learner and awaiting Assessor feedback</td></tr>';
	html += '<tr><td><i class="fa fa-hourglass-o"></i></td><td>Being marked by Assessor</td></tr>';
	html += '<tr><td><i class="fa fa-warning"></i></td><td>Marked and Not Accepted by Assessor</td></tr>';
	html += '<tr><td><i class="fa fa-thumbs-o-up"></i></td><td>Marked and Signed Off by Assessor</td></tr>';
	html += '<tr><td><i class="fa fa-check"></i></td><td>Accepted by IV</td></tr>';
	html += '<tr><td><i class="fa fa-close"></i></td><td>Not Accepted by IV</td></tr>';
	html += '</table>';
	$("<div></div>").html(html).dialog({
		title: " Key ",
		resizable: false,
		modal: true,
		width: 'auto',
		maxWidth: 550,
		height: 'auto',
		maxHeight: 500,
		closeOnEscape: false,
		buttons: {
			'OK': function() {
				$(this).dialog('close');
			}
		}
	}).css("background", "#FFF");
}
</script>
</body>
</html>