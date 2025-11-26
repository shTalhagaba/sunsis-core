<?php /* @var $view View */ ?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Sunesis</title>
    <link rel="stylesheet" href="/common.css" type="text/css" />
    <script src="/js/jquery.min.js" type="text/javascript"></script>
    <script src="/common.js" type="text/javascript"></script>
    <script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>

    <!-- CSS for TabView -->

    <link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/skins/sam/tabview.css">
    <link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/border_tabs.css">


    <!-- Dependency source files -->

    <script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>
    <script type="text/javascript" src="/yui/2.4.1/build/container/container.js"></script>

    <!-- Page-specific script -->
    <script type="text/javascript" src="/yui/2.4.1/build/utilities/utilities.js"></script>
    <script type="text/javascript" src="/yui/2.4.1/build/element/element-beta.js"></script>

    <script type="text/javascript" src="/yui/2.4.1/build/treeview/treeview.js"></script>
    <script type="text/javascript" src="/yui/2.4.1/build/animation/animation.js"></script>
    <script type="text/javascript" src="/yui/2.4.1/build/accordion_menu/accordion-menu-v2.js"></script>

    <script type="text/javascript" src="/yui/2.4.1/build/dragdrop/dragdrop.js"></script>
    <script type="text/javascript" src="/yui/2.4.1/build/tabview/tabview.js"></script>

    <style type="text/css">
    #tooltip {
        width: 300px;
        background-image: url('/images/shadow-30.png');
        position: absolute;
        display: none;
        top: 50%;
        left: 50%;
        margin-top: -50px;
        margin-left: -50px;
    }

    #tooltip_content {
        position: relative;
        top: -3px;
        left: -3px;
        background-color: #FDF1E2;
        border: 1px gray solid;
        padding: 2px;
        font-family: sans-serif;
        font-size: 10pt;
    }
    </style>

    <script type="text/javascript">
    YAHOO.namespace("am.scope");



    function treeInit() {


        myTabs = new YAHOO.widget.TabView("demo");
    }



    YAHOO.util.Event.onDOMReady(treeInit);

    $(document).ready(function() {
        $("#dataMatrix tbody tr td").hover(function() {
            var col = $(this).parent().children().index($(this));
            //var row = $(this).parent().parent().children().index($(this).parent());
            var header_text = $("#dataMatrix thead tr:first th").eq(col)[0].innerHTML;
            var info = $('td:nth-child(1)', $(this).parents('tr')).text();
            var lrn = $('td:nth-child(2)', $(this).parents('tr')).text();
            entry_onmouseover('<b>' + info + ' ' + header_text + '</b> of Learner (ULN: <b>' + lrn +
                ')</b>');

        }, function() {
            entry_onmouseout();
        });
    });

    function entry_onmouseover(header_text) {
        var tooltip = document.getElementById('tooltip');
        var content = document.getElementById('tooltip_content');
        content.innerHTML = header_text;
        tooltip.style.display = "block";
    }

    function entry_onmouseout() {
        var tooltip = document.getElementById('tooltip');
        tooltip.style.display = "none";
    }
    </script>



</head>

<body class="yui-skin-sam">
    <div class="banner">
        <div class="Title">Sunesis Records VS PFR File</div>
        <div class="ActionIconBar">
            <!--
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		-->
        </div>
    </div>
    <?php $_SESSION['bc']->render($link); ?>



    <div id="demo" class="yui-navset">
        <div id="tooltip" style="position: fixed;display: none;">
            <div id="tooltip_content"></div>
        </div>
        <br>
        <ul class="yui-nav">
            <li class="selected"><a href="#tab1"><em>Similar Records </em></a></li>
            <li class=""><a href="#tab2"><em>Discrepancies </em></a></li>
            <li class=""><a href="#tab3"><em>Summary</em></a></li>
            <li class=""><a href="#tab4"><em>No funding in Sunesis </em></a></li>
            <li class=""><a href="#tab5"><em>No funding in PFR</em></a></li>
            <!--<li class=""><a href="#tab6"><em>Blank Learning Aims in PFR with funding</em></a></li>-->
        </ul>

        <div class="yui-content" style='background: white'>
            <div id="tab1">
                <p>
                <div align="center" style="margin-top:50px;">
                    <?php
					echo "<h3>Similarities</h3>";
					if (isset($report1))
						echo $report1; //$report1 declared in the act_read_pfr
					?>
                </div>
                </p>
            </div>

            <div id="tab2">
                <p>
                <div align="center" style="margin-top:50px;">
                    <?php
					echo "<h3>Discrepancies</h3>";
					if (isset($report2))
						echo $report2; //$report2 declared in the act_read_pfr
					?>
                </div>
                </p>
            </div>

            <div id="tab3">
                <p>
                <div align="center" style="margin-top:50px;">
                    <?php
					echo '<table>';
					if (!empty($contract)) {
						$contracts = explode(",", $contract);
						$count = count($contracts);

						echo '<tr><td colspan="2" style="font-size: 1.4em; color: #666;">All Contracts (' . $count . ' contract' . ($count > 1 ? 's' : '') . ')</td></tr>';
					} else {
						echo '<tr><td colspan="2" style="font-size: 1.4em; color: #666;">No Contracts Found</td></tr>';
					}
					$submissionDisplay = isset($submission) ? $submission : '';
					echo '<tr><td colspan="2" style="font-size: 1.4em; color: #666;">Submission (' . $submissionDisplay . ')</td></tr>';
					/*			echo '<tr><td colspan="2" style="font-weight: bold; font-style: italic;" >Learning Aim Summary</td></tr>';
			echo '<tr><td>Total Learning Aims in the PFR file: </td><td style="text-align:right;">'.$this->totalLearningAimsInPFR.'</td></tr>';
			echo '<tr><td>&pound; Value Learning Aims in the PFR file: </td><td style="text-align:right;">'.$this->totalLearningAimsInPFR.'</td></tr>';
			echo '<tr><td>&pound; Value Learning Aims in Sunesis: </td><td style="text-align:right;">'.$this->sunesisLearningAimsPoundValue.'</td></tr>';
			echo '<tr><td>Learning Aims with no funding in Sunesis</td><td style="text-align:right;">'.$this->extraRecordsInPFR.'</td></tr>';
			echo '<tr><td>Learning Aims with no funding on the PFR</td><td style="text-align:right;">'.$this->extraRecordsInSunesis.'</td></tr>';*/
					echo '<tr><td colspan="2" style="font-weight: bold; font-style: italic;" >Financial Summary</td></tr>';
					echo '<tr><td>Value in the PFR: </td><td style="text-align:right;" >' . $this->_formatCash(round($this->pfrTotal, 2)) . '</td></tr>';
					echo '<tr><td>Value in Sunesis: </td><td style="text-align:right;" >' . $this->_formatCash(round($this->sunesisTotal, 2)) . '</td></tr>';
					$discrepancy =  $this->sunesisTotal - $this->pfrTotal;
					$discrepancy_style = '';
					if ($discrepancy < 0)
						$discrepancy_style = 'font-style:italic; color: ' . $this->color_scheme['good'] . '; font-weight: bold;';
					else
						$discrepancy_style = 'font-style:italic; color: ' . $this->color_scheme['bad'] . '; font-weight: bold;';
					echo '<tr><td style="' . $discrepancy_style . '" >Financial Difference: </td>';
					echo '<td style="' . $discrepancy_style . 'text-align:right;" >' . $this->_formatCash(round($discrepancy, 2)) . '</td></tr>';
					if (!empty($data) && is_array($data) && count($data) >= 2) {
						$x = round($data[1], 2);
						$y = round($data[0], 2);
					} else {
						$x = $y = 0; 
					}
					if ($x > 0 and $y > 0) {
						$accuracyPercentage = abs($x / $y * 100);
						$errorPercentage = 100 - $accuracyPercentage;
					} elseif ($x == 0 and $y == 0) {
						$accuracyPercentage = 100;
						$errorPercentage = 0;
					} else {
						$accuracyPercentage = 0;
						$errorPercentage = 100;
					}

					echo '<tr><td colspan="2" style="font-weight: bold; font-style: italic;" >Accuracy</td></tr>';
					$accuracyPercentageToShow = $this->_formatCash(round($accuracyPercentage, "2"));
					echo '<tr><td>Percentage:</td><td style="text-align:right;">' . str_replace('&pound;', '', $accuracyPercentageToShow) . "% </td></tr>";
					echo '</table>';

					$k = array();
					$k[] = array("" => "", "AccuracyPercentage" => round($accuracyPercentage, "2"), "ErrorPercentage" => round($errorPercentage, "2"), "Total" => 100);

					$report3 = new DataMatrix(array_keys($k[0]), $k, false);
					$report3->addTotalColumns(array('Accuracy', 'Error', 'Total'));

					echo "<h3>Bar Chart</h3>";
					echo $report3->to('BarChart');
					echo "<h3>Pie Chart</h3>";
					echo $report3->to('PieChart');

					?>
                </div>
                </p>




            </div>
            <div id="tab4">
                <p>
                <div align="center" style="margin-top:50px;">
                    <?php
					echo "<h3>No funding in Sunesis</h3>";
					if (isset($report4))
						echo $report4; //$report4 declared in the act_read_pfr
					?>
                </div>
                </p>
            </div>
            <div id="tab5">
                <p>
                <div align="center" style="margin-top:50px;">
                    <?php
					echo "<h3>No funding in PFR</h3>";
					if (isset($report5))
						echo $report5; //$report5 declared in the act_read_pfr
					?>
                </div>
                </p>
            </div>
            <!--<div id="tab6"><p>
			<div align="center" style="margin-top:50px;">
				<?php
				/*				echo "<h3>Blank Learning Aims in PFR with funding</h3>";
				if(isset($report6))
					echo $report6;//$report6 declared in the act_read_pfr
				*/ ?>
			</div>
			</p>
		</div>-->
        </div>
    </div>
    <?php //if(isset($report))echo $report; 
	?>
</body>

</html>

<!--action="do.php?_action=read_pfr"-->