<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Perspective - Sunesis</title>
    <link rel="stylesheet" href="/common.css" type="text/css"/>
    <link rel="stylesheet" href="/print.css" media="print" type="text/css"/>
    <script src="/js/jquery.min.js" type="text/javascript"></script>
    <script src="/common.js" type="text/javascript"></script>
    <script language="JavaScript" src="/scripts/AC_OETags.js" type="text/javascript" ></script>
    <!-- <script language="JavaScript" src="/calendarPopup/CalendarPopup.js" type="text/javascript" ></script> -->

    <script language="javascript" type="text/javascript" >
        function checkLogin()
        {
            var f = document.forms['login'];
            f.submit();
        }

        function disclaimer()
        {
            document.getElementById('disclaimer').style.display = "none";
            document.getElementById('main').style.display = "block";
            document.getElementById('txtUsername').focus();
        }

        function body_onload()
        {
            if(window.self != window.top) {
                window.top.location.href = window.location.href;
            }

            var myForm = document.forms['login'];
            var warnings = document.getElementById('divWarnings');
            var isFirefox = window.navigator.userAgent.indexOf('Firefox') > -1;

            myForm.elements['screen_width'].value = window.screen.width;
            myForm.elements['screen_height'].value = window.screen.height;
            myForm.elements['color_depth'].value = window.screen.colorDepth;
            myForm.elements['flash'].value = getFlashVersion();
        }


        /**
         * Requires the Adobe Flash Detection script
         */
        function getFlashVersion()
        {
            var versionStr = GetSwfVer();

            if (versionStr == -1 )
            {
                versionStr = '';
            }
            else if (versionStr != 0)
            {
                if(isIE && isWin && !isOpera)
                {
                    // Given "WIN 2,0,0,11"
                    tokens = versionStr.split(" ");
                    versionStr = tokens[1].replace(/,/g,'.');
                }
            }

            return versionStr;
        }
    </script>
    <style type="text/css">
        h1 {
            font-family: arial,sans-serif;
            font-size: 18pt;
            color: #395596;
        }

        html, body {
            font-family: arial,sans-serif;
            height:100%;
            margin: 0;
            padding: 0;
            border: none;
            text-align: center;
        }

	.message {
            color: red;
            font-family: arial,sans-serif;
            font-style: italic;
            width: 300px;
        }

        .loginBox {
            -moz-border-radius: 12px;
            -webkit-border-radius: 12px;
            border-radius: 12px;
            -moz-box-shadow: 2px 3px 6px rgba(0,0,0,0.6);
            -webkit-box-shadow: 2px 3px 6px rgba(0,0,0,0.6);
            box-shadow: 2px 3px 6px rgba(0,0,0,0.6);
            border-color:#00A4E4;
            border-width:1px;
            border-style:solid;
            /*margin:20px;*/
            padding:10px;
            background-color:#FAFAFA;
            color: #002D62;
        }

        div.caveat {
            font-family: sans-serif;
            font-size: 8pt;
            color: gray;
            width: 300px;
            margin: 5px;
            text-align: center;
        }

        div.hostname {
            position:absolute;
            float: right;
            top: 10px;
            left: 10px;

            font-family: 'arial black', sans-serif;
            font-size: 20pt;
            color: #EEEEEE;
        }

        #divMessages {
            /*border:1px red solid;*/
            width:50%;
            height:60px;
            overflow: hidden;
            text-align:center;

            font-family: 'arial black',sans-serif;
            font-style:italic;
            color:red;
            font-size:14pt;
            margin: auto;
            z-index: 2;
        }

        #divWarnings {
            position:absolute;
            bottom:10px;
            left:25%;
            /* border:1px silver solid; */
            padding: 5px;
            width:50%;
            text-align:justify;

            font-family: sans-serif;
            color: #444444;
            font-size:10pt;
        }

        #divGetFirefox {
            position:absolute;
            bottom: 10px;
            right: 10px;
        }

        #customerlogo {
            /*
      * #137 - error messaging
      * float: left;
      */
            margin: 10px 0px 0px 10px;
        }


        .maintenance {
            -moz-border-radius-bottomleft:12px;
            -moz-border-radius-bottomright:12px;
            -moz-border-radius-topleft:12px;
            -moz-border-radius-topright:12px;
            background-color:#FAFAFA;
            border:1px solid #FF0000;
            color:#002D62;
            padding:5px;
            text-align:center;
            width:500px;
        }

    </style>

</head>
<body onload="body_onload()">

<?php
$filename = SystemConfig::getEntityValue($link, "logo");
$filename = $filename ? $filename : 'perspective.png';
?>

<div id="disclaimer" style="margin-top: 50px">
    <?php if(in_array(DB_NAME, ["am_ligauk", "am_lead_demo_", "am_lead"])){ ?>
    <table align=center width=500 border=3 cellpadding=10>
        <tr>
            <td>
                <b>Notice</b>
            </td>
        </tr>
        <tr>
            <td style='text-align:center; color:#FF0000'>
                Unfortunately Sunesis will be unavailable for you until further notice. Please contact Perspective on 0121 506 9400 if you would like to discuss this. <br/><br/>Kind regards, <br/><br/>Perspective Support
            </td>
        </tr>
    </table>
    <?php } ?>

    <?php if(!in_array(DB_NAME, ["am_ligauk", "am_lead_demo_", "am_lead"])){ ?>
        <table align="center" width="500" border="3" cellpadding="10" style="background-color: #fff; box-shadow: 2px 3px 6px rgba(0,0,0,0.6);border-color:#00A4E4;padding:10px;border-radius: 15px;">
            <tr>
                <td align="center" style="border-radius: 10px;box-shadow: 2px 3px 6px rgba(0,0,0,0.6);">
                    <b>Agreement </b>
                </td>
            </tr>
            <tr>
                <td align="left" style="border-radius: 8px;">
                    <p>I agree to adhere to the rules and regulations of the General Data Protection Regulations (GDPR) including collecting and processing personal information in accordance with the six principles detailed below:</p>
                    <p>6 Principles of GDPR.Data should be</p>
                    <ol>
                        <li>Processed lawfully, fairly and in a transparent manner</li>
                        <li>Collected for specified, explicit and legitimate purposes</li>
                        <li>Adequate, relevant and limited to what is necessary</li>
                        <li>Accurate and, where necessary, kept up to date</li>
                        <li>Retained only for as long as necessary</li>
                        <li>Processed in an appropriate manner to maintain security</li>
                    </ol>
                    <p>I agree to adhere to the rules and regulations of the Freedom of Information Act 2000 which gives individuals a general right of access to all recorded information held by public authorities, including educational establishments.</p>
                    <p>I agree to promote and adhere to Equal Opportunity and Diversity policies on race, gender, age, disability, religion or belief and sexual orientation within the Learning Environment.</p>
                    <p>To view Perspective (UK) Ltd's Privacy Statement please <a href="do.php?_action=priv_stmt" target="_blank">click here</a></p>
                    <p>Note: The Privacy Statement will be updated regularly.</p>
                </td>
            </tr>
        </table>
        <br/>
        <br/>
    <?php } ?>

    <br/>
    <br/>


    <?php if(!in_array(DB_NAME, ["am_ligauk",  "am_lead_demo_", "am_lead"])){ ?>
        <button type="button" onclick="disclaimer();">&nbsp;&nbsp;&nbsp;I Agree&nbsp;&nbsp;&nbsp;</button>
    <?php } ?>
</div>



<div id="main" style='display: none; margin: auto; width: 960px;'>
    
    <?php if(!in_array(DB_NAME, ["am_ligauk",  "am_lead_demo_", "am_lead"])){ ?>    

    <div id="customerlogo" style='margin: auto; width: 960px;'><img src="/images/logos/<?php echo $filename; ?>" alt="Sunesis <?php echo DB_NAME; ?> Logo" style="box-shadow:2px 3px 6px #ccc;" /></div>

    <div style="margin-bottom: 50px; float: left; clear: left;"></div>

    <div id="divMessages"><?php if(isset($message)) echo htmlspecialchars((string)$message); ?></div>

    <div id="divWarnings"></div>

    <div id="firstform" style="border: none; margin: auto; clear: left; width: 960px; ">

        <table  border="0" cellspacing="0" cellpadding="0"  style="margin-top: auto; width: 960px; ">
            <tr>
                <td align="center" valign="middle">
                    <br />
                    <form name="login" action="<?php echo $_SERVER['PHP_SELF'].'?_action=login' ?>" method="post" autocomplete="off">
                        <!-- <input type="hidden" name="_action" value="login" /> -->
                        <input type="hidden" name="screen_width" />
                        <input type="hidden" name="screen_height" />
                        <input type="hidden" name="color_depth" />
                        <input type="hidden" name="flash" />
                        <input type="hidden" name="destination" value="<?php echo (isset($_REQUEST['destination'])?htmlspecialchars((string)$_REQUEST['destination']):''); ?>" />

                        <table class="loginBox" border="0" cellpadding="2" cellspacing="2">
                            <tr>
                                <td style="color: rgb(102,105,108)" >Username:</td>
                                <td><input id="txtUsername" type="text" name="username" value="" autofocus tabindex="1"/></td>
                            </tr>
                            <tr>
                                <td style="color: rgb(102,105,108)">Password:</td>
                                <td><input type="password" name="password" value="" tabindex="2"/></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><!-- <input onclick = "checkLogin(this);" type="button" value="Login" style="width:100%" tabindex="3"/> -->
                                    <input type="submit" value="Login" style="width:100%" tabindex="3"/>
                                    <div class="g-signin2" data-onsuccess="onSignIn"></div>
                                </td>
                            </tr>
                        </table>
                    </form>
                </td>


            </tr>
        </table>
	<br><br>
	

        <?php if (false) : ?>
	    <br><br>
            <table align="center" width="500" border="3" cellpadding="10" style="background-color: #fff; box-shadow: 2px 3px 6px rgba(0,0,0,0.6);border-color:#00A4E4;padding:10px;border-radius: 15px;">
            <tr>
                <td align="left" style="border-radius: 8px;">
                    <p><strong>Christmas Closure</strong></p>
                    <p>Please note: Perspective's general support will shut down from 5:00PM on Monday 23rd December, reopening again from 9AM on Thursday 2nd January 2025.</p>
                    <p>Support requests may still be raised during the closure period, and non-urgent requests will be processed from January onward.</p>
                    <p>If you have an urgent support request during the closure period, please email <a href="mailto:support@perspective-uk.com">support@perspective-uk.com</a> including the word 'Urgent' in the email title.</p>
                    <p>We would like to take this opportunity to wish you a Merry Christmas and a prosperous New Year from everyone at Perspective (UK).</p>
                </td>
            </tr>
        </table>
            <div style="margin-top: 85px;"> <img  src="/images/logos/SUNlogo1.jpg"/> </div>
        <?php endif; ?>

        <?php
        if(DB_NAME=="am_crackerjack")
            $_t = '1';
        else
            $_t = '4';
        ?>

    </div>
    <p><br><br></p>

    <?php } ?>

</div>

</body>
</html>
