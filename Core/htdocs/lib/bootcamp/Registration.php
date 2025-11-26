<?php

class Registration extends Entity
{
    public static function loadFromDatabase(PDO $link, $id)
    {
        if ($id == '') {
            return null;
        }

        $key = addslashes((string)$id);
        $query = <<<HEREDOC
SELECT
	*
FROM
	registrations
WHERE
    id='$key';
HEREDOC;
        $st = $link->query($query);

        $result = new Registration();
        if ($st) {
            $row = $st->fetch();
            if ($row) {
                $result->populate($row);
            }
        }

        return $result;
    }

    public function save(PDO $link)
    {
        $this->home_postcode = strtoupper($this->home_postcode ?: '');
        $this->workplace_postcode = strtoupper($this->workplace_postcode ?: '');
        $this->ni = strtoupper($this->ni ?: '');

        DAO::saveObjectToTable($link, 'registrations', $this);
    }

    public function getGenderDescription()
    {
        $gendersDdl = [
            'M' => 'Male',
            'F' => 'Female',
            'O' => 'Other',
            'P' => 'Prefer not to say',
        ];

        return isset($gendersDdl[$this->gender]) ? $gendersDdl[$this->gender] : $this->gender;
    }

    public function gerEthnicityDescription(PDO $link)
    {
        return DAO::getSingleValue($link, "SELECT Ethnicity_Desc FROM lis201415.ilr_ethnicity WHERE Ethnicity = '{$this->ethnicity}'");
    }

    public function getHhsDescription()
    {
        $hhs = [
            1 => 'No household member is in employment and the household includes one or more dependent children',
            2 => 'No household member is in employment and the household does not include any dependent children',
            3 => 'Learner lives in a single adult household with dependent children',
            98 => 'Learner wants to withhold this information',
            99 => 'None of these statements apply',
        ];

        return isset($hhs[$this->hhs]) ? $hhs[$this->hhs] : $this->hhs;
    }

    public function getLlddDescription()
    {
        $llddDesc = '';
        if($this->LLDD == 'Y')
        {
            $llddDesc = 'Yes';
        }
        elseif($this->LLDD == 'N')
        {
            $llddDesc = 'No';
        }
        elseif($this->LLDD == 'P')
        {
            $llddDesc = 'Prefer not to say';
        }

        return $llddDesc;        
    }

    public function getLlddCatDescription($separator = ',')
    {
        $LLDDCats = LookupHelper::getLlddCategoriesArray();

        if($this->llddcat == '')
        {
            return '';
        }

        $cats = '';
        foreach(explode(',', $this->llddcat) AS $llddCode)
        {
            $cats .= isset( $LLDDCats[$llddCode] ) ? $LLDDCats[$llddCode] : $llddCode;
            $cats .= $separator;
        }

        return $cats;
    }

    public function getPrimaryLlddDescription()
    {
        $LLDDCats = LookupHelper::getLlddCategoriesArray();
        return isset($LLDDCats[$this->primary_lldd]) ? $LLDDCats[$this->primary_lldd] : '';
    }

    public function getPriorAttainmentDescription()
    {
        $priorAttainDdl = LookupHelper::getPriorAttainmentsArray();
        foreach($priorAttainDdl AS $priorAttain)
        {
            if($priorAttain[0] == $this->prior_attainment)
            {
                return $priorAttain[1];
            }
        }
    }
    
    public function getLevel6SubjectDescription()
    {
        $subjectsDdl = LookupHelper::getSubjectsDdl();
        foreach($subjectsDdl AS $entry)
        {
            if($entry[0] == $this->level6_subject)
            {
                return $entry[1];
            }
        }
        return '';
    }

    public function getEmploymentStatusDescription()
    {
        $statuses = LookupHelper::getEmploymentStatusArray();
        return isset($statuses[$this->employment_status]) ? $statuses[$this->employment_status] : '';
    }

    public function getLoeDescription()
    {
        $loeList = [
            '1' => 'Up to 3 months',
            '2' => '4-6 months',
            '3' => '7-12 months',
            '4' => 'more than 12 months',
        ];
        return isset($loeList[$this->LOE]) ? $loeList[$this->LOE] : '';
    }

    public function getEiiDescription()
    {
        $eiiList = [
            '5' => '0-10 hours per week',
            '6' => '11-20 hours per week',
            '7' => '21-30 hours per week',
            '8' => 'Employed for 31+ hours per week',
        ];
        return isset($eiiList[$this->EII]) ? $eiiList[$this->EII] : '';
    }

    public function viaCurrentEmployerDescription()
    {
        $viaCurrentEmployerList = LookupHelper::viaCurrentEmployerDdl();
        foreach($viaCurrentEmployerList AS $entry)
        {
            if($entry[0] == $this->via_current_employer)
            {
                return $entry[1];
            }
        }
        return '';
    }
    
    public function planToWorkAlongsideDescription()
    {
        $planToWorkAlongsideList = LookupHelper::planToWorkAlongsideDdl();
        foreach($planToWorkAlongsideList AS $entry)
        {
            if($entry[0] == $this->plan_to_work_alongside)
            {
                return $entry[1];
            }
        }
        return '';
    }

    public function getLouDescription()
    {
        $louList = [
            '1' => 'unemployed for less than 6 months',
            '2' => 'unemployed for 6-11 months',
            '3' => 'unemployed for 12-23 months',
            '4' => 'unemployed for 24-35 months',
            '5' => 'unemployed for over 36 months',
        ];
        return isset($louList[$this->LOU]) ? $louList[$this->LOU] : '';
    }

    public function getBsiCatDescription($separator = ',')
    {
        $bsiList = [
            '1' => 'JSA',
            '2' => 'ESA WRAG',
            '3' => 'Another state benefit',
            '4' => 'Universal Credit',
        ];

        if($this->BSI == '')
        {
            return '';
        }

        $bsis = '';
        foreach(explode(',', $this->BSI) AS $bsiCode)
        {
            $bsis .= isset( $bsiList[$bsiCode] ) ? $bsiList[$bsiCode] : $bsiCode;
            $bsis .= $separator;
        }

        return $bsis;
    }
    
    public function getHearUsDescription($separator = ',')
    {
        $hearUsList = [
            '1' => 'Current Employer',
            '2' => 'Job Center / Work Coach / DWP',
            '3' => 'Social Media',
            '4' => 'Friends / Family',
            '5' => 'FE college / training provider',
            '6' => 'THE National Careers Service',
            '7' => 'Gov.uk website',
            '8' => 'Other (e.g. search engine, local media press)',
        ];

        if($this->hear_us == '')
        {
            return '';
        }

        $hearUsDescs = '';
        foreach(explode(',', $this->hear_us) AS $hearUsCode)
        {
            $hearUsDescs .= isset( $hearUsList[$hearUsCode] ) ? $hearUsList[$hearUsCode] : $hearUsCode;
            $hearUsDescs .= $separator;
        }

        return $hearUsDescs;
    }

    public function getStatus()
    {
        if( is_null($this->is_finished) || $this->is_finished == 'N' )
        {
            return self::STATUS_AWAITING_INFO;
        }
        elseif( $this->is_finished == 'Y' && $this->is_compliant == 0 )
        {
            return self::STATUS_COMPLIANCE_AWAITING;
        }
        elseif( $this->is_finished == 'Y' && $this->is_compliant == 2 )
        {
            return self::STATUS_NOT_COMPLIANT;
        }
        elseif( $this->entity_id != '' )
        {
            return self::STATUS_LEARNER_CREATED;
        }
        elseif( $this->is_finished == 'Y' && $this->is_compliant == 1 )
        {
            return self::STATUS_COMPLIANCE_COMPLETE;
        }
        // elseif( $this->is_finished == 'Y' && $this->is_synced == 1 )
        // {
        //     return self::STATUS_COMPLIANCE_COMPLETE;
        // }
        else
        {
            return '';
        }
    }

    public static function getStatusLabelColor($statusDescription)
    {
        if($statusDescription == self::STATUS_AWAITING_INFO)
        {
            return 'primary';
        }
        elseif($statusDescription == self::STATUS_COMPLIANCE_AWAITING)
        {
            return 'warning';
        }
        elseif($statusDescription == self::STATUS_COMPLIANCE_COMPLETE)
        {
            return 'success';
        }
        elseif($statusDescription == self::STATUS_LEARNER_CREATED)
        {
            return 'success';
        }
        elseif($statusDescription == self::STATUS_NOT_COMPLIANT)
        {
            return 'danger';
        }
        else
        {
            return 'default';
        }
    }

    public function getCompliantStatus(PDO $link)
    {
        $compliantLatest = DAO::getObject($link, "SELECT * FROM registration_compliance WHERE registration_compliance.registration_id = '{$this->id}'");
        if( isset($compliantLatest->id) )
        {
            return $compliantLatest->compliance_status;
        }

        return null;
    }

    const STATUS_AWAITING_INFO = 'Awaiting Information';
    const STATUS_COMPLIANCE_AWAITING = 'Awaiting Compliance Check';
    const STATUS_COMPLIANCE_COMPLETE = 'Compliant';
    const STATUS_LEARNER_CREATED = 'Learner Record Created';
    const STATUS_NOT_COMPLIANT = 'Not Compliant';

    public $id = NULL;
    public $entity_id = NULL;
    public $entity_type = NULL;
    public $learner_title = NULL;
    public $firstnames = NULL;
    public $surname = NULL;
    public $dob = NULL;
    public $home_postcode = NULL;
    public $home_address_line_1 = NULL;
    public $home_address_line_2 = NULL;
    public $home_address_line_3 = NULL;
    public $home_address_line_4 = NULL;
    public $home_telephone = NULL;
    public $home_mobile = NULL;
    public $home_email = NULL;
    public $ni = NULL;
    public $gender = NULL;
    public $ethnicity = NULL;
    public $hhs = NULL;
    public $criminal_conviction = NULL;
    public $currently_caring = NULL;
    public $em_con_title1 = NULL;
    public $em_con_name1 = NULL;
    public $em_con_rel1 = NULL;
    public $em_con_tel1 = NULL;
    public $em_con_mob1 = NULL;
    public $em_con_email1 = NULL;
    public $em_con_title2 = NULL;
    public $em_con_name2 = NULL;
    public $em_con_rel2 = NULL;
    public $em_con_tel2 = NULL;
    public $em_con_mob2 = NULL;
    public $em_con_email2 = NULL;
    public $RUI = NULL;
    public $PMC = NULL;
    public $LLDD = NULL;
    public $llddcat = NULL;
    public $primary_lldd = NULL;
    public $confidential_interview = NULL;
    public $prior_attainment = NULL;
    public $level6_subject = NULL;
    public $employment_status = NULL;
    public $SEI = NULL;
    public $emp_status_employer = NULL;
    public $emp_status_employer_tel = NULL;
    public $employer_contact_name = NULL;
    public $employer_contact_email = NULL;
    public $workplace_postcode = NULL;
    public $current_job_title = NULL;
    public $current_occupation = NULL;
    public $LOE = NULL;
    public $EII = NULL;
    public $current_salary = NULL;
    public $via_current_employer = NULL;
    public $plan_to_work_alongside = NULL;
    public $LOU = NULL;
    public $BSI = NULL;
    public $PEI = NULL;
    public $hear_us = NULL;
    public $is_finished = NULL;
    public $learner_sign = NULL;
    public $learner_sign_date = NULL;
    public $is_synced = NULL;
    public $course_id = NULL;
    public $is_compliant = NULL;
}
