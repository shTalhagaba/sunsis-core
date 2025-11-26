<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Attach Onefile Standards/Learning Aims</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
		.disabled{
			pointer-events:none;
			opacity:0.4;
		}
	</style>
</head>
<body>

<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Attach Onefile Standards/Learning Aims</div>
			<div class="ButtonBar">
				<span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
				<span class="btn btn-xs btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
			</div>
			<div class="ActionIconBar">

			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<?php $_SESSION['bc']->render($link); ?>
	</div>
</div>
<br>

<div class="row">
    <div class="col-sm-12">
        <div class="callout">
            <h4><?php echo htmlspecialchars((string)$framework->title); ?></h4>
        </div>
    </div>
</div>

<div class="row">
	<form class="form-horizontal" name="frmLinkSunOneAims" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<input type="hidden" name="_action" value="save_link_sun_one_aims" />
		<input type="hidden" name="framework_id" value="<?php echo $framework->id; ?>" />
		<div class="col-sm-12">

			<div class="box box-primary">
                <div class="box-header with-border">
                    <span class="box-title">Attach Onefile Standards / Learning Aims</span>
                </div>
				<div class="box-body">	
                    <div class="form-group">
                        <label for="onefile.integration" class="col-sm-4 control-label fieldLabel_optional">Onefile Organisation:</label>
                        <div class="col-sm-6">
                            <?php echo HTML::selectChosen('onefile_organisation_id', Onefile::getOnefileOrganisationsDdl($link), isset($onefile_saved_info->OrganisationID) ? $onefile_saved_info->OrganisationID : ''); ?>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-sm btn-info" id="btnOnefileRefresh" onclick="refresh_standards();"><i class="fa fa-refresh"></i> Refresh</span></td>
                        </div>
                    </div>
                    
                    <div class="form-group table-responsive">
                        <div class="table-responsive">
                            <?php echo $this->renderQualificationsTable($link, $view, $framework); ?>
                        </div>
                    </div>
				</div>

			</div>

		</div>

	</form>
</div>

<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script language="JavaScript">

	$(function() {
		
	});

    function get_onefile_customer_id()
    {
        var url = 'do.php?_action=ajax_onefile&subaction=getOnefileCustomerID';
        var client = ajaxRequest(url);
        if (client) 
        {
            if(client.responseText == 200)
            {
                alert("Connection with Onefile is successful");
                window.location.reload();
            }
            else if(client.responseText == 401)
            {
                alert("Error: 401 Unauthorized");
            }
            else if(client.responseText == 403)
            {
                alert("Error: 403 Forbidden");
            }
            else if(client.responseText == 500)
            {
                alert("Error: 500 Internal Server Error");
            }
            else
            {
                alert(client.responseText);
            }
        }
    }

    function refresh_standards()
    {
        var url = 'do.php?_action=ajax_onefile&subaction=getOnefileStandards'
        + "&organisation_id=" + encodeURIComponent($('#onefile_organisation_id').val());

        $("button#btnOnefileRefresh").attr('disabled', true);
        $("button#btnOnefileRefresh").html('<i class="fa fa-refresh fa-spin"></i> Please wait');

        function onefileRefreshCallback()
        {
            window.location.reload();
        }

        var client = ajaxRequest(url, null, null, onefileRefreshCallback);
    }    

    function save()
    {
        var myForm = document.forms["frmLinkSunOneAims"];
        var client = ajaxPostForm(myForm);
        if(client)
        {
            alert('The information has been saved successfully.');
            window.location.reload();
        }
        else
        {
            alert(client);
        }
    }
</script>

</body>
</html>