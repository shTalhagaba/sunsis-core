<?php
class AttendanceHelper
{
	/**
	 * Echos header cells directly to STDOUT
	 *
	 * @return void
	 */
	public static function echoHeaderCells($include_number_of_registers = true)
	{
		if($include_number_of_registers)
		{
			echo '<th class="AttendanceStatistic" width="30" title="Registers" align="center" style="font-size:6pt;color:gray" valign="middle">number of registers</th>';
		}

		echo <<<HEREDOC
<th class="AttendanceStatistic" width="30" title="Attendances" style="font-size:6pt;color:gray"><img src="/images/register/reg-attended-16.png" width="16" height="16" /><br/>present</th>
<th class="AttendanceStatistic" width="30" title="Latecomers" style="font-size:6pt;color:gray"><img src="/images/register/reg-late-16.png" width="16" height="16" /><br/>late</th>
<th class="AttendanceStatistic" width="30" title="Authorised Absences" style="font-size:6pt;color:gray"><img src="/images/register/reg-aa-16.png" width="16" height="16" /><br/>auth.</th>
<th class="AttendanceStatistic" width="30" title="Unexplained Absences" style="font-size:6pt;color:gray"><img src="/images/register/reg-mystery-16.png" width="16" height="16" /><br/>unexp.</th>
<th class="AttendanceStatistic" width="30" title="Unauthorised Absences" style="font-size:6pt;color:gray"><img src="/images/register/reg-ua-16.png" width="16" height="16" /><br/>unauth.</th>
HEREDOC;

	}


	/**
	 * Echos data cells directly to STDOUT
	 * @param mixed $row array or object
	 * @return void
	 */
	public static function echoDataCells($row)
	{
		if(!is_array($row) && !is_object($row))
		{
			throw new Exception("Argument \$row must be an array or an object");
		}

		if(is_array($row))
		{
			$total = $row['attendances']
				+ $row['lates']
				+ $row['authorised_absences']
				+ $row['unexplained_absences']
				+ $row['unauthorised_absences']
				+ $row['dismissals_uniform']
				+ $row['dismissals_discipline'];

			if(array_key_exists('scheduled_lessons', $row) && array_key_exists('registered_lessons', $row))
			{
				echo '<td class="AttendanceStatistic" align="center">';
				echo $row['registered_lessons'].'<br><span style="border-top:1px solid black">'.$row['scheduled_lessons'];
				echo '</td>';
			}
			else
			{
				//echo '<td>&nbsp;</td>';		
			}

			$total_attendance = $row['attendances'] + $row['lates'];

//			if( $total_attendance > 0)
//			{
//				echo '<td class="AttendanceStatistic" title="Punctual + Late" align="center" style="border-right-style:solid">';
//				echo $total_attendance.'<br/><span class="AttendancePercentage" style="font-size:80%">'.sprintf("%.1f",($total_attendance/$total) * 100)."%</span></td>\n";
//			}
//			else
//			{
//				echo '<td class="AttendanceStatistic" style="border-right-style:solid" title="Attendances" align="center">&nbsp;</td>'."\n";
//			}

			if($row['attendances'] > 0)
			{
				echo '<td class="AttendanceStatistic" title="Attendances" align="center" style="background-color:#CEEEDD">';
				echo $row['attendances'].'<br/><span class="AttendancePercentage" style="font-size:80%">'.sprintf("%.1f",($row['attendances']/$total) * 100).'%</span></td>';
			}
			else
			{
				echo '<td class="AttendanceStatistic" title="Attendances" align="center">&nbsp;</td>';
			}

			if($row['lates'] > 0)
			{
				echo '<td class="AttendanceStatistic" title="Lates" align="center" style="background-color:#CEEEDD">';
				echo $row['lates'].'<br/><span class="AttendancePercentage" style="font-size:80%">'.sprintf("%.1f",($row['lates']/$total) * 100).'%</span></td>';
			}
			else
			{
				echo '<td class="AttendanceStatistic" title="Lates" align="center">&nbsp;</td>';
			}

			if($row['authorised_absences'] > 0)
			{
				echo '<td class="AttendanceStatistic" title="Authorised absences" align="center" style="background-color:#CEEEDD">';
				echo $row['authorised_absences'].'<br/><span class="AttendancePercentage" style="font-size:80%">'.sprintf("%.1f",($row['authorised_absences']/$total) * 100).'%</span></td>';
			}
			else
			{
				echo '<td class="AttendanceStatistic" title="Authorised absences" align="center">&nbsp;</td>';
			}


			if($row['unexplained_absences'] > 0)
			{
				echo '<td class="AttendanceStatistic" title="Unexplained absences" align="center" style="background-color:#FDDFBB">';
				echo $row['unexplained_absences'].'<br/><span class="AttendancePercentage" style="font-size:80%">'.sprintf("%.1f",($row['unexplained_absences']/$total) * 100).'%</span></td>';
			}
			else
			{
				echo '<td class="AttendanceStatistic" title="Unexplained absences" align="center">&nbsp;</td>';
			}

			if($row['unauthorised_absences'] > 0)
			{
				echo '<td class="AttendanceStatistic" title="Unauthorised absence" align="center" style="background-color:#FFCCCC">';
				echo $row['unauthorised_absences'].'<br/><span class="AttendancePercentage" style="font-size:80%">'.sprintf("%.1f",($row['unauthorised_absences']/$total) * 100).'%</span></td>';
			}
			else
			{
				echo '<td class="AttendanceStatistic" title="Unauthorised absence" align="center">&nbsp;</td>';
			}

			if($row['dismissals_uniform'] > 0)
			{
				echo '<td class="AttendanceStatistic" title="Dismissal due to incorrect dress" align="center" style="background-color:#FFCCCC">';
				echo $row['dismissals_uniform'].'<br/><span class="AttendancePercentage" style="font-size:80%">'.sprintf("%.1f",($row['dismissals_uniform']/$total) * 100).'%</span></td>';
			}
			else
			{
				//echo '<td class="AttendanceStatistic" title="Dismissal due to incorrect dress" align="center">&nbsp;</td>';
			}

			if($row['dismissals_discipline'] > 0)
			{
				echo '<td class="AttendanceStatistic" title="Dismissal due to discliplinary offence" align="center" style="background-color:#FFCCCC">';
				echo $row['dismissals_discipline'].'<br/><span class="AttendancePercentage" style="font-size:80%">'.sprintf("%.1f",($row['dismissals_discipline']/$total) * 100).'%</span></td>';
			}
			else
			{
				//echo '<td class="AttendanceStatistic" title="Dismissal due to disciplinary offence" align="center">&nbsp;</td>';
			}
		}
		else
		{
			$total = $row->attendances
				+ $row->lates
				+ $row->authorised_absences
				+ $row->unexplained_absences
				+ $row->unauthorised_absences
				+ $row->dismissals_uniform
				+ $row->dismissals_discipline;

			if(array_key_exists('scheduled_lessons', (array) $row) && array_key_exists('registered_lessons', (array) $row))
			{
				echo '<td class="AttendanceStatistic" align="center">';
				echo $row->registered_lessons.'<br><span style="border-top:1px solid black">'.$row->scheduled_lessons;
				echo '</td>';
			}

			$total_attendance = $row->attendances + $row->lates;

//			if( $total_attendance > 0)
//			{
//				echo '<td class="AttendanceStatistic" title="Punctual + Late" align="center" style="border-right-style:solid">';
//				echo $total_attendance.'<br/><span class="AttendancePercentage" style="font-size:80%">'.sprintf("%.1f",($total_attendance/$total) * 100)."%</span></td>\n";
//			}
//			else
//			{
//				echo '<td class="AttendanceStatistic" style="border-right-style:solid" title="Attendances" align="center">&nbsp;</td>'."\n";
//			}

			if($row->attendances > 0)
			{
				echo '<td class="AttendanceStatistic" title="Attendances" align="center" style="background-color:#CEEEDD">';
				echo $row->attendances.'<br/><span class="AttendancePercentage" style="font-size:80%">'.sprintf("%.1f",($row->attendances/$total) * 100).'%</span></td>';
			}
			else
			{
				echo '<td class="AttendanceStatistic" title="Attendances" align="center">&nbsp;</td>';
			}

			if($row->lates > 0)
			{
				echo '<td class="AttendanceStatistic" title="Lates" align="center" style="background-color:#CEEEDD">';
				echo $row->lates.'<br/><span class="AttendancePercentage" style="font-size:80%">'.sprintf("%.1f",($row->lates/$total) * 100).'%</span></td>';
			}
			else
			{
				echo '<td class="AttendanceStatistic" title="Lates" align="center">&nbsp;</td>';
			}

			if($row->authorised_absences > 0)
			{
				echo '<td class="AttendanceStatistic" title="Authorised absences" align="center" style="background-color:#CEEEDD">';
				echo $row->authorised_absences.'<br/><span class="AttendancePercentage" style="font-size:80%">'.sprintf("%.1f",($row->authorised_absences/$total) * 100).'%</span></td>';
			}
			else
			{
				echo '<td class="AttendanceStatistic" title="Authorised absences" align="center">&nbsp;</td>';
			}


			if($row->unexplained_absences > 0)
			{
				echo '<td class="AttendanceStatistic" title="Unexplained absences" align="center" style="background-color:#FDDFBB">';
				echo $row->unexplained_absences.'<br/><span class="AttendancePercentage" style="font-size:80%">'.sprintf("%.1f",($row->unexplained_absences/$total) * 100).'%</span></td>';
			}
			else
			{
				echo '<td class="AttendanceStatistic" title="Unexplained absences" align="center">&nbsp;</td>';
			}

			if($row->unauthorised_absences > 0)
			{
				echo '<td class="AttendanceStatistic" title="Unauthorised absence" align="center" style="background-color:#FFCCCC">';
				echo $row->unauthorised_absences.'<br/><span class="AttendancePercentage" style="font-size:80%">'.sprintf("%.1f",($row->unauthorised_absences/$total) * 100).'%</span></td>';
			}
			else
			{
				echo '<td class="AttendanceStatistic" title="Unauthorised absence" align="center">&nbsp;</td>';
			}

			if($row->dismissals_uniform > 0)
			{
				echo '<td class="AttendanceStatistic" title="Dismissal due to incorrect dress" align="center" style="background-color:#FFCCCC">';
				echo $row->dismissals_uniform.'<br/><span class="AttendancePercentage" style="font-size:80%">'.sprintf("%.1f",($row->dismissals_uniform/$total) * 100).'%</span></td>';
			}
			else
			{
				//echo '<td class="AttendanceStatistic" title="Dismissal due to incorrect dress" align="center">&nbsp;</td>';
			}

			if($row->dismissals_discipline > 0)
			{
				echo '<td class="AttendanceStatistic" title="Dismissal due to discliplinary offence" align="center" style="background-color:#FFCCCC">';
				echo $row->dismissals_discipline.'<br/><span class="AttendancePercentage" style="font-size:80%">'.sprintf("%.1f",($row->dismissals_discipline/$total) * 100).'%</span></td>';
			}
			else
			{
				//echo '<td class="AttendanceStatistic" title="Dismissal due to disciplinary offence" align="center">&nbsp;</td>';
			}

		}
	}

	/*
	public static function echoWeekSummary(PDO $link, $sql)
	{
		echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6" style="margin-left:10px;table-layout:fixed;width:400px">';
		echo "<col width=\"50\" /><col /><col /><col /><col /><col /><col /><col />\n";
		
		// Column headings
		echo '<tr>';
		echo '<th>&nbsp;</th>';
		//echo '<th width="30" title="Registers" align="center" style="font-size:6pt;color:gray" valign="middle">registers<br/><span style="border-top:1px silver solid">lessons</span></th>';
		AttendanceHelper::echoHeaderCells();
		echo "</tr>\n";	
		
		
		$st = $link->query($sql);
		if($st== false)
		{
			$weekday_column = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
			
			if(!$row = $st->fetch())
			{
				echo implode($link->errorInfo());
			}
			
			for($i = 0; $i < 8; $i++)
			{
				echo '<tr height="44">';
				
				if($i <= 6) // Sun -> Sat
				{
					echo '<td>'.$weekday_column[$i].'</td>';
				}
				else // summary row
				{
					echo '<td style="font-weight:bold">Total</td>';
				}
				
				if(!is_null($row) && array_key_exists('day', $row) && (($row['day'] - 1) == $i) )
				{
					AttendanceHelper::echoDataCells($row);
					$row = $st->fetch();
				}
				elseif(!is_null($row) && $i == 7)
				{
					// The ROLLUP summary row
					AttendanceHelper::echoDataCells($row);			
				}
				else
				{
					echo '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';
				}
				
				echo "</tr>\n";
			}
		}
		else
		{
			throw new Exception(implode($link->errorInfo()));
		}
		
		echo '</table>';		
	}
	*/

	public static function getSmallIcon($entry)
	{
		switch($entry)
		{
			case 1:
				return '<img src="/images/register/reg-attended-16.png" width="16" height="16" />';
				break;

			case 2:
				return '<img src="/images/register/reg-late-16.png" width="16" height="16" />';
				break;

			case 3:
				return '<img src="/images/register/reg-aa-16.png" width="16" height="16" />';
				break;

			case 4:
				return '<img src="/images/register/reg-mystery-16.png" width="16" height="16" />';
				break;

			case 5:
				return '<img src="/images/register/reg-ua-16.png" width="16" height="16" />';
				break;

			case 6:
				return '<img src="/images/register/reg-dismissal-28x16.png" width="28" height="16" />';
				break;

			case 7:
				return '<img src="/images/register/reg-dismissal-28x16.png" width="28" height="16" />';
				break;

			case 8:
				return '<img src="/images/register/reg-na-16.png" width="16" height="16" />';
				break;

			case 9:
				return '<img src="/images/register/reg-very-late-16.png" width="16" height="16" />';
				break;

			default:
				return '';
				break;
		}
	}


	public static function getSmallIconCaption($entry)
	{
		switch($entry)
		{
			case 1:
				return 'present';
				break;

			case 2:
				return 'late';
				break;

			case 3:
				return 'auth.';
				break;

			case 4:
				return 'unexp.';
				break;

			case 5:
				return 'unauth.';
				break;

			case 6:
				return 'uniform';
				break;

			case 7:
				return 'other';
				break;

			case 9:
				return 'very late';
				break;

			default:
				return '';
				break;
		}
	}

	public static function renderWeeklyAttendanceStatusIcons(PDO $link, $learnerId, $scheduleId)
	{
		$result = DAO::getResultset($link, "SELECT * FROM session_attendance WHERE schedule_id = '{$scheduleId}' AND learner_id = '{$learnerId}' ORDER BY attendance_date", DAO::FETCH_ASSOC);
		if( count($result) == 0 )
		{
			$schedule = DAO::getObject($link, "SELECT * FROM crm_training_schedule WHERE id = '{$scheduleId}'");
			$sd = new Date($schedule->training_date);
			for($i = 1; $i <= (int)$schedule->duration; $i++)
			{
				echo '<i title="' . $sd->format('l') . ': NOT RECORDED" class="fa fa-circle text-gray fa-lg"></i> &nbsp;';
				$sd->addDays(1);
			}
			
			return;
		}

		foreach($result AS $row)
		{
			$color = 'gray';
			$status = ': NOT RECORDED ';
			if($row['attendance_code'] == 1 || $row['attendance_code'] == 2)
			{
				$color = 'green';
				$status = ': ATTENDED';
			}
			if($row['attendance_code'] == 3 || $row['attendance_code'] == 5)
			{
				$color = 'red';
				$status = ': ABSENT';
			}
			echo '<i title="' . $row['attendance_day'] . $status . '" class="fa fa-circle text-' . $color . ' fa-lg"></i> &nbsp;';
		}
	}

	public static function duplexTrainingProgress(PDO $link, $trainingId)
	{
		$training = DAO::getObject($link, "SELECT * FROM training WHERE id = '{$trainingId}'");
		$schedule = DAO::getObject($link, "SELECT * FROM crm_training_schedule WHERE id = '{$training->schedule_id}'");

		$totalRegisters = 0;
		$sd = new Date($schedule->training_date);
		for($i = 1; $i <= (int)$schedule->duration; $i++)
		{
			if(!in_array($sd->format('l'), ["Saturday", "Sunday"]))
			{
				$totalRegisters++;
			}
			$sd->addDays(1);
		}

		$attended = DAO::getSingleValue($link, "SELECT COUNT(*) FROM session_attendance WHERE schedule_id = '{$schedule->id}' AND learner_id = '{$training->learner_id}' AND attendance_code IN (1, 2) ");
		
		$registersProgress = round( ($attended/$totalRegisters)*100 );
		$vocantoProgress = is_null($training->vocanto_progress) ? 0 : $training->vocanto_progress;

		$weightedRegistersProgress = $registersProgress * 0.55;
		$weightedVocantoProgress = $vocantoProgress * 0.45;

		$overallProgress = $weightedRegistersProgress + $weightedVocantoProgress;
		$overallProgress = round($overallProgress);


		echo "<br>Registers:&nbsp;{$registersProgress}%";
		echo "<br>Vocanto:&nbsp;{$vocantoProgress}%";
		echo "<br>Overall:&nbsp;{$overallProgress}%";

	}
}
?>