<?php /* @var $vo CRMNote */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>CRM Note</title>
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
    </Script>
</head>
<body id="candidates">
<div id="maincontent">
<div id="col2" class="column">
    <form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="_action" value="letter" />
        <table border="0" cellspacing="0" style="margin-left:10px">
            <tr>
                <td class="fieldLabel_compulsory">Name:</td>
                <td><input class="compulsory" type="text" name="name" id="name" size="40" /></td>
            </tr>
            <tr>
                <td class="fieldLabel_compulsory">Number:</td>
                <td><input class="compulsory" type="text" name="number" id="number" size="40" /></td>
            </tr>
            <tr>
                <td class="fieldLabel_compulsory">Letter:</td>
                <td><input class="compulsory" type="text" name="letter" id="letter" size="3" /></td>
            </tr>
            <tr><td><button onclick="save();">Letter</button>
            </td></tr>
        </table>
    </form>
</div>
</div>
</body>
</html>