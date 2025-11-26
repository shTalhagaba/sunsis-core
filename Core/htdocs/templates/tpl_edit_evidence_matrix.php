<?php /* @var $tr TrainingRecord */ ?>
<?php /* @var $op_details TROperationsVO */ ?>
<?php /* @var $inductee Inductee */ ?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Evidence Matrix</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/chosen/bootstrap-chosen.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote-bs3.css">
    <link href="/assets/adminlte/plugins/datatables/jquery.dataTables.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <style>
        #home_postcode{text-transform:uppercase}
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
        .disabled{
            pointer-events:none;
            opacity:0.4;
        }
        .loading-image{background-image: url('images/progress-animations/loading51.gif');background-color: rgba(255,255,255,0.5);background-position: center center;background-repeat: no-repeat; filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#50FFFFFF,endColorstr=#50FFFFFF);width: 100%; height: 100%; position: fixed; top: 0; left: 0; z-index: 9999;}
    </style>
</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Evidence Matrix</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
            </div>
            <div class="ActionIconBar">

            </div>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>
<br>

<div class="row">
<div class="col-md-12">
<div class="nav-tabs-custom">
<ul class="nav nav-tabs">
    <li class="active"><a href="#tab1" data-toggle="tab">Evidences</a></li>
    <li><a href="#tab2" data-toggle="tab">Projects</a></li>
</ul>
<div class="tab-content">

<div class="active tab-pane" id="tab1">
    <div class="box">
        <div class="box-header">
            <span class="btn btn-primary btn-sm " onclick="$('#EvidenceModal').modal('show');"><i class="fa fa-plus"></i> Add New</span>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <?php
                $competencies = DAO::getResultset($link, "SELECT
                                id, description,
                                (SELECT COUNT(*) FROM evidence_criteria INNER JOIN courses ON courses.id = evidence_criteria.course_id WHERE course_id = '$course_id' AND competency = lookup_assessment_plan_log_mode.id AND courses.id = evidence_criteria.course_id) AS evidence_count
                                FROM lookup_assessment_plan_log_mode WHERE framework_id = (SELECT framework_id FROM courses WHERE id = $course_id);", DAO::FETCH_ASSOC);

                echo '<table class="table table-bordered"><tr class="bg-primary"><th>Competency</th><th>Criteria</th><th>Actions</th></tr>';
                foreach($competencies AS $competency)
                {
                    $count = ($competency['evidence_count']==0)?1:$competency['evidence_count'];
                    echo '<tr><td align="center"  style = "background-color: palegreen; vertical-align: middle;" rowspan=' . $count . '><b>' . $competency['description'] . '</b></td>';

                    $evidences = DAO::getResultset($link, "SELECT * FROM evidence_criteria WHERE course_id = '{$course_id}' and competency = '{$competency['id']}' order by sequence", DAO::FETCH_ASSOC);

                    foreach($evidences as $evidence)
                    {
                        echo '<td>' . $evidence['criteria'] . '</td>';
                        echo '<td><span class="btn btn-primary btn-xs" onclick="prepareEvidenceModalForEdit(\'' . $evidence['id'] . '\');"><i class="fa fa-edit"></i> Edit</span>&nbsp;';
                        echo '<span class="btn btn-primary btn-xs" onclick="deleteEvidence(\'' . $evidence['id'] . '\');"><i class="fa fa-eraser"></i> Delete</span></td></tr>';
                    }

                    echo '</tr>';
                }

                echo '</table>';
                ?>
            </div>
        </div>
    </div>
</div>

<div class="tab-pane" id="tab2">
    <div class="box">
        <div class="box-header">
            <span class="btn btn-primary btn-sm " onclick="$('#ProjectModal').modal('show');"><i class="fa fa-plus"></i> Add New</span>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <?php
                $result = DAO::getResultset($link, "SELECT * FROM evidence_project WHERE course_id = '{$course_id}'", DAO::FETCH_ASSOC);
                echo '<table class="table table-bordered"><tr  class="bg-primary"><th>Task</th><th>Actions</th></tr>';
                foreach($result AS $row)
                {
                    echo '<tr>';
                    echo '<td>' . $row['project'] . '</td>';
                    echo '<td><span class="btn btn-primary btn-xs" onclick="prepareProjectModalForEdit(\'' . $row['id'] . '\');"><i class="fa fa-edit"></i> Edit</span>&nbsp;';
                    echo '<span class="btn btn-primary btn-xs" onclick="deleteProject(\'' . $row['id'] . '\');"><i class="fa fa-eraser"></i> Delete</span></td></tr>';
                    echo '</tr>';
                }
                echo '</table>';
                ?>
            </div>
        </div>
    </div>
</div>

<div class="tab-pane" id="tab3">
    <div class="box">
        <div class="box-header">
            <span class="btn btn-primary btn-sm " onclick="Save()"><i class="fa fa-plus"></i> Save</span>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                <tr  class="bg-primary"><th>Evidence Criteria</th>
                <?php
                $projects = DAO::getResultset($link, "SELECT * FROM evidence_project WHERE course_id = '{$course_id}'", DAO::FETCH_ASSOC);
                foreach($projects as $project)
                {
                    echo "<th style='text-align:center'>" . $project['project'] . "</th>";
                }
                echo "</tr>";
                $evidences = DAO::getResultset($link, "SELECT * FROM evidence_criteria WHERE course_id = '{$course_id}'", DAO::FETCH_ASSOC);
                foreach($evidences as $evidence)
                {
                    echo "<tr><td style='text-align:left'>" . $evidence['criteria'] . "</td>";
                    foreach($projects as $project)
                        echo "<td style='text-align:center'>" . "<input type = checkbox>" . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                ?>
            </div>
        </div>
    </div>
</div>


</div>
</div>

</div>

</div>

<div class="loading-image" style="display: none;"></div>

<div class="modal fade" id="EvidenceModal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title text-bold">Details</h5>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" name="frmEvidence" id="frmEvidence" method="post" action="do.php?_action=save_evidence_matrix">
                    <input type="hidden" name="formName" value="frmEvidence" />
                    <input type="hidden" name="entity" value="evidence" />
                    <input type="hidden" id = "id" name="id" value="" />
                    <input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label fieldLabel_optional" for ="competency">Competency:</label>
                                <div class="col-sm-12">
                                    <?php
                                    $competencies = DAO::getResultSet($link, "SELECT id, description, NULL FROM lookup_assessment_plan_log_mode WHERE framework_id IN (SELECT framework_id FROM courses WHERE id = '$course_id') ORDER BY description");
                                    echo HTML::selectChosen('competency', $competencies, null, false); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for ="task_comments">Criteria:</label>
                        <textarea class="form-control" name="evidence_criteria" id="evidence_criteria" rows="1" style="width: 100%;"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left btn-md" onclick="$('#EvidenceModal').modal('hide');">Cancel</button>
                <button type="button" id="btnEvidenceModalSave" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ProjectModal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title text-bold">Details</h5>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" name="frmProject" id="frmProject" method="post" action="do.php?_action=save_evidence_matrix">
                    <input type="hidden" name="formName" value="frmProject" />
                    <input type="hidden" name="entity" value="project" />
                    <input type="hidden" name="id"  id="id" value="" />
                    <input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />
                    <div class="control-group">
                        <label class="control-label" for ="task_comments">Task:</label>
                        <textarea class="form-control" name="project_criteria" id="project_criteria" rows="1" style="width: 100%;"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left btn-md" onclick="$('#ProjectModal').modal('hide');">Cancel</button>
                <button type="button" id="btnProjectModalSave" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
    </div>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/assets/adminlte/plugins/summernote/summernote.min.js"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker.js"></script>

<script language="JavaScript">

function deleteEvidence(id)
{
    confirmation("Do you want to remove this criteria?").then(function (answer) {
        var ansbool = (String(answer) == "true");
        if(ansbool){

            var client = ajaxRequest('do.php?_action=ajax_evidence&subaction=delete_evidence&id='+id);
            location.reload();
        }
    });
}

function deleteProject(id)
{
    confirmation("Do you want to remove this task?").then(function (answer) {
        var ansbool = (String(answer) == "true");
        if(ansbool){

            var client = ajaxRequest('do.php?_action=ajax_evidence&subaction=delete_project&id='+id);
            location.reload();
        }
    });
}

function prepareEvidenceModalForEdit(id)
{
    var form = document.forms['frmEvidence'];
    $.ajax({
        type:'GET',
        dataType: 'json',
        url:'do.php?_action=ajax_evidence&subaction=get_evidence&id='+id,
        async: false,
        success: function(data) {
            $.each( data, function( key, value ) {
                 $('#frmEvidence #'+key).val(value);
            });
            $('#EvidenceModal').modal('show');
        },
        error: function(data, textStatus, xhr){
            console.log(data.responseText);
        }
    });
}

function prepareProjectModalForEdit(id)
{
    var form = document.forms['frmProject'];
    $.ajax({
        type:'GET',
        dataType: 'json',
        url:'do.php?_action=ajax_evidence&subaction=get_project&id='+id,
        async: false,
        success: function(data) {
            $.each( data, function( key, value ) {
                $('#frmProject #'+key).val(value);
            });
            $('#ProjectModal').modal('show');
        },
        error: function(data, textStatus, xhr){
            console.log(data.responseText);
        }
    });
}


$(function() {
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        yearRange: 'c-50:c+50'
    });



    $("button#btnEvidenceModalSave").click(function(){
        if(validateForm(document.forms['frmEvidence']) == false)
        {
            return;
        }
        $("#frmEvidence").submit();
    });

    $("button#btnProjectModalSave").click(function(){
        if(validateForm(document.forms['frmProject']) == false)
        {
            return;
        }
        $("#frmProject").submit();
    });


    $(".datepicker").addClass("form-control");

    $('input[class=radioMainContact]').iCheck({checkboxClass: 'icheckbox_flat-red'});

    $('input[class=radioRedLight]').iCheck({radioClass: 'iradio_square-red', increaseArea: '20%'});
    $('input[class=radioOrangeLight]').iCheck({radioClass: 'iradio_square-orange', increaseArea: '20%'});
    $('input[class=radioYellowLight]').iCheck({radioClass: 'iradio_square-yellow', increaseArea: '20%'});

});

function confirmation(question) {
    var defer = $.Deferred();
    $('<div></div>')
            .html(question)
            .dialog({
                autoOpen: true,
                modal: true,
                title: 'Confirmation',
                buttons: {
                    "Yes": function () {
                        defer.resolve("true");//this text 'true' can be anything. But for this usage, it should be true or false.
                        $(this).dialog("close");
                    },
                    "No": function () {
                        defer.resolve("false");//this text 'false' can be anything. But for this usage, it should be true or false.
                        $(this).dialog("close");
                    }
                },
                close: function () {
                    //$(this).remove();
                    $(this).dialog('destroy').remove()
                }
            });
    return defer.promise();
};

</script>

</body>
</html>