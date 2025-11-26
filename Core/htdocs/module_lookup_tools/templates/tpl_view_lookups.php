<?php
/**
 * User: Richard Elmes
 * Date: 10/05/12
 * Time: 14:15
 */
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Vacancies</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
</head>
<body id="candidates" >
<div class="banner">
	<div class="Title">
		View Look Up Data
		<?php
			echo $view->display_lookup_tables($link);
		?>
	</div>
	<div class="ButtonBar">

	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php
// establish all the messaging values
// for use in feedback
$feedback_message = '&#160;';
$feedback_color = '#DCE5CD';

if ( isset($this->feedback_message) ) {
	$feedback_message = $this->feedback_message;
}

?>
<div id="infoblock">
	<?php $_SESSION['bc']->render($link); ?>
	<div id="feedback"><?php echo $feedback_message; ?></div>
</div>

<div id="maincontent">
	<div id="vacancies">
		<h1>
			<?php if ( isset($_REQUEST['table_name']) ) {echo 'lookup_'.$_REQUEST['table_name'];} ?> Data Table Content
		</h1>

		<?php
			if ( isset($_REQUEST['table_name']) ) {
				echo '<p>';
				echo $view->display_table_comments($link, $_REQUEST['table_name']);
				echo '</p>';
				$view->render($link);
				if ( isset($view->table_has_primary_key) && $view->table_has_primary_key == 0 ) {
					echo '<div style="text-align:center"><h1>This Table has no Primary Key, please speak to the database team to make any changes to existing entries!</h1></div>';

				}
				echo '<h1>Add a new entry to the table:</h1>';
				echo $view->render_new_form($link, $_REQUEST['table_name']);
			}
		?>
	</div>
	<?php include_once('templates/layout/tpl_footer.php'); ?>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script type="text/javascript">
		function table_name_onchange(menuoption) {
			window.location.href='do.php?_action=view_lookups&table_name='+menuoption.value;
		}
		<?php
		if ( isset($view->table_has_primary_key) && $view->table_has_primary_key == 0 ) {
			echo '$("#existing_entries :input").attr("disabled", true);';
		}
		?>
	</script>
	<noscript>
		<?php include_once('templates/tpl_noscript.php'); ?>
	</noscript>
</body>
</html>