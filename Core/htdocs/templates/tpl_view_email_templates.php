
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>View Templates</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
	</style>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">View Templates</div>
			<div class="ButtonBar">
			</div>
			<div class="ActionIconBar">
			</div>
		</div>
	</div>
</div>

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-11 col-sm-offset-1">
            <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>?_action=view_email_templates">
                <input type="hidden" name="_action" value="view_email_templates">
                <div class="form-group">
                    <label for="template_id" class="col-sm-4 control-label ">Select Template:</label>
                    <div class="col-sm-8">
                        <?php echo HTML::select('template_id', DAO::getResultset($link, "SELECT id, template_type FROM email_templates WHERE id = 3 OR template_type LIKE 'Master%' ORDER BY template_type;"), $template_id, true); ?>
                        <button type="submit" class="btn btn-info btn-xs"><i class="fa fa-search"></i> Click to view</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
	<div class="row">
		<div class="col-sm-11 col-sm-offset-1">
			<div class="well well-sm table-responsive">
                <p><br></p>
				<?php echo $template; ?>
			</div>
		</div>
	</div>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<script language="JavaScript">

	function div_filter_crumbs_onclick(div)
	{
		showHideBlock(div);
		showHideBlock('div_filters');
		showHideBlock('applySavedFilter');
	}
</script>

</body>
</html>