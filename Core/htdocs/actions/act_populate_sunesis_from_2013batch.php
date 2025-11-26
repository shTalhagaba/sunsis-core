<?php
class populate_sunesis_from_2013batch implements IAction
{
    public function execute(PDO $link)
    {
        $filename = $_FILES['uploadedfile']['tmp_name'];
        $content = file_get_contents($filename);
        $ptype = "Apps";
        $southampton = "";
        $contract_id = DAO::getSingleValue($link, "select id from contracts where contract_year = 2013 order by id limit 0,1");
        $submission = DAO::getSingleValue($link, "SELECT submission FROM central.`lookup_submission_dates` WHERE start_submission_date <= CURDATE() AND last_submission_date >= CURDATE() AND contract_year = 2013 AND contract_type = 2;");
        if($submission=='')
            $submission="W13";
        $provider_id = DAO::getSingleValue($link, "SELECT id FROM organisations WHERE organisation_type = 3 AND ukprn IN (SELECT ukprn FROM organisations WHERE organisation_type = 1);");
        if($provider_id=='')
            pre("where is provider");

        $qualifications = Array();
        $qualificationsDownload = Array();
        $ER_Other_Frameworks = Array();
        $Apps_Frameworks = Array();
        $tr_id = DAO::getSingleValue($link, "select max(tr_id) from ilr");
        if($tr_id=='')
            $tr_id = 0;
        $index = 0;
        $content = str_replace("</Message>","",$content);
        $learners = explode("<Learner>",$content);

        $learners2 = Array();
        // Clean Non 45 learners
        for($a=0; $a<sizeof($learners); $a++)
        {
            $FundModel = false;
            $learner = str_replace("'","\'",$learners[$a]);
            $learner = str_replace(array("\r\n", "\r"), "\n", $learner);
            $learner = "<Learner>".$learner;
            if($a>0)
            {
                $ilr = Ilr2012::loadFromXML($learner);
                $l03s[] = "".$ilr->LearnRefNumber;
                foreach($ilr->LearningDelivery as $delivery)
                {
                    if(("".$delivery->FundModel)=="35")
                        $FundModel = true;
                }
                if($FundModel)
                    $learners2[] = $learners[$a];
            }
        }

        unset($learners);
        $learners = $learners2;
        DAO::execute($link, "delete from disc where batch = 2013");
        // Check Qualifications to be downloaded and framewokrs to be built
        foreach($learners as $learner)
        {
            $index++;
            $learner = str_replace("'","\'",$learner);
            $learner = str_replace(array("\r\n", "\r"), "\n", $learner);
            $learner = "<Learner>".$learner;
            if($index>=1)
            {
                $ilr = Ilr2013::loadFromXML($learner);
                foreach($ilr->LearningDelivery as $delivery)
                {
                    // Shove into Disc
                    $batch = 2013;
                    $FundModel = $delivery->FundModel;
                    $LearnRefNumber = $ilr->LearnRefNumber;
                    $LearnAimRef = $delivery->LearnAimRef;
                    $LearnStartDate = Date::toMySQL($delivery->LearnStartDate);
                    $LearnPlanEndDate = Date::toMySQL($delivery->LearnPlanEndDate);
                    if($delivery->LearnActEndDate=='')
                        $LearnActEndDate = "NULL";
                    else
                        $LearnActEndDate = "'" . Date::toMySQL($delivery->LearnActEndDate) . "'";
                    if($delivery->AchDate=='')
                        $AchDate = "NULL";
                    else
                        $AchDate = "'" . Date::toMySQL($delivery->AchDate) . "'";

                    $GivenNames = addslashes((string)$ilr->GivenNames);
                    $FamilyName = addslashes((string)$ilr->FamilyName);
                    foreach($ilr->LearnerEmploymentStatus as $empstatusemp)
                    {
                        $EmpId = $empstatusemp->EmpId;
                    }
                    DAO::execute($link, "insert into disc(Batch,FundModel,NLearnRefNumber,NLearnAimRef,NLearnStartDate,NLearnPlanEndDate,NLearnActEndDate,NAchDate,GivenNames,FamilyName,EmpId) values ('2013','$FundModel','$LearnRefNumber','$LearnAimRef','$LearnStartDate','$LearnPlanEndDate',$LearnActEndDate,$AchDate,'$GivenNames','$FamilyName','$EmpId')");

                    $qid = "" . $delivery->LearnAimRef;

                    if($ptype=="Other")
                        if(("".$delivery->AimType)=='4' && ("".$delivery->FundModel)=='35')
                            if(!in_array($qid,$ER_Other_Frameworks))
                                $ER_Other_Frameworks[] = "" . $qid;

                    if($ptype=="Apps")
                        if(("".$delivery->AimType)!='4' && ("".$delivery->FundModel)=='35' && ("".$delivery->CompStatus)!='6')
                            if(!in_array("" . $delivery->FworkCode . "," . $delivery->ProgType . ","  . $delivery->LearnAimRef,$Apps_Frameworks))
                                $Apps_Frameworks[] = "" . $delivery->FworkCode . "," . $delivery->ProgType . ","  . $delivery->LearnAimRef;

                    if($ptype=="Apps")
                        if(("".$delivery->AimType)!='4' && ("".$delivery->FundModel)=='35')
                            if(!in_array($qid,$qualifications))
                            {
                                if($qid!='ZPROG001' && $qid!='XESF0001' && $qid!='ZESF0001' && $qid!='ZVOC0007' && $qid!='ZVOC0015' && $qid!='ZVOC0004' && $qid!='ZVOC0006' && $qid!='ZVOC0005' && $qid!='ZVOC0009' && $qid!='ZVOC0014' && $qid!='' && $qid!='Z9OP205A' && $qid!='CMISC001' && $qid!='ZUQAH15A' && $qid!='ZFLW0001')
                                {
                                    $qualifications[] = $qid;
                                    $found = DAO::getSingleValue($link, "select count(*) from qualifications where replace(id,'/','') = '$qid'");
                                    if(!$found)
                                        $qualificationsDownload[] = $qid;
                                }
                            }

                    if($ptype=="Other")
                        if(("".$delivery->AimType)=='4' && ("".$delivery->FundModel)=='35')
                            if(!in_array($qid,$qualifications))
                            {
                                if($qid!='ZPROG001' && $qid!='XESF0001' && $qid!='ZESF0001' && $qid!='ZVOC0007' && $qid!='ZVOC0015' && $qid!='ZVOC0004' && $qid!='ZVOC0006' && $qid!='ZVOC0005' && $qid!='ZVOC0009' && $qid!='ZVOC0014' && $qid!='' && $qid!='Z9OP205A' && $qid!='CMISC001' && $qid!='ZUQAH15A' && $qid!='ZFLW0001')
                                {
                                    $qualifications[] = $qid;
                                    $found = DAO::getSingleValue($link, "select count(*) from qualifications where replace(id,'/','') = '$qid'");
                                    if(!$found)
                                        $qualificationsDownload[] = $qid;
                                }
                            }

                }
            }
        }


        DAO::execute($link, "UPDATE tr LEFT JOIN disc ON disc.`NLearnRefNumber` = tr.`l03` AND disc.`Batch` = 2013 SET tr.`firstnames` = disc.GivenNames, tr.`surname` = disc.FamilyName WHERE disc.`NLearnRefNumber` IS NOT NULL;");
        DAO::execute($link, "UPDATE users INNER JOIN tr ON tr.`username` = users.`username` SET users.`firstnames` = tr.`firstnames`, users.surname = tr.`surname`;");
        DAO::execute($link, "Update tr set ilr_status = 1");


        if(sizeof($qualificationsDownload)>0)
            pre("These qualifications need to be downloaded \n" . implode(",",$qualificationsDownload));

        if($ptype=="Other")
        {
            // Check if All ER Other Frameworks and courses have been created
            $frameworksToBeCreated = Array();
            foreach($ER_Other_Frameworks as $framework)
            {
                if($framework!='ZPROG001' && $framework!='XESF0001' && $framework!='ZESF0001')
                {
                    $found = DAO::getSingleValue($link, "SELECT count(*) FROM framework_qualifications INNER JOIN frameworks ON frameworks.id = framework_qualifications.framework_id AND (frameworks.framework_type = 99 OR frameworks.framework_type is null or frameworks.framework_type = '') WHERE REPLACE(framework_qualifications.id,'/','') = '$framework'");
                    if(!$found)
                    {
                        $f = new Framework();
                        $f->title = DAO::getSingleValue($link, "SELECT CONCAT(REPLACE(id,'/',''),' - ',title) FROM qualifications WHERE REPLACE(id,'/','')='$framework';");
                        if($f->title=='')
                            pre("SELECT CONCAT(REPLACE(id,'/',''),' - ',title) FROM qualifications WHERE REPLACE(id,'/','')='$framework';");
                        $f->framework_code = '';
                        $f->duration_in_months = 12;
                        $f->active = 1;
                        $f->framework_type = 99;
                        $f->save($link);
                        $fid = $f->id;
                        DAO::execute($link, "INSERT INTO framework_qualifications SELECT id,lsc_learning_aim,awarding_body,title,description,assessment_method,structure,LEVEL,qualification_type,regulation_start_date,operational_start_date,operational_end_date,certification_end_date,dfes_approval_start_date,dfes_approval_end_date,'$fid',evidences,units,internaltitle,100,12,units_required,mandatory_units,1 FROM qualifications WHERE REPLACE(id,'/','')='$framework';");

                        $course = new Course($link);
                        $course->id = NULL;
                        $course->organisations_id = DAO::getSingleValue($link, "select id from organisations where organisation_type = 3 limit 0,1");
                        $course->title = $f->title;
                        $course->framework_id = $f->id;
                        $course->programme_type = 1;
                        $course->active = 1;
                        $course->course_start_date = '2013-01-01';
                        $course->course_end_date = '2020-12-31';
                        $course->save($link);
                        // Add qualification to the course too
                        $query  = "insert into course_qualifications_dates (select framework_qualifications.id, framework_qualifications.framework_id, internaltitle, $course->id, courses.course_start_date, DATE_ADD(courses.course_start_date, INTERVAL framework_qualifications.duration_in_months MONTH), '0', '0', '0' from framework_qualifications left join courses on courses.id = $course->id where framework_qualifications.framework_id = $f->id);";
                        DAO::execute($link, $query);
                    }
                }
            }
        }

        // Check if All Apps Frameworks and courses have been created
        $frameworksToBeCreated = Array();
        if($ptype=="Apps")
        {
            foreach($Apps_Frameworks as $framework)
            {
                $app = explode(",", $framework);
                $framework_code = $app[0];
                $prog_type = $app[1];
                $aim_ref = $app[2];
                $found = DAO::getSingleValue($link, "SELECT count(*) FROM frameworks where framework_code = $framework_code and framework_type = '$prog_type'");
                if(!$found)
                {
                    $f = new Framework($link);
                    $f->title = DAO::getSingleValue($link, "select CONCAT(FRAMEWORK_CODE, ' - ',FRAMEWORK_DESC) from lad201213.frameworks where FRAMEWORK_CODE='$framework_code' and FRAMEWORK_TYPE_CODE='$prog_type'");
                    if($f->title=='')
                        $f->title = $framework_code . ' - ' . $prog_type;
                    $f->framework_code = $framework_code;
                    $f->id = NULL;
                    $f->duration_in_months = 12;
                    $f->parent_org = 1;
                    $f->active = 1;
                    $f->clients = '';
                    $f->framework_type = $prog_type;
                    $f->save($link);
                    $fid = $f->id;

                    // and create course too
                    $course = new Course($link);
                    $course->id = NULL;
                    $course->organisations_id = DAO::getSingleValue($link, "select id from organisations where organisation_type = 3 limit 0,1");
                    $course->title = $f->title;
                    $course->framework_id = $f->id;
                    $course->programme_type = 2;
                    $course->active = 1;
                    $course->course_start_date = '2013-01-01';
                    $course->course_end_date = '2020-12-31';
                    $course->save($link);
                }
            }

            // Sort out courses
            foreach($Apps_Frameworks as $framework)
            {
                $app = explode(",", $framework);
                $framework_code = $app[0];
                $prog_type = $app[1];
                $aim_ref = $app[2];
                if($framework_code!='0' && $framework_code!='' && $prog_type!='')
                {
                    $fid = DAO::getSingleValue($link, "SELECT id FROM frameworks where framework_code = '$framework_code' and framework_type = '$prog_type'");
                    if($fid=='')
                        pre("Framework not found for Framework Code " . $framework_code . " and programme type " . $prog_type);
                    $found = DAO::getSingleValue($link, "select count(*) from framework_qualifications where framework_id = $fid and replace(id,'/','')='$aim_ref'");
                    $course_id = DAO::getSingleValue($link, "select id from courses where framework_id = $fid order by id desc limit 0,1");
                    if($course_id=='')
                    {
                        $f = Framework::loadFromDatabase($link, $fid);
                        // and create course too
                        $course = new Course($link);
                        $course->id = NULL;
                        $course->organisations_id = DAO::getSingleValue($link, "select id from organisations where organisation_type = 3 limit 0,1");
                        $course->title = $f->title;
                        $course->framework_id = $f->id;
                        $course->programme_type = 2;
                        $course->active = 1;
                        $course->course_start_date = '2012-01-01';
                        $course->course_end_date = '2020-12-31';
                        $course->save($link);
                        $course_id = $course->id;
                    }
                    if(!$found)
                    {
                        DAO::execute($link, "INSERT INTO framework_qualifications SELECT id,lsc_learning_aim,awarding_body,title,description,assessment_method,structure,LEVEL,qualification_type,regulation_start_date,operational_start_date,operational_end_date,certification_end_date,dfes_approval_start_date,dfes_approval_end_date,'$fid',evidences,units,internaltitle,10,12,units_required,mandatory_units,1 FROM qualifications WHERE REPLACE(id,'/','')='$aim_ref';");
                    }
                    $cqfound = DAO::getSingleValue($link, "select count(*) from course_qualifications_dates where course_id = $course_id and replace(qualification_id,'/','') = '$aim_ref'");
                    if($cqfound!='1')
                    {
                        DAO::execute($link, "insert into course_qualifications_dates (select framework_qualifications.id, framework_qualifications.framework_id, internaltitle, $course_id, courses.course_start_date, DATE_ADD(courses.course_start_date, INTERVAL framework_qualifications.duration_in_months MONTH), '0', '0', '0' from framework_qualifications left join courses on courses.id = $course_id where framework_qualifications.framework_id = $fid and replace(framework_qualifications.id,'/','') = '$aim_ref');");
                    }
                }
            }
        }

        $index = 0;
        foreach($learners as $learner)
        {
            $index++;
            $tr_id++;
            $learner = str_replace("'","\'",$learner);
            $learner = str_replace(array("\r\n", "\r"), "\n", $learner);
            $learner = "<Learner>".$learner;

            $ER_Other = Array();
            $Apprenticeships = Array();

            if($index>=1)
            {
                $ilr = Ilr2012::loadFromXML($learner);
                foreach($ilr->LearningDelivery as $delivery)
                {

                    if($ptype=="Other")
                        if(("".$delivery->AimType)=='4' && ("".$delivery->FundModel)=='35')
                            $ER_Other[] = "" . $delivery->LearnAimRef . "," . $delivery->LearnStartDate . "," . $delivery->LearnPlanEndDate;

                    if($ptype=="Apps")
                        if(("".$delivery->AimType)=='1' && ("".$delivery->FundModel)=='35' && ("".$delivery->CompStatus)!='6')
                            $Apprenticeships[] = "" . $delivery->FworkCode . "," . $delivery->ProgType . ","  . $delivery->LearnStartDate . "," . $delivery->LearnPlanEndDate;
                }

                if(sizeof($ER_Other)>1)
                {
                    foreach($ER_Other as $aims)
                    {
                        $aim = explode(",",$aims);
                        $pageDom = new DomDocument();
                        $pageDom->loadXML($learner);
                        $LearningDelivery = $pageDom->getElementsByTagName('LearningDelivery');
                        $nodesToRemove = Array();
                        $empToRemove = Array();
                        foreach($LearningDelivery as $ld)
                        {
                            $LearnAimRef = $ld->getElementsByTagName('LearnAimRef')->item(0)->nodeValue;
                            $LearnStartDate = Date::toMySQL($ld->getElementsByTagName('LearnStartDate')->item(0)->nodeValue);
                            $StartDate = Date::toMySQL($aim[1]);
                            if($LearnAimRef!=$aim[0] || $LearnStartDate!=$StartDate)
                            {
                                $nodesToRemove[] = $ld;
                            }
                            else
                            {
                                $course_id = DAO::getSingleValue($link, "select courses.id from framework_qualifications inner join frameworks on frameworks.id = framework_qualifications.framework_id and frameworks.framework_type='99' inner join courses on courses.framework_id = frameworks.id where replace(framework_qualifications.id,'/','') = '$LearnAimRef'");
                                if($course_id=='' || $course_id=="NULL")
                                    throw new Exception("select courses.id from framework_qualifications inner join frameworks on frameworks.id = framework_qualifications.framework_id and frameworks.framework_type='99' inner join courses on courses.framework_id = frameworks.id where replace(framework_qualifications.id,'/','') = '$LearnAimRef'");
                            }
                        }
                        $LearnerEmploymentStatus = $pageDom->getElementsByTagName('LearnerEmploymentStatus');
                        foreach($LearnerEmploymentStatus as $les)
                        {
                            $DateEmpStatApp = new Date($les->getElementsByTagName('DateEmpStatApp')->item(0)->nodeValue);
                            $PlanEndDate = new Date($aim[2]);
                            $StartDate = new Date($aim[1]);
                            $StartDate->subtractDays(1);
                            if($DateEmpStatApp->getDate() < $StartDate->getDate() || $DateEmpStatApp->getDate()>$PlanEndDate->getDate())
                            {
                                $empToRemove[] = $les;
                            }
                        }
                        foreach($nodesToRemove as $n)
                        {
                            $n->parentNode->removeChild($n);
                        }
                        foreach($empToRemove as $e)
                        {
                            $e->parentNode->removeChild($e);
                        }
                        $ilr3 = $pageDom->saveXML();
                        $ilr3=substr($ilr3,21);

                        $employer_id = $this->createEmployer($link, $ilr3);
                        $this->createLearner($link,$ilr3,$employer_id);
                        $this->createTrainingRecordOTHER($link,$ilr3,$tr_id,$course_id,"ER_OTHER_MULTI",$contract_id,$submission,$provider_id);
                        $tr_id++;
                    }
                }
                elseif(sizeof($Apprenticeships)>1)
                {
                    foreach($Apprenticeships as $frameworks)
                    {
                        $framework = explode(",",$frameworks);
                        $pageDom = new DomDocument();
                        $pageDom->loadXML($learner);
                        $LearningDelivery = $pageDom->getElementsByTagName('LearningDelivery');
                        $nodesToRemove = Array();
                        $empToRemove = Array();
                        foreach($LearningDelivery as $ld)
                        {
                            $FworkCode = @$ld->getElementsByTagName('FworkCode')->item(0)->nodeValue;
                            @$ProgType = $ld->getElementsByTagName('ProgType')->item(0)->nodeValue;
                            if($FworkCode!=$framework[0] || $ProgType!=$framework[1])
                            {
                                $nodesToRemove[] = $ld;
                            }
                            else
                            {
                                $FworkCode1 = $ld->getElementsByTagName('FworkCode')->item(0)->nodeValue;
                                $ProgType1 = $ld->getElementsByTagName('ProgType')->item(0)->nodeValue;
                            }
                        }
                        $LearnerEmploymentStatus = $pageDom->getElementsByTagName('LearnerEmploymentStatus');
                        foreach($LearnerEmploymentStatus as $les)
                        {
                            $DateEmpStatApp = new Date($les->getElementsByTagName('DateEmpStatApp')->item(0)->nodeValue);
                            $PlanEndDate = new Date($framework[3]);
                            $StartDate = new Date($framework[2]);
                            $StartDate->subtractDays(1);
                            if($DateEmpStatApp->getDate() < $StartDate->getDate() || $DateEmpStatApp->getDate()>$PlanEndDate->getDate())
                            {
                                $empToRemove[] = $les;
                            }
                        }
                        foreach($nodesToRemove as $n)
                        {
                            $n->parentNode->removeChild($n);
                        }
                        foreach($empToRemove as $e)
                        {
                            $e->parentNode->removeChild($e);
                        }
                        $ilr3 = $pageDom->saveXML();
                        $ilr3=substr($ilr3,21);
                        $employer_id = $this->createEmployer($link, $ilr3);
                        $this->createLearner($link,$ilr3,$employer_id);
                        $course_id = DAO::getSingleValue($link, "select courses.id from frameworks inner join courses on courses.framework_id = frameworks.id where frameworks.framework_code = '$FworkCode1' and framework_type = '$ProgType1'");
                        $this->createTrainingRecordAPP($link,$ilr3,$tr_id,$course_id,"APP_MULTI",$contract_id,$submission,$provider_id,$southampton);
                        $tr_id++;
                    }
                }
                else
                {
                    $ilr = Ilr2012::loadFromXML($learner);
                    $type = '';

                    foreach($ilr->LearningDelivery as $ld)
                    {
                        if($ld->AimType=='4' && $ld->LearnAimRef!='XESF0001' && $ld->LearnAimRef!='ZESF0001' && $ld->FundModel=='35')
                        {
                            if($ptype=="Other")
                            {
                                $type = "ER_OTHER_SINGLE";
                                $LearnAimRef = "" . $ld->LearnAimRef;
                                $course_id = DAO::getSingleValue($link, "select courses.id from framework_qualifications inner join frameworks on frameworks.id = framework_qualifications.framework_id and frameworks.framework_type='99' inner join courses on courses.framework_id = frameworks.id where replace(framework_qualifications.id,'/','') = '$LearnAimRef'");
                                if($course_id=='')
                                    pre("select courses.id from framework_qualifications inner join frameworks on frameworks.id = framework_qualifications.framework_id and frameworks.framework_type='99' inner join courses on courses.framework_id = frameworks.id where replace(framework_qualifications.id,'/','') = '$LearnAimRef'");
                                $employer_id = $this->createEmployer($link, $learner);
                                $this->createLearner($link,$learner,$employer_id);
                                $this->createTrainingRecordOTHER($link,$learner,$tr_id,$course_id,$type,$contract_id,$submission,$provider_id);
                            }
                        }
                        elseif($ld->AimType=='1' && $ld->FundModel=='35')
                        {
                            if($ptype=="Apps")
                            {
                                $type = "APP_SINGLE";
                                $FworkCode = "" . $ld->FworkCode;
                                $ProgType = "" . $ld->ProgType;
                                $course_id = DAO::getSingleValue($link, "select courses.id from frameworks inner join courses on courses.framework_id = frameworks.id where frameworks.framework_code = '$FworkCode' and framework_type = '$ProgType'");
                                if($course_id=='')
                                    pre("select courses.id from frameworks inner join courses on courses.framework_id = frameworks.id where frameworks.framework_code = '$FworkCode' and framework_type = '$ProgType'");
                                $employer_id = $this->createEmployer($link, $learner);
                                $this->createLearner($link,$learner,$employer_id);
                                $this->createTrainingRecordAPP($link,$learner,$tr_id,$course_id,$type,$contract_id,$submission,$provider_id,$southampton);
                            }
                        }
                    }
                }
            }
        }

        DAO::execute($link, "update ilr set l03 = trim(extractvalue(ilr,'/Learner/LearnRefNumber')) where contract_id in (select id from contracts where contract_year > 2012)");
        DAO::execute($link, "UPDATE tr LEFT JOIN ilr ON ilr.tr_id = tr.id AND submission = 'W06' LEFT JOIN organisations ON organisations.`ukprn` = LEFT(extractvalue(ilr,'/Learner/LearningDelivery/PartnerUKPRN'),8) SET provider_id = organisations.id WHERE organisations.id IS NOT NULL;");
        DAO::execute($link, "UPDATE tr LEFT JOIN ilr ON ilr.tr_id = tr.id AND submission = 'W06' LEFT JOIN organisations ON organisations.`ukprn` = LEFT(extractvalue(ilr,'/ilr/main/A22|ilr/subaim/A22'),8) SET provider_id = organisations.id WHERE organisations.id IS NOT NULL;");
        DAO::execute($link, "UPDATE tr INNER JOIN locations ON locations.`organisations_id` = tr.`provider_id` SET provider_location_id = locations.id;");
        DAO::execute($link, "UPDATE student_qualifications INNER JOIN tr ON tr.id = student_qualifications.tr_id INNER JOIN disc ON TRIM(tr.l03) = TRIM(disc.NLearnRefNumber) AND disc.NLearnAimRef = REPLACE(student_qualifications.id,'/','') SET student_qualifications.start_date = disc.NLearnStartDate, student_qualifications.end_date = disc.NLearnPlanEndDate, student_qualifications.actual_end_date = disc.NLearnActEndDate, student_qualifications.achievement_date = disc.NAchDate;");
        DAO::execute($link, "UPDATE disc INNER JOIN tr ON TRIM(tr.l03) = TRIM(disc.NLearnRefNumber) INNER JOIN student_qualifications ON student_qualifications.tr_id = tr.id AND disc.NLearnAimRef = REPLACE(student_qualifications.id,'/','') SET disc.SAchDate = student_qualifications.achievement_date , disc.SLearnActEndDate = student_qualifications.actual_end_date , disc.SLearnAimRef = REPLACE(student_qualifications.id,'/','') , disc.SLearnPlanEndDate = student_qualifications.end_date , disc.SLearnRefNumber = TRIM(tr.l03) , disc.SLearnStartDate = student_qualifications.start_date;");
        DAO::execute($link, "UPDATE tr LEFT JOIN disc ON disc.`NLearnRefNumber` = tr.`l03` AND disc.`Batch` = 2013 SET tr.`firstnames` = disc.GivenNames, tr.`surname` = disc.FamilyName WHERE disc.`NLearnRefNumber` IS NOT NULL;");
        DAO::execute($link, "UPDATE users INNER JOIN tr ON tr.`username` = users.`username` SET users.`firstnames` = tr.`firstnames`, users.surname = tr.`surname`;");
        DAO::execute($link, "Update tr set ilr_status = 1");


        pre("Finished");

    }

    public function createTrainingRecordAPP($link, $learner, $tr_id, $course_id, $type, $contract_id, $submission, $provider_id, $client = '')
    {
        $ilr = Ilr2013::loadFromXML($learner);
        $LearnRefNumber = trim($ilr->LearnRefNumber);

        $ProgType = '';
        $FworkCode = '';
        foreach($ilr->LearningDelivery as $ld)
        {
            if($ld->AimType=='1')
            {
                $ProgType = "" . $ld->ProgType;
                $FworkCode = "" . $ld->FworkCode;
                if($ld->LearnActEndDate=='')
                    $closure_date = "NULL";
                else
                    $closure_date = "'" . Date::toMySQL($ld->LearnActEndDate) . "'";
                if($ld->LearnStartDate=='')
                    $start_date = "NULL";
                else
                    $start_date = "'" . Date::toMySQL($ld->LearnStartDate) . "'";
                if($ld->LearnPlanEndDate=='')
                    $target_date = "NULL";
                else
                    $target_date = "'" . Date::toMySQL($ld->LearnPlanEndDate) . "'";
                $status_code = $ld->CompStatus;

                break;
            }
        }
        if($ProgType=='' || $FworkCode=='')
            pre("Missing data line 337");

        $tr_id = DAO::getSingleValue($link, "SELECT tr_id FROM ilr WHERE trim(extractvalue(ilr,'/Learner/LearnRefNumber'))='$LearnRefNumber' AND extractvalue(ilr,'/Learner/LearningDelivery[AimType=1]/FworkCode')='$FworkCode' AND extractvalue(ilr,'/Learner/LearningDelivery[AimType=1]/ProgType')='$ProgType' and submission = '$submission' and contract_id in (select id from contracts where contract_year = 2013);");
        if($tr_id)
        {
            $learner = str_replace("'","",$learner);
            DAO::execute($link, "update ilr set ilr = '$learner' where tr_id = '$tr_id' and submission = '$submission' and contract_id = '$contract_id'");
            DAO::execute($link, "update tr set start_date = $start_date, target_date = $target_date, closure_date = $closure_date, status_code = '$status_code' where id = '$tr_id'");
            foreach($ilr->LearningDelivery as $ld)
            {
                if($ld->AimType!='1')
                {
                    $LearnAimRef = "" . $ld->LearnAimRef;
                    $LearnStartDate = Date::toMySQL($ld->LearnStartDate);
                    $LearnPlanEndDate = Date::toMySQL($ld->LearnPlanEndDate);
                    if($ld->LearnActEndDate=='')
                        $LearnActEndDate = "NULL";
                    else
                        $LearnActEndDate = "'" . Date::toMySQL($ld->LearnActEndDate) . "'";
                    if($ld->AchDate=='')
                        $AchDate = "NULL";
                    else
                        $AchDate = "'" . $ld->AchDate . "'";

                    $found = DAO::getSingleValue($link, "select tr_id from student_qualifications where replace(id,'/','')='$LearnAimRef' and tr_id = '$tr_id'");
                    if(!$found)
                    {
                        //if($tr_id=='4705')
                        //  pre($found . $LearnAimRef);
                        $course_id = DAO::getSingleValue($link,"select course_id from courses_tr where tr_id = '$tr_id'");
                        if($course_id=='')
                        {
                            $FworkCode = "".$ld->FworkCode;
                            $ProgType = "".$ld->ProgType;
                            $course_id = DAO::getSingleValue($link, "select courses.id from courses inner join frameworks on frameworks.id = courses.framework_id where frameworks.framework_code = '$FworkCode' and frameworks.framework_type = '$ProgType'");
                            $fid = DAO::getSingleValue($link, "select framework_id from courses where id = $course_id");
                            DAO::execute($link, "insert into courses_tr (course_id, tr_id, qualification_id, framework_id) values($course_id,$tr_id,0,$fid);");
                            DAO::execute($link, "INSERT INTO student_frameworks SELECT frameworks.title, frameworks.id, courses_tr.`tr_id`, frameworks.`framework_code`, '',12 FROM courses_tr INNER JOIN frameworks ON frameworks.id = courses_tr.`framework_id` WHERE tr_id NOT IN (SELECT tr_id FROM student_frameworks);");
                        }
                        $fid = DAO::getSingleValue($link, "select framework_id from courses where id = $course_id");
                        // importing qualification from framework
                        //$query = "insert into student_qualifications select id, '$fid', '$tr_id', framework_qualifications.internaltitle, lsc_learning_aim, awarding_body, title, description, assessment_method, structure, level, qualification_type, accreditation_start_date, operational_centre_start_date, accreditation_end_date, certification_end_date, dfes_approval_start_date, dfes_approval_end_date, evidences, units, '0', '0', '0', '0', '0', units_required, proportion, 0, 0, 0, 0, 0, 0, 0, '$LearnStartDate', '$LearnPlanEndDate', $LearnActEndDate, $AchDate, units_required, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '' from framework_qualifications LEFT JOIN course_qualifications_dates on course_qualifications_dates.qualification_id = framework_qualifications.id and course_qualifications_dates.framework_id = framework_qualifications.framework_id and  course_qualifications_dates.internaltitle = framework_qualifications.internaltitle where replace(framework_qualifications.id,'/','') = '$LearnAimRef' and framework_qualifications.framework_id = '$fid' and course_qualifications_dates.course_id='$course_id'";
                        $query = "insert into student_qualifications select id, '$fid', '$tr_id', framework_qualifications.internaltitle, lsc_learning_aim, awarding_body, title, description, assessment_method, structure, level, qualification_type, accreditation_start_date, operational_centre_start_date, accreditation_end_date, certification_end_date, dfes_approval_start_date, dfes_approval_end_date, evidences, units, '0', '0', '0', '0', '0', units_required, proportion, 0, 0, 0, 0, 0, 0, 0, '$LearnStartDate', '$LearnPlanEndDate', $LearnActEndDate, $AchDate, units_required, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '','' from framework_qualifications where replace(framework_qualifications.id,'/','') = '$LearnAimRef' and framework_qualifications.framework_id = '$fid'";
                        DAO::execute($link, $query);
                    }
                    //DAO::execute($link,"update student_qualifications set start_date = '$LearnStartDate', end_date = '$LearnPlanEndDate', actual_end_date = $LearnActEndDate, achievement_date = $AchDate where tr_id = '$tr_id' and replace(id,'/','')='$LearnAimRef'");
                }
            }
        }
        else
        {
            $tr_id2 = DAO::getSingleValue($link, "SELECT tr_id FROM ilr WHERE trim(extractvalue(ilr,'/ilr/learner/L03'))='$LearnRefNumber' and extractvalue(ilr,'/ilr/programmeaim/A26')='$FworkCode' and extractvalue(ilr,'/ilr/programmeaim/A15')='$ProgType' AND contract_id in (select id from contracts where contract_year < 2013);");
            if($tr_id2)
            {
                $found = DAO::getSingleValue($link, "select count(*) from ilr where submission = '$submission' and tr_id = '$tr_id2' and contract_id = '$contract_id'");
                if(!$found)
                    DAO::execute($link, "insert into ilr(ilr,submission,tr_id,contract_id,contract_type) values('$learner','$submission','$tr_id2',$contract_id,'ER')");
                $tr_id = $tr_id2;
            }
            else
            {
                $tr_id = DAO::getSingleValue($link, "select id from tr where trim(l03) = '$LearnRefNumber' and id not in (select tr_id from ilr)");
                if(!$tr_id)
                {
                    $tr_id = DAO::getSingleValue($link, "select max(tr_id) from ilr");
                    $tr_id++;
                    $ilr = Ilr2013::loadFromXML($learner);
                    $user = User::loadFromDatabase($link, trim($ilr->LearnRefNumber));
                    $tr = new TrainingRecord();
                    $tr->populate($user, true);
                    $tr->id = $tr_id;
                    $tr->contract_id = $contract_id;
                    foreach($ilr->LearningDelivery as $delivery)
                    {
                        if($delivery->AimType=='1' || $delivery->AimType=='4')
                        {
                            $start_date = $delivery->LearnStartDate;
                            $end_date = $delivery->LearnPlanEndDate;
                            if($delivery->LearnActEndDate=='')
                                $closure_date = NULL;
                            else
                                $closure_date = $delivery->LearnActEndDate;
                            $status_code = $delivery->CompStatus;
                            break;
                        }
                    }
                    $aims = Array();
                    foreach($ilr->LearningDelivery as $delivery)
                    {
                        $aims[] = "'" . $delivery->LearnAimRef . "'";
                    }
                    $aims = implode(",",$aims);

                    $tr->start_date = $start_date;
                    $tr->target_date = $end_date;
                    $tr->closure_date = $closure_date;
                    $tr->status_code = $status_code;
                    $tr->work_experience = 0;
                    $tr->l03 = trim($ilr->LearnRefNumber);
                    $tr->provider_id = $provider_id;
                    $tr->save($link);

                    // Lets Attach
                    $link->beginTransaction();
                    try
                    {
                        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
                        if (!$tr) {
                            throw new Exception("Could not find training record with id #" . $tr_id);
                        }

                        $sd = Date::toMySQL($tr->start_date);
                        if (!$sd) {
                            throw new Exception("This training record (#$tr_id) does not have a training start-date. Please correct this before adding new qualifications.");
                        }
                        $ed = Date::toMySQL($tr->target_date);
                        if (!$ed) {
                            throw new Exception("This training record (#$tr_id) does not have a training planned-end-date. Please correct this before adding new qualifications.");
                        }

                        $course = Course::loadFromDatabase($link, $course_id);
                        if (!$course) {
                            throw new Exception("Could not find course with id #" . $course_id);
                        }

                        $que = "select id from locations where organisations_id='$course->organisations_id'";
                        $location_id = trim(DAO::getSingleValue($link, $que));
                        $provider = Location::loadFromDatabase($link, $location_id);
                        if(empty($provider)) {
                            throw new Exception($course_id . "Cannot find location with id #$location_id for organisation #{$course->organisations_id}");
                        }

                        $data = array();
                        $data['id'] = $tr->id;
                        $data['provider_address_line_1'] = $provider->address_line_1;
                        $data['provider_address_line_2'] = $provider->address_line_2;
                        $data['provider_address_line_3'] = $provider->address_line_3;
                        $data['provider_address_line_4'] = $provider->address_line_4;
                        $data['provider_postcode'] = $provider->postcode;
                        DAO::saveObjectToTable($link, 'tr', $data);

                        // Enrol on a course and put in a  group
                        $framework_id = '0';
                        $qualification_id = '0';



                        $que = "select main_qualification_id from courses where id='$course_id'";
                        $qualification_id = DAO::getSingleValue($link, $que);

                        if($qualification_id=='')
                        {
                            $qualification_id  = '0';
                            $que = "select framework_id from courses where id='$course_id'";
                            $framework_id = DAO::getSingleValue($link, $que);
                            $fid = $framework_id;
                        }

                        // enroling on a course
                        $query = "insert IGNORE into courses_tr values($course_id, $tr_id, '$qualification_id', $framework_id);";
                        DAO::execute($link, $query);

                        // Check if this course has a framework attached to it and get framework id
                        $que = "select framework_id from courses where id='$course_id'";
                        $fid = DAO::getSingleValue($link, $que);

                        $que = "select id from student_frameworks where tr_id='$tr_id'";
                        $tr_framework_id = DAO::getSingleValue($link, $que);

                        if($fid!='')
                        {
                            if($tr_framework_id=='')
                            {
                                // Importing framework
                                $query = "insert into student_frameworks select title, id, '$tr_id', framework_code, comments, duration_in_months from frameworks where id = '$fid';";
                                DAO::execute($link, $query);
                                // importing qualification from framework
                                $query = "insert into student_qualifications select id, '$fid', '$tr_id', framework_qualifications.internaltitle, lsc_learning_aim, awarding_body, title, description, assessment_method, structure, level, qualification_type, accreditation_start_date, operational_centre_start_date, accreditation_end_date, certification_end_date, dfes_approval_start_date, dfes_approval_end_date, evidences, units, '0', '0', '0', '0', '0', units_required, proportion, 0, 0, 0, 0, 0, 0, 0, '$sd', '$ed', NULL, NULL, units_required, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '' from framework_qualifications LEFT JOIN course_qualifications_dates on course_qualifications_dates.qualification_id = framework_qualifications.id and course_qualifications_dates.framework_id = framework_qualifications.framework_id and course_qualifications_dates.internaltitle = framework_qualifications.internaltitle 	where replace(framework_qualifications.id,'/','') in ($aims) and framework_qualifications.framework_id = '$fid' and course_qualifications_dates.course_id='$course_id';";
                                DAO::execute($link, $query);
                            }
                        }
                        $learner = str_replace("'","",$learner);
                        DAO::execute($link, "insert into ilr(l03,ilr,submission,tr_id,contract_id,contract_type) values(trim('$LearnRefNumber'),'$learner','W06','$tr_id',$contract_id,'ER')");
                        $link->commit();
                    }
                    catch(Exception $e)
                    {
                        $link->rollback();
                        throw new WrappedException($e);
                    }
                }
                else
                {
                    $learner = str_replace("'","",$learner);
                    DAO::execute($link, "insert into ilr(l03,ilr,submission,tr_id,contract_id,contract_type) values(trim('$LearnRefNumber'),'$learner','W06','$tr_id',$contract_id,'ER')");
                }

            }
        }

        if($client == 'southampton')
        {
            $xpath = $ilr->xpath("/Learner/LearningDelivery[LearnAimRef='ZPROG001']/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='B']/ProvSpecDelMon");
            $ProvSpecLearnMon2 = "" . (empty($xpath[0]))?'':$xpath[0];
            $framework_title = DAO::getSingleValue($link, "select framework_title from course_codes where course_code = '$ProvSpecLearnMon2' limit 0,1");
            if($framework_title!='')
            {
                $pathway_exists = DAO::getSingleValue($link, "select id from frameworks where trim(title) = trim('$framework_title')");
                if(!$pathway_exists)
                {
                    $xpath = $ilr->xpath("/Learner/LearningDelivery[LearnAimRef='ZPROG001']/FworkCode");
                    $FworkCode = "" . (empty($xpath[0]))?'':$xpath[0];
                    $xpath = $ilr->xpath("/Learner/LearningDelivery[LearnAimRef='ZPROG001']/ProgType");
                    $ProgType = "" . (empty($xpath[0]))?'':$xpath[0];
                    $framework_id = DAO::getSingleValue($link, "select id from frameworks where framework_code = '$FworkCode' and framework_type='$ProgType' and comments is null");
                    if($framework_id=='')
                    {
                        pre("Oops Framework for Framework Code " . $FworkCode . " and prog type = " . $ProgType);
                    }
                    else
                    {
                        $f = Framework::loadFromDatabase($link, $framework_id);
                        $f->id = NULL;
                        $f->title = $framework_title;
                        $f->comments = "Pathway";
                        $f->save($link);
                        DAO::execute($link, "insert into framework_qualifications select id,lsc_learning_aim,awarding_body,title,description,assessment_method,structure,level,qualification_type,accreditation_start_date,operational_centre_start_date,accreditation_end_date,certification_end_date,dfes_approval_start_date,dfes_approval_end_date,$f->id,evidences,units,internaltitle,proportion,duration_in_months,units_required,mandatory_units,main_aim from framework_qualifications where framework_id = '$framework_id' ");

                        // and create course too
                        $course = new Course($link);
                        $course->id = NULL;
                        $course->organisations_id = DAO::getSingleValue($link, "select id from organisations where organisation_type = 3 limit 0,1");
                        $course->title = $f->title;
                        $course->framework_id = $f->id;
                        $course->programme_type = 2;
                        $course->active = 1;
                        $course->course_start_date = '2012-01-01';
                        $course->course_end_date = '2020-12-31';
                        $course->save($link);

                    }
                }
            }
        }
    }

    public function createTrainingRecordOTHER($link, $learner, $tr_id, $course_id, $type, $contract_id, $submission, $provider_id)
    {
        $ilr = Ilr2012::loadFromXML($learner);
        $LearnRefNumber = trim($ilr->LearnRefNumber);
        if(("".$ilr->LearningDelivery->LearnActEndDate)=='')
            $closure_date = "NULL";
        else
            $closure_date = "'" . Date::toMySQL($ilr->LearningDelivery->LearnActEndDate) . "'";
        $status_code = $ilr->LearningDelivery->CompStatus;
        $LearnAimRef = "" . $ilr->LearningDelivery->LearnAimRef;

        $tr_id = DAO::getSingleValue($link, "SELECT tr_id FROM ilr WHERE trim(extractvalue(ilr,'/Learner/LearnRefNumber'))='$LearnRefNumber' AND extractvalue(ilr,'/Learner/LearningDelivery[AimType=4]/LearnAimRef')='$LearnAimRef' and submission = '$submission' and contract_id in (select id from contracts where contract_year = 2012);");
        if($tr_id)
        {
            DAO::execute($link, "update ilr set ilr = '$learner' where tr_id = '$tr_id' and submission = '$submission' and contract_id = '$contract_id'");
            DAO::execute($link, "update tr set closure_date = $closure_date, status_code = '$status_code' where id = '$tr_id'");

            foreach($ilr->LearningDelivery as $ld)
            {
                if($ld->AimType!='4')
                {
                    $LearnAimRef = "" . $ld->LearnAimRef;
                    $LearnStartDate = Date::toMySQL($ld->LearnStartDate);
                    $LearnPlanEndDate = Date::toMySQL($ld->LearnPlanEndDate);
                    if($ld->LearnActEndDate=='')
                        $LearnActEndDate = "NULL";
                    else
                        $LearnActEndDate = "'" . Date::toMySQL($ld->LearnActEndDate) . "'";
                    if($ld->AchDate=='')
                        $AchDate = "NULL";
                    else
                        $AchDate = "'" . Date::toMySQL($ld->AchDate) . "'";

                    $found = DAO::getSingleValue($link, "select tr_id from student_qualifications where replace(id,'/','')='$LearnAimRef' and tr_id = '$tr_id'");
                    if(!$found)
                    {
                        $course_id = DAO::getSingleValue($link,"select course_id from courses_tr where tr_id = '$tr_id'");
                        $fid = DAO::getSingleValue($link, "select framework_id from courses where id = $course_id");
                        // importing qualification from framework
                        $query = "insert into student_qualifications select id, '$fid', '$tr_id', framework_qualifications.internaltitle, lsc_learning_aim, awarding_body, title, description, assessment_method, structure, level, qualification_type, accreditation_start_date, operational_centre_start_date, accreditation_end_date, certification_end_date, dfes_approval_start_date, dfes_approval_end_date, evidences, units, '0', '0', '0', '0', '0', units_required, proportion, 0, 0, 0, 0, 0, 0, 0, '$LearnStartDate', '$LearnPlanEndDate', $LearnActEndDate, $AchDate, units_required, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '','' from framework_qualifications LEFT JOIN course_qualifications_dates on course_qualifications_dates.qualification_id = framework_qualifications.id and course_qualifications_dates.framework_id = framework_qualifications.framework_id and  course_qualifications_dates.internaltitle = framework_qualifications.internaltitle where replace(framework_qualifications.id,'/','')= '$LearnAimRef' and framework_qualifications.framework_id = '$fid' and course_qualifications_dates.course_id='$course_id'";
                        DAO::execute($link, $query);
                    }
                    // DAO::execute($link,"update student_qualifications set start_date = '$LearnStartDate', end_date = '$LearnPlanEndDate', actual_end_date = $LearnActEndDate, achievement_date = $AchDate where tr_id = '$tr_id' and replace(id,'/','')='$LearnAimRef'");
                }
            }
        }
        else
        {
            $tr_id2 = DAO::getSingleValue($link, "SELECT tr_id FROM ilr WHERE trim(extractvalue(ilr,'/ilr/learner/L03'))='$LearnRefNumber' AND extractvalue(ilr,'/ilr/main/A09')='$LearnAimRef' and contract_id in (select id from contracts where contract_year < 2012);");
            if($tr_id2)
            {
                DAO::execute($link, "insert IGNORE into ilr(ilr,submission,tr_id,contract_id,contract_type) values('$learner','$submission','$tr_id2',$contract_id,'ER')");
            }
            else
            {
                // Check if tr without ILR exists
                $tr_id = DAO::getSingleValue($link, "select id from tr where trim(l03) = '$LearnRefNumber' and id not in (select tr_id from ilr)");
                if(!$tr_id)
                {
                    $tr_id = DAO::getSingleValue($link, "select max(tr_id) from ilr");
                    $tr_id++;
                    $ilr = Ilr2012::loadFromXML($learner);
                    $user = User::loadFromDatabase($link, trim($ilr->LearnRefNumber));
                    $tr = new TrainingRecord();
                    $tr->populate($user, true);
                    $tr->id = $tr_id;
                    $tr->contract_id = $contract_id;
                    foreach($ilr->LearningDelivery as $delivery)
                    {
                        if($delivery->AimType=='4')
                        {
                            $start_date = $delivery->LearnStartDate;
                            $end_date = $delivery->LearnPlanEndDate;
                            if($delivery->LearnActEndDate=='')
                                $closure_date = NULL;
                            else
                                $closure_date = $delivery->LearnActEndDate;
                            $status_code = $delivery->CompStatus;
                            break;
                        }
                    }
                    $aims = Array();
                    foreach($ilr->LearningDelivery as $delivery)
                    {
                        $aims[] = "'" . $delivery->LearnAimRef . "'";
                    }
                    $aims = implode(",",$aims);

                    $tr->start_date = $start_date;
                    $tr->target_date = $end_date;
                    $tr->closure_date = $closure_date;
                    $tr->status_code = $status_code;
                    $tr->work_experience = 0;
                    $tr->l03 = trim($ilr->LearnRefNumber);
                    $tr->provider_id = $provider_id;
                    $tr->save($link);

                    // Lets Attach
                    $link->beginTransaction();
                    try
                    {
                        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
                        if (!$tr) {
                            throw new Exception("Could not find training record with id #" . $tr_id);
                        }

                        $sd = Date::toMySQL($tr->start_date);
                        if (!$sd) {
                            throw new Exception("This training record (#$tr_id) does not have a training start-date. Please correct this before adding new qualifications.");
                        }
                        $ed = Date::toMySQL($tr->target_date);
                        if (!$ed) {
                            throw new Exception("This training record (#$tr_id) does not have a training planned-end-date. Please correct this before adding new qualifications.");
                        }

                        $course = Course::loadFromDatabase($link, $course_id);
                        if (empty($course)) {
                            throw new Exception("Could not find course with id #" . $course_id);
                        }

                        $que = "select id from locations where organisations_id='$course->organisations_id'";
                        $location_id = trim(DAO::getSingleValue($link, $que));
                        $provider = Location::loadFromDatabase($link, $location_id);
                        if(empty($provider)) {
                            throw new Exception($course_id . "Cannot find location with id #$location_id for organisation #{$course->organisations_id}");
                        }

                        $data = array();
                        $data['id'] = $tr->id;
                        $data['provider_address_line_1'] = $provider->address_line_1;
                        $data['provider_address_line_2'] = $provider->address_line_2;
                        $data['provider_address_line_3'] = $provider->address_line_3;
                        $data['provider_address_line_4'] = $provider->address_line_4;
                        $data['provider_postcode'] = $provider->postcode;
                        DAO::saveObjectToTable($link, 'tr', $data);

                        // Enrol on a course and put in a  group
                        $framework_id = '0';
                        $qualification_id = '0';



                        $que = "select main_qualification_id from courses where id='$course_id'";
                        $qualification_id = DAO::getSingleValue($link, $que);

                        if($qualification_id=='')
                        {
                            $qualification_id  = '0';
                            $que = "select framework_id from courses where id='$course_id'";
                            $framework_id = DAO::getSingleValue($link, $que);
                            $fid = $framework_id;
                        }

                        // enroling on a course
                        $query = "insert into courses_tr (course_id, tr_id, qualification_id, framework_id) values($course_id, $tr_id, '$qualification_id', $framework_id);";
                        DAO::execute($link, $query);

                        // Check if this course has a framework attached to it and get framework id
                        $que = "select framework_id from courses where id='$course_id'";
                        $fid = DAO::getSingleValue($link, $que);

                        $que = "select id from student_frameworks where tr_id='$tr_id'";
                        $tr_framework_id = DAO::getSingleValue($link, $que);

                        if($fid!='')
                        {
                            if($tr_framework_id=='')
                            {
                                // Importing framework
                                $query = "insert into student_frameworks select title, id, '$tr_id', framework_code, comments, duration_in_months from frameworks where id = '$fid';";
                                DAO::execute($link, $query);
                                // importing qualification from framework
                                $query = "insert into student_qualifications select id, '$fid', '$tr_id', framework_qualifications.internaltitle, lsc_learning_aim, awarding_body, title, description, assessment_method, structure, level, qualification_type, accreditation_start_date, operational_centre_start_date, accreditation_end_date, certification_end_date, dfes_approval_start_date, dfes_approval_end_date, evidences, units, '0', '0', '0', '0', '0', units_required, proportion, 0, 0, 0, 0, 0, 0, 0, '$sd', '$ed', NULL, NULL, units_required, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '' from framework_qualifications LEFT JOIN course_qualifications_dates on course_qualifications_dates.qualification_id = framework_qualifications.id and course_qualifications_dates.framework_id = framework_qualifications.framework_id and course_qualifications_dates.internaltitle = framework_qualifications.internaltitle 	where replace(framework_qualifications.id,'/','') in ($aims) and framework_qualifications.framework_id = '$fid' and course_qualifications_dates.course_id='$course_id';";
                                DAO::execute($link, $query);
                            }
                        }

                        DAO::execute($link, "insert into ilr(ilr,submission,tr_id,contract_id,contract_type) values('$learner','W06','$tr_id',$contract_id,'ER')");
                        $link->commit();
                    }
                    catch(Exception $e)
                    {
                        $link->rollback();
                        throw new WrappedException($e);
                    }
                }
                else
                {
                    DAO::execute($link, "insert into ilr(ilr,submission,tr_id,contract_id,contract_type) values('$learner','W06','$tr_id',$contract_id,'ER')");
                }
            }
        }
    }

    public function createLearner($link, $learner, $employer_id)
    {
        $ilr = Ilr2013::loadFromXML($learner);
        $username = trim($ilr->LearnRefNumber);
        $found = DAO::getSingleValue($link,"select count(*) from users where username='$username'");
        if(!$found)
        {
            $user = new User();
            $user->firstnames = trim($ilr->GivenNames);
            $user->surname = trim($ilr->FamilyName);
            $user->username = $username;
            $user->dob = Date::toMySQL($ilr->DateOfBirth);
            $user->password = "password";
            $user->record_status = 1;
            $user->ni = $ilr->NINumber;
            $user->gender = $ilr->Sex;
            $user->ethnicity = $ilr->Ethnicity;
            $xpath = $ilr->xpath('/Learner/LearnerContact/PostAdd/AddLine1'); $add1 = (empty($xpath))?'':(string)$xpath[0];
            $xpath = $ilr->xpath('/Learner/LearnerContact/PostAdd/AddLine2'); $add2 = (empty($xpath))?'':(string)$xpath[0];
            $xpath = $ilr->xpath('/Learner/LearnerContact/PostAdd/AddLine3'); $add3 = (empty($xpath))?'':(string)$xpath[0];
            $xpath = $ilr->xpath('/Learner/LearnerContact/PostAdd/AddLine4'); $add4 = (empty($xpath))?'':(string)$xpath[0];
            $xpath = $ilr->xpath('/Learner/LearnerContact/TelNumber'); $tel = (empty($xpath))?'':$xpath[0];
            $user->home_address_line_1 = $add1;
            $user->home_address_line_2 = $add2;
            $user->home_address_line_3 = $add3;
            $user->home_address_line_4 = $add4;
            $xpath = $ilr->xpath("/Learner/LearnerContact[ContType='1' and LocType='2']/PostCode"); $ppe = (empty($xpath))?'':$xpath[0];
            $user->home_postcode = $ppe;
            $user->home_telephone = $tel;
            $user->uln = $ilr->ULN;
            $user->l24 = $ilr->Domicile;
            $user->l14 = $ilr->LLDDHealthProb;
            $xpath = $ilr->xpath("/Learner/LLDDandHealthProblem[LLDDType='DS']/LLDDCode"); $ds = (empty($xpath))?'':$xpath[0];
            $xpath = $ilr->xpath("/Learner/LLDDandHealthProblem[LLDDType='LD']/LLDDCode"); $ld = (empty($xpath))?'':$xpath[0];
            $user->l15 = $ds;
            $user->l16 = $ld;
            $xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $lsr1 = (empty($xpath[0]))?'':(string)$xpath[0];
            $xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $lsr2 = (empty($xpath[1]))?'':(string)$xpath[1];
            $xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $lsr3 = (empty($xpath[2]))?'':(string)$xpath[2];
            $xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $lsr4 = (empty($xpath[3]))?'':(string)$xpath[3];
            $user->l34a = $lsr1;
            $user->l34b = $lsr2;
            $user->l34c = $lsr3;
            $user->l34d = $lsr4;
            $user->l35 = $ilr->PriorAttain;
            if($user->l35=='')
                $user->l35 = 1;
            $user->l36 = 0;
            $user->l39 = $ilr->Dest;
            $xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='NLM']/LearnFAMCode"); $nlm1 = (empty($xpath[0]))?'':(string)$xpath[0];
            $xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='NLM']/LearnFAMCode"); $nlm2 = (empty($xpath[1]))?'':(string)$xpath[1];
            $user->l40a = $nlm1;
            $user->l40b = $nlm2;
            $user->l45 = $ilr->ULN;
            $location_id = DAO::getSingleValue($link, "SELECT locations.id FROM locations INNER JOIN organisations ON organisations.id = locations.organisations_id WHERE organisations.id = '$employer_id';");
            $user->employer_id = $employer_id;
            $user->employer_location_id = $location_id;
            $user->type = 5;
            if($user->l39=='')
                $user->l39=95;
            $user->save($link, true);

        }
        return $found;
    }

    public function createEmployer($link, $learner)
    {
        $ilr = Ilr2012::loadFromXML($learner);
        $edrs='';
        $postcode = '';
        foreach($ilr->LearnerEmploymentStatus as $les)
        {
            $edrs = trim($les->EmpId);
            $postcode = trim($les->WorkLocPostCode);
        }
        if($edrs=='')
            $edrs='999999999';
        if($postcode=='')
            $postcode = DAO::getSingleValue($link, "select postcode from locations where organisations_id in (select id from organisations where organisation_type = 1) limit 0,1");
        $found = DAO::getSingleValue($link,"select id from organisations where edrs='$edrs'");
        if(!$found)
        {
            $e = new Employer();
            $e->edrs = $edrs;
            $e->legal_name = $edrs;
            $e->trading_name = $edrs;
            $e->active = 1;
            $e->save($link);
            $found = $e->id;

            $loc = new Location();
            $loc->short_name = "Main Site";
            $loc->full_name = "Main Site";
            $loc->organisations_id = $found;
            $loc->postcode = $postcode;
            $loc->is_legal_address = 1;
            $loc->save($link);
        }

        return $found;
    }
}
?>