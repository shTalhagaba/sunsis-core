<?php /* @var $vo User*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Funding Report</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>

<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="js/highcharts.js" type="text/javascript"></script>

<!-- CSS for TabView -->
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/skins/sam/tabview.css">
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/border_tabs.css">

<!-- Dependency source files -->
<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/container/container.js"></script>

<!-- Page-specific script -->
<script type="text/javascript" src="/yui/2.4.1/build/utilities/utilities.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/element/element-beta.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/tabview/tabview.js"></script>


<script type="text/javascript">
    function selectAllCodes()
    {
        selectAll = document.getElementById("selectAll");
        var myForm = document.forms["frmBusiness2"];
        var checkboxes = myForm.elements['boroughradio'];
        for(var i = 0; i < checkboxes.length; i++)
        {
            if(selectAll.checked)
                checkboxes[i].checked = true;
            else
                checkboxes[i].checked = false;
        }
    }

    function saveForm()
    {
        xml = "<BusinessCodes>";
        $('input:text').each(function()
        {
            if($(this).val()!="")
            {
                xml += "<Business>";
                xml += "<Code>" + $(this).attr('name').split("|")[0] + "</Code>";
                xml += "<Month>" + $(this).attr('name').split("|")[1] + "</Month>";
                xml += "<Value>" + $(this).val() + "</Value>";
                xml += "</Business>";
            }
        });
        xml += "</BusinessCodes>";
        var myForm = document.forms["frmBusiness"];
        myForm.elements["questions_xml"].value = xml;
        myForm.submit();
    }

    function saveForm2()
    {
        var myForm = document.forms["frmBusiness2"];
        var checkboxes = myForm.elements['boroughradio'];
        var evidence_id = "";
        var xml = "<BusinessCodes>";
        for(var i = 0; i < checkboxes.length; i++)
        {
            if(checkboxes[i].checked)
            {
                evidence_id =  checkboxes[i].value;
                xml += '<BusinessCode>' + evidence_id + '</BusinessCode>';
            }
        }
        xml += "</BusinessCodes>";
        myForm.elements["questions_xml"].value = xml;
        myForm.elements["single_multi"].value = $('#values').val();
        myForm.elements["chart_type"].value = $('#chart_types').val();
        myForm.elements["value_type"].value = $('#value_types').val();

        myForm.submit();
    }

    <?php
    $index = 1;
    foreach($business_codes as $business_code)
    {?>
    $(function () {
        $('#container<?php echo $index++;?>').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Funding Values'
            },
            subtitle: {
                text: 'Contract [<?php echo $business_code; ?>]'
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },            yAxis: {
                title: {
                    text: 'Funding'
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
                name: 'Funding Values',
                data: [<?php echo @join(",", $pfr[$business_code]);?>],
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    format: '{point.y:.0f}', // one decimal
                    y: 10, // 10 pixels down from the top
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            }]
        });
    });


    $(function () {
        $('#total').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Total Funding'
            },
            xAxis: {
                categories: ['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul']
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Amount'
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
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                        style: {
                            textShadow: '0 0 3px black'
                        }
                    }
                }
            },
            series: [<?php echo $stacked; ?>]
        });
    });

        <?php } ?>

    function toggleComparison()
    {
        showHideBlock(document.getElementById("parentcontainer"));
        showHideBlock(document.getElementById("tab1"),'none');
        showHideBlock(document.getElementById("tab2"),'none');
    }
    function toggleProfileValues()
    {
        showHideBlock(document.getElementById("parentcontainer"),'none');
        showHideBlock(document.getElementById("tab1"));
        showHideBlock(document.getElementById("tab2"),'none');
    }
    function toggleBusinessCodes()
    {
        showHideBlock(document.getElementById("parentcontainer"),'none');
        showHideBlock(document.getElementById("tab2"));
        showHideBlock(document.getElementById("tab1"),'none');
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
</style>
</head>
<body onload='$(".loading-gif").hide();' class="yui-skin-sam">
<div class="banner">
    <div class="Title">Funding Report</div>
    <div class="ButtonBar">
        <button onclick="toggleComparison();">Funding Chart</button>
        <button onclick="toggleProfileValues();">Funding Table</button>
        <button onclick="toggleBusinessCodes();">Configuration</button>
    </div>
    <div class="ActionIconBar">

    </div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<div class="loading-gif" id="progress">
    <img src="/images/progress-animations/loading51.gif" alt="Loading" class="loading-gif"/>
</div>


<div id="tab1" style="display:none">
    <form method="post" id="frmBusiness">
        <input type="hidden" name="_action" value="save_business_codes" />
        <input type="hidden" name="current_year" value="<?php echo $current_year; ?>" />
        <input type="hidden" name="screen_tab" value="1" />
        <input type="hidden" name="questions_xml" value="" />
        <h4>Funding Values</h4>
        <?php echo $this->renderBusinessCodes($link, $current_year,$pfr); ?>
    </form>
</div>

<div id="tab2" style="display:none">
    <form method="post" id="frmBusiness2">
        <input type="hidden" name="_action" value="save_business_codes" />
        <input type="hidden" name="current_year" value="<?php echo $current_year; ?>" />
        <input type="hidden" name="screen_tab" value="2" />
        <input type="hidden" name="single_multi" value="" />
        <input type="hidden" name="chart_type" value="" />
        <input type="hidden" name="value_type" value="" />
        <input type="hidden" name="questions_xml" value="" />
        <input type="hidden" name="report" value="show_pfr_values" />
        <p><span class="button" onclick="saveForm2();">&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;</span> </p>
        <!-- <h4>Settings</h4> -->
        <?php
        /*$single_multi_dropdown = array(array('Single', 'Combined chart for all business codes'), array('Multi', 'Multiple Charts per Business Code'));
        $chart_type_dropdown = array(array('line', 'Line'), array('bar', 'Bar'),array('column', 'Column'),array('areaspline', 'Area'));
        $value_type_dropdown = array(array('Individual', 'Individual values per month'), array('Incremental', 'Incremental values per month'));
        echo '<table class="table1"><tr><td>Chart per Business Code or Aggregated</td><td>';
        echo HTML::select('values', $single_multi_dropdown, $single_multi, false, false);
        echo '</td></tr><tr><td>Type of chart</td><td>';
        echo HTML::select('chart_types', $chart_type_dropdown, $chart_type, false, false);
        echo '</td></tr><tr><td>Individual values or incremental values</td><td>';
        echo HTML::select('value_types', $value_type_dropdown, $values_type, false, false);
        echo '</td></tr></table>'; */
        ?>
        <h4>Contracts</h4>
        <?php echo $this->renderBusinessCodes2($link, $current_year); ?>
    </form>
</div>

<div id="parentcontainer">
    <?php
    $index = 1;
    foreach($business_codes as $business_code)
    {
        echo '<div id="container'.$index.'" style="border: 1px solid black; min-width: 310px; height: 400px; margin: 0 auto"></div>';
        $index++;
    }
    echo '<div id="total" style="border: 1px solid black; min-width: 310px; height: 400px; margin: 0 auto"></div>';
    ?>
</div>

</body>
</html>