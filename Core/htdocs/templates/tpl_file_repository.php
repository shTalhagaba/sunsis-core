<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Qualifications</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.17.custom.css" type="text/css"/>
<script language="javascript" src="/js/jquery.min.js" type="text/javascript"></script>
<script language="JavaScript" src="/common.js"></script>
<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.17.custom.min.js"></script>



<script language="JavaScript">

	// jQuery initialisation
	$(function(){

		$('div.Directory tr td:first-child').hover(function(){
			$(this).css("background-color","#dfe9cd");
		} , function(){
			$(this).css("background-color","");
		});

		$('select[name="section"]').change(function(e){
			window.location.replace("do.php?_action=file_repository&section=" + encodeURIComponent($(this).val()));
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
						window.location.replace("do.php?_action=file_repository&section=" + encodeURIComponent($('select#section').val()));
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
					var client = ajaxRequest('do.php?_action=file_repository&subaction=deletesection&section=' + encodeURIComponent($('select#section').val()));
					if(client){
						window.location.replace("do.php?_action=file_repository&section=");
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
					var client = ajaxRequest('do.php?_action=file_repository&subaction=createsection&section=' + encodeURIComponent(title));
					if(client){
						window.location.replace("do.php?_action=file_repository&section=" + encodeURIComponent(title));
					}
				},
				'Cancel': function() {$(this).dialog('close');}
			}
		});
	});

	function div_filter_crumbs_onclick(div)
	{
		showHideBlock(div);
		showHideBlock('div_filters');
	}

	function createSection()
	{
		var $dialog = $('#dialogCreateSection');
		$('input[name="title"]', $dialog).val("");

		$dialog.dialog("open");
	}


	function upload(element)
	{
		if($('input[type=file]').val() == ''){
			return;
		}

		$('img#progressSpinner').show();
		var f = document.forms[0];
		f.submit();
	}


	function uploadFile() {
		var myForm = document.forms[0];
		myForm.submit();
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

</script>

<style type="text/css">

	select#section
	{
		min-width: 240px;
	}

	div#jQueryTabs
	{
		margin-left: 10px;
		width: 580px;
	}

	div.Directory
	{
		width: 590px;
		margin-left: 10px;
		margin-top: 25px;
	}

	div.Directory table
	{
		width: 100%;
		font-size: 10pt;
	}

	div.Directory table tr
	{
		height: 2.2em;
	}

	div.Directory a
	{
		color: orange;
	}

	#upload
	{
		left:18px;
		position:relative;
		width:65px;
		cursor: pointer;
		padding-top: 0px;
		padding-right: 0px;
		padding-bottom: 0px;
		padding-left: 0px;
		margin: 0px 0px 0px 0px;
	}

	.UploadIcon
	{
		cursor: pointer;
	}

	#fileUpload
	{
		font-size: 0.9em;
		font-weight:bold;
		padding-top: 0px;
		padding-right: 0px;
		padding-bottom: 0px;
		padding-left: 0px;
		margin: 0px 0px 0px 0px;
	}

	.text
	{
		font-weight: normal;
		font-size: 100%;
		color: #395596;
	}

	td.fieldLabel_compulsory
	{
		font-weight: bold;
		font-size: 10pt;
		color: #294586;
		padding-top: 2px;
		padding-right: 2px;
		padding-bottom: 2px;
		padding-left: 2px;
	}

	#explorerToolbar
	{
		width: 690px;
		height: 50px;
	}

	div.FileMenu
	{
		width: 570px!important;
	}

	.FileMenu a
	{
		line-height: 2em;
		margin:2px;
		float:left;
		height:30px;

		text-align: center;
		text-decoration:none;
		font-family: Arial,sans-serif;
		font-size: 1.1em;
		color: green;
		padding: 1px 5px 1px 5px;

		font-weight: bold;

		/*border: #668FEB;*/
		border: #727375;

		border-width:1px;
		border-style: solid;


		border-top-color: #77A22F;
		border-left-color: #688e29;
		border-right-color: #77A22F;
		border-bottom-color: #516e20;


		-moz-border-top-left-radius: 7px;
		-moz-border-top-right-radius: 7px;
		-webkit-border-top-left-radius: 7px;
		-webkit-border-top-right-radius: 7px;

		border-top-left-radius: 5px;
		border-top-right-radius: 5px;
		border-bottom-right-radius: 5px;
		border-bottom-left-radius: 5px;
	}

	.FileMenu a:hover, .FileMenu a.selected
	{
		background: rgb(228,239,192); /* Old browsers */
		/* IE9 SVG, needs conditional override of 'filter' to 'none' */
		background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2U0ZWZjMCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNhYmJkNzMiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
		background: -moz-linear-gradient(top,  rgba(228,239,192,1) 0%, rgba(171,189,115,1) 100%); /* FF3.6+ */
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(228,239,192,1)), color-stop(100%,rgba(171,189,115,1))); /* Chrome,Safari4+ */
		background: -webkit-linear-gradient(top,  rgba(228,239,192,1) 0%,rgba(171,189,115,1) 100%); /* Chrome10+,Safari5.1+ */
		background: -o-linear-gradient(top,  rgba(228,239,192,1) 0%,rgba(171,189,115,1) 100%); /* Opera 11.10+ */
		background: -ms-linear-gradient(top,  rgba(228,239,192,1) 0%,rgba(171,189,115,1) 100%); /* IE10+ */
		background: linear-gradient(top,  rgba(228,239,192,1) 0%,rgba(171,189,115,1) 100%); /* W3C */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e4efc0', endColorstr='#abbd73',GradientType=0 ); /* IE6-8 */
	}


	div.blockFile
	{
		text-align: center;
		border-width: 1px;
		border-style: solid;
		border-color: #E3E3E3 #BFBFBF #BFBFBF #E3E3E3;
		padding: 8px!important;
		margin-bottom: 1.5em;
		margin-top: 2em;
		word-wrap: break-word;
		width: 570px!important;
		/* To enable gradients in IE < 9 */
		zoom: 1;
		-moz-border-radius: 7px;
		-webkit-border-radius: 7px;
		border-radius: 7px;
		-moz-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
		-webkit-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
		box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
		/* http://www.colorzilla.com/gradient-editor/ */
		background: rgb(255,255,255); /* Old browsers */
		/* IE9 SVG, needs conditional override of 'filter' to 'none' */
		background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmZmZmZiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmNmY2ZjYiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
		background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(246,246,246,1) 100%); /* FF3.6+ */
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,1)), color-stop(100%,rgba(246,246,246,1))); /* Chrome,Safari4+ */
		background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* Chrome10+,Safari5.1+ */
		background: -o-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* Opera 11.10+ */
		background: -ms-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* IE10+ */
		background: linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* W3C */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f6f6f6',GradientType=0 ); /* IE6-8 */
	}

	table.UploadControls
	{
		margin-top: 2em;
	}


	table.resultset
	{
		width: 570px!important;
	}

</style>

</head>

<body>
<div class="banner">
	<div class="Title">File Repository</div>
	<div class="ButtonBar"></div>
	<div class="ActionIconBar"></div>
</div>

<form method="post" action="do.php?_action=file_repository" enctype="multipart/form-data">
	<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo Repository::getMaxFileSize(); ?>" />
	<table width="100%" cellspacing="4" cellpadding="4" style="margin-left:10px">
		<tr>
			<td valign="top">
				<table width="580" cellspacing="4" cellpadding="4" style="margin-left:10px">
					<col width="100"/><col/>
					<tr>
						<td colspan="2">
							<h3 class="introduction">Introduction</h3>
							<p class="sectionDescription">The Sunesis file repository provides a secure conduit for the movement of sensitive data files between users and Perspective.</p>
							<p class="sectionDescription">Files can be uploaded, viewed and deleted by all users. Please note when a file is deleted it is permanent and irrecoverable.</p>
							<p class="sectionDescription">To upload a file choose the relevant section, browse for the document and click 'Upload File'.</p>
							<p class="sectionDescription">To assign the document to a new section, click on "New Section". 
								Accepted file types are: <code>.pdf</code>, </code><code>.doc</code>, <code>.docx</code>, <code>.csv</code>, <code>.rar</code>, <code>.txt</code>, <code>.xls</code>,<code>.xlsx</code>, <code>.xml</code>, <code>.zip</code>, and <code>.7z</code>.</p>
							<p class="sectionDescription">To view the documents that have been uploaded, click on the relevant section below.</p>
						</td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">Section:</td>
						<td><?php
							echo HTML::select("section", $section_options, $section); if($_SESSION['user']->type != 19){
								?> <a onclick="createSection();return false;" style="margin-left:10px">New</a> |
							<?php if($this->isSectionEmpty($section)){ ?>
								<a onclick="deleteSection();return false;">Delete</a>
								<?php } else { ?>
								<span style="color:silver">Delete</span>
								<?php }
						}?>
						</td>
					</tr>
					<?php if($_SESSION['user']->type != 19) {?>
					<tr>
						<td class="fieldLabel_compulsory">Upload file:</td>
						<td><input class="compulsory" type="file" name="uploadedFile[]" /><button style="margin-left: 10px" onclick="upload();return false;">Upload</button>
							<img id="progressSpinner" src="/images/loading4.gif" width="16" height="16" style="margin-left:5px;visibility:hidden"/></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">File space:</td>
						<td><?php $this->renderSpaceRemaining(); ?></td>
					</tr>
					<?php }?>
				</table>
				<?php
				if($_SESSION['user']->type == 5 AND $section != "")
				{
					$section = $_SESSION['user']->username . '/general/' . $section;
					$this->renderSectionFiles($section, $_SESSION['user']->username);
				}
				else
				{
					$this->renderSectionFiles($section);
				}
				?>
			</td>
			<td valign="top">
				<table>
					<tr>
						<td>
							<div class="panelPieChartFileUsage" id="panelPieChartFileUsage" style="min-width: 500px; height: 450px; margin: 30 auto"></div>
							<div class="panelPieChartFileUsage" id="panelPieChartFileUsage"></div> <br>
							<span class="fieldLabel_compulsory">Used Space: </span><?php echo $usedSpace; ?> MB <br>
							<span class="fieldLabel_compulsory">Remaining Space: </span><?php echo $remaining_space; ?> MB 
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2"><div id="panelFileRepoUsage" style="min-width: 500px; height: 450px; margin: 30 auto"></div></td>
		</tr>
	</table>
	
</form>

<div id="dialogDeleteFile" style="display:none" title="Delete file"></div>

<div id="dialogDeleteSection" style="display:none" title="Delete section"></div>

<div id="dialogCreateSection" style="display:none" title="Create section">
	<p>Enter a title for the new section. Section titles can be up to 25 characters in length, and may contain letter
		numbers, spaces, hyphens and underscores.</p>
	<p><input type="text" name="title" value="" size="25" maxlength="25" /></p>
</div>

<script src="https://code.highcharts.com/7.0.0/highcharts.src.js"></script>
<script src="https://code.highcharts.com/7.0.0/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/7.0.0/modules/exporting.js"></script>
<script src="https://code.highcharts.com/7.0.0/modules/no-data-to-display.js"></script>
<script src="module_charts/assets/jsonfn.js"></script>

<script>

	$(function(){

        var chart = new Highcharts.chart('panelPieChartFileUsage', JSONfn.parse(JSON.stringify(<?php echo $pieChartFileUsage; ?>)));
        var chart = new Highcharts.chart('panelFileRepoUsage', JSONfn.parse(JSON.stringify(<?php echo $panelFileRepoUsage; ?>)));

	});

</script>

</body>
</html>