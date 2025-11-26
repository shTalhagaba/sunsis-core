
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis - TNP and PMR Report</title>
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
        #home_postcode{text-transform:uppercase}
    </style>
</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">TNP and PMR Report</div>
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
        <div class="col-md-12">
            <div class="callout callout-info">
                <i class="fa fa-info-circle"></i> This report lists all the learners from the selected contracts with their TNPs and PMRs.
                This report will select the current submission's (<b>W<?php echo $current_submission; ?></b>) ILRs.
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="box-body" style="max-height: 500px; overflow-y: scroll;">
                <table class="table table-striped">
                    <thead><tr><th>&nbsp;</th><th>Contract Title</th></tr></thead>
                    <tbody>
                    <?php
                    $contracts = DAO::getResultset($link, "SELECT contracts.id, contracts.title FROM contracts WHERE contract_year = '$current_contract_year' ORDER BY contracts.title ", DAO::FETCH_ASSOC);
                    foreach($contracts AS $c)
                    {
                        echo '<tr>';
                        echo '<td><input class="chkContractChoice" type="checkbox" name="contracts[]" value="' . $c['id'] . '" /></td>';
                        echo '<td>' . $c['title'] . '</td>';
                        echo '</tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="box-footer">
                <button type="button" class="btn btn-primary pull-right" onclick="viewPMRs(); "> View</button>
            </div>
        </div>

        <div class="col-sm-9 table-responsive">
            <span class="btn btn-primary pull-right" onclick="exportPMRs();"><i class="fa fa-download"></i> </span>
            <table id="tblResult" class="table row-border">
                <thead><tr><th>LearnRefNumber</th><th>ULN</th><th>Family Name</th><th>Given Name</th><th>Employer</th><th>Business Code</th><th>Start Date</th><th>Planned End Date</th><th>Framework</th><th>TNPs</th><th>Total TNP</th><th>PMR1 Date</th><th>PMR1 Amount</th><th>PMR2 Date</th><th>PMR2 Amount</th><th>PMR3 Date</th><th>PMR3 Amount</th><th>PMR4 Date</th><th>PMR4 Amount</th><th>PMR5 Date</th><th>PMR5 Amount</th><th>PMR6 Date</th><th>PMR6 Amount</th><th>PMR7 Date</th><th>PMR7 Amount</th><th>PMR8 Date</th><th>PMR8 Amount</th><th>PMR9 Date</th><th>PMR9 Amount</th><th>PMR10 Date</th><th>PMR10 Amount</th><th>PMR11 Date</th><th>PMR11 Amount</th><th>PMR12 Date</th><th>PMR12 Amount</th><th>PMR13 Date</th><th>PMR13 Amount</th><th>PMR14 Date</th><th>PMR14 Amount</th><th>PMR15 Date</th><th>PMR15 Amount</th><th>PMR16 Date</th><th>PMR16 Amount</th><th>PMR17 Date</th><th>PMR17 Amount</th><th>PMR18 Date</th><th>PMR18 Amount</th><th>PMR19 Date</th><th>PMR19 Amount</th><th>PMR20 Date</th><th>PMR20 Amount</th><th>Total PMR</th></tr></thead>
                <tbody>
                <tr><td colspan="14"><i>Select contract(s) and click 'View' to find the learners with different aims in ILR and Training Record</i></td></tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js?n=<?php echo time(); ?>" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>

<script language="JavaScript">

    $(function(){
        $('.chkContractChoice').iCheck({
            checkboxClass: 'icheckbox_flat-red',
            radioClass: 'iradio_flat-red'
        });

    });

    function viewPMRs()
    {
        var selectedContracts = [];
        $("input[name='contracts[]']").each( function () {
            if(this.checked)
                selectedContracts.push(this.value);
        });
        if(selectedContracts.length == 0)
        {
            alert('Please select the contract');
            return false;
        }

        $('#tblResult > tbody').html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i> <img id="loading" src="images/loading.gif" alt="Loading" /></div>');

        var client = ajaxRequest('do.php?_action=view_pmrs&subaction=viewPMRs&contracts='+selectedContracts.join(','), null, null, viewPMRsCallback);
    }

    function viewPMRsCallback(client)
    {
        $('#tblResult > tbody').html(client.responseText);
    }

    function exportPMRs()
    {
        var selectedContracts = [];
        $("input[name='contracts[]']").each( function () {
            if(this.checked)
                selectedContracts.push(this.value);
        });
        if(selectedContracts.length == 0)
        {
            alert('Please select the contract');
            return false;
        }
        window.location.href='do.php?_action=view_pmrs&subaction=exportPMRs&contracts='+selectedContracts.join(',');
    }

</script>

</body>
</html>