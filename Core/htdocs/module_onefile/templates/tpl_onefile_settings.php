<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Onefile Settings</title>
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
			<div class="Title" style="margin-left: 6px;">Onefile Settings</div>
			<div class="ButtonBar">
				<span class="btn btn-xs btn-default" onclick="window.location.href='do.php?_action=home_page';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
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
	<form class="form-horizontal" name="frmOnefileSettings" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<input type="hidden" name="_action" value="onefile_settings" />
		<input type="hidden" name="subaction" value="save_settings" />
		<input type="hidden" name="id" value="" />
		<div class="col-md-8">

			<div class="box box-primary">
                <div class="box-header with-border">
                    <span class="box-title">Settings</span>
                </div>
				<div class="box-body">	
                    <div class="form-group">
                        <label for="onefile.integration" class="col-sm-4 control-label fieldLabel_optional">Enable Integration:</label>
                        <div class="col-sm-8">
                            <?php
                            echo $enabled == '1' ?
                                '<input value="1" class="yes_no_toggle" type="checkbox" name="onefile.integration" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
                                '<input value="1" class="yes_no_toggle" type="checkbox" name="onefile.integration" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="onefile.X-CustomerToken" class="col-sm-4 control-label fieldLabel_optional">Customer Token:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="onefile.X-CustomerToken" id="onefile.X-CustomerToken" value="<?php echo $customerToken; ?>" maxlength="46" />
                        </div>
                        <div class="col-sm-2">
                            <span class="btn btn-info btn-sm" onclick="get_onefile_customer_id();">Test Connection</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="onefile.customerID" class="col-sm-4 control-label fieldLabel_optional">Customer ID & Name:</label>
                        <div class="col-sm-6">
                            <h5 class="text-info text-bold"><?php echo $customerID . ': ' . $customerName;?></h5>                            
                        </div>
                        <div class="col-sm-2">
                            <span class="btn btn-info btn-sm" onclick="get_onefile_customer_id();"><?php echo $customerID == '' ? 'GET' : 'Update';?> Customer ID</span>
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
</script>

</body>
</html>