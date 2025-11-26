<div id="flash_title" style="margin-left: 70px; margin-top: 20px;  margin-bottom:3px;">
<img src='/images/logos/lsn.jpg' />

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
			<td><input style="border: 1px solid rgb(0,36,91)" id="txtUsername" type="text" name="username" value="" /></td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><input style="border: 1px solid rgb(0,36,91)"  type="password" name="password" value="" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="Login" style="width:100%"/></td>
		</tr>
                <tr>
                	<td colspan="2" style="text-align: right;" >
                       		<a href="<?php echo $_SERVER['PHP_SELF'].'?_action=view_candidate_register' ?>">register as a candidate</a>
                        </td>
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
