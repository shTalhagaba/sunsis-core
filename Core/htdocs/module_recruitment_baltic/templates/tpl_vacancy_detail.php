<?php /* @var $vacancy Vacancy */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

	<!-- for Google -->
	<meta name="description" content="Apply for apprenticeship opportunities in Baltic.  "/>
	<meta name="keywords" content="Baltic, baltic training services, apprenticeships, vacancies, , "/>
	<meta name="author" content="Perspective Limited" />
	<meta name="copyright" content="Perspective Limited" />
	<meta name="application-name" content="Sunesis" />

	<!-- for Facebook -->
	<meta property="og:title" content="Baltic Apprenticeships" />
	<meta property="og:type" content="website" />
	<meta property="og:image" content="http://baltictraining.com/wp-content/uploads/2014/06/logo1.png" />
	<meta property="og:url" content="https://baltic.sunesis.uk.net/do.php?_action=vacancy_detail&id=<?php echo $vacancy->id; ?>"/>
	<meta property="og:description" content="Apply for apprenticeship opportunities."/>

	<!-- for Twitter -->
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:title" content="Baltic Apprenticeships" />
	<meta name="twitter:description" content="Apply for apprenticeship opportunities." />
	<meta name="twitter:image" content="http://baltictraining.com/wp-content/uploads/2014/06/logo1.png" />

	<title>Vacancy - <?php echo htmlspecialchars((string)$vacancy->job_title); ?></title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<style type="text/css">

		html {
			/*position: fixed;*/
			width: 100%;
			height: 100%;
			z-index: 99999;
			background: url('/images/logos/<?php echo SystemConfig::getEntityValue($link, 'logo'); ?>') top right no-repeat;

		}
	</style>
</head>



<body>
<h3><?php echo htmlspecialchars((string)$vacancy->job_title); ?></h3>
<input type="button" style="width: 200px; height: 60px; font-size: 25px;" class="next button" onclick="window.location.href=('do.php?_action=view_candidate_register&mode=application&vac_id[]=' + <?php echo $vacancy->id; ?>);" value="Apply &raquo;" />
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
	<col width="250" />
	<tr>
		<td class="fieldLabel">Vacancy Code:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vacancy->code); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Description:</td><td class="fieldValue"><?php echo nl2br($vacancy->description); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Skills Requirement:</td><td class="fieldValue"><?php echo nl2br($vacancy->skills_req); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Training to be provided:</td><td class="fieldValue"><?php echo nl2br($vacancy->training_provided); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Personal Qualities:</td><td class="fieldValue"><?php echo (nl2br($vacancy->person_spec)); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Qualification Requirements:</td><td class="fieldValue"><?php echo nl2br($vacancy->required_quals); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Future Prospects:</td><td class="fieldValue"><?php echo nl2br($vacancy->future_prospects); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Salary per week:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vacancy->salary); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Weekly working schedule:</td><td class="fieldValue"><?php echo nl2br($shift_pattern); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Number of Hours (per week):</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vacancy->hrs_per_week); ?></td>
	</tr>
	<tr>
		<td align="right"><input type="button" style="width: 200px; height: 60px; font-size: 25px;" class="next button" onclick="window.location.href=('do.php?_action=view_candidate_register&mode=application&vac_id[]=' + <?php echo $vacancy->id; ?>);" value="Apply &raquo;" /> </td>
		<td align="left"><input type="button" style="width: 350px; height: 60px; font-size: 25px;" class="next button" onclick="window.location.href=('do.php?_action=view_candidate_vacancies');" value="Search Other Vacancies &raquo;" /> </td>
	</tr>
</table>
</body>
</html>