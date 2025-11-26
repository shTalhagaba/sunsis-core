<header class="main-header">
	<!-- Header Navbar -->
	<nav class="navbar navbar-static-top" role="navigation">

		<!-- Navbar Right Menu -->
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<!-- Messages: style can be found in dropdown.less-->
				<li class="dropdown messages-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-envelope-o"></i>
						<span class="label label-success">0</span>
					</a>
					<ul class="dropdown-menu">
						<li class="header">You have 0 messages</li>
						<li>
							<!-- inner menu: contains the actual data -->
							<ul class="menu">

								<!-- end message -->

							</ul>
						</li>
						<li class="footer"><a href="#">See All Messages</a></li>
					</ul>
				</li>
				<!-- Notifications: style can be found in dropdown.less -->
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
				<!-- Tasks: style can be found in dropdown.less -->
				<li class="dropdown tasks-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-flag-o"></i>
						<span class="label label-danger"><?php echo isset($events->actions)?$events->actions:'0'; ?></span>
					</a>
					<ul class="dropdown-menu">
						<li class="header">Upcoming events</li>
						<li>
							<!-- inner menu: contains the actual data -->
							<ul class="menu">
								<?php if(isset($events->actions) && count($events->actions) == 0)
								{
									echo '<li><a href="#"><h3>You have no upcoming events in next 15 days</h3></a></li>';
								}
								else
								{
									if(isset($events->next_review_in) && $events->next_review_in != '')
									{
										echo '<li><a href="#"><h3>' . $events->next_review_in . '</h3></a></li>';
									}
									if(isset($events->next_appointment_in) && $events->next_appointment_in != '')
									{
										echo '<li><a href="#"><h3>' . $events->next_appointment_in . '</h3></a></li>';
									}
									if(isset($events->next_crm_action_in) && $events->next_crm_action_in != '')
									{
										echo '<li><a href="#"><h3>' . $events->next_crm_action_in . '</h3></a></li>';
									}
								}
								?>
								<!-- end task item -->
							</ul>
						</li>
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
						<li class="user-header">
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
			async: false,
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
			localStorage.clear();
			window.onbeforeunload = null;
			window.top.onbeforeunload = null;
			window.top.location.href='/do.php?_action=logout';
		}
	}
</script>