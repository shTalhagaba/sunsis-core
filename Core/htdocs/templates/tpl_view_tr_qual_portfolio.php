<?php /* @var $training_record TrainingRecord */ ?>
<?php /* @var $framework Framework */ ?>
<?php /* @var $course Course */ ?>
<?php /* @var $assessor User */ ?>
<?php /* @var $student_qualification StudentQualification */ ?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Portfolio</title>
	<link rel="stylesheet" href="/common.css" type="text/css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<!--<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>-->
	<script language="JavaScript" src="/common.js"></script>
	<link rel="stylesheet" type="text/css" href="/css/tooltipster.css" />
	<script type="text/javascript" src="js/jquery.tooltipster.min.js"></script>
	<script>
		$(document).ready(function() {
			$('.tooltip').tooltipster({
				contentAsHTML: true,
				animation: 'fade',
				delay: 200
			});
		});
	</script>
	<script language="JavaScript">
		function open_evidence_repository() {}

		function entry_onclick(radio) {}
	</script>

	<style type="text/css">
		.PercentageBar {
			background-color: #d3d3d3;
			position: relative;
			font-size: small;
			width: 100%;
			margin: 1px;
		}

		.PercentageBar DIV {
			height: 20px;
			line-height: 20px;
		}

		.PercentageBar .percent {
			position: absolute;
			background-color: #37549A;
			left: 0px;
			z-index: 0;
		}

		.PercentageBar .caption {
			position: relative;
			text-align: center;
			color: #000;
			z-index: 1;
		}

		/*
		.SmallPercentageBar {background-color: #d3d3d3; position: relative; font-size: small; width: 100%; margin: 1px;}
		.SmallPercentageBar DIV {height: 10px; line-height: 10px;}
		.SmallPercentageBar .percent {position: absolute; background-color: Green; left: 0px; z-index: 0;}
		.SmallPercentageBar .caption {position: relative; text-align: center; color: #000; z-index: 1;}
*/

		.PercentageBarSignedOff {
			background-color: #d3d3d3;
			position: relative;
			font-size: small;
			width: 100%;
			margin: 1px;
		}

		.PercentageBarSignedOff DIV {
			height: 20px;
			line-height: 20px;
		}

		.PercentageBarSignedOff .percent {
			position: absolute;
			background-color: #5C7F25;
			left: 0px;
			z-index: 0;
		}

		.PercentageBarSignedOff .caption {
			position: relative;
			text-align: center;
			color: #000;
			z-index: 1;
		}

		.PercentageBarASignedOff {
			background-color: #d3d3d3;
			position: relative;
			font-size: small;
			width: 100%;
			margin: 1px;
		}

		.PercentageBarASignedOff DIV {
			height: 20px;
			line-height: 20px;
		}

		.PercentageBarASignedOff .percent {
			position: absolute;
			background-color: #F6A230;
			left: 0px;
			z-index: 0;
		}

		.PercentageBarASignedOff .caption {
			position: relative;
			text-align: center;
			color: #000;
			z-index: 1;
		}

		.SmallPercentageBarSignedOff {
			background-color: #d3d3d3;
			position: relative;
			font-size: small;
			width: 100%;
			margin: 1px;
		}

		.SmallPercentageBarSignedOff DIV {
			height: 10px;
			line-height: 10px;
		}

		.SmallPercentageBarSignedOff .percent {
			position: absolute;
			background-color: #5C7F25;
			left: 0px;
			z-index: 0;
		}

		.SmallPercentageBarSignedOff .caption {
			position: relative;
			text-align: center;
			color: #000;
			z-index: 1;
		}

		.SmallPercentageBarASignedOff {
			background-color: #d3d3d3;
			position: relative;
			font-size: small;
			width: 100%;
			margin: 1px;
		}

		.SmallPercentageBarASignedOff DIV {
			height: 10px;
			line-height: 10px;
		}

		.SmallPercentageBarASignedOff .percent {
			position: absolute;
			background-color: #F6A230;
			left: 0px;
			z-index: 0;
		}

		.SmallPercentageBarASignedOff .caption {
			position: relative;
			text-align: center;
			color: #000;
			z-index: 1;
		}

		div.main {
			width: 50%;
			height: 50%;
			float: left;
		}

		div.block {
			text-align: center;
			border-width: 1px;
			border-style: solid;
			border-color: #E3E3E3 #BFBFBF #BFBFBF #E3E3E3;
			padding: 8px !important;
			margin-bottom: 1.5em;
			word-wrap: break-word;
			width: 95% !important;
			zoom: 1;
			-moz-border-radius: 7px;
			-webkit-border-radius: 7px;
			border-radius: 7px;
			-moz-box-shadow: 3px 3px 5px rgba(127, 108, 56, 0.4);
			-webkit-box-shadow: 3px 3px 5px rgba(127, 108, 56, 0.4);
			box-shadow: 3px 3px 5px rgba(127, 108, 56, 0.4);
			background: rgb(255, 255, 255);
			/* Old browsers */
			background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmZmZmZiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmNmY2ZjYiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
			background: -moz-linear-gradient(top, rgba(255, 255, 255, 1) 0%, rgba(246, 246, 246, 1) 100%);
			/* FF3.6+ */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, rgba(255, 255, 255, 1)), color-stop(100%, rgba(246, 246, 246, 1)));
			/* Chrome,Safari4+ */
			background: -webkit-linear-gradient(top, rgba(255, 255, 255, 1) 0%, rgba(246, 246, 246, 1) 100%);
			/* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(top, rgba(255, 255, 255, 1) 0%, rgba(246, 246, 246, 1) 100%);
			/* Opera 11.10+ */
			background: -ms-linear-gradient(top, rgba(255, 255, 255, 1) 0%, rgba(246, 246, 246, 1) 100%);
			/* IE10+ */
			background: linear-gradient(top, rgba(255, 255, 255, 1) 0%, rgba(246, 246, 246, 1) 100%);
			/* W3C */
			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#f6f6f6', GradientType=0);
			/* IE6-8 */
		}
	</style>
</head>

<body>
	<div class="banner">
		<div class="Title">Portfolio</div>
		<div class="ButtonBar">
			<button onclick="<?php echo $js_cancel; ?>">Cancel</button>
		</div>
		<div class="ActionIconBar">

		</div>
	</div>

	<?php $_SESSION['bc']->render($link); ?>

	<h3>Record of Achievement - <?php echo $training_record->firstnames . ' ' . $training_record->surname; ?></h3>
	<p>
		<span class="button"
			onclick="window.location.href='do.php?_action=tr_qual_evidence_repo&tr_id=<?php echo $training_record->id; ?>&qualification_id=<?php echo $student_qualification->id; ?>&framework_id=<?php echo $framework->id; ?>&internaltitle=<?php echo $student_qualification->internaltitle; ?>';">Evidence
			Repository</span>
	</p>
	<p>
	<div align="left">
		<table class="resultset" border="0" cellspacing="0" cellpadding="6">
			<tr>
				<th>QAN</th>
				<th>Qualification Title</th>
				<th>Start Date</th>
				<th>Assessor</th>
				<th>Assessor's email</th>
				<th>Assessor's Telephone</th>
				<th>Awarding Body</th>
				<th>Registration Number</th>
				<th>Planned End Date</th>
				<th>Actual End Date</th>
			</tr>
			<tr>
				<td><?php echo $student_qualification->id; ?></td>
				<td><?php echo $student_qualification->internaltitle; ?></td>
				<td><?php echo Date::toShort($training_record->start_date); ?></td>
				<?php if ($assessor): ?>
					<td><?php echo $assessor->firstnames . ' ' . $assessor->surname; ?></td>
					<td><?php echo $assessor->work_email; ?></td>
					<td><?php echo $assessor->work_telephone; ?></td>
				<?php endif; ?>
				<td><?php echo $student_qualification->awarding_body; ?></td>
				<td><?php echo $student_qualification->awarding_body_reg; ?></td>
				<td><?php echo Date::toShort($student_qualification->end_date); ?></td>
				<td><?php echo $student_qualification->actual_end_date; ?></td>
			</tr>
		</table>
	</div>
	</p>
	<p>
	<div class="block" align="center">
		<table width="100%">
			<caption><strong>Signed off Progress <?php echo $total_sign_off_percentage . '%'; ?> / Awaiting Sign Off
					Progress <?php echo $total_awaiting_sign_off_percentage . "%"; ?></strong></caption>
			<tr style='width:100%; '>
				<td
					style="width: 10%; border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;">
					Signed off (<?php echo $total_sign_off_percentage . '%'; ?>)</td>
				<td style="padding-left: 5px; padding-right: 5px; ">
					<div class="PercentageBarSignedOff" style=" border-radius:25px;">
						<div class="percent"
							style="width: <?php echo $total_sign_off_percentage . '%'; ?>; border-radius:25px;">&nbsp;
						</div>
						<div class="caption"></div>
					</div>
				</td>
			</tr>
		</table>
		<table width="100%">
			<caption><strong>Awaiting Sign Off Progress <?php echo $total_awaiting_sign_off_percentage . "%"; ?></strong>
			</caption>
			<tr style='width:100%; '>
				<td
					style="width: 10%; border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;">
					Awaiting Sign off (<?php echo $total_awaiting_sign_off_percentage . "%"; ?>)</td>
				<td style="padding-left: 5px; padding-right: 5px; ">
					<div class="PercentageBarASignedOff" style=" border-radius:25px;">
						<div class="percent"
							style="width: <?php echo $total_awaiting_sign_off_percentage . "%"; ?>; border-radius:25px;">
							&nbsp;</div>
						<div class="caption"></div>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<div class="block" align="center">
		<table width="100%">
			<caption><strong>Months Elapsed <?php echo sprintf("%.2f", $months_passed_float); ?> <br> Course Duration
					<?php echo Date::toShort($training_record->start_date); ?> to
					<?php echo Date::toShort($training_record->target_date); ?></strong></caption>
			<tr style='width:100%; '>
				<td
					style="width: 10%; border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;">
					Month Elapsed (<?php echo sprintf("%.2f", $months_passed_float); ?>)</td>
				<td style="padding-left: 5px; padding-right: 5px; ">
					<div class="PercentageBar" style=" border-radius:25px;">
						<div class="percent"
							style="width: <?php echo ($months_passed / $months_in_course * 100); ?>%; border-radius:25px;">
							&nbsp;</div>
						<div class="caption"></div>
					</div>
				</td>
				<td
					style="width: 10%; border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;">
					Remaining (<?php echo $months_in_course; ?>)</td>
			</tr>
		</table>
		<!--//
		<tr style='width:100%'>
			<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;"> Months Elapsed (<?php /*echo sprintf("%.2f",$months_passed_float); */ ?>) </td>
			<td style="padding-left: 5px; padding-right: 5px" align=left width="430px"> <div style='background-color:RoyalBlue; height: 20px; line-height: 1px; font-size: 1px; width:<?php /*echo ($months_passed/$months_in_course*100);*/ ?>%; border-radius:25px;' />
				<p style='position:relative; left:150px'> <font face=arial size='-2'>&nbsp;</font></p></td> <td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;"> <?php /*echo sprintf("%.1f",($months_passed/$months_in_course*100));*/ ?>% </td> </tr>

		//-->
	</div>
	</p>
	<p>
	<div align="center">
		<?php echo $this->buildUnitProgressTable($link, $student_qualification->id, $training_record->id); ?>
	</div>
	</p>
</body>

</html>