<?php
class ajax_reset_milestones implements IAction
{
	public function execute(PDO $link)
	{
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';

		if(isset($tr_id) && isset($qualification_id))
			$sql = "delete from student_milestones where tr_id = {$tr_id} and qualification_id = '{$qualification_id}'";
		else
			$sql = "truncate student_milestones";
		$st = $link->query($sql);

		if($tr_id!='' && $qualification_id!='')
			$sql = "SELECT *, timestampdiff(MONTH, start_date, end_date) as months FROM student_qualifications where end_date > curdate() and timestampdiff(MONTH, start_date, end_date)>0 and tr_id = {$tr_id} and id = '{$qualification_id}'";
		else
			$sql = "SELECT *, timestampdiff(MONTH, start_date, end_date) as months FROM student_qualifications where end_date > curdate() and timestampdiff(MONTH, start_date, end_date)>0";
		$st = $link->query($sql);
		$unit=0;
		while($row = $st->fetch())
		{
			//$pageDom = new DomDocument();
			//@$pageDom->loadXML(utf8_encode($row['evidences']));
            $m = array();
            if($row['evidences']!='')
            {
	            $pageDom = XML::loadXmlDom(mb_convert_encoding($row['evidences'],'UTF-8'));
                $evidences = $pageDom->getElementsByTagName('unit');
                $data = array();
                foreach($evidences as $evidence)
                {
                   // $data = array();
                    $unit_id = $evidence->getAttribute('owner_reference');
                    $tr_id = $row['tr_id'];
                    $framework_id = $row['framework_id'];
                    $qualification_id = $row['id'];
                    $internaltitle = $row['internaltitle'];

                    // Old data sometimes has no owner reference for a unit
                    if(!$unit_id){
                        continue;
                    }

                    $m = Array();
                    for($a = 1; $a<=$row['months']; $a++)
                    {
                        if($a==$row['months'])
                            $m[] = 100;
                        else
                            $m[] = sprintf("%.1f", 100 / $row['months'] * $a);
                    }
                    for($a = $row['months']+1; $a<=36; $a++)
                    {
                        $m[] = 100;
                    }

                    $m['framework_id'] = $framework_id;
                    $m['qualification_id'] = $qualification_id;
                    $m['internaltitle'] = $internaltitle;
                    $m['unit_id'] = $unit_id ? $unit_id:'';
                    $m['month_1'] = $m[0];
                    $m['month_2'] = $m[1];
                    $m['month_3'] = $m[2];
                    $m['month_4'] = $m[3];
                    $m['month_5'] = $m[4];
                    $m['month_6'] = $m[5];
                    $m['month_7'] = $m[6];
                    $m['month_8'] = $m[7];
                    $m['month_9'] = $m[8];
                    $m['month_10'] = $m[9];
                    $m['month_11'] = $m[10];
                    $m['month_12'] = $m[11];
                    $m['month_13'] = $m[12];
                    $m['month_14'] = $m[13];
                    $m['month_15'] = $m[14];
                    $m['month_16'] = $m[15];
                    $m['month_17'] = $m[16];
                    $m['month_18'] = $m[17];
                    $m['month_19'] = $m[18];
                    $m['month_20'] = $m[19];
                    $m['month_21'] = $m[20];
                    $m['month_22'] = $m[21];
                    $m['month_23'] = $m[22];
                    $m['month_24'] = $m[23];
                    $m['month_25'] = $m[24];
                    $m['month_26'] = $m[25];
                    $m['month_27'] = $m[26];
                    $m['month_28'] = $m[27];
                    $m['month_29'] = $m[28];
                    $m['month_30'] = $m[29];
                    $m['month_31'] = $m[30];
                    $m['month_32'] = $m[31];
                    $m['month_33'] = $m[32];
                    $m['month_34'] = $m[33];
                    $m['month_35'] = $m[34];
                    $m['month_36'] = $m[35];
                    $m['id'] = 1;
                    $m['tr_id'] = $tr_id;
                    $m['chosen'] = 1;
				    //$data[] = $m;
                    DAO::execute($link, "insert into student_milestones (framework_id, qualification_id, internaltitle, unit_id, month_1, month_2, month_3, month_4, month_5, month_6, month_7, month_8,  month_9, month_10, month_11, month_12, month_13, month_14, month_15, month_16, month_17, month_18, month_19, month_20, month_21, month_22, month_23, month_24, month_25, month_26, month_27, month_28, month_29, month_30, month_31, month_32, month_33, month_34, month_35, month_36, id, tr_id, chosen) values ($framework_id, '$qualification_id','$internaltitle','$unit_id',$m[0],$m[1],$m[2],$m[3],$m[4],$m[5],$m[6],$m[7],$m[8],$m[9],$m[10],$m[11],$m[12],$m[13],$m[14],$m[15],$m[16],$m[17],$m[18],$m[19],$m[20],$m[21],$m[22],$m[23],$m[24],$m[25],$m[26],$m[27],$m[28],$m[29],$m[30],$m[31],$m[32],$m[33],$m[34],$m[35],1,$tr_id,1)");
                }

                if($tr_id!='')
                {
                    DAO::execute($link, "UPDATE tr
LEFT OUTER JOIN (
		SELECT tr.id AS tr_id,SUM(`sub`.target * proportion / (SELECT SUM(proportion)
                                        FROM   student_qualifications
                                        WHERE  tr_id = tr.id
                                               AND aptitude != 1)) AS result
FROM tr
       LEFT OUTER JOIN (SELECT student_milestones.tr_id,
                               student_qualifications.proportion,
                               CASE TIMESTAMPDIFF(MONTH, student_qualifications.start_date, CURDATE())
                                 WHEN -1 THEN 0
                                 WHEN -2 THEN 0
                                 WHEN -3 THEN 0
                                 WHEN -4 THEN 0
                                 WHEN -5 THEN 0
                                 WHEN -6 THEN 0
                                 WHEN -7 THEN 0
                                 WHEN -8 THEN 0
                                 WHEN -9 THEN 0
                                 WHEN -10 THEN 0
                                 WHEN 0 THEN 0
                                 WHEN 1 THEN AVG(student_milestones.month_1)
                                 WHEN 2 THEN AVG(student_milestones.month_2)
                                 WHEN 3 THEN AVG(student_milestones.month_3)
                                 WHEN 4 THEN AVG(student_milestones.month_4)
                                 WHEN 5 THEN AVG(student_milestones.month_5)
                                 WHEN 6 THEN AVG(student_milestones.month_6)
                                 WHEN 7 THEN AVG(student_milestones.month_7)
                                 WHEN 8 THEN AVG(student_milestones.month_8)
                                 WHEN 9 THEN AVG(student_milestones.month_9)
                                 WHEN 10 THEN AVG(student_milestones.month_10)
                                 WHEN 11 THEN AVG(student_milestones.month_11)
                                 WHEN 12 THEN AVG(student_milestones.month_12)
                                 WHEN 13 THEN AVG(student_milestones.month_13)
                                 WHEN 14 THEN AVG(student_milestones.month_14)
                                 WHEN 15 THEN AVG(student_milestones.month_15)
                                 WHEN 16 THEN AVG(student_milestones.month_16)
                                 WHEN 17 THEN AVG(student_milestones.month_17)
                                 WHEN 18 THEN AVG(student_milestones.month_18)
                                 WHEN 19 THEN AVG(student_milestones.month_19)
                                 WHEN 20 THEN AVG(student_milestones.month_20)
                                 WHEN 21 THEN AVG(student_milestones.month_21)
                                 WHEN 22 THEN AVG(student_milestones.month_22)
                                 WHEN 23 THEN AVG(student_milestones.month_23)
                                 WHEN 24 THEN AVG(student_milestones.month_24)
                                 WHEN 25 THEN AVG(student_milestones.month_25)
                                 WHEN 26 THEN AVG(student_milestones.month_26)
                                 WHEN 27 THEN AVG(student_milestones.month_27)
                                 WHEN 28 THEN AVG(student_milestones.month_28)
                                 WHEN 29 THEN AVG(student_milestones.month_29)
                                 WHEN 30 THEN AVG(student_milestones.month_30)
                                 WHEN 31 THEN AVG(student_milestones.month_31)
                                 WHEN 32 THEN AVG(student_milestones.month_32)
                                 WHEN 33 THEN AVG(student_milestones.month_33)
                                 WHEN 34 THEN AVG(student_milestones.month_34)
                                 WHEN 35 THEN AVG(student_milestones.month_35)
                                 WHEN 36 THEN AVG(student_milestones.month_36)
                                 ELSE 100
                               END AS target
                        FROM   student_milestones
                               LEFT JOIN student_qualifications
                                       ON student_qualifications.id =
                                          student_milestones.`qualification_id`
                                          AND student_milestones.tr_id =
                                              student_qualifications.`tr_id`

                                          AND
student_qualifications.aptitude != 1
                        GROUP  BY student_milestones.`tr_id`, student_milestones.`qualification_id`) AS
                       `sub`
                     ON tr.id = `sub`.tr_id
                    GROUP BY tr.`id`
	) AS `subquery`
		ON `subquery`.tr_id = tr.id

SET target = result
WHERE tr.id = '$tr_id';");

                }


            }
            else
            {
	            $unit_id = "Sudo";
                $tr_id = $row['tr_id'];
                $framework_id = $row['framework_id'];
                $qualification_id = $row['id'];
                $internaltitle = $row['internaltitle'];

                $m = Array();
                for($a = 1; $a<=$row['months']; $a++)
                {
                    if($a==$row['months'])
                        $m[] = 100;
                    else
                        $m[] = sprintf("%.1f", 100 / $row['months'] * $a);
                }
                for($a = $row['months']+1; $a<=36; $a++)
                {
                    $m[] = 100;
                }

                $m['framework_id'] = $framework_id;
                $m['qualification_id'] = $qualification_id;
                $m['internaltitle'] = $internaltitle;
                $m['unit_id'] = $unit_id ? $unit_id:'';
                $m['month_1'] = $m[0];
                $m['month_2'] = $m[1];
                $m['month_3'] = $m[2];
                $m['month_4'] = $m[3];
                $m['month_5'] = $m[4];
                $m['month_6'] = $m[5];
                $m['month_7'] = $m[6];
                $m['month_8'] = $m[7];
                $m['month_9'] = $m[8];
                $m['month_10'] = $m[9];
                $m['month_11'] = $m[10];
                $m['month_12'] = $m[11];
                $m['month_13'] = $m[12];
                $m['month_14'] = $m[13];
                $m['month_15'] = $m[14];
                $m['month_16'] = $m[15];
                $m['month_17'] = $m[16];
                $m['month_18'] = $m[17];
                $m['month_19'] = $m[18];
                $m['month_20'] = $m[19];
                $m['month_21'] = $m[20];
                $m['month_22'] = $m[21];
                $m['month_23'] = $m[22];
                $m['month_24'] = $m[23];
                $m['month_25'] = $m[24];
                $m['month_26'] = $m[25];
                $m['month_27'] = $m[26];
                $m['month_28'] = $m[27];
                $m['month_29'] = $m[28];
                $m['month_30'] = $m[29];
                $m['month_31'] = $m[30];
                $m['month_32'] = $m[31];
                $m['month_33'] = $m[32];
                $m['month_34'] = $m[33];
                $m['month_35'] = $m[34];
                $m['month_36'] = $m[35];
                $m['id'] = 1;
                $m['tr_id'] = $tr_id;
                $m['chosen'] = 1;
//				$data[] = $m;
                DAO::execute($link, "insert into student_milestones (framework_id, qualification_id, internaltitle, unit_id, month_1, month_2, month_3, month_4, month_5, month_6, month_7, month_8,  month_9, month_10, month_11, month_12, month_13, month_14, month_15, month_16, month_17, month_18, month_19, month_20, month_21, month_22, month_23, month_24, month_25, month_26, month_27, month_28, month_29, month_30, month_31, month_32, month_33, month_34, month_35, month_36, id, tr_id, chosen) values ($framework_id, '$qualification_id','$internaltitle','$unit_id',$m[0],$m[1],$m[2],$m[3],$m[4],$m[5],$m[6],$m[7],$m[8],$m[9],$m[10],$m[11],$m[12],$m[13],$m[14],$m[15],$m[16],$m[17],$m[18],$m[19],$m[20],$m[21],$m[22],$m[23],$m[24],$m[25],$m[26],$m[27],$m[28],$m[29],$m[30],$m[31],$m[32],$m[33],$m[34],$m[35],1,$tr_id,1)");

            }
            //DAO::multipleRowInsert($link, "student_milestones", $data);
		}
	}
}
?>