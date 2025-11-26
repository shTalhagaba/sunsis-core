<?php /* @var $organisation Organisation */ ?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Create User</title>
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
<body class="container-fluid">
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;"> Create User</div>
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
    <input type="hidden" name="id" value="" />
    <input type="hidden" name="employer_id" value="<?php echo $organisation->id; ?>" />
    <input type="hidden" name="selected_menus" value="" />

    <div class="row">
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border"><h5 class="lead no-margin">Details</h5></div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="employer_id" class="col-sm-4 control-label fieldLabel_compulsory">Organisation: </label>
                        <div class="col-sm-8">
                            <div class="callout callout-default">
                                Name: <?php echo $organisation->legal_name; ?><br>
                                Type: <?php echo DAO::getSingleValue($link, "SELECT org_type FROM lookup_org_type WHERE id = '{$organisation->organisation_type}'"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="employer_location_id" class="col-sm-4 control-label fieldLabel_compulsory">Organisation Location: </label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('employer_location_id', $location_ddl, '', true, true); ?>
                        </div>
                    </div>
                    <?php if($organisation->organisation_type == Organisation::TYPE_TRAINING_PROVIDER) {?>
                    <div class="form-group">
                        <label for="department" class="col-sm-4 control-label fieldLabel_optional">Department: </label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('department', $departments_ddl, '', true, false); ?>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="type" class="col-sm-4 control-label fieldLabel_compulsory">Access Level / User Type: </label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('type', $accesses_ddl, '', false, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="firstnames" class="col-sm-4 control-label fieldLabel_compulsory">Firstname(s): </label>
                        <div class="col-sm-8"><input type="text" class="form-control compulsory" name="firstnames" id="firstnames" /></div>
                    </div>
                    <div class="form-group">
                        <label for="surname" class="col-sm-4 control-label fieldLabel_compulsory">Surname: </label>
                        <div class="col-sm-8"><input type="text" class="form-control compulsory" name="surname" id="surname" /></div>
                    </div>
                    <div class="form-group">
                        <label for="job_role" class="col-sm-4 control-label fieldLabel_optional">Position/Job Role: </label>
                        <div class="col-sm-8"><input type="text" class="form-control optional" name="job_role" id="job_role" value="" /></div>
                    </div>
                    <div class="form-group">
                        <label for="work_email" class="col-sm-4 control-label fieldLabel_optional">Email: </label>
                        <div class="col-sm-8"><input type="text" class="form-control optional" name="work_email" id="work_email" /></div>
                    </div>
                    <div class="form-group">
                        <label for="work_telephone" class="col-sm-4 control-label fieldLabel_optional">Telephone: </label>
                        <div class="col-sm-8"><input type="text" class="form-control optional" name="work_telephone" id="work_telephone" /></div>
                    </div>
                    <div class="form-group">
                        <label for="work_mobile" class="col-sm-4 control-label fieldLabel_optional">Mobile: </label>
                        <div class="col-sm-8"><input type="text" class="form-control optional" name="work_mobile" id="work_mobile" /></div>
                    </div>
                    <div class="form-group">
                        <label for="work_address_line_1" class="col-sm-4 control-label fieldLabel_optional">Address Line 1: </label>
                        <div class="col-sm-8"><input type="text" class="form-control optional" name="work_address_line_1" id="work_address_line_1" /></div>
                    </div>
                    <div class="form-group">
                        <label for="work_address_line_2" class="col-sm-4 control-label fieldLabel_optional">Address Line 2: </label>
                        <div class="col-sm-8"><input type="text" class="form-control optional" name="work_address_line_2" id="work_address_line_2" /></div>
                    </div>
                    <div class="form-group">
                        <label for="work_address_line_3" class="col-sm-4 control-label fieldLabel_optional">Address Line 3: </label>
                        <div class="col-sm-8"><input type="text" class="form-control optional" name="work_address_line_3" id="work_address_line_3" /></div>
                    </div>
                    <div class="form-group">
                        <label for="work_address_line_4" class="col-sm-4 control-label fieldLabel_optional">Address Line 4: </label>
                        <div class="col-sm-8"><input type="text" class="form-control optional" name="work_address_line_4" id="work_address_line_4" /></div>
                    </div>
                    <div class="form-group">
                        <label for="work_postcode" class="col-sm-4 control-label fieldLabel_optional">Postcode: </label>
                        <div class="col-sm-8"><input type="text" class="form-control optional" name="work_postcode" id="work_postcode" /></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box box-info">
                <div class="box-header with-border"><h5 class="lead no-margin">Access Credentials</h5> </div>
                <div class="box-body">
                    <div class="callout callout-info">
                        <p><i class="fa fa-info-circle"></i> Strong passwords are important for the protection of learner data.
                            The password may contain letters, numbers, spaces and punctuation.
                            The password must be between 8 and 50 characters long and contain at least one number, one lowercase letter and one uppercase letter.
                            Passwords based on single words are vulnerable to automated dictionary-attacks and are not allowed.</p>
                    </div>
                    <div class="form-group">
                        <label for="username" class="col-sm-4 control-label fieldLabel_compulsory">Username: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control compulsory" name="username" id="username" maxlength="20" onfocus="username_onfocus(this);"  />
                            <p class="text-muted" id="usernameMessage"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-4 control-label fieldLabel_compulsory">Password/Passphrase: </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control compulsory" name="password" id="password" maxlength="45" />
                        </div>
                        <div class="col-sm-4">
                            <span class="btn btn-info btn-md" onclick="document.getElementById('password').value=dicewarePassword(4,8,50);"><i class="fa fa-refresh"></i> Generate</span>
                        </div>
                    </div>
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
        $(window).trigger('resize');

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
            window.location.replace('do.php?_action=read_user&id=' + id);

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

    function employer_location_id_onchange(ele)
    {
        if(ele.value == '')
            return;

        var client = ajaxRequest('do.php?_action=add_system_user&subaction=getLocationAddress&location_id='+encodeURIComponent(ele.value));
        if(client)
        {
            var response = $.parseJSON(client.responseText);
            $('#work_address_line_1').val(response.address_line_1);
            $('#work_address_line_2').val(response.address_line_2);
            $('#work_address_line_3').val(response.address_line_3);
            $('#work_address_line_4').val(response.address_line_4);
            $('#work_postcode').val(response.postcode);
        }
    }

    function username_onfocus(username)
    {
        var firstnames = username.form.elements['firstnames'].value.toLowerCase();
        var surname = username.form.elements['surname'].value.toLowerCase();

        if(username.value == '')
        {
            var tmp = firstnames.substring(0,1) + surname.replace(/[^a-zA-Z]/, '');
            tmp = tmp.replace("'", "");
            username.value = tmp.substring(0,21);
        }
        if(username.value.length < 8)
        {
            var i = 1;
            do
            {
                username.value += i++;
            }while(username.value.length < 8);
        }
    }

</script>

</body>
</html>