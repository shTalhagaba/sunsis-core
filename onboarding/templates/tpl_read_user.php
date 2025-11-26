<?php /* @var $vo User */ ?>
<?php /* @var $organisation Organisation */ ?>
<?php /* @var $location Location */ ?>

<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View User</title>

    <link rel="stylesheet" href="css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote-bs3.css">


    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style type="text/css">
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>

</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">View User
                [<?php echo $vo->firstnames . ' ' . $vo->surname; ?>]
            </div>
            <div class="ButtonBar">
				<span class="btn btn-sm btn-default"
                      onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i
                        class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <?php if($_SESSION['user']->isAdmin()){?>
                <span class="btn btn-sm btn-default" onclick="window.location.href='do.php?_action=edit_user&edit=1&id=<?php echo $vo->id; ?>';"><i class="fa fa-edit"></i> Edit</span>
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

<div class="content-wrapper">

    <div class="row">
        <div class="col-sm-4">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <span class="box-title with-header">
                                <span class="lead text-bold"><?php echo htmlspecialchars($vo->firstnames) . ' ' . htmlspecialchars(strtoupper($vo->surname)); ?>
                                </span>
                            </span>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <tr><th>Username:</th><td><code><?php echo $vo->username; ?></code></td></tr>
                                    <tr><th>System Access:</th><td><?php echo $vo->web_access == 1 ? '<label class="label-success">Enabled</label>' : '<label class="label-danger">Disabled</label>'; ?></td></tr>
                                    <tr><th>User Type:</th><td><?php echo DAO::getSingleValue($link, "SELECT description FROM lookup_user_types WHERE id = '{$vo->type}'"); ?></td></tr>
                                    <tr><th>Email:</th><td><?php echo $vo->work_email; ?></td></tr>
                                    <tr><th>Telephone:</th><td><?php echo $vo->work_telephone; ?></td></tr>
                                    <tr><th>Mobile:</th><td><?php echo $vo->work_mobile; ?></td></tr>
                                    <tr><th>Work Address:</th>
                                        <td>
                                            <?php echo $vo->work_address_line_1 != '' ? $vo->work_address_line_1 . '<br>' : ''; ?>
                                            <?php echo $vo->work_address_line_2 != '' ? $vo->work_address_line_2 . '<br>' : ''; ?>
                                            <?php echo $vo->work_address_line_3 != '' ? $vo->work_address_line_3 . '<br>' : ''; ?>
                                            <?php echo $vo->work_address_line_4 != '' ? $vo->work_address_line_4 . '<br>' : ''; ?>
                                            <?php echo $vo->work_postcode != '' ? $vo->work_postcode . '<br>' : ''; ?>
                                        </td>
                                    </tr>
                                    <tr><th>Employer</th><td><?php echo $organisation->legal_name; ?></td></tr>
                                    <tr>
                                        <th>Employer Address</th>
                                        <td class="small">
                                            <?php echo $location->address_line_1 != '' ? $location->address_line_1 . '<br>' : ''; ?>
                                            <?php echo $location->address_line_2 != '' ? $location->address_line_2 . '<br>' : ''; ?>
                                            <?php echo $location->address_line_3 != '' ? $location->address_line_3 . '<br>' : ''; ?>
                                            <?php echo $location->address_line_4 != '' ? $location->address_line_4 . '<br>' : ''; ?>
                                            <?php echo $location->postcode != '' ? $location->postcode . '<br>' : ''; ?>
                                        </td>
                                    </tr>
				    <?php if($_SESSION['user']->isAdmin() && !is_null($vo->signature) ){ ?>
                                    <tr>
                                        <th>User Signature</th>
                                        <td>
                                            <img id="img_user_signature" src="do.php?_action=generate_image&<?php echo $vo->signature; ?>" style="border: 1px solid;border-radius: 15px;" />
                                        </td>
                                    </tr>
                                    <?php } ?>	
                                </table>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr><th>Total Logins:</th><td><code><?php echo DAO::getSingleValue($link, "SELECT COUNT(*) FROM logins WHERE username = '{$vo->username}';"); ?></code></td></tr>
                                    <tr><th>Last Login:</th><td><code><?php echo DAO::getSingleValue($link, "SELECT DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') FROM logins WHERE username = '{$vo->username}' ORDER BY date DESC LIMIT 1;"); ?></code></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">

                </div>
            </div>
        </div>
    </div>

    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/adminlte/dist/js/app.min.js"></script>
    <script src="js/common.js" type="text/javascript"></script>
    <script src="/assets/adminlte/plugins/summernote/summernote.min.js"></script>

    <script>

        $(function () {

        });


    </script>
</body>
</html>
