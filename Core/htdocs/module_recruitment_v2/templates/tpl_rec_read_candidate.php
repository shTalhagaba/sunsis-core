<?php /* @var $candidate RecCandidate*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<title>Candidate</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>

	<link type="text/css" rel="stylesheet" href="css/calendar_green.css" />
	<!-- CSS for TabView -->
	<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/skins/sam/tabview.css">
	<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/border_tabs.css">

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>

	<script type="text/javascript" src="/assets/js/jquery/jquery.maskedinput-1.2.2.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker-1.0.0.js"></script>
	<script type="text/javascript" src="/assets/js/js_calendar_addevent.js"></script>

	<!-- Dependency source files -->
	<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/container/container.js"></script>

	<!-- Page-specific script -->
	<script type="text/javascript" src="/yui/2.4.1/build/utilities/utilities.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/element/element-beta.js"></script>

	<script type="text/javascript" src="/yui/2.4.1/build/treeview/treeview.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/animation/animation.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/accordion_menu/accordion-menu-v2.js"></script>

	<script type="text/javascript" src="/yui/2.4.1/build/dragdrop/dragdrop.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/tabview/tabview.js"></script>
	<script>
		var phpCandidateId = <?php echo "'" . $candidate->id . "'";?>;
		var phpCandidateName = '<?php echo $candidate->firstnames; ?>';
		phpCandidateName += ' ';
		phpCandidateName += '<?php echo addslashes((string)$candidate->surname); ?>';
		var phpCandidateEmail = '<?php echo $candidate->email; ?>';
		var phpViewOnly = '<?php echo $view_only; ?>';
	</script>
	<script src="/js/rec_read_candidate.js?n=<?php echo time(); ?>"></script>
	<script type="text/javascript">
		YAHOO.namespace("am.scope");
		function treeInit() {
			myTabs = new YAHOO.widget.TabView("demo");
		}
		YAHOO.util.Event.onDOMReady(treeInit);
	</script>
	<style type="text/css">
		fieldset {
			border: 3px solid #B5B8C8;
			border-radius: 15px;
		}

		legend {
			font-size: 12px;
			color: #15428B;
			font-weight: 900;
		}
		div.panel
		{

			border-width: 1px;
			border-style: solid;
			border-color: #E3E3E3 #BFBFBF #BFBFBF #E3E3E3;
			padding: 8px!important;
			margin-bottom: 1.5em;
			margin-left: 1.5em;
			word-wrap: break-word;
			width: 80%!important;
			/* To enable gradients in IE < 9 */
			zoom: 1;
			-moz-border-radius: 7px;
			-webkit-border-radius: 7px;
			border-radius: 7px;
			-moz-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			-webkit-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			/* http://www.colorzilla.com/gradient-editor/ */
			background: rgb(255,255,255); /* Old browsers */
			/* IE9 SVG, needs conditional override of 'filter' to 'none' */
			background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmZmZmZiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmNmY2ZjYiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
			background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(246,246,246,1) 100%); /* FF3.6+ */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,1)), color-stop(100%,rgba(246,246,246,1))); /* Chrome,Safari4+ */
			background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* Opera 11.10+ */
			background: -ms-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* IE10+ */
			background: linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* W3C */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f6f6f6',GradientType=0 ); /* IE6-8 */
		}
		#list2 ul { font-family:Verdana, Arial, serif; font-size:20px;   }
		#list2 ul li { }
		#list2 ul li p { padding:8px; font-style:normal; font-family:Arial; font-size:13px;  border-left: 1px solid #999; }
		#list2 ul li p em { display:block; }
	</style>
</head>

<body onload='$(".loading-gif").toggle();' class="yui-skin-sam">
<input type="hidden" name="candidate_id" id="candidate_id" value="<?php echo $candidate->id; ?>"/>

<div class="banner">
	<div class="Title">Candidate</div>
	<div class="ButtonBar">
		<?php if ($_SESSION['user']->isAdmin() && !$view_only) { ?>
		<button
			onclick="window.location.replace('do.php?_action=rec_edit_candidate&id=<?php echo $candidate->id; ?>');">
			Edit
		</button>
		<?php } ?>
		<button
			onclick="if(window.name == 'viewUser'){window.close();} window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">
			Close
		</button>
	</div>
	<div class="ActionIconBar">
		<button
			onclick="window.open('do.php?_action=rec_read_candidate&export=pdf&id=<?php echo $candidate->id; ?>', '_blank')"
			title="Export to PDF"><img src="/images/btn-printer.gif" width="16" height="16"
		                               style="vertical-align:text-bottom"/></button>
	</div>
</div>


<?php $_SESSION['bc']->render($link); ?>

<table style="margin-top:10px">


</table>
<div class="loading-gif">
	<img src="/images/progress-animations/loading51.gif" alt="Loading" />
</div>
<div id="demo" class="yui-navset">

	<div align="left" style="font-size: 50px;
padding: 15px;
height: 50px;
text-align: left;
text-shadow: -4px 4px 3px #999, 1px -1px 2px #000;
margin-top: 0;
margin-bottom: 0;
color: #395596;">
		<?php echo htmlspecialchars((string)$candidate->firstnames . ' ' . $candidate->surname); ?>
	</div>
	<?php if($candidate->username != '') {?>
		<p>This candidate has been converted into Sunesis Learner, click <a href="do.php?_action=read_user&username=<?php echo $candidate->username; ?>">here</a> to navigate to learner screen.</p>
	<?php } ?>
	<ul class="yui-nav">
		<li <?php echo $tab1; ?>><a href="#tab1"><em>Information</em></a></li>
		<li <?php echo $tab2; ?>><a href="#tab2"><em>Applications (<?php echo (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate_applications WHERE candidate_id = '{$candidate->id}'"); ?>)</em></a></li>
		<li <?php echo $tab3; ?>><a href="#tab3"><em>CRM Notes (<?php echo (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate_crm_notes WHERE candidate_id = '{$candidate->id}'"); ?>)</em></a></li>
		<li <?php echo $tab4; ?>><a href="#tab4"><em>Emails (<?php echo (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate_emails WHERE candidate_id = '{$candidate->id}'"); ?>)</em></a></li>
		<li <?php echo $tab5; ?>><a href="#tab5"><em>Invites (<?php echo (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM calendar_event WHERE for_whom = '{$candidate->id}'"); ?>)</em></a></li>
		<li <?php echo $tab6; ?>><a href="#tab6"><em>History</em></a></li>
	</ul>

	<div class="yui-content" style='background: white'>
		<div id="tab1">
			<?php
			$cv_file_link = '';
			if (file_exists(DATA_ROOT . "/uploads/" . DB_NAME . "/recruitment/cv_1_" . $candidate->id . ".doc")) {
				$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=cv_1_' . $candidate->id . '.doc">Applicants CV 1</a> (doc)';
			} elseif (file_exists(DATA_ROOT . "/uploads/" . DB_NAME . "/recruitment/cv_1_" . $candidate->id . ".docx")) {
				$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment&f=cv_1_' . $candidate->id . '.docx">Applicants CV 1</a> (docx)';
			}
			elseif (file_exists(DATA_ROOT . "/uploads/" . DB_NAME . "/recruitment/cv_1_" . $candidate->id . ".pdf")) {
				$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=cv_1_' . $candidate->id . '.pdf">Applicants CV 1</a> (pdf)';
			}
			?>
			<table border="0" cellspacing="4" cellpadding="6">
				<tr>
					<td valign="top">
						<fieldset>
							<legend>Details</legend>
							<table border="0" cellspacing="0" cellpadding="6" style="min-width: 600px;">
								<tr>
									<td class="fieldLabel">Firstname(s):</td>
									<td class="fieldValue"><?php echo htmlspecialchars((string)$candidate->firstnames); ?></td>
								</tr>
								<tr>
									<td class="fieldLabel">Surname:</td>
									<td class="fieldValue"><?php echo htmlspecialchars((string)$candidate->surname); ?></td>
								</tr>

								<tr>
									<td class="fieldLabel">Date of birth:</td>
									<td class="fieldValue"><?php
										echo htmlspecialchars(Date::toMedium($candidate->dob));
										if ($candidate->dob) {
											echo '<span style="margin-left:30px;color:gray">(' . Date::dateDiff(date("Y-m-d"), $candidate->dob) . ')</span>';
										}
										?></td>
								</tr>
								<tr>
									<td class="fieldLabel">National Insurance:</td>
									<td class="fieldValue"><?php echo htmlspecialchars((string)$candidate->national_insurance); ?></td>
								</tr>
								<!--<tr>
									<td class="fieldLabel">Gender:</td>
									<td class="fieldValue"><?php /*echo htmlspecialchars(DAO::getSingleValue($link, "SELECT description FROM lookup_gender WHERE id='{$candidate->gender}';")); */?></td>
								</tr>
								<tr>
									<td class="fieldLabel">Ethnicity:</td>
									<td class="fieldValue"><?php /*echo htmlspecialchars(DAO::getSingleValue($link, "SELECT Ethnicity_Desc FROM lis201314.ilr_ethnicity WHERE Ethnicity='{$candidate->ethnicity}';")); */?></td>
								</tr>-->
								<tr>
									<form name="uploadCV1" method="post"
									      action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=rec_save_candidate_file"
									      ENCTYPE="multipart/form-data">
										<input type="hidden" name="_action" value="rec_save_candidate_file"/>
										<input type="hidden" name="control_name" value="candidate_cv_1"/>
										<input type="hidden" name="file_prefix" value="cv_1_"/>
										<input type="hidden" name="candidate_id" value="<?php echo $candidate->id;?>"/>
										<?php if ($cv_file_link != '') { ?>
										<input type="hidden" name="mode" value="update"/>
										<?php } else { ?>
										<input type="hidden" name="mode" value="add"/>
										<?php } ?>
										<td class="fieldLabel" valign="top">Candidate CV:</td>
										<td class="fieldValue">
											<?php if ($cv_file_link != '') echo  $cv_file_link; else echo 'CV Not Provided'; ?>
											<br><br>
															
										</td>
									</form>
								</tr>
								<tr>
									<td class="fieldLabel" valign="top">Address:</td>
									<td class="fieldValue">
										<?php
										echo $candidate->address1 . '<br>';
										echo $candidate->address2 . '<br>';
										echo $candidate->borough . '<br>';
										echo $candidate->county . '<br>';
										echo $candidate->postcode . '<br>';
										?>
									</td>
								</tr>
								<tr>
									<td class="fieldLabel">Telephone:</td>
									<td class="fieldValue"><?php echo $candidate->telephone; ?></td>
								</tr>
								<tr>
									<td class="fieldLabel">Mobile:</td>
									<td class="fieldValue"><?php echo $candidate->mobile; ?></td>
								</tr>
								<tr>
									<td class="fieldLabel">Email:</td>
									<td class="fieldValue"><?php echo $candidate->email; ?></td>
								</tr>
								<tr>
									<td class="fieldLabel">Parent/Guardian Email:</td>
									<td class="fieldValue"><?php echo $candidate->guardian_email; ?></td>
								</tr>
								<tr>
									<td class="fieldLabel">Parent/Guardian Contact:</td>
									<td class="fieldValue"><?php echo $candidate->guardian_contact; ?></td>
								</tr>
							</table>
						</fieldset>
<!--						<fieldset>
							<legend>Study Needs</legend>
							<table cellpadding="4">
								<tr>
									<td class="fieldLabel">LLDD:</td>
									<td style="max-width: 350px;" class="fieldValue"><?php /*echo DAO::getSingleValue($link,"SELECT LLDDInd_Desc FROM lis201213.ilr_llddind WHERE LLDDInd = '{$candidate->lldd}';"); */?></td>
								</tr>
								<tr>
									<td class="fieldLabel">LLDD Categories:</td>
									<td class="fieldValue">
										<?php
/*										$lldd_options = $candidate->getCandidateLLDDOptions($link);
										if(is_array($lldd_options))
										{
											foreach($lldd_options AS $option)
												echo DAO::getSingleValue($link, "SELECT CONCAT(code, ' ', description) FROM central.lookup_lldd_cat WHERE CODE = '{$option}'") . '<br>';
										}

										*/?>
									</td>
								</tr>
							</table>
						</fieldset>
-->					</td>
					<td valign="top">
						<fieldset>
							<legend>Study History</legend>
							<?php echo $this->render_candidate_qualifications($link, $candidate); ?>
						</fieldset>
						<fieldset>
							<legend>Employment History</legend>
							<?php echo $this->render_candidate_employment($link, $candidate); ?>
						</fieldset>
						<fieldset>
							<legend>Availability to work</legend>
							<table class="resultset" cellpadding="6">
								<thead><tr><th>Day</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th><th>Sunday</th></tr></thead>
								<tr>
									<th>Start Time</th>
									<td><?php echo $shift_pattern->mon_start_time; ?></td>
									<td><?php echo $shift_pattern->tue_start_time; ?></td>
									<td><?php echo $shift_pattern->wed_start_time; ?></td>
									<td><?php echo $shift_pattern->thu_start_time; ?></td>
									<td><?php echo $shift_pattern->fri_start_time; ?></td>
									<td><?php echo $shift_pattern->sat_start_time; ?></td>
									<td><?php echo $shift_pattern->sun_start_time; ?></td>
								</tr>
								<tr>
									<th>End Time</th>
									<td><?php echo $shift_pattern->mon_end_time; ?></td>
									<td><?php echo $shift_pattern->tue_end_time; ?></td>
									<td><?php echo $shift_pattern->wed_end_time; ?></td>
									<td><?php echo $shift_pattern->thu_end_time; ?></td>
									<td><?php echo $shift_pattern->fri_end_time; ?></td>
									<td><?php echo $shift_pattern->sat_end_time; ?></td>
									<td><?php echo $shift_pattern->sun_end_time; ?></td>
								</tr>
							</table>
						</fieldset>
					</td>
				</tr>
			</table>
		</div>

		<div id="tab2">
			<h3>Applications</h3>
			<?php echo $this->renderCandidateApplication($link, $candidate); ?>
		</div>

		<div id="tab3">
			<h3>CRM Notes</h3>
			<span class="button" id="btn_new_crm_note" onclick="addNewCRMNote();">Add New</span>
			<p></p>
			<div class="panel" id="div_new_crm_note" style="display: none;">
				<table>
					<tr>
						<td colspan="4" align="left">
							<table>
								<tr><td class="fieldLabel">Application:</td><td><?php echo HTML::select('new_crm_note_application_id', $application_ddl, '', true); ?></td></tr>
								<tr><td class="fieldLabel_compulsory">Date:</td><td><?php echo HTML::datebox('new_crm_note_date', '', true); ?></td></tr>
								<tr><td class="fieldLabel_compulsory">Type of Contact:</td><td><?php echo HTML::select('new_crm_note_type_of_contact', $crm_note_contact_type_ddl, '', false, true); ?></td></tr>
								<tr><td class="fieldLabel_compulsory">Subject:</td><td><input class="compulsory" id="new_crm_note_subject" type="text" value="<?php echo $client_name; ?> Application Update" size="50" /></td></tr>
								<tr><td class="fieldLabel_compulsory">Next Action Date:</td><td><?php echo HTML::datebox('new_crm_note_next_action_date', ''); ?></td></tr>
								<tr><td class="fieldLabel_compulsory" valign="">Actioned:</td><td><?php echo HTML::select('new_crm_note_actioned', $yes_no_options, '', false); ?></td></tr>
								<tr><td class="fieldLabel_compulsory" valign="top">Agreed Action:</td><td><textarea id="new_crm_note_agreed_action" rows="5" cols="50"></textarea></td></tr>
								<tr><td class="fieldLabel_compulsory" valign="top">Other Notes:</td><td><textarea id="new_crm_note_other_notes" rows="5" cols="50"></textarea></td></tr>
								<tr>
									<td colspan="2" align="right">
										<span class="button" onclick="saveNewCRMNote();">Save</span>
										&nbsp; <span class="button" onclick="$('#div_new_crm_note').hide();$('#btn_new_crm_note').show();">Cancel</span>
										<input type="hidden" id="new_crm_note_id" name="new_crm_note_id" value="" />
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			<?php echo $this->renderCRMNotesTab($link, $candidate); ?>
		</div>

		<div id="tab4">
			<h3>Emails sent from Sunesis</h3>
			<span class="button" id="btnCompose" onclick="$('#btnCompose').slideToggle();$('#trEmailComposer').slideToggle();">Compose</span><p></p>
			<div class="panel" id="trEmailComposer" style="display: none;">
				<span class="button" onclick="sendEmail();">Send</span>
				<span class="button" onclick="$('#btnCompose').slideToggle();$('#trEmailComposer').slideToggle();">Cancel</span>
				<table>
					<tr>
						<td valign="top">
							<fieldset>
								<legend>Email Headers</legend>
									<table>
										<tr><td class="fieldLabel">Candidate Name:</td><td class="fieldValue"><?php echo $candidate->firstnames . ' ' . $candidate->surname; ?></td></tr>
										<tr><td class="fieldLabel">Candidate Email:</td><td><input type="text" name="candidateEmail" id="candidateEmail" value="<?php echo $candidate->email; ?>" size="30" /></td></tr>
										<tr><td class="fieldLabel">Your Name:</td><td class="fieldValue"><?php echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?></td></tr>
										<tr><td class="fieldLabel">Your Email:</td><td><input type="text" name="senderEmail" id="senderEmail" value="<?php echo SystemConfig::getEntityValue($link, 'rec_v2_email'); ?>" size="30" /></td></tr>
										<tr><td class="fieldLabel">Subject:</td><td><input type="text" name="emailSubject" id="emailSubject" value="<?php echo $client_name; ?> Application Update" size="30" /></td></tr>
									</table>
							</fieldset>
							<fieldset>
								<legend>Email Templates</legend>
								<table>
									<tr><td class="fieldLabel">Applications:</td><td><?php echo HTML::select('application_id_for_email', $application_ddl, '', true); ?></td></tr>
									<tr><td class="fieldLabel">Templates:</td><td><?php echo HTML::select('email_template_type', $candidate_email_templates, '', true); ?></td></tr>
									<tr><td colspan="2" align="right"><span class="button" onclick="loadEmailTemplate();">Load Email Template</span> </td></tr>
								</table>
							</fieldset>
						</td>
						<td valign="top">
							<fieldset>
								<legend>Message</legend>
								<textarea class="mceEditor" id="email_contents" name="email_contents" style="font-family:sans-serif; font-size:10pt;" rows="35" ></textarea>
							</fieldset>
						</td>
					</tr>
				</table>
			</div>
			<?php echo $this->renderEmailsTab($link, $candidate); ?>
		</div>

		<div id="tab5">
			<h3>Invites</h3>
			<span class="button" id="btnNewInvite" onclick="addNewInvite();">New Invite</span><p></p>
			<div class="panel" id="div_new_invite"  style="display: none;">
				<form id="frmAddInvite" action="do.php?_action=rec_read_candidate">
					<input type="hidden" name="subaction" value="saveInvite" />
					<input type="hidden" name="mode" value="add" />
					<input type="hidden" name="sequence_number" value="" />
					<input type="hidden" name="event_uid" value="" />
					<input type="hidden" name="event_id" value="" />
					<input type="hidden" name="candidate_id" value="<?php echo $candidate->id; ?>" />
					<table class="formtable">
						<tr><td class="fieldLabel_compulsory" width="100">Event Title/Subject:</td><td><?php echo HTML::textbox('title', '', ' class="compulsory width1" ') ?></td></tr>
						<tr>
							<td class="fieldLabel_compulsory">Dates:</td>
							<td>
								<input type="text" name="datefrom" class="compulsory width2" id="datefrom" />
								<input type="text" id="datefromtime" name="datefromtime" class="compulsory width2" /> to <input type="text" id="datetotime" name="datetotime" class="compulsory width2" />
								<input type="text" name="dateto" class="compulsory width2" id="dateto" />
							</td>
						</tr>
						<tr>
							<td class="fieldLabel_compulsory">All day?</td>
							<td><?php echo HTML::radioChoice('allday', array('Yes' => 1, 'No' => 0), 0); ?></td>
						</tr>
						<tr>
							<td class="fieldLabel_compulsory">Applications in CV Sent:</td>
							<td>
								<?php
								$sql = "SELECT candidate_applications.id, CONCAT(vacancy_reference, ' - ', vacancy_title), NULL FROM candidate_applications INNER JOIN vacancies ON vacancy_id = vacancies.id WHERE current_status = '" . RecCandidateApplication::CV_SENT . "' AND candidate_id = '{$candidate->id}';";
								$cv_sent_applications = DAO::getResultset($link, $sql);
								echo HTML::select('cv_sent_application_id', $cv_sent_applications, '', true);
								?>
							</td>
						</tr>
						<tr>
							<td class="fieldLabel">Templates:</td>
							<td><?php echo HTML::select('invite_template_type', $candidate_invite_templates, '', true); ?></td>
						</tr>
						<tr>
							<td class="fieldLabel_compulsory">Location:</td>
							<td><input type="text" name="location" id="location" value="" class="compulsory width1" /></td>
						</tr>
						<tr valign="top">
							<td class="fieldLabel_compulsory">Description:</td>
							<td><textarea id="invite_description" name="description" rows="15" cols="45" class="mceEditor compulsory width1"></textarea></td>
						</tr>
						<tr>
							<td class="fieldLabel_compulsory">Send as email:</td>
							<td class="fieldVale"><?php echo HTML::select('sendInviteAsEmail', $yes_no_options, 'No', false);?></td>
						</tr>
						<tr>
							<td colspan="2" align="right"><span id="spanCreateInvite" class="button" onclick="createNewInvite();"> &nbsp;Create &nbsp;</span> &nbsp; <span class="button" onclick="$('#div_new_invite').hide();$('#btnNewInvite').show();">Cancel</span></td>
						</tr>
					</table>
				</form>
			</div>
			<?php echo $this->renderInvitesTab($link, $candidate); ?>
		</div>

		<div id="tab6">
			<h3>History</h3>
			<div align="left" id="list2">
				<ul>
					<?php echo $this->renderCandidateHistory($link, $candidate); ?>
				</ul>
			</div>
		</div>

	</div>
</div>

<script type="text/javascript" src="/yui/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
	tinymce.init({
		selector : ".mceEditor",
		theme: "modern",
		oninit : "setPlainText",
		menubar : false,
		plugins : "paste"
	});

	function setPlainText() {
		var ed = tinyMCE.get('elm1');

		ed.pasteAsPlainText = true;

		//adding handlers crossbrowser
		if (tinymce.isOpera || /Firefox\/2/.test(navigator.userAgent)) {
			ed.onKeyDown.add(function (ed, e) {
				if (((tinymce.isMac ? e.metaKey : e.ctrlKey) && e.keyCode == 86) || (e.shiftKey && e.keyCode == 45))
					ed.pasteAsPlainText = true;
			});
		} else {
			ed.onPaste.addToTop(function (ed, e) {
				ed.pasteAsPlainText = true;
			});
		}
	}
</script>

<?php if(!$_SESSION['user']->isAdmin()){
$logo = 'SUNlogo.jpg';
	$c_name = 'Perspective';
	if(DB_NAME == "am_superdrug")
	{
		$logo = 'superdrug.bmp';
		$c_name = 'Superdrug';
	}
?>
<div id="footer">
	<span style="float: left; text-align: left;" ><?php echo date('D, d M Y H:i:s T'); ?></span>
	<span style="float: right; text-align: right;">Powered by Sunesis &nbsp;|&nbsp;&copy; <?php echo date('Y'); ?> Perspective Ltd</span>
	<span style="float: right"><img src="/images/logos/<?php echo $logo; ?>" alt="<?php echo $c_name; ?>" style="box-shadow:2px 3px 6px #ccc; border-radius: 6px;" />
</div>
<?php } ?>

</body>
</html>

