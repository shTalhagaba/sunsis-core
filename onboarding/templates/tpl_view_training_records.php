<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Enrolments</title>
    <link rel="stylesheet" href="css/common.css" type="text/css" />
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        #tooltip {
            width: 300px;
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
</head>

<body class="table-responsive">
    <div class="row">
        <div class="col-sm-12">
            <div class="banner">
                <div class="Title" style="margin-left: 6px;">Onboarding Enrolments</div>
                <div class="ButtonBar">
                    <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                </div>
                <div class="ActionIconBar">
                    <span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('applyFilter');showHideBlock('div_filter_crumbs');" title="Show/hide filters"></span>
                    <span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="exportToExcel('<?php echo $view->getViewName(); ?>');" title="Export to .csv file"></span>
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
            <?php echo $view->getFilterCrumbs(); ?>
        </div>
    </div>

    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div id="applyFilter" style="display:none">
                    <form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applySavedFilter" name="applyFilter">
                        <input type="hidden" name="_action" value="view_training_records" />
                        <?php
                        for ($i = 1; $i <= 18; $i++) {
                            $filterViewName = "ViewTrainingRecords_filter_chk" . $i;
                            $_f = $view->getFilter("filter_chk" . $i);
                            $_f_value = '';
                            if ($_f != '') {
                                $_f_value = $_f->getValue();
                            }
                            echo '<input type="hidden" name="' . $filterViewName . '" value="' . $_f_value . '" />';
                        }
                        ?>

                        <div id="filterBox" class="clearfix small">
                            <fieldset>
                                <div class="field float"><label>Status:</label> <?php echo $view->getFilterHTML('filter_status'); ?></div>
                                <div class="field float"><label>First Name:</label> <?php echo $view->getFilterHTML('filter_firstnames'); ?></div>
                                <div class="field float"><label>Surname:</label> <?php echo $view->getFilterHTML('filter_surname'); ?></div>
                                <div class="field float"><label>Employer:</label> <?php echo $view->getFilterHTML('filter_employer'); ?></div>
                                <div class="field float"><label>Standard:</label> <?php echo $view->getFilterHTML('filter_standard'); ?></div>
                                <div class="field float"><label>Stats:</label> <?php echo $view->getFilterHTML('filter_stats'); ?></div>
                                <div class="field float"><label>Type of Funding:</label> <?php echo $view->getFilterHTML('filter_type_of_funding'); ?></div>
                                <div class="field float"><label>Funding Model:</label> <?php echo $view->getFilterHTML('filter_funding_model'); ?></div>
                                <div class="field float"><label>Funding Model Specific:</label> <?php echo $view->getFilterHTML('filter_fund_model_extra'); ?></div>
                                <div class="field float" style="display: none;"><label>Training IDs:</label> <?php echo $view->getFilterHTML('filter_system_id'); ?></div>
                            </fieldset>
                            <fieldset>
                                <legend>Dates</legend>
                                <div class="field float"><label>Practical Period Start Date between:</label><?php echo $view->getFilterHTML('from_practical_period_start_date'); ?> &nbsp;and&nbsp; <?php echo $view->getFilterHTML('to_practical_period_start_date'); ?></div>
                                <div class="field float"><label>Practical Period End Date between:</label><?php echo $view->getFilterHTML('from_practical_period_end_date'); ?> &nbsp;and&nbsp; <?php echo $view->getFilterHTML('to_practical_period_end_date'); ?></div>
                            </fieldset>
                            <fieldset>
                                <div class="field float"><label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></div>
                                <div class="field float"><label>Sort by:</label> <?php echo $view->getFilterHTML(View::KEY_ORDER_BY); ?></div>
                            </fieldset>

                            <fieldset>
                                <input type="submit" value="Apply" />&nbsp;<input type="button" onclick="resetViewFilters(document.forms['applyFilter']);" value="Reset" />&nbsp;
                            </fieldset>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php 
        $learnerSignFilters = [
            3 => 'Pre IAG Form',
            5 => 'Learning Style',
            6 => 'Writing Assessment',
            8 => 'Skills Scan',
            11 => 'Apprenticeship Agreement',
            16 => 'OTJ Planner',
            14 => 'FDIL',
        ];
        $employerSignFilters = [
            1 => 'Initial Contract',
            9 => 'Skills Scan',
            12 => 'Apprenticeship Agreement',
            17 => 'OTJ Planner',
        ];
        $providerSignFilters = [
            2 => 'Initial Contract',
            4 => 'Pre IAG Form',
            7 => 'Writing Assessment',
            10 => 'Skills Scan',
            18 => 'OTJ Planner',
            15 => 'FDIL',
            13 => 'Onboarding',
        ];
        ?>
        <?php if(DB_NAME == "am_ela") { ?>
        <div class="row">
            <div class="col-sm-4">
                <table id="tblLearnerFilters" class="table table-bordered small">
                    <tr class="bg-gray">
                        <th class="text-center" >Learner</th>
                        <th class="text-center">
                            Yes <br>
                            <input class="selectAllYes" type="radio" value="1">
                        </th>
                        <th class="text-center">
                            No <br>
                            <input class="selectAllNo" type="radio" value="2">
                        </th>
                        <th class="text-center">
                            Don't apply <br>
                            <input class="selectAllReset" type="radio" value="">
                        </th>
                    </tr>
                    <?php 
                    foreach($learnerSignFilters AS $index => $value)
                    {
                        $_f = $view->getFilter("filter_chk" . $index);
                        $_f_value = '';
                        if ($_f != '') {
                            $_f_value = $_f->getValue();
                        }
                        $checked1 = '';
                        $checked2 = '';
                        $checked3 = '';
                        if ($_f_value == "1") {
                            $checked1 = ' checked="checked" ';
                        } elseif ($_f_value == "2") {
                            $checked2 = ' checked="checked" ';
                        } else {
                            $checked3 = ' checked="checked" ';
                        }
                        echo '<tr>';
                        echo '<th>' . $value . '</th>';
                        echo '<td class="text-center"><input class="chkAdditionalFilters" type="radio" name="filter_chk' . $index . '[]" value="1"' . $checked1 . '></td>';
                        echo '<td class="text-center"><input class="chkAdditionalFilters" type="radio" name="filter_chk' . $index . '[]" value="2"' . $checked2 . '></td>';
                        echo '<td class="text-center"><input class="chkAdditionalFilters" type="radio" name="filter_chk' . $index . '[]" value=""' . $checked3 . '></td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>
            <div class="col-sm-4">
                <table id="tblEmployerFilters" class="table table-bordered small">
                    <tr class="bg-gray">
                        <th class="text-center" >Employer</th>
                        <th class="text-center">
                            Yes <br>
                            <input class="selectAllYes" type="radio" value="1">
                        </th>
                        <th class="text-center">
                            No <br>
                            <input class="selectAllNo" type="radio" value="2">
                        </th>
                        <th class="text-center">
                            Don't apply <br>
                            <input class="selectAllReset" type="radio" value="">
                        </th>
                    </tr>
                    <?php 
                    foreach($employerSignFilters AS $index => $value)
                    {
                        $_f = $view->getFilter("filter_chk" . $index);
                        $_f_value = '';
                        if ($_f != '') {
                            $_f_value = $_f->getValue();
                        }
                        $checked1 = '';
                        $checked2 = '';
                        $checked3 = '';
                        if ($_f_value == "1") {
                            $checked1 = ' checked="checked" ';
                        } elseif ($_f_value == "2") {
                            $checked2 = ' checked="checked" ';
                        } else {
                            $checked3 = ' checked="checked" ';
                        }
                        echo '<tr>';
                        echo '<th>' . $value . '</th>';
                        echo '<td class="text-center"><input class="chkAdditionalFilters" type="radio" name="filter_chk' . $index . '[]" value="1"' . $checked1 . '></td>';
                        echo '<td class="text-center"><input class="chkAdditionalFilters" type="radio" name="filter_chk' . $index . '[]" value="2"' . $checked2 . '></td>';
                        echo '<td class="text-center"><input class="chkAdditionalFilters" type="radio" name="filter_chk' . $index . '[]" value=""' . $checked3 . '></td>';
                        echo '</tr>';

                    }
                    ?>
                </table>
            </div>
            <div class="col-sm-4">
                <table id="tblProviderFilters" class="table table-bordered small">
                    <tr class="bg-gray">
                        <th class="text-center" >Training Provider</th>
                        <th class="text-center">
                            Yes <br>
                            <input class="selectAllYes" type="radio" value="1">
                        </th>
                        <th class="text-center">
                            No <br>
                            <input class="selectAllNo" type="radio" value="2">
                        </th>
                        <th class="text-center">
                            Don't apply <br>
                            <input class="selectAllReset" type="radio" value="">
                        </th>
                    </tr>
                    <?php 
                    foreach($providerSignFilters AS $index => $value)
                    {
                        $_f = $view->getFilter("filter_chk" . $index);
                        $_f_value = '';
                        if ($_f != '') {
                            $_f_value = $_f->getValue();
                        }
                        $checked1 = '';
                        $checked2 = '';
                        $checked3 = '';
                        if ($_f_value == "1") {
                            $checked1 = ' checked="checked" ';
                        } elseif ($_f_value == "2") {
                            $checked2 = ' checked="checked" ';
                        } else {
                            $checked3 = ' checked="checked" ';
                        }
                        echo '<tr>';
                        echo '<th>' . $value . '</th>';
                        echo '<td class="text-center"><input class="chkAdditionalFilters" type="radio" name="filter_chk' . $index . '[]" value="1"' . $checked1 . '></td>';
                        echo '<td class="text-center"><input class="chkAdditionalFilters" type="radio" name="filter_chk' . $index . '[]" value="2"' . $checked2 . '></td>';
                        echo '<td class="text-center"><input class="chkAdditionalFilters" type="radio" name="filter_chk' . $index . '[]" value=""' . $checked3 . '></td>';
                        echo '</tr>';

                    }
                    ?>
                </table>
            </div>
        </div>
        <?php } ?>

        <div class="row">
            <div class="col-sm-12">
                <?php echo $view->render($link); ?>
            </div>
        </div>

        <div id="tooltip" style="position: fixed;display: none;">
            <div id="tooltip_content"></div>
        </div>
    </div>

    <!-- Popup calendar -->
    <div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

    <script type="text/javascript">
        const DB_NAME = '<?php echo DB_NAME; ?>';
    </script>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="js/common.js?n=<?php echo time(); ?>" type="text/javascript"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        $("input.chkAdditionalFilters").on("change", function() {
            var filter_name = "ViewTrainingRecords_" + this.name.replace('[]', '');
            $("form[id=applySavedFilter] input[name=" + filter_name + "]").val(this.value);
            $("form[id=applySavedFilter]").submit();
        });

        $('#tblTrainings tbody td').on("mouseover", function() {
            var td = $(this);
            var th = td.closest('tbody').prev('thead').find('> tr > th:eq(' + td.index() + ')');
            td.attr("title", th.text());
        });

        $("#tblTrainings tbody tr td").hover(function() {
            var col = $(this).parent().children().index($(this));
            //var row = $(this).parent().parent().children().index($(this).parent());
            var header_text = $("#tblTrainings thead tr:first th").eq(col)[0].innerHTML;
            var firstnames = $('td:nth-child(2)', $(this).parents('tr')).text();
            var surname = $('td:nth-child(3)', $(this).parents('tr')).text();
            entry_onmouseover('This is <b>' + header_text + '</b> of <b>' + firstnames + ' ' + surname + '</b>');

        }, function() {
            entry_onmouseout();
        });

        function div_filter_crumbs_onclick(div) {
            showHideBlock(div);
            showHideBlock('applyFilter');
        }

        function resetViewFilters() {
            var form = document.getElementById('applySavedFilter');

            form.reset();

            for (var i = 0; i < form.elements.length; i++) {
                if (form.elements[i].resetToDefault) {
                    form.elements[i].resetToDefault();
                }
            }
            for (var i = 1; i <= 18; i++) {
                form.elements["ViewTrainingRecords_filter_chk" + i].value = '';
            }
        }

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

        function setViewRadioValue(filter_name, filter_value)
        {
            if(filter_name === undefined) return;
            filter_name = "ViewTrainingRecords_" + filter_name.replace('[]', '');
            $("form[id=applySavedFilter] input[name=" + filter_name + "]").val(filter_value);
        }

        $('.selectAllYes, .selectAllNo, .selectAllReset').on('click', function () {
            const table = $(this).closest('table');
            const columnIndex = $(this).closest('th').index() - 1;
            const filterValue = this.value;
            table.find('tr').each(function () {
                $(this).find('td:eq(' + columnIndex + ') input[type=radio]').prop('checked', true);
                setViewRadioValue( $(this).find('td:eq(' + columnIndex + ') input[type=radio]').attr('name'), filterValue );
            });
            $("form[id=applySavedFilter]").submit();
        });
    </script>

    <!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
    <script type="text/javascript" src="/calendarPopup/CalendarPopup.js"></script>

    <!-- Initialise calendar popup -->
    <script type="text/javascript">
        <?php if (preg_match('/MSIE [1-6]/', $_SERVER['HTTP_USER_AGENT'])) { ?>
            var calPop = new CalendarPopup();
            calPop.showNavigationDropdowns();
        <?php } else { ?>
            var calPop = new CalendarPopup("calPop1");
            calPop.showNavigationDropdowns();
            document.write(getCalendarStyles());
        <?php } ?>
    </script>

</body>

</html>