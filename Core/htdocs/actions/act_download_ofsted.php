<?php

use setasign\Fpdi\Fpdi;


class download_ofsted implements IAction
{
    public function execute(PDO $link)
    {
        set_time_limit(0);
        if (DB_NAME == 'am_baltic') {
            $sql = <<<HEREDOC
SELECT
tr.id as tr_id, tr.l03 as l03, tr.contract_id
,(SELECT ilr FROM ilr LEFT JOIN contracts ON contracts.id=ilr.`contract_id` WHERE tr.id = ilr.`tr_id` ORDER BY contract_year DESC, submission DESC LIMIT 0,1) as ilr
,contracts.title as title
FROM tr
INNER JOIN contracts ON tr.`contract_id` = contracts.id
WHERE locate('Unfunded',contracts.title)=0 and (tr.closure_date >= '2012-08-01' OR tr.`closure_date` IS NULL) AND tr.`start_date` < '2013-08-01' AND contracts.`contract_holder` IN (SELECT id FROM organisations WHERE organisation_type = 4 AND ukprn IN (SELECT ukprn FROM organisations WHERE organisation_type = 1));
HEREDOC;
        } else {
            $sql = <<<HEREDOC
SELECT
tr.id as tr_id, tr.l03 as l03, tr.contract_id
,(SELECT ilr FROM ilr LEFT JOIN contracts ON contracts.id=ilr.`contract_id` WHERE tr.id = ilr.`tr_id` ORDER BY contract_year DESC, submission DESC LIMIT 0,1) as ilr
,contracts.title as title
FROM tr
INNER JOIN contracts ON tr.`contract_id` = contracts.id and contracts.funding_type = 1
WHERE locate('Unfunded',contracts.title)=0 and (tr.closure_date >= '2012-08-01' OR tr.`closure_date` IS NULL) AND tr.`start_date` < '2013-08-01' AND contracts.`contract_holder` IN (SELECT id FROM organisations WHERE organisation_type = 4 AND ukprn IN (SELECT ukprn FROM organisations WHERE organisation_type = 1));
HEREDOC;
        }
        $st = $link->query($sql);
        if ($st) {
            $this->createTempTable($link);
            while ($row = $st->fetch()) {
                $ilr = Ilr2012::loadFromXML($row['ilr']);
                $tr_id = $row['tr_id'];
                $contract_title = $row['title'];
                $l03 = $row['l03'];
                $contract_id = $row['contract_id'];
                if ($ilr->learnerinformation->L08 != "Y") {
                    if (($ilr->aims[0]->A15 != "99" && $ilr->aims[0]->A15 != "" && $ilr->aims[0]->A15 != "0")) {
                        $programme_type = "Apprenticeship";
                        $start_date = Date::toMySQL($ilr->programmeaim->A27);
                        $end_date = Date::toMySQL($ilr->programmeaim->A28);
                        $level = $ilr->aims[0]->A15;
                        $a09 = $ilr->aims[0]->A09;
                        if ($ilr->learnerinformation->L11 != '00/00/0000' && $ilr->learnerinformation->L11 != '00000000') {
                            $dob = $ilr->learnerinformation->L11;
                            $dob = Date::toMySQL($dob);
                            $age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
                        } else {
                            $age = '';
                        }
                        if ($age <= 16)
                            $age_band = "14-16";
                        elseif ($age <= 18)
                            $age_band = "16-18";
                        elseif ($age <= 24)
                            $age_band = "19-24";
                        elseif ($age >= 25)
                            $age_band = "25+";
                        else
                            $age_band = "Unknown";
                        DAO::execute($link, "insert into ofsted(l03,tr_id,programme_type,start_date,planned_end_date,level,a09,age_band,contract_title,contract_id) values('$l03','$tr_id','$programme_type','$start_date','$end_date','$level','$a09','$age_band','$contract_title','$contract_id');");
                    } else {
                        for ($a = 0; $a <= $ilr->subaims; $a++) {
                            // Calclation of A_TTGAIN

                            if (($ilr->aims[$a]->A10 == '45' || $ilr->aims[$a]->A10 == '46' || $ilr->aims[$a]->A10 == '60') && ($ilr->aims[$a]->A15 != '2' && $ilr->aims[$a]->A15 != '3' && $ilr->aims[$a]->A15 != '10') && ($ilr->aims[$a]->A46a != '83' && $ilr->aims[$a]->A46b != '83')) {

                                // Age Band Calculation
                                if (($ilr->aims[$a]->A18 == '24' || $ilr->aims[$a]->A18 == '23' || $ilr->aims[$a]->A18 == '22') && $ilr->aims[$a]->A46a != '125')
                                    $programme_type = "Workplace";
                                elseif ($ilr->aims[$a]->A18 == '1' || $ilr->aims[$a]->A46a == '125')
                                    $programme_type = "Classroom";
                                else
                                    $programme_type = "Unknown";
                                $start_date = Date::toMySQL($ilr->aims[$a]->A27);
                                $end_date = Date::toMySQL($ilr->aims[$a]->A28);
                                $a09 = $ilr->aims[$a]->A09;
                                $level = DAO::getSingleValue($link, "select level from qualifications where replace(id,'/','') = '$a09';");
                                if ($ilr->learnerinformation->L11 != '00/00/0000' && $ilr->learnerinformation->L11 != '00000000') {
                                    $dob = $ilr->learnerinformation->L11;
                                    $dob = Date::toMySQL($dob);
                                    $age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
                                } else {
                                    $age = '';
                                }
                                if ($age <= 16)
                                    $age_band = "14-16";
                                elseif ($age <= 18)
                                    $age_band = "16-18";
                                elseif ($age <= 24)
                                    $age_band = "19-24";
                                elseif ($age >= 25)
                                    $age_band = "25+";
                                else
                                    $age_band = "Unknown";
                                DAO::execute($link, "insert into ofsted(l03,tr_id,programme_type,start_date,planned_end_date,level,a09,age_band,contract_title,contract_id) values('$l03',$tr_id,'$programme_type','$start_date','$end_date','$level','$a09','$age_band','$contract_title','$contract_id');");
                            }
                        }
                    }
                }
            }
        }

        DAO::execute($link, "update ofsted LEFT JOIN lad201213.learning_aim on learning_aim.LEARNING_AIM_REF = ofsted.a09 LEFT JOIN lad201213.learning_aim_types on learning_aim_types.LEARNING_AIM_TYPE_CODE = learning_aim.LEARNING_AIM_TYPE_CODE set aim_type = LEARNING_AIM_TYPE_DESC");
        DAO::execute($link, "update ofsted INNER JOIN lad201213.all_annual_values on all_annual_values.LEARNING_AIM_REF = ofsted.a09 INNER JOIN lad201213.ssa_tier1_codes on ssa_tier1_codes.SSA_TIER1_CODE = all_annual_values.SSA_TIER1_CODE set ssa1 = CONCAT(lad201213.ssa_tier1_codes.SSA_TIER1_CODE,' ',lad201213.ssa_tier1_codes.SSA_TIER1_DESC)");

        DAO::execute($link, "Drop table IF EXISTS ofsted2;");
        DAO::execute($link, "create table ofsted2 select * from ofsted");


        $m1 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted where programme_type='Apprenticeship';");
        $m2 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted where programme_type!='Apprenticeship';");
        $m3 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL <= 1 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type != 'Apprenticeship';");
        $m4 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL <= 1 AND (age_band = '19-24' OR age_band = '25+') AND programme_type != 'Apprenticeship';");
        $m5 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 2 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type != 'Apprenticeship';");
        $m6 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 2 AND (age_band = '19-24' OR age_band = '25+') AND programme_type != 'Apprenticeship';");
        $m7 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 3 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type != 'Apprenticeship';");
        $m8 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 3 AND (age_band = '19-24' OR age_band = '25+') AND programme_type != 'Apprenticeship';");
        $m9 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL >= 4 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type != 'Apprenticeship';");
        $m10 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL >= 4 AND (age_band = '19-24' OR age_band = '25+') AND programme_type != 'Apprenticeship';");
        $m11 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 3 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type = 'Apprenticeship';");
        $m12 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 3 AND (age_band = '19-24' OR age_band = '25+') AND programme_type = 'Apprenticeship';");
        $m13 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 2 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type = 'Apprenticeship';");
        $m14 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 2 AND (age_band = '19-24' OR age_band = '25+') AND programme_type = 'Apprenticeship';");
        $m15 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL > 3 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type = 'Apprenticeship';");
        $m16 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL > 3 AND (age_band = '19-24' OR age_band = '25+') AND programme_type = 'Apprenticeship';");
        $m17 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE locate('PETP',contract_title)>1 or locate('BRS',contract_title)>1");

        // main course level 1 or below 16-18
        $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/main_course_level_1_or_below_16-18.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');
        $sql = <<<HEREDOC
SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL <= 1 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type != 'Apprenticeship' GROUP BY l03;
HEREDOC;

        $st = $link->query($sql);
        if ($st) {
            $csv_fields = array();
            $csv_fields[0] = array();
            $csv_fields[0][] = 'Contract';
            $csv_fields[0][] = 'L03';
            $csv_fields[0][] = 'Programme Type';
            $csv_fields[0][] = 'Start Date';
            $csv_fields[0][] = 'Planned End Date';
            $csv_fields[0][] = 'Level';
            $csv_fields[0][] = 'Age Band';
            $csv_fields[0][] = 'Main Aim';
            $csv_fields[0][] = 'Aim Type';
            $index = 0;
            while ($row = $st->fetch()) {
                $index++;
                $csv_fields[$index][] = $row['contract_title'];
                $csv_fields[$index][] = $row['l03'];
                $csv_fields[$index][] = $row['programme_type'];
                $csv_fields[$index][] = $row['start_date'];
                $csv_fields[$index][] = $row['planned_end_date'];
                $csv_fields[$index][] = $row['level'];
                $csv_fields[$index][] = $row['age_band'];
                $csv_fields[$index][] = $row['a09'];
                $csv_fields[$index][] = $row['aim_type'];
            }
        }
        foreach ($csv_fields as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        // main course level 1 or below 19
        $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/main_course_level_1_or_below_19_plus.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');
        $sql = <<<HEREDOC
SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL <= 1 AND (age_band = '19-24' OR age_band = '25+') AND programme_type != 'Apprenticeship' GROUP BY l03;
HEREDOC;

        $st = $link->query($sql);
        if ($st) {
            $csv_fields = array();
            $csv_fields[0] = array();
            $csv_fields[0][] = 'Contract';
            $csv_fields[0][] = 'L03';
            $csv_fields[0][] = 'Programme Type';
            $csv_fields[0][] = 'Start Date';
            $csv_fields[0][] = 'Planned End Date';
            $csv_fields[0][] = 'Level';
            $csv_fields[0][] = 'Age Band';
            $csv_fields[0][] = 'Main Aim';
            $csv_fields[0][] = 'Aim Type';
            $index = 0;
            while ($row = $st->fetch()) {
                $index++;
                $csv_fields[$index][] = $row['contract_title'];
                $csv_fields[$index][] = $row['l03'];
                $csv_fields[$index][] = $row['programme_type'];
                $csv_fields[$index][] = $row['start_date'];
                $csv_fields[$index][] = $row['planned_end_date'];
                $csv_fields[$index][] = $row['level'];
                $csv_fields[$index][] = $row['age_band'];
                $csv_fields[$index][] = $row['a09'];
                $csv_fields[$index][] = $row['aim_type'];
            }
        }
        foreach ($csv_fields as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        // main course level 2 16-18
        $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/main_course_level_2_16-18.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');
        $sql = <<<HEREDOC
SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL = 2 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type != 'Apprenticeship' GROUP BY l03;
HEREDOC;

        $st = $link->query($sql);
        if ($st) {
            $csv_fields = array();
            $csv_fields[0] = array();
            $csv_fields[0][] = 'Contract';
            $csv_fields[0][] = 'L03';
            $csv_fields[0][] = 'Programme Type';
            $csv_fields[0][] = 'Start Date';
            $csv_fields[0][] = 'Planned End Date';
            $csv_fields[0][] = 'Level';
            $csv_fields[0][] = 'Age Band';
            $csv_fields[0][] = 'Main Aim';
            $csv_fields[0][] = 'Aim Type';
            $index = 0;
            while ($row = $st->fetch()) {
                $index++;
                $csv_fields[$index][] = $row['contract_title'];
                $csv_fields[$index][] = $row['l03'];
                $csv_fields[$index][] = $row['programme_type'];
                $csv_fields[$index][] = $row['start_date'];
                $csv_fields[$index][] = $row['planned_end_date'];
                $csv_fields[$index][] = $row['level'];
                $csv_fields[$index][] = $row['age_band'];
                $csv_fields[$index][] = $row['a09'];
                $csv_fields[$index][] = $row['aim_type'];
            }
        }
        foreach ($csv_fields as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        // main course level 2 19+
        $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/main_course_level_2_19_plus.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');
        $sql = <<<HEREDOC
SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL = 2 AND (age_band = '19-24' OR age_band = '25+') AND programme_type != 'Apprenticeshp' GROUP BY l03;
HEREDOC;

        $st = $link->query($sql);
        if ($st) {
            $csv_fields = array();
            $csv_fields[0] = array();
            $csv_fields[0][] = 'Contract';
            $csv_fields[0][] = 'L03';
            $csv_fields[0][] = 'Programme Type';
            $csv_fields[0][] = 'Start Date';
            $csv_fields[0][] = 'Planned End Date';
            $csv_fields[0][] = 'Level';
            $csv_fields[0][] = 'Age Band';
            $csv_fields[0][] = 'Main Aim';
            $csv_fields[0][] = 'Aim Type';
            $index = 0;
            while ($row = $st->fetch()) {
                $index++;
                $csv_fields[$index][] = $row['contract_title'];
                $csv_fields[$index][] = $row['l03'];
                $csv_fields[$index][] = $row['programme_type'];
                $csv_fields[$index][] = $row['start_date'];
                $csv_fields[$index][] = $row['planned_end_date'];
                $csv_fields[$index][] = $row['level'];
                $csv_fields[$index][] = $row['age_band'];
                $csv_fields[$index][] = $row['a09'];
                $csv_fields[$index][] = $row['aim_type'];
            }
        }
        foreach ($csv_fields as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        // main course level 3 16-18
        $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/main_course_level_3_16-18.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');
        $sql = <<<HEREDOC
SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE level = 3 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type != 'Apprenticeship' GROUP BY l03;
HEREDOC;

        $st = $link->query($sql);
        if ($st) {
            $csv_fields = array();
            $csv_fields[0] = array();
            $csv_fields[0][] = 'Contract';
            $csv_fields[0][] = 'L03';
            $csv_fields[0][] = 'Programme Type';
            $csv_fields[0][] = 'Start Date';
            $csv_fields[0][] = 'Planned End Date';
            $csv_fields[0][] = 'Level';
            $csv_fields[0][] = 'Age Band';
            $csv_fields[0][] = 'Main Aim';
            $csv_fields[0][] = 'Aim Type';
            $index = 0;
            while ($row = $st->fetch()) {
                $index++;
                $csv_fields[$index][] = $row['contract_title'];
                $csv_fields[$index][] = $row['l03'];
                $csv_fields[$index][] = $row['programme_type'];
                $csv_fields[$index][] = $row['start_date'];
                $csv_fields[$index][] = $row['planned_end_date'];
                $csv_fields[$index][] = $row['level'];
                $csv_fields[$index][] = $row['age_band'];
                $csv_fields[$index][] = $row['a09'];
                $csv_fields[$index][] = $row['aim_type'];
            }
        }
        foreach ($csv_fields as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        // main course level 3 19+
        $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/main_course_level_3_19_plus.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');
        $sql = <<<HEREDOC
SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL = 3 AND (age_band = '19-24' OR age_band = '25+') AND programme_type != 'Apprenticeship' GROUP BY l03;
HEREDOC;

        $st = $link->query($sql);
        if ($st) {
            $csv_fields = array();
            $csv_fields[0] = array();
            $csv_fields[0][] = 'Contract';
            $csv_fields[0][] = 'L03';
            $csv_fields[0][] = 'Programme Type';
            $csv_fields[0][] = 'Start Date';
            $csv_fields[0][] = 'Planned End Date';
            $csv_fields[0][] = 'Level';
            $csv_fields[0][] = 'Age Band';
            $csv_fields[0][] = 'Main Aim';
            $csv_fields[0][] = 'Aim Type';
            $index = 0;
            while ($row = $st->fetch()) {
                $index++;
                $csv_fields[$index][] = $row['contract_title'];
                $csv_fields[$index][] = $row['l03'];
                $csv_fields[$index][] = $row['programme_type'];
                $csv_fields[$index][] = $row['start_date'];
                $csv_fields[$index][] = $row['planned_end_date'];
                $csv_fields[$index][] = $row['level'];
                $csv_fields[$index][] = $row['age_band'];
                $csv_fields[$index][] = $row['a09'];
                $csv_fields[$index][] = $row['aim_type'];
            }
        }
        foreach ($csv_fields as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        // main course level 4 16-18
        $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/main_course_level_4_16-18.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');
        $sql = <<<HEREDOC
SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL >= 4 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type != 'Apprenticeship' GROUP BY l03;
HEREDOC;

        $st = $link->query($sql);
        if ($st) {
            $csv_fields = array();
            $csv_fields[0] = array();
            $csv_fields[0][] = 'Contract';
            $csv_fields[0][] = 'L03';
            $csv_fields[0][] = 'Programme Type';
            $csv_fields[0][] = 'Start Date';
            $csv_fields[0][] = 'Planned End Date';
            $csv_fields[0][] = 'Level';
            $csv_fields[0][] = 'Age Band';
            $csv_fields[0][] = 'Main Aim';
            $csv_fields[0][] = 'Aim Type';
            $index = 0;
            while ($row = $st->fetch()) {
                $index++;
                $csv_fields[$index][] = $row['contract_title'];
                $csv_fields[$index][] = $row['l03'];
                $csv_fields[$index][] = $row['programme_type'];
                $csv_fields[$index][] = $row['start_date'];
                $csv_fields[$index][] = $row['planned_end_date'];
                $csv_fields[$index][] = $row['level'];
                $csv_fields[$index][] = $row['age_band'];
                $csv_fields[$index][] = $row['a09'];
                $csv_fields[$index][] = $row['aim_type'];
            }
        }
        foreach ($csv_fields as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        // main course level 4 19+
        $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/main_course_level_4_19_plus.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');
        $sql = <<<HEREDOC
SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL >= 4 AND (age_band = '19-24' OR age_band = '25+') AND programme_type != 'Apprenticeship' GROUP BY l03;
HEREDOC;

        $st = $link->query($sql);
        if ($st) {
            $csv_fields = array();
            $csv_fields[0] = array();
            $csv_fields[0][] = 'Contract';
            $csv_fields[0][] = 'L03';
            $csv_fields[0][] = 'Programme Type';
            $csv_fields[0][] = 'Start Date';
            $csv_fields[0][] = 'Planned End Date';
            $csv_fields[0][] = 'Level';
            $csv_fields[0][] = 'Age Band';
            $csv_fields[0][] = 'Main Aim';
            $csv_fields[0][] = 'Aim Type';
            $index = 0;
            while ($row = $st->fetch()) {
                $index++;
                $csv_fields[$index][] = $row['contract_title'];
                $csv_fields[$index][] = $row['l03'];
                $csv_fields[$index][] = $row['programme_type'];
                $csv_fields[$index][] = $row['start_date'];
                $csv_fields[$index][] = $row['planned_end_date'];
                $csv_fields[$index][] = $row['level'];
                $csv_fields[$index][] = $row['age_band'];
                $csv_fields[$index][] = $row['a09'];
                $csv_fields[$index][] = $row['aim_type'];
            }
        }
        foreach ($csv_fields as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        // Apprentices Intermediate 16-18
        $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/intermediate_apprentices_16-18.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');
        $sql = <<<HEREDOC
SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL = 3 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type = 'Apprenticeship' GROUP BY l03;
HEREDOC;

        $st = $link->query($sql);
        if ($st) {
            $csv_fields = array();
            $csv_fields[0] = array();
            $csv_fields[0][] = 'Contract';
            $csv_fields[0][] = 'L03';
            $csv_fields[0][] = 'Programme Type';
            $csv_fields[0][] = 'Start Date';
            $csv_fields[0][] = 'Planned End Date';
            $csv_fields[0][] = 'Level';
            $csv_fields[0][] = 'Age Band';
            $csv_fields[0][] = 'Main Aim';
            $csv_fields[0][] = 'Aim Type';
            $index = 0;
            while ($row = $st->fetch()) {
                $index++;
                $csv_fields[$index][] = $row['contract_title'];
                $csv_fields[$index][] = $row['l03'];
                $csv_fields[$index][] = $row['programme_type'];
                $csv_fields[$index][] = $row['start_date'];
                $csv_fields[$index][] = $row['planned_end_date'];
                $csv_fields[$index][] = $row['level'];
                $csv_fields[$index][] = $row['age_band'];
                $csv_fields[$index][] = $row['a09'];
                $csv_fields[$index][] = $row['aim_type'];
            }
        }
        foreach ($csv_fields as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        // Apprentices Intermediate 19+
        $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/intermediate_apprentices_19_plus.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');
        $sql = <<<HEREDOC
SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL = 3 AND (age_band = '19-24' OR age_band = '25+') AND programme_type = 'Apprenticeship' GROUP BY l03;
HEREDOC;

        $st = $link->query($sql);
        if ($st) {
            $csv_fields = array();
            $csv_fields[0] = array();
            $csv_fields[0][] = 'Contract';
            $csv_fields[0][] = 'L03';
            $csv_fields[0][] = 'Programme Type';
            $csv_fields[0][] = 'Start Date';
            $csv_fields[0][] = 'Planned End Date';
            $csv_fields[0][] = 'Level';
            $csv_fields[0][] = 'Age Band';
            $csv_fields[0][] = 'Main Aim';
            $csv_fields[0][] = 'Aim Type';
            $index = 0;
            while ($row = $st->fetch()) {
                $index++;
                $csv_fields[$index][] = $row['contract_title'];
                $csv_fields[$index][] = $row['l03'];
                $csv_fields[$index][] = $row['programme_type'];
                $csv_fields[$index][] = $row['start_date'];
                $csv_fields[$index][] = $row['planned_end_date'];
                $csv_fields[$index][] = $row['level'];
                $csv_fields[$index][] = $row['age_band'];
                $csv_fields[$index][] = $row['a09'];
                $csv_fields[$index][] = $row['aim_type'];
            }
        }
        foreach ($csv_fields as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        // Apprentices Advanced 16-18
        $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/advanced_apprentices_16-18.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');
        $sql = <<<HEREDOC
SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL = 2 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type = 'Apprenticeship' GROUP BY l03;
HEREDOC;

        $st = $link->query($sql);
        if ($st) {
            $csv_fields = array();
            $csv_fields[0] = array();
            $csv_fields[0][] = 'Contract';
            $csv_fields[0][] = 'L03';
            $csv_fields[0][] = 'Programme Type';
            $csv_fields[0][] = 'Start Date';
            $csv_fields[0][] = 'Planned End Date';
            $csv_fields[0][] = 'Level';
            $csv_fields[0][] = 'Age Band';
            $csv_fields[0][] = 'Main Aim';
            $csv_fields[0][] = 'Aim Type';
            $index = 0;
            while ($row = $st->fetch()) {
                $index++;
                $csv_fields[$index][] = $row['contract_title'];
                $csv_fields[$index][] = $row['l03'];
                $csv_fields[$index][] = $row['programme_type'];
                $csv_fields[$index][] = $row['start_date'];
                $csv_fields[$index][] = $row['planned_end_date'];
                $csv_fields[$index][] = $row['level'];
                $csv_fields[$index][] = $row['age_band'];
                $csv_fields[$index][] = $row['a09'];
                $csv_fields[$index][] = $row['aim_type'];
            }
        }
        foreach ($csv_fields as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        // Apprentices Advanced 19
        $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/advanced_apprentices_19_plus.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');
        $sql = <<<HEREDOC
SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL = 2 AND (age_band = '19-24' OR age_band = '25+') AND programme_type = 'Apprenticeship' GROUP BY l03;
HEREDOC;

        $st = $link->query($sql);
        if ($st) {
            $csv_fields = array();
            $csv_fields[0] = array();
            $csv_fields[0][] = 'Contract';
            $csv_fields[0][] = 'L03';
            $csv_fields[0][] = 'Programme Type';
            $csv_fields[0][] = 'Start Date';
            $csv_fields[0][] = 'Planned End Date';
            $csv_fields[0][] = 'Level';
            $csv_fields[0][] = 'Age Band';
            $csv_fields[0][] = 'Main Aim';
            $csv_fields[0][] = 'Aim Type';
            $index = 0;
            while ($row = $st->fetch()) {
                $index++;
                $csv_fields[$index][] = $row['l03'];
                $csv_fields[$index][] = $row['contract_title'];
                $csv_fields[$index][] = $row['programme_type'];
                $csv_fields[$index][] = $row['start_date'];
                $csv_fields[$index][] = $row['planned_end_date'];
                $csv_fields[$index][] = $row['level'];
                $csv_fields[$index][] = $row['age_band'];
                $csv_fields[$index][] = $row['a09'];
                $csv_fields[$index][] = $row['aim_type'];
            }
        }
        foreach ($csv_fields as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        // Apprentices Higher 16-18
        $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/higher_apprentices_16-18.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');
        $sql = <<<HEREDOC
SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL > 3 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type = 'Apprenticeship' GROUP BY l03;
HEREDOC;

        $st = $link->query($sql);
        if ($st) {
            $csv_fields = array();
            $csv_fields[0] = array();
            $csv_fields[0][] = 'Contract';
            $csv_fields[0][] = 'L03';
            $csv_fields[0][] = 'Programme Type';
            $csv_fields[0][] = 'Start Date';
            $csv_fields[0][] = 'Planned End Date';
            $csv_fields[0][] = 'Level';
            $csv_fields[0][] = 'Age Band';
            $csv_fields[0][] = 'Main Aim';
            $csv_fields[0][] = 'Aim Type';
            $index = 0;
            while ($row = $st->fetch()) {
                $index++;
                $csv_fields[$index][] = $row['contract_title'];
                $csv_fields[$index][] = $row['l03'];
                $csv_fields[$index][] = $row['programme_type'];
                $csv_fields[$index][] = $row['start_date'];
                $csv_fields[$index][] = $row['planned_end_date'];
                $csv_fields[$index][] = $row['level'];
                $csv_fields[$index][] = $row['age_band'];
                $csv_fields[$index][] = $row['a09'];
                $csv_fields[$index][] = $row['aim_type'];
            }
        }
        foreach ($csv_fields as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        // Apprentices Higher 19-24
        $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/higher_apprentices_19_plus.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');
        $sql = <<<HEREDOC
SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL > 3 AND (age_band = '19-24' OR age_band = '25+') AND programme_type = 'Apprenticeship' GROUP BY l03;
HEREDOC;

        $st = $link->query($sql);
        if ($st) {
            $csv_fields = array();
            $csv_fields[0] = array();
            $csv_fields[0][] = 'Contract';
            $csv_fields[0][] = 'L03';
            $csv_fields[0][] = 'Programme Type';
            $csv_fields[0][] = 'Start Date';
            $csv_fields[0][] = 'Planned End Date';
            $csv_fields[0][] = 'Level';
            $csv_fields[0][] = 'Age Band';
            $csv_fields[0][] = 'Main Aim';
            $csv_fields[0][] = 'Aim Type';
            $index = 0;
            while ($row = $st->fetch()) {
                $index++;
                $csv_fields[$index][] = $row['contract_title'];
                $csv_fields[$index][] = $row['l03'];
                $csv_fields[$index][] = $row['programme_type'];
                $csv_fields[$index][] = $row['start_date'];
                $csv_fields[$index][] = $row['planned_end_date'];
                $csv_fields[$index][] = $row['level'];
                $csv_fields[$index][] = $row['age_band'];
                $csv_fields[$index][] = $row['a09'];
                $csv_fields[$index][] = $row['aim_type'];
            }
        }
        foreach ($csv_fields as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        // number of learners aged 14-16
        $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/number_of_learners_14-16.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');
        $sql = <<<HEREDOC
SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE age_band = '14-16' GROUP BY l03;
HEREDOC;

        $st = $link->query($sql);
        if ($st) {
            $csv_fields = array();
            $csv_fields[0] = array();
            $csv_fields[0][] = 'Contract';
            $csv_fields[0][] = 'L03';
            $csv_fields[0][] = 'Programme Type';
            $csv_fields[0][] = 'Start Date';
            $csv_fields[0][] = 'Planned End Date';
            $csv_fields[0][] = 'Level';
            $csv_fields[0][] = 'Age Band';
            $csv_fields[0][] = 'Main Aim';
            $csv_fields[0][] = 'Aim Type';
            $index = 0;
            while ($row = $st->fetch()) {
                $index++;
                $csv_fields[$index][] = $row['contract_title'];
                $csv_fields[$index][] = $row['l03'];
                $csv_fields[$index][] = $row['programme_type'];
                $csv_fields[$index][] = $row['start_date'];
                $csv_fields[$index][] = $row['planned_end_date'];
                $csv_fields[$index][] = $row['level'];
                $csv_fields[$index][] = $row['age_band'];
                $csv_fields[$index][] = $row['a09'];
                $csv_fields[$index][] = $row['aim_type'];
            }
        }
        foreach ($csv_fields as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        // number of employability learners
        $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/number_of_employability_learners.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');
        $sql = <<<HEREDOC
SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE locate('PETP',contract_title)>1 or locate('BRS',contract_title)>1 GROUP BY l03;
HEREDOC;

        $st = $link->query($sql);
        if ($st) {
            $csv_fields = array();
            $csv_fields[0] = array();
            $csv_fields[0][] = 'Contract';
            $csv_fields[0][] = 'L03';
            $csv_fields[0][] = 'Programme Type';
            $csv_fields[0][] = 'Start Date';
            $csv_fields[0][] = 'Planned End Date';
            $csv_fields[0][] = 'Level';
            $csv_fields[0][] = 'Age Band';
            $csv_fields[0][] = 'Main Aim';
            $csv_fields[0][] = 'Aim Type';
            $index = 0;
            while ($row = $st->fetch()) {
                $index++;
                $csv_fields[$index][] = $row['contract_title'];
                $csv_fields[$index][] = $row['l03'];
                $csv_fields[$index][] = $row['programme_type'];
                $csv_fields[$index][] = $row['start_date'];
                $csv_fields[$index][] = $row['planned_end_date'];
                $csv_fields[$index][] = $row['level'];
                $csv_fields[$index][] = $row['age_band'];
                $csv_fields[$index][] = $row['a09'];
                $csv_fields[$index][] = $row['aim_type'];
            }
        }
        foreach ($csv_fields as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);


        // Loop through all the contracts starting with the most recent
        $current_contract_year = DAO::getSingleValue($link, "SELECT contract_year FROM contracts ORDER BY contract_year DESC LIMIT 0,1;");
        $this->createTempTable2($link);
        $values = '';
        $counter = 0;
        $data = array();

        for ($year = $current_contract_year; $year >= ($current_contract_year - 4); $year--) {
            if ($_SESSION['user']->isAdmin()) {
                $sql = "SELECT * FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE ilr.is_active = 1 and contracts.funding_body = 2 and submission = (SELECT MAX(submission) FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE contract_year = $year) AND funding_type=1 and contract_year = '$year' and tr_id not in (SELECT tr_id FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE contract_year > $year);";
            } else {
                $org_id = $_SESSION['user']->employer_id;
                $ukprn = DAO::getSingleValue($link, "select ukprn from organisations where id = '$org_id'");
                $sql = "SELECT * FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE ilr.is_active=1 and contracts on contracts.funding_body = 2 and submission = (SELECT MAX(submission) FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE contract_year = $year) and locate('$ukprn',ilr)>0 AND funding_type=1 and contract_year = '$year' and tr_id not in (SELECT tr_id FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE contract_year > $year);";
            }
            $st = $link->query($sql);
            if ($st) {
                while ($row = $st->fetch()) {
                    if ($row['contract_year'] < 2012) {
                        $ilr = Ilr2011::loadFromXML($row['ilr']);
                        $tr_id = $row['tr_id'];
                        $submission = $row['submission'];
                        $l03 = $row['L03'];
                        $contract_id = $row['contract_id'];
                        $p_prog_status = -1;

                        if ($ilr->learnerinformation->L08 != "Y") {
                            if (($ilr->programmeaim->A15 != "99" && $ilr->programmeaim->A15 != "" && $ilr->programmeaim->A15 != "0")) {
                                $programme_type = "Apprenticeship";
                                $start_date = Date::toMySQL($ilr->programmeaim->A27);
                                $end_date = Date::toMySQL($ilr->programmeaim->A28);

                                // Age Band Calculation
                                if ($ilr->learnerinformation->L11 != '00/00/0000' && $ilr->learnerinformation->L11 != '00000000') {
                                    $dob = $ilr->learnerinformation->L11;
                                    $dob = Date::toMySQL($dob);
                                    $age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
                                } else {
                                    $age = '';
                                }
                                if ($age <= 18)
                                    $age_band = "16-18";
                                elseif ($age <= 24)
                                    $age_band = "19-24";
                                elseif ($age >= 25)
                                    $age_band = "25+";
                                else
                                    $age_band = "Unknown";

                                if ($ilr->programmeaim->A31 != '00000000' && $ilr->programmeaim->A31 != '00/00/0000' && $ilr->programmeaim->A31 != '')
                                    $actual_date = Date::toMySQL($ilr->programmeaim->A31);
                                else
                                    $actual_date = "0000-00-00";

                                if ($ilr->programmeaim->A40 != '00000000' && $ilr->programmeaim->A40 != '00/00/0000' && $ilr->programmeaim->A40 != '')
                                    $achievement_date = Date::toMySQL($ilr->programmeaim->A40);
                                else
                                    $achievement_date = "0000-00-00";

                                $level = $ilr->programmeaim->A15;


                                // Calculation for p_prog_status for apprenticeship only
                                if ($ilr->programmeaim->A15 == '2' || $ilr->programmeaim->A15 == '3' || $ilr->programmeaim->A15 == '10') {
                                    $p_prog_status = 7;
                                    if ($actual_date == '0000-00-00')
                                        $p_prog_status = 0;
                                    if ($achievement_date != '' && $achievement_date != '0000-00-00')
                                        $p_prog_status = 1;
                                    if ($actual_date != '0000-00-00' && ($ilr->programmeaim->A35 == 4 || $ilr->programmeaim->A35 == 5) && $achievement_date != '0000-00-00')
                                        $p_prog_status = 3;
                                    if ($ilr->aims[0]->A40 != '00000000' && $actual_date != '0000-00-00' && $achievement_date == '0000-00-00')
                                        $p_prog_status = 4;
                                    if ($ilr->aims[0]->A40 != '00000000' && $actual_date == '0000-00-00')
                                        $p_prog_status = 5;
                                    if ($ilr->aims[0]->A40 == '00000000' && $actual_date != '0000-00-00' && $achievement_date == '0000-00-00')
                                        $p_prog_status = 6;
                                    if ($ilr->programmeaim->A34 == 3)
                                        $p_prog_status = 13;
                                    if ($ilr->programmeaim->A34 == 4 || $ilr->programmeaim->A34 == 5)
                                        $p_prog_status = 8;
                                    if ($ilr->programmeaim->A50 == 2)
                                        $p_prog_status = 9;
                                    if ($ilr->programmeaim->A50 == 7)
                                        $p_prog_status = 10;
                                    if ($ilr->programmeaim->A34 == 6)
                                        $p_prog_status = 11;
                                    if ($ilr->aims[0]->A40 != '00000000' && $ilr->programmeaim->A34 == 6)
                                        $p_prog_status = 12;
                                }

                                $a23 = $ilr->programmeaim->A23;

                                $local_authority = DAO::getSingleValue($link, "select local_authority from central.lookup_postcode_la where postcode='$a23' limit 0,1");
                                if ($local_authority == '') {
                                    $postcode = str_replace(" ", "", $a23);
                                    $page = @file_get_contents("http://www.uk-postcodes.com/postcode/" . $postcode);
                                    $local_authority = substr($page, strpos($page, "<strong>District</strong>"), (strpos($page, "<strong>Ward</strong>") - strpos($page, "<strong>District</strong>")));
                                    $local_authority = str_replace("<strong>District</strong>", "", $local_authority);
                                    $local_authority = @substr($local_authority, strpos($local_authority, ">") + 1, (strpos($local_authority, "<", 2) - strpos($local_authority, ">") - 1));
                                    $local_authority = @str_replace("City Council", "", $local_authority);
                                    $local_authority = @str_replace("District", "", $local_authority);
                                    $local_authority = @str_replace("Council", "", $local_authority);
                                    $local_authority = @str_replace("Borough", "", $local_authority);
                                    if ($local_authority == "")
                                        $local_authority = "Not Found";
                                    $local_authority = str_replace("'", "\'", $local_authority);
                                    DAO::execute($link, "insert into central.lookup_postcode_la (postcode, local_authority) values('$a23', '$local_authority')");
                                }
                                $local_authority = str_replace("'", "\'", $local_authority);

                                $a26 = $ilr->programmeaim->A26;
                                $a09 = $ilr->aims[0]->A09;

                                $ukprn = $ilr->aims[0]->A22;
                                if ($ukprn != '' && $ukprn != '00000000' && $ukprn != '        ') {
                                    $provider = DAO::getSingleValue($link, "select legal_name from organisations where ukprn = '$ukprn'");
                                } else {
                                    $provider = '';
                                }


                                $ethnicity = $ilr->learnerinformation->L12;

                                $d = array();
                                $d['l03'] = $l03;
                                $d['tr_id'] = $tr_id;
                                $d['programme_type'] = $programme_type;
                                $d['start_date'] = $start_date;
                                $d['planned_end_date'] = $end_date;
                                $d['actual_end_date'] = $actual_date;
                                $d['achievement_date'] = $achievement_date;
                                $d['expected'] = 0;
                                $d['actual'] = 0;
                                $d['hybrid'] = 0;
                                $d['p_prog_status'] = $p_prog_status;
                                $d['contract_id'] = $contract_id;
                                $d['submission'] = $submission;
                                $d['level'] = $level;
                                $d['age_band'] = $age_band;
                                $d['a09'] = $a09;
                                $d['local_authority'] = $local_authority;
                                $d['region'] = $a23;
                                $d['postcode'] = $a23;
                                $d['sfc'] = $a26;
                                $d['ssa1'] = '';
                                $d['ssa2'] = '';
                                //$d['glh'] = $glh;
                                $d['employer'] = '';
                                $d['assessor'] = '';
                                $d['provider'] = $provider;
                                $d['contractor'] = '';
                                $d['ethnicity']    = $ethnicity;
                                $data[] = $d;

                                //$values .= "('$l03',$tr_id,'$programme_type','$start_date','$end_date', '$actual_date','$achievement_date' , 0, 0, 0, $p_prog_status, $contract_id, '$submission', '$level','$age_band','$a09','$local_authority','$a23','$a23','$a26','$ssa1','$ssa2','$employer','$assessor','$provider','$contractor','$ethnicity'),";
                            } else {

                                for ($a = 0; $a <= $ilr->subaims; $a++) {
                                    // Calclation of A_TTGAIN

                                    if (($ilr->aims[$a]->A10 == '45' || $ilr->aims[$a]->A10 == '46' || $ilr->aims[$a]->A10 == '60') && ($ilr->aims[$a]->A15 != '2' && $ilr->aims[$a]->A15 != '3' && $ilr->aims[$a]->A15 != '10') && ($ilr->aims[$a]->A46a != '83' && $ilr->aims[$a]->A46b != '83')) {

                                        // Age Band Calculation
                                        if (($ilr->aims[$a]->A18 == '24' || $ilr->aims[$a]->A18 == '23' || $ilr->aims[$a]->A18 == '22') && $ilr->aims[$a]->A46a != '125')
                                            $programme_type = "Workplace";
                                        elseif ($ilr->aims[$a]->A18 == '1' || $ilr->aims[$a]->A46a == '125')
                                            $programme_type = "Classroom";
                                        else
                                            $programme_type = "Unknown";
                                        $start_date = Date::toMySQL($ilr->aims[$a]->A27);
                                        $end_date = Date::toMySQL($ilr->aims[$a]->A28);

                                        if ($ilr->learnerinformation->L11 != '00/00/0000') {
                                            $dob = $ilr->learnerinformation->L11;
                                            $dob = Date::toMySQL($dob);
                                            $age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
                                        } else {
                                            $age = '';
                                        }
                                        if ($age <= 18)
                                            $age_band = "16-18";
                                        elseif ($age <= 24)
                                            $age_band = "19-24";
                                        elseif ($age >= 25)
                                            $age_band = "25+";
                                        else
                                            $age = "Unknown";

                                        if ($ilr->aims[$a]->A31 != '00000000' && $ilr->aims[$a]->A31 != '00/00/0000' && $ilr->aims[$a]->A31 != '')
                                            $actual_date = Date::toMySQL($ilr->aims[$a]->A31);
                                        else
                                            $actual_date = "0000-00-00";

                                        if ($ilr->aims[$a]->A40 != '00000000' && $ilr->aims[$a]->A40 != '00/00/0000' && $ilr->aims[$a]->A40 != '')
                                            $achievement_date = Date::toMySQL($ilr->aims[$a]->A40);
                                        else
                                            $achievement_date = "0000-00-00";

                                        $level = $ilr->aims[$a]->A15;
                                        $a09 = $ilr->aims[$a]->A09;

                                        // Calculation for p_prog_status for apprenticeship only
                                        $p_prog_status = 7;
                                        if ($actual_date == '0000-00-00')
                                            $p_prog_status = 0;
                                        if ($achievement_date != '0000-00-00')
                                            $p_prog_status = 1;
                                        if ($actual_date != '0000-00-00' && ($ilr->aims[$a]->A35 == 4 || $ilr->aims[$a]->A35 == 5) && $achievement_date == '0000-00-00')
                                            $p_prog_status = 3;
                                        if ($actual_date != '0000-00-00' && $achievement_date == '0000-00-00')
                                            $p_prog_status = 6;
                                        if ($ilr->aims[$a]->A34 == 3)
                                            $p_prog_status = 13;
                                        if ($ilr->aims[$a]->A34 == 4 || $ilr->aims[$a]->A34 == 5)
                                            $p_prog_status = 8;
                                        if ($ilr->aims[$a]->A50 == 2)
                                            $p_prog_status = 9;
                                        if ($ilr->aims[$a]->A50 == 7)
                                            $p_prog_status = 10;
                                        if ($ilr->aims[$a]->A34 == 6)
                                            $p_prog_status = 11;

                                        $a23 = trim($ilr->aims[0]->A23);

                                        if (strlen($a23) > 8)
                                            pre("Postcode " . $a23 . " is not correct");

                                        $local_authority = DAO::getSingleValue($link, "select local_authority from central.lookup_postcode_la where postcode='$a23' limit 0,1");
                                        if ($local_authority == '') {
                                            $postcode = str_replace(" ", "", $a23);
                                            $page = @file_get_contents("http://www.uk-postcodes.com/postcode/" . $postcode);
                                            $local_authority = substr($page, strpos($page, "<strong>District</strong>"), (strpos($page, "<strong>Ward</strong>") - strpos($page, "<strong>District</strong>")));
                                            $local_authority = str_replace("<strong>District</strong>", "", $local_authority);
                                            $local_authority = @substr($local_authority, strpos($local_authority, ">") + 1, (strpos($local_authority, "<", 2) - strpos($local_authority, ">") - 1));
                                            $local_authority = @str_replace("City Council", "", $local_authority);
                                            $local_authority = @str_replace("District", "", $local_authority);
                                            $local_authority = @str_replace("Council", "", $local_authority);
                                            $local_authority = @str_replace("Borough", "", $local_authority);
                                            if ($local_authority == '')
                                                $local_authority = "Not Found";
                                            $local_authority = str_replace("'", "\'", $local_authority);
                                            DAO::execute($link, "insert into central.lookup_postcode_la (postcode, local_authority) values('$a23', '$local_authority')");
                                        }
                                        $local_authority = str_replace("'", "\'", $local_authority);

                                        $a09 = $ilr->aims[0]->A09;
                                        $a26 = $ilr->aims[0]->A26;


                                        $ukprn = $ilr->aims[$a]->A22;
                                        if ($ukprn != '' && $ukprn != '00000000' && $ukprn != '        ') {
                                            $provider = DAO::getSingleValue($link, "select legal_name from organisations where ukprn = '$ukprn'");
                                        } else {
                                            $provider = '';
                                        }

                                        $provider = addslashes((string)$provider);
                                        $ethnicity = $ilr->learnerinformation->L12;

                                        $d = array();
                                        $d['l03'] = $l03;
                                        $d['tr_id'] = $tr_id;
                                        $d['programme_type'] = $programme_type;
                                        $d['start_date'] = $start_date;
                                        $d['planned_end_date'] = $end_date;
                                        $d['actual_end_date'] = $actual_date;
                                        $d['achievement_date'] = $achievement_date;
                                        $d['expected'] = 0;
                                        $d['actual'] = 0;
                                        $d['hybrid'] = 0;
                                        $d['p_prog_status'] = $p_prog_status;
                                        $d['contract_id'] = $contract_id;
                                        $d['submission'] = $submission;
                                        $d['level'] = $level;
                                        $d['age_band'] = $age_band;
                                        $d['a09'] = $a09;
                                        $d['local_authority'] = $local_authority;
                                        $d['region'] = $a23;
                                        $d['postcode'] = $a23;
                                        $d['sfc'] = $a26;
                                        $d['ssa1'] = '';
                                        $d['ssa2'] = '';
                                        //$d['glh'] = $glh;
                                        $d['employer'] = '';
                                        $d['assessor'] = '';
                                        $d['provider'] = $provider;
                                        $d['contractor'] = '';
                                        $d['ethnicity']    = $ethnicity;
                                        $data[] = $d;
                                    }
                                }
                            }

                            $counter++;
                        }
                    } else {
                        $ilr = Ilr2012::loadFromXML($row['ilr']);
                        $tr_id = $row['tr_id'];
                        $submission = $row['submission'];
                        $l03 = $row['L03'];
                        $contract_id = $row['contract_id'];
                        $p_prog_status = -1;

                        foreach ($ilr->LearningDelivery as $delivery) {
                            if ($delivery->AimType == 1 && $delivery->ProgType != '99') {
                                $programme_type = "Apprenticeship";
                                $a26 = "" . $delivery->FworkCode;
                                $start_date = Date::toMySQL("" . $delivery->LearnStartDate);
                                $end_date = Date::toMySQL("" . $delivery->LearnPlanEndDate);
                                if (("" . $ilr->DateOfBirth) != '00/00/0000' && ("" . $ilr->DateOfBirth) != '00000000') {
                                    $dob = "" . $ilr->DateOfBirth;
                                    $dob = Date::toMySQL($dob);
                                    $age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
                                } else {
                                    $age = '';
                                }
                                // Age Band Calculation
                                if ($age <= 18)
                                    $age_band = "16-18";
                                elseif ($age <= 24)
                                    $age_band = "19-24";
                                elseif ($age >= 25)
                                    $age_band = "25+";
                                else
                                    $age_band = "Unknown";

                                if ($delivery->LearnActEndDate != '00000000' && $delivery->LearnActEndDate != '00/00/0000' && $delivery->LearnActEndDate != '')
                                    $actual_date = Date::toMySQL($delivery->LearnActEndDate);
                                else
                                    $actual_date = "0000-00-00";

                                if ($delivery->AchDate != '00000000' && $delivery->AchDate != '00/00/0000' && $delivery->AchDate != '')
                                    $achievement_date = Date::toMySQL($delivery->AchDate);
                                else
                                    $achievement_date = "0000-00-00";

                                $level = "" . $delivery->ProgType;

                                // Calculation for p_prog_status for apprenticeship only
                                if ($delivery->ProgType == '2' || $delivery->ProgType == '3' || $delivery->ProgType == '10') {
                                    $p_prog_status = 7;
                                    if ($actual_date == '0000-00-00')
                                        $p_prog_status = 0;
                                    if ($achievement_date != '' && $achievement_date != '0000-00-00')
                                        $p_prog_status = 1;
                                    if ($actual_date != '0000-00-00' && ($delivery->Outcome == '4' || $delivery->Outcome == '5') && $achievement_date != '0000-00-00')
                                        $p_prog_status = 3;
                                    if ($achievement_date && $actual_date != '0000-00-00' && $achievement_date == '0000-00-00')
                                        $p_prog_status = 4;
                                    if ($achievement_date && $actual_date == '0000-00-00')
                                        $p_prog_status = 5;
                                    if ($achievement_date && $actual_date != '0000-00-00' && $achievement_date == '0000-00-00')
                                        $p_prog_status = 6;
                                    if ($delivery->CompStatus == '3')
                                        $p_prog_status = 13;
                                    if ($delivery->CompStatus == 4 || $delivery->CompStatus == 5)
                                        $p_prog_status = 8;
                                    if ($delivery->WithdrawReason == 2)
                                        $p_prog_status = 9;
                                    if ($delivery->WithdrawReason == 7)
                                        $p_prog_status = 10;
                                    if ($delivery->CompStatus == 6)
                                        $p_prog_status = 11;
                                    if ($delivery->AchDate != '00000000' && $delivery->CompStatus == 6)
                                        $p_prog_status = 12;
                                }
                                $a23 = "" . $delivery->DelLocPostCode;
                                $local_authority = DAO::getSingleValue($link, "select local_authority from central.lookup_postcode_la where postcode='$a23' limit 0,1");
                                if ($local_authority == '') {
                                    $postcode = str_replace(" ", "", $a23);
                                    $page = @file_get_contents("http://www.uk-postcodes.com/postcode/" . $postcode);
                                    $local_authority = substr($page, strpos($page, "<strong>District</strong>"), (strpos($page, "<strong>Ward</strong>") - strpos($page, "<strong>District</strong>")));
                                    $local_authority = str_replace("<strong>District</strong>", "", $local_authority);
                                    $local_authority = @substr($local_authority, strpos($local_authority, ">") + 1, (strpos($local_authority, "<", 2) - strpos($local_authority, ">") - 1));
                                    $local_authority = @str_replace("City Council", "", $local_authority);
                                    $local_authority = @str_replace("District", "", $local_authority);
                                    $local_authority = @str_replace("Council", "", $local_authority);
                                    $local_authority = @str_replace("Borough", "", $local_authority);
                                    if ($local_authority == "")
                                        $local_authority = "Not Found";
                                    $local_authority = str_replace("'", "\'", $local_authority);
                                    DAO::execute($link, "insert into central.lookup_postcode_la (postcode, local_authority) values('$a23', '$local_authority')");
                                }
                                $local_authority = str_replace("'", "\'", $local_authority);

                                $a09 = '';
                                foreach ($ilr->LearningDelivery as $d) {
                                    if ($d->AimType == 1 || $d->AimType == 4) {
                                        $a09 = "" . $d->LearnAimRef;
                                        $ukprn = "" . $d->PartnerUKPRN;
                                    }
                                }
                                //if($a09!='')
                                //{
                                //		$ssa1 = DAO::getSingleValue($link, "SELECT CONCAT(lad200910.SSA_TIER1_CODES.SSA_TIER1_CODE,' ',lad200910.SSA_TIER1_CODES.SSA_TIER1_DESC) FROM lad200910.SSA_TIER1_CODES INNER JOIN lad200910.ALL_ANNUAL_VALUES ON lad200910.ALL_ANNUAL_VALUES.SSA_TIER1_CODE = lad200910.SSA_TIER1_CODES.SSA_TIER1_CODE WHERE ALL_ANNUAL_VALUES.LEARNING_AIM_REF = '$a09';");
                                //			$ssa2 = DAO::getSingleValue($link, "SELECT CONCAT(lad200910.SSA_TIER2_CODES.SSA_TIER2_CODE,' ',lad200910.SSA_TIER2_CODES.SSA_TIER2_DESC) FROM lad200910.SSA_TIER2_CODES INNER JOIN lad200910.ALL_ANNUAL_VALUES ON lad200910.ALL_ANNUAL_VALUES.SSA_TIER2_CODE = lad200910.SSA_TIER2_CODES.SSA_TIER2_CODE WHERE lad200910.ALL_ANNUAL_VALUES.LEARNING_AIM_REF = '$a09'");
                                //		}

                                if ($ukprn != '' && $ukprn != '00000000' && $ukprn != '        ') {
                                    $provider = DAO::getSingleValue($link, "select legal_name from organisations where ukprn = '$ukprn'");
                                } else {
                                    $provider = '';
                                }

                                $provider = addslashes((string)$provider);
                                $ethnicity = "" . $ilr->Ethnicity;
                                $d = array();
                                $d['l03'] = $l03;
                                $d['tr_id'] = $tr_id;
                                $d['programme_type'] = $programme_type;
                                $d['start_date'] = $start_date;
                                $d['planned_end_date'] = $end_date;
                                $d['actual_end_date'] = $actual_date;
                                $d['achievement_date'] = $achievement_date;
                                $d['expected'] = 0;
                                $d['actual'] = 0;
                                $d['hybrid'] = 0;
                                $d['p_prog_status'] = $p_prog_status;
                                $d['contract_id'] = $contract_id;
                                $d['submission'] = $submission;
                                $d['level'] = $level;
                                $d['age_band'] = $age_band;
                                $d['a09'] = $a09;
                                $d['local_authority'] = $local_authority;
                                $d['region'] = $a23;
                                $d['postcode'] = $a23;
                                $d['sfc'] = $a26;
                                $d['ssa1'] = '';
                                $d['ssa2'] = '';
                                //$d['glh'] = $glh;
                                $d['employer'] = '';
                                $d['assessor'] = '';
                                $d['provider'] = $provider;
                                $d['contractor'] = '';
                                $d['ethnicity']    = $ethnicity;
                                $data[] = $d;
                            } else {
                                if ($delivery->AimType == 4 && $delivery->FundModel != '99') {
                                    $ldm = '';
                                    foreach ($delivery->LearningDeliveryFAM as $ldf) {
                                        if ($ldf->LearnDelFAMType == 'LDM')
                                            if ($ldf->LearnDelFAMCode == '125')
                                                $ldm = 'Classroom';
                                    }

                                    if ($ldm == 'Classroom')
                                        $programme_type = "Classroom";
                                    elseif ($delivery->MainDelMeth == '24' || $delivery->MainDelMeth == '23' || $delivery->MainDelMeth == '22')
                                        $programme_type = "Workplace";
                                    else
                                        $programme_type = "Unknown";

                                    $start_date = Date::toMySQL($delivery->LearnStartDate);
                                    $end_date = Date::toMySQL($delivery->LearnPlanEndDate);

                                    if ($ilr->DateOfBirth != '00/00/0000') {
                                        $dob = "" . $ilr->DateOfBirth;
                                        $dob = Date::toMySQL($dob);
                                        $age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
                                    } else {
                                        $age = '';
                                    }
                                    if ($age <= 18)
                                        $age_band = "16-18";
                                    elseif ($age <= 24)
                                        $age_band = "19-24";
                                    elseif ($age >= 25)
                                        $age_band = "25+";
                                    else
                                        $age = "Unknown";

                                    if ($delivery->LearnActEndDate != '00000000' && $delivery->LearnActEndDate != '00/00/0000' && $delivery->LearnActEndDate != '')
                                        $actual_date = Date::toMySQL($delivery->LearnActEndDate);
                                    else
                                        $actual_date = "0000-00-00";

                                    if ($delivery->AchDate != '00000000' && $delivery->AchDate != '00/00/0000' && $delivery->AchDate != '')
                                        $achievement_date = Date::toMySQL($delivery->AchDate);
                                    else
                                        $achievement_date = "0000-00-00";

                                    $level = "" . $delivery->ProgType;
                                    $a09 = "" . $delivery->LearnAimRef;
                                    // Calculation for p_prog_status for apprenticeship only
                                    $p_prog_status = 7;
                                    if ($actual_date == '0000-00-00')
                                        $p_prog_status = 0;
                                    if ($achievement_date != '0000-00-00')
                                        $p_prog_status = 1;
                                    if ($actual_date != '0000-00-00' && ($delivery->Outcome == 4 || $delivery->Outcome == 5) && $achievement_date == '0000-00-00')
                                        $p_prog_status = 3;
                                    if ($actual_date != '0000-00-00' && $achievement_date == '0000-00-00')
                                        $p_prog_status = 6;
                                    if ($delivery->CompStatus == 3)
                                        $p_prog_status = 13;
                                    if ($delivery->CompStatus == 4 || $delivery->CompStatus == 5)
                                        $p_prog_status = 8;
                                    if ($delivery->WithdrawReason == 2)
                                        $p_prog_status = 9;
                                    if ($delivery->WithdrawReason == 7)
                                        $p_prog_status = 10;
                                    if ($delivery->CompStatus == 6)
                                        $p_prog_status = 11;

                                    $a23 = trim($delivery->DelLocPostCode);
                                    $local_authority = DAO::getSingleValue($link, "select local_authority from central.lookup_postcode_la where postcode='$a23' limit 0,1");
                                    if ($local_authority == '') {
                                        $postcode = str_replace(" ", "", $a23);
                                        $page = @file_get_contents("http://www.uk-postcodes.com/postcode/" . $postcode);
                                        $local_authority = substr($page, strpos($page, "<strong>District</strong>"), (strpos($page, "<strong>Ward</strong>") - strpos($page, "<strong>District</strong>")));
                                        $local_authority = str_replace("<strong>District</strong>", "", $local_authority);
                                        $local_authority = @substr($local_authority, strpos($local_authority, ">") + 1, (strpos($local_authority, "<", 2) - strpos($local_authority, ">") - 1));
                                        $local_authority = @str_replace("City Council", "", $local_authority);
                                        $local_authority = @str_replace("District", "", $local_authority);
                                        $local_authority = @str_replace("Council", "", $local_authority);
                                        $local_authority = @str_replace("Borough", "", $local_authority);
                                        if ($local_authority == '')
                                            $local_authority = "Not Found";
                                        $local_authority = str_replace("'", "\'", $local_authority);
                                        DAO::execute($link, "insert into central.lookup_postcode_la (postcode, local_authority) values('$a23', '$local_authority')");
                                    }
                                    $local_authority = str_replace("'", "\'", $local_authority);

                                    $ukprn = "" . $delivery->PartnerUKPRN;
                                    if ($ukprn != '' && $ukprn != '00000000' && $ukprn != '        ') {
                                        $provider = DAO::getSingleValue($link, "select legal_name from organisations where ukprn = '$ukprn'");
                                    } else {
                                        $provider = '';
                                    }

                                    $provider = addslashes((string)$provider);
                                    $ethnicity = $ilr->Ethnicity;

                                    $d = array();
                                    $d['l03'] = $l03;
                                    $d['tr_id'] = $tr_id;
                                    $d['programme_type'] = $programme_type;
                                    $d['start_date'] = $start_date;
                                    $d['planned_end_date'] = $end_date;
                                    $d['actual_end_date'] = $actual_date;
                                    $d['achievement_date'] = $achievement_date;
                                    $d['expected'] = 0;
                                    $d['actual'] = 0;
                                    $d['hybrid'] = 0;
                                    $d['p_prog_status'] = $p_prog_status;
                                    $d['contract_id'] = $contract_id;
                                    $d['submission'] = $submission;
                                    $d['level'] = $level;
                                    $d['age_band'] = $age_band;
                                    $d['a09'] = $a09;
                                    $d['local_authority'] = $local_authority;
                                    $d['region'] = $a23;
                                    $d['postcode'] = $a23;
                                    $d['sfc'] = '';
                                    $d['ssa1'] = '';
                                    $d['ssa2'] = '';
                                    //$d['glh'] = $glh;
                                    $d['employer'] = '';
                                    $d['assessor'] = '';
                                    $d['provider'] = $provider;
                                    $d['contractor'] = '';
                                    $d['ethnicity']    = $ethnicity;
                                    $d['aim_type'] = '';
                                    $data[] = $d;
                                }
                            }
                        }
                        $counter++;
                    }
                }
            }
        }
        DAO::multipleRowInsert($link, "success_rates", $data);
        DAO::execute($link, "update success_rates INNER JOIN lad201213.all_annual_values on all_annual_values.LEARNING_AIM_REF = success_rates.a09 INNER JOIN lad201213.ssa_tier1_codes on ssa_tier1_codes.SSA_TIER1_CODE = all_annual_values.SSA_TIER1_CODE set ssa1 = CONCAT(lad201213.ssa_tier1_codes.SSA_TIER1_CODE,' ',lad201213.ssa_tier1_codes.SSA_TIER1_DESC)");
        DAO::execute($link, "update success_rates INNER JOIN lad201213.all_annual_values on all_annual_values.LEARNING_AIM_REF = success_rates.a09 INNER JOIN lad201213.ssa_tier2_codes on ssa_tier2_codes.SSA_TIER2_CODE = all_annual_values.SSA_TIER2_CODE set ssa2 = CONCAT(lad201213.ssa_tier2_codes.SSA_TIER2_CODE,' ',lad201213.ssa_tier2_codes.SSA_TIER2_DESC)");
        DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN organisations on organisations.id = tr.employer_id set employer = organisations.legal_name");
        DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN organisations on organisations.id = tr.provider_id set provider = organisations.legal_name where provider='' or provider is NULL");
        DAO::execute($link, "update success_rates INNER JOIN contracts on contracts.id = success_rates.contract_id INNER JOIN organisations on organisations.id = contracts.contract_holder set contractor = organisations.legal_name");
        DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN users on users.id = tr.assessor set success_rates.assessor = CONCAT(users.firstnames, ' ', users.surname)");
        DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN group_members on group_members.tr_id = tr.id INNER JOIN groups on group_members.groups_id = groups.id INNER JOIN users on users.id = groups.assessor set success_rates.assessor = CONCAT(users.firstnames, ' ', users.surname) where success_rates.assessor is NULL or success_rates.assessor=''");

        DAO::execute($link, "DELETE FROM success_rates WHERE (p_prog_status = 13 or p_prog_status=6 or p_prog_status=-1 or p_prog_status=8)  AND DATE_ADD(start_date, INTERVAL 42 DAY)>actual_end_date and programme_type!='Classroom';");
        DAO::execute($link, "DELETE FROM success_rates WHERE p_prog_status = 8 OR p_prog_status=12;");

        //pre($link->errorInfo());
        DAO::execute($link, "UPDATE success_rates SET actual = (SELECT contract_year FROM central.lookup_submission_dates WHERE success_rates.actual_end_date >= central.lookup_submission_dates.census_start_date AND success_rates.actual_end_date <= central.lookup_submission_dates.census_end_date and central.lookup_submission_dates.contract_type = '2'), expected = (SELECT contract_year FROM central.lookup_submission_dates WHERE success_rates.planned_end_date >= central.lookup_submission_dates.census_start_date AND success_rates.planned_end_date <= central.lookup_submission_dates.census_end_date and central.lookup_submission_dates.contract_type = '2');");
        DAO::execute($link, "update success_rates set ethnicity = (select Ethnicity_Desc from lis201112.ilr_l12_ethnicity where TRIM(Ethnicity_Code)=trim(success_rates.ethnicity) UNION select Ethnicity_Desc from lis201011.ilr_l12_ethnicity where TRIM(Ethnicity_Code)=trim(success_rates.ethnicity) limit 0,1);");
        DAO::execute($link, "update success_rates INNER JOIN lad201213.frameworks on frameworks.FRAMEWORK_CODE = success_rates.sfc set sfc = frameworks.FRAMEWORK_DESC");
        DAO::execute($link, "update success_rates set sfc = LEFT(sfc,POSITION('-' IN sfc)-1)");
        DAO::execute($link, "update success_rates LEFT JOIN lad201213.learning_aim on learning_aim.LEARNING_AIM_REF = success_rates.a09 LEFT JOIN lad201213.learning_aim_types on learning_aim_types.LEARNING_AIM_TYPE_CODE = learning_aim.LEARNING_AIM_TYPE_CODE set aim_type = LEARNING_AIM_TYPE_DESC");
        DAO::execute($link, "update success_rates set ssa1 = sfc where ssa1='X Not Applicable'");

        DAO::execute($link, "DELETE FROM success_rates WHERE aim_type = 'QCF Units' and programme_type = 'Classroom'");
        DAO::execute($link, "DELETE FROM success_rates WHERE aim_type = 'Employability Award' and programme_type = 'Classroom'");
        //      DAO::execute($link, "drop table ofsted2");
        //      DAO::execute($link, "create table ofsted2 select * From success_rates");


        // main course level 1 or below 16-18
        $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/basedata.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');
        $sql = <<<HEREDOC
SELECT * from success_rates;
HEREDOC;

        $st = $link->query($sql);
        if ($st) {
            $csv_fields = array();
            $csv_fields[0] = array();
            $csv_fields[0][] = 'L03';
            $csv_fields[0][] = 'Programme Type';
            $csv_fields[0][] = 'Start Date';
            $csv_fields[0][] = 'Planned End Date';
            $csv_fields[0][] = 'Actual End Date';
            $csv_fields[0][] = 'Achievement Date';
            $csv_fields[0][] = 'Expected End Year';
            $csv_fields[0][] = 'Actual End Year';
            $csv_fields[0][] = 'Hybrid End Year';
            $csv_fields[0][] = 'P Prog Status';
            $csv_fields[0][] = 'Submission';
            $csv_fields[0][] = 'Level';
            $csv_fields[0][] = 'Age Band';
            $csv_fields[0][] = 'Learning Aim Reference';
            $csv_fields[0][] = 'Local Authority';
            $csv_fields[0][] = 'Government Region';
            $csv_fields[0][] = 'PostCode';
            $csv_fields[0][] = 'SFC';
            $csv_fields[0][] = 'Sector Subject Area Tier 1';
            $csv_fields[0][] = 'Sector Subject Area Tier 2';
            $csv_fields[0][] = 'Employer';
            $csv_fields[0][] = 'Assessor';
            $csv_fields[0][] = 'Provider';
            $csv_fields[0][] = 'Contractor';
            $csv_fields[0][] = 'Ethnicity';
            $csv_fields[0][] = 'Aim Type';
            $index = 0;
            while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
                $index++;
                fputcsv($fp, $row);
                /*$csv_fields[$index][] = $row['l03'];
                $csv_fields[$index][] = $row['programme_type'];
                $csv_fields[$index][] = $row['start_date'];
                $csv_fields[$index][] = $row['planned_end_date'];
                $csv_fields[$index][] = $row['actual_end_date'];
                $csv_fields[$index][] = $row['achievement_date'];
                $csv_fields[$index][] = $row['expected'];
                $csv_fields[$index][] = $row['actual'];
                $csv_fields[$index][] = $row['hybrid'];
                $csv_fields[$index][] = $row['p_prog_status'];
                $csv_fields[$index][] = $row['submission'];
                $csv_fields[$index][] = $row['level'];
                $csv_fields[$index][] = $row['age_band'];
                $csv_fields[$index][] = $row['a09'];
                $csv_fields[$index][] = $row['local_authority'];
                $csv_fields[$index][] = $row['region'];
                $csv_fields[$index][] = $row['postcode'];
                $csv_fields[$index][] = $row['sfc'];
                $csv_fields[$index][] = $row['ssa1'];
                $csv_fields[$index][] = $row['ssa2'];
                $csv_fields[$index][] = $row['employer'];
                $csv_fields[$index][] = $row['assessor'];
                $csv_fields[$index][] = $row['provider'];
                $csv_fields[$index][] = $row['contractor'];
                $csv_fields[$index][] = $row['ethnicity'];
                $csv_fields[$index][] = $row['aim_type'];*/
            }
        }
        /*        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }*/
        fclose($fp);

        // Populate PDF
        $pdf = new FPDI();
        $pagecount = $pdf->setSourceFile('ofsted.pdf');
        $tpl = $pdf->ImportPage(1);
        $s = $pdf->getTemplatesize($tpl);
        $pdf->AddPage('P', array($s['width'], $s['height']));
        $pdf->useTemplate($tpl);
        $pdf->SetFont('Arial', '', 10);
        $tpl = $pdf->ImportPage(2);
        $s = $pdf->getTemplatesize($tpl);
        $pdf->AddPage('P', array($s['width'], $s['height']));
        $pdf->useTemplate($tpl);
        $pdf->SetFont('Arial', '', 10);
        $tpl = $pdf->ImportPage(3);
        $s = $pdf->getTemplatesize($tpl);
        $pdf->AddPage('P', array($s['width'], $s['height']));
        $pdf->useTemplate($tpl);
        $pdf->SetFont('Arial', '', 10);
        $tpl = $pdf->ImportPage(4);
        $s = $pdf->getTemplatesize($tpl);
        $pdf->AddPage('P', array($s['width'], $s['height']));
        $pdf->useTemplate($tpl);
        $pdf->SetFont('Arial', '', 10);

        $tpl = $pdf->ImportPage(5);
        $s = $pdf->getTemplatesize($tpl);
        $pdf->AddPage('P', array($s['width'], $s['height']));
        $pdf->useTemplate($tpl);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Text(152, 73, $m1);
        $pdf->Text(152, 83, $m2);
        $pdf->Text(93, 141, $m3);
        $pdf->Text(103, 141, $m4);
        $pdf->Text(118, 141, $m5);
        $pdf->Text(128, 141, $m6);
        $pdf->Text(143, 141, $m7);
        $pdf->Text(155, 141, $m8);
        $pdf->Text(169, 141, $m9);
        $pdf->Text(182, 141, $m10);
        $pdf->Text(95, 165, $m11);
        $pdf->Text(110, 165, $m12);
        $pdf->Text(127, 165, $m13);
        $pdf->Text(143, 165, $m14);
        $pdf->Text(162, 165, $m15);
        $pdf->Text(178, 165, $m16);
        $pdf->Text(127, 200, $m17);

        $tpl = $pdf->ImportPage(6);
        $s = $pdf->getTemplatesize($tpl);
        $pdf->AddPage('P', array($s['width'], $s['height']));
        $pdf->useTemplate($tpl);
        $pdf->SetFont('Arial', '', 10);

        $ssas = DAO::getSingleColumn($link, "select distinct ssa1 from success_rates where expected = 2012 or actual=2012");
        foreach ($ssas as $ssa) {
            $tpl = $pdf->ImportPage(7);
            $s = $pdf->getTemplatesize($tpl);
            $pdf->AddPage('P', array($s['width'], $s['height']));
            $pdf->useTemplate($tpl);
            $pdf->SetFont('Arial', '', 10);

            $pdf->Text(92, 105, $ssa);
            $pdf->Text(20, 168, $ssa);

            $overallachievers = $this->getOverallAchievers($link, "2012", "", "", "", "", $ssa, "", "", "", "", "", "", "");
            $overallleavers = $this->getOverallLeaver($link, "2012", "", "", "", "", $ssa, "", "", "", "", "", "", "");
            if ($overallleavers != 0) {
                $overallrate = ($overallachievers / $overallleavers * 100);
                $pdf->Text(100, 185, sprintf("%.2f", $overallrate));
            }

            $overallachievers = $this->getOverallAchievers($link, "2012", "Classroom", "", "", "", $ssa, "", "", "", "", "", "", " and (timestampdiff(week,start_date,planned_end_date)) > 24");
            $overallleavers = $this->getOverallLeaver($link, "2012", "Classroom", "", "", "", $ssa, "", "", "", "", "", "", " and (timestampdiff(week,start_date,planned_end_date)) > 24");
            if ($overallleavers != 0) {
                $overallrate = ($overallachievers / $overallleavers * 100);
                $pdf->Text(150, 100, "Khush");
                $pdf->Text(100, 192, sprintf("%.2f", $overallrate));
            }

            $overallleavers = $this->getOverallLeaver($link, "2012", "Workplace", "", "", "", $ssa, "", "", "", "", "", "", " and (timestampdiff(week,start_date,planned_end_date)) > 24");
            if ($overallleavers != 0) {
                $pdf->Text(146, 114, "Y");
            }

            $overallachievers = $this->getOverallAchievers($link, "2012", "Apprenticeship", "", "", "", $ssa, "", "", "", "", "", "", "");
            $overallleavers = $this->getOverallLeaver($link, "2012", "Apprenticeship", "", "", "", $ssa, "", "", "", "", "", "", "");
            if ($overallleavers != 0) {
                $overallrate = ($overallachievers / $overallleavers * 100);
                $pdf->Text(100, 199, sprintf("%.2f", $overallrate));
            }

            $overallachievers = $this->getTimelyAchievers($link, "2012", "Apprenticeship", "", "", "", $ssa, "", "", "", "", "", "", "");
            $overallleavers = $this->getTimelyLeaver($link, "2012", "Apprenticeship", "", "", "", $ssa, "", "", "", "", "", "", "");
            if ($overallleavers != 0) {
                $overallrate = ($overallachievers / $overallleavers * 100);
                $pdf->Text(100, 206, sprintf("%.2f", $overallrate));
            }
        }

        DAO::execute($link, "drop table if exists success_rates2");
        DAO::execute($link, "create table success_rates2 select * from success_rates");

        //      $ssas = DAO::getResultSet($link, "SELECT a09, COUNT(a09), (SELECT LEVEL FROM qualifications WHERE REPLACE(id,'/','') = a09 LIMIT 0,1) AS level FROM success_rates WHERE (expected = 2012 OR actual = 2012) GROUP BY a09  ORDER BY COUNT(a09) DESC LIMIT 0,3");
        $tpl = $pdf->ImportPage(8);
        $s = $pdf->getTemplatesize($tpl);
        $pdf->AddPage('P', array($s['width'], $s['height']));
        $pdf->useTemplate($tpl);
        $pdf->SetFont('Arial', '', 10);
        //$row = 56;
        /*      foreach($ssas as $ssa)
        {
            $row = $row + 39;
            $pdf->Text(123,$row,$ssa[1]);
            $row = $row + 11;
            $a09 = $ssa[0];
            $overallachievers = $this->getOverallAchievers($link, "2012", "", "", "", "", "","","","","","",""," and a09 = '$a09'");
            $overallleavers = $this->getOverallLeaver($link, "2012", "", "", "", "", "","","","","","",""," and a09 = '$a09'");
            if($overallleavers!=0)
            {
                $overallrate = ($overallachievers / $overallleavers * 100);
                $pdf->Text(123,$row,sprintf("%.2f",$overallrate));
            }
            $row = $row + 9;
            $pdf->Text(20,$row,$ssa[0]);
            $pdf->Text(60,$row,$ssa[2]);
        }
*/
        $ssas = DAO::getResultSet($link, "SELECT a09, COUNT(a09), (SELECT LEVEL FROM qualifications WHERE REPLACE(id,'/','') = a09 LIMIT 0,1) AS level FROM success_rates WHERE (expected = 2012 OR actual = 2012) GROUP BY a09  ORDER BY COUNT(a09) DESC LIMIT 3,2");
        $tpl = $pdf->ImportPage(9);
        $s = $pdf->getTemplatesize($tpl);
        $pdf->AddPage('P', array($s['width'], $s['height']));
        $pdf->useTemplate($tpl);
        $pdf->SetFont('Arial', '', 10);
        $row = 61;
        foreach ($ssas as $ssa) {
            $row = $row + 39;
            $pdf->Text(123, $row, $ssa[1]);
            $row = $row + 11;
            $a09 = $ssa[0];
            $overallachievers = $this->getOverallAchievers($link, "2012", "", "", "", "", "", "", "", "", "", "", "", " and a09 = '$a09'");
            $overallleavers = $this->getOverallLeaver($link, "2012", "", "", "", "", "", "", "", "", "", "", "", " and a09 = '$a09'");
            if ($overallleavers != 0) {
                $overallrate = ($overallachievers / $overallleavers * 100);
                $pdf->Text(123, $row, sprintf("%.2f", $overallrate));
            }
            $row = $row + 9;
            $pdf->Text(20, $row, $ssa[0]);
            $pdf->Text(60, $row, $ssa[2]);
        }


        $ssas = DAO::getResultSet($link, "SELECT a09, COUNT(a09), (SELECT LEVEL FROM qualifications WHERE REPLACE(id,'/','') = a09 LIMIT 0,1) AS level FROM success_rates WHERE (expected = 2012 OR actual = 2012) GROUP BY a09  ORDER BY COUNT(a09) DESC LIMIT 5,2");
        $tpl = $pdf->ImportPage(9);
        $s = $pdf->getTemplatesize($tpl);
        $pdf->AddPage('P', array($s['width'], $s['height']));
        $pdf->useTemplate($tpl);
        $pdf->SetFont('Arial', '', 10);
        $row = 61;
        foreach ($ssas as $ssa) {
            $row = $row + 39;
            $pdf->Text(123, $row, $ssa[1]);
            $row = $row + 11;
            $a09 = $ssa[0];
            $overallachievers = $this->getOverallAchievers($link, "2012", "", "", "", "", "", "", "", "", "", "", "", " and a09 = '$a09'");
            $overallleavers = $this->getOverallLeaver($link, "2012", "", "", "", "", "", "", "", "", "", "", "", " and a09 = '$a09'");
            if ($overallleavers != 0) {
                $overallrate = ($overallachievers / $overallleavers * 100);
                $pdf->Text(123, $row, sprintf("%.2f", $overallrate));
            }
            $row = $row + 9;
            $pdf->Text(20, $row, $ssa[0]);
            $pdf->Text(60, $row, $ssa[2]);
        }

        $ssas = DAO::getResultSet($link, "SELECT a09, COUNT(a09), (SELECT LEVEL FROM qualifications WHERE REPLACE(id,'/','') = a09 LIMIT 0,1) AS level FROM success_rates WHERE (expected = 2012 OR actual = 2012) GROUP BY a09  ORDER BY COUNT(a09) DESC LIMIT 7,2");
        $tpl = $pdf->ImportPage(9);
        $s = $pdf->getTemplatesize($tpl);
        $pdf->AddPage('P', array($s['width'], $s['height']));
        $pdf->useTemplate($tpl);
        $pdf->SetFont('Arial', '', 10);
        $row = 61;
        foreach ($ssas as $ssa) {
            $row = $row + 39;
            $pdf->Text(123, $row, $ssa[1]);
            $row = $row + 11;
            $a09 = $ssa[0];
            $overallachievers = $this->getOverallAchievers($link, "2012", "", "", "", "", "", "", "", "", "", "", "", " and a09 = '$a09'");
            $overallleavers = $this->getOverallLeaver($link, "2012", "", "", "", "", "", "", "", "", "", "", "", " and a09 = '$a09'");
            if ($overallleavers != 0) {
                $overallrate = ($overallachievers / $overallleavers * 100);
                $pdf->Text(123, $row, sprintf("%.2f", $overallrate));
            }
            $row = $row + 9;
            $pdf->Text(20, $row, $ssa[0]);
            $pdf->Text(60, $row, $ssa[2]);
        }

        $ssas = DAO::getResultSet($link, "SELECT a09, COUNT(a09), (SELECT LEVEL FROM qualifications WHERE REPLACE(id,'/','') = a09 LIMIT 0,1) AS level FROM success_rates WHERE (expected = 2012 OR actual = 2012) GROUP BY a09  ORDER BY COUNT(a09) DESC LIMIT 9,2");
        $tpl = $pdf->ImportPage(9);
        $s = $pdf->getTemplatesize($tpl);
        $pdf->AddPage('P', array($s['width'], $s['height']));
        $pdf->useTemplate($tpl);
        $pdf->SetFont('Arial', '', 10);
        $row = 61;
        foreach ($ssas as $ssa) {
            $row = $row + 39;
            $pdf->Text(123, $row, $ssa[1]);
            $row = $row + 11;
            $a09 = $ssa[0];
            $overallachievers = $this->getOverallAchievers($link, "2012", "", "", "", "", "", "", "", "", "", "", "", " and a09 = '$a09'");
            $overallleavers = $this->getOverallLeaver($link, "2012", "", "", "", "", "", "", "", "", "", "", "", " and a09 = '$a09'");
            if ($overallleavers != 0) {
                $overallrate = ($overallachievers / $overallleavers * 100);
                $pdf->Text(123, $row, sprintf("%.2f", $overallrate));
            }
            $row = $row + 9;
            $pdf->Text(20, $row, $ssa[0]);
            $pdf->Text(60, $row, $ssa[2]);
        }

        $ssas = DAO::getResultSet($link, "SELECT a09, COUNT(a09), (SELECT LEVEL FROM qualifications WHERE REPLACE(id,'/','') = a09 LIMIT 0,1) AS level FROM success_rates WHERE (expected = 2012 OR actual = 2012) GROUP BY a09  ORDER BY COUNT(a09) DESC LIMIT 11,2");
        $tpl = $pdf->ImportPage(9);
        $s = $pdf->getTemplatesize($tpl);
        $pdf->AddPage('P', array($s['width'], $s['height']));
        $pdf->useTemplate($tpl);
        $pdf->SetFont('Arial', '', 10);
        $row = 61;
        foreach ($ssas as $ssa) {
            $row = $row + 39;
            $pdf->Text(123, $row, $ssa[1]);
            $row = $row + 11;
            $a09 = $ssa[0];
            $overallachievers = $this->getOverallAchievers($link, "2012", "", "", "", "", "", "", "", "", "", "", "", " and a09 = '$a09'");
            $overallleavers = $this->getOverallLeaver($link, "2012", "", "", "", "", "", "", "", "", "", "", "", " and a09 = '$a09'");
            if ($overallleavers != 0) {
                $overallrate = ($overallachievers / $overallleavers * 100);
                $pdf->Text(123, $row, sprintf("%.2f", $overallrate));
            }
            $row = $row + 9;
            $pdf->Text(20, $row, $ssa[0]);
            $pdf->Text(60, $row, $ssa[2]);
        }

        $ssas = DAO::getResultSet($link, "SELECT a09, COUNT(a09), (SELECT LEVEL FROM qualifications WHERE REPLACE(id,'/','') = a09 LIMIT 0,1) AS level FROM success_rates WHERE (expected = 2012 OR actual = 2012) GROUP BY a09  ORDER BY COUNT(a09) DESC LIMIT 13,2");
        $tpl = $pdf->ImportPage(9);
        $s = $pdf->getTemplatesize($tpl);
        $pdf->AddPage('P', array($s['width'], $s['height']));
        $pdf->useTemplate($tpl);
        $pdf->SetFont('Arial', '', 10);
        $row = 61;
        foreach ($ssas as $ssa) {
            $row = $row + 39;
            $pdf->Text(123, $row, $ssa[1]);
            $row = $row + 11;
            $a09 = $ssa[0];
            $overallachievers = $this->getOverallAchievers($link, "2012", "", "", "", "", "", "", "", "", "", "", "", " and a09 = '$a09'");
            $overallleavers = $this->getOverallLeaver($link, "2012", "", "", "", "", "", "", "", "", "", "", "", " and a09 = '$a09'");
            if ($overallleavers != 0) {
                $overallrate = ($overallachievers / $overallleavers * 100);
                $pdf->Text(123, $row, sprintf("%.2f", $overallrate));
            }
            $row = $row + 9;
            $pdf->Text(20, $row, $ssa[0]);
            $pdf->Text(60, $row, $ssa[2]);
        }

        $ssas = DAO::getResultSet($link, "SELECT a09, COUNT(a09), (SELECT LEVEL FROM qualifications WHERE REPLACE(id,'/','') = a09 LIMIT 0,1) AS level FROM success_rates WHERE (expected = 2012 OR actual = 2012) GROUP BY a09  ORDER BY COUNT(a09) DESC LIMIT 15,2");
        $tpl = $pdf->ImportPage(9);
        $s = $pdf->getTemplatesize($tpl);
        $pdf->AddPage('P', array($s['width'], $s['height']));
        $pdf->useTemplate($tpl);
        $pdf->SetFont('Arial', '', 10);
        $row = 61;
        foreach ($ssas as $ssa) {
            $row = $row + 39;
            $pdf->Text(123, $row, $ssa[1]);
            $row = $row + 11;
            $a09 = $ssa[0];
            $overallachievers = $this->getOverallAchievers($link, "2012", "", "", "", "", "", "", "", "", "", "", "", " and a09 = '$a09'");
            $overallleavers = $this->getOverallLeaver($link, "2012", "", "", "", "", "", "", "", "", "", "", "", " and a09 = '$a09'");
            if ($overallleavers != 0) {
                $overallrate = ($overallachievers / $overallleavers * 100);
                $pdf->Text(123, $row, sprintf("%.2f", $overallrate));
            }
            $row = $row + 9;
            $pdf->Text(20, $row, $ssa[0]);
            $pdf->Text(60, $row, $ssa[2]);
        }

        $ssas = DAO::getResultSet($link, "SELECT a09, COUNT(a09), (SELECT LEVEL FROM qualifications WHERE REPLACE(id,'/','') = a09 LIMIT 0,1) AS level FROM success_rates WHERE (expected = 2012 OR actual = 2012) GROUP BY a09  ORDER BY COUNT(a09) DESC LIMIT 17,2");
        $tpl = $pdf->ImportPage(9);
        $s = $pdf->getTemplatesize($tpl);
        $pdf->AddPage('P', array($s['width'], $s['height']));
        $pdf->useTemplate($tpl);
        $pdf->SetFont('Arial', '', 10);
        $row = 61;
        foreach ($ssas as $ssa) {
            $row = $row + 39;
            $pdf->Text(123, $row, $ssa[1]);
            $row = $row + 11;
            $a09 = $ssa[0];
            $overallachievers = $this->getOverallAchievers($link, "2012", "", "", "", "", "", "", "", "", "", "", "", " and a09 = '$a09'");
            $overallleavers = $this->getOverallLeaver($link, "2012", "", "", "", "", "", "", "", "", "", "", "", " and a09 = '$a09'");
            if ($overallleavers != 0) {
                $overallrate = ($overallachievers / $overallleavers * 100);
                $pdf->Text(123, $row, sprintf("%.2f", $overallrate));
            }
            $row = $row + 9;
            $pdf->Text(20, $row, $ssa[0]);
            $pdf->Text(60, $row, $ssa[2]);
        }

        $ssas = DAO::getResultSet($link, "SELECT a09, COUNT(a09), (SELECT LEVEL FROM qualifications WHERE REPLACE(id,'/','') = a09 LIMIT 0,1) AS level FROM success_rates WHERE (expected = 2012 OR actual = 2012) GROUP BY a09  ORDER BY COUNT(a09) DESC LIMIT 19,2");
        $tpl = $pdf->ImportPage(9);
        $s = $pdf->getTemplatesize($tpl);
        $pdf->AddPage('P', array($s['width'], $s['height']));
        $pdf->useTemplate($tpl);
        $pdf->SetFont('Arial', '', 10);
        $row = 61;
        foreach ($ssas as $ssa) {
            $row = $row + 39;
            $pdf->Text(123, $row, $ssa[1]);
            $row = $row + 11;
            $a09 = $ssa[0];
            $overallachievers = $this->getOverallAchievers($link, "2012", "", "", "", "", "", "", "", "", "", "", "", " and a09 = '$a09'");
            $overallleavers = $this->getOverallLeaver($link, "2012", "", "", "", "", "", "", "", "", "", "", "", " and a09 = '$a09'");
            if ($overallleavers != 0) {
                $overallrate = ($overallachievers / $overallleavers * 100);
                $pdf->Text(123, $row, sprintf("%.2f", $overallrate));
            }
            $row = $row + 9;
            $pdf->Text(20, $row, $ssa[0]);
            $pdf->Text(60, $row, $ssa[2]);
        }

        // Prepare directory
        $admin_reports = Repository::getRoot() . '/ofsted';
        if (is_file($admin_reports)) {
            throw new Exception("admin_reports exists but it is a file and not a directory");
        }
        if (!is_dir($admin_reports)) {
            mkdir($admin_reports);
        }
        $pdf->Output($admin_reports . "/ofsted.pdf", 'F');
        @unlink($admin_reports . "/data.zip");

        // create object
        $zip = new ZipArchive();
        if ($zip->open("../uploads/" . DB_NAME . "/ofsted/data.zip", ZIPARCHIVE::CREATE) !== TRUE) {
            die("Could not open archive");
        }


        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/main_course_level_1_or_below_16-18.csv", "main_course_level_1_or_below_16-18.csv") or die("ERROR: Could not add file:");
        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/main_course_level_1_or_below_19_plus.csv", "main_course_level_1_or_below_19_plus.csv") or die("ERROR: Could not add file:");
        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/main_course_level_2_16-18.csv", "main_course_level_2_16-18.csv") or die("ERROR: Could not add file:");
        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/main_course_level_2_19_plus.csv", "main_course_level_2_19_plus.csv") or die("ERROR: Could not add file:");
        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/main_course_level_3_16-18.csv", "main_course_level_3_16-18.csv") or die("ERROR: Could not add file:");
        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/main_course_level_3_19_plus.csv", "main_course_level_3_19_plus.csv") or die("ERROR: Could not add file:");
        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/main_course_level_4_16-18.csv", "main_course_level_4_16-18.csv") or die("ERROR: Could not add file:");
        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/main_course_level_4_19_plus.csv", "main_course_level_4_19_plus.csv") or die("ERROR: Could not add file:");
        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/intermediate_apprentices_16-18.csv", "intermediate_apprentices_16-18.csv") or die("ERROR: Could not add file:");
        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/intermediate_apprentices_19_plus.csv", "intermediate_apprentices_19_plus.csv") or die("ERROR: Could not add file:");
        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/advanced_apprentices_16-18.csv", "advanced_apprentices_16-18.csv") or die("ERROR: Could not add file:");
        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/advanced_apprentices_19_plus.csv", "advanced_apprentices_19_plus.csv") or die("ERROR: Could not add file:");
        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/higher_apprentices_16-18.csv", "higher_apprentices_16-18.csv") or die("ERROR: Could not add file:");
        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/higher_apprentices_19_plus.csv", "higher_apprentices_19_plus.csv") or die("ERROR: Could not add file:");
        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/number_of_learners_14-16.csv", "number_of_learners_14-16.csv") or die("ERROR: Could not add file:");
        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/number_of_employability_learners.csv", "number_of_employability_learners.csv") or die("ERROR: Could not add file:");
        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/ofsted.pdf", "ofsted.pdf") or die("ERROR: Could not add file:");
        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/basedata.csv", "basedata.csv") or die("ERROR: Could not add file:");

        $zip->close();
        http_redirect("do.php?_action=downloader&path=/" . DB_NAME . "/ofsted/&f=data.zip");
    }

    public function createTempTable(PDO $link)
    {
        $sql = <<<HEREDOC
CREATE TEMPORARY TABLE `ofsted` (
  `l03` varchar(12) DEFAULT NULL,
  `tr_id` int(11) DEFAULT NULL,
  `programme_type` varchar(15) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `planned_end_date` date DEFAULT NULL,
  `actual_end_date` date DEFAULT NULL,
  `achievement_date` date DEFAULT NULL,
  `expected` int(11) DEFAULT NULL,
  `actual` int(11) DEFAULT NULL,
  `hybrid` int(11) DEFAULT NULL,
  `p_prog_status` int(11) DEFAULT NULL,
  `contract_id` int(11) DEFAULT NULL,
  `submission` varchar(3) DEFAULT NULL,
  `level` varchar(20) DEFAULT NULL,
  `age_band` varchar(20) DEFAULT NULL,
  `a09` varchar(8) DEFAULT NULL,
  `local_authority` varchar(50) DEFAULT NULL,
  `region` varchar(50) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL,
  `sfc` varchar(100) DEFAULT NULL,
  `ssa1` varchar(100) DEFAULT NULL,
  `ssa2` varchar(100) DEFAULT NULL,
  `employer` varchar(100) DEFAULT NULL,
  `assessor` varchar(100) DEFAULT NULL,
  `provider` varchar(100) DEFAULT NULL,
  `contractor` varchar(100) DEFAULT NULL,
  `ethnicity` varchar(255) DEFAULT NULL,
  `aim_type` varchar(50) DEFAULT NULL,
  `contract_title` varchar(50) DEFAULT NULL,
  KEY `prog` (`programme_type`,`expected`,`actual`),
  INDEX(ssa1), INDEX(ssa2), index(programme_type), index(age_band)
) ENGINE 'MEMORY'
HEREDOC;
        DAO::execute($link, $sql);
    }

    public function createTempTable2(PDO $link)
    {
        $sql = <<<HEREDOC
CREATE TEMPORARY TABLE `success_rates` (
  `l03` varchar(12) DEFAULT NULL,
  `tr_id` int(11) DEFAULT NULL,
  `programme_type` varchar(15) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `planned_end_date` date DEFAULT NULL,
  `actual_end_date` date DEFAULT NULL,
  `achievement_date` date DEFAULT NULL,
  `expected` int(11) DEFAULT NULL,
  `actual` int(11) DEFAULT NULL,
  `hybrid` int(11) DEFAULT NULL,
  `p_prog_status` int(11) DEFAULT NULL,
  `contract_id` int(11) DEFAULT NULL,
  `submission` varchar(3) DEFAULT NULL,
  `level` varchar(20) DEFAULT NULL,
  `age_band` varchar(20) DEFAULT NULL,
  `a09` varchar(8) DEFAULT NULL,
  `local_authority` varchar(50) DEFAULT NULL,
  `region` varchar(50) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL,
  `sfc` varchar(100) DEFAULT NULL,
  `ssa1` varchar(100) DEFAULT NULL,
  `ssa2` varchar(100) DEFAULT NULL,
  `employer` varchar(100) DEFAULT NULL,
  `assessor` varchar(100) DEFAULT NULL,
  `provider` varchar(100) DEFAULT NULL,
  `contractor` varchar(100) DEFAULT NULL,
  `ethnicity` varchar(255) DEFAULT NULL,
  `aim_type` varchar(50) DEFAULT NULL,
  KEY `prog` (`programme_type`,`expected`,`actual`),
  INDEX(ssa1), INDEX(ssa2), index(programme_type), index(age_band)
) ENGINE 'MEMORY'
HEREDOC;
        DAO::execute($link, $sql);
    }


    public function getOverallAchievers($link, $year, $programme_type, $age_band, $level, $region = '', $ssa = '', $sfc = '', $employer = '', $assessor = '', $provider = '', $contractor = '', $ethnicity = '', $where = '')
    {
        if ($region == 'All regions')
            $region = '';
        if ($employer == 'All employers')
            $employer = '';
        if ($assessor == 'All assessors')
            $assessor = '';
        if ($provider == 'All providers')
            $provider = '';
        if ($contractor == 'All contractors')
            $contractor = '';
        if ($ethnicity == 'All ethnicities')
            $ethnicity = '';
        if ($programme_type == 'All programmes')
            $programme_type = '';

        $sfc = addslashes((string)$sfc);
        if ($level != '')
            $where .= " and level = '$level'";
        if ($age_band != '')
            $where .= " and age_band = '$age_band'";
        if ($region != '')
            $where .= " and region='$region'";
        if ($ssa != '')
            $where .= " and ssa1='$ssa'";
        if ($sfc != '')
            $where .= " and ssa2='$sfc'";
        if ($employer != '')
            $where .= " and employer='$employer'";
        if ($assessor != '')
            $where .= " and assessor='$assessor'";
        if ($provider != '')
            $where .= " and provider='$provider'";
        if ($contractor != '')
            $where .= " and contractor='$contractor'";
        if ($ethnicity != '')
            $where .= " and ethnicity='$ethnicity'";
        if ($programme_type != '')
            $where .= " and programme_type='$programme_type'";

        return DAO::getSingleValue($link, "SELECT count(*) FROM success_rates WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year)) AND p_prog_status = 1 $where;");
    }

    public function getOverallLeaver($link, $year, $programme_type, $age_band, $level, $region = '', $ssa = '', $sfc = '', $employer = '', $assessor = '', $provider = '', $contractor = '', $ethnicity = '', $where = '')
    {
        if ($region == 'All regions')
            $region = '';
        if ($employer == 'All employers')
            $employer = '';
        if ($assessor == 'All assessors')
            $assessor = '';
        if ($provider == 'All providers')
            $provider = '';
        if ($contractor == 'All contractors')
            $contractor = '';
        if ($ethnicity == 'All ethnicities')
            $ethnicity = '';
        if ($programme_type == 'All programmes')
            $programme_type = '';

        $sfc = addslashes((string)$sfc);
        if ($level != '')
            $where .= " and level = '$level'";
        if ($age_band != '')
            $where .= " and age_band = '$age_band'";
        if ($region != '')
            $where .= " and region='$region'";
        if ($ssa != '')
            $where .= " and ssa1='$ssa'";
        if ($sfc != '')
            $where .= " and ssa2='$sfc'";
        if ($employer != '')
            $where .= " and employer='$employer'";
        if ($assessor != '')
            $where .= " and assessor='$assessor'";
        if ($provider != '')
            $where .= " and provider='$provider'";
        if ($contractor != '')
            $where .= " and contractor='$contractor'";
        if ($ethnicity != '')
            $where .= " and ethnicity='$ethnicity'";
        if ($programme_type != '')
            $where .= " and programme_type='$programme_type'";

        return DAO::getSingleValue($link, "SELECT count(*) FROM success_rates WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year))  $where;");
    }

    public function getTimelyAchievers($link, $year, $programme_type, $age_band, $level, $region = '', $ssa = '', $sfc = '', $employer = '', $assessor = '', $provider = '', $contractor = '', $ethnicity = '', $where = '')
    {
        if ($region == 'All regions')
            $region = '';
        if ($employer == 'All employers')
            $employer = '';
        if ($assessor == 'All assessors')
            $assessor = '';
        if ($provider == 'All providers')
            $provider = '';
        if ($contractor == 'All contractors')
            $contractor = '';
        if ($ethnicity == 'All ethnicities')
            $ethnicity = '';
        if ($programme_type == 'All programmes')
            $programme_type = '';

        $sfc = addslashes((string)$sfc);
        if ($level != '')
            $where .= " and level = '$level'";
        if ($age_band != '')
            $where .= " and age_band = '$age_band'";
        if ($region != '')
            $where .= " and region='$region'";
        if ($ssa != '')
            $where .= " and ssa1='$ssa'";
        if ($sfc != '')
            $where .= " and ssa2='$sfc'";
        if ($employer != '')
            $where .= " and employer='$employer'";
        if ($assessor != '')
            $where .= " and assessor='$assessor'";
        if ($provider != '')
            $where .= " and provider='$provider'";
        if ($contractor != '')
            $where .= " and contractor='$contractor'";
        if ($ethnicity != '')
            $where .= " and ethnicity='$ethnicity'";
        if ($programme_type != '')
            $where .= " and programme_type='$programme_type'";

        return DAO::getSingleValue($link, "SELECT count(*) FROM success_rates WHERE expected = $year AND p_prog_status = 1 and DATEDIFF(actual_end_date, planned_end_date)<=90  $where;");
    }

    public function getTimelyLeaver($link, $year, $programme_type, $age_band, $level, $region = '', $ssa = '', $sfc = '', $employer = '', $assessor = '', $provider = '', $contractor = '', $ethnicity = '', $where = '')
    {
        if ($region == 'All regions')
            $region = '';
        if ($employer == 'All employers')
            $employer = '';
        if ($assessor == 'All assessors')
            $assessor = '';
        if ($provider == 'All providers')
            $provider = '';
        if ($contractor == 'All contractors')
            $contractor = '';
        if ($ethnicity == 'All ethnicities')
            $ethnicity = '';
        if ($programme_type == 'All programmes')
            $programme_type = '';

        $sfc = addslashes((string)$sfc);
        if ($level != '')
            $where .= " and level = '$level'";
        if ($age_band != '')
            $where .= " and age_band = '$age_band'";
        if ($region != '')
            $where .= " and region='$region'";
        if ($ssa != '')
            $where .= " and ssa1='$ssa'";
        if ($sfc != '')
            $where .= " and ssa2='$sfc'";
        if ($employer != '')
            $where .= " and employer='$employer'";
        if ($assessor != '')
            $where .= " and assessor='$assessor'";
        if ($provider != '')
            $where .= " and provider='$provider'";
        if ($contractor != '')
            $where .= " and contractor='$contractor'";
        if ($ethnicity != '')
            $where .= " and ethnicity='$ethnicity'";
        if ($programme_type != '')
            $where .= " and programme_type='$programme_type'";

        return DAO::getSingleValue($link, "SELECT count(*) FROM success_rates WHERE expected = $year and p_prog_status!=0 $where;");
    }

    public function array2xml($array, $xml = false)
    {
        if ($xml === false) {
            $xml = new SimpleXMLElement('<root/>');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                array2xml($value, $xml->addChild($key));
            } else {
                $xml->addChild($key, $value);
            }
        }
        return $xml->asXML();
    }
}