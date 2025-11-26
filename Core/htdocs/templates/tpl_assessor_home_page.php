<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<title>Sunesis - Assessor View</title>
	<link rel="stylesheet" href="/common.css" type="text/css" />
	<link rel="stylesheet" href="/css/announcements.css" type="text/css" />
	<link rel="stylesheet" href="/css/home_page.css" type="text/css"/>
	<link rel="stylesheet" href="/print.css" media="print" type="text/css" />
	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.17.custom.css" type="text/css"/>

	<script language="javascript" src="/js/jquery.min.js" type="text/javascript"></script>
	<script language="javascript" src="/js/highcharts2.js" type="text/javascript"></script>
	<script src="/js/modules/exporting.js" type="text/javascript"></script>
	<script type="text/javascript" src="/common.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.17.custom.min.js"></script>

	<style type="text/css">
		#slideshow {
			/*margin: 50px auto;*/
			position: relative;
			width: 240px;
			height: 240px;
			padding: 10px;
			box-shadow: 0 0 20px rgba(0,0,0,0.4);
		}

		#slideshow > div {
			position: absolute;
			top: 10px;
			left: 10px;
			right: 10px;
			bottom: 10px;
		}
		div.block
		{
			text-align: left;
			border-width: 1px;
			border-style: solid;
			border-color: #E3E3E3 #BFBFBF #BFBFBF #E3E3E3;
			padding: 8px!important;
			margin-bottom: 1.5em;
			word-wrap: break-word;
			width: 100%!important;
			/* To enable gradients in IE < 9 */
			zoom: 1;
			-moz-border-radius: 7px;
			-webkit-border-radius: 7px;
			border-radius: 7px;
			-moz-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			-webkit-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			/* http://www.colorzilla.com/gradient-editor/ */
			background: rgb(255,255,255); /* Old browsers */
			/* IE9 SVG, needs conditional override of 'filter' to 'none' */
			background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmZmZmZiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmNmY2ZjYiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
			background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(246,246,246,1) 100%); /* FF3.6+ */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,1)), color-stop(100%,rgba(246,246,246,1))); /* Chrome,Safari4+ */
			background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* Opera 11.10+ */
			background: -ms-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* IE10+ */
			background: linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* W3C */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f6f6f6',GradientType=0 ); /* IE6-8 */
		}
		div.Announcements {
			margin-top: 0px;
			/*padding-right: 1.5em;*/
		}

		div.Statistics {
			margin-top: 0px;
		}

		div.ButtonPanel {
			border-width: 1px;
			border-style: solid;
			border-color: #DDDDDD #CCC #CCC #DDDDDD;
			background-color: white;
			margin-bottom: 1.5em;
			padding: 5px;
			-moz-border-radius: 7px;
			-webkit-border-radius: 7px;
			border-radius: 7px;

			-moz-box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
			-webkit-box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
			box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
		}

		div.LabelNewAnnouncement {
			float: right;
			padding: 5px;
			margin: 0px 0px 10px 10px;

			font-size: 12pt;
			font-weight: bold;
			color: orange;

			/*border: 2px solid orange;*/

			/*background-color: white;*/
			color: orange;
			font-size: 12pt;

			-moz-border-radius: 5px;
			-webkit-border-radius: 5px;
			border-radius: 5px;

			-moz-box-shadow: 0px 0px 8px rgba(255, 165, 0, 0.8);
			-webkit-box-shadow: 0px 0px 8px rgba(255, 165, 0, 0.8);
			box-shadow: 0px 0px 8px rgba(255, 165, 0, 0.8);
		}

		div.NavigationPanel {
			border-width: 1px;
			border-style: solid;
			border-color: #DDDDDD #CCC #CCC #DDDDDD;
			background-color: white;
			margin-bottom: 1.5em;
			padding: 0px;
			-moz-border-radius: 7px;
			-webkit-border-radius: 7px;
			border-radius: 7px;

			-moz-box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
			-webkit-box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
			box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
		}

		*.FloatingImage {
			float: left;
			margin: 0px 10px 2px 0px;
		}

		*.NoSelect {
			-webkit-user-select: none;
			-khtml-user-select: none;
			-moz-user-select: none;
			user-select: none;
		}

		td.HighLight {
			background-color: orange;
			color: white;
		}

		<?php if (preg_match('/MSIE [5-8]/', $_SERVER ['HTTP_USER_AGENT'])) { ?>
		div.Announcement, div.Comment, div.ButtonPanel, div.NavigationPanel {
			border-width: 1px 2px 2px 1px;
		}

		div.NavigationPanel {
			border: none;
		}
			<?php } ?>
	</style>
	<!--[if gte IE 9]>
	<style type="text/css">
		div.Announcement, div.block, div.GraphMenu, div.SupportMenu, a:hover, div.GraphMenu a {
			filter: none !important;
		}
	</style>
	<![endif]-->

	<script type="text/javascript">
		//Toggle between announcement subtitle and content
		$(document).ready(function()
		{
			$(".morelink").click(function()
			{
				$(this).toggle();

				long_text = $(this).prop('id').replace("morelink", "long");
				$('#'+long_text).slideDown("fast");
			});

			$(".lesslink").click(function()
			{
				var long_text = $(this).prop('id').replace("lesslink", "long");
				$('#'+long_text).slideUp("fast");

				short_text = long_text.replace("long", "morelink");
				$('#'+short_text).toggle();
			});
		});
		function details(id) {
			showHideBlock(document.getElementById(id));
		}
		//Toggle between statistic graphs
		function show_graph(linkobj, graph) {
			$('#learnerStatus').hide();
			/* $('#learnerPerOrganisation').hide(); */
			$('#ilr').hide();

			$('#' + graph).fadeToggle(1000, "linear");


			$('#GraphMenu a').each(function (index) {
				$(this).toggleClass("selected", false);
			});

			$(linkobj).toggleClass("selected", true);
		}

	</script>


</head>


<body id="candidates">
<div class="banner">
	<div class="Title">Sunesis - Assessor View</div>
	<div class="Timestamp"><?php echo date('D, d M Y H:i:s T'); ?></div>
	<div class="ButtonBar"></div>
	<div class="ActionIconBar"><img src="/images/btn-printer.gif" class="ActionIcon" onclick="window.print()" width="25"
	                                height="25"/></div>
	<div class="banner_end"></div>
</div>


<div id="maincontent" style="">
	<!--<div class="ButtonPanel"><?php /*$this->renderButtons($link); */?></div>-->
	<div class="column">
		<div class="Announcements">
			<?php $this->renderAnnouncements($link, $announcement_view);?>
		</div>
		<br>
		<div>
			<div class="GraphMenu" id="GraphMenu">

				<ul>
					<li><a name="Statistics" href="#statistics" class="selected" onclick="show_graph(this, 'learnerStatus')">Learner Status</a></li>
					<li><a name="Statistics" href="#statistics" onclick="show_graph(this, 'ilr')">ILR's</a></li>
				</ul>
			</div>
		</div>
		<div class="GraphMenu" id="GraphMenu">
			<div style="border:0px solid black" id="learnerStatus"></div>
			<div style="border:0px solid black; display:none;" id="ilr"></div>
		</div>
		
	</div>
	<div class="column">
		<img id="prev" style="cursor: pointer;" src="/images/view-navigation/previous.gif" />
		<img id="next" style="float: right; cursor: pointer;" src="/images/view-navigation/next.gif" />
		<div id="slideshow">

			<?php
			$user_id = $_SESSION['user']->id;
			$sql = <<<HEREDOC
SELECT tr.`firstnames`, tr.`surname`, appointments.`appointment_date`, appointments.`appointment_start_time`, appointments.`appointment_end_time`,
(SELECT legal_name FROM organisations WHERE id = tr.employer_id) AS legal_name, tr.`work_address_line_1`, tr.`work_address_line_2`, tr.`work_address_line_3`, tr.`work_address_line_4`, tr.`work_email`,
tr.`work_mobile`, tr.`work_telephone`, tr.`work_postcode`
FROM tr INNER JOIN appointments ON tr.id = appointments.`tr_id` AND appointment_date = CURRENT_DATE() AND appointments.appointment_status = 1 WHERE appointments.`interviewer` = $user_id
HEREDOC;

			$organisations_to_visit = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
			foreach($organisations_to_visit AS $org)
			{
				echo '<div>';
				echo '<h2>Visits Today</h2>';
				echo '<p><strong>' . $org['appointment_start_time'] . ' - ' . $org['appointment_end_time'] . '</strong></p>';
				echo '<p><strong>' . $org['surname'] . ', ' . $org['firstnames'] . '</strong></p>';
				echo '<strong>' . $org['legal_name'] . '</strong><a href="http://maps.google.co.uk/maps?f=q&hl=en&q=' . urlencode($org['work_postcode']) . '" target="_blank"><img src="/images/postcode-image.jpg" width="20" height="20" /></a><br>';
				if(!is_null($org['work_address_line_1']))
					echo $org['work_address_line_1'] . ', ';
				if(!is_null($org['work_address_line_2']))
					echo $org['work_address_line_2'] . ', ';
				if(!is_null($org['work_address_line_3']))
					echo $org['work_address_line_3'] . '<br>';
				if(!is_null($org['work_address_line_4']))
					echo $org['work_address_line_4'] . '<br>';
				if(!is_null($org['work_postcode']))
					echo '<a href="http://maps.google.co.uk/maps?f=q&hl=en&q=' . urlencode($org['work_postcode']) . '" target="_blank">'. htmlspecialchars((string)$org['work_postcode']) . '</a><br>';
				if(!is_null($org['work_telephone']))
					echo $org['work_telephone'] . '<br>';
				if(!is_null($org['work_mobile']))
					echo $org['work_mobile'] . '<br>';
				if(!is_null($org['work_email']))
					echo '<a href="mailto:' . urlencode($org['work_email']) . '">'. htmlspecialchars((string)$org['work_email']) . '</a><br>';
				echo '</div>';
			}
			if(count($organisations_to_visit) == 0)
			{
				echo '<div>';
				echo '<h3>You currently have no scheduled visits today</h3>';
				echo '</div>';
			}
			?>
		</div>
		<br>
		<div id="col1" class="block"></div>
		<p><?php echo $this->getAppointmentsStats($link); ?></p>
	</div>
</div>
<script type="text/javascript">
	<?php
	if( isset($_SESSION['user']->new_announcement_count) && $_SESSION['user']->new_announcement_count > 0 ) {
		?>
	$(function() {
		$( "#dialog:ui-dialog" ).dialog( "destroy" );

		$( "#dialog-confirm" ).dialog({
			resizable: false,
			height:180,
			modal: true,
			width: 450,
			closeOnEscape: true,
			draggable: false,
			autoOpen: true,
			buttons:
			{
				"OK": function()
				{
					$( this ).dialog( "close" );
				}
			}
		});
	});

		<?php
	}
	?>
	<?php echo $stat_graphs; ?>
</script>

<script>
	var stopped=false;
	<?php if(count($organisations_to_visit) == 1){ ?>
		stopped=true;
	<?php } ?>
	$("#slideshow > div:gt(0)").hide();

	setInterval(function() {
		if(!stopped)
		{
			$('#slideshow > div:first')
				.fadeOut(500)
				.next()
				.fadeIn(500)
				.end()
				.appendTo('#slideshow');
		}
	},  2000);


</script>
<script language="javascript" type="text/javascript">
	var global_date = -1;

	// if the feedback element has content show it
	$(document).ready(function() {

		$("#next").click(function(){
			stopped = true;
			$('#slideshow > div:first-child')

				.fadeOut(500)
				.next()
				.fadeIn(500)
				.end()
				.appendTo('#slideshow');
		});

		$("#prev").click(function(){
			stopped = true;
			$('#slideshow > div:first-child')
				.fadeOut(500)
			$('#slideshow > div:last-child')
				.prependTo('#slideshow')
				.fadeOut();
			$('#slideshow > div:first-child').fadeIn();
		});

		display_actions(-1);


		$('.actionlist').live("click", function(){
			global_date = $(this).attr('href').match(/appointment_start_date=(.*)/)[1];
			display_actions($(this).attr('href').match(/appointment_start_date=(.*)/)[1]);
			return false;
		});
	});

	function display_actions(appointment_start) {
		var request = ajaxRequest('do.php?_action=ajax_get_learners_appointments','appointment_start_date='+appointment_start);
		$("div[id=col1]").html(request.responseText);
	}

</script>

</body>
</html>
