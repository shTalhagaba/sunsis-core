
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis - Salesforce Integration</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
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
            <div class="Title" style="margin-left: 6px;">Sunesis - Salesforce Integration</div>
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
    <div class="row small">
        <div class="col-sm-6">
            <form class="form-horizontal" name="frmImportLearners" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="_action" value="ajax_tracking" />
                <input type="hidden" name="subaction" value="importLearnersFromSalesforce" />
                <div class="box-header with-border"><h2 class="box-title">Search record in Salesforce<small> this action will bring all matching records from Salesforce</small></h2></div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="FirstName" class="col-sm-4 control-label">First Name:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="FirstName" id="FirstName" value="" maxlength="100" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="LastName" class="col-sm-4 control-label">Last Name:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="LastName" id="LastName" value="" maxlength="100" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Birthdate" class="col-sm-4 control-label">Birthdate:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::datebox('Birthdate', '', false); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="National_Insurance__c" class="col-sm-4 control-label">National Insurance:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="National_Insurance__c" id="National_Insurance__c" value="" maxlength="9" />
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="button" id="btnImportLearner" class="btn btn-primary pull-right" onclick="importLearners(); "><i class="fa fa-cloud-download"></i> Import Learner</button>
                </div>
            </form>

        </div>

        <div class="col-sm-6">
            <div class="callout callout-info">
                <p><span class="fa fa-info-circle"></span> Salesforce Candidates data may be imported into Sunesis.</p>
                <p><span class="fa fa-info-circle"></span> Enter whatever minimum information you have for a learner and Sunesis will bring all the matching results from Salesforce.</p>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-12">
            <p><strong>Matching Records Table</strong></p>
            <div class="table-responsive">
                <table id="tblResult" class="table table-striped small">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Gender</th>
                        <th>Birthdate</th>
                        <th>National Insurance</th>
                        <th>Phone</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Additional Information</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr><td colspan="10"><i>Enter the information in search panel and click 'Import Learner' to fetch the matching record(s) from Salesforce</i></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>

<script language="JavaScript">
    $(function(){
        $('#input_Birthdate').attr('class', 'datepicker optional form-control');


    });

    function importLearners()
    {
        <?php if($_SESSION['user']->induction_access == 'R'){?>
        alert('You do not have sufficient privileges to perform this action.');
        return;
        <?php } ?>
        if($('#FirstName').val().trim() == '' && $('#LastName').val().trim() == '' && $('#input_Birthdate').val().trim() == '' && $('#National_Insurance__c').val().trim() == '')
        {
            alert('Please enter information in at least one of the fields');
            return;
        }

        $('#btnImportLearner').prop('disabled', true);

        $('#tblResult > tbody').html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i> <img id="loading" src="images/loading.gif" alt="Loading" /></div>');

        var myForm = document.forms["frmImportLearners"];

        var client = ajaxPostForm(myForm, importLearnersCallback);
    }

    function importLearnersCallback(client, error)
    {
        if(!error)
        {
            //console.log(client.responseText);
            $('#tblResult > tbody').html(client.responseText);

            $('.chkSelectedLearners').iCheck({
                checkboxClass: 'icheckbox_flat-red',
                radioClass: 'iradio_flat-red'
            });
        }
        else
        {
            alert('Operation failed, please raise a support request with the details of your action.');
            $('#tblResult > tbody').html('<tr><td colspan="12"><i>Enter the information in search panel and click \'Import Learner\' to fetch the matching record(s) from Salesforce</i></td></tr>');
        }

        $('#btnImportLearner').prop('disabled', false);
    }

    function createLearnerInSunesis()
    {
        var stringLearners = [];
        $("input[name='selectedLearners[]']").each( function () {
            if(this.checked)
                stringLearners.push(this.value);
        });

        if(stringLearners.length == 0)
        {
            alert('Please select a learner');
            return;
        }
	if(stringLearners.length > 1)
        {
            alert('Please select only one learner');
            return;
        }

        stringLearners = stringLearners.join(',');

        var client = ajaxRequest('do.php?_action=ajax_tracking&subaction=createSFLearnersInSunesis&sf_ids='	+ stringLearners, null, null, createSFLearnersInSunesisCallback);
    }

    function createSFLearnersInSunesisCallback(client, error)
    {
        if(!error)
        {
            alert('The selected learner is created successfully in Sunesis.');
            if(client.responseText == 0)
                window.location.href = 'do.php?_action=induction_home&selected_tab=tab6';
            else
                window.location.href = 'do.php?_action=edit_inductee&id='+client.responseText;
        }

    }

</script>

</body>
</html>