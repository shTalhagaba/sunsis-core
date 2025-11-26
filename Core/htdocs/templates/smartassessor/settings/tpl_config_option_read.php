<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Smart Assessor: Configure Options</title>
        <link rel="stylesheet" href="/common.css" type="text/css"/>
        <link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.17.custom.css" type="text/css"/>
        <link rel="stylesheet" type="text/css" media="print" href="/print.css" />
        <script src="/js/jquery.min.js" type="text/javascript"></script>
        <script src="/common.js" type="text/javascript"></script>
        <script src="/jquery-ui/js/jquery-ui-1.8.17.custom.min.js" type="text/javascript"></script>

        <script type="text/javascript">
            $(function(){
                $('#btnEdit').click(function(e){
                    window.location.replace("do.php?_action=sa_config_option&subaction=edit");
                });

			
            });

        </script>

        <style type="text/css">

        </style>

    </head>

    <body>
        <div class="banner">
            <div class="Title">Smart Assessor: Configure Options</div>
            <div class="ButtonBar">
                <button id="btnEdit">Edit</button>

            </div>
            <div class="ActionIconBar">
                <button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
                <button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
            </div>
        </div>

        <h3 class="introduction">Instructions</h3>
        <p class="introduction">This page provides to disable duplicate functions within Sunesis.</p>

        <h3>Configure Options Parameters</h3>
        <table cellspacing="4" cellpadding="4" style="margin-left:10px; width:590px">
            <col width="100"/>
            <tr>
                <td class="fieldLabel">Employers</td>
                <td><?php echo (htmlspecialchars(HTML::yesNoUnknown($employers, 'Enabled', 'Disabled'))); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">Learners</td>
                <td><?php echo (htmlspecialchars(HTML::yesNoUnknown($learners, 'Enabled', 'Disabled'))); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">Assessors</td>
                <td><?php echo (htmlspecialchars(HTML::yesNoUnknown($assessors, 'Enabled', 'Disabled'))); ?></td>
            </tr>
             <tr>
                <td class="fieldLabel">Review</td>
                <td><?php echo (htmlspecialchars(HTML::yesNoUnknown($review, 'Enabled', 'Disabled'))); ?></td>
            </tr>

        </table>


    </body>

</html>