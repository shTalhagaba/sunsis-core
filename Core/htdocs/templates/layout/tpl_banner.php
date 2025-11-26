<div class="banner">
	<div class="Title"><?php echo isset($banner['page_title']) ? $banner['page_title'] : ''; ?></div>
	<div class="ButtonBar">
		<?php echo isset($banner['system_buttons']) ? $banner['system_buttons'] : ''; ?>
	</div>
	<div class="ActionIconBar">
		<?php echo isset($banner['action_buttons']) ? $banner['action_buttons'] : ''; ?>
		<button onclick="window.print()" title="Print-friendly view" id="btn-print" ></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)" id="btn-refresh"></button>	
	</div>
</div>
<!--
<div class="banner">
	<table>
		<tbody>
		<tr>
			<td valign="top" class="left">
				<?php 
					if( isset($banner['page_title']) ) { echo $banner['page_title']; }
					else { echo '&nbsp;'; }
				?>
			</td>
			<td valign="top" align="right" class="right">
				<?php 
					if( isset($banner['system_buttons']) ) { echo $banner['system_buttons']; }
					else { echo '&nbsp;'; }
				?>
			</td>
		</tr>
		<tr class="button_bar" >
			<td valign="bottom" align="left" id="view_action" class="left">
				<?php 
					if( isset($banner['action_buttons']) ) { echo $banner['action_buttons']; }
					else { echo '&nbsp;'; }
				?>
			</td>
			<td valign="bottom" align="right" class="right">
				<?php 
					if( isset($banner['low_system_buttons']) ) { echo $banner['low_system_buttons']; }
				?>
				<button onclick="window.print()" title="Print-friendly view" id="btn-print" ></button>
				<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)" id="btn-refresh" /></button>				
			</td>
		</tr>
		</tbody>
	</table>
</div>
-->