<?php
class ViewFrameworksTrainingRecord extends View
{

    public static function getInstance($link, $tr_id, $framework_id)
    {
        $key = 'view_'.__CLASS__.$tr_id.'-'.$framework_id;

        if($tr_id=='')
            throw new Exception("Required Data missing");

//		if(!isset($_SESSION[$key]))
        if(true)
        {
            if(DB_NAME=="am_pathway" || DB_NAME=="am_reed_demo" || DB_NAME=="am_reed" || DB_NAME=="am_lcurve" || DB_NAME=="am_lead")
                $certificate_sent = " student_qualifications.certificate_sent, student_qualifications.certificate_no, student_qualifications.paperwork_received_date,";
            else
                $certificate_sent = " ";
            if(DB_NAME=="am_lcurve")
            {
                $enrolment_form_sent = " student_qualifications.enrolment_form_sent, ";
                $leaver_pprwrk_sent = " student_qualifications.leaver_pprwrk_sent, ";
            }
            else
            {
                $enrolment_form_sent = " ";
                $leaver_pprwrk_sent = " ";
            }
            $pending = "";
            if(in_array(DB_NAME, ["am_ela"]))
            {
                $pending = " student_qualifications.pending, ";
            }
            $compstatus = "";
            if(DB_NAME=="am_reed" || DB_NAME=="am_reed_demo")
                $compstatus = " student_qualifications.compstatus, ";
            // Create new view object
            $sql = <<<HEREDOC
select 
    timestampdiff(MONTH, student_qualifications.start_date, CURDATE()) as cmonth,
	if(student_qualifications.end_date<CURDATE(),1,0) as passed,
	student_qualifications.tr_id,
	student_qualifications.internaltitle,
	student_qualifications.id,
	student_qualifications.framework_id,
	student_qualifications.units,
	student_qualifications.unitsBehind,
	student_qualifications.unitsOnTrack,
	student_qualifications.unitsCompleted,
	organisations.legal_name,
	student_qualifications.qualification_type,
	student_qualifications.level,
	student_qualifications.start_date,
	student_qualifications.end_date,
	student_qualifications.actual_end_date,
	student_qualifications.achievement_date,
	$compstatus
	student_qualifications.proportion,
	student_qualifications.awarding_body_date,
	student_qualifications.awarding_body_reg,
	student_qualifications.certificate_applied,
	student_qualifications.certificate_received,
	$certificate_sent
	$enrolment_form_sent
	$leaver_pprwrk_sent
	IF(student_qualifications.unitsUnderAssessment>100,100,student_qualifications.unitsUnderAssessment) as unitsUnderAssessment,
	student_qualifications.unitsPercentage,
	course_qualifications_dates.tutor_username,
	tr.assessor,
	tr.tutor,
	student_qualifications.aptitude,
	contracts.id as contract_id,
	contracts.contract_year,
	contracts.contract_type,
	(select group_concat(old_tutor) from groups where id in (select groups_id from group_members where tr_id = student_qualifications.tr_id)) as old_tutor,
    certificate_no,
    certificate_post_date,
    awarding_body_expiry_date,
    candidate_no,
	$pending
    awarding_body_batch
from student_qualifications 
	LEFT JOIN courses_tr on courses_tr.tr_id = student_qualifications.tr_id
	LEFT JOIN course_qualifications_dates on course_qualifications_dates.course_id = courses_tr.course_id and 
		course_qualifications_dates.qualification_id = student_qualifications.id and
		course_qualifications_dates.framework_id = student_qualifications.framework_id and
		course_qualifications_dates.internaltitle = student_qualifications.internaltitle 
	LEFT JOIN organisations on organisations.id = course_qualifications_dates.provider_id
	LEFT JOIN tr on tr.id = student_qualifications.tr_id
	LEFT JOIN contracts on contracts.id = tr.contract_id	
where student_qualifications.tr_id = '$tr_id' and student_qualifications.framework_id!='0'
ORDER BY student_qualifications.auto_id
HEREDOC;

            $view = $_SESSION[$key] = new ViewFrameworksTrainingRecord();
            $view->setSQL($sql);
            $view->tr_id = $tr_id; // Khushnood
            // Add view filters
            $options = array(
                0=>array(20,20,null,null),
                1=>array(50,50,null,null),
                2=>array(100,100,null,null),
                3=>array(0,'No limit',null,null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

            if(in_array(DB_NAME, ["am_ela", "am_demo", "am_crackerjack"]))
			{
				$options = array(
					0=>array(1, '', null, 'ORDER BY student_qualifications.auto_id, student_qualifications.title'),
					1=>array(2, '', null, 'ORDER BY student_qualifications.title DESC'));
				$f = new DropDownViewFilter('order_by', $options, 1, false);
				$f->setDescriptionFormat("Sort by: %s");
				$view->addFilter($f);
			}
			else
			{
				$options = array(
					//	0=>array(1, 'Type (asc), Level (asc)', null, 'ORDER BY title, level'),
					//	1=>array(2, 'Type (desc), Level (desc)', null, 'ORDER BY title DESC, level DESC'));
					0=>array(1, '', null, 'ORDER BY student_qualifications.auto_id'),
					1=>array(2, '', null, 'ORDER BY student_qualifications.title DESC'));
				$f = new DropDownViewFilter('order_by', $options, 1, false);
				$f->setDescriptionFormat("Sort by: %s");
				$view->addFilter($f);
			}
        }

        return $_SESSION[$key];
    }


    public function render(PDO $link)
    {
        /* @var $result pdo_result */
        $st = $link->query($this->getSQL());
        if($st)
        {
//			echo $this->getViewNavigator('left');
            echo '<div align="left"><table id="framework_table" class="resultset" border="0" cellspacing="0" cellpadding="6">';
            /*			echo '<thead><tr><th>&nbsp;</th><th>Title</th><th>Start Date</th><th>End Date</th><th>Total Units</th><th>Not Started</th><th>Behind</th><th>On Track</th><th>Under Assessment</th><th>Completed</th><th>No Status</th></tr></thead>';

                        echo '<tbody>';
                        while($row = $fetch())
                        {

                            $tr = $this->tr_id;

                            $que = "select sum(units) from student_qualifications where tr_id='$tr' and framework_id={$row['id']}";
                            $total_units = trim(DAO::getSingleValue($link, $que));

                            $que = "select sum(unitscompleted) from student_qualifications where tr_id='$tr' and framework_id={$row['id']}";
                            $units_completed = trim(DAO::getSingleValue($link, $que));

                            $que = "select sum(unitsNotStarted) from student_qualifications where tr_id='$tr' and framework_id={$row['id']}";
                            $units_not_started = trim(DAO::getSingleValue($link, $que));

                            $que = "select sum(unitsBehind) from student_qualifications where tr_id='$tr' and framework_id={$row['id']}";
                            $units_behind = trim(DAO::getSingleValue($link, $que));

                            $que = "select sum(unitsOnTrack) from student_qualifications where tr_id='$tr' and framework_id={$row['id']}";
                            $units_on_track = trim(DAO::getSingleValue($link, $que));

                            $que = "select sum(unitsUnderAssessment) from student_qualifications where tr_id='$tr' and framework_id={$row['id']}";
                            $units_under_assessment = trim(DAO::getSingleValue($link, $que));

                            if($total_units=='')
                                $total_units=0;

                            if($units_completed=='')
                                $units_completed=0;

                            if($units_not_started=='')
                                $units_not_started=0;

                            if($units_behind=='')
                                $units_behind=0;

                            if($units_on_track=='')
                                $units_on_track=0;

                            if($units_under_assessment=='')
                                $units_under_assessment=0;

                                $units_no_status = $total_units - $units_behind - $units_completed - $units_not_started - $units_on_track - $units_under_assessment;

                            echo HTML::viewrow_opening_tag('do.php?_action=view_student_qualifications&id=' . rawurlencode((string) $row['id']) . '&tr_id=' . rawurlencode((string) $this->tr_id));
                            echo '<td><img src="/images/rosette.gif" /></td>';
                            echo '<td align="left">' . HTML::cell($row['title']) . "</td>";
                            echo '<td align="left">' . HTML::cell($row['start_date']) . "</td>";
                            echo '<td align="left">' . HTML::cell($row['end_date']) . "</td>";
                            echo '<td align="center">' . HTML::cell($total_units) . "</td>";
                            echo '<td align="center">' . HTML::cell($units_not_started) . "</td>";
                            echo '<td align="center">' . HTML::cell($units_behind) . "</td>";
                            echo '<td align="center">' . HTML::cell($units_on_track) . "</td>";
                            echo '<td align="center">' . HTML::cell($units_under_assessment) . "</td>";
                            echo '<td align="center">' . HTML::cell($units_completed) . "</td>";
                            echo '<td align="center">' . HTML::cell($units_no_status) . "</td>";
                            echo '</tr>';
                        }
                        echo '</tbody></table></div align="left">';*/
            if(DB_NAME=='ams' || DB_NAME=='am_lmpqswift' || DB_NAME=='am_lead' || DB_NAME=='am_lead_demo' || DB_NAME=='am_morthying')
                echo '<thead><tr><th colspan=8>Qualifications</th><th class="unit_status" style="display: none;" colspan=5>Unit Status</th><th class="additional_dates" style="display: none;" colspan=7>Additional Dates<span class="button" button type="button" style="display: none;"  onclick="saveAdditionalDates();">Save</span></th><th colspan=4>Progress</th><th colspan=4>Navigate to</th><th colspan=1>Action</th></tr>';
            elseif(DB_NAME=="am_pathway"  || DB_NAME=="am_reed_demo" || DB_NAME=="am_reed")
                echo '<thead><tr><th colspan=8>Qualifications</th><th class="unit_status" style="display: none;" colspan=5>Unit Status</th><th class="additional_dates" style="display: none;" colspan=5>Additional Dates<span class="button" button type="button" style="display: none;"  onclick="saveAdditionalDates();">Save</span></th><th colspan=4>Progress</th><th colspan=3>Navigate to</th><th colspan=1>Action</th></tr>';
            elseif(DB_NAME=='am_ela')
                echo '<thead><tr><th colspan=8>Qualifications</th><th class="unit_status" style="display: none;" colspan=5>Unit Status</th><th class="additional_dates" style="display: none;" colspan=9>Awarding Body Information<span class="button" button type="button" style="display: none;" onclick="saveAdditionalDates();">Save</span></th><th colspan=4>Progress</th><th colspan=3>Navigate to</th><th colspan=1>Action</th></tr>';
            else
                echo '<thead><tr><th colspan=8>Qualifications</th><th class="unit_status" style="display: none;" colspan=5>Unit Status</th><th class="additional_dates" style="display: none;" colspan=5>Awarding Body Information<span class="button" button type="button" style="display: none;" onclick="saveAdditionalDates();">Save</span></th><th colspan=4>Progress</th><th colspan=3>Navigate to</th><th colspan=1>Action</th></tr>';

            echo '<tr><th>Internal Title</th><th>Type</th><th>Level</th><th>QAN</th><th>Start Date</th><th>Target End Date</th><th>Actual End Date</th><th>Achievement Date</th><th class="unit_status" style="display: none;" title="Total Units">T</th><th class="unit_status" style="display: none;" title="Units Not Started">N</th><th class="unit_status" style="display: none;" title="Units Behind">B</th><th class="unit_status" style="display: none;" title="Units On Track">O</th><th class="unit_status" style="display: none;" title="Units Completed">C</th>';
            if(DB_NAME=="am_lead")
            {

                echo '<th class="additional_dates" style="display: none;" title="Awarding Body Registration Date">ABR Date<br>';
                if($_SESSION['user']->type != 12)
                    echo '<span title="Copy Awarding Body Registration Date" id="ABRDateFill" class="button" button type="button" onmousedown="updateABRDate();">Fill</span><span style="display: none" title="Save Awarding Body Registration date" id="ABRDateSave" class="button" button type="button" onclick="saveABRDate();">Save</span></th>';

                echo '<th class="additional_dates" style="display: none;" title="Awarding Body Registration number">ABR No<br>';
                if($_SESSION['user']->type != 12)
                    echo '<span title="Copy Awarding Body Registration number" id="ABRNoFill" class="button" button type="button" onmousedown="updateABRNo();">Fill</span><span style="display: none" title="Save Awarding Body Registration number" id="ABRNoSave" class="button" button type="button" onclick="saveABRNo();">Save</span></th>';

                echo '<th class="additional_dates" style="display: none;" title="Papaerwork Received Date">Paperwork Received Date<br>';
                if($_SESSION['user']->type != 12)
                    echo '<span title="Copy Papaerwork Received Date" id="PWRDateFill" class="button" button type="button" onmousedown="updatePWRDate();">Fill</span><span style="display: none" title="Save Paperwork Received Date" id="PWRDateSave" class="button" button type="button" onclick="savePWRDate();">Save</span></th>';

                echo '<th class="additional_dates" style="display: none;" title="Certificate number">Certificate No<br>';
                if($_SESSION['user']->type != 12)
                    echo '<span title="Copy Certificate number" id="CerNoFill" class="button" button type="button" onmousedown="updateCerNo();">Fill</span><span style="display: none" title="Save Certificate number" id="CerNoSave" class="button" button type="button" onclick="saveCerNo();">Save</span></th>';

                echo '<th class="additional_dates" style="display: none;" title="Date Certificate Applied for">Cert App Date<br>';
                if($_SESSION['user']->type != 12)
                    echo '<span title="Copy Date Certificate Applied for" id="CertAppDateFill" class="button" button type="button" onmousedown="updateCertAppDate();">Fill</span><span style="display: none" title="Save certificate applied for date" id="CertAppDateSave" class="button" button type="button" onclick="saveCertAppDate();">Save</span></th>';

                echo '<th class="additional_dates" style="display: none;" title="Date Certificate Received">Cert Rec Date<br>';
                if($_SESSION['user']->type != 12)
                    echo '<span title="Copy Certificate Received" id="CertRecDateFill" class="button" button type="button" onmousedown="updateCertRecDate();">Fill</span><span style="display: none" title="Save certificate received date" id="CertRecDateSave" class="button" button type="button" onclick="saveCertRecDate();">Save</span></th>';

                echo '<th class="additional_dates" style="display: none;" title="Date Certificate Processed to College">Processed to College Date<br>';
                if($_SESSION['user']->type != 12)
                    echo '<span title="Copy Certificate Sent Date" id="CertSentDateFill" class="button" button type="button" onmousedown="updateCertSentDate();">Fill</span><span style="display: none" title="Save certificate sent date" id="CertSentDateSave" class="button" button type="button" onclick="saveCertSentDate();">Save</span></th>';
            }
            elseif(DB_NAME=='am_ela')
            {
                echo '<th class="additional_dates" style="display: none;" title="Awarding Body Registration Date">ABR Date<br>';
                if($_SESSION['user']->type != 12)
                    echo '<span title="Copy Awarding Body Registration Date" id="ABRDateFill" class="button" button type="button" onmousedown="updateABRDate();">Fill</span><span style="display: none" title="Save Awarding Body Registration date" id="ABRDateSave" class="button" button type="button" onclick="saveABRDate();">Save</span></th>';
                echo '<th class="additional_dates" style="display: none;" title="Awarding Body Registration number">ABR No<br>';
                if($_SESSION['user']->type != 12)
                    echo '<span title="Copy Awarding Body Registration number" id="ABRNoFill" class="button" button type="button" onmousedown="updateABRNo();">Fill</span><span style="display: none" title="Save Awarding Body Registration number" id="ABRNoSave" class="button" button type="button" onclick="saveABRNo();">Save</span></th>';
                echo '<th class="additional_dates" style="display: none;" title="Date Certificate Applied for">Cert App Date<br>';
                if($_SESSION['user']->type != 12)
                    echo '<span title="Copy Date Certificate Applied for" id="CertAppDateFill" class="button" button type="button" onmousedown="updateCertAppDate();">Fill</span><span style="display: none" title="Save certificate applied for date" id="CertAppDateSave" class="button" button type="button" onclick="saveCertAppDate();">Save</span></th>';
                echo '<th class="additional_dates" style="display: none;" title="Date Certificate Received">Cert Rec Date<br>';
                if($_SESSION['user']->type != 12)
                    echo '<span title="Copy Certificate Received" id="CertRecDateFill" class="button" button type="button" onmousedown="updateCertRecDate();">Fill</span><span style="display: none" title="Save certificate received date" id="CertRecDateSave" class="button" button type="button" onclick="saveCertRecDate();">Save</span></th>';
                echo '<th class="additional_dates" style="display: none;" title="Certificate number">Certificate No<br>';
                if($_SESSION['user']->type != 12)
                    echo '<span title="Copy Certificate number" id="CerNoFill" class="button" button type="button" onmousedown="updateCerNo();">Fill</span><span style="display: none" title="Save Certificate number" id="CerNoSave" class="button" button type="button" onclick="saveCerNo();">Save</span></th>';

                echo '<th class="additional_dates" style="display: none;" title="Certificate post date">Certificate Post Date<br>';
                echo '<th class="additional_dates" style="display: none;" title="Expiry Date">Expiry Date<br>';
                echo '<th class="additional_dates" style="display: none;" title="Batch No">Batch No<br>';
                echo '<th class="additional_dates" style="display: none;" title="Candidate No">Candidate No<br>';
            }
            else
            {
                echo '<th class="additional_dates" style="display: none;" title="Awarding Body Registration Date">ABR Date<br>';
                if($_SESSION['user']->type != 12)
                    echo '<span title="Copy Awarding Body Registration Date" id="ABRDateFill" class="button" button type="button" onmousedown="updateABRDate();">Fill</span><span style="display: none" title="Save Awarding Body Registration date" id="ABRDateSave" class="button" button type="button" onclick="saveABRDate();">Save</span></th>';
                echo '<th class="additional_dates" style="display: none;" title="Awarding Body Registration number">ABR No<br>';
                if($_SESSION['user']->type != 12)
                    echo '<span title="Copy Awarding Body Registration number" id="ABRNoFill" class="button" button type="button" onmousedown="updateABRNo();">Fill</span><span style="display: none" title="Save Awarding Body Registration number" id="ABRNoSave" class="button" button type="button" onclick="saveABRNo();">Save</span></th>';
                echo '<th class="additional_dates" style="display: none;" title="Date Certificate Applied for">Cert App Date<br>';
                if($_SESSION['user']->type != 12)
                    echo '<span title="Copy Date Certificate Applied for" id="CertAppDateFill" class="button" button type="button" onmousedown="updateCertAppDate();">Fill</span><span style="display: none" title="Save certificate applied for date" id="CertAppDateSave" class="button" button type="button" onclick="saveCertAppDate();">Save</span></th>';
                echo '<th class="additional_dates" style="display: none;" title="Date Certificate Received">Cert Rec Date<br>';
                if($_SESSION['user']->type != 12)
                    echo '<span title="Copy Certificate Received" id="CertRecDateFill" class="button" button type="button" onmousedown="updateCertRecDate();">Fill</span><span style="display: none" title="Save certificate received date" id="CertRecDateSave" class="button" button type="button" onclick="saveCertRecDate();">Save</span></th>';
                echo '<th class="additional_dates" style="display: none;" title="Certificate number">Certificate No<br>';
                if($_SESSION['user']->type != 12)
                    echo '<span title="Copy Certificate number" id="CerNoFill" class="button" button type="button" onmousedown="updateCerNo();">Fill</span><span style="display: none" title="Save Certificate number" id="CerNoSave" class="button" button type="button" onclick="saveCerNo();">Save</span></th>';
            }


            if(DB_NAME=="am_pathway"  || DB_NAME=="am_reed_demo" || DB_NAME=="am_reed" || DB_NAME=="am_lcurve" )
            {
                echo '<th class="additional_dates" style="display: none;" title="Date Certificate Sent">Cert Sent Date<br>';
                if($_SESSION['user']->type != 12)
                    echo '<span title="Copy Certificate Sent Date" id="CertSentDateFill" class="button" button type="button" onmousedown="updateCertSentDate();">Fill</span><span style="display: none" title="Save certificate sent date" id="CertSentDateSave" class="button" button type="button" onclick="saveCertSentDate();">Save</span></th>';
            }
            echo '<th>Proportion</th><th>Target</th><th>% Achieved</th><th>Status</th>';

            if(DB_NAME!='am_tmuk')
                echo '<th>Edit</th>';
            if(DB_NAME!='am_stamford')
                echo '<th>Matrix</th>';
            echo '<th>Tabular</th>';

            if(DB_NAME=='ams' || DB_NAME=='am_lmpqswift' || DB_NAME=='am_lead')
                echo '<th>ILR</th>';



            /*            if(DB_NAME=='ams' || DB_NAME=='am_dv8training' || DB_NAME=='am_lead' || DB_NAME=='am_lead_demo' || DB_NAME=='am_morthying')
                   echo '<thead><tr><th colspan=8>Qualifications</th><th class="unit_status" style="display: none;" colspan=5>Unit Status</th><th class="additional_dates" style="display: none;" colspan=4>Additional Dates</th><th colspan=4>Progress</th><th colspan=5>Navigate to</th></tr>';
               else
                   echo '<thead><tr><th colspan=8>Qualifications</th><th class="unit_status" style="display: none;" colspan=5>Unit Status</th><th class="additional_dates" style="display: none;" colspan=4>Additional Dates</th><th colspan=4>Progress</th><th colspan=4>Navigate to</th></tr>';

               echo '<tr><th>Internal Title</th><th>Type</th><th>Level</th><th>QAN</th><th>Start Date</th><th>Target End Date</th><th>Actual End Date</th><th>Achievement Date</th><th class="unit_status" style="display: none;" title="Total Units">T</th><th class="unit_status" style="display: none;" title="Units Not Started">N</th><th class="unit_status" style="display: none;" title="Units Behind">B</th><th class="unit_status" style="display: none;" title="Units On Track">O</th><th class="unit_status" style="display: none;" title="Units Completed">C</th><th class="additional_dates" style="display: none;" title="Awarding Body Registration Date">ABR Date</th><th class="additional_dates" style="display: none;" title="Awarding Body Registration number">ABR No:</th><th class="additional_dates" style="display: none;" title="Date Certificate Applied for">Cert App Date</th><th class="additional_dates" style="display: none;" title="Date Certificate Received">Cert Rec Date</th><th>Proportion</th><th>Target</th><th>% Achieved</th><th>Status</th>';

               if(DB_NAME!='am_tmuk')
                   echo '<th>Edit</th>';
               if(DB_NAME!='am_stamford')
                   echo '<th>Matrix</th>';

               echo '<th>Tabular</th>';

               if(DB_NAME=='ams' || DB_NAME=='am_dv8training' || DB_NAME=='am_lead')
                   echo '<th>ILR</th>';
   //			echo '</tr></thead>';

   */

            echo '<tbody>';
            $total_units = 0;
            $total_not_started = 0;
            $total_behind = 0;
            $total_on_track = 0;
            $total_completed = 0;
            $total_target = 0;
            $total_percentage = 0;

            while($row = $st->fetch())
            {


                $que = "select title from student_frameworks where tr_id={$row['tr_id']}";
                $framework_title = trim((string) DAO::getSingleValue($link, $que));

                $qual_id = $row['id'];

                if(!isset($row['cmonth']))
                    $row['cmonth'] = 100;

                $current_month_since_study_start_date = $row['cmonth'];

                $month = "month_" . ($current_month_since_study_start_date);

                $internaltitle = $row['internaltitle'];

                if(!isset($row['passed']))
                    $row['passed'] = 0;

                /*if($row['passed']=='1')
                    $target = 100;
                else
                    if($current_month_since_study_start_date>=1 && $current_month_since_study_start_date<=36)
                    {// Calculating target month and target
                        $internaltitle = addslashes((string)$internaltitle);
                        $que = "select avg($month) from student_milestones LEFT JOIN student_qualifications ON student_qualifications.id = student_milestones.qualification_id AND student_qualifications.tr_id = student_milestones.tr_id where student_qualifications.aptitude!=1 and chosen=1 and qualification_id='$qual_id' and student_milestones.internaltitle='$internaltitle' and student_milestones.tr_id={$row['tr_id']}";
                        $target = trim((string) DAO::getSingleValue($link, $que));
                    }
                    else
                        $target='0';
                */

                $target = 50;

                //pre($que);

                $tdate = new Date($row['end_date']);
                $cdate = new Date(date('d-m-Y'));
                if($cdate->getDate()>=$tdate->getDate())
                    $target = 100;

                $sdate = new Date($row['start_date']);
                if($cdate->getDate() < $sdate->getDate())
                    $target = 0;


                $que = "select DATE_FORMAT(target_date,'%d/%m/%Y') from tr where id={$row['tr_id']}";
                $end_date = trim((string) DAO::getSingleValue($link, $que));

                $unitsnostatus = $row['units'] - $row['unitsBehind'] - $row['unitsOnTrack'] - $row['unitsCompleted'];

                //echo HTML::viewrow_opening_tag('do.php?_action=read_student_qualification&qualification_id=' . rawurlencode((string) $row['id']).'&internaltitle='.rawurlencode((string) $row['internaltitle']).'&framework_id='.rawurlencode((string) $row['framework_id']).'&tr_id='.rawurlencode((string) $this->tr_id));
                if($row['aptitude']==1)
					echo '<tr style="text-decoration: line-through;">';
				else
					echo '<tr>';

                //	echo '<td><img src="/images/rosette.gif" /></td>';
                //	echo '<td align="left">' . HTML::cell($row['legal_name']) . "</td>";
                // What is the latest submission
                $contract_year = $row['contract_year'];
                $contract_id = $row['contract_id'];
                $tr_id = $row['tr_id'];
                $co = Contract::loadFromDatabase($link, $contract_id);
                $submission = DAO::getSingleValue($link, "SELECT submission FROM ilr INNER JOIN contracts on contracts.id = ilr.contract_id where tr_id = '$tr_id' AND (CONCAT(submission,contract_year) IN (SELECT CONCAT(submission,contract_year) FROM central.lookup_submission_dates WHERE last_submission_date >= CURDATE() and contract_type='$co->funding_body' ORDER BY last_submission_date)) Limit 0,1;");
                $ilr = DAO::getSingleValue($link, "select ilr from ilr inner join contracts on contracts.id = ilr.contract_id where tr_id = '$tr_id' order by contract_year desc, submission desc limit 0,1");
                $flag = "NF";
                if($contract_year<2012)
                {
                    if($ilr!='')
                    {
                        //$pageDom->loadXML($ilr);
                        $pageDom = XML::loadXmlDom($ilr);
                        $e = $pageDom->getElementsByTagName('main');
                        foreach($e as $node)
                        {
                            if(str_replace("/","",$row['id']) == $node->getElementsByTagName('A09')->item(0)->nodeValue)
                                $flag="F";
                        }
                        $e = $pageDom->getElementsByTagName('subaim');
                        foreach($e as $node)
                        {
                            if(str_replace("/","",$row['id']) == $node->getElementsByTagName('A09')->item(0)->nodeValue)
                                $flag="F";
                        }
                    }
                }
                else
                {
                    if($ilr!='')
                    {
                        //$pageDom->loadXML($ilr);
                        $pageDom = XML::loadXmlDom($ilr);
                        $e = $pageDom->getElementsByTagName('LearningDelivery');
                        foreach($e as $node)
                        {
                            if(str_replace("/","",$row['id']) == $node->getElementsByTagName('LearnAimRef')->item(0)->nodeValue)
                                $flag="F";
                        }
                    }
                }

				if(in_array(DB_NAME, ["am_ela"]))
				{
					$exclude_check = DAO::getSingleValue($link, "SELECT EXTRACTVALUE(ilr, 'Learner/LearningDelivery[LearnAimRef=\"{$row['id']}\"]/Exclude') AS Exclude FROM ilr WHERE tr_id = '{$row['tr_id']}' ORDER BY contract_id DESC, submission DESC LIMIT 0, 1;");
					echo trim((string) $exclude_check) == '1' ? '<tr style="color: silver">' : '<tr>';
				}
				else
				{
					echo '<tr>';
				}
                echo '<td align="left">' . HTML::cell($row['internaltitle']) . "</td>";
                echo '<td align="center">' . HTML::cell($row['qualification_type']) . "</td>";
                echo '<td align="center">' . htmlspecialchars((string)$row['level']) . "</td>";
                echo '<td align="left">' . htmlspecialchars((string)$row['id']) . "</td>";
                if(DB_NAME!="am_reed" && DB_NAME!="am_reed_demo")
                {
                    echo '<td align="center">' . htmlspecialchars(date_format(date_create((string) $row['start_date']),"d/m/Y")) . "</td>";
                    echo '<td align="center">' . htmlspecialchars(date_format(date_create((string) $row['end_date']),"d/m/Y")) . "</td>";
                }
                else
                {
                    echo '<td align="center">' . htmlspecialchars(Date::to($row['start_date'], Date::SHORT)) . "</td>";
                    echo '<td align="center">' . htmlspecialchars(Date::to($row['end_date'], Date::SHORT)) . "</td>";
                }
                if($row['actual_end_date']!='')
                    echo '<td align="center">' . HTML::cell(htmlspecialchars(date_format(date_create((string) $row['actual_end_date']),"d/m/Y"))) . "</td>";
                else
                    echo '<td align="center">' . htmlspecialchars('') . "</td>";
                if($row['achievement_date']!='')
                    echo '<td align="center">' . HTML::cell(htmlspecialchars(date_format(date_create((string) $row['achievement_date']),"d/m/Y"))) . "</td>";
                else
                    echo '<td align="center">' . htmlspecialchars('') . "</td>";

//				echo '<td align="left">' . htmlspecialchars((string)$row['lsc_learning_aim']) . "</td>";
                echo '<td class="unit_status" style="display: none;" align="center">' . htmlspecialchars((string)$row['units']) . "</td>";
                echo '<td class="unit_status" style="display: none;" align="center">' . htmlspecialchars((string)$unitsnostatus) . "</td>";
                echo '<td class="unit_status" style="display: none;" align="center">' . htmlspecialchars((string)$row['unitsBehind']) . "</td>";
                echo '<td class="unit_status" style="display: none;" align="center">' . htmlspecialchars((string)$row['unitsOnTrack']) . "</td>";
//				echo '<td align="center">' . htmlspecialchars((string)$row['unitsUnderAssessment']) . "</td>";
                echo '<td class="unit_status" style="display: none;" align="center">' . htmlspecialchars((string)$row['unitsCompleted']) . "</td>";

                $id = str_replace("/","",$row['id']);

                echo '<td class="additional_dates" style="display: none;"> <input placeholder="dd/mm/yyyy" id="qualificationABRDate' . $id  . '" style="text-align: center;" onchange="$(\'#ABRDateSave\').show()"  size = 8 maxlength = 10 value="' . Date::toShort($row['awarding_body_date']) . '"></input>';
                echo '</td>';
                echo '<td class="additional_dates" style="display: none;"> <input id="qualificationABReg' . $id  . '" style="text-align: center;" onchange="$(\'#ABRNoSave\').show()"  size = 8 maxlength = 10 value="' . $row['awarding_body_reg'] . '"></input>';
                echo '</td>';
                if(DB_NAME=="am_pathway" || DB_NAME=="am_reed_demo" || DB_NAME=="am_reed" || DB_NAME=="am_lcurve" || DB_NAME=="am_lead")
                {
                    echo '<td class="additional_dates" style="display: none;"> <input placeholder="dd/mm/yyyy" id="qualificationPWRDate' . $id  . '" style="text-align: center;" onchange="$(\'#PWRDateSave\').show()"  size = 8 maxlength = 10 value="' . Date::toShort($row['paperwork_received_date']) . '"></input>';
                    echo '</td>';
                    echo '<td class="additional_dates" style="display: none;"> <input id="qualificationCer' . $id  . '" style="text-align: center;" onchange="$(\'#CerNoSave\').show()"  size = 8 maxlength = 10 value="' . $row['certificate_no'] . '"></input>';
                    echo '</td>';
                }
                echo '<td class="additional_dates" style="display: none;"> <input placeholder="dd/mm/yyyy" id="qualificationCertApp' . $id  . '" style="text-align: center;" onchange="$(\'#CertAppDateSave\').show()"  size = 8 maxlength = 10 value="' . Date::toShort($row['certificate_applied']) . '"></input>';
                echo '</td>';
                echo '<td class="additional_dates" style="display: none;"> <input placeholder="dd/mm/yyyy" id="qualificationCertRec' . $id  . '" style="text-align: center;" onchange="$(\'#CertRecDateSave\').show()"  size = 8 maxlength = 10 value="' . Date::toShort($row['certificate_received']) . '"></input>';
                echo '</td>';
                if(DB_NAME=="am_pathway" || DB_NAME=="am_reed_demo" || DB_NAME=="am_reed" || DB_NAME=="am_lcurve" || DB_NAME=="am_lead")
                {
                    echo '<td class="additional_dates" style="display: none;"> <input placeholder="dd/mm/yyyy" id="qualificationCertSent' . $id  . '" style="text-align: center;" onchange="$(\'#CertSentDateSave\').show()"  size = 8 maxlength = 10 value="' . Date::toShort($row['certificate_sent']) . '"></input>';
                    echo '</td>';
                }
                if(DB_NAME=="am_lcurve")
                {
                    echo '<td class="additional_dates" style="display: none;"> <input placeholder="dd/mm/yyyy" id="enrolmentFormSent' . $id  . '" style="text-align: center;" onchange="$(\'#EnrolFormSentDateSave\').show()"  size = 8 maxlength = 10 value="' . Date::toShort($row['enrolment_form_sent']) . '"></input>';
                    echo '</td>';
                    echo '<td class="additional_dates" style="display: none;"> <input placeholder="dd/mm/yyyy" id="LeaverPPRWRKSent' . $id  . '" style="text-align: center;" onchange="$(\'#LeaverPPRWRKSentDateSave\').show()"  size = 8 maxlength = 10 value="' . Date::toShort($row['leaver_pprwrk_sent']) . '"></input>';
                    echo '</td>';
                }
                echo '<td class="additional_dates" style="display: none;"> <input id="qualificationCer' . $id  . '" style="text-align: center;" onchange="$(\'#CerNoSave\').show()"  size = 8 maxlength = 10 value="' . $row['certificate_no'] . '"></input>';
                echo '</td>';

                if(DB_NAME=="am_ela")
                {
                    echo '<td class="additional_dates" style="display: none;"> <input placeholder="dd/mm/yyyy" id="enrolmentFormSent' . $id  . '" style="text-align: center;" onchange="$(\'#CertificatePostDateSave\').show()"  size = 8 maxlength = 10 value="' . Date::toShort($row['certificate_post_date']) . '"></input>';
                    echo '</td>';
                    echo '<td class="additional_dates" style="display: none;"> <input placeholder="dd/mm/yyyy" id="ABRExpiryDate' . $id  . '" style="text-align: center;" onchange="$(\'#ABRExpiryDateSave\').show()"  size = 8 maxlength = 10 value="' . Date::toShort($row['awarding_body_expiry_date']) . '"></input>';
                    echo '</td>';
                    echo '<td class="additional_dates" style="display: none;"> <input id="awarding_body_batch' . $id  . '" style="text-align: center;" onchange="$(\'#CerNoSave\').show()"  size = 8 maxlength = 10 value="' . $row['awarding_body_batch'] . '"></input>';
                    echo '</td>';
                    echo '<td class="additional_dates" style="display: none;"> <input id="candidate_no' . $id  . '" style="text-align: center;" onchange="$(\'#CerNoSave\').show()"  size = 8 maxlength = 10 value="' . $row['candidate_no'] . '"></input>';
                    echo '</td>';
                }



                /*				echo '<td class="additional_dates" style="display: none;"> <input id="qualificationABRDate' . $id  . '" style="text-align: center;" onchange="saveABRDate(\'' . $id . '\')"  size = 8 maxlength = 10 value="' . Date::toShort($row['awarding_body_date']) . '"></input>';
                    echo '<span style= "display: none; width: 25px" id="saveABRDateButton' . $id . '" class="button" onclick="saveABRDateFunc(\'' . $id .  '\');">Save</span>';
                    echo '</td>';
                    echo '<td class="additional_dates" style="display: none;"> <input id="qualificationABReg' . $id  . '" style="text-align: center;" onchange="saveABReg(\'' . $id . '\')"  size = 8 maxlength = 10 value="' . $row['awarding_body_reg'] . '"></input>';
                    echo '<span style= "display: none; width: 25px" id="saveABRegButton' . $id . '" class="button" onclick="saveABRegFunc(\'' . $id .  '\');">Save</span>';
                    echo '</td>';
                    echo '<td class="additional_dates" style="display: none;"> <input id="qualificationCertApp' . $id  . '" style="text-align: center;" onchange="saveCertApp(\'' . $id . '\')"  size = 8 maxlength = 10 value="' . Date::toShort($row['certificate_applied']) . '"></input>';
                    echo '<span style= "display: none; width: 25px" id="saveCertAppButton' . $id . '" class="button" onclick="saveCertAppFunc(\'' . $id .  '\');">Save</span>';
                    echo '</td>';
                    echo '<td class="additional_dates" style="display: none;"> <input id="qualificationCertRec' . $id  . '" style="text-align: center;" onchange="saveCertRec(\'' . $id . '\')"  size = 8 maxlength = 10 value="' . Date::toShort($row['certificate_received']) . '"></input>';
                    echo '<span style= "display: none; width: 25px" id="saveCertRecButton' . $id . '" class="button" onclick="saveCertRecFunc(\'' . $id .  '\');">Save</span>';
                    echo '</td>';

    */
                echo '<td align="center">' . htmlspecialchars((string)$row['proportion']) . "</td>";
                echo '<td align="center">' . htmlspecialchars(sprintf("%.2f",$target)) . "</td>";

                if(DB_NAME=='ams' || DB_NAME=='am_hybrid' || DB_NAME=='am_lead' || DB_NAME=='am_baltic' || DB_NAME=='am_direct' || DB_NAME=='am_lmpqswift' || DB_NAME=='am_beacon' || DB_NAME=='am_reed_demo' || DB_NAME=='am_jtj' || DB_NAME=='am_doncaster' || DB_NAME=='am_skillspoint' || DB_NAME=='am_tle' || DB_NAME=='am_southampton' || DB_NAME=='am_demo' || DB_NAME=='am_ligauk' || DB_NAME=='am_reed' || DB_NAME=='am_reed_demo' || DB_NAME=='am_traintogether' || DB_NAME=='am_pathway' || DB_NAME=='am_edudo' || DB_NAME=='am_lema' || DB_NAME=='am_siemens_demo' || DB_NAME=='am_siemens')
                {
                    //re - added in quotations for support requests 21933 & 21934
                    echo '<td> <input id="qualificationPercentage' . $id  . '" style="text-align: center;" onchange="savePercentage(\'' . $id . '\')" onKeyPress="return numbersonly(this, event)" size = 5 maxlength = 5 value="' . htmlspecialchars(sprintf("%.2f",$row['unitsUnderAssessment'])) . '"></input>';
                    echo '<span style= "display: none; width: 25px" id="savePercentageButton' . $id . '" class="button" onclick="saveQualificationPercentage(\'' . $id .  '\');">Save</span>';
                    echo '</td>';
                }
                else
                {
                    echo '<td align="center" title="Proportion towards framework: ' . $row['proportion'] . '">' . htmlspecialchars(sprintf("%.2f",$row['unitsUnderAssessment'])) . "</td>";
                }
//				echo '<td id = "percentageAchievedInput" style="display:none"></td>';

		$pendingIcon = "";
                $tdPos = "";
                if(in_array(DB_NAME, ["am_ela"]))
                {
                    $pendingIcon = $row['pending'] == 1 ? '<span title="Pending" style="position: absolute; bottom: 0; right: 0; color: red; font-size: 14px; font-weight: bold;">P</span>' : '';
                    $tdPos = $row['pending'] == 1 ? ' position: relative; ' : '';
                }
                $textStyle ='' ;
                if(DB_NAME=='am_doncaster' || DB_NAME=='am_southampton' || DB_NAME=='am_lewisham')
                    if($flag=="NF")
                        echo "<td align='center' style='border-right-style: solid;'> <img style='width:35px; height:35px;' src=\"/images/exempt2.png\" border=\"0\" /></td>";
                    else
                        if((int)$target>0 || (int)$row['unitsUnderAssessment']>0)
                            if((int)$row['unitsUnderAssessment']<(int)$target)
                                echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/red-cross.gif\" border=\"0\" /></td>";
                            else
                                echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/green-tick.gif\" border=\"0\" /></td>";
                        else
                            echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/green-tick.gif\" border=\"0\" /></td>";
                else
                    if($row['aptitude']==1)
                        echo "<td align='center' style='border-right-style: solid; {$tdPos}'> <img style='width:35px; height:35px;' src=\"/images/exempt.gif\" border=\"0\" /> {$pendingIcon}</td>";
                    else
                        if((int)$target>0 || (int)$row['unitsUnderAssessment']>0)
                            if((int)$row['unitsUnderAssessment']<(int)$target)
                                echo "<td align='center' style='border-right-style: solid; {$tdPos}'> <img src=\"/images/red-cross.gif\" border=\"0\" /> {$pendingIcon}</td>";
                            else
                                echo "<td align='center' style='border-right-style: solid; {$tdPos}'> <img src=\"/images/green-tick.gif\" border=\"0\" /> {$pendingIcon}</td>";
                        else
                            echo "<td align='center' style='border-right-style: solid; {$tdPos}'> <img src=\"/images/green-tick.gif\" border=\"0\" /> {$pendingIcon}</td>";


                $qualification_tutors = Array();
                $tutors = explode("," , (string) $row['tutor_username']);

                //throw new Exception($row['tutor_username']);

                if(DB_NAME=='am_imi' && (!$_SESSION['user']->isAdmin()))
                {
                    //		echo '<td align=center><img src="/images/read.jpg" title="View qualification tree" border="0" height="25px" width="25px" /></a></td>';
                    echo '<td align=center><img src="/images/edit.jpg" border="0" title="Edit qualification tree" height="25px" width="25px" /></a></td>';
                    echo '<td align=center><img src="/images/matrix.jpg" border="0" title="Mark progress through matrix" height="25px" width="25px" /></a></td>';
                    echo '<td align=center><img src="/images/tabular.jpg" border="0" title="Mark progress through data table" height="25px" width="25px" /></a></td>';
                }
                elseif($_SESSION['user']->isAdmin() || $_SESSION['user']->isOrgAdmin() || $this->isGroupTutor($link, $row['tr_id']) || $this->isGroupVerifier($link, $row['tr_id']) || $this->isGroupAssessor($link, $row['tr_id']) || in_array($_SESSION['user']->id, $tutors) || $_SESSION['user']->id == $row['assessor'] || $_SESSION['user']->id == $row['tutor'] || $_SESSION['user']->type==15 || $_SESSION['user']->type==4 || $_SESSION['user']->type==20 || $_SESSION['user']->type==8 || $_SESSION['user']->type==9 || $_SESSION['user']->type==21)
                {

                    //		echo '<td align=center><a href="do.php?_action=read_student_qualification&qualification_id=' . rawurlencode((string) $row['id']).'&internaltitle='.rawurlencode((string) $row['internaltitle']).'&framework_id='.rawurlencode((string) $row['framework_id']).'&tr_id='.rawurlencode((string) $this->tr_id) . '"><img src="/images/read.jpg" title="View qualification tree" border="0" height="25px" width="25px" /></a></td>';
                    echo '<td align=center><a href="do.php?_action=edit_student_qualification&qualification_id=' . rawurlencode((string) $row['id']).'&internaltitle='.rawurlencode((string) $row['internaltitle']).'&framework_id='.rawurlencode((string) $row['framework_id']).'&tr_id='.rawurlencode((string) $this->tr_id).'&target='.rawurlencode((string) $target).'&achieved='.rawurlencode((string) $row['unitsUnderAssessment']) . '"><img src="/images/edit.jpg" border="0" title="Edit qualification tree" height="25px" width="25px" /></a></td>';
                    echo '<td align=center><a href="do.php?_action=edit_tr_matrix&qualification_id=' . rawurlencode((string) $row['id']).'&internaltitle='.rawurlencode((string) $row['internaltitle']).'&framework_id='.rawurlencode((string) $row['framework_id']).'&tr_id='.rawurlencode((string) $this->tr_id).'&target='.rawurlencode((string) $target).'&achieved='.rawurlencode((string) $row['unitsUnderAssessment']) . '"><img src="/images/matrix.jpg" border="0" title="Mark progress through matrix" height="25px" width="25px" /></a></td>';
                    echo '<td align=center><a href="do.php?_action=view_tr_qualification_tabular&qualification_id=' . rawurlencode((string) $row['id']).'&internaltitle='.rawurlencode((string) $row['internaltitle']).'&view=COMPACT&framework_id='.rawurlencode((string) $row['framework_id']).'&tr_id='.rawurlencode((string) $this->tr_id).'&target='.rawurlencode((string) $target).'&achieved='.rawurlencode((string) $row['unitsUnderAssessment']) . '"><img src="/images/tabular.jpg" border="0" title="Mark progress through data table" height="25px" width="25px" /></a></td>';
                    if(DB_NAME=="am_ray_recruit" || DB_NAME=="am_demo")
                        echo '<td align=center><a href="do.php?_action=view_tr_qual_portfolio&qualification_id=' . rawurlencode((string) $row['id']).'&internaltitle='.rawurlencode((string) $row['internaltitle']).'&framework_id='.rawurlencode((string) $row['framework_id']).'&tr_id='.rawurlencode((string) $this->tr_id). '"><img src="/images/portfolio_icon.gif" border="0" title="Portfolio" height="25px" width="25px" /></a></td>';
                }
                else
                {
                    //		echo '<td align=center><img src="/images/read.jpg" title="View qualification tree" border="0" height="25px" width="25px" /></a></td>';
                    echo '<td align=center><img src="/images/edit.jpg" border="0" title="Edit qualification tree" height="25px" width="25px" /></a></td>';
                    echo '<td align=center><img src="/images/matrix.jpg" border="0" title="Mark progress through matrix" height="25px" width="25px" /></a></td>';
                    echo '<td align=center><img src="/images/tabular.jpg" border="0" title="Mark progress through data table" height="25px" width="25px" /></a></td>';
                }

                //echo '<td align=center><a href="do.php?_action=view_tr_qualification_tabular2&qualification_id=' . rawurlencode((string) $row['id']).'&internaltitle='.rawurlencode((string) $row['internaltitle']).'&framework_id='.rawurlencode((string) $row['framework_id']).'&tr_id='.rawurlencode((string) $this->tr_id).'&target='.rawurlencode((string) $target).'&achieved='.rawurlencode((string) $row['unitsUnderAssessment']) . '"><img src="/images/tabular.jpg" border="0" title="Mark progress through data table" height="25px" width="25px" /></a></td>';

                if(DB_NAME != "am_reed_demo" && DB_NAME != "am_reed")
                {
                    if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==8)
                    {
                        if($flag=="NF")
                            if(DB_NAME!='am_doncaster' && DB_NAME!='am_southampton' && DB_NAME!='am_lewisham')
                                echo '<td align=center><a href="do.php?_action=add_aim_to_ilr&qualification_id=' . rawurlencode((string) $row['id']).'&submission='.rawurlencode((string) $submission).'&contract_id='.rawurlencode((string) $contract_id).'&tr_id='.rawurlencode((string) $this->tr_id).'&target='.rawurlencode((string) $target).'&achieved='.rawurlencode((string) $row['unitsUnderAssessment']) . '">Add to ILR</a></td>';
                            elseif(DB_NAME=="am_doncaster")
                                echo '<td>New Addition</td>';
                            else
                                echo '<td>Additional</td>';
                        else
                            echo '<td>&nbsp;</td>';
                    }
                }
                else
                {
                    if($_SESSION['user']->isAdmin() || $_SESSION['user']->type == User::TYPE_MANAGER)
                    {
                        if($flag=="NF")
                        {
                            if(DB_NAME=="am_reed" || DB_NAME=="am_reed_demo")
                            {
                                echo '<td align=center><img title="Start aim with start information and add to ILR" style="cursor: pointer;" width="30" height="30" src="images/start_aim.png" onclick="addAimToILR(\'' . $row['id'] . '\', \'' . rawurlencode((string) $submission) . '\', \'' . rawurlencode((string) $contract_id) . '\', \'' . rawurlencode((string) $this->tr_id) . '\', \'' . rawurlencode((string) $target) . '\', \'' . rawurlencode((string) $row['unitsUnderAssessment']) . '\');" /></td>';
                            }
                            else
                            {
                                echo '<td align=center><a href="do.php?_action=add_aim_to_ilr&qualification_id=' . rawurlencode((string) $row['id']).'&submission='.rawurlencode((string) $submission).'&contract_id='.rawurlencode((string) $contract_id).'&tr_id='.rawurlencode((string) $this->tr_id).'&target='.rawurlencode((string) $target).'&achieved='.rawurlencode((string) $row['unitsUnderAssessment']) . '">Add to ILR</a></td>';
                            }
                        }
                        else
                        {
                            if(DB_NAME == "am_reed" || DB_NAME == "am_reed_demo")
                            {
                                if($row['actual_end_date'] == '')
                                    echo '<td align=center><img title="Close aim with learning aim end information" style="cursor: pointer;" width="30" height="30" src="images/stop_aim.png" onclick="closeAim(\'' . $row['id'] . '\', \'' . rawurlencode((string) $submission) . '\', \'' . rawurlencode((string) $contract_id) . '\', \'' . rawurlencode((string) $this->tr_id) . '\', \'' . rawurlencode((string) $target) . '\', \'' . rawurlencode((string) $row['unitsUnderAssessment']) . '\', \''. $row['start_date']. '\');" /></td>';
                                elseif($row['actual_end_date'] != '' && strtolower(substr( str_replace('/', '', $row['id']), 0, 4)) != "z000")
                                {
                                    if($row['achievement_date'] == '' && $row['compstatus'] != '3' && $row['compstatus'] != '6')
                                        echo '<td align=center><img title="Enter aim achievement information" style="cursor: pointer;" src="images/rosette.gif" onclick="achieveAim(\'' . $row['id'] . '\', \'' . rawurlencode((string) $submission) . '\', \'' . rawurlencode((string) $contract_id) . '\', \'' . rawurlencode((string) $this->tr_id) . '\', \'' . rawurlencode((string) $target) . '\', \'' . rawurlencode((string) $row['unitsUnderAssessment']) . '\', \''. $row['actual_end_date']. '\');" /></td>';
                                    else
                                        echo '<td>&nbsp;</td>';
                                }
                                else
                                    echo '<td>&nbsp;</td>';
                            }
                            else
                                echo '<td>&nbsp;</td>';
                        }
                    }
                }
                echo '</tr>';
            }

            // Add total row
//			echo '<tr><td>Total</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';
//			echo '<td>'
            echo '</tbody></table></div align="left">';
//			echo $this->getViewNavigator('left');

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }
    }

    public function isGroupTutor($link, $tr_id)
    {
        $que = "select users.id from users inner join groups on groups.tutor = users.id inner join group_members on group_members.groups_id = groups.id where group_members.tr_id = $tr_id";
        $group_tutor = DAO::getSingleValue($link, $que);
        if($group_tutor == $_SESSION['user']->id)
            return true;
        else
            return false;
    }

    public function isGroupAssessor($link, $tr_id)
    {
        $que = "select users.id from users inner join groups on groups.assessor = users.id inner join group_members on group_members.groups_id = groups.id where group_members.tr_id = $tr_id";
        $group_assessor = DAO::getSingleValue($link, $que);
        if($group_assessor == $_SESSION['user']->id)
            return true;
        else
            return false;
    }


    public function isGroupVerifier($link, $tr_id)
    {
        $que = "select users.id from users inner join groups on groups.verifier = users.id inner join group_members on group_members.groups_id = groups.id where group_members.tr_id = $tr_id";
        $group_verifier = DAO::getSingleValue($link, $que);
        if($group_verifier == $_SESSION['user']->id)
            return true;
        else
            return false;
    }

    public function isSelf($link, $tr_id)
    {
        $que = "select users.username from users inner join tr on tr.username = users.username where tr.id = $tr_id";
        $self = DAO::getSingleValue($link, $que);
        if($self == $_SESSION['user']->username)
            return true;
        else
            return false;
    }


    public $tr_id;
}
?>