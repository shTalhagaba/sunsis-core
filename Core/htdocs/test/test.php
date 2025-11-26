<?php
	require('config.php');
	//ini_set("output_buffering", "0");
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Test 1</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" href="/css/announcements.css" type="text/css"/>
<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.17.custom.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/jquery-ui/js/jquery-ui-1.8.17.custom.min.js" type="text/javascript"></script>


<script type="text/javascript">

});
</script>


<style type="text/css">

div.Announcements
{
	width: 400px;
}

div.Newspaper {
	column-count: 3;
	-moz-column-count: 3;
	-webkit-column-count: 3;

	column-gap: 30px;
	-moz-column-gap: 30px;
	-webkit-column-gap: 30px;

	width: 1150px;
}

<?php echo NewsFeed::getCSS(); ?>

</style>


</head>
<body>
<?php
//$link = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";port=".DB_PORT, DB_USER, DB_PASSWORD);
$link = DAO::getConnection();
?>

<?php
function getTotalSpace($space)
{
	$default = 500 * (1024 * 1024); // 500M
	//$space = SystemConfig::get('repository.space');
	if (!$space) {
		$space = $default;
	} else {
		$space = strtoupper($space);
		if (preg_match('/([0-9]+)([KMG])/', $space, $matches)) {
			switch ($matches[2]) {
				case 'K':
					$space = $matches[1] * (1024);
					break;
				case 'M':
					$space = $matches[1] * (1024 * 1024);
					break;
				case 'G':
					$space = $matches[1] * (1024 * 1024 * 1024);
					break;
				default:
					$space = $default;
					break;
			}
		} else {
			$space = $default;
		}
	}

	return $space;
}


echo getTotalSpace('');

?>


</body>
</html>
