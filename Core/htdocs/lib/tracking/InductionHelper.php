<?php
/**
 * Created by JetBrains PhpStorm.
 * User: inaam
 * Date: 4/28/17
 * Time: 10:47 AM
 * To change this template use File | Settings | File Templates.
 */
class InductionHelper
{
    public static function getQuarters()
    {
        $last_year = date('Y') - 1;
        $current_year = date('Y');
        $next_year = date('Y') + 1;

        $zeroth = new stdClass();
        $zeroth->desc = 'zeroth';
        $zeroth->start_date = new Date($last_year . '-' . '11-01');
        $zeroth->end_date = new Date($current_year . '-' . '01-31');
        $first = new stdClass();
        $first->desc = 'first';
        $first->start_date = new Date($current_year . '-' . '02-01');
        $first->end_date = new Date($current_year . '-' . '04-30');
        $second = new stdClass();
        $second->desc = 'second';
        $second->start_date = new Date($current_year . '-' . '05-01');
        $second->end_date = new Date($current_year . '-' . '07-31');
        $third = new stdClass();
        $third->desc = 'third';
        $third->start_date = new Date($current_year . '-' . '08-01');
        $third->end_date = new Date($current_year . '-' . '10-31');
        $fourth = new stdClass();
        $fourth->desc = 'fourth';
        $fourth->start_date = new Date($current_year . '-' . '11-01');
        $fourth->end_date = new Date($next_year . '-' . '01-31');

        //TODO: special one - to be improved later
        $lastbeforezeroth = new stdClass();
        $lastbeforezeroth->desc = 'lastbeforezeroth';
        $lastbeforezeroth->start_date = new Date($last_year . '-' . '08-01');
        $lastbeforezeroth->end_date = new Date($last_year . '-' . '10-31');

        $quarters = array();
        $quarters['zeroth'] = $zeroth;
        $quarters['first'] = $first;
        $quarters['second'] = $second;
        $quarters['third'] = $third;
        $quarters['fourth'] = $fourth;
        $quarters['lastbeforezeroth'] = $lastbeforezeroth;

        return $quarters;
    }

    public static function getCurrentQuarter()
    {
        $today = new Date(date('Y-m-d'));
        foreach(self::getQuarters() AS $q)
        {
            if($today->equals($q->start_date) || $today->equals($q->end_date))
                return $q;
            elseif($today->after($q->start_date) && $today->before($q->end_date))
                return $q;
        }
    }

    public static function getLastQuarter()
    {
        $quarters = self::getQuarters();
        $current_quarter = self::getCurrentQuarter();
        if($current_quarter->desc == 'first')
            return $quarters['zeroth'];
        elseif($current_quarter->desc == 'second')
            return $quarters['first'];
        elseif($current_quarter->desc == 'third')
            return $quarters['second'];
        elseif($current_quarter->desc == 'fourth')
            return $quarters['third'];
        elseif($current_quarter->desc == 'zeroth')
            return $quarters['lastbeforezeroth'];
    }

    public static function getFlag($color, $title = '', $width = '', $height = '')
    {
        if($title == '')
            $title = $color == 'red' ? 'To be arranged or leaver' : ($color == 'green' ? 'Completed Induction' : ($color == 'yellow' ? 'Prior to induction' : ($color == 'blue' ? 'On hold' : '')));
        return '<img src="images/icons-flags/flag-'.$color.'.png" width="'.$width.'" height="'.$height.'" title="'.$title.'" />';
    }

    public static function getListInductionHeadset()
    {
        return array('N' => 'No', 'S' => 'Sent', 'NR' => 'Not Required', 'SF' => 'Signed For');
    }

    public static function getDDLInductionHeadset()
    {
        return  array(
            array('S', 'Sent'),
            array('NR', 'Not Required'),
            /*array('N', 'No'),
            array('SF', 'Signed For')*/
        );
    }

    public static function getListSchOptions()
    {
        return array('I' => 'Invited', 'B' => 'Booked', 'R' => 'Required', 'U' => 'Uploaded', 'P' => 'Pass', 'MC' => 'Merit/Credit', 'D' => 'Distinction', 'NR' => 'Not Required', 'RP' => 'Result Pending');
    }

    public static function getDDLSchOptions()
    {
        return  array(
            array('I', 'Invited'),
            array('B', 'Booked'),
            array('R', 'Required'),
            array('U', 'Uploaded'),
            array('P', 'Pass'),
            array('MC', 'Merit/Credit'),
            array('D', 'Distinction'),
            array('NR', 'Not Required'),
            array('RP', 'Result Pending')
        );
    }

    public static function getListIAG()
    {
        return
            array(
                'E1' => 'Entry Level 1',
                'E2' => 'Entry Level 2',
                'E3' => 'Entry Level 3',
                'L1' => 'Level 1',
                'L2' => 'Level 2',
                'L3' => 'Level 3',
                'U1' => 'Unclassified',
                'NA' => 'N/A'
            );
    }

    public static function getDDLIAG()
    {
        return  array(
            array('E1', 'Entry Level 1'),
            array('E2', 'Entry Level 2'),
            array('E3', 'Entry Level 3'),
            array('L1', 'Level 1'),
            array('L2', 'Level 2'),
            array('L3', 'Level 3'),
            array('U1', 'Unclassified'),
            array('NA', 'N/A')
        );
    }

    public static function getListICT()
    {
        return
            array(
                'E1' => 'Entry Level 1',
                'E2' => 'Entry Level 2',
                'E3' => 'Entry Level 3',
                'L1' => 'Level 1',
                'L2' => 'Level 2',
                'L3' => 'Level 3',
                'U1' => 'Unclassified',
                'NA' => 'N/A'
            );
    }

    public static function getDDLICT()
    {
        return  array(
            array('E1', 'Entry Level 1'),
            array('E2', 'Entry Level 2'),
            array('E3', 'Entry Level 3'),
            array('L1', 'Level 1'),
            array('L2', 'Level 2'),
            array('L3', 'Level 3'),
            array('U1', 'Unclassified'),
            array('NA', 'N/A')
        );
    }

    public static function getDDLCourseGroups()
    {
        return  array(
            array('SMDM/DM L3', 'SMDM/DM L3'),
            array('DM L4', 'DM L4'),
            array('ITPRO Level 4 Framework', 'ITPRO Level 4 Framework'),
            array('IT Standards L3', 'IT Standards L3'),
            array('IT Standards L4', 'IT Standards L4'),
            array('IT Tech Sales L3', 'IT Tech Sales L3')
        );
    }

    public static function getListEligibilityTestStatus()
    {
        return
            array(
                'S' => 'Started',
                'C' => 'Completed',
                'O' => 'Outstanding',
                'NA' => 'Not Applicable'
            );
    }

    public static function getDDLEligibilityTestStatus()
    {
        return  array(
            array('S', 'Started'),
            array('C', 'Completed'),
            array('O', 'Outstanding'),
            array('NA', 'Not Applicable')
        );
    }

    public static function getListMIAP()
    {
        return
            array(
                'C' => 'Checking',
                'I' => 'Ineligible',
                'N' => 'No record',
                'Y' => 'Yes'
            );
    }

    public static function getDDLMIAP()
    {
        return  array(
            array('C', 'Checking'),
            array('I', 'Ineligible'),
            array('N', 'No record'),
            array('Y', 'Yes')
        );
    }

    public static function getListYesNo($na = false)
    {
        if($na)
            return array('NA' => 'N/A', 'N' => 'No', 'Y' => 'Yes' );
        else
            return array('N' => 'No', 'Y' => 'Yes' );
    }

    public static function getDDLYesNo($na = false)
    {
        if($na)
            return  array(array('NA', 'N/A'),array('N', 'No'),array('Y', 'Yes'));
        else
            return  array(array('N', 'No'),array('Y', 'Yes'));
    }

    public static function getListBIL()
    {
        return array('N' => 'No', 'O' => 'Ops BIL', 'F' => 'Formal BIL' );
    }

    public static function getDDLBIL()
    {
        return  array(array('N', 'No'),array('O', 'Ops BIL'),array('F', 'Formal BIL'));
    }

    public static function getListPeedStatus()
    {
        return array('Y' => 'PEED', 'PP' => 'Potential PEED', 'N' => 'No' );
    }

    public static function getDdlPeedStatus()
    {
        return  array(array('Y', 'PEED'),array('PP', 'Potential PEED'),array('N', 'No'));
    }

    public static function getListLAR()
    {
        return array('N' => 'No', 'Y' => 'LAR', 'O' => 'Ops LAR', 'S' => 'Sales LAR', 'D' => 'Direct Leaver' );
    }

    public static function getDDLLAR()
    {
        //return  array(array('N', 'No'),array('Y', 'LAR'),array('O', 'Ops LAR'),array('S', 'Sales LAR'));
        return  array(array('N', 'No'),array('O', 'Ops LAR'),array('S', 'Sales LAR'),);
    }

    public static function getListComplaintOutcome()
    {
        return array('O' => 'Open', 'C' => 'Closed');
    }

    public static function getDDLComplaintOutcome()
    {
        return  array(array('O', 'Open'),array('C', 'Closed'));
    }

    public static function getListRelatedDepartments()
    {
        return
            array(
                'SSA' => 'Support Services - Admin',
                'IT' => 'IT',
                'Q' => 'Quality',
                'BA' => 'Business Admin',
                'CR' => 'Customer Relations',
                'OIS' => 'Operations and Induction Support',
                'A' => 'Assessors',
                'T' => 'Trainers',
                'R' => 'Recruitment',
                'M' => 'Marketing',
                'NBD' => 'New Business Development',
                'AML' => 'Account Management / Levy',
                'BDM' => 'Business Development Managers'
            );
    }

    public static function getDDLRelatedDepartments()
    {
        return  array(
            array('SSA', 'Support Services - Admin'),
            array('IT', 'IT'),
            array('Q', 'Quality'),
            array('BA', 'Business Admin'),
            array('CR', 'Customer Relations'),
            array('OIS', 'Operations and Induction Support'),
            array('A', 'Assessors'),
            array('T', 'Trainers'),
            array('R', 'Recruitment'),
            array('M', 'Marketing'),
            array('NBD', 'New Business Development'),
            array('AML', 'Account Management / Levy'),
            array('BDM', 'Business Development Managers')
        );
    }

    public static function getDDLRedAmberYellow()
    {
        return  array(array('R', 'Red'),array('O', 'Orange'),array('Y', 'Yellow'));
    }

    public static function getListRedAmberYellow()
    {
        return array('R' => 'Red', 'O' => 'Orange', 'Y' => 'Yellow' );
    }

    public static function getDDLRedAmberGreen()
    {
        return  array(array('R', 'Red'),array('A', 'Amber'),array('G', 'Green'));
    }

    public static function getListRedAmberGreen()
    {
        return array('R' => 'Red', 'A' => 'Amber', 'G' => 'Green' );
    }

    public static function getListAMPM()
    {
        return array('A' => 'AM', 'P' => 'PM', 'D' => 'All Day' );
    }

    public static function getDDLAMPM()
    {
        return  array(array('A', 'AM'),array('P', 'PM'),);
    }

    public static function getListDasAccountCreated()
    {
        return [
            'NR' => 'Not Required',
            'NC' => 'Not Created',
            'C' => 'Created',
            'PR' => 'Permissions Required',
            'RM' => 'Reservations Made',
            'AM' => 'Application Made',
            'AP' => 'Admin Processed',
            'RA' => 'Raised to ARM',
            'LT' => 'Levy Transfer - application made',
            'LTN' => 'Levy Transfer - application not made',
        ];
    }

    public static function getDDLDasAccountCreated()
    {
        return  [
            ['NR', 'Not Required'],
            ['NC', 'Not Created'],
            // ['C', 'Created'],
            ['PR', 'Permissions Required'],
//            ['RM', 'Reservations Made'],
            ['AM', 'Application Made'],
            // ['AP', 'Admin Processed'],
            ['RA', 'Raised to ARM'],
            ['LT', 'Levy Transfer - application made'],
            ['LTN', 'Levy Transfer - application not made'],
        ];
    }

    public static function getListLevyApplication()
    {
        return [
            'NA' => 'N/A',
            'AN' => 'Application not made',
            'AM' => 'Application made',
            'AP' => 'Admin Processed',
            'LT' => 'Levy Transfer - application made',
            'LTN' => 'Levy Transfer - application not made',
            'RM' => 'Raised to ARM',
        ];
    }

    public static function getDdlLevyApplication()
    {
        return  [
            ['NA', 'N/A'],
            ['AN', 'Application not made'],
            ['AM', 'Application made'],
            // ['AP', 'Admin Processed'],
            ['LT', 'Levy Transfer - application made'],
            ['LTN', 'Levy Transfer - application not made'],
            ['RM', 'Raised to ARM'],
        ];
    }

    public static function getListLearnerStatus()
    {
        return [
            'A' => 'Achieved',
            //'BIL' => 'BIL',
            'OBIL' => 'Ops BIL',
            'FBIL' => 'Formal BIL',
            'LAR' => 'LAR',
            'OP' => 'On Programme',
            'PA' => 'PEED - Assessment',
            'PC' => 'PEED - Coordinator',
            'PLM' => 'PEED - Learning Mentor',
            'GR' => 'Gateway Ready',
            'F' => 'Fail',
            'LRA' => 'LRAS (Learners requiring additional support)',
            'PL' => 'PEED/LAR',
            'LB' => 'LAR & BIL',
            'PNDL' => 'Pending Leaver',
        ];
    }

    public static function getDDLLearnerStatus()
    {
        return  [
            ['A', 'Achieved'],
            //['BIL', 'BIL'],
            ['OBIL', 'Ops BIL'],
            ['FBIL', 'Formal BIL'],
            // ['LAR', 'LAR'],
            ['OP', 'On Programme'],
            ['PA', 'PEED - Assessment'],
            ['PC', 'PEED - Coordinator'],
            ['PLM', 'PEED - Learning Mentor'],
            ['GR', 'Gateway Ready'],
            ['F', 'Fail'],
            // ['LRA', 'LRAS (Learners requiring additional support)'],
            // ['PL', 'PEED/LAR'],
            // ['LB', 'LAR & BIL'],
            ['PNDL', 'Pending Leaver'],
        ];
    }

    public static function getListInductionStatus()
    {
        return
            array(
                'TBA' => 'To Be Arranged',
                'S' => 'Scheduled',
                'C' => 'Completed',
                'H' => 'Holding Induction',
                'L' => 'Leaver',
                'W' => 'Withdrawn',
                'LT' => 'Learner Transfer',
            );
    }

    public static function getDDLInductionStatus()
    {
        return  array(
            array('TBA', 'To Be Arranged'),
            array('S', 'Scheduled'),
            array('C', 'Completed'),
            array('H', 'Holding Induction'),
            array('L', 'Leaver'),
            array('W', 'Withdrawn'),
            array('LT', 'Learner Transfer'),
        );
    }

    public static function getListLearnerLeaveReason()
    {
        return
            array(
                'CL' => 'Completed Learning',
                'CA' => 'Completed & Achieved',
                'EL' => 'Early Leaver',
                'O' => 'Other',
                'R' => 'Rework'
            );
    }

    public static function getDDLLearnerLeaveReason()
    {
        return  array(
            array('CL', 'Completed Learning'),
            array('CA', 'Completed & Achieved'),
            array('EL', 'Early Leaver'),
            array('O', 'Other'),
            array('R', 'Rework')
        );
    }

    public static function getListSLAReceived()
    {
        return
            array(
                'YN' => 'Yes New',
                'YO' => 'Yes Old',
                'N' => 'No',
                'R' => 'Rejected'
            );
    }

    public static function getDDLSLAReceived()
    {
        return  array(
            array('YN', 'Yes New'),
            array('YO', 'Yes Old'),
            array('N', 'No'),
            array('R', 'Rejected')
        );
    }

    public static function getListCommitmentStatement()
    {
        return
            array(
                'NS' => 'Not Sent',
                'S' => 'Sent',
                'FC' => 'Fully Completed',
                'RP' => 'RPL Commitment Statement',
                'ST' => 'Standard Commitment Statement',
            );
    }

    public static function getDDLCommitmentStatement()
    {
        return  array(
            array('NS', 'Not Sent'),
            array('S', 'Sent'),
            array('FC', 'Fully Completed'),
        );
    }

    public static function getDDLCommitmentStatementOnly()
    {
        return  array(
            array('NS', 'Not Sent'),
            array('S', 'Sent'),
            array('FC', 'Fully Completed'),
            //array('RP', 'RPL Commitment Statement'),
            //array('ST', 'Standard Commitment Statement'),
        );
    }

    public static function getListYesNoExempt()
    {
        return
            array(
                'N' => 'No',
                'Y' => 'Yes',
                'E' => 'Exempt'
            );
    }

    public static function getDDLYesNoExempt()
    {
        return  array(
            array('N', 'No'),
            array('Y', 'Yes'),
            array('E', 'Exempt')
        );
    }

    public static function getListDiplomaWSDelivery()
    {
        return
            array(
                'L' => 'Location',
                'O' => 'OLL'
            );
    }

    public static function getDDLDiplomaWSDelivery()
    {
        return  array(
            array('L', 'Location'),
            array('O', 'OLL')
        );
    }

    public static function getListQualityCategory()
    {
        return
            array(
                'S' => 'Post quality retention strategy',
                'R' => 'Pre quality retention strategy'
            );
    }

    public static function getDDLQualityCategory()
    {
        return  array(
            array('S', 'Post quality retention strategy'),
            array('R', 'Pre quality retention strategy')
        );
    }

    public static function getListAgeBand()
    {
        return
            array(
                '1' => 'Aged between 16 and 18 years',
                '2' => 'Aged between 19 and 24 years',
                '3' => 'Aged over 24 years'
            );
    }

    public static function getDDLAgeBand()
    {
        return  array(
            array('1', 'Aged between 16 and 18 years'),
            array('2', 'Aged between 19 and 24 years'),
            array('3', 'Aged over 24 years')
        );
    }

    public static function getListRetentionCategories()
    {
        return
            array(
                '1' => 'Right Employer',
                '2' => 'Right Role/Standard',
                '3' => 'Right Candidate',
                '4' => 'Right Support',
                '5' => 'Other'
            );
    }

    public static function getDDLRetentionCategories()
    {
        return  array(
            array('1', 'Right Employer'),
            array('2', 'Right Role/Standard'),
            array('3', 'Right Candidate'),
            array('4', 'Right Support'),
            array('5', 'Other')
        );
    }

    public static function getListOpOwners($bil = '')
    {
        if($bil != '')
        {
            return
                array(
                    '22566' => 'Samantha Hutchinson',
                    '23461' => 'Hannah Gibson',
                    '26982' => 'Glen Preston',
                    '21095' => 'Annie Rockett',
                    '27206' => 'Tom Dormer',
                    '22552' => 'Nicole Maxwell',
                    '26054' => 'Olivia Pennington',
                    '27362' => 'Matt Ward',
                    '27443' => 'Sophie Mayes',
                    '28444' => 'Gemma Slack',
                    '28578' => 'Rebekah Lumsdon',
                    '28579' => 'Ben Kitching',
                    '28934' => 'Bailey Myers',
                    '29001' => 'Emma Barker',
                    '29100' => 'Lisa Seline',
                    'arm' => 'ARM',
                    'onboarding' => 'Onboarding',
                    'lm' => 'Learning Mentor',
                    'coordinator' => 'Coordinator',
                    'cc' => 'Customer Care',
                );
        }

        return
            array(
                '22566' => 'Samantha Hutchinson',
                '23461' => 'Hannah Gibson',
                '26982' => 'Glen Preston',
                '21095' => 'Annie Rockett',
                '27206' => 'Tom Dormer',
                '22552' => 'Nicole Maxwell',
                '26054' => 'Olivia Pennington',
                '27362' => 'Matt Ward',
                '27443' => 'Sophie Mayes',
                '28444' => 'Gemma Slack',
                '28578' => 'Rebekah Lumsdon',
                '28579' => 'Ben Kitching',
                '28934' => 'Bailey Myers',
                '29001' => 'Emma Barker',
                '29100' => 'Lisa Seline',
                '29280' => 'Amy Larkings',
            );
    }

    public static function getDDLOpOwners($bil = '')
    {
        if($bil != '')
        {
            return  array(
                array('arm', 'ARM'),
                array('onboarding', 'Onboarding'),
                array('lm', 'Learning Mentor'),
                array('coordinator', 'Coordinator'),
                array('cc', 'Customer Care'),
            );
    
        }

        return  array(
            //array('22566', 'Samantha Hutchinson'),
            // array('21095', 'Annie Rockett'),
            // array('28160', 'Ellie Pearson'),
            array('23461', 'Hannah Gibson'),
            //array('26982', 'Glen Preston'),
            //array('27206', 'Tom Dormer'),
            array('27362', 'Matt Ward'),
            // array('22552', 'Nicole Maxwell'),
            // array('26054', 'Olivia Pennington'),
            // array('28210', 'Sophie Gilroy'),
            // array('27443', 'Sophie Mayes'),
            // array('28444', 'Gemma Slack'),
            // array('28578', 'Rebekah Lumsdon'),
            // array('28579', 'Ben Kitching'),
            array('28934', 'Bailey Myers'),
            // array('29001', 'Emma Barker'),
            // array('29100', 'Lisa Seline'),
            // array('29280', 'Amy Larkings'),
        );
    }

    public static function getListProgressionStatus()
    {
        return [
            '6' => 'Awaiting employer',
            '4' => 'Awaiting learner',
            '5' => 'Current progression concern',
            '3' => 'Learner Committed',
            '2' => 'Learner Undecided',
            '1' => 'Not progressing',
        ];
    }

    public static function getDDLProgressionStatus()
    {
        return [
            [6, 'Awaiting employer'],
            [4, 'Awaiting learner'],
            [5, 'Current progression concern'],
            [7, 'Definitely progressing - fully confirmed'],
            [3, 'Learner committed'],
            [2, 'Learner undecided'],
            [1, 'Not progressing'],
        ];
    }

    public static function getListReasonForNotProgressing()
    {
        return [
            '1' => 'Too much work',
            '2' => 'Wrong Job Role',
            '3' => 'Moving Company',
            '4' => 'Employer Against',
            '5' => 'Lack of engagement',
            '6' => 'Other',
            '7' => 'Wanting to take time out of education - revisits',
            '8' => 'Not getting kept on',
            '9' => 'No direct progression route available',
	    '10' => 'Alternative FE',
        ];
    }

    public static function getDDLReasonForNotProgressing()
    {
        return [
            [1, 'Too much work'],
            [2, 'Wrong Job Role'],
            [3, 'Moving Company'],
            [4, 'Employer Against'],
            [5, 'Lack of engagement'],
            [6, 'Other'],
            [7, 'Wanting to take time out of education - revisits'],
            [8, 'Not getting kept on'],
            [9, 'No direct progression route available'],
            [10, 'Alternative FE'],
        ];
    }

    public static function getListInducteeType()
    {
        return
            array(
                'NA' => 'NB - New Apprentice',
                'WFD' => 'NB - WFD',
                'P' => 'Progression',
                'ANEW' => 'ACCM - New',
                'AWFD' => 'ACCM - WFD',
                'KNEW' => 'KEY ACCT - New',
                'KWFD' => 'KEY ACCT - WFD',
                'NSSU' => 'NB - STRAIGHT SIGN UP',
                'ASSU' => 'ACCM - STRAIGHT SIGN UP',
                'KSSU' => 'KEY ACCT - STRAIGHT SIGN UP',
                '3AAA' => '3AAA Transfer',
                'LAN' => 'LEVY ACCM - New',
                'LASP' => 'LEVY ACCM - Straight Sign Up',
                'LAWS' => 'LEVY ACCM - WFD',
                'LAPG' => 'LEVY ACCM - PROG'
            );
    }

    public static function getDDLInducteeType()
    {
        return  array(
            array('NA', 'NB - New Apprentice'),
            array('WFD', 'NB - WFD'),
            array('P', 'Progression'),
            array('ANEW', 'ACCM - New'),
            array('AWFD', 'ACCM - WFD'),
            array('KNEW', 'KEY ACCT - New'),
            array('KWFD', 'KEY ACCT - WFD'),
            array('NSSU', 'NB - STRAIGHT SIGN UP'),
            array('ASSU', 'ACCM - STRAIGHT SIGN UP'),
            array('KSSU', 'KEY ACCT - STRAIGHT SIGN UP'),
            array('3AAA', '3AAA Transfer'),
            array('LAN', 'LEVY ACCM - New'),
            array('LASP',  'LEVY ACCM - Straight Sign Up'),
            array('LAWS', 'LEVY ACCM - WFD'),
            array('LAPG', 'LEVY ACCM - PROG')
        );
    }

    public static function getListInducteeTypeV2()
    {
        return
            array(
                '3AAA' => '3AAA Transfer',
                'NA' => 'New Apprentice',
                'P' => 'Progression',
                'SSU' => 'New Apprentice - Client Sourced',
                'WFD' => 'WFD',
                'DXC' => 'DXC Transfer',
                'HOET' => 'HOET Transfer',
                'INT' => 'Internal',
                'LT' => 'Learner Transfer',
            );
    }

    public static function getDDLInducteeTypeV2()
    {
        return  array(
            // array('3AAA', '3AAA Transfer'),
            array('NA', 'New Apprentice'),
            array('P', 'Progression'),
            array('SSU', 'New Apprentice - Client Sourced'),
            array('WFD', 'WFD'),
            array('DXC', 'DXC Transfer'),
            array('HOET', 'HOET Transfer'),
            array('INT', 'Internal'),
            array('LT', 'Learner Transfer'),
        );
    }

    public static function getDdlBilRetentions()
    {
        return [
            ['RE', 'Right Employer'],
            ['RR', 'Right Role/Standard'],
            ['RC', 'Right Candidate'],
            ['RS', 'Right Support'],
            ['OTH', 'Other'],
        ];
    }

    public static function getListBilRetentions()
    {
        return [
            'RE' => 'Right Employer',
            'RR' => 'Right Role/Standard',
            'RC' => 'Right Candidate',
            'RS' => 'Right Support',
            'OTH' => 'Other',
        ];
    }

    public static function getListInducteeEmployerType()
    {
        return
            array(
                //'AM' => 'Account Management',		
                'NB' => 'New Business',
                'SG' => 'EEM Self Generated',
                'L' => 'Levy',
                'SC' => 'Senior Consultant - Levy',
                'EE' => 'EEM Self',
                'LS' => 'Levy Team Self Gen',
                'LT' => 'Enterprise Acquisition',
                'NT' => 'Customer Acquisition',
                'NG' => 'Non Levy Self Gen',
                'NM' => 'Account Management',
                'LM' => 'Enterprise Accounts',
                'PG' => 'Specialist Self Gen',
                'CG' => 'CEM Self Gen',
                'IN' => 'Internal',
            );
    }

    public static function getDDLInducteeEmployerType()
    {
        return  array(
            // array('AM', 'Account Management'),
            // array('CG', 'CEM Self Gen'),
            // array('SG', 'EEM Self Generated'),
            // array('EE', 'EEM Self'),
            // array('L', 'Levy'),
            // array('LS', 'Levy Team Self Gen'),
            array('LT', 'Enterprise Acquisition'),
            array('LM', 'Enterprise Accounts'),
            // array('NB', 'New Business'),
            array('NT', 'Customer Acquisition'),
            // array('NG', 'Non Levy Self Gen'),
            array('NM', 'Account Management'),
            // array('SC', 'Senior Consultant - Levy'),
            //array('PG', 'Specialist Self Gen'),
            array('IN', 'Internal'),
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

    public static function getListDeliveryLocations(PDO $link)
    {
        return DAO::getLookupTable($link, 'SELECT id, description FROM lookup_delivery_locations ORDER BY description');
    }

    public static function getDDLDeliveryLocations(PDO $link)
    {
        return DAO::getResultset($link, "SELECT id, description FROM lookup_delivery_locations ORDER BY description");
    }

    public static function getDDLTrackingFrameworks(PDO $link)
    {
        return DAO::getResultset($link, "SELECT id, title FROM frameworks WHERE track = '1' ORDER BY title", DAO::FETCH_ASSOC);
    }

    public static function getListInductionAssessors(PDO $link, $type = 'induction')
    {
        $table = $type == 'assigned'?'lookup_assigned_assessors':'lookup_induction_assessors';
        $sql = "SELECT id, CONCAT(firstnames, ' ', surname) FROM users INNER JOIN {$table} ON users.id = user_id ORDER BY firstnames";
        return DAO::getLookupTable($link, $sql);
    }

    public static function getListInductionOwners(PDO $link)
    {
        return DAO::getLookupTable($link, "SELECT users.id, CONCAT(users.firstnames, ' ', users.surname) FROM users INNER JOIN lookup_induction_owners ON users.id = user_id ORDER BY firstnames");
    }	

    public static function getDDLInductionOwners(PDO $link)
    {
        //$sql = "SELECT id, CONCAT(firstnames, ' ', surname), NULL FROM users WHERE username IN ('abielok', 'tlilley1', 'rherdman16', 'adavison1') ORDER BY firstnames";
        //return DAO::getResultset($link, $sql);
	$table = 'lookup_induction_owners';
        $sql = "SELECT id, CONCAT(firstnames, ' ', surname, ' (', username, ')'), null FROM users INNER JOIN {$table} ON users.id = user_id ORDER BY firstnames";
        return DAO::getResultset($link, $sql);
    }

    public static function getDDLInductionAssessors(PDO $link, $type = 'induction')
    {
        $table = $type == 'assigned'?'lookup_assigned_assessors':'lookup_induction_assessors';
        $sql = "SELECT id, CONCAT(firstnames, ' ', surname), null FROM users INNER JOIN {$table} ON users.id = user_id ORDER BY firstnames";
        return DAO::getResultset($link, $sql);
    }

    public static function getDdlActivelyInvolvedNonUsersList()
    {
        return [
            ['ARM', 'ARM'],
            ['Programme Coach', 'Programme Coach'],
            ['Programme Coord', 'Programme Coord'],
            ['Safeguarding', 'Safeguarding'],
            ['Apprentice Success', 'Apprentice Success'],
        ];
    }

    public static function getDdlActivelyInvolvedUsersList(PDO $link)
    {
        $sql = "SELECT id, CONCAT(firstnames, ' ', surname), null FROM users INNER JOIN lookup_actively_involved_users ON users.id = lookup_actively_involved_users.user_id ORDER BY firstnames";
        return DAO::getResultset($link, $sql);
    }

    public static function getListActivelyInvolvedUsersList(PDO $link)
    {
        $sql = "SELECT id, CONCAT(firstnames, ' ', surname) AS description FROM users INNER JOIN lookup_actively_involved_users ON users.id = lookup_actively_involved_users.user_id ORDER BY firstnames";
        return DAO::getLookupTable($link, $sql);
    }

    public static function getListInductionCoordinators(PDO $link)
    {
        //return DAO::getLookupTable($link, "SELECT users.id, CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.username IN ('rherdman16', 'lepearson', 'ajohnson18', 'bmilburn', 'nimaxwell', 'opennington', 'mattward1', 'sophiemayes', 'elliepearson', 'sophiegilroy', 'gslack12', 'rlumsdon', 'bkitching', 'bmyers12', 'ebarker12', 'lseline1') ORDER BY users.firstnames");
	return DAO::getLookupTable($link, "SELECT users.id, CONCAT(users.firstnames, ' ', users.surname) FROM users INNER JOIN lookup_induction_assigned_coord ON users.id = user_id ORDER BY firstnames");
    }

    public static function getDDLInductionCoordinators(PDO $link)
    {
        //return DAO::getResultset($link, "SELECT users.id, CONCAT(users.firstnames, ' ', users.surname), null FROM users WHERE users.username IN ('rherdman16', 'lepearson', 'ajohnson18', 'bmilburn', 'nimaxwell', 'opennington', 'mattward1', 'sophiemayes', 'elliepearson', 'sophiegilroy', 'gslack12', 'rlumsdon', 'bkitching', 'bmyers12', 'ebarker12', 'lseline1') ORDER BY users.firstnames");
	$table = 'lookup_induction_assigned_coord';
        $sql = "SELECT id, CONCAT(firstnames, ' ', surname, ' (', username, ')'), null FROM users INNER JOIN {$table} ON users.id = user_id ORDER BY firstnames";
        return DAO::getResultset($link, $sql);
    }

    public static function getListOpInternalManagers(PDO $link)
    {
        return DAO::getLookupTable($link, "SELECT users.id, CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.op_internal_manager = 'Y' ORDER BY users.firstnames");
    }

    public static function getDDLOpInternalManagers(PDO $link)
    {
        return DAO::getResultset($link, "SELECT users.id, CONCAT(users.firstnames, ' ', users.surname), null FROM users WHERE users.op_internal_manager = 'Y' ORDER BY users.firstnames");
    }

    public static function getListOpTrainers(PDO $link)
    {
        return DAO::getLookupTable($link, "SELECT users.id, CONCAT(users.firstnames, ' ', users.surname) FROM users INNER JOIN lookup_op_trainers ON users.id = user_id ORDER BY firstnames");
    }

    public static function getDDLOpTrainers(PDO $link)
    {
        $table = 'lookup_op_trainers';
        $sql = "SELECT id, CONCAT(firstnames, ' ', surname, ' (', username, ')'), null FROM users INNER JOIN {$table} ON users.id = user_id ORDER BY firstnames";
        return DAO::getResultset($link, $sql);
    }

    public static function getListInductionProgramme(PDO $link)
    {
        return DAO::getLookupTable($link, "SELECT courses.id, CONCAT(courses.title, ' (',frameworks.`title`, ')') FROM courses INNER JOIN frameworks ON courses.`framework_id` = frameworks.id WHERE courses.`active` = 1 AND courses.induction = 'Y' ORDER BY courses.title;");
    }

    public static function getDDLInductionProgramme(PDO $link, $inductionProgrammeId = '')
    {
        return $inductionProgrammeId == '' ? 
            DAO::getResultset($link, "SELECT courses.id, CONCAT(courses.title, ' (',frameworks.`title`, ')') FROM courses INNER JOIN frameworks ON courses.`framework_id` = frameworks.id WHERE courses.`active` = 1 AND frameworks.`active` = 1 AND courses.induction = 'Y' ORDER BY courses.title") :
            DAO::getResultset($link, "SELECT courses.id, CONCAT(courses.title, ' (',frameworks.`title`, ')') FROM courses INNER JOIN frameworks ON courses.`framework_id` = frameworks.id WHERE (courses.`active` = 1 AND frameworks.`active` = 1 AND courses.induction = 'Y') OR (courses.id = '{$inductionProgrammeId}') ORDER BY courses.title");
    }

    public static function addMonths($date,$months)
    {
        $init=clone $date;
        $modifier=$months.' months';
        $back_modifier =-$months.' months';

        $date->modify($modifier);
        $back_to_init= clone $date;
        $back_to_init->modify($back_modifier);

        while($init->format('m')!=$back_to_init->format('m'))
        {
            $date->modify('-1 day')    ;
            $back_to_init= clone $date;
            $back_to_init->modify($back_modifier);
        }
    }

    public static function getDDLCancellationType()
    {
        return  array(
            array('M30', 'More than 30 days of event'),
            array('14T30', '14 to 30 days of event'),
            array('7T14', '7 to 14 days of event'),
            array('1T7', '1 to 7 days of event'),
            array('O', 'On day of event')
        );
    }

    public static function getListCancellationType()
    {
        return
            array(
                'M30' => 'More than 30 days of event',
                '14T30' => '14 to 30 days of event',
                '7T14' => '7 to 14 days of event',
                '1T7' => '1 to 7 days of event',
                'O' => 'On day of event'
            );
    }

    public static function getDDLBalticValues()
    {
        return  array(
            array('Pa', 'Passion'),
            array('Su', 'Support'),
            array('Cu', 'Curiosity'),
            array('Se', 'Selflessness'),
            array('Cs', 'Customer Service'),
            array('Im', 'Impact'),
            array('En', 'Enjoyment'),
            array('Wi', 'Wisdom'),
            array('Cm', 'Communication'),
            array('In', 'Innovation'),
            array('Co', 'Courage'),
            array('Ho', 'Honesty')
        );
    }

    public static function getListBalticValues()
    {
        return
            array(
                'Pa' => 'Passion',
                'Su' => 'Support',
                'Cu' => 'Curiosity',
                'Se' => 'Selflessness',
                'Cs' => 'Customer Service',
                'Im' => 'Impact',
                'En' => 'Enjoyment',
                'Wi' => 'Wisdom',
                'Cm' => 'Communication',
                'In' => 'Innovation',
                'Co' => 'Courage',
                'Ho' => 'Honesty'
            );
    }

    public static function getDDLCancellationCategory()
    {
        return  array(
            array('BR', 'Baltic reschedule'),
            array('CE', 'Coordinator error'),
            array('L', 'Leaver'),
            array('LL', 'Learner on leave'),
            array('LAR', 'LAR'),
            array('LOS', 'Learner off sick'),
            array('O', 'Other'),
            array('S', 'Suspended')
        );
    }

    public static function getListSessionRegisterStatus()
    {
        return
            array(
                'NC' => 'Not Completed',
                'C' => 'Completed',
                'S' => 'Signed-off',
                'NA' => 'Not accepted',
                'R' => 'Resubmitted'
            );
    }

    public static function getDDLSessionRegisterStatus()
    {
        return  array(
            array('NC', 'Not Completed'),
            array('C', 'Completed'),
            array('S', 'Signed-off'),
            array('NA', 'Not accepted'),
            array('R', 'Resubmitted')
        );
    }

    public static function getListCancellationCategory()
    {
        return
            array(
                'BR' => 'Baltic reschedule',
                'CE' => 'Coordinator error',
                'L' => 'Leaver',
                'LL' => 'Learner on leave',
                'LAR' => 'LAR',
                'LOS' => 'Learner off sick',
                'O' => 'Other',
                'S' => 'Suspended'
            );
    }

    public static function getDDLEventTypes()
    {
        return  array(
            array('CRS', 'Course'),
            array('DEV', 'Development'),
            array('EX', 'Exam'),
            array('MRK', 'Marking'),
            array('OBS', 'Observations'),
            array('PRP', 'Preparations'),
            array('ST', 'Staff training'),
            array('SUP', 'Support'),
            array('TM', 'Trainer meeting'),
            array('WRK', 'Workshop'),
            array('O', 'Other')
        );
    }

    public static function getListEventTypes()
    {
        return
            array(
                'CRS' => 'Course',
                'DEV' => 'Development',
                'EX' => 'Exam',
                'MRK' => 'Marking',
                'OBS' => 'Observations',
                'PRP' => 'Preparations',
                'ST' => 'Staff training',
                'SUP' => 'Support',
                'TM' => 'Trainer meeting',
                'WRK' => 'Workshop',
                'O' => 'Other'
            );
    }

    public static function getDDLSessionEntryCodes()
    {
        return  array(
            array('0', ''),
            array('1', 'Attended'),
            array('2', 'Late'),
            array('3', 'Absent'),
            array('4', 'N/A')
        );
    }

    public static function getListSessionEntryCodes()
    {
        return
            array(
                '0' => '',
                '1' => 'Attended',
                '2' => 'Late',
                '3' => 'Absent',
                '4' => 'N/A'
            );
    }

    public static function getDDLLastLearningEvidence()
    {
        return  array(
//            array('REV', 'Review'),
//            array('FAP', 'FAP'),
//            array('WB', 'Workshop Booklet'),
//            array('FS', 'Functional Skills'),
//            array('OTH', 'Other')
            array('TLE', 'Time Log Entry'),
            array('APS', 'Assessment Plan Submission'),
            array('R', 'Register'),
            array('C', 'Certificate'),
            array('OTH', 'Other'),
        );
    }

    public static function getListLastLearningEvidence()
    {
        return
            array(
                'REV' => 'Review',
                'FAP' => 'FAP',
                'WB' => 'Workshop Booklet',
                'FS' => 'Functional Skills',
                'OTH' => 'Other',
                'TLE' => 'Time Log Entry',
                'APS' => 'Assessment Plan Submission',
                'R' => 'Register',
                'C' => 'Certificate',
            );
    }

    public static function getDDLLearnerID()
    {
        return  array(
            /*array('RPI', 'Received prior induction'),
            array('RFI', 'Received following induction'),
            array('O', 'Outstanding'),
            array('RAI', 'Received at induction'),
            array('SP', 'Sign posted'),
            array('NR', 'Not Required')*/
            array('P', 'Passport'),
            array('DL', 'Driving License'),
            array('PDL', 'Provisional Driving License'),
            array('PAC', 'Proof of Age Card'),
            array('BC', 'Birth Certificate'),
            array('R', 'Residency'),
            array('PTC', 'Passed to Coach'),
        );
    }

    public static function getListLearnerID()
    {
        return
            array(
                'RPI' => 'Received prior induction',
                'RAI' => 'Received at induction',
                'RFI' => 'Received following induction',
                'O' => 'Outstanding',
                'SP' => 'Sign posted',
                'NR' => 'Not Required',
                'P' => 'Passport',
                'DL' => 'Driving License',
                'PDL' => 'Provisional Driving License',
                'PAC' => 'Proof of Age Card',
                'BC' => 'Birth Certificate',
                'R' => 'Residency',
                'PTC' => 'Passed to Coach',
            );
    }

    public static function getTrackingUnits(PDO $link, $frameworks = array())
    {
        $units_ddl = array();

        if ($frameworks == '')
            return $units_ddl;

        $added_units = array();
        foreach($frameworks AS $framework_id)
        {
            $qualifications = DAO::getSingleColumn($link, "SELECT REPLACE(id, '/', '') AS id FROM framework_qualifications WHERE framework_id = '{$framework_id}' AND LOCATE('track=\"true\"', evidences) > 0 ;");
            foreach($qualifications AS $qualification_id)
            {
                $sql = <<<HEREDOC
SELECT
	 framework_qualifications.id,
	 framework_qualifications.evidences
FROM
	 framework_qualifications
WHERE
	 framework_qualifications.framework_id = '$framework_id' AND REPLACE(framework_qualifications.id, '/', '') = '$qualification_id' ;
HEREDOC;

                $student_qualifications = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

                foreach ($student_qualifications AS $qualification)
                {
                    $evidence = XML::loadSimpleXML($qualification['evidences']);

                    $units = $evidence->xpath('//unit');
                    $q_units = array();
                    foreach ($units AS $unit)
                    {
                        $temp = array();
                        $temp = (array)$unit->attributes();
                        $temp = $temp['@attributes'];
                        if(!isset($temp['op_title'])) continue;
                        $temp['op_title'] = str_replace('/','', $temp['op_title']);
                        $temp['qualification_id'] = $qualification_id;
                        $temp['framework_id'] = $framework_id;
                        if(isset($temp['track']) && $temp['track'] == 'true' && !in_array($temp['op_title'], $added_units))
                        {
                            $units_ddl[] = $temp;
                            $added_units[] = $temp['op_title'];
                        }
                    }
                    //$units_ddl[] = $q_units;
                }
            }
        }
        return $units_ddl;
    }

    public static function getTrackingUnitsForDDL(PDO $link, $frameworks = array())
    {
        $units_ddl = array();

        if (count($frameworks) == 0)
        {
            $frameworks = DAO::getSingleColumn($link, "SELECT frameworks.id FROM frameworks WHERE frameworks.track = '1'");
        }

        foreach($frameworks AS $framework_id)
        {
            $qualifications = DAO::getSingleColumn($link, "SELECT REPLACE(id, '/', '') AS id FROM framework_qualifications WHERE framework_id = '{$framework_id}' AND LOCATE('track=\"true\"', evidences) > 0 ;");
            foreach($qualifications AS $qualification_id)
            {
                $sql = <<<HEREDOC
SELECT
	 framework_qualifications.id,
	 framework_qualifications.evidences
FROM
	 framework_qualifications
WHERE
	 framework_qualifications.framework_id = '$framework_id' AND REPLACE(framework_qualifications.id, '/', '') = '$qualification_id' ;
HEREDOC;

                $student_qualifications = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

                foreach ($student_qualifications AS $qualification)
                {
                    $evidence = XML::loadSimpleXML($qualification['evidences']);

                    $units = $evidence->xpath('//unit');
                    $q_units = array();
                    foreach ($units AS $unit)
                    {
                        $temp = array();
                        $temp = (array)$unit->attributes();
                        $temp = $temp['@attributes'];
                        if(!isset($temp['op_title'])) continue;
                        //$temp['op_title'] = str_replace('/','', $temp['op_title']);
                        $temp['qualification_id'] = $qualification_id;
                        $temp['framework_id'] = $framework_id;
                        if(isset($temp['track']) && $temp['track'] == 'true')
                            $q_units[] = $temp;
                    }
                    $units_ddl[] = $q_units;
                }
            }
        }
        $final_ddl = array();
        $added_units = array();
        foreach($units_ddl AS $unit_entry)
        {
            for($i=0;$i<count($unit_entry);$i++)
            {
                $unit_ref_key = '';
                $unit_ref_key .= $unit_entry[$i]['op_title'];
                //$unit_ref_key .= $unit_entry[$i]['owner_reference'] . '|';
                //$unit_ref_key .= $unit_entry[$i]['qualification_id'] . '|';
                //$unit_ref_key .= $unit_entry[$i]['reference'] . '|';
                //$unit_ref_key .= $unit_entry[$i]['framework_id'] . '|';

                //$final_ddl[] = array($unit_entry[$i]['owner_reference'], $unit_entry[$i]['qualification_id'] . ', ' . $unit_entry[$i]['reference'] . ', ' . $unit_entry[$i]['owner_reference'] . ' [' . $unit_entry[$i]['title'] . ']');
                if(!in_array($unit_ref_key, $added_units))
                {
                    $final_ddl[] = array($unit_ref_key, $unit_entry[$i]['title']);
                    $added_units[] = $unit_ref_key;
                }
            }
        }
        array_multisort($final_ddl, SORT_ASC );

        return $final_ddl;
    }

    public static function getProviderLocationsDDL(PDO $link, $course_id = '')
    {
        $course_clause = '';
        if($course_id != '')
            $course_clause = ' AND providers.id IN (SELECT courses.`organisations_id` FROM courses WHERE id = ' . $course_id . ') ';
        $sql = <<<SQL
SELECT
providers_locations.id,
CONCAT(COALESCE(providers.`legal_name`), ' (',COALESCE(`address_line_1`,''),' ',COALESCE(`address_line_2`,''),',',COALESCE(`postcode`,''), ')') AS detail,
providers.`legal_name` AS provider
FROM organisations AS providers
INNER JOIN locations AS providers_locations
ON providers.id = providers_locations.`organisations_id`
WHERE providers.`organisation_type` = 3 $course_clause
ORDER BY providers.legal_name
;
SQL;
        return DAO::getResultset($link, $sql);
    }

    public static function getListEPA()
    {
        return
            array(
                'ED' => 'EPA Date',
                'ID' => 'IQA Date',
                'AB' => 'Awarding Body',
                'EU' => 'E-Portfolio Uploaded',
                'SP' => 'Synoptic Project',
                'I' => 'Interview',
                'O' => 'Outcome'
            );
    }

    public static function getDDLEPA()
    {
        return  array(
            array('ED', 'EPA Date'),
            array('ID', 'IQA Date'),
            array('AB', 'Awarding Body'),
            array('EU', 'E-Portfolio Uploaded'),
            array('SP', 'Synoptic Project'),
            array('I', 'Interview'),
            array('O', 'Outcome')
        );
    }

    public static function getListMockStatus()
    {
        return
            array(
                'MO' => 'Mock Outstanding',
                'MI' => 'Mock Issued',
                'MP' => 'Mock Passed',
                'SI' => 'SDS Issued',
                'SO' => 'SDS Outstanding',
                'SP' => 'SDS Passed'
            );
    }

    public static function getDDLMockStatus()
    {
        return  array(
            array('MO', 'Mock Outstanding'),
            array('MI', 'Mock Issued'),
            array('MP', 'Mock Passed'),
            array('SI', 'SDS Issued'),
            array('SO', 'SDS Outstanding'),
            array('SP', 'SDS Passed')
        );
    }

    public static function getListOpTask()
    {
        return
            array(
                '1' => 'EPA ready',
                '2' => 'Employer reference',
                '3' => 'Summative portfolio',
                '4' => 'IQA complete',
                '5' => 'Passed to SS',
                '6' => 'Synoptic project',
                '7' => 'Interview',
                '8' => 'EPA result',
                '9' => 'Project',
                '10' => 'Gateway Declarations',
                '11' => 'EPA Forecast',
                '12' => 'Gateway Forecast',
                '13' => 'Achievement Forecast',
                '14' => 'Potential Forecast',
                '15' => 'End of Learning Statement',
                '16' => 'EPA Workshop',
                '17' => 'PEED forecast',
                '18' => 'Packet Tracer Sent',
                '19' => 'Deadline Date',
                '20' => 'Project Brief Accepted',
                '21' => 'Multi Choice Quiz',
                '22' => 'Professional Discussion',
                '23' => 'Interview 2',
                '24' => 'Multi Choice Quiz',
                '25' => 'Interview Cancellation',
            );
    }

    public static function getDDLOpTask($tracker_id = '')
    {
        $tasks = array(
            array('1', 'EPA ready'),
            array('2', 'Employer reference'),
            array('3', 'Summative portfolio'),
            array('4', 'IQA complete'),
            array('5', 'Passed to SS'),
            array('6', 'Synoptic project'),
            array('7', 'Interview'),
            array('8', 'EPA result'),
            array('9', 'Project'),
            array('10', 'Gateway Declarations'),
            array('11', 'EPA Forecast'),
            array('12', 'Gateway Forecast'),
            array('13', 'Achievement Forecast'),
            array('14', 'Potential Forecast'),
            array('15', 'End of Learning Statement'),
            array('16', 'EPA Workshop'),
            array('17', 'PEED forecast'),
            array('18', 'Packet Tracer Sent'),
            array('25', 'Interview Cancellation'),
        );

        if( in_array($tracker_id, [36, 66]) )
        {
            $extra_tasks = [
                [19, 'Deadline Date'],
                [20, 'Project Brief Accepted'],
                [21, 'Multi Choice Quiz'],
                [22, 'Professional Discussion'],
            ];

            $tasks = array_merge($tasks, $extra_tasks);
        }

	if( in_array($tracker_id, [50, 55, 60, 72, 51, 56, 61, 71]) )
        {
            $extra_tasks = [
                [23, 'Interview 2'],
            ];

            $tasks = array_merge($tasks, $extra_tasks);
        }

	if( in_array($tracker_id, [80, 89]) )
        {
            $extra_tasks = [
                [24, 'Multi Choice Quiz'],
            ];

            $tasks = array_merge($tasks, $extra_tasks);
        }

        return $tasks;
    }

    public static function getListOpEpao()
    {
        return
            array(
                'BCS' => 'BCS',
                'AP' => 'AP',
                'EPA' => '1st for EPA',
                'City and Guilds' => 'City and Guilds',
            );
    }

    public static function getDDLOpEpao()
    {
        return  array(
            array('BCS', 'BCS'),
            array('AP', 'AP'),
            array('EPA', '1st for EPA'),
            array('City and Guilds', 'City and Guilds'),
        );
    }

    public static function getListPotentialAchMonth()
    {
        $a = [];
        for($i = 12; $i <= 30; $i++)
            $a[$i] = $i;
        return $a;
    }

    public static function getDDLPotentialAchMonth()
    {
        $a = [];
        for($i = 12; $i <= 30; $i++)
        {
            $a[] = [$i, $i];
        }
        return $a;
    }

    public static function getListOpTaskStatus($op_task = '')
    {
        if($op_task == '1')
            return array('1' => 'Ready', '2' => 'Not ready', '52' => 'Sent to EPAO', '53' => 'Project Submission');
        elseif($op_task == '2')
            return array('3' => 'Requested', '4' => 'Await from employer', '1' => 'Ready', '24' => 'To be sent');
        elseif($op_task == '3')
            return array('5' => 'Assessor accepted', '6' => 'Assessor declined', '7' => 'Assessor passed to IQA','22' => 'In-progress with assessor');
        elseif($op_task == '4')
            return array('8' => 'IQA passed', '9' => 'IQA rejected', '23' => 'In Progress', '25' => 'Not applicable', '26' => 'To be sampled');
        elseif($op_task == '5')
            return array('10' => 'BCS', '11' => 'C&G', '50' => 'AP', '51' => '1st for EPA', '59' => 'NCFE/CACHE');
        elseif($op_task == '6' || $op_task == '7')
            return array('12' => 'Invited', '13' => 'Booked', '14' => 'Completed', '15' => 'Rejected', '27' => 'Awaiting BCS confirmation', '43' => 'Awaiting EPA confirmation');
        elseif($op_task == '8')
            return array('16' => 'Passed', '17' => 'Merit', '18' => 'Distinction', '19' => 'Fail', '41' => 'Fail- continue', '42' => 'Fail- completion non-achiever');
        elseif($op_task == '9')
            return array('20' => 'Selected', '21' => 'Not selected');
        elseif($op_task == '10')
            return array('29' => 'Sent', '30' => 'Chased', '31' => 'Complete', '32' => 'Not Sent');
        elseif($op_task == '11')
            return array('33' => 'Yes', '34' => 'No');
        elseif($op_task == '12')
            return array('35' => 'Yes', '36' => 'No');
        elseif($op_task == '15')
            return array('37' => 'Not Set', '14' => 'Completed', '38' => 'Not Completed');
        elseif($op_task == '16')
            return array('39' => 'Required', '40' => 'Not Required', '12' => 'Invited', '13' => 'Booked', '14' => 'Completed');
        elseif($op_task == '19')
            return array('57' => 'Not yet required', '23' => 'In Progress', '58' => 'Extended', '14' => 'Completed' );
        elseif($op_task == '20')
            return array('39' => 'Required', '54' => 'Submitted', '55' => 'Accepted', '56' => 'Rejected - Sent for Rework');
        elseif($op_task == '21' || $op_task == '22')
            return array('39' => 'Required', '12' => 'Invited', '13' => 'Booked', '14' => 'Completed');
	elseif($op_task == '25')
            return array('60' => 'Baltic Invoice', '61' => 'Employer Invoice', '62' => 'Baltic Reviewing', '63' => 'Invoice waived by EPAO');
        else
            return
                array(
                    '1' => 'Ready',
                    '2' => 'Not ready',
                    '3' => 'Requested',
                    '4' => 'Await return from employer',
                    '5' => 'Assessor accepted',
                    '6' => 'Assessor declined',
                    '7' => 'Assessor passed to IQA',
                    '8' => 'IQA passed',
                    '9' => 'IQA rejected',
                    '10' => 'BCS',
                    '11' => 'C&G',
                    '12' => 'Invited',
                    '13' => 'Booked',
                    '14' => 'Completed',
                    '15' => 'Rejected',
                    '16' => 'Pass',
                    '17' => 'Merit',
                    '18' => 'Distinction',
                    '19' => 'Fail',
                    '20' => 'Selected',
                    '21' => 'Not selected',
                    '22' => 'In-progress with assessor',
                    '23' => 'In Progress',
                    '24' => 'to be sent',
                    '25' => 'Not applicable',
                    '26' => 'To be sampled',
                    '27' => 'Awaiting BCS confirmation',
                    '28' => 'EPA Ready',
                    '29' => 'Sent',
                    '30' => 'Chased',
                    '31' => 'Complete',
                    '32' => 'Not Sent',
                    '33' => 'Yes',
                    '34' => 'No',
                    '35' => 'Yes',
                    '36' => 'No',
                    '37' => 'Not Set',
                    '38' => 'Not Completed',
                    '39' => 'Required',
                    '40' => 'Not Required',
                    '41' => 'Fail- continue',
                    '42' => 'Fail- completion non-achiever',
                    '43' => 'Awaiting EPA confirmation',
                    '44' => 'Involvement - PEED Concern',
                    '45' => 'Owning',
                    '46' => 'No action',
                    '47' => 'Action plan in place',
                    '48' => 'Involvement - LAR',
                    '49' => 'PEED Cause',
                    '50' => 'AP',
                    '51' => '1st for EPA',
                    '52' => 'Sent to EPAO',
                    '53' => 'Project Submission',
                    '54' => 'Submitted',
                    '55' => 'Accepted',
                    '56' => 'Rejected - Sent for Rework',
                    '57' => 'Not yet required',
                    '58' => 'Extended',
                    '59' => 'NCFE/CACHE',
                    '60' => 'Baltic Invoice',
                    '61' => 'Employer Invoice',
                    '62' => 'Baltic Reviewing',
                    '63' => 'Invoice waived by EPAO',
                );
    }

    public static function getDDLOpTaskStatus()
    {
        return  array(
            array('1', 'Ready'),
            array('2', 'Not ready'),
            array('3', 'Requested'),
            array('4', 'Await return from employer'),
            array('5', 'Assessor accepted'),
            array('6', 'Assessor declined'),
            array('7', 'Assessor passed to IQA'),
            array('8', 'IQA passed'),
            array('9', 'IQA rejected'),
            array('10', 'BCS'),
            array('11', 'C&G'),
            array('12', 'Invited'),
            array('13', 'Booked'),
            array('14', 'Completed'),
            array('15', 'Rejected'),
            array('16', 'Pass'),
            array('17', 'Merit'),
            array('18', 'Distinction'),
            // array('19', 'Fail'),
            array('20', 'Selected'),
            array('21', 'Not Selected'),
            array('22', 'In-progress with assessor'),
            array('23', 'In Progress'),
            array('24', 'To be sent'),
            array('25', 'Not applicable'),
            array('26', 'To be sampled'),
            array('27', 'Awaiting BCS confirmation'),
            array('28', 'EPA Ready'),
            array('29', 'Sent'),
            array('30', 'Chased'),
            array('31', 'Complete'),
            array('32', 'Not Sent'),
            array('33', 'Yes'),
            array('34', 'No'),
            array('35', 'Yes'),
            array('36' , 'No'),
            array('37', 'Not Set'),
            array('38', 'Not Completed'),
            array('39', 'Required'),
            array('40', 'Not Required'),
            array('41', 'Fail- continue'),
            array('42', 'Fail- completion non-achiever'),
            array('43', 'Awaiting EPA confirmation'),
            // array('44', 'Involvement - PEED Concern'),
            // array('45', 'Owning'),
            // array('46', 'No action'),
            // array('47', 'Action plan in place'),
            // array('48', 'Involvement - LAR'),
            // array('49', 'PEED Cause'),
        );
    }

    public static function getDDLTestLocation()
    {
        return  array(
            array('Newcastle', 'Newcastle'),
            array('Darlington', 'Darlington'),
            array('Birmingham', 'Birmingham'),
            array('Coventry', 'Coventry'),
            array('Luton', 'Luton'),
            array('Nottingham', 'Nottingham'),
            array('Preston', 'Preston'),
            array('Northampton', 'Northampton'),
            array('Manchester', 'Manchester'),
            array('Leeds', 'Leeds'),
            array('Sheffield', 'Sheffield')
        );
    }

    public static function getListTestLocation()
    {
        return
            array(
                'Newcastle' => 'Newcastle',
                'Darlington' => 'Darlington',
                'Birmingham' => 'Birmingham',
                'Coventry' => 'Coventry',
                'Luton' => 'Luton',
                'Nottingham' => 'Nottingham',
                'Preston' => 'Preston',
                'Northampton' => 'Northampton',
                'Manchester' => 'Manchester',
                'Leeds' => 'Leeds',
                'Sheffield' => 'Sheffield'
            );
    }

    public static function getDDLLARRAGRating()
    {
        return  array(
            // array('1', 'LAR - Terminate'),
            // array('2', 'LAR - Tolerate'),
            // array('3', 'LAR - Treat'),
            // array('4', 'BIL LAR - Terminate'),
            // array('5', 'BIL LAR - Tolerate'),
            // array('6', 'BIL LAR - Treat'),
            // array('7', 'High Risk LAR - Terminate'),
            // array('8', 'High Risk LAR - Tolerate'),
            // array('9', 'High Risk LAR - Treat'),
            // array('10', 'High Risk BIL LAR - Terminate'),
            // array('11', 'High Risk BIL LAR - Tolerate'),
            // array('12', 'High Risk BIL LAR - Treat')
            array('G', 'Green'),
            array('A', 'Amber'),
            array('R', 'Red'),
        );
    }

    public static function getListLARRAGRating()
    {
        return
            array(
                '1' => 'LAR - Terminate',
                '2' => 'LAR - Tolerate',
                '3' => 'LAR - Treat',
                '4' => 'BIL LAR - Terminate',
                '5' => 'BIL LAR - Tolerate',
                '6' => 'BIL LAR - Treat',
                '7' => 'High Risk LAR - Terminate',
                '8' => 'High Risk LAR - Tolerate',
                '9' => 'High Risk LAR - Treat',
                '10' => 'High Risk BIL LAR - Terminate',
                '11' => 'High Risk BIL LAR - Tolerate',
                '12' => 'High Risk BIL LAR - Treat',
                'G' => 'Green',
                'A' => 'Amber',
                'R' => 'Red',
            );
    }

    public static function getDDLOpTaskType()
    {
        return  array(
            array('1', 'On Programme'),
            array('2', 'Re-Sit'),
        );
    }

    public static function getListOpTaskType()
    {
        return
            array(
                '1' => 'On Programme',
                '2' => 'Re-Sit',
            );
    }

    public static function getDdlLeaverMotive()
    {
        return [
            ['21', 'Business Performance'],
            ['22', 'Business Environment'],
            // ['50', 'Business Commitment'],
            ['28', 'Health'],
            ['29', 'Mental Health & Wellbeing'],
            ['51', 'Exposure/Role Concerns'],
            ['52', 'Role Change'],
            // ['39', 'Higher/Further Education'],
            ['53', 'Employer - Lack of commitment'],
            ['54', 'Learner - Lack of commitment'],
            //['40', 'Lack of commitment/Interest'],
            ['41', 'New Job - Salary'],
            ['42', 'New Job - Opportunity'],
            ['43', 'New Job - Industry Change'],
            ['55', 'Lack of progress on apprenticeship'],
            //['23', 'Incorrect Job Role/Role Change'],
            ['45', 'Apprentice Capability'],
            ['46', 'Apprentice Conduct'],
            ['47', 'Programme not met expectations'],
            ['48', 'Role not met expectations'],
            // ['49', 'Maternity'],
        ];
    }

    public static function getListLeaverMotive()
    {
        return [
	    '9' => 'Apprentice Performance',
            '21' => 'Business Performance',
            '22' => 'Business Environment',
            '50' => 'Business Commitment',
            '28' => 'Health',
            '29' => 'Mental Health & Wellbeing',
            '51' => 'Exposure/Role Concerns',
            '52' => 'Role Change',
            '39' => 'Higher/Further Education',
            '53' => 'Employer - Lack of commitment',
            '54' => 'Learner - Lack of commitment',
            '40' => 'Lack of commitment/Interest',
            '41' => 'New Job - Salary',
            '42' => 'New Job - Opportunity',
            '43' => 'New Job - Industry Change',
            '55' => 'Lack of progress on apprenticeship',
            '23' => 'Incorrect Job Role',
            '45' => 'Apprentice Capability',
            '46' => 'Apprentice Conduct',
            '47' => 'Programme not met expectations',
            '48' => 'Role not met expectations',
            '49' => 'Maternity',
	    '35' => 'Dissatisfied with Baltic',
		'5' => 'Health & Wellbeing',
		'1' => 'New Job',	
        ];
    }

    public static function getDDLLARReasonOnly()
    {
        return [
            ['28', 'Business Performance'],
            ['29', 'Business Environment'],
            ['50', 'Business Commitment'],
            ['37', 'Health'],
            ['38', 'Mental Health & Wellbeing'],
            ['51', 'Exposure/Role Concerns'],
            ['52', 'Role Change'],
            ['39', 'Higher/Further Education'],
            ['53', 'Employer - Lack of commitment'],
            ['54', 'Learner - Lack of commitment'],
            ['41', 'New Job - Salary'],
            ['42', 'New Job - Opportunity'],
            ['43', 'New Job - Industry Change'],
            ['55', 'Lack of progress on apprenticeship'],
            ['45', 'Apprentice Capability'],
            ['46', 'Apprentice Conduct'],
            ['47', 'Programme not met expectations'],
            ['48', 'Role not met expectations'],
            // ['40', 'Lack of commitment/Interest'],
            // ['44', 'Incorrect Job Role/Role Change'],
        ];

    }

    public static function getDDLLARReason()
    {
        /*return  array(
            array('1', '1: Performance Issues/Concerns'),
            array('2', '2: Attendance/Timekeeping'),
            array('3', '3: Attitude/Work Ethic'),
            array('4', '4: Lack of progress'),
            array('5', '5: Incorrect Job Role'),
            array('6', '6: Personal reasons/Health'),
            array('7', '7: AWOL'),
            array('8', '8: Funding'),
            array('9', '9: Capability'),
            array('10', '10: Learner sourcing own role'),
            array('11', '11: Change of Employer - Transferring Apprenticeship'),
            array('12', '12: Learner concerns with workplace'),
            array('13', '13: Resignation'),
            array('14', '13.1: New Job - Transferring Apprenticeship'),
            array('15', '13.2: New Job - Not Transferring Apprenticeship'),
            array('16', '13.3: New Job - None Relevant to programme'),
            array('17', '13.4: Education'),
            array('18', '13.5: No longer interested in subject area'),
            array('19', '13.6: Other'),
            array('20', '14: Lack of commitment - Learner & Employer'),
            array('21', '14.1: Unhappy with course'),
            array('22', '14.2: Unhappy with delivery model'),
            array('23', '14.3: Uncontactable'),
            array('24', '14.4: Unwillingness to commit to programme requirements'),
            array('25', '15: Redundancy'),
            array('26', '15.1: Sales LAR'),
            array('27', '15.2: None Sales LAR - Does not want to continue')
        );*/
        /*
        return  array(
            array('1', 'New Job'),
            array('2', 'Incorrect job role'),
            array('3', 'Lack of progress'),
            array('4', 'Alternative Education'),
            array('5', 'Health'),
            array('6', 'Personal issues'),
            array('7', 'Lack of Commitment from Apprentice'),
            array('8', 'Lack of Commitment from Employer'),
            array('9', 'Performance'),
            array('10', 'Attendance'),
            array('11', 'AWOL'),
            array('12', 'Attitude'),
            array('13', 'Unhappy with apprenticeship'),
            array('14', 'Employer Uncontactable'),
            array('15', 'Redundancy'),
            array('16', 'Capability'),
            array('17', 'Funding'),
            array('18', 'Change of Employer'),
            array('19', 'Concerns with Workplace'),
            array('20', 'Other'),
            array('21', 'Job role concerns'),
            array('22', 'Furlough'),
            array('23', 'Working from home'),
        );*/
        return [
            ['28', 'Business Performance'],
            ['29', 'Business Environment'],
            ['50', 'Business Commitment'],
            ['37', 'Health'],
            ['38', 'Mental Health & Wellbeing'],
            ['51', 'Exposure/Role Concerns'],
            ['52', 'Role Change'],
            ['39', 'Higher/Further Education'],
            ['53', 'Employer - Lack of commitment'],
            ['54', 'Learner - Lack of commitment'],
            ['41', 'New Job - Salary'],
            ['42', 'New Job - Opportunity'],
            ['43', 'New Job - Industry Change'],
            ['55', 'Lack of progress on apprenticeship'],
            ['45', 'Apprentice Capability'],
            ['46', 'Apprentice Conduct'],
            ['47', 'Programme not met expectations'],
            ['48', 'Role not met expectations'],
            // ['40', 'Lack of commitment/Interest'],
            // ['44', 'Incorrect Job Role/Role Change'],
        ];

    }

    public static function getListLARReason()
    {
        /*return
            array(
                '1' => '1: Performance Issues/Concerns',
                '2' => '2: Attendance/Timekeeping',
                '3' => '3: Attitude/Work Ethic',
                '4' => '4: Lack of progress',
                '5' => '5: Incorrect Job Role',
                '6' => '6: Personal reasons/Health',
                '7' => '7: AWOL',
                '8' => '8: Funding',
                '9' => '9: Capability',
                '10' => '10: Learner sourcing own role',
                '11' => '11: Change of Employer - Transferring Apprenticeship',
                '12' => '12: Learner concerns with workplace',
                '13' => '13: Resignation',
                '14' => '13.1: New Job - Transferring Apprenticeship',
                '15' => '13.2: New Job - Not Transferring Apprenticeship',
                '16' => '13.3: New Job - None Relevant to programme',
                '17' => '13.4: Education',
                '18' => '13.5: No longer interested in subject area',
                '19' => '13.6: Other',
                '20' => '14: Lack of commitment - Learner & Employer',
                '21' => '14.1: Unhappy with course',
                '22' => '14.2: Unhappy with delivery model',
                '23' => '14.3: Uncontactable',
                '24' => '14.4: Unwillingness to commit to programme requirements',
                '25' => '15: Redundancy',
                '26' => '15.1: Sales LAR',
                '27' => '15.2: None Sales LAR - Does not want to continue'
            );*/
        return  array(
            '3' => 'Lack of progress',
            '4' => 'Alternative Education',
            '6' => 'Personal issues',
            '7' => 'Lack of Commitment from Apprentice',
            '8' => 'Lack of Commitment from Employer',
            '10' => 'Attendance',
            '11' => 'AWOL',
            '12' => 'Attitude',
            '13' => 'Unhappy with apprenticeship',
            '14' => 'Employer Uncontactable',
            '15' => 'Redundancy',
            '17' => 'Funding',
            '18' => 'Change of Employer',
            '19' => 'Concerns with Workplace',
            '20' => 'Other',
            '21' => 'Job role concerns',
            '22' => 'Furlough',
            '23' => 'Working from home',
            '28' => 'Business Performance',
            '29' => 'Business Environment',
            '2' => 'Incorrect job role',
            '9' => 'Apprentice Performance',
            '5' => 'Health & Wellbeing',
            '1' => 'New Job',
            '16' => 'Capability',
            '35' => 'Dissatisfied with Baltic',
            '36' => 'Job Role Change',
            '37' => 'Health',
            '38' => 'Mental Health & Wellbeing',
            '39' => 'Higher/Further Education',
            '40' => 'Lack of commitment/Interest',
            '41' => 'New Job - Salary',
            '42' => 'New Job - Opportunity',
            '43' => 'New Job - Industry Change',
            '44' => 'Incorrect Job Role/Role Change',
            '45' => 'Apprentice Capability',
            '46' => 'Apprentice Conduct',
            '47' => 'Programme not met expectations',
            '48' => 'Role not met expectations',
            '50' => 'Business Commitment',
        );
    }

    public static function getDDLLARCause()
    {
        return  array(
//            array('1', '1: New Job'),
//            array('2', '2: Incorrect job role'),
//            array('3', '3: Lack of Progress'),
//            array('4', '4: Alternate Education'),
//            array('5', '5: Health'),
//            array('6', '6: Personal issues'),
//            array('7', '7: Lack of commitment from Apprentice'),
//            array('8', '8: Lack of commitment from Employer'),
//            array('9', '9: Performance'),
//            array('10', '10: Attendance'),
//            array('11', '11: AWOL'),
//            array('12', '12: Attitude'),
//            array('13', '13: Unhappy with apprenticeship'),
//            array('14', '14: Employer Uncontactable'),
//            array('15', '15: Redundancy'),
//            array('16', '16: Capability'),
//            array('17', '17: Funding'),
//            array('18', '18: Change of Employer'),
//            array('19', '19: Concerns with Workplace'),
//            array('20', '20: Other'),
            array('21', 'Business Performance'),
            array('22', 'Business Environment'),
            array('23', 'Incorrect Job Role'),
            array('24', 'Apprentice Performance'),
            //array('25', 'Health & Wellbeing'),
            array('26', 'New Job'),
            array('27', 'Dissatisfied with Baltic'),
            array('28', 'Health'),
            array('29', 'Mental Health & Wellbeing'),
        );
    }

    public static function getListLARCause()
    {
        return
            array(
                '1' => '1: New Job',
                '2' => '2: Incorrect job role',
                '3' => '3: Lack of Progress',
                '4' => '4: Alternate Education',
                '5' => '5: Health',
                '6' => '6: Personal issues',
                '7' => '7: Lack of commitment from Apprentice',
                '8' => '8: Lack of commitment from Employer',
                '9' => '9: Performance',
                '10' => '10: Attendance',
                '11' => '11: AWOL',
                '12' => '12: Attitude',
                '13' => '13: Unhappy with apprenticeship',
                '14' => '14: Employer Uncontactable',
                '15' => '15: Redundancy',
                '16' => '16: Capability',
                '17' => '17: Funding',
                '18' => '18: Change of Employer',
                '19' => '19: Concerns with Workplace',
                '20' => '20: Other',
                '21' => 'Business Performance',
                '22' => 'Business Environment',
                '23' => 'Incorrect Job Role',
                '24' => 'Apprentice Performance',
                '25' => 'Health & Wellbeing',
                '26' => 'New Job',
                '27' => 'Dissatisfied with Baltic',
                '28' => 'Health',
                '29' => 'Mental Health & Welbeing',
                '39' => 'Higher/Further Education',
                '40' => 'Lack of commitment/Interest',
                '41' => 'New Job - Salary',
                '42' => 'New Job - Opportunity',
                '43' => 'New Job - Industry Change',
                '45' => 'Apprentice Capability',
                '46' => 'Apprentice Conduct',
                '47' => 'Programme not met expectations',	
                '48' => 'Role not met expectations',
                '50' => 'Business Commitment',
                '51' => 'Exposure/Role Concerns',
                '52' => 'Exposure/Role Concerns',
                '53' => 'Employer - Lack of commitment',
                '54' => 'Learner - Lack of commitment',
                '55' => 'Lack of progress on apprenticeship',
            );
    }

    public static function getDDLOpLeaverReasons(PDO $link)
    {
        //return DAO::getResultset($link, "SELECT id, CONCAT(sub_code, ' ', sub_category), CONCAT(`code`, '. ', category) FROM lookup_op_leaver_reasons ;");
        return  array(
            array('2', 'Dismissal'),
            array('1', 'Resignation'),
            array('8', 'Redundancy/End of Contract'),
            array('3', 'Removed from apprenticeship - Apprentice'),
            array('11', 'Removed from apprenticeship - Baltic'),
            array('14', 'Removed from apprenticeship - Employer'),
            // array('4', 'Redundancy'),
            // array('5', 'End of Contract'),
            // array('6', 'Removed'),
            // array('7', 'Candidate Resignation'),
            // array('9', 'Employer led Dismissal'),
            // array('10', 'Job Role Change'),
            //array('12', 'Health'),
            //array('13', 'Mental Health & Wellbeing'),
            
        );
    }

    public static function getListOpLeaverReasons()
    {
        return
            array(
                '1' => 'Resignation',
                '2' => 'Dismissal',
                '3' => 'Removed from apprenticeship - Apprentice',
                '4' => 'Redundancy',
                '5' => 'End of Contract',
                '6' => 'Removed',
                '7' => 'Candidate Resignation',
                '8' => 'Redundancy/End of Contract',
                '9' => 'Employer led Dismissal',
                '10' => 'Job Role Change',
                '11' => 'Removed from apprenticeship - Baltic',
                //'12' => 'Health',
                //'13' => 'Mental Health & Wellbeing',
                '14' => 'Removed from apprenticeship - Employer',
            );
    }

    public static function getTrainingStatusDesc($value)
    {
        $list = array(
            '1' => '1- Continue',
            '2' => '2- Completed',
            '3' => '3- Withdrawn',
            '6' => '6- Temporarily Withdrawn'
        );
        return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getTrainingOutcomeDesc($value)
    {
        $list = array(
            '1' => '1- Achieved',
            '2' => '2- Partial Achievement',
            '3' => '3- No Achievement',
            '6' => '6- Achieved but uncashed (AS-levels only)',
            '7' => '7- Achieved and cashed (AS-levels only)',
            '8' => '8- Learning activities are complete but the outcome is not yet known'
        );
        return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getListAdditionalSupportEvidence()
    {
        return array('R' => 'Requested', 'P' => 'Provided', 'D' => 'Declined', 'A' => 'Accepted' );
    }

    public static function getDDLAdditionalSupportEvidence()
    {
        return  array(array('R', 'Requested'),array('P', 'Provided'),array('D', 'Declined'),array('A', 'Accepted'));
    }

    public static function getDDLInductionARM(PDO $link)
    {
        return  array(
            array('26302', 'Abbie Todd'),
            array('22249', 'Rachel Armstrong'),
            array('22235', 'Shelby Cooper')
        );
    }

    public static function getInductionARM($value = '')
    {
        $arm = [
            '22235' => 'Shelby Cooper',
            '22249' => 'Rachel Armstrong',
            '26302' => 'Abbie Todd'
        ];

        return isset($arm[$value]) ? $arm[$value] : $value;
    }

    public static function getDDLWebcam(PDO $link)
    {
        return  array(
            array('NR', 'Not Required'),
            array('S', 'Sent'),
        );
    }

    public static function getListWebcam($value = '')
    {
        $arm = [
            'NR' => 'Not Required',
            'S' => 'Sent'
        ];

        return isset($arm[$value]) ? $arm[$value] : $value;
    }

    public static function getDDLEPAOwner()
    {
        return  array(
            array('C', 'Coordinator'),
            array('LM', 'Learning Mentor')
        );
    }

    public static function getListEPAOwner($value = '')
    {
        $arm = [
            'C' => 'Coordinator',
            'LM' => 'Learning Mentor'
        ];

        return isset($arm[$value]) ? $arm[$value] : $value;
    }

    public static function getListInductionLdd()
    {
        return [
            '1' => 'Emotional/Behaviour difficulties',
            '2' => 'Multiple difficulties',
            '4' => 'Vision impairment',
            '5' => 'Hearing impairment',
            '6' => 'Disability affecting mobility',
            '9' => 'Mental health difficulty',
            'MLD' => 'Moderate Learning Difficulty',
            'SLD' => 'Severe Learning Difficulty',
            'DXA' => 'Dyslexia',
            'DLA' => 'Dyscalculia',
            'ASD' => 'Autism Spectrum Disorder',
            '15' => 'Asperger\'s syndrome',
            '16' => 'Temporary disability after illness',
            '17' => 'Speech, language, and communication needs',
            '93' => 'Other physical disability',
            '95' => 'Other medical condition',
            '97' => 'Other disability',
            '98' => 'Prefer not to say',
            'OSLD' => 'Other Specific Learning Difficulty',
            'OTH' => 'Other (Additional Data Required)',
            'PNS' => 'Prefer Not To Say',
            'NP' => 'Not provided',
            'N' => 'None',
        ];
    }

    public static function getDDLInductionLdd()
    {
        return  [
            ['1', 'Emotional/Behaviour difficulties'],
            ['2', 'Multiple difficulties'],
            ['4', 'Vision impairment'],
            ['5', 'Hearing impairment'],
            ['6', 'Disability affecting mobility'],
            ['9', 'Mental health difficulty'],
            ['MLD', 'Moderate Learning Difficulty'],
            ['SLD', 'Severe Learning Difficulty'],
            ['DXA', 'Dyslexia'],
            ['DLA', 'Dyscalculia'],
            ['ASD', 'Autism Spectrum Disorder'],
            ['15', 'Asperger\'s syndrome'],
            ['16', 'Temporary disability after illness'],
            ['17', 'Speech, language, and communication needs'],
            ['93', 'Other physical disability'],
            ['95', 'Other medical condition'],
            ['97', 'Other disability'],
            ['98', 'Prefer not to say'],
            // ['OSLD', 'Other Specific Learning Difficulty'],
            // ['OTH', 'Other (Additional Data Required)'],
            // ['PNS', 'Prefer Not To Say'],
            // ['NP', 'Not provided'],
            // ['N', 'None'],
        ];
    }

    public static function getSupportConversationDdl()
    {
        return [
            [1, 'Engaged and Support Required'],
            [2, 'Engaged and No Support Required'],
            [3, 'Did Not Engage'],
        ];
    }

    public static function getSupportConversationList()
    {
        return [
            1 => 'Engaged and Support Required',
            2 => 'Engaged and No Support Required',
            3 => 'Did Not Engage',
        ];
    }

    public static function getDdlSen()
    {
        return [
            [1, 'Mental Health'],
            [2, 'Stress'],
            [3, 'Anxiety (undiagnosed)'],
            [4, 'Anxiety'],
            [5, 'SEN'],
        ];
    }

    public static function getListSen()
    {
        return [
            1 => 'Mental Health',
            2 => 'Stress',
            3 => 'Anxiety (undiagnosed)',
            4 => 'Anxiety',
            5 => 'SEN',
        ];
    }

    public static function getListPeedCause()
    {
        return [
            '1' => 'Covid - Lack of Evidence',
            '2' => 'Covid - Performance',
            '3' => 'DXC',
            '4' => 'Furlough',
            '5' => 'Health',
            '6' => 'High Rework - Missed Deadlines',
            '7' => 'Job Role Concerns',
            '8' => 'Lack of Commitment - Apprentice - Capability',
            '9' => 'Lack of Commitment - Apprentice - Missed Deadlines',
            '10' => 'Lack of Evidence',
            '11' => 'Lack of Time in Workplace',
            '12' => 'Learning Mentor Knowledge',
            '13' => 'Performance - Concerns with Apprentice',
            '14' => 'Redundancy',
        ];
    }

    public static function getDDLPeedCause()
    {
        return  [
            ['1', 'Covid - Lack of Evidence'],
            ['2', 'Covid - Performance'],
            ['3', 'DXC'],
            ['4', 'Furlough'],
            ['5', 'Health'],
            ['6', 'High Rework - Missed Deadlines'],
            ['7', 'Job Role Concerns'],
            ['8', 'Lack of Commitment - Apprentice - Capability'],
            ['9', 'Lack of Commitment - Apprentice - Missed Deadlines'],
            ['10', 'Lack of Evidence'],
            ['11', 'Lack of Time in Workplace'],
            ['12', 'Learning Mentor Knowledge'],
            ['13', 'Performance - Concerns with Apprentice'],
            ['14', 'Redundancy'],
        ];
    }

    public static function getListProgressionRating()
    {
        return [
            'H' => 'Hot',
            'W' => 'Warm',
            'C' => 'Cold',
        ];
    }

    public static function getDdlProgressionRating()
    {
        return  [
            ['H', 'Hot'],
            ['W', 'Warm'],
            ['C', 'Cold'],
        ];
    }

    public static function getListLslInvolvementStatus()
    {
        return [
            'O' => 'Owning',
            'I' => 'Involvement',
            'IL' => 'Involvement - LAR',
            'IB' => 'Involvement - BIL',
            'AP' => 'Action Plan',
            'NA' => 'No Action',
        ];
    }

    public static function getDdlInvovlementStatus()
    {
        return  [
            ['O', 'Owning'],
            ['I', 'Involvement'],
            ['IL', 'Involvement - LAR'],
            ['IB', 'Involvement - BIL'],
            ['AP', 'Action Plan'],
            ['NA', 'No Action'],
        ];
    }

    public static function getListPortfolioPredictions()
    {
        return [
            'P' => 'Pass',
            'M' => 'Merit',
            'D' => 'Distinction',
        ];
    }

    public static function getDdlPortfolioPredictions()
    {
        return  [
            ['P', 'Pass'],
            ['M', 'Merit'],
            ['D', 'Distinction'],
        ];
    }

    public static function getListReschedulingCategory()
    {
        return [
            'lar' => 'LAR',
            'bil' => 'BIL',
            'lvr' => 'Leaver',
            'ecc' => 'Employer cannot commit',
            'los' => 'Learner off sick',
            'loh' => 'Learner on holiday',
            'oth' => 'Other',
            'brs' => 'Baltic re-schedule',
            'err' => 'Co-ordinator error',
            'lcc' => 'Learner cannot commit',
            'exm' => 'Exempt',
            'tec' => 'Technical',
            'id' => 'ID',
            'add' => 'Address',
        ];
    }

    public static function getDdlReschedulingCategory()
    {
        return  [
            ['lar', 'LAR'],
            ['bil', 'BIL'],
            ['lvr', 'Leaver'],
            ['ecc', 'Employer cannot commit'],
            ['los', 'Learner off sick'],
            ['loh', 'Learner on holiday'],
            ['oth', 'Other'],
            ['brs', 'Baltic re-schedule'],
            ['err', 'Co-ordinator error'],
            ['lcc', 'Learner cannot commit'],
            ['exm', 'Exempt'],
            ['tec', 'Technical'],
            ['id', 'ID'],
            // ['add', 'Address'],
        ];
    }

    public static function getListLarRiskOf()
    {
        return [
            'lvr' => 'Leaver',
            'lach' => 'Late Achiever',
        ];
    }

    public static function getDdlLarRiskOf()
    {
        return  [
            ['lvr', 'Leaver'],
            ['lach', 'Late Achiever'],
        ];
    }

    public static function getListArmProgressionStatus()
    {
        return [
            'ams' => 'At Meeting Stage',
            'le' => 'Leaving Employer',
            'np' => 'Not Progressing',
            'p' => 'Progressed',
            'pp' => 'Planned Progression',
	    'op' => 'On Programme',
            'sp' => 'Summative Check Passed',
            'ap' => 'Apprenticeship Passed',
        ];
    }

    public static function getDdlArmProgressionStatus()
    {
        return  [
            ['ams', 'At Meeting Stage'],
            ['le', 'Leaving Employer'],
            ['np', 'Not Progressing'],
            ['pp', 'Planned Progression'],
            ['p', 'Progressed'],
            ['op', 'On Programme'],
            ['sp', 'Summative Check Passed'],
            ['ap', 'Apprenticesip Passed'],
        ];
    }

    public static function getListArmReasonForNonProgression()
    {
        return [
            'be' => 'Bad Experience',
            'loc' => 'Lack of Commitment',
            'al' => 'Alternative Learning',
            'bfl' => 'Break from Learning',
            'ncf' => 'No Course to Fit',
            'nrn' => 'New Role Not Relevant',
            'br' => 'Baltic Rejected',
            'fe' => 'Failed EPA',
            'le' => 'Left Employer',
        ];
    }

    public static function getDdlArmReasonForNonProgression()
    {
        return  [
            ['al', 'Alternative Learning'],
            ['be', 'Bad Experience'],
            ['br', 'Baltic Rejected'],
            ['bfl', 'Break from Learning'],
            ['fe', 'Failed EPA'],
            ['loc', 'Lack of Commitment'],
            ['le', 'Left Employer'],
            ['ncf', 'No Course to Fit'],
            ['nrn', 'New Role Not Relevant'],
        ];
    }

    public static function getDdlEmployerMentor()
    {
        return  [
            ['1', 'A great mentor, they do the job and understand sufficiently the programme and are 100% committed'],
            ['2', 'They understand the programme and but are not very involved in the apprenticeships'],
            ['3', 'Mentor not fit/relevant to the learner'],
        ];
    }

    public static function getCrmNoteRating()
    {
        return [
            ['S1', 'Stage 1'],
            ['S2', 'Stage 2'],
            ['S3', 'Stage 3'],
        ];
    }

    public static function getCrmNoteRatingList()
    {
        return [
            'S1' => 'Stage 1',
            'S2' => 'Stage 2',
            'S3' => 'Stage 3',
        ];
    }

    public static function getCrmNoteConcerns()
    {
        return [
            ['L', 'Learner'],
            ['E', 'Employer'],
            ['B', 'Both'],
            ['T', 'Training Provider - Baltic'],
        ];
    }

    public static function getCrmNoteConcernsList()
    {
        return [
            'L' => 'Learner',
            'E' => 'Employer',
            'B' => 'Both',
            'T' => 'Training Provider - Baltic',
        ];
    }

    public static function getDdlReasonOutsideMatrix()
    {
        return [
            ['SC', 'Scheduling Contraints'],
            ['ER', 'Employer Request'],
            ['R', 'Reschedule'],
        ];
    }

    public static function getPeedOwnersDdl()
    {
        return [
            ['Hannah Gibson', 'Hannah Gibson'],
            ['Learning Mentor', 'Learning Mentor'],
        ];
    }

    public static function getDdlArmProgressionRating()
    {
        return [
            // ['l', 'Likely'],
            // ['50', '50/50'],
            // ['u', 'Unlikely'],
            // ['nd', 'Not Discussed'],
            // ['hl', 'Highly Likely'],
            ['dp', 'Definitely Progressing - 100% (first available cohort'],
            ['db', 'Definitely Progressing - Break'],
            ['75', '75%'],
            ['50', '50%'],
            ['25', '25%'],
            ['np', 'Not progressing'],
            ['pc', 'Progression Concern'],
        ];
    }

    public static function getListArmProgressionRating()
    {
        return [
            'l' => 'Likely',
            '50' => '50/50',
            'u' => 'Unlikely',
            'nd' => 'Not Discussed',
            'hl' => 'Highly Likely',
            'dp' => 'Definitely Progressing - 100% (first available cohort',
            'db' => 'Definitely Progressing - Break',
            '75' => '75%',
            '50' => '50%',
            '25' => '25%',
            'np' => 'Not progressing',
            'pc' => 'Progression Concern',
        ];
    }

    public static function getDdlLearnerCrmReason()
    {
        return [
            ['9', 'Apprentice Performance'],
            ['28', 'Business Performance'],
            ['29', 'Business Environment'],
            ['16', 'Capability'],
            ['35', 'Dissatisfied with Baltic'],
            ['5', 'Health & Wellbeing'],
            ['2', 'Incorrect job role'],
            ['36', 'Job Role Change'],
            ['37', 'Low Salary'],
            ['1', 'New Job'],
            ['40', 'Onboarding Concerns'],
            ['39', 'Previous leavers from employer'],
            ['38', 'Travel outside of what we would like'],
        ];
    }

    public static function getListLearnerCrmReason()
    {
        return [
            '28' => 'Business Performance',
            '29' => 'Business Environment',
            '2' => 'Incorrect job role',
            '9' => 'Apprentice Performance',
            '5' => 'Health & Wellbeing',
            '1' => 'New Job',
            '16' => 'Capability',
            '35' => 'Dissatisfied with Baltic',
            '36' => 'Job Role Change',
            '37' => 'Low Salary',
            '38' => 'Travel outside of what we would like',
            '39' => 'Previous leavers from employer',
            '40' => 'Onboarding Concerns',
        ];
    }

    public static function getDdlCerts()
    {
        return [
            ['1', 'Received'],	
            //['2', 'Not Received'],
            ['3', 'Before FS Process'],
            ['4', 'Quals confirmed on PLR'],
            ['5', 'Certificate Requested'],
            ['6', 'Certificates Re-print requested'],
            ['7', 'No Qual - SMT Approved'],
        ];
    }

    public static function getListCerts()
    {
        return [
            '1' => 'Received',
            '2' => 'Not Received',
            '3' => 'Before FS Process',
            '4' => 'Quals confirmed on PLR',
            '5' => 'Certificate Requested',
            '6' => 'Certificates Re-print requested',
            '7' => 'No Qual - SMT Approved',
            'Y' => 'Yes',
            'YP' => 'Yes - Pending Evidence',
            'N' => 'No - SMT Approved',
        ];
    }

    public static function getDdlSupportSessionsSubjects()
    {
        return [
            [0,"Assessment Plans"],
            [1,"Reflective Hours"],
            [2,"Functional Skills"],
            [3,"Others"],
            [4,"Competency Workshops"],
        ];
    }

    public static function getListSupportSessionsSubjects()
    {
        return [
            0 => "Assessment Plans",
            1 => "Reflective Hours",
            2 => "Functional Skills",
            3 => "Others",
            4 => "Competency Workshops",
        ];
    }

    public static function getDdlInductionMoved()
    {
        return [
            [1, "Moved within month"],
            [2, "Moved to another month"],
        ];
    }

    public static function getListInductionMoved()
    {
        return [
            1 => "Moved within month",
            2 => "Moved to another month",
        ];
    }

    public static function getDdlInductionMovedReason()
    {
        return [
            // [1, "Compliance Paperwork - Employer"],
            // [2, "Compliance Paperwork - Learner"],
            // [3, "Digital Account Not Created"],
            // [4, "Employer Concerns"],
            // [6, "Workload"],
            ['9', 'Employer Paperwork'],
            ['10', 'Learner Paperwork'],
            ['11', 'Assessments Incomplete'],
            ['12', 'DAS Account Not Created'],
            [7, "Holding Inductions"],
            [5, "Sickness"],
            ['13', 'Start Date Changed'],
            [8, "Other"],
        ];
    }

    public static function getListInductionMovedReason()
    {
        return [
            1 => "Compliance Paperwork - Employer",
            2 => "Compliance Paperwork - Learner",
            3 => "Digital Account Not Created",
            4 => "Employer Concerns",
            5 => "Sickness",
            6 => "Workload",
            7 => "Holding Inductions",
            8 => "Other",
            9 => "Employer Paperwork",
            10 => "Learner Paperwork",
            11 => "Assessments Incomplete",
            12 => "DAS Account Not Created",
            13 => "Start Date Changed",
        ];
    }

    public static function getDdlLeaverForm()
    {
        return [
            ['C', 'Completed'],
            ['N', 'No'],
            ['S', 'Sent'],
	        ['Y', 'Yes'],
        ];
    }

    public static function getListLeaverForm()
    {
        return [
            'C' => 'Completed',
            'N' => 'No',
            'S' => 'Sent',
	        'Y' => 'Yes',
        ];
    }	

    public static function getArmChanceToProgressDdl()
    {
        return [
            ['1', 'Potential to progress'],
            ['2', 'Will never progress'],
            ['3', 'Level 4 N/A'],
        ];
    }

    public static function getArmChanceToProgressList()
    {
        return [
            '1' => 'Potential to progress',
            '2' => 'Will never progress',
            '3' => 'Level 4 N/A',
        ];
    }	

    public static function getDdlHoldingInductionReason()
    {
        return [
            // ['1', 'Awaiting confirmed start date'],
            // ['2', 'DBS checks'],
            // ['3', 'DAS'],
            // ['4', 'College/Uni unenrolment'],
            // ['5', 'Employer commitment (On-Boarding struggle to contact)'],
            // ['6', 'Apprentice performance'],
            // ['7', 'Business environment'],
            // ['8', 'Apprentice commitment'],
            ['10', 'Employer Paperwork'],
            ['11', 'Learner Paperwork'],
            ['18', 'Paperwork - Both'],
            ['12', 'DBS / References'],
            ['13', 'DAS Account'],
            ['14', 'Sickness'],
            ['15', 'Start Date Unconfirmed'],
            ['16', 'Awaiting Unenrolment'],
            ['17', 'Employer OOO'],
            ['9', 'Other'],
        ];        
    }

    public static function getListHoldingInductionReason()
    {
        return [
            '1' => 'Awaiting confirmed start date',
            '2' => 'DBS checks',
            '3' => 'DAS',
            '4' => 'College/Uni unenrolment',
            '5' => 'Employer commitment (On-Boarding struggle to contact)',
            '6' => 'Apprentice performance',
            '7' => 'Business environment',
            '8' => 'Apprentice commitment',
            '9' => 'Other',
            '10' => 'Employer Paperwork',
            '11' => 'Learner Paperwork',
            '12' => 'DBS / References',
            '13' => 'DAS Account',
            '14' => 'Sickness',
            '15' => 'Start Date Unconfirmed',
            '16' => 'Awaiting Unenrolment',
            '17' => 'Employer OOO',
            '18' => 'Paperwork - Both',
        ];        
    }

    public static function getDdlLeaverPositiveOutcome()
    {
        return [
            [1, 'Higher/Further Education'],
            [2, 'Full Time Role'],
            [3, 'Promotion'],
        ];        
    }

    public static function getListLeaverPositiveOutcome()
    {
        return [
            '1' => 'Higher/Further Education',
            '2' => 'Full Time Role',
            '3' => 'Promotion',
        ];
    }

    public static function getDdlReschedulingType()
    {
        return [
            [1, 'Schedule cancellation'],
            [2, 'Prior to Reminders'],
            [3, 'Cancellation within 4 weeks notice'],
            //[4, 'Cancellation with 14+ days notice'],
            [5, 'Cancellation with 7+ days notice'],
            [6, 'Cancelled within 7 days of course start date'],
            [7, 'Cancelled on the day of course'],
            [8, 'Other'],
        ];
    }
    
    public static function getListReschedulingType()
    {
        return [
            1 => 'Schedule cancellation',
            2 => 'Prior to Reminders',
            3 => 'Cancellation within 4 weeks notice',
            4 => 'Cancellation with 14+ days notice',
            5 => 'Cancellation with 7+ days notice',
            6 => 'Cancelled within 7 days of course start date',
            7 => 'Cancelled on the day of course',
            8 => 'Other',
        ];
    }

    public static function getDdlRedFlagReason()
    {
        return [
            // ['pq', 'Pre-qual'],
            // ['bc', 'Business commitment'],
            // ['sal', 'Salary'],
            ['appc', 'Apprentice commitment'],
            ['cwa', 'Concerns with apprentice'],
            ['el', 'Eligibility'],
            ['empc', 'Employer commitment'],
            ['ec', 'Employer contact'],
            // ['oth', 'Other'],
        ];
    }	

    public static function getDdlRelatedQualifications()
    {
        return [
            ['1', 'Degree relevant to routeway'],
            ['2', 'Other degree'],
            ['3', 'College qualification relevant to routeway'],
            ['4', 'Online course/bootcamp relevant to routeway'],
            ['5', 'n/a - none of the above'],
        ];
    }

    public static function getListHoldingContractReason()
    {
        return [
            1 => 'Application to be approved',
            2 => 'Levy Application to be made',
            3 => 'Application overlap',
            4 => 'Other',
            5 => 'Data Mismatch',
            6 => 'Reinstatement',
        ];
    }

    public static function getDdlHoldingContractReason()
    {
        return [
            ['1', 'Application to be approved'],
            ['2', 'Levy Application to be made'],
            ['3', 'Application overlap'],
            ['4', 'Other'],
            ['5', 'Data Mismatch'],
            ['6', 'Reinstatement'],
        ];
    }

    public static function getDdlHoldingContractProcessedBy()
    {
        return [
	    ['30067', 'Courtney Finch Easom'],	
            ['14085', 'Hayley Pigford'],
            ['29222', 'Kendra Moore'],
            ['28919', 'Lauren Storey'],
            ['32089', 'Shannon Dale'],
        ];
    }

    public static function getListHoldingContractProcessedBy()
    {
        return [
	    '30067' => 'Courtney Finch Easom',
            '14085' => 'Hayley Pigford',
            '29222' => 'Kendra Moore',
            '28919' => 'Lauren Storey',
            '32089' => 'Shannon Dale',
        ];
    }

    public static function getLddAgeCategoryDdl()
    {
        return [
            ['1', '16-18'],
            ['2', '19-24'],
            ['3', '25-34'],
            ['4', '45-54'],
            ['5', '55-64'],
            ['6', '65+'],
            ['7', 'Prefer not to say'],
        ];
    }

    public static function getLddAgeCategoryList()
    {
        return [
            '1' => '16-18',
            '2' => '19-24',
            '3' => '25-34',
            '4' => '45-54',
            '5' => '55-64',
            '6' => '65+',
            '7' => 'Prefer not to say',
        ];
    }

    public static function getLddGenderIdentDdl()
    {
        return [
            ['1', 'Male'],
            ['2', 'Female'],
            ['3', 'Trans-gender'],
            ['4', 'Non-binary'],
            ['5', 'Other'],
            ['6', 'Prefer not to say'],
        ];
    }

    public static function getLddGenderIdentList()
    {
        return [
            '1' => 'Male',
            '2' => 'Female',
            '3' => 'Trans-gender',
            '4' => 'Non-binary',
            '5' => 'Other',
            '6' => 'Prefer not to say',
        ];
    }

    public static function getLddSexOrientDdl()
    {
        return [
            ['1', 'Bisexual'],
            ['2', 'Gay or Lesbian'],
            ['3', 'Heterosexual'],
            ['4', 'Other'],
            ['5', 'Prefer not to say'],
        ];
    }

    public static function getLddSexOrientList()
    {
        return [
            '1' => 'Bisexual',
            '2' => 'Gay or Lesbian',
            '3' => 'Heterosexual',
            '4' => 'Other',
            '5' => 'Prefer not to say',
        ];
    }

    public static function getLddConditionsDdl()
    {
        return [
            ['15', 'Asperger\'s Syndrome'],
            ['101', 'Attention Deficit Disorder (ADD)'],
            ['102', 'Attention Deficit Hyperactivity Disorder (ADHD)'],
            ['14', 'Autism Spectrum Disorder'],
            ['13', 'Dyscalculia'],
            ['103', 'Dysgraphia'],
            ['12', 'Dyslexia'],
            ['96', 'Other Learning Difficulty'],
            ['104', 'Other Neurodiverse Condition'],
            ['105', 'None'],
        ];
    }

    public static function getLddConditionsList()
    {
        return [
            '15' => 'Asperger\'s Syndrome',
            '101' => 'Attention Deficit Disorder (ADD)',
            '102' => 'Attention Deficit Hyperactivity Disorder (ADHD)',
            '14' => 'Autism Spectrum Disorder',
            '13' => 'Dyscalculia',
            '103' => 'Dysgraphia',
            '12' => 'Dyslexia',
            '96' => 'Other Learning Difficulty',
            '104' => 'Other Neurodiverse Condition',
            '105' => 'None',
        ];
    }

    public static function getLddMentalDdl()
    {
        return [
            ['1', 'Anxiety Disorders and Stress'],
            ['2', 'Bipolar Disorder'],
            ['3', 'Depression'],
            ['4', 'Obsessive-Compulsive Disorder (OCD)'],
            ['5', 'Post-Traumatic Stress Disorder (PTSD)'],
            ['6', 'Stress'],
            ['7', 'Other Mental Health'],
            ['8', 'None'],
        ];
    }

    public static function getLddMentalList()
    {
        return [
            '1' => 'Anxiety Disorders and Stress',
            '2' => 'Bipolar Disorder',
            '3' => 'Depression',
            '4' => 'Obsessive-Compulsive Disorder (OCD)',
            '5' => 'Post-Traumatic Stress Disorder (PTSD)',
            '6' => 'Stress',
            '7' => 'Other Mental Health',
            '8' => 'None',
        ];
    }

    public static function getLddPhysicalDdl()
    {
        return [
            ['106', 'Cerebral Palsy'],
            ['107', 'Cystic Fibrosis'],
            ['108', 'Dyspraxia or Development Coordination Disorder (DCD)'],
            ['109', 'Epilepsy'],
            ['5', 'Hearing Impairment'],
            ['4', 'Vision Impairment'],
            ['97', 'Other Disability Affecting Mobility'],
            ['95', 'Other Medical Condition'],
            ['93', 'Other Physical Disability'],
            ['110', 'None'],
        ];
    }

    public static function getLddPhysicalList()
    {
        return [
            '106' => 'Cerebral Palsy',
            '107' => 'Cystic Fibrosis',
            '108' => 'Dyspraxia or Development Coordination Disorder (DCD)',
            '109' => 'Epilepsy',
            '5' => 'Hearing Impairment',
            '4' => 'Vision Impairment',
            '97' => 'Other Disability Affecting Mobility',
            '95' => 'Other Medical Condition',
            '93' => 'Other Physical Disability',
            '110' => 'None',
        ];
    }

    public static function getDdlDataMismatch()
    {
        return [
            [1, 'Dlock_01: no matching UKPRN found'],
            [2, 'Dlock_02: no matching ULN number'],
            [3, 'Dlock_3: no matching standard code found'],
            [4, 'Dlock_4: no matching framework code'],
            [6, 'Dlock_06: no matching pathway'],
            [7, 'Dlock_07: no matching negotiated price'],
            [8, 'Dlock_08: multiple matching records found'],
            [91, 'Dlock_09: no matching start date'],
            [92, 'Dlock_09: after a change of employer'],
            [10, 'Dlock_10: employer has stopped the record'],
            [11, 'Dlock_11: the employer is not a levy payer'],
            [12, 'Dlock_12: employer has paused the commitment'],
        ];
    }

    public static function getListDataMismatch()
    {
        return [
            1 => 'Dlock_01: no matching UKPRN found',
            2 => 'Dlock_02: no matching ULN number',
            3 => 'Dlock_3: no matching standard code found',
            4 => 'Dlock_4: no matching framework code',
            6 => 'Dlock_06: no matching pathway',
            7 => 'Dlock_07: no matching negotiated price',
            8 => 'Dlock_08: multiple matching records found',
            91 => 'Dlock_09: no matching start date',
            92 => 'Dlock_09: after a change of employer',
            10 => 'Dlock_10: employer has stopped the record',
            11 => 'Dlock_11: the employer is not a levy payer',
            12 => 'Dlock_12: employer has paused the commitment',
        ];
    }	

    

    public static function getListFundingReduction()
    {
        return [
            1 => 'Prior Quals',
            2 => 'Prior Experience',
            3 => 'Prior Quals & Experience',
            4 => 'Employer agreed reduction',
            5 => 'Staff reduction',
            6 => 'Previous apprenticeship completed',
            7 => 'Other (with text box to explain)',
            8 => 'Admin/Processing Error',
            9 => 'No Reduction',
        ];
    }

    public static function getDdlFundingReduction()
    {
        return [
            [1, 'Prior Quals'],
            [2, 'Prior Experience'],
            // [3, 'Prior Quals & Experience'],
            [4, 'Employer agreed reduction'],
            [5, 'Staff reduction'],
            [6, 'Previous apprenticeship completed'],
            [7, 'Other (with text box to explain)'],
            [8, 'Admin/Processing Error'],
            [9, 'No Reduction'],
        ];
    }

    public static function getDdlIpStatus()
    {
        return [
            ['FC', 'Fully Completed'],
            ['E', 'Escalated'],
            ['AFR', 'Awaiting Funding Reduction'],
            ['AS', 'Awaiting Signature'],
        ];
    }

    public static function getDdlIpSkillsScan()
    {
        return [
            ['NC', 'Not Completed'],
            ['RS', 'Requires Signature'],
            ['U', 'Uploaded'],
        ];
    }

    public static function getDdlPriorExpFurtherDetails()
    {
        return [
            ['1', '0-2 Years'],
            ['2', '3-4 Years'],
            ['3', '5+ Years'],
        ];
    }

    public static function getListPriorExpFurtherDetails()
    {
        return [ 
            '1' => '0-2 Years',
            '2' => '3-4 Years',
            '3' => '5+ Years',
        ];
    }

    public static function getDdlArmChanceToProgress()
    {
        return [
            [1, 'ACM - Potential to progress'],
            [2, 'ACM - Employer never progress'],
            [3, 'ACM - Role will never fit'],
            [4, 'NB - After intro - role never fit'],
            [5, 'NB - After intro - chance to progress'],
        ];
    }

    public static function getListArmChanceToProgress()
    {
        return [ 
            1 => 'ACM - Potential to progress',
            2 => 'ACM - Employer never progress',
            3 => 'ACM - Role will never fit',
            4 => 'NB - After intro - role never fit',
            5 => 'NB - After intro - chance to progress',
        ];
    }

    public static function getDdlLarDestination()
    {
        return [
            ['Continuing - No Concern', 'Continuing - No Concern'],
            ['Continuing - Monitoring', 'Continuing - Monitoring'],
            ['EPA Ready', 'EPA Ready'],
            ['BIL', 'BIL'],
            ['Leaver', 'Leaver'],
        ];
    }

    public static function getDdlLeaverLdolEvidence()
    {
        return [
            ['Time Log', 'Time Log'],
            ['Submission', 'Submission'],
            ['Register', 'Register'],
            ['Attendance/Recording', 'Attendance/Recording'],
            ['Certificate', 'Certificate'],
            ['FS', 'FS'],
            ['Other', 'Other'],
        ];
    }

    public static function getDdlEpaAssessmentMethods()
    {
        return [
	    ['Distinction', 'Distinction'],
            ['Pass', 'Pass'],
            ['Merit', 'Merit'],
	    ['Fail', 'Fail'],
        ];
    }

    public static function getDdlLrasOwner()
    {
        return [
            ['Safeguarding', 'Safeguarding'],
            ['Programme Manager', 'Programme Manager'],
            ['Apprentice Success', 'Apprentice Success'],
        ];
    }

    public static function getDdlMonths()
    {
        return [
            ['Jan', 'Jan'],
            ['Feb', 'Feb'],
            ['Mar', 'Mar'],
            ['Apr', 'Apr'],
            ['May', 'May'],
            ['Jun', 'Jun'],
            ['Jul', 'Jul'],
            ['Aug', 'Aug'],
            ['Sep', 'Sep'],
            ['Oct', 'Oct'],
            ['Nov', 'Nov'],
            ['Dec', 'Dec'],
        ];
    }

    public static function getDdlYesNoFsExempt()
    {
        return [
            ['NA', 'N/A'],
            ['N', 'No - SMT Approved'],
            ['Y', 'Yes'],
            ['YP', 'Yes - Pending Evidence'],
        ];
    }

    public static function getListYesNoFsExempt()
    {
        return [
            'NA' => 'N/A',
            'N' => 'No - SMT Approved',
            'Y' => 'Yes',            
            'YP' => 'Yes - Pending Evidence',            
        ];
    }

    public static function getDataPathwayDdl()
    {
        return [
            ['1', 'Data Essentials L3 - Marketing'],
            ['2', 'Data Essentials L3 - Leadership'],
            ['3', 'Data Essentials L3 - Operations'],
            ['4', 'Other'],
        ];
    }

    public static function getDataPathwayList()
    {
        return [
            1 => 'Data Essentials L3 - Marketing',
            2 => 'Data Essentials L3 - Leadership',
            3 => 'Data Essentials L3 - Operations',
            4 => 'Other',
        ];
    }

    public static function getITPathwayDdl()
    {
        return [
            ['AZ900', 'AZ900 - Azure Fundamentals'],
            ['MS900', 'MS900 - 365 Fundamentals'],
            ['SC900', 'SC900 - Security & Compliance Fundamentals'],
        ];
    }

    public static function getITPathwayList()
    {
        return [
            'AZ900' => 'AZ900 - Azure Fundamentals',
            'MS900' => 'MS900 - 365 Fundamentals',
            'SC900' => 'SC900 - Security & Compliance Fundamentals',
        ];
    }

    public static function caseloadRiskOriginDdl()
    {
        return [
            [1, 'In-session: Onboarding'],
            [2, 'In-session: Training'],
            [3, 'In-session: Review'],
            [4, 'In-session: Support'],
            [5, 'Raised to ARM'],
            [6, 'Raised to Coach'],
            [7, 'Raised to Recruitment'],
            [8, 'Raised to Coordinators'],
            [9, 'Survey'],
            [10, 'Other'],
        ];
    }

    public static function caseloadRiskOriginList()
    {
        return [
            1 => 'In-session: Onboarding',
            2 => 'In-session: Training',
            3 => 'In-session: Review',
            4 => 'In-session: Support',
            5 => 'Raised to ARM',
            6 => 'Raised to Coach',
            7 => 'Raised to Recruitment',
            8 => 'Raised to Coordinators',
            9 => 'Survey',
            10 => 'Other',
        ];
    }
}
