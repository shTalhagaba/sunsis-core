<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Companies</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<!--[if IE]>
	<link rel="stylesheet" href="/common-ie.css" type="text/css"/>
	<![endif]-->
	<script type="text/javascript">
		var GB_ROOT_DIR = "/assets/js/greybox/";
	</script>
	<script type="text/javascript" src="/assets/js/greybox/AJS.js"></script>
	<script type="text/javascript" src="/assets/js/greybox/AJS_fx.js"></script>
	<script type="text/javascript" src="/assets/js/greybox/gb_scripts.js"></script>
	<link href="/assets/js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<script type="text/javascript" language="javascript">
		function downloadDump()
		{
			//window.location.href='do.php?_action=download_dump&subaction=download_dump';
			var myForm = document.forms['form1'];
			myForm.submit();
		}
	</script>

</head>

<body>
<div class="banner">
	<div class="Title">Data Dump</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->isAdmin()) { ?>
		<button onclick="downloadDump();">Download</button>
		<?php } ?>
	</div>
	<div class="ActionIconBar">
	</div>
</div>


<div align="center" style="margin-top:50px;">
	<form name="form1" action="do.php?_action=download_dump" method="post">
		<input type="hidden" name="subaction" value="download_dump" />
		<?php echo HTML::checkboxGrid('tables', $tables, ''); ?>
	</form>
</div>



</body>
</html>