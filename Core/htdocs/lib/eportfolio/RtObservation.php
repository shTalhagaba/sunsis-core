<?php
class RtObservation extends Entity
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
	rt_observations
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
				$observation = new RtObservation($row['tr_id']);
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

		$id = DAO::getSingleValue($link, "SELECT id FROM rt_observations WHERE tr_id=" . $link->quote($tr_id));
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

		return DAO::saveObjectToTable($link, 'rt_observations', $this);
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
