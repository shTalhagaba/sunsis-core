<?php
$onefile_linked = $pot_vo->onefile_id != '' ? true : false;
$onefile_saved_info = DAO::getSingleValue($link, "SELECT detail FROM onefile_tr WHERE tr_id = '{$pot_vo->id}' ORDER BY created_at DESC LIMIT 1");
$onefile_saved_info = $onefile_saved_info != '' ? json_decode($onefile_saved_info) : '';

// For Imported Records
$onefile_classroom_id = isset($onefile_saved_info->ClassroomID) ? $onefile_saved_info->ClassroomID : '';
if($onefile_classroom_id == '')
{
    $onefile_classroom_id = DAO::getSingleValue($link, "SELECT ClassroomID FROM onefile_learners WHERE ID = '{$pot_vo->onefile_id}'");
}
$onefile_episode_name = isset($onefile_saved_info->EpisodeName) ? $onefile_saved_info->EpisodeName : '';
if($onefile_episode_name == '')
{
    $onefile_episode_name = DAO::getSingleValue($link, "SELECT EpisodeName FROM onefile_learners WHERE ID = '{$pot_vo->onefile_id}'");
}

if(!$onefile_linked)
{
    echo <<<HTML

<h3>OneFile</h3>
<p class="introduction">This learner has not yet been created/linked with Onefile. You can use this panel to create this learner's record in Onefile.</p>
<h4>Create Learner in OneFile</h4>
<p class="introduction">OneFile learner record will be created with the following information:</p>

HTML;

}
else
{
	$onefileProgress = DAO::getSingleValue($link, "SELECT Progress FROM onefile_learners WHERE ID = '{$pot_vo->onefile_id}'");
	$onefileOtj = DAO::getObject($link, "SELECT * FROM onefile_otj WHERE onefile_learner_id = '{$pot_vo->onefile_id}'");
    $onefileOtjInfo = '';
    if(isset($onefileOtj->onefile_learner_id))
    {
        $onefileOtjInfo .= 'Total Contracted Hours: ' . $onefileOtj->contracted_hours . ' <br> ';
        $onefileOtjInfo .= 'Method of Calculating Min OTJ: ' . $onefileOtj->method_of_calc . ' <br> ';
        $onefileOtjInfo .= 'Planned OTJ (Hrs): ' . $onefileOtj->planned_otj . ' <br> ';
        $onefileOtjInfo .= 'Actual OTJ (Hrs): ' . $onefileOtj->actual_hours . ' <br> ';
        $onefileOtjInfo .= '% of Planned: ' . $onefileOtj->percent_of_planned . ' <br> ';
        $onefileOtjInfo .= 'Duration (Wks): ' . $onefileOtj->duration . ' <br> ';
        $onefileOtjInfo .= 'Min OTJ (Hrs): ' . $onefileOtj->min_otj . ' <br> ';
    }
    $onefileLatestReview = DAO::getObject($link, "SELECT * FROM onefile_reviews WHERE LearnerID = '{$pot_vo->onefile_id}' AND StartedOn IS NOT NULL ORDER BY StartedOn DESC LIMIT 1");
    $latestReviewInfo = '';
    if(isset($onefileLatestReview->ID))
    {
	$latestReviewInfo .= 'Created On: ' . Date::to($onefileLatestReview->CreatedOn, Date::DATETIME) . ' <br> ';
        $latestReviewInfo .= 'Scheduled For: ' . Date::to($onefileLatestReview->ScheduledFor, Date::DATETIME) . ' <br> ';
        $latestReviewInfo .= 'Started On: ' . Date::to($onefileLatestReview->StartedOn, Date::DATETIME) . ' <br> ';
        $latestReviewInfo .= 'Assessor Signed On: ' . Date::to($onefileLatestReview->AssessorSignedOn, Date::DATETIME) . ' <br> ';
        $latestReviewInfo .= 'Learner Signed On: ' . Date::to($onefileLatestReview->LearnerSignedOn, Date::DATETIME) . ' <br> ';
        $latestReviewInfo .= 'Progress: ' . $onefileLatestReview->Progress . ' <br> ';
    }
    $onefileLatestTlap = DAO::getObject($link, "SELECT * FROM onefile_tlap WHERE LearnerID = '{$pot_vo->onefile_id}' AND AssessorSignedOn IS NOT NULL ORDER BY PlanOn DESC LIMIT 1");
    $latestTlapInfo = '';
    if(isset($onefileLatestTlap->Title))
    {
        $latestTlapInfo .= 'Title: ' . $onefileLatestTlap->Title . ' <br> ';
        $latestTlapInfo .= 'Plan On: ' . Date::toShort($onefileLatestTlap->PlanOn) . ' <br> ';
        $latestTlapInfo .= 'Assessor Signed On: ' . Date::to($onefileLatestTlap->AssessorSignedOn, Date::DATETIME) . ' <br> ';
        $latestTlapInfo .= 'Learner Signed On: ' . Date::to($onefileLatestTlap->LearnerSignedOn, Date::DATETIME) . ' <br> ';
    }	

    echo <<<HTML

<h3>OneFile</h3>
<p class="introduction">This learner has been created/linked with Onefile. You can use this panel to update this learner's record in Onefile.</p>
<p class="introduction" style="color: green">
    Onefile ID: {$pot_vo->onefile_id}, Onefile Username: {$pot_vo->onefile_username}
</p>
<div style="float: right; color: green;">
    <h4 class="introduction">Information From Onefile: <button onclick="window.location.search += '&tabOnefile=1';" title="Refresh Onefile Info."><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom"></button></h4>
    <span class="introduction">Learner Progress: {$onefileProgress}%</span><br>
    <span class="introduction">---------- OTJ Info --------------------</span><br>
    {$onefileOtjInfo}
    <span class="introduction">---------- Latest Review Info ----------</span><br>
    {$latestReviewInfo}
    <span class="introduction">---------- Latest TLAP Info ----------</span><br>
    {$latestTlapInfo}
</div>
<h4>Update Learner in OneFile</h4>
<p class="introduction">Learner record has the following information in Sunesis:</p>

HTML;

}
?>


<form name="frmOnefile" id="frmOnefile" method="POST" action="do.php?_action=ajax_onefile">
    <input type="hidden" name="_action" value="ajax_onefile">
    <input type="hidden" name="subaction" value="<?php echo !$onefile_linked ? 'createLearnerInOnefile' : 'updateLearnerInOnefile'; ?>">
    <input type="hidden" name="tr_id" value="<?php echo $pot_vo->id; ?>">
    <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">

    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px; width: 75%;">
        <colgroup>
            <col width="170">
            <col>
            <col width="350">
            <col>
        </colgroup>
        <tr>
            <th>Organisation:</th>
            <td class="fieldValue" style="background-color: #D3D3D3;"><?php echo HTML::selectChosen('onefile_organisation_id', Onefile::getOnefileOrganisationsDdl($link), isset($onefile_saved_info->OrganisationID) ? $onefile_saved_info->OrganisationID : ''); ?></td>
        </tr>
        <tr>
            <th>First Name:</th>
            <td class="fieldValue"><?php echo $pot_vo->firstnames; ?></td>
        </tr>
        <tr>
            <th>Last Name:</th>
            <td class="fieldValue"><?php echo $pot_vo->surname; ?></td>
        </tr>
        <tr>
            <th>Email:</th>
            <td class="fieldValue" style="background-color: #D3D3D3;"><?php echo HTML::selectChosen('onefile_email', $emails_list, isset($onefile_saved_info->Email) ? $onefile_saved_info->Email : ''); ?></td>
        </tr>
        <tr>
            <th>Default Assessor:</th>
            <td class="fieldValue">
                <?php
                $_assessor_user = DAO::getObject($link, "SELECT CONCAT(surname, ', ', firstnames) AS name, onefile_user_id FROM users WHERE users.id = '{$pot_vo->assessor}'");
                if (isset($_assessor_user->name)) {
                    echo $_assessor_user->name . ' &nbsp; ';
                    echo $_assessor_user->onefile_user_id != '' ? '<span style="color: green">LINKED (OneFile Assessor ID: ' . $_assessor_user->onefile_user_id . ')</span>' : '<span style="color: red">NOT LINKED <img height="18px" src="images/register/disc-bad.png" /></span>';
                }
                ?>
                <input type="hidden" name="onefile_assessor_linked" value="<?php echo isset($_assessor_user->onefile_user_id) ? $_assessor_user->onefile_user_id : ''; ?>" />
            </td>
        </tr>
        <tr>
            <th>Classroom:</th>
            <td class="fieldValue" style="background-color: #D3D3D3;">
                <?php echo HTML::selectChosen('onefile_classroom_id', $onefile_classrooms_list, $onefile_classroom_id); ?>
                <span class="button" onclick="refresh_onefile_classrooms();">Refresh Classrooms List</span>
            </td>
        </tr>
        <tr>
            <th>Placement:</th>
            <td class="fieldValue">
                <?php
                echo $employer->legal_name . ' &nbsp; ';
                echo $employer->onefile_placement_id != '' ? '<span style="color: green">LINKED (OneFile Placement ID: ' . $employer->onefile_placement_id . ')</span>' : '<span style="color: red">NOT LINKED <img height="18px" src="images/register/disc-bad.png" /></span>';
                ?>
                <input type="hidden" name="onefile_placement_linked" value="<?php echo $employer->onefile_placement_id; ?>" />
            </td>
        </tr>
        <tr>
            <th>MISID:</th>
            <td class="fieldValue"><?php echo $pot_vo->username . '_' . $pot_vo->id; ?></td>
        </tr>
        <tr>
            <th>Mobile Number:</th>
            <td class="fieldValue"><?php echo $pot_vo->home_mobile; ?></td>
        </tr>
        <tr>
            <th>Telephone:</th>
            <td class="fieldValue"><?php echo $pot_vo->home_telephone; ?></td>
        </tr>
        <tr>
            <th>Home Address:</th>
            <td class="fieldValue">
                <?php
                echo $pot_vo->home_address_line_1 != '' ? $pot_vo->home_address_line_1 : '';
                echo $pot_vo->home_address_line_2 != '' ? '<br>' . $pot_vo->home_address_line_2 : '';
                echo $pot_vo->home_address_line_3 != '' ? '<br>' . $pot_vo->home_address_line_3 : '';
                echo $pot_vo->home_address_line_4 != '' ? '<br>' . $pot_vo->home_address_line_4 : '';
                echo $pot_vo->home_postcode != '' ? '<br>' . $pot_vo->home_postcode : '';
                ?>
            </td>
        </tr>
        <tr>
            <th>Work Address:</th>
            <td class="fieldValue">
                <?php
                echo $pot_vo->work_address_line_1 != '' ? $pot_vo->work_address_line_1 : '';
                echo $pot_vo->work_address_line_2 != '' ? '<br>' . $pot_vo->work_address_line_2 : '';
                echo $pot_vo->work_address_line_3 != '' ? '<br>' . $pot_vo->work_address_line_3 : '';
                echo $pot_vo->work_address_line_4 != '' ? '<br>' . $pot_vo->work_address_line_4 : '';
                echo $pot_vo->work_postcode != '' ? '<br>' . $pot_vo->work_postcode : '';
                ?>
            </td>
        </tr>
        <tr>
            <th>DOB:</th>
            <td class="fieldValue"><?php echo Date::toShort($pot_vo->dob); ?></td>
        </tr>
        <tr>
            <th>ULN:</th>
            <td class="fieldValue"><?php echo $pot_vo->uln; ?></td>
        </tr>
        <tr>
            <th>NINO:</th>
            <td class="fieldValue"><?php echo $pot_vo->ni; ?></td>
        </tr>
        <tr>
            <th>Episode Name:</th>
            <td class="fieldValue" style="background-color: #D3D3D3;"><input style="width: 100%;" type="text" name="onefile_episode_name" id="onefile_episode_name" value="<?php echo $onefile_episode_name != '' ? $onefile_episode_name : $framework_title; ?>" /></td>
        </tr>
        <tr>
            <th>Center Register:</th>
            <td class="fieldValue" style="background-color: #D3D3D3;"><?php echo HTML::datebox('onefile_centre_register', isset($onefile_saved_info->CentreRegister) ? $onefile_saved_info->CentreRegister : $pot_vo->created); ?></td>
        </tr>
        <tr>
            <th>Start On:</th>
            <td class="fieldValue"><?php echo Date::toShort($pot_vo->start_date); ?></td>
        </tr>
        <tr>
            <th>Planned End Date:</th>
            <td class="fieldValue"><?php echo Date::toShort($pot_vo->target_date); ?></td>
        </tr>
        <tr>
            <th>L12:</th>
            <td class="fieldValue"><?php echo $ethnicity; ?></td>
        </tr>
        <tr>
            <th>L13:</th>
            <td class="fieldValue"><?php echo $pot_vo->gender; ?></td>
        </tr>
        <tr>
            <th>L14:</th>
            <td class="fieldValue">
                <?php
                echo $user->l14 == 1 ? 'Learner considers to have LLDD' : ($user->l14 == 2 ? 'Learner does not conisder to have LLDD' : ($user->l14 == 9 ? 'No information provided' : ''));
                ?>
            </td>
        </tr>
        <tr>
            <th>Select Learning Aims:</th>
            <td class="fieldValue">
                <table class="resultset">
                <?php
                $student_qualifications = DAO::getResultset($link, "SELECT auto_id, id, title, onefile_learning_aim_id, framework_id FROM student_qualifications WHERE tr_id = '{$pot_vo->id}' ORDER BY qual_sequence", DAO::FETCH_ASSOC);
                foreach($student_qualifications AS $student_qual)
                {
                    $fwk_qual_onefile_standard_id = DAO::getSingleValue($link, "SELECT onefile_standard_id FROM framework_qualifications WHERE id = '{$student_qual['id']}' AND framework_id = '{$student_qual['framework_id']}'");
                    echo '<tr>';
                    echo '<td><input type="checkbox" name="chkOnefileStandards[]" value="' . trim(str_replace("/", "", $student_qual['auto_id'])) . '" /></td>';
                    echo '<td>' . $student_qual['id'] . '</td>';
                    echo '<td>' . $student_qual['title'] . '</td>';
                    echo '<td>';
                    echo $student_qual['onefile_learning_aim_id'] != '' ? 
                        '<span style="color: green">LINKED (OneFile Learning Aim ID: ' . $student_qual['onefile_learning_aim_id'] . ')</span>' : 
                        '<span style="color: red">NOT LINKED </span>';
                    echo '</td>';
                    if($onefile_linked)
                    {
                        echo '<td>';
                        echo $fwk_qual_onefile_standard_id != '' ? '<button type="button" onclick="pushAimInOneFile(\''.$student_qual['auto_id'].'\', \''.$pot_vo->id.'\', \''.$student_qual['onefile_learning_aim_id'].'\')">Push in OneFile</button>' : '';
                        echo '</td>';
                    }
                    echo '</tr>';
                }
                ?>
                </table>
            </td>
        </tr>
	<?php
        if(!$onefile_linked)
        {
            $learner_reviews_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessor_review WHERE tr_id = '{$pot_vo->id}'");
            if($learner_reviews_count > 0)
            {
                echo '<tr>';
                echo '<th>Push ' . $learner_reviews_count . ' scheduled reviews?</th>';
                echo '<td>';
                echo HTML::select('push_reviews_to_onefile', [[1, 'Yes'], [0, 'No']]);
                echo '</td>';
                echo '</tr>';
            }
        }
        ?>
        <tr>
            <td colspan="2">
                <button type="button" role="button" style="color: green; width: 100%; height: 50px; font-weight: bold;" onclick="submitOnefileForm();">
                    <?php echo !$onefile_linked ? 'Click to Push in OneFile' : 'Click to Update in OneFile'; ?>
                </button>
            </td>
        </tr>
    </table>
</form>

<hr>

<h3>Logs</h3>
<?php 
$onefile_logs_result = DAO::getResultset($link, "SELECT action, created_by, created_at, (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = onefile_tr.created_by) AS created_by_name FROM onefile_tr WHERE onefile_tr.tr_id = '{$pot_vo->id}' ORDER BY created_at", DAO::FETCH_ASSOC);
if(count($onefile_logs_result) == 0)
{
    echo '<p class="introduction"><i>No records found.</i></p>';
}
else
{
    echo '<table class="resultset" cellpadding="6">';
    echo '<thead><tr><th>Action</th><th>By</th><th>Timestamp</th></tr></thead>';
    echo '<tbody>';
    foreach($onefile_logs_result AS $onefile_log_row)
    {
        echo '<tr>';
        echo '<td>' . $onefile_log_row['action'] . '</td>';
        echo '<td>' . $onefile_log_row['created_by_name'] . '</td>';
        echo '<td>' . Date::to($onefile_log_row['created_at'], Date::DATETIME) . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</tr>';
}
?>