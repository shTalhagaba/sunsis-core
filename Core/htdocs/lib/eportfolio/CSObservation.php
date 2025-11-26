<?php
class CSObservation extends Entity
{
	public function __construct($tr_id)
	{
		$this->tr_id = $tr_id;
		$this->evidences = XML::loadSimpleXML('<Units></Units>');
	}

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
	cs_observations
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$observation = null;
		if($st)
		{
			$observation = null;
			$row = $st->fetch();
			if($row)
			{
				$observation = new CSObservation($row['tr_id']);
				$observation->populate($row);
				$observation->evidences = XML::loadSimpleXML($observation->evidences);
			}
		}
		else
		{
			throw new Exception("Could not execute database query to find observation record. " . '----' . $query . '----' . $link->errorCode());
		}

		return $observation;
	}

	public static function loadFromDatabaseByTrainingId(PDO $link, $tr_id)
	{
		if (!$tr_id || !is_numeric($tr_id)) {
			throw new Exception("Missing or non-numeric id");
		}

		$id = DAO::getSingleValue($link, "SELECT id FROM cs_observations WHERE tr_id=" . $link->quote($tr_id));
		if (!$id) {
			return null;
		}

		return self::loadFromDatabase($link, $id);
	}

	public function save(PDO $link)
	{
		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		@$dom->loadXML($this->evidences->saveXML());
		$dom->formatOutput = TRUE;
		$this->evidences = $dom->saveXml();
		$this->evidences = str_replace('<?xml version="1.0"?>', '', $this->evidences);

		return DAO::saveObjectToTable($link, 'cs_observations', $this);
	}

	public function updateProgress(PDO $link)
	{
		$obs_unit_Reference = 'Unit 12';
		$qualification_xml = DAO::getSingleValue($link, "SELECT evidences FROM student_qualifications WHERE tr_id = '{$this->tr_id}' AND id = '" . Workbook::CS_QAN . "'");
		$qualification = XML::loadSimpleXML($qualification_xml);

		$obs_unit = $qualification->xpath('//units[@title="Additional Evidences"]/unit[@owner_reference="'.$obs_unit_Reference.'"]');
		if(!isset($obs_unit[0]))
		{
			$obs_unit_Reference = str_replace(' ', '', $obs_unit_Reference);
			$obs_unit = $qualification->xpath('//units[@title="Additional Evidences"]/unit[@owner_reference="'.$obs_unit_Reference.'"]');
		}
		$obs_unit = $obs_unit[0];
		$obs_unit->attributes()->percentage = 100; // update unit progress

		$qualification->attributes()->percentage = (int)$qualification->attributes()->percentage + (int)$obs_unit->attributes()->proportion; // updated qualification overall progress
		DAO::execute($link, "UPDATE student_qualifications SET unitsUnderAssessment = unitsUnderAssessment + " . (int)$obs_unit->attributes()->proportion . " WHERE tr_id = '" . $this->tr_id . "' AND id = '" . Workbook::CS_QAN . "'"); 

		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		@$dom->loadXML($qualification->saveXML());
		$dom->formatOutput = TRUE;
		$qualification = $dom->saveXml();
		$qualification = str_replace('<?xml version="1.0"?>', '', $qualification);
		DAO::execute($link, "UPDATE student_qualifications SET evidences = '{$qualification}' WHERE tr_id = '{$this->tr_id}' AND id = '" . Workbook::CS_QAN . "'");
	}

	public function getHeaderLogo(PDO $link)
	{
		$employer_legal_name = DAO::getSingleValue($link, "SELECT legal_name FROM organisations INNER JOIN tr ON organisations.id = tr.employer_id WHERE tr.id = '{$this->tr_id}'");
		return strpos(strtolower($employer_legal_name), 'savers') !== false ? 'Savers.png' : 'superdrug.png';
	}

	public $id = NULL;
	public $tr_id = NULL;
	public $evidences = NULL;
	public $assessor_sign = NULL;
	public $assessor_sign_date = NULL;
	public $full_save = NULL;

}
