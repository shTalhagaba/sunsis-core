<?php
class TrainingRecordIlr extends Entity 
{
	public static function loadFromDatabase(PDO $link, $id)
	{
		$key = 'view_'.__CLASS__;
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		
		$query = "SELECT * FROM tr WHERE status_code='1' and contract_id='$id';";
		$st = $link->query($query);

		$pot = null;
		if($st)
		{
			$row = $st->fetch();
			if($row)
			{
				$pot = new TrainingRecordIlr();
				$pot->populate($row);
			}
			else
			{
				$pot = new TrainingRecordIlr();
			}
		}
		else
		{
			$pot = new TrainingRecordIlr();
	
			//Throw new Exception(implode($link->errorInfo()));
		}

		return $pot;
	}
	
	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		
$sql = <<<HEREDOC
SELECT
	*
FROM
	tr where status_code=1 and contract_id='$id';
HEREDOC;

		$st = $link->query($sql);
		if($st) 
		{
			// echo $this->getViewNavigator('left');
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th> Name </th> <th> Employer </th><th> Provider </th><th> Start Date </th> </tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . rawurlencode($row['id']));
				echo '<td><img src="/images/rosette.gif" /></td>';
				echo '<td align="left">' . HTML::cell($row['surname']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['employer_id']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['provider_id']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['start_date']) . "</td>";
//				echo '<td align="left">' . htmlspecialchars((string)$row['postcode']) . "</td>";
//				echo '<td align="left">' . htmlspecialchars((string)$row['town']) . "</td>";
//				echo '<td align="left">' . htmlspecialchars((string)$row['gender']) . "</td>";
				echo '</tr>';
			}
			echo '</tbody></table></div align="center">';
			// echo $this->getViewNavigator('left');
			
		}
		else
		{
			echo "<p> No Training Record belong to this contract </p>";
			//throw new Exception(implode($link->errorInfo()));
		}
		
	}
	
	public function save(PDO $link)
	{
		return DAO::saveObjectToTable($link, 'tr', $this);		
	}
	
	
	public function delete(PDO $link)
	{
		if(!$this->isSafeToDelete($link))
		{
			throw new Exception("PeriodOfTraining #{$this->id} cannot be deleted");
		}
		
		$query = "DELETE FROM tr WHERE id={$this->id};";
		DAO::execute($link, $query);

		return true;		
	}
	
	
	public function isSafeToDelete(PDO $link)
	{
		return false;
	}
	

	/**
	 * Overridden method
	 * @param pdo $link
	 * @param ValueObject $new_object
	 * @param array $exclude_fields
	 */
	public function buildAuditLogString(PDO $link, Entity $new_vo, array $exclude_fields = array())
	{
		if(count($exclude_fields) == 0)
		{
			// These fields use lookup codes
			$exclude_fields = array('ethnicity', 'gender', 'status_code');
		}
		
		$changes_list = parent::buildAuditLogString($link, $new_vo, $exclude_fields);
		
		// Test each of the exceptions separately
		if($this->ethnicity != $new_vo->ethnicity)
		{
			$lookup = DAO::getLookupTable($link, "SELECT id, description FROM lookup_ethnicity ORDER BY id");
			$changes_list .= "[ethnicity] changed from '{$lookup[$this->ethnicity]}' to '{$lookup[$new_vo->ethnicity]}'\n";
		}
		if($this->gender != $new_vo->gender)
		{
			$lookup = DAO::getLookupTable($link, "SELECT id, description FROM lookup_gender ORDER BY id");
			$changes_list .= "[gender] changed from '{$lookup[$this->gender]}' to '{$lookup[$new_vo->gender]}'\n";
		}
		if($this->status_code != $new_vo->status_code)
		{
			$lookup = DAO::getLookupTable($link, "SELECT code, description FROM lookup_pot_status ORDER BY code");
			$changes_list .= "[status] changed from '{$lookup[$this->status_code]}' to '{$lookup[$new_vo->status_code]}'\n";
		}
		
		return $changes_list;
	}	
	
	
	/**
	 * @param mixed $id A numeric id, an array of numeric ids or a SQL
	 * query that produces a list of numeric ids
	 */
	public function updateAttendanceStatistics()
	{

	}
	
	
	public function updateProgressStatistics()
	{

	}


	
	
	
	public $id = NULL;
	public $username = NULL;
	public $programme = NULL;
	public $cohort = NULL;
	public $start_date = NULL;
	public $target_date = NULL;
	public $closure_date = NULL;
	public $status_code = NULL;
	
	// SNAPSHOT FROM LEARNER RECORD
	public $school_id = NULL;
	public $firstnames = NULL;
	public $surname = NULL;
	public $gender = NULL;
	public $ethnicity = NULL;
	public $dob = NULL;
	public $uln = NULL;
	public $upi = NULL;
	public $upn = NULL;
	public $ni = NULL;

/*	public $home_paon_start_number = NULL;
	public $home_paon_start_suffix = NULL;
	public $home_paon_end_number = NULL;
	public $home_paon_end_suffix = NULL;
	public $home_paon_description = NULL;
	public $home_saon_start_number = NULL;
	public $home_saon_start_suffix = NULL;
	public $home_saon_end_number = NULL;
	public $home_saon_end_suffix = NULL;
	public $home_saon_description = NULL;
	public $home_street_description = NULL;
	public $home_locality = NULL;
	public $home_town = NULL;
	public $home_county = NULL;*/
	public $home_address_line_1 = NULL;
	public $home_address_line_2 = NULL;
	public $home_address_line_3 = NULL;
	public $home_address_line_4 = NULL;
	public $home_postcode = NULL;
	public $home_email = NULL;
	public $home_telephone = NULL;
	public $home_mobile = NULL;
	
	public $employer_id = NULL;
	public $employer_location_id = NULL;
/*	public $work_paon_start_number = NULL;
	public $work_paon_start_suffix = NULL;
	public $work_paon_end_number = NULL;
	public $work_paon_end_suffix = NULL;
	public $work_paon_description = NULL;
	public $work_saon_start_number = NULL;
	public $work_saon_start_suffix = NULL;
	public $work_saon_end_number = NULL;
	public $work_saon_end_suffix = NULL;
	public $work_saon_description = NULL;
	public $work_street_description = NULL;
	public $work_locality = NULL;
	public $work_town = NULL;
	public $work_county = NULL;*/
	public $work_address_line_1 = NULL;
	public $work_address_line_2 = NULL;
	public $work_address_line_3 = NULL;
	public $work_address_line_4 = NULL;
	public $work_postcode = NULL;
	public $work_email = NULL;
	public $work_telephone = NULL;
	public $work_mobile = NULL;
	
	public $provider_id = NULL;
	public $provider_location_id = NULL;
/*	public $provider_paon_start_number = NULL;
	public $provider_paon_start_suffix = NULL;
	public $provider_paon_end_number = NULL;
	public $provider_paon_end_suffix = NULL;
	public $provider_paon_description = NULL;
	public $provider_saon_start_number = NULL;
	public $provider_saon_start_suffix = NULL;
	public $provider_saon_end_number = NULL;
	public $provider_saon_end_suffix = NULL;
	public $provider_saon_description = NULL;
	public $provider_street_description = NULL;
	public $provider_locality = NULL;
	public $provider_town = NULL;
	public $provider_county = NULL;*/
	public $provider_address_line_1 = NULL;
	public $provider_address_line_2 = NULL;
	public $provider_address_line_3 = NULL;
	public $provider_address_line_4 = NULL;
	public $provider_postcode = NULL;
	public $provider_email = NULL;
	public $provider_telephone = NULL;	
	
	
	// ATTENDANCE STATISTICS
	public $scheduled_lessons = null;
	public $registered_lessons = null;
	public $attendances = null;
	public $lates = null;
	public $authorised_absences = null;
	public $unexplained_absences = null;
	public $unauthorised_absences = null;
	public $dismissals_uniform = null;
	public $dismissals_discipline = null;
	
	public $units_total = null;
	public $units_not_started = null;
	public $units_behind = null;
	public $units_on_track = null;
	public $units_under_assessment = null;
	public $units_completed = null;
}
?>