<?php
class CandidateExtraInfo extends Entity
{
	function __construct($candidate_id)
	{
		$this->candidate_id = $candidate_id;
	}
	public static function loadFromDatabase(PDO $link, $candidate_id)
	{
		if($candidate_id == '')
		{
			return null;
		}

		$key = addslashes((string)$candidate_id);
		$query = <<<HEREDOC
SELECT
	*
FROM
	candidate_extra_info
WHERE
	candidate_id='$key';
HEREDOC;

		$st = $link->query($query);

		$candidate_extra_info = null;
		if($st)
		{
			$candidate_extra_info = null;
			$row = $st->fetch();
			if($row)
			{
				$candidate_extra_info = new CandidateExtraInfo($candidate_id);
				$candidate_extra_info->populate($row);
			}

		}
		else
		{
			throw new Exception("Could not execute database query to find candidate extra info. " . '----' . $query . '----' . $link->errorCode());
		}

		return $candidate_extra_info;
	}

	public function save(PDO $link)
	{
		return DAO::saveObjectToTable($link, 'candidate_extra_info', $this);
	}

	public function delete(PDO $link)
	{
		// Placeholder
	}


	public function isSafeToDelete(PDO $link)
	{
		return false;
	}


	public $id = null;
	public $candidate_id = NULL;
	public $ok_to_leave_school = NULL;
	public $currently_in_further_edu = NULL;
	public $able_to_take_app = NULL;
	public $been_a_uk_citizen = NULL;
	public $have_criminal_record = NULL;
	public $criminal_record_details= NULL;
	public $know_org_policy = NULL;
	public $know_about_disqualification = NULL;

	protected $audit_fields = array(
		'ok_to_leave_school'=>'OK tp leave school legally',
		'currently_in_further_edu'=>'in further edu or full time emp',
		'able_to_take_app'=>'able to undertake 12 month app',
		'been_a_uk_citizen'=>'been uk citizen',
		'have_criminal_record'=>'Start have criminal records',
		'criminal_record_details'=>'criminal records details',
		'know_org_policy'=>'aware of org policy',
		'know_about_disqualification'=>'aware of disqualification'
	);

}
?>