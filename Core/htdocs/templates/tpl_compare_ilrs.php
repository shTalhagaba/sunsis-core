<?php /* @var $vo OrganisationVO */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>ILR Comparison</title>
    <link rel="stylesheet" href="/common.css" type="text/css"/>
    <script src="/js/jquery.min.js" type="text/javascript"></script>
    <script src="/common.js" type="text/javascript"></script>

    <script type="text/javascript">
    function V2(v)
    {
        if(document.getElementById("exclude").checked==true)
            if(v==1)
                window.location.href='do.php?_action=compare_ilrs&exclusion=1&status=1&output=CSV';
            else
                window.location.href='do.php?_action=compare_ilrs&exclusion=1&status=1';
        else
            if(v==1)
                window.location.href='do.php?_action=compare_ilrs&exclusion=1&output=CSV';
            else
                window.location.href='do.php?_action=compare_ilrs&exclusion=1';
    }
    </script>
</head>

<body>
<div class="banner">
    <div class="Title">ILR Comparison</div>
    <div class="ButtonBar">
        <!--<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button> -->
        <input type = checkbox id="exclude" <?php echo $checked; ?> onclick="V2(0)" /> Exclude ILRs ended this year and were continuing last year
    </div>
    <div class="ActionIconBar">
        <button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
        <button onclick="V2(1)" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
    </div>
</div>

<h3> Details </h3>

<?php echo $resultText; ?>

</body>
</html>