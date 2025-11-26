<?php
$caseload = '';
if($_SESSION['caseload_learners_only'] == '1')
    $caseload = " AND tr.coach = '{$_SESSION['user']->id}' ";
$tg_learners_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr WHERE tr.tg_id = '{$tg->id}' {$caseload}");
?>
<div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-8 well well-sm">
        <?php if($_SESSION['user']->isAdmin()){?>
            <span class="pull-left btn btn-xs btn-primary"
                  onclick="window.location.href='do.php?_action=read_course_v2&subview=add_edit_training_group&id=<?php echo $course->id; ?>&group_id=<?php echo $group->id; ?>&tg_id=<?php echo $tg->id; ?>&from_view=training_group_view'">
			<i class="fa fa-edit"></i> Edit Training Group
		</span> &nbsp;
        <?php } ?>
        <span class="btn btn-xs btn-primary"
              onclick="window.location.href='do.php?_action=add_exam_results_multiple&subview=show_learners&course_id=<?php echo $course->id; ?>&group_id=<?php echo $group->id; ?>&tg_id=<?php echo $tg->id; ?>'">
			<i class="fa fa-graduation-cap"></i> Add Exam Result
		</span>
        <span class="btn btn-xs btn-primary"
              onclick="window.location.href='do.php?_action=add_learners_tracking&subview=show_learners&course_id=<?php echo $course->id; ?>&group_id=<?php echo $group->id; ?>&tg_id=<?php echo $tg->id; ?>'">
			<i class="fa fa-graduation-cap"></i> Record Tracking
		</span>
        <?php if($_SESSION['user']->isAdmin()){?>
            <span class="pull-right btn btn-xs btn-danger" onclick="delete_training_group('<?php echo $tg->id; ?>');"><i class="fa fa-trash"></i> Delete Training Group</span>
        <?php } ?>
    </div>
    <div class="col-sm-2"></div>
</div>
<p></p>
<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header"><span class="lead text-bold">Training Group Detail </span></div>
            <div class="box-body">
                <table class="table table-bordered">
                    <tr>
                        <td><span class="text-bold">Title:</span><br><?php echo $tg->title; ?></td>
                        <td><span class="text-bold">Cohort Title:</span><br><a href="do.php?_action=read_course_v2&subview=group_view&id=<?php echo $course->id; ?>&group_id=<?php echo $group->id; ?>"><?php echo $group->title; ?></a></td>
                        <td><span class="text-bold">Learners Count:</span><br><?php echo $tg_learners_count; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<p></p>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header"><span class="lead text-bold">File Repository</span> &nbsp; <small>you can upload files e.g. delivery plan for this training group</small></div>
            <div class="box-body">
                <table class="table row-border">
                    <?php
                    $repository = Repository::getRoot().'/t_groups/'.$tg->id;
                    $files = Repository::readDirectory($repository);
                    if(count($files) == 0){
                        echo '<tr><td><i>No files uploaded</i></td></tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        $col = 0;
                        foreach($files as $f)
                        {
                            if($f->isDir()){
                                continue;
                            }
                            $col++;
                            if($col > 3)
                            {
                                $col = 0;
                                echo '</tr><tr>';
                            }
                            $ext = new SplFileInfo($f->getName());
                            $ext = $ext->getExtension();
                            $image = 'fa-file';
                            if($ext == 'doc' || $ext == 'docx')
                                $image = 'fa-file-word-o';
                            elseif($ext == 'pdf')
                                $image = 'fa-file-pdf-o';
                            elseif($ext == 'txt')
                                $image = 'fa-file-text-o';
                            echo '<td>';
                            echo '<a href="' . $f->getDownloadURL() . '"><i class="fa '.$image.'"></i> ' . htmlspecialchars((string)$f->getName()) . '</a><br><span class="direct-chat-timestamp "><i class="fa fa-clock-o"></i> <small>' . date("d/m/Y H:i:s", $f->getModifiedTime()) .'</small></span><br>';
                            echo '<span title="Delete this file" class="btn btn-xs btn-danger" onclick="deleteFile(\''.$f->getRelativePath().'\');"><i class="fa fa-trash"></i></span>';
                            echo '</td>';
                        }
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>
            <div class="box-footer">
                <form name="frmUploadFileToTrainingGroup" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" ENCTYPE="multipart/form-data">
                    <input type="hidden" name="_action" value="upload_file_training_group" />
                    <input type="hidden" name="subaction" value="upload_file" />
                    <input type="hidden" name="course_id" value="<?php echo $course->id; ?>" />
                    <input type="hidden" name="group_id" value="<?php echo $group->id; ?>" />
                    <input type="hidden" name="tg_id" value="<?php echo $tg->id; ?>" />
                    <div class="row">
                        <div class="col-sm-6"><input class="compulsory" type="file" name="uploaded_tg_file" /></div>
                        <div class="col-sm-6"><span class="btn btn-sm btn-success" onclick="uploadFileToTrainingGroup();"><i class="fa fa-upload"></i> Click to upload</span></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <span class="lead text-bold">Learners </span> <span class="badge bg-purple"><?php echo $tg_learners_count; ?></span>
    </div>
    <div class="col-sm-12">
        <?php
        //echo $tg_learners->render($link, $course->id, ['subview' => 'training_group_view']);
        $tracking_template = $course->getKSBTemplate($link);
        $sql = <<<SQL
SELECT
	tr.id AS tr_id,
	tr.firstnames,
	tr.surname,
	tr.status_code,tr.gender,
	(SELECT title FROM contracts WHERE id = tr.contract_id) AS contract,
	(SELECT aptitude FROM student_qualifications WHERE tr_id = tr.`id` AND LOWER(internaltitle) LIKE '%math%' AND qualification_type = 'FS' AND student_qualifications.level = '1' LIMIT 1) AS l1_maths_exempt,
	(SELECT aptitude FROM student_qualifications WHERE tr_id = tr.`id` AND LOWER(internaltitle) LIKE '%math%' AND qualification_type = 'FS' AND student_qualifications.level = '2' LIMIT 1) AS l2_maths_exempt,
	(SELECT aptitude FROM student_qualifications WHERE tr_id = tr.`id` AND LOWER(internaltitle) LIKE '%english%' AND qualification_type = 'FS' AND student_qualifications.level = '1' LIMIT 1) AS l1_eng_exempt,
	(SELECT aptitude FROM student_qualifications WHERE tr_id = tr.`id` AND LOWER(internaltitle) LIKE '%english%' AND qualification_type = 'FS' AND student_qualifications.level = '2' LIMIT 1) AS l2_eng_exempt,
	
	(SELECT MAX(tr_tracking.`date`) FROM tr_tracking WHERE tr_id = tr.`id`) AS max_tracking_date,
	(SELECT MAX(actual_date) FROM additional_support WHERE tr_id = tr.id) AS max_support_date

FROM
	tr INNER JOIN courses_tr ON tr.id = courses_tr.tr_id
WHERE
	courses_tr.course_id = '{$course->id}' AND tr.tg_id = '{$tg->id}' $caseload
ORDER BY
	tr.surname, firstnames
SQL;
        $st_tg = DAO::query($link, $sql);
        if($st_tg)
        {
            echo '<div class="well well-sm text-center text-bold" style="padding: 1px;">' . $st_tg->rowCount() . ' records</div>';
            echo '<div class="table-responsive">';
            echo '<table id="tblTGLearners" class="table table-bordered">';
            echo '<thead>';
            echo '<tr>';
            echo '<th class="bg-green">&nbsp;</th><th class="bg-green">&nbsp;</th><th class="bg-green">Learner</th><th class="bg-green">LDoC</th>';
            foreach($tracking_template->sections AS $section)
            {
                echo '<th class="text-center text-orange bg-black"><span style = "letter-spacing: 2px;">' . str_replace(' ', '&nbsp;', strtoupper($section->section_title)) . '</span></th>';
            }
            echo '<th>L1 Maths</th><th>L2 Maths</th>';
            echo '<th>L1 Eng.</th><th>L2 Eng.</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            while($row = $st_tg->fetch())
            {
                echo '<tr>';
                echo '<td><span class="btn btn-xs btn-primary" title="View/Edit compliance checklist" onclick="window.location.href=\'do.php?_action=edit_tr_compliance&tr_id='.$row['tr_id'].'\'"><i class="fa fa-list"></i></span> </td>';
                echo '<td title=#'.$row['tr_id'] . '>';
                $folderColour = $row['gender'] == 'M' ? 'blue' : 'red';
                $textStyle = '';
                switch($row['status_code'])
                {
                    case 1:
                        echo "<img src=\"/images/folder-$folderColour.png\" border=\"0\" alt=\"\" />";
                        break;

                    case 2:
                        echo "<img src=\"/images/folder-$folderColour-happy.png\" border=\"0\" alt=\"\" />";
                        break;

                    case 3:
                    case 6:
                        echo "<img src=\"/images/folder-$folderColour-sad.png\" border=\"0\" alt=\"\" />";
                        break;

                    case 4:
                        echo "<img src=\"/images/transfer.png\" border=\"0\" alt=\"\" />";
                        break;
                    case 5:
                        echo "<img src=\"/images/folder-$folderColour.png\" border=\"0\" style=\"opacity:0.3\" alt=\"\" />";
                        $textStyle = 'text-decoration:line-through;color:gray';
                        break;

                    default:
                        echo '?';
                        break;
                }
                echo '</td>';
                echo "<td align=\"left\" style=\"$textStyle;font-size:100%;\">"
                    . HTML::cell($row['surname'])
                    . '<div style="margin-left:5px;color:gray;font-style:italic;">'
                    . HTML::cell($row['firstnames']) . '</div>';
                echo '</td>';
                if($row['max_tracking_date'] != '' && $row['max_support_date'] != '')
                {
                    $max_tracking_date = new Date($row['max_tracking_date']);
                    $max_support_date = new Date($row['max_support_date']);
                    echo $max_tracking_date->after($max_support_date) ? '<td>' . $max_tracking_date->formatShort() . '</td>' : '<td>' . $max_support_date->formatShort() . '</td>';
                }
                elseif($row['max_tracking_date'] == '')
                {
                    echo '<td>' . Date::toShort($row['max_support_date']) . '</td>';
                }
                elseif($row['max_support_date'] == '')
                {
                    echo '<td>' . Date::toShort($row['max_tracking_date']) . '</td>';
                }
                else
                {
                    echo '<td></td>';
                }
                foreach($tracking_template->sections AS $section)
                {
                    $evidence_ids = array_map(function($evidence){
                        return $evidence->evidence_id;
                    }, $section->evidences);
                    $implode_evidence_ids = implode(',', $evidence_ids);
                    $section_evidences = count($evidence_ids);
                    if($section_evidences == 0)
                        $learner_evidence_count = 0;
                    else
                        $learner_evidence_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_tracking WHERE tr_id = '{$row['tr_id']}' AND tracking_id IN ({$implode_evidence_ids})");
                    $percentage = $section_evidences > 0 ? round(($learner_evidence_count/$section_evidences)*100, 2) : 0;
                    echo '<td>';
                    $class_text = '';
                    if($learner_evidence_count == $section_evidences)
                        $class_text = 'text-green';
                    if($learner_evidence_count == 0)
                        $class_text = 'text-red';
                    echo "<span class='{$class_text}'>{$learner_evidence_count} / {$section_evidences} = {$percentage}%</span>";
                    echo '</td>';
                }
                echo is_null($row['l1_maths_exempt']) ? '<td align="center" class="bg-red">' : '<td align="center">';
                if($row['l1_maths_exempt'] == '1')
                {
                    echo "<img style='width:35px; height:35px;' src=\"/images/exempt.gif\" border=\"0\" />";
                }
                else
                {
                    $l1_exam = DAO::getObject($link, "SELECT * FROM exam_results WHERE LOWER(qualification_title) LIKE '%math%' AND LOWER(qualification_title) LIKE '%level 1%' AND tr_id = '{$row['tr_id']}' ORDER BY id DESC LIMIT 1");
                    if(isset($l1_exam->id))
                    {
                        if(strtolower($l1_exam->exam_result) == 'fail')
                        {
                            echo '<i class="fa fa-remove"></i> ';
                        }
                        else
                        {
                            echo $l1_exam->attempt_no == '1' ? '<i class="fa fa-check text-green"></i>' : '<i class="fa fa-check text-red"></i>';
                        }
                    }
                }
                echo '</td>';
                echo is_null($row['l2_maths_exempt']) ? '<td align="center" class="bg-red">' : '<td align="center">';
                if($row['l2_maths_exempt'] == '1')
                {
                    echo "<img style='width:35px; height:35px;' src=\"/images/exempt.gif\" border=\"0\" />";
                }
                else
                {
                    $l2_exam = DAO::getObject($link, "SELECT * FROM exam_results WHERE LOWER(qualification_title) LIKE '%math%' AND LOWER(qualification_title) LIKE '%level 2%' AND tr_id = '{$row['tr_id']}' ORDER BY id DESC LIMIT 1");
                    if(isset($l2_exam->id))
                    {
                        if(strtolower($l2_exam->exam_result) == 'fail')
                        {
                            echo '<i class="fa fa-remove"></i> ';
                        }
                        else
                        {
                            echo $l2_exam->attempt_no == '1' ? '<i class="fa fa-check text-green"></i>' : '<i class="fa fa-check text-red"></i>';
                        }
                    }
                }
                echo '</td>';

                echo is_null($row['l1_eng_exempt']) ? '<td align="center" class="bg-red">' : '<td align="center">';
                if($row['l1_eng_exempt'] == '1')
                {
                    echo "<img style='width:35px; height:35px;' src=\"/images/exempt.gif\" border=\"0\" />";
                }
                else
                {
                    $l1_eng_read = DAO::getObject($link, "SELECT * FROM exam_results WHERE LOWER(qualification_title) LIKE '%eng%' AND LOWER(qualification_title) LIKE '%level 1%' AND LOWER(unit_title) LIKE '%read%' AND tr_id = '{$row['tr_id']}' ORDER BY id DESC LIMIT 1");
                    $l1_eng_write = DAO::getObject($link, "SELECT * FROM exam_results WHERE LOWER(qualification_title) LIKE '%eng%' AND LOWER(qualification_title) LIKE '%level 1%' AND LOWER(unit_title) LIKE '%writ%' AND tr_id = '{$row['tr_id']}' ORDER BY id DESC LIMIT 1");
                    $l1_eng_speak = DAO::getObject($link, "SELECT * FROM exam_results WHERE LOWER(qualification_title) LIKE '%eng%' AND LOWER(qualification_title) LIKE '%level 1%' AND LOWER(unit_title) LIKE '%speak%' AND tr_id = '{$row['tr_id']}' ORDER BY id DESC LIMIT 1");
                    if(isset($l1_eng_read->id) && isset($l1_eng_write->id) && isset($l1_eng_speak->id))
                    {
                        if(strtolower($l1_eng_read->exam_result) == 'fail' || strtolower($l1_eng_write->exam_result) == 'fail' || strtolower($l1_eng_speak->exam_result) == 'fail')
                        {
                            echo '<i class="fa fa-remove"></i> ';
                        }
                        elseif(strtolower($l1_eng_read->exam_result) == 'pass' && strtolower($l1_eng_write->exam_result) == 'pass' && strtolower($l1_eng_speak->exam_result) == 'pass')
                        {
                            if($l1_eng_read->attempt_no == '1' && $l1_eng_write->attempt_no == '1' && $l1_eng_speak->attempt_no == '1')
                                echo '<i class="fa fa-check text-green"></i>';
                            else
                                echo '<i class="fa fa-check text-red"></i>';
                        }
                    }
                }
                echo '</td>';
                echo is_null($row['l2_eng_exempt']) ? '<td align="center" class="bg-red">' : '<td align="center">';
                if($row['l2_eng_exempt'] == '1')
                {
                    echo "<img style='width:35px; height:35px;' src=\"/images/exempt.gif\" border=\"0\" />";
                }
                else
                {
                    $l2_eng_read = DAO::getObject($link, "SELECT * FROM exam_results WHERE LOWER(qualification_title) LIKE '%eng%' AND LOWER(qualification_title) LIKE '%level 2%' AND LOWER(unit_title) LIKE '%read%' AND tr_id = '{$row['tr_id']}' ORDER BY id DESC LIMIT 1");
                    $l2_eng_write = DAO::getObject($link, "SELECT * FROM exam_results WHERE LOWER(qualification_title) LIKE '%eng%' AND LOWER(qualification_title) LIKE '%level 2%' AND LOWER(unit_title) LIKE '%writ%' AND tr_id = '{$row['tr_id']}' ORDER BY id DESC LIMIT 1");
                    $l2_eng_speak = DAO::getObject($link, "SELECT * FROM exam_results WHERE LOWER(qualification_title) LIKE '%eng%' AND LOWER(qualification_title) LIKE '%level 2%' AND LOWER(unit_title) LIKE '%speak%' AND tr_id = '{$row['tr_id']}' ORDER BY id DESC LIMIT 1");
                    if(isset($l2_eng_read->id) && isset($l2_eng_write->id) && isset($l2_eng_speak->id))
                    {
                        if(strtolower($l2_eng_read->exam_result) == 'fail' || strtolower($l2_eng_write->exam_result) == 'fail' || strtolower($l2_eng_speak->exam_result) == 'fail')
                        {
                            echo '<i class="fa fa-remove"></i> ';
                        }
                        elseif(strtolower($l2_eng_read->exam_result) == 'pass' && strtolower($l2_eng_write->exam_result) == 'pass' && strtolower($l2_eng_speak->exam_result) == 'pass')
                        {
                            if($l2_eng_read->attempt_no == '1' && $l2_eng_write->attempt_no == '1' && $l2_eng_speak->attempt_no == '1')
                                echo '<i class="fa fa-check text-green"></i>';
                            else
                                echo '<i class="fa fa-check text-red"></i>';
                        }
                    }
                }
                echo '</td>';

                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }
        ?>
    </div>
</div>



