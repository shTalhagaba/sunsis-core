<?php
class bc_form_learner_registration implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        include('/lib/bootcamp/BootcampHelper.php');
        
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        if(trim($id) != '' && trim($key) != '')
        {
            if(!BootcampHelper::isValidBootcampRegistrationUrl($id, $key))
            {
                http_redirect("do.php?_action=bc_error_page");
                exit;
            }
        }
        else
        {
            http_redirect("do.php?_action=bc_error_page");
            exit;
        }

        $learner = User::loadFromDatabaseById($link, $id);
        if(is_null($learner))
        {
            http_redirect("do.php?_action=bc_error_page");
            exit;
        }

        $extraInfo = UserExtraInfo::loadFromDatabase($link, $learner->id);
        if($extraInfo->is_finished == 'Y')
        {
            http_redirect("do.php?_action=bc_form_already_completed");
            exit;
        }



        $header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");
        $client_name = SystemConfig::getEntityValue($link, "client_name");
        $logo1 = "images/logos/imi.jpg";
        $logo2 = "images/logos/wolverhampton_college.jpg";

        $titlesDdl = [
            ['Mr', 'Mr'],
            ['Mrs', 'Mrs'],
            ['Miss', 'Miss'],
            ['Ms', 'Ms'],
        ];

        $gendersDdl = [
            ['M', 'Male'],
            ['F', 'Female'],
            ['O', 'Other'],
            ['P', 'Prefer not to say'],
        ];

        $priorAttainDdl = [
            ['99', 'No record of attainment (have not attained any qualifications)'],
            ['101', 'Entry Level (Basic Entry Level, E)'],
            ['201', 'Level 1 (5 GCSEs D-G/3-1; 1 AS Level; GNVQ Foundation; BTEC First Certificate)'],
            ['301', 'Level 2 (5 GCSEs A*-C/9-4; NVQ2; 2 or 3 AS Levels; GNVQ Intermediate; BTEC First Diploma'],
            ['401', 'Level 3 (4 AS Level; 2 A2/A Level; NVQ3; BTEC Diploma/Extended Diploma/Access to HE)'],
            ['10', 'Level 4 (Certificate of Higher Education; HNC)'],
            ['11', 'Level 5 (Foundation Degree; HND)'],
            ['12', 'Level 6 (Bachelor\'s Degree; Graduate qualification'],
            ['13', 'Level 7 (Master\'s Degree; Postgraduate qualification)'],
            ['401', 'Level 8 (Doctorate, PhD)'],
            ['97', 'Other qualification: level not known'],
        ];

        $ethnicityDdl = DAO::getResultset($link, "SELECT DISTINCT Ethnicity, Ethnicity_Desc, NULL FROM lis201415.ilr_ethnicity ORDER BY Ethnicity, Ethnicity_Desc");

        $subjectsDdl = [
            ['1', 'Medicine and dentistry'],
            ['2', 'Subjects allied to medicine'],
            ['3', 'Biological and sport sciences'],
            ['4', 'Psychology'],
            ['5', 'Veterinary sciences'],
            ['6', 'Agriculture, food and related studies'],
            ['7', 'Physical sciences'],
            ['8', 'General and others in sciences'],
            ['9', 'Mathematical sciences'],
            ['10', 'Engineering and technology'],
            ['11', 'Computing'],
            ['12', 'Geographical and environmental studies (natural sciences)'],
            ['13', 'Architecture, building and planning'],
            ['14', 'Geographical and environmental studies (social sciences)'],
            ['15', 'Humanities and liberal arts (non-specific)'],
            ['16', 'Social sciences'],
            ['17', 'Law'],
            ['18', 'Business and management'],
            ['19', 'Communications and media'],
            ['20', 'Language and area studies'],
            ['21', 'Historical, philosphical and religious studies'],
            ['22', 'Creative arts and design'],
            ['23', 'Education and teaching'],
            ['24', 'Combined and general studies'],
        ];

        usort($subjectsDdl, function ($a, $b) {
            return strcmp($a[1], $b[1]);
        });

        $employerStatusDdl = [
            '10' => 'In full-time employment',
            '20' => 'In part-time employment',
            '30' => 'Employed - zero hour contract',
            '40' => 'Self-employed',
            '50' => 'Unemployed less than 12 months',
            '60' => 'Unemployed more than 12 months',
            '70' => 'In full-time education or training',
            '80' => 'Not working - long term sickness',
            '90' => 'Not working - caring responsibilities',
            '100' => 'Prisoner',
            '110' => 'Retired',
        ];

        $benefitsDdl = [
            '1' => 'In receipt of JSA',
            '2' => 'In receipt of ESA (Part of WRAG group)',
            '4' => 'In receipt of Universal Credit',
            '6' => 'In receipt of another State Benefit',
        ];

        $LLDD = [
            ['Y', 'Yes'],
            ['N', 'No'],
            ['P', 'Prefer not to say'],
        ];

        $YesNoList = [
            ['0', 'No'],
            ['1', 'Yes'],
        ];

        $LLDDCats = array(
            '4' => 'Visual impairment',
            '5' => 'Hearing impairment',
            '6' => 'Disability affecting mobility',
            '7' => 'Profound complex disabilities',
            '8' => 'Social and emotional difficulties',
            '9' => 'Mental health difficulty',
            '10' => 'Moderate learning difficulty',
            '11' => 'Severe learning difficulty',
            '12' => 'Dyslexia',
            '13' => 'Dyscalculia',
            '14' => 'Autism spectrum disorder',
            '15' => 'Asperger\'s syndrome',
            '16' => 'Temporary disability after illness (for example post-viral) or accident',
            '17' => 'Speech, Language and Communication Needs',
            '93' => 'Other physical disability',
            '94' => 'Other specific learning difficulty (e.g. Dyspraxia)',
            '95' => 'Other medical condition (for example epilepsy, asthma, diabetes)',
            '96' => 'Other learning difficulty',
            '97' => 'Other disability',
            '98' => 'Prefer not to say'
        );

        $LLDDCat_dropdown = array(
            array('1', 'Emotional/behavioural difficulties'),
            array('2', 'Multiple disabilities'),
            array('3', 'Multiple learning difficulties'),
            array('4', 'Vision impairment'),
            array('5', 'Hearing impairment'),
            array('6', 'Disability affecting mobility'),
            array('7', 'Profound complex disabilities'),
            array('8', 'Social and emotional difficulties'),
            array('9', 'Mental health difficulty'),
            array('10', 'Moderate learning difficulty'),
            array('11', 'Severe learning difficulty'),
            array('12', 'Dyslexia'),
            array('13', 'Dyscalculia'),
            array('14', 'Autism spectrum disorder'),
            array('15', 'Asperger\'s syndrome'),
            array('16', 'Temporary disability after illness (for example post-viral) or accident'),
            array('17', 'Speech, Language and Communication Needs'),
            array('93', 'Other physical disability'),
            array('94', 'Other specific learning difficulty (e.g. Dyspraxia)'),
            array('95', 'Other medical condition (for example epilepsy, asthma, diabetes)'),
            array('96', 'Other learning difficulty'),
            array('97', 'Other disability'),
            array('98', 'Prefer not to say'),
        );

        $LOE_dropdown = array(array('1', 'Up to 3 months'), array('2', '4-6 months'), array('3', '7-12 months'), array('4', 'more than 12 months'));
        array_unshift($LOE_dropdown, array('','Length of employment',''));
        $EII_dropdown = array(array('5', '0-10 hours per week'), array('6', '11-20 hours per week'), array('7', '21-30 hours per week'), array('8', 'Employed for 31+ hours per week'));
        array_unshift($EII_dropdown, array('','Hours/week',''));
        $LOU_dropdown = array(array('1', 'unemployed for less than 6 months'), array('2', 'unemployed for 6-11 months'), array('3', 'unemployed for 12-23 months'), array('4', 'unemployed for 24-35 months'), array('5', 'unemployed for over 36 months'));
        array_unshift($LOU_dropdown, array('','Length of unemployment',''));
        $BSI_dropdown = array(array('1', 'JSA'), array('2', 'ESA WRAG'), array('3', 'Another state benefit'), array('4', 'Universal Credit'));
        array_unshift($BSI_dropdown, array('','Select benefit type if applicable',''));

        $ethnicityDdl = DAO::getResultset($link,"SELECT Ethnicity, Ethnicity_Desc, null FROM lis201213.ilr_ethnicity ORDER BY Ethnicity;");

        $provider = Organisation::loadFromDatabase($link, DAO::getSingleValue($link, "SELECT id FROM organisations WHERE organisation_type = '1'"));

        $viaCurrentEmployerList = [
            [1, 'Yes - My employer supports me attending this course and will be co-funding this training with a funding contribution'],
            [2, 'No - I am looking for a new job with a different employer'],
            [3, 'N/A - Self Employed'],
            [4, 'N/A - not in paid employment'],
        ];

        $planToWorkAlongsideList = [
            [1, 'Yes - (Full-time employment)'],
            [2, 'Yes - (Part-time employed)'],
            [3, 'Yes - (Self-employed)'],
            [4, 'No'],
        ];
        

        include('tpl_bc_form_learner_registration.php');
    }


}