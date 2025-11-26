<?php


class LookupHelper
{
    public static function getListDeliveryLocation($value = '')
    {
        $list = [
            'NA' => 'N/A',
            'C' => 'College',
            'W' => 'Workplace',
        ];
        return $value != '' ? (isset($list[$value]) ? $list[$value] : $value) : $list;
    }

    public static function getDDLDeliveryLocation()
    {
        return  array(
            array('NA', 'N/A'),
            array('C', 'College'),
            array('W', 'Workplace'),
        );
    }

    public static function getListModeOfAttendance($value = '')
    {
        $list = [
            'NA' => 'N/A',
            'WDR' => 'Weekly D-Rel.',
            'FDR' => 'Fortnightly D.Rel.',
            'PMA' => 'Planned Mock Assessment',
            'MON' => 'Monthly',
            '3W' => '3 Weekly',
            '6W' => '6 Weekly',
        ];
        return $value != '' ? (isset($list[$value]) ? $list[$value] : $value) : $list;
    }

    public static function getDDLModeOfAttendance()
    {
        return  array(
            array('NA', 'N/A'),
            array('WDR', 'Weekly D-Rel.'),
            array('FDR', 'Fortnightly D.Rel.'),
            array('PMA', 'Planned Mock Assessment'),
            array('MON', 'Monthly'),
            array('3W', '3 Weekly'),
            array('6W', '6 Weekly'),
        );
    }

    public static function getListDayOfWeek($value = '')
    {
        $list = [
            'NA' => 'N/A',
            'Monday' => 'Monday',
            'Tuesday' => 'Tuesday',
            'Wednesday' => 'Wednesday',
            'Thursday' => 'Thursday',
            'Friday' => 'Friday',
            'Saturday' => 'Saturday',
            'Sunday' => 'Sunday',
        ];
        return $value != '' ? (isset($list[$value]) ? $list[$value] : $value) : $list;
    }

    public static function getDDLDayOfWeek()
    {
        return  array(
            array('NA', 'N/A'),
            array('Monday', 'Monday'),
            array('Tuesday', 'Tuesday'),
            array('Wednesday', 'Wednesday'),
            array('Thursday', 'Thursday'),
            array('Friday', 'Friday'),
            array('Saturday', 'Saturday'),
            array('Sunday', 'Sunday'),
        );
    }

    public static function getListEmployerType($value = '')
    {
        $list = [
            'NE' => 'New Employer',
            'EE' => 'Existing Employer',
        ];
        return $value != '' ? (isset($list[$value]) ? $list[$value] : $value) : $list;
    }

    public static function getDDLEmployerType()
    {
        return  array(
            array('NE', 'New Employer'),
            array('EE', 'Existing Employer'),
        );
    }

    public static function getDDLYesNo()
    {
        return  array(
            array('N', 'No'),
            array('Y', 'Yes'),
        );
    }

    public static function getListYesNo()
    {
        return  array(
            'N' => 'No',
            'Y' => 'Yes',
        );
    }

    public static function getListFundingType($value = '')
    {
        $list = [
            'L' => 'Levy (DAS) Account',
            'CO' => 'Co-Investment',
        ];
        return $value != '' ? (isset($list[$value]) ? $list[$value] : $value) : $list;
    }

    public static function getDDLFundingType()
    {
        return  array(
            array('L', 'Levy (DAS) Account'),
            array('CO', 'Co-Investment'),
        );
    }

    public static function getListGender()
    {
        return
            array(
                '' => '',
                'F' => 'Female',
                'M' => 'Male',
                'U' => 'Unknown',
                'W' => 'Withheld'
            );
    }

    public static function getDDLGender()
    {
        return  array(
            array('F', 'Female'),
            array('M', 'Male'),
            array('U', 'Unknown'),
            array('W', 'Withheld')
        );
    }

    public static function getDDLHhs()
    {
        return  array(
            array('1', 'No household member is in employment and the household includes one or more dependent children'),
            array('2', 'No household member is in employment and the household does not include any dependent children'),
            array('3', 'Learner lives in a single adult household with dependent children'),
            array('98', 'Learner wants to withhold this information'),
            array('99', 'None of these statements apply'),
        );
    }

    public static function getListHhs()
    {
        return  array(
            '1' => 'No household member is in employment and the household includes one or more dependent children',
            '2' => 'No household member is in employment and the household does not include any dependent children',
            '3' => 'Learner lives in a single adult household with dependent children',
            '98' => 'Learner wants to withhold this information',
            '99' => 'None of these statements apply',
        );
    }

    public static function getDDLTitles(PDO $link)
    {
        return DAO::getResultset($link, "SELECT id, description, null FROM lookup_titles ORDER BY description");
    }

    public static function getDDLKsbScores()
    {
        $scores = [
            ['1', '1 - no knowledge or skills'],
            ['2', '2 - minimal knowledge and skills'],
            ['3', '3 - some of knowledge and skills'],
            ['4', '4 - the majority of the knowledge or skills'],
            ['5', '5 - fully competent'],
        ];
        return $scores;
    }

    public static function getListKsbScores()
    {
        $scores = [
            '1' => '1 - no knowledge or skills',
            '2' => '2 - minimal knowledge and skills',
            '3' => '3 - some of knowledge and skills',
            '4' => '4 - the majority of the knowledge or skills',
            '5' => '5 - fully competent',
        ];
        return $scores;
    }

    public static function getDDLWeeks()
    {
        $weeks = [];
        for($i = 1; $i <= 100; $i++)
        {
            $weeks[] = ['Week'.$i, 'Week ' . $i];
        }
        return $weeks;
    }

    public static function getLlddCategoriesArray()
    {
        return array(
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
    }

    public static function getPriorAttainmentsArray()
    {
        return [
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
    }

    public static function getSubjectsDdl()
    {
        return [
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
    }

    public static function getEmploymentStatusArray()
    {
        return [
            '10' => 'In paid employment',
            '11' => 'Not in paid employment, looking for work and available to start work',
            '12' => 'Not in paid employment, not looking for work and/or not available to start work',
            '98' => 'Not known / don\'t want to provide',
        ];
    }

    public static function viaCurrentEmployerDdl()
    {
        return [
            [1, 'Yes - My employer supports me attending this course and will be co-funding this training with a funding contribution'],
            [2, 'No - I am looking for a new job with a different employer'],
            [3, 'N/A - Self Employed'],
            [4, 'N/A - not in paid employment'],
        ];
    }
    
    public static function planToWorkAlongsideDdl()
    {
        return [
            [1, 'Yes - (Full-time employment)'],
            [2, 'Yes - (Part-time employed)'],
            [3, 'Yes - (Self-employed)'],
            [4, 'No'],
        ];
    }
}