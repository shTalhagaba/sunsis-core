<?php
class CandidateNotes extends Entity
{
	public static function loadFromDatabase(PDO $link, $candidate_id)
	{
		if($candidate_id == '') {
			return null;
		}
		
		$key = addslashes((string)$candidate_id);

		$query = <<<HEREDOC
SELECT
	*
FROM
	candidate_notes
WHERE
	candidate_id='$key'
ORDER BY created desc;
HEREDOC;
			
		$st = $link->query($query);

		if( $st )	{
			$candidate_notes = new CandidateNotes();
			$candidate_notes->candidate_id = $key;
			
			while( $row = $st->fetch() ) {
				$candidate_notes->comments[] = array(
					'id' => $row['id'],
					'note' => $row['note'],
					'username' => $row['username'],
					'created' => $row['created'],
					'status' => $row['status']
				);
			}
		}
		return $candidate_notes;	
	}
	
	public function save(PDO $link)
	{
		$cand_note_sql = <<<HEREDOC
insert into candidate_notes (
		candidate_id, 
		note, 
		username, 
		created, 
		status
	) 
VALUES (
		{$this->candidate_id},
		'{$this->note}',
		'{$this->username}',
		now(),
		{$this->status}
	);	
HEREDOC;

		$st = $link->query($cand_note_sql);
		if( $st == false ) {
			return 'There has been a problem saving this note!';
		}
		else {
			return 'Successfully added this note:<br/><strong>'.$this->note.'</strong>';
		}	
	}
	
	public function render() {
			$candidate_notes = '<tr><td colspan="4" style="font-weight:bold; background-color: #eee;">Candidate History</td></tr>';
			foreach( $this->comments as $note_id => $note_content ) {
				$candidate_notes .= '<tr';
				if ( $note_content['status'] == 1 ) {
					$candidate_notes .= ' style="font-weight:bold;" ';
				}
				$candidate_notes .= '>';
				$candidate_notes .= '<td colspan="1">'.$note_content['created'].' [';
				$candidate_notes .= $note_content['username'].']</td>';
				$candidate_notes .= '<td colspan="3">'.$note_content['note'].'</td>';
				$candidate_notes .= '</tr>';
			}
			$candidate_notes .= '<tr id="cand_comment_'.$this->candidate_id.'" >';
			$candidate_notes .= '<td colspan="4"><span style="text-align: left;" >New Comment: </span><br/><textarea id="cand_note_'.$this->candidate_id.'" style="width: 98%" ></textarea>';
			$candidate_notes .= '<br/><span style="text-align: right; float: right"><a href="#" onclick="save_cand_note('.$this->candidate_id.');return false;" >save comment</a></span></td>';
			$candidate_notes .= '</tr>';
			
			return $candidate_notes;
	}
	
	public static function render_js() {
		
		$candidate_note_js = "function save_cand_note(candid) {";
		$candidate_note_js .= "var candidate_comment = 'cand_note_'+candid;";
		$candidate_note_js .= "var candidate_row = 'cand_comment_'+candid;";
		$candidate_note_js .= "comment_text = document.getElementById(candidate_comment).value;";
		$candidate_note_js .= "if ( comment_text.length > 0 ) {";
		// RE: added in the escape to resolve text truncating issue #22761
		$candidate_note_js .= " var params = 'candid='+candid+'&comment='+escape(comment_text);";
		$candidate_note_js .= "	var request = ajaxRequest('do.php?_action=ajax_save_candidate_comment', params);";
		$candidate_note_js .= "	if ( request.responseText.match('/^Successfully/') ) {";
		$candidate_note_js .= "	alert('There has been a problem saving this note!');";
		$candidate_note_js .= "}";
		$candidate_note_js .= "	else {";
		$candidate_note_js .= "		document.getElementById(candidate_row).cells[0].innerHTML = request.responseText;";
		$candidate_note_js .= "	}";
		$candidate_note_js .= "}";
		$candidate_note_js .= "}";
		
		$candidate_note_js .= "\nfunction save_cand_status(candid, vacid) {";
		$candidate_note_js .= "var application_comment = 'app_note_'+candid;";
		$candidate_note_js .= "var application_date = document.getElementById('nad_year_'+candid).value+'-'+document.getElementById('nad_month_'+candid).value+'-'+document.getElementById('nad_day_'+candid).value;";
		$candidate_note_js .= "var application_row = 'app_comment_'+candid;";
		$candidate_note_js .= "comment_text = document.getElementById(application_comment).value;";
		//$candidate_note_js .= "if ( comment_text.length > 0 ) {";
		$candidate_note_js .= "var request = ajaxRequest('do.php?_action=ajax_save_status_comment','candid='+candid+'&comment='+comment_text+'&vacid='+vacid+'&date='+application_date);";
		$candidate_note_js .= "if ( request.responseText.match('/^Successfully/') ) {";
		$candidate_note_js .= "alert('There has been a problem saving this status!');";
		$candidate_note_js .= "}";
		$candidate_note_js .= "else{";
		$candidate_note_js .= "document.getElementById(application_row).cells[0].innerHTML = request.responseText;";
		$candidate_note_js .= "}";
		//$candidate_note_js .= "}";
		$candidate_note_js .= "}";
		
		
		return $candidate_note_js;
	}

	public static function renderNotes(PDO $link, $parent_table, $parent_id)
	{
		$key_pid = addslashes((string)$parent_id);
		$user_identities = DAO::pdo_implode($_SESSION['user']->getIdentities());

		if($_SESSION['user']->isAdmin())
		{
			$sql = <<<HEREDOC
SELECT
	candidate_notes.*,
	users.firstnames,
	users.surname,
	users.work_email,
	users.work_telephone
FROM
	candidate_notes LEFT OUTER JOIN users
	ON candidate_notes.username = users.username
WHERE
	candidate_notes.candidate_id='$key_pid';
HEREDOC;
		}
		else
		{
			$sql = <<<HEREDOC
SELECT
	candidate_notes.*,
	GROUP_CONCAT(acl.privilege) AS privileges,
	users.firstnames,
	users.surname,
	users.work_email,
	users.work_telephone
FROM
	candidate_notes LEFT OUTER JOIN acl
	ON (acl.resource_category='note' AND acl.resource_id = candidate_notes.id)
	LEFT OUTER JOIN users ON candidate_notes.username = users.username
WHERE
	candidate_notes.candidate_id='$key_pid';'
	AND acl.ident IN ($user_identities)
	AND acl.privilege IN ('read', 'write')
GROUP BY
	candidate_notes.id;
HEREDOC;
		}

		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				echo '<div class="note">';
				echo '<div class="header">';
				echo '<td align="right">';

				echo '</td></tr></table></div>';

				if($row['work_email'] != '')
				{
					echo "<div class=\"author\" title=\"{$row['firstnames']} {$row['surname']}, Tel: {$row['work_telephone']}\"><a href=\"mailto:{$row['work_email']}\">{$row['username']}</a>";
				}
				else
				{
					echo "<div class=\"author\">{$row['username']}";
				}
				echo ' (' . date('d/m/Y H:i:s T', strtotime($row['created'])) . ')</div>';
				echo HTML::nl2p(htmlspecialchars((string)$row['note']));
				echo '</div>';
			}
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}

	}


	public $candidate_id = null;
	
	public $note = null;
	public $username = null;
	public $status = null;
	
	public $comments = array();
}
?>