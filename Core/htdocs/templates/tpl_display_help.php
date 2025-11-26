<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Qualifications</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
<script language="javascript" src="/js/jquery.min.js" type="text/javascript"></script>
<script language="JavaScript" src="/common.js"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script language="JavaScript" src="/calendarPopup/CalendarPopup.js"></script>


</script>

<script language="JavaScript" src="/scripts/display_help.js"></script>
<script type="text/JavaScript">
var helpKey = '<?php echo addslashes((string)$vo->key); ?>';
var helpId = '<?php echo addslashes((string)$vo->id); ?>';

$(function(){
	// IE6 doesn't support the CSS selector below natively - so we have to use jQuery
	$('div.Wiki tr:first-child > td').css('background-color', '#ffcd62').css('font-weight', 'bold');

	// No way to select a parent in CSS, so we use jQuery
	// to alter the margins around a paragraph containing a single image
	$('div.Wiki > p > img').closest('p').each(function(){
		if($(this).text() == ""){
			$(this).css('margin-bottom', '2em').css('margin-left', '20px');
		}
	});
	
	if(window.history.length > 1)
	{
		$('#btnBack, #btnForward').removeAttr("disabled");
		$('#btnBack img').attr('src', '/images/navigation3/previous.gif');
		$('#btnForward img').attr('src', '/images/navigation3/next.gif');
	}
	else
	{
		$('#btnBack, #btnForward').attr("disabled", "disabled");
		$('#btnBack img').attr('src', '/images/navigation3/previous-grey.gif');
		$('#btnForward img').attr('src', '/images/navigation3/next-grey.gif');
	}
});

function editHelpPage()
{
	window.opener.location.replace('do.php?_action=edit_help&key=' + encodeURIComponent(helpKey) + '&id=' + encodeURIComponent(helpId));
	window.close();
}

</script>

<style type="text/css">
body
{

	margin: 0px;
}





<?php if (! preg_match ( '/MSIE [1-6]/', $_SERVER ['HTTP_USER_AGENT'] )) { ?>



<?php } ?>
</style>
</head>
<body>


<div class="banner">
	
	<div class="ButtonBar">

	</div>
	<div class="ActionIconBar">
		
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>





<div class="Wiki">
<h1><?php echo $vo->title; ?></h1>
<?php $this->renderHelp($vo); ?>
</div>
</body>
</html>