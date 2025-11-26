<form class="form-horizontal" name="frmEmployments" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />
    <input type="hidden" name="_action" value="save_employments" />
    <div class="row">
        <div class="col-sm-12">

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h2 class="box-title">Details</h2>
                    <div class="pull-right box-tools">
                        <span class="btn btn-sm btn-primary" onclick="save_employments();">
                            <i class="fa fa-save"></i> Save Employments
                        </span>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table table-responsive row-border cw-table-list">
                        <tr>
                            <th style="width: 15%;">Date From</th>
                            <th style="width: 15%;">Date To</th>
                            <th style="width: 20%;">Employer</th>
                            <th style="width: 20%;">Role</th>
                            <th style="width: 30%;">Responsibilities</th>
                        </tr>
                        <?php
                        $saved_employment_records = DAO::getResultset($link, "SELECT * FROM ob_learners_ea WHERE tr_id = '{$tr->id}'", DAO::FETCH_ASSOC);

                        for ($i = 1; $i <= 8; $i++) {
                            if (isset($saved_employment_records[$i - 1]['tr_id']) && isset($saved_employment_records[$i - 1]['tr_id']) == $tr->id) {
                                echo '<tr>';
                                echo '<td><input class="datecontrol form-control" type="text" name="ea_date_from' . $i . '" id="input_ea_date_from' . $i . '" value="' . Date::toShort($saved_employment_records[$i - 1]['ea_date_from']) . '" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td>';
                                echo '<td><input class="datecontrol form-control" type="text" name="ea_date_to' . $i . '" id="input_ea_date_to' . $i . '" value="' . Date::toShort($saved_employment_records[$i - 1]['ea_date_to']) . '" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td>';
                                echo '<td><input class="form-control" type="text" name="ea_employer' . $i . '" id="ea_employer' . $i . '" value="' . $saved_employment_records[$i - 1]['ea_employer'] . '" /></td>';
                                echo '<td><input class="form-control" type="text" name="ea_role' . $i . '" id="ea_role' . $i . '" value="' . $saved_employment_records[$i - 1]['ea_role'] . '" /></td>';
                                echo '<td><textarea name="ea_resp' . $i . '" id="ea_resp' . $i . '" rows="3" style="width: 100%;">' . nl2br($saved_employment_records[$i - 1]['ea_resp'] ?? '') . '</textarea></td>';
                                echo '</tr>';
                            } else {
                                echo '<tr>';
                                echo '<td><input class="datecontrol form-control" type="text" name="ea_date_from' . $i . '" id="input_ea_date_from' . $i . '" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td>';
                                echo '<td><input class="datecontrol form-control" type="text" name="ea_date_to' . $i . '" id="input_ea_date_to' . $i . '" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td>';
                                echo '<td><input class="form-control" type="text" name="ea_employer' . $i . '" id="ea_employer' . $i . '" value="" /></td>';
                                echo '<td><input class="form-control" type="text" name="ea_role' . $i . '" id="ea_role' . $i . '" value="" /></td>';
                                echo '<td><textarea name="ea_resp' . $i . '" id="ea_resp' . $i . '" rows="3" style="width: 100%;"></textarea></td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>