<?php 
$ilp_form = DAO::getObject($link, "SELECT * FROM ob_learner_ilp_form WHERE tr_id = '{$tr->id}'");
if(!isset($ilp_form->tr_id)) {
    $ilp_form = new stdClass();
    $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM ob_learner_ilp_form");
    foreach($records AS $_key => $value)
        $ilp_form->$value = null;
    $ilp_form->tr_id = $tr->id;
}
$ilp_form->form_data = is_null($ilp_form->form_data) ? new stdClass() : json_decode($ilp_form->form_data);
?>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmIlpForm" id="frmIlpForm">
                <input type="hidden" name="_action" value="save_ilp_form">
                <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>">

                <table class="table table-bordered">
                    <tr>
                        <td style="width: 50%;">
                            <span class="text-bold">Specific Goal</span><br>
                            What are you going to achieve? What do you want to develop or improve whilst onthe course?
                        </td>
                        <td>
                            <textarea class="form-control" name="specific_goal" id="specific_goal" rows="5" ><?php echo isset($ilp_form->form_data->specific_goal) ? $ilp_form->form_data->specific_goal : ''; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%;">
                            <span class="text-bold">Measurable</span><br>
                            How will you know when you have achieved this goal? How will others know?
                        </td>
                        <td>
                            <textarea class="form-control" name="measurable" id="measurable" rows="5" ><?php echo isset($ilp_form->form_data->measurable) ? $ilp_form->form_data->measurable : ''; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%;">
                            <span class="text-bold">Achievable</span><br>
                            What is needed to achieve this goal? Skills, knowledge, resources, support, time, etc.
                        </td>
                        <td>
                            <textarea class="form-control" name="achievable" id="achievable" rows="5" ><?php echo isset($ilp_form->form_data->achievable) ? $ilp_form->form_data->achievable : ''; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%;">
                            <span class="text-bold">Realistic</span><br>
                            What steps can I put in place to achieve this goal?
                        </td>
                        <td>
                            <textarea class="form-control" name="realistic" id="realistic" rows="5" ><?php echo isset($ilp_form->form_data->realistic) ? $ilp_form->form_data->realistic : ''; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%;">
                            <span class="text-bold">Timescale</span><br>
                            When will I achieve the goal by? It is a short/medium or long term goal? Dates?
                        </td>
                        <td>
                            <textarea class="form-control" name="timescale" id="timescale" rows="5" ><?php echo isset($ilp_form->form_data->timescale) ? $ilp_form->form_data->timescale : ''; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button type="submit" class="btn btn-block btn-sm btn-success"><i class="fa fa-save"></i> Save Information</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>

<?php 
unset($ilp_form);
?>