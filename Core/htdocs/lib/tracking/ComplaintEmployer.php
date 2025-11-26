<?php
class ComplaintEmployer extends Complaint
{
	public function __construct($organisation_id)
	{
		$this->record_id = $organisation_id;
		$this->complaint_type = Complaint::EMPLOYER_COMPLAINT;
	}

	public function getEmployerDetails(PDO $link)
	{
		return DAO::getObject($link, "SELECT * FROM organisations WHERE id = '{$this->record_id}'");
	}
}