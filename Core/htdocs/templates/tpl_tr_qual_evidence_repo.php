<?php /* @var $training_record TrainingRecord */ ?>
<?php /* @var $framework Framework */ ?>
<?php /* @var $course Course */ ?>
<?php /* @var $assessor User */ ?>
<?php /* @var $student_qualification StudentQualification */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Qualification Evidence Repository</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
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
		function uploadFile()
		{
			var myForm = document.forms['uploadFileForm'];
			myForm.submit();
		}

		function downloadFile(evidence_name)
		{
			var tr_username = '<?php echo $training_record->username; ?>';
			var client_name = '<?php echo DB_NAME; ?>';
			var download_link = "do.php?_action=downloader&path=" + client_name + "/" + tr_username + "/portfolio/&f=" + evidence_name;
			window.location.href = download_link;
		}

		function CheckUnCheckEvidenceModule(chkBox)
		{
//			alert($(chkBox).is(":checked"));
			$("#lblAjxSuccessMessage").hide();
			$("#lblAjaxErrorMessage").hide();
			var res = chkBox.id.split("_");
			var checked = "";
			if($(chkBox).is(":checked"))
				checked = 1;
			else
				checked = 0;

			var postData = 'evidence_id=' + encodeURIComponent(res[0])
				+ '&unit_id=' + encodeURIComponent(res[1])
				+ '&tr_id=' + encodeURIComponent(<?php echo $training_record->id; ?>)
				+ '&checked=' + checked;

			$.ajax({
				type:"POST",
				url: "do.php?_action=ajax_save_unit_evidence_mapping",
				data: postData,
				async:true,
				success:function (response) {
					$("#lblAjxSuccessMessage").show();
				},
				error:function (client) {
					alert(client.responseText);
					$("#lblAjaxErrorMessage").show();
				}
			});
		}

	</script>

	<style type="text/css">

		.PercentageBar {background-color: Red; position: relative; font-size: small; width: 100%; margin: 1px;}
		.PercentageBar DIV {height: 20px; line-height: 20px;}
		.PercentageBar .percent {position: absolute; background-color: LightGreen; left: 0px; z-index: 0;}
		.PercentageBar .caption {position: relative; text-align: center; color: #000; z-index: 1;}

		div.main { width: 50%; height: 50%; float: left; }
		div.block
		{
			text-align: center;
			border-width: 1px;
			border-style: solid;
			border-color: #E3E3E3 #BFBFBF #BFBFBF #E3E3E3;
			padding: 8px!important;
			margin-bottom: 1.5em;
			word-wrap: break-word;
			width: 50%!important;
			zoom: 1;
			-moz-border-radius: 7px;
			-webkit-border-radius: 7px;
			border-radius: 7px;
			-moz-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			-webkit-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			background: rgb(255,255,255); /* Old browsers */
			background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmZmZmZiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmNmY2ZjYiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
			background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(246,246,246,1) 100%); /* FF3.6+ */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,1)), color-stop(100%,rgba(246,246,246,1))); /* Chrome,Safari4+ */
			background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* Opera 11.10+ */
			background: -ms-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* IE10+ */
			background: linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* W3C */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f6f6f6',GradientType=0 ); /* IE6-8 */
		}
	</style>
</head>
<body>
<div class="banner">
	<div class="Title">Qualification Evidence Repository</div>
	<div class="ButtonBar">
		<button onclick="<?php echo $js_cancel; ?>">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<p><strong><?php echo $training_record->firstnames . ' ' . $training_record->surname; ?> - <?php echo $student_qualification->internaltitle; ?></strong></p>
<hr>
<p>
<div>
	<p>Select and upload evidence from your computer. This file will be added into your Evidence Repository, which can then be referenced to qualification units</p>
	<form name="uploadFileForm" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=save_file_evidence_repo" ENCTYPE="multipart/form-data">
		<input type="hidden" name="_action" value="save_file_evidence_repo" />
		<input type="hidden" name="username" value = "<?php echo $training_record->username;?>"/>
		<input type="hidden" name="qualification_id" value = "<?php echo $student_qualification->id;?>"/>
		<input type="hidden" name="internaltitle" value = "<?php echo $student_qualification->internaltitle;?>"/>
		<input type="hidden" name="framework_id" value = "<?php echo $framework->id;?>"/>
		<input type="hidden" name="tr_id" value = "<?php echo $training_record->id;?>"/>

		<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
			<col width="190" />
			<?php if($_SESSION['user']->type != 19 && $_SESSION['user']->type != User::TYPE_ORGANISATION_VIEWER) { ?>
			<tr>
				<td class="fieldLabel_compulsory">Evidence File</td>
				<td class="compulsory"><input class="compulsory" type="file" name="uploadedfile" id="uploadedfile"/><button style="margin-left: 10px" onclick="uploadFile();return false;">Upload</button></td>
			</tr>
			<tr>
				<td class="fieldLabel_compulsory">Evidence Type</td>
				<td class="compulsory"><?php echo HTML::select('evidenceType', $evidence_type_dropdown, null, true, true); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel_compulsory">Evidence Description</td>
				<td class="compulsory"><input type="text" name="evidenceDesc" id="evidenceDesc" value="" size="50" /></td>
			</tr>
			<?php }?>
		</table>
	</form>
</div>
</p>
<p>
	<div align="center" style="height:10px;">
		<span id="lblAjaxErrorMessage" style="display: none;"> Something went wrong, Please try again or raise the support request.</span>
		<span id="lblAjxSuccessMessage" style="display: none;"> Your data saved successfully. </span>
	</div>
</p>
<p>
	<div align="center">
		<?php echo $this->buildUnitEvidencesTable($link, $student_qualification->id, $student_qualification->internaltitle, $training_record); ?>
		<?php /*echo $this->getFileDownloads($training_record, 'portfolio'); */?>
	</div>
</p>
</body>
</html>