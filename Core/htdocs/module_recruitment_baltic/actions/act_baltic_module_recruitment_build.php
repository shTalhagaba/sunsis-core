<?php

class baltic_module_recruitment_build implements IAction
{
	public function execute(PDO $link)
	{	
		
		// allow only admin logins to view status 
		if ( !$this->check_module_manager() ) {	
			throw new Exception ('Unknown action: module_recruitment_build');
		}
		
		// load all the postcodes up
		if ( isset($_REQUEST['validate_postcodes']) ) {
			echo $this->present_location_setup($link);
			exit;
		}

		// update the postcode
		if ( isset($_REQUEST['loca_postcode_update']) ) {
			$sql_location_data = "update locations set easting='".$_REQUEST['e']."', northing='".$_REQUEST['n']."', longitude='".$_REQUEST['lon']."', latitude='".$_REQUEST['lat']."' where postcode ='".$_REQUEST['loca_postcode_update']."'";
			$st = $link->query($sql_location_data);

			// update the vacancies also
			$sql_location_data = "update vacancies set easting='".$_REQUEST['e']."', northing='".$_REQUEST['n']."', longitude='".$_REQUEST['lon']."', latitude='".$_REQUEST['lat']."' where postcode ='".$_REQUEST['loca_postcode_update']."'";
			$st = $link->query($sql_location_data);
			
			// add the postcode to the lookup table
			$postcode_elements = preg_split("/ /", $_REQUEST['loca_postcode_update']);
			$set_postcode_sql = 'insert into central.lookup_postcode_geolocation (outward, inward, easting, northing, latitude, longitude, source ) ';
			$set_postcode_sql .= 'values ("'.$postcode_elements[0].'", "'.$postcode_elements[1].'", "'.$_REQUEST['e'].'", "'.$_REQUEST['n'].'", "'.$_REQUEST['lat'].'", "'.$_REQUEST['lon'].'", "Sunesis")';
			$st = $link->query($set_postcode_sql);
		}

		// update the postcode
		if ( isset($_REQUEST['cand_postcode_update']) ) {
			$sql_location_data = "update candidate set easting='".$_REQUEST['e']."', northing='".$_REQUEST['n']."', longitude='".$_REQUEST['lon']."', latitude='".$_REQUEST['lat']."' where postcode ='".$_REQUEST['cand_postcode_update']."'";
			$st = $link->query($sql_location_data);

			// add the postcode to the lookup table
			$postcode_elements = preg_split("/ /", $_REQUEST['cand_postcode_update']);
			$set_postcode_sql = 'insert into central.lookup_postcode_geolocation (outward, inward, easting, northing, latitude, longitude, source ) ';
			$set_postcode_sql .= 'values ("'.$postcode_elements[0].'", "'.$postcode_elements[1].'", "'.$_REQUEST['e'].'", "'.$_REQUEST['n'].'", "'.$_REQUEST['lat'].'", "'.$_REQUEST['lon'].'", "Sunesis")';
			$st = $link->query($set_postcode_sql);
		}
		
		
		// allow only perspective logins, from perspective offices to manage configuration
		if ( !SOURCE_BLYTHE_VALLEY && !SOURCE_LOCAL ) {
			http_redirect($_SESSION['bc']->getCurrent());
		}
		
		$_SESSION['bc']->add($link, "do.php?_action=recruitment_module_build", "Recruitment Module Manager");

		// check all tables are present
		$table_exist_array = $this->check_table_setup($link);
		
		// do any table set up required
		// check if the table doesn't exist 
		// at this point
		if ( isset($_REQUEST['table']) && !isset($this->actual_db_tables[$_REQUEST['table']]) ) {

			if ( isset($this->sql_tables[$_REQUEST['table']]) ) {	
				$sql = $this->sql_tables[$_REQUEST['table']];
				$st = $link->query($sql);
			}
		}
		
		// do any table column set up required
		if ( isset($_REQUEST['table_columns']) && isset($this->actual_db_tables[$_REQUEST['table_columns']]) ) {
						
			// columns that are missing from the table
			$columns_to_create = array_diff_key($this->db_tables[$_REQUEST['table_columns']], $this->actual_db_tables[$_REQUEST['table_columns']]);
			
			// create the columns
			foreach( $columns_to_create as $column_name => $column_type ) {
				$table_modify_sql = 'ALTER TABLE '.$_REQUEST['table_columns'].' ADD COLUMN '.$column_name.' '.$column_type;	
				$st = $link->query($table_modify_sql);	
			}

			// redo the table check
			$table_exist_array = $this->check_table_setup($link);

			// columns that are incorrectly defined
			$columns_to_modify = array_diff_assoc($this->db_tables[$_REQUEST['table_columns']], $this->actual_db_tables[$_REQUEST['table_columns']]);
			
			// modify columns
			foreach( $columns_to_modify as $column_name => $column_type ) {
				$table_modify_sql = 'ALTER TABLE '.$_REQUEST['table_columns'].' MODIFY COLUMN '.$column_name.' '.$column_type;	
				$st = $link->query($table_modify_sql);	
			}		
		}
		
		// set up of core data requirement
		// - could do some checking on this to 
		// - only allow if data not set up enough
		if( isset($_REQUEST['initial']) ) {
			foreach( $this->sql_setup as $table_name => $table_updates ) {			
				$st = $link->query($table_updates);		
			}
		}
		
		// redo the table check
		$table_exist_array = $this->check_table_setup($link);
		
		$current_table_build_html = '<table class="resultset" ><thead><tr><th>Table Name</th><th>Present</th><th>Build</th><th>Configuration</th><th>Update</th></tr></thead><tbody>';
		foreach( $this->db_tables as $table_name => $table_columns ) {
			$current_table_build_html .= '<tr><td>'.$table_name.'</td>';
			if ( isset($this->actual_db_tables[$table_name]) ) {
				$current_table_build_html .= '<td><img src="images/green-tick.gif" /></td><td></td>';
				$current_table_build_html .= $this->check_table_columns($table_name);
				$current_table_build_html .= '</tr>';
			}
			else {
				$current_table_build_html .= '<td><img src="images/notice_icon_red.gif" /></td><td><a href="/do.php?_action=module_recruitment_build&amp;table='.$table_name.'">build table &raquo;</a></td>';
				$current_table_build_html .= '<td>&nbsp;</td><td>&nbsp;</td>';
				$current_table_build_html .= '</tr>';
			}	
		}		
		$current_table_build_html .= '</tbody></table>';
		
		require_once('tpl_baltic_module_recruitment_build.php');
	}
	
	/**
	 * Check the user location & login
	 */
	private function check_module_manager() {
				
		if ( isset($_SESSION['user']->username) && $_SESSION['user']->username == 'admin' ) {		
			return true;
		}		
		return false;
	}
	
	/**
	 * 
	 * Check tables are present in the system
	 * @param PDO $link
	 */
	private function check_table_setup(PDO $link) {

		$table_checks = implode("','", array_keys($this->db_tables));
		
		$sql_table_data = "select * from information_schema.tables WHERE TABLE_SCHEMA = '".DB_NAME."'  and TABLE_NAME in ('".$table_checks."')";
		
		if( $st = $link->query($sql_table_data) ) {
			while( $row_table_data = $st->fetch() ) {
				
				$table_name = $row_table_data['TABLE_NAME'];
				
				// builds the top level table info
				if ( !isset($this->actual_db_tables[$table_name]) ) {
					$this->actual_db_tables[$table_name] = array();
				}
				
				// gets all the table details
				$sql_table_columns = "show columns from $table_name;";
				if( $st_table_columns = $link->query($sql_table_columns) ) {
				 	while( $row_table_columns = $st_table_columns->fetch() ) {
				 		$this->actual_db_tables[$table_name][$row_table_columns['Field']] = $row_table_columns['Type'];
				 	}
				}
			}
		}
		
		// returns any differences in the actual against expected tables ( do they exist or not only ).
		return array_diff_key($this->db_tables, $this->actual_db_tables);
	}
	
	
	/**
	 * 
	 * Check the table columns etc are correct
	 */
	private function check_table_columns($table_name = '') {
		
		if ( $table_name == '' ) {		
			return null;
		}
		
		$current_table_build_html = '';

		//compare the content of db_tables & actual_db_tables
		$this_table_differences = array_diff_key($this->db_tables[$table_name], $this->actual_db_tables[$table_name]);
		
		// columns that are incorrectly defined
		$columns_to_modify = array_diff_assoc($this->db_tables[$table_name], $this->actual_db_tables[$table_name]);

		// missing columns
		if ( sizeof($this_table_differences) || sizeof($columns_to_modify)  ) {		
			$current_table_build_html .= '<td><img src="images/notice_icon_red.gif" /></td><td><a href="/do.php?_action=module_recruitment_build&amp;table_columns='.$table_name.'">update columns &raquo;</a></td>';
				
		}
		else {
			$current_table_build_html .= '<td><img src="images/green-tick.gif" /></td><td>&nbsp;</td>';	
		}
		
		return $current_table_build_html;
		
	}
	
	/**
	 * 
	 * Check location data is set up in the system
	 * @param PDO $link
	 */
	private function check_location_setup(PDO $link)
	{
		$sql_location_data = <<<SQL
SELECT
  organisations_id,
  id,
  CONCAT(
    IFNULL(address_line_1, ''),
 	'+',
    IFNULL(address_line_3, ''),
    '+',
    IFNULL(address_line_4, '')
  ) AS fulladdress,
  postcode
FROM
  locations
WHERE easting IS NULL
  AND postcode IS NOT NULL
SQL;

		if( $st = $link->query($sql_location_data) ) {
			while( $row_location_data = $st->fetch() ) {			
				if ( !isset($this->location_data[$row_location_data['postcode']]) && $row_location_data['postcode'] != '' ) {
					$loc = new GeoLocation();
					$loc->setPostcode($row_location_data['postcode'], $link);
					$longitude = $loc->getLongitude();
					$latitude = $loc->getLatitude();
					$easting = $loc->getEasting();
					$northing = $loc->getNorthing();
					$this->location_data[$row_location_data['postcode']] = array(
						'address' => $row_location_data['fulladdress'].' <a href="http://maps.google.co.uk/maps?q='.$row_location_data['fulladdress'].'&hl=en" target="_blank">find in google</a>&nbsp;<a href="do.php?_action=read_location&organisation_id='.$row_location_data['organisations_id'].'&id='.$row_location_data['id'].'" target="_blank">go to location</a>',
						'lon' 	=> $longitude,
						'lat' 	=> $latitude,
						'east'	=> $easting,
						'north'	=> $northing
					);
				}
			}
		}
	}

	/**
	 *
	 * Check location data is set up in the system
	 * @param PDO $link
	 */
	private function check_candidate_setup(PDO $link) {

		$sql_location_data = "select CONCAT(address2, '+', county) as fulladdress, postcode from candidate WHERE easting IS NULL and postcode is not NULL";

		if( $st = $link->query($sql_location_data) ) {
			while( $row_location_data = $st->fetch() ) {
				if ( !isset($this->candidate_data[$row_location_data['postcode']]) && $row_location_data['postcode'] != '' ) {
					$loc = new GeoLocation();
					$loc->setPostcode($row_location_data['postcode'], $link);
					$longitude = $loc->getLongitude();
					$latitude = $loc->getLatitude();
					$easting = $loc->getEasting();
					$northing = $loc->getNorthing();
					$this->candidate_data[$row_location_data['postcode']] = array(
						'address' => $row_location_data['fulladdress'].' <a href="http://maps.google.co.uk/maps?q='.$row_location_data['fulladdress'].'&hl=en" target="_blank">find in google</a>',
						'lon' 	=> $longitude,
						'lat' 	=> $latitude,
						'east'	=> $easting,
						'north'	=> $northing
					);
				}
			}
		}
	}

	private function present_location_setup(PDO $link) {
		
		$this->check_location_setup($link);

		$postcode_issues_html = '<h3>Verify Postcodes for Locations / Vacancies</h3>';
		$postcode_issues_html .= '<table class="resultset"><thead><tr><th>Postcode</th><th>Easting</th><th>Northing</th><th>Longitude</th><th>Latitude</th><th>Update</th><th></tr></thead><tbody>';
		foreach( $this->location_data as $postcode_name => $postcode_value ) {
			$postcode_issues_html .= '<tr class="shortrecord" ><td>'.$postcode_name.'</td>';
			$postcode_issues_html .= '<td>'.$postcode_value['east'].'</td>';
			$postcode_issues_html .= '<td>'.$postcode_value['north'].'</td>';
			$postcode_issues_html .= '<td>'.$postcode_value['lon'].'</td>';
			$postcode_issues_html .= '<td>'.$postcode_value['lat'].'</td>';
			if ( $postcode_value['east'] != '' ) {
				$postcode_issues_html .= '<td><a href="/do.php?_action=module_recruitment_build&amp;loca_postcode_update='.$postcode_name.'&amp;e='.$postcode_value['east'].'&amp;n='.$postcode_value['north'].'&amp;lat='.$postcode_value['lat'].'&amp;lon='.$postcode_value['lon'].'" class="button">update</a></td>';
				$sql_location_data = "update locations set easting='".$postcode_value['east']."', northing='".$postcode_value['north']."', longitude='".$postcode_value['lon']."', latitude='".$postcode_value['lat']."' where postcode ='".$postcode_name."'";
				$this->sql_for_bulk .= $sql_location_data.";\n";
				$sql_location_data = "update vacancies set easting='".$postcode_value['east']."', northing='".$postcode_value['north']."', longitude='".$postcode_value['lon']."', latitude='".$postcode_value['lat']."' where postcode ='".$postcode_name."'";
				$this->sql_for_bulk .= $sql_location_data.";\n";
			}
			else {
				$postcode_issues_html .= '<td>!cannot_find! '.$postcode_value['address'].'</td>';
			}
			$postcode_issues_html .= '</tr>';
		}
		$postcode_issues_html .= '</tbody></table>';
		$this->sql_for_bulk .= "\n\n";

		$this->check_candidate_setup($link);

		$postcode_issues_html .= '<h3>Verify Postcodes for Candidates</h3>';
		$postcode_issues_html .= '<table class="resultset"><thead><tr><th>Postcode</th><th>Easting</th><th>Northing</th><th>Longitude</th><th>Latitude</th><th>Update</th><th></tr></thead><tbody>';
		foreach( $this->candidate_data as $postcode_name => $postcode_value ) {
			$postcode_issues_html .= '<tr class="shortrecord" ><td>'.$postcode_name.'</td>';
			$postcode_issues_html .= '<td>'.$postcode_value['east'].'</td>';
			$postcode_issues_html .= '<td>'.$postcode_value['north'].'</td>';
			$postcode_issues_html .= '<td>'.$postcode_value['lon'].'</td>';
			$postcode_issues_html .= '<td>'.$postcode_value['lat'].'</td>';
			if ( $postcode_value['east'] != '' ) {
				$postcode_issues_html .= '<td><a href="/do.php?_action=module_recruitment_build&amp;cand_postcode_update='.$postcode_name.'&amp;e='.$postcode_value['east'].'&amp;n='.$postcode_value['north'].'&amp;lat='.$postcode_value['lat'].'&amp;lon='.$postcode_value['lon'].'" class="button">update</a></td>';
				$sql_location_data = "update candidate set easting='".$postcode_value['east']."', northing='".$postcode_value['north']."', longitude='".$postcode_value['lon']."', latitude='".$postcode_value['lat']."' where postcode ='".$postcode_name."'";
				$this->sql_for_bulk .= $sql_location_data.";\n";
			}
			else {
				$postcode_issues_html .= '<td>!cannot find!: '.$postcode_value['address'].'</td>';
			}
			$postcode_issues_html .= '</tr>';
		}
		$postcode_issues_html .= '</tbody></table>';


//		$postcode_issues_html .= '<pre style="text-align:left">'.$this->sql_for_bulk.'</pre>';

		// this is a temporary section to populate up the geolocation table from multimap
		//
		//$sql_location_data = "select postcode from central.lookup_postcode_la";
		//
		//if( $st = $link->query($sql_location_data) ) {
		//	while( $row_location_data = $st->fetch() ) {			
		//		if ( !isset($this->location_data[$row_location_data['postcode']]) && $row_location_data['postcode'] != '' ) {
		//			$loc = new GeoLocation();
		//			$loc->setPostcode($row_location_data['postcode'], $link);
		//			$longitude = $loc->getLongitude();
		//			$latitude = $loc->getLatitude();
		//			$easting = $loc->getEasting();
		//			$northing = $loc->getNorthing();
		//			$this->location_data[$row_location_data['postcode']] = array(
		//				'lon' 	=> $longitude,
		//				'lat' 	=> $latitude,
		//				'east'	=> $easting,
		//				'north'	=> $northing
		//			);
		//		}
		//	}
		//}
		
		return $postcode_issues_html;
	}
	
	/**
	 * 
	 * Create the data base tables
	 * @param PDO $link
	 */
	private function build_tables(PDO $link) {
		
		
	}
	
	// all tables we need to run recruitment manager.
	// this needs to be pulled in from external file based upon 
	// the version of recruitment manager running
	private $db_tables = array(
	
		'candidate' => array(
                    'id' => 'int(6) unsigned',
                    'firstnames' => 'varchar(50)',
                    'surname' => 'varchar(50)',
                    'gender' => 'enum(\'F\',\'M\',\'U\',\'W\')',
                    'ethnicity' => 'int(2)',
                    'dob' => 'date',
                    'national_insurance' => 'varchar(13)',
                    'address1' => 'varchar(100)',
                    'address2' => 'varchar(100)',
                    'borough' => 'int(3)',
                    'county' => 'varchar(100)',
                    'postcode' => 'varchar(8)',
                    'telephone' => 'varchar(20)',
                    'mobile' => 'varchar(20)',
                    'fax' => 'varchar(20)',
                    'email' => 'varchar(180)',
                    'employment_status' => 'int(1) unsigned zerofill',
                    'hours_per_week' => 'float(5,2)',
                    'time_last_worked' => 'int(2)',
                    'last_education' => 'int(1)',
                    'previous_qualification' => 'tinyint(1)',
                    'created' => 'timestamp',
                    'longitude' => 'double',
                    'latitude' => 'double',
                    'northing' => 'int(11)',
                    'easting' => 'int(11)',
                    'enrolled' => 'int(10)',
                    'username' => 'varchar(45)',
                    'status' => 'int(1)',
                    'assessor' => 'varchar(45)',
                    'screening_score' => 'int(3)',
                    'comment' => 'text',
                    'status_code' => 'varchar(50)',
					// re: 05/09/2011
					//  added in region on 
					//  a candidate basis
					'region' => 'varchar(50)',
                ),
            'candidate_applications' => Array(
                    'application_id' => 'int(10) unsigned',
                    'candidate_id' => 'int(6)',
                    'vacancy_id' => 'int(10)',
                    'application_comments' => 'varchar(500)',
                    'application_status' => 'int(2)',
                    'application_screening' => 'int(3)',
                    'has_been_screened' => 'int(11)',
                ),
           'candidate_difficulty' => Array(
                    'id' => 'int(8) unsigned',
                    'candidate_id' => 'int(6) unsigned',
                    'difficulty_code' => 'smallint(5)',
                ),
           'candidate_disability' => Array(
                    'id' => 'int(8) unsigned',
                    'candidate_id' => 'int(6) unsigned',
                    'disability_code' => 'smallint(5)',
                ),
            'candidate_history' => Array(
                    'id' => 'int(8) unsigned',
                    'candidate_id' => 'int(6)',
                    'start_date' => 'date',
                    'end_date' => 'date',
                    'company_name' => 'varchar(200)',
                    'job_title' => 'varchar(200)',
                    'skills' => 'text',
                ),
            'candidate_metadata' => Array(
                    'userdataid' => 'int(10) unsigned',
                    'userinfoid' => 'int(10) unsigned',
                    'candidateid' => 'int(6) unsigned',
                    'stringvalue' => 'varchar(1000)',
                    'intvalue' => 'int(15)',
                    'datevalue' => 'datetime',
                    'floatvalue' => 'float(15,4)',
                    'vacancy_id' => 'int(10)',
                ),
            'candidate_notes' => Array(
                    'id' => 'int(10) unsigned',
                    'candidate_id' => 'int(8) unsigned',
                    'note' => 'text',
                    'username' => 'varchar(45)',
                    'modified' => 'timestamp',
                    'created' => 'timestamp',
                    'status' => 'int(1)'
                ),
            'candidate_qualification' => Array(
                    'id' => 'int(8) unsigned',
                    'candidate_id' => 'int(6)',
                    'qualification_level' => 'varchar(10)',
                    'qualification_subject' => 'varchar(200)',
                    'qualification_grade' => 'varchar(2)',
                    'qualification_date' => 'date',
                ),
            'employer_locations' => Array(
                    'id' => 'int(10) unsigned',
                    'organisations_id' => 'int(10) unsigned',
                    'is_legal_address' => 'tinyint(4)',
                    'full_name' => 'varchar(100)',
                    'short_name' => 'varchar(30)',
                    'lsc_number' => 'int(3) unsigned zerofill',
	                'address_line_1' => 'VARCHAR(100)',
		            'address_line_2' => 'VARCHAR(100)',
		            'address_line_3' => 'VARCHAR(100)',
		            'address_line_4' => 'VARCHAR(100)',
 /*                 'saon_start_number' => 'char(4)',
                    'saon_start_suffix' => 'char(1)',
                    'saon_end_number' => 'char(4)',
                    'saon_end_suffix' => 'char(1)',
                    'saon_description' => 'varchar(90)',
                    'paon_start_number' => 'char(4)',
                    'paon_start_suffix' => 'char(1)',
                    'paon_end_number' => 'char(4)',
                    'paon_end_suffix' => 'char(1)',
                    'paon_description' => 'varchar(90)',
                    'street_description' => 'varchar(100)',
                    'locality' => 'varchar(50)',
                    'town' => 'varchar(30)',
                    'county' => 'varchar(30)',*/
                    'postcode' => 'varchar(10)',
                    'telephone' => 'varchar(20)',
                    'fax' => 'varchar(20)',
                    'line1' => 'varchar(100)',
                    'line2' => 'varchar(100)',
                    'line3' => 'varchar(100)',
                    'line4' => 'varchar(100)',
                    'longitude' => 'double',
                    'latitude' => 'double',
                    'northing' => 'int(11)',
                    'easting' => 'int(11)',
                    'contact_name' => 'varchar(50)',
                    'contact_mobile' => 'varchar(50)',
                    'contact_telephone' => 'varchar(15)',
                    'contact_email' => 'varchar(50)',
                ),
            'employers' => Array(
                    'id' => 'int(10) unsigned',
                    'organisation_type' => 'varchar(10)',
                    'upin' => 'varchar(6)',
                    'ukprn' => 'varchar(8)',
                    'legal_name' => 'varchar(200)',
                    'trading_name' => 'varchar(200)',
                    'short_name' => 'varchar(20)',
                    'company_number' => 'varchar(20)',
                    'charity_number' => 'varchar(20)',
                    'vat_number' => 'varchar(20)',
                    'is_training_provider' => 'tinyint(1)',
                    'zone' => 'varchar(10)',
                    'region' => 'varchar(50)',
                    'status' => 'varchar(50)',
                    'fsm' => 'varchar(50)',
                    'code' => 'varchar(20)',
                    'notes' => 'varchar(100)',
                    'shortcode' => 'varchar(10)',
                    'sector' => 'int(5)',
                    'dealer_group' => 'varchar(100)',
                    'manufacturer' => 'int(11)',
                    'org_type' => 'varchar(100)',
                    'workplaces_available' => 'int(10)',
                    'dealer_participating' => 'int(1)',
                    'reason_not_participating' => 'int(10)',
                    'edrs' => 'varchar(30)',
                    'creator' => 'varchar(50)',
                    'parent_org' => 'int(11)',
                    'retailer_code' => 'varchar(10)',
                    'employer_code' => 'varchar(20)',
                    'district' => 'int(10)',
                    'active' => 'tinyint(4)',
                    'health_safety' => 'tinyint(4)',
                ),
            'lookup_vacancy_status' => Array(
                    'id' => 'int(10)',
                    'description' => 'varchar(25)',
                ),
            'lookup_vacancy_type' => Array(
                    'id' => 'int(10)',
                    'description' => 'varchar(100)',
                ),
            'users_metadata' => Array(
                    'userdataid' => 'int(10) unsigned',
                    'userinfoid' => 'int(10) unsigned',
                    'username' => 'varchar(45)',
                    'stringvalue' => 'varchar(255)',
                    'intvalue' => 'int(15)',
                    'datevalue' => 'datetime',
                    'floatvalue' => 'float(15,4)',
                    'textvalue' => 'text',
                ),
            'vacancies' => Array(
                    'id' => 'int(10) unsigned',
                    'code' => 'varchar(20)',
                    'no_of_vacancies' => 'int(10)',
                    'max_submissions' => 'int(10)',
                    'status' => 'int(10)',
                    'reason_not_filled' => 'varchar(50)',
                    'description' => 'varchar(5000)',
                    'employer_id' => 'int(10)',
                    'postcode' => 'varchar(8)',
                    'location' => 'int(10)',
                    'longitude' => 'double',
                    'latitude' => 'double',
                    'northing' => 'int(11)',
                    'easting' => 'int(11)',
                    'type' => 'int(10)',
                    'live_date' => 'date',
                    'expiry_date' => 'date',
                    'job_title' => 'varchar(5000)',
                    'award_sector' => 'int(2)',
                    'salary' => 'varchar(300)',
                    'hours_mon' => 'int(2)',
                    'hours_tues' => 'int(2)',
                    'hours_wed' => 'int(2)',
                    'hours_thurs' => 'int(2)',
                    'hours_fri' => 'int(2)',
                    'hours_sat' => 'int(2)',
                    'hours_sun' => 'int(2)',
                    'person_spec' => 'varchar(5000)',
                    'required_quals' => 'varchar(5000)',
                    'misc' => 'varchar(5000)',
                    'to_level_3' => 'int(1)',
                    'prospects' => 'varchar(500)',
                    'interview_date' => 'date',
                    'current_applications' => 'int(3)',
                    'new_applications' => 'int(3)',
                    'active' => 'int(1)',
                    'shifts_mon' => 'varchar(50)',
                    'shifts_tues' => 'varchar(50)',
                    'shifts_wed' => 'varchar(50)',
                    'shifts_thurs' => 'varchar(50)',
                    'shifts_fri' => 'varchar(50)',
                    'shifts_sat' => 'varchar(50)',
                    'shifts_sun' => 'varchar(50)',
                    'shift_pattern' => 'varchar(5000)',
        ),
		'users_capture_info' => array(
            'userinfoid' => 'int(10) unsigned',
    		'userinfoname' => 'varchar(1000)',
    		'userinfotype' => 'enum(\'checkbox\',\'date\',\'float\',\'int\',\'radio\',\'select\',\'string\',\'text\')',
    		'infoorder' => 'int(10) unsigned',
    		'infogroupid' => 'int(10)',
    		'infogroupname' => 'varchar(50)',
    		'compulsory' => 'int(1)',
    		'lookupvalues' => 'text',
    		'scorevalues' => 'text',
        ),
	);
	
	private $actual_db_tables = array();
	
	// table creation
	private $sql_tables = array(
		// updated to include a region.
		'candidate' => "CREATE TABLE `candidate` (  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,  `firstnames` varchar(50) COLLATE utf8_bin NOT NULL,  `surname` varchar(50) COLLATE utf8_bin NOT NULL,  `gender` enum('F','M','U','W') COLLATE utf8_bin NOT NULL COMMENT 'link to lookup_gender',  `ethnicity` int(2) NOT NULL DEFAULT '99' COMMENT 'link to lis******.ethnicity_code',  `dob` date NOT NULL,  `national_insurance` varchar(13) COLLATE utf8_bin DEFAULT NULL,  `address1` varchar(100) COLLATE utf8_bin DEFAULT NULL,  `address2` varchar(100) COLLATE utf8_bin DEFAULT NULL,  `borough` int(3) NOT NULL COMMENT 'link to lookup_boroughs',  `county` varchar(100) COLLATE utf8_bin NOT NULL COMMENT 'link to lookup_boroughs',  `postcode` varchar(8) COLLATE utf8_bin NOT NULL,  `telephone` varchar(20) COLLATE utf8_bin DEFAULT NULL,  `mobile` varchar(20) COLLATE utf8_bin DEFAULT NULL,  `fax` varchar(20) COLLATE utf8_bin DEFAULT NULL,  `email` varchar(180) COLLATE utf8_bin DEFAULT NULL,  `employment_status` int(1) unsigned zerofill DEFAULT '5' COMMENT 'link to lookup_candidate_employment_status',  `hours_per_week` float(5,2) DEFAULT '0.00',  `time_last_worked` int(2) DEFAULT '0' COMMENT 'months since last worked (36 max)',  `last_education` int(1) DEFAULT NULL COMMENT 'link to lookup_last_education',  `previous_qualification` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'link to lookup_candidate_qualification',  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,  `longitude` double DEFAULT NULL,  `latitude` double DEFAULT NULL,  `northing` int(11) DEFAULT NULL,  `easting` int(11) DEFAULT NULL,  `enrolled` int(10) DEFAULT '0',  `username` varchar(45) COLLATE utf8_bin DEFAULT NULL,  `status` int(1) DEFAULT '0' COMMENT 'flag for indicating if sales person has approved the candidate for the role',  `assessor` varchar(45) COLLATE utf8_bin DEFAULT NULL COMMENT 'name of the assessor linked to the candidate',  `screening_score` int(3) DEFAULT '0' COMMENT 'percentage acheived on screening',  `comment` text COLLATE utf8_bin,  `status_code` varchar(50) COLLATE utf8_bin DEFAULT NULL,  `region` varchar(50) COLLATE utf8_bin DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin",
		'candidate_applications' => "CREATE TABLE `candidate_applications` (  `application_id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `candidate_id` int(6) NOT NULL,  `vacancy_id` int(10) NOT NULL,  `application_comments` varchar(500) DEFAULT NULL,  `application_status` int(2) DEFAULT NULL,  `application_screening` int(3) DEFAULT '0',  `has_been_screened` int(11) DEFAULT '0',  PRIMARY KEY (`application_id`),  UNIQUE KEY `candidate_id` (`candidate_id`,`vacancy_id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1",
		'candidate_difficulty' => "CREATE TABLE `candidate_difficulty` (  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,  `candidate_id` int(6) unsigned NOT NULL,  `difficulty_code` smallint(5) DEFAULT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin",
		'candidate_disability' => "CREATE TABLE `candidate_disability` (  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,  `candidate_id` int(6) unsigned NOT NULL,  `disability_code` smallint(5) DEFAULT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin",
		'candidate_history' => "CREATE TABLE `candidate_history` (  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,  `candidate_id` int(6) DEFAULT NULL,  `start_date` date DEFAULT NULL,  `end_date` date DEFAULT NULL,  `company_name` varchar(200) DEFAULT NULL,  `job_title` varchar(200) DEFAULT NULL,  `skills` text,  PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1",
		'candidate_metadata' => "CREATE TABLE `candidate_metadata` (  `userdataid` int(10) unsigned NOT NULL AUTO_INCREMENT,  `userinfoid` int(10) unsigned NOT NULL,  `candidateid` int(6) unsigned NOT NULL,  `stringvalue` varchar(1000) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,  `intvalue` int(15) DEFAULT NULL,  `datevalue` datetime DEFAULT NULL,  `floatvalue` float(15,4) DEFAULT NULL,  `vacancy_id` int(10) DEFAULT NULL,  PRIMARY KEY (`userdataid`),  UNIQUE KEY `candidateinfoid` (`userinfoid`,`candidateid`,`vacancy_id`),  CONSTRAINT `FK_candidate_data` FOREIGN KEY (`userinfoid`) REFERENCES `users_capture_info` (`userinfoid`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci",
		'candidate_notes' => "CREATE TABLE `candidate_notes` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `candidate_id` varchar(10) NOT NULL,  `note` text,  `username` varchar(45) DEFAULT NULL,  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,  `status` int(1) DEFAULT '0' COMMENT 'indicates if note is system generated or user entered ( 1 = system )',  PRIMARY KEY (`id`), KEY `candidate_id` (`candidate_id`) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1",
		'candidate_qualifications' => "CREATE TABLE `candidate_qualification` (  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,  `candidate_id` int(6) DEFAULT NULL,  `qualification_level` varchar(10) COLLATE utf8_bin DEFAULT NULL,  `qualification_subject` varchar(200) COLLATE utf8_bin DEFAULT NULL,  `qualification_grade` varchar(2) COLLATE utf8_bin DEFAULT NULL,  `qualification_date` date DEFAULT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin",
		'lookup_vacancy_status' => "CREATE TABLE `lookup_vacancy_status` (  `id` int(10) NOT NULL,  `description` varchar(25) DEFAULT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1",
		'lookup_vacancy_type' => "CREATE TABLE `lookup_vacancy_type` (  `id` int(10) DEFAULT NULL,  `description` varchar(100) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1",
		'users_capture_info' => "CREATE TABLE `users_capture_info` (  `userinfoid` int(10) unsigned NOT NULL AUTO_INCREMENT,  `userinfoname` varchar(1000) COLLATE utf8_bin NOT NULL,  `userinfotype` enum('checkbox','date','float','int','radio','select','string','text') COLLATE utf8_bin NOT NULL,  `infoorder` int(10) unsigned NOT NULL DEFAULT '1',  `infogroupid` int(10) DEFAULT '1',  `infogroupname` varchar(50) COLLATE utf8_bin DEFAULT NULL,  `compulsory` int(1) NOT NULL DEFAULT '0' COMMENT 'is the information compulsory 0 - no 1 - yes',  `lookupvalues` text COLLATE utf8_bin COMMENT 'comma separated list of lookup values, if type is lookup',  `scorevalues` text COLLATE utf8_bin COMMENT 'comma separated list of scores for the lookup values.',  PRIMARY KEY (`userinfoid`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin",
		'users_metadata' => "CREATE TABLE `users_metadata` (  `userdataid` int(10) unsigned NOT NULL AUTO_INCREMENT,  `userinfoid` int(10) unsigned NOT NULL,  `username` varchar(45) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,  `stringvalue` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,  `intvalue` int(15) DEFAULT NULL,  `datevalue` datetime DEFAULT NULL,  `floatvalue` float(15,4) DEFAULT NULL,  `textvalue` text COLLATE latin1_general_ci,  PRIMARY KEY (`userdataid`),  UNIQUE KEY `userinfoid` (`userinfoid`,`username`),  CONSTRAINT `FK_users_data` FOREIGN KEY (`userinfoid`) REFERENCES `users_capture_info` (`userinfoid`)) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci",
		'vacancies' => "CREATE TABLE `vacancies` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `code` varchar(20) DEFAULT NULL,  `no_of_vacancies` int(10) DEFAULT NULL,  `max_submissions` int(10) DEFAULT NULL,  `status` int(10) DEFAULT NULL,  `reason_not_filled` varchar(50) DEFAULT NULL,  `description` varchar(5000) DEFAULT NULL,  `employer_id` int(10) NOT NULL,  `postcode` varchar(8) DEFAULT NULL,  `location` int(10) DEFAULT NULL,  `longitude` double DEFAULT NULL,  `latitude` double DEFAULT NULL,  `northing` int(11) DEFAULT NULL,  `easting` int(11) DEFAULT NULL,  `type` int(10) DEFAULT NULL,  `live_date` date DEFAULT NULL,  `expiry_date` date DEFAULT NULL,  `job_title` varchar(5000) DEFAULT NULL,  `award_sector` int(2) DEFAULT NULL,  `salary` varchar(300) DEFAULT NULL,  `hours_mon` int(2) DEFAULT '0',  `hours_tues` int(2) DEFAULT '0',  `hours_wed` int(2) DEFAULT '0',  `hours_thurs` int(2) DEFAULT '0',  `hours_fri` int(2) DEFAULT '0',  `hours_sat` int(2) DEFAULT '0',  `hours_sun` int(2) DEFAULT '0',  `person_spec` varchar(5000) DEFAULT NULL,  `required_quals` varchar(5000) DEFAULT NULL,  `misc` varchar(5000) DEFAULT NULL,  `to_level_3` int(1) DEFAULT '0',  `prospects` varchar(500) DEFAULT NULL,  `interview_date` date DEFAULT NULL,  `current_applications` int(3) DEFAULT '0',  `new_applications` int(3) DEFAULT '0',`active` int(1) DEFAULT '1',`shifts_mon` varchar(50) DEFAULT NULL,`shifts_tues` varchar(50) DEFAULT NULL,`shifts_wed` varchar(50) DEFAULT NULL,`shifts_thurs` varchar(50) DEFAULT NULL,`shifts_fri` varchar(50) DEFAULT NULL,`shifts_sat` varchar(50) DEFAULT NULL,`shifts_sun` varchar(50) DEFAULT NULL,`shift_pattern` varchar(5000) DEFAULT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1",
		'employer_locations' => "CREATE TABLE `employer_locations` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `organisations_id` int(10) unsigned NOT NULL,  `is_legal_address` tinyint(4) DEFAULT '0',  `full_name` varchar(100) DEFAULT NULL,  `short_name` varchar(30) DEFAULT NULL,  `lsc_number` int(3) unsigned zerofill DEFAULT NULL,  `address_line_1` VARCHAR(100) DEFAULT NULL, `address_line_2` VARCHAR(100) DEFAULT NULL, `address_line_3` VARCHAR(100) DEFAULT NULL, `address_line_4` VARCHAR(100) DEFAULT NULL,  `postcode` varchar(10) DEFAULT NULL,  `telephone` varchar(20) DEFAULT NULL,  `fax` varchar(20) DEFAULT NULL,  `line1` varchar(100) DEFAULT NULL,  `line2` varchar(100) DEFAULT NULL,  `line3` varchar(100) DEFAULT NULL,  `line4` varchar(100) DEFAULT NULL,  `longitude` double DEFAULT NULL,  `latitude` double DEFAULT NULL,  `northing` int(11) DEFAULT NULL,  `easting` int(11) DEFAULT NULL,  `contact_name` varchar(50) DEFAULT NULL,  `contact_mobile` varchar(50) DEFAULT NULL,  `contact_telephone` varchar(15) DEFAULT NULL,  `contact_email` varchar(50) DEFAULT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1",
		'employers' => "CREATE TABLE `employers` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `organisation_type` varchar(10) DEFAULT NULL,  `upin` varchar(6) DEFAULT NULL,  `ukprn` varchar(8) DEFAULT NULL,  `legal_name` varchar(200) DEFAULT NULL,  `trading_name` varchar(200) DEFAULT NULL,  `short_name` varchar(20) DEFAULT NULL,  `company_number` varchar(20) DEFAULT NULL,  `charity_number` varchar(20) DEFAULT NULL,  `vat_number` varchar(20) DEFAULT NULL,  `is_training_provider` tinyint(1) DEFAULT NULL,  `zone` varchar(10) DEFAULT NULL,  `region` varchar(50) DEFAULT NULL,  `status` varchar(50) DEFAULT NULL,  `fsm` varchar(50) DEFAULT NULL,  `code` varchar(20) DEFAULT NULL,  `notes` varchar(100) DEFAULT NULL,  `shortcode` varchar(10) DEFAULT NULL,  `sector` int(5) DEFAULT NULL,  `dealer_group` varchar(100) DEFAULT NULL,  `manufacturer` int(11) DEFAULT NULL,  `org_type` varchar(100) DEFAULT NULL,  `workplaces_available` int(10) DEFAULT NULL,  `dealer_participating` int(1) DEFAULT NULL,  `reason_not_participating` int(10) DEFAULT NULL,  `edrs` varchar(30) DEFAULT NULL,  `creator` varchar(50) DEFAULT NULL,  `parent_org` int(11) DEFAULT NULL,  `retailer_code` varchar(10) DEFAULT NULL,  `employer_code` varchar(20) DEFAULT NULL,  `district` int(10) DEFAULT NULL,  `active` tinyint(4) DEFAULT NULL,  `health_safety` tinyint(4) DEFAULT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1"
	);
	
	// set up data
	private $sql_setup = array( 
		'configuration' => "REPLACE INTO `configuration`(`entity`,`value`) values ('module_recruitment','1'),('recruitment_bespoke',NULL),('recruitment_email','support@sunesis-uk.net'),('recruitment_contact','0121 506 9542'),('recruitment_home','http://www.perspective-uk.com')",
		'lookup_vacancy_status' => "REPLACE INTO `lookup_vacancy_status`(`id`,`description`) values (1,'Open'), (2,'Close')",
		'lookup_vacancy_type' => "REPLACE INTO `lookup_vacancy_type`(`id`,`description`) values (1,'Business Administration'),(2,'Customer Service'),(3,'Sales/Telesales'),(4,'Warehousing'),(5,'Retail'),(6,'Childcare')",
		'view_columns' => "REPLACE INTO `view_columns`(`view`,`colum`,`sequence`,`visible`,`alignment`,`user`) values ('ViewCandidateEmployers','name',1,1,'left','master'),('ViewCandidateEmployers','address_line_1',2,1,'left','master'),('ViewCandidateEmployers','town',3,1,'left','master')('ViewCandidateEmployers','postcode',4,1,'left','master'),('ViewCandidateEmployers','telephone',5,1,'left','master'),('ViewCandidates','address',5,1,'left','master'),('ViewCandidates','postcode',6,1,'left','master'),('ViewCandidates','telephone',7,1,'left','master'),('ViewCandidates','registered',8,1,'left','master'),('ViewCandidates','firstnames',1,1,'left','master'),('ViewCandidates','surname',2,1,'left','master'),('ViewCandidates','email',3,1,'left','master'),('ViewCandidates','national_insurance',4,1,'left','master'),('ViewCandidates','national_insurance',1,0,'left','gjones_sales'),('ViewCandidates','address',1,0,'left','gjones_sales'),('ViewCandidates','screening_score',9,1,'centre','master'),('ViewEmployersPool','dpn',1,1,'left','master'),('ViewEmployersPool','company',2,1,'left','master'),('ViewEmployersPool','address1',11,1,'left','master'),('ViewEmployersPool','address2',12,1,'left','master'),('ViewEmployersPool','address3',13,1,'left','master'),('ViewEmployersPool','address4',14,1,'left','master'),('ViewEmployersPool','address5',15,1,'left','master'),('ViewEmployersPool','postcode',10,1,'left','master'),('ViewEmployersPool','telephone',6,1,'left','master'),('ViewEmployersPool','title',3,1,'left','master'),('ViewEmployersPool','firstname',4,1,'left','master'),('ViewEmployersPool','surname',5,1,'left','master'),('ViewEmployersPool','job',9,1,'left','master'),('ViewEmployersPool','email1',7,1,'left','master'),('ViewEmployersPool','email2',8,1,'left','master'),('ViewEmployersPool','sic92co',21,1,'left','master'),('ViewEmployersPool','sic92des',20,1,'left','master'),('ViewEmployersPool','marsecde',19,1,'left','master'),('ViewEmployersPool','empband',18,1,'left','master'),('ViewEmployersPool','total',17,1,'left','master'),('ViewEmployersPool','headbran',16,1,'left','master'),('ViewEmployersPool','url',22,1,'left','master'),('ViewEmployersPool','empband',1,0,'left','admin')",
	);
	
	// postcode validation
	private $location_data = array();
	private $candidate_data = array();
	public $sql_for_bulk = '';
}
?>
