<?php 
$employer_agreement_done = DAO::getSingleValue($link, "SELECT COUNT(*) FROM employer_agreements WHERE employer_id = '{$tr->employer_id}' AND employer_sign IS NOT NULL AND provider_sign IS NOT NULL ORDER BY id DESC");
if($employer_agreement_done == 0 && DB_NAME != "am_ela")
{
    echo '<div class="callout callout-danger">';
    echo '<span class="text-bold"><i class="fa fa-warning"></i> Employer Contract Not Completed</span><br>';
    echo 'Employer Contract must be completed and full signed by the Employer and Training Provider before creating this initial contract. <br>';
    echo '</div>';

}
?>

<div class="callout callout-info">
    <i class="fa fa-info-circle"></i> Initial/Apprenticeship contract is signed by Training Provider and Employer. <br>
    <i class="fa fa-info-circle"></i> Please sign the contract and then send an email to Employer in order to get Employer's signature.
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <caption class="text-bold text-center bg-gray">Existing Contracts</caption>
        <tr>
            <th>Created At</th>
            <th>Created By</th>
            <th>Last Updated At</th>
            <th>Signed by Employer</th>
            <th>Signed by Provider</th>
            <th></th>
            <th></th>
        </tr>
        <?php
        $sql = <<<SQL
SELECT
 employer_agreement_schedules.id,
 employer_agreement_schedules.detail,
 employer_agreement_schedules.tr_id,
 employer_agreement_schedules.created_at,
 employer_agreement_schedules.updated_at,
 employer_agreement_schedules.emp_sign, employer_agreement_schedules.emp_sign_date,
 employer_agreement_schedules.tp_sign, employer_agreement_schedules.tp_sign_date,
 (SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = employer_agreement_schedules.created_by) AS created_by 
FROM
  employer_agreement_schedules WHERE tr_id = '{$tr->id}' ORDER BY created_by DESC;                                
SQL;
        $records_initial_contracts = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        foreach ($records_initial_contracts as $row) {
            $detail = json_decode($row['detail']);

            echo '<tr>';
            echo '<td>' . Date::to($row['created_at'], Date::DATETIME) . '</td>';
            echo '<td>' . $row['created_by'] . '</td>';
            echo '<td>' . Date::to($row['updated_at'], Date::DATETIME) . '</td>';
            echo '<td>';
            echo $row['emp_sign'] != '' ? 'Yes on ' . Date::toShort($row['emp_sign_date']) : 'No';
            echo '</td>';
            echo '<td>';
            echo $row['tp_sign'] != '' ? 'Yes on ' . Date::toShort($row['tp_sign_date']) : 'No';
            echo '</td>';
            echo '<td>';
            if($row['emp_sign'] != '' && $row['tp_sign'] != '')
            {
                echo isset($detail->practical_period_start_date) ? 'Practical Period Start Date: ' . $detail->practical_period_start_date . '<br>' : '';
                echo isset($detail->practical_period_end_date) ? 'Practical Period End Date: ' . $detail->practical_period_end_date . '<br>' : '';
                echo isset($detail->planned_epa_date) ? 'Planned EPA Date: ' . $detail->planned_epa_date . '<br>' : '';
                echo isset($detail->contracted_hours_per_week) ? 'Contracted Hours Per Week: ' . $detail->contracted_hours_per_week . '<br>' : '';
                echo isset($detail->weeks_to_be_worked_per_year) ? 'Weeks to be Worked Per Year: ' . $detail->weeks_to_be_worked_per_year . '<br>' : '<br>';
                echo (isset($detail->section15radio) && $detail->section15radio == 2) ? 
                    '<span class="text-warning">Employer has selected the following option:<br>I confirm that apprentice(s) named in this contract has/have been issued with a contract of employment and is/will be employed for at least 16 hours per week. I am aware that the duration of the apprenticeship will be extended accordingly to take account of this.</span>: '
                     : '';
            }
            echo '</td>';
            echo '<td>';
            echo '<span class="btn btn-xs btn-info" onclick="window.location.href=\'do.php?_action=edit_initial_contract&id=' . $row['id'] . '&tr_id=' . $row['tr_id'] . '\'"><i class="fa fa-folder-open"></i> View/Edit</span> &nbsp; ';
            echo '<span class="btn btn-xs btn-primary" onclick="load_and_prepare_initial_contract_email(\''.$row['id'].'\');"><i class="fa fa-envelope"></i> Email</span> &nbsp;';
            if($row['emp_sign'] == '' && $row['tp_sign'] == '' && $_SESSION['user']->isAdmin())
            {
		echo '<span class="btn btn-xs btn-danger" onclick="delete_initial_contract(\''.$row['id'].'\');"><i class="fa fa-trash"></i> Delete</span> &nbsp;';    
            }
            echo '</td>';
            echo '</tr>';
        }

        ?>
    </table>
</div>