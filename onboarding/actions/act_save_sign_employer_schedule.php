<?php
class save_sign_employer_schedule implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $employer_id = isset($_POST['employer_id']) ? $_POST['employer_id'] : '';
        $tr_id = isset($_POST['tr_id']) ? $_POST['tr_id'] : '';
        $key = isset($_POST['key']) ? $_POST['key'] : '';

        // if(trim($id) != '' && trim($employer_id) != '' && trim($tr_id) != '' && trim($key) != '')
        // {
        //     if(!OnboardingHelper::isValidEmployerScheduleKey($link, $id, $employer_id, $tr_id, $key))
        //     {
        //         OnboardingHelper::generateErrorPage($link);
        //         exit;
        //     }
        // }
        // else
        // {
        //     OnboardingHelper::generateErrorPage($link);
        //     exit;
        // }

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
	$schedule = EmployerSchedule1::loadFromDatabase($link, $id);

        // $schedule = $tr->getEmployerAgreementSchedule1($link);
        // if($schedule->id != $id)
        // {
        //     throw new Exception("Invalid information.");
        // }
        $detail = json_decode($schedule->detail);
        $detail->section12 = isset($_REQUEST['section12']) ? $_REQUEST['section12'] : '';
        $detail->section15radio = isset($_REQUEST['section15radio']) ? $_REQUEST['section15radio'] : '';
        $detail->section15 = isset($_REQUEST['section15']) ? $_REQUEST['section15'] : '';
        $detail->emp_sign_name = isset($_REQUEST['emp_sign_name']) ? $_REQUEST['emp_sign_name'] : '';
        $detail->emp_sign = isset($_REQUEST['emp_sign']) ? $_REQUEST['emp_sign'] : '';
        $detail->emp_sign_date = isset($_REQUEST['emp_sign_date']) ? $_REQUEST['emp_sign_date'] : '';
        $detail->upfront_payment = isset($_REQUEST['upfront_payment']) ? $_REQUEST['upfront_payment'] : '';
        $detail->monthly_payment = isset($_REQUEST['monthly_payment']) ? $_REQUEST['monthly_payment'] : '';
	if(!isset($_POST['wm_auth']))
        {
            $detail->wm_auth = 0;
        }
	$detail->practical_period_start_date = isset($_REQUEST['practical_period_start_date']) ? $_REQUEST['practical_period_start_date'] : $detail->practical_period_start_date;
        $detail->practical_period_end_date = isset($_REQUEST['practical_period_end_date']) ? $_REQUEST['practical_period_end_date'] : $detail->practical_period_end_date;
        $detail->planned_epa_date = isset($_REQUEST['planned_epa_date']) ? $_REQUEST['planned_epa_date'] : $detail->planned_epa_date;
        $detail->contracted_hours_per_week = isset($_REQUEST['contracted_hours_per_week']) ? $_REQUEST['contracted_hours_per_week'] : $detail->contracted_hours_per_week;
        $detail->weeks_to_be_worked_per_year = isset($_REQUEST['weeks_to_be_worked_per_year']) ? $_REQUEST['weeks_to_be_worked_per_year'] : $detail->weeks_to_be_worked_per_year;
        $detail->contact_name = isset($_REQUEST['contact_name']) ? $_REQUEST['contact_name'] : $detail->contact_name;
        $detail->contact_telephone = isset($_REQUEST['contact_telephone']) ? $_REQUEST['contact_telephone'] : $detail->contact_telephone;
        $detail->contact_email = isset($_REQUEST['contact_email']) ? $_REQUEST['contact_email'] : $detail->contact_email;

        $schedule->detail = json_encode($detail);
        $schedule->emp_sign_name = isset($_REQUEST['emp_sign_name']) ? $_REQUEST['emp_sign_name'] : '';
        $schedule->emp_sign = isset($_REQUEST['emp_sign']) ? $_REQUEST['emp_sign'] : '';
        $schedule->emp_sign_date = isset($_REQUEST['emp_sign_date']) ? $_REQUEST['emp_sign_date'] : '';

	$tr->practical_period_start_date = ( isset($_POST['practical_period_start_date']) && $_POST['practical_period_start_date'] != '' ) ? $_POST['practical_period_start_date'] : $tr->practical_period_start_date;
        $tr->practical_period_end_date = ( isset($_POST['practical_period_end_date']) && $_POST['practical_period_end_date'] != '' ) ? $_POST['practical_period_end_date'] : $tr->practical_period_end_date;
        $tr->planned_epa_date = ( isset($_POST['planned_epa_date']) && $_POST['planned_epa_date'] != '' ) ? $_POST['planned_epa_date'] : $tr->planned_epa_date;
        $tr->contracted_hours_per_week = ( isset($_POST['contracted_hours_per_week']) && $_POST['contracted_hours_per_week'] != '' ) ? $_POST['contracted_hours_per_week'] : $tr->contracted_hours_per_week;
        $tr->weeks_to_be_worked_per_year = ( isset($_POST['weeks_to_be_worked_per_year']) && $_POST['weeks_to_be_worked_per_year'] != '' ) ? $_POST['weeks_to_be_worked_per_year'] : $tr->weeks_to_be_worked_per_year;

	$tr->total_contracted_hours_per_year = ceil( floatval($tr->contracted_hours_per_week) * floatval($tr->weeks_to_be_worked_per_year) );
        $tr->total_contracted_hours_full_apprenticeship = ceil( (floatval($tr->total_contracted_hours_per_year)/12) * floatval($tr->duration_practical_period) );

        $tr->fs_maths_opt_in = isset($_POST['fs_maths_opt_in']) ? $_POST['fs_maths_opt_in'] : '';
        $tr->fs_eng_opt_in = isset($_POST['fs_eng_opt_in']) ? $_POST['fs_eng_opt_in'] : '';
        
        $tr->save($link);

        $schedule->save($link);

//        EmployerSchedule1::generateCompletionPage($link, $tr->id);

        $_POST = null;
        unset($_POST);

        http_redirect('do.php?_action=employer_schedule_completed&k='.md5('sunesis_form_completed_for_'.$tr->id));
    }
}
?>