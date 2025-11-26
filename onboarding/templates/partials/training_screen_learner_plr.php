<?php 
$plr_records = DAO::getResultset($link, "SELECT * FROM lrs_learner_learning_events WHERE tr_id = '{$tr->id}' AND sunesis_core = 0", DAO::FETCH_ASSOC); 
?>
<?php if(SystemConfig::getEntityValue($link, "lrs")) {?>
<div class="row">
    <div class="col-sm-12">
        <button type="button" class="btn btn-primary btn-md" id="btnDownloadLearnerPlr"><i class="fa fa-cloud-download"></i> Download Learning Events from LRS</button>
	<?php if(count($plr_records) > 0) { ?>
        <button type="button" class="btn btn-info btn-sm pull-right" onclick="generatePlrPdf();"><i class="fa fa-file-pdf-o"></i> Export</button>
        <?php } ?>
        <p><br></p>
    </div>
    <?php if(count($plr_records) > 0) { ?>
    <div class="col-sm-12">
        <p class="text-center text-bold text-info">
            <?php echo count($plr_records); ?> records
        </p>
    </div>
    <?php } ?>
</div>


<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive">
            <?php 
            //$plr_records = DAO::getResultset($link, "SELECT * FROM lrs_learner_learning_events WHERE tr_id = '{$tr->id}' AND sunesis_core = 0", DAO::FETCH_ASSOC); 
            foreach($plr_records AS $plr_record)
            {
                echo '<div class="box box-primary">';
                echo '<div class="box-header with-border">';
                echo '<span class="box-title">';
                echo $plr_record['SubjectCode'] . ': ' . $plr_record['Subject'];
                echo ' &nbsp; <span class="label label-info">' . $plr_record['Grade'] . '</span>';
                echo '</span>';
                echo '</div>';
                echo '<div class="box-body">';
                echo '<span class="text-bold">Achievement Award Date: </span>' .  Date::toShort($plr_record['AchievementAwardDate']) . ' | '; 
                echo '<span class="text-bold">Achievement Provider Name: </span>' . $plr_record['AchievementProviderName'] . ' | ';
                echo '<span class="text-bold">Achievement Provider Ukprn: </span>' . $plr_record['AchievementProviderUkprn']  . ' | ';
                echo '<span class="text-bold">Awarding Organisation Name: </span>' . $plr_record['AwardingOrganisationName'] . ' | ';
                echo '<span class="text-bold">Awarding Organisation Ukprn: </span>' . $plr_record['AwardingOrganisationUkprn'] . ' | '; 
                echo '<span class="text-bold">Collection Type: </span>' . $plr_record['CollectionType'] . ' | '; 
                echo '<span class="text-bold">Credits: </span>' . $plr_record['Credits'] . ' | '; 
                echo '<span class="text-bold">Date Loaded: </span>' . Date::toShort($plr_record['DateLoaded']) . ' | '; 
                echo '<span class="text-bold">Language For Assessment: </span>' . $plr_record['LanguageForAssessment'] . ' | '; 
                echo '<span class="text-bold">Level: </span>' . $plr_record['Level'] . ' | '; 
                echo '<span class="text-bold">Qualification Type: </span>' . $plr_record['QualificationType'] . ' | '; 
                echo '<span class="text-bold">Source: </span>' . $plr_record['Source'] . ' | '; 
                echo '<span class="text-bold">Status: </span>' . $plr_record['Status'] . ' | '; 
                echo '<span class="text-bold">Participation Start Date: </span>' . Date::toShort($plr_record['ParticipationStartDate']) . ' | '; 
                echo '<span class="text-bold">Participation End Date: </span>' . Date::toShort($plr_record['ParticipationEndDate']) . ' | '; 
                echo '</div>';
                echo '</div>';

            }
            ?>

            <!-- <table id="tblPlrData" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Achievement Award Date</th>
                        <th>Achievement Provider Name</th>
                        <th>Achievement Provider Ukprn</th>
                        <th>Awarding Organisation Name</th>
                        <th>Awarding Organisation Ukprn</th>
                        <th>Collection Type</th>
                        <th>Credits</th>
                        <th>Date Loaded</th>
                        <th>Grade</th>
                        <th>Language For Assessment</th>
                        <th>Level</th>
                        <th>Qualification Type</th>
                        <th>Source</th>
                        <th>Status</th>
                        <th>Subject</th>
                        <th>Subject Code</th>
                        <th>Participation Start Date</th>
                        <th>Participation End Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $plr_records = DAO::getResultset($link, "SELECT * FROM lrs_learner_learning_events WHERE tr_id = '{$tr->id}'", DAO::FETCH_ASSOC); 
                    foreach($plr_records AS $plr_record)
                    {
                        echo '<tr>';
                        echo '<td>' . Date::toShort($plr_record['AchievementAwardDate']) . '</td>';
                        echo '<td>' . $plr_record['AchievementProviderName'] . '</td>';
                        echo '<td>' . $plr_record['AchievementProviderUkprn'] . '</td>';
                        echo '<td>' . $plr_record['AwardingOrganisationName'] . '</td>';
                        echo '<td>' . $plr_record['AwardingOrganisationUkprn'] . '</td>';
                        echo '<td>' . $plr_record['CollectionType'] . '</td>';
                        echo '<td>' . $plr_record['Credits'] . '</td>';
                        echo '<td>' . Date::toShort($plr_record['DateLoaded']) . '</td>';
                        echo '<td>' . $plr_record['Grade'] . '</td>';
                        echo '<td>' . $plr_record['LanguageForAssessment'] . '</td>';
                        echo '<td>' . $plr_record['Level'] . '</td>';
                        echo '<td>' . $plr_record['QualificationType'] . '</td>';
                        echo '<td>' . $plr_record['Source'] . '</td>';
                        echo '<td>' . $plr_record['Status'] . '</td>';
                        echo '<td>' . $plr_record['Subject'] . '</td>';
                        echo '<td>' . $plr_record['SubjectCode'] . '</td>';
                        echo '<td>' . Date::toShort($plr_record['ParticipationStartDate']) . '</td>';
                        echo '<td>' . Date::toShort($plr_record['ParticipationEndDate']) . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table> -->

        </div>
    </div>
</div>
<?php } else { ?>
<div class="row">
    <div class="col-sm-12">
        <p class="text-info"><br><i class="fa fa-info-circle"></i> LRS module is not switched on for your system. </p>
        <p class="text-info"><i class="fa fa-info-circle"></i> Please contact Sunesis Support to switch it on.</p>
    </div>
</div>
<?php } ?>

<?php if(DB_NAME == "am_demo") { ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="table-responsive">
                <div class="box box-primary"><div class="box-header with-border"><span class="box-title">10013246: Certificate in Adult Literacy - Level 1 &nbsp; <span class="label label-info">9999999999</span></span></div><div class="box-body"><span class="text-bold">Achievement Award Date: </span> | <span class="text-bold">Achievement Provider Name: </span>UFI LIMITED | <span class="text-bold">Achievement Provider Ukprn: </span>10003816 | <span class="text-bold">Awarding Organisation Name: </span>Oxford Cambridge &amp; RSA Examinations | <span class="text-bold">Awarding Organisation Ukprn: </span>100109 | <span class="text-bold">Collection Type: </span>F | <span class="text-bold">Credits: </span>0 | <span class="text-bold">Date Loaded: </span>12/12/2011 | <span class="text-bold">Language For Assessment: </span> | <span class="text-bold">Level: </span> | <span class="text-bold">Qualification Type: </span>Certificate | <span class="text-bold">Source: </span>ILR | <span class="text-bold">Status: </span>F | <span class="text-bold">Participation Start Date: </span>14/09/2010 | <span class="text-bold">Participation End Date: </span>17/12/2010 | </div></div>
                <div class="box box-primary"><div class="box-header with-border"><span class="box-title">Z0000087: Non regulated SFA formula funded provision  Level 2  Health and Social Care  101 to 196 hrs  PW B &nbsp; <span class="label label-info">9999999999</span></span></div><div class="box-body"><span class="text-bold">Achievement Award Date: </span> | <span class="text-bold">Achievement Provider Name: </span>SERCO LIMITED | <span class="text-bold">Achievement Provider Ukprn: </span>10005752 | <span class="text-bold">Awarding Organisation Name: </span>Generic award - no awarding body | <span class="text-bold">Awarding Organisation Ukprn: </span>100106 | <span class="text-bold">Collection Type: </span>S | <span class="text-bold">Credits: </span>0 | <span class="text-bold">Date Loaded: </span>17/02/2023 | <span class="text-bold">Language For Assessment: </span> | <span class="text-bold">Level: </span> | <span class="text-bold">Qualification Type: </span>Other | <span class="text-bold">Source: </span>ILR | <span class="text-bold">Status: </span>F | <span class="text-bold">Participation Start Date: </span>01/09/2021 | <span class="text-bold">Participation End Date: </span>11/11/2021 | </div></div>
                <div class="box box-primary"><div class="box-header with-border"><span class="box-title">ZESF0001: ESF learner start and assessment &nbsp; <span class="label label-info">9999999999</span></span></div><div class="box-body"><span class="text-bold">Achievement Award Date: </span> | <span class="text-bold">Achievement Provider Name: </span>SERCO LIMITED | <span class="text-bold">Achievement Provider Ukprn: </span>10005752 | <span class="text-bold">Awarding Organisation Name: </span>Generic award - no awarding body | <span class="text-bold">Awarding Organisation Ukprn: </span>100106 | <span class="text-bold">Collection Type: </span>S | <span class="text-bold">Credits: </span>0 | <span class="text-bold">Date Loaded: </span>17/02/2023 | <span class="text-bold">Language For Assessment: </span> | <span class="text-bold">Level: </span> | <span class="text-bold">Qualification Type: </span>Other | <span class="text-bold">Source: </span>ILR | <span class="text-bold">Status: </span>F | <span class="text-bold">Participation Start Date: </span>01/09/2021 | <span class="text-bold">Participation End Date: </span>01/09/2021 | </div></div>
                <div class="box box-primary"><div class="box-header with-border"><span class="box-title">ZESF0001: ESF Cofinanced - Participant receiving matrix accredited IAG &nbsp; <span class="label label-info">PA</span></span></div><div class="box-body"><span class="text-bold">Achievement Award Date: </span> | <span class="text-bold">Achievement Provider Name: </span>WALSALL COLLEGE | <span class="text-bold">Achievement Provider Ukprn: </span>10007315 | <span class="text-bold">Awarding Organisation Name: </span>BRIGHTON AND SUSSEX MEDICAL SCHOOL | <span class="text-bold">Awarding Organisation Ukprn: </span>999999 | <span class="text-bold">Collection Type: </span>S | <span class="text-bold">Credits: </span>0 | <span class="text-bold">Date Loaded: </span>19/12/2014 | <span class="text-bold">Language For Assessment: </span> | <span class="text-bold">Level: </span> | <span class="text-bold">Qualification Type: </span>Other | <span class="text-bold">Source: </span>ILR | <span class="text-bold">Status: </span>F | <span class="text-bold">Participation Start Date: </span>30/10/2013 | <span class="text-bold">Participation End Date: </span>30/10/2013 | </div></div>
                <div class="box box-primary"><div class="box-header with-border"><span class="box-title">ZINN0002: Innovation code - Award 7 - 12 credits &nbsp; <span class="label label-info">PA</span></span></div><div class="box-body"><span class="text-bold">Achievement Award Date: </span> | <span class="text-bold">Achievement Provider Name: </span>WALSALL COLLEGE | <span class="text-bold">Achievement Provider Ukprn: </span>10007315 | <span class="text-bold">Awarding Organisation Name: </span>BRIGHTON AND SUSSEX MEDICAL SCHOOL | <span class="text-bold">Awarding Organisation Ukprn: </span>999999 | <span class="text-bold">Collection Type: </span>S | <span class="text-bold">Credits: </span>0 | <span class="text-bold">Date Loaded: </span>19/12/2014 | <span class="text-bold">Language For Assessment: </span> | <span class="text-bold">Level: </span> | <span class="text-bold">Qualification Type: </span>Other | <span class="text-bold">Source: </span>ILR | <span class="text-bold">Status: </span>F | <span class="text-bold">Participation Start Date: </span>30/10/2013 | <span class="text-bold">Participation End Date: </span>19/02/2014 | </div></div>
                

            </div>
        </div>
    </div>
    <p><br></p>
    <div class="row">
   <div class="col-sm-12">
      <div class="table-responsive">
         <table class="table table-bordered table-condensed">
            <caption class="text-bold bg-gray">Initial Assessment</caption>
            <tbody>
               <tr>
                  <th>Session ID</th>
                  <th>Course Component Name</th>
                  <th>Ability Measurement</th>
                  <th>Measuring Assessment Name</th>
                  <th>Measured At</th>
               </tr>
               <tr>
                  <td class="small">57546e3a-9892-42b7-b758-4cc73dcde382</td>
                  <td>Functional Skills English</td>
                  <td align="center" class="text-bold">1.75</td>
                  <td>English Initial Assessment</td>
                  <td>03/02/2021 18:53:55</td>
               </tr>
               <tr>
                  <td class="small">b49730d1-d906-45bb-b1a6-18c61654dfd3</td>
                  <td>Functional Skills Maths</td>
                  <td align="center" class="text-bold">2.56</td>
                  <td>Maths Initial Assessment</td>
                  <td>03/02/2021 20:32:48</td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
</div>
<div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-condensed">
                                        <caption class="text-bold bg-gray">Diagnostic Assessment</caption>
                                        <tbody><tr><th>Session ID</th><th>Course Component Name</th><th>Ability Measurement</th><th>Measuring Assessment Name</th><th>Measured At</th></tr>
                                        <tr><td class="small">ead9fd2a-7570-4441-a04c-5a088f4c7433</td><td>Reading</td><td align="center" class="text-bold">2.9</td><td>English Diagnostic Assessment</td><td>03/02/2021 20:09:24</td></tr><tr><td class="small">ead9fd2a-7570-4441-a04c-5a088f4c7433</td><td>Spelling, Punctuation and Grammar</td><td align="center" class="text-bold">1.75</td><td>English Diagnostic Assessment</td><td>03/02/2021 20:09:24</td></tr><tr><td class="small">ead9fd2a-7570-4441-a04c-5a088f4c7433</td><td>Writing</td><td align="center" class="text-bold">2.9</td><td>English Diagnostic Assessment</td><td>03/02/2021 20:09:24</td></tr><tr><td class="small">ead9fd2a-7570-4441-a04c-5a088f4c7433</td><td>Writing - Text</td><td align="center" class="text-bold">2.9</td><td>English Diagnostic Assessment</td><td>03/02/2021 20:09:24</td></tr><tr><td class="small">ead9fd2a-7570-4441-a04c-5a088f4c7433</td><td>Reading - Text</td><td align="center" class="text-bold">2.88</td><td>English Diagnostic Assessment</td><td>03/02/2021 20:09:24</td></tr><tr><td class="small">ead9fd2a-7570-4441-a04c-5a088f4c7433</td><td>Reading - Word</td><td align="center" class="text-bold">2.92</td><td>English Diagnostic Assessment</td><td>03/02/2021 20:09:24</td></tr><tr><td class="small">ead9fd2a-7570-4441-a04c-5a088f4c7433</td><td>Grammar</td><td align="center" class="text-bold">2.55</td><td>English Diagnostic Assessment</td><td>03/02/2021 20:09:24</td></tr><tr><td class="small">ead9fd2a-7570-4441-a04c-5a088f4c7433</td><td>Punctuation</td><td align="center" class="text-bold">0.82</td><td>English Diagnostic Assessment</td><td>03/02/2021 20:09:24</td></tr><tr><td class="small">ead9fd2a-7570-4441-a04c-5a088f4c7433</td><td>Spelling</td><td align="center" class="text-bold">1.88</td><td>English Diagnostic Assessment</td><td>03/02/2021 20:09:24</td></tr><tr><td class="small">087d8eab-7933-4f6f-b8b5-5dc66d8e4660</td><td>Number</td><td align="center" class="text-bold">2.9</td><td>Maths Diagnostic Assessment</td><td>03/02/2021 21:33:21</td></tr><tr><td class="small">087d8eab-7933-4f6f-b8b5-5dc66d8e4660</td><td>Measure, Shape and Space</td><td align="center" class="text-bold">0.95</td><td>Maths Diagnostic Assessment</td><td>03/02/2021 21:33:21</td></tr><tr><td class="small">087d8eab-7933-4f6f-b8b5-5dc66d8e4660</td><td>Statistics and Data</td><td align="center" class="text-bold">2.16</td><td>Maths Diagnostic Assessment</td><td>03/02/2021 21:33:21</td></tr><tr><td class="small">087d8eab-7933-4f6f-b8b5-5dc66d8e4660</td><td>Whole Numbers</td><td align="center" class="text-bold">2.9</td><td>Maths Diagnostic Assessment</td><td>03/02/2021 21:33:21</td></tr><tr><td class="small">087d8eab-7933-4f6f-b8b5-5dc66d8e4660</td><td>Calculations</td><td align="center" class="text-bold">2.9</td><td>Maths Diagnostic Assessment</td><td>03/02/2021 21:33:21</td></tr><tr><td class="small">087d8eab-7933-4f6f-b8b5-5dc66d8e4660</td><td>Fractions, Decimals, Percentages and Ratio</td><td align="center" class="text-bold">2.9</td><td>Maths Diagnostic Assessment</td><td>03/02/2021 21:33:21</td></tr><tr><td class="small">087d8eab-7933-4f6f-b8b5-5dc66d8e4660</td><td>Measure</td><td align="center" class="text-bold">0.46</td><td>Maths Diagnostic Assessment</td><td>03/02/2021 21:33:21</td></tr><tr><td class="small">087d8eab-7933-4f6f-b8b5-5dc66d8e4660</td><td>Shape</td><td align="center" class="text-bold">1.25</td><td>Maths Diagnostic Assessment</td><td>03/02/2021 21:33:21</td></tr><tr><td class="small">087d8eab-7933-4f6f-b8b5-5dc66d8e4660</td><td>Handling Information and Data</td><td align="center" class="text-bold">0.87</td><td>Maths Diagnostic Assessment</td><td>03/02/2021 21:33:21</td></tr><tr><td class="small">087d8eab-7933-4f6f-b8b5-5dc66d8e4660</td><td>Statistics</td><td align="center" class="text-bold">2.9</td><td>Maths Diagnostic Assessment</td><td>03/02/2021 21:33:21</td></tr>                                    </tbody></table>
                                </div>
                            </div>
                        </div>
<?php } ?>

