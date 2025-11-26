<?php
class Enquiry extends Entity
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
	crm_enquiries
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$enquiry = null;
		if($st)
		{
			$enquiry = null;
			$row = $st->fetch();
			if($row)
			{
				$enquiry = new Enquiry();
				$enquiry->populate($row);
			}
		}
		else
		{
			throw new Exception("Could not execute database query to find enquiry record. " . '----' . $query . '----' . $link->errorCode());
		}

		return $enquiry;
	}

	public function save(PDO $link)
	{
		$this->modified = date('Y-m-d H:i:s');
		$this->created = ($this->id == "") ? date('Y-m-d H:i:s') : $this->created;
		$this->created_by = $this->id == "" ? $_SESSION['user']->id : $this->created_by;

		$result = DAO::saveObjectToTable($link, 'crm_enquiries', $this);

		return $result;
	}

	public function delete(PDO $link)
	{
		if($this->isSafeToDelete($link))
		{
			$enquiry_id = $this->id;
			$query = <<<HEREDOC
DELETE FROM
	crm_enquiries,
	crm_activities,
	crm_entities_comments,
	crm_entities_files,
	n1,
	n2,
	n3
USING
	crm_enquiries
	LEFT OUTER JOIN	crm_activities ON (crm_enquiries.id = crm_activities.entity_id AND crm_activities.entity_type = 'enquiry')
	LEFT OUTER JOIN crm_entities_comments ON (crm_enquiries.id = crm_entities_comments.entity_id AND crm_entities_comments.entity_type = 'enquiry')
	LEFT OUTER JOIN crm_entities_files ON (crm_enquiries.id = crm_entities_files.entity_id AND crm_entities_files.entity_type = 'Enquiry')
	LEFT OUTER JOIN notes n1 ON (crm_enquiries.id = n1.parent_id AND n1.parent_table = 'crm_enquiries')
	LEFT OUTER JOIN notes n2 ON (crm_activities.id = n2.parent_id AND n2.parent_table = 'crm_activities')
	LEFT OUTER JOIN notes n3 ON (crm_entities_files.id = n3.parent_id AND n2.parent_table = 'crm_entities_files')
WHERE
	crm_enquiries.id={$this->id};
HEREDOC;
			$r = DAO::execute($link, $query);
			if($r > 0)
			{
				$repository = Repository::getRoot().'/crm/Enquiry/'.$enquiry_id;
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

	public static function getListEnquiryStatus($individual = '')
	{
		$a = array(
			'1' => 'New',
			'2' => 'In Progress',
			'3' => 'Successful',
			'4' => 'Unsuccessful'
		);
		asort($a);
		return $individual == '' ? $a : $a[$individual];
	}

	public function isNew()
	{
		return $this->status == 1 ? true : false;
	}

	public function isInProgress()
	{
		return $this->status == 2 ? true : false;
	}

	public function isSuccessful()
	{
		return $this->status == 3 ? true : false;
	}

	public function isUnsuccessful()
	{
		return $this->status == 4 ? true : false;
	}

	public static function getDDLEnquiryStatus()
	{
		$a = array(
			array('1', 'New'),
			array('2', 'In Progress'),
			array('3', 'Successful'),
			array('4', 'Unsuccessful')
		);
		asort($a);
		return $a;
	}

	public static function getListEnquiryType($individual = '')
	{
		$a = array(
			// '1' => 'Phone',
			// '2' => 'Email',
			// '3' => 'Social Media',
			// '4' => 'Face-to-face',
			'11' => 'AEB',
			'12' => 'Apprenticeship',
			'13' => 'Commercial',
		);
		asort($a);
		return $individual == '' ? $a : (isset($a[$individual]) ? $a[$individual] : '');
	}

	public static function getDDLEnquiryType()
	{
		$a = array(
			array('11', 'AEB'),
			array('12', 'Apprenticeship'),
			array('13', 'Commercial')
		);
		asort($a);
		return $a;
	}

	public static function getListIndustries(PDO $link)
	{
		return DAO::getLookupTable($link, "SELECT id, description FROM lookup_sector_types ORDER BY description");
	}

	public static function getDDLIndustries(PDO $link)
	{
		return DAO::getResultset($link, "SELECT id, description, null FROM lookup_crm_products ORDER BY description");
	}

	public function getIndustryDescription(PDO $link)
	{
		return !is_null($this->industry) ? DAO::getSingleValue($link, "SELECT GROUP_CONCAT(description SEPARATOR ' | ') FROM lookup_sector_types WHERE id IN ({$this->industry})") : '';
	}

	public static function getCreatedByName(PDO $link, $user_id)
	{
		return DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$user_id}'");
	}

	public function isReadyToConvert()
	{
		return $this->status == 3 ? true : false;
	}

	public function isEditable()
	{
		return $this->isNew() ? true : ($this->isInProgress() ? true : false);
	}

	public function isLocked()
	{
		return $this->converted == 1 ? true : false;
	}

	public function getOwnerName(PDO $link)
	{
		return DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$this->created_by}'");
	}

	public function renderNotes(PDO $link)
	{
		$activities_ids = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(id) FROM crm_activities WHERE `entity_id` = '{$this->id}' AND entity_type = 'enquiry';");

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
		notes.parent_table='crm_enquiries' AND notes.parent_id='$this->id'

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


	public function getTableName()
	{
		return 'crm_enquiries';
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
			$exclude_fields = array('status', 'industry', 'created_by');
		}

		$changes_list = parent::buildAuditLogString($link, $new_vo, $exclude_fields);

		if(($new_vo->status != '') && ($this->status != $new_vo->status))
		{
			$lookup = array(
				'1' => 'New',
				'2' => 'In Progress',
				'3' => 'Successful',
				'4' => 'Unsuccessful'
			);
			$from = isset($lookup[$this->status]) ? $lookup[$this->status] : $this->status;
			$to = isset($lookup[$new_vo->status]) ? $lookup[$new_vo->status] : $new_vo->status;
			$changes_list .= "[Status] changed from '$from' to '$to'\n";
		}
		if(($new_vo->created_by != '') && ($this->created_by != $new_vo->created_by))
		{
			$lookup = DAO::getLookupTable($link, "SELECT id, CONCAT(firstnames, ' ', surname) FROM users WHERE users.type != 5 AND users.web_access = '1'");
			$from = isset($lookup[$this->created_by]) ? $lookup[$this->created_by] : $this->created_by;
			$to = isset($lookup[$new_vo->created_by]) ? $lookup[$new_vo->created_by] : $new_vo->created_by;
			$changes_list .= "[Owner] changed from '$from' to '$to'\n";
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

		return $changes_list;
	}

	public function isConverted()
	{
		return $this->converted == 1 ? true : false;
	}

	public function getLinkedLeadID(PDO $link)
	{
		return DAO::getSingleValue($link, "SELECT crm_leads.id FROM crm_leads WHERE crm_leads.enquiry_id = '{$this->id}'");
	}

	public function activityCount(PDO $link, $activity_type)
	{
		return DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_activities WHERE entity_id = '{$this->id}' AND entity_type = 'enquiry' AND activity_type = '{$activity_type}'");
	}

	public function filesCount(PDO $link)
	{
		return DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_entities_files WHERE entity_id = '{$this->id}' AND entity_type = 'enquiry'");
	}

	public $id = NULL;
	public $status = NULL;
	public $enquiry_title = NULL;
	public $enquiry_type = NULL;
	public $source = NULL;
	public $main_contact_id = NULL;
	public $description = NULL;
	public $converted = NULL;
	public $company_id = NULL;
	public $company_location_id = NULL;
	public $created_by = NULL;
	public $created = NULL;
	public $modified = NULL;
	public $company_type = NULL;
	public $industry = NULL;

	protected $audit_fields = array(

		'status'=>'Status',
		'enquiry_title' => 'Enquiry Title',
		'enquiry_type' => 'Enquiry Type',
		'source' => 'Enquiry Source',
		'main_contact_id' => 'Main Contact ID',
		'description' => 'Enquiry Detail/Desc.',
	);


}
?>