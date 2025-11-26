<div class="row">
    <div class="col-sm-12">
        <p class="lead text-bold">Section 2 - Learning Support / Additional Details</p>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <caption class="text-bold bg-gray-light">Learning Support</caption>
            <tr>
                <th>Date Discussed</th>
                <th>Support Required</th>
                <th>Details</th>
                <th>Date Claimed From</th>
            </tr>
            <?php
            $result = DAO::getResultset($link, "SELECT * FROM ob_learner_als WHERE tr_id = '{$tr->id}'", DAO::FETCH_ASSOC);
            foreach($result AS $row)
            {
                echo '<tr>';
                echo '<td>' . Date::toShort($row['date_discussed']) . '</td>';
                echo $row['support_required'] == 'Y' ? '<td>Yes</td>' : '<td>No</td>';
                echo '<td>' . $row['details'] . '</td>';
                echo '<td>' . Date::toShort($row['date_claimed_from']) . '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
</div>

