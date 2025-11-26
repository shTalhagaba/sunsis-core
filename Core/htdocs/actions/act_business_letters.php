<?php
class business_letters implements IAction
{
    public function execute(PDO $link)
    {
        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=business_letters", "View Business Letters");

        // Presentation
        include('tpl_business_letters.php');
    }


    public function renderAssessorAutoEmails(PDO $link, $tr_id)
    {
        if(DB_NAME=='am_baltic')
        {
            $sql = '
        SELECT *, (SELECT legal_name FROM organisations WHERE organisations.id = tr.`employer_id`) AS employer_name FROM forms_audit
        INNER JOIN assessor_review ON assessor_review.`id` = forms_audit.`form_id` AND assessor_review.`tr_id` in (select id from tr where status_code = 1)
        LEFT JOIN tr ON assessor_review.`tr_id` = tr.id
        WHERE description IN ("Review Form 72HR Bsuiness Letter","Review Form 168HR Bsuiness Letter","Review Form 192HR Business Letter - Assessor","Review Form 5 Days Business Letter - Employer","Review Form 10 Days Business Letter - Employer")
        AND forms_audit.`form_id` NOT IN (SELECT review_id FROM assessor_review_forms_employer WHERE signature_employer_font IS NOT NULL)
        AND forms_audit.`form_id` NOT IN (SELECT review_id FROM arf_introduction WHERE signature_employer_font IS NOT NULL)
        order by forms_audit.date desc;';
        }
        elseif(DB_NAME=="am_baltic_demo")
        {
            $sql = '
        SELECT *, (SELECT legal_name FROM organisations WHERE organisations.id = tr.`employer_id`) AS employer_name FROM forms_audit
        INNER JOIN assessor_review ON assessor_review.`id` = forms_audit.`form_id` AND assessor_review.`tr_id` in (select id from tr where status_code = 1)
        LEFT JOIN tr ON assessor_review.`tr_id` = tr.id
        WHERE description IN ("Review Form 192HR Business Letter - Assessor","Review Form 5 Days Business Letter - Employer","Review Form 10 Days Business Letter - Employer","Review Form 10 Days Business Letter - Employer")
        AND forms_audit.`form_id` NOT IN (SELECT review_id FROM assessor_review_forms_employer WHERE signature_employer_font IS NOT NULL)
        AND forms_audit.`form_id` NOT IN (SELECT review_id FROM arf_introduction WHERE signature_employer_font IS NOT NULL)
        order by forms_audit.date desc;';
        }

        $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        echo '<table id="tblLearners" class="table table-bordered table-hover">';
        echo '<thead class="bg-gray"><tr><th>Firstname(s)</th><th>Surname</th><th>Review Date</th><th>Description</th><th>Date/time</th><th>Contents</th><th>Employer Name</th><th>Download Count</th></tr></thead>';
        echo '<tbody>';
        foreach($records AS $row)
        {
            $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM assessor_review_forms_assessor1 WHERE review_id = {$row['form_id']}"));
            echo '<tr>';
            echo '<td>' . $row['firstnames'].'</td>';
            echo '<td>' . $row['surname'] . '</td>';
            echo '<td>' . Date::toShort($row['meeting_date']) . '</td>';
            echo '<td>' . str_replace("Bsuiness", "Business", $row['description']) . '</td>';
            echo '<td>' . Date::to($row['date'], Date::DATETIME) . '</td>';
            echo "<td style='text-align: center'><a href='do.php?_action=generate_email_pdf&counter=1&tr_id={$row['tr_id']}&review_id={$row['form_id']}&desc={$row['description']}'><img src='/images/pdf_icon.png' width='32px' height='32px'/></a></td>";
            echo '<td style="text-align: left">' . HTML::cell($row['employer_name']) . '</td>';
            echo '<td style="text-align: center">' . HTML::cell($row['smart_assessor_id']) . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';

        echo '</table><br><br>';
    }
}
?>