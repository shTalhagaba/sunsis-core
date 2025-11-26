<?php
class rec_save_candidate implements IAction
{

	public function execute(PDO $link)
	{
		//pre($_REQUEST);
		if(isset($_REQUEST['candidate_id']) && $_REQUEST['candidate_id'] != '')
			$_REQUEST['id'] = $_REQUEST['candidate_id'];

		$vo = new RecCandidate();
		$vo->populate($_REQUEST);

		//pre($vo);

		$shift_pattern = array(
			'mon_start_time',
			'tue_start_time',
			'wed_start_time',
			'thu_start_time',
			'fri_start_time',
			'sat_start_time',
			'sun_start_time',
			'mon_end_time',
			'tue_end_time',
			'wed_end_time',
			'thu_end_time',
			'fri_end_time',
			'sat_end_time',
			'sun_end_time'
		);
		DAO::transaction_start($link);
		try
		{
			$vo->save($link);
			if($_REQUEST['candidate_id'] == '')
				$vo->saveCandidateNotes($link, 'Candidate record is created');
			$this->save_candidate_qualifications($link, $vo->id);
			$this->save_candidate_employments($link, $vo->id);
			$this->upload_cv($vo->id);
			if(isset($_REQUEST['lldd_options']))
				$this->saveCandidateLLDDOptions($link, $vo->id, $_REQUEST['lldd_options']);

			$stdShiftPattern = new stdClass();
			foreach($shift_pattern AS $key)
			{
				if(isset($_REQUEST[$key]))
					$stdShiftPattern->$key = $_REQUEST[$key];
			}
			if(isset($stdShiftPattern))
			{
				$stdShiftPattern->candidate_id = $vo->id;
				DAO::saveObjectToTable($link, 'candidate_shift_patterns', $stdShiftPattern);
			}

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		if(IS_AJAX)
		{
			header("Content-Type: text/plain");
			echo $vo->id;
		}
		else
		{
			http_redirect('do.php?_action=rec_read_candidate&id='.$vo->id);
		}
	}

	public function save_candidate_qualifications(PDO $link, $candidate_id)
	{
		if($candidate_id == '')
			return;

		$qualifications = array();
		$objGCSEEnglish = new stdClass();
		$objGCSEEnglish->candidate_id = $candidate_id;
		$objGCSEEnglish->qualification_level = 'GCSE';
		$objGCSEEnglish->qualification_subject = 'English Language';
		$objGCSEEnglish->qualification_grade = isset($_REQUEST['gcse_english_grade'])?$_REQUEST['gcse_english_grade']:'';
		$objGCSEEnglish->qualification_date = isset($_REQUEST['gcse_english_date_completed'])?$_REQUEST['gcse_english_date_completed']:'';
		$objGCSEEnglish->institution = isset($_REQUEST['gcse_english_school_name'])?$_REQUEST['gcse_english_school_name']:'';
		$qualifications[] = $objGCSEEnglish;

		$objGCSEMaths = new stdClass();
		$objGCSEMaths->candidate_id = $candidate_id;
		$objGCSEMaths->qualification_level = 'GCSE';
		$objGCSEMaths->qualification_subject = 'Maths';
		$objGCSEMaths->qualification_grade = isset($_REQUEST['gcse_maths_grade'])?$_REQUEST['gcse_maths_grade']:'';
		$objGCSEMaths->qualification_date = isset($_REQUEST['gcse_maths_date_completed'])?$_REQUEST['gcse_maths_date_completed']:'';
		$objGCSEMaths->institution = isset($_REQUEST['gcse_maths_school_name'])?$_REQUEST['gcse_maths_school_name']:'';
		$qualifications[] = $objGCSEMaths;

		for($i = 1; $i <= 5; $i++)
		{
			$objQualification = new stdClass();
			$objQualification->candidate_id = $candidate_id;
			$objQualification->qualification_level = isset($_REQUEST['level_'.$i])?$_REQUEST['level_'.$i]:'';
			$objQualification->qualification_subject = isset($_REQUEST['level_'.$i])?$_REQUEST['subject_'.$i]:'';
			$objQualification->qualification_grade = isset($_REQUEST['grade_'.$i])?$_REQUEST['grade_'.$i]:'';
			$objQualification->qualification_date = isset($_REQUEST['date_completed_'.$i])?$_REQUEST['date_completed_'.$i]:'';
			$objQualification->institution = isset($_REQUEST['school_name_'.$i])?$_REQUEST['school_name_'.$i]:'';
			$all_blank = true;
			foreach ($objQualification AS $key => $value)
			{
				if($key != 'candidate_id' && trim($value) != '')
				{
					$all_blank = false;
					break;
				}
			}
			if(!$all_blank)
				$qualifications[] = $objQualification;
		}

		DAO::execute($link, "DELETE FROM candidate_qualification WHERE candidate_id = '{$candidate_id}'");

		DAO::multipleRowInsert($link, 'candidate_qualification', $qualifications);
	}

	public function save_candidate_employments(PDO $link, $candidate_id)
	{
		if($candidate_id == '')
			return;

		$employments = array();

		for($i = 1; $i <= 5; $i++)
		{
			$objEmployment = new stdClass();
			$objEmployment->candidate_id = $candidate_id;
			$objEmployment->company_name = isset($_REQUEST['company_name_'.$i])?$_REQUEST['company_name_'.$i]:'';
			$objEmployment->job_title = isset($_REQUEST['job_title_'.$i])?$_REQUEST['job_title_'.$i]:'';
			$objEmployment->start_date = isset($_REQUEST['start_date_'.$i])?$_REQUEST['start_date_'.$i]:'';
			$objEmployment->end_date = isset($_REQUEST['end_date_'.$i])?$_REQUEST['end_date_'.$i]:'';
			$objEmployment->skills = isset($_REQUEST['skills_'.$i])?Text::utf8_to_latin1($_REQUEST['skills_'.$i]):'';
			$all_blank = true;
			foreach ($objEmployment AS $key => $value)
			{
				if($key != 'candidate_id' && trim($value) != '')
				{
					$all_blank = false;
					break;
				}
			}
			if(!$all_blank)
				$employments[] = $objEmployment;
		}

		DAO::execute($link, "DELETE FROM candidate_history WHERE candidate_id = '{$candidate_id}'");

		DAO::multipleRowInsert($link, 'candidate_history', $employments);
	}

	private function saveCandidateLLDDOptions(PDO $link, $candidate_id, $lldd_options)
	{
		if($candidate_id == '' || !is_array($lldd_options))
			return;
		DAO::execute($link, "DELETE FROM candidate_lldd WHERE candidate_id = '{$candidate_id}'");
		foreach($lldd_options AS $option)
			DAO::execute($link, "INSERT INTO candidate_lldd (candidate_id, lldd) VALUES ('{$candidate_id}', '{$option}')");
	}

	private function upload_cv($candidate_id)
	{
		$target_directory = 'recruitment';
		$filepaths = Repository::processFileUploads('cv_file', $target_directory);
		foreach($filepaths as $filepath)
		{
			$ext = pathinfo($filepath, PATHINFO_EXTENSION);
			$path = dirname($filepath);
			rename($filepath, $path.'/cv_1_'.$candidate_id.'.'.$ext);
		}
	}
}