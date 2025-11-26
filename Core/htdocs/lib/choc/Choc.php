<?php
class Choc extends Entity
{
	public static function loadFromDatabase(PDO $link, $id)
	{
		if($id == '')
		{
			return null;
		}
		
		$key = addslashes((string)$id);
		$query = <<<HEREDOC
SELECT
	*
FROM
	chocs
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$choc = null;
		if($st)
		{
			$choc = null;
			$row = $st->fetch();
			if($row)
			{
				$choc = new Choc();
				$choc->populate($row);
			}
			
		}
		else
		{
			throw new Exception("Could not execute database query to find choc entry. " . '----' . $query . '----' . $link->errorCode());
		}

		return $choc;	
	}
	
	public function save(PDO $link)
	{
        $this->created_at = $this->id == '' ? date('Y-m-d H:i:s') : $this->created_at;
        $this->created_by = $this->id == '' ? $_SESSION['user']->id : $this->created_by;
        //$this->choc_status = $this->id == '' ? "NEW" : $this->choc_status;
        $this->updated_at = date('Y-m-d H:i:s');
		if($this->id == '')
		{
			$tr = TrainingRecord::loadFromDatabase($link, $this->tr_id);
			$this->old_employer = $tr->employer_id;
			$this->old_employer_location = $tr->employer_location_id;
			$this->old_employer_manager = $tr->crm_contact_id;
		}
        return DAO::saveObjectToTable($link, 'chocs', $this);
	}
	
	public function delete(PDO $link)
	{
		// Placeholder
	}

	public static function getChocList()
	{
		return[
			'NEW' => 'NEW',
			'CREATED BY LEARNER' => 'CREATED BY LEARNER',
			'IN PROGRESS' => 'IN PROGRESS',
			'ACCEPTED' => 'ACCEPTED',
			'REFERRED' => 'REFERRED',
			'REFERRED TO LEARNER' => 'REFERRED TO LEARNER',
			'COMPLETED' => 'COMPLETED',
		];
	}

	public static function getChocDdl()
	{
		return[
			['NEW', 'NEW'],
			['CREATED BY LEARNER', 'CREATED BY LEARNER'],
			['IN PROGRESS', 'IN PROGRESS'],
			['ACCEPTED', 'ACCEPTED'],
			['REFERRED', 'REFERRED'],
			['REFERRED TO LEARNER', 'REFERRED TO LEARNER'],
			['COMPLETED', 'COMPLETED'],
		];
	}

	public function isSafeToDelete(PDO $link)
	{
		return false;
	}

	public static function saveComments(PDO $link, Choc $choc, $comments)
	{
		$comments = str_replace("£", "&pound;", $comments);
		$comments = Text::utf8_to_latin1($comments);
		$comments = htmlspecialchars((string)$comments, 16);
		$xml = DAO::getSingleValue($link, "SELECT comments FROM chocs WHERE id = '{$choc->id}'");
		if(is_null($xml) || $xml == '')
			$xml = '<Comment></Comment>';
		$xml = XML::loadSimpleXML($xml);
		$new_note = $xml->addChild('Note');
		$new_note->Note = $comments;
		$new_note->CreatedBy = $_SESSION['user']->id;
		$new_note->DateTime = date('Y-m-d H:i:s');

		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		@$dom->loadXML($xml->saveXML());
		$dom->formatOutput = TRUE;
		$modified_xml = $dom->saveXml();
		$modified_xml = str_replace('<?xml version="1.0"?>', '', $modified_xml);

		return $modified_xml;
	}

		
	public $id = null;
	public $choc_type = NULL;
	public $created_by = NULL;
	public $choc_status = NULL;
	public $choc_details = NULL;
	public $comments = NULL;
	public $tr_id = NULL;
	public $assigned_to = NULL;
	public $created_at = NULL;
	public $updated_at = NULL;
	public $old_employer = NULL;
	public $old_employer_location = NULL;
	public $old_employer_manager = NULL;
	
}
?>