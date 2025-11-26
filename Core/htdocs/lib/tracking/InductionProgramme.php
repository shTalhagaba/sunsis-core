<?php

class InductionProgramme extends Entity
{
	public static function loadFromDatabase(PDO $link, $id = '')
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
	induction_programme
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$inductionProg = null;
		if($st)
		{
			$inductionProg = null;
			$row = $st->fetch();
			if($row)
			{
				$inductionProg = new InductionProgramme();
				$inductionProg->populate($row);
			}

		}
		else
		{
			throw new Exception("Could not execute database query to find induction programme record. " . '----' . $query . '----' . $link->errorCode());
		}

		return $inductionProg;
	}

	public function save(PDO $link)
	{
		$this->modified = "";
		$this->created = ($this->id == "") ? date('Y-m-d H:i:s') : $this->created;
		$this->created_by = ($this->id == "") ? $_SESSION['user']->id : $this->created_by;

		return DAO::saveObjectToTable($link, 'induction_programme', $this);
	}

	public function delete(PDO $link)
	{

	}


	public function isSafeToDelete(PDO $link)
	{
		return false;
	}

	public function renderComments(PDO $link)
	{
		$html = '<table class="table">';
		$html .= '<tr><th>Creation DateTime</th><th>Created By</th><th>Detail</th></tr>';
		$notes = DAO::getSingleValue($link, "SELECT induction_programme.programme_notes FROM induction_programme WHERE induction_programme.id = '{$this->id}'");
		if($notes == '')
		{
			$html .= '<tr><td colspan="3"><i>No record found.</i></td></tr>';
		}
		else
		{
			$notes = XML::loadSimpleXML($notes);
			foreach($notes->Note AS $note)
			{
				$html .= '<tr>';
				$html .= '<td>' . Date::to($note->DateTime, Date::DATETIME) . '</td>';
				$html .= '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy}'") . '</td>';
				$html .= '<td>' . htmlspecialchars((string)$note->Note) . '</td>';
				$html .= '</tr>';
			}
		}
		$html .= '</table>';
		return '<small>' . $html . '</small>';
	}


	public $id = NULL;
	public $inductee_id = NULL;
	public $programme_id = NULL;
    	public $created_by = NULL;
    	public $eligibility_test_status = NULL;
    	public $eligibility_test_type = NULL;
    	public $programme_notes = NULL;
	public $skilsure_username = NULL;
	public $skilsure_password = NULL;
	public $mentor_username = NULL;
	public $mentor_password = NULL;
	public $related_quals = NULL;
	public $skills_scan = NULL;
	public $skills_scan_grade = NULL;
	public $funding_reduction = NULL;
	public $reduction_price = NULL;
	public $coordinator_notes_program = NULL;
	public $funding_reduction_other = NULL;
	public $call_arranged_for = NULL;
	public $ip_status = NULL;
	public $prior_quals_further_details = NULL;
	public $prior_experience_further_details = NULL;
	public $employer_agreed_reduction_further_details = NULL;
	public $prev_app_further_details = NULL;
	public $reduction_keyed_by_admin = 0;
	public $admin_error_details = NULL;
	public $funding_reduction_further_details = NULL;
	public $data_pathway = NULL;
	public $it_pathway = NULL;

	public $created = NULL;
	public $modified = NULL;

}
