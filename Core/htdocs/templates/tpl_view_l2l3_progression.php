<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Progression Report</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/css/theme.default.css">
	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>
	<script type="text/javascript" src="/js/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="/js/jquery.tablesorter.widgets.js"></script>

	<style type="text/css">
		#tooltip
		{
			width:300px;
			background-image:url('/images/shadow-30.png');
			position: absolute;
			display: none;
			top: 50%;
			left: 50%;
			margin-top: -50px;
			margin-left: -50px;
		}

		#tooltip_content
		{
			position:relative;
			top: -3px;
			left: -3px;
			background-color: #FDF1E2;
			border: 1px gray solid;
			padding: 2px;
			font-family: sans-serif;
			font-size: 10pt;
		}
	</style>
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
            color: white;
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

	<script language="JavaScript">

        function expor(detail)
        {
            window.location.href='do.php?_action=export_success_rates&trs='+detail;
        }

		function div_filter_crumbs_onclick(div)
		{
			showHideBlock(div);
			showHideBlock('div_filters');
		}

		$(document).ready(function () {
				$("#dataMatrix").tablesorter({
					widgets: ["saveSort"]
				});
			}
		);

		$(document).ready(function() {
			$("#dataMatrix tbody tr td").hover(function(){
				var col = $(this).parent().children().index($(this));
				//var row = $(this).parent().parent().children().index($(this).parent());
				var header_text = $("#dataMatrix thead tr th").eq(col)[0].innerHTML;
				var header_text_of_first_column = $("#dataMatrix thead tr th").eq(1)[0].innerHTML;
				var lrn = $('td:nth-child(2)', $(this).parents('tr')).text();
				entry_onmouseover('<b>' + header_text + '</b> of ' + header_text_of_first_column + ': <b>' + lrn + '</b>');

			},function(){
				entry_onmouseout();
			});
		});

		function entry_onmouseover(header_text)
		{
			var tooltip = document.getElementById('tooltip');
			var content = document.getElementById('tooltip_content');
			content.innerHTML = header_text;
			tooltip.style.display = "block";
		}

		function entry_onmouseout()
		{
			var tooltip = document.getElementById('tooltip');
			tooltip.style.display = "none";
		}

	</script>

</head>

<body>
<div class="banner">
	<div class="Title">Progression Report</div>
	<div class="ButtonBar">
		<button onclick="window.history.go(-1);">Back</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<!--<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>-->
        <button onclick="$('#divCharts').toggle();"><img src="/images/btn-printer.gif" title="Show/hide charts" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.href='do.php?_action=view_l2l3_progression&subaction=export_csv'" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<?php echo $view->getFilterCrumbs() ?>

<div id="div_filters" style="display:none">

	<form method="get" action="#" id="applySavedFilter">
		<input type="hidden" name="_action" value="view_learners" />
		<?php echo $view->getSavedFiltersHTML(); ?>
	</form>

	<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
		<input type="hidden" name="page" value="1" />
		<input type="hidden" name="_action" value="view_l2l3_progression" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />
		<div id="filterBox" class="clearfix">
			<fieldset>
				<legend>Report Type</legend>
				<div class="field float">
					<label>Report Type:</label><?php echo $view->getFilterHTML('filter_report_type'); ?>
				</div>
				<!--<div class="field float">
					<label>First Contract Year:</label><?php //echo $view->getFilterHTML('filter_first_contract_year'); ?>
				</div>-->
				<div class="field float">
					<label>Progression year:</label><?php echo $view->getFilterHTML('filter_second_contract_year'); ?>
				</div>
			</fieldset>
			<fieldset>
				<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" />&nbsp;<input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
			</fieldset>
		</div>
	</form>
</div>

<div style="display: block; width: 100%" id="divCharts">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-primary">
                <div class="panel-heading"><b></b></div>
                <br>
                <table class="table1"  style="margin-left:10px; margin-right:10px">
                    <thead>
                        <tr>
                            <th></th>
                            <th colspan=3 style='text-align: center'>Level 2 to Level 3 Progression</th>
                            <th colspan=3 style='text-align: center'>Level 3 to Level 4 Progression</th>
                            <th colspan=3 style='text-align: center'>Traineeship to Apprenticeship Progression</th>
                            <th colspan=3 style='text-align: center'>Study Programmes to Traineeship Progression</th>
                        </tr>
                        <tr>
                            <th>Year</th>
                            <th>Level 2 Achievers</th>
                            <th>Progressed to Level 3</th>
                            <th>%</th>
                            <th>Level 3 Achievers</th>
                            <th>Progressed to Level 4</th>
                            <th>%</th>
                            <th>Traineeship leavers</th>
                            <th>Progressed to Apprenticeship</th>
                            <th>%</th>
                            <th>Study programme leavers</th>
                            <th>Progressed to Traineeship</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $years = DAO::getSingleColumn($link, "select distinct contract_year from contracts order by contract_year desc");
                        $pro = 0;
                        foreach($years as $year)
                        {
                            echo "<tr>";
                            echo "<td style='background: #90ee90;'>".$year."</td>";
                            echo "<td>";
                            $ach = 1;
                            foreach($l2_achievers as $l2ach)
                                if(isset($l2ach[0]) and $l2ach[0]==$year)
                                {
                                    echo sprintf("%s",$l2ach[1]);
                                    $ach = $l2ach[1];
                                }
                            echo "</td>";
                            echo "<td>";
                            foreach($l3_progression as $l2ach)
                                if(isset($l2ach[0]) and $l2ach[0]==$year)
                                {
                                    echo sprintf("%s",$l2ach[1]);
                                    $pro = $l2ach[1];
                                }
                            echo "</td>";
                            echo "<td style='background: #add8e6; width:50px'>";
                            if($pro>0)
                                echo sprintf("%2d",($pro/$ach*100))."%";
                            else
                                echo "0%";
                            $pro = 0;
                            echo "</td>";

                            echo "<td>";
                            $ach=1;
                            foreach($l3_achievers as $l2ach)
                                if(isset($l2ach[0]) and $l2ach[0]==$year)
                                {
                                    echo "<a href=javascript:expor('" . sprintf("%s",$l2ach[2]) . "');>" . sprintf("%s",$l2ach[1]);
                                    $ach = $l2ach[1];
                                }
                            echo "</td>";
                            echo "<td>";
                            foreach($l4_progression as $l2ach)
                                if(isset($l2ach[0]) and $l2ach[0]==$year)
                                {
                                    echo sprintf("%s",$l2ach[1]);
                                    $pro = $l2ach[1];
                                }
                            echo "</td>";
                            echo "<td style='background: #add8e6; width:50px'>";
                            if($pro>0)
                                echo sprintf("%2d",($pro/$ach*100))."%";
                            else
                                echo "0%";
                            $pro = 0;
                            echo "</td>";

                            echo "<td>";
                            $ach=1;
                            foreach($traineeship_leavers as $l2ach)
                                if(isset($l2ach[0]) and $l2ach[0]==$year)
                                {
                                    echo sprintf("%s",$l2ach[1]);
                                    $ach = $l2ach[1];
                                }
                            echo "</td>";
                            echo "<td>";
                            foreach($app_progression as $l2ach)
                                if(isset($l2ach[0]) and $l2ach[0]==$year)
                                {
                                    echo sprintf("%s",$l2ach[1]);
                                    $pro = $l2ach[1];
                                }
                            echo "</td>";
                            echo "<td style='background: #add8e6; width:50px'>";
                            if($pro>0)
                                echo sprintf("%2d",($pro/$ach*100))."%";
                            else
                                echo "0%";
                            $pro = 0;
                            echo "</td>";


                            echo "<td>";
                            foreach($study_programme_leavers as $l2ach)
                                if(isset($l2ach[0]) and $l2ach[0]==$year)
                                {
                                    echo sprintf("%s",$l2ach[1]);
                                    $ach = $l2ach[1];
                                }
                            echo "</td>";
                            echo "<td>";
                            foreach($traineeship_progression as $l2ach)
                                if(isset($l2ach[0]) and $l2ach[0]==$year)
                                {
                                    echo sprintf("%s",$l2ach[1]);
                                    $pro = $l2ach[1];
                                }
                            echo "</td>";
                            echo "<td style='background: #add8e6; width:50px'>";
                            if($pro>0)
                                echo sprintf("%2d",($pro/$ach*100))."%";
                            else
                                echo "0%";
                            $pro = 0;
                            echo "</td>";

                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <br>
            </div>
        </div>
    </div>
</div>

<div align="center" style="margin-top:50px;">
	<?php $view->render($link); ?>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>


</body>
</html>