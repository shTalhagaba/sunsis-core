<html>
<head>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" href="/print.css" media="print" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
</head>
<body>
<div id="header" style="margin-top: 5px; border-top: 12px solid rgb(0,143,206);" width="100%" ></div>
<center>
<div style="margin-top: 20px; ">

<span><h2>BRIGHT TRAINING LIMITED</h2> </span>

</div>

<script language="javascript">
function borough_onchange(id, event)
{

	var url = 'do.php?_action=ajax_get_county&id=' + id.value;

	alert(expandURI(url));
	
	alert(url);
	var c = ajaxRequest(url);

	alert(c.responseText);
	if(c != null)
	{
		//document.getElementById("county").value = client.responseText
	}
}

</script>

<div id="main_image" style="margin-left: 0px; margin-top: -20px;  margin-bottom:3px;">

<img src='/images/bright.jpg' />
</div>
</center>

<?php if($candidate) { ?>

<div style="clear: both"></div>						

<div id="main" style='font-family: "Arial"; font-size: 10pt; margin-top: 5px; display: block; margin-left: auto; margin-right: auto; width: 960px;'>
	<form name="login" action="/do.php?_action=save_candidate" method="post">
	<input type="hidden" name="screen_width" />
	<input type="hidden" name="screen_height" />
	<input type="hidden" name="color_depth" />
	<input type="hidden" name="flash" />
	<input type="hidden" name="destination" value="<?php echo (isset($_REQUEST['destination'])?htmlspecialchars((string)$_REQUEST['destination']):''); ?>" />

	
	<div style="margin-left: 200px; margin-top: 30px" >
		<h3>Personal Information</h3>
		<table>
			<tr>
				<td width="250" class="">Given Name: <span style="color: red">&nbsp; * </span></td>
				<td><input class="compulsory" type="text" name="firstnames"  size="40" maxlength="100"/></td>
			</tr>
			<tr>
				<td class="">Family Name: <span style="color: red">&nbsp; * </span></td>
				<td><input class="compulsory" type="text" name="surname"  size="40" maxlength="100"/></td>
			</tr>
			<tr>
				<td class="">Gender: <span style="color: red">&nbsp; * </span></td>
				<td><?php $gender = "SELECT id, description, null FROM lookup_gender;";
						  $gender = DAO::getResultset($link, $gender);
				echo HTML::select('gender', $gender, '', false, true); ?></td>
			</tr>
			<tr>
				<td class="">Ethnicity: <span style="color: red">&nbsp; * </span></td>
				<td><?php 
					$L12_dropdown = DAO::getResultset($link,"SELECT Ethnicity_Code, LEFT(CONCAT(Ethnicity_Code, ' ', Ethnicity_Desc), 50), null from lis201011.ILR_L12_Ethnicity order by Ethnicity_Code;");
					array_unshift($L12_dropdown,array('0','Please select one',''));
					echo HTML::select('ethnicity', $L12_dropdown, '', false, true); ?></td>
			</tr>
			<tr>
				<td class="">Date of Birth: <span style="color: red">&nbsp; * </span></td>
				<td>
				<?php 
				$day = array(array('',''),array(1,1),array(2,2),array(3,3),array(4,4),array(5,5),array(6,6),array(7,7),array(8,8),array(9,9),array(10,10),array(11,11),array(12,12),array(13,13),array(14,14),array(15,15),array(16,16),array(17,17),array(18,18),array(19,19),array(20,20),array(21,21),array(22,22),array(23,23),array(24,24),array(25,25),array(26,26),array(27,27),array(28,28),array(29,29),array(30,30),array(31,31)); 
				$month = array(array('',''),array(1,1),array(2,2),array(3,3),array(4,4),array(5,5),array(6,6),array(7,7),array(8,8),array(9,9),array(10,10),array(11,11),array(12,12));
				$year = array(array('',''));
				for($a = 1970; $a<=2010; $a++)
				{
					$year[] = array($a,$a);	
				}
				echo HTML::select('dob_day', $day, '', true, true); 
				echo HTML::select('dob_month', $month, '', true, true); 
				echo HTML::select('dob_year', $year, '', true, true); 
				?>
				</td>
				
			</tr>		
			<tr>
				<td class="">National Insurance:</td>
				<td><input class="compulsory" type="text" name="ni"  size="11" maxlength="100"/></td>
			</tr>		
			
		</table>
		<h3>Contact Details</h3>
		<table>
			<tr>
				<td width="250" class="">House name: <span style="color: red">&nbsp; * </span></td>
				<td><input class="compulsory" type="text" name="address1"  size="40" maxlength="100"/></td>
			</tr>
			<tr>
				<td width="240" class="">Street and number: <span style="color: red">&nbsp; * </span></td>
				<td><input class="compulsory" type="text" name="address2"  size="40" maxlength="100"/></td>
			</tr>
			<tr>
				<td class="">Borough:</td>
				<td><?php  $borough = DAO::getResultset($link, "SELECT id, description, null FROM central.lookup_boroughs order by description;");
				array_unshift($borough,array('0','Please select one',''));
				echo HTML::select('borough', $borough, '', false, false); ?></td>
			</tr>
			<tr>
				<td class="">County:</td>
				<td><input class="compulsory" type="text" id="county" name="county"  size="40" maxlength="100"/></td>
			</tr>
			<tr>
				<td width="240" class="">Postcode:</td>
				<td><input class="compulsory" type="text" name="postcode"  size="8" maxlength="100"/></td>
			</tr>
			<tr>
				<td width="240" class="">Telephone:</td>
				<td><input class="compulsory" type="text" name="telephone"  size="15" maxlength="100"/></td>
			</tr>
			<tr>
				<td width="240" class="">Mobile:</td>
				<td><input class="compulsory" type="text" name="mobile"  size="15" maxlength="100"/></td>
			</tr>
			<tr>
				<td width="240" class="">Fax:</td>
				<td><input class="compulsory" type="text" name="fax"  size="15" maxlength="100"/></td>
			</tr>
			<tr>
				<td width="240" class="">Email:</td>
				<td><input class="compulsory" type="text" name="email"  size="20" maxlength="100"/></td>
			</tr>
		</table>
		<h3>Employment Status</h3>
		<table>
			<tr>
				<td width="250" class="">What is your employment status:</td>
				<td>
				<?php $employment_status = array(array('0','Please select one'),array('1','Employed'),array('2','Self Employed'),array('3','Full Time Education or Training'),array('4','Unemployed'),array('5','Economically Inactive'),array('6','14 - 19 NEET'));	
					echo HTML::select('employment_status', $employment_status, '', false, true); ?>
				</td>
			</tr>
			<tr>
				<td width="240" class="">If employed, how many hours per week:</td>
				<td><input class="compulsory" type="text" name="hours_per_week"  size="10" maxlength="100"/></td>
			</tr>
			<tr>
				<td width="240" class="">If not employed, when was the last time that you worked:</td>
				<td>
				<?php $last_time_worked = array(array('0','Please select one'),array('1','Less than 6 months'),array('2','6-11 months'),array('3','12-23 months'),array('4','24-35 months'),array('5','Over 36 months'));	
					echo HTML::select('last_time_worked', $last_time_worked, '', false, true); ?>
				</td>
			</tr>
		</table>		
		<h3>Study Needs</h3>
		<table>
			<tr>
				<td width="50" class="">Disability:</td>
			</tr>
			<tr>
				<td colspan=2><?php  $disability = DAO::getResultset($link, "SELECT Disability_Code, LEFT(CONCAT(Disability_Code, ' ', Disability_Desc), 40), null from lis201011.ilr_l15_disability order by Disability_Code limit 0,10;");
				echo HTML::checkboxGrid('disability', $disability, null, 2, true); ?></td>
			</tr>
			<tr>
				<td  colspan=2 class="">Learning Difficulty:</td>
			</tr>
			<tr>
				<td colspan=2><?php  $difficulty = DAO::getResultset($link, "SELECT Difficulty_Code, LEFT(CONCAT(Difficulty_Code,' ',Difficulty_Desc),40),null from lis201011.ilr_l16_difficulty order by Difficulty_Code limit 0,8;");
				echo HTML::checkboxGrid('difficulty', $difficulty, null, 2, true); ?></td>
			</tr>
		</table>		
		<h3>Study History</h3>
		<table>
			<tr>
				<td width="250" class="">Highest education completed:</td>
				<td><input class="compulsory" type="text" name="last_education"  size="10" maxlength="100"/></td>
			</tr>
			<tr>
				<td colspan=2>
					<table class="resultset" style="cellpadding: 3px; cellspacing: 3px;">
						<tr>
							<th>GCSE/A/AS Level</th><th>Subject</th><th>Grade</th>
						</tr>
						<tr>
							<td><input class="compulsory" type="text" name="level1"  size="10" maxlength="100"/></td>
							<td><input class="compulsory" type="text" name="subject1"  size="40" maxlength="100"/></td>
							<td><input class="compulsory" type="text" name="grade1"  size="1" maxlength="100"/></td>
						</tr>
						<tr>
							<td><input class="compulsory" type="text" name="level2"  size="10" maxlength="100"/></td>
							<td><input class="compulsory" type="text" name="subject2"  size="40" maxlength="100"/></td>
							<td><input class="compulsory" type="text" name="grade2"  size="1" maxlength="100"/></td>
						</tr>
						<tr>
							<td><input class="compulsory" type="text" name="level3"  size="10" maxlength="100"/></td>
							<td><input class="compulsory" type="text" name="subject3"  size="40" maxlength="100"/></td>
							<td><input class="compulsory" type="text" name="grade3"  size="1" maxlength="100"/></td>
						</tr>
						<tr>
							<td><input class="compulsory" type="text" name="level4"  size="10" maxlength="100"/></td>
							<td><input class="compulsory" type="text" name="subject4"  size="40" maxlength="100"/></td>
							<td><input class="compulsory" type="text" name="grade4"  size="1" maxlength="100"/></td>
						</tr>
						<tr>
							<td><input class="compulsory" type="text" name="level5"  size="10" maxlength="100"/></td>
							<td><input class="compulsory" type="text" name="subject5"  size="40" maxlength="100"/></td>
							<td><input class="compulsory" type="text" name="grade5"  size="1" maxlength="100"/></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>Have you completed an NVQ or BTEC Qualification before</td>
				<td><?php echo HTML::radioButtonGrid('web_access', $previous_qualification, '', 2); ?>				
			</tr>
			<tr>
				<td colspan=2>
					<table class="resultset">
						<tr>
							<th>Level</th><th>Course</th><th>Date Completed</th>
						</tr>
						<tr>
							<td><input class="compulsory" type="text" name="level1"  size="10" maxlength="100"/></td>
							<td><input class="compulsory" type="text" name="subject1"  size="40" maxlength="100"/></td>
							<td><input class="compulsory" type="text" name="grade1"  size="10" maxlength="100"/></td>
						</tr>
						<tr>
							<td><input class="compulsory" type="text" name="level2"  size="10" maxlength="100"/></td>
							<td><input class="compulsory" type="text" name="subject2"  size="40" maxlength="100"/></td>
							<td><input class="compulsory" type="text" name="grade2"  size="10" maxlength="100"/></td>
						</tr>
						<tr>
							<td><input class="compulsory" type="text" name="level3"  size="10" maxlength="100"/></td>
							<td><input class="compulsory" type="text" name="subject3"  size="40" maxlength="100"/></td>
							<td><input class="compulsory" type="text" name="grade3"  size="10" maxlength="100"/></td>
						</tr>
					</table>
				</td>
			</tr>



		</table>		
		
	</div>
	</form>
	</div>

	
</div>
<?php } else { ?>
<div style="clear: both"></div>						

<div id="main" style='margin-top: 5px; display: block; margin-left: auto; margin-right: auto; width: 960px;'>
	<form name="login" action="<?php echo $_SERVER['PHP_SELF'].'?_action=login' ?>" method="post">
	<input type="hidden" name="screen_width" />
	<input type="hidden" name="screen_height" />
	<input type="hidden" name="color_depth" />
	<input type="hidden" name="flash" />
	<input type="hidden" name="destination" value="<?php echo (isset($_REQUEST['destination'])?htmlspecialchars((string)$_REQUEST['destination']):''); ?>" />

	
	<div style="margin-left: -60px; margin-top: 30px" >
	<center>
	
		<table  border="0" cellpadding="2" cellspacing="2">
		<tr>
			<td>Username:</td>
			<td><input style="border: 1px solid rgb(0,143,206)" id="txtUsername" type="text" name="username" value="" /></td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><input style="border: 1px solid rgb(0,143,206)"  type="password" name="password" value="" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="Login" style="width:100%"/></td>
		</tr>
	</table>
	
	</center>
	</div>
	
	</form>
	<div id="divMessages"><?php if(isset($message)) echo htmlspecialchars((string)$message); ?></div>	
	<div id="divWarnings"></div>

	<div style="clear: both; ">
	<div style="margin-left: 0px">
	</div>
	
	</div>
</div>
<?php } ?>
</body>
</html>