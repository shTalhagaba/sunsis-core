<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Merge Duplicate Learner Records (ULN)</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.17.custom.css" type="text/css"/>
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/jquery-ui/js/jquery-ui-1.8.17.custom.min.js" type="text/javascript"></script>

<script type="text/javascript">
$(function(){
	$('input.MergeButton').click(mergeButtonOnClick);
	$('input.MarkPrimary').click(markPrimaryOnClick);

	$('#dialogMergeConfirm').dialog({
		modal: true,
		width: 500,
		closeOnEscape: true,
		autoOpen: false,
		resizable: false,
		draggable: false,
		buttons: {
			'Cancel': function() {
				$(this).dialog('close');
			},
			'Merge': function() {
				var btn = $(this).data('btnMerge');
				mergeRecords(btn);
				$(this).dialog('close');
			}
		}
	});
});

function markPrimaryOnClick(e)
{
	var $table = $(this).closest('table');
	var $tr = $(this).closest('tr');
	var $buttons = $('input:radio', $table);
	var $checkboxes = $('input:checkbox', $table);
	var $peerCheckbox = $('input:checkbox[value="' + $(this).val() + '"]', $table);

	$buttons.closest('tr').css('background-color', '');
	$checkboxes.prop('checked', 'checked').prop('disabled', false);
	$peerCheckbox.prop('checked', '').prop('disabled', true);
	$tr.css('background-color', '#FFD9B5')
}

function mergeButtonOnClick(e)
{
	var $btn = $(this);
	var $table = $btn.closest('table');
	var $selectedButton = $('input:radio:checked', $table);
	var $selectedCheckboxes = $('input:checkbox:checked', $table);

	if ($selectedButton.length == 0) {
		alert("Merge failed. No master record selected.");
		return false;
	}
	if ($selectedCheckboxes.length == 0) {
		alert("Merge failed. No duplicate records selected.");
		return false;
	}

	var $dialog = $('#dialogMergeConfirm');
	$dialog.data('btnMerge', this);
	$dialog.dialog('open');
}

function mergeRecords(btnMerge)
{
	var $btn = $(btnMerge);
	var $table = $btn.closest('table');
	var $selectedButton = $('input:radio:checked', $table);
	var $selectedCheckboxes = $('input:checkbox:checked', $table);

	if ($selectedButton.length == 0) {
		alert("Merge failed. No master record selected.");
		return false;
	}
	if ($selectedCheckboxes.length == 0) {
		alert("Merge failed. No duplicate records selected.");
		return false;
	}

	// Extract primary and secondary IDs
	var primaryId = $selectedButton.val();
	var secondaryIds = new Array();
	$selectedCheckboxes.each(function(){
		secondaryIds.push($(this).val());
	});

	// Build URL
	var url = "do.php?_action=merge_duplicate_learners_by_uln&subaction=merge"
		+ "&primary_id=" + encodeURIComponent(primaryId);
	for (var i = 0; i < secondaryIds.length; i++) {
		url += "&secondary_id[]=" + encodeURIComponent(secondaryIds[i]);
	}

	var client = ajaxRequest(url);
	if (client) {
		window.location.reload();
	}
}

function viewRecord(username)
{
	window.open('do.php?_action=read_user&username=' + encodeURIComponent(username),
		'viewUser', 'height=400,width=650,scrollbars=yes,resizable=yes,toolbar=no,location=no');
}

</script>

<style type="text/css">
h3.introduction {
	width: 100%;
	padding: 0px 0px 3px 3px;
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
	width: 800px;
}

li {
	margin-top: 5px;
}

table.resultset {
	margin-top: 40px;
}

</style>

</head>

<body>
<div class="banner">
	<div class="Title">Merge Duplicate Learner Records (ULN)</div>
	<div class="ButtonBar">
		<!-- <button onclick="window.location.href='do.php?_action=edit_user&people=<?php //echo $people; ?>&people_type=<?php //echo $people_type; ?>';">New</button> -->
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<div style="width:900px;margin-left:auto;margin-right:auto;">
<h3 class="introduction">Important - please read</h3>
<p class="introduction">The <a href="http://www.learningrecordsservice.org.uk/products/uln/" target="_blank">Unique Learner Number</a> (ULN)
is a unique learner identifier issued by the Learner Records Service. It is similar to the NHS number in design and is used
to identify a learner across providers and awarding bodies. No two people can have the same ULN.</p>
<p class="introduction">No two learner records belonging to the same employer in Sunesis should have the same ULN. If two or more
	of an employer's learners in Sunesis have the same ULN, it indicates either a duplication of learner records
	or an incorrect assignment of a ULN.</p>
<ul class="introduction">
	<li>Where the records of different learners share the same ULN, the records need to be corrected, not merged.
		The shared ULN can only be correct for one of the learners at most. Use the "View" buttons to edit learner records
		and remove or replace incorrectly assigned ULNs. <b>Warning</b>: do not merge the records of different learners together.</li>
	<li>Where a learner has one or more duplicate records, the duplicate records need to be merged into one record.
		Please designate one of the duplicated records as the <i>primary record</i> and one or more of the remaining records as <i>duplicate records</i>,
		then press "Merge".
		The training records of the duplicate records will be transfered to the primary record, and then the duplicate
		records will be deleted.</li>
</ul>
<p class="introduction" style="margin-top:20px">Please take the greatest care when merging records together.
	The merge process is irreversible.</p>

<?php $this->_renderDuplicates($link); ?>

</div>

<div title="Merge Records" id="dialogMergeConfirm" style="display:none">
	<p>This action will replace all references to the secondary records with
	references to the <b>primary record</b>, severing all relationships to the secondary
	records. The secondary records will be then be removed.</p>
	<p>This action <span style="font-style:italic">cannot</span> be undone. Continue?</p>
</div>

</body>

</html>