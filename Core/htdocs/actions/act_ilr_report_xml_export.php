<?php

class ilr_report_xml_export implements IAction
{
    public function execute(PDO $link)
    {

        $contract_id = isset($_REQUEST['contract_id']) ? $_REQUEST['contract_id'] : '';
        $submission = isset($_REQUEST['submission']) ? $_REQUEST['submission'] : '';
        $assessor = isset($_REQUEST['assessor']) ? $_REQUEST['assessor'] : '';
        $employer = isset($_REQUEST['employer']) ? $_REQUEST['employer'] : '';
        $course = isset($_REQUEST['course']) ? $_REQUEST['course'] : '';
        $provider = isset($_REQUEST['provider']) ? $_REQUEST['provider'] : '';
        $active = isset($_REQUEST['active']) ? $_REQUEST['active'] : '';
        $valid = isset($_REQUEST['valid']) ? $_REQUEST['valid'] : '';
        $lsf = isset($_REQUEST['lsf']) ? $_REQUEST['lsf'] : '';
        $zprog = isset($_REQUEST['zprog']) ? $_REQUEST['zprog'] : '';

//pre($contract_id . ', ' . $submission . ', ' . $assessor . ', ' . $employer . ', ' . $course . ', ' . $provider . ', ' . $active . ', ' . $valid);
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

        $columns = isset($_REQUEST['columns']) ? $_REQUEST['columns'] : '';

        $columns = explode(",", $columns);

        $filename = "ilr_report";

        header("Content-Type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename="' . $filename . '.CSV"');

// Internet Explorer requires two extra headers when downloading files over HTTPS
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) {
            header('Pragma: public');
            header('Cache-Control: max-age=0');
        }

        $sql = <<<HEREDOC
SELECT
	ilr.*, contracts.*, tr.*, CONCAT(advisors.firstnames,' ',advisors.surname) as registered_by, REPLACE(courses.title,',','') AS course_title, contracts.title AS contract_title,
	(SELECT legal_name FROM organisations WHERE organisations.id = tr.`employer_id`) AS employer,
	(SELECT title FROM groups INNER JOIN group_members on groups.id = group_members.groups_id WHERE group_members.tr_id = tr.`id`) AS group_title,
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

        $st = $link->query($sql);

        if ($st) {
            $employment_status = array(
                '10' => '10 In paid employment',
                '11' => '11 Not in paid employment and looking for work',
                '12' => '12 Not in paid employment and not looking for work',
                '98' => '98 Not known/ Not provided'
            );
            $LOU_dropdown = array('1' => '1. 0-6 months', '2' => '2. 6-11 months', '3' => '3. 12-23 months', '4' => '4. 24-35 months', '5' => '5. over 36 months');
            //echo $this->getViewNavigator();
            foreach ($columns as $column) {
                if ($column == 'LDM') {
                    echo 'LDM,LDM,LDM,';
                } elseif ($column == 'HHS') {
                    echo 'HHS,HHS,';
                } elseif ($column == 'Contact') {
                    echo 'AddLine1,AddLine2,AddLine3,AddLine4,PostCode,Email,TelNumber,';
                } elseif ($column == 'Employment') {
                    echo 'EmpStat,Date,EmpId,LOU,BSI,EII,LOE,SEM,EmpStat,Date,EmpId,LOU,BSI,EII,LOE,SEM,EmpStat,Date,EmpId,LOU,BSI,EII,LOE,SEM,';
                } elseif ($column == 'LSF') {
                    echo 'LSF,LSFFrom,LSFTo,';
                } elseif ($column == 'TNP') {
                    echo 'TNP - Date (1),TNP - Amount (1),TNP - Date (2),TNP - Amount (2),';
                } else {
                    echo ucwords(str_replace("_", " ", str_replace("_and_", " & ", $column))) . ',';
                }
            }

            $learner = array('LearnRefNumber', 'ULN', 'FamilyName', 'GivenNames', 'DateOfBirth', 'Ethnicity', 'Sex', 'LLDDHealthProb', 'NINumber', 'Domicile', 'PriorAttain', 'Accom', 'ALSCost', 'DisUpFact', 'Dest', 'EngGrade', 'MathGrade', 'LLDDCat');
            echo "\n";
            while ($row = $st->fetch()) {
                $ilr = Ilr2016::loadFromXML($row['ilr']);
                foreach ($ilr->LearningDelivery as $delivery) {
                    foreach ($columns as $column) {
                        $row['qualification_title'] = DAO::getSingleValue($link, 'SELECT REPLACE(title,",","") as title FROM student_qualifications WHERE student_qualifications.tr_id = "' . $row['tr_id'] . '" AND REPLACE(student_qualifications.id, "/", "") = "' . $delivery->LearnAimRef . '"');
                        $column = str_replace(" ", "", (ucwords(str_replace('_', ' ', $column))));
                        if ($column == 'RegisteredBy') {
                            echo $row['registered_by'] . ",";
                        }
                        if ($column == 'Contract Title') {
                            echo $row['contract_title'] . ",";
                        }


                        if (in_array($column, $learner)) {
                            if ($column == 'DateOfBirth') {
                                echo '' . ((isset($ilr->$column)) ? (($ilr->$column == '') ? '' : Date::toShort($ilr->$column)) : '') . ',';
                            } elseif ($column == 'Ethnicity' && isset($ilr->$column) && $ilr->$column->__toString() != '') {
                                $ethnicity = DAO::getSingleValue($link, "SELECT CONCAT(Ethnicity_Code, ' ', Ethnicity_Desc) FROM lis201112.ilr_l12_ethnicity WHERE Ethnicity_Code = '" . $ilr->$column . "'ORDER BY Ethnicity_Code;");
                                echo '' . $ethnicity . ',';
                            } elseif ($column == 'PriorAttain') {
                                $xpath = $ilr->xpath('/Learner/PriorAttain/PriorLevel');
                                $priorAttain = (empty($xpath)) ? '' : (string)$xpath[0];
                                echo $priorAttain == '' ?
                                    $priorAttain :
                                    DAO::getSingleValue($link, "SELECT DISTINCT CONCAT(PriorAttain, ' ', PriorAttainDesc) FROM lis201415.ilr_priorattain2 WHERE PriorAttain = '{$priorAttain}';");
                                echo '' . str_replace(",", " ", $priorAttain) . ',';
                            } elseif ($column == 'LLDDCat')// && SOURCE_BLYTHE_VALLEY)
                            {
                                $xpath = $ilr->xpath('/Learner/LLDDandHealthProblem[PrimaryLLDD=\'1\']/LLDDCat');
                                $primary_lldd = (empty($xpath)) ? '' : (string)$xpath[0];
                                //$primary_lldd = DAO::getSingleValue($link, "SELECT CONCAT(code, ' ', description) FROM central.lookup_lldd_cat WHERE CODE IN ('$primary_lldd')");
                                echo '' . $primary_lldd . ',';
                            } else {
                                echo ((isset($ilr->$column)) ? (($ilr->$column == '') ? '' : trim($ilr->$column)) : '') . ',';
                            }


                        }

                        if ($column == 'LDM') {
                            $ldm = 0;
                            $counter = 0;
                            foreach ($delivery->LearningDeliveryFAM as $ldf) {
                                if ($ldf->LearnDelFAMType == 'LDM') {
                                    $counter++;
                                    if ($counter > 3) {
                                        break;
                                    }

                                    $ldm++;
                                    echo '' . ((isset($ldf->LearnDelFAMCode)) ? (($ldf->LearnDelFAMCode == '') ? '' : (($ldf->LearnDelFAMCode == 'undefined') ? '' : $ldf->LearnDelFAMCode)) : '') . ',';
                                }
                            }
                            for ($ldm++; $ldm <= 3; $ldm++) {
                                echo ',';
                            }
                        }
                        if ($column == 'HHS') {
                            $ldm = 0;
                            $counter = 0;
                            foreach ($delivery->LearningDeliveryFAM as $ldf) {
                                if ($ldf->LearnDelFAMType == 'HHS') {
                                    $counter++;
                                    if ($counter > 2) {
                                        break;
                                    }

                                    $ldm++;
                                    echo '' . ((isset($ldf->LearnDelFAMCode)) ? (($ldf->LearnDelFAMCode == '') ? '' : (($ldf->LearnDelFAMCode == 'undefined') ? '' : $ldf->LearnDelFAMCode)) : '') . ',';
                                }
                            }
                            for ($ldm++; $ldm <= 2; $ldm++) {
                                echo ',';
                            }
                        }
                        if ($column == 'Contact') {
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
                            $add1 = str_replace(",", "", $add1);
                            $add2 = str_replace(",", "", $add2);
                            $add3 = str_replace(",", "", $add3);
                            $add4 = str_replace(",", "", $add4);
                            $email = str_replace(",", "", $email);
                            echo '' . ((isset($add1)) ? (($add1 == '') ? '' : (($add1 == 'undefined') ? '' : $add1)) : '') . ',';
                            echo '' . ((isset($add2)) ? (($add2 == '') ? '' : (($add2 == 'undefined') ? '' : $add2)) : '') . ',';
                            echo '' . ((isset($add3)) ? (($add3 == '') ? '' : (($add3 == 'undefined') ? '' : $add3)) : '') . ',';
                            echo '' . ((isset($add4)) ? (($add4 == '') ? '' : (($add4 == 'undefined') ? '' : $add4)) : '') . ',';
                            echo '' . ((isset($cp)) ? (($cp == '') ? '' : (($cp == 'undefined') ? '' : $cp)) : '') . ',';
                            echo '' . ((isset($email)) ? (($email == '') ? '' : (($email == 'undefined') ? '' : $email)) : '') . ',';
                            echo '' . ((isset($tel)) ? (($tel == '') ? '' : (($tel == 'undefined') ? '' : $tel)) : '') . ',';
                        }

                        if ($column == 'Employment') {
                            $ldm = 0;
                            $counter = 0;
                            foreach ($ilr->LearnerEmploymentStatus as $les) {
                                $counter++;
                                if ($counter > 3) {
                                    break;
                                }
                                $ldm++;
                                if (isset($employment_status[$les->EmpStat->__toString()])) {
                                    echo '' . ((isset($les->EmpStat)) ? (($les->EmpStat == '') ? '' : (($les->EmpStat == 'undefined') ? '' : $employment_status[$les->EmpStat->__toString()])) : '') . ',';
                                } else {
                                    echo ',';
                                }
                                echo '' . ((isset($les->DateEmpStatApp)) ? (($les->DateEmpStatApp == '') ? '' : (($les->DateEmpStatApp == 'undefined') ? '' : Date::toShort($les->DateEmpStatApp))) : '') . ',';
                                echo '' . ((isset($les->EmpId)) ? (($les->EmpId == '') ? '' : (($les->EmpId == 'undefined') ? '' : $les->EmpId)) : '') . ',';
                                $loufound = false;
                                foreach ($les->EmploymentStatusMonitoring as $esm) {
                                    if ($esm->ESMType == 'LOU') {
                                        $loufound = true;
                                        echo '' . ((isset($esm->ESMCode)) ? (($esm->ESMCode == '') ? '' : (($esm->ESMCode == 'undefined') ? '' : $LOU_dropdown[$esm->ESMCode->__toString()])) : '') . ',';
                                    }
                                }
                                if (!$loufound) {
                                    echo ',';
                                }

                                $bsifound = false;
                                foreach ($les->EmploymentStatusMonitoring as $esm) {
                                    if ($esm->ESMType == 'BSI') {
                                        $bsifound = true;
                                        echo '' . ((isset($esm->ESMCode)) ? (($esm->ESMCode == '') ? '' : (($esm->ESMCode == 'undefined') ? '' : $esm->ESMCode)) : '') . ',';
                                    }
                                }
                                if (!$bsifound) {
                                    echo ',';
                                }

                                $eiifound = false;
                                foreach ($les->EmploymentStatusMonitoring as $esm) {
                                    if ($esm->ESMType == 'EII') {
                                        $eiifound = true;
                                        echo '' . ((isset($esm->ESMCode)) ? (($esm->ESMCode == '') ? '' : (($esm->ESMCode == 'undefined') ? '' : $esm->ESMCode)) : '') . ',';
                                    }
                                }
                                if (!$eiifound) {
                                    echo ',';
                                }

                                $loefound = false;
                                foreach ($les->EmploymentStatusMonitoring as $esm) {
                                    if ($esm->ESMType == 'LOE') {
                                        $loefound = true;
                                        echo '' . ((isset($esm->ESMCode)) ? (($esm->ESMCode == '') ? '' : (($esm->ESMCode == 'undefined') ? '' : $esm->ESMCode)) : '') . ',';
                                    }
                                }
                                if (!$loefound) {
                                    echo ',';
                                }

                                $semfound = false;
                                foreach ($les->EmploymentStatusMonitoring as $esm) {
                                    if ($esm->ESMType == 'SEM') {
                                        $semfound = true;
                                        echo isset($esm->ESMCode) ? $esm->ESMCode . ',' : ',';
                                    }
                                }
                                if (!$semfound) {
                                    echo ',';
                                }
                            }
                            for ($ldm++; $ldm <= 3; $ldm++) {
                                echo ',';
                                echo ',';
                                echo ',';
                                echo ',';
                                echo ',';
                                echo ',';
                                echo ',';
                                echo ',';
                            }
                        }

                        if ($column == 'FFI') {
                            $ind = 0;
                            foreach ($delivery->LearningDeliveryFAM as $ldf) {
                                if ($ldf->LearnDelFAMType == 'FFI') {
                                    $ind++;
                                    echo '' . ((isset($ldf->LearnDelFAMCode)) ? (($ldf->LearnDelFAMCode == '') ? '' : (($ldf->LearnDelFAMCode == 'undefined') ? '' : $ldf->LearnDelFAMCode)) : '') . ',';
                                    break;
                                }
                            }
                            if ($ind == 0) {
                                echo ',';
                            }
                        }
                        if ($column == 'EEF') {
                            $ind = 0;
                            foreach ($delivery->LearningDeliveryFAM as $ldf) {
                                if ($ldf->LearnDelFAMType == 'EEF') {
                                    $ind++;
                                    echo '' . ((isset($ldf->LearnDelFAMCode)) ? (($ldf->LearnDelFAMCode == '') ? '' : (($ldf->LearnDelFAMCode == 'undefined') ? '' : $ldf->LearnDelFAMCode)) : '') . ',';
                                    break;
                                }
                            }
                            if ($ind == 0) {
                                echo ',';
                            }
                        }
                    }
                    foreach ($columns as $column) {
                        $column = str_replace(" ", "", (ucwords(str_replace('_', ' ', $column))));
                        if (in_array($column, ['LearnStartDate', 'LearnPlanEndDate', 'LearnActEndDate', 'AchDate'])) {
                            echo '' . Date::toShort($delivery->$column) . ',';
                        } elseif (!in_array($column, $learner) && !in_array($column, ['Employment', 'LSF', 'TNP', 'LDM', 'HHS', 'SOF', 'FFI', 'LSR', 'PlanLearnHours', 'EmpOutcome', 'ZWRKX001Hours', 'ProvSpecDelMonA', 'PlanEEPHours', 'MGA', 'FME', 'EGA', 'Title', 'Contact', 'RegisteredBy', 'CourseTitle', 'ContractTitle', 'QualificationTitle', 'Employer', 'IsActive', 'GroupTitle', 'AgeAtEnrolment', 'LLDDCat'])) {
                            echo '' . ((isset($delivery->$column)) ? (($delivery->$column == '') ? '' : (($delivery->$column == 'undefined') ? '' : $delivery->$column)) : '') . ',';
                        }
                        if ($column == 'CourseTitle') {
                            echo '' . $row['course_title'] . ',';
                        }
                        // if($column=='PartnerUKPRN')
                        // {
                        //     echo '' . $delivery->PartnerUKPRN .  ',';
                        // }
                        if ($column == 'DelLocPostCode') {
                            echo '' . strtoupper($delivery->DelLocPostCode) . ',';
                        }
                        if ($column == 'ContractTitle') {
                            echo '' . $row['contract_title'] . ',';
                        }
                        if ($column == 'QualificationTitle') {
                            echo '' . $row['qualification_title'] . ',';
                        }
                        if ($column == 'GroupTitle') {
                            echo '' . $row['group_title'] . ',';
                        }
                        if ($column == 'AgeAtEnrolment') {
                            echo '' . $row['age_at_enrolment'] . ',';
                        }
                        if ($column == 'Employer') {
                            echo '' . $row['employer'] . ',';
                        }
                        if ($column == 'IsActive') {
                            echo $row['is_active'] == 1 ? 'Yes,' : 'No,';
                        }
                        if ($column == 'MGA') {
                            $xpath = $ilr->xpath('/Learner/LearnerFAM[LearnFAMType=\'MGA\']/LearnFAMCode');
                            $mga = (empty($xpath)) ? '' : (string)$xpath[0];
                            echo '' . ((isset($mga)) ? (($mga == '') ? '' : (($mga == 'undefined') ? '' : $mga)) : '') . ',';
                        }
                        if ($column == 'FME') {
                            $xpath = $ilr->xpath('/Learner/LearnerFAM[LearnFAMType=\'FME\']/LearnFAMCode');
                            $fme = (empty($xpath)) ? '' : (string)$xpath[0];
                            echo '' . ((isset($fme)) ? (($fme == '') ? '' : (($fme == 'undefined') ? '' : $fme)) : '') . ',';
                        }
                        if ($column == 'EGA') {
                            $xpath = $ilr->xpath('/Learner/LearnerFAM[LearnFAMType=\'EGA\']/LearnFAMCode');
                            $ega = (empty($xpath)) ? '' : (string)$xpath[0];
                            echo '' . ((isset($ega)) ? (($ega == '') ? '' : (($ega == 'undefined') ? '' : $ega)) : '') . ',';
                        }
                        if ($column == 'LSR') {
                            $xpath = $ilr->xpath('/Learner/LearnerFAM[LearnFAMType=\'LSR\']/LearnFAMCode');
                            $lsr = (empty($xpath)) ? '' : (string)$xpath[0];
                            echo '' . ((isset($lsr)) ? (($lsr == '') ? '' : (($lsr == 'undefined') ? ';' : $lsr)) : '') . ',';
                        }
                        if ($column == 'PlanLearnHours') {
                            $xpath = $ilr->xpath('/Learner/PlanLearnHours');
                            $pl_hours = (empty($xpath)) ? '' : (string)$xpath[0];
                            echo '' . ((isset($pl_hours)) ? (($pl_hours == '') ? '' : (($pl_hours == 'undefined') ? ';' : $pl_hours)) : '') . ',';
                        }
                        if ($column == 'PlanEEPHours') {
                            $xpath = $ilr->xpath('/Learner/PlanEEPHours');
                            $pl_eep_hours = (empty($xpath)) ? '' : (string)$xpath[0];
                            echo '' . ((isset($pl_eep_hours)) ? (($pl_eep_hours == '') ? '' : (($pl_eep_hours == 'undefined') ? ';' : $pl_eep_hours)) : '') . ',';
                        }
                        if ($column == 'ProvSpecDelMonA') {
                            $xpath = $delivery->xpath('ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur=\'A\']/ProvSpecDelMon');
                            $prov_spec_learn_mon = (empty($xpath)) ? '' : (string)$xpath[0];
                            echo '' . ((isset($prov_spec_learn_mon)) ? (($prov_spec_learn_mon == '') ? '' : (($prov_spec_learn_mon == 'undefined') ? ';' : $prov_spec_learn_mon)) : '') . ',';
                        }
                        if ($column == 'EmpOutcome') {
                            $anyEmpOutcome = false;
                            foreach ($delivery->EmpOutcome as $emp_outcome) {
                                $anyEmpOutcome = true;
                                echo '' . ((isset($emp_outcome)) ? (($emp_outcome == '') ? '' : (($emp_outcome == 'undefined') ? '' : $emp_outcome)) : '') . ',';
                            }
                            if (!$anyEmpOutcome) {
                                echo ',';
                            }
                        }
                        if ($column == 'HouseholdSituation') {
                            $hhs_info = "";
                            foreach ($delivery->LearningDeliveryFAM as $hhs) {
                                if ($hhs->LearnDelFAMType == 'HHS' && isset($hhs->LearnDelFAMCode) && ($hhs->LearnDelFAMCode != '')) {
                                    $hhs_info .= '' . ((isset($hhs->LearnDelFAMCode)) ? (($hhs->LearnDelFAMCode == '') ? '&nbsp' : (($hhs->LearnDelFAMCode == 'undefined') ? '&nbsp;' : $hhs->LearnDelFAMCode)) : '&nbsp') . ';';
                                }
                            }
                            echo '' . rtrim($hhs_info, ';') . ',';
                        }
                        if ($column == 'LSF') {
                            $ind = 0;
                            foreach ($delivery->LearningDeliveryFAM as $ldf) {
                                if ($ldf->LearnDelFAMType == 'LSF' && isset($ldf->LearnDelFAMCode) && ($ldf->LearnDelFAMCode == '1')) {
                                    $ind++;
                                    echo '' . ((isset($ldf->LearnDelFAMCode)) ? (($ldf->LearnDelFAMCode == '') ? '' : (($ldf->LearnDelFAMCode == 'undefined') ? ';' : $ldf->LearnDelFAMCode)) : '') . ',';
                                    echo '' . ((isset($ldf->LearnDelFAMDateFrom)) ? (($ldf->LearnDelFAMDateFrom == '') ? '' : (($ldf->LearnDelFAMDateFrom == 'undefined') ? ';' : Date::toShort($ldf->LearnDelFAMDateFrom))) : '') . ',';
                                    echo '' . ((isset($ldf->LearnDelFAMDateTo)) ? (($ldf->LearnDelFAMDateTo == '') ? '' : (($ldf->LearnDelFAMDateTo == 'undefined') ? ';' : Date::toShort($ldf->LearnDelFAMDateTo))) : '') . ',';
                                    break;
                                }
                            }
                            if ($ind == 0) {
                                echo ',,,';
                            }
                        }
                        if ($column == 'SOF') {
                            $ind = 0;
                            foreach ($delivery->LearningDeliveryFAM as $ldf) {
                                if ($ldf->LearnDelFAMType == 'SOF') {
                                    $ind++;
                                    echo '' . $ldf->LearnDelFAMCode . ',';
                                    break;
                                }
                            }
                            if ($ind == 0) {
                                echo '' . '&nbsp' . ',';
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
                                    echo Date::toShort($TrailblazerApprenticeshipFinancialRecord->TBFinDate->__toString()) . ',';
                                    echo $TrailblazerApprenticeshipFinancialRecord->TBFinAmount->__toString() . ',';
                                }
                            }
                            if ($ind == 0) {
                                echo ',,,,';
                            }
                            if ($ind == 1) {
                                echo ',,';
                            }
                        }

                        if ($column == 'ZWRKX001Hours') {
                            echo $row['ZWRKX001_Hours'] . ',';
                        }
                    }

                    echo "\n";
                }
            }

            //echo $this->getViewNavigator();


        } else {
            throw new DatabaseException($link, $this->getSQL());
        }

    }
}

?>