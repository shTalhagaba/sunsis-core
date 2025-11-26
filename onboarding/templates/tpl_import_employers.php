<?php /* @var $link PDO */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Employers Import</title>
    <link rel="stylesheet" href="css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Employers Import </div>
            <div class="ButtonBar">

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

<div class="container-fluid">
    <!--    <div class="row">-->
    <!--        <div class="col-sm-12">-->
    <!--            <form class="form-horizontal" name="frmImportFromDirectory" id="frmImportFromDirectory"-->
    <!--                  action="--><?php //echo $_SERVER['PHP_SELF']; ?><!--" method="post">-->
    <!--                <input type="hidden" name="_action" value="import_employers" />-->
    <!--                <input type="hidden" name="subaction" value="importFromDirectory" />-->
    <!---->
    <!--                <input type="submit" value="Import from Directory">-->
    <!--            </form>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--    <div class="row">-->
    <!--        <div class="col-sm-12">-->
    <!--            <form class="form-horizontal" name="frmImportLearnersFromDirectory" id="frmImportLearnersFromDirectory"-->
    <!--                  action="--><?php //echo $_SERVER['PHP_SELF']; ?><!--" method="post">-->
    <!--                <input type="hidden" name="_action" value="import_employers" />-->
    <!--                <input type="hidden" name="subaction" value="importLearnersFromDirectory" />-->
    <!---->
    <!--                <input type="submit" value="Import learners from Directory">-->
    <!--            </form>-->
    <!--        </div>-->
    <!--    </div>-->

    <form class="form-horizontal" name="frmImportEmployer" id="frmImportEmployer"
          action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"  enctype="multipart/form-data">
        <input type="hidden" name="_action" value="import_employers" />
        <input type="hidden" name="subaction" value="import" />
        <div class="row">
            <div class="col-sm-7">
                <div class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title">Upload .csv File</h2>
                    </div>
                    <div class="box-body">
                        <div class="col-sm-12">
                            <div class="callout callout-default">
                                <p class="text-info"><i class="fa fa-info-circle"></i> Only .csv files are allowed.</p>
                                <p class="text-info"><i class="fa fa-info-circle"></i> Maximum file size is 5MB.</p>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <p><br></p>
                            <input type="file" name="file_employers" />
                        </div>
                    </div>
                    <div class="box-footer">
                        <p class="text-center" style="display: none;" id="_spinner"><span class="text-bold"><i class="fa fa-refresh fa-spin fa-lg"></i>  Importing ...</span></p>
                        <input type="button" id="btnSubmit" onclick="save();" class="btn btn-md btn-primary btn-block" value="Submit"/>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-bordered table-condensed">
                    <tr>
                        <th>Import ID</th>
                        <th>Import File</th>
                        <th>Import File Modified Time</th>
                        <th>Import File Size</th>
                        <th>Import File Outcome</th>
                        <th>Import Timestamp</th>
                        <th>Actions</th>
                        <?php echo $_SESSION['user']->username == "admin" ? "<th>Admin</th>" : ""; ?>
                    </tr>
                    <?php
                    $records = DAO::getResultset($link, "SELECT * FROM data_imports WHERE import_entity = 'employer' ORDER BY import_timestamp DESC", DAO::FETCH_ASSOC);
                    if($records == 0)
                    {
                        echo '<tr><td colspan="8"><i>No records found.</i></td></tr>';
                    }
                    else
                    {
                        foreach($records AS $row)
                        {
                            echo '<tr>';
                            echo '<td>' . $row['import_id'] . '</td>';
                            echo '<td>' . $row['import_file'] . '</td>';
                            echo '<td>' . date(Date::DATETIME, $row['import_file_modified_time']) . '</td>';
                            echo '<td>' . Repository::formatFileSize($row['import_file_size']) . '</td>';
                            echo $row['import_successful'] == 1 ? '<td>Successful</td>' : '<td>Unsuccessful</td>';
                            echo '<td>' . Date::to($row['import_timestamp'], Date::DATETIME) . '</td>';
                            if(is_file(Repository::getRoot() . "/DataImports/employers/" . $row['import_file']))
                            {
                                $file = new RepositoryFile(Repository::getRoot() . "/DataImports/employers/" . $row['import_file']);
                                echo '<td><a href="' . $file->getDownloadURL() . '"><i class="fa fa-download"></i> </a> </td>';
                            }
                            else
                            {
                                echo '<td></td>';
                            }
                            echo $_SESSION['user']->username == 'admin' ? '<td><span class="btn btn-xs btn-danger" onclick="removeEntry(\''.$row['import_id'].'\');"><i class="fa fa-remove"></i></span></td>' : '';
                            echo '</tr>';
                        }
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>


<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>

<script language="JavaScript">

    function save()
    {
        var myForm = document.forms["frmImportEmployer"];
        if(!validateForm(myForm))
        {
            return;
        }

        if($('input:file').val() == '')
        {
            alert('Please select your file.');
            return;
        }

        var ext = $('input:file').val().split('.').pop().toLowerCase();
        if ($.inArray(ext, ['csv']) == -1){
            alert('Only .csv files are allowed.');
            return false;
        }

        $('#btnSubmit').attr('disabled', true);
        $('#_spinner').show();

        myForm.submit();
    }

    $(function(){


        $('input:file').change(
            function(e) {
                var files = e.originalEvent.target.files;
                for (var i=0, len=files.length; i<len; i++){
                    var n = files[i].name,
                        s = files[i].size,
                        t = files[i].type;

                    var ext = n.split('.').pop().toLowerCase();
                    if ($.inArray(ext, ['csv']) == -1){
                        alert('Only .csv files are allowed.');
                        return false;
                    }

                    if (s > 5000000) {
                        alert('Please deselect this file: "' + n + '," it\'s larger than the maximum filesize allowed. Sorry!');
                        return false;
                    }
                }
            });

    });

    <?php if($_SESSION['user']->username == 'admin') {?>
    function removeEntry(import_id)
    {
        var req = ajaxRequest('do.php?_action=import_employers&subaction=removeEntry&import_id='+encodeURIComponent(import_id));
        if(req)
        {
            window.location.reload();
        }
    }
    <?php } ?>
</script>

</body>
</html>