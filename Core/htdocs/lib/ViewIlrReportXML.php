<?php

class ViewIlrReportXML extends View
{

    public static function getInstance($contract_id, $submission, $assessor, $employer, $course, $provider, $active, $valid, $lsf, $zprog)
    {
        $key = 'view_' . __CLASS__ . $contract_id . $submission . $assessor . $employer . $course;
        if (true) {
            $where = '';
            if ($assessor != '') {
                $where .= " and tr.assessor = '$assessor'";
            }
            if ($employer != '') {
                $where .= " and tr.employer_id = '$employer'";
            }
            if ($course != '') {
                $where .= " and courses_tr.course_id = '$course'";
            }
            if ($provider != '') {
                $where .= " and tr.provider_id = '$provider'";
            }
            if ($active == '2') {
                $where .= " and ilr.is_active = 1";
            }
            if ($active == '3') {
                $where .= " and ilr.is_active = 0";
            }
            if ($valid == '2') {
                $where .= " and ilr.is_valid = 1";
            }
            if ($valid == '3') {
                $where .= " and ilr.is_valid = 0";
            }
            if ($lsf == '2') {
                $where .= " AND LOCATE('LSF', ilr) > 0 ";
            }
            if ($zprog != '') {
                if ($zprog == '2') {
                    $where .= " AND LOCATE('ZPROG001', ilr) > 0 ";
                } elseif ($zprog == '3') {
                    $where .= " AND LOCATE('ZPROG001', ilr) = 0 ";
                }
            }


            // Create new view object
            $sql = <<<HEREDOC
SELECT
	ilr.*, contracts.*, tr.*, ilr.tr_id,CONCAT(advisors.firstnames,' ',advisors.surname) as registered_by, REPLACE(courses.title,',','') AS course_title, contracts.title AS contract_title,
	(SELECT legal_name FROM organisations WHERE organisations.id = tr.`employer_id`) AS employer,
	(SELECT title FROM groups INNER JOIN group_members ON groups.id = group_members.groups_id WHERE group_members.tr_id = tr.`id`) AS group_title,
	'' as qualification_title,
	((DATE_FORMAT(tr.start_date,'%Y') - DATE_FORMAT(tr.dob,'%Y')) - (DATE_FORMAT(tr.start_date,'00-%m-%d') < DATE_FORMAT(tr.dob,'00-%m-%d'))) AS age_at_enrolment,
    (SELECT extractvalue(ilr.ilr, 'sum(/Learner/LearningDelivery[LearnAimRef="ZWRKX001"]/LearningDeliveryWorkPlacement/WorkPlaceHours)')) AS ZWRKX001_Hours

FROM
	ilr
	LEFT JOIN contracts on contracts.id = ilr.contract_id
    LEFT JOIN tr on tr.id = ilr.tr_id
    LEFT JOIN courses_tr on tr.id = courses_tr.tr_id
    LEFT JOIN courses on courses_tr.course_id = courses.id
    LEFT JOIN users on users.username = tr.username
    LEFT JOIN users as advisors on advisors.username = users.who_created
WHERE
	ilr.contract_id in ($contract_id) AND submission = '$submission' $where
	order by ilr.L03
HEREDOC;
            $view = $_SESSION[$key] = new ViewIlrReportXML();
            $view->setSQL($sql);

        }

        return $_SESSION[$key];
    }


    public function render(PDO $link, $columns)
    {
        /* @var $result pdo_result */
        $st = $link->query($this->getSQL());

        if ($st) {

            //echo $this->getViewNavigator();
            echo '<div align="center"><table id="dataMatrix" class="resultset" border="0" cellspacing="0" cellpadding="6">';
            echo '<thead><tr>';
            foreach ($columns as $column) {
                if ($column == 'LDM') {
                    echo '<th>LDM</th><th>LDM</th><th>LDM</th>';
                } elseif ($column == 'HHS') {
                    echo '<th>HHS</th><th>HHS</th>';
                } elseif ($column == 'Contact') {
                    echo '<th>AddLine1</th><th>AddLine2</th><th>AddLine3</th><th>AddLine4</th><th>PostCode</th><th>Email</th><th>TelNumber</th>';
                } elseif ($column == 'Employment') {
                    echo '<th>EmpStat</th><th>Date</th><th>EmpId</th><th>LOU</th><th>BSI</th><th>EII</th><th>LOE</th><th>SEM</th><th>EmpStat</th><th>Date</th><th>EmpId</th><th>LOU</th><th>BSI</th><th>EII</th><th>LOE</th><th>SEM</th><th>EmpStat</th><th>Date</th><th>EmpId</th><th>LOU</th><th>BSI</th><th>EII</th><th>LOE</th><th>SEM</th>';
                } elseif ($column == 'LSF') {
                    echo '<th>LSF</th><th>LSFFrom</th><th>LSFTo</th>';
                } elseif ($column == 'TNP') {
                    echo '<th>TNP - Date (1)</th><th>TNP - Amount (1)</th><th>TNP - Date (2)</th><th>TNP - Amount (2)</th>';
                } else {
                    echo '<th>' . ucwords(str_replace("_", " ", str_replace("_and_", " & ", $column))) . '</th>';
                }

            }
            echo '</thead></tr>';
            echo '<tbody>';
            $learner = array('LearnRefNumber', 'ULN', 'FamilyName', 'GivenNames', 'DateOfBirth', 'Ethnicity', 'Sex', 'LLDDHealthProb', 'NINumber', 'Domicile', 'PriorAttain', 'Accom', 'ALSCost', 'DisUpFact', 'Dest', 'EngGrade', 'MathGrade', 'LLDDCat');
            while ($row = $st->fetch()) {
                $ilr = Ilr2016::loadFromXML($row['ilr']);
                foreach ($ilr->LearningDelivery as $delivery) {
                    $row['qualification_title'] = DAO::getSingleValue($link, 'SELECT title FROM student_qualifications WHERE student_qualifications.tr_id = "' . $row['tr_id'] . '" AND REPLACE(student_qualifications.id, "/", "") = "' . $delivery->LearnAimRef . '"');
                    $xpath = $ilr->xpath('/Learner/LLDDandHealthProblem/LLDDCat');
                    $llddcat = (empty($xpath)) ? '' : (string)$xpath[0];
                    $row['LLDDCat'] = (isset($llddcat) ? ($llddcat == '' ? '&nbsp' : ($llddcat == 'undefined' ? '&nbsp;' : $llddcat)) : '&nbsp');

                    echo '<tr>';
                    foreach ($columns as $column) {
                        $column = str_replace(" ", "", (ucwords(str_replace('_', ' ', $column))));
                        if ($column == 'RegisteredBy') {
                            echo '<td>' . $row['registered_by'] . '</td>';
                        } elseif ($column == 'Title') {
                            echo '<td>' . $row['title'] . '</td>';
                        } elseif (in_array($column, $learner)) {
                            if ($column == 'LLDDCat')// && SOURCE_BLYTHE_VALLEY)
                            {
                                $xpath = $ilr->xpath('/Learner/LLDDandHealthProblem[PrimaryLLDD=\'1\']/LLDDCat');
                                $primary_lldd = (empty($xpath)) ? '' : (string)$xpath[0];
                                $primary_lldd = DAO::getSingleValue($link, "SELECT CONCAT(code, ' ', description) FROM central.lookup_lldd_cat WHERE CODE IN ('$primary_lldd')");
                                echo '<td style="font-size:smaller;">' . $primary_lldd . '</td>';
                            } elseif ($column == 'DateOfBirth') {
                                echo '<td>' . ((isset($ilr->$column)) ? (($ilr->$column == '') ? '&nbsp' : str_replace("dd/mm/yyyy", "", Date::toShort($ilr->$column))) : '&nbsp') . '</td>';
                            } elseif ($column == 'PriorAttain') {
                                $xpath = $ilr->xpath('/Learner/PriorAttain/PriorLevel');
                                $priorAttain = (empty($xpath)) ? '' : (string)$xpath[0];
                                echo $priorAttain == '' ?
                                    '<td>' . $priorAttain . '</td>' :
                                    '<td>' . DAO::getSingleValue($link, "SELECT DISTINCT CONCAT(PriorAttain, ' ', PriorAttainDesc) FROM lis201415.ilr_priorattain2 WHERE PriorAttain = '{$priorAttain}';") . '</td>';
                            } else {
                                echo '<td>' . ((isset($ilr->$column)) ? (($ilr->$column == '') ? '&nbsp' : str_replace("dd/mm/yyyy", "", $ilr->$column)) : '&nbsp') . '</td>';
                            }
                        } elseif ($column == 'Contact') {
                            $xpath = $ilr->xpath('/Learner/LearnerContact/PostAdd/AddLine1');
                            $add1 = (empty($xpath)) ? '' : (string)$xpath[0];
                            $xpath = $ilr->xpath('/Learner/LearnerContact/PostAdd/AddLine2');
                            $add2 = (empty($xpath)) ? '' : (string)$xpath[0];
                            $xpath = $ilr->xpath('/Learner/LearnerContact/PostAdd/AddLine3');
                            $add3 = (empty($xpath)) ? '' : (string)$xpath[0];
                            $xpath = $ilr->xpath('/Learner/LearnerContact/PostAdd/AddLine4');
                            $add4 = (empty($xpath)) ? '' : (string)$xpath[0];
                            $xpath = $ilr->xpath('/Learner/LearnerContact/TelNumber');
                            $tel = (empty($xpath)) ? '' : $xpath[0];
                            $xpath = $ilr->xpath("/Learner/LearnerContact[ContType='2' and LocType='2']/PostCode");
                            $cp = (empty($xpath)) ? '' : $xpath[0];
                            $xpath = $ilr->xpath("/Learner/LearnerContact[ContType='1' and LocType='2']/PostCode");
                            $ppe = (empty($xpath)) ? '' : $xpath[0];
                            $xpath = $ilr->xpath("/Learner/LearnerContact[ContType='2' and LocType='4']/Email");
                            $email = (empty($xpath)) ? '' : $xpath[0];
                            echo '<td>' . (isset($add1) ? ($add1 == '' ? '&nbsp' : ($add1 == 'undefined' ? '&nbsp;' : $add1)) : '&nbsp') . '</td>';
                            echo '<td>' . (isset($add2) ? ($add2 == '' ? '&nbsp' : ($add2 == 'undefined' ? '&nbsp;' : $add2)) : '&nbsp') . '</td>';
                            echo '<td>' . (isset($add3) ? ($add3 == '' ? '&nbsp' : ($add3 == 'undefined' ? '&nbsp;' : $add3)) : '&nbsp') . '</td>';
                            echo '<td>' . (isset($add4) ? ($add4 == '' ? '&nbsp' : ($add4 == 'undefined' ? '&nbsp;' : $add4)) : '&nbsp') . '</td>';
                            echo '<td>' . (isset($cp) ? ($cp == '' ? '&nbsp' : ($cp == 'undefined' ? '&nbsp;' : $cp)) : '&nbsp') . '</td>';
                            echo '<td>' . (isset($email) ? ($email == '' ? '&nbsp' : ($email == 'undefined' ? '&nbsp;' : $email)) : '&nbsp') . '</td>';
                            echo '<td>' . (isset($tel) ? ($tel == '' ? '&nbsp' : ($tel == 'undefined' ? '&nbsp;' : $tel)) : '&nbsp') . '</td>';
                        } elseif ($column == 'Employment') {
                            $ldm = 0;
                            $counter = 0;
                            foreach ($ilr->LearnerEmploymentStatus as $les) {
                                $counter++;
                                if ($counter > 3) {
                                    break;
                                }
                                $ldm++;
                                echo '<td>' . (isset($les->EmpStat) ? ($les->EmpStat == '' ? '&nbsp' : ($les->EmpStat == 'undefined' ? '&nbsp;' : $les->EmpStat)) : '&nbsp') . '</td>';
                                echo '<td>' . (isset($les->DateEmpStatApp) ? ($les->DateEmpStatApp == '' ? '&nbsp' : ($les->DateEmpStatApp == 'undefined' ? '&nbsp;' : Date::toShort($les->DateEmpStatApp))) : '&nbsp') . '</td>';
                                echo '<td>' . (isset($les->EmpId) ? ($les->EmpId == '' ? '&nbsp' : ($les->EmpId == 'undefined' ? '&nbsp;' : $les->EmpId)) : '&nbsp') . '</td>';
                                $loufound = false;
                                foreach ($les->EmploymentStatusMonitoring as $esm) {
                                    if ($esm->ESMType == 'LOU') {
                                        $loufound = true;
                                        echo '<td>' . (isset($esm->ESMCode) ? ($esm->ESMCode == '' ? '&nbsp' : ($esm->ESMCode == 'undefined' ? '&nbsp;' : $esm->ESMCode)) : '&nbsp') . '</td>';
                                    }
                                }
                                if (!$loufound) {
                                    echo '<td>&nbsp;</td>';
                                }

                                $bsifound = false;
                                foreach ($les->EmploymentStatusMonitoring as $esm) {
                                    if ($esm->ESMType == 'BSI') {
                                        $bsifound = true;
                                        echo '<td>' . (isset($esm->ESMCode) ? ($esm->ESMCode == '' ? '&nbsp' : ($esm->ESMCode == 'undefined' ? '&nbsp;' : $esm->ESMCode)) : '&nbsp') . '</td>';
                                    }
                                }
                                if (!$bsifound) {
                                    echo '<td>&nbsp;</td>';
                                }

                                $eiifound = false;
                                foreach ($les->EmploymentStatusMonitoring as $esm) {
                                    if ($esm->ESMType == 'EII') {
                                        $eiifound = true;
                                        echo '<td>' . (isset($esm->ESMCode) ? ($esm->ESMCode == '' ? '&nbsp' : ($esm->ESMCode == 'undefined' ? '&nbsp;' : $esm->ESMCode)) : '&nbsp') . '</td>';
                                    }
                                }
                                if (!$eiifound) {
                                    echo '<td>&nbsp;</td>';
                                }

                                $loefound = false;
                                foreach ($les->EmploymentStatusMonitoring as $esm) {
                                    if ($esm->ESMType == 'LOE') {
                                        $loefound = true;
                                        echo '<td>' . (isset($esm->ESMCode) ? ($esm->ESMCode == '' ? '&nbsp' : ($esm->ESMCode == 'undefined' ? '&nbsp;' : $esm->ESMCode)) : '&nbsp') . '</td>';
                                    }
                                }
                                if (!$loefound) {
                                    echo '<td>&nbsp;</td>';
                                }

                                $semfound = false;
                                foreach ($les->EmploymentStatusMonitoring as $esm) {
                                    if ($esm->ESMType == 'SEM') {
                                        $semfound = true;
                                        echo isset($esm->ESMCode) ? '<td>' . $esm->ESMCode . '</td>' : '<td></td>';
                                    }
                                }
                                if (!$semfound) {
                                    echo '<td>&nbsp;</td>';
                                }
                            }
                            for ($ldm++; $ldm <= 3; $ldm++) {
                                echo '<td>&nbsp;</td>';
                                echo '<td>&nbsp;</td>';
                                echo '<td>&nbsp;</td>';
                                echo '<td>&nbsp;</td>';
                                echo '<td>&nbsp;</td>';
                                echo '<td>&nbsp;</td>';
                                echo '<td>&nbsp;</td>';
                                echo '<td>&nbsp;</td>';
                            }
                        } elseif ($column == 'LDM') {
                            $ldm = 0;
                            $counter = 0;
                            foreach ($delivery->LearningDeliveryFAM as $ldf) {
                                if ($ldf->LearnDelFAMType == 'LDM') {
                                    $counter++;
                                    if ($counter > 3) {
                                        break;
                                    }

                                    $ldm++;
                                    echo '<td>' . (isset($ldf->LearnDelFAMCode) ? ($ldf->LearnDelFAMCode == '' ? '&nbsp' : ($ldf->LearnDelFAMCode == 'undefined' ? '&nbsp;' : $ldf->LearnDelFAMCode)) : '&nbsp') . '</td>';
                                }
                            }
                            for ($ldm++; $ldm <= 3; $ldm++) {
                                echo '<td>&nbsp;</td>';
                            }
                        } elseif ($column == 'HHS') {
                            $ldm = 0;
                            $counter = 0;
                            foreach ($delivery->LearningDeliveryFAM as $ldf) {
                                if ($ldf->LearnDelFAMType == 'HHS') {
                                    $counter++;
                                    if ($counter > 2) {
                                        break;
                                    }

                                    $ldm++;
                                    echo '<td>' . (isset($ldf->LearnDelFAMCode) ? ($ldf->LearnDelFAMCode == '' ? '&nbsp' : ($ldf->LearnDelFAMCode == 'undefined' ? '&nbsp;' : $ldf->LearnDelFAMCode)) : '&nbsp') . '</td>';
                                }
                            }
                            for ($ldm++; $ldm <= 2; $ldm++) {
                                echo '<td>&nbsp;</td>';
                            }
                        } elseif ($column == 'FFI') {
                            $ind = 0;
                            foreach ($delivery->LearningDeliveryFAM as $ldf) {
                                if ($ldf->LearnDelFAMType == 'FFI' && isset($ldf->LearnDelFAMCode) && ($ldf->LearnDelFAMCode == '1' || $ldf->LearnDelFAMCode == '2')) {
                                    $ind++;
                                    echo '<td>' . ((isset($ldf->LearnDelFAMCode)) ? (($ldf->LearnDelFAMCode == '') ? '&nbsp' : (($ldf->LearnDelFAMCode == 'undefined') ? '&nbsp;' : $ldf->LearnDelFAMCode)) : '&nbsp') . '</td>';
                                    break;
                                }
                            }
                            if ($ind == 0) {
                                echo '<td>' . '&nbsp' . '</td>';
                            }
                        } elseif ($column == 'EEF') {
                            $ind = 0;
                            foreach ($delivery->LearningDeliveryFAM as $ldf) {
                                if ($ldf->LearnDelFAMType == 'EEF' && isset($ldf->LearnDelFAMCode) && ($ldf->LearnDelFAMCode == '1' || $ldf->LearnDelFAMCode == '2' || $ldf->LearnDelFAMCode == '3')) {
                                    $ind++;
                                    echo '<td>' . (isset($ldf->LearnDelFAMCode) ? ($ldf->LearnDelFAMCode == '' ? '&nbsp' : ($ldf->LearnDelFAMCode == 'undefined' ? '&nbsp;' : $ldf->LearnDelFAMCode)) : '&nbsp') . '</td>';
                                    break;
                                }
                            }
                            if ($ind == 0) {
                                echo '<td>' . '&nbsp' . '</td>';
                            }
                        } elseif ($column == "Exclude") {
                            if ($delivery->Exclude == "1") {
                                echo '<td>Yes</td>';
                            } else {
                                echo '<td>No</td>';
                            }
                        }

                    }
                    foreach ($columns as $column) {
                        $column = str_replace(" ", "", (ucwords(str_replace('_', ' ', $column))));
                        if (in_array($column, ['LearnStartDate', 'LearnPlanEndDate', 'LearnActEndDate', 'AchDate'])) {
                            echo '<td>' . Date::toShort("" . $delivery->$column) . '</td>';
                        } elseif (!in_array($column, $learner) && !in_array($column, ['Employment', 'LSF', 'TNP', 'LDM', 'HHS', 'SOF', 'FFI', 'LSR', 'NLM', 'PlanLearnHours', 'EmpOutcome', 'ZWRKX001Hours', 'ProvSpecDelMonA', 'PlanEEPHours', 'MGA', 'FME', 'EGA', 'Title', 'Contact', 'RegisteredBy', 'CourseTitle', 'ContractTitle', 'QualificationTitle', 'Employer', 'IsActive', 'GroupTitle', 'AgeAtEnrolment', 'LLDDCat'])) {
                            echo '<td>' . ((isset($delivery->$column)) ? (($delivery->$column == '') ? '&nbsp' : (($delivery->$column == 'undefined') ? '&nbsp' : $delivery->$column)) : '&nbsp') . '</td>';
                        }
                        if ($column == 'CourseTitle') {
                            echo '<td>' . $row['course_title'] . '</td>';
                        } elseif ($column == 'ContractTitle') {
                            echo '<td>' . $row['contract_title'] . '</td>';
                        }
                        if ($column == 'QualificationTitle') {
                            echo '<td>' . $row['qualification_title'] . '</td>';
                        }
                        if ($column == 'GroupTitle') {
                            echo '<td>' . $row['group_title'] . '</td>';
                        }
                        if ($column == 'AgeAtEnrolment') {
                            echo '<td>' . $row['age_at_enrolment'] . '</td>';
                        }
                        if ($column == 'Employer') {
                            echo '<td>' . $row['employer'] . '</td>';
                        }
                        if ($column == 'IsActive') {
                            echo $row['is_active'] == 1 ? '<td>Yes</td>' : '<td>No</td>';
                        }
                        if ($column == 'MGA') {
                            $xpath = $ilr->xpath('/Learner/LearnerFAM[LearnFAMType=\'MGA\']/LearnFAMCode');
                            $mga = (empty($xpath)) ? '' : (string)$xpath[0];
                            echo '<td>' . ((isset($mga)) ? (($mga == '') ? '&nbsp' : (($mga == 'undefined') ? '&nbsp;' : $mga)) : '&nbsp') . '</td>';
                        }
                        if ($column == 'FME') {
                            $xpath = $ilr->xpath('/Learner/LearnerFAM[LearnFAMType=\'FME\']/LearnFAMCode');
                            $fme = (empty($xpath)) ? '' : (string)$xpath[0];
                            echo '<td>' . ((isset($fme)) ? (($fme == '') ? '&nbsp' : (($fme == 'undefined') ? '&nbsp;' : $fme)) : '&nbsp') . '</td>';
                        }
                        if ($column == 'EGA') {
                            $xpath = $ilr->xpath('/Learner/LearnerFAM[LearnFAMType=\'EGA\']/LearnFAMCode');
                            $ega = (empty($xpath)) ? '' : (string)$xpath[0];
                            echo '<td>' . ((isset($ega)) ? (($ega == '') ? '&nbsp' : (($ega == 'undefined') ? '&nbsp;' : $ega)) : '&nbsp') . '</td>';
                        }
                        /*if($column=='PartnerUKPRN')
                        {
                            $xpath = $ilr->xpath('/Learner/PartnerUKPRN'); $ega = (empty($xpath))?'':(string)$xpath[0];
                            echo '<td>' . ((isset($ega))?(($ega=='')?'&nbsp':($ega=='undefined')?'&nbsp;':$ega):'&nbsp') . '</td>';
                        }*/
                        if ($column == 'LSR') {
                            $xpath = $ilr->xpath('/Learner/LearnerFAM[LearnFAMType=\'LSR\']/LearnFAMCode');
                            $lsr = (empty($xpath)) ? '' : (string)$xpath[0];
                            echo '<td>' . ((isset($lsr)) ? (($lsr == '') ? '&nbsp' : (($lsr == 'undefined') ? '&nbsp;' : $lsr)) : '&nbsp') . '</td>';
                        }
                        if ($column == 'NLM') {
                            $xpath = $ilr->xpath('/Learner/LearnerFAM[LearnFAMType=\'NLM\']/LearnFAMCode');
                            $nlm = (empty($xpath)) ? '' : (string)$xpath[0];
                            echo '<td>' . ((isset($nlm)) ? (($nlm == '') ? '&nbsp' : (($nlm == 'undefined') ? '&nbsp;' : $nlm)) : '&nbsp') . '</td>';
                        }
                        if ($column == 'PlanLearnHours') {
                            $xpath = $ilr->xpath('/Learner/PlanLearnHours');
                            $pl_hours = (empty($xpath)) ? '' : (string)$xpath[0];
                            echo '<td>' . ((isset($pl_hours)) ? (($pl_hours == '') ? '&nbsp' : (($pl_hours == 'undefined') ? '&nbsp;' : $pl_hours)) : '&nbsp') . '</td>';
                        }
                        if ($column == 'PlanEEPHours') {
                            $xpath = $ilr->xpath('/Learner/PlanEEPHours');
                            $pl_eep_hours = (empty($xpath)) ? '' : (string)$xpath[0];
                            echo '<td>' . ((isset($pl_eep_hours)) ? (($pl_eep_hours == '') ? '&nbsp' : (($pl_eep_hours == 'undefined') ? '&nbsp;' : $pl_eep_hours)) : '&nbsp') . '</td>';
                        }
                        if ($column == 'ProvSpecDelMonA') {
                            $xpath = $delivery->xpath('ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur=\'A\']/ProvSpecDelMon');
                            $prov_spec_learn_mon = (empty($xpath)) ? '' : (string)$xpath[0];
                            echo '<td>' . ((isset($prov_spec_learn_mon)) ? (($prov_spec_learn_mon == '') ? '&nbsp' : (($prov_spec_learn_mon == 'undefined') ? '&nbsp;' : $prov_spec_learn_mon)) : '&nbsp') . '</td>';
                        }
                        if ($column == 'EmpOutcome') {
                            $anyEmpOutcome = false;
                            foreach ($delivery->EmpOutcome as $emp_outcome) {
                                $anyEmpOutcome = true;
                                echo '<td>' . ((isset($emp_outcome)) ? (($emp_outcome == '') ? '&nbsp' : (($emp_outcome == 'undefined') ? '&nbsp;' : $emp_outcome)) : '&nbsp') . '</td>';
                            }
                            if (!$anyEmpOutcome) {
                                echo '<td>&nbsp;</td>';
                            }
                        }
                        if ($column == 'LSF') {
                            $ind = 0;
                            foreach ($delivery->LearningDeliveryFAM as $ldf) {
                                if ($ldf->LearnDelFAMType == 'LSF' && isset($ldf->LearnDelFAMCode) && ($ldf->LearnDelFAMCode == '1')) {
                                    $ind++;
                                    echo '<td>' . ((isset($ldf->LearnDelFAMCode)) ? (($ldf->LearnDelFAMCode == '') ? '&nbsp' : (($ldf->LearnDelFAMCode == 'undefined') ? '&nbsp;' : $ldf->LearnDelFAMCode)) : '&nbsp') . '</td>';
                                    echo '<td>' . ((isset($ldf->LearnDelFAMDateFrom)) ? (($ldf->LearnDelFAMDateFrom == '') ? '&nbsp' : (($ldf->LearnDelFAMDateFrom == 'undefined') ? '&nbsp;' : Date::toShort($ldf->LearnDelFAMDateFrom))) : '&nbsp') . '</td>';
                                    echo '<td>' . ((isset($ldf->LearnDelFAMDateTo)) ? (($ldf->LearnDelFAMDateTo == '') ? '&nbsp' : (($ldf->LearnDelFAMDateTo == 'undefined') ? '&nbsp;' : Date::toShort($ldf->LearnDelFAMDateTo))) : '&nbsp') . '</td>';
                                    break;
                                }
                            }
                            if ($ind == 0) {
                                echo '<td>' . '&nbsp' . '</td>';
                                echo '<td>' . '&nbsp' . '</td>';
                                echo '<td>' . '&nbsp' . '</td>';
                            }
                        }
                        if ($column == 'SOF') {
                            $ind = 0;
                            foreach ($delivery->LearningDeliveryFAM as $ldf) {
                                if ($ldf->LearnDelFAMType == 'SOF') {
                                    $ind++;
                                    echo '<td>' . $ldf->LearnDelFAMCode . '</td>';
                                    break;
                                }
                            }
                            if ($ind == 0) {
                                echo '<td>' . '&nbsp' . '</td>';
                            }
                        }
                        if ($column == 'RES') {
                            $ind = 0;
                            foreach ($delivery->LearningDeliveryFAM as $ldf) {
                                if ($ldf->LearnDelFAMType == 'RES') {
                                    $ind++;
                                    echo '<td>' . $ldf->LearnDelFAMCode . '</td>';
                                    break;
                                }
                            }
                            if ($ind == 0) {
                                echo '<td>' . '&nbsp' . '</td>';
                            }
                        }
                        if ($column == 'TNP') {
                            $ind = 0;
                            foreach ($delivery->TrailblazerApprenticeshipFinancialRecord as $TrailblazerApprenticeshipFinancialRecord) {
                                if ($TrailblazerApprenticeshipFinancialRecord->TBFinType->__toString() == 'TNP') {
                                    if ($ind > 1) {
                                        break;
                                    }

                                    $ind++;
                                    echo '<td align="center">' . Date::toShort($TrailblazerApprenticeshipFinancialRecord->TBFinDate->__toString()) . '</td>';
                                    echo '<td align="center">' . $TrailblazerApprenticeshipFinancialRecord->TBFinAmount->__toString() . '</td>';
                                }
                            }
                            if ($ind == 0) {
                                echo '<td></td><td></td><td></td><td></td>';
                            }
                            if ($ind == 1) {
                                echo '<td></td><td></td>';
                            }
                        }
                        if ($column == 'ZWRKX001Hours') {
                            echo '<td>' . $row['ZWRKX001_Hours'] . '</td>';
                        }
                    }

                }

                echo '</tr>';
            }

            echo '</tbody></table></div>';
            //echo $this->getViewNavigator();


        } else {
            throw new DatabaseException($link, $this->getSQL());
        }

    }
}

?>