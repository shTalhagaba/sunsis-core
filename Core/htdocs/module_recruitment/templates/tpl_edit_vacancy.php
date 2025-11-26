<?php /* @var $vo Contract */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Contract</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script language="JavaScript" src="/calendarPopup/CalendarPopup.js"></script>

<!-- Initialise calendar popup -->
<script language="JavaScript">
	var calPop = new CalendarPopup("calPop1");
	calPop.showNavigationDropdowns();
	document.write(getCalendarStyles());
</script>

<script language="JavaScript">
function save()
{
	var myForm = document.forms[0];
	if(validateForm(myForm) == false)
	{
		return false;
	}
	
	myForm.submit();
}

function numbersonly(myfield, e, dec)
{
var key;
var keychar;

if (window.event)
   key = window.event.keyCode;
else if (e)
   key = e.which;
else
   return true;
   
keychar = String.fromCharCode(key);

// To check if it goes beyond 100
if(parseInt(myfield.value+keychar)<0 || parseInt(myfield.value+keychar)>100)
	return false;

// control keys
if ((key==null) || (key==0) || (key==8) || 
    (key==9) || (key==13) || (key==27) )
   return true;

// numbers
else if ((("0123456789").indexOf(keychar) > -1))
   return true;

// decimal point jump
else if (dec && (keychar == "."))
   {
   myfield.form.elements[dec].focus();
   return false;
   }
else
   return false;

}

</script>

</head>
<body>
<div class="banner">
	<div class="Title">Vacancy</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12){?>
		<button onclick="save();">Save</button>
		<?php }?>
 		<button onclick="if(confirm('Are you sure?'))window.history.go(-1);"> Cancel</button> 
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
<input type="hidden" name="employer_id" value="<?php echo $employer_id; ?>" />
<input type="hidden" name="status" value="1" />
<input type="hidden" name="_action" value="save_vacancy"/>
<table>
	<tr>
		<td colpsan="4"><h2>Key Vacancy Information</h2></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Job Title:</td>
		<td><input class="compulsory" type="text" name="job_title" value="<?php echo htmlspecialchars((string)$vo->job_title); ?>" /></td>
		<td class="fieldLabel_compulsory">Vacancy Code:</td>
		<td>
			<?php echo htmlspecialchars((string)$vo->code); ?>
			<input type="hidden" name="code" value="<?php echo htmlspecialchars((string)$vo->code); ?>" />
		</td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Award to be completed:</td>
		<td><?php echo HTML::select('type', $type_dropdown, $vo->type, true, true, true); ?></td>
		<td class="fieldLabel_compulsory"> No. of Vacancies:</td>
		<td><input class="compulsory" type="text" name="no_of_vacancies" value="<?php echo htmlspecialchars((string)$vo->no_of_vacancies); ?>" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory"> Proposed Interview Date:</td>
		<td><?php echo HTML::datebox('interview_date', $vo->interview_date); ?></td>
		<td class="fieldLabel_compulsory"> Salary Information:</td>
        <td><input class="compulsory" name="salary" type="text" value="<?php echo htmlspecialchars((string)$vo->salary); ?>" /></td>
	</tr>	
	<tr>
		<td class="fieldLabel_compulsory"> Possibility to complete a level 3 advanced apprenticeship</td>
		<td><input type="radio" name="to_level_3" value="1"/> yes <input type="radio" name="to_level_3" value="0" /> no </td>
		<td class="fieldLabel_compulsory"> Other (please state):</td>
		<td><input type="text" name="prospects" value="<?php echo htmlspecialchars((string)$vo->prospects); ?>" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory"> Location:</td>
		<td><?php echo HTML::select('location', $locations_dropdown, $vo->location, true, true); ?></td>
		<td class="fieldLabel_compulsory"> Active Vacancy:</td>
		<td><?php echo HTML::select('active', $active_dropdown, $vo->active, true, true); ?></td>
	</tr>
	
	<tr>
		<td class="fieldLabel_compulsory">Job Description:</td>
		<td colspan="3"><textarea class="compulsory" style="font-family:sans-serif; font-size:10pt" name="description" rows="4" cols="70" ><?php echo htmlspecialchars((string)$vo->description); ?></textarea></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Person Specification:</td>
		<td colspan="3"><textarea class="compulsory" style="font-family:sans-serif; font-size:10pt" name="person_spec" rows="4" cols="70"><?php echo htmlspecialchars((string)$vo->person_spec); ?></textarea></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Qualifications Required:</td>
		<td colspan="3"><textarea class="compulsory" style="font-family:sans-serif; font-size:10pt" name="required_quals" rows="4" cols="70"><?php echo htmlspecialchars((string)$vo->required_quals); ?></textarea></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Important Other Information:</td>
		<td colspan="3"><textarea class="compulsory" style="font-family:sans-serif; font-size:10pt" name="misc" rows="4" cols="70"><?php echo htmlspecialchars((string)$vo->misc); ?></textarea></td>
	</tr>
	
	<tr>
		<td>Expected Weekly Working Hours:</td>
		<td colspan="3">
			<table>
				<tr>
					<td>Monday:</td>
					<td><input type="text" onkeypress="return numbersonly(this, event);" name="hours_mon" value="<?php echo htmlspecialchars((string)$vo->hours_mon); ?>" style="width: 20px" /> hours woo hoo</td>
				</tr>
				<tr>
					<td>Tuesday:</td>
					<td><input type="text" onkeypress="return numbersonly(this, event);" name="hours_tues" value="<?php echo htmlspecialchars((string)$vo->hours_tues); ?>" style="width: 20px" /> hours</td>
				</tr>
				<tr>
					<td>Wednesday:</td>
					<td><input type="text" onkeypress="return numbersonly(this, event);" name="hours_wed" value="<?php echo htmlspecialchars((string)$vo->hours_wed); ?>" style="width: 20px" /> hours</td>
				</tr>
				<tr>
					<td>Thursday:</td>
					<td><input type="text" onkeypress="return numbersonly(this, event);" name="hours_thurs" value="<?php echo htmlspecialchars((string)$vo->hours_thurs); ?>" style="width: 20px" /> hours</td>
				</tr>
				<tr>
					<td>Friday:</td>
					<td><input type="text" onkeypress="return numbersonly(this, event);" name="hours_fri" value="<?php echo htmlspecialchars((string)$vo->hours_fri); ?>" style="width: 20px" /> hours</td>
				</tr>
				<tr>
					<td>Saturday:</td>
					<td><input type="text" onkeypress="return numbersonly(this, event);" name="hours_sat" value="<?php echo htmlspecialchars((string)$vo->hours_sat); ?>" style="width: 20px" /> hours</td>
				</tr>
				<tr>
					<td>Sunday:</td>
					<td><input type="text" onkeypress="return numbersonly(this, event);" name="hours_sun" value="<?php echo htmlspecialchars((string)$vo->hours_sun); ?>" style="width: 20px" /> hours</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>
