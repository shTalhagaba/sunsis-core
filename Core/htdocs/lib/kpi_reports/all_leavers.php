<?php

class KPI_Report_all_leavers extends KPI_Report
{
    function __construct($link, $year, $programme_type)
    {
        parent::__construct($link, $year, $programme_type);
    }

    protected function getData($link)
    {
        $where = '';
        if ($_SESSION['user']->isAdmin() || $_SESSION['user']->type == 12 || $_SESSION['user']->type == 15) {
            $where = '';
        } elseif ($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type == 8 || $_SESSION['user']->type == 13 || $_SESSION['user']->type == 14) {
            $emp = $_SESSION['user']->employer_id;
            $where = ' and (tr.provider_id= ' . $emp . ' or tr.employer_id=' . $emp . ')';
        } elseif ($_SESSION['user']->type == 2) {
            $username = $_SESSION['user']->username;
            $id = $_SESSION['user']->id;
            $where = ' and (g.tutor = ' . '"' . $username . '"' . ' or course_qualifications_dates.tutor_username = ' . '"' . $id . '" or tr.tutor="' . $id . '")';
        } elseif ($_SESSION['user']->type == 3) {
            $username = $_SESSION['user']->username;
            $id = $_SESSION['user']->id;
            $where = ' and (g.assessor = ' . '"' . $username . '" or tr.assessor="' . $id . '")';
        } elseif ($_SESSION['user']->type == 4) {
            $username = $_SESSION['user']->username;
            $id = $_SESSION['user']->id;
            $where = ' and (g.verifier = ' . '"' . $username . '" or tr.verifier="' . $id . '")';
        } elseif ($_SESSION['user']->type == 6) {
            $username = $_SESSION['user']->username;
            $where = ' and g.wbcoordinator = ' . '"' . $username . '"';
        } elseif ($_SESSION['user']->type == 5) {
            $username = $_SESSION['user']->username;
            $where = ' and tr.username = ' . '"' . $username . '"';
        } elseif ($_SESSION['user']->type == 9) {
            $username = $_SESSION['user']->username;
            $where = ' and assessors.supervisor = ' . '"' . $username . '"';
        } elseif ($_SESSION['user']->type == 16) {
            $emp = $_SESSION['user']->employer_id;
            $where = ' and (contracts.contract_holder= ' . $emp . ')';
        } elseif ($_SESSION['user']->type == 18) {
            $supervisors = preg_replace('/([^,]+)/', '\'$1\'', $_SESSION['user']->supervisor);
            $assessors = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(\"\'\",id,\"\'\") FROM users WHERE supervisor in ($supervisors);");
            $where = ' and (g.assessor in (' . $assessors . ') or tr.assessor in (' . $assessors . '))';
        } elseif ($_SESSION['user']->type == 19) {
            $brand = $_SESSION['user']->department;
            $where = " and org.manufacturer = '$brand'";
        } elseif ($_SESSION['user']->type == 20) {
            $username = $_SESSION['user']->username;
            $where = ' and (tr.programme="' . $username . '")';
        } elseif ($_SESSION['user']->type == 21) {
            $username = $_SESSION['user']->username;
            $where = ' and (find_in_set("' . $username . '", course.director))';
        }

        $where .= " and course.programme_type= $this->programme_type";


        $sql = "
			SELECT DISTINCT
				org.legal_name as Employer 
				,CONCAT(tr.surname,', ', tr.firstnames) AS `name`
				, l03 AS reference_number
				#, DATE_FORMAT(tr.dob,'%d/%m/%Y') AS date_of_birth
				#, DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(tr.dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(tr.dob, '00-%m-%d')) AS age
				#, (CASE tr.gender WHEN 'M' THEN 'Male' WHEN 'F' THEN 'Female' ELSE 'Undefined' END) AS gender
				, sq.id as NVQ_aim_reference
				, sq.title as NVQ_Title
                , concat(acs.firstnames,' ',acs.surname) as apprentice_coordinator
				#, org.legal_name as employer
				#, c.title as contract
				#, g.title as group_title
				#, COALESCE(CONCAT(ua.surname, ', ', ua.firstnames),'n/a') AS assessor
				, DATE_FORMAT(tr.start_date,'%d/%m/%Y') as start_date
				, DATE_FORMAT(tr.closure_date,'%d/%m/%Y') AS leave_date 
				, DATE_FORMAT(tr.target_date,'%d/%m/%Y') AS target_completion_date
				, (TO_DAYS(tr.target_date) - TO_DAYS(tr.closure_date)) AS days_left_early
				, IF(ua2.surname is not null,ua2.surname,ua.surname) AS assessor_surname
				, lrl.description AS reason_for_leaving
				# fields that must be unset in the loop ;)
				, tr.contract_id
				, tr.id as tr_id
			FROM 
				tr 
			LEFT JOIN
				courses_tr on courses_tr.tr_id = tr.id
			LEFT JOIN
				courses as course on course.id = courses_tr.course_id
			LEFT JOIN
				group_members AS gm ON (gm.tr_id = tr.id)
			LEFT JOIN
				groups AS g ON (g.id = gm.groups_id)	
			LEFT JOIN
				contracts AS c ON (c.id = tr.contract_id)
			LEFT JOIN
				assessor_review AS ar ON (ar.tr_id = tr.id)
			LEFT JOIN
				users as acs on acs.username = tr.programme
			LEFT JOIN
				users AS ua ON (ua.username = g.assessor)
			LEFT JOIN
				users AS ua2 ON (ua2.id = tr.assessor)
			LEFT JOIN
				organisations AS org ON (org.id = tr.employer_id)
			LEFT JOIN
				student_qualifications as sq on sq.tr_id = tr.id and sq.qualification_type = 'NVQ'				
			LEFT JOIN
				lookup_reasons_for_leaving lrl ON lrl.id = tr.reasons_for_leaving
			WHERE 
				(status_code = 3 or status_code=4) 
				and tr.closure_date >= c.start_date and tr.closure_date <= c.end_date
				AND c.contract_year = '" . $this->year . "'	$where		
		" . "ORDER BY tr.surname ASC";


        //pre($sql);
        $query = $this->db->query($sql);
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        $c = 0;
        while ($row = $query->fetch()) {
            $tr_id = $row['tr_id'];
            $row['name'] = '<a href="/do.php?_action=read_training_record&amp;id=' . $row['tr_id'] . '&amp;contract=' . $row['contract_id'] . '">' . ucwords(strtolower($row['name'])) . '</a>';
            unset($row['tr_id'], $row['contract_id']);
            $row['days_left_early'] = $row['days_left_early'];

            if (preg_match('/Level 2/i', (string)$row['NVQ_Title'])) {
                $row['NVQ_Title'] = 'Level 2';
            } elseif (preg_match('/Level 3/i', (string)$row['NVQ_Title'])) {
                $row['NVQ_Title'] = 'Level 3';
            } elseif (preg_match('/Level 1/i', (string)$row['NVQ_Title'])) {
                $row['NVQ_Title'] = 'Level 1';
            }

            // set default values to handle issue with no ilr being found - re
            $row['programme_type'] = "";
            $row['national_insurance'] = "";
            $row['gender'] = "";
            $row['disability'] = "";
            $row['ethnicity'] = "";
            $row['als'] = "";
            $row['home_postcode'] = "";

            // Is it TtG or Apprenticeship?
            $ilr = DAO::getSingleValue($link, "select ilr from ilr where tr_id = $tr_id order by contract_id DESC, submission DESC LIMIT 1");
            if (isset($ilr) && $ilr != '') {
                $ilr = Ilr2009::loadFromXML($ilr);
                $a15 = (int)$ilr->aims[0]->A15;
                $row['programme_type'] = ($a15 == 99) ? "Adult NVQ" : "Apprenticeship";
            }

            $additional = DAO::getResultset($link, "select extractvalue(ilr,'/ilr/learner/L13'), extractvalue(ilr,'/ilr/learner/L15'), extractvalue(ilr,'/ilr/learner/L12'), extractvalue(ilr,'/ilr/learner/L29'), extractvalue(ilr,'/ilr/learner/L17') from ilr left join contracts on contracts.id = ilr.contract_id where tr_id = $tr_id order by contract_year desc, submission desc");
            if (isset($additional[0])) {
                $row['gender'] = $additional[0][0];
                $row['disability'] = $additional[0][1];
                $row['ethnicity'] = $additional[0][2];
                $row['als'] = $additional[0][3];
                $row['home_postcode'] = $additional[0][4];
            }

            $this->data[] = $row;
        }
    }

    private function rgb2hex($r, $g = -1, $b = -1)
    {
        if (is_array($r) && count($r) == 3) {
            list($r, $g, $b) = $r;
        }

        $r = (int)$r;
        $g = (int)$g;
        $b = (int)$b;

        $r = dechex($r < 0 ? 0 : ($r > 255 ? 255 : $r));
        $g = dechex($g < 0 ? 0 : ($g > 255 ? 255 : $g));
        $b = dechex($b < 0 ? 0 : ($b > 255 ? 255 : $b));

        $color = (strlen($r) < 2 ? '0' : '') . $r;
        $color .= (strlen($g) < 2 ? '0' : '') . $g;
        $color .= (strlen($b) < 2 ? '0' : '') . $b;
        return '#' . $color;
    }

    private function colorStrengthen($value, $lower, $upper, $boundaries)
    {
        $red = 255;
        $green = 0;
        $blue = 0;

        $colourStrengthen = 50;

        $interval = floor(($upper - $lower) / $boundaries);
        //echo $lower . '-' . $upper . '-' . $boundaries . '-' . $interval;
        //die;

        for ($b = 0; $b < $boundaries; $b++) {
            $l = $lower + ($interval * 1);
            $u = $l + $interval;

            if ($value >= $l and $value < $u) {
                echo 'Found ' . $value . ' in region ' . $l . '-' . $u . '<br />';
                return $this->rgb2hex($red, $green + ($colourStrengthen * $b), $blue * ($colourStrengthen + $b));
            }
        }
        return $this->rgb2hex($red, $green, $blue);
    }


}

?>