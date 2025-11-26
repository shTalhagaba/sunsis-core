<?php
class FrameworkQualification extends Entity
{
	public static function loadFromDatabase(PDO $link, $qualification_id, $framework_id, $internaltitle)
	{
		if(is_null($qualification_id))
		{
			throw new Exception("qualification id is null");
		}
		
		$q = new FrameworkQualification();
		$sql = "SELECT * FROM framework_qualifications WHERE id = '$qualification_id' and framework_id='$framework_id' and internaltitle='".addslashes((string)$internaltitle)."';";
		$st = $link->query($sql);
		if($st)
		{
			if($row = $st->fetch())
			{
				$q->populate($row);
				if($row['evidences']=='')
					$q->evidences=null;	
				
			}
			else
			{
				return null;
			}

		}
		else
		{
			throw new DatabaseException($link, $sql);
		}

		return $q;
	}

	public static function loadFromDatabaseWithoutQualTitle(PDO $link, $qualification_id, $framework_id)
	{
		if(is_null($qualification_id))
		{
			throw new Exception("qualification id is null");
		}

		$qualification_id = str_replace('/', '', $qualification_id);

		$q = new FrameworkQualification();
		$sql = "SELECT * FROM framework_qualifications WHERE REPLACE(id, '/', '') = '{$qualification_id}' AND framework_id = '{$framework_id}' LIMIT 0, 1";
		$st = $link->query($sql);
		if($st)
		{
			if($row = $st->fetch())
			{
				$q->populate($row);
				if($row['evidences']=='')
					$q->evidences=null;

			}
			else
			{
				return null;
			}

		}
		else
		{
			throw new DatabaseException($link, $sql);
		}

		return $q;
	}

	public static function loadFromDatabaseByQualificationFrameworkCourse(PDO $link, $qualification_id, $framework_id, $course_id, $internaltitle='')
	{
		if($qualification_id == '' || $framework_id == '' || $internaltitle == '' && $course_id == '')
		{
			throw new Exception("mandatory information is null");
		}

		$qualification_id = str_replace('/', '', $qualification_id);
		$internaltitle = addslashes((string)$internaltitle);

		$objFrameworkQualification = new FrameworkQualification();
		$sql = <<<SQL
SELECT
	*
FROM
	framework_qualifications
LEFT JOIN
	course_qualifications_dates ON course_qualifications_dates.qualification_id = framework_qualifications.id
	AND
	course_qualifications_dates.framework_id = framework_qualifications.framework_id
	AND
	course_qualifications_dates.internaltitle = framework_qualifications.internaltitle
WHERE
	framework_qualifications.framework_id = '$framework_id' AND course_qualifications_dates.course_id = '$course_id'
	AND
	REPLACE(course_qualifications_dates.`qualification_id`, '/', '') IN ('$qualification_id')
	AND
	framework_qualifications.internaltitle = '$internaltitle'
SQL;

		$st = $link->query($sql);
		if($st)
		{
			if($row = $st->fetch())
			{
				$objFrameworkQualification->populate($row);
				if($row['evidences'] == '')
					$objFrameworkQualification->evidences=null;

			}
			else
			{
				return null;
			}
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}

		return $objFrameworkQualification;
	}	
	

	public static function loadFromXML($xml)
	{
		$q = new FrameworkQualification();
		
		if(strpos($xml, '<?xml version=') === false)
		{
			// Go against the grain and assume ISO-8859-1
			$xml = '<?xml version="1.0" encoding="iso-8859-1"?>' . $xml;
		}
		
		//$root = new SimpleXMLElement($xml);
		$root = XML::loadSimpleXML($xml);

		$q->title = utf8_decode($root['title']);
		$q->internaltitle = utf8_decode($root['internaltitle']);
		$q->proportion = utf8_decode($root['proportion']);
		$q->qualification_type = utf8_decode($root['type']);
		$q->level = utf8_decode($root['level']);
		$q->id = utf8_decode($root['reference']);
		$q->awarding_body = utf8_decode($root['awarding_body']);
		$q->accreditation_start_date = utf8_decode($root['accreditation_start_date']);
		$q->operational_centre_start_date = utf8_decode($root['operational_centre_start_date']);
		$q->accreditation_end_date = utf8_decode($root['accreditation_end_date']);
		$q->certification_end_date = utf8_decode($root['certification_end_date']);
		$q->dfes_approval_start_date = utf8_decode($root['dfes_approval_start_date']);
		$q->dfes_approval_end_date = utf8_decode($root['dfes_approval_end_date']);
		
		$q->description = utf8_decode($root->description);
		$q->assessment_method = utf8_decode($root->assessment_method);
		$q->structure = utf8_decode($root->structure);

		return $q;
	}
	
	

	/**
	 * This should be called in the context of a transaction
	 *
	 * @param pdo $link
	 */
	public function save(PDO $link, $fid)
	{
		// Clean up text fields
		$this->description = $this->cleanTextField($this->description);
		$this->structure = $this->cleanTextField($this->structure);
		$this->assessment_method = $this->cleanTextField($this->assessment_method);
		
		DAO::saveObjectToTable($link, 'framework_qualifications', $this);

		$this->internaltitle = $link->quote($this->internaltitle);
		DAO::execute($link, "update framework_qualifications set mandatory_units = '$this->mandatory_units' where id = '$this->id' and framework_id = '$this->framework_id' and internaltitle = $this->internaltitle");
		// Update units if data has been provided, otherwise skip
		return $this->id;
	}
	
	
	/**
	 * Deletes the qualification and its structure, but leaves the units untouched.
	 * Maybe later we could add a routine to delete unused units?
	 * Should be called in a transaction
	 */

	public function delete(PDO $link)
	{
		$qualification_id = addslashes((string)$this->id);
		$framework_id = addslashes((string)$this->framework_id);
		$internaltitle = addslashes((string)$this->internaltitle);
		
		// Delete the qualification's structure and the qualification
		$sql = <<<HEREDOC
DELETE FROM
	framework_qualifications
WHERE
	id = '$qualification_id' and framework_id = '$framework_id' and internaltitle = '$internaltitle'; 
HEREDOC;
		DAO::execute($link, $sql);
	}
	

	public function toXML($prefix = null, $namespace = null)
	{
		if(!is_null($namespace))
		{
			if($prefix == '')
			{
				$xmlns = "xmlns=\"".htmlspecialchars((string)$namespace).'"';
			}
			else
			{
				$xmlns = "xmlns:$prefix=\"".htmlspecialchars((string)$namespace).'"';
			}
		}
		else
		{
			$xmlns = '';
		}
		
		if($prefix != '')
		{
			$p = $prefix.':';
		}
		else
		{
			$p = '';
		}
		
		
		$xml = "<{$p}qualification $xmlns "
			.$p.'reference="'.htmlspecialchars((string)$this->id).'" '
			.$p.'lsc_learning_aim="'.htmlspecialchars((string)$this->lsc_learning_aim).'" '
			.$p.'awarding_body="'.htmlspecialchars((string)$this->awarding_body).'" '
			.$p.'title="'.htmlspecialchars((string)$this->title).'" '
			.$p.'internaltitle="'.htmlspecialchars((string)$this->internaltitle).'" '
			.$p.'proportion="'.htmlspecialchars((string)$this->proportion).'" '
			.$p.'level="'.htmlspecialchars((string)$this->level).'" '
			.$p.'type="'.htmlspecialchars((string)$this->qualification_type).'" '
			.$p.'accreditation_start_date="'.htmlspecialchars(Date::toMySQL($this->accreditation_start_date)).'" '
			.$p.'operational_centre_start_date="'.htmlspecialchars(Date::toMySQL($this->operational_centre_start_date)).'" '
			.$p.'accreditation_end_date="'.htmlspecialchars(Date::toMySQL($this->accreditation_end_date)).'" '
			.$p.'certification_end_date="'.htmlspecialchars(Date::toMySQL($this->certification_end_date)).'" '
			.$p.'dfes_approval_start_date="'.htmlspecialchars(Date::toMySQL($this->dfes_approval_start_date)).'" '
			.$p.'dfes_approval_end_date="'.htmlspecialchars(Date::toMySQL($this->dfes_approval_end_date)).'">'."\n";
		
		$xml .= "<{$p}description>".htmlspecialchars((string)$this->description)."</{$p}description>\n";
		$xml .= "<{$p}assessment_method>".htmlspecialchars((string)$this->assessment_method)."</{$p}assessment_method>\n";
		$xml .= "<{$p}structure>".htmlspecialchars((string)$this->structure)."</{$p}structure>\n";

		
		// Add any units if present
//		if(!is_null($this->units) && ($this->units instanceof QualificationUnits))
//		{
			// $xml .= $this->units->toXML($prefix); Khushnood

			if($this->evidences!='')
				$xml.= $this->evidences; // Khushnood
			else
				$xml.= "<root></root>"; 
			
//		}		

// Add any performance figures if present
		if(!is_null($this->grades) && (count($this->grades) > 0) )
		{
			$xml .= "<{$p}performance_figures>\n";
			foreach($this->grades as $grade)
			{
				$xml .= $grade->toXML($prefix, $namespace);
			}
			$xml .= "</{$p}performance_figures>\n";
		}
		
		$xml .= "</{$p}qualification>";
			
		
		return $xml;
	}
	
	
	public function isSafeToDelete(PDO $link)
	{
		$num_courses = "SELECT COUNT(*) FROM courses WHERE main_qualification_id='{$this->id}';";
		$num_courses = DAO::getSingleValue($link, $num_courses);
		
		return $num_courses === 0;
	}
	
	
	private function cleanTextField($fieldValue)
	{
		$fieldValue = str_replace($this->HTML_NEW_LINES, "\n", $fieldValue); // Convert <br/> etc. into \n
		$fieldValue = str_replace("\r", '', $fieldValue); // Remove all carriage returns (we'll use the UNIX newline)
		$fieldValue = preg_replace('/\n{2,}/', "\n", $fieldValue); // Remove superfluous newlines
		$fieldValue = strip_tags($fieldValue); // Remove HTML tags
		
		return $fieldValue;
	}
	
	public $recordid = NULL;
	public $id = NULL;
	public $lsc_learning_aim = NULL;
	public $awarding_body = NULL;
	public $title = NULL;
	public $internaltitle = NULL;
	public $level = NULL;
	public $qualification_type = NULL;
	public $start_date = NULL;
	public $end_date = NULL;

	public $description = NULL;
	public $assessment_method = NULL;
	public $structure = NULL;		
	
	public $accreditation_start_date = NULL;			// The first date that awarding bodies can register candidates for a qualification. 
	public $operational_centre_start_date = NULL;	// The date when the qualification will become operational in centres. 
	public $accreditation_end_date = NULL;				// The final date that a candidate wanting to undertake a qualification can register. 
	public $certification_end_date = NULL;				// The final date by which registered candidates must complete the qualification.
	public $dfes_approval_start_date = NULL;
	public $dfes_approval_end_date = NULL;
		
	public $grades = NULL;
	public $units = NULL;
	public $elements = NULL;
	public $framework_id = NULL;
	public $evidences = NULL;
	public $proportion = NULL;
	public $duration_in_months = NULL;
	
	private $HTML_NEW_LINES = array('<br>', '<br/>', '<br />', '<BR>', '<BR/>', '<BR />', '</p>', '</P>');
}


?>