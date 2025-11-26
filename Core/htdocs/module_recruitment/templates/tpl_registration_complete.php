<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Perspective - Sunesis</title>
<!-- link rel="stylesheet" href="/common.css" type="text/css" / -->
<link rel="stylesheet" href="/css/core.css" type="text/css"/>
<link rel="stylesheet" href="/css/open.css" type="text/css"/>
<link rel="stylesheet" href="/print.css" media="print" type="text/css"/>
<?php
	// #176 - allow for client specific styling
	$css_filename = SystemConfig::getEntityValue($link, 'styling');
	if ( $css_filename != '' ) {
		echo '<link rel="stylesheet" href="/css/client/'.$css_filename.'" type="text/css"/>';	
	} 
?>

</head>
<body onload="body_onload()" id="registration">
<?php 
	$filename = DAO::getSingleValue($link, "Select value from configuration where entity='logo'");
	$filename = ($filename=='')?'perspective.png':$filename;
?>
  <div id="recruitment">
    <div id="customerlogo">
      <!--  img src="/images/logos/<?php echo $filename; ?>" alt="Sunesis - <?php echo DB_NAME; ?> candidate registration" / -->
    </div>
	<div id="divMessages">
		Your registration has been successful.
	</div>	
	<div id="main" style="text-align: center;" >
		Thank you for taking the time to complete our employer registration form.
		We will be in contact with you to discuss your apprenticeship requirements. Return to <a href="<?php echo SystemConfig::getEntityValue($link, 'recruitment_home'); ?>">our website</a>
		<br/>
		<br/>
		If you experience any difficulties with registration please contact us at: <a href="mailto:<?php echo SystemConfig::getEntityValue($link, 'recruitment_email'); ?>"><?php echo SystemConfig::getEntityValue($link, 'recruitment_email'); ?></a>
		<br/>
        Or help desk hotline: <?php echo SystemConfig::getEntityValue($link, 'recruitment_contact'); ?>
	</div>		   	
</div>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<script language="javascript" src="/js/sunesis-registration.js" type="text/javascript"></script>
<noscript>
	<?php include_once('templates/tpl_noscript.php'); ?>
</noscript>
</body>
</html>
