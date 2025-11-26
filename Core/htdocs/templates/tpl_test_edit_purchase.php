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

    <!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
    <script language="JavaScript" src="/calendarPopup/CalendarPopup.js"></script>

    <!-- Initialise calendar popup -->
    <script language="JavaScript">
        <?php if(preg_match('/MSIE [1-6]/', $_SERVER['HTTP_USER_AGENT']) ) { ?>
        var calPop = new CalendarPopup();
        calPop.showNavigationDropdowns();
            <?php } else { ?>
        var calPop = new CalendarPopup("calPop1");
        calPop.showNavigationDropdowns();
        document.write(getCalendarStyles());
            <?php } ?>
    </script>

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

        function make_onchange(make, event)
        {
            var f = make.form;
            var model = f.elements['model'];

            if(make.value != '')
            {
                make.disabled = true;
                ajaxPopulateSelect(model, 'do.php?_action=ajax_load_test_models&make=' + make.value);
                make.disabled = false;
            }
            else
            {
                emptySelectElement(model);
            }
        }

    </script>

</head>
<body>
<div class="banner">
    <div class="Title">Purchase</div>
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
    <input type="hidden" name="_action" value="test_save_purchase" />
    <table border="0" cellspacing="4" style="margin-left:10px">
        <col width="170" />
        <tr>
            <td class="fieldLabel_compulsory">Purchase Date:</td>
            <td><?php echo HTML::datebox('sales_date', $vo->sales_date, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Make:</td>
            <td><?php echo HTML::select('make', $makes, $vo->make, true, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Model:</td>
            <td><?php echo HTML::select('model', $models, $vo->model, true, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Registration</td>
            <td><input class="compulsory" type="text" name="reg_mark" value="<?php echo htmlspecialchars((string)$vo->reg_mark); ?>" size="40" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Transmission:</td>
            <td><?php echo HTML::select('transmission', $transmission, $vo->transmission, true, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Supplier Name</td>
            <td><input class="optional" type="text" name="c_name" value="<?php echo htmlspecialchars((string)$vo->c_name); ?>" size="40" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Supplier Address</td>
            <td><input class="optional" type="text" name="c_address" value="<?php echo htmlspecialchars((string)$vo->c_address); ?>" size="40" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Supplier Phone</td>
            <td><input class="optional" type="text" name="c_address" value="<?php echo htmlspecialchars((string)$vo->c_phone); ?>" size="40" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Price</td>
            <td><input class="compulsory" type="text" name="price" value="<?php echo htmlspecialchars((string)$vo->price); ?>" size="40" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Colour</td>
            <td><input class="compulsory" type="text" name="colour" value="<?php echo htmlspecialchars((string)$vo->colour); ?>" size="40" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Keys</td>
            <td><input class="compulsory" type="text" name="car_keys" value="<?php echo htmlspecialchars((string)$vo->car_keys); ?>" size="40" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Engine Size</td>
            <td><input class="compulsory" type="text" name="engine_size" value="<?php echo htmlspecialchars((string)$vo->engine_size); ?>" size="40" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Mileage</td>
            <td><input class="compulsory" type="text" name="mileage" value="<?php echo htmlspecialchars((string)$vo->mileage); ?>" size="40" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Service History?:</td>
            <td class="optional"><?php echo HTML::checkbox('service_history', 1, $vo->service_history, true, false); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">MOT Expiry Date:</td>
            <td><?php echo HTML::datebox('mot', $vo->mot, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Road Tax Expiry Date:</td>
            <td><?php echo HTML::datebox('road_tax', $vo->road_tax, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Previous Owners</td>
            <td><input class="compulsory" type="text" name="owners" value="<?php echo htmlspecialchars((string)$vo->owners); ?>" size="40" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Transportation cost</td>
            <td><input class="compulsory" type="text" name="transport" value="<?php echo htmlspecialchars((string)$vo->transport); ?>" size="40" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Mechanical work</td>
            <td><input class="compulsory" type="text" name="repair" value="<?php echo htmlspecialchars((string)$vo->repair); ?>" size="40" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Valeting</td>
            <td><input class="compulsory" type="text" name="valet" value="<?php echo htmlspecialchars((string)$vo->valet); ?>" size="40" /></td>
        </tr>
    </table>

    <h3>Imported Car</h3>
    <table>
        <tr>
            <td class="fieldLabel_optional">SVA Fee</td>
            <td><input class="optional" type="text" name="sva_fee" value="<?php echo htmlspecialchars((string)$vo->sva_fee); ?>" size="40" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Registration Fee</td>
            <td><input class="optional" type="text" name="registration_fee" value="<?php echo htmlspecialchars((string)$vo->registration_fee); ?>" size="40" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Speedo Meter Change</td>
            <td><input class="optional" type="text" name="speedo_meter_change" value="<?php echo htmlspecialchars((string)$vo->speedo_meter_change); ?>" size="40" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Immobiliser</td>
            <td><input class="optional" type="text" name="immobiliser" value="<?php echo htmlspecialchars((string)$vo->immobiliser); ?>" size="40" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Custom Duty</td>
            <td><input class="optional" type="text" name="custom_duty" value="<?php echo htmlspecialchars((string)$vo->custom_duty); ?>" size="40" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">VAT</td>
            <td><input class="optional" type="text" name="vat" value="<?php echo htmlspecialchars((string)$vo->vat); ?>" size="40" /></td>
        </tr>
    </table>

    <!-- Popup calendar -->
    <div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>