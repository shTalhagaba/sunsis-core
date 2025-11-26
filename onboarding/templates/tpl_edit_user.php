<?php /* @var $vo User */ ?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $vo->id == "" ? 'Create User' : "Edit User"; ?></title>
    <link rel="stylesheet" href="css/common.css?n=<?php echo time(); ?>" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body class="container-fluid" onload="body_onload();">
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;"><?php echo $vo->id == "" ? 'Create User' : "Edit User"; ?></div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
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

<form class="form-horizontal" name="frmEditUser" id="frmEditUser" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="_action" value="save_user" />
    <input type="hidden" name="id" value="<?php echo $vo->id; ?>" />
    <input type="hidden" name="selected_menus" value="" />

    <div class="row">
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border"><h5 class="lead no-margin">Personal Details</h5></div>
                <div class="box-body">
                    <div class="callout callout-info"><i class="fa fa-info-circle"></i> First and second names may only contain the letters a-z, spaces, hyphens and apostrophes.</div>
                    <div class="form-group">
                        <label for="firstnames" class="col-sm-4 control-label fieldLabel_compulsory">Firstname(s): </label>
                        <div class="col-sm-8"><input type="text" class="form-control compulsory" name="firstnames" id="firstnames" value="<?php echo htmlspecialchars($vo->firstnames ?? ''); ?>" /></div>
                    </div>
                    <div class="form-group">
                        <label for="surname" class="col-sm-4 control-label fieldLabel_compulsory">Surname: </label>
                        <div class="col-sm-8"><input type="text" class="form-control compulsory" name="surname" id="surname" value="<?php echo htmlspecialchars($vo->surname ?? ''); ?>" /></div>
                    </div>
                    <div class="form-group">
                        <label for="job_role" class="col-sm-4 control-label fieldLabel_optional">Position/Job Role: </label>
                        <div class="col-sm-8"><input type="text" class="form-control optional" name="job_role" id="job_role" value="<?php echo htmlspecialchars($vo->job_role ?? ''); ?>" /></div>
                    </div>
                    <div class="form-group">
                        <label for="work_email" class="col-sm-4 control-label fieldLabel_optional">Email: </label>
                        <div class="col-sm-8"><input type="text" class="form-control optional" name="work_email" id="work_email" value="<?php echo htmlspecialchars($vo->work_email ?? ''); ?>" /></div>
                    </div>
                    <div class="form-group">
                        <label for="work_telephone" class="col-sm-4 control-label fieldLabel_optional">Telephone: </label>
                        <div class="col-sm-8"><input type="text" class="form-control optional" name="work_telephone" id="work_telephone" value="<?php echo htmlspecialchars($vo->work_telephone ?? ''); ?>" /></div>
                    </div>
                    <div class="form-group">
                        <label for="work_mobile" class="col-sm-4 control-label fieldLabel_optional">Mobile: </label>
                        <div class="col-sm-8"><input type="text" class="form-control optional" name="work_mobile" id="work_mobile" value="<?php echo htmlspecialchars($vo->work_mobile ?? ''); ?>" /></div>
                    </div>
                    <div class="form-group">
                        <label for="work_address_line_1" class="col-sm-4 control-label fieldLabel_optional">Address Line 1: </label>
                        <div class="col-sm-8"><input type="text" class="form-control optional" name="work_address_line_1" id="work_address_line_1" value="<?php echo htmlspecialchars($vo->work_address_line_1 ?? ''); ?>" /></div>
                    </div>
                    <div class="form-group">
                        <label for="work_address_line_2" class="col-sm-4 control-label fieldLabel_optional">Address Line 2: </label>
                        <div class="col-sm-8"><input type="text" class="form-control optional" name="work_address_line_2" id="work_address_line_2" value="<?php echo htmlspecialchars($vo->work_address_line_2 ?? ''); ?>" /></div>
                    </div>
                    <div class="form-group">
                        <label for="work_address_line_3" class="col-sm-4 control-label fieldLabel_optional">Address Line 3: </label>
                        <div class="col-sm-8"><input type="text" class="form-control optional" name="work_address_line_3" id="work_address_line_3" value="<?php echo htmlspecialchars($vo->work_address_line_3 ?? ''); ?>" /></div>
                    </div>
                    <div class="form-group">
                        <label for="work_address_line_4" class="col-sm-4 control-label fieldLabel_optional">Address Line 4: </label>
                        <div class="col-sm-8"><input type="text" class="form-control optional" name="work_address_line_4" id="work_address_line_4" value="<?php echo htmlspecialchars($vo->work_address_line_4 ?? ''); ?>" /></div>
                    </div>
                    <div class="form-group">
                        <label for="work_postcode" class="col-sm-4 control-label fieldLabel_optional">Postcode: </label>
                        <div class="col-sm-8"><input type="text" class="form-control optional" name="work_postcode" id="work_postcode" value="<?php echo htmlspecialchars($vo->work_postcode ?? ''); ?>" /></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box box-info">
                <div class="box-header with-border"><h5 class="lead no-margin">Security Credentials</h5> </div>
                <div class="box-body">
                    <div class="callout callout-info">
                        <p><i class="fa fa-info-circle"></i> Strong passwords are important for the protection of learner data.
                            The password may contain letters, numbers, spaces and punctuation.
                            The password must be between 8 and 50 characters long and contain at least one number, one lowercase letter and one uppercase letter.
                            Passwords based on single words are vulnerable to automated dictionary-attacks and are not allowed.</p>
                        <?php if($vo->username != '') { ?>
                            <p><i class="fa fa-info-circle"></i> Leave this field blank to retain the user's existing passphrase. </p>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="username" class="col-sm-4 control-label fieldLabel_compulsory">Username: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control compulsory" name="username" id="username" value="<?php echo htmlspecialchars($vo->username ?? ''); ?>" <?php echo $vo->username != '' ? 'disabled="disabled"' : ''; ?> maxlength="20" />
                            <p class="text-muted" id="usernameMessage"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-4 control-label <?php echo $vo->username == '' ? 'fieldLabel_compulsory' : 'fieldLabel_optional'; ?>">Password/Passphrase: </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control <?php echo $vo->username == '' ? 'compulsory' : 'optional'; ?>" name="password" id="password" maxlength="45" />
                        </div>
                        <div class="col-sm-4">
                            <span class="btn btn-info btn-md" onclick="document.getElementById('password').value=dicewarePassword(4,8,50);"><i class="fa fa-refresh"></i> Generate</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border"><h5 class="lead no-margin">Organisation and Access Level</h5> </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="org_type" class="col-sm-4 control-label fieldLabel_compulsory">Organisation Type: </label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('org_type', $organisationsTypes, $user_org_type, false, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="employer_id" class="col-sm-4 control-label fieldLabel_compulsory">Organisation: </label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('employer_id', $orgs_ddl, $vo->employer_id, false, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="employer_location_id" class="col-sm-4 control-label fieldLabel_compulsory">Organisation Location: </label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('employer_location_id', $locs_ddl, $vo->employer_location_id, false, true); ?>
                        </div>
                    </div>
                    <?php if($user_org->organisation_type == Organisation::TYPE_TRAINING_PROVIDER) {?>
                        <div class="form-group">
                            <label for="department" class="col-sm-4 control-label fieldLabel_optional">Department: </label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('department', $departments_ddl, $vo->department, true, false); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="type" class="col-sm-4 control-label fieldLabel_compulsory">Access Level / User Type: </label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('type', $accesses_ddl, $vo->type, false, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="web_access" class="col-sm-4 control-label fieldLabel_compulsory">Web Access: </label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('web_access', [[1, 'Enable'], [0, 'Disable']], $vo->web_access, false, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ob_access_only" class="col-sm-4 control-label fieldLabel_optional">Access to Onboarding Module only: </label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('ob_access_only', [[0, 'No'], [1, 'Yes']], $vo->ob_access_only, false); ?>
                        </div>
                    </div>
                    <?php if( DB_NAME == "am_ela" ){?>
                    <div class="form-group">
                        <label for="learners_caseload" class="col-sm-4 control-label fieldLabel_optional">Learners Caseload: </label>
                        <div class="col-sm-8">
			    <?php
                            $caseloadList = [
                                [0, 'Access to All'], 
                                [OnboardingLearner::CASELOAD_FRONTLINE, 'Access to Frontline Learners'], 
                                [OnboardingLearner::CASELOAD_LINKS_TRAINING, 'Access to Links Training Learners'], 
                                [OnboardingLearner::CASELOAD_NEW_ACCESS, ' MOD'], 
                                [OnboardingLearner::CASELOAD_INTERNAL_ELA, 'Access to Internal ELA Learners'],
                                [OnboardingLearner::CASELOAD_ADMIN_SALES, 'Admin Sales']
                            ];
                            echo HTML::selectChosen('learners_caseload', $caseloadList, $vo->learners_caseload, true, false); 
                            ?>
                        </div>
                    </div>
                    <?php } ?>
<!--                    <div class="form-group">-->
<!--                        <label for="access_list" class="col-sm-4 control-label fieldLabel_compulsory">Permissions</label>-->
<!--                        <div class="col-sm-8">-->
<!--                            --><?php
//                            $menus = DAO::getResultset($link, "SELECT * FROM lookup_menus WHERE parent_id IS NULL ORDER BY s_order ASC", DAO::FETCH_ASSOC);
//                            echo '<table class="table row-border table-bordered">';
//                            foreach($menus AS $main)
//                            {
//                                echo '<tr><td>';
//                                echo '<p><input id="'.$main['id'].'" type="checkbox" class="parent_menu" /> &nbsp; ' . $main['title'] . '</p>';
//                                $submenus = DAO::getResultset($link, "SELECT * FROM lookup_menus WHERE parent_id = '{$main['id']}' ORDER BY s_order ASC", DAO::FETCH_ASSOC);
//                                echo '<p style="margin-left: 15px;">';
//                                foreach($submenus AS $sub)
//                                {
//                                    echo '<input id="'.$sub['id'].'" type="checkbox" class="'.$main['id'].'" /> &nbsp; ' . $sub['title'] . '<br>';
//                                }
//                                echo '</p>';
//                                echo '</td></tr>';
//                            }
//                            echo '</table>';
//                            ?>
<!--                        </div>-->
<!--                    </div>-->
                </div>
            </div>
        </div>
    </div>

</form>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script language="JavaScript" src="js/password.js"></script>
<script src="js/common.js" type="text/javascript"></script>

<script language="JavaScript">

    function org_type_onchange(select)
    {
        updateOrganisationsList();
        updateOrganisationLocationsList();
        updateAccessList();
    }

    function employer_id_onchange(select)
    {
        updateOrganisationLocationsList();
    }

    function updateOrganisationsList()
    {
        var org_type = $('[name=org_type]');
        var org_id = $('[name=employer_id]');

        var url = "do.php?_action=edit_user&subaction=getOrganisationDDL"
            + "&org_type=" + org_type.val();
        if(org_id.length)
        {
            ajaxPopulateSelect(org_id[0], url);
        }
    }

    function updateOrganisationLocationsList()
    {
        var org_id = $('[name=employer_id]');
        var org_location = $('[name=employer_location_id]');

        var url = "do.php?_action=edit_user&subaction=getOrganisationLocationDDL"
            + "&org_id=" + org_id.val();
        if(org_location.length)
        {
            ajaxPopulateSelect(org_location[0], url);
        }
    }

    function updateAccessList()
    {
        var org_type = $('[name=org_type]');
        var type = $('[name=type]');

        var url = "do.php?_action=edit_user&subaction=getAccessDDL"
            + "&org_type=" + org_type.val();
        if(type.length)
        {
            ajaxPopulateSelect(type[0], url);
        }
    }

    function body_onload()
    {
        // updateOrganisationsList();
        // updateOrganisationLocationsList();
        // updateAccessList();
        $(window).trigger('resize');
    }

    function validateUsername(e)
    {
        var f = this.form;
        if(f.elements.id.value != ""){
            return;
        }

        if(this.value.length == 0){
            $(this).css("color", "black");
            $('#usernameMessage').text("");
            return false;
        }

        if(this.value.length < 8){
            $(this).css("color", "red");
            $('#usernameMessage').text("Too short").css("color", "red");
            return;
        }

        if(this.value.length > 20){
            $(this).css("color", "red");
            $('#usernameMessage').text("Too long").css("color", "red");
            return;
        }

        re = /^[a-z][a-z0-9_]{7,19}$/;
        this.value = this.value.toLowerCase();
        this.value = jQuery.trim(this.value);
        if(re.test(this.value) == false){
            $(this).css("color", "red");
            $('#usernameMessage').text("Wrong format (see above)").css("color", "red");
            return;
        }


        var client = ajaxRequest("do.php?_action=ajax_check_username&username=" + encodeURIComponent(this.value));
        if(client && client.responseText == 0)
        {
            $(this).css("color", "red");
            $('#usernameMessage').text("Already taken").css("color", "red");
        }
        else
        {
            $(this).css("color", "green");
            $('#usernameMessage').text("Valid").css("color", "green");
        }

    }

    $(function(){
        $('input[name=username]').keyup(validateUsername).change(validateUsername);
    });

    function save()
    {
        if(window.saveLock)	{
            return;
        }

        window.saveLock = true;

        var myForm = document.forms[0];

        if(!validateForm(myForm)){
            window.saveLock = false;
            return false;
        }

        // First and second name validation
        var fn = myForm.elements.firstnames;
        var sn = myForm.elements.surname;
        fn.value = jQuery.trim(fn.value);
        sn.value = jQuery.trim(sn.value);
        var re = /^[a-zA-Z\x27\x2D ]+$/;
        if(re.test(fn.value) == false){
            alert("The firstname(s) may only contain the letters a-z, spaces, hyphens and apostrophes.");
            fn.focus();
            window.saveLock = false;
            return false;
        }
        if(re.test(sn.value) == false){
            alert("The surname may only contain the letters a-z, spaces, hyphens and apostrophes.");
            sn.focus();
            window.saveLock = false;
            return false;
        }

        // Username validation
        var un = myForm.elements.username;
        var id = myForm.elements.id.value;
        re = /^[a-z][a-z0-9_]{7,19}$/;
        un.value = un.value.toLowerCase();
        un.value = jQuery.trim(un.value);
        if(id == "" && re.test(un.value) == false){
            alert("The username should be 8 to 20 characters in length, be in lowercase, start with a letter and may contain letters, numbers and underscores only.");
            un.focus();
            window.saveLock = false;
            return false;
        }

        if(myForm.elements["id"].value == "")
        {
            var client = ajaxRequest("do.php?_action=ajax_check_username&username=" + encodeURIComponent(un.value));
            if(client && client.responseText == 0)
            {
                alert("Your chosen username, '" + un.value + "', has already been taken. Please try a different variation.");
                un.focus();
                window.saveLock = false;
                return false;
            }
        }

        // Validate password on server
        var pwd = myForm.elements.password;
        if(pwd.value.length > 0){
            if(pwd.value.length < 8){
                alert("Passphrase must be between 8 and 50 characters long");
                pwd.focus();
                window.saveLock = false;
                return false;
            }

            var username = myForm.elements.username.value;
            var firstnames = myForm.elements.firstnames.value;
            var surname = myForm.elements.surname.value;
            var org_legal_name = $('#employer_id :selected').text();
            var illegalWords = username + " " + firstnames + " " + surname + " " + org_legal_name;
            var client = ajaxRequest("do.php?_action=ajax_check_password_strength"
                + "&pwd=" + encodeURIComponent(pwd.value)
                + "&extra_words=" + encodeURIComponent(illegalWords));
            if(client != null){
                var res = window.JSON ? JSON.parse(client.responseText) : eval("(" + client.responseText + ")");
                if(res['code'] == 0){
                    alert("Password unsuitable because " + res['message']);
                    pwd.value = '';
                    pwd.focus();
                    window.saveLock = false;
                    return false;
                }
            }
        }

        var selected_menus = [];
        $('input[type=checkbox]').each(function(){
            if(this.checked)
                selected_menus.push(this.id);
        });

        myForm.elements['selected_menus'].value = selected_menus;

        var client = ajaxPostForm(myForm);
        if(client != null){
            var id = parseInt(client.responseText);
            // window.location.replace('do.php?_action=read_user&id=' + id);
            window.location.replace('<?php echo $referer; ?>');

            // Exit the function without releasing the save lock
            return;
        }

        window.saveLock = false;
    }

    $(function(){
        $('.parent_menu').on('click', function(){
            if(this.checked)
            {
                $('.'+this.id).prop('checked', true);
            }
            else
            {
                $('.'+this.id).prop('checked', false);
            }
        });
    });
</script>

</body>
</html>