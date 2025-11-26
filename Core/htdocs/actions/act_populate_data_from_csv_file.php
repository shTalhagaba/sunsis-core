<?php
class populate_data_from_csv_file implements IAction
{
	// flag to indicate if to do a clean down [1 = yes]
	private $is_fresh_install = 1;
	
	// logging active
	private $do_logging = 1;
	// location of log file
	private $log_file = '/Users/Perspective/workspace/sunesis/logs/'; 
	// log file handle;
	private $log_file_handle = NULL;
	// name of the .csv file to import options = ['employers', 'learners', 'assessors']
	private $import_file = 'learners';
	
	// sanity check on system name to prevent wiping data from wrong system
	private $database_to_update = 'am_bright';

	
	// client specific data.
	private $system_owner_name = 'Leicester College of Performing Arts';
	private $firstnames = 'Neil';
	private $surname = 'Allen';
	private $gender = 'M';
	private $short_name = 'LCPA';
	private $is_legal_address = 0;
/*	private $paon_description = 'LCPA';
	private $street_description = 'LCPA';
	private $locality = 'Garden Street';
	private $town = 'Leicester';
	private $county = NULL;*/
	private $address_line_1 = 'LCPA';
	private $address_line_2 = 'LCPA';
	private $address_line_3 = 'Garden Street';
	private $address_line_4 = 'Leicester';
	private $postcode = 'LE1 3UA';
	private $telephone = '0116 2622279';
	private $fax = NULL;
	
	public function execute(PDO $link) {
		
		// check in the correct database
		if ( DB_NAME != $this->database_to_update ) {
			throw new Exception('Incorrect database specified');
		}
		
		// logging set up
		if ( $this->do_logging ) {
			$this->log_file .= 'import.'.date("ymd").'.'.DB_NAME.'.log';
			$this->log_file_handle = fopen($this->log_file, 'a+');
		}
		
		// $complete_record = array();
		if ( !isset($this->import_file) ) {
			throw new Exception('No Import File Specified for Import');
		}
		
		if ( !file_exists($this->import_file.'.csv') ) {
			throw new Exception('No Import File Located for Import');	
		}
			
		/**
		* backup the database prior to any manipulations
		* 
		* !not yet functional!
		*/
		$this->backup_database();
		
		/**
		* if this is a full 'clean' need to ensure database is clean before proceeding.
		* - and ensure only the system owner organisation and adminstrator user exist.
		* 
		*/		
		if( $this->is_fresh_install ) {
			$this->clean_database($link);
		}
		
		$generic_object = NULL;
		
		/**
		* do import type specific stuff
		* - set any values that are consistent for the import
		*/
		switch ( $this->import_file ) {
			case 'learners':
				$generic_object = new User();
				$generic_object->password = "password";
				$generic_object->type = 5;
				break;
			case 'employers':
				$generic_object = new Employer();
				$generic_object->active = 1;
				$generic_object->organisation_type = 2;
				break;
			case 'assessors':
				$generic_object = new User();
				$generic_object->password = "password";
				$generic_object->type = 3;
				// default the employer to system owner - check this ??
				$generic_object->employer_id = 1;
				break;
			default:
				// not handled - bail for now
				exit;
		}
		
		$this->import_file .= '.csv';
				
		$gc = '';
		$handle = fopen($this->import_file,"r");
		
		// get the header information
		$st = fgets($handle);
		$header_array = explode(",", $st);
		
		// get all the record information
		while( !feof($handle) ) {
			$st = fgets($handle);
						
			// Create Import Items
			$import_object = clone($generic_object);
			$arr = explode(",",$st);

			if( $arr[0] == 'END' ) {
				throw new Exception($gc);
			}
			
			// set each value we can find in the object here
			foreach ($arr as $position => $system_value ) {
				if( isset($header_array[$position]) ) {
					$user_data_field = strtolower($header_array[$position]);
					$user_data_field = preg_replace('/ /', '_', $user_data_field);
					if ( array_key_exists($user_data_field, $import_object) ) {
						$import_object->{$user_data_field} = $system_value;
					}
				}
			}	
			
			/**
			* post processing value creation
			* for those values not directly imported 
			* or those requiring manipulations
			*/
			//  - user object
			if ( ($import_object instanceof User) &&( isset($import_object->surname) ) ) {
				$this->learner_import($link, $import_object);
				$import_object->save($link, true);
			}
			else if ( ( $import_object instanceof Employer )&&( isset($import_object->trading_name) ) ) {
				$this->employer_import($link, $import_object);	
				// re-check the object values to save a location
				// !CHANGE THIS SECONDARY LOOPING!
				foreach ($arr as $position => $system_value ) {
					if( isset($header_array[$position]) ) {
						$user_data_field = strtolower($header_array[$position]);
						$user_data_field = preg_replace('/ /', '_', $user_data_field);
						if ( array_key_exists($user_data_field, $import_object) ) {
							$import_object->{$user_data_field} = $system_value;
						}
					}
				}
				// save the import data
				$import_object->save($link, true);
				
			}
		}			
		fclose($handle); 
		
		if( isset($this->log_file_handle) ) {
			fclose($this->log_file_handle);
		}
	}
	
	private function learner_import( PDO $link, &$learner_object ) {
		
		/**
		* location of the learners employer 
		*/
		$employer_code_sql = 'select id from organisations where employer_code = "'.$learner_object->employer_id.'"';
		$emp_id = DAO::getSingleValue($link, $employer_code_sql);
		if($emp_id == '') {
			// !!THIS IS NOT GOOD - WE SHOULD IGNORE THIS LEARNER AND FLAG IT!!
		}
		$learner_object->employer_location_id = DAO::getSingleValue($link, "select id from locations where organisations_id = '$emp_id'");		
		
		/**
		* username
		* - ensure there are no odd characters in the surname
		*/
		$learner_object->surname = preg_replace('/\W+/','',$learner_object->surname);
		
		$username =  strtolower(substr($learner_object->firstnames, 0, 1).$learner_object->surname);
		// Validate unique user identities
		$sql = "SELECT username FROM users FOR UPDATE";
		$user_list = DAO::getSingleColumn($link, $sql);
			
		$username_incre = 1;
			
		while( in_array($username, $user_list) ) {
			// remove any digits at the end of the username
			$username = preg_replace('/\d+$/', '', $username);
			// increment the username values
			$username .= $username_incre;
			$username_incre++;
		}	
		$learner_object->username = $username;
		/**
		* date of birth
		* - ensure it is the correct length dd/mm/yyyy ( 10 characters ) - if not ignore
		* - if its ok, convert to correct format
		*/
		if( strlen($learner_object->dob) == 10 ) {
			$learner_object->dob = Date::toMySQL($learner_object->dob);	
		}
		else {
			$learner_object->dob = '';
		}
		/**
		* gender
		* - ensure if it is blank we put 'U' - unknown
		*/
		if ( $learner_object->gender == '' ) {
			$learner_object->gender = 'U';
		}
		/**
		* may have put in the full ( male / female) - lift the first character and uppercase it
		*/
		elseif( strlen($learner_object->gender) > 1 ) {
			$learner_object->gender = strtoupper( substr($learner_object->gender, 0, 1) );				
		}
		
		/**
		* ethnicity - default to 99 - unknown / not provided
		*/
		if ( !is_numeric($learner_object->ethnicity) ) {
			$learner_object->ethnicity = 99;
		}

		/**
		* enrollment number
		* - ensure it does not exceed length
		*/
		if ( strlen($learner_object->enrollment_no) >= 10 ) {
			$learner_object->enrollment_no = substr($learner_object->enrollment_no, 0, 10);
		 }
		return;
	}
	
	/**
	* 
	* Carry out all Employer specific data set up for an import.
	* @param PDO $link
	* @param reference to Employer Object $employer_object
	*/
	private function employer_import( PDO $link, &$employer_object ) {
		/**
		* ensure we have the sector
		* - if not set it up
		*/
		$sql = "SELECT id FROM lookup_sector_types where description = '".$employer_object->sector."'";
		$sector_id = DAO::getSingleValue($link, $sql);
		if( !isset($sector_id) ) {
			$insert_sector_sql = "INSERT into lookup_sector_types ( description ) values ('".$employer_object->sector."')";
			DAO::execute($link, $insert_sector_sql);
			$sector_id = DAO::getSingleValue($link, $sql);
		}
		$employer_object->sector = $sector_id;
	
		/**
		* ensure we have an employer code
		*/
		if ( !isset($employer_object->employer_code ) ) {
			$employer_object->employer_code = $employer_object->id;
		}
		
		
		/**
		* duplicate the trading_name into other *_name fields
		*/
		$employer_object->legal_name = $employer_object->trading_name;
		
		/**
		* check if we have an edrs number 
		* ( Employer Identifier, field A44 ) ref page 9: 
		* http://www.theia.org.uk/NR/rdonlyres/0304DDE7-72CE-4CA8-A949-B3C147BE2CCA/0/Summary_ILRChanges200910v2.pdf
		* if not then set up as a new employer with a new location
		*/
		// ?? isn't this the wrong object to reference  ??
		if( !isset($import_object->edrs) ) {
			$employer_object->save($link, true);
			// change the object to save the location
			$organisation_id = $employer_object->id;
			$employer_object = new Location();
			$employer_object->organisations_id = $organisation_id;
			$employer_object->full_name = 'Main Site'; 
			$employer_object->short_name = 'main';
			$employer_object->is_legal_address = 1;
		}
		return;
	}
	
	/**
	* Create a mysqldump file of the database we are about to clean 
	* for sanity / restore in case of error
	* 
	* NOT YET OPERATIONAL
	*/
	private function backup_database() {
		$backup_archive = DB_NAME.'.'.date("hms_ymd").'.sql.gz';
		$backup_cmd = 'mysqldump --single-transaction --routines --order-by-primary '.DB_NAME.' | gzip -c - > '.$backup_archive;
		if( isset($this->log_file_handle) ) {
			fputs($this->log_file_handle, "BACKUP CREATED: ".$backup_archive."\n");
		}
		return 1;	
	}
	
	/**
	* 
	* Remove all data from the database and set up required data.
	* @param PDO $link
	*/
	private function clean_database( PDO $link ) {
		/**
		* ensure its the initial import, so is ok to clean down.
		* - could do checks on the log file also?
		*/
		if ( $this->import_file != 'employers') {
			return 1;	
		}
		/**
		* get all the tables we want to clear
		*  (having records and not a lookup) 
		*  - exclude configuration
		*  - exclude acl
		*  - if any gacl_* tables exist remove those also
		*/
		$removable_tables[0] = 'gacl_';
		$ignorable_tables = array('^lookup', '^dropdown', '^acl$', '^configuration$', '^view_columns$');
		
		$rmv_pattern = '/(' .implode('|', $removable_tables) .')/i';
		$ign_pattern = '/(' .implode('|', $ignorable_tables) .')/i';
		
		$alltableclear_sql = 'SELECT table_name FROM information_schema.tables WHERE table_schema = "'.DB_NAME.'" ';
		$alltableclear_sql .= 'AND TABLE_ROWS > 0';
		$table_list = DAO::getSingleColumn($link, $alltableclear_sql);
				
		$drop_count = 0;
		$trunc_count = 0;
		
		foreach ( $table_list as $tablename ) {
			if( preg_match($rmv_pattern, $tablename) ) {
				$tabledrop_sql = 'DROP TABLE '.$tablename;
				DAO::execute($link, $tabledrop_sql);
				$drop_count++;
			}
			else if (!preg_match($ign_pattern, $tablename) )  {
				$tableclear_sql = 'TRUNCATE TABLE '.$tablename;
				DAO::execute($link, $tableclear_sql);
				$trunc_count++;
			}
		}
		
		if( isset($this->log_file_handle) ) {
			fputs($this->log_file_handle, "DROPPED ".$drop_count." TABLES \n");
			fputs($this->log_file_handle, "TRUNCATED ".$trunc_count." TABLES \n");
		}
		
		/**
		* create the system owner
		*/
		// organisation record
		$system_owner = new Employer();
		$system_owner->active = 1;
		$system_owner->organisation_type = 1;
		$system_owner->trading_name = $this->system_owner_name;
		$system_owner->employer_code = 1;
		$this->employer_import($link, $system_owner);
		
		// location record	
		$system_owner->full_name = $this->system_owner_name;
		$system_owner->short_name = $this->short_name;
		$system_owner->is_legal_address = $this->is_legal_address;
		$system_owner->address_line_1 = $this->address_line_1;
		$system_owner->address_line_2 = $this->address_line_2;
		$system_owner->address_line_3 = $this->address_line_3;
		$system_owner->address_line_4 = $this->address_line_4;
/*		$system_owner->paon_description = $this->paon_description;
		$system_owner->street_description = $this->street_description;
		$system_owner->locality = $this->locality;
		$system_owner->town = $this->town;
		$system_owner->county = $this->county;*/
		$system_owner->postcode = $this->postcode;
		$system_owner->telephone = $this->telephone;
		$system_owner->fax = $this->fax;
		$system_owner->save($link, true);
		if( isset($this->log_file_handle) ) {
			fputs($this->log_file_handle, "SYSTEM OWNER CREATED: ".$this->system_owner_name."\n");
		}
		
		/**
		* create the administrator user
		*/
  		$system_admin = new User();	
  		$system_admin->username = "admin";	
		$system_admin->password = "perspective08";		// change this ASAP!!
		$system_admin->firstnames = $this->firstnames;
		$system_admin->surname = $this->surname;
		$system_admin->gender = $this->gender;
		$system_admin->employer_id = $system_owner->id;
		$system_admin->employer_location_id = $system_owner->id;
		$system_admin->type = 1;
		$system_admin->save($link, true);
		if( isset($this->log_file_handle) ) {
			fputs($this->log_file_handle, "SYSTEM ADMIN CREATED: ".$system_admin->username."\n");
		}
		
		// clean up objects allocated resources and remove
		// $system_owner->__destruct();
  		unset($system_owner);
  		unset($system_admin);
		return 1;
	}
}
?>