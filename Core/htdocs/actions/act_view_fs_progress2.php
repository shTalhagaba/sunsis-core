<?php
class view_fs_progress2 implements IAction
{
    public function execute(PDO $link)
    {
        $export = isset($_REQUEST['export'])?$_REQUEST['export']:'';

        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_fs_progress2", "View FS Progress Report 2");

        $view = ViewFSProgress2::getInstance($link);
        $view->refresh($link, $_REQUEST);

        if($export==1)
        {
            $this->exportRecordsToExcel($link, $view);
        }

        require_once('tpl_view_fs_progress2.php');
    }

    private function exportRecordsToExcel(PDO $link, ViewFSProgress2 $view)
    {
        set_time_limit(0);
        $statement = $view->getSQLStatement();
        $statement->removeClause('limit');//$statement->setClause()
        $st = $link->query($statement->__toString());
        if($st)
        {
            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename=ViewFSProgress.csv');
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
            {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }

            $report_type = $view->getFilterValue('filter_report_type');

            if($report_type==1)
            {
                $line = '';
                $line.= 'Programme,Firstnames,Surname,Induction Date,Planned End Date,Days on Programme,FS Exemption Status,English Evidence Seen,Maths Evidence Seen,Exemption Evidence,Achieved,FS Achieved Date,Required to complete,Maths Overall Status,Maths Course Date,Maths Comments,Maths Exam Date,English Overall Status,English Course Date,English Comments,Reading Exam Date,Writing Exam Date,SLC Date,FS Coach,Walled Garden Enrolment Number,Maths Forecasted End Date,English Forecasted End Date, Learner Risk, Risk Comments';

                echo $line . "\r\n";
                while($row = $st->fetch(PDO::FETCH_ASSOC))
                {
                    $line = str_replace(',', ' ', $row['programme']) . ',';
                    $line .= str_replace(',', ' ', $row['firstnames']) . ',';
                    $line .= str_replace(',', ' ', $row['surname']) . ',';
                    $line .= str_replace(',', ' ', $row['induction_date']) . ',';
			        $line .= str_replace(',', ' ', $row['planned_end_date']) . ',';
			        $line .= str_replace(',', ' ', $row['days_on_programme']) . ',';
			        $line .= str_replace(',', ' ', $row['fs_required']) . ',';
			        $line .= str_replace(',', ' ', $row['english_evidence_seen']) . ',';
			        $line .= str_replace(',', ' ', $row['maths_evidence_seen']) . ',';
                    $line .= str_replace(',', ' ', str_replace("\r\n"," ",$row['general_comments'])) . ',';
			        $line .= str_replace(',', ' ', $row['achieved']) . ',';
			        $line .= str_replace(',', ' ', $row['achieved_timestamp']) . ',';
                    //$line .= str_replace(',', ' ', $row['allocated_tutor']) . ',';
                    $line .= str_replace(',', ' ', $row['required_to_complete']) . ',';
                    $line .= str_replace(',', ' ', $row['maths_overall_status']) . ',';
                    $line .= str_replace(',', ' ', $row['maths_course_date']) . ',';
                    $line .= str_replace(',', ' ', str_replace("\r\n"," ",$row['maths_comments'])) . ',';
                    $line .= str_replace(',', ' ', $row['maths_exam_date']) . ',';
                    $line .= str_replace(',', ' ', $row['english_overall_status']) . ',';
                    $line .= str_replace(',', ' ', $row['english_course_date']) . ',';
                    $line .= str_replace(',', ' ', str_replace("\r\n"," ",$row['english_course_comments'])) . ',';
                    $line .= str_replace(',', ' ', $row['reading_exam_date']) . ',';
                    $line .= str_replace(',', ' ', $row['writing_exam_date']) . ',';
                    $line .= str_replace(',', ' ', $row['slc_course_date']) . ',';
                    $line .= str_replace(',', ' ', $row['fs_coach']) . ',';
                    $line .= str_replace(',', ' ', $row['walled_garden_enrolment_number']) . ',';
                    $line .= str_replace(',', ' ', $row['maths_forecasted_end_date']) . ',';
                    $line .= str_replace(',', ' ', $row['english_forecasted_end_date']) . ',';
                    $line .= str_replace(',', ' ', $row['learner_risk']) . ',';
                    $line .= str_replace(',', ' ', $row['risk_comments']) . ',';
                    
                    echo $line . "\r\n";
                }
            }

            if($report_type==2)
            {
                $line = '';
                $line .= 'Programme,Firstnames,Surname,Tutor,Planned End Date,Required to complete,Maths Mock Status,Maths Mock NDA Date,Maths Mock Comments,English Mock Status,English Mock NDA Date,English Mock Comments';
                echo $line . "\r\n";
                while($row = $st->fetch(PDO::FETCH_ASSOC))
                {
                    $line = str_replace(',', ' ', $row['programme']) . ',';
                    $line .= str_replace(',', ' ', $row['firstnames']) . ',';
                    $line .= str_replace(',', ' ', $row['surname']) . ',';
                    $line .= str_replace(',', ' ', $row['allocated_tutor']) . ',';
			$line .= str_replace(',', ' ', $row['planned_end_date']) . ',';
                    $line .= str_replace(',', ' ', $row['required_to_complete']) . ',';
                    $line .= str_replace(',', ' ', $row['maths_mock_status']) . ',';
                    $line .= str_replace(',', ' ', $row['maths_mock_nda_date']) . ',';
                    $line .= str_replace(',', ' ', str_replace("\r\n"," ",$row['maths_mock_comments'])) . ',';
                    $line .= str_replace(',', ' ', $row['english_mock_status']) . ',';
                    $line .= str_replace(',', ' ', $row['english_mock_nda_date']) . ',';
                    $line .= str_replace(',', ' ', str_replace("\r\n"," ",$row['english_mock_comments'])) . ',';
                    echo $line . "\r\n";
                }
            }

            if($report_type==3)
            {
                $line = '';
                $line .= 'Programme,Firstnames,Surname,Induction Date,Planned End Date,Tutor,Maths Overall Status,Maths Achieved Date,English Overall Status,English Achieved Date';

                echo $line . "\r\n";
                while($row = $st->fetch(PDO::FETCH_ASSOC))
                {
                    $line = str_replace(',', ' ', $row['programme']) . ',';
                    $line .= str_replace(',', ' ', $row['firstnames']) . ',';
                    $line .= str_replace(',', ' ', $row['surname']) . ',';
                    $line .= str_replace(',', ' ', $row['induction_date']) . ',';
			$line .= str_replace(',', ' ', $row['planned_end_date']) . ',';
                    $line .= str_replace(',', ' ', $row['allocated_tutor']) . ',';
                    $line .= str_replace(',', ' ', $row['maths_overall_status']) . ',';
                    $line .= str_replace(',', ' ', $row['maths_achieved_date']) . ',';
                    $line .= str_replace(',', ' ', $row['english_overall_status']) . ',';
                    $line .= str_replace(',', ' ', $row['english_achieved_date']) . ',';
                    echo $line . "\r\n";
                }
            }
            if($report_type==4)
            {
                $line = '';
                $line .= 'Programme,Firstnames,Surname,Induction Date,Planned End Date,Tutor,Required to complete,Maths Overall Status,Maths Course Date,Maths Exam Date,Maths Exam Result,Maths Exam Score,Maths RFT,Maths Achieved Date,Maths Comments,English Overall Status,English Course Date,English Course Status,English Achieved Date,English Reading Status,Reading Exam Date,Reading Exam Result,Reading Exam Score,Reading RFT,Reading Exam Result Recieved Date,English Writing Status,Writing Exam Date,Writing Exam Result,Writing Exam Score,Writing RFT,Writing Exam Result Recieved Date,SLC Status,SLC Date Exam Result Recieved,SLC Course Date,SLC RFT';
                echo $line . "\r\n";
                while($row = $st->fetch(PDO::FETCH_ASSOC))
                {
                    $line = str_replace(',', ' ', $row['programme']) . ',';
                    $line .= str_replace(',', ' ', $row['firstnames']) . ',';
                    $line .= str_replace(',', ' ', $row['surname']) . ',';
                    $line .= str_replace(',', ' ', $row['induction_date']) . ',';
			$line .= str_replace(',', ' ', $row['planned_end_date']) . ',';
                    $line .= str_replace(',', ' ', $row['allocated_tutor']) . ',';
                    $line .= str_replace(',', ' ', $row['required_to_complete']) . ',';
                    $line .= str_replace(',', ' ', $row['maths_overall_status']) . ',';
                    $line .= str_replace(',', ' ', $row['maths_course_date']) . ',';
                    $line .= str_replace(',', ' ', $row['maths_exam_date']) . ',';
                    $line .= str_replace(',', ' ', $row['maths_exam_result']) . ',';
                    $line .= str_replace(',', ' ', $row['maths_exam_score']) . ',';
                    $line .= str_replace(',', ' ', $row['maths_rft']) . ',';
                    $line .= str_replace(',', ' ', $row['maths_achieved_date']) . ',';
                    $line .= str_replace(',', ' ', str_replace("\r\n"," ",$row['maths_comments'])) . ',';
                    $line .= str_replace(',', ' ', $row['english_overall_status']) . ',';
                    $line .= str_replace(',', ' ', $row['english_course_date']) . ',';
                    $line .= str_replace(',', ' ', $row['english_course_status']) . ',';
                    $line .= str_replace(',', ' ', $row['english_achieved_date']) . ',';
                    $line .= str_replace(',', ' ', $row['english_reading_status']) . ',';
                    $line .= str_replace(',', ' ', $row['reading_exam_date']) . ',';
                    $line .= str_replace(',', ' ', $row['reading_exam_result']) . ',';
                    $line .= str_replace(',', ' ', $row['reading_exam_score']) . ',';
                    $line .= str_replace(',', ' ', $row['reading_rft']) . ',';
                    $line .= str_replace(',', ' ', $row['reading_exam_result_received_date']) . ',';
                    $line .= str_replace(',', ' ', $row['english_writing_status']) . ',';
                    $line .= str_replace(',', ' ', $row['writing_exam_date']) . ',';
                    $line .= str_replace(',', ' ', $row['writing_exam_result']) . ',';
                    $line .= str_replace(',', ' ', $row['writing_exam_score']) . ',';
                    $line .= str_replace(',', ' ', $row['writing_rft']) . ',';
                    $line .= str_replace(',', ' ', $row['writing_exam_result_received_date']) . ',';
                    $line .= str_replace(',', ' ', $row['slc_status']) . ',';
                    $line .= str_replace(',', ' ', $row['slc_date_exam_result_received']) . ',';
                    $line .= str_replace(',', ' ', $row['slc_course_date']) . ',';
                    $line .= str_replace(',', ' ', $row['slc_rft']) . ',';
                    echo $line . "\r\n";
                }
            }
            if($report_type==5)
            {
                $line = '';
                $line .= 'Programme,Firstnames,Surname,Induction Date,Planned End Date,Allocated Tutor,Coordinator,Learning Mentor,Days On Programme,Target Completion Date,Required to Complete,FS Exemption Status,English Exemption Evidence Seen,Maths Exemption Evidence Seen,Comments';
                echo $line . "\r\n";
                while($row = $st->fetch(PDO::FETCH_ASSOC))
                {
                    $learner_info = FSProgress::getLearnerInfo($link, $row['training_record_id']);
                    $end_date = ($learner_info[0][1]!="")?$learner_info[0][1]:date("d/m/Y");
                    $info = Date::dateDiffInfo($learner_info[0][0], $end_date);
                    $completion_date = new Date($learner_info[0][0]);
                    $completion_date->addMonths(6);
                    $days_on_programme = isset($info['days']) ? $info['days'] : '';                

                    $line = str_replace(',', ' ', $row['programme']) . ',';
                    $line .= str_replace(',', ' ', $row['firstnames']) . ',';
                    $line .= str_replace(',', ' ', $row['surname']) . ',';
                    $line .= str_replace(',', ' ', $row['induction_date']) . ',';
			$line .= str_replace(',', ' ', $row['planned_end_date']) . ',';
                    $line .= str_replace(',', ' ', $row['allocated_tutor']) . ',';
                    $line .= str_replace(',', ' ', $row['coordinator']) . ',';
                    $line .= str_replace(',', ' ', $row['assessor']) . ',';
                    $line .= str_replace(',', ' ', $days_on_programme) . ',';
                    $line .= str_replace(',', ' ', $completion_date) . ',';
                    $line .= str_replace(',', ' ', $row['required_to_complete']) . ',';
                    $line .= str_replace(',', ' ', $row['fs_required']) . ',';
                    $line .= str_replace(',', ' ', $row['english_evidence']) . ',';
                    $line .= str_replace(',', ' ', $row['maths_evidence']) . ',';
                   $line .= str_replace(',', ' ', str_replace("\r\n"," ",$row['general_comments'])) . ',';
                    echo $line . "\r\n";
                }
            }
            if($report_type==6)
            {
                $line = '';
                $line .= 'Programme,Firstnames,Surname,Induction Date,Planned End Date,Maths Test Status,Maths Test Date,English Reading Test Status,English Reading Test Date,English Writing Test Status,English Writing Test Date,SLC Status,SLC Date';
                echo $line . "\r\n";
                while($row = $st->fetch(PDO::FETCH_ASSOC))
                {
                    $line = str_replace(',', ' ', $row['programme']) . ',';
                    $line .= str_replace(',', ' ', $row['firstnames']) . ',';
                    $line .= str_replace(',', ' ', $row['surname']) . ',';
                    $line .= str_replace(',', ' ', $row['induction_date']) . ',';
			        $line .= str_replace(',', ' ', $row['planned_end_date']) . ',';
			        $line .= str_replace(',', ' ', $row['maths_test_status']) . ',';
			        $line .= str_replace(',', ' ', $row['maths_exam_date']) . ',';
			        $line .= str_replace(',', ' ', $row['english_overall_status_reading']) . ',';
			        $line .= str_replace(',', ' ', $row['reading_exam_date']) . ',';
			        $line .= str_replace(',', ' ', $row['english_overall_status_writing']) . ',';
			        $line .= str_replace(',', ' ', $row['writing_exam_date']) . ',';
			        $line .= str_replace(',', ' ', $row['slc_status']) . ',';
			        $line .= str_replace(',', ' ', $row['slc_date']) . ',';

                    echo $line . "\r\n";
                }
            }


        }

        exit;
    }
}
?>