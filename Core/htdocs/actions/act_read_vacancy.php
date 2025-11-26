<?php
class read_vacancy implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$export = isset($_GET['export']) ? $_GET['export'] : '';
		
		if($id == '')
		{
			throw new Exception("Missing argument \$id");
		}
		

		$vo = Vacancy::loadFromDatabase($link, $id);

		$que = "select description from lookup_vacancy_type where id='$vo->type'";
		$type_of_vacancy = trim(DAO::getSingleValue($link, $que));
		
		
		
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

/*		$query = "select description from lookup_sector_types where id=$vo->sector";
		$sector= trim(DAO::getSingleValue($link, $query));
*/
		if(DB_NAME == "am_ray_recruit")
			$vacancy_job_type_dropdown = array('P' => 'Permanent', 'C' => 'Contract', 'T' => 'Temporary', 'AL2' => 'Apprenticeship Level 2', 'AL3' => 'Apprenticeship Level 3');
		else
			$vacancy_job_type_dropdown = array('P' => 'Permanent', 'C' => 'Contract', 'T' => 'Temporary');
		$vacancy_job_hours_dropdown = array('F' => 'Full Time','P' =>'Part Time');

		require_once('tpl_read_vacancy.php');
	}

	private function exportToPDF(PDO $link, Vacancy $vacancy)
	{

		include("./MPDF57/mpdf.php");

		$mpdf=new mPDF('','','','',15,15,47,16,9,9);
		// LOAD a stylesheet
		$stylesheet = file_get_contents('common.css');
		$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

		$mpdf->SetImportUse();

		$mpdf->SetDocTemplate('BalticTrainingVacancyTemplate.pdf',1);	// 1|0 to continue after end of document or not - used on matching page numbers

		$html = '';
		$vacancy->dd =  ($vacancy->dd == 1)? 'Yes':'No';
		$vacancy->age =  ($vacancy->age == 1)? 'Yes':'No';
		$vacancy->at_risk =  ($vacancy->at_risk == 1)? 'Yes':'No';
		$vacancy->to_level_3 =  ($vacancy->to_level_3 == 1)? 'Yes':'No';
		$vacancy->induction_confirmed =  ($vacancy->induction_confirmed == 1)? 'Yes':'No';
		$que = "select description from lookup_vacancy_type where id='$vacancy->type'";
		$type_of_vacancy = trim(DAO::getSingleValue($link, $que));
		$vacancy->created = date ( 'D, d M Y H:i:s T', strtotime ( $vacancy->created ) );
		$vacancy->interview_date = Date::toMedium($vacancy->interview_date);
		$vacancy->induction_date = Date::toMedium($vacancy->induction_date);

		$html = <<<HEREDOC
<h3> Vacancy Details</h3>
<table border="0" cellspacing="4" cellpadding="4">
	<col width="25" /><col />
	<tr>
		<td class="fieldLabel">Creation Date: </td>
		<td class="fieldValue" >$vacancy->created</td>
	</tr>
	<tr>
		<td class="fieldLabel">Job Title:</td>
		<td class="fieldValue" >$vacancy->job_title</td>
		<td class="fieldLabel">Vacancy Code:</td>
		<td class="fieldValue">$vacancy->code</td>
	</tr>
	<tr>
		<td class="fieldLabel">Award to be completed:</td>
		<td class="fieldValue">$type_of_vacancy</td>
		<td class="fieldLabel"> No. of Vacancies:</td>
		<td class="fieldValue">$vacancy->no_of_vacancies</td>
	</tr>
	<tr>
		<td class="fieldLabel"> Proposed Interview Date:</td>
		<td class="fieldValue">$vacancy->interview_date</td>
		<td class="fieldLabel"> Salary Information:</td>
		<td class="fieldValue">$vacancy->salary</td>
	</tr>
	<tr>
		<td class="fieldLabel"> Location:</td>
		<td class="fieldValue">$vacancy->postcode</td>
		<td class="fieldLabel"> Active Vacancy:</td>
		<td class="fieldValue">$vacancy->active</td>
	</tr>
	<tr>
		<td class="fieldLabel"> Source:</td>
		<td class="fieldValue">$vacancy->source</td>
		<td class="fieldLabel"> Business Resource Manager:</td>
		<td class="fieldValue">$vacancy->brm</td>
	</tr>
	<tr>
		<td class="fieldLabel"> Apprenticeship Type:</td>
		<td class="fieldValue">$vacancy->apprenticeship_type</td>
		<td class="fieldLabel"> Due Diligence:</td>
		<td class="fieldValue">$vacancy->dd</td>
	</tr>
	<tr>
		<td class="fieldLabel"> Age:</td>
		<td class="fieldValue">$vacancy->age</td>
		<td class="fieldLabel"> At Risk:</td>
		<td class="fieldValue">$vacancy->at_risk</td>
	</tr>
	<tr>
		<td class="fieldLabel"> Induction Confirmed:</td>
		<td class="fieldValue">$vacancy->induction_confirmed</td>
		<td class="fieldLabel"> Induction Date:</td>
		<td class="fieldValue">$vacancy->induction_date</td>
	</tr>
</table>
<table border="0" cellspacing="4" cellpadding="4">
	<tr>
		<td class="fieldlabel"> Expected Weekly Working Routine:</td>
		<td  colspan="3" width="500"   class="fieldValue">$vacancy->shift_pattern</td>
	</tr>
	<tr>
		<td class="fieldLabel">Job Description:</td>
		<td  colspan="3" width="500"   class="fieldValue">$vacancy->description</td>
	</tr>
	<tr>
		<td class="fieldLabel">Person Specification:</td>
		<td  colspan="3" width="500"    class="fieldValue">$vacancy->person_spec</td>
	</tr>
	<tr>
		<td class="fieldLabel">Qualifications Required:</td>
		<td  colspan="3" width="500"   class="fieldValue">$vacancy->required_quals</td>
	</tr>
	<tr>
		<td class="fieldLabel">Important Other Information:</td>
		<td  colspan="3" width="500"   class="fieldValue">$vacancy->misc</td>
	</tr>
	<tr>
		<td class="fieldLabel"> Possibility to complete a level 3<br> advanced apprenticeship:</td>
		<td class="fieldValue">$vacancy->to_level_3</td>
	</tr>
	<tr>
		<td class="fieldLabel"> Other (please state):</td>
		<td  colspan="3" width="500"   class="fieldValue">$vacancy->prospects</td>
	</tr>
	<tr>
		<td class="fieldLabel"> Additional Comments with dates/action plan:</td>
		<td  colspan="3" width="500"  class="fieldValue">$vacancy->comments</td>
	</tr>
</table>
HEREDOC;

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
}
?>