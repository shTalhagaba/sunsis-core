<?php
class QualSpecification extends Entity
{
	public static function loadFromDatabase(PDO $link, $qan_id)
	{
		if($qan_id == '')
		{
			return null;
		}
		
		$key = addslashes((string)$qan_id);
		$query = <<<HEREDOC
SELECT
	*
FROM
	qualification_specification
WHERE
	qan_id='$key';
HEREDOC;
		$st = $link->query($query);

		$spec = null;
		if( $st ) {
			$spec = null;
			$spec = new QualSpecification();
			while( $row = $st->fetch() ) {
				
				$unitcode = array();
				$unitcode['id'] = $row['id'];
				$unitcode['qan_id'] = $row['qan_id'];
				$unitcode['unit_id'] = $row['unit_id'];
				$unitcode['unit_title'] = $row['unit_title'];
				$unitcode['unit_code'] = $row['unit_code'];
				$unitcode['unit_reference_number'] = $row['unit_reference_number'];
				$unitcode['unit_aim'] = $row['unit_aim'];
				$unitcode['unit_summary'] = $row['unit_summary'];
				$unitcode['unit_requirements'] = $row['unit_requirements'];
				// added in additional sections to split the unit requirements
				$unitcode['unit_evidence'] = $row['unit_evidence'];
				$unitcode['unit_observation'] = $row['unit_observation'];
				
				$unitcode['assessment_criteria'] = $row['assessment_criteria'];
				$unitcode['unit_content'] = $row['unit_content'];
				
				$spec->acc_start_date = $row['accreditation_start_date'];
				$spec->acc_end_date = $row['accreditation_end_date'];
				
				// key value must strip whitespace and be lowercased - as there is inconsistency 
				// between RITS and Specification files on these things....
				// also need to verify as can appear more than once?
				$check_unit_title = preg_replace("/[^a-zA-Z0-9!?\'\"]/", "", strtolower($row['unit_title']));
				if ( in_array($check_unit_title, array_keys($spec->qual_specification)) ) {
						
					//$spec->qual_specification[$check_unit_title."_unit".$unitcode['unit_id']] = $unitcode;	
					$spec->qual_specification[$check_unit_title."_unit".$unitcode['unit_reference_number']] = $unitcode;
				}
				else {
					$spec->qual_specification[preg_replace("/[^a-zA-Z0-9!?\'\"]/", "", strtolower($row['unit_title'])) ] = $unitcode;
				}
			}
			
		}
		else {
			throw new Exception("Could not execute database query to find specification");
		}
		return $spec;	
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param PDO $link
	 */
	public function save( PDO $link ) {
		return DAO::saveObjectToTable($link, 'qualification_specification', $this);
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param PDO $link
	 * @throws Exception
	 */
	public function delete( PDO $link )	{
		if( !$this->isSafeToDelete($link) )	{
			throw new Exception("This qualification specification is not safe to delete");
		}
		
		$sql = <<<HEREDOC
DELETE FROM
	qualification_specification
WHERE
	qualification_specification.qan_id='{$this->qan_id}'
HEREDOC;
		DAO::execute($link, $sql);
	}

	/**
	 * 
	 * Enter description here ...
	 * @param PDO $link
	 */	
	public function isSafeToDelete(PDO $link)
	{
		return TRUE;
	}
	
	
	public $id = NULL;
	public $qan_id = NULL;
	public $unit_id = NULL;
	public $unit_title = NULL;
	public $unit_code = NULL;
	public $unit_reference_number = NULL;
	public $unit_aim = NULL;
	public $unit_summary = NULL;
	public $unit_requirements = NULL;
	public $unit_observation = NULL;
	public $unit_evidence = NULL;
	public $unit_content = NULL;
	public $assessment_criteria = NULL;
	public $acc_start_date = NULL;
	public $acc_end_date = NULL;
	
	public $qual_specification = array();

}
?>