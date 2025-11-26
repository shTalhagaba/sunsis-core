<?php
class SDReviewFormHelper
{
	public static function getFullReviewFormVO()
	{
		$vo = new stdClass();

		$vo->review_id = null;
		$vo->tr_id = null;
		$vo->a_visit_date = null;
		$vo->a_visit_start = null;
		$vo->a_visit_end = null;
		$vo->a_record_of_comm = null;
		$vo->a_qual_last_visit = null;
		$vo->a_fs_last_visit = null;
		$vo->a_qual_today = null;
		$vo->a_fs_today = null;
		$vo->a_qual_next_visit = null;
		$vo->a_fs_next_visit = null;
		$vo->a_qual_act_next_visit = null;
		$vo->a_fs_act_next_visit = null;
		$vo->a_fdbck_sgp = null;
		$vo->a_feedback = null;
		$vo->a_chngs_to_notify = null;
		$vo->a_tests_sat = null;
		$vo->a_sign = null;
		$vo->a_next_visit = null;
		$vo->l_equ_div = null;
		$vo->l_training= null;
		$vo->l_future_asp = null;
		$vo->l_support = null;
		$vo->l_feel_safe = 'N';
		$vo->l_had_acc = 'N';
		$vo->l_hav_changes = 'N';
		$vo->l_have_health_issues = 'N';
		$vo->l_feedback = null;
		$vo->l_sign = null;
		$vo->m_punc = 'N';
		$vo->m_well_pres = 'N';
		$vo->m_respectful = 'N';
		$vo->m_team_player = 'N';
		$vo->m_show_pd = 'N';
		$vo->m_demo_skills = 'N';
		$vo->m_show_conf = 'N';
		$vo->m_attendance = 'N';
		$vo->m_creative = 'N';
		$vo->m_feedback = null;
		$vo->m_sign = null;
		$vo->l_sign_date = null;
		$vo->a_sign_date = null;
		$vo->m_sign_date = null;
		$vo->m_name = null;
		$vo->overall_progression = null;
		$vo->a_booklet_abu = null;
		$vo->a_booklet_end = null;
		$vo->a_booklet_hns = null;
		$vo->a_booklet_sm = null;
		$vo->a_maths_level1 = null;
		$vo->a_ict_level1 = null;
		$vo->a_maths_level2 = null;
		$vo->a_ict_level2 = null;
		$vo->a_eng_read_level1 = null;
		$vo->a_eng_write_level1 = null;
		$vo->a_eng_speak_listen_level1 = null;
		$vo->a_eng_read_level2 = null;
		$vo->a_eng_write_level2 = null;
		$vo->a_eng_speak_listen_level2 = null;
		$vo->a_fs_maths_ict_target_date = null;
		$vo->a_fs_eng_target_date = null;
		$vo->a_next_visit_time = null;

		return $vo;
	}

	public static function getAssessorReviewFormVO()
	{
		//return self::getFullReviewFormVO();
		$vo = new stdClass();

		$vo->review_id = null;
		$vo->tr_id = null;
		$vo->a_visit_date = null;
		$vo->a_visit_start = null;
		$vo->a_visit_end = null;
		$vo->a_record_of_comm = null;
		$vo->a_qual_last_visit = null;
		$vo->a_fs_last_visit = null;
		$vo->a_qual_today = null;
		$vo->a_fs_today = null;
		$vo->a_qual_next_visit = null;
		$vo->a_fs_next_visit = null;
		$vo->a_qual_act_next_visit = null;
		$vo->a_fs_act_next_visit = null;
		$vo->a_fdbck_sgp = null;
		$vo->a_feedback = null;
		$vo->a_chngs_to_notify = null;
		$vo->a_tests_sat = null;
		$vo->a_sign = null;
		$vo->a_next_visit = null;
		$vo->m_punc = 'N';
		$vo->m_well_pres = 'N';
		$vo->m_respectful = 'N';
		$vo->m_team_player = 'N';
		$vo->m_show_pd = 'N';
		$vo->m_demo_skills = 'N';
		$vo->m_show_conf = 'N';
		$vo->m_attendance = 'N';
		$vo->m_creative = 'N';
		$vo->m_feedback = null;
		$vo->m_sign = null;
		$vo->a_sign_date = null;
		$vo->m_sign_date = null;
		$vo->m_name = null;
		$vo->overall_progression = null;
		$vo->a_booklet_abu = null;
		$vo->a_booklet_end = null;
		$vo->a_booklet_hns = null;
		$vo->a_booklet_sm = null;
		$vo->a_maths_level1 = null;
		$vo->a_ict_level1 = null;
		$vo->a_maths_level2 = null;
		$vo->a_ict_level2 = null;
		$vo->a_eng_read_level1 = null;
		$vo->a_eng_write_level1 = null;
		$vo->a_eng_speak_listen_level1 = null;
		$vo->a_eng_read_level2 = null;
		$vo->a_eng_write_level2 = null;
		$vo->a_eng_speak_listen_level2 = null;
		$vo->a_fs_maths_ict_target_date = null;
		$vo->a_fs_eng_target_date = null;
		$vo->a_next_visit_time = null;

		return $vo;
	}

	public static function getLearnerReviewFormVO()
	{
		$vo = new stdClass();

		$vo->review_id = null;
		$vo->tr_id = null;
		$vo->l_equ_div = null;
		$vo->l_training= null;
		$vo->l_future_asp = null;
		$vo->l_support = null;
		$vo->l_feel_safe = 'N';
		$vo->l_had_acc = 'N';
		$vo->l_hav_changes = 'N';
		$vo->l_have_health_issues = 'N';
		$vo->l_feedback = null;
		$vo->l_sign = null;
		$vo->l_sign_date = null;

		return $vo;
	}
	public static function getManagerReviewFormVO()
	{
		$vo = new stdClass();

		$vo->review_id = null;
		$vo->tr_id = null;
		$vo->m_punc = 'N';
		$vo->m_well_pres = 'N';
		$vo->m_respectful = 'N';
		$vo->m_team_player = 'N';
		$vo->m_show_pd = 'N';
		$vo->m_demo_skills = 'N';
		$vo->m_show_conf = 'N';
		$vo->m_attendance = 'N';
		$vo->m_creative = 'N';
		$vo->m_feedback = null;
		$vo->m_sign = null;
		$vo->m_sign_date = null;
		$vo->m_name = null;

		return $vo;
	}
}