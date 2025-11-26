<?php
/**
 * User: Richard Elmes
 * Date: 10/05/12
 * Time: 12:56
 */
class CaptureInfo extends Entity {

	public static function loadFromDatabase(PDO $link, $id) {

		if( $id == '' ) {
			return null;
		}

		$key = addslashes((string)$id);
		$query = <<<HEREDOC
SELECT
	*
FROM
	users_capture_info
WHERE
	userinfoid='$key'
LIMIT 1;
HEREDOC;
		$st = $link->query($query);

		$user_capture_info = null;
		if( $st ) {
			$user_capture_info = null;
			$row = $st->fetch();
			if( $row ) {
				$user_capture_info = new CaptureInfo();
				$user_capture_info->populate($row);
			}
		}
		else {
			throw new Exception("Could not execute database query to find user capture information. " . '----' . $query . '----' . $link->errorCode());
		}

		return $user_capture_info;
	}

	public function save(PDO $link)	{


		if ( !isset($this->compulsory) ) {
			$this->compulsory = 0;
		}
		elseif( $this->compulsory != 0 ) {
			$this->compulsory = 1;
		}

		// check and create a new info group based upon the sector types
		if ( isset($this->infogroupid) && $this->infogroupid != "" && !is_numeric($this->infogroupid) ) {
			$this->infogroupname = $this->infogroupid;
			$this->infogroupid = DAO::getSingleValue($link, 'select max(infogroupid)+1 from users_capture_info');
		}
		else {
			$this->infogroupname = DAO::getSingleValue($link, 'select infogroupname from users_capture_info where infogroupid = '.$this->infogroupid);
		}

		return DAO::saveObjectToTable($link, 'users_capture_info', $this);
	}


	public function delete(PDO $link) {

		if( !$this->isSafeToDelete($link) ) {
			throw new Exception("This capture info has associated entries! We cannot remove it");
		}

		$sql = <<<HEREDOC
DELETE FROM
	users_capture_info where userinfoid={$this->userinfoid}
HEREDOC;
		DAO::execute($link, $sql);
	}


	public function isSafeToDelete(PDO $link) {
		// check in the candidate metadata
		$num_users = "SELECT COUNT(*) FROM candidate_metadata WHERE userinfoid={$this->userinfoid}";

		$num_users = DAO::getSingleValue($link, $num_users);
		// check on the users metadata
		if ( $num_users == 0 ) {
			$num_users = "SELECT COUNT(*) FROM users_metadata WHERE userinfoid={$this->userinfoid}";
			$num_users = DAO::getSingleValue($link, $num_users);
		}

		if ( $num_users == 0 ) {
			return true;
		}
		return false;
	}


	public $userinfoid = null;
	public $userinfoname = null;
	public $userinfotype = null;
	public $infoorder = null;
	public $infogroupid = null;
	public $infogroupname = null;
	public $compulsory = 0;
	public $lookupvalues = null;
	public $scorevalues = null;

}
?>