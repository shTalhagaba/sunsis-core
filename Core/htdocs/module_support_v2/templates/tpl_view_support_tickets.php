<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Support Tickets</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css" />
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
                    <button class="btn btn-default btn-sm" onclick="window.location.href='do.php?_action=edit_contract';"><i class="fa fa-plus"></i> Create New Contract</button>
                </div>
                <div class="ActionIconBar">
                    <span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('divFilters');showHideBlock('div_filter_crumbs');showHideBlock('applySavedFilter');" title="Show/hide filters"></span>
                    <span class="btn btn-sm btn-info fa fa-print" onclick="window.print();" title="Print-friendly view"></span>
                    <span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="exportToExcel('view_ViewContracts');" title="Export to .CSV file"></span>
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

    <div id="divFilters" style="display: none;">
        <form method="get" action="/do.php" name="frmFilters" id="frmFilters">
            <input type="hidden" name="_action" value="view_support_tickets">
            <input type="hidden" name="filter_page" value="">

            <div id="filterBox" class="clearfix small">
                <fieldset>
                    <legend>Ticket</legend>
                    <div class="field float">
                        <label>Number:</label>
                        <input type="text" name="filter_ticket_number" value="<?php echo isset($_REQUEST['filter_ticket_number']) ? $_REQUEST['filter_ticket_number'] : ''; ?>">
                    </div>
                    <div class="field float">
                        <label>Status:</label>
                        <select name="filter_ticket_status">
                            <option value=""></option>
                            <option value="1">Assigned</option>
                            <option value="6">Closed</option>
                        </select>
                    </div>
                </fieldset>
                <fieldset>
                    <input type="submit" value="Apply">
                </fieldset>
            </div>
        </form>
    </div>

    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <p class="text-center">
                    <img src="images/progress-animations/loading51.gif" alt="Loading" class="loadingSpinner" style="display: none;" />
                </p>


                <div class="row">
                    <div class="col-sm-12">
                        <div align="center" class="viewNavigator">
                            <table width="450" id="tblPaginator">
                                <tbody>
                                    <tr>
                                        <td width="20%" align="right">
                                            <button 
                                                type="button" 
                                                class="btn btn-sm btn-default" 
                                                id="firstPage" 
                                                <?php echo $meta->current_page == 1 ? 'disabled="disabled"' : ''; ?>
                                            >
                                                <i class="fa fa-step-backward"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-default" id="prevPage" <?php echo $links->prev == '' ? 'disabled="disabled"' : ''; ?>>
                                                <i class="fa fa-caret-left"></i>
                                            </button>
                                        </td>
                                        <td align="center" width="60%" valign="middle">
                                            page 
                                            <select id="pagesList">
                                                <?php 
                                                for($i = 1; $i <= $meta->last_page; $i++)
                                                {
                                                    $selectedPage = $i == $meta->current_page ? 'selected="selected"' : '';
                                                    echo '<option value="' . $i . '"' . $selectedPage . '>' . $i . '</option>';
                                                }
                                                ?>
                                            </select>
                                            of <?php echo $meta->last_page; ?> (<span id="totalRecords"><?php echo $meta->total; ?></span> records)
                                        </td>
                                        <td width="20%" align="left">
                                            <button type="button" class="btn btn-sm btn-default" id="nextPage" <?php echo $links->next == '' ? 'disabled="disabled"' : ''; ?>>
                                                <i class="fa fa-caret-right"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-default" id="lastPage" <?php echo $meta->current_page == $meta->last_page ? 'disabled="disabled"' : ''; ?>>
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
                    <table class="table table-bordered" id="tblTickets">
                        <thead>
                            <tr>
                                <th>Number</th>
                                <th>Subject</th>
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
    <script src="/common.js" type="text/javascript"></script>
    <script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

    <script language="JavaScript">
        
        $("button#firstPage").on('click', function(){
            $("#frmFilters input[name=filter_page]").val(1);
            $("#frmFilters").submit();
        });
        $("button#lastPage").on('click', function(){
            $("#frmFilters input[name=filter_page]").val(<?php echo $meta->last_page; ?>);
            $("#frmFilters").submit();
        });
        $("button#nextPage").on('click', function(){
            $("#frmFilters input[name=filter_page]").val(<?php echo $meta->current_page + 1; ?>);
            $("#frmFilters").submit();
        });
        $("button#prevPage").on('click', function(){
            $("#frmFilters input[name=filter_page]").val(<?php echo $meta->current_page - 1; ?>);
            $("#frmFilters").submit();
        });
        $("select#pagesList").on('change', function(){
            $("#frmFilters input[name=filter_page]").val(this.value);
            $("#frmFilters").submit();
        });

        function refreshTickets() {
            const tableBody = document.querySelector('#tblTickets tbody');

            tableBody.innerHTML = '';

            response = $.parseJSON('<?php echo $response; ?>');

            console.log(response.data);

            response.data.forEach((ticket) => {
                const row = document.createElement('tr');
                const tn = document.createElement('td');
                const tsb = document.createElement('td');
                const td = document.createElement('td');
                const tst = document.createElement('td');
                const tp = document.createElement('td');
                const tr = document.createElement('td');
                const tdd = document.createElement('td');
                const tla = document.createElement('td');
                const tru = document.createElement('td');

                tn.innerHTML = '<a href="#">' + ticket.ticket_number + '</a>';
                tsb.innerHTML = ticket.subject;
                td.innerHTML = ticket.description.length > 300 ? ticket.description.substring(1, 300) + '...' : ticket.description;
                tst.innerHTML = ticket.status.description;
                tp.innerHTML = ticket.priority.description;
                tr.innerHTML = ticket.resolved ? '<i class="fa fa-check text-green"></i>' : '';
                tdd.innerHTML = formatDate(ticket.due_date);
                tla.innerHTML = formatDate(ticket.created_at);
                tru.innerHTML = formatDate(ticket.updated_at);

                row.appendChild(tn);
                row.appendChild(tsb);
                row.appendChild(td);
                row.appendChild(tst);
                row.appendChild(tp);
                row.appendChild(tr);
                row.appendChild(tdd);
                row.appendChild(tla);
                row.appendChild(tru);

                tableBody.appendChild(row);
            });            
        }

        function setFirstPage() {
            var url = window.location.href;
            url = URLToArray(url);
            if (url.page === undefined) {

            }
        }

        $(document).ready(function() {


            refreshTickets();

            console.log(window.location.href);
            console.log(URLToArray(window.location.href));

            // $("table#tblPaginator #nextPage").on('click', function(){
            //     console.log('asd');
            // });


        });

        function URLToArray(url) {
            var request = {};
            var pairs = url.substring(url.indexOf('?') + 1).split('&');
            for (var i = 0; i < pairs.length; i++) {
                if (!pairs[i])
                    continue;
                var pair = pairs[i].split('=');
                request[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
            }
            return request;
        }
    </script>

</body>

</html>