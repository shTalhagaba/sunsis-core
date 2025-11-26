<?php
class Ilr extends Entity
{

    public static function getAuditDetails($link, $entry_id)
    {
        $resultText = "";

        $st = $link->query("SELECT ilr_audit_trail_entry.* FROM ilr_audit_trail_entry INNER JOIN ilr_audit ON ilr_audit.id = ilr_audit_id WHERE DATE<'2018-09-10' and ilr_audit_id = " .$entry_id);
        if($st && $st->rowCount() > 0)
        {
            $resultText .= '<div align="left">';
            $resultText .= '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
            $resultText .= '<thead><tr><th class="topRow">Field Changed</th><th>Old Value</th><th>New Value</th></thead></tr>';
            $resultText .= '<tbody>';

            while($row = $st->fetch(PDO::FETCH_ASSOC))
            {
                $resultText .= "<tr>";
                $resultText .= "<td>" . $row['field_changed'] . "</td>";
                $resultText .= "<td>" . $row['old_value'] . "</td>";
                $resultText .= "<td>" . $row['new_value'] . "</td>";
                $resultText .= "</tr>";
            }
        }

        $st = $link->query("SELECT ilr_audit_trail_entry.* FROM ilr_audit_trail_entry INNER JOIN ilr_audit ON ilr_audit.id = ilr_audit_id WHERE DATE>='2018-09-10' and date<'2018-09-30' and ilr_audit_id = " .$entry_id);
        if($st && $st->rowCount() > 0)
        {
            $resultText .= '<div align="left">';
            $resultText .= '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
            $resultText .= '<thead><tr><th class="topRow">Entity</th><th>Field</th><th>Old Value</th><th>New Value</th></thead></tr>';
            $resultText .= '<tbody>';

            while($row = $st->fetch(PDO::FETCH_ASSOC))
            {
                $master_array = array();
                $old_rows = explode("$",$row['old_value']);
                $new_rows = explode("$",$row['new_value']);
                foreach($old_rows as $old_row)
                {
                    foreach($new_rows as $new_row)
                    {
                        $old_values = explode("|",$old_row);
                        $new_values = explode("|",$new_row);
                        // Display changes
                        foreach($old_values as $old_value)
                        {
                            foreach($new_values as $new_value)
                            {
                                if($old_values[0]==$new_values[0])
                                {
                                    if(strpos($old_value,"=") or strpos($new_value,"="))
                                    {
                                        $old_data = explode("=",$old_value);
                                        $new_data = explode("=",$new_value);
                                        if($old_data[0]==$new_data[0] and $old_data[1]!=$new_data[1])
                                        {
                                            $master_value = $new_values[0].$old_data[0].$old_data[1].$new_data[1];
                                            if(!in_array($master_value,$master_array))
                                            {
                                                $resultText .= "<tr>";
                                                $resultText .= "<td>" . $new_values[0] . "</td>";
                                                $resultText .= "<td>" . $old_data[0] . "</td>";
                                                $resultText .= "<td>" . $old_data[1] . "</td>";
                                                $resultText .= "<td>" . $new_data[1] . "</td>";
                                                $master_array[] = $master_value;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        // Display Removals
                        foreach($old_values as $old_value)
                        {
                            if(strpos($old_value,"="))
                            {
                                $exists = false;
                                $old_data = explode("=",$old_value);
                                // Check if it does not exists in the new data
                                foreach($new_values as $new_value)
                                {
                                    if(strpos($new_value,"="))
                                    {
                                        $new_data = explode("=",$new_value);
                                        if($old_data[0]==$new_data[0])
                                            $exists = true;
                                    }
                                }
                                if($exists==false)
                                {
                                    $master_value = $old_values[0].$old_data[0].$old_data[1];
                                    if(!in_array($master_value,$master_array))
                                    {
                                        $resultText .= "<tr>";
                                        $resultText .= "<td>" . $old_values[0] . "</td>";
                                        $resultText .= "<td>" . $old_data[0] . "</td>";
                                        $resultText .= "<td>" . $old_data[1] . "</td>";
                                        $resultText .= "<td>&nbsp;</td>";
                                        $master_array[] = $master_value;
                                    }
                                }
                            }
                        }
                        // Display Additions
                        foreach($new_values as $new_value)
                        {
                            if(strpos($new_value,"="))
                            {
                                $exists = false;
                                $new_data = explode("=",$new_value);
                                // Check if it does not exists in the old data
                                foreach($old_values as $old_value)
                                {
                                    if(strpos($old_value,"="))
                                    {
                                        $old_data = explode("=",$old_value);
                                        if($new_data[0]==$old_data[0])
                                            $exists = true;
                                    }
                                }
                                if($exists==false)
                                {
                                    $master_value = $new_values[0].$new_data[0].$new_data[1];
                                    if(!in_array($master_value,$master_array))
                                    {
                                        $resultText .= "<tr>";
                                        $resultText .= "<td>" . $new_values[0] . "</td>";
                                        $resultText .= "<td>" . $new_data[0] . "</td>";
                                        $resultText .= "<td>&nbsp;</td>";
                                        $resultText .= "<td>" . $new_data[1] . "</td>";
                                        $master_array[] = $master_value;
                                    }
                                }
                            }
                        }
                    }
                    $resultText .= "</tr>";
                }
            }

            $resultText .= '</tbody>';
            $resultText .= '</table></div>';
        }


        $st = $link->query("SELECT ilr_audit_trail_entry.* FROM ilr_audit_trail_entry INNER JOIN ilr_audit ON ilr_audit.id = ilr_audit_id WHERE DATE>='2018-09-30' and ilr_audit_id = " .$entry_id);
        if($st && $st->rowCount() > 0)
        {
            $resultText .= '<div align="left">';
            $resultText .= '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
            $resultText .= '<thead><tr><th class="topRow">Entity</th><th>Field</th><th>Previous Value</th><th>New Value</th></thead></tr>';
            $resultText .= '<tbody>';

            while($row = $st->fetch(PDO::FETCH_ASSOC))
            {
                $tr_id = $row['ilr_audit_id'];
                $new_rows = explode("$",$row['new_value']);
                rsort($new_rows);
                foreach($new_rows as $new_row)
                {
                    $new_values = explode("|",$new_row);
                    if($new_values[0]!='')
                    {
                        $resultText .= "<tr>";
                        $resultText .= "<td>" . $new_values[0] . "</td>";
                        $v1 = isset($new_values[1])?$new_values[1]:'&nbsp;';
                        $v2 = isset($new_values[2])?$new_values[2]:'&nbsp;';
                        $v3 = isset($new_values[3])?$new_values[3]:'&nbsp;';
                        $resultText .= "<td>" . $v1 . "</td>";
                        $resultText .= "<td>" . $v2 . "</td>";
                        $resultText .= "<td>" . $v3 . "</td>";
                        $resultText .= "</tr>";
                    }
                }
            }

            $resultText .= '</tbody>';
            $resultText .= '</table></div>';
        }
        return $resultText;
    }

	public static function loadFromDatabase(PDO $link, $id)
	{
		$key = 'view_'.__CLASS__;
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		
		$query = "SELECT * FROM ilr WHERE tr_id=" . addslashes((string)$id) . ";";
		$st = $link->query($query);

		$pot = null;
		if($st)
		{
			$row = $st->fetch();
			if($row)
			{
				$pot = new Ilr();
				$pot->populate($row);
			}
			else
			{
				$pot = new Ilr();
			}
		}
		else
		{
			$pot = new Ilr();
	
			//Throw new Exception(implode($link->errorInfo()));
		}

		return $pot;
	}
	
	public function render(PDO $link, $sub)
	{
		/* @var $result pdo_result */
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		$vo = Contract::loadFromDatabase($link, $id);
		
$sql = "Select count(*) from ilr where submission='$sub' and contract_id='$id'";
$no_of_ilrs = DAO::getResultset($link, $sql);

$sql = "Select count(*) from ilr where submission='$sub' and contract_id='$id' and is_approved=1";
$approved = DAO::getResultset($link, $sql);

$sql = "Select count(*) from ilr where submission='$sub' and contract_id='$id' and is_complete=1";
$complete = DAO::getResultset($link, $sql);

$sql = "Select count(*) from ilr where submission='$sub' and contract_id='$id' and is_valid=1";
$valid = DAO::getResultset($link, $sql);

$sql = "Select count(*) from ilr where submission='$sub' and contract_id='$id' and is_active=1";
$active = DAO::getResultset($link, $sql);

$sql = "Select count(*) from ilr where submission='$sub' and contract_id='$id' and is_approved=0 and is_active=1";
$active_unapproved = DAO::getResultset($link, $sql);

$sql = "select DATE_FORMAT(last_submission_date,'%d-%m-%Y') as last_submission_date from central.lookup_submission_dates where submission = '$sub' and contract_type = '$vo->funding_body' and contract_year = (select contract_year from contracts where id='$id');";
$submission_date = DAO::getSingleValue($link, $sql);

$sql = "select IF(CURDATE()>last_submission_date,'Passed','NotPassed') from central.lookup_submission_dates where submission = '$sub' and contract_type = '$vo->funding_body' and contract_year = (select contract_year from contracts where id='$id');";
$submission_date_passed = DAO::getSingleValue($link, $sql);

$previous_submission = 'W' . str_pad(substr($sub,1,2)-1,2,'0',0); 

$sql = "select IF(CURDATE()>last_submission_date,'Passed','NotPassed') from central.lookup_submission_dates where submission = '$previous_submission' and contract_type = '$vo->funding_body' and contract_year = (select contract_year from contracts where id='$id');";
$previous_submission_date_passed = DAO::getSingleValue($link, $sql);

$sql = "select profile from lookup_profile_values where submission = '$sub' and contract_id = '$id'";
$profile = DAO::getSingleValue($link, $sql);

$sql = "select profile from lookup_pfr_values where submission = '$sub' and contract_id = '$id'";
$pfr = DAO::getSingleValue($link, $sql);

			if((int)$active_unapproved[0][0]==0 && (int)$active[0][0] > 0)
				$action="Download";
			if((int)$active_unapproved[0][0] > 0)
				$action="Review";
			if((int)$active[0][0] < 1)
				$action = "Create";
				
			//throw new Exception($previous_submission_date_passed.$sub);	
			if($previous_submission=="W00")
				echo HTML::viewrow_opening_tag('do.php?_action=view_ilrs&id=' . $sub.$id . '&passed='.$submission_date_passed);
			else
				if($previous_submission_date_passed=='Passed')
					echo HTML::viewrow_opening_tag('do.php?_action=view_ilrs&id=' . $sub.$id . '&passed='.$submission_date_passed);

			echo '<td><img src="/images/rosette.gif" /></td>';

			$contract_year = DAO::getSingleValue($link,"select contract_year from contracts where id = '$id'");
			$co = Contract::loadFromDatabase($link, $id);	
			
			if($co->funding_body == '1')
				$sub = str_replace("W","LR",$sub);
			else
				if($contract_year>2009)
					$sub = str_replace("W","ER",$sub);
			
			echo '<td align="center">' . HTML::cell($sub) . "</td>";

			echo '<td align="center">' . HTML::cell($submission_date) . "</td>";
			
			$no_of_valid = $valid[0][0]." / ". ((int)$no_of_ilrs[0][0]-(int)$valid[0][0]);
			echo '<td align="center">' . HTML::cell($no_of_valid) . "</td>";
			$no_of_approved = $approved[0][0]." / ". ((int)$no_of_ilrs[0][0]-(int)$approved[0][0]);
			echo '<td align="center">' . HTML::cell($no_of_approved) . "</td>";
			$no_of_active   = $active[0][0]." / ".   ((int)$no_of_ilrs[0][0]-(int)$active[0][0]);
			echo '<td align="center">' . HTML::cell($no_of_active) . "</td>";

			echo '<td align="center">' . HTML::cell($profile) . "</td>";
			echo '<td align="center">' . HTML::cell($pfr) . "</td>";
			
			
			if((int)$active_unapproved[0][0]==0 && (int)$active[0][0] > 0)
				echo '<td align="left" bgcolor="#33CC33">' . HTML::cell($action) . "</td>";
			if((int)$active_unapproved[0][0] > 0)
				echo '<td align="left" bgcolor="#FFFF00">' . HTML::cell($action) . "</td>";
			if((int)$active[0][0] < 1)
				echo '<td align="left" bgcolor="#FF0000">' . HTML::cell($action) . "</td>";
		
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
/*	public $provider_location_id = NULL;
	public $provider_paon_start_number = NULL;
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