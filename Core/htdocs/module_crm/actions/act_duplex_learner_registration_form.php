<?php
class duplex_learner_registration_form implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $header_image1 = "images/logos/duplex.png";
        $client_name = "Duplex Business Services";
        $logo1 = "images/logos/imi.jpg";
        $logo2 = "images/logos/wolverhampton_college.jpg";
        $location_address = "Unit 46 Planetary Industrial Estate, Planetary Road, Wednesfield, Wolverhampton WV13 3XA";

        $_token = md5(time());

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
            ['97', 'Other qualification; level not known'],
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
            array('1', '1 Emotional/behavioural difficulties'),
            array('2', '2 Multiple disabilities'),
            array('3', '3 Multiple learning difficulties'),
            array('4', '4 Vision impairment'),
            array('5', '5 Hearing impairment'),
            array('6', '6 Disability affecting mobility'),
            array('7', '7 Profound complex disabilities'),
            array('8', '8 Social and emotional difficulties'),
            array('9', '9 Mental health difficulty'),
            array('10', '10 Moderate learning difficulty'),
            array('11', '11 Severe learning difficulty'),
            array('12', '12 Dyslexia'),
            array('13', '13 Dyscalculia'),
            array('14', '14 Autism spectrum disorder'),
            array('15', '15 Asperger\'s syndrome'),
            array('16', '16 Temporary disability after illness (for example post-viral) or accident'),
            array('17', '17 Speech, Language and Communication Needs'),
            array('93', '93 Other physical disability'),
            array('94', '94 Other specific learning difficulty (e.g. Dyspraxia)'),
            array('95', '95 Other medical condition (for example epilepsy, asthma, diabetes)'),
            array('96', '96 Other learning difficulty'),
            array('97', '97 Other disability'),
            array('98', '98 Prefer not to say'),
        );

        include('tpl_duplex_learner_registration_form.php');
    }


}