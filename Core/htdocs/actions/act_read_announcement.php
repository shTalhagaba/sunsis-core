<?php
class read_announcement implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		$comment_id = isset($_REQUEST['comment_id']) ? $_REQUEST['comment_id'] : '';
		$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';
		
		switch(strtolower($subaction))
		{
			case 'savecomment':
				$this->saveComment($link);
				return;
				
			case 'loadcomment':
				$this->loadComment($link);
				return;
				
			case 'deletecomment':
				$this->deleteComment($link);
				return;
				
			default:
				break;
		}
		
		
		
		if(!$id || !is_numeric($id)){
			throw new Exception("Missing or non-numeric querystring argument 'id'");
		}
		
		$vo = Announcement::loadFromDatabase($link, $id);
		if(!$vo){
			throw new Exception("No record of id #".$id." found");
		}
		
		$_SESSION['bc']->add($link, "do.php?_action=edit_announcement", "Edit Announcements");
		
			
		include("tpl_read_announcement.php");
	}
/*	
	private function renderAudience(PDO $link, Announcement $vo)
	{
		echo "<h4>Partnerships</h4>";
		echo '<div id="PartnershipList">';
		if($vo->all_partnerships)
		{
			$label = $vo->organisations_id ? "All related" : "All";
			echo '<p><span class="AllOrganisationChip">'.$label.'</span></p>';
		}
		else
		{
			$sql = <<<HEREDOC
SELECT
	organisations.id, organisations.legal_name, organisations.short_name
FROM
	announcement_acl INNER JOIN organisations
		ON announcement_acl.org_id = organisations.id
WHERE
	announcement_acl.announcements_id = {$vo->id}
	AND organisations.org_type_id = 4
ORDER BY
	organisations.legal_name
HEREDOC;
			$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
			if(count($rows))
			{
				foreach($rows as $row)
				{
					echo '<p><span class="SelectedOrganisationChip" title="'.$row['legal_name'].'">' . $row['short_name'] . '</span></p>';
				}
			}
			else
			{
				echo '<p><span class="NullOrganisationChip">None</span></p>';
			}
		}
		echo '</div>';
		

	
		echo "<h4>Home Schools</h4>";
		echo '<div id="SchoolList">';
		if($vo->all_schools)
		{
			$label = $vo->organisations_id ? "All related" : "All";
			echo '<p><span class="AllOrganisationChip">'.$label.'</span></p>';
		}
		else
		{
			$sql = <<<HEREDOC
SELECT
	organisations.id,
	CONCAT(organisations.legal_name, ' (', IFNULL(organisations.la,''), '/', IFNULL(organisations.estab,''), ')') AS `legal_name`,
	CONCAT(organisations.short_name, ' (', IFNULL(organisations.la,''), '/', IFNULL(organisations.estab,''), ')') AS `short_name`
FROM
	announcement_acl INNER JOIN organisations
		ON announcement_acl.org_id = organisations.id
WHERE
	announcement_acl.announcements_id = {$vo->id}
	AND organisations.org_type_id = 1
ORDER BY
	organisations.legal_name
HEREDOC;
			$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
			if(count($rows))
			{
				foreach($rows as $row)
				{
					echo '<p><span class="SelectedOrganisationChip" title="'.$row['legal_name'].'">' . $row['short_name'] . '</span></p>';
				}
			}
			else
			{
				echo '<p><span class="NullOrganisationChip">None</span></p>';
			}
		}
		echo '</div>';		
	
	
		echo "<h4>Lesson Providers</h4>";
		echo '<div id="ProviderList">';
		if($vo->all_providers)
		{
			$label = $vo->organisations_id ? "All related" : "All";
			echo '<p><span class="AllOrganisationChip">'.$label.'</span></p>';
		}
		else
		{
			$sql = <<<HEREDOC
SELECT
	organisations.id,
	CONCAT(organisations.legal_name, ' (', IFNULL(organisations.la,''), '/', IFNULL(organisations.estab,''), ')') AS `legal_name`,
	CONCAT(organisations.short_name, ' (', IFNULL(organisations.la,''), '/', IFNULL(organisations.estab,''), ')') AS `short_name`
FROM
	announcement_acl INNER JOIN organisations
		ON announcement_acl.org_id = organisations.id
WHERE
	announcement_acl.announcements_id = {$vo->id}
	AND organisations.org_type_id = 2
ORDER BY
	organisations.legal_name
HEREDOC;
			$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
			if(count($rows))
			{
				foreach($rows as $row)
				{
					echo '<p><span class="SelectedOrganisationChip" title="'.$row['legal_name'].'">' . $row['short_name'] . '</span></p>';
				}
			}
			else
			{
				echo '<p><span class="NullOrganisationChip">None</span></p>';
			}
		}
		echo '</div>';
	}
	
	
	private function renderAuthor(PDO $link, Announcement $vo)
	{
		$user = User::loadFromDatabase($link, $vo->users_id);
		if(!$user){
			return;
		}
		
		echo '<table cellspacing="4" cellpadding="4" border="0" style="color:#555555;margin-top:10px;table-layout:fixed" width="200">';
		echo '<col width="50"/><col width="150"/>';
		echo '<tr><td valign="top" style="font-weight:bold">Name:</td><td style="word-wrap: break-word;">'.$user->firstnames.' '.$user->surname.'</td></tr>';
		echo '<tr><td valign="top" style="font-weight:bold">Org:</td><td>'.HTML::abbr($vo->organisations_legal_name, $vo->organisations_short_name).'</td></tr>';
		echo '<tr><td valign="top" style="font-weight:bold">Email:</td><td style="word-wrap: break-word;">'.htmlspecialchars((string)$user->email).'</td></tr>';
		echo '<tr><td valign="top" style="font-weight:bold">Tel:</td><td style="word-wrap: break-word;">'.htmlspecialchars((string)$user->telephone).'</td></tr>';
		echo '</table>';
	}
	

	private function renderPublicationDates(PDO $link, Announcement $vo)
	{
		$user = User::loadFromDatabase($link, $vo->users_id);
		if(!$user){
			return;
		}
		
		echo '<table cellspacing="4" cellpadding="4" border="0" style="color:#555555;margin-top:10px;">';
		echo '<tr><td valign="top" style="font-weight:bold">Publication:</td><td>'.Date::to($vo->publication_date, Date::SHORT).'</td></tr>';
		echo '<tr><td valign="top" style="font-weight:bold">Expiry:</td><td>'.Date::to($vo->expiry_date, Date::SHORT).'</td></tr>';
		echo '</table>';
	}
	
	private function renderTimestamps(PDO $link, Announcement $vo)
	{
		$user = User::loadFromDatabase($link, $vo->users_id);
		if(!$user){
			return;
		}
		
		echo '<table cellspacing="4" cellpadding="4" border="0" style="color:#555555;margin-top:10px;">';
		echo '<tr><td valign="top" style="font-weight:bold">Created:</td><td>'.Date::to($vo->created, Date::DATETIME).'</td></tr>';
		echo '<tr><td valign="top" style="font-weight:bold">Modified:</td><td>'.Date::to($vo->modified, Date::DATETIME).'</td></tr>';
		echo '</table>';
	}
	
*/	
	private function renderContent(PDO $link, Announcement $vo)
	{
		

		$user = $_SESSION['user'];
		$org = $_SESSION['user']->org;
		$today = new Date("now");
		$ts_last_login = Date::toTimestamp(Date::toMySQL($user->last_logged_in));
		$ts_publication = Date::toTimestamp($vo->publication_date);
		$ts_modified = Date::toTimestamp(Date::toMySQL($vo->modified));
		
		//$last_login_date = new Date($user->last_logged_in);
		
	echo '<div class="Announcement">';
		
		
		if(!$vo->publication_date)
		{
			echo '<div class="DateOld">&nbsp;</div>';
			echo '<div class="Title">'.htmlspecialchars((string)$vo->title).'</div>';		
		}

		else if($ts_last_login <= $ts_publication || $ts_last_login <= $ts_modified)
		{
			echo '	<div class="DateOld">';
			echo '		<div class="Month">'.Date::to($vo->publication_date, "M").'</div>';
			echo '		<div class="Day">'.Date::to($vo->publication_date, "d").'</div>';
			echo '	</div>';

			echo '<div class="Title">'.htmlspecialchars((string)$vo->title).'</div>';
			echo '<div class="Subtitle">'.htmlspecialchars((string)$vo->subtitle).'</div>';
			//echo '<div class="Title"><span class="NewContent"><img src="/images/new.png" width="30" height="30"/></span>'.htmlspecialchars((string)$vo->title).'</div>';
		}

		else
		{
			echo '	<div class="DateOld">';
				echo '		<div class="Month">'.Date::to($vo->publication_date, "M").'</div>';
				echo '		<div class="Day">'.Date::to($vo->publication_date, "j").'</div>';
			echo '	</div>';
			echo '<div class="Title">'.htmlspecialchars((string)$vo->title).'</div>';	
			echo '<div class="Subtitle">'.htmlspecialchars((string)$vo->subtitle).'</div>';		
		}	
			echo '<div class="Body longcontent ">'.HTML::wikify($vo->content).'</div>';
			echo '<div class="Meta">by '.$vo->author.' ('.$vo->organisations_legal_name . ')</div>';
			echo '</div>';
		
		
			
	}
/*
	private function renderComments(PDO $link, Announcement $vo)
	{
		$user = $_SESSION['user'];
		$org = $_SESSION['user']->org;
		
		$today = new Date("now");
	//	$ts_last_login = Date::toTimestamp(Date::toMySQL($user->last_logged_in));
		
		$db_name = DB_NAME;
		$sql = <<<HEREDOC
SELECT
	announcements.*,
	IFNULL(organisations.legal_name,"Perspective") AS `org_legal_name`,
	users.firstnames AS `user_firstnames`,
	users.surname AS `user_surname`
FROM
	announcements LEFT OUTER JOIN users
		ON announcements.users_id = users.id AND announcements.database_name = '$db_name'
	LEFT OUTER JOIN organisations
		ON users.employer_id = organisations.id
WHERE
	announcements.parent_id={$vo->id}		
HEREDOC;
		$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		
		foreach($rows as $row)
		{
			$ts_publication = Date::toTimestamp($row['publication_date']);
			$ts_modified = Date::toTimestamp(Date::toMySQL($row['modified']));
			
			echo '<div class="Comment">';
			echo '<div class="DateOld"><div class="Month">'.Date::to($row['publication_date'], "M")
				.'</div><div class="Day">'.Date::to($row['publication_date'], "d").'</div></div>';
			if($ts_last_login <= $ts_publication || $ts_last_login <= $ts_modified)
			{
				echo '<div class="Title"><span class="NewContent"><img src="/images/white-star-10.gif" width="11" height="10"/></span>'.$row['user_firstnames'].' '.$row['user_surname'].' ('.$row['organisations_legal_name'] . ')</div>';
			}
			else
			{
				echo '<div class="Title">'.$row['user_firstname'].' '.$row['user_surname'].' ('.$row['organisations_legal_name'] . ')</div>';		
			}
			
			echo '<div class="Body">'.HTML::wikify($row['content']).'</div>';
			
			if($user->isSysAdmin() || ($row['organisations_id'] == $org->id && ($user->id == $row['users_id'] || $user->isLocalAdmin())) )
			{
				echo '<div class="Links"><a href="" onclick="deleteComment('.$row['id'].'); return false;">delete</a> | ';
				echo '<a href="" onclick="editComment('.$row['id'].'); return false;">edit</a></div>';
			}
			
			echo '</div>';			
		}
	}
	

	private function loadComment(PDO $link)
	{
		$comment_id = isset($_REQUEST['comment_id']) ? $_REQUEST['comment_id'] : '';
		$comment = Announcement::loadFromDatabase($link, $comment_id);
		
		$obj = new stdClass();
		$obj->id = $comment->id;
		$obj->parent_id = $comment->parent_id;
		$obj->content = $comment->content;
		
		header("Content-Type: application/json");
		echo Text::json_encode_latin1($comment);
	}
	
	private function saveComment(PDO $link)
	{
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
		$comment_id = isset($_REQUEST['comment_id']) ? $_REQUEST['comment_id'] : null;
		$comment_content = isset($_REQUEST['comment']) ? $_REQUEST['comment'] : '';
		
		$user = $_SESSION['user'];
		$org = $_SESSION['user']->org;
		
		$comment = new Announcement();
		$comment->created = '';
		$comment->modified = '';
		$comment->id = $comment_id;
		$comment->parent_id = $id;
		$comment->content = $comment_content;
		if(!$comment->id){
			$comment->users_id = $user->id; // author
			$comment->organisations_id = $org->id; // author's organisation
			$comment->publication_date = Date::toMySQL("now");
		}
		
		try
		{
			DAO::transaction_start($link);
			$comment->save($link);
			$parent = Announcement::loadFromDatabase($link, $id);
			$parent->updateMostRecentCommentTimestamp($link);
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
		
		// If CLM Support posted the original announcement, alert them
		if(!$parent->organisations_id)
		{
			$this->emailAnnouncementAuthor($link, $parent, $comment);
		}
	}
	

	private function deleteComment(PDO $link)
	{
		$comment_id = isset($_REQUEST['comment_id']) ? $_REQUEST['comment_id'] : null;
		
		$comment = Announcement::loadFromDatabase($link, $comment_id);
		if(!$comment){
			return;
		}
		
		$parent = Announcement::loadFromDatabase($link, $comment->parent_id);
		if(!$parent){
			return;
		}
		
		try
		{
			DAO::transaction_start($link);
			$comment->delete($link);
			$parent->updateMostRecentCommentTimestamp($link);
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
	}
	
	

	private function emailAnnouncementAuthor(PDO $link, Announcement $announcement, Announcement $comment)
	{
		// Don't send an email if running locally or on the demo site
		if(SOURCE_LOCAL || DEMO_SITE){
			return;
		}
		
		$announcement_author = User::loadFromDatabase($link, $announcement->users_id);
		if(!$announcement_author || !$announcement_author->email){
			return;
		}
		
		$comment_author = User::loadFromDatabase($link, $comment->users_id);
		if(!$comment_author){
			return;
		}
		
		$recipients = $announcement_author->email;
		$subject = "Re: ".$announcement->title;
		$from = "CLM <donotreply@perspective-uk.com>";
		$envelope_from = "donotreply@perspective-uk.com";
		$headers = "From: ".$from."\r\n";
		//$headers .= "MIME-Version: 1.0\r\n";
		//$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n";

		$msg = <<<HEREDOC
User: {$comment_author->firstnames} {$comment_author->surname} ({$comment_author->username})
Email: {$comment_author->email}
Tel: {$comment_author->telephone}
Mbl: {$comment_author->mobile}

{$comment->content}
HEREDOC;
		
		$msg = wordwrap($msg, 70);
		
		mail($recipients, $subject, $msg, $headers);
	}
*/
}

?>
