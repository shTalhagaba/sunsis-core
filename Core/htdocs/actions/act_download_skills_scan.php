<?php
class download_skills_scan implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $meeting_date = isset($_REQUEST['meeting_date']) ? $_REQUEST['meeting_date'] : '';
        $source = isset($_REQUEST['source']) ? $_REQUEST['source'] : '';
        $review_id = isset($_REQUEST['review_id']) ? $_REQUEST['review_id'] : '';
        $key = isset($_REQUEST['key']) ? $_REQUEST['key'] : '';
        $output = isset($_REQUEST['output']) ? $_REQUEST['output'] : '';

        include("./MPDF57/mpdf.php");

        $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);

        $framework_id = DAO::getSingleValue($link, "SELECT framework_id FROM courses_tr WHERE tr_id = '$tr_id'");
        if($framework_id==378 or $framework_id==404 or $framework_id==405)
        {
            $count=DAO::getSingleValue($link, "select count(*) from skills_scan where tr_id = '$tr_id'");
            if($count==0)
                DAO::execute($link,"INSERT INTO skills_scan SELECT NULL,id,'$tr_id','' FROM `lookup_skills_scan` WHERE framework_id = '$framework_id';");

            $st = DAO::query($link, "SELECT skills_scan.id,`lookup_skills_scan`.`description`,`lookup_skills_scan`.`description2`,skills_scan.`grade`, lookup_skills_scan.category
                                    FROM skills_scan LEFT JOIN  `lookup_skills_scan` ON `lookup_skills_scan`.`id` = skills_scan.`plan_id`
                                    AND lookup_skills_scan.`framework_id` = '$framework_id'
                                    WHERE tr_id = '$tr_id';");
            $ss_rows = '';
            $category = "";
            while($row = $st->fetch())
            {
                if($category!=$row['category'])
                {
                    if($category!="")
                        $ss_rows .= "</table></fieldset><br>";
                    $ss_rows .= "<fieldset><legend>" . $row['category'] . "</legend>";
                    $ss_rows .= '<table class = "table1" border="0" cellspacing="2" cellpadding="10" style="margin-left:5px">';
                    $ss_rows .= "<tr><th>Criteria</th><th>Grade</th></tr>";
                    $category=$row['category'];
                }
                $ss_rows .= '<tr>';
                $ss_rows .= '<td valign="top">'.$row['description'].'</td>';
                $ss_rows .= '<td valign="top"><input type="text" name="ss|' . $row['id'] . '" value="' . $row['grade'] .  '" size=5/></td>';
                $ss_rows .= '</tr>';
            }
            $ss_rows .= "</table></fieldset><br>";

            $return_html = <<<HTML
<form name="frmSkillsScan" action="do.php?_action=save_skills_scan" method="post">
	<input type="hidden" name="tr_id" value="$tr_id">
	<input type="hidden" name="framework_id" value="$framework_id">
			$ss_rows
	<br>
</form>
<br>
HTML;



        }
        else
        {

            $st = DAO::query($link, "SELECT skills_scan.id,`lookup_skills_scan`.`description`,`lookup_skills_scan`.`description2`,skills_scan.`grade`,skills_scan.`grade2`,skills_scan.`grade3`,skills_scan.`grade4` FROM skills_scan LEFT JOIN  `lookup_skills_scan` ON `lookup_skills_scan`.`id` = skills_scan.`plan_id`
AND lookup_skills_scan.`framework_id` = '$framework_id'
WHERE tr_id = '$tr_id';");
            $ss_rows = '';
            while($row = $st->fetch())
            {
                $ss_rows .= '<tr>';
                $ss_rows .= '<td valign="top">'.$row['description'].'</td>';
                $ss_rows .= '<td valign="top">'.$row['description2'].'</td>';
                $ss_rows .= '<td valign="top"><input type="text" name="ss|' . $row['id'] . '" value="' . $row['grade'] .  '" size=5/></td>';
                $ss_rows .= '<td valign="top"><input type="text" name="ss2|' . $row['id'] . '" value="' . $row['grade2'] .  '" size=5/></td>';
                $ss_rows .= '<td valign="top"><input type="text" name="ss3|' . $row['id'] . '" value="' . $row['grade3'] .  '" size=5/></td>';
                $ss_rows .= '<td valign="top"><input type="text" name="ss4|' . $row['id'] . '" value="' . $row['grade4'] .  '" size=5/></td>';
                $ss_rows .= '</tr>';
            }


            $st2 = DAO::query($link, "SELECT technical_knowledge.id,`lookup_technical_knowledge`.`description`,`lookup_technical_knowledge`.`description2`,technical_knowledge.`grade`,technical_knowledge.`grade2`,technical_knowledge.`grade3`,technical_knowledge.`grade4` FROM technical_knowledge LEFT JOIN  `lookup_technical_knowledge` ON `lookup_technical_knowledge`.`id` = technical_knowledge.`plan_id`
AND lookup_technical_knowledge.`framework_id` = '$framework_id'
WHERE tr_id = '$tr_id';");
            $tk_rows = '';
            while($row2 = $st2->fetch())
            {
                $tk_rows .= '<tr>';
                $tk_rows .= '<td valign="top">'.$row2['description'].'</td>';
                $tk_rows .= '<td valign="top">'.$row2['description2'].'</td>';
                $tk_rows .= '<td valign="top"><input type="text" name="tk|' . $row2['id'] . '" value="' . $row2['grade'] .  '" size=5/></td>';
                $tk_rows .= '<td valign="top"><input type="text" name="tk2|' . $row2['id'] . '" value="' . $row2['grade2'] .  '" size=5/></td>';
                $tk_rows .= '<td valign="top"><input type="text" name="tk3|' . $row2['id'] . '" value="' . $row2['grade3'] .  '" size=5/></td>';
                $tk_rows .= '<td valign="top"><input type="text" name="tk4|' . $row2['id'] . '" value="' . $row2['grade4'] .  '" size=5/></td>';
                $tk_rows .= '</tr>';
            }

            $st3 = DAO::query($link, "SELECT attitudes_behaviours.id,`lookup_attitudes_behaviours`.`description`,attitudes_behaviours.`grade`,attitudes_behaviours.`grade2`,attitudes_behaviours.`grade3`,attitudes_behaviours.`grade4` FROM attitudes_behaviours LEFT JOIN  `lookup_attitudes_behaviours` ON `lookup_attitudes_behaviours`.`id` = attitudes_behaviours.`plan_id`
AND lookup_attitudes_behaviours.`framework_id` = '$framework_id'
WHERE tr_id = '$tr_id';");
            $ab_rows = '';
            while($row3 = $st3->fetch())
            {
                $ab_rows .= '<tr>';
                $ab_rows .= '<td valign="top">'.$row3['description'].'</td>';
                $ab_rows .= '<td valign="top"><input type="text" name="ab|' . $row3['id'] . '" value="' . $row3['grade'] .  '" size=5/></td>';
                $ab_rows .= '<td valign="top"><input type="text" name="ab2|' . $row3['id'] . '" value="' . $row3['grade2'] .  '" size=5/></td>';
                $ab_rows .= '<td valign="top"><input type="text" name="ab3|' . $row3['id'] . '" value="' . $row3['grade3'] .  '" size=5/></td>';
                $ab_rows .= '<td valign="top"><input type="text" name="ab4|' . $row3['id'] . '" value="' . $row3['grade4'] .  '" size=5/></td>';
                $ab_rows .= '</tr>';
            }



            $html = <<<HTML
<form name="frmSkillsScan" action="do.php?_action=save_skills_scan" method="post">
	<input type="hidden" name="tr_id" value="$tr_id">
	<input type="hidden" name="framework_id" value="$framework_id">
	<fieldset>
		<H1>Competencies</H1>
		<table class="table1" border="0" cellspacing="2" cellpadding="10" style="margin-left:5px">
        <tbody>
			<tr>
				<th>Competency</th><th>Description</th><th>Starting Point Grade</th><th>Month 6 Review</th><th>Month 12 Review</th><th>Month 18 Review</th>
			</tr>
			$ss_rows
		</table>
	</fieldset>
	<br>
	<fieldset>
		<H1>Technical Knowledge</H1>
		<table class="table1" border="0" cellspacing="2" cellpadding="10" style="margin-left:5px">
		<tbody>
			<tr>
				<th>Technical Knowledge</th><th>Description</th><th>Starting Point Grade</th><th>Month 6 Review</th><th>Month 12 Review</th><th>Month 18 Review</th>
			</tr>
			$tk_rows
        </tbody>
		</table>
	</fieldset>
	<br>
	<fieldset>
		<H1>Attitudes & Behaviours</H1>
		<table class="table1" border="0" cellspacing="2" cellpadding="10" style="margin-left:5px">
		<tbody>
			<tr>
				<th>Attitudes & Behaviours</th><th>Starting Point Grade</th><th>Month 6 Review</th><th>Month 12 Review</th><th>Month 18 Review</th>
			</tr>
			$ab_rows
        </tbody>
		</table>
	</fieldset>
</form>
<br>
HTML;

        }



        $mpdf=new mPDF('D');

        $mpdf->SetDisplayMode('fullpage');

        $stylesheet = file_get_contents('./MPDF57/examples/baltic.css');
        $mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

        $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
        $filename = $training_record->firstnames . ' ' . $training_record->surname . ' - Skills Scan.pdf';
        $mpdf->Output($filename,'D');
        exit;
    }
}
?>