<?php
class LessonNoteVO extends ValueObject
{
	public function __construct($setDefaults = false)
	{
		parent::__construct();
		
		if($setDefaults)
		{
			$this->username = $_SESSION['username'];
			$this->firstnames = $_SESSION['firstnames'];
			$this->surname = $_SESSION['surname'];
			$this->organisation_name = $_SESSION['org']->short_name;
			$this->created = date('Y-m-d G:i:s');
		}
	}
	
	public $id = null;
	public $lessons_id = null;
	public $subject = null;
	public $note = null;
	public $username = null;
	public $firstnames = null;
	public $surname = null;
	public $organisation_name = null;
	public $is_audit_note = null;
	public $created = null;
}
?>