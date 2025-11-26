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
		function showComments(element)
		{
			element = '#'+element;
			$(element).fadeToggle();
		}
		function select_element_pcs(element)
		{
			var ele_id = element.id.split('_');
			var rg = 'map_'+ele_id[2]+'_';

			if($(element).is(":checked"))
				$('[id^="'+rg+'"]').attr('checked','checked');
			else
				$('[id^="'+rg+'"]').removeAttr('checked');
		}
		function signoff_element_pcs(element)
		{
			var ele_id = element.id.split('_');
			var rg = 'signoff_'+ele_id[2]+'_';

			if($(element).is(":checked"))
				$('[id^="'+rg+'"]').attr('checked','checked');
			else
				$('[id^="'+rg+'"]').removeAttr('checked');
		}
		function save_pcs_evidence_mapping()
		{
			var chkBoxArray = [];
			$('.mapped_chkbox:checked').each(function() {
				chkBoxArray.push($(this).attr('id'));
			});
			var postData = 'selected_pcs=' + encodeURIComponent(chkBoxArray)
				+ '&tr_id=' + encodeURIComponent(<?php echo $training_record->id; ?>)
				+ '&evidence_id=' + encodeURIComponent(<?php echo $evidence_id; ?>);


			$.ajax({
				type:"POST",
				url: "do.php?_action=ajax_save_pcs_evidence_mapping",
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
		function signoff_pcs_evidence_mapping()
		{
			var chkBoxArray = [];
			$('.signoff_chkbox:checked').each(function() {
				chkBoxArray.push($(this).attr('id'));
			});

			var postData = 'selected_pcs=' + encodeURIComponent(chkBoxArray)
				+ '&tr_id=' + encodeURIComponent(<?php echo $training_record->id; ?>)
				+ '&evidence_id=' + encodeURIComponent(<?php echo $evidence_id; ?>);


			$.ajax({
				type:"POST",
				url: "do.php?_action=ajax_signoff_pcs_evidence_mapping",
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
		function save_evidence_feedback()
		{
			if($('#txt_feedback').val() == '')
			{
				alert('Please enter feedback notes.');
				return;
			}
			var postData = 'feedback=' + encodeURIComponent($('#txt_feedback').val())
				+ '&tr_id=' + encodeURIComponent(<?php echo $training_record->id; ?>)
				+ '&evidence_id=' + encodeURIComponent(<?php echo $evidence_id; ?>);


			$.ajax({
				type:"POST",
				url: "do.php?_action=ajax_save_evidence_feedback",
				data: postData,
				async:true,
				success:function (response) {
					alert('Feedback Saved.');
					window.location.reload();
				},
				error:function (client) {
					alert(client.responseText);
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
	<div class="Title">Map Evidence to PC's</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=User::TYPE_SYSTEM_VIEWER){?><button onclick="save_pcs_evidence_mapping();">Save</button><?php }?>
		<?php if($_SESSION['user']->type!=User::TYPE_SYSTEM_VIEWER){?><button onclick="signoff_pcs_evidence_mapping();">Sign off</button><?php }?>
		<button onclick="<?php echo $js_cancel; ?>">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<p><strong><?php echo $training_record->firstnames . ' ' . $training_record->surname; ?> - <?php echo $internal_title; ?></strong></p>
<hr>
<p>
	<div>
	<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
		<col width="190" />
		<?php
			$evidence_details = DAO::getResultset($link, "SELECT * FROM tr_qual_portfolio_evidences WHERE id = " . $evidence_id . " LIMIT 0,1 ", DAO::FETCH_ASSOC);
			$evidence_details = $evidence_details[0];
			$evidence_tooltip = "Evidence: " . $evidence_details['evidence_name'];
			$evidence_tooltip .= "<br>Evidence Type: " . DAO::getSingleValue($link, "SELECT type FROM lookup_evidence_type WHERE id = " . $evidence_details['evidence_type']);
			$evidence_tooltip .= "<br>Evidence Description: " . $evidence_details['evidence_description'];
			$evidence_tooltip .= "<br>Evidence Size: " . Repository::formatFileSize($evidence_details['evidence_size']);
			$evidence_tooltip .= "<br>DateTime Uploaded: " . Date::to($evidence_details['date_uploaded'], 'd/m/Y H:i:s');
			$evidence_tooltip .= "<br>Uploaded By: " . DAO::getSingleValue($link, "SELECT CONCAT(users.firstnames, ' ', users.surname, ' (', lookup_user_types.description, ')') FROM users, lookup_user_types WHERE username = '" . $evidence_details['uploaded_by'] . "' AND users.type = lookup_user_types.id");
			$download_link = "do.php?_action=downloader&path=" . DB_NAME . "/" . $training_record->username . "/portfolio/&f=" . $evidence_details['evidence_name'];
		?>
		<tr>
			<td>Evidence:</td>
			<td><a class="tooltip" title="<?php echo $evidence_tooltip; ?>" href="<?php echo $download_link; ?>"><?php echo $evidence_details['evidence_name']; ?></a></td>
		</tr>
		<tr>
			<td valign="top">Assessor Feedback:</td>
			<td><?php echo $evidence_details['feedback']; ?></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<textarea id="txt_feedback" name="txt_feedback" style="font-family:sans-serif; font-size:10pt" name="description" rows="5" cols="50" ></textarea>
			</td>
		</tr>
		<tr>
			<td></td>
			<td><span class="button" onclick="save_evidence_feedback();">Save</span></td>
		</tr>
	</table>

	</div>
</p>
<p>
<div align="center" style="height:10px;">
	<span id="lblAjaxErrorMessage" style="display: none;"> Something went wrong, Please try again or raise the support request.</span>
	<span id="lblAjxSuccessMessage" style="display: none;"> Your data saved successfully. </span>
</div>
</p>

<p>
	<div align="left" style="height:10px;">
		<?php echo $this->generatePCSTable($link, $evidence_id, $tr_id, $qualification_id); ?>
	</div>
</p>

</body>
</html>