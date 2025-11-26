<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Smart Assessor: Learner Link with Assessor</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.17.custom.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script src="/jquery-ui/js/jquery-ui-1.8.17.custom.min.js" type="text/javascript"></script>

	<script type="text/javascript">

		$(function(){
			$('input#btnFilter').click(onFilterClick).click();
		});

		function onFilterClick(e) {
			var $checkboxes = $('div#Filters input:checkbox:checked');
			var filters = '';
			for (var i = 0; i < $checkboxes.length; i++) {
				filters += '&filter_sections[]=' + $checkboxes.eq(i).val();
			}

			$('div#Filters input').prop('disabled', 'disabled');
			showProgressAnimation('loading51.gif');
			$('div#content').load('do.php?_action=sa_learnerassessor_sync&subaction=rendercontent' + filters, null, onContentLoad);
		}

		function onContentLoad(responseText, textStatus, xmlHttpRequest)
		{
			$('input.SelectAll').click(function(e){
				if ($(this).prop('checked')) {
					$(this).closest('table').find('input.SelectRow').prop('checked', 'checked').closest('tr').css('background-color', '#dfe9cd');
				} else {
					$(this).closest('table').find('input.SelectRow').prop('checked', '').closest('tr').css('background-color', '');
				}
			})
			$('input#BtnLink').click(linkRecords);
			$('input#BtnUnlink').click(unlinkRecords);
			$('input#BtnCreate').click(createRecords);
            $('input#BtnCreateInSunesis').click(createRecordsInSunesis);
            $('input#BtnUpdateInSunesis').click(updateRecordsInSunesis);

			$('div#content input:checkbox').not('[disabled="disabled"]').not('.SelectAll').click(onCheckboxClick);

			$('div#Filters input').prop('disabled', '');
		}

		function createRecords(e)
		{
			var $selectedBoxes = $(this).closest('table').find('input.SelectRow:checked').remove('input[value=""]');
			if ($selectedBoxes.length == 0) {
				alert("Please select one or more records to create in Smart Assessor");
				return;
			}

			var data = '';
			for (var i = 0, max = $selectedBoxes.length; i < max; i++) {
				data += '&ids[]=' + encodeURIComponent($selectedBoxes.eq(i).val());
			}

			showProgressAnimation('loading51.gif');
			var client = ajaxRequest('do.php?_action=sa_learnerassessor_sync&subaction=createrecords' + data);
			if (client) {
/*				alert(client.responseText);
				if (window.console) {
					window.console.log(client.responseText);
				}*/
			}
			//$('div#content').load('do.php?_action=sa_learnerassessor_sync&subaction=rendercontent', null, onContentLoad);
			$('input#btnFilter').click();
		}

		function linkRecords(e)
		{
			var $selectedBoxes = $(this).closest('table').find('input.SelectRow:checked').remove('input[value=""]');
			if ($selectedBoxes.length == 0) {
				alert("Please select one or more pairs of records to link together");
				return;
			}

			var data = '';
			for (var i = 0, max = $selectedBoxes.length; i < max; i++) {
				data += '&ids[]=' + encodeURIComponent($selectedBoxes.eq(i).val());
			}

			showProgressAnimation('loading51.gif');
			var client = ajaxRequest('do.php?_action=sa_learnerassessor_sync&subaction=linkrecords' + data);
			if (!client) {
				//
			}
			//$('div#content').load('do.php?_action=sa_learnerassessor_sync&subaction=rendercontent', null, onContentLoad);
			$('input#btnFilter').click();
		}

		function unlinkRecords(e)
		{
			var $selectedBoxes = $(this).closest('table').find('input.SelectRow:checked').remove('input[value=""]');
			if ($selectedBoxes.length == 0) {
				alert("Please select one or more pairs of records to unlink");
				return;
			}

			var data = '';
			for (var i = 0, max = $selectedBoxes.length; i < max; i++) {
				data += '&ids[]=' + encodeURIComponent($selectedBoxes.eq(i).val());
			}

			showProgressAnimation('loading51.gif');
			var client = ajaxRequest('do.php?_action=sa_learnerassessor_sync&subaction=unlinkrecords' + data);
			if (!client) {
				//
			}
			//$('div#content').load('do.php?_action=sa_learnerassessor_sync&subaction=rendercontent', null, onContentLoad);
			$('input#btnFilter').click();
		}

        function createRecordsInSunesis(e)
		{
			var $selectedBoxes = $(this).closest('table').find('input.SelectRow:checked').remove('input[value=""]');
			if ($selectedBoxes.length == 0) {
				alert("Please select one or more records to create in Sunesis");
				return;
			}

			var data = '';
			for (var i = 0, max = $selectedBoxes.length; i < max; i++) {
				data += '&ids[]=' + encodeURIComponent($selectedBoxes.eq(i).val());
			}

			showProgressAnimation('loading51.gif');
			var client = ajaxRequest('do.php?_action=sa_learnerassessor_sync&subaction=createrecordsinsunesis' + data);
			if (client) {
/*				alert(client.responseText);
				if (window.console) {
					window.console.log(client.responseText);
				}*/
			}
			//$('div#content').load('do.php?_action=sa_learnerassessor_sync&subaction=rendercontent', null, onContentLoad);
			$('input#btnFilter').click();
		}

        function updateRecordsInSunesis(e)
		{
			var $selectedBoxes = $(this).closest('table').find('input.SelectRow:checked').remove('input[value=""]');
			if ($selectedBoxes.length == 0) {
				alert("Please select one or more records to update in Sunesis");
				return;
			}

			var data = '';
			for (var i = 0, max = $selectedBoxes.length; i < max; i++) {
				data += '&ids[]=' + encodeURIComponent($selectedBoxes.eq(i).val());
			}

			showProgressAnimation('loading51.gif');
			var client = ajaxRequest('do.php?_action=sa_learnerassessor_sync&subaction=updaterecordsinsunesis' + data);
			if (client) {
/*				alert(client.responseText);
				if (window.console) {
					window.console.log(client.responseText);
				}*/
			}
			//$('div#content').load('do.php?_action=sa_learnerassessor_sync&subaction=rendercontent', null, onContentLoad);
			$('input#btnFilter').click();
		}

		function showProgressAnimation(filename)
		{
			filename = filename ? filename : 'loading51.gif'; // default
			$('div#content').html('<img src="/images/progress-animations/' + encodeURIComponent(filename) + '"/>');
		}

		function onCheckboxClick(e)
		{
			if ($(this).is(':checked')) {
				$(this).closest('tr').css('background-color', '#dfe9cd');
			} else {
				$(this).closest('tr').css('background-color', '');
			}
		}

	</script>

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

			/* Tweak the standard resultset table */
		table.resultset {
			margin-top: 40px;
			border-collapse: collapse;
			table-layout: fixed;
			width: 1150px;
			word-wrap: break-word;
		}
		table.resultset td {
			border-width: 1px 1px 1px 1px;
		}
		caption {
			font-size: 14pt;
			font-weight: bold;
			color: #176281;
		}

		td.MissingData{
			background-color: red;
		}

		td.SmartAssessorId{
			font-size:6pt;
			font-family:'Segoe UI', Tahoma, Arial, Sans-Serif;
		}

		tr.Data {
			font-size:8pt;
			font-family:'Segoe UI', Tahoma, Arial, Sans-Serif;
		}

		div#Filters {
			margin-top: 70px;
			text-align: center;
			background-color: #bbca85;
			padding: 8px;
			width: 95%;
			margin-left: auto;
			margin-right: auto;

			border-radius: 8px;

			-moz-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			-webkit-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
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
	</style>

</head>

<body>
<div class="banner">
	<div class="Title">Smart Assessor: Learner Link with Assessor</div>
	<div class="ButtonBar">

	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<div style="width:1150px;margin-left:auto;margin-right:auto;">
	<h3 class="introduction">Instructions</h3>
	<div class="Newspaper">
	<p class="introduction">
         Sunesis learner assessor data may be synchronised with learner course assessor in Smart Assessor.
         There must be a Learner, Qualification and Assessor in Smart Assessor with same Learner, Qualification and Assessor data as in Sunesis.
		 <!--If the learner course is not assigned to any assessor in Smart Assessor, where the assessor exists within Sunesis, then System will migrate Sunesis learner's assessor with Smart Assessor learner course.-->
    </p>
	<p class="introduction">This page provides tools to create or remove links between existing learner assessor records in Smart Assessor.
	</p>
	<p class="introduction">Learner courses are requested from Smart Assessor and compared to Learner Qualification in Sunesis. The results of the
		comparison are collated into three groups:</p>
	<ul class="introduction">
		<li>Linked learner assessor records: These learner assessor records are identified as being equivalent, either because they were created in Smart Assessor
			by Sunesis or because they have been manually linked.</li>
        <li>No match between assigned assessors in Sunesis and Smart Assessor learner assessor: Learner assessor which is assigned in SA is different than assessor assigned in Sunesis.</li>
        <!--<li>No match in assigned assessors in Sunesis and Smart Assessor: Learner assessor which is assigned in SA is different than assessor assigned in Sunesis. Use this tool to update learner's assessor in Sunesis.</li>-->
		<!--<li>Sunesis Assessor no link with Learners in Smart Assessor: Sunesis learner courses which has assigned assessor but assessor is not linked with Smart Assessor learner courses. Use this tool to update the records in Smart Assessor.</li>-->
        <li>Smart Assessor learner link with assessor with no record in Sunesis: Smart Assessor learner courses which have assigned assessor but assessor is not linked within Sunesis learner courses. Use this tool to update the records in Sunesis.</li>
	</ul>
	</div>

	<div id="Filters">
		<table width="100%">
			<col width="80"/><col width="700"/><col/>
			<tr>
				<td style="font-weight:bold">Sections: </td>
				<td><?php echo HTML::checkboxGrid('filter_sections', $filterSectionsOptions, $filterSections, 4); ?></td>
				<td align="right"><input type="button" id="btnFilter" value="Go" /></td>
			</tr>
		</table>
	</div>

	<div id="content" style="text-align:center;margin-top:50px;"><img src="/images/progress-animations/loading51.gif"/></div>
</div>

<div id="output"></div>

</body>

</html>