<?php

class view_aims_difference implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        if ($subaction == 'viewLearners') {
            echo $this->viewAimsDifference($link);
            exit;
        }
        if ($subaction == 'exportAimsDifference') {
            echo $this->exportAimsDifference($link);
            exit;
        }

        $current_submission = DAO::getSingleValue($link, "SELECT right(submission,2) FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date;");

        include_once('tpl_view_aims_difference.php');
    }


    private function viewAimsDifference(PDO $link)
    {
        $contracts = isset($_REQUEST['contracts']) ? $_REQUEST['contracts'] : '';
        if ($contracts == '') {
            return 'no contract selected';
        }

        $current_submission = DAO::getSingleValue($link, "SELECT right(submission,2) FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date;");

        $sql = <<<SQL
SELECT
  ilr.contract_id,
  ilr.tr_id,
  ilr.l03,
  extractvalue (ilr, '/Learner/ULN') AS ULN,
  extractvalue (ilr, '/Learner/FamilyName') AS FamilyName,
  extractvalue (ilr, '/Learner/GivenNames') AS GivenNames,
  extractvalue (
    ilr,
    'Learner/LearningDelivery/LearnAimRef'
  ) AS ilr_aims,
  (SELECT
    GROUP_CONCAT(REPLACE(id, '/', '') SEPARATOR ' ')
  FROM
    student_qualifications
  WHERE tr_id = ilr.`tr_id` AND aptitude != '1') AS tr_aims
FROM
  ilr
WHERE
	ilr.contract_id IN ($contracts) AND ilr.submission = 'W$current_submission'
ORDER BY FamilyName
;
SQL;

        $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        $html = '';
        foreach ($records as $row) {
            $ilr_aims = explode(' ', $row['ilr_aims'] ?: '');
            $tr_aims = explode(' ', $row['tr_aims'] ?: '');

            foreach ($ilr_aims as $key => &$value) {
                if ($value == 'ZPROG001') {
                    unset($ilr_aims[$key]);
                }
            }
            $diff_aims_in_ilr = array_diff($ilr_aims, $tr_aims);
            $diff_aims_in_tr = array_diff($tr_aims, $ilr_aims);

            if (count($diff_aims_in_ilr) == 0 && count($diff_aims_in_tr) == 0) {
                continue;
            }

            $html .= '<tr>';

            $html .= '<td><a href="do.php?_action=read_training_record&id=' . $row['tr_id'] . '&contract_id=' . $row['contract_id'] . '">' . $row['l03'] . '</a></td>';
            $html .= '<td>' . $row['ULN'] . '</td>';
            $html .= '<td>' . $row['FamilyName'] . '</td>';
            $html .= '<td>' . $row['GivenNames'] . '</td>';
            $html .= '<td>' . implode(', ', $diff_aims_in_ilr) . '</td>';
            $html .= '<td>' . implode(', ', $diff_aims_in_tr) . '</td>';

            $html .= '</tr>';
        }

        $html = $html == '' ? 'No discrepancies found in any ILR' : $html;
        return $html;
    }

    private function exportAimsDifference(PDO $link)
    {
        $contracts = isset($_REQUEST['contracts']) ? $_REQUEST['contracts'] : '';
        if ($contracts == '') {
            return 'no contract selected';
        }

        $current_submission = DAO::getSingleValue($link, "SELECT right(submission,2) FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date;");

        $sql = <<<SQL
SELECT
  ilr.tr_id,
  ilr.l03,
  extractvalue (ilr, '/Learner/ULN') AS ULN,
  extractvalue (ilr, '/Learner/FamilyName') AS FamilyName,
  extractvalue (ilr, '/Learner/GivenNames') AS GivenNames,
  extractvalue (
    ilr,
    'Learner/LearningDelivery/LearnAimRef'
  ) AS ilr_aims,
  (SELECT
    GROUP_CONCAT(REPLACE(id, '/', '') SEPARATOR ' ')
  FROM
    student_qualifications
  WHERE tr_id = ilr.`tr_id` AND aptitude != '1') AS tr_aims
FROM
  ilr
WHERE
	ilr.contract_id IN ($contracts) AND ilr.submission = 'W$current_submission'
ORDER BY FamilyName
;
SQL;

        $rows = array();
        $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        foreach ($result as $rs) {
            $rows[] = $rs;
        }
        unset($result);

        header("Content-Type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename=AimsDiffInILRAndTR.csv');
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) {
            header('Pragma: public');
            header('Cache-Control: max-age=0');
        }
        $line = '';
        $line .= 'L03,ULN,FamilyName,GivenNames,Diff aims in ILR,Diff aims in TR';
        echo $line . "\r\n";
        foreach ($rows as $row) {
            $ilr_aims = explode(' ', $row['ilr_aims'] ?: '');
            $tr_aims = explode(' ', $row['tr_aims'] ?: '');

            foreach ($ilr_aims as $key => &$value) {
                if ($value == 'ZPROG001') {
                    unset($ilr_aims[$key]);
                }
            }
            $diff_aims_in_ilr = array_diff($ilr_aims, $tr_aims);
            $diff_aims_in_tr = array_diff($tr_aims, $ilr_aims);

            if (count($diff_aims_in_ilr) == 0 && count($diff_aims_in_tr) == 0) {
                continue;
            }
            $line = '';
            $line .= $row['l03'] . ',';
            $line .= $this->csvSafe($row['ULN']) . ',';
            $line .= $this->csvSafe($row['FamilyName']) . ',';
            $line .= $this->csvSafe($row['GivenNames']) . ',';
            $line .= $this->csvSafe(implode(', ', $diff_aims_in_ilr)) . ',';
            $line .= $this->csvSafe(implode(', ', $diff_aims_in_tr)) . ',';
            echo $line . "\r\n";
            unset($p);
        }
        exit;
    }

    private function csvSafe($value)
    {
        $value = str_replace(',', ';', $value);
        $value = str_replace(array("\n", "\r"), '', $value);
        $value = str_replace("\t", '', $value);
        $value = '"' . str_replace('"', '""', $value) . '"';
        return $value;
    }
}