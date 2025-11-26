<style type="text/css">

	div.banner .ButtonBar {
		width: 1150px !important;

	}

	label {
		/*display: block;*/
		padding-left: 15px;
		text-indent: -15px;
		font-size: 2;
	}
	input.chckbox {
		width: 13px;
		height: 13px;
		padding: 0;
		margin:0;
		vertical-align: bottom;
		position: relative;
		top: -1px;
		overflow: hidden;
	}
</style>
<div class="banner">
	<div class="Title"><?php echo isset($banner['page_title']) ? $banner['page_title'] : ''; ?></div>
	<div class="ButtonBar">
		<?php echo isset($banner['system_buttons']) ? $banner['system_buttons'] : ''; ?>
	</div>
	<div class="ActionIconBar">
		<?php echo isset($banner['action_buttons']) ? $banner['action_buttons'] : ''; ?>
		<button onclick="window.print()" title="Print-friendly view" id="btn-print" ></button>
		<button onclick="window.location.href='do.php?_action=support_requests&amp;export=csv'" title="Export to .CSV file" id="btn-excel"></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)" id="btn-refresh"></button>
	</div>
	<!--	<div style="position: absolute;top: 58px;left: 0px;">-->
	<!--<div class="ButtonBar">
		<b>Show: </b>
		<label><input class="chckbox" type="checkbox" value="All" />Show All</label>
		&nbsp;&nbsp;
		<input class="chckbox" type="checkbox" value="Closed"  />Closed

	</div>-->

	<div class="ButtonBar"  style="font-size: small">
		<form method="get" name="preferences" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<input type="hidden" name="_action" value="support_requests" />
			<input type="hidden" name="header" value="1" />
			<input type="hidden" name="filter_username_preferences" value="" />
			<?php $checked0 = in_array('All',$options)? 'checked' : '';  ?>
			<label><input class="chckbox"  type="checkbox" name="options[]" <?php echo $checked0; ?> value="All" onclick="submitPreferencesForm();"/>All</label>
			<?php $checked1 = in_array('New',$options)? 'checked' : '';  ?>
			<label><input class="chckbox" type="checkbox" name="options[]" <?php echo $checked1; ?> value="New" onclick="submitPreferencesForm();"/>New Requests</label>
			<?php $checked2 = in_array('Assigned',$options)? 'checked' : '';  ?>
			<label><input class="chckbox" type="checkbox" name="options[]" <?php echo $checked2; ?> value="Assigned" onclick="submitPreferencesForm();"/>Being Looked Into</label>
			<?php $checked4 = in_array('Awaiting Client',$options)? 'checked' : '';  ?>
			<label><input class="chckbox" type="checkbox" name="options[]" <?php echo $checked4; ?> value="Awaiting Client" onclick="submitPreferencesForm();"/>Requiring Feedback</label>
			<?php $checked4 = in_array('Deployment',$options)? 'checked' : '';  ?>
			<label><input class="chckbox" type="checkbox" name="options[]" <?php echo $checked4; ?> value="Deployment" onclick="submitPreferencesForm();"/>Under Consideration</label>
			<?php $checked3 = in_array('Development',$options)? 'checked' : '';  ?>
			<label><input class="chckbox" type="checkbox" name="options[]" <?php echo $checked3; ?> value="Development" onclick="submitPreferencesForm();"/>Being Worked On</label>
			<?php $checked5 = in_array('Chargeable Development',$options)? 'checked' : '';  ?>
			<label><input class="chckbox" type="checkbox" name="options[]" <?php echo $checked5; ?> value="Chargeable Development" onclick="submitPreferencesForm();"/>Chargeable Development</label>
			<?php $checked5 = in_array('Not Viable',$options)? 'checked' : '';  ?>
			<label><input class="chckbox" type="checkbox" name="options[]" <?php echo $checked5; ?> value="Not Viable" onclick="submitPreferencesForm();"/>Not Viable</label>
			<?php $checked6 = in_array('Closed',$options)? 'checked' : '';  ?>
			<label><input class="chckbox" type="checkbox" name="options[]" <?php echo $checked6; ?> value="Closed" onclick="submitPreferencesForm();"/>Finished With</label>
				<!--			<tr><td colspan="2"><input type="submit" value="Go" /></td></tr>-->

		</form>
	</div>
</div>
