
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Operations Schedule</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/calendar/css/calendar.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		body {
		//overflow: scroll;
		}
	</style>
</head>
<body>
<div class="row">
	<div class="col-sm-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Operations Schedule</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
			</div>
			<div class="ActionIconBar">
				<span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"></span>
				<span class="btn btn-sm btn-info fa fa-refresh" onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"></span>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<?php $_SESSION['bc']->render($link); ?>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div id="div_filters" style="display:none">

			<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter" name="frmFilters">
				<input type="hidden" name="_action" value="view_operations_schedule" />

				<div id="filterBox" class="clearfix">
					<fieldset>
						<div class="field float">
							<label>Trainers:</label>
							<div id="grid_filter_trainers" style="height: 250px;width: 300px;overflow-y: scroll; overflow-x: scroll; margin-left: 5%;">
								<table cellspacing="1" cellpadding="0">
									<tbody>
										<?php 
										$options = DAO::getResultset($link, "SELECT DISTINCT users.id, CONCAT(firstnames, ' ', surname) AS user_name FROM users INNER JOIN sessions ON users.id = sessions.personnel ORDER BY firstnames", DAO::FETCH_ASSOC);
										$selected_trainers = $filter_trainers != '' ? explode(",", $filter_trainers) : [];
										foreach($options AS $op)
										{
											$_ch = in_array($op['id'], $selected_trainers) ? " checked " : "";
											echo '<tr>';
											echo '<td title="" style="padding-right:20px">';
											echo '<input type="checkbox" ' . $_ch . ' name="filter_trainers[]" value="' . $op['id'] . '"> &nbsp;' . $op['user_name'];
											echo '</td>';
											echo '</tr>';
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="field float">
							<label>Programme:</label>
							<?php 
							$programmes_list = DAO::getResultset($link, "SELECT title, title FROM op_trackers WHERE title NOT LIKE '%no live learners%' ORDER BY title");
							echo HTML::select('filter_programme', $programmes_list, $filter_programme, true); 
							?>
						</div>
					</fieldset>
					<fieldset>
						<input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms['frmFilters']);" value="Reset" />
					</fieldset>
				</div>
			</form>
		</div>
	</div>
</div>

<p></p>

<div class="row">
	<div class="col-sm-2">
		<!--<span class="btn btn-sm btn-primary" onclick="window.location.replace('do.php?_action=edit_op_session');"><i class="fa fa-plus"></i> New Event</span>-->
	</div>
	<div class="col-sm-10 page-header">
		<div class="pull-right form-inline">
			<div class="btn-group">
				<button class="btn btn-primary" data-calendar-nav="prev"><< Prev</button>
				<button class="btn btn-default" data-calendar-nav="today">Today</button>
				<button class="btn btn-primary" data-calendar-nav="next">Next >></button>
			</div>
			<div class="btn-group">
				<button class="btn btn-warning" data-calendar-view="year">Year</button>
				<button class="btn btn-warning active" data-calendar-view="month">Month</button>
				<button class="btn btn-warning" data-calendar-view="week">Week</button>
				<button class="btn btn-warning" data-calendar-view="day">Day</button>
			</div>
		</div>
		<h2></h2>
	</div>
</div>

<div class="row">
	<div class="col-sm-4">
		<span class="text-bold">Events</span><br>
		<ul id="eventlist" class="nav nav-list"></ul>
	</div>
	<div class="col-sm-8 pull-right">
		<div id="calendar"></div>
	</div>
</div>

<div class="modal fade" id="events-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h2 class="modal-title">Event</h2>
			</div>
			<div class="modal-body" style="height: auto">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" id="btnEditSession" class="btn btn-primary pull-left"><i class="fa fa-folder-open"></i> View Detail</button>
			</div>
		</div>
	</div>
</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/adminlte/plugins/calendar/components/underscore/underscore-min.js"></script>
<script type="text/javascript" src="/assets/adminlte/plugins/calendar/js/calendar.js"></script>
<script type="text/javascript" src="common.js"></script>
<script type="text/javascript">

	var options = {
		weekbox: true,
		merge_holidays:true,
		display_week_numbers: true,
		modal: "#events-modal",
		modal_type : "ajax",
		modal_title : function (e) { return e.event_type },
		/*events_source: 'https://localhost/events.json.php',*/
		events_source: '/do.php?_action=ajax_operations_schedule_calendar<?php echo $filter_trainers != "" ? "&filter_trainers=" . $filter_trainers : ""; ?><?php echo $filter_programme != "" ? "&filter_programme=" . $filter_programme : ""; ?>',
		view: 'month',
		tmpl_path: "/assets/adminlte/plugins/calendar/tmpls/",
		tmpl_cache: false,
		day: '<?php echo isset($calendar_view_start_date)?$calendar_view_start_date:date('Y-m-d'); ?>',
		onAfterModalShown: function(event) {
			$("#btnEditSession").click(function(){ window.location.replace('do.php?_action=manage_session&id='+event.id) });
		},
		onAfterEventsLoad: function(events) {
			if(!events) {
				return;
			}
			var list = $('#eventlist');
			list.html('');

			$.each(events, function(key, val) {
				var html = '<ul>';
				html += '<li><b>Programme:</b> ' + val.tracker + '</li>';
				html += '<li><b>Trainer:</b> ' + val.trainer + '</li>';
				html += '<li><b>Duration:</b> ' + val.duration + '</li>';
				html += '<li><b>Max. Spaces:</b> ' + val.max_learners + '</li>';
				html += '<li><b>Remaining Spaces:</b> ' + val.available + '</li>';
				html += '</ul>';
				$(document.createElement('li'))
					.html('<a href="do.php?_action=manage_session&id=' + val.id + '">' + ' <b>[' + val.unit_ref + ']</b>' + html + '</a>')
					.appendTo(list);
			});
		},
		onAfterViewLoad: function(view) {
			$('.page-header h2').text(this.getTitle());
			$('.btn-group button').removeClass('active');
			$('button[data-calendar-view="' + view + '"]').addClass('active');
			var start = stringToDate(formatDate(this.options.position.start));
			var end = stringToDate(formatDate(this.options.position.end));
			var url = "do.php?_action=view_operations_schedule_tabular&_reset=1&ViewOperationsScheduleTabular_filter_from_start_date="+encodeURIComponent(formatDateGB(start))+"&ViewOperationsScheduleTabular_filter_to_end_date="+encodeURIComponent(formatDateGB(end));
			$("#btnTabularView").attr("onclick","window.location.href='"+url+"';");
		},
		classes: {
			months: {
				general: 'label'
			}
		}
	};

	var calendar = $('#calendar').calendar(options);

	$('.btn-group button[data-calendar-nav]').each(function() {
		var $this = $(this);
		$this.click(function() {
			calendar.navigate($this.data('calendar-nav'));
		});
	});

	$('.btn-group button[data-calendar-view]').each(function() {
		var $this = $(this);
		$this.click(function() {
			calendar.view($this.data('calendar-view'));
		});
	});

/*
	$('#first_day').change(function(){
		var value = $(this).val();
		value = value.length ? parseInt(value) : null;
		calendar.setOptions({first_day: value});
		calendar.view();
	});
*/

	$('#events-modal .modal-header, #events-modal .modal-footer').click(function(e){
		//e.preventDefault();
		//e.stopPropagation();
	});

	function div_filter_crumbs_onclick(div)
	{
		showHideBlock(div);
		showHideBlock('div_filters');
	}

</script>
</body>
</html>