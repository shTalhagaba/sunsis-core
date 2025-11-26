<!DOCTYPE html>
<html lang="en">
<head>
    <title>Qualification Achievement Rates</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script src="/common.js?n=<?php echo time(); ?>" type="text/javascript"></script>

    <script src="https://code.highcharts.com/7.0.0/highcharts.js"></script>
    <script src="https://code.highcharts.com/7.0.0/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/7.0.0/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/7.0.0/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/7.0.0/modules/solid-gauge.js"></script>

    <script language = "javascript">
    function expor(detail)
    {
        window.location.href='do.php?_action=export_success_rates&trs='+detail;
    }


    function calculate()
    {
	    $.ajax({
		    type:'GET',
		    url:'do.php?_action=success_rates',
		    beforeSend: function(){
			    $('#tdCalculate').html('<img id="loading"  src="images/loading.gif" alt="Loading" />');
		    },
		    success: function() {
			    window.location.reload();
		    },
		    error: function(){
			    console.log('error');
			    $('#tdCalculate').html('<div class="panel-heading" style="background: darkgrey; cursor: pointer;" onclick="calculate()"><b>Recalculate</b></div></td><td width="10px"></td><td class = ""> Last calculated on <br><?php echo DAO::getSingleValue($link,"select value from configuration where entity = 'QAR'"); ?>');
		    }
	    });
    }
    </script>


    <style type="text/css">
        .disabledbutton {
            pointer-events: none;
            opacity: 0.4;
        }

        table.table1{
            font-family: "Trebuchet MS", sans-serif;
            font-size: 16px;
            font-weight: bold;
            line-height: 1.4em;
            font-style: normal;
            border-collapse:separate;
        }
        .table1 thead th{
            padding:15px;
            color:#fff;
            text-shadow:1px 1px 1px #568F23;
            border:1px solid #93CE37;
            border-bottom:3px solid #9ED929;
            background-color:#9DD929;
            background:-webkit-gradient(
                linear,
                left bottom,
                left top,
                color-stop(0.02, rgb(123,192,67)),
                color-stop(0.51, rgb(139,198,66)),
                color-stop(0.87, rgb(158,217,41))
            );
            background: -moz-linear-gradient(
                center bottom,
                rgb(123,192,67) 2%,
                rgb(139,198,66) 51%,
                rgb(158,217,41) 87%
            );
            -webkit-border-top-left-radius:5px;
            -webkit-border-top-right-radius:5px;
            -moz-border-radius:5px 5px 0px 0px;
            border-top-left-radius:5px;
            border-top-right-radius:5px;
        }
        .table1 thead th:empty{
            background:transparent;
            border:none;
        }
        .table1 tbody th{
            color:#fff;
            text-shadow:1px 1px 1px #568F23;
            background-color:#9DD929;
            border:1px solid #93CE37;
            border-right:3px solid #9ED929;
            padding:0px 10px;
            background:-webkit-gradient(
                linear,
                left bottom,
                right top,
                color-stop(0.02, rgb(158,217,41)),
                color-stop(0.51, rgb(139,198,66)),
                color-stop(0.87, rgb(123,192,67))
            );
            background: -moz-linear-gradient(
                left bottom,
                rgb(158,217,41) 2%,
                rgb(139,198,66) 51%,
                rgb(123,192,67) 87%
            );
            -moz-border-radius:5px 0px 0px 5px;
            -webkit-border-top-left-radius:5px;
            -webkit-border-bottom-left-radius:5px;
            border-top-left-radius:5px;
            border-bottom-left-radius:5px;
        }
        .table1 tfoot td{
            color: #9CD009;
            font-size:32px;
            text-align:center;
            padding:10px 0px;
            text-shadow:1px 1px 1px #444;
        }
        .table1 tfoot th{
            color:#666;
        }
        .table1 tbody td{
            padding:10px;
            text-align:center;
            background-color:#DEF3CA;
            border: 2px solid #E7EFE0;
            -moz-border-radius:2px;
            -webkit-border-radius:2px;
            border-radius:2px;
            color:#666;
            text-shadow:1px 1px 1px #fff;
        }

        td.label1 {
            padding: 5px 10px;
            -webkit-border-radius: 8px;
            -moz-border-radius: 8px;
            border-radius: 8px;
            -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
            -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
            box-shadow: rgba(0,0,0,1) 0 1px 0;
            color: black;
            font-size: 14px;
            font-family: Georgia, serif;
            text-decoration: none;
            vertical-align: middle;
        }

        td.label2 {
            border-top: 1px solid #96d1f8;
            background: #65a9d7;
            background: -webkit-gradient(linear, left top, left bottom, from(#3e779d), to(#65a9d7));
            background: -webkit-linear-gradient(top, #3e779d, #65a9d7);
            background: -moz-linear-gradient(top, #3e779d, #65a9d7);
            background: -ms-linear-gradient(top, #3e779d, #65a9d7);
            background: -o-linear-gradient(top, #3e779d, #65a9d7);
            padding: 5px 10px;
            -webkit-border-radius: 8px;
            -moz-border-radius: 8px;
            border-radius: 8px;
            -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
            -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
            box-shadow: rgba(0,0,0,1) 0 1px 0;
            text-shadow: rgba(0,0,0,.4) 0 1px 0;
            color: black;
            font-size: 14px;
            font-family: Georgia, serif;
            text-decoration: none;
            vertical-align: middle;
        }


    </style>

    <style>
            /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
        .row.content {height: 550px}

            /* Set gray background color and 100% height */
        .sidenav {
            background-color: #f1f1f1;
            height: 100%;
        }

            /* On small screens, set height to 'auto' for the grid */
        @media screen and (max-width: 767px) {
            .row.content {height: auto;}
        }

        .panel-body{
            text-align: center;
            font-size: larger;
        }
    </style>
</head>
<body>
<?php
$filename = SystemConfig::getEntityValue($link, "logo");
$filename = $filename ? $filename : 'perspective.png';
?>

<div class="container-fluid">
    <div class="row content">

        <br>
        <div class="col-sm-9">
            <div class="well">
                <div id="logo">
                    <?php
                    $age_band_dropdown = array(
                        array('16-18', '16-18'),
                        array('19-23', '19-23'),
                        array('24+', '24+'),
                        array('19+', '19+'),
                        array('All age_band', 'All ages')
                    );
                    $qar_type_dropdown = array(
                        array('Overall', 'Overall'),
                        array('Timely', 'Timely'),
                    );
                    $best_case_dropdown = array(
                        array('Actual', 'Actual'),
                        array('Best', 'Best Case'),
                    );
                    $level_dropdown = array(
                        array('All level', 'All'),
                        array('3', '3 - Intermediate Apprenticeship'),
                        array('2', '2 - Advanced Apprenticeship'),
                        array('20', '20 - Higher Apprenticeship'),
                        array('25', '25 - Standard'),
                    );
                    $at_risk_dropdown = array(
                        array('1', 'All'),
                        array('2', 'At Risk'),
                        array('3', 'Not at risk'),
                    );

                    $learner_type_dropdown = array(
                        array('All learner type', 'All'),
                        array('3AAA', '3AAA Transfer'),
                        array('NA', 'New Apprentice'),
                        array('P', 'Progression'),
                        array('SSU', 'Straight Sign Up'),
                        array('WFD', 'WFD'),
                        array('DXC', 'DXC Transfer'),
                        array('HOET', 'HOET Transfer'),
                    );

                    $employer_type_dropdown = array(
                        array('All employer type', 'All'),
                        array('AM', 'Account Management'),
                        array('SG', 'EEM Self Generated'),
                        array('EE', 'EEM Self'),
                        array('L', 'Levy'),
                        array('LS', 'Levy Team Self Gen'),
                        array('LT', 'Levy Team'),
                        array('LM', 'Levy Account Management'),
                        array('NB', 'New Business'),
                        array('NT', 'Non Levy Team'),
                        array('NG', 'Non Levy Self Gen'),
                        array('NM', 'Non Levy Account Management'),
                        array('SC', 'Senior Consultant - Levy'),
                    );

                    echo '<table><tr><td class = "label1">Age Band <br>' . HTML::select('age_band_filter', $age_band_dropdown, $age_band_filter) . '</td>';
                        echo '<td width="50px"></td>';
                        echo '<td class="label1">QAR Type <br>' . HTML::select('qar_type_filter', $qar_type_dropdown, $qar_type_filter) . '</td>';
                        echo '<td width="50px"></td>';
                        echo '<td class="label1">Scenario <br>' . HTML::select('best_case_filter', $best_case_dropdown, $best_case_filter) . '</td>';
                        echo '<td width="50px"></td>';
                        echo '<td class="label1">App Level <br>' . HTML::select('level_filter', $level_dropdown, $level_filter) . '</td>';
                        echo '<td width="25px"></td>';
                        echo '<td class="label1">At Risk<br>' . HTML::select('at_risk_filter', $at_risk_dropdown, $at_risk_filter) . '</td>';
                        echo '<td width="25px"></td>';
                        echo '<td id="tdCalculate"><div class="panel-heading" style="background: darkgrey; cursor: pointer;" onclick="calculate()"><b>Recalculate</b></div>Last calculated on &nbsp;' . DAO::getSingleValue($link,"select value from configuration where entity = 'QAR'") . '</td>';
                        echo '</td>';
                        if(DB_NAME=='am_baltic')
                        {
                            echo '</tr><tr>';
                            echo '<td width="50px"></td>';
                            echo '<td class="label1">Learner Type <br>' . HTML::select('learner_type_filter', $learner_type_dropdown, $learner_type_filter) . '</td>';
                            echo '<td width="50px"></td>';
                            echo '<td class="label1">Employer Type <br>' . HTML::select('employer_type_filter', $employer_type_dropdown, $employer_type_filter) . '</td>';
                        }
                        echo '</tr></table>';

                    //echo '</tr></table>';
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2" style='cursor: pointer' onclick='click_tab("summary");'>
                    <div class="panel panel-primary">
                        <?php if($tab=='summary') echo '<div class="panel-heading" style="background: darkgrey; "><b>Summary</b></div>'; else echo '<div class="panel-heading">Summary</div>'; ?>
                    </div>
                </div>
                <div class="col-sm-2" style='cursor: pointer' onclick='click_tab("demographics");'>
                    <div class="panel panel-primary">
                        <?php if($tab=='demographics') echo '<div class="panel-heading" style="background: darkgrey; "><b>Demographics</b></div>'; else echo '<div class="panel-heading">Demographics</div>'; ?>
                    </div>
                </div>
                <div class="col-sm-2" style='cursor: pointer' onclick='click_tab("ssa");'>
                    <div class="panel panel-primary">
                        <?php if($tab=='ssa') echo '<div class="panel-heading" style="background: darkgrey; "><b>By SSA</b></div>'; else echo '<div class="panel-heading">By SSA</div>'; ?>
                    </div>
                </div>
                <div class="col-sm-2" style='cursor: pointer' onclick='click_tab("framework");'>
                    <div class="panel panel-primary">
                        <?php if($tab=='framework') echo '<div class="panel-heading" style="background: darkgrey; "><b>By FWork/ Std</b></div>'; else echo '<div class="panel-heading">By FWork/ Std</div>'; ?>
                    </div>
                </div>
                <div class="col-sm-2" style='cursor: pointer' onclick='click_tab("assessor");'>
                    <div class="panel panel-primary">
                        <?php if($tab=='assessor') if(DB_NAME=='am_siemens' or DB_NAME=='am_siemens_demo') echo '<div class="panel-heading" style="background: darkgrey; "><b>By App: Coord:</b></div>'; else echo '<div class="panel-heading" style="background: darkgrey; "><b>By Assessor</b></div>'; else if(DB_NAME=='am_siemens' or DB_NAME=='am_siemens_demo') echo '<div class="panel-heading">By App: Coord:</div>'; else echo '<div class="panel-heading">By Assessor</div>';?>
                    </div>
                </div>
                <div class="col-sm-2" style='cursor: pointer' onclick='click_tab("level");'>
                    <div class="panel panel-primary">
                        <?php if($tab=='level') echo '<div class="panel-heading" style="background: darkgrey; "><b>By Level</b></div>'; else echo '<div class="panel-heading">By Level</div>'; ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2" style='cursor: pointer' onclick='click_tab("leavers");'>
                    <div class="panel panel-primary">
                        <?php if($tab=='leavers') echo '<div class="panel-heading" style="background: darkgrey; "><b>Leavers</b></div>'; else echo '<div class="panel-heading">Leavers</div>'; ?>
                    </div>
                </div>
            </div>

            <?php if($tab=='summary') { ?>
                <?php if($qar_type_filter=='Overall') { ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><b>Overall Qualification Achievement Rates</b></div>
                            <div class="chart-panel-body " id="OverallSummary"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><b>Overall QAR Trend</b></div>
                            <div class="chart-panel-body " id="LineChartOverallTrend"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><b>Retention Rates</b></div>
                            <div class="chart-panel-body " id="OverallRetention"></div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php if($qar_type_filter=='Timely') { ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><b>Timely Qualification Achievement Rates</b></div>
                            <div class="chart-panel-body " id="TimelySummary"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><b>Timely QAR Trend</b></div>
                            <div class="chart-panel-body " id="LineChartTimelyTrend"></div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            <?php } elseif($tab=='demographics') { ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>QAR by Gender</b></div>
                        <div class="chart-panel-body " id="LearnerByGender"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>QAR by LLDD</b></div>
                        <div class="chart-panel-body " id="LearnerByLLDD"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>QAR by Ethnicity</b></div>
                        <div class="chart-panel-body " id="LearnerByEthnicity"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>QAR by Age Band</b></div>
                        <div class="chart-panel-body " id="LearnerByAgeBand"></div>
                    </div>
                </div>
            </div>
            <?php } elseif($tab=='ssa') { ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>QAR by Sector Subject Area</b></div>
                        <div class="chart-panel-body " id="LearnerBySSA"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>QAR by Sector Subject Area (Apprenticehips)</b></div>
                        <div class="chart-panel-body " id="LearnerBySSAChartApp"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>QAR by Sector Subject Area (Education & Training)</b></div>
                        <div class="chart-panel-body " id="LearnerBySSAChartEducation"></div>
                    </div>
                </div>
            </div>
            <?php } elseif($tab=='framework') { ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>QAR by Framework/ Standard</b></div>
                        <div class="chart-panel-body " id="LearnerByFramework"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>QAR by Apprenticeship Framework</b></div>
                        <div class="chart-panel-body " id="LearnerByFrameworkChartApp"></div>
                    </div>
                </div>
            </div>
            <?php } elseif($tab=='assessor') { ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b><?php if(DB_NAME=='am_siemens' or DB_NAME=='am_siemens_demo') echo "QAR by Apprenticeship Coordinator"; else echo "QAR by Assessor"; ?></b></div>
                        <div class="chart-panel-body " id="LearnerByAssessor"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b><?php if(DB_NAME=='am_siemens' or DB_NAME=='am_siemens_demo') echo "QAR by Apprenticeship Coordinator (Apprenticeships)"; else echo "QAR by Assessor (Apprenticeships)"; ?></b></div>
                        <div class="chart-panel-body " id="LearnerByAssessorApp"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b><?php if(DB_NAME=='am_siemens' or DB_NAME=='am_siemens_demo') echo "QAR by Apprenticeship Coordinator (Education & Training)"; else echo "QAR by Assessor (Education & Training)"; ?></b></div>
                        <div class="chart-panel-body " id="LearnerByAssessorEducation"></div>
                    </div>
                </div>
            </div>
            <?php } elseif($tab=='level') { ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>QAR by Level</b></div>
                        <div class="chart-panel-body " id="LearnerByLevel"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>QAR by Level (Apprenticeship)</b></div>
                        <div class="chart-panel-body " id="LearnerByLevelApp"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>QAR by Level (Education & Training)</b></div>
                        <div class="chart-panel-body " id="LearnerByLevelEducation"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>Retention Rates by Level</b></div>
                        <div class="chart-panel-body " id="RetentionByLevel"></div>
                    </div>
                </div>
            </div>
            <?php } elseif($tab=='leavers') { ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>Withdrawn trend (Hybrid end-year)</b></div>
                        <div class="chart-panel-body " id="LeaversByTrend"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>Withdrawn trend (withdrawn year)</b></div>
                        <div class="chart-panel-body " id="LeaversByTrendActual"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>Leaners withdrawn against learners on programme</b></div>
                        <div class="chart-panel-body " id="LeaversByTrendOnProgramme"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>Leavers/Withdrawn by year (Hybrid end-year)</b></div>
                        <div class="chart-panel-body " id="LeaversByYear"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>Withdrawn Reason by year (Hybrid end-year)</b></div>
                        <div class="chart-panel-body " id="LeaversByYearReason"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>Withdrawn learners (Hybrid end-year)</b></div>
                        <div class="chart-panel-body " id="LeaversByYearImpact"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>Withdrawn learners (Withdrawn year)</b></div>
                        <div class="chart-panel-body " id="LeaversByYearActual"></div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<script>
var tab = <?php echo "'" . $tab . "'"; ?>;
$(function () {

    <?php if($tab=='summary') { ?>

    <?php if($qar_type_filter=='Overall') { ?>

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=getOverallSummary&tab=summary',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#OverallSummary").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            $("#OverallSummary").html(response);
        }
    });

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=getLineChartOverallTrend&tab=summary',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LineChartOverallTrend").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            drawLineChartOverallTrend(JSON.parse(response));
        }
    });

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=getRetentionSummary&tab=summary',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#OverallRetention").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            $("#OverallRetention").html(response);
        }
    });


    <?php } ?>
    <?php if($qar_type_filter=='Timely') { ?>

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=getTimelySummary&tab=summary',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#TimelySummary").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            $("#TimelySummary").html(response);
        }
    });


    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=getLineChartTimelyTrend&tab=summary',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LineChartTimelyTrend").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            drawLineChartTimelyTrend(JSON.parse(response));
        }
    });

    <?php } ?>
    <?php } elseif($tab=='demographics') { ?>

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=LearnerByGenderTable&tab=demographics',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LearnerByGender").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            $("#LearnerByGender").html(response);
        }
    });

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=LearnerByAgeBandTable&tab=demographics',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LearnerByAgeBand").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            $("#LearnerByAgeBand").html(response);
        }
    });

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=LearnerByLLDDTable&tab=demographics',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LearnerByLLDD").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            $("#LearnerByLLDD").html(response);
        }
    });

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=LearnerByEthnicityTable&tab=demographics',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LearnerByEthnicity").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            $("#LearnerByEthnicity").html(response);
        }
    });

    <?php } elseif($tab=='ssa') { ?>

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=LearnerBySSATable&tab=ssa',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LearnerBySSA").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            $("#LearnerBySSA").html(response);
        }
    });

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=LearnerBySSAChartApp&tab=ssa',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LearnerBySSAChartApp").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            $("#LearnerBySSAChartApp").html(response);
            drawLearnerBySSAChartApp(JSON.parse(response));
        }
    });

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=LearnerBySSAChartEducation&tab=ssa',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LearnerBySSAChartEducation").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            $("#LearnerBySSAChartEducation").html(response);
            drawLearnerBySSAChartEducation(JSON.parse(response));
        }
    });

    <?php } elseif($tab=='framework') { ?>

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=LearnerByFrameworkTable&tab=framework',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LearnerByFramework").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            $("#LearnerByFramework").html(response);
        }
    });

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=LearnerByFrameworkChartApp&tab=framework',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LearnerByFrameworkChartApp").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            drawLearnerByFrameworkChartApp(JSON.parse(response));
        }
    });

    <?php } elseif($tab=='assessor') { ?>

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=LearnerByAssessorTable&tab=assessor',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LearnerByAssessor").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            $("#LearnerByAssessor").html(response);
        }
    });

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=LearnerByAssessorApp&tab=assessor',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LearnerByAssessorApp").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            drawLearnerByAssessorApp(JSON.parse(response));
        }
    });

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=LearnerByAssessorEducation&tab=assessor',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LearnerByAssessorEducation").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            drawLearnerByAssessorEducation(JSON.parse(response));
        }
    });

    <?php } elseif($tab=='level') { ?>

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=LearnerByLevelTable&tab=level',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LearnerByLevel").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            $("#LearnerByLevel").html(response);
        }
    });

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=LearnerByLevelApp&tab=level',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LearnerByLevelApp").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            drawLearnerByLevelApp(JSON.parse(response));
        }
    });

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=LearnerByLevelEducation&tab=level',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LearnerByLevelEducation").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            drawLearnerByLevelEducation(JSON.parse(response));
        }
    });

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=RetentionByLevelTable&tab=level',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#RetentionByLevel").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            $("#RetentionByLevel").html(response);
        }
    });


    <?php } elseif($tab=='leavers') { ?>

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=leaversbytrend&tab=level',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LeaversByTrend").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            drawLeaversByTrend(JSON.parse(response));
        }
    });

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=leaversbytrendactual&tab=level',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LeaversByTrendActual").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            drawLeaversByTrendActual(JSON.parse(response));
        }
    });

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=leaversbytrendonprogramme&tab=level',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LeaversByTrendOnProgramme").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            drawLeaversByTrendOnProgramme(JSON.parse(response));
        }
    });

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=leavers&tab=level',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LeaversByYear").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            drawLeaversByYear(JSON.parse(response));
        }
    });

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=leaversbyreason&tab=level',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LeaversByYearReason").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            drawLeaversByYearReason(JSON.parse(response));
        }
    });

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=leaversbyimpact&tab=level',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LeaversByYearImpact").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            drawLeaversByYearImpact(JSON.parse(response));
        }
    });

    $.ajax({
        url:'do.php?_action=qar&age_band=' + $('#age_band_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&level=' + $('#level_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&panel=leaversbyactual&tab=level',
        type:"GET",
        async:true,
        beforeSend:function (data) {
            $("#LeaversByYearActual").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
        },
        success:function (response) {
            drawLeaversByYearActual(JSON.parse(response));
        }
    });


    <?php } ?>
});

function drawLineChartOverallTrend(data){
    var options = {
    chart: {
        renderTo: 'LineChartOverallTrend',
        type: 'line'
    },
    title: {
        text: 'Overall Qualification Achievement Rates Trend'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: []
    },
    yAxis: {
        title: {
            text: 'QAR Percentage'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'Apprenticeships',
        data: []
    }, {
        name: 'Education and Training [16-18]',
        data: []
    }, {
        name: 'Education and Training [19+]',
        data: []
    }]
    }
    options.xAxis.categories = data[0]['Years'];
    options.series[0]['data'] = data[1];
    options.series[1]['data'] = data[2];
    options.series[2]['data'] = data[3];
    new Highcharts.Chart(options);
}

function drawLineChartTimelyTrend(data){
    var options = {
        chart: {
            renderTo: 'LineChartTimelyTrend',
            type: 'line'
        },
        title: {
            text: 'Timely Qualification Achievement Rates Trend'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: []
        },
        yAxis: {
            title: {
                text: 'QAR Percentage'
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: [{
            name: 'Apprenticeships',
            data: []
        }, {
            name: 'Education and Training [16-18]',
            data: []
        }, {
            name: 'Education and Training [19+]',
            data: []
        }]
    }
    options.xAxis.categories = data[0]['Years'];
    options.series[0]['data'] = data[1];
    options.series[1]['data'] = data[2];
    options.series[2]['data'] = data[3];
    new Highcharts.Chart(options);
}

function drawLearnerByGenderChart(data){
    var options = {
        chart: {
            renderTo: 'LearnerByGenderChart',
            type: 'column',
            options3d: {
                enabled: true,
                alpha: 15,
                beta: 0,
                depth: 40,
                viewDistance: 25
            },
            height: 350
        },
        title: {
            text: 'QARs By Gender',
            x: -20 //center
        },
        subtitle: {
            text: '',
            x: -20
        },
        xAxis: {
            categories: []
        },
        yAxis: {
            title: {
                text: 'Learners'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            formatter: function() {
                return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': '+ this.y;
            }
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -10,
            y: 100,
            borderWidth: 0
        },
        series: [{
            type: 'column',
            name: 'Learners QARs By Gender'
        }]
    }
    options.xAxis.categories = data[0]['data'];
    options.series[0] = data[1];
    options.series[1] = data[2];
    new Highcharts.Chart(options);
}

function drawLeaversByTrend(data){
    var options = {
        chart: {
            renderTo: 'LeaversByTrend',
            type: 'line'
        },
        title: {
            text: 'Withdrawn percentage trend (Hybrid end-year)'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: []
        },
        yAxis: {
            title: {
                text: 'Withdrawn trend'
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: []
    };
    index = data.length;
    options.xAxis.categories = data[0]['data'];
    for(i = 1; i<index; i++)
        options.series[i-1] = data[i];
    new Highcharts.Chart(options);
}

function drawLeaversByTrendActual(data){
    var options = {
        chart: {
            renderTo: 'LeaversByTrendActual',
            type: 'line'
        },
        title: {
            text: 'Withdrawn percentage trend (Year withdrawn)'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: []
        },
        yAxis: {
            title: {
                text: 'Withdrawn trend'
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: []
    };
    index = data.length;
    options.xAxis.categories = data[0]['data'];
    for(i = 1; i<index; i++)
        options.series[i-1] = data[i];
    new Highcharts.Chart(options);
}

function drawLeaversByTrendOnProgramme(data){
    var options = {
        chart: {
            renderTo: 'LeaversByTrendOnProgramme',
            type: 'line'
        },
        title: {
            text: 'Withdrawn percentage trend (Year withdrawn against learners on programme)'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: []
        },
        yAxis: {
            title: {
                text: 'Withdrawn trend'
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: []
    };
    index = data.length;
    options.xAxis.categories = data[0]['data'];
    for(i = 1; i<index; i++)
        options.series[i-1] = data[i];
    new Highcharts.Chart(options);
}

function drawLeaversByYear(data){
    var options = {
        chart: {
            renderTo: 'LeaversByYear',
            type: 'column'
        },
        title: {
            text: '',
        },
        xAxis: {
            categories: []
        },
        yAxis: {
            title: {
                text: 'Learners'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            formatter: function() {
                return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': '+ this.y;
            }
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        series: [{
            type: 'column',
            name: 'Leavers'
        }]
    }
    index = data.length;
    options.xAxis.categories = data[0]['data'];
    for(i = 1; i<index; i++)
        options.series[i-1] = data[i];
    new Highcharts.Chart(options);
}

function drawLeaversByYearReason(data){
var options = {
    chart: {
        type: 'column',
        renderTo: 'LeaversByYearReason'

    },
    title: {
        text: ''
    },
    xAxis: {
        categories: []
    },
    yAxis: {
        min: 0,
                title: {
            text: 'Leavers by reason'
        },
        stackLabels: {
            enabled: true,
                    style: {
                fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        }
    },
    legend: {
        align: 'right',
                x: -30,
                verticalAlign: 'top',
                y: 25,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
    },
    tooltip: {
        headerFormat: '<b>{point.x}</b><br/>',
                pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
    },
    plotOptions: {
        column: {
            stacking: 'normal',
                    dataLabels: {
                enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
            }
        }
    },
    series: []
}

    options.xAxis.categories = data[0]['data'];
    index = data.length;
    options.xAxis.categories = data[0]['data'];
    for(i = 1; i<index; i++)
        options.series[i-1] = data[i];
    new Highcharts.Chart(options);

}

function drawLeaversByYearImpact(data){
    var options = {
        chart: {
            type: 'column',
            renderTo: 'LeaversByYearImpact'

        },
        title: {
            text: ''
        },
        xAxis: {
            categories: []
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Withdrawn learners (Hybrid end-year)'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 0,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
            }
        },
        series: []
    }

    options.xAxis.categories = data[0]['data'];
    index = data.length;
    options.xAxis.categories = data[0]['data'];
    for(i = 1; i<index; i++)
        options.series[i-1] = data[i];
    console.log(data);
    new Highcharts.Chart(options);
}

function drawLeaversByYearActual(data){
    var options = {
        chart: {
            type: 'column',
            renderTo: 'LeaversByYearActual'

        },
        title: {
            text: ''
        },
        xAxis: {
            categories: []
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Withdrawn learners (withdrawn year)'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 0,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
            }
        },
        series: []
    }

    options.xAxis.categories = data[0]['data'];
    index = data.length;
    options.xAxis.categories = data[0]['data'];
    for(i = 1; i<index; i++)
        options.series[i-1] = data[i];
    console.log(data);
    new Highcharts.Chart(options);
}

function drawLearnerBySSAChartApp(data){
    var options = {
        chart: {
            renderTo: 'LearnerBySSAChartApp',
            type: 'column',
            options3d: {
                enabled: true,
                alpha: 15,
                beta: 0,
                depth: 40,
                viewDistance: 25
            },
            height: 350
        },
        title: {
            text: 'QAR By SSA (Apprenticeship)',
            x: -20 //center
        },
        subtitle: {
            text: '',
            x: -20
        },
        xAxis: {
            categories: [<?php echo implode(",",$year); ?>]
        },
        yAxis: {
            title: {
                text: 'Rates'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            formatter: function() {
                return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': '+ this.y;
            }
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -10,
            y: 100,
            borderWidth: 0
        },
        series: [{
            type: 'column',
            name: 'Learners Progress By Assessor'
        }]
    }

      index = data.length;
      for(i = 0; i<index; i++)
        options.series[i] = data[i];
    new Highcharts.Chart(options);
}

function drawLearnerBySSAChartEducation(data){
    var options = {
        chart: {
            renderTo: 'LearnerBySSAChartEducation',
            type: 'column',
            options3d: {
                enabled: true,
                alpha: 15,
                beta: 0,
                depth: 40,
                viewDistance: 25
            },
            height: 350
        },
        title: {
            text: 'QAR By SSA (Education & Training)',
            x: -20 //center
        },
        subtitle: {
            text: '',
            x: -20
        },
        xAxis: {
            categories: [<?php echo implode(",",$year); ?>]
        },
        yAxis: {
            title: {
                text: 'Rates'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            formatter: function() {
                return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': '+ this.y;
            }
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -10,
            y: 100,
            borderWidth: 0
        },
        series: [{
            type: 'column',
            name: 'Learners Progress By Assessor'
        }]
    }

    index = data.length;
    for(i = 0; i<index; i++)
        options.series[i] = data[i];

    new Highcharts.Chart(options);
}

function drawLearnerByFrameworkChartApp(data){
    var options = {
        chart: {
            renderTo: 'LearnerByFrameworkChartApp',
            type: 'column',
            options3d: {
                enabled: true,
                alpha: 15,
                beta: 0,
                depth: 40,
                viewDistance: 25
            },
            height: 350
        },
        title: {
            text: 'QAR By Framework (Apprenticeship)',
            x: -20 //center
        },
        subtitle: {
            text: '',
            x: -20
        },
        xAxis: {
            categories: [<?php echo implode(",",$year); ?>]
        },
        yAxis: {
            title: {
                text: 'Rates'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            formatter: function() {
                return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': '+ this.y;
            }
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -10,
            y: 100,
            borderWidth: 0
        },
        series: [{
            type: 'column',
            name: 'Learners Progress By Assessor'
        }]
    }

    index = data.length;
    for(i = 0; i<index; i++)
        options.series[i] = data[i];

    new Highcharts.Chart(options);
}

function drawLearnerByAssessorApp(data){
    var options = {
        chart: {
            renderTo: 'LearnerByAssessorApp',
            type: 'column',
            options3d: {
                enabled: true,
                alpha: 15,
                beta: 0,
                depth: 40,
                viewDistance: 25
            },
            height: 350
        },
        title: {
            text: 'QAR (Apprenticeship)',
            x: -20 //center
        },
        subtitle: {
            text: '',
            x: -20
        },
        xAxis: {
            categories: [<?php echo implode(",",$year); ?>]
        },
        yAxis: {
            title: {
                text: 'Rates'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            formatter: function() {
                return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': '+ this.y;
            }
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -10,
            y: 100,
            borderWidth: 0
        },
        series: [{
            type: 'column',
            name: 'Learners Progress By Assessor'
        }]
    }

    index = data.length;
    for(i = 0; i<index; i++)
        options.series[i] = data[i];

    new Highcharts.Chart(options);
}

function drawLearnerByAssessorEducation(data){
    var options = {
        chart: {
            renderTo: 'LearnerByAssessorEducation',
            type: 'column',
            options3d: {
                enabled: true,
                alpha: 15,
                beta: 0,
                depth: 40,
                viewDistance: 25
            },
            height: 350
        },
        title: {
            text: 'QAR (Education & Training)',
            x: -20 //center
        },
        subtitle: {
            text: '',
            x: -20
        },
        xAxis: {
            categories: [<?php echo implode(",",$year); ?>]
        },
        yAxis: {
            title: {
                text: 'Rates'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            formatter: function() {
                return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': '+ this.y;
            }
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -10,
            y: 100,
            borderWidth: 0
        },
        series: [{
            type: 'column',
            name: 'Learners Progress By Assessor'
        }]
    }

    index = data.length;
    for(i = 0; i<index; i++)
        options.series[i] = data[i];

    new Highcharts.Chart(options);
}

function drawLearnerByLevelApp(data){
    var options = {
        chart: {
            renderTo: 'LearnerByLevelApp',
            type: 'column',
            options3d: {
                enabled: true,
                alpha: 15,
                beta: 0,
                depth: 40,
                viewDistance: 25
            },
            height: 350
        },
        title: {
            text: 'QAR By Level (Apprenticeship)',
            x: -20 //center
        },
        subtitle: {
            text: '',
            x: -20
        },
        xAxis: {
            categories: [<?php echo implode(",",$year); ?>]
        },
        yAxis: {
            title: {
                text: 'Rates'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            formatter: function() {
                return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': '+ this.y;
            }
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -10,
            y: 100,
            borderWidth: 0
        },
        series: [{
            type: 'column',
            name: 'Learners Progress By Assessor'
        }]
    }

    index = data.length;
    for(i = 0; i<index; i++)
        options.series[i] = data[i];

    new Highcharts.Chart(options);
}

function drawLearnerByLevelEducation(data){
    var options = {
        chart: {
            renderTo: 'LearnerByLevelEducation',
            type: 'column',
            options3d: {
                enabled: true,
                alpha: 15,
                beta: 0,
                depth: 40,
                viewDistance: 25
            },
            height: 350
        },
        title: {
            text: 'QAR By Level (Education & Training)',
            x: -20 //center
        },
        subtitle: {
            text: '',
            x: -20
        },
        xAxis: {
            categories: [<?php echo implode(",",$year); ?>]
        },
        yAxis: {
            title: {
                text: 'Rates'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            formatter: function() {
                return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': '+ this.y;
            }
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -10,
            y: 100,
            borderWidth: 0
        },
        series: [{
            type: 'column',
            name: 'Learners Progress By Assessor'
        }]
    }

    index = data.length;
    for(i = 0; i<index; i++)
        options.series[i] = data[i];

    new Highcharts.Chart(options);
}

function age_band_filter_onchange(ele) {
    window.location.href = 'do.php?_action=qar&age_band=' + encodeURIComponent(ele.value) + '&level=' + $('#level_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&tab=' + tab;
}

function click_tab(ele) {
    window.location.href = 'do.php?_action=qar&age_band=' + encodeURIComponent($('#age_band_filter').val()) + '&level=' + $('#level_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&qar_type=' + $('#qar_type_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() +  '&tab=' + ele;
}

function qar_type_filter_onchange(ele) {
    window.location.href = 'do.php?_action=qar&qar_type=' + encodeURIComponent(ele.value) + '&level=' + $('#level_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&age_band_type=' + $('#age_band_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&tab=' + tab;
}

function best_case_filter_onchange(ele) {
    window.location.href = 'do.php?_action=qar&qar_type=' + $('#qar_type_filter').val() + '&level=' + $('#level_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&age_band_type=' + $('#age_band_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&tab=' + tab;
}

function level_filter_onchange(ele) {
    window.location.href = 'do.php?_action=qar&qar_type=' + $('#qar_type_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&level=' + $('#level_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&age_band_type=' + $('#age_band_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&tab=' + tab;
}

function at_risk_filter_onchange(ele) {
    window.location.href = 'do.php?_action=qar&qar_type=' + $('#qar_type_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&level=' + $('#level_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&age_band_type=' + $('#age_band_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&tab=' + tab;
}

function learner_type_filter_onchange(ele)
{
    window.location.href = 'do.php?_action=qar&qar_type=' + $('#qar_type_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&level=' + $('#level_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&age_band_type=' + $('#age_band_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&tab=' + tab;
}

function employer_type_filter_onchange(ele)
{
    window.location.href = 'do.php?_action=qar&qar_type=' + $('#qar_type_filter').val() + '&best_case=' + $('#best_case_filter').val() + '&level=' + $('#level_filter').val() + '&at_risk=' + $('#at_risk_filter').val() + '&age_band_type=' + $('#age_band_filter').val() + '&learner_type=' + $('#learner_type_filter').val() + '&employer_type=' + $('#employer_type_filter').val() + '&tab=' + tab;
}

</script>
</body>
</html>

