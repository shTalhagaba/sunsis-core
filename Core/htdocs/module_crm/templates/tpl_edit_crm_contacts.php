
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $contact->contact_id == ''?'Add Contact Person':'Edit Contact Person'; ?></title>
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
            <div class="Title" style="margin-left: 6px;"><?php echo $contact->contact_id == ''?'Add Contact Person':'Edit Contact Person'; ?></div>
            <div class="ButtonBar">
                <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';">
                    <i class="fa fa-arrow-circle-o-left"></i> Cancel
                </span>
                <span class="btn btn-xs btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
                <?php if($contact->contact_id != "" && $_SESSION['user']->isAdmin()) { ?>
                    <span class="btn btn-xs btn-danger" onclick="deleteContact();"><i class="fa fa-remove"></i> Delete</span>
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

</div>

<div class="row">
    <div class="col-sm-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h2 class="box-title"><?php echo $contact->contact_id == ''?'Add':'Edit'; ?> Details</h2>
            </div>
            <form autocomplete="off" class="form-horizontal" name="frmCrmContact" id="frmCrmContact"
                 action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                 <input type="hidden" name="_action" value="save_crm_contacts" />
                <input type="hidden" name="contact_id" value="<?php echo $contact->contact_id; ?>" />
                <input type="hidden" name="org_id" value="<?php echo $org_id; ?>" />
                <input type="hidden" name="org_type" value="<?php echo $org_type; ?>" />
                <div class="box-body with-border">
                    <div class="form-group">
                        <label for="contact_title" class="col-sm-4 control-label fieldLabel_compulsory">Title:</label>
                        <div class="col-sm-8">
                            <input class="form-control optional" type="text" name="contact_title" id="contact_title" value="<?php echo $contact->contact_title; ?>" maxlength="10" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="contact_name" class="col-sm-4 control-label fieldLabel_compulsory">Name:</label>
                        <div class="col-sm-8">
                            <input class="form-control compulsory" type="text" name="contact_name" id="contact_name" value="<?php echo $contact->contact_name; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="job_title" class="col-sm-4 control-label fieldLabel_optional">Job Title:</label>
                        <div class="col-sm-8">
                            <input class="form-control optional" type="text" name="job_title" id="job_title" value="<?php echo $contact->job_title; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="contact_telephone" class="col-sm-4 control-label fieldLabel_optional">Telephone:</label>
                        <div class="col-sm-8">
                            <input class="form-control optional" type="text" name="contact_telephone" id="contact_telephone" value="<?php echo $contact->contact_telephone; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="contact_mobile" class="col-sm-4 control-label fieldLabel_optional">Mobile:</label>
                        <div class="col-sm-8">
                            <input class="form-control optional" type="text" name="contact_mobile" id="contact_mobile" value="<?php echo $contact->contact_mobile; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="contact_email" class="col-sm-4 control-label fieldLabel_optional">Email:</label>
                        <div class="col-sm-8">
                            <input class="form-control optional" type="email" name="contact_email" id="contact_email" value="<?php echo $contact->contact_email; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="job_title" class="col-sm-4 control-label fieldLabel_optional">Job Title:</label>
                        <div class="col-sm-8">
                            <input class="form-control optional" type="text" name="job_title" id="job_title" value="<?php echo $contact->job_title; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="decision_maker" class="col-sm-4 control-label fieldLabel_optional">Decision Maker:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('decision_maker', [[0, 'No'], [1, 'Yes']], $contact->decision_maker); ?>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <span class="btn btn-sm btn-primary pull-left" onclick="save();"><i class="fa fa-save"></i> Save Information</span>
                    <span class="btn btn-xs btn-danger pull-right" onclick="deleteContact();"><i class="fa fa-trash"></i> Delete</span>
                </div>
            </form>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="callout callout-default">
            <h5 class="lead text-bold text-success">Company: <?php echo $organisation->legal_name; ?></h5>
            <span class="text-bold">Type: </span><?php echo ucwords($org_type); ?><br>
            <span class="text-bold">System ID: </span><?php echo $organisation->id; ?><br>
            <div class="callout callout-default">
                <span class="text-bold">Contacts:</span><br>
                <?php
                $result = $org_type == 'pool' ? 
                    DAO::getResultset($link, "SELECT * FROM pool_contact WHERE pool_id = '{$org_id}' AND contact_id != '{$contact->contact_id}' ORDER BY contact_name", DAO::FETCH_ASSOC) :
                    DAO::getResultset($link, "SELECT * FROM crm_contacts WHERE org_id = '{$org_id}' AND contact_id != '{$contact->contact_id}' ORDER BY contact_name", DAO::FETCH_ASSOC);
                if(count($result) > 0)
                {
                    echo '<ul class="products-list product-list-in-box">';
                    foreach($result AS $row)
                    {
                        echo '<div class="product-img"><i class="fa fa-user fa-2x"></i></div>';
                        echo '<div class="product-info">';
                        echo '<a href="do.php?_action=edit_crm_contacts&contact_id='.$row['contact_id'].'&org_id='.$org_id.'&org_type='.$org_type.'" class="product-title text-green">' . $row['contact_title'] . ' ' . $row['contact_name'] . '</a>';
                        echo '<span class="product-description">';
                        echo $row['contact_telephone'] != '' ? '<i class="fa fa-phone"></i> ' . $row['contact_telephone'] . ' | ' : '';
                        echo $row['contact_mobile'] != '' ? '<i class="fa fa-mobile"></i> ' . $row['contact_mobile'] . ' <br> ' : '';
                        echo $row['contact_email'] != '' ? '<i class="fa fa-envelope"></i> ' . $row['contact_email'] . '<br>' : '';
                        echo $row['job_role'] != '' ? $row['job_role'] . '<br>' : '';
                        echo $row['job_title'] != '' ? $row['job_title'] . '<br>' : '';
                        echo '</span>';
                        echo '</div>';
                        echo '</li>';
                    }
                    echo '</ul>';
                }
                ?>
            </div>
        </div>
    </div>
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

    function save()
    {
        var myForm = document.forms['frmCrmContact'];
        if(validateForm(myForm) == false)
        {
            return false;
        }
        if(myForm.contact_email.value != '' && !validateEmail(myForm.contact_email.value))
        {
            alert('Please enter valid email address.');
            myForm.contact_email.focus();
            return;
        }

        myForm.submit();
    }

    function deleteContact()
    {
        if(window.confirm("Do you really want to delete this Contact?"))
        {
            window.location.replace('do.php?_action=ajax_helper&subaction=delete_crm_contact&id=<?php echo $contact->contact_id; ?>&org_id=<?php echo $org_id; ?>&org_type=<?php echo $org_type; ?>');
        }
    }

</script>

</body>
</html>