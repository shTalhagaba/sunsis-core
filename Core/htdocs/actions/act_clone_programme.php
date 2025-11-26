<?php
class clone_programme implements IAction
{
    public function execute(PDO $link)
    {
        $course_id = isset($_REQUEST['programme'])?$_REQUEST['programme']:'';
        $course = Course::loadFromDatabase($link, $course_id);

        $sql_framework = "INSERT INTO frameworks SELECT title, NULL, `framework_code`, `comments`, `duration_in_months`, `parent_org`, `active`, `clients`, `framework_type`, `milestones`, `start_payment`, `milestone_payment`,
        `achievement_payment`, `funding_stream`, `StandardCode`, `PwayCode`, `track`, `short_name`, `otj_hours`, `epa_org_id`, `epa_org_assessor_id`, `standard_ref_no`, `gateway_forecast`, `epa_forecast`
        FROM frameworks WHERE id = {$course->framework_id};";

        if($course_id=='' or $course->id=='')
            pre("Error");

        DAO::execute($link, $sql_framework);
        $framework_id = DAO::getSingleValue($link, "select id from frameworks order by id desc limit 1");

        $sql_framework_qualifications = "INSERT INTO framework_qualifications SELECT `id`, `lsc_learning_aim`, `awarding_body`, `title`, `description`, `assessment_method`, `structure`, `level`, `qualification_type`, `accreditation_start_date`,
        `operational_centre_start_date`,  `accreditation_end_date`,  `certification_end_date`,  `dfes_approval_start_date`,  `dfes_approval_end_date`,  '$framework_id',  `evidences`,  `units`,  `internaltitle`,
        `proportion`,  `duration_in_months`,  `units_required`,  `mandatory_units`,  `main_aim` FROM `framework_qualifications` WHERE framework_id = '$course->framework_id';";

        DAO::execute($link, $sql_framework_qualifications);

        $sql_courses = "INSERT INTO courses SELECT NULL,   `organisations_id`,  title,  `description`,  `main_qualification_id`,  `awarding_body_centre`,  `programme_number`,  `course_start_date`,  `course_end_date`,
        `min_numbers`,  `max_numbers`,  `director`,  `scheduled_lessons`,  `registered_lessons`,  `attendances`,  `lates`,  `very_lates`,  `authorised_absences`,  `unexplained_absences`,  `unauthorised_absences`,
        `dismissals_uniform`,  `dismissals_discipline`,  `username`,  '$framework_id',  `programme_type`,  `active`,  `frequency`,  `subsequent`,  `review_programme_title`,  `induction`,  `l4`,  `course_group`,
        `apprenticeship_title`,  `routway`,  `assessment_evidence` FROM `courses` WHERE id = '$course_id';";

        DAO::execute($link, $sql_courses);
        $new_course_id = DAO::getSingleValue($link, "select id from courses order by id desc limit 1");

        $sql_course_qualifications_dates = "INSERT INTO course_qualifications_dates SELECT   `qualification_id`,  '$framework_id',  `internaltitle`,  '$new_course_id',  `qualification_start_date`,  `qualification_end_date`,  `provider_id`,  `tutor_username`,  `location_id` FROM `course_qualifications_dates` WHERE course_id = '$course_id';";
        DAO::execute($link, $sql_course_qualifications_dates);
        $sql_ap_percentage = "insert into ap_percentage SELECT NULL,  '$new_course_id',  `min_month`,  `max_month`,  `aps`,  `comp` FROM `ap_percentage` where course_id = '$course_id'";
        DAO::execute($link, $sql_ap_percentage);
        $sql_evidence_criteria = "insert into evidence_criteria SELECT NULL,  '$new_course_id',  `criteria`, `competency` FROM `evidence_criteria` where course_id = '$course_id'";
        DAO::execute($link, $sql_evidence_criteria);
        $sql_evidence_project = "insert into evidence_project SELECT   NULL,  '$new_course_id',  `project` FROM `evidence_project` where course_id = '$course_id'";
        DAO::execute($link, $sql_evidence_project);
        $sql_lookup_assessment_plan_log_mode = "insert into lookup_assessment_plan_log_mode SELECT  `id`,  `description`,  `weeks`,  '$framework_id' FROM `lookup_assessment_plan_log_mode` where framework_id = '$course->framework_id'";
        DAO::execute($link, $sql_lookup_assessment_plan_log_mode);
        $sql_lookup_attitudes_behaviours = "insert into lookup_attitudes_behaviours SELECT   `id`,  `description`,  `weeks`,  '$framework_id' FROM `lookup_attitudes_behaviours` where framework_id = '$course->framework_id'";
        DAO::execute($link, $sql_lookup_attitudes_behaviours);
        $sql_lookup_skills_scan = "insert into lookup_skills_scan SELECT  `id`,  `description`,  `weeks`,  '$framework_id',  `description2`,  `category` FROM `lookup_skills_scan` where framework_id = '$course->framework_id'";
        DAO::execute($link, $sql_lookup_skills_scan);
        $sql_lookup_technical_knowledge = "insert into lookup_technical_knowledge SELECT   `id`,  `description`,  `weeks`,  '$framework_id',  `description2` FROM `lookup_technical_knowledge` where framework_id = '$course->framework_id'";
        DAO::execute($link, $sql_lookup_technical_knowledge);

    }
}
?>