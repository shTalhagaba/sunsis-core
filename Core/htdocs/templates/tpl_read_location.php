<?php /* @var $vo OrganisationVO */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Location</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>

	<script language="JavaScript">
		// jQuery initialisation
		$(function(){

			$('div.Directory tr td:first-child').hover(function(){
				$(this).css("background-color","#dfe9cd");
			} , function(){
				$(this).css("background-color","");
			});

			$('select[name="section"]').change(function(e){
				window.location.replace("do.php?_action=read_location&id="+encodeURIComponent(<?php echo $id; ?>)+"&section=" + encodeURIComponent($(this).val()));
			});

			$('div.Directory table tr:even:has(td)').css('background-color', '#f0f0f0');
			$('div.Directory table tr:even:has(th)').css('background-color', '#cccccc');

			$('#dialogDeleteFile').dialog({
				modal: true,
				width: 450,
				closeOnEscape: true,
				autoOpen: false,
				resizable: false,
				draggable: false,
				buttons: {
					'Delete': function() {
						$(this).dialog('close');
						var client = ajaxRequest('do.php?_action=delete_file&f=' + encodeURIComponent($(this).data('filepath')));
						if(client){
							window.location.replace("do.php?_action=read_location&id="+encodeURIComponent(<?php echo $id; ?>)+"&section=" + encodeURIComponent($('select#section').val()));
						}
					},
					'Cancel': function() {$(this).dialog('close');}
				}
			});

			$('#dialogDeleteSection').dialog({
				modal: true,
				width: 450,
				closeOnEscape: true,
				autoOpen: false,
				resizable: false,
				draggable: false,
				buttons: {
					'Delete': function() {
						$(this).dialog('close');
						var client = ajaxRequest('do.php?_action=read_location&id='+encodeURIComponent(<?php echo $id; ?>)+'&subaction=deletesection&section=' + encodeURIComponent($('select#section').val()));
						if(client){
							window.location.replace("do.php?_action=read_location&id="+encodeURIComponent(<?php echo $id; ?>)+"&section=");
						}
					},
					'Cancel': function() {$(this).dialog('close');}
				}
			});

			$('#dialogCreateSection').dialog({
				modal: true,
				width: 450,
				closeOnEscape: true,
				autoOpen: false,
				resizable: false,
				draggable: false,
				buttons: {
					'Create': function() {
						var title = $('input[name="title"]', this).val();
						// Validate the title
						title = jQuery.trim(title);
						if(title == "" || title == "null" || title == null || title.toLowerCase() == "general"){
							return;
						}
						if(title.length > 25){
							alert("Section titles must be 25 characters or fewer in length");
							return;
						}
						if(title.match(/[^A-Za-z0-9 \-_]/)){
							alert("Illegal characters in title. Please use letters, numbers, spaces, underscores and hyphens only.");
							return;
						}
						$(this).dialog('close');
						// Check if a section exists with the same title
						var sections = document.getElementById('sections');
						var $options = $('select#sections option');
						for(var i = 0; i < $options.length; i++)
						{
							if($options[i].value.toLowerCase() == text.toLowerCase())
							{
								sections.selectedIndex = i;
								return;
							}
						};
						// Call the server
						var client = ajaxRequest('do.php?_action=read_location&id='+encodeURIComponent(<?php echo $id; ?>)+'&subaction=createsection&section=' + encodeURIComponent(title));
						if(client){
							window.location.replace("do.php?_action=read_location&id="+encodeURIComponent(<?php echo $id; ?>)+"&section=" + encodeURIComponent(title));
						}
					},
					'Cancel': function() {$(this).dialog('close');}
				}
			});
		});


	function deleteRecord()
	{
		if(window.confirm("Delete this location?"))
		{
			window.location.replace('do.php?_action=delete_location&id=<?php echo $vo->id; ?>');
		}
	}

	function uploadFile() {
		var myForm = document.forms[0];
		myForm.submit();
	}

		function createSection()
		{
			var $dialog = $('#dialogCreateSection');
			$('input[name="title"]', $dialog).val("");

			$dialog.dialog("open");
		}

		function deleteSection()
		{
			var $section = $('select#section');
			if($section.val() == ''){
				return;
			}

			var $dialog = $('#dialogDeleteSection');

			// Change the message
			$dialog.html("<p>Delete <b>" + $section.val() + "</b>.</p><p>Deletion is permanent and irrecoverable.  Continue?</p>");

			$dialog.dialog("open");
		}

		function downloadFile(path)
		{
			window.location.href="do.php?_action=downloader&f=" + encodeURIComponent(path);
		}

		function deleteFile(path)
		{
			var $dialog = $('#dialogDeleteFile');

			// Set the filepath to delete
			$dialog.data('filepath', path);

			// Change the message
			var filename = path.split('/').pop();
			$dialog.html("<p>Delete <b>" + filename + "</b>.</p><p>Deletion is permanent and irrecoverable.  Continue?</p>");

			$dialog.dialog("open");
		}
	</script>
</head>

<body>
<div class="banner">
	<div class="Title"><?php echo ($vo->is_legal_address == 1) ? 'Main Site / Legal Address' : 'Organisation Site' ?></div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		<?php
			// re: added manager access for REED 25/06/2012 ( applied globally )
			// ---
			if( $_SESSION['user']->isAdmin() || $_SESSION['user']->type == 8 || $_SESSION['user']->type == 7 || $_SESSION['user']->type == 12 || (DB_NAME=='am_mcq' && $_SESSION['user']->type==4)) {
		?>
		<button	onclick="window.location.replace('do.php?id=<?php echo $vo->id; ?>&_action=edit_location&organisations_id=<?php echo $vo->organisations_id; ?>');">Edit</button>
 		<button onclick="window.location.replace('do.php?id=<?php echo $vo->id; ?>&_action=edit_health_and_safety&back=read_employer');">Health & Safety</button> 
		<?php } ?>
		<?php if($_SESSION['user']->isAdmin() && $isSafeToDelete){ ?>
			<button onclick="deleteRecord();">Delete</button>
		<?php } ?>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3> Primary Contact </h3>

<table border="0" cellspacing="4">
	<tr>
		<td class="fieldLabel_compulsory">Contact name:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->contact_name ?: ''); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Mobile phone:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->contact_mobile ?: ''); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Telephone:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->contact_telephone ?: ''); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Email:</td>
		<?php echo '<td> <a href="mailto:' . htmlspecialchars((string)$vo->contact_email ?: '') . '">' . htmlspecialchars((string)$vo->contact_email ?: '') . '</a></td>'; ?>
	</tr>
</table>

<h3> Contact Details </h3>

<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
	<tr>
		<td class="fieldLabel">Organisation:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$o_vo->legal_name ?: ''); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Full name:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->full_name ?: ''); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Abbreviation:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->short_name ?: ''); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel" valign="top">Address:</td>
		<td class="fieldValue"><?php echo $bs7666->formatRead(); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Telephone:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->telephone ?: ''); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Fax:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->fax ?: ''); ?></td>
	</tr>
	<?php if(DB_NAME=="am_superdrug"){?>
	<tr>
		<td class="fieldLabel">Store No.:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->lsc_number ?: ''); ?></td>
	</tr>
	<?php } ?>
</table>

<h3>Related Records</h3>
<?php $this->_renderRelatedRecordsSummary($link, $vo); ?>
<h3>File Repository</h3>
<div>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=save_employer_repository" ENCTYPE="multipart/form-data">
		<input type="hidden" name="_action" value="save_employer_repository" />
		<input type="hidden" name="emp_id" value="<?php echo $emp_id;?>" />
		<input type="hidden" name="loc_id" value="<?php echo $id;?>" />

		<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
			<col width="150" />
			<tr>
				<td class="fieldLabel_compulsory">Section:</td>
				<td><?php
					echo HTML::select("section", $section_options, $section); if($_SESSION['user']->type != 19){
						?> <a onclick="createSection();return false;" style="margin-left:10px">New</a> |
						<?php if($_SESSION['user']->isAdmin() && $this->isSectionEmpty($section, $emp_id, $id)){ ?>
							<a onclick="deleteSection();return false;">Delete</a>
							<?php } else { ?>
							<span style="color:silver">Delete</span>
							<?php }
					}?>
				</td>
			</tr>
			<tr>
				<td class="fieldLabel_compulsory">File to upload:</td>
				<?php
				?>
				<td><input class="compulsory" type="file" name="uploaded_employer_file" />&nbsp;
					<span id="uploadFileButton" class="button" onclick="uploadFile();">&nbsp;Upload&nbsp;</span>
				</td>
			</tr>
		</table>
	</form>
</div>
<?php echo $html2;?>
<div id="dialogDeleteFile" style="display:none" title="Delete file"></div>

<div id="dialogDeleteSection" style="display:none" title="Delete section"></div>

<div id="dialogCreateSection" style="display:none" title="Create section">
	<p>Enter a title for the new section. Section titles can be up to 25 characters in length, and may contain letter
		numbers, spaces, hyphens and underscores.</p>
	<p><input type="text" name="title" value="" size="25" maxlength="25" /></p>
</div>

</body>
</html>