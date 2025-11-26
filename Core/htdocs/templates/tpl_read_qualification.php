<?php /* @var $vo Qualification */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Qualification</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://jonmiles.github.io/bootstrap-treeview/bower_components/bootstrap/dist/css/bootstrap.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">View Qualification</div>
            <div class="ButtonBar">
                <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
                <?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type == User::TYPE_MANAGER) { ?>
                    <span class="btn btn-xs btn-default" onclick="window.location.replace('do.php?_action=edit_qualification&id=<?php echo rawurlencode($vo->id); ?>&internaltitle=<?php echo rawurlencode($internaltitle);?>');"><i class="fa fa-edit"></i> Edit</span>
                    <span class="btn btn-xs btn-default" onclick="window.location.replace('do.php?_action=view_qualification_tabular&id=<?php echo rawurlencode($vo->id); ?>&clients=<?php echo rawurlencode($clients)?>&internaltitle=<?php echo rawurlencode($internaltitle);?>');">Tabular View</span>
                <?php } ?>
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
    <div class="col-sm-4">
        <div class="box box-solid box-primary">
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr><th>Number (QAN)</th><td class="bg-success"><?php echo $vo->id; ?></td></tr>
                        <tr><th>Standard Ref.</th><td class="bg-success"><?php echo $vo->lsc_learning_aim; ?></td></tr>
                        <tr><th>Title</th><td class="bg-success"><?php echo $vo->title; ?></td></tr>
                        <tr><th>Awarding Body</th><td class="bg-success"><?php echo $vo->awarding_body; ?></td></tr>
                        <tr><th>Type</th><td class="bg-success"><?php echo DAO::getSingleValue($link, "SELECT CONCAT(id, ' - ', description) FROM lookup_qual_type WHERE id = '{$vo->qualification_type}'"); ?></td></tr>
                        <tr><th>Level</th><td class="bg-success"><?php echo DAO::getSingleValue($link, "SELECT CONCAT(id, ' - ', description) FROM lookup_qual_level WHERE id = '{$vo->level}'"); ?></td></tr>
                        <tr><th>Total Credit Values</th><td class="bg-success"><?php echo $vo->total_credit_value; ?></td></tr>
                        <tr><th>Guided Learning Hours</th><td class="bg-success"><?php echo $vo->guided_learning_hours; ?></td></tr>
                        <tr><th>Sector/Subject Area</th><td class="bg-success"><?php echo $vo->mainarea; ?></td></tr>
                        <tr><th>Regulation start date</th><td class="bg-success"><?php echo Date::toShort($vo->regulation_start_date); ?></td></tr>
                        <tr><th>Operational start date</th><td class="bg-success"><?php echo Date::toShort($vo->operational_start_date); ?></td></tr>
                        <tr><th>Operational end date</th><td class="bg-success"><?php echo Date::toShort($vo->operational_end_date); ?></td></tr>
                        <tr><th>Certification end date</th><td class="bg-success"><?php echo Date::toShort($vo->certification_end_date); ?></td></tr>
                        <tr><th>Description</th><td class="bg-success"><?php echo nl2br((string) $vo->description); ?></td></tr>
                        <tr><th>Assessment Method</th><td class="bg-success"><?php echo nl2br((string) $vo->assessment_method); ?></td></tr>
                        <tr><th>Internal Title</th><td class="bg-success"><?php echo ($vo->internaltitle); ?></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-8">
        <table class="table table-bordered">
            <tr>
                <th>Total Units</th>
                <td class="bg-success"><?php echo DAO::getSingleValue($link, "SELECT EXTRACTVALUE(evidences, 'count(//unit)') FROM qualifications WHERE auto_id = '{$vo->auto_id}'"); ?></td>
                <th>Mandatory Units</th>
                <td class="bg-success"><?php echo DAO::getSingleValue($link, "SELECT EXTRACTVALUE(evidences, 'count(//unit[@mandatory=\"true\"])') FROM qualifications WHERE auto_id = '{$vo->auto_id}'"); ?></td>
            </tr>
        </table>
        <p></p>
        <span class="btn btn-xs btn-info btnExpandAll"> <i class="fa fa-plus"></i> Expand All </span>
        <span class="btn btn-xs btn-info btnCollapseAll"> <i class="fa fa-minus"></i> Collapse All </span>
	<span class="btn btn-xs btn-info" onclick="window.location.reload();"> <i class="fa fa-refresh"></i> Refresh </span>
        <p></p>
        <div class="table-responsive">
            <?php if(count($this->main_tree) == 0) {?><div class="callout callout-info">Qualification structure is not yet setup.</div><?php } ?>
            <?php if(count($this->main_tree) > 0) {?><div id="tree"></div><?php } ?>
        </div>
    </div>

</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="https://jonmiles.github.io/bootstrap-treeview/js/bootstrap-treeview.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<script language="JavaScript">

    $(function() {
        $('#tree').treeview({data: getTree(), showTags:true});

        $("span.btnCollapseAll").on('click', function(){
            $('#tree').treeview('collapseAll', { silent: true });
        });

        $("span.btnExpandAll").on('click', function(){
            $('#tree').treeview('expandAll', { silent: true });
        });
    });

    function getTree()
    {
        var tree = <?php echo json_encode($this->main_tree); ?>;
        return tree;
    }




</script>

</body>
</html>