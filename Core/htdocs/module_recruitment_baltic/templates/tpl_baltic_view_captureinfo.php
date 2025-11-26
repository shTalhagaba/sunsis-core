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

<?php
	$selected_theme = SystemConfig::getEntityValue($link, 'module_theme');
	if ( $selected_theme ) {
		echo '<link rel="stylesheet" href="/css/'.$selected_theme.'/common.css" type="text/css"/>';
	}
?>

<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
</head>
<body id="candidates" >
<div class="banner">
	<div class="Title">User Capture Data</div>
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

?>
<div id="infoblock">
	<?php $_SESSION['bc']->render($link); ?>
	<div id="feedback"><?php echo $feedback_message; ?></div>
</div>

<div id="maincontent">
	<div id="vacancies">
		<h1>User Capture Questions</h1>

		<?php
		// allow for the highlighting of the amended / created question..
		if ( isset($question->userinfoid) ) {
			echo $view->render_edit($link, $question->userinfoid);
		}
		else {
			echo $view->render_edit($link);
		}
		?>
		<h1>Create a New Question:</h1>
		<?php echo $view->render_new_form($link); ?>
	</div>
	<?php include_once('templates/layout/tpl_footer.php'); ?>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("form").live('submit', function(e) {
				var the_form = $(this);
				$.each($(the_form).serializeArray(), function(i, field ) {
					if ( field.name == 'infogroupid' && field.value === '' ) {
						var section_name = prompt('A new section? Type it here:');
						if (section_name != null && section_name != '' ) {
							$('#infogroupid').remove();
							the_form.append('<input type="hidden" name="infogroupid" value="'+section_name+'" />');
						}
						else {
							e.preventDefault();
							return false;
						}
					}
				});
			});
		});
	</script>
	<noscript>
		<?php include_once('templates/tpl_noscript.php'); ?>
	</noscript>
</body>
</html>