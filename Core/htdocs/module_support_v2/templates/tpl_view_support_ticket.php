<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Support Ticket</title>
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
                <div class="Title" style="margin-left: 6px;">Support Ticket</div>
                <div class="ButtonBar">
                    <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
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
        <div class="col-sm-5">
            <div class="callout callout-default">
                <dl class="row">
                    <dt class="col-sm-6">Ticket Number: </dt>
                    <dd class="col-sm-6"><?php echo $ticket->ticket_number; ?></dd>
                </dl>
                <dl class="row">
                    <dt class="col-sm-6">Type: </dt>
                    <dd class="col-sm-6"><?php echo $ticket->type->description; ?></dd>
                </dl>
                <dl class="row">
                    <dt class="col-sm-6">Status: </dt>
                    <dd class="col-sm-6"><?php echo $ticket->status->description; ?></dd>
                </dl>
                <dl class="row">
                    <dt class="col-sm-6">Priority: </dt>
                    <dd class="col-sm-6"><?php echo $ticket->priority->description; ?></dd>
                </dl>
            </div>
            
            <div class="callout callout-default">
                <dl class="row">
                    <dt class="col-sm-6">Raised By: </dt>
                    <dd class="col-sm-6">
                        <?php echo $ticket->account_contact->firstname . ' ' . $ticket->account_contact->lastname; ?> of
                        <?php echo $ticket->account->name; ?>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="col-sm-6">Logged At: </dt>
                    <dd class="col-sm-6"><?php echo Date::to($ticket->created_at, Date::DATETIME); ?></dd>
                </dl>
                <dl class="row">
                    <dt class="col-sm-6">Recently Modified At: </dt>
                    <dd class="col-sm-6"><?php echo Date::to($ticket->updated_at, Date::DATETIME); ?></dd>
                </dl>
            </div>

        </div>
        <div class="col-sm-7">
            <ul class="timeline">

                <li class="time-label">
                    <span class="bg-blue">
                        <?php echo Date::toMedium($ticket->created_at); ?>
                    </span>
                </li>

                <li>
                    <i class="fa fa-user bg-blue"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> <?php echo Date::to($ticket->created_at, 'H:i'); ?></span>
                        <h3 class="timeline-header">By <span class="text-info"><?php echo $ticket->account_contact->firstname . ' ' . $ticket->account_contact->lastname; ?></span></h3>
                        <div class="timeline-body">
                            <?php 
                            echo '<span class="lead">' . $ticket->subject . '</span><br>';
                            echo nl2br($ticket->description); 
			    if(isset($ticket->attachments) && count($ticket->attachments) > 0)
                            {
                                foreach($ticket->attachments AS $attachment)
                                {
                                    echo '<br><br><i class="fa fa-file"></i> ';
                                    echo '<span style="cursor:pointer; text-decoration: underline;" onclick="downloadFile(\'' . $attachment->download_url . '\', \'' . $attachment->original_filename . '\', \'' . $attachment->extension . '\');">';
                                    echo $attachment->original_filename . '.' . $attachment->extension; 
                                    echo '</span>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </li>

                <?php 
                foreach($ticket->ticket_notes AS $ticketNote)
                {
                    $c1 = 'bg-green';
                    $c2 = 'fa-user-md bg-green';
                    if($ticketNote->account_contact_id != '')
                    {
                        $c1 = 'bg-blue';
                        $c2 = 'fa-user bg-blue';
                    }

                    echo '<li class="time-label">';
                    echo '<span class="' . $c1 . '">';
                    echo Date::toMedium($ticketNote->created_at);
                    echo '</span>';
                    echo '</li>';

                    echo '<li>';
                    echo '<i class="fa ' . $c2 . '"></i>';
                    echo '<div class="timeline-item">';
                    echo '<span class="time"><i class="fa fa-clock-o"></i>' .Date::to($ticketNote->created_at, 'H:i') . '</span>';
                    echo '<h3 class="timeline-header">By <span class="text-info">';
                    echo ($ticketNote->account_contact_id == '') ? 'Perspective Support' : $ticket->account_contact->firstname . ' ' . $ticket->account_contact->lastname;
                    echo '</span></h3>';
                    echo '<div class="timeline-body">';
                    echo nl2br($ticketNote->notes); 
		    if(isset($ticketNote->attachments) && count($ticketNote->attachments) > 0)
                    {
                        foreach($ticketNote->attachments AS $attachment)
                        {
                            echo '<br><i class="fa fa-file"></i> ';
                            echo '<span style="cursor:pointer; text-decoration: underline;" onclick="downloadFile(\'' . $attachment->download_url . '\', \'' . $attachment->original_filename . '\', \'' . $attachment->extension . '\');">';
                            echo $attachment->original_filename . '.' . $attachment->extension; 
                            echo '</span>';
                        }
                    }
                    echo '</div>';
                    echo '</div>';
                    echo '</li>';
                }
                ?>
                
            </ul>

	    <?php if( in_array(strtolower($ticket->status->description), ['assigned', 'awaiting client', 'awaiting confirmation', 'requires additional requirements']) ) { ?>            
            <form role="form" id="frmTicketFeedback" method="post" enctype="multipart/form-data">
                <input type="hidden" name="firstname" value="<?php echo $_SESSION['user']->firstnames; ?>">
                <input type="hidden" name="lastname" value="<?php echo $_SESSION['user']->surname; ?>">
                <input type="hidden" name="ticket_id" value="<?php echo $ticket->id; ?>">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h5 class="box-title">Your feedback: </h5>
                    </div>
                    <div class="alert alert-danger alert-dismissible" id="errorPanel" style="display: none;">
                        <h4><i class="icon fa fa-exclamation-triangle"></i> Alert!</h4>
                        <ul id="errorList"></ul>
                    </div>
                    <div class="box-body">
                        <p class="text-center" id="loading" style="display: none;">
                            <img src="images/progress-animations/loading51.gif" alt="Loading" />
                        </p>
                        <div class="form-group">
                            <label for="account_contact_email">Email address: *</label>
                            <input type="text" 
                                style="background-color: #f3fae5 !important;border-width: 1px;border-color: #648827;background-color: #f3fae5 !important;border-style: solid;padding: 2px;" 
                                class="form-control compulsory" id="account_contact_email" name="account_contact_email" placeholder="Enter your email" 
                                value="<?php echo $_SESSION['user']->work_email; ?>" required maxlength="255">
                        </div>
                        <div class="form-group">
                            <label for="notes" class="control-label fieldLabel_compulsory">Notes/Details: *</label>
                            <textarea name="notes" id="notes" class="form-control compulsory" rows="10"></textarea>
                            
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
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="close_ticket" value="1"> Happy to close this ticket</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="button" id="btnSubmit" onclick="submitForm()" class="btn btn-block btn-success btn-sm">Send your feedback <i class="fa fa-send"></i></button>
                    </div>
                </div>
            </form>    
	    <?php } ?>
        </div>
    </div>

    <br>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/common.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script language="JavaScript">
        const TokenID = '<?php echo $supportHelper->getXTokenId(); ?>';
    </script>

    <script language="JavaScript">
        function submitForm() {
            const form = document.getElementById('frmTicketFeedback');
            if(! validateForm(form) )
            {
                return;
            }
            if(! validateEmail(document.getElementById('account_contact_email').value) )
            {
                return alert('Please enter a valid email address');
            }
            const loadingMessage = document.getElementById('loading');
            const errorPanel = document.getElementById('errorPanel');
            const errorList = document.getElementById('errorList');

            errorList.innerHTML = '';
            loadingMessage.style.display = 'block';
            const formData = new FormData(form);
            const apiUrl = 'https://tickets.sunesis.uk.net/api/v1/tickets/create_note';

            axios.post(apiUrl, formData, {
                    headers: {
                        'X-TokenID': TokenID,
                        'Content-Type': 'multipart/form-data',
                    },
                })
                .then(function(response) {

                    window.location.reload();
                    
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

	function downloadFile(downloadUrl, fileName, fileExtenstion) {
            axios({
                headers: {
                    'X-TokenID': TokenID,
                    'Content-Type': 'multipart/form-data',
                },
                url: downloadUrl, 
                method: 'GET',
                responseType: 'blob',
            })
            .then((response) => {
                const href = URL.createObjectURL(response.data);
                const link = document.createElement('a');
                link.href = href;
                link.setAttribute('download', fileName+'.'+fileExtenstion); 
                document.body.appendChild(link);
                link.click();

                document.body.removeChild(link);
                URL.revokeObjectURL(href);
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