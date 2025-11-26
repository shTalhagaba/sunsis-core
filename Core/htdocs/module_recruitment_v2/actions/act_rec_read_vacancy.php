<?php
class rec_read_vacancy implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';
		$export = isset($_GET['export']) ? $_GET['export'] : '';

		if($subaction == 'saveSectorQuestions')
		{
			echo $this->saveSectorQuestions($link);
			exit;
		}
		if($subaction == 'uploadVacancyToNAS')
		{
			echo $this->uploadVacancyToNAS($link);
			exit;
		}

		if($id == '')
		{
			throw new Exception("Missing argument \$id");
		}

		$_SESSION['bc']->add($link, "do.php?_action=rec_read_vacancy&id={$id}", "View Vacancy");

		$vo = RecVacancy::loadFromDatabase($link, $id);

		if($vo==null)
		{
			throw new Exception("could not found");
		}

		if($export=='pdf')
		{
			$this->exportToPDF($link, $vo);
		}
		elseif($export=='word')
		{
			$this->exportToWord($link, $vo);
		}

		$vacancy_location = DAO::getSingleValue($link, "SELECT CONCAT(COALESCE(full_name), ' (',COALESCE(`address_line_1`,''),' ',COALESCE(`address_line_2`,''),', ', COALESCE(`postcode`,''), ')') AS location FROM locations WHERE id = '$vo->location_id'");
		$sector_questions_ddl = DAO::getResultset($link, "SELECT id, description FROM rec_questions WHERE sector_id = '{$vo->sector}' AND type = '1' ORDER BY description");
		$selected_sector_questions = DAO::getSingleColumn($link, "SELECT question_id FROM rec_vacancy_questions INNER JOIN rec_questions ON rec_vacancy_questions.question_id = rec_questions.id WHERE rec_questions.type = '1' AND vacancy_id = '{$vo->id}'");

		require_once('tpl_rec_read_vacancy.php');
	}

	private function saveSectorQuestions(PDO $link)
	{
		$vacancy_id = isset($_REQUEST['vacancy_id'])?$_REQUEST['vacancy_id']:'';
		$question_ids = isset($_REQUEST['question_ids'])?json_decode($_REQUEST['question_ids']):'';
		if($vacancy_id == '' || $question_ids == '' || !is_array($question_ids))
			return 'missing querystring data';

		DAO::transaction_start($link);
		try
		{
			DAO::execute($link, "DELETE FROM rec_vacancy_questions WHERE vacancy_id = '{$vacancy_id}'");
			foreach($question_ids AS $q)
			{
				$o = new stdClass();
				$o->vacancy_id = $vacancy_id;
				$o->question_id = $q;
				DAO::saveObjectToTable($link, 'rec_vacancy_questions', $o);
			}
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link);
			throw new Exception($e);
		}
		return true;
	}

	private function exportToPDF(PDO $link)
	{

		$vacancy = RecVacancy::loadFromDatabase($link, $_REQUEST['id']);

		include("./MPDF57/mpdf.php");

		$mpdf=new mPDF('','','','',15,15,47,16,9,9);

		$mpdf->SetImportUse();

		$html = <<<HTML
<div id="wrapper">
	<div id="contentwrap">
		<div id="logo" align="center"><img align="center" src="/images/logos/superdrug-logo-trans.png" height="50"/></div>
		<p>
			<br>
			Superdrug is part of the AS Watson Group and is the UK's second-largest beauty and health retailer currently
			operating over 830 stores in England, Scotland, Wales, Northern Ireland and the Republic of Ireland. We
			currently have about 200 in-store pharmacies.
			Our purpose is to be the best in everyday accessible health and we are committed to bringing innovation and
			new products to the high street at affordable prices, along with delivering fantastic customer service.
		</p>

		<div align="center"><img src="/images/aaa.jpg"/></div>
		<br>

		<h3 align="center">$vacancy->job_title</h3>
		<br>

		<div style="border-radius: 10px; border: 1px solid #000000; padding: 5px;">
			<table cellspacing="0" cellpadding="5" width="100%">
				<tr>
					<td>
						<p align="center">
							We are looking for a Pharmacist whose skills go beyond just great clinical, professional and
							management
							ones. We're after people who can bring real commerciality and leadership to their pharmacy,
							inspiring
							every person who works there to deliver exceptional service and exceed targets. As the
							pharmacist on
							site you will be responsible for supporting the Pharmacy Manager to deliver great service,
							stock
							control, operational and GPhC standards. You will receive direct line management support
							from your
							Pharmacy Manager.
						</p>

						<p align="center">
							If you have outstanding communication and interpersonal skills, you could become a vital
							member of both
							the store team and the local community and could help to maintain excellent customer
							loyalty.
						</p>

						<p align="center">
							It's a great experience that will prepare you for even bigger challenges. Our pharmacists
							are extremely
							important to us; we recognise that you could be our Pharmacy Managers of tomorrow. We will
							support you
							to develop both your professional and commercial skills.
						</p>
					</td>
				</tr>
			</table>
		</div>
		<br>
		<div style="border-radius: 10px; border: 1px solid #000000; padding: 5px;">
			<table cellspacing="0" cellpadding="5" width="100%">
				<tr>
					<td align="center">
						Superdrug Stores Plc<br>
						Stadium Way<br>
						South Elmsall<br>
						Pontefract<br>
						WF9 2XR<br>
						01977657008<br><br>
					</td>
				</tr>
			</table>
		</div>

	</div>
</div>
HTML;


		$mpdf->AddPage();
		$mpdf->WriteHTML($html);


		$mpdf->Output();

		exit;
	}

	private function exportToWord(PDO $link, Vacancy $vacancy)
	{
		require_once('./lib/PHPWord/PHPWord.php');

		$PHPWord = new PHPWord();


		$document = $PHPWord->loadTemplate(DATA_ROOT."/uploads/am_pathway/BalticTrainingVacancyTemplate.docx");
		$document->setValue('CREATION_DATE', $vacancy->created);
		$document->setValue('JOB_TITLE', $vacancy->job_title);
		$document->setValue('VACANCY_CODE', $vacancy->code);
		$document->setValue('AWARD_TO_BE_COMPLETED', $vacancy->award_sector);
		$document->setValue('No_Of_Vacancies', $vacancy->no_of_vacancies);
		$document->setValue('SALARY', $vacancy->salary);
		$document->setValue('LOCATION', $vacancy->location);
		$document->setValue('ACTIVE', $vacancy->active);
		$document->setValue('SOURCE', $vacancy->source);
		$document->setValue('BRM', $vacancy->brm);
		$document->setValue('APPRENTICESHIP_TYPE', $vacancy->apprenticeship_type);
		$document->setValue('DD', $vacancy->dd);
		$document->setValue('AGE', $vacancy->age);
		$document->setValue('AT_RISK', $vacancy->at_risk);
		$document->setValue('INDUCTION_CONFIRMED', $vacancy->interview_date);
		$document->setValue('INDUCTION_DAE', $vacancy->induction_date);
		$document->setValue('EXPECTED_WEEKLY_WORKING_ROUTINE', $vacancy->shift_pattern);
		$document->setValue('JOB_DESCRIPTION', $vacancy->description);
		$document->setValue('PERSON_SPECIFICATION', $vacancy->person_spec);
		$document->setValue('IMPORTANT_OTHER_INFORMATION', $vacancy->prospects);
		$document->setValue('POSSIBILITY_TO_COMPLETE_A_LEVEL_3_ADVANCE_APPRENTICESHIP', $vacancy->prospects);
		$document->setValue('ADDITIONAL_COMMENTS_WITH_DATES', $vacancy->comments);
		$document->save(DATA_ROOT."/uploads/am_pathway/BalticTrainingVacancyTemplateVaa.docx");

		exit;
	}

	private function uploadVacancyToNAS(PDO $link)
	{
		include_once('./lib/NAS/VacancyManagement/VacancyUploader.php');

		$vacancy_id = isset($_REQUEST['vacancy_id'])?$_REQUEST['vacancy_id']:'';
		if($vacancy_id == '')
			throw new Exception('Missing quersting argument: vacancy_id');

		$vacancy = RecVacancy::loadFromDatabase($link, $vacancy_id);
		if(is_null($vacancy))
			throw new Exception('Vacancy not found');

		$errors = VacancyUploader::checkVacancyMandatoryInformation($link, $vacancy);
		if($errors != '')
		{
			unset($vacancy);
			return $errors;
		}

		return VacancyUploader::uploadVacancyToNAS($link, $vacancy);
	}
}
?>