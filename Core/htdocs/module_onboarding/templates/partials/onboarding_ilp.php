<?php /* @var $ob_learner User */ ?>
<?php /* @var $tr TrainingRecord */ ?>
<?php /* @var $employer Employer */ ?>
<?php /* @var $employer_main_site Location */ ?>
<?php /* @var $framework Framework */ ?>
<?php
$gender_list = InductionHelper::getListGender();
$ilp_weeks_on_programme = DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(WEEK, '{$tr->start_date}', '{$tr->target_date}')");
$ilp_months_on_programme = DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(MONTH, '{$tr->start_date}', '{$tr->target_date}')");
$ilp_planned_hours = DAO::getSingleValue($link, "SELECT SUM(glh) FROM student_qualifications WHERE tr_id = '{$tr->id}' ");
$ilp_epa_org = DAO::getObject($link, "SELECT * FROM central.`epa_organisations` WHERE EPA_ORG_ID = '{$tr->epa_organisation}'");
?>

<div class="row">
    <div class="col-sm-12">
        <div class="small">
            <div class="row"><div class="col-sm-12"><strong>Section 1: Learner, Employer / Organisation and Provider (as applicable) Details: </strong></div></div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <table class="table table-responsive table-bordered ilp" id="ilp_learner">
                        <tr><th colspan="2" style="background-color: #e0ffff;">Learner Details</th></tr>
                        <tr><th>Title:</th><td id="ilp_learner_title"><?php echo $ob_learner->learner_title; ?></td></tr>
                        <tr><th>Learner First Name(s):</th><td id="ilp_firstnames"><?php echo $ob_learner->firstnames; ?></td></tr>
                        <tr><th>Learner Surname:</th><td id="ilp_surname"><?php echo $ob_learner->surname; ?></td></tr>
                        <tr><th>Date of Birth:</th><td id="ilp_dob"><?php echo Date::toShort($ob_learner->dob); ?></td></tr>
                        <tr><th>Gender:</th><td id="ilp_dob"><?php echo isset($gender_list[$ob_learner->gender]) ? $gender_list[$ob_learner->gender] : $ob_learner->gender; ?></td></tr>
                        <tr><th>Email:</th><td id="ilp_home_email"><?php echo $ob_learner->home_email; ?></td></tr>
                        <tr><th>Telephone:</th><td id="ilp_home_telephone"><?php echo $ob_learner->home_telephone; ?></td></tr>
                        <tr><th>Mobile:</th><td id="ilp_home_mobile"><?php echo $ob_learner->home_mobile; ?></td></tr>
                    </table>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <table class="table table-responsive table-bordered ilp">
                        <tr><th colspan="2" style="background-color: #e0ffff;">Employer Organisation Details</th></tr>
                        <tr><th>Employer Name:</th><td><?php echo $employer->legal_name; ?></td></tr>
                        <tr><th>Employer Contact:</th><td><?php echo DAO::getSingleValue($link, "SELECT contact_name FROM organisation_contact WHERE contact_id = '{$tr->crm_contact_id}'"); ?></td></tr>
                        <tr><th>Employer Address:</th><td><?php echo $employer_main_site->address_line_1 . ' ' . $employer_main_site->address_line_2 . ' ' . $employer_main_site->address_line_3 . ' ' . $employer_main_site->address_line_4; ?></td></tr>
                        <tr><th>Postcode:</th><td><?php echo $employer_main_site->postcode; ?></td></tr>
                        <tr><th>Mobile:</th><td><?php echo DAO::getSingleValue($link, "SELECT contact_mobile FROM organisation_contact WHERE contact_id = '{$tr->crm_contact_id}'"); ?></td></tr>
                        <tr><th>Telephone:</th><td><?php echo DAO::getSingleValue($link, "SELECT contact_telephone FROM organisation_contact WHERE contact_id = '{$tr->crm_contact_id}'"); ?></td></tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <table class="table table-responsive table-bordered ilp">
                        <tr><th colspan="6" style="background-color: #e0ffff;">Programme Details</th></tr>
                        <tr><th>Programme Title:</th><td colspan="5"><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$tr->id}'"); ?></td></tr>
                        <tr><th>Programme Type:</th><td colspan="5"><?php echo $programme_type; ?></td></tr>
                        <tr>
                            <th>Start Date:</th><td><?php echo Date::toShort($tr->start_date); ?></td>
                            <th>Expected Completion:</th><td><?php echo Date::toShort($tr->target_date); ?></td>
                            <th>Expected Completion (including EPA):</th><td><?php echo Date::toShort($tr->end_date_inc_epa); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <!--<div class="col-lg-6 col-md-6 col-sm-12">
                    <table class="table table-responsive table-bordered ilp">
                        <tr><th colspan="2" style="background-color: #e0ffff;">Emergency Contact Details</th></tr>
                        <tr><th>Title:</th><td id="ilp_em_con_title"></td></tr>
                        <tr><th>Name:</th><td id="ilp_em_con_name"></td></tr>
                        <tr><th>Relationship to Learner:</th><td id="ilp_em_con_rel"></td></tr>
                        <tr><th>Home Number:</th><td id="ilp_em_con_tel"></td></tr>
                        <tr><th>Mobile Number:</th><td id="ilp_em_con_mob"></td></tr>
                    </table>
                </div>-->
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <table class="table table-responsive table-bordered ilp text-center">
                        <tr><th colspan="4" style="background-color: #e0ffff;">1b: Prior Attainment</th></tr>
                        <tr><th>Qualification Title (Prior Attainment)</th><th>Date Awarded</th><th>Grade</th></tr>
                        <tr id="ilp_gcse_english"><td></td><td></td><td></td></tr>
                        <tr id="ilp_gcse_maths"><td></td><td></td><td></td></tr>
                        <tr id="ilp_pa1"><td></td><td></td><td></td></tr>
                        <tr id="ilp_pa2"><td></td><td></td><td></td></tr>
                        <tr id="ilp_pa3"><td></td><td></td><td></td></tr>
                        <tr id="ilp_pa4"><td></td><td></td><td></td></tr>
                        <tr id="ilp_pa5"><td></td><td></td><td></td></tr>
                        <tr id="ilp_pa6"><td></td><td></td><td></td></tr>
                        <tr id="ilp_pa7"><td></td><td></td><td></td></tr>

                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <table class="table table-bordered">
                        <tr>
                            <th>Programme duration in weeks</th>
                            <th>Statutory Annual Leave Entitlement<br><small>(adjusted to reflect programme duration)</small></th>
                            <th>No. of days contracted per week</th>
                            <th>No. of normal working hours per week<br><small>(excluding overtime)</small></th>
                            <th>*Planned Hours</th>
                            <th>Off-the-job Hours</th>
                        </tr>
                        <tr>
                            <td><?php echo $ilp_weeks_on_programme; ?> weeks</td>
                            <td><?php echo $ob_learner->statutory_annual_leave; ?> </td>
                            <td><?php echo $ob_learner->emp_q8; ?> </td>
                            <td><?php echo $ob_learner->emp_q7; ?> </td>
                            <td><?php echo $ilp_planned_hours; ?> </td>
                            <td>
                                <?php
                                if($tr->otj_hours == '')
                                    $otj_hours = (floatval($ilp_weeks_on_programme)-floatval($ob_learner->statutory_annual_leave))*floatval($ob_learner->emp_q7)*0.2;
                                else
                                    $otj_hours = $tr->otj_hours;
                                echo ceil($otj_hours);
                                ?>
                            </td>
                        </tr>
                    </table>
                    <table class="table table-bordered">
                        <tbody>
                        <tr class="bg-gray">
                            <td>
                                <p><strong>Planned Activity </strong></p>
                            </td>
                            <td>
                                <p><strong>Potential off-the-job hours </strong></p>
                            </td>
                        </tr>
                        <tr class="bg-gray">
                            <td colspan="2">
                                <p><strong>Theory</strong></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Induction to COSHH symbols, regulations and global harmonisation.</p>
                            </td>
                            <td>
                                <p>0.5 hours</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Equality and Diversity, Prevent and British Values</p>
                            </td>
                            <td>
                                <p>2 hours</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Support workbook</p>
                            </td>
                            <td>
                                <p>20 hours</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Classroom theory training</p>
                            </td>
                            <td>
                                <p>37.5 hours</p>
                            </td>
                        </tr>
                        <tr class="bg-gray">
                            <td colspan="2">
                                <p><strong>Off the job portfolio &ndash; problem solving pack&ndash; pre-improvement</strong></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Project scope</p>
                            </td>
                            <td>
                                <p>2 hours</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Pre-improvement data collection</p>
                            </td>
                            <td>
                                <p>10 hours</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Practical problem-solving document (PPS)</p>
                            </td>
                            <td>
                                <p>5 hours</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Waste Walk</p>
                            </td>
                            <td>
                                <p>2 hours</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>5s audits</p>
                            </td>
                            <td>
                                <p>1 hour</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Risk assessments</p>
                            </td>
                            <td>
                                <p>2 hours</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Pre-improvement photos</p>
                            </td>
                            <td>
                                <p>0.5 hours</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Standard operation procedures</p>
                            </td>
                            <td>
                                <p>1 hour</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Job description</p>
                            </td>
                            <td>
                                <p>0.5 hours</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Organisation chart</p>
                            </td>
                            <td>
                                <p>0.5 hours</p>
                            </td>
                        </tr>
                        <tr class="bg-gray">
                            <td>
                                <p><strong>Project implementation &amp; portfolio building</strong></p>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <p>Project/portfolio classroom training days</p>
                            </td>
                            <td>
                                <p>30 hours</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>CI action plans/portfolio building</p>
                            </td>
                            <td>
                                <p>40 hours</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Further audits (5s/H&amp;S)</p>
                            </td>
                            <td>
                                <p>5 hours</p>
                            </td>
                        </tr>
                        <tr class="bg-gray">
                            <td colspan="2">
                                <p><strong>End-point Assessment Preparation</strong></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Workplace observation coaching 2</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Professional discussion coaching</p>
                            </td>
                            <td>
                                <p>7.5</p>
                            </td>
                        </tr>
                        <tr class="bg-gray">
                            <td colspan="2">
                                <p><strong>Ongoing &ndash; embedded activities</strong></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>&middot; Continuous improvement activities in the workplace (e.g. 5S, TPM, SMED, Kaizen)</p>
                                <p>&middot; Extra training &ndash; new equipment, training of SOP&rsquo;s,</p>
                                <p>&middot; Support/guidance (job shadowing, mentoring, reflective practice)</p>
                                <p>&middot; Promotion and undertaking of roles and responsibilities (helping and guiding others, contributing to team performance, presenting ideas and solutions)</p>
                                <p>&middot; Research and study</p>
                                <p>&middot; Personal development in soft skills</p>
                            </td>
                            <td>
                                <p>250 &ndash; 300 hours</p>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <p><br></p>

                    <p class="text-bold">3.0	Delivery Pattern and Expected Contact</p>

                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td>
                                <p><strong>How will the knowledge, skills and behaviours be delivered and achieved for the apprenticeship standard?</strong></p>
                            </td>
                            <td>
                                <p>Classroom based tutorial, project portfolio, log book, improvement project presentation and work based action plans. Assessment and achievement will be completed through an End Point Assessment once training days have been completed and the Gateway requirements have been met.</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><strong>How will the learning &amp; skills be achieved for the Functional Skills?</strong></p>
                            </td>
                            <td>
                                <p>Class based learning, Practice papers, Workbooks.</p>
                                <p>Functional skills will be achieved through Online or Paper based Exams</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><strong>What level of contact will be maintained with the Apprentice?</strong></p>
                            </td>
                            <td>
                                <p>Monthly on-site training</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><strong>How will the 20% off the job training be delivered?</strong></p>
                            </td>
                            <td>
                                <p>The teaching of theory (for example, training sessions, simulation activities, and online learning). Practical training, project work and implementation. Learning support and time spent writing log books and assignments.</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><strong>Progress Reviews &ndash; How will this process be carried out and who will be involved?</strong></p>
                            </td>
                            <td>
                                <p>The first review carried out at 4 weeks from today&rsquo;s start date. The following reviews every 8 weeks until completion.</p>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <p><br></p>

                    <?php
                    $main_aim_qual = DAO::getObject($link, "SELECT * FROM framework_qualifications WHERE framework_id = '{$framework->id}' AND main_aim = '1'");
                    //$qual_evidence = DAO::getSingleValue($link, "SELECT evidences FROM qualifications WHERE REPLACE(id, '/', '') = '60335890'");
		    $qual_evidence = $main_aim_qual->evidences;
                    $qual_evidence = XML::loadSimpleXML($qual_evidence);
                    $mandatory_units = [];
                    $optional_units = [];
                    $units = $qual_evidence->xpath('//unit');
                    foreach($units AS $unit)
                    {
                        $mandatory = $unit->attributes()->mandatory->__toString();
                        $temp = (array)$unit->attributes();
                        if($mandatory == "true")
                            $mandatory_units[] = $temp['@attributes'];
                        else
                            $optional_units[] = $temp['@attributes'];
                    }
                    unset($units);
                    ?>

                    <table class="table table-responsive table-bordered ilp ">
                        <tr class="text-center"><th colspan="2" style="background-color: #e0ffff;">4.0 Components of Programme: <?php echo $main_aim_qual->internaltitle; ?></th></tr>
                        <tr><th colspan="2">Mandatory Units</th></tr>
                        <?php
                        foreach($mandatory_units AS $man_unit)
                        {
                            echo '<tr><td>' . $man_unit['reference'] . '</td><td>' . $man_unit['title'] . '</td></tr>';
                        }
                        ?>
                        <tr><th colspan="2">Optional Units</th></tr>
                        <?php
                        foreach($optional_units AS $opt_unit)
                        {
                            echo '<tr><td>' . $opt_unit['reference'] . '</td><td>' . $opt_unit['title'] . '</td></tr>';
                        }
                        ?>
                    </table>

                    <p class="text-bold">4.1	The EPA consists of two distinct assessment methods: </p>

                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td colspan="5">
                                <p><strong>End Point Assessment Overview</strong></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><strong>Assessment Method</strong></p>
                            </td>
                            <td>
                                <p><strong>Area Assessed</strong></p>
                            </td>
                            <td>
                                <p><strong>Assessed by</strong></p>
                            </td>
                            <td>
                                <p><strong>Grading</strong></p>
                            </td>
                            <td>
                                <p><strong>Gateway Requirements</strong></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><em>Workplace observation</em></p>
                            </td>
                            <td>
                                <p><em>Knowledge, skill and behaviour elements</em></p>
                            </td>
                            <td>
                                <p><em>End Point Assessment Organisation</em></p>
                            </td>
                            <td>
                                <p><em>Fail/Pass</em></p>
                                <p>&nbsp;</p>
                            </td>
                            <td rowspan="2">
                                <p><em>Employer is satisfied the apprentice is consistently working at, or above, the level of the occupational standard. </em></p>
                                <p>&nbsp;</p>
                                <p><em>Achieved English and mathematics at level 1 and taken the tests for level 2. </em></p>
                                <p>&nbsp;</p>
                                <p><em>Achieved Level 2 Diploma in Manufacturing (Knowledge and Skills).</em></p>
                                <p>&nbsp;</p>
                                <p><em>&nbsp;Apprentices must submit a portfolio of evidence.</em></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><em>Professional Discussion</em></p>
                            </td>
                            <td>
                                <p><em>Knowledge, skill and behaviour elements</em></p>
                            </td>
                            <td>
                                <p><em>End Point Assessment Organisation</em></p>
                            </td>
                            <td>
                                <p><em>Fail/Pass/ Distinction</em></p>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <p class="text-bold">4.2	Details of End Point Assessment Organisation: -</p>

                    <table class="table table-bordered">
                        <tbody>
                        <tr><td colspan="2"><p><strong>End Point Assessment Organisation Details </strong></p></td></tr>
                        <tr>
                            <th>EPA Name</th>
                            <td><?php echo isset($ilp_epa_org->EP_Assessment_Organisations) ? $ilp_epa_org->EP_Assessment_Organisations : ''; ?></td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>
                                <?php
                                echo isset($ilp_epa_org->Contact_address1) ? $ilp_epa_org->Contact_address1 . '<br>' : '';
                                echo isset($ilp_epa_org->Contact_address2) ? $ilp_epa_org->Contact_address2 . '<br>' : '';
                                echo isset($ilp_epa_org->Contact_address3) ? $ilp_epa_org->Contact_address3 . '<br>' : '';
                                echo isset($ilp_epa_org->Contact_address4) ? $ilp_epa_org->Contact_address4 . '<br>' : '';
                                echo isset($ilp_epa_org->Postcode) ? $ilp_epa_org->Postcode : '';
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Contact Name</th>
                            <td><?php echo isset($ilp_epa_org->Contact_Name) ? $ilp_epa_org->Contact_Name : ''; ?></td>
                        </tr>
                        <tr>
                            <th>Telephone / Email</th>
                            <td>
                                <?php
                                echo isset($ilp_epa_org->Contact_number) ? $ilp_epa_org->Contact_number . '<br>' : '';
                                echo isset($ilp_epa_org->Contact_email) ? $ilp_epa_org->Contact_email : '';
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>EPA Organisation ID</th>
                            <td><?php echo isset($ilp_epa_org->EPA_ORG_ID) ? $ilp_epa_org->EPA_ORG_ID : ''; ?></td>
                        </tr>
                        </tbody>
                    </table>

                    <p><br></p>

                    <p class="text-bold">5	Complaints and Dispute Resolution</p>

                    <div style="margin-left: 5px;">
                        <p>5.2	The Training Provider has overall responsibility for resolving any issues and disputes arising in relation to the delivery of the apprenticeship.  Visit our website www.leadltd.co.uk for all our policies.</p>
                        <p>5.3	Where the Employer has an issue or dispute relating to delivery of this apprenticeship, they should refer this to a senior member of the LEAD team.</p>
                        <p>5.4	Where the Apprentice has an issue or dispute relating to the assessment decision, they should contact the End Point Assessment organisation to appeal.</p>
                        <p>Telephone: 01904 236 483 www.oawards.co.uk/contact-us/</p>
                        <p>5.5	Where the Apprentice has an issue or dispute relating to the provision of the delivery of services undertaken by The Training Provider, the Employer shall make the matter known to The Training Provider in writing or by email.</p>
                        <p>5.6	Apprentices and their employers can contact the apprenticeship helpline regarding apprenticeship concerns, complaints and enquiries using the contact details below.</p>
                    </div>

                    <p class="text-bold">Apprenticeship helpline e-mail: nationalhelpdesk@apprenticeships.gov.uk</p>
                    <p class="text-bold">Telephone: 0800 015 0400 8am to 10pm, 7 days a week</p>

                    <p><br></p>
                    <p>The ESFA will acknowledge your complaint within 5 days and will let you know what will happen next</p>

                    <p></p>
                    <p class="text-bold">Complaints team</p>
                    <p>Education and Skills Funding Agency </p>
                    <p>Cheylesmore House, Quinton Road </p>
                    <p>Coventry, CV1 2WT </p>

                    <p><br></p>

                    <p>If you're unhappy with the ESFA response, you can write to the Complaints Adjudicator to decide on your case if you're unhappy with how the ESFA has dealt with your complaint.</p>

                    <p></p>

                    <p class="text-bold">Complaints adjudicator	</p>
                    <p>Legal and information compliance</p>
                    <p>Education and Skills Funding Agency</p>
                    <p>Cheylesmore House, Quinton Road</p>
                    <p>Coventry, CV1 2WT</p>
                </div>
            </div>

        </div>

        <hr>

    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <p><span class="btn btn-sm btn-info" onclick="window.open('do.php?_action=view_ob_document&doc=cs&ob_learner_id=<?php echo $ob_learner->id; ?>', '_blank');">Commitment Statement</span></p>
        <p><input class="clsICheck" type="checkbox" name="ea_consent" /><label>Please tick the box to show you have read and understand the Commitment Statement.</label></p>
    </div>
</div>
