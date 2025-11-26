<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<title>Sunesis Home Page</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" href="/css/announcements.css" type="text/css"/>
	<link rel="stylesheet" href="/css/home_page.css" type="text/css"/>
	<link rel="stylesheet" href="/print.css" media="print" type="text/css"/>
	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.17.custom.css" type="text/css"/>

	<script language="javascript" src="/js/jquery.min.js" type="text/javascript"></script>
	<script language="javascript" src="/js/highcharts2.js" type="text/javascript"></script>
	<script src="/js/modules/exporting.js" type="text/javascript"></script>
	<script type="text/javascript" src="/common.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.17.custom.min.js"></script>

	<script type="text/javascript">
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

		//Toggle between support information
		function show_support(linkobj, support) {
			$('#supportInformation').hide();
			$('#howToSheets').hide();
			$('#releaseInformation').hide();
			$('#' + support).fadeToggle(1000, "linear");
			$('#SupportMenu a').each(function (index) {
				$(this).toggleClass("selected", false);
			});
			$(linkobj).toggleClass("selected", true);
		}

		//Toggle between announcement subtitle and content
		$(document).ready(function () {
			/*$(".longcontent").each(function(){
			 $(this).hide();
		 });*/

			$(".morelink").click(function () {
				$(this).toggle();

				long_text = $(this).prop('id').replace("morelink", "long");
				$('#' + long_text).slideDown("fast");
			});

			$(".lesslink").click(function () {
				var long_text = $(this).prop('id').replace("lesslink", "long");
				$('#' + long_text).slideUp("fast");

				short_text = long_text.replace("long", "morelink");
				$('#' + short_text).toggle();
			});
		});

		function details(id) {
			showHideBlock(document.getElementById(id));
		}
	</script>
	<!--[if gte IE 9]>
	<style type="text/css">
		div.Announcement, div.block, div.GraphMenu, div.SupportMenu, a:hover, div.GraphMenu a {
			filter: none !important;
		}
	</style>
	<![endif]-->

</head>
<body onload='$(".loading-gif").hide();' id="candidates">
<div id="homepage"></div>
<!-- Build ANNOUNCEMENTS -->
<?php if(DB_NAME!='am_edexcel' && $_SESSION['user']->type!=User::TYPE_LEARNER && $_SESSION['user']->type!=User::TYPE_BRAND_MANAGER) { ?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=homepage" enctype="multipart/form-data">
	<input type="hidden" name="_action" value="homepage" />
</form>
<div class="column Announcements" >
	<?php
	$this->renderAnnouncements($link,$announcement_view);
	$announcement_view = $this->buildView($link);
	?>
	<?php if(DB_NAME!="am_set" && DB_NAME!='am_template'){?>
	<div class="ButtonPanel">
		<div class="GraphMenu" id="GraphMenu">

			<ul>
				<li><a name="Statistics" href="#statistics" class="selected" onclick="show_graph(this, 'learnerStatus')">Learner Status</a></li>
				<?php // <li><a name="Statistics" href="#statistics" onclick="show_graph(this, 'learnerPerOrganisation')">Learners per Organisation</a></li> ?>
				<li><a name="Statistics" href="#statistics" onclick="show_graph(this, 'ilr')">ILR's</a></li>
			</ul>
		</div>
	</div>
	<div style="border:0px solid black" id="learnerStatus"></div>
	<!--  div style="border:0px solid black; display:none;" id="learnerPerOrganisation"></div -->
	<div style="border:0px solid black; display:none;" id="ilr"></div>
	<?php }?>
	<?php if(DB_NAME!='am_template' && $_SESSION['user']->type!=User::TYPE_LEARNER && $_SESSION['user']->type!=User::TYPE_BRAND_MANAGER) { ?>
	<div class="block">
		<h3>File Repository</h3>
		<p>
			The Sunesis <a href="do.php?_action=file_repository" target="right">File Repository </a> provides a secure conduit for the movement of sensitive data files between users and Perspective.
		</p>
		<div style="border:0px solid black" id="fileSize"></div>
	</div>
	<?php } ?>
	<?php
	if ( SystemConfig::getEntityValue($link, 'module_support') && $_SESSION['user']->type != 19 && DB_NAME!='am_template') {
		if( ( SystemConfig::getEntityValue($link, 'support_limited') && $_SESSION['user']->isAdmin() == 1 ) ) {
			?>
			<!-- Link to Support Information -->
		<div class="block">
		<div class="GraphMenu_new" id="SupportMenu">
			<ul>
				<li><a name="Support" href="#Support" class="selected" onclick="show_support(this, 'supportInformation')">Support</a></li>
				<?php if($_SESSION['user']->type!=5 && $_SESSION['user']->type!=19) { ?>
				<li><a name="Support" href="#Support" onclick="show_support(this, 'howToSheets')">How To Guides</a></li>
				<?php } ?>
				<li><a name="release" href="#release" onclick="show_support(this, 'releaseInformation')">Release Notes</a></li>
			</ul>
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

			<div style="border:0px solid black;display:none;" id="releaseInformation">
				<br>
				<p>
					Following are the monthly software releases containing information for new functionalities.
				</p>
				<p>
					<a href="/images/Sunesis_Release_Oct2013.docx">Release Notes 1 (October 2013)</a>
				</p>
				<p>
					<a href="/images/Sunesis_Release_Nov2013.docx">Release Notes 2 (November 2013)</a>
				</p>
				<p>
					<a href="/images/Sunesis_Release_Dec2013.docx">Release Notes 3 (December 2013)</a>
				</p>
				<p>
					<a href="/images/Sunesis_Release_Jan2014.docx">Release Notes 4 (January 2014)</a>
				</p>
				<p>
					<a href="/images/Sunesis_Release_Feb2014.docx">Release Notes 5 (February 2014)</a>
				</p>
				<p>
					<a href="/images/Sunesis_Release_Mar2014.docx">Release Notes 6 (March 2014)</a>
				</p>
				<p>
					<a href="/images/Sunesis_Release_Apr2014.docx">Release Notes 7 (April 2014)</a>
				</p>
			</div>

			<?php if($_SESSION['user']->type!=5 && $_SESSION['user']->type!=19) { ?>
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
				<?php
			}
		}
		else if( !SystemConfig::getEntityValue($link, 'support_limited') ) {
			?>
			<!-- Link to Support Information -->
		<div class="block">
			<div class="GraphMenu_new" id="SupportMenu">
				<ul>
					<li><a name="Support" href="#Support" class="selected" onclick="show_support(this, 'supportInformation')">Support</a></li>
					<?php if($_SESSION['user']->type!=5) { ?>
					<li><a name="Support" href="#Support" onclick="show_support(this, 'howToSheets')">How To Guides</a></li>
					<?php } ?>
					<li><a name="release" href="#release" onclick="show_support(this, 'releaseInformation')">Release Notes</a></li>
				</ul>
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
			<div style="border:0px solid black;display:none;" id="releaseInformation">
				<br>
				<p>
					Following are the monthly software releases containing information for new functionalities.
				</p>
				<p>
					<a href="/images/Sunesis_Release_Oct2013.docx">Release Notes 1 (October 2013)</a>
				</p>
				<p>
					<a href="/images/Sunesis_Release_Nov2013.docx">Release Notes 2 (November 2013)</a>
				</p>
				<p>
					<a href="/images/Sunesis_Release_Dec2013.docx">Release Notes 3 (December 2013)</a>
				</p>
				<p>
					<a href="/images/Sunesis_Release_Jan2014.docx">Release Notes 4 (January 2014)</a>
				</p>
				<p>
					<a href="/images/Sunesis_Release_Feb2014.docx">Release Notes 5 (February 2014)</a>
				</p>
				<p>
					<a href="/images/Sunesis_Release_Mar2014.docx">Release Notes 6 (March 2014)</a>
				</p>
				<p>
					<a href="/images/Sunesis_Release_Apr2014.docx">Release Notes 7 (April 2014)</a>
				</p>
			</div>
			<?php if($_SESSION['user']->type!=5) { ?>
				<div style="border:0px solid black; display:none;" id="howToSheets">
					<br>
					<p>Please use the guides below to help with your use of Sunesis.  All our 'How to' guides are in PDF format.</p>
					<?php echo $help_guide_html;?>
					<p>In order to view them you will need to have Adobe Reader installed.</p>
					<p>
						<a href="http://www.adobe.com/products/acrobat/readstep2.html" target="_blank"><img src="/images/get_adobe_reader.png" style="border:0;" alt="get adobe reader" /></a>
					</p>
				</div>
				<?php
			}
		}
		?>
	</div>
<?php } ?>
</div>
</div>
	<?php
	if( isset($_SESSION['user']->new_announcement_count) && $_SESSION['user']->new_announcement_count == '1' )
	{
		?>
	<!-- If new Announcement equals 1 show following dialogue-->
	<div id="dialog-confirm" style="display:none" title="Hi <?php echo $_SESSION['user']->firstnames;?>">
		<p>There has been <b><?php echo $_SESSION['user']->new_announcement_count; ?></b> new announcement since you last logged in.</p>
		<p>All announcements can be viewed on the Sunesis homepage.</p>
	</div>

		<?php
	}
	else
	{
		?>
	<!-- else show annoucement dialogue for more than 1-->
	<div id="dialog-confirm" style="display:none" title="Hi <?php echo $_SESSION['user']->firstnames;?>">
		<p>There have been <b><?php echo $_SESSION['user']->new_announcement_count; ?></b> new announcements since you last logged in.</p>
		<p>All announcements can be viewed on the Sunesis homepage.</p>
	</div>

		<?php
	}
	?>
	<?php } ?>
<div class="column">
	<!--<div id="col1" class="block"></div>-->
	<h3>Appointments</h3>
	<p><?php echo $this->getAppointmentsStats($link); ?></p>
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
<?php
if ( (SystemConfig::getEntityValue($link, 'module_kpi_sla_reports') || DB_NAME=="am_demo") && ( $_SESSION['user']->is_admin) && (1 != 1))
{
	if($filter_dtls[0] != 'false')
	{
		?>
	<div style="float: left; clear: both;">
		<?php foreach($filter_dtls as $filter_details)
	{
		?>
		<iframe src="do.php?_action=sla_kpi_graphs_dashboard&report_type=<?php echo $filter_details['report_type'];?>&filter_id=<?php echo $filter_details['id'];?>" width="1166px" style="border:0; height: 500px;" scrolling="no"></iframe>
		<?php
	}
		?>
	</div>
		<?php
	}
}
?>
<!--<script language="javascript" type="text/javascript">
	   var global_date = -1;
	   // if the feedback element has content show it
	   $(document).ready(function() {

		   display_actions(-1);


		   $('.actionlist').live("click", function(){
			   global_date = $(this).attr('href').match(/interview_start_date=(.*)/)[1];
			   display_actions($(this).attr('href').match(/interview_start_date=(.*)/)[1]);
			   return false;
		   });
	   });

	   function display_actions(interview_start) {
		   var request = ajaxRequest('do.php?_action=ajax_get_learners_interviews','interview_start_date='+interview_start);
		   $("div[id=col1]").html(request.responseText);
	   }

   </script>-->

</body>
</html>