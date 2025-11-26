<?php
class OtjSheet extends Entity
{
    public static function exportToPdf(PDO $link, $tr_id)
    {
        $tr = TrainingRecord::loadFromDatabase($link, $tr_id); /* @var $tr TrainingRecord */
        $ob_learner = $tr->getObLearnerRecord($link); /* @var $ob_learner OnboardingLearner */
        $employer = Employer::loadFromDatabase($link, $tr->employer_id);

        $mpdf = new \Mpdf\Mpdf(['format' => 'Legal-L', 'default_font_size' => 10]);
        $mpdf->setAutoBottomMargin = 'stretch';

        $sunesis_stamp = md5('ghost' . date('d/m/Y') . $tr->id);
        $sunesis_stamp = substr($sunesis_stamp, 0, 10);
        $date = date('d/m/Y H:i:s');
        $footer = <<<HEREDOC
		<div>
			<table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
				<tr>
					<td width = "30%" align="left" style="font-size: 10px">{$date}</td>
					<td width = "35%" align="left" style="font-size: 10px">OTJ Sheet</td>
					<td width = "35%" align="right" style="font-size: 10px">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
				</tr>
			</table>
		</div>
HEREDOC;

        $mpdf->SetHTMLFooter($footer);

        //Beginning Buffer to save PHP variables and HTML tags
        ob_start();

        $start_date = Date::toShort($tr->practical_period_start_date);

        if($tr->otj_overwritten != '')
        {
            $planned_otj_hours = $tr->otj_overwritten;
        } 
        else
        {
            $planned_otj_hours = $tr->contracted_hours_per_week >= 30 ? $tr->off_the_job_hours_based_on_duration : $tr->part_time_otj_hours;
        }

        $otj_details = '';
        $otj_details .= 'Contracted hours per week: ' . $tr->contracted_hours_per_week . '<br>';
        $otj_details .= 'Weeks to be worked per year: ' . $tr->weeks_to_be_worked_per_year . '<br>';
        $otj_details .= 'Total contracted hours per year: ' . $tr->total_contracted_hours_per_year . '<br>';
        if ($tr->contracted_hours_per_week >= 30) {
            $otj_details .= 'Length of Programme (Practical Period): ' . $tr->duration_practical_period . ' months<br>';
            $otj_details .= 'Total Contracted Hours - Full Apprenticeship: ' . $tr->total_contracted_hours_full_apprenticeship . ' hours<br>';
            // $otj_details .= 'Minimum 20% OTJ Training: ' . $tr->off_the_job_hours_based_on_duration . ' hours<br>';
            $otj_details .= 'Off-the-job Hours: ' . $planned_otj_hours . ' hours<br>';
            $total_weeks = round($tr->total_contracted_hours_full_apprenticeship / $tr->contracted_hours_per_week, 2);
        } else {
            $otj_details .= '<tr><th>Minimum Duration (part time): ' . $tr->minimum_duration_part_time . ' months<br>';
            $otj_details .= '<tr><th>Total Contracted Hours - Full Apprenticeship: ' . $tr->part_time_total_contracted_hours_full_apprenticeship . ' hours<br>';
            // $otj_details .= '<tr class="bg-light-blue-gradient"><th>Minimum 20% OTJ Training: ' . $tr->part_time_otj_hours . ' hours<br>';
            $otj_details .= '<tr class="bg-light-blue-gradient"><th>Off-the-job Hours: ' . $planned_otj_hours . ' hours<br>';
            $total_weeks = round($tr->part_time_total_contracted_hours_full_apprenticeship / $tr->contracted_hours_per_week, 2);
        }

        //$temp = ceil($total_weeks*$tr->contracted_hours_per_week*0.2);
        $temp = round($total_weeks * 6);

        $first_page_empty_rows = '';
        for ($i = 1; $i <= 3; $i++) {
            $first_page_empty_rows .= '<tr><td><p><br>&nbsp;<br>&nbsp;<br><br>&nbsp;</p></td><td></td><td></td><td></td><td></td><td style="background-color: cyan"></td><td></td><td></td><td></td></tr>';
        }
        $second_page_empty_rows = '';
        for ($i = 1; $i <= 7; $i++) {
            $second_page_empty_rows .= '<tr><td><p><br>&nbsp;<br>&nbsp;<br><br>&nbsp;</p></td><td></td><td></td><td></td><td></td><td style="background-color: cyan"></td><td></td><td></td><td></td></tr>';
        }

        $html = <<<HTML
<div style="float: left; width: 50%;margin-bottom: 0pt; ">
<h4>Off The Job Training Record</h4>

<p>Together with your employer, use the table below to record evidence of any occasions you have either spent shadowing, 
    online training, preparing for professional discussion, mentoring, writing assignments, practical tasks or any other 
    tasks set by your Assessor, <u>during your contracted hours</u>. Record how many hours you have spent and ensure this is signed by all parties.
</p>

</div>        
<div style="text-align: center; ">
    <table border="1" style="width: 100%;" cellpadding="6" style="font-size: 9pt;">
        <tr>
            <th>Learner Name</th><td>{$ob_learner->firstnames} {$ob_learner->surname}</td>
            <th>Start Date</th><td>{$start_date}</td>
        </tr>
        <tr>
            <th>Employer Name</th><td colspan="3">{$employer->legal_name}</td>
        </tr>
        <tr>
            <th>Min OTJT Hours Required</th>
            <td colspan="3">
                {$otj_details}
            </td>
        </tr>	
	    <tr><td colspan="4">Hours MUST NOT include Maths or English Study</td></tr>
    </table>
</div>
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6" style="font-size: 9pt;">
        <tr>
            <th rowspan="2">Dates<br>(review<br>monthly)</th>
            <th rowspan="2">
                Evidence of OTJT
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </th>
            <th rowspan="2">Hours </th>
            <th rowspan="2">+ Monthly<br>College<br>Hours </th>
            <th rowspan="2">= Total<br>monthly<br>hours  </th>
            <th rowspan="2" style="background-color: cyan">Overall<br>Hours<br>(Cumulative)</th>           
            <th colspan="3">I confirm that this is a true reflection of OTJT</th>
        </tr>
        <tr>
            <th>Learner signature </th>
            <th>Assessor signature </th>
            <th>Employer name & signature </th>
        </tr>
        {$first_page_empty_rows}
    </table>
</div> 

HTML;
        $mpdf->WriteHTML($html);
        $mpdf->AddPage('L');

        $html = <<<HTML
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6" style="font-size: 9pt;">
        <tr>
            <th rowspan="2">Dates<br>(review<br>monthly)</th>
            <th rowspan="2">
                Evidence of OTJT
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </th>
            <th rowspan="2">Hours </th>
            <th rowspan="2">+ Monthly<br>College<br>Hours </th>
            <th rowspan="2">= Total<br>monthly<br>hours  </th>
            <th rowspan="2" style="background-color: cyan">Overall<br>Hours<br>(Cumulative)</th>           
            <th colspan="3">I confirm that this is a true reflection of OTJT</th>
        </tr>
        <tr>
            <th>Learner signature </th>
            <th>Assessor signature </th>
            <th>Employer name & signature </th>
        </tr>
        {$second_page_empty_rows}
    </table>
</div> 

HTML;

        $mpdf->WriteHTML($html);
        $mpdf->AddPage('L');

        $html = <<<HTML
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6" style="font-size: 9pt;">
        <tr>
            <th rowspan="2">Dates<br>(review<br>monthly)</th>
            <th rowspan="2">
                Evidence of OTJT
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </th>
            <th rowspan="2">Hours </th>
            <th rowspan="2">+ Monthly<br>College<br>Hours </th>
            <th rowspan="2">= Total<br>monthly<br>hours  </th>
            <th rowspan="2" style="background-color: cyan">Overall<br>Hours<br>(Cumulative)</th>           
            <th colspan="3">I confirm that this is a true reflection of OTJT</th>
        </tr>
        <tr>
            <th>Learner signature </th>
            <th>Assessor signature </th>
            <th>Employer name & signature </th>
        </tr>
        {$second_page_empty_rows}
    </table>
</div> 

HTML;

        $mpdf->WriteHTML($html);
        $mpdf->AddPage('L');

        $html = <<<HTML
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6" style="font-size: 9pt;">
        <tr>
            <th rowspan="2">Dates<br>(review<br>monthly)</th>
            <th rowspan="2">
                Evidence of OTJT
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </th>
            <th rowspan="2">Hours </th>
            <th rowspan="2">+ Monthly<br>College<br>Hours </th>
            <th rowspan="2">= Total<br>monthly<br>hours  </th>
            <th rowspan="2" style="background-color: cyan">Overall<br>Hours<br>(Cumulative)</th>           
            <th colspan="3">I confirm that this is a true reflection of OTJT</th>
        </tr>
        <tr>
            <th>Learner signature </th>
            <th>Assessor signature </th>
            <th>Employer name & signature </th>
        </tr>
        {$second_page_empty_rows}
    </table>
</div> 

HTML;

        $mpdf->WriteHTML($html);

        //$mpdf->Output('OTJT Sheet.pdf', 'D');

        $otj_file = $tr->getDirectoryPath() . '/OTJT Sheet.pdf';
        $mpdf->Output($otj_file, 'F');
    }
}
