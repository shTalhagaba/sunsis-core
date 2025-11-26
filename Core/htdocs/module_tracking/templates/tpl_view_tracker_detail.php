
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Programme Detail</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link href="/assets/adminlte/plugins/datatables/jquery.dataTables.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
        body {

        }
    </style>
</head>
<body class="table-responsive">
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">View Learners</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
            </div>
            <div class="ActionIconBar">
                <span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"></span>
                <span class="btn btn-sm btn-info fa fa-print" onclick="window.print();" title="Print-friendly view"></span>
                <!--<span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="exportToExcel('<?php /*echo $view->viewKey; */?>');" title="Export to .CSV file"></span>-->
                <span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="window.location.replace('do.php?_action=view_tracker_detail&id=<?php echo $tracker_id; ?>&subaction=export')" title="Export to .CSV file"></span>
                <span class="btn btn-sm btn-info fa  fa-check-square-o" onclick="showHideBlock('div_addLesson');" title="Choose columns you want to see"></span>
                <span class="btn btn-sm btn-info fa fa-refresh" onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"></span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <?php echo $view->getFilterCrumbs(); ?>
    </div>
</div>

<div class="content-wrapper">
    <div class="row">
        <form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" id="id" name="id" value="<?php echo $tracker_id; ?>" />
            <div id="div_addLesson" align="center" style="margin-top:10px;margin-bottom:20px;<?php echo 'display:none'; ?>">
                <table border="0" style="margin:10px;background-color:silver; border:1px solid black;padding:3px;">
                    <tr>
                        <td><?php echo HTML::checkBoxGrid('columns', $view->getColumns($link), $view->getSelectedColumnsNumbers($link), 9); ?></td>
                        <td>
                            <div style="margin:20px 0px 20px 10px">
                                <span class="button" onclick="changeColumns();"> Go </span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
    <div class="row">
        <div class="small" id="div_filters" style="display:none">
            <form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter" name="applyFilter">
                <input type="hidden" name="_action" value="view_tracker_detail" />
                <input type="hidden" id="filter_name" name="filter_name" value="" />
                <input type="hidden" id="filter_id" name="filter_id" value="<?php echo $filter_tracker->getValue(); ?>" />
                <input type="hidden" id="id" name="id" value="<?php echo $filter_tracker->getValue(); ?>" />

                <div id="filterBox" class="clearfix">
                    <fieldset>
                        <legend>General</legend>
                        <!--<div class="field float"><label>Programme:</label><?php /*echo $view->getFilterHTML('filter_tracker'); */?></div>-->
                        <div class="field float"><label>Surname:</label><?php echo $view->getFilterHTML('filter_surname'); ?></div>
                        <div class="field float"><label>First Name:</label><?php echo $view->getFilterHTML('filter_firstnames'); ?></div>
                        <div class="field float"><label>Employer:</label><?php echo $view->getFilterHTML('filter_employer'); ?></div>
                        <div class="field float"><label>Assessor:</label><?php echo $view->getFilterHTML('filter_assessor'); ?></div>
                        <div class="field float"><label>CRC Alert:</label><?php echo $view->getFilterHTML('filter_crc_alert'); ?></div>
                        <div class="field float"><label>BIL:</label><?php echo $view->getFilterHTML('filter_break_in_learning'); ?></div>
                        <div class="field float"><label>LAR:</label><?php echo $view->getFilterHTML('filter_lar'); ?></div>
                        <div class="field float"><label>Leaver:</label><?php echo $view->getFilterHTML('filter_leaver'); ?></div>
                        <div class="field float"><label>Delivery Location:</label><?php echo $view->getFilterHTML('filter_dl'); ?></div>
                        <div class="field float"><label>Added to LMS:</label><?php echo $view->getFilterHTML('filter_added_to_lms'); ?></div>
                        <div class="field float"><label>Learner Type:</label><?php echo $view->getFilterHTML('filter_learner_type'); ?></div>
                        <div class="field float"><label>Completed Learners:</label><?php echo $view->getFilterHTML('filter_op_status'); ?></div>
                        <div class="field float"><label>Training Status:</label><?php echo $view->getFilterHTML('filter_record_status'); ?></div>
                        <div class="field float"><label>Coordinator:</label><?php echo $view->getFilterHTML('filter_coordinator'); ?></div>
                    </fieldset>
                    <fieldset>
                        <div class="field"><label>Training record creation date between&nbsp;</label><?php echo $view->getFilterHTML('filter_from_tr_creation_date'); ?>&nbsp;and&nbsp;<?php echo $view->getFilterHTML('filter_to_tr_creation_date'); ?></div>
                        <div class="field"><label>Induction date between&nbsp;</label><?php echo $view->getFilterHTML('filter_from_induction_date'); ?>&nbsp;and&nbsp;<?php echo $view->getFilterHTML('filter_to_induction_date'); ?></div>
                        <div class="field"><label>48 hour call date between&nbsp;</label><?php echo $view->getFilterHTML('filter_from_48_hour_call'); ?>&nbsp;and&nbsp;<?php echo $view->getFilterHTML('filter_to_48_hour_call'); ?></div>
                        <div class="field"><label>Moc on demand 1 date between&nbsp;</label><?php echo $view->getFilterHTML('filter_from_moc_demand_1'); ?>&nbsp;and&nbsp;<?php echo $view->getFilterHTML('filter_to_moc_demand_1'); ?></div>
                        <div class="field"><label>Moc on demand 2 date between&nbsp;</label><?php echo $view->getFilterHTML('filter_from_moc_demand_2'); ?>&nbsp;and&nbsp;<?php echo $view->getFilterHTML('filter_to_moc_demand_2'); ?></div>
                    </fieldset>
                    <fieldset>
                        <legend>Options</legend>
                        <div class="field float"><label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></div>
                        <div class="field float"><label>Sort By:</label> <?php echo $view->getFilterHTML('order_by'); ?></div>
                    </fieldset>

                    <fieldset>
                        <input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetFilters();" value="Reset" />
                    </fieldset>
                </div>
            </form>
        </div>

    </div>
    <div class="row">
        <?php echo $view->render($link, $view->getSelectedColumns($link)); ?>
    </div>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>

<script language="JavaScript">
    function resetFilters()
    {
        var form = document.forms["applyFilter"];
        resetViewFilters(form);

        if ( $('#grid_filter_record_status').length )
        {
            var grid = document.getElementById('grid_filter_record_status');
            grid.resetGridToIndex(1);
        }
    }

    $(function () {

        $('#tblLearners').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": false,
            "autoWidth": true
        });

        $("tr.bg-warning").css("background-color", "#fcf8e3");

        $('img[title="Show calendar"]').hide();
        $(".DateBox").datepicker();

    });

    function getTrackerUnitSchedulingLog(tr_id, unit_ref)
    {
        var log = '';
        $.ajax({
            type:'GET',
            async: false,
            url:'do.php?_action=ajax_tracking&subaction=getTrackerUnitSchedulingLog&tr_id='+encodeURIComponent(tr_id)+'&unit_ref='+encodeURIComponent(unit_ref),
            success: function(response) {
                log = response;
            },
            error: function(data, textStatus, xhr){
                console.log(data.responseText);
            }
        });
        return log;
    }

    function setSchCode(tr_id, unit_ref, sch_code, learner_name)//, date_from, time_from, date_to, time_to)
    {
        var form_number = 1 + Math.floor(Math.random() * 1000);

        var html = '<div class="small"> <p><span class="text-bold">Learner Name:</span> ' + learner_name + '</p>';
        html += '<p><span class="text-bold">Unit Reference:</span> ' + unit_ref + '</p>';
        html += '<p></p>';
        html += '<form name="frm_sch_code'+form_number+'" action="do.php" method="get">';
        html += '<input type="hidden" name="subaction" value="setSchCode" />';
        html += '<input type="hidden" name="tr_id" value="'+tr_id+'" />';
        html += '<input type="hidden" name="unit_ref" value="'+unit_ref+'" />';
        html += '<table class="table small">';
        html += '<tr><td>Status</td><td><select  name="sch_code"  id="sch_code">';
        html += sch_code == 'I' ? '<option selected="selected" value="I">Invited</option>' : '<option value="I">Invited</option>';
        html += sch_code == 'B' ? '<option selected="selected" value="B">Booked</option>' : '<option value="B">Booked</option>';
        html += sch_code == 'R' ? '<option selected="selected" value="R">Required</option>' : '<option value="R">Required</option>';
        html += sch_code == 'U' ? '<option selected="selected" value="U">Uploaded</option>' : '<option value="U">Uploaded</option>';
        html += sch_code == 'P' ? '<option selected="selected" value="P">Pass</option>' : '<option value="P">Pass</option>';
        html += sch_code == 'MC' ? '<option selected="selected" value="MC">Merit/Credit</option>' : '<option value="MC">Merit/Credit</option>';
        html += sch_code == 'D' ? '<option selected="selected" value="D">Distinction</option>' : '<option value="D">Distinction</option>';
        html += sch_code == 'NR' ? '<option selected="selected" value="NR">Not Required</option>' : '<option value="NR">Not Required</option>';
	html += sch_code == 'RP' ? '<option selected="selected" value="RP">Result Pending</option>' : '<option value="RP">Result Pending</option>';
        html += '</select></td></tr>';
        html += '<tr><td>Comments:</td><td><textarea rows="3" style="width: 100%;" name="sch_comments" id="sch_comments"></textarea> </td></tr>';
        html += '</table>';
        html += '</form></div>';

        html += '<small>' + getTrackerUnitSchedulingLog(tr_id, unit_ref) + '</small>';

        var newDiv = $(document.createElement('div'));
        $(newDiv).html(html);
        $(newDiv)
            .dialog({
                title: 'Select status',
                resizable: true,
                height:400,
                width:450,
                modal: true,
                buttons: {
                    'Save': function() {
                        if($('form[name="frm_sch_code'+form_number+'"] textarea[name="sch_comments"]').val().trim() == '')
                        {
                            alert("Please enter Comments.");
                            return false;
                        }
                        $.ajax({
                            type:'POST',
                            url:'do.php?_action=ajax_tracking',
                            data: $('form[name="frm_sch_code'+form_number+'"]').serialize(),
                            async: false,
                            success: function(data, textStatus, xhr) {
                                //alert('Record updated successfully');
                                window.location.reload();
                            },
                            error: function(data, textStatus, xhr){
                                alert(data.responseText);
                                console.log(data.responseText);
                            }
                        });
                    },
                    Cancel: function() {
                        $(this).dialog('close');
                    }
                }
            }).css("background", "#FFF");
    }

    function changeColumns()
    {
        var viewName = "<?php echo $view->getViewName()?>";
        var $checkboxes = $('input[type="checkbox"][name^="columns"]:not(:checked)'); // find unchecked boxes
        var columns = new Array();
        for(var i = 0; i < $checkboxes.length; i++)
        {
            var obj = {
                view:viewName,
                colum:$checkboxes[i].parentNode.title,
                visible:0
            };
            columns.push(obj);
        }
        var json = JSON.stringify(columns);
        var post = "json=" + encodeURIComponent(json) + "&view=" + encodeURIComponent(viewName);
        var client = ajaxRequest("do.php?_action=ajax_save_columns", post);
        if(client){
            window.location.reload();
        }
    }

    function div_filter_crumbs_onclick(div)
    {
        showHideBlock(div);
        showHideBlock('div_filters');
    }

    function removeTrackerUnitSchLog(sch_id)
    {
        if(sch_id == '')
            return;

        if(!confirm("Are you sure, you want to remove this entry?"))
        {
            return;
        }

        $.ajax({
            type:'POST',
            url:'do.php?_action=ajax_tracking&subaction=removeTrackerUnitSchLog',
            data: {sch_id: sch_id},
            async: false,
            success: function(data, textStatus, xhr) {
                //alert('Record updated successfully');
                window.location.reload();
            },
            error: function(data, textStatus, xhr){
                alert(data.responseText);
                console.log(data.responseText);
            }
        });
    }

</script>

</body>
</html>