<?php
class User extends Entity
{
	/**
	 * @static
	 * @param PDO $link
	 * @param int $id
	 * @return null|User
	 * @throws Exception
	 */
	public static function loadFromDatabaseById(PDO $link, $id)
	{
		if (!$id || !is_numeric($id)) {
			throw new Exception("Missing or non-numeric id");
		}

		$username = DAO::getSingleValue($link, "SELECT username FROM users WHERE id=" . $link->quote($id));
		if (!$username) {
			return null;
		}

		return self::loadFromDatabase($link, $username);
	}


	/**
	 * @static
	 * @param PDO $link
	 * @param string $username
	 * @return null|User
	 */
	public static function loadFromDatabase(PDO $link, $username)
	{
		if($username == ''){
			return null;
		}

		$key = $link->quote($username);
		$query = <<<SQL
SELECT
	users.*,
	organisations.short_name AS org_short_name,
	organisations.legal_name as org_legal_name,
	locations.short_name AS loc_short_name,
	lookup_people_type.people_type as role
FROM
	users LEFT OUTER JOIN organisations ON (users.employer_id = organisations.id)
	LEFT OUTER JOIN locations ON (users.employer_location_id = locations.id)
	LEFT OUTER JOIN lookup_people_type ON (lookup_people_type.id = users.type) 
WHERE
	username = $key
SQL;

		$user = null;
		$obj = DAO::getObject($link, $query);
		if($obj)
		{
			$user = new User();
			$user->populate($obj);

			// Expand multi-value fields
			if( is_null($user->acl_filters) )
			{
				$user->acl_filters = array();
			}
			else
			{
				$user->acl_filters = explode(',', $user->acl_filters);
			}

			if( is_null($user->acl_adopted_identities) )
			{
				$user->acl_adopted_identities = array();
			}
			else
			{
				$user->acl_adopted_identities = explode(',', $user->acl_adopted_identities);
			}

			// Build up Identities (username, organisation wildcards, groups)
			$user->identities = $user->getPrimaryIdentities($link);
			$user->identities = array_merge($user->identities, $user->getGroups($link));
			$user->identities[] = '*';

			// RELMES - GET META DATA - DO CHECK IF REQUIRED
			$user->getUserMetaData($link, $username);

			// Add secondary identities (adopted identities)
			if($user->acl_adopted_identities != '')
			{
				$user->identities = array_merge($user->identities, $user->expandIdentities($link, $user->acl_adopted_identities));
			}

			// Clean up the identities array
			$user->identities = array_unique($user->identities);

			// Application-wide ACL
			$acl = ACL::loadFromDatabase($link, 'application', '1');
			$user->is_admin = $acl->isAuthorised($user, 'administrator');
			$user->is_organisation_creator = $acl->isAuthorised($user, 'org creator');
			$user->is_people_creator = $acl->isAuthorised($user, 'people creator');

			// Organisation and location objects
			$user->org = Organisation::loadFromDatabase($link, $user->employer_id);
			$user->loc = Location::loadFromDatabase($link, $user->employer_location_id);

			// Set 'org' and 'employer' admin flags
			$parent_org_type = $user->org ? $user->org->organisation_type : null;
			$user->is_org_admin = $user->type == User::TYPE_ADMIN && $parent_org_type == Organisation::TYPE_TRAINING_PROVIDER;
			$user->isEmployerAdmin = $user->type == User::TYPE_ADMIN && $parent_org_type == Organisation::TYPE_EMPLOYER;

			// Find users last login date
			$sql = "SELECT `date` FROM logins WHERE username = '".$user->username."' ORDER BY `date` DESC LIMIT 1";
			$user->last_logged_in = DAO::getSingleValue($link, $sql);


		}

		return $user;
	}


	public function save(PDO $link, $newRecord = false)
	{
		$current_password_sha1 = DAO::getSingleValue($link, "SELECT users.pwd_sha1 FROM users WHERE users.id = '{$this->id}'");

		$this->username = strtolower($this->username);

		// Don't save an invalid ULN
		if($this->l45 && !User::isValidUln($this->l45)) {
			$this->l45 = '';
		}

		// Lock all training records with the same username (we update the home telephone later)
		/*		$sql = "SELECT id FROM tr WHERE username=" . $link->quote($this->username). " FOR UPDATE";
				$tr_ids = DAO::getSingleColumn($link, $sql);*/

		// Lock user record (or the username key if no record exists)
		$sql = "SELECT id FROM users WHERE username = '".addslashes((string)$this->username)."' FOR UPDATE";
		$existing_id = DAO::getSingleValue($link, $sql);
		if (!$this->id && $existing_id) {
			throw new Exception("A user already exists with username $this->username");
		}

		// Find previous level of web access
		$sql = "SELECT web_access FROM users WHERE username='".addslashes((string)$this->username)."'";
		$previous_web_access = DAO::getSingleValue($link, $sql);
		if(is_null($previous_web_access)){
			$previous_web_access = 0; // new user (no existing record)
		}

		// Generate a new password if the administrator has not supplied one
		// and the account is being activated
		if( ($this->web_access > $previous_web_access) && ($this->password == '') )
		{
			//$dw = new Diceware();
			//$this->password = $dw->generatePassphrase($link);
			do
			{
				$pwd = PasswordUtilities::generateDatePassword();
				$pwd = PasswordUtilities::randomCapitalisation($pwd, 1);
				$pwd = PasswordUtilities::replaceSpacesWithNumbers($pwd);
				$validationResults = PasswordUtilities::checkPasswordStrength($pwd, PasswordUtilities::getIllegalWords());
			} while($validationResults['code'] == 0);
			$this->password = $pwd;
		}

		// Hash password
		$this->pwd_sha1 = $this->password != '' ? sha1($this->password) : $this->pwd_sha1;

		if(DB_NAME=="am_reed" || DB_NAME == "am_reed_demo")
		{
			$loc = new GeoLocation();
			$loc->setPostcode($this->home_postcode, $link);
			$this->longitude = $loc->getLongitude();
			$this->latitude = $loc->getLatitude();
			$this->easting = $loc->getEasting();
			$this->northing = $loc->getNorthing();
		}

		if($this->id != '' && $current_password_sha1 != $this->pwd_sha1 && $this->pwd_sha1 != 'da39a3ee5e6b4b0d3255bfef95601890afd80709' && $this->pwd_sha1 != '')
		{
			// only store last 3 passwords
			$saved_passwords = DAO::getSingleValue($link, "SELECT COUNT(*) FROM user_password_history WHERE user_id = '{$this->id}' AND username = '{$this->username}'");
			if($saved_passwords == 3)
			{
				$history_record_to_delete = DAO::getSingleValue($link, "SELECT id FROM user_password_history WHERE user_id = '{$this->id}' AND username = '{$this->username}' ORDER BY created_at ASC");
				DAO::execute($link, "DELETE FROM user_password_history WHERE id = '{$history_record_to_delete}'");
			}
			$history = (object)[
				'user_id' => $this->id,
				'username' => $this->username,
				'password_sha1' => $this->pwd_sha1,
				'created_at' => date('Y-m-d H:i:s'),
			];
			DAO::saveObjectToTable($link, 'user_password_history', $history);
			$this->password_changed_at = date('Y-m-d');
		}
		if($this->id == '')
		{
			$this->password_changed_at = date('Y-m-d');
		}
		DAO::saveObjectToTable($link, 'users', $this);



		// Update home telephone number in all training records
		/*		DAO::execute($link, "UPDATE tr SET home_telephone = '".addslashes((string)$this->home_telephone)
					."' WHERE tr.username = '".addslashes((string)$this->username)."'");*/


		// Post save: email the user their new password
		/*		if( $this->web_access > $previous_web_access )
		{
			$to = $this->work_email;
			$from = $_SESSION['user']->work_email;
			$subject = "Welcome";
			$message = <<<HEREDOC
Your passphrase is:
	{$this->password}

If this is the first time your account has been activated then
you will be notified of your username in a separate communication.
If you have forgotten your username then please contact
the System Administrator.

This passphrase is known to the administrator and has been
passed openly via email. When you next login to your account
you should reset your passphrase to one known only to you.
Go to "My Account" in the left-hand menu and select
"Change Password".  A random-passphrase generator is provided
for your convenience.

Strong passphrases are important to the security of this site.
Please make yours hard to guess and hard to generate
randomly from a dictionary.

If you find you cannot login with your new passphrase,
please inform the System Administrator immediately.


Yours sincerely,

{$_SESSION['user']->firstnames} {$_SESSION['user']->surname}
System Administrator
HEREDOC;
						
			@mail($to, $subject, $message, "From: $from\r\nCc: $from", "-f ".$from);
		}*/
	}


	/**
	 * Run as part of a transaction
	 */
	public function delete(PDO $link)
	{

		$un = addslashes((string)$this->username);
		$count = DAO::getSingleValue($link, "select count(*) from tr where username = '$un'");
		if($count>0)
		{
			throw new Exception("This learner is enrolled on a course so cannot be deleted");
		}

		$identities = "'".addslashes((string)$this->username)."', '".addslashes((string)$this->getFullyQualifiedName())."'";
		$queries[] = "DELETE FROM users WHERE username='$un'";
		$queries[] = "DELETE FROM acl WHERE ident IN ($identities)";
		//$queries[] = "DELETE FROM group_members WHERE member IN ($identities)";
		foreach($queries as $query)
		{
			DAO::execute($link, $query);
		}

		return true;
	}


	public function isSafeToDelete()
	{
		return true; // it is always safe to delete a user
	}


	public function getIdentities()
	{
		return $this->identities;
	}


	public function isAdmin()
	{
		return $this->is_admin;
	}

	public function isOrgAdmin()
	{
		return $this->is_org_admin;
	}

	public function isOrganisationCreator()
	{
		return $this->is_admin || $this->is_organisation_creator;
	}

	public function isPeopleCreator()
	{
		return $this->is_admin || $this->is_people_creator;
	}


	public function getACLFilters()
	{
		return $this->acl_filters;
	}


	public function getFullyQualifiedName()
	{
		if($this->employer_id != '')
		{
			return $this->username.'/'.$this->loc_short_name.'/'.$this->org_short_name;
		}
		else
		{
			return $this->username;
		}
	}

	/**
	 * Returns a boolean indication of whether a user has a particular identity
	 * or not.
	 *
	 * @param string $identity
	 * @return boolean
	 */
	public function is($identity)
	{
		return in_array($identity, $this->identities);
	}

	/**
	 * Returns the thumbnail required to display user portrait
	 * or gender specific default value
	 *  - static function need to ensure this is ok
	 * @param string $username 	- learner username
	 * @param string $gender		- learner gender
	 * @param string $styling	- any specific css styling applied to the portrait
	 * @return string
	 */
	public static function getUserThumbnail($username, $gender = 'U', $styling = '')
	{
		// Prepare paths where a photograph may possibly be
		$username = trim($username);
		$root = Repository::getRoot();
		$user_root = $root.'/'.$username;
		if(!is_dir($user_root)){
			$user_root = null;
		}
		$user_photo_root =  $root.'/'.$username.'/photos';
		if(!is_dir($user_photo_root)){
			$user_photo_root = null;
		}

		// Search for the photograph in all possible paths
		$photo = '';
		if($user_photo_root)
		{
			$images = glob($user_photo_root.'/profilePhoto.{jpg,jpeg,gif,png,JPG,JPEG,GIF,PNG}', GLOB_BRACE);
			if(count($images) > 0){
				$photo = $images[0]; // return first image in the glob
			}
		}
		elseif($user_root)
		{
			$images = glob($user_root.'/*.{jpg,jpeg,gif,png,JPG,JPEG,GIF,PNG}', GLOB_BRACE);
			foreach($images as $image){
				if(stripos($image, 'signat') === false){
					$photo = $image; // return first image that is not a user's signature
				}
			}
		}

		if($photo)
		{
			$styling .= "; width:30px; height:30px";
			$user_thumbnail = "<img style=\"$styling\" src=\"do.php?_action=display_image&username=".rawurlencode($username)."\" border=\"0\" title=\"{$username}\" />";
		}
		elseif($gender == 'M')
		{
			$user_thumbnail = "<img style=\"$styling\" src=\"/images/boy-blonde-hair.gif\" border=\"0\" title=\"{$username}\" />";
		}
		else
		{
			$user_thumbnail = "<img style=\"$styling\" src=\"/images/girl-black-hair.gif\" border=\"0\" title=\"{$username}\" />";
		}

		return $user_thumbnail;
	}

	/**
	 * Returns the user's photograph, searching for it in three possible locations:<br/>
	 * 1) Lewisham's /photos directory<br/>
	 * 2) The user's /username/photos directory<br/>
	 * 3) The user's /username directory<br/>
	 * @author iss
	 * @return absolute filepath to the user's photograph
	 */
	public function getPhotoPath()
	{
		$username = trim($this->username ?: '');
		$enrollment_no = trim($this->enrollment_no ?: '');

		$root = Repository::getRoot();
		$photo_root = $root.'/photos';
		if(!is_dir($photo_root)){
			$photo_root = null;
		}
		$user_root = $root.'/'.$username;
		if(!is_dir($user_root)){
			$user_root = null;
		}
		$user_photo_root =  $root.'/'.$username.'/photos';
		if(!is_dir($user_photo_root)){
			$user_photo_root = null;
		}
		// (1)
		if($photo_root && $enrollment_no){
			$images = glob($photo_root.'/'.$enrollment_no.'.{jpg,jpeg,gif,png,JPG,JPEG,GIF,PNG}', GLOB_BRACE);
			if(count($images) > 0){
				return $images[0]; // return first image in the glob
			}
		}
		// (2)
		if($user_photo_root){
			$images = glob($user_photo_root.'/profilePhoto.{jpg,jpeg,gif,png,JPG,JPEG,GIF,PNG}', GLOB_BRACE);
			if(count($images) > 0){
				return $images[0]; // return first image in the glob
			}
		}
		// (3)
		if($user_root){
			$images = glob($user_root.'/*.{jpg,jpeg,gif,png,JPG,JPEG,GIF,PNG}', GLOB_BRACE);
			foreach($images as $image){
				if(stripos($image, 'signat') === false){
					return $image; // return first image that is not a user's signature
				}
			}
		}
		return null;
	}


	/**
	 * Returns an array of identities, from most specific to least specific
	 * @param PDO $link
	 * @return array
	 */
	private function getPrimaryIdentities(PDO $link)
	{
		$identities = array();

		$identities[] = $this->username;

		// If the user is associated with an employer...
		if($this->employer_id != '')
		{
			$org = Organisation::loadFromDatabase($link, $this->employer_id);

			if($this->employer_location_id != '')
			{
				$loc = Location::loadFromDatabase($link, $this->employer_location_id);
				//$identities[] = $this->username.'/'.$loc->short_name.'/'.$org->short_name;
				//$identities[] = '*/'.$loc->short_name.'/'.$org->short_name;
			}

			//$identities[] = '*/'.$org->short_name;
		}

		return $identities;
	}


	/**
	 * Expands wildcard globs into full lists of employees. Used when working out
	 * the adopted identities of a user. Does not work with groups.
	 * @param PDO $link
	 * @param array $identities
	 * @return array
	 */
	private function expandIdentities(PDO $link, array $identities)
	{
		if(count($identities) == 0)
		{
			return array();
		}

		// Build search string for main query
		$having = '';
		foreach($identities as $identity)
		{
			if(strlen($having) > 0)
			{
				$having .= ' OR ';
			}

			if(strpos($identity, '*') !== FALSE)
			{
				$having .= " fqn LIKE '%".substr($identity, 1)."' ";
			}
			else
			{
				$having .= " fqn LIKE '$identity' ";
			}
		}


		$sql = <<<HEREDOC
SELECT
	username,
	CONCAT('*/', organisations.short_name) AS org_glob,
	CONCAT('*/', locations.short_name, '/', organisations.short_name) AS loc_glob,
	CONCAT(username, '/', locations.short_name, '/', organisations.short_name) AS fqn
FROM
	users INNER JOIN organisations INNER JOIN locations
	ON users.employer_id = organisations.id AND users.employer_location_id = locations.id
HAVING
	$having		
HEREDOC;

		// Add expanded identities (will result in many duplicates, but these are removed later)
		$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($rows as $row){
			$identities[] = $row['org_glob'];
			$identities[] = $row['loc_glob'];
			$identities[] = $row['fqn'];
		}

		return array_unique($identities);
	}


	/**
	 * Returns an array of groups the user is a member of, from most to least
	 * direct (i.e. if a user is specifically mentioned as a member of group Green,
	 * and through his membership of group Green he is also indirectly a member of group Cyan,
	 * then this function will return array('Green', 'Cyan').
	 */
	private function getGroups(PDO $link)
	{
		// User's identities
		$identities = $this->getPrimaryIdentities($link);
		$identities[] = '*'; // least specific identity of all

		$groups = array(); // holds groups in hierarchical then alphabetical order

		// First get the primary groups
		$groups = array();
		$sql = "SELECT DISTINCT group_name FROM groups INNER JOIN group_members ON groups.id = group_members.groups_id WHERE member IN (".DAO::pdo_implode($identities).')';
		$groups = DAO::getSingleColumn($link, $sql);
		sort($groups); // groups on the same hierarchical level are sorted into alphabetical order

		// Now work up through the group hierarchy
		if(count($groups) > 0)
		{
			do
			{
				// Record the number of known groups at the start of this particular iteration
				$groupCountAtStart = count($groups);

				// Get group names
				$sql = "SELECT DISTINCT group_name FROM groups INNER JOIN group_members ON groups.id = group_members.groups_id WHERE member IN (".DAO::pdo_implode($groups).')';
				$results = DAO::getSingleColumn($link, $sql);
				sort($results); // groups on the same hierarchical level are sorted into alphabetical order

				// Iterate through the results and add any new groups to the main groups array
				foreach($results as $g)
				{
					if(!in_array($g, $groups))
					{
						$groups[] = $g; // Add new group name
					}
				}
			} while(count($groups) > $groupCountAtStart); // iterate again if we found any new groups
		}

		return $groups;
	}

	/**
	 *
	 * function to retreive system specific user metadata
	 * if the username is not supplied then return the metadata field name and its type.
	 * trac ticket: #171
	 * @param PDO $link
	 * @param string $username
	 * @return void
	 * @throws Exception
	 */
	public function getUserMetaData( PDO $link, $username = "" )
	{
		// move this into a central function
		// check the required table set up exists
		$check_tables_sql = 'SELECT table_name FROM information_schema.tables WHERE table_name in ("users_capture_info","users_metadata") and table_schema = "'.DB_NAME.'"';
		$checked_table = DAO::getSingleColumn($link, $check_tables_sql);

		// if we don't have both these tables kick it back
		if( sizeof($checked_table) !== 2 ) {
			return;
		}

		// check username exists
		if ( $username != "" ) {
			// get data based on username
			$sql_userdata = <<<HEREDOC
				SELECT users_capture_info.infogroupname as groupname,
				users_capture_info.userinfoname as metaname, 
				IF(users_capture_info.userinfotype = 'string', users_metadata.stringvalue, 
				IF(users_capture_info.userinfotype = 'radio', users_metadata.stringvalue, 
				IF(users_capture_info.userinfotype = 'int', users_metadata.intvalue, 
				IF(users_capture_info.userinfotype = 'float', users_metadata.floatvalue, 
				IF(users_capture_info.userinfotype = 'date', users_metadata.datevalue, ''))))) AS metadata 
				FROM users_capture_info LEFT JOIN users_metadata 
				ON users_capture_info.userinfoid = users_metadata.userinfoid
				WHERE users_metadata.username = '{$username}'
				ORDER BY users_capture_info.infoorder ASC;
HEREDOC;

		} else {
			// get meta data fields to allow form creation
			$sql_userdata = <<<HEREDOC
				SELECT users_capture_info.infogroupname as groupname,
				CONCAT(users_capture_info.userinfoid,"_", users_capture_info.userinfoname) AS metaname, 
				CONCAT(users_capture_info.compulsory,"_", users_capture_info.userinfotype) AS metadata 
				FROM users_capture_info
				ORDER BY users_capture_info.infogroupid, users_capture_info.infoorder ASC;
HEREDOC;

		}

		$st = $link->query($sql_userdata);
		if($st) {
			while( $row = $st->fetch() ) {
				if ( !array_key_exists($row['groupname'], $this->user_metadata) ) {
					$this->user_metadata[$row['groupname']] = array();
				}
				$this->user_metadata[$row['groupname']][$row['metaname']] = $row['metadata'];
			}
		}
		else {
			throw new Exception('ERR: The metadata is not correctly set up ['.$sql_userdata.']');
		}

		return;
	}

	/**
	 *
	 * function to retreive system specific user metadata
	 * if the username is not supplied then return the metadata field name and its type.
	 * trac ticket: #171
	 * @param PDO $link
	 * @param string $username
	 * @param string $meta_data
	 * @return string
	 * @throws Exception
	 */
	public static function getSpecificUserMetaData( PDO $link, $username = "", $meta_data = "" ) {

		// move this into a central function
		// check the required table set up exists
		$check_tables_sql = 'SELECT table_name FROM information_schema.tables WHERE table_name in ("users_capture_info","users_metadata") and table_schema = "'.DB_NAME.'"';
		$checked_table = DAO::getSingleColumn($link, $check_tables_sql);

		// if we don't have both these tables kick it back
		if( sizeof($checked_table) !== 2 ) {
			return;
		}


		// get meta data fields to allow form creation
		$sql_userdata = <<<HEREDOC
			SELECT  
				IF(users_capture_info.userinfotype = 'string', users_metadata.stringvalue, 
				IF(users_capture_info.userinfotype = 'radio', users_metadata.stringvalue, 
				IF(users_capture_info.userinfotype = 'int', users_metadata.intvalue, 
				IF(users_capture_info.userinfotype = 'float', users_metadata.floatvalue, 
				IF(users_capture_info.userinfotype = 'date', users_metadata.datevalue, ''))))) AS metadata 
				FROM users_capture_info LEFT JOIN users_metadata 
				ON users_capture_info.userinfoid = users_metadata.userinfoid
				WHERE users_metadata.username = '{$username}'
				AND users_capture_info.userinfoname = '{$meta_data}';
HEREDOC;

		return DAO::getSingleValue($link, $sql_userdata);
	}

	/**
	 * function to retreive user addresses if multiples are allowed.
	 * trac: #179
	 * task: {0000000044}
	 * @author: relmes
	 * @param PDO $link
	 * @param str $username
	 * @throws Exception
	 */
	/*	private function getUserAddresses( PDO $link, $username ) {
		// * check multi address functionality is activated
		// config value - user_multiaddresses 1 - active / 0 - inactive
		// if ( get_config_value('user_multiaddresses') ) {
		// 	return;			
		// 	
		// }

		// move this into a central function
		// table value
		// check the required table set up exists
		$check_tables_sql = 'SELECT table_name FROM information_schema.tables WHERE table_name in ("users_addresses") and table_schema = "'.DB_NAME.'"';
		$checked_table = DAO::getSingleColumn($link, $check_tables_sql);

		// if we don't have both the required tables kick it back
		if( sizeof($checked_table) !== 1 ) {
			return;
		}
		
		// set up the metadata array
		$this->user_metadata['multiple_addresses'] = array();
		
		// * retreive all address information
		// ?? could just check primary address ?? 
		$sql_userdata = <<<HEREDOC
			SELECT users_addresses.*
			FROM users_addresses 
			WHERE users_addresses.username = '{$username}'
HEREDOC;

		$st = $link->query($sql_userdata);
		if($st) {
			while( $row = $st->fetch() ) {			
				if ( $row['primary_address'] == 1 ) {
					// set the primary_address_title of the user
					$this->primary_address_title = $row['address_title'];
					// * replace 'home' address on learner with primary
					$this->home_paon_start_number = $row['paon_start_number'];
					$this->home_paon_start_suffix = $row['paon_start_suffix'];
					$this->home_paon_end_number = $row['paon_end_number'];
					$this->home_paon_end_suffix = $row['paon_end_suffix'];
					$this->home_paon_description = $row['paon_description'];
	
					$this->home_saon_start_number = $row['saon_start_number'];
					$this->home_saon_start_suffix = $row['saon_start_suffix'];
					$this->home_saon_end_number = $row['saon_end_number'];
					$this->home_saon_end_suffix = $row['saon_end_suffix'];
					$this->home_saon_description = $row['saon_description'];
	
					$this->home_street_description = $row['street_description'];
					$this->home_locality = $row['locality'];
					$this->home_town = $row['town'];
					$this->home_county = $row['county'];
					$this->home_postcode = $row['postcode'];	
				}
				else {
					// create a metadata record for each address row.
					array_push($this->user_metadata['multiple_addresses'], $row);
				}
			}
		}
		else {
			throw new Exception('ERR: No address data is set up for this user');
		}
		// * store all subsequent addresses
		return 1;
	}*/

	/**
	 * function to set user addresses if multiples are allowed.
	 * trac: #179
	 * task: {0000000044}
	 * @author: relmes
	 * @param PDO $link
	 * @param str $username
	 * @param array $post_data
	 * @throws Exception
	 */
	/*	public function setUserAddresses( PDO $link, $username, $post_data ) {
		
		
		$test_array = array();
		if ( $this->getUserAddresses($link, $username) ) {	
			
			
			$number_of_keys = array_keys($post_data, 'address_title');
			$address_count = 0;
			
					
			while($address_count <= sizeof($number_of_keys) ) {
				if ( isset($post_data['address_postcode'][$address_count]) ) {
					$address_sql = 'REPLACE INTO users_addresses SET ';
					$new_address = new Address($post_data, 'address_'.$address_count);
					foreach ($new_address as $address_key => $address_value ) {
						$address_sql .= $address_key.' = "'.htmlspecialchars((string)$address_value).'", ';
					}
					$address_sql .= 'telephone = "'.htmlspecialchars((string)$post_data['address_telephone'][$address_count]).'", ';
					$address_sql .= 'fax = "'.htmlspecialchars((string)$post_data['address_fax'][$address_count]).'", ';		
					$address_sql .= 'mobile = "'.htmlspecialchars((string)$post_data['address_mobile'][$address_count]).'", ';	
					$address_sql .= 'address_title = "'.htmlspecialchars((string)$post_data['address_title'][$address_count]).'", ';		
					//throw new Exception($address_sql);
				}
				$address_count++;
			}
		}
		else {
			//return;
		}
		//pre($test_array);
	}*/

	/**
	 * Handles the presentation of user addresses
	 * in order to cope with multiple address requirement
	 * trac: #179
	 * task: 0000000044
	 * @author: relmes
	 * @param PDO $link
	 * @return string
	 */
	public function displayUserAddresses ( PDO $link )
	{
		$user_address_details = '';

		$l = Location::loadFromDatabase($link, $this->employer_location_id);
		if ($l) {
			$work_address = new Address($l);
			$user_address_details .= '<h3>Work Contact Details</h3>';
			$user_address_details .= '<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">';
			$user_address_details .= '<col width="170" /><col width="400" />';
			$user_address_details .= '<tr>';
			$user_address_details .= '<td class="fieldLabel" valign="top">Address</td>';
			$user_address_details .= '<td class="fieldValue">' . $work_address->formatRead() . '</td>';
			$user_address_details .= '</tr>';
			$user_address_details .= '<tr>';
			$user_address_details .= '<td class="fieldLabel">Telephone</td>';
			$user_address_details .= '<td class="fieldValue">'.htmlspecialchars((string)$l->telephone).'</td>';
			$user_address_details .= '</tr>';
			$user_address_details .= '<tr>';
			$user_address_details .= '<td class="fieldLabel">Mobile</td>';
			$user_address_details .= '<td class="fieldValue">'.htmlspecialchars((string)$l->contact_mobile).'</td>';
			$user_address_details .= '</tr>';
			$user_address_details .= '<tr>';
			$user_address_details .= '<td class="fieldLabel">Fax</td>';
			$user_address_details .= '<td class="fieldValue">'.htmlspecialchars((string)$l->fax).'</td>';
			$user_address_details .= '</tr>';
			$user_address_details .= '<tr>';
			$user_address_details .= '<td class="fieldLabel">Email</td>';
			$user_address_details .= '<td class="fieldValue"><a href="mailto:' . htmlspecialchars((string)$this->work_email) . '">' . htmlspecialchars((string)$this->work_email).'</td>';
			$user_address_details .= '</tr>';
			$user_address_details .= '</table>';
		}

		// #179 - need to check the multi_addresses flag to output address titles here.
		$home_address = new Address($this, 'home_');
		$address_title = 'Home Contact Details';
		if ( isset($this->primary_address_title) ) {
			$address_title = $this->primary_address_title.' (Primary Address)';
		}
		$user_address_details .= '<h3>'.$address_title.'</h3>';
		$user_address_details .= '<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">';
		$user_address_details .= '<col width="170" /><col width="400" />';
		$user_address_details .= '<tr>';
		$user_address_details .= '	<td class="fieldLabel" valign="top">Address</td>';
		$user_address_details .= '	<td class="fieldValue">'.$home_address->formatRead().'</td>';
		$user_address_details .= '</tr>';
		$user_address_details .= '<tr>';
		$user_address_details .= '	<td class="fieldLabel">Telephone</td>';
		$user_address_details .= '	<td class="fieldValue">'.htmlspecialchars((string)$this->home_telephone).'</td>';
		$user_address_details .= '</tr>';
		$user_address_details .= '<tr>';
		$user_address_details .= '	<td class="fieldLabel">Mobile</td>';
		$user_address_details .= '	<td class="fieldValue">'.htmlspecialchars((string)$this->home_mobile).'</td>';
		$user_address_details .= '</tr>';
		IF(DB_NAME=="am_platinum")
		{
			$user_address_details .= '<tr>';
			$user_address_details .= '	<td class="fieldLabel">Emergency Telephone</td>';
			$user_address_details .= '	<td class="fieldValue">'.htmlspecialchars((string)$this->tel_emergency).'</td>';
			$user_address_details .= '</tr>';
		}
		$user_address_details .= '<tr>';
		$user_address_details .= '	<td class="fieldLabel">Fax</td>';
		$user_address_details .= '	<td class="fieldValue">'.htmlspecialchars((string)$this->home_fax).'</td>';
		$user_address_details .= '</tr>';
		$user_address_details .= '<tr>';
		$user_address_details .= '	<td class="fieldLabel">Email</td>';
		$user_address_details .= '	<td class="fieldValue"><a href="mailto:' . htmlspecialchars((string)$this->home_email) . '">' . htmlspecialchars((string)$this->home_email).'</td>';
		$user_address_details .= '</tr>';
		$user_address_details .= '</table>';

		// #179 - display all the other addresses stored against the user
		/*		if ( ( isset($this->user_metadata) ) && ( isset($this->user_metadata['multiple_addresses']) ) ) {
			$user_addresses = $this->user_metadata['multiple_addresses'];
			foreach ( $user_addresses as $address_label => $address_value ) {
				// reformat the address for display.
				$format_address = new Address($address_value, '');
				
				$user_address_details .= '<h3>'.htmlspecialchars((string)$address_value['address_title']).'</h3>';
				$user_address_details .= '<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">';
				$user_address_details .= '	<col width="170" /><col width="400" />';
				$user_address_details .= '	<tr>';
				$user_address_details .= '		<td class="fieldLabel" valign="top">Address</td>';
				$user_address_details .= '		<td class="fieldValue">'.$format_address->formatRead().'</td>';
				$user_address_details .= '	</tr>';
				$user_address_details .= '	<tr>';
				$user_address_details .= '		<td class="fieldLabel">Telephone</td>';
				$user_address_details .= '		<td class="fieldValue">'.htmlspecialchars((string)$address_value['telephone']).'</td>';
				$user_address_details .= '	</tr>';
				$user_address_details .= '	<tr>';
				$user_address_details .= '		<td class="fieldLabel">Mobile</td>';
				$user_address_details .= '		<td class="fieldValue">'.htmlspecialchars((string)$address_value['mobile']).'</td>';
				$user_address_details .= '	</tr>';
				$user_address_details .= '	<tr>';
				$user_address_details .= '		<td class="fieldLabel">Fax</td>';
				$user_address_details .= '		<td class="fieldValue">'.htmlspecialchars((string)$address_value['fax']).'</td>';
				$user_address_details .= '	</tr>';
				$user_address_details .= '	<tr>';
				$user_address_details .= '		<td class="fieldLabel">Email</td>';
				$user_address_details .= '		<td class="fieldValue"><a href="mailto:'.htmlspecialchars((string)$address_value['email']).'">'.htmlspecialchars((string)$address_value['email']).'</td>';
				$user_address_details .= '	</tr>';
				$user_address_details .= '</table>';
			}
			
		}*/

		return $user_address_details;
	}


	/**
	 * @param string|array[string] $type
	 * @return string
	 */
	public static function getTypeAsString($type)
	{
		if(!is_numeric($type)) {
			return '';
		}
		$type = (array) $type;

		$types = array(
			1 => "System Administrator",
			2 => "Tutor",
			3 => "Assessor",
			4 => "IQA",
			5 => "Learner",
			6 => "Other Learner",
			7 => "Salesperson",
			8 => "Manager",
			9 => "Supervisor",
			10 => "Contract Manager",
			11 => "Consultant",
			12 => "System Viewer",
			13 => "Organisation Viewer",
			14 => "School Viewer",
			15 => "Global Verifier",
			16 => "Contract Manager",
			17 => "External Verifier",
			18 => "Global Manager"
		);

		foreach ($type as &$t) {
			if(array_key_exists($t, $types)) {
				$t = $types[$t];
			}
		}

		$string = implode(',', $type);
		return $string;
	}

	/**
	 * @static
	 * @param string $uln
	 * @return bool True if the ULN has a valid value
	 */
	public static function isValidUln($uln)
	{
		$uln = trim($uln);
		$valid_pattern = "/^[1-9]{1}[0-9]{9}$/";
		$valid_pattern = preg_match($valid_pattern, $uln);
		if ($valid_pattern)	{
			$remainder = ((10 * $uln[0])
				+ (9 * $uln[1])
				+ (8 * $uln[2])
				+ (7 * $uln[3])
				+ (6 * $uln[4])
				+ (5 * $uln[5])
				+ (4 * $uln[6])
				+ (3 * $uln[7])
				+ (2 * $uln[8])) % 11;

			if ($remainder == 0)	{
				return false;
			}

			$check_digit = 10 - $remainder;
			if ($check_digit != $uln[9]) {
				return false;
			}

			return true;
		}

		return false;
	}

	public function getParticipantTargetPremiumGroups(PDO $link)
	{
		$target_premium_groups = array();
		if(isset($this->contract) && $this->contract != '' && !is_null($this->contract))
		{
			$target_premium_groups = DAO::getSingleColumn($link, "SELECT group_id FROM participant_target_premium_groups WHERE participant_id = '{$this->id}'");
		}
		return $target_premium_groups;
	}

	public function getContractTypeFromContract(PDO $link)
	{
		if(is_null($this->contract) || $this->contract == '')
			return false;

		$contract_type = DAO::getSingleValue($link, "SELECT contracts.esf_contract_type FROM contracts WHERE contracts.id = '{$this->contract}'");
		if(is_null($contract_type) || $contract_type == '')
			return false;

		return $contract_type;
	}

	/**
	 * @static
	 * @param string $ni
	 * @return bool
	 */
	public static function validateNI($ni)
	{
		if($ni)
		{
			// Check if is valid NI pattern
			$valid_pattern = "/^[A-CEGHJ-NOPR-TW-Z]{1}[A-CEGHJ-NPR-TW-Z]{1}[0-9]{6}[ABCD\s]{0,1}$/";
			$valid_pattern = preg_match($valid_pattern, $ni);
			$invalid_pattern = "/(^GB)|(^BG)|(^NK)|(^KN)|(^TN)|(^NT)|(^ZZ).+/";
			$invalid_pattern = preg_match($invalid_pattern, $ni);
			if($valid_pattern == false || $invalid_pattern == true)
			{
				return false;
			}
		}

		return true;
	}


	/**
	 * Replaces all referencecs to one or more secondary records with a reference to a
	 * specified primary record, severing all relationships to the
	 * secondary record(s).
	 *
	 * @static
	 * @param PDO $link
	 * @param int $primaryId Primary record id
	 * @param int|array[int] $secondaryId Secondary record id
	 * @param bool $deleteSecondaryRecord Remove secondary records on completion
	 * @throws Exception
	 */
	public static function merge(PDO $link, $primaryId, $secondaryId, $deleteSecondaryRecord = false)
	{
		// Validation
		if (!$primaryId) {
			throw new Exception("Empty argument: primaryId");
		}
		if (!$secondaryId) {
			throw new Exception("Empty argument: secondaryId");
		}
		if (!is_numeric($primaryId)) {
			throw new Exception("primaryId value must be numeric");
		}
		if (is_array($secondaryId)) {
			foreach ($secondaryId as $id) {
				if(!is_numeric($id)) {
					throw new Exception("secondaryId values must be numeric");
				}
			}
		} else {
			if (!is_numeric($secondaryId)) {
				throw new Exception("secondaryId value must be numeric");
			}
			$secondaryId = (array) $secondaryId;
		}

		// Retrieve usernames
		$primaryUsername = DAO::getSingleValue($link, "SELECT username FROM users WHERE id=".$primaryId);
		if (!$primaryUsername) {
			throw new Exception("Primary user record #" . $primaryId . ' not found');
		}
		$secondaryUsername = DAO::getSingleColumn($link, "SELECT username FROM users WHERE id IN(".implode(',', $secondaryId).")");
		if (count($secondaryId) != count($secondaryUsername)) {
			throw new Exception("Could not find all the secondary learner records");
		}

		// Prepare variables for use in SQL statements
		$pu = $link->quote($primaryUsername);
		$su = DAO::pdo_implode($secondaryUsername);
		$si = DAO::pdo_implode($secondaryId);

		if (DAO::schemaEntityExists($link, null, 'assessment_plan', 'assessor')) {
			DAO::execute($link, "UPDATE assessment_plan SET assessor = $pu WHERE assessor IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'assessor_review', 'assessor')) {
			DAO::execute($link, "UPDATE assessor_review SET assessor = $pu WHERE assessor IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'calendar_event', 'username')) {
			DAO::execute($link, "UPDATE calendar_event SET username = $pu WHERE username IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'candidate', 'username')) {
			DAO::execute($link, "UPDATE candidate SET username = $pu WHERE username IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'candidate', 'assessor')) {
			DAO::execute($link, "UPDATE candidate SET assessor = $pu WHERE assessor IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'candidate_notes', 'username')) {
			DAO::execute($link, "UPDATE candidate_notes SET username = $pu WHERE username IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'course_qualifications_dates', 'tutor_username')) {
			DAO::execute($link, "UPDATE course_qualifications_dates SET tutor_username = $pu WHERE tutor_username IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'courses', 'username')) {
			DAO::execute($link, "UPDATE courses SET username = $pu WHERE username IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'courses', 'director')) {
			DAO::execute($link, "UPDATE courses SET director = $pu WHERE director IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'employerpool_notes', 'username')) {
			DAO::execute($link, "UPDATE employerpool_notes SET username = $pu WHERE username IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'employment', 'username')) {
			DAO::execute($link, "UPDATE employment SET username = $pu WHERE username IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'error_log', 'username')) {
			DAO::execute($link, "UPDATE error_log SET username = $pu WHERE username IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'groups', 'tutor')) {
			DAO::execute($link, "UPDATE groups SET tutor = $pu WHERE tutor IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'groups', 'old_tutor')) {
			DAO::execute($link, "UPDATE groups SET old_tutor = $pu WHERE old_tutor IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'groups', 'assessor')) {
			DAO::execute($link, "UPDATE groups SET assessor = $pu WHERE assessor IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'groups', 'verifier')) {
			DAO::execute($link, "UPDATE groups SET verifier = $pu WHERE verifier IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'groups', 'old_assessor')) {
			DAO::execute($link, "UPDATE groups SET old_assessor = $pu WHERE old_assessor IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'groups', 'old_verifier')) {
			DAO::execute($link, "UPDATE groups SET old_verifier = $pu WHERE old_verifier IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'groups', 'wbcoordinator')) {
			DAO::execute($link, "UPDATE groups SET wbcoordinator = $pu WHERE wbcoordinator IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'groups', 'old_wbcoordinator')) {
			DAO::execute($link, "UPDATE groups SET old_wbcoordinator = $pu WHERE old_wbcoordinator IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'health_safety', 'assessor')) {
			DAO::execute($link, "UPDATE health_safety SET assessor = $pu WHERE assessor IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'ilr_audit', 'username')) {
			DAO::execute($link, "UPDATE ilr_audit SET username = $pu WHERE username IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'lesson_notes', 'username')) {
			DAO::execute($link, "UPDATE lesson_notes SET username = $pu WHERE username IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'lessons', 'tutor')) {
			DAO::execute($link, "UPDATE lessons SET tutor = $pu WHERE tutor IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'logins', 'username')) {
			DAO::execute($link, "UPDATE logins SET username = $pu WHERE username IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'logins', 'user_id')) {
			DAO::execute($link, "UPDATE logins SET user_id = $primaryId WHERE user_id IN($si);");
		}
		if (DAO::schemaEntityExists($link, null, 'logins_unsuccessful', 'username')) {
			DAO::execute($link, "UPDATE logins_unsuccessful SET username = $pu WHERE username IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'notes', 'username')) {
			DAO::execute($link, "UPDATE notes SET username = $pu WHERE username IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'people_group_definitions', 'member')) {
			DAO::execute($link, "UPDATE people_group_definitions SET member = $pu WHERE member IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'people_group_members', 'member')) {
			DAO::execute($link, "UPDATE people_group_members SET member = $pu WHERE member IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'register_entry_notes', 'username')) {
			DAO::execute($link, "UPDATE register_entry_notes SET username = $pu WHERE username IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'student_qualifications', 'username')) {
			DAO::execute($link, "UPDATE student_qualifications SET username = $pu WHERE username IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'tr', 'username')) {
			DAO::execute($link, "UPDATE tr SET username = $pu WHERE username IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'tr', 'assessor')) {
			DAO::execute($link, "UPDATE tr SET assessor = $pu WHERE assessor IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'tr', 'tutor')) {
			DAO::execute($link, "UPDATE tr SET tutor = $pu WHERE tutor IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'tr', 'verifier')) {
			DAO::execute($link, "UPDATE tr SET verifier = $pu WHERE verifier IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'tr_notes', 'username')) {
			DAO::execute($link, "UPDATE tr_notes SET username = $pu WHERE username IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'tr_unit_progress', 'username')) {
			DAO::execute($link, "UPDATE tr_unit_progress SET username = $pu WHERE username IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'users', 'supervisor')) {
			DAO::execute($link, "UPDATE users SET supervisor = $pu WHERE supervisor IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'user_saved_filters', 'username')) {
			DAO::execute($link, "UPDATE user_saved_filters SET username = $pu WHERE username IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'users_metadata', 'username')) {
			DAO::execute($link, "UPDATE users_metadata SET username = $pu WHERE username IN($su);");
		}
		if (DAO::schemaEntityExists($link, null, 'view_columns', 'user')) {
			DAO::execute($link, "UPDATE view_columns SET `user` = $pu WHERE `user` IN($su);");
		}

		if ($deleteSecondaryRecord) {
			DAO::execute($link, "DELETE FROM users WHERE id IN($si)");
		}
	}

	public function getParticipantSelfDecRecords(PDO $link)
	{
		return (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM participant_self_dec WHERE participant_id = '{$this->id}'");
	}

	public function updateParticipantStatus(PDO $link, $new_status)
	{
		DAO::execute($link, "UPDATE users SET users.participant_status = '{$new_status}' WHERE users.id = '{$this->id}'");
	}

	public function isThereAnyLiteracyExemptedDiagnostics(PDO $link)
	{
		$check = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM participant_assessment WHERE participant_id = '{$this->id}' AND status = '" . ParticipantAssessment::EXEMPTED . "' AND type = '1'");
		if($check > 0)
			return DAO::getSingleValue($link, "SELECT id FROM participant_assessment WHERE participant_id = '{$this->id}' AND status = '" . ParticipantAssessment::EXEMPTED . "' AND type = '1' ORDER BY id DESC LIMIT 1");
		else
			return false;
	}

	public function isThereAnyNumeracyExemptedDiagnostics(PDO $link)
	{
		$check = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM participant_assessment WHERE participant_id = '{$this->id}' AND status = '" . ParticipantAssessment::EXEMPTED . "' AND type = '2'");
		if($check > 0)
			return DAO::getSingleValue($link, "SELECT id FROM participant_assessment WHERE participant_id = '{$this->id}' AND status = '" . ParticipantAssessment::EXEMPTED . "' AND type = '2' ORDER BY id DESC LIMIT 1");
		else
			return false;
	}

	public function getNotifications(PDO $link)
	{
		$result = new stdClass();
		$result->unread_notifications = 0;
		$result->total_notifications = 0;
		$result->notifications = array();
		$notifications = array();
		// check for workbooks
		$results = DAO::getResultset($link, "SELECT * FROM user_notifications WHERE `user_id` = '{$this->id}' ORDER BY created DESC ", DAO::FETCH_ASSOC);
		foreach($results AS $row)
		{
			$result->unread_notifications += $row['checked'] == 0 ? 1 : 0;

			$item = '';
			$item .= $row['checked'] == 0 ? '<li class="bg-gray">' : '<li>';
			//$item .= '<a class="clsNotificationsMenuItem" id="' . $row['id'] . '" href="#" onclick="return window.phpAssessorSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'' . $row['link'] . '\'">' . $row['detail'] . '<br><span class="fa fa-clock-o"></span> ' . Date::to($row['created'], Date::DATETIME) . '</a>';
			if($row['checked'] == 0)
			{
				$item .= '<a class="clsNotificationsMenuItem" id="' . $row['id'] . '" href="#">'.$row['detail'].'<br>';
				$item .= '<span style="display:none;" id="clsNotificationsMenuItemLink">'.$row['link'].'</span>';
				$item .= '<span class="fa fa-clock-o"></span> ' . Date::to($row['created'], Date::DATETIME) . '</a>';
			}
			else
			{
				$item .= '<a href="#" onclick="return window.phpAssessorSignature == \'\' ? alert(\'Please first create your signature\') : window.location.href=\'' . $row['link'] . '\'">' . $row['detail'] . '<br><span class="fa fa-clock-o"></span> ' . Date::to($row['created'], Date::DATETIME) . '</a>';
			}
			$item .= '</li>';
			$notifications[] = $item;
		}
		$result->total_notifications = count($notifications);
		$result->notifications = $notifications;

		return $result;
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
			$exclude_fields = array('participant_status');
		}

		$changes_list = parent::buildAuditLogString($link, $new_vo, $exclude_fields);

		// Test each of the exceptions separately
		if($this->participant_status != $new_vo->participant_status)
		{
			$lookup = DAO::getLookupTable($link, "SELECT id, description FROM lookup_participant_status ORDER BY id");
			$from = isset($lookup[$this->participant_status]) ? $lookup[$this->participant_status] : $this->participant_status;
			$to = isset($lookup[$new_vo->participant_status]) ? $lookup[$new_vo->participant_status] : $new_vo->participant_status;
			$changes_list .= "[Participant Status] changed from '$from' to '$to'\n";
		}

		return $changes_list;
	}

	public function getForskillsUser(PDO $link)
	{
		return DAO::getObject($link, "SELECT * FROM forskills_users WHERE sunesis_username = '{$this->username}'");
	}

	/** @var int */
	public $id = NULL;
	public $firstnames = NULL;
	public $surname = NULL;
	public $username = NULL;
	public $dob = NULL;
	public $ni = NULL;
	public $uln = NULL;
	public $upi = NULL;
	public $upn = NULL;
	public $gender = 'U';
	public $ethnicity = 23;

	public $employer_id = NULL;
	public $employer_location_id = NULL;
	public $department = NULL;
	public $job_role = NULL;

	public $public_key = NULL;
	public $public_key_type = NULL;

	public $use_x509_authentication = NULL;
	public $x509_serial = NULL;
	public $x509_validity_start = NULL;
	public $x509_validity_end = NULL;
	public $x509_subject_dn = NULL;
	public $x509_issuer_dn = NULL;
	public $x509_certificate = NULL;

	public $web_access = 1; // Default to allowing access
	public $record_status = 1; // Default to 'live'

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

	public $work_telephone = NULL;
	public $work_mobile = NULL;
	public $work_fax = NULL;
	public $work_email = NULL;

	public $created = NULL;
	public $modified = NULL;

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

	public $home_telephone = NULL;
	public $home_mobile = NULL;
	public $home_fax = NULL;
	public $home_email = NULL;
	public $ifl = NULL;
	public $crb = 0;
	public $bennett_test = NULL;
	public $enrollment_no = NULL;
	public $numeracy = NULL;
	public $literacy = NULL;
	public $esol = NULL;
	public $supervisor = NULL;

	// Security reponsibilities
	public $acl_filters = NULL;
	public $acl_adopted_identities = NULL;


	// Password fields
	// (transitional to storing unhashed password)
	public $password = NULL;
	public $pwd_sha1 = NULL;


	// These fields do not exist in the database table
	public $org = NULL;
	public $loc = NULL;
	public $org_short_name = NULL;
	public $loc_short_name = NULL;
	public $type = NULL;
	public $org_legal_name = NULL;
	public $role = NULL;

	public $identities = NULL;
	public $certificates = NULL;
	public $is_admin = NULL;
	public $is_organisation_creator = NULL;
	public $is_people_creator = NULL;
	public $is_org_admin = NULL;
	/*public $is_workplace = NULL;
	public $is_funding = NULL;
	public $is_sub_contractor = NULL;
	public $is_salesman = 0;
	public $is_manager = 0;
	public $is_HSAuditor = 0;
	public $is_compliance = 0;*/
	public $clipboardType = NULL;
	public $clipboard = NULL;
	public $clipboardNode = NULL;
	public $numeracy_diagnostic = NULL;
	public $literacy_diagnostic = NULL;
	public $esol_diagnostic = NULL;
	public $isEmployerAdmin = NULL;
	public $last_logged_in= NULL;

	public $ict = NULL;
	public $ict_diagnostic = NULL;

	// ILR Fields
	public $l24 = NULL;
	public $l14 = NULL;
	public $l15 = NULL;
	public $l16 = NULL;
	public $l35 = NULL;
	public $l34a = NULL;
	public $l34b = NULL;
	public $l34c = NULL;
	public $l34d = NULL;
	public $l36 = NULL;
	public $l37 = NULL;
	public $l47 = NULL;
	public $l48 = NULL;
	public $l28a = NULL;
	public $l28b = NULL;
	public $l39 = NULL;
	public $l40a = NULL;
	public $l40b = NULL;
	public $l41a = NULL;
	public $l41b = NULL;
	public $l42a = NULL;
	public $l42b = NULL;
	public $l45 = NULL;
	public $who_created = NULL;
	public $nationality = NULL;
	public $enteredOnToDigiApp = NULL; // for Edudo
	public $referral_source = NULL;// for Reed
	public $initial_appointment_date = NULL; // for Reed
	public $referral_date = NULL; //for Reed
	public $learner_find_agent = NULL;//added for LCurve
	public $ref_source_other_desc = NULL;//added for Reed
	public $verification_type = NULL;//added for miap
	public $verification_type_other = NULL;//added for miap
	public $ability_to_share = NULL;//added for miap
	public $place_of_birth = NULL;//added for miap

	public $ld1 = NULL;//added for lead
	public $ld2 = NULL;//added for lead

	//for reed work routes reporting
	public $initial_interview_date = NULL;
	public $job_goal_1 = NULL;
	public $job_goal_2 = NULL;
	public $job_goal_3 = NULL;
	public $job_readiness = NULL;
	public $prev_school = NULL;
	public $employer_business_code = NULL;

	public $gcse_eng = NULL;
	public $gcse_maths = NULL;
	public $tel_emergency = NULL;
	public $initially_engaged_by = NULL;

	public $age_grant = NULL;
	public $pin = NULL;

	// relmes - should this not be private...
	public $user_metadata = array();

	public $smart_assessor_id = null;
	public $ecordia_id = null;

	public $date_password_reset = NULL;

	public $learner_office = NULL;
	public $signposting_org = NULL;

	public $uci_number = NULL;// for platinum
	public $candidate_number = NULL;// for platinum

	public $is_participant = NULL;
	public $contract = NULL;
	public $is_lone_parent = NULL;
	public $primary_lldd = NULL;
	public $lldd_cat = NULL;
	public $no_basic_skills = NULL;
	public $participant_status = NULL;
	public $hhs = NULL;
	public $lsr = NULL;
	public $fme = NULL;
	public $id_seen = NULL;
	public $id_seen_type = NULL;
	public $id_seen_other_desc = NULL;
	public $id_seen_passport_number = NULL;
	public $lou = NULL;
	public $adviser = NULL;
	public $payroll_number  = NULL; // for gi group
    	public $learner_work_email  = NULL; // for gi group
	public $induction_access  = NULL; 
	public $induction_menus = null;
	public $op_access = null;
	public $op_menus = null;
	public $signature = null;
	public $nok_title = null;
	public $nok_name = null;
	public $nok_rel = null;
	public $nok_mob = null;
	public $nok_tel = null;
	public $nok_email = null;
	public $abr_number = null;
	public $ucas = null;
	public $rui = null;
	public $pmc = null;
	public $pass_to_als = null;
	public $high_level = null;
	public $other_diagnostic = null;
	public $other = null;
	public $eng_first = null;
	public $created_by = null;
	public $fs_progress_tab = null;
	public $fs_progress_access = null;
	public $capacity = null;
	public $literacy_other = null;
	public $numeracy_other = null;
	public $imi_candidate_number = null;

	const TYPE_ADMIN = 1;
	const TYPE_TUTOR = 2;
	const TYPE_ASSESSOR = 3;
	const TYPE_VERIFIER = 4;
	const TYPE_LEARNER = 5;
	const TYPE_OTHER_LEARNER = 6;
	const TYPE_SALESPERSON = 7;
	const TYPE_MANAGER = 8;
	const TYPE_SUPERVISOR = 9;
	const TYPE_CONTRACT_MANAGER = 10;
	const TYPE_CONSULTANT = 11;
	const TYPE_SYSTEM_VIEWER = 12;
	const TYPE_ORGANISATION_VIEWER = 13;
	const TYPE_SCHOOL_VIEWER = 14;
	const TYPE_GLOBAL_VERIFIER = 15;
	const TYPE_CONTRACT_MANAGER_2 = 16; // Duplicate of 10
	const TYPE_EXTERNAL_VERIFIER = 17;
	const TYPE_GLOBAL_MANAGER = 18;
	const TYPE_BRAND_MANAGER = 19;
	const TYPE_APPRENTICE_COORDINATOR = 20;
	const TYPE_COURSE_DIRECTOR = 21;
	const TYPE_APPRENTICE_RECRUITMENT_TEAM_MEMBER = 22;
	const TYPE_BUSINESS_RESOURCE_MANAGER = 23;
	const TYPE_TELESALES = 24;
	const TYPE_REVIEWER = 25;
	const TYPE_STORE_MANAGER = 26;
	const TYPE_CRM_FRON_DESK_USER = 30;

	const JOB_ROLE_LINE_MANAGER = 1;

	public $northing = null;
	public $easting = null;
	public $longitude = null;
	public $latitude = null;

	public $line_manager = null;
	public $line_manager_tel = null;
	public $line_manager_email = null;
	public $trainer = null;
	public $imi_redeem_code = null;
	public $duplex_status = null;
	public $active = null;
	public $bypass_postcode = null;
	public $iqa = null;
	public $reduced_sample = null;
	public $password_changed_at = null;
	public $ob_access_only = null;
	public $duplex_emp_status = null;
	public $duplex_funding_available = null;
	public $onefile_organisation_id = null;
	public $onefile_user_id = null;
	public $chk_numeracy_diagnostic = null;
	public $grade_numeracy_diagnostic = null;
	public $grade_numeracy_diagnostic_other = null;
	public $chk_literacy_diagnostic = null;
	public $grade_literacy_diagnostic = null;
	public $grade_literacy_diagnostic_other = null;
	public $support_contact_id = null;

	protected $audit_fields = array(
		'firstnames'=>'First names'
		,'surname'=>'Surname'
		,'adviser'=>'Adviser'
	);

}
?>