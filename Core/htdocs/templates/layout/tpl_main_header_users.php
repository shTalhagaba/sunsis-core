<?php 
$photopath = $_SESSION['user']->getPhotoPath();
if($photopath){
    $photopath = "do.php?_action=display_image&username=".rawurlencode($_SESSION['user']->username);
} else {
    $photopath = "/images/no_photo.png";
}
$notifResult = new stdClass();
$notifResult->unread_notifications = 0;
$notifResult->total_notifications = 0;
$notifResult->notifications = array();
$notifications = array();
$results = DAO::getResultset($link, "SELECT * FROM user_notifications WHERE `user_id` = '{$_SESSION['user']->id}' ORDER BY created DESC ", DAO::FETCH_ASSOC);
foreach($results AS $row)
{
	$notifResult->unread_notifications += $row['checked'] == 0 ? 1 : 0;

	$item = '';
	$item .= $row['checked'] == 0 ? '<li class="bg-gray">' : '<li>';
	$item .= '<a class="clsNotificationsMenuItem" id="' . $row['id'] . '" href="' . $row['link'] . '">' . $row['detail'] . '<br><span class="fa fa-clock-o"></span> ' . Date::to($row['created'], Date::DATETIME) . '</a>';
	$item .= '</li>';
	$notifications[] = $item;
}
$notifResult->total_notifications = count($notifications);
$notifResult->notifications = $notifications;
?>
<header class="main-header">
	<!-- Header Navbar -->
	<nav class="navbar navbar-static-top" role="navigation">

		<!-- Navbar Right Menu -->
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<!-- Notifications: style can be found in dropdown.less -->
				<li class="dropdown notifications-menu" title="Release Notes" onclick="window.location.href='do.php?_action=view_release_notes'">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-bullhorn fa-spin"></i>
						<span class="label label-primary">1</span>
					</a>					
				</li>

				<li class="dropdown notifications-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-bell-o"></i>
						<span class="label label-warning"><?php echo $notifResult->unread_notifications; ?></span>
					</a>
					<ul class="dropdown-menu">
						<?php if($notifResult->total_notifications == 0) { echo '<li class="header">You have no notifications.</li>'; } ?>
						<li>
							<!-- inner menu: contains the actual data -->
							<ul class="menu small">
								<?php
								foreach($notifResult->notifications AS $n)
									echo $n;
								?>
							</ul>
						</li>
						<li class="footer"><a href="do.php?_action=view_your_notifications">View all</a></li>
					</ul>
				</li>
		
				<!-- User Account Menu -->
				<li class="dropdown user user-menu">
					<!-- Menu Toggle Button -->
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<!-- The user image in the navbar-->
						<img src="<?php echo $photopath; ?>" class="user-image" alt="User Image">
						<!-- hidden-xs hides the username on small devices so only the image appears. -->
						<span class="hidden-xs"><?php echo $_SESSION['user']->firstnames; ?></span>
					</a>
					<ul class="dropdown-menu">
						<!-- The user image in the menu -->
						<li class="user-header bg-aqua">
							<img src="<?php echo $photopath; ?>" class="img-circle" alt="User Image">
							<p>
								<?php echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?>
								-
								<?php echo strtoupper(DAO::getSingleValue($link, "SELECT description FROM lookup_user_types WHERE id = '{$_SESSION['user']->type}'")); ?>
								<small><?php echo $_SESSION['user']->org->legal_name; ?></small>
							</p>
						</li>
						<!-- Menu Body -->
						<!-- Menu Footer-->
						<li class="user-footer">
							<div class="pull-left">
								<a href="do.php?_action=read_user&username=<?php echo $_SESSION['user']->username; ?>" class="btn btn-default btn-flat">Profile</a>
							</div>
							<div class="pull-right">
								<a href="" onclick="logout();return false;"
								   class="btn btn-default btn-flat">Logout</a>
							</div>
						</li>
					</ul>
				</li>

				<!-- Control Sidebar Toggle Button -->
				<!--<li>
					<a href="#" data-toggle="control-sidebar"><i class="fa fa-bars"></i></a>
				</li>-->

			</ul>
		</div>

	</nav>

</header>

<script type="text/javascript">
	$('.clsNotificationsMenuItem').click(function(){
		var _link = $(this).children('span#clsNotificationsMenuItemLink').text();
		$.ajax({
			type:'POST',
			url:'do.php?_action=ajax_notifications_menu&subaction=checkNotification&id='+this.id,
			success: function(data, textStatus, xhr) {
				window.location.href=_link;
			},
			error: function(data) {
				window.location.href=_link;
			}
		});
	});

	function logout()
	{
		if(confirm("Logout?"))
		{
			window.onbeforeunload = null;
			window.top.onbeforeunload = null;
			window.top.location.href='/do.php?_action=logout';
		}
	}
</script>