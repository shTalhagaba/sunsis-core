<?php
class active_campaign_learner implements IAction
{
    public function execute(PDO $link)
    {
        $emp_id = isset($_REQUEST['emp_id']) ? $_REQUEST['emp_id'] : '';
        $search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
        $update = isset($_REQUEST['update']) ? $_REQUEST['update'] : '';

        if($emp_id>0 and $update=='')
        {
            $ac_learner = DAO::getObject($link, "select * from ac_learners where id = '$emp_id'");
            $learner = new User();
            $learner->id = $ac_learner->id;
            $learner->username = $ac_learner->username;
            $learner->firstnames = $ac_learner->firstnames;
            $learner->surname = $ac_learner->surname;
            $learner->employer_id = $ac_learner->employer_id;
            $learner->employer_location_id = $ac_learner->employer_location_id;
            $learner->record_status = $ac_learner->record_status;
            $learner->web_access = $ac_learner->web_access;
            $learner->dob = $ac_learner->dob;
            $learner->ni = $ac_learner->ni;
            $learner->gender = $ac_learner->gender;
            $learner->ethnicity = $ac_learner->ethnicity;
            $learner->home_address_line_1 = $ac_learner->home_address_line_1;
            $learner->home_address_line_2 = $ac_learner->home_address_line_2;
            $learner->home_address_line_3 = $ac_learner->home_address_line_3;
            $learner->home_address_line_4 = $ac_learner->home_address_line_4;
            $learner->home_telephone = $ac_learner->home_telephone;
            $learner->home_email = $ac_learner->home_email;
            $learner->type = $ac_learner->type;
            $learner->save($link);
            $this->CreateInSunesis($link, $emp_id);
        }
        elseif($emp_id>0 and $update>0)
        {
            $ac_learner = DAO::getObject($link, "select * from ac_learners where id = '$emp_id'");
            $review_date = $ac_learner->most_recent_review_date;
            $otj_date = $ac_learner->latest_attendance;
            $attendance_date = $ac_learner->latest_attendance;
            $attendance_type = $ac_learner->latest_attendance_type;
            $assessor = $ac_learner->development_coach;
            $total_recorded_otj = $ac_learner->total_recorded_otj;
            $ni = $ac_learner->ni;
            $uln = $ac_learner->uln;
            $ac_id = $ac_learner->id;

            $tr_id = DAO::getSingleValue($link, "select id from tr where (ni = '$ni' or uln = '$uln')");
            if($tr_id=="")
            {
                pre("This learner needs to be enrolled");
            }
            else
            {
                DAO::execute($link, "update tr set active_campaign_id = '$ac_id' where id = '$tr_id' and active_campaign_id is null");
            }

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
            /*else
            {
                $otj = new stdClass();
                $otj->tr_id = $tr_id;
                $otj->date = $otj_date;
                $otj->type = 4;
                $otj->duration_hours = $ac_learner->most_recent_otj_my_portfolio + $ac_learner->most_recent_otj_attendance + $ac_learner->most_recent_otj_progress_review; 
                DAO::saveObjectToTable($link, 'otj', $otj);
            }*/

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

        if($search!="")
        {
            DAO::execute($link, "truncate ac_learners");
            $this->updateLearners($link, $this->getLearners("https://city-skills.api-us1.com/api/3/contacts?search={$search}"));
            //DAO::execute($link, "SET group_concat_max_len=25000;");
            //$ids = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(active_campaign_id SEPARATOR \"&ids[]=\") FROM tr WHERE status_code = 1 AND active_campaign_id IS NOT NULL;");
            //$ids = "ids[]=" . $ids;
            //$this->updateLearners($link, $this->getLearners("https://city-skills.api-us1.com/api/3/contacts?{$ids}"));
        }

        include('tpl_active_campaign_learner.php');
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

    public function updateLearners($link, $response)
    {
        $result = json_decode($response, true);
        $html="";
        $employers = array();
        foreach($result as $accounts)
        {
            for($i = 0; $i<sizeof($accounts); $i++)
            {
                $learner = new ACLearner();
                $learner->type = 5;
                if(!isset($accounts[$i]['id']))
                    continue;
                $learner->id = (isset($accounts[$i]['id']))?$accounts[$i]['id']:NULL;
                $learner->home_email = $accounts[$i]['email'];
                $learner->home_telephone = substr($accounts[$i]['phone'],0,20);
                $firstname = preg_replace('/[^(\x20-\x7F)]*/','', $accounts[$i]['firstName']);
                $learner->firstnames = $firstname;
                $surname = preg_replace('/[^(\x20-\x7F)]*/','', $accounts[$i]['lastName']);
                $learner->surname = $surname;

                $emp = addslashes((string)$accounts[$i]['orgname']);
                $emp_id = DAO::getSingleValue($link, "select id from organisations where legal_name like '$emp'");
                if($emp_id)
                {
                    $loc_id = DAO::getSingleValue($link, "select id from locations where organisations_id = '$emp_id'");
                }
                else
                {
                    $emp_id = 2042;
                    $loc_id = 1774;
                }
                $learner->employer_id = $emp_id;
                $learner->employer_location_id = $loc_id;
                $learner->username = (isset($accounts[$i]['id']))?$accounts[$i]['id']:NULL;
            
                $fields = json_decode($this->getLearners($accounts[$i]['links']['fieldValues'].""),true);
                //pre($fields);

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
                                
                        }    
                    }
                }

                DAO::saveObjectToTable($link, "ac_learners", $learner);
            }
        }
    }

    public function CreateInSunesis($link, $id)
    {
        pre("Under development");
        $ac_employer = DAO::getObject($link, "select * from ac_employers where Id = '$id'");
        $emp = new StdClass();
        $loc = new StdClass();
        $emp->id = NULL;
        $emp->organisation_type = 2;
        $emp->legal_name = $ac_employer->Name;
        $emp->trading_name = $ac_employer->Name;
        $emp->short_name = substr($ac_employer->Name, 0 , 20);
        $emp->active = 1;
        $res = DAO::saveObjectToTable($link, 'organisations', $emp);
        $loc->id = NULL;
        $loc->organisations_id = DAO::getSingleValue($link, "select max(id) from organisations");
        $loc->is_legal_address = 1;
        $loc->full_name = "Main Site";
        $loc->short_name = "main site";
        $loc->address_line_1 = $ac_employer->Add1;
        $loc->address_line_2 = $ac_employer->Add2;
        $loc->address_line_3 = $ac_employer->Add3;
        $loc->address_line_4 = $ac_employer->Add4;
        $loc->full_name = "Main Site";
        $loc->postcode = $ac_employer->Postcode;
        $loc->line1 = $ac_employer->Add1;
        $loc->line2 = $ac_employer->Add2;
        $loc->line3 = $ac_employer->Add3;
        $loc->line4 = $ac_employer->Add4;
        $loc->contact_name = $ac_employer->PC_Name;
        $loc->contact_mobile = $ac_employer->PC_Mobile;
        $loc->contact_email = $ac_employer->PC_Email;
        $res = DAO::saveObjectToTable($link, 'locations', $loc);

        DAO::execute($link, "update ac_employers inner join organisations on organisations.legal_name = ac_employers.Name set Sunesis = organisations.id");
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