<?php
class Widget extends Entity
{
	public static function loadFromDatabase(PDO $link, $id)
	{
		$query = "SELECT * FROM widgets WHERE id=" . addslashes((string)$id) . ";";
		$st = $link->query($query);

		$widget = null;
		if($st)
		{
			$row = $st->fetch();
			if($row)
			{
				$widget = new Widget();
				$widget->populate($row);
			}
			
		}
		else
		{
			throw new DatabaseException($link, $query);
		}

		return $widget;
	}
	
	
	public function save(PDO $link)
	{
		if($this->id == '')
		{
			// New record
			$this->created = ''; // DAO will save this as NULL, which results in a fresh timestamp
			$this->seq = 1; // DAO will not save this at all, triggering the default table value
					
			// Create an audit note
			$note = new Note();
			$note->subject = "Document created";
		}
		else
		{
			// Check for changes
			$existing_record = Widget::loadFromDatabase($link, $this->id);
			$log_string = $existing_record->buildAuditLogString($link, $this);
			
			if($log_string != '')
			{
				// Create an audit note
				$note = new Note();
				$note->subject = "Document changed";
				$note->note = $log_string;
			}
		}
		
		// Check sequence (not strict -- validate only if 'seq' is specified)
		if( !is_null($this->seq) && is_numeric($this->seq) && ($this->id != '') )
		{
			$db_seq = DAO::getSingleValue($link, "SELECT seq FROM widgets WHERE id=".$this->id);
			if((int)$this->seq !== $db_seq)
			{
				throw new Exception("Save cancelled. Someone else has opened, edited and saved this record while you were editing it. You must cancel your edit and begin again.");
			}
			
			// Increment sequence
			$this->seq = $this->seq + 1;
		}		

		
		DAO::saveObjectToTable($link, 'widgets', $this);	
		
		// Save audit note (after saving main record because we need the ID)
		if(isset($note) && !is_null($note))
		{
			$note->is_audit_note = true;
			$note->parent_table = 'widgets';
			$note->parent_id = $this->id;
			$note->save($link);
		}
		
		return $this->id;
	}
	
	
	public function delete(PDO $link)
	{
		if(!$this->isSafeToDelete($link))
		{
			throw new Exception("Widget #{$this->id} cannot be deleted");
		}
		
		$query = <<<HEREDOC
DELETE FROM
	widgets, acl, notes
USING
	widgets LEFT OUTER JOIN acl
	ON (acl.resource_category='widget' AND acl.resource_id=widgets.id)
	LEFT OUTER JOIN notes
	ON (notes.parent_table='widgets' AND notes.parent_id={$this->id})
HEREDOC;
		DAO::execute($link, $query);

		return true;		
	}
	
	
	public function isSafeToDelete(PDO $link)
	{
		if($_SESSION['user']->isAdmin())
		{
			return true;
		}
		else
		{
			$acl = ACL::loadFromDatabase($link, 'widget', $this->id); /* @var $acl ACL */

			return $acl->isAuthorised($_SESSION['user'], 'write');
		}
	}
	
	
	public $id = NULL;
	public $title = NULL;
	public $seq = NULL;
	public $modified = NULL;
	public $created = NULL;
	
	protected $audit_fields = array('title'=>'Widget Title');
}
?>