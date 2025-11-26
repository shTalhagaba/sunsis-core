$(function () {
	$('.dual_select').bootstrapDualListbox({
		selectorMinimalHeight:500,
		preserveSelectionOnMove:'true'
	});
	$('.datepicker').attr('class', 'form-control');

});



$body = $("body");

$(document).on({
	ajaxStart: function() { $body.addClass("loading"); },
	ajaxStop: function() { $body.removeClass("loading"); }
});


function download_tracking_view_to_csv(course_id)
{
	window.location.href='do.php?_action=read_course_v2&subview=tracking_view_export&id=' + course_id;
}


$('#tracking_learner_status').on('change', function(){


	if (window.location.search.indexOf('tracking_learner_status') > -1)
	{
		$.urlParam = function(name){
			var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
			return results[1] || 0;
		};

		window.location.href=location.href.replace("tracking_learner_status="+$.urlParam('tracking_learner_status'), "tracking_learner_status="+this.value);
	}
	else
	{
		window.location.href=location.href+'&tracking_learner_status='+this.value;
	}

});


function resetFilters()
{
	var form = document.forms["filters"];
	resetViewFilters(form);
}

function uploadFileToTrainingGroup()
{
	var myForm = document.forms["frmUploadFileToTrainingGroup"];
	if(validateForm(myForm) == false)
	{
		return false;
	}
	myForm.submit();
}

function deleteFile(filepath)
{
	if(!confirm('This action is irreversible, are you sure you want to continue?'))
		return false;

	var client = ajaxRequest('do.php?_action=delete_file&f=' + encodeURIComponent(filepath));
	if(client)
	{
		window.location.reload();
	}
	else
	{
		alert(client);
	}
}

$(document).ready(function(){
	//fill data to tree  with AJAX call
	$('#tree-container').jstree({
		'plugins' : ['state','contextmenu','wholerow'],
		'core' : {
			"themes" : { "variant" : "large" },
			"check_callback": function (operation, node, node_parent, node_position, more) {
				// operation can be 'create_node', 'rename_node', 'delete_node', 'move_node', 'copy_node' or 'edit'
				// in case of 'rename_node' node_position is filled with the new node name
				if (operation === 'delete_node') {
					if (!confirm('Are you sure?')) {
						return false;
					}
				}
				if (operation === 'create_node') {
					if(node_parent.original.nodeType == "evidence") {
						alert('You cannot create children of Evidence.');
						return false;
					}
				}
				if (operation === 'move_node' || operation === 'copy_node') {
					alert('Not allowed');
					return false;
				}
				return true;
			},
			'data' : {
				"url" : "do.php?_action=ajax_module_training&subaction=getTree&course_id="+window.phpCourseID,
				"plugins" : [ "wholerow", "checkbox", "types" ],
				"dataType" : "json"
			}
		},
		"types" : {
			"#" : {
				"max_children" : 1,
				"max_depth" : 2,
				"valid_children" : ["root"]
			}
		}
	}).on('create_node.jstree', function (e, data) {
			$.get("do.php?_action=ajax_module_training&subaction=create_node&course_id="+window.phpCourseID,
				{ 'id' : data.node.parent, 'position' : data.position, 'text' : data.node.text })
				.done(function (d) {
					data.instance.set_id(data.node, d.id);
				})
				.fail(function () {
					data.instance.refresh();
				});
		}).on('rename_node.jstree', function (e, data) {
			$.get("do.php?_action=ajax_module_training&subaction=rename_node&course_id="+window.phpCourseID,
				{ 'id' : data.node.id, 'text' : data.text })
				.fail(function () {
					data.instance.refresh();
				});
		}).on('delete_node.jstree', function (e, data) {
			$.get("do.php?_action=ajax_module_training&subaction=delete_node&course_id="+window.phpCourseID,
				{ 'id' : data.node.id })
				.fail(function () {
					data.instance.refresh();
				});
		});
});

function add_new_section_tracking_template()
{
	var section_title = prompt('Enter the title for new section');
	if(section_title === null)
	{
		return;
	}
	$.get("do.php?_action=ajax_module_training&subaction=add_new_section&course_id="+window.phpCourseID,
		{ 'text' : section_title })
		.done(function(){
			window.location.reload();
		})
		.fail(function () {
			alert('Something went wrong, try again!');
			window.location.reload();
		});
}

function updateCaseloadCheck(checkbox)
{
	console.log(checkbox.checked);
	var state = 0;
	if(checkbox.checked)
		state = 1;
	$.get("do.php?_action=ajax_module_training&subaction=update_caseload_check",
		{ 'state' : state })
		.done(function (d) {
			window.location.reload();
		})
		.fail(function () {
			window.location.reload();
		});
}

$(function(){
	$('#maani').DataTable({
		"paging": true,
		"lengthChange": true,
		"searching": true,
		"ordering": false,
		"info": false,
		"autoWidth": true
	});
	$('#tblCourseGroups, #tblCourseGroupsTGs, #tblTGLearners, #tblCohortLearners').DataTable({
		"paging": false,
		"lengthChange": false,
		"searching": true,
		"ordering": false,
		"info": false,
		"autoWidth": true
	});
});

function delete_training_group(tg_id)
{
	if(tg_id == '')
		return;

	if(!confirm('Are you sure?'))
		return;

	$.ajax({
		type: "POST",
		url: "do.php?_action=ajax_module_training&subaction=delete_training_group",
		data: {'course_id' : window.phpCourseID, 'tg_id' : tg_id },
		dataType: 'json'
	}).done(function (response) {
		if(response.status == 'success')
		{
			alert(response.message);
			window.location.href= 'do.php?_action=read_course_v2&subview=training_groups&id='+window.phpCourseID;
		}
		else
		{
			alert(response.message);
			window.location.reload();
		}
	})
		.fail(function (error) {
			window.location.reload();
	});
}

function delete_cohort(group_id)
{
	if(group_id == '')
		return;

	if(!confirm('Are you sure?'))
		return;

	$.ajax({
		type: "POST",
		url: "do.php?_action=ajax_module_training&subaction=delete_cohort",
		data: {'course_id' : window.phpCourseID, 'group_id' : group_id },
		dataType: 'json'
	}).done(function (response) {
			if(response.status == 'success')
			{
				alert(response.message);
				window.location.href= 'do.php?_action=read_course_v2&subview=groups&id='+window.phpCourseID;
			}
			else
			{
				alert(response.message);
				window.location.reload();
			}
		})
		.fail(function (error) {
			window.location.reload();
		});
}