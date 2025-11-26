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

        function registration_onchange(id)
        {
            query = "SELECT description FROM car_makes INNER JOIN test_purchase ON test_purchase.make = car_makes.id WHERE test_purchase.id=";
            var request = ajaxRequest("do.php?_action=ajax_get_value&id=" + id.value + "&query=" + htmlspecialchars(query));
            document.getElementById('make').value = request.responseText;

            query = "SELECT description FROM car_models INNER JOIN test_purchase ON test_purchase.model = car_models.id WHERE test_purchase.id=";
            var request = ajaxRequest("do.php?_action=ajax_get_value&id=" + id.value + "&query=" + htmlspecialchars(query));
            document.getElementById('model').value = request.responseText;

            query = "SELECT colour from test_purchase WHERE id=";
            var request = ajaxRequest("do.php?_action=ajax_get_value&id=" + id.value + "&query=" + htmlspecialchars(query));
            document.getElementById('colour').value = request.responseText;

            query = "SELECT car_keys from test_purchase WHERE id=";
            var request = ajaxRequest("do.php?_action=ajax_get_value&id=" + id.value + "&query=" + htmlspecialchars(query));
            document.getElementById('car_keys').value = request.responseText;

            query = "SELECT service_history from test_purchase WHERE id=";
            var request = ajaxRequest("do.php?_action=ajax_get_value&id=" + id.value + "&query=" + htmlspecialchars(query));
            document.getElementById('service_history').value = request.responseText;

            query = "SELECT mot from test_purchase WHERE id=";
            var request = ajaxRequest("do.php?_action=ajax_get_value&id=" + id.value + "&query=" + htmlspecialchars(query));
            document.getElementById('mot').value = request.responseText;

            query = "SELECT road_tax from test_purchase WHERE id=";
            var request = ajaxRequest("do.php?_action=ajax_get_value&id=" + id.value + "&query=" + htmlspecialchars(query));
            document.getElementById('road_tax').value = request.responseText;

            query = "SELECT owners from test_purchase WHERE id=";
            var request = ajaxRequest("do.php?_action=ajax_get_value&id=" + id.value + "&query=" + htmlspecialchars(query));
            document.getElementById('owners').value = request.responseText;

        }
    </script>

</head>
<body>
<div class="banner">
    <div class="Title">Sales</div>
    <div class="ButtonBar">
        <?php if($_SESSION['user']->type!=12){?>
        <button onclick="save();">Save</button>
        <?php }?>
        <button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
        <button onclick="window.location.href='do.php?_action=test_invoice&id='+<?php echo $vo->id; ?>"> Invoice </button>
        <button onclick="window.location.href='do.php?_action=test_invoice2&id='+<?php echo $vo->id; ?>"> Invoice 2</button>
        <button onclick="window.location.href='do.php?_action=test_invoice3&id='+<?php echo $vo->id; ?>"> Invoice 3</button>
    </div>
    <div class="ActionIconBar">

    </div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Details</h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="id" value="<?php echo $vo->id ?>" />
    <input type="hidden" name="_action" value="test_save_sales" />
    <table border="0" cellspacing="4" style="margin-left:10px">
        <col width="170" />
        <tr>
            <td class="fieldLabel_compulsory">Invoice No:</td>
            <td ><input class="optional" type="text" id="invoice" name="invoice" value="<?php echo $vo->invoice; ?>" size="15" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Registration:</td>
            <td><?php echo HTML::select('registration', $registrations, $vo->pid, true, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Make:</td>
            <td ><input class="optional" readonly type="text" id="make" name="make" value="<?php echo $p->make; ?>" size="15" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Model:</td>
            <td ><input class="optional" readonly type="text" id="model" name="model" value="<?php echo $p->model; ?>" size="15" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Colour</td>
            <td ><input class="optional" readonly type="text" id="colour" name="colour" value="<?php echo $p->colour; ?>" size="15" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Keys</td>
            <td ><input class="optional" readonly type="text" id="car_keys" name="car_keys" value="<?php echo $p->car_keys; ?>" size="15" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Service History?:</td>
            <td ><input class="optional" readonly type="text" id="service_history" name="service_history" value="<?php echo $p->service_history; ?>" size="15" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">MOT Expiry Date:</td>
            <td ><input class="optional" readonly type="text" id="mot" name="mot" value="<?php echo $p->mot; ?>" size="15" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Road Tax Expiry Date:</td>
            <td ><input class="optional" readonly type="text" id="road_tax" name="road_tax" value="<?php echo $p->road_tax; ?>" size="15" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Previous Owners</td>
            <td ><input class="optional" readonly type="text" id="owners" name="owners" value="<?php echo $p->owners; ?>" size="15" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Sales Date:</td>
            <td><?php echo HTML::datebox('sales_date', $vo->sales_date, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Customer Name</td>
            <td><input class="optional" type="text" name="c_name" value="<?php echo htmlspecialchars((string)$vo->c_name); ?>" size="40" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Customer Address</td>
            <td><input class="optional" type="text" name="c_address" value="<?php echo htmlspecialchars((string)$vo->c_address); ?>" size="40" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Customer Phone</td>
            <td><input class="optional" type="text" name="c_phone" value="<?php echo htmlspecialchars((string)$vo->c_phone); ?>" size="40" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Price</td>
            <td><input class="compulsory" type="text" name="price" value="<?php echo htmlspecialchars((string)$vo->price); ?>" size="40" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Deposit</td>
            <td><input class="optional" type="text" name="deposit" value="<?php echo htmlspecialchars((string)$vo->deposit); ?>" size="40" /></td>
        </tr>
    </table>


    <!-- Popup calendar -->
    <div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>