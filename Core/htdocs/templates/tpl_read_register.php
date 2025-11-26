<?php /* @var $reg Register */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Register</title>
	<link rel="stylesheet" href="/common.css" type="text/css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>


	<script language="JavaScript">
		function toggleNextRow(cell, event) {
			var rowNext = cell.parentNode.nextSibling;

			showHideBlock(rowNext);

			// Stop the event from bubbling (or the training record will load)
			if (event.stopPropagation) {
				event.stopPropagation(); // DOM 2
			} else {
				event.cancelBubble = true; // IE
			}
		}


		function deleteLessonNote(id) {
			if (window.confirm("Permanently delete this note?")) {
				window.location.href = 'do.php?_action=delete_lesson_note&id=' + id;
			}
		}

		function deleteRegisterEntryNote(id) {
			if (window.confirm("Permanently delete this note?")) {
				window.location.href = 'do.php?_action=delete_register_entry_note&id=' + id;
			}
		}

		function editRegisterEntryNote(id) {
			window.location.replace('do.php?_action=edit_register_entry_note&id=' + id);
		}

		function editLessonNote(id) {
			window.location.replace('do.php?_action=edit_lesson_note&id=' + id);
		}

		function public_onclick(element) {
			var grid = document.getElementById('grid_readers');

			if (element.value == 1) {
				grid.clear();
				grid.disable();
				grid.style.color = 'gray';
			} else {
				grid.reset();
				grid.setValues("<?php // echo $_SESSION['org']->id; 
								?>");
				grid.enable();
				grid.style.color = 'black';
			}
		}

		/*
  function readers_onclick(element)
  {
	  var public_0 = document.getElementById("public_0");
	  var public_1 = document.getElementById("public_1");
	  var grid = document.getElementById('grid_readers');

	  if(grid.getValues().length == 0)
	  {
		  public_0.checked = true;
		  grid.clear();
		  grid.disable();
	  }
  }
  */

		function body_onload() {
			var public_0 = document.getElementById("public_0");
			var public_1 = document.getElementById("public_1");
			var grid = document.getElementById('grid_readers');

			var count_readers = <?php echo count($readers_preselect); ?>;
			if (count_readers == 0) {
				grid.clear();
				grid.disable();
				grid.style.color = 'gray';
			} else {
				grid.reset();
				grid.setValues("<?php // echo $_SESSION['org']->id; 
								?>");
				grid.enable();
				grid.style.color = 'black';
			}
		}

		function editRegister() {
			var isFutureRegister = <?php echo $future_register; ?>;
			if (isFutureRegister) {
				alert('You cannot mark a future register');
				return;
			} else {
				window.location.replace('do.php?lesson_id=<?php echo $reg->lesson->id; ?>&_action=edit_register&attendance_module=<?php echo $attendance_module; ?>&attendance_module_id=<?php echo $attendance_module_id; ?>');
			}
		}

		function sendEmailForRefComm(pot_id, lesson_id)
		{
			var client = ajaxRequest('do.php?_action=ajax_email_actions&subaction=sendEmailForRefComm&pot_id='+pot_id+'&lesson_id='+lesson_id);
			if(client)
			{
				alert(client.responseText);
				window.location.reload();
			}
		}

	</script>
</head>



<body onload="body_onload()">
	<div class="banner">
		<div class="Title">Register</div>
		<div class="ButtonBar">
			<button class="toolbarbutton" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';">Close</button>
			<?php  //if( ($_SESSION['org']->org_type_id != ORG_SCHOOL)
			// || (($_SESSION['org']->org_type_id == ORG_SCHOOL) && ($reg->lesson->registered_lessons == 1))) { 
			?>
			<button onclick="editRegister();">Edit</button>
			<!--<button	onclick="window.location.replace('do.php?lesson_id=<?php /*echo $reg->lesson->id; */ ?>&_action=email_absence_notification');">Email Notification</button>-->
			<?php //} 
			?>
		</div>
		<div class="ActionIconBar">
			<!--		<button onclick="window.location.href='do.php?_action=read_register&export=pdf&lesson_id=--><?php //echo $lesson_id; 
																													?><!--'" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>-->
			<button onclick="window.open('do.php?_action=read_register&export=pdf&lesson_id=<?php echo $lesson_id; ?>', '_blank')" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
			<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		</div>
	</div>

	<?php $_SESSION['bc']->render($link); ?>

	<h3>Lesson</h3>
	<table border="0" style="margin-left:10px" cellspacing="4" cellpadding="4" width="590">
		<col width="110" />
		<tr>
			<td class="fieldLabel">Title:</td>
			<td class="fieldValue"><?php echo htmlspecialchars((string)$reg->lesson->lesson_title); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">Date &amp; Time:</td>
			<td class="fieldValue"><?php echo htmlspecialchars(Date::toLong($reg->lesson->date) . ' (' . $reg->lesson->start_time . ' - ' . $reg->lesson->end_time . ')'); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">Provider:</td>
			<!--		<td class="fieldValue">--><?php //echo htmlspecialchars((string)$reg->provider->legal_name.' ('.$reg->location->full_name.')'); 
													?><!--</td>-->
			<td class="fieldValue"><?php echo htmlspecialchars((string)$reg->provider->legal_name); ?></td>
		</tr>
		<?php if ($attendance_module) { ?>
			<tr>
				<td class="fieldLabel" valign="top">Module:</td>
				<td class="fieldValue"><?php echo htmlspecialchars((string)$reg->attendance_module->module_title); ?></td>
			</tr>
		<?php } else { ?>
			<tr>
				<td class="fieldLabel" valign="top">Course:</td>
				<td class="fieldValue"><?php echo htmlspecialchars((string)$reg->course->title . ' (' . Date::toShort($reg->course->course_start_date) . ')'); ?></td>
			</tr>
		<?php } ?>
		<tr>
			<td class="fieldLabel">Group:</td>
			<td class="fieldValue"><?php echo htmlspecialchars((string)$reg->group->title); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">Qualification:</td>
			<td class="fieldValue"><?php echo htmlspecialchars((string)$reg->lesson->qualification); ?></td>
		</tr>
		<?php if (DB_NAME != "am_reed_demo" && DB_NAME != 'am_reed' && isset($reg->tutor)) { ?>
			<tr>
				<td class="fieldLabel">FS Tutor:</td>
				<td class="fieldValue"><a href="mailto:<?php echo $reg->tutor->email; ?>"><?php echo htmlspecialchars((string)$reg->tutor->firstnames . ' ' . $reg->tutor->surname); ?></a>
					(<?php echo htmlspecialchars((string)$reg->tutor->telephone) ?>)</td>
			</tr>
		<?php } ?>
		<?php if (DB_NAME == "am_reed" || DB_NAME == "am_reed_demo") { ?>
			<tr>
				<td class="fieldLabel">Module:</td>
				<td class="fieldValue"><?php
										if ($reg->lesson->module != '')
											echo htmlspecialchars((string) DAO::getSingleValue($link, "select title from modules where id = {$reg->lesson->module}"));
										else
											echo '&nbsp;'; ?>
				</td>
			</tr>
			<tr>
				<td class="fieldLabel">GLH in Lesson:</td>
				<td class="fieldValue"><?php
										if ($reg->lesson->module != '')
											echo htmlspecialchars((string) DAO::getSingleValue($link, "select learning_hours from modules where id = {$reg->lesson->module}"));
										else
											echo '&nbsp;'; ?>
				</td>
			</tr>
		<?php } ?>
	</table>

	<h3>Learner Attendance</h3>
	<table class="resultset" border="0" style="margin-left:10px;" cellspacing="0" cellpadding="6" width="580">
		<!--  <col width="32"/><col width="44"/><col/><col/><col width="44"/><col/> -->
		<tr>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th colspan="2">Learner</th>
			<th>Organisation</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<?php echo DB_NAME == "am_demo" ? '<th>Reflective Comments</th>' : ''; ?>
		</tr>

		<?php
		$rowNum = 0;
		foreach ($reg as $entry) { /* @var $e RegisterEntry */
			$rowNum++;
			$data_present = !(is_null($entry->entry) || ($entry->entry == 0));

			echo HTML::viewrow_opening_tag("do.php?_action=read_training_record&id={$entry->pot_id}");

			if ($entry->hasNotes()) {
				echo '<td onclick="toggleNextRow(this, arguments.length > 0 ? arguments[0] : window.event);" style="cursor:pointer" title="click to read notes" align="center"><img src="/images/text-left.png" border="0" /></td>';
			} else {
				echo '<td align="center">&nbsp;</td>';
			}

			$icon_style = $data_present && $entry->within_pot_dates ? 'opacity:1.0' : 'opacity:0.3';

			// #170 - relmes - use learner portrait if available
			echo "<td align=\"center\"><a href=\"do.php?_action=read_training_record&id={$entry->pot_id}\">";
			echo User::getUserThumbnail($entry->student_username, $entry->student_gender, $icon_style);
			if (User::getSpecificUserMetaData($link, $entry->student_username, "Report to reception") == "yes") {
				echo '<br/>report to reception';
			}
			echo "</td>";
			// #170 - relmes - use learner portrait if available

			// Remaining cells
			if ($entry->late_starter) {
				$text_style = 'color:blue';
			} elseif ($entry->pot_closed) {
				$text_style = 'color: silver; text-decoration: line-through';
			} else {
				$text_style = '';
			}
			echo "<td style=\"$text_style; font-style: italic; text-transform: uppercase\" >" . HTML::cell($entry->student_surname) . '</td>';
			echo "<td style=\"$text_style\" >" . HTML::cell($entry->student_firstnames) . '</td>';
			echo "<td style=\"$text_style\" >" . HTML::cell($entry->school_short_name) . '</td>';

			echo '<td align="center">';
			switch ($entry->entry) {
				case 1:
					echo '<img src="/images/register/reg-tick.png" width="32" height="32" />';
					break;

				case 2:
					echo '<img src="/images/register/reg-late.png" width="32" height="32" />';
					break;

				case 3:
					echo '<img src="/images/register/reg-aa.png" width="32" height="32" />';
					break;

				case 4:
					echo '<img src="/images/register/reg-question.png" width="32" height="32" />';
					break;

				case 5:
					echo '<img src="/images/register/reg-cross.png" width="32" height="32" />';
					break;

				case 6:
					echo '<img src="/images/register/reg-uniform.png" width="32" height="32" />';
					break;

				case 7:
					echo '<img src="/images/register/reg-discipline.png" width="32" height="32" />';
					break;

				case 8:
					echo '<img src="/images/register/reg-na.png" width="32" height="32" />';
					break;

				case 9:
					echo '<img src="/images/register/reg-very-late.png" width="32" height="32" />';
					break;

				default:
					echo '&nbsp;';
					break;
			}

			echo '</td>';
			echo "<td style=\"font-style:italic;\">" . HTML::cell($entry->entry_description) . "</td>";
			/*echo "<td>&nbsp;</td>";*/
			if( DB_NAME == "am_demo" )
			{
				$reflective_comments_learner = DAO::getSingleValue($link, "SELECT reflective_comments_learner FROM register_entries WHERE pot_id = '{$entry->pot_id}' AND lessons_id = '{$lesson_id}'");
				echo '<td>';
				echo HTML::cell($reflective_comments_learner) . '<br>';
				if( trim((string) $reflective_comments_learner) == '' && $entry->id != '' )
				{
					$homeEmail = DAO::getSingleValue($link, "SELECT home_email FROM tr WHERE tr.id = '{$entry->pot_id}'");
					if($homeEmail != '')
					{
						echo '<input type="button" onclick="sendEmailForRefComm(' . $entry->pot_id . ', ' . $lesson_id . ')" value="Send Email to Learner [' . $homeEmail . ']" />';
					}
				}
				echo '</td>';
			}

			echo '</tr>';

			if ($entry->hasNotes()) {
				echo '<tr style="display:none">';
				echo '<td colspan="7"><div style="width:550px">';

				foreach ($entry as $note) /* @var $note RegisterEntryNote */ {
					echo "<div class=\"note\">";

					/*if( (($_SESSION['role'] == 'admin') || ($_SESSION['username'] == $note->username))
									&& $note->is_audit_note == '0' ) */ {
						echo <<<HEREDOC
<div class="header">
<table width="100%" style="border-style:none"><tr><td align="left" style="border-style:none"></td>
<td align="right" style="border-style:none">
<span class="button" onclick="editRegisterEntryNote({$note->id})">Edit</span>
<span class="button" onclick="deleteRegisterEntryNote({$note->id})">Delete</span></td>
</td></tr></table></div>
HEREDOC;
					}

					if ($note->email != '') {
						echo "<div class=\"author\"><a href=\"mailto:{$note->email}\">{$note->firstnames} {$note->surname}</a> @ {$note->organisation_name}";
					} else {
						echo "<div class=\"author\">{$note->firstnames} {$note->surname} @ {$note->organisation_name}";
					}
					echo ' (' . date('D, d M Y H:i:s T', strtotime($note->modified)) . ')</div>';
					echo HTML::nl2p(htmlspecialchars((string)$note->note)) . '</div>';					
				}

				echo '</div></td></tr>';
			}
		}

		echo '</table>';
		?>
		<?php if (SystemConfig::getEntityValue($link, 'attendance_module_v2') && count($extra_attendees_entries) > 0) { ?>
			<h3>Additional Attendees</h3>

			<table class="resultset" border="0" style="margin-left:10px;" cellspacing="0" cellpadding="6" width="580">
				<tr>
					<th>&nbsp;</th>
					<th colspan="2">Attendee</th>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					
				</tr>

				<?php
				$rowNum1 = 0;
				$extra_entry = null;
				foreach ($extra_attendees_entries as $attendee_entry) {
					$rowNum1++;
					$extra_entry = new RegisterExtraAttendeeEntry();
					$extra_entry->populate($attendee_entry);


					echo '<tr height="32">';


					// Row number
					echo '<td style="color:#AAAAAA" align="right">' . $rowNum1 . '</td>';



					$text_style = '';

					echo "<td style=\"$text_style; font-style: italic; text-transform: uppercase\" >" . HTML::cell($extra_entry->student_surname) . '</td>';
					echo "<td style=\"$text_style\" >" . HTML::cell($extra_entry->student_firstnames) . '</td>';


					echo '<td align="center">';
					switch ($extra_entry->entry) {
						case 1:
							echo '<img src="/images/register/reg-tick.png" width="32" height="32" />';
							break;

						case 2:
							echo '<img src="/images/register/reg-late.png" width="32" height="32" />';
							break;

						case 3:
							echo '<img src="/images/register/reg-aa.png" width="32" height="32" />';
							break;

						case 4:
							echo '<img src="/images/register/reg-question.png" width="32" height="32" />';
							break;

						case 5:
							echo '<img src="/images/register/reg-cross.png" width="32" height="32" />';
							break;

						case 6:
							echo '<img src="/images/register/reg-uniform.png" width="32" height="32" />';
							break;

						case 7:
							echo '<img src="/images/register/reg-discipline.png" width="32" height="32" />';
							break;

						case 8:
							echo '<img src="/images/register/reg-na.png" width="32" height="32" />';
							break;

						case 9:
							echo '<img src="/images/register/reg-very-late.png" width="32" height="32" />';
							break;

						default:
							echo '&nbsp;';
							break;
					}

					echo '</td>';
					echo "<td style=\"font-style:italic;\">" . HTML::cell($extra_entry->entry_description) . "</td>";


					echo '</tr>';
				}
				?>
			</table>
		<?php } ?>

		<h3>Attendance Summary</h3>
		<p class="sectionDescription">These
			statistics below are dynamic and reflect the position <em>now</em>, not
			the position when the register was completed.</p>
		<table class="resultset" border="0" cellspacing="0" cellpadding="6" style="margin-left:10px">
			<?php
			echo '<tr>';
			echo '<th>&nbsp;</th>';
			AttendanceHelper::echoHeaderCells();
			echo '</tr>';
			echo '<tr>';
			echo '<td style="font-weight:bold">This Lesson</td>';
			AttendanceHelper::echoDataCells($reg->lesson);
			echo '</tr>';
			echo '<tr>';
			echo '<td style="font-weight:bold">This Group</td>';
			AttendanceHelper::echoDataCells($reg->group);
			echo '</tr>';
			echo '<tr>';
			if (!$attendance_module) {
				echo '<td style="font-weight:bold">This Course</td>';
				AttendanceHelper::echoDataCells($reg->course);
			}
			/*	else
	{
		echo '<td style="font-weight:bold">This Module</td>';
		AttendanceHelper::echoDataCells($reg->attendance_module);
	}*/
			echo '</tr>';
			?>
		</table>

		<h3>Lesson Administration Notes</h3>
		<p class="sectionDescription">Please do <b>not</b> record comments pertaining to <b>learner attendance</b> or <b>progress</b>
			here; lesson administration notes will not be included in the learner's training record.</p>
		<div style="width:600px">
			<?php
			$this->renderRegisterNotes($link, $reg);
			?>


			<form name="newNote" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=add_lesson_note" method="post" enctype="multipart/form-data">
				<input type="hidden" name="lessons_id" value="<?php echo $reg->lesson->id ?>" />
				<input type="hidden" name="_action" value="add_lesson_note" />

				<div class="note" id="newNote">
					<div class="header"><b>Subject:</b> <input class="subject" type="text" name="subject" size="50" maxlength="50" /></div>
					<p><textarea class="content" name="note" style="width:99%" rows="5"></textarea></p>

					<fieldset id="readership_fieldset" style="margin-top:10px;">
						<legend>Readership: <input type="radio" id="public_1" name="public" value="1" onclick="public_onclick(this);" <?php if (count($readers_preselect) == 0) {
																																			echo 'checked="checked"';
																																		} ?> />Public
							<input style="margin-left: 10px" type="radio" id="public_0" name="public" value="0" onclick="public_onclick(this);" <?php if (count($readers_preselect) > 0) {
																																					echo 'checked="checked"';
																																				} ?> />Private (selected organisations only)
						</legend>
						<?php echo HTML::voltCheckboxGrid('readers', $readers_dropdown, $readers_preselect, 5); ?>
					</fieldset>

					<table border="0" width="100%" style="margin-top: 10px;">
						<tr>
							<td align="left" style="font-size:8pt; color:gray; text-align:justify;" width="70%">This is an official document and access to its
								contents may be requested under the Data Protection Act. Please fashion your
								notes accordingly, and conduct private discussions by other means.</td>
							<td align="right" width="30%"><input type="submit" value="Add Note" /></td>
						</tr>
					</table>
				</div>
			</form>

		</div>

</body>

</html>