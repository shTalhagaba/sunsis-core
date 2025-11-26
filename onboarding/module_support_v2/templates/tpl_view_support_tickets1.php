<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Support Tickets</title>
    <link rel="stylesheet" href="/css/common.css" type="text/css" />
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
        <div class="col-sm-12">
            <div class="banner">
                <div class="Title" style="margin-left: 6px;">Support Tickets</div>
                <div class="ButtonBar">
                    <button class="btn btn-default btn-xs" onclick="window.location.href='do.php?_action=create_support_ticket';"><i class="fa fa-plus"></i> Raise Support Ticket</button>
                </div>
                <div class="ActionIconBar">
                    <span class="btn btn-sm btn-info fa fa-print" onclick="window.print();" title="Print-friendly view"></span>
                    <!-- <span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="exportToExcel('view_ViewContracts');" title="Export to .CSV file"></span> -->
                    <span class="btn btn-sm btn-info fa fa-refresh" onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?php $_SESSION['bc']->render($link); ?>
        </div>
    </div>

    <div id="divFilters">
        <form method="get" action="do.php" name="frmFilters" id="frmFilters">
            <input type="hidden" name="product_id" value="5" />
            
            <div id="filterBox" class="clearfix small">
                <fieldset>
                    <div class="field float">
                        <label>Number:</label>
                        <input type="number" name="ticket_number">
                    </div>
                    <div class="field float">
                        <label>Status:</label>
                        <?php echo HTML::select('ticket_status', $statusList, null, true); ?>
                    </div>
                    <div class="field float">
                        <label>Type:</label>
                        <?php echo HTML::select('type', $typesList, null, true); ?>
                    </div>
                    <div class="field float">
                        <label>Priority:</label>
                        <?php echo HTML::select('customer_priority', $prioritiesList, null, true); ?>
                    </div>
                    <div class="field float">
                        <label>Subject:</label>
                        <input type="text" name="subject">
                    </div>
                    <div class="field float">
                        <label>Resolved:</label>
                        <?php echo HTML::select('resolved', [[1, 'Yes'], [0, 'No']], null, true); ?>
                    </div>
                    <div class="field float">
                        <label>Exclude Closed Tickets:</label>
                        <?php echo HTML::select('exclude_closed_tickets', [[1, 'Yes'], [0, 'No']], null, false); ?>
                    </div>
                    <div class="field float">
                        <label>Raised By:</label>
                        <?php 
                        if($_SESSION['user']->isAdmin())
                        {
                            $raisedBy = DAO::getResultset($link, "SELECT support_contact_id, CONCAT(firstnames, ' ', surname), null FROM users WHERE users.support_contact_id IS NOT NULL ORDER BY firstnames;");
                            echo HTML::select('account_contact_id', $raisedBy, null, true); ; 
                        }
                        else
                        {
                            $raisedBy = [['', '']];
                            if(! is_null($_SESSION['user']->support_contact_id) )
                            {
                                $raisedBy = [[$_SESSION['user']->support_contact_id, $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname]];
                            }
			    else
                            {
                                $raisedBy = [[$_SESSION['user']->id, $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname]];
                            }
                            echo HTML::select('account_contact_id', $raisedBy, null, false); ; 
                        }
                        ?>
                    </div>
                </fieldset>
                <fieldset>
                    <button type="submit">Apply</button>
                    <button type="reset">Reset</button>
                </fieldset>
            </div>
        </form>
    </div>

    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <p class="text-center">
                    <img src="images/progress-animations/loading51.gif" alt="Loading" id="loading-container" style="display: none;" />
                </p>

                <div class="row">
                    <div class="col-sm-12">
                        <div align="center" class="viewNavigator">
                            <table width="450" id="tblPaginator">
                                <tbody>
                                    <tr>
                                        <td width="20%" align="right" id="leftTd">
                                            <button type="button" class="btn btn-sm btn-default" id="firstPage">
                                                <i class="fa fa-step-backward"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-default" id="prevPage">
                                                <i class="fa fa-caret-left"></i>
                                            </button>
                                        </td>
                                        <td align="center" width="60%" valign="middle">
                                            page 
                                            <div id="divPageSelector" style="display: inline;"><select id="pageSelector"></select></div>
                                            of <span id="lastPageNumber"></span> (<span id="totalRecords"></span> records)
                                        </td>
                                        <td width="20%" align="left" id="rightTd">
                                            <button type="button" class="btn btn-sm btn-default" id="nextPage">
                                                <i class="fa fa-caret-right"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-default" id="lastPage">
                                                <i class="fa fa-step-forward"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="ticket-table">
                        <thead>
                            <tr>
                                <th>Number</th>
                                <th>Subject</th>
                                <th>Raised By</th>
                                <th>Details</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Resolved</th>
                                <th>Due Date</th>
                                <th>Logged At</th>
                                <th>Recent Updated At</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/js/common.js" type="text/javascript"></script>
    <script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script language="JavaScript">
        const TokenID = '<?php echo $supportHelper->getXTokenId(); ?>';
        var requestFilters = '<?php echo json_encode($filters); ?>';
    </script>
    <script src="/module_support_v2/js/view_support_tickets.js?n=<?php echo time(); ?>"></script>   

</body>

</html>