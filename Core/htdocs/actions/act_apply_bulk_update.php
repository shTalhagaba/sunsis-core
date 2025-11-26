<?php
class apply_bulk_update implements IAction
{
    public function execute(PDO $link)
    {
        $username = $_SESSION['user']->username;
        DAO::execute($link, "UPDATE student_qualifications
        INNER JOIN tr ON tr.id = student_qualifications.tr_id
        INNER JOIN bulk_update2 ON bulk_update2.value_1 = tr.uln AND bulk_update2.value_2 = REPLACE(student_qualifications.id,'/','')
        SET student_qualifications.awarding_body_reg = value_3
        ,student_qualifications.awarding_body_date = STR_TO_DATE(value_4, '%d/%m/%Y')
        ,student_qualifications.certificate_applied = STR_TO_DATE(value_5, '%d/%m/%Y')
        ,student_qualifications.certificate_received = STR_TO_DATE(value_6, '%d/%m/%Y')
        ,student_qualifications.certificate_no = value_7
        ,student_qualifications.certificate_post_date = STR_TO_DATE(value_8, '%d/%m/%Y')
        ,student_qualifications.awarding_body_expiry_date = STR_TO_DATE(value_9, '%d/%m/%Y')
        ,student_qualifications.candidate_no = value_10
        ,student_qualifications.awarding_body_batch = value_11
        ");

        /*DAO::execute($link, "insert into bulk_update_audit SELECT CONCAT(tr.firstnames, ' ',tr.surname) AS learner, CONCAT(\"Assessor was changed to \", CONCAT(users.firstnames, \" \", users.surname)) AS comm, NOW(), '$username' FROM bulk_update2 
        LEFT JOIN tr ON tr.id = bulk_update2.value_1 AND tr.uln = bulk_update2.value_2
        LEFT JOIN users ON users.id = value_5
        WHERE value_4 IS NOT NULL AND value_5 IS NOT NULL;");*/
        pre("Updates applied");
    }
}