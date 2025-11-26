<?php
class StudentQualification extends Entity
{
	public static function loadFromDatabase(PDO $link, $qualification_id, $fid, $tr_id, $internaltitle)
	{
		if(is_null($qualification_id) || is_null($fid) || is_null($tr_id))
		{
			return null;
		}
		
		$q = new StudentQualification();
		$inter_title = addslashes($internaltitle);
		$sql = "SELECT * FROM student_qualifications WHERE id='$qualification_id' and framework_id='$fid' and tr_id='$tr_id' and internaltitle='$inter_title';";
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
		
		// Load performance figures
		// $q->grades = StudentQualificationGrade::loadFromDatabase($link, $qualification_id, $fid, $tr_id);
		
		return $q;
	}

    public static function loadFromDatabaseById(PDO $link, $id)
    {
        if(is_null($id))
        {
            return null;
        }

        $q = new StudentQualification();
        $sql = "SELECT * FROM student_qualifications WHERE auto_id='$id'";
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

        // Load performance figures
        // $q->grades = StudentQualificationGrade::loadFromDatabase($link, $qualification_id, $fid, $tr_id);

        return $q;
    }


	public static function loadFromXML($xml)
	{
		$q = new StudentQualification();
		
		if(strpos($xml, '<?xml version=') === false)
		{
			// Go against the grain and assume ISO-8859-1
			$xml = '<?xml version="1.0" encoding="iso-8859-1"?>' . $xml;
		}
		
		//$root = new SimpleXMLElement($xml);
		$root = XML::loadSimpleXML($xml);

		$q->title = utf8_decode($root['title']);
		$q->internaltitle = utf8_decode($root['internaltitle']);
		$q->attitude = utf8_decode($root['attitude']);
		$q->aptitude = utf8_decode($root['aptitude']);
		$q->comments = utf8_decode($root['comments']);
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
		
		
		// Performance figures
/*		$counter = 1;
		foreach($root->performance_figures as $figures)
		{
			foreach($figures->children() as $tag) /* @var $tag SimpleXMLElement 
			{
				// Process each attainment figure
				if($tag->getName() == "attainment")
				{
					$g = new StudentQualificationGrade();
					$g->ordinal = $counter++;
					$g->qualification_id = $q->id;
					$g->grade = (string) $tag['grade'];
					$g->level_1_threshold = (integer) $tag['level_1_threshold'];
					$g->level_1_and_2_threshold = (integer) $tag['level_1_and_2_threshold'];
					$g->level_3_threshold = (integer) $tag['level_3_threshold'];
					$g->points = (float) $tag['points'];
					
					$q->grades[] = $g;
				}
			}
			
			break; // There should only be one <performance_figures> element present
		}
		
	*/
		return $q;
	}
	
	
	/**
	 * This should be called in the context of a transaction
	 *
	 * @param pdo $link
	 */
	public function save(PDO $link)
	{
		// Clean up text fields
		$this->description = $this->cleanTextField($this->description);
		$this->structure = $this->cleanTextField($this->structure);
		$this->assessment_method = $this->cleanTextField($this->assessment_method);
		$this->username = $_SESSION['user']->username;
		$this->trading_name = $_SESSION['user']->org->trading_name;
		
		DAO::saveObjectToTable($link, 'student_qualifications', $this);
		
		return $this->id;
}
	

	/**
	 * Deletes the qualification and its structure, but leaves the units untouched.
	 * Maybe later we could add a routine to delete unused units?
	 * Should be called in a transaction
	 */

	public function delete(PDO $link)
	{
		$qid = addslashes($this->id);
		$fid = addslashes($this->framework_id);
		$tr_id = addslashes($this->tr_id);
		$internaltitle = addslashes($this->internaltitle);
		
		
		// Delete the qualification's structure and the qualification
		$sql = <<<HEREDOC
DELETE FROM
	student_qualifications
WHERE
	id = '$qid' and framework_id='$fid' and tr_id='$tr_id' and internaltitle='$internaltitle'; 
HEREDOC;
		DAO::execute($link, $sql);
	}
	

	public function toXML($prefix = null, $namespace = null)
	{
		if(!is_null($namespace))
		{
			if($prefix == '')
			{
				$xmlns = "xmlns=\"".htmlspecialchars($namespace).'"';
			}
			else
			{
				$xmlns = "xmlns:$prefix=\"".htmlspecialchars($namespace).'"';
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
			.$p.'reference="'.htmlspecialchars($this->id).'" '
			.$p.'lsc_learning_aim="'.htmlspecialchars($this->lsc_learning_aim).'" '
			.$p.'awarding_body="'.htmlspecialchars($this->awarding_body).'" '
			.$p.'title="'.htmlspecialchars($this->title).'" '
			.$p.'internaltitle="'.htmlspecialchars($this->internaltitle).'" '
			.$p.'aptitude="'.htmlspecialchars($this->aptitude).'" '
			.$p.'attitude="'.htmlspecialchars($this->attitude).'" '
			.$p.'comments="'.htmlspecialchars($this->comments).'" '
			.$p.'level="'.htmlspecialchars($this->level).'" '
			.$p.'qualification_type="'.htmlspecialchars($this->qualification_type).'" '
			.$p.'accreditation_start_date="'.htmlspecialchars(Date::toMySQL($this->accreditation_start_date)).'" '
			.$p.'operational_centre_start_date="'.htmlspecialchars(Date::toMySQL($this->operational_centre_start_date)).'" '
			.$p.'accreditation_end_date="'.htmlspecialchars(Date::toMySQL($this->accreditation_end_date)).'" '
			.$p.'certification_end_date="'.htmlspecialchars(Date::toMySQL($this->certification_end_date)).'" '
			.$p.'dfes_approval_start_date="'.htmlspecialchars(Date::toMySQL($this->dfes_approval_start_date)).'" '
			.$p.'dfes_approval_end_date="'.htmlspecialchars(Date::toMySQL($this->dfes_approval_end_date)).'">'."\n";
		
		$xml .= "<{$p}description>".htmlspecialchars($this->description)."</{$p}description>\n";
		$xml .= "<{$p}assessment_method>".htmlspecialchars($this->assessment_method)."</{$p}assessment_method>\n";
		$xml .= "<{$p}structure>".htmlspecialchars($this->structure)."</{$p}structure>\n";

			// Add any units if present from the blob
			$xml.= $this->evidences; 
					
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
	
	public $id = NULL;
	public $lsc_learning_aim = NULL;
	public $awarding_body = NULL;
	public $title = NULL;
	public $level = NULL;
	public $qualification_type = NULL;

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
	public $tr_id = NUll;
	public $evidences = NULL;
	public $unitsNotStarted = NULL;
	public $unitsBehind = NULL;
	public $unitsOnTrack = NULL;
	public $unitsUnderAssessment = NULL;
	public $unitsCompleted = NULL;
	public $internaltitle = NULL;
	public $aptitude = NULL;
	public $attitude = NULL;
	public $comments = NULL;
	public $modified = NULL;
	public $username = NULL;
	public $trading_name = NULL;
	public $auto_id = NULL;
	public $start_date = NULL;
	public $end_date = NULL;
	public $actual_end_date = NULL;
	public $achievement_date = NULL;
	public $units_required = NULL;
	public $awarding_body_reg = NULL;
	public $awarding_body_date = NULL;
	public $awarding_body_batch = NULL;
	public $proportion = NULL;
	public $glh = NULL;
	public $smart_assessor_id = NULL;
	public $qual_exempt = NULL;
	public $qual_sequence = NULL;

	private $HTML_NEW_LINES = array('<br>', '<br/>', '<br />', '<BR>', '<BR/>', '<BR />', '</p>', '</P>');
}


?>