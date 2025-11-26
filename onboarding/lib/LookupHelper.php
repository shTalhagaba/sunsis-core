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
        if( in_array(DB_NAME, ["am_ela"]) )
        {
            $list = [
                'L' => 'Levy (DAS) Account',
                'CO' => 'Non Levy',
                'LG' => 'Levy Gifted',
            ];
        }
        else
        {
            $list = [
                'L' => 'Levy (DAS) Account',
                'CO' => 'Co-Investment',
                'LG' => 'Levy Gifted',
            ];
        }
        return $value != '' ? (isset($list[$value]) ? $list[$value] : $value) : $list;
    }

    public static function getDDLFundingType()
    {
        return  in_array(DB_NAME, ["am_ela"]) ? 
            [
                ['L', 'Levy (DAS) Account'],
                ['CO', 'Non Levy'],
                ['LG', 'Levy Gifted'],
            ] : [
                ['L', 'Levy (DAS) Account'],
                ['CO', 'Co-Investment'],
                ['LG', 'Levy Gifted'],
            ]; 
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

    public static function getEthnicitiesDdl()
    {
        return [
            ['31', 'British'],
            ['32', 'Irish'],
            ['33', 'Gypsy or Irish Traveller'],
            ['34', 'Any other White background'],
            ['35', 'White and Black Caribbean'],
            ['36', 'White and Black African'],
            ['37', 'White and Asian'],
            ['38', 'Any other Mixed'],
            ['39', 'Indian'],
            ['40', 'Pakistani'],
            ['41', 'Bangladeshi'],
            ['42', 'Chinese'],
            ['43', 'Any other Asian'],
            ['44', 'African'],
            ['45', 'Caribbean'],
            ['46', 'Any other Black'],
            ['47', 'Arab'],
            ['98', 'Any other ethnic group'],
            ['99', 'Not known/not provided'],
        ];
    }

    public static function getEthnicitiesList($code = '')
    {
        $ethnicities = [
            '31' => 'British',
            '32' => 'Irish',
            '33' => 'Gypsy or Irish Traveller',
            '34' => 'Any other White background',
            '35' => 'White and Black Caribbean',
            '36' => 'White and Black African',
            '37' => 'White and Asian',
            '38' => 'Any other Mixed',
            '39' => 'Indian',
            '40' => 'Pakistani',
            '41' => 'Bangladeshi',
            '42' => 'Chinese',
            '43' => 'Any other Asian',
            '44' => 'African',
            '45' => 'Caribbean',
            '46' => 'Any other Black',
            '47' => 'Arab',
            '98' => 'Any other ethnic group',
            '99' => 'Not known/not provided',
        ];

        if($code != '')
            return isset($ethnicities[$code]) ? $ethnicities[$code] : '';

        return $ethnicities;
    }

    public static function getTrainingStatusDdl()
    {
        return [
            [TrainingRecord::STATUS_IN_PROGRESS, 'In Progress'],
            [TrainingRecord::STATUS_COMPLETED, 'Completed'],
            [TrainingRecord::STATUS_ARCHIVED, 'Archived'],
            [TrainingRecord::STATUS_CONVERTED, 'Converted'],
            [TrainingRecord::STATUS_NOT_PROGRESSED, 'Not Progressed'],
        ];
    }

    public static function getLrsGendersList()
    {
        return [
            0 => 'Not Known. The gender of the person has not been recorded.',
            1 => 'Male',
            2 => 'Female',
            9 => 'Not Specified. Unable to be classified as either male or female.',
        ];
    }
    public static function getLrsGendersDdl()
    {
        return [
            [0,  'Not Known. The gender of the person has not been recorded.'],
            [1, 'Male'],
            [2, 'Female'],
            [9, 'Not Specified. Unable to be classified as either male or female.'],
        ];
    }

}