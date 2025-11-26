<?php
class OnboardingLogger extends Entity
{
	public static function loadFromDatabase(PDO $link, $id)
	{
		if($id == '' || !is_numeric($id))
		{
			throw new Exception("Argument id must be numeric");
		}

		$note = null;
		$sql = "SELECT * FROM onboarding_log WHERE id=$id";
		$st = $link->query($sql);
		if($st)
		{
			if($row = $st->fetch())
			{
				$log = new OnboardingLogger();
				$log->populate($row);
			}
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}

		return $log;
	}


	public function save(PDO $link)
	{
		// Clean note field
		$this->note = preg_replace('/[\n\r]+/', "\n", $this->note); // Remove superfluous newlines
		$this->note = trim(strip_tags($this->note)); // Remove HTML tags
		$this->note = substr($this->note, 0, 499);

		DAO::saveObjectToTable($link, 'onboarding_log', $this);

		return $this->id;
	}

	public $id = NULL;
	public $subject = NULL;
	public $note = NULL;
	public $tr_id = NULL;
	public $by_whom = NULL;
	public $created = NULL;
}


?>
