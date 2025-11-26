<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<title>Internal Support</title>
	<link rel="stylesheet" href="common.css" type="text/css"/>
	<style type="text/css">

		body {
			background-color: transparent;
			/* text-align: center; */
		}

		#wrapper {
			background-color: #fff;
			margin: 0 auto;
			padding: 20px;
			text-align: left;
			width: 960px;
		}

		#content {
			float: left;
			margin: 0, auto;
			width: auto;
			padding: 0 10px;
			border-right: 1px solid #7F9D89;
		}

		dd, input, select, textarea {
			padding: 2px 0;
			width: 210px;
		}

		input.radio-option {
			width: auto;
		}

		.datadisplay {
			font-size: 1.2em;
		}

		.clearfix {
			clear: both;
			height: 0;
			overflow: hidden;
		}

		#help {
			float: left;
			width: 240px;
			padding-left: 10px;
		}

		legend {
			/* background-color:#77A22F; */
			/* border:1px solid #7F9D89; */
			color: #000;
			font-size: 1.4em;
			padding: 0;
			margin: 0px 0px 10px -3px;
			text-align: right;
			text-transform: capitalize;
		}

		.smalltext {
			padding: 0 0 0 3px;
			margin: 0;
			font-size: 0.8em;
			color: #9f9f9f;
		}

		#support-requests {
			/* background-color: #f9f9f9; */
			border: none;
			float: left;
			height: 250px;
			width: 645px;
			margin: 3px;
			padding: 3px;
		}

		div.banner, #breadcrumbs {
			text-align: left;
		}

		h2 {
			padding: 0;
			color: #77A22F;
			margin: 0 0 5px 0;
			font-weight: normal;
			font-size: 1.5em;
			/* text-decoration: underline; */
		}

		.icon-pdf {
			padding-left: 20px;
			background: transparent url(/images/icons.png) 0 -248px no-repeat;
			width: 50px;
			height: 50px;
			line-height: 1.2em;
		}

		.icon-new-pdf {
			padding-left: 20px;
			background: transparent url(/images/icons.png) 0 -286px no-repeat;
			width: 50px;
			height: 50px;
			line-height: 1.2em;
		}

		#cases {
			width: 100%;
			border-collapse: collapse;
		}

		#cases td {
			border: 1px solid #9f9f9f;
			padding: 5px;
		}

		.header-row {
			background-color: #e9e9e9;
			font-size: 1.2em;
			font-weight: bold;
		}

		.case-info {
			background-color: #f9f9f9;
		}

		.case-solution td {
			background-color: #E0EAD0;
		}

		.case-complete td {
			background-color: #77A22F;
			color: #fff;
		}

	</style>

	<style type="text/css">
		@media print {
			#help {
				display: none;
			}
		}
	</style>
</head>
<body>

<div class="banner">
	<div class="Title">Your Support Requests</div>
	<div class="ButtonBar">
		<button onclick="window.location.replace('<?php echo $_SESSION['bc']->getPrevious(); ?>');">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<div id="wrapper">
	<div id="content">
		<div id="support-requests">
			<?php
			echo $cases_table;
			?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<script type="text/javascript">
	function save() {
		return true;
	}

	$(document).ready(function () {

		$('.change-status').click(function () {
			var $style = $(this).attr('class').replace('change-status r', '');
			var $link = $(this).attr('id');
			if (typeof $('#case-comment-' + $style) != 'undefined') {
				if ($('#case-comment-' + $style).attr('value') == 'your comments...') {
					alert('Can you add your comments, so we can best help you with your request!');
					return false;
				}

				if (typeof $('#close' + $style).attr('checked') != 'undefined') {
					if (confirm('You are about to close this request ' + $style + ', are you sure? ')) {
						if (typeof $('#case-comment-' + $style).attr('value') != 'undefined') {
							$link += escape($('#case-comment-' + $style).attr('value'));
						}
						$link += '&case-finished=1';
						// redirect to the update location
						$(location).attr('href', $link);
					}
					return false;
				}

				if (confirm('You are about to change the status of your request ID: ' + $style + ', are you sure? ')) {
					if (typeof $('#case-comment-' + $style).attr('value') != 'undefined') {
						$link += escape($('#case-comment-' + $style).attr('value'));
					}
					// redirect to the update location
					$(location).attr('href', $link);
				}
			}
			return false;
		});

	});
</script>
<script type="text/javascript">
	function submitSupportRequestsFilterForm() {
		var myForm = document.forms['support_filters'];
		myForm.submit();
	}
	function resetFilters() {
		var myForm = document.forms['support_filters'];
		myForm.reset();
		myForm.elements['filter_username'].value = '';
	}
	function submitPreferencesForm()
	{
		var myForm = document.forms['preferences'];
		myForm.elements['filter_username_preferences'].value = document.getElementById('filter_username').value;
		myForm.submit();

	}
</script>

</body>
</html>
