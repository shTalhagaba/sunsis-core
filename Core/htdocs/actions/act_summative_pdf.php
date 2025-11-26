<?php
class summative_pdf implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
        $course_id = DAO::getSingleValue($link, "select course_id from courses_tr where tr_id = '$tr_id'");
        $course = Course::loadFromDatabase($link, $course_id);

        $framework_id = $course->framework_id;
        $assessor_id = $training_record->assessor;
        $assessor = DAO::getSingleValue($link, "select concat(firstnames, ' ', surname) from users where id = '$assessor_id'");

        $html = "<table border='1' style='width: 100%;' cellspacing='0' cellpadding='5'>
        <thead>
        <tr>
            <th colspan='6' style='color: #000; background-color: #d2d6de !important'>Learner Details</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th>Learner Name:</th>
            <td>" . $training_record->firstnames . " " . $training_record->surname . "</td>
            <th>Programme:</th>
            <td>" . $course->title . "</td>
            <th>Coach:</th>
            <td>" . $assessor . "</td>
        </tr>
        <tr>
            <th>IQA Lead:</th>
            <td>&nbsp;</td>
            <th>Date Sampled:</th>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        </tbody></table><p><br></p>";

        $competencies = DAO::getResultset($link, "SELECT id, description FROM lookup_assessment_plan_log_mode WHERE framework_id = '$framework_id';", DAO::FETCH_ASSOC);
        $html.= '<table border="1" style="width: 100%;" cellspacing="0" cellpadding="5">';
        $html.= '<thead>';
        $html .= '<tr style="color: #000; background-color: #d2d6de !important">';
        $html .= '<th>Competency</th><th>Completed Criteria</th><th>Included</th><th>IQA Accept</th><th>IQA Reject</th><th>Summative RAG</th>';
        $html .= '<th>Recommendation Comments</th><th>Recommendation Type</th></th><th>Rejection Comments</th><th>Coach Rejection Actioned</th>';
        $html .= '<th>Coach Actioned Status</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html.= '<tbody>';
        foreach($competencies AS $competency)
        {
            $criterias = DAO::getResultset($link, "SELECT * FROM evidence_criteria WHERE course_id = '$course_id' AND competency = {$competency['id']};", DAO::FETCH_ASSOC);
            // $html .= '<tr>';
            // $html .= '<td rowspan="' . (sizeof($criterias)+1) . '">' . $competency['description'] . '</td></tr>';
            $criteria_ids = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(matrix) 
            FROM tr_projects 
            INNER JOIN courses_tr ON courses_tr.tr_id = tr_projects.tr_id 
            INNER JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
                AND matrix IS NOT NULL and completion_date is not null
            WHERE tr_projects.tr_id = '$tr_id';");
            $criteria_ids2 = explode(",",$criteria_ids);
        
            $summatives = DAO::getResultset($link, "
            SELECT 
            tr_id
            ,submission_id
            ,iqa_date
            ,competency_id
            ,CASE iqa_status WHEN 1 THEN \"Accepted\" WHEN 2 THEN \"Rejected\" END AS iqa_status
            ,recommendation_comments
            ,CASE recommendations_type WHEN 1 THEN \"Higher grades\" WHEN 2 THEN \"Strengthen evidence / knowledge\" END AS recommendation_type
            ,rejection_comments
            ,coach_recommendations
            ,case coach_actioned_status when 1 then \"Yes\" when 2 then \"Set as interview prep & manager approval\" End as coach_actioned_status
            ,iqa_accept
            ,iqa_reject
            ,(SELECT evidence_project.project FROM project_submissions 
            LEFT JOIN tr_projects ON tr_projects.id = project_submissions.project_id
            LEFT JOIN evidence_project ON evidence_project.id = tr_projects.project
            WHERE project_submissions.id = submissions_iqa.submission_id) as project
            ,(SELECT tr_projects.id FROM project_submissions 
            LEFT JOIN tr_projects ON tr_projects.id = project_submissions.project_id
            LEFT JOIN evidence_project ON evidence_project.id = tr_projects.project
            WHERE project_submissions.id = submissions_iqa.submission_id) as project_id
        
            FROM submissions_iqa WHERE tr_id = $tr_id AND submission_id IN (SELECT sub.id 
            FROM tr_projects 
            INNER JOIN courses_tr ON courses_tr.tr_id = tr_projects.tr_id 
            INNER JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
                AND matrix IS NOT NULL
            WHERE tr_projects.tr_id = '$tr_id');
        ", DAO::FETCH_ASSOC);
            foreach($criterias as $criteria)
            {
                $html .= '<tr>';
                $html .= '<td>' . $competency['description'] . '</td>';
                $html .= '<td>'. $criteria['criteria'] .'</td>';
                if(in_array($criteria['id'],$criteria_ids2))
                    $html .= '<td align="center"><img src="images/register/reg-tick.png" /></td>';
                else
                    $html .= '<td></td>';
                $empty = true;
        
                $summatives2 = [];
                foreach($summatives as $summative)
                {
                    $summatives2[$summative['competency_id']] = $summative;        
                }
        
                foreach($summatives2 as $summative)
                {
                    if($summative['competency_id']==$criteria['id'])
                    {
                        if($summative['iqa_accept']==1)
                            $html .= '<td><img src="images/register/reg-tick.png" /></td>';
                        else
                            $html .= '<td></td>';
        
                        if($summative['iqa_reject']==1)
                            $html .= '<td><img src="images/register/reg-tick.png" /></td>';
                        else
                            $html .= '<td></td>';
        
                        if($summative['recommendation_type']=="Higher grades")
                            $html .= '<td>Blue</td>';
                        elseif($summative['recommendation_type']=="Strengthen evidence / knowledge")
                            $html .= '<td>Amber</td>';
                        elseif($summative['iqa_status']=="Accepted")
                            $html .= '<td>Green</td>';
                        elseif($summative['iqa_status']=="Rejected")
                            $html .= '<td>Red</td>';
                        else
                            $html .= '<td></td>';
                        $html .= '<td>' . $summative['recommendation_comments'] . '</td>';
                        $html .= '<td>' . $summative['recommendation_type'] . '</td>';
                        $html .= '<td>' . $summative['rejection_comments'] . '</td>';
                        if($summative['coach_recommendations']=="1")
                            $html .= '<td><img src="images/register/reg-tick.png" /></td>';
                        else
                            $html .= '<td></td>';
                        $html .= '<td>' . $summative['coach_actioned_status'] . '</td>';

                        $empty = false;
                        break;
                    }
                }
                if($empty)
                    $html .= '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';
        
                $html .= '</tr>';
            }
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        $html .=  '<br>';


        include("./MPDF57/mpdf.php");

        $mpdf=new mPDF('','Legal-L','','',15,15,57,16,9,9); 
        $mpdf->SetMargins(15, 15, 36);
        $mpdf->SetDisplayMode('fullpage');

        $mpdf->WriteHTML($html);
        $filename = "summative.pdf";
        $mpdf->Output($filename, 'D');
        exit;
    }
}
?>