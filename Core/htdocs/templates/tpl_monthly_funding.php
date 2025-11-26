
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Monthly Funding Report</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

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
            <div class="Title" style="margin-left: 6px;">Periodic Funding Report</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
            </div>
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
        <div class="col-sm-12">
            <div class="box box-info" id="divPanels">
                <div class="box-header with-border">
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <form name="frmEPADates" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" name="_action" value="monthly_funding" />
                        <input type="hidden" name="recalculate" value="0" />
                        <input type="hidden" name="contracts" value="" />
                        <input type="hidden" name="debug" value="0" />
                        <div class="row well well-sm">
                            <div class="col-sm-8">
                                <?php $this->renderContracts($link, $contracts); ?>
                            </div>
                        </div>
                        <div class="row well well-sm">
                            <div class="col-sm-1">
                                From
                            </div>
                            <div class="col-sm-2">
                                <input name="fromDate" id="fromDate" class="date-picker" value="<?php echo Date::toShort($fromDate); ?>" />
                            </div>
                            <div class="col-sm-1">
                                To
                            </div>
                            <div class="col-sm-2">
				<input name="toDate" id="toDate" class="date-picker" value="<?php echo Date::toShort($toDate); ?>" />
                            </div>
                            <div class="col-sm-2"><button onclick="Refresh()" class="btn btn-xs btn-info"><i class="fa fa-refresh"></i> Apply</button></div>

                            <span class="btn btn-sm btn-default" onclick="window.location.href='javascript:ExportFull();'"><i class="fa fa-arrow-circle-o-left"></i> Full Report</span>

                            <!--<div class="col-sm-2"><button onclick="ExportFull()" class="btn btn-xs btn-info"><i class="fa fa-file"></i> Full Report</button></div>-->
                        </div>
                    </form>

                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="box box-success box-solid">
                                <div class="box-header with-border"><h1 class="box-title">Periodic Monthly Report</h1></div>
                                <div class="box-body">
                                    <div class="table-responsive">
                                        <?php
                                        echo '<table class="table table-bordered text-center">';
                                        echo '<thead class="bg-gray"><tr><th>Status</th><th>L2 Earnings</th><th>L2 Learners</th><th>L3 Earnings</th><th>L3 Learners</th><th>L4 Earnings</th><th>L4 Learners</th><th>Total Earnings</th><th>Total Learners</th></tr></thead>';
                                        echo '<tbody>';

                                        //$current_submission = DAO::getSingleValue($link, "SELECT submission FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE tr_id = $id ORDER BY contract_year DESC, submission DESC LIMIT 1");

                                        DAO::execute($link, "SET SESSION group_concat_max_len = 10000000;");

                                        $l2_starts = DAO::getObject($link, "SELECT COUNT(*) as n, GROUP_CONCAT(tr.id) as trs FROM tr
                                                                                    INNER JOIN contracts on contracts.id = tr.contract_id and contracts.funding_type = 1 $where
                                                                                    INNER JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
                                                                                    WHERE student_frameworks.title LIKE '%Level 2%' and status_code = 1 AND GREATEST(COALESCE(created,tr.start_date),tr.start_date) >= '$start_date' AND GREATEST(COALESCE(created,tr.start_date),tr.start_date) <= '$end_date'
                                                                                    AND (SELECT extractvalue(ilr, \"/Learner/LearningDelivery[AimType=1]/LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode\") FROM ilr WHERE ilr.tr_id = tr.id ORDER BY contract_id DESC, submission DESC LIMIT 1) != 1;
                                                                                    ");


                                        /*pre("SELECT COUNT(*) as n, GROUP_CONCAT(tr.id) as trs FROM tr
                                                                                    INNER JOIN contracts on contracts.id = tr.contract_id and contracts.funding_type = 1 $where
                                                                                    INNER JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
                                                                                    WHERE student_frameworks.title LIKE '%Level 2%' and status_code = 1 AND GREATEST(COALESCE(created,tr.start_date),tr.start_date) >= '$start_date' AND GREATEST(COALESCE(created,tr.start_date),tr.start_date) <= '$end_date'
                                                                                    AND (SELECT extractvalue(ilr, \"/Learner/LearningDelivery[AimType=1]/LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode\") FROM ilr WHERE ilr.tr_id = tr.id ORDER BY contract_id DESC, submission DESC LIMIT 1) != 1;
                                                                                    ");*/

                                        //pre($l2_starts);


                                        $l3_starts = DAO::getObject($link, "SELECT COUNT(*) as n, GROUP_CONCAT(tr.id) as trs FROM tr
                                                                                    INNER JOIN contracts on contracts.id = tr.contract_id and contracts.funding_type = 1 $where
                                                                                    INNER JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
                                                                                    WHERE student_frameworks.title LIKE '%Level 3%' and status_code = 1 AND GREATEST(COALESCE(created,tr.start_date),tr.start_date) >= '$start_date' AND GREATEST(COALESCE(created,tr.start_date),tr.start_date) <= '$end_date'
                                                                                    AND (SELECT extractvalue(ilr, \"/Learner/LearningDelivery[AimType=1]/LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode\") FROM ilr WHERE ilr.tr_id = tr.id ORDER BY contract_id DESC, submission DESC LIMIT 1) != 1;
                                                                                    ");

                                        $l4_starts = DAO::getObject($link, "SELECT COUNT(*) as n, GROUP_CONCAT(tr.id) as trs FROM tr
                                                                                    INNER JOIN contracts on contracts.id = tr.contract_id and contracts.funding_type = 1 $where
                                                                                    INNER JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
                                                                                    WHERE student_frameworks.title LIKE '%Level 4%' and status_code = 1 AND GREATEST(COALESCE(created,tr.start_date),tr.start_date) >= '$start_date' AND GREATEST(COALESCE(created,tr.start_date),tr.start_date) <= '$end_date'
                                                                                    AND (SELECT extractvalue(ilr, \"/Learner/LearningDelivery[AimType=1]/LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode\") FROM ilr WHERE ilr.tr_id = tr.id ORDER BY contract_id DESC, submission DESC LIMIT 1) != 1;
                                                                                    ");

                                        $l2_restarts = DAO::getObject($link, "SELECT COUNT(*) as n, GROUP_CONCAT(tr.id) as trs FROM tr
                                                                                    INNER JOIN contracts on contracts.id = tr.contract_id and contracts.funding_type = 1 $where
                                                                                    INNER JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
                                                                                    WHERE student_frameworks.title LIKE '%Level 2%' and status_code = 1 AND GREATEST(COALESCE(created,tr.start_date),tr.start_date) BETWEEN '$start_date' AND '$end_date'
                                                                                    AND (SELECT extractvalue(ilr, \"/Learner/LearningDelivery[AimType=1]/LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode\") FROM ilr WHERE ilr.tr_id = tr.id ORDER BY contract_id DESC, submission DESC LIMIT 1) = 1;
                                                                                    ");

                                        $l3_restarts = DAO::getObject($link, "SELECT COUNT(*) as n, GROUP_CONCAT(tr.id) as trs FROM tr
                                                                                    INNER JOIN contracts on contracts.id = tr.contract_id and contracts.funding_type = 1 $where
                                                                                    INNER JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
                                                                                    WHERE student_frameworks.title LIKE '%Level 3%' and status_code = 1 AND GREATEST(COALESCE(created,tr.start_date),tr.start_date) BETWEEN '$start_date' AND '$end_date'
                                                                                    AND (SELECT extractvalue(ilr, \"/Learner/LearningDelivery[AimType=1]/LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode\") FROM ilr WHERE ilr.tr_id = tr.id ORDER BY contract_id DESC, submission DESC LIMIT 1) = 1;
                                                                                    ");

                                        $l4_restarts = DAO::getObject($link, "SELECT COUNT(*) as n, GROUP_CONCAT(tr.id) as trs FROM tr
                                                                                    INNER JOIN contracts on contracts.id = tr.contract_id and contracts.funding_type = 1 $where
                                                                                    INNER JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
                                                                                    WHERE student_frameworks.title LIKE '%Level 4%' and status_code = 1 AND GREATEST(COALESCE(created,tr.start_date),tr.start_date) BETWEEN '$start_date' AND '$end_date'
                                                                                    AND (SELECT extractvalue(ilr, \"/Learner/LearningDelivery[AimType=1]/LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode\") FROM ilr WHERE ilr.tr_id = tr.id ORDER BY contract_id DESC, submission DESC LIMIT 1) = 1;
                                                                                    ");


                                        $l2_completions = DAO::getObject($link, "SELECT COUNT(*) as n, GROUP_CONCAT(tr.id) as trs FROM tr
                                                                                    INNER JOIN contracts on contracts.id = tr.contract_id and contracts.funding_type = 1 $where
                                                                                    INNER JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
                                                                                    WHERE student_frameworks.title LIKE '%Level 2%' AND status_code = 2 and GREATEST(COALESCE(marked_date,closure_date),closure_date) >= '$start_date' AND GREATEST(COALESCE(marked_date,closure_date),closure_date) <= '$end_date';
                                                                                    ");

                                        $l3_completions = DAO::getObject($link, "SELECT COUNT(*) as n, GROUP_CONCAT(tr.id) as trs FROM tr
                                                                                    INNER JOIN contracts on contracts.id = tr.contract_id and contracts.funding_type = 1 $where
                                                                                    INNER JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
                                                                                    WHERE student_frameworks.title LIKE '%Level 3%' AND status_code = 2 and GREATEST(COALESCE(marked_date,closure_date),closure_date) >= '$start_date' AND GREATEST(COALESCE(marked_date,closure_date),closure_date) <= '$end_date';
                                                                                    ");

                                        $l4_completions = DAO::getObject($link, "SELECT COUNT(*) as n, GROUP_CONCAT(tr.id) as trs FROM tr
                                                                                    INNER JOIN contracts on contracts.id = tr.contract_id and contracts.funding_type = 1 $where
                                                                                    INNER JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
                                                                                    WHERE student_frameworks.title LIKE '%Level 4%' AND status_code = 2 and GREATEST(COALESCE(marked_date,closure_date),closure_date) >= '$start_date' AND GREATEST(COALESCE(marked_date,closure_date),closure_date) <= '$end_date';
                                                                                    ");

                                        $l2_with = DAO::getObject($link, "SELECT COUNT(*) as n, GROUP_CONCAT(tr.id) as trs FROM tr
                                                                                    INNER JOIN contracts on contracts.id = tr.contract_id and contracts.funding_type = 1 $where
                                                                                    INNER JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
                                                                                    WHERE student_frameworks.title LIKE '%Level 2%' AND status_code = 3 and GREATEST(COALESCE(marked_date,closure_date),closure_date) >= '$start_date' AND GREATEST(COALESCE(marked_date,closure_date),closure_date) <= '$end_date';
                                                                                    ");

                                        $l3_with = DAO::getObject($link, "SELECT COUNT(*) as n, GROUP_CONCAT(tr.id) as trs FROM tr
                                                                                    INNER JOIN contracts on contracts.id = tr.contract_id and contracts.funding_type = 1 $where
                                                                                    INNER JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
                                                                                    WHERE student_frameworks.title LIKE '%Level 3%' AND status_code = 3 and GREATEST(COALESCE(marked_date,closure_date),closure_date) >= '$start_date' AND GREATEST(COALESCE(marked_date,closure_date),closure_date) <= '$end_date';
                                                                                    ");

                                        $l4_with = DAO::getObject($link, "SELECT COUNT(*) as n, GROUP_CONCAT(tr.id) as trs FROM tr
                                                                                    INNER JOIN contracts on contracts.id = tr.contract_id and contracts.funding_type = 1 $where
                                                                                    INNER JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
                                                                                    WHERE student_frameworks.title LIKE '%Level 4%' AND status_code = 3 and GREATEST(COALESCE(marked_date,closure_date),closure_date) >= '$start_date' AND GREATEST(COALESCE(marked_date,closure_date),closure_date) <= '$end_date';
                                                                                    ");

                                        $l2_break = DAO::getObject($link, "SELECT COUNT(*) as n, GROUP_CONCAT(tr.id) as trs FROM tr
                                                                                    INNER JOIN contracts on contracts.id = tr.contract_id and contracts.funding_type = 1 $where
                                                                                    INNER JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
                                                                                    WHERE student_frameworks.title LIKE '%Level 2%' AND status_code = 6 and GREATEST(COALESCE(marked_date,closure_date),closure_date) >= '$start_date' AND GREATEST(COALESCE(marked_date,closure_date),closure_date) <= '$end_date';
                                                                                    ");

                                        $l3_break = DAO::getObject($link, "SELECT COUNT(*) as n, GROUP_CONCAT(tr.id) as trs FROM tr
                                                                                    INNER JOIN contracts on contracts.id = tr.contract_id and contracts.funding_type = 1 $where
                                                                                    INNER JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
                                                                                    WHERE student_frameworks.title LIKE '%Level 3%' AND status_code = 6 and GREATEST(COALESCE(marked_date,closure_date),closure_date) >= '$start_date' AND GREATEST(COALESCE(marked_date,closure_date),closure_date) <= '$end_date';
                                                                                    ");

                                        $l4_break = DAO::getObject($link, "SELECT COUNT(*) as n, GROUP_CONCAT(tr.id) as trs FROM tr
                                                                                    INNER JOIN contracts on contracts.id = tr.contract_id and contracts.funding_type = 1 $where
                                                                                    INNER JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
                                                                                    WHERE student_frameworks.title LIKE '%Level 4%' AND status_code = 6 and GREATEST(COALESCE(marked_date,closure_date),closure_date) >= '$start_date' AND GREATEST(COALESCE(marked_date,closure_date),closure_date) <= '$end_date';
                                                                                    ");

                                        $l2_cont = DAO::getObject($link, "SELECT COUNT(*) as n, GROUP_CONCAT(tr.id) as trs FROM tr
                                                                                    INNER JOIN contracts on contracts.id = tr.contract_id and contracts.funding_type = 1 $where
                                                                                    INNER JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
                                                                                    WHERE student_frameworks.title LIKE '%Level 2%' AND status_code = 1 and GREATEST(COALESCE(created,tr.start_date),tr.start_date) <= '$end_date';
                                                                                    ");

                                        $l3_cont = DAO::getObject($link, "SELECT COUNT(*) as n, GROUP_CONCAT(tr.id) as trs FROM tr
                                                                                    INNER JOIN contracts on contracts.id = tr.contract_id and contracts.funding_type = 1 $where
                                                                                    INNER JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
                                                                                    WHERE student_frameworks.title LIKE '%Level 3%' AND status_code = 1 and GREATEST(COALESCE(created,tr.start_date),tr.start_date) <= '$end_date';
                                                                                    ");

                                        $l4_cont = DAO::getObject($link, "SELECT COUNT(*) as n, GROUP_CONCAT(tr.id) as trs FROM tr
                                                                                    INNER JOIN contracts on contracts.id = tr.contract_id and contracts.funding_type = 1 $where
                                                                                    INNER JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
                                                                                    WHERE student_frameworks.title LIKE '%Level 4%' AND status_code = 1 and GREATEST(COALESCE(created,tr.start_date),tr.start_date) <= '$end_date';
                                                                                    ");

                                        //$predictions = new FundingPredictionPeriod($link, $contracts, 25, "", "", "", $current_submission, "", $tr_id);
                                        //$data = $predictions->get_learnerdata();


                                        echo '<tr>';
                                        echo '<td>New Starts</td>';
                                        $gt = 0;
                                        $l2 =  round($this->getTRsFunding($link, $l2_starts->trs,$start_date));
                                        $l3 =  round($this->getTRsFunding($link, $l3_starts->trs,$start_date));
                                        $l4 =  round($this->getTRsFunding($link, $l4_starts->trs,$start_date));
                                        $total = $l2+$l3+$l4;
                                        $gt+=$total;
                                        echo "<td><a href=javascript:exporf('"  . $l2_starts->trs . "','" . $start_date . "');>&pound;" . $l2 . "</td>";
                                        echo "<td><a href=javascript:expor('"  . $l2_starts->trs . "');>" . $l2_starts->n . "</td>";
                                        echo "<td><a href=javascript:exporf('"  . $l3_starts->trs . "','" . $start_date . "');>&pound;" . $l3 . "</td>";
                                        echo "<td><a href=javascript:expor('"  . $l3_starts->trs . "');>" . $l3_starts->n . "</td>";
                                        echo "<td><a href=javascript:exporf('"  . $l4_starts->trs . "','" . $start_date . "');>&pound;" . $l4 . "</td>";
                                        echo "<td><a href=javascript:expor('"  . $l4_starts->trs . "');>" . $l4_starts->n . "</td>";
                                        echo '<td>&pound;' . $total . '</td>';
                                        echo '<td>' . ($l2_starts->n + $l3_starts->n + $l4_starts->n). '</td>';
                                        echo '</tr>';

                                        $l2 =  round($this->getTRsFunding($link, $l2_restarts->trs,$start_date));
                                        $l3 =  round($this->getTRsFunding($link, $l3_restarts->trs,$start_date));
                                        $l4 =  round($this->getTRsFunding($link, $l4_restarts->trs,$start_date));
                                        $total = $l2+$l3+$l4;
                                        $gt+=$total;

                                        echo '<tr>';
                                        echo '<td>Re-starts</td>';
                                        echo "<td><a href=javascript:exporf('"  . $l2_restarts->trs . "','" . $start_date . "');>&pound;" . $l2 . "</td>";
                                        echo "<td><a href=javascript:expor('"  . $l2_restarts->trs . "');>" . $l2_restarts->n . "</td>";
                                        echo "<td><a href=javascript:exporf('"  . $l3_restarts->trs . "','" . $start_date . "');>&pound;" . $l3 . "</td>";
                                        echo "<td><a href=javascript:expor('"  . $l3_restarts->trs . "');>" . $l3_restarts->n . "</td>";
                                        echo "<td><a href=javascript:exporf('"  . $l4_restarts->trs . "','" . $start_date . "');>&pound;" . $l4 . "</td>";
                                        echo "<td><a href=javascript:expor('"  . $l4_restarts->trs . "');>" . $l4_restarts->n . "</td>";
                                        echo '<td>&pound;'.$total.'</td>';
                                        echo '<td>' . ($l4_restarts->n + $l3_restarts->n + $l4_restarts->n). '</td>';
                                        echo '</tr>';

                                        $l2_all = $l2_starts->trs . "," . $l2_restarts->trs . "," . $l2_completions->trs . "," . $l2_with->trs . "," . $l2_break->trs . "," . $l2_cont->trs;
                                        $l3_all = $l3_starts->trs . "," . $l3_restarts->trs . "," . $l3_completions->trs . "," . $l3_with->trs . "," . $l3_break->trs . "," . $l3_cont->trs;
                                        $l4_all = $l4_starts->trs . "," . $l4_restarts->trs . "," . $l4_completions->trs . "," . $l4_with->trs . "," . $l4_break->trs . "," . $l4_cont->trs;

                                        $l2 =  round($this->getTRsFundingFS($link, implode(",",array_filter(explode(",",$l2_all))), $start_date));
                                        $l3 =  round($this->getTRsFundingFS($link, implode(",",array_filter(explode(",",$l3_all))), $start_date));
                                        $l4 =  round($this->getTRsFundingFS($link, implode(",",array_filter(explode(",",$l4_all))), $start_date));
                                        $total = $l2+$l3+$l4;
                                        $gt+=$total;

                                        echo '<tr>';
                                        echo '<td>Functional skills</td>';
                                        echo '<td>&pound;'.$l2.'</td>';
                                        echo '<td>&nbsp;</td>';
                                        echo '<td>&pound;'.$l3.'</td>';
                                        echo '<td>&nbsp;</td>';
                                        echo '<td>&pound;'.$l4.'</td>';
                                        echo '<td>&nbsp;</td>';
                                        echo '<td>&pound;'.$total.'</td>';
                                        echo '<td>&nbsp;</td>';
                                        echo '</tr>';


                                        $l2 =  round($this->getTRsFunding($link, $l2_completions->trs,$start_date));
                                        $l3 =  round($this->getTRsFunding($link, $l3_completions->trs,$start_date));
                                        $l4 =  round($this->getTRsFunding($link, $l4_completions->trs,$start_date));
                                        $total = $l2+$l3+$l4;
                                        $gt+=$total;

                                        echo '<tr>';
                                        echo '<td>Completion</td>';
                                        echo "<td><a href=javascript:exporf('"  . $l2_completions->trs . "','" . $start_date . "');>&pound;" . $l2 . "</td>";
                                        echo "<td><a href=javascript:expor('"  . $l2_completions->trs . "');>" . $l2_completions->n . "</td>";
                                        echo "<td><a href=javascript:exporf('"  . $l3_completions->trs . "','" . $start_date . "');>&pound;" . $l3 . "</td>";
                                        echo "<td><a href=javascript:expor('"  . $l3_completions->trs . "');>" . $l3_completions->n . "</td>";
                                        echo "<td><a href=javascript:exporf('"  . $l4_completions->trs . "','" . $start_date . "');>&pound;" . $l4 . "</td>";
                                        echo "<td><a href=javascript:expor('"  . $l4_completions->trs . "');>" . $l4_completions->n . "</td>";
                                        echo '<td>&pound;'.$total.'</td>';
                                        echo '<td>' . ($l2_completions->n + $l3_completions->n + $l4_completions->n). '</td>';
                                        echo '</tr>';


                                        $l2 =  round($this->getTRsFunding($link, $l2_with->trs,$start_date));
                                        $l3 =  round($this->getTRsFunding($link, $l3_with->trs,$start_date));
                                        $l4 =  round($this->getTRsFunding($link, $l4_with->trs,$start_date));
                                        $total = $l2+$l3+$l4;
                                        $gt+=$total;

                                        echo '<tr>';
                                        echo '<td>Withdrawal</td>';
                                        echo "<td><a href=javascript:exporf('"  . $l2_with->trs . "','" . $start_date . "');>&pound;" . $l2 . "</td>";
                                        echo "<td><a href=javascript:expor('"  . $l2_with->trs . "');>" . $l2_with->n . "</td>";
                                        echo "<td><a href=javascript:exporf('"  . $l3_with->trs . "','" . $start_date . "');>&pound;" . $l3 . "</td>";
                                        echo "<td><a href=javascript:expor('"  . $l3_with->trs . "');>" . $l3_with->n . "</td>";
                                        echo "<td><a href=javascript:exporf('"  . $l4_with->trs . "','" . $start_date . "');>&pound;" . $l4 . "</td>";
                                        echo "<td><a href=javascript:expor('"  . $l4_with->trs . "');>" . $l4_with->n . "</td>";
                                        echo '<td>&pound;'.$total.'</td>';
                                        echo '<td>' . ($l2_with->n + $l3_with->n + $l4_with->n). '</td>';
                                        echo '</tr>';

                                        $l2 =  round($this->getTRsFunding($link, $l2_break->trs,$start_date));
                                        $l3 =  round($this->getTRsFunding($link, $l3_break->trs,$start_date));
                                        $l4 =  round($this->getTRsFunding($link, $l4_break->trs,$start_date));
                                        $total = $l2+$l3+$l4;
                                        $gt+=$total;

                                        echo '<tr>';
                                        echo '<td>Break in learning</td>';
                                        echo "<td><a href=javascript:exporf('"  . $l2_break->trs . "','" . $start_date . "');>&pound;" . $l2 . "</td>";
                                        echo "<td><a href=javascript:expor('"  . $l2_break->trs . "');>" . $l2_break->n . "</td>";
                                        echo "<td><a href=javascript:exporf('"  . $l3_break->trs . "','" . $start_date . "');>&pound;" . $l3 . "</td>";
                                        echo "<td><a href=javascript:expor('"  . $l3_break->trs . "');>" . $l3_break->n . "</td>";
                                        echo "<td><a href=javascript:exporf('"  . $l4_break->trs . "','" . $start_date . "');>&pound;" . $l4 . "</td>";
                                        echo "<td><a href=javascript:expor('"  . $l4_break->trs . "');>" . $l4_break->n . "</td>";
                                        echo '<td>&pound;'.$total.'</td>';
                                        echo '<td>' . ($l2_break->n + $l3_break->n + $l4_break->n). '</td>';
                                        echo '</tr>';

                                        $l2 =  round($this->getTRsFunding($link, $l2_cont->trs,$start_date));
                                        $l3 =  round($this->getTRsFunding($link, $l3_cont->trs,$start_date));
                                        $l4 =  round($this->getTRsFunding($link, $l4_cont->trs,$start_date));
                                        $total = $l2+$l3+$l4;
                                        $gt+=$total;

                                        echo '<tr>';
                                        echo '<td>On-programme</td>';
                                        echo "<td><a href=javascript:exporf('"  . $l2_cont->trs . "','" . $start_date . "');>&pound;" . $l2 . "</td>";
                                        echo "<td><a href=javascript:expor('"  . $l2_cont->trs . "');>" . $l2_cont->n . "</td>";
                                        echo "<td><a href=javascript:exporf('"  . $l3_cont->trs . "','" . $start_date . "');>&pound;" . $l3 . "</td>";
                                        echo "<td><a href=javascript:expor('"  . $l3_cont->trs . "');>" . $l3_cont->n . "</td>";
                                        echo "<td><a href=javascript:exporf('"  . $l4_cont->trs . "','" . $start_date . "');>&pound;" . $l4 . "</td>";
                                        echo "<td><a href=javascript:expor('"  . $l4_cont->trs . "');>" . $l4_cont->n . "</td>";
                                        echo '<td>&pound;'.$total.'</td>';
                                        echo '<td>' . ($l2_cont->n + $l3_cont->n + $l4_cont->n). '</td>';
                                        echo '</tr>';

                                        echo '<tr class="bg-gray">';
                                        echo '<td>Grand Total</td>';
                                        echo "<td>&nbsp;</td>";
                                        echo "<td>&nbsp;</td>";
                                        echo "<td>&nbsp;</td>";
                                        echo "<td>&nbsp;</td>";
                                        echo "<td>&nbsp;</td>";
                                        echo "<td>&nbsp;</td>";
                                        echo '<td>&pound;'.$gt.'</td>';
                                        echo '<td>&nbsp;</td>';
                                        echo '</tr>';

                                        echo '</tbody>';
                                        echo '</table> ';

                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="frmFilters" name="frmFilters">
    <input type="hidden" name="_action" value="view_epa_dash_learners" />
    <input type="hidden" name="_reset" value="1" />
    <input type="hidden" name="filter_ids" value="" />
</form>

<form name="frmDashPDF" action="do.php?_action=operations_dashboard" method="post">
    <input type="hidden" name="subaction" value="generate_dash_pdf" />
    <input type="hidden" name="html" value="" />
</form>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/plugins/html2canvas/html2canvas.js"></script>
<script src="/assets/adminlte/plugins/FileSaver/FileSaver.js"></script>

<script language="JavaScript">

    function showDetail(ids)
    {
        var frmFilters = document.forms["frmFilters"];
        frmFilters.filter_ids.value = ids;

        frmFilters.submit();
    }

    $(function(){
        $('#manager').on('change', function(){
            showWaitingOverlays();
            var qs = '&fromDate=' + encodeURIComponent($("#fromDate").val()) +
                    '&toDate=' + encodeURIComponent($("#toDate").val());
        });

        $('.date-picker').datepicker( {

            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'dd/mm/yy',

            onSelect: function(dateText) {
                showWaitingOverlays();
                var qs = '&fromDate=' + encodeURIComponent($("#fromDate").val()) +
                        '&toDate=' + encodeURIComponent($("#toDate").val());
            }
        });

    });

    function expor(detail)
    {
        window.location.href='do.php?_action=export_success_rates&trs='+detail;
    }

    function exporf(detail, start_date)
    {
        window.location.href='do.php?_action=export_success_rates&funding=1&start_date='+start_date+'&trs='+detail;
    }

    function ExportFull()
    {
        sd = document.forms[0].fromDate.value;
        window.location.href='do.php?_action=export_monthly_funding&start_date='+sd;
    }

    function Refresh()
    {
        form = document.forms[0];
        var buttons = form.elements['evidenceradio'];
        var evidence_id = '';
        var selected = 0;
        var contracts = Array();
        var index = 0;
        for(var i = 0; i < buttons.length; i++)
        {
            if(buttons[i].checked)
            {
                contracts[index] = buttons[i].value;
                index++;
                selected = 1;
            }
        }

        if(selected == 0)
        {
            alert("Please select a contract");
            return false;
        }

        form.contracts.value = contracts.join(",");
        //alert(form.contracts.value);
        form.submit();
    }

    function calculate()
    {
        form = document.forms[0];
        form.recalculate.value=1;
        form.submit();
    }

</script>

</body>
</html>
