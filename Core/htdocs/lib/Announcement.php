<?php
class Announcement extends ValueObject
{
	public static function loadFromDatabase(PDO $link, $id)
	{
		if($id == '' || !is_numeric($id)){
			return null;
		}
		
		$object = null;
		
		$key = addslashes((string)$id);
		$sql = <<<HEREDOC
SELECT
	announcements.*,
	'Perspective' AS `org_legal_name`,
	'Perspective' AS `organisations_legal_name`,
	'Perspective' AS `organisations_short_name`,
	users.username AS `user_username`,
	users.firstnames AS `user_firstnames`,
	users.surname AS `user_surname`
FROM
	announcements LEFT OUTER JOIN users
		ON announcements.users_id = users.username

WHERE
	announcements.id='$key';
HEREDOC;

		if($st = $link->query($sql))
		{
			if( $row = $st->fetch() ) 
			{
				$object = new Announcement();
				$object->populate($row);
			}
			else
			{
				return null;
			}

			//mysqli_free_result($result);
			
			//// Add ACL entries
			//$sql = "SELECT org_id FROM announcement_acl WHERE announcements_id='".addslashes((string)$id)."';";
			//$acl = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
			//if(!is_null($acl)){
			//	$object->acl_entries = $acl;
			//}
			
			// Correct organisation
			//if(!$object->organisations_id){
			//	$object->organisations_legal_name = "Perspective";
			//	$object->organisations_short_name = "Perspective";
			//}
		}
		else
		{
			// throw new Exception($link, mysqli_errno($link), $sql);
		}
		
		return $object;	
	}
	
	
	public function save(PDO $link)
	{
		$this->modified = "";
		$this->created = ($this->id == "") ? "" : $this->created;
		
		$this->content = trim($this->content);
		
    	DAO::saveObjectToTable($link, 'announcements', $this);
    	
    	
	}
	
	
	public function delete(PDO $link, $id = null)
	{
		if(is_null($id))
		{
			$id = $this->id;
		}
		
		if(is_array($id))
		{
			$id = DAO::mysqli_implode($id);
		}
    	
		DAO::execute($link, "DELETE FROM announcements WHERE id IN(".$id.") OR parent_id IN(".$id.")");
    	//DAO::execute($link, "DELETE FROM announcement_acl WHERE announcements_id IN(".$id.")");
    	
    	return true;
    }
    
    
    public function isSafeToDelete(PDO $link, $record_id = null)
    {
    	return true;
	}
	

	public function isReader(PDO $link, User $user = null)
	{
		if(is_null($user)){
			$user = $_SESSION['user'];
		}
		
		if(!$org->id){
			return true; // Sys admin
		}
		
		if($user->organisations_org_type_id == ORG_PARTNERSHIP)
		{
			if($this->all_partnerships){
				return true;
			}
			foreach($this->acl_entries as $entry)
			{
				if($entry['partnership_id'] == $org->id){
					return true;
				}
			}
		}
		

		return false;		
	}
	
	
	public function isEditor(PDO $link, User $user = null)
	{
		if(is_null($user)){
		//	$user = $_SESSION['user'];
		}
		
		//if($user->role == "admin"){
			return true;
	//	}
		
		//return $this->organisations_id == $user->organisations_id && ($this->users_id == $user->id || $user->isLocalAdmin());
	}
	
	public function updateMostRecentCommentTimestamp(PDO $link)
	{
		// Announcements only -- if this is a comment, return early
		if($this->parent_id){
			return;
		}
		
		if(!$this->id){
			return;
		}
		
		$sql = <<<HEREDOC
UPDATE
	announcements LEFT OUTER JOIN
		(SELECT
			parent_id AS `announcements_id`,
			MAX(modified) AS `most_recent_comment`
		FROM
			announcements
		WHERE
			parent_id IS NOT NULL
			AND parent_id = {$this->id}
		GROUP BY
			parent_id) AS `sub`
	ON announcements.id = `sub`.announcements_id
SET
	announcements.`most_recent_comment` = sub.most_recent_comment,
	announcements.modified = announcements.modified
WHERE
	announcements.id = {$this->id}
HEREDOC;
		DAO::execute($link, $sql);
	}
	
	
	public $id;
	public $parent_id;
	public $title;
	public $subtitle;
	public $publication_date;
	public $expiry_date;
	public $content;
	public $sticky;
	public $users_id;
	public $organisations_id;
	public $organisations_legal_name;
	public $organisations_short_name;
	public $author;
	
	//public $all_partnerships;
	//public $all_schools;
	//public $all_providers;
	
	public $created;
	public $modified;
	
	public $user_firstnames;
	public $user_surname;
	
//	private $acl_entries = array();
}
?>