
<center>
<div style="margin-top: 20px; ">

<img src='/images/rtn_logo.gif' style="box-shadow:2px 3px 6px #ccc;" />

</div>



<div id="flash_title" style="margin-left: 70px; margin-top: 20px;  margin-bottom:3px;">
						<object height="159" width="593" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0">
  							<param name="movie" value="/images/rtn_b_rps_5.swf" />
  							<param name="quality" value="high" />
  							<param name="wmode" value="opaque" />
  							<embed height="159" width="593" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" wmode="opaque" src="/images/rtn_b_rps_5.swf" quality="high"></embed>
						</object>
</div>
</center>
<div style="clear: both"></div>						

<div id="main" style='margin-top: 5px; display: block; margin-left: auto; margin-right: auto; width: 960px;'>
	<form name="login" action="<?php echo $_SERVER['PHP_SELF'].'?_action=login' ?>" method="post">
	<input type="hidden" name="screen_width" />
	<input type="hidden" name="screen_height" />
	<input type="hidden" name="color_depth" />
	<input type="hidden" name="flash" />
	<input type="hidden" name="destination" value="<?php echo (isset($_REQUEST['destination'])?htmlspecialchars((string)$_REQUEST['destination']):''); ?>" />

	
	<div style="margin-top: 40px" >
	<center>
	
		<table class="loginBox" border="0" cellpadding="2" cellspacing="2">
		<tr>
			<td>Username:</td>
			<td><input style="border: 1px solid rgb(206,17,38)" id="txtUsername" type="text" name="username" value="" /></td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><input style="border: 1px solid rgb(206,17,38)"  type="password" name="password" value="" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="Login" style="width:100%"/></td>
		</tr>
	</table>
	
	</center>
	</div>
	
	</form>
	<div id="divMessages"><?php if(isset($message)) echo htmlspecialchars((string)$message); ?></div>	
	<div id="divWarnings"></div>

	<div style="clear: both; ">
	<div style="margin-left: 70px">
	</div>
	
	</div>

	
</div>
