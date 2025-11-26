<!DOCTYPE html>
<html lang="en">
<head>
<title>Allocation Report</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

<script src="/common.js?n=<?php echo time(); ?>" type="text/javascript"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>

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
        padding:8px;
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
        font-size: 12px;
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
<div class="banner">
    <div class="Title">Allocation Analysis</div>
</div>
<table class="table1">
    <thead>
        <tr>
            <th>
                Allocation Amount &pound;<?php echo $allocation->allocation_amount; ?>
            </th>
            <?php
                foreach($months as $month)
                {
                    echo "<th>" . $this->getFormattedDate($month) . "</th>";
                }
            ?>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align: left">
                Monthly Allocation
            </td>
            <?php
            foreach($months as $month)
            {
                echo "<td>&pound;" . round($monthly_allocation) . "</td>";
            }
            ?>

        </tr>
        <tr>
            <td style="text-align: left">
                Monthly Funding
            </td>
            <?php
            $remaining_allowance = Array();
            $monthly_funding = Array();
            foreach($months as $month)
            {
                $funding = 0;
                foreach($learner_funding as $lf)
                    if(isset($lf[$month]))
                        $funding+=$lf[$month];
                $remaining_allowance[$month] = $monthly_allocation - $funding;
                $monthly_funding[$month] = $funding;
                echo "<td>&pound;" . round($funding) . "</td>";
            }
            ?>
        </tr>
        <tr>
            <td style="text-align: left">
                Remaining Allocation (each month)
            </td>
            <?php
            foreach($months as $month)
            {
                echo "<td>&pound;" . round($remaining_allowance[$month]) . "</td>";
            }
            ?>
        </tr>
        <tr>
            <td style="text-align: left">
                Allocation Remaining (Accumulated)
            </td>
            <?php
            $accu = $allocation->allocation_amount;
            foreach($months as $month)
            {
                $accu-=($monthly_funding[$month]);
                echo "<td>&pound;" . round($accu) . "</td>";
            }
            ?>
        </tr>
        <tr>
            <td style="text-align: left">
                Allocation Consumed (%)
            </td>
            <?php
            $accu = 0;
            foreach($months as $month)
            {
                $accu+=($monthly_funding[$month]);
                echo "<td>" . round($accu/$allocation->allocation_amount*100) . "%</td>";
            }
            ?>
        </tr>
</table>
<br><br><br><br><br>
    <div id = "details">
        <?php
        echo "<table class='table1'><thead><th>LearRefNumber</th>";
        foreach($months as $month)
            echo "<th>" . $this->getFormattedDate($month) . "</th>";
        echo "</thead>";
        echo "<tbody>";
        foreach($learner_funding as $key => $lf)
        {
            echo "<tr>";
            echo "<td>" . $key . "</td>";
            foreach($months as $month)
            {
                if(isset($lf[$month]))
                    echo "<td>" . $lf[$month] . "</td>";
                else
                    echo "<td>0</td>";
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
        ?>
    </div>

    </tbody>
</table>
</body>
</html>

