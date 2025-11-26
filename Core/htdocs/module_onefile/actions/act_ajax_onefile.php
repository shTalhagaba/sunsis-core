<?php
class ajax_onefile extends ActionController
{
	public function indexAction(PDO $link)
	{
        $authorised = $_SESSION['user']->isAdmin();
		if (!$authorised) 
        {
			throw new UnauthorizedException();
		}
        require_once('./lib/onefile/Onefile.php');
    }

    public function getOnefileCustomerIDAction(PDO $link)
    {
        $onefile = new Onefile();

        $response = $onefile->api_Customer();

        if($response->getHttpCode() == 200)
        {
            $temp = new stdClass();
            $temp->key = "onefile.customer";
            $temp->value = $response->getBody();
            DAO::saveObjectToTable($link, "onefile", $temp);
        }

        echo $response->getHttpCode();
    }

    public function getOnefileUsersAction(PDO $link)
    {
        $sunesis_user_type = isset($_REQUEST['sunesis_user_type']) ? $_REQUEST['sunesis_user_type'] : '';
        $organisation_id = isset($_REQUEST['organisation_id']) ? $_REQUEST['organisation_id'] : 0;
        if($sunesis_user_type == '')
        {
            return;
        }

        $onefile = new Onefile();

        $role = '';
        if($sunesis_user_type == User::TYPE_ASSESSOR || $sunesis_user_type == User::TYPE_TUTOR)
        {
            $role = Onefile::TYPE_ASSESSOR_TUTOR;
        }
        elseif($sunesis_user_type == User::TYPE_VERIFIER)
        {
            $role = Onefile::TYPE_IV_IQA;
        }

        $response = $onefile->api_UserSearch([
            'Role' => $role,
            'OrganisationID' => $organisation_id,
            'LastModified' => '2021-03-28T14:23:29.195Z',
        ]);
	$responseBody = $response->getBody();
        if($sunesis_user_type == User::TYPE_ASSESSOR || $sunesis_user_type == User::TYPE_TUTOR)
        {
            $role = Onefile::TYPE_TRAINEE_ASSESSOR;
            $response2 = $onefile->api_UserSearch([
                'Role' => $role,
                'OrganisationID' => $organisation_id,
                'LastModified' => '2021-03-28T14:23:29.195Z',
            ]);
            if($response2->getHttpCode() == 200 && $_SESSION['user']->username == 'aperspective')
            {
                $mainUsers = json_decode($responseBody);
                $traineeUsers = json_decode($response2->getBody());
                if(count($traineeUsers) > 0)
                {
                    $responseBody = array_merge($mainUsers, $traineeUsers);
                }
		$responseBody = json_encode($responseBody);
            }
        }

        if($response->getHttpCode() == 200)
        {
            $temp = new stdClass();
            $temp->key = "users_{$sunesis_user_type}";
            $temp->value = $responseBody;
            DAO::saveObjectToTable($link, "onefile", $temp);
        }
    }

    public function createUserInOnefileAction(PDO $link)
    {
        $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
        $organisation_id = isset($_REQUEST['organisation_id']) ? $_REQUEST['organisation_id'] : 0;
        if($user_id == '')
        {
            return;
        }
        $user = User::loadFromDatabaseById($link, $user_id);

        $onefile = new Onefile();

        $role = '';
        if($user->type == User::TYPE_ASSESSOR || $user->type == User::TYPE_TUTOR)
        {
            $role = Onefile::TYPE_ASSESSOR_TUTOR;
        }
        elseif($user->type == User::TYPE_VERIFIER)
        {
            $role = Onefile::TYPE_IV_IQA;
        }
        if($role == '')
        {
            throw new Exception("The user type is not available in Onefile.");
        }

        $address = $user->work_address_line_1 != '' ? $user->work_address_line_1 : '';
        $address .= $user->work_address_line_2 != '' ? PHP_EOL . $user->work_address_line_2 : '';
        $address .= $user->work_address_line_3 != '' ? PHP_EOL . $user->work_address_line_3 : '';
        $address .= $user->work_address_line_4 != '' ? PHP_EOL . $user->work_address_line_4 : '';
        $address .= $user->work_postcode != '' ? PHP_EOL . $user->work_postcode : '';

        $response = $onefile->api_UserCreate([
            'OrganisationID' => $organisation_id,
            'FirstName' => $user->firstnames,
            'LastName' => $user->surname,
            'Username' => $user->username,
            'Role' => $role,
            'Email' => $user->home_email != '' ? $user->home_email : $user->work_email,
            'MobileNumber' => $user->home_mobile != '' ? $user->home_mobile : $user->work_mobile,
            'Telephone' => $user->home_telephone != '' ? $user->home_telephone : $user->work_telephone,            
            'WorkAddress' => $address,
            'MISID' => $user->id,
        ]);

        echo $response->getHttpCode();
    }

    public function updateLearnerInOnefileAction(PDO $link)
    {   
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
        if($user_id == '' || $tr_id == '')
        {
            throw new Exception("Missing querystring argument: user_id, tr_id");
        }
        $user = User::loadFromDatabaseById($link, $user_id);
        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if($tr->onefile_id == '')
        {
            throw new Exception("This learner cannot be updated in Onefile.");
        }

        $onefile = new Onefile();

        $home_address = $tr->home_address_line_1 != '' ? $tr->home_address_line_1 : '';
        $home_address .= $tr->home_address_line_2 != '' ? PHP_EOL . $tr->home_address_line_2 : '';
        $home_address .= $tr->home_address_line_3 != '' ? PHP_EOL . $tr->home_address_line_3 : '';
        $home_address .= $tr->home_address_line_4 != '' ? PHP_EOL . $tr->home_address_line_4 : '';
        $home_address .= $tr->home_postcode != '' ? PHP_EOL . $tr->home_postcode : '';

        $work_address = $tr->work_address_line_1 != '' ? $tr->work_address_line_1 : '';
        $work_address .= $tr->work_address_line_2 != '' ? PHP_EOL . $tr->work_address_line_2 : '';
        $work_address .= $tr->work_address_line_3 != '' ? PHP_EOL . $tr->work_address_line_3 : '';
        $work_address .= $tr->work_address_line_4 != '' ? PHP_EOL . $tr->work_address_line_4 : '';
        $work_address .= $tr->work_postcode != '' ? PHP_EOL . $tr->work_postcode : '';

        $data = [
            'ID' => $tr->onefile_id,
            'Username' => $tr->onefile_username,
            'OrganisationID' => $_POST['onefile_organisation_id'],
            'FirstName' => $tr->firstnames,
            'LastName' => $tr->surname,
            'Email' => $_POST['onefile_email'],
            'Role' => Onefile::TYPE_LEARNER,
            'DefaultAssessorID' => $_POST['onefile_assessor_linked'],
            'ClassroomID' => $_POST['onefile_classroom_id'],
            'PlacementID' => $_POST['onefile_placement_linked'],
            'CompanyName' => $tr->legal_name,
            'MISID' => $tr->username . '_' . $tr->id,
            'EpisodeName' => $_POST['onefile_episode_name'],
            'CentreRegister' => $_POST['onefile_centre_register'],
            'StartOn' => date("Y-m-d\TH:i:s.000\Z", strtotime("{$tr->start_date} 00:00:00")),
            'PlannedEndDate' => date("Y-m-d\TH:i:s.000\Z", strtotime("{$tr->target_date} 00:00:00"))
        ];

        if($tr->home_mobile != '')
        {
            $data['MobileNumber'] = $tr->home_mobile;
        }
        if($tr->home_telephone != '')
        {
            $data['Telephone'] = $tr->home_telephone;
        }
        if($home_address != '')
        {
            $data['HomeAddress'] = $home_address;
        }
        if($work_address != '')
        {
            $data['WorkAddress'] = $work_address;
        }
        if($tr->dob != '')
        {
            $data['DOB'] = date("Y-m-d\TH:i:s.000\Z", strtotime("{$tr->dob} 00:00:00"));
        }
        if($tr->uln != '')
        {
            $data['ULN'] = $tr->uln;
        }
        if($tr->ni != '')
        {
            $data['NINO'] = $tr->ni;
        }
        if($tr->ethnicity != '')
        {
            $data['L12'] = $tr->ethnicity;
        }
        if($tr->gender != '')
        {
            $data['L13'] = $tr->gender;
        }
        if($user->l14 != '')
        {
            $data['L14'] = $user->l14;

	    if($data['L14'] == '1')
            {
                $data['PrimaryLLDDCode'] = $user->primary_lldd;
                $llddCats = explode(',', $user->lldd_cat);

                $data['SecondaryLLDDCodes'] = array_diff($llddCats, [$user->primary_lldd]);
            }
        }

        $response = $onefile->api_UserUpdate( $data, $tr->onefile_id );

        if($response->getHttpCode() == 204)
        {
            // successful
        }
        elseif($response->getHttpCode() == 400)
        {
            throw new Exception("400: Bad Request. " . $response->getBody());
        }
        elseif($response->getHttpCode() == 401)
        {
            throw new Exception("401: Unauthorized");
        }
        elseif($response->getHttpCode() == 403)
        {
            throw new Exception("403: Forbidden");
        }
        elseif($response->getHttpCode() == 500)
        {
            throw new Exception("500: Internal Server Error");
        }
        else
        {
            throw new Exception($response->getHttpCode() . " Unknown error.");
        }

	if(isset($_POST['chkOnefileStandards']))
        {
            // already linked aims
            $onefile_learning_aim_ids = DAO::getLookupTable($link, "SELECT auto_id, onefile_learning_aim_id FROM student_qualifications WHERE tr_id = '{$tr->id}' ");
            foreach($_POST['chkOnefileStandards'] AS $auto_id)
            {
                if( array_key_exists($auto_id, $onefile_learning_aim_ids) )
                {
                    // if aim was not linked while creating
                    if( $onefile_learning_aim_ids[$auto_id] == '' )
                    {
                        $this->createAims($link, $tr, [$auto_id]);
                    }
                    // else update the aim information
                    else
                    {
                        $this->pushLearningAimsInformation($link, $tr, $onefile_learning_aim_ids[$auto_id]);
                    }
                }
            }
        }

	// $assessor_reviews = DAO::getSingleColumn($link, "SELECT id FROM assessor_review WHERE tr_id = '{$tr->id}'");
        // if(count($assessor_reviews) > 0)
        // {
        //     $this->createVisits($link, $tr->onefile_id, $_POST['onefile_organisation_id'], $_POST['onefile_assessor_linked'], $assessor_reviews);
        // }

        // log the user actions
        $this->logUserActions($link, $tr, "Learner updated in Onefile", $data);

        echo $response->getHttpCode();
    }

    public function createLearnerInOnefileAction(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
        if($user_id == '' || $tr_id == '')
        {
            throw new Exception("Missing querystring argument: user_id, tr_id");
        }
        $user = User::loadFromDatabaseById($link, $user_id);
        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);

        $onefile = new Onefile();

        $home_address = $tr->home_address_line_1 != '' ? $tr->home_address_line_1 : '';
        $home_address .= $tr->home_address_line_2 != '' ? PHP_EOL . $tr->home_address_line_2 : '';
        $home_address .= $tr->home_address_line_3 != '' ? PHP_EOL . $tr->home_address_line_3 : '';
        $home_address .= $tr->home_address_line_4 != '' ? PHP_EOL . $tr->home_address_line_4 : '';
        $home_address .= $tr->home_postcode != '' ? PHP_EOL . $tr->home_postcode : '';

        $work_address = $tr->work_address_line_1 != '' ? $tr->work_address_line_1 : '';
        $work_address .= $tr->work_address_line_2 != '' ? PHP_EOL . $tr->work_address_line_2 : '';
        $work_address .= $tr->work_address_line_3 != '' ? PHP_EOL . $tr->work_address_line_3 : '';
        $work_address .= $tr->work_address_line_4 != '' ? PHP_EOL . $tr->work_address_line_4 : '';
        $work_address .= $tr->work_postcode != '' ? PHP_EOL . $tr->work_postcode : '';

        $data = [
            'OrganisationID' => $_POST['onefile_organisation_id'],
            'FirstName' => $tr->firstnames,
            'LastName' => $tr->surname,
            'Email' => $_POST['onefile_email'],
            'Role' => Onefile::TYPE_LEARNER,
            'DefaultAssessorID' => $_POST['onefile_assessor_linked'],
            'ClassroomID' => $_POST['onefile_classroom_id'],
            'PlacementID' => $_POST['onefile_placement_linked'],
            'CompanyName' => $tr->legal_name,
            'MISID' => $tr->username . '_' . $tr->id,
            'EpisodeName' => $_POST['onefile_episode_name'],
            // 'CentreRegister' => $_POST['onefile_centre_register'],
            'StartOn' => date("Y-m-d\TH:i:s.000\Z", strtotime("{$tr->start_date} 00:00:00")),
            'PlannedEndDate' => date("Y-m-d\TH:i:s.000\Z", strtotime("{$tr->target_date} 00:00:00"))
        ];

	if($_POST['onefile_centre_register'] != '')
        {
            $centerRegister = Date::toMySQL($_POST['onefile_centre_register']);
            $centerRegister = date("Y-m-d\TH:i:s.000\Z", strtotime("{$centerRegister} 00:00:00"));
            $data['CentreRegister'] = $centerRegister;
        }

        if($tr->home_mobile != '')
        {
            $data['MobileNumber'] = $tr->home_mobile;
        }
        if($tr->home_telephone != '')
        {
            $data['Telephone'] = $tr->home_telephone;
        }
        if($home_address != '')
        {
            $data['HomeAddress'] = $home_address;
        }
        if($work_address != '')
        {
            $data['WorkAddress'] = $work_address;
        }
        if($tr->dob != '')
        {
            $data['DOB'] = date("Y-m-d\TH:i:s.000\Z", strtotime("{$tr->dob} 00:00:00"));
        }
        if($tr->uln != '')
        {
            $data['ULN'] = $tr->uln;
        }
        if($tr->ni != '')
        {
            $data['NINO'] = $tr->ni;
        }
        if($tr->ethnicity != '')
        {
            $data['L12'] = $tr->ethnicity;
        }
        if($tr->gender != '')
        {
            $data['L13'] = $tr->gender;
        }
        if($user->l14 != '')
        {
            $data['L14'] = $user->l14;

            if($data['L14'] == '1')
            {
                $data['PrimaryLLDDCode'] = $user->primary_lldd;
                $llddCats = explode(',', $user->lldd_cat);

                $data['SecondaryLLDDCodes'] = array_diff($llddCats, [$user->primary_lldd]);
            }
        }

        $response = $onefile->api_UserCreate( $data );

        if($response->getHttpCode() == 200)
        {
            $new_user_details = $response->getBody();
            $new_user_details = json_decode($new_user_details);
            
            $tr->onefile_id = $new_user_details->ID;
            $tr->onefile_username = $new_user_details->Username;
            $tr->save($link);
        }
        elseif($response->getHttpCode() == 400)
        {
            throw new Exception("400: Bad Request. " . $response->getBody());
        }
        elseif($response->getHttpCode() == 401)
        {
            throw new Exception("401: Unauthorized");
        }
        elseif($response->getHttpCode() == 403)
        {
            throw new Exception("403: Forbidden");
        }
        elseif($response->getHttpCode() == 500)
        {
            throw new Exception("500: Internal Server Error");
        }
        else
        {
            throw new Exception("Unknown error.");
        }

        // log the user actions
        $this->logUserActions($link, $tr, "Learner created in Onefile", $data);

        // assign the framework template if Sunesis framework is linked
        // $onefile_fwk_tpl_id = DAO::getSingleValue($link, "SELECT frameworks.onefile_fwk_tpl_id FROM frameworks WHERE frameworks.id IN (SELECT student_frameworks.id FROM student_frameworks WHERE student_frameworks.tr_id = '{$tr->id}')");
        // if($onefile_fwk_tpl_id != '')
        // {
        //     $response2 = $onefile->api_FrameworkTemplateAssign( [], $tr->onefile_id, $onefile_fwk_tpl_id );
        //     if($response2->getHttpCode() == 204)
        //     {
        //         throw new Exception("200: All done");
        //     }
        //     elseif($response2->getHttpCode() == 400)
        //     {
        //         throw new Exception("400: Bad Request");
        //     }
        //     elseif($response2->getHttpCode() == 401)
        //     {
        //         throw new Exception("401: Unauthorized");
        //     }
        //     elseif($response2->getHttpCode() == 403)
        //     {
        //         throw new Exception("403: Forbidden");
        //     }
        //     elseif($response2->getHttpCode() == 500)
        //     {
        //         throw new Exception("500: Internal Server Error");
        //     }
        //     else
        //     {
        //         throw new Exception("Unknown error.");
        //     }
        // }

        if(isset($_REQUEST['chkOnefileStandards']) && is_array($_REQUEST['chkOnefileStandards']) && count($_REQUEST['chkOnefileStandards']) > 0)
        {
            $rows_updated = $this->createAims($link, $tr, $_REQUEST['chkOnefileStandards']);

            if($rows_updated > 0)
            {
                $this->pushLearningAimsInformation($link, $tr);
            }
        }

	if(isset($_POST['push_reviews_to_onefile']) && $_POST['push_reviews_to_onefile'] == 1)
        {
            $assessor_reviews = DAO::getSingleColumn($link, "SELECT id FROM assessor_review WHERE tr_id = '{$tr->id}'");
            if(count($assessor_reviews) > 0)
            {
                $this->createVisits($link, $tr->onefile_id, $_POST['onefile_organisation_id'], $_POST['onefile_assessor_linked'], $assessor_reviews);
            }
        }

        echo $response->getHttpCode();
    }

    public function pushLearningAimsInformation(PDO $link, TrainingRecord $tr, $onefile_learning_aim_id = '')
    {
        $responses = [];
        $sql = new SQLStatement("SELECT student_qualifications.* FROM student_qualifications ");
        $sql->setClause("WHERE student_qualifications.tr_id = '{$tr->id}'");

        $onefile = new Onefile();
        if($onefile_learning_aim_id != '')
        {
            $sql->setClause("WHERE student_qualifications.onefile_learning_aim_id = '{$onefile_learning_aim_id}'");
        }

        $result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
        foreach($result AS $row)
        {
            $data = [
                'ID' => $row['onefile_learning_aim_id'],
                'StartDate' => date("Y-m-d\TH:i:s.000\Z", strtotime("{$row['start_date']} 00:00:00")),
                'PlannedEnd' => date("Y-m-d\TH:i:s.000\Z", strtotime("{$row['end_date']} 00:00:00")),
                'UserID' => $tr->onefile_id,
                'Status' => 0,
            ];
            if( trim($row['awarding_body_reg']) != '' )
            {
                $data['RegistrationNumber'] = $row['awarding_body_reg'];
            }
            if( trim($row['awarding_body_date']) != '' )
            {
                $data['RegistrationDate'] = date("Y-m-d\TH:i:s.000\Z", strtotime("{$row['awarding_body_date']} 00:00:00"));
            }
            if( trim($row['certificate_applied']) != '' )
            {
                $certificate_applied = Date::toMySQL($row['certificate_applied']);
                $data['CertificateAppliedDate'] = date("Y-m-d\TH:i:s.000\Z", strtotime("{$certificate_applied} 00:00:00"));
            }
            if( trim($row['achievement_date']) != '' )
            {
                $achievement_date = Date::toMySQL($row['achievement_date']);
                $data['AchievedDate'] = date("Y-m-d\TH:i:s.000\Z", strtotime("{$achievement_date} 00:00:00"));
            }
            
            $response = $onefile->api_UpdatesLearningAim( $data, $row['onefile_learning_aim_id'] );
            $responses[] = [
                'code' => $response->getHttpCode(),
                'message' => $response->getBody(),
            ];

        }
        return $responses;
    }

    public function addUpdateOnefileLearningAimsAction(PDO $link)
    {
        $auto_id = isset($_REQUEST['auto_id']) ? $_REQUEST['auto_id'] : '';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $learning_aim_id = isset($_REQUEST['learning_aim_id']) ? $_REQUEST['learning_aim_id'] : '';

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if($learning_aim_id == '')
        {
            $this->createAims($link, $tr, [$auto_id]);
        }

        $response = $this->pushLearningAimsInformation($link, $tr, $learning_aim_id);
        echo json_encode($response);
    }

    public function createAims(PDO $link, TrainingRecord $tr, array $auto_ids)
    {
        $auto_ids = implode(",", $auto_ids);
        $onefile = new Onefile();
        $student_qualifications_update = [];

        $sql = "
        SELECT 
            student_qualifications.`auto_id`, student_qualifications.`tr_id`, framework_qualifications.`onefile_standard_id` 
        FROM framework_qualifications
            INNER JOIN student_qualifications ON 
            (
                framework_qualifications.`framework_id` = student_qualifications.`framework_id` AND REPLACE(framework_qualifications.`id`, '/', '') = REPLACE(student_qualifications.`id`, '/', '')
            )
        WHERE 
            student_qualifications.`tr_id` = '{$tr->id}' AND student_qualifications.`auto_id` IN ({$auto_ids}) AND framework_qualifications.`onefile_standard_id` IS NOT NULL;
        ";

        $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        foreach($result AS $row)
        {
            $api_response = $onefile->api_StandardAssign([], $tr->onefile_id, $row['onefile_standard_id']);

            if($api_response->getHttpCode() == 200)
            {
                $student_qualifications_update[] = [
                    'auto_id' => $row['auto_id'],
                    'onefile_learning_aim_id' => $api_response->getBody(),
                ];
            }
        }

        if(count($student_qualifications_update) > 0)
        {
            foreach($student_qualifications_update AS $entry)
            {
                DAO::execute($link, "UPDATE student_qualifications SET onefile_learning_aim_id = '{$entry['onefile_learning_aim_id']}' WHERE auto_id = '{$entry['auto_id']}' AND tr_id = '{$tr->id}'");
            }
        }

        return count($student_qualifications_update);
    }

    private function logUserActions(PDO $link, TrainingRecord $tr, $action, $data)
    {
        $log = new stdClass();
        $log->tr_id = $tr->id;
        $log->action = $action;
        $log->detail = json_encode($data);
        $log->created_by = $_SESSION['user']->id;
        $log->created_at = date('Y-m-d H:i:s');
        DAO::saveObjectToTable($link, "onefile_tr", $log);
    }

    public function getOnefilePlacementsAction(PDO $link)
    {
        $organisation_id = isset($_REQUEST['organisation_id']) ? $_REQUEST['organisation_id'] : '';
        if($organisation_id == '')
        {
            return;
        }

        $onefile = new Onefile();

        $response = $onefile->api_PlacementSearch([
            'OrganisationID' => $organisation_id,
        ]);

        if($response->getHttpCode() == 200)
        {
            $temp = new stdClass();
            $temp->key = "placements_{$organisation_id}";
            $temp->value = $response->getBody();
            $temp->value = preg_replace('/[^\x20-\x7E\xA0\x0D\x0A]/', ' ', $temp->value);
            DAO::saveObjectToTable($link, "onefile", $temp);
        }
    }

    public function createPlacementInOnefileAction(PDO $link)
    {
        $employer_id = isset($_REQUEST['employer_id']) ? $_REQUEST['employer_id'] : '';
        $organisation_id = isset($_REQUEST['organisation_id']) ? $_REQUEST['organisation_id'] : '';
        if($employer_id == '')
        {
            return;
        }
        $employer = Employer::loadFromDatabase($link, $employer_id);
        $location = $employer->getMainLocation($link);
        if(is_null($location))
        {
            $location = new Location();
        }

        $onefile = new Onefile();

        $address = $location->address_line_1 != '' ? $location->address_line_1 : '';
        $address .= $location->address_line_2 != '' ? PHP_EOL . $location->address_line_2 : '';
        $address .= $location->address_line_3 != '' ? PHP_EOL . $location->address_line_3 : '';
        $address .= $location->address_line_4 != '' ? PHP_EOL . $location->address_line_4 : '';
        $address .= $location->postcode != '' ? PHP_EOL . $location->postcode : '';

        $response = $onefile->api_PlacementCreate([
            'OrganisationID' => $organisation_id == '' ? $employer->onefile_organisation_id : $organisation_id,
            'Name' => $employer->legal_name,
            'Address' => $address,
            'Active' => $employer->active ? true : false,
            'Contact' => $location->contact_name,
            'Email' => $location->contact_email,
            'Fax' => $location->fax,
            'Telephone' => $location->telephone != '' ? $location->telephone : $location->contact_telephone,
            'Web' => $employer->url,
            'MISID' => $employer->id,
        ]);

        echo $response->getHttpCode();
    }

    public function createVisits(PDO $link, $onefile_learner_id, $onefile_organisation_id, $assessor_onefile_id = '', $sunesis_reviews_ids = [])
    {
        if(count($sunesis_reviews_ids) == 0)
        {
            return;
        }

        $onefile = new Onefile();

        $sunesis_reviews_ids = implode(",", $sunesis_reviews_ids);
        $result = DAO::getResultset($link, "SELECT assessor_review.*, (SELECT users.`onefile_user_id` FROM users WHERE users.`username` = assessor_review.`assessor`) AS onefile_assessor_id FROM assessor_review WHERE assessor_review.id IN ({$sunesis_reviews_ids})", DAO::FETCH_ASSOC);
        foreach($result AS $row)
        {
            $AssessorID = $assessor_onefile_id != '' ? $assessor_onefile_id : $row['onefile_assessor_id'];
            $visit_data = [
                'LearnerID' => $onefile_learner_id,
                'OrganisationID' => $onefile_organisation_id,
                'DateFrom' => date("Y-m-d\TH:i:s.000\Z", strtotime("{$row['due_date']} 09:00:00")),
                'DateTo' => date("Y-m-d\TH:i:s.000\Z", strtotime("{$row['due_date']} 10:00:00")),
                'VisitTypeID' => $row['place'] == '' ? 2 : intval($row['place']),
                'SMSReminder' => false,
            ];
            if($AssessorID != '')
            {
                $visit_data['AssessorID'] = $AssessorID;
            }
            $visit_response = $onefile->api_VisitCreate($visit_data); 

            if($visit_response->getHttpCode() == 200)
            {
                $review_data = [
                    'LearnerID' => $onefile_learner_id,
                    'Status' => Onefile::REVIEW_SCHEDULED,
                    'ScheduledFor' => date("Y-m-d\TH:i:s.000\Z", strtotime("{$row['due_date']} 09:00:00")),
                    'VisitID' => $visit_response->getBody(),
                ];
                if($AssessorID != '')
                {
                    $review_data['AssessorID'] = $AssessorID;
                }
                $review_response = $onefile->api_ReviewCreate($review_data);

                $temp = new stdClass();
                $temp->id = $row['id'];
                $temp->onefile_review_id = $review_response->getBody();
                $temp->onefile_visit_id = $visit_response->getBody();

                DAO::saveObjectToTable($link, 'assessor_review', $temp);        
            }
        }
    }

    public function getOnefileStandardsAction(PDO $link)
    {
        $organisation_id = isset($_REQUEST['organisation_id']) ? $_REQUEST['organisation_id'] : '';
        if($organisation_id == '')
        {
            return;
        }

        $onefile = new Onefile();

        $response = $onefile->api_StandardSearch([
            'OrganisationID' => $organisation_id,
        ]);

        if($response->getHttpCode() == 200)
        {
            $temp = new stdClass();
            $temp->key = "standards_{$organisation_id}";
            $temp->value = $response->getBody();
            DAO::saveObjectToTable($link, "onefile", $temp);
        }
    }
    
    public function getOnefileOrganisationsAction(PDO $link)
    {
        $onefile_customer = DAO::getSingleValue($link, "SELECT `value` FROM onefile WHERE `key` = 'onefile.customer'");
        $onefile_customer = json_decode($onefile_customer);
        if(!isset($onefile_customer->ID))
        {
            return;
        }

        $onefile = new Onefile();

        $response = $onefile->api_OrganisationSearch([
            'CustomerID' => $onefile_customer->ID,
        ]);

        if($response->getHttpCode() == 200)
        {
            $temp = new stdClass();
            $temp->key = "organisations_{$onefile_customer->ID}";
            $temp->value = $response->getBody();
            DAO::saveObjectToTable($link, "onefile", $temp);
        }
    }
    
    public function getOnefileFrameworkTemplatesAction(PDO $link)
    {
        $organisation_id = isset($_REQUEST['organisation_id']) ? $_REQUEST['organisation_id'] : '';
        if($organisation_id == '')
        {
            return;
        }

        $onefile = new Onefile();

        $response = $onefile->api_FrameworkTemplateSearch([
            'OrganisationID' => $organisation_id,
        ]);

        if($response->getHttpCode() == 200)
        {
            $temp = new stdClass();
            $temp->key = "fwk_tpls_{$organisation_id}";
            $temp->value = $response->getBody();
            DAO::saveObjectToTable($link, "onefile", $temp);
        }
    }
    
    public function getOnefileClassroomsAction(PDO $link)
    {
        $organisation_id = isset($_REQUEST['organisation_id']) ? $_REQUEST['organisation_id'] : '';
        if($organisation_id == '')
        {
            return;
        }

        $onefile = new Onefile();

        $response = $onefile->api_ClassroomSearch([
            'OrganisationID' => $organisation_id,
        ]);

        if($response->getHttpCode() == 200)
        {
            $temp = new stdClass();
            $temp->key = "classrooms_{$organisation_id}";
            $temp->value = $response->getBody();
            DAO::saveObjectToTable($link, "onefile", $temp);
        }
    }

    public function getOnefileLearningAimAction(PDO $link, $learningAimId)
    {
        $onefile = new Onefile();

        $response = $onefile->api_LearningAim($learningAimId);

        if($response->getHttpCode() == 200)
        {
            $response->getBody();
        }
    }

    public function getTlapsAction(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $organisation_id = isset($_REQUEST['organisation_id']) ? $_REQUEST['organisation_id'] : '';

        if($tr_id == '' || $organisation_id == '')
        {
            return;
        }

        $onefileLearnerId = DAO::getSingleValue($link, "SELECT tr.`onefile_id` FROM tr WHERE tr.id = '{$tr_id}'");
        if($onefileLearnerId == '')
        {
            return;
        }

	// only run if last updated is more than 14 days ago.
        $lastModifiedDays = DAO::getSingleValue($link, "SELECT DATEDIFF(CURRENT_TIMESTAMP(), MAX(LastUpdated)) FROM onefile_tlap WHERE LearnerID = '{$onefileLearnerId}'");
        if($lastModifiedDays != '' && (int) $lastModifiedDays < 15)
        {
            return;
        }

        $onefile = new Onefile();

        $response = $onefile->api_PlanSearch([
            'OrganisationID' => $organisation_id,
            'LearnerID' => $onefileLearnerId,
        ]);

        $tlaps = [];
        if($response->getHttpCode() == 200)
        {
            $result = $response->getBody();

		$result = json_decode($result);

            foreach($result AS $row)
            {
                $tlaps[] = [
                    'PlanOn' => $this->formatOneFileDate($row->PlanOn, true),
                    'ID' => $row->ID, // Plan ID
                ];
            }
        }

        if(count($tlaps) > 0)
        {
            DAO::multipleRowInsert($link, "onefile_tlap", $tlaps);
        }

	foreach($tlaps AS $tlap)
        {
            $response = $onefile->api_Plan($tlap["ID"]);

            if($response->getHttpCode() == 200)
            {
                $result = $response->getBody();

                $result = json_decode($result);

		if( isset($result->Title) && !empty($result->Title) )
                {
                    $result->Title = preg_replace('/[^\x00-\x7F]/', '', $result->Title);
                }
		if( isset($result->PlanOn) && !empty($result->PlanOn) )
                {
                    $result->PlanOn = $this->formatOneFileDate($result->PlanOn, true);
                }
		if( isset($result->AssessorSignedOn) && !empty($result->AssessorSignedOn) )
                {
                    $result->AssessorSignedOn = $this->formatOneFileDate($result->AssessorSignedOn, true);
                }
                if( isset($result->LearnerSignedOn) && !empty($result->LearnerSignedOn) )
                {
                    $result->LearnerSignedOn = $this->formatOneFileDate($result->LearnerSignedOn, true);
                }

                DAO::saveObjectToTable($link, "onefile_tlap", $result);
            }
        }
    }

    public function getReviewsAction(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $organisation_id = isset($_REQUEST['organisation_id']) ? $_REQUEST['organisation_id'] : '';

        if($tr_id == '' || $organisation_id == '')
        {
            return;
        }

        $onefileLearnerId = DAO::getSingleValue($link, "SELECT tr.`onefile_id` FROM tr WHERE tr.id = '{$tr_id}'");
        if($onefileLearnerId == '')
        {
            return;
        }

        // only run if last updated is more than 14 days ago.
        $lastModifiedDays = DAO::getSingleValue($link, "SELECT DATEDIFF(CURRENT_TIMESTAMP(), MAX(LastUpdated)) FROM onefile_reviews WHERE LearnerID = '{$onefileLearnerId}'");
        if($lastModifiedDays != '' && (int) $lastModifiedDays < 5)
        {
            return;
        }

        $onefile = new Onefile();

        try 
        {
            $response = $onefile->api_ReviewSearch([
                'OrganisationID' => $organisation_id,
                'LearnerID' => $onefileLearnerId,
            ]);
    
            $reviews = [];
            if($response->getHttpCode() == 200)
            {
                $result = $response->getBody();
    
                $result = json_decode($result);
    
                foreach($result AS $row)
                {
                    $reviews[] = (object)[
                        'ScheduledFor' => $this->formatOneFileDate($row->ScheduledFor, true),
                        'ID' => $row->ID, // Review ID
                    ];
                }
            }
    
            if(count($reviews) > 0)
            {
                DAO::multipleRowInsert($link, "onefile_reviews", $reviews);
            }

            foreach($reviews AS $review)
            {
                $response = $onefile->api_Review($review->ID);
    
                if($response->getHttpCode() == 200)
                {
                    $result = $response->getBody();
    
                    $result = json_decode($result);
    
                    foreach(['CreatedOn', 'ScheduledFor', 'StartedOn', 'AssessorSignedOn', 'LearnerSignedOn'] as $dateField)
                    {
                        if( isset($result->$dateField) && !empty($result->$dateField) )
                        {
                            $result->$dateField = $this->formatOneFileDate($result->$dateField, true);
                        }    
                    }
    
                    DAO::saveObjectToTable($link, "onefile_reviews", $result);
                }
            }    
        } 
        catch (Exception $e) 
        {
            throw new Exception($e->getMessage());
        }
    }

    private function formatOneFileDate($apiDate, $withTime = false)
    {
        $dateTime = new DateTime($apiDate, new DateTimeZone('UTC'));

        $dateTime->setTimezone(new DateTimeZone('Europe/London'));

        return !$withTime ? $dateTime->format('Y-m-d') : $dateTime->format('Y-m-d H:i:s');
    }
}