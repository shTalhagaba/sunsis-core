<?php
class active_campaign_bulk implements IAction
{
    public function execute(PDO $link)
    {
        $refresh = isset($_REQUEST['refresh']) ? $_REQUEST['refresh'] : '0';

        if($refresh==1)
        {
            $this->refreshActiveCampaign($link);
            DAO::execute($link, "UPDATE ac_bulk_update SET created = NOW() WHERE created IS NULL;");
            $this->updateSystem($link);
        }

        include('tpl_active_campaign_bulk.php');
    }

    public function refreshActiveCampaign($link)
    {
        DAO::execute($link, "SET group_concat_max_len=25000;");
        $ids = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(active_campaign_id SEPARATOR \"&ids[]=\") FROM tr WHERE status_code = 1 AND active_campaign_id IS NOT NULL;");
        $ids = "ids[]=" . $ids;
        $this->updateLearners($link, $this->getLearners("https://city-skills.api-us1.com/api/3/contacts?{$ids}"));
    }

    public function updateLearners($link, $response)
    {
        $result = json_decode($response, true);

        if($_SESSION['user']->username=="rich0001")
        {
            //pre($result);
        }

        $html="";
        $employers = array();
        foreach($result as $accounts)
        {
            for($i = 0; $i<sizeof($accounts); $i++)
            {
                $learner = new ACLearner();
                $learner->id = (isset($accounts[$i]['id']))?$accounts[$i]['id']:NULL;
            
                $fields = @json_decode($this->getLearners($accounts[$i]['links']['fieldValues'].""),true);

                if($_SESSION['user']->username=="rich0001" and $fields['fieldValues'][0]['contact']==8658)
                {
                    //$fields = @json_decode($this->getLearners('https://city-skills.api-us1.com/api/3/fields?limit=100&offset=200'),true);
                    //$fields = @json_decode($this->getLearners($accounts[$i]['links']['fieldValues'].""),true);
                    //pre($fields);
                    //pre($this->getLearners($accounts[0]['links']['fieldValues'].""));
                }

                if(is_array($fields))
                {
                    foreach($fields as $field)
                    {
                        foreach($field as $field1)
                        {
                            if($field1['field']==167)
                                $learner->ni = substr(str_replace("-","",str_replace(" ","",$field1['value'])),0,9);
                            elseif($field1['field']==89)
                                $learner->gender = substr($field1['value'],0,1);
                            elseif($field1['field']==39)
                                $learner->home_postcode = $field1['value'];
                            elseif($field1['field']==38)
                                $learner->uln = $field1['value'];
                            elseif($field1['field']==34)
                                $learner->dob = $field1['value'];
                            elseif($field1['field']==35)
                            {
                                $address = explode(",",$field1['value']);        
                                $learner->home_address_line_1 = (isset($address[0])?$address[0]:"");    
                                $learner->home_address_line_2 = (isset($address[1])?$address[1]:"");    
                                $learner->home_address_line_3 = (isset($address[2])?$address[2]:"");    
                                $learner->home_address_line_4 = (isset($address[3])?$address[3]:"");    
                            }
                            elseif($field1['field']==198)
                                $learner->most_recent_otj_progress_review = $field1['value'];
                            elseif($field1['field']==200)
                                $learner->most_recent_otj_attendance = $field1['value'];
                            elseif($field1['field']==202)
                                $learner->total_recorded_otj = $field1['value'];
                            elseif($field1['field']==130)
                                $learner->development_coach = $field1['value'];
                            elseif($field1['field']==130)
                                $learner->development_coach = $field1['value'];
                            elseif($field1['field']==122)
                                $learner->latest_attendance = $field1['value'];
                            elseif($field1['field']==123)
                                $learner->latest_attendance_type = $field1['value'];
                            elseif($field1['field']==71)
                                $learner->most_recent_review_date = $field1['value'];
                            elseif($field1['field']==94)
                                $learner->most_recent_otj_my_portfolio = $field1['value'];
                            elseif($field1['field']==125)
                                $learner->gateway_date = Date::toMySQL($field1['value']);
                            elseif($field1['field']==18)
                                $learner->assessor = $field1['value'];
                            elseif($field1['field']==173)
                                $learner->scheduled_lessons = $field1['value'];
                            elseif($field1['field']==171)
                                $learner->attendance = $field1['value'];
                            elseif($field1['field']==174)
                                $learner->late_total = $field1['value'];
                            elseif($field1['field']==172)
                                $learner->unauthorised_absence = $field1['value'];
                            elseif($field1['field']==175)
                                $learner->sickness_total = $field1['value'];
                            elseif($field1['field']==85)
                                $learner->line_manager = $field1['value'];
                            elseif($field1['field']==178)
                                $learner->line_manager_email = $field1['value'];
                            elseif($field1['field']==37)
                                $learner->why_are_you_doing_this_apps = $field1['value'];
                            elseif($field1['field']==70)
                                $learner->most_recent_review_comment = $field1['value'];
                            elseif($field1['field']==84)
                                $learner->most_recent_employer_comment =  mb_convert_encoding($field1['value'], 'UTF-8', 'auto');
                            elseif($field1['field']==95)
                                $learner->most_recent_city_skills_comments = $field1['value'];
                            elseif($field1['field']==312)
                                $learner->review_targets = $field1['value'];
                            elseif($field1['field']==135)
                                $learner->next_progress_review = $field1['value'];
                                
                        }    
                    }
                }

                DAO::saveObjectToTable($link, "ac_bulk_update", $learner);
            }
        }
    }

    public function updateSystem($link)
    {
        $most_recent = DAO::getSingleValue($link, "select created from ac_bulk_update order by created desc limit 1");
        $ac_ids = DAO::getSingleColumn($link, "SELECT id from ac_bulk_update where created = '$most_recent'");
        foreach($ac_ids as $ac_id)
        {
            $tr_id = DAO::getSingleValue($link, "select id from tr where active_campaign_id = '$ac_id'");
            //DAO::execute($link, "UPDATE tr SET active_campaign_id = NULL WHERE status_code = 1 AND active_campaign_id NOT IN (SELECT id FROM ac_bulk_update WHERE created = '$most_recent');");
            $ac_learner = DAO::getObject($link, "select * from ac_bulk_update where id = '$ac_id' and created = '$most_recent'");
            $review_date = $ac_learner->most_recent_review_date;
            $otj_date = $ac_learner->latest_attendance;
            $attendance_date = $ac_learner->latest_attendance;
            $attendance_type = $ac_learner->latest_attendance_type;
            $assessor = $ac_learner->development_coach;
            $total_recorded_otj = $ac_learner->total_recorded_otj;
            $ac_id = $ac_learner->id;
    
            // Update gateway date
            $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
            $tr->gateway_date = $ac_learner->gateway_date;
            $assessor_id = DAO::getSingleValue($link, "select id from users where type = 3 and concat(firstnames, ' ', surname) = '$ac_learner->development_coach'");
            if($assessor_id)
                $tr->assessor = $assessor_id;

            $tr->scheduled_lessons = $ac_learner->scheduled_lessons;
            $tr->attendance = $ac_learner->attendance;
            $tr->late_total = $ac_learner->late_total;
            $tr->unauthorised_absence = $ac_learner->unauthorised_absence;
            $tr->sickness_total = $ac_learner->sickness_total;
            $tr->why_are_you_doing_this_apps = $ac_learner->why_are_you_doing_this_apps;
            $tr->most_recent_review_comment = $ac_learner->most_recent_review_comment;
            $tr->most_recent_employer_comment = mb_convert_encoding($ac_learner->most_recent_employer_comment,'UTF-8');
            $tr->most_recent_city_skills_comments = $ac_learner->most_recent_city_skills_comments;
            $tr->review_targets = $ac_learner->review_targets;
            $tr->next_progress_review = $ac_learner->next_progress_review;
            $tr->line_manager = @$ac_learner->line_manager;
            $tr->line_manager_email = @$ac_learner->line_manager_email;

            $tr->save($link);

            // Review
            $found = DAO::getSingleValue($link, "select count(*) from assessor_review where tr_id = '$tr_id' and meeting_date = '$review_date'");
            if($found>0)
            {

            }
            else
            {
                $assessor_username = DAO::getSingleValue($link, "select username from users where CONCAT(firstnames, ' ', surname) = '$assessor'");
                $review = new stdClass();
                $review->id = NULL;
                $review->tr_id = $tr_id;
                $review->due_date = $review_date;
                $review->meeting_date = $review_date;
                $review->assessor = $assessor_username;
                $review->place = "Online";
                $review->paperwork_received = 5;
                DAO::execute($link, "delete from assessor_review where meeting_date is null and tr_id = '$tr_id'");
                DAO::saveObjectToTable($link, 'assessor_review', $review);
            }

            // OTJ
            $found = DAO::getSingleValue($link, "select sum(duration_hours) from otj where tr_id = '$tr_id'");
            if($found<$total_recorded_otj)
            {
                $otj = new stdClass();
                $otj->tr_id = $tr_id;
                $otj->date = date("Y-m-d");
                $otj->type = 4;
                $otj->duration_hours = $total_recorded_otj - $found; 
                DAO::saveObjectToTable($link, 'otj', $otj);
            }

            // Attendance
            if(Date::isDate($attendance_date) and ($attendance_type=="A" or $attendance_type=="L" or $attendance_type=="U" or $attendance_type=="S"))
            {
                $found = DAO::getSingleValue($link, "SELECT COUNT(*) FROM register_entries LEFT JOIN lessons ON lessons.id = register_entries.lessons_id WHERE `date` = '$attendance_date' AND pot_id = '$tr_id'");
                if($found>0)
                {

                }
                else
                {
                    if($attendance_type=="A")
                        $attendance = 1;
                    elseif($attendance_type=="L")
                        $attendance = 2;
                    elseif($attendance_type=="U")
                        $attendance = 5;
                    elseif($attendance_type=="S")
                        $attendance = 3;

                    $lesson_id = DAO::getSingleValue($link, "SELECT id FROM lessons WHERE `date` = '$attendance_date' AND groups_id IN (SELECT groups_id FROM group_members WHERE tr_id = '$tr_id')");
                    if($lesson_id > 0 )
                    {
                        $att = new RegisterEntry();
                        $att->id = NULL;
                        $att->lessons_id = $lesson_id;
                        $att->pot_id = $tr_id;
                        $att->entry = $attendance;
                        $att->created = date("Y-m-d H:i:s");
                        $att->lesson_contribution = 0;
                        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
                        $att->school_id = $tr->employer_id;
                        DAO::saveObjectToTable($link, 'register_entries', $att);
                    }
                }
            }
        }
    }
 
    public function getLearners($url)
    {
        $headers = $this->getHeaders();
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_HEADER, 0); // include the headers in the output
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($curl);
        curl_close($curl);
        return($result);
    }

    public function getHeaders()
    {
        return $headers = [
            "Content-type:application/json",
            "Api-Token: efba70d3e0ea42f2721f7ac4497d34fce51946a2c0da44f410457db478e616fff764915f",
            "Accept: application/json;"
        ];
    }

    public function getCustomFields($url)
    {
        $headers = $this->getHeaders();
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_HEADER, 0); // include the headers in the output
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);
        return($result);

    }
}