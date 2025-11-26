<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>College Tools</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.17.custom.css" type="text/css"/>
	<script language="javascript" src="/js/jquery.min.js" type="text/javascript"></script>
	<script language="JavaScript" src="/common.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.17.custom.min.js"></script>

	<!-- Bootstrap CSS Toolkit styles -->
	<link rel="stylesheet" href="uploader1/css/bootstrap.min.css">
	<link rel="stylesheet" href="uploader1/css/styles.css">
</head>

<body>
<div class="banner">
	<div class="Title">College Tool - Populate Sunesis</div>
	<div class="ButtonBar"></div>
	<div class="ActionIconBar"></div>
</div>


<div class="containers">
	<!-- Button to select & upload files -->
  <span class="btn btn-success fileinput-button">
    <span>Select File</span>
    <!-- The file input field used as target for the file upload widget -->
    <input id="fileupload" type="file" name="files[]" multiple>
  </span>


	<!-- The global progress bar -->
	<p></p>
	<div id="progress" class="progress progress-success progress-striped">
		<div class="bar"></div>
	</div>



	<!-- The list of files uploaded -->
	<p id="filesHeading" style="visibility: hidden;">Files uploaded:</p>
	<ul id="files"></ul>
	<div>
		<?php if(isset($output)) echo $output; ?>
	</div>


	<!-- Load jQuery and the necessary widget JS files to enable file upload -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="uploader1/js/jquery.ui.widget.js"></script>
	<script src="uploader1/js/jquery.iframe-transport.js"></script>
	<script src="uploader1/js/jquery.fileupload.js"></script>




	<!-- JavaScript used to call the fileupload widget to upload files -->
	<script>
		// When the server is ready...
		$(function () {
			'use strict';

			// Define the url to send the image data to
			var url = '../uploader1/files.php';

			// Call the fileupload widget and set some parameters
			$('#fileupload').fileupload({
				url: url,
				dataType: 'json',
				done: function (e, data) {
					// Add each uploaded file name to the #files list
					$.each(data.result.files, function (index, file) {
						var filename = file.name;
						$('<li/>').text(file.name).appendTo('#files');
						window.location.href = 'do.php?_action=read_and_display_xml&filename='+file.name;
					});
				},
				progressall: function (e, data) {
					// Update the progress bar while files are being uploaded
					var progress = parseInt(data.loaded / data.total * 100, 10);
					$('#progress .bar').css(
						'width',
						progress + '%'
					);
				},
				success: function(result){
//						window.location.href = 'do.php?_action=read_and_display_xml&filename=E';
				}
			});
		});

	</script>
</div>
</body>
</html>