<?php  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Learner Import Result</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>
	<style type="text/css">
		h3.introduction {
			width: 100%;
			padding: 0px 0px 3px 0px;
			margin: 0px;
		}

		p.introduction {
			width: 100%;
			padding: 0px;
			margin: 10px 0px 0px 0px;
		}

		ul.introduction {
			font-family: sans-serif;
			font-size: 11pt;
			color: #176281;
			font-style: normal;
			text-align: justify;
			margin: 15px 0px 10px 0px;
			/*width: 1000px;*/
		}

		li {
			margin-top: 5px;
		}

	</style>

	<script language="JavaScript">



	</script>

</head>
<body>
<div class="banner">
	<div class="Title">Learner Import Result</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12){?>
<!--		<button id="btnSearch"  onclick="search_by_demographics();">Search</button>
-->		<?php }?>
<!--		<button onclick="window.location.href='<?php /*echo $_SESSION['bc']->getPrevious();*/?>';">Cancel</button>
-->	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>


<p></p>
<div id="output" align="center">
<?php echo $outputHTML; ?>
</div>
</body>
</html>