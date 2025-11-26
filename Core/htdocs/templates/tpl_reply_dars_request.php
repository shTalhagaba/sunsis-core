<?php /* @var $request DARSRequest */ ?>
<?php /* @var $requester User */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Reply Support Request</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>

	<script language="JavaScript">
		function save()
		{
			var myForm = document.forms["frmReplyDARSRequest"];
			if(validateForm(myForm) == false)
			{
				return false;
			}

			myForm.submit();
		}

		function downloadFile(path)
		{
			window.location.href="do.php?_action=downloader&f=" + encodeURIComponent(path);
		}
	</script>
	<style type="text/css">
		fieldset {
			border: 3px solid #B5B8C8;
			border-radius: 15px;
		}

		legend {
			font-size: 12px;
			color: #15428B;
			font-weight: 900;
		}
	</style>
</head>
<body>
<div class="banner">
	<div class="Title">Reply Support Request</div>
	<div class="ButtonBar">
		<button onclick="save();">Save</button>
		<button onclick="window.location.replace('<?php echo $_SESSION['bc']->getPrevious(); ?>');">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>
<p></p>
<fieldset>
	<legend>Request ID: <?php echo $request->id; ?></legend>
	<table border="0" cellspacing="6" cellpadding="6" style="margin-left:10px">
		<tr>
			<td class="fieldLabel">Raised By:</td>
			<td class="fieldValue"><?php echo $requester->firstnames . ' ' . $requester->surname . ' (' . $requester->org_legal_name . ')'; ?></td>
			<td class="fieldLabel">Type:</td>
			<td class="fieldValue"><?php echo $request->getTypeDescription(); ?></td>
			<td class="fieldLabel">Status:</td>
			<td class="fieldValue"><?php echo $request->getStatusDescription(); ?></td>
			<td class="fieldLabel">Priority:</td>
			<td class="fieldValue"><?php echo $request->getPriorityDescription(); ?></td>
			<td class="fieldLabel">Created:</td>
			<td class="fieldValue"><?php echo Date::to($request->created, Date::DATETIME); ?></td>
			<?php
			$cols = "10";
			if($download_link != '')
			{
				$cols = "12";
			?>
			<td class="fieldLabel">Attachment:</td>
			<td class="fieldValue" style="cursor:pointer;word-wrap:break-word;" title="Download file" align="left" onclick="downloadFile('<?php echo $download_link; ?>');"><img src="/images/download.gif" border="0" /></td>
			<?php } ?>
		</tr>
		<tr>
			<td colspan="<?php echo $cols; ?>" class="fieldValue">
				<?php
				echo '<p><strong>Detail:</strong><br>' . htmlspecialchars((string)$request->details) . '</p>';
				if(!is_null($request->participants))
				{
					echo '<p><strong>Participants:</strong><br>';
					$participants = explode(',', $request->participants);
					foreach($participants AS $participant)
						echo $participant . ' - ' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$participant}'") . '<br>';
					echo '</p>';
				}
				?>
			</td>
		</tr>
	</table>
</fieldset>
<p></p>
<div style="float: left">
	<form name="frmReplyDARSRequest" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<input type="hidden" name="id" value="<?php echo $request->id; ?>" />
		<input type="hidden" name="_action" value="reply_dars_request" />
		<input type="hidden" name="subaction" value="save_reply" />
		<table border="0" cellspacing="8" style="margin-left:10px">
			<tr>
				<td class="fieldLabel_compulsory">Status:</td>
				<td class="compulsory"><?php echo HTML::select('status', $request->getRequestStatusList(), $request->status, false, true); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional" valign="top">Resolution:</td>
				<td class="optional"><textarea name="notes" rows="10" cols="80"></textarea></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional" valign="top">Resolved:</td>
				<td class="optional"><input type="checkbox" name="resolved" <?php echo $request->resolved=='1'?"checked":""; ?> /></td>
			</tr>
		</table>
	</form>
</div>

<div id="vertical_line" style="float: left; border-left:1px solid #808080;height:500px"></div>

<div style="float: left; margin-left: 10px;">
	<h3>Resolution Notes/History</h3>
	<table class="resultset" border="0" cellspacing="0" cellpadding="10" >
		<tr><th>By</th><th>DateTime</th><th>Notes</th></tr>
		<?php
			$notes = DAO::getResultset($link, "SELECT dars_history.created, dars_history.by, dars_history.notes, CONCAT(firstnames, ' ', surname) AS by_name FROM dars_history LEFT JOIN users ON dars_history.by = users.id WHERE dars_id = '{$request->id}'", DAO::FETCH_ASSOC);
			if(count($notes) > 0)
			{
				foreach($notes AS $note)
				{
					echo '<tr>';
					echo '<td>' . $note['by_name'] . '</td>';
					echo '<td>' . Date::to($note['created'], Date::DATETIME) . '</td>';
					echo '<td>' . htmlspecialchars((string)$note['notes']) . '</td>';
					echo '</tr>';
				}
			}
			else
			{
				echo '<tr><td colspan="3">None</td></tr>';
			}
		?>
	</table>
</div>
</body>
</html>