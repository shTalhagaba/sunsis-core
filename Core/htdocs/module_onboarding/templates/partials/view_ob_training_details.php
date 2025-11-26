<?php
$tr_id_valid = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr WHERE tr.id = '{$vo->linked_tr_id}'");
if($tr_id_valid > 0)
{
    $objTr = TrainingRecord::loadFromDatabase($link, $vo->linked_tr_id);
}
else
{
    $objTr = new TrainingRecord();
}
?>

<div class="row">
    <div class="col-sm-12">
        <span class="lead text-green text-bold">Training Record Details</span>
        <table class="table table -bordered table-condensed">
            <tr>
                <th>Contract:</th>
                <td><?php echo DAO::getSingleValue($link, "SELECT contracts.title FROM contracts WHERE contracts.id = '{$objTr->contract_id}'"); ?></td>
            </tr>
            <tr>
                <th>Training Dates:</th>
                <td>Start Date: <?php echo Date::toShort($objTr->start_date); ?> &nbsp; &nbsp; Planned End Date (including EPA): <?php echo Date::toShort($objTr->end_date_inc_epa); ?></td>
            </tr>
            <tr>
                <th>Practical Period Dates:</th>
                <td>Start Date: <?php echo Date::toShort($vo->practical_start_date); ?> &nbsp; &nbsp; End Date: <?php echo Date::toShort($vo->practical_end_date); ?></td>
            </tr>
            <tr>
                <th>Weeks on Programme:</th>
                <td><?php echo $vo->weeks_on_programme; ?> weeks</td>
            </tr>
            <tr>
                <th>Statutory Annual Leave:</th>
                <td><?php echo $vo->statutory_annual_leave; ?> days</td>
            </tr>
            <tr>
                <th>Normal Weekly Hours:</th>
                <td><?php echo $vo->emp_q7; ?> hours</td>
            </tr>
            <tr>
                <th>Off-the-job training (hours):</th>
                <td><?php echo $objTr->otj_hours; ?> hours</td>
            </tr>
            <tr>
                <th>Course:</th>
                <td><?php echo DAO::getSingleValue($link, "SELECT title FROM courses INNER JOIN courses_tr ON courses.id = courses_tr.course_id WHERE courses_tr.tr_id = '{$objTr->id}'"); ?></td>
            </tr>
        </table>
    </div>
</div>
