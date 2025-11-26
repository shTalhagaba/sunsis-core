<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<meta name="viewport" content="width=device-width">
	<title>Numeracy Test</title>

	<link rel="stylesheet" href="/print.css" media="print" type="text/css"/>
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/jquery.steps.js"></script>
	<link href="css/jquery.steps.css?t=<?echo time();?>" rel="stylesheet">
	<script src="js/form-validation/jquery.validate.min.js"></script>
	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.24.custom.css" type="text/css"/>
	<script type="text/javascript" src="//code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
	<script src="/common.js" type="text/javascript"></script>
	<link rel="stylesheet" href="css/flipclock.css">
	<script src="js/flipclock.js"></script>
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
	<style type="text/css">
		input[type="text"] {
			border-width: 1px;
			border-color: #7F9DB9;
			border-style: solid;
			padding: 2px;
			background-color: #FFFFFF !important; /* GOOGLE TOOLBAR AUTOFILL KILLER */
			height: 2em;
			border-radius: 4px;
		}
	</style>
	<script type="text/javascript">

	</script>
	<script src="js/test_numeracy.js?n=<?php echo time(); ?>"></script>
	<link rel="stylesheet" type="text/css" href="../css/test_numeracy.css?n=<?php echo time(); ?>"/>
</head>
<body>


<div id="wrapper">
<div id="headerwrap">
	<div id="header">
		<div id="logo"><img src="/images/logos/SUNlogo.jpg"</div>
	</div>
</div>
<div id="navigationwrap">
	<div id="navigation">
		<div>
			<div id="progressbar"></div>
		</div>
		<p id="progressLabel">0%</p>
		<div class="clock" style="margin:2em;"></div>
	</div>
</div>
<div id="contentwrap">
<div id="content">
<div class="wizard">
<form id="frmInitialAssessment" action="/do.php?_action=test_numeracy" method="post" autocomplete="off">
<input type="hidden" name="check" id="check" value="check"/>

<h3>Section 1</h3>
<fieldset>
	<legend>Section 1</legend>
	<div style="clear: both;"><p style="font-size: 18px;"><strong>Read the questions below and answer the questions</strong></p></div>
	<br>
	<div style="float: left; width: 100%;">

		<?php
		$questions = DAO::getResultset($link, "SELECT * FROM ia_numeracy_questions WHERE section='section1'", DAO::FETCH_ASSOC);

		foreach ($questions AS $question)
		{
			$table_id = "question_" . $question['id'];
			$question_row_id = "question_" . $question['id'] . "_description";
			$question_blank_id = "question_" . $question['id'] . "_blank";
			$answers_row_id = "choices_question_" . $question['id'];
			$question_statement = $question['description'];
			$question_statement = str_replace('?', '<span id="' . $question_blank_id . '" style="color: green; font-size: 15pt; text-decoration: underline;">?</span>', $question_statement);

			$id_choice_1 = "question_" . $question['id'] . "_choice_1";
			$id_choice_2 = "question_" . $question['id'] . "_choice_2";
			$id_choice_3 = "question_" . $question['id'] . "_choice_3";
			$id_choice_4 = "question_" . $question['id'] . "_choice_4";

			echo '<table id="' . $table_id . '" style="border: 3px solid #B5B8C8;border-radius: 15px; width: 100%;">';
			echo '<tr id="' . $question_row_id . '">';
			echo '<td colspan="4"><p><span style="color:blue">' . $question['id'] . '</span>. ' . $question_statement . '</p></td>';
			echo '</tr>';
			echo '<tr id="' . $answers_row_id . '" class="tr_answers">';
			echo '<td align="center" class="" id="' . $id_choice_1 . '" onclick="circle(this);">' . $question['choice_1'] . '</td>';
			echo '<td align="center" class="" id="' . $id_choice_2 . '" onclick="circle(this);">' . $question['choice_2'] . '</td>';
			echo '<td align="center" class="" id="' . $id_choice_3 . '" onclick="circle(this);">' . $question['choice_3'] . '</td>';
			echo '<td align="center" class="" id="' . $id_choice_4 . '" onclick="circle(this);">' . $question['choice_4'] . '</td>';
			echo '</tr>';

			echo '</table><br>';
		}
		?>
	</div>
</fieldset>
<h3>Section 2</h3>
<fieldset>
	<legend>Section 2</legend>
	<div style="float: left;">
		<?php
		$questions = DAO::getResultset($link, "SELECT * FROM ia_numeracy_questions WHERE section='section2'", DAO::FETCH_ASSOC);

		foreach ($questions AS $question) {

			$table_id = "question_" . $question['id'];
			$question_row_id = "question_" . $question['id'] . "_description";
			$question_blank_id = "question_" . $question['id'] . "_blank";
			$answers_row_id = "choices_question_" . $question['id'];
			$question_statement = $question['description'];
			$question_statement = str_replace('?', '<span id="' . $question_blank_id . '" style="color: green; font-size: 15pt; text-decoration: underline;">?</span>', $question_statement);

			$id_choice_1 = "question_" . $question['id'] . "_choice_1";
			$id_choice_2 = "question_" . $question['id'] . "_choice_2";
			$id_choice_3 = "question_" . $question['id'] . "_choice_3";
			$id_choice_4 = "question_" . $question['id'] . "_choice_4";

			echo '<table id="' . $table_id . '" style="border: 3px solid #B5B8C8;border-radius: 15px; width: 100%;">';
			if($question['id'] == 10)
				echo '<tr><td colspan="3"><table align="center" border="1">
				<tr><td>Sarah</td><td>&pound; 424</td></tr>
				<tr><td>Heather</td><td>&pound; 473</td></tr>
				<tr><td>Katie</td><td>&pound; 452</td></tr>
				<tr><td>Jane</td><td>&pound; 475</td></tr>
				</table></td></tr>';
			echo '<tr id="' . $question_row_id . '">';
			echo '<td colspan="4"><p><span style="color:blue">' . $question['id'] . '</span>. ' . $question_statement . '</p></td>';
			echo '</tr>';
			echo '<tr id="' . $answers_row_id . '" class="tr_answers">';
			echo '<td align="center" class="" id="' . $id_choice_1 . '" onclick="circle(this);">' . $question['choice_1'] . '</td>';
			echo '<td align="center" class="" id="' . $id_choice_2 . '" onclick="circle(this);">' . $question['choice_2'] . '</td>';
			echo '<td align="center" class="" id="' . $id_choice_3 . '" onclick="circle(this);">' . $question['choice_3'] . '</td>';
			echo '<td align="center" class="" id="' . $id_choice_4 . '" onclick="circle(this);">' . $question['choice_4'] . '</td>';
			echo '</tr>';

			echo '</table><br>';
		}
		?>
	</div>
</fieldset>
<h3>Section 3</h3>
<fieldset>
	<legend>Section 3</legend>

	<div style="clear: both;"><p style="font-size: 18px; color: blue;">A secretary buys 3 boxes of A1 paper. Each box has 10 packets. There are 25 sheets of paper in each packet. </p></div>
	<br>

	<div style="float: left;">
		<?php
		$questions = DAO::getResultset($link, "SELECT * FROM ia_numeracy_questions WHERE section='section3'", DAO::FETCH_ASSOC);

		foreach ($questions AS $question) {
			$table_id = "question_" . $question['id'];
			$question_row_id = "question_" . $question['id'] . "_description";
			$question_blank_id = "question_" . $question['id'] . "_blank";
			$answers_row_id = "choices_question_" . $question['id'];
			$question_statement = $question['description'];
			$question_statement = str_replace('?', '<span id="' . $question_blank_id . '" style="color: green; font-size: 15pt; text-decoration: underline;">?</span>', $question_statement);

			$id_choice_1 = "question_" . $question['id'] . "_choice_1";
			$id_choice_2 = "question_" . $question['id'] . "_choice_2";
			$id_choice_3 = "question_" . $question['id'] . "_choice_3";
			$id_choice_4 = "question_" . $question['id'] . "_choice_4";

			if($question['id'] == 15)
				echo '<br><p><div style="clear: both;"><p style="font-size: 18px; color: blue;">Trisha is a manicurist. Her clients are late 1/4 of the time. Trisha has 28 clients a week.</p></div></p></br>';
			echo '<table id="' . $table_id . '" style="border: 3px solid #B5B8C8;border-radius: 15px; width: 100%;">';
			echo '<tr id="' . $question_row_id . '">';
			echo '<td colspan="4"><p><span style="color:blue">' . $question['id'] . '</span>. ' . $question_statement . '.</p></td>';
			echo '</tr>';
			echo '<tr id="' . $answers_row_id . '" class="tr_answers">';
			echo '<td align="center" class="" id="' . $id_choice_1 . '" onclick="circle(this);">' . $question['choice_1'] . '</td>';
			echo '<td align="center" class="" id="' . $id_choice_2 . '" onclick="circle(this);">' . $question['choice_2'] . '</td>';
			echo '<td align="center" class="" id="' . $id_choice_3 . '" onclick="circle(this);">' . $question['choice_3'] . '</td>';
			echo '<td align="center" class="" id="' . $id_choice_4 . '" onclick="circle(this);">' . $question['choice_4'] . '</td>';
			echo '</tr>';

			echo '</table><br>';
		}
		?>
	</div>
</fieldset>

</form>
</div>
</div>
</div>
<div id="footerwrap">
	<div id="footer">
		<span
			style="float: left; text-align: left; margin-top: 10px;margin-left: 10px;"><?php echo date('D, d M Y'); ?></span>
		<span style="float: right; text-align: right; margin-top: 10px; margin-right: 5px;">Powered by Sunesis &nbsp;|&nbsp;&copy; <?php echo date('Y'); ?>
			Perspective Ltd</span>
	</div>
</div>
</div>
<script type="text/javascript">
	var clock;

	$(document).ready(function () {

		clock = $('.clock').FlipClock(600, {
			clockFace:'MinuteCounter',
			countdown:true,
			autoStart:false,
			callbacks:{
				start:function () {
					$('.message').html('The clock has started!');
				},
				stop:function () {
					finishAndSubmitAssessment();
				}
			}
		});
		clock.start();
		$('.start').click(function (e) {

			clock.start();
		});

	});
</script>
</body>
</html>
