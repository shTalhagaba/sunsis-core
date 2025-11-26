<?php /* @var $vo User */ ?>

<!DOCTYPE html>

<head xmlns="http://www.w3.org/1999/html">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View System User</title>

    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css" />
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
	input[type=radio] {
			transform: scale(1.8);
		}
    </style>
</head>

<body>
    <div class="row">
        <div class="col-lg-12">
            <div class="banner">
                <div class="Title" style="margin-left: 6px;">View System User</div>
                <div class="ButtonBar">
                    <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                    <?php if (
                        ($_SESSION['user']->isAdmin() ||
                        in_array($_SESSION['user']->username,["atodd123","hgibson1","jrearsv","marbrown","rachaelgreen","ecann123","mijones12"])) ||
                        !in_array($_SESSION['user']->type, [User::TYPE_TUTOR, User::TYPE_ASSESSOR, User::TYPE_ORGANISATION_VIEWER, User::TYPE_SCHOOL_VIEWER, User::TYPE_SYSTEM_VIEWER, User::TYPE_BRAND_MANAGER, User::TYPE_APPRENTICE_COORDINATOR, User::TYPE_LEARNER])
                    ) { ?>
                        <span class="btn btn-sm btn-default" onclick="window.location.replace('do.php?_action=edit_user&username=<?php echo $vo->username; ?>&organisations_id=<?php echo $vo->employer_id; ?>');"><i class="fa fa-edit"></i> Edit</span>
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
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="<?php echo $photopath; ?>" alt="User profile picture">
                        <span class="profile-username"><?php echo htmlspecialchars((string)$vo->firstnames) . ' ' . htmlspecialchars(strtoupper($vo->surname ?: '')); ?></span>
                        <p class="text-muted"><?php echo htmlspecialchars((string)$vo->job_role); ?></p>
                        <div class="col-sm-12 invoice-col">
                            <a class="text-bold" href="do.php?_action=read_employer_v3&id=<?php echo isset($vo->org->id) ? $vo->org->id : ''; ?>">
                                <?php echo isset($vo->org->legal_name) ? $vo->org->legal_name : ''; ?>
                            </a>
                            <br>
                            <?php echo $vo->loc->address_line_3 != '' ? $vo->loc->address_line_3 . '<br>' : ''; ?>
                            <?php echo $vo->loc->address_line_4 != '' ? $vo->loc->address_line_4 . '<br>' : ''; ?>
                        </div>
                    </div>
                </div>
                <div class="box  box-info box-solid">
                    <div class="box-header with-border"><span class="box-title">Contact Information</span></div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <strong><i class="fa fa-map-marker margin-r-5"></i> Work Contact Details</strong>
                        <address>
                            <?php
                            echo trim((string)$work_address->address_line_1) != '' ? htmlspecialchars((string)$work_address->address_line_1) . '<br>' : '';
                            echo trim((string)$work_address->address_line_2) != '' ? htmlspecialchars((string)$work_address->address_line_2) . '<br>' : '';
                            echo trim((string)$work_address->address_line_3) != '' ? htmlspecialchars((string)$work_address->address_line_3) . '<br>' : '';
                            echo trim((string)$work_address->address_line_4) != '' ? htmlspecialchars((string)$work_address->address_line_4) . '<br>' : '';
                            echo trim((string)$work_address->postcode) != '' ? htmlspecialchars((string)$work_address->postcode) . '<br>' : '';
                            echo trim((string)$vo->work_telephone) != '' ? '<span class="fa fa-phone"></span> ' . htmlspecialchars((string)$vo->work_telephone) . '<br>' : '';
                            echo trim((string)$vo->work_mobile) != '' ? '<span class="fa fa-mobile-phone"></span> ' . htmlspecialchars((string)$vo->work_mobile) . '<br>' : '';
                            echo trim((string)$vo->work_email) != '' ? '<span class="fa  fa-envelope"></span> <a href="mailto:' . $vo->work_email . '">' . htmlspecialchars((string)$vo->work_email) . '</a>' : '';
                            ?>
                        </address>

                        <hr>

                        <strong><i class="fa fa-map-marker margin-r-5"></i> Home Contact Details</strong>

                        <address>
                            <?php
                            echo trim((string)$home_address->address_line_1) != '' ? htmlspecialchars((string)$home_address->address_line_1) . '<br>' : '';
                            echo trim((string)$home_address->address_line_2) != '' ? htmlspecialchars((string)$home_address->address_line_2) . '<br>' : '';
                            echo trim((string)$home_address->address_line_3) != '' ? htmlspecialchars((string)$home_address->address_line_3) . '<br>' : '';
                            echo trim((string)$home_address->address_line_4) != '' ? htmlspecialchars((string)$home_address->address_line_4) . '<br>' : '';
                            if (trim((string)$home_address->postcode) != '') {
                                echo (isset($valid_postcode) && $valid_postcode != '') ?
                                    '<span class="text-success">' . $home_address->postcode . '</span><br>' :
                                    '<span class="text-danger">' . $home_address->postcode . '</span><br>';
                            } else {
                                echo '<br>';
                            }
                            echo trim((string)$vo->home_telephone) != '' ? '<span class="fa fa-phone"></span> ' . htmlspecialchars((string)$vo->home_telephone) . '<br>' : '';
                            echo trim((string)$vo->home_mobile) != '' ? '<span class="fa fa-mobile-phone"></span> ' . htmlspecialchars((string)$vo->home_mobile) . '<br>' : '';
                            echo trim((string)$vo->home_email) != '' ? '<span class="fa  fa-envelope"></span> <a href="mailto:' . $vo->home_email . '">' . htmlspecialchars((string)$vo->home_email) . '</a>' : '';

                            ?>
                        </address>

                        <hr>

                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="nav-tabs-custom bg-gray-light">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_details" data-toggle="tab">Details</a></li>
                        <?php if (SOURCE_LOCAL || in_array(DB_NAME, ["am_demo"])) { ?>
                            <li><a href="#tab_job_role" data-toggle="tab">Job Role</a></li>
                            <li><a href="#tab_holidays" data-toggle="tab">Holidays</a></li>
                            <li><a href="#tab_absences" data-toggle="tab">Absences</a></li>
                            <li><a href="#tab_cpd" data-toggle="tab">CPD / Training Needs</a></li>
                        <?php } ?>
			<?php if( (DB_NAME == "am_presentation" || SOURCE_LOCAL) && (in_array($vo->type, [User::TYPE_ASSESSOR, User::TYPE_TUTOR])) ){ ?>
                            <li><a href="#tab_rag" data-toggle="tab">RAG Rating</a></li>
                        <?php } ?>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="tab_details">

                            <div class="row">

                                <div class="col-sm-6">
                                    <div class="box box-info box-solid">
                                        <div class="box-body no-padding">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <tr>
                                                        <th style="width:40%">System User Type:</th>
                                                        <td><?php echo DAO::getSingleValue($link, "SELECT description FROM lookup_user_types WHERE id = '{$vo->type}'"); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Gender:</th>
                                                        <td><?php echo DAO::getSingleValue($link, "SELECT description FROM lookup_gender WHERE id = '{$vo->gender}'"); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Record Status:</th>
                                                        <td><?php echo $vo->active == 1 ? '<span class="text-green">Active</span>' : '<span class="text-red">Not Active</span>'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>System Access:</th>
                                                        <td><?php echo $vo->web_access == 1 ? '<span class="text-green">Enabled</span>' : '<span class="text-red">Disabled</span>'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>System Username:</th>
                                                        <td><?php echo '<code>' . $vo->username . '</code>'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Active:</th>
                                                        <td><?php echo $vo->active == 1 ? '<span class="text-green">Yes</span>' : '<span class="text-red">No</span>'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Logins:</th>
                                                        <td><?php echo DAO::getSingleValue($link, "SELECT COUNT(*) FROM logins WHERE logins.username = '{$vo->username}'"); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Last Login DateTime:</th>
                                                        <td><?php echo isset($last_login->date) ? Date::to($last_login->date, 'l jS F Y H:i:s') : ''; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Last Login User Agent:</th>
                                                        <td><?php echo isset($last_login->user_agent) ? $last_login->user_agent : ''; ?></td>
                                                    </tr>
                                                    <?php if ( SystemConfig::getEntityValue($link, 'onefile.integration') && in_array($vo->type, [User::TYPE_ASSESSOR, USer::TYPE_TUTOR]) ) { ?>
                                                        <tr>
                                                            <th>OneFile Status:</th>
                                                            <td>
                                                                <?php echo $vo->onefile_user_id == '' ? '<span class="label label-danger">Not Linked</span>' : '<span class="label label-success">Linked (OneFile User ID: ' . $vo->onefile_user_id . ')</span>'; ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    <?php if (in_array(DB_NAME, ["am_baltic", "am_baltic_demo"])) { ?>
                                                        <tr>
                                                            <th>Induction Access:</th>
                                                            <td>
                                                                <?php echo $vo->induction_access == 'R' ? 'Read' : ($vo->induction_access == 'W' ? 'Write' : ($vo->induction_access == 'D' ? 'Disable' : '')); ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Induction Menus:</th>
                                                            <td><?php echo $vo->induction_menus; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Operations Access:</th>
                                                            <td>
                                                                <?php echo $vo->op_access == 'R' ? 'Read' : ($vo->op_access == 'W' ? 'Write' : ($vo->op_access == 'D' ? 'Disable' : '')); ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Operations Menus:</th>
                                                            <td><?php echo $vo->op_menus; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">

                                </div>

                            </div>

                        </div>
                        <?php if (SOURCE_LOCAL || in_array(DB_NAME, ["am_demo"])) { ?>
                            <div class="tab-pane" id="tab_job_role">
                                <h5 class="lead text-bold">Job Role Details</h5>
                                <p><span class="btn btn-primary btn-xs" onclick="$('#jobRoleModal').modal('show');"><i class="fa fa-edit"></i> Edit Job Role Details</span><br></p>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>Job Role:</th>
                                            <td><?php echo htmlspecialchars((string)$vo->job_role); ?></td>
                                        </tr>
                                        <tr>
                                            <th style="width:40%">Start Date:</th>
                                            <td>01/01/2015<br><label class="label label-info"><?php echo Date::dateDiff(date('y-m-d'), '2015-01-01'); ?></label></td>
                                        </tr>
                                        <tr>
                                            <th>Type:</th>
                                            <td>Permanent</td>
                                        </tr>
                                        <tr>
                                            <th>Salary:</th>
                                            <td>&pound;37,000.00 per annum</td>
                                        </tr>
                                        <tr>
                                            <th>Holiday Entitlement:</th>
                                            <td>35 days</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_holidays">
                                <h5 class="lead text-bold">Holidays Details</h5>
                                <p><span class="btn btn-primary btn-xs" onclick="$('#holidaysModal').modal('show');"><i class="fa fa-plus"></i> Add Holidays</span><br></p>
                                <div class="table-responsive callout callout-default">
                                    <table class="table">
                                        <tr>
                                            <th>Annual Holidays Entitlement: <h5 class="text-info lead text-bold">35 days</h5>
                                            </th>
                                        </tr>
                                    </table>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Dates</th>
                                            <th>Number of Days</th>
                                            <th>Remaining</th>
                                        </tr>
                                        <tr>
                                            <td>01/03/2022 - 10/03/2022</td>
                                            <td>7</td>
                                            <td>28</td>
                                            <th><span class="btn btn-info btn-xs"><i class="fa fa-edit"></i> Edit</span></th>
                                        </tr>
                                        <tr>
                                            <td>28/03/2022 - 29/03/2022</td>
                                            <td>2</td>
                                            <td>26</td>
                                            <th><span class="btn btn-info btn-xs"><i class="fa fa-edit"></i> Edit</span></th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_absences">
                                <h5 class="lead text-bold">Absences Details</h5>
                                <p><span class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Add Absences Details</span><br></p>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Date From</th>
                                            <th>Date To</th>
                                            <th>Reason Code</th>
                                            <th>Number of Days</th>
                                            <th>Total</th>
                                        </tr>
					<tr>
                                            <td>17/01/2022</td>
                                            <td>20/01/2022</td>
                                            <td>A12</td>
                                            <td>4</td>
                                            <td>4</td>
                                            <th><span class="btn btn-info btn-xs"><i class="fa fa-edit"></i> Edit</span></th>
                                        </tr>
                                        <tr>
                                            <td>16/03/2022</td>
                                            <td>18/03/2023</td>
                                            <td>B1</td>
                                            <td>3</td>
                                            <td>7</td>
                                            <th><span class="btn btn-info btn-xs"><i class="fa fa-edit"></i> Edit</span></th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_cpd">
                                <h5 class="lead text-bold">CPD / Training Details</h5>
                                <p><span class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Add CPD/Training Details</span><br></p>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Date From</th>
                                            <th>Date To</th>
                                            <th>Course</th>
                                            <th>Number of Days</th>
                                            <th>TBR</th>
                                            <th>Date of Next Course</th>
                                        </tr>
					<tr>
                                            <td>06/12/2021</td>
                                            <td>07/12/2021</td>
                                            <td>Health and Safety</td>
                                            <td>2</td>
                                            <td>6 months</td>
                                            <td>06/06/2022</td>
                                            <th><span class="btn btn-info btn-xs"><i class="fa fa-edit"></i> Edit</span></th>
                                        </tr>
                                        <tr>
                                            <td>14/03/2022</td>
                                            <td>14/03/2022</td>
                                            <td>Company Policies</td>
                                            <td>1</td>
                                            <td>12 months</td>
                                            <td>13/03/2022</td>
                                            <th><span class="btn btn-info btn-xs"><i class="fa fa-edit"></i> Edit</span></th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>
			<?php if( (DB_NAME == "am_presentation" || SOURCE_LOCAL) && (in_array($vo->type, [User::TYPE_ASSESSOR, User::TYPE_TUTOR])) ) { ?>
                            <div class="tab-pane" id="tab_rag">
                                <h5 class="lead text-bold">
                                    RAG Rating for
                                    <?php echo DAO::getSingleValue($link, "SELECT description FROM lookup_user_types WHERE id = '{$vo->type}'"); ?>
                                </h5>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="panel-body fieldValue">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td class="bg-green text-center">
                                                        <input type="radio" name="user_rag" value="G">
                                                    </td>
                                                    <td class="bg-orange text-center">
                                                        <input type="radio" name="user_rag" value="A">
                                                    </td>
                                                    <td class="bg-red text-center">
                                                        <input type="radio" name="user_rag" value="R">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                        <textarea name="rag_comments" id="rag_comments" class="form-control" rows="10" placeholder="Enter your comments"></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                        <span id="btnSaveRag" class="btn btn-sm btn-primary pull-right"><i class="fa fa-save"></i> Save Rating</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="panel-body fieldValue">
                                            <table class="table table-bordered" id="tblRagEntries">
                                                <?php
                                                $savedRatingsResult = DAO::getResultset($link, "SELECT * FROM users_rag_ratings WHERE user_id = '{$vo->id}' ORDER BY id DESC", DAO::FETCH_ASSOC);
                                                if(count($savedRatingsResult) == 0)
                                                {
                                                    echo '<tr style="display: none;">';
                                                    echo '<td style="width: 5%;"><i class="fa fa-circle fa-3x text-success"></i></td>';
                                                    echo '<td>These are the comments</td>';
                                                    echo '</tr>';    
                                                }
                                                else
                                                {
                                                    foreach($savedRatingsResult AS $savedRating)
                                                    {
                                                        $ratingClass = $savedRating['rag_rating'] == 'G' ? 'success' : ( $savedRating['rag_rating'] == 'A' ? 'orange' : ($savedRating['rag_rating'] == 'R' ? 'red' : '') );
                                                        echo '<tr>';
                                                        echo '<td style="width: 5%;"><i class="fa fa-circle fa-3x text-' . $ratingClass . '"></i></td>';
                                                        echo '<td>' . $savedRating['rag_comments'] . '<br>';
                                                        echo '<i class="text-info">By: ' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$savedRating['created_by']}'") . '</i><br>';
                                                        echo '<i class="text-info">On: ' . Date::to($savedRating['created_at'], Date::DATETIME) . '</i>';
                                                        echo '</td>';
                                                        echo '</tr>';
                                                    }
                                                }
                                                ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>

        <div class="modal fade" id="jobRoleModal" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h5 class="modal-title text-bold">Job Role Details</h5>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" method="post" method="post" action="">
                            <div class="control-group">
                                <label class="control-label" for="task_date">Start Date:</label>
                                <input type="text" class="form-control compulsory required datepicker" id="task_date" name="task_date" value="01/01/2015" placeholder="dd/mm/yyyy">
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="task_type">Type:</label>
                                <?php echo HTML::selectChosen('task_type', [[1, 'Permanent'], [2, 'Agency'], [3, 'Part Time']]); ?>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="task_type">Salary:</label>
                                <input class="form-control" type="text" name="task_peed_cause" value="37000" />
                                <?php echo HTML::selectChosen('task_type', [[1, 'per annum'], [2, 'per month'], [3, 'per week']]); ?>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="task_type">Holiday Entitlement (Days):</label>
                                <input class="form-control" type="text" name="task_peed_cause" value="35" />
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left btn-sm" onclick="$('#jobRoleModal').modal('hide');">Cancel</button>
                        <button type="button" id="btnjobRoleModalSave" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="holidaysModal" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h5 class="modal-title text-bold">Holidays Details</h5>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" method="post" method="post" action="">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="control-group">
                                        <label class="control-label" for="task_date">Date From:</label>
                                        <input type="text" class="form-control compulsory required datepicker" id="task_date" name="task_date" value="01/01/2015" placeholder="dd/mm/yyyy">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="control-group">
                                        <label class="control-label" for="task_date">Date To:</label>
                                        <input type="text" class="form-control compulsory required datepicker" id="task_date" name="task_date" value="01/01/2015" placeholder="dd/mm/yyyy">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left btn-sm" onclick="$('#holidaysModal').modal('hide');">Cancel</button>
                        <button type="button" id="btnjobRoleModalSave" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
            </div>
        </div>


        <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
        <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
        <script src="/assets/adminlte/dist/js/app.min.js"></script>
        <script src="/common.js" type="text/javascript"></script>


        <script>
            $(function() {

            });

	    $("span#btnSaveRag").on('click', function(){
                var rag = $("input[type=radio][name=user_rag]:checked").val();
                var comments = $("textarea[name=rag_comments]").val();

                console.log(rag);
                if(rag === undefined || comments == '')
                {
                    alert('Please select rating and enter comments.');
                    return false;
                }

                var row = '';
                if(rag == 'G')
                {
                    row += '<td style="width: 5%;"><i class="fa fa-circle fa-3x text-green"></i></td>';
                }
                else if(rag == 'A')
                {
                    row += '<td style="width: 5%;"><i class="fa fa-circle fa-3x text-orange"></i></td>';
                }
                else if(rag == 'R')
                {
                    row += '<td style="width: 5%;"><i class="fa fa-circle fa-3x text-red"></i></td>';
                }

                // $('#tblRagEntries tr:last').after('<tr>' + row + '<td>' + comments + '</td></tr>');
                
                
                var post = '&rag_rating='+encodeURIComponent(rag)+'&rag_comments='+encodeURIComponent(comments)+'&user_id=<?php echo $vo->id; ?>';
                var client = ajaxRequest('do.php?_action=ajax_actions&subaction=saveUserRagRating', post);
                if(client.responseText == 'ERROR')
                {
                    $('#tblRagEntries').prepend('<tr>' + row + '<td>' + comments + '</td></tr>');
                }
                else
                {
                    $('#tblRagEntries').prepend(client.responseText);
                }
                $("input[type=radio][name=user_rag]").prop('checked', false);
                $("textarea[name=rag_comments]").val('');
            });
        </script>
</body>

</html>