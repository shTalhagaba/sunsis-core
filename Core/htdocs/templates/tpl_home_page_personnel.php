<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sunesis Home Page</title>
<link rel="stylesheet" href="/common.css" type="text/css" />
<link rel="stylesheet" href="/css/announcements.css" type="text/css" />
<link rel="stylesheet" href="/print.css" media="print" type="text/css" />

<script language="javascript" src="/js/jquery.min.js" type="text/javascript"></script>
<script language="javascript" src="/js/highcharts.js" type="text/javascript"></script>
<script src="/js/modules/exporting.js" type="text/javascript"></script>

<script type="text/javascript" src="/common.js"></script>

<style type="text/css">
.icon-ppt { padding-left: 20px; background: transparent url(/images/icons.png) 0 0px no-repeat; width:50px; height:50px}
.icon-dmg { padding-left: 20px; background: transparent url(/images/icons.png) 0 -36px no-repeat; width:50px; height:50px}
.icon-prv { padding-left: 20px; background: transparent url(/images/icons.png) 0 -72px no-repeat; width:50px; height:50px}
.icon-gen { padding-left: 20px; background: transparent url(/images/icons.png) 0 -108px no-repeat; width:50px; height:50px}
.icon-doc { padding-left: 20px; background: transparent url(/images/icons.png) 0 -144px no-repeat; width:50px; height:50px}
.icon-jar { padding-left: 20px; background: transparent url(/images/icons.png) 0 -180px no-repeat; width:50px; height:50px}
.icon-zip { padding-left: 20px; background: transparent url(/images/icons.png) 0 -216px no-repeat; width:50px; height:50px}
.icon-pdf { padding-left: 20px; background: transparent url(/images/icons.png) 0 -248px no-repeat; width: 50px; height: 50px; line-height: 1.2em;}
.icon-new-pdf { padding-left: 20px; background: transparent url(/images/icons.png) 0 -283px no-repeat; width: 50px; height: 50px; line-height: 1.2em;}


	/******* Statistics Style ******************/
div.GraphMenu
{
	width: 90%!important;
	margin:0px!important;
	padding:0px!important;
}

.GraphMenu a
{
	line-height: 1em;
	text-align: center;
	float:left;
	height: 35px;
	padding-top:7px!important;
	padding-bottom:5px!important;
	margin:0px!important;
	text-decoration:none;
	font-family: Arial,sans-serif;
	font-size: 1.1em;
	color:#555555;
	background-color: white;
	border: solid #555555;
	border-width: 1px;
	width: 45%;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px 5px 2px 2px;
	-moz-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
	-webkit-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
	box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
}

.GraphMenu a.selected
{
	color:#fff;
	background: rgb(40,52,59); /* Old browsers */
	background: -moz-linear-gradient(top,  rgba(40,52,59,1) 0%, rgba(130,140,149,1) 44%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(40,52,59,1)), color-stop(44%,rgba(130,140,149,1))); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top,  rgba(40,52,59,1) 0%,rgba(130,140,149,1) 44%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top,  rgba(40,52,59,1) 0%,rgba(130,140,149,1) 44%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top,  rgba(40,52,59,1) 0%,rgba(130,140,149,1) 44%); /* IE10+ */
	background: linear-gradient(top,  rgba(40,52,59,1) 0%,rgba(130,140,149,1) 44%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#28343b', endColorstr='#828c95',GradientType=0 ); /* IE6-9 */
}

.GraphMenu a:hover
{
	color:#555555;
	background: rgb(226,226,226); /* Old browsers */
	/* IE9 SVG, needs conditional override of 'filter' to 'none' */
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2UyZTJlMiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjUwJSIgc3RvcC1jb2xvcj0iI2RiZGJkYiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjUxJSIgc3RvcC1jb2xvcj0iI2QxZDFkMSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmZWZlZmUiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top,  rgba(226,226,226,1) 0%, rgba(219,219,219,1) 50%, rgba(209,209,209,1) 51%, rgba(254,254,254,1) 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(226,226,226,1)), color-stop(50%,rgba(219,219,219,1)), color-stop(51%,rgba(209,209,209,1)), color-stop(100%,rgba(254,254,254,1))); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top,  rgba(226,226,226,1) 0%,rgba(219,219,219,1) 50%,rgba(209,209,209,1) 51%,rgba(254,254,254,1) 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top,  rgba(226,226,226,1) 0%,rgba(219,219,219,1) 50%,rgba(209,209,209,1) 51%,rgba(254,254,254,1) 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top,  rgba(226,226,226,1) 0%,rgba(219,219,219,1) 50%,rgba(209,209,209,1) 51%,rgba(254,254,254,1) 100%); /* IE10+ */
	background: linear-gradient(top,  rgba(226,226,226,1) 0%,rgba(219,219,219,1) 50%,rgba(209,209,209,1) 51%,rgba(254,254,254,1) 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e2e2e2', endColorstr='#fefefe',GradientType=0 ); /* IE6-8 */
}

.GraphMenu ul
{
	height: 35px;
	list-style-type:none;
	position:relative;
	margin:0px!important;
	padding-top:0px!important;
}

.GraphMenu li
{
	display:inline;
	margin:0px!important;
	padding:0px!important;
	margin:0px!important;
	text-align: justify;
}

	/******* Statistics Table Style ******************/
table.resultset
{
	border-width:1px 1px 1px 1px!important;
	border-color:#CCCCCC!important;
	border-style:dotted!important;
	width:275px!important;
}

table.resultset td
{
	border-width:1px 1px 1px 1px!important;
	border-color:#CCCCCC!important;
	text-align: center!important;
	border-style:dotted!important;
	color: #555555;
}

table.resultset th
{
	border-width:1px 1px 1px 1px!important;
	border-color:#CCCCCC!important;
	text-align: center!important;
	border-style:dotted!important;
	color: #555555;
}

	/******* Background Style ******************/
div.block
{
	text-align: center;
	border-width: 1px;
	border-style: solid;
	border-color: #E3E3E3 #BFBFBF #BFBFBF #E3E3E3;
	padding: 8px!important;
	margin-bottom: 1.5em;
	word-wrap: break-word;
	width: 90%!important;
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

#candidates h3
{
	margin-top: 0px;
	font-family: Arial,sans-serif;
	font-size: 16pt;
	color: #555555;
	letter-spacing: 0em;
}

#candidates p
{
	font-family: sans-serif;
	font-size: 100%;
	color: #555555;
	font-style: normal;
	text-align: justify;
	margin: 5px 10px 10px 10px;
}

div.column
{
	padding: 10px!important;
}

#candidates p.taskTitle
{
	font-size: 12px;
	font-weight: bold;
	color: #555555;
	width: 85%;
	height: 1.2em;
	overflow: hidden;
}

#candidates p.sectionDescription
{
	font-size: 9pt;
	font-style: italic;
	width:90%;
}

#candidates p.taskborder
{
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #999999;
	padding-bottom:5px;
}

	/* Announcements */
div.longcontent
{
	display: none;
}

</style>


<script type="text/javascript">

	/*var root=null;
	var tags = new Array();
	var tagcount = 0;
	var xml = '<root>';
	var elements_counter = 0;
	var oldReference = '';
	var unitTitleElement = '';
	var evidence_methods = new Array();
	var evidence_types = new Array();
	var evidence_categories = new Array();*/

	//Toggle between statistic graphs
	function show_graph(linkobj, graph)
	{
		$('#learnerStatus').hide();
		/* $('#learnerPerOrganisation').hide(); */
		$('#ILRContainer').hide();

		$('#'+graph).fadeToggle(1000, "linear");


		$('#GraphMenu a').each(function(index){
			$(this).toggleClass("selected", false);
		});

		$(linkobj).toggleClass("selected", true);
	}

	//Toggle between support information
	function show_support(linkobj, support)
	{
		$('#supportInformation').hide();
		$('#howToSheets').hide();
		$('#'+support).fadeToggle(1000, "linear");
		$('#SupportMenu a').each(function(index){
			$(this).toggleClass("selected", false);
		});
		$(linkobj).toggleClass("selected", true);
	}

	//Toggle between announcement subtitle and content
	$(document).ready(function() {
		/*$(".longcontent").each(function(){
			$(this).hide();
		});*/

		$(".morelink").click(function(){
			$(this).toggle();

			long_text = $(this).prop('id').replace("morelink", "long");
			$('#'+long_text).slideDown("fast");
		});

		$(".lesslink").click(function(){
			var long_text = $(this).prop('id').replace("lesslink", "long");
			$('#'+long_text).slideUp("fast");

			short_text = long_text.replace("long", "morelink");
			$('#'+short_text).toggle();
		});
	});
</script>

<!--[if gte IE 9]>
<style type="text/css">
	div.Announcement, div.block, div.GraphMenu, div.SupportMenu, a:hover, div.GraphMenu a {
		filter: none !important;
	}
</style>
<![endif]-->

</head>


<body id="candidates">
<div id="homepage">
</div>

<div id="maincontent" style="" >


	<!-- Build STATISTICS -->
	<div class="column">
		<div class="ButtonPanel">
			<div class="GraphMenu" id="GraphMenu">

				<ul>
					<li><a name="Statistics" href="#statistics" class="selected" onclick="show_graph(this, 'learnerStatus')">Learner Status</a></li>
					<?php // <li><a name="Statistics" href="#statistics" onclick="show_graph(this, 'learnerPerOrganisation')">Learners per Organisation</a></li> ?>
					<li><a name="Statistics" href="#statistics" onclick="show_graph(this, 'ILRContainer')">ILR's</a></li>
				</ul>
			</div>
		</div>

		<div style="border:0px solid black" id="learnerStatus"></div>
		<!--  div style="border:0px solid black; display:none;" id="learnerPerOrganisation"></div -->
		<div style="border:0px solid black; display:none;" id="ILRContainer">

			<div id="ILR"></div>
			<table class="resultset" id="ILRdatatable">
				<thead>
				<tr height="10px">
					<th>ILR Validation</th>
					<th>No of ILRs</th>
				</tr>
				</thead>
				<tbody>
				<tr height="10px">
					<th>Not Valid</th>
					<td><?php echo $ilr_count["invalid"]; ?></td>
				</tr>
				<tr height="10px">
					<th>Valid</th>
					<td><?php echo $ilr_count["valid"]; ?></td>
				</tr>
				</tbody>
			</table>
		</div>

		<!-- Link to most frequent tasks -->
		<h3>You May Want To...</h3>
		<p class="taskTitle"> View all Qualification Framework</p>
		<p class="sectionDescription">A template of qualifications used for more than 1 course</p>
		<p class="taskborder"><a href="do.php?_action=view_frameworks" target="right">View Frameworks</a></p>
		<p class="taskTitle"> View all Courses</p>
		<p class="sectionDescription">Learners are enrolled onto courses where they complete different qualifications</p>
		<p class="taskborder"><a href="do.php?_action=view_courses2" target="right">View Courses</a></p>
		<p class="taskTitle"> View all Learners</p>
		<p class="sectionDescription">Learners are available to be enrolled onto training courses. </p>
		<p><a href="do.php?_action=view_learners&id=1" target="right">View Learners</a></p>

	</div>


			<div style="border:0px solid black" id="supportInformation">
				<br>
				<p>
					To raise a query with the support team, please use the <a href="do.php?_action=support_form&header=1" target="right">Support Request Form</a>
				</p>
				<p>
					To view the status of your support requests, please see <a href="do.php?_action=support_requests&header=1" target="right">Your Support Requests</a>
				</p>
				<p>
					Our support hours are 9am until 5pm Monday to Friday.
				</p>
			</div>

			<div style="border:0px solid black; display:none;" id="howToSheets">
				<br>
				<p>Please use the guides below to help with your use of Sunesis.  All our 'How to' guides are in PDF format.
				</p>
				<?php echo $help_guide_html;?>
				<p>In order to view them you will need to have Adobe Reader installed.
				</p>
				<p><a href="http://www.adobe.com/products/acrobat/readstep2.html" target="_blank"><img src="/images/get_adobe_reader.png" style="border:0;" alt="get adobe reader" /></a>
				</p>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		<?php echo $stat_graphs; ?>
	</script>

</body>
</html>