<?php
class download_checklist implements IAction
{
	public function execute(PDO $link)
	{
        set_time_limit(0);
        ini_set('memory_limit','1024M');
        $sql = "SELECT distinct internaltitle from student_qualifications where framework_id = 0";
        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                $qual_id = $row['internaltitle'];

                $CSVFileName = "../uploads/am_raytheon/data_dump/" . $qual_id .".csv";
                $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
                fclose($FileHandle);
                $fp = fopen($CSVFileName, 'w');

                $sql2 = "SELECT evidences FROM student_qualifications WHERE internaltitle='$qual_id' LIMIT 0,1";
                $rows2 = DAO::getResultset($link, $sql2, DAO::FETCH_ASSOC);
                foreach($rows2 as $row2)
                {
                    $pageDom = XML::loadXmlDom($row2['evidences']);
                    $e = $pageDom->getElementsByTagName('evidence');
                    $csv_fields = array();
                    $csv_fields[] = 'L03';
                    $csv_fields[] = 'Forenames';
                    $csv_fields[] = 'Surname';
                    $csv_fields[] = 'Record Status';
                    $csv_fields[] = 'Start Date';
	                $csv_fields[] = 'Planned End Date';
	                $csv_fields[] = 'Actual End Date';
	                $csv_fields[] = 'Assessor';
                    foreach($e as $node)
                    {
                        $csv_fields[] = $node->getAttribute('title');
                        $csv_fields[] = "Comments";
                    }
                    fputcsv($fp, $csv_fields);
                }

                // Content
	            //$sql3 = "SELECT distinct student_qualifications.*, tr.*, IF(groups.assessor IS NOT NULL,CONCAT(users.firstnames,' ',users.surname),CONCAT(assessorsng.firstnames,' ',assessorsng.surname)) AS Assessor FROM student_qualifications INNER JOIN tr ON tr.id = student_qualifications.tr_id LEFT JOIN group_members ON group_members.tr_id = tr.id LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id LEFT JOIN courses ON courses.id = courses_tr.course_id LEFT JOIN groups ON groups.courses_id = courses.id AND group_members.groups_id = groups.id  LEFT JOIN users ON users.id = groups.assessor LEFT JOIN users AS assessorsng ON users.id = tr.assessor WHERE student_qualifications.internaltitle = '$qual_id';";
	            $sql3 = <<<SQL
 SELECT DISTINCT
  student_qualifications.`evidences`,
  tr.`l03`,
  tr.`firstnames`,
  tr.`surname`,
  CASE
    tr.`status_code`
    WHEN '1'
    THEN 'continuing'
    WHEN 2
    THEN 'completed'
    WHEN '3'
    THEN 'withdrawn'
    WHEN '4'
    THEN 'transferred to new provider'
    WHEN '5'
    THEN 'change in learning within same programme'
    WHEN '6'
    THEN 'temporary withdrawn'
    WHEN '7'
    THEN 'delete from ilr'
  END AS record_status,
  tr.start_date,
  tr.`target_date`,
  tr.`closure_date`,
  IF(
    groups.assessor IS NOT NULL,
    CONCAT(
      users.firstnames,
      ' ',
      users.surname
    ),
    CONCAT(
      assessorsng.firstnames,
      ' ',
      assessorsng.surname
    )
  ) AS assessor
FROM
  student_qualifications
  INNER JOIN tr
    ON tr.id = student_qualifications.tr_id
  LEFT JOIN group_members
    ON group_members.tr_id = tr.id
  LEFT JOIN courses_tr
    ON courses_tr.tr_id = tr.id
  LEFT JOIN courses
    ON courses.id = courses_tr.course_id
  LEFT JOIN groups
    ON groups.courses_id = courses.id
    AND group_members.groups_id = groups.id
  LEFT JOIN users
    ON users.id = groups.assessor AND users.`type` = 3
  LEFT JOIN users AS assessorsng
    ON users.id = tr.assessor AND assessorsng.type = 3
WHERE student_qualifications.internaltitle = '$qual_id'

;
SQL;

                $st3 = $link->query($sql3);
                if($st3)
                {
                    while($row3 = $st3->fetch())
                    {
                        $fields = Array();
                        $pageDom = XML::loadXmlDom($row3['evidences']);
                        $e = $pageDom->getElementsByTagName('evidence');
                        $fields[] = $row3['l03'];
                        $fields[] = $row3['firstnames'];
                        $fields[] = $row3['surname'];
                        $fields[] = $row3['record_status'];
                        $fields[] = $row3['start_date'];
	                    $fields[] = $row3['target_date'];
	                    $fields[] = $row3['closure_date'];
	                    $fields[] = $row3['assessor'];
                        foreach($e as $node)
                        {
                            if($node->getAttribute('status')=='a')
                                $fields[] = "Y";
                            elseif($node->getAttribute('status')=='o')
                                $fields[] = "O";
                            else
                                $fields[] = "NULL";
                            $fields[] =  $node->getAttribute('comments');
                        }
                        fputcsv($fp, $fields);
                    }
                }
            }
            fclose($fp);
        }

        // create object
        $zip = new ZipArchive();
        if ($zip->open("../uploads/am_raytheon/data_dump/data.zip", ZIPARCHIVE::CREATE) !== TRUE)
        {
            die ("Could not open archive");
        }
        $st = $link->query("SELECT distinct internaltitle from student_qualifications where framework_id = 0");
        if($st)
            while($row = $st->fetch())
                $zip->addFile("../uploads/am_raytheon/data_dump/" . $row['internaltitle'] . ".csv", $row['internaltitle'] . ".csv") or die ("ERROR: Could not add file:");
        $zip->close();
        http_redirect("do.php?_action=downloader&path=/am_raytheon/data_dump/&f=data.zip");

	}
}
?>