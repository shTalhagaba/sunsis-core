<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Frameworks and Units Details</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>


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

	<script type="text/javascript">
		function run()
		{
			window.location.replace('do.php?_action=report1&framework_id='+$('#framework_id').val());
		}
	</script>
</head>

<body>
<div class="banner">
	<div class="Title">Frameworks and Units Details</div>

	<div class="ActionIconBar">
		<!--<button onclick="window.location.href = 'do.php?_action=report1&export=export';" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>-->
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<div align="center" style="margin-top:50px;">

	<?php echo HTML::select('framework_id', $frameworksDDL, $framework_id, true); ?>
	<span class="button" onclick="run();">Enter</span>

</div>

<div align="center" style="margin-top:50px;">

	<?php echo $text_html; ?>

</div>


</body>
</html>