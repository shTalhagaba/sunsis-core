<?php /* @var $vo Contract */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Contract</title>
    <link rel="stylesheet" href="/common.css" type="text/css"/>
    <script src="/js/jquery.min.js" type="text/javascript"></script>
    <script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>



	<script language="JavaScript">
        function save()
        {
            var myForm = document.forms[0];
            if(validateForm(myForm) == false)
            {
                return false;
            }

            myForm.submit();
        }


        function numbersonly(myfield, e, dec)
        {
            var key;
            var keychar;

            if (window.event)
                key = window.event.keyCode;
            else if (e)
                key = e.which;
            else
                return true;

            keychar = String.fromCharCode(key);


// control keys
            if ((key==null) || (key==0) || (key==8) ||
                    (key==9) || (key==13) || (key==27) )
                return true;

// numbers
            else if ((("0123456789.").indexOf(keychar) > -1))
                return true;

// decimal point jump
            else if (dec && (keychar == "."))
            {
                myfield.form.elements[dec].focus();
                return false;
            }
            else
                return false;


        }


        /*function funding_body_onchange(fundingBody)
        {
            var contractType = document.forms[0].elements['contract_type'];
            if(fundingBody.value != null && fundingBody.value != '')
            {
                ajaxPopulateSelect(contractType, 'do.php?_action=ajax_load_contracttype_dropdown&funding_body=' + encodeURIComponent(fundingBody.value));
            }
            else
            {
                emptySelectElement(contractType);
            }
        }
        */

        function contract_year_onchange(year)
        {
            if(year.value==2013)
            {
                document.getElementById('input_start_date').value = '01/08/2013';
                document.getElementById('input_end_date').value = '31/07/2014';
            }
            if(year.value==2012)
            {
                document.getElementById('input_start_date').value = '01/08/2012';
                document.getElementById('input_end_date').value = '31/07/2013';
            }
            else if(year.value==2011)
            {
                document.getElementById('input_start_date').value = '01/08/2011';
                document.getElementById('input_end_date').value = '31/07/2012';
            }
            else if(year.value==2010)
            {
                document.getElementById('input_start_date').value = '01/08/2010';
                document.getElementById('input_end_date').value = '31/07/2011';
            }
            else if(year.value==2009)
            {
                document.getElementById('input_start_date').value = '01/08/2009';
                document.getElementById('input_end_date').value = '31/07/2010';
            }
            else if(year.value==2008)
            {
                document.getElementById('input_start_date').value = '01/08/2008';
                document.getElementById('input_end_date').value = '31/07/2009';
            }
            else if(year.value==2007)
            {
                document.getElementById('input_start_date').value = '01/08/2007';
                document.getElementById('input_end_date').value = '31/07/2008';
            }
        }

        function contract_holder_onchange(contractholder)
        {

            var request = ajaxBuildRequestObject();
            request.open("GET", expandURI('do.php?_action=ajax_get_upin&id=' + contractholder.value), false);
            request.setRequestHeader("x-ajax", "1"); // marker for server code		request.setRequestHeader("x-ajax", "1"); // marker for server code
            request.send(null);


            if(request.status == 200)
            {
                var upin = request.responseText;

                if(upin != 'error')
                {
                    if(document.getElementById('contract_year').value!='' && document.getElementById('contract_year').value<2012)
                        document.getElementById('upin').value = upin;
                }
                else
                {
                }
            }
            else
            {
                ajaxErrorHandler(request);
            }

            var request = ajaxBuildRequestObject();
            request.open("GET", expandURI('do.php?_action=ajax_get_ukprn&id=' + contractholder.value), false);
            request.setRequestHeader("x-ajax", "1"); // marker for server code		request.setRequestHeader("x-ajax", "1"); // marker for server code
            request.send(null);


            if(request.status == 200)
            {
                var ukprn = request.responseText;

                if(upin != 'error')
                {
                    document.getElementById('ukprn').value = ukprn;
                }
                else
                {
                }
            }
            else
            {
                ajaxErrorHandler(request);
            }
        }

    </script>

</head>
<body>
<div class="banner">
    <div class="Title">Module</div>
    <div class="ButtonBar">
        <?php if($_SESSION['user']->type!=12){?>
        <button onclick="save();">Save</button>
        <?php }?>
        <button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
    </div>
    <div class="ActionIconBar">

    </div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Details</h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="id" value="<?php echo $vo->id ?>" />
    <input type="hidden" name="_action" value="save_module" />
    <table border="0" cellspacing="4" style="margin-left:10px">
        <col width="170" />
        <tr>
            <td class="fieldLabel_compulsory">Title: (Ref)</td>
            <td><input class="compulsory" type="text" name="title" value="<?php echo htmlspecialchars((string)$vo->title); ?>" size="40" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Training Provider:</td>
            <td><?php echo HTML::select('provider_id', $providers, $vo->provider_id, true, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Learning Hours:</td>
            <td><input class="optional" type="text" id="learning_hours" name="learning_hours" value="<?php echo htmlspecialchars((string)$vo->learning_hours); ?>" size="15" /></td>
        </tr>
    </table>

    <!-- Popup calendar -->
    <div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>