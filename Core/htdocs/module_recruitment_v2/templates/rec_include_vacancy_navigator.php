<!-- RecVacancy navigation -->
<?php /* @var $vacancy RecVacancy*/ ?>
<?php
$disabled = ' disabled="disabled" ';
if($_SESSION['user']->isAdmin())
	$disabled = '';
?>
<?php if($_SESSION['user']->isAdmin()){?>
<div align="center" style="margin-bottom:30px;">
<?php } else { ?>
	<div align="left" style="margin-bottom:30px;">
<?php } ?>
	<table style="border:1px solid silver;background-color:#EEEEEE;padding:2px; border-radius: 15px;" cellspacing="3">
		<tr>
			<td class="fieldLabel" align="left">Title:</td><td><?php echo htmlspecialchars((string)$vacancy->vacancy_title); ?></td>
			<td width="30">&nbsp;</td>
			<td class="fieldLabel" align="left">Employer:</td><td><?php echo htmlspecialchars((string)$vacancy->getEmployerName($link)); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel" align="left">Possible Start Date:</td><td><?php echo htmlspecialchars(Date::toShort($vacancy->possible_start_date)); ?></td>
			<td width="30">&nbsp;</td>
			<td class="fieldLabel" align="left">Closing Date:</td><td><?php echo htmlspecialchars(Date::toShort($vacancy->closing_date)); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel" align="left">Interview Date From:</td><td><?php echo htmlspecialchars(Date::toShort($vacancy->interview_from_date)); ?></td>
			<td width="30">&nbsp;</td>
			<td class="fieldLabel" align="left">Expected Duration:</td><td><?php echo htmlspecialchars((string)$vacancy->expected_duration); ?></td>
		</tr>
		<?php if($_SESSION['user']->isAdmin()){?>
		<tr>
			<td colspan="5" align="center">
				<button id="btnMatching" <?php echo $disabled; ?> type="button" onclick="window.location.href='do.php?_action=rec_view_vacancy&id=<?php echo $vacancy->id; ?>';">Matching</button>
				<button id="btnNotScreened" <?php echo $disabled; ?> type="button" onclick="window.location.href='do.php?_action=rec_view_vacancy_applications&id=<?php echo $vacancy->id; ?>&status=<?php echo RecCandidateApplication::CREATED; ?>'">Not Screened (<?php echo $vacancy->getNumberOfNotScreenedApplications($link); ?>)</button>
				<button id="btnScreened" <?php echo $disabled; ?> type="button" onclick="window.location.href='do.php?_action=rec_view_vacancy_applications&id=<?php echo $vacancy->id; ?>&status=<?php echo RecCandidateApplication::SCREENED; ?>'">Screened (<?php echo $vacancy->getNumberOfScreenedApplications($link); ?>)</button>
				<button id="btnTelephonicInterviewed" <?php echo $disabled; ?> type="button" onclick="window.location.href='do.php?_action=rec_view_vacancy_applications&id=<?php echo $vacancy->id; ?>&status=<?php echo RecCandidateApplication::TELEPHONE_INTERVIEWED; ?>'">Telephone Interviewed (<?php echo $vacancy->getNumberOfTelephonicInterviewedApplications($link); ?>)</button>
				<button id="btnCVSent" type="button" onclick="window.location.href='do.php?_action=rec_view_vacancy_applications&id=<?php echo $vacancy->id; ?>&status=<?php echo RecCandidateApplication::CV_SENT; ?>'">CV Sent (<?php echo $vacancy->getNumberOfCVSentApplications($link); ?>)</button>
				<button id="btnInterviewSuccessful" <?php echo $disabled; ?> type="button" onclick="window.location.href='do.php?_action=rec_view_vacancy_applications&id=<?php echo $vacancy->id; ?>&status=<?php echo RecCandidateApplication::INTERVIEW_SUCCESSFUL; ?>'">Interview Successful (<?php echo $vacancy->getNumberOfSuccessfulApplications($link); ?>)</button>
				<button id="btnInterviewUnsuccessful" <?php echo $disabled; ?> type="button" onclick="window.location.href='do.php?_action=rec_view_vacancy_applications&id=<?php echo $vacancy->id; ?>&status=<?php echo RecCandidateApplication::INTERVIEW_UNSUCCESSFUL; ?>'">Interview Unsuccessful (<?php echo $vacancy->getNumberOfUnsuccessfulApplications($link); ?>)</button>
				<button id="btnSunesisLearner" <?php echo $disabled; ?> type="button" onclick="window.location.href='do.php?_action=rec_view_vacancy_applications&id=<?php echo $vacancy->id; ?>&status=<?php echo RecCandidateApplication::SUNESIS_LEARNER; ?>'">Sunesis Learners (<?php echo $vacancy->getNumberOfSunesisLearners($link); ?>)</button>
				<button id="btnWithdrawn" <?php echo $disabled; ?> type="button" onclick="window.location.href='do.php?_action=rec_view_vacancy_applications&id=<?php echo $vacancy->id; ?>&status=<?php echo RecCandidateApplication::WITHDRAWN; ?>'">Withdrawn (<?php echo $vacancy->getNumberOfWithdrawnApplications($link); ?>)</button>
				<button id="btnRejected" <?php echo $disabled; ?> type="button" onclick="window.location.href='do.php?_action=rec_view_vacancy_applications&id=<?php echo $vacancy->id; ?>&status=<?php echo RecCandidateApplication::REJECTED; ?>'">Rejected (<?php echo $vacancy->getNumberOfRejectedApplications($link); ?>)</button>
			</td>
		</tr>
		<?php } else { ?>
		<tr>
			<td colspan="5" align="center">
				<button id="btnCVSent" type="button" onclick="window.location.href='do.php?_action=rec_view_vacancy_applications&id=<?php echo $vacancy->id; ?>&status=<?php echo RecCandidateApplication::CV_SENT; ?>'">CV Sent (<?php echo $vacancy->getNumberOfCVSentApplications($link); ?>)</button>
				<button id="btnInterviewSuccessful" type="button" onclick="window.location.href='do.php?_action=rec_view_vacancy_applications&id=<?php echo $vacancy->id; ?>&status=<?php echo RecCandidateApplication::INTERVIEW_SUCCESSFUL; ?>'">Interview Successful (<?php echo $vacancy->getNumberOfSuccessfulApplications($link); ?>)</button>
				<button id="btnInterviewUnsuccessful" type="button" onclick="window.location.href='do.php?_action=rec_view_vacancy_applications&id=<?php echo $vacancy->id; ?>&status=<?php echo RecCandidateApplication::INTERVIEW_UNSUCCESSFUL; ?>'">Interview Unsuccessful (<?php echo $vacancy->getNumberOfUnsuccessfulApplications($link); ?>)</button>
				<button id="btnSunesisLearner" type="button" onclick="window.location.href='do.php?_action=rec_view_vacancy_applications&id=<?php echo $vacancy->id; ?>&status=<?php echo RecCandidateApplication::SUNESIS_LEARNER; ?>'">Sunesis Learners (<?php echo $vacancy->getNumberOfSunesisLearners($link); ?>)</button>
			</td>
		</tr>
		<?php } ?>
	</table>
</div>