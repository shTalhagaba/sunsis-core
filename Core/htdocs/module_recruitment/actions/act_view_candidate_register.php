<?php
class view_candidate_register implements IUnauthenticatedAction

{
	public function execute( PDO $link ) {

		$vacancies = "";
		if(isset($_REQUEST['vac_id']))
		{

			for($i = 0; $i < sizeof($_REQUEST['vac_id']) - 1; $i++)
				$vacancies .= $_REQUEST['vac_id'][$i] . ",";
			$lastIndex = sizeof($_REQUEST['vac_id']) - 1;
			$vacancies .= $_REQUEST['vac_id'][$lastIndex];
		}

	    if ( SystemConfig::getEntityValue($link, 'recruitment_bespoke') ) {
		    $this->present_bespoke_registration($link);
		}
		else {
			$candidate_id = isset($_GET['candidate_id']) ? $_GET['candidate_id'] : '';
			if($candidate_id == '')
			{
				// New record
				$candidate = new Candidate();
				$mode = isset($_REQUEST['mode'])?$_REQUEST['mode']:'';
			}
			else
			{
				$candidate = Candidate::loadFromDatabase($link, $candidate_id);
				$candidateCVLink = $candidate->getCVLink($candidate_id, $candidate->firstnames . ' ' . $candidate->surname);
				$mode = isset($_REQUEST['mode'])?$_REQUEST['mode']:'';
			}

			$sql = "SELECT description, description, NULL FROM central.lookup_counties GROUP BY description ORDER BY description ASC;";
			$counties = DAO::getResultSet($link, $sql);


			require_once('tpl_view_candidate_register.php');
		}
	}

	/**
	 *
	 * This function is used for obtaining and presenting the client specific
	 * requirements for cpature.
	 * @param PDO $link
	 */
	private function present_bespoke_registration( PDO $link ) {

		$sql = "SELECT description, description, NULL FROM central.lookup_counties GROUP BY description ORDER BY description ASC;";
		$counties = DAO::getResultSet($link, $sql);
		// instantiate the user
		$registrant = new User();

		// get the client specific data required for capture
		$registrant->getUserMetaData($link);
		require_once('tpl_view_candidate_bespoke_register.php');
	}

	public function present_checkbox_values( PDO $link, $userinfoid ) {

		$sql_lookupdata = <<<HEREDOC
				SELECT 	userinfoname,
						lookupvalues
				FROM 	users_capture_info
				WHERE 	users_capture_info.userinfoid = $userinfoid ;
HEREDOC;

		$st = $link->query($sql_lookupdata);
		if($st) {

			$lookup_gridbox = array();
			$row = $st->fetch();
			//$display_name = preg_replace('/ /', '', strtolower($row['userinfoname']));
			$display_name = 'reg_'.$userinfoid;
			$lookup_options = explode("|", $row['lookupvalues']);
			foreach ( $lookup_options as $value ) {
				$lookup_gridbox[] = array($value,$value,NULL);
			}
			// inserts a table - need to review this?
			return HTML::checkboxGrid($display_name, $lookup_gridbox, null, 3, true);
		}
		else {
			throw new Exception('ERR: The metadata is not correctly set up');
		}

		return;
	}

	public function present_radio_values( PDO $link, $userinfoid ) {

		$sql_lookupdata = <<<HEREDOC
				SELECT 	userinfoname,
						lookupvalues
				FROM 	users_capture_info
				WHERE 	users_capture_info.userinfoid = $userinfoid ;
HEREDOC;

		$st = $link->query($sql_lookupdata);
		if($st) {

			$lookup_gridbox = array();
			$row = $st->fetch();
			//$display_name = preg_replace('/ /', '', strtolower($row['userinfoname']));
			$display_name = 'reg_'.$userinfoid;
			$lookup_options = explode("|", $row['lookupvalues']);
			foreach ( $lookup_options as $value ) {
				$lookup_gridbox[] = array($value,$value,NULL);
			}
			// inserts a table - need to review this?
			return HTML::radioButtonGrid($display_name, $lookup_gridbox, '');
		}
		else {
			throw new Exception('ERR: The metadata is not correctly set up');
		}

		return;
	}

	public function present_select_values( PDO $link, $userinfoid ) {

		$sql_lookupdata = <<<HEREDOC
				SELECT 	userinfoname,
						lookupvalues
				FROM 	users_capture_info
				WHERE 	users_capture_info.userinfoid = $userinfoid ;
HEREDOC;

		$st = $link->query($sql_lookupdata);
		if($st) {

			$lookup_gridbox = array();
			$row = $st->fetch();
			// $display_name = preg_replace('/ /', '', strtolower($row['userinfoname']));
			$display_name = 'reg_'.$userinfoid;
			$lookup_options = explode("|", $row['lookupvalues']);
			foreach ( $lookup_options as $value ) {
				$lookup_gridbox[] = array($value,$value,NULL);
			}
			return HTML::select($display_name, $lookup_gridbox, true, true, false);
		}
		else {
			throw new Exception('ERR: The metadata is not correctly set up');
		}

		return;
	}

	public function present_qualification_questions(PDO $link) {

		// date drop down populations
		$day = array(array('','dd'),array(1,1),array(2,2),array(3,3),array(4,4),array(5,5),array(6,6),array(7,7),array(8,8),array(9,9),array(10,10),array(11,11),array(12,12),array(13,13),array(14,14),array(15,15),array(16,16),array(17,17),array(18,18),array(19,19),array(20,20),array(21,21),array(22,22),array(23,23),array(24,24),array(25,25),array(26,26),array(27,27),array(28,28),array(29,29),array(30,30),array(31,31));
		$month = array(array('','mon'),array(1,'Jan'),array(2,'Feb'),array(3,'Mar'),array(4,'Apr'),array(5,'May'),array(6,'Jun'),array(7,'Jul'),array(8,'Aug'),array(9,'Sep'),array(10,'Oct'),array(11,'Nov'),array(12,'Dec'));
		$year = array(array('','yyyy'));

		$this_year = date("Y")+2;
		$early_year = $this_year-60;

		for($a = $this_year; $a >= $early_year; $a--) {
			$year[] = array($a,$a);
		}

		// awfully greedy pattern matches - need to change
		// - ie issue with onchange on array[] - removing it so verfiy this
		$day_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_day[]', $day, '', false, true));
		$day_options = preg_replace("/id=\"(.*)\"/", "", $day_options);
		$mon_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_mon[]', $month, '', false, true));
		$mon_options = preg_replace("/id=\"(.*)\"/", "", $mon_options);
		$year_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_year[]', $year, '', false, true));
		$year_options = preg_replace("/id=\"(.*)\"/", "", $year_options);

		// none compulsory date fields
		$qual_day_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_day[]', $day, '', false, false));
		$qual_day_options = preg_replace("/id=\"(.*)\"/", "", $qual_day_options);
		$qual_mon_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_mon[]', $month, '', false, false));
		$qual_mon_options = preg_replace("/id=\"(.*)\"/", "", $qual_mon_options);
		$qual_year_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_year[]', $year, '', false, false));
		$qual_year_options = preg_replace("/id=\"(.*)\"/", "", $qual_year_options);

		// employment history date fields
		$hist_sday_options = preg_replace("/id=\"(.*)\"/", "", HTML::select('hist_sday[]', $day, '', false, false));
		$hist_smon_options = preg_replace("/id=\"(.*)\"/", "", HTML::select('hist_smon[]', $month, '', false, false));
		$hist_syear_options = preg_replace("/id=\"(.*)\"/", "", HTML::select('hist_syear[]', $year, '', false, false));

		$hist_eday_options = preg_replace("/id=\"(.*)\"/", "", HTML::select('hist_eday[]', $day, '', false, false));
		$hist_emon_options = preg_replace("/id=\"(.*)\"/", "", HTML::select('hist_emon[]', $month, '', false, false));
		$hist_eyear_options = preg_replace("/id=\"(.*)\"/", "", HTML::select('hist_eyear[]', $year, '', false, false));

		// GCSE / A / AS level options
		$qual_level_one = array(array('GCSE','GCSE'),array('A', 'A Level'),array('AS','AS Level'),array('Other','Other'));
		// - ie issue with onchange on array[] - removing it so verfiy this
		$qual_level_one_options = preg_replace("/onchange=\"(.*)\"/", "", HTML::select('level[]', $qual_level_one, '', true, false, true));
		$qual_level_one_options = preg_replace("/id=\"(.*)\"/", "", $qual_level_one_options);

		// NVQ / BTEC level options
		$qual_level_two = array(array('NVQ','NVQ'),array('BTEC','BTEC'), array('Key Skills', 'Key Skills'), array('Functional Skills','Functional Skills'), array('Other','Other'));
		// - ie issue with onchange on array[] - removing it so verify this
		$qual_level_two_options = preg_replace("/onchange=\"(.*)\"/", "", HTML::select('level[]', $qual_level_two, '', true, false, true));
		$qual_level_two_options = preg_replace("/id=\"(.*)\"/", "", $qual_level_two_options);

		// Grades for GCSE / A / AS level options
		$qual_grades = array(array('A*','A*'),array('A','A'),array('B','B'),array('C','C'),array('D','D'),array('E','E'),array('F','F'),array('G','G'),array('U','U'));
		// - ie issue with onchange on array[] - removing it so verify this
		$qualification_grade_options = preg_replace("/onchange=\"(.*)\"/", "", HTML::select('grade[]', $qual_grades, '', true, false, true));
		$qualification_grade_options = preg_replace("/id=\"(.*)\"/", "",$qualification_grade_options);
		// mandatory grades
		$mandatory_grade_options = preg_replace("/id=\"(.*)\"/", "class=\"compulsory\"", HTML::select('grade[]', $qual_grades, '', true, true, true));

		echo '<p>Employment and Work Experience History (including paid employment, school work experience and voluntary positions)</p>';
		echo '	<table>';
		for( $hist_cnt = 1; $hist_cnt <= 4; $hist_cnt++ ) {
			echo '		<tr>';
			echo '	<td>Company <?php echo $hist_cnt; ?> Name:</td>';
			echo '	<td><input type="text" name="company_name[]"  maxlength="100" /></td>';
			echo '	<td>Job Title:</td>';
			echo '	<td><input type="text" name="job_title[]"  maxlength="100" /></td>';
			echo '</tr>';
			echo '<td>Start Date:</td>';
			echo '<td>';
			echo preg_replace("/\r\n/","", $hist_sday_options);
			echo preg_replace("/\r\n/","", $hist_smon_options);
			echo preg_replace("/\r\n/","", $hist_syear_options);
			echo '</td>';
			echo '<td>End Date:</td>';
			echo '<td>';
			echo preg_replace("/\r\n/","", $hist_eday_options);
			echo preg_replace("/\r\n/","", $hist_emon_options);
			echo preg_replace("/\r\n/","", $hist_eyear_options);
			echo '</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td>Skills Learnt:</td>';
			echo '<td colspan="3" ><textarea name="job_skills[]" style="width:98%" ></textarea></td>';
			echo '</tr>';
		}
		echo '</table>';
		echo '	<br/>';
		echo '	<table>';
		echo '		<tr>';
		echo '			<td width="250" class="">Highest education completed:</td>';
		echo '			<td>';

						$last_education = DAO::getResultset($link, "SELECT id, description, null FROM lookup_candidate_qualification order by id;");
						array_unshift($last_education ,array('0','Please select one',''));
						echo HTML::select('last_education', $last_education, '', false, false);

		echo '			</td>';
		echo '		</tr>';
		echo '	</table>';
        echo '	<br/>';
		echo '	<table class="resultset" id="qual_one" >';
		echo '		<thead>';
		echo '		<tr>';
		echo '			<th>Qualification Level</th>';
		echo '			<th>Subject</th>';
		echo '			<th>Grade</th>';
		echo '		</tr>';
		echo '		</thead>';
		echo '		<tbody>';
		echo '		<tr>';
		echo '			<td>';
		echo '				GCSE';
		echo '				<input type="hidden" name="level[]" value="GCSE" />';
		echo '			</td>';
		echo '			<td >';
		echo '				Maths';
		echo '				<input type="hidden" name="subject[]"  value="Maths" />';
		echo '			</td>';
		echo '			<td>';
		echo $mandatory_grade_options;
		echo '<span style="color: red">&nbsp; * </span>';
		echo '				<input type="hidden" name="comp_day[]" value="00" />';
		echo '				<input type="hidden" name="comp_mon[]" value="00" />';
		echo '				<input type="hidden" name="comp_year[]" value="0000" />';
		echo '			</td>';
		echo '		</tr>';
		echo '		<tr>';
		echo '			<td>';
		echo '				GCSE';
		echo '				<input type="hidden" name="level[]" value="GCSE" />';
		echo '			</td>';
		echo '			<td >';
		echo '				English';
		echo '				<input type="hidden" name="subject[]" value="English"/>';
		echo '			</td>';
		echo '			<td>';
		echo $mandatory_grade_options;
		echo '<span style="color: red">&nbsp; * </span>';
		echo '				<input type="hidden" name="comp_day[]" value="00" />';
		echo '				<input type="hidden" name="comp_mon[]" value="00" />';
		echo '				<input type="hidden" name="comp_year[]" value="0000" />';
		echo '			</td>';
		echo '		</tr>';
		echo '		<tr>';
		echo '			<td>';
		echo $qual_level_one_options;
		echo '			</td>';
		echo '			<td ><input type="text" name="subject[]"  size="40" maxlength="100"/></td>';
		echo '			<td>';
		echo $qualification_grade_options;
		echo '				<input type="hidden" name="comp_day[]" value="00" />';
		echo '				<input type="hidden" name="comp_mon[]" value="00" />';
		echo '				<input type="hidden" name="comp_year[]" value="0000" />';
		echo '			</td>';
		echo '		</tr>';
		echo '		<tr>';
		echo '			<td>';
		echo $qual_level_one_options;
		echo '			</td>';
		echo '			<td ><input type="text" name="subject[]"  size="40" maxlength="100"/></td>';
		echo '			<td>';
		echo $qualification_grade_options;
		echo '				<input type="hidden" name="comp_day[]" value="00" />';
		echo '				<input type="hidden" name="comp_mon[]" value="00" />';
		echo '				<input type="hidden" name="comp_year[]" value="0000" />';
		echo '			</td>';
		echo '		</tr>';
		echo '		<tr>';
		echo '			<td>';
		echo $qual_level_one_options;
		echo '			</td>';
		echo '			<td ><input type="text" name="subject[]"  size="40" maxlength="100"/></td>';
		echo '			<td>';
		echo $qualification_grade_options;
		echo '				<input type="hidden" name="comp_day[]" value="00" />';
		echo '				<input type="hidden" name="comp_mon[]" value="00" />';
		echo '				<input type="hidden" name="comp_year[]" value="0000" />';
		echo '			</td>';
		echo '		</tr>';
		echo '		</tbody>';
		echo '	</table>';
        echo '<a href="#study_quals" onclick="javascript:newqual(\'qual_one\')" >add another qualification</a>';
        echo '<a name="nvq_quals"></a>';
        echo '<p>';
		echo '	Have you completed a Vocational Qualification before?';
		echo '	<input type="radio" onclick="if(window.previous_qualification_onclick){window.previous_qualification_onclick(this, arguments.length &gt; 0 ? arguments[0] : window.event)}" onchange="if(window.previous_qualification_onchange){window.previous_qualification_onchange(this, arguments.length &gt; 0 ? arguments[0] : window.event)}" value="1" name="previous_qualification" />';
		echo '	Yes';
		echo '	<input type="radio" onclick="if(window.previous_qualification_onclick){window.previous_qualification_onclick(this, arguments.length &gt; 0 ? arguments[0] : window.event)}" onchange="if(window.previous_qualification_onchange){window.previous_qualification_onchange(this, arguments.length &gt; 0 ? arguments[0] : window.event)}" value="0" name="previous_qualification" />';
		echo '	No';
		echo '</p>';
		echo '<table class="resultset" id="qual_two" >';
		echo '	<thead>';
		echo '	<tr>';
		echo '		<th>Level</th>';
		echo '		<th>Course</th>';
		echo '		<th>Date Completed</th>';
		echo '	</tr>';
		echo '	</thead>';
		echo '	<tbody>';
		echo '	<tr>';
		echo '		<td>';
		echo $qual_level_two_options;
		echo '		</td>';
		echo '		<td><input type="text" name="subject[]"  size="40" maxlength="100"/></td>';
		echo '		<td>';
		echo '			<input type="hidden" name="grade[]" value="~" />';
		echo preg_replace("/\r\n/","", $qual_day_options);
		echo preg_replace("/\r\n/","", $qual_mon_options);
		echo preg_replace("/\r\n/","", $qual_year_options);
		echo '		</td>';
		echo '	</tr>';
		echo '	<tr>';
		echo '		<td>';
		echo $qual_level_two_options;
		echo '		</td>';
		echo '		<td><input type="text" name="subject[]"  size="40" maxlength="100"/></td>';
		echo '		<td>';
		echo '			<input type="hidden" name="grade[]" value="~" />';
		echo preg_replace("/\r\n/","", $qual_day_options);
		echo preg_replace("/\r\n/","", $qual_mon_options);
		echo preg_replace("/\r\n/","", $qual_year_options);
		echo '		</td>';
		echo '	</tr>';
		echo '	<tr>';
		echo '		<td>';
		echo $qual_level_two_options;
		echo '		</td>';
		echo '		<td><input type="text" name="subject[]"  size="40" maxlength="100"/></td>';
		echo '		<td>';
		echo '			<input type="hidden" name="grade[]" value="~" />';
		echo preg_replace("/\r\n/","", $qual_day_options);
		echo preg_replace("/\r\n/","", $qual_mon_options);
		echo preg_replace("/\r\n/","", $qual_year_options);
		echo '		</td>';
		echo '	</tr>';
		echo '	</tbody>';
		echo '</table>';
        echo '<a href="#nvq_quals" onclick="javascript:newqual(\'qual_two\')">add another qualification</a>';
	}
}
?>
