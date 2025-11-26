<?php
class bc_registration implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $registration = new Registration();
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        if(trim($key) != '')
        {
            $registrationId = BootcampHelper::isValidBootcampRegistrationUrl($link, $key);
            if($registrationId != '')
            {
                $registration = Registration::loadFromDatabase($link, $registrationId);
                if($registration->is_finished == 'Y')
                {
                    http_redirect('do.php?_action=bc_form_already_completed');
                }
            }
        }

        $header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");
        $client_name = SystemConfig::getEntityValue($link, "client_name");

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

        $priorAttainDdl = LookupHelper::getPriorAttainmentsArray();

        $ethnicityDdl = DAO::getResultset($link, "SELECT DISTINCT Ethnicity, Ethnicity_Desc, NULL FROM lis201415.ilr_ethnicity ORDER BY Ethnicity, Ethnicity_Desc");

        $subjectsDdl = LookupHelper::getSubjectsDdl();

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

        $LLDDCats = LookupHelper::getLlddCategoriesArray();

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

        $viaCurrentEmployerList = LookupHelper::viaCurrentEmployerDdl();

        $planToWorkAlongsideList = LookupHelper::planToWorkAlongsideDdl();
        

        include('tpl_bc_registration.php');
    }


}