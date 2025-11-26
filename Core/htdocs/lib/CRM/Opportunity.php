<?php
class Opportunity extends Entity
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
	crm_opportunities
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$opportunity = null;
		if($st)
		{
			$opportunity = null;
			$row = $st->fetch();
			if($row)
			{
				$opportunity = new Opportunity();
				$opportunity->populate($row);
			}

		}
		else
		{
			throw new Exception("Could not execute database query to find opportunity record. " . '----' . $query . '----' . $link->errorCode());
		}

		return $opportunity;
	}

	public function save(PDO $link)
	{
		$this->modified = date('Y-m-d H:i:s');
		$this->created = ($this->id == "") ? date('Y-m-d H:i:s') : $this->created;

		return DAO::saveObjectToTable($link, 'crm_opportunities', $this);
	}

	public function delete(PDO $link)
	{
		if($this->isSafeToDelete($link))
		{
			$opportunity_id = $this->id;
			$query = <<<HEREDOC
DELETE FROM
	crm_opportunities,
	crm_activities,
	crm_entities_comments,
	crm_entities_files,
	n1,
	n2,
	n3
USING
	crm_opportunities
	LEFT OUTER JOIN	crm_activities ON (crm_opportunities.id = crm_activities.entity_id AND crm_activities.entity_type = 'opportunity')
	LEFT OUTER JOIN crm_entities_comments ON (crm_opportunities.id = crm_entities_comments.entity_id AND crm_entities_comments.entity_type = 'opportunity')
	LEFT OUTER JOIN crm_entities_files ON (crm_opportunities.id = crm_entities_files.entity_id AND crm_entities_files.entity_type = 'opportunity')
	LEFT OUTER JOIN notes n1 ON (crm_opportunities.id = n1.parent_id AND n1.parent_table = 'crm_opportunities')
	LEFT OUTER JOIN notes n2 ON (crm_activities.id = n2.parent_id AND n2.parent_table = 'crm_activities')
	LEFT OUTER JOIN notes n3 ON (crm_entities_files.id = n3.parent_id AND n2.parent_table = 'crm_entities_files')
WHERE
	crm_opportunities.id={$this->id};
HEREDOC;
			$r = DAO::execute($link, $query);
			if($r > 0)
			{
				$repository = Repository::getRoot().'/crm/opportunity/'.$opportunity_id;
				$files = Repository::readDirectory($repository);
				foreach($files AS $f)
				{
					if($f->isDir()){
						continue;
					}
					unlink($f->getAbsolutePath());
				}
			}
		}
		else
		{
			throw new Exception('This record is not safe to delete.');
		}
	}

	public function isSafeToDelete(PDO $link)
	{
		return true;
	}

	public function renderNotes(PDO $link)
	{
		$activities_ids = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(id) FROM crm_activities WHERE `entity_id` = '{$this->id}' AND entity_type = 'opportunity';");

		$activities_where = "";

		if($activities_ids != '')
		{
			$activities_where = <<<HEREDOC
UNION ALL
SELECT
	notes.*,
	users.work_email,
	users.work_telephone
FROM
	notes LEFT OUTER JOIN users
	ON notes.username = users.username
WHERE
	notes.parent_table='crm_activities' AND notes.parent_id IN ($activities_ids)

HEREDOC;
		}

		$sql = <<<HEREDOC
SELECT * FROM
(
	SELECT
		notes.*,
		users.work_email,
		users.work_telephone
	FROM
		notes LEFT OUTER JOIN users
		ON notes.username = users.username
	WHERE
		notes.parent_table='crm_opportunities' AND notes.parent_id='$this->id'

	$activities_where

) a
ORDER BY id

;
HEREDOC;
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				echo '<div class="note">';
				echo '<div class="header">';
				echo '<table width="100%"><tr><td align="left">'.htmlspecialchars((string)$row['subject']).'</td>';
				echo '<td align="right">';
				if( $row['is_audit_note'] == '0' )
				{
					echo <<<HEREDOC
<span class="button" onclick="editLessonNote({$row['id']})">Edit</span>
<span class="button" onclick="deleteLessonNote({$row['id']})">Delete</span></td>
HEREDOC;
				}
				echo '</td></tr></table></div>';

				if($row['work_email'] != '')
				{
					echo "<div class=\"author\" title=\"{$row['firstnames']} {$row['surname']}, Tel: {$row['work_telephone']}\">{$row['firstnames']} {$row['surname']} <a href=\"mailto:{$row['work_email']}\">{$row['fqn']}</a>";
				}
				else
				{
					echo "<div class=\"author\">{$row['firstnames']} {$row['surname']} {$row['fqn']}";
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

	public static function getListOpportunityType($individual)
	{
		$options = [
			'1' => 'Apprenticeship',
			'2' => 'Work Experience',
			'3' => 'Full Cost',
			'4' => 'Project',
			'5' => 'Other'
		];
		asort($options);
		return $individual == '' ? $options : $options[$individual];
	}

	public static function getDDLOpportunityType()
	{
		$options = array(
            array('6', 'A Levels'),
            array('9', 'Access to HE'),
			array('1', 'Apprenticeship'),
			array('3', 'Full Cost'),
            array('7', 'GCSE'),
            array('5', 'Other'),
            array('4', 'Project'),
            array('8', 'T Levels'),
            array('2', 'Work Experience'),
		);
		//asort($options);
		return $options;
	}

	public static function getListOpportunityTaskStatus($individual)
	{
		$options = [
			'1' => 'Not Started',
			'2' => 'Started',
			'3' => 'In Progress',
			'4' => 'Completed'
		];
		asort($options);
		return $individual == '' ? $options : $options[$individual];
	}

	public static function getDDLOpportunityTaskStatus()
	{
		$options = array(
			array('1', 'Not Started'),
			array('2', 'Started'),
			array('3', 'In Progress'),
			array('4', 'Completed')
		);
		asort($options);
		return $options;
	}

	public static function getListOpportunityMeetingType($individual)
	{
		$options = [
			'1' => 'Meeting',
			'2' => 'Training',
			'3' => 'Other'
		];
		asort($options);
		return $individual == '' ? $options : $options[$individual];
	}

	public static function getDDLOpportunityMeetingType()
	{
		$options = array(
			array('1', 'Meeting'),
			array('2', 'Training'),
			array('3', 'Other')
		);
		asort($options);
		return $options;
	}

	public static function getListOpportunityMeetingStatus($individual)
	{
		$options = [
			'1' => 'Planned',
			'2' => 'Held',
			'3' => 'Not Held'
		];
		asort($options);
		return $individual == '' ? $options : $options[$individual];
	}

	public static function getDDLOpportunityMeetingStatus()
	{
		$options = array(
			array('1', 'Planned'),
			array('2', 'Held'),
			array('3', 'Not Held')
		);
		asort($options);
		return $options;
	}

	public static function getListOpportunityCallStatus1($individual)
	{
		$options = [
			'1' => 'Inbound',
			'2' => 'Outbound'
		];
		asort($options);
		return $individual == '' ? $options : $options[$individual];
	}

	public static function getDDLOpportunityCallStatus1()
	{
		$options = array(
			array('1', 'Inbound'),
			array('2', 'Outbound')
		);
		asort($options);
		return $options;
	}

	public static function getListOpportunityCallStatus2($individual)
	{
		$options = [
			'1' => 'Planned',
			'2' => 'Held',
			'3' => 'Not Held'
		];
		asort($options);
		return $individual == '' ? $options : $options[$individual];
	}

	public static function getDDLOpportunityCallStatus2()
	{
		$options = array(
			array('1', 'Planned'),
			array('2', 'Held'),
			array('3', 'Not Held')
		);
		asort($options);
		return $options;
	}

	public static function getDDLOpportunityTaskPriority()
	{
		$options = array(
			array('1', 'Low'),
			array('2', 'Medium'),
			array('3', 'High'),
			array('4', 'Critical')
		);
		asort($options);
		return $options;
	}

	public static function getListOpportunityTaskPriority($individual = '')
	{
		$a = array(
			'1' => 'Low',
			'2' => 'Medium',
			'3' => 'High',
			'4' => 'Critical'
		);
		asort($a);
		return $individual == '' ? $a : $a[$individual];
	}

	public static function getListOpportunityStatus($individual = '')
	{
		$a = array(
			'1' => 'Open',
			'2' => 'In Progress',
			'3' => 'Qualified',
			'4' => 'Unqualified'
		);
		asort($a);
		return $individual == '' ? $a : $a[$individual];
	}

	public static function getDDLOpportunityStatus()
	{
		$a = array(
			array('1', 'Open'),
			array('2', 'In Progress'),
			array('3', 'Qualified'),
			array('4', 'Unqualified')
		);
		asort($a);
		return $a;
	}

	public function isQualified()
	{
		return $this->status == 3 ? true : false;
	}

	public function isUnqualified()
	{
		return $this->status == 4 ? true : false;
	}

	public function getEmployerAgreements(PDO $link)
	{
		return DAO::getResultset($link, "SELECT * FROM crm_employer_agreements WHERE opportunity_id = '{$this->id}' ORDER BY created", DAO::FETCH_ASSOC);
	}

	public function isLocked()
	{
		return $this->converted ? true : (($this->isQualified() || $this->isUnqualified()) ? true : false);
	}

	public function getOwnerName(PDO $link)
	{
		return DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$this->created_by}'");
	}

	public function activityCount(PDO $link, $activity_type)
	{
		return DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_activities WHERE entity_id = '{$this->id}' AND entity_type = 'opportunity' AND activity_type = '{$activity_type}'");
	}

	public function getTableName()
	{
		return 'crm_opportunities';
	}
	public static function getListIndustries(PDO $link)
	{
		return DAO::getLookupTable($link, "SELECT id, description FROM lookup_sector_types ORDER BY description");
	}

	public function filesCount(PDO $link)
	{
		return DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_entities_files WHERE entity_id = '{$this->id}' AND entity_type = 'opportunity'");
	}

	/**
	 * Overridden method
	 * @param pdo $link
	 * @param ValueObject $new_object
	 * @param array $exclude_fields
	 */
	public function buildAuditLogString(PDO $link, Entity $new_vo, array $exclude_fields = array())
	{
		if(count($exclude_fields) == 0)
		{
			// These fields use lookup codes
			$exclude_fields = array('created_by', 'status', 'industry', 'hwc');
		}

		$changes_list = parent::buildAuditLogString($link, $new_vo, $exclude_fields);

		// Test each of the exceptions separately
		if(($new_vo->created_by != '') && ($this->created_by != $new_vo->created_by))
		{
			$lookup = DAO::getLookupTable($link, "SELECT id, CONCAT(firstnames, ' ', surname) FROM users WHERE users.type != 5 AND users.web_access = '1'");
			$from = isset($lookup[$this->created_by]) ? $lookup[$this->created_by] : $this->created_by;
			$to = isset($lookup[$new_vo->created_by]) ? $lookup[$new_vo->created_by] : $new_vo->created_by;
			$changes_list .= "[Owner] changed from '$from' to '$to'\n";
		}
		if(($new_vo->status != '') && ($this->status != $new_vo->status))
		{
			$lookup = array(
				'1' => 'New',
				'2' => 'Assigned',
				'3' => 'In Progress',
				'4' => 'Converted',
				'5' => 'Lost Sales',
				'6' => 'Purchase Order Received',
				'7' => 'Signed Proposal Received',
				'8' => 'Open',
				'9' => 'Contacted',
				'10' => 'Qualified',
				'11' => 'Unqualified'
			);
			$from = isset($lookup[$this->status]) ? $lookup[$this->status] : $this->status;
			$to = isset($lookup[$new_vo->status]) ? $lookup[$new_vo->status] : $new_vo->status;
			$changes_list .= "[Status] changed from '$from' to '$to'\n";
		}
		if(is_array($new_vo->industry) && $this->industry != $new_vo->industry)
		{
			$diff1 = array_diff(explode(',', $this->industry), $new_vo->industry);
			$diff2 = array_diff($new_vo->industry, explode(',', $this->industry));
			if(!empty($diff1) || !empty($diff2))
			{
				$lookup = $this->getListIndustries($link);
				$from = '';
				foreach(explode(',', $this->industry) AS $id)
					$from .= isset($lookup[$id]) ? $lookup[$id] . ', ' : $id . ',';
				$to = '';
				foreach($new_vo->industry AS $id)
					$to .= isset($lookup[$id]) ? $lookup[$id] . ', ' : $id . ',';
				$changes_list .= "[Industries] changed from '$from' to '$to'\n";

			}
		}
		if($this->hwc != $new_vo->hwc)
		{
			$lookup = array(
				'H' => 'Hot',
				'W' => 'Warm',
				'C' => 'Cold'
			);
			$from = isset($lookup[$this->hwc]) ? $lookup[$this->hwc] : $this->hwc;
			$to = isset($lookup[$new_vo->hwc]) ? $lookup[$new_vo->hwc] : $new_vo->hwc;
			$changes_list .= "[Hot/Warm/Cold] changed from '$from' to '$to'\n";
		}
        if(($new_vo->company_rating != '') && ($this->company_rating != $new_vo->company_rating))
        {
            $lookup = array(
                'G' => 'Gold',
                'S' => 'Silver',
                'B' => 'Bronze',
            );
            $from = isset($lookup[$this->company_rating]) ? $lookup[$this->company_rating] : $this->company_rating;
            $to = isset($lookup[$new_vo->company_rating]) ? $lookup[$new_vo->company_rating] : $new_vo->company_rating;
            $changes_list .= "[Rating] changed from '$from' to '$to'\n";
        }

        return $changes_list;
	}

	public $id= NULL;
	public $status= NULL;
	public $opportunity_title= NULL;
	public $first_name= NULL;
	public $middle_name= NULL;
	public $surname= NULL;
	public $contact_title= NULL;
	public $email= NULL;
	public $phone= NULL;
	public $mobile= NULL;
	public $company= NULL;
    public $trading_name= NULL;
    public $company_number= NULL;
    public $no_of_employees1= NULL;
    public $job_role= NULL;
	public $p_addr= NULL;
	public $p_addr_city= NULL;
	public $p_addr_region= NULL;
	public $p_addr_postcode= NULL;
	public $rating= NULL;
	public $website= NULL;
	public $industry= NULL;
	public $no_of_employees= NULL;
	public $est_closed_date= NULL;
	public $source= NULL;
	public $description= NULL;
	public $hwc= NULL;
	public $converted= NULL;
	public $o_type= NULL;
	public $est_revenue= NULL;
	public $repeat_business= NULL;
	public $lead_id= NULL;
	public $a_year= NULL;
	public $company_id= NULL;
	public $company_location_id= NULL;
	public $company_rating= NULL;
	public $choiceEmp= NULL;
	public $employer_id= NULL;
	public $created_by= NULL;
	public $created = NULL;
	public $modified = NULL;
	public $company_type = NULL;
	public $main_contact_id = NULL;
	public $estimated_learners = NULL;

	protected $audit_fields = array(
		'status'=>'OpportunityStatus',
		'first_name'=>'Contact First Name',
		'surname'=>'Contact Surname',
		'email'=>'Contact Email',
		'contact_title' => 'Contact Title',
		'phone' => 'Contact Telephone',
		'mobile' => 'Contact Mobile',
		'company' => 'company',
		'p_addr' => 'Perm. Address Line 1',
		'p_addr_postcode' => 'Perm. Postcode',
		's_addr' => 'Sec. Address Line 1',
		's_addr_postcode' => 'Sec. Postcode',
		'website' => 'Website',
		'no_of_employees' => 'Number of Employees',
		'est_closed_date' => 'Estimated Closed Date',
		'source' => 'OpportunitySource',
		'description' => 'OpportunityDetail/Desc.',
		'a_year' => 'Academic Year',
		'opportunity_title' => 'Opportunity Title'
	);
}
?>