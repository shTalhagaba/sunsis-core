<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <title>System error</title>

    <script src="/js/jquery.min.js" type="text/javascript"></script>
    <script src="js/common.js" type="text/javascript"></script>

    <style type="text/css">
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            border: none;
            text-align: center;

            font-family: arial, sans-serif;
        }

        div.box {
            color: #3A4D16;
            /*background-color: #FAFAFA;*/
            background-color: #dfe9cd;
            font-family: sans-serif;
            width: 600px;
            /*border: 3px #D41E48 solid;*/
            border: 3px #608025 solid;
            padding: 10px;
            -moz-border-radius: 12px;
            -webkit-border-radius: 12px;
            border-radius: 12px;
            -moz-box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.5);
            -webkit-box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.5);
            box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.5);
        }

        div.box h4 {
            margin-top: 3px;
        }

        div.message {
            border: 1px solid gray;
            background-color: #f3fedf;
            margin: 20px 50px 20px 50px;
            padding: 5px;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            border-radius: 5px;
            font-size: 10pt;
            text-align: left;
        }

        div.details {
            font-size: 9pt;
            border: 1px solid #678c29;
            background-color: #f3fedf;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            border-radius: 5px;
        }

        td p:first-child {
            margin-top: 0px;
        }
    </style>


</head>

<body>

<table width="100%" border="0" style="height:100%">
    <tr>
        <td valign="middle" align="center">
            <div class="box">
                <h4>Sorry, the server has been unable to complete your request</h4>
                <div class="message"><?php echo nl2br(htmlspecialchars($message)); ?></div>

                <?php if (SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL) { ?>
                    <div class="details" align="left">
                        <p style="text-align:center; background-color:#3A4D16;color:white">The details below are visible
                            to Perspective developers and users from Blythe Valley only</p>
                        <table border="0" cellspacing="4" cellpadding="2">
                            <col width="60"/>
                            <col/>
                            <tr>
                                <td style="font-weight:bold;">Code</td>
                                <td style="word-wrap:break-word;"><?php echo htmlspecialchars($code); ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold;">File</td>
                                <td style="word-wrap:break-word;"><?php echo htmlspecialchars(basename($file)); ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold;">Line</td>
                                <td style="word-wrap:break-word;"><?php echo htmlspecialchars($line); ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold;" valign="top">Trace</td>
                                <td style="word-wrap:break-word;" valign="top"><?php echo $trace; ?></td>
                            </tr>
                            <?php if (isset($extra_info) && $extra_info) { ?>
                                <tr>
                                    <td style="font-weight:bold;" valign="top">Extra Info</td>
                                    <td style="word-wrap:break-word;font-size:8pt;"
                                        valign="top"><?php echo Text::abbreviate(str_replace("\t", "&nbsp;&nbsp;&nbsp;", nl2br(htmlspecialchars($extra_info))), 3072, "..."); ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                <?php } ?>

                <p>
                    <button onclick="history.go(-1);">&lt; Back</button>
                </p>
            </div>
        </td>
    </tr>
</table>

</body>
</html>