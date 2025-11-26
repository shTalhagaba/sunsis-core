<div id="divMessages"><?php if(isset($message)) echo htmlspecialchars((string)$message); ?></div>

<div id="divWarnings"></div>

<div id="main" style='margin-top: 5px; background-color: white; display: block; margin-left: auto; margin-right: auto; width: 960px;'>

<div style="float: left"><img src='/images/logos/imi.jpg' /></div>
<div style="border: none; margin-left: auto; margin-right: auto; clear: left; width: 960px; ">


<div style="margin-top: 10px;" ><img src='/images/logos/header_imi.jpg' /></div>
<table border="0" cellspacing="0" cellpadding="0"  style="width: 800px; ">
<tr>
<td align="center" valign="middle">
	<br />
	<form name="login" action="<?php echo $_SERVER['PHP_SELF'].'?_action=login' ?>" method="post">
	<!-- <input type="hidden" name="_action" value="login" /> -->
	<input type="hidden" name="screen_width" />
	<input type="hidden" name="screen_height" />
	<input type="hidden" name="color_depth" />
	<input type="hidden" name="flash" />
	<input type="hidden" name="destination" value="<?php echo (isset($_REQUEST['destination'])?htmlspecialchars((string)$_REQUEST['destination']):''); ?>" />

	<table class="loginBox" border="0" cellpadding="2" cellspacing="2">
		<tr>
			<td>Username:</td>
			<td><input id="txtUsername" type="text" name="username" value="" /></td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><input type="password" name="password" value="" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="Login" style="width:100%"/></td>
		</tr>
	</table>
	</form>
</td>


</tr>
</table>
</div>

</div>
