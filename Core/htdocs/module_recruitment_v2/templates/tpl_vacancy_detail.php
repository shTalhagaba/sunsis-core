<?php /* @var $vacancy RecVacancy */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Perspective - Sunesis</title>
	<link rel="stylesheet" type="text/css" href="../../common.css"/>
	<link href="css/jquery.steps.css" rel="stylesheet">
	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="../module_recruitment_v2/css/application.css?n=<?php echo time(); ?>"/>

	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="jquery-ui/js/jquery-ui.js"></script>
	<script src="js/jquery.steps.js"></script>
	<script src="js/form-validation/jquery.validate.min.js"></script>

	<script src="/common.js" type="text/javascript"></script>
	<script src="../module_recruitment_v2/js/search_vacancies.js?n=<?php echo time(); ?>"></script>

	<style type="text/css">

		.searchPanel {
			width: 30%;
			float: left;
			min-width: 400px;
			border: 3px solid #B5B8C8;
			border-radius: 15px;
		}

		.resultPanel {
			margin-left: 35%;
			border: 3px solid #B5B8C8;
			border-radius: 15px;
		}

		#messageBox {
			position: relative;
			top: 35%;
			left: 35%;
			margin-top: -50px;
			margin-left: -50px;
		}

		.searchPanelButton {
			height: 30px;
			width: 100px;
			padding-top: 5px;
			font-weight: bold;
			font-size: 1.5em;
			text-align: center;
		}

	</style>

</head>
<?php
$logo = SystemConfig::getEntityValue($link, 'logo');
if($logo == '')
	$logo = 'SUNlogo.jpg';
?>
<body>
<div id="wrapper">
	<div id="headerwrap">
		<div id="header">
			<div id="logo"><img src="/images/logos/<?php echo $logo; ?>" height="50" /></div>
		</div>
	</div>

	<div id="contentwrap">
		<div id="content">
			<div class="searchPanel">
				<form id="frmSearchVacancies" name="frmSearchVacancies" action="/do.php?_action=search_vacancies" method="post" autocomplete="off">
					<table>
						<tr>
							<td><label for="sector">Sector:</label></td>
							<?php echo '<td>' . HTML::select('sector', $type_ddl, $sector, true, false, true, 1, ' style="min-width:270px;" ') . '</td>'; ?>
						</tr>
						<tr>
							<td><label for="region">Region:</label></td>
							<?php echo '<td>' . HTML::select('region', $region_ddl, $region, true) . '</td>'; ?>
						</tr>
						<tr>
							<td><label for="keywords">Keywords:</label></td>
							<?php echo '<td><input type="text" name="keywords" id="keywords" value="' . $keywords . '" /></td>'; ?>
						</tr>
						<tr>
							<td colspan="2" align="right"><span style="width: 95%; padding-top: 5px; padding-bottom: 5px; font-size: 1.5em;" class="recButton" onclick="searchVacancies();">Search &raquo;</span></td>
						</tr>
						<?php if(DB_NAME == "am_superdrug"){?>
						<tr>
							<td colspan="2"><span style="width: 95%; padding-top: 5px; padding-bottom: 5px; font-size: 1.2em; background-color: #FF69B4" class="recButton" onclick="window.open('https://www.superdrug.jobs/see-all-vacancies.html','_self');">Not interested in apprenticeships? Click here</span></td>
						</tr>
						<?php } ?>
					</table>
				</form>
			</div>

			<div class="resultPanel">
					<br>
					<div class="panel">
						<div class="panel-body" >
							<table>
								<td rowspan="6" valign="top" ><span onclick="applyForVacancy('<?php echo $vacancy->id; ?>');" class="recButton searchPanelButton">Apply</span></td>
								<td valign="top" style="font-weight: 800;">Title:</td><td><?php echo $vacancy->vacancy_title; ?></td></tr>
								<tr><td valign="top" style="font-weight: 800;">Reference:</td><td><?php echo $vacancy->vacancy_reference; ?></td></tr>
								<!--<tr><td valign="top" style="font-weight: 800;">Sector:</td><td><?php /*echo $vacancy->getSectorDescription($link); */?></td></tr>-->
								<tr><td valign="top" style="font-weight: 800;">Closing Date:</td><td><?php echo Date::toShort($vacancy->closing_date); ?></td></tr>
								<tr><td valign="top" style="font-weight: 800;">Location:</td><td><?php echo $vacancy->getLocation($link); ?></td></tr>
								<?php
								$wage_info = "";
								if($vacancy->wage != '' && !is_null($vacancy->wage))
									$wage_info = $vacancy->wage;
								if($vacancy->wage_type == 'Weekly')
									$wage_info = $vacancy->wage . ' per week';
								else
									$wage_info = $vacancy->wage . ' ' . $vacancy->wage_text;
								?>
								<tr><td valign="top" style="font-weight: 800;">Salary:</td><td>£<?php echo $wage_info; ?></td></tr>
							</table>
						</div>
					</div>
					<div>
						<table cellpadding="1">
							<tr><td class="fieldLabel">Full Description:</td></tr>
							<tr><td><?php echo ($vacancy->full_description); ?></td></tr>
							<?php if(!is_null($vacancy->personal_qualities)) {?>
							<tr><td class="fieldLabel">Person Specification:</td></tr>
							<tr><td><?php echo ($vacancy->personal_qualities); ?></td></tr>
							<?php } ?>
							<?php if(!is_null($vacancy->qualifications_required)) {?>
							<tr><td class="fieldLabel">Qualifications Required:</td></tr>
							<tr><td><?php echo ($vacancy->qualifications_required); ?></td></tr>
							<?php } ?>
							<?php if(!is_null($vacancy->other_info)) {?>
							<tr><td class="fieldLabel">Further Information:</td></tr>
							<tr><td><?php echo ($vacancy->other_info); ?></td></tr>
							<?php } ?>
						</table>
					</div>
			</div>

		</div>
	</div>
	<div id="footerwrap">
		<div id="footer">
		<span
			style="float: left; text-align: left; margin-left: 10px;"><?php echo date('D, d M Y'); ?></span>
		<span
			style="float: right; text-align: right; margin-right: 5px;">Powered by Sunesis &nbsp;|&nbsp;&copy; <?php echo date('Y'); ?>
			Perspective Ltd</span>
		</div>
	</div>
</div>

<div id="dialog_unique_id" style="display:none" title="Information"></div>

<div id="dialog_unique_id_sent" style="display:none" title="Information"></div>

</body>
</html>