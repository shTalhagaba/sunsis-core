<?php
class Qualification extends Entity
{
    public static function loadFromDatabase(PDO $link, $qualification_id, $internaltitle, $clients = '')
    {
        if(is_null($qualification_id))
        {
            return null;
        }

        $q = new Qualification();

        $internaltitle = addslashes($internaltitle);

        if(DB_NAME=='am_edexcel')
        {
            if($clients=='')
                $username = $_SESSION['user']->username;
            else
                $username = $clients;

            $sql = "SELECT * FROM qualifications WHERE id='$qualification_id' and internaltitle='$internaltitle' and clients='$username';";
        }
        else
        {
            $sql = "SELECT * FROM qualifications WHERE id='$qualification_id' and internaltitle='$internaltitle';";
        }

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

        // Load units
//		$q->units = QualificationUnits::loadFromDatabase($link, $q->id);

        // Load performance figures
        //$q->grades = QualificationGrade::loadFromDatabase($link, $q->id);

        // Load Elements
        // $q->elements = QualificationElement::loadFromDatabase($link, $q->id);

        return $q;
    }

    public static function loadFromCache(PDO $link, $qualification_id)
    {
        if(is_null($qualification_id))
        {
            return null;
        }

        $q = new Qualification();

        $qualification_id = str_replace('/','',$qualification_id);

        $sql = "SELECT * FROM central.qualifications WHERE replace(id,'/','')='$qualification_id'";

        $st = $link->query($sql);

        if($st)
        {
            if($row = $st->fetch())
            {
                $q->populate($row);
                $q->regulation_start_date = $row['accreditation_start_date'];
                $q->operational_start_date = $row['accreditation_start_date'];
                $q->operational_end_date = $row['accreditation_end_date'];
                $q->certification_end_date = $row['certification_end_date'];
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

        // Load units
//		$q->units = QualificationUnits::loadFromDatabase($link, $q->id);

        // Load performance figures
        //$q->grades = QualificationGrade::loadFromDatabase($link, $q->id);

        // Load Elements
        // $q->elements = QualificationElement::loadFromDatabase($link, $q->id);

        return $q;
    }

    public static function loadFromXML($xml)
    {
        $q = new Qualification();

        if(strpos($xml, '<?xml version=') === false)
        {
            // Go against the grain and assume ISO-8859-1
            $xml = '<?xml version="1.0" encoding="ISO-8859-1"?>' . $xml;
        }

        //$root = new SimpleXMLElement($xml);
        $root = XML::loadSimpleXML($xml);

        $q->title = utf8_decode($root['title']);
        $q->internaltitle = utf8_decode($root['internaltitle']);
        $q->qualification_type = utf8_decode($root['type']);
        $q->level = utf8_decode($root['level']);
        $q->id = utf8_decode($root['reference']);
        $q->awarding_body = utf8_decode($root['awarding_body']);
        $q->regulation_start_date = utf8_decode($root['regulation_start_date']);
        $q->operational_start_date = utf8_decode($root['operational_start_date']);
        $q->operational_end_date = utf8_decode($root['operational_end_date']);
        $q->certification_end_date = utf8_decode($root['certification_end_date']);

        $q->mainarea = $root->mainarea;
        $q->subarea = $root->subarea;

        $q->description = utf8_decode($root->description);
        $q->assessment_method = utf8_decode($root->assessment_method);
        $q->structure = utf8_decode($root->structure);
        $q->guided_learning_hours = utf8_decode($root['guided_learning_hours']);


        /*		$counter = 1;
          foreach($root->units as $units) // For every <units> element
          {
              $q->units = QualificationUnits::loadFromXML(utf8_decode($units->asXML()));
              $q->units->initialise(0, $q->id);

              break; // There should only be one <units> element directly under Qualification
          }


          // Performance figures
          $counter = 1;
          foreach($root->performance_figures as $figures)
          {
              foreach($figures->children() as $tag)
              {
                  // Process each attainment figure
                  if($tag->getName() == "attainment")
                  {
                      $g = new QualificationGrade();
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

    public static function loadFromDatabaseByAutoId(PDO $link, $auto_id)
    {
        if($auto_id == '')
        {
            return null;
        }

        $key = addslashes($auto_id);
	$st = $link->query("SELECT * FROM qualifications WHERE auto_id = '{$key}'");
        $qualification = null;
        if($st)
        {
            $qualification = null;
            $row = $st->fetch();
            if($row)
            {
                $qualification = new Qualification();
                $qualification->populate($row);
            }
        }
        else
        {
            throw new Exception("Could not execute database query to find qualification. " . $link->errorCode());
        }

        return $qualification;
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

        if(is_null($this->mainarea) || $this->mainarea == '')
        {
            $sql_mainarea_from_lars = <<<SQL
SELECT lookup.SectorSubjectAreaTier1Desc
FROM qualifications INNER JOIN lars201415.`Core_LARS_LearningDelivery` AS larsld
ON REPLACE(larsld.LearnAimRef, '/', '') = REPLACE(qualifications.id, '/', '')
INNER JOIN lars201415.`CoreReference_LARS_SectorSubjectAreaTier1_Lookup` AS lookup
ON lookup.SectorSubjectAreaTier1 = larsld.SectorSubjectAreaTier1
WHERE REPLACE(qualifications.id, '/', '') = REPLACE('$this->id', '/','')
GROUP BY larsld.`LearnAimRef`
;
SQL;
            $this->mainarea = DAO::getSingleValue($link, $sql_mainarea_from_lars);
        }

        if(is_null($this->subarea) || $this->subarea == '')
        {
            $sql_subarea_from_lars = <<<SQL
SELECT lookup.SectorSubjectAreaTier2Desc
FROM qualifications INNER JOIN lars201415.`Core_LARS_LearningDelivery` AS larsld
ON REPLACE(larsld.LearnAimRef, '/', '') = REPLACE(qualifications.id, '/', '')
INNER JOIN lars201415.`CoreReference_LARS_SectorSubjectAreaTier2_Lookup` AS lookup
ON lookup.SectorSubjectAreaTier2 = larsld.SectorSubjectAreaTier2
WHERE REPLACE(qualifications.id, '/', '') = REPLACE('$this->id', '/','')
GROUP BY larsld.`LearnAimRef`
;
SQL;
            $this->subarea = DAO::getSingleValue($link, $sql_subarea_from_lars);
        }

        $this->evidences = str_replace("\xA0", " ", $this->evidences);
        $this->evidences = preg_replace('/[\x00-\x1F\x7F]/u', '', $this->evidences);

        DAO::saveObjectToTable($link, 'qualifications', $this);



        return $this->id;
    }


    /**
     * Deletes the qualification and its structure, but leaves the units untouched.
     * Maybe later we could add a routine to delete unused units?
     * Should be called in a transaction
     */
    public function delete(PDO $link)
    {
        $qan = addslashes($this->id);
        $internaltitle = addslashes($this->internaltitle);

        // Delete the qualification's structure and the qualification
        $sql = <<<HEREDOC
DELETE FROM
	qualifications
WHERE
	id = '$qan' and internaltitle='$internaltitle';
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
            .$p.'level="'.htmlspecialchars($this->level).'" '
            .$p.'type="'.htmlspecialchars($this->qualification_type).'" '
            .$p.'regulation_start_date="'.htmlspecialchars(Date::toMySQL($this->regulation_start_date)).'" '
            .$p.'operational_start_date="'.htmlspecialchars(Date::toMySQL($this->operational_start_date)).'" '
            .$p.'operational_end_date="'.htmlspecialchars(Date::toMySQL($this->operational_end_date)).'" '
            .$p.'certification_end_date="'.htmlspecialchars(Date::toMySQL($this->certification_end_date)).'" '
            .$p.'mainarea="'.htmlspecialchars($this->mainarea).'" '
            .$p.'subarea="'.htmlspecialchars($this->subarea).'" '
            .$p.'guided_learning_hours="'.htmlspecialchars($this->guided_learning_hours).'">' . "\n";


        $xml .= "<{$p}description>".htmlspecialchars($this->description)."</{$p}description>\n";
        $xml .= "<{$p}assessment_method>".htmlspecialchars($this->assessment_method)."</{$p}assessment_method>\n";
        $xml .= "<{$p}structure>".htmlspecialchars($this->structure)."</{$p}structure>\n";

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

    public $id = NULL;
    public $lsc_learning_aim = NULL;
    public $awarding_body = NULL;
    public $title = NULL;
    public $level = NULL;
    public $qualification_type = NULL;
    public $credits = NULL;

    public $description = NULL;
    public $assessment_method = NULL;
    public $structure = NULL;

    public $regulation_start_date = NULL;			// The first date that awarding bodies can register candidates for a qualification.
    public $operational_start_date = NULL;	// The date when the qualification will become operational in centres.
    public $operational_end_date = NULL;				// The final date that a candidate wanting to undertake a qualification can register.
    public $certification_end_date = NULL;				// The final date by which registered candidates must complete the qualification.


    public $grades = NULL;
    public $units = NULL;
    public $elements = NULL;
    public $evidences = NULL;
    public $internaltitle = NULL;
    public $recordid = NULL;
    public $total_units = NULL;
    public $total_proportion = NULL;
    public $unitswithevidence = NULL;
    public $elements_without_evidence = NULL;
    public $units_required = NULL;
    public $mandatory_units = NULL;
    public $clients = NULL;
    public $mainarea = NULL;
    public $subarea = NULL;
    public $guided_learning_hours = NULL;
    public $qual_status = NULL;
    public $active;
    public $ebs_ui_code;
    public $auto_id;
    public $total_credit_value;
    public $units_guided_learning_hours;
    public $units_credit_value;
    public $tqt = null;		


    private $HTML_NEW_LINES = array('<br>', '<br/>', '<br />', '<BR>', '<BR/>', '<BR />', '</p>', '</P>');
}


?>