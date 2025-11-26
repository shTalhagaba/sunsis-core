<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Support Ticket Form</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css" />
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

    <!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->


</head>

<body>

    <div class="row">
        <div class="col-lg-12">
            <div class="banner">
                <div class="Title" style="margin-left: 6px;">Support Ticket Form</div>
                <div class="ButtonBar">
                    <span class="btn btn-xs btn-default" onclick="window.location.href='do.php?_action=view_support_tickets';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
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
        <div class="col-sm-8 col-sm-offset-2">
            <p class="text-center" id="loading" style="display: none;">
                <img src="images/progress-animations/loading51.gif" alt="Loading" />
            </p>

            <div class="alert alert-danger alert-dismissible" id="errorPanel" style="display: none;">
                <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-close"></i></button> -->
                <h4><i class="icon fa fa-exclamation-triangle"></i> Alert!</h4>
                <ul id="errorList"></ul>
            </div>

            <div class="alert alert-success alert-dismissible" id="successPanel" style="display: none;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-close"></i></button>
                <h4>Thank you for your query</h4>
                <p>Your support ticket has been sent to Perspective Support.</p>
                <p>Your case number is: <strong style="font-size: x-large;"><span class="text-bold" id="caseNumber">12121</span></strong><br />
                (This number should be used in all future communications regarding this query)</p> <br/>
                <p>You have been sent a copy of the Support Request details to the email address you provided.</p>
                <p>A member of the Support Team will contact you at the earliest opportunity</p>
                <p>Support Hours are 9am until 5pm Monday to Friday</p>
                <br/>
                <p>You can check on your support requests by visting the <a href="do.php?_action=view_support_tickets&amp;header=1">Your Support Requests</a> section on Sunesis.</p>
            </div>
        </div>
    </div>

    <div class="row">
        <form role="form" id="frmTicket" method="post" enctype="multipart/form-data">
            <input type="hidden" name="firstname" value="<?php echo $_SESSION['user']->firstnames; ?>">
            <input type="hidden" name="lastname" value="<?php echo $_SESSION['user']->surname; ?>">
            <input type="hidden" name="product_id" value="1">

            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h5 class="box-title">Your Details: </h5>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <h5 class="text-bold text-info"><?php echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?></h5>
                            <h5 class="text-bold text-info"><?php echo $_SESSION['user']->org->legal_name; ?></h5>
                        </div>
                        <div class="form-group">
                            <label for="email">Email address: *</label>
                            <input type="text" 
                                style="background-color: #f3fae5 !important;border-width: 1px;border-color: #648827;background-color: #f3fae5 !important;border-style: solid;padding: 2px;" 
                                class="form-control compulsory" id="email" name="email" placeholder="Enter your email" 
                                value="<?php echo $_SESSION['user']->work_email; ?>" required maxlength="255">
                        </div>
                        <div class="form-group">
                            <label for="phone">Telephone: </label>
                            <input type="text" class="form-control" id="phone" placeholder="Enter your telephone" value="<?php echo $_SESSION['user']->work_telephone; ?>" maxlength="25">
                        </div>
                        <div class="form-group">
                            <label for="job_role">Job Role: </label>
                            <input type="text" class="form-control" id="job_role" value="<?php echo $_SESSION['user']->job_role; ?>" maxlength="70">
                        </div>
                        <div class="form-group">
                            <label for="department">Department: </label>
                            <input type="text" class="form-control" id="department" maxlength="70">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h5 class="box-title">Ticket Details</h5>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="type" class="control-label fieldLabel_compulsory">Type: *</label>
                            <?php echo HTML::selectChosen('type', $typesList, null, false, true); ?>
                        </div>
                        <div class="form-group">
                            <label for="customer_priority" class="control-label fieldLabel_compulsory">Priority: *</label>
                            <?php echo HTML::selectChosen('customer_priority', $prioritiesList, null, false, true); ?>
                        </div>
                        <div class="form-group">
                            <label for="subject" class="control-label fieldLabel_compulsory">Subject: *</label>
                            <input type="text" class="form-control compulsory" name="subject" id="subject" maxlength="255">
                        </div>
                        <div class="form-group">
                            <label for="description" class="control-label fieldLabel_compulsory">Description/Details: *</label>
                            <textarea name="description" id="description" class="form-control compulsory" rows="10"></textarea>
                            <span class="text-info small">
                                <p>
                                    Please include details of the action you were attempting to complete.<br>
                                    e.g. the name of the learner, course or qualification, or the name of the report or export.
                                </p>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="evidence_file" class="control-label">File Attachment</label>
                            <input type="file" name="evidence_file" class="form-control">
                            <span class="text-info small">
                                <p>
                                    If there is a screenshot or document you can send us to help us understand your query, please attach it here.<br>
                                    If you want to upload multiple files, zip them to make single zip file.
                                </p>
                            </span>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="button" id="btnSubmit" onclick="submitForm()" class="btn btn-block btn-success btn-sm">Send your query <i class="fa fa-send"></i></button>
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
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script language="JavaScript">
        const TokenID = '<?php echo $supportHelper->getXTokenId(); ?>';
    </script>

    <script language="JavaScript">
        function submitForm() {
            const form = document.getElementById('frmTicket');
            if(! validateForm(form) )
            {
                return;
            }
            if(! validateEmail(document.getElementById('email').value) )
            {
                return alert('Please enter a valid email address');
            }
            const loadingMessage = document.getElementById('loading');
            const successPanel = document.getElementById('successPanel');
            const errorPanel = document.getElementById('errorPanel');
            const errorList = document.getElementById('errorList');
            const caseNumber = document.getElementById('caseNumber');

            errorList.innerHTML = '';
            loadingMessage.style.display = 'block';
            const formData = new FormData(form);
            const apiUrl = 'https://tickets.sunesis.uk.net/api/v1/tickets';

            axios.post(apiUrl, formData, {
                    headers: {
                        'X-TokenID': TokenID,
                        'Content-Type': 'multipart/form-data',
                    },
                })
                .then(function(response) {

                    caseNumber.innerText = response.data.data.ticket_number;
                    
                    loadingMessage.style.display = 'none';

                    successPanel.style.display = 'block';

                    form.reset();

                    window.scrollTo(0, 0);

                    return axios.post('do.php?_action=ajax_support_tickets&subaction=saveAccountContactId&account_contact_id=' + response.data.data.account_contact.id);
                })
                .then(function(response) {
                    console.log(response.data);
                })
                .catch(function(error) {
                    // Handle validation errors
                    if (error.response && error.response.status === 422) {
                        const errors = error.response.data.errors;

                        // Display validation errors in the modal
                        for (const field in errors) {
                            const errorMessage = errors[field][0];
                            const errorItem = document.createElement('li');
                            errorItem.innerText = errorMessage;
                            errorList.appendChild(errorItem);
                        }

                        // Show the error modal
                        errorPanel.style.display = 'block';
                        window.scrollTo(0, 0);
                    } else {
                        // Handle other errors (e.g., display a general error message)
                        console.error(error);
                        var message = error.message + '<br>';
                        if(error.response.data.message)
                        {
                            message += error.response.data.message + '<br>';
                        }
                        if(error.response.status && error.response.statusText)
                        {
                            message += error.response.status + ': ' + error.response.statusText + '<br>';
                        }
                        showDialog(error.code, message);
                    }

                    // Hide loading message
                    loadingMessage.style.display = 'none';
                });
        }

        function showDialog(title, message) {
            $("<div></div>").html(message).dialog({
                id: "dlgMessage",
                title: title == '' ? 'Alert' : title,
                resizable: false,
                modal: true,
                width: 400,
                height: 350,
                buttons: {
                    'Close': function() {
                        $(this).dialog('close');
                    }
                }
            });
        }

        
    </script>

</body>

</html>