<div id="header" style="margin-top: 5px; border-top: 12px solid rgb(70,109,159);" width="100%" ></div>
<center>
<div style="margin-top: 20px; ">

<span><h2>Specialist Training and Development Limited</h2> </span>

</div>



<div id="main_image" style="margin-left: 70px; margin-top: 20px;  margin-bottom:3px;">

<img src='/images/stdlimited.jpg' />
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
	
		<table  border="0" cellpadding="2" cellspacing="2">
		<tr>
			<td>Username:</td>
			<td><input style="border: 1px solid rgb(70,109,159)" id="txtUsername" type="text" name="username" value="" /></td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><input style="border: 1px solid rgb(70,109,159)"  type="password" name="password" value="" /></td>
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
