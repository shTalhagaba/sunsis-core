<?php
class ComplaintLearner extends Complaint
{
	public function __construct($training_record_id)
	{
		$this->record_id = $training_record_id;
		$this->complaint_type = Complaint::LEARNER_COMPLAINT;
	}

	public function getLearnerDetails(PDO $link)
	{
		return DAO::getObject($link, "SELECT firstnames, surname, home_email, learner_work_email, home_telephone, home_mobile FROM tr WHERE id = '{$this->record_id}'");
	}
}