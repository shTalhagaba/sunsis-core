<?php
class read_training implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        if ($id == '') {
            throw new Exception("Missing querystring argument: id");
        }

        $tr = TrainingRecord::loadFromDatabase($link, $id);
        if (is_null($tr)) {
            throw new Exception("Invalid id");
        }

        $_SESSION['bc']->add($link, "do.php?_action=read_training&id={$id}", "View Training");

        $ob_learner = $tr->getObLearnerRecord($link);
        if (DB_NAME == "am_ela") {
            if ($_SESSION['user']->learners_caseload == 0) {
                // do nothing
            } elseif ($_SESSION['user']->learners_caseload != $ob_learner->caseload_org_id) {
                throw new UnauthorizedException("You are not authorised to view this record.");
            }
        }

        $gender_description = DAO::getSingleValue($link, "SELECT description FROM lookup_gender WHERE id='{$ob_learner->gender}';");

        $employer = Organisation::loadFromDatabase($link, $tr->employer_id);
        $location = Location::loadFromDatabase($link, $tr->employer_location_id);
        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);
        $provider_location = Location::loadFromDatabase($link, $tr->provider_location_id);
        $subcontractor = null;
        $subcontractor_location = null;
        if ($tr->subcontractor_id != '') {
            $subcontractor = Organisation::loadFromDatabase($link, $tr->subcontractor_id);
            $subcontractor_location = Location::loadFromDatabase($link, $tr->subcontractor_location_id);
        }

        $framework = Framework::loadFromDatabase($link, $tr->framework_id);

        $skills_analysis = $tr->getSkillsAnalysis($link);
        $schedule = $tr->getEmployerAgreementSchedule1($link);

        $employerId = $employer ? $employer->id : '';
        $primary_contact_email = $location ? $location->contact_email : '';
        if ($primary_contact_email == '') {
            $primary_contact_email_sql = <<<SQL
SELECT
  organisation_contacts.`contact_email`
FROM
  organisation_contacts
WHERE organisation_contacts.`org_id` = '{$employerId}'
  AND organisation_contacts.`job_role` = 99
  AND organisation_contacts.`contact_email` IS NOT NULL
ORDER BY organisation_contacts.`contact_id` DESC
LIMIT 1;
SQL;
            $primary_contact_email = DAO::getSingleValue($link, $primary_contact_email_sql);
        }

        $tr->generateSignatureImages($link);

        // generate schedule 1 pdf if not done already
        $schedule_ids = DAO::getSingleColumn($link, "SELECT id FROM employer_agreement_schedules WHERE tr_id = '{$tr->id}' AND emp_sign IS NOT NULL AND tp_sign IS NOT NULL;");
        foreach ($schedule_ids as $_sch_id) {
            $_sch = EmployerSchedule1::loadFromDatabase($link, $_sch_id);
            PdfHelper::initialContractPdf($link, $tr, $_sch);
        }

        // generate employer app agreement pdf if not done already
        //$tr->generateEmployerAppAgreementPdf($link);
        PdfHelper::apprenticeshipAgreementPdf($link, $tr);
        if (DB_NAME == "am_ela" && $tr->id == 74) {
            //PdfHelper::evidenceOfEmploymentPdf($link, $tr);
        }

        // generate employer app agreement pdf if not done already
        PdfHelper::skillsScanAgreementPdf($link, $tr);

        // generate learning agreement pdf if not done already
        // $tr->generateLearningAgreementPdf($link);

        // generate commitment statement pdf if not done already
        // $tr->generateCommitmentStatementPdf($link);
        if ($framework->fund_model != Framework::FUNDING_STREAM_99) {
            PdfHelper::commitmentStatementPdf($link, $tr);
        }

        if (DB_NAME == "am_ela") {
            PdfHelper::preIagFormPdf($link, $tr);
            PdfHelper::learningStylesAssessmentPdf($link, $tr);
        }

        if (in_array(DB_NAME, ["am_eet", "am_puzzled"]) && $tr->isNonApp($link)) {
            PdfHelper::enrolmentFormPdf($link, $tr);
        }
        if ($framework->fund_model == Framework::FUNDING_STREAM_99) {
            PdfHelper::enrolmentFormPdf($link, $tr);
        }
        // generate first learning activity pdf if not done already
        // $tr->generateFirstLearningActivityPdf($link);
        PdfHelper::writingAssessmentPdf($link, $tr);

        $trainer_work_email = '';
        if ($tr->trainers != '') {
            $trainer_work_email = DAO::getSingleValue($link, "SELECT work_email FROM users WHERE users.id IN ($tr->trainers) LIMIT 1");
        }

        if (!is_null($tr->generate_pdfs))
            DAO::execute($link, "UPDATE ob_tr SET ob_tr.generate_pdfs = NULL WHERE ob_tr.id = '{$tr->id}'");

        $dl_hours_ok = true;
        if (!is_null($skills_analysis)) {
            if ($skills_analysis->learner_sign == '') {
                $chk_dl = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ob_learner_ksb WHERE tr_id = '{$tr->id}' AND del_hours = '0' OR del_hours = ''");
                if ($chk_dl > 0)
                    $dl_hours_ok = false;
            }
        }

        $tab = isset($_SESSION['training_read_screen_tab']) ? $_SESSION['training_read_screen_tab'] : 'tab_tr_details';

        $initial_contract_label = '<span class="label label-danger"><i class="fa fa-close"></i> Initial Contract</span>';
        if (isset($schedule->tp_sign) && $schedule->tp_sign != '') {
            $initial_contract_label = $schedule->emp_sign == '' ? '<span class="label label-warning"><i class="fa fa-check"></i> Initial Contract</span>' : '<span class="label label-success"><i class="fa fa-check"></i> Initial Contract</span>';
        }
        $pre_iag_label = '';
        if (in_array(DB_NAME, ["am_ela", "am_eet", "am_demo", "am_puzzled", "am_am", "am_crackerjack"]) && in_array($framework->fund_model, [Framework::FUNDING_STREAM_APP, Framework::FUNDING_STREAM_99])) {
            $pre_iag_label = '<span class="label label-danger"><i class="fa fa-close"></i> Pre-IAG Form</span>';
            $ob_learner_pre_iag_form = DAO::getObject($link, "SELECT * FROM ob_learner_pre_iag_form WHERE tr_id = '{$tr->id}'");
            if (isset($ob_learner_pre_iag_form->learner_sign) && $ob_learner_pre_iag_form->learner_sign != '') {
                $pre_iag_label = $ob_learner_pre_iag_form->provider_sign == '' ? '<span class="label label-warning"><i class="fa fa-check"></i> Pre-IAG Form</span>' : '<span class="label label-success"><i class="fa fa-check"></i> Pre-IAG Form</span>';
            }
        }
        $writing_assessment_label = '<span class="label label-danger"><i class="fa fa-close"></i> Writing Assessment</span>';
        $writing_assessment_form = DAO::getObject($link, "SELECT * FROM ob_learner_writing_assessment WHERE tr_id = '{$tr->id}'");
        if (isset($writing_assessment_form->learner_sign) && $writing_assessment_form->learner_sign != '') {
            $writing_assessment_label = $writing_assessment_form->provider_sign == '' ? '<span class="label label-warning"><i class="fa fa-check"></i> Writing Assessment</span>' : '<span class="label label-success"><i class="fa fa-check"></i> Writing Assessment</span>';
        }
        $learn_styles_label = '';
        if (in_array(DB_NAME, ["am_ela", "am_eet", "am_demo", "am_puzzled"])) {
            $learn_styles_label = '<span class="label label-danger"><i class="fa fa-close"></i> Learn Styles Assessment</span>';
            $learn_styles_form = DAO::getObject($link, "SELECT * FROM ob_learner_learning_style WHERE tr_id = '{$tr->id}'");
            if (isset($learn_styles_form->learner_sign) && $learn_styles_form->learner_sign != '') {
                $learn_styles_label = '<span class="label label-success"><i class="fa fa-check"></i> Learn Styles Assessment</span>';
            }
        }
        $sa_label = '<span class="label label-danger"><i class="fa fa-close"></i> Skills Analysis</span>';
        if (isset($skills_analysis->signed_by_learner) && ($skills_analysis->signed_by_learner == '1' || $skills_analysis->employer_sign != '')) {
            $sa_label = $skills_analysis->signed_by_provider != '1' ? '<span class="label label-warning"><i class="fa fa-check"></i> Skills Analysis</span>' : '<span class="label label-success"><i class="fa fa-check"></i> Skills Analysis</span>';
        }
        $ob_label = '<span class="label label-danger"><i class="fa fa-close"></i> Onboarding Questionnaire</span>';
        if (isset($tr->learner_sign) && $tr->learner_sign != '') {
            $ob_label = $tr->tp_sign == '' ? '<span class="label label-warning"><i class="fa fa-check"></i> Onboarding Questionnaire</span>' : '<span class="label label-success"><i class="fa fa-check"></i> Onboarding Questionnaire</span>';
        }
        $app_ag_label = '<span class="label label-danger"><i class="fa fa-close"></i> Apprenticeship Agreement</span>';
        if (isset($tr->learner_sign) && $tr->learner_sign != '') {
            $app_ag_label = $tr->emp_sign == '' ? '<span class="label label-warning"><i class="fa fa-check"></i> Apprenticeship Agreement</span>' : '<span class="label label-success"><i class="fa fa-check"></i> Apprenticeship Agreement</span>';
        }

        $otj_label = '';
        if ($tr->practical_period_start_date >= '2023-08-01' && (DB_NAME == "am_ela") && !in_array($tr->id, OnboardingHelper::UlnsToSkip($link))) {
            $otj_signs = DAO::getObject($link, "SELECT * FROM otj_planner_signatures WHERE tr_id = '{$tr->id}'");
            $otj_label = '<span class="label label-danger"><i class="fa fa-close"></i> OTJ Planner</span>';
            if (isset($otj_signs->tr_id)) {
                if ($otj_signs->learner_sign != '' && $otj_signs->employer_sign != '' && $otj_signs->provider_sign != '') {
                    $otj_label = '<span class="label label-success"><i class="fa fa-check"></i> OTJ Planner</span>';
                } elseif ($otj_signs->learner_sign != '' || $otj_signs->employer_sign != '' || $otj_signs->provider_sign != '') {
                    $otj_label = '<span class="label label-warning"><i class="fa fa-check"></i> OTJ Planner</span>';
                }
            }
        }

        $countries = DAO::getResultset($link, "SELECT id, country_name FROM lookup_countries WHERE id = 76 UNION ALL SELECT id, country_name FROM lookup_countries WHERE id != 76;");
        $nationalities = DAO::getResultset($link, "SELECT id, description FROM lookup_nationalities ORDER BY description;");

        $saved_eligibility_list = $tr->EligibilityList
            ? explode(',', $tr->EligibilityList)
            : [];
        $care_leaver_details = $tr->getCareLeaverDetails($link);
        $criminal_conviction_details = $tr->getCriminalConvictionDetails($link);

        $iag_form_results = DAO::getResultset($link, "SELECT * FROM ob_learner_pre_iag_form WHERE tr_id = '{$tr->id}'", DAO::FETCH_ASSOC);
        if (count($iag_form_results) == 0) {
            $iag_entry = new stdClass();
            $iag_entry->tr_id = $tr->id;
            DAO::saveObjectToTable($link, "ob_learner_pre_iag_form", $iag_entry);
        }

        if ($tr->isNonApp($link)) {
            $bespoke_training_plan_results = DAO::getResultset($link, "SELECT * FROM ob_learner_bespoke_training_plan WHERE tr_id = '{$tr->id}'", DAO::FETCH_ASSOC);
            if (count($bespoke_training_plan_results) == 0) {
                $bespoke_training_plan_entry = new stdClass();
                $bespoke_training_plan_entry->tr_id = $tr->id;
                DAO::saveObjectToTable($link, "ob_learner_bespoke_training_plan", $bespoke_training_plan_entry);
            }
        }

        $employer_agreement_full_signed = false;
        $employer_agreement_signs = DAO::getObject($link, "SELECT provider_sign, employer_sign FROM employer_agreements WHERE employer_id = '{$tr->employer_id}' ORDER BY employer_agreements.id DESC LIMIT 1");
        if (isset($employer_agreement_signs->provider_sign) && isset($employer_agreement_signs->employer_sign) && $employer_agreement_signs->provider_sign != '' && $employer_agreement_signs->employer_sign != '') {
            $employer_agreement_full_signed = true;
        }

        if (DB_NAME == "am_ela" && !$tr->isNonApp($link)) {
            $this->prepareOtjPlanner($link, $tr, $framework);
            if ($skills_analysis->signed_by_learner == 1 && $skills_analysis->signed_by_provider == 1 && $skills_analysis->employer_sign != '') {
                $sa_label = '<span class="label label-success"><i class="fa fa-check"></i> Skills Analysis</span>';
            } elseif ($skills_analysis->signed_by_learner == 1 || $skills_analysis->signed_by_provider == 1 || $skills_analysis->employer_sign != '') {
                $sa_label = '<span class="label label-warning"><i class="fa fa-check"></i> Skills Analysis</span>';
            } else {
                $sa_label = '<span class="label label-danger"><i class="fa fa-close"></i> Skills Analysis</span>';
            }
        }

        $enrolment_label = '';
        $bespoke_tp_label = '';
        $wellbeing_label = '';
        if ($tr->isNonApp($link) && $framework->fund_model == Framework::FUNDING_STREAM_99) {
            $enrolment_label = '<span class="label label-danger"><i class="fa fa-close"></i> Enrolment Form</span>';
            if (isset($tr->learner_sign) && $tr->learner_sign != '') {
                $enrolment_label = $tr->tp_sign == '' ? '<span class="label label-warning"><i class="fa fa-check"></i> Enrolment Form</span>' : '<span class="label label-success"><i class="fa fa-check"></i> Enrolment Form</span>';
            }
        }
        if ($tr->isNonApp($link) && in_array(DB_NAME, ["am_eet", "am_puzzled"])) {
            $enrolment_label = '<span class="label label-danger"><i class="fa fa-close"></i> Enrolment Form</span>';
            if (isset($tr->learner_sign) && $tr->learner_sign != '') {
                $enrolment_label = $tr->tp_sign == '' ? '<span class="label label-warning"><i class="fa fa-check"></i> Enrolment Form</span>' : '<span class="label label-success"><i class="fa fa-check"></i> Enrolment Form</span>';
            }

            $bespoke_tp_label = '<span class="label label-danger"><i class="fa fa-close"></i> Bespoke Training Plan</span>';
            $bespoke_tp_form = DAO::getObject($link, "SELECT * FROM ob_learner_bespoke_training_plan WHERE tr_id = '{$tr->id}'");
            if (isset($bespoke_tp_form->learner_sign) && $bespoke_tp_form->learner_sign != '') {
                $bespoke_tp_label = $bespoke_tp_form->provider_sign == '' ? '<span class="label label-warning"><i class="fa fa-check"></i> Bespoke Training Plan</span>' : '<span class="label label-success"><i class="fa fa-check"></i> Bespoke Training Plan</span>';
            }

            $wellbeing_label = '<span class="label label-danger"><i class="fa fa-close"></i> Wellbing Assessment</span>';
            if (in_array(DB_NAME, ["am_eet", "am_puzzled"])) {
                $weelbeing_form = DAO::getObject($link, "SELECT * FROM ob_learner_wellbeing_assessment WHERE tr_id = '{$tr->id}'");
                if (isset($weelbeing_form->learner_sign) && $weelbeing_form->learner_sign != '') {
                    $wellbeing_label = $weelbeing_form->provider_sign == '' ? '<span class="label label-warning"><i class="fa fa-check"></i> Wellbing Assessment</span>' : '<span class="label label-success"><i class="fa fa-check"></i> Wellbing Assessment</span>';
                }
            }
        }

        $ageAtStart = 0;
        if (!empty($tr->practical_period_start_date) && !empty($ob_learner->dob)) {
            $ageAtStart = Date::dateDiffInfo($tr->practical_period_start_date, $ob_learner->dob);
            $ageAtStart = isset($ageAtStart["year"]) ? $ageAtStart["year"] : 0;
        }

        include_once('tpl_read_training.php');
    }

    public function generateSchedule1Pdf(PDO $link, TrainingRecord $tr, EmployerSchedule1 $schedule)
    {
        $schedule_directory = $tr->getDirectoryPath() . 'schedule1/';
        if (!is_dir($schedule_directory)) {
            mkdir("$schedule_directory", 0777, true);
        }
        $schedule_file = $schedule_directory . EmployerSchedule1::SCH_PDF_NAME;
        if (!is_file($schedule_file) || in_array("S1", explode(",", $tr->generate_pdfs))) {
            $schedule->generatePdf($link);
        }
    }

    private function getDescriptions($lookup, $keys, $nbsp = false)
    {
        if (!is_array($keys))
            $keys = explode(",", $keys);

        $output = [];
        foreach ($lookup as $key => $value) {
            if (in_array($key, $keys))
                $output[] = $nbsp ? str_replace(" ", "&nbsp;", $value) : $value;
        }

        return $output;
    }

    public function renderScheduleTab(PDO $link, OnboardingLearner $ob_learner, TrainingRecord $tr, EmployerSchedule1 $schedule) {}

    public function renderAlsAndAdditionalDetails(PDO $link, TrainingRecord $vo)
    {
        echo '<p><span class="btn btn-xs btn-primary" onclick="window.location.href=\'do.php?_action=edit_ob_learner_als&tr_id=' . $vo->id . '\'">Create ALS</span></p>';
        echo '<table class="table table-bordered">';
        echo '<tr><th>Date Discussed</th><th>Support Required</th><th>Details of any learning support</th><th>Date Claimed From</th><th>Additional Info.</th></tr>';
        $result = DAO::getResultset($link, "SELECT * FROM ob_learner_als WHERE tr_id = '{$vo->id}'", DAO::FETCH_ASSOC);
        foreach ($result as $row) {
            echo HTML::viewrow_opening_tag('do.php?_action=edit_ob_learner_als&id=' . $row['id'] . '&tr_id=' . $vo->id);
            echo '<td>' . Date::toShort($row['date_discussed']) . '</td>';
            echo $row['support_required'] == 'Y' ? '<td>Yes</td>' : '<td>No</td>';
            echo '<td class="small">' . $row['details'] . '</td>';
            echo '<td>' . Date::toShort($row['date_claimed_from']) . '</td>';
            echo '<td class="small">' . $row['additional_info'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';

        echo '<p><br></p>';
    }

    public function renderKsbStatsOverview(PDO $link, TrainingRecord $vo)
    {
        echo '<table class="table table-bordered">';
        echo '<tr><th colspan="2">Skills Analysis Score Overview</th></tr>';
        echo '<tr><th></th><th>Score</th></tr>';
        $result = DAO::getResultset($link, "SELECT unit_group, SUM(score) AS _score FROM ob_learner_ksb WHERE tr_id = '{$vo->id}' GROUP BY unit_group;", DAO::FETCH_ASSOC);
        $total = 0;
        foreach ($result as $row) {
            echo '<tr>';
            echo '<th>' . $row['unit_group'] . '</th>';
            echo '<td>' . $row['_score'] . '</td>';
            echo '</tr>';
            $total += $row['_score'];
        }
        echo '<tr><th>Total</th><td>' . $total . '</td></tr>';

        echo '</table>';
    }

    public function renderKsbStatsDetail(PDO $link, TrainingRecord $vo)
    {
        $scores = LookupHelper::getListKsbScores();
        echo '<table class="table table-bordered">';
        echo '<tr><th colspan="2">Key</th></tr>';
        foreach ($scores as $key => $value) {
            echo '<tr>';
            echo '<td>' . $key . '</td><td>' . $value . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        $btn_view_log = '';
        $logs = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ob_learner_ksb_log WHERE tr_id = '{$vo->id}'");
        if ($logs > 0)
            $btn_view_log = '<span title="View Changes" class="btn btn-xs btn-info pull-right" onclick="viewKsbLogInfo();"><i class="fa fa-info-circle"></i> </span>';
        echo '<table class="table table-bordered">';
        echo '<tr><th colspan="7">Skills Analysis Detail ' . $btn_view_log . '</th></tr>';
        echo '<tr><th>KSB</th><th>Topic</th><th>Required</th><th>Score</th><th>Comments</th><th class="small">Delivery Plan Hours (100%)</th><th class="small">Delivery Plan Hours (following assessment)</th></tr>';
        $result = DAO::getResultset($link, "SELECT * FROM ob_learner_ksb WHERE tr_id = '{$vo->id}' ORDER BY id", DAO::FETCH_ASSOC);

        $delivery_plan_total_fa = 0;
        $delivery_plan_total_ba = 0;
        foreach ($result as $row) {
            $delivery_plan_hours = 0;
            $del_hours = $row['del_hours'] != '' ? floatval($row['del_hours']) : 20;
            echo '<tr>';
            echo '<td>' . $row['unit_group'] . '</td>';
            echo '<td>' . $row['unit_title'] . '</td>';
            echo '<td>' . $row['evidence_title'] . '</td>';
            echo '<td>' . $row['score'] . '</td>';
            echo '<td class="small">' . $row['comments'] . '</td>';
            echo '<td>' . $del_hours . '</td>';
            echo '<td>';
            if ($row['score'] == 5)
                $delivery_plan_hours = ceil($del_hours * 0.25);
            elseif ($row['score'] == 4)
                $delivery_plan_hours = ceil($del_hours * 0.5);
            elseif ($row['score'] == 3)
                $delivery_plan_hours = ceil($del_hours * 0.75);
            elseif ($row['score'] == 2)
                $delivery_plan_hours = ceil($del_hours * 0.9);
            elseif ($row['score'] == 1)
                $delivery_plan_hours = $del_hours;
            echo $delivery_plan_hours;
            echo '</td>';
            echo '</tr>';
            $delivery_plan_total_fa += $delivery_plan_hours;
            $delivery_plan_total_ba += $del_hours;
        }
        $delivery_plan_total_fa = ceil($delivery_plan_total_fa);
        echo '<tr><th></th><th></th><th></th><th></th><th></th>';
        echo '<th class="bg-light-blue">' . $delivery_plan_total_ba . '</th><th class="bg-light-blue">' . $delivery_plan_total_fa . '</th></tr>';
        echo '<tr><th colspan="7" class="text-center bg-green-gradient">' . round(($delivery_plan_total_fa / $delivery_plan_total_ba) * 100, 0) . '%</th> </tr>';
        echo '</table>';
    }

    public function renderComposeNewMessageBox(PDO $link, TrainingRecord $vo)
    {
        $ob_learner = $vo->getObLearnerRecord($link);

        $fdil_entry_created = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ob_learner_fdil WHERE tr_id = '{$vo->id}'");
        if ($fdil_entry_created)
            $email_templates = DAO::getResultset($link, "SELECT template_type, template_type, null FROM email_templates WHERE template_type IN ('INITIAL_ASSESSMENT_MATH','INITIAL_ASSESSMENT_ENGLISH','NON_APP_ENROLMENT FORM', 'SKILLS_SCAN_URL', 'ONBOARDING_URL', 'SKILLS_SCAN_PASSED', 'SKILLS_SCAN_FAILED', 'APP_AGREEMENT_EMAIL_TO_EMPLOYER', 'LEARNER_FIRST_DAY_IN_LEARNING', 'PRE_IAG_FORM', 'LEARN_STYLE_ASSESSMENT_URL', 'EVALUATION_FORMS_AND_SKILLS_ANALYSIS', 'FDIL_SESSION_LEARNER_URL', 'FDIL_SESSION_TUTOR_URL', 'OTJ_LEARNER_URL', 'OTJ_EMPLOYER_URL', 'SKILLS_SCAN_EMPLOYER_URL', 'EVALUATION_FORMS_AND_SKILLS_ANALYSIS_NO_WRITING', 'DP_LEARNER_URL', 'DP_EMPLOYER_URL', 'ENROLMENT_URL') ORDER BY sorting;");
        else
            $email_templates = DAO::getResultset($link, "SELECT template_type, template_type, null FROM email_templates WHERE template_type IN ('INITIAL_ASSESSMENT_MATH','INITIAL_ASSESSMENT_ENGLISH', 'NON_APP_ENROLMENT FORM', 'SKILLS_SCAN_URL', 'ONBOARDING_URL', 'SKILLS_SCAN_PASSED', 'SKILLS_SCAN_FAILED', 'APP_AGREEMENT_EMAIL_TO_EMPLOYER', 'LEARNER_FIRST_DAY_IN_LEARNING', 'PRE_IAG_FORM', 'LEARN_STYLE_ASSESSMENT_URL', 'EVALUATION_FORMS_AND_SKILLS_ANALYSIS', 'OTJ_LEARNER_URL', 'OTJ_EMPLOYER_URL', 'SKILLS_SCAN_EMPLOYER_URL', 'EVALUATION_FORMS_AND_SKILLS_ANALYSIS_NO_WRITING', 'NON_APP_ENROLMENT FORM', 'DP_LEARNER_URL', 'DP_EMPLOYER_URL', 'ENROLMENT_URL') ORDER BY sorting;");
        if (in_array(DB_NAME, ["am_eet", "am_puzzled"])) {
            if ($vo->isNonApp($link)) {
                $email_templates = DAO::getResultset($link, "SELECT template_type, template_type, null FROM email_templates WHERE template_type IN ('INITIAL_ASSESSMENT_MATH','INITIAL_ASSESSMENT_ENGLISH', 'NON_APP_ENROLMENT FORM') ORDER BY sorting;");
            } else {
                $email_templates = DAO::getResultset($link, "SELECT template_type, template_type, null FROM email_templates WHERE template_type IN ('INITIAL_ASSESSMENT_MATH','INITIAL_ASSESSMENT_ENGLISH', 'SKILLS_SCAN_URL', 'ONBOARDING_URL', 'APP_AGREEMENT_EMAIL_TO_EMPLOYER', 'PRE_IAG_FORM', 'LEARN_STYLE_ASSESSMENT_URL', 'EVALUATION_FORMS_AND_SKILLS_ANALYSIS', 'SKILLS_SCAN_EMPLOYER_URL', 'EVALUATION_FORMS_AND_SKILLS_ANALYSIS_NO_WRITING') ORDER BY sorting;");
            }
        }
        $framework = Framework::loadFromDatabase($link, $vo->framework_id);
        if ($framework->fund_model == Framework::FUNDING_STREAM_99) {
            $email_templates = DAO::getResultset($link, "SELECT template_type, template_type, null FROM email_templates WHERE template_type IN ('COMMERCIAL_AND_LEARNER_LOAN_ENROLMENT_FORM', 'COMMERCIAL_AND_LEARNER_LOAN_ADDITIONAL_FORMS', 'COMM_ONBOARDING_EMPLOYER_URL') ORDER BY sorting;");
        }

        array_unshift($email_templates, array('', 'Email template:', ''));
        $ddlTemplates =  HTML::selectChosen('frmEmailTemplate', $email_templates, '', false);
        $html = <<<HTML
<form name="frmEmail" id="frmEmail" action="do.php?_action=ajax_email_actions" method="post">
	<input type="hidden" name="subaction" value="sendEmail" />
	<input type="hidden" name="frmEmailEntityType" value="ob_learners" />
	<input type="hidden" name="frmEmailEntityId" value="$vo->id" />
	<div class="box box-primary">
		<div class="box-header with-border"><h2 class="box-title">Compose New Email</h2></div>
		<div class="box-body">
			<div class="form-group"><div class="row"> <div class="col-sm-8"> $ddlTemplates </div><div class="col-sm-4"> <span class="btn btn-sm btn-default" onclick="load_email_template_in_frmEmail();">Load template</span></div> </div></div>
			<div class="form-group">To: <input name="frmEmailTo" id="frmEmailTo" class="form-control compulsory" placeholder="To:" value="$ob_learner->home_email"></div>
			<div class="form-group">Subject: <input name="frmEmailSubject" id="frmEmailSubject" class="form-control compulsory" placeholder="Subject:" autocomplete="0"></div>
			<div class="form-group"><textarea name="frmEmailBody" id="frmEmailBody" class="form-control compulsory" style="height: 300px"></textarea></div>
		</div>
		<div class="box-footer">
			<div class="pull-right"><span class="btn btn-primary" onclick="sendEmail();"><i class="fa fa-envelope-o"></i> Send</span></div>
			<span class="btn btn-default" onclick="$('#btnCompose').show(); $('#mailBox').show(); $('#composeNewMessageBox').hide();"><i class="fa fa-times"></i> Discard</span>
		</div>
	</div>
</form>
HTML;

        return $html;
    }

    public function renderOnboardingTab(PDO $link, TrainingRecord $tr)
    {
        $skills_analysis = $tr->getSkillsAnalysis($link);
        if ($skills_analysis->signed_by_learner == 1 && $skills_analysis->signed_by_provider == 1) {
            if ($skills_analysis->is_eligible_after_ss == 'Y') {
                if ($tr->dp_set == 'N') {
                    echo '<span class="btn btn-primary btn-md" onclick="window.location.href=\'do.php?_action=enrol_ob_learner&tr_id=' . $tr->id . '\'">';
                    echo 'Prepare for Onboarding';
                    echo '</span>';
                } else {
                    if ($tr->is_finished == 'Y') {
                        echo '<p class="text-bold text-info "><i class="fa fa-info-circle"></i> Signed by learner.</p>';
                        if ($tr->emp_sign != '') {
                            echo '<p class="text-bold text-info "><i class="fa fa-info-circle"></i> Signed by employer.</p>';
                            if ($tr->tp_sign != '') {
                                echo '<p class="text-bold text-info "><i class="fa fa-info-circle"></i> Signed by provider.</p>';
                            } else {
                                echo '<p class="text-bold text-red "><i class="fa fa-info-circle"></i> Need provider signature. </p>';
                                echo '<p><span class="btn btn-primary btn-xs" onclick="window.location.href=\'do.php?_action=provider_sign_onboarding&tr_id=' . $tr->id . '\'">';
                                echo 'Provider Sign Onboarding Form';
                                echo '</span></p>';
                            }
                            echo '<div class="pull-right">';
                            echo '<span class="btn btn-success btn-xs" onclick="downloadEmployerAppAgreement();">';
                            echo '<i class="fa fa-file-pdf-o"></i> Download App. Agreement';
                            echo '</span> &nbsp;';
                            echo '</div>';

                            $onboarding_directory = $tr->getDirectoryPath() . 'onboarding/';
                            $a_file = $onboarding_directory . OnboardingHelper::LEARNING_AGREEMENT;
                            if (is_file($a_file)) {
                                echo '<div class="pull-right">';
                                echo '<span class="btn btn-success btn-xs" onclick="downloadLearningAgreement();">';
                                echo '<i class="fa fa-file-pdf-o"></i> Download Learning Agreement';
                                echo '</span> &nbsp;';
                                echo '</div>';
                            }

                            $onboarding_directory = $tr->getDirectoryPath() . 'onboarding/';
                            $c_file = $onboarding_directory . OnboardingHelper::COMMITMENT_PDF_NAME;
                            if (is_file($c_file)) {
                                echo '<div class="pull-right">';
                                echo '<span class="btn btn-success btn-xs" onclick="downloadCommitmentStatement();">';
                                echo '<i class="fa fa-file-pdf-o"></i> Download Training Plan';
                                echo '</span> &nbsp;';
                                echo '</div>';
                            }

                            $onboarding_directory = $tr->getDirectoryPath() . 'onboarding/';
                            $fla_file = $onboarding_directory . OnboardingHelper::FIRST_LEARNING_ACTIVITY;
                            if (is_file($fla_file)) {
                                echo '<div class="pull-right">';
                                echo '<span class="btn btn-success btn-xs" onclick="downloadFirstLearningActivity();">';
                                echo '<i class="fa fa-file-pdf-o"></i> Download First Learning Activity';
                                echo '</span> &nbsp;';
                                echo '</div>';
                            }
                        }
                        //$this->showOnboarding($link, $tr);
                        echo '<p><br></p><p class="text-center"><span class="btn btn-primary btn-md" onclick="window.location.href=\'do.php?_action=view_form_onboarding&id=' . $tr->id . '\'">';
                        echo 'View Apprenticeship Enrolment / Training Plan';
                        echo '</span></p>';
                    } else {
                        echo '<p class="text-bold text-info "><i class="fa fa-info-circle"></i> Awaiting learner.</p>';
                        //echo '<div class="input-group">';
                        //echo '<input id="ob_url" readonly type="text" class="form-control" value="' . OnboardingHelper::generateOnboardingUrl($tr->id) . '">';
                        //echo '<span class="input-group-addon" title="Click to copy the URL" onclick="copyUrl(\'ob_url\', \'copyObUrlTooltip\');"> <i class="fa fa-copy"></i></span>';
                        //echo '</div>';
                    }
                }
            } else {
                echo '<p class="text-bold text-red "><i class="fa fa-info-circle"></i> Learner is set as ineligible after Skills Assessment.</p>';
            }
        } else {
            echo '<p class="text-bold text-info "><i class="fa fa-info-circle"></i> Awaiting Skills Assessment.</p>';
        }
    }

    public function renderFileRepository(TrainingRecord $vo, $directory = 'other')
    {
        $repository = $vo->getDirectoryPath() . $directory;
        $files = Repository::readDirectory($repository);

        if (count($files) > 0) {
            echo '<div class="row is-flex">';
            foreach ($files as $f) {
                if ($f->isDir()) {
                    continue;
                }
                $ext = new SplFileInfo($f->getName());
                $ext = $ext->getExtension();
                $image = 'fa-file';
                if ($ext == 'doc' || $ext == 'docx')
                    $image = 'fa-file-word-o';
                elseif ($ext == 'pdf')
                    $image = 'fa-file-pdf-o';
                elseif ($ext == 'txt')
                    $image = 'fa-file-text-o';

                $html = '<li class="list-group-item">';
                $html .= '<i class="fa ' . $image . '"></i> ' . htmlspecialchars($f->getName());
                $html .= '<br><span class="direct-chat-timestamp"><i class="fa fa-clock-o"></i> ' . date("d/m/Y H:i:s", $f->getModifiedTime()) . '</span>';
                $html .= '<br><span class="direct-chat-timestamp"><i class="fa fa-folder"></i> ' . Repository::formatFileSize($f->getSize()) . '</span>';

                $html .= '<br><p>';
                $html .= '<span title="Download file" class="btn btn-xs btn-info" onclick="window.location.href=\'' . $f->getDownloadURL() . '\';"><i class="fa fa-download"></i></span>';
                $html .= '<span title="Delete file" class="btn btn-xs btn-danger pull-right" onclick="deleteFile(\'' . $f->getRelativePath() . '\');"><i class="fa fa-trash"></i></span>';
                $html .= '</p>';

                echo '</li>';
                echo <<<HTML
<div class="col-sm-12">
	$html
</div>
HTML;
            }
            echo '</div> ';
        } else {
            echo '<p><br></p><div class="panel panel-info"><i class="fa fa-info-circle"></i> No files.</div> ';
        }
    }

    public function showEmployerSchedule(PDO $link, TrainingRecord $tr)
    {
        $schedule = $tr->getEmployerAgreementSchedule1($link);
        $detail = json_decode($schedule->detail);
        $employer = Employer::loadFromDatabase($link, $tr->employer_id);
        $employer_location = Location::loadFromDatabase($link, $tr->employer_location_id);
        $ob_learner = $tr->getObLearnerRecord($link);
        $framework = Framework::loadFromDatabase($link, $tr->framework_id);
        $ob_learner_dob = Date::toShort($ob_learner->dob);
        $age_at_start = Date::dateDiff(date("Y-m-d"), $ob_learner->dob);

        if ($schedule->tp_sign == '')
            echo '<span class="label label-danger"><i class="fa fa-close"></i> Signed by provider</span>';
        else
            echo '<span class="label label-success"><i class="fa fa-check"></i> Signed by provider</span>';
        echo '&nbsp;';
        if ($schedule->emp_sign == '')
            echo '<span class="label label-danger"><i class="fa fa-close"></i> Signed by employer</span>';
        else
            echo '<span class="label label-success"><i class="fa fa-check"></i> Signed by employer</span>';

        echo (!is_null($schedule->emp_sign) && !is_null($schedule->tp_sign)) ? '<span class="btn btn-xs btn-success pull-right" onclick="downloadSchedule();"><i class="fa fa-file-pdf-o"></i> Download</span>' : '';

        echo <<<HTML
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col width="8%">
            <col width="20%">
            <col width="20%">
            <col width="20%">
            <col width="20%">
            <tr><th colspan="6" class="bg-blue">Section 1 - Employer and Apprentice Details</th></tr>
            <tr>
                <th rowspan="4">1.1</th>
            </tr>
            <tr>
                <th>Name of Employer</th>
                <td colspan="3">{$employer->legal_name}</td>
            </tr>
            <tr>
                <th>Contact Name</th>
                <td>{$employer_location->contact_name}</td>
                <th>Contact Tel No.</th>
                <td>{$employer_location->contact_telephone}</td>
            </tr>
            <tr>
                <th>Contact Email</th>
                <td colspan="3">{$employer_location->contact_email}</td>
            </tr>
            <tr>
                <th rowspan="4">1.2</th>
            </tr>
            <tr>
                <th>Name of Apprentice</th>
                <td colspan="3">{$ob_learner->firstnames} {$ob_learner->surname}</td>
            </tr>
            <tr>
                <th>Date of Birth</th>
                <td>{$ob_learner_dob}</td>
                <th>Age at start of apprenticeship</th>
                <td>{$age_at_start}</td>
            </tr>
            <tr>
                <th>ULN</th>
                <td></td>
                <th>Cohort</th>
                <td>{$framework->title}</td>
            </tr>
        </table>
    </div>
</div>

HTML;

        $apprentice_job_title = (isset($detail->apprentice_job_title) && $detail->apprentice_job_title != '') ? $detail->apprentice_job_title : $tr->job_title;
        $level = DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';");
        $title_of_app = $framework->getStandardCodeDesc($link);
        $proposed_sd = isset($detail->proposed_start_date) ? $detail->proposed_start_date : '';
        $proposed_ed = isset($detail->proposed_end_date) ? $detail->proposed_end_date : '';

        echo <<<HTML
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col width="8%">
            <col width="30%">
            <col width="70%">
            <tr><th colspan="3" class="bg-blue">Section 2 - Apprenticeship Programme</th></tr>
            <tr>
                <th>2.1</th>
                <th>Apprentice Job Title</th>
                <td>{$apprentice_job_title}</td>
            </tr>
            <tr>
                <th>2.2</th>
                <th>Standard</th>
                <td>{$framework->title}</td>
            </tr>
            <tr>
                <th>2.3</th>
                <th>Level of Apprenticeship</th>
                <td>{$level}</td>
            </tr>
            <tr>
                <th>2.4</th>
                <th>Title of Apprenticeship</th>
                <td>{$title_of_app}</td>
            </tr>
            <tr>
                <th>2.5</th>
                <th>Location of Training</th>
                <td>
                    $employer_location->address_line_1,
                    $employer_location->address_line_2 
                    $employer_location->address_line_3, 
                    $employer_location->address_line_4,
                    $employer_location->postcode
                </td>
            </tr>
            <tr>
                <th>2.6</th>
                <th>Proposed Start Date</th>
                <td>{$proposed_sd}</td>
            </tr>
            <tr>
                <th>2.7</th>
                <th>Proposed End Date<br><small>(for practical training)</small></th>
                <td>{$proposed_ed}</td>
            </tr>
        </table>
    </div>
</div>
HTML;

        $trainers_ids = $tr->trainers != '' ? explode(",", $tr->trainers) : [];
        $_trainers = '';
        foreach ($trainers_ids as $_t_id)
            $_trainers .= DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$_t_id}'") . '<br>';

        echo <<<HTML
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col width="8%">
            <col width="30%">
            <col width="70%">
            <tr><th colspan="3" class="bg-blue">Section 3 - Training Provider Actions</th></tr>
            <tr>
                <th>3.1</th>
                <th>Training to be delivered by the<br>Training Provider</th>
                <td>{$detail->training_by_provider}</td>
            </tr>
            <tr>
                <th>3.2</th>
                <th>Trainer</th>
                <td>{$_trainers}</td>
            </tr>
            <tr>
                <th>3.3</th>
                <th>Training Provider Equipment</th>
                <td>{$detail->provider_equipment}</td>
            </tr>
        </table>
    </div>
</div>

HTML;

        echo <<<HTML
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col width="8%">
            <col width="30%">
            <col width="70%">
            <tr><th colspan="3" class="bg-blue">Section 4 - Employer Actions</th></tr>
            <tr>
                <th>4.1</th>
                <th>Training to be delivered by the<br>Employer</th>
                <td>{$detail->training_by_employer}</td>
            </tr>
            <tr>
                <th>4.2</th>
                <th>Employer Equipment</th>
                <td>{$detail->employer_equipment}</td>
            </tr>
        </table>
    </div>
</div>

HTML;

        $epa_org_name = $tr->getEpaOrgName($link);
        echo <<<HTML
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col width="8%">
            <col width="30%">
            <col width="70%">
            <tr><th colspan="3" class="bg-blue">Section 5 - End-Point Assessment (EPA) Organisation - Standards Only</th></tr>
            <tr>
                <th>5.1</th>
                <th>Name of EPA Organisation</th>
                <td>{$epa_org_name}</td>
            </tr>
        </table>
    </div>
</div>

HTML;

        $subcontractor_name = $tr->getSubcontractorLegalName($link);
        $subcon_ukprn = DAO::getSingleValue($link, "SELECT ukprn FROM organisations WHERE id = '{$tr->subcontractor_id}'");
        echo <<<HTML
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col width="8%">
            <col width="30%">
            <col width="70%">
            <tr><th colspan="3" class="bg-blue">Section 6 - Subcontracting</th></tr>
            <tr>
                <th>6.1</th>
                <th>Name of Subcontractor</th>
                <td>{$subcontractor_name}</td>
            </tr>
            <tr>
                <th>6.2</th>
                <th>Training to be delivered by<br>Subcontractor</th>
                <td>{$detail->training_by_subcontractor}</td>
            </tr>
            <tr>
                <th>6.3</th>
                <th>UKPRN</th>
                <td>{$subcon_ukprn}</td>
            </tr>
        </table>
    </div>
</div>

HTML;
        $_e = DAO::getSingleValue($link, "SELECT COUNT(*) FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}' AND qualification_type = 'FS' AND LOWER(title) LIKE '%english%';");
        $_m = DAO::getSingleValue($link, "SELECT COUNT(*) FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}' AND qualification_type = 'FS' AND LOWER(title) LIKE '%math%';");
        $_ict = DAO::getSingleValue($link, "SELECT COUNT(*) FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}' AND qualification_type = 'FS' AND LOWER(title) LIKE '%ict%';");
        $_e = $_e > 0 ? 'Yes' : 'No';
        $_m = $_m > 0 ? 'Yes' : 'No';
        $_ict = $_ict > 0 ? 'Yes' : 'No';

        echo <<<HTML
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col width="8%">
            <col width="30%">
            <col width="70%">
            <tr><th colspan="3" class="bg-blue">Section 7 - Functional Skills required for this Apprenticeship (not the individual)</th></tr>
            <tr>
                <th>7.1</th>
                <th>Maths</th>
                <td>$_e</td>
            </tr>
            <tr>
                <th>7.2</th>
                <th>English</th>
                <td>$_m</td>
            </tr>
            <tr>
                <th>7.3</th>
                <th>ICT</th>
                <td>$_ict</td>
            </tr>
        </table>
    </div>
</div>

HTML;

        echo <<<HTML
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col width="8%">
            <col width="70%">
            <col width="30%">
            <tr><th colspan="3" class="bg-blue">Section 8 - Non-Funded Items</th></tr>
            <tr><th colspan="2">Detail of items not eligible for funding</th><th>Cost (&pound;)</th></tr>
            <tr>
                <th>8.1</th>
                <td>{$detail->items_not_eligible_for_funding1}</td>
                <td>{$detail->cost_of_items_not_eligible_for_funding1}</td>
            </tr>
            <tr>
                <th>8.2</th>
                <td>{$detail->items_not_eligible_for_funding2}</td>
                <td>{$detail->cost_of_items_not_eligible_for_funding2}</td>
            </tr>
        </table>
    </div>
</div>

HTML;
        $_band_max = $framework->getFundingBandMax($link);
        echo <<<HTML
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col width="8%">
            <col width="70%">
            <col width="30%">
            <tr><th colspan="3" class="bg-blue">Section 9 - Proposed Cost of Training Per Apprentice</th></tr>
            <tr><th colspan="2">The agreed charges (excluding VAT) for the training of each apprentice under this agreement is as follows:</th><th>Price per Agreement (&pound;)</th></tr>
            <tr>
                <th>9.1</th>
                <th>
                    Training Costs (all costs associated with the delivery of training, e.g. teaching, learning, assessment, reviews & OTJ etc.)
                    <br><span class="text-info"><i class="fa fa-info-circle"></i> <i>the maximum funding band for this standard is &pound; $_band_max</i></span>
                </th>
                <td>{$detail->training_cost}</td>
            </tr>
            <tr>
                <th>9.2</th>
                <th>Training Materials</th>
                <td>{$detail->training_material}</td>
            </tr>
            <tr>
                <th>9.3</th>
                <th>Registration, Examination & Certification cost associated with mandatory qualifications</th>
                <td>{$detail->reg_and_cert}</td>
            </tr>
            <tr>
                <th>9.4</th>
                <th>Total College Training Costs - TNP1 (9.1 + 9.2 + 9.3)</th>
                <td>{$detail->total_col_train_cost}</td>
            </tr>
            <tr>
                <th>9.5</th>
                <th>End Point Assessment Costs - TNP2 (standards only)</th>
                <td>{$tr->epa_price}</td>
            </tr>
            <tr>
                <th>9.6</th>
                <th>Total Negotiated Price (9.4 + 9.5)</th>
                <td>{$detail->total_negotiated_price}</td>
            </tr>
            <tr>
                <th>9.7</th>
                <th>Subcontractor Training Costs (if applicable) </th>
                <td>{$detail->subcontractor_training_cost}</td>
            </tr>
            <tr>
                <th>9.8</th>
                <th>Subcontractor Management / Monitoring Fee (if applicable)</th>
                <td>{$detail->subcontractor_management_cost}</td>
            </tr>
            <tr>
                <th>9.9</th>
                <th>Additional costs to be funded by the Employer (not eligible for Department for Education (DfE) funding)</th>
                <td>{$detail->additional_costs_by_employer}</td>
            </tr>
            <tr>
                <th>9.10</th>
                <th>Additional costs to be funded by the Training Provider (not eligible for Department for Education (DfE) funding)</th>
                <td>{$detail->additional_costs_by_tp}</td>
            </tr>
        </table>
    </div>
</div>

HTML;

        $cost1 = '';
        $cost2 = '';
        $cost3 = '';
        $cost4 = '';

        $learner_age_sql = <<<SQL
SELECT 
    ((DATE_FORMAT(CURDATE(),'%Y') - DATE_FORMAT('{$ob_learner->dob}','%Y')) - (DATE_FORMAT(CURDATE(),'00-%m-%d') < DATE_FORMAT('{$ob_learner->dob}','00-%m-%d'))) AS age        
SQL;
        $learner_age = DAO::getSingleValue($link, $learner_age_sql);

        if ($employer->funding_type == 'L') {
            $cost1 = $detail->cost_paid_to_barnsley1;
        } else {
            if (in_array($employer->code, [3, 4]) || $learner_age >= 19) // then show 2nd and 3rd box
            {
                $cost2 = $detail->cost_paid_to_barnsley2;
                $cost3 = $detail->cost_paid_to_barnsley3;
            } elseif (in_array($employer->code, [1, 2]) && $learner_age < 19) // then show 4th box
            {
                $cost4 = $detail->cost_paid_to_barnsley4;
            }
        }

        echo <<<HTML
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col width="25%">
            <col width="25%">
            <col width="25%">
            <col width="25%">
            <tr><th colspan="4" class="bg-blue">Section 10 - Total Cost of Training Paid to the Training Provider</th></tr>
            <tr class="text-center">
                <th>Levy Paying Employers</th>
                <th>Co-Funded/Non Levy Employers</th>
                <th>Government Contribution</th>
                <th>Government Contribution - SME</th>
            </tr>
            <tr class="text-center">
                <td>Maximum Employer Contribution via Levy - 100%</td>
                <td>0% or 5% Employer Contribution</td>
                <td>95%</td>
                <td>100%</td>
            </tr>
            <tr class="text-center">
                <td>{$cost1}</td>
                <td>{$cost2}</td>
                <td>{$cost3}</td>
                <td>{$cost4}</td>
            </tr>
        </table>
    </div>
</div>

HTML;

        echo <<<HTML
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <tr><th class="bg-blue">Section 11 - Additional Details (details supporting the negotiated costs / reduced rates)</th></tr>
            <tr>
                <td>The negotiated price will be confirmed with the Employer after the Skills Analysis has taken place, together with the first visit from the trainer.</td>
            </tr>
            <tr>
                <td>{$detail->section11_additional_details}</td>
            </tr>
        </table>
    </div>
</div>

HTML;

        $section12Option1 = (isset($detail->section12) && is_array($detail->section12) && in_array(1, $detail->section12)) ? '<i class="fa fa-check fa-lg text-green"></i> ' : '';
        $section12Option2 = (isset($detail->section12) && is_array($detail->section12) && in_array(2, $detail->section12)) ? '<i class="fa fa-check fa-lg text-green"></i> ' : '';
        $section12Option3 = (isset($detail->section12) && is_array($detail->section12) && in_array(3, $detail->section12)) ? '<i class="fa fa-check fa-lg text-green"></i> ' : '';
        echo <<<HTML
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <tr><th class="bg-blue">Section 12 - Additional Payments</th></tr>
            <tr>
                <td>
                    <p class="text-bold">16-18 Employer Incentive / 19-24 Education Health Care Plan</p>
                    <p>
                        The training provider and employer will receive a payment towards the additional cost associated with training
                        if, at the start of the apprenticeship, the apprentice is:
                    </p>
                    <ul style="margin-left: 5px;">
                        <li class="text-bold">
                            Aged between 16 and 18 years old (or 15 years of age if the apprentice's 16th birthday
                            is between the last Friday of June and 31 August).
                        </li>
                        <li class="text-bold">
                            Aged between 19 and 24 years old and has either an Education, Health and Care (EHC) plan
                            provided by their local authority or has been in the care of thier local authority.
                        </li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td>
                    <p>
                        <label>
                            {$section12Option1}
                            I (the Employer) confirm I am eligible for the &pound;1,000 16-18 Employer Incentive
                            for the Apprentice detailed within this schedule.
                        </label>
                    </p>
                    <p>
                        <label>
                            {$section12Option2}
                            I (the Employer) confirm I am eligible for the &pound;1,000 19-24 Education Health Care plan or care leaver
                            employer incentive for the Apprentice detailed within this schedule.
                            (Relevant evidence will be required at the beginning of the apprenticeship)
                        </label>
                    </p>
                    <p>
                        <label>
                            {$section12Option3}
                            Not Applicable
                        </label>
                    </p>
                </td>
            </tr>
        </table>
    </div>
</div>

HTML;

        echo <<<HTML
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <tr><th class="bg-blue">Section 13 - Payment Schedule</th></tr>
            <tr>
                <td>
                    <p class="text-bold">Levy Paying Employers</p>
                    <ul style="margin-left: 5px;">
                        <li>
                            80% of the total price will be taken from your Apprenticeship Service
                            account on a monthly basis, over the duration of the apprentice's programme.
                        </li>
                        <li class="text-bold">
                            20% of the total cost will be retained for achievement and/or End Point
                            Assessment costs and will be taken from your Apprenticeship Service Account.
                        </li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="text-bold">Co-Investor Employers</p>
                    <ul style="margin-left: 5px;">
                        <li>
                            Where your 5% Employer Contribution is &pound;250 or less, you will be
                            invoiced in full at the start of the apprenticeship programme.
                        </li>
                        <li class="text-bold">
                            Where your 5% Employer Contribution is over &pound;250, you will be invoiced in full,
                            and payments will be obtained on 4 equal instalments at months 1, 4, 7 and 9.
                        </li>
                        <li class="text-bold">
                            Invoices are to be paid within 30 days from the date of invoice.
                        </li>
                    </ul>
                </td>
            </tr>
        </table>
    </div>
</div>

HTML;

        echo <<<HTML
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <tr><th class="bg-blue">Section 14 - Mandatory Policies</th></tr>
            <tr>
                <td>
                    <p>Training Provider policies available to learner:</p>
                    <ul style="margin-left: 5px;">
                        <li>Safeguarding</li>
                        <li>Health & Safety</li>
                        <li>Equality & Diversity</li>
                        <li>GDPR</li>
                        <li>Complaints</li>
                    </ul>
                </td>
            </tr>
        </table>
    </div>
</div>

HTML;

        $section15radio_option1 = (isset($detail->section15radio) && $detail->section15radio == '1') ? '<i class="fa fa-check fa-lg"></i>' : '';
        $section15radio_option2 = (isset($detail->section15radio) && $detail->section15radio == '2') ? '<i class="fa fa-check fa-lg"></i>' : '';

        $_check_dec = $schedule->emp_sign_name != '' ? '<i class="fa fa-check fa-lg"></i>' : '';

        $_v1 = '';
        if (!$tr->postJuly25Start()) {
            $_v1 = "20% off-the-job training is the equivalent of 1 day per week based on a 5 day working week.";
        }

        echo <<<HTML
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <tr><th class="bg-blue">Section 15 - Employer Declarations (Please tick Option 1 OR Option 2 and all 3 declarations further below.)</th></tr>
            <tr>
                <td>
                    <span class="text-bold" style="margin-left: 5px;"> {$section15radio_option1} Option 1 - </span>
                    I confirm that apprentice(s) named in this Schedule 1 has/have been issued with a contract of
                    employment and is/will be employed for at least 30 hours per week. The minimum
                    duration of each apprenticeship is based on the apprentice working at least 30 hours a week.
                    <p class="text-bold">OR</p>
                    <span class="text-bold" style="margin-left: 5px;"> {$section15radio_option2} Option 2 - </span>
                    I confirm that apprentice(s) named in this Schedule 1 has/have been issued with a contract of
                    employment and is/will be employed for at least 16 hours per week. I am aware that
                    the duration of the apprenticeship will be extended accordingly to take account of this.
                </td>
            </tr>
            <tr>
                <td>
                    $_check_dec Off-the-job training has been discussed and I am aware of the requirements for this.
                    $_v1
                </td>
            </tr>
            <tr>
                <td>
                    $_check_dec The cost of this Apprenticeship has been discussed with us in detail, 
                    we fully understand the negotiated price for training and associated costs (TNP1) and we have negotiated the EPA price (TNP2). 
                    I understand that this is an indicative price at this point and is subject to change after the Skills Analysis has taken place.
                </td>
            </tr>
            <tr>
                <td>
                    $_check_dec I confirm that all apprentices listed in this schedule will spend at least
                    50% of their working hours in England over the duration of the apprenticeship.
                </td>
            </tr>
            <tr>
                <td>
                    $_check_dec I confirm as part of our recruitment process we have check the named apprentice(s) right
                     to work in the UK and have checked and hold copies of the relevant documentation which will be made
                      available to the main provider when requested.
                </td>
            </tr>
        </table>
    </div>
</div>

HTML;

        $emp_sign = isset($detail->emp_sign) ? $detail->emp_sign : 'title=Not yet signed&font=Signature_Regular.ttf&size=25';
        $tp_sign = isset($detail->tp_sign) ? $detail->tp_sign : 'title=Not yet signed&font=Signature_Regular.ttf&size=25';
        $emp_sign_date = isset($schedule->emp_sign_date) ? Date::toShort($schedule->emp_sign_date) : '';
        $tp_sign_date = isset($schedule->tp_sign_date) ? Date::toShort($schedule->tp_sign_date) : '';
        echo <<<HTML
<div class="row">
    <div class="col-sm-12 table-responsive">
        <table style="margin-top: 5px;" class="table table-bordered">
            <tr><th colspan="4" class="bg-blue">Signatures</th></tr>
            <tr><th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th></tr>
            <tr>
                <td>Employer</td>
                <td>{$schedule->emp_sign_name}</td>
                <td><img id="img_tp_sign" src="do.php?_action=generate_image&{$emp_sign}" style="border: 2px solid;border-radius: 15px;" /></td>
                <td>{$emp_sign_date}</td>
            </tr>
            <tr>
                <td>Training Provider</td>
                <td>{$detail->tp_sign_name}</td>
                <td><img id="img_tp_sign" src="do.php?_action=generate_image&{$tp_sign}" style="border: 2px solid;border-radius: 15px;" /></td>
                <td>{$tp_sign_date}</td>
            </tr>

        </table>
    </div>
</div>

HTML;
    }

    public function showOnboarding(PDO $link, TrainingRecord $tr)
    {
        $ob_learner = $tr->getObLearnerRecord($link);

        $emergency_contacts_result = DAO::getResultset($link, "SELECT * FROM ob_learner_emergency_contacts WHERE tr_id = '{$tr->id}' ORDER BY em_con_seq", DAO::FETCH_ASSOC);
        echo '<table class="table table-bordered">';
        echo '<caption>Emergency Contacts</caption>';
        echo '<thead><tr><th>Title</th><th>Name</th><th>Relation</th><th>Telephone</th><th>Mobile</th></tr></thead>';
        echo '<tbody>';
        if (count($emergency_contacts_result) == 0) {
            echo '<tr><td colspan="5">No records found.</td> </tr>';
        } else {
            foreach ($emergency_contacts_result as $emergency_contacts_record) {
                echo '<tr>';
                echo '<td>' . $emergency_contacts_record['em_con_title'] . '</td>';
                echo '<td>' . $emergency_contacts_record['em_con_name'] . '</td>';
                echo '<td>' . $emergency_contacts_record['em_con_rel'] . '</td>';
                echo '<td>' . $emergency_contacts_record['em_con_tel'] . '</td>';
                echo '<td>' . $emergency_contacts_record['em_con_mob'] . '</td>';
                echo '</tr>';
            }
        }
        echo '</tbody>';
        echo '</table>';

        $eligibility_list = $tr->EligibilityList != '' ? explode(",", $tr->EligibilityList) : [];
        echo '<table class="table table-bordered">';
        echo '<caption>Eligiblity</caption>';
        echo '<tr><td>Have you lived within the UK/EEA or EU for the last 3 Years?</td>';
        echo in_array(1, $eligibility_list) ? '<td>Yes</td>' : '<td>No</td>';
        echo '</tr>';
        echo '<tr><td>Are you currently enrolled at any other college, or training provider?</td>';
        echo in_array(2, $eligibility_list) ? '<td>Yes</td>' : '<td>No</td>';
        echo '</tr>';
        if (in_array(2, $eligibility_list)) {
            echo '<tr><td>Details</td>';
            echo '<td>' . $tr->currently_enrolled_in_other . '</td>';
            echo '</tr>';
        }
        echo '<tr><td>Country of Birth</td>';
        echo '<td>' . $tr->country_of_birth . '</td>';
        echo '</tr>';
        echo '<tr><td>Country of Permanent Residence</td>';
        echo '<td>' . $tr->country_of_perm_residence . '</td>';
        echo '</tr>';
        echo '<tr><td>Nationality</td>';
        echo '<td>' . $tr->nationality . '</td>';
        echo '</tr>';
        echo '<tr><td>Passport/Birth Certificate</td>';
        echo '<td>' . $tr->evidence_pp_file . '</td>';
        echo '</tr>';
        echo '<tr><td>Do you have a valid National Insurance Number?</td>';
        echo in_array(2, $eligibility_list) ? '<td>Yes</td>' : '<td>No</td>';
        echo '</tr>';


        echo '</tbody>';
        echo '</table>';
    }

    public function showSentEmails(PDO $link, TrainingRecord $tr)
    {
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered small">';
        $result = DAO::getResultset($link, "SELECT * FROM emails WHERE emails.entity_type IN ('ob_learners', 'tr') AND emails.entity_id = '{$tr->id}' ORDER BY created DESC", DAO::FETCH_ASSOC);
        echo '<caption class="lead text-bold text-center">Sent Emails (' . count($result) . ')</caption>';
        echo '<tr><th>DateTime</th><th>By</th><th>To Address</th><th>Subject</th><th>Email</th></tr>';
        foreach ($result as $row) {
            echo '<tr>';
            echo '<td>' . Date::to($row['created'], Date::DATETIME) . '</td>';
            echo '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = '{$row['by_whom']}'") . '</td>';
            echo '<td>' . $row['email_to'] . '</td>';
            echo '<td>' . $row['email_subject'] . '</td>';
            echo '<td><span class="btn btn-xs btn-info" onclick="viewEmail(\'' . $row['id'] . '\');"><i class="fa fa-eye"></i> View Email</span> </td>';
            echo '<td><span class="btn btn-xs btn-primary" onclick="resendEmail(\'' . $row['id'] . '\');"><i class="fa fa-send"></i> Resend Email</span> </td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
    }

    function prepareOtjPlanner(PDO $link, TrainingRecord $tr, Framework $framework)
    {
        // this function will run only if there are no otj planner entries for the learner.
        $entries = DAO::getSingleValue($link, "SELECT COUNT(*) FROM otj_tr_template_sections WHERE tr_id = '{$tr->id}'");
        if ($entries > 0) {
            return;
        }
        $standard_sections = DAO::getResultset($link, "SELECT * FROM otj_prog_template_sections WHERE framework_id = '{$framework->id}'", DAO::FETCH_ASSOC);
        if (count($standard_sections) == 0) {
            return;
        }

        DAO::transaction_start($link);
        try {
            foreach ($standard_sections as $standard_section) {
                $section = new stdClass();
                $section->section_id = null;
                $section->section_desc = $standard_section['section_desc'];
                $section->prog_section_id = $standard_section['section_id'];
                $section->tr_id = $tr->id;
                for ($i = 2; $i <= 16; $i++) {
                    $col = "col_{$i}_otj";
                    $section->$col = isset($standard_section[$col]) ? $standard_section[$col] : 0;
                }
                DAO::saveObjectToTable($link, "otj_tr_template_sections", $section);

                $standard_subsections = DAO::getResultset($link, "SELECT * FROM otj_prog_template_subsections WHERE section_id = '{$standard_section['section_id']}'", DAO::FETCH_ASSOC);
                foreach ($standard_subsections as $standard_subsection) {
                    $subsection = new stdClass();
                    $subsection->subsection_id = null;
                    $subsection->subsection_desc = $standard_subsection['subsection_desc'];
                    $subsection->section_id = $section->section_id;
                    DAO::saveObjectToTable($link, "otj_tr_template_subsections", $subsection);

                    $standard_activities = DAO::getResultset($link, "SELECT * FROM otj_prog_template_activities WHERE subsection_id = '{$standard_subsection['subsection_id']}'", DAO::FETCH_ASSOC);
                    foreach ($standard_activities as $standard_activity) {
                        $activity = new stdClass();
                        $activity->activity_id = null;
                        $activity->activity_desc = $standard_activity['activity_desc'];
                        $activity->subsection_id = $subsection->subsection_id;
                        DAO::saveObjectToTable($link, "otj_tr_template_activities", $activity);
                    }
                }
            }

            DAO::transaction_commit($link);
        } catch (Exception $e) {
            DAO::transaction_rollback($link);
            throw new Exception($e->getMessage());
        }
    }
}
