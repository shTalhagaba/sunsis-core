
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Induction Dashboard</title>
    <link rel="stylesheet" href="module_tracking/css/common.css?n=<?php echo time(); ?>" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>

        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>

</head>

<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Induction Dashboard</div>
            <div class="ButtonBar"></div>
            <div class="ActionIconBar"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>

<p></p>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h1 class="box-title"> <label class="text-center" id="lblStatsMonthName"><?php echo $current_month; ?></label></h1>
                    <div class="box-tools pull-right">
                        <input name="statsMonth" id="statsMonth" class="date-picker" value="<?php echo date('F Y'); ?>" />
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">

                            <div class="box box-info" id="divPanels">
                                <div class="box-header with-border">
                                    <h2 class="box-title"><span class="fa fa-calendar"></span> Statistics</h2>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" id="btnPanels"><i class="fa fa-print"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body" id="divInductionDashPanels"></div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">

                            <div class="box box-info table-responsive" id="divByAgeBand">
                                <div class="box-header with-border">
                                    <h1 class="box-title"><span class="fa fa-calendar"></span> Capacity Breakdown by Programme</h1>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" id="btnByAgeBand"><i class="fa fa-print"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body" id="divInductionTable"></div>
                            </div>

                            <!--<div class="box box-info">
                                <div class="box-header with-border">
                                    <h1 class="box-title"><span class="fa fa-calendar"></span> Learners By Employers</h1>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body" id="divInductionByEmployer" style="max-height: 350px; overflow-y: scroll;"></div>
                            </div>-->

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="box box-info" id="divQuarterlyInfo">
                <div class="box-header with-border">
                    <h1 class="box-title"><span class="fa fa-calendar"></span> Quarterly Information</h1>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" id="btnQuarterlyInfo"><i class="fa fa-print"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <?php echo $this->showQuarterlyCompletion($link); ?>
                </div>
            </div>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h1 class="box-title"><span class="fa fa-calendar"></span> Reports</h1>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_induction_reports&subview=sales_induction_data'">Sales Induction Data</span>
                    <!--					<span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_induction_reports&subview=induction_assessor_prep'">Induction Assessor Prep</span>-->
                    <span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_induction_reports&_reset=1&subview=holding_inductions'">Holding Inductions</span>
                    <!--					<span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_induction_reports&subview=to_be_arranged'">To be arranged update</span>-->
                    <!--					<span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_induction_reports&subview=completed_vs_live'">Completed VS Live</span>-->
                </div>
            </div>
            <br>
            <div class="col-lg-6 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h1><?php echo count($withdrawn_restarts); ?></h1>
                        <p>Withdrawn and then Restarts</p>
                    </div>
                    <div class="icon"><i class="fa fa-users"></i> </div>
                    <a href="#" onclick="showWithdrawnRestartsDetail('<?php echo implode(',', $withdrawn_restarts); ?>');" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
                </div>
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h1><?php echo $learner_transfers; ?></h1>
                        <p>Learner Transfers</p>
                    </div>
                    <div class="icon"><i class="fa fa-users"></i> </div>
		    <a href="do.php?_action=induction_home&_reset=1&selected_tab=tab6&view=view_ViewInduction_All&view_ViewInduction_Allfilter_learner_type%5B%5D=4" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!--<div class="box box-primary" id="divAssessorInfo">
                <div class="box-header with-border">
                    <h2 class="box-title"><span class="glyphicon glyphicon-stats"></span> Assigned Assessors - Learners</h2>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" id="btnAssessorInfo"><i class="fa fa-print"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="chart-responsive" id="panelLearnersByAssessors"></div>
                        </div>
                    </div>
                </div>
            </div>-->

        </div>

    </div>

</div>

<form method="get" action="/do.php" id="frmWithdrawnRestartFilters" name="frmWithdrawnRestartFilters">
    <input type="hidden" name="_action" value="view_withdrawn" />
    <input type="hidden" name="_reset" value="1" />
    <input type="hidden" name="filter_tr_ids" value="" />
</form>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js?n=<?php echo time(); ?>" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/plugins/chartjs/ChartNew.js"></script>
<script src="/assets/adminlte/plugins/html2canvas/html2canvas.js"></script>
<script src="/assets/adminlte/plugins/FileSaver/FileSaver.js"></script>

<script language="JavaScript">
    $(function(){

        $("#btnPanels").click(function() {
            html2canvas($("#divPanels"), {
                onrendered: function(canvas) {
                    //theCanvas = canvas;
                    //document.body.appendChild(canvas);

                    canvas.toBlob(function(blob) {
                        saveAs(blob, "DashPanels.png");
                    });
                }
            });
        });

        $("#btnByAgeBand").click(function() {
            html2canvas($("#divByAgeBand"), {
                onrendered: function(canvas) {
                    //theCanvas = canvas;
                    //document.body.appendChild(canvas);

                    canvas.toBlob(function(blob) {
                        saveAs(blob, "ByAgeBand.png");
                    });
                }
            });
        });

        $("#btnQuarterlyInfo").click(function() {
            html2canvas($("#divQuarterlyInfo"), {
                onrendered: function(canvas) {
                    canvas.toBlob(function(blob) {
                        saveAs(blob, "QuarterlyInfo.png");
                    });
                }
            });
        });

        $("#btnAssessorInfo").click(function() {
            html2canvas($("#divAssessorInfo"), {
                onrendered: function(canvas) {
                    canvas.toBlob(function(blob) {
                        saveAs(blob, "AssessorInfo.png");
                    });
                }
            });
        });

        $('.date-picker').datepicker( {

            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'MM yy',

            onSelect: function(dateText) {
                $('#lblStatsMonthName').html(this.value);
                $('#divInductionDashPanels').html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i> <img id="loading" src="images/loading.gif" alt="Loading" /></div>');
                $('#divInductionTable').html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i> <img id="loading" src="images/loading.gif" alt="Loading" /></div>');
                $('#divInductionByEmployer').html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i> <img id="loading" src="images/loading.gif" alt="Loading" /></div>');
                var request = ajaxRequest('do.php?_action=induction_dashboard&subaction=showInductionTable&statsMonth=' + encodeURIComponent(this.value), null, null, showInductionTableCallback);
                var request = ajaxRequest('do.php?_action=induction_dashboard&subaction=showInductionDashPanels&statsMonth=' + encodeURIComponent(this.value), null, null, showInductionDashPanelsCallback);
                var request = ajaxRequest('do.php?_action=induction_dashboard&subaction=showInductionByEmployer&statsMonth=' + encodeURIComponent(this.value), null, null, showInductionByEmployerCallback);
            }
        });

        var request = ajaxRequest('do.php?_action=induction_dashboard&subaction=showInductionTable&statsMonth=<?php echo $current_month; ?>', null, null, showInductionTableCallback);
        var request = ajaxRequest('do.php?_action=induction_dashboard&subaction=showInductionDashPanels&statsMonth=<?php echo $current_month; ?>', null, null, showInductionDashPanelsCallback);
        var request = ajaxRequest('do.php?_action=induction_dashboard&subaction=showInductionByEmployer&statsMonth=<?php echo $current_month; ?>', null, null, showInductionByEmployerCallback);

        //loadBarLearnersByAssessors();

    });

    var randomColorGenerator = function () {
        return '#' + (Math.random().toString(16) + '0000000').slice(2, 8);
    };

    function showInductionTableCallback(response, error)
    {
        if(!error)
        {
            $('#divInductionTable').html(response.responseText);
        }
    }
    function showInductionDashPanelsCallback(response, error)
    {
        if(!error)
        {
            $('#divInductionDashPanels').html(response.responseText);
        }
    }
    function showInductionByEmployerCallback(response, error)
    {
        if(!error)
        {
            $('#divInductionByEmployer').html(response.responseText);
        }
    }

    function loadBarLearnersByAssessors()
    {
        $.ajax({
            type:'GET',
            url:'do.php?_action=induction_dashboard&subaction=getStatsLearnersByAssessors',
            dataType: 'json',
            beforeSend: function(){
                $("#panelLearnersByAssessors").html("<h5 class='text-bold'><i class=\"fa fa-refresh fa-spin\"></i> loading graph ...</h5>");
            },
            success: function(response) {
                response.options.mouseDownLeft = fnAddLinksToGraph;
                $("#panelLearnersByAssessors").html('<canvas id="barLearnersByAssessors" height="750" width="550" style="width: 100%;"></canvas>');
                var myLine1 = new Chart(document.getElementById("barLearnersByAssessors").getContext("2d")).StackedBar(response.data, response.options);
            },
            error: function(data, textStatus, xhr){
                console.log(data.responseText);
            }
        });
    }

    function fnAddLinksToGraph(event,ctx,config,data,other)
    {
        if(other.v1 == "On Programme")
            window.location.href = "do.php?_action=induction_dashboard&subaction=navToTRSummary&comp_due=0&assessor="+encodeURIComponent(other.v2);
        if(other.v1 == "Completions Due")
            window.location.href = "do.php?_action=induction_dashboard&subaction=navToTRSummary&comp_due=1&assessor="+encodeURIComponent(other.v2);
        if(other.v1 == "Newly Assigned")
            window.location.href = "do.php?_action=induction_dashboard&subaction=navToInduction&assessor="+encodeURIComponent(other.v2);
    }

    function showWithdrawnRestartsDetail(ids)
    {
        var frmFilters = document.forms["frmWithdrawnRestartFilters"];
        frmFilters.filter_tr_ids.value = ids;

        frmFilters.submit();
    }
</script>

</body>
</html>
