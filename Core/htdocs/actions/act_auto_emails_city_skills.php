<?php
class auto_emails_city_skills implements IAction
{
    public function execute(PDO $link)
    {
        /*$review_id = $row['review_id'];
        $review_date = Date::toShort($row['review_date']);
        $tr_id = DAO::getSingleValue($link, "select tr_id from assessor_review where id = '$review_id'");
        $source=2;
        $key = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
        $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
        $assessor_id = $training_record->assessor;
        if(in_array($tr_id, Array(29951,28959,29965,29029,29008,29297)))
            $mailtolearner = $training_record->home_email; //Mailto here
        else
            $mailtolearner = $training_record->learner_work_email; //Mailto here
        $client = DB_NAME;
        $client = str_replace("am_","",$client);
        $client = str_replace("_","-",$client);
        $assessor_name = DAO::getSingleValue($link, "SELECT concat(firstnames,' ', surname) FROM users WHERE id = '$assessor_id'");

        $from = DAO::getSingleValue($link, "select work_email from users where id = '$training_record->assessor'");*/

        //<img src=\"https://city-skills.sunesis.uk.net/images/city-skills.png\"><br>
        $message = "<html><body>
        Hi,
        <br><br>";

        $rows = DAO::getResultset($link, "SELECT CONCAT(firstnames,' ',surname) AS assessor
        ,(SELECT COUNT(*) FROM tr WHERE assessor = users.id AND status_code = 1) AS learners 
        ,(SELECT COUNT(*) FROM tr WHERE assessor = users.id AND status_code = 1 AND target_date < CURDATE()) AS peed
        ,(SELECT COUNT(*) FROM tr WHERE assessor = users.id AND status_code = 1 AND DATE_ADD(target_date, INTERVAL 90 DAY) > CURDATE()) AS peed_90
        ,(SELECT COUNT(*) FROM assessor_review LEFT JOIN tr ON tr.id = assessor_review.tr_id WHERE YEAR(meeting_date)=YEAR(CURDATE()) AND MONTH(meeting_date)=MONTH(CURDATE()) AND tr_id = tr.id AND tr.assessor = users.id AND tr.status_code = 1) AS reviews
        ,(SELECT COUNT(*) FROM tr WHERE assessor = users.id AND status_code = 1 AND (gateway_date IS NULL OR gateway_date < CURDATE())) AS gateway
        ,(SELECT COUNT(*) FROM assessor_review LEFT JOIN tr ON tr.id = assessor_review.tr_id WHERE MONTH(meeting_date)=8 AND YEAR(meeting_date)=(YEAR(CURDATE())-1) AND tr_id = tr.id AND tr.assessor = users.id AND tr.status_code = 1) AS aug
        ,(SELECT COUNT(*) FROM assessor_review LEFT JOIN tr ON tr.id = assessor_review.tr_id WHERE MONTH(meeting_date)=9 AND YEAR(meeting_date)=(YEAR(CURDATE())-1) AND tr_id = tr.id AND tr.assessor = users.id AND tr.status_code = 1) AS sep
        ,(SELECT COUNT(*) FROM assessor_review LEFT JOIN tr ON tr.id = assessor_review.tr_id WHERE MONTH(meeting_date)=10 AND YEAR(meeting_date)=(YEAR(CURDATE())-1) AND tr_id = tr.id AND tr.assessor = users.id AND tr.status_code = 1) AS `oct`
        ,(SELECT COUNT(*) FROM assessor_review LEFT JOIN tr ON tr.id = assessor_review.tr_id WHERE MONTH(meeting_date)=11 AND YEAR(meeting_date)=(YEAR(CURDATE())-1) AND tr_id = tr.id AND tr.assessor = users.id AND tr.status_code = 1) AS nov
        ,(SELECT COUNT(*) FROM assessor_review LEFT JOIN tr ON tr.id = assessor_review.tr_id WHERE MONTH(meeting_date)=12 AND YEAR(meeting_date)=(YEAR(CURDATE())-1) AND tr_id = tr.id AND tr.assessor = users.id AND tr.status_code = 1) AS `dec`
        ,(SELECT COUNT(*) FROM assessor_review LEFT JOIN tr ON tr.id = assessor_review.tr_id WHERE MONTH(meeting_date)=1 AND YEAR(meeting_date)=(YEAR(CURDATE())) AND tr_id = tr.id AND tr.assessor = users.id AND tr.status_code = 1) AS jan
        ,(SELECT COUNT(*) FROM assessor_review LEFT JOIN tr ON tr.id = assessor_review.tr_id WHERE MONTH(meeting_date)=2 AND YEAR(meeting_date)=(YEAR(CURDATE())) AND tr_id = tr.id AND tr.assessor = users.id AND tr.status_code = 1) AS feb
        ,(SELECT COUNT(*) FROM assessor_review LEFT JOIN tr ON tr.id = assessor_review.tr_id WHERE MONTH(meeting_date)=3 AND YEAR(meeting_date)=(YEAR(CURDATE())) AND tr_id = tr.id AND tr.assessor = users.id AND tr.status_code = 1) AS mar
        ,(SELECT COUNT(*) FROM assessor_review LEFT JOIN tr ON tr.id = assessor_review.tr_id WHERE MONTH(meeting_date)=4 AND YEAR(meeting_date)=(YEAR(CURDATE())) AND tr_id = tr.id AND tr.assessor = users.id AND tr.status_code = 1) AS apr
        ,(SELECT COUNT(*) FROM assessor_review LEFT JOIN tr ON tr.id = assessor_review.tr_id WHERE MONTH(meeting_date)=5 AND YEAR(meeting_date)=(YEAR(CURDATE())) AND tr_id = tr.id AND tr.assessor = users.id AND tr.status_code = 1) AS may
        ,(SELECT COUNT(*) FROM assessor_review LEFT JOIN tr ON tr.id = assessor_review.tr_id WHERE MONTH(meeting_date)=6 AND YEAR(meeting_date)=(YEAR(CURDATE())) AND tr_id = tr.id AND tr.assessor = users.id AND tr.status_code = 1) AS jun
        ,(SELECT COUNT(*) FROM assessor_review LEFT JOIN tr ON tr.id = assessor_review.tr_id WHERE MONTH(meeting_date)=7 AND YEAR(meeting_date)=(YEAR(CURDATE())) AND tr_id = tr.id AND tr.assessor = users.id AND tr.status_code = 1) AS jul
        FROM users
        WHERE TYPE = 3 AND active = 1 AND id IN (SELECT assessor FROM tr WHERE status_code = 1)
        ORDER BY firstnames, surname
        ;");
        $message.="<table border=\"1\" cellspacing=\"0\" cellpadding=\"8\">
        <thead>
            <tr style=\"background-color: #4CAF50; color: white;\">
                <th>Assessor</th>
                <th>Numbers in learning <br>(Excl PPED)</th>
                <th>PPED</th>
                <th>PPEDin the next 3 months</th>
                <th>Total PRs in current month</th>
                <th>No of missing gateways</th>
                <th>Actual PRs Aug</th>
                <th>Actual PRs Sep</th>
                <th>Actual PRs Oct</th>
                <th>Actual PRs Nov</th>
                <th>Actual PRs Dec</th>
                <th>Actual PRs Jan</th>
                <th>Actual PRs Feb</th>
                <th>Actual PRs Mar</th>
                <th>Actual PRs Apr</th>
                <th>Actual PRs May</th>
                <th>Actual PRs Jun</th>
                <th>Actual PRs Jul</th>
            </tr>
        </thead>
        <tbody>";
        foreach($rows as $row)
        {
            $message.="<tr style=\"background-color: #f2f2f2;\">";
            $message.="<td>" . $row[0] . "</td>";
            $cont = $row[1]-$row[2];
            $message.="<td>" . $cont . "</td>";
            $message.="<td>" . $row[2] . "</td>";
            $message.="<td>" . $row[3] . "</td>";
            $message.="<td>" . $row[4] . "</td>";
            $message.="<td>" . $row[5] . "</td>";
            $message.="<td>" . $row[6] . "</td>";
            $message.="<td>" . $row[7] . "</td>";
            $message.="<td>" . $row[8] . "</td>";
            $message.="<td>" . $row[9] . "</td>";
            $message.="<td>" . $row[10] . "</td>";
            $message.="<td>" . $row[11] . "</td>";
            $message.="<td>" . $row[12] . "</td>";
            $message.="<td>" . $row[13] . "</td>";
            $message.="<td>" . $row[14] . "</td>";
            $message.="<td>" . $row[15] . "</td>";
            $message.="<td>" . $row[16] . "</td>";
            $message.="<td>" . $row[17] . "</td>";
            $message.="</tr>";
        }
        $message.="</tbody></table><br><br>";

        $no_assessors = DAO::getSingleValue($link, "select count(*) from tr where assessor is null");
        if($no_assessors)
        {
            $rows = DAO::getResultset($link, "SELECT CONCAT(firstnames,' ',surname) AS learner
            FROM tr
            WHERE assessor is null and status_code = 1
            ORDER BY firstnames, surname
            ;");
            $message.="<table border=\"1\" cellspacing=\"0\" cellpadding=\"8\">
            <thead>
                <tr style=\"background-color: #4CAF50; color: white;\">
                    <th>Leaners with no assessor</th>
                </tr>
            </thead>
            <tbody>";
            foreach($rows as $row)
            {
                $message.="<tr style=\"background-color: #f2f2f2;\">";
                $message.="<td>" . $row[0] . "</td>";
            }
            $message.="</tbody></table>";
        }

        $message.="</body></html>";

        $subject = "Weekly Report";
        $success1 = Emailer::weekly_emails_city_skills("k.khan@perspective-uk.com;rich.holmden@city-skills.com", 'apprenticeships@perspective-uk.com', "support@perspective-uk.com", $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
        //$success1 = Emailer::weekly_emails_city_skills("k.khan@perspective-uk.com", 'apprenticeships@perspective-uk.com', "support@perspective-uk.com", $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
        pre("Please check your email in a few seconds");
        http_redirect('do.php?_action=home_page');
    }
}